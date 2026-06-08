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
class KANPAICLASSIC_modulExtended
{
   private $db;
   private $params;
   private $lang = 'deu';

   function __construct() {
      $this->db = Control::getDB();
      $this->params = Control::getParams();
      $this->text = Control::getText();
      $this->lang = $this->params->selected_lang;
      $this->_checkDatabase();
   }

   public function getContent($module = '') {
      if ($module != '') {
         if ($module == 'accordion') {
            $livedesigner = true;
            $accordion    = null;
            $this->getAccordion($accordion);

            ob_start();
            include '../classes/modules/extended/extended_accordion.tpl.php';
            $html = ob_get_contents();
            ob_clean();

            return $html;
         }

         if ($module == 'carussell') {
            $livedesigner = true;
            $carussell    = null;
            $this->getCarussell($carussell);

            ob_start();
            include '../classes/modules/extended/extended_carussell.tpl.php';
            $html = ob_get_contents();
            ob_clean();

            return $html;
         }
      }

      // Karussell speichern
      // 14.05.2019
      if ($this->params->func == 'updateCarussell') {
         $this->saveCarussell();
         $carussell = null;
         $this->getCarussell($carussell);

         ob_start();
         include '../classes/modules/extended/extended_carussell.tpl.php';
         $html = ob_get_contents();
         ob_clean();

         exit(json_encode(['status' => 'ok', 'html' => $html]));
      }

      // Accordion speichern
      // 14.05.2019
      if ($this->params->func == 'updateAccordion') {
         $this->saveAccordion();
         $accordion = null;
         $this->getAccordion($accordion);

         ob_start();
         include '../classes/modules/extended/extended_accordion.tpl.php';
         $html = ob_get_contents();
         ob_clean();

         exit(json_encode(['status' => 'ok', 'html' => $html]));
      }

      // Artikel-Slider speichern
      // 14.05.2019
      if ($this->params->func == 'updateSlider') {
         $this->saveSlider();
         $slider = null;
         $this->getSlider($slider);

         ob_start();
         include '../classes/modules/extended/extended_slider.tpl.php';
         $html = ob_get_contents();
         ob_clean();

         exit(json_encode(['status' => 'ok', 'html' => $html]));
      }

      // Bilder upload
      // 14.05.2019
      elseif (strpos($this->params->func, 'upload') !== false) {
         // Antwort an iFrame
         $this->_fileUpload();
         return;
      }

      // Bilder löschen
      // 14.05.2019
      elseif ($this->params->func == 'delete') {
         $this->_delImg();
         return;
      }

      // Seite ausgeben
      // 14.05.2019
      $carussell = null;
      $this->getCarussell($carussell);
      $accordion = null;
      $this->getAccordion($accordion);
      $slider = null;
      $this->getSlider($slider);

      include '../classes/modules/extended/extended.tpl.php';

      return;
   }

   private function _checkDatabase() {
      // Karussell
      $count = $this->db->query("SELECT id FROM #__pro WHERE typ = 'carussell_conf'");
      if ($count == 0) {
         $data = [
                        'active'      => 'n',
                        'position'    => 'center',
                        'radius_x'    => '350',
                        'radius_y'    => '50',
                        'mirror'      => '50',
                        'speed'       => '30000',
                        'color'       => '#f6f6f6'
         ];

         $this->db->query("INSERT INTO #__pro VALUES(NULL, 'carussell_conf', 0, '', '".$this->db->escape(json_encode($data))."')");

         for ($i = 1; $i < 16; $i++) {
            $data = [
               'image' => '',
               'link' => '',
               'intern' => 'n',
               'tooltip' => '',
               'article_id' => ''
            ];
            $this->db->query("INSERT INTO #__pro VALUES(NULL, 'carussell_img', $i, 'deu', '".$this->db->escape(json_encode($data))."')");
         }
      }

      // Accordion
      $count = $this->db->query("SELECT id FROM #__pro WHERE typ = 'accordion_conf'");
      if ($count == 0) {
         $data = [
                        'active'      => 'n',
                        'position'    => 'center', // top, center, bottom
                        'galerie'   => 'g',  // (g)alery / (h)tml
                        'mouseover' => 'r',  // (r)ollover / (m)ouseclick
//                        'g_gesamt'  => 800,
                        'a_gesamt'  => 800,
                        'a_breite'  => 500,
                        'a_hoehe'   => 300,
                        'dauer'     => '0,8',
                        'wechsel'   => '6'
         ];

         $this->db->query("INSERT INTO #__pro VALUES(NULL, 'accordion_conf', 0, '', '".$this->db->escape(json_encode($data))."')");

         for ($i = 1; $i <= 15; $i++) {
            $data = [
               'image' => '',
               'link' => '',
               'intern' => 'n',
               'tooltip' => ''
            ];
            $this->db->query("INSERT INTO #__pro VALUES(NULL, 'accordion_img', $i, 'deu', '".$this->db->escape(json_encode($data))."')");
         }

         for ($i = 1; $i <= 6; $i++) {
            $data = [
               'text'   => '',
               'active' => 'n',
               'color'  => '#ffffff'
            ];
            $this->db->query("INSERT INTO #__pro VALUES(NULL, 'accordion_html', $i, 'deu', '".$this->db->escape(json_encode($data))."')");
         }
      }

      // Slider
      $count = $this->db->query("SELECT id FROM #__pro WHERE typ = 'slider_conf'");
      if ($count == 0) {
         $data = [
                        'active'            => 'n',
                        'position'          => 'bottom',
                        'frameRate'         => '60',
                        'itemOffset'        => '5',
                        'startEndDistance'  => '20',
                        'tooltipPos_x'      => '0',
                        'tooltipPos_y'      => '0',
                        'sliderPosY'        => '150',
                        'speedLimiter'      => '40',
                        'useAutoReflection' => 'y',
                        'color'             => '#ffffff',
                        'reflectionAlpha'   => '70'
         ];

         $this->db->query("INSERT INTO #__pro VALUES(NULL, 'slider_conf', 0, '', '".$this->db->escape(json_encode($data))."')");

         for ($i = 1; $i < 16; $i++) {
            $data = [
               'image' => '',
               'link' => '',
               'tooltip' => '',
               'intern' => 'n',
               'article_id' => ''
            ];
            $this->db->query("INSERT INTO #__pro VALUES(NULL, 'slider_img', $i, 'deu', '".$this->db->escape(json_encode($data))."')");
         }
      }
   }

   // Carussell Daten bereitstellen
   // 14.05.2019
   private function getCarussell(&$carussell) {
      $carussell = [];
      $conf = $this->db->querySingleObject("SELECT data FROM #__pro WHERE typ = 'carussell_conf'");
      if (is_object($conf)) {
         $carussell['conf'] = json_decode($conf->data);
      }
      else {
         return false;
      }

      $images = $this->db->queryAllObjects("SELECT data, sort FROM #__pro WHERE typ = 'carussell_img' AND lang = '$this->lang' ORDER BY sort");

      if (count($images) == 0) {
         $this->_copyCarussell();
         $images = $this->db->queryAllObjects("SELECT data, sort FROM #__pro WHERE typ = 'carussell_img' AND lang = '$this->lang' ORDER BY sort");
      }

      for ($i = 0; $i < count($images); $i++) {
         $carussell['img_'.($i+1)] = new \stdClass();
         $data = json_decode($images[$i]->data);
         $carussell['img_'.($i+1)]->image = Helper::checkImage($data->image,
                                                               TEMPLATE_PATH.'/images/',
                                                               TEMPLATE_URL.'/images/',
                                                               ADMIN_URL.'/img/nopic78.jpg');
         $carussell['img_'.($i+1)]->link = $data->link;
         $carussell['img_'.($i+1)]->intern = $data->intern;
         $carussell['img_'.($i+1)]->tooltip = $data->tooltip;
         $carussell['img_'.($i+1)]->article_id = $data->article_id;
      }
   }

   // Carussell speichern
   // 14.05.2019
   public function saveCarussell() {
      $lang = $this->params->postString('lang');

      $conf = [];
      $conf['active_desktop'] = $this->params->postCheckbox('active_desktop');
      $conf['active_tablet']  = $this->params->postCheckbox('active_tablet');
      $conf['active_mobile']  = $this->params->postCheckbox('active_mobile');
      $conf['position']       = $this->params->postString('position');
      $conf['radius_x']       = $this->params->postString('radius_x');
      $conf['radius_y']       = $this->params->postString('radius_y');
      $conf['speed']          = $this->params->postString('speed');
      $conf['mirror']         = $this->params->postString('mirror');
      $conf['color']          = $this->params->postString('color');
      $width                  = CONF_THUMB_X;
      $height                 = CONF_THUMB_Y;

      $this->db->query("UPDATE #__pro SET data = '".$this->db->escape(json_encode($conf))."' WHERE typ = 'carussell_conf'");

      for ($i = 1; $i <= 15; $i++) {
         // alte Daten lesen
         $data = json_decode($this->db->querySingleValue("SELECT data FROM #__pro WHERE typ = 'carussell_img' AND sort = $i AND lang = '$lang'"));

         // Aktualisieren aus Artikel?
         if ($this->params->postCheckbox('article_'.$i) == 'y') {
            $extern = false;
            $filename = '';
            $article_id = $this->params->postString('articlenr_'.$i);

            // 1. Zeichen '#' Artikel-ID aus Link
            if (substr($article_id, 0, 1) == '#') {
               $article_id = str_replace('#', '', $article_id);
               $article = $this->db->querySingleObject("SELECT i.name_".$lang." AS name, i.pict01, a.id FROM #__articles_info AS i, #__articles AS a WHERE a.id = $article_id AND a.parent_id = i.id");
            }

            // Sonst aus Admin / Artikelliste
            else {
               $tmp_arr = explode('-', $article_id);
               $sort = 1;

               // Variante gewählt?
               if (isset($tmp_arr[1])) {
                  $article_id = $tmp_arr[0];
                  $sort = $tmp_arr[1];
               }
               if (defined('CONF_MODULE_PORTAL')) {
                  // admin
                  if ($_SESSION['haendler'] == 'n') {
                     $article = $this->db->querySingleObject("SELECT i.name_".$lang." AS name, i.pict01, a.id FROM #__articles_info AS i, #__articles AS a WHERE i.id = $article_id AND a.parent_id = i.id AND a.sort = $sort");
                  }
                  // Händler
                  else {
                     $user_id = $_SESSION['user_id'];
                     $article = $this->db->querySingleObject("SELECT i.name_".$lang." AS name, i.pict01, a.id FROM #__articles_info AS i, #__articles AS a WHERE i.shop_id = $article_id AND haendler_id = $user_id AND a.parent_id = i.id AND a.sort = $sort");
                  }

                  if ($article != null) {
                     $data[$i]['article_id'] = '#'.$article->id;
                  }
               }
               else {
                  $article = $this->db->querySingleObject("SELECT i.name_".$lang." AS name, i.pict01, a.id FROM #__articles_info AS i, #__articles AS a WHERE i.id = $article_id AND a.parent_id = i.id AND a.sort = $sort");
               }
            }

            if ($article) {
               $data->link = ADMIN_URL_IDX.'/'.$lang.'_'.$article->id.'/'.Helper::checkLink($article->name);
               $data->tooltip = $article->name;
               $data->article_id = $article_id;

               if (strpos($article->pict01, 'http://') !== false || strpos($article->pict01, 'https://') !== false) {
                  $extern = true;
                  $filename = Helper::downloadImage($article->pict01, $article->id, '01');
                  if ($filename !== '') {
                     $filename = $this->params->filepath.'/'.CONF_PICT_PATH.str_replace('.jpg', '', $filename).'_tn.jpg';
                  }
               }
               else {
                  $filename = $this->params->filepath.'/'.CONF_PICT_PATH.$article->pict01.'_tn.jpg';
               }

               if (is_file($filename)) {
                  Helper::resizePic($filename, TEMPLATE_PATH.'/images/carussell_'.$lang.'_'.$i.'.png', $width, $height, 'png', false);
                  $data->image = 'carussell_'.$lang.'_'.$i.'.png';
               }
            }
            else {
               $data->link = '';
               $data->tooltip = '';
               $data->article_id = 0;
               $data->image = '';            }
         }

         // oder Eingabe?
         else {
            $data->link = Helper::checkUrl($this->params->postString('link_'.$i));
            $data->tooltip = $this->params->postString('tooltip_'.$i);
            if ((int)$data->article_id == 0) {
               $data->article_id = '';
            }
         }
         $data->intern = $this->params->postCheckbox('intern_'.$i);
         $this->db->query("UPDATE #__pro SET data = '".$this->db->escape(json_encode($data))."' WHERE typ = 'carussell_img' AND sort = $i AND lang = '$lang'");
      }

      // Maske einfärben
      list($r, $g, $b) = sscanf($conf['color'], '#%02x%02x%02x');
      $img = imagecreatefrompng(TEMPLATE_PATH.'/images/system/mask_carussell_default.png');
      imagealphablending($img, false);
      imagesavealpha($img, true);
      imagefilter($img, IMG_FILTER_COLORIZE, $r, $g, $b);
      imagepng($img, TEMPLATE_PATH.'/images/mask_carussell.png');
      imagedestroy($img);
   }

   private function _copyCarussell() {
      $data_arr = $this->db->queryAllObjects("SELECT data FROM #__pro WHERE typ = 'carussell_img' AND lang = 'deu' ORDER BY sort");
      $path = TEMPLATE_PATH.'/images/';

      for ($i = 1; $i <= 15; $i++) {
         $data = json_decode($data_arr[$i - 1]->data);
         if ($data->image != '') {
            if (file_exists($path.$data->image)) {
               copy($path.$data->image, $path.'carussell_'.$this->lang.'_'.$i.'.png');
               $data->image = 'carussell_'.$this->lang.'_'.$i.'.png';
            }
            else {
               $data->image = '';
            }
         }

         $this->db->query("INSERT INTO #__pro VALUES(NULL, 'carussell_img', $i, '$this->lang', '".$this->db->escape(json_encode($data))."')");
      }
   }

   // Accordion Daten bereitstellen
   // 14.05.2019
   private function getAccordion(&$accordion) {
      $accordion = [];
      $conf      = $this->db->querySingleObject("SELECT data FROM #__pro WHERE typ = 'accordion_conf'");

      if (is_object($conf)) {
         $accordion['conf'] = json_decode($conf->data);
      }

      else {
         return false;
      }

      $images = $this->db->queryAllObjects("SELECT data, sort FROM #__pro WHERE typ = 'accordion_img' AND lang = '$this->lang' ORDER BY sort");

      if (count($images) == 0) {
         $this->_copyAccordion();
         $images = $this->db->queryAllObjects("SELECT data, sort FROM #__pro WHERE typ = 'accordion_img' AND lang = '$this->lang' ORDER BY sort");
      }

      for ($i = 0; $i < count($images); $i++) {
         $accordion['img_'.($i+1)]         = new \stdClass();
         $data                             = json_decode($images[$i]->data);
         $accordion['img_'.($i+1)]->image  = Helper::checkImage($data->image,
                                                               TEMPLATE_PATH.'/images/',
                                                               TEMPLATE_URL.'/images/',
                                                               ADMIN_URL.'/img/nopic78.jpg');
         $accordion['img_'.($i+1)]->link   = $data->link;
         $accordion['img_'.($i+1)]->intern = $data->intern;
      }

      $html = $this->db->queryAllObjects("SELECT data, sort FROM #__pro WHERE typ = 'accordion_html' AND lang = '$this->lang' ORDER BY sort");

      for ($i = 0; $i < count($html); $i++) {
         $data = json_decode($html[$i]->data);
         $accordion['html_'.($i+1)]         = new \stdClass();
         $accordion['html_'.($i+1)]->text   = $data->text;
         $accordion['html_'.($i+1)]->active = $data->active;
         $accordion['html_'.($i+1)]->color  = $data->color;
      }
   }

   // Accordion speichern
   // 14.05.2019
   public function saveAccordion() {
      $lang = $this->params->postString('lang');

      $conf = [];
      $conf['active_desktop'] = $this->params->postCheckbox('active_desktop');
      $conf['active_tablet']  = $this->params->postCheckbox('active_tablet');
      $conf['active_mobile']  = $this->params->postCheckbox('active_mobile');
      $conf['position']       = $this->params->postString('position');
      $conf['galerie']        = $this->params->postString('galerie');
      $conf['mouseover']      = $this->params->postString('mouseover');
      $conf['a_breite']       = $this->params->postString('a_breite');
      $conf['a_hoehe']        = $this->params->postString('a_hoehe');
      $conf['dauer']          = $this->params->postString('dauer');
      $conf['wechsel']        = $this->params->postString('wechsel');

      $this->db->query("UPDATE #__pro SET data = '".$this->db->escape(json_encode($conf))."' WHERE typ = 'accordion_conf'");

      if ($conf['galerie'] == 'g') {
         for ($i = 1; $i <= 15; $i++) {
            // alte Daten lesen
            $data = json_decode($this->db->querySingleValue("SELECT data FROM #__pro WHERE typ = 'accordion_img' AND sort = $i AND lang = '$this->lang'"));
            $data->link = helper::checkUrl($this->params->postString('link_'.$i));
            $data->intern = $this->params->postCheckbox('intern_'.$i);
            $this->db->query("UPDATE #__pro SET data = '".$this->db->escape(json_encode($data))."' WHERE typ = 'accordion_img' AND sort = $i AND lang = '$this->lang'");
         }
      }

      else {
         for ($i = 1; $i <= 6; $i++) {
            // alte Daten lesen
            $data         = json_decode($this->db->querySingleValue("SELECT data FROM #__pro WHERE typ = 'accordion_html' AND sort = $i AND lang = '$this->lang'"));
            $data->text   = $this->params->postString('html_'.$i, '', 'none');
            $data->active = $this->params->postCheckbox('html_'.$i.'_active');
            $data->color  = $this->params->postString('html_'.$i.'_color');

            $this->db->query("UPDATE #__pro SET data = '".$this->db->escape(json_encode($data))."' WHERE typ = 'accordion_html' AND sort = $i AND lang = '$this->lang'");
         }
      }
   }

   private function _copyAccordion() {
      $data_arr = $this->db->queryAllObjects("SELECT data FROM #__pro WHERE typ = 'accordion_img' AND lang = 'deu' ORDER BY sort");
      $path = TEMPLATE_PATH.'/images/';

      for ($i = 1; $i <= 15; $i++) {
         $data = json_decode($data_arr[$i - 1]->data);
         if ($data->image != '') {
            if (file_exists($path.$data->image)) {
               copy($path.$data->image, $path.'accordion_'.$this->lang.'_'.$i.'.png');
               $data->image = 'accordion_'.$this->lang.'_'.$i.'.png';
            }
            else {
               $data->image = '';
            }
         }

         $this->db->query("INSERT INTO #__pro VALUES(NULL, 'accordion_img', $i, '$this->lang', '".$this->db->escape(json_encode($data))."')");
      }

      $data_arr = $this->db->queryAllObjects("SELECT data FROM #__pro WHERE typ = 'accordion_html' AND lang = 'deu' ORDER BY sort");

      for ($i = 1; $i <= 6; $i++) {
         $data = $data_arr[$i-1]->data;
         $this->db->query("INSERT INTO #__pro VALUES(NULL, 'accordion_html', $i, '$this->lang', '".$this->db->escape($data)."')");
      }
   }

   // Artikel-Slider Daten bereitstellen
   // 14.05.2019
   private function getSlider(&$slider) {
      $slider = [];
      $conf = $this->db->querySingleObject("SELECT data FROM #__pro WHERE typ = 'slider_conf'");

      if (is_object($conf)) {
         $slider['conf'] = json_decode($conf->data);
      }
      else {
         return false;
      }

      $images = $this->db->queryAllObjects("SELECT data, sort FROM #__pro WHERE typ = 'slider_img' AND lang = '$this->lang' ORDER BY sort");

      if (count($images) == 0) {
         $this->_copySlider();
         $images = $this->db->queryAllObjects("SELECT data, sort FROM #__pro WHERE typ = 'slider_img' AND lang = '$this->lang' ORDER BY sort");
      }

      for ($i = 0; $i < count($images); $i++) {
         $slider['img_'.($i+1)] = new \stdClass();
         $data = json_decode($images[$i]->data);
         $slider['img_'.($i+1)]->image = Helper::checkImage($data->image,
                                                               TEMPLATE_PATH.'/images/',
                                                               TEMPLATE_URL.'/images/',
                                                               ADMIN_URL.'/img/nopic78.jpg');
         $slider['img_'.($i+1)]->link = $data->link;
         $slider['img_'.($i+1)]->intern = $data->intern;
         $slider['img_'.($i+1)]->tooltip = $data->tooltip;
         $slider['img_'.($i+1)]->article_id = $data->article_id;

         if (!strpos($slider['img_'.($i+1)]->image, 'nopic78.jpg')) {
            list($width,$height) = getimagesize(TEMPLATE_PATH.'/images/'.$data->image);
            if ($width >= $height) {
               $slider['img_'.($i+1)]->width = 78;
               $slider['img_'.($i+1)]->height = round($height / $width * 78);
            }
            else {
               $slider['img_'.($i+1)]->width = round($width / $height * 78);
               $slider['img_'.($i+1)]->height = 78;
            }
         }
         else {
            $slider['img_'.($i+1)]->width = 78;
            $slider['img_'.($i+1)]->height = 78;
         }
      }
   }

   // Artikel-Slider speichern
   // 14.05.2019
   public function saveSlider() {
      $lang = $this->params->selected_lang;

      // Konfiguration speichern
      $conf = [];
      $conf['active_desktop']   = $this->params->postCheckbox('active_desktop');
      $conf['active_tablet']    = $this->params->postCheckbox('active_tablet');
      $conf['active_mobile']    = $this->params->postCheckbox('active_mobile');
      $conf['position']         = $this->params->postString('position');
      $conf['itemOffset']       = $this->params->postString('itemOffset');
      $conf['startEndDistance'] = $this->params->postString('startEndDistance');
      $conf['speedLimiter']     = $this->params->postString('speedLimiter');
      $conf['reflectionAlpha']  = $this->params->postString('reflectionAlpha');
      $conf['color']            = $this->params->postString('color');

      $this->db->query("UPDATE #__pro SET data = '".$this->db->escape(json_encode($conf))."' WHERE typ = 'slider_conf'");

      // Bilder / SEO speichern
      for ($i = 1; $i <= 15; $i++) {
         // alte Daten lesen
         $data       = $this->db->querySingleValue("SELECT data FROM #__pro WHERE typ = 'slider_img' AND sort = $i AND lang = '$lang'");
         $article_id = 0;
         $article    = false;

         $data = json_decode($data);

         // Aktualisieren aus Artikel?
         if ($this->params->postCheckbox('article_'.$i) == 'y') {
            $article_id = $this->params->postString('articlenr_'.$i);

            // 1. Zeichen '#' Artikel-ID aus Link
            if (substr($article_id, 0, 1) == '#') {
               $article_id = str_replace('#', '', $article_id);
               $article    = $this->db->querySingleObject("SELECT i.name_".$lang." AS name, i.pict01, a.id FROM #__articles_info AS i, #__articles AS a WHERE a.id = $article_id AND a.parent_id = i.id");
            }

            // Sonst aus Admin / Artikelliste
            else {
               $sort    = 1;
               $tmp_arr = explode('-', $article_id);

               // Variante gewählt?
               if (isset($tmp_arr[1])) {
                  $article_id = $tmp_arr[0];
                  $sort = $tmp_arr[1];
               }

               // Portal
               if (defined('CONF_MODULE_PORTAL')) {
                  // admin
                  if ($_SESSION['haendler'] == 'n') {
                     $article = $this->db->querySingleObject("SELECT i.name_".$lang." AS name, i.pict01, a.id FROM #__articles_info AS i, #__articles AS a WHERE i.id = $article_id AND a.parent_id = i.id AND a.sort = $sort");
                  }
                  // Händler
                  else {
                     $user_id = $_SESSION['user_id'];
                     $article = $this->db->querySingleObject("SELECT i.name_".$lang." AS name, i.pict01, a.id FROM #__articles_info AS i, #__articles AS a WHERE i.shop_id = $article_id AND haendler_id = $user_id AND a.parent_id = i.id AND a.sort = $sort");
                  }

                  if ($article != null) {
                     $data[$i]['article_id'] = '#'.$article->id;
                  }
               }

               // Shop
               else {
                  $article = $this->db->querySingleObject("SELECT i.name_".$lang." AS name, i.pict01, a.id FROM #__articles_info AS i, #__articles AS a WHERE i.id = $article_id AND a.parent_id = i.id AND a.sort = $sort");
               }
            }

            if ($article) {
               $extern   = false;
               $filename = '';
               $width    = 0;
               $height   = 0;

               $data->link       = ADMIN_URL_IDX.'/'.$lang.'_'.$article->id.'/'.Helper::checkLink($article->name);
               $data->tooltip    = $article->name;
               $data->article_id = $article_id;

               if (strpos($article->pict01, 'http://') !== false || strpos($article->pict01, 'https://') !== false) {
                  $extern = true;
                  $filename = Helper::downloadImage($article->pict01, $article->id, '01');

                  if ($filename !== '') {
                     $filename = $this->params->filepath.'/'.CONF_PICT_PATH.str_replace('.jpg', '', $filename).'_tn.jpg';
                  }
               }
               else {
                  $filename = $this->params->filepath.'/'.CONF_PICT_PATH.$article->pict01.'_tn.jpg';
               }

               if (is_file($filename)) {
                  list($width, $height) = getimagesize($filename);
                  Helper::resizePic($filename, TEMPLATE_PATH.'/images/slider_'.$lang.'_'.$i.'.png', $width, $height, 'png', $extern);
                  $data->image = 'slider_'.$lang.'_'.$i.'.png';;
               }
            }

            else {
               $data->link = '';
               $data->tooltip = '';
               $data->article_id = 0;
               $filename = '';
               $data->image = '';
            }

         }

         // oder Eingabe?
         else {
            $data->link = Helper::checkUrl($this->params->postString('link_'.$i));
            $data->tooltip = $this->params->postString('tooltip_'.$i);

            if ((int)$data->article_id == 0) {
               $data->article_id = '';
            }
         }

         $data->intern = $this->params->postCheckbox('intern_'.$i);
         $this->db->query("UPDATE #__pro SET data = '".$this->db->escape(json_encode($data))."' WHERE typ = 'slider_img' AND sort = $i AND lang = '$lang'");
      }

      // Maske einfärben
      list($r, $g, $b) = sscanf($conf['color'], '#%02x%02x%02x');
      $img = imagecreatefrompng(TEMPLATE_PATH.'/images/system/mask_slider_default.png');
      imagealphablending($img, false);
      imagesavealpha($img, true);
      imagefilter($img, IMG_FILTER_COLORIZE, $r, $g, $b);
      imagepng($img, TEMPLATE_PATH.'/images/mask_slider.png');
      imagedestroy($img);
   }

   private function _copySlider() {
      $data_arr = $this->db->queryAllObjects("SELECT data FROM #__pro WHERE typ = 'slider_img' AND lang = 'deu' ORDER BY sort");
      $path = TEMPLATE_PATH.'/images/';

      for ($i = 1; $i <= 15; $i++) {
         $data = json_decode($data_arr[$i - 1]->data);

         if ($data->image != '') {
            if (file_exists($path.$data->image)) {
               copy($path.$data->image, $path.'slider_'.$this->lang.'_'.$i.'.png');
               $data->image = 'slider_'.$this->lang.'_'.$i.'.png';
            }

            else {
               $data->image = '';
            }
         }

         $this->db->query("INSERT INTO #__pro VALUES(NULL, 'slider_img', $i, '$this->lang', '".$this->db->escape(json_encode($data))."')");
      }
   }

   // Bilder upload
   // 14.05.2019
   private function _delImg() {
      if (isset($_POST['name'])) {
         $typ       = $this->params->postString('name');
         $sort      = $this->params->postInt('sort');
         $lang      = $this->params->selected_lang;

         $uploaddir = TEMPLATE_PATH.'/images/';
         $filename  = $typ.'_'.$lang.'_'.$sort.'.png';

         if (is_file($uploaddir.$filename)) {
            unlink($uploaddir.$filename);
         }

         $data = json_decode($this->db->querySingleValue("SELECT data FROM #__pro WHERE typ = '".$typ."_img' AND sort = $sort AND lang = '$lang'"));

         if(is_object($data)) {
            $data->image = '';
            $this->db->query("UPDATE #__pro SET data = '".$this->db->escape(json_encode($data))."' WHERE typ = '".$typ."_img' AND sort = $sort AND lang = '$lang'");
            $data = json_decode($this->db->querySingleValue("SELECT data FROM #__pro WHERE typ = '".$typ."_img' AND sort = $sort AND lang = '$lang'"));

            if ($data->image == '') {
               exit(json_encode(['status' => 'ok', 'html' => ADMIN_URL.'/img/nopic78.jpg?'.time()]));
            }
         }
      }

               exit(json_encode(['status' => 'error', 'msg' => 'Datei konnte nicht gelöscht werden']));
   }

   // Bilder löschen
   // 14.05.2019
   private function _fileUpload() {
      // Namen aus $_FILES lesen
      $temp = array_keys($_FILES);
      $tempname = $temp[0];
//      $filename = $tempname.'.png';

      $lang      = $this->params->selected_lang;
      $typ       = $this->params->postString('param1');
      $sort      = $this->params->postInt('param2');
      $filename  = $typ.'_'.$lang.'_'.$sort.'.png';
//      $filetype  = $_FILES['file']['type'];
//      $ext       = Helper::getExtension($_FILES['file']['name']);
      $uploaddir = TEMPLATE_PATH.'/images/';
      $uploadurl = TEMPLATE_URL.'/images/';

      move_uploaded_file($_FILES['file']['tmp_name'], $uploaddir.$filename);

//      list($typ, $lang, $sort) = explode('_', $tempname);
//      move_uploaded_file($_FILES[$tempname]['tmp_name'], $uploaddir.$filename);

      if ($typ == 'accordion') {
         Helper::imageResize($uploaddir.$filename, $uploaddir.$filename, 1024, 0, '', true, true, false, 0, 500, false);
      }

      else {
         $this->_resizeImg($uploaddir.$filename, $uploaddir.$filename, 0, CONF_THUMB_Y);
      }

      $data = json_decode($this->db->querySingleValue("SELECT data FROM #__pro WHERE typ = '".$typ."_img' AND sort = $sort AND lang = '$lang'"));
      $data->image = $filename;
      $this->db->query("UPDATE #__pro SET data = '".$this->db->escape(json_encode($data))."' WHERE typ = '".$typ."_img' AND sort = $sort AND lang = '$lang'");

      $data = json_decode($this->db->querySingleValue("SELECT data FROM #__pro WHERE typ = '".$typ."_img' AND sort = $sort AND lang = '$lang'"));

      exit(json_encode(['status' => 'ok', 'html' => $uploadurl.$data->image.'?'.time(), 'target' => 'img_src']));
   }

   private function _resizeImg($orgfile, $newfile, $breite = 0, $hoehe = 0) {
      $resize = false;
      list($breite_org, $hoehe_org, $typ) = getimagesize($orgfile);

      // Nur Format wandeln
      if ($breite == 0 && $hoehe == 0) {
         // Ist schon .png? OK.
         if ($typ == IMAGETYPE_PNG) {
            return;
         }

         $breite = $breite_org;
         $hoehe = $hoehe_org;
      }

      // Bildgröße anpassen?
      else if ($hoehe_org > $hoehe) {
         $breite = floor($breite_org * $hoehe / $hoehe_org);
         $resize = true;
      }

      else {
         $hoehe = $hoehe_org;
         $breite = $breite_org;
      }

      //
      if ($breite != 0 && $hoehe != 0) {
         $new_im = imagecreatetruecolor($breite, $hoehe);
         imagealphablending($new_im, false);
         imagesavealpha($new_im, true);

         switch($typ) {
            case IMAGETYPE_PNG:
               $im = imagecreatefrompng($orgfile);
               $resize = true;

               break;

            case IMAGETYPE_JPEG:
               $im = imagecreatefromjpeg($orgfile);
               $resize = true;

               break;

            case IMAGETYPE_GIF:
               $im = imagecreatefromgif($orgfile);
               $resize = true;

               break;
         }

         if ($resize) {
            imagecopyresampled($new_im, $im, 0, 0, 0, 0, $breite, $hoehe, $breite_org, $hoehe_org);
            imagepng($new_im, $newfile);

            unset($im);
            unset($new_im);
         }

         else {
            copy($orgfile, $newfile);
         }
      }

      else {
         copy($orgfile, $newfile);
      }

      return $hoehe;
   }
}
