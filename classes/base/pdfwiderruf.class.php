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

// 21.07.2018: Footer-Grafik deaktiviert

namespace KANPAICLASSIC;

if (!defined('KANPAICLASSIC')) {
   die ("This file cannot run outside the Kanpai Classic Shopsoftware");
}

define ('FONTBOLD', '');

if (!defined('CONF_PDF_FONT1')) {
   define ('CONF_PDF_FONT1', 'firasanslight');    // Regular
   define ('CONF_PDF_FONT2', 'firasansmedium');   // Bold
}

class PDF extends \TCPDF
{
   private $head_img;
   private $foot_img;

   function init($head_img, $foot_img, $orientation = 'P', $unit = 'mm', $format = 'format') {
      $this->head_img = $head_img;
      $this->foot_img = $foot_img;
   }

   function header() {
      // Header nur auf 1. Seite
      if ($this->pageNo() == 1 && file_exists($this->head_img)) {
         $this->Image($this->head_img, 0, 0, 210);
      }
   }

   function footer() {
      // if (file_exists($this->foot_img)) {
      //    $this->Image($this->foot_img, 0, 265.6, 210);
      // }

      $this->SetY(-10);
      $this->SetFont(CONF_PDF_FONT1,'I',8);
      $this->SetTextColor(128);
      $this->Cell(0,10,'Seite '.$this->PageNo(),0,0,'C');
   }
}


class KANPAICLASSIC_pdf
{
   private $pdf;
   private $seite = 1;
   private $lang;

   public $db;
   public $params;
   public $text;

   function __construct() {
      $this->db          = Control::getDB();
      $this->params      = Control::getParams();
      $this->text        = Control::getText();
   }

   function makePdf ($name, $textstring, $lang, $nr, $haendler_id = 0, $h_head_img = '', $h_footer_img = '') {
      $this->lang = $lang;
      $head_img   = TEMPLATE_PATH . '/images/rechnungskopf_' . $lang . '.jpg';
      $foot_img   = '';
      $seite      = 1;

      // PDF in Kundensprache
      $datei = SHOP_PATH.'/classes/pdf/'.$name.'_'.$lang.'.pdf';

      // Neue PDF, A4, Einheiten in mm
      $this->pdf = new PDF('P', 'mm', 'A4', true, 'UTF-8', false);
      $this->pdf->init($head_img, $foot_img, 'P', 'mm', 'A4');

      // Ausgabegroeße 100%, Seitenweise anzeigen
      $this->pdf->SetDisplayMode(100, 'single');
      $this->pdf->setPageOrientation('P', true, 40);

      // Documenttitel, Titel im Acrobat-Reader
      if ($name == 'agb') {
         $this->pdf->SetTitle('AGB');
      }

      else if ($name == 'versand') {
         $this->pdf->SetTitle('Versand');
      }

      else {
         $this->pdf->SetTitle('Widerufsbelehrung');
      }

      $this->pdf->setfont(CONF_PDF_FONT1, '', 10);

      $textstring = str_ireplace(['[AUSKLAPPEN]', '[\AUSKLAPPEN]'], '', $textstring);
      $texte = explode('[NEUESEITE]', $textstring);

      for ($i = 0; $i < count($texte); $i++) {
         // Raender setzen, rechts 25mm, oben 20mm, links 20mm
         $this->pdf->SetMargins(21, ($seite == 1 ? 60 : 10), 20);
         $text1 = $texte[$i];
         $this->pdf->AddPage();
         $seite++;

         $posY  = $this->pdf->GetY();
         $this->pdf->SetY($posY);
         $text2 = '';

         if (strpos($text1, 'class="rechtstexte"') != false) {
            $text2 = str_replace('p dir="ltr"', 'p', $text1);
         }

         else {
            $text2 = str_replace(array('</p>', '<br />', '<br/>'), array("</p>\n", "<br />\n"), $text1);
         }

//var_dump(strpos($text1, 'ltr'));
//var_dump(strpos($text2, 'ltr'));
//var_dump($text);
         //$text  = html_entity_decode(strip_tags($text1), ENT_NOQUOTES, 'UTF-8');
         $text  = html_entity_decode(strip_tags($text2), ENT_NOQUOTES | ENT_HTML5, 'UTF-8');

         if (strstr($text, '<strong>')) {
            // $this->pdf->setfont(CONF_PDF_FONT2, '', 12);
            $this->pdf->Write(5, $text);
            // $this->pdf->setfont(CONF_PDF_FONT1, '', 10);
         }
         else {
            $this->pdf->Write(5, $text);
         }
      }

      $posY = $this->pdf->GetY();
      $this->pdf->SetY($posY + 5);

      $posX1 = $this->pdf->GetX()+1.3;

      // Link zur Widerruf-Formular
      if ($name != 'agb' && $name != 'versand') {
//         $this->pdf->Write(5, $this->text->get('widerruf', 'form', $lang), str_replace('/admin', '', SHOP_URL_IDX.'/widerruf'.$nr));
         $this->pdf->Write(5, $this->text->get('widerruf', 'form', $lang), SHOP_URL_IDX.'/widerruf'.$nr);
      }

      $posX2 = $this->pdf->GetX()+1.3;
      $posY1 = $this->pdf->GetY()+4;
      $this->pdf->Line($posX1, $posY1, $posX2, $posY1);

      $this->pdf->Output($datei, 'F');
      return;
   }

   // horizontale Linie Zeichnen
   function strich($y, $offset) {
      $this->pdf->Line(190 - 24, $y + $offset, 190, $y + $offset);
   }
}
?>
