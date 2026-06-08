ALTER TABLE `#__firma2`
   ADD `sitemap_check` ENUM('n','y') NOT NULL DEFAULT 'n' AFTER `merkliste_check`,
   ADD `sitemap_menu` ENUM('n','y') NOT NULL DEFAULT 'y' AFTER `sitemap_check`,
   ADD `sitemap_agb` ENUM('n','y') NOT NULL DEFAULT 'y' AFTER `sitemap_menu`,
   ADD `sitemap_cat` ENUM('n','y') NOT NULL DEFAULT 'y' AFTER `sitemap_agb`,
   ADD `sitemap_cat_lev1` ENUM('n','y') NOT NULL DEFAULT 'y' AFTER `sitemap_cat`,
   ADD `sitemap_cat_lev2` ENUM('n','y') NOT NULL DEFAULT 'y' AFTER `sitemap_cat_lev1`,
   ADD `sitemap_articles` ENUM('n','y') NOT NULL DEFAULT 'y' AFTER `sitemap_cat_lev2`,
   ADD `sitemap_xml` ENUM('n','y') NOT NULL DEFAULT 'y' AFTER `sitemap_articles`,
   ADD `sitemap_title` ENUM('n','y') NOT NULL DEFAULT 'y' AFTER `sitemap_xml`;
