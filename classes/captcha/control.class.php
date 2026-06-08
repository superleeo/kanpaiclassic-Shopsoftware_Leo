<?php
/*
###################################################################################
  OBADJA(R) Shopsystem
  Release Datum: 01.08.2012
  Entwicklungsstand: 05.03.2015 Version 4.2

  OBADJA(R) - the best software solutions
  http://www.obadja.eu

  (c) Copyright by Dipl. Des. Sven Scholz - Design Center OBADJA(R)

  Copyrightvermerke duerfen NICHT entfernt werden!
  ------------------------------------------------------------------------
  Bei Verstoß gegen die Lizenzbedingungen kann die Lizenz jederzeit entzogen werden.
  Der Kaufpreises wird nicht erstattet. Wer gegen die Lizenzbedingungen verstoesst, muss
  mit einer Vertragsstrafe von 50.000 Euro je Einzeldelikt rechnen!
  ------------------------------------------------------------------------
  Dieses Programm ist eine Software von Dipl. Des. Sven Scholz, Design Center OBADJA(R).
  Diese Software darf nicht veroeffentlicht, weitergeben und/oder modifizieren werden. 
  Es gelten die Ihnen mitgeteilten und unterschriebenen Lizenzbestimmungen.
  Diese Software/Website ist eine Einzellizenz und für den Betrieb auf einem Speicherplatz
  (Webspace) berechtigt.
  Die Veroeffentlichung dieses Programms erfolgt OHNE IRGENDEINE GARANTIE, sogar ohne
  die implizite Garantie der MARKTREIFE oder der VERWENDBARKEIT FUER EINEN BESTIMMTEN ZWECK.

##################################################################################
  Copyrightvermerke duerfen NICHT entfernt werden!
*/

if (!file_exists('../../admin/config.inc.php')) {
//   header('Location: '.str_replace('/index.php', '', $_SERVER['PHP_SELF']).'/admin');
}

include_once '../../admin/config.inc.php';
include_once '../base/database.class.php';
// include_once '../base/helper.class.php';

// Über diese statische Klasse finden alle Klasseninitialisierunge statt
// Verhindert, dass Klassen mehrmals geladen/initialisiert werden
// Klassen werden nur geladen, wenn sie auch benötigt werden

class Control {
   public static function init() {
      $params = self::getParams();
   }

   public static function &getDB() {
      static $instance;
      if (!$instance) {
         include_once '../base/database.class.php';
         $instance = new OBADJA_Database();
      }
      return $instance;
   }

   public static function &getParams() {
      static $instance;
      if (!$instance) {
         require_once '../base/params_base.class.php';
         require_once '../base/session.class.php';
         require_once '../params.class.php';
         $instance = new OBADJA_Params();
      }

      return $instance;
   }

}