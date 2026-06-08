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

class PDF extends \TCPDF
{
   function init($orientation = 'P', $unit = 'mm', $format = 'A4') {
      parent::__construct($orientation, $unit, $format);
   }
}


class KANPAICLASSIC_PdfPaket
{
   private $pdf;
   private $params;
   private $haendler_suffix = '';
   private $re_data;
   
   function __construct() {
      $this->params = Control::getParams();

      if (defined('CONF_MODULE_PORTAL') && isset($_SESSION['haendler_id'])) {
         $haendler_suffix = '_'.$_SESSION['haendler_id'];
      }
   }

   public function paketDHL ($re_id) {
      $offset_x     = (int)Helper::getData('print_dhl_left'.$this->haendler_suffix);
      $offset_y     = (int)Helper::getData('print_dhl_top'.$this->haendler_suffix);
      $papersize    = 'A6';
      $orientation  = 'L';

      $margin_left  = 0;
      $margin_top   = 0;
      $margin_right = 0;
      
      $bestellung = Control::getBestellung();
      $bestellung->getDetailBestellung($re_id);
      $this->re_data = $bestellung->dataDetails;

      $this->pdf = new PDF($orientation, 'mm', $papersize, true, 'UTF-8', false);
      $this->pdf->SetCreator('FLOW-Shopsoftware');
      $this->pdf->SetAuthor('FLOW-Shopsoftware');
      $this->pdf->SetTitle('FLOW-Shopsoftware');
      $this->pdf->SetSubject('FLOW-Shopsoftware');
      $this->pdf->SetKeywords('FLOW-Shopsoftware');
      $this->pdf->setPrintHeader(false);
      $this->pdf->setPrintFooter(false);

      // Ausgabegroeße 100%, Seitenweise anzeigen
      $this->pdf->SetDisplayMode(100, 'SinglePage');

      // Documenttitel, Titel im Acrobat-Reader
      $this->pdf->SetTitle('Paketaufkleber DHL '.$this->re_data->bestellnummer);

      // Raender setzen
      $this->pdf->SetMargins($margin_left + $offset_x, $offset_y, $margin_right - $offset_x);
      $this->pdf->SetFont('OpenSans', '', 12);

      $this->pdf->AddPage();
      $x = $this->pdf->GetX() + 2;
      $y = $this->pdf->GetY() + 12;
      
      // Grafik einbinden
//      $this->pdf->startLayer('hidden', false, true, true);
//      $this->pdf->image($this->params->filepath.'/classes/pdf/images/dhl.jpg', 0, 0, 148, 105);
//      $this->pdf->endLayer();


      // Absender
      $this->pdf->SetXY($x + 0, $y + 0);
      $this->pdf->Cell(50, 10, $this->params->firma['shop_name'], 0, 0, 'L', false, 0, 0, true, 'T', 'M');

      $this->pdf->SetXY($x + 0, $y + 7.5);
      $this->pdf->cell(50, 10, $this->params->firma['first_name'].' '.$this->params->firma['last_name'], 0, 0, 'L', false, 0, 0, false, 'T', 'M');

      $this->pdf->SetXY($x + 0, $y + 15);
      $this->pdf->cell(50, 10, $this->params->firma['street'].' '.$this->params->firma['haus_nr'], 0, 0, 'L', false, 0, 0, true, 'T', 'M');

      $this->pdf->SetXY($x + 0, $y + 22.5);
      $this->pdf->cell(18, 10, $this->params->firma['postal_code'], 0, 0, 'L', false, 0, 0, true, 'T', 'M');
      $this->pdf->SetXY($x + 18, $y + 22.5);
      $this->pdf->cell(32, 10, $this->params->firma['city'], 0, 0, 'L', false, 0, 0, true, 'T', 'M');
      
      // Empfänger
      $data = $this->_empfaenger();
      $x += 71;
      
      $this->pdf->SetXY($x, $y + 0);
      $this->pdf->cell(70, 10, $data['dhl1'], 0, 0, 'L', false, 0, 0, true, 'T', 'M');

      $this->pdf->SetXY($x, $y + 7.5);
      $this->pdf->cell(35, 10, $data['dhl2'], 0, 0, 'L', false, 0, 0, true, 'T', 'M');
      $this->pdf->SetXY($x + 35, $y + 7.5);
      $this->pdf->cell(35, 10, $data['telefon'], 0, 0, 'L', false, 0, 0, true, 'T', 'M');

      $this->pdf->SetXY($x, $y + 15);
      $this->pdf->cell(70, 10, $data['adresse'], 0, 0, 'L', false, 0, 0, true, 'T', 'M');

      $this->pdf->SetXY($x, $y + 22.5);
      $this->pdf->cell(24, 10, $data['plz'], 0, 0, 'L', false, 0, 0, true, 'T', 'M');
      $this->pdf->SetXY($x + 24, $y + 22.5);
      $this->pdf->cell(50, 10, $data['ort'], 0, 0, 'L', false, 0, 0, true, 'T', 'M');
      
      $this->pdf->SetXY($x, $y + 30);
      $this->pdf->cell(70, 10, $data['land'], 0, 0, 'L', false, 0, 0, true, 'T', 'M');
      

      ob_end_clean();
      $this->pdf->Output('Paketaufkleber_DHL_'.$this->re_data->bestellnummer.'.pdf', 'D');
      return;
   }

   public function paketHermes ($re_id) {
      $offset_x    = (int)Helper::getData('print_hermes_left'.$this->haendler_suffix);
      $offset_y    = (int)Helper::getData('print_hermes_top'.$this->haendler_suffix);
      $papersize   = 'HERMES';
      $orientation = 'L';

      $margin_left  = 59;
      $margin_top   = 8;
      
      $bestellung = Control::getBestellung();
      $bestellung->getDetailBestellung($re_id);
      $this->re_data = $bestellung->dataDetails;

      $this->pdf = new PDF($orientation, 'mm', $papersize, true, 'UTF-8', false);
      $this->pdf->SetCreator('FLOW-Shopsoftware');
      $this->pdf->SetAuthor('FLOW-Shopsoftware');
      $this->pdf->SetTitle('FLOW-Shopsoftware');
      $this->pdf->SetSubject('FLOW-Shopsoftware');
      $this->pdf->SetKeywords('FLOW-Shopsoftware');
      $this->pdf->setPrintHeader(false);
      $this->pdf->setPrintFooter(false);
      $this->pdf->setPageOrientation($orientation, false, 0);
      
      // Ausgabegroeße 100%, Seitenweise anzeigen
      $this->pdf->SetDisplayMode(100, 'SinglePage');

      // Documenttitel, Titel im Acrobat-Reader
      $this->pdf->SetTitle('Paketaufkleber Hermes '.$this->re_data->bestellnummer);

      // Raender setzen
      $this->pdf->SetMargins($margin_left + $offset_x, $margin_top + $offset_y);
      $this->pdf->setfont('RobotoMono', '', 12);
      $this->pdf->AddPage();
      $x = $this->pdf->GetX();
      $y = $this->pdf->GetY();
      $hoehe  = 8.55;
      $breite = 4.236;
      $spacing = $breite - $this->pdf->getCharWidth(64);
      $this->pdf->setFontSpacing($spacing);
      // $this->pdf->startLayer('hidden', false, true, true);
      // $this->pdf->image($this->params->filepath.'/classes/pdf/images/hermes.jpg', 3, 0, 169, 100);
      // $this->pdf->endLayer();
      
      // Empfänger
      // Name, Vorname
      $data = $this->_empfaenger();
      $this->pdf->SetXY($x, $y + 0 * $hoehe);
      $this->pdf->cell(110, 8, $data['name_vor'], 0, 0, 'L', false, 0, 0, true, 'T', 'M');

      // Adresse
      $this->pdf->SetXY($x, $y + 1 * $hoehe);
      $this->pdf->cell(110, 8, $data['adresse'], 0, 0, 'L', false, 0, 0, true, 'T', 'M');

      // PLZ Ort
      $this->pdf->SetXY($x, $y + 3 * $hoehe);
      $this->pdf->cell(34, 8, $data['plz'], 0, 0, 'L', false, 0, 0, true, 'T', 'M');
      $this->pdf->SetXY($x + 38, $y + 3 * $hoehe);
      $this->pdf->cell(72, 8, $data['ort'], 0, 0, 'L', false, 0, 0, true, 'T', 'M');

      // Land
      $this->pdf->SetXY($x, $y + 4 * $hoehe);
      $this->pdf->cell(110, 8, $data['land'], 0, 0, 'L', false, 0, 0, true, 'T', 'M');
      
      // Absender
      $y += 54;
      $hoehe  = 9.667;

      $this->pdf->SetXY($x, $y + 0 * $hoehe);
      $this->pdf->Cell(26, 8, date('dmy'), 0, 0, 'L', false, 0, 0, true, 'T', 'M');
      $this->pdf->SetXY($x + 30, $y + 0 * $hoehe);
      $this->pdf->Cell(80, 8, $this->params->firma['telefon'], 0, 0, 'L', false, 0, 0, true, 'T', 'M');

      $this->pdf->SetXY($x, $y + 1 * $hoehe);
      $this->pdf->cell(110, 10, $this->params->firma['first_name'].' '.$this->params->firma['last_name'], 0, 0, 'L', false, 0, 0, true, 'T', 'M');

      $this->pdf->SetXY($x, $y + 2 * $hoehe);
      $this->pdf->cell(110, 10, $this->params->firma['street'].' '.$this->params->firma['haus_nr'], 0, 0, 'L', false, 0, 0, true, 'T', 'M');

      $this->pdf->SetXY($x, $y + 3 * $hoehe);
      $this->pdf->cell(110, 10, $this->params->firma['postal_code'], 0, 0, 'L', false, 0, 0, true, 'T', 'M');
      $this->pdf->SetXY($x + 38, $y + 3 * $hoehe);
      $this->pdf->cell(110, 10, $this->params->firma['city'], 0, 0, 'L', false, 0, 0, true, 'T', 'M');

      // Ausgabe
      ob_end_clean();
      $this->pdf->Output('Paketaufkleber_Hermes_'.$this->re_data->bestellnummer.'.pdf', 'D');
      return;
   }

   public function paketDPD ($re_id) {
      $offset_x    = (int)Helper::getData('print_dpd_left'.$this->haendler_suffix);
      $offset_y    = (int)Helper::getData('print_dpd_top'.$this->haendler_suffix);
      $papersize   = 'DPD';
      $orientation = 'L';
      $klasse      = Helper::getData('print_dpd_klasse'.$this->haendler_suffix);
      $land        = Helper::getData('print_dpd_land'.$this->haendler_suffix);

      $margin_left  = 0;
      $margin_top   = 0;

      $bestellung = Control::getBestellung();
      $bestellung->getDetailBestellung($re_id);
      $this->re_data = $bestellung->dataDetails;

      $this->pdf = new PDF($orientation, 'mm', $papersize, true, 'UTF-8', false);
      $this->pdf->SetCreator('FLOW-Shopsoftware');
      $this->pdf->SetAuthor('FLOW-Shopsoftware');
      $this->pdf->SetTitle('FLOW-Shopsoftware');
      $this->pdf->SetSubject('FLOW-Shopsoftware');
      $this->pdf->SetKeywords('FLOW-Shopsoftware');
      $this->pdf->setPrintHeader(false);
      $this->pdf->setPrintFooter(false);
      $this->pdf->setPageOrientation($orientation, false, 0);

      // Ausgabegroeße 100%, Seitenweise anzeigen
      $this->pdf->SetDisplayMode(100, 'SinglePage');

      // Documenttitel, Titel im Acrobat-Reader
      $this->pdf->SetTitle('Paketaufkleber DPD '.$this->re_data->bestellnummer);

      // Raender setzen
      $this->pdf->SetMargins($margin_left, $margin_top);
      $this->pdf->setfont('Roboto', '', 12);
      $this->pdf->AddPage();
      $x = $this->pdf->GetX() + 64 + $offset_x;
      $y = $this->pdf->GetY() + 20 + $offset_y;
      
      // Empfänger
      $data = $this->_empfaenger();
      
      // Firma
      $this->pdf->SetXY($x, $y + 0.5);
      $this->pdf->cell(70, 10, $data['firma'], 0, 0, 'L', false, 0, 0, true, 'T', 'M');

      //Name ( Telefon)
      $this->pdf->SetXY($x, $y + 9.5);
      $this->pdf->cell(35, 10, $data['vor_name'], 0, 0, 'L', false, 0, 0, true, 'T', 'M');
      $this->pdf->SetXY($x + 78, $y + 9.5);
      $this->pdf->cell(35, 10, $data['telefon'], 0, 0, 'L', false, 0, 0, true, 'T', 'M');

      // Adresse
      $this->pdf->SetXY($x, $y + 18.5);
      $this->pdf->cell(70, 10, $data['adresse'], 0, 0, 'L', false, 0, 0, true, 'T', 'M');

      // Land / PLZ / Ort
      $this->pdf->setfont('RobotoMono', '', 12);
      $breite = 4.0;
      $spacing = $breite - $this->pdf->getCharWidth(64);
      $this->pdf->setFontSpacing($spacing);
      $this->pdf->SetXY($x, $y + 27);
      $this->pdf->cell(8, 10, $land, 0, 0, 'L', false, 0, 0, true, 'T', 'M');
      $this->pdf->SetXY($x + 10, $y + 27);
      $this->pdf->cell(23, 10, $data['plz'], 0, 0, 'L', false, 0, 0, true, 'T', 'M');
      $this->pdf->setfont('Roboto', '', 12);
      $this->pdf->setFontSpacing(0);
      $this->pdf->SetXY($x + 35, $y + 27);
      $this->pdf->cell(100, 10, $data['ort'], 0, 0, 'L', false, 0, 0, true, 'T', 'M');
      
      // Paketklasse
      if ($klasse == 'S' || $klasse == 'M') {
         $this->pdf->SetXY($x + ($klasse == 'M' ? 12 : 2), $y + 51);
         $this->pdf->cell(10, 10, 'X', 0, 0, 'L', false, 0, 0, true, 'T', 'M');
      }

      if ($klasse == 'L' || $klasse == 'XL') {
         $this->pdf->SetXY($x + ($klasse == 'XL' ? 12 : 22), $y + 57);
         $this->pdf->cell(10, 10, 'X', 0, 0, 'L', false, 0, 0, true, 'T', 'M');
      }

      // Absender
      $y += 46;
      $x += 30;
      
      // Firma / Name
      $this->pdf->SetXY($x + 0, $y + 0);
      $this->pdf->Cell(50, 10, $this->params->firma['shop_name'].' '.$this->params->firma['first_name'].' '.$this->params->firma['last_name'], 0, 0, 'L', false, 0, 0, true, 'T', 'M');

      // Adressse
      $this->pdf->SetXY($x + 0, $y + 6.67);
      $this->pdf->cell(50, 10, $this->params->firma['street'].' '.$this->params->firma['haus_nr'], 0, 0, 'L', false, 0, 0, true, 'T', 'M');

      // PLZ / Ort
      $this->pdf->SetXY($x + 0, $y + 13.33);
      $this->pdf->cell(18, 10, $this->params->firma['postal_code'], 0, 0, 'L', false, 0, 0, true, 'T', 'M');
      $this->pdf->SetXY($x + 26, $y + 14);
      $this->pdf->cell(32, 10, $this->params->firma['city'], 0, 0, 'L', false, 0, 0, true, 'T', 'M');

      // Telefon
      $this->pdf->SetXY($x + 0, $y + 20);
      $this->pdf->cell(18, 10, $this->params->firma['telefon'], 0, 0, 'L', false, 0, 0, true, 'T', 'M');
      
      ob_end_clean();
      $this->pdf->Output('Paketaufkleber_DPD_'.$this->re_data->bestellnummer.'.pdf', 'D');
      return;
   }

   public function paketGLS ($re_id) {
      $offset_x  = (int)Helper::getData('print_gls_left'.$this->haendler_suffix);
      $offset_y  = (int)Helper::getData('print_gls_top'.$this->haendler_suffix);

      $papersize = 'GLS';
      $orientation  = 'P';
      $margin_left  = 0;
      $margin_top   = 0;
      $margin_right = 0;

      $bestellung = Control::getBestellung();
      $bestellung->getDetailBestellung($re_id);
      $this->re_data = $bestellung->dataDetails;

      $this->pdf = new PDF($orientation, 'mm', $papersize, true, 'UTF-8', false);
      $this->pdf->SetCreator('FLOW-Shopsoftware');
      $this->pdf->SetAuthor('FLOW-Shopsoftware');
      $this->pdf->SetTitle('FLOW-Shopsoftware');
      $this->pdf->SetSubject('FLOW-Shopsoftware');
      $this->pdf->SetKeywords('FLOW-Shopsoftware');
      $this->pdf->setPrintHeader(false);
      $this->pdf->setPrintFooter(false);

      // Ausgabegroeße 100%, Seitenweise anzeigen
      $this->pdf->SetDisplayMode(100, 'SinglePage');

      // Documenttitel, Titel im Acrobat-Reader
      $this->pdf->SetTitle('Paketaufkleber DHL '.$this->re_data->bestellnummer);

      // Raender setzen, rechts 25mm, oben 20mm, links 20mm
      $this->pdf->SetMargins($margin_left, $margin_top);
      $this->pdf->setfont('OpenSans', '', 12);
      $this->pdf->AddPage();
      $x = $this->pdf->GetX() + 16 + $offset_x;
      $y = $this->pdf->GetY() + 42 + $offset_y;
      

      // Absender
      $this->pdf->SetXY($x + 0, $y + 0);
      $this->pdf->Cell(50, 10, $this->params->firma['shop_name'], 0, 0, 'L', false, 0, 0, true, 'T', 'M');

      $this->pdf->SetXY($x + 0, $y + 6);
      $this->pdf->cell(50, 10, $this->params->firma['first_name'].' '.$this->params->firma['last_name'], 0, 0, 'L', false, 0, 0, false, 'T', 'M');

      $this->pdf->SetXY($x + 0, $y + 12);
      $this->pdf->cell(50, 10, $this->params->firma['street'].' '.$this->params->firma['haus_nr'], 0, 0, 'L', false, 0, 0, true, 'T', 'M');

      $this->pdf->SetXY($x + 0, $y + 18);
      $this->pdf->cell(18, 10, $this->params->firma['postal_code'].' '.$this->params->firma['city'], 0, 0, 'L', false, 0, 0, true, 'T', 'M');

      $this->pdf->SetXY($x + 15, $y + 35);
      $this->pdf->cell(32, 10, $this->params->firma['telefon'], 0, 0, 'L', false, 0, 0, true, 'T', 'M');
      
      $this->pdf->SetXY($x, $y + 50);
      $this->pdf->cell(32, 10, Helper::getData('print_gls_inhalt'.$this->haendler_suffix), 0, 0, 'L', false, 0, 0, true, 'T', 'M');
      
      // Empfänger
      $data = $this->_empfaenger();
      $x += 68;
      
      $this->pdf->SetXY($x, $y + 0);
      $this->pdf->cell(70, 10, ($data['firma'] != '' ? $data['firma'] : $data['anrede']), 0, 0, 'L', false, 0, 0, true, 'T', 'M');

      $this->pdf->SetXY($x, $y + 6);
      $this->pdf->cell(70, 10, $data['vor_name'], 0, 0, 'L', false, 0, 0, true, 'T', 'M');

      $this->pdf->SetXY($x, $y + 12);
      $this->pdf->cell(70, 10, $data['plz'].' '.$data['ort'], 0, 0, 'L', false, 0, 0, true, 'T', 'M');

      $this->pdf->SetXY($x, $y + 18);
      $this->pdf->cell(24, 10, $data['land'], 0, 0, 'L', false, 0, 0, true, 'T', 'M');
      
      $this->pdf->SetXY($x + 15, $y + 35);
      $this->pdf->cell(24, 10, $data['telefon'], 0, 0, 'L', false, 0, 0, true, 'T', 'M');
      
      $this->pdf->SetXY($x + 12, $y + 50);
      $this->pdf->cell(24, 10, Helper::getData('print_dpd_klasse'.$this->haendler_suffix), 0, 0, 'L', false, 0, 0, true, 'T', 'M');
      
      ob_end_clean();
      $this->pdf->Output('Paketaufkleber_GLS_'.$this->re_data->bestellnummer.'.pdf', 'D');
      return;
   }

   public function etikett ($re_arr) {
      $offset_x    = (int)Helper::getData('print_etikett_left'.$this->haendler_suffix);
      $offset_y    = (int)Helper::getData('print_etikett_top'.$this->haendler_suffix);
      $etikett_x   = (int)Helper::getData('print_etikett_x'.$this->haendler_suffix);
      $etikett_y   = (int)Helper::getData('print_etikett_y'.$this->haendler_suffix);
      $spalten     = (int)Helper::getData('print_etikett_spalten'.$this->haendler_suffix);
      $zeilen      = (int)Helper::getData('print_etikett_zeilen'.$this->haendler_suffix);
      $spalten_off = (int)Helper::getData('print_etikett_offsetx'.$this->haendler_suffix);
      $zeilen_off  = (int)Helper::getData('print_etikett_offsety'.$this->haendler_suffix);
      $dirup       = Helper::getData('print_etikett_dirup'.$this->haendler_suffix);
      $papersize   = 'A4';
      $orientation = 'P';

      $margin_left = 0;
      $margin_top  = 0;
      $bestellung  = Control::getBestellung();

      $this->pdf = new PDF($orientation, 'mm', $papersize, true, 'UTF-8', false);
      $this->pdf->SetCreator('FLOW-Shopsoftware');
      $this->pdf->SetAuthor('FLOW-Shopsoftware');
      $this->pdf->SetTitle('FLOW-Shopsoftware');
      $this->pdf->SetSubject('FLOW-Shopsoftware');
      $this->pdf->SetKeywords('FLOW-Shopsoftware');
      $this->pdf->setPrintHeader(false);
      $this->pdf->setPrintFooter(false);
      $this->pdf->setPageOrientation($orientation, false, 0);

      // Ausgabegroeße 100%, Seitenweise anzeigen
      $this->pdf->SetDisplayMode(100, 'SinglePage');

      // Documenttitel, Titel im Acrobat-Reader
      $this->pdf->SetTitle('Adressaufkleber');

      // Raender setzen
      $this->pdf->SetMargins($margin_left, $margin_top);
      $this->pdf->AddPage();
      $x = $this->pdf->GetX() + 10 + $offset_x;
      $y = $this->pdf->GetY() + 20 + $offset_y;
      $anzahl = $zeilen * $spalten;

      $start  = $spalten * ($etikett_y - 1) + $etikett_x;
      $hoehe  = round((297 - 40 - ($zeilen - 1) * $zeilen_off) / $zeilen);
      $breite = round((210 - 16 - ($spalten - 1) * $spalten_off) / $spalten);

      $pos_x = $etikett_x;
      $pos_y = $etikett_y;
      $printed = 0;
      
      for ($i = 0; $i < count($re_arr); $i++) {
         if ((int)$re_arr[$i] > 0) {
            $bestellung->getDetailBestellung($re_arr[$i]);
            $this->re_data = $bestellung->dataDetails;
            $data = $this->_empfaenger();

            // von oben nach unten
            if ($dirup == 'y') {
               if ($start == $anzahl) {
                  $this->pdf->AddPage();
                  $start = 1;
                  $pos_x = 1;
                  $pos_y = 1;
               }
               else {
                  $pos_x++;
                  
                  if ($pos_x > $spalten) {
                     $pos_y++;
                     $pos_x = 1;
                  }
               }

               $akt_x = $x + ($pos_x - 1) * ($spalten_off + $breite);
               $akt_y = $y + ($pos_y - 1) * ($zeilen_off + $hoehe);
               $start++;
            }

            // von unten nach oben drucken
            else {
               if ($start == 0) {
                  $this->pdf->AddPage();
                  $start = $anzahl;
                  $pos_x = $spalten;
                  $pos_y = $zeilen;
               }

               else {
                  if ($pos_x == 0) {
                     $pos_y--;
                     $pos_x = $spalten;
                  }
               }

               $akt_x = $x + ($pos_x - 1) * ($spalten_off + $breite);
               $akt_y = $y + ($pos_y - 1) * ($zeilen_off + $hoehe);
               $pos_x--;
               $start--;
            }
            
            // Absender
            $this->pdf->setfont('OpenSans', 'U', 8);
            $this->pdf->SetXY($akt_x + 0, $akt_y);
            $this->pdf->Cell($breite, 10, $this->params->firma['shop_name'].', '.$this->params->firma['street'].' '.$this->params->firma['haus_nr'].', '.$this->params->firma['postal_code'].' '.$this->params->firma['city'], 0, 0, 'L', false, 0, 0, true, 'T', 'M');

            // Adresse
            $this->pdf->setfont('OpenSans', '', 10);
            $abstand = ($hoehe - 10) / 6;
            $this->pdf->SetXY($akt_x, $akt_y + $abstand);
            $this->pdf->cell(70, 10, ($data['firma'] != '' ? $data['firma'] : $data['anrede']), 0, 0, 'L', false, 0, 0, true, 'T', 'M');
      
            $this->pdf->SetXY($akt_x, $akt_y + 2 * $abstand);
            $this->pdf->cell(70, 10, $data['vor_name'], 0, 0, 'L', false, 0, 0, true, 'T', 'M');
      
            $this->pdf->SetXY($akt_x, $akt_y + 3 * $abstand);
            $this->pdf->cell(70, 10, $data['adresse'], 0, 0, 'L', false, 0, 0, true, 'T', 'M');
      
            $this->pdf->SetXY($akt_x, $akt_y + 4 * $abstand);
            $this->pdf->cell(70, 10, $data['plz'].' '.$data['ort'], 0, 0, 'L', false, 0, 0, true, 'T', 'M');
      
            $this->pdf->SetXY($akt_x, $akt_y + 5 * $abstand);
            $this->pdf->cell(24, 10, $data['land'], 0, 0, 'L', false, 0, 0, true, 'T', 'M');
            
            $printed++;
         }
      }

      ob_end_clean();
      $this->pdf->Output('Adressaufkleber.pdf', 'D');
      return;
   }

   
   private function _empfaenger() {
      $data = [
                    'anrede'    => '',
                    'firma'     => '',
                    'vor_name'  => '',
                    'name_vor'  => '',
                    'adresse'   => '',
                    'plz'       => '',
                    'ort'       => '',
                    'land'      => '',
                    'land_kurz' => '',
                    'telefon'   => '',
                    'dhl1'      => '',
                    'dhl2'      => '',
                    ];

      if ($this->re_data->lieferadresse == 'y') {
         if ($this->re_data->lf_firma == '') {
            $data['dhl1'] = $this->re_data->lf_anrede == 'herr' ? 'Herr' : 'Frau';
            $data['dhl2'] = $this->re_data->lf_vorname.' '.$this->re_data->lf_nachname;
         }
         
         else {
            $data['dhl1'] = $this->re_data->lf_firma;
            $data['dhl2'] = $this->re_data->lf_anrede == 'herr' ? 'Herr ' : 'Frau '.$this->re_data->lf_nachname;
         }
         
         $data['anrede']    = $this->re_data->lf_anrede == 'herr' ? 'Herr' : 'Frau';
         $data['firma']     = $this->re_data->lf_firma;
         $data['name_vor']  = $this->re_data->lf_nachname.', '.$this->re_data->lf_vorname;
         $data['vor_name']  = $this->re_data->lf_vorname.' '.$this->re_data->lf_nachname;
         $data['adresse']   = $this->re_data->lf_adresse.' '.$this->re_data->lf_hausnr;
         $data['plz']       = $this->re_data->lf_plz;
         $data['ort']       = $this->re_data->lf_ort;
         $data['land']      = Helper::getStaatName($this->re_data->lf_staat, $this->re_data->lf_staat2);
         $data['telefon']   = $this->re_data->telefon;
      }
      
      else {
         if ($this->re_data->firma == '') {
            $data['dhl1'] = $this->re_data->anrede == 'herr' ? 'Herr' : 'Frau';
            $data['dhl2'] = $this->re_data->vorname.' '.$this->re_data->nachname;
         }
         
         else {
            $data['dhl1'] = $this->re_data->firma;
            $data['dhl2'] = $this->re_data->anrede == 'herr' ? 'Herr ' : 'Frau '.$this->re_data->nachname;
         }
         
         $data['anrede']    = $this->re_data->anrede == 'herr' ? 'Herr' : 'Frau';
         $data['firma']     = $this->re_data->firma;
         $data['name_vor']  = $this->re_data->nachname.', '.$this->re_data->vorname;
         $data['vor_name']  = $this->re_data->vorname.' '.$this->re_data->nachname;
         $data['adresse']   = $this->re_data->adresse.' '.$this->re_data->hausnr;
         $data['plz']       = $this->re_data->plz;
         $data['ort']       = $this->re_data->ort;
         $data['land']      = Helper::getStaatName($this->re_data->staat, $this->re_data->staat2);
         $data['telefon']   = $this->re_data->telefon;
      }
      
      return $data;
   }
}
?>
