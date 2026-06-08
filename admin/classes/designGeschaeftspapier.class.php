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

require_once 'classes/design.class.php';
class KANPAICLASSIC_designGeschaeftspapier extends KANPAICLASSIC_design
{
   private $rechnung = '';

   function __construct() {
      parent::__construct();
   }

   // Definitionen Anzeigetext, color/Background-color, Defaultwerte
   public function getContent() {
      // Update - CSS-Colors schreiben, Namen Bilder/Flash ändern, Größe ermitteln und in CSS-Banner
      // Rechnungsfuss/-kopf hochgeladen
      if ($this->params->func == 'fileUpload') {
         $image = $this->_fileUpload();
         $this->makeWiderruf();

         exit(json_encode(['status' => 'ok', 'html' => $image.'?'.time(), 'target' => 'img_src']));
      }

      elseif ($this->params->func == 'papierDelete') {
         $image    = $this->params->postString('image');
         $picture = ADMIN_URL.'/img/nopic.png?'.time();

         if ($image == 'rechnungsfuss') {
            $picture = TEMPLATE_URL.'/images/'.$image.'_'.$this->params->selected_lang.'.jpg?'.time();
         }

         $this->deleteImg($image);
         $this->_checkFooter($this->params->selected_lang);
         $this->makeWiderruf($this->params->selected_lang);

         exit(json_encode(['status' => 'ok', 'html' => $picture]));
      }

      //
      elseif ($this->params->func == 'papierSave') {
         $this->_rechnungSave();

         // Wenn keine Footergrafik
         if ($this->_checkFooter()) {
            $this->makeWiderruf();
         }

         header('Location: '.ADMIN_URL_IDX.'/designGeschaeftspapier');
         exit;
      }

      // Seite ausgeben
      $this->_getReNr();
      include ADMIN_PATH.'/templates/designGeschaeftspapier.tpl.php';
      return;
   }

   private function _getReNr() {
      $this->rechnung = $this->db->querySingleValue("SELECT rechnung FROM #__nummern WHERE id = 1");
   }

   private function _rechnungSave() {
      $rechnung =  $this->params->postInt('rechnung');

      if (defined('CONF_MODULE_PORTAL')) {
         $sql = "UPDATE #__nummern SET rechnung = $rechnung";
      }
      else {
         $sql = "UPDATE #__nummern SET rechnung = $rechnung WHERE id = 1";
      }

      $this->db->query($sql);

      echo json_encode(['status' => 'ok']);
      exit;
   }

   private function makeWiderruf() {
      $pdf = Control::getPdfWiderruf();

      // Widerruf-PDFs neu erstellen
      foreach ($this->params->langs as $lang) {
         for ($i = 1; $i < 5; $i++) {
            $text = $this->db->querySingleValue("SELECT text FROM #__seiten WHERE art = 'widerruf$i' AND lang = '$lang'");

            if ($text && $text != '') {
               $pdf->makePdf('Widerruf'.chr(64 + $i), $text, $lang, 1);
            }
         }
      }
   }

   private function _checkFooter($lang) {
      if (!file_exists(TEMPLATE_PATH.'/images/rechnungsfuss_'.$lang.'.jpg')) {
         Helper::makeRechnungsfussShop($lang);

         return true;
      }

      return false;
   }
}
