<?php
/*
###################################################################################
  Kanpai Classic Shopsoftware II
  Entwicklungsstand: 20.06.2020 Version 80

  (c) Copyright by Kanpai Classic - Web Development
  Kanpai Classic
  https://www.kanpaiclassic.com
  https://www.kanpaiclassic.com

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
if ($mode == 'export') {      // 1. Zeile enthält Spaltennamen
   // Zusatzinfo für Artikel auslesen
   $sql = "SELECT i.id, i.steuersatz, i.name_$lang AS name, i.desc_$lang AS beschreibung,
                  i.pict01, i.pict02, i.pict03, i.pict04, i.pict05, i.pict06, i.pict07, i.pict08, i.pict09, i.pict10, i.pict11,
                  i.versand_preis, i.gewicht, i.widerruf, i. lieferfrist, i.staffelung,
                  a.id AS artikel_id, i.masse_check, i.masse_min, i.masse_komma, i.grundeinheit, i.ge_netto_aktiv, i.ge_netto,
                  c.network_id AS cat_id,
                  g.categories AS gcat, g.zustand, i.marke, a.gtin, a.mpn
              FROM #__articles_info AS i
           LEFT JOIN #__articles AS a
              ON i.id = a.parent_id
           LEFT JOIN #__categories AS c
              ON c.id = i.cat_id
           LEFT JOIN #__articles_to_googlecats AS g
              ON  g.article_id = i.id
           WHERE c.network_id > 0
              AND a.sort = 1
           ORDER BY i.id";
   $info = $this->db->queryAllObjects($sql);

   $xml  = '<?xml version="1.0" encoding="UTF-8"?>'.CR;
   $xml .= '<articles>'.CR;
   $xml .= '<version>3.0</version>'.CR;
   $xml .= '<lang>deu</lang>'.CR;

   for ($i = 0; $i < count($info); $i++) {




      $xml .= '<article>'.CR;
      $xml .= '   <id>'.$info[$i]->id.'</id>'.CR;

      // Varianten auslesen
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

      $xml .= '   <name><![CDATA['.$info[$i]->name.']]></name>'.CR;
      $xml .= '   <beschreibung><![CDATA['.str_replace(array('<![CDATA[','// ]]>'), '', $info[$i]->beschreibung).']]></beschreibung>'.CR;
      $xml .= '   <kategorie_id>'.$info[$i]->cat_id.'</kategorie_id>'.CR;
      $xml .= '   <steuersatz>'.$info[$i]->steuersatz.'</steuersatz>'.CR;
// deu_im Link      $xml .= '   <link><![CDATA['.$shopurl.(defined('CONF_USE_HTACCESS') ? '' : '/index.php').'/deu_'.$info[$i]->artikel_id.'/'.urldecode($info[$i]->name).']]></link>'.CR;
      $xml .= '   <link><![CDATA['.$shopurl.(defined('CONF_USE_HTACCESS') ? '' : '/index.php').'/'.$info[$i]->artikel_id.'/'.urldecode($info[$i]->name).']]></link>'.CR;
      $xml .= '   <bildlink>'.($info[$i]->pict01 == ''   || $info[$i]->pict01 == 'nopic.png' ? '' : $shopurl.'/'.CONF_PICT_PATH.str_replace('.jpg', '', $info[$i]->pict01).'.jpg').'</bildlink>'.CR;
      $xml .= '   <bildlink2>'.($info[$i]->pict02 == ''  || $info[$i]->pict02 == 'nopic.png' ? '' : $shopurl.'/'.CONF_PICT_PATH.str_replace('.jpg', '', $info[$i]->pict02).'.jpg').'</bildlink2>'.CR;
      $xml .= '   <bildlink3>'.($info[$i]->pict03 == ''  || $info[$i]->pict03 == 'nopic.png' ? '' : $shopurl.'/'.CONF_PICT_PATH.str_replace('.jpg', '', $info[$i]->pict03).'.jpg').'</bildlink3>'.CR;
      $xml .= '   <bildlink4>'.($info[$i]->pict04 == ''  || $info[$i]->pict04 == 'nopic.png' ? '' : $shopurl.'/'.CONF_PICT_PATH.str_replace('.jpg', '', $info[$i]->pict04).'.jpg').'</bildlink4>'.CR;
      $xml .= '   <bildlink5>'.($info[$i]->pict05 == ''  || $info[$i]->pict05 == 'nopic.png' ? '' : $shopurl.'/'.CONF_PICT_PATH.str_replace('.jpg', '', $info[$i]->pict05).'.jpg').'</bildlink5>'.CR;
      $xml .= '   <bildlink6>'.($info[$i]->pict06 == ''  || $info[$i]->pict06 == 'nopic.png' ? '' : $shopurl.'/'.CONF_PICT_PATH.str_replace('.jpg', '', $info[$i]->pict06).'.jpg').'</bildlink6>'.CR;
      $xml .= '   <bildlink7>'.($info[$i]->pict07 == ''  || $info[$i]->pict07 == 'nopic.png' ? '' : $shopurl.'/'.CONF_PICT_PATH.str_replace('.jpg', '', $info[$i]->pict07).'.jpg').'</bildlink7>'.CR;
      $xml .= '   <bildlink8>'.($info[$i]->pict08 == ''  || $info[$i]->pict08 == 'nopic.png' ? '' : $shopurl.'/'.CONF_PICT_PATH.str_replace('.jpg', '', $info[$i]->pict08).'.jpg').'</bildlink8>'.CR;
      $xml .= '   <bildlink9>'.($info[$i]->pict09 == ''  || $info[$i]->pict09 == 'nopic.png' ? '' : $shopurl.'/'.CONF_PICT_PATH.str_replace('.jpg', '', $info[$i]->pict09).'.jpg').'</bildlink9>'.CR;
      $xml .= '   <bildlink10>'.($info[$i]->pict10 == '' || $info[$i]->pict10 == 'nopic.png' ? '' : $shopurl.'/'.CONF_PICT_PATH.str_replace('.jpg', '', $info[$i]->pict10).'.jpg').'</bildlink10>'.CR;
      $xml .= '   <bildlink11>'.($info[$i]->pict11 == '' || $info[$i]->pict11 == 'nopic.png' ? '' : $shopurl.'/'.CONF_PICT_PATH.str_replace('.jpg', '', $info[$i]->pict11).'.jpg').'</bildlink11>'.CR;
      $xml .= '   <staffelung>'.$info[$i]->staffelung.'</staffelung>'.CR;
      $xml .= '   <grundeinheit>'.$info[$i]->grundeinheit.'</grundeinheit>'.CR;
      $xml .= '   <genetto>'.$info[$i]->ge_netto.'</genetto>'.CR;
      $xml .= '   <geaktiv>'.$info[$i]->ge_netto_aktiv.'</geaktiv>'.CR;
      $xml .= '   <versand_preis>'.$info[$i]->versand_preis.'</versand_preis>'.CR;
      $xml .= '   <massecheck>'.$info[$i]->masse_check.'</massecheck>'.CR;
      $xml .= '   <massemin>'.$info[$i]->masse_min.'</massemin>'.CR;
      $xml .= '   <massekomma>'.$info[$i]->masse_komma.'</massekomma>'.CR;
      $xml .= '   <gewicht>'.$info[$i]->gewicht.'</gewicht>'.CR;
      $xml .= '   <widerruf>'.$info[$i]->widerruf.'</widerruf>'.CR;
      $xml .= '   <lieferzeit>'.$info[$i]->lieferfrist.'</lieferzeit>'.CR;
      $xml .= '   <gcat>'.$info[$i]->gcat.'</gcat>'.CR;
      $xml .= '   <zustand>'.$info[$i]->marke.'</zustand>'.CR;
      $xml .= '   <marke>'.$info[$i]->marke.'</marke>'.CR;

      for ($d = 0; $d< count($data); $d++) {
         $xml .= '   <variant>'.CR;
         $xml .= '      <sort>'.$data[$d]->sort.'</sort>'.CR;
         $xml .= '      <artnr><![CDATA['.$data[$d]->art_nr.']]></artnr>'.CR;
         $xml .= '      <gtin>'.$data[$d]->gtin.'</gtin>'.CR;
         $xml .= '      <mpn>'.$data[$d]->mpn.'</mpn>'.CR;
         $xml .= '      <online>'.$data[$d]->online.'</online>'.CR;
         $xml .= '      <preis>'.$data[$d]->netto.'</preis>'.CR;
         $xml .= '      <angebot_active>'.$data[$d]->angebot_active.'</angebot_active>'.CR;
         $xml .= '      <angebotspreis>'.$data[$d]->angebot.'</angebotspreis>'.CR;
         $xml .= '      <lagerbestand>'.$data[$d]->menge.'</lagerbestand>'.CR;
         $xml .= '      <merkmal1><![CDATA['.$data[$d]->mm_name1.']]></merkmal1>'.CR;
         $xml .= '      <wert1><![CDATA['.$data[$d]->w_name1.']]></wert1>'.CR;
         $xml .= '      <merkmal2><![CDATA['.$data[$d]->mm_name2.']]></merkmal2>'.CR;
         $xml .= '      <wert2><![CDATA['.$data[$d]->w_name2.']]></wert2>'.CR;
         $xml .= '   </variant>'.CR;
      }

      $xml .= '</article>'.CR;
   }
   $xml .= '</articles>'.CR;

}

/******* IMPORT ************************************************************************************/
if ($mode == 'import') {
   $xml = simplexml_load_file($file, 'SimpleXMLElement', LIBXML_NOCDATA);
   $version = $xml->version;

   // Alte Version importieren
   if ((float)$version == 2) {
      include $this->params->filepath.'/admin/classes/import_export/portal_xml_v2.inc.php';
   }

   else {
      $lang = $xml->lang;
      $articles = $xml->article;

      if (defined('CONF_PORTAL')) {
         $this->db->query("DELETE FROM #__articles WHERE parent_id IN (SELECT id FROM #__articles_info WHERE haendler_id = $haendler_id)");
         $this->db->query("DELETE FROM #__articles_info WHERE haendler_id = $haendler_id");
      }

      for ($i = 0; $i < count($articles); $i++) {
         $this->last_id = 0;
         $art_id = (int)$articles[$i]->id;
         if ($overwrite == 'n') {
            $art_id = 0;
         }

         $artikel = array();
         $artikel['id']             = $art_id;
         $artikel['art_nr']         = $this->db->escape($articles[$i]->art_nr);
         $artikel['name']           = $this->db->escape($articles[$i]->name);
         $artikel['beschreibung']   = $this->db->escape($articles[$i]->beschreibung);
         $artikel['kategorie_id']   = (int)$articles[$i]->kategorie_id;
         $artikel['steuersatz']     = (int)$articles[$i]->steuersatz;
         $artikel['pict01']         = $articles[$i]->bildlink;
         $artikel['pict02']         = $articles[$i]->bildlink2;
         $artikel['pict03']         = $articles[$i]->bildlink3;
         $artikel['pict04']         = $articles[$i]->bildlink4;
         $artikel['pict05']         = $articles[$i]->bildlink5;
         $artikel['pict06']         = $articles[$i]->bildlink6;
         $artikel['pict07']         = $articles[$i]->bildlink7;
         $artikel['pict08']         = $articles[$i]->bildlink8;
         $artikel['pict09']         = $articles[$i]->bildlink9;
         $artikel['pict10']         = $articles[$i]->bildlink10;
         $artikel['pict11']         = $articles[$i]->bildlink11;
         $artikel['staffelung']     = $articles[$i]->staffelung;
         $artikel['grundeinheit']   = $articles[$i]->grundeinheit;
         $artikel['ge_netto']       = $articles[$i]->genetto;
         $artikel['ge_netto_aktiv'] = $articles[$i]->geaktiv;
         $artikel['versand_preis']  = $articles[$i]->versand_preis;
         $artikel['masse_check']    = $articles[$i]->massecheck;
         $artikel['masse_min']      = $articles[$i]->massemin;
         $artikel['masse_komma']    = $articles[$i]->massekomma;
         $artikel['gewicht']        = $articles[$i]->gewicht;
         $artikel['widerruf']       = $articles[$i]->widerruf;
         $artikel['lieferzeit']     = $articles[$i]->lieferzeit;
         $artikel['gtin']           = $articles[$i]->gtin;
         $artikel['gcat']           = $articles[$i]->gcat;
         $artikel['zustand']        = $articles[$i]->zustand;
         $artikel['marke']          = $articles[$i]->marke;
         $artikel['mpn']            = $articles[$i]->mpn;

         $this->_insertArticle($artikel, $overwrite, $catname, $cronjob, $haendler_id);
         $variants = $articles[$i]->variant;

         for ($v = 0; $v < count($variants); $v++) {
            $variant = array();
            $variant['parent']         = $this->last_id;
            $variant['sort']           = ((int)$variants[$v]->sort > 0) ? (int)$variants[$v]->sort : 0;
            $variant['online']         = $variants[$v]->online;
            $variant['art_nr']         = $this->db->escape($variants[$v]->artnr);
            $variant['netto']          = $variants[$v]->preis;
            $variant['angebot']        = $variants[$v]->angebotspreis;
            $variant['angebot_active'] = $variants[$v]->angebot_active;
            $variant['menge']          = $variants[$v]->lagerbestand;
            $variant['mm_name1']       = (string)$variants[$v]->merkmal1;
            $variant['w_name1']        = (string)$variants[$v]->wert1;
            $variant['mm_name2']       = (string)$variants[$v]->merkmal2;
            $variant['w_name2']        = (string)$variants[$v]->wert2;

            // Verändert die Werte !
            $this->_checkMerkmalePortal((string)$variants[$v]->merkmal1, (string)$variants[$v]->wert1);
            $this->_checkMerkmalePortal((string)$variants[$v]->merkmal2, (string)$variants[$v]->wert2);

            $this->_insertVariant($variant, $overwrite);
         }
      }
      echo "<script>parent.Royalart.uploadDone('ok', 'Datei erfolgreich importiert');</script>";
      exit;
   }
}
?>