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

/* Format Gambio 2.0 export
 0 - XTSOL                    muss
 1 - p_id                     artikel-id
 2 - p_model                  art_nr
 3 - p_stock                  Menge
 4 - p_sorting                Sortierung
 5 - p_startpage              1/0
 6 - p_startpage_sort         n/r
 7 - p_shipping               1
 8 - p_tpl                    default
 9 - p_opttpl                 default / product_options_dropdown.html

10 - p_manufacturer           id
11 - p_fsk18                  0 / 1
12 - p_priceNoTax             Netto
13 - p_priceNoTax.1
14 - p_priceNoTax.2
15 - p_priceNoTax.3
16 - p_tax                    1
17 - p_status                 -> online: 1 -> 'y', 0 -> 'n'
18 - p_weight                 -> gewicht
19 - vp_ean                   ->

20 - p_disc                   -> ????
21 - p_date_added             ->
22 - p_last_modified          ->
23 - p_date_available         n/r
24 - p_ordered                verkauft
25 - nc_ultra_shipping_costs  -> ind. versandkosten
26 - gm_show_date_added       0
27 - gm_show_price_offer      0
28 - gm_show_weight           0
29 - gm_show_qty_info         0 / 1 ???

30 - m_price_status           0
31 - gm_min_order             1.0000
32 - gm_graduated_qty         1.0000 (Staffelpreise)
33 - gm_options_template      default / product_options_dropdown.html
34 - p_vpe                    0
35 - p_vpe_status             0
36 - p_vpe_value              0.0000
37 - p_image.1                Name
38 - p_image.2                Name
39 - p_image.3                Name

40 - p_image.4                Name
41 - p_image.5                Name
42 - p_image.6                Name
43 - p_image.7                Name
44 - p_image                  Name
45 - p_name.en                Name-eng
46 - p_desc.en                Beschr-eng
47 - p_shortdesc.en           n/r
48 - p_meta_title.en          TITLE-eng
49 - p_meta_desc.en           META-eng

50 - p_meta_key.en            ???
51 - p_keywords.en            Key-eng
52 - p_url.en
53 - gm_url_keywords.en
54 - p_name.de                Name-deu
55 - p_desc.de
56 - p_shortdesc.de
57 - p_meta_title.de
58 - p_meta_desc.de
59 - p_meta_key.de

60 - p_keywords.de
61 - p_url.de
62 - gm_url_keywords.de
63 - p_cat.0                  Kategorie-Name
64 - p_cat.1
65 - p_cat.2
66 - p_cat.3
67 - p_cat.4
68 - p_cat.5
*/

// Anzahl Spalten in CSV-Datei
$array_count = 69;

//******* EXPORT ************************************************************************************/
if ($mode == 'export') {
   $trenner = $config->trenner;
   $wt = $config->worttrenner;
   $datum = date('Y-m-d H:m:s');
   $csv = '';

   if ($config->csv_head == 'y') {
      $head  = $wt.'XTSOL'.$wt.$trenner.$wt.'p_id'.$wt.$trenner.$wt.'p_model'.$wt.$trenner.$wt.'p_stock'.$wt.$trenner.$wt.'p_sorting'.$wt.$trenner;
      $head .= $wt.'p_startpage'.$wt.$trenner.$wt.'p_startpage_sort'.$wt.$trenner.$wt.'p_shipping'.$wt.$trenner.$wt.'p_tpl'.$wt.$trenner.$wt.'p_opttpl'.$wt.$trenner;
      $head .= $wt.'p_manufacturer'.$wt.$trenner.$wt.'p_fsk18'.$wt.$trenner.$wt.'p_priceNoTax'.$wt.$trenner.$wt.'p_priceNoTax 1'.$wt.$trenner.$wt.'p_priceNoTax 2'.$wt.$trenner;
      $head .= $wt.'p_priceNoTax.3'.$wt.$trenner.$wt.'p_tax'.$wt.$trenner.$wt.'p_status'.$wt.$trenner.$wt.'p_weight'.$wt.$trenner.$wt.'vp_ean'.$wt.$trenner;
      $head .= $wt.'p_disc'.$wt.$trenner.$wt.'p_date_added'.$wt.$trenner.$wt.'p_last_modified'.$wt.$trenner.$wt.'p_date_available'.$wt.$trenner.$wt.'p_ordered'.$wt.$trenner;
      $head .= $wt.'nc_ultra_shipping_costs'.$wt.$trenner.$wt.'gm_show_date_added'.$wt.$trenner.$wt.'gm_show_price_offer'.$wt.$trenner.$wt.'gm_show_weight'.$wt.$trenner.$wt.'gm_show_qty_info'.$wt.$trenner;
      $head .= $wt.'m_price_status'.$wt.$trenner.$wt.'gm_min_order'.$wt.$trenner.$wt.'gm_graduated_qty'.$wt.$trenner.$wt.'gm_options_template'.$wt.$trenner.$wt.'p_vpe'.$wt.$trenner;
      $head .= $wt.'p_vpe_status'.$wt.$trenner.$wt.'p_vpe_value'.$wt.$trenner.$wt.'p_image.1'.$wt.$trenner.$wt.'p_image.2'.$wt.$trenner.$wt.'p_image.3'.$wt.$trenner;
      $head .= $wt.'p_image 4'.$wt.$trenner.$wt.'p_image 5'.$wt.$trenner.$wt.'p_image 6'.$wt.$trenner.$wt.'p_image 7'.$wt.$trenner.$wt.'p_image'.$wt.$trenner;
      $head .= $wt.'p_name.en'.$wt.$trenner.$wt.'p_desc.en'.$wt.$trenner.$wt.'p_shortdesc.en'.$wt.$trenner.$wt.'p_meta_title.en'.$wt.$trenner.$wt.'p_meta_desc.en'.$wt.$trenner;
      $head .= $wt.'p_meta_key.en'.$wt.$trenner.$wt.'p_keywords.en'.$wt.$trenner.$wt.'p_url.en'.$wt.$trenner.$wt.'gm_url_keywords.en '.$wt.$trenner.$wt.'p_name.de'.$wt.$trenner;
      $head .= $wt.'p_desc.de'.$wt.$trenner.$wt.'p_shortdesc.de'.$wt.$trenner.$wt.'p_meta_title.de'.$wt.$trenner.$wt.'p_meta_desc.de'.$wt.$trenner.$wt.'p_meta_key.de'.$wt.$trenner;
      $head .= $wt.'p_keywords.de'.$wt.$trenner.$wt.'p_url.de'.$wt.$trenner.$wt.'gm_url_keywords.de'.$wt.$trenner.$wt.'p_cat.0'.$wt.$trenner.$wt.'p_cat.1'.$wt.$trenner;
      $head .= $wt.'p_cat.2'.$wt.$trenner.$wt.'p_cat.3'.$wt.$trenner.$wt.'p_cat.4'.$wt.$trenner.$wt.'p_cat.5'.$wt.CRLF;

      $csv .= $head;
   }

   $sql = "SELECT i.id, ac.cat_id, i.steuersatz, i.name_$lang AS name, i.desc_$lang AS beschreibung, i.image,
                  i.versand_preis, i.gewicht, i.sortierung,
                  a.id AS artikel_id,
                  c.name_deu AS catname, i.widerruf, i.lieferfrist,
                  g.categories AS gcat, g.zustand, i.marke, a.gtin, a.mpn
              FROM shop_articles_info AS i
           LEFT JOIN #__articles AS a
              ON i.id = a.parent_id
           LEFT JOIN #__article_to_cats AS ac
              ON ac.parent_id = i.id
           LEFT JOIN #__categories AS c
              ON c.id = ac.cat_id
           LEFT JOIN shop_articles_to_googlecats AS g
              ON  g.parent_id = i.id
           WHERE a.sort = 1
              AND ac.sort = 0
           ORDER BY i.id";
   $info = $this->db->queryAllObjects($sql);

   for ($i = 0; $i < count($info); $i++) {
      $versand_preis = (float)$info[$i]->versand_preis;

      if ($config->exp_preis == 'brutto') {
         $versand_preis *= (1 + $this->params->firma['tax'.(int)$info[$i]->steuersatz] / 100);
      }

      $versand_preis = number_format(round($versand_preis, $config->exp_stellen), $config->exp_stellen, $config->exp_separator, $config->exp_sep1000);

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

      $data = $this->db->queryAllObjects($sql);

      for ($d = 0; $d < (is_array($data) ?count($data) : 0); $d++) {
         $images = $this->db->queryAllObjects("SELECT image FROM #__articles_images WHERE parent_id = ".$info[$i]->id." ORDER BY sort");
         $preis           = (float)$data[$d]->netto;

         if ($config->exp_preis == 'brutto') {
            $preis *= (1 + $this->params->firma['tax'.(int)$info[$i]->steuersatz] / 100);
         }

         $preis           = number_format(round($preis, $config->exp_stellen), $config->exp_stellen, $config->exp_separator, $config->exp_sep1000);
         $s_preis         = (float)$data[$d]->angebot;

         if ($config->exp_preis == 'brutto') {
            $s_preis *= (1 + $this->params->firma['tax'.(int)$info[$i]->steuersatz] / 100);
         }

         $s_preis         = number_format(round($s_preis, $config->exp_stellen), $config->exp_stellen, $config->exp_separator, $config->exp_sep1000);
         $name            = str_replace($wt, $wt.$wt, $info[$i]->name);
         $beschreibung    = '';

         if ($config->html == 'text') {
            $beschreibung = $this->_html2txt(($info[$i]->beschreibung));
         }
         else {
            $beschreibung = str_replace(CR, '', nl2br($info[$i]->beschreibung));
         }

         $csv .= $wt.'XTSOL'.$wt.$trenner;               // XTSOL
         $csv .= $wt.$info[$i]->id.$wt.$trenner;         // p_id
         $csv .= $wt.$data[$d]->art_nr.$wt.$trenner;     // p_model
         $csv .= $wt.$data[$d]->menge.$wt.$trenner;      // p_stock
         $csv .= $wt.$info[$i]->sortierung.$wt.$trenner; // p_sorting
         $csv .= $wt.'0'.$wt.$trenner;                   // p_startpage
         $csv .= $wt.''.$wt.$trenner;                    // p_startpage_sort
         $csv .= $wt.'1'.$wt.$trenner;                   // p_shipping
         $csv .= $wt.'default'.$wt.$trenner;             // p_opttpl
         $csv .= $wt.'default'.$wt.$trenner;             //

         $csv .= $wt.''.$wt.$trenner;        // p_manufacturer
         $csv .= $wt.'0'.$wt.$trenner;       // p_fsk18
         $csv .= $wt.$preis.$wt.$trenner;    // p_priceNoTax
         $csv .= $wt.''.$wt.$trenner;        // p_priceNoTax.1
         $csv .= $wt.''.$wt.$trenner;        // p_priceNoTax.2
         $csv .= $wt.''.$wt.$trenner;        // p_priceNoTax.3
         $csv .= $wt.'1'.$wt.$trenner;       // p_tax
         $csv .= $wt.($data[$d]->online == 'y' ? 1 : 0).$wt.$trenner;        // p_status
         $csv .= $wt.$info[$i]->gewicht.$wt.$trenner;        // p_weight
         $csv .= $wt.$info[$i]->gtin.$wt.$trenner;        // vp_ean

         $csv .= $wt.$s_preis.$wt.$trenner;        // p_disc -> Sonderpreis ???
         $csv .= $wt.$datum.$wt.$trenner;        // p_date_added
         $csv .= $wt.$datum.$wt.$trenner;        // p_last_modified
         $csv .= $wt.''.$wt.$trenner;        // p_date_available
         $csv .= $wt.'0.0000'.$wt.$trenner;        // p_ordered
         $csv .= $wt.$versand_preis.$wt.$trenner;        // nc_ultra_shipping_costs
         $csv .= $wt.'0'.$wt.$trenner;        // gm_show_date_added
         $csv .= $wt.'0'.$wt.$trenner;        // gm_show_price_offer
         $csv .= $wt.'0'.$wt.$trenner;        // gm_show_weight
         $csv .= $wt.'0'.$wt.$trenner;        // gm_show_qty_info

         $csv .= $wt.'0'.$wt.$trenner;        // m_price_status
         $csv .= $wt.'1.0000'.$wt.$trenner;        // gm_min_order
         $csv .= $wt.'1.0000'.$wt.$trenner;        // gm_graduated_qty
         $csv .= $wt.'default'.$wt.$trenner;        // gm_options_template
         $csv .= $wt.'0'.$wt.$trenner;        // p_vpe
         $csv .= $wt.'0'.$wt.$trenner;        // p_vpe_status
         $csv .= $wt.'0.000'.$wt.$trenner;        // p_vpe_value

         for ($p = 0; $p < 7; $p++) {
            $csv .= $wt.$this->_checkPict((isset($images[$p]->image) ? $images[$p]->image : ''), $picurl).$wt.$trenner;        // p_image.1 - 7
         }

         $csv .= $wt.$this->_checkPict($info[$i]->image, $picurl).$wt.$trenner;        // p_image
         $csv .= $wt.''.$wt.$trenner;        // p_name.en
         $csv .= $wt.''.$wt.$trenner;        // p_desc.en
         $csv .= $wt.''.$wt.$trenner;        // p_shortdesc.en
         $csv .= $wt.''.$wt.$trenner;        // p_meta_title.en
         $csv .= $wt.''.$wt.$trenner;        // p_meta_desc.en

         $csv .= $wt.''.$wt.$trenner;        // p_meta_key.en
         $csv .= $wt.''.$wt.$trenner;        // p_keywords.en
         $csv .= $wt.''.$wt.$trenner;        // p_url.en
         $csv .= $wt.''.$wt.$trenner;        // gm_url_keywords.en
         $csv .= $wt.$name.$wt.$trenner;        // p_name.de
         $csv .= $wt.$beschreibung.$wt.$trenner;        // p_desc.de
         $csv .= $wt.$name.$wt.$trenner;        // p_shortdesc.de
         $csv .= $wt.''.$wt.$trenner;        // p_meta_title.de
         $csv .= $wt.''.$wt.$trenner;        // p_meta_desc.de
         $csv .= $wt.''.$wt.$trenner;        // p_meta_key.de

         $csv .= $wt.''.$wt.$trenner;        // p_keywords.de
// deu_im Link         $csv .= $wt.$shopurl.(defined('CONF_USE_HTACCESS') ? '' : '/index.php').'/deu_'.$info[$i]->id.'/'.urldecode($info[$i]->name).$wt.$trenner;        // p_url.de
         $csv .= $wt.$shopurl.(defined('CONF_USE_HTACCESS') ? '' : '/index.php').'/'.$info[$i]->id.'/'.urldecode($info[$i]->name).$wt.$trenner;        // p_url.de
         $csv .= $wt.''.$wt.$trenner;        // gm_url_keywords.de
         $csv .= $wt.$info[$i]->catname.$wt.$trenner;        // p_cat.0
         $csv .= $wt.''.$wt.$trenner;        // p_cat.1
         $csv .= $wt.''.$wt.$trenner;        // p_cat.2
         $csv .= $wt.''.$wt.$trenner;        // p_cat.3
         $csv .= $wt.''.$wt.$trenner;        // p_cat.4
         $csv .= $wt.''.$wt;        // p_cat.5
         $csv .= CRLF;
      }
   }
   $csv = iconv("UTF-8", "Windows-1252", $csv);
}


//******* IMPORT ************************************************************************************/
if ($mode == 'import') {


$start = 0;
   $zeilen_c = $start;
   $errortext = '';
   $trenner = $config->trenner;
   $wt = $config->worttrenner;
   $tt = $wt;
   if ($wt == '') {
      $tt = '"';
   }

   $articles = file($file);
   $art_kat = 0;
   $trenner = $config->trenner;
   $old_id = '';
   $kat_id = 0;
   $parent_id = 0;
   $sort = 0;

   for ($i = $start; $i <count($articles); $i++) {
      $zeilen_c++;
      $zeile = trim($articles[$i], "\n,\r");
      $act_article = explode($trenner, $zeile);

      for ($a = 0; $a < count($act_article); $a++) {
         $act_article[$a] = trim($act_article[$a], "\"'");
      }

      if (count($act_article) != $array_count && count($act_article) != ($array_count + 1) ) {
         $errortext = "Anzahl Spalten ist falsch! Statt $array_count oder ".($array_count +1)." wurden ".count($act_article).' gefunden in Zeile '.$zeilen_c;
         $zeilen_c--;
         break;
      }
if ($i == 0) { continue; }
      $preis = (float)$act_article[12];
      $s_preis = (float)$act_article[20];
      $versand_preis = (float)$act_article[20];
      if ($config->imp_preis == 'brutto') {
         $preis = $preis * (1 + $this->params->firma['tax'.(int)$artikel['steuersatz']] / 100);
         $s_preis = $s_preis * (1 + $this->params->firma['tax'.(int)$artikel['steuersatz']] / 100);
         $versand_preis = $versand_preis * (1 + $this->params->firma['tax'.(int)$artikel['steuersatz']] / 100);
      }

      $org_id = $act_article[1];
      // ID akt. Artikel mit letzem Artikel vergleichen
      if ($org_id != $old_id) {
         $old_id = $org_id;
         $parent_id = 0;
         $sort = 1;
         // Artklel in DB suchen, wenn er überschrieben werden soll
         if ($overwrite == 'y') {
            $parent_id = $this->_getArtIdByArtNr($act_article[1]);
            $kat_id = $this->_getArtKatByArtId($act_article[1]);
         }

         $artikel['id']             = $parent_id;
         $artikel['name']           = utf8_encode($this->db->escape($act_article[54]));
         $artikel['beschreibung']   = utf8_encode($this->db->escape($act_article[55]));
//         $artikel['kategorie_id']   = $art_kat;
         $artikel['kategorie_id']   = 1;
         $artikel['steuersatz']     = $act_article[16];
         $artikel['versand_preis']  = $versand_preis;
         $artikel['gewicht']        = $act_article[18];
         $artikel['pict01']         = $act_article[44];
         $artikel['pict02']         = $this->_getPictFromUrl($act_article[37]);
         $artikel['pict03']         = $this->_getPictFromUrl($act_article[38]);
         $artikel['pict04']         = $this->_getPictFromUrl($act_article[39]);
         $artikel['pict05']         = $this->_getPictFromUrl($act_article[40]);
         $artikel['pict06']         = $this->_getPictFromUrl($act_article[41]);
         $artikel['pict07']         = $this->_getPictFromUrl($act_article[42]);
         $artikel['pict08']         = $this->_getPictFromUrl($act_article[43]);
         $artikel['pict09']         = 'nopic.png';
         $artikel['pict10']         = 'nopic.png';
         $artikel['pict11']         = 'nopic.png';
         $artikel['staffelung']     = '';
         $artikel['widerruf']       = 1;
         $artikel['lieferzeit']     = 2;
         $artikel['masse_check']    = 'n';
         $artikel['masse_min']      = '1.00000';
         $artikel['masse_komma']    = '1';
         $artikel['grundeinheit']   = '';
         $artikel['ge_netto_aktiv'] = 'n';
         $artikel['ge_netto']       = '0.00000';
         $artikel['gtin']           = $act_article[19];
         $artikel['gcat']           = '';
         $artikel['zustand']        = '';
         $artikel['marke']          = '';
         $artikel['mpn']            = '';

         $this->_insertArticle($artikel, $overwrite, $catname, $cronjob, $haendler_id);
      }

      // Eintrag in articles
      $variant = array();
      $variant['parent']         = $parent_id;
      $variant['sort']           = $sort;
      $variant['art_nr']         = $act_article[2];
      $variant['mm_name1']       = 0;
      $variant['w_name1']        = 0;
      $variant['mm_name2']       = 0;
      $variant['w_name2']        = 0;
      $variant['netto']          = $preis;
      $variant['angebot_active'] = 'n';
      $variant['angebot']        = $s_preis;
      $variant['menge']          = $act_article[3];
      $variant['online']         = ((int)$act_article[17] == 1 ? 'y' : 'n');
      $this->_insertVariant($variant);
      $sort++;
   }

   $zeilen_c -= $start;
   if ($errortext == '') {
      echo "<script>parent.Royalart.uploadDone('ok', ' ".$zeilen_c." Artikel wurden importiert', 'checkok');</script>";
   }
   else {
      echo "<script>parent.Royalart.uploadDone('error', '".$errortext."', 'checkok');</script>";
   }
}
?>