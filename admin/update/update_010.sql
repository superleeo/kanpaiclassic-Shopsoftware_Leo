ALTER TABLE `#__articles_info`
   ADD `versandfrei_check` ENUM('n','y') NULL DEFAULT 'n'  AFTER `marke_aktiv`,
   ADD `artikelgrafik1_check` ENUM('n','y') NOT NULL DEFAULT 'n'  AFTER `versandfrei_check`,
   ADD `artikelgrafik2_check` ENUM('n','y') NOT NULL DEFAULT 'n'  AFTER `artikelgrafik1_check`,
   ADD `artikelgrafik3_check` ENUM('n','y') NOT NULL DEFAULT 'n'  AFTER `artikelgrafik2_check`,
   ADD `artikelgrafik4_check` ENUM('n','y') NOT NULL DEFAULT 'n'  AFTER `artikelgrafik3_check`,
   ADD `artikelgrafik5_check` ENUM('n','y') NOT NULL DEFAULT 'n'  AFTER `artikelgrafik4_check`,
   ADD `artikelgrafik6_check` ENUM('n','y') NOT NULL DEFAULT 'n'  AFTER `artikelgrafik5_check`,
   ADD `image_hover` VARCHAR(250) NOT NULL DEFAULT '' AFTER `image`;

ALTER TABLE `#__categories`ADD KEY `idx_active` (`active`);

UPDATE `#__laender` SET `region` = '' WHERE `id` = 170;

ALTER TABLE `#__articles_images` ADD `count` SMALLINT UNSIGNED NOT NULL DEFAULT '2' AFTER `image`;
