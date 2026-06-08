ALTER TABLE `#__firma` ADD `rechnung_preis` DECIMAL(5,2) NOT NULL DEFAULT '0' AFTER `rechnung_check`;
ALTER TABLE `#__firma` ADD `lastschrift_preis` DECIMAL(5,2) NOT NULL DEFAULT '0' AFTER `lastschrift_check`;

CREATE TABLE `#__cookies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bezeichnung` varchar(32) NOT NULL DEFAULT '',
  `lang` varchar(3) NOT NULL,
  `value` varchar(4096) NOT NULL DEFAULT '',
   PRIMARY KEY (`id`),
   UNIQUE KEY `idx_bez_lang` (`bezeichnung`,`lang`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

INSERT INTO `#__cookies` (`id`, `bezeichnung`, `lang`, `value`) VALUES
(1, 'wesentlich_text', 'deu', 'Anbieter: Keine Übermittlung an Drittanbieter\nZweck: Diese Cookies sind für die Funktion des Onlineshops notwendig (z.B. Warenkorb oder dem Festlegen Ihrer Datenschutzeinstellungen) und können nicht deaktiviert werden.\nCookiename: flow_shop\nLebensdauer: Schließen des Browsers'),
(2, 'social_text', 'deu', 'Anbieter: Facebook Ireland Limited (Facebook, Instagram) und weitere...\nZweck: Mit diesen Cookies wird die Funktionalität des Onlineshops erweitert, um z.B. Like-Buttons und weitere Social-Media-Scripte bereit zustellen.\nDiese Cookies können von uns oder von Drittanbietern gesetzt werden, deren Dienste wir auf unseren Seiten verwenden.\nWenn Sie diese Cookies nicht zulassen, funktionieren einige oder alle dieser Dienste möglicherweise nicht mehr.\nCookiename: _js_datr, datr, fr, locale, sb, wd');

UPDATE #__firma SET paypal_preis        = -paypal_preis;
UPDATE #__firma SET vorkasse_preis      = -vorkasse_preis;
UPDATE #__firma SET bar_preis           = -bar_preis;
UPDATE #__firma SET sofort_preis        = -sofort_preis;
UPDATE #__firma SET vrpay_preis         = -vrpay_preis;
UPDATE #__firma SET kklastschrift_preis = -kklastschrift_preis;
UPDATE #__firma SET paypalplus_preis    = -paypalplus_preis;
UPDATE #__firma SET amazon_preis        = -amazon_preis;
UPDATE #__firma SET twint_preis         = -twint_preis;
UPDATE #__firma SET easycredit_preis    = -easycredit_preis;
UPDATE #__firma SET klarna_preis        = -klarna_preis;
UPDATE #__firma SET paydirekt_preis     = -paydirekt_preis;

UPDATE #__data SET data = -data WHERE type = 'postfinance_preis';
