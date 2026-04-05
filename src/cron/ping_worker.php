<?php
/**
 * FTTH Monitor - Background Ping Worker
 * 
 * Runs as an infinite loop in a Docker container.
 * Reads ping_interval from system_settings, pings all active lines,
 * updates status/history, sends Telegram alert on DOWN.
 * 
 * Usage: php /var/www/html/cron/ping_worker.php
 */

// Timezone
date_default_timezone_set('Asia/Ho_Chi_Minh');

echo "[" . date('Y-m-d H:i:s') . "] FTTH Ping Worker started\n";

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/FtthLine.php';
require_once __DIR__ . '/../models/Setting.php';

$ftthLine = new FtthLine($pdo);
$setting = new Setting($pdo);

// Track previous statuses for transition detection
$previousStatuses = [];

while (true) {
    try {
        // Read interval from DB each loop (admin can change it live)
        $interval = intval($setting->get('ping_interval', 30));
        if ($interval < 5)
            $interval = 5; // minimum 5 seconds

        $telegramEnabled = $setting->get('telegram_enabled', '0') === '1';
        $telegramToken = $setting->get('telegram_bot_token', '');
        $telegramChatId = $setting->get('telegram_chat_id', '');

        echo "[" . date('Y-m-d H:i:s') . "] Ping cycle starting (interval: {$interval}s)\n";

        // Get all MONITORED lines (exclude dynamic IP)
        $lines = $ftthLine->getMonitored();
        $upCount = 0;
        $downCount = 0;

        foreach ($lines as $line) {
            $result = $ftthLine->ping($line['ip_address']);
            $status = $result['success'] ? 'up' : 'down';
            $responseTime = $result['response_time'];

            // Update status in DB + write to CSV
            $ftthLine->updateStatus(
                $line['id'],
                $status,
                $responseTime,
                $result['success'] ? 'OK' : 'Timeout/Unreachable',
                $line['name'],
                $line['ip_address']
            );

            if ($status === 'up')
                $upCount++;
            else
                $downCount++;

            // Detect status transition: was UP/unknown, now DOWN
            $prevStatus = $previousStatuses[$line['id']] ?? $line['status'];
            if ($status === 'down' && $prevStatus !== 'down') {
                echo "[" . date('Y-m-d H:i:s') . "] ⚠ LINE DOWN: {$line['name']} ({$line['ip_address']})\n";

                // Log alert
                try {
                    $alertStmt = $pdo->prepare("INSERT INTO alert_log (line_id, alert_type, message) VALUES (?, 'down', ?)");
                    $alertStmt->execute([$line['id'], "Line '{$line['name']}' ({$line['ip_address']}) is DOWN"]);
                }
                catch (Exception $e) {
                    echo "[" . date('Y-m-d H:i:s') . "] Alert log error: " . $e->getMessage() . "\n";
                }

                // Send Telegram
                if ($telegramEnabled && $telegramToken && $telegramChatId) {
                    $customerName = $line['customer_name_rel'] ?? 'N/A';
                    $branchName = $line['branch_name'] ?? '';
                    $msg = "🔴 *FTTH LINE DOWN*\n\n"
                        . "📛 *Line:* {$line['name']}\n"
                        . "🌐 *IP:* `{$line['ip_address']}`\n"
                        . "🏢 *Khách hàng:* {$customerName}\n"
                        . ($branchName ? "📍 *Chi nhánh:* {$branchName}\n" : "")
                        . "⏰ *Thời gian:* " . date('d/m/Y H:i:s') . "\n"
                        . "📊 *Response:* {$responseTime} ms";

                    sendTelegram($telegramToken, $telegramChatId, $msg);
                }
            }

            // Detect recovery: was DOWN, now UP
            if ($status === 'up' && $prevStatus === 'down') {
                echo "[" . date('Y-m-d H:i:s') . "] ✅ LINE RECOVERED: {$line['name']} ({$line['ip_address']})\n";

                try {
                    $alertStmt = $pdo->prepare("INSERT INTO alert_log (line_id, alert_type, message) VALUES (?, 'recovery', ?)");
                    $alertStmt->execute([$line['id'], "Line '{$line['name']}' ({$line['ip_address']}) recovered"]);
                }
                catch (Exception $e) {
                // silent
                }

                if ($telegramEnabled && $telegramToken && $telegramChatId) {
                    $msg = "🟢 *FTTH LINE RECOVERED*\n\n"
                        . "📛 *Line:* {$line['name']}\n"
                        . "🌐 *IP:* `{$line['ip_address']}`\n"
                        . "⏰ *Thời gian:* " . date('d/m/Y H:i:s') . "\n"
                        . "📊 *Response:* {$responseTime} ms";
                    sendTelegram($telegramToken, $telegramChatId, $msg);
                }
            }

            $previousStatuses[$line['id']] = $status;
        }

        echo "[" . date('Y-m-d H:i:s') . "] Cycle done: {$upCount} up, {$downCount} down. Sleeping {$interval}s\n";

    }
    catch (Exception $e) {
        echo "[" . date('Y-m-d H:i:s') . "] ERROR: " . $e->getMessage() . "\n";

        // Reconnect DB if connection lost
        try {
            $host = getenv('DB_HOST') ?: 'mysql';
            $dbname = getenv('DB_NAME') ?: 'ftth_monitor';
            $user = getenv('DB_USER') ?: 'ftth_user';
            $pass = getenv('DB_PASS') ?: 'FtthMon@2026';
            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
            $ftthLine = new FtthLine($pdo);
            $setting = new Setting($pdo);
            echo "[" . date('Y-m-d H:i:s') . "] DB reconnected\n";
        }
        catch (Exception $re) {
            echo "[" . date('Y-m-d H:i:s') . "] Reconnect failed: " . $re->getMessage() . "\n";
        }
    }

    sleep($interval ?? 30);
}

function sendTelegram($token, $chatId, $message)
{
    $url = "https://api.telegram.org/bot{$token}/sendMessage";
    $data = [
        'chat_id' => $chatId,
        'text' => $message,
        'parse_mode' => 'Markdown',
        'disable_web_page_preview' => true
    ];

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($data),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10
    ]);
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpCode !== 200) {
        echo "[" . date('Y-m-d H:i:s') . "] Telegram send failed (HTTP {$httpCode}): {$result}\n";
    }
    else {
        echo "[" . date('Y-m-d H:i:s') . "] Telegram sent OK\n";
    }
}