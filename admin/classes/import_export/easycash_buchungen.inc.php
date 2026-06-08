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

$csv  = '# rechnung, re_id, user_id, bestellnummer, gewerbe, netto, steuer1, steuer2, steuer3, steuersatz1, steuersatz2, stuersatz3, versand, versand_ust, zahlart_add, zahlart_ust, waehrung_id, user_rabatt gutschrift, gutschrift_brutto, gutschein_steuer,
           anrede, vorname, nachname, firma, ustid, adresse, hausnur, plz, ort, buland, staat, email,
           lf_anrede, lf_vorname, lf_nachname, lf_firma, lf_adresse, lf_hausnur, lf_plz, lf_ort, lf_buland, lf_staat,
           lieferdatum, rechnungsnummer, rechnungsdatum, zahlungsart, zahlungsinfo1, zahlungsinfo2'.CR;
$csv .= '# artikel, re_art_id, artikel_id, artikel_nummer, menge, artikel_preis, steuersatz, name, desc, merkmal1, wert2, merkmal2, wert2, grundeinheit, versand_preis, gewicht,
           rechner_breite, rechner_hoehe, rechner_tiefe, rechner_mode, rechner_einheit'.CR;

$head  = '"Buchungsart";';
$head .= '"Belegdatum";';
$head .= '"Belegnr";';      // Rechnungsnummer
$head .= '"Bezeichnung";';  // Bestellnummer
$head .= '"Zahldatum";';
$head .= '"Zahlungsart";';

$head .= '"BruttoBetrag";';
$head .= '"USt";';
$head .= '"USt-Betrag";';
$head .= '"USt-Erm";';
$head .= '"USt-Betrag-Erm";';

$head .= '"USt0";';
$head .= '"Kundennr";';
$head .= '"Firma";';
$head .= '"Name";';
$head .= '"Vorname";';

$head .= '"Strasse";';
$head .= '"PLZ";';
$head .= '"Ort";';
$head .= '"Land";';
$head .= '"UStID"';

$head .= "\r\n";

if ($file && isset($buchung_file) && !file_exists($buchung_file)) {
   file_put_contents($buchung_file, $head);
}

$csv  = '';

for ($r = 0; $r < (is_array($data) ? count($data) : 0); $r++) {
//   $articles = $data[$r]->articles;
//   $articles_count = $data[$r]->articles_count;
   $netto            = (float)$data[$r]->netto;
   $steuer1          = (float)$data[$r]->steuer1;
   $steuer2          = (float)$data[$r]->steuer2;
   $steuer3          = (float)$data[$r]->steuer3;
   $steuersatz1      = (float)$data[$r]->steuersatz1;
   $steuersatz2      = (float)$data[$r]->steuersatz2;
   $steuersatz3      = (float)$data[$r]->steuersatz3;
   $versand          = (float)$data[$r]->versand;
   $versand_ust      = (float)$data[$r]->versand_ust;
   $zahlart_add      = (float)$data[$r]->zahlart_add;
   $zahlart_ust      = (float)$data[$r]->zahlart_ust;
//   $csv .= $data[$r]->user_rabatt;
   $gutschrift       = (float)$data[$r]->gutschrift;
   $gutschein_steuer = (float)$data[$r]->gutschein_steuer;
   $brutto           = 0;

   $brutto1_p = 0;
   $brutto2_p = 0;
   $steuer1_p = 0;
   $steuer2_p = 0;
   $zweizeilig = false;
   $ende = 1;

   // Nur erm. Steuer
   if ($steuer1  == 0 && $steuer2 > 0) {
      $netto     += $versand + $zahlart_add - $gutschrift;
      $steuer2   += $versand_ust + $zahlart_ust - $gutschein_steuer;
      $brutto    = $netto + $steuer2;
   }

   else if ($steuer2  == 0 && $steuer1 > 0) {
      $netto     += $versand + $zahlart_add - $gutschrift;
      $steuer1   += $versand_ust + $zahlart_ust - $gutschein_steuer;
      $brutto    = $netto + $steuer1 + $steuer2;
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

      $steuersatz2_p = $steuersatz2;
      $zweizeilig = true;
   }

   if ($_2zeilig_check === 'y' && $zweizeilig === true) {
      $ende = 2;
      $steuer2 = 0;
      $steuersatz2 = 0;
   }

   for ($z = 1; $z <= $ende; $z++) {
      if ($z == 2) {
         $brutto  = $brutto2_p;
         $steuer1 = 0;
         $steuer2 = $steuer2_p;
         $steuersatz1 = 0;
         $steuersatz2 = $steuersatz2_p;
      }

      $bestellnummer = $data[$r]->bestellnummer;

      if (substr($data[$r]->ebay_order, 0, 2) == 'a:') {
         $bestellnummer = substr($data[$r]->ebay_order, 2);
      }

      $csv .= '"Einnahmen";';
      $csv .= '"'.\KANPAICLASSIC\Helper::sqlDatum($data[$r]->rechnungsdatum).'";';
      $csv .= '"'.$data[$r]->rechnungsnummer.'";';
      $csv .= '"'.$bestellnummer.'";';
      $csv .= '"'.\KANPAICLASSIC\Helper::sqlDatum($data[$r]->zahlungdatum).'";';
      $csv .= '"'.\KANPAICLASSIC\Helper::getZahlartText($data[$r]->zahlungsart).'";';

      $csv .= '"'.number_format($brutto, 2, ',', '.').'";';
      $csv .= '"'.number_format($steuersatz1, 2, ',', '.').'";';
      $csv .= '"'.number_format($steuer1, 2, ',', '.').'";';
      $csv .= '"'.number_format($steuersatz2, 2, ',', '.').'";';
      $csv .= '"'.number_format($steuer2, 2, ',', '.').'";';

      $csv .= '"'.number_format($steuersatz3, 2, ',', '.').'";';
      $csv .= '"'.$data[$r]->user_id.'";';
      $csv .= '"'.$data[$r]->firma.'";';
      $csv .= '"'.$data[$r]->vorname.'";';
      $csv .= '"'.$data[$r]->nachname.'";';

      $csv .= '"'.$data[$r]->adresse.' '.$data[$r]->hausnr.'";';
      $csv .= '"'.$data[$r]->plz.'";';
      $csv .= '"'.$data[$r]->ort.'";';
      $csv .= '"'.\KANPAICLASSIC\Helper::getStaatName($data[$r]->staat, $data[$r]->staat2).'";';
      $csv .= '"'.$data[$r]->ustid.'"';

      $csv .= "\r\n";

      if ($file) {
         file_put_contents($buchung_file, $csv, FILE_APPEND);
         $csv = '';
      }
   }
}

if (!$file) {
   header('Content-type: text/csv');

   if ($csv != '') {
      header('Content-Disposition: attachment; filename="'.$filename.'"');
      echo $head.$csv;
   }

   else {
      header('Content-Disposition: attachment; filename=keine_daten_gefunden.csv');
      echo 'Keine Daten im Zeitraum vorhanden';
   }

   exit;
}
