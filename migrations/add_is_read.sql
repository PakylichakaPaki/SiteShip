ALTER TABLE `message` ADD COLUMN `is_read` TINYINT(1) NOT NULL DEFAULT 0;
CREATE INDEX `idx-message-is_read` ON `message` (`is_read`);
