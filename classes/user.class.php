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

class KANPAICLASSIC_user
{
   private $db = null;
   private $params = null;

   public $user = array();
   public $user_err = array();
   public $regerror = false;
   public $mailvorhanden = false;
   public  $mailchanged   = false;
   private $alter_error = false;

   public function __construct() {
      $this->db     = Control::getDB();
      $this->params = Control::getParams();
      $this->init();
   }

   public function __destruct() {
      $_SESSION['user'] = $this->user;
   }

   // Userdaten vorbelegen (leer)
   public function init() {
      // User-Daten aus Session verwenden
      if (defined('CONF_MODULE_BESTZUS') && isset($_SESSION['user'])) {
         $this->user = $_SESSION['user'];
         $this->resetUserErr();
         return;
      }

      // falls User vorhanden und User-Daten nicht in Session, aus DB lesen
      if ($this->params->user_id > 0) {
         $this->read($this->params->user_id);
         $this->params->setSession('user', $this->user);
      }

      else if (isset($_SESSION['user'])) {
         $this->user = $_SESSION['user'];
      }

      // Userdaten default
      else {
         $this->user['id']             = 0;
         $this->user['anrede']         = '';
         $this->user['name']           = 'gast';
         $this->user['email']          = '';
         $this->user['email2']         = '';
         $this->user['password']       = '';
         $this->user['password1']      = '';
         $this->user['password2']      = '';
         $this->user['role']           = '9';
         $this->user['vorname']        = '';
         $this->user['nachname']       = '';
         $this->user['firma']          = '';
         $this->user['adresse']        = '';
         $this->user['hausnr']         = '';
         $this->user['plz']            = '';
         $this->user['ort']            = '';
         $this->user['buland']         = '';
         $this->user['staat']          = (int)$this->db->querySingleValue("SELECT id FROM #__laender WHERE sort > 0 ORDER BY sort, id");
         $this->user['staat2']         = '';
         $this->user['gebdatum']       = '';
         $this->user['ustid']          = '';
         $this->user['telefon']        = '';
         $this->user['created']        = '';
         $this->user['modified']       = '';
         $this->user['last_login']     = '';
         $this->user['forgotten']      = '';
         $this->user['newsletter']     = 'n';
         $this->user['daten']          = 'n';
         $this->user['agb']            = 'n';
         $this->user['gesperrt']       = $this->params->firma['account_manual'];
         $this->user['lang']           = 'deu';
         $this->user['lieferadresse']  = 'n';
         $this->user['lf_anrede']      = '';
         $this->user['lf_vorname']     = '';
         $this->user['lf_nachname']    = '';
         $this->user['lf_firma']       = '';
         $this->user['lf_postnr']      = '';
         $this->user['lf_adresse']     = '';
         $this->user['lf_hausnr']      = '';
         $this->user['lf_plz']         = '';
         $this->user['lf_ort']         = '';
         $this->user['lf_buland']      = '';
         $this->user['lf_staat']       = 0;
         $this->user['lf_staat2']      = '';
         $this->user['rabatt']         = 0.0;
         $this->user['gutschrift']     = 0.0;
         $this->user['pp_mail']        = '';
         $this->user['pp_id']          = '';
         $this->user['bank_name']      = '';
         $this->user['bank_inhaber']   = '';
         $this->user['bank_iban']      = '';
         $this->user['bank_bic']       = '';
         $this->user['website']        = '';

         $this->user['kk_inhaber']     = '';
         $this->user['kk_name']        = '';
         $this->user['kk_nr']          = '';
         $this->user['kk_datum']       = '0000-00-00:::';
         $this->user['rechnung_kunde'] = 'n';

         $this->params->setSession('user', $this->user);
      }

      // Newsletter als Vorbelegung warenkorb übernehmen
      if (!isset($_SESSION['newsletter'])) {
         $_SESSION['newsletter'] = $this->user['newsletter'];
      }


      // für Fehlerausgabe Formulare
      if (!isset($_SESSION['user_err'])) {
         $this->resetUserErr();
      }
      else {
         $this->user_err = $_SESSION['user_err'];
      }
   }

   // Feld forgotten setzen, falls delete = true auch Passwort löschen (bei forgotten)
   public function forgotten($email, $mode = 'modify') {
      if ($email) {
         $forgotten = md5('KANPAICLASSIC'.time());

         if ($mode == 'verify') {
            $sql = "UPDATE #__users SET forgotten = '$forgotten' WHERE email = '$email'";
         }

         else {
            $sql = "UPDATE #__users SET forgotten = '$forgotten', modified = '".date('Y-m-d H:i:m')."' WHERE email = '$email'";
         }

         $this->db->query($sql);
         $sql = "SELECT lang FROM #__users WHERE email = '$email'";
         $query = $this->db->query($sql);

//         if ($query) {
//            $data = $this->db->getObject();
//            $this->params->selected_lang = $data->lang;
//         }

         return $forgotten;
      }
      return false;
   }

   // Feld newsletter_check setzen
   public function anmeldungNL($email) {
      if ($email) {
         $link = md5('KANPAICLASSIC'.time());
         $this->db->query("UPDATE #__users SET newsletter_check = '$link' WHERE email = '$email'");
         return $link;
      }
      return false;
   }

   // DB aktualisieren. Wenn Newsletter von n auf y geändert wurde, true zurückgeben, sonst false
   // von konto  und lieferung aufgerufen
   public function newsletterChanged($user_id, $newsletter) {
      if ((int)$user_id < 1) {
         return false;
      }

      $userdata = $this->db->querySingleObject("SELECT email, newsletter FROM #__users WHERE id = $user_id");
      $newsletter_old = $userdata->newsletter;

      // Keine Änderung Newsletter
      if ($newsletter == $newsletter_old) {
         return true;
      }

      $this->db->query("UPDATE #__users SET newsletter = '$newsletter' WHERE id = $user_id");

      if ($newsletter == 'y') {
         // Email aus Übergabeparameter, falls Email geändert wurde
         //$link = $this->anmeldungNL($_SESSION['email']); // TODO: war relativ sicher ein bug. Aber nochmal prüfen.
         $link = $this->anmeldungNL($userdata->email);
         $mail = Control::getMail();
         $mail->sendAnmeldungNL($userdata->email, SHOP_URL_IDX.'/validatenl/'.$link, $this->user);
      }

      else {
         $this->db->query("UPDATE #__users SET newsletter_check = '' WHERE id = $user_id");
      }

      return true;
   }

   // Validierungslink Newsletter
   public function validateNL($validate) {
      $data = $this->db->querySingleObject("SELECT id, newsletter_check FROM #__users WHERE newsletter_check = '$validate'");

      // NL-Validierung gefunden
      if ($data) {
         $this->db->query("UPDATE #__users SET newsletter_check = 'ok' WHERE id = $data->id");
         return true;
      }

      return false;
   }

   // Kunde löschen
   public function delete($user_id) {
      $this->db->query("DELETE FROM #__users WHERE id = $user_id");
   }

   private function resetUserErr() {
      // Fehler-Felder zurücksetzen
      $this->user_err['vorname_err']     = false;
      $this->user_err['nachname_err']    = false;
      $this->user_err['gebdatum_err']    = false;
      $this->user_err['adresse_err']     = false;
      $this->user_err['plz_err']         = false;
      $this->user_err['firma_err']       = false;
      $this->user_err['ort_err']         = false;
      $this->user_err['staat_err']       = false;
      $this->user_err['staat2_err']      = false;
      $this->user_err['telefon_err']     = false;
      $this->user_err['email_err']       = false;
      $this->user_err['email2_err']      = false;
      $this->user_err['email_msg']       = '';
      $this->user_err['password1_err']   = false;
//      $this->user_err['password2_err']   = false;

      $this->user_err['daten_err']       = false;
      $this->user_err['agb_err']         = false;

      $this->user_err['lf_vorname_err']  = false;
      $this->user_err['lf_nachname_err'] = false;
      $this->user_err['lf_adresse_err']  = false;
      $this->user_err['lf_plz_err']      = false;
      $this->user_err['lf_ort_err']      = false;
      $this->user_err['lf_staat_err']    = false;
      $this->user_err['lf_staat2_err']   = false;
      $this->user_err['website_err']     = false;

      $this->user_err['perso_err']       = false;
      $this->user_err['perso_err_msg']   = '';
      $this->params->setSession('user_err', $this->user_err);
   }

   // Testen, ob Email in User-DB vorhanden ist
   public function mailVorhanden($email) {
      if ($email) {
         $sql = "SELECT email FROM #__users WHERE email = '$email'";
         if ($this->db->query($sql) == 1) {
            return true;
         }
      }
      return false;
   }

   // Validierungslink
   public function validate($validate) {
      $sql = "SELECT id, password, role, email, lang, gesperrt, modified FROM #__users WHERE forgotten = '$validate'";

      if ($this->db->query($sql)) {
         $data = $this->db->getObject();
         $this->params->selected_lang = $data->lang;
         $role = (int)$data->role;

         if ($role == 5) {
            $this->params->is_haendler = true;
         }

         // User gesperrt - role > 100 -> manuelle Freigabe
         if ($role != 5 && $data->gesperrt == 'y' && (int)$data->role < 100) {
            return "gesperrt";
         }

         // Passwort abfragen - Formular PW eingeben anzeigen
         if ($data->modified != '0000-00-00 00:00:00' && $data->modified != null && $this->params->postInt('pwcheck') != 1) {
            return 'password';
         }

         // Passwort testen und in DB eintragen
         if ($this->params->postInt('pwcheck') == 1) {
            if (strlen($this->params->postString('password1')) > 2) {
               if ($role == 9 || $role == 109) {
                  $role = $role + 1;
               }

               $sql = "UPDATE #__users SET forgotten = '', password = '".md5($this->params->postString('password1'))."', modified = '".date('Y-m-d H:i:m')."', role = $role  WHERE id = ".$data->id;
               $this->db->query($sql);

               // Admin PW-Forgotten
               if ($role == 0) {
                  // Fehlgeschlagenen Admin-Logins löschen
                  $this->db->query("TRUNCATE TABLE #__admin_logins");


                  header('Location: '.ADMIN_URL);
                  exit;
               }

               // Anmelden
               $this->read($data->id);
               $this->params->setSession('user', $this->user);
               $_SESSION['logged_in'] = true;
               $_SESSION['user_id'] = $this->user['id'];
               $_SESSION['user_name'] = $this->user['nachname'];
               $_SESSION['email'] = $this->user['email'];
               return 'pwchanged';
            }
            else {
               return 'password_fail';
            }
         }

         // Validierung nach Neuregistrierung, forgotten löschen, Status auf Neukunde(10) bzw haendler (5)
         else {
            if ($role != 5) {
               $role = 10;
            }

            $sql = "UPDATE #__users SET role = $role, forgotten = '', modified = '".date('Y-m-d H:i:m')."' WHERE id = $data->id";
            $this->db->query($sql);

            // Anmelden
            if ($this->params->firma['account_manual'] == 'y') {
               return 'login';
            }

            //$this->read($data->id);
            //$this->params->setSession('user', $this->user);
            //$_SESSION['user_id'] = $this->user['id'];
            //$_SESSION['user_name'] = $this->user['nachname'];
            //$_SESSION['email'] = $this->user['email'];
            $_SESSION['logged_in'] = false;

            return 'anmeldung';
         }
      }

      return 'fail';
   }

   // Uebergabeparameter aus Bestellformular/Adressen/Mein Konto lesen und und auf Änderung prüfen
   public function checkAdresse() {
      $changed = false;

      if ($this->user['anrede'] != $this->params->postString('anrede')) {
         $changed = true;
      }
      $this->user['anrede'] = $this->params->postString('anrede');

      if ($this->user['vorname'] != $this->params->postString('vorname')) {
         $changed = true;
      }
      $this->user['vorname'] = $this->params->postString('vorname');

      if ($this->user['nachname'] != $this->params->postString('nachname')) {
         $changed = true;
      }
      $this->user['nachname'] = $this->params->postString('nachname');

      if ($this->user['email'] != $this->params->postString('email')) {
         $changed = true;
         $this->mailchanged = true;
      }
      $this->user['email'] = $this->params->postString('email');
      $this->user['email2'] = $this->params->postString('email2');

      if ($this->user['telefon'] != $this->params->postString('telefon')) {
         $changed = true;
      }
      $this->user['telefon'] = $this->params->postString('telefon');

      if ($this->user['firma'] != $this->params->postString('firma')) {
         $changed = true;
      }
      $this->user['firma'] = $this->params->postString('firma');

      if ($this->user['ustid'] != $this->params->postString('ustid')) {
         $changed = true;
      }
      $this->user['ustid'] = $this->params->postString('ustid');

      if ($this->user['adresse'] != $this->params->postString('adresse')) {
         $changed = true;
      }
      $this->user['adresse'] = $this->params->postString('adresse');

      if ($this->user['hausnr'] != $this->params->postString('hausnr')) {
         $changed = true;
      }
      $this->user['hausnr'] = $this->params->postString('hausnr');

      if ($this->user['plz'] != $this->params->postString('plz')) {
         $changed = true;
      }
      $this->user['plz'] = $this->params->postString('plz');

      if ($this->user['ort'] != $this->params->postString('ort')) {
         $changed = true;
      }
      $this->user['ort'] = $this->params->postString('ort');

      if ($this->user['buland'] != $this->params->postString('buland')) {
         $changed = true;
      }
      $this->user['buland'] = $this->params->postString('buland');

      if ($this->user['staat'] != $this->params->postInt('staat')) {
         $changed = true;
      }
      $this->user['staat'] = $this->params->postInt('staat');

      if ($this->user['staat2'] != $this->params->postString('staat2')) {
         $changed = true;
      }
      $this->user['staat2'] = $this->params->postString('staat2');

      // immer 'y' seeit Lieferadresse Pflicht
      $this->user['lieferadresse'] = 'y';

      if ($this->params->postCheckbox('lieferadresse') == 'y'
            && !defined('CONF_MODULE_BESTZUS')
            || defined('CONF_MODULE_BESTZUS') &&  $this->params->postString('lf_nachname') != '')
      {
         if ($this->user['lf_anrede'] != $this->params->postString('lf_anrede')) {
            $changed = true;
         }

         $this->user['lf_anrede'] = $this->params->postString('lf_anrede');
         if ($this->user['lf_vorname'] != $this->params->postString('lf_vorname')) {
            $changed = true;
         }
         $this->user['lf_vorname'] = $this->params->postString('lf_vorname');

         if ($this->user['lf_nachname'] != $this->params->postString('lf_nachname')) {
            $changed = true;
         }
         $this->user['lf_nachname'] = $this->params->postString('lf_nachname');

         if ($this->user['lf_firma'] != $this->params->postString('lf_firma')) {
            $changed = true;
         }
         $this->user['lf_firma'] = $this->params->postString('lf_firma');

         if ($this->user['lf_postnr'] != $this->params->postString('lf_postnr')) {
            $changed = true;
         }
         $this->user['lf_postnr'] = $this->params->postString('lf_postnr');

         if ($this->user['lf_adresse'] != $this->params->postString('lf_adresse')) {
            $changed = true;
         }
         $this->user['lf_adresse'] = $this->params->postString('lf_adresse');

         if ($this->user['lf_hausnr'] != $this->params->postString('lf_hausnr')) {
            $changed = true;
         }
         $this->user['lf_hausnr'] = $this->params->postString('lf_hausnr');

         if ($this->user['lf_plz'] = $this->params->postString('lf_plz')) {
            $changed = true;
         }
         $this->user['lf_plz'] = $this->params->postString('lf_plz');

         if ($this->user['lf_ort'] != $this->params->postString('lf_ort')) {
            $changed = true;
         }
         $this->user['lf_ort'] = $this->params->postString('lf_ort');

         if ($this->user['lf_buland'] != $this->params->postString('lf_buland')) {
            $changed = true;
         }
         $this->user['lf_buland'] = $this->params->postString('lf_buland');

         if ($this->user['lf_staat'] != $this->params->postInt('lf_staat')) {
            $changed = true;
         }
         $this->user['lf_staat'] = $this->params->postInt('lf_staat');

         if ($this->user['lf_staat2'] != $this->params->postString('lf_staat2')) {
            $changed = true;
         }
         $this->user['lf_staat2'] = $this->params->postString('lf_staat2');
      }

      // Falls bei alten Versionen noch keine Lieferadresse oder bei Module Bestellzusammenfassung
      else if (($this->user['lf_nachname'] == '' && (!defined('CONF_MODULE_BESTZUS') ||
                   defined('CONF_MODULE_BESTZUS') && $this->user['lf_nachname'] == '')) ||
                   defined('CONF_PORTAL'))
      {
         $this->user['lf_anrede'] = $this->params->postString('anrede');
         $this->user['lf_vorname'] = $this->params->postString('vorname');
         $this->user['lf_nachname'] = $this->params->postString('nachname');
         $this->user['lf_firma'] = $this->params->postString('firma');
         $this->user['lf_adresse'] = $this->params->postString('adresse');
         $this->user['lf_hausnr'] = $this->params->postString('hausnr');
         $this->user['lf_plz'] = $this->params->postString('plz');
         $this->user['lf_ort'] = $this->params->postString('ort');
         $this->user['lf_buland'] = $this->params->postString('buland');
         $this->user['lf_staat'] = $this->params->postInt('staat');
         $this->user['lf_staat2'] = $this->params->postString('staat2');

         $changed = true;
      }

      $this->params->setSession('user', $this->user);
      $this->params->setSession('lieferung_land', $this->user['lf_staat']);
      $this->params->setSession('rechnung_land', $this->user['staat']);

      $this->params->email = $this->params->postString('email');
      $this->user['id'] = $this->params->user_id;

      $this->user['geb_datum'] = '';
      unset($_SESSION['write_alter']);

      // Altercheck bei Login / Lieferung / Artikel mit FSK
      if (defined('CONF_MODULE_PERSOCHECK') && $this->params->firma['fsk_show'] != 'y' && $_SESSION['fsk_artikel']) {
         $perso_nr = '';

         // Noch kein Altercheck - Perso-Nr lesen
         if (!$_SESSION['alter_check']) {
            $perso_nr = $this->params->postString('perso_nr', '', 'url');
            $_SESSION['perso_nr'] = $perso_nr;

            // Perso-Nr vorhanden
            if ($_SESSION['perso_nr'] != '') {
               $perso = Control::getModulePersocheck();
               $test  = $perso->check_perso($perso_nr);

               // Perso-Nr gültig
               if ($test->status) {
                  $changed = true;
                  $_SESSION['alter_check']         = true;
                  $_SESSION['write_alter']         = true;
                  $_SESSION['alter_typ']           = $test->typ;
                  $_SESSION['geb_datum']           = $test->geb_datum;
                  $_SESSION['alter_ok_date']       = $test->geb_datum;
                  $_SESSION['perso_nr']            = $test->nr;  // Perso-Nr bereinigt
                  $_SESSION['user']['gebdatum']   = $test->geb_datum;
                  $this->user['gebdatum']          = $test->geb_datum;
                  $this->user_err['perso_err']     = false;
                  $this->user_err['perso_err_msg'] = '';
               }

               // Perso-Nr ungültig
               else {
                  $_SESSION['alter_check']     = false;
                  $this->user_err['perso_err'] = true;
                  $this->user_err['perso_err_msg'] = $test->txt;
               }
            }

            // Perso-Nr war leer
            else {
               $_SESSION['alter_check']     = false;
               $this->user_err['perso_err'] = true;
            }
         }

         else {
            $this->user['gebdatum'] = isset($_SESSION['geb_datum'])? $_SESSION['geb_datum'] : '';
            $this->user_err['perso_err'] = false;

         }
      }

      return $changed;
   }

   // Adressdaten bei Bestellung auf Pflichtfelder überprüfen
   public function checkBestellung() {
      // Fehler Perso-Check behalten
      $test = $this->user_err['perso_err'];
      $test2 = $this->user_err['perso_err_msg'];
      $this->resetUserErr();

      if ($test) {
         $test = $this->user_err['perso_err'] = true;
         $this->user_err['perso_err_msg'] = $test2;
         $this->regerror = true;
      }

      $this->user['gebdatum'] = $this->params->postString('gebdatum_jahr') . '-' . $this->params->postString('gebdatum_monat') . '-' . $this->params->postString('gebdatum_tag');
      $datum = $this->user['gebdatum'];

      // Geburtsdatum
      preg_match('/^([\d]{2,4})\-([\d]{1,2})\-([\d]{1,2})$/', $datum, $test);

      if (count($test) == 4) {
         $jahr = $test[1];
         $monat = $test[2];
         $tag = $test[3];

         if (strlen($jahr) == 2) {
            if ((int)$jahr > date('y')) {
               $jahr = '19' . $jahr;
            }
            else {
               $jahr = '20' . $jahr;
            }
         }

         // Datum gültig?
         if (checkdate($monat, $tag, $jahr)) {
            $this->user['gebdatum'] = sprintf('%04d-%02d-%02d', $jahr, $monat, $tag);
         }
      }

      // Adressdaten auf Min-Länge testen
      $check     = array('vorname', 'nachname', 'adresse', 'plz', 'ort');
      $check_len = array( 2,        2,          5,         4,     3,  );

      $i = 0;
      foreach ($check as $feld) {
         if (strlen($this->user[$feld]) < $check_len[$i]) {
            $this->user_err[$feld.'_err'] = true;
            $this->regerror = true;
            $i++;
         }
      }

      if (strlen($this->user['hausnr']) < 1) {
         $this->user_err['adresse_err'] = true;
         $this->regerror = true;
      }

      if ((int)$this->user['staat'] == 10 && $this->user['staat2'] == '') {
         $this->user_err['staat2_err'] = true;
         $this->regerror = true;
      }

      if ($this->params->firma['telefon_aktiv'] ==  'y') {
         if (strlen($this->user['telefon']) < 3 || (substr($this->user['telefon'], 0, 1) != '0' && substr($this->user['telefon'], 0, 1) != '+')) {
            $this->user_err['telefon_err'] = true;
            $this->regerror = true;
         }
      }

      if ($this->params->firma['b2b_check'] == 'y') {
         if (strlen($this->user['firma']) < 3) {
            $this->user_err['firma_err'] = true;
            $this->regerror = true;
         }
      }

      // Falls Rechnungsadresse kein Lieferland, Lieferadresse erzwingen
      if($this->user['lieferadresse'] == 'n') {
         $laender = Control::getLaender();
         $check = $laender->checkLieferung($this->user['staat']);

         if ($check !== true) {
            $this->user['lieferadresse'] = 'y';
            $this->regerror = true;
            $this->params->setSession('user', $this->user);
         }
      }

         $check     = array('lf_vorname', 'lf_nachname', 'lf_adresse', 'lf_plz', 'lf_ort');
         $check_len = array( 2,            2,             5,            4,        3,     );
      $i         = 0;

         foreach ($check as $feld) {
            if (strlen($this->user[$feld]) < $check_len[$i]) {
               $this->user_err[$feld.'_err'] = true;
               $this->regerror = true;
               $i++;
            }
         }

         if (strlen($this->user['lf_hausnr']) < 1) {
            $this->user_err['lf_adresse_err'] = true;
            $this->regerror = true;
         }

         if ((int)$this->user['lf_staat'] == 0) {
            $this->user_err['lf_staat_err'] = true;
            $this->regerror = true;
         }

         if ((int)$this->user['lf_staat'] == 10 && $this->user['lf_staat2'] == '') {
            $this->user_err['lf_staat2_err'] = true;
            $this->regerror = true;
      }

      if (!$this->checkMail($this->user['email'])) {
         $this->user_err['email_err'] = true;
         $this->user_err['email_msg'] = $this->params->text->get('kunde', 'fehler3');
         $this->regerror = true;
      }

      $this->params->setSession('email', $this->user['email']);

      if ($this->mailchanged && !$this->regerror) {
         $this->mailChange();
      }

      $this->params->setSession('user_err', $this->user_err);

      // Bei Coupon kein $user->user['nachname']
      if ($_SESSION['user_name'] == '') {
         $_SESSION['user_name'] = $this->user['nachname'];
      }

      $lieferland = $this->user['staat'];

      if ($this->user['lieferadresse'] == 'y') {
      	$lieferland = $this->user['lf_staat'];
      }

      if ($this->alter_error) {
         $this->user_err['perso_err'] = true;
         $this->regerror = true;
      }

      // Wenn OK -> true
      return !$this->regerror;
   }

   // Übergabe-Parameter für Anmeldung lesen
   // Nur noch von params/coupon verwendet
   public function getMoreParams() {
      // Adressdaten validieren, wie Direktkauf
      $this->checkAdresse();

      // Zusätzlich zu Direktkauf
      $this->user['gebdatum'] = $this->params->postString('gebdatum_jahr') . '-' . $this->params->postString('gebdatum_monat') . '-' . $this->params->postString('gebdatum_tag');
      $this->user['telefon'] = $this->params->postString('telefon');
      $this->user['email'] = $this->params->postString('email');
      $this->user['email2']   = $this->params->postString('email2');

      if ($this->params->postString('password1') != '') {
         $this->user['password1'] = $this->params->postString('password1');
         $this->user['password2'] = $this->params->postString('password1');
      }
   }

   // Userdaten aus DB lesen
   public function read($userid) {
      $sql = '';

      if ((int)$userid == $userid) {
         if ($userid == 0) {
            return false;
         }
         else {
            $sql = "SELECT * FROM #__users WHERE id = $userid";
         }
      }

      else {
         $sql = "SELECT * FROM #__users WHERE email = $userid";
      }

      if (!$this->db->query($sql)) {
         return false;
      }

      $data = $this->db->getObject();
      $this->params->selected_lang = $data->lang;

      $this->user['id']            = $data->id;
      $this->user['anrede']        = $data->anrede;
      $this->user['name']          = $data->name;
      $this->user['email']         = $data->email;
      $this->user['password']      = $data->password;
      $this->user['role']          = $data->role;
      $this->user['vorname']       = $data->vorname;
      $this->user['nachname']      = $data->nachname;
      $this->user['firma']         = $data->firma;
      $this->user['adresse']       = $data->adresse;
      $this->user['hausnr']        = $data->hausnr;
      $this->user['plz']           = $data->plz;
      $this->user['ort']           = $data->ort;
      $this->user['buland']        = $data->buland;
      $this->user['staat']         = $data->staat;
      $this->user['staat2']        = $data->staat2;
      $this->user['gebdatum']      = $data->gebdatum;
      $this->user['alter_check']   = $data->alter_check;
      $this->user['ustid']         = $data->ustid;
      $this->user['telefon']       = $data->telefon;
      $this->user['newsletter']    = $data->newsletter;
      $this->user['gesperrt']      = $data->gesperrt;
      $this->user['lieferadresse'] = 'y';

      if (!isset($_SESSION['newsletter'])) {
         $_SESSION['newsletter'] = $data->newsletter;
      }

      // Bei Modul Bestellzusammenfassung keine Lieferadresse
      if (!defined('CONF_MODULE_BESTELLZUSAMMENFASSUNG')) {
         // Falls Lieferadresse vorhanden -> übernehmen (alte Shops ohne Lieferadresse)
         if ($data->lf_nachname != '') {
            $this->user['lf_anrede']     = $data->lf_anrede;
            $this->user['lf_vorname']    = $data->lf_vorname;
            $this->user['lf_nachname']   = $data->lf_nachname;
            $this->user['lf_firma']      = $data->lf_firma;
            $this->user['lf_postnr']     = $data->lf_postnr;
            $this->user['lf_adresse']    = $data->lf_adresse;
            $this->user['lf_hausnr']     = $data->lf_hausnr;
            $this->user['lf_plz']        = $data->lf_plz;
            $this->user['lf_ort']        = $data->lf_ort;
            $this->user['lf_buland']     = $data->lf_buland;
            $this->user['lf_staat']      = $data->lf_staat;
            $this->user['lf_staat2']     = $data->lf_staat2;
         }

         // Sonst Rechnungsadresse als Lieferadress übernehmen
         else {
            $this->user['lf_anrede']     = $data->anrede;
            $this->user['lf_vorname']    = $data->vorname;
            $this->user['lf_nachname']   = $data->nachname;
            $this->user['lf_firma']      = $data->firma;
            $this->user['lf_postnr']     = '';
            $this->user['lf_adresse']    = $data->adresse;
            $this->user['lf_hausnr']     = $data->hausnr;
            $this->user['lf_plz']        = $data->plz;
            $this->user['lf_ort']        = $data->ort;
            $this->user['lf_buland']     = $data->buland;
            $this->user['lf_staat']      = $data->staat;
            $this->user['lf_staat2']     = $data->staat2;
         }
      }

      // Bei Modul Bestellzusammenfassung: Lieferadresse leer
      else {
         $this->user['lf_anrede']     = '';
         $this->user['lf_vorname']    = '';
         $this->user['lf_nachname']   = '';
         $this->user['lf_firma']      = '';
         $this->user['lf_postnr']     = '';
         $this->user['lf_adresse']    = '';
         $this->user['lf_hausnr']     = '';
         $this->user['lf_plz']        = '';
         $this->user['lf_ort']        = '';
         $this->user['lf_buland']     = '';
         $this->user['lf_staat']      = 160;
         $this->user['lf_staat2']     = '';
      }

      $this->user['created']       = $data->created;
      $this->user['modified']      = $data->modified;
      $this->user['last_login']    = $data->last_login;
      $this->user['forgotten']     = $data->forgotten;
      $this->user['rabatt']        = $data->rabatt;
      $this->user['gutschrift']    = $data->gutschrift;
      $this->user['lang']          = $data->lang;
      $this->user['pp_mail']       = $data->pp_mail;
      $this->user['pp_id']         = $data->pp_id;

      $this->user['bank_name']     = $data->bank_name;
      $this->user['bank_inhaber']  = $data->bank_inhaber;
      $this->user['bank_iban']     = $data->bank_iban;
      $this->user['bank_bic']      = $data->bank_bic;

      if ((int)$data->role > 100) {
         $this->params->valid_user = true;
      }

      $this->user['kk_inhaber']    = '';
      $this->user['kk_name']       = '';
      $this->user['kk_nr']         = '';
      $this->user['kk_datum']      = '0000-00-00:::';
      $this->user['rechnung_kunde'] = $data->rechnung_kunde;

      $_SESSION['rechnung_land']  = $this->user['staat'];
      $_SESSION['lieferung_land'] = $this->user['lf_staat'];

      if ($this->user['alter_check'] != '') {
         $_SESSION['alter_check'] = true;
      }

      else {
         $_SESSION['alter_check'] = false;
      }

      return true;
   }

   // Userdaten in DB schreiben (Aendern oder Neu)
   public function write($mode) {
      $sql     = '';
      $where   = '';
      $user_id = 0;

      // Falls User durch Sofortkauf / Gutschein/Coupon registriert anhand Email suchen
      $sql    = "SELECT id FROM #__users WHERE email = '".$this->user['email']."' AND password = 'd6e16e12d31ca5eaaf18ef8c8c6a3d82'";
      $anzahl = $this->db->query($sql);

      // Gefunden
      if ($anzahl == 1) {
         $test = $this->db->getObject();
         $mode = 'update';
         $this->params->user_id = $test->id;

         $user_id = $test->id;
      }

      // Vorhandener User -> Kein INSERT möglich
      if ($mode == "insert") {
         if ($this->user['id']) {
            return false;
         }

         $sql = "INSERT INTO #__users ";
      }

      elseif ($mode == "update") {
         $user_id = $this->params->user_id;
         $sql     = "UPDATE #__users ";
         $where   = " WHERE id = ".$this->params->user_id;
      }

      else {
         return false;
      }

      // Mit Altersverifizierung
      if (isset($_SESSION['write_alter'])) {
         $this->user['gebdatum'] = $_SESSION['geb_datum'];
      }

      $sql .= "SET anrede        = '".$this->user['anrede']."',
                   vorname       = '".$this->db->escape($this->user['vorname'])."',
                   nachname      = '".$this->db->escape($this->user['nachname'])."',
                   gebdatum      = '".$this->user['gebdatum']."',
                   firma         = '".$this->db->escape($this->user['firma'])."',
                   ustid         = '".$this->user['ustid']."',
                   adresse       = '".$this->db->escape($this->user['adresse'])."',
                   hausnr        = '".$this->user['hausnr']."',
                   plz           = '".$this->user['plz']."',
                   ort           = '".$this->db->escape($this->user['ort'])."',
                   buland        = '".$this->db->escape($this->user['buland'])."',
                   staat         = '".$this->user['staat']."',
                   staat2        = '".$this->db->escape($this->user['staat2'])."',
                   telefon       = '".$this->user['telefon']."',
                   email         = '".$this->user['email']."',
                   newsletter    = '".$_SESSION['newsletter']."',
                   lang          = '".$this->params->selected_lang."', ";

      // Bei Modul Bestellzusammenfassung Lieferadresse nicht speichern
      if (!defined('CONF_MODULE_BESTELLZUSAMMENFASSUNG')) {
         $sql .= " lieferadresse = '".($this->user['lieferadresse'] == 'y' ? 'y' : 'n')."',
                   lf_anrede     = '".$this->user['lf_anrede']."',
                   lf_vorname    = '".$this->db->escape($this->user['lf_vorname'])."',
                   lf_nachname   = '".$this->db->escape($this->user['lf_nachname'])."',
                   lf_firma      = '".$this->db->escape($this->user['lf_firma'])."',
                   lf_postnr     = '".$this->user['lf_postnr']."',
                   lf_adresse    = '".$this->db->escape($this->user['lf_adresse'])."',
                   lf_hausnr     = '".$this->user['lf_hausnr']."',
                   lf_plz        = '".$this->user['lf_plz']."',
                   lf_ort        = '".$this->db->escape($this->user['lf_ort'])."',
                   lf_buland     = '".$this->db->escape($this->user['lf_buland'])."',
                   lf_staat      = '".$this->user['lf_staat']."',
                   lf_staat2     = '".$this->db->escape($this->user['lf_staat2'])."'";
       }

      else {
         $sql .= "lieferadresse = 'n',
                  lf_anrede     = '',
                  lf_vorname    = '',
                  lf_nachname   = '',
                  lf_firma      = '',
                  lf_postnr     = '',
                  lf_adresse    = '',
                  lf_hausnr     = '',
                  lf_plz        = '',
                  lf_ort        = '',
                  lf_buland     = '',
                  lf_staat      = '160',
                  lf_staat2     = ''";
      }

      if (isset($_SESSION['write_alter'])) {
         $alter_check = $_SESSION['alter_typ'];
         $sql .= ", alter_check   = '".$alter_check."'";
         unset($_SESSION['write_alter']);
      }

      // Passwort ändern
//      if ($this->params->postString('password1') == $this->params->postString('password2') && strlen($this->params->postString('password2')) >= 2 ) {
      if (strlen($this->params->postString('password1')) > 2 ) {
         $sql .= ', password = "' . md5($this->params->postString('password1')) . '"';
      }

      // Bei Neukunden
      if($mode == 'insert'){
         $sql .= ", forgotten = '',
                    role = '".($this->params->firma['account_manual'] == 'y' ? 109 : 9)."',
                    gesperrt = '".$this->params->firma['account_manual']."'";
      }

      if ($this->db->query($sql.$where)) {
         // Neuer Kunde
         if ($mode == 'insert'){
            $user_id = $this->db->getNewId();
         }
         unset($_SESSION['user_err']);
         $test = true;

         if (!defined('CONF_MODULE_BESTELLZUSAMMENFASSUNG')) {
            $this->user = array();

            // User-Daten aus DB neu lesen
            $test = $this->read($user_id);
         }

         if ($test) {
            $this->params->user_id   = $user_id;
            $this->params->logged_in = true;
            $this->params->user      = $this->user;
            $_SESSION['user']        = $this->user;
            $_SESSION['user_id']     = $user_id;
            $_SESSION['logged_in']   = true;
            $_SESSION['user_name']   = $this->user['nachname'];
            $_SESSION['email']       = $this->user['email'];
            $_SESSION['lang']        = $this->user['lang'];

            $this->loginerror = false;
            $this->params->loginerror = false;
            $this->params->selected_lang = $this->user['lang'];

            return true;
         }
      }

      return false;
   }

   public function storeBank() {
      if ($this->params->user_id > 0) {
         $sql = "UPDATE #__users SET
                    bank_inhaber = '".$this->db->escape($this->user['bank_inhaber'])."',
                    bank_name = '".$this->db->escape($this->user['bank_name'])."',
                    bank_iban = '".$this->user['bank_iban']."',
                    bank_bic = '".$this->user['bank_bic']."'
                 WHERE id = ".$this->params->user_id;
         $this->db->query($sql);
      }
   }

   // Test auf gültige Mailadresse
   public function checkMail($email) {
      if (!preg_match("/^[_a-zA-Z0-9-](\.{0,1}[_a-zA-Z0-9-])*@([_a-zA-Z0-9-]{2,63}\.){0,}[_a-zA-Z0-9-]{2,63}(\.[_a-zA-Z]{2,4}){1,2}$/i", $email)) {
         return false;
      }
      return true;
   }

   // Test, ob Kunde gesperrt ist
   public function checkGesperrt($email) {
      $sql = "SELECT gesperrt FROM #__users WHERE email = '$email'";
      if ($this->db->query($sql)) {
         if ($this->db->getObject()->gesperrt == 'n') {
            return true;
         }
      }
      return false;
   }

   // Passwort ändern
   public function checkPw() {
      // Passwort nicht geändert
      // Password 2 verwenden, um automatisches Ausfüllen durch Browser ignorieren
      if ($this->params->postString('password1') == '') {
         return false;
      }

      $this->db->query("UPDATE #__users SET password = '".md5($this->params->postString('password1'))."' WHERE id = ".$this->params->user_id);

      return true;
   }

   // Test, ob Pflichtfelder ausgefuellt sind
   public function checkAnmeldung($haendler) {
      $_SESSION['newsletter'] = $this->params->postCheckbox('newsletter');
      $test = false;

      // Adresse überprüfen
      $this->checkAdresse();
      $this->checkBestellung();

      // Bei Händleranmeldung nicht auf Hausnr überprüfen
      if ($haendler != 1 && strlen($this->user['hausnr']) < 1) {
         $this->user_err['adresse_err'] = true;
         $this->regerror = true;
      }

      $this->user['password1'] = $this->params->postString('password1');
//      $this->user['password2'] = $this->params->postString('password1');

      if (!isset($this->user['password1']) || $this->user['password1'] == '') {
         $this->user_err['password1_err'] = true;
         $this->regerror = true;
      }

      $this->user['password'] = md5(@$this->user['password1']);

      if (defined('CONF_HAEKCHEN') || $this->params->postInt('check_agb') > 0) {
         $this->user['daten'] = $this->params->postCheckbox('daten');
         if ($this->user['daten'] != 'y') {
            $this->user_err['daten_err'] = true;
            $this->regerror = true;
         }

         $this->user['agb'] = $this->params->postCheckbox('agb');
         if ($this->user['agb'] != 'y') {
            $this->user_err['agb_err'] = true;
            $this->regerror = true;
         }
      }

      if ((int)$this->user['staat'] == 10 && $this->user['staat2'] == '') {
         $this->user_err['staat2_err'] = true;
         $this->regerror = true;
      }

      // Altersverifizierung über Artikel
      //if (defined('CONF_MODULE_PERSOCHECK') && (defined('CONF_ALTER_PFLICHT') || defined('CONF_ALTER_PFLICHT') && $_SESSION['fsk_artikel']) && $this->params->firma['fsk_show'] != 'y' && $_SESSION['fsk_artikel']) {
      if (defined('CONF_MODULE_PERSOCHECK') && $this->params->firma['fsk_show'] != 'y' && $_SESSION['fsk_artikel']) {
         if (!$_SESSION['alter_check']) {
            $perso_nr = $this->params->postString('perso_nr', '', 'url');
            $_SESSION['perso_nr'] = $perso_nr;

            if ($perso_nr != '') {
               $perso = Control::getModulePersocheck();
               $test  = $perso->check_perso($perso_nr);

               if ($test->status) {
                  $changed = true;
                  $_SESSION['alter_check'] = true;
                  $_SESSION['write_alter'] = true;
                  $_SESSION['alter_typ'] = $test->typ;
                  $_SESSION['geb_datum']   = $test->geb_datum;
                  $this->user['geb_datum'] = $test->geb_datum;
                  $this->user['gebdatum'] = $test->geb_datum;
                  $this->user_err['perso_err'] = false;
                  $this->user_err['perso_err_msg'] = '';
               }

               else {
                  $this->alter_error = true;
                  $this->regerror = true;
                  $this->user_err['perso_err_msg'] = $test->txt;
               }
            }

            else {
               $this->user_err['perso_err'] = true;
               $this->regerror = true;
            }
         }

         else {
            $this->user['gebdatum'] = $_SESSION['geb_datum'];
         }
      }

      // Haendleranmeldung ?
      if ($this->params->postInt('haendler') == 1) {
         $website = Helper::checkUrl($this->params->postString('website'));
         if ($website == '') {
            $this->user_err['website_err'] = true;;
            $this->regerror = true;
         }
         $this->user['website'] = $website;
      }

      // Mail gültig?
      if ($this->user['email'] != $this->user['email2']) {
         $this->user_err['email2_err'] = true;
         $this->regerror = true;
      }

      if (!$this->checkMail($this->user['email'])) {
         $this->user_err['email_err'] = true;
         $this->user_err['email_msg'] = $this->params->text->get('kunde', 'fehler3');
         $this->regerror = true;
         $this->user['email2'] = '';
         $this->user_err['email2_err'] = false;
      }

      // Mail nicht vorhanden?
      else {
         $sql = "SELECT id, password FROM #__users WHERE email = '".$this->user['email']."'";
         $test = $this->db->query($sql);

         if ($test && $this->params->user_id < 1 && $this->db->getObject()->password != '') {
            $this->user_err['email_err'] = true;
            $this->user_err['email_msg'] = $this->params->text->get('kunde', 'fehler2');
            $this->regerror = true;
            $this->params->mailvorhanden = true;
            $this->user['email2'] = '';
            $this->user_err['email2_err'] = false;
         }
      }

      $this->params->setSession('user_err', $this->user_err);
      $this->params->setSession('user', $this->user);
      return ($this->regerror ? false : true);
   }

   public function setPaypal($user_id, $pp_mail, $pp_id) {
      $sql = "UPDATE #__users SET `pp_mail` = '$pp_mail', `pp_id` = '$pp_id' WHERE `id` = " .$user_id;
      $this->db->query($sql);
      return($this->db->last_sql);
   }

   // Coupon-Ecke / nicht registrierter User
   public function coupon($email, $password, $gutschein_id) {
      if (!$this->checkMail($email)) {
         return 'nomail';
      }

      if ($this->mailVorhanden($email)) {
         return 'mailvorhanden';
      }

      $sql = "INSERT INTO #__users SET email = '$email',
                                       password = '".md5($password)."',
                                       role = 10, newsletter = 'y',
                                       anrede = '',
                                       staat = 160,
                                       lang = '".$this->params->selected_lang."'";
//      $this->db->query($sql);

      $id = (int)$this->db->getNewId();
      $this->params->user_id = $id;
      $this->read($id);

      // Ist Coupon überhaupt vorhanden??
      $test = $this->db->query("SELECT * FROM #__gutscheine WHERE gutschein_id = 5");

      // Coupon gefunden
      if ($test == 1) {
         $data = $this->db->getObject();
         // Hat Kunde Coupon schon eingelöst?
         $test = $this->db->query("SELECT * FROM #__gutscheine_kunden WHERE code = '$data->code' AND email = '$email'");

         if ($test == 0) {
//            $this->db->query("INSERT INTO #__gutscheine_kunden VALUES(".$this->params->user_id.", '".$email."', '".$data->code."', ".$data->mode.", '".$data->wert."', '".$data->datum."', 'n')");
            $mail = Control::getMail();
            $mail->sendEmailGutschein($email, $gutschein_id, $data->code, $data->wert, $data->mode);
            return 'OK';
         }
         // Coupon wurde bereits aktiviert
         return 'couponactive';
      }

      return 'nocoupon';
   }

   // Newsletter ohne Account
   // Nicht mehr verwendet??? Auch bei shop77
   public function DELnewsletterUser($email, $newsletter) {
      // Eintrag anhand Email suchen (da kein PW vergeben)
      $data = $this->db->querySingleObject("SELECT id, password, newsletter FROM #__users WHERE email = '$email'");

      // Eintrag bereits vorhanden
      if ($data) {
         if ($newsletter == 'y' && $data && $data->newsletter == 'y') {
            return (int)$data->id;
         }

         else {
            // Wenn nicht registrierter Kunde löschen
            if (strlen($data->password < 20)) {
               $this->db->query("DELETE FROM #__users WHERE id = $data->id");
               return false;
            }
         }
      }

      // User eintragen mit Name, Vorname, Email
      else {
         $sql = "INSERT INTO #__users SET anrede = '', name = '', email = '$email', password = '', role = 9,
                                          vorname = '".$this->db->escape($this->user['vorname'])."', nachname = '".$this->db->escape($this->user['nachname'])."',
                                          firma = '', adresse = '', plz = '', ort = '', staat = ".$this->user['staat'].", newsletter = 'y'";
         $this->params->db->query($sql);
         // Test, dass Insert erfolgreich war und getNewId davon kommt
         $test = $this->db->getNewId();

         if ($test > 0) {
            return $test;
         }
      }

      return false;
   }

   public function setHaendler($user_id, $website, $email) {
      // User-Role auf 5 setzen
      $this->params->db->query("UPDATE #__users SET role='5', name = '$email' WHERE id = $user_id");

      // Eintrag in haendler vorhanden ?
      (int)$test = $this->params->db->querySingleValue("SELECT count(user_id) FROM #__haendler WHERE user_id = $user_id");
      // Eintrag vorhanden
      if ($test == 1) {
         // Webseite aktualisieren
         $this->params->db->query("UPDATE #__haendler SET website = '$website',
                                                          h_modified = '".date('Y-m-d')."'
                                                          WHERE user_id = $user_id");
      }

      // Sonst neuer Eintrag
      else {
         $this->params->db->query("INSERT INTO #__haendler SET user_id = $user_id,
                                                               haendler_nr = ".date('y').$user_id.",
                                                               website = '$website',
                                                               provision = '".str_replace(',', '.', $this->params->firma['provision'])."',
                                                               h_created = '".date('Y-m-d')."',
                                                               h_modified = '".date('Y-m-d')."'");
         // Bei Händlern gesperrt -> Haendler manuell freigeben
         $this->params->db->query("UPDATE #__users SET gesperrt = '".$this->params->firma['haendler_manual']."' WHERE id = $user_id");
      }

   }

   // TODO: Benachrichtigung, wenn Mail und damit Benutzername geändert wurde.
   private function mailChange() {
      return '';
   }

   public function changeLang($user_id, $lang) {
      $this->params->db->query("UPDATE #__users SET lang = '$lang' WHERE id = $user_id");
      $this->read($user_id);
   }
}
