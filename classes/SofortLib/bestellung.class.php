<?php
/*
###################################################################################
  FLOW Shopssoftware
  Entwicklungsstand: 12.03.2018 Version 6.2

  RoyalArt - Agentur für Softwaregestaltung
  http://www.royalart.de
  http://www.shopsoftware.com

  (c) Copyright by Dipl. Des. (FH) Sven Scholz - RoyalArt Agentur

  Copyrightvermerke duerfen NICHT entfernt werden!
  ------------------------------------------------------------------------
  Bei Verstoß gegen die Lizenzbedingungen kann die Lizenz jederzeit entzogen werden.
  Der Kaufpreises wird nicht erstattet. Wer gegen die Lizenzbedingungen verstoesst, muss
  mit einer Vertragsstrafe von 50.000 Euro je Einzeldelikt rechnen!
  ------------------------------------------------------------------------
  Dieses Programm ist eine Software von Dipl. Des. (FH) Sven Scholz, RoyalArt Agentur.
  Diese Software darf nicht veroeffentlicht, weitergeben und/oder modifizieren werden.
  Es gelten die Ihnen mitgeteilten Lizenzbestimmungen.
  Diese Software/Website ist eine Einzellizenz und für den Betrieb auf einem Speicherplatz
  (Webspace) berechtigt.
  Die Veroeffentlichung dieses Programms erfolgt OHNE IRGENDEINE GARANTIE, sogar ohne
  die implizite Garantie der MARKTREIFE oder der VERWENDBARKEIT FUER EINEN BESTIMMTEN ZWECK.

##################################################################################
  Copyrightvermerke duerfen NICHT entfernt werden!
*/

if (!defined('OBADJA')) {
   die ("This file connot run outside the FLOW&reg; Shopsoftware");
}

class OBADJA_Bestellung extends OBADJA_Bestellungen_BASE
{
   public $user;

   public function __construct() {
      parent::__construct();
      $this->user = Control::getUser();
   }

   // Bestellung in DB eintragen
   public function bestellungNeu($msg, $haendler_id = 0) {
      // Warenkorb nach params->warenkorb einlesen
      $warenkorb = Control::getWk();

      $g_preis = $_SESSION['wk_netto'] + $_SESSION['wk_steuer1'] + $_SESSION['wk_steuer2'] + $_SESSION['wk_steuer3']
               + $_SESSION['versand_ust'] + $_SESSION['zahlart_ust'];

      $zahlungsart = (int)$_SESSION['zahlungsart'];
      $zahlungsinfo1 = '';
      $zahlungsinfo2 = '';

      // Kundendaten in DB
      $this->params->email = $this->user->user['email'];
      $this->params->bestellnummer = $_SESSION['bestellnummer'];


      $gewerbe = 1;

      // Keine USt (Ausland?)
      if ($this->params->firma['tax_active'] != 'y') {
         $gewerbe = 2;
      }

      // Kleingewerbe
      if ($this->params->firma['kleingewerbe'] == 'y') {
         $gewerbe = 3;
      }

      // Bestelldaten aus Session übernehmen
      // Steuer Rabattgruppen ist bei wk_steuerX bereits berücksichtigt
      $provision = 0;
      if ($haendler_id != 0) {
         $provision = $this->db->querySingleValue("SELECT provision FROM #__haendler WHERE user_id = $haendler_id");
      }

      $gutschein = $_SESSION['gutschein'];

      $sql = "INSERT INTO #__rechnung SET
               haendler_id      = $haendler_id,
               user_id          = ".$this->params->user_id.",
               bestellnummer    = '".$this->db->escape($_SESSION['bestellnummer'])."',
               gewerbe          = ".$gewerbe.",
               netto            = '".$_SESSION['wk_summe_netto']."',
               versand          = '".$_SESSION['wk_versand']."',
               versand_ust      = '".$_SESSION['versand_ust']."',
               rabatt           = '".$_SESSION['wk_rabatt']."',
               user_rabatt      = '".$this->user->user['rabatt']."',
               gutschrift       = '".($_SESSION['wk_gutschrift'] + $gutschein)."',
               steuer1          = '".$_SESSION['wk_steuer1']."',
               steuer2          = '".$_SESSION['wk_steuer2']."',
               steuer3          = '".$_SESSION['wk_steuer3']."',
               zahlart_add      = '".$_SESSION['zahlart_preis']."',
               zahlart_ust      = '".$_SESSION['zahlart_ust']."',
               gutschein_code   = '".$_SESSION['gutschein_code']."',
               gutschein_brutto = '".$_SESSION['gutschein']."',
               gutschein_steuer = '".$_SESSION['gutschein_ust']."',
               provision        = '".$provision."',";

      // Steuersätze und Kundendaten
      $sql.= " steuersatz1      = '".$this->params->firma['tax1']."',
               steuersatz2      = '".$this->params->firma['tax2']."',
               steuersatz3      = '".$this->params->firma['tax3']."',
               waehrung_id      = '".$this->params->waehrung_id."',
               w_faktor         = '".$this->params->w_faktor."',

               lang_kunde       = '".$this->params->selected_lang."',
               anrede           = '".$this->user->user['anrede']."',
               vorname          = '".$this->db->escape($this->user->user['vorname'])."',
               nachname         = '".$this->db->escape($this->user->user['nachname'])."',
               email            = '".$this->db->escape($this->user->user['email'])."',
               telefon          = '".$this->db->escape($this->user->user['telefon'])."',
               firma            = '".$this->db->escape($this->user->user['firma'])."',
               adresse          = '".$this->db->escape($this->user->user['adresse'])."',
               hausnr           = '".$this->db->escape($this->user->user['hausnr'])."',
               plz              = '".$this->db->escape($this->user->user['plz'])."',
               ort              = '".$this->db->escape($this->user->user['ort'])."',
               buland           = '".$this->db->escape($this->user->user['buland'])."',
               staat            = '".$this->user->user['staat']."',
               staat2           = '".$this->user->user['staat2']."',
               ustid            = '".$this->db->escape($this->user->user['ustid'])."',

               lieferadresse    = '".$this->user->user['lieferadresse']."',";

      if ($this->user->user['lieferadresse'] == 'y') {
         $sql .= "lf_anrede     = '".$this->user->user['lf_anrede']."',
                  lf_vorname    = '".$this->db->escape($this->user->user['lf_vorname'])."',
                  lf_nachname   = '".$this->db->escape($this->user->user['lf_nachname'])."',
                  lf_firma      = '".$this->db->escape($this->user->user['lf_firma'])."',
                  lf_postnr     = '".$this->db->escape($this->user->user['lf_postnr'])."',
                  lf_adresse    = '".$this->db->escape($this->user->user['lf_adresse'])."',
                  lf_hausnr     = '".$this->db->escape($this->user->user['lf_hausnr'])."',
                  lf_plz        = '".$this->db->escape($this->user->user['lf_plz'])."',
                  lf_ort        = '".$this->db->escape($this->user->user['lf_ort'])."',
                  lf_buland     = '".$this->db->escape($this->user->user['lf_buland'])."',
                  lf_staat      = '".$this->user->user['lf_staat']."',
                  lf_staat2     = '".$this->user->user['lf_staat2']."',";
      }

      else {
         $sql .= "lf_anrede     = '',
                  lf_vorname    = '',
                  lf_nachname   = '',
                  lf_firma      = '',
                  lf_postnr     = '',
                  lf_adresse    = '',
                  lf_hausnr     = '',
                  lf_plz        = '',
                  lf_ort        = '',
                  lf_buland     = '',
                  lf_staat      = '',
                  lf_staat2     = '',";
      }

      $sql .= "zahlungsart      = '".$zahlungsart."',
               zahlungsinfo1    = '".$zahlungsinfo1."',
               zahlungsinfo2    = '".$zahlungsinfo2."',
               msg_kunde        = '".$this->db->escape($msg)."', ";

      if ($zahlungsart == 9) {
         $sql .= "bank_name        = '".$this->db->escape($this->user->user['kk_name'])."',
                  bank_inhaber     = '".$this->db->escape($this->user->user['kk_inhaber'])."',
                  bank_iban        = '".$this->db->escape(Helper::checkString($this->user->user['kk_nr']))."',
                  bank_bic         = '".$this->user->user['kk_datum']."',";
      }

      else {
         $sql .= "bank_name        = '".$this->db->escape($this->user->user['bank_name'])."',
                  bank_inhaber     = '".$this->db->escape($this->user->user['bank_inhaber'])."',
                  bank_iban        = '".$this->db->escape($this->user->user['bank_iban'])."',
                  bank_bic         = '".$this->db->escape($this->user->user['bank_bic'])."',";
      }

      $sql .= "prov_re_nr = '',
               prov_re_datum = 0,
               wk             = '".$this->params->user_id.': '.$this->db->escape(json_encode($this->params->warenkorb))."'";

      if ((int)$this->params->firma['version'] > 9) {
         // Fehler bei Aktualisierung Browser
         if (isset($_SESSION['widerruf_wk']) && $_SESSION['widerruf_wk'] > 0)
            $sql .= " , widerruf = '".$_SESSION['widerruf_wk']."'";
      }

      else {
            $sql .= " , widerruf = '1'";
      }

      if (!$this->db->query($sql)) {
         return false;
      }

      
        $last_id = $this->db->getNewId();

      if (defined('CONF_PORTAL') && $this->params->user_id > 0) {
         $role = 9;
         $newrole = (int)$this->user->user['role'];
         if ($newrole >= 10) {
            $role = 10;
         }

         $hrole = (int)$this->db->querySingleValue("SELECT kundengruppe FROM #__kunde_haendler WHERE haendler_id = $haendler_id AND kunden_id = ".$this->params->user_id);

         // Eintrag vorhandne
         if ($hrole != null) {
            // Bei Händler nicht verifiziert und User nicht Verifiziert -> Neukunde
            if ($role > $hrole) {
               $this->db->query("UPDATE #__kunde_haendler SET kundengruppe = $role WHERE haendler_id = $haendler_id AND kunden_id = ".$this->params->user_id);
            }
         }

         // Kunde für Händler eintragen
         else {
            $this->db->query("INSERT INTO #__kunde_haendler SET haendler_id = $haendler_id, kunden_id = ".$this->params->user_id.", kundengruppe = $role");
         }
      }

      // Artikel in DB eintragen
      $shop_lang = $this->params->default_lang;
      $kunde_lang = $this->params->selected_lang;

      // Artikel speichern
      for ($i = 0; $i < count($this->params->warenkorb); $i++) {
         $wk = $this->params->warenkorb[$i];

         // Nur gewählter Händler, wenn $haendler_id gesetzt (Portal)
         if ($haendler_id != 0 && $haendler_id != $wk['haendler_id']) {
            continue;
         }

         // Motiv-Datei verschieben und Name in WK ändern
         if (defined('CONF_MODULE_MOTIVUL') && ($wk['motiv_uploadp_check'] == 'y' || $wk['motiv_uploadt_check'] == 'y')) {
            if ($wk['motiv_upload_name'] != '') {
               $tmpdir = $this->params->filepath.'/tmp/';
               $dir = $this->params->filepath.'/downloads/motiv_dateien/';

               if (!is_dir($dir)) {
                  mkdir($dir);
               }

               $tmp = explode('.', $wk['motiv_upload_name']);
               $name = str_replace("'", '', $this->user->user['nachname'].'_'.$this->user->user['vorname'].'_'.date('Ymd-His').'.'.$tmp[count($tmp) - 1]);

               copy($tmpdir.$wk['motiv_upload_name'], $dir.$name);
               $wk['motiv_upload_name'] = $name;
            }
         }

         //$this->_setArticle($last_id, $wk['art_id'], $wk['art_menge'], $shop_lang, $kunde_lang, $wk['foto_sort'], $wk['motiv_upload_name'], $wk['motiv_upload_text'], $wk['configurator']);
         $this->_setArticle($last_id, $shop_lang, $kunde_lang, $wk);
      }

      // Gutschrift korrigieren (aus User-Daten)
      $gutschrift = $_SESSION['wk_gutschrift'];
      if ($gutschrift > 0) {
         $sql = "UPDATE #__users SET gutschrift = (gutschrift - $gutschrift) WHERE id = ".$this->user->user['id'];
         $query = $this->db->query($sql);
// ???         $this->user->user['rabatt'] -= $rabatt;
         $this->user->user['gutschrift'] -= $gutschrift;
      }

      // Gutschein als eingelöst in DB eintragen
      if (isset($_SESSION['gutschein_code']) && $_SESSION['gutschein_code'] != '') {
         $sql = "INSERT INTO #__gutscheine_kunden SET user_id = ".$this->params->user_id.", email = '".$this->user->user['email']."', code = '".$_SESSION['gutschein_code']."', mode = '".$_SESSION['gutschein_mode']."', wert = '".$_SESSION['gutschein_wert']."', datum = '".$_SESSION['gutschein_datum']."', eingeloest = 'y' ON DUPLICATE KEY UPDATE datum = '".date('Y-m-d')."'";
         $this->db->query($sql);
         unset($_SESSION['gutschein_code']);
         unset($_SESSION['gutschein_mode']);
         unset($_SESSION['gutschein_wert']);
         unset($_SESSION['gutschein_datum']);
      }

      $this->checkZahlart($last_id, $g_preis);
      return $last_id;
   }

   public function checkZahlart ($id, $preis) {
      $zahlungsart = $_SESSION['zahlungsart'];
      switch ($zahlungsart) {

         // Paypal
         case 2:
            $paypal = Control::getPaypal();
//            $paypal->bezahlen($preis, $_SESSION['bestellnummer'], $this->user->user['pp_mail']);
            $paypal->bezahlen($preis, $_SESSION['bestellnummer'], $this->user->user, $id);
            unset($_SESSION['gesamt_show']);
            // Redirect durch Paypal-Klasse
            // nachfolgender Code wird ausgeführt !!!
            break;

         // Sofortüberweisung
         case 7:
            $sofort = Control::getSofortUeberweisung();
            $sofort->bezahlen($preis, $_SESSION['bestellnummer'], $this->params->email, Helper::waehrungText($this->params->firma['waehrung1'], 2));
            break;

         // vrpay
         case 8:
            $vrpay = Control::getVrpay();
            $vrpay->bezahlen($preis, $_SESSION['bestellnummer']);
            unset($_SESSION['gesamt_show']);
            // Redirect durch Vrpay-Klasse
            // nachfolgender Code wird ausgeführt !!!
            break;

         // PaypalPlus
         case 10:
//            $paypal = Control::getPaypal();
//            $paypal->bezahlenPlus($preis, $_SESSION['bestellnummer'], $this->user->user['pp_mail']);
//            unset($_SESSION['gesamt_show']);
            // Redirect durch Paypal-Klasse
            // nachfolgender Code wird ausgeführt !!!
            break;

         // Amazon
         case 11:
            $amazon = Control::getAmazon();
            $amazon->bezahlen($preis, $_SESSION['bestellnummer'], $this->user->user['email']);
            unset($_SESSION['gesamt_show']);
            // Redirect durch Amazon-Klasse
            // nachfolgender Code wird ausgeführt !!!
            break;

         // Lastschrift
         default:
            break;
      }
   }

   // Antwort von Paypal, wenn Kunde bezahlt hat
   public function paypalNotify() {
      $best_nr    = $_REQUEST['item_number'];
      $payer_id   = $_REQUEST['payer_id'];
      $payer_mail = $_REQUEST['payer_email'];
      $track_id   = $_REQUEST['ipn_track_id'];
      $betrag     = $_REQUEST['mc_gross'];
      $user_id    = $_REQUEST['custom'];

      $re_nr = $this->db->getRechnungsnummer();
      $re_dat = date('Y-m-d');

      $sql = "UPDATE #__rechnung SET
               `zahlungsinfo1`       = '$payer_mail',
               `zahlungsinfo2`       = '$track_id',
               `rechnungsnummer`     = '$re_nr',
               `rechnungsdatum`      = '$re_dat',
               `zahlungdatum`        = '$re_dat',
               `zahlung`             = '$betrag'
              WHERE `bestellnummer` = '$best_nr'";
      $this->db->query($sql);

      if (defined('CONF_PAYPAL_DEBUG')) {
         $fp = fopen($this->params->filepath.'/paypal_notify.txt', 'a');
         fwrite($fp, date('d.m.Y H:i:s')."\n".print_r($_REQUEST, true)."\n");
         fclose($fp);
      }

      if ((int)$user_id != 0) {
         $this->user->setPaypal($user_id, $payer_mail, $payer_id);
      }

      echo '<!DOCTYPE html><html><head><title><title><head><body></body></html>';
      exit;
   }

   // Nachschauen, ob Notify von Paypal erfolgreich war
   public function checkPaypal($id, $loop = 0) {
      $sql = "SELECT zahlungsinfo2 FROM #__rechnung WHERE bestellnummer = '".$this->db->escape($id)."'";
      if ($this->db->query($sql) != 1) {
         if (defined('CONF_PAYPAL_DEBUG')) {
            $fp = fopen($this->params->filepath.'/paypal_check.txt', 'a');
            fwrite($fp, date('d.m.Y H:i:s')."\nKein Eintrag in DB\n");
            fclose($fp);
         }

         $loop++;
         if ($loop < 4) {
            return false;
         }

         else {
            sleep(2);
            $this->checkPaypal($id, $loop);
         }
      }

      else {
         $data = $this->db->getObject();
         if ($data->zahlungsinfo2 == '') {
            if (defined('CONF_PAYPAL_DEBUG')) {
               $fp = fopen($this->params->filepath.'/paypal_check.txt', 'a');
               fwrite($fp, date('d.m.Y H:i:s')."\nKein Transaktionscode\n");
               fclose($fp);
            }
            return false;
         }

         if (defined('CONF_PAYPAL_DEBUG')) {
            $fp = fopen($this->params->filepath.'/paypal_check.txt', 'a');
            fwrite($fp, date('d.m.Y H:i:s')."\nOK\n");
            fclose($fp);
         }
         return true;
      }
   }

   // Antwort von VRpay, wenn Kunde bezahlt hat
   public function vrpayNotify() {
      if (defined('CONF_VRPAY_DEBUG')) {
         $fp = fopen($this->params->filepath.'/vrpay_notify.txt', 'a');
         fwrite($fp, date('d.m.Y H:i:s')."\n".print_r($_POST, true)."\n");
         fclose($fp);
      }

      $error = false;

      if(isset($_POST['RES_STATE'])) {
         //do something in case of the transaction state
         switch ($_POST['RES_STATE']) {
            case "RESERVED":
               //TODO: action when state is RESERVED
                 break;

            case "PURCHASED":
               //TODO: action when state is PURCHASED
               $notify_id      = $_REQUEST['NOTIFY_ID'];
               $secret         = $_REQUEST['NOTIFY_SECRET'];
               $res_aid        = $_REQUEST['RES_AID'];
               $res_date       = $_REQUEST['RES_DATE'];
               $res_msg        = $_REQUEST['RES_MESSAGE'];
               $trx_action     = $_REQUEST['TRX_ACTION'];
               $betrag         = ((int)$_REQUEST['TRX_AMOUNT'] / 100);
               $trx_brand      = $_REQUEST['TRX_BRAND'];
               $trx_cardno     = $_REQUEST['TRX_CARDNO'];
               $trx_currency   = $_REQUEST['TRX_CURRENCY'];
               $trx_expiredate = $_REQUEST['TRX_EXPIRYDATE'];
               $best_nr        = $_REQUEST['TRX_REFNO'];

               $re_nr = $this->db->getRechnungsnummer();
               $re_dat = date('Y-m-d');

               $sql = "UPDATE #__rechnung
               SET `zahlungsinfo1`   = '$trx_brand',
               `zahlungsinfo2`       = '$trx_cardno / $trx_expiredate',
               `rechnungsnummer`     = '$re_nr',
               `rechnungsdatum`      = '$re_dat',
               `zahlungdatum`        = '$re_dat',
               `zahlung`             = '$betrag'
               WHERE `bestellnummer` = '$best_nr'";

               $this->db->query($sql);
               break;

            case "REJECTED":
               //TODO: action when state is REJECTED
                 break;
            case "CANCELED":
               //TODO: action when state is CANCELED
                 break;
            case "REVERSED":
               //TODO: action when state is REVERSED
               break;
            case "PURCHASE.REVERSAL":
               //TODO: action when state is PURCHASE.REVERSAL
               break;

            case "PROCESSING":
               //TODO: action when state is PROCESSING
               break;
            default:
               $error = true;
         }
      }
   }

   // Nachschauen, ob Notify con VRpay erfolgreich war
   public function checkVrpay($id) {
      $sql = "SELECT zahlungsinfo2 FROM #__rechnung WHERE bestellnummer = '$id'";
      if ($this->db->query($sql) != 1) {
         if (defined('CONF_VRPAY_DEBUG')) {
            $fp = fopen($this->params->filepath.'/vrpay_check.txt', 'a');
            fwrite($fp, date('d.m.Y H:i:s')."\nKein Eintrag in DB\n");
            fclose($fp);
         }
         return false;
      }

      $data = $this->db->getObject();
      if ($data->zahlungsinfo2 == '') {
         if (defined('CONF_VRPAY_DEBUG')) {
            $fp = fopen($this->params->filepath.'/vrpay_check.txt', 'a');
            fwrite($fp, date('d.m.Y H:i:s')."\nKeine Bestellnummer in DB\n");
            fclose($fp);
         }
         return false;
      }

      if (defined('CONF_VRPAY_DEBUG')) {
         $fp = fopen($this->params->filepath.'/vrpay_check.txt', 'a');
         fwrite($fp, date('d.m.Y H:i:s')."\nOK\n");
         fclose($fp);
      }
      return true;
   }

   // Antwort von Sofortüberweisung, wenn Kunde bezahlt hat
   public function sofortueberweisungNotify() {
      $api_key = $this->params->firma['sofort_key'];
      $notification = Control::getSofortLibNotification();
      $notification->getNotification();
      $transactionId = $notification->getTransactionId();

      // Status abrufen
      $data = Control::getSofortLibData($api_key);
      $data->setTransaction($transactionId);
      $data->sendRequest();

      $trans_id = $data->getTransaction();
      $betrag   = $data->getAmount();
      $waehrung = $data->getCurrency();
      $status   = $data->getStatus();
      $reason   = $data->getStatusReason();
      $best_nr  = $data->getUserVariable(0,0);

      if (defined('CONF_SOFORT_DEBUG')) {
         $fp = fopen("sofort_notify.txt","w");
         fwrite($fp, print_r($data, true));
         fclose($fp);
      }

      $re_nr = $this->db->getRechnungsnummer();
      $re_dat = date('Y-m-d');

      $sql = "UPDATE #__rechnung SET `zahlungsinfo1`   = '$trans_id',
                                     `zahlungsinfo2`   = '$status',
                                     `rechnungsnummer` = '$re_nr',
                                     `rechnungsdatum`  = '$re_dat',
                                     `zahlungdatum`    = '$re_dat',
                                     `zahlung`         = '$betrag'
              WHERE `bestellnummer` = '$best_nr'";
      $this->db->query($sql);
      exit;
   }

   // Nachschauen, ob Notify von Sofortüberweisung erfolgreich war
   public function checkSofort($id) {
      // Warten auf Notification
      for ($i = 0; $i < 5; $i++) {
         $sql = "SELECT zahlungsinfo1 FROM #__rechnung WHERE bestellnummer = '$id'";
         if ($this->db->query($sql) != 1) {
            if (defined('CONF_SOFORT_DEBUG')) {
               $fp = fopen($this->params->filepath.'/sofort_check.txt', 'a');
               fwrite($fp, date('d.m.Y H:i:s')."\nKein Eintrag in DB\n");
               fclose($fp);
            }
            return false;
         }

         $data = $this->db->getObject();
         if ($data->zahlungsinfo1 == '') {
            sleep(1);
         }
         else {
            if (defined('CONF_SOFORT_DEBUG')) {
               $fp = fopen($this->params->filepath.'/sofort_check.txt', 'a');
               fwrite($fp, date('d.m.Y H:i:s')."\nOK\n");
               fclose($fp);
            }
            return true;
         }
      }

      if (defined('CONF_SOFORT_DEBUG')) {
         $fp = fopen($this->params->filepath.'/sofort_check.txt', 'a');
         fwrite($fp, date('d.m.Y H:i:s')."\nKeine Bestellnummer zurück erhalten\n");
         fclose($fp);
      }
      return false;
   }

   public function amazonNotify() {
      $best_nr    = $_REQUEST['item_number'];
      $payer_id   = $_REQUEST['payer_id'];
      $payer_mail = $_REQUEST['payer_email'];
      $track_id   = $_REQUEST['ipn_track_id'];
      $betrag     = $_REQUEST['mc_gross'];
      $user_id    = $_REQUEST['custom'];

      $re_nr = $this->db->getRechnungsnummer();
      $re_dat = date('Y-m-d');

      $sql = "UPDATE #__rechnung SET
               `zahlungsinfo1`       = '$payer_mail',
               `zahlungsinfo2`       = '$track_id',
               `rechnungsnummer`     = '$re_nr',
               `rechnungsdatum`      = '$re_dat',
               `zahlungdatum`        = '$re_dat',
               `zahlung`             = '$betrag'
              WHERE `bestellnummer` = '$best_nr'";
      $this->db->query($sql);

      if (defined('CONF_AMAZON_DEBUG')) {
         $fp = fopen($this->params->filepath.'/amazon_notify.txt', 'a');
         fwrite($fp, date('d.m.Y H:i:s')."\n".print_r($_REQUEST, true)."\n");
         fclose($fp);
      }

      if ((int)$user_id != 0) {
         $this->user->setPaypal($user_id, $payer_mail, $payer_id);
      }

      echo '<!DOCTYPE html><html><head><title><title><head><body></body></html>';
      exit;
   }

   // Nachschauen, ob Notify von Paypal erfolgreich war
   public function checkAmazon($id) {
      $sql = "SELECT zahlungsinfo2 FROM #__rechnung WHERE bestellnummer = '$id'";
      if ($this->db->query($sql) != 1) {
         if (defined('CONF_AMAZON_DEBUG')) {
            $fp = fopen($this->params->filepath.'/amazon_check.txt', 'a');
            fwrite($fp, date('d.m.Y H:i:s')."\nKein Eintrag in DB\n");
            fclose($fp);
         }
         return false;
      }

      $data = $this->db->getObject();
      if ($data->zahlungsinfo2 == '') {
         if (defined('CONF_AMAZON_DEBUG')) {
            $fp = fopen($this->params->filepath.'/paypal_check.txt', 'a');
            fwrite($fp, date('d.m.Y H:i:s')."\nKein Transaktionscode\n");
            fclose($fp);
         }
         return false;
      }

      if (defined('CONF_AMAZON_DEBUG')) {
         $fp = fopen($this->params->filepath.'/paypal_check.txt', 'a');
         fwrite($fp, date('d.m.Y H:i:s')."\nOK\n");
         fclose($fp);
      }
      return true;
   }
}
?>