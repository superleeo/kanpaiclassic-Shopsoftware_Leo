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
require_once SHOP_PATH.'/classes/base/articles_base.class.php';

class KANPAICLASSIC_articles extends KANPAICLASSIC_articlesBase {
   private $data                 = [];
   private $anzahl;

   public  $parent_id            = 0;
   private $staffelpreis         = '';
   public  $steuer               = 0.00;
   public  $preis_s              = 0.0; // Netto/Angebot zur Berechnung Staffelpreise
   public  $merkmal1             = 0;
   public  $merkmal2             = 0;
   public  $wert1                = 0;
   public  $wert2                = 0;
   public  $configurator_val     = '';
   public  $configurator_check   = 'n';
   public  $matrix               = 'n';
   public  $haendler_nr          = '';

   public  $merkmal1_txt         = '';
   public  $merkmal1_txt_raw     = '';
   public  $merkmal2_txt         = '';
   public  $merkmal2_txt_raw     = '';
   public  $wert1_opt            = '';
   public  $wert2_opt            = '';

   public  $wert1_arr;
   public  $wert2_arr;
   public  $staffel_zahl         = 0;
   public  $rechner_check        = 'n';
   public  $config_menge_check   = 'y';
   public  $config_einheit_check = 'n';

   public function __construct() {
      parent::__construct();
   }

   public function setData($articles_ids) {
      $this->loadArticles(0, $articles_ids);
   }

   public function loadArticles($max_anzahl = 0, $articles_ids = []) {
      if (($this->params->task == '' || $this->params->task == 'designLivedesigner' ) && $this->params->firma['startseite_artikel'] == 'reihen' && (int)$this->params->firma['startseite_reihen'] == 0) {
        // $this->params->hide_articles = true;
      }

      if ($this->params->hide_articles) {
         $this->data                = [];
         $this->anzahl              = 0;
         $this->params->artikel_max = 0;
         $this->params->art_anzahl  = 0;

         return;
      }

      // Alle Artikel auslesen, da die Anzahl erfasst werden muss unter Berücksichtigung cat_pass
      $lang    = $this->params->selected_lang;
      $filter  = '';

      $sql     = "SELECT i.id as parent, i.id AS parent_id, i.steuersatz,  i.name_$lang AS art_name, i.desc_$lang AS artikel_text, i.haendler_id, i.childs, i.image, i.image AS pict01,
                         i.lieferfrist, i.show_object, i.fsk_check, i.staffelung, i.versand_preis, i.childs, i.is_foto, i.marke, i.configurator, i.configurator_check,
                         i. gewicht, i.spedition, i.config_einheit_check, i.config_menge_check, i.rechner_check, i.mixer_artikel_check,
                         i.timer_check, i.timer_end, i.timer_menge, i.timer_anzeige, i.timer_art_disable,
                         i.image_hover, versandfrei_check, artikelgrafik1_check, artikelgrafik2_check, artikelgrafik3_check, artikelgrafik4_check, artikelgrafik5_check, artikelgrafik6_check,
                         i.sortierung, i.grundeinheit, i.ge_netto_aktiv, i.motiv_uploadp_check, i.motiv_uploadt_check, i.artikelgruppe, i.neu_check, ab_check,
                         a.id as id, a.id AS article_id, a.netto, a.angebot, a.angebot_active, a.menge, a.ge_netto, a.mpn, a.gtin,
                         (CASE WHEN (a.angebot_active = 'y') THEN a.angebot ELSE a.netto END) AS preis_sort,
                         ac.cat_id,
                         c.cat_pass, c.name_$lang AS cat_name,
                         a.merkmal1 AS mm1_val, a.merkmal2 AS mm2_val, a.wert1 AS w1_val, wert2 AS w2_val,
                         m.merkmal_$lang as merkmal1, w.wert_$lang as wert1, mm.merkmal_$lang as merkmal2, ww.wert_$lang as wert2,
                         (SELECT MIN(netto) FROM #__articles WHERE parent_id = i.id AND online = 'y' AND angebot_active != 'y') AS preis_min,
                         (SELECT MIN(angebot) FROM #__articles WHERE parent_id = i.id AND online = 'y' AND angebot_active = 'y') AS sonderpreis_min
                     FROM #__articles_info as i
                  LEFT JOIN #__articles as a
                     ON a.parent_id = i.id
                  LEFT JOIN #__article_to_cats AS ac
                     ON ac.parent_id = a.parent_id
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
                  WHERE online = 'y'";

      if ($this->params->cat_list != '') {
         $sql .= " AND ac.cat_id IN (" . $this->params->cat_list . ")";
      }

      else if ($this->params->cats_active != '') {
         $sql .= " AND ac.cat_id IN (" . $this->params->cats_active . ")";
      }

      $sortierung = ' i.sortierung, i.id DESC';

      if (defined('CONF_MODULE_SORTIERUNG')) {
         if (!isset($_SESSION['module_sortierung'])) {
            $_SESSION['module_sortierung'] = 1;
         }

         switch($_SESSION['module_sortierung']) {
            // neueste Artikel
            case 1:
               $sortierung = ' i.sortierung, i.id DESC ';
               break;

            // Preis aufsteigend
            case 2:
               $sortierung = ' preis_sort ASC, i.id DESC';
               break;

            // Preis Absteigend
            case 3:
               $sortierung = ' preis_sort DESC, i.id DESC';
               break;

            // A bis Z
            case 4:
               $sortierung = ' art_name ASC, i.id DESC';
               break;

            // Z bis A
            case 5:
               $sortierung = ' art_name DESC, i.id DESC';
               break;

         }
      }

      if (isset($_SESSION['kategoriefilter'])) {
         if (count($_SESSION['kategoriefilter']['marken']) > 0) {
            $filter .= " AND (";

            foreach ($_SESSION['kategoriefilter']['marken'] as $m) {
               $filter .= " i.marke = '$m' OR";
            }

            $filter = rtrim($filter, ' OR');
            $filter .= ") ";
         }

         if ($_SESSION['kategoriefilter']['merkmal1'] > 0 && count($_SESSION['kategoriefilter']['werte1']) > 0) {
            $filter .= " AND (";

            foreach ($_SESSION['kategoriefilter']['werte1'] as $w) {
               $filter .= " a.merkmal1 = ".$_SESSION['kategoriefilter']['merkmal1']." AND wert1 = $w OR";
            }

            $filter = rtrim($filter, ' OR');
            $filter .= ") ";
         }

         if ($_SESSION['kategoriefilter']['merkmal2'] > 0 && count($_SESSION['kategoriefilter']['werte2']) > 0) {
            $filter .= " AND (";

            foreach ($_SESSION['kategoriefilter']['werte2'] as $w) {
               $filter .= " a.merkmal2 = ".$_SESSION['kategoriefilter']['merkmal2']." AND wert2 = $w OR";
            }

            $filter = rtrim($filter, ' OR');
            $filter .= ") ";
         }
      }

      if (!empty($articles_ids)) {
         // $sql .= " AND i.id IN (". implode(',', $articles_ids).") ";
         $sql       .= " AND a.id IN (". implode(',', $articles_ids).") ";
         $sortierung = 'i.id';
      }

      $sql .= " $filter GROUP BY parent ORDER BY $sortierung";
      $anzahl = $this->db_extern->query($sql);

      // min. 1 Artikel vorhanden ?
      if ($anzahl) {
         $max_anzahl = 0;

         // Startseite und Artikel begrenzt ?
//         if (($this->params->task == '' || $this->params->task == 'designLivedesigner' ) && $this->params->firma['startseite_artikel'] == 'reihen') {
         if ($this->params->task == 'designLivedesigner') {
            if (defined('CONF_RESPONSIVE')) {
               // $max_anzahl = CONF_ARTIKEL_PRO_REIHE * (int)$this->params->firma['startseite_reihen'];
               $max_anzahl = 12 * (int)$this->params->firma['startseite_reihen'];
            }

            else {
               $addart = 0;

               if ($this->params->firma['startseite_breite'] == 'breit') {
                  $addart = 1;
               }

               $max_anzahl = ((CONF_ARTIKELZEILE + $addart) * (int)$this->params->firma['startseite_reihen']);
            }
         }else { // Nicht Startseite
            $max_anzahl = $_SESSION['artikel_reihen'] * $_SESSION['artikel_pro_reihe'];
         }

         if($max_anzahl<=0){ // Topartikel sind sonst nicht sichtbar bei 0. TODO: weiter einschränken wegen eventuellen Seiteneffekten
             $max_anzahl=12;
         }


         $artikel_seite = 1;

         if (defined('CONF_RESPONSIVE')) {
            $artikel_seite = $_SESSION['artikel_seite'];
            $test = ($artikel_seite - 1) * $max_anzahl;

            if ($test >= $anzahl) {
               $_SESSION['artikel_seite'] = (int)floor($anzahl / $max_anzahl) + ($anzahl % $max_anzahl > 0 ? 1 : 0);
            }
         }

         else {
            $artikel_seite = $this->params->artikel_seite;
//            $test = $artikel_seite;
         }

         $min = 0;
         $max = 0;
         // $loaded = $this->params->postInt('artikel_anzahl');
         $min = ($artikel_seite - 1) * $max_anzahl;
         $max = $min + $max_anzahl - 1;
         $i = 0;
         $c = 0;

         // Artikel-Daten in Array einlesen
         while ($dbdata = $this->db_extern->getObject()) {
            // Artikel in PW-Geschützen Kategorien ausschlißen, wenn sie nicht freigeschaltet
            if ($dbdata->cat_pass == '' || (isset($_SESSION['cat_pass'][$dbdata->cat_id]) && $_SESSION['cat_pass'][$dbdata->cat_id] == $dbdata->cat_pass)) {
               if ($i >= $min && $i <= $max) {
                  $c++;

                  // Merkmale / Were = 0 ausschließen
                  if ((int)$dbdata->mm1_val == 0) {
                     $dbdata->merkmal1 = '';
                  }

                  if ((int)$dbdata->w1_val == 0) {
                     $dbdata->wert1 = '';
                  }

                  if ((int)$dbdata->mm2_val == 0) {
                     $dbdata->merkmal2 = '';
                  }

                  if ((int)$dbdata->w2_val == 0) {
                     $dbdata->wert2 = '';
                  }

                  if ((float)$dbdata->preis_min > 0 && (float)$dbdata->sonderpreis_min > 0) {
                     $dbdata->netto_min = min((float)$dbdata->preis_min, (float)$dbdata->sonderpreis_min);
                  }

                  else if ((float)$dbdata->sonderpreis_min > 0) {
                     $dbdata->netto_min = (float)$dbdata->sonderpreis_min;
                  }

                  else {
                     $dbdata->netto_min = (float)$dbdata->preis_min;
                  }

                  $this->data[] = $dbdata;
               }

               // Alle durchgehen, um Anzehl festzustekken
               $i++;
            }
         }

         if (!empty($articles_ids)) {
            $sort_data  = $this->data;
            $this->data = [];

            foreach ($articles_ids as $v) {
               for ($s = 0; $s < count($sort_data); $s++) {
                  if (isset($sort_data[$s]->article_id) && (int)$sort_data[$s]->article_id == (int)$v) {
                     $this->data[] = $sort_data[$s];
                     break;
                  }
               }
            }
         }

         $this->anzahl              = $i;

         if ($this->params->artikel_max == 0) {
            $this->params->artikel_max = $i;
         }

         $this->params->art_anzahl  = $c;
      }
   }

   public function renderData($data, $promo) {
      $this->data = $data;
      $this->anzahl = count($data);
      $html = $this->renderList($promo, false);

      return $html;
   }

   public function loadArticlesZubehoer($parent_id, $max = 0) {
      $zubehoer   = Control::getModuleZubehoer();
      $data       = $zubehoer->getData($parent_id);

      $this->data = $data;
      $anzahl     = (is_array($data) ? count($data) : 0);

      if ($max > 0 && $anzahl > $max) {
         $anzahl = $max;
      }

      $this->anzahl = $anzahl;
//      $this->params->artikel_max = $anzahl;
      $this->params->art_anzahl = $anzahl;

      return $anzahl;
   }

   public function loadArticlesAehnliche($parent_id, $max = 0) {
      $aehnliche  = Control::getModuleAehnliche();
      $data       = $aehnliche->getData($parent_id);

      $this->data = $data;
      $anzahl = (is_array($data)? count($data) : 0);

      if ($max > 0 && $anzahl > $max) {
         $anzahl = $max;
      }

      $this->anzahl = $anzahl;
      $this->params->art_anzahl  = $anzahl;

      return $anzahl;
   }

   public function loadArticlesLastseen($parent_id = 0) {
      $max        = 0;
      $data       = [];
      $anzahl     = count($_SESSION['last_articles']);
      $jump       = 0;
      $ii         = 0;
      $parent_ids = [];

      for ($i = 0; $i < $anzahl; $i++) {
         $d = $this->getArticleById($_SESSION['last_articles'][$i], '', '', 0, 0, true);

         if ($d) {
            if ((int)$d->parent_id == (int)$parent_id || in_array((int)$d->parent_id, $parent_ids)) {
               $jump++;
               continue;
            }

            $data[$ii] = $d;
            $data[$ii]->id           = $data[$ii]->art_id;
            $data[$ii]->parent       = $data[$ii]->parent_id;
            $data[$ii]->art_name     = $data[$ii]->artikel_name;
            $data[$ii]->configurator = $data[$ii]->artikel_configurator;
            $data[$ii]->mm1_val      = $data[$ii]->merkmal1;
            $data[$ii]->mm2_val      = $data[$ii]->merkmal2;
            $data[$ii]->w1_val       = $data[$ii]->wert1;
            $data[$ii]->w2_val       = $data[$ii]->wert2;
            $data[$ii]->merkmal1     = $data[$ii]->merkmal1_name;
            $data[$ii]->merkmal2     = $data[$ii]->merkmal2_name;
            $data[$ii]->wert1        = $data[$ii]->wert1_name;
            $data[$ii]->wert2        = $data[$ii]->wert2_name;

            $parent_ids[] = $data[$ii]->parent_id;

            $ii++;
         }
      }

      $this->data = $data;

      if ($max > 0 && $anzahl > $max) {
         $anzahl = $max;
      }

      $this->anzahl = $anzahl;
//      $this->params->artikel_max = $anzahl;
      $this->params->art_anzahl = $anzahl;

      return $anzahl - $jump;
   }

   public function getParentByArtid($art_id) {
      return (int)$this->db_extern->querySingleValue("SELECT parent_id FROM #__articles WHERE id = $art_id");
   }

   public function getZubehoerTitle($parent_id) {
      $zubehoer = Control::getModuleZubehoer();
      $data = $zubehoer->getLangData($parent_id);

      return $data->{$this->params->selected_lang};
   }

   public function getAehnlicheTitle($parent_id) {
      $zubehoer = Control::getModuleAehnliche();
      $data = $zubehoer->getLangData($parent_id);

      return $data->{$this->params->selected_lang};
   }

   // Artikel anzeigen, wenn id = 0 als Liste, sonst Detailansicht
   public function render($art_id, &$back, $is_html = true, $mod_sort = true, $no_cache = false, $container = '') {
      // Artikelliste
      if (!$art_id) {
         $back[0] = $this->renderList(false, $mod_sort, $no_cache, $container);

         return;
      }

      $found  = 0;
      $parent = 0;
      $inwk   = true;

      // Test, ob Foto-Artikel
      if (defined('CONF_FOTOGRAF')) {
         $test = $this->db_extern->querySingleObject("SELECT is_foto, org_set, a.parent_id
                                                  FROM #__articles_info AS i, #__articles AS a
                                               WHERE a.id = $art_id
                                                  AND i.id = a.parent_id");

         if ($test && $test->is_foto == 'y') {
            $this->params->foto_set = $test->org_set;
            $this->params->parent_id = $test->parent_id;
            $back = $this->_renderFoto($art_id);
            return;
         }
      }

      // Artikel auf Mengen / lager_leer prüfen
      $sql ="SELECT id, parent_id, menge, online
                FROM #__articles
             WHERE id = $art_id";

      $data = $this->db_extern->querySingleObject($sql);
// var_dump($data);
      if (!is_object($data)) {
         return $back[0] = 'not found old';
      }

      $online = $data->online;

      // Status 'in Warenkorb' deaktivieren?
      if ($online == 'y' && $data->menge <= 0 && $this->params->firma['lager_leer'] == 'n') {
         $inwk = false;
      }

      // Alternativen Subartikel suchen, wenn Artikel deaktiviert oder keine Menge
      if ($online == 'n' /* || $data->menge <= 0 && $this->params->firma['lager_leer'] == 'n' */) {
         // Artikel mit allen Varianten lesen
         $found = 0;
         $sql ="SELECT id, parent_id, menge, online
                   FROM #__articles
               WHERE parent_id = (SELECT parent_id FROM #__articles WHERE id = $art_id)
               ORDER BY sort";
         $this->db_extern->query($sql);

         while ($data = $this->db_extern->getObject()) {
            if ($data) {
               if ($found > 0 || $data->online == 'n') {
                  continue;
               }
            }
         }

         $art_id = $found;
         $this->params->art_id = $found;

         // kein Artikel gefunden, Redirect auf Startseite
         if ($found == 0) {
            return $back[0] = 'not found deactivated';
         }
      }

      $data = $this->getArticleById($art_id);

      if ((int)$data->steuersatz == 0) {
         $data->steuersatz = 1;
      }

      $this->parent_id           = $data->parent_id;
      $this->params->art_name    = $data->artikel_name;
      $this->params->art_id      = $data->art_id;
      $this->params->art_text    = Helper::truncate(strip_tags($data->artikel_text), 250);
      $this->params->parent_id   = $data->parent_id;
      $this->params->widerruf_wk = $data->widerruf;

      // Falls Staffelpreis vorhanden, merken für weitere Verarbeitung
      $this->staffelpreis        = $data->staffelung;
      $this->steuer              = $this->params->firma['tax'.$data->steuersatz];
      $this->preis_s             = $data->netto;

      if ($data->angebot_active == 'y') {
         $this->preis_s = $data->angebot;
      }


      // Merkmale/Werte für spätere Verarbeitung merken
      if ($data->merkmal1 > 0 || $data->merkmal2 > 0) {
         $this->merkmal1 = $data->merkmal1;
         $this->merkmal2 = $data->merkmal2;
         $this->wert1    = $data->wert1;
         $this->wert2    = $data->wert2;
         $this->getMerkmale($data->parent_id);
      }
      else {
         $this->merkmal1 = 0;
         $this->merkmal2 = 0;
         $this->wert1    = 0;
         $this->wert2    = 0;
      }

      $this->configurator_val   = $data->artikel_configurator;
      $this->configurator_check = $data->configurator_check;
      $this->matrix             = $data->matrix;

      $angebot = 0;

      if ($data->angebot_active == 'y') {
         $angebot = $data->angebot;
      }

      if (defined('CONF_MODULE_PORTAL')) {
         $this->haendler_nr = $this->db->querySingleValue("SELECT haendler_nr FROM #__haendler WHERE user_id = $data->haendler_id");
      }

      // $inwk bestimmt Button Bestellen / nicht verfügbar / Vorbestellung
      $back = $this->_detailseite($data, $inwk);
   }

   // Alle Daten Artikel lesen (articles_info + articles) und rabattgruppe berücksichtigen
   private function _detailseite($data, $inwk, $is_foto = false) {
       $detail        = ''; // falls Galerie verwendet wird
       $html          = '';
       $html_foto     = '';
       $preview_html  = '';
       $script        = '';
       $pic_count     = 0;
       $odd           = true;
       $back          = [];
       $startbild     = (int)$data->startbild;
       $parent_id     = (int)$data->parent_id;

       $image_url     = ($this->params->multishop ? \KANPAICLASSIC\Helper::getData('multishop_images') : SHOP_URL).'/'.CONF_PICT_PATH;
       //      $_360grad_imgs = false;


       if (defined('CONF_MODULE_ENERGIEEFFIZIENZLABEL')){


      }



      if (defined('CONF_MODULE_360GRAD')) {
         $_360grad      = Control::getModule360grad();
//         $_360grad_imgs = $_360grad->checkImages($parent_id);
         $this->params->is_360grad = $_360grad->checkImages($parent_id, $startbild);
      }

      if (!defined('CONF_MODULE_VARIANTENBILDER') || $startbild < 1) {
         $startbild = 1;
      }
      $isVideos = false;
      $videos_html = '';
      $videos_image = '';
      if(defined('CONF_MODULE_VIDEO')){
         //if there is videos available
         $productid = $this->params->parent_id;
         $videos =  Control::getModuleVideo()->listVideos($productid);
         if (is_array($videos) && count($videos) > 0){
            $isVideos = true;
            $firstvideolink = Control::getModuleVideo()->getVideoUrl($productid, current($videos));
            $videos_html .= '<div id="videos_wrapper" style="display:none;">'.CR;
            $videos_html .= '   <div style="overflow:hidden;">';
            $videos_html .= '       <div class="videos_html_preview" style="text-align:center;max-height:400px"><video class="videos_preview" style="width:100%; max-height:400px" controls playsinline><source src="'.$firstvideolink.'#t=0.001" type="video/mp4"></video></div>';
            if ( count($videos) > 1 ){
               $videos_html .= '       <div class="videos_html_data" style="display:flex; gap:5px; justify-content:flex-start; overflow:auto; padding-top:5px">';
               foreach ($videos as $ctr => $video){
                  $videolink = Control::getModuleVideo()->getVideoUrl($productid, $video);
                  $videos_html .= '<div style="position:relative; width:25%; max-width:25%; display:flex; align-items:center; justify-content: center">';
                  $videos_html .= '    <video class="videos_popup" style="max-width:calc(100% - 5px);width:calc(100% - 5px); cursor:pointer; max-height:100px;" data-key="'.$ctr.'" onclick="videos_show_preview('.$ctr.')" playsinline><source src="'.$videolink.'#t=0.001" type="video/mp4"></video>';
                  $videos_html .= '    <div style="position:absolute; min-width:calc(100% - 5px);;min-height:100%; background-color: rgba(0,0,0,0.1); cursor:pointer" onclick="videos_show_preview('.$ctr.')"> </div>';
                  $videos_html .= '    <img style="position:absolute; left: 0; right: 0; top: 0; bottom: 0; max-height:90%; max-width:90%; text-align: center; margin: auto; cursor:pointer; " onclick="videos_show_preview('.$ctr.')" src="'.TEMPLATE_URL.'/images/system/video_preview.png"/>';
                  $videos_html .= '</div>';
               } 
               $videos_html .= '       </div>';
            }
            $videos_html .= '   </div>';
            $videos_html .= '</div>';
            
            $video_front_src = TEMPLATE_URL.'/images/system/video_front.png';
         }
         //videos
      }

      // Foto-Modul
      if ($this->params->foto_set > 0 || $isVideos) {
         $anz_promo = 3;

         if (defined('CONF_FOTO_PROMO')) {
            $anz_promo = CONF_FOTO_PROMO;
         }

         $html_foto = '<div class="bg_flaechen bildtitel fliesstext font16"><a class="foto_set_all" href="'.SHOP_URL.'/'.$this->params->selected_lang.'__'.$this->params->foto_set.'">'.$this->text->get('art_detail', 'fotoset').'</a></div>';
         // Andere Bilder des Fotosets als Promo-Artikel
         $html_foto .= $this->_promoArticleFotoset($this->params->foto_set, $anz_promo);
      }

      else {
         // Datailseite Inhalt
         $preview_html = '<div class="bg_flaechen bildtitel fliesstext font16">'.$this->text->get('art_detail', 'bildtitel').'</div>';
      }

      // Datailseite Bilder unter Menü
      $html .= '<div id="details_container">';

      // Vorschaubilder
      $anz_thumbs  = 0;
      $script     .=  'var pict_array = new Array();'.CR;
      $title       = $data->artikel_name;
      $startpic    = '';

      // Modul 360grad und verwendet
      if ($this->params->is_360grad) {
         $detail .= '<div id="view360"></div>';
         $startpic = SHOP_URL.'/'.CONF_PICT_PATH.'360grad/'.$parent_id.'/image_001.jpg';
      }

      else {
         // $data->image + anzahl $data->images
         $anz_thumbs = (is_array($data->images) ? count($data->images) : 0) + 1;

         // Alle Bilder durchgehen
         for ($i = 0; $i <= (is_array($data->images) ? count($data->images) : 0); $i++) {
//            $image = 'nopic.png';
            $image     = '';
            $thumbnail = '';

            // Startbild
            if ($i == 0) {
               $image = ($data->image != '' && $data->image != 'nopic.png' ? $data->image : $image);
            }

            else {
               $image = $data->images[$i - 1]->image;
            }

            // Großes Vorschaubild altes Design
            // 1. Bild Thumbnail bei normalem Template
            // Großes Vorschaubild neues Design
            // Andere Bilder Thumbnail, bei Responsive alle

            // Bild auf anderem FLOW-SHOP
            if (strpos($image, 'http://') !== false || strpos($image, 'https://') !== false) {
               $thumbnail = str_replace('.jpg', '_td.jpg', $image);
            }

            else {
               $thumbnail = ($this->params->multishop ? $image_url.$image.'_td.jpg'.$this->params->firma['article_cache'] : Helper::testPicture($image.'_td.jpg').$this->params->firma['article_cache']);
            }

            // Original auf anderem Server
            if (strpos($image, 'http://') !== false || strpos($image, 'https://') !== false) {
               $picture  = $image.$this->params->firma['article_cache'];
               $original = str_replace('pictures/', 'pictures/original/', $image).$this->params->firma['article_cache'];
            }

            // Bild auf eigenem Server
            else {
               $picture  = $this->params->multishop ? $image_url.$image.'.jpg' : Helper::testPicture($image.'.jpg').$this->params->firma['article_cache'];
               $original = $this->params->multishop ? $image_url.'original/'.$image.'.jpg' : Helper::testPicture('original/'.$image.'.jpg').$this->params->firma['article_cache'];
            }

//            $class     = '';
//            $check     = false;
            $startbild = ($startbild <= $anz_thumbs ? $startbild : 1);

            // Thumbnail vorhanden, Hauptbild in Array aufnehemen (vorladen)
            if ($i == 0 || strpos($picture, 'http://') !== false || strpos($picture, 'https://') !== false || Helper::$pic_status) {
               if ($i + 1 == $startbild) {
                  $startpic   = $picture.$this->params->firma['article_cache'];
               }

               $tab = ($this->params->firma['bild_tab'] == 'y' && !defined('CONF_RESPONSIVE') ? 3 : 1);
               $pic_count++;

               // Altes Design (beauty)
               if ($i == 0 && !defined('CONF_RESPONSIVE')) {
                  $preview_html .= '<div class="pics_first">';
                  $preview_html .= '   <div class="cbp-caption" onclick="Royalart.changeGalery('.($i + 1).');">';
                  $preview_html .= '      <a class="cbp-caption-defaultWrap" href="javascript:void();">';

                  if ($this->params->firma['detailbild'] > 1) {
                     $preview_html .= '         <img class="img_active" src="' . $thumbnail . '" onclick="Royalart.changeTab('.$tab.'); Royalart.changeGalery(' . ($i + 1) . ');" title="'.$title.'" alt="" />';
                  }
                  else {
                     $preview_html .= '         <img class="img_active" src="' . $thumbnail . '" class="art_detail_bild" onclick="Royalart.changeTab('.$tab.'); $(\'#art_detail_bild_pic\').attr(\'src\', pict_array['.($i + 1).'].src;" title="'.$title.'" alt="" />';
                  }

                  $preview_html .= '      </a>';
                  $preview_html .= '   </div>';
                  $preview_html .= '</div>';
               }

               else {
                  $html .= '<div class="pics_'.($odd ? 'left' : 'right') . ' cbp-item"'.(defined('CONF_RESPONSIVE') ? ' style="width:116px;"' : '').'>';
                  $html .= '   <div class="cbp-caption" onclick="Royalart.changeGalery('.($i + 1).');">';
                  $html .= '      <a class="cbp-caption-defaultWrap" href="javascript:void();">';

                  if ($this->params->firma['detailbild'] > 1) {
                     $html .= '         <img class="img_active" src="' . $thumbnail . '" onclick="Royalart.changeTab('.$tab.'); Royalart.changeGalery(' . ($i + 1) . ');" title="'.$title.'" alt="" />';
                  }

                  else {
                     $html .= '      <img class="img_active" src="' . $thumbnail . '" class="art_detail_bild" onclick="Royalart.changeTab('.$tab.'); $(\'#art_detail_bild_pic\').attr(\'src\', pict_array['.($i + 1).'].src);" title="'.$title.'" alt="" />';
                  }

                  $html .= '      </a>';
                  $html .= '   </div>';
                  $html .= '</div>';
               }

               // Bildergalerie
               // 1. nichts
               // 2. <a href="big.jpg" class="MagicZoomPlus" rel="zoom-position: inner">< img src="small.jpg"/></a>
               // 3. <a href="big.jpg" class="MagicZoomPlus" rel="zoom-width:400px; zoom-height:400px">< img src="small.jpg"/></a>
               // 4. <a href="big.jpg" class="MagicZoomPlus" rel="pan-zoom:true">< img src="small.jpg"/></a>
               // MagicZoom
               switch ($this->params->firma['detailbild']) {
                  case 2:
                     $detail .= '<div id="art_detail_bild_' . ($i + 1) . '" class="art_detail_bild"'.(($i + 1) != $startbild ? ' style="display:none;"' : '').'><a href="' . $original . '" class="MagicZoomPlus" rel="'.CONF_MAGICZOOM_2.'; group:detail"><img class="img_active" src="'.$picture.'" title="'.$title.'" alt="'.$title.'" /></a></div>';
                     break;

                  case 3:
                     $detail .= '<div id="art_detail_bild_' . ($i + 1) . '" class="art_detail_bild"'.(($i + 1) != $startbild ? ' style="display:none;"' : '').'><a href="' . $original . '" class="MagicZoomPlus" rel="'.CONF_MAGICZOOM_3.'; group:detail"><img class="img_active" src="'.$picture.'" title="'.$title.'" alt="'.$title.'" /></a></div>';
                     break;

                  case 4:
                     $detail .= '<div id="art_detail_bild_' . ($i + 1) . '" class="art_detail_bild"'.(($i + 1) != $startbild ? ' style="display:none;"' : '').'><a href="' . $original . '" class="MagicZoomPlus" rel="'.CONF_MAGICZOOM_4.'; group:detail"><img class="img_active" src="'.$picture.'" title="'.$title.'" alt="'.$title.'" /></a></div>';
                     break;

                  case 1:
                  default:
                     $detail .= '<div id="art_detail_bild_' . ($i + 1) . '" class="art_detail_bild"'.(($i + 1) != $startbild ? ' style="display:none;"' : '').'><img class="img_active" src="'.$picture.'" title="'.$title.'" alt="'.$title.'" /></div>';
                     $script .=  'pict_array['.($i + 1).'] = new Image();'.CR;
                     $script .=  'pict_array['.($i + 1).'].src = "'. $picture.'";'.CR;
                     break;
               }

               //add videos to the end of images
               if ($isVideos && $i == (is_array($data->images) ? count($data->images) : 0)){

                  $videos_image .= '<div class="pics_'.($odd ? 'left' : 'right') . ' cbp-item"'.(defined('CONF_RESPONSIVE') ? ' style="width:116px;"' : '').'>';
                  $videos_image .= '   <div class="cbp-caption" onclick="showVideosPopup()">';
                  $videos_image .= '      <a class="cbp-caption-defaultWrap" href="javascript:void();">';
                  $videos_image .= '         <img class="img_active" src="' . $video_front_src . '"  />';
                  $videos_image .= '      </a>';
                  $videos_image .= '   </div>';
                  $videos_image .= '</div>';

                  if ($i >= 1){
                     $html.= $videos_image;
                  }else{
                     $videos_image = '<div id="details_container">'.$videos_image.'</div>';
                  }
               }
               

               $odd = !$odd;
            }
         } // for

         $script .= '$("a.cbp-caption-defaultWrap").click( function(e) { e.preventDefault(); });'.CR;
         $html .= '</div>';

         $script = Helper::htmlScript($script);

         // Foto-Artikel
         if ($html_foto != '') {
            $back[1] = $html_foto.'<div style="display:none">'.$html.'</div>';
         }

         // Normaler Artikel
         else {
            $back[1] = $preview_html.$html;
         }
      } // for

      $detail_script = '';
      $preview_html = $html;

      // Template einbinden
      include_once TEMPLATE_PATH.'/article_details.tpl.php';
      $back[0] = $html;
      $this->params->details_script = $script.$detail_script;
      return $back;
   }

   private function _renderFoto($art_id) {
      $data            = null;
      $preise          = null;
      $module_foto     = Control::getModuleFoto();

      $module_foto->getData($data, $preise, $art_id);
      $this->parent_id = $data->parent_id;

      $foto_data = [];
      $max_foto = max((int)$data->foto_size_x, (int)$data->foto_size_y);
      $faktor = (int)$data->foto_size_x / $max_foto;
      $steuer = $this->params->firma['tax'.$data->steuersatz];

      $tax_active = false;
      if (Helper::checkSteuer($_SESSION['rechnung_land'], $_SESSION['wk_land']) && $this->params->firma['tax_active'] == 'y') {
         $tax_active = true;
      }

      for ($i = 0; $i < count($preise); $i++) {
         $foto_x = 0;
         $foto_y = 0;
         // Größe längere Kante
         $size = (int)$preise[$i]->size;

         if (strstr($preise[$i]->name, '[MAX]')) {
            $name = str_replace('[MAX]', '', $preise[$i]->name);
            $foto_x = $data->foto_size_x;
            $foto_y = $data->foto_size_y;
         }

         else {
            $name = $preise[$i]->name;

            // Landscape
            if ((int)$data->foto_size_x > (int)$data->foto_size_y) {
               if ($size > $data->foto_size_x) {
                  continue;
               }
               $foto_x = $size;
               $foto_y = floor((int)$data->foto_size_y * $size / (int)$data->foto_size_x);
            }

            // Portait
            else {
               if ($size > $data->foto_size_y) {
                  continue;
               }
               $foto_x = floor((int)$data->foto_size_x * $size / (int)$data->foto_size_y);
               $foto_y = $size;
            }
         }

         $netto = (float)$preise[$i]->price;
         $preis = Helper::number_format($netto, 2, ',', '.');

         if ($tax_active) {
            // Preis mit Umsatzsteuer anzeigen
            if ($this->params->firma['tax_show'] == 'y') {
               $brutto = $netto * (1 + $steuer / 100);
               $preis = Helper::number_format($brutto, 2, ',', '.');
            }
         }

         $foto_data[$i] = [$name, $foto_x.' x '.$foto_y, $preis, $preise[$i]->foto_set, $preise[$i]->sort];
      }
      $data->fotodata = $foto_data;
      return $this->_detailseite($data, true, true);
   }

   public function renderListMixer($promo = false, $mod_sort = true, $no_cache = false, $container = '') {
      return $this->renderList($promo, $mod_sort, $no_cache, $container);
   }

   // Artikel-Liste anzeigen / Bei ZubehoerModule: $this->data von Module generiert
   private function renderList($promo = false, $mod_sort = true, $no_cache = false, $container = '') {
      global $mixer;
      $html      = '';
      $image_url = ($this->params->multishop ? \KANPAICLASSIC\Helper::getData('multishop_images') : SHOP_URL).'/'.CONF_PICT_PATH;

      // Keine Artikel vorhanden
      if (!isset($this->data[0]) || !$this->data[0]) {
         return $html;
      }

      // Admin / Livedesigner
      $livedesigner = (isset($GLOBALS['lifedesigner']) ? $GLOBALS['lifedesigner'] : false);

      if (!$promo && !$this->params->isAjax) {
         // Artikelliste
         if ($container == '') {


            $html .= '<div class="article_filter_container" style="position:relative">'.CR;

            if ($livedesigner) {
               $html .= '<div id="live_artikelliste">'.CR;
               $html .= '  <div class="livedesigner live_artikelliste" title="Artikelliste" onclick="Livedesigner.popupArtikel();"></div>'.CR;
               $html .= '</div>'.CR;
            }

            if (!defined('CONF_MODULE_SORTIERUNG') || isset($_SESSION['suche']) || !$mod_sort || (($this->params->task == '' || $this->params->task == 'designLivedesigner') && $this->params->firma['startseite_artikel'] == 'reihen')) {
               $html .= '<div id="filters_container" class="cbp-l-filters-button padding_top"></div>'.CR;
            }

            else {
               include SHOP_PATH.'/classes/modules/sortierung/sortierung.module.php';
               $html .= '<div id="filters_container" class="cbp-l-filters-button padding_top" style="min-height:54px; padding-right:165px;"></div>'.CR;
               $html .= $mod_sort;
            }

            $html .= '<div id="article_container">';

         }

         // Keine Artikelliste
         else {
            if (strstr($container, 'module1_container_') === null) {
               $html .= '<div class="padding_top"></div>'.CR;
            }

            $html .= '<div id="'.$container.'">';
         }
      }

      // im Template verwendet
      $categories   = Control::getCategories();
      $markenfilter = $categories->getMarkenfilter(); // y/n

      // $i wird im Template verwendet
      $ii          = 0;
      $anz_artikel = count($this->data);

      if ($this->anzahl < $anz_artikel) {
         $anz_artikel = $this->anzahl;
      }

      // Artikel pro Zeile
      $art_zeile = CONF_ARTIKELZEILE;

      if ($this->params->task == '') {
         $art_zeile = (CONF_ARTIKELZEILE + ($this->params->firma['startseite_breite'] == 'breit' && $this->params->firma['startseite_artikel'] == 'reihen' ? 1 : 0));
      }

      $tax_active = false;

      if (Helper::checkSteuer($_SESSION['rechnung_land'], $_SESSION['wk_land']) && $this->params->firma['tax_active'] == 'y') {
         $tax_active = true;
      }

      foreach ($this->data as $artikel) {
         $ii++;
         $linie_horiz = false;
         $linie_vert  = false;
         $file        = '';

         if ($ii > $this->anzahl) {
            continue;
         }

         if ($anz_artikel - ($ii -1) > $art_zeile) {
            $linie_horiz = true;
         }

         if ($ii % $art_zeile != 0 && $ii < $anz_artikel) {
            $linie_vert = true;
         }

         if (defined('CONF_MODULE_RABATTE')) {
            $berechnung = Control::getBerechnungen();
            $berechnung->rabatt($artikel);
         }

         // Ergebnis in Klassen-Variablen preis, sonderpreis, steuer, ust_txt, waehrung
         $this->getPrice($artikel, $tax_active);

         $art_name    = str_replace('"', "'", $artikel->art_name);
         $sonderpreis = false;

         if ($artikel->angebot_active == 'y') {
            $sonderpreis = true;
         }

         $werte                = $this->params->getWerte($artikel->merkmal1, $artikel->wert1, $artikel->merkmal2, $artikel->wert2);
         $link                 = $this->params->getLink('artikel', $artikel->id, $art_name, $werte, $artikel->cat_name);
         $marke                = $artikel->marke;
         $configurator         = $artikel->configurator;
         $configurator_check   = $artikel->configurator_check;
         $config_einheit_check = $artikel->config_einheit_check;
         $rechner_check        = $artikel->rechner_check;
         $config_menge_check   = $artikel->config_menge_check;

         // Artikelbild vorhanden und Front-Zoom eingeschaltet
         $thumb_x     = '1px';
         $thumb_y     = '1px';
         $rand        = 'auto';
         $thumb       = '';
         $file        = '';
//         $picture     = $artikel->pict01;
         $picture     = $artikel->image;
         $thumb_hover = ($artikel->image_hover != '' ? SHOP_URL.'/'.CONF_PICT_PATH.$artikel->image_hover.'_tn.jpg' : '');

         if ($picture == '0') {
            $picture = '';
         }

         $picture_big = "";

         // Thumbnail ist Link
         if (strpos($picture, 'http://') !== false || strpos($picture, 'https://') !== false) {
            $thumb_x     = 'auto';
            $thumb_x     = 'auto';
            $rand        = ' margin:auto';
            $thumb       = str_replace('.jpg', '_tn.jpg', $picture).$this->params->firma['article_cache'];
            $picture_big = $picture;
         }

         // Bild vorhanden
         else {
            // altes Template
            if (!defined('CONF_RESPONSIVE')) {
               $file = SHOP_URL.'/'.CONF_PICT_PATH.$picture.'_tn.jpg';

               if (!$this->params->multishop && file_exists($file)) {
                  $thumb = IMAGE_URL.'/'.CONF_PICT_PATH.$picture.'_tn.jpg'.$this->params->firma['article_cache'];
               }

               else if ($this->params->multishop && ($picture != 'nopic.png' || $picture != '')) {
                  $thumb = \KANPAICLASSIC\Helper::getData('multishop_images').'/'.CONF_PICT_PATH.$picture.'_tn.jpg'.$this->params->firma['article_cache'];
               }

               else {
                  $thumb = TEMPLATE_URL.'/images/system/'.CONF_NOPICT;
                  $file = TEMPLATE_PATH.'/images/system/'.CONF_NOPICT;
               }
            }

            // Responsive Design
            else {
               if ($this->params->firma['cpf_size'] == 'gross' || $this->params->firma['cpf_size'] == 'riesig') {
                  $file = SHOP_PATH.'/'.CONF_PICT_PATH.$picture.'_tp.jpg';

                  if (!$this->params->multishop && file_exists($file)) {
                     $thumb = SHOP_URL.'/'.CONF_PICT_PATH.$picture.'_tp.jpg'.$this->params->firma['article_cache'];
                  }

                  else if ($this->params->multishop && $picture != 'nopic.png' && $picture != '') {
                     $thumb = \KANPAICLASSIC\Helper::getData('multishop_images').'/'.CONF_PICT_PATH.$picture.'_tn.jpg'.$this->params->firma['article_cache'];
                  }

                  else {
                     $thumb = TEMPLATE_URL.'/images/system/'.CONF_NOPICT;
                     $file  = TEMPLATE_PATH.'/images/system/'.CONF_NOPICT;
                  }
               }

               else if ($this->params->firma['cpf_size'] == 'klein_prop' || $this->params->firma['cpf_size'] == 'normal_prop') {
                  $file = SHOP_PATH.'/'.CONF_PICT_PATH.$picture.'_tp.jpg';

                  if (!$this->params->multishop && file_exists($file)) {
                     $thumb = SHOP_URL.'/'.CONF_PICT_PATH.$picture.'_tp.jpg'.$this->params->firma['article_cache'];
                  }

                  else if ($this->params->multishop && $picture != 'nopic.png' && $picture != '') {
                     $thumb = \KANPAICLASSIC\Helper::getData('multishop_images').'/'.CONF_PICT_PATH.$picture.'_tn.jpg'.$this->params->firma['article_cache'];
                  }

                  else {
                     $thumb = TEMPLATE_URL.'/images/system/'.CONF_NOPICT;
                     $file  = TEMPLATE_PATH.'/images/system/'.CONF_NOPICT;
                  }
               }

               else {
                  $file = SHOP_PATH.'/'.CONF_PICT_PATH.$picture.'_tn.jpg';

                  if (!$this->params->multishop && file_exists($file)) {
                     $thumb = $image_url.$picture.'_tn.jpg'.$this->params->firma['article_cache'];
                  }

                  else if ($this->params->multishop && $picture != 'nopic.png' && $picture != '') {
                     $thumb = \KANPAICLASSIC\Helper::getData('multishop_images').'/'.CONF_PICT_PATH.$picture.'_tn.jpg'.$this->params->firma['article_cache'];
                  }

                  else {
                     $thumb = TEMPLATE_URL.'/images/system/'.CONF_NOPICT;
                     $file  = TEMPLATE_PATH.'/images/system/'.CONF_NOPICT;
                  }
               }
            }

            $picture_big = ($this->params->multishop ? $image_url.$picture.'.jpg' : Helper::testPicture($picture.'.jpg', $this->params).$this->params->firma['article_cache']);

            if (!$this->params->multishop) {
               $size    = getimagesize($file);
               $thumb_x = round($size[0] / 1.20);
               $thumb_y = round($size[1] / 1.20);
               $rand    = ' margin-left:'.((CONF_THUMB_X - $thumb_x) / 2).'px;';
            }
         }

         // Nur article_container bei Kategorie-Mixer
         if ($container == '' && $mixer) {
            $html .= '   <div class="mixer_over" style="pointer-events:none;">';
         }

         include TEMPLATE_PATH.'/article_list.tpl.php';

         if ($mixer) {
            $html .= '   </div>';
         }
      }

      $html .= '<div class="clear"></div>';

      if (!$promo && !$this->params->isAjax) {
         $html .= '</div>';
         $html .= '<div class="clear"></div>';

         if ($container == '') {
            $html .= '</div>';
         }
      }

      return $html;
   }

   // Zufällige Foto-Artikel des Sets auswählen
   private function _promoArticleFotoset($foto_set, $anzahl) {
      $html = '';

      // Anzahl verfügbarer Artikel feststellen
      $lager = $this->params->firma['lager_leer'];
      $sql = "SELECT i.id
      FROM #__articles_info as i
         LEFT JOIN #__articles as a
            ON a.parent_id = i.id
         WHERE i.org_set = $foto_set
            AND a.sort = 1
            AND a.online = 'y'";

      if ($lager == 'n') {
         $sql .= " AND a.menge > 0 ";
      }

      $p = $this->db_extern->queryAllObjects($sql);

      $promo = [];
      for ($i = 0; $i < count($p); $i++) {
         $promo[] = (int)$p[$i]->id;
      }
      $max_artikel = count($promo) - 1;

      // Zufällige Artikel auslesen
      $zaehler = 0;
      $max = 16;
      $art_array = [];
      $art_array[] = (int)$this->parent_id;

      while ($zaehler < $anzahl && $max > 0) {
         $max--;
         $rand = rand(0, $max_artikel);
         $art_id = $promo[$rand];

         if (!in_array($art_id, $art_array)) {
            $lang = $this->params->selected_lang;
//            $pics = '';
//            for ($i = 1; $i <= $this->params->firma['count_pics']; $i++) {
//               $pics .= sprintf('pict%02d, ', $i);
//            }

            $sql = "SELECT a.id, i.id AS info_id, i.steuersatz, i.name_$lang AS art_name, i.desc_$lang AS artikel_text, i.haendler_id, i.image,
                        i.is_foto, i.motiv_uploadt_check, i.motiv_uploadt_check, i.artikelgruppe, i.marke, i.configurator, i.configurator_check,
                        i. gewicht, i.spedition, i.config_einheit_check, i.config_menge_check,
                        i.timer_check, i.timer_end, i.timer_menge, i.timer_anzeige, i.timer_art_disable,
                        i.staffelung, i.versand_preis, a.netto, a.angebot, a.angebot_active, i.grundeinheit, i.ge_netto_aktiv, a.menge, a.ge_netto
                       FROM #__articles_info as i
                    LEFT JOIN #__articles as a
                       ON a.parent_id = i.id
                    WHERE i.id = $art_id
                       AND a.sort = 1
                       AND a.online = 'y' ";
            if ($lager == 'n') {
               $sql .= " AND menge > 0 ";
            }

            $data = $this->db_extern->queryAllObjects($sql);

            if ($data) {
               $zaehler++;
               $art_array[] = $art_id;
               $this->data = [];
               $this->data = $data;
               $html .= '<div class="promo">';
               $html .= $this->renderList(true, false);
               $html .= '</div>';
               $this->data = [];
            }
         }
      }
      $html .= '<div class="promo"></div>';
      return $html;
   }

   public function loadSuche() {
      if ($this->params->foto_set_list > 0) {
         $this->_loadFotoSet();
         return;
      }

      // $suche = $this->db->escape(str_replace(' ', '%', urldecode($this->params->getString('suchen', '', 'sql'))));
      $suche = $this->db->escape(urldecode(trim($this->params->getString('suchen', '', 'sql'))));
      $lang = $this->params->selected_lang;

      $sql = "SELECT i.id as parent_id, i.steuersatz, i.name_$lang AS art_name, i.desc_$lang AS artikel_text, i.haendler_id, i.childs, image,
                     i.staffelung, i.versand_preis, i.childs, i.motiv_uploadp_check, i.motiv_uploadt_check, i.artikelgruppe, i.marke,
                     i. gewicht, i.spedition, i.configurator, i.configurator_check,i.config_einheit_check, i.config_menge_check, i.rechner_check, i.neu_check, ab_check,
                     i.timer_check, i.timer_end, i.timer_menge, i.timer_anzeige, i.timer_art_disable, i.show_object, i.fsk_check,
                     i.sortierung, i.grundeinheit, i.ge_netto_aktiv, i.is_foto, a.mpn, a.gtin, i.mixer_artikel_check,
                     i.image_hover, versandfrei_check, artikelgrafik1_check, artikelgrafik2_check, artikelgrafik3_check, artikelgrafik4_check, artikelgrafik5_check, artikelgrafik6_check,
                     a.id as id, a.netto, a.angebot, a.angebot_active, a.menge, a.ge_netto, a.merkmal1, a.wert1, a.merkmal2, a.wert2,
                     c.name_$lang AS cat_name, i.energy_efficiency as energy_efficiency, i.energy_efficiency_image as energy_efficiency_image
              FROM #__articles_info as i
              LEFT JOIN #__articles as a
                 ON a.parent_id = i.id
              LEFT JOIN #__article_to_cats AS ac
                 ON ac.parent_id = i.id
              LEFT JOIN #__categories as c
                 ON ac.cat_id = c.id
              WHERE c.cat_pass = ''
                 AND online = 'y'
                 AND ac.sort = 0";

     if ($this->params->cats_active != '') {
         $sql .= " AND ac.cat_id IN (".$this->params->cats_active.") ";
      }

      $sortierung = ' i.sortierung,  i.id DESC';

      if (defined('CONF_MODULE_SORTIERUNG')) {
         if (!isset($_SESSION['module_sortierung'])) {
            $_SESSION['module_sortierung'] = 1;
         }

         switch($_SESSION['module_sortierung']) {
            // neueste Artikel
            case 1:
               $sortierung = ' i.sortierung, i.id DESC';
               break;

            // Preis aufsteigend
            case 2:
               $sortierung = ' a.netto ASC, i.id DESC';
               break;

            // Preis Absteigend
            case 3:
               $sortierung = ' a.netto DESC, i.id DESC';
               break;

            // A bis Z
            case 4:
               $sortierung = ' art_name ASC, i.id DESC';
               break;

            // Z bis A
            case 5:
               $sortierung = ' art_name DESC, i.id DESC';
               break;

         }
      }

      // $sql .= " AND (i.name_$lang LIKE '%".$suche."%' OR i.desc_$lang LIKE '%".htmlentities($suche)."%' OR i.marke LIKE '".$suche."%' OR a.art_nr LIKE '%".$suche."%' OR a.gtin LIKE '%".$suche."%' OR a.mpn LIKE '%".$suche."%')";
      $sql .= " AND (";
      $suche_sql = '';
      $suche_array = explode(' ',$suche);
      foreach ($suche_array as $suche_string){
         $suche_sql .= ($suche_sql == '')? ' ' : ' OR ';
         $suche_string = $this->db->escape(urldecode(trim($suche_string)));   
         $suche_sql .= " i.name_$lang LIKE '%".$suche_string."%' OR i.desc_$lang LIKE '%".htmlentities($suche_string)."%'  OR i.marke LIKE '%".$suche_string."%' OR a.art_nr LIKE '%".$suche_string."%' OR a.gtin LIKE '%".$suche_string."%' OR a.mpn LIKE '%".$suche_string."%'";
      }
      $sql .= $suche_sql;
      $sql .= " )";
      $sql .= " GROUP BY i.id ORDER BY $sortierung";

      $this->data   = $this->db_extern->queryAllObjects($sql);
      $this->anzahl = ($this->data ? count($this->data) : 0);

      return;
   }

   private function _loadFotoSet() {
      $lang = $this->params->selected_lang;
//      $pics = '';
//      for ($i = 1; $i <= $this->params->firma['count_pics']; $i++) {
//         $pics .= sprintf('pict%02d, ', $i);
//      }

      $sql = "SELECT i.id as parent, i.steuersatz, ac.cat_id, i.name_$lang AS art_name, i.haendler_id, i.image, i.lieferfrist,
                     i.staffelung, i.versand_preis, i.childs, i.is_foto, i.marke, i.configurator, i.configurator_check,i.config_einheit_check,
                     i. gewicht, i.spedition, i.config_menge_check, i.timer_check, i.timer_end, i.timer_menge, i.timer_anzeige, i.timer_art_disable, i.neu_check, ab_check,
                     i.sortierung, i.grundeinheit, i.ge_netto_aktiv,  i.motiv_uploadp_check, i.motiv_uploadt_check, i.artikelgruppe,
                     a.id as id, a.netto, a.angebot, a.angebot_active, a.menge, a.ge_netto, c.cat_pass
              FROM #__articles_info as i
              LEFT JOIN #__articles as a
                 ON a.parent_id = i.id
              LEFT JOIN #__article_to_cats AS ac
                 ON ac.parent_id = i.id
              LEFT JOIN #__categories as c
                 ON ac.cat_id = c.id
              WHERE i.org_set = ".$this->params->foto_set_list."
                 AND a.sort = 1
                 AND a.online = 'y'
                 AND ac.sort = 0";
      if ($this->params->firma['lager_leer'] == 'n') {
         $sql .= " AND a.menge > 0";
      }

      $sql .= " ORDER BY i.sortierung, i.id DESC";


      $anzahl = $this->db_extern->query($sql);
      $this->anzahl = $anzahl;

      // min. 1 Artikel vorhanden
      if ($anzahl) {
         $art_seite = $_SESSION['artikel_seite'];
         if ($art_seite < 1) {
            $art_seite = 1;
         }

         $min = ($art_seite -1) * $this->params->art_anzahl;
         $max = $min + $this->params->art_anzahl - 1;
         $i = 0;

         // Artikel-Daten in Array einlesen
         while ($dbdata = $this->db_extern->getObject()) {
            if ($dbdata->cat_pass == '' || (isset($_SESSION['cat_pass'][$dbdata->cat_id]) && $_SESSION['cat_pass'][$dbdata->cat_id] == $dbdata->cat_pass)) {
               if ($i >= $min and $i <= $max) {
                  $this->data[] = $dbdata;
               }
               if ($i > $max) {
                  break;
               }
               $i++;
            }
         }
      }
      return $this->renderList(false, false);
   }

   public function getCounter() {
      // Artikel pro Seite Default
      $seite   = 1;
      $art_min = 0;
      $art_max = 0;
      $limit   = 0;

      $html  = '';
      $html .= '<div class="padding_bottom">';
      $html .= "<div class='pager_left col_ll_l'><span class='anz_seiten_text fliesstext text_normal'>".$this->text->get('categorie', 'counter_r', 'lang')."</span>";

      if (defined('CONF_RESPONSIVE')) {
         $seite   = $_SESSION['artikel_seite'];
         $art_min = CONF_ARTZEILEN_MIN;
         $art_max = CONF_ARTZEILEN_MAX ;
         $limit = $_SESSION['artikel_reihen'];


         for ($i = $art_min; $i <= $art_max; $i += $art_min) {
            if ($i == $limit) {
               $class = " active";
            }
            else {
               ($class = "");
            }
            $html .= "<span class='anz_seiten fliesstext text_normal pointer txt_menu$class' onclick='changeAnzahl($i);'>$i</span>";
         }

         // Bei Startseite / normale Anzeige nicht gesetzt -> Fehlermeldung
         if (!isset($_SESSION['artikel_pro_reihe']) || $_SESSION['artikel_pro_reihe'] == 0) {
            $_SESSION['artikel_pro_reihe'] = CONF_ARTZEILEN_DEFAULT;
         }

         $limit *= $_SESSION['artikel_pro_reihe'];

         if ($this->anzahl) {
            $start = 1;                                  // Start mit Seite
            $ende = @floor($this->anzahl / $limit);  // max. Seiten

            // Wenn letzte seite nicht voll
            if ($ende * $limit < $this->anzahl) {
               $ende++;
            }

            $von = ($seite - 1) * $limit + 1;            // Art. von
            $bis = ($seite) * $limit;                    // Art. bis

            // Korrekturen bei letzer Seite
            if ($seite == $ende) {
               $bis = $this->anzahl;
            }
         }
      }

      else {
         // Liste Anzahl pro Seite
         $seite   = $this->params->artikel_seite;
         $art_min = CONF_ARTZEILEN_MIN * CONF_ARTIKELZEILE;
         $art_max = CONF_ARTZEILEN_MAX * CONF_ARTIKELZEILE;

         // Sicherstellen, dass art_anzahl immer gültige Werte hat
         if (!isset($_SESSION['art_anzahl'])) {
            $_SESSION['art_anzahl'] = CONF_ARTZEILEN_DEFAULT * CONF_ARTIKELZEILE;
         }

         if ($this->anzahl) {
            $limit = $_SESSION['art_anzahl'];
            $ende = ceil($this->anzahl / $limit);

            if ($seite > $ende) {
               $seite = $ende;
            }

            $start = 1;                                  // Start mit Seite
            $von = ($seite - 1) * $limit + 1;            // Art. von
            $bis = $von + $limit - 1;                    // Art. bis

            // Wenn letzte seite nicht voll
            if ($seite * $limit > $this->anzahl) {
               $bis = $this->anzahl;
//               $ende++;
            }

         }

         // Anzahl pro Zeile
         $html .= "<div class='pager_left col_ll_l'><span class='anz_seiten_text menu_unten txt_menu'> " . $this->text->get('categorie', 'counter', 'lang') . " </span>";

         for ($i = $art_min; $i <= $art_max; $i += $art_min) {
            if ($i == $limit) {
               $class = " active";
            }

            else {
               $class = "";
            }

            $html .= "<a href='".SHOP_URL."/anzahl/$i'><span class='anz_seiten menu_unten txt_menu fliesstext text_normal'$class'>$i</span></a>\n";
         }
      }
      $html .= "</div>".CR;


      // Artikel x von y
      $html .= "<div class='pager_right col_ll_r'>\n";

      // Artikel vorhanden
      if ($this->anzahl) {
         // Zurück / Anfang aktiv
         if ($seite > $start) {
            if (defined('CONF_RESPONSIVE')) {
               $html .= "<div class='fliesstext text_normal go_begin active' onclick='changeSeite(0);'></div>".CR;
               $html .= "<div class='fliesstext text_normal go_last active' onclick='changeSeite(1);'></div>".CR;
            }
            else {
               $html .= "<a href='".SHOP_URL."/seite/$start'><div class='menu_unten txt_menu go_begin active'></div></a>".CR;
               $html .= "<a href='".SHOP_URL."/seite/".($seite - 1)."'><div class='menu_unten txt_menu go_last active'></div></a>".CR;
            }
         }

         // Zurück / Anfang nicht aktiv
         else {
            if (defined('CONF_RESPONSIVE')) {
               $html .= "<div class='fliesstext text_normal go_begin'></div>".CR;
               $html .= "<div class='fliesstext text_normal go_last'></div>".CR;
            }
            else {
               $html .= "<div class='menu_unten txt_menu go_begin'></div>".CR;
               $html .= "<div class='menu_unten txt_menu go_last'></div>".CR;
            }
         }

         // Artikel von ... bis
         $html .= '<span class="vonbis fliesstext text_normal"><span id="cbp_von" class="fliesstext text_normal">'.$von.'</span> - <span id="cbp_bis"  class="fliesstext text_normal">'.$bis.'</span> '.$this->text->get('categorie', 'von', 'lang').' '.$this->anzahl . '</span>'.CR;

         // Weiter / Ende aktiv
         if ($seite  < $ende) {
            if (defined('CONF_RESPONSIVE')) {
               $html .= "<div class='fliesstext text_normal go_next active' onclick='changeSeite(2);'></div>".CR;
               $html .= "<div class='fliesstext text_normal go_end active' onclick='changeSeite(3);'></div>".CR;
            }
            else {
               $html .= "<a href='".SHOP_URL."/seite/".($seite + 1)."'><div class='menu_unten txt_menu go_next active'></div></a>".CR;
               $html .= "<a href='".SHOP_URL."/seite/$ende'><div class='menu_unten txt_menu go_end active'></div></a>".CR;
            }
         }

         // Weiter / Ende nicht aktiv
         else {
            $html .= "<div class='menu_unten txt_menu go_next'></div>".CR;
            $html .= "<div class='menu_unten txt_menu go_end'></div>".CR;
         }
      }

      // Keine Artikel vorhanden
      else {
         $html .= "<span class='fliesstext text_normal'>".$this->text->get('categorie', 'keine_artikel')."</span>".CR;
      }

      $html .= "</div>".CR;
      $html .= '<div class="clear"></div>'.CR;
      $html .= "</div>".CR;

      return $html;
   }

   function getMerkmale() {
      $lang = $this->params->selected_lang;
      $sql = "SELECT i.name_$lang AS name,  a.id, a.merkmal1 AS mm1_val, a.merkmal2 AS mm2_val, a.wert1 AS w1_val, wert2 AS w2_val,
                     m.merkmal_$lang as merkmal1, w.wert_$lang as wert1, w.wert_img AS wert_img1,
                     mm.merkmal_$lang as merkmal2, ww.wert_$lang as wert2, ww.wert_img AS wert_img2,
                     k.name_$lang AS kategoriename
                 FROM #__articles AS a
              LEFT JOIN #__articles_info AS i
                 ON i.id = a.parent_id
              LEFT JOIN #__merkmale as m
                 ON a.merkmal1 = m.id
              LEFT JOIN #__werte as w
                 ON a.wert1 = w.id
              LEFT JOIN #__merkmale as mm
                 ON a.merkmal2 = mm.id
              LEFT JOIN #__werte as ww
                 ON a.wert2 = ww.id
              LEFT JOIN #__article_to_cats AS ac
                 ON ac.parent_id = i.id
              LEFT JOIN #__categories AS k
                 ON ac.cat_id = k.id
              WHERE a.parent_id = ".$this->parent_id ."
                 AND a.online = 'y'
                 AND ac.sort = 0
              ORDER BY a.sort";

      $anzahl = $this->db_extern->query($sql);
      $data = [];
      $aktuell = -1;
      $name = '';

      for ($i = 0; $i < $anzahl; $i++) {
         $data[$i] = $this->db_extern->getObject();

         if ((int)$data[$i]->mm1_val == 0) {
            $data[$i]->merkmal1 = '';
         }

         if ((int)$data[$i]->w1_val == 0) {
            $data[$i]->wert1 = '';
         }

         if ((int)$data[$i]->mm2_val == 0) {
            $data[$i]->merkmal2 = '';
         }

         if ((int)$data[$i]->w2_val == 0) {
            $data[$i]->wert2 = '';
         }

         if ($data[$i]->id == $this->params->art_id) {
            $aktuell = $i;
         }
         else if ($aktuell == -1) {
            $aktuell = $i;
            $name = $data[$i]->name;
         }
      }

      // Merkmale1 / Werte1
      $this->merkmal1_txt = '<div class="merkmal1_txt fliesstext">' . $data[$aktuell]->merkmal1 . '</div>';
      $this->merkmal1_txt_raw = $data[$aktuell]->merkmal1;

      $arr_wert1 = [];

      for ($i = 0; $i < $anzahl; $i++) {
         $index = $data[$i]->w1_val;
         if (!isset($arr_wert1[$index]) || $i == $aktuell) {
            $arr_wert1[$index] = $data[$i];
         }
      }

      $this->wert1_arr = $arr_wert1;
      $html = '<select class="inp_border" id="merkmal1_sel" onchange="Royalart.wertChanged(this);">';

      foreach ($arr_wert1 as $wert) {
         $selected = '';
         if ($wert->w1_val == $this->wert1) {
            $selected = ' selected="selected"';
         }
         $html .= '<option value="'.$wert->id.'"'.$selected.' data-link="'.
               $this->params->getLink('artikel', $wert->id, $data[$aktuell]->name,
               $this->params->getWerte($data[$aktuell]->merkmal1, $wert->wert1, $wert->merkmal2, $wert->wert2), $data[$aktuell]->kategoriename).'">'.$wert->wert1.'</option>';
      }

      $html .= '</select>';
      $this->wert1_opt = $html;

      // Merkmale2 / Werte2
      $this->merkmal2_txt = '<div class="merkmal2_txt fliesstext">' . $data[$aktuell]->merkmal2 . '</div>';
      $this->merkmal2_txt_raw = $data[$aktuell]->merkmal2;

      $arr_wert2 = [];
      for ($i = 0; $i < $anzahl; $i++) {
         $index = $data[$i]->w2_val;
         if ($data[$i]->w1_val == $this->wert1 && (!isset($arr_wert2[$index]) || $i == $aktuell)) {
            $arr_wert2[$index] = $data[$i];
         }
      }
      $this->wert2_arr = $arr_wert2;

//      $html = '<select class="inp_border" id="merkmal2_sel" onchange="Royalart.wertChanged(this.value);">';
      $html = '<select class="inp_border" id="merkmal2_sel" onchange="Royalart.wertChanged(this);">';
      foreach ($arr_wert2 as $wert) {
         $selected = '';
         if ($wert->w2_val == $this->wert2) {
            $selected = ' selected="selected"';
         }
         $html .= '<option value="'.$wert->id.'"'.$selected.' data-link="'.
                   $this->params->getLink('artikel', $wert->id, $data[$aktuell]->name,
                   $this->params->getWerte($data[$aktuell]->merkmal1, $data[$aktuell]->wert1, $data[$aktuell]->merkmal2, $wert->wert2), $data[$aktuell]->kategoriename).'">'.$wert->wert2.'</option>';
      }
      $html .= '</select>';
      $this->wert2_opt = $html;
   }

   // Staffelpreise für Artikel-Details
   function getStaffelpreis() {
      if ($this->params->firma['staffelpreise'] !== 'y') {
         return '';
      }

      $wk_versand = Helper::checkSteuer($_SESSION['rechnung_land'], $_SESSION['wk_land']);
      $tax_active = false;

      // USt verwenden ?
      if ($this->params->firma['tax_active'] == 'y' && $this->params->firma['tax_show'] == 'y') {
         $tax_active = true;
      }

      $this->staffel_zahl = 0;
      $preis = (float)$this->preis_s;   //Netto/Angebot
      $html  = '<div class="staffel_titel">';
      $html .= '   <div class="staffel_menge_titel txt_tit text_bold">'.$this->text->get('staffel', 'menge').'</div>';


      // Mit Steuer (brutto)
      if ($tax_active) {
         if ($wk_versand) {
            $html .= '   <div class="staffel_brutto_titel txt_tit text_bold">'.$this->text->get('staffel', 'brutto').'</div>';
         }

         else {
            $html .= '   <div class="staffel_brutto_titel txt_tit text_bold">'.$this->text->get('staffel', 'preis').'</div>';
         }
      }

      // Ohne Steuer (netto)
      else {
         // Shop-Land = WK-Land && (USt nicht aktiv oder Preise ohne USt)
         if ($wk_versand && ($this->params->firma['tax_active'] == 'y' || $this->params->firma['tax_show'] == 'y')) {
            $html .= '   <div class="staffel_brutto_titel txt_tit text_bold">'. $this->text->get('staffel', 'netto').'</div>';
         }

         else {
            $html .= '   <div class="staffel_brutto_titel txt_tit text_bold">'. $this->text->get('staffel', 'preis').'</div>';
         }
      }

      $html .= '<div class="clear"></div>';
      $html .= '</div>';

      $werte = explode("#", $this->staffelpreis);

      foreach ($werte as $wert) {
         $staffel = explode(';', $wert);

         if ($staffel[0] == 'y') {
            $this->staffel_zahl++;
            $preis_netto  = $preis + (float)$staffel[2];
            $preis_brutto = ($preis + (float)$staffel[2]) * (1 + $this->steuer /100);

            $html .= '<div class="staffel_zeile">';
            $html .= '   <div class="staffel_menge">'. $staffel[1] . '</div>';

            // Brutto
            if ($tax_active && $wk_versand) {
               $html .= '   <div class="staffel_brutto">'. Helper::number_format($preis_brutto,2, ',', '.') . $this->params->waehrung .'</div>';
            }

            // Nettot
            else {
               $html .= '   <div class="staffel_brutto">'.Helper::number_format($preis_netto, 2, ',', '.').' '.$this->params->waehrung .'</div>';
            }

            $html .= '<div class="clear"></div>';
            $html .= '</div>';
         }
      }

      return $html;
   }

   // Bilder auf Existenz überprüfen und evtl. in richtiger Größe erstellen
   private function _getArticleboxImages($image, $parent_id) {
      $breite = '1px';
      $hoehe  = '1px';
      $rand = 'auto';

      $thumb_hoehe  = $this->params->firma['thumb_hoehe'];
      $thumb_breite = $this->params->firma['thumb_breite'];
      $thumb_over   = $this->params->firma['thumb_over_check'];
      $thumb_fix    = $this->params->firma['thumb_fix_width'];

      $thumb    = '';
      $thumburl = TEMPLATE_URL.'/images/system/nopic.png';
//!!!
//      $zoomurl  = PICTURE_URL.$picture;
      $zoomurl  = PICTURE_URL.$image;

      // Wenn Artikeltext angezeigt wird, Hoehe um 42px reduzieren
      if ($thumb_over == 'n') {
         $thumb_hoehe -= 42;
      }

      // Kein Bild vorhanden
//      if ($picture == '' || strpos($picture, 'nopic.png') !== false) {
      if ($image == '' || strpos($image, 'nopic.png') !== false) {
         $zoomurl = '';
      }

      else {
         // Versuch Bilder vom Shop herunterzuladen, falls erlaubt.
//         if (strpos($picture, 'http://') !== false || strpos($picture, 'https://') !== false) {
         if (strpos($image, 'http://') !== false || strpos($image, 'https://') !== false) {
            $image = $this->downloadImage($image, $parent_id);
//            $zoomurl  = PICTURE_URL.$picture;
            $zoomurl  = PICTURE_URL.$image;
         }

         // Bild konnte nicht heruntergeladen werden
//         if ($picture == 'nopic.png') {
         if ($image == 'nopic.png') {
            $zoomurl = '';
         }

//         else if (strpos($picture, 'http://') !== false || strpos($picture, 'https://') !== false) {
         else if (strpos($image, 'http://') !== false || strpos($image, 'https://') !== false) {
            // Bild auf anderem Server
            if (defined('CONF_')) {
//               $thumb = str_replace('.jpg', '_tn.jpg', $picture);
               $thumb = str_replace('.jpg', '_tn.jpg', $image);
               $thumburl = PICTURE_URL.$thumb;
               $breite = 'auto';
               $hoehe  = 'auto';
            }
            else if (file_exists(SHOP_PATH.'/'.CONF_PICT_PATH.$image)){
               $thumb = str_replace('.jpg', '_'.$thumb_breite.'x'.$thumb_hoehe.'.jpg', $picture);
               $thumburl = PICTURE_URL.$thumb;

               if (!file_exists(SHOP_PATH.'/'.CONF_PICT_PATH.$thumb)) {
                  $this->_makeThumb($image, $thumb, $breite, $hoehe, $thumb_fix);
               }

               list($breite, $hoehe) = getimagesize($thumb);
            }

            // Kein Bild vorhanden
            else {
               $zoomurl = '';
            }
         }
      }

//      return array($thumb, $zoom, $breite, $hoehe, $rand);
      return [$thumb, $zoomurl, $breite, $hoehe, $rand];
   }

   private function _downloadImage($name, $parent_id) {
      return $name;
   }

   private function _makeThumb($image, $thumb, $breite, $hoehe, $thumb_fix) {

   }

   // Preisberechnung via AJAX / Artikel-Details
   public function checkPrice() {
      $berechnung = Control::getBerechnungen();
      $back          = '';
      $html          = '';
      $m_arr         = [];
      $matrix_msg    = '';
      $masse_check   = 'n';
      $masse_menge   = $this->params->postFloat('masse_menge');
      $masse_msg     = '';

      $article_id    = $this->params->postInt('article_id');
      $artikel_menge = $this->params->postFloat('artikel_menge');
      $rechner_menge = $this->params->postFloat('rechner_menge');
      $configurator  = $this->params->postString('configurator');

      // Artikel-Mixer
      $mixer2        = (defined('CONF_MODULE_MIXER_ARTIKEL') ? $this->params->postString('mixer2') : '');

      $article       = null;

      // Daten Artikel holen und um Parameter erweitern
      if ($mixer2 != '') {
         // Für Nährwerte merken
         $_SESSION['mixer2'] = $mixer2;

         $mixer   = Control::getModuleMixerArtikel();
         $article = $mixer->getArticleById($article_id, '', '', 0, 0, false, $mixer2);
      }

      else {
         unset($_SESSION['mixer2']);
         $article = $this->getArticleById($article_id);
      }

      if ((int)$article->steuersatz == 0){
         $article->steuersatz = 1;
      }

      $article->netto         = (float)$article->netto;
      $article->angebot       = (float)$article->angebot;

      if (defined('CONF_MODULE_MATRIX') && $this->params->postCheckbox('is_matrix') == 'y') {
         $matrix = Control::getModuleMatrix();
         $m_arr  = $matrix->getPrice($article_id, $this->params->postFloat('matrix_breite'), $this->params->postFloat('matrix_hoehe'));

         if ($m_arr['status'] == 'ok') {
            $article->netto = $m_arr['preis'];
            $matrix_msg = 'ok';
         }

         else {
            $article->netto = $m_arr['preis'];
            $matrix_msg = $this->text->get('pmatrix', $m_arr['status']);
         }
      }

      if ($article->masse_check == 'y' && $masse_menge < (float)$article->masse_min) {
         $masse_check   = 'y';
         $masse_menge = (float)$article->masse_min;
         $masse_msg   = '<span class="form_err">'.$this->text->get('pmatrix', 'klein').'</span>';
      }

      $article->artikel_menge = $artikel_menge;
      $article->rechner_menge = $rechner_menge;

      // Bei Konfigurator Werte dekodieren
      if (defined('CONF_MODULE_MEGACONFIGURATOR') && $configurator != '' && $configurator != '[]') {
         if ($article->artikel_configurator != '' && $article->artikel_configurator != '[]') {
            $mod_conf = Control::getModuleConfigurator();
            $configurator = $mod_conf->decodeConfigurator($configurator);
         }
      }

      $article->configurator = $configurator;
      // Preise berechnen (als WK-Artikel)
      $preise   = $berechnung->berechneWkArtikel([$article]);
      $preis    = $preise[0]->preis;
      $price_ge = $article->ge_netto;  // Angebot bereits berücksichtigt

      // Grundpreis bei Staffelpreisen korrigieren
      if ($this->params->firma['staffelpreise'] == 'y') {
         $price_ge = Helper::staffelpreisGe($article->netto, $price_ge, $artikel_menge, $article->staffelung);
      }

      // Und Brutto / Netto berückschtigen
      $steuersatz = (float)$this->params->firma['tax'.$article->steuersatz];
      $price      = $berechnung->berechnePreis($price_ge, $steuersatz, false, true);

      if ($this->params->firma['tax_show'] == 'y') {
         $price_ge   = $price['brutto'];
      }

      else {
         $price_ge   = $price['netto'];
      }

      // Preise Konfigurator multiplizieren oder addieren

      if ($this->params->firma['check_w2'] == 'y' || $this->params->firma['check_w3'] == 'y' || $this->params->firma['check_w4'] == 'y') {
         if ($this->params->firma['check_w2'] == 'y') {
            $html .= '               <div class="waehrung ueberschrift text_max">'.CR;
            $html .= Helper::number_format($preis * $this->params->firma['kurs2'], 2, ',', '.').'&nbsp;'.Helper::waehrungText($this->params->firma['waehrung2'], 1);
            $html .= '               </div>'.CR;
         }
         if ($this->params->firma['check_w3'] == 'y') {
            $html .= '               <div class="waehrung ueberschrift text_max">'.CR;
            $html .= Helper::number_format($preis * $this->params->firma['kurs3'], 2, ',', '.').'&nbsp;'.Helper::waehrungText($this->params->firma['waehrung3'], 1);
            $html .= '               </div>'.CR;
         }
         if ($this->params->firma['check_w4'] == 'y') {
            $html .= '               <div class="waehrung ueberschrift text_max">'.CR;
            $html .= Helper::number_format($preis * $this->params->firma['kurs4'], 2, ',', '.').'&nbsp;'.Helper::waehrungText($this->params->firma['waehrung4'], 1);
            $html .= '               </div>'.CR;
         }
      }

      if ($matrix_msg == '') {
         echo json_encode(['status'        => 'ok',
                           'preis_x'       => $m_arr,
                           'preis'         => Helper::number_format($preis, 2, ',', '.'),
                           'waehrungen'    => $html,
                           'preis_ge'      => Helper::number_format($price_ge, 2, ',', '.'),
                           'rechner_menge' => number_format($rechner_menge, (int)$article->masse_komma, ',', ''),
                           'masse_check'   => $masse_check,
                           'masse_menge'   => number_format($masse_menge, (int)$article->masse_komma, ',', ''),
                           'masse_msg'     => $masse_msg
                          ]);
      }

      else {
         echo json_encode(['status'        => 'ok',
                           'preis_x'       => $m_arr,
                           'preis'         => Helper::number_format($preis, 2, ',', '.'),
                           'waehrungen'    => $html,
                           'preis_ge'      => Helper::number_format($price_ge, 2, ',', '.'),
                           'matrix_breite' => number_format($_SESSION['MATRIX_BREITE'], $m_arr['komma'], ',', ''),
                           'matrix_hoehe'  => number_format($_SESSION['MATRIX_HOEHE'], $m_arr['komma'], ',', ''),
                           'matrix_msg'    => $matrix_msg,
                           'matrix_xfail'  => $m_arr['x_fail'],
                           'matrix_yfail'  => $m_arr['y_fail'],
                           'matrix_config' => $m_arr['matrix_config'],
                           'rechner_menge' => number_format($rechner_menge, (int)$article->masse_komma, ',', ''),
                           'masse_check'   => $masse_check,
                           'masse_menge'   => number_format($masse_menge, (int)$article->masse_komma, ',', ''),
                           'masse_msg'     => $masse_msg
                          ]);
      }

      exit;
   }

   public function getNaehrwerte($parent_id) {
      $nw          = $this->db_extern->querySingleObject("SELECT * FROM #__articles_naehrwerte WHERE parent_id = $parent_id");
      $zu          = [];
      $zu['shop']  = $this->db_extern->queryAllObjects("SELECT * FROM #__articles_zutaten WHERE parent_id = $parent_id AND lang = '".$this->params->firma['default_lang']."'");
      $zu['kunde'] = $this->db_extern->queryAllObjects("SELECT * FROM #__articles_zutaten WHERE parent_id = $parent_id AND lang = '".$this->params->selected_lang."'");

      return ['naehrwerte' => $nw, 'zutaten' => $zu];
   }
}