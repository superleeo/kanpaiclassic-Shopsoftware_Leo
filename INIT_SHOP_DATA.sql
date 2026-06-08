-- ============================================================================
-- 妙味日本料理铁板烧 - 初始化数据脚本
-- Miaowei Teppanyaki - Initial Setup SQL
-- 曼海姆 Mannheim, Germany
-- ============================================================================
-- 注意: 该脚本应在系统基本安装完成后执行
-- 用于初始化店铺信息、商品分类和示例商品数据

-- ============================================================================
-- 1. 店铺信息配置 (需要替换 flow_shop 为你的实际表前缀)
-- ============================================================================

-- 清空旧数据 (谨慎操作!)
-- DELETE FROM flow_shop_data WHERE data_name LIKE 'firma_%';

-- 插入店铺基本信息
INSERT INTO `flow_shop_data` (`data_name`, `data_value`) VALUES
  ('firma_name', '妙味日本料理铁板烧'),
  ('firma_name_en', 'Miaowei Teppanyaki'),
  ('firma_strasse', 'Augustaanlage 15'),
  ('firma_plz', '68161'),
  ('firma_ort', 'Mannheim'),
  ('firma_land', 'Deutschland'),
  ('firma_telefon', '+49 621 XXXXXX'),
  ('firma_fax', ''),
  ('firma_email', 'info@miaowei-teppanyaki.de'),
  ('firma_url', 'https://www.miaowei-teppanyaki.de'),
  ('firma_steuernummer', 'DE123456789'),
  ('firma_umsatzsteuer_id', 'DE123456789'),
  ('firma_inhaber', '妙味日本料理创始人'),
  ('firma_inhaber_en', 'Founder of Miaowei Teppanyaki'),
  ('versandart_land', 'DE,EU'),
  ('waehrung1', 'EUR'),
  ('waehrung1_zeichen', '€'),
  ('kurs1', '1'),
  ('region', 'EU'),
  ('shop_on_check', '1'),
  ('impressum_text', '<p>妙味日本料理铁板烧<br>Mannheim, Deutschland</p>'),
  ('datenschutz_text', '<p>Datenschutzerklärung nach DSGVO</p>'),
  ('versand_info_text', '<p>Lieferinformation: Belieferung im Mannheimer Stadtgebiet</p>'),
  ('agb_text', '<p>Allgemeine Geschäftsbedingungen</p>');

-- 营业时间
INSERT INTO `flow_shop_data` (`data_name`, `data_value`) VALUES
  ('opening_time_mon', 'Geschlossen'),
  ('opening_time_tue', '11:00-22:00'),
  ('opening_time_wed', '11:00-22:00'),
  ('opening_time_thu', '11:00-22:00'),
  ('opening_time_fri', '11:00-23:00'),
  ('opening_time_sat', '11:00-23:00'),
  ('opening_time_sun', '11:00-22:00');

-- ============================================================================
-- 2. 创建商品分类
-- ============================================================================

-- 主分类: 套餐 (Menü Sets)
INSERT INTO `flow_shop_kategorien` 
  (`kat_name`, `kat_name_en`, `kat_beschreibung`, `parent_id`, `kat_bild`, `cat_position`, `sichtbar`) 
VALUES 
  ('Menü Sets', 'Menu Sets', 'Vorgefertigte Menüs und Sets für jeden Geschmack', 0, '', 1, 1);
-- ID: 1

-- 主分类: 铁板烧 (Teppanyaki)
INSERT INTO `flow_shop_kategorien` 
  (`kat_name`, `kat_name_en`, `kat_beschreibung`, `parent_id`, `kat_bild`, `cat_position`, `sichtbar`) 
VALUES 
  ('Teppanyaki', 'Teppanyaki', 'Klassische japanische Teppanyaki-Gerichte, frisch auf der Platte zubereitet', 0, '', 2, 1);
-- ID: 2

-- 主分类: 寿司 (Sushi)
INSERT INTO `flow_shop_kategorien` 
  (`kat_name`, `kat_name_en`, `kat_beschreibung`, `parent_id`, `kat_bild`, `cat_position`, `sichtbar`) 
VALUES 
  ('Sushi', 'Sushi', 'Frische Sushi in verschiedenen Variationen', 0, '', 3, 1);
-- ID: 3

-- 主分类: 配菜 (Beilagen)
INSERT INTO `flow_shop_kategorien` 
  (`kat_name`, `kat_name_en`, `kat_beschreibung`, `parent_id`, `kat_bild`, `cat_position`, `sichtbar`) 
VALUES 
  ('Beilagen', 'Sides', 'Japanische Beilagen und Vorspeisen', 0, '', 4, 1);
-- ID: 4

-- 主分类: 饮品 (Getränke)
INSERT INTO `flow_shop_kategorien` 
  (`kat_name`, `kat_name_en`, `kat_beschreibung`, `parent_id`, `kat_bild`, `cat_position`, `sichtbar`) 
VALUES 
  ('Getränke', 'Drinks', 'Verschiedene Getränke und japanische Spezialitäten', 0, '', 5, 1);
-- ID: 5

-- 主分类: 甜点 (Desserts)
INSERT INTO `flow_shop_kategorien` 
  (`kat_name`, `kat_name_en`, `kat_beschreibung`, `parent_id`, `kat_bild`, `cat_position`, `sichtbar`) 
VALUES 
  ('Desserts', 'Desserts', 'Süße Köstlichkeiten zum Abschluss', 0, '', 6, 1);
-- ID: 6

-- 子分类: 海鲜套餐 (unter Menü Sets)
INSERT INTO `flow_shop_kategorien` 
  (`kat_name`, `kat_name_en`, `kat_beschreibung`, `parent_id`, `kat_bild`, `cat_position`, `sichtbar`) 
VALUES 
  ('Meeresfrüchte Sets', 'Seafood Sets', 'Luxuriöse Meeresfrüchte Menüs', 1, '', 1, 1);
-- ID: 7

-- 子分类: 肉类套餐 (unter Menü Sets)
INSERT INTO `flow_shop_kategorien` 
  (`kat_name`, `kat_name_en`, `kat_beschreibung`, `parent_id`, `kat_bild`, `cat_position`, `sichtbar`) 
VALUES 
  ('Fleisch Sets', 'Meat Sets', 'Premium Fleischmenüs mit japanischen Spezialtäten', 1, '', 2, 1);
-- ID: 8

-- 子分类: 素食套餐 (unter Menü Sets)
INSERT INTO `flow_shop_kategorien` 
  (`kat_name`, `kat_name_en`, `kat_beschreibung`, `parent_id`, `kat_bild`, `cat_position`, `sichtbar`) 
VALUES 
  ('Vegetarische Sets', 'Vegetarian Sets', 'Köstliche vegetarische Gerichte', 1, '', 3, 1);
-- ID: 9

-- 子分类: 虾/贝类 (unter Teppanyaki)
INSERT INTO `flow_shop_kategorien` 
  (`kat_name`, `kat_name_en`, `kat_beschreibung`, `parent_id`, `kat_bild`, `cat_position`, `sichtbar`) 
VALUES 
  ('Krevetten & Meeresfrüchte', 'Shrimp & Seafood', 'Frische Krevetten und Meeresfrüchte auf der Teppanyaki', 2, '', 1, 1);
-- ID: 10

-- 子分类: 牛肉 (unter Teppanyaki)
INSERT INTO `flow_shop_kategorien` 
  (`kat_name`, `kat_name_en`, `kat_beschreibung`, `parent_id`, `kat_bild`, `cat_position`, `sichtbar`) 
VALUES 
  ('Rindfleisch', 'Beef', 'Premium Rindfleisch und japanisches Wagyu', 2, '', 2, 1);
-- ID: 11

-- ============================================================================
-- 3. 创建示例商品
-- ============================================================================

-- 商品1: 豪华海鲜套餐
INSERT INTO `flow_shop_artikel` 
  (`art_name`, `art_name_en`, `art_beschreibung_short`, `art_beschreibung_long`, 
   `art_preis_brutto`, `art_gewicht`, `art_menge`, `art_mindest`, `art_einheit`, 
   `art_ust_id`, `art_sichtbar`, `art_neu`, `art_position`) 
VALUES 
  ('Luxus Meeresfrüchte Set', 'Luxury Seafood Set', 
   'Auswahl an frischen Meeresfrüchten, Teppanyaki-gekocht', 
   '<p>Genießen Sie unsere prämiierte Zusammenstellung:<br>- Frische Riesenkrevetten (3 Stück)<br>- Jakobsmuscheln (2 Stück)<br>- Fischfilet (2 Stück)<br>- Seetang<br>- Frischer Reis<br>- Miso-Suppe</p>', 
   24.99, 350, 100, 1, 'Portion', 1, 1, 1, 1);
-- 获取插入的ID: SET @article_id1 = LAST_INSERT_ID();

-- 关联到分类: 海鲜套餐
-- INSERT INTO `flow_shop_artikel_zu_kategorien` 
--   (`artikel_id`, `kategorie_id`) 
-- VALUES 
--   (@article_id1, 7);

-- 商品2: A5和牛铁板烧
INSERT INTO `flow_shop_artikel` 
  (`art_name`, `art_name_en`, `art_beschreibung_short`, `art_beschreibung_long`, 
   `art_preis_brutto`, `art_gewicht`, `art_menge`, `art_mindest`, `art_einheit`, 
   `art_ust_id`, `art_sichtbar`, `art_neu`, `art_position`) 
VALUES 
  ('A5 Wagyu Teppanyaki', 'A5 Wagyu Teppanyaki', 
   'Premium japanisches A5 Wagyu, auf der Teppanyaki zubereitet', 
   '<p>Das Beste vom Besten:<br>- 200g A5 Wagyu Rind<br>- Zwiebeln, Pilze, Bohnensprossen<br>- Ei<br>- Frischer Reis<br>- Miso-Suppe<br><br>Spektakuläres Teppanyaki-Schauspiel während der Zubereitung!</p>', 
   34.99, 400, 80, 1, 'Portion', 1, 1, 1, 2);

-- 商品3: 妙味特选寿司拼盘
INSERT INTO `flow_shop_artikel` 
  (`art_name`, `art_name_en`, `art_beschreibung_short`, `art_beschreibung_long`, 
   `art_preis_brutto`, `art_gewicht`, `art_menge`, `art_mindest`, `art_einheit`, 
   `art_ust_id`, `art_sichtbar`, `art_neu`, `art_position`) 
VALUES 
  ('Miaowei Premium Sushi Platte', 'Miaowei Premium Sushi Platter', 
   'Auswahl der besten Sushi-Variationen', 
   '<p>Unsere Signature Sushi Platte mit 12 Stück:<br>- 4x Nigiri mit verschiedenen Fischsorten<br>- 4x Maki Rollen<br>- 4x Spezial Rolls<br><br>Perfekt für zwei Personen oder als Vorspeise!</p>', 
   18.50, 200, 60, 1, 'Platte', 1, 1, 1, 1);

-- 商品4: 毛豆
INSERT INTO `flow_shop_artikel` 
  (`art_name`, `art_name_en`, `art_beschreibung_short`, `art_beschreibung_long`, 
   `art_preis_brutto`, `art_gewicht`, `art_menge`, `art_mindest`, `art_einheit`, 
   `art_ust_id`, `art_sichtbar`, `art_neu`, `art_position`) 
VALUES 
  ('Edamame', 'Edamame', 
   'Gedämpfte grüne Sojabohnen mit Salz', 
   '<p>Frische, gedämpfte Edamame (Sojabohnen)<br>Mit feinem Meersalz gewürzt<br>Klassisches japanisches Vorspeisen-Gericht<br>Portion: 200g</p>', 
   4.99, 200, 200, 1, 'Portion', 1, 1, 0, 1);

-- 商品5: 日式沙拉
INSERT INTO `flow_shop_artikel` 
  (`art_name`, `art_name_en`, `art_beschreibung_short`, `art_beschreibung_long`, 
   `art_preis_brutto`, `art_gewicht`, `art_menge`, `art_mindest`, `art_einheit`, 
   `art_ust_id`, `art_sichtbar`, `art_neu`, `art_position`) 
VALUES 
  ('Japanischer Salat', 'Japanese Salad', 
   'Frischer Salat mit japanischem Dressing', 
   '<p>Frische, knackige Mischung mit:<br>- Eisbergsalat<br>- Avocado<br>- Karotten<br>- Sesamdressing<br>- Geröstete Sesamkörnchen</p>', 
   5.99, 250, 150, 1, 'Portion', 1, 1, 0, 2);

-- 商品6: 日本清酒
INSERT INTO `flow_shop_artikel` 
  (`art_name`, `art_name_en`, `art_beschreibung_short`, `art_beschreibung_long`, 
   `art_preis_brutto`, `art_gewicht`, `art_menge`, `art_mindest`, `art_einheit`, 
   `art_ust_id`, `art_sichtbar`, `art_neu`, `art_position`) 
VALUES 
  ('Sake (0,5l Flasche)', 'Sake (0.5l Bottle)', 
   'Premium japanischer Reiswein, kühl zu genießen', 
   '<p>Authentischer japanischer Sake<br>Alkoholgehalt: 15,5%<br>Volumen: 0,5l<br>Perfekt zu unseren Teppanyaki- und Sushi-Gerichten<br><br>Lagerung: Kühl lagern</p>', 
   12.99, 500, 40, 1, 'Flasche', 1, 1, 0, 1);

-- ============================================================================
-- 4. 支付方式配置
-- ============================================================================

-- 插入支付方式数据 (需要根据系统实际的支付方式表结构调整)
-- 这个部分具体取决于系统的支付模块实现

-- ============================================================================
-- 5. 分配商品到分类
-- ============================================================================
-- 注意: 需要获取实际的商品ID和分类ID

-- 示例（需要根据实际插入结果调整ID）:
-- INSERT INTO `flow_shop_artikel_zu_kategorien` 
--   (`artikel_id`, `kategorie_id`) 
-- VALUES 
--   (1, 7),   -- 海鲜套餐 -> 海鲜套餐分类
--   (2, 11),  -- 和牛 -> 牛肉分类
--   (2, 2),   -- 和牛 -> 主分类Teppanyaki
--   (3, 3),   -- 寿司 -> 寿司分类
--   (4, 4),   -- 毛豆 -> 配菜分类
--   (5, 4),   -- 沙拉 -> 配菜分类
--   (6, 5);   -- 清酒 -> 饮品分类

-- ============================================================================
-- 6. 完成提示
-- ============================================================================

-- 执行成功后，请在管理后台进行以下操作:
-- 1. 为每个商品上传高质量的图片（800x600或以上分辨率）
-- 2. 查看和编辑商品的详细信息
-- 3. 配置运费和支付方式
-- 4. 设置SMTP邮件配置
-- 5. 进行订单测试
-- 6. 在公网上发布前进行全面测试

-- ============================================================================
-- END OF SCRIPT
-- ============================================================================
