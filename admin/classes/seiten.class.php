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
define ('SEITEN1', ['homebutton', 'ueberuns1', 'ueberuns2', 'ueberuns3', 'kontakt']);
define ('SEITEN2', ['impressum', 'datenschutz', 'kontakt2', 'anmelden', 'ueberuns4', 'ueberuns5']);
define ('SEITEN3', ['versand', 'agb', 'kundeninfo', 'widerruf1', 'widerruf2', 'widerruf3', 'widerruf4', 'widerruf5']);

if (!defined('KANPAICLASSIC')) {
   die ("This file cannot run outside the Kanpai Classic Shopsoftware");
}

class KANPAICLASSIC_seiten
{
   public $db;
   public $params;
   public $text;
   public $text_array = [];
   public $keywords;


   function __construct() {
      $this->db = Control::getDB();
      $this->params = Control::getParams();
      $this->text = Control::getText();
   }


   public function getContent() {

      include_once 'classes/menu.class.php';
      $menu = Control::getMenu();
      $this->menudata = $menu->menuData();

      // Editor/Popup anzeigen
      // 04.05.2019
      if ($this->params->func == 'popup') {
         $this->popup();
      }

      // Daten Editor/Popup speichern
      // 04.05.2019
      elseif ($this->params->func == 'savePopup') {
         $this->savePopup();
      }

      // Sitemap/Popup anzeigen
      // 29.01.2020
      if ($this->params->func == 'popupSitemap') {
         $this->popupSitemap();
      }

      // Daten Sitemap/Popup speichern
      // 28.01.2020
      elseif ($this->params->func == 'savePopupSitemap') {
         $this->savePopupSitemap();
      }

      // Status Checkpoxen (Seite aktiv / inaktiv) speichern
      // 04.05.2019
      elseif ($this->params->func == 'active') {
         $this->active();
      }

      // Bilder Upload
      // 04.05.2019
      elseif ($this->params->func == 'upload') {
         $this->upload();
         return;
      }

      // Bild löschen
      // 04.05.2019
      elseif ($this->params->func == 'deleteImg') {
         $this->deleteImg();
      }

      // Links zu Bildern speichern
      // 04.05.2019
      elseif ($this->params->func == 'saveLinks') {
         $this->_saveLinks();
         return;
      }

      // Popup Conversion anzeigeb
      // 04.05.2019
      elseif ($this->params->func == 'loadConversion') {
         $this->_loadConversion();
         exit;
      }

      // Popup Conversion speichern
      // 04.05.2019
      elseif ($this->params->func == 'saveConversion') {
         $this->_saveConversion();
         exit;
      }

      // Popup Trusted Shops speichern
      // 04.05.2019
      elseif ($this->params->func == 'saveTrustedshops') {
         $this->_saveTrustedshops();
         exit;
      }

      // Popup Header anzeigen
      // 08.01.2020
      elseif ($this->params->func == 'headerPopup') {
         $this->headerPopup();
         exit;
      }

      // Popup Cookies anzeigen
      // 17.09.2020
      elseif ($this->params->func == 'headerSave') {
         $this->headerSave();
         exit;
      }

      // Popup Cookies anzeigen
      // 17.09.2020
      elseif ($this->params->func == 'cookiePopup') {

         $this->cookiePopup();
         exit;
      }



      // Popup Cookies anzeigen
      // 08.01.2020
      elseif ($this->params->func == 'cookieSave') {
         $this->cookieSave();
         exit;
      }

  

      // Seite anzeigen
      // 04.05.2019
      $lang    = $this->params->selected_lang;
      $seiten1 = $this->_getData(SEITEN1);
      $seiten2 = $this->_getData(SEITEN2);
      $seiten3 = $this->_getData(SEITEN3);

      include ADMIN_PATH.'/templates/seiten.tpl.php';
      return;
   }

   // Daten lesen Systemtexte
   // 04.05.2019
   public function _getData($seiten) {
      $lang   = $this->params->selected_lang;
      $data   = [];

      foreach ($seiten as $seitenname) {
         $s    = $seitenname;
         $name = '';

         // Defaulteinträge bei Widerruf und 'deu'
         if ($lang == 'deu') {
            if ($s == 'widerruf1') { $name = '& Formular'; }
            if ($s == 'widerruf2') { $name = 'Standard 2'; }
            if ($s == 'widerruf3') { $name = 'Spedition'; }
            if ($s == 'widerruf4') { $name = 'Dienstleistung'; }
            if ($s == 'widerruf5') { $name = 'Download'; }
         }

         $sql = "SELECT s.id, s.art, s.lang, s.text, s.name, s.check, k.keywords, k.description, k.titeltag
                    FROM #__seiten AS s
                 LEFT JOIN #__keywords AS k
                    ON k.lang = s.lang AND s.art = k.seite
                 WHERE s.lang = '$lang' AND s.art = '$s'";
         $data1 = $this->db->querySingleObject($sql);

         // Eintrag in DB vorvanden
         if ($data1) {
            $data[$seitenname] = ['seite'       => $data1->text,
                                  'name'        => $data1->name,
                                  'keywords'    => $data1->keywords,
                                  'description' => $data1->description,
                                  'titeltag'    => $data1->titeltag,
                                  'check'       => $data1->check];
         }

         // Somst neuer Eintrag in DB und Standardwerte zurück geben
         else {
            $this->db->query("INSERT INTO #__seiten VALUES (NULL, '$s', '$lang', '', '$name', 'n')");
            $data[$seitenname] = ['seite' => $s, 'name' => $name, 'keywords' => '', 'description' => '', 'titeltag' => '', 'check' => 'n'];
         }
      }

      return $data;
   }

   // Popup Editor anzeigen
   // 04.05.2019
   public function popup() {
      $seite                = $this->params->postString('seite');
      $lang                 = $this->params->selected_lang;
      $html                 = '';
      $uns                  = 0;
      $info                 = '';
      $pdf_link             = '';
      $wr_name              = '';
      $name                 = '';

      // $uns an Seite anpassen für Bilder
      if ($seite == 'ueberuns')  { $uns = 1; }
      if ($seite == 'ueberuns1') { $uns = 1; }
      if ($seite == 'ueberuns2') { $uns = 2; }
      if ($seite == 'ueberuns3') { $uns = 3; }
      if ($seite == 'ueberuns4') { $uns = 4; }
      if ($seite == 'ueberuns5') { $uns = 5; }

      if ($seite == 'impressum') { $uns = 11; }
      if ($seite == 'kontakt')   { $uns = 12; }

      // Zusätzliche Texte
      if ($seite == 'starthtml') { $info = 'Diese wichtigen Metatags sind auch wirksame, wenn der Button HOME deaktiviert ist.'; }
      if ($seite == 'kundeninfo') { $info = 'Nach aktuellem Recht stehen die Kundeninformationen bei den AGBs.'; }

      // Zusätzliche Texte und PDF Widerruf
      if ($seite == 'widerruf1') {
         $wr_name  = 'Widerruf Standard 1';
         $pdf_link = SHOP_URL.'/classes/pdf/WiderrufA_'.$lang.'.pdf';
         $wr_txt   = 'Stellen Sie beim Artikel auf "Standard 1", dann wird beim Kauf dieser Widerruf angezeigt.';
      }

      if ($seite == 'widerruf2') {
         $wr_name  = 'Widerruf Standard 2';
         $pdf_link = SHOP_URL.'/classes/pdf/WiderrufB_'.$lang.'.pdf';
         $wr_txt   = 'Stellen Sie beim Artikel auf "Standard 2", dann wird beim Kauf dieser Widerruf angezeigt.';
      }

      if ($seite == 'widerruf3') {
         $wr_name  = 'Widerruf für Spedition';
         $pdf_link = SHOP_URL.'/classes/pdf/WiderrufC_'.$lang.'.pdf';
         $wr_txt   = 'Haben Sie einen Speditionsartikel, so stellen Sie bitte im Artikel "Spedition" ein, dann wird beim Kauf dieser Widerruf angezeigt.';
      }

      if ($seite == 'widerruf4') {
         $wr_name  = 'Widerruf für Dienstleistungen';
         $pdf_link = SHOP_URL.'/classes/pdf/WiderrufD_'.$lang.'.pdf';
         $wr_txt   = 'Wenn Sie eine Dienstleistung anbieten, dann wird beim Kauf dieser Widerruf angezeigt. Ebenfalls erfolgt eine Abfrage, ob die Dienstleistung schon vor Ablauf der Widerrufsfrist beginnen soll.';
      }

      if ($seite == 'widerruf5') {
         $wr_name  = 'Widerruf für Downloadartikel';
         $pdf_link = SHOP_URL.'/classes/pdf/WiderrufE_'.$lang.'.pdf';
         $wr_txt   = 'Wenn Sie Downloadartikel anbieten, dann wird beim Kauf dieser Widerruf angezeigt. Ebenfalls erfolgt eine Abfrage, ob der Download schon vor Ablauf der Widerrufsfrist beginnen soll.';
      }

      if ($seite == 'agb' && is_file(SHOP_PATH.'/classes/pdf/agb_'.$lang.'.pdf')) {
         $pdf_link = SHOP_URL.'/classes/pdf/agb_'.$lang.'.pdf';

      }

      if ($seite == 'versand' && is_file(SHOP_PATH.'/classes/pdf/versand_'.$lang.'.pdf')) {
         $pdf_link = SHOP_URL.'/classes/pdf/versand_'.$lang.'.pdf';
      }

      if ($seite == 'kontakt2') { $seite = 'kontakt'; }
      if ($seite == 'homebutton') { $seite = 'starthtml'; }

      $data  = $this->db->querySingleObject("SELECT s.id, s.art, s.lang, s.text, s.name, s.check, k.keywords, k.description, k.titeltag
                                                FROM #__seiten AS s
                                             LEFT JOIN #__keywords AS k
                                                ON k.lang = s.lang AND s.art = k.seite
                                             WHERE s.lang = '$lang' AND s.art = '$seite'");

      // Plus DSGVO bei Datenschutz
      $data2 = ($seite == 'datenschutz' ? $this->db->querySingleObject("SELECT * FROM #__seiten WHERE art = 'ds_gvo' AND lang = '$lang'") : '');

      require_once ADMIN_PATH.'/templates/popup_seiten.tpl.php';

      echo json_encode(['status' => 'ok', 'html' => $html]);
      exit;
   }

   // Popup Sitemap anzeigen
   // 28.01.2020
   public function popupSitemap() {
      $html = '';
      require_once ADMIN_PATH.'/templates/popup_sitemap.tpl.php';

      exit(json_encode(['status' => 'ok', 'html' => $html]));
   }

   // Popup Editor in DB speichern
   // 04.05.2019
   private function savePopup() {
      $lang           = $this->params->selected_lang;
      $seite          = $this->params->postString('seite');
      $text           = $this->params->postString('text', '', 'sql');
      $name           = $this->params->postString('title_name');
      $check          = $this->params->postCheckbox('check');
      $widerruf_check = $this->params->postCheckbox('widerruf_check');
      $pdf            = Control::getPdfWiderruf();
      $title          = '';

      // Text speichern
      $seiten_id = (int)$this->db->querySingleValue("SELECT `id` FROM #__seiten WHERE lang = '$lang' and `art` = '$seite'");

      // Eintrag vorhanden
      if ($seiten_id > 0) {
         if ($seite == 'starthtml') {
            $this->db->query("UPDATE #__seiten SET `check` = '$check' WHERE id = $seiten_id");
         }

         else {
            $this->db->query("UPDATE #__seiten SET `text`  = '".$this->db->escape($text)."',
                                                   `name`  = '".$this->db->escape(urldecode($name))."',
                                                   `check` = '$check'
                              WHERE id = $seiten_id");
         }
      }

      // Neuer Eintrag
      else {
         $this->db->query("INSERT INTO #__seiten VALUES (NULL, '$seite', '$lang', '".$this->db->escape($text)."', '$name', '$check')");
      }

      // Name bei überuns1 - 5 aktualisieren
      if(strstr($seite, 'ueberuns') !== false) {
         $title = $name;
      }

      // Name bei Widerruf - aktualisieren
      if(strstr($seite, 'widerruf') !== false) {
         $title = 'Widerrufsrecht '.$name;
      }

      // Zusätzlich bei einzelnen Seiten
      if ($seite == 'datenschutz') {
         $telefon_aktiv = $this->params->postCheckbox('telefon_aktiv');
         $this->db->query("UPDATE #__firma SET telefon_aktiv = '$telefon_aktiv'");

         $check  = $this->params->postCheckbox('ds_gvo_check');
         $text   = $this->params->postString('ds_gvo_text', '', 'sql');

         $this->db->query("INSERT INTO #__seiten VALUES (NULL, 'ds_gvo', '$lang', '".$this->db->escape($text)."', '$name', '$check')
                              ON DUPLICATE KEY UPDATE `text` = '".$this->db->escape($text)."', `name` = '".$this->db->escape($name)."', `check` = '$check'");
      }

      if ($seite == 'kundeninfo') {
         $kundeninfo_check = $this->params->postCheckbox('kundeninfo_check');
         $schlichtung_check = $this->params->postCheckbox('schlichtung_check');

         $this->db->query("UPDATE #__firma2 SET kundeninfo_check = '$kundeninfo_check', schlichtung_check = '$schlichtung_check'");
      }

      // Title, Keywords, Description
      if ($seite == 'agb' || $seite == 'kontakt' || $seite == 'impressum' || $seite == 'datenschutz' || $seite == 'versand' || $seite == 'starthtml' || strstr($seite, 'ueberuns') != false) {
         $t     = $this->params->postString('titletag', '', "sql");
         $d     = $this->params->postString('description', "", "sql");
         $k     = $this->params->postString('keywords', '', "sql");

         $sql = "INSERT INTO #__keywords
                    VALUES ('$lang', '$seite', '$t', '$k', '$d')
                 ON DUPLICATE KEY UPDATE
                    `titeltag` = '$t', `keywords` = '$k', `description` = '$d'";
         $this->db->query($sql);
      }

      // Inhaberdaten Kontakt
      if ($seite == 'kontakt') {
         $kontakt_inhaber = $this->params->postCheckbox('inhaber_check');
         $this->db->query("UPDATE #__firma2 SET kontakt_inhaber = '$kontakt_inhaber'");
      }

      // Inhaberdaten Impressum
      if ($seite == 'impressum') {
         $impressum_inhaber = $this->params->postCheckbox('inhaber_check');
         $this->db->query("UPDATE #__firma2 SET impressum_inhaber = '$impressum_inhaber'");
      }

      // Widerruf Formular
      if (strstr($seite, 'widerruf') !== false) {
         $form_check = $this->params->postCheckbox('widerruf_check');
         $this->db->query("UPDATE #__firma2 SET ".$seite."_form = '$form_check' WHERE id = 1");
      }

      // PDF-Seite erstellen
      if ($seite == 'versand') {
         $pdf->makePdf('versand', $text, $lang, 4);
      }

      if ($seite == 'agb') {
         $pdf->makePdf('agb', $text, $lang, 4);
      }

      if ($seite == 'widerruf1') {
         $pdf->makePdf('WiderrufA', $text, $lang, 1);
      }

      if ($seite == 'widerruf2') {
         $pdf->makePdf('WiderrufB', $text, $lang, 2);
      }

      if ($seite == 'widerruf3') {
         $pdf->makePdf('WiderrufC', $text, $lang, 3);
      }

      if ($seite == 'widerruf4') {
         $pdf->makePdf('WiderrufD', $text, $lang, 4);
      }

      if ($seite == 'widerruf5') {
         $pdf->makePdf('WiderrufE', $text, $lang, 5);
      }

      $this->sitemap();

      exit(json_encode(['status' => 'ok', 'title' => $title]));
   }

   // Popup Sitemap in DB speichern
   // 28.01.2020
   private function savePopupSitemap() {
      // alte Einstellungen speichern
      $status = $this->db->querySingleObject("SELECT sitemap_menu, sitemap_agb, sitemap_cat, sitemap_cat_lev1, sitemap_cat_lev2, sitemap_articles, sitemap_xml FROM #__firma2 WHERE id = 1");

      // Einstellungen aktualisieren
      $this->db->query("UPDATE #__firma2 SET
                           sitemap_menu     = '".$this->params->postCheckbox('sitemap_menu')."',
                           sitemap_agb      = '".$this->params->postCheckbox('sitemap_agb')."',
                           sitemap_cat      = '".$this->params->postCheckbox('sitemap_cat')."',
                           sitemap_cat_lev1 = '".$this->params->postCheckbox('sitemap_cat_lev1')."',
                           sitemap_cat_lev2 = '".$this->params->postCheckbox('sitemap_cat_lev2')."',
                           sitemap_articles = '".$this->params->postCheckbox('sitemap_articles')."',
                           sitemap_title    = '".$this->params->postCheckbox('sitemap_title')."',
                           sitemap_xml      = '".$this->params->postCheckbox('sitemap_xml')."'
                        WHERE id = 1");

      // Daten neu einlesen
      $this->params->getFirmData();

      $sitemap = Control::getSitemap();
      $sitemap->check($status);

      exit(json_encode(['status' => 'ok']));
   }

   // Seite aktivieren / deaktivieren (aus shop_firma2)
   // 04.05.2019
   private function active() {
      $seite  = $this->params->postString('seite');
      $active = $this->params->postCheckbox('active');

      $this->db->query("UPDATE #__firma2 SET ".$seite."_check = '$active' WHERE id = 1");
      $last_sql = $this->db->last_sql;
      $this->params->getFirmData();

      $check = $this->params->firma[$seite.'_check'];

      if ($check == $active) {
         $this->params->getFirmData();
         // Bei Sitemap Daten erstellen oder löschen
         if ($seite == 'sitemap') {
            $sitemap = Control::getSitemap();
            $sitemap->status($active);
         }

         echo(json_encode(['status' => 'ok', 'sql' => $last_sql]));
         $this->sitemap();
         exit;
      }

      exit(json_encode(['status' => 'failed', 'msg' => 'Einstellung konnte nicht gespeichert werden.', 'sql' => $last_sql]));
   }

   // Bilder Seiten und Danke-Seite speichern
   // 04.05.2019
   private function upload() {
      $picnr    = $this->params->postInt('param1');
      $uns      = $this->params->postInt('param2');
      $lang     = $this->params->selected_lang;

      $dir      = TEMPLATE_PATH.'/images/';
      $img_url  = TEMPLATE_URL.'/images/';
      $filename = 'tmp_'.time();

      // Namen aus $_FILES lesen
      $tempfile = $_FILES['file']['tmp_name'];


      if ($uns != 0) {
         // Überuns 1 - 5
         if ($uns < 11) {
            $filename = 'uns'.$uns.'_'.$picnr.'_'.$lang;
         }

         // Impressum
         else if ($uns == 11) {
            $filename = 'impressum'.$picnr.'_'.$lang;
         }

         // Kontakt
         else if ($uns == 12) {
            $filename = 'kontakt'.$picnr.'_'.$lang;
         }

         // Danke-Bilder
         else {
            $filename = 'danke'.$picnr.'_'.$lang.'.jpg';
            move_uploaded_file($tempfile, $dir.$filename);

            // Sprach-unabhängiges Bild löschen
            @unlink($dir.'danke'.$picnr.'.jpg');

            Helper::resizePic($dir.$filename, $dir.$filename, 0, 0, 'jpg', false );
            exit(json_encode(['status' => 'ok', 'html' => $img_url.$filename.'?'.time(), 'target' => 'img_src']));
         }
      }

      move_uploaded_file($tempfile, $dir.$filename.'.jpg');

      Helper::resizePicCenter($dir.$filename.'.jpg', $dir.$filename.'.jpg', 591, 370, 'jpg' );
      Helper::resizePicCenter($dir.$filename.'.jpg', $dir.$filename.'_tn.jpg', 78, 78, 'jpg' );

      Helper::setData('ueberuns'.$uns.'_'.$lang.'_image'.$picnr, $filename);

      echo json_encode(['status' => 'ok', 'html' => $img_url.$filename.'_tn.jpg?'.time(), 'target' => 'img_src']);
      exit;
   }

   // Bild Seite oder Danke-Seite löschen
   // 04.05.2019
   private function deleteImg() {
      $dir      = TEMPLATE_PATH.'/images/';
      //$filename = $this->params->postString('filename');
      $data     = $this->params->postString('data');
      $filename = '';

      if ($data == 'danke1' || $data == 'danke2') {
         @unlink($dir.$data.'.jpg');
         $filename = $data.'_'.$this->params->selected_lang;
      }

      else {
         $filename = Helper::getData($data, '');
         Helper::setData($data, '');
      }

      @unlink($dir.$filename.'.jpg');
      @unlink($dir.$filename.'_tn.jpg');


      exit(json_encode(['status' => 'ok']));
   }

   // Editor Popup-Popup Links speichern
   // 04.05.2019
   private function _saveLinks() {
      // $lang    = $this->params->postString('lang');
      $lang    = $this->params->selected_lang;
      $seite   = $this->params->postString('seite');
      $elem_id = $this->params->postInt('elem_id');
      $intern  = $this->params->postCheckbox('intern');
      $link    = $this->params->postString('link');
      $seo     = $this->params->postString('seo');

      // Link immer mit http:// speichern
      if ($link != '' && substr($link, 0, 4) != 'http') {
         $link = 'http://'.$link;
      }

      if ($seite !== 'danke') {
         Helper::setData($seite.'_'.$lang.'_link'.$elem_id, $link);
         Helper::setData($seite.'_'.$lang.'_intern'.$elem_id, $intern);
         Helper::setData($seite.'_'.$lang.'_seo'.$elem_id, $seo);
      }

      else {
         $link_id = $this->params->storeLinks();
         $links = $link.'|'.$intern.'|'.$seo;
         $this->db->query("UPDATE #__links SET $seite$elem_id = '$links' WHERE id = $link_id");

//         $this->params->storeLinks($links, $lang);
      }

      echo json_encode(['status' => 'ok']);
   }

   // Popup Conversion und Tracking-Code anzeigen
   // 04.05.2019
   private function _loadConversion() {
      $script   = '';
      $tracking = '';

      if (file_exists(TEMPLATE_PATH.'/save/save/conversion.inc.php')) {
         $script = file_get_contents(TEMPLATE_PATH.'/save/save/conversion.inc.php');
      }

      if (file_exists(TEMPLATE_PATH.'/save/save/trackingcode.txt')) {
         $tracking = file_get_contents(TEMPLATE_PATH.'/save/save/trackingcode.txt');
      }

      $html = '';
      require_once SHOP_PATH.'/classes/modules/conversion_code/popup_conversion.tpl.php';

      echo json_encode(['status' => 'ok', 'html' => $html]);
      exit;
   }

   // Conversion und Tracking-Code speichern
   // 04.05.2019
   private function _saveConversion() {
      if (!is_dir(TEMPLATE_PATH.'/save')) {
         mkdir(TEMPLATE_PATH.'/save');
      }

      if (!is_dir(TEMPLATE_PATH.'/save/save')) {
         mkdir(TEMPLATE_PATH.'/save/save');
         file_put_contents(TEMPLATE_PATH.'/save/save/.htaccess', 'deny from all'.CR.'ErrorDocument 403 "<h1>Zugriff gesperrt</h1>"'.CR);
      }

      $script = $this->_checkInput($this->params->postString('script'));
      file_put_contents(TEMPLATE_PATH.'/save/save/conversion.inc.php', $script);

      $tracking = $this->_checkInput($this->params->postString('tracking'));
      file_put_contents(TEMPLATE_PATH.'/save/save/trackingcode.txt', $tracking);

      exit(json_encode(['status' => 'ok']));
   }

   // TrustetShops ID speichern
   // 04.05.2019
   private function _saveTrustedshops() {
      $trusted_id = $this->_checkInput($this->params->postString('trustedshop'));
      $this->db->query("UPDATE #__firma SET trustedshop = '".$this->db->escape($trusted_id)."' WHERE id = 1");

      // Gespeicherten Code zurück geben
      $this->params->getFirmData();
      exit(json_encode(['status' => 'ok', 'code' => $this->params->firma['trustedshop']]));
   }

   // Form, Input bei Eingabe unterbinden
   // 04.05.2019
   private function _checkInput($input) {
      $back = str_replace(['<input', '<form'], '', $input);

      return $back;
   }

   // Status (active y/n auslesen wenn vorhanden, sonst $check
   // 30.08.2019
   public function _check($seite, $check) {
      $checked = '';

      if (isset($this->params->firma[$seite.'_check'])) {
         $checked = $this->params->firma[$seite.'_check'];
      }

      else {
         $checked = $check;
      }

      return $checked;
   }

   // Seitennamen für Anzeige ersetzen
   // 30.08.2019
   public function _checkName($seite, $name) {
      if ($seite == 'homebutton') { $name = 'Home'; }
      if ($seite == 'kontakt') { $name = 'Kontakt'; }
      if ($seite == 'impressum') { $name = 'Impressum'; }
      if ($seite == 'datenschutz') { $name = 'Datenschutzerklärung'; }
      if ($seite == 'kontakt2') { $name = 'Kontakt'; }
      if ($seite == 'anmelden') { $name = 'Anmelden'; }
      if ($seite == 'versand') { $name = 'Zahlung & Versand'; }
      if ($seite == 'agb') { $name = 'AGB & Kundeninfo'; }
      if ($seite == 'kundeninfo') { $name = 'Kundeninformation'; }

      if (strstr($seite, 'widerruf') !== false) {
         $name = 'Widerrufsrecht '.$name;
      }

      return $name;
   }

   public function sitemap($old_menu = '', $old_agb = '') {
      $kontakt_show = true;

      // Keine Änderung
      if ($old_agb == $this->params->firma['sitemap_menu'] && $old_agb == $this->params->firma['sitemap_agb']) {
         return;
      }

      if ($this->params->firma['sitemap_menu'] == 'y' || $this->params->firma['sitemap_agb'] == 'y') {
         $html  = '';
         $xml   = '';
         $datum = date('Y-m-d');
         $lang  = $this->params->default_lang;

         // Sitemap Menü
         $html .= '<div class="seiten_block">';

         foreach (SEITEN1 as $seite) {
            if ($seite == 'homebutton' || $this->params->firma[$seite.'_check'] != 'y') {
               continue;
            }

            if ($seite == 'kontakt') {
               $kontakt_show = false;
            }

            $name = '';
            $link = SHOP_URL_IDX.'/'.$seite;

            if (substr($seite, 0, 8) == 'ueberuns') {
               $name = \KANPAICLASSIC\Helper::getUeberUns((int)substr($seite, 8, 1));
               $link = $this->params->getLink(\KANPAICLASSIC\Helper::checkLink($name));
            }

            else if (substr($seite, 0, 8) == 'widerruf') {
               $name = \KANPAICLASSIC\Helper::getWiderruf((int)substr($seite, 8, 1));
            }

            else {
               $name = $this->text->get('menu', $seite, $lang);
            }

            $html .= '<div class="seite" title="'.$this->db->querySingleValue("SELECT titeltag FROM #__keywords WHERE lang = '$lang' AND seite = '$seite'").'">'.CR;
            $html .= '   <a href="'.$link.'"><span class="fliesstext text_normal ellipsis">'.$name.'</span></a>'.CR;
            $html  .= '</div>';

            $xml  .= '   <url>'."\n";
            $xml  .= '      <loc>'.$link.'</loc>'."\n";
            $xml  .= '      <lastmod>'.$datum.'</lastmod>'."\n";
            $xml  .= '      <changefreq>weekly</changefreq>'."\n";
            $xml  .= '      <priority>0.8</priority>'."\n";
            $xml  .= '   </url>'."\n";
         }

         $html  .= '</div>';

         // Sitemap AGB etc.
         $html .= '<div class="seiten_block">';

         foreach (SEITEN2 as $seite) {
            if ($this->params->firma[$seite.'_check'] != 'y' || $seite == 'kontakt2' && !$kontakt_show) {
               continue;
            }

            $name2 = '';

            if ($seite == 'kontakt2') {
               $seite = 'kontakt';
            }

            if ($seite == 'anmelden') {
               $seite = 'login';
            }

            $name = '';
            $link = SHOP_URL_IDX.'/'.$seite;

            if (substr($seite, 0, 8) == 'ueberuns') {
               $name = \KANPAICLASSIC\Helper::getUeberUns((int)substr($seite, 8, 1));
               $link = $this->params->getLink(\KANPAICLASSIC\Helper::checkLink($name));
            }

            else if (substr($seite, 0, 8) == 'widerruf') {
               $name = \KANPAICLASSIC\Helper::getWiderruf((int)substr($seite, 8, 1));
            }

            else if ($seite== 'login') {
               $name  = $this->text->get('menu', $seite, $lang);
               $name2 = $this->text->get('menu', 'konto', $lang);
            }

            else {
               $name = $this->text->get('menu', $seite, $lang);
            }

            $html .= '<div class="seite hide_'.$seite.'" title="'.$this->db->querySingleValue("SELECT titeltag FROM #__keywords WHERE lang = '$lang' AND seite = '$seite'").'">'.CR;
            $html .= '   <a href="'.$link.'"><span class="fliesstext text_normal ellipsis">'.$name.'</span></a>'.CR;
            $html  .= '</div>';

            if ($name2 != '') {
               $html .= '<div class="seite hide_konto">'.CR;
               $html .= '   <a href="'.SHOP_URL_IDX.'/konto"><span class="fliesstext text_normal ellipsis">'.$name2.'</span></a>'.CR;
               $html  .= '</div>';
            }

            $xml  .= '   <url>'."\n";
            $xml  .= '      <loc>'.SHOP_URL_IDX.'/'.$seite.'</loc>'."\n";
            $xml  .= '      <lastmod>'.$datum.'</lastmod>'."\n";
            $xml  .= '      <changefreq>weekly</changefreq>'."\n";
            $xml  .= '      <priority>0.8</priority>'."\n";
            $xml  .= '   </url>'."\n";
         }

         $html  .= '</div>';
         $html .= '<div class="seiten_block">';

         foreach (SEITEN3 as $seite) {
            if ($this->params->firma[$seite.'_check'] != 'y') {
               continue;
            }

            $name = '';
            $link = $seite;

            if (substr($seite, 0, 8) == 'ueberuns') {
               $name = \KANPAICLASSIC\Helper::getUeberUns((int)substr($seite, 8, 1));
            }

            else if (substr($seite, 0, 8) == 'widerruf') {
               $name = \KANPAICLASSIC\Helper::getWiderruf((int)substr($seite, 8, 1));
               $link = $this->params->getLink(\KANPAICLASSIC\Helper::checkLink($seite));
            }

            else {
               $name = $this->text->get('menu', $seite, $lang);
            }

            $html .= '<div class="seite" title="'.$this->db->querySingleValue("SELECT titeltag FROM #__keywords WHERE lang = '$lang' AND seite = '$seite'").'">'.CR;
            $html .= '   <a href="'.SHOP_URL_IDX.'/'.$seite.'"><span class="fliesstext text_normal ellipsis">'.$name.'</span></a>'.CR;
            $html  .= '</div>';

            $xml  .= '   <url>'."\n";
            $xml  .= '      <loc>'.SHOP_URL_IDX.'/'.$seite.'</loc>'."\n";
            $xml  .= '      <lastmod>'.$datum.'</lastmod>'."\n";
            $xml  .= '      <changefreq>weekly</changefreq>'."\n";
            $xml  .= '      <priority>0.8</priority>'."\n";
            $xml  .= '   </url>'."\n";
         }

         $html  .= '</div>';

         \file_put_contents(SHOP_PATH.'/sitemap_seiten.html', $html);
         \file_put_contents(SHOP_PATH.'/sitemap_seiten.xml', $xml);
      }

      else {
         @unlink(SHOP_PATH.'/sitemap_seiten.html');
         @unlink(SHOP_PATH.'/sitemap_seiten.xml');
      }

      $sitemap = Control::getSitemap();
      $sitemap->sitemapXml();
   }

   private function headerPopup() {
      require_once ADMIN_PATH.'/classes/designTemplate.class.php';
      $design = new KANPAICLASSIC_designTemplate();

      $design->loadHeaderscript();
      exit;
   }

   private function headerSave() {
      require_once ADMIN_PATH.'/classes/designTemplate.class.php';
      $design = new KANPAICLASSIC_designTemplate();

      $design->loadHeaderscript();
      exit;
   }

   private function cookiePopup() {

      require_once ADMIN_PATH.'/classes/designTemplate.class.php';
      $design = new KANPAICLASSIC_designTemplate();

      $design->loadCookiePopup();
      exit;
   }





   private function cookieSave() {
      require_once ADMIN_PATH.'/classes/designTemplate.class.php';
      $design = new KANPAICLASSIC_designTemplate();

      $design->loadCookiePopup
         ();
      exit;
   }

   

}


