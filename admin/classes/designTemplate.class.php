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
class KANPAICLASSIC_designTemplate extends KANPAICLASSIC_design
{
   private $templates = [];

   function __construct() {
      parent::__construct();
   }

   public function getContent() {
      // Update - CSS-Colors schreiben, Namen Bilder/Flash ändern, Größe ermitteln und in CSS-Banner
      if ($this->params->func == 'save') {
         $this->saveJson();
         $this->_saveConfig();
         $this->_saveTexte();

         header("Location: ".ADMIN_URL_IDX.'/designTemplate');
         exit;
      }

      // Shop aktivieren / deaktiviern
      // 16.05.2019
      elseif ($this->params->func == 'shopOnOff') {
         $this->shopOnOff();
      }

   // Templatename in DB speichern
   // 16.05.2019
      elseif ($this->params->func == 'template') {
         $this->saveTemplate();
         header("Location: ".ADMIN_URL_IDX.'/designTemplate');
         exit;
      }

      // Logo, Banner, Slideshow usw. upload
      // 16.05.2019
      elseif ($this->params->func == 'uploadImg') {
         $this->_fileUpload();
      }

      // Logo, Banner, Slideshow usw. löschen
      // 16.05.2019
      elseif ($this->params->func == 'deleteImg') {
         $file = $this->deleteImg();

         exit(json_encode(['status' => 'ok', 'html' => ADMIN_URL.'/img/nopic.png', 'image' => $file]));
      }

      elseif ($this->params->func == 'saveLink') {
         $this->saveLink();
         exit;
      }

      elseif ($this->params->func == 'loadHeaderscript') {
         $this->loadHeaderscript();
         exit;
      }

      elseif ($this->params->func == 'saveHeaderscript') {
         $this->saveHeaderScript();
         exit;
      }

      elseif ($this->params->func == 'saveCookiePopup') {
         $this->saveCookiePopup();
         exit;
      }

      elseif ($this->params->func == 'loadMenuPopup') {
         $html = '';

         // Defaultwerte laden / speichern, wenn notwendig
         if (!isset($this->params->firma['icon_farbe'])) {
            $this->loadJson();
            file_put_contents(TEMPLATE_PATH.'/css/template.json', json_encode($this->json, JSON_PRETTY_PRINT));
            $this->params->getFirmData();
         }

         require_once 'templates/popup_design_menu.tpl.php';
         exit(json_encode(['status' => 'ok', 'html' => $html]));
      }

      elseif ($this->params->func == 'saveMenuPopup') {
         $this->saveMenuPopup();
         exit(json_encode(['status' => 'ok']));
      }

      elseif ($this->params->func == 'socialPopup') {
         $this->socialPopup();
         exit;
      }

      elseif ($this->params->func == 'saveSocial') {
         $this->_saveSocial();
         exit;
      }

      elseif ($this->params->func == 'callCheck') {
         Helper::setData('call_check', $this->params->postCheckbox('call_check'));
         $this->params->getFirmData();

         echo json_encode(['status' => 'ok']);
         exit;
      }

      // Popup Footer-Icons anzeigen
     // 18.05.2019
      elseif ($this->params->func == 'footerPopup') {
         $html = '';

         $this->loadJson();
         $this->params->getFirmData();

         require_once ADMIN_PATH.'/templates/popup_design_footer.tpl.php';
         echo json_encode(['status' => 'ok', 'html' => $html]);
         exit;
      }

      // Popup Footer-Icons speichern
     // 18.05.2019
      elseif ($this->params->func == 'saveFooter') {
         $this->_saveFooter();
      }

      elseif ($this->params->func == 'multishop') {
         echo json_encode(['status' => $this->_multishop()]);
         return;
      }

      elseif ($this->params->func == 'zoomPopup') {
         $this->loadJson();
         $html = '';
         include_once ADMIN_PATH.'/templates/popup_zoom.tpl.php';

         exit(json_encode(['status' => 'ok', 'html' => $html]));
      }

      elseif ($this->params->func == 'zoomPopupSave') {
         $this->zoomPopupSave();
      }

      $this->getTemplateNames();
      $this->loadJson();
      $social = $this->_loadSocialIcons();
      $text_array = $this->_loadTexte();

      // Alte Version übernehmen
      if (isset($this->params->firma['links_old']) && $this->params->firma['links_old'] == true) {
         for ($i = 1; $i <= 20; $i++) {
            if ($i == 9 || $i == 10) {
               continue;
            }

            else if ($i < 10) {
               $this->params->firma['link'.$i.'_intern_'.$this->params->selected_lang] = $this->links['intern_check'];
            }

            else if ($i < 19) {
               $this->params->firma['link'.$i.'_intern_'.$this->params->selected_lang] = $this->links['intern_sl1_check'];
            }

            else {
               $this->params->firma['link'.$i.'_intern_'.$this->params->selected_lang] = $this->links['intern_sl2_check'];
            }
         }
      }

      include ADMIN_PATH.'/templates/designTemplate.tpl.php';

      return;
   }

   // Transparentes PNG für Artikelliste erstellen
   // 30.05.2019
   private function _saveConfig() {
      $zoom = 1.0;

      if (defined('CONF_THUMB_ZOOM')) {
         $zoom = CONF_THUMB_ZOOM;
      }

      $img   = imagecreate((int)round($zoom * ($this->json['thumb_width'] > 0 ? $this->json['thumb_width'] : CONF_THUMB_X)), (int)round($zoom * ($this->json['thumb_height'] > 0 ? $this->json['thumb_height'] : CONF_THUMB_Y)));
      $color = imagecolorallocate($img, 0xff, 0xff, 0xff);
      imagecolortransparent($img, $color);
      imagepng($img, TEMPLATE_PATH.'/images/system/nopic.png');
      imagedestroy($img);
   }

   // Shop aktivieren / deaktiviern
   // 16.05.2019
   private function shopOnOff() {
      $shop_on = $this->params->postCheckbox('status');
      $this->db->query("UPDATE #__firma SET shop_on_check = '$shop_on' WHERE id = 1");

      exit(json_encode(['status' => 'ok']));
   }

   // Vorhandene Templates im Filesystem lesen
   // 30.05.2019
   private function getTemplateNames() {
      if ($handle = opendir(SHOP_PATH.'/templates/')) {
         while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != ".." && $file != "_svn" && $file != ".svn" && $file != "alt") {
               if (is_dir(SHOP_PATH.'/templates/'.$file)) {
                  $this->templates[] = $file;
               }
               sort($this->templates);
            }
         }
         closedir($handle);
      }
   }

   // Templatename in DB speichern
   // 16.05.2019
   private function saveTemplate() {
      $template = $this->params->postString('template');
      $sql = "UPDATE #__firma SET template = '$template' WHERE id = 1";
      $this->db->query($sql);

      exit(json_encode(['status' => 'ok']));
   }

   // Popup Headerscrip & Cookies anzeigen
   // Wird auch von Seiten/Cookiepopup aufgerufen
   public function loadHeaderscript() {
      $script1 = '';
//      $script2 = '';
      $script3 = '';

      // Immer aktives Script
      if (file_exists(TEMPLATE_PATH.'/save/save/headerscript.inc.php')) {
         $script1 = file_get_contents(TEMPLATE_PATH.'/save/save/headerscript.inc.php');
      }

      // Scripte sind nur aktiv, wenn Kunde zugestimmt hat
//      if (file_exists(TEMPLATE_PATH.'/save/save/headerscript2.inc.php')) {
//         $script2 = file_get_contents(TEMPLATE_PATH.'/save/save/headerscript2.inc.php');
//      }

      // Google Verification-Code
      if (file_exists(TEMPLATE_PATH.'/save/save/google_verification.inc.php')) {
         $script3 = file_get_contents(TEMPLATE_PATH.'/save/save/google_verification.inc.php');
      }

      if (defined('CONF_MODULE_HEADERSCRIPT')) {
         $html  = '<h1 class="txt_tit">Headerscript</h1>';
         $html .= '<p style="margin-bottom:3px;">Google Verification-Code:</p>';
         $html .= '<textarea class="txt_inp" id="script_text3" placeholder="&lt;meta /&gt;">'.$script3.'</textarea>';
         $html .= '<br /><br />';

         $html .= '<p style="margin-bottom:3px;">Immer aktives Script (z.B. Chat-Script, Cookie-Consent-Manager etc.)</p>';
         $html .= '<textarea class="txt_inp" id="script_text1" placeholder="<script></script>">'.$script1.'</textarea>';
         $html .= '<br /><div><span class="txt_bez ellipsis"><a href="http://chat-software.eu" title="Chat-Software" target="_blank" rel="noopener"><img src="../img/social_icons/03.png" alt="Chat-Software" width="25" height="25" /></a></span> <a href="http://chat-software.eu" class="link ci_color" title="Chat-Software" target="_blank">www.chat-software.eu</a>
   </div><br />';
      }
/*
      $html .= '<div class="cookie_zeile" style="line-height:20px; margin-top:3px;">';
      $html .= '   <input type="radio" value="n" class="newdesign" name="cookie_check" id="cookie_check_n"'.($this->params->firma['cookie_check'] == 'n' ? ' checked="checked"' : '').' style="vertical-align:top;" />'.CR;
      $html .= '   <label for="cookie_check_n" onclick="$(\'#cookie_script\').hide(); Multibox.resize();"></label>'.CR;
      $html .= '   &nbsp;Cookie-Popup aus'.CR;
      $html .= '</div>';

      $html .= '<div class="cookie_zeile" style="line-height:20px; margin-top:3px;">';
      $html .= '   <input type="radio" value="y" class="newdesign" name="cookie_check" id="cookie_check_y"'.($this->params->firma['cookie_check'] == 'y' ? ' checked="checked"' : '').' style="vertical-align:top;" />'.CR;
      $html .= '   <label for="cookie_check_y" onclick="$(\'#cookie_script\').hide(); Multibox.resize();"></label>'.CR;
      $html .= '   &nbsp;Cookie-Popup (nur aktivieren, wenn Sie keine persist. Cookies nutzen)'.CR;
      $html .= '</div>';

      $html .= '<div class="cookie_zeile" style="line-height:20px; margin-top:3px;">';
      $html .= '   <input type="radio" value="p" class="newdesign" name="cookie_check" id="cookie_check_p"'.($this->params->firma['cookie_check'] == 'p' ? ' checked="checked"' : '').' style="vertical-align:top;" />'.CR;
      $html .= '   <label for="cookie_check_p" onclick="$(\'#cookie_script\').show(); Multibox.resize();"></label>'.CR;
      $html .= '   &nbsp;Cookie-Popup (nicht für persistente Cookies geeignet)'.CR;
      $html .= '</div>';

      if (defined('CONF_MODULE_HEADERSCRIPT')) {
         $html .= '<div id="cookie_script"'.($this->params->firma['cookie_check'] != 'p' ? ' style="display:none;"' : '').'>'.CR;
         $html .= '   <br />';
         $html .= '   <p style="margin-bottom:3px;">Scripte sind nur aktiv, wenn Kunde zugestimmt hat:</p>';
         $html .= '   <textarea class="txt_inp" id="script_text2" placeholder="<script></script>">'.$script2.'</textarea>';
         $html .= '</div>';
      }
*/
      $html .= '<div class="buttonzeile">';
      $html .= '   <span class="button button_left txt_but" onclick="Multibox.close();">abbrechen</span>';
      $html .= '   <span class="button_ci button_right txt_btn" onclick="Design.saveHeaderscript();">speichern</span>';
      $html .= '</div>';

      echo json_encode(['status' => 'ok', 'html' => $html]);
      exit;
   }

   public function saveHeaderScript() {
      if (!is_dir(TEMPLATE_PATH.'/save')) {
         mkdir(TEMPLATE_PATH.'/save');
      }

      if (!is_dir(TEMPLATE_PATH.'/save/save')) {
         mkdir(TEMPLATE_PATH.'/save/save');
         file_put_contents(TEMPLATE_PATH.'/save/save/.htaccess', 'deny from all'.CR.'ErrorDocument 403 "<h1>Zugriff gesperrt</h1>"'.CR);
      }

      // immer aktiv
      $script1 = $this->_checkInput($_POST['script1']);
      file_put_contents(TEMPLATE_PATH.'/save/save/headerscript.inc.php', $script1);

//      $script2 = $this->_checkInput($_POST['script2']);
//      file_put_contents(TEMPLATE_PATH.'/save/save/headerscript2.inc.php', $script2);

      $script3 = $this->_checkInput($_POST['script3']);
      file_put_contents(TEMPLATE_PATH.'/save/save/google_verification.inc.php', $script3);

      echo json_encode(['status' => 'ok']);
      return;
   }

   // Popup Headerscrip & Cookies anzeigen
   // Wird auch von Seiten/Cookiepopup aufgerufen
   public function loadCookiePopup() {
      $cookie_settings = Helper::cookieSettings();
      $html = '';

      $html .= '<div id="cookiepopup">';
      $html .= '   <div class="cookie_help">'.CR;
      $html .= '      <a class="help_kanpaiclassic" href="https://help.kanpaiclassic.com/o76/cookies-manager/" target="_blank"></a>'.CR;
      $html .= '   </div>'.CR;
      $html .= '   <div class="cookie_zeile radio">';
      $html .= '      <input type="radio" value="n" class="newdesign" name="cookie_check" id="cookie_check_n"'.($this->params->firma['cookie_check'] == 'n' ? ' checked="checked"' : '').' style="vertical-align:top;" />'.CR;
      $html .= '      <label for="cookie_check_n" onclick="$(\'#cookie_show\').hide(); Multibox.resize(); console.log(\'n\');"></label>'.CR;
      $html .= '      &nbsp;Cookie-Popup aus'.CR;
      $html .= '   </div>';
      $html .= '   <div class="cookie_zeile radio">';
      $html .= '      <input type="radio" value="y" class="newdesign" name="cookie_check" id="cookie_check_y"'.($this->params->firma['cookie_check'] == 'y' ? ' checked="checked"' : '').' style="vertical-align:top;" />'.CR;
      $html .= '      <label for="cookie_check_y" onclick="console.log(\'y\'); $(\'#cookie_show\').hide(); Multibox.resize();"></label>'.CR;
      $html .= '      &nbsp;Cookie-OK-Popup (Standard Session-Cookies)'.CR;
      $html .= '   </div>';
      $html .= '   <div class="cookie_zeile radio" style="line-height:20px; margin-top:3px;">';
      $html .= '      <input type="radio" value="p" class="newdesign" name="cookie_check" id="cookie_check_p"'.($this->params->firma['cookie_check'] == 'p' ? ' checked="checked"' : '').' style="vertical-align:top;" />'.CR;
      $html .= '      <label for="cookie_check_p" onclick="console.log(\'p\'); $(\'#cookie_show\').show(); Multibox.resize();"></label>'.CR;
      $html .= '      &nbsp;Cookie-Consent-Manager'.CR;
      $html .= '   </div>';

      // wesentlich
      $html .= '   <div id="cookie_show"'.($this->params->firma['cookie_check'] !== 'p' ? ' style="display:none;"' : '').'>'.CR;
      $html .= '      <div class="cookie_title">'.CR;
      $html .= '         <div class="txt_tit" id="">wesentlich&nbsp;<span class="help ci_color" title="notwendige Sessioncookies"></span></div>'.CR;
      $html .= '      </div>';
      $html .= '      <div class="cookie_text">';
      $html .= '         <textarea class="txt_inp" id="wesentlich_text" placeholder="Beschreibung">'.$cookie_settings->wesentlich_text.'</textarea>';
      $html .= '      </div>';

      // Social Media
      $html .= '      <div class="cookie_title">'.CR;
      $html .= '         <div class="txt_tit" id="">Social Media&nbsp;<span class="help ci_color" title="Einstellung für Like- u. Teilen-Buttons"></span></div>';
      $html .= '      </div>';
      $html .= '      <div class="cookie_text">';
      $html .= '         <textarea class="txt_inp" id="social_text" placeholder="Beschreibung">'.$cookie_settings->social_text.'</textarea>';
      $html .= '      </div>';

      //
      $html .= '      <div class="cookie_title">'.CR;
      $html .= '         <input type="text" value="'.$cookie_settings->marketing_title.'" class="txt_tit" id="marketing_title" name="marketing_title" />';
      $html .= '         &nbsp;<span class="help ci_color" title="Cookiescriptplatz 1 z.B. GoogleAnalytics"></span>';
      $html .= '      </div>';
      $html .= '      <div class="cookie_text">';
      $html .= '         <textarea class="txt_inp" id="marketing_text" placeholder="Beschreibung">'.$cookie_settings->marketing_text.'</textarea>';
      $html .= '      </div>';
      $html .= '      <div class="cookie_script">';
      $html .= '         <textarea class="txt_inp" id="marketing_script" placeholder="<script></script>">'.$cookie_settings->marketing_script.'</textarea>';
      $html .= '      </div>';

      //
      $html .= '      <div class="cookie_title">'.CR;
      $html .= '         <input type="text" id="funktionell_title" class="txt_tit" value="'.$cookie_settings->funktionell_title.'" />';
      $html .= '         &nbsp;<span class="help ci_color" title="Cookiescriptplatz 2 z.B. GoogleAnalytics"></span>';
      $html .= '      </div>';
      $html .= '     <div class="cookie_text">';
      $html .= '         <textarea class="txt_inp" id="funktionell_text" placeholder="Beschreibung">'.$cookie_settings->funktionell_text.'</textarea>';
      $html .= '      </div>';
      $html .= '      <div class="cookie_script">';
      $html .= '         <textarea class="txt_inp" id="funktionell_script" placeholder="<script></script>">'.$cookie_settings->funktionell_script.'</textarea>';
      $html .= '      </div>';
      $html .= '   </div>';

      $html .= '   <div class="buttonzeile">';
      $html .= '      <span class="button button_left txt_but" onclick="Multibox.close();">abbrechen</span>';
      $html .= '      <span class="button_ci button_right txt_btn" onclick="Design.saveCookiePopup();">speichern</span>';
      $html .= '   </div>';
      $html .= '</div>';

      echo json_encode(['status' => 'ok', 'html' => $html]);
      exit;
   }

   public function saveCookiePopup() {
      $lang = $this->params->selected_lang;

      $this->db->query("UPDATE #__firma SET cookie_check = '".$this->params->postString('cookie_check')."'");

      $this->db->query("INSERT INTO  #__cookies SET bezeichnung = 'wesentlich_text', value = '".$this->db->escape($this->params->postString('wesentlich_text'))."', lang = '$lang' ON DUPLICATE KEY UPDATE value = '".$this->db->escape($this->params->postString('wesentlich_text'))."'");
      $this->db->query("INSERT INTO  #__cookies SET bezeichnung = 'social_text', value = '".$this->db->escape($this->params->postString('social_text'))."', lang = '$lang' ON DUPLICATE KEY UPDATE value = '".$this->db->escape($this->params->postString('social_text'))."'");

      $this->db->query("INSERT INTO  #__cookies SET bezeichnung = 'marketing_title', value = '".$this->db->escape($this->params->postString('marketing_title'))."', lang = '$lang' ON DUPLICATE KEY UPDATE value = '".$this->db->escape($this->params->postString('marketing_title'))."'");
      $this->db->query("INSERT INTO  #__cookies SET bezeichnung = 'marketing_text', value = '".$this->db->escape($this->params->postString('marketing_text'))."', lang = '$lang' ON DUPLICATE KEY UPDATE value = '".$this->db->escape($this->params->postString('marketing_text'))."'");
      $this->db->query("INSERT INTO  #__cookies SET bezeichnung = 'marketing_script', value = '".$this->db->escape($this->params->postString('marketing_script', '', 'none'))."', lang = '$lang' ON DUPLICATE KEY UPDATE value = '".$this->db->escape($this->params->postString('marketing_script', '', 'none'))."'");

      $this->db->query("INSERT INTO  #__cookies SET bezeichnung = 'funktionell_title', value = '".$this->db->escape($this->params->postString('funktionell_title'))."', lang = '$lang' ON DUPLICATE KEY UPDATE value = '".$this->db->escape($this->params->postString('funktionell_title'))."'");
      $this->db->query("INSERT INTO  #__cookies SET bezeichnung = 'funktionell_text', value = '".$this->db->escape($this->params->postString('funktionell_text'))."', lang = '$lang' ON DUPLICATE KEY UPDATE value = '".$this->db->escape($this->params->postString('funktionell_text'))."'");
      $this->db->query("INSERT INTO  #__cookies SET bezeichnung = 'funktionell_script', value = '".$this->db->escape($this->params->postString('funktionell_script', '', 'none'))."', lang = '$lang' ON DUPLICATE KEY UPDATE value = '".$this->db->escape($this->params->postString('funktionell_script', '', 'none'))."'");
//      $this->params->getFirmData();

      echo json_encode(['status' => 'ok']);
      return;
   }

   // Popup für Menü-Einstellungen anzeigen
   // 30.05.2019
   private function saveMenuPopup() {
//      $homebutton_check = $this->params->postCheckbox('homebutton_check');
//      $kontakt_check    = $this->params->postCheckbox('kontakt_check');

      $anmelden_mode    = $this->params->postInt('anmelden_mode');
      $merkliste_mode   = $this->params->postInt('merkliste_mode');
      $warenkorb_mode   = $this->params->postInt('warenkorb_mode');
      $suchfeld_mode    = $this->params->postInt('suchfeld_mode');
      $flaggen_mode     = $this->params->postInt('flaggen_mode');
      $icon_farbe       = $this->params->postString('icon_farbe');

//      $this->db->query("UPDATE #__firma2 SET homebutton_check = '$homebutton_check' WHERE id = 1");
//      $this->db->query("UPDATE #__firma2 SET kontakt_check = '$kontakt_check' WHERE id = 1");

      $this->loadJson();

      $this->json['anmelden_mode']  = $anmelden_mode;
      $this->json['merkliste_mode'] = $merkliste_mode;
      $this->json['warenkorb_mode'] = $warenkorb_mode;
      $this->json['suchfeld_mode']  = $suchfeld_mode;
      $this->json['flaggen_mode']   = $flaggen_mode;
      $this->json['icon_farbe']     = $icon_farbe;

      file_put_contents(TEMPLATE_PATH.'/css/template.json', json_encode($this->json, JSON_PRETTY_PRINT));
      $this->params->getFirmData();
   }

   // Popup für Menü-Einstellungen speichern
   // 30.05.2019
   private function saveLink() {
      $name     = $this->params->postString('name');
      $link     = '';
      $link_id  = $this->params->storeLinks();

      // logobanner in DB logo, banner1 nicht mehr verwendet
      if ($name == 'logobanner') {
         // ID des Eintrags
         $link = $this->_checkLinks($this->params->postString('link')).'|'.$this->params->postString('intern').'|'.$this->params->postString('seo');
         $this->db->query("UPDATE #__links SET logo = '$link' WHERE id = $link_id");
      }

      else if ($name == 'banner2') {
         $link = $this->_checkLinks($this->params->postString('link')).'|'.$this->params->postString('intern').'|'.$this->params->postString('seo');
         $this->db->query("UPDATE #__links SET banner2 = '$link' WHERE id = $link_id");
      }

      else if ($name == 'logo') {
         $link = $this->_checkLinks($this->params->postString('link')).'|'.$this->params->postString('intern').'|'.$this->params->postString('seo');
         $this->db->query("UPDATE #__links SET logo = '$link' WHERE id = $link_id");
      }

      else if ($name == 'logomenu') {
         $link = $this->_checkLinks($this->params->postString('link')).'|'.$this->params->postString('intern').'|'.$this->params->postString('seo');
         $this->db->query("UPDATE #__links SET logomenu = '$link' WHERE id = $link_id");
      }

      else {
         $link = $this->_checkLinks($this->params->postString('link')).'|'.$this->params->postString('intern').'|'.$this->params->postString('seo').'|'.
                 $this->params->postString('text').'|'.
                 $this->params->postString('color_text').'|'.
                 $this->params->postString('color_text_opc').'|'.
                 $this->params->postString('color_bg').'|'.
                 $this->params->postString('color_bg_opc');
         $this->db->query("UPDATE #__links SET $name = '$link' WHERE id = $link_id");
      }

      exit(json_encode(['status' => 'ok', 'sql' => $this->db->last_sql]));
   }

   // Daten für Social-Icons laden
   // 29.05.2019
   public function _loadSocialIcons() {
      // Template berücksichtigen
      $social_offset     = 81 + CONF_TEMPLATE_ID * 10;
      $social_offset_max = $social_offset + 4;

      $social = $this->db->queryAllObjects("SELECT id, name, image, footer, detail1, detail2, profillink FROM #__social WHERE id < 80 OR (id >= $social_offset AND id < $social_offset_max) ORDER BY `displayorder`,id");
      // Zusatzicons erstellen, wenn nicht vorhanden / vom Template abhängig
      if (count($social) < 26) {
         for ($i = $social_offset; $i < $social_offset_max; $i++) {
            $this->db->query("INSERT INTO #__social SET id = $i, name = 'Zusatzicons', footer = 'n', profillink = 'y', detail1 = 'n', detail2 = 'd', script_check = 'y'");
         }

         $social = $this->db->queryAllObjects("SELECT id, name, image, footer, detail1, detail2, profillink FROM #__social WHERE id < 80 OR (id >= $social_offset AND id < $social_offset_max) ORDER BY `displayorder`,id");
      }

      return $social;
   }

   public function socialIconsHtml($social) {
      $image_url  = TEMPLATE_URL.'/images/';
      $image_path = TEMPLATE_PATH.'/images/';
      $html       = '';

      for ($i = 0; $i < count($social); $i++) {
         $not_active  = ' not_active';
         $social_icon = ADMIN_URL.'/img/social_icons/'.$social[$i]->image.'.png';

         // Zusätzliche Icons
         if ((int)$social[$i]->id > 100) {
            if ($social[$i]->image == '' || !is_file($image_path.'/'.$social[$i]->image.'.png')) {
               $social_icon = ADMIN_URL.'/img/social_icons/platzhalter.png';
            }

            else {
               $social_icon = $image_url.'/'.$social[$i]->image.'.png';
            }
         }

         if ($social[$i]->detail1 != 'd' && $social[$i]->detail1 == 'y') {
            $not_active = '';
         }

         if ($social[$i]->detail2 != 'd' && $social[$i]->detail2 == 'y') {
            $not_active = '';
         }

         if ($social[$i]->footer == 'y') {
            $not_active = '';
         }

         $html .= '<div title="'.$social[$i]->name.'" class="social_img" onclick="Design.socialPopup('.$social[$i]->id.');">'.CR;
         $html .= '   <img id="img_'.$social[$i]->id.'" class="'.$not_active.'" src="'.$social_icon.'" alt="" />'.CR;
         $html .= '</div>'.CR;
      }

      return $html;
   }

   // Popup Konfiguration Social-Icons anzeigen
   // 30.05.2019
   private function socialPopup() {
      $id = $this->params->postInt('id');
      $html = '';

      $social = $this->db->querySingleObject("SELECT * FROM #__social WHERE id = $id");
      $html .= '<div id="popup_social">'.CR;
      $html .= '   <h1 class="txt_tit">'.$social->name.'</h1>'.CR;

      // Linke Seite
      if ($social->script_check == 'y') {
         $html .= '   <div class="adminbox_left">'.CR;
      }

      if ($social->detail1 != 'd') {
         $image = ADMIN_URL.'/img/social_icons/'.$social->image.'.jpg';


         // nehme an das ist Fehlerhaft?
        /* if (file_exists(SHOP_PATH.'/img/social_icons/'.$social->image.'a.jpg')) {
            $image = ADMIN_URL.'/img/social_icons/'.$social->image.'a.jpg';
         } */



         if ($social->aicon_on_top && file_exists(ADMIN_PATH.'/img/social_icons/'.$social->image.'a.jpg')) {
             $image = ADMIN_URL.'/img/social_icons/'.$social->image.'a.jpg?'.time();
         }


         if ((int)$social->id > 100) {
            if ($social->image == '') {
               $image = ADMIN_URL.'/img/social_icons/platzhalter.jpg';
            }

            else {
               $image = TEMPLATE_URL.'/images/'.$social->image.'.png';
            }
         }

         if(!empty($social->customtext_admin_header)){
             $html .= '      <div class="adminbox_zeile adm_titel txt_bez">'.$social->customtext_admin_header.'</div>'.CR;
         }else{
             $html .= '      <div class="adminbox_zeile adm_titel txt_bez">auf Artikelseite</div>'.CR;
         }



         $html .= '      <div class="adminbox_zeile">'.CR;
         $html .= '         <input type="checkbox" class="newdesign" id="detail1_check"'.($social->detail1 == 'y' ? ' checked="checked"' : '').' />'.CR;
         $html .= '         <label for="detail1_check"></label>'.CR;

         if(!$social->aicon_on_top){
             $html .= '         <img id="socialicon1" class="img_artikelseite img_'.$social->id.'" src="'.$image.'?'.time().'" />'.CR;
         }else{
             $html .= '         <img id="socialicon1" class="img_'.$social->id.'" src="'.$image.'?'.time().'" />'.CR;
         }

         if ($social->detail2 == 'd' && $social->detail_link_check == 'y') {
            $html .= '         <div class="input_right">'.CR;
            $html .= '            <input type="text" class="txt_inp" id="detail_link" name="detail_link" value="'.$social->detail_link.'" placeholder="&bdquo;Accountname&ldquo;" />'.CR;
            $html .= '         </div>'.CR;
         }

         $html .= '</div>'.CR;

         if ($social->detail2 != 'd') {
            $image = ADMIN_URL.'/img/social_icons/'.$social->image.'.jpg';

            if (file_exists(ADMIN_PATH.'/img/social_icons/'.$social->image.'b.jpg')) {
               $image = ADMIN_URL.'/img/social_icons/'.$social->image.'b.jpg?'.time();
            }

            if ((int)$social->id > 100) {
               if ($social->image == '') {
                  $image = ADMIN_URL.'/img/social_icons/platzhalter.jpg?'.time();
               }
               else {
                  $image = TEMPLATE_URL.'/images/'.$social->image.'.png?'.time();
               }
            }

            $html .= '<div class="adminbox_zeile">'.CR;
            $html .= '   <input type="checkbox" class="newdesign" id="detail2_check"'.($social->detail2 == 'y' ? ' checked="checked"' : '').' />'.CR;
            $html .= '   <label for="detail2_check"></label>'.CR;
            $html .= '   <img id="socialicon3" class="img_'.$social->id.'" src="'.ADMIN_URL.'/img/social_icons/'.$social->image.'b.jpg" />'.CR;

            if ($social->detail_link_check == 'y') {
               $html .= '   <div class="input_right">';
               $html .= '      <input type="text" class="txt_inp" id="detail_link" name="detail_link" value="'.$social->detail_link.'" placeholder="&bdquo;Accountname&ldquo;" />'.CR;
               $html .= '   </div>'.CR;
            }

            $html .= '</div>'.CR;

         }
      }

      else {
         $html .= '      <input type="hidden" id="detail1" name="detail1" value="n" />'.CR;
         $html .= '      <input type="hidden" id="detail2" name="detail2" value="n" />'.CR;
         $html .= '      <input type="hidden" id="detail_link" name="detail_link" value="'.$social->detail_link.'" />'.CR;
         $html .= '      <input type="hidden" id="detail_link" name="detail_link" value="'.$social->detail_link.'" />'.CR;

      }

      if ($social->script_check == 'y') {
         $html .= '      </div>'.CR;
         $html .= '      <div class="adminbox_right">'.CR;
         $html .= '         <div class="adminbox_zeile adm_titel txt_bez">Script einkopieren</div>'.CR;
         $html .= '         <textarea id="detail_script" placeholder="<script>[URL] wird durch Artikel-URL ersetzt</script>">'.$social->detail_script.'</textarea>'.CR;
         $html .= '         <input type="hidden" id="script_check" value="on" />'.CR;
         $html .= '      </div>'.CR;
         $html .= '      <div class="clear"></div>'.CR;
      }

      $image = ADMIN_URL.'/img/social_icons/'.$social->image.'.jpg'.CR;

      // Zusätzlich Icons
      if ((int)$social->id > 100) {
         if ($social->image == '') {
            $image = ADMIN_URL.'/img/social_icons/platzhalter.jpg';
         }

         else {
            $image = TEMPLATE_URL.'/images/'.$social->image.'.png';
         }
      }

      // Favoriten

      if(!empty($social->customtext_admin_footer)){
          $html .= '      <div class="adminbox_zeile adm_title txt_bez">'.$social->customtext_admin_footer.'</div>'.CR;
      }else{

          if ((int)$social->id == 1) {
              $html .= '      <div class="adminbox_zeile adm_title txt_bez"></div>'.CR;
          }

          else if ($social->profillink == 'y') {
              $html .= '      <div class="adminbox_zeile adm_title txt_bez">im Footer Profillink</div>'.CR;
          }

          else {
              $html .= '      <div class="adminbox_zeile adm_title txt_bez">im Footer</div>'.CR;
          }

      }

      $html .= '   <div class="adminbox_zeile">'.CR;
      $html .= '      <input type="checkbox" class="newdesign" id="footer_check"'.($social->footer == 'y' ? ' checked="checked"' : '').' />'.CR;
      $html .= '      <label for="footer_check"></label>'.CR;
      $html .= '      <img id="socialicon2" class="img_footer img_'.$social->id.'" src="'.$image.'?'.time().'" />'.CR;

      // Zusätzliche Icons
      if ($id > 100) {
         $html .= '      <div class="social_upload pointer upload_button" onclick="Design.uploadImg(\'socialicon\', '.$id.',  \'img_'.$id.'\', \'jpg,png\');"></div>';
         $html .= '      <div class="input_right75">'.CR;
         $html .= '         <input type="text" class="link_extra txt_inp" id="footer_link" name="footer_link" value="'.$social->footer_link.'" />'.CR;
         $html .= '      </div>'.CR;
         $html .= '   </div>'.CR;

         $html .= '   <div class="adminbox_zeile">'.CR;
         $html .= '      <span class="span_extra">Titel</span>'.CR;
         $html .= '      <div class="input_right75">'.CR;
         $html .= '         <input class="txt_inp link_extra" type="text" id="social_name" value="'.$social->name.'" />'.CR;
         $html .= '      </div>'.CR;
      }

      // Icons außer Favoriten / Mail
      else if ($id > 2) {
         $html .= '      <div class="input_right">'.CR;
         $html .= '         <input type="text" class="txt_inp" id="footer_link" name="footer_link" value="'.$social->footer_link.'" />'.CR;
         $html .= '      </div>'.CR;
      }

      // Faforiten
      else {
         $html .= '      <input type="hidden" id="footer_link" name="footer_link" value="" />'.CR;
      }

      $html .= '   </div>'.CR;

      $html .= '   <div class="buttonzeile">'.CR;
      $html .= '      <span class="button txt_but" onclick="Multibox.close();">abbrechen</span>'.CR;
      $html .= '      <span class="button_ci txt_btn" onclick="Design.saveSocial('.$id.');">speichern</span>'.CR;
      $html .= '   </div>'.CR;
      $html .= '</div>'.CR;

      exit(json_encode(['status' => 'ok', 'html' => $html]));
   }

   // Popup Konfiguration Social-Icons speichern
   // 30.05.2019
   private function _saveSocial() {
      $id            = $this->params->postInt('id');
      $detail1       = substr($this->params->postString('detail1_check'), 0, 1);
      $detail2       = substr($this->params->postString('detail2_check'), 0, 1);
      $footer        = $this->params->postCheckbox('footer_check');
      $footer_link   = $this->params->postString('footer_link');
      $detail_script = isset($_POST['detail_script']) ? $_POST['detail_script'] : '';
      $detail_link   = $this->params->postString('detail_link');

      $sql = "UPDATE #__social SET
                 footer        = '$footer',
                 footer_link   = '$footer_link',
                 detail1       = '$detail1',
                 detail2       = '$detail2',
                 detail_script = '".$this->db->escape($detail_script)."',
                 detail_link   = '".$this->db->escape($detail_link)."'";

      if ($id > 100 ) {
         $sql .= ", `name` = '".$this->params->postString('social_name')."'";
      }

      $sql .= " WHERE id = $id";

      $this->db->query($sql);

      $active = 'n';
      if ($detail1 != 'd' && $detail1 == 'y') {
         $active = 'y';
      }

      if ($detail2 != 'd' && $detail2 == 'y') {
         $active = 'y';
      }

      if ($footer == 'y') {
         $active = 'y';
      }

      exit(json_encode(['status' => 'ok', 'active' => $active, 'html' => $this->socialIconsHtml($this->_loadSocialIcons())]));
   }

   // Starthtml und Footer laden
   // 30.05.2019
   public function _loadTexte() {
      $lang = $this->params->selected_lang;
      $html_array = [];

//      $html_array[] = 'starthtml';
      $html_array[] =  $this->db->querySingleValue("SELECT text FROM #__seiten WHERE lang = '$lang' AND art = 'starthtml'");

//      $html_array[] = 'footer';
      $html_array[] =  $this->db->querySingleValue("SELECT text FROM #__seiten WHERE lang = '$lang' AND art = 'footer'");

      return $html_array;
   }

   // Starthtml und Footer speichern
   private function _saveTexte() {
      $this->db->query("UPDATE #__firma SET `social_status` = '".$this->params->postString('social_status')."' WHERE id = 1");
      $this->params->firma['social_status'] = $this->params->postString('social_status');

      $lang = $this->params->postString('sel_lang');

      $footer = $this->params->postString('footer_text','',  'sql');
      $this->_writeSql($lang, 'footer', $footer, '');

      // nur wenn angezeigt speichern
      if ($this->params->postCheckbox('starthtml_on') == 'y') {
         $starthtml = $this->params->postString('starthtml_text','',  'sql');
         $this->_writeSql($lang, 'starthtml', $starthtml, '');
      }
   }

   // Daten in DB (shop_seiten) eintragen
   private function _writeSql($lang, $art, $text, $name) {
      $test = $this->db->querySingleObject("SELECT `id`, `text`, `name` FROM #__seiten WHERE lang = '$lang' and `art` = '$art'");

      // Eintrag vorhanden
      if ($test) {
         // Keine Änderung
         if ($test->text == $text && $test->name == $name) {
            return;
         }

         // Änderung speichern
         else {
            $this->db->query("UPDATE #__seiten SET `text` = '".$this->db->escape($text)."', `name` = '$name' WHERE id = $test->id");
         }
      }

      // Neuer Eintrag
      else {
         $this->db->query("INSERT INTO #__seiten SET `art` = '$art', `lang` = '$lang', `text` = '".$this->db->escape($text)."', `name` = '$name'");
      }

      return;
   }

   private function _multishop() {
      $val = $this->params->postCheckbox('multishop');
      if ($val == 'n' || $val == 'y') {
         $this->db->query("UPDATE #__firma SET multishop = '$val' WHERE id = 1");
         return $val;
      }
      return 'ERROR';
   }

   // Auch von Popup-Livedesigner verwendet
   public function _animationOptions($animation) {
      $html  = '';
      $html .= '<option value="3dflip"'.($animation == '3dflip' ? ' selected="selected"' : '').'>3d Flip</option>';
      $html .= '<option value="bounceBottom"'.($animation == 'bounceBottom' ? ' selected="selected"' : '').'>Bounce Bottom</option>';
      $html .= '<option value="bounceLeft"'.($animation == 'bounceLeft' ? ' selected="selected"' : '').'>Bounce Left</option>';
      $html .= '<option value="bounceTop"'.($animation == 'bounceTop' ? ' selected="selected"' : '').'>Bounce Top</option>';
      $html .= '<option value="fadeOut"'.($animation == 'fadeOut' ? ' selected="selected"' : '').'>Fade Out</option>';
      $html .= '<option value="fadeOutTop"'.($animation == 'fadeOutTop' ? ' selected="selected"' : '').'>Fade Out Top</option>';
      $html .= '<option value="flipBottom"'.($animation == 'flipBottom' ? ' selected="selected"' : '').'>Flip Bottom</option>';
      $html .= '<option value="flipOut"'.($animation == 'flipOut' ? ' selected="selected"' : '').'>Flip Out</option>';
      $html .= '<option value="flipOutDelay"'.($animation == 'flipOutDelay' ? ' selected="selected"' : '').'>Flip Out Delay</option>';
      $html .= '<option value="foldLeft"'.($animation == 'foldLeft' ? ' selected="selected"' : '').'>Fold Left</option>';
      $html .= '<option value="frontRow"'.($animation == 'frontRow' ? ' selected="selected"' : '').'>Front Row</option>';
      $html .= '<option value="moveLeft"'.($animation == 'moveLeft' ? ' selected="selected"' : '').'>Move Left</option>';
      $html .= '<option value="quicksand"'.($animation == 'quicksand' ? ' selected="selected"' : '').'>Quicksand</option>';
      $html .= '<option value="rotateSides"'.($animation == 'rotateSides' ? ' selected="selected"' : '').'>Rotate Sides</option>';
      $html .= '<option value="rotateRoom"'.($animation == 'rotateRoom' ? ' selected="selected"' : '').'>Rotate Room</option>';
      $html .= '<option value="scaleDown"'.($animation == 'rotateRoom' ? ' selected="selected"' : '').'>Scale Down</option>';
      $html .= '<option value="scaleSides"'.($animation == 'scaleSides' ? ' selected="selected"' : '').'>Scale Sides</option>';
      $html .= '<option value="slideLeft"'.($animation == 'slideLeft' ? ' selected="selected"' : '').'>Slide Left</option>';
      $html .= '<option value="sequentially"'.($animation == 'sequentially' ? ' selected="selected"' : '').'>Sequentially</option>';
      $html .= '<option value="slideDelay"'.($animation == 'slideDelay' ? ' selected="selected"' : '').'>Slide Delay</option>';
      $html .= '<option value="skew"'.($animation == 'skew' ? ' selected="selected"' : '').'>Skew</option>';
      $html .= '<option value="unfold"'.($animation == 'unfold' ? ' selected="selected"' : '').'>Unfold</option>';

      return $html;
   }

   // Auch von Popup-Livedesigner verwendet
   public function _designOptions($animation) {
      $html  = '';
//      $html .= '<option value="fadeIn"'.($animation == 'fadeIn' ? ' selected="selected"' : '').'>Fade In</option>';
      $html .= '<option value="lazyLoading"'.($animation == 'lazyLoading' ? ' selected="selected"' : '').'>lazy Loading</option>';
      $html .= '<option value="fadeInToTop"'.($animation == 'fadeInToTop' ? ' selected="selected"' : '').'>Fade In To Top</option>';
      $html .= '<option value="sequentially"'.($animation == 'sequentially' ? ' selected="selected"' : '').'>Sequentially</option>';
      $html .= '<option value="bottomToTop"'.($animation == 'bottomToTop' ? ' selected="selected"' : '').'>Bottom To Top</option>';

      return $html;
   }

    // Popup Footer-Icons speichern
   // 18.05.2019
   private function _saveFooter() {
      $livedesigner                      = $this->params->postCheckbox('livedesigner');

      $this->loadJson();

      $this->json['footer_dhl']          = $this->params->postCheckbox('footer_dhl');
      $this->json['footer_dpd']          = $this->params->postCheckbox('footer_dpd');
      $this->json['footer_hermes']       = $this->params->postCheckbox('footer_hermes');
      $this->json['footer_gls']          = $this->params->postCheckbox('footer_gls');
      $this->json['footer_ups']          = $this->params->postCheckbox('footer_ups');
      $this->json['footer_post']         = $this->params->postCheckbox('footer_post');
      $this->json['footer_ssl']          = $this->params->postCheckbox('footer_ssl');

      $this->json['footer_bar']          = $this->params->postCheckbox('footer_bar');
      $this->json['footer_ueberweisung'] = $this->params->postCheckbox('footer_ueberweisung');
      $this->json['footer_rechnung']     = $this->params->postCheckbox('footer_rechnung');
      $this->json['footer_nachnahme']    = $this->params->postCheckbox('footer_nachnahme');
      $this->json['footer_paypal']       = $this->params->postCheckbox('footer_paypal');
      $this->json['footer_paypalplus']   = $this->params->postCheckbox('footer_paypalplus');
      $this->json['footer_visa']         = $this->params->postCheckbox('footer_visa');
      $this->json['footer_sofort']       = $this->params->postCheckbox('footer_sofort');
      $this->json['footer_klarna']       = $this->params->postCheckbox('footer_klarna');
      $this->json['footer_amazon']       = $this->params->postCheckbox('footer_amazon');
      $this->json['footer_easycredit']   = $this->params->postCheckbox('footer_easycredit');
      $this->json['footer_ratenkauf']    = $this->params->postCheckbox('footer_ratenkauf');
      $this->json['footer_paydirekt']    = $this->params->postCheckbox('footer_paydirekt');
      $this->json['footer_postfinance']  = $this->params->postCheckbox('footer_postfinance');
      $this->json['footer_twint']        = $this->params->postCheckbox('footer_twint');
      $this->json['footer_wir']          = $this->params->postCheckbox('footer_wir');
      $this->json['footer_swisspay']     = $this->params->postCheckbox('footer_swisspay');

      $this->json['footer_farbe']        = $this->params->postString('footer_farbe');

      file_put_contents(TEMPLATE_PATH.'/css/template.json', json_encode($this->json, JSON_PRETTY_PRINT));
      $this->params->getFirmData();

      $html = '';

      if ($livedesigner == 'y') {
         $footer_farbe = (isset($this->params->firma['footer_farbe']) && $this->params->firma['footer_farbe'] !== 'antrazit' ? $this->params->firma['footer_farbe'] : '');
         $pf = $this->params->firma;

         require_once TEMPLATE_PATH.'/footer_icons.tpl.php';
      }

      exit(json_encode(['status' => 'ok', 'html' => $html]));
   }

   private function zoomPopupSave() {
      $this->loadJson();
      $this->json['detailbild'] = $this->params->postInt('detailbild');

      file_put_contents(TEMPLATE_PATH.'/css/template.json', json_encode($this->json, JSON_PRETTY_PRINT));
      $this->jsonBackup();

      exit(json_encode(['status' => 'ok']));
   }
}
