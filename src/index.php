<?php
// Session + Timezone
date_default_timezone_set('Asia/Ho_Chi_Minh');
$sessionLifetime = 30 * 24 * 60 * 60;
ini_set('session.gc_maxlifetime', $sessionLifetime);
session_set_cookie_params([
    'lifetime' => $sessionLifetime,
    'path' => '/',
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();

require_once 'config/database.php';
require_once 'config/EnvConfig.php';
require_once 'models/User.php';
require_once 'models/Customer.php';
require_once 'models/FtthLine.php';
require_once 'models/Setting.php';

try { EnvConfig::load(); } catch (Exception $e) { error_log('Failed to load .env: ' . $e->getMessage()); }

$user = new User($pdo);
$customer = new Customer($pdo);
$ftthLine = new FtthLine($pdo);
$setting = new Setting($pdo);

$action = $_GET['action'] ?? 'dashboard';

// Handle logout
if ($action === 'logout') {
    session_destroy();
    header('Location: index.php');
    exit;
}

// ====== API endpoints ======
if ($action === 'api_refresh_status') {
    header('Content-Type: application/json');
    if (!isset($_SESSION['user_id'])) { echo json_encode(['success' => false]); exit; }
    
    $statsStmt = $pdo->query("SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN status = 'up' THEN 1 ELSE 0 END) as up_count,
        SUM(CASE WHEN status = 'down' THEN 1 ELSE 0 END) as down_count,
        SUM(CASE WHEN status = 'warning' THEN 1 ELSE 0 END) as warning_count,
        SUM(CASE WHEN status = 'paused' THEN 1 ELSE 0 END) as paused_count,
        SUM(CASE WHEN status = 'unknown' THEN 1 ELSE 0 END) as unknown_count
        FROM ftth_lines WHERE active = 1 AND is_dynamic_ip = 0");
    $stats = $statsStmt->fetch(PDO::FETCH_ASSOC);
    
    $lines = $ftthLine->getMonitored();
    foreach ($lines as &$l) {
        $l['last_check_fmt'] = $l['last_check'] ? date('d/m H:i:s', strtotime($l['last_check'])) : 'Never';
    }
    
    echo json_encode(['success' => true, 'stats' => $stats, 'lines' => $lines, 'time' => date('H:i:s')]);
    exit;
}

if ($action === 'api_get_branches') {
    header('Content-Type: application/json');
    if (!isset($_SESSION['user_id'])) { echo json_encode(['success' => false]); exit; }
    $customerId = intval($_GET['customer_id'] ?? 0);
    $branches = $customer->getBranchesForDropdown($customerId);
    echo json_encode(['success' => true, 'branches' => $branches]);
    exit;
}

if ($action === 'api_test_telegram') {
    header('Content-Type: application/json');
    if (!isset($_SESSION['user_id'])) { echo json_encode(['success' => false]); exit; }
    $token = $_GET['token'] ?? '';
    $chatId = $_GET['chat_id'] ?? '';
    if (!$token || !$chatId) { echo json_encode(['success' => false, 'error' => 'Token và Chat ID không được để trống']); exit; }
    
    $msg = "✅ *FTTH Monitor - Test Message*\n\nKết nối Telegram thành công!\n⏰ " . date('d/m/Y H:i:s');
    $url = "https://api.telegram.org/bot{$token}/sendMessage";
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query(['chat_id' => $chatId, 'text' => $msg, 'parse_mode' => 'Markdown']),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10
    ]);
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    echo json_encode(['success' => $httpCode === 200, 'error' => $httpCode !== 200 ? 'HTTP ' . $httpCode : null]);
    exit;
}

// ====== Login check ======
if (!isset($_SESSION['user_id'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
        $result = $user->authenticate($_POST['username'], $_POST['password']);
        if ($result === false) {
            $error = 'Tên đăng nhập hoặc mật khẩu không đúng';
            include 'views/login.php';
            exit;
        }
        $_SESSION['user_id'] = $result['id'];
        $_SESSION['user_role'] = $result['role'];
        header('Location: index.php');
        exit;
    }
    include 'views/login.php';
    exit;
}

$currentUser = $user->get($_SESSION['user_id']);
if (!$currentUser || !$currentUser['active']) {
    session_destroy();
    header('Location: index.php');
    exit;
}

// ====== Routing ======
switch ($action) {

    // ---- Dashboard ----
    case 'dashboard':
    default:
        $statsStmt = $pdo->query("SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN status = 'up' THEN 1 ELSE 0 END) as up_count,
            SUM(CASE WHEN status = 'down' THEN 1 ELSE 0 END) as down_count,
            SUM(CASE WHEN status = 'warning' THEN 1 ELSE 0 END) as warning_count,
            SUM(CASE WHEN status = 'paused' THEN 1 ELSE 0 END) as paused_count,
            SUM(CASE WHEN status = 'unknown' THEN 1 ELSE 0 END) as unknown_count
            FROM ftth_lines WHERE active = 1 AND is_dynamic_ip = 0");
        $stats = $statsStmt->fetch(PDO::FETCH_ASSOC);
        $lines = $ftthLine->getMonitored();
        $pingInterval = $setting->get('ping_interval', '30');
        
        // Monthly stats: down/recovery count per line
        $monthStart = date('Y-m-01 00:00:00');
        $monthEnd = date('Y-m-t 23:59:59');
        $monthlyStmt = $pdo->prepare("
            SELECT l.id, l.name, l.ip_address, l.uptime_percent,
                c.name as customer_name,
                (SELECT COUNT(*) FROM alert_log WHERE line_id = l.id AND alert_type = 'down' AND created_at BETWEEN ? AND ?) as down_count,
                (SELECT COUNT(*) FROM alert_log WHERE line_id = l.id AND alert_type = 'recovery' AND created_at BETWEEN ? AND ?) as recovery_count
            FROM ftth_lines l
            LEFT JOIN customers c ON l.customer_id = c.id
            WHERE l.active = 1
            ORDER BY down_count DESC, l.name
        ");
        $monthlyStmt->execute([$monthStart, $monthEnd, $monthStart, $monthEnd]);
        $monthlyStats = $monthlyStmt->fetchAll(PDO::FETCH_ASSOC);
        
        include 'views/dashboard.php';
        break;

    // ---- Settings (Admin only) ----
    case 'settings':
    case 'save_settings':
    case 'users':
    case 'save_user':
    case 'toggle_user':
        if (!$user->hasPermission($currentUser['id'], 'system_settings')) {
            $_SESSION['error_message'] = 'Bạn không có quyền truy cập!';
            header('Location: ?action=dashboard');
            exit;
        }

        if ($action === 'settings') {
            $settings = $setting->getAll();
            include 'views/settings.php';
            break;
        }

        if ($action === 'save_settings' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $setting->saveMultiple([
                'ping_interval' => max(5, intval($_POST['ping_interval'] ?? 30)),
                'telegram_enabled' => $_POST['telegram_enabled'] ?? '0',
                'telegram_bot_token' => trim($_POST['telegram_bot_token'] ?? ''),
                'telegram_chat_id' => trim($_POST['telegram_chat_id'] ?? ''),
                'timezone' => $_POST['timezone'] ?? 'Asia/Ho_Chi_Minh'
            ]);
            $_SESSION['success_message'] = 'Cài đặt đã được lưu!';
            header('Location: ?action=settings');
            exit;
        }

        if ($action === 'users') {
            $users_list = $user->getAll();
            // Fetch permissions for each user to pre-fill the edit modal
            foreach ($users_list as &$u) {
                $u['permissions'] = $user->getUserPermissions($u['id']);
            }
            unset($u); // Fix reference bug
            $all_permissions = $user->getAllPermissions();
            include 'views/users.php';
            break;
        }

        if ($action === 'save_user' && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            if ($id) {
                // Update existing user
                $user->update($id, [
                    'name' => $_POST['name'],
                    'email' => $_POST['email'] ?? null,
                    'role' => $_POST['role'] ?? 'viewer'
                ]);
                // Update password if provided
                if (!empty($_POST['password'])) {
                    $user->setPassword($id, $_POST['password']);
                }
                $_SESSION['success_message'] = 'Cập nhật user thành công!';
            } else {
                // Create new user
                if (empty($_POST['username']) || empty($_POST['password'])) {
                    $_SESSION['error_message'] = 'Username và mật khẩu là bắt buộc!';
                } else {
                    try {
                        $user->create([
                            'username' => $_POST['username'],
                            'name' => $_POST['name'],
                            'email' => $_POST['email'] ?? null,
                            'password' => $_POST['password'],
                            'role' => $_POST['role'] ?? 'viewer'
                        ]);
                $_SESSION['success_message'] = 'Tạo user thành công!';
                    } catch (PDOException $e) {
                        $_SESSION['error_message'] = 'Username đã tồn tại!';
                    }
                }
            }

            // Sync granular permissions if provided (and user ID is known)
            $newUserId = $id ?: ($pdo->lastInsertId() ?: null);
            if ($newUserId && isset($_POST['permissions']) && is_array($_POST['permissions'])) {
                $user->updatePermissions($newUserId, $_POST['permissions']);
            }

            header('Location: ?action=users');
            exit;
        }

        if ($action === 'toggle_user') {
            $id = $_GET['id'] ?? null;
            $active = $_GET['active'] ?? 1;
            if ($id) {
                $user->toggleActive($id, $active);
                $_SESSION['success_message'] = $active ? 'Đã kích hoạt user!' : 'Đã vô hiệu hóa user!';
            }
            header('Location: ?action=users');
            exit;
        }
        break;

    case 'change_password':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentPass = $_POST['current_password'] ?? '';
            $newPass = $_POST['new_password'] ?? '';
            $confirmPass = $_POST['confirm_password'] ?? '';

            // Verify current password
            $currentUser = $user->get($_SESSION['user_id']);
            if (!password_verify($currentPass, $currentUser['password'])) {
                $_SESSION['error_message'] = 'Mật khẩu hiện tại không đúng!';
            } elseif (strlen($newPass) < 6) {
                $_SESSION['error_message'] = 'Mật khẩu mới phải có ít nhất 6 ký tự!';
            } elseif ($newPass !== $confirmPass) {
                $_SESSION['error_message'] = 'Mật khẩu xác nhận không khớp!';
            } else {
                $user->setPassword($_SESSION['user_id'], $newPass);
                $_SESSION['success_message'] = 'Đổi mật khẩu thành công!';
            }
        }
        header('Location: ?action=settings');
        exit;

    // ---- Customers ----
    case 'customers':
        $customers_list = $customer->getAll();
        include 'views/customers.php';
        break;

    case 'save_customer':
        if (!$user->hasPermission($currentUser['id'], 'manage_customers')) {
            $_SESSION['error_message'] = 'Bạn không có quyền thực hiện hành động này!';
            header('Location: ?action=customers');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                if (!empty($_POST['id'])) {
                    $customer->update($_POST['id'], $_POST);
                    $_SESSION['success_message'] = 'Cập nhật khách hàng thành công!';
                } else {
                    $customer->create($_POST);
                    $_SESSION['success_message'] = 'Thêm khách hàng thành công!';
                }
            } catch (Exception $e) { $_SESSION['error_message'] = $e->getMessage(); }
        }
        header('Location: ?action=customers');
        exit;

    case 'delete_customer':
        if (!$user->hasPermission($currentUser['id'], 'manage_customers')) {
            $_SESSION['error_message'] = 'Bạn không có quyền thực hiện hành động này!';
            header('Location: ?action=customers');
            exit;
        }
        $customer->delete($_GET['id']);
        $_SESSION['success_message'] = 'Đã xóa khách hàng!';
        header('Location: ?action=customers');
        exit;

    case 'api_customer_branches':
        header('Content-Type: application/json');
        $cid = intval($_GET['customer_id'] ?? 0);
        $branches = $customer->getBranches($cid);
        echo json_encode(['branches' => $branches]);
        exit;

    case 'customer_branches':
        $customerId = intval($_GET['customer_id'] ?? 0);
        $customerData = $customer->get($customerId);
        if (!$customerData) { header('Location: ?action=customers'); exit; }
        $branches = $customer->getBranches($customerId);
        include 'views/customer_branches.php';
        break;

    case 'save_branch':
        if (!$user->hasPermission($currentUser['id'], 'manage_customers')) {
            $_SESSION['error_message'] = 'Bạn không có quyền thực hiện hành động này!';
            header('Location: ?action=customers');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                if (!empty($_POST['id'])) {
                    $customer->updateBranch($_POST['id'], $_POST);
                    $_SESSION['success_message'] = 'Cập nhật chi nhánh thành công!';
                } else {
                    $customer->createBranch($_POST);
                    $_SESSION['success_message'] = 'Thêm chi nhánh thành công!';
                }
            } catch (Exception $e) { $_SESSION['error_message'] = $e->getMessage(); }
        }
        header('Location: ?action=customer_branches&customer_id=' . ($_POST['customer_id'] ?? 0));
        exit;

    case 'delete_branch':
        if (!$user->hasPermission($currentUser['id'], 'manage_customers')) {
            $_SESSION['error_message'] = 'Bạn không có quyền thực hiện hành động này!';
            header('Location: ?action=customers');
            exit;
        }
        $branch = $customer->getBranch($_GET['id']);
        $customer->deleteBranch($_GET['id']);
        $_SESSION['success_message'] = 'Đã xóa chi nhánh!';
        header('Location: ?action=customer_branches&customer_id=' . ($branch['customer_id'] ?? 0));
        exit;

    // ---- FTTH Lines ----
    case 'lines':
        $lines = $ftthLine->getAll();
        $customers_list = $customer->getAllForDropdown();
        include 'views/lines.php';
        break;

    case 'save_line':
        if (!$user->hasPermission($currentUser['id'], 'manage_lines')) {
            $_SESSION['error_message'] = 'Bạn không có quyền thực hiện hành động này!';
            header('Location: ?action=lines');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $_POST['created_by'] = $_SESSION['user_id'];
                
                // Auto-create branch in customer_branches
                // Store name (name field) = branch name
                $customerId = $_POST['customer_id'] ?? null;
                $branchName = trim($_POST['name'] ?? '');
                $branchAddress = trim($_POST['branch_address'] ?? '');
                $branchPhone = trim($_POST['phone'] ?? '');
                $_POST['branch_name'] = $branchName; // save to ftth_lines too
                
                if ($customerId && $branchName) {
                    // Check if branch already exists for this customer
                    $checkStmt = $pdo->prepare("SELECT id FROM customer_branches WHERE customer_id = ? AND branch_name = ?");
                    $checkStmt->execute([$customerId, $branchName]);
                    $existingBranch = $checkStmt->fetch(PDO::FETCH_ASSOC);
                    
                    if ($existingBranch) {
                        // Update address & phone
                        $updStmt = $pdo->prepare("UPDATE customer_branches SET address = COALESCE(NULLIF(?, ''), address), phone = COALESCE(NULLIF(?, ''), phone) WHERE id = ?");
                        $updStmt->execute([$branchAddress, $branchPhone, $existingBranch['id']]);
                    } else {
                        // Create new branch
                        $insStmt = $pdo->prepare("INSERT INTO customer_branches (customer_id, branch_name, address, phone) VALUES (?, ?, ?, ?)");
                        $insStmt->execute([$customerId, $branchName, $branchAddress, $branchPhone ?: null]);
                    }
                }
                
                if (!empty($_POST['id'])) {
                    $ftthLine->update($_POST['id'], $_POST);
                    $_SESSION['success_message'] = 'Cập nhật line thành công!';
                } else {
                    $ftthLine->create($_POST);
                    $_SESSION['success_message'] = 'Thêm line thành công!';
                }
            } catch (Exception $e) { $_SESSION['error_message'] = $e->getMessage(); }
        }
        header('Location: ?action=lines');
        exit;

    case 'delete_line':
        if (!$user->hasPermission($currentUser['id'], 'manage_lines')) {
            $_SESSION['error_message'] = 'Bạn không có quyền thực hiện hành động này!';
            header('Location: ?action=lines');
            exit;
        }
        $ftthLine->delete($_GET['id']);
        $_SESSION['success_message'] = 'Đã xóa line!';
        header('Location: ?action=lines');
        exit;
}
