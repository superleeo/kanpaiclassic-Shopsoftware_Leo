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

// komplette Behandlung Hilfstexte incl. Generierung JavaScript
class KANPAICLASSIC_help
{
   private $db;
   private $params;
   private $text;
   private $links = array();

   function __construct() {
      $this->db = Control::getDB();
      $this->params = Control::getParams();
      $this->text = Control::getText();
      $this->getLinks();
   }

   // Datei_Inhalt (URLs) in Array einlesen
   private function getLinks() {
      $links = array();

      if (file_exists(ADMIN_PATH.'/helptexte.txt')) {
         $links = file(ADMIN_PATH.'/helptexte.txt');

         for ($i = 0; $i < count($links); $i++) {
            if (!strstr($links[$i], '// ')) {
               $this->links[] = $links[$i];
            }
         }
      }

      // Array mit leeren URLs - Fehlermeldung verhindern
      else {
         for ($i = 0; $i > 100; $i++) {
            $this->links[] = '';
         }
      }
   }


   // Hilfstext lesen und zurück geben
   public function getText($id) {
      $id--;
      $text = '';

      // URL aus Array lesen und Inhalt der Datei lesen
      $link = trim($this->links[$id]);
//      $link = str_replace('\n', '', $link);
//      $link = str_replace('\r', '', $link);
//      $link = str_replace('\a', '', $link);
//      $handle = @fopen ($link, "r");
//      if ($handle) {
//         $text = fread($handle, 10000);
//         fclose($handle);
//      }
      $text = $link;
      if ($text == '') {
         $text = 'keine Hilfe vorhanden';
      }
      if (stristr($text, 'doctype')) {
         $text = 'keine Hilfe vorhanden';
      }
      $text = str_replace('\n', '', $text);
      $text = str_replace('\r', '', $text);
      $text = str_replace('\a', '', $text);
      return $text;
   }

   // Script für Hilfstexte
   public function XXXgetScript() {
      $script = <<< EOT
<script>
function helptipOn(elem, helptext) {
   if(helptext.length) {
      var helpdiv = document.getElementById('helpdiv');
      helpdiv.innerHTML = helptext;

      helpdiv.style.display = 'block';

      var pageX = (document.all) ? document.body.offsetWidth : window.innerWidth;
      var pageY = (document.all) ? document.body.offsetHeight : window.innerHeight;

      var x1 = getX(elem);
      var y1 = getY(elem);

      var x2 = helpdiv.offsetWidth;
      var y2 = helpdiv.offsetHeight;

      helpdiv.style.top  = (y1 + 25) + 'px';
      helpdiv.style.left = (x1 + 25) + 'px';
   }
}

function getX(elem) {
   x = elem.offsetLeft;
   if (!elem.offsetParent) {
      return x;
   }
   else {
      return (x + getX(elem.offsetParent));
   }
}

function getY (elem) {
   y = elem.offsetTop;
   if (!elem.offsetParent) {
      return y;
   }
   else {
      return (y + getY(elem.offsetParent));
   }
}

function helptipOff(){
   document.getElementById('helpdiv').style.display = 'none';
}

function initHelpdiv() {
   var helpdiv = document.createElement('div');
   helpdiv.id = 'helpdiv';
   document.body.appendChild(helpdiv);
}

initHelpdiv();
</script>
EOT;
      //return $script;
      return '';
   }
}
