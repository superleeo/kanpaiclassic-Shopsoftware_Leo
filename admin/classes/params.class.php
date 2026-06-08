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
define('LOGIN_GESPERRT', 'Achtung: Admin-Login wurde wegen zu vieler Fehlversuche für 24 Stunden gesperrt.<br>Wenn die Sperrung aktiv ist, können Sie auch die Passwort-Vergessen-Funktion verwenden, um Ihr Passwort neu zu vergeben und damit vorzeitig zu entsperren.');
define('LOGIN_FAILED',   'Achtung: Admin-Login mit falschem Passwort.');

if (!defined('KANPAICLASSIC')) {
   die ("This file cannot run outside the Kanpai Classic Shopsoftware");
}

if (defined('INSTALL')) {
   include '../../classes/base/params_base.class.php';
}

class KANPAICLASSIC_params extends KANPAICLASSIC_session
{
   public  $task          = '';
   public  $func          = '';
   public  $add_params    = '';
   // Ersetzen
   public  $isAjax        = false;
   public  $is_ajax       = false;
   public  $menu_item     = 0;
   public  $user_id       = 0;
   public  $token         = '';
   public  $params3       = '';
   public  $loginerror    = 0;

   public  $logged_in     = false;
   private $allowed_tasks = "login logout home bestellungen kunden artikel kategorien seiten designTemplate designGeschaeftspapier designExtended designColors "
                           ."toolsFunktionen toolsGutscheine toolsSchnittstellen toolsFoto toolsRabattgruppen toolsBackup "
                           ."einstellungen shopinhaber versandart zahlungsart lagerhaltung steuer_gewerbe laender texte "
                           ."haendler designAdmin designLivedesigner";

   // Für Livedesigner:
   public $html5_mode          = '';

   public function __construct() {
      $this->isAdmin = true;
      parent::__construct();

      $server_name = $_SERVER['SERVER_NAME'];

      if (defined('CONF_USE_HTTP_HOST')) {
         $server_name = $_SERVER['HTTP_HOST'];
      }


      if (!isset($_SESSION['haendler'])) {
         $_SESSION['haendler'] = 'n';
      }

      if (defined('CONF_USEADMIN_HTTPS') && CONF_USEADMIN_HTTPS === true && !isset($_SERVER['HTTPS'])) {
         header('HTTP/1.1 301 Moved Permanently');
         header('Location: https://'.$server_name.$_SERVER['REQUEST_URI']);
      }

      $protocol = 'http://';

      if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
         $protocol = 'https://';
      }

//      $this->baseurl = $protocol.$server_name.$this->basepath.'/admin/index.php';
unset($this->baseurl);
unset($this->basepath);
unset($this->filepath);
unset($this->shopurl);
unset($this->linkurl);
      $this->check_defaultlang();
      $this->getImage();
   }

   // Sprache für Backend festlegen
   private function setDefaultLang() {
      $this->default_lang  = 'deu';
      $this->selected_lang = 'deu';
      $_SESSION['lang']    = 'deu';
   }


   // Parameter nach index.php/../.. auswerten
   public function get_functions() {
      if (URL_PARAMS != '') {
         // .map verhindern
         if (substr(URL_PARAMS, -4) === '.map') {
            exit;
         }

         $funcs = explode('/', URL_PARAMS);
         $count = count($funcs);

//         if ($funcs[0] == 'check_redirect') {
//            file_put_contents(dirname(__DIR__).'/is_admin_redirect', '');
//            exit (dirname(__DIR__).'/is_admin_redirect');
//         }

         // /admin überspringen
         if ($funcs[0] == 'admin') {
            $count--;
            array_shift($funcs);
         }

         // index.php überspringen
         if ($funcs[0] == 'index.php') {
            $count--;
            array_shift($funcs);
         }

         // Startseite
         if (!isset($funcs[0]) || $funcs[0] == '') {
            $this->task = '';
            $_SESSION['task'] = $this->task;
            return;
         }

         // Bei AJAX wird ajax vorangestellt
         if ($funcs[0] == 'ajax') {
            $this->isAjax = true;
            $this->is_ajax = true;
            $count--;
            array_shift($funcs);
         }

         if ($funcs[0] == 'login') {
//            $this->adminLogin();
            return;
         }

         // Sprache ändern
         if ($funcs[0] == 'lang') {
            $count--;
            array_shift($funcs);
            $this->selected_lang = $funcs[0];
            $count--;
            array_shift($funcs);

            $_SESSION['lang']         = $this->selected_lang;
            $_SESSION['lang_changed'] = true;
            $this->task               = $_SESSION['task'];
            $this->func               = $_SESSION['func'];

            $add_params = (is_array($_SESSION['add_params']) ? '/'.implode('/', $_SESSION['add_params']) : '');
            header('Location: '.ADMIN_URL_IDX.'/'.$this->task.'/'.$this->func.$add_params);
            exit;
         }

         // Weitere Parameter vorhanden? - Task
         if ($count > 0) {
            if ($this->checkTask($funcs[0])) {
               $this->task = $funcs[0];

               if (!$this->isAjax) {
                  $_SESSION['task'] = $this->task;
               }

               array_shift($funcs);
            }
         }

         // Sonst Startseite
         else {
            $this->task = '';
            $_SESSION['task'] = '';
         }

         // 2. Parameter vorhanden? - Func
         if ($count > 1) {
            $this->func = $funcs[0];

            if (!$this->isAjax) {
               $_SESSION['func'] = $this->func;
            }

            array_shift($funcs);
         }

         else {
            $this->func = '';
            $_SESSION['func'] = '';
         }

         // Mehr als 2 Parameter
         if ($count > 2) {
            if ($this->func == 'catedit') {
               $_POST['catid'] = $funcs[0];
            }

            // Nicht bei AJAX-Funktionen
            if (!$this->isAjax) {
               $this->add_params       = $funcs;
               $_SESSION['add_params'] = $funcs;
            }

            $this->params3 = $funcs[0];
         }

         // Gespeicherte Zusatzparameter löschen
         else if (!$this->isAjax) {
            $this->add_params       = '';
            $_SESSION['add_params'] = '';
         }
      }

      else {
         $this->task = '';
         $this->func = '';
      }
   }

   // Login für Admin prüfen
   public function adminLogin() {
      $this->db->query("DELETE FROM #__admin_logins WHERE UNIX_TIMESTAMP(time) < '".(time() - (60* 60 * 24))."'");
      // Protokolle älter als 1 Tag löschen

      // Sessions bereinigen
      session_gc();

      session_destroy();
      $this->initSession();

      $username       = $this->postString('username');
      $password       = $this->postString('password');
      $ip             = $_SERVER['REMOTE_ADDR'];

      $this->loginerror = (int)$this->db->querySingleValue("SELECT count(*) FROM #__admin_logins WHERE ip = '$ip'");

      // nach Youtube umleiten
      if ($username == "'=' 'or'") {
         header('Location: https://www.youtube.com/watch?v=d432yl8cQc0');
         exit;
      }

      // Username und Passwort dürfen nicht leer sein
      if ($username != '' && $password != '') {
         $data = $this->db->querySingleObject("SELECT id, name, vorname, nachname, email, role, lang, gesperrt FROM #__users
                                               WHERE name = '".$this->db->escape($username)."' AND password ='".md5($this->db->escape($password))."' AND role < 9");

         // Kein gültiger Eintrag
         if (!$data) {
            $this->db->query("INSERT INTO #__admin_logins SET ip = '$ip', login = '$username', pass = '$password'");
            $this->loginerror++;

            Helper::shopLog('login', LOGIN_FAILED, $_SERVER['REMOTE_ADDR']);

            if (defined('CONF_LOGIN_FAILED')) {
               $mail = Control::getMail();
               $mail->sendNachricht($this->firma['email'], LOGIN_FAILED);
            }
         }

         else if (!defined('CONF_LOGIN_FAILED') || $this->loginerror < CONF_LOGIN_FAILED) {
            $this->loginerror = (int)$this->db->querySingleValue("SELECT count(*) FROM #__admin_logins WHERE ip = '$ip'");

            Helper::shopLog('admin_login', 'OK', $_SERVER['REMOTE_ADDR']);
            $this->db->query("DELETE FROM #__admin_logins WHERE ip = '$ip'");

            $_SESSION['user_id']       = (int)$data->id;
            $this->user_id             = $_SESSION['user_id'];
            $_SESSION['user_name']     = $data->name;
            $_SESSION['user_nachname'] = $data->nachname;
            $_SESSION['user_vorname']  = $data->vorname;
            $_SESSION['user_email']    = $data->email;
            $_SESSION['user_role']     = (int)$data->role;
            $_SESSION['task']          = '';
            $_SESSION['func']          = '';
            $_SESSION['add_params']    = '';

            $this->setDefaultLang();

            // Haendler-Login

            $_SESSION['haendler'] = 'n';

            @unlink(SHOP_PATH.'/export/buchung.lock');
            @unlink(SHOP_PATH.'/export/buchungen.csv');
            $this->sendBewertung();    // -> Redirect
         }


         if ($this->loginerror == 10 && defined('LOGIN_GESPERRT')) {
            Helper::shopLog('login', LOGIN_GESPERRT, $_SERVER['REMOTE_ADDR']);
            $mail = Control::getMail();
            $mail->sendNachricht($this->firma['email'], LOGIN_GESPERRT);
         }

         return;
      }
   }

   public function _checkProvision() {
      if (defined('CONF_MODULE_PORTAL')) {
         include_once '../classes/modules/portal/portal.module.php';
         $portal = CONTROL::getModulePortal();
         $portal->checkProvision();
      }
   }

   private function check_defaultlang() {
      if (!defined('CONF_DEFAULT_LANG')) {
         define('CONF_DEFAULT_LANG', 'deu');
      }

      if ($this->firma['default_lang'] != CONF_DEFAULT_LANG) {
         $this->db->query("UPDATE #__firma SET default_lang = '".CONF_DEFAULT_LANG."' WHERE id = 1");
         $this->firma['default_lang'] = CONF_DEFAULT_LANG;
      }
   }

   // Funktion erlaubt ?
   private function checkTask($task) {
      if (stripos($this->allowed_tasks, $task) !== false) {
         return true;
      }

      return false;
   }

   private function sendBewertung() {
      if (defined('CONF_BEWERTUNG_MODE') && CONF_BEWERTUNG_MODE != 'none') {
         $sql = "SELECT best_id, email FROM #__bewertung WHERE datum < NOW()";
         $anzahl = $this->db->query($sql);
         $data = [];

         for ($i = 0; $i < $anzahl; $i++) {
            $data[$i] = $this->db->getObject();
         }

         $mail = Control::getMail();

         for ($i = 0; $i < count($data); $i++) {
            $mail->sendBewertung($data[$i]->email, $data[$i]->best_id);

            // Siegel_ref wird durch Link-Ersetzung erstellt, falls CONF_MODULE_SHOPSIEGEL aktiv ist
            if ($mail->siegel_ref != '') {
               fopen(SHOPSIEGEL_LINK.'/bestellung/'.$mail->siegel_ref, 'r');
            }

            $this->db->query("DELETE FROM #__bewertung WHERE best_id = ".$data[$i]->best_id);
         }
      }

      // Redirect, da nach Login Class Obadja_Bestellungen (default) nochmals geladen wird aber bereits durch Mail geladen ist.
      header('Location: '.ADMIN_URL);
      exit;
   }
}

