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

/******* EXPORT ************************************************************************************/
if ($mode == 'export') {
   $trenner = $config->trenner;
   $wt = $config->worttrenner;
   $sql = "SELECT i.id, ac.cat_id, i.steuersatz, i.name_$lang AS name, i.desc_$lang AS beschreibung,
                  i.pict01, i.pict02, i.pict03, i.pict04, i.pict05, i.pict06, i.pict07, i.pict08, i.pict09, i.pict10, i.pict11,
                  i.versand_preis, i.gewicht,
                  a.id AS artikel_id,
                  c.name_deu AS catname, i.widerruf, i.lieferfrist,
                  g.categories AS gcat, g.zustand, i.marke, a.gtin, a.mpn
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
              AND ac.sort = 0";
   $info = $this->db->queryAllObjects($sql);

   $csv = '';
   if ($config->csv_head == 'y') {
      // shop_article
      $head  = $wt.'id'.$wt.$trenner;
      $head .= $wt.'sort'.$wt.$trenner.$wt.'art_nr'.$wt.$trenner.$wt.'online'.$wt.$trenner.$wt.'merkmal1'.$wt.$trenner.$wt.'wert1'.$wt.$trenner.$wt.'merkmal2'.$wt.$trenner;
      $head .= $wt.'wert2'.$wt.$trenner.$wt.'brutto'.$wt.$trenner.$wt.'angebot'.$wt.$trenner.$wt.'ang_brutto'.$wt.$trenner.$wt.'menge'.$wt.$trenner;
      $head .= $wt.'online'.$wt.$trenner;
      // shop_article_info
      $head .= $wt.'name'.$wt.$trenner.$wt.'beschreibung'.$wt.$trenner.$wt.'cat_id'.$wt.$trenner.$wt.'cat_name'.$wt.$trenner.$wt.'ust_satz'.$wt.$trenner;
      $head .= $wt.'vers_netto'.$wt.$trenner.$wt.'gewicht'.$wt.$trenner.$wt.'url'.$wt.$trenner.$wt.'bild1'.$wt.$trenner.$wt.'bild2'.$wt.$trenner;
      $head .= $wt.'bild3'.$wt.$trenner.$wt.'bild4'.$wt.$trenner.$wt.'bild5'.$wt.$trenner.$wt.'bild6'.$wt.$trenner.$wt.'bild7'.$wt.$trenner;
      $head .= $wt.'bild8'.$wt.$trenner.$wt.'bild9'.$wt.$trenner.$wt.'bild10'.$wt.$trenner.$wt.'bild11'.$wt.$trenner.$wt.'widerruf'.$wt.$trenner;
      $head .= $wt.'lieferzeit'.$wt.$trenner.$wt.'gcat'.$wt.$trenner.$wt.'zustand'.$wt.$trenner.$wt.'marke'.$wt.$trenner.$wt.'gtin'.$wt.$trenner;
      $head .= $wt.'mpn'.$wt.CRLF;
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
                     m1.merkmal_$lang AS mm_name1, m2.merkmal_$lang AS mm_name2, w1.wert_$lang AS w_name1, w2.wert_$lang AS w_name2
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
         $brutto = number_format(round((float)$data[$d]->netto * (1 + $this->params->firma['tax'.(int)$info[$i]->steuersatz] / 100), 2), 2, '.', '');
         $ang_brutto = number_format(round((float)$data[$d]->angebot * (1 + $this->params->firma['tax'.(int)$info[$i]->steuersatz] / 100), 2), 2, '.', '');

         $variante  = $wt.$data[$d]->sort.$wt.$trenner;
         $variante .= $wt.$data[$d]->art_nr.$wt.$trenner;
         $variante .= $wt.$data[$d]->online.$wt.$trenner;
         $variante .= $wt.$data[$d]->mm_name1.$wt.$trenner;
         $variante .= $wt.$data[$d]->w_name1.$wt.$trenner;
         $variante .= $wt.$data[$d]->mm_name2.$wt.$trenner;

         $variante .= $wt.$data[$d]->w_name2.$wt.$trenner;
         $variante .= $wt.$brutto.$wt.$trenner;
         $variante .= $wt.$data[$d]->angebot_active.$wt.$trenner;
         $variante .= $wt.$ang_brutto.$wt.$trenner;
         $variante .= $wt.$data[$d]->menge.$wt.$trenner;

         $variante .= $wt.$data[$d]->online.$wt.$trenner;

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
            $csv2 .= $wt.$wt.CRLF;
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

      $csv .= $wt.$info[$i]->name.$wt.$trenner;
      $csv .= $wt.$beschreibung.$wt.$trenner;
      $csv .= $wt.$info[$i]->cat_id.$wt.$trenner;
      $csv .= $wt.$info[$i]->catname.$wt.$trenner;
      $csv .= $wt.$info[$i]->steuersatz.$wt.$trenner;

      $csv .= $wt.$vers_brutto.$wt.$trenner;
      $csv .= $wt.$info[$i]->gewicht.$wt.$trenner;
// deu_ im Link     $csv .= $wt.$shopurl.(defined('CONF_USE_HTACCESS') ? '' : '/index.php').'/deu_'.$info[$i]->artikel_id.'/'.urldecode($info[$i]->name).$wt.$trenner;
      $csv .= $wt.$shopurl.(defined('CONF_USE_HTACCESS') ? '' : '/index.php').'/'.$info[$i]->artikel_id.'/'.urldecode($info[$i]->name).$wt.$trenner;
      $csv .= $wt.($info[$i]->pict01 == '' || $info[$i]->pict01 == 'nopic.png' ? '' : (strstr($info[$i]->pict01, 'http') !== false ? $info[$i]->pict01 : $picurl.'/'.CONF_PICT_PATH.$info[$i]->pict01.'.jpg')).$wt.$trenner;
      $csv .= $wt.($info[$i]->pict02 == '' || $info[$i]->pict02 == 'nopic.png' ? '' : (strstr($info[$i]->pict02, 'http') !== false ? $info[$i]->pict02 : $picurl.'/'.CONF_PICT_PATH.$info[$i]->pict02.'.jpg')).$wt.$trenner;

      $csv .= $wt.($info[$i]->pict03 == '' || $info[$i]->pict03 == 'nopic.png' ? '' : (strstr($info[$i]->pict03, 'http') !== false ? $info[$i]->pict03 : $picurl.'/'.CONF_PICT_PATH.$info[$i]->pict03.'.jpg')).$wt.$trenner;
      $csv .= $wt.($info[$i]->pict04 == '' || $info[$i]->pict04 == 'nopic.png' ? '' : (strstr($info[$i]->pict04, 'http') !== false ? $info[$i]->pict04 : $picurl.'/'.CONF_PICT_PATH.$info[$i]->pict04.'.jpg')).$wt.$trenner;
      $csv .= $wt.($info[$i]->pict05 == '' || $info[$i]->pict05 == 'nopic.png' ? '' : (strstr($info[$i]->pict05, 'http') !== false ? $info[$i]->pict05 : $picurl.'/'.CONF_PICT_PATH.$info[$i]->pict05.'.jpg')).$wt.$trenner;
      $csv .= $wt.($info[$i]->pict06 == '' || $info[$i]->pict06 == 'nopic.png' ? '' : (strstr($info[$i]->pict06, 'http') !== false ? $info[$i]->pict06 : $picurl.'/'.CONF_PICT_PATH.$info[$i]->pict06.'.jpg')).$wt.$trenner;
      $csv .= $wt.($info[$i]->pict07 == '' || $info[$i]->pict07 == 'nopic.png' ? '' : (strstr($info[$i]->pict07, 'http') !== false ? $info[$i]->pict07 : $picurl.'/'.CONF_PICT_PATH.$info[$i]->pict07.'.jpg')).$wt.$trenner;

      $csv .= $wt.($info[$i]->pict08 == '' || $info[$i]->pict08 == 'nopic.png' ? '' : (strstr($info[$i]->pict08, 'http') !== false ? $info[$i]->pict08 : $picurl.'/'.CONF_PICT_PATH.$info[$i]->pict08.'.jpg')).$wt.$trenner;
      $csv .= $wt.($info[$i]->pict09 == '' || $info[$i]->pict09 == 'nopic.png' ? '' : (strstr($info[$i]->pict09, 'http') !== false ? $info[$i]->pict09 : $picurl.'/'.CONF_PICT_PATH.$info[$i]->pict09.'.jpg')).$wt.$trenner;
      $csv .= $wt.($info[$i]->pict10 == '' || $info[$i]->pict10 == 'nopic.png' ? '' : (strstr($info[$i]->pict10, 'http') !== false ? $info[$i]->pict10 : $picurl.'/'.CONF_PICT_PATH.$info[$i]->pict10.'.jpg')).$wt.$trenner;
      $csv .= $wt.($info[$i]->pict11 == '' || $info[$i]->pict11 == 'nopic.png' ? '' : (strstr($info[$i]->pict11, 'http') !== false ? $info[$i]->pict11 : $picurl.'/'.CONF_PICT_PATH.$info[$i]->pict11.'.jpg')).$wt.$trenner;
      $csv .= $wt.$info[$i]->widerruf.$wt.$trenner;

      $csv .= $wt.$info[$i]->lieferfrist.$wt.$trenner;
      $csv .= $wt.$info[$i]->gcat.$wt.$trenner;
      $csv .= $wt.$info[$i]->zustand.$wt.$trenner;
      $csv .= $wt.$info[$i]->marke.$wt.$trenner;
      $csv .= $wt.$info[$i]->gtin.$wt.$trenner;

      $csv .= $wt.$info[$i]->mpn.$wt.$trenner.CRLF;

      $csv .= $csv2;
   }
}

/******* IMPORT ************************************************************************************/
if ($mode == 'import') {
}

?>