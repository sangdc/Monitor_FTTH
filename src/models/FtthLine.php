<?php

class FtthLine {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getAll() {
        $sql = "SELECT l.*, c.name as customer_name_rel
                FROM ftth_lines l
                LEFT JOIN customers c ON l.customer_id = c.id
                WHERE l.active = 1
                ORDER BY l.expiry_date IS NULL ASC, l.expiry_date ASC, CAST(l.store_code AS UNSIGNED) ASC, l.store_code ASC";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMonitored() {
        $sql = "SELECT l.*, c.name as customer_name_rel
                FROM ftth_lines l
                LEFT JOIN customers c ON l.customer_id = c.id
                WHERE l.active = 1 AND l.ip_type = 'static'
                ORDER BY CAST(l.store_code AS UNSIGNED) ASC, l.store_code ASC, c.name ASC, l.name ASC";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get($id) {
        $stmt = $this->pdo->prepare("SELECT l.*, c.name as customer_name_rel
            FROM ftth_lines l
            LEFT JOIN customers c ON l.customer_id = c.id
            WHERE l.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO ftth_lines 
            (name, store_code, ip_address, provider, isp_account, check_method, customer_id, branch_name, branch_address, phone, regional_contact, on_net, expiry_date, notes, contract_id, olt_info, ip_type, created_by) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['name'],
            $data['store_code'] ?? null,
            $data['ip_address'] ?: null,
            $data['provider'] ?? null,
            $data['isp_account'] ?? null,
            $data['check_method'] ?? 'ping',
            $data['customer_id'] ?: null,
            $data['branch_name'] ?? null,
            $data['branch_address'] ?? null,
            $data['phone'] ?? null,
            $data['regional_contact'] ?? null,
            $data['on_net'] ?: null,
            $data['expiry_date'] ?: null,
            $data['notes'] ?? null,
            $data['contract_id'] ?? null,
            $data['olt_info'] ?? null,
            $data['ip_type'] ?? 'static',
            $data['created_by'] ?? null
        ]);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE ftth_lines SET 
            name = ?, store_code = ?, ip_address = ?, provider = ?, isp_account = ?,
            check_method = ?,
            customer_id = ?, branch_name = ?, branch_address = ?,
            phone = ?, regional_contact = ?, on_net = ?, expiry_date = ?, notes = ?,
            contract_id = ?, olt_info = ?, ip_type = ?
            WHERE id = ?");
        return $stmt->execute([
            $data['name'],
            $data['store_code'] ?? null,
            $data['ip_address'] ?: null,
            $data['provider'] ?? null,
            $data['isp_account'] ?? null,
            $data['check_method'] ?? 'ping',
            $data['customer_id'] ?: null,
            $data['branch_name'] ?? null,
            $data['branch_address'] ?? null,
            $data['phone'] ?? null,
            $data['regional_contact'] ?? null,
            $data['on_net'] ?: null,
            $data['expiry_date'] ?: null,
            $data['notes'] ?? null,
            $data['contract_id'] ?? null,
            $data['olt_info'] ?? null,
            $data['ip_type'] ?? 'static',
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("UPDATE ftth_lines SET active = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function updateStatus($id, $status, $responseTime = null, $message = null, $lineName = '', $ip = '', $customerName = '') {
        $now = date('Y-m-d H:i:s');
        
        // Update line status in DB
        $stmt = $this->pdo->prepare("UPDATE ftth_lines SET status = ?, last_check = ?, avg_response_time = ?, 
            last_up = CASE WHEN ? = 'up' THEN ? ELSE last_up END,
            last_down = CASE WHEN ? = 'down' THEN ? ELSE last_down END
            WHERE id = ?");
        $stmt->execute([$status, $now, $responseTime, $status, $now, $status, $now, $id]);

        // Write to daily CSV: logs/CustomerName/StoreName/YYYY-MM-DD.csv
        $logDir = '/var/www/html/logs';
        $sanitize = function($s) { return preg_replace('/[\/\\\\:*?"<>|]+/', '_', trim($s ?: 'Unknown')); };
        $custDir = $logDir . '/' . $sanitize($customerName) . '/' . $sanitize($lineName);
        if (!is_dir($custDir)) @mkdir($custDir, 0755, true);
        $csvFile = $custDir . '/' . date('Y-m-d') . '.csv';
        clearstatcache(true, $csvFile);
        $isNew = !file_exists($csvFile);
        $fp = @fopen($csvFile, 'a');
        if ($fp) {
            if ($isNew) fputcsv($fp, ['line_id','line_name','ip','status','response_time','message','checked_at']);
            fputcsv($fp, [$id, $lineName, $ip, $status, $responseTime, $message, $now]);
            fclose($fp);
        } else {
            error_log("FTTH Log Warning: Cannot open/create file " . $csvFile . ". Please check permissions.");
        }
    }

    public function ping($ip) {
        $startTime = microtime(true);
        
        // Use exec to run ping command (2 packets, 2 second timeout)
        $output = [];
        $exitCode = 0;
        exec("ping -c 2 -W 2 " . escapeshellarg($ip) . " 2>&1", $output, $exitCode);
        
        $endTime = microtime(true);
        $responseTime = round(($endTime - $startTime) * 1000, 2); // ms
        
        // Parse average time from ping output
        $avgTime = null;
        foreach ($output as $line) {
            if (preg_match('/min\/avg\/max.*=\s*[\d.]+\/([\d.]+)/', $line, $matches)) {
                $avgTime = floatval($matches[1]);
            }
        }
        
        return [
            'success' => $exitCode === 0,
            'response_time' => $avgTime ?? $responseTime,
            'output' => implode("\n", $output),
            'exit_code' => $exitCode
        ];
    }

    public function getHistory($lineId, $limit = 50) {
        $stmt = $this->pdo->prepare("SELECT * FROM ftth_line_history WHERE line_id = ? ORDER BY checked_at DESC LIMIT ?");
        $stmt->execute([$lineId, $limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
