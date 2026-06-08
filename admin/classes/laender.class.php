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

class KANPAICLASSIC_laender
{
   public $db;
   public $params;
   public $text;

   private $default_lang = '';
   private $select_langs = [];
   private $langs =  ['deu', 'eng'];
   private $waehrung = [];

   private $data1 = [];
   private $data2 = [];
   private $data3 = [];
   private $laender = [];

   function __construct() {
      $this->db = Control::getDB();
      $this->params = Control::getParams();
      $this->text = Control::getText();
   }


   public function getContent() {
      if ($this->params->func == 'update') {
         $this->writeData();
         header('Location: '.ADMIN_URL_IDX.'/laender');
         exit;
      }

      else {
         $this->getData();
      }
      include 'templates/laender.tpl.php';
      return;
   }


   // Länder- / Währungsinformationen lesen
   private function getData() {
      $first = true;
      $lang = '';

      foreach ($this->langs as $language) {
         if ($first) {
            $lang = $language;
            $first = false;
         }

         else {
            $lang .= ", $language";
         }
      }

      $sql = "SELECT  langs, default_lang, check_w2, check_w3, check_w4, kurs2, kurs3, kurs4, waehrung1, waehrung2, waehrung3, waehrung4 FROM #__firma WHERE id = 1";
      $this->db->query($sql);
      $data = $this->db->getObject();

      $this->default_lang = $data->default_lang;
      $tmp = $data->langs;

      $this->select_langs = explode(';', $tmp);

      $this->waehrung['check_w2']  = $data->check_w2;
      $this->waehrung['check_w3']  = $data->check_w3;
      $this->waehrung['check_w4']  = $data->check_w4;
      $this->waehrung['kurs2']     = $data->kurs2;
      $this->waehrung['kurs3']     = $data->kurs3;
      $this->waehrung['kurs4']     = $data->kurs4;
      $this->waehrung['waehrung1'] = $data->waehrung1;
      $this->waehrung['waehrung2'] = $data->waehrung2;
      $this->waehrung['waehrung3'] = $data->waehrung3;
      $this->waehrung['waehrung4'] = $data->waehrung4;

      // Länder auslesen
      $data = [];
      $data0 = [];
      $sql = "SELECT * FROM #__laender ORDER BY sort, id";
      $this->db->query($sql);

      while($temp = $this->db->getObject()) {
         if ($temp->name != $temp->name_shop) {
            $temp->name = Helper::truncate($temp->name . ' (' . $temp->name_shop, 27) . ')';
         }

         $temp->versand = str_replace('.', ',', $temp->versand);

         if ($temp->sort == 0) {
            $data0[] = $temp;
         }

         else {
            $data[] = $temp;
         }
      }

      $data = array_merge($data, $data0);

      // für linke und rechte Spalte aufteilen
      $split = ceil(count($data) / 2);
      for ($i = 0; $i < $split; $i ++) {
         $this->data1[] = $data[$i];

         if (isset($data[$i + $split])) {
            $this->data2[] = $data[$i + $split];
         }
      }

      $this->laender = [
          'deu' => 'Deutsch',
          'eng' => 'Englisch',
          'spa' => 'Spanisch',
          'dan' => 'Dänisch',
          'fin' => 'Finnisch',
          'fra' => 'Französisch',
          'ita' => 'Italienisch',
          'nld' => 'Niederländisch',
          'nor' => 'Norwegisch',
          'por' => 'Portugiesisch',
          'swe' => 'Schwedisch',
          'tue' => 'Türkisch',
          'rus' => 'Russisch',
          'gri' => 'Griechisch',
          'ara' => 'Arabisch'
      ];

      return;
   }


   // Länder- / Währungsinformationen speichern
   private function writeData() {
      $lang_array = $this->params->postArray('lang_check');

      if (array_search($this->params->firma['default_lang'], $lang_array) === false) {
         $lang_array[] = $this->params->firma['default_lang'];
      }

      $langs = '';

      foreach ($this->langs as $l) {
         if (array_search($l, $lang_array) !== false) {
            $langs .= $l.';';
         }
      }

      $langs = rtrim($langs, ';');

      // Währungen prüfen
      $check_w2  = $this->params->postCheckbox('check_w2');
      $check_w3  = $this->params->postCheckbox('check_w3');
      $check_w4  = $this->params->postCheckbox('check_w4');
      $kurs2     = $this->params->postFloat('kurs2');
      $kurs3     = $this->params->postFloat('kurs3');
      $kurs4     = $this->params->postFloat('kurs4');
      $waehrung1 = $this->params->postInt('waehrung1');
      $waehrung2 = $this->params->postInt('waehrung2');
      $waehrung3 = $this->params->postInt('waehrung3');
      $waehrung4 = $this->params->postInt('waehrung4');

      $default_lang = CONF_DEFAULT_LANG;

      $sql = "UPDATE #__firma
                SET langs = '$langs',
                    default_lang = '$default_lang',
                    check_w2 = '$check_w2',
                    check_w3 = '$check_w3',
                    check_w4 = '$check_w4',
                    kurs2    = '$kurs2',
                    kurs3    = '$kurs3',
                    kurs4    = '$kurs4',
                    waehrung1 = $waehrung1,
                    waehrung2 = $waehrung2,
                    waehrung3 = $waehrung3,
                    waehrung4 = $waehrung4
              WHERE id = 1";
      $this->db->query($sql);

      $sort    = $this->params->postArray('land_sort');
      $id      = $this->params->postArray('land_id');
      $versand = $this->params->postArray('land_versand');

      for ($i = 0; $i < count($id); $i++) {
         $sql = "UPDATE #__laender SET
                    sort    = $sort[$i],
                    versand = ".(float)str_replace(',', '.', $versand[$i])."
                 WHERE id = $id[$i]";
         $this->db->query($sql);
      }

      $versandart_land = $this->db->querySingleValue("SELECT id FROM #__laender WHERE sort > 0 ORDER BY sort, id");
      $this->db->query("UPDATE #__firma SET versandart_land = '$versandart_land' WHERE id = 1");

      // Firmendaten neu einlesen
      $this->params->getFirmData();
   }

   private function _selectWaehrung($w_id, $css_id) {
      $html  = '<span class="selectbox30">';
      $html .= '   <select name="waehrung'.$css_id.'">';

      for ($i = 1; $i < 9; $i++ ) {
         $selected = '';
         if ($i == $w_id) {
            $selected = ' selected="selected"';
         }

         if ($i == 1) {
            $html .= '      <option value="1" '.$selected.'>EUR (Euro)</option>';
         }
         if ($i == 2) {
            $html .= '      <option value="2" '.$selected.'>GBP (Pfund Sterling)</option>';
         }
         if ($i == 3) {
            $html .= '      <option value="3" '.$selected.'>USD (US-Dollar)</option>';
         }
         if ($i == 4) {
            $html .= '      <option value="4" '.$selected.'>CHF (Schweizer Franken)</option>';
         }
         if ($i == 5) {
            $html .= '      <option value="5" '.$selected.'>RUB (Russische Rubel)</option>';
         }
         if ($i == 6) {
            $html .= '      <option value="6" '.$selected.'>Kr (Kronen)</option>';
         }
         if ($i == 7) {
            $html .= '      <option value="7" '.$selected.'>Li (Lire)</option>';
         }
         if ($i == 8) {
            $html .= '      <option value="8" '.$selected.'>Dh (Dirham)</option>';
         }
      }
      $html .= '   </select>';
      $html .= '</span>';
      return $html;
   }
}
