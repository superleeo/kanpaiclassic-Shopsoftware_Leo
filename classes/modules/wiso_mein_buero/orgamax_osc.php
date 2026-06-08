<?PHP
/***************************************************************************\
*
*  Copyright (c) 2013 deltra Buisness Software GmbH & Co. KG
*  http://www.deltra.de
*  Zuletzt bearbeitet am: 2013-07-03
*
\***************************************************************************/
// define('TEST', true);

if (defined('TEST')) {
   ini_set('display_errors', 1);
   error_reporting(E_ALL);
   echo 'TEST<br /><br />';
}

if (is_file('../xdebug/wiso_log')) {
   ini_set('display_errors', 1);
   error_reporting(E_ALL);

   $fh = fopen('../xdebug/log/wiso_osc_in', 'a');
   fwrite($fh, date('d.m.Y H:i:s')."\n".print_r($_SERVER,true)."\n".print_r($_GET, true)."\n".print_r($_POST, true)."\n");
   fclose($fh);
}

#Benötigte Dateien einbinden:
if (file_exists("inc/requirements.php")) {
   require_once("inc/requirements.php");
}

else {
    die("Die Datei <b>requirements.php</b> konnte nicht gefunden werden. <br><br> Bitte &uuml;berpr&uuml;fen Sie, ob diese im Verzeichnis \"inc\" der Webshop-Anbindung vorhanden ist.");
}


// if (($_REQUEST) && $_SERVER['HTTP_USER_AGENT'] == OMX_AGENT) {
if (defined('TEST') || (($_REQUEST) && $_SERVER['HTTP_USER_AGENT'] == OMX_AGENT)) {
   // Shopsystem ermitteln
   $webshop = '';

   //2011-03-03,HM,der Webserver soll tatsächlich utf-8 liefern!!!
   if (!defined('TEST')) {
      header("Content-Type: text/xml; charset=utf-8");
      $webshop = htmlentities($_REQUEST['shp_system']);
   }

   else {
      $webshop = 'individuell';
   }

   includeShopSystemIfValid($webshop) or die(xml_error_ausgeben(DeltraResources::getText("SHOPSYSTEM_NOT_FOUND"),__FILE__, __FUNCTION__, __LINE__));

   if (!lizenz()) {
      //die (xml_error_ausgeben('Lizenz ungültig'));
   }

   # Start-Funktion aufrufen
   starten();

   # Beginn vom Verarbeiten der Daten
   $GLOBALS['lastorderID'] = -1;
   $XMLDoc = '<?xml version="1.0" encoding="utf-8" standalone="yes"?>'."\n".'<OrderNotification>'."\n";

   # Query / Array / XML-Doc holen und ausführen
   $query = '';
   $data  = null;
   daten_holen($query); // individuell.php Setzt $query (SQL-String) und datakind.


   // datakind ist 1 -> Daten aus DB
   if ($GLOBALS['datakind'] == 1) {
      /* Verbindung zur DB aufbauen */
      $data = $db->queryAllObjects($query);

      if (is_array($data) &&  count($data) > 0) {
         foreach ($data as $article) {
            $row = (array)$article;
            $XMLDoc = PositionEintragen($row, $XMLDoc);
         } // Für jeden Eintrag in der Tabelle
      } // Wenn Query ausgeführt werden konnte

      else {
         die (xml_error_ausgeben("". $err_msg['datatype_1_assoc'] . chr(13) . chr(10) . chr(13) . chr(10) . "",__FILE__, __FUNCTION__, __LINE__));
      } // Wenn Query nicht ausgef?hrt werden konnte
   } // Ein Query wurde geliefert

   elseif ($GLOBALS['datakind'] == 2) {
      foreach ($GLOBALS['ErgebnisArray'] as  $index => $row) {
         $XMLDoc = PositionEintragen($row, $XMLDoc);
      } // Für jedes Arrayelement
   } // Ein Ergebnisarray wurde geliefert

   elseif ($GLOBALS['datakind'] == 3) {
      $XMLDoc = $GLOBALS['XMLDoc'];
   } // XMLDoc wurde komplett von der Magento- oder Shopware-Schnittstelle geliefert


   if ((($GLOBALS['datakind'] == 1) && is_array($data) && count($data) > 0) or (($GLOBALS['datakind'] == 2) and (is_array($GLOBALS['ErgebnisArray']) > 0))) {
      $XMLDoc .= '</Bestellvorgang>';
   } // Bestellvorgang nur dann schließen, wenn es auch einen gab.

   if (($GLOBALS['datakind'] == 1) or ($GLOBALS['datakind'] == 2)) {
      $XMLDoc .= '</OrderNotification>';
   }

   // Für Debug-File
   $test = $XMLDoc;

   //   if (strlen(VERSCHLUESSELN) > 0) {
   if (strlen(VERSCHLUESSELN) > 0) {
      $XMLDoc = encryptString($XMLDoc, SCHLUESSEL);
   }

   // Daten ausgeben
   if (!defined('TEST')) {
      echo $XMLDoc;
   }

   else {
      echo htmlentities($test);
   }

   // Log-Dei schreiben
   if (is_file('../xdebug/wiso_log')) {
      $fh = fopen('../xdebug/log/wiso_osc_out', 'a');
      fwrite($fh, date('d.m.Y H:i:s')."\n".$test."\n".$XMLDoc."\n");
      fclose($fh);
   }

   # Ende-Funktion aufrufen
   ende();
} // Gibt es POST Variablen und wurde der Client erkannt

else {
   # Startseite laden:
   $shp_startseite = $_SERVER['HTTP_HOST'];
//   header("Location: http://$shp_startseite");

} // Falsche Zugriffsart
