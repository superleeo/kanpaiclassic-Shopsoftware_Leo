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

// Länder aus DB lesen und Option-List zurück geben (ohne <select></select>)
class KANPAICLASSIC_laender
{
   private $db;
   private $params;
   private $text;
   private $laender_arr = [];

   function __construct() {
      $this->db = Control::getDB();
      $this->params = Control::getParams();
      $this->text = Control::getText();
//      $this->getLaender();
   }

   // alle Länder in $this->laender_arr[] einlesen, deaktivierte am Ende
   private function _getLaenderAll() {
      $data0 = [];
      $this->laender_arr = [];
      $sql = "SELECT id, name, name_shop, sort, versand FROM #__laender ORDER BY sort, id";
      $query = $this->db->query($sql);
      while($temp = $this->db->getObject()) {
         if ($temp->name != $temp->name_shop) {
            $temp->name = Helper::truncate($temp->name . ' (' . $temp->name_shop, 27) . ')';
         }

         $temp->versand = str_replace('.', ',', $temp->versand);
         if ($temp->sort != 0) {
            $this->laender_arr[] = $temp;
         }
         else {
            $data0[] = $temp;
         }
      }

      foreach ($data0 as $temp) {
         $this->laender_arr[] = $temp;
      }
   }

   // Aktive Länder in $this->laender_arr[] eintragen
   private function _getLaenderActive() {

      //$abholung = $this->db->querySingleValue("SELECT abholung_check_1 FROM #__firma WHERE id = 1");

      $this->laender_arr = [];
      $sql = "SELECT id, name, name_shop, versand FROM #__laender WHERE sort > 0 ORDER BY sort, id";
      $query = $this->db->query($sql);



      /*if($abholung == 'n'){
          $this->laender_arr[]=
                $this->text->get('warenkorb', 'abholung');
      }*/

      while ($land = $this->db->getObject()) {

          /*if($abholung == 'y' && $land->id == 1) {

              $this->laender_arr[]  = $land;

              $land->name           = $this->text->get('warenkorb', 'abholung'); // set Translation
              $land->name_shop      = $this->text->get('warenkorb', 'abholung');

             // $land->name           = $this->text->get('bitte', 'waehlen'); // set Translation
             // $land->name_shop      = $this->text->get('bitte', 'waehlen');

          }else if ($land->id != 1){
              $this->laender_arr[] = $land;
          }*/

          $this->laender_arr[] = $land;

      }

   }

   // Aktive Länder laden + alle EU-Länder, wenn Heimatland in EU
   private function _getLaenderEu() {
      $this->laender_arr = [];

      // Heimatland
      $eu = $this->db->querySingleValue("SELECT region FROM #__laender WHERE sort = 1");

      if ($eu != 'eu') {
         $eu = 'keine_eu';
      }

      $this->laender_arr = $this->db->queryAllObjects("SELECT id, name, name_shop, versand, if(sort = 0, 10000, sort) as sort, region FROM #__laender WHERE sort > 0 || region = 'eu' || id = 230 || id = 280 || id = 370 || id = 450 ORDER BY sort, id");
   }

   // <option>-Liste für Versandland / Kundendaten
   public function getOption($select = 0, $all = false) {
      if ($all) {
         $this->_getLaenderAll();
      }
      else {
         $this->_getLaenderActive();
      }

      foreach($this->laender_arr as $value) {
         $aktiv = '';

         // if($value->id  == 1)continue;

         if ($value->id == $select) {
            $aktiv = ' selected="selected"';
         }

         if ((int)$value->id == 10) {
            $name = $this->text->get('laender', 'noteu');
         }

         // Wenn Namen verschieden, shop_name in () anzeigen
         else if ($value->name != $value->name_shop) {
            $name = $value->name.' ('.$value->name_shop.')';
         }

         else {
            $name = $value->name;
         }

         $html .= "<option value ='".$value->id."'$aktiv>".$value->name."</option>";
      }
      return $html;
   }

   // <option>-Liste für Versandland / WK
   public function getOptionWk($select = 0) {
      $this->_getLaenderActive();

     /* $abholung = $this->db->querySingleValue("SELECT abholung_check_1 FROM #__firma WHERE id = 1");

      // Heimatland als Vorauswahl, wenn nichts angegeben - außer Abholung ist aktiv
      if ($select < 1 && !$abholung) {
          $select = (int)$this->db->querySingleValue("SELECT id FROM #__laender WHERE sort > 0 ORDER BY sort, id");
      }*/

      // $select = (int)$this->db->querySingleValue("SELECT id FROM #__laender WHERE sort > 0 ORDER BY sort, id");
      $html = '';


      foreach($this->laender_arr as $value) {

         /* if ((int)$value->id == 1) {


              if($abholung != 'y'){
                  continue;
              }

          }*/

         $aktiv = '';
         if ($value->id == $select) {
            $aktiv = ' selected="selected"';
         }

         if ((int)$value->id == 10) {
            $name = $this->text->get('laender', 'noteu');
         }

         // Wenn Namen verschieden, shop_name in () anzeigen
         else if ($value->name != $value->name_shop) {
            $name = $value->name.' ('.$value->name_shop.')';
         }
         else {
            $name = $value->name;
         }

         // Kein Preisaufschlag anzeigen
         // Aufpreis nur anzeigen, wenn vorhanden
         //if ($value->versand > 0) {
           // $name .= ' (+'.number_format($value->versand, 2, ',', '').' '.$this->params->waehrung.')';
         //}

         $html .= "<option value ='".$value->id."'$aktiv>".$name."</option>";
      }
      return $html;
   }

   // <option>-Liste für Rechnungsland / Kundendaten
   public function getOptionEu($select = 0) {
      $html = '';
      $this->_getLaenderEU();

      foreach($this->laender_arr as $value) {


          /*if ($value->id == 1) {
              continue;
          }*/


          $aktiv = '';
         if ($value->id == $select) {
            $aktiv = ' selected="selected"';
         }

         if ((int)$value->id == 10) {
            $name = $this->text->get('laender', 'noteu');
         }

         // Wenn Namen verschieden, shop_name in () anzeigen
         else if ($value->name != $value->name_shop) {
            $name = $value->name.' ('.$value->name_shop.')';
         }
         else {
            $name = $value->name;
         }

         $html .= "<option value ='".$value->id."'$aktiv>".$name."</option>";
      }

      return $html;
   }

   public function getPrice($land) {
      // DE als Vorauswahl
      if ($land < 1) {
         $land = 160;
      }

      $sql = "SELECT versand FROM #__laender WHERE id = $land";
      $this->db->query($sql);
      $data = $this->db->getObject();
      return (float)$data->versand;
   }

   // Test, ob Land aktiv ist
   public function checkLieferung($land) {
      $this->db->query("SELECT sort FROM #__laender WHERE id = $land");
      $data = $this->db->getObject();
      if ($data->sort > 0) {
         return true;
      }
      return false;
   }

   public function insertCountry($name) {
      $sql = "INSERT INTO #__laender SET
                 `name`      = '$name',
                 `shop_name` = '$name',
                 `sort`      = 0;
                 `versand`   = '0.00'";

      $this->db->query($sql);
      return $this->db->lastId();
   }

   public function getStaatById($staat) {
      $name = $this->db->querySingleValue("SELECT name_shop FROM #__laender WHERE id = $staat");
      return $name;
   }

   public function getDomainById($staat) {
      $domain = $this->db->querySingleValue("SELECT domain FROM #__laender WHERE id = $staat");
      return $domain;
   }
}