USE flow_shop;

UPDATE shop_firma
SET shop_name = 'Mannheim Sushi & Teppanyaki',
    firm_name = 'Mannheim Japanese Restaurant',
    street = 'Ludwigstraße',
    haus_nr = '12',
    postal_code = '68161',
    city = 'Mannheim',
    country = 'Deutschland',
    email = 'info@mannheim-sushi.de',
    telefon = '+49 621 1234567',
    web = 'www.mannheim-sushi.de'
WHERE id = 1;

INSERT INTO shop_categories (parent_id, active, network_id, level, ordered, childs, name_deu, title_deu, name_eng, title_eng)
SELECT 0, 'y', 0, 1, 1, 0, 'Sushi', 'Sushi', 'Sushi', 'Sushi'
WHERE NOT EXISTS (SELECT 1 FROM shop_categories WHERE name_deu = 'Sushi' AND level = 1);

INSERT INTO shop_categories (parent_id, active, network_id, level, ordered, childs, name_deu, title_deu, name_eng, title_eng)
SELECT 0, 'y', 0, 1, 2, 0, 'Teppanyaki', 'Teppanyaki', 'Teppanyaki', 'Teppanyaki'
WHERE NOT EXISTS (SELECT 1 FROM shop_categories WHERE name_deu = 'Teppanyaki' AND level = 1);

INSERT INTO shop_categories (parent_id, active, network_id, level, ordered, childs, name_deu, title_deu, name_eng, title_eng)
SELECT 0, 'y', 0, 1, 3, 0, 'Getränke', 'Getränke', 'Drinks', 'Drinks'
WHERE NOT EXISTS (SELECT 1 FROM shop_categories WHERE name_deu = 'Getränke' AND level = 1);

INSERT INTO shop_categories (parent_id, active, network_id, level, ordered, childs, name_deu, title_deu, name_eng, title_eng)
SELECT 0, 'y', 0, 1, 4, 0, 'Desserts', 'Desserts', 'Desserts', 'Desserts'
WHERE NOT EXISTS (SELECT 1 FROM shop_categories WHERE name_deu = 'Desserts' AND level = 1);

SET @cat_sushi = (SELECT id FROM shop_categories WHERE name_deu = 'Sushi' AND level = 1 LIMIT 1);
SET @cat_teppan = (SELECT id FROM shop_categories WHERE name_deu = 'Teppanyaki' AND level = 1 LIMIT 1);
SET @cat_drinks = (SELECT id FROM shop_categories WHERE name_deu = 'Getränke' AND level = 1 LIMIT 1);
SET @cat_desserts = (SELECT id FROM shop_categories WHERE name_deu = 'Desserts' AND level = 1 LIMIT 1);

INSERT INTO shop_articles_info (haendler_id, sortierung, childs, steuersatz, name_deu, desc_deu, grundeinheit, ge_netto_aktiv, gewicht, is_foto)
SELECT 0, 1, 1, 19, 'Sushi Deluxe', 'Ein Deluxe-Sushi-Set mit Lachs, Thunfisch, Avocado und Garnelen.', 'St.', 'n', 0.50, 'n'
WHERE NOT EXISTS (SELECT 1 FROM shop_articles_info WHERE name_deu = 'Sushi Deluxe');

SET @info_sushi_deluxe = (SELECT id FROM shop_articles_info WHERE name_deu = 'Sushi Deluxe' LIMIT 1);

INSERT INTO shop_articles (parent_id, sort, online, art_nr, netto, haendler_netto, ge_netto, angebot, angebot_active, menge, ge_menge, merkmal1, wert1, merkmal2, wert2, gewicht, filename, filetyp, gtin, mpn, imported, startbild, matrix)
SELECT @info_sushi_deluxe, 1, 'y', 'SUSHI01', 24.90, 24.90, 24.90, 0, 'n', 1, 1, 0, 0, 0, 0, 0.50, '', '', '', '', 'n', 1, 'n'
WHERE NOT EXISTS (SELECT 1 FROM shop_articles a WHERE a.parent_id = @info_sushi_deluxe AND a.sort = 1);

SET @article_sushi_deluxe = (SELECT id FROM shop_articles WHERE parent_id = @info_sushi_deluxe AND sort = 1 LIMIT 1);
INSERT INTO shop_article_to_cats (parent_id, cat_id, sort)
SELECT @article_sushi_deluxe, @cat_sushi, 1
WHERE @article_sushi_deluxe IS NOT NULL AND @cat_sushi IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM shop_article_to_cats WHERE parent_id = @article_sushi_deluxe AND cat_id = @cat_sushi);

INSERT INTO shop_articles_info (haendler_id, sortierung, childs, steuersatz, name_deu, desc_deu, grundeinheit, ge_netto_aktiv, gewicht, is_foto)
SELECT 0, 2, 1, 19, 'California Roll Set', 'Ein Set mit California Rolls, Mango und frischem Gemüse.', 'St.', 'n', 0.40, 'n'
WHERE NOT EXISTS (SELECT 1 FROM shop_articles_info WHERE name_deu = 'California Roll Set');

SET @info_california = (SELECT id FROM shop_articles_info WHERE name_deu = 'California Roll Set' LIMIT 1);
INSERT INTO shop_articles (parent_id, sort, online, art_nr, netto, haendler_netto, ge_netto, angebot, angebot_active, menge, ge_menge, merkmal1, wert1, merkmal2, wert2, gewicht, filename, filetyp, gtin, mpn, imported, startbild, matrix)
SELECT @info_california, 1, 'y', 'SUSHI02', 18.90, 18.90, 18.90, 0, 'n', 1, 1, 0, 0, 0, 0, 0.40, '', '', '', '', 'n', 1, 'n'
WHERE NOT EXISTS (SELECT 1 FROM shop_articles a WHERE a.parent_id = @info_california AND a.sort = 1);

SET @article_california = (SELECT id FROM shop_articles WHERE parent_id = @info_california AND sort = 1 LIMIT 1);
INSERT INTO shop_article_to_cats (parent_id, cat_id, sort)
SELECT @article_california, @cat_sushi, 2
WHERE @article_california IS NOT NULL AND @cat_sushi IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM shop_article_to_cats WHERE parent_id = @article_california AND cat_id = @cat_sushi);

INSERT INTO shop_articles_info (haendler_id, sortierung, childs, steuersatz, name_deu, desc_deu, grundeinheit, ge_netto_aktiv, gewicht, is_foto)
SELECT 0, 3, 1, 19, 'Teppanyaki Rindfilet', 'Zartes Rindfilet vom Teppanyaki-Grill mit Gemüse und Reis.', 'St.', 'n', 0.60, 'n'
WHERE NOT EXISTS (SELECT 1 FROM shop_articles_info WHERE name_deu = 'Teppanyaki Rindfilet');

SET @info_teppanyaki = (SELECT id FROM shop_articles_info WHERE name_deu = 'Teppanyaki Rindfilet' LIMIT 1);
INSERT INTO shop_articles (parent_id, sort, online, art_nr, netto, haendler_netto, ge_netto, angebot, angebot_active, menge, ge_menge, merkmal1, wert1, merkmal2, wert2, gewicht, filename, filetyp, gtin, mpn, imported, startbild, matrix)
SELECT @info_teppanyaki, 1, 'y', 'TEPP01', 18.90, 18.90, 18.90, 0, 'n', 1, 1, 0, 0, 0, 0, 0.60, '', '', '', '', 'n', 1, 'n'
WHERE NOT EXISTS (SELECT 1 FROM shop_articles a WHERE a.parent_id = @info_teppanyaki AND a.sort = 1);

SET @article_teppanyaki = (SELECT id FROM shop_articles WHERE parent_id = @info_teppanyaki AND sort = 1 LIMIT 1);
INSERT INTO shop_article_to_cats (parent_id, cat_id, sort)
SELECT @article_teppanyaki, @cat_teppan, 1
WHERE @article_teppanyaki IS NOT NULL AND @cat_teppan IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM shop_article_to_cats WHERE parent_id = @article_teppanyaki AND cat_id = @cat_teppan);

INSERT INTO shop_articles_info (haendler_id, sortierung, childs, steuersatz, name_deu, desc_deu, grundeinheit, ge_netto_aktiv, gewicht, is_foto)
SELECT 0, 4, 1, 19, 'Miso Suppe', 'Würzige Miso-Suppe mit Tofu, Wakame und Frühlingszwiebeln.', 'St.', 'n', 0.30, 'n'
WHERE NOT EXISTS (SELECT 1 FROM shop_articles_info WHERE name_deu = 'Miso Suppe');

SET @info_miso = (SELECT id FROM shop_articles_info WHERE name_deu = 'Miso Suppe' LIMIT 1);
INSERT INTO shop_articles (parent_id, sort, online, art_nr, netto, haendler_netto, ge_netto, angebot, angebot_active, menge, ge_menge, merkmal1, wert1, merkmal2, wert2, gewicht, filename, filetyp, gtin, mpn, imported, startbild, matrix)
SELECT @info_miso, 1, 'y', 'SUPP01', 3.90, 3.90, 3.90, 0, 'n', 1, 1, 0, 0, 0, 0, 0.30, '', '', '', '', 'n', 1, 'n'
WHERE NOT EXISTS (SELECT 1 FROM shop_articles a WHERE a.parent_id = @info_miso AND a.sort = 1);

SET @article_miso = (SELECT id FROM shop_articles WHERE parent_id = @info_miso AND sort = 1 LIMIT 1);
INSERT INTO shop_article_to_cats (parent_id, cat_id, sort)
SELECT @article_miso, @cat_teppan, 2
WHERE @article_miso IS NOT NULL AND @cat_teppan IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM shop_article_to_cats WHERE parent_id = @article_miso AND cat_id = @cat_teppan);

INSERT INTO shop_articles_info (haendler_id, sortierung, childs, steuersatz, name_deu, desc_deu, grundeinheit, ge_netto_aktiv, gewicht, is_foto)
SELECT 0, 5, 1, 19, 'Sake Premium', 'Warmer Premium-Sake aus feiner Reisqualität.', 'Fl.', 'n', 0.50, 'n'
WHERE NOT EXISTS (SELECT 1 FROM shop_articles_info WHERE name_deu = 'Sake Premium');

SET @info_sake = (SELECT id FROM shop_articles_info WHERE name_deu = 'Sake Premium' LIMIT 1);
INSERT INTO shop_articles (parent_id, sort, online, art_nr, netto, haendler_netto, ge_netto, angebot, angebot_active, menge, ge_menge, merkmal1, wert1, merkmal2, wert2, gewicht, filename, filetyp, gtin, mpn, imported, startbild, matrix)
SELECT @info_sake, 1, 'y', 'DRNK01', 4.90, 4.90, 4.90, 0, 'n', 1, 1, 0, 0, 0, 0, 0.50, '', '', '', '', 'n', 1, 'n'
WHERE NOT EXISTS (SELECT 1 FROM shop_articles a WHERE a.parent_id = @info_sake AND a.sort = 1);

SET @article_sake = (SELECT id FROM shop_articles WHERE parent_id = @info_sake AND sort = 1 LIMIT 1);
INSERT INTO shop_article_to_cats (parent_id, cat_id, sort)
SELECT @article_sake, @cat_drinks, 1
WHERE @article_sake IS NOT NULL AND @cat_drinks IS NOT NULL
  AND NOT EXISTS (SELECT 1 FROM shop_article_to_cats WHERE parent_id = @article_sake AND cat_id = @cat_drinks);

COMMIT;
