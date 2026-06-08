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

@ini_set('magic_quotes_runtime', 0);
@ini_set('zend.ze1_compatibility_mode', '0');
@date_default_timezone_set("Europe/Berlin");

class KANPAICLASSIC_paramsBase
{
   public $get_params         = [];
   public $post_params        = [];
   public $server_params      = [];
   public $firma              = [];
   public $langs              = [];
   public $selected_lang      = '';   // wird von getLang gesetzt
   public $default_lang       = '';    // wird von getLang gesetzt
   public $db                 = null;
   public $db_extern          = null;
   public $text               = null;
   public $li_image           = '';
   public $cookie_enabled     = false;
   public $logged_in          = false;
   public $sitelang           = 'deu';

   public $isAdmin            = false;
   public $waehrung           = '';
   public $waehrung_iso       = '';
   public $waehrung_id        = 1;
   public $w_faktor           = 1.00;
   public $eu_list;
   public $site_region;
   public $site_id;
   public $client_dnt         = false; // Do not track me
   public $debug              = '';
   public $multishop          = false;
   public $multishop_template = '';
//   public $no_extern_db_error = false;

   public $links = [];

   // Von /classes/params.class.php für LiveDesigner verschoben
   public $hide_articles      = false;
   public $cat_list           = '';
   public $cats_active        = '';
   public $article_search     = false;
   public $set_offset         = false;
   public $artikel_seite      = 1;

   // Pfade setzen, wird von abgeleiteten Klassen aufgerufen
   public function __construct() {
      $this->client_dnt =(isset($_SERVER["HTTP_DNT"]) && $_SERVER["HTTP_DNT"] == 1 ? true : false);

      // Für Schweizer Kunden, wird in iFrame angezeigt
      if ($_SERVER['SERVER_NAME'] == 'shop.ce-switzerland.ch') {
         function _walk_server(&$val) {
            $val = str_replace('/SHOP', '', $val);
         }

         array_walk_recursive($_SERVER, '_walk_server');
      }

      $server_name = $_SERVER['SERVER_NAME'];

      // Umlaut-Domains
      if (function_exists('\idn_to_utf8')) {
         $server_name = \idn_to_utf8($_SERVER['SERVER_NAME'], IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46);
      }

      if (defined('CONF_USE_HTTP_HOST')) {
         $server_name = $_SERVER['HTTP_HOST'];

         // Umlaut-Domains
         if (function_exists('\idn_to_utf8')) {
            $server_name = \idn_to_utf8($_SERVER['HTTP_HOST'], IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46);
         }
      }

      $basepath = rtrim(substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['SCRIPT_NAME'],'index.php')) , '/');

      if (isset($_SERVER['REDIRECT_URL'])) {
         $basepath = str_replace( $_SERVER['REDIRECT_URL'], '',  $basepath);
      }

      $basepath = str_replace('/admin', '', $basepath);
      $basepath = str_replace('/update', '', $basepath);

      if ($basepath == '/') {
         $basepath = '';
      }

      $protocol = 'http://';

      if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
         $protocol = 'https://';
      }

      if ($protocol == 'http://' && @file_exists(dirname(dirname(__DIR__)).'/ssl_force')) {
         exit(header('Location: '.str_replace('http://', 'https://', $_SERVER['HTTP_REFERER'])));
      }

      $linkurl = $protocol.$server_name.$basepath;

      define('SHOP_URL_IDX', $linkurl.(defined('CONF_USE_HTACCESS') ? '' : '/index.php'));
      define('SHOP_URL', $linkurl);
      define('SHOP_PATH', dirname(dirname(__DIR__)));

      define('ADMIN_URL_IDX', $linkurl.'/admin/index.php');
      define('ADMIN_URL', $linkurl.'/admin');
      define('ADMIN_PATH', dirname(dirname(__DIR__)).'/admin');

      define('URL_PARAMS', preg_replace('|(\?.*)|', '', ltrim(str_replace(str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']), '', $_SERVER['REQUEST_URI']), '/')));
      define ('PICTURE_PATH', SHOP_PATH.'/'.CONF_PICT_PATH);
      define ('PICTURE_URL',  SHOP_URL.'/'.CONF_PICT_PATH);

      if (isset($_COOKIE)) {
         $this->cookie_enabled = true;
      }

      // Installation verwendet eigene DB-Methoden, da Zugangsdaten noch nicht vorhanden
      if (!defined('INSTALL')) {
         $this->db        = Control::getDB();
         $this->db_extern = Control::getExternDB();

         $this->getFirmData();

         define('TEMPLATE_URL', SHOP_URL.'/templates/'.$this->firma['template']);
         define('TEMPLATE_PATH', SHOP_PATH.'/templates/'.$this->firma['template']);
      }
   }

   // Firmendaten aus Tabelle _firma auslesen
   // und aktivierte Sprachen, sowie Default-Sprache Shop wählen
   // Falls noch kein Template gewählt, 1. vorhandene wählen (alphabetisch)
   public function getFirmData() {
      // Firmendaten einlesen
      $firma1      = (array)$this->db->querySingleObject("SELECT * FROM #__firma WHERE id = 1");
      $firma2      = (array)$this->db->querySingleObject("SELECT * FROM #__firma2 WHERE id = 1");
      $this->firma = array_merge($firma1, $firma2);

      unset ($this->firma['id']);

      $this->firma['region']                    = $this->db->querySingleValue("SELECT region FROM #__laender WHERE id = ".$this->firma['versandart_land']);
      $this->firma['call_check']                = Helper::getData('call_check', 'n');
      $this->firma['sonderpreis_ausschliessen'] = Helper::getData('sonderpreis_ausschliessen', 'n');
      $this->multishop                          = (defined('CONF_MODULE_MULTISHOP') && Helper::getData('multishop') == 'y' ? true : false);

      if ($this->multishop) {
         $db_extern = \KANPAICLASSIC\Control::getExternDB();
         $this->multishop_template = \KANPAICLASSIC\Helper::getData('multishop_images').'/templates/'.$db_extern->querySingleValue("SELECT template FROM #__firma WHERE id = 1");

      }

      // Spracheinstellungen
      $this->langs = explode(';', $this->firma['langs']);

      if (!isset($_SESSION['lang'])) {
         if ($this->firma['default_lang'] != '') {
            $_SESSION['lang'] = $this->firma['default_lang'];
         }
         else {
            $_SESSION['lang'] = 'deu';
         }
      }

      $this->selected_lang = $_SESSION['lang'];
      $this->default_lang = $this->firma['default_lang'];

      // Keywords in gewählter Sprache
      $sql = "SELECT * FROM #__keywords WHERE lang = '".$_SESSION['lang']."' AND seite = 'starthtml'";
      $this->db->query($sql);
      $data = $this->db->getObject();

      if ($data) {
         $this->firma['titeltag']    = $data->titeltag;
         $this->firma['keywords']    = $data->keywords;
         $this->firma['description'] = $data->description;
      }

      else {
         $this->firma['titeltag']    = '';
         $this->firma['keywords']    = '';
         $this->firma['description'] = '';
      }

      // Für Meta-Tag language
      $this->site_lang       = $this->db->querySingleValue("SELECT iso_lang FROM #__laender WHERE kurz ='". $_SESSION['lang'] ."'");
      $this->firma['region'] = $this->db->querySingleValue("SELECT region FROM #__laender WHERE id = '". $this->firma['versandart_land'] ."'");

      // Liste EU-Länder (außer land_id)
      $data = $this->db->queryAllObjects("SELECT id FROM #__laender WHERE region = 'eu' AND id != ".$this->firma['versandart_land']);

      if ($data && count($data) > 0) {
         foreach ($data as $d) {
            $this->eu_list[] = (int)$d->id;
         }
      }

      // Land für WK default auf Heimatland Shop
      if (!isset($_SESSION['wk_land']) || $_SESSION['wk_land'] == 0) {
         $_SESSION['wk_land'] = (int)$this->firma['versandart_land'];
      }

      // Rechnung-Land default auf Heimatland Shop
      if (!isset($_SESSION['rechnung_land']) || $_SESSION['rechnung_land'] == 0) {
         $_SESSION['rechnung_land'] = (int)$this->firma['versandart_land'];
      }

      // Liefer-Land default auf Heimatland Shop
      if (!isset($_SESSION['lieferung_land']) || $_SESSION['lieferung_land'] == 0) {
         $_SESSION['lieferung_land'] = (int)$this->firma['versandart_land'];
      }

      // Default-Template, falls noch keines gewählt wurde
      if ($this->firma['template'] == '') {
         $templates = [];

         if (($handle = opendir(SHOP_PATH.'/templates/'))) {
            while (false !== ($file = readdir($handle))) {
               if ($file != "." && $file != ".." && $file != "_svn" && $file != ".svn") {
                  if (is_dir(SHOP_PATH.'/templates/'.$file)) {
                     $templates[] = $file;
                  }
               }
            }

            closedir($handle);
            sort($templates);
            $this->firma['template']      = $templates[0];
         }
      }

      $this->firma['image_cache'] = '?'.Helper::getData('image_cache');
      if ($this->firma['image_cache'] == '?') {
         Helper::setData('image_cache', time());
         $this->firma['image_cache']   = '?'.Helper::getData('image_cache');
      }

      $this->firma['article_cache'] = '?'.Helper::getData('article_cache');
      if ($this->firma['article_cache'] == '?') {
         Helper::setData('article_cache', time());
         $this->firma['article_cache']  = '?'.Helper::getData('article_cache');
      }

      // Konfiguration aus template.json einlesen. TEMPLATE_PATH wird erst später definiert, wenn Daten
      $templatepath = SHOP_PATH.'/templates/'.$this->firma['template'];

      // template.json in Firmendaten einbinden
      if (is_file($templatepath.'/css/template.json')) {
         $template_ini = \json_decode(file_get_contents($templatepath.'/css/template.json'));
         unset($template_ini->kontakt_check);

         foreach($template_ini as $k => $v) {
            if (strpos($k, 'x') === 0) {
               $k = preg_replace('|x\d+_|', '', $k);
            }

            $this->firma[$k] = $v;
         }
      }

      if (!defined('WIDTH_MENU')) {
         if (isset($this->firma['kategorien_links']) && ($this->firma['kategorien_links'] == 'l' || $this->firma['kategorien_links'] == 'y')) {
            define('WIDTH_MENU', 285);
         }
         else {
            define('WIDTH_MENU', 0);
         }
      }
   }

   // Methode getTest()
   // true  - wenn $_GET-Parameter vorhanden ist
   // false - wenn nicht
   public function getTest($name) {
      if (isset($_GET[$name])) {
         return true;
      }
      return false;
   }

   // Methode postTest()
   // true  - wenn $_POST-Parameter vorhanden ist
   // false - wenn nicht
   public function postTest ($name) {
      if (isset($_POST[$name])) {
         return true;
      }
      return false;
   }

   // Methode getString()
   // Überprür $_GET-Parameter auf gültigen String
   // gibt String zurück, wenn Parameter existiert
   // gibt $default zurück, falls er nicht existiert
   // gibt $default zurück bei exploits
   //
   public function getString ($name, $default = '', $mode = 'all') {
      if (isset($get_params[$name])) {
         return $get_params[$name];
      }

      if (isset($_GET[$name])) {
         $test = $this->checkString($_GET[$name], $mode);

         if ($test !== false) {
            $this->post_params[$name] = $test;
            return $test;
         }
      }

      return $default;
   }

   // Methode postString()
   // Überprür $_POST-Parameter auf gültigen String
   // gibt String zurück, wenn Parameter existiert
   // gibt $default zurück, falls er nicht existiert
   // gibt $default zurück bei exploits
   //
   public function postString ($name, $default = '', $mode = 'all') {
      if (isset($this->post_params[$name])) {
         return $this->post_params[$name];
      }

      if (isset($_POST[$name])) {
         $test = $this->checkString($_POST[$name], $mode);

         if ($test !== false) {
            $this->post_params[$name] = $test;
            return $test;
         }
      }
      return $default;
   }

   // Dasselbe für Arrays
   public function postArray($name, $default = '', $mode = 'all') {
      if (isset($this->post_params[$name])) {
         return $this->post_params[$name];
      }

      $ret_arr = [];
      if (isset($_POST[$name]) && is_array($_POST[$name])) {
         foreach ($_POST[$name] as $key => $value) {
            if (is_array($value)) {
               $ret_arr2 = [];

               foreach ($value as $k2 => $v2) {
                  $ret_arr2[] = $this->checkString($v2, $mode);
               }

               $ret_arr[] = $ret_arr2;
            }

            else {
               $ret_arr[] = $this->checkString($value, $mode);
            }
         }
      }

      return $ret_arr;
   }

   // Dasselbe für Int-Werte ($_GET)
   public function getInt ($name, $default = 0) {
      if (isset($this->get_params[$name])) {
         return $this->get_params[$name];
      }

      if (isset($_GET[$name])) {
         $test = $this->checkInt($_POST[$name]);
         if ($test !== false) {
            $this->post_params[$name] = $test;
            return $test;
         }
      }
      return $default;
   }

   // Dasselbe für Int-Werte ($_POST)
   public function postInt($name, $default = 0) {
      if (isset($this->post_params[$name])) {
         return $this->post_params[$name];
      }

      if (isset($_POST[$name])) {
         $test = (int)$_POST[$name];
         $this->post_params[$name] = $test;
         return $test;
      }
      return $default;
   }

   public function setInt($name, $default = 0) {
      $this->post_params[$name] = $default;
   }

   // Dasselbe für Float-Werte ($_POST)
   public function postFloat($name, $default = 0.0) {
      if (isset($this->post_params[$name])) {
         return $this->post_params[$name];
      }

      if (isset($_POST[$name])) {
         $test = $this->str2float($_POST[$name]);
         $this->post_params[$name] = $this->str2float($_POST[$name]);
         return $test;
      }

      return $default;
   }

   public function str2float($val) {
     $last = max(strrpos($val, ','), strrpos($val, '.'));

     if ($last !== false) {
         $val = strtr($val, ',.', '||');
         $val[$last] = '.';
         $val = str_replace('|', '', $val);
     }

     return (float)$val;
   }

   // Dasselbe für Checkboxen (y/n) ($_POST)
   public function postCheckbox($name){
      if (isset($this->post_params[$name])) {
         return $this->post_params[$name];
      }

      // Rückgabewert ist Array
      if (isset($_POST[$name]) && is_array($_POST[$name])) {
         $test = [];
         foreach ($_POST[$name] as $key => $value) {
            $test1 = $_POST[$value];
            if ( $test == 'on' || $test == 'true' || $test == 'y') {
               $test[$key] = 'y';
            }
            else {
               $test[$key] = 'n';
            }
         }
      }

      // Rückgabewert ist Einzelwert
      else {
         $test = (isset($_POST[$name]) && ($_POST[$name] == 'on' || $_POST[$name] == 'true' || $_POST[$name] == 'y')) ? 'y' : 'n';
      }

      $this->post_params[$name] = $test;
      return $test;
   }

   function postRadio($name) {
      if (isset($this->post_params[$name])) {
         return $this->post_params[$name];
      }

      $test = 'n';
      if (isset($_POST[$name])) {
         $test = $_POST[$name];
         if ( $test == 'on' or $test == 'true' or $test == 'y') {
            $test = 'y';
         }
      }
      return $test;
   }

   // Name des aufgerufenen Scripts zurückgeben
   public function getScript() {
      return $_SERVER['SCRIPT_NAME'];
   }

   // Strings auf Exploids überprüfen
   private function checkString($string, $mode) {
      $string = trim($string);

      if ($mode == 'none') {
         // return str_replace("'", '´', $string);
         return $string;
      }

      if ($mode == 'html' or $mode == 'all') {
         // $string = str_replace("'", '´', trim(html_entity_decode(strip_tags($string))));
         $string = trim(html_entity_decode(strip_tags($string)));

//         if (get_magic_quotes_gpc()) {
//            $string = stripslashes($string);
//         }
      }

      if ($mode == 'sql' or $mode == 'all') {
//         $string = str_replace(array("\\r", "\\n", '\\"', "\\\\'", "\\&quot;"), array('', '', '"', "'", ''), $this->db->escape($string));
      }

      return $string;
   }

/*
 * Für neue Erweiterung beachen:
  `text_over` VARCHAR(64) NOT NULL DEFAULT '',
  `text_color` VARCHAR(32) NOT NULL DEFAULT '',
  `text_bg` VARCHAR(32) NOT NULL DEFAULT '',

*/
   // Links Collage, Slideshow usw. aus DB lesen
   public function getLinks($lang) {
      // Defaultwerte setzen
      $this->links = [];

      // Collage
      for ($i = 1; $i < 9; $i++) {
         $this->links['link'.$i] = '';
         $this->links['link'.$i.'_intern']         = 'n';
         $this->links['link'.$i.'_seo']            = '';
         $this->links['link'.$i.'_text']           = '';
         $this->links['link'.$i.'_color_text']     = '#ffffff';
         $this->links['link'.$i.'_color_text_opc'] = '1';
         $this->links['link'.$i.'_color_bg']       = '#000000';
         $this->links['link'.$i.'_color_bg_opc']   = '0.3';
      }

      // Slideshow
      for ($i = 11; $i < 21; $i++) {
         $this->links['link'.$i] = '';
         $this->links['link'.$i.'_intern']         = 'n';
         $this->links['link'.$i.'_seo']            = '';
         $this->links['link'.$i.'_text']           = '';
         $this->links['link'.$i.'_color_text']     = '#ffffff';
         $this->links['link'.$i.'_color_text_opc'] = '1';
         $this->links['link'.$i.'_color_bg']       = '#000000';
         $this->links['link'.$i.'_color_bg_opc']   = '0.3';
      }

      // Sonstige
      $this->links['logoseo']        = '';
      $this->links['logolink']       = SHOP_URL;
      $this->links['logointern']     = 'y';

      $this->links['logomenuseo']    = '';

//      $this->links['bannerlink1']    = '';
//      $this->links['banner1_intern'] = 'n';
//      $this->links['bannerseo1']     = '';

      $this->links['bannerlink2']    = '';
      $this->links['banner2_intern'] = 'n';
      $this->links['bannerseo2']     = '';

      $this->links['danke1_link']    = '';
      $this->links['danke1_intern']  = 'n';
      $this->links['danke1_seo']     = '';
      $this->links['danke2_link']    = '';
      $this->links['danke2_intern']  = 'n';
      $this->links['danke2_seo']     = '';

      // Daten aus DB lesen
      $data = $this->db->querySingleObject("SELECT * FROM #__links WHERE template = '".$this->firma['template']."' AND lang = '$lang'");

      // Und Defaultwerte überschreiben
      if ($data) {
         for ($i = 1; $i < 21; $i++) {
            if ($i == 9 || $i == 10) {
               continue;
            }

            $test = $this->_splitLinks($data->{'link'.$i}, '|');
            isset($test[0]) && $this->links['link'.$i]                   = $test[0];
            isset($test[1]) && $this->links['link'.$i.'_intern']         = $test[1];
            isset($test[2]) && $this->links['link'.$i.'_seo']            = $test[2];
            isset($test[3]) && $this->links['link'.$i.'_text']           = $test[3];
            isset($test[4]) && $this->links['link'.$i.'_color_text']     = $test[4];
            isset($test[5]) && $this->links['link'.$i.'_color_text_opc'] = $test[5];
            isset($test[6]) && $this->links['link'.$i.'_color_bg']       = $test[6];
            isset($test[7]) && $this->links['link'.$i.'_color_bg_opc']   = $test[7];
         }

         $test = $this->_splitLinks($data->logo);
         $this->links['logolink']   = $test[0];
         $this->links['logointern'] = $test[1];
         $this->links['logoseo']    = $test[2];


         $this->links['logomenuseo'] = $data->logomenu;

         $test = $this->_splitLinks($data->banner1);
         $this->links['bannerlink1']    = $test[0];
         $this->links['banner1_intern'] = $test[1];
         $this->links['bannerseo1']     = $test[2];

         $test = $this->_splitLinks($data->banner2);
         $this->links['bannerlink2']    = $test[0];
         $this->links['banner2_intern'] = $test[1];
         $this->links['bannerseo2']     = $test[2];

         $test = @$this->_splitLinks($data->danke1);
         $this->links['danke1_link']    = $test[0];
         $this->links['danke1_intern']  = $test[1];
         $this->links['danke1_seo']     = $test[2];

         $test = @$this->_splitLinks($data->danke2);
         $this->links['danke2_link']    = $test[0];
         $this->links['danke2_intern']  = $test[1];
         $this->links['danke2_seo']     = $test[2];
      }
   }

   // Links Collage, Slideshow usw. in DB speichern
   // Gibt ID des Eintrags in shop_links zurück
   public function StoreLinks() {
      $template = $this->firma['template'];
      $lang     = $this->selected_lang;
      $links_id = (int)$this->db->querySingleValue("SELECT id FROM #__links WHERE template = '$template' AND lang = '".$lang."'");

      // Neuer Eintrag, falls nicht vorhanden
      if ($links_id == 0) {
         $this->db->query("INSERT #__links SET template = '$template', lang = '$lang'");

         $links_id = $this->db->getNewId();
      }

      return $links_id;
   }

/*
 * Für neu Erweiterung beachen:
  `text_over` VARCHAR(64) NOT NULL DEFAULT '',
  `text_color` VARCHAR(32) NOT NULL DEFAULT '',
  `text_bg` VARCHAR(32) NOT NULL DEFAULT '',

*/
   private function _splitLinks($link) {
      $test = explode('|', $link);

      if (!isset($test[0])) {
         $test[0] = '';
      }

      $test[0] = trim($test[0], '|');

      if (!isset($test[1])) {
         $test[1] = 'n';
      }

      if (!isset($test[2])) {
         $test[2] = '';
      }

      return $test;
   }

   // Links generieren für Kategorien und Artikel ('artikel', $artikel->id, $artikel->cat_name.'/'.$art_name, $werte);
   public function getLink($mode, $id = '', $name = '', $werte = '', $cat_name = '') {
      if ($mode == 'artikel') {
         $mode = '';
         $id   = '/'.($this->selected_lang != 'deu' ? $this->selected_lang.'_' : '').$id;
         $name = ($cat_name != '' ? $cat_name.'-' : '').$name;
      }

      else if ($mode == 'kategorie') {
         $mode      = '';
         $show_name = '';
         $cat_len   = 10;
         $cat_arr   = explode('/', $name);
         $end       = count($cat_arr);
         $start     = 0;

         if (defined('CONF_CATLINKS')) {
            $cat_len = CONF_CATLINKS;
         }

         $start = $end - $cat_len;

         if ($start < 0) {
            $start = 0;
         }

         for ($i = $start; $i < $end; $i++) {
            $show_name .= '-'.$cat_arr[$i];
         }

         $show_name = ltrim($show_name, '-');

         return SHOP_URL_IDX.'/k'.($this->selected_lang != 'deu' ? $this->selected_lang.'' : '').$id.($show_name != '' ? "/": '').Helper::checkFilename($show_name != '' ? $show_name : '');

      }

      else {
         $mode = $mode != '' ? '/'.urlencode($mode) : '';
      }

      $link = $name.($werte != '' ? '-'.$werte : '');
      return SHOP_URL_IDX.$mode.$id.'/'.Helper::checkFilename($link);
   }

   // Werte als Textstring zurückgeben
   public function getWerte($m1, $w1, $m2, $w2) {
      $werte = '';

      if ($m1 != '' && $w1 != '') {
//         if (Helper::getData('seo_utf8', 'n') == 'y') {
//            $test   = urlencode($m1).'-'.urlencode($w1);
//            $werte .= str_replace(array('%2F', '%25', ' ', '_'), array('/', '-'), $test);
//         }

//         else {
            $werte .= $m1.'-'.$w1;
//         }
      }

      if ($m2 != '' && $w2 != '') {
//         if (Helper::getData('seo_utf8', 'n') == 'y') {
//            $test   = urlencode($m2).'-'.urlencode($w2);
//            $werte .= ($werte != '' ?  '-' : '').str_replace(array('%2F', '%25', ' ', '_'), array('/', '-'), $test);
//         }

//         else {
            $werte .= ($werte != '' ?  '-' : '').$m2.'-'.$w2;
//         }
      }

      return $werte;
   }
}
