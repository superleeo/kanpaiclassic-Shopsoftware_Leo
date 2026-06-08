ALTER TABLE `#__firma`  ADD `paypalv2_check` ENUM('n','y') NOT NULL DEFAULT 'n'  AFTER `kklastschrift_preis`,  ADD `paypalv2_preis` DECIMAL(8,2) NOT NULL DEFAULT '0.00'  AFTER `paypalv2_check`;
INSERT INTO `#__data` (`id`, `type`, `data`) VALUES (NULL, 'ppv2_client_id', ''), (NULL, 'ppv2_client_secret', '');


ALTER TABLE `#__firma`  ADD `mollie_check` ENUM('n','y') NOT NULL DEFAULT 'n'  AFTER `kklastschrift_preis`,  ADD `mollie_preis` DECIMAL(8,2) NOT NULL DEFAULT '0.00'  AFTER `mollie_check`;
INSERT INTO `#__data` (`id`, `type`, `data`) VALUES (NULL, 'mollie_test_key', ''), (NULL, 'mollie_live_key', '');

ALTER TABLE `#__firma`  ADD `bonusprogramm_aktiv` ENUM('n','y') NOT NULL DEFAULT 'n',  ADD `bonusprogramm_prozent` DECIMAL(8,2) NOT NULL DEFAULT '0.00';

/* INSERT INTO `#__laender` (`id`, `domain`, `iso_lang`, `kurz`, `region`, `name`, `name_shop`, `sort`, `versand`, `locale`) VALUES (1,   'ah', 'ah', 'abh', 'eu', 'Abholung', 'Abholung', 2, '0.00', ''); */ /*Abholung als "Land"*/

ALTER TABLE `#__firma`  ADD `show_breadcrumbs` ENUM('n','y') NOT NULL DEFAULT 'n';
