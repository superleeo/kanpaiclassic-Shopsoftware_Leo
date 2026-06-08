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
   define('KANPAICLASSIC', 1);
}

ini_set('default_charset', 'UTF-8');
ini_set('serialize_precision', 12);


ini_set('display_errors', 1);
error_reporting(E_ALL);
// Nach Installation ist $db vorhanden

$dbprefix   = '';
$version_db = '';

try {
   require('../config.inc.php');
   $dbprefix = CONF_DBPREFIX;
} catch(Exeption $e) {
   exit("Konfiguration konnte nicht gefunden werden<br />");
}

include_once '../classes/control.class.php';
Control::init();

$db         = Control::getDB();
$params     = Control::getParams();
$version_db = (int)$db->querySingleValue("SELECT version FROM #__firma WHERE id = 1");

// PHP-Version überprüfen
list($a, $b, $c) = explode('.', PHP_VERSION);

if ((int)$a < 7 || (int)$a == 7 && (int)$b < 1) {
   echo '<p style="color:#cc0000; font-size:20px;">Achtung! PHP Version '.PHP_VERSION.' ist veraltet. Bitte auf PHP 7.1 oder höher updaten.</p>';
}

else {
   echo '<p><div align="center">PHP Version '.PHP_VERSION.': OK.</div></p>';
}

// Update-Dateien einlesen
$files = [];

// Dateien einlesen
if (($handle = opendir(dirname(__FILE__)))) {
   while (false !== ($file = readdir($handle))) {
      if ($file != "." && $file != "..") {
         if (substr($file, -4) == '.sql'&& strstr($file, 'update')) {
            $files[] = $file;
         }
      }
   }

   closedir($handle);
}

// Dateinamen sortieren _01 ... _99
sort($files);

// Extra Update vorhanden?
if (isset($files[0]) && $files[0] == 'extra_update.sql') {
   updateDB('extra_update.sql', 0);

   unlink('extra_update.sql');
   echo '<br />extra_update.sql wurde installiert<br />';
   $version_db = (int)$db->querySingleValue("SELECT version FROM #__firma WHERE id = 1");

   // extra_update.php vorhanden ?
   if (file_exists(ADMIN_PATH.'/update/extra_script.php')) {
      require_once ADMIN_PATH.'/update/extra_script.php';

      unlink(ADMIN_PATH.'/update/extra_script.php');
   }
}

else {
   $print_version = '';

   if (is_array($files) && count($files) > 0) {
      for ($i = 0; $i < count($files); $i++) {
         $filename   = $files[$i];
         $version_db = (int)$db->querySingleValue("SELECT version FROM #__firma WHERE id = 1");

         if ($print_version == '') {
            $print_version = ' ';
         }

         if (strstr($files[$i], 'update_') === false) {
            continue;
         }

         list(, $version) = explode('_', str_replace('.sql', '', $files[$i]));

         if (strlen($version) !== 3) {
            continue;
         }

         $version_file = (int)$version;

         if ($version_file == ($version_db + 1)) {
            updateDB($files[$i], $version_file, true);
            $print_version = ' ';
         }

         else if ($version_file > ($version_db + 1)) {
            die('update_'.sprintf('%02d', $version_db + 1).'.sql fehlt');
         }
      }
   }
}

echo '<p><div align="center">Software-DB-Version: '.$version_db.' <br /><br /><br /><br /><br /><br /><br /><br /><img src="'.ADMIN_URL.'/img/version_logo.jpg" /><br /><img src="'.ADMIN_URL.'/img/version.jpg" /><br /></div></p>';
echo '<p><div align="center"><br /><br />Prüfen Sie auf ihrer Updateseite, ob diese Version aktuell ist.<br />
Ihr Supportaccount:<br /><a href="https://support.kanpaiclassic.com/" target="_blank">support.royalart.de</a><br /></div></p>';

deleteTemp();
// ********** ENDE ********** //

function updateDB($filename, $version, $update = false) {
   global $db;

   echo "<br />Update $version: $filename";

   $sql = (file_exists(\dirname(__FILE__).'/'.$filename) ? \file_get_contents(\dirname(__FILE__).'/'.$filename) : '');

   if ($sql) {
      $test = $db->mquery($sql, $filename);

      if (!$test) {
         exit(' '.$filename.' konnte nicht verarbeitet werden') ;
      }
   }

   else {
      die ("<br />Update $filename konnte nicht gelesen werden");
   }

   // DB-Version aktualisieren
   if ($version > 0) {
      $sql = "UPDATE #__firma SET version = $version WHERE id = 1";
      $db->query($sql);
   }

   if ($update) {
      echo "<br />Update wurde erfolgreich durchgef&uuml;hrt.";
   }
}

// Temporäre Tabellen löschen
function deleteTemp() {
   global $db;
   $count = 0;

   $temp = $db->queryAllObjects("SHOW TABLES");

   if ($temp) {
      foreach ($temp as $t) {
         $t = array_values((array)$t);
         if (substr($t[0], 0, 3) == 'tmp') {
            $count++;
            $db->query("DROP TABLE `$t[0]`");
         }
      }
   }

   if ($count > 0 && isset($_GET['show'])) {
      echo '<br />'.$count.' Temporäre Dateien gelöscht<br />';
   }
}
