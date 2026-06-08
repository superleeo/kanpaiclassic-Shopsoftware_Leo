ALTER TABLE `#__rechnung` 
   CHANGE `netto` `netto` DECIMAL(24,9) NOT NULL DEFAULT 0.00,
   CHANGE `steuer1` `steuer1` DECIMAL(24,9) NOT NULL DEFAULT 0.00,
   CHANGE `steuer2` `steuer2` DECIMAL(24,9) NOT NULL DEFAULT 0.00,
   CHANGE `steuer3` `steuer3` DECIMAL(24,9) NOT NULL DEFAULT 0.00,
   CHANGE `versand` `versand` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
   CHANGE `versand_ust` `versand_ust` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
   CHANGE `zahlart_add` `zahlart_add` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
   CHANGE `zahlart_ust` `zahlart_ust` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
   CHANGE `user_rabatt` `user_rabatt` DECIMAL(6,4) NOT NULL DEFAULT 0.00,
   CHANGE `rabatt` `rabatt` DECIMAL(16,9) NOT NULL DEFAULT 0.00,
   CHANGE `gutschrift` `gutschrift` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
   CHANGE `gutschein_brutto` `gutschein_brutto` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
   CHANGE `gutschein_steuer` `gutschein_steuer` DECIMAL(12,2) NOT NULL DEFAULT 0.00;
