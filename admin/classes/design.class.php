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

define ('SLIDE_HEIGHT', 800);

class KANPAICLASSIC_design
{
   public    $db;
   public    $params;
   public    $text;
   private   $templates = [];
   private   $rechnung = 0;
   private   $template_config = null;
   //protected $json = [];
   public    $json = [];
   protected $default_json = [];
   protected $image_ratio_default = '1.33';

   function __construct() {
      $this->db     = Control::getDB();
      $this->params = Control::getParams();
      $this->text   = Control::getText();
   }

   // Default-Werte, werden durch /templ.../css/template.json �berschrieben
   // 30.05. 2019
   private function _defaultJson() {
      $default_json = [
                           'shop_check'          => 'n',
                           'bg_fixed'            => 'y',
                           'bg_repeat'           => 'z',
                           'max_width'           => CONF_BANNERBREITE,  // startseite_breite bei Template Fullscreen
                           'content_padding'     => 10,
                           'flaeche'             => 'y',       // Header
                           'bildschirmbreit'     => 'n',       // Artikelliste
                           'flaeche_hg'          => 'y',       // Shopmitte
                           'flaeche_footer'      => 'y',       // Footer
                           'abstand_oben'        => '20',      // nur wenn flaeche inaktiv
                           'schatten'            => 'n',
                           'detailbild'          => 3,
                           'startseite_breite'   => 'kategorien',  // Template Beauty: kategorien / breit /fullscreen: kategorien => volle Breite / breit => max_width
                           'startseite_artikel'  => 'reihen',     // artikel / reihen => startseite_reihen
                           'startseite_reihen'   => '3',

                           'slideshow_on'        => 'y',
                           'rechts_slide'        => 'y',
                           'fullscreen_slide'    => 'n',
                           'slideshow_r_check'   => 'n',
                           'fullscreen_slide_b'  => 1900,
                           'fullscreen_slide_h'  => 750,

                           'starthtml_on'        => 'n',
                           'collage_on'          => 'n',

                           'zoom_artikel'        => 'y',
                           'bild_tab'            => 'y',
                           'linien_vert'         => 'n',
                           'linien_horz'         => 'n',
                           'linien_kat'          => 'n',
                           'kategorien_links'    => 'h',
                           'art_zeilen'          => 1,

                           'bg_artikelbild'      => 'ffffff',
                           'thumb_width'         => '',
                           'thumb_height'        => '',
                           'thumb_over_check'    => 'n',
                           'merkmal_over_check'  => 'n',
                           'abstand'             => '40',
                           'cpf_size'            => 'normal',
                           'cbp_display'         => 'lazyLoading',
                           'cbp_animation'       => '3dflip',
                           'image_ratio'         => $this->image_ratio_default,
                           'wk_popup_check'      => 'n',

                           'anmelden_mode'       => 1,
                           'merkliste_mode'      => 2,
                           'warenkorb_mode'      => 1,
                           'suchfeld_mode'       => 1,
                           'flaggen_mode'        => 1,
                           'icon_farbe'          => 'weiss',

                           'footer_mode'         => 'freundlich', // komplex
                           'footer_farbe'        => 'weiss',      // dunkel

                           'footer_dhl'          => 'n',          // y / n
                           'footer_dpd'          => 'n',          // y / n
                           'footer_hermes'       => 'n',          // y / n
                           'footer_gls'          => 'n',          // y / n
                           'footer_ups'          => 'n',          // y / n
                           'footer_post'         => 'n',          // y / n
                           'footer_ssl'          => 'n',          // y / n

                           'footer_bar'          => 'n',          // y / n
                           'footer_ueberweisung' => 'n',          // y / n
                           'footer_rechnung'     => 'n',          // y / n
                           'footer_nachnahme'    => 'n',          // y / n
                           'footer_paypal'       => 'n',          // y / n
                           'footer_paypalplus'   => 'n',          // y / n
                           'footer_visa'         => 'n',          // y / n
                           'footer_sofort'       => 'n',          // y / n
                           'footer_klarna'       => 'n',          // y / n
                           'footer_amazon'       => 'n',          // y / n
                           'footer_easycredit'   => 'n',          // y / n
                           'footer_paydirekt'    => 'n',          // y / n
                           'footer_ratenkauf'    => 'n',          // y / n
                           'footer_postfinance'  => 'n',          // y / n
                           'footer_twint'        => 'n',          // y / n
                           'footer_wir'          => 'n',          // y / n
                           'footer_swisspay'     => 'n',          // y / n

//                           'bannerlink1'         => '',
//                           'bannerlink2'         => '',
//                           'banner1_intern'      => 'n',
//                           'banner2_intern'      => 'n',
                           'bannerunten_on'      => 'y',
                           'artikelliste_on'     => 'y',

                           'logoseo'             => '',
                           'bannerseo1'          => '',
                           'bannerseo2'          => '',

                           'fontfamily1'         => '1900',
                           'fontsize1'           => '20',
                           'fontfamily2'         => '1900',
                           'fontsize2'           => '18',
                           'fontfamily3'         => '1900',
                           'fontsize3'           => '16',
                           'fontfamily4'         => '1900',
                           'fontsize4'           => '13'
                     ];

      return $default_json;
   }

   // Alias
   public function loadJson($store = false) {
      $this->_loadJson($store);
   }

   // default_jason mit Werten aus Datei überschreiben (dadurch erweiterbar)
   // 08.05.2019
   private function _loadJson($store = false) {
      $this->default_json = $this->_defaultJson();
      $file_json          = [];

      if (file_exists(TEMPLATE_PATH.'/css/template.json')) {
         $file_json = json_decode(file_get_contents(TEMPLATE_PATH.'/css/template.json'), true);

         if (!isset($file_json['image_ratio']) || $file_json['image_ratio'] == 0) {
            $file_json['image_ratio'] = $this->image_ratio_default;
         }

         // Kompatibilitäts-Korrektur
         if (isset($file_json['cpf_zoom']) && !isset($file_json['zoom_artikel'])) {
            $file_json['zoom_artikel'] = $file_json['cpf_zoom'];
         }

         unset($file_json['cpf_zoom']);
      }

      $this->json = array_merge($this->default_json, $file_json);

      if ($store) {
         file_put_contents(TEMPLATE_PATH.'/css/template.json', json_encode($this->json, JSON_PRETTY_PRINT));
         $this->jsonBackup();
      }
   }

   // Werte von designEinstellungen speichern
   // 30.05.2019
   public function saveJson() {
      $this->_loadJson();
      $this->json['bg_fixed']           = $this->params->postRadio('bg_fixed');
      $this->json['bg_repeat']          = $this->params->postRadio('bg_repeat');
      $this->json['shop_check']         = $this->params->postCheckbox('shop_check');
      $this->json['content_padding']    = $this->params->postInt('content_padding');
      $this->json['abstand_oben']       = $this->params->postInt('abstand_oben');
      $this->json['flaeche']            = $this->params->postRadio('flaeche');               // flaeche_header
      $this->json['flaeche_hg']         = $this->params->postCheckbox('flaeche_mitte');      // flaeche_mitte
      $this->json['bildschirmbreit']    = $this->params->postCheckbox('bildschirmbreit');    // flaeche_artikelliste
      $this->json['flaeche_footer']     = $this->params->postRadio('flaeche_footer');        // flaeche_footer
      $this->json['schatten']           = $this->params->postRadio('schatten');
      $this->json['startseite_breite']  = $this->params->postString('startseite_breite');
      $this->json['artikelliste_on']    = $this->params->postCheckbox('artikelliste_on');
      $this->json['startseite_artikel'] = $this->params->postString('startseite_artikel');
      $this->json['startseite_reihen']  = $this->params->postInt('startseite_reihen');
      $this->json['slideshow_on']       = $this->params->postCheckbox('slideshow_on');
      $this->json['rechts_slide']       = $this->params->postCheckbox('rechts_slide');
      $this->json['slideshow_r_check']  = $this->params->postCheckbox('slideshow_r_check');
      $this->json['fullscreen_slide']   = $this->params->postCheckbox('fullscreen_slide');
      $this->json['fullscreen_slide_b'] = $this->default_json['fullscreen_slide_b'];
      $this->json['fullscreen_slide_h'] = $this->default_json['fullscreen_slide_h'];
      $this->json['collage_on']         = $this->params->postCheckbox('collage_on');
      $this->json['zoom_artikel']       = $this->params->postCheckbox('zoom_artikel');
      $this->json['bild_tab']           = $this->params->postRadio('bild_tab');
      $this->json['linien_vert']        = $this->params->postCheckbox('linien_vert');
      $this->json['linien_horz']        = $this->params->postCheckbox('linien_horz');
      $this->json['linien_kat']         = $this->params->postCheckbox('linien_kat');
      $this->json['starthtml_on']       = $this->params->postCheckbox('starthtml_on');
      $this->json['bannerunten_on']     = $this->params->postCheckbox('bannerunten_on');
      $this->json['kategorien_links']   = $this->params->postString('kategorien_links');
      $this->json['cbp_display']        = $this->params->postString('cbp_display');
      $this->json['cbp_animation']      = $this->params->postString('cbp_animation');
      $this->json['footer_mode']        = $this->params->postString('footer_mode');

      // Ab Template fullscreen
      $size  = 300;
      $ratio = $this->params->postFloat('image_ratio');

      if (defined('CONF_THUMBWIDTH_NORMAL')) {
         $size = CONF_THUMBWIDTH_NORMAL;
      }

      if ($ratio == 0) {
         $ratio = $this->image_ratio_default;
      }
      else {
         $ratio = round((1 / $ratio), 4);
      }

      $this->json['thumb_width']        = $size;
      $this->json['image_ratio']        = $ratio;
      $this->json['thumb_height']       = (int)round($size / $ratio);
      $this->json['thumb_over_check']   = $this->params->postCheckbox('thumb_over_check');
      $this->json['merkmal_over_check'] = $this->params->postCheckbox('merkmal_over_check');
      $this->json['max_width']          = ($this->params->postInt('max_width') > 0 ? $this->params->postInt('max_width') : CONF_BANNERBREITE);
      $this->json['abstand']            = $this->params->postInt('abstand');
      $this->json['cpf_size']           = $this->params->postString('cpf_size');
      $this->json['cpf_zoom']           = $this->params->postCheckbox('cpf_zoom');
      $this->json['wk_popup_check']     = $this->params->postCheckbox('wk_popup_check');

      file_put_contents(TEMPLATE_PATH.'/css/template.json', json_encode($this->json, JSON_PRETTY_PRINT));
      $this->jsonBackup();
   }

   // Werte von designColors speichern
   // 30.05.2019
   protected function saveJsonFonts() {
      $this->_loadJson();

      $this->json['fontfamily1']        = $this->params->postString('fontfamily1');
      $this->json['fontsize1']          = $this->params->postString('fontsize1');
      $this->json['fontfamily2']        = $this->params->postString('fontfamily2');
      $this->json['fontsize2']          = $this->params->postString('fontsize2');
      $this->json['fontfamily3']        = $this->params->postString('fontfamily3');
      $this->json['fontsize3']          = $this->params->postString('fontsize3');
      $this->json['fontfamily4']        = $this->params->postString('fontfamily4');
      $this->json['fontsize4']          = $this->params->postString('fontsize4');
      // 1 - oder 2 zeilig
      $this->json['art_zeilen']         = $this->params->postInt('art_zeilen');
      // Hintergrund Thumb-Erstellung
      $this->json['bg_artikelbild']     = str_replace(['%23', '#'], '', $this->params->postString('bg_artikelbild'));

      file_put_contents(TEMPLATE_PATH.'/css/template.json', json_encode($this->json, JSON_PRETTY_PRINT));

      // Löschen, damit vom Editor-Config-Script neu erstellt wird.
      @unlink(TEMPLATE_PATH.'/save/editor_content.css');

      // Sicherheitskopien anlegen
      $this->jsonBackup();
   }

   // Sicherheitskopien von /templ.../css/template.json erstellen
   // 30.05.2019
   protected function jsonBackup() {
      if (!is_dir(TEMPLATE_PATH.'/save')) {
         mkdir(TEMPLATE_PATH.'/save');
      }

      if (file_exists(TEMPLATE_PATH.'/save/template.json_9')) {
         @unlink(TEMPLATE_PATH.'/save/template.json_9');
      }

      if (file_exists(TEMPLATE_PATH.'/save/template.json_8')) {
         rename(TEMPLATE_PATH.'/save/template.json_8', TEMPLATE_PATH.'/save/template.json_9');
      }

      if (file_exists(TEMPLATE_PATH.'/save/template.json_7')) {
         rename(TEMPLATE_PATH.'/save/template.json_7', TEMPLATE_PATH.'/save/template.json_8');
      }

      if (file_exists(TEMPLATE_PATH.'/save/template.json_6')) {
         rename(TEMPLATE_PATH.'/save/template.json_6', TEMPLATE_PATH.'/save/template.json_7');
      }

      if (file_exists(TEMPLATE_PATH.'/save/template.json_5')) {
         rename(TEMPLATE_PATH.'/save/template.json_5', TEMPLATE_PATH.'/save/template.json_6');
      }

      if (file_exists(TEMPLATE_PATH.'/save/template.json_4')) {
         rename(TEMPLATE_PATH.'/save/template.json_4', TEMPLATE_PATH.'/save/template.json_5');
      }

      if (file_exists(TEMPLATE_PATH.'/save/template.json_3')) {
         rename(TEMPLATE_PATH.'/save/template.json_3', TEMPLATE_PATH.'/save/template.json_4');
      }

      if (file_exists(TEMPLATE_PATH.'/save/template.json_2')) {
         rename(TEMPLATE_PATH.'/save/template.json_2', TEMPLATE_PATH.'/save/template.json_3');
      }

      if (file_exists(TEMPLATE_PATH.'/save/template.json_1')) {
         rename(TEMPLATE_PATH.'/save/template.json_1', TEMPLATE_PATH.'/save/template.json_2');
      }

      if (file_exists(TEMPLATE_PATH.'/css/template.json')) {
         copy(TEMPLATE_PATH.'/css/template.json', TEMPLATE_PATH.'/save/template.json_1');
      }

      // Konfiguration
      if (file_exists(TEMPLATE_PATH.'/save/config.inc.php_9')) {
         @unlink(TEMPLATE_PATH.'/save/config.inc.php_9');
      }

      if (file_exists(TEMPLATE_PATH.'/save/config.inc.php_8')) {
         rename(TEMPLATE_PATH.'/save/config.inc.php_8', TEMPLATE_PATH.'/save/config.inc.php_9');
      }

      if (file_exists(TEMPLATE_PATH.'/save/config.inc.php_7')) {
         rename(TEMPLATE_PATH.'/save/config.inc.php_7', TEMPLATE_PATH.'/save/config.inc.php_8');
      }

      if (file_exists(TEMPLATE_PATH.'/save/config.inc.php_6')) {
         rename(TEMPLATE_PATH.'/save/config.inc.php_6', TEMPLATE_PATH.'/save/config.inc.php_7');
      }

      if (file_exists(TEMPLATE_PATH.'/save/config.inc.php_5')) {
         rename(TEMPLATE_PATH.'/save/config.inc.php_5', TEMPLATE_PATH.'/save/config.inc.php_6');
      }

      if (file_exists(TEMPLATE_PATH.'/save/config.inc.php_4')) {
         rename(TEMPLATE_PATH.'/save/config.inc.php_4', TEMPLATE_PATH.'/save/config.inc.php_5');
      }

      if (file_exists(TEMPLATE_PATH.'/save/config.inc.php_3')) {
         rename(TEMPLATE_PATH.'/save/config.inc.php_3', TEMPLATE_PATH.'/save/config.inc.php_4');
      }

      if (file_exists(TEMPLATE_PATH.'/save/config.inc.php_2')) {
         rename(TEMPLATE_PATH.'/save/config.inc.php_2', TEMPLATE_PATH.'/save/config.inc.php_3');
      }

      if (file_exists(TEMPLATE_PATH.'/save/config.inc.php_1')) {
         rename(TEMPLATE_PATH.'/save/config.inc.php_1', TEMPLATE_PATH.'/save/config.inc.php_2');
      }

      if (file_exists(TEMPLATE_PATH.'/admin/config.inc.php')) {
         copy(TEMPLATE_PATH.'/admin/config.inc.php', TEMPLATE_PATH.'/save/config.inc.php_1');
      }
   }

   // Bilder löschen (in /images des aktuellen Templates)
   // 30.05.2019
   protected function deleteImg($filename = '') {
      $uploaddir = TEMPLATE_PATH.'/images/';
      $lang      = $this->params->selected_lang;
      $pic_nr    = 0;

      if ($filename == '') {
         $filename = $this->params->postString('image');
         $pic_nr   = $this->params->postInt('pic_nr');
      }

      if ($filename == 'favicon') {
         $files = glob($uploaddir.'/apple-icon*');
         array_walk($files, function($f) { unlink($f); });

         $files = glob($uploaddir.'/android-icon*');
         array_walk($files, function($f) { unlink($f); });

         $files = glob($uploaddir.'/ms-icon*');
         array_walk($files, function($f) { unlink($f); });

         $files = glob($uploaddir.'/tile-*');
         array_walk($files, function($f) { unlink($f); });

         $files = glob($uploaddir.'/favicon*');
         array_walk($files, function($f) { unlink($f); });

         return;
      }

      if ($filename == 'accordion') {
         $filename .= '_'.$lang.'_'.$pic_nr;
      }

      if ($filename == 'carussell') {
         $filename .= '_'.$lang.'_'.$pic_nr;
      }

      if ($filename == 'slider') {
         $filename .= '_'.$lang.'_'.$pic_nr;
      }

      // Slideshow
      if ($filename == 'slide') {
         $filename .= $pic_nr;

         @unlink($uploaddir.'slide'.$pic_nr.'_'.$lang.'.jpg');
         @unlink($uploaddir.'slide'.$pic_nr.'l_'.$lang.'.jpg');
         @unlink($uploaddir.'slide'.$pic_nr.'s_'.$lang.'.jpg');
         @unlink($uploaddir.'slide'.$pic_nr.'w_'.$lang.'.jpg');
         @unlink($uploaddir.'slide'.$pic_nr.'_'.$lang.'.png');
         @unlink($uploaddir.'slide'.$pic_nr.'l_'.$lang.'.png');
         @unlink($uploaddir.'slide'.$pic_nr.'s_'.$lang.'.png');
         @unlink($uploaddir.'slide'.$pic_nr.'w_'.$lang.'.png');

      }

      // Collage
      if ($filename == 'collage') {
         $filename = 'bild'.$pic_nr.'_'.$lang;
      }

      // Auflösung Handy
      if ($filename == 'startbild_video') {
         @unlink($uploaddir.'startbild_videos_'.$lang.'.jpg');
      }

      @unlink($uploaddir.$filename.'.jpg');
      @unlink($uploaddir.$filename.'_tn.jpg');
      @unlink($uploaddir.$filename.'_tp.jpg');

      @unlink($uploaddir.$filename.'_'.$lang.'.jpg');
      @unlink($uploaddir.$filename.'_'.$lang.'_tn.jpg');
      @unlink($uploaddir.$filename.'_'.$lang.'_tp.jpg');

      @unlink($uploaddir.$filename.'.png');
      @unlink($uploaddir.$filename.'_tn.png');
      @unlink($uploaddir.$filename.'_tp.png');

      @unlink($uploaddir.$filename.'_'.$lang.'.png');
      @unlink($uploaddir.$filename.'_'.$lang.'_tn.png');
      @unlink($uploaddir.$filename.'_'.$lang.'_tp.png');

      @unlink($uploaddir.$filename.'.swf');
      @unlink($uploaddir.$filename.'_'.$lang.'.swf');
      @unlink($uploaddir.$filename.'.mp4');
      @unlink($uploaddir.$filename.'_'.$lang.'.mp4');
      @unlink($uploaddir.$filename.'.webm');
      @unlink($uploaddir.$filename.'_'.$lang.'.webm');
      @unlink($uploaddir.$filename.'.mov');
      @unlink($uploaddir.$filename.'_'.$lang.'.mov');
      @unlink($uploaddir.$filename.'.json');
      @unlink($uploaddir.$filename.'_'.$lang.'.json');

      return $filename.'_x';
   }

   // Logos / Banner hochladen und Größe anpassen
   // Helper::resizePic konvertiert Bild und speichert ab
   // 30.05.2019
   protected function _fileUpload($file = '') {
      Helper::setData('image_cache', time());
      // Namen aus $_FILES lesen
//      $tempfile = $_FILES['file']['tmp_name'];
//      $tempname  = $file;
      $tempname  = $this->params->postString('param1');
      $bild_nr   = $this->params->postString('param2');
      $filetype  = '';
      $hoehe     = 0;
      $width_add = ($this->params->firma['kategorien_links'] == 'y' || $this->params->firma['kategorien_links'] == 'l' ? (WIDTH_MENU + $this->params->firma['content_padding']) : 0);

      if (!empty($_FILES) && $tempname != '') {
         if ($file == '') {
            // Namen aus $_FILES lesen
            $filetype    = $_FILES['file']['type'];
            $ext         = Helper::getExtension($_FILES['file']['name']);
            $uploaddir   = TEMPLATE_PATH.DIRECTORY_SEPARATOR.'images'.DIRECTORY_SEPARATOR;
            $uploadurl   = TEMPLATE_URL.'/images/';
            $lang        = $this->params->selected_lang;
            $file_upload = '';

         }

         // Startbild / Startvideo
         if ($tempname == 'startbild_video') {
            move_uploaded_file($_FILES['file']['tmp_name'], $uploaddir.$tempname);

            list($breite, $hoehe) = getimagesize($uploaddir.$tempname);

            if ($hoehe > $this->params->firma['fullscreen_slide_h']) {
               $hoehe = $this->params->firma['fullscreen_slide_h'];
            }

            $this->deleteImg('startbild_video');
            $this->deleteImg('startbild_videos');

            if ($ext == 'mp4') {
               $video = $uploaddir.'startbild_video_'.$lang.'.'.$ext;

               rename($uploaddir.$tempname, $video);
               $file_upload = ADMIN_URL.'/img/video.png';
            }

            else if ($ext == 'webm') {
               rename($uploaddir.$tempname, $uploaddir.'startbild_video_'.$lang.'.'.$ext);
               $file_upload = ADMIN_URL.'/img/video.png';
            }
            
            else if ($ext == 'mov') {
               rename($uploaddir.$tempname, $uploaddir.'startbild_video_'.$lang.'.'.$ext);
               $file_upload = ADMIN_URL.'/img/video.png';
            }

            else {
               $file_upload = $uploadurl.'startbild_video_'.$lang.'_tn.'.$ext;
               $filename1   = $uploaddir.'startbild_video_'.$lang.'.'. $ext;
               $filename2   = $uploaddir.'startbild_videos_'.$lang.'.'. $ext;

               list($b, $h) = getimagesize($uploaddir.$tempname);
               $h1 = $h;
               $h2 = $h;
               $b1 = 1900;
               $b2 = 1024;

               if ($b < $b1) {
                  $b1 = $b;
               }

               if ($b < $b2) {
                  $b2 = $b;
               }

               $h1 = round($b1 * $h/$b);
               $h2 = round($b2 * $h/$b);

               Helper::resizePicCenter($uploaddir.$tempname, $filename1, $b1, $h1, 'jpg', false);
               Helper::resizePicCenter($uploaddir.$tempname, $filename2, $b2, $h2, 'jpg', false);
               Helper::imageResize($uploaddir.$tempname, $uploaddir.'startbild_video_'.$lang.'_tn.jpg', 78, 78, 'jpg', true, false, false, 78, 78, false);
            }
         }

         move_uploaded_file($_FILES['file']['tmp_name'], $uploaddir.$tempname);

         // Bei Video Fehlermeldung
         list($breite, $hoehe) = @getimagesize($uploaddir.$tempname);

         if ($hoehe > $this->params->firma['fullscreen_slide_h']) {
            $hoehe = $this->params->firma['fullscreen_slide_h'];
         }

         // Startseite / Slideshow
         if ($tempname == 'slide') {
            $breite1 = CONF_BANNERBREITE;

            // content_padding = 10px (hidden input -> tmpl/css/template.json)
            if (isset($this->params->firma['max_width'])) {
               $breite1 = $this->params->firma['max_width'] + 2 * $this->params->firma['content_padding'];
            }

            // Im FE verwendet, wenn Bilder rechts vorhanden (breite2 / hoehe2)
            $breite2 = $breite1 * 0.66;

            // Default
            $factor1 = 2.1;

            // Slideshow normale Breite
            // aus tmpl/template.inc.php bei ID: 2 (2.1),
            if (defined('CONF_SLIDESHOW_HEIGHT1')) {
               $factor1 = CONF_SLIDESHOW_HEIGHT1;
            }

            // Default
            $factor2 = 1.5;

            // mit rechten Bildern
            // aus tmpl/template.inc.php bei ID: 2 (1.69),
            if (defined('CONF_SLIDESHOW_HEIGHT2')) {
               $factor2 = CONF_SLIDESHOW_HEIGHT2;
            }

            $hoehe1 = round($breite1 / $factor1);
            $hoehe2 = round($breite2 / $factor2);

            // Hintergrundfarbe bg_innen
            $background = false;
            $match      = '';
            $mycss      = file_get_contents(TEMPLATE_PATH.'/css/colors.css');
            preg_match("/bg_innen\s\{\s.*?:\s*?rgba\((.*?),\s*?(.*?),\s*?(.*?),\s*?(.*?)\).*?\}/i", $mycss, $match);

            if (isset($match[4])) {
               $background = [(int)$match[1], (int)$match[2], (int)$match[3], (int)($match[4] * 127)];
            }

            // 8 Bilder für Slideshow
            if ($bild_nr < 9) {
               $file_upload  = $uploadurl.'slide'.$bild_nr.'_'.$lang.'.jpg';
               $file_upload1 = $uploadurl.'slide'.$bild_nr.'_'.$lang.'.jpg?'.time();    // ohne rechte Bilder
               $file_upload2 = $uploadurl.'slide'.$bild_nr.'w_'.$lang.'.jpg?'.time();     // mit rechten Bildern
               $file_upload3 = $uploadurl.'slide'.$bild_nr.'l_'.$lang.'.jpg?'.time();    // fullscreen
               $filename1    = $uploaddir.'slide'.$bild_nr.'w_'.$lang.'.jpg';    // ohne rechte Bilder
               $filename2    = $uploaddir.'slide'.$bild_nr.'_'.$lang.'.jpg';     // mit rechten Bildern
               $filename3    = $uploaddir.'slide'.$bild_nr.'l_'.$lang.'.jpg';    // fullscreen

               // Slideshow normal
               Helper::resizePicCenter($uploaddir.$tempname, $filename1, $breite1, $hoehe1, 'jpg', false, $background);

               // Slideshow mit Bilder rechts
               Helper::resizePicCenter($uploaddir.$tempname, $filename2, $breite2, $hoehe2, 'jpg', false, $background);

               // Slideshow fullscreen
               Helper::resizeImageSlideshow($uploaddir.$tempname, $filename3, 1900, 750, false);

               // Thumbnail / Anzeige Design
               Helper::imageResize($uploaddir.$tempname, $uploaddir.'slide'.$bild_nr.'_'.$lang.'_tn.jpg', 78, 78, 'jpg', true, false, false, 78, 78, false);

               exit(json_encode(['status' => 'ok', 'html' => $file_upload.'?'.time(), 'target' => 'img_src', 'img_normal' => $file_upload1, 'img_left' => $file_upload2, 'img_fullscreen' => $file_upload3, 'callback' => "Design.slideshowcallback('.$file_upload1.', '.$file_upload2.', '.$file_upload3.')"]));
            }

            // 2 Bilder rechts
            else {
               $hoehe       = round($hoehe2 * 0.5 * 0.98);
               $breite      = round($breite2 * 0.5);

               $file_upload = $uploadurl.'slide'.$bild_nr.'_'.$lang.'.jpg';
               $filename    = $uploaddir.'slide'.$bild_nr.'_'.$lang.'.'. $ext;

               Helper::resizePicCenter($uploaddir.$tempname, $filename, $breite, $hoehe, 'jpg', false, $background);
               // Thumbnail / Anzeige Design
               Helper::imageResize($uploaddir.$tempname, TEMPLATE_PATH.'/images/slide'.$bild_nr.'_'.$lang.'_tn.jpg', 78, 78, 'jpg', true, false, false, 78, 78, false);
            }
         }

         // 8 Bilder Collage
         if ($tempname == 'collage') {
            $factor = 1;

            if (isset($this->params->firma['max_width'])) {
               $factor = $this->params->firma['max_width'] / 1183;
            }

            $breite = round(591 * $factor);

            if ($bild_nr == 5 || $bild_nr == 8) {
               $breite = round(812 * $factor);
            }

            if ($bild_nr == 6 || $bild_nr == 7) {
               $breite = round(370 * $factor);
            }

            $hoehe = round(370 * $factor);
            $filename = $uploaddir.'bild'.$bild_nr.'_'.$lang.'.'. $ext;
            $file_upload = $uploadurl.'bild'.$bild_nr.'_'.$lang.'.jpg';

            Helper::resizePicCenter($uploaddir.$tempname, $filename, $breite, $hoehe, 'jpg' );
            Helper::imageResize($uploaddir.$tempname, TEMPLATE_PATH.'/images/bild'.$bild_nr.'_'.$this->params->selected_lang.'_tn.jpg', 78, 78, 'jpg', true, false, false, 78, 78, false);
         }

         // Logobanner als png
         if ($tempname == 'logobanner') {
            $breite = CONF_BANNERBREITE;

            if (isset($this->params->firma['max_width'])) {
               $breite = $this->params->firma['max_width'] + 2 * $this->params->firma['content_padding'] + $width_add ;
            }

            $filename    = $uploaddir.'logo_'.$lang.'.png';
            $file_upload = $uploadurl.'logo_'.$lang.'.png';
            Helper::imageResize($uploaddir.$tempname, $filename, $breite, 0, 'png', true, true, true, 0, 500, true);
         }

         // Logo im Menü als png
         if ($tempname == 'logomenu') {
            $file_upload = $uploadurl.'logomenu_'.$lang.'_tn.png';

            $filename = $uploaddir.'logomenu_'.$lang.'_tn.png';
            Helper::imageResize($uploaddir.$tempname, $filename, 0, 0, 'png', false, true, true, 0, 32, true);

            $filename = $uploaddir.'logomenu_'.$lang.'.png';
            Helper::imageResize($uploaddir.$tempname, $filename, 0, 0, 'png', true, true, true, 0, 500, true);

         }

         // Hintergrund
         if ($tempname == 'bg') {
            $file_upload = $uploadurl.'bg_tn.jpg';
            $filename    = $uploaddir.'bg.jpg';
//            Helper::resizePic($uploaddir.$tempname, $filename, 0, 0, '' );


//            $filename1   = $uploaddir.'bg.jpg';
//            $filename2   = $uploaddir.'bgs.jpg';

            list($b, $h) = getimagesize($uploaddir.$tempname);
            $h1 = $hoehe;
            $h2 = $hoehe;
            $b1 = 1900;
            $b2 = 1024;

            if ($breite < $b1) {
               $b1 = $breite;
            }

            if ($breite < $b2) {
               $b2 = $breite;
            }

            $h1 = round($b1 * $hoehe/$breite);
            $h2 = round($b2 * $hoehe/$breite);

//            Helper::resizePicCenter($uploaddir.$tempname, $filename1, $b1, $h1, 'jpg', false);
            Helper::resizePicCenter($uploaddir.$tempname, $filename, $b1, $h1, 'jpg', false);
//            Helper::resizePicCenter($uploaddir.$tempname, $filename2, $b2, $h2, 'jpg', false);
            Helper::imageResize($uploaddir.$tempname, $uploaddir.'bg_tn.jpg', 78, 78, 'jpg', true, false, false, 78, 78, false);
         }

         // Danke1 -> Seiten
         // Danke2 -> Seiten

         // Rechnungskof/-fuss
         if ($tempname == 'rechnungskopf') {
            $filename = $uploaddir.'rechnungskopf_'.$lang.'.jpg';
            $fileurl  = $uploadurl.'rechnungskopf_'.$lang.'.jpg';
            Helper::resizePic($uploaddir.$tempname, $filename, 0, 0, 'jpg' );
//              Helper::resizeImageSlideshow($uploaddir.$tempname, $filename, 2480, 612, true);

            $this->_renewPdf($lang);
            return $fileurl;
         }

         if ($tempname == 'rechnungsfuss') {
            $filename = $uploaddir.'rechnungsfuss_'.$lang.'.jpg';
            $fileurl  = $uploadurl.'rechnungsfuss_'.$lang.'.jpg';
            Helper::resizePic($uploaddir.$tempname, $filename, 0, 0, '' );
//              Helper::resizeImageSlideshow($uploaddir.$tempname, $filename, 2480, 372, true);

            $this->_renewPdf($lang);
            return $fileurl;
         }

         // Banner1 jpg
         if ($tempname == 'banner1') {
            $breite = CONF_BANNERBREITE;
            if (isset($this->params->firma['max_width'])) {
               $breite = $this->params->firma['max_width'] + 2 * $this->params->firma['content_padding'] + $width_add;
            }

            $filename = $uploaddir.'banner1_'.$lang.'.jpg';
            Helper::imageResize($uploaddir.$tempname, $filename, $breite, 0, 'png', true, true, false, 0, 500, false);
         }

         // Banner2 jpg
         if ($tempname == 'banner2') {
            $breite = CONF_BANNERBREITE;

            if (isset($this->params->firma['max_width'])) {
               $breite = $this->params->firma['max_width'] + 2 * $this->params->firma['content_padding'] + $width_add;
            }

            //$file_upload = $uploadurl.'banner2_'.$lang.'.'.$ext;
            $file_upload = $uploadurl.'banner2_'.$lang.'.jpg';
            $filename    = $uploaddir.'banner2_'.$lang.'.jpg';
            Helper::imageResize($uploaddir.$tempname, $filename, $breite, 0, 'png', true, true, false, 0, 500, false);
         }

         // FavIcons
         if ($tempname == 'favicon') {
            $file_upload = $uploadurl.'favicon-32x32.png';

            Helper::imageResize($uploaddir.$tempname, $uploaddir.'apple-icon-57x57.png', 57, 57, 'png', false, true, false, 57, 57, true);
            Helper::imageResize($uploaddir.$tempname, $uploaddir.'apple-icon-60x60.png', 60, 60, 'png', false, true, false, 60, 60, true);
            Helper::imageResize($uploaddir.$tempname, $uploaddir.'apple-icon-72x72.png', 72, 72, 'png', false, true, false, 72, 72, true);
            Helper::imageResize($uploaddir.$tempname, $uploaddir.'apple-icon-76x76.png', 76, 76, 'png', false, true, false, 76, 76, true);
            Helper::imageResize($uploaddir.$tempname, $uploaddir.'apple-icon-114x114.png', 114, 114, 'png', false, true, false, 114, 114, true);
            Helper::imageResize($uploaddir.$tempname, $uploaddir.'apple-icon-120x120.png', 120, 120, 'png', false, true, false, 120, 120, true);
            Helper::imageResize($uploaddir.$tempname, $uploaddir.'apple-icon-144x144.png', 144, 144, 'png', false, true, false, 144, 144, true);
            Helper::imageResize($uploaddir.$tempname, $uploaddir.'apple-icon-152x152.png', 152, 152, 'png', false, true, false, 152, 152, true);
            Helper::imageResize($uploaddir.$tempname, $uploaddir.'apple-icon-180x180.png', 180, 180, 'png', false, true, false, 180, 180, true);
            Helper::imageResize($uploaddir.$tempname, $uploaddir.'android-icon-192x192.png', 192, 192, 'png', false, true, false, 192, 192, true);
            Helper::imageResize($uploaddir.$tempname, $uploaddir.'favicon-32x32.png', 32, 32, 'png', false, true, false, 32, 32, true);
            Helper::imageResize($uploaddir.$tempname, $uploaddir.'favicon-96x96.png', 96, 96, 'png', false, true, false, 96, 96, true);
            Helper::imageResize($uploaddir.$tempname, $uploaddir.'favicon-16x16.png', 16, 16, 'png', false, true, false, 16, 16, true);
            Helper::imageResize($uploaddir.$tempname, $uploaddir.'ms-icon-144x144.png', 144, 144, 'png', false, true, false, 144, 144, true);
            Helper::imageResize($uploaddir.$tempname, $uploaddir.'tile-tiny.png', 70, 70, 'png', false, true, false, 70, 70, true);
            Helper::imageResize($uploaddir.$tempname, $uploaddir.'tile-square.png', 150, 150, 'png', false, true, false, 150, 150, true);
            Helper::imageResize($uploaddir.$tempname, $uploaddir.'tile-wide.png', 310, 70, 'png', false, true, false, 310, 70, true);
            Helper::imageResize($uploaddir.$tempname, $uploaddir.'tile-large.png', 310, 310, 'png', true, true, false, 310, 310, true);
         }

         if ($tempname == 'socialicon') {
            $file_upload = $uploadurl.'icon_'.$bild_nr.'.png';
            $filename    = $uploaddir.'icon_'.$bild_nr.'.png';

            // Bild normal
            Helper::imageResize($uploaddir.$tempname, TEMPLATE_PATH.'/images/icon_'.$bild_nr.'.png', 50, 50, 'png', true, true, false, 50, 50, false);

            // Bild S/W
            $im = imagecreatefrompng(TEMPLATE_PATH.'/images/icon_'.$bild_nr.'.png');

            if($im && imagefilter($im, IMG_FILTER_GRAYSCALE)) {
               imagepng($im, TEMPLATE_PATH.'/images/icon_'.$bild_nr.'_teilen.png');
               imagedestroy($im);
            }

            $this->db->query("UPDATE #__social SET image = 'icon_".$bild_nr."' WHERE id = $bild_nr");
         }

         @unlink($uploaddir.$tempname);

         exit(json_encode(['status' => 'ok', 'html' => $file_upload.'?'.time(), 'target' => 'img_src']));
      }

      else {
         echo '<script>window.top.window.alert("Datei wurde nicht erkannt. Dateigröße zu hoch?");</script>';
         return false;
      }

   }

   // http:// dem Link hinzufügen, falls notwendig
   // 30.05.2019
   protected function _checkLinks($link) {
      if ($link != '' && stristr($link, 'http://') === false && stristr($link, 'https://') === false) {
         $link = 'http://' . $link;
      }

      if ($link == 'http://') {
         $link = '';
      }

      return $link;
   }

   // Form, Input bei Eingabe unterbinden
   // 19.05.2019
   protected function _checkInput($input) {
      $back = str_replace(['<input', '<form'], '', $input);

      return $back;
   }

   private function _renewPdf($lang) {
      $pdf   = Control::getPdfWiderruf();

      $pdf->makePdf('versand',   $this->db->querySingleValue("SELECT `text` FROM #__seiten WHERE lang = '$lang' AND `art` = 'versand'"), $lang, 4);
      $pdf->makePdf('agb',       $this->db->querySingleValue("SELECT `text` FROM #__seiten WHERE lang = '$lang' AND `art` = 'agb'"), $lang, 4);
      $pdf->makePdf('WiderrufA', $this->db->querySingleValue("SELECT `text` FROM #__seiten WHERE lang = '$lang' AND `art` = 'widerruf1'"), $lang, 1);
      $pdf->makePdf('WiderrufB', $this->db->querySingleValue("SELECT `text` FROM #__seiten WHERE lang = '$lang' AND `art` = 'widerruf2'"), $lang, 2);
      $pdf->makePdf('WiderrufC', $this->db->querySingleValue("SELECT `text` FROM #__seiten WHERE lang = '$lang' AND `art` = 'widerruf3'"), $lang, 3);
      $pdf->makePdf('WiderrufD', $this->db->querySingleValue("SELECT `text` FROM #__seiten WHERE lang = '$lang' AND `art` = 'widerruf4'"), $lang, 4);
      $pdf->makePdf('WiderrufE', $this->db->querySingleValue("SELECT `text` FROM #__seiten WHERE lang = '$lang' AND `art` = 'widerruf5'"), $lang, 5);
   }
}
