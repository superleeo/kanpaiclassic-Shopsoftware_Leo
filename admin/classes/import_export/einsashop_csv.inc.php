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

// Anzahl Spalten
$array_count = 41;

//******* EXPORT ************************************************************************************/

/******* IMPORT ************************************************************************************/
if ($mode == 'import') {
//   $overwrite = true;
   $extern = false;
   $zeilen_c = $start;
   $errortext = '';
   $trenner = $config->trenner;
   $wt = $config->worttrenner;

   $handle = fopen($file, 'r');
   $i = 0;
   $zeilen_c = 0;

   while($csvstring = fgets($handle)) {
      $csvstring = rtrim($csvstring, "\n,\r,;");
      $i++;
      if ($start ==  $i) {
         continue;
      }

      // Bei Restart nach Abbruch
      if (($restart + $start) > $i) {
         continue;
      }

      $act_article = explode($wt.$trenner.$wt, $csvstring);
      foreach ($act_article as $k => $v) {
         $act_article[$k] = trim($v, $wt);
      }

      if (count($act_article) != $array_count) {
//         $errortext = "<script></script>Anzahl Spalten ist falsch! Statt $array_count wurden ".count($act_article).' gefunden in Zeile '.$zeilen_c;
         $errortext = "Anzahl Spalten ist falsch! Statt $array_count wurden ".count($act_article).' gefunden in Zeile '.$zeilen_c;
         $zeilen_c--;
         break;
      }

      $this->last_id = 0;
      $art_id = 0;
      if ($overwrite == 'y') {
         if ($act_article[0] != '') {
            $art_id = (int)$act_article[0];
         }
         // Keine ID -> nach Art_id suchen
         else {
            $art_id = -1;
         }
      }

      // Steuersatz prüfen
      // Versandpreis in Netto umrechnen
      $steuerfaktor = 1.0;
      if ($act_article[19] == '' || (int)$act_article[19] < 0 || (int) $act_article[19] > 3) {
         $act_article[19] = 1;
      }

      if ($config->imp_preis == 'brutto') {
         $steuerfaktor = (1 + $this->params->firma['tax'.(int)$act_article[19]] / 100);
      }

      $versand = (float)(str_replace(',', '.', $act_article[20]) / $steuerfaktor);

      $artikel = array();
      $artikel['id']             = $art_id;
      // Zur Suche nach Artikel-ID
      $artikel['art_nr']         = $act_article[2];
      $artikel['name']           = $this->db->escape(str_replace('""', '"', $act_article[15]));
      $artikel['beschreibung']   = $this->db->escape(str_replace('""', '"', $act_article[16]));
      $artikel['kategorie_id']   = $act_article[17];
      $artikel['kat_name']       = $act_article[18];
      $artikel['steuersatz']     = $act_article[19];
      $artikel['versand_preis']  = $versand;
      $artikel['gewicht']        = $act_article[21];
      $artikel['vpm']            = $act_article[22];
      $artikel['vpe']            = $act_article[23];
      $artikel['images']         = [];
      // URL = $act_article[24]

      if (!defined('CONF_MODULE_PORTAL')) {
         $artikel['image']          = $this->_checkPict($act_article[25]);

         for ($p = 0; $p < 10; $p++) {
            $artikel['pictures'][]  = $this->_checkPict($act_article[(26 + $p)]);
         }
      }

      else {
         $artikel['image']          = $this->_getPictFromUrl($act_article[25], true);

         for ($p = 0; $p < 10; $p++) {
            $artikel['pictures'][]  = $this->_checkPict($act_article[(26 + $p)]);
         }
      }

      $artikel['staffelung']     = '';
      $artikel['widerruf']       = $act_article[36];
      $artikel['lieferzeit']     = $act_article[37];
      $artikel['masse_check']    = 'n';
      $artikel['masse_min']      = '1.00000';
      $artikel['masse_komma']    = '1';
      $artikel['grundeinheit']   = '';
      $artikel['ge_netto_aktiv'] = 'n';
      $artikel['ge_netto']       = '0.00000';
      $artikel['gcat']           = $act_article[38];
      $artikel['zustand']        = $act_article[39];
      $artikel['marke']          = $act_article[40];

      $this->_insertArticle($artikel, $overwrite, $catname, $cronjob, $haendler_id);

      $preis = (float)str_replace(',', '.', $act_article[10]) / $steuerfaktor;
      $ang_preis = (float)str_replace(',', '.', $act_article[13]) / $steuerfaktor;

      $variant = array();
      $variant['parent']         = $this->last_id;
      $variant['sort']           = ((int)$act_article[1] > 0) ? (int)$act_article[1] : 0;
      $variant['art_nr']         = $act_article[2];
      $variant['gtin']           = $act_article[3];
      $variant['mpn']            = $act_article[4];
      $variant['online']         = $act_article[5];
      $variant['mm_name1']       = $act_article[6];
      $variant['w_name1']        = $act_article[7];
      $variant['mm_name2']       = $act_article[8];
      $variant['w_name2']        = $act_article[9];
      $variant['netto']          = $preis;
      $variant['haendler_netto'] = $act_article[11];
      $variant['angebot_active'] = $act_article[12];
      $variant['angebot']        = $ang_preis;
      $variant['menge']          = $act_article[14];

      $this->_insertVariant($variant, $overwrite);

      $zeilen_c++;
      if (defined('CRONJOBTEST')) {
         if ($zeilen_c > 100) {
//            break;
         }
      }
   }
   fclose($handle);

   $zeilen_c -= $start;
   if (isset($is_cli) && $is_cli) {
      if ($errortext == '') {
         echo $zeilen_c." Artikel/Varianten wurden importiert\n";
         return true;
      }
      else {
         echo $errortext."\n";
         return false;
      }
   }
   else {
      if ($errortext == '') {
         echo "<script>parent.Royalart.uploadDone('ok', ' ".$zeilen_c." Artikel/Varianten wurden importiert', 'checkok');</script>";
      }
      else {
         echo "<script>parent.Royalart.uploadDone('error', '".$errortext."', 'checkok');</script>";
      }
   }
 }

?>