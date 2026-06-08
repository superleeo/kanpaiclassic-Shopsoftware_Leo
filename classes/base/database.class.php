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

class KANPAICLASSIC_database
{
   private $db          = null;        // MySQLi-Objekt
   private $result      = null;    // MySQLi::Result-Objekt
   public  $rows        = 0;          // Anzahl Datensätze letze Query

   public $last_sql     = '';     // letzter SQL-Befehl
   public $all_sql      = '';      // letzter SQL-Befehl
   public $error        = 0;         // Fehler-Nummer
   public $message      = "";      // Fehlermeldung
   public $all_messages = ''; // Alte Fehlermeldungen
   public $count        = 0;         // Anzahl Queries
   public $time         = 0.0;

   private $server      = '';
   private $user        = '';
   private $pass        = '';
   private $database    = '';
   private $port        = '';
   private $extern_db   = false;

   // DB öffnen
   function __construct($server = '', $user = '', $pass = '', $database = '', $port = '') {
      $this->server   = $server;
      $this->user     = $user;
      $this->pass     = $pass;
      $this->database = $database;
      $this->port     = (int)$port;

      $ret = $this->db_connect();

      if ($ret !== false) {
         $this->db = $ret;
         return true;
      }

      return false;
   }

   // DB schließen
   function __destruct() {
      if (is_object($this->result)) {
         $this->result->close();
      }
      if ($this->db) {
         $this->db->close();
      }
   }

   // Mit DB verbinden in utf-8
   private function db_connect() {
      $mysqli = null;

      // Externe DB
      if ($this->server != '') {
         $this->extern_db = true;
         $mysqli = @new \mysqli($this->server, $this->user, $this->pass, $this->database, $this->port);
      }

      else if (!defined('CONF_DBPORT') || CONF_DBPORT == '') {
         $mysqli = new \mysqli(CONF_DBHOST, CONF_DBUSER, CONF_DBPASS, CONF_DATABASE);
      }

      else if (defined('CONF_DBPORT') && CONF_DBPORT != '' && (!defined('CONF_DBSOCKET') || CONF_DBSOCKET == '')){
         $mysqli = new \mysqli(CONF_DBHOST, CONF_DBUSER, CONF_DBPASS, CONF_DATABASE, CONF_DBPORT);
      }

      else {
         $mysqli = new \mysqli(CONF_DBHOST, CONF_DBUSER, CONF_DBPASS, CONF_DATABASE, CONF_DBPORT, CONF_DBSOCKET);
      }

      /* check connection */
      if ($mysqli->connect_errno) {
         $this->last_sql = 'Verbindungsdaten falsch';
         $this->debugAndDie($mysqli->connect_error);
         return false;
      }

      /* change character set to utf8 */
      if (!$mysqli->set_charset("utf8")) {
         $this->debugAndDie("Error loading character set utf8: " . $mysqli->error);
         return false;
      }

      // MySQL 8.0+ compatible mode (NO_AUTO_CREATE_USER removed in MySQL 8+)
      // $mysqli->query("SET SESSION sql_mode = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION'");
      // Strict-Mode
      $mysqli->query("SET SESSION sql_mode = 'NO_ENGINE_SUBSTITUTION'");
      // Normaler Mode
      // $mysqli->query("SET SESSION sql_mode = ''");
      return $mysqli;
   }

   // Vorige Ergebnisse löschen
   private function clear() {
      if (is_object($this->result)) {
         $this->result->close();
         $this->result = null;
      }

      $this->last_sql = '';
      $this->rows = 0;
      $this->all_messages .= $this->message . "\n";
      $this->message = "";

   }

   // Abfrage durchführen
   public function query($sql) {
      if ($this->extern_db && !$this->db) { return null;}

      $sql = $this->_sqlReplace($sql);
      $start = microtime(true);
      $this->count++;
      $this->clear();

      $this->last_sql = $sql;
//      $this->all_sql .= $this->last_sql . "\n";

      // Abfrage

      $this->result = $this->db->query($sql) or $this->debugAndDie($sql);
      if ($this->result === false) {
         $this->error = $this->db->errno;
         $this->message = $this->error . ": " . $this->db->error;
         $this->time += microtime(true) - $start;
         return false;
      }

      // Bei SELECT Anzahl der Ergebnisse
      if (is_object($this->result)) {
         $this->rows = $this->result->num_rows;
         $this->time += microtime(true) - $start;
         return $this->rows;
      }

      // sonst Anzahl betroffener Zeilen
      $this->rows = $this->db->affected_rows;
      $this->time += microtime(true) - $start;
      return $this->rows;
   }

   // Mehrere durch ";" getrennte Abfragen
   public function mquery($sql, $filename = '') {
      $this->last_sql = 'mquery';
      $start = microtime(true);
      $this->clear();
      $this->count++;
      $i = 1;

      // #__ mit Prefix ersetzten
      $sql = str_replace('#__', CONF_DBPREFIX, $sql);
      $this->last_sql = $sql;

      if ($this->db->multi_query($sql)) {
         do {
            $i++;
            /* store first result set */
            if (($result = $this->db->store_result())) {
               while ($row = $result->fetch_row()) {
               }
               $result->free();
            }
            if ($this->db->more_results()) {
            }
          } while (@$this->db->next_result());
      }
      if ($this->db->errno) {
         echo '<br /><span style="color:#cc0000"> Update '.$filename.' konnte nicht durchgef&uuml;hrt werden. Fehler bei Befehl '.$i.' : '.$this->db->error.'</span>';
         return false;
      }

      return true;
   }

   public function querySingleObject($sql) {
      if ($this->extern_db && !$this->db) { return null; }

      $sql = $this->_sqlReplace($sql);
      $sql .= " LIMIT 1";
      $this->last_sql = $sql;
      $this->result = $this->db->query($sql) or $this->_debugAndDie($sql);
      return $this->getObject();
    }

   // Rückgabe: 1.Wert oder NULL, wenn nicht gefunden
   public function querySingleValue($sql, $pos = 0) {
      if ($this->extern_db && !$this->db) { return null; }

      if ($pos == 0) {
         $sql = "$sql LIMIT 1";
      }

      $this->query($sql);
      $data = $this->result->fetch_row();
      if (isset($data[$pos])) {
         return $data[$pos];
      }

      return null;
   }

   public function queryAllObjects($sql) {
      $test = $this->query($sql);
      if ($test < 1) {
         return null;
      }
      else {
         $data = array();
         while ($tmp = $this->getObject()) {
            if ($tmp != null) {
               $data[] = $tmp;
            }
         }
         return $data;
      }
   }

   private function _debugAndDie($sql, $extern = false) {
      if (defined('CONF_DEBUG_DB')) {
         if ($this->last_sql != '') {
            echo '<div style="margin:auto; width:500px; top:300px; border:1px solid red;">SQL-ERROR:<br />'.$this->last_sql.'<br /><div>Error: '.(is_object($this->db) ? $this->db->error : '').'</div></div>';
         }

         else {
            echo '<div style="margin:auto; width:500px; top:300px; border:1px solid red;">SQL-ERROR:<br />'.$sql.'</div>';
         }
//         error();
      }

      else {
         echo '<div style="margin:auto; width:500px; top:300px; border:1px solid red;">SQL-ERROR:<br />'.$this->last_sql.'<br /><div>Error: '.(is_object($this->db) ? $this->db->error : '').'</div>';
         $err_arr = debug_backtrace();

         if (count($err_arr)) {
            foreach ($err_arr as $v) {
               if ($v['file'] != __FILE__) {
                  $teile = explode('/', $v['file']);
                  $datei = $teile[count($teile) - 1];
                  echo '<div>Aufruf von '.$datei.' Zeile '.$v['line'].'</div>';
                  echo '</div>';
                  break;
               }
            }
         }
      }
//      throw new Exeption('DB_QUERY_ERROR');
//      if (defined('CONF_DB_DEBUG')) {
//      }

      if (!$extern) {
         die;
      }

      else {
         return;
      }
   }

    // Ergebnis als Objekt zurückliefern, falls nicht gefunden NULL
   public function getObject($myclass = '') {
      $this->last_sql .= ' (getObject)';

      if (is_object($this->result)) {
         if ($myclass == '') {
            return $this->result->fetch_object();
         }
         else {
            return $this->result->fetch_object($myclass);

         }

      }
      else {
         echo '<div style="margin:auto; width:500px; top:300px; border:1px solid red;">SQL-ERROR:<br />'.$this->last_sql.'</div>';
//         throw new Exeption('DB-QUUERY-ERROR');
         exit;
      }
   }

   // Ergebnis als Array zurückliefern
   public function getArray() {
      return $this->result->fetch_array();
   }

   // ID letzter INSERT zurück liefern
   public function getNewId() {
      return (int)$this->db->insert_id;
   }

   // String 'Escapen'
   public function escape($string) {
      return $this->db->real_escape_string($string);
   }

   private function debugAndDie($sql) {
      if ($this->extern_db) {
         echo 'Fehler Externe DB<br />';
         $this->_debugAndDie($sql, true);
         return;
      }

      $this->_debugAndDie($sql);
   }

   private function _debugQuery($sql, $reason = "Debug") {
      $color = ($reason == "Error" ? "red" : "orange");
      echo '<div style="border:solid '.$color.' 1px; margin: 2px;">'.
              '<p style="margin: 0 0 2px 0; padding: 0; background-color: #DDF;">'.
                 '<strong style="padding: 0 3px; background-color: $color; color: white;">'.$reason.':</strong> '.
                 '<span style="font-family: monospace;">'.htmlentities($sql).'</span>
               </p>
            </div>';
   }

   private function _sqlReplace($sql) {
      if (defined('CONF_DBPREFIX')) {
         return str_replace('#__', CONF_DBPREFIX, $sql);
      }
      return $sql;
   }

   // Bestellnummer lesen und erhöhen
   public function getBestellnummer() {
      return $this->getNummer('bestellung');
   }

   // Rechnungsnummer Sammelbestellung lesen und erhöhen
   public function getCollectornummer() {
      return $this->getNummer('collector');
   }

   // Rechnungsnummer lesen und erhöhen
   public function getRechnungsnummer() {
      return (defined('CONF_RECHNUNG_PREFIX') ? CONF_RECHNUNG_PREFIX : '').$this->getNummer('rechnung');
   }

   // Rechnungsnummer lesen und erhöhen für Haendler
   public function getRechnungsnummerHaendler($haendler_id) {
      // Falls noch nicht angelegt, Eintrag anlegen
      $test = $this->querySingleObject("SELECT * FROM #__nummern WHERE id = $haendler_id");

      if (!is_object($test)) {
         $test = $this->querySingleObject("SELECT * FROM #__nummern WHERE id = 1");
         $this->query("INSERT INTO #__nummern SET id = $haendler_id, rechnung = $test->rechnung");
      }
      return $this->getNummer('rechnung', $haendler_id);
   }


   // Daten lesen und Incrementieren mit Lock
   // verhinder dass nummer nochmals gelesen werden kann, bevor sie verändert wurde
   private function getNummer($art, $haendler_id = 0) {
      // Tabelle sperren
      $sql = "LOCK TABLES #__nummern WRITE";
      $this->query($sql);

      // Wert abfragen und für Update incrementieren
      if ($haendler_id == 0 || $art == 'bestellung') {
         $sql = "SELECT $art FROM #__nummern WHERE id = 1";
      }
      else {
         $sql = "SELECT $art FROM #__nummern WHERE id = $haendler_id";
      }
      $this->query($sql);
      $data = $this->getObject();

      // Update mit incrementiertem Wert
      if ($haendler_id == 0 || $art == 'bestellung') {
         $sql = "UPDATE #__nummern SET $art = $art + 1 WHERE id = 1";
      }
      else {
         $sql = "UPDATE #__nummern SET $art = $art + 1 WHERE id = $haendler_id";
      }
      $this->query($sql);

      // Tabelle wieder freigeben
      $sql = "UNLOCK TABLES";
      $this->query($sql);

      if ($haendler_id == 0 || $art == 'bestellung') {
         $back = $data->$art;
      }
      else {
         $back = $haendler_id.'-'.$data->$art;
      }
//      $next = $back;
//      $next++;

      return $back;
   }

}