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

require_once ADMIN_PATH.'/classes/design.class.php';

class KANPAICLASSIC_designColors extends KANPAICLASSIC_design
{

   public  $css       = [];
   private $colors    = [];
   private $colors_bg = [];

   private $zeichen_main = '';
   private $zeichen_sub = '';

   function __construct() {
      parent::__construct();

      $this->_defaultColors();
   }

   public function getContent() {
      // Save - CSS-Colors speichern, Fonts speichern
      if ($this->params->func == 'save') {
         $this->saveCss();
         $this->saveJsonFonts();
         $this->saveZeichen();

         header("Location: ".ADMIN_URL_IDX.'/designColors');
         exit;
      }

      // Seite anzeigen
//      $this->_readCss();
      $this->loadCss();
      $this->loadJson();
      $this->_readZeichen();

      include 'templates/designColors.tpl.php';
      return;
   }

   // Array mit Flächen / Schriftfarben erstellen
   // 16.05.2019
   private function _defaultColors() {
      // Key ist CSS-Klasse, name ist Anzeigename.
      $this->css['menuleiste']      = ['sort' => '01', 'name' => 'menuleiste',     'val' => 'ffffff', 'typ' => 'background-color', 'opacity_check' => 'y', 'opacity' => 1.0];
      $this->css['bg_header']       = ['sort' => '02', 'name' => 'bg_header',      'val' => 'ffffff', 'typ' => 'background-color', 'opacity_check' => 'y', 'opacity' => 1.0];
      $this->css['horiz_kat']       = ['sort' => '03', 'name' => 'horiz_kat',      'val' => 'f6f6f6', 'typ' => 'background-color', 'opacity_check' => 'y', 'opacity' => 1.0];
      $this->css['horiz_aktiv']     = ['sort' => '04', 'name' => 'horiz_aktiv',    'val' => 'dddddd', 'typ' => 'background-color', 'opacity_check' => 'y', 'opacity' => 1.0];
      $this->css['vertikal_kat']    = ['sort' => '05', 'name' => 'vertikal_kat',   'val' => 'ffffff', 'typ' => 'background-color', 'opacity_check' => 'y', 'opacity' => 1.0];
      $this->css['unter_kat']       = ['sort' => '06', 'name' => 'unter_kat',      'val' => 'eeeeee', 'typ' => 'background-color', 'opacity_check' => 'y', 'opacity' => 1.0];
      $this->css['over_kat']        = ['sort' => '07', 'name' => 'over_kat',       'val' => 'dddddd', 'typ' => 'background-color', 'opacity_check' => 'y', 'opacity' => 1.0];
      $this->css['bg_aussen']       = ['sort' => '08', 'name' => 'bg_aussen',      'val' => 'eeeeee', 'typ' => 'background-color', 'opacity_check' => 'y', 'opacity' => 1.0];
      $this->css['bg_innen']        = ['sort' => '09', 'name' => 'bg_innen',       'val' => 'ffffff', 'typ' => 'background-color', 'opacity_check' => 'y', 'opacity' => 1.0];
      $this->css['bg_flaechen']     = ['sort' => '10', 'name' => 'bg_flaechen',    'val' => 'f6f6f6', 'typ' => 'background-color', 'opacity_check' => 'y', 'opacity' => 1.0];
      $this->css['bg_button']       = ['sort' => '11', 'name' => 'bg_button',      'val' => '222222', 'typ' => 'background-color', 'opacity_check' => 'n', 'opacity' => 1.0];
      $this->css['button_ovr']      = ['sort' => '12', 'name' => 'ovr_button',     'val' => '882222', 'typ' => 'background-color', 'opacity_check' => 'n', 'opacity' => 1.0];
      $this->css['bg_footer']       = ['sort' => '13', 'name' => 'bg_footer',      'val' => 'eeeeee', 'typ' => 'background-color', 'opacity_check' => 'y', 'opacity' => 1.0];
      $this->css['leer1']           = ['sort' => '00', 'name' => '',               'val' => '',       'typ' => 'background-color'];
      // Teil 2
      $this->css['bg_artikelbild']  = ['sort' => '14', 'name' => 'bg_artikelbild', 'val' => 'ffffff', 'typ' => 'background-color', 'opacity_check' => 'n', 'opacity' => 1.0];
      $this->css['bg_preise']       = ['sort' => '15', 'name' => 'bg_preise',      'val' => 'f6f6f6', 'typ' => 'background-color', 'opacity_check' => 'y', 'opacity' => 1.0];
      $this->css['leer2']           = ['sort' => '00', 'name' => '',               'val' => '',       'typ' => 'background-color'];


      $this->css['menu_oben']       = ['sort' => '16', 'name' => 'menu_oben',      'val' => '555555', 'typ' => 'color'];
      $this->css['over_oben']       = ['sort' => '17', 'name' => 'over_oben',      'val' => '000000', 'typ' => 'color'];
      $this->css['horiz_kat_c']     = ['sort' => '18', 'name' => 'horiz_kat',      'val' => '555555', 'typ' => 'color'];
      $this->css['horiz_kat_c_ovr'] = ['sort' => '19', 'name' => 'horiz_aktiv',    'val' => '333333', 'typ' => 'color'];
      $this->css['haupt_kat_c']     = ['sort' => '20', 'name' => 'vertikal_kat',   'val' => '333333', 'typ' => 'color'];
      $this->css['leer3']           = ['sort' => '00', 'name' => '',               'val' => '',       'typ' => 'color'];
      $this->css['haupt_kat_c_ovr'] = ['sort' => '21', 'name' => 'over_kat',       'val' => '333333', 'typ' => 'color'];
      $this->css['ueberschrift']    = ['sort' => '22', 'name' => 'ueberschrift',   'val' => '555555', 'typ' => 'color'];
      $this->css['text_bold']       = ['sort' => '23', 'name' => 'text_bold',      'val' => '555555', 'typ' => 'color'];
      $this->css['fliesstext']      = ['sort' => '24', 'name' => 'fliesstext',     'val' => '555555', 'typ' => 'color'];
      $this->css['text_formular']   = ['sort' => '25', 'name' => 'text_formular',  'val' => '555555', 'typ' => 'color'];
      $this->css['col_button']      = ['sort' => '26', 'name' => 'button',         'val' => 'ffffff', 'typ' => 'color'];
      $this->css['menu_unten']      = ['sort' => '27', 'name' => 'menu_unten',     'val' => '555555', 'typ' => 'color'];
      $this->css['over_unten']      = ['sort' => '28', 'name' => 'over_unten',     'val' => '333333', 'typ' => 'color'];
      // Teil 2
      $this->css['artikelname']     = ['sort' => '29', 'name' => 'artikelname',    'val' => '555555', 'typ' => 'color'];
      $this->css['info']            = ['sort' => '30', 'name' => 'Info',           'val' => '999999', 'typ' => 'color'];
      $this->css['angebot']         = ['sort' => '31', 'name' => 'angebot',        'val' => '555555', 'typ' => 'color'];
   }

   // CSS-Farben aus Datei auslesen
//   public function _readCss() {
   public function loadCss() {
      if (!file_exists(TEMPLATE_PATH.'/css/colors.css')) {
         // In color und bg_color aufteilen
         foreach ($this->css as $key => $value) {
            if ($value['typ'] == 'color') {
               $this->colors[] = ['css_name' => $key];
            }

            else {
               $this->colors_bg[] = ['css_name' => $key];
            }
         }

         return;
      }

      // wenn Datei vorhanden, auslesen und in $this->css ändern
      $mycss = file_get_contents(TEMPLATE_PATH.'/css/colors.css');

      foreach ($this->css as $key => $value) {
         $match = '';

         // RGBA-Werte color = (rgba(rrr, ggg, bbb, ttt)
         preg_match("/$key\s\{\s.*?:\s*?rgba\((.*?),\s*?(.*?),\s*?(.*?),\s*?(.*?)\).*?\}/i", $mycss, $match);

         if (isset($match[4])) {
            $this->css[$key]['val']     = ((int)$match[1] < 16 ? '0'.dechex($match[1]) : dechex($match[1])) . ((int)$match[2] < 16 ? '0'.dechex($match[2]) : dechex($match[2])) . ((int)$match[3] < 16 ? '0'.dechex($match[3]) : dechex($match[3]));
            $this->css[$key]['opacity'] = $match[4];
         }

         // RGB-Werte color = #rrggbb
         else {
            preg_match("/$key\s*?\{\s.*?:\s*?#(.*?);.*?\}/is", $mycss, $match);

            if (isset($match[1])) {
               $this->css[$key]['val'] = $match[1];

               // Obacity bei bg_color
               if ($this->css[$key]['typ'] == 'background-color' && $this->css[$key]['opacity_check'] == 'y') {
                  preg_match("/$key\s*\{.*?opacity:\s*(.*?);/is", $mycss, $match);

                  if (isset($match[1])) {
                     $this->css[$key]['opacity'] = $match[1];
                  }
               }
            }
         }

         // In color und bg_color aufteilen
         if ($value['typ'] == 'color') {
            $this->colors[] = ['css_name' => $key];
         }

         else {
            $this->colors_bg[] = ['css_name' => $key];
         }
      }
   }

   // CSS-Datei (Farben) speichern
   public function saveCss($livedesigner = false) {
      $file = '';

      if (!$livedesigner) {
         // Parameter in Array einlesen
         foreach ($this->css as $key => $value) {
            // Leere Einträge überspringen
            if (strstr($key, 'leer') !== false) {
               continue;
            }

            $this->css[$key]['val'] = $this->params->postString($key);

            if ($this->css[$key]['typ'] == 'background-color' && $this->css[$key]['opacity_check'] == 'y') {
               // Opacity setzen
               $this->css[$key]['opacity'] = $this->params->postString($key.'_opacity');
            }
         }
      }

      else {
         foreach ($this->css as $key => $value) {
            $this->css[$key]['val'] = '#'.$this->css[$key]['val'];
         }
      }

      foreach ($this->css as $key => $value) {
         // Leere Einträge überspringen
         if (strstr($key, 'leer') !== false) {
            continue;
         }

         // Werte aus Übergabeparameter
         if ($value['typ'] == 'background-color' && $value['opacity_check'] == 'y') {
            // Opacity berücksichtigen -> color -> rgb
            $r = hexdec(substr($value['val'], 1, 2));
            $g = hexdec(substr($value['val'], 3, 2));
            $b = hexdec(substr($value['val'], 5, 2));

            $file .= '.'.$key.' { '.$value['typ'].':rgba('.$r.','.$g.','.$b.','.$value['opacity'].'); }'."\n";
         }

         else {
            $file .= '.'.$key.' { '.$value['typ'].':'.$value['val'].'; }'."\n";
         }

         // Zusätzliche Werte
         if ($key == 'menu_oben') {
            $file .= '.menu_oben a { '.$value['typ'].':'.$value['val'].'; }'."\n";
         }

         if ($key == 'over_oben') {
            $file .= '.menu_oben:hover { '.$value['typ'].':'.$value['val'].'; }'."\n";
            $file .= '.menu_oben:hover span { '.$value['typ'].':'.$value['val'].'; }'."\n";
            $file .= '#menu2 a:hover { '.$value['typ'].':'.$value['val'].'; }'."\n";
            $file .= '#menu2 span:hover { '.$value['typ'].':'.$value['val'].'; }'."\n";
         }

         if ($key == 'menu_unten') {
            $file .= '.menu_unten a { '.$value['typ'].':'.$value['val'].'; }'."\n";
            $file .= '.menu_unten_text { '.$value['typ'].':'.$value['val'].'; }'."\n";
         }

         if ($key == 'over_unten') {
            $file .= '.menu_unten:hover { '.$value['typ'].':'.$value['val'].'; }'."\n";
            $file .= '#menu3 a:hover { '.$value['typ'].':'.$value['val'].'; }'."\n";
         }

         // Hintergrund Responsive-Menü
         if ($key == 'unter_kat') {
            $file .= '.bg_responsive { '.$value['typ'].':'.$value['val'].'; background-image:linear-gradient('.$this->_color3d($value['val']).' 0%, '.$value['val'].' 100%);}'."\n";
         }

         // horiz_aktiv auf !important
         if ($key == 'horiz_aktiv') {
            $file .= '.horiz_aktiv { '.$value['typ'].':'.$value['val'].' !important; }'.CR;
         }

         // Kategorien Background
         if ($key == 'over_kat') {
            $r = hexdec(substr($value['val'], 1, 2));
            $g = hexdec(substr($value['val'], 3, 2));
            $b = hexdec(substr($value['val'], 5, 2));

            $file .= '.vertikal_kat:hover { '.$value['typ'].':rgba('.$r.','.$g.','.$b.','.$value['opacity'].') !important; }'."\n";
            //$file .= '#kat_links .selected { '.$value['typ'].':rgba('.$r.','.$g.','.$b.','.$value['opacity'].') !important; }'."\n";
            $file .= '#kat_links .current { '.$value['typ'].':rgba('.$r.','.$g.','.$b.','.$value['opacity'].') !important; }'."\n";
            $file .= '.horiz_kat:hover { '.$value['typ'].':rgba('.$r.','.$g.','.$b.','.$value['opacity'].') !important; }'."\n";
            $file .= '.unter_kat:hover { '.$value['typ'].':rgba('.$r.','.$g.','.$b.','.$value['opacity'].') !important; }'."\n";
            $file .= '.kat_active { '.$value['typ'].':rgba('.$r.','.$g.','.$b.','.$value['opacity'].') !important; }'."\n";
            $file .= '.sub_kat:hover { '.$value['typ'].':rgba('.$r.','.$g.','.$b.','.$value['opacity'].') !important; }'."\n";
         }

         // Hintergrund Sub-Kategoien fullscreen
         if ($key == 'horiz_kat') {
            if ($value['typ'] == 'background-color' && $value['opacity_check'] == 'y') {
               // Opacity berücksichtigen
               $r = hexdec(substr($value['val'], 1, 2));
               $g = hexdec(substr($value['val'], 3, 2));
               $b = hexdec(substr($value['val'], 5, 2));

               $file .= '.bg_horiz_kat { '.$value['typ'].':rgba('.$r.','.$g.','.$b.','.$value['opacity'].'); }'."\n";
               $file .= '.tooltip.top .tooltip-arrow { border-top-color:rgba('.$r.','.$g.','.$b.','.$value['opacity'].') !important; }'."\n";
               $file .= '.tooltip-inner { background-color:rgba('.$r.','.$g.','.$b.','.$value['opacity'].') !important; }'."\n";
            }
            else {
               $file .= '.bg_horiz_kat { '.$value['typ'].':'.$value['val'].'; }'."\n";
            }
         }

         if ($key == 'bg_innen') {
            $file .= '.bg_innen_no_trans { '.$value['typ'].':'.$this->_color3d($value['val']).'; }'."\n";
         }

         if ($key == 'bg_flaechen') {
            $r = hexdec(substr($value['val'], 1, 2));
            $g = hexdec(substr($value['val'], 3, 2));
            $b = hexdec(substr($value['val'], 5, 2));

            $file .= '.bg_button_only_hover:hover { '.$value['typ'].':'.$this->_color3dr($value['val']).'; }'."\n";
            $file .= '.bg_flaechen .text_toggle_line.more { background-image: linear-gradient(rgba('.$r.','.$g.','.$b.', 0) 0, rgba('.$r.','.$g.','.$b.','.$value['opacity'].') 50%, rgba('.$r.','.$g.','.$b.','.$value['opacity'].') 100%); }'."\n";
         }

         if ($key == 'bg_footer') {
            $r = hexdec(substr($value['val'], 1, 2));
            $g = hexdec(substr($value['val'], 3, 2));
            $b = hexdec(substr($value['val'], 5, 2));

            $file .= '.bg_footer .text_toggle_line.more { background-image: linear-gradient(rgba('.$r.','.$g.','.$b.', 0) 0, rgba('.$r.','.$g.','.$b.','.$value['opacity'].') 50%, rgba('.$r.','.$g.','.$b.','.$value['opacity'].') 100%); }'."\n";
         }

         if ($key == 'bg_button') {
            $file .= '.bg_button_no { '.$value['typ'].':'.$value['val'].'; }'."\n";
            $file .= '.wert_selected { background-color:'.$value['val'].' !important; }'."\n";
            $file .= '#detail_info .is_selected { background-color:'.$value['val'].' !important; }'."\n";
         }

         if ($key == 'button_ovr') {
            $file .= '.bg_button:hover { '.$value['typ'].':'.$value['val'].'; }'."\n";
         }

         // Kategorien Farben
         if ($key == 'horiz_kat_c') {
            $file .= '.horiz_kat_no_over { color:'.$value['val'].'}'."\n";
            $file .= '.tooltip-inner { color:'.$value['val'].' !important; font-weight:bold;}'."\n";
         }

         if ($key == 'horiz_kat_c_ovr') {
            $file .= '.horiz_aktiv { '.$value['typ'].':'.$value['val'].' !important; }'."\n";
            $file .= '.horiz_kat_c.active { '.$value['typ'].':'.$value['val'].' !important; }'."\n";
            $file .= '.horiz_kat_c.selected { '.$value['typ'].':'.$value['val'].' !important; }'."\n";
         }

         if ($key == 'haupt_kat_c') {
            $file .= '.unter_kat_c { '.$value['typ'].':'.$value['val'].' !important; }'."\n";
         }

         if ($key == 'haupt_kat_c_ovr') {
            $file .= 'div.horiz_kat_c:hover { '.$value['typ'].':'.$value['val'].' !important; }'."\n";
            $file .= 'div.haupt_kat_c:hover { '.$value['typ'].':'.$value['val'].' !important; }'."\n";
            $file .= 'em.haupt_kat_c:hover { '.$value['typ'].':'.$value['val'].' !important; }'."\n";
            $file .= 'div.unter_kat_c:hover { '.$value['typ'].':'.$value['val'].' !important; }'."\n";
            $file .= '#kat_links .current { '.$value['typ'].':'.$value['val'].' !important; }'."\n";
         }

         // Sonstiges
         if ($key == 'fliesstext') {
            $file .= 'a { color:'.$value['val'].'; text-decoration:none; }'."\n";
            $file .= 'hr.line_top { border-top-color:'.$value['val'].' !important; }'."\n";
            $file .= 'hr.line_bottom { border-top-color:'.$value['val'].' !important; }'."\n";
            $file .= '.fliesstext_livedesigner { color:'.$value['val'].' !important; }'."\n";
         }
      }

      file_put_contents(TEMPLATE_PATH.'/css/colors.css', $file);
      $this->cssBackup();
   }

   private function cssBackup() {
      if (!is_dir(TEMPLATE_PATH.'/save')) {
         mkdir(TEMPLATE_PATH.'/save');
      }

      if (file_exists(TEMPLATE_PATH.'/save/colors.css_9')) {
         @unlink(TEMPLATE_PATH.'/save/colors.css_9');
      }

      if (file_exists(TEMPLATE_PATH.'/save/colors.css_8')) {
         rename(TEMPLATE_PATH.'/save/colors.css_8', TEMPLATE_PATH.'/save/colors.css_9');
      }

      if (file_exists(TEMPLATE_PATH.'/save/colors.css_7')) {
         rename(TEMPLATE_PATH.'/save/colors.css_7', TEMPLATE_PATH.'/save/colors.css_8');
      }

      if (file_exists(TEMPLATE_PATH.'/save/colors.css_6')) {
         rename(TEMPLATE_PATH.'/save/colors.css_6', TEMPLATE_PATH.'/save/colors.css_7');
      }

      if (file_exists(TEMPLATE_PATH.'/save/colors.css_5')) {
         rename(TEMPLATE_PATH.'/save/colors.css_5', TEMPLATE_PATH.'/save/colors.css_6');
      }

      if (file_exists(TEMPLATE_PATH.'/save/colors.css_4')) {
         rename(TEMPLATE_PATH.'/save/colors.css_4', TEMPLATE_PATH.'/save/colors.css_5');
      }

      if (file_exists(TEMPLATE_PATH.'/save/colors.css_3')) {
         rename(TEMPLATE_PATH.'/save/colors.css_3', TEMPLATE_PATH.'/save/colors.css_4');
      }

      if (file_exists(TEMPLATE_PATH.'/save/colors.css_2')) {
         rename(TEMPLATE_PATH.'/save/colors.css_2', TEMPLATE_PATH.'/save/colors.css_3');
      }

      if (file_exists(TEMPLATE_PATH.'/save/colors.css_1')) {
         rename(TEMPLATE_PATH.'/save/colors.css_1', TEMPLATE_PATH.'/save/colors.css_2');
      }

      if (file_exists(TEMPLATE_PATH.'/css/colors.css')) {
         copy(TEMPLATE_PATH.'/css/colors.css', TEMPLATE_PATH.'/save/colors.css_1');
      }
   }

   private function _color3d($color) {
      $r = hexdec(substr($color, 1, 2)) + 34;
      if ($r > 255) {
         $r = 255;
      }

      $g = hexdec(substr($color, 3, 2)) + 34;
      if ($g > 255) {
         $g = 255;
      }

      $b = hexdec(substr($color, 5, 2)) + 34;
      if ($b > 255) {
         $b = 255;
      }
      $new_color = str_pad(dechex($r), 2, "0", STR_PAD_LEFT) . str_pad(dechex($g), 2, "0", STR_PAD_LEFT) . str_pad(dechex($b), 2, "0", STR_PAD_LEFT);

      return '#'.$new_color;
   }

   private function _color3dr($color) {
      $r = hexdec(substr($color, 1, 2)) - 34;
      if ($r < 0) {
         $r = 0;
      }

      $g = hexdec(substr($color, 3, 2)) - 34;
      if ($g < 0) {
         $g = 0;
      }

      $b = hexdec(substr($color, 5, 2)) - 34;
      if ($b < 0) {
         $b = 0;
      }
      $new_color = str_pad(dechex($r), 2, "0", STR_PAD_LEFT) . str_pad(dechex($g), 2, "0", STR_PAD_LEFT) . str_pad(dechex($b), 2, "0", STR_PAD_LEFT);

      return '#'.$new_color;
   }

   // CSS zum Einbinden aller Googlefonts (lokale Dateien)
   private function _getFontCSS() {
      $css = '';
      $font_url = SHOP_URL.'/fonts';
      require SHOP_PATH.'/classes/base/googlefonts.inc.php';

      foreach($googlefonts as $key => $font) {
         if ($font[5] != '') {
            $css  .= $font[5].CR;
         }
      }

      return $css;
   }

   public function _getFontfamily($search_font) {
      $html       = '';
      $googlesort = [];
      $g_sort     = [];
      $font_url   = SHOP_URL.'/fonts';

      require SHOP_PATH.'/classes/base/googlefonts.inc.php';

      foreach($googlefonts as $k => $g) {
         $googlesort[$g[0]] = $k;
      }

      ksort($googlesort);

      foreach ($googlesort as $k => $g) {
         $g_sort[$g] = $googlefonts[$g];
      }

      // Alte Version übernehmen / Fehlermeldungen verhindern


      if ($search_font == 'Arial') {
         $search_font = 100;
      }
      else if ($search_font == 'Open Sans') {
         $search_font = 200;
      }
      else if ($search_font == 'Poiret One') {
         $search_font = 300;
      }
      else if ($search_font == 'Abel') {
         $search_font = 600;
      }
      else if ($search_font == 'Handlee') {
         $search_font = 1600;
      }
      else if ($search_font == 'Bad Script') {
         $search_font = 1800;
      }
      else if ($search_font == 'Raleway') {
         $search_font = 1900;
      }

//      foreach($googlefonts as $key => $font) {
      foreach($g_sort as $key => $font) {
         $html  .= '<option value="'.$key.'" data-fontweight="'.$font[1].'" data-fontfamily="'.$font[2].'"'.($key == $search_font ? ' selected="selected"' : '').'>'.$font[0].'</option>'.CR;
      }

      return $html;
   }

   public function _getFontsize($fontsize) {
      $html  = '<option value="10"'.($fontsize == 10 ? ' selected="selected"' : '').'>10px</option>'.CR;
      $html .= '<option value="11"'.($fontsize == 11 ? ' selected="selected"' : '').'>11px</option>'.CR;
      $html .= '<option value="12"'.($fontsize == 12 ? ' selected="selected"' : '').'>12px</option>'.CR;
      $html .= '<option value="13"'.($fontsize == 13 ? ' selected="selected"' : '').'>13px</option>'.CR;
      $html .= '<option value="14"'.($fontsize == 14 ? ' selected="selected"' : '').'>14px</option>'.CR;
      $html .= '<option value="15"'.($fontsize == 15 ? ' selected="selected"' : '').'>15px</option>'.CR;
      $html .= '<option value="16"'.($fontsize == 16 ? ' selected="selected"' : '').'>16px</option>'.CR;
      $html .= '<option value="18"'.($fontsize == 18 ? ' selected="selected"' : '').'>18px</option>'.CR;
      $html .= '<option value="20"'.($fontsize == 20 ? ' selected="selected"' : '').'>20px</option>'.CR;
      $html .= '<option value="22"'.($fontsize == 22 ? ' selected="selected"' : '').'>22px</option>'.CR;
      $html .= '<option value="24"'.($fontsize == 24 ? ' selected="selected"' : '').'>24px</option>'.CR;
      $html .= '<option value="26"'.($fontsize == 26 ? ' selected="selected"' : '').'>26px</option>'.CR;
      $html .= '<option value="28"'.($fontsize == 28 ? ' selected="selected"' : '').'>28px</option>'.CR;
      $html .= '<option value="30"'.($fontsize == 30 ? ' selected="selected"' : '').'>30px</option>'.CR;      
      return $html;
   }

   private function _readZeichen() {
      $this->zeichen_main = $this->params->firma['zeichen_main'];
      $this->zeichen_sub  = $this->params->firma['zeichen_sub'];
   }

   private function saveZeichen() {
      $zeichen_main = $this->params->postInt('zeichen_main');
      $zeichen_sub  = $this->params->postInt('zeichen_sub');

      $this->db->query("UPDATE #__firma SET zeichen_main = $zeichen_main, zeichen_sub = $zeichen_sub WHERE id = 1");

      foreach(glob(SHOP_PATH.'/tmp/cat_cache*') as $f) {
         unlink($f);
      }
   }
}
