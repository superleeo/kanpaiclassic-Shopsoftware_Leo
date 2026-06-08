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

// Texte in der gewählten Sprauche zurückgeben
// Falls keine Sprache angegeben ist, oder der Text in der
// gewünschten Sprache nicht verfügbar ist,
// wird die Default-Sprache des Shops genommen
class KANPAICLASSIC_languageBase
{
   public static $cfg = array();
   // wird von Kindklasse gesetzt
   public static $params = null;
   // Fall-Back
   //   public static $default = 'deu';
   public static $default = '';

   public function get($typ, $art = '', $lang = '') {
      if ($lang == '' or $lang == 'lang') {
         $lang = self::$params->selected_lang;
      }

      // Default, falls nichts gefunden wird
      $value = '*****';
      //$value = $typ.'*'.$art;

      if (is_array(@self::$cfg[$typ])) {
         if (is_array(@self::$cfg[$typ][$art])) {
            if (@self::$cfg[$typ][$art][$lang] != '') {
               $value = self::$cfg[$typ][$art][$lang];
            }
            elseif (@self::$cfg[$typ][$art][self::$default] != '') {
               $value = self::$cfg[$typ][$art][self::$default];
            }
         }

         else {
            if (@self::$cfg[$typ][$art] != '') {
               $value = self::$cfg[$typ][$art];
            }
         }
      }
      elseif (@self::$cfg[$typ] != '') {
         $value = self::$cfg[$typ];
      }

      if (defined('CONF_SHOW_LANGARRAY') && CONF_SHOW_LANGARRAY == true) {
         return '['.$typ.']['.$art.']';
      }

      return $value;
   } // end function



}
?>