-- DB_ADD_RESTAURANT.sql
-- 为餐厅功能添加两张辅助表：`reservations` 和 `vouchers`。
-- 请根据系统表前缀调整表名或合并到现有商品表中。

CREATE TABLE IF NOT EXISTS `reservations` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(191) NOT NULL,
  `email` VARCHAR(191) NOT NULL,
  `phone` VARCHAR(63),
  `date` DATE NOT NULL,
  `time` TIME NOT NULL,
  `persons` INT NOT NULL DEFAULT 2,
  `notes` TEXT,
  `status` VARCHAR(32) NOT NULL DEFAULT 'pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `vouchers` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `code` VARCHAR(64) NOT NULL UNIQUE,
  `amount` DECIMAL(10,2) NOT NULL,
  `currency` VARCHAR(8) DEFAULT 'EUR',
  `valid_from` DATE DEFAULT NULL,
  `valid_to` DATE DEFAULT NULL,
  `used` TINYINT(1) DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 示例数据：三个代金券（注意：实际项目中通常把代金券作为商品以支持购物车）
INSERT INTO `vouchers` (`code`, `amount`, `currency`, `valid_from`, `valid_to`) VALUES
 ('VOUCHER25', 25.00, 'EUR', NULL, NULL),
 ('VOUCHER50', 50.00, 'EUR', NULL, NULL),
 ('VOUCHER100', 100.00, 'EUR', NULL, NULL);

-- 说明:
-- 1) 若您的系统把代金券作为商品 (flow_shop_artikel)，建议在该表中新建代金券商品并利用现有购物车/结账流程。
-- 2) `reservations` 表需要一个后端处理器写入数据（例如在 classes/ 下新建 Reservation 类或在 index.php 增加 task=reservation 的处理）。
-- 3) 导入方法示例:
--    mysql -u root -p flow_shop_miaowei < DB_ADD_RESTAURANT.sql

