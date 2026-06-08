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

class KANPAICLASSIC_designAdmin
{
   private $db     = null;
   private $params = null;

   function __construct() {
      $this->db = Control::getDb();
      $this->params = Control::getParams();
   }

   public function getContent() {
      if ($this->params->func == 'save') {
         $this->save();
         exit;
      }

      $this->showDesign();
   }

   public function loadDesign() {
      $admin_config = new \stdClass();
      $data = $this->db->queryAllObjects("SELECT type, data FROM #__data WHERE type LIKE 'admdsgn_%'");

      if (is_array($data)) {
         foreach ($data as $d) {
            $admin_config->{$d->type} = $d->data;
         }
      }

      return $admin_config;
   }

   public function showDesign() {
//      $admin_config = $this->loadDesign();

      require_once SHOP_PATH.'/classes/modules/admindesign/admindesign.tpl.php';
      exit;
   }

   public function save() {
      $type = $this->params->postString('type');
      $data = $this->params->postString('data');

      Helper::setData($type, $data);

      echo json_encode(['status' => 'ok', 'data' => Helper::getData($type)]);
      exit;

   }

   public function _getFontfamily($font_id) {
      require_once ADMIN_PATH.'/classes/designColors.class.php';
      $design = new KANPAICLASSIC_designColors();

      return $design->_getFontfamily($font_id);
   }

   public function _getFontsize($font_size) {
      require_once ADMIN_PATH.'/classes/designColors.class.php';
      $design = new KANPAICLASSIC_designColors();

      return $design->_getFontsize($font_size);
   }
}