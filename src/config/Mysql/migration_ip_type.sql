-- Migration: Đổi is_dynamic_ip thành ip_type
-- Chạy trên phpMyAdmin

-- 1. Thêm cột ip_type
ALTER TABLE `ftth_lines` ADD COLUMN `ip_type` VARCHAR(30) NOT NULL DEFAULT 'static' AFTER `olt_info`;

-- 2. Chuyển data cũ: is_dynamic_ip=1 → ip_type='dynamic'
UPDATE `ftth_lines` SET `ip_type` = 'dynamic' WHERE `is_dynamic_ip` = 1;

-- 3. Xoá cột cũ
ALTER TABLE `ftth_lines` DROP COLUMN `is_dynamic_ip`;
