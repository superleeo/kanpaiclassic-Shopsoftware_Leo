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

if (!defined('KANPAICLASSIC')) {
   die ("This file cannot run outside the Kanpai Classic Shopsoftware");
}

include '../config.temp';
include '../../classes/base/database.class.php';

$username = $params->postString('username', '', 'none');
$password = $params->postString('password', '', 'none');
$vorname  = $params->postString('vorname', '', 'none');
$nachname = $params->postString('nachname', '', 'none');
$email    = $params->postString('email', '', 'none');

if ($username == '' or $password == '') {
   include 'inst_step4.tpl.php';
   return;
}


$db = new \KANPAICLASSIC\KANPAICLASSIC_database;

$sql = "SELECT first_name, last_name, email FROM #__firma WHERE id = 1";
if ($db->query($sql) === false) {
   die ("DB-Fehler: $db->message");
}

$data = $db->getObject();
$db->query("INSERT INTO #__users SET name = '$username', password = '" . md5($password) . "', vorname = '$data->first_name', nachname = '$data->last_name', email = 'notused@notused.tld', role = 0
           ON DUPLICATE KEY UPDATE name = '$username', password = '" . md5($password) . "', vorname = '$data->first_name', nachname = '$data->last_name', email = 'notused@notused.tld', role = 0");

if ($db->query($sql) === false) {
   die ("DB-Fehler: $db->message : $db->last_sql");
}

if (!@rename("../config.temp", "../config.inc.php")) {
   echo "Configurations-Datei konnte nicht umbenannt werden.";
}
else {
   header('Location: ../index.php');
}
?>