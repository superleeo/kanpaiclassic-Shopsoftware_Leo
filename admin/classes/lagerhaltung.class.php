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

class KANPAICLASSIC_lagerhaltung
{
   public $db;
   public $params;
   public $text;
   private $lager = [];

   function __construct() {
      $this->db = Control::getDB();
      $this->params = Control::getParams();
      $this->text = Control::getText();
   }


   public function getContent() {
      if ($this->params->func == 'update') {
         $this->writeData();
         $this->getData();
      }
      else {
         $this->getData();
      }
      include 'templates/lagerhaltung.tpl.php';
      return;
   }


   // Daten lesen
   private function getData() {
      $sql = "SELECT  lager_show, lager_abziehen, lager_leer, lager_bestell_check, lager_zeit, lager_deaktiviert FROM #__firma WHERE id = 1";
      $this->db->query($sql);
      $data = $this->db->getObject();

      $this->lager['lager_show']          = $data->lager_show;
      $this->lager['lager_abziehen']      = $data->lager_abziehen;
      $this->lager['lager_leer']          = $data->lager_leer;
      $this->lager['lager_bestell_check'] = $data->lager_bestell_check;
      $this->lager['lager_zeit']          = $data->lager_zeit;
      $this->lager['lager_deaktiviert']   = $data->lager_deaktiviert;

      return;
   }

   // Daten speichern
   private function writeData() {
      $lager_show          = $this->params->postString('lager_show') != '' ? 'y' : 'n';
      $lager_abziehen      = $this->params->postString('lager_abziehen') != '' ? 'y' : 'n';
      $lager_leer          = $this->params->postString('lager_leer') != '' ? 'y' : 'n';
      $lager_bestell_check = $this->params->postString('lager_bestell_check') != '' ? 'y' : 'n';
      $lager_zeit          = (int)$this->params->postInt('lager_zeit');
      $lager_deaktiviert   = $this->params->postString('lager_deaktiviert') != '' ? 'y' : 'n';
      
      if ($lager_leer == 'n') {
         $lager_bestell_check = 'n';
      }

      $sql = "UPDATE #__firma
                SET lager_show          = '$lager_show', 
                    lager_abziehen      = '$lager_abziehen', 
                    lager_leer          = '$lager_leer', 
                    lager_bestell_check = '$lager_bestell_check', 
                    lager_zeit          = '$lager_zeit', 
                    lager_deaktiviert   = '$lager_deaktiviert'
              WHERE id = 1";
      $this->db->query($sql);
   }
}
?>