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

// 05.04.2019
// Aktuelle Version immer shopsoftware_xml.inc.php
/******* EXPORT ************************************************************************************/
if ($mode == 'export') {
   $pics = '';

//   for ($p = 1; $p <= (int)$this->params->firma['count_pics']; $p++) {
//      $pics .= " i.pict".sprintf('%02d', $p).",";
//   }

   // Zusatzinfo für Artikel auslesen
   $sql = "SELECT i.id, i.steuersatz, i.name_$lang AS name, i.desc_$lang AS beschreibung, i.image,
                  i.sortierung, i.versand_preis, i.gewicht, i.widerruf, i.lieferfrist, i.staffelung, i.marke, i.vpe, i.vpm, i.grundeinheit, i.ge_netto_aktiv,
                  a.id AS artikel_id, i.masse_check, i.masse_min, i.masse_komma, i.grundeinheit, i.ge_netto_aktiv,
                  i.grundeinheit_rechner, i.rechner_check, rechner_mode, gew_check,
                  ac.cat_id, c.name_deu AS catname,
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
           ORDER BY i.id";
   $info = $this->db->queryAllObjects($sql);

   $xml  = '<?xml version="1.0" encoding="UTF-8"?>'.CR;
   $xml .= '<articles>'.CR;
   $xml .= '<version>7.0</version>'.CR;
   $xml .= '<lang>deu</lang>'.CR;

   for ($i = 0; $i < (\is_array($info) ? count($info) : 0); $i++) {
      $info[$i]->images = $this->db->queryAllObjects("SELECT image FROM #__articles_images WHERE parent_id = ".$info[$i]->id." ORDER BY sort");

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

      $info[$i]->name = str_replace('"', '""', $info[$i]->name);
      $info[$i]->beschreibung = str_replace('"', '""', $info[$i]->beschreibung);

      // artikel_info
      $xml .= '   <name><![CDATA['.$info[$i]->name.']]></name>'.CR;
      $xml .= '   <beschreibung><![CDATA['.str_replace(['<![CDATA[','// ]]>'], '', $info[$i]->beschreibung).']]></beschreibung>'.CR;
      $xml .= '   <sortierung>'.$info[$i]->sortierung.'</sortierung>'.CR;
      $xml .= '   <kategorie_id>'.$info[$i]->cat_id.'</kategorie_id>'.CR;
      // cat_id2
      $xml .= '   <steuersatz>'.$info[$i]->steuersatz.'</steuersatz>'.CR;
      $xml .= '   <link><![CDATA['.$shopurl.(defined('CONF_USE_HTACCESS') ? '' : '/index.php').'/'.$info[$i]->artikel_id.'/'.urldecode($info[$i]->name).']]></link>'.CR;
      $xml .= '   <bildlink1><![CDATA['.$this->_checkPict($info[$i]->image, $picurl).']]></bildlink1>'.CR;

      $img = 2;
      for ($p = 0; $p < 16; $p++) {
         if (isset($info[$i]->images[$p])) {
            $xml .= '   <bildlink'.$img.'><![CDATA['.$this->_checkPict($info[$i]->images[$p]->image, $picurl).']]></bildlink'.$img.'>'.CR;
            $img++;
         }
      }

      $xml .= '   <staffelung>'.$info[$i]->staffelung.'</staffelung>'.CR;
      $xml .= '   <einheit>'.$info[$i]->grundeinheit.'</einheit>'.CR;
      $xml .= '   <einheit_aktiv>'.$info[$i]->ge_netto_aktiv.'</einheit_aktiv>'.CR;
      $xml .= '   <einheit_rechner>'.$info[$i]->grundeinheit_rechner.'</einheit_rechner>'.CR;
      $xml .= '   <gew_check>'.$info[$i]->gew_check.'</gew_check>'.CR;
      $xml .= '   <rechner_aktiv>'.$info[$i]->rechner_check.'</rechner_aktiv>'.CR;
      $xml .= '   <rechner_mode>'.$info[$i]->rechner_mode.'</rechner_mode>'.CR;
      // $info[$i]->spalten2_check
      $xml .= '   <versand_preis>'.$info[$i]->versand_preis.'</versand_preis>'.CR;
      $xml .= '   <massecheck>'.$info[$i]->masse_check.'</massecheck>'.CR;
      $xml .= '   <massemin>'.$info[$i]->masse_min.'</massemin>'.CR;
      $xml .= '   <massekomma>'.$info[$i]->masse_komma.'</massekomma>'.CR;
      $xml .= '   <gewicht>'.$info[$i]->gewicht.'</gewicht>'.CR;
      // $info[$i]->gewicht_check - nicht verwendet
      $xml .= '   <groesse>'.$info[$i]->vpm.'</groesse>'.CR;
      $xml .= '   <ve>'.$info[$i]->vpe.'</ve>'.CR;
      $xml .= '   <widerruf>'.$info[$i]->widerruf.'</widerruf>'.CR;
      $xml .= '   <lieferzeit>'.$info[$i]->lieferfrist.'</lieferzeit>'.CR;
      // is_foto
      // foto
      // foto_set
      // org_set
      // foto_size_x
      // foto_size_y
      // motiv_uplaodp_check
      // motiv_uplaodt_check
      // artikelgruppe
      $xml .= '   <gcat>'.$info[$i]->gcat.'</gcat>'.CR;           // aus #__articles_to_googlecats
      $xml .= '   <zustand>'.$info[$i]->zustand.'</zustand>'.CR;  // aus #__articles_to_googlecats
      $xml .= '   <marke>'.$info[$i]->marke.'</marke>'.CR;
      // configurator_check
      // configurator_artnr_check
      // configurator
      // config_einheit_check
      // config_menge_check
      // timer_check
      // timer_end
      // timer_anzeige
      // timer_art_disable
      // clicks
      // show_object
      // fsk_check


      for ($d = 0; $d< count($data); $d++) {
         $xml .= '   <variant>'.CR;
         // id
         // parent_id
         $xml .= '      <sort>'.$data[$d]->sort.'</sort>'.CR;
         $xml .= '      <artnr><![CDATA['.$data[$d]->art_nr.']]></artnr>'.CR;
         $xml .= '      <gtin><![CDATA['.$data[$d]->gtin.']]></gtin>'.CR;
         $xml .= '      <mpn><![CDATA['.$data[$d]->mpn.']]></mpn>'.CR;
         $xml .= '      <online>'.$data[$d]->online.'</online>'.CR;
         $xml .= '      <preis>'.$data[$d]->netto.'</preis>'.CR;
         $xml .= '      <haendler_netto>'.$data[$d]->haendler_netto.'</haendler_netto>'.CR;
         $xml .= '      <angebot_active>'.$data[$d]->angebot_active.'</angebot_active>'.CR;
         $xml .= '      <angebotspreis>'.$data[$d]->angebot.'</angebotspreis>'.CR;
         $xml .= '      <merkmal1><![CDATA['.((int)$data[$d]->merkmal1 > 0 ?  $data[$d]->mm_name1 : '').']]></merkmal1>'.CR;
         $xml .= '      <wert1><![CDATA['.((int)$data[$d]->wert1 > 0 ?  $data[$d]->w_name1 : '').']]></wert1>'.CR;
         $xml .= '      <merkmal2><![CDATA['.((int)$data[$d]->merkmal2 > 0 ?  $data[$d]->mm_name2 : '').']]></merkmal2>'.CR;
         $xml .= '      <wert2><![CDATA['.((int)$data[$d]->wert2 > 0 ?  $data[$d]->w_name2 : '').']]></wert2>'.CR;
         $xml .= '      <lager>'.$data[$d]->menge.'</lager>'.CR;
         // gewicht - nicht verwendet
         // filename
         // filetyp
         $xml .= '      <ge_netto>'.$data[$d]->ge_netto.'</ge_netto>'.CR;
         $xml .= '      <ge_faktor>'.$data[$d]->ge_menge.'</ge_faktor>'.CR;
         // startbild
         $xml .= '      <startbild>'.$data[$d]->startbild.'</startbild>'.CR;
         $xml .= '   </variant>'.CR;
      }

      $xml .= '</article>'.CR;
   }
   $xml .= '</articles>'.CR;
}

//
/******* IMPORT ************************************************************************************/
if ($mode == 'import') {
   $xml = simplexml_load_file($file, 'SimpleXMLElement', LIBXML_NOCDATA);
   $version = $xml->version;

   // Alte Version importieren
   if ((float)$version < 7) {
      include ADMIN_PATH.'/classes/import_export/shopsoftware_xml_v6.inc.php';
      return;
   }

   else {
      $lang = $xml->lang;
      $articles = $xml->article;
      $anzahl = count($articles);

      for ($i = 0; $i < $anzahl; $i++) {
         $this->last_id = 0;
         $art_id = (int)$articles[$i]->id;

         if ($overwrite == 'n') {
            $art_id = 0;
         }

         $artikel = [];
         $artikel['id']             = $art_id;
         $artikel['name']           = (string)$this->db->escape($articles[$i]->name);
         $artikel['beschreibung']   = (string)$this->db->escape($articles[$i]->beschreibung);
         $artikel['sortierung']     = (int)$articles[$i]->sortierung;
         $artikel['kategorie_id']   = (int)$articles[$i]->kategorie_id;
         $artikel['steuersatz']     = (int)$articles[$i]->steuersatz;
         $artikel['image']          = (int)$articles[$i]->bildlink1;
         $artikel['images']         = [];
         // Link nicht übernehmen

         for ($p = 0; $p < 16; $p++) {
            if (isset($articles[$i]->{'bildlink'.$p})) {
               $artikel['images'][] = $articles[$i]->{'bildlink'.$p};
            }


            $artikel['staffelung']           = (string)$articles[$i]->staffelung;
            $artikel['grundeinheit']         = (string)$articles[$i]->einheit;
            $artikel['ge_netto_aktiv']       = (string)$articles[$i]->einheit_aktiv;
            $artikel['grundeinheit_rechner'] = (string)$articles[$i]->einheit_rechner;
            $artikel['gew_check']            = (string)$articles[$i]->gew_check;
            $artikel['rechner_check']        = (string)$articles[$i]->rechner_aktiv;
            $artikel['rechner_mode']         = (string)$articles[$i]->rechner_mode;
            $artikel['versand_preis']        = (string)$articles[$i]->versand_preis;
            $artikel['masse_check']          = (string)$articles[$i]->massecheck;
            $artikel['masse_min']            = (string)$articles[$i]->massemin;
            $artikel['masse_komma']          = (string)$articles[$i]->massekomma;
            $artikel['gewicht']              = (string)$articles[$i]->gewicht;
            $artikel['vpm']                  = (string)$articles[$i]->groesse;
            $artikel['vpe']                  = (string)$articles[$i]->ve;
            $artikel['widerruf']             = (string)$articles[$i]->widerruf;
            $artikel['lieferzeit']           = (string)$articles[$i]->lieferzeit;
            $artikel['gcat']                 = (string)$articles[$i]->gcat;
            $artikel['zustand']              = (string)$articles[$i]->zustand;
            $artikel['marke']                = (string)$articles[$i]->marke;

            $this->_insertArticle($artikel, $overwrite, $catname, $cronjob, $haendler_id);

            $variants = $articles[$i]->variant;

            for ($v = 0; $v < count($variants); $v++) {
               $variant = [];
               $variant['parent']         = $this->last_id;
               $variant['sort']           = ((int)$variants[$v]->sort > 0) ? (int)$variants[$v]->sort : 0;
               $variant['art_nr']         = $this->db->escape((string)$variants[$v]->artnr);
               $variant['gtin']           = (string)$variants[$v]->gtin;
               $variant['mpn']            = (string)$variants[$v]->mpn;
               $variant['online']         = (string)$variants[$v]->online;
               $variant['netto']          = (float)$variants[$v]->preis;
               $variant['haendler_netto'] = (float)$variants[$v]->haendler_netto;
               $variant['angebot']        = (float)$variants[$v]->angebotspreis;
               $variant['angebot_active'] = (string)$variants[$v]->angebot_active;
               $variant['mm_name1']       = (string)$variants[$v]->merkmal1;
               $variant['w_name1']        = (string)$variants[$v]->wert1;
               $variant['mm_name2']       = (string)$variants[$v]->merkmal2;
               $variant['w_name2']        = (string)$variants[$v]->wert2;
               $variant['menge']          = (string)$variants[$v]->lager;
               $variant['ge_netto']       = (float)$variants[$v]->ge_netto;
               $variant['ge_menge']       = (float)$variants[$v]->ge_faktor;
               $variant['startbild']      = (int)$variants[$v]->startbild;

               $this->_insertVariant($variant, $overwrite);
            }
         }
      }

      echo json_encode(['status' => 'ok', 'msg' => $anzahl.' Artikel wurden importiert']);
      exit;
   }
}
