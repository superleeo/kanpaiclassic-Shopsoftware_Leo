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

namespace KANPAICLASSIC;

if (!defined('KANPAICLASSIC')) {
   die ("This file cannot run outside the Kanpai Classic Shopsoftware");
}

class KANPAICLASSIC_articlesBase
{

   public $db;
   public $db_extern;
   public $params;
   public $text;
   public $configurator;
   public $mixer_kategorie;
   public $mixer_artikel;

   public $kleingewerbe;
   public $tax_active;
   public $tax_show;
   public $tax = array();
   public $preis = 0;
   public $sonderpreis = 0;
   public $sonderpreis_prozent = 0;
   public $ust_txt = '';
   public $hat_ust = false;
   public $versandkosten_incl = false;

   public $energy_efficiency=null;
   public $energy_efficiency_image=null;

   public function __construct() {
      $this->db        = Control::getDB();
      $this->db_extern = Control::getExternDB();
      $this->params    = Control::getParams();
      $this->text      = Control::getText();

      if (defined('CONF_MODULE_MEGACONFIGURATOR')) {
         $this->configurator = Control::getModuleConfigurator();
      }

//      if (defined('CONF_MODULE_MIXER_KATEGORIE')) {
//         $this->mixer_kategorie = Control::getModuleMixerKategorie();
//      }

      if (defined('CONF_MODULE_MIXER_ARTIKEL')) {
//         $this->mixer_artikel = Control::getModuleMixerArtikel();
      }

      if (defined('CONF_MODULE_TIMER')) {
         $this->_checkTimer();
      }


   }

   // Preise für Artikel berechenen
   // Wird noch für Details verwendet. WK und Aktualisierungen Details nutzten berechnungen.class.php
   public function getPrice($artikel, $tax_active) {
      $tab = 1;

      if ($this->params->firma['versandart_land'] != $_SESSION['wk_land']) {
         if ($this->params->firma['region'] != 'eu') {
            $tab = 2;
         }

         else {
            $region = $this->db->querySingleValue("SELECT region FROM #__laender WHERE id = ".$_SESSION['wk_land']);

            if ($region == 'eu') {
               $tab = 2;
            }
            else {
               $tab = 3;
            }
         }
      }

      if ((int)$artikel->steuersatz == 0) {
         $artikel->steuersatz = 1;
      }

      $preis   = 0;
      $steuer  = 0.00;
      $ust_txt = '';
      $tax     = 'tax'.$artikel->steuersatz;
      $netto   = $artikel->netto;
      $angebot = 0;

      if ($artikel->angebot_active == 'y') {
         $angebot = $artikel->angebot;
      }

      $sonderpreis = $angebot;

      // Preise in gewählte Währung umrechnen
      $waehrung = $this->params->waehrung;
      $preis    = $artikel->netto;
      $steuer   = $this->params->firma[$tax];

      // Umsatzsteuer aktiv
      if ($tax_active) {
         // Preis mit Umsatzsteuer anzeigen
         if ($this->params->firma['tax_show'] == 'y') {
            $preis       *= (1 + $steuer / 100);
            $sonderpreis *= (1 + $steuer / 100);
            $ust_txt      = $this->text->get('art-detail', 'preis_brutto') . ' ' . $this->params->firma[$tax] . '% ' . $this->text->get('art-detail', 'preis_ust');
         }

         else {
            $ust_txt = $this->text->get('art-detail', 'preis_netto') . ' ' . $this->params->firma[$tax] . '% ' . $this->text->get('art-detail', 'preis_ust');
         }

         $this->hat_ust = true;
      }

      // Ohne USt
      else {
         $preis       = $netto;
         $sonderpreis = $angebot;
         $ust_txt     = $this->text->get('article', 'preis_ausland');
      }

      // Steuersatz 0%
      if ((int)$artikel->steuersatz == 3) {
         $ust_txt = $this->text->get('article', 'preis_kleing');
      }

      // Kleingewerbe anzeigen
      if ($this->params->firma['kleingewerbe'] == 'y') {
         $ust_txt = $this->text->get('article', 'preis_kleing');
      }

      // Kann durch Berechnungen != 0 werden
      if ($angebot < 0.01) {
         $sonderpreis = 0;
      }

      $ge_preis   = 0;
      $ge_text    = '';
      $ge_text_wk = '';

      // Grundeinheit, wie bei Artikel-Details angezeigt
      if ($artikel->ge_netto_aktiv == 'y') {
         $ge_netto = $artikel->ge_netto;  // In DB abhängig von Netto / Angebot gespeichert

         if ($tax_active && $this->params->firma['tax_show'] == 'y') {
            $ge_preis = Helper::number_format($ge_netto * (1 + $steuer / 100), 2, ',', '.');
         }

         else {
            $ge_preis = Helper::number_format($ge_netto, 2, ',', '.');
         }

//         $ge_text    = '<span class="ge_wrapper">'.$ge_preis . '</span> ' . $waehrung . ' ' . $this->text->get('article', 'je') . ' ' . $this->text->get('ge',$artikel->grundeinheit);
//         $ge_text_wk = '<span id="ge_wrapper">'.$ge_preis . '</span> ' . $waehrung . ' ' . $this->text->get('article', 'je') . ' ' . $this->text->get('ge',$artikel->grundeinheit);
         $ge_text    = '<span class="ge_wrapper angebot text_klein">'.$ge_preis .'</span>'.$waehrung.' '. $this->text->get('article', 'je').' '.$this->text->get('ge',$artikel->grundeinheit);
         $ge_text_wk = '<span id="ge_wrapper angebot text_klein">'.$ge_preis.'</span> '.$waehrung.' '.$this->text->get('article', 'je') .' '. $this->text->get('ge',$artikel->grundeinheit);
      }

      $this->preis               = $preis;
      $this->sonderpreis         = $sonderpreis;
      $this->sonderpreis_prozent = 0;

      if ($preis > $sonderpreis && $sonderpreis > 0) {
         $this->sonderpreis_prozent = round((1 - $sonderpreis/$preis) *100);
      }

      $this->steuer              = $steuer; // steuersatz
      $this->ust_txt             = $ust_txt;
      $this->grundeinheit        = $ge_text;
      $this->grundeinheit_wk     = $ge_text_wk;

      // exkl. Versandkosten als Default
      $versandkosten_incl = false;

      // indiv. Versandkosten
      if (((int)$this->params->firma['versandart_'.$tab] == 1 || (int)$this->params->firma['versandart_'.$tab] == 5) && (float)$artikel->versand_preis == 0) {
         $versandkosten_incl = true;
      }

      // Pauschale Versandkosten
      if ((int)$this->params->firma['versandart_'.$tab] == 2) {
         $versandkosten = json_decode($this->params->firma['versandkosten_'.$tab]);

         if ($versandkosten->versandkosten1 == 0 && $versandkosten->versandkosten2 == 0 && $versandkosten->versandkosten3 == 0) {
            $versandkosten_incl = true;
         }
      }

      // Gew.abhängige Versandkosten
      if ((int)$this->params->firma['versandart_'.$tab] == 3) {
         // Versandkosten als JSON
         $vj  = json_decode($this->params->firma['versandkosten_'.$tab]);
         $gew = (float)$artikel->gewicht;

         if ($gew <= $vj->gewichtwert1 && $vj->gewichtkosten1 == 0) {
            $versandkosten_incl = true;
         }

         else if ($gew <= $vj->gewichtwert2 && $vj->gewichtkosten2 == 0) {
            $versandkosten_incl = true;
         }

         else if ($gew <= $vj->gewichtwert3 && $vj->gewichtkosten3 == 0) {
            $versandkosten_incl = true;
         }

         else if ($gew <= $vj->gewichtwert4 && $vj->gewichtkosten4 == 0) {
            $versandkosten_incl = true;
         }

         else if ($gew > $vj->gewichtwert4 && $vj->gewichtkosten5 == 0) {
            $versandkosten_incl = true;
         }
      }

      // Versandkosten pro Stück
      if ((int)$this->params->firma['versandart_'.$tab] == 4  && (float)$this->params->firma['versand_stueck_'.$tab] == 0) {
         $versandkosten_incl = true;
      }

      // Versandkostenfrei ab 0
      if ($this->params->firma['check_vers_frei_'.$tab] == 'y'  && (float)$this->params->firma['vers_frei_'.$tab] == 0) {
         $versandkosten_incl = true;
      }

      // Versandkostenfrei <= Artikelpreis
//      if ($this->params->firma['check_vers_frei_'.$tab] == 'y'  && (float)$this->params->firma['vers_frei_'.$tab] <= $preis) {
//         $versandkosten_incl = true;
//      }

      // Bei Modul Spedition
      if (defined('CONF_MODULE_SPEDITION') && (int)$artikel->spedition > 0) {
         // Speditionspreis == 0
         if (json_decode($this->params->firma['versandkosten_'.$tab])->{'spedition_preis'.(int)$artikel->spedition} == 0) {
            $versandkosten_incl = true;
         }

         else {
            $versandkosten_incl = false;
         }
      }

      $this->versandkosten_incl = $versandkosten_incl;

      return;
   }

   private function _checkTimer() {
      // Artikel nach Ablauf Timer deaktivieren
      $this->db_extern->query("UPDATE #__articles AS a, #__articles_info as i SET a.online = 'n', i.timer_check = 'n'
                          WHERE i.timer_check = 'y' AND i.timer_end < NOW() AND i.timer_art_disable = 'y' AND a.parent_id = i.id");

      // Sonderpreis nach Ablauf Timer deaktivieren
      $this->db_extern->query("UPDATE #__articles AS a, #__articles_info as i SET a.angebot_active = 'n', i.timer_check = 'n'
                          WHERE i.timer_check = 'y' AND i.timer_end < NOW() AND i.timer_art_disable = 'n' AND a.parent_id = i.id");

      return;
   }

   // Artikeldaten anhand shop_articles/id lesen, mit Einträgen aus #__shop_info und Namen Merkmale/Werte Kategorie
   // Bei Kategorie-Mixer wird mixer_kategorie.module.php verwendet
   public function getArticleById($art_id, $lang = '', $lang_kunde = '', $foto_set = 0, $foto_sort = 0, $no_rabatt = false) {
      if ($art_id < 1) {
         return false;
      }

      // Artikel Details aus DB lesen
      if ($lang == '') {
         $lang = $this->params->selected_lang;
      }

      if ($lang_kunde == '') {
         $lang_kunde = 'deu';
      }

      $sql = "SELECT DISTINCT i.id, i.id AS parent_id, i.childs, i.steuersatz, i.name_$lang AS artikel_name, i.desc_$lang AS `artikel_text`,
                 i.name_$lang_kunde AS artikel_name2, i.desc_$lang_kunde AS `artikel_text2`, i.spalten2_check, i.haendler_id, i.image, i.image AS pict01,
                 i.staffelung, i.versand_preis, i.grundeinheit, i.grundeinheit_rechner, a.ge_netto, i.ge_netto_aktiv, i.widerruf, i.lieferfrist, i.is_foto,
                 i.masse_check, i.masse_komma, i.masse_min, i.rechner_check, i.rechner_mode, i.motiv_uploadp_check, i.motiv_uploadt_check, i.artikelgruppe, i.vpe, i.vpm, i.marke,
                 i.gewicht, i.configurator AS artikel_configurator, i.configurator_check, i.config_einheit_check, i.config_menge_check,
                 i.timer_check, i.timer_end, i.timer_menge, i.timer_anzeige, i.timer_art_disable, i.show_object, i.fsk_check, i.foto_set, i.neu_check, i.ab_check, i.marke_aktiv,
                 i.image_hover, versandfrei_check, artikelgrafik1_check, artikelgrafik2_check, artikelgrafik3_check, artikelgrafik4_check, artikelgrafik5_check, artikelgrafik6_check,
                 i.mixer_artikel_check, i.naehrwerte_check, i.mixer_gewicht_check, i.mixer_gewicht, i.mixer_naehrwerte_check, i.spedition,
                 a.id as art_id, a.parent_id, a.art_nr, a.netto, a.angebot, a.angebot_active, a.menge, a.startbild,
                 a.filename, a.filetyp, a.merkmal1, a.merkmal2, a.wert1, a.wert2, a.gtin, a.mpn, a.matrix, a.ge_menge, a.startbild,
                 m1.merkmal_$lang as merkmal1_name, m2.merkmal_$lang as merkmal2_name, w1.wert_$lang as wert1_name, w2.wert_$lang as wert2_name,
                 c.name_$lang AS cat_name, i.energy_efficiency as energy_efficiency, i.energy_efficiency_image as energy_efficiency_image
              FROM #__articles as a
                 LEFT JOIN #__articles_info AS i
                    ON i.id = a.parent_id
                 LEFT JOIN #__merkmale AS m1
                    ON a.merkmal1 = m1.id
                 LEFT JOIN #__merkmale AS m2
                    ON a.merkmal2 = m2.id
                 LEFT JOIN #__werte AS w1
                    ON a.wert1 = w1.id
                 LEFT JOIN #__werte AS w2
                    ON a.wert2 = w2.id
                 LEFT JOIN #__article_to_cats AS ac
                    ON ac.parent_id = i.id
                 LEFT JOIN #__categories AS c
                    ON ac.cat_id = c.id
              WHERE a.id = $art_id
                 AND ac.sort = 0";

      if (!$this->params->isAdmin) {
         $sql .= " AND online = 'y'";
      }

      $sql .= " AND i.id = (SELECT parent_id FROM #__articles WHERE id = $art_id)";
      $data = $this->db_extern->querySingleObject($sql);

      if ($data) {
         $data->images = $this->db_extern->queryAllObjects("SELECT id, sort, image FROM #__articles_images WHERE parent_id = $data->id ORDER BY sort");

         $data->spedition = (int)$data->spedition;

         if ((int)$data->merkmal1 == 0) {
            $data->merkmal1_name = '';
         }

         if ((int)$data->wert1 == 0) {
            $data->wert1_name = '';
         }

         if ((int)$data->merkmal2 == 0) {
            $data->merkmal2_name = '';
         }

         if ((int)$data->wert2 == 0) {
            $data->wert2_name = '';
         }

         if ($data->ge_netto_aktiv == '') {
            $data->ge_netto_aktiv = 'n';
         }

         // Foto-Artikel
         if ($data && defined('CONF_FOTOGRAF') && $foto_set > 0 && $foto_sort > 0) {
            $foto_ext = $this->db_extern->querySingleObject("SELECT name, price FROM #__foto_data WHERE foto_set = ".$foto_set." AND sort = ".$foto_sort);

            if (!$foto_ext) {
               $foto_ext = $this->db_extern->querySingleObject("SELECT name, price FROM #__foto_data WHERE foto_set = 1 AND sort = ".$foto_sort);
            }

            $data->netto = (float)$foto_ext->price;
            $data->artikel_name  .= ' '.str_replace('[MAX]', '', $foto_ext->name);
            $data->artikel_name2 .= ' '.str_replace('[MAX]', '', $foto_ext->name);
         }

         if ($data && defined('CONF_MODULE_RABATTE') && !$no_rabatt) {
            $berechnung = Control::getBerechnungen();
            $berechnung->rabatt($data);
         }

         $data->mixer      = '';
         $data->naehrwerte = '';
         $data->zutaten    = '';
         $data->cat_id     = 0;

         // Wird als Objekt zurückgegeben
         if ($data->naehrwerte_check == 'y') {
            $data->naehrwerte       = $this->db_extern->querySingleObject("SELECT * FROM #__articles_naehrwerte WHERE parent_id = $data->parent_id");
            $data->zutaten          = [];
            $data->zutaten['shop']  = $this->db_extern->queryAllObjects("SELECT * FROM #__articles_zutaten WHERE parent_id = $data->parent_id AND lang = '$lang' ORDER BY title");
            $data->zutaten['kunde'] = $this->db_extern->queryAllObjects("SELECT * FROM #__articles_zutaten WHERE parent_id = $data->parent_id AND lang = '$lang_kunde' ORDER BY title");
         }
      }

      return $data;
   }
}