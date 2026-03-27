<?php

class Customer {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // ---- Customers ----
    public function getAll() {
        $sql = "SELECT c.*, 
                (SELECT COUNT(*) FROM customer_branches WHERE customer_id = c.id AND active = 1) as branch_count,
                (SELECT COUNT(*) FROM ftth_lines WHERE customer_id = c.id AND active = 1) as line_count
                FROM customers c WHERE c.active = 1 ORDER BY c.name";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function get($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM customers WHERE id = ? AND active = 1");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO customers (name, contact_person, phone, email, notes) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['name'],
            $data['contact_person'] ?? null,
            $data['phone'] ?? null,
            $data['email'] ?? null,
            $data['notes'] ?? null
        ]);
        return $this->pdo->lastInsertId();
    }

    public function update($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE customers SET name = ?, contact_person = ?, phone = ?, email = ?, notes = ? WHERE id = ?");
        return $stmt->execute([
            $data['name'],
            $data['contact_person'] ?? null,
            $data['phone'] ?? null,
            $data['email'] ?? null,
            $data['notes'] ?? null,
            $id
        ]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("UPDATE customers SET active = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // ---- Branches ----
    public function getBranches($customerId) {
        $stmt = $this->pdo->prepare("SELECT b.*, 
            (SELECT COUNT(*) FROM ftth_lines WHERE customer_id = b.customer_id AND name = b.branch_name AND active = 1) as line_count,
            (SELECT store_code FROM ftth_lines WHERE customer_id = b.customer_id AND name = b.branch_name AND active = 1 AND store_code IS NOT NULL LIMIT 1) as store_code
            FROM customer_branches b WHERE b.customer_id = ? AND b.active = 1 
            ORDER BY CAST((SELECT store_code FROM ftth_lines WHERE customer_id = b.customer_id AND name = b.branch_name AND active = 1 AND store_code IS NOT NULL LIMIT 1) AS UNSIGNED) ASC, b.branch_name ASC");
        $stmt->execute([$customerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBranch($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM customer_branches WHERE id = ? AND active = 1");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createBranch($data) {
        $stmt = $this->pdo->prepare("INSERT INTO customer_branches (customer_id, branch_name, address, contact_person, phone) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['customer_id'],
            $data['branch_name'],
            $data['address'] ?? null,
            $data['contact_person'] ?? null,
            $data['phone'] ?? null
        ]);
        return $this->pdo->lastInsertId();
    }

    public function updateBranch($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE customer_branches SET branch_name = ?, address = ?, contact_person = ?, phone = ? WHERE id = ?");
        return $stmt->execute([
            $data['branch_name'],
            $data['address'] ?? null,
            $data['contact_person'] ?? null,
            $data['phone'] ?? null,
            $id
        ]);
    }

    public function deleteBranch($id) {
        $stmt = $this->pdo->prepare("UPDATE customer_branches SET active = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getAllForDropdown() {
        $sql = "SELECT c.id, c.name FROM customers c WHERE c.active = 1 ORDER BY c.name";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBranchesForDropdown($customerId) {
        $stmt = $this->pdo->prepare("SELECT id, branch_name, address FROM customer_branches WHERE customer_id = ? AND active = 1 ORDER BY branch_name");
        $stmt->execute([$customerId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
