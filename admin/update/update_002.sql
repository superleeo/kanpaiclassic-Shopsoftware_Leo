SELECT count(*) INTO @test
   FROM information_schema.columns
WHERE TABLE_SCHEMA = database()
   AND TABLE_NAME = '#__klarna'
   AND COLUMN_NAME = 'status';
SET @query = IF(@test <= 0,
   "ALTER TABLE `#__klarna` ADD `status` VARCHAR(16) NOT NULL DEFAULT '' AFTER `date`",
   'SELECT 1+1');
PREPARE stmt FROM @query;
EXECUTE stmt;
SET @query = IF(@test <= 0,
   "ALTER TABLE `#__klarna` ADD KEY `idx_klarna_order_id` (`klarna_order_id`)",
   'SELECT 1+1');
PREPARE stmt FROM @query;
EXECUTE stmt;

ALTER TABLE `#__data` CHANGE `data` `data` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;