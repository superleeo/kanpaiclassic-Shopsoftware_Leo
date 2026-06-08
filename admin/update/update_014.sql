/* Gutscheinfeld erscheint im Warenkorb? */
ALTER TABLE `#__firma` ADD `activate_voucher` ENUM('n','y') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'y' AFTER `gutschein_aktiv`;
