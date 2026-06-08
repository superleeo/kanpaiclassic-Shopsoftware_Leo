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

class KANPAICLASSIC_designExtended
{

   public $db;
   public $params;
   public $popup;

   function __construct() {
      $this->db = Control::getDB();
      $this->params = Control::getParams();

      if (defined('CONF_MODULE_POPUP')) {
         $this->popup = Control::getModulePopup();
      }
   }

   public function getContent() {
      // Seite anzeigen
      // 14.05.2019
      if ($this->params->func == '') {
         include ADMIN_PATH.'/templates/designExtended.tpl.php';
      }

      // An Modul Popup speichern
      // 14.05.2019
      else if ($this->params->func == 'popupSave') {
         $this->popup->popupSave();
         exit;
      }

      // An Modul Popup Bild upload
      // 14.05.2019
      else if ($this->params->func == 'popupUpload') {
         $this->popup->popupUpload();
         exit;
      }

      // An Modul Popup Bild lösehen
      // 14.05.2019
      else if ($this->params->func == 'popupDelete') {
         $this->popup->popupDelete();
         exit;
      }

      else {
         // An Modul HTML5 weiterreichen
         // 14.05.2019
         include "../classes/modules/extended/extended.module.php";
         $extended = new KANPAICLASSIC_modulExtended;
         $extended->getContent();
      }
   }
}
