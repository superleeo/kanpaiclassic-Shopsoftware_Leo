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
//$array_count = 41;
$array_count = 44;

//******* EXPORT ************************************************************************************/
if ($mode == 'export') {
   $trenner = $config->trenner;
   $wt = $config->worttrenner;
   $tt = '';
   if ($wt == '') {
      $tt = '"';
   }

   $sql = "SELECT i.id, i.cat_id, i.steuersatz, i.name_$lang AS name, i.desc_$lang AS beschreibung,
                  i.pict01, i.pict02, i.pict03, i.pict04, i.pict05, i.pict06, i.pict07, i.pict08, i.pict09, i.pict10, i.pict11,
                  i.versand_preis, i.gewicht, i.marke, i.vpe, i.vpm, i.grundeinheit, i.ge_netto_aktiv,
                  a.id AS artikel_id,
                  c.name_deu AS catname, i.widerruf, i.lieferfrist,
                  g.categories AS gcat, g.zustand
              FROM #__articles_info AS i
           LEFT JOIN #__articles AS a
              ON i.id = a.parent_id
           LEFT JOIN #__categories AS c
              ON c.id = i.cat_id
           LEFT JOIN #__articles_to_googlecats AS g
              ON  g.parent_id = i.id
           WHERE a.sort = 1
           ORDER BY i.id";

   $info = $this->db->queryAllObjects($sql);

   $csv = '';
   if ($config->csv_head == 'y') {
      // shop_article
      $head  = $wt.'id'.$wt.$trenner.$wt.'sort'.$wt.$trenner.$wt.'art_nr'.$wt.$trenner.$wt.'gtin'.$wt.$trenner.$wt.'mpn'.$wt.$trenner;
      $head .= $wt.'online'.$wt.$trenner.$wt.'merkmal1'.$wt.$trenner.$wt.'wert1'.$wt.$trenner.$wt.'merkmal2'.$wt.$trenner.$wt.'wert2'.$wt.$trenner;
      $head .= $wt.'brutto'.$wt.$trenner.$wt.'haendler_netto'.$wt.$trenner.$wt.'angebot'.$wt.$trenner.$wt.'ang_brutto'.$wt.$trenner.$wt.'menge'.$wt.$trenner;
      $head .= $wt.'ge_brutto'.$wt.$trenner;
// 15 Einträge
// 16 Einträge - ge_brutto hinzugefügt
//id;sort;art_nr;gtin;mpn;
//online;merkmal1;wert1;merkmal2;wert2;
//brutto;haendler_netto;angebot;ang_brutto;menge;


      // shop_article_info
      $head .= $wt.'name'.$wt.$trenner.$wt.'beschreibung'.$wt.$trenner.$wt.'cat_id'.$wt.$trenner.$wt.'cat_name'.$wt.$trenner.$wt.'ust_satz'.$wt.$trenner;
      $head .= $wt.'vers_netto'.$wt.$trenner.$wt.'gewicht'.$wt.$trenner.$wt.'groesse'.$wt.$trenner.$wt.'ve'.$wt.$trenner.$wt.'url'.$wt.$trenner;
      $head .= $wt.'bild1'.$wt.$trenner.$wt.'bild2'.$wt.$trenner.$wt.'bild3'.$wt.$trenner.$wt.'bild4'.$wt.$trenner.$wt.'bild5'.$wt.$trenner;
      $head .= $wt.'bild6'.$wt.$trenner.$wt.'bild7'.$wt.$trenner.$wt.'bild8'.$wt.$trenner.$wt.'bild9'.$wt.$trenner.$wt.'bild10'.$wt.$trenner;
      $head .= $wt.'bild11'.$wt.$trenner.$wt.'widerruf'.$wt.$trenner.$wt.'lieferzeit'.$wt.$trenner.$wt.'gcat'.$wt.$trenner.$wt.'zustand'.$wt.$trenner;
      $head .= $wt.'marke'.$wt.$trenner.$wt.'grundeinheit'.$wt.$trenner.$wt.'ge_aktiv'.$wt.CRLF;
// 26 Einträge
// 28 Einträge - grundeinheit, ge_aktiv hinzugefügt
//name;beschreibung;cat_id;cat_name;ust_satz;
//vers_netto;gewicht;ve;groesse;url;
//bild1;bild2;bild3;bild4;bild5;
//bild6;bild7;bild8;bild9;bild10;
//bild11;widerruf;lieferzeit;gcat;zustand;
//marke;
      $csv .= $head;
   }

   for ($i = 0; $i < count($info); $i++) {
      // Bei Kleingewerbe Steuersatz3 (0%)
      if ($this->params->firma['kleingewerbe'] == 'y') {
         $info[$i]->steuersatz = 3;
      }
      $csv2 = '';
      $csv .= $wt.$info[$i]->id.$wt.$trenner;

      $sql = "SELECT a.*,
                     m1.merkmal_$lang AS mm_name1, m2.merkmal_$lang AS mm_name2, w1.wert_$lang AS w_name1, w2.wert_$lang AS w_name2, ge_netto
                 FROM #__articles AS a
              LEFT JOIN #__merkmale AS m1
                 ON m1.id = a.merkmal1
              LEFT JOIN #__merkmale AS m2
                 ON m2.id = a.merkmal2
              LEFT JOIN #__werte AS w1
                 ON w1.id = a.wert1
              LEFT JOIN #__werte AS w2
                 ON w2.id = a.wert2
             WHERE parent_id = ".$info[$i]->id."
             ORDER BY sort";

             $this->db->query($sql);
      $data = array();
      while ($tmp = $this->db->getObject()) {
         if ($tmp) {
            $data[] = $tmp;
         }
      }

      for ($d = 0; $d< count($data); $d++) {
         $brutto         = number_format(round((float)$data[$d]->netto * (1 + $this->params->firma['tax'.(int)$info[$i]->steuersatz] / 100), 2), 2, '.', '');
         $ge_brutto      = number_format(round((float)$data[$d]->ge_netto * (1 + $this->params->firma['tax'.(int)$info[$i]->steuersatz] / 100), 2), 2, '.', '');
         $ang_brutto     = number_format(round((float)$data[$d]->angebot * (1 + $this->params->firma['tax'.(int)$info[$i]->steuersatz] / 100), 2), 2, '.', '');
         $haendler_netto = number_format(round((float)$data[$d]->haendler_netto, 2), 2, '.', '');

         // ID
         $variante  = $wt.$data[$d]->sort.$wt.$trenner;
         $variante .= $wt.$data[$d]->art_nr.$wt.$trenner;
         $variante .= $wt.$data[$d]->gtin.$wt.$trenner;
         $variante .= $wt.$data[$d]->mpn.$wt.$trenner;

         $variante .= $wt.$data[$d]->online.$wt.$trenner;
         $variante .= $wt.$data[$d]->mm_name1.$wt.$trenner;
         $variante .= $wt.$data[$d]->w_name1.$wt.$trenner;
         $variante .= $wt.$data[$d]->mm_name2.$wt.$trenner;
         $variante .= $wt.$data[$d]->w_name2.$wt.$trenner;

         $variante .= $wt.$brutto.$wt.$trenner;
         $variante .= $wt.$haendler_netto.$wt.$trenner;
         $variante .= $wt.$data[$d]->angebot_active.$wt.$trenner;
         $variante .= $wt.$ang_brutto.$wt.$trenner;
         $variante .= $wt.$data[$d]->menge.$wt.$trenner;
         $variante .= $wt.$ge_brutto.$wt.$trenner;


         // Hauptartikel
         if ($d == 0) {
            $csv .= $variante;
         }

         // Varianten
         else {
            $csv2 .= $wt.$wt.$trenner.$variante;
            // Anzahl Felder aus article_info ohne id (26)
            $csv2 .= $wt.$wt.$trenner. $wt.$wt.$trenner. $wt.$wt.$trenner. $wt.$wt.$trenner. $wt.$wt.$trenner;
            $csv2 .= $wt.$wt.$trenner. $wt.$wt.$trenner. $wt.$wt.$trenner. $wt.$wt.$trenner. $wt.$wt.$trenner;
            $csv2 .= $wt.$wt.$trenner. $wt.$wt.$trenner. $wt.$wt.$trenner. $wt.$wt.$trenner. $wt.$wt.$trenner;
            $csv2 .= $wt.$wt.$trenner. $wt.$wt.$trenner. $wt.$wt.$trenner. $wt.$wt.$trenner. $wt.$wt.$trenner;
            $csv2 .= $wt.$wt.$trenner. $wt.$wt.$trenner. $wt.$wt.$trenner. $wt.$wt.$trenner. $wt.$wt.$trenner;
            $csv2 .= $wt.$wt.$trenner. $wt.$wt.$trenner. $wt.$wt.CRLF;
         }
      }

      $name = str_replace($wt, $wt.$wt, $info[$i]->name);
      $beschreibung = '';
      if ($config->html == 'text') {
         $beschreibung = $this->_html2txt(($info[$i]->beschreibung));
      }
      else {
         $beschreibung = str_replace(CR, '', nl2br($info[$i]->beschreibung));
      }

      $beschreibung = str_replace($wt, $wt.$wt, $beschreibung);

      $vers_brutto = number_format(round((float)$info[$i]->versand_preis * (1 + $this->params->firma['tax'.(int)$info[$i]->steuersatz] / 100), 2), 2, '.', '');

      $csv .= $wt.$tt.$info[$i]->name.$tt.$wt.$trenner;
      $csv .= $wt.$tt.$beschreibung.$tt.$wt.$trenner;
      $csv .= $wt.$info[$i]->cat_id.$wt.$trenner;
      $csv .= $wt.$info[$i]->catname.$wt.$trenner;
      $csv .= $wt.$info[$i]->steuersatz.$wt.$trenner;

      $csv .= $wt.$vers_brutto.$wt.$trenner;
      $csv .= $wt.$info[$i]->gewicht.$wt.$trenner;
      $csv .= $wt.$info[$i]->vpm.$wt.$trenner;
      $csv .= $wt.$info[$i]->vpe.$wt.$trenner;
// deu_im Link      $csv .= $wt.$shopurl.(defined('CONF_USE_HTACCESS') ? '' : '/index.php').'/deu_'.$info[$i]->artikel_id.'/'.urldecode($info[$i]->name).$wt.$trenner;
      $csv .= $wt.$shopurl.(defined('CONF_USE_HTACCESS') ? '' : '/index.php').'/'.$info[$i]->artikel_id.'/'.urldecode($info[$i]->name).$wt.$trenner;

      $csv .= $wt.$this->_checkPict($info[$i]->pict01, $picurl).$wt.$trenner;
      $csv .= $wt.$this->_checkPict($info[$i]->pict02, $picurl).$wt.$trenner;
      $csv .= $wt.$this->_checkPict($info[$i]->pict03, $picurl).$wt.$trenner;
      $csv .= $wt.$this->_checkPict($info[$i]->pict04, $picurl).$wt.$trenner;
      $csv .= $wt.$this->_checkPict($info[$i]->pict05, $picurl).$wt.$trenner;

      $csv .= $wt.$this->_checkPict($info[$i]->pict06, $picurl).$wt.$trenner;
      $csv .= $wt.$this->_checkPict($info[$i]->pict07, $picurl).$wt.$trenner;
      $csv .= $wt.$this->_checkPict($info[$i]->pict08, $picurl).$wt.$trenner;
      $csv .= $wt.$this->_checkPict($info[$i]->pict09, $picurl).$wt.$trenner;
      $csv .= $wt.$this->_checkPict($info[$i]->pict10, $picurl).$wt.$trenner;

      $csv .= $wt.$this->_checkPict($info[$i]->pict11, $picurl).$wt.$trenner;
      $csv .= $wt.$info[$i]->widerruf.$wt.$trenner;
      $csv .= $wt.$info[$i]->lieferfrist.$wt.$trenner;

      if ($info[$i]->gcat != '') {
         $csv .= $wt.$tt.$info[$i]->gcat.$tt.$wt.$trenner;
      }

      else {
         $csv .= $wt.$info[$i]->gcat.$wt.$trenner;
      }

      $csv .= $wt.$info[$i]->zustand.$wt.$trenner;

      $csv .= $wt.$info[$i]->marke.$wt.$trenner;
      $csv .= $wt.$info[$i]->grundeinheit.$wt.$trenner;
      $csv .= $wt.$info[$i]->ge_netto_aktiv.$wt.CRLF;

      $csv .= $csv2;
   }
}

/******* IMPORT ************************************************************************************/
if ($mode == 'import') {
   $extern = false;
   $zeilen_c = $start;
   $errortext = '';
   $trenner = $config->trenner;
   $wt = $config->worttrenner;
   $tt = $wt;
   if ($wt == '') {
      $tt = '"';
   }

   $extern = true;
   $start_id = 1;
   $articles = array();

   //   $articles = file($file);
   $csvstring1 = file_get_contents($file);
   $csvstring = strip_tags(html_entity_decode($csvstring1, ENT_NOQUOTES, 'UTF-8'));

   // Bei HTML verwenden
   if ($csvstring != $csvstring1) {
      unset($csvstring);
      unset($csvstring1);
      $handle = fopen($file, 'r');
      $i = 0;
      $zeilen_c = 0;

      while($csvstring = fgets($handle)) {
         $csvstring = rtrim($csvstring, "\n,\r");
         $zeilen_c++;
         $i++;

         $act_article = explode($wt.$trenner.$wt, $csvstring);

         if (count($act_article) > $array_count) {
            $offset = count($act_article) - $array_count;

            for ($a = 1; $a <= $offset; $a++) {
//               $act_article[16] .= ';'.$act_article[16 + $a];
               $act_article[17] .= ';'.$act_article[17 + $a];
            }

//            for ($a = 17; $a < ($array_count); $a++) {
            for ($a = 18; $a < ($array_count); $a++) {
               $act_article[$a] = $act_article[$a + $offset];
               if (($a + $offset) >= $array_count) {
                  unset($act_article[$a + $offset]);
               }
            }
         }

         foreach ($act_article as $k => $v) {
            $act_article[$k] = trim($v, $wt);
         }


         if (count($act_article) != $array_count) {
            $errortext = "Anzahl Spalten ist falsch! Statt $array_count wurden ".count($act_article).' gefunden in Zeile '.$zeilen_c;
            break;
         }
         $articles[] = $act_article;
      }
   }

   // Bei Text verwenden
   else {
//      preg_match_all('|(.*?;){16}(.*?)(;.*?){24}\s|is', $csvstring, $tmp);
      preg_match_all('|(.*?;){17}(.*?)(;.*?){26}\s|is', $csvstring, $tmp);
      $filepos = 0;
      for ($i = 0; $i < count($tmp[0]); $i++) {
         $artikel = $tmp[0][$i];
         // Beschreibung vorhanden?
         if ($tmp[2][$i] !== '') {
            $pos = strpos($artikel, $tmp[2][$i]);
            $len = strlen($tmp[2][$i]);
            // Teil vor Beschreibung
            $teil1 = substr($artikel, 0, $pos);
            $teil1_len = strlen($teil1);
            // Teil nach Beschreibung
            $teil3 = substr($artikel, $len + $teil1_len);

            $laenge = (strpos($csvstring1, $teil3, $filepos) - $filepos - $teil1_len);
            $teil2 = substr($csvstring1, ($filepos + $teil1_len), $laenge);

            $artikel = $teil1.$teil2.$teil3;
            $filepos += strlen($artikel);
         }
         else {
            $filepos += strlen($artikel);
         }

         $artikel = trim($tmp[0][$i], "\n,\r");

         if ($i == $start && substr($artikel, 0, 1) == ';') {
            $this->sort_fixed = true;
         }

         if ($this->sort_fixed && substr($artikel, 0, 1) == ';') {
            $articles[$i] = $start_id++.$artikel;
         }
         else {
            $articles[$i] = $artikel;
         }
      }
   }

   // Verarbeiten
   $steuerfaktor = 1.0;
   $zeilen_c = 0;
   for ($i = $start; $i < count($articles); $i++) {
      $zeilen_c++;
      $act_article = array();

      if (is_array($articles[$i])) {
         $act_article = $articles[$i];
      }

      else {
         $zeile = trim($articles[$i], "\n,\r").'XENDX';
         $zeile_org = $articles[$i];

         // Zeilen trennen
         while (strlen($zeile) > 0) {
            // ';' 1. Zeichen => Trennzeichen Felder
            if (substr($zeile, 0, 1) == $trenner) {
               $act_article[] = '';
               $zeile = substr($zeile, 1);
            }
            // 1. Zeichen => zugehöriges '"' suchen und Text dazwischen übernehmen
            else if (substr($zeile, 0, 1) == $tt) {
               $pos = strpos($zeile, $tt.$trenner);
               if ($pos !== false) {
                  $act_article[] = substr($zeile, 1, $pos - 1);
                  $zeile = substr($zeile, $pos + 2);
               }
               // letzter Eintrag in der Zeile
               else {
                  $act_article[] = $zeile;
                  $zeile = '';
               }
            }
            // sonst Wert bis nächstem ';' übernehmen

            else if (trim($zeile, "\n,\r") == '') {
               $act_article[] = '';
               $zeile = '';
            }

            else if ($zeile != 'XENEX') {
               $pos = strpos($zeile, ';');
               if ($pos !== false) {
                  $act_article[] = substr($zeile, 0, $pos);
                  $zeile = substr($zeile, $pos + 1);

                  if ($zeile == '') {
                     $act_article[] = '';
                  }
               }

               // letzter Eintrag
               else {
                  $act_article[] = str_replace('XENDX', '', $zeile);
                  $zeile = '';
               }
            }
            else {
               $act_article[] = $zeile;
               $zeile = '';
            }
         } // while
      }

      if (count($act_article) != $array_count) {
//         $errortext = "<script></script>Anzahl Spalten ist falsch! Statt $array_count wurden ".count($act_article).' gefunden in Zeile '.$zeilen_c;
         $errortext = "Anzahl Spalten ist falsch! Statt $array_count wurden ".count($act_article).' gefunden in Zeile '.$zeilen_c;
         $zeilen_c--;
         break;
      }

      // Titelzeile überspringen
      if ($act_article[0] == 'id' || $act_article[0] == $tt.'id'.$tt) {
         continue;
      }

      // Daten bereinigen
      for ($a = 0; $a < count($act_article); $a++) {
         $act_article[$a] = trim($act_article[$a], '"');
      }


      $sort = ((int)$act_article[1] > 0) ? (int)$act_article[1] : 0;

      if ($act_article[0] != '') {
         $this->last_id = 0;
         $art_id = 0;
         if ($overwrite == 'y') {
            $art_id = (int)$act_article[0];
            $sort = 1;
         }

         // Steuersatz prüfen
         // Versandpreis in Netto umrechnen
         $steuerfaktor = 1.0;
//         if ($act_article[19] == '' || (int)$act_article[16] < 0 || (int) $act_article[19] > 3) {
//            $act_article[19] = 1;
//         }
         if ($act_article[20] == '' || (int)$act_article[17] < 0 || (int) $act_article[20] > 3) {
            $act_article[20] = 1;
         }

         if ($config->imp_preis == 'brutto') {
//            $steuerfaktor = (1 + $this->params->firma['tax'.(int)$act_article[19]] / 100);
            $steuerfaktor = (1 + $this->params->firma['tax'.(int)$act_article[20]] / 100);
         }

//         $versand = (float)(str_replace(',', '.', $act_article[20]) / $steuerfaktor);
         $versand  = (float)(str_replace(',', '.', $act_article[21]) / $steuerfaktor);

         $artikel = array();
         $artikel['id']             = $art_id;
         // Zur Suche nach Artikel-ID
         $artikel['art_nr']         = $act_article[2];
//         $artikel['name']           = $this->db->escape(str_replace('""', '"', $act_article[15]));
         $artikel['name']           = $this->db->escape(str_replace('""', '"', $act_article[16]));
//         $artikel['beschreibung']   = $this->db->escape(str_replace('""', '"', $act_article[16]));
         $artikel['beschreibung']   = $this->db->escape(str_replace('""', '"', $act_article[17]));
         if ($extern) {
            $artikel['beschreibung']   = str_replace(array('\r\n', '\n'), '<br />', $artikel['beschreibung']);
         }
//         $artikel['kategorie_id']   = $act_article[17];
         $artikel['kategorie_id']   = $act_article[18];
//         $artikel['kat_name']       = $act_article[18];
         $artikel['kat_name']       = $act_article[19];
//         $artikel['steuersatz']     = $act_article[19];
         $artikel['steuersatz']     = $act_article[20];
         $artikel['versand_preis']  = $versand;
//         $artikel['gewicht']        = $act_article[21];
         $artikel['gewicht']        = $act_article[22];
//         $artikel['vpm']            = $act_article[22];
         $artikel['vpm']            = $act_article[23];
//         $artikel['vpe']            = $act_article[23];
         $artikel['vpe']            = $act_article[24];
         // URL = $act_article[24]

         if (!defined('CONF_MODULE_PORTAL')) {
//            $artikel['pict01']         = $this->_checkPict($act_article[25]);
//            $artikel['pict02']         = $this->_checkPict($act_article[26]);
//            $artikel['pict03']         = $this->_checkPict($act_article[27]);
//            $artikel['pict04']         = $this->_checkPict($act_article[28]);
//            $artikel['pict05']         = $this->_checkPict($act_article[29]);
//            $artikel['pict06']         = $this->_checkPict($act_article[30]);
//            $artikel['pict07']         = $this->_checkPict($act_article[31]);
//            $artikel['pict08']         = $this->_checkPict($act_article[32]);
//            $artikel['pict09']         = $this->_checkPict($act_article[33]);
//            $artikel['pict10']         = $this->_checkPict($act_article[34]);
//            $artikel['pict11']         = $this->_checkPict($act_article[35]);

            $artikel['pict01']         = $this->_checkPict($act_article[26]);
            $artikel['pict02']         = $this->_checkPict($act_article[27]);
            $artikel['pict03']         = $this->_checkPict($act_article[28]);
            $artikel['pict04']         = $this->_checkPict($act_article[29]);
            $artikel['pict05']         = $this->_checkPict($act_article[30]);
            $artikel['pict06']         = $this->_checkPict($act_article[31]);
            $artikel['pict07']         = $this->_checkPict($act_article[32]);
            $artikel['pict08']         = $this->_checkPict($act_article[33]);
            $artikel['pict09']         = $this->_checkPict($act_article[34]);
            $artikel['pict10']         = $this->_checkPict($act_article[35]);
            $artikel['pict11']         = $this->_checkPict($act_article[36]);
         }
         else {
//            $artikel['pict01']         = $this->_getPictFromUrl($act_article[25], true);
//            $artikel['pict02']         = $this->_getPictFromUrl($act_article[26]);
//            $artikel['pict03']         = $this->_getPictFromUrl($act_article[27]);
//            $artikel['pict04']         = $this->_getPictFromUrl($act_article[28]);
//            $artikel['pict05']         = $this->_getPictFromUrl($act_article[29]);
//            $artikel['pict06']         = $this->_getPictFromUrl($act_article[30]);
//            $artikel['pict07']         = $this->_getPictFromUrl($act_article[31]);
//            $artikel['pict08']         = $this->_getPictFromUrl($act_article[32]);
//            $artikel['pict09']         = $this->_getPictFromUrl($act_article[33]);
//            $artikel['pict10']         = $this->_getPictFromUrl($act_article[34]);
//            $artikel['pict11']         = $this->_getPictFromUrl($act_article[35]);

            $artikel['pict01']         = $this->_getPictFromUrl($act_article[25], true);
            $artikel['pict02']         = $this->_getPictFromUrl($act_article[26]);
            $artikel['pict03']         = $this->_getPictFromUrl($act_article[27]);
            $artikel['pict04']         = $this->_getPictFromUrl($act_article[28]);
            $artikel['pict05']         = $this->_getPictFromUrl($act_article[29]);
            $artikel['pict06']         = $this->_getPictFromUrl($act_article[30]);
            $artikel['pict07']         = $this->_getPictFromUrl($act_article[31]);
            $artikel['pict08']         = $this->_getPictFromUrl($act_article[32]);
            $artikel['pict09']         = $this->_getPictFromUrl($act_article[33]);
            $artikel['pict10']         = $this->_getPictFromUrl($act_article[34]);
            $artikel['pict11']         = $this->_getPictFromUrl($act_article[35]);
         }

         $artikel['staffelung']     = '';
//         $artikel['widerruf']       = $act_article[36];
         $artikel['widerruf']       = $act_article[37];
//         $artikel['lieferzeit']     = $act_article[37];
         $artikel['lieferzeit']     = $act_article[38];
         $artikel['masse_check']    = 'n';
         $artikel['masse_min']      = '1.00000';
         $artikel['masse_komma']    = '1';
         $artikel['grundeinheit']   = '';
//         $artikel['ge_netto_aktiv'] = 'n';
//         $artikel['ge_netto']       = '0.00000';
//         $artikel['gcat']           = $act_article[38];
//         $artikel['zustand']        = $act_article[39];
//         $artikel['marke']          = $act_article[40];
         $artikel['gcat']           = $act_article[39];
         $artikel['zustand']        = $act_article[40];
         $artikel['marke']          = $act_article[41];
         $artikel['grundeinheit']   = $act_article[42];
         $artikel['ge_netto_aktiv'] = $act_article[43];

         $this->_insertArticle($artikel, $overwrite, $haendler_id, $cronjob);
      }

      $preis = (float)str_replace(',', '.', $act_article[10]) / $steuerfaktor;
      $ang_preis = (float)str_replace(',', '.', $act_article[13]) / $steuerfaktor;
      $ge_netto = (float)(str_replace(',', '.', $act_article[15]) / $steuerfaktor);

      $variant = array();
      $variant['parent']         = $this->last_id;
      $variant['sort']           = $sort;
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
      $variant['ge_netto']       = $ge_netto;

      $this->_insertVariant($variant, $overwrite);
   } // for

   if ($errortext == '') {
      echo "<script>parent.Royalart.uploadDone('ok', ' ".$zeilen_c." Artikel/Varianten wurden importiert', 'checkok');</script>";
   }
   else {
      echo "<script>parent.Royalart.uploadDone('error', '".$errortext."', 'checkok');</script>";
   }
}
?>