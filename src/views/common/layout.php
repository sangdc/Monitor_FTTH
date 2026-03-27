<?php
function renderHeader($currentUser, $userModel, $activeMenu = 'dashboard') {
    $menuItems = [
        'dashboard' => ['icon' => 'fa-chart-line', 'label' => 'Dashboard'],
        'lines' => ['icon' => 'fa-network-wired', 'label' => 'FTTH Lines'],
        'customers' => ['icon' => 'fa-building', 'label' => 'Khách hàng'],
    ];
    if ($userModel->hasPermission($currentUser['id'], 'system_settings')) {
        $menuItems['users'] = ['icon' => 'fa-users-cog', 'label' => 'Users'];
        $menuItems['settings'] = ['icon' => 'fa-cog', 'label' => 'Cài đặt'];
    }
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <title>FTTH Monitor</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="assets/css/app.css?v=<?= time() ?>" rel="stylesheet">
</head>
<body>
    <div class="top-bar">
        <div class="brand">
            <div class="brand-icon"><i class="fas fa-network-wired"></i></div>
            <div class="brand-name">FTTH <span>Monitor</span></div>
        </div>
        <nav class="main-nav">
            <?php foreach ($menuItems as $key => $item): ?>
                <a href="?action=<?= $key ?>" class="nav-item <?= $activeMenu === $key ? 'active' : '' ?>">
                    <i class="fas <?= $item['icon'] ?>"></i>
                    <span><?= $item['label'] ?></span>
                </a>
            <?php endforeach; ?>
        </nav>
        <div class="user-menu">
            <div class="user-info">
                <div class="user-name"><?= htmlspecialchars($currentUser['name']) ?></div>
                <div class="user-role"><?= htmlspecialchars($currentUser['role']) ?></div>
            </div>
            <a href="?action=logout" class="btn-logout"><i class="fas fa-sign-out-alt"></i></a>
        </div>
    </div>
    <div class="app-content">
<?php
}

function renderFooter() {
?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
}

function showFlash() {
    if (isset($_SESSION['success_message'])) {
        echo '<div class="flash-msg success"><i class="fas fa-check-circle"></i>' . htmlspecialchars($_SESSION['success_message']) . '</div>';
        unset($_SESSION['success_message']);
    }
    if (isset($_SESSION['error_message'])) {
        echo '<div class="flash-msg error"><i class="fas fa-exclamation-circle"></i>' . htmlspecialchars($_SESSION['error_message']) . '</div>';
        unset($_SESSION['error_message']);
    }
}
?>
