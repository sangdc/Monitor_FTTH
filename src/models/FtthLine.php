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
            (name, store_code, ip_address, provider, isp_account, check_method, customer_id, branch_name, branch_address, phone, regional_contact, on_net, expiry_date, notes, contract_id, olt_info, created_by) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['name'],
            $data['store_code'] ?? null,
            $data['ip_address'],
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
            contract_id = ?, olt_info = ?
            WHERE id = ?");
        return $stmt->execute([
            $data['name'],
            $data['store_code'] ?? null,
            $data['ip_address'],
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
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("UPDATE ftth_lines SET active = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function updateStatus($id, $status, $responseTime = null, $message = null) {
        $now = date('Y-m-d H:i:s');
        
        // Update line status
        $updates = ['status' => $status, 'last_check' => $now, 'avg_response_time' => $responseTime];
        if ($status === 'up') $updates['last_up'] = $now;
        if ($status === 'down') $updates['last_down'] = $now;

        $stmt = $this->pdo->prepare("UPDATE ftth_lines SET status = ?, last_check = ?, avg_response_time = ?, 
            last_up = CASE WHEN ? = 'up' THEN ? ELSE last_up END,
            last_down = CASE WHEN ? = 'down' THEN ? ELSE last_down END
            WHERE id = ?");
        $stmt->execute([$status, $now, $responseTime, $status, $now, $status, $now, $id]);

        // Insert history record
        $histStmt = $this->pdo->prepare("INSERT INTO ftth_line_history (line_id, status, response_time, message) VALUES (?, ?, ?, ?)");
        $histStmt->execute([$id, $status, $responseTime, $message]);
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
