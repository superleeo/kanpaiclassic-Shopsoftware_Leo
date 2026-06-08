ALTER TABLE `#__rechnung` CHANGE `user_rabatt` `user_rabatt` DECIMAL(6,4) NOT NULL DEFAULT '0.00';
ALTER TABLE `#__rechnung` CHANGE `rabatt` `rabatt` DECIMAL(16,9) NULL DEFAULT '0.00';
ALTER TABLE `#__users` CHANGE `rabatt` `rabatt` DECIMAL(6,4) NOT NULL DEFAULT '0.00';
ALTER TABLE `#__firma` CHANGE `cookie_check` `cookie_check` ENUM('n','y','p') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'n';
ALTER TABLE `#__articles` CHANGE `ge_menge` `ge_menge` FLOAT NOT NULL DEFAULT '0.00000';

CREATE TABLE `#__module` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `categorie` varchar(32) CHARACTER SET ascii NOT NULL,
  `module` varchar(32) CHARACTER SET ascii NOT NULL,
  `cat_module_id` int(10) UNSIGNED NOT NULL,
  `sort` int(10) UNSIGNED NOT NULL,
  `active` enum('n','y') CHARACTER SET ascii DEFAULT 'n',
  `anzahl` int(10) UNSIGNED NOT NULL,
  `title` varchar(32) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `value` varchar(16000) CHARACTER SET utf8 NOT NULL,
  `extra` varchar(1024) CHARACTER SET ascii NOT NULL DEFAULT '',
  `background_color` varchar(32) CHARACTER SET ascii NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `#__rechnung_artikel` CHANGE `motiv_upload_text` `motiv_upload_text` VARCHAR(4096) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '';
ALTER TABLE `#__merkliste` CHANGE `motiv_upload_text` `motiv_upload_text` VARCHAR(4096) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '';
ALTER TABLE `#__warenkorb` CHANGE `motiv_upload_text` `motiv_upload_text` VARCHAR(4096) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT '';


DELETE FROM `#__gutscheine_kunden` WHERE `user_id` = 0;

ALTER TABLE `#__articles_info` CHANGE `gewicht` `gewicht` DECIMAL(10,5) NULL DEFAULT '0.50000';

ALTER TABLE `#__categorie_images`
  ADD `anzahl` INT NOT NULL DEFAULT '1' AFTER `search6`,
  ADD `images` VARCHAR(10240) NOT NULL DEFAULT '' AFTER `anzahl`,
  ADD `options` VARCHAR(256) NOT NULL DEFAULT '' AFTER `images`;

SELECT count(*) INTO @test
   FROM information_schema.columns
WHERE TABLE_SCHEMA = database()
   AND TABLE_NAME = '#__firma'
   AND COLUMN_NAME = 'bannerlink1';
SET @query = IF(@test > 0,
   "ALTER TABLE #__firma
  DROP COLUMN bannerlink1,
  DROP COLUMN bannerlink2,
  DROP COLUMN banner1_intern,
  DROP COLUMN banner2_intern;",
   'SELECT 1+1');
PREPARE stmt FROM @query;
EXECUTE stmt;

SELECT count(*) INTO @test
   FROM information_schema.columns
WHERE TABLE_SCHEMA = database()
   AND TABLE_NAME = '#__firma'
   AND COLUMN_NAME = 'danke1_intern';
SET @query = IF(@test > 0,
   "ALTER TABLE #__firma
  DROP COLUMN danke1_link,
  DROP COLUMN danke1_intern,
  DROP COLUMN danke2_link,
  DROP COLUMN danke2_intern;",
   'SELECT 1+1');
PREPARE stmt FROM @query;
EXECUTE stmt;

SELECT count(*) INTO @test
   FROM information_schema.columns
WHERE TABLE_SCHEMA = database()
   AND TABLE_NAME = '#__firma'
   AND COLUMN_NAME = 'detailbild';
SET @query = IF(@test > 0,
   "ALTER TABLE #__firma
  DROP COLUMN detailbild;",
   'SELECT 1+1');
PREPARE stmt FROM @query;
EXECUTE stmt;

SELECT count(*) INTO @test
   FROM information_schema.columns
WHERE TABLE_SCHEMA = database()
   AND TABLE_NAME = '#__firma'
   AND COLUMN_NAME = 'bild_tab';
SET @query = IF(@test > 0,
   "ALTER TABLE #__firma
  DROP COLUMN bild_tab;",
   'SELECT 1+1');
PREPARE stmt FROM @query;
EXECUTE stmt;

SELECT count(*) INTO @test
   FROM information_schema.columns
WHERE TABLE_SCHEMA = database()
   AND TABLE_NAME = '#__firma'
   AND COLUMN_NAME = 'zoom_artikel';
SET @query = IF(@test > 0,
   "ALTER TABLE #__firma
  DROP COLUMN zoom_artikel;",
   'SELECT 1+1');
PREPARE stmt FROM @query;
EXECUTE stmt;

SELECT count(*) INTO @test
   FROM information_schema.columns
WHERE TABLE_SCHEMA = database()
   AND TABLE_NAME = '#__firma'
   AND COLUMN_NAME = 'linien_vert';
SET @query = IF(@test > 0,
   "ALTER TABLE #__firma
  DROP COLUMN linien_vert;",
   'SELECT 1+1');
PREPARE stmt FROM @query;
EXECUTE stmt;

SELECT count(*) INTO @test
   FROM information_schema.columns
WHERE TABLE_SCHEMA = database()
   AND TABLE_NAME = '#__firma'
   AND COLUMN_NAME = 'linien_horz';
SET @query = IF(@test > 0,
   "ALTER TABLE #__firma
  DROP COLUMN linien_horz;",
   'SELECT 1+1');
PREPARE stmt FROM @query;
EXECUTE stmt;

SELECT count(*) INTO @test
   FROM information_schema.columns
WHERE TABLE_SCHEMA = database()
   AND TABLE_NAME = '#__firma'
   AND COLUMN_NAME = 'linien_kat';
SET @query = IF(@test > 0,
   "ALTER TABLE #__firma
  DROP COLUMN linien_kat;",
   'SELECT 1+1');
PREPARE stmt FROM @query;
EXECUTE stmt;

SELECT count(*) INTO @test
   FROM information_schema.columns
WHERE TABLE_SCHEMA = database()
   AND TABLE_NAME = '#__firma'
   AND COLUMN_NAME = 'use_cache';
SET @query = IF(@test > 0,
   "ALTER TABLE #__firma
  DROP COLUMN use_cache;",
   'SELECT 1+1');
PREPARE stmt FROM @query;
EXECUTE stmt;

SELECT count(*) INTO @test
   FROM information_schema.columns
WHERE TABLE_SCHEMA = database()
   AND TABLE_NAME = '#__firma'
   AND COLUMN_NAME = 'bg_fixed';
SET @query = IF(@test > 0,
   "ALTER TABLE #__firma
  DROP COLUMN bg_fixed;",
   'SELECT 1+1');
PREPARE stmt FROM @query;
EXECUTE stmt;

SELECT count(*) INTO @test
   FROM information_schema.columns
WHERE TABLE_SCHEMA = database()
   AND TABLE_NAME = '#__firma'
   AND COLUMN_NAME = 'bg_repeat';
SET @query = IF(@test > 0,
   "ALTER TABLE #__firma
  DROP COLUMN bg_repeat;",
   'SELECT 1+1');
PREPARE stmt FROM @query;
EXECUTE stmt;

SELECT count(*) INTO @test
   FROM information_schema.columns
WHERE TABLE_SCHEMA = database()
   AND TABLE_NAME = '#__firma'
   AND COLUMN_NAME = 'flaeche';
SET @query = IF(@test > 0,
   "ALTER TABLE #__firma
  DROP COLUMN flaeche;",
   'SELECT 1+1');
PREPARE stmt FROM @query;
EXECUTE stmt;

SELECT count(*) INTO @test
   FROM information_schema.columns
WHERE TABLE_SCHEMA = database()
   AND TABLE_NAME = '#__firma'
   AND COLUMN_NAME = 'schatten';
SET @query = IF(@test > 0,
   "ALTER TABLE #__firma
  DROP COLUMN schatten;",
   'SELECT 1+1');
PREPARE stmt FROM @query;
EXECUTE stmt;

SELECT count(*) INTO @test
   FROM information_schema.columns
WHERE TABLE_SCHEMA = database()
   AND TABLE_NAME = '#__firma'
   AND COLUMN_NAME = 'startseite_breite';
SET @query = IF(@test > 0,
   "ALTER TABLE #__firma
  DROP COLUMN startseite_breite;",
   'SELECT 1+1');
PREPARE stmt FROM @query;
EXECUTE stmt;

SELECT count(*) INTO @test
   FROM information_schema.columns
WHERE TABLE_SCHEMA = database()
   AND TABLE_NAME = '#__firma'
   AND COLUMN_NAME = 'startseite_artikel';
SET @query = IF(@test > 0,
   "ALTER TABLE #__firma
  DROP COLUMN startseite_artikel;",
   'SELECT 1+1');
PREPARE stmt FROM @query;
EXECUTE stmt;

SELECT count(*) INTO @test
   FROM information_schema.columns
WHERE TABLE_SCHEMA = database()
   AND TABLE_NAME = '#__firma'
   AND COLUMN_NAME = 'startseite_reihen';
SET @query = IF(@test > 0,
   "ALTER TABLE #__firma
  DROP COLUMN startseite_reihen;",
   'SELECT 1+1');
PREPARE stmt FROM @query;
EXECUTE stmt;

SELECT count(*) INTO @test
   FROM information_schema.columns
WHERE TABLE_SCHEMA = database()
   AND TABLE_NAME = '#__firma'
   AND COLUMN_NAME = 'collage_on';
SET @query = IF(@test > 0,
   "ALTER TABLE #__firma
  DROP COLUMN collage_on;",
   'SELECT 1+1');
PREPARE stmt FROM @query;
EXECUTE stmt;

SELECT count(*) INTO @test
   FROM information_schema.columns
WHERE TABLE_SCHEMA = database()
   AND TABLE_NAME = '#__firma'
   AND COLUMN_NAME = 'slideshow_on';
SET @query = IF(@test > 0,
   "ALTER TABLE #__firma
  DROP COLUMN slideshow_on;",
   'SELECT 1+1');
PREPARE stmt FROM @query;
EXECUTE stmt;

SELECT count(*) INTO @test
   FROM information_schema.columns
WHERE TABLE_SCHEMA = database()
   AND TABLE_NAME = '#__firma'
   AND COLUMN_NAME = 'rechts_slide';
SET @query = IF(@test > 0,
   "ALTER TABLE #__firma
  DROP COLUMN rechts_slide;",
   'SELECT 1+1');
PREPARE stmt FROM @query;
EXECUTE stmt;

SELECT count(*) INTO @test
   FROM information_schema.columns
WHERE TABLE_SCHEMA = database()
   AND TABLE_NAME = '#__firma'
   AND COLUMN_NAME = 'thumb_over_check';
SET @query = IF(@test > 0,
   "ALTER TABLE #__firma
  DROP COLUMN thumb_over_check;",
   'SELECT 1+1');
PREPARE stmt FROM @query;
EXECUTE stmt;

SELECT count(*) INTO @test
   FROM information_schema.columns
WHERE TABLE_SCHEMA = database()
   AND TABLE_NAME = '#__firma'
   AND COLUMN_NAME = 'thumb_height';
SET @query = IF(@test > 0,
   "ALTER TABLE #__firma
  DROP COLUMN thumb_height;",
   'SELECT 1+1');
PREPARE stmt FROM @query;
EXECUTE stmt;

SELECT count(*) INTO @test
   FROM information_schema.columns
WHERE TABLE_SCHEMA = database()
   AND TABLE_NAME = '#__firma'
   AND COLUMN_NAME = 'thumb_width';
SET @query = IF(@test > 0,
   "ALTER TABLE #__firma
  DROP COLUMN thumb_width;",
   'SELECT 1+1');
PREPARE stmt FROM @query;
EXECUTE stmt;

SELECT count(*) INTO @test
   FROM information_schema.columns
WHERE TABLE_SCHEMA = database()
   AND TABLE_NAME = '#__firma'
   AND COLUMN_NAME = 'thumb_fix_width';
SET @query = IF(@test > 0,
   "ALTER TABLE #__firma
  DROP COLUMN thumb_fix_width;",
   'SELECT 1+1');
PREPARE stmt FROM @query;
EXECUTE stmt;

SELECT count(*) INTO @test
   FROM information_schema.columns
WHERE TABLE_SCHEMA = database()
   AND TABLE_NAME = '#__firma'
   AND COLUMN_NAME = 'motiv_upload_check';
SET @query = IF(@test > 0,
   "ALTER TABLE #__firma
  DROP COLUMN motiv_upload_check;",
   'SELECT 1+1');
PREPARE stmt FROM @query;
EXECUTE stmt;

SELECT count(*) INTO @test
   FROM information_schema.columns
WHERE TABLE_SCHEMA = database()
   AND TABLE_NAME = '#__firma'
   AND COLUMN_NAME = 'count_pics';
SET @query = IF(@test > 0,
   "ALTER TABLE #__firma
  DROP COLUMN count_pics;",
   'SELECT 1+1');
PREPARE stmt FROM @query;
EXECUTE stmt;

SELECT count(*) INTO @test
   FROM information_schema.columns
WHERE TABLE_SCHEMA = database()
   AND TABLE_NAME = '#__firma'
   AND COLUMN_NAME = 'home_check';
SET @query = IF(@test > 0,
   "ALTER TABLE #__firma
  DROP COLUMN home_check;",
   'SELECT 1+1');
PREPARE stmt FROM @query;
EXECUTE stmt;

SELECT count(*) INTO @test
   FROM information_schema.columns
WHERE TABLE_SCHEMA = database()
   AND TABLE_NAME = '#__firma'
   AND COLUMN_NAME = 'anmelden_mode';
SET @query = IF(@test > 0,
   "ALTER TABLE #__firma
  DROP COLUMN anmelden_mode;",
   'SELECT 1+1');
PREPARE stmt FROM @query;
EXECUTE stmt;

SELECT count(*) INTO @test
   FROM information_schema.columns
WHERE TABLE_SCHEMA = database()
   AND TABLE_NAME = '#__firma'
   AND COLUMN_NAME = 'merkliste_mode';
SET @query = IF(@test > 0,
   "ALTER TABLE #__firma
  DROP COLUMN merkliste_mode;",
   'SELECT 1+1');
PREPARE stmt FROM @query;
EXECUTE stmt;

SELECT count(*) INTO @test
   FROM information_schema.columns
WHERE TABLE_SCHEMA = database()
   AND TABLE_NAME = '#__firma'
   AND COLUMN_NAME = 'merkliste_mode';
SET @query = IF(@test > 0,
   "ALTER TABLE #__firma
  DROP COLUMN merkliste_mode;",
   'SELECT 1+1');
PREPARE stmt FROM @query;
EXECUTE stmt;

SELECT count(*) INTO @test
   FROM information_schema.columns
WHERE TABLE_SCHEMA = database()
   AND TABLE_NAME = '#__firma'
   AND COLUMN_NAME = 'suchfeld_mode';
SET @query = IF(@test > 0,
   "ALTER TABLE #__firma
  DROP COLUMN suchfeld_mode;",
   'SELECT 1+1');
PREPARE stmt FROM @query;
EXECUTE stmt;

SELECT count(*) INTO @test
   FROM information_schema.columns
WHERE TABLE_SCHEMA = database()
   AND TABLE_NAME = '#__firma'
   AND COLUMN_NAME = 'flaggen_mode';
SET @query = IF(@test > 0,
   "ALTER TABLE #__firma
  DROP COLUMN flaggen_mode;",
   'SELECT 1+1');
PREPARE stmt FROM @query;
EXECUTE stmt;

SELECT count(*) INTO @test
   FROM information_schema.columns
WHERE TABLE_SCHEMA = database()
   AND TABLE_NAME = '#__firma'
   AND COLUMN_NAME = 'icon_farbe';
SET @query = IF(@test > 0,
   "ALTER TABLE #__firma
  DROP COLUMN icon_farbe;",
   'SELECT 1+1');
PREPARE stmt FROM @query;
EXECUTE stmt;

SELECT count(*) INTO @test
   FROM information_schema.columns
WHERE TABLE_SCHEMA = database()
   AND TABLE_NAME = '#__firma'
   AND COLUMN_NAME = 'admin_size';
SET @query = IF(@test > 0,
   "ALTER TABLE #__firma
  DROP COLUMN admin_size;",
   'SELECT 1+1');
PREPARE stmt FROM @query;
EXECUTE stmt;

SELECT count(*) INTO @test
   FROM information_schema.columns
WHERE TABLE_SCHEMA = database()
   AND TABLE_NAME = '#__firma'
   AND COLUMN_NAME = 'haendler_manual';
SET @query = IF(@test > 0,
   "ALTER TABLE #__firma
  DROP COLUMN haendler_manual;",
   'SELECT 1+1');
PREPARE stmt FROM @query;
EXECUTE stmt;

SELECT count(*) INTO @test
   FROM information_schema.columns
WHERE TABLE_SCHEMA = database()
   AND TABLE_NAME = '#__firma'
   AND COLUMN_NAME = 'provision';
SET @query = IF(@test > 0,
   "ALTER TABLE #__firma
  DROP COLUMN provision;",
   'SELECT 1+1');
PREPARE stmt FROM @query;
EXECUTE stmt;

ALTER TABLE `#__firma` ADD `umlaut_links` ENUM('n','y') NOT NULL DEFAULT 'n' AFTER `telefon_aktiv`;
