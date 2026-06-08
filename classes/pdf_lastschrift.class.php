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

define ('FONT_NORMAL', 10);
define ('FONT_SMALL', 9);
define ('FONTBOLD', '');

if (!defined('CONF_PDF_FONT1')) {
   define ('CONF_PDF_FONT1', 'firasanslight');    // Regular
   define ('CONF_PDF_FONT2', 'firasansmedium');   // Bold
}

class PDF extends \TCPDF
{
   private $head_img;
   private $foot_img;

   function init($head_img, $foot_img) {
      $this->head_img = $head_img;
      $this->foot_img = $foot_img;
//      parent::__construct($orientation, $unit, $format);
   }

   function header() {
      if (file_exists($this->head_img)) {
         $this->Image($this->head_img, 0, 0, 210);
      }
   }

   function footer() {
      if (file_exists($this->foot_img)) {
         $this->Image($this->foot_img, 0, 265.6, 210);
      }
      $this->SetY(-10);
      $this->SetFont(CONF_PDF_FONT1,'I',8);
      $this->SetTextColor(128);
      $this->Cell(0,10,'Seite '.$this->PageNo(),0,0,'C');
   }
}


class KANPAICLASSIC_PdfLastschrift
{
   private $pdf;
   private $lang;

   public $db;
   public $params;
   public $text;
   private $pdfart = '';
   private $zahlarttext = '';


   function __construct() {
      $this->db = Control::getDB();
      $this->params = Control::getParams();
      $this->text = Control::getText();
      $this->bestellung = Control::getBestellung();
   }

   // $art = 'rechnung', 'bestaetigung'
   function makePdf ($id, $user) {
      $this->lang = 'deu';

      // Ländername aus staat.Id holen
      $sql = "SELECT name FROM #__laender WHERE id = ". $user['staat'];
      $this->db->query($sql);
      $data = $this->db->getObject();
      $land = $data->name;

      $datei = 'Einzugsermaechtigung_'.$this->params->firma['shop_name'].'.pdf';
      $head_img = TEMPLATE_PATH . '/images/rechnungskopf_' . $this->lang . '.jpg';
      $foot_img = TEMPLATE_PATH . '/images/rechnungsfuss_' . $this->lang . '.jpg';

      // Neue PDF, A4, Einheiten in mm
//      $this->pdf = new PdfLastschrift();
      $this->pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
      $this->pdf->init($head_img, $foot_img);

      // Ausgabegroeße 100%, Seitenweise anzeigen
      $this->pdf->SetDisplayMode(100, 'single');

      // Documenttitel, Titel im Acrobat-Reader
      $this->pdf->SetTitle('Einzugsermächtigung');

      // Raender setzen, rechts 25mm, oben 20mm, links 20mm
      //      $this->pdf->SetMargins(25, 51.8, 20);
      $this->pdf->SetMargins(21, 45, 20);
      $posY = $this->pdf->GetY();
      $this->pdf->SetY($posY + 20);
      $this->pdf->SetFont(CONF_PDF_FONT1,'',9);

      $this->pdf->AddPage();

      $txt = "\n\nEINZUGSERMÄCHTIGUNG\n\n";
      $txt .= $this->params->firma['shop_name']."\n";
      $txt .= $this->params->firma['street']."\n";
      $txt .= $this->params->firma['postal_code']." ".$this->params->firma['city']." ".$this->params->firma['country']."\n\n";

      $txt .= "Deutsche Lastschriftermächtigung\n\n";
      $txt .= "Ermächtigung zum Einzug von Forderungen durch Lastschriften\n";
      $txt .= "Bei Fälligkeit der Forderungen wird der Schuldner vier Tage vor dem Einzug der Lastschrift per E-Mail benachrichtigt\n\n";
      $txt .= "Einzugsermächtigung: Hiermit ermächtige(n) ich/wir Sie widerruflich, die von mir/uns zu entrichtenden Zahlungen zu Gunsten von ". $this->params->firma['shop_name'] . " bei Fälligkeit zu Lasten meines/unseres Kontos durch Lastschrift einziehen zu lassen.\n\n";
      $txt .= "Im Falle von umstrittenen Forderungen können Sie die Abbuchungen bei dem kontoführenden Kreditinstitut widerrufen. Der Widerruf kann bis zu sechs Wochen nach der Abbuchung eingereicht werden.\n\n";
      $txt .= "Die Forderungen entstehen durch den Kauf bei ". $this->params->firma['shop_name'] . " .\n\n";
      if ($this->params->user_id > 0) {
         $txt .= "Kundennummer: ".$this->params->user_id."\n";
      }
      else {
         $txt .= "Kundennummer: Gastzugang\n";
      }
      $txt .= "------------------------------------------------------------------------------------------------------------------------------\n";

      $txt .= "Bankverbindung:\n";

      $txt .= "Kontoinhaber: ".$user['bank_inhaber']."\n";
      $txt .= "Anschrift des Kontoinhabers: ".$user['adresse']."\n";
      $txt .= $user['plz']." ".$user['ort']." ".$land."\n\n";

      $txt .= "anderer Kontoinhaber:\n";
      $txt .= "____________ _____________\n";
      $txt .= "__________________________\n";
      $txt .= "__________________________\n\n";

      $txt .= "IBAN: ".$user['bank_iban']."\n";
      $txt .= "BIC: ".$user['bank_bic']."\n";
      $txt .= "Kreditinstitut: ".$user['bank_name']."\n\n";

      $txt .= "Ort, Datum: __________________________________     Unterschrift:_______________\n";

      $txt .= "------------------------------------------------------------------------------------------------------------------------------\n\n";

      $txt .= "Wichtig: Sie können die Einzugsermächtigung per Fax senden. In diesem Fall erklären Sie sich damit einverstanden, dass ".$this->params->firma['shop_name']." die per Fax gesendete Kopie der Einzugsermächtigung wie eine mit der Originalunterschrift versehene zugesendete Kopie für sämtliche Autorisierungszwecke verwendet.\n\n";


      $txt .= "FAX-Nummer: ".$this->params->firma['fax']."\n";

      $this->pdf->Write(4.5, $txt);

      ob_end_clean();
      $this->pdf->Output($datei, 'D');

      return;
   }

   function strich($y) {
      $this->pdf->Line(190 - 24, $y - 3, 190, $y - 3);
   }
}