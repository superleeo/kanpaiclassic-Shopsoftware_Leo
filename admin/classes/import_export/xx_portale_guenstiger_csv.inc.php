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
   $trenner   = $config->trenner;
   $wt        = $config->worttrenner;
   $all       = $config->all;
   $csv       = '';
   $exp_preis = $config->exp_preis;

   if ($config->csv_head == 'y') {
      $head  = $wt.'ProduktID'.$wt;                                    // Artikel-Id (#__articles)
      $head .= $trenner.$wt.'Gewicht'.$wt;                             //
      $head .= $trenner.$wt.'EAN'.$wt;                                 // gtin
      $head .= $trenner.$wt.'Lagerbestand'.$wt;                        // Menge
      $head .= $trenner.$wt.'Hersteller'.$wt;                          // marke
      $head .= $trenner.$wt.'ProduktBezeichnung'.$wt;                  // name_deu
      $head .= $trenner.$wt.'ArtikelNroderHerstellerArtikelNr'.$wt;    // mpn
      $head .= $trenner.$wt.'Preis'.$wt;                               // Brutto
      $head .= $trenner.$wt.'ProduktLangBeschreibung'.$wt;             // desc_deu
      $head .= $trenner.$wt.'ProduktKurzBeschreibung'.$wt;             // desc_deu
      $head .= $trenner.$wt.'Lieferzeit'.$wt;                          // lieferzeit
      $head .= $trenner.$wt.'Produktlink'.$wt;                         // link
      $head .= $trenner.$wt.'FotoLink'.$wt;                            // pict01
      $head .= $trenner.$wt.'Kategoriename'.$wt;                       // cat_id -> Kategoriename
      $head .= $trenner.CRLF;
      $csv  .= $head;
   }

   // shop_article
   $sql = "SELECT i.id, ac.cat_id, i.steuersatz, i.name_$lang AS name, i.desc_$lang AS beschreibung, i.pict01, i.pict02,
                  i.versand_preis, i.gewicht, i.widerruf, i.lieferfrist, i.marke,
                  c.name_deu AS catname
              FROM #__articles_info AS i
           LEFT JOIN #__article_to_cats AS ac
              ON ac.parent_id = i.id
           LEFT JOIN #__categories AS c
              ON c.id = ac.cat_id
           WHERE ac.sort = 0";

   $info = $this->db->queryAllObjects($sql);

   for ($i = 0; $i < count($info); $i++) {
      $found = false;

      $name         = $info[$i]->name;
      $beschreibung = '';
      $marke        = $info[$i]->marke;
      $lieferfrist  = $info[$i]->lieferfrist;
      $bild         = ($info[$i]->pict01 == '' || $info[$i]->pict01 == 'nopic.png' ? '' : $picurl.'/'.CONF_PICT_PATH.$info[$i]->pict01.'.jpg');
      $kategorie    = $info[$i]->catname;

      if ($config->html == 'text') {
         $beschreibung = $this->_html2txt(($info[$i]->beschreibung));
      }
      else {
         $beschreibung = str_replace(CR, '', nl2br($info[$i]->beschreibung));
      }

      $steuersatz = (float)$this->params->firma['tax'.(int)$info[$i]->steuersatz];
      
      if ($this->params->firma['kleingewerbe'] == 'y') {
         $steuersatz = 0;
      }

      $sql = "SELECT a.id, a.online, a.art_nr, a.netto, a.angebot, a.angebot_active, a.menge, a.gewicht, a.gtin, a.mpn,
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
               AND menge > 0
             ORDER BY sort";

      $data = $this->db->queryAllObjects($sql);

      for ($a = 0; $a< count($data); $a++) {
         $d = $data[$a];

         // Nicht Online? nächste Variante
         if ($d->online != 'y') {
            continue;
         }
         
         // Keine Varianten?
         else if ($all != 'all' && $found) {
            break;
         }
         
         $found = true;

         $netto      = ($d->angebot_active == 'y' ? $d->angebot : $d->netto);
         $steuersatz = 
         $brutto = number_format((float)$netto * (1 + $steuersatz / 100), 2, '.', '');
         $netto  = number_format($netto, 2, '.', '');
         $link   = $shopurl.(defined('CONF_USE_HTACCESS') ? '' : '/index.php').'/'.$d->id.'/'.urldecode($info[$i]->name);

         if ($all != 'all') {
            if ($d->mm_name1 != '' && w_name1 != '') {
               $name .= ' '.mm_name1.': '.w_name1;
            }

            if ($d->mm_name2 != '' && w_name2 != '') {
               $name .= ' '.mm_name2.': '.w_name2;
            }
         }

         $csv .= $wt.$d->id.$wt;                                                 // Artikel-Id (#__articles)
         $csv .= $trenner.$wt.$d->gewicht.$wt;                                   // gewicht
         $csv .= $trenner.$wt.$d->gtin.$wt;                                      // gtin
         $csv .= $trenner.$wt.(int)$d->menge.$wt;                                // Menge
         $csv .= $trenner.$wt.$marke.$wt;                                        // marke
         $csv .= $trenner.$wt.$name.$wt;                                         // name_deu
         $csv .= $trenner.$wt.$d->mpn.$wt;                                       // mpn
         $csv .= $trenner.$wt.($exp_preis == 'netto' ? $netto : $brutto).$wt;    // Brutto
         $csv .= $trenner.$wt.$beschreibung.$wt;                                 // desc_deu
         $csv .= $trenner.$wt.Helper::truncate($beschreibung, 100).$wt;          // desc_deu
         $csv .= $trenner.$wt.$lieferfrist.$wt;                                  // lieferzeit
         $csv .= $trenner.$wt.$link.$wt;                                         // link
         $csv .= $trenner.$wt.$bild.$wt;                                         // pict01
         $csv .= $trenner.$wt.$kategorie.$wt;                                    // cat_id -> Kategoriename
         $csv .= $trenner.CRLF;
      }
   }
}

/******* IMPORT ************************************************************************************/
if ($mode == 'import') {
}

?>