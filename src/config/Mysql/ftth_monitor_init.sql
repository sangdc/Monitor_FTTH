-- =============================================
-- FTTH Monitor - Database Initialization
-- =============================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- =============================================
-- Users & Authentication
-- =============================================
CREATE TABLE IF NOT EXISTS `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `username` VARCHAR(50) NOT NULL UNIQUE,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(100) DEFAULT NULL,
    `password` VARCHAR(255) DEFAULT NULL,
    `role` ENUM('admin', 'user', 'viewer') NOT NULL DEFAULT 'user',
    `active` TINYINT(1) NOT NULL DEFAULT 1,
    `avatar` VARCHAR(255) DEFAULT NULL,
    `last_login` DATETIME DEFAULT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Permissions System
-- =============================================
CREATE TABLE IF NOT EXISTS `permissions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(50) NOT NULL UNIQUE,
    `description` VARCHAR(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `user_permissions` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT NOT NULL,
    `permission_id` INT NOT NULL,
    UNIQUE KEY `uk_user_perm` (`user_id`, `permission_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`permission_id`) REFERENCES `permissions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- FTTH Lines (Monitored Devices)
-- =============================================
CREATE TABLE IF NOT EXISTS `ftth_lines` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `ip_address` VARCHAR(45) NOT NULL,
    `port` INT DEFAULT 80,
    `check_method` ENUM('ping', 'http', 'tcp', 'snmp') NOT NULL DEFAULT 'ping',
    `check_interval` INT NOT NULL DEFAULT 300 COMMENT 'seconds between checks',
    `location` VARCHAR(255) DEFAULT NULL,
    `customer_name` VARCHAR(100) DEFAULT NULL,
    `contract_id` VARCHAR(50) DEFAULT NULL,
    `olt_info` VARCHAR(100) DEFAULT NULL COMMENT 'OLT/PON port info',
    `status` ENUM('up', 'down', 'warning', 'paused', 'unknown') NOT NULL DEFAULT 'unknown',
    `last_check` DATETIME DEFAULT NULL,
    `last_up` DATETIME DEFAULT NULL,
    `last_down` DATETIME DEFAULT NULL,
    `uptime_percent` DECIMAL(5,2) DEFAULT 100.00,
    `avg_response_time` DECIMAL(10,2) DEFAULT NULL COMMENT 'ms',
    `tags` VARCHAR(255) DEFAULT NULL COMMENT 'comma-separated tags',
    `notify_enabled` TINYINT(1) NOT NULL DEFAULT 1,
    `active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_by` INT DEFAULT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_status` (`status`),
    INDEX `idx_ip` (`ip_address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- FTTH Line Check History
-- =============================================
CREATE TABLE IF NOT EXISTS `ftth_line_history` (
    `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
    `line_id` INT NOT NULL,
    `status` ENUM('up', 'down', 'warning', 'unknown') NOT NULL,
    `response_time` DECIMAL(10,2) DEFAULT NULL COMMENT 'ms',
    `message` VARCHAR(255) DEFAULT NULL,
    `checked_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`line_id`) REFERENCES `ftth_lines`(`id`) ON DELETE CASCADE,
    INDEX `idx_line_checked` (`line_id`, `checked_at`),
    INDEX `idx_checked_at` (`checked_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Alerts / Notifications Log
-- =============================================
CREATE TABLE IF NOT EXISTS `alert_log` (
    `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
    `line_id` INT NOT NULL,
    `alert_type` ENUM('down', 'up', 'warning', 'recovery') NOT NULL,
    `message` TEXT DEFAULT NULL,
    `notified` TINYINT(1) DEFAULT 0,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`line_id`) REFERENCES `ftth_lines`(`id`) ON DELETE CASCADE,
    INDEX `idx_line_alert` (`line_id`, `created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Activity Log
-- =============================================
CREATE TABLE IF NOT EXISTS `activity_log` (
    `id` BIGINT AUTO_INCREMENT PRIMARY KEY,
    `user_id` INT DEFAULT NULL,
    `action` VARCHAR(50) NOT NULL,
    `entity_type` VARCHAR(50) DEFAULT NULL,
    `entity_id` INT DEFAULT NULL,
    `details` JSON DEFAULT NULL,
    `ip_address` VARCHAR(45) DEFAULT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_user_action` (`user_id`, `action`),
    INDEX `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Seed Data
-- =============================================

-- Default permissions
INSERT INTO `permissions` (`name`, `description`) VALUES
    ('login', 'Can login to the system'),
    ('view_dashboard', 'Can view monitoring dashboard'),
    ('manage_lines', 'Can add/edit/delete FTTH lines'),
    ('view_lines', 'Can view FTTH line details'),
    ('manage_users', 'Can manage user accounts'),
    ('manage_alerts', 'Can configure alert settings'),
    ('view_reports', 'Can view reports and statistics'),
    ('system_settings', 'Can change system settings');

-- Admin user (password: admin123)
INSERT INTO `users` (`username`, `name`, `email`, `password`, `role`) VALUES
    ('admin', 'Administrator', 'admin@ftthmonitor.local', '$2y$10$k689w2Ip0zYKxqpKH7ZPduQ/afo5hfs7p.rMeiVh884ka9VpuKqEy', 'admin');

-- Grant all permissions to admin
INSERT INTO `user_permissions` (`user_id`, `permission_id`)
    SELECT 1, id FROM `permissions`;

-- Sample FTTH lines for demo
INSERT INTO `ftth_lines` (`name`, `ip_address`, `port`, `check_method`, `location`, `customer_name`, `status`, `created_by`) VALUES
    ('Line FTTH - VP Chính', '192.168.1.1', 80, 'ping', 'Văn phòng chính', 'Internal', 'unknown', 1),
    ('Line FTTH - Chi nhánh 1', '10.0.0.1', 80, 'ping', 'Chi nhánh HCM', 'Khách hàng A', 'unknown', 1),
    ('Line FTTH - Chi nhánh 2', '10.0.1.1', 80, 'ping', 'Chi nhánh HN', 'Khách hàng B', 'unknown', 1);

SET FOREIGN_KEY_CHECKS = 1;
