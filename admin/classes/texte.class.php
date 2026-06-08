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

class KANPAICLASSIC_texte
{
   private $db;
   private $params;
   private $text;
   private $art1            = '';
   private $art2            = '';
   private $data_text1      = [];
   private $data_text2      = [];
   private $gutschein_aktiv = false;
//   private $download_aktiv  = true;


   public $langdata = '';

   function __construct() {
      $this->db     = Control::getDB();
      $this->params = Control::getParams();
      $this->text   = Control::getText();

      $this->art1 = "'anmeldung',    'pw_vergessen',
                     'anfrage_best', 'best_best',
                     'best_admin',   'rechnung',
                     'bewertung',    'download',";

      // In config.inc.php
      if (defined('CONF_WIDERRUF_DL')) {
         $this->download_aktiv = true;
         $this->art1 .= "'widerruf_dl_no', 'widerruf_dl_yes', ";
      }

      $this->art1 .= "'gutschrift', 'lastschrift'";

      $this->gutschein_aktiv = true;
      $this->art2 .= "'newsletter', 'anmeldung_nl',
                      'gutschein1', 'gutschein2',
                      'gutschein3', 'gutschein4',
                      'gutschein5'";

   }

   // Auswahl der Funktionen
   public function getContent() {
      // Mail-Texte speichern
      // 24.04.2019
      if ($this->params->func == 'update') {
         $this->_writeData();
         Helper::setData('mail_footer_check', $this->params->postCheckbox('mail_footer_check'));
         Helper::setData('mail_attach_images_mail', $this->params->postCheckbox('mail_attach_images_mail'));
         header('Location: '.ADMIN_URL_IDX.'/texte');
         exit;
      }

      // Systemtexte aus /admin/reset_system_textesql neu erstellen
      // 24.04.2019
      else if ($this->params->func == 'reset') {
         $this->_resetData();

         header('Location: '.ADMIN_URL_IDX.'/texte');
         exit;
      }

      // Image für Mailheader hochladen
      // 24.04.2019
      else if ($this->params->func == 'headerUpload') {
         $this->_headerUpload();
         // Exit in Aufruf
      }

      // Image für Mailheader löschen
      // 24.04.2019
      else if ($this->params->func == 'headerDelete') {
         @unlink(TEMPLATE_PATH.'/images/mailheader.png');

         echo json_encode(['status' => 'ok']);
         exit;
      }

      // Seite anzeigen
      // 24.04.2019
      else {
         $this->_getData();
      }

      include ADMIN_PATH.'/templates/texte.tpl.php';
      return;
   }

   // Mail-Texte aus DB lesen, gewählten Sprache (Gutscheine getrennt -> data_text2)
   // 24.04.2019
   private function _getData() {
      $data_text1 = $this->db->queryAllObjects("SELECT `id`, `art`, `lang`, `betreff`, `text` FROM #__system_texte WHERE lang = '".$this->params->selected_lang."' AND art IN ($this->art1)");
      $art1_arr   = explode(',', str_replace([' ', "\n"], [''], $this->art1));

      for ($i = 0; (is_array($art1_arr) ? $i < count($art1_arr) : 0); $i++) {
         for ($j = 0; $j < (is_array($data_text1) ? count($data_text1) : 0); $j++) {
            $test = substr($art1_arr[$i], 1 , -1);

            if ($test == $data_text1[$j]->art) {
              $this->data_text1[] = $data_text1[$j];
              continue;
            }
         }
      }

      $data_text2 = $this->db->queryAllObjects("SELECT `id`, `art`, `lang`, `betreff`, `text` FROM #__system_texte WHERE lang = '".$this->params->selected_lang."' AND art IN ($this->art2)");
      $art2_arr   = explode(',', str_replace([' ', "\n"], '', $this->art2));

      for ($i = 0; $i < (is_array($art2_arr) ? count($art2_arr) : 0); $i++) {
         for ($j = 0; $j < (is_array($data_text2) ? count($data_text2) : 0); $j++) {
            $test = substr($art2_arr[$i], 1 , -1);

            if ($test == $data_text2[$j]->art) {
              $this->data_text2[] = $data_text2[$j];
              continue;
            }
         }
      }

      return;
   }

   // Mail-Texte in DB schreiben Schleife
   // 24.04.2019
   private function _writeData() {
      $texte  = $this->art1;
      $texte .= ', '.$this->art2;

      $texte = explode(',', str_replace([' ', "\n"], [''], $texte));

      foreach ($texte as $art) {
         $art     = str_replace("'", '', trim($art));
         $text    = $this->db->escape($this->params->postString($art, '', 'sql'));
         $betreff = $this->db->escape($this->params->postString($art.'_betr', '', 'sql'));

         $this->_writeSql($art, $text, $betreff);
      }
   }

   // Mail-Texte in DB schreiben
   // 24.04.2019
   private function _writeSql($art, $text, $betreff) {
      $lang = $this->params->selected_lang;

      $test = $this->db->querySingleObject("SELECT id FROM #__system_texte WHERE lang = '$lang' AND art = '$art'");

      if ($test) {
         $this->db->query("UPDATE #__system_texte SET `betreff` = '$betreff', `text` = '$text' WHERE id = $test->id");
      }

      else {
         $this->db->query("INSERT INTO #__system_texte VALUES (NULL, '$art', '$lang', '$betreff', '$text')");
      }

      return;
   }

   // Stand Auslieferungszustand wieder herstellen
   // 24.04.2019
   private function _resetData() {
      if (defined('CONF_MODULE_PORTAL')) {
         $sql = file_get_contents(ADMIN_PATH.'/reset_system_texte.sql');
      }
      else {
         $sql = file_get_contents(ADMIN_PATH.'/reset_system_texte.sql');
      }

      $this->db->mquery($sql);
   }

   // Upload Header
   // 25.04.2019
   private function _headerUpload() {
      $tmp_name = $_FILES['file']['tmp_name'];
      $filename = 'mailheader.png';

      @unlink(TEMPLATE_PATH.'/images/mailheader.jpg');

      move_uploaded_file($tmp_name, TEMPLATE_PATH.'/images/'.$filename);

      echo json_encode(['status' => 'ok', 'target' => 'src', 'html' => TEMPLATE_URL.'/images/'.$filename.'?'.time()]);
      exit;
   }

   private function getName($art) {
      $name = '';

      if ($art == 'anmeldung')       { $name = 'Anmeldung'; }
      if ($art == 'pw_vergessen')    { $name = 'Passwort vergessen'; }
      if ($art == 'anfrage_best')    { $name = 'Anfragebestätigung an Kunden'; }
      if ($art == 'best_best')       { $name = 'Bestellbestätigung an Kunden'; }
      if ($art == 'best_admin')      { $name = 'Bestellungs-E-Mail an Admin'; }
      if ($art == 'rechnung')        { $name = 'Versandbestätigung'; }
      if ($art == 'bewertung')       { $name = 'Bewertungs-E-Mail'; }
      if ($art == 'download')        { $name = 'Download-E-Mail'; }
      if ($art == 'lastschrift')     { $name = 'Lastschrift ohne PDF'; }
      if ($art == 'widerruf_dl_no')  { $name = 'Kein Verzicht auf Widerruf bei Dienstleistungen'; }
      if ($art == 'widerruf_dl_yes') { $name = 'Verzicht auf Widerruf bei Dienstleistungen'; }
      if ($art == 'gutschrift')      { $name = 'Gutschrift-E-Mail'; }

      if ($art == 'anmeldung_nl')    { $name = 'Newsletter-Verifizierung'; }
      if ($art == 'newsletter')      { $name = 'Newsletter-Einwilligung'; }
      if ($art == 'gutschein1')      { $name = 'Gutschein-Newsletter 1'; }
      if ($art == 'gutschein2')      { $name = 'Gutschein-Newsletter 2'; }
      if ($art == 'gutschein3')      { $name = 'Gutschein-Newsletter 3'; }
      if ($art == 'gutschein4')      { $name = 'Gutschein-Newsletter 4'; }
      if ($art == 'gutschein5')      { $name = 'Coupon'; }

      return $name;
   }
}
