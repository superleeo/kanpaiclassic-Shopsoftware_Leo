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

$shopname = '';
$firma = '';
$vorname = '';
$nachname = '';
$strasse = '';
$haus_nr = '';
$plz = '';
$ort = '';
$land = '';
$email = '';
$mailfrom = '';
$inst_prefix = $params->postString('inst_prefix');

$vars = ['shop_name', 'firm_name', 'first_name', 'last_name', 'street', 'haus_nr', 'postal_code', 'city', 'country', 'email', 'mailfrom'];
$ok = true;

// Test, ob alle Felder ausgefüllt wurden
foreach ($vars as $var) {
   ${$var} = $params->postString($var, '', 'none');
      if ($params->postString($var, '', 'none') == '' or substr($params->postString($var, '', 'none'), 0, 1) == ' ') {
         $ok = false;
      }
}

// Wenn alle Felder ausgefüllt waren
if ($ok) {
   include '../config.temp';
   include '../../classes/base/database.class.php';

   $db = new \KANPAICLASSIC\KANPAICLASSIC_database;
//   $sql = "INSERT INTO #__firma
   $sql = "UPDATE #__firma SET
            shop_name = '$shop_name',
            firm_name = '$firm_name',
            first_name = '$first_name',
            last_name = '$last_name',
            street = '$street',
            haus_nr = '$haus_nr',
            postal_code = '$postal_code',
            city = '$city',
            country = '$country',
            email = '$email',
            mailfrom = '$mailfrom'
         WHERE id = 1";
   if ($db->query($sql) == false) {
      echo  $db->message;
      die ('DB-Fehler');
   }
   include 'inst_step4.tpl.php';
}

else {
   include 'inst_step3.tpl.php';
}
?>