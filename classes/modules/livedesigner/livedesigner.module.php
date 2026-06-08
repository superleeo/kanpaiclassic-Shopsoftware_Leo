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

require_once ADMIN_PATH.'/classes/designColors.class.php';

class KANPAICLASSIC_modulLivedesigner extends \KANPAICLASSIC\KANPAICLASSIC_design {
   private $design_colors    = null;
   private $livedesigner2    = null;
   private $livedesigner_ext = null;

   function __construct() {
      parent::__construct();

      $this->loadJson(false);
      $this->design_colors = new KANPAICLASSIC_designColors();
      $this->design_colors->loadCss();

      if (file_exists(SHOP_PATH.'/classes/modules/livedesigner2/livedesigner2.module.php')) {
         require_once SHOP_PATH.'/classes/modules/livedesigner2/livedesigner2.module.php';
         $this->livedesigner2 = new \KANPAICLASSIC\KANPAICLASSIC_modulLivedesigner2($this);

         if (file_exists(SHOP_PATH.'/classes/modules/livedesigner_ext/livedesigner_ext.module.php')) {
            require_once SHOP_PATH.'/classes/modules/livedesigner_ext/livedesigner_ext.module.php';
            $this->livedesigner_ext = new \KANPAICLASSIC\KANPAICLASSIC_modulLivedesignerExt($this);
         }
      }
   }

   public function getContent() {
      if ($this->params->func == '') {
         $this->loadStartseite();
      }

      else if ($this->params->func == 'popupElemente') {
         $html   = '';
         $module_arr = [];

         if (is_object($this->livedesigner2)) {
            $module_arr = $this->livedesigner2->getModule($module_arr);
         }

         if (is_object($this->livedesigner_ext)) {
            $module_arr = $this->livedesigner_ext->getModule($module_arr);
         }

         require_once SHOP_PATH.'/classes/modules/livedesigner/popup_elemente.tpl.php';

         exit(json_encode(['status' => 'ok', 'html' => $html]));
      }

      else if ($this->params->func == 'saveElemente') {
         $this->saveElemente();
      }

      else if ($this->params->func == 'popupBreite') {
         $html = '';
         $this->loadJson(false);
         $this->design_colors->loadCss();
         $json = $this->json;
         $css  = $this->design_colors->css;
         require_once SHOP_PATH.'/classes/modules/livedesigner/popup_breite.tpl.php';

         exit(json_encode(['status' => 'ok', 'html' => $html]));
      }

      else if ($this->params->func == 'saveBreite') {
         $this->saveBreite();
      }

      else if ($this->params->func == 'popupBackground') {
         $html = '';
         $this->loadJson(false);
         $this->design_colors->loadCss();
         $json = $this->json;
         $css  = $this->design_colors->css;

         require_once SHOP_PATH.'/classes/modules/livedesigner/popup_background.tpl.php';

         exit(json_encode(['status' => 'ok', 'html' => $html]));
      }

      else if ($this->params->func == 'saveBackground') {
         $this->saveBackground();
      }

      else if ($this->params->func == 'popupVideo') {
         $html = '';

         require_once SHOP_PATH.'/classes/modules/livedesigner/popup_video.tpl.php';

         exit(json_encode(['status' => 'ok', 'html' => $html]));
      }

      else if ($this->params->func == 'saveVideo') {
         $this->saveBackground();
      }

      else if ($this->params->func == 'popupMenuLeft') {
         $html = '';
         $this->loadJson();
         $this->design_colors->loadCss();
         $json = $this->json;
         $css  = $this->design_colors->css;
         require_once ADMIN_PATH.'/classes/seiten.class.php';
         $seiten = new KANPAICLASSIC_seiten();
         $seiten1 = $seiten->_getData(SEITEN1);
         require_once SHOP_PATH.'/classes/modules/livedesigner/popup_menuleft.tpl.php';

         exit(\json_encode(['status' => 'ok', 'html' => $html]));
      }

      else if ($this->params->func == 'saveMenuLeft') {
         $this->saveMenuLeft();
      }

      else if ($this->params->func == 'popupMenuRight') {
         $html = '';
         $this->loadJson();
         $this->design_colors->loadCss();
         $json = $this->json;
         $css  = $this->design_colors->css;
         require_once ADMIN_PATH.'/classes/seiten.class.php';
         $seiten = new KANPAICLASSIC_seiten();
         $seiten1 = $seiten->_getData(SEITEN1);
         require_once SHOP_PATH.'/classes/modules/livedesigner/popup_menuright.tpl.php';

         exit(\json_encode(['status' => 'ok', 'html' => $html]));
      }

      else if ($this->params->func == 'saveMenuRight') {
         $this->saveMenuRight();
      }

      else if ($this->params->func == 'popupKategorien') {
         $html = '';
         $this->loadJson();
         $this->design_colors->loadCss();
         $json = $this->json;
         $css  = $this->design_colors->css;
         require_once SHOP_PATH.'/classes/modules/livedesigner/popup_kategorien.tpl.php';

         exit(\json_encode(['status' => 'ok', 'html' => $html]));
      }

      else if ($this->params->func == 'saveKategorien') {
         $this->saveKategorien();
      }

      else if ($this->params->func == 'popupAbstand') {
         $html = '';
         require_once SHOP_PATH.'/classes/modules/livedesigner/popup_abstand.tpl.php';

         exit(\json_encode(['status' => 'ok', 'html' => $html]));
      }

      else if ($this->params->func == 'saveAbstand') {
         $this->saveAbstand();
      }

      else if ($this->params->func == 'popupAbstandoben') {
         $html = '';
         require_once SHOP_PATH.'/classes/modules/livedesigner/popup_abstandoben.tpl.php';

         exit(\json_encode(['status' => 'ok', 'html' => $html]));
      }

      else if ($this->params->func == 'saveAbstandoben') {
         $this->saveAbstandoben();
      }

      else if ($this->params->func == 'popupArtikelListe') {
         $html = '';
         $this->loadJson();
         $this->design_colors->loadCss();
         $json = $this->json;
         $css  = $this->design_colors->css;
         require_once \ADMIN_PATH.'/classes/designTemplate.class.php';
         $design = new \KANPAICLASSIC\KANPAICLASSIC_designTemplate();
         require_once SHOP_PATH.'/classes/modules/livedesigner/popup_artikel_liste.tpl.php';

         exit(\json_encode(['status' => 'ok', 'html' => $html]));
      }

      else if ($this->params->func == 'popupArtikelDetails') {
         $html = '';
         $this->loadJson();
         $this->design_colors->loadCss();
         $json = $this->json;
         $css  = $this->design_colors->css;
         require_once SHOP_PATH.'/classes/modules/livedesigner/popup_artikel_details.tpl.php';

         exit(\json_encode(['status' => 'ok', 'html' => $html]));
      }

      else if ($this->params->func == 'popupBannerunten') {
         $html = '';
         $this->loadJson();
         $this->design_colors->loadCss();
         $json = $this->json;
         $css  = $this->design_colors->css;
         require_once SHOP_PATH.'/classes/modules/livedesigner/popup_bannerunten.tpl.php';

         exit(json_encode(['status' => 'ok', 'html' => $html]));
      }

      else if ($this->params->func == 'saveBannerunten') {
         $this->saveBannerunten();
      }

      else if ($this->params->func == 'popupFooter') {
         $html = '';
         $this->loadJson();
         $this->design_colors->loadCss();
         $json = $this->json;
         $css  = $this->design_colors->css;

         require_once ADMIN_PATH.'/classes/designTemplate.class.php';
         $design     = new KANPAICLASSIC_designTemplate();
         $text_array = $design->_loadTexte();

         require_once SHOP_PATH.'/classes/modules/livedesigner/popup_footer.tpl.php';

         exit(\json_encode(['status' => 'ok', 'html' => $html]));
      }

      else if ($this->params->func == 'saveFooter') {
         $this->saveFooter();
      }

      else if ($this->params->func == 'popupFooterlinks') {
         require_once ADMIN_PATH.'/classes/seiten.class.php';

         $html    = '';
         $seiten  = new KANPAICLASSIC_seiten();
         $seiten2 = $seiten->_getData(SEITEN2);
         $seiten3 = $seiten->_getData(SEITEN3);
         $css     = $this->design_colors->css;
         require_once SHOP_PATH.'/classes/modules/livedesigner/popup_footer_links.tpl.php';

         exit(\json_encode(['status' => 'ok', 'html' => $html]));
      }

      else if ($this->params->func == 'saveFooterlinks') {
         $this->saveFooterlinks();
      }

      else if ($this->params->func == 'popupStarthtml') {
         $html = '';
         require_once ADMIN_PATH.'/classes/designTemplate.class.php';
         $design = new KANPAICLASSIC_designTemplate();
         $text_array = $design->_loadTexte();

         require_once SHOP_PATH.'/classes/modules/livedesigner/popup_starthtml.tpl.php';

         exit(\json_encode(['status' => 'ok', 'html' => $html]));
      }

      else if ($this->params->func == 'popupLogobanner') {
         $html = '';
         $this->loadJson();
         $this->design_colors->loadCss();
         $json = $this->json;
         $css  = $this->design_colors->css;
         require_once SHOP_PATH.'/classes/modules/livedesigner/popup_logobanner.tpl.php';

         exit(\json_encode(['status' => 'ok', 'html' => $html]));
      }

      else if ($this->params->func == 'popupSlideshow') {
         $html = '';
         $image_url            = TEMPLATE_URL.'/images/';
         $image_path           = TEMPLATE_PATH.'/images/';
         $sel_lang             = $this->params->selected_lang;
         $no_img               = ADMIN_URL.'/img/nopic.png';
         $this->params->getLinks($sel_lang);
         require_once SHOP_PATH.'/classes/modules/livedesigner/popup_slideshow.tpl.php';

         exit(\json_encode(['status' => 'ok', 'html' => $html]));
      }

      else if ($this->params->func == 'popupCollage') {
         $html = '';
         $image_url            = TEMPLATE_URL.'/images/';
         $image_path           = TEMPLATE_PATH.'/images/';
         $sel_lang             = $this->params->selected_lang;
         $no_img               = ADMIN_URL.'/img/nopic.png';
         $this->params->getLinks($sel_lang);
         $collage_on = 'y';
         require_once SHOP_PATH.'/classes/modules/livedesigner/popup_collage.tpl.php';

         exit(\json_encode(['status' => 'ok', 'html' => $html]));
      }

      else if ($this->params->func == 'popupNetzwerk') {
         $image_path = TEMPLATE_PATH.'/images/';
         $image_path = TEMPLATE_URL.'/images/';

         $html = '';
         $this->loadJson();
         $json = $this->json;
         require_once ADMIN_PATH.'/classes/designTemplate.class.php';
         $design = new KANPAICLASSIC_designTemplate();
         $social = $design->_loadSocialIcons();

         require_once SHOP_PATH.'/classes/modules/livedesigner/popup_netzwerk.tpl.php';

         exit(\json_encode(['status' => 'ok', 'html' => $html]));
      }

      else if ($this->params->func == 'popupPopup') {
         $html = '';
         require_once SHOP_PATH.'/classes/modules/livedesigner/popup_popup.tpl.php';

         exit(\json_encode(['status' => 'ok', 'html' => $html]));
      }

      else if ($this->params->func == 'popupArtikelslider') {
         $html = '';
                  require_once SHOP_PATH.'/classes/modules/livedesigner/popup_accordion.tpl.php';

         exit(\json_encode(['status' => 'ok', 'html' => $html]));
      }

      else if ($this->params->func == 'saveArtikelListe') {
         $this->saveArtikelListe();
      }

      else if ($this->params->func == 'saveArtikelDetails') {
         $this->saveArtikelDetails();
      }

      else if ($this->params->func == 'saveImageChange') {
         $this->saveImageChange();
      }

      else if ($this->params->func == 'saveLogobanner') {
         $this->saveLogobanner();
      }

      else if ($this->params->func == 'saveNetzwerk') {
         $this->saveNetzwerk();
      }

      else if ($this->params->func == 'saveSlideshow') {
         $this->saveSlideshow();
      }

      else if ($this->params->func == 'saveCollage') {
         $this->saveCollage();
      }

      else if ($this->params->func == 'saveStarthtml') {
         $this->saveStarthtml();
      }

      else if ($this->params->func == 'templateCss') {
         $this->templateCss();
      }

      else if ($this->params->func == 'editSeite') {
         require_once ADMIN_PATH.'/classes/seiten.class.php';
         $seiten = new KANPAICLASSIC_seiten();
         $seiten->popup();
      }

      else if ($this->params->func == 'moduleNew') {
         $this->moduleNew();
      }

      else if ($this->params->func == 'moduleSort') {
         $this->moduleSort();
      }

      else if ($this->params->func == 'moduleActive') {
         $this->moduleActive();
      }

      else if ($this->params->func == 'moduleDelete') {
         $this->moduleDelete();
      }

/* ********* Funktionen Livedesigner2 ********** */
      else if ($this->params->func == 'popupArtikel2') {
         $this->livedesigner2->popupArtikel();
      }

      else if ($this->params->func == 'addArticleSelected2') {
         $this->livedesigner2->articleAdd();
      }

      // Artikel-Array (aus Parameter sort_arr) speichern
      else if ($this->params->func == 'popupArtikelSort2') {
         $this->livedesigner2->articleSort();
      }

      else if ($this->params->func == 'popupArtikelDelete2') {
         $this->livedesigner2->artikelDelete();
      }

      else if ($this->params->func == 'popupArtikelSave2') {
         $this->livedesigner2->articleSave();
      }

      else if ($this->params->func == 'popupBild2') {
         $this->livedesigner2->popupBild();
      }

      else if ($this->params->func == 'popupBildSave2') {
         $this->livedesigner2->bildSave();
      }

      else if ($this->params->func == 'bildSeo2') {
         $this->livedesigner2->bildSeo();
      }

      else if ($this->params->func == 'bildSeoSave2') {
         $this->livedesigner2->bildSeoSave();
      }

      else if ($this->params->func == 'bildColors2') {
         $this->livedesigner2->bildColors();
      }

      else if ($this->params->func == 'bildColorsSave2') {
         $this->livedesigner2->bildColorsSave();
      }

      else if ($this->params->func == 'bildUpload2') {
         $this->livedesigner2->bildUpload();
      }

      else if ($this->params->func == 'bildRefresh2') {
         $this->livedesigner2->bildRefresh();
      }

      else if ($this->params->func == 'bildSort2') {
         $this->livedesigner2->bildSort();
      }

      else if ($this->params->func == 'bildDelete2') {
         $this->livedesigner2->bildDelete();
      }

      else if ($this->params->func == 'popupText2') {
         $this->livedesigner2->popupText();
      }

      else if ($this->params->func == 'popupTextSave2') {
         $this->livedesigner2->textSave();
      }

      else if ($this->params->func == 'saveModuleBackground') {
         $this->design_colors->css['bg_innen']['val']     = str_replace('#', '', $this->params->postString('background'));
         $this->design_colors->css['bg_innen']['opacity'] = number_format($this->params->postFloat('background_opc'), 2);
         $this->design_colors->saveCss(true);
         exit(Json_encode(['status' => 'ok']));
     }

      else if ($this->params->func == 'saveModuleBackground2') {
         $this->livedesigner2->saveModuleBackground();
      }

      else if ($this->params->func == 'saveModuleBackgroundExt') {
         $this->livedesigner_ext->saveModuleBackground();
      }

/* ********* Funktionen Livedesigner_Ext Accordion ********** */
      else if ($this->params->func == 'popupAccordion') {
         $this->livedesigner_ext->popupAccordion();
      }

      else if ($this->params->func == 'popupAccordionSave') {
         $this->livedesigner_ext->accordionSave();
      }

      else if ($this->params->func == 'accordionUpload') {
         $this->livedesigner_ext->accordionUpload();
      }

      else if ($this->params->func == 'accordionRefresh') {
         $this->livedesigner_ext->accordionRefresh();
      }

      else if ($this->params->func == 'accordionSort') {
         $this->livedesigner_ext->accordionSort();
      }

      else if ($this->params->func == 'accordionDelete') {
         $this->livedesigner_ext->accordionDelete();
      }

      else if ($this->params->func == 'accordionSeo') {
         $this->livedesigner_ext->accordionSeo();
      }

      else if ($this->params->func == 'accordionSeoSave') {
         $this->livedesigner_ext->accordionSeoSave();
      }


/* ********* Funktionen Livedesigner_Ext Karussell ********** */
      else if ($this->params->func == 'popupKarussell') {
         $this->livedesigner_ext->popupKarussell();
      }

      else if ($this->params->func == 'popupKarussellSave') {
         $this->livedesigner_ext->karussellSave();
      }

      else if ($this->params->func == 'karussellUpload') {
         $this->livedesigner_ext->karussellUpload();
      }

      else if ($this->params->func == 'karussellRefresh') {
         $this->livedesigner_ext->karussellRefresh();
      }

      else if ($this->params->func == 'karussellSort') {
         $this->livedesigner_ext->karussellSort();
      }

      else if ($this->params->func == 'karussellDelete') {
         $this->livedesigner_ext->karussellDelete();
      }

      else if ($this->params->func == 'karussellSeo') {
         $this->livedesigner_ext->karussellSeo();
      }

      else if ($this->params->func == 'karussellSeoSave') {
         $this->livedesigner_ext->karussellSeoSave();
      }

      else if ($this->params->func == 'karussellColors') {
         $this->livedesigner_ext->karussellColors();
      }

      else if ($this->params->func == 'karussellColorsSave') {
         $this->livedesigner_ext->karussellColorsSave();
      }

/* ********* Funktionen Livedesigner_Ext Artikel-Slider ********** */
      else if ($this->params->func == 'popupSlider') {
         $this->livedesigner_ext->popupSlider();
      }

      else if ($this->params->func == 'popupSliderSave') {
         $this->livedesigner_ext->sliderSave();
      }

      else if ($this->params->func == 'sliderUpload') {
         $this->livedesigner_ext->sliderUpload();
      }

      else if ($this->params->func == 'sliderRefresh') {
         $this->livedesigner_ext->sliderRefresh();
      }

      else if ($this->params->func == 'sliderSort') {
         $this->livedesigner_ext->sliderSort();
      }

      else if ($this->params->func == 'sliderDelete') {
         $this->livedesigner_ext->sliderDelete();
      }

      else if ($this->params->func == 'sliderSeo') {
         $this->livedesigner_ext->sliderSeo();
      }

      else if ($this->params->func == 'sliderSeoSave') {
         $this->livedesigner_ext->sliderSeoSave();
      }

      else if ($this->params->func == 'sliderColors') {
         $this->livedesigner_ext->sliderColors();
      }

      else if ($this->params->func == 'sliderColorsSave') {
         $this->livedesigner_ext->sliderColorsSave();
      }

      else {
         exit($this->params->func.' nicht vorhanden');
      }
   }

   // Starseite FE in BE Design/Livedesigner einbinden
   private function loadStartseite() {
      header('Expires: Sun, 01 Jan 2014 00:00:00 GMT');
      header('Cache-Control: no-store, no-cache, must-revalidate');
      header('Cache-Control: post-check=0, pre-check=0', FALSE);
      header('Pragma: no-cache');

      $live_telefon          = '<span class="livedesigner live_telefon"          title="Telefon"            onclick="Livedesigner.popupTelefon();"></span>';

      $live_width            = '<span class="livedesigner live_width"            title="Breite / Flächen"   onclick="Livedesigner.popupBreite();"></span>';
      $live_startseite       = '<span class="livedesigner live_startseite"       title="Einfügen"           onclick="Livedesigner.popupElemente();"></span>';

      $live_background       = '<span class="livedesigner live_background"       title="Hintergrund"        onclick="Livedesigner.popupBackground();"></span>';
      $live_video            = '<span class="livedesigner live_video"            title="Startbild / -video" onclick="Livedesigner.popupVideo();"></span>';
      $live_menu_left        = '<span class="livedesigner live_menu_left"        title="Menüzeile links"    onclick="Livedesigner.popupMenuLeft();"></span>';
      $live_menu_right       = '<span class="livedesigner live_menu_right"       title="Menüzeile rechts"   onclick="Livedesigner.popupMenuRight();"></span>';
      $live_logobanner       = '<span class="livedesigner live_logobanner"       title="Logobanner"         onclick="Livedesigner.popupLogobanner();"></span>';
      $live_logobanner_no    = '<span class="livedesigner live_logobanner_no"    title="Logobanner"         onclick="Livedesigner.popupLogobanner();"></span>';
      // in articles.class.php $live_artikelliste     = '<span class="livedesigner live_kategorien"       title="Artikellist"        onclick="Livedesigner.popupArtikelListe();"></span>';
      $live_kategorien       = '<span class="livedesigner live_kategorien"       title="Kategorien"         onclick="Livedesigner.popupKategorien();"></span>';
      $live_abstand          = '<span class="livedesigner live_abstand"          title="Abstand"            onclick="Livedesigner.popupAbstand();"></span>';
      $live_abstandoben      = '<span class="livedesigner live_abstandoben"      title="Abstand"            onclick="Livedesigner.popupAbstandoben();"></span>';

      $live_einfuegen_center = '';
      $live_artikelliste     = '<span class="livedesigner live_artikelliste"     title="Artikelliste"       onclick="Livedesigner.popupArtikelListe();"></span>';
      $live_artikeldetails   = '<span class="livedesigner live_artikeldetails"   title="Artikel-Detailbild" onclick="Livedesigner.popupArtikelDetails();"></span>';

      $live_bannerunten      = '<span class="livedesigner live_bannerunten"      title="Banner unten"       onclick="Livedesigner.popupBannerunten();"></span>';

      if ($this->params->firma['social_status'] === 'nein') {
         $live_netzwerk         = '<span class="livedesigner live_netzwerk_no"         title="Netzwerk-Links"     onclick="Livedesigner.popupNetzwerk();"></span>';
      }

      else {
         $live_netzwerk         = '<span class="livedesigner live_netzwerk"      title="Netzwerk-Links"     onclick="Livedesigner.popupNetzwerk();"></span>';
      }

      $live_footer           = '<span class="livedesigner live_footer"           title="Footer"             onclick="Livedesigner.popupFooter();"></span>';
      $live_footerlinks      = '<span class="livedesigner live_footerlinks"      title="Footer Seiten"      onclick="Livedesigner.popupFooterlinks();"></span>';
      $live_icons            = '<span class="livedesigner live_icons"            title="Versand & Zahlungsart" onclick="Design.footerPopup(2);;"></span>';

      $live_slideshow        = '<span class="livedesigner live_slideshow"        title="Slideshow"          onclick="Livedesigner.popupSlideshow();"></span>';
      $live_starthtml        = '<span class="livedesigner live_starthtml"        title="Startseitentext"    onclick="Livedesigner.popupStarthtml();"></span>';
      $live_collage          = '<span class="livedesigner live_collage"          title="Kollage"            onclick="Livedesigner.popupCollage();;"></span>';

      $this->params->firma['use_cache'] = 'n';
      $_SESSION['artikel_pro_reihe'] = 6;
      $_SESSION['artikel_reihen']    = ($this->params->firma['startseite_artikel'] != 'artikel' ? $this->params->firma['startseite_reihen'] : CONF_ARTIKELZEILE * $_SESSION['artikel_pro_reihe']);
      //$_SESSION['artikel_reihen']    = 3;
      $_SESSION['artikel_seite']     = 1;
      $_SESSION['alter_ok']          = true;

      $livedesigner                  = true;
      $GLOABALS['livedesigner']      = true;
      $text                          = \KANPAICLASSIC\Control::getText();
      $load1                         = \KANPAICLASSIC\Control::getArtikel();
      $load2                         = \KANPAICLASSIC\Control::getKategorie();
      $kat                           = \KANPAICLASSIC\Control::getCategories();
      $articles                      = \KANPAICLASSIC\Control::getArticles();
      $kategorie                     = '';

      $params                        = &$this->params;
      $lang                          = $params->selected_lang;
      $params->getLinks($lang);
      $params->cat_mode              = 2;
      $params->artikel_max           = 0;
      $keywords                      = '';
      $description                   = '';
      $site_lang                     = $params->selected_lang;
      $titel_tag                     = '';
      $footer                        = \KANPAICLASSIC\Helper::getFooter(true);
      $script                        = '';
      $image_path                    = TEMPLATE_PATH.'/images/';
      $module1                       = '';
      $mixer                         = false;

      $countertext                   = $articles->getCounter();
      $isExtended                    = false;
      $outtext                       = '';

      if (defined('CONF_MODULE_EXTENDED') && !$this->livedesigner_ext) {
         $isExtended = \KANPAICLASSIC\Control::getShopExtended();
      }

      $params->head                  = '';
      $params->parent_id             = 0;
      $params->art_id                = '';

      $artikel_fe                    = ['', ''];

//         $module                        = $this->frontend(true);
//      $module1                        = $this->frontend(true);
//         $module2                        = $this->frontend(true);

      if (!$this->livedesigner_ext) {
         if (defined('CONF_MODULE_EXTENDED')) {
            $isExtended = \KANPAICLASSIC\Control::getShopExtended();
         }
      }

      // Class User wird aufgerufen (verhindert)
      if (!isset($this->params->firma['artikelliste_on']) || $this->params->firma['artikelliste_on'] == 'y') {
         $articles->loadArticles();

         $articles->render($params->art_id, $artikel_fe);
      }

      // Nach LoadArticles, sonst wird Shop-Session gestartet
      $params->task                  = '';   // Um Shop / Startseite zu laden
      $artikel_main                  = $artikel_fe[0];
      $countertext                   = $params->hide_articles ? '' : $articles->getCounter();

      $logolink                      = null;

      // Menü oben
      $wkAnzahl                      = 0;
      $device                        = 'desktop';
      $device_detect                 = '';
      $langs                         = $params->langs;
      // vertikale Kategorienen in /admin/index.php
      $cat_left            = ($params->firma['kategorien_links'] == 'y' || $params->firma['kategorien_links'] == 'l' ? true : false);
      // Header bildschirmbreit
      $is_flaeche_header   = ($params->firma['flaeche'] == 'n' ? false : true);
      // Shopmitte bildschirmbreit
      $is_flaeche_mitte    = ($params->firma['flaeche_hg'] == 'n'|| $cat_left ? false : true);
      // Artikelliste bildschirmbreit
      $is_flaeche_liste    = ($params->firma['bildschirmbreit'] == 'y' && ($params->task == 'kategorie' || $params->task == '' || !$cat_left) ? true :false);
      // Footer bildschirmbreit
      $is_flaeche_footer   = ($params->firma['flaeche_footer'] == 'n' ? false : true);

      // Menü oben in Variable einlesen
      ob_start();
      include TEMPLATE_PATH . '/menu_oben.tpl.php';
      $menu_oben = ob_get_contents();
      ob_clean();

      // Menü unten in Variable einlesen
      ob_start();
      include TEMPLATE_PATH . '/menu_unten.tpl.php';
      $menu_unten = ob_get_contents();
      ob_clean();

      // Inhalt Starseitet in Variable einlesen
      ob_start();
      include TEMPLATE_PATH . '/template.tpl.php';
      $startseite = ob_get_contents();
      ob_clean();

      $params->task = 'designLivedesigner';  // Wieder auf Ursprungswert für Admin
      require_once SHOP_PATH.'/classes/modules/livedesigner/livedesigner.tpl.php';

      exit;
   }

   private function saveArtikelListe() {
      $this->loadJson();

      $this->json['artikelliste_on']    = $this->params->postCheckbox('artikelliste_on');
      $this->json['startseite_artikel'] = $this->params->postString('startseite_artikel');
      $this->json['startseite_reihen']  = $this->params->postInt('startseite_reihen');
      $this->json['zoom_artikel']       = $this->params->postCheckbox('zoom_artikel');
      $this->json['thumb_over_check']   = $this->params->postCheckbox('thumb_over_check');
      $this->json['merkmal_over_check'] = $this->params->postCheckbox('merkmal_over_check');
      $this->json['cbp_display']        = $this->params->postString('cbp_display');
      $this->json['cbp_animation']      = $this->params->postString('cbp_animation');
      $this->json['wk_popup_check']     = $this->params->postCheckbox('wk_popup_check');
      $this->json['cpf_size']           = $this->params->postString('cpf_size');
//      $this->json['image_ratio']        = round(1 / $this->params->postFloat('image_ratio'), 4);

      file_put_contents(TEMPLATE_PATH.'/css/template.json', json_encode($this->json, JSON_PRETTY_PRINT));
      $this->jsonBackup();

      exit(json_encode(['status' => 'ok']));
   }

   private function saveArtikelDetails() {
      $this->json['detailbild'] = $this->params->postInt('detailbild');

      file_put_contents(TEMPLATE_PATH.'/css/template.json', json_encode($this->json, JSON_PRETTY_PRINT));
      $this->jsonBackup();

      exit(json_encode(['status' => 'ok']));
   }

   private function saveBackground() {
      $this->json['bg_repeat'] = $this->params->postString('bg_repeat');
      $this->json['bg_fixed']  = $this->params->postCheckbox('bg_fixed');
      $this->json['flaeche_hg']  = $this->params->postCheckbox('flaeche_hg');

      file_put_contents(TEMPLATE_PATH.'/css/template.json', json_encode($this->json, JSON_PRETTY_PRINT));
      $this->jsonBackup();

      $this->design_colors->css['bg_aussen']['val']     = str_replace('#', '', $this->params->postString('bg_aussen'));
      $this->design_colors->css['bg_aussen']['opacity'] = number_format($this->params->postFloat('bg_aussen_opacity'), 2);

      $this->design_colors->saveCss(true);

      exit(json_encode(['status' => 'ok']));
   }

   private function saveBreite() {
      $max_width       = $this->params->postInt('max_width');
      $flaeche         = $this->params->postCheckbox('flaeche_header');
      $flaeche_hg      = $this->params->postCheckbox('flaeche_mitte');
      $bildschirmbreit = $this->params->postCheckbox('flaeche_liste');
      $flaeche_footer  = $this->params->postCheckbox('flaeche_footer');
      $reload          = 'n';

      // Bei Änderung neu laden / Templatewechsel
      if ($this->json['max_width'] !== $max_width ||
          $this->json['flaeche'] !== $flaeche ||
          $this->json['flaeche_hg'] !== $flaeche_hg ||
          $this->json['bildschirmbreit'] !== $bildschirmbreit ||
          $this->json['flaeche_footer'] !== $flaeche_footer)
      {
         $reload = 'y';
      }

      $this->json['max_width']       = $max_width;
      $this->json['flaeche']         = $flaeche;
      $this->json['flaeche_hg']      = $flaeche_hg;
      $this->json['bildschirmbreit'] = $bildschirmbreit;
      $this->json['flaeche_footer']  = $flaeche_footer;

      file_put_contents(TEMPLATE_PATH.'/css/template.json', json_encode($this->json, JSON_PRETTY_PRINT));
      $this->jsonBackup();
/*
      $this->design_colors->css['bg_header']['val']       = str_replace('#', '', $this->params->postString('ld_bg_header'));
      $this->design_colors->css['bg_header']['opacity']   = number_format($this->params->postFloat('ld_bg_header_opacity'), 2);
      $this->design_colors->css['bg_innen']['val']        = str_replace('#', '', $this->params->postString('ld_bg_innen'));
      $this->design_colors->css['bg_innen']['opacity']    = number_format($this->params->postFloat('ld_bg_innen_opacity'), 2);
      $this->design_colors->css['bg_footer']['val']       = str_replace('#', '', $this->params->postString('ld_bg_footer'));
      $this->design_colors->css['bg_footer']['opacity']   = number_format($this->params->postFloat('ld_bg_footer_opacity'), 2);

      $this->design_colors->saveCss(true);
*/
      exit(json_encode(['status' => 'ok', 'html' => '', 'reload' => $reload]));
   }

   private function saveElemente() {
      // Telefon-Nr Shopinhaber
      $telefon_on = $this->params->postCheckbox('telefon_on');
      Helper::setData('call_check', $telefon_on);

      $this->json['slideshow_on']    = $this->params->postCheckbox('slideshow_on');
      $this->json['starthtml_on']    = $this->params->postCheckbox('starthtml_on');
      $this->json['collage_on']      = $this->params->postCheckbox('collage_on');
      $this->json['artikelliste_on'] = $this->params->postCheckbox('artikelliste_on');
      $this->json['bannerunten_on']  = $this->params->postCheckbox('bannerunten_on');

      file_put_contents(TEMPLATE_PATH.'/css/template.json', json_encode($this->json, JSON_PRETTY_PRINT));
      $this->jsonBackup();

      exit(json_encode(['status' => 'ok']));
   }

   private function saveKategorien() {
      $reload           = 'n';

      $kategorien_links = $this->params->postString('kategorien_links');
      $shop_check       = $this->params->postCheckbox('shop_check');
      $schatten         = $this->params->postCheckbox('schatten');
      $linien_kat       = $this->params->postCheckbox('linien_kat');

      if ($kategorien_links !== $this->json['kategorien_links'] ||
         $shop_check !== $this->json['shop_check'] ||
         $schatten !== $this->json['schatten'] ||
         $linien_kat !== $this->json['linien_kat'])
      {
         $reload = 'y';
      }

      $this->json['kategorien_links'] = $kategorien_links;
      $this->json['shop_check']       = $shop_check;
      $this->json['schatten']         = $schatten;
      $this->json['linien_kat']       = $linien_kat;

      file_put_contents(TEMPLATE_PATH.'/css/template.json', json_encode($this->json, JSON_PRETTY_PRINT));
      $this->jsonBackup();

      $this->design_colors->css['horiz_kat']['val']        = str_replace('#', '', $this->params->postString('horiz_kat'));
      $this->design_colors->css['horiz_kat']['opacity']    = number_format($this->params->postFloat('horiz_kat_opacity'), 2);
      $this->design_colors->css['horiz_aktiv']['val']      = str_replace('#', '', $this->params->postString('horiz_aktiv'));
      $this->design_colors->css['horiz_aktiv']['opacity']  = number_format($this->params->postFloat('horiz_aktiv_opacity'), 2);
      $this->design_colors->css['vertikal_kat']['val']     = str_replace('#', '', $this->params->postString('vertikal_kat'));
      $this->design_colors->css['vertikal_kat']['opacity'] = number_format($this->params->postFloat('vertikal_kat_opacity'), 2);
      $this->design_colors->css['unter_kat']['val']        = str_replace('#', '', $this->params->postString('unter_kat'));
      $this->design_colors->css['unter_kat']['opacity']    = number_format($this->params->postFloat('unter_kat_opacity'), 2);
      $this->design_colors->css['over_kat']['val']         = str_replace('#', '', $this->params->postString('over_kat'));
      $this->design_colors->css['over_kat']['opacity']     = number_format($this->params->postFloat('over_kat_opacity'), 2);

      $this->design_colors->css['horiz_kat_c']['val']      = str_replace('#', '', $this->params->postString('horiz_kat_c'));
      $this->design_colors->css['horiz_kat_c_ovr']['val']  = str_replace('#', '', $this->params->postString('horiz_kat_c_ovr'));
      $this->design_colors->css['haupt_kat_c']['val']      = str_replace('#', '', $this->params->postString('haupt_kat_c'));
      $this->design_colors->css['haupt_kat_c_ovr']['val']  = str_replace('#', '', $this->params->postString('haupt_kat_c_ovr'));

      $this->design_colors->saveCss(true);

      exit(json_encode(['status' => 'ok', 'reload' => $reload]));
   }

   private function saveAbstand() {
      $html   = '';
      $reload = 'n';

      $this->json['abstand'] = $this->params->postInt('abstand');

      file_put_contents(TEMPLATE_PATH.'/css/template.json', json_encode($this->json, JSON_PRETTY_PRINT));
      //$this->jsonBackup();

      exit(json_encode(['status' => 'ok', 'html' => $html, 'reload' => $reload]));
   }

   private function saveAbstandoben() {
      $html   = '';
      $reload = 'n';

      $this->json['abstand_oben'] = $this->params->postInt('abstand_oben');

      file_put_contents(TEMPLATE_PATH.'/css/template.json', json_encode($this->json, JSON_PRETTY_PRINT));
      //$this->jsonBackup();

      exit(json_encode(['status' => 'ok', 'html' => $html, 'reload' => $reload]));
   }

   private function saveLogobanner() {
      $lang = $this->params->selected_lang;

      $this->design_colors->css['bg_header']['val']       = str_replace('#', '', $this->params->postString('bg_header'));
      $this->design_colors->css['bg_header']['opacity']   = number_format($this->params->postFloat('bg_header_opacity'), 2);

      $this->design_colors->saveCss(true);

      exit(json_encode(['status' => 'ok']));

   }

   private function saveNetzwerk() {
      $html          = '';
      $reload        = 'n';

      $telefon_status = Helper::getData('call_check');
      $telefon_on    = $this->params->postCheckbox('telefon_on');
      Helper::setData('call_check', $telefon_on);

      if ($telefon_status !== $telefon_on) {
         $reload = 'y';
      }

      $social_status = $this->params->postString('social_status');

      if ($this->db->querySingleValue("SELECT `social_status` FROM #__firma WHERE id = 1") !== $social_status) {
         $this->db->query("UPDATE #__firma SET `social_status` = '".$this->params->postString('social_status')."' WHERE id = 1");
         $reload = 'y';
      }

      $html = Helper::getSocial();

      exit(json_encode(['status' => 'ok', 'html' => $html, 'reload' => $reload]));
   }

   private function saveSlideshow() {
      $html         = '';
      $slideshow_on = $this->params->postCheckbox('slideshow_on');

      $this->json['slideshow_r_check'] = $this->params->postCheckbox('slideshow_r_check');
      $this->json['rechts_slide']      = $this->params->postCheckbox('rechts_slide');
      $this->json['fullscreen_slide']  = $this->params->postCheckbox('fullscreen_slide');
      $this->json['slideshow_on']      = $slideshow_on;

      file_put_contents(TEMPLATE_PATH.'/css/template.json', json_encode($this->json, JSON_PRETTY_PRINT));
      $this->jsonBackup();

      exit(json_encode(['status' => 'ok', 'html' => $html]));
   }

   private function saveFooter() {
      $this->design_colors->css['menu_unten']['val']    = str_replace('#', '', $this->params->postString('menu_unten'));
      $this->design_colors->css['over_unten']['val']    = str_replace('#', '', $this->params->postString('over_unten'));
      $this->design_colors->css['bg_footer']['val']     = str_replace('#', '', $this->params->postString('bg_footer'));
      $this->design_colors->css['bg_footer']['opacity'] = number_format($this->params->postFloat('bg_footer_opacity'), 2);
      $this->design_colors->saveCss(true);

      $lang   = $this->params->selected_lang;
      $footer = $this->params->postString('footer_text','',  'sql');
      $test   = (int)$this->db->querySingleValue("SELECT `id` FROM #__seiten WHERE lang = '$lang' and `art` = 'footer'");
      $reload = 'y';

      // Eintrag vorhanden
      if ($test > 0) {
         $this->db->query("UPDATE #__seiten SET `text` = '".$this->db->escape($footer)."' WHERE id = $test");
      }

      // Neuer Eintrag
      else {
         $this->db->query("INSERT INTO #__seiten SET `art` = 'footer', `lang` = '$lang', `text` = '".$this->db->escape($footer)."'");
      }

      if ($this->json['footer_mode'] != $this->params->postString('footer_mode')) {
         $this->json['footer_mode']  = $this->params->postString('footer_mode');
         file_put_contents(TEMPLATE_PATH.'/css/template.json', json_encode($this->json, JSON_PRETTY_PRINT));
         $this->jsonBackup();
         $reload = 'y';
      }

      require_once ADMIN_PATH.'/classes/designTemplate.class.php';
      $design     = new KANPAICLASSIC_designTemplate();
      $text_array = $design->_loadTexte();

      exit(json_encode(['status' => 'ok', 'html' => $text_array[1], 'reload' => $reload]));
   }

   private function saveFooterlinks() {
      $html   = '';
      $reload = 'y';

      $this->design_colors->css['menu_unten']['val']    = str_replace('#', '', $this->params->postString('menu_unten'));
      $this->design_colors->css['over_unten']['val']    = str_replace('#', '', $this->params->postString('over_unten'));
      $this->design_colors->css['bg_footer']['val']     = str_replace('#', '', $this->params->postString('bg_footer'));
      $this->design_colors->css['bg_footer']['opacity'] = number_format($this->params->postFloat('bg_footer_opacity'), 2);
      $this->design_colors->saveCss(true);

      if ($this->json['footer_mode'] != $this->params->postString('footer_mode')) {
         $this->json['footer_mode']  = $this->params->postString('footer_mode');
         file_put_contents(TEMPLATE_PATH.'/css/template.json', json_encode($this->json, JSON_PRETTY_PRINT));
         $this->jsonBackup();
         $reload = 'y';
      }

      exit(json_encode(['status' => 'ok', 'html' => $html, 'reload' => $reload]));
   }

   private function saveBannerunten() {
      $html           = '';
      $bannerunten_on = $this->params->postCheckbox('bannerunten_on');

      $this->json['bannerunten_on'] = $bannerunten_on;
      file_put_contents(TEMPLATE_PATH.'/css/template.json', json_encode($this->json, JSON_PRETTY_PRINT));

      exit(json_encode(['status' => 'ok', 'html' => $html]));

   }

   private function saveMenuLeft() {
      $this->design_colors->css['menuleiste']['val']     = str_replace('#', '', $this->params->postString('menuleiste'));
      $this->design_colors->css['menuleiste']['opacity'] = number_format($this->params->postFloat('menuleiste_opacity'), 2);
      $this->design_colors->css['menu_oben']['val']      = str_replace('#', '', $this->params->postString('menu_oben'));
      $this->design_colors->css['over_oben']['val']      = str_replace('#', '', $this->params->postString('over_oben'));
      $this->design_colors->saveCss(true);

      $this->json['shop_check'] = $this->params->postCheckbox('shop_check');
      file_put_contents(TEMPLATE_PATH.'/css/template.json', json_encode($this->json, JSON_PRETTY_PRINT));
      $this->jsonBackup();

      exit(json_encode(['status' => 'ok']));
   }

   private function saveMenuRight() {
      $this->json['icon_farbe']     = $this->params->postString('icon_farbe');
      $this->json['anmelden_mode']  = $this->params->postInt('anmelden_mode');
      $this->json['merkliste_mode'] = $this->params->postInt('merkliste_mode');
      $this->json['warenkorb_mode'] = $this->params->postInt('warenkorb_mode');
      $this->json['suchfeld_mode']  = $this->params->postInt('suchfeld_mode');
      $this->json['flaggen_mode']   = $this->params->postInt('flaggen_mode');

      file_put_contents(TEMPLATE_PATH.'/css/template.json', json_encode($this->json, JSON_PRETTY_PRINT));
      $this->jsonBackup();

      $this->design_colors->css['menuleiste']['val']     = str_replace('#', '', $this->params->postString('menuleiste'));
      $this->design_colors->css['menuleiste']['opacity'] = number_format($this->params->postFloat('menuleiste_opacity'), 2);
      $this->design_colors->css['menu_oben']['val']      = str_replace('#', '', $this->params->postString('menu_oben'));
      $this->design_colors->css['over_oben']['val']      = str_replace('#', '', $this->params->postString('over_oben'));

      $this->design_colors->saveCss(true);

      exit(json_encode(['status' => 'ok']));
   }

   private function saveStarthtml() {
      $lang      = $this->params->selected_lang;
      $starthtml = $this->params->postString('starthtml','',  'sql');
      $test      = (int)$this->db->querySingleValue("SELECT `id` FROM #__seiten WHERE lang = '$lang' and `art` = 'starthtml'");
      $reload    = 'n';
      $html      = '';

      // Eintrag vorhanden
      if ($test > 0) {
         $this->db->query("UPDATE #__seiten SET `text` = '".$this->db->escape($starthtml)."' WHERE id = $test");
      }

      // Neuer Eintrag
      else {
         $this->db->query("INSERT INTO #__seiten SET `art` = 'starthtml', `lang` = '$lang', `text` = '".$this->db->escape($starthtml)."'");
      }

      $starthtml_on = $this->params->postCheckbox('starthtml_on');

      if ($this->json['starthtml_on'] !== $starthtml_on) {
         $reload = 'y';
         $this->json['starthtml_on'] = $starthtml_on;

         file_put_contents(TEMPLATE_PATH.'/css/template.json', json_encode($this->json, JSON_PRETTY_PRINT));
         // kein Backup
      }

      else {
         require_once ADMIN_PATH.'/classes/designTemplate.class.php';
         $design = new KANPAICLASSIC_designTemplate();
         $text_array = $design->_loadTexte();
         $html = $text_array[0];
      }

      exit(json_encode(['status' => 'ok', 'html' => $html, 'reload' => $reload]));
   }

   private function saveCollage() {
      $html       ='';
      $reload     = 'n';
      $collage_on = $this->params->postCheckbox('collage_on');

      if ($this->json['collage_on'] !== $collage_on) {
         $this->json['collage_on'] = $collage_on;

         file_put_contents(TEMPLATE_PATH.'/css/template.json', json_encode($this->json, JSON_PRETTY_PRINT));
         // kein Backup
      }

      $reload     = 'y';
/* TODO Template-Snippet aus templat_xyz.tpl.php
      else {
         $image_url            = TEMPLATE_URL.'/images/';
         $image_path           = TEMPLATE_PATH.'/images/';
         $sel_lang             = $this->params->selected_lang;
         $no_img               = ADMIN_URL.'/img/nopic.png';
         $this->params->getLinks($sel_lang);
         require_once SHOP_PATH.'/classes/modules/livedesigner/popup_collage.tpl.php';
      }
*/

      exit(json_encode(['status' => 'ok', 'html' => $html, 'reload' => $reload]));
   }

   private function templateCss() {
      $params = $this->params;
      $is_flaeche_header = ($params->firma['flaeche'] == 'n' ? false : true);
      $is_flaeche        = ($params->firma['flaeche_hg'] == 'n' ? false : true);
      $is_flaeche_footer = ($params->firma['flaeche_footer'] == 'n' ? false : true);
      $content_width = CONF_BANNERBREITE;

      if (isset($params->firma['max_width'])) {
         $content_width = $params->firma['max_width'];
      }

      $content_padding = 10;
      if (isset($params->firma['content_padding'])) {
         $content_padding = $params->firma['content_padding'];
      }

      $logo_width = CONF_BANNERBREITE;

      $thumb_width  = ((int)$params->firma['thumb_width'] > 0 ? (int)$params->firma['thumb_width'] : CONF_THUMB_X);
      $thumb_height = ((int)$params->firma['thumb_height'] > 0 ? (int)$params->firma['thumb_height'] : CONF_THUMB_Y);
      $artikel_abstand_h = 10;
      $artikel_abstand_v = 10;
      $art_line_h = 0;
      $art_line_v = 0;

      $text_height = 0;
      if ($params->firma['thumb_over_check'] != 'y') {
         $text_height =  53;
      }

      if ($params->firma['linien_horz'] == 'y') {
         list(, $art_line_h) = getimagesize(TEMPLATE_PATH.'/images/system/line_horizontal.png');
         if (($artikel_abstand_h - $art_line_h) % 2 != 0) {
            $artikel_abstand_h++;
         }
      }
      if ($params->firma['linien_vert'] == 'y') {
         list($art_line_v) = getimagesize(TEMPLATE_PATH.'/images/system/line_vertikal.png');
         if (($artikel_abstand_v - $art_line_v) % 2 != 0) {
            $artikel_abstand_v++;
         }
      }
      if (isset($params->firma['max_width'])) {
         $logo_width = ($content_width + WIDTH_ADD + 2 * $content_padding + (WIDTH_ADD > 0 ? $content_padding : 0));
      }

      $is_flaeche_liste = false;

      if ($params->firma['bildschirmbreit'] == 'y' && ($params->task == 'kategorie' || $params->task == '' || $params->task == 'startseite')) {
         $is_flaeche_liste = true;
      }

      $cat_left            = ($params->firma['kategorien_links'] == 'y' || $params->firma['kategorien_links'] == 'l' ? true : false);
      $schatten            = ($params->firma['schatten'] == 'y' ? ' shadow' : '');
      $background_repeat   = 'repeat';
      $background_position = 'center top';
      $background_size     = '';

      if ($params->firma['bg_repeat'] == 'n') {
         $background_repeat   = 'no-repeat';
         $background_position = 'center center';
         $background_size     = 'background-size:cover;';
      }

      else if ($params->firma['bg_repeat'] == 'x') {
         $background_repeat   = 'repeat-x';
         $background_position = 'center top';
      }

      else if ($params->firma['bg_repeat'] == 'y') {
         $background_repeat   = 'repeat-y';
         $background_position = 'center top';
      }

      $bg_img = TEMPLATE_URL.'/images/system/no_pic1x1.png';

      if (file_exists(TEMPLATE_PATH.'/images/bg.jpg')) {
         $bg_img = TEMPLATE_URL.'/images/bg.jpg';
      }

      $fonts = array();
      $font_url = SHOP_URL.'/fonts';
      $fonts_css = '';
      require SHOP_PATH.'/classes/base/googlefonts.inc.php';

      // is_numeric wegen Fehlermeldungen bei Umstellung
      $fonts[] = (is_numeric($params->firma['fontfamily1']) ? $googlefonts[$params->firma['fontfamily1']] : array('', 400, 'Arial', '', ''));
      $fonts[] = (is_numeric($params->firma['fontfamily2']) ? $googlefonts[$params->firma['fontfamily2']] : array('', 400, 'Arial', '', ''));
      $fonts[] = (is_numeric($params->firma['fontfamily3']) ? $googlefonts[$params->firma['fontfamily3']] : array('', 400, 'Arial', '', ''));
      $fonts[] = (is_numeric($params->firma['fontfamily4']) ? $googlefonts[$params->firma['fontfamily4']] : array('', 400, 'Arial', '', ''));

      // String für Google-Fonts bauen
      foreach ($fonts as $font) {
         if (isset($font[5]) && $font[5] != '') {
            $fonts_css .= $font[5].CR;
         }
      }

      $fontsize1 = 22;
      if (isset($params->firma['fontsize1'])) {
         $fontsize1 = $params->firma['fontsize1'];
      }

      $fontsize2 = 18;
      if (isset($params->firma['fontsize2'])) {
         $fontsize2 = $params->firma['fontsize2'];
      }

      $fontsize3 = 14;
      if (isset($params->firma['fontsize3'])) {
         $fontsize3 = $params->firma['fontsize3'];
      }

      $fontsize4 = 12;
      if (isset($params->firma['fontsize4'])) {
         $fontsize4 = $params->firma['fontsize4'];
      }

      require_once TEMPLATE_PATH.'/css/template_var.css';
      exit;
   }

   private function saveModuleBackground() {
      $modul_id   = $this->params->postString('modul_id');
      $bg         = ltrim($this->params->postString('background'), '#');
      $bg_opc     = $this->params->postString('background_opc');
      $background = 'bg_innen';

      if ($modul_id == 'starthtml') {
         if ($bg != 'bg_innen') {
            $background  = hexdec(substr($bg, 0, 2)).','.hexdec(substr($bg, 2, 2)).','.hexdec(substr($bg, 4, 2)).','.$bg_opc;
         }

         $this->db->query("UPDATE #__module SET background_color = '$background' WHERE categorie = 'starthtml' AND module = 'starthtml'");
      }

      exit(Json_encode(['status' => 'ok']));
   }

   private function hinzufuegen($module_arr) {
      $html = '';

      if (is_object($this->livedesigner2)) {
         $modules    = $this->db->queryAllObjects("SELECT * FROM #__module WHERE categorie = 'livedesigner_modul' ORDER BY sort");
         $anz_module = 0;
         $html      .= '<div id="livedesigner2_sortable">';

         if ($modules) {
            foreach ($modules as $m) {
               if ($m->module == 'artikel') {
                  $html .= $this->livedesigner2->moduleArtikel($m);
               }

               if ($m->module == 'bild') {
                  $html .= $this->livedesigner2->moduleBild($m);
               }

               if ($m->module == 'text') {
                  $html .= $this->livedesigner2->moduleText($m);
               }

               if (is_object($this->livedesigner_ext)) {
                  if ($m->module == 'karussell') {
                     $html .= $this->livedesigner_ext->moduleKarussell($m);
                     $module_arr['karussell']['active'] = 'y';
                     $anz_module++;
                  }

                  if ($m->module == 'accordion') {
                     $html .= $this->livedesigner_ext->moduleAccordion($m);
                     $module_arr['accordion']['active'] = 'y';
                     $anz_module++;
                  }

                  if ($m->module == 'slider') {
                     $html .= $this->livedesigner_ext->moduleSlider($m);
                     $module_arr['slider']['active'] = 'y';
                     $anz_module++;
                  }
               }
            }
         }
         $html .= '</div>';

         $html .= '<div>';
         $html .= '   <span class="selectbox30"'.($anz_module == count($module_arr) ? ' style="display:none;"' : '').'>';
         $html .= '      <select id="livedesigner_select" onchange="Livedesigner.moduleNew($(this).val())">';
         $html .= '        <option value="">Hinzufügen</option>';

         foreach ($module_arr as $k => $m) {
            $html .= '        <option value="'.$k.'"'.($m['active'] == 'y' ? ' style="display:none;"' : '').' data-mode="'.$m['mode'].'">'.$m['name'].'</option>';
         }

         $html .= '      </select>';
         $html .= '   </span>';
         $html .= '</div>';
      }

      return $html;
   }

   public function moduleNew() {
      $module = $this->params->postString('module') ;

      if ($module == 'artikel') {
         $this->livedesigner2->moduleArtikelNew();
      }

      else if ($module == 'bild') {
         $this->livedesigner2->moduleBildNew();
      }

      else if ($module == 'text') {
         $this->livedesigner2->moduleTextNew();
      }

      else if ($module == 'accordion') {
         $this->livedesigner_ext->moduleAccordionNew();
      }

      else if ($module == 'karussell') {
         $this->livedesigner_ext->moduleKarussellNew();
      }

      else if ($module == 'slider') {
         $this->livedesigner_ext->moduleSliderNew();
      }
      exit(json_encode(['status' => 'error', 'msg' => 'falsche Auswahl']));
   }

   public function moduleActive() {
      $modul_id = $this->params->postInt('modul_id');
      $active   = $this->params->postCheckbox('active');

      $this->db->query("UPDATE #__module SET active = '$active' WHERE id = $modul_id");

      exit(json_encode(['status' => 'ok', 'active' => $active]));
   }

   public function moduleSort() {
      $sort_arr = $this->params->postArray('sort_arr');

      if (is_array($sort_arr)) {
         foreach ($sort_arr as $s) {
            $this->db->query("UPDATE #__module SET sort = '$s[1]' WHERE id = $s[0]");
         }
      }

      exit(json_encode(['status' => 'ok']));

   }

   public function moduleDelete() {
      $modul_id = $this->params->postInt('modul_id');
      $modul = $this->db->querySingleObject("SELECT * FROM #__module WHERE id = $modul_id");

      if ($modul) {
         // Bilder löschen
         if ($modul->module == 'bild') {
            $this->livedesigner2->bildDeleteModul($modul);
         }

         if ($modul->module == 'karussell') {
            $this->livedesigner_ext->KarussellDeleteModul($modul);
         }

         if ($modul->module == 'slider') {
            $this->livedesigner_ext->SliderDeleteModul($modul);
         }

         if ($modul->module == 'accordion') {
            $this->livedesigner_ext->AccordionDeleteModul($modul);
         }
      }

      $this->db->query("DELETE FROM #__module WHERE id = $modul_id");
      exit(json_encode(['status' => 'ok', 'modul_id' => $modul_id]));

   }

   // Module für FE generieren
   public function frontend($is_flaeche_mitte, $livedesigner, $cat_left) {

      $html   = '';
      $module = null;

      if (is_object($this->livedesigner2)) {
         $module = $this->db->queryAllObjects("SELECT * FROM #__module WHERE categorie = 'livedesigner_modul' AND active = 'y' ORDER BY sort");

         if ($module) {
            $html .= '<div id="module">'.CR;

            foreach ($module as $m) {

               $modul_id = $m->id;
               $anzahl   = (int)$m->anzahl;

               if ($m->module == 'artikel') {
                  $articles    = Control::getArticles();
                  $artikel     = json_decode($m->value);
                  $color       = ($m->extra != '' ? $m->extra : '#ffffff');
                  $bg          = \KANPAICLASSIC\Helper::moduleColor($m->background_color);
                  $background  = $bg->css;
                  $article_ids = [];


                  for ($i = 0; $i < $anzahl; $i++) {
                     $article_ids[] = $artikel[$i];
                  }

                  if (!empty($article_ids)) {
                     include SHOP_PATH.'/classes/modules/livedesigner2/fe_artikel.tpl.php';
                  }

               }

               if ($m->module == 'bild') {
                  include SHOP_PATH.'/classes/modules/livedesigner2/fe_bild.tpl.php';
               }

               if ($m->module == 'text') {
                  $texte      = json_decode($m->value);
                  $bg         = \KANPAICLASSIC\Helper::moduleColor($m->background_color);
                  $background = $bg->css;
                  include SHOP_PATH.'/classes/modules/livedesigner2/fe_text.tpl.php';
               }

               // Modul Livedesigner_ext vorhanden
               if (is_object($this->livedesigner_ext)) {
                  if ($m->module == 'accordion') {
                     $bg          = \KANPAICLASSIC\Helper::moduleColor($m->background_color);
                     $background  = $bg->css;

                     include SHOP_PATH.'/classes/modules/livedesigner_ext/fe_accordion.tpl.php';
                  }

                  if ($m->module == 'karussell') {
                     $bg         = \KANPAICLASSIC\Helper::moduleColor($m->background_color);
                     $background = $bg->css;

                     include SHOP_PATH.'/classes/modules/livedesigner_ext/fe_karussell.tpl.php';
                  }

                  if ($m->module == 'slider') {
                     $texte      = json_decode($m->value);
                     $bg         = \KANPAICLASSIC\Helper::moduleColor($m->background_color);
                     $background = $bg->css;
                     include SHOP_PATH.'/classes/modules/livedesigner_ext/fe_slider.tpl.php';
                  }
               }
            }

            $html .= '</div>'.CR;
         }

         return $html;
      }
   }
}