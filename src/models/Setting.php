<?php

class Setting
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function get($key, $default = null): ?string
    {
        $stmt = $this->pdo->prepare("SELECT setting_value FROM system_settings WHERE setting_key = ?");
        $stmt->execute([$key]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (string)$row['setting_value'] : $default;
    }

    public function set($key, $value)
    {
        $stmt = $this->pdo->prepare("INSERT INTO system_settings (setting_key, setting_value) VALUES (?, ?) 
            ON DUPLICATE KEY UPDATE setting_value = ?, updated_at = NOW()");
        return $stmt->execute([$key, $value, $value]);
    }

    public function getAll()
    {
        $stmt = $this->pdo->query("SELECT * FROM system_settings ORDER BY setting_key");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $settings = [];
        foreach ($rows as $r) {
            $settings[$r['setting_key']] = $r['setting_value'];
        }
        return $settings;
    }

    public function saveMultiple($data)
    {
        foreach ($data as $key => $value) {
            $this->set($key, $value);
        }
    }
}