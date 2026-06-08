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
                  a.id AS artikel_id, i.masse_check, i.masse_min, i.masse_komma, i.grundeinheit, i.ge_netto_aktiv, i.ge_netto,
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

   $xml  = '<?xml version="1.0" encoding="UTF-8"?>'.CR;
   $xml .= '<articles>'.CR;
   $xml .= '<lang>deu</lang>'.CR;

   // shop_article
   $head = '"id";"sort";"art_nr";"merkmal1";"wert1";"merkmal2";"wert2";"brutto";"angebot";"ang_brutto";"menge";"online";';
   // shop_article_info
   $head .= '"name";"beschreibung";"cat_id";"cat_name";"ust_satz";"vers_netto";"gewicht";"url";"bild1";"bild2";"bild3";"bild4";"bild5";"bild6";"bild7";"bild8";"bild9";"bild10";"bild11";"widerruf";"lieferzeit";"gcat";"zustand";"marke";"gtin";"mpn"'.CRLF;
   $csv  = $head;
//      $csv  = '';

   for ($i = 0; $i < count($info); $i++) {
      // Bei Kleingewerbe Steuersatz3 (0%)
      if ($this->params->firma['kleingewerbe'] == 'y') {
         $info[$i]->steuersatz = 3;
      }
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

      $data = $this->db->queryAllObjects($sql);

      for ($d = 0; $d< count($data); $d++) {
         $brutto = number_format(round((float)$data[$d]->netto * (1 + $this->params->firma['tax'.(int)$info[$i]->steuersatz] / 100), 2), 2, '.', '');
         $ang_brutto = number_format(round((float)$data[$d]->angebot * (1 + $this->params->firma['tax'.(int)$info[$i]->steuersatz] / 100), 2), 2, '.', '');

         $xml .= '   <variant>'.CR;
         $xml .= '      <sort>'.$data[$d]->sort.'</sort>'.CR;
         $xml .= '      <artnr><![CDATA['.$data[$d]->art_nr.']]></artnr>'.CR;
         $xml .= '      <merkmal1><![CDATA['.$data[$d]->mm_name1.']]></merkmal1>'.CR;
         $xml .= '      <wert1><![CDATA['.$data[$d]->w_name1.']]></wert1>'.CR;
         $xml .= '      <merkmal2><![CDATA['.$data[$d]->mm_name2.']]></merkmal2>'.CR;
         $xml .= '      <wert2><![CDATA['.$data[$d]->w_name2.']]></wert2>'.CR;
         $xml .= '      <preis>'.$data[$d]->netto.'</preis>'.CR;
         $xml .= '      <angebot_active>'.$data[$d]->angebot_active.'</angebot_active>'.CR;
         $xml .= '      <angebotspreis>'.$data[$d]->angebot.'</angebotspreis>'.CR;
         $xml .= '      <lagerbestand>'.$data[$d]->menge.'</lagerbestand>'.CR;
         $xml .= '   </variant>'.CR;
      }

      $info[$i]->name = str_replace('"', '""', $info[$i]->name);
      $info[$i]->beschreibung = str_replace('"', '""', $info[$i]->beschreibung);
      $vers_brutto = number_format(round((float)$info[$i]->versand_preis * (1 + $this->params->firma['tax'.(int)$info[$i]->steuersatz] / 100), 2), 2, '.', '');

      $xml .= '   <name><![CDATA['.$info[$i]->name.']]></name>'.CR;
      $xml .= '   <beschreibung><![CDATA['.$info[$i]->beschreibung.']]></beschreibung>'.CR;
      $xml .= '   <kategorie_id>'.$info[$i]->cat_id.'</kategorie_id>'.CR;
      $xml .= '   <kategoriename><![CDATA['.$info[$i]->catname.']]></kategoriename>'.CR;
      $xml .= '   <steuersatz>'.$info[$i]->steuersatz.'</steuersatz>'.CR;
      $xml .= '   <versand>'.$info[$i]->versand_preis.'</versand>'.CR;
      $xml .= '   <gewicht>'.$info[$i]->gewicht.'</gewicht>'.CR;
// deu_im Link      $xml .= '   <link>'.(defined('CONF_USE_HTACCESS') ? '' : '/index.php').'/deu_'.$info[$i]->artikel_id.'/'.urldecode($info[$i]->name).'</link>'.CR;
      $xml .= '   <link>'.(defined('CONF_USE_HTACCESS') ? '' : '/index.php').'/'.$info[$i]->artikel_id.'/'.urldecode($info[$i]->name).'</link>'.CR;
      $xml .= '   <bildlink>'.$this->_checkPicExport($info[$i]->pict01, $picurl).'</bildlink>'.CR;
      $xml .= '   <bildlink2>'.$this->_checkPicExport($info[$i]->pict02, $picurl).'</bildlink2>'.CR;
      $xml .= '   <bildlink3>'.$this->_checkPicExport($info[$i]->pict03, $picurl).'</bildlink3>'.CR;
      $xml .= '   <bildlink4>'.$this->_checkPicExport($info[$i]->pict04, $picurl).'</bildlink4>'.CR;
      $xml .= '   <bildlink5>'.$this->_checkPicExport($info[$i]->pict05, $picurl).'</bildlink5>'.CR;
      $xml .= '   <bildlink6>'.$this->_checkPicExport($info[$i]->pict06, $picurl).'</bildlink6>'.CR;
      $xml .= '   <bildlink7>'.$this->_checkPicExport($info[$i]->pict07, $picurl).'</bildlink7>'.CR;
      $xml .= '   <bildlink8>'.$this->_checkPicExport($info[$i]->pict08, $picurl).'</bildlink8>'.CR;
      $xml .= '   <bildlink9>'.$this->_checkPicExport($info[$i]->pict09, $picurl).'</bildlink9>'.CR;
      $xml .= '   <bildlink10>'.$this->_checkPicExport($info[$i]->pict10, $picurl).'</bildlink10>'.CR;
      $xml .= '   <bildlink11>'.$this->_checkPicExport($info[$i]->pict11, $picurl).'</bildlink11>'.CR;
      $xml .= '   <widerruf>'.$info[$i]->widerruf.'</widerruf>'.CR;
      $xml .= '   <lieferzeit>'.$info[$i]->lieferfrist.'</lieferzeit>'.CR;
      $xml .= '   <massecheck>'.$info[$i]->masse_check.'</massecheck>'.CR;
      $xml .= '   <massemin>'.$info[$i]->masse_min.'</massemin>'.CR;
      $xml .= '   <massekomma>'.$info[$i]->masse_komma.'</massekomma>'.CR;
      $xml .= '   <grundeinheit>'.$info[$i]->grundeinheit.'</grundeinheit>'.CR;
      $xml .= '   <geaktiv>'.$info[$i]->ge_netto_aktiv.'</geaktiv>'.CR;
      $xml .= '   <genetto>'.$info[$i]->ge_netto.'</genetto>'.CR;
      $xml .= '   <gcat>'.$info[$i]->gcat.'</gcat>'.CR;
      $xml .= '   <zustand>'.$info[$i]->zustand.'</zustand>'.CR;
      $xml .= '   <marke>'.$info[$i]->marke.'</marke>'.CR;
      $xml .= '   <gtin>'.$info[$i]->gtin.'</gtin>'.CR;
      $xml .= '   <mpn>'.$info[$i]->mpn.'</mpn>'.CR;
      $xml .= '</article>'.CR;
   }
   $xml .= '</articles>'.CR;
}

/******* IMPORT ************************************************************************************/
if ($mode == 'import') {
}
?>