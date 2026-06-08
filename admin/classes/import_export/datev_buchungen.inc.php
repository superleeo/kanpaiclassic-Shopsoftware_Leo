<?php
/*
###################################################################################
  KANPAI CLASSIC Shopsoftware - Entwicklungsstand 06.2025

  Kanpai Classic - Web Development
  https://www.kanpaiclassic.com
  https://www.kanpaiclassic.com

  c Copyright by Kanpai Classic - Kanpai Classic Web Development


  Copyrightvermerke duerfen NICHT entfernt werden!

  ------------------------------------------------------------------------
  Dieses Programm ist eine Software von Kanpai Classic Web Development.
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

$head  = '""Umsatz (ohne Soll/Haben-Kz)"";'; // 1
$head .= '""Soll/Haben-Kennzeichen"";';      // 1
$head .= '""WKZ Umsatz"";';                  // 1
$head .= '""Kurs"";';						 // 4
$head .= '""Basisumsatz"";';			     // 5
$head .= '""WKZ Basisumsatz"";';
$head .= '""Konto"";';
$head .= '""Gegenkonto"";';
$head .= '""'.mb_convert_encoding('BU-Schlüssel', 'Windows-1252', 'UTF-8').'"";';
$head .= '""Belegdatum"";';				      // 10
$head .= '""Belegfeld 1"";';
$head .= '""Belegfeld 2"";';
$head .= '""Skonto"";';
$head .= '""Buchungstext"";';
$head .= '""Postensperre"";';				  // 15
$head .= '""Diverse Adressnummer"";';
$head .= '""'.mb_convert_encoding('Geschäftspartnerbank', 'Windows-1252', 'UTF-8').'"";';
$head .= '""Sachverhalt"";';
$head .= '""Zinssperre"";';
$head .= '""Beleglink"";';					  // 20
$head .= '""Beleginfo - Art 1"";';
$head .= '""Beleginfo - Inhalt 1"";';
$head .= '""Beleginfo - Art 2"";';
$head .= '""Beleginfo - Inhalt 2"";';
$head .= '""Beleginfo - Art 3"";';		      // 25
$head .= '""Beleginfo - Inhalt 3"";';
$head .= '""Beleginfo - Art 4"";';
$head .= '""Beleginfo - Inhalt 4"";';
$head .= '""Beleginfo - Art 5"";';
$head .= '""Beleginfo - Inhalt 5"";';	      // 30
$head .= '""Beleginfo - Art 6"";';
$head .= '""Beleginfo - Inhalt 6"";';
$head .= '""Beleginfo - Art 7"";';
$head .= '""Beleginfo - Inhalt 7"";';
$head .= '""Beleginfo - Art 8"";';            // 35
$head .= '""Beleginfo -Inhalt 8"";';
$head .= '""KOST1 - Kostenstelle"";';
$head .= '""KOST2 - Kostenstelle"";';
$head .= '""Kost Menge"";';
$head .= '""EU-Land u. USt-IdNr."";';	      // 40
$head .= '""EU-Steuersatz"";';
$head .= '""Abw. Versteuerungsart"";';
$head .= '""Sachverhalt L+L"";';
$head .= '""'.mb_convert_encoding('Funktionsergänzung L+L', 'Windows-1252', 'UTF-8').'"";';
$head .= '""BU 49 Hauptfunktionstyp"";';      // 45
$head .= '""BU 49 Hauptfunktionsnummer"";';
$head .= '""'.mb_convert_encoding('BU 49 Funktionsergänzung', 'Windows-1252', 'UTF-8').'"";';
$head .= '""Zusatzinformation - Art 1"";';
$head .= '""Zusatzinformation - Inhalt 1"";';
$head .= '""Zusatzinformation - Art 2"";';    // 50
$head .= '""Zusatzinformation - Inhalt 2"";';
$head .= '""Zusatzinformation - Art 3"";';
$head .= '""Zusatzinformation - Inhalt 3"";';
$head .= '""Zusatzinformation - Art 4"";';
$head .= '""Zusatzinformation - Inhalt 4"";'; // 55
$head .= '""Zusatzinformation - Art 5"";';
$head .= '""Zusatzinformation - Inhalt 5"";';
$head .= '""Zusatzinformation - Art 6"";';
$head .= '""Zusatzinformation - Inhalt 6"";';
$head .= '""Zusatzinformation - Art 7"";';    // 60
$head .= '""Zusatzinformation - Inhalt 7"";';
$head .= '""Zusatzinformation - Art 8"";';
$head .= '""Zusatzinformation - Inhalt 8"";';
$head .= '""Zusatzinformation - Art 9"";';
$head .= '""Zusatzinformation - Inhalt 9"";'; // 65
$head .= '""Zusatzinformation - Art 10"";';
$head .= '""Zusatzinformation - Inhalt 10"";';
$head .= '""Zusatzinformation - Art 11"";';
$head .= '""Zusatzinformation - Inhalt 11"";';
$head .= '""Zusatzinformation - Art 12"";';   // 70
$head .= '""Zusatzinformation - Inhalt 12"";';
$head .= '""Zusatzinformation - Art 13"";';
$head .= '""Zusatzinformation - Inhalt 13"";';
$head .= '""Zusatzinformation - Art 14"";';
$head .= '""Zusatzinformation - Inhalt 14"";';// 75
$head .= '""Zusatzinformation - Art 15"";';
$head .= '""Zusatzinformation - Inhalt 15"";';
$head .= '""Zusatzinformation - Art 16"";';
$head .= '""Zusatzinformation - Inhalt 16"";';
$head .= '""Zusatzinformation - Art 17"";';   // 80
$head .= '""Zusatzinformation - Inhalt 17"";';
$head .= '""Zusatzinformation - Art 18"";';
$head .= '""Zusatzinformation - Inhalt 18"";';
$head .= '""Zusatzinformation - Art 19"";';
$head .= '""Zusatzinformation - Inhalt 19"";';// 85
$head .= '""Zusatzinformation - Art 20"";';
$head .= '""Zusatzinformation - Inhalt 20"";';
$head .= '""'.mb_convert_encoding('Stück', 'Windows-1252', 'UTF-8').'"";';
$head .= '""Gewicht"";';
$head .= '""Zahlweise"";';                    // 90
$head .= '""'.mb_convert_encoding('Forderungsart', 'Windows-1252', 'UTF-8').'"";';
$head .= '""Veranlagungsjahr"";';
$head .= '""'.mb_convert_encoding('Zugeordnete Fälligkeit', 'Windows-1252', 'UTF-8').'"";';
$head .= '""Skontotyp"";';
$head .= '""Auftragsnummer"";';               // 95
$head .= '""Buchungstyp"";';
$head .= '""'.mb_convert_encoding('USt-Schlüssel (Anzahlungen)', 'Windows-1252', 'UTF-8').'"";';
$head .= '""EU-Mitgliedstaat (Anzahlungen)"";';
$head .= '""Sachverhalt L+L (Anzahlungen)"";';
$head .= '""EU-Steuersatz (Anzahlungen)"";';  // 100
$head .= '""'.mb_convert_encoding('Erlöskonto (Anzahlungen)', 'Windows-1252', 'UTF-8').'"";';
$head .= '""Herkunft-Kz"";';
$head .= '""Leerfeld"";';
$head .= '""KOST-Datum"";';
$head .= '""SEPA-Mandatsreferenz"";';         // 105
$head .= '""Skontosperre"";';
$head .= '""Gesellschaftername"";';
$head .= '""Beteiligtennummer"";';
$head .= '""Identifikationsnummer"";';
$head .= '""Zeichnernummer"";';               // 110
$head .= '""Postensperre bis"";';
$head .= '""Bezeichnung SoBil-Sachverhalt"";';
$head .= '""Kennzeichen SoBil-Buchung"";';
$head .= '""Festschreibung"";';
$head .= '""Leistungsdatum"";';               // 115
$head .= '""Datum Zuord. Steuerperiode"";';
$head .= "\r\n";

if (isset($buchung_file) && !file_exists($buchung_file)) {
   file_put_contents($buchung_file, $head);
}

else if ($file && !file_exists(SHOP_PATH.'/export/buchngen.csv')) {
   file_put_contents(SHOP_PATH.'/export/buchngen.csv', $head);
}

$csv = '';

for ($r = 0; $r < (is_array($data) ? count($data) : 0); $r++) {
   $d = $data[$r];
//   $articles = $data[$r]->articles;
//   $articles_count = $data[$r]->articles_count;
   $netto            = (float)$d->netto;
   $steuer1          = (float)$d->steuer1;
   $steuer2          = (float)$d->steuer2;
   $steuer3          = (float)$d->steuer3;
   $steuersatz1      = (float)$d->steuersatz1;
   $steuersatz2      = (float)$d->steuersatz2;
   $steuersatz3      = (float)$d->steuersatz3;
   $steuersatz       = 0;
   $versand          = (float)$d->versand;
   $versand_ust      = (float)$d->versand_ust;
   $zahlart_add      = (float)$d->zahlart_add;
   $zahlart_ust      = (float)$d->zahlart_ust;
//   $csv .= $data[$r]->user_rabatt;
   $gutschrift       = (float)$d->gutschrift;
   $gutschein_steuer = (float)$d->gutschein_steuer;
   $brutto           = 0;

   $brutto1_p = 0;
   $brutto2_p = 0;
   $steuer1_p = 0;
   $steuer2_p = 0;
   $zweizeilig = false;
   $ende = 1;

   // Nur erm. Steuer
   if ($steuer1 == 0 && $steuer2 > 0) {
      $netto   += $versand + $zahlart_add - $gutschrift;
      $steuer2 += $versand_ust + $zahlart_ust - $gutschein_steuer;
      $brutto  = $netto + $steuer2;
      $steuersatz = $steuersatz2;
   }

   else if ($steuer2 == 0 && $steuer1 > 1) {
      $netto   += $versand + $zahlart_add - $gutschrift;
      $steuer1 += $versand_ust + $zahlart_ust - $gutschein_steuer;
      $brutto  = $netto + $steuer1 + $steuer2;
      $steuersatz = $steuersatz1;
   }

   else {
      $steuer    = $steuer1 + $steuer2 + $versand_ust + $zahlart_ust - $gutschein_steuer;
      $brutto    = $netto + $steuer + $versand + $zahlart_add - $gutschrift;

      $netto1    = $steuer1 / ($steuersatz1 / 100);
      $netto2    = $netto - $netto1;
      $netto1    += $versand + $zahlart_add - $gutschrift;

      $steuer1   = $steuer1 + $versand_ust + $zahlart_ust - $gutschein_steuer;
      $steuer2_p = $steuer2;

      $brutto1_p = $netto1 + $steuer1;
      $brutto2_p = $netto2 + $steuer2;

      $steuer1_p = $steuer1 + $versand_ust + $zahlart_ust - $gutschein_steuer;
      $steuer2_p = $steuer2;

      if ($_2zeilig_check === 'y') {
         $brutto = $brutto1_p;
      }

      $steuersatz = $steuersatz1;
      $zweizeilig = true;
   }

   if ($_2zeilig_check === 'y' && $zweizeilig === true) {
      $ende = 2;
      $steuer2 = 0;
   }

   for ($z = 1; $z <= $ende; $z++) {
      if ($z == 2) {
         $brutto  = $brutto2_p;
         $steuer1 = 0;
         $steuer2 = $steuer2_p;
         $steuersatz = $steuersatz2;
      }

      $id = $d->id;

      if (substr($d->ebay_order, 0, 2) == 'a:') {
         $id = substr($d->ebay_order, 2);
      }

      $csv .= '""'.number_format($brutto, 2, ',', '').'"";';       // '"Umsatz";';
      $csv .= '""S"";';       // '"Soll/Haben-Kennzeichen";';
      $csv .= '""EUR"";';       // '"WKZ Umsatz";';
      $csv .= ';';       // '"Kurs";';
      $csv .= ';';       // (5) '"Basisumsatz";';
      $csv .= ';';       // '"WKZ Basisumsatz";';
      $csv .= '""'.$d->user_id.'"";';       // '"Konto";';
      $csv .= ';';       // '"Gegenkonto";';
      $csv .= ';';       // '"BU-Schlüssel";';
      $csv .= '""'.date('dm', strtotime($d->rechnungsdatum)).'"";';       // (10) '"Belegdatum";';
      $csv .= '""'.$id.'"";';       // '"Belegfeld 1";';
      $csv .= ';';       // '"Belegfeld 2";';
      $csv .= ';';       // '"Skonto";';
      $csv .= ';';       // '"Buchungstext";';
      $csv .= ';';       // (15) '"Postensperre";';
      $csv .= ';';       // '"Diverse Adressnummer";';
      $csv .= ';';       // '"Geschäftspartnerbank";';
      $csv .= ';';       // '"Sachverhalt";';
      $csv .= ';';       // '"Zinssperre";';
      $csv .= ';';       // (20) '"Beleglink";';
      $csv .= ';';       // '"Beleginfo – Art 1";';
      $csv .= ';';       // '"Beleginfo – Inhalt 1";';
      $csv .= ';';       // '"Beleginfo – Art 2";';
      $csv .= ';';       // '"Beleginfo – Inhalt 2";';
      $csv .= ';';       // (25) '"Beleginfo – Art 3";';
      $csv .= ';';       // '"Beleginfo – Inhalt 3";';
      $csv .= ';';       // '"Beleginfo – Art 4";';
      $csv .= ';';       // '"Beleginfo – Inhalt 4";';
      $csv .= ';';       // '"Beleginfo – Art 5";';
      $csv .= ';';       // (30) '"Beleginfo – Inhalt 5";';
      $csv .= ';';       // '"Beleginfo – Art 6";';
      $csv .= ';';       // '"Beleginfo – Inhalt 6";';
      $csv .= ';';       // '"Beleginfo – Art 7";';
      $csv .= ';';       // '"Beleginfo – Inhalt 7";';
      $csv .= ';';       // (35) '"Beleginfo – Art 8";';
      $csv .= ';';       // '"Beleginfo – Inhalt 8";';
      $csv .= ';';       // '"KOST1 – Kostenstelle";';
      $csv .= ';';       // '"KOST2 – Kostenstelle";';
      $csv .= ';';       // '"Kost Menge";';
      $csv .= '""'.$d->ustid.'"";';       // (40) '"EU-Land u. USt-IdNr.";';
      $csv .= '""'.number_format($steuersatz, 2, ',', '.').'""';       // '"EU-Steuersatz";';
      $csv .= ';';       // '"Abw. Versteuerungsart";';
      $csv .= ';';       // '"Sachverhalt L+L";';
      $csv .= ';';       // '"Funktionsergänzung L+L";';
      $csv .= ';';       // (45) '"BU 49 Hauptfunktionstyp";';
      $csv .= ';';       // '"BU 49 Hauptfunktionsnummer";';
      $csv .= ';';       // '"BU 49 Funktionsergänzung";';
      $csv .= '""Name"";'; // '"Zusatzinformation – Art 1";';
      $csv .= '""'.mb_convert_encoding($d->nachname, 'Windows-1252', 'UTF-8').'"";';       // '"Zusatzinformation – Inhalt 1";';
      $csv .= '""Vorname"";';       // (50) '"Zusatzinformation – Art 2";';
      $csv .= '""'.mb_convert_encoding($d->vorname, 'Windows-1252', 'UTF-8').'"";';       // '"Zusatzinformation – Inhalt 2";';
      $csv .= '""Firma"";';       // '"Zusatzinformation – Art 3";';
      $csv .= '""'.mb_convert_encoding($d->firma, 'Windows-1252', 'UTF-8').'"";';       // '"Zusatzinformation – Inhalt 3";';
      $csv .= '""Str.Nr."";';       // '"Zusatzinformation – Art 4";';
      $csv .= '""'.mb_convert_encoding($d->adresse, 'Windows-1252', 'UTF-8').' '.$d->hausnr.'"";';       // (55) '"Zusatzinformation – Inhalt 4";';
      $csv .= '""PLZ"";';       // '"Zusatzinformation – Art 5";';
      $csv .= '""'.$d->plz.'";';       // '"Zusatzinformation – Inhalt 5";';
      $csv .= '""ORT"";';       // '"Zusatzinformation – Art 6";';
      $csv .= '""'.mb_convert_encoding($d->ort, 'Windows-1252', 'UTF-8').'"";';       // '"Zusatzinformation – Inhalt 6";';
      $csv .= '""Land"";';       // (60) '"Zusatzinformation – Art 7";';
      $csv .= '""'.mb_convert_encoding(\KANPAICLASSIC\Helper::getStaatName($d->staat, $d->staat2), 'Windows-1252', 'UTF-8').'"";';       // '"Zusatzinformation – Inhalt 7";';
      $csv .= '""Zahlart"";';       // '"Zusatzinformation – Art 8";';
      $csv .= '""'.mb_convert_encoding(\KANPAICLASSIC\Helper::getZahlartText($d->zahlungsart), 'Windows-1252', 'UTF-8').'"";';       // '"Zusatzinformation – Inhalt 8";';
      $csv .= ';';       // '"Zusatzinformation – Art 9";';
      $csv .= ';';       // (65) '"Zusatzinformation – Inhalt 9";';
      $csv .= ';';       // '"Zusatzinformation – Art 10";';
      $csv .= ';';       // '"Zusatzinformation – Inhalt 10";';
      $csv .= ';';       // '"Zusatzinformation – Art 11";';
      $csv .= ';';       // '"Zusatzinformation – Inhalt 11";';
      $csv .= ';';       // (70) '"Zusatzinformation – Art 12";';
      $csv .= ';';       // '"Zusatzinformation – Inhalt 12";';
      $csv .= ';';       // '"Zusatzinformation – Art 13";';
      $csv .= ';';       // '"Zusatzinformation – Inhalt 13";';
      $csv .= ';';       // '"Zusatzinformation – Art 14";';
      $csv .= ';';       // (75) '"Zusatzinformation – Inhalt 14";';
      $csv .= ';';       // '"Zusatzinformation – Art 15";';
      $csv .= ';';       // '"Zusatzinformation – Inhalt 15";';
      $csv .= ';';       // '"Zusatzinformation – Art 16";';
      $csv .= ';';       // '"Zusatzinformation – Inhalt 16";';
      $csv .= ';';       // (80) '"Zusatzinformation – Art 17";';
      $csv .= ';';       // '"Zusatzinformation – Inhalt 17";';
      $csv .= ';';       // '"Zusatzinformation – Art 18";';
      $csv .= ';';       // '"Zusatzinformation – Inhalt 18";';
      $csv .= ';';       // '"Zusatzinformation – Art 19";';
      $csv .= ';';       // (85) '"Zusatzinformation – Inhalt 19";';
      $csv .= ';';       // '"Zusatzinformation – Art 20";';
      $csv .= ';';       // '"Zusatzinformation – Inhalt 20";';
      $csv .= ';';       // '"Stück";';
      $csv .= ';';       // '"Gewicht";';
      $csv .= ';';       // (90) '"Zahlweise";';
      $csv .= ';';       // '"Forderungsart";';
      $csv .= ';';       // '"Veranlagungsjahr";';
      $csv .= ';';       // '"Zugeordnete Fälligkeit";';
      $csv .= ';';       // '"Skontotyp";';
      $csv .= ';';       // (95) '"Auftragsnummer";';
      $csv .= ';';       // '"Buchungstyp";';
      $csv .= ';';       // '"USt-Schlüssel (Anzahlungen)";';
      $csv .= ';';       // '"EU-Mitgliedstaat (Anzahlungen)";';
      $csv .= ';';       // '"Sachverhalt L+L (Anzahlungen)";';
      $csv .= ';';       // (100) '"EU-Steuersatz (Anzahlungen)";';
      $csv .= ';';       // '"Erlöskonto (Anzahlungen)";';
      $csv .= ';';       // '"Herkunft-Kz";';
      $csv .= ';';       // '"Leerfeld";';
      $csv .= ';';       // '"KOST-Datum";';
      $csv .= ';';       // (105) '"SEPA-Mandatsreferenz";';
      $csv .= ';';       // '"Skontosperre";';
      $csv .= ';';       // '"Gesellschaftername";';
      $csv .= ';';       // '"Beteiligtennummer";';
      $csv .= ';';       // '"'.$best_nr.'";';
      $csv .= ';';       // (110) '"Zeichnernummer";';
      $csv .= ';';       // '"Postensperre bis";';
      $csv .= ';';       // '"Bezeichnung SoBil-Sachverhalt";';
      $csv .= ';';       // '"Kennzeichen SoBil-Buchung";';
      $csv .= ';';       // '"Festschreibung";';
      $csv .= ';';       // (115) '"Leistungsdatum";';
      $csv .= ';';       // '"Datum Zuord. Steuerperiode";';

      $csv .= "\r\n";

      if (isset($buchung_file)) {
         file_put_contents($buchung_file, $csv, FILE_APPEND);
         $csv = '';
      }

      else if ($file) {
         file_put_contents(SHOP_PATH.'/export/buchngen.csv', $csv, FILE_APPEND);
         $csv = '';
      }
   }
}

if (!$file) {
   header('Content-type: text/csv');

   header('Content-type: text/csv');

   if ($csv != '') {
      header('Content-Disposition: attachment; filename="'.$filename.'"');
      echo ($head.$csv);
   }

   else {
      header('Content-Disposition: attachment; filename=keine_daten_gefunden.csv');
      echo 'Keine Daten im Zeitraum vorhanden';
   }

   exit;
}
