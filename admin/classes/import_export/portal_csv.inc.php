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

// Kopie von shopsoftware_csv.inc.php #
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
/*
   $pics = (int)$this->params->firma['count_pics'];
   $pic  = '';

   for ($p = 1; $p <= (int)$this->params->firma['count_pics']; $p++) {
      $pic .= " i.pict".sprintf('%02d', $p).",";
   }
*/
   $haendler_sql = '';
   if (isset($haendler_id) && $haendler_id > 0) {
      $haendler_sql = "  AND haendler_id = $haendler_id ";
   }

   $sql = "SELECT i.id, ac.cat_id, i.steuersatz, i.name_$lang AS name, i.desc_$lang AS beschreibung, i.image, i.sortierung,
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
      $head  = $wt.'sortierung'.$wt.$trenner.$wt.'id_v7'.$wt.$trenner.$wt.'variante'.$wt.$trenner.$wt.'art_nr'.$wt.$trenner.$wt.'gtin'.$wt.$trenner;
      $head .= $wt.'mpn'.$wt.$trenner.$wt.'online'.$wt.$trenner.$wt.'merkmal1'.$wt.$trenner.$wt.'wert1'.$wt.$trenner.$wt.'merkmal2'.$wt.$trenner;
      $head .= $wt.'wert2'.$wt.$trenner.$wt.'brutto'.$wt.$trenner.$wt.'haendler_netto'.$wt.$trenner.$wt.'angebot'.$wt.$trenner.$wt.'ang_brutto'.$wt.$trenner;
      $head .= $wt.'lager'.$wt.$trenner.$wt.'ge_brutto'.$wt.$trenner.$wt.'ge_faktor'.$wt.$trenner.$wt.'einheit'.$wt.$trenner.$wt.'einheit_aktiv'.$wt.$trenner;
// 20 Einträge - einheit und einheit aktiv hinzugefügt (Pos 19 / 20)


      // shop_article_info
      $head .= $wt.'name'.$wt.$trenner.$wt.'beschreibung'.$wt.$trenner.$wt.'cat_id'.$wt.$trenner.$wt.'cat_name'.$wt.$trenner.$wt.'ust_satz'.$wt.$trenner;
      $head .= $wt.'vers_brutto'.$wt.$trenner.$wt.'gewicht'.$wt.$trenner.$wt.'groesse'.$wt.$trenner.$wt.'vpe'.$wt.$trenner.$wt.'url'.$wt.$trenner;
// 10 Einträge
      for ($p = 1; $p <= 10; $p++) {
         $head .= $wt.'bild'.$p.$wt.$trenner;
      }
// 17 Einträge

      $head .= $wt.'widerruf'.$wt.$trenner.$wt.'lieferzeit'.$wt.$trenner.$wt.'gcat'.$wt.$trenner.$wt.'zustand'.$wt.$trenner.$wt.'marke'.$wt.CRLF;
//  5 Einträge

// 52 Einträge / Spalten
      $csv .= $head;
   }

   for ($i = 0; $i < (is_array($info) ? count($info) : 0); $i++) {
      $images = $this->db->queryAllObjects("SELECT image FROM #__articles_images WHERE parent_id = ".$info[$i]->id." ORDER BY sort");

      // Bei Kleingewerbe Steuersatz3 (0%)
      if ($this->params->firma['kleingewerbe'] == 'y') {
         $info[$i]->steuersatz = 3;
      }
      $csv2 = '';
      $csv .= $wt.$info[$i]->sortierung.$wt.$trenner;
      $csv .= $wt.$info[$i]->id.$wt.$trenner;

      // Varianten einlesen
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
      $csv .= $wt . $this->_checkPict($info[$i]->image, $picurl) . $wt . $trenner;

      for ($p = 0; $p < 9; $p++) {
         $csv .= $wt . (isset($images[$p]->image) ? $this->_checkPict($images[$p]->image, $picurl) : '') . $wt . $trenner;
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
      $csv .= $wt.$info[$i]->marke.$wt.CRLF;
      $csv .= $csv2;
   }
}

/******* IMPORT ************************************************************************************/
