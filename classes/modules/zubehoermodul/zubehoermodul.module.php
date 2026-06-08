<?php
/*
###################################################################################
  Kanpai Classic Shopsoftware Entwicklungsstand: 14.01.2021 Version 11

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

namespace KANPAICLASSIC;

if (!defined('KANPAICLASSIC')) {
   define('KANPAICLASSIC', true);
}

class KANPAICLASSIC_modulZubehoer
{
   private $db        = null;
   private $db_extern = null;
   private $params    = null;

   function __construct() {
      $this->db        = Control::getDB();
      $this->db_extern = Control::getExternDB();
      $this->params    = Control::getParams();
   }

   // Daten für Popup via artikel_liste_sub.tpl.php und FE
   // 15.07.2019
   public function getData($parent_id) {
      $lang      = $this->params->selected_lang;

      if ($this->params->isAdmin) {
         $lang = 'deu';
      }

      $sql = "SELECT z.id AS db_id, a.id, a.sort, i.ab_check, i.gewicht, i.spedition,
                     i.id as parent, i.steuersatz, i.name_$lang AS art_name, i.desc_$lang AS artikel_text, i.haendler_id, i.childs, i.image,
                     i.lieferfrist, i.show_object, i.fsk_check, i.staffelung, i.versand_preis, i.childs, i.is_foto, i.marke, i.configurator, i.configurator_check,
                     i. gewicht, i.spedition, i.config_einheit_check, i.config_menge_check, a.online, i.childs, i.rechner_check, i.mixer_artikel_check,
                     i.timer_check, i.timer_end, i.timer_menge, i.timer_anzeige, i.timer_art_disable, i.neu_check, ab_check,
                     i.image_hover, versandfrei_check, artikelgrafik1_check, artikelgrafik2_check, artikelgrafik3_check, artikelgrafik4_check, i.artikelgrafik5_check, i.artikelgrafik6_check,
                     i.sortierung, i.grundeinheit, i.ge_netto_aktiv, i.motiv_uploadp_check, i.motiv_uploadt_check, i.artikelgruppe,
                     a.id as id, a.netto, a.angebot, a.angebot_active, a.menge, a.ge_netto, a.mpn, a.gtin,
                     c.cat_pass, c.name_$lang AS cat_name,
                     a.merkmal1 AS mm1_val, a.merkmal2 AS mm2_val, a.wert1 AS w1_val, wert2 AS w2_val,
                     m.merkmal_$lang as merkmal1, w.wert_$lang as wert1, mm.merkmal_$lang as merkmal2, ww.wert_$lang as wert2
                  FROM #__articles_zubehoer AS z
               LEFT JOIN #__articles AS a
                  ON z.zubehoer_id = a.id
               LEFT JOIN #__articles_info AS i
                  ON a.parent_id = i.id
               LEFT JOIN #__article_to_cats AS ac
                  ON i.id = ac.parent_id
               LEFT JOIN #__categories as c
                  ON ac.cat_id = c.id
               LEFT JOIN #__merkmale as m
                  ON a.merkmal1 = m.id
               LEFT JOIN #__werte as w
                  ON a.wert1 = w.id
               LEFT JOIN #__merkmale as mm
                  ON a.merkmal2 = mm.id
               LEFT JOIN #__werte as ww
                  ON a.wert2 = ww.id
               WHERE z.parent_id = $parent_id
                  AND ac.sort = 0 ";
      if (!$this->params->isAdmin) {
         $sql .= "         AND a.online = 'y'";
      }
      $sql .= "         Order by z.sort";

      $data = $this->db_extern->queryAllObjects($sql);

      return ($data);
   }

   public function getLangData($parent_id) {
      $data1 = new \stdClass();
      $sql = '';

      foreach ($this->params->langs as $lang) {
         if ($lang == 'deu') {
            $data1->{$lang} = 'Passendes Zubehör';
         }

         else {
            $data1->{$lang} = '';
         }

         $sql .= "$lang, ";
      }

      $sql = rtrim($sql, ', ');
      $data = $this->db_extern->querySingleObject("SELECT $sql FROM #__articles_zubehoer_lang WHERE parent_id = $parent_id");

      if ($data) {
         return ($data);
      }

      return $data1;
   }

   public function saveData($parent_id, $zubehoer_id) {
      $test = $this->db_extern->querySingleValue("SELECT id FROM #__articles_zubehoer WHERE parent_id = $parent_id AND zubehoer_id = $zubehoer_id");

      // Artikel beireits eingefügt
      if ($test) {
         return false;
      }

      $sort = (int) $this->db_extern->querySingleValue("SELECT MAX(sort) FROM #__articles_zubehoer WHERE parent_id = $parent_id");
      $sort++;
      $this->db_extern->query("INSERT INTO #__articles_zubehoer SET parent_id = $parent_id, zubehoer_id = $zubehoer_id, sort = $sort;");

      return true;
   }

   // Sortierung, und Titel
   public function saveSortData($parent_id) {
      // Sortierung speichern
      $db_id  = (isset($_POST['db_id']) ? $_POST['db_id'] : false);
      $sort  = (isset($_POST['sort']) ? $_POST['sort'] : false);

      if (is_array($db_id) && count($db_id) > 0) {
         for ($i = 0; $i < count($db_id); $i++) {
            $this->db_extern->query("UPDATE #__articles_zubehoer SET sort = $sort[$i] WHERE id = $db_id[$i]");
         }
      }

      // Namen speichern
      $ztitle = $this->params->selected_lang." = '".$this->db->escape($this->params->postString("ztitle"))."'";

      $test = $this->db_extern->querySingleValue("SELECT parent_id FROM #__articles_zubehoer_lang WHERE parent_id = $parent_id");

      if ($test) {
         $this->db_extern->query("UPDATE #__articles_zubehoer_lang SET $ztitle WHERE parent_id = $parent_id");
      }

      else {
         $this->db_extern->query("INSERT INTO #__articles_zubehoer_lang SET parent_id = $parent_id, $ztitle");
      }
   }

   public function zubehoerDelete($db_id) {
      $this->db_extern->query("DELETE FROM #__articles_zubehoer WHERE id = $db_id");
   }

   public function DELgetDataFromArticleID($art_id) {
      $db = Control::getDB();
      $parent_id = $db->queryAllObjects("SELECT parent_id FROM #__articles WHERE id = $art_id");
      $data = $db->queryAllObjects("SELECT a.id, i.name_deu FROM #__articles_info AS i, #__articles AS a WHERE a.parent_id = i.id AND i.id IN (SELECT zubehoer_id FROM #__articles_zubehoer WHERE parent_id = $parent_id)");

      return $data;
   }
}
