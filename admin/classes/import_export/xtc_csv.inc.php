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

/* Format xtc
 0 - intern-nr
 1 - artnr
 2 - product-name
 3 - imagename
 4 - shortdesc

 5 - product-description
 6 - size
 7 - color
 8 - variationname
 9 - parent-child

10 - parent-intern-nr
11 - relationship-type
12 - variation-theme
13 - main-image-url
14 - shipping-weight

15 - item-price
16 - currency
17 - products_status
18 - categorie
19 - manufacturer
20 - recommended_retail_price Brutto!
21 - attrib_agio
*/

/******* EXPORT ************************************************************************************/
if ($mode == 'export') {
   $trenner = $config->trenner;
   $wt = $config->worttrenner;

   $csv = '';
   if ($config->csv_head == 'y') {
      $head  = $wt.'intern-nr'.$wt.$trenner.$wt.'artnr'.$wt.$trenner.$wt.'product-name'.$wt.$trenner.$wt.'imagename'.$wt.$trenner.$wt.'shortdesc'.$wt.$trenner;
      $head .= $wt.'product-description'.$wt.$trenner.$wt.'size'.$wt.$trenner.$wt.'color'.$wt.$trenner.$wt.'variationname'.$wt.$trenner.$wt.'parent-child'.$wt.$trenner;
      $head .= $wt.'parent-intern-nr'.$wt.$trenner.$wt.'relationship-type'.$wt.$trenner.$wt.'variation-theme'.$wt.$trenner.$wt.'main-image-url'.$wt.$trenner.$wt.'shipping-weight'.$wt.$trenner;
      $head .= $wt.'item-price'.$wt.$trenner.$wt.'currency'.$wt.$trenner.$wt.'products_status'.$wt.$trenner.$wt.'categorie'.$wt.$trenner.$wt.'manufacturer'.$wt.$trenner;
      $head .= $wt.'recommended_retail_price'.$wt.$trenner.$wt.'attrib_agio'.$wt;
      $head .= CRLF;
      $csv .= $head;
   }

   // Article-Info lesen
   $sql = "SELECT i.id, ac.cat_id, i.steuersatz, i.name_$lang AS name, i.desc_$lang AS beschreibung, i.image, i.versand_preis, i.gewicht,
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

   for ($i = 0; $i < count($info); $i++) {
      // Varianten dazu aus Articles
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

      $anzahl = ($data ? count($data) : 0);
      $images = $this->db->queryAllObjects("SELECT image FROM #__articles_images WHERE parent_id = ".$info[$i]->id." ORDER BY sort");

      // nur Hauptartikel
      if ($anzahl == 1) {
         $d = 0;

         $name = str_replace($wt, $wt.$wt, $info[$i]->name);
         $beschreibung = '';

         if ($config->html == 'text') {
            $beschreibung = $this->_html2txt(($info[$i]->beschreibung));
         }

         else {
            $beschreibung = str_replace(CR, '', nl2br($info[$i]->beschreibung));
         }

         $beschreibung = str_replace($wt, $wt.$wt, $beschreibung);
         $preis = (float)$data[$d]->netto;

         if ($config->exp_preis == 'brutto') {
            $preis *= (1 + $this->params->firma['tax'.(int)$info[$i]->steuersatz] / 100);
         }

         $csv .= $wt.$info[$i]->id.$trenner.$wt;      // intern-nr
         $csv .= $wt.$data[$d]->art_nr.$trenner.$wt;      // artnr
         $csv .= $wt.$name.$trenner.$wt;      // product-name
         $csv .= $wt.($info[$i]->image == '' || $info[$i]->image == 'nopic.png' ? '' : SHOP_URL.'/'.CONF_PICT_PATH.$info[$i]->image.'.jpg').$trenner.$wt;      // main-image-url
         $csv .= $wt.$name.$trenner.$wt;      // shortdesc

         $csv .= $wt.$beschreibung.$trenner.$wt;      // product-description
         $csv .= $wt.$data[$d]->w_name1.$trenner.$wt;      // size
         $csv .= $wt.$data[$d]->w_name2.$trenner.$wt;      // color
         $csv .= $wt.$data[$d]->w_name1.$trenner.$wt;      // variationname
         $csv .= $wt.''.$trenner.$wt;      // parent-child, leer, parent oder child

         $csv .= $wt.''.$trenner.$wt;      // parent-intern-nr, intern-nr parent bei child
         $csv .= $wt.''.$trenner.$wt;      // relationship-type VARIATION bei child
         $csv .= $wt.($data[$d]->w_name1 != '' ? 'Size' : '').$trenner.$wt;      // variation-theme
         $csv .= $wt.($info[$i]->image == '' || $info[$i]->image == 'nopic.png' ? '' : SHOP_URL.'/'.CONF_PICT_PATH.$info[$i]->image.'.jpg').$trenner.$wt;      // main-image-url
         $csv .= $wt.$info[$i]->gewicht.$trenner.$wt;      // shipping-weight

         $csv .= $wt.$preis.$trenner.$wt;      // item-price
         $csv .= $wt.'EUR'.$trenner.$wt;      // currency
         $csv .= $wt.($data[$d]->online == 'y' ? '1' : '0').$trenner.$wt;      // products_status
         $csv .= $wt.$info[$i]->catname.$trenner.$wt;      // categorie
         $csv .= $wt.''.$trenner.$wt;      // manufacturer

         $csv .= $wt.$preis.$trenner.$wt;      // recommended_retail_price; Brutto!
         $csv .= $wt.'0'.$trenner.$wt;      // attrib_agio
         $csv .= CRLF;
      }

      // Varianten / master/child
      else {
         $parent_id = 0;
         for ($d = 0; $d< $anzahl; $d++) {
            $preis = (float)$data[$d]->netto;
            if ($config->exp_preis == 'brutto') {
               $preis *= (1 + $this->params->firma['tax'.(int)$info[$i]->steuersatz] / 100);
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

            // 1. Variante ist Hauptartikel
            if ($d == 0) {
               $parent_id = $info[$i]->id;

               $csv .= $wt.$info[$i]->id.$trenner.$wt;      // intern-nr
               $csv .= $wt.$data[$d]->art_nr.$trenner.$wt;      // artnr
               $csv .= $wt.$name.$trenner.$wt;      // product-name
               $csv .= $wt.($info[$i]->image == '' || $info[$i]->image == 'nopic.png' ? '' : $info[$i]->image.'.jpg').$trenner.$wt;      // imagename
               $csv .= $wt.$name.$trenner.$wt;      // shortdesc

               $csv .= $wt.$beschreibung.$trenner.$wt;      // product-description
               $csv .= $wt.''.$trenner.$wt;      // size
               $csv .= $wt.''.$trenner.$wt;      // color
               $csv .= $wt.''.$trenner.$wt;      // variationname
               $csv .= $wt.'parent'.$trenner.$wt;      // parent-child, leer, parent oder child

               $csv .= $wt.''.$trenner.$wt;      // parent-intern-nr, intern-nr parent bei child
               $csv .= $wt.''.$trenner.$wt;      // relationship-type VARIATION bei child
               $csv .= $wt.($data[$d]->w_name1 != '' ? 'Size' : '').$trenner.$wt;      // variation-theme
               $csv .= $wt.($info[$i]->image == '' || $info[$i]->image == 'nopic.png' ? '' : SHOP_URL.'/'.CONF_PICT_PATH.$info[$i]->image.'.jpg').$trenner.$wt;      // main-image-url
               $csv .= $wt.$info[$i]->gewicht.$trenner.$wt;      // shipping-weight

               $csv .= $wt.$preis.$trenner.$wt;      // item-price
               $csv .= $wt.'EUR'.$trenner.$wt;      // currency
               $csv .= $wt.($data[$d]->online == 'y' ? '1' : '0').$trenner.$wt;      // products_status
               $csv .= $wt.$info[$i]->catname.$trenner.$wt;      // categorie
               $csv .= $wt.''.$trenner.$wt;      // manufacturer

               $csv .= $wt.$preis.$trenner.$wt;      // recommended_retail_price; Brutto!
               $csv .= $wt.'0'.$trenner.$wt;      // attrib_agio
               $csv .= CRLF;
            }

            // Weitere Varianten
            else {
               $csv .= $wt.$info[$i]->id.'-'.($d * 10).$trenner.$wt;     // intern-nr
               $csv .= $wt.$data[$d]->art_nr.$trenner.$wt;                 // artnr
               $csv .= $wt.$name.$trenner.$wt;                   // product-name
               $csv .= $wt.($info[$i]->image == '' || $info[$i]->image == 'nopic.png' ? '' : SHOP_URL.'/'.CONF_PICT_PATH.$info[$i]->image.'.jpg').$trenner.$wt;      // main-image-url
               $csv .= $wt.$name.$trenner.$wt;                   // shortdesc

               $csv .= $wt.$beschreibung.$trenner.$wt;           // product-description
               $csv .= $wt.$data[$d]->w_name1.$trenner.$wt;                // size
               $csv .= $wt.$data[$d]->w_name2.$trenner.$wt;                // color
               $csv .= $wt.$data[$d]->w_name1.$trenner.$wt;                // variationname
               $csv .= $wt.'child'.$trenner.$wt;                           // parent-child, leer, parent oder child

               $csv .= $wt.$parent_id.$trenner.$wt;                        // parent-intern-nr, intern-nr parent bei child
               $csv .= $wt.'Variation'.$trenner.$wt;                       // relationship-type VARIATION bei child
               $csv .= $wt.($data[$d]->w_name1 != '' ? 'Size' : '').$trenner.$wt;      // variation-theme
               $csv .= $wt.($info[$i]->image == '' || $info[$i]->image == 'nopic.png' ? '' : SHOP_URL.'/'.CONF_PICT_PATH.$info[$i]->image.'.jpg').$trenner.$wt;      // main-image-url
               $csv .= $wt.$info[$i]->gewicht.$trenner.$wt;                // shipping-weight

               $csv .= $wt.$preis.$trenner.$wt;                            // item-price
               $csv .= $wt.'EUR'.$trenner.$wt;                             // currency
               $csv .= $wt.($data[$d]->online == 'y' ? '1' : '0').$trenner.$wt;      // products_status
               $csv .= $wt.$info[$i]->catname.$trenner.$wt;                // categorie
               $csv .= $wt.''.$trenner.$wt;                                // manufacturer

               $csv .= $wt.$preis.$trenner.$wt;                            // recommended_retail_price; Brutto!
               $csv .= $wt.'0'.$trenner.$wt;                               // attrib_agio
               $csv .= CRLF;
            }
         }
      }
   }
}

/******* IMPORT ************************************************************************************/
if ($mode == 'import') {      // 1. Zeile enthält Spaltennamen
   $art_kat = 0;
   $trenner = $config->trenner;
   $old_id = '';
   $kat_id = 0;
   $parent_id = 0;
   $sort = 0;

   $articles = file($file);
   for ($i = $start; $i <count($articles); $i++) {
      $zeile = trim($articles[$i], "\n,\r");
      $act_article = explode($trenner, $zeile);
//var_dump($zeile, $act_article);
      for ($a = 0; $a < count($act_article); $a++) {
         $act_article[$a] = trim($act_article[$a], "\"' ");
      }


      // Hauptartikel oder Parent-Artikel
      if ($act_article[9] != 'child') {
         $art_id = 0;
         $art_kat = 0;
         // Bei Overwrite Artikel-Id anhand intern-nr suchen
         if ($overwrite == 'y') {
            // Artikel ohne Varinten
            if ($act_article[9] != 'parent') {
               $art_id = $this->_getArtIdByArtNr($act_article[0]);
               $art_kat = $categorie->getCategoryByName($act_article[18], $lang);
            }
            // Artikel mit Varianten: Parent wird nicht übernommen, Parent ist 1. Variante
            else {
               $next_article = explode(';', $articles[$i + 1]);
               $art_id = $this->_getArtIdByArtNr($next_article[0]);
            }
         }

         // Eintrag in articles_info
         $sort = 1;
         $artikel = array();
         $artikel['id']             = $art_id;
         $artikel['name']           = utf8_encode($this->db->escape($act_article[2]));
         $artikel['beschreibung']   = utf8_encode($this->db->escape($act_article[5]));
         $artikel['kategorie_id']   = $art_kat;
         $artikel['steuersatz']     = 1;
         $artikel['versand_preis']  = 0;
         $artikel['gewicht']        = $act_article[14];
         $artikel['image']          = 'nopic.png';
         $artikel['images']         = null;
         $artikel['staffelung']     = '';
         $artikel['widerruf']       = 1;
         $artikel['lieferzeit']     = 2;
         $artikel['masse_check']    = 'n';
         $artikel['masse_min']      = '1.00000';
         $artikel['masse_komma']    = '1';
         $artikel['grundeinheit']   = '';
         $artikel['ge_netto_aktiv'] = 'n';
         $artikel['ge_netto']       = '0.00000';
         $artikel['gtin']           = '';
         $artikel['gcat']           = '';
         $artikel['zustand']        = '';
         $artikel['marke']          = '';
         $artikel['mpn']            = '';
         $this->_insertArticle($artikel, $overwrite, $catname, $cronjob, $haendler_id);

         $variant = array();
         $variant['parent']         = $art_id;
         $variant['sort']           = $sort;
         $variant['art_nr']         = $act_article[1];
         $variant['mm_name1']       = $config->imp_merkmal1;
         $variant['w_name1']        = $act_article[6];
         $variant['mm_name2']       = $config->imp_merkmal2;
         $variant['w_name2']        = $act_article[7];
         $variant['netto']          = ($act_article[20] / (1.0 + (float)$this->params->firma['tax1']));
         $variant['angebot_active'] = 'n';
         $variant['angebot']        = $act_article[15];
         $variant['menge']          = $act_article[10];
         $variant['online']         = 'y';
         $this->_insertVariant($variant);
         $sort++;
      }

      // Fehlende Werte anlegen
      if ($act_article[6] != '') {
         $this->_checkWerte($config->imp_merkmal1, $act_article[6]);
      }
      if ($act_article[7] != '') {
         $this->_checkWerte($config->imp_merkmal2, $act_article[7]);
      }

      // Eintrag in articles
      // Parent überspringen (wurde schon berücksichtigt)
      if ($act_article[9] != 'parent') {
         $variant = array();
         $variant['parent']         = $art_id;
         $variant['sort']           = $sort;
         $variant['art_nr']         = $act_article[1];
         $variant['mm_name1']       = $config->imp_merkmal1;
         $variant['w_name1']        = $act_article[6];
         $variant['mm_name2']       = $config->imp_merkmal2;
         $variant['w_name2']        = $act_article[7];
         $variant['netto']          = ($act_article[20] / (1.0 + (float)$this->params->firma['tax1']));
         $variant['angebot_active'] = 'n';
         $variant['angebot']        = $act_article[15];
         $variant['menge']          = $act_article[10];
         $variant['online']         = 'y';
         $this->_insertVariant($variant);
         $sort++;
      }
   }
   echo "<script>parent.Royalart.uploadDone('ok', 'Datei erfolgreich importiert', 'checkok');</script>";
}
?>