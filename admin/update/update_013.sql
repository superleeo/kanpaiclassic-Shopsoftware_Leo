
ALTER TABLE `#__rechnung` CHANGE `last_change` `last_change` timestamp NOT NULL DEFAULT '1970-01-02 00:00:00',
                            CHANGE `rechnungsdatum` `rechnungsdatum` timestamp NOT NULL DEFAULT '1970-01-02 00:00:00',
                            CHANGE `lieferdatum` `lieferdatum` timestamp NOT NULL DEFAULT '1970-01-02 00:00:00',
                            CHANGE `zahlungdatum` `zahlungdatum` timestamp NOT NULL DEFAULT '1970-01-02 00:00:00';


ALTER TABLE `#__rechnung` ADD `abholung_checkbox` ENUM('n','y') NOT NULL DEFAULT 'n';

