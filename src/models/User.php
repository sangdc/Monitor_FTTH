<?php

class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function authenticate($username, $password) {
        $sql = "SELECT id, password, role FROM users WHERE username = ? AND active = TRUE";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Update last login
            $this->pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?")->execute([$user['id']]);
            return ['id' => $user['id'], 'role' => $user['role']];
        }
        return false;
    }

    public function get($id) {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll() {
        $sql = "SELECT * FROM users ORDER BY name";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $sql = "INSERT INTO users (username, name, email, password, role) VALUES (:username, :name, :email, :password, :role)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'username' => $data['username'],
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'role' => $data['role'] ?? 'user'
        ]);
    }

    public function update($id, $data) {
        $fields = ['name', 'email', 'role', 'active'];
        $updates = [];
        $params = ['id' => $id];

        foreach ($fields as $field) {
            if (isset($data[$field])) {
                $updates[] = "`$field` = :$field";
                $params[$field] = $data[$field];
            }
        }

        if (empty($updates)) return false;

        $sql = "UPDATE users SET " . implode(', ', $updates) . " WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($params);
    }

    public function setPassword($userId, $password) {
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            password_hash($password, PASSWORD_DEFAULT),
            $userId
        ]);
    }

    public function hasPermission($userId, $permissionName) {
        $user = $this->get($userId);
        if ($user && $user['role'] === 'admin') {
            return true;
        }

        $sql = "SELECT COUNT(*) as count 
                FROM user_permissions up 
                JOIN permissions p ON up.permission_id = p.id 
                WHERE up.user_id = ? AND p.name = ? AND 
                EXISTS (SELECT 1 FROM users u WHERE u.id = up.user_id AND u.active = TRUE)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId, $permissionName]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'] > 0;
    }

    public function getAllPermissions() {
        $stmt = $this->pdo->query("SELECT * FROM permissions ORDER BY name");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserPermissions($userId) {
        $sql = "SELECT permission_id FROM user_permissions WHERE user_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'permission_id');
    }

    public function updatePermissions($userId, $permissions) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare("DELETE FROM user_permissions WHERE user_id = ?");
            $stmt->execute([$userId]);

            if (!empty($permissions)) {
                $stmt = $this->pdo->prepare("INSERT INTO user_permissions (user_id, permission_id) VALUES (?, ?)");
                foreach ($permissions as $permId) {
                    $stmt->execute([$userId, $permId]);
                }
            }

            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    public function toggleActive($userId, $active) {
        if ($userId == $_SESSION['user_id']) return false;
        $sql = "UPDATE users SET active = :active WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':active' => $active ? 1 : 0, ':id' => $userId]);
    }

    public function delete($id) {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}
