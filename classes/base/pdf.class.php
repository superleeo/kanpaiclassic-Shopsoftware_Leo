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

namespace KANPAICLASSIC;

if (!defined('KANPAICLASSIC')) {
   die ("This file cannot run outside the Kanpai Classic Shopsoftware");
}

if (!defined('CONF_PDF_FONT1')) {
   define ('CONF_PDF_FONT1', 'firasanslight');    // Regular
   define ('CONF_PDF_FONT2', 'firasansmedium');   // Bold
}

// verwendete Schriftgrößen
if (!defined('FONT_NORMAL')) {
   define ('FONT_NORMAL', 10);
   define ('FONT_SMALL', 9);
   //define ('FONTBOLD', 'B'); // Fontname im Original *-bold.ttf wird zu *b.php
   define ('FONTBOLD', '');
}

// Bei Änderungen auch hier!
// /classes/pdf_lastschrift.class.php
// /classes/base/pdfwiderruf.class.php
// /classes/modules/portal/adminpdf.class.php
// /classes/modules/bestellzusammenfassung/pdf_collector.class.php
// /classes/modules/pdfkatalog/pdfkatalog.class.php
// /classes/modules/naehrwerte/pdf.class.php
// /admin/classes/paket_etiketten_pdf.class.php


class PDF extends \TCPDF
{
   private $head_img;
   private $foot_img;

   function init($head_img, $foot_img, $orientation = 'P', $unit = 'mm', $format = 'format') {
      $this->head_img = $head_img;
      $this->foot_img = $foot_img;
   }

   function header() {
      if ($this->page == 1) {
         if (file_exists($this->head_img)) {
            $this->Image($this->head_img, 0, 0, 210);
         }
      }
   }

   function footer() {
      if ($this->page == 1) {
         if (file_exists($this->foot_img)) {
            $this->Image($this->foot_img, 0, 265.6, 210);
         }
      }

      $this->SetY(-10);
      $this->SetFont(CONF_PDF_FONT1,'i', 8);
      $this->SetTextColor(128);
      $this->Cell(0, 10, 'Seite '.$this->PageNo().' von '.$this->getAliasNbPages(), 0, 0, 'C');
   }
}

class KANPAICLASSIC_pdf
{
   protected $pdf;
   protected $seite = 1;
   protected $details;
   protected $artikel;
   protected $lang;
   protected $is_kunde = false;

   protected $db;
   protected $params;
   public    $text;
   protected $bestellung;
   protected $pdfart = '';
   protected $zahlarttext = '';
   protected $waehrung;
   protected $is_netto = true;
   protected $is_ausland = false;

   protected $font1;
   protected $font2;


   function __construct() {
      $this->db = Control::getDB();
      $this->params = Control::getParams();
      $this->text = Control::getText();
      $this->bestellung = Control::getBestellung();
      $this->waehrung = ' '.Helper::waehrungText($this->params->firma['waehrung1'], 1);
   }

   // $id  : id von Eintrag Rechnung
   // $art ' 'rechnung', 'bestaetigung', lieferschein
   // $mode: D -> Download, F -> als File Speichern
   function makePdf ($id, $art, $mode = 'D', $for = 'admin') {
      $this->getData($id, $art);
      $this->lang = $this->params->firma['default_lang'];

      if ($for == 'kunde') {
         // PDF in Kundensprache
         $this->lang = $this->details->lang_kunde;
         $this->is_kunde = true;
      }

      if ($art == 'bestellung') {
         $pdfart = 'b';
         $datei = $this->details->bestellnummer . '.pdf';
      }

      else if ($art == 'lieferschein') {
         $pdfart = 'l';
         $datei = $this->details->bestellnummer . '.pdf';
      }

      else {
         $pdfart = 'r';
         $datei  = 'Re-' . $this->details->rechnungsnummer . '.pdf';

         // Zahlungsart / Rechnung auf Server speichern
         if (Helper::getData('rechnung_server', 'n') == 'y' && $mode == 'D') {
            $mode   = 'FD';

            // Verzeichnisse und Dateien anlegen, wenn nicht existiert
            if (!is_dir(SHOP_PATH.'/downloads')) {
               mkdir(SHOP_PATH.'/downloads');
            }

            if (!is_dir(SHOP_PATH.'/downloads/rechnungen')) {
               mkdir(SHOP_PATH.'/downloads/rechnungen');

               file_put_contents(SHOP_PATH.'/downloads/rechnungen/.htaccess', "AuthType Basic\nAuthName 'No Access'\norder deny,allow\ndeny from all\nallow from 127.0.0.1\nErrorDocument 403 '<h1>Zugriff gesperrt</h1>'\n");
               file_put_contents(SHOP_PATH.'/downloads/rechnungen/index.php', "<!DOCTYPE html>\n<html>\n   <head>\n      <title></title>\n   </head>\n   \n   <body>\n   </body>\n</html>\n");
            }
         }
      }

      $head_img = TEMPLATE_PATH . '/images/rechnungskopf_' . $this->lang . '.jpg';
      $foot_img = TEMPLATE_PATH . '/images/rechnungsfuss_' . $this->lang . '.jpg';

      // Bei Datei speichern: Pfad setzten
      if ($mode == 'F') {
         $datei = SHOP_PATH.'/tmp/'.$datei;
      }

      $this->pdfart = $pdfart;

      // Neue PDF, A4, Einheiten in mm
      $this->pdf = new PDF('P', 'mm', 'A4', true, 'UTF-8', false);
      $this->pdf->init($head_img, $foot_img, 'P', 'mm', 'A4');

      // Nicht ändern, sonst zeigt Adobe Reader nur Punkte bei manchen Zeichensätzen
      $this->pdf->setFontSubsetting(false);
      $this->pdf->AddFont(CONF_PDF_FONT1);
      $this->pdf->AddFont(CONF_PDF_FONT2);
      // Ausgabegroeße 100%, Seitenweise anzeigen
      $this->pdf->SetDisplayMode(100, 'single');

      // Documenttitel, Titel im Acrobat-Reader
      $this->pdf->SetTitle('Rechnung');

      // Raender setzen: links, oben, rechts
      $this->pdf->SetMargins(21, 45, 20);
      $this->pdf->setRTL(false, true);

      $this->font1 = CONF_PDF_FONT1;
      $this->font2 = CONF_PDF_FONT2;

      $this->pdf->AddPage();
      $this->pdf->setfont($this->font2, '', 11);
      $this->printAdresse($art);
      $posY = $this->pdf->GetY();
      $this->pdf->SetY($posY + 10);

      $anzahl_artikel = count($this->artikel);
      $seiten         = 1;

      if ($this->pdfart == 'r') {
         $this->pdf->setfont($this->font2, FONTBOLD, 11);
         $this->pdf->Cell(100, 5, $this->text->get('pdf', 'rechnung', $this->lang), 0, 1, 'L', 0);
         $this->pdf->setfont($this->font1, '', FONT_NORMAL);
      }

      // Titelzeile Artikel ausgeben
      $this->pdf->setfont($this->font2, FONTBOLD, FONT_NORMAL);
      $this->printTitelZeile();
      $this->pdf->setfont($this->font1, '', FONT_NORMAL);

      $zeile = $this->pdf->GetY();
      $summen_hoehe = $this->printSummen('check', 0);
      $zeile = $this->pdf->GetY();

      for ($i = 0; $i < $anzahl_artikel; $i++) {
         $t = $i + 1;

         if (($t < $anzahl_artikel && $seiten == 1 && $zeile > (297 - 65))
            || ($t < $anzahl_artikel && $seiten > 1 && $zeile > (297 -  43))
            ) {
            $this->pdf->AddPage();
            $this->pdf->SetY(30);
            $zeile = $this->pdf->GetY();
            // $zeile = 1;
            $seiten++;
         }

         $zeile += $this->printArtikel($this->artikel[$i], '');
         $this->pdf->setfont($this->font1, '', FONT_NORMAL);
      }

      $this->pdf->setfont($this->font1, '', FONT_NORMAL);

      if ((297 - $summen_hoehe - ($seiten == 1 ? 35 : 10)) < $this->pdf->GetY()) {
         $this->pdf->AddPage();
         $seiten++;
      }

      $this->printSummen('', (297 - $summen_hoehe - ($seiten == 1 ? 35 : 20)));
      $this->pdf->setfont($this->font1, '', FONT_NORMAL);

      // Nährwerttabelle(n) anhängen
      if (defined('CONF_MODULE_NAEHRWERTE') && !empty($this->bestellung->nw_zutaten)) {
         $articles = $this->bestellung->nw_zutaten;
         $nw_pdf = Control::getNaehrwertePdf();

         // 2 Artikel pro Seite
         for ($i = 0; $i < count($articles); $i += 2) {
            if (isset($articles[$i])) {
               $this->pdf->AddPage();
               $this->pdf->SetY(20);
               $nw_pdf->printContent($articles[$i], $this->pdf, $for, $this->lang);
            }

            if (isset($articles[$i + 1])) {
               $nw_pdf->printContent($articles[$i + 1], $this->pdf, $for, $this->lang);
            }
         }
      }

      if (ob_get_contents()) {
         ob_end_clean();
      }

      // Zahlungsart / Rechnung auf Server speichern
      if ($mode == 'FD') {
         $this->pdf->Output(SHOP_PATH.'/downloads/rechnungen/'.$datei, 'F');
         $this->pdf->Output($datei, 'D');
      }

      else {
         $this->pdf->Output($datei, $mode);
      }

      // Falls nicht 'lieferschein' in shop_rechnungen speichern
      if ($pdfart != 'l') {
         $test = $this->db->querySingleValue("SELECT pdf FROM #__rechnung WHERE id = $id");

         // Nur wenn $test nicht 'r' ist ändern
         if ($test != 'r') {
            $this->db->query("UPDATE #__rechnung SET pdf = '$pdfart' WHERE id = $id");
         }
      }

      return($datei);
   }

   // Daten holen
   protected function getData($id, $art) {
      $this->bestellung->getDetailBestellung($id);
      $this->details = $this->bestellung->dataDetails;
      $this->bestellung->getDetailArtikel($id);
      $this->artikel = $this->bestellung->dataArtikel;

      // 1 - Mit USt
      // 2 - keine USt
      // 3 - Kleingewerbe
      $gewerbe = (int)$this->details->gewerbe;

      if ($this->params->firma['tax_show'] == 'y' || $gewerbe == 3) {
         $this->is_netto = false;
      }

      if ($gewerbe == 2) {
         $this->is_ausland = true;
         $this->is_netto = true;
      }

      if ($gewerbe == 3) {
         $this->is_ausland = true;
      }

      $this->zahlarttext = Helper::getZahlartText($this->details->zahlungsart, $this->lang).($art != 'lieferschein' ? ' '.$this->text->get('zahlart', 'leer', $this->lang) : '');
   }

   // Adresse Ausgeben
   protected function printAdresse($art) {
      $this->pdf->setfont($this->font1, '', 11);
      $zeile_s1 = array();
      $zeile_s2 = array();
      $zeile_s3 = array();
      $anz_zeilen = 7;

      $this->pdf->Ln(0, true);

      // zeile 1 ... Linke Spalte
      $i = 1;

      if ($this->pdfart == 'l') {
         $zeile_s1[$i++] = $this->details->lf_vorname. ' ' . $this->details->lf_nachname;

         if ($this->details->lf_firma == '') {
//            $zeile_s1[$i++] = '';
         }
         else {
            $zeile_s1[$i++] = $this->details->lf_firma;
         }

         if ($this->details->lf_postnr != '') {
            $zeile_s1[$i++] = $this->details->lf_postnr;
         }

         $zeile_s1[$i++] = $this->details->lf_adresse.' '.$this->details->lf_hausnr;
         $zeile_s1[$i++] = $this->details->lf_plz. ' ' . $this->details->lf_ort;

         if ($this->details->lf_buland != '') {
            $zeile_s1[$i++] = $this->details->lf_buland;
         }

         $zeile_s1[$i++] = $this->details->lf_land;
      }

      else {
         $zeile_s1[$i++] = $this->details->vorname. ' ' . $this->details->nachname;
         if ($this->details->firma == '') {
//            $zeile_s1[$i++] = '';
         }
         else {
            $zeile_s1[$i++] = $this->details->firma;
         }

         $zeile_s1[$i++] = $this->details->adresse.' '.$this->details->hausnr;
         $zeile_s1[$i++] = $this->details->plz. ' ' . $this->details->ort;

         if ($this->details->buland != '') {
            $zeile_s1[$i++] = $this->details->buland;
         }

         $zeile_s1[$i++] = $this->details->land;

         if ($this->pdfart == 'r' && $this->details->ustid != '') {
            $zeile_s1[$i++] = $this->details->ustid;
         }
      }

      while($i <= $anz_zeilen) {
         $zeile_s1[$i] = '';
         $i++;
      }

      // Zeile 7 ... Rechte Spalte
      $i = 1;

      if ($this->pdfart == 'r') {
         $zeile_s2[$i] = $this->text->get('adresse', 'rechnr', $this->lang) . ':';
         $zeile_s3[$i] = $this->details->rechnungsnummer;
         $i++;
         $zeile_s2[$i] = $this->text->get('konto', 'datum', $this->lang) . ':';
         $zeile_s3[$i] = Helper::sqlDatum($this->details->rechnungsdatum);
         $i++;
      }

      else if ($this->pdfart == 'l') {
         $zeile_s2[$i] = '';
         $zeile_s3[$i] = $this->text->get('pdf', 'lieferschein', $this->lang) . '';
         $i++;
         $zeile_s2[$i] = $this->text->get('pdf', 'lieferdatum', $this->lang) . ':';
         $zeile_s3[$i] = Helper::sqlDatum($this->details->lieferdatum);
         $i++;
      }

      $zeile_s2[$i] = $this->text->get('adresse', 'bestnr', $this->lang) . ':';
      $zeile_s3[$i] = $this->details->bestellnummer;
      $i++;
      $zeile_s2[$i] = $this->text->get('adresse', 'vom', $this->lang) . ':';
      $zeile_s3[$i] = Helper::sqlDatum($this->details->created);
      $i++;

      if ($this->pdfart == 'r' && $this->details->lieferdatum != '' && substr($this->details->lieferdatum, 0, 10) != '0000-00-00' && $this->details->lieferdatum !== null) {
         $zeile_s2[$i] = $this->text->get('pdf', 'lieferdatum', $this->lang) . ':';
         $zeile_s3[$i] = Helper::sqlDatum($this->details->lieferdatum);
         $i++;
      }

      while($i <= $anz_zeilen) {
         $zeile_s2[$i] = '';
         $zeile_s3[$i] = '';
         $zeile[$i + 6] = '';
         $i++;
      }

      $laenge = max($this->pdf->GetStringWidth($zeile_s3[1]), $this->pdf->GetStringWidth($zeile_s3[2]), $this->pdf->GetStringWidth($zeile_s3[3]), $this->pdf->GetStringWidth($zeile_s3[4]), $this->pdf->GetStringWidth($zeile_s3[5]));
      $laenge = (int)ceil($laenge) + 1;
      $x = $this->pdf->GetX();
      $y = $this->pdf->GetY();
      $this->pdf->SetY($y + 10);

      for ($i = 1; $i <= $anz_zeilen; $i++) {
         $this->pdf->Cell(108, 5, $zeile_s1[$i], 0, 0, 'L', 0);
         if ($i < 6) {
            $this->pdf->Cell(65-$laenge, 5, $zeile_s2[$i], 0, 0, 'R', 0);

            // Text 'Lieferschein' bold
            if ($i == 1 && $this->pdfart == 'l') {
               $this->pdf->setfont($this->font2, FONTBOLD, 11);
            $this->pdf->Cell($laenge, 5, $zeile_s3[$i], 0, 1, 'R', 0);
               $this->pdf->setfont($this->font1, '', 11);
            }

            else {
               $this->pdf->Cell($laenge, 5, $zeile_s3[$i], 0, 1, 'R', 0);
            }
         }
      }
   } // End Method printAdresse()

   // Titelzeile Artikel ausgeben
   protected function printTitelZeile () {
      $this->pdf->setfont($this->font2, FONTBOLD, 10);
      $this->pdf->Cell(25, 10, $this->text->get('artikel','best_nr', $this->lang), 0, 0, '', 0);
      $this->pdf->Cell(82, 10, $this->text->get('artikel','name', $this->lang), 0, 0, '', 0);

      // Kein Preis anzeigen
      if ($this->pdfart == 'b' && $this->params->firma['price_login'] == 'y' && $this->params->user_id == 0 || $this->pdfart == 'l') {
         $this->pdf->Cell(18, 10, '    ', 0, 0, 'R', 0);
         $this->pdf->Cell( 8, 10, '   ', 0, 0, 'R', 0);
         $this->pdf->Cell(18, 10, $this->text->get('artikel','menge', $this->lang), 0, 0, 'R', 0);
         $this->pdf->Cell(22, 10, '', 0, 1, 'R', 0);
      }

      // Preis anzeigen
      else {
         $this->pdf->Cell(18, 10, $this->text->get('artikel', $this->is_netto ? 'netto' : 'brutto', $this->lang), 0, 0, 'R', 0);
         $this->pdf->Cell(8,  10, ($this->is_ausland ? '' : $this->text->get('artikel','ust', $this->lang)), 0, 0, 'L', 0);
         $this->pdf->Cell(18, 10, $this->text->get('artikel','menge', $this->lang), 0, 0, 'R', 0);
         $this->pdf->Cell(22, 10, $this->text->get('artikel','summe', $this->lang), 0, 1, 'R', 0);
      }

      $this->pdf->Line(21, $this->pdf->GetY(), 194, $this->pdf->GetY());
   } // End Method printTitelZeile()

   // Artikel-Zeile ausgeben
   protected function printArtikel ($artikel, $mode = '') {
      $zeile      = 0;
      $is_rechner = false;
      $is_conf    = false;
      $is_matrix  = false;
      $is_mixer   = false;
      $hoehe      = 5;
      $rechner    = '';

      $version    = (int)$artikel->art_version;
      $name       = $artikel->name_shop;
      $menge      = number_format($artikel->menge, 0);
      $motiv_text = $artikel->motiv_upload_text;

      $merkmal1   = $artikel->merkmal1;
      $wert1      = $artikel->wert1;
      $merkmal2   = $artikel->merkmal2;
      $wert2      = $artikel->wert2;

      if ($this->is_kunde) {
         $name       = $artikel->name_kunde;
         $merkmal1   = $artikel->merkmal1_kunde;
         $wert1      = $artikel->wert1_kunde;
         $merkmal2   = $artikel->merkmal2_kunde;
         $wert2      = $artikel->wert2_kunde;
      }

      $merkmale   = ($merkmal1 != '' ? $merkmal1.': ' : '');
      $merkmale  .= ($wert1 != '' ? $wert1 : '');
      $merkmale  .= ($merkmale != '' ? ', ' : '');
      $merkmale  .= ($merkmal2 != '' ? $merkmal2.': ' : '');
      $merkmale  .= ($wert2 != '' ? $wert2 : '');
      $abstand_u  = 0;

      $start = $this->pdf->getY();

      $this->pdf->setfont($this->font1, '', FONT_NORMAL);

      if ($artikel->masse_check == 'y' || $artikel->rechner_check == 'y') {
         $grundeinheit = $this->text->get('ge', $artikel->grundeinheit, $this->lang);

         if ($artikel->masse_check == 'y' && $artikel->rechner_check != 'y') {
            $menge = number_format($artikel->menge, (int)$artikel->masse_komma, ',', '').' '.$artikel->grundeinheit_rechner;
         }
      }

      if (defined('CONF_MODULE_MEGACONFIGURATOR') && $artikel->configurator != '[]' && $artikel->configurator != '') {
         $is_conf = true;
      }

      if (defined('CONF_MODULE_MATRIX') && $artikel->preismatrix != '') {
         $is_matrix = true;
      }

      if ($artikel->rechner_check == 'y') {
         $is_rechner      = true;
         $grundeinheit_r  = $this->text->get('ge', $artikel->grundeinheit_rechner, $this->lang);
         $rechner_einheit = $this->text->get('ge', $artikel->rechner_einheit, $this->lang);
         $breite_r        = number_format((float)$artikel->rechner_breite, (int)$artikel->masse_komma, ',', '').$rechner_einheit;
         $hoehe_r         = number_format((float)$artikel->rechner_hoehe,  (int)$artikel->masse_komma, ',', '').$rechner_einheit;
         $tiefe_r         = number_format((float)$artikel->rechner_tiefe,  (int)$artikel->masse_komma, ',', '').$rechner_einheit;
         $rechner         = $this->text->get('ge', 'mode'.$artikel->rechner_mode).' ';

         if ((int)$artikel->rechner_mode == 1) {
            $rechner .= $breite_r;
         }

         else if ((int)$artikel->rechner_mode == 2) {
            $rechner .= $breite_r.' x '.$hoehe_r.' = '.number_format((float)$artikel->rechner_breite * (float)$artikel->rechner_hoehe, (int)$artikel->masse_komma, ',', '').$grundeinheit_r;
         }

         else if ((int)$artikel->rechner_mode == 3) {
            $rechner .= $breite_r.' x '.$hoehe_r.' x '.$tiefe_r.' = '.number_format((float)$artikel->rechner_breite * (float)$artikel->rechner_hoehe * (float)$artikel->rechner_tiefe, (int)$artikel->masse_komma, ',', '').$grundeinheit_r;
         }
      }

      if ($mode != 'check') {
         $this->pdf->Cell(18, 2.5, '', 0, 1, 'R', false, '', 0, true, 'T', 'M');
         $y = $this->pdf->GetY();
         $zeile += 0.25;
      }

      if ($this->pdf->GetStringWidth($name.'  '.$merkmale) <= 82) {
         if ($mode != 'check') {
            $this->pdf->Cell(25, $hoehe, Helper::truncate($artikel->artikel_nummer, 15), 0, 0, '', 0);
            $this->pdf->Cell(82, $hoehe, Helper::truncate($name, 40) . '  ' . $merkmale, 0, 0, '', 0);
            $zeile += $hoehe;
         }
      }

      else {
         $name1      = '';
         $name2      = '';
         $name1_voll = false;
         $teile      = explode(' ', $name);

         $name1      = trim($teile[0]);
         unset($teile[0]);

         foreach ($teile as $t) {
            if (!$name1_voll) {
               if ($this->pdf->GetStringWidth($name1.' '.$t) <= 82) {
                  $name1 .= ' '.$t;
               }

               else {
                  $name2 = $t;
                  $name1_voll = true;
               }
            }

            else {
               if ($this->pdf->GetStringWidth($name2.' '.$t.'  '.$merkmale) <= 82) {
                  $name2 .= ' '.$t;
                  //$zeile += .5;
               }

               else {
                  break;
               }
            }
         }

         $name2 .= '  '.$merkmale;

         if ($mode != 'check') {
            $x = $this->pdf->getX();
            $y = $this->pdf->getY();
            $this->pdf->setXY($x, $y + $hoehe / 2);
            $this->pdf->Cell(25, $hoehe, Helper::truncate($artikel->artikel_nummer, 15), 0, 0, '', 0);
            $x = $this->pdf->getX();
            $zeile += $hoehe;

            $this->pdf->setXY($x, $y);
            $this->pdf->Cell(82, $hoehe, trim($name1), 0, 0, '', 0);
            $this->pdf->setXY($x, $y + $hoehe);
            $this->pdf->Cell(82, $hoehe, trim($name2), 0, 0, '', 0);
            $this->pdf->setXY($x + 82, $y + $hoehe / 2);
            $zeile += $hoehe;

            $abstand_u  = $hoehe / 2;
         }
      }

      // Keine Preise
      if (($this->pdfart == 'b' && $this->params->firma['price_login'] == 'y' && $this->params->user_id == 0) || $this->pdfart == 'l') {
         if ($mode != 'check') {
            $this->pdf->Cell(18, $hoehe, '   ', 0, 0, 'R', 0);
            $this->pdf->Cell( 8, $hoehe, '   ', 0, 0, 'R', 0);
            $this->pdf->Cell(18, $hoehe, $menge, 0, 1, 'R', 0);
         }
      }

      else {
         // Netto-Preise
         if ($this->is_netto) {
            if ($mode != 'check') {
                  $name  = $artikel->name_shop;
                  $menge = number_format($artikel->menge, 0);
                  $this->pdf->Cell(18, $hoehe, number_format($artikel->artikel_preis, 2, ',', '.').$this->waehrung, 0, 0, 'R', 0);
                  $this->pdf->Cell( 8, $hoehe, ($this->is_ausland ? '' : $artikel->satz.'%'), 0, 0, 'R', 0);
                  $this->pdf->Cell(18, $hoehe, $menge, 0, 0, 'R', 0);
               $this->pdf->Cell(22, $hoehe, number_format(round($artikel->artikel_preis, 2) * $artikel->menge, 2, ',', '.').$this->waehrung, 0, 1, 'R', 0);
               }
            }

         // Brutto-Preise
         else {
            if ($mode != 'check') {
                  $this->pdf->Cell(18, $hoehe, number_format($artikel->artikel_brutto, 2, ',', '.') . $this->waehrung, 0, 0, 'R', 0);
                  $this->pdf->Cell( 8, $hoehe, ($this->is_ausland ? '' : $artikel->satz.'%'), 0, 0, 'R', 0);
                  $this->pdf->Cell(18, $hoehe, $menge, 0, 0, 'R', 0);
                  $this->pdf->Cell(22, $hoehe, number_format($artikel->artikel_brutto * $artikel->menge, 2, ',', '.') . $this->waehrung, 0, 1, 'R', 0);
            }
         }
      }

      $this->pdf->setXY($this->pdf->getX(), $this->pdf->getY() + $abstand_u);

      if ($motiv_text != '') {

         $text = explode("\n", $motiv_text);

         if ($mode != 'check') {

             $line1 = "";
             $line2 = "";

             if(count($text)>0){

                 if(strlen($text[0])>90){

                     $line1 = trim(substr($text[0],0,90));
                     $line2 = trim(substr($text[0],91,183));

                     if(strlen($text[0])>183 || count($text)>1){
                         $line2.="...";
                     }



                 }else{

                     $line1 = trim($text[0]);

                     if(count($text)>1){
                         $line2 = trim(Helper::truncate($text[1],90));

                         if(strlen($text[1])>90  || count($text)>2){
                             $line2.="...";
                         }
                     }

                 }
             }

             if(!empty($line1)){
                 $this->pdf->Cell(25, 2.5, '', 0, 0, '', 0);
                 $this->pdf->Cell(90, 2.5, $line1, 0, 0, '', 0);
                 $this->pdf->Cell(18, 2.5, '', 0, 1, 'R', 0);
             }

             if(!empty($line2)){
                 $this->pdf->Cell(25, 2.5, '', 0, 0, '', 0);
                 $this->pdf->Cell(90, 2.5, $line2, 0, 0, '', 0);
                 $this->pdf->Cell(18, 2.5, '', 0, 1, 'R', 0);
             }


            /*for ($i = 0; $i < count($text); $i++) {
               if ($i > 1) {
                   break;
               }

               $this->pdf->Cell(25, 2.5, '', 0, 0, '', 0);
               //$this->pdf->Cell(90, 2.5, $motiv_text, 0, 1, '', 0);
               $dots = strlen($text[$i])>91 || ($i == 1 && count($text) > 1 );

               $this->pdf->Cell(90, 2.5, Helper::truncate( trim($text[$i]), 91).($dots ? ' ...' : ''), 0, 0, '', 0);
               $this->pdf->Cell(18, 2.5, '', 0, 1, 'R', 0);
            }*/

         }
      }

      if ($is_matrix) {
         if ($mode != 'check') {
            $matrix = json_decode($artikel->preismatrix);
            $this->pdf->Cell(25, 2.5, '', 0, 0, '', 0);
            $this->pdf->Cell(90, 2.5, $matrix->{'breite_'.$this->lang}.' x '.$matrix->{'hoehe_'.$this->lang}.' ('.$matrix->{'einheit_'.$this->lang}.') : '.number_format($matrix->breite, $matrix->komma, ',', '').' x '.number_format($matrix->hoehe, $matrix->komma, ',', ''), 0, 1, '', 0);
            $this->pdf->Cell(18, 2.5, '', 0, 1, 'R', 0);
            $zeile += 0.25;
         }
      }

      if ($is_conf) {
         $configurator = Control::getModuleConfigurator();
         $conf         = json_decode($artikel->configurator, true);
         $texte        = null;

         if (isset($conf['texte'])) {
             $texte = $conf['texte'];
            unset($conf['texte']);
         }

         $c = count($conf) - 1;

         for ($k = 0; $k <= $c; $k++) {
            if ($configurator->configLineToText($conf[$k], true) !='') {
               if ($mode != 'check') {
                  $this->pdf->Cell(25, 5, '', 0, 0, '', 0);
                  $this->pdf->Cell(($is_rechner ? 90 : 90), 5, $configurator->configLineToText($conf[$k], true), 0, ($is_rechner ? 0 : 1), '', 0);
               }

               if ($mode != 'check') {
                  if ($is_rechner) {
                     $this->pdf->Cell(36, 5, $rechner, 0, 1, 'R', 0);
                     $is_rechner = false;
                  }
               }

               $zeile += 0.5;
            }
         }

         if ($texte !== null) {
            foreach ($texte as $t) {
               if ($mode != 'check') {
                  $this->pdf->Cell(25, 5, '', 0, 0, '', 0);
                  $this->pdf->Cell(($is_rechner ? 90 : 90), 5, $configurator->textById($t['text_id'], $this->params->selected_lang).': '.nl2br($t['text']), 0, ($is_rechner ? 0 : 1), '', 0);
               }

               if ($mode != 'check') {
                  if ($is_rechner) {
                     $this->pdf->Cell(36, 5, $rechner, 0, 1, 'R', 0);
                     $is_rechner = false;
                  }
               }

               $zeile += 0.5;
            }
         }
      }

      if ($is_rechner) {
         $this->pdf->Cell(25, $hoehe, '', 0, 0, '', 0);
         $this->pdf->Cell(90, $hoehe, '', 0, 0, '', 0);
         $this->pdf->Cell(58, $hoehe, $rechner, 0, 1, '', 0);
         $is_rechner = false;

         if (!$is_conf) {
            $zeile += 1;
         }
      }

      if ((int)$artikel->lager_zeit > 0) {
         $this->pdf->Cell(25, $hoehe, '', 0, 0, '', 0);
         $this->pdf->Cell(90, $hoehe, $this->text->get('art_detail', 'lieferzeit2').': '.$artikel->lager_zeit.' '.$this->text->get('art_detail', 'tage'), 0, 0, '', 0);
         $this->pdf->Cell(58, $hoehe, '', 0, 1, '', 0);
         $zeile += 1;
      }

      if ($artikel->mixer != '') {
         $is_mixer = true;
      }

      // Artikel-Mixer und Kategori-Mixer
      if ($is_mixer && $artikel->mixer != '') {
         $mix = json_decode($artikel->mixer);

         if (is_array($mix) && count($mix) > 0) {
            if ($mode == 'check') {
               $zeile += $hoehe * count($mix);
            }

            else {
               $this->pdf->SetTextColor(128);

               for ($m = 0; $m < count($mix); $m++) {
                     $name_m  = ($mode == 'admin') ? $mix[$m]->artikel_name : $mix[$m]->artikel_name2;
                     $menge_m = (isset($mix[$m]->value) ? $mix[$m]->value : number_format($mix[$m]->menge, 0));

                     if ($this->is_kunde) {
                        $name_m  = $mix[$m]->artikel_name2;
                     }

                     $this->pdf->Cell(25, $hoehe, Helper::truncate($mix[$m]->art_nr, 15), 0, 0, '', 0);
                     $this->pdf->Cell(82, $hoehe, Helper::truncate($name_m, 40) . '  ' . $merkmale, 0, 0, '', 0);
                     $this->pdf->Cell(18, $hoehe, '', 0, 0, 'R', 0);
                     $this->pdf->Cell(26, $hoehe, $menge_m, 0, 0, 'R', 0);
                     $this->pdf->Cell(22, $hoehe, '', 0, 1, 'R', 0);
                     $zeile += $hoehe;
               }

               $this->pdf->SetTextColor(0);
            }
         }
      }

      $this->pdf->Cell(18, 2.5, '', 0, 1, 'R', false, '', 0, true, 'T', 'M');
      $zeile += 1;

      $this->pdf->Line(21, $this->pdf->GetY(), 194, $this->pdf->GetY());

      return ($this->pdf->getY() - $start);
   } // End Method printArtikel ()

   protected function printSummen($check = '', $ypos) {
      $cell_height = 5;
      $zeilen      = 0;
      $zinsen      = '';
      $txt_height  = 0;
      $msg         = '';

      $isAbholung = $this->details->abholung_checkbox == 'y';

      if ((int)$this->details->zahlungsart == 13 && $this->details->zahlungsinfo2 != '') {
         $zinsen = number_format((float)$this->details->zahlungsinfo2, 2, ',', '.');
      }

      // Jeweils Anzahl Zeilen bei Summen / 4 Spalten
      // zeile  1 - 13 Leer / Mitteilung an Kunden
      // zeile 14 - 26 Leer / Mitteilung an Kunden
      // zeile 27 - 40 Text
      // Zeile 41 - 54 Betrag

      $zeile = array();
      // .. mit leeren Werten vorbelegen
      for ($i = 1; $i <= 4 * 13; $i++) {
         $zeile[$i][0] = '';
         $zeile[$i][1] = '';
      }

      // PDF ohne Preise
      if ($this->pdfart == 'b' && $this->params->firma['price_login'] == 'y' && $this->params->user_id == 0 || $this->pdfart == 'l') {
         $i = 32;
         $zeile[$i][0] = $this->zahlarttext;
         $zeile[13 + $i++][0] = '';
      }

      // Rechnung, Bestellung , wenn Preise sichtbar
      else {
         // B2B-kunden / Nettopreise
         if ($this->is_netto) {
            // Zwischesumme und Strich darunter (27 + 28)
            $zeile[27][0] = $this->text->get('artikel', 'zw_summe', $this->lang);
            $zeile[27][1] = 'B';
            $zeile[40][0] = number_format($this->details->netto, 2, ',', '.') . $this->waehrung;
            $zeile[40][1] = 'B';
            $zeile[41][0] = 'strich';
            $i = 29;

            // Rabatt (29))
            if ($this->details->rabatt > 0.0) {
               $zeile[$i][0] = $this->text->get('artikel', 'rabatt', $this->lang);
               $zeile[13 + $i++][0] = '- ' . number_format($this->details->rabatt_netto, 2, ',', '.') . $this->waehrung;
            }

            // Versandkosten (30))
            if(!$isAbholung){
                $zeile[$i][0] = $this->text->get('artikel', 'versand', $this->lang);
            }else{
                $zeile[$i][0] = $this->text->get('warenkorb', 'abholung', $this->lang);
            }
            $zeile[13 + $i++][0] = number_format($this->details->versand_netto, 2, ',', '.') . $this->waehrung;

            // Zahlart - auch negative Werte möglich (31)
               $zeile[$i][0] = $this->zahlarttext;
               $zeile[13 + $i++][0] = number_format($this->details->zahlart_netto, 2, ',', '.') . $this->waehrung;

            // Gutschrift / Gutschein (32)
            if ($this->details->gutschrift_brutto > 0.0 || $this->details->gutschein_brutto > 0.0) {
               $zeile[$i][0] = $this->text->get('artikel', 'gutschrift', $this->lang);
               $zeile[13 + $i++][0] = '- ' . number_format($this->details->gutschrift_netto, 2, ',', '.') . $this->waehrung;
            }

            if (($this->details->steuer1 + $this->details->steuer2 + $this->details->steuer3) > 0) {
               $steuer1 = $this->details->steuer1 - $this->details->rabatt_ust1;
               $steuer2 = $this->details->steuer2 - $this->details->rabatt_ust2;
               $steuer3 = $this->details->steuer3 - $this->details->rabatt_ust3;

               // Nur reduzierte USt
               if ($this->details->steuer1 == 0 ) {
                  $steuer2 += $this->details->versand_ust + $this->details->zahlart_ust - $this->details->gutschrift_ust;
               }

               // Normale USt oder gemischt
               else {
                  $steuer1 += $this->details->versand_ust + $this->details->zahlart_ust - $this->details->gutschrift_ust;
               }

               // Steuer reduziert (33)
               if ($steuer2 > 0) {
                  $zeile[$i][0] = $this->text->get('artikel', 'ust', $this->lang) . ' ' . $this->details->steuersatz2 . '%';
                  $zeile[13 + $i++][0] = number_format($steuer2, 2, ',', '.') . $this->waehrung;
               }

               // Steuer normal (34)
               if ($steuer1 > 0) {
                  $zeile[$i][0] = $this->text->get('artikel', 'ust', $this->lang) . ' ' . $this->details->steuersatz1 . '%';
                  $zeile[13 + $i++][0] = number_format($steuer1, 2, ',', '.') . $this->waehrung;
               }
            }

            // Summe (35 + 36)
            $zeile[$i][0] = '';
            $zeile[13 + $i++][0] = 'strich';

            $zeile[$i][0] = $this->text->get('artikel', 'gesamtsumme', $this->lang);
            $zeile[$i][1] = 'B';
            $gsumme = ($this->details->netto
                  + $this->details->steuer1
                  + $this->details->steuer2
                  + $this->details->steuer3
                  + $this->details->versand_brutto
                  + $this->details->zahlart_brutto
                  - $this->details->rabatt_brutto
                  - $this->details->gutschrift_brutto);
            $zeile[13 + $i][0] = number_format($gsumme, 2, ',', '.') . $this->waehrung;
            $zeile[13 + $i++][1] = 'B';

            // 2. Währung (37)
            if ($this->params->firma['check_w2'] == 'y') {
               $zeile[$i][0] = '';
               $zeile[13 + $i++][0] = number_format(($gsumme * (float)$this->params->firma['kurs2']), 2, ',', '.').' '.Helper::waehrungText($this->params->firma['waehrung2'], 1);
            }

            // 3. Waehrung (38)
            if ($this->params->firma['check_w3'] == 'y') {
               $zeile[$i][0] = '';
               $zeile[13 + $i++][0] = number_format(($gsumme * (float)$this->params->firma['kurs3']), 2, ',', '.').' '.Helper::waehrungText($this->params->firma['waehrung3'], 1);
            }

            // 4, Währung (39)
            if ($this->params->firma['check_w4'] == 'y') {
               $zeile[$i][0] = '';
               $zeile[13 + $i++][0] = number_format(($gsumme * (float)$this->params->firma['kurs4']), 2, ',', '.').' '.Helper::waehrungText($this->params->firma['waehrung4'], 1);
            }
         }

         // Bruttopreise / Endkunden
         else {
            // Zwischesumme und Strich darunter (27 + 28)
            $zeile[27][0] = $this->text->get('artikel', 'zw_summe', $this->lang);
            $zeile[27][1] = 'B';
            $zeile[40][0] = number_format($this->details->brutto, 2, ',', '.') . $this->waehrung;
            $zeile[40][1] = 'B';
            $zeile[41][0] = 'strich';
            $i = 29;

            // Rabatt (29))
            if ($this->details->rabatt > 0.0) {
               $zeile[$i][0] = $this->text->get('artikel', 'rabatt', $this->lang);
               $zeile[13 + $i++][0] = '- ' . number_format($this->details->rabatt_brutto, 2, ',', '.') . $this->waehrung;
            }


            // Versandkosten (30))
            if(!$isAbholung){
                $zeile[$i][0] = $this->text->get('artikel', 'versand', $this->lang);
            }else{
                $zeile[$i][0] = $this->text->get('warenkorb', 'abholung', $this->lang);
            }

            $zeile[13 + $i++][0] = number_format($this->details->versand_brutto, 2, ',', '.') . $this->waehrung;

            // Zahlart - auch negative Werte möglich (31)
               $zeile[$i][0] = $this->zahlarttext;
               $zeile[13 + $i++][0] = number_format($this->details->zahlart_brutto, 2, ',', '.') . $this->waehrung;


            // Gutschrift / Gutschein (34)
            if (($this->details->gutschrift_brutto + (float)$this->details->gutschein_brutto) > 0.0) {
               $zeile[$i][0] = $this->text->get('artikel', 'gutschrift', $this->lang);
               $zeile[13 + $i++][0] = '- ' . number_format($this->details->gutschrift_brutto, 2, ',', '.') . $this->waehrung;
            }

            // Summe (35 + 36)
            $zeile[$i][0] = '';
            $zeile[13 + $i++][0] = 'strich';

            $zeile[$i][0] = $this->text->get('artikel', 'gesamtsumme', $this->lang);
            $zeile[$i][1] = 'B';

            $gsumme = ( $this->details->brutto
                      + $this->details->versand_brutto
                      + $this->details->zahlart_brutto
                      - $this->details->gutschrift_brutto
                      - $this->details->rabatt_brutto);
            $zeile[13 + $i][0] = number_format($gsumme, 2, ',', '.') . $this->waehrung;
            $zeile[13 + $i][1] = 'B';
            $i++;

            if (($this->details->steuer1 + $this->details->steuer2 + $this->details->steuer3) > 0) {
               $steuer1 = $this->details->steuer1 - $this->details->rabatt_ust1;
               $steuer2 = $this->details->steuer2 - $this->details->rabatt_ust2;
               $steuer3 = $this->details->steuer3 - $this->details->rabatt_ust3;

               if ($this->details->steuer1 == 0) {
                  $steuer2 += $this->details->versand_ust + $this->details->zahlart_ust - $this->details->gutschrift_ust;
               }
               else {
                  $steuer1 += $this->details->versand_ust + $this->details->zahlart_ust - $this->details->gutschrift_ust;
               }

               // $zeile[$i][0] = $this->text->get('artikel', 'netto', $this->lang);
               // $zeile[13 + $i++][0] = number_format($gsumme - $steuer1 - $steuer2 - $steuer3, 2, ',', '.') . $this->waehrung;

               // Steuer reduziert (32)
               if ($steuer2 > 0) {
                  $zeile[$i][0] = $this->text->get('artikel', 'ust_lang', $this->lang) . ' (' . $this->details->steuersatz2 . '%)';
                  $zeile[13 + $i++][0] = number_format($steuer2, 2, ',', '.') . $this->waehrung;
               }

               // Steuer normal (33)
               if ($steuer1 > 0) {
                  $zeile[$i][0] = $this->text->get('artikel', 'ust_lang', $this->lang) . ' (' . $this->details->steuersatz1 . '%)';
                  $zeile[13 + $i++][0] = number_format($steuer1, 2, ',', '.') . $this->waehrung;
               }
            }

            // 2. Währung (37)
            if ($this->params->firma['check_w2'] == 'y') {
               $zeile[$i][0] = '';
               $zeile[13 + $i++][0] = number_format(($gsumme * (float)$this->params->firma['kurs2']), 2, ',', '.').' '.Helper::waehrungText($this->params->firma['waehrung2'], 1);
            }

            // 3. Waehrung (38)
            if ($this->params->firma['check_w3'] == 'y') {
               $zeile[$i][0] = '';
               $zeile[13 + $i++][0] = number_format(($gsumme * (float)$this->params->firma['kurs3']), 2, ',', '.').' '.Helper::waehrungText($this->params->firma['waehrung3'], 1);
            }

            // 4, Währung (39)
            if ($this->params->firma['check_w4'] == 'y') {
               $zeile[$i][0] = '';
               $zeile[13 + $i++][0] = number_format(($gsumme * (float)$this->params->firma['kurs4']), 2, ',', '.').' '.Helper::waehrungText($this->params->firma['waehrung4'], 1);
            }
         }

         // Zinsen Ratenkauf
         if ($zinsen != '') {
            // $zeile[$i][0] = $this->text->get('artikel', 'gutschrift', $this->lang);
            $zeile[$i][0] = 'zusätzliche Kosten Ratenkauf';
            $zeile[$i][1] = 'B';
            $zeile[13 + $i][0] = $zinsen.$this->waehrung;
            $zeile[13 + $i][1] = 'B';
            $i++;
         }
      }

      // Mitteilungen
      if ($check != 'check') {
         // Anmerkungen Kunde / Admin
         $this->pdf->SetY($ypos);

         // Bei Bestellung Mitteilung Kunde
         if ($this->pdfart == 'b') {
            $msg = Helper::truncate($this->details->msg_kunde, 300);
         }

         // Sonst Mitteilung von Admin
         else {
            $msg = Helper::truncate($this->details->msg_admin, 300);
         }

         // Nachricht
         $this->pdf->MultiCell(89, 5, $msg, 0, 'L', 0);

         $this->pdf->SetY($ypos);
      }

      else {
         if ($this->pdfart == 'b') {
            $msg = Helper::truncate($this->details->msg_kunde, 300);
         }

         // Sonst Mitteilung von Admin
         else {
            $msg = Helper::truncate($this->details->msg_admin, 300);
         }

         // Nachricht
         $txt_height = ceil($this->pdf->getStringHeight(89, $msg));
      }

      if ($check == 'check') {
         for ($i = 1; $i <= 13; $i++) {
            if ($zeile[$i][0] == '' && $zeile[$i + 13][0] == '' && $zeile[$i + 26][0] == '' && $zeile[$i + 39][0] == '') {
               continue;
            }

            if ($zeile[$i + 39][0] == 'strich') {
               $zeilen += 1;
            }

            else {
               $zeilen += $cell_height;
            }
         }

         // Letzte Zeile V
         $zahlart = (int)$this->details->zahlungsart;

         if ($this->pdfart != 'l') {
            //$zeilen += $cell_height;
         }
      }

      else {
         // Summen in PDF einsetzen
         for ($i = 1; $i <= 13; $i++) {
            if ($zeile[$i][0] == '' && $zeile[$i + 13][0] == '' && $zeile[$i + 26][0] == '' && $zeile[$i + 39][0] == '') {
               continue;
            }

            if ($zeile[$i + 39][0] == 'strich') {
               $this->strich($this->pdf->GetY(), 0);
            }

            else {
               $this->pdf->Cell(52, $cell_height, $zeile[$i][0], 0, 0, '', 0);
               $this->pdf->Cell(55, $cell_height, $zeile[$i + 13][0], 0, 0, '', 0);

               // Spalte 3 Bold?
               if ($zeile[$i + 26][1] == 'B') {
                  $this->pdf->setfont($this->font2, FONTBOLD, 10);
                  $this->pdf->Cell(38, $cell_height, $zeile[$i + 26][0], 0, 0, 'R', 0);
                  $this->pdf->setfont($this->font1, '', 10);
               }
               else {
                  $this->pdf->Cell(38, $cell_height, $zeile[$i + 26][0], 0, 0, 'R', 0);
               }

               // Spalte 4 Bold?
               if ($zeile[$i + 39][1] == 'B') {
                  $this->pdf->setfont($this->font2, FONTBOLD, 10);
                  $this->pdf->Cell(24, $cell_height, $zeile[$i + 39][0], 0, 1, 'R', 0);
                  $this->pdf->setfont($this->font1, '', 10);
               }
               else {
                  $this->pdf->Cell(24, $cell_height, $zeile[$i + 39][0], 0, 1, 'R');
               }
            }
         }
      }

      // Zahlart Text bei Rechnung
      if ($this->pdfart == 'r' && file_exists(ADMIN_PATH.'/zahlart.json')) {
         $za_text = json_decode(file_get_contents(ADMIN_PATH.'/zahlart.json'));

         if (isset($za_text->{'za'.(int)$this->details->zahlungsart.'_'.$this->lang}) && $za_text->{'za'.(int)$this->details->zahlungsart.'_'.$this->lang} != '') {
            $zahlart_text = $za_text->{'za'.(int)$this->details->zahlungsart.'_'.$this->lang};

            if ((int)$this->details->zahlungsart == 1) {
               $zahlart_text = $za_text->{'za_re'.(int)$this->details->zahlungsart.'_'.$this->lang};
            }

            if ($this->details->zahlungsinfo1 != '') {
               $zahlart_text .= ' '.$this->details->zahlungsinfo1;
            }

            else if ($this->details->zahlungsinfo2 != '') {
               $zahlart_text .= ' '.$this->details->zahlungsinfo2;
            }

            if ($check == 'check') {
               $zeilen += $cell_height;
            }

            else {
               $this->pdf->Cell(0, $cell_height, $zahlart_text, 0, 1, 'C', 0);
            }
         }
      }

      // Text für Kleingewerbe
      if ($this->pdfart != 'l') {
         $zahlart = (int)$this->details->zahlungsart;

         if ($this->details->gewerbe == 3 || $zahlart == 1 || $zahlart == 6) {
            // Leerzeile
            if ($check == 'check') {
//               $zeilen += $cell_height;
            }

            else {
//               $this->pdf->Cell(0, $cell_height, '', 0, 1, 'C', 0);
            }
         }

         if ($this->details->gewerbe == 3) {
            if ($check == 'check') {
               $zeilen += $cell_height;
            }

            else {
               $this->pdf->Cell(0, $cell_height, $this->text->get('mail', 'preis_kleing', $this->lang), 0, 1, 'C', 0);
            }
         }

         // Verwendungszweck
         if ($zahlart == 1 || $zahlart == 6) {
            if ($check == 'check') {
//               $zeilen += $cell_height;
            }

            else {
//               $this->pdf->Cell(0, $cell_height,  $this->text->get('pdf', 'verwzweck', $this->lang).': '.$this->details->bestellnummer, 0, 1, 'C', 0);
            }
         }
      }

      return max($zeilen, $txt_height);
   }

   // horizontale Linie Zeichnen
   function strich($y, $offset) {
      $this->pdf->Line(190 - 24, $y + $offset, 190, $y + $offset);
   }

}