<?PHP
/***************************************************************************\
*
*  Copyright (c) 2013 deltra Buisness Software GmbH & Co. KG
*  http://www.deltra.de
*  Zuletzt bearbeitet am: 2013-03-05
*
\***************************************************************************/
//define('TEST', true);

if (defined('TEST')) {
   ini_set('display_errors', 1);
   error_reporting(E_ALL);
   // echo 'TEST<br /><br />';
}

if (is_file('../xdebug/wiso_log')) {
   $fh = fopen('../xdebug/log/wiso_osc_sync_in', 'a');
   fwrite($fh, date('d.m.Y H:i:s')."\n".print_r($_SERVER, true)."\n".print_r($_GET, true)."\n".print_r($_POST, true)."\n");
   fclose($fh);
}

#Benötigte Dateien einbinden:
if (file_exists("inc/requirements.php")) {
   require_once("inc/requirements.php");

   if (!defined('TEST')) {
      header("Content-Type: text/xml; charset=utf-8");
   }
}

else {
    die("Die Datei <b>requirements.php</b> konnte nicht gefunden werden. <br><br> Bitte &uuml;berpr&uuml;fen Sie, ob diese im Verzeichnis \"inc\" der Webshop-Anbindung vorhanden ist.");
}

if (defined('TEST') || (($_REQUEST) && $_SERVER['HTTP_USER_AGENT'] == OMX_AGENT)) {
//   if (true) {

   // Request-Parameter ermitteln
   $webshop = htmlentities(@$_REQUEST['shp_system']);

   if (defined('TEST')) {
      $webshop = 'individuell';
   }

   // Schnittstelle des Shopsystems einbetten
   includeShopSystemIfValid($webshop) or die(xml_error_ausgeben(DeltraResources::getText("SHOPSYSTEM_NOT_FOUND"),__FILE__, __FUNCTION__, __LINE__));

   $function = htmlentities($_REQUEST["sync"]);

   if ($function != 'check_open_orders' && !lizenz()) {
      die (xml_error_ausgeben('Lizenz ungültig', '', '', ''));
   }

   # Artikel Webshop -> ERP

   if (defined('TEST')) {
      // echo $function.'<br /><br />';
   }

   // Artikel Webshop -> ERP
   if ($function == "shop_to_omx") {
         # Start-Funktion aufrufen
         starten();

         # Beginn vom Verarbeiten der Daten:
         $XMLDoc = '<?xml version="1.0" encoding="utf-8" standalone="yes"?>'."\n".'<Artikelimport>'."\n";

         # Query / Array holen und ausf�hren
         if (function_exists('artikeldaten_shop_zu_orgamax')) {
            artikeldaten_shop_zu_orgamax();

            if ($GLOBALS['datakind'] == 1) {
               /* Verbindung zur DB aufbauen */
               //@$qRes = mysql_query($GLOBALS['query']);
               //if ($qRes) {
                  //while ($row = mysql_fetch_assoc($qRes))
                  //{
               $data = $db->queryAllObjects($GLOBALS['query']);

               if (is_array($data) && count($data > 0)) {
                  foreach ($data as $article) {
                     $row = (array)$article;
                     $XMLDoc = ArtikelEintragen($row, $XMLDoc);
                  } // F�r jeden Eintrag in der Tabelle
                       $XMLDoc .= '</Artikelimport>';
               } // Wenn Query ausgeführt werden konnte

               else  {
                  die (xml_error_ausgeben(DeltraResources::getText("SQL_EXECUTION_ERROR") . chr(13) . chr(10) . chr(13) . chr(10) . mysqli_error($GLOBALS['sql_con'])."",__FILE__, __FUNCTION__, __LINE__));
               } // Wenn Query nicht ausgef?hrt werden konnte
            } // Ein Query wurde geliefert

            elseif ($GLOBALS['datakind'] == 2) {
               foreach ($GLOBALS['ErgebnisArray'] as  $index => $row) {
                  $XMLDoc = ArtikelEintragen($row, $XMLDoc);
               } // F?r jedes Arrayelement

               $XMLDoc .= '</Artikelimport>';
            } // Ein Ergebnisarray wurde geliefert

            elseif ($GLOBALS['datakind'] == 3) {
                $XMLDoc = $GLOBALS['XMLDoc'];
            }

            if (is_file('../xdebug/wiso_log')) {
               $fh = fopen('../xdebug/log/wiso_artikel_nach_omx', 'a');
               fwrite($fh, date('d.m.Y H:i:s')."\n".$XMLDoc."\n");
               fclose($fh);
            }

            if (strlen(VERSCHLUESSELN) > 0) {
               $XMLDocCrypt = encryptString($XMLDoc, SCHLUESSEL);
            }

            // Daten ausgeben
            if (!defined('TEST')) {
               echo  $XMLDocCrypt;
            }
            
            else {
               echo  '<code>'.htmlentities($XMLDoc).'</code>';
            }

            # Ende-Funktion aufrufen
            ende();
         }

         else {
            die (xml_error_ausgeben("Import Funktion f�r Artikeldaten nicht vorhanden",__FILE__, __FUNCTION__, __LINE__));
         }
      } // Artikeldaten aus dem Webshop �bernehmen

   # Artikel ERP -> Webshop
   else if ($function == "check_open_orders") {
      starten();

      if(function_exists('pruefeOffeneBestellungenImShop')) {
         pruefeOffeneBestellungenImShop();
      }

      else {
         die (xml_error_ausgeben("Import Funktion zum Pr�fen offener Bestellungen ist nicht vorhanden",__FILE__, __FUNCTION__, __LINE__));
      }

      ende();
   }

   else if ($function == "omx_to_shop") {
      starten();

      if(function_exists('artikeldaten_orgamax_zu_shop')) {
         artikeldaten_orgamax_zu_shop();
      }

      else {
         die (xml_error_ausgeben("Export Funktion f�r Artikeldaten nicht vorhanden",__FILE__, __FUNCTION__, __LINE__));
      }

      ende();
   }

   # Artikelliste vom Shop laden
   else if ($function == "articlelist_from_shop") {
      starten();

      if(function_exists('hole_Artikelliste_fuer_export')) {
         $artikelliste = hole_Artikelliste_fuer_export();
         
         if (!defined('TEST')) {
            echo $artikelliste;
         }
         
         else {
            echo '<code>'.htmlentities($artikelliste).'</code>';
         }
      }

      else {
         die (xml_error_ausgeben("Import Function von Artikellisten nicht vorhanden",__FILE__, __FUNCTION__, __LINE__));
      }

      ende();
   }

   # Lagerbestand -> Webshop
   else if ($function == "stockvalue_to_shop") {
      starten();

      if(function_exists('setze_lagerbestand_im_shop')) {
         setze_lagerbestand_im_shop();
      }

      else {
         die (xml_error_ausgeben("Export Funktion f�r Bestellstatus nicht vorhanden",__FILE__, __FUNCTION__, __LINE__));
      }

      ende();
   }

   # Preisliste -> Webshop
   else if ($function == "pricelist_to_shop") {
      starten();

      if(function_exists('setze_Artikelpreise_im_shop')) {
         setze_Artikelpreise_im_shop();
      }

      else {
         die (xml_error_ausgeben("Export Funktion f�r Preislisten nicht vorhanden",__FILE__, __FUNCTION__, __LINE__));
      }

      ende();
   }

   else if($function == "paging_informationen") {
      starten();

      if(function_exists('paging_informationen')) {
         paging_informationen();
      }

      else {
         die (xml_error_ausgeben("Export Funktion für Paging Informationen nicht vorhanden",__FILE__, __FUNCTION__, __LINE__));
      }

      ende();
   }
   
   else {
      // Protokoll ausgeben
      die('Funktion nicht vorhanden');
   }
}
