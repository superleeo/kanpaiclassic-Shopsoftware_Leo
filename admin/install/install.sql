/*
###################################################################################
  Kanpai Classic Shopsoftware - Entwicklungsstand 06.2025

  Kanpai Classic - Web Development
  https://www.kanpaiclassic.com
  https://www.kanpaiclassic.com

  c Copyright by Kanpai Classic - Kanpai Classic Web Development


  Copyrightvermerke duerfen NICHT entfernt werden!

  ------------------------------------------------------------------------
  Dieses Programm ist eine Software von Kanpai Classic, Kanpai Classic Web Development.
  Diese Software/Website ist eine Einzelplatzlizenz und für den Betrieb auf einem Speicherplatz 1 Installation berechtigt.
  Die Veroeffentlichung dieses Programms erfolgt OHNE IRGENDEINE GARANTIE, sogar ohne
  die implizite Garantie der MARKTREIFE oder der VERWENDBARKEIT FUER EINEN BESTIMMTEN ZWECK.
  Diese Script darf nicht veroeffentlicht oder weitergeben werden. Es gilt das Urheberrecht.
  Diese Software darf nur mit schritflicher Genehmigung modifizieren werden.
  Es gelten die Ihnen mitgeteilten Lizenzbestimmungen.
  ------------------------------------------------------------------------
  Bei Verstoß gegen die Lizenzbedingungen kann die Lizenz jederzeit entzogen werden. Der Kaufpreises wird nicht erstattet.
  Wer gegen die Lizenzbedingungen verstoesst insbesondere bei illegalem Vertrieb oder Mehrfachnutzung des Scriptes  muss mit einer Vertragsstrafe von 50.000 Euro je Einzeldelikt rechnen!

##################################################################################
  Copyrightvermerke duerfen NICHT entfernt werden!
*/

SET SESSION sql_mode = 'STRICT_TRANS_TABLES,ALLOW_INVALID_DATES';

DROP TABLE IF EXISTS `#__admin_logins`;
CREATE TABLE IF NOT EXISTS`#__admin_logins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(255) NOT NULL,
  `login` varchar(32) NOT NULL,
  `pass` varchar(32) NOT NULL,
  `time` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__articles`;
CREATE TABLE IF NOT EXISTS `#__articles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned NOT NULL,
  `sort` int(10) unsigned DEFAULT NULL,
  `online` enum('n','y') NOT NULL DEFAULT 'y',
  `art_nr` varchar(30) NOT NULL,
  `netto` decimal(16,9) NOT NULL DEFAULT '0.000000000',
  `haendler_netto` DECIMAL(12,6) NOT NULL DEFAULT '0',
  `ge_netto` DECIMAL(16,9) NOT NULL DEFAULT '0',
  `angebot` decimal(16,9) NOT NULL DEFAULT '0.000000000',
  `angebot_active` enum('n','y') NOT NULL DEFAULT 'n',
  `menge` DECIMAL(12,5) NOT NULL DEFAULT '0',
  `ge_menge` FLOAT NOT NULL DEFAULT '0.00000',
  `merkmal1` smallint(5) unsigned NOT NULL,
  `wert1` smallint(5) unsigned NOT NULL,
  `merkmal2` smallint(5) unsigned NOT NULL,
  `wert2` smallint(5) unsigned NOT NULL,
  `gewicht` decimal(7,2) unsigned NOT NULL default '0.00',
  `filename` varchar(127) NOT NULL DEFAULT '',
  `filetyp` varchar(127) NOT NULL DEFAULT '',
  `gtin` VARCHAR(32) NOT NULL DEFAULT '',
  `mpn` VARCHAR(32) NOT NULL DEFAULT '',
  `imported` ENUM('n','y') NOT NULL DEFAULT 'n',
  `startbild` TINYINT UNSIGNED NOT NULL DEFAULT '1',
  `matrix` ENUM('n','y') NOT NULL DEFAULT 'n',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_parent_sort` (`parent_id`,`sort`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `#__articles_360grad`;
CREATE TABLE `#__articles_360grad` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) UNSIGNED NOT NULL,
  `sort` int(11) NOT NULL,
  `img_name` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_parent` (`parent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__articles_aehnliche`;
CREATE TABLE `#__articles_aehnliche` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) UNSIGNED NOT NULL,
  `zubehoer_id` int(10) UNSIGNED NOT NULL,
  `sort` tinyint(3) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idz_art_id` (`parent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

# 11
DROP TABLE IF EXISTS `#__articles_aehnliche_lang`;
CREATE TABLE `#__articles_aehnliche_lang` (
  `parent_id` int(10) UNSIGNED NOT NULL,
  `deu` varchar(256) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `eng` varchar(256) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `spa` varchar(256) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `dan` varchar(256) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `fin` varchar(256) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `fra` varchar(256) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `ita` varchar(256) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `nld` varchar(256) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `nor` varchar(256) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `por` varchar(256) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `swe` varchar(256) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `tue` varchar(256) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `rus` varchar(256) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `gri` varchar(256) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `ara` varchar(256) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`parent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `#__articles_images`;
CREATE TABLE `#__articles_images` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) UNSIGNED NOT NULL,
  `sort` tinyint(3) UNSIGNED NOT NULL,
  `image` varchar(128) NOT NULL,
  `count` SMALLINT UNSIGNED NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_parent_id_sort` (`parent_id`,`sort`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `#__articles_info`;
CREATE TABLE IF NOT EXISTS `#__articles_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `haendler_id` int(10) unsigned NOT NULL DEFAULT '0',
  `shop_id` int(10) unsigned DEFAULT NULL,
  `sortierung` int(11) NOT NULL DEFAULT '1',
  `childs` smallint(4) unsigned NOT NULL DEFAULT '1',
  `steuersatz` tinyint(5) NOT NULL,
  `name_deu` varchar(250) DEFAULT NULL,
  `desc_deu` text NULL,
  `name_eng` varchar(250) DEFAULT NULL,
  `desc_eng` text NULL,
  `name_spa` varchar(250) DEFAULT NULL,
  `desc_spa` text NULL,
  `name_dan` varchar(250) DEFAULT NULL,
  `desc_dan` text NULL,
  `name_fin` varchar(250) DEFAULT NULL,
  `desc_fin` text NULL,
  `name_fra` varchar(250) DEFAULT NULL,
  `desc_fra` text NULL,
  `name_ita` varchar(250) DEFAULT NULL,
  `desc_ita` text NULL,
  `name_nld` varchar(250) DEFAULT NULL,
  `desc_nld` text NULL,
  `name_nor` varchar(250) DEFAULT NULL,
  `desc_nor` text NULL,
  `name_por` varchar(250) DEFAULT NULL,
  `desc_por` text NULL,
  `name_swe` varchar(250) DEFAULT NULL,
  `desc_swe` text NULL,
  `name_tue` varchar(250) DEFAULT NULL,
  `desc_tue` text NULL,
  `name_rus` varchar(250) DEFAULT NULL,
  `desc_rus` text NULL,
  `name_gri` varchar(250) DEFAULT NULL,
  `desc_gri` text NULL,
  `name_ara` varchar(250) DEFAULT NULL,
  `desc_ara` text NULL,
  `image` varchar(250) NOT NULL DEFAULT '',
  `image_hover` VARCHAR(250) NOT NULL DEFAULT '',
  `staffelung` varchar(250) DEFAULT NULL,
  `grundeinheit` varchar(10) DEFAULT NULL,
  `grundeinheit_rechner` VARCHAR(10) NULL DEFAULT NULL,
  `ge_netto_aktiv` enum('n','y') DEFAULT 'n',
  `spalten2_check` ENUM('n','y') NOT NULL DEFAULT 'n',
  `versand_preis` decimal(10,5) DEFAULT '0.00000',
  `masse_check` ENUM('n','y') NOT NULL DEFAULT 'n' ,
  `masse_min` DECIMAL(10,6) NOT NULL DEFAULT '1' ,
  `masse_komma` SMALLINT UNSIGNED NOT NULL DEFAULT '0' ,
  `rechner_check` ENUM('n','y') NOT NULL DEFAULT 'n' ,
  `rechner_mode` TINYINT UNSIGNED NOT NULL DEFAULT '2',
  `gewicht` decimal(10,5) DEFAULT '0.50000',
  `gew_check` ENUM('n','y') NOT NULL DEFAULT 'n' ,
  `widerruf` TINYINT UNSIGNED NOT NULL DEFAULT '1' ,
  `lieferfrist` VARCHAR(8) NOT NULL DEFAULT '3' ,
  `is_foto` ENUM( 'n', 'y' ) NOT NULL DEFAULT 'n',
  `foto` VARCHAR( 256 ) NOT NULL DEFAULT '',
  `foto_set` INT(10) UNSIGNED NULL DEFAULT '0' ,
  `org_set` INT UNSIGNED NOT NULL DEFAULT '0' ,
  `foto_size_x` INT UNSIGNED NULL ,
  `foto_size_y` INT UNSIGNED NULL ,
  `motiv_uploadp_check` ENUM('n','y') NOT NULL DEFAULT 'n' ,
  `motiv_uploadt_check` ENUM('n','y') NOT NULL DEFAULT 'n' ,
  `artikelgruppe` INT NOT NULL DEFAULT '0',
  `marke` VARCHAR(32) NOT NULL DEFAULT '' ,
  `vpe` VARCHAR(32) NOT NULL DEFAULT '' ,
  `vpm` VARCHAR(32) NOT NULL DEFAULT '' ,
  `configurator_check` enum('n','y') NOT NULL DEFAULT 'n',
  `configurator_artnr_check` ENUM('n','y') NOT NULL DEFAULT 'n',
  `configurator` TEXT NULL,
  `config_einheit_check` ENUM('n','y') NOT NULL DEFAULT 'n' ,
  `config_menge_check` ENUM('n','y') NOT NULL DEFAULT 'y' ,
  `timer_check` ENUM('n','y') NOT NULL DEFAULT 'n' ,
  `timer_end` TIMESTAMP NOT NULL DEFAULT '1970-01-02 00:00:00' ,
  `timer_menge` INT UNSIGNED NOT NULL DEFAULT '0' ,
  `timer_anzeige` ENUM('n','y') NOT NULL DEFAULT 'y' ,
  `timer_art_disable` ENUM('n','y') NOT NULL DEFAULT 'n' ,
  `clicks` INT UNSIGNED NOT NULL DEFAULT '0' ,
  `show_object` ENUM('n','y') NOT NULL DEFAULT 'n' ,
  `fsk_check` ENUM('n','y') NOT NULL DEFAULT 'n' ,
  `neu_check` ENUM('n','y') NOT NULL DEFAULT 'n',
  `ab_check` ENUM('n','y') NOT NULL DEFAULT 'n',
  `mixer_artikel_check` ENUM('n','y') NOT NULL DEFAULT 'n' ,
  `naehrwerte_check` ENUM('n','y') NOT NULL DEFAULT 'n',
  `mixer_gewicht_check` ENUM('n','y') NOT NULL DEFAULT 'n',
  `mixer_gewicht` DECIMAL(8,3) NOT NULL DEFAULT '0',
  `mixer_naehrwerte_check` ENUM('n','y') NOT NULL DEFAULT 'n',
  `360grad_check` ENUM('n','y') NOT NULL DEFAULT 'n',
  `spedition` TINYINT UNSIGNED NOT NULL DEFAULT '0',
  `marke_aktiv` ENUM('n','y') NULL DEFAULT 'n',
  `versandfrei_check` ENUM('n','y') NULL DEFAULT 'n'  /*AFTER `marke_aktiv`*/,
  `artikelgrafik1_check` ENUM('n','y') NOT NULL DEFAULT 'n',
  `artikelgrafik2_check` ENUM('n','y') NOT NULL DEFAULT 'n',
  `artikelgrafik3_check` ENUM('n','y') NOT NULL DEFAULT 'n',
  `artikelgrafik4_check` ENUM('n','y') NOT NULL DEFAULT 'n',
  `artikelgrafik5_check` ENUM('n','y') NOT NULL DEFAULT 'n',
  `artikelgrafik6_check` ENUM('n','y') NOT NULL DEFAULT 'n',
  `energy_efficiency` VARCHAR(25) NULL DEFAULT NULL, 
  `energy_efficiency_image` VARCHAR(250) NULL,
PRIMARY KEY (`id`),
  KEY `sortierung` (`sortierung`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `#__articles_mixer`;
CREATE TABLE `#__articles_mixer` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) UNSIGNED NOT NULL,
  `article_id` int(10) UNSIGNED NOT NULL,
  `sort` tinyint(3) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_art_id` (`parent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `#__articles_naehrwerte`;
CREATE TABLE `#__articles_naehrwerte` (
  `parent_id` int(10) UNSIGNED NOT NULL,
  `brennwert` decimal(12,2) DEFAULT '0.00',
  `fett` decimal(12,2) NOT NULL DEFAULT '0.00',
  `f_saeure` decimal(12,2) NOT NULL DEFAULT '0.00',
  `k_hydrate` decimal(12,2) NOT NULL DEFAULT '0.00',
  `zucker` decimal(12,2) NOT NULL DEFAULT '0.00',
  `ballast` decimal(12,2) NOT NULL DEFAULT '0.00',
  `eiweiss` decimal(12,2) NOT NULL DEFAULT '0.00',
  `salz` decimal(12,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`parent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

# 21
DROP TABLE IF EXISTS `#__articles_seo`;
CREATE TABLE `#__articles_seo` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) UNSIGNED NOT NULL,
  `lang` varchar(10) NOT NULL,
  `metaauto` enum('n','y') NOT NULL DEFAULT 'y',
  `metatitle` varchar(256) NOT NULL DEFAULT '',
  `metadesc` varchar(2048) NOT NULL DEFAULT '',
  `metakey` varchar(512) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_parent_lang` (`parent_id`,`lang`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `#__article_to_cats`;
CREATE TABLE `#__article_to_cats` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) UNSIGNED NOT NULL,
  `cat_id` int(10) UNSIGNED NOT NULL,
  `sort` tinyint(3) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_article` (`parent_id`),
  KEY `idx_cat` (`cat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__articles_to_ebaycats`;
CREATE TABLE IF NOT EXISTS `#__articles_to_ebaycats` (
  `article_id` int(10) unsigned NOT NULL,
  `cat_ids` varchar(50) NOT NULL,
  `auktion` enum('n','y') NOT NULL DEFAULT 'n',
  `festpreis` decimal(9,2) NOT NULL,
  `startpreis` decimal(9,2) NOT NULL,
  `vorschlag` enum('n','y') NOT NULL DEFAULT 'n',
  `vorschlag_ok` decimal(9,2) DEFAULT NULL,
  `vorschlag_min` decimal(9,2) DEFAULT NULL,
  `neu` enum('n','y') NOT NULL DEFAULT 'y',
  `dauer` enum('n','y') NOT NULL DEFAULT 'n',
  `dauer_tage` int(11) NOT NULL DEFAULT '10',
  `menge` int(10) unsigned NOT NULL,
  `varianten` ENUM('n','y') NOT NULL DEFAULT 'n',
  `options` VARCHAR(4096) NOT NULL DEFAULT '',
  `item_id` VARCHAR(20) NOT NULL DEFAULT '' ,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`article_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `#__articles_to_googlecats`;
CREATE TABLE IF NOT EXISTS `#__articles_to_googlecats` (
  `parent_id` int(10) unsigned NOT NULL,
  `categories` varchar(50) NOT NULL,
  `zustand` enum('n','g') NOT NULL DEFAULT 'n',
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`parent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `#__articles_zutaten`;
CREATE TABLE `#__articles_zutaten` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) UNSIGNED NOT NULL,
  `lang` varchar(3) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_parent_lang_title` (`parent_id`,`lang`,`title`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

# 31
DROP TABLE IF EXISTS `#__bewertung`;
CREATE TABLE IF NOT EXISTS `#__bewertung` (
  `best_id` int(10) unsigned NOT NULL,
  `datum` datetime NOT NULL,
  `email` varchar(200) NOT NULL,
  PRIMARY KEY (`best_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `#__categories`;
CREATE TABLE IF NOT EXISTS `#__categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned NOT NULL,
  `active` enum('n','y') NOT NULL DEFAULT 'y',
  `network_id` int(10) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(4) NOT NULL,
  `ordered` int unsigned NOT NULL DEFAULT '0',
  `childs` tinyint(4) NOT NULL DEFAULT '0',

  `name_deu` varchar(50) NOT NULL DEFAULT '',
  `desc_deu` text NULL,
  `description_deu` text NULL ,
  `keywords_deu` text NULL,
  `title_deu` VARCHAR(256) NOT NULL DEFAULT '',

  `name_eng` varchar(50) NOT NULL DEFAULT '',
  `desc_eng` text NULL,
  `description_eng` text NULL,
  `keywords_eng` text NULL,
  `title_eng` VARCHAR(256) NOT NULL DEFAULT '',

  `name_spa` varchar(50) NOT NULL DEFAULT '',
  `desc_spa` text NULL,
  `description_spa` text NULL,
  `keywords_spa` text NULL,
  `title_spa` VARCHAR(256) NOT NULL DEFAULT '',

  `name_dan` varchar(50) NOT NULL DEFAULT '',
  `desc_dan` text NULL,
  `description_dan` text NULL,
  `keywords_dan` text NULL,
  `title_dan` VARCHAR(256) NOT NULL DEFAULT '',

  `name_fin` varchar(50) NOT NULL DEFAULT '',
  `desc_fin` text NULL,
  `description_fin` text NULL,
  `keywords_fin` text NULL,
  `title_fin` VARCHAR(256) NOT NULL DEFAULT '',

  `name_fra` varchar(50) NOT NULL DEFAULT '',
  `desc_fra` text NULL,
  `description_fra` text NULL,
  `keywords_fra` text NULL,
  `title_fra` VARCHAR(256) NOT NULL DEFAULT '',

  `name_ita` varchar(50) NOT NULL DEFAULT '',
  `desc_ita` text NULL,
  `description_ita` text NULL,
  `keywords_ita` text NULL,
  `title_ita` VARCHAR(256) NOT NULL DEFAULT '',

  `name_nld` varchar(50) NOT NULL DEFAULT '',
  `desc_nld` text NULL,
  `description_nld` text NULL,
  `keywords_nld` text NULL,
  `title_nld` VARCHAR(256) NOT NULL DEFAULT '',

  `name_nor` varchar(50) NOT NULL DEFAULT '',
  `desc_nor` text NULL,
  `description_nor` text NULL,
  `keywords_nor` text NULL,
  `title_nor` VARCHAR(256) NOT NULL DEFAULT '',

  `name_por` varchar(50) NOT NULL DEFAULT '',
  `desc_por` text NULL,
  `description_por` text NULL,
  `keywords_por` text NULL,
  `title_por` VARCHAR(256) NOT NULL DEFAULT '',

  `name_swe` varchar(50) NOT NULL DEFAULT '',
  `desc_swe` text NULL,
  `description_swe` text NULL,
  `keywords_swe` text NULL,
  `title_swe` VARCHAR(256) NOT NULL DEFAULT '',

  `name_tue` varchar(50) NOT NULL DEFAULT '',
  `desc_tue` text NULL,
  `description_tue` text NULL,
  `keywords_tue` text NULL,
  `title_tue` VARCHAR(256) NOT NULL DEFAULT '',

  `name_rus` varchar(50) NOT NULL DEFAULT '',
  `desc_rus` text NULL,
  `description_rus` text NULL,
  `keywords_rus` text NULL,
  `title_rus` VARCHAR(256) NOT NULL DEFAULT '',

  `name_gri` varchar(50) NOT NULL DEFAULT '',
  `desc_gri` text NULL,
  `description_gri` text NULL,
  `keywords_gri` text NULL,
  `title_gri` VARCHAR(256) NOT NULL DEFAULT '',

  `name_ara` varchar(50) NOT NULL DEFAULT '',
  `desc_ara` text NULL,
  `description_ara` text NULL,
  `keywords_ara` text NULL,
  `title_ara` VARCHAR(256) NOT NULL DEFAULT '',

  `cat_pass` varchar(32) NOT NULL DEFAULT '',
  `artikel` int(10) unsigned NOT NULL DEFAULT '0',
  `markenfilter` ENUM('n','y') NOT NULL DEFAULT 'n',
  `clicks` INT UNSIGNED NOT NULL DEFAULT '0',
  `show_text` ENUM('n','y') NOT NULL DEFAULT 'n',
  `hide_articles` ENUM('n','y') NOT NULL DEFAULT 'n',
  `alter_check` ENUM('n','y') NOT NULL DEFAULT 'n',
  `filter_active` ENUM('n','y') NOT NULL DEFAULT 'n',
  `filter_json` VARCHAR(1024) NOT NULL DEFAULT '',
  `mixer_check` ENUM('n','y') NOT NULL DEFAULT 'n',
  `mixer_gewicht` DECIMAL(12,5) NOT NULL DEFAULT '0',
  `mixer_einheit_g` VARCHAR(10) NOT NULL DEFAULT 'g',
  `gewicht_check` ENUM('n','y') NOT NULL DEFAULT 'n',
  `naehrwerte_check` ENUM('n','y') NOT NULL DEFAULT 'n',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`),
  KEY `idx_active` (`active`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `#__articles_zubehoer`;
CREATE TABLE `#__articles_zubehoer` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) UNSIGNED NOT NULL,
  `zubehoer_id` int(10) UNSIGNED NOT NULL,
  `sort` tinyint(3) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idz_art_id` (`parent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `#__articles_zubehoer_lang`;
CREATE TABLE `#__articles_zubehoer_lang` (
  `parent_id` int(10) UNSIGNED NOT NULL,
  `deu` varchar(256) NOT NULL DEFAULT '',
  `eng` varchar(256) NOT NULL DEFAULT '',
  `spa` varchar(256) NOT NULL DEFAULT '',
  `dan` varchar(256) NOT NULL DEFAULT '',
  `fin` varchar(256) NOT NULL DEFAULT '',
  `fra` varchar(256) NOT NULL DEFAULT '',
  `ita` varchar(256) NOT NULL DEFAULT '',
  `nld` varchar(256) NOT NULL DEFAULT '',
  `nor` varchar(256) NOT NULL DEFAULT '',
  `por` varchar(256) NOT NULL DEFAULT '',
  `swe` varchar(256) NOT NULL DEFAULT '',
  `tue` varchar(256) NOT NULL DEFAULT '',
  `rus` varchar(256) NOT NULL DEFAULT '',
  `gri` varchar(256) NOT NULL DEFAULT '',
  `ara` varchar(256) NOT NULL DEFAULT '',
  PRIMARY KEY (`parent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `#__categorie_images`;
CREATE TABLE `#__categorie_images` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `cat_id` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `lang` varchar(3) NOT NULL DEFAULT '',
  `img1` varchar(32) NOT NULL DEFAULT '',
  `img2` varchar(32) NOT NULL DEFAULT '',
  `img3` varchar(32) NOT NULL DEFAULT '',
  `img4` varchar(32) NOT NULL DEFAULT '',
  `img5` varchar(32) NOT NULL DEFAULT '',
  `img6` varchar(32) NOT NULL DEFAULT '',
  `mixer1` VARCHAR(32) NOT NULL DEFAULT '',
  `mixer2` VARCHAR(32) NOT NULL DEFAULT '',
  `mixer3` VARCHAR(32) NOT NULL DEFAULT '',
  `link1` varchar(256) NOT NULL DEFAULT '',
  `link2` varchar(256) NOT NULL DEFAULT '',
  `link3` varchar(256) NOT NULL DEFAULT '',
  `link4` varchar(256) NOT NULL DEFAULT '',
  `link5` varchar(256) NOT NULL DEFAULT '',
  `link6` varchar(256) NOT NULL DEFAULT '',
  `intern1` enum('n','y') NOT NULL DEFAULT 'y',
  `intern2` enum('n','y') NOT NULL DEFAULT 'y',
  `intern3` enum('n','y') NOT NULL DEFAULT 'y',
  `intern4` enum('n','y') NOT NULL DEFAULT 'y',
  `intern5` enum('n','y') NOT NULL DEFAULT 'y',
  `intern6` enum('n','y') NOT NULL DEFAULT 'y',
  `search1` varchar(256) NOT NULL DEFAULT '',
  `search2` varchar(256) NOT NULL DEFAULT '',
  `search3` varchar(256) NOT NULL DEFAULT '',
  `search4` varchar(256) NOT NULL DEFAULT '',
  `search5` varchar(256) NOT NULL DEFAULT '',
  `search6` varchar(256) NOT NULL DEFAULT '',
  `anzahl` INT NOT NULL DEFAULT '1',
  `images` VARCHAR(10240) NOT NULL DEFAULT '',
  `options` VARCHAR(256) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `car_id_lang` (`cat_id`,`lang`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

# 41
DROP TABLE IF EXISTS `#__configurator_merkmale`;
CREATE TABLE IF NOT EXISTS `#__configurator_merkmale` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `merkmal_deu` varchar(32) NOT NULL DEFAULT '',
  `merkmal_eng` varchar(32) NOT NULL DEFAULT '',
  `merkmal_spa` varchar(32) NOT NULL DEFAULT '',
  `merkmal_dan` varchar(32) NOT NULL DEFAULT '',
  `merkmal_fin` varchar(32) NOT NULL DEFAULT '',
  `merkmal_fra` varchar(32) NOT NULL DEFAULT '',
  `merkmal_ita` varchar(32) NOT NULL DEFAULT '',
  `merkmal_nld` varchar(32) NOT NULL DEFAULT '',
  `merkmal_nor` varchar(32) NOT NULL DEFAULT '',
  `merkmal_por` varchar(32) NOT NULL DEFAULT '',
  `merkmal_swe` varchar(32) NOT NULL DEFAULT '',
  `merkmal_tue` varchar(32) NOT NULL DEFAULT '',
  `merkmal_rus` varchar(32) NOT NULL DEFAULT '',
  `merkmal_gri` varchar(32) NOT NULL DEFAULT '',
  `merkmal_ara` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `#__configurator_texte`;
CREATE TABLE IF NOT EXISTS `#__configurator_texte` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `text_deu` varchar(256) NOT NULL DEFAULT '',
  `text_eng` varchar(256) NOT NULL DEFAULT '',
  `text_spa` varchar(256) NOT NULL DEFAULT '',
  `text_dan` varchar(256) NOT NULL DEFAULT '',
  `text_fin` varchar(256) NOT NULL DEFAULT '',
  `text_fra` varchar(256) NOT NULL DEFAULT '',
  `text_ita` varchar(256) NOT NULL DEFAULT '',
  `text_nld` varchar(256) NOT NULL DEFAULT '',
  `text_nor` varchar(256) NOT NULL DEFAULT '',
  `text_por` varchar(256) NOT NULL DEFAULT '',
  `text_swe` varchar(256) NOT NULL DEFAULT '',
  `text_tue` varchar(256) NOT NULL DEFAULT '',
  `text_rus` varchar(256) NOT NULL DEFAULT '',
  `text_gri` varchar(256) NOT NULL DEFAULT '',
  `text_ara` varchar(256) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `#__configurator_werte`;
CREATE TABLE IF NOT EXISTS `#__configurator_werte` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `merkmal_id` int(11) NOT NULL,
  `wert_deu` varchar(32) NOT NULL DEFAULT '',
  `wert_eng` varchar(32) NOT NULL DEFAULT '',
  `wert_spa` varchar(32) NOT NULL DEFAULT '',
  `wert_dan` varchar(32) NOT NULL DEFAULT '',
  `wert_fin` varchar(32) NOT NULL DEFAULT '',
  `wert_fra` varchar(32) NOT NULL DEFAULT '',
  `wert_ita` varchar(32) NOT NULL DEFAULT '',
  `wert_nld` varchar(32) NOT NULL DEFAULT '',
  `wert_nor` varchar(32) NOT NULL DEFAULT '',
  `wert_por` varchar(32) NOT NULL DEFAULT '',
  `wert_swe` varchar(32) NOT NULL DEFAULT '',
  `wert_tue` varchar(32) NOT NULL DEFAULT '',
  `wert_rus` varchar(32) NOT NULL DEFAULT '',
  `wert_gri` varchar(32) NOT NULL DEFAULT '',
  `wert_ara` varchar(32) NOT NULL DEFAULT '',
  `wert_img` varchar(64) NOT NULL DEFAULT '',
  `price_add` decimal(8,4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `#__cookies`;
CREATE TABLE IF NOT EXISTS `#__cookies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bezeichnung` varchar(32) NOT NULL DEFAULT '',
  `lang` varchar(3) NOT NULL,
  `value` varchar(4096) NOT NULL DEFAULT '',
   PRIMARY KEY (`id`),
   UNIQUE KEY `idx_bez_lang` (`bezeichnung`,`lang`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `#__cronjobs`;
CREATE TABLE IF NOT EXISTS `#__cronjobs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `done` enum('n','y') NOT NULL DEFAULT 'n',
  `type` varchar(16) NOT NULL DEFAULT '',
  `overwrite` enum('n','y') NOT NULL DEFAULT 'n',
  `import_url` varchar(1024) NOT NULL DEFAULT '',
  `import_file` VARCHAR(1024) NOT NULL DEFAULT '',
  `import_images` enum('n','y') NOT NULL DEFAULT 'n',
  `start` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `count` int(10) unsigned NOT NULL DEFAULT '0',
  `status` varchar(32) NOT NULL DEFAULT '',
  `statistik` varchar(8192) NOT NULL DEFAULT '',
  `haendler_id` int(10) unsigned NOT NULL DEFAULT '0',
  `crash` INT UNSIGNED NOT NULL DEFAULT '0',
  `crashinfo` VARCHAR(4096) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#51
DROP TABLE IF EXISTS `#__cron_articles`;
CREATE TABLE IF NOT EXISTS `#__cron_articles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned NOT NULL,
  `cronjob_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `#__cron_crash`;
CREATE TABLE IF NOT EXISTS `#__cron_crash` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `crash` int(10) unsigned NOT NULL,
  `crashinfo` varchar(4096) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `#__crosspromo`;
CREATE TABLE IF NOT EXISTS `#__crosspromo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned NOT NULL,
  `active` enum('n','y') NOT NULL DEFAULT 'y',
  `lang` varchar(3) NOT NULL,
  `data` varchar(8192) NOT NULL,
  `title` VARCHAR(64) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `parent_id` (`parent_id`,`lang`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `#__data`;
CREATE TABLE IF NOT EXISTS `#__data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(32) NOT NULL,
  `data` TEXT NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_type` (`type`)
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

# 61
DROP TABLE IF EXISTS `#__dhl_status`;
CREATE TABLE IF NOT EXISTS `#__dhl_status` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `re_id` int(10) unsigned NOT NULL,
  `startdate` datetime DEFAULT NULL,
  `enddate` datetime NOT NULL DEFAULT '1970-01-02 00:00:00',
  `sendungs_nr` varchar(32) NOT NULL DEFAULT '',
  `status` varchar(10) NOT NULL DEFAULT '',
  `msg` varchar(20000) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `re_id` (`re_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
ALTER TABLE `#__dhl_status` ADD INDEX(`re_id`);

DROP TABLE IF EXISTS `#__downloads`;
CREATE TABLE IF NOT EXISTS `#__downloads` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rechnung_id` int(10) unsigned NOT NULL,
  `filename` varchar(128) NOT NULL,
  `mime_type` varchar(64) NOT NULL,
  `link` varchar(128) NOT NULL,
  `sort` INT UNSIGNED NOT NULL DEFAULT '0' ,
  `artikel_id` INT UNSIGNED NOT NULL DEFAULT '0' ,
  `count` tinyint(3) unsigned NOT NULL,
  `last_upload` datetime NOT NULL,
  `valid` date NOT NULL,
  `allowed` enum('n','y') NOT NULL DEFAULT 'y',
  PRIMARY KEY (`id`),
  KEY `idx_rechnung_id` (`rechnung_id`),
  KEY `idx_link` (`link`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `#__ebay_cats`;
CREATE TABLE IF NOT EXISTS `#__ebay_cats` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `cat_id` int DEFAULT 0,
  `parent` int DEFAULT 0,
  `level` int DEFAULT 0,
  `cat_name` varchar(255) DEFAULT '',
  `cat_options` MEDIUMTEXT NULL,
    PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `#__firma`;
CREATE TABLE IF NOT EXISTS `#__firma` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shop_name` varchar(50) NOT NULL,
  `shop_name_check` enum('n','y') NOT NULL DEFAULT 'n',
  `firm_name` varchar(50) NOT NULL,
  `firm_name_check` enum('n','y') NOT NULL DEFAULT 'y',
  `first_name` varchar(50) NOT NULL,
  `first_name_check` enum('n','y') NOT NULL DEFAULT 'y',
  `last_name` varchar(50) NOT NULL,
  `last_name_check` enum('n','y') NOT NULL DEFAULT 'y',
  `street` varchar(50) NOT NULL,
  `haus_nr` VARCHAR(8) NOT NULL DEFAULT '',
  `street_check` enum('n','y') NOT NULL DEFAULT 'y',
  `postal_code` varchar(10) NOT NULL DEFAULT '',
  `postal_code_check` enum('n','y') NOT NULL DEFAULT 'y',
  `city` varchar(50) NOT NULL,
  `city_check` enum('n','y') NOT NULL DEFAULT 'y',
  `country` varchar(50) NOT NULL,
  `country_check` enum('n','y') NOT NULL DEFAULT 'y',
  `email` varchar(50) NOT NULL,
  `email_check` enum('n','y') NOT NULL DEFAULT 'y',
  `mailfrom` varchar(50) NOT NULL,
  `mailfrom_check` enum('n','y') NOT NULL DEFAULT 'y',
  `telefon` varchar(25) NOT NULL,
  `telefon_check` enum('n','y') NOT NULL DEFAULT 'y',
  `fax` varchar(25) NOT NULL DEFAULT '',
  `fax_check` enum('n','y') NOT NULL DEFAULT 'y',
  `email2` varchar(50) NOT NULL DEFAULT '',
  `email2_check` enum('n','y') NOT NULL DEFAULT 'y',
  `paypal_mail` varchar(50) NOT NULL DEFAULT '',
  `paypal_mail_check` enum('n','y') NOT NULL DEFAULT 'n',
  `web` varchar(50) NOT NULL DEFAULT '',
  `web_check` enum('n','y') NOT NULL DEFAULT 'y',
  `finanzamt` varchar(50) NOT NULL DEFAULT '',
  `finanzamt_check` enum('n','y') NOT NULL DEFAULT 'n',
  `steuernr` varchar(50) NOT NULL DEFAULT '',
  `steuernr_check` enum('n','y') NOT NULL DEFAULT 'y',
  `ustid` varchar(50) NOT NULL DEFAULT '',
  `ustid_check` enum('n','y') NOT NULL DEFAULT 'y',
  `bank1` varchar(50) NOT NULL DEFAULT '',
  `bank1_check` enum('n','y') NOT NULL DEFAULT 'y',
  `bank1_inhaber` varchar(50) NOT NULL DEFAULT '',
  `bank1_inhaber_check` enum('y','n') NOT NULL DEFAULT 'y',
  `bank1_iban` varchar(50) NOT NULL DEFAULT '',
  `bank1_iban_check` enum('n','y') NOT NULL DEFAULT 'y',
  `bank1_bic` varchar(50) NOT NULL DEFAULT '',
  `bank1_bic_check` enum('n','y') NOT NULL DEFAULT 'n',
  `default_lang` varchar(3) NOT NULL,
  `langs` varchar(100) NOT NULL,
  `network` enum('n','y') NOT NULL DEFAULT 'n',
  `kleingewerbe` enum('n','y') NOT NULL DEFAULT 'n',
  `tax_active` enum('n','y') NOT NULL DEFAULT 'y',
  `tax_show` enum('n','y') NOT NULL DEFAULT 'y',
  `price_login` enum('n','y') NOT NULL DEFAULT 'n',
  `account_manual` enum('n','y') NOT NULL DEFAULT 'n',
  `tax1` float NOT NULL DEFAULT '19',
  `check_tax1` enum('n','y') NOT NULL DEFAULT 'y',
  `tax2` float NOT NULL DEFAULT '7',
  `check_tax2` enum('n','y') NOT NULL DEFAULT 'y',
  `tax3` float NOT NULL DEFAULT '0',
  `tax_ch_check` ENUM('n','y') NOT NULL DEFAULT 'y',
  `tax_eu_check` ENUM('n','y') NOT NULL DEFAULT 'n',
  `check_tax3` enum('n','y') NOT NULL DEFAULT 'y',
  `template` varchar(25) NOT NULL DEFAULT '',
  `check_w2` enum('n','y') NOT NULL DEFAULT 'n',
  `check_w3` enum('n','y') NOT NULL DEFAULT 'n',
  `check_w4` enum('n','y') NOT NULL DEFAULT 'n',
  `kurs2` decimal(16,8) NOT NULL DEFAULT '1.21506000',
  `kurs3` decimal(16,8) NOT NULL DEFAULT '0.84936000',
  `kurs4` decimal(16,8) NOT NULL DEFAULT '1.30000017',
  `waehrung1` tinyint(4) NOT NULL DEFAULT '1',
  `waehrung2` tinyint(4) NOT NULL DEFAULT '4',
  `waehrung3` tinyint(4) NOT NULL DEFAULT '2',
  `waehrung4` tinyint(4) NOT NULL DEFAULT '3',
  `lager_show` enum('n','y') NOT NULL DEFAULT 'y',
  `lager_abziehen` enum('n','y') NOT NULL DEFAULT 'y',
  `lager_leer` enum('y','n') NOT NULL DEFAULT 'y',
  `lager_bestell_check` ENUM('n','y') NOT NULL DEFAULT 'n',
  `lager_zeit` TINYINT UNSIGNED NOT NULL DEFAULT '0',
  `lager_deaktiviert` enum('y','n') NOT NULL DEFAULT 'n',
  `versandart_1` tinyint(4) NOT NULL DEFAULT '3',
  `versandart_2` tinyint(4) NOT NULL DEFAULT '3',
  `versandart_3` tinyint(4) NOT NULL DEFAULT '3',
  `versandart_land` INT UNSIGNED NOT NULL DEFAULT '160',
  `versandkosten_1` VARCHAR(512) NOT NULL DEFAULT '{"versandkosten1":3.28,"versandkosten2":3.28,"versandkosten3":3.28,"versandwert2":8.4,"versandwert4":42.02,"gewichtkosten1":0.78,"gewichtkosten2":1.22,"gewichtkosten3":3.28,"gewichtkosten4":5.8,"gewichtkosten5":7.56,"gewichtwert1":0.02,"gewichtwert2":0.05,"gewichtwert3":0.5,"gewichtwert4":1}',
  `versandkosten_2` VARCHAR(512) NOT NULL DEFAULT '{"versandkosten1":5.8,"versandkosten2":5.8,"versandkosten3":5.8,"versandwert2":8.4,"versandwert4":42.02,"gewichtkosten1":0.78,"gewichtkosten2":1.22,"gewichtkosten3":3.28,"gewichtkosten4":5.8,"gewichtkosten5":7.56,"gewichtwert1":0.02,"gewichtwert2":0.05,"gewichtwert3":0.5,"gewichtwert4":1}',
  `versandkosten_3` VARCHAR(512) NOT NULL DEFAULT '{"versandkosten1":5.8,"versandkosten2":5.8,"versandkosten3":5.8,"versandwert2":8.4,"versandwert4":42.02,"gewichtkosten1":0.78,"gewichtkosten2":1.22,"gewichtkosten3":3.28,"gewichtkosten4":5.8,"gewichtkosten5":7.56,"gewichtwert1":0.02,"gewichtwert2":0.05,"gewichtwert3":0.5,"gewichtwert4":1}',
  `versand_gewicht_1` decimal(6,2) NOT NULL DEFAULT '0.00',
  `versand_gewicht_2` decimal(6,2) NOT NULL DEFAULT '0.00',
  `versand_gewicht_3` decimal(6,2) NOT NULL DEFAULT '0.00',
  `versand_stueck_1` decimal(6,2) NOT NULL DEFAULT '10.00',
  `versand_stueck_2` decimal(6,2) NOT NULL DEFAULT '10.00',
  `versand_stueck_3` decimal(6,2) NOT NULL DEFAULT '10.00',
  `abholung_check_1` enum('n','y') NOT NULL DEFAULT 'y',
  `abholung_check_2` enum('n','y') NOT NULL DEFAULT 'y',
  `abholung_check_3` enum('n','y') NOT NULL DEFAULT 'y',
  `abholung_preis_1` decimal(8,2) NOT NULL DEFAULT '0.00',
  `abholung_preis_2` decimal(8,2) NOT NULL DEFAULT '0.00',
  `abholung_preis_3` decimal(8,2) NOT NULL DEFAULT '0.00',
  `min_preis_1` DECIMAL(8,2) NOT NULL DEFAULT '0',
  `min_preis_2` DECIMAL(8,2) NOT NULL DEFAULT '0',
  `min_preis_3` DECIMAL(8,2) NOT NULL DEFAULT '0',
  `min_preis_check_1` ENUM('n','y') NOT NULL DEFAULT 'n',
  `min_preis_check_2` ENUM('n','y') NOT NULL DEFAULT 'n',
  `min_preis_check_3` ENUM('n','y') NOT NULL DEFAULT 'n',
  `check_vers_frei_1` enum('n','y') NOT NULL DEFAULT 'n',
  `check_vers_frei_2` enum('n','y') NOT NULL DEFAULT 'n',
  `check_vers_frei_3` enum('n','y') NOT NULL DEFAULT 'n',
  `vers_grafik_check` ENUM('n','y') NOT NULL DEFAULT 'n',
  `mindest_check` ENUM('n','y') NOT NULL DEFAULT 'y',
  `vers_frei_1` decimal(6,2) NOT NULL DEFAULT '100.00',
  `vers_frei_2` decimal(6,2) NOT NULL DEFAULT '100.00',
  `vers_frei_3` decimal(6,2) NOT NULL DEFAULT '100.00',
  `gewicht_detail_check` ENUM('n','y') NOT NULL DEFAULT 'y',
  `dhl_is_user` VARCHAR(32) NOT NULL DEFAULT '' ,
  `dhl_is_sign` VARCHAR(32) NOT NULL DEFAULT '' ,
  `dhl_is_ekp`  VARCHAR(32) NOT NULL DEFAULT '' ,
  `dhl_paket` TINYINT NOT NULL DEFAULT '1',
  `dhl_teilnehmer` VARCHAR(2) NOT NULL DEFAULT '01',
  `dhl_gewicht`   int(11) NOT NULL DEFAULT '100',
  `dhl_versand`   int(11) NOT NULL DEFAULT '1',
  `paypal_check` enum('n','y') NOT NULL DEFAULT 'n',
  `pp_test` enum('y','n') NOT NULL DEFAULT 'n',
  `pp_test_user` varchar(255) DEFAULT 'testmode@paypal.de',
  `paypal_preis` decimal(8,2) NOT NULL DEFAULT '0.00',
  `za_waehlen_check` ENUM('n','y') NOT NULL DEFAULT 'y' ,
  `vorkasse_check` enum('n','y') NOT NULL DEFAULT 'y',
  `vorkasse_preis` decimal(8,2) NOT NULL DEFAULT '0.00',
  `lastschrift_check` enum('n','y') NOT NULL DEFAULT 'n',
  `lastschrift_preis` DECIMAL(5,2) NOT NULL DEFAULT '0',
  `lastschrift_check_user` ENUM('n','y') NOT NULL DEFAULT 'n',
  `lastschrift_check_country` ENUM('n','y') NOT NULL DEFAULT 'n',
  `nachnahme_check` enum('y','n') NOT NULL DEFAULT 'n',
  `nachnahme_check_user` ENUM('n','y') NOT NULL DEFAULT 'n',
  `nachnahme_check_country` ENUM('n','y') NOT NULL DEFAULT 'n',
  `nachnahme_preis` float NOT NULL DEFAULT '4.00',
  `rechnung_check` enum('n','y')  NOT NULL DEFAULT 'y',
  `rechnung_preis` DECIMAL(5,2) NOT NULL DEFAULT '0',
  `rechnung_check_user` ENUM('n','y') NOT NULL DEFAULT 'n',
  `rechnung_check_country` ENUM('n','y') NOT NULL DEFAULT 'n',
  `bar_check` enum('n','y') NOT NULL DEFAULT 'y',
  `bar_preis` decimal(8,2) NOT NULL DEFAULT '0.00',
  `sofort_check` enum('n','y') NOT NULL DEFAULT 'n',
  `sofort_preis` decimal(8,2) NOT NULL DEFAULT '0.00',
  `sofort_key` varchar(64) NOT NULL DEFAULT '',
  `sofort_mail` enum('n','y') NOT NULL DEFAULT 'y',
  `vrpay_check` enum('n','y') NOT NULL DEFAULT 'n',
  `vrpay_url` varchar(64) NOT NULL DEFAULT '',
  `vrpay_number` varchar(64) NOT NULL DEFAULT '',
  `vrpay_pass` varchar(32) NOT NULL DEFAULT '',
  `vrpay_preis` decimal(16,9) NOT NULL DEFAULT '0.000000000',
  `kklastschrift_check` ENUM('n','y') NOT NULL DEFAULT 'n',
  `kklastschrift_preis` DECIMAL(8,2) NOT NULL DEFAULT '0.0',
  `mollie_check` ENUM('n','y') NOT NULL DEFAULT 'n' ,
  `mollie_preis` DECIMAL(8,2) NOT NULL DEFAULT '0' ,
  `paypalv2_check` ENUM('n','y') NOT NULL DEFAULT 'n' ,
  `paypalv2_preis` DECIMAL(8,2) NOT NULL DEFAULT '0' ,
  `paypalplus_check` ENUM('n','y') NOT NULL DEFAULT 'n' ,
  `paypalplus_preis` DECIMAL(8,2) NOT NULL DEFAULT '0' ,
  `amazon_check` ENUM('n','y') NOT NULL DEFAULT 'n',
  `amazon_login_check` ENUM('n','y') NOT NULL DEFAULT 'n',
  `amazon_preis` DECIMAL(8,2) NOT NULL DEFAULT '0' ,
  `amazon_client` varchar(64) NOT NULL DEFAULT '' ,
  `amazon_secret` varchar(64) NOT NULL DEFAULT '' ,
  `amazon_seller` VARCHAR(32) NOT NULL DEFAULT '' ,
  `amazon_access` VARCHAR(32) NOT NULL DEFAULT '' ,
  `twint_check` ENUM('n','y') NOT NULL DEFAULT 'n',
  `twint_preis` DECIMAL(8,2) NOT NULL DEFAULT '0',
  `wir_check` ENUM('n','y') NOT NULL DEFAULT 'n',
  `easycredit_check` ENUM('n','y') NOT NULL DEFAULT 'n',
  `easycredit_preis` DECIMAL(8,2) NOT NULL DEFAULT '0',
  `easycredit_api_id` VARCHAR(16) NOT NULL DEFAULT '',
  `easycredit_token` VARCHAR(32) NOT NULL DEFAULT '',
  `klarna_check` ENUM('n','y') NOT NULL DEFAULT 'n',
  `klarna_preis` DECIMAL(8,2) NOT NULL DEFAULT '0',
  `klarna_user` VARCHAR(64) NOT NULL DEFAULT '',
  `klarna_pass` VARCHAR(64) NOT NULL DEFAULT '',
  `paydirekt_check` ENUM('n','y') NOT NULL DEFAULT 'n',
  `paydirekt_preis` DECIMAL(8,2) NOT NULL DEFAULT '0',
  `paydirekt_key` VARCHAR(64) NOT NULL DEFAULT '',
  `paydirekt_secret` VARCHAR(64) NOT NULL DEFAULT '',
  `lastschrift_pdf_check` enum('n','y') NOT NULL DEFAULT 'n',
  `staffelpreise` enum('n','y') NOT NULL DEFAULT 'y',
  `grundeinheit` enum('n','y') NOT NULL DEFAULT 'y',
  `downloads` enum('n','y') NOT NULL DEFAULT 'n',
  `ean_check` ENUM('n','y') NOT NULL DEFAULT 'n',
  `gast_aktiv` enum('n','y') NOT NULL DEFAULT 'y',
  `schnittstellen` enum('n','y') NOT NULL DEFAULT 'n',
  `ebay_api` ENUM( 'n', 'y' ) NOT NULL DEFAULT 'n' ,
  `ebay_token` VARCHAR( 1024 ) CHARACTER SET ascii COLLATE ascii_bin NOT NULL DEFAULT '' ,
  `ebay_import` ENUM('n','y') NOT NULL DEFAULT 'n',
  `gutschein_aktiv` enum('n','y') NOT NULL DEFAULT 'y',
  `activate_voucher` enum('n','y') NOT NULL DEFAULT 'y',
  `show_breadcrumbs` enum('n','y') NOT NULL DEFAULT 'n',  
  `bonusprogramm_aktiv` enum('n','y') NOT NULL DEFAULT 'n',  
  `bonusprogramm_prozent` DECIMAL(8,2) NOT NULL DEFAULT '0',
  `newsletter_footer` enum('n','y') NOT NULL DEFAULT 'n',
  `show_coupon` enum('n','y') NOT NULL DEFAULT 'n',
  `social_status` varchar(10) NOT NULL DEFAULT 'unten',
  `statistik` enum('n','y') NOT NULL DEFAULT 'n',
  `re_automatik` ENUM('n','y') NOT NULL DEFAULT 'y',
  `re_autowert` SMALLINT UNSIGNED NOT NULL DEFAULT '50',
  `hide_wk` enum('n','y') NOT NULL DEFAULT 'n',
  `hide_anm` enum('n','y') NOT NULL DEFAULT 'n',
  `frage_check` ENUM('n','y') NOT NULL DEFAULT 'n',
  `shop_on_check` ENUM('n','y') NOT NULL DEFAULT 'y',
  `fsk` TINYINT UNSIGNED NOT NULL DEFAULT '18',
  `fsk_show` ENUM('n','y') NOT NULL DEFAULT 'n',
  `zeichen_main` TINYINT UNSIGNED NOT NULL DEFAULT '22',
  `zeichen_sub` TINYINT UNSIGNED NOT NULL DEFAULT '20',
  `cookie_check` ENUM('n','y','p') NOT NULL DEFAULT 'n',
  `b2b_check` ENUM('n','y') NOT NULL DEFAULT 'n',
  `b2b_widerruf` ENUM('n','y') NOT NULL DEFAULT 'y',
  `letzte` ENUM('n','y') NOT NULL DEFAULT 'n',
  `trustedshop` VARCHAR(40) NOT NULL DEFAULT '',
  `popup_check` ENUM('n','y') NOT NULL DEFAULT 'n',
  `telefon_aktiv` ENUM('n','y') NOT NULL DEFAULT 'n',
  `admin_size` TINYINT UNSIGNED NOT NULL DEFAULT '1',
  `umlaut_links'` ENUM('n','y') NOT NULL DEFAULT 'n',
  `version` tinyint(6) NOT NULL DEFAULT '14',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `#__firma2` ;
CREATE TABLE `#__firma2` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `homebutton_check` enum('n','y') NOT NULL DEFAULT 'y',
  `impressum_check` enum('n','y') NOT NULL DEFAULT 'y',
  `impressum_inhaber` enum('n','y') NOT NULL DEFAULT 'y',
  `datenschutz_check` enum('n','y') NOT NULL DEFAULT 'y',
  `kontakt_check` enum('n','y') NOT NULL DEFAULT 'y',
  `kontakt2_check` enum('n','y') NOT NULL DEFAULT 'y',
  `kontakt_inhaber` enum('n','y') NOT NULL DEFAULT 'y',
  `anmelden_check` enum('n','y') NOT NULL DEFAULT 'y',
  `versand_check` enum('n','y') NOT NULL DEFAULT 'y',
  `agb_check` enum('n','y') NOT NULL DEFAULT 'y',
  `newsletter_check` enum('n','y') NOT NULL DEFAULT 'n',
  `ueberuns1_check` enum('n','y') NOT NULL DEFAULT 'y',
  `ueberuns2_check` enum('n','y') NOT NULL DEFAULT 'n',
  `ueberuns3_check` enum('n','y') NOT NULL DEFAULT 'n',
  `ueberuns4_check` enum('n','y') NOT NULL DEFAULT 'n',
  `ueberuns5_check` enum('n','y') NOT NULL DEFAULT 'n',
  `widerruf1_check` enum('n','y') NOT NULL DEFAULT 'y',
  `widerruf1_form` enum('n','y') NOT NULL DEFAULT 'y',
  `widerruf2_check` enum('n','y') NOT NULL DEFAULT 'n',
  `widerruf2_form` enum('n','y') NOT NULL DEFAULT 'y',
  `widerruf3_check` enum('n','y') NOT NULL DEFAULT 'n',
  `widerruf3_form` enum('n','y') NOT NULL DEFAULT 'y',
  `widerruf4_check` enum('n','y') NOT NULL DEFAULT 'n',
  `widerruf4_form` enum('n','y') NOT NULL DEFAULT 'y',
  `widerruf5_check` enum('n','y') NOT NULL DEFAULT 'n',
  `widerruf5_form` enum('n','y') NOT NULL DEFAULT 'y',
  `kundeninfo_check` enum('n','y') NOT NULL DEFAULT 'n',
  `schlichtung_check` enum('n','y') NOT NULL DEFAULT 'y',
  `warenkorb_check` enum('n','y') NOT NULL DEFAULT 'y',
  `merkliste_check` enum('n','y') NOT NULL DEFAULT 'y',
  `sitemap_check` ENUM('n','y') NOT NULL DEFAULT 'y',
  `sitemap_menu` ENUM('n','y') NOT NULL DEFAULT 'y',
  `sitemap_agb` ENUM('n','y') NOT NULL DEFAULT 'y',
  `sitemap_cat` ENUM('n','y') NOT NULL DEFAULT 'y',
  `sitemap_cat_lev1` ENUM('n','y') NOT NULL DEFAULT 'y',
  `sitemap_cat_lev2` ENUM('n','y') NOT NULL DEFAULT 'y',
  `sitemap_articles` ENUM('n','y') NOT NULL DEFAULT 'y',
  `sitemap_xml` ENUM('n','y') NOT NULL DEFAULT 'y',
  `sitemap_title` ENUM('n','y') NOT NULL DEFAULT 'y',
  `cookie_wesentlich` ENUM('n','y') NOT NULL DEFAULT 'y',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

# 71
DROP TABLE IF EXISTS `#__foto_data` ;
CREATE TABLE IF NOT EXISTS `#__foto_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `foto_set` int(10) unsigned NOT NULL,
  `sort` tinyint(3) unsigned NOT NULL,
  `name` varchar(32) NOT NULL,
  `size` int(11) NOT NULL,
  `price` decimal(15,9) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `#__google_cats`;
CREATE TABLE IF NOT EXISTS `#__google_cats` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `level` tinyint(3) unsigned NOT NULL,
  `parent` int(10) unsigned NOT NULL,
  `google_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `#__gutscheine`;
CREATE TABLE IF NOT EXISTS `#__gutscheine` (
  `gutschein_id` int(10) unsigned NOT NULL,
  `code` varchar(32) NOT NULL DEFAULT '',
  `wert` decimal(6,2) NOT NULL DEFAULT '0.00',
  `mode` enum('1','2') NOT NULL DEFAULT '1',
  `datum` date NOT NULL DEFAULT '0000-00-00',
  `min` DECIMAL(8,2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`gutschein_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `#__gutscheine_kunden`;
CREATE TABLE IF NOT EXISTS `#__gutscheine_kunden` (
  `user_id` int(10) unsigned NOT NULL,
  `email` varchar(200) NOT NULL,
  `code` varchar(32) NOT NULL,
  `mode` tinyint(3) unsigned NOT NULL,
  `wert` decimal(6,2) NOT NULL,
  `datum` date NOT NULL,
  `eingeloest` enum('n','y') NOT NULL DEFAULT 'n',
  UNIQUE KEY `unique` (`email`,`code`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `#__gutscheine_print`;
CREATE TABLE IF NOT EXISTS `#__gutscheine_print` (
  `gutschein_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(32) NOT NULL DEFAULT '',
  `wert` decimal(6,2) NOT NULL DEFAULT '0.00',
  `mode` enum('1','2') NOT NULL DEFAULT '1',
  `datum` date NOT NULL DEFAULT '0000-00-00',
  `min` DECIMAL(8,2) NOT NULL DEFAULT '0',
  `deleted` ENUM('n','y') NOT NULL DEFAULT 'n',
  `outdated` ENUM('n','y') NOT NULL DEFAULT 'n',
  PRIMARY KEY (`gutschein_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci  AUTO_INCREMENT=1;

# 81
DROP TABLE IF EXISTS `#__keywords`;
CREATE TABLE IF NOT EXISTS `#__keywords` (
  `lang` varchar(3) NOT NULL,
  `seite` VARCHAR(32) NOT NULL DEFAULT 'starthtml',
  `titeltag` varchar(4096) NOT NULL,
  `keywords` varchar(4096) NOT NULL,
  `description` varchar(4096) NOT NULL,
  PRIMARY KEY (`lang`, `seite`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `#__klarna`;
CREATE TABLE `#__klarna` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `klarna_order_id` varchar(255) NOT NULL,
  `preis` DECIMAL(10,2) NOT NULL DEFAULT '0',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` VARCHAR(16) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_klarna_order_id` (`klarna_order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

# 91
DROP TABLE IF EXISTS `#__laender`;
CREATE TABLE IF NOT EXISTS `#__laender` (
  `id` int(10) unsigned NOT NULL,
  `domain` varchar(10) NOT NULL,
  `iso_lang` varchar(5) NOT NULL,
  `kurz` varchar(5) NOT NULL,
  `region` varchar(3) NOT NULL,
  `name` varchar(32) NOT NULL,
  `name_shop` varchar(32) NOT NULL,
  `sort` smallint(3) unsigned NOT NULL DEFAULT '0',
  `versand` decimal(6,2) NOT NULL DEFAULT '0.00',
  `locale` varchar(16) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `#__links`;
CREATE TABLE IF NOT EXISTS `#__links` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `template` varchar(20) NOT NULL,
  `lang` char(3) NOT NULL,
  `link1` varchar(512) NOT NULL DEFAULT '',
  `link2` varchar(512) NOT NULL DEFAULT '',
  `link3` varchar(512) NOT NULL DEFAULT '',
  `link4` varchar(512) NOT NULL DEFAULT '',
  `link5` varchar(512) NOT NULL DEFAULT '',
  `link6` varchar(512) NOT NULL DEFAULT '',
  `link7` varchar(512) NOT NULL DEFAULT '',
  `link8` varchar(512) NOT NULL DEFAULT '',
  `intern_check` ENUM('n','y') NOT NULL DEFAULT 'y',
  `link11` VARCHAR(512) NOT NULL DEFAULT '',
  `link12` VARCHAR(512) NOT NULL DEFAULT '',
  `link13` VARCHAR(512) NOT NULL DEFAULT '',
  `link14` VARCHAR(512) NOT NULL DEFAULT '',
  `link15` VARCHAR(512) NOT NULL DEFAULT '',
  `link16` VARCHAR(512) NOT NULL DEFAULT '',
  `link17` VARCHAR(512) NOT NULL DEFAULT '',
  `link18` VARCHAR(512) NOT NULL DEFAULT '',
  `link19` VARCHAR(512) NOT NULL DEFAULT '',
  `link20` VARCHAR(512) NOT NULL DEFAULT '',
  `intern_sl1_check` ENUM('n','y') NOT NULL DEFAULT 'y',
  `intern_sl2_check` ENUM('n','y') NOT NULL DEFAULT 'y',
  `logo` VARCHAR(512) NOT NULL DEFAULT '',
  `logomenu` VARCHAR(512) NOT NULL,
  `banner1` VARCHAR(512) NOT NULL DEFAULT '',
  `banner2` VARCHAR(512) NOT NULL DEFAULT '',
  `danke1` VARCHAR(512) NOT NULL DEFAULT '',
  `danke2` VARCHAR(512) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `#__matrix`;
CREATE TABLE `#__matrix` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `art_id` int(10) UNSIGNED NOT NULL,
  `typ` varchar(8) NOT NULL,
  `pos_x` int(10) UNSIGNED NOT NULL,
  `pos_y` int(10) UNSIGNED NOT NULL,
  `breite` decimal(10,2) UNSIGNED NOT NULL,
  `hoehe` decimal(10,2) UNSIGNED NOT NULL,
  `preis` decimal(16,9) UNSIGNED NOT NULL,
  `text` varchar(4096) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_art_id` (`art_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__merkliste`;
CREATE TABLE IF NOT EXISTS `#__merkliste` (
 `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `merkliste_id` varchar(32) DEFAULT '',
  `art_id` int(10) unsigned NOT NULL,
  `cat_id` INT UNSIGNED NOT NULL DEFAULT '0',
  `art_menge` decimal(12,5) unsigned NOT NULL,
  `foto_set` int(10) unsigned NOT NULL DEFAULT '0',
  `foto_sort` int(10) unsigned NOT NULL DEFAULT '0',
  `motiv_uploadp_check` ENUM('n','y') NOT NULL DEFAULT 'n',
  `motiv_uploadt_check` ENUM('n','y') NOT NULL DEFAULT 'n',
  `motiv_upload_name` VARCHAR(128) NOT NULL DEFAULT '',
  `motiv_upload_user` VARCHAR(128) NOT NULL DEFAULT '',
  `motiv_upload_text` VARCHAR(4096) NOT NULL DEFAULT '',
  `art_inserted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `haendler_id` INT UNSIGNED NOT NULL DEFAULT '0',
  `configurator` TEXT NULL,
  `rechner_check` enum('n','y') NOT NULL DEFAULT 'n',
  `rechner_breite` decimal(10,5) NOT NULL DEFAULT '0.00000',
  `rechner_hoehe` decimal(10,5) NOT NULL DEFAULT '0.00000',
  `rechner_tiefe` DECIMAL(8,3) NOT NULL DEFAULT '1',
  `rechner_mode` TINYINT UNSIGNED NOT NULL DEFAULT '2',
  `rechner_einheit` varchar(16) NOT NULL DEFAULT '',
  `preismatrix` varchar(4096) NOT NULL DEFAULT '',
  `mixer` VARCHAR(4096) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `#__merkmale`;
CREATE TABLE IF NOT EXISTS `#__merkmale` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `merkmal_deu` varchar(32) NOT NULL DEFAULT '',
  `merkmal_eng` varchar(32) NOT NULL DEFAULT '',
  `merkmal_spa` varchar(32) NOT NULL DEFAULT '',
  `merkmal_dan` varchar(32) NOT NULL DEFAULT '',
  `merkmal_fin` varchar(32) NOT NULL DEFAULT '',
  `merkmal_fra` varchar(32) NOT NULL DEFAULT '',
  `merkmal_ita` varchar(32) NOT NULL DEFAULT '',
  `merkmal_nld` varchar(32) NOT NULL DEFAULT '',
  `merkmal_nor` varchar(32) NOT NULL DEFAULT '',
  `merkmal_por` varchar(32) NOT NULL DEFAULT '',
  `merkmal_swe` varchar(32) NOT NULL DEFAULT '',
  `merkmal_tue` varchar(32) NOT NULL DEFAULT '',
  `merkmal_rus` varchar(32) NOT NULL DEFAULT '',
  `merkmal_gri` varchar(32) NOT NULL DEFAULT '',
  `merkmal_ara` varchar(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

# 101
DROP TABLE IF EXISTS `#__module`;
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


DROP TABLE IF EXISTS `#__musikplayer`;
CREATE TABLE `#__musikplayer` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `position` enum('left','right') NOT NULL DEFAULT 'left',
  `sort` TINYINT UNSIGNED NOT NULL DEFAULT '255',
  `type` enum('title','file') NOT NULL DEFAULT 'title',
  `filename` varchar(256) NOT NULL DEFAULT '',
  `text` varchar(1024) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `idx_parent_id` (`parent_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
# 90
DROP TABLE IF EXISTS `#__net_categories`;
CREATE TABLE IF NOT EXISTS `#__net_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned NOT NULL,
  `network_id` int(10) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(4) NOT NULL,
  `ordered` tinyint(4) NOT NULL DEFAULT '0',
  `childs` tinyint(4) NOT NULL DEFAULT '0',
  `name_deu` varchar(50) NOT NULL DEFAULT '',
  `desc_deu` varchar(1024) NOT NULL DEFAULT '',
  `name_eng` varchar(50) NOT NULL DEFAULT '',
  `desc_eng` varchar(1024) NOT NULL DEFAULT '',
  `name_spa` varchar(50) NOT NULL DEFAULT '',
  `desc_spa` varchar(1024) NOT NULL DEFAULT '',
  `name_dan` varchar(50) NOT NULL DEFAULT '',
  `desc_dan` varchar(1024) NOT NULL DEFAULT '',
  `name_fin` varchar(50) NOT NULL DEFAULT '',
  `desc_fin` varchar(1024) NOT NULL DEFAULT '',
  `name_fra` varchar(50) NOT NULL DEFAULT '',
  `desc_fra` varchar(1024) NOT NULL DEFAULT '',
  `name_ita` varchar(50) NOT NULL DEFAULT '',
  `desc_ita` varchar(1024) NOT NULL DEFAULT '',
  `name_nld` varchar(50) NOT NULL DEFAULT '',
  `desc_nld` varchar(1024) NOT NULL DEFAULT '',
  `name_nor` varchar(50) NOT NULL DEFAULT '',
  `desc_nor` varchar(1024) NOT NULL DEFAULT '',
  `name_por` varchar(50) NOT NULL DEFAULT '',
  `desc_por` varchar(1024) NOT NULL DEFAULT '',
  `name_swe` varchar(50) NOT NULL DEFAULT '',
  `desc_swe` varchar(1024) NOT NULL DEFAULT '',
  `name_tue` varchar(50) NOT NULL DEFAULT '',
  `desc_tue` varchar(1024) NOT NULL DEFAULT '',
  `name_rus` varchar(50) NOT NULL DEFAULT '',
  `desc_rus` varchar(1024) NOT NULL DEFAULT '',
  `name_gri` varchar(50) NOT NULL DEFAULT '',
  `desc_gri` varchar(1024) NOT NULL DEFAULT '',
  `name_ara` varchar(50) NOT NULL DEFAULT '',
  `desc_ara` varchar(1024) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `#__nummern`;
CREATE TABLE IF NOT EXISTS `#__nummern` (
  `id` int(10) unsigned NOT NULL,
  `bestellung` int(10) unsigned NOT NULL,
  `rechnung` int(10) unsigned NOT NULL,
  `collector` INT UNSIGNED NOT NULL DEFAULT '10000'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `#__pro`;
CREATE TABLE IF NOT EXISTS `#__pro` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `typ` varchar(16) NOT NULL,
  `sort` tinyint(4) NOT NULL DEFAULT '0',
  `lang` varchar(3) NOT NULL,
  `data` varchar(10000) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

# 111
DROP TABLE IF EXISTS `#__rabatte`;
CREATE TABLE IF NOT EXISTS `#__rabatte` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `haendler_id` int(10) unsigned NOT NULL,
  `kundengruppe` int(10) unsigned NOT NULL,
  `artikelgruppe` int(10) unsigned NOT NULL,
  `rabatt` decimal(8,2) NOT NULL,
  `sonderpreis_check` enum('n','y') NOT NULL DEFAULT 'n',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

# 101
DROP TABLE IF EXISTS `#__rechnung`;
CREATE TABLE IF NOT EXISTS `#__rechnung` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `haendler_id` int(10) unsigned NOT NULL default '0',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  `deleted` enum('y','n') NOT NULL DEFAULT 'n',
  `user_id` int(10) unsigned NOT NULL,
  `bestellnummer` varchar(50) NOT NULL,
  `gewerbe` tinyint(3) unsigned NOT NULL,
  `gewerbeinfo` VARCHAR(16) NOT NULL DEFAULT '',
  `netto` DECIMAL(24,9) NOT NULL DEFAULT 0.00,
  `versand` decimal(12,2) DEFAULT NULL,
  `versand_ust` decimal(12,2) NOT NULL DEFAULT '0.00',
  `zahlart_add` decimal(12,2) NOT NULL DEFAULT '0.00',
  `zahlart_ust` decimal(12,2) NOT NULL DEFAULT '0.00',
  `user_rabatt` DECIMAL(6,4) NOT NULL DEFAULT '0.00',
  `rabatt` DECIMAL(16,9) NULL DEFAULT '0.00',
  `gutschrift` decimal(6,2) DEFAULT NULL,
  `gutschein_code` varchar(128) NOT NULL DEFAULT '',
  `gutschein_brutto` decimal(8,2) NOT NULL DEFAULT '0.00',
  `gutschein_steuer` decimal(8,2) NOT NULL DEFAULT '0.00',
  `steuer1` DECIMAL(24,9) NOT NULL DEFAULT 0.00,
  `steuer2` DECIMAL(24,9) NOT NULL DEFAULT 0.00,
  `steuer3` DECIMAL(24,9) NOT NULL DEFAULT 0.00,
  `waehrung_id` TINYINT UNSIGNED NOT NULL DEFAULT '1' ,
  `w_faktor` DECIMAL(16,9) NOT NULL DEFAULT '1' ,
  `steuersatz1` float DEFAULT NULL,
  `steuersatz2` float DEFAULT NULL,
  `steuersatz3` float DEFAULT NULL,
  `lang_kunde` varchar(20) NOT NULL,
  `anrede` varchar(16) NOT NULL DEFAULT '',
  `vorname` varchar(30) NOT NULL,
  `nachname` varchar(30) NOT NULL,
  `firma` varchar(50) NOT NULL,
  `ustid` varchar(30) DEFAULT NULL,
  `adresse` varchar(50) NOT NULL,
  `hausnr` VARCHAR(16) NOT NULL DEFAULT '' ,
  `plz` varchar(10) NOT NULL,
  `ort` varchar(50) NOT NULL,
  `buland` VARCHAR(32) NOT NULL DEFAULT '' ,
  `staat` varchar(32) NOT NULL,
  `staat2` varchar(32) NOT NULL DEFAULT '',
  `email` varchar(200) DEFAULT NULL,
  `telefon` varchar(50) DEFAULT NULL,
  `lieferadresse` enum('y','n') NOT NULL DEFAULT 'n',
  `lf_anrede` varchar(16) NOT NULL DEFAULT '',
  `lf_vorname` varchar(30) DEFAULT NULL,
  `lf_nachname` varchar(30) DEFAULT NULL,
  `lf_firma` varchar(50) DEFAULT NULL,
  `lf_adresse` varchar(50) DEFAULT NULL,
  `lf_hausnr` VARCHAR(16) NOT NULL DEFAULT '' ,
  `lf_postnr` VARCHAR(32) NOT NULL DEFAULT '' ,
  `lf_plz` varchar(10) DEFAULT NULL,
  `lf_ort` varchar(50) DEFAULT NULL,
  `lf_buland` VARCHAR(32) NOT NULL DEFAULT '' ,
  `lf_staat` varchar(50) DEFAULT NULL,
  `lf_staat2` VARCHAR(32) NOT NULL DEFAULT '',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `liefernummer` int(10) unsigned DEFAULT NULL,
  `lieferdatum` timestamp NOT NULL DEFAULT '1970-01-02 00:00:00',
  `rechnungsnummer` varchar(24) DEFAULT '',
  `rechnungsdatum` timestamp NOT NULL DEFAULT '1970-01-02 00:00:00',
  `zahlung` float DEFAULT NULL,
  `zahlungdatum` timestamp NOT NULL DEFAULT '1970-01-02 00:00:00',
  `gemahnt` enum('y','n') NOT NULL DEFAULT 'n',
  `msg_kunde` text NULL,
  `msg_admin` text NULL,
  `pdf` varchar(3) DEFAULT NULL,
  `zahlungsart` tinyint(4) NOT NULL DEFAULT '99',
  `zahlungsinfo1` varchar(100) NOT NULL DEFAULT '',
  `zahlungsinfo2` varchar(100) NOT NULL DEFAULT '',
  `bank_name` varchar(50) DEFAULT '',
  `bank_inhaber` varchar(50) DEFAULT '',
  `bank_iban` varchar(50) DEFAULT '',
  `bank_bic` varchar(50) DEFAULT '',
  `wk` varchar(4096) DEFAULT NULL,
  `abgerechnet` ENUM('n','y','d') NOT NULL DEFAULT 'n',
  `provision` decimal(5,2) NULL DEFAULT '0.00',
  `prov_re_nr` VARCHAR(24) NULL DEFAULT NULL,
  `prov_re_datum` DATETIME NULL DEFAULT NULL,
  `widerruf` TINYINT UNSIGNED NOT NULL DEFAULT ' 0' ,
  `dhl_send_check` ENUM('n','y') NOT NULL DEFAULT 'n' ,
  `ppp_get_url` VARCHAR(128) NOT NULL  DEFAULT '',
  `ppp_post_url` VARCHAR(128) NOT NULL DEFAULT '' ,
  `ppp_status` TINYINT UNSIGNED NOT NULL DEFAULT '99' ,
  `ebay_order` VARCHAR(32) NOT NULL DEFAULT '',
  `collected` ENUM('n','y') NOT NULL DEFAULT 'n' ,
  `collector` ENUM('n','y') NOT NULL DEFAULT 'n' ,
  `dhl_intraship` VARCHAR(64) NOT NULL DEFAULT '',
  `ds_gvo_check` ENUM('n','y') NOT NULL DEFAULT 'n',  
  `abholung_checkbox` ENUM('n','y') NOT NULL DEFAULT 'n',
  `gebdatum` DATE NULL DEFAULT NULL,
  `last_change` TIMESTAMP NOT NULL DEFAULT '1970-01-02 00:00:00',
  PRIMARY KEY (`id`),
  KEY `idx_rechnungsdatum` (`rechnungsdatum`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
# 100

DROP TABLE IF EXISTS `#__rechnung_artikel`;
CREATE TABLE IF NOT EXISTS `#__rechnung_artikel` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rechnung_id` int(10) unsigned NOT NULL,
  `artikel_id` int(10) unsigned NOT NULL,
  `cat_id` INT UNSIGNED NOT NULL DEFAULT '0',
  `artikel_nummer` varchar(50) NOT NULL,
  `menge` DECIMAL(12,5) NOT NULL,
  `masse_check` ENUM('n','y') NOT NULL DEFAULT 'n' ,
  `masse_komma` SMALLINT UNSIGNED NOT NULL DEFAULT '0' ,
  `artikel_preis` decimal(16,9) NOT NULL,
  `preis_wk` DECIMAL(16,9) NOT NULL DEFAULT '0',
  `steuersatz` tinyint(3) unsigned NOT NULL,
  `name_shop` varchar(250) DEFAULT NULL,
  `desc_shop` text NULL,
  `name_kunde` varchar(250) DEFAULT NULL,
  `desc_kunde` text NULL,
  `merkmal1` smallint(5) unsigned NOT NULL,
  `wert1` smallint(5) unsigned NOT NULL,
  `merkmal2` smallint(5) unsigned NOT NULL,
  `wert2` smallint(5) unsigned NOT NULL,
  `grundeinheit` varchar(10) DEFAULT NULL,
  `ge_netto` decimal(8,2) DEFAULT '0.00',
  `ge_netto_aktiv` enum('n','y') DEFAULT 'n',
  `versand_preis` decimal(8,2) unsigned DEFAULT '0.00',
  `gewicht` decimal(10,5) DEFAULT '0.00',
  `gew_check` ENUM('n','y') NOT NULL DEFAULT 'n',
  `aktiv` enum('y','n') NOT NULL DEFAULT 'y',
  `staffelung` varchar(250) NOT NULL DEFAULT '',
  `filename` varchar(127) NOT NULL DEFAULT '',
  `filetyp` varchar(127) NOT NULL DEFAULT '',
  `foto_set` INT UNSIGNED NOT NULL DEFAULT '0',
  `foto_sort` INT UNSIGNED NOT NULL DEFAULT '0',
  `motiv_upload_name` VARCHAR(128) NOT NULL DEFAULT '',
  `motiv_upload_text` VARCHAR(4096) NOT NULL DEFAULT '',
  `configurator` TEXT NULL,
  `configurator_kunde` TEXT NULL,
  `configurator_wk` TEXT NULL,
  `rechner_check` enum('n','y') NOT NULL DEFAULT 'n',
  `rechner_breite` decimal(10,5) NOT NULL DEFAULT '0.00000',
  `rechner_hoehe` decimal(10,5) NOT NULL DEFAULT '0.00000',
  `rechner_tiefe` DECIMAL(8,3) NOT NULL DEFAULT '1',
  `rechner_mode` TINYINT UNSIGNED NOT NULL DEFAULT '2',
  `rechner_einheit` varchar(16) NOT NULL DEFAULT '',
  `grundeinheit_rechner` VARCHAR(16) NOT NULL DEFAULT '',
  `art_version` TINYINT UNSIGNED NOT NULL DEFAULT '77',
  `ebay_id` VARCHAR(64) NOT NULL DEFAULT '',
  `lager_zeit` TINYINT UNSIGNED NOT NULL DEFAULT '0',
  `preismatrix` varchar(4096) NOT NULL DEFAULT '',
  `mixer` VARCHAR(4096) NOT NULL DEFAULT '',
  `naehrwerte` VARCHAR(512) NOT NULL DEFAULT '',
  `zutaten` VARCHAR(4096) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `#__rechnung_collector`;
CREATE TABLE `#__rechnung_collector` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `collector_id` int(10) UNSIGNED NOT NULL,
  `rechnung_id` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_collector` (`collector_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;

DROP TABLE IF EXISTS `#__sessions`;
CREATE TABLE IF NOT EXISTS `#__sessions` (
  `session_id` varchar(64) NOT NULL,
  `session_data` text NULL,
  `session_time` int(11) NOT NULL,
  `session_control` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

#121
DROP TABLE IF EXISTS `#__social`;
CREATE TABLE IF NOT EXISTS `#__social` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(40) NOT NULL DEFAULT '',
  `image` varchar(32) NOT NULL DEFAULT '',
  `footer` enum('n','y') NOT NULL DEFAULT 'n',
  `footer_link` varchar(64) NOT NULL DEFAULT '',
  `profillink` ENUM('n','y') NOT NULL DEFAULT 'n',
  `detail1` ENUM('n','y','d') NOT NULL DEFAULT 'd',
  `detail2` ENUM('n','y','d') NOT NULL DEFAULT 'd',
  `script_check` ENUM('n','y') NOT NULL DEFAULT 'n',
  `detail_script` VARCHAR(4048) NOT NULL DEFAULT '',
  `detail_link_check` ENUM('n','y') NOT NULL DEFAULT 'n',
  `detail_link` VARCHAR(256) NOT NULL DEFAULT '',
  `displayorder` INT NOT NULL DEFAULT '100',
  `aicon_on_top` ENUM('y','n') NOT NULL DEFAULT 'y',
  `customtext_admin_top` VARCHAR(512) NOT NULL DEFAULT '',
  `customtext_admin_footer` VARCHAR(512) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



# 111
DROP TABLE IF EXISTS `#__statistik`;
CREATE TABLE `#__statistik` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `typ` varchar(32) NOT NULL,
  `typ_id` int(10) UNSIGNED NOT NULL,
  `session_id` varchar(64) DEFAULT NULL,
  `haendler_id` int(10) UNSIGNED NOT NULL,
  `anzahl` int(10) unsigned NOT NULL,
  `robot` ENUM('n','y') NOT NULL DEFAULT 'n',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `#__seiten`;
CREATE TABLE IF NOT EXISTS `#__seiten` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `art` varchar(25) NOT NULL,
  `lang` varchar(25) NOT NULL,
  `text` MEDIUMTEXT NULL,
  `name` varchar(32) DEFAULT NULL,
  `check` ENUM('n','y') NOT NULL DEFAULT 'y',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_art_lang` (`art`,`lang`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
# 110

DROP TABLE IF EXISTS `#__users`;
CREATE TABLE IF NOT EXISTS `#__users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `anrede` varchar(16) NOT NULL DEFAULT '',
  `name` varchar(50) NOT NULL DEFAULT '',
  `email` varchar(200) NOT NULL,
  `password` varchar(50) NULL DEFAULT NULL,
  `role` int DEFAULT NULL,
  `vorname` varchar(30) NOT NULL DEFAULT '',
  `nachname` varchar(30) NOT NULL DEFAULT '',
  `firma` varchar(50) NOT NULL DEFAULT '',
  `adresse` varchar(50) NOT NULL DEFAULT '',
  `plz` varchar(10) NOT NULL DEFAULT '',
  `ort` varchar(50) NOT NULL DEFAULT '',
  `hausnr` VARCHAR(16) NOT NULL DEFAULT '' ,
  `buland` VARCHAR(32) NOT NULL DEFAULT '' ,
  `staat` int(10) unsigned NOT NULL DEFAULT '160',
  `staat2` VARCHAR(32) NOT NULL DEFAULT '' ,
  `gebdatum` date NULL DEFAULT NULL,
  `end_datum` DATE NULL DEFAULT NULL,
  `alter_check` VARCHAR(16) NOT NULL DEFAULT '',
  `ustid` varchar(20) NOT NULL DEFAULT '',
  `telefon` varchar(50) NOT NULL DEFAULT '',
  `newsletter` enum('y','n') NOT NULL DEFAULT 'n',
  `newsletter_check` varchar(32) NOT NULL DEFAULT '',
  `daten` enum('y','n') NOT NULL DEFAULT 'n',
  `agb` enum('y','n') NOT NULL DEFAULT 'n',
  `info` text NULL,
  `lieferadresse` enum('y','n') NOT NULL DEFAULT 'n',
  `lf_anrede` varchar(16) NOT NULL DEFAULT '',
  `lf_vorname` varchar(30) DEFAULT NULL DEFAULT '',
  `lf_nachname` varchar(30) DEFAULT NULL DEFAULT '',
  `lf_firma` varchar(50) DEFAULT NULL DEFAULT '',
  `lf_adresse` varchar(50) DEFAULT NULL DEFAULT '',
  `lf_hausnr` VARCHAR(16) NOT NULL DEFAULT '' ,
  `lf_postnr` VARCHAR(32) NOT NULL DEFAULT '' ,
  `lf_plz` varchar(10) DEFAULT NULL,
  `lf_ort` varchar(50) DEFAULT NULL,
  `lf_buland` VARCHAR(32) NOT NULL DEFAULT '' ,
  `lf_staat` int(10) unsigned DEFAULT NULL,
  `lf_staat2` VARCHAR(32) NOT NULL DEFAULT '' ,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` datetime NULL,
  `last_login` datetime NULL ,
  `forgotten` varchar(32) NOT NULL DEFAULT '',
  `gutschrift` decimal(15,9) NOT NULL DEFAULT '0.00',
  `rabatt` DECIMAL(6,4) NOT NULL DEFAULT '0.00',
  `gesperrt` enum('y','n') NOT NULL DEFAULT 'n',
  `lang` char(3) NOT NULL DEFAULT 'deu',
  `pp_mail` varchar(100) NOT NULL DEFAULT '',
  `pp_id` varchar(100) NOT NULL DEFAULT '',
  `bank_name` varchar(50) DEFAULT '',
  `bank_inhaber` varchar(50) DEFAULT '',
  `bank_iban` varchar(50) DEFAULT '',
  `bank_bic` varchar(50) DEFAULT '',
  `sofort_ident` VARCHAR(8) NOT NULL DEFAULT '',
  `rechnung_kunde` ENUM('n','y') NOT NULL DEFAULT 'n',
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_email` (`email`),
  KEY `newsletter_check` (`newsletter_check`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

DROP TABLE IF EXISTS `#__warenkorb`;
CREATE TABLE IF NOT EXISTS `#__warenkorb` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `art_id` int(10) unsigned NOT NULL,
  `cat_id` INT UNSIGNED NOT NULL DEFAULT '0',
  `art_menge` DECIMAL(12,5) unsigned NOT NULL,
  `foto_set` INT UNSIGNED NOT NULL DEFAULT '0' ,
  `foto_sort` INT UNSIGNED NOT NULL DEFAULT '0' ,
  `motiv_uploadp_check` ENUM('n','y') NOT NULL DEFAULT 'n',
  `motiv_uploadt_check` ENUM('n','y') NOT NULL DEFAULT 'n',
  `motiv_upload_name` VARCHAR(128) NOT NULL DEFAULT '',
  `motiv_upload_user` VARCHAR(128) NOT NULL DEFAULT '',
  `motiv_upload_text` VARCHAR(4096) NOT NULL DEFAULT '',
  `art_inserted` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `haendler_id` int(10) unsigned NOT NULL DEFAULT '0',
  `configurator` TEXT NULL,
  `rechner_check` enum('n','y') NOT NULL DEFAULT 'n',
  `rechner_tiefe` DECIMAL(8,3) NOT NULL DEFAULT '1',
  `rechner_mode` TINYINT UNSIGNED NOT NULL DEFAULT '2',
  `rechner_breite` decimal(10,5) NOT NULL DEFAULT '0.00000',
  `rechner_hoehe` decimal(10,5) NOT NULL DEFAULT '0.00000',
  `rechner_einheit` varchar(16) NOT NULL DEFAULT '',
  `preismatrix` varchar(4096) NOT NULL DEFAULT '',
  `mixer` VARCHAR(4096) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `user` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

#131
DROP TABLE IF EXISTS `#__werte`;
CREATE TABLE IF NOT EXISTS `#__werte` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `merkmal_id` int(11) NOT NULL,
  `wert_deu` varchar(32) NOT NULL DEFAULT '',
  `wert_eng` varchar(32) NOT NULL DEFAULT '',
  `wert_spa` varchar(32) NOT NULL DEFAULT '',
  `wert_dan` varchar(32) NOT NULL DEFAULT '',
  `wert_fin` varchar(32) NOT NULL DEFAULT '',
  `wert_fra` varchar(32) NOT NULL DEFAULT '',
  `wert_ita` varchar(32) NOT NULL DEFAULT '',
  `wert_nld` varchar(32) NOT NULL DEFAULT '',
  `wert_nor` varchar(32) NOT NULL DEFAULT '',
  `wert_por` varchar(32) NOT NULL DEFAULT '',
  `wert_swe` varchar(32) NOT NULL DEFAULT '',
  `wert_tue` varchar(32) NOT NULL DEFAULT '',
  `wert_rus` varchar(32) NOT NULL DEFAULT '',
  `wert_gri` varchar(32) NOT NULL DEFAULT '',
  `wert_ara` varchar(32) NOT NULL DEFAULT '',
  `wert_img` varchar(64) NOT NULL DEFAULT '',
  `wert_sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
# 121 gesamt

