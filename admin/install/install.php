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

// verhindern, dass Installation ein weiteres Mal ausgeführt wird
if (file_exists('../config.inc.php')) {
   header('Location: '.str_replace('/install/install.php', '', $_SERVER['REQUEST_URI']));
   return;
}
ini_set('display_errors', 1);
error_reporting(E_ALL);
// HTTP-Header auf UTF-8 zwingen
header("Content-Type: text/html; charset=UTF-8");

define ('KANPAICLASSIC', true);
define ('INSTALL', true);

include 'params.class.php';
$db = null;
$params = new KANPAICLASSIC_Params($db);


$task = $params->postString('task', '', 'none');
$func = $params->postString('func', '', 'none');
$restart = false;

// Installation nach Erstellung DB abgebrochen
if (!$func && file_exists('../config.temp')) {
   $task = 'install';
   $func = 'step4';
   $restart = true;
}

// Installation noch nicht durchgeführt
elseif (!$task && !file_exists('../config.inc.php')) {
   $task = 'install';
   $func = 'step0';
}

if ($task == 'lizenz') {
   if ($params->postCheckbox('licence_check') == 'y') {
      $func = 'step1';
   }
   else {
      $func = 'step0';
   }
}

// Installation durchführen
// Manche Scripte rufen Scripte zur Auswertung auf, bevor wieder hierher zurückgekehrt wird
if ($task = 'install') {
   switch ($func) {
      case 'step0':
         include 'lizenz.tpl.php';
         break;

      case 'step1':
         include 'inst_step1.tpl.php';
         break;

      case 'step2':
         $sqlerror = 0;
         include 'inst_step2.tpl.php';
         break;

      case 'step3':
         include 'install_db.inc.php'; // --> weiter mit inst_step3.tpl.php
         break;

      case 'step4':
         if ($restart) {
            include 'inst_step3.tpl.php';
         }
         else {
            include 'set_firma.inc.php';  // --> weiter mit inst_step4.tpl.php
         }
         break;

      case 'step5':
         include 'set_admin.inc.php'; // --> weiter mit ../admin/index.php -> Login
         break;
   }
   return;
}
?>
Funktion nicht vorhanden

