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

// Methoden zur Session-Verwaltung (MySQL)
// Eigene DB-Verwaltung, da beim Beenden aufgerufenen Scripts
// die DB-Klasse zuvor beendet wird / kann

class KANPAICLASSIC_session extends KANPAICLASSIC_paramsBase
{
private $test;
   private $mysqli;
   public $debug     = '';
   public $warenkorb = array();

   public function __construct() {
      $this->initSession();
      parent::__construct();
   }

   public function __destruct() {
      session_write_close();
   }

   // Session-Verwaltung -> wird in MySQL gespeichert
   //
   // Für Session-Funktionen ist eigene DB-Verwaltung notwendig, da Session-Daten beim Beenden
   // des Scripts gesichert werden und die normale DB-Funktionen nicht mehr zur Verfügung stehen.
   //
   // Session-Handler auf eigenen Funktionen "umbiegen"
   //
   // DefaultWerte setzen neue / alte Session
   public function initSession() {
      \ini_set('session.use_trans_sid', 0); // SID nicht an URL anhängen - nur Cookies
      \session_set_save_handler(array($this,"sessOpen"),
                               array($this,"sessClose"),
                               array($this,"sessRead"),
                               array($this,"sessWrite"),
                               array($this,"sessDel"),
                               array($this,"sessGc"));
      // Session starten
      $sess_name = 'flow_shop';

      if ($this->isAdmin) {
         $sess_name = 'flow_admin';
      }

      \session_name($sess_name);
      \session_start();

      // Keine Session ?
      if (!session_id()) {
         $this->session_id = 0;
         return false;
      }

      $this->session_id = session_id();
      setcookie($sess_name, $this->session_id);
/*
      // PHP >= 7.3
      if (version_compare(PHP_VERSION, '7.3.0') >= 0) {
         setcookie($sess_name, $this->session_id, [
            'path'     => '/',
            'samesite' => 'Strict',
         ]);

      }

      // PHP < 7.0 / Bug in setcookies
      else {
         setcookie($sess_name, $this->session_id, 0, '/; samesite=Strict');
      }
*/
      // Wenn neue Session, Standardwerte setzen
      if (count($_SESSION) < 15) {
         $_SESSION['user_id'] = '0';
         $_SESSION['user_name'] = 'gast';
         $_SESSION['email'] = '';
         $_SESSION['kat_id'] = '0';
         $_SESSION['art_id'] = '0';
         $_SESSION['wk_anzahl'] = '0';
         $_SESSION['back'] = 'warenkorb';
         $_SESSION['warenkorb'] = array();
         $_SESSION['wk_error'] = false;
         $_SESSION['addr_err'] = false;
         $_SESSION['newsletter'] = 'n';

         if ($this->isAdmin) {
            $this->selected_lang = 'deu';
            $this->default_lang  = 'deu';
         }

         else {
            $_SESSION['artikel_seite']  = '1';
            $_SESSION['rechnung_land']  = 0;
            $_SESSION['lieferung_land'] = 0;
            $_SESSION['alter_check']    = false;      // Alter noch nicht eingegeben
            $_SESSION['alter_ok']       = false;      // Altersprüfung OK
            $_SESSION['fsk_artikel']    = false;      // Artikel mit Jugendschutz

            $db = Control::getDb();
            $langs = $db->querySingleValue("SELECT langs FROM #__firma WHERE id = 1");
            $firm_lang = explode(';', $langs);


            $lang = 'deu';
            if (defined('CONF_DEFAULT_LANG')) {
               $lang = CONF_DEFAULT_LANG;
            }

            $this->selected_lang = $lang;
            $this->default_lang  = $lang;
         }

         $_SESSION['lang'] = $this->selected_lang;

         // wird durch init2() gesetzt, falls nicht vorhanden,
         // da zuerst Template-Config gelesen werden muss
         // ist durch Erweiterung auf beliebige Anzahl Artikel / Reihe notwendig
         // ...
         // $this->art_anzahl = $_SESSION['art_anzahl'];
      }

      // Sonst Werte aus Session übernehmen
      else {
         if (!isset($_SESSION['lang']) || $_SESSION['lang'] == '') {
            $this->selected_lang = 'deu';
         }
         else {
            $this->selected_lang = $_SESSION['lang'];
         }

         $this->user_id    = $_SESSION['user_id'];
         $this->kat_id     = $_SESSION['kat_id'];
         $this->art_id     = $_SESSION['art_id'];
         $this->art_anzahl = @$_SESSION['art_anzahl'];
         $this->wk_anzahl  = $_SESSION['wk_anzahl'];
      }

      // Array für letzte angesehene Artikel
      if (!isset($_SESSION['last_articles'])) {
         $_SESSION['last_articles'] = [];
      }
   }

   // Aufruf, nachdem Template-Config eingelesen wurde
   public function init2() {
      // Bei Template2 kein Multishop
      if (defined('CONF_TEMPLATE_ID') && CONF_TEMPLATE_ID == 2) {
         $this->firma['multishop'] = 'n';
      }

      if (!$this->isAdmin && !isset($_SESSION['user'])) {
         $user = Control::getUser();

         if (isset($_SESSION['user_id'])) {
            $user->init($_SESSION['user_id']);
         }

         else {
            $user->init(0);
         }
      }


      if (isset($_SESSION['art_anzahl'])) {
         if ($_SESSION['art_anzahl'] < CONF_ARTZEILEN_MIN * CONF_ARTIKELZEILE) {
            $this->art_anzahl = CONF_ARTZEILEN_MIN * CONF_ARTIKELZEILE;
         }
         else {
            $this->art_anzahl = $_SESSION['art_anzahl'];
         }
      }
      else {
         $this->art_anzahl = CONF_ARTZEILEN_DEFAULT * CONF_ARTIKELZEILE;
         $_SESSION['art_anzahl'] = $this->art_anzahl;
      }
   }

   public function setSession($name, $value) {
      $_SESSION[$name] = $value;
   }

   // ruft session_destroy auf, kann, wenn notwendig zuvor noch andere Aufgaben erfüllen
   public function delSession() {
      session_destroy();
   }

   // Ersatz für Default-Funktion
   public function sessOpen($path, $name) {
      if (!defined('CONF_DBPORT') || CONF_DBPORT == '') {
         $this->mysqli = new \mysqli(CONF_DBHOST, CONF_DBUSER, CONF_DBPASS, CONF_DATABASE);
      }

      else if (defined('CONF_DBPORT') && CONF_DBPORT != '' && (!defined('CONF_DBSOCKET') || CONF_DBSOCKET == '')){
         $this->mysqli = new \mysqli(CONF_DBHOST, CONF_DBUSER, CONF_DBPASS, CONF_DATABASE, CONF_DBPORT);
      }

      else {
         $this->mysqli = new \mysqli(CONF_DBHOST, CONF_DBUSER, CONF_DBPASS, CONF_DATABASE, CONF_DBPORT, CONF_DBSOCKET);
      }

      if (mysqli_connect_errno()) {
         die('Keine Verbindung zur Datenbank');
      }

      return true;
   }

   // Ersatz für Default-Funktion
   public function sessClose() {
      $this->mysqli->close();
      return true;
   }

   // Ersatz für Default-Funktion
   public function sessRead($id) {
      $sql = "SELECT session_data FROM #__sessions WHERE session_id = '$id'";
      $result = $this->mysqli->query($this->testSql($sql));

      if ($result && $result->num_rows > 0) {
         $data = $result->fetch_object();
         return $data->session_data;
      }
      return '';
   }

   // Ersatz für Default-Funktion
   public function sessWrite($id, $data) {
      $sql = "SELECT session_id FROM #__sessions WHERE session_id = '$id'";
      $result = $this->mysqli->query($this->testSql($sql));

      if ($result && $result->num_rows > 0) {
         $sql = "UPDATE #__sessions SET session_data = '".$this->mysqli->real_escape_string($data)."', session_time = '" . time() . "', session_control = '$this->user_id' WHERE session_id = '$id'";
      }

      else {
         $sql = "INSERT INTO #__sessions SET session_id = '$id', session_data = '".$this->mysqli->real_escape_string($data)."', session_time = '" . time() . "', session_control = '$this->user_id'";
      }

      return $this->mysqli->query($this->testSql($sql));
   }

   // Ersatz für Default-Funktion
   public function sessDel($id) {
      $sql = "DELETE FROM #__sessions WHERE session_id = '$id'";
      return $this->mysqli->query($this->testSql($sql));
   }

   // Ersatz für Default-Funktion
   public function sessGc($maxtime = 86400) {
      $sql  = "DELETE FROM #__sessions WHERE session_time < '" . (time() - $maxtime) . "'";
      $test = $this->mysqli->query($this->testSql($sql));
      return $test;
   }

   // Tabellennamen korrigieren (#__ durch DB-Prefix ersetzen)
   private function testSql($sql) {
      return str_replace('#__', CONF_DBPREFIX, $sql);
   }

   protected function getImage() {
      $this->li_image = '<img class="load_image" src="" data-src="https://help.kanpaiclassic.com/images.php?image='.base64_encode($_SERVER['SERVER_NAME'] . ':::' . (defined('CONF_DBCRYPT') ? CONF_DBCRYPT : 'fdseg')).'" style="display:none" />';
   }
}