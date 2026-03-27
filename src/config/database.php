<?php

$db_config = [
    'host' => getenv('DB_HOST') ?: 'mysql',
    'port' => '3306',
    'dbname' => getenv('DB_DATABASE') ?: getenv('DB_NAME') ?: 'ftth_monitor',
    'username' => getenv('DB_USERNAME') ?: getenv('DB_USER') ?: 'root',
    'password' => getenv('DB_PASSWORD') ?: getenv('DB_PASS') ?: 'root_password'
];

try {
    $pdo = new PDO(
        "mysql:host={$db_config['host']};port={$db_config['port']};dbname={$db_config['dbname']}",
        $db_config['username'],
        $db_config['password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ]
    );
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
