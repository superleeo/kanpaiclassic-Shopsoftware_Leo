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

// Parameter aus Design übernehmen (DB / template.json - beides in $params->firma)

global $cat_left, $is_flaeche_header, $is_flaeche_mitte, $is_flaeche_liste, $is_flaeche_footer;
$content_padding     = 10;

if (isset($params->firma['content_padding'])) {
   $content_padding = $params->firma['content_padding'];
}

// Breite des Shops
$content_width       = CONF_BANNERBREITE;

if (isset($params->firma['max_width'])) {
   $content_width = $params->firma['max_width'];
}

// Breite des Shop + rand Li/re
$content_width_nopad = $content_width + 2 * $content_padding;

$menu_width           = WIDTH_MENU + 2 * $content_padding;
$menu_width_nopad     = WIDTH_MENU + $content_padding;
$logo_width           = $content_width_nopad;
$shop_width           = $content_width;
$shop_width_nopad     = $content_width_nopad;
$content_center       = $content_width;
$content_center_nopad = $content_width_nopad;

if ($cat_left) {
   $content_center       = $content_width + $menu_width_nopad;
   $content_center_nopad = $content_width_nopad + $menu_width_nopad;
}

$startseite = false;
$starthtml  = '';

// Restaurant pages use their own template, not the default start page
if ($params->task == '') {
   $startseite = true;
   $starthtml  = KANPAICLASSIC\Helper::getStartseite();
}

$is_zubehoer         = false;
$is_aehnliche        = false;
$is_lastseen         = false;
$zubehoer_text       = '';
$aehnliche_text      = '';
$lastseen_text       = '';
$module_unten        = false;


if ($is_aehnliche || $is_zubehoer || $is_lastseen) {
   $module_unten = true;
}
// Hintergrund und -Bild
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
   $background_repeat = 'repeat-x';
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

// Logo - zuerst .png, dann .jpg
$logo                = '';
$logo_h              = 0;
$logo_w              = 0;
$logolink            = SHOP_URL;
$logoseo             = $params->links['logoseo'];
$logointern = 'y';

// Logo (unter Header
if (file_exists(TEMPLATE_PATH.'/images/logo_'.$lang.'.png')) {
   $logo = TEMPLATE_URL.'/images/logo_'.$lang.'.png'.$params->firma['image_cache'];
   list($logo_w, $logo_h) = getimagesize(TEMPLATE_PATH.'/images/logo_'.$lang.'.png');
}

else if (file_exists(TEMPLATE_PATH.'/images/logo_'.$lang.'.jpg')) {
   $logo = TEMPLATE_URL.'/images/logo_'.$lang.'.jpg'.$params->firma['image_cache'];
   list($logo_w, $logo_h) = getimagesize(TEMPLATE_PATH.'/images/logo_'.$lang.'.jpg');
}

// Banner über Footer
$banner2_on          = false;

if (!isset($params->firma['bannerunten_on']) || $params->firma['bannerunten_on'] == 'y') {
   $banner2_on     = true;
   $banner2        = '';
   $banner2_h      = 0;
   $banner2_w      = 0;
   $banner2_link   = $params->links['bannerlink2'];
   $banner2_intern = ($params->links['banner2_intern'] == 'n' ? true : false);
   $bannerseo2     = $params->links['bannerseo2'];

   if (file_exists(TEMPLATE_PATH.'/images/banner2_'.$lang.'.jpg')) {
      $banner2 = TEMPLATE_URL.'/images/banner2_'.$lang.'.jpg'.$params->firma['image_cache'];
      list($banner2_w, $banner2_h) = getimagesize(TEMPLATE_PATH.'/images/banner2_'.$lang.'.jpg');

      if ($banner2_w > $content_width) {
         $faktor     = $content_width / $banner2_w;
         $banner2_h *= $faktor;
         $banner2_w *= $faktor;
      }
   }
}

// Daten für Artikelliste / Cubeportfolio
$thumb_width         = ((int)$params->firma['thumb_width'] > 0 ? (int)$params->firma['thumb_width'] : CONF_THUMB_X);
$thumb_height        = ((int)$params->firma['thumb_height'] > 0 ? (int)$params->firma['thumb_height'] : CONF_THUMB_Y);
$artikel_abstand_h   = 10;
$artikel_abstand_v   = 10;
$art_line_h          = 0;
$art_line_v          = 0;

$text_height         = 0;

// Artikelliste Symbole im Bild anzeigen
if ($params->firma['thumb_over_check'] != 'y') {
   $text_height =  53;
}

// Linien vertikales Menü
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

// Design/Slideshow auf Starteseite
// 0: keine Slideshow
// 1: Einzelnes Bild
// 2: Slideshow
$slideshow           = 0;
$slideshow_on        = ($params->firma['slideshow_on'] == 'y' ? true :false);
$slideshow_full      = ($params->firma['fullscreen_slide'] == 'y' ? true :false);
$slide_width         = 0;
$slide_height        = 0;
$slide_left          = 0;
$slide_pics          = [];
$slide_right         = false;
$slide_right_o       = false;
$slide_right_u       = false;

// Slideshow bei Startseite: 1 - Einzelbild; 2 - Slideshow
if ($startseite && $params->firma['slideshow_on'] == 'y') {
   // Test, ob rechte bilder vorhanden sind und
   if ($params->firma['slideshow_r_check'] == 'y' && !$slideshow_full) {
      $slide_right = true;

      if (file_exists(TEMPLATE_PATH.'/images/slide9_'.$lang.'.jpg')) {
         $slide_right_o = TEMPLATE_URL.'/images/slide9_'.$lang.'.jpg';
      }

      else {
         $slide_right_o = TEMPLATE_URL.'/images/system/slideshow_right_default.jpg';
      }

      if (file_exists(TEMPLATE_PATH.'/images/slide10_'.$lang.'.jpg')) {
         $slide_right_u = TEMPLATE_URL.'/images/slide10_'.$lang.'.jpg';
      }

      else {
         $slide_right_u = TEMPLATE_URL.'/images/system/slideshow_right_default.jpg';
      }
   }

   $bild_breite = '';   // slide1 -> bei bilder rechts, slide1w -> Slideshow, slide1l -> Slideshow bildschirmbreit

   if ($params->firma['fullscreen_slide'] == 'y') {
      // Bei Mobile keine fullscreen-Slideshow Bilder
      if ($device == 'mobile') {
         $bild_breite = 'w';
      }

      else {
         $bild_breite = 'l';
      }
   }

   // Slideshow normale Breite
   else if (!$slide_right) {
      $bild_breite = 'w';
   }

   // Bilder (Slide1 ... Slide8) in Array einlesen
   foreach (glob(TEMPLATE_PATH.'/images/slide*.jpg') as $file) {
      preg_match('|slide([\d])'.$bild_breite.'_'.$lang.'\.jpg|', $file, $pic);

      if (isset($pic[0]) && (int)$pic[1] < 9) {
         $slide_pics[] = [$pic[0], (int)$pic[1]];
      }
   }

  if (!empty($slide_pics)) {
      // Einzelbild
      if (count($slide_pics) == 1) {
         $slideshow = 1;
      }

      // Slideshow
      else {
         $slideshow = 2;
         sort($slide_pics);
      }

      $startbild_single = $slide_pics[0][0];
      list($slide_width, $slide_height) = getimagesize(TEMPLATE_PATH.'/images/'.$startbild_single);
      $slide_faktor = $content_width / $slide_width;
      $slide_width = floor($slide_width * $slide_faktor);
      $slide_height = floor($slide_height * $slide_faktor);

      if ($params->firma['fullscreen_slide'] != 'y') {
//         $slide_height = floor($slide_height * $slide_faktor);

         $slide_left = 0;

         if ($slide_right) {
            $slide_width  *= 0.66;
            $slide_height *= 0.66;
         }
      }
   }
}

// Design/Collage auf Starteite
$collage             = '';
if ($startseite && $params->firma['collage_on'] == 'y') {
   $nopic = TEMPLATE_URL.'/images/system/nopic.png';

   $coll = new \stdClass();

   for ($i = 1; $i < 9; $i++) {
      $coll->{'c'.$i} = new \stdClass();
      $coll->{'c'.$i}->lnk = $params->links['link'.$i];
      $coll->{'c'.$i}->int = $params->links['link'.$i.'_intern'];
      $coll->{'c'.$i}->seo = $params->links['link'.$i.'_seo'];
      $coll->{'c'.$i}->txt = $params->links['link'.$i.'_text'];
      $coll->{'c'.$i}->col = KANPAICLASSIC\Helper::hex2rgba($params->links['link'.$i.'_color_text'], $params->links['link'.$i.'_color_text_opc']);
      $coll->{'c'.$i}->bg  = KANPAICLASSIC\Helper::hex2rgba($params->links['link'.$i.'_color_bg'], $params->links['link'.$i.'_color_bg_opc']);
   }

   for ($i = 1; $i < 8; $i += 2) {
      if (is_file(TEMPLATE_PATH.'/images/bild'.$i.'_'.$lang.'.jpg') || is_file(TEMPLATE_PATH.'/images/bild'.($i + 1).'_'.$lang.'.jpg')) {
         $startbild_l = 'startbild_l';
         $startbild_r = 'startbild_r';

         if ($i == 5) {
            $startbild_l = 'startbild_lb';
            $startbild_r = 'startbild_rs';
         }

         if ($i == 7) {
            $startbild_l = 'startbild_ls';
            $startbild_r = 'startbild_rb';
         }
         // Bild links
         // link vorhanden
         if ($coll->{'c'.$i}->lnk != '') {
            $collage .= '<a class="'.$startbild_l.'" href="'.$coll->{'c'.$i}->lnk.'"'.($coll->{'c'.$i}->int == 'y' ? ' target="_top"' : ' target="_blank"').'>'.CR;
            $collage .= '   <img data-responsive="" src="'.(is_file(TEMPLATE_PATH.'/images/bild'.$i.'_'.$lang.'.jpg') ? TEMPLATE_URL.'/images/bild'.$i.'_'.$lang.'.jpg'.$params->firma['image_cache'] : $nopic).'" alt="'.$coll->{'c'.$i}->seo.'" title="'.$coll->{'c'.$i}->seo.'" name="'.$coll->{'c'.$i}->seo.'" />'.CR;

            if ($coll->{'c'.$i}->txt != '') {
               $collage .= '   <span class="collage_title ellipsis text_max fliesstext" style="color:'.$coll->{'c'.$i}->col.' !important; background-color:'.$coll->{'c'.$i}->bg.';">'.$coll->{'c'.$i}->txt.'</span>'.CR;
            }

            $collage .= '</a>'.CR;
         }

         // Kein Link vorhanden
         else {
            $collage .= '<span class="'.$startbild_l.'">'.CR;
            $collage .= '   <img data-responsive="" src="'.(is_file(TEMPLATE_PATH.'/images/bild'.$i.'_'.$lang.'.jpg') ? TEMPLATE_URL.'/images/bild'.$i.'_'.$lang.'.jpg'.$params->firma['image_cache'] : $nopic).'" alt="'.$coll->{'c'.$i}->seo.'" title="'.$coll->{'c'.$i}->seo.'" name="'.$coll->{'c'.$i}->seo.'" />'.CR;

            if ($coll->{'c'.$i}->txt != '') {
               $collage .= '   <span class="collage_title ellipsis text_max fliesstext" style="color:'.$coll->{'c'.$i}->col.' !important; background-color:'.$coll->{'c'.$i}->bg.';" >'.$coll->{'c'.$i}->txt.'</span>'.CR;
            }

            $collage .= '</span>'.CR;
         }

         // Bild rechts
         if ($coll->{'c'.($i + 1)}->lnk != '') {
            $collage .= '<a class="'.$startbild_r.'" href="'.$coll->{'c'.($i + 1)}->lnk.'"'.($coll->{'c'.($i + 1)}->int == 'y' ? ' target="_top"' : ' target="_blank"').'>'.CR;
            $collage .= '   <img data-responsive="" src="'.(is_file(TEMPLATE_PATH.'/images/bild'.($i + 1).'_'.$lang.'.jpg') ? TEMPLATE_URL.'/images/bild'.($i + 1).'_'.$lang.'.jpg'.$params->firma['image_cache'] : $nopic).'" alt="'.$coll->{'c'.($i + 1)}->seo.'" title="'.$coll->{'c'.($i + 1)}->seo.'" name="'.$coll->{'c'.($i + 1)}->seo.'" />'.CR;

            if ($coll->{'c'.($i + 1)}->txt != '') {
               $collage .= '   <span class="collage_title ellipsis text_max fliesstext" style="color:'.$coll->{'c'.($i + 1)}->col.' !important; background-color:'.$coll->{'c'.($i + 1)}->bg.';" >'.$coll->{'c'.($i + 1)}->txt.'</span>'.CR;
            }

            $collage .= '</a>'.CR;
         }

         else {
            $collage .= '<span class="'.$startbild_r.'">'.CR;
            $collage .= '   <img data-responsive="" src="'.(is_file(TEMPLATE_PATH.'/images/bild'.($i + 1).'_'.$lang.'.jpg') ? TEMPLATE_URL.'/images/bild'.($i + 1).'_'.$lang.'.jpg'.$params->firma['image_cache'] : $nopic).'" alt="'.$coll->{'c'.($i + 1)}->seo.'" title="'.$coll->{'c'.($i + 1)}->seo.'" name="'.$coll->{'c'.($i + 1)}->seo.'" />'.CR;

            if ($coll->{'c'.($i + 1)}->txt != '') {
               $collage .= '   <span class="collage_title ellipsis text_max fliesstext" style="color:'.$coll->{'c'.($i + 1)}->col.' !important; background-color:'.$coll->{'c'.($i + 1)}->bg.';" >'.$coll->{'c'.($i + 1)}->txt.'</span>'.CR;
            }

            $collage .= '</span>'.CR;
         }
      }
   }

   $collage .= '<div class="clear"></div>';
}

$module1             = '';

if ($startseite && is_file(SHOP_PATH.'/classes/modules/livedesigner/livedesigner.module.php')) {
   // require_once SHOP_PATH.'/classes/modules/livedesigner2/livedesigner2.module.php';
   require_once SHOP_PATH.'/classes/modules/livedesigner/livedesigner.module.php';
   $livedesignerX  = new \KANPAICLASSIC\KANPAICLASSIC_modulLivedesigner;
   $module1        = $livedesignerX->frontend($is_flaeche_mitte, (isset($livedesigner) ? true : false), $cat_left);
}

// Modul Extended
$slider_top          = false;
$accordion_top       = false;
$carussell_top       = false;

$slider_center       = false;
$accordion_center    = false;
$carussell_center    = false;

$slider_bottom       = false;
$accordion_bottom    = false;
$carussell_bottom    = false;

$slider_reload       = '';
$accordion_reload    = '';
$carussell_reload    = '';

$cross_slider        = false;
$art_slider          = false;
$slider_arr          = false;

$start__bild         = false;
$start_video         = false;
$start_video_reload  = false;

// Objekt Modul Extendet, wenn vorhanden
if ($isExtended && !defined('CONF_MODULE_LIVEDESIGNER2') && !defined('CONF_MODULE_LIVEDESIGNER_EXT')) {
//   $slider_reload     = ($isExtended->slider_reload != 'center' || $isExtended->slider_reload == 'center' && $startseite ? $isExtended->slider_reload : '');
//   $accordion_reload  = ($isExtended->accordion_reload != 'center' || $isExtended->accordion_reload == 'center' && $startseite ? $isExtended->accordion_reload : '');
//   $carussell_reload  = ($isExtended->carussell_reload != 'center' || $isExtended->carussell_reload == 'center' && $startseite ? $isExtended->carussell_reload : '');

   // Immer aktiv Top
   if($isExtended->slider_active && $isExtended->slider_pos == 'top') {
      $slider_top = true;
   }

   if ($isExtended->accordion_active && $isExtended->accordion_pos == 'top') {
      $accordion_top = true;
   }

   if ($isExtended->carussell_active && $isExtended->carussell_pos == 'top') {
      $carussell_top = true;
   }

   // nur auf Startseite aktiv / Mitte
   if ($startseite) {
      if ($isExtended->slider_active && $isExtended->slider_pos == 'center') {
         $slider_center = true;
      }

      if ($isExtended->accordion_active && $isExtended->accordion_pos == 'center') {
         $accordion_center = true;
      }

      if ($isExtended->carussell_active && $isExtended->carussell_pos == 'center') {
         $carussell_center = true;
      }
   }

   // immer aktiv Footer
   if($isExtended->slider_active && $isExtended->slider_pos == 'bottom') {
      $slider_bottom = true;
   }

   if ($isExtended->accordion_active && $isExtended->accordion_pos == 'bottom') {
      $accordion_bottom = true;
   }

   if ($isExtended->carussell_active && $isExtended->carussell_pos == 'bottom') {
      $carussell_bottom = true;
   }
}

// Modul Artike-Slider
if ($params->task == 'artikel' && defined('CONF_MODULE_CROSSPROMO')) {
   $art_slider    = KANPAICLASSIC\Control::getModuleCrosspromo();
   $slider_arr    = $art_slider->getSliderFe($params->parent_id, $lang);

   if ($slider_arr[0] != '') {
      $cross_slider  = true;
      $slider_top    = false;
      $slider_center = false;
      $slider_bottom = false;
   }
}

// Marker, wo Extended aktiv
$extended_top        = $slider_top || $accordion_top || $carussell_top;
$extended_middle     = $startseite && ($collage != '' || $slider_center || $accordion_center || $carussell_center || $starthtml != '');
$extended_bottom     = $carussell_bottom || $accordion_bottom || $slider_bottom || $cross_slider;

// Startbild/ -Video
if (defined('CONF_MODULE_EXTENDED') && $params->task == '' && !$params->set_offset) {
   if ($device == 'desktop' && is_file(TEMPLATE_PATH.'/images/startbild_video_'.$lang.'.jpg')) {
      $start__bild     = true;
      $startbild_video = TEMPLATE_URL.'/images/startbild_video_'.$lang.'.jpg';
   }

   else if ($device != 'desktop' && is_file(TEMPLATE_PATH.'/images/startbild_videos_'.$lang.'.jpg')) {
      $start__bild     = true;
      $startbild_video = TEMPLATE_URL.'/images/startbild_videos_'.$lang.'.jpg';

      if ($device == '') {
         $start_video_reload = true;
      }
   }

   else if (is_file(TEMPLATE_PATH.'/images/startbild_video_'.$lang.'.mp4')) {
      $start_video     = true;
      $startbild_video = TEMPLATE_URL.'/images/startbild_video_'.$lang.'.mp4';
   }

   else if (is_file(TEMPLATE_PATH.'/images/startbild_video_'.$lang.'.webm')) {
      $start_video     = true;
      $startbild_video = TEMPLATE_URL.'/images/startbild_video_'.$lang.'.webm';
   }

   else if (is_file(TEMPLATE_PATH.'/images/startbild_video_'.$lang.'.mov')) {
      $start_video     = true;
      $startbild_video = TEMPLATE_URL.'/images/startbild_video_'.$lang.'.mov';
   }
}

// Module Zubehör, Ähnliche, Zulezt gesehen
$zubehoer            = [];
$aehnliche           = [];
$lastseen            = [];

// Modul Zubehör
if (defined('CONF_MODULE_ZUBEHOER') && $params->task == 'artikel' && (int)$articles->parent_id > 0) {
   $anzahl   = $articles->loadArticlesZubehoer($articles->parent_id);

   if ($anzahl > 0) {
      $articles->render(0, $zubehoer, true, false, false, 'zubehoer_container');
      $is_zubehoer = true;

      $zubehoer_text = $articles->getZubehoerTitle($articles->parent_id);
      $zubehoer_text == '' && $zubehoer_text = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
   }
}

// Modul Ähnliche Artiekel
if (defined('CONF_MODULE_AEHNLICHE') && $params->task == 'artikel' && (int)$articles->parent_id > 0) {
   $anzahl   = $articles->loadArticlesAehnliche($articles->parent_id);

   if ($anzahl > 0) {
      $articles->render(0, $aehnliche, true, false, false, 'aehnliche_container');
      $is_aehnliche = true;

      $aehnliche_text = $articles->getAehnlicheTitle($articles->parent_id);
      $aehnliche_text = ($aehnliche_text != '' ? $aehnliche_text : '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
   }
}
// Zulezt gesehen -> Wird nur bei template_normal.php ausgegeben / nicht bei Mixer-Kategorien
if (!$mixer /*&& $params->task != ''*/ && defined('CONF_MODULE_ZUBEHOER') && $params->firma['letzte'] == 'y') {
   
   $anzahl = $articles->loadArticlesLastseen($params->parent_id);

   if ($anzahl > 0) {
      $articles->render(0, $lastseen, true, false, false, 'lastseen_container');
      $is_lastseen = true;

      $lastseen_text = $text->get('article', 'lastseen');
      $lastseen_text == '' && $aehnliche_text = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
   }
}

// Artikelcounter bei Kategorien und (bedingt) Startseite
//$is_counter          = ($params->firma['artikelliste_on'] == 'y' && ($params->task == 'kategorie' || $startseite && $params->firma['startseite_artikel'] == 'artikel') ? true : false);
$is_counter          =  ($params->task == 'kategorie' || $params->firma['artikelliste_on'] == 'y' &&  $startseite && $params->firma['startseite_artikel'] == 'artikel') ? true : false;

// Telefon anzeigen
$is_call_check       = ($params->firma['call_check'] == 'y' ? true : false);

// Für boxen.php
$boxscript           = '';
$startseite_box      = CONF_BANNERBREITE;

if ($params->firma['startseite_breite'] == 'kategorien') {
   $startseite_box -= (CONF_THUMB_X + 3 * 18);
}

// Google-Fonts einbinden
$fonts               = [];
$font_url            = SHOP_URL.'/fonts';
$fonts_css = '';
require SHOP_PATH.'/classes/base/googlefonts.inc.php';

// is_numeric wegen Fehlermeldungen bei Umstellung
$fonts[]             = (is_numeric($params->firma['fontfamily1']) ? $googlefonts[$params->firma['fontfamily1']] : ['', 400, 'Arial', '', '']);
$fonts[]             = (is_numeric($params->firma['fontfamily2']) ? $googlefonts[$params->firma['fontfamily2']] : ['', 400, 'Arial', '', '']);
$fonts[]             = (is_numeric($params->firma['fontfamily3']) ? $googlefonts[$params->firma['fontfamily3']] : ['', 400, 'Arial', '', '']);
$fonts[]             = (is_numeric($params->firma['fontfamily4']) ? $googlefonts[$params->firma['fontfamily4']] : ['', 400, 'Arial', '', '']);

// String für Google-Fonts bauen
foreach ($fonts as $font) {
   if (isset($font[5]) && $font[5] != '') {
      $fonts_css .= $font[5].CR;
   }
}

$fontsize1           = 22;
if (isset($params->firma['fontsize1'])) {
   $fontsize1 = $params->firma['fontsize1'];
}

$fontsize2           = 18;
if (isset($params->firma['fontsize2'])) {
   $fontsize2 = $params->firma['fontsize2'];
}

$fontsize3           = 14;
if (isset($params->firma['fontsize3'])) {
   $fontsize3 = $params->firma['fontsize3'];
}

$fontsize4           = 12;
if (isset($params->firma['fontsize4'])) {
   $fontsize4 = $params->firma['fontsize4'];
}
