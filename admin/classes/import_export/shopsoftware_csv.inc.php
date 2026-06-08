<?php
/*
###################################################################################
  Kanpai Classic Shopsoftware
  Entwicklungsstand: 07.03.2019 Version 7.2

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

   $pics = (int)$this->params->firma['count_pics'];
   $pic  = '';

   for ($p = 1; $p <= (int)$this->params->firma['count_pics']; $p++) {
      $pic .= " i.pict".sprintf('%02d', $p).",";
   }

   $haendler_sql = '';
   if (isset($haendler_id) && $haendler_id > 0) {
      $haendler_sql = "  AND haendler_id = $haendler_id ";
   }

   $sql = "SELECT i.id, ac.cat_id, i.steuersatz, i.name_$lang AS name, i.desc_$lang AS beschreibung, $pic i.sortierung,
                  i.versand_preis, i.gewicht, i.widerruf, i. lieferfrist, i.staffelung, i.marke, i.vpe, i.vpm, i.grundeinheit, i.ge_netto_aktiv,
                  a.id AS artikel_id, i.masse_check, i.masse_min, i.masse_komma, i.grundeinheit, i.ge_netto_aktiv, i.grundeinheit_rechner, i.rechner_check, rechner_mode,
                  c.name_deu AS catname,
                  g.categories AS gcat, g.zustand
              FROM #__articles_info AS i
           LEFT JOIN #__articles AS a
              ON i.id = a.parent_id
           LEFT JOIN #__article_to_cats AS ac
              ON ac.parent_id = i.id
           LEFT JOIN #__categories AS c
              ON c.id = ac.cat_id
           LEFT JOIN #__articles_to_googlecats AS g
              ON  g.parent_id = i.id
           WHERE a.sort = 1
              AND ac.sort = 0
              $haendler_sql
           ORDER BY i.id";

   $info = $this->db->queryAllObjects($sql);

   $csv = '';
   if ($config->csv_head == 'y') {
      // shop_article
      $head  = $wt.'sortierung'.$wt.$trenner.$wt.'id_v7'.$wt.$trenner.$wt.'variante'.$wt.$trenner.$wt.'art_nr'.$wt.$trenner.$wt.'gtin'.$wt.$trenner.$wt.'mpn'.$wt.$trenner;
      $head .= $wt.'online'.$wt.$trenner.$wt.'merkmal1'.$wt.$trenner.$wt.'wert1'.$wt.$trenner.$wt.'merkmal2'.$wt.$trenner.$wt.'wert2'.$wt.$trenner;
      $head .= $wt.'brutto'.$wt.$trenner.$wt.'haendler_netto'.$wt.$trenner.$wt.'angebot'.$wt.$trenner.$wt.'ang_brutto'.$wt.$trenner.$wt.'lager'.$wt.$trenner;
      $head .= $wt.'ge_brutto'.$wt.$trenner.$wt.'ge_faktor'.$wt.$trenner.$wt.'einheit'.$wt.$trenner.$wt.'einheit_aktiv'.$wt.$trenner;
// 15 Einträge
// 16 Einträge - ge_brutto hinzugefügt
// 17 Einträge - ge_menge hinzugefügt
// 18 Einträge - sortierung (aus info Pos 2)
// 20 Einträge - einheit und einheit aktiv hinzugefügt (Pos 19 / 20)


      // shop_article_info
      $head .= $wt.'name'.$wt.$trenner.$wt.'beschreibung'.$wt.$trenner.$wt.'cat_id'.$wt.$trenner.$wt.'cat_name'.$wt.$trenner;
      $head .= $wt.'ust_satz'.$wt.$trenner.$wt.'vers_brutto'.$wt.$trenner.$wt.'gewicht'.$wt.$trenner.$wt.'groesse'.$wt.$trenner.$wt.'vpe'.$wt.$trenner;
      $head .= $wt.'url'.$wt.$trenner;

      for ($p = 1; $p <= $pics; $p++) {
         $head .= $wt.'bild'.$p.$wt.$trenner;
      }

      $head .= $wt.'widerruf'.$wt.$trenner.$wt.'lieferzeit'.$wt.$trenner.$wt.'gcat'.$wt.$trenner.$wt.'zustand'.$wt.$trenner;
      $head .= $wt.'marke'.$wt.$trenner.CRLF;
// 26 Einträge
// 28 Einträge - grundeinheit, ge_aktiv hinzugefügt
// 35 Einträge - Sortierung, Bild12 -17 hinzugefügt

// 17 + 35 = 52 (Spalten)
      $csv .= $head;
   }

   for ($i = 0; $i < count($info); $i++) {
      // Bei Kleingewerbe Steuersatz3 (0%)
      if ($this->params->firma['kleingewerbe'] == 'y') {
         $info[$i]->steuersatz = 3;
      }
      $csv2 = '';
      $csv .= $wt.$info[$i]->sortierung.$wt.$trenner;
      $csv .= $wt.$info[$i]->id.$wt.$trenner;

      $sql = "SELECT a.*,
                     m1.merkmal_$lang AS mm_name1, m2.merkmal_$lang AS mm_name2, w1.wert_$lang AS w_name1, w2.wert_$lang AS w_name2, ge_netto, ge_menge
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
//         $variante  = $wt.$info[$i]->sortierung.$wt.$trenner;
         $variante  = $wt.$data[$d]->sort.$wt.$trenner;
         $variante .= $wt.$data[$d]->art_nr.$wt.$trenner;
         $variante .= $wt.$data[$d]->gtin.$wt.$trenner;
         $variante .= $wt.$data[$d]->mpn.$wt.$trenner;

         $variante .= $wt.$data[$d]->online.$wt.$trenner;
         $variante .= $wt.((int)$data[$d]->merkmal1 > 0 ? $data[$d]->mm_name1 : '').$wt.$trenner;
         $variante .= $wt.((int)$data[$d]->wert1 > 0 ? $data[$d]->w_name1 : '').$wt.$trenner;
         $variante .= $wt.((int)$data[$d]->merkmal2 > 0 ? $data[$d]->mm_name2 : '').$wt.$trenner;
         $variante .= $wt.((int)$data[$d]->wert2 > 0 ? $data[$d]->w_name2 : '').$wt.$trenner;

         $variante .= $wt.$brutto.$wt.$trenner;
         $variante .= $wt.$haendler_netto.$wt.$trenner;
         $variante .= $wt.$data[$d]->angebot_active.$wt.$trenner;
         $variante .= $wt.$ang_brutto.$wt.$trenner;
         $variante .= $wt.$data[$d]->menge.$wt.$trenner;

         $variante .= $wt.$ge_brutto.$wt.$trenner;
         $variante .= $wt.$data[$d]->ge_menge.$wt.$trenner;
         $variante .= $wt.$info[$i]->grundeinheit.$wt.$trenner;
         $variante .= $wt.$info[$i]->ge_netto_aktiv.$wt.$trenner;


         // Hauptartikel
         if ($d == 0) {
            $csv .= $variante;
         }

         // Varianten
         else {
            $csv2 .= $wt.$wt.$trenner;
            $csv2 .= $wt.$wt.$trenner.$variante;
            // Anzahl Felder aus article_info ohne id (26)
            // Anzahl Felder aus article_info ohne id, sortierung ge_netto, ge_netto_aktiv (23)
//            $csv2 .= $wt.$wt.$trenner. $wt.$wt.$trenner. $wt.$wt.$trenner. $wt.$wt.$trenner. $wt.$wt.$trenner;
            $csv2 .= $wt.$wt.$trenner. $wt.$wt.$trenner;
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

//      $csv .= $wt.$info[$i]->sortierung.$wt.$trenner;
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

      for ($p = 1; $p <= $pics; $p++) {
         $csv .= $wt . $this->_checkPict($info[$i]->{'pict' . sprintf('%02d', $p)}, $picurl) . $wt . $trenner;
      }

      $csv .= $wt.$info[$i]->widerruf.$wt.$trenner;
      $csv .= $wt.$info[$i]->lieferfrist.$wt.$trenner;

      if ($info[$i]->gcat != '') {
         $csv .= $wt.$tt.$info[$i]->gcat.$tt.$wt.$trenner;
      }

      else {
         $csv .= $wt.$info[$i]->gcat.$wt.$trenner;
      }

      $csv .= $wt.$info[$i]->zustand.$wt.$trenner;

//      $csv .= $wt.$info[$i]->marke.$wt.$trenner;
      $csv .= $wt.$info[$i]->marke.$wt.CRLF;
//      $csv .= $wt.$info[$i]->grundeinheit.$wt.$trenner;
//      $csv .= $wt.$info[$i]->ge_netto_aktiv.$wt.CRLF;

      $csv .= $csv2;
   }
}

/******* IMPORT ************************************************************************************/
if ($mode == 'import') {
   $pics = 17;

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
   $articles = [];

   $handle = fopen($file, 'r');
   $i = 0;
   $zeilen_c = 0;

   // Datei Zeilenweise einlesen und in Array umwandeln
   while($csvstring = fgets($handle)) {
      $act_article = [];
      $csvstring = rtrim($csvstring, "\n,\r");
      $zeilen_c++;
      $i++;

      $t = preg_match_all('#((".*?")|(.*?));#', $csvstring . ';', $p);

      // Werte korrigiert in Array einlesen
      foreach ($p[1] as $q) {
         $act_article[] = trim($q, '"');
      }

      $articles[] = $act_article;
   }

   // Verarbeiten
   $steuerfaktor = 1.0;
   $zeilen_c     = 0;

   for ($i = $start; $i < count($articles); $i++) {
      $zeilen_c++;
      $act_article = $articles[$i];

      // Titelzeile überspringen ?
      if ($act_article[1] == 'id' || $act_article[1] == $tt.'id'.$tt || $act_article[1] == 'id_v7' || $act_article[1] == $tt.'id_v7'.$tt) {
         continue;
      }

      // Artikel Sort für Reihenfolge Varianten
      $sort = ((int)$act_article[2] > 0) ? (int)$act_article[2] : 0;

      // Hauptartikel ?
      if ($act_article[1] != '') {
         $this->last_id = 0;
         $art_id = 0;

         if ($overwrite == 'y') {
            $art_id = (int)$act_article[1];
            $sort = 1;
         }

         // Steuersatz prüfen
         // Versandpreis in Netto umrechnen
         $steuerfaktor = 1.0;

         // Steuersatz
         while($csvstring = stream_get_line ($handle, 0, "\r\n")) {
            $act_article[24] = 1;
         }

         if ($config->imp_preis == 'brutto') {
            $steuerfaktor = (1 + $this->params->firma['tax'.(int)$act_article[24]] / 100);
         }

         // Versandkosten
         $versand  = (float)(str_replace(',', '.', $act_article[25]) / $steuerfaktor);

         $artikel = array();
         $artikel['id']             = $art_id;
         // Zur Suche nach Artikel-ID
         $artikel['art_nr']         = $act_article[2];
         $artikel['sortierung']     = $act_article[0];
         $artikel['name']           = $this->db->escape(str_replace('""', '"', $act_article[20]));
         $artikel['beschreibung']   = $this->db->escape(str_replace('""', '"', $act_article[21]));

         if ($extern) {
            $artikel['beschreibung']   = str_replace(array('\r\n', '\n'), '<br />', $artikel['beschreibung']);
         }

         $artikel['kategorie_id']   = $act_article[22];
         $artikel['kat_name']       = $act_article[23];
         $artikel['steuersatz']     = $act_article[24];
         $artikel['versand_preis']  = $versand;
         $artikel['gewicht']        = $act_article[26];
         $artikel['vpm']            = $act_article[27];
         $artikel['vpe']            = $act_article[28];
         // $url =                  = $act_article[29];

         $artikel['images'] = [];

         for ($p = 1; $p <= $pics; $p++) {
            if ($p == 1) {
               $artikel['imgage'] = $this->_checkPict($act_article[29 + $p], true);
            }

            else {
               $image = $this->_checkPict($act_article[29 + $p], true);

               if ($image != '') {
                  $artikel['images'][] = $image;
               }
         }

         $artikel['staffelung']     = '';
         $artikel['widerruf']       = $act_article[$pics + 30];   // $act_article[57]
         $artikel['lieferzeit']     = $act_article[$pics + 31];
         $artikel['masse_check']    = 'n';
         $artikel['masse_min']      = '1.00000';
         // 05.01.2018: Default auf 0 geändert
         // $artikel['masse_komma']    = '1';
         $artikel['masse_komma']    = '0';
         $artikel['gcat']           = $act_article[$pics + 32];
         $artikel['zustand']        = $act_article[$pics + 33];
         $artikel['marke']          = $act_article[$pics + 34];
         $artikel['grundeinheit']   = $act_article[18];
         $artikel['ge_netto_aktiv'] = $act_article[19];

         $this->_insertArticle($artikel, $overwrite, $catname, $cronjob, $haendler_id);
      }

      // Varianten
      $preis = (float)str_replace(',', '.', $act_article[11]) / $steuerfaktor;
      $ang_preis = (float)str_replace(',', '.', $act_article[14]) / $steuerfaktor;
      $ge_netto = (float)(str_replace(',', '.', $act_article[16]) / $steuerfaktor);

      $variant = array();
      $variant['parent']         = $this->last_id;
      $variant['sort']           = $sort;
      $variant['art_nr']         = $act_article[3];
      $variant['gtin']           = $act_article[4];
      $variant['mpn']            = $act_article[5];
      $variant['online']         = $act_article[6];
      $variant['mm_name1']       = $act_article[7];
      $variant['w_name1']        = $act_article[8];
      $variant['mm_name2']       = $act_article[9];
      $variant['w_name2']        = $act_article[10];
      $variant['netto']          = $preis;
      $variant['haendler_netto'] = $act_article[12];
      $variant['angebot_active'] = $act_article[13];
      $variant['angebot']        = $ang_preis;
      $variant['menge']          = $act_article[15];
      $variant['ge_netto']       = $ge_netto;
      $variant['ge_menge']       = $act_article[17];

      $this->_insertVariant($variant, $overwrite);
   } // for
   }

   if ($errortext == '') {
      echo "<script>parent.Royalart.uploadDone('ok', ' ".$zeilen_c." Artikel/Varianten wurden importiert', 'checkok');</script>";
   }

   else {
      echo "<script>parent.Royalart.uploadDone('error', '".$errortext."', 'checkok');</script>";
   }
}
