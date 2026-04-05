-- Migration: Thêm hỗ trợ IP Động + Xoá bảng history
-- Chạy trên phpMyAdmin hoặc MySQL CLI

-- 1. Thêm cột is_dynamic_ip vào ftth_lines
ALTER TABLE `ftth_lines` ADD COLUMN `is_dynamic_ip` TINYINT(1) NOT NULL DEFAULT 0 AFTER `olt_info`;

-- 2. Cho phép ip_address nullable (IP động có thể không có IP)
ALTER TABLE `ftth_lines` MODIFY `ip_address` varchar(45) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL;

-- 3. Xoá bảng ftth_line_history (log giờ ghi vào file CSV)
DROP TABLE IF EXISTS `ftth_line_history`;

-- 4. Kiểm tra kết quả
SELECT COUNT(*) as total_lines, SUM(is_dynamic_ip) as dynamic_lines FROM ftth_lines WHERE active = 1;
