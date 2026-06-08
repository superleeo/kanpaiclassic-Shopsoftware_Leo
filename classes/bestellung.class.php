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

//require_once 'classes/base/articles_base.class.php';
//require_once 'classes/base/helper.class.php';

class KANPAICLASSIC_bestellung extends KANPAICLASSIC_bestellungenBase
{
   public $user;

   public function __construct() {
      parent::__construct();
      $this->user = Control::getUser();
   }

   // Bestellung in DB eintragen
   public function bestellungNeu($msg) {
      // Warenkorb nach params->warenkorb einlesen
      $warenkorb = Control::getWk();

      $abholung_checkbox = $_SESSION['abholung_checkbox'];

      $g_preis = $_SESSION['wk_summe_netto'] + $_SESSION['wk_steuer1'] + $_SESSION['wk_steuer2'] + $_SESSION['wk_steuer3']
               - ($_SESSION['wk_rabatt'] + $_SESSION['wk_rabatt_ust'])
               + $_SESSION['wk_versand'] + $_SESSION['versand_ust']
               + $_SESSION['zahlart_preis'] + $_SESSION['zahlart_ust']
               - $_SESSION['wk_gutschrift']
               - $_SESSION['gutschein'];

      $zahlungsart   = (int)$_SESSION['zahlungsart'];
      $zahlungsinfo1 = '';
      $zahlungsinfo2 = '';

      if ($zahlungsart == 11) {
         $zahlungsinfo1 = 'Rückmeldung in 3 min';
      }

      // Kundendaten in DB
      $this->params->email = $this->user->user['email'];
      $this->params->bestellnummer = $_SESSION['bestellnummer'];


      $gewerbe     = 1;
      $gewerbeinfo = '';

      // Keine USt (Ausland?)
      if (!Helper::checkSteuer($_SESSION['rechnung_land'], $_SESSION['wk_land']) || $this->params->firma['tax_active'] != 'y') {
         $gewerbe = 2;
      }

      // Kleingewerbe
      if ($this->params->firma['kleingewerbe'] == 'y') {
         $gewerbe = 3;
      }

      // Bestelldaten aus Session übernehmen
      // Steuer Rabattgruppen ist bei wk_steuerX bereits berücksichtigt
      $netto            = $_SESSION['wk_summe_netto'];
      $steuer1          = $_SESSION['wk_steuer1'];
      $steuer2          = $_SESSION['wk_steuer2'];
      $steuer3          = $_SESSION['wk_steuer3'];
      $versand          = $_SESSION['wk_versand'];
      $versand_ust      = $_SESSION['versand_ust'];
      $zahlart_add      = $_SESSION['zahlart_preis'];
      $zahlart_ust      = $_SESSION['zahlart_ust'];
      $brutto           = $netto + $steuer1 + $steuer2 + $steuer3 + $versand + $versand_ust + $zahlart_add + $zahlart_ust;

      $gutschein = $_SESSION['gutschein'];



      $sql = "INSERT INTO #__rechnung SET
               haendler_id      = 0,
               user_id          = ".$this->params->user_id.",
               bestellnummer    = '".$this->db->escape($_SESSION['bestellnummer'])."',
               gewerbe          = ".$gewerbe.",
               gewerbeinfo      = '".$gewerbeinfo."',

               netto            = '".$netto."',


               abholung_checkbox            = '".$abholung_checkbox."',
               steuer1          = '".$steuer1."',
               steuer2          = '".$steuer2."',
               steuer3          = '".$steuer3."',
               versand          = '".$versand."',
               versand_ust      = '".$versand_ust."',
               zahlart_add      = '".$zahlart_add."',
               zahlart_ust      = '".$zahlart_ust."',

               rabatt           = '".$_SESSION['wk_rabatt']."',
               user_rabatt      = '".$this->user->user['rabatt']."',
               gutschrift       = '".($_SESSION['wk_gutschrift'] + $gutschein)."',
               gutschein_code   = '".$_SESSION['gutschein_code']."',
               /* Bei Print-Gutscheinen voller Wert */
               gutschein_brutto = '".(isset($_SESSION['Print_Gutschein']) ? $_SESSION['Print_Gutschein'] : $_SESSION['gutschein'])."',
               gutschein_steuer = '".$_SESSION['gutschein_ust']."',

               steuersatz1      = '".$this->params->firma['tax1']."',
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
               gebdatum         = '".$this->db->escape($this->user->user['gebdatum'])."',

               lieferadresse    = 'y',
               lf_anrede        = '".$this->user->user['lf_anrede']."',
               lf_vorname       = '".$this->db->escape($this->user->user['lf_vorname'])."',
               lf_nachname      = '".$this->db->escape($this->user->user['lf_nachname'])."',
               lf_firma         = '".$this->db->escape($this->user->user['lf_firma'])."',
               lf_postnr        = '".$this->db->escape($this->user->user['lf_postnr'])."',
               lf_adresse       = '".$this->db->escape($this->user->user['lf_adresse'])."',
               lf_hausnr        = '".$this->db->escape($this->user->user['lf_hausnr'])."',
               lf_plz           = '".$this->db->escape($this->user->user['lf_plz'])."',
               lf_ort           = '".$this->db->escape($this->user->user['lf_ort'])."',
               lf_buland        = '".$this->db->escape($this->user->user['lf_buland'])."',
               lf_staat         = '".$this->user->user['lf_staat']."',
               lf_staat2        = '".$this->user->user['lf_staat2']."',

               zahlungsart      = '".$zahlungsart."',
               zahlungsinfo1    = '".$zahlungsinfo1."',
               zahlungsinfo2    = '".$zahlungsinfo2."',
               msg_kunde        = '".$this->db->escape($msg)."', ";

      if ($zahlungsart == 9) {
         $sql .= "bank_name        = '".$this->db->escape($this->user->user['kk_name'])."',
                  bank_inhaber     = '".$this->db->escape($this->user->user['kk_inhaber'])."',
                  bank_iban        = '".$this->db->escape(Helper::checkString($this->user->user['kk_nr']))."',
                  bank_bic         = '".$this->user->user['kk_datum'].':::'.$this->user->user['kk_pruef']."',";
      }

      else {
         $sql .= "bank_name        = '".$this->db->escape($this->user->user['bank_name'])."',
                  bank_inhaber     = '".$this->db->escape($this->user->user['bank_inhaber'])."',
                  bank_iban        = '".$this->db->escape($this->user->user['bank_iban'])."',
                  bank_bic         = '".$this->db->escape($this->user->user['bank_bic'])."',";
      }

      $sql .= "prov_re_nr    = '',
               prov_re_datum = 0,
               wk            = '".$this->params->user_id.': '.$this->db->escape(json_encode($this->params->warenkorb))."'";

      if (isset($_SESSION['widerruf_wk']) && $_SESSION['widerruf_wk'] > 0) {
         $widerruf = $_SESSION['widerruf_wk'];
      }

      // Widerruf DL
      if ($widerruf == 4 && isset($_SESSION['widerruf_dl']) && $_SESSION['widerruf_dl'] == 'y') {
         $widerruf = 14;
      }

      $sql .= " , widerruf = '$widerruf'";
      $sql .= " ,ds_gvo_check = '".(isset($_SESSION['ds_gvo_check']) ? $_SESSION['ds_gvo_check'] : 'n')."'";

      // EU-Reverse Charge Text
      if ($this->params->firma['tax_active'] == 'y' && $this->params->firma['kleingewerbe'] == 'n' && $this->user->user['ustid'] != '' && file_exists(ADMIN_PATH.'/zahlart.json')) {
         $shop      = $this->db->querySingleObject("SELECT id, region FROM #__laender WHERE name LIKE '".$this->params->firma['country']."%' OR name_shop LIKE '".$this->params->firma['country']."%'");
         $kunde_reg = $this->db->querySingleValue("SELECT region FROM #__laender WHERE id = ".$this->user->user['lf_staat']);

         if ($shop && $shop->region == 'eu' && $kunde_reg == 'eu' && (int)$shop->id != (int)$this->user->user['lf_staat']) {
            $za_text = json_decode(file_get_contents(ADMIN_PATH.'/zahlart.json'));

            if (isset($za_text->{'za99_'.$this->params->selected_lang})) {
               $sql .= ", msg_admin = '".$this->db->escape(str_ireplace('[TRENNER]', "\n", $za_text->{'za99_'.$this->params->selected_lang}))."'";
            }
         }
      }

      if (!$this->db->query($sql)) {
         return false;
      }

      $last_id = $this->db->getNewId();

      if(defined('CONF_MODULE_BONUSPROGRAMM')){
          Control::getModuleBonusprogramm()->addGutschrift($this->db->escape($_SESSION['bestellnummer']));
      }


      // ZA-Automatik bei Amazon-Payments
      // amazonNotify() ???
      if (isset($_SESSION['AmazonAuthorizationId'])) {
         $re_dat = date('Y-m-d');
         $sql    = '';

         if (Helper::getData('za_automatik', 'n') == 'y') {
            $re_nr  = $this->db->getRechnungsnummer();

            $sql = "UPDATE #__rechnung SET
                     `zahlungsinfo1`       = 'Amazon-Referenz-ID".$_SESSION['AmazonWarning']."',
                     `zahlungsinfo2`       = '".$_SESSION['AmazonAuthorizationId']."',
                     `rechnungsnummer`     = '$re_nr',
                     `rechnungsdatum`      = '$re_dat',
                     `zahlungdatum`        = '$re_dat',
                     `zahlung`             = '$g_preis',
                     `pdf`                 = 'r'
                    WHERE `id`  = $last_id";

            $this->db->query($sql);
         }

         else {
            $sql = "UPDATE #__rechnung SET
                     `zahlungsinfo1`       = 'Amazon-Referenz-ID".$_SESSION['AmazonWarning']."',
                     `zahlungsinfo2`       = '".$_SESSION['AmazonAuthorizationId']."',
                     `zahlungdatum`        = '$re_dat',
                     `zahlung`             = '$g_preis'
                    WHERE `id`  = $last_id";

            $this->db->query($sql);
         }

         unset($_SESSION['AmazonAuthorizationId']);
         unset($_SESSION['AmazonWarning']);
      }

      // Artikel in DB eintragen
      $shop_lang  = $this->params->default_lang;
      $kunde_lang = $this->params->selected_lang;

      // Artikel einzeln speichern
      for ($i = 0; $i < count($this->params->warenkorb); $i++) {
         $wk = $this->params->warenkorb[$i];

         // Motiv-Datei verschieben und Name in WK ändern
         if (defined('CONF_MODULE_MOTIVUL') && ($wk['motiv_uploadp_check'] == 'y' || $wk['motiv_uploadt_check'] == 'y')) {
            if ($wk['motiv_upload_name'] != '') {
               $name   = '';
               $tmpdir = SHOP_PATH.'/tmp/';
               $dir    = SHOP_PATH.'/downloads/motiv_dateien/';

               if (!file_exists(SHOP_PATH.'/downloads/.htaccess') || filesize(SHOP_PATH.'/downloads/.htaccess') > 3) {
                  file_put_contents(SHOP_PATH.'/downloads/.htaccess', '');
               }

               if (!is_dir($dir)) {
                  mkdir($dir);
               }

               // Für Extension
               $tmp  = explode('.', $wk['motiv_upload_name']);
               $name = str_replace("'", '', $this->user->user['nachname'].'_'.$this->user->user['vorname'].'_'.date('Ymd-Hi').'_'.($i +1).'.'.$tmp[count($tmp) - 1]);

               copy($tmpdir.$wk['motiv_upload_name'], $dir.$name);
               @unlink($tmpdir.$wk['motiv_upload_name']);
               $wk['motiv_upload_name'] = $name;
            }
         }

         $this->_setArticle($last_id, $shop_lang, $kunde_lang, $wk);
      }

      // Gutschrift korrigieren (aus User-Daten)
      $gutschrift = $_SESSION['wk_gutschrift'];

      // Gutschrift des Kunden korrigieren
      if ($gutschrift > 0) {
         // In DB speichern
         $sql = "UPDATE #__users SET gutschrift = (gutschrift - $gutschrift) WHERE id = ".$this->user->user['id'];
         $this->db->query($sql);
         $gutschrift_neu = (float)$this->db->querySingleValue("SELECT gutschrift FROM #__users WHERE id = ".$this->user->user['id']);
         // user::user und Session korrigieren
         $this->user->user['gutschrift'] = $gutschrift_neu;
         $_SESSION['user']['gutschrift'] = $gutschrift_neu;
      }

      // Gutschein als eingelöst in DB eintragen
      if (isset($_SESSION['gutschein_code']) && $_SESSION['gutschein_code'] != '') {
         // Gutscheine-Print löschen
         if (isset($_SESSION['gutschein_print'])) {
            $this->db->query("UPDATE #__gutscheine_print SET deleted = 'y' WHERE code ='".$_SESSION['gutschein_code']."'");
         }

         // Gutschein für Kunden als eingelöst merken
         else {
            $this->db->query("INSERT INTO #__gutscheine_kunden SET user_id = ".$this->params->user_id.", email = '".$this->user->user['email']."', code = '".$_SESSION['gutschein_code']."', mode = '".$_SESSION['gutschein_mode']."', wert = '".$_SESSION['gutschein_wert']."', datum = NOW(), eingeloest = 'y' ON DUPLICATE KEY UPDATE datum = '".date('Y-m-d')."'");
         }

         // Print-Gutscheien nicht ausgenutzt
         if ((int)$this->user->user['id'] > 0 && isset($_SESSION['Print_Gutschein']) && (float)$_SESSION['Print_Gutschein'] > $brutto) {
            $gutschrift = (float)$_SESSION['Print_Gutschein'] - $brutto;
            $this->db->query("UPDATE #__users SET gutschrift = (gutschrift + $gutschrift) WHERE id = ".$this->user->user['id']);
            $this->user->user['gutschrift'] += $gutschrift;
         }

         unset($_SESSION['gutschein_code']);
         unset($_SESSION['gutschein_mode']);
         unset($_SESSION['gutschein_wert']);
         unset($_SESSION['gutschein_datum']);
         unset($_SESSION['gutschein_print']);
         unset($_SESSION['Print_Gutschein']);
      }

      $_SESSION['bestzus_adresse'] = '';

      // Nur bei Bezahlung mit Amazon
      if (Helper::getData('za_automatik', 'n') == 'y' && isset($_SESSION['AmazonAuthorizationId'])) {
         $this->im_export->exportBuchungenAuto($last_id);
      }


      // Aktionen Zahlart-abhängig
      $this->checkZahlart($last_id, round($g_preis, 2), $this->user->user['email']);

      return $last_id;
   }

   public function checkZahlart ($re_id, $preis, $email) {
      $zahlungsart = $_SESSION['zahlungsart'];

      switch ($zahlungsart) {
         // Vorkasse
         case 1:
            break;

         // Paypal
         case 2:
            $artikel_nummern = $this->getArtikelnummern($re_id);
            $paypal = Control::getPaypal();
            $paypal->init($this->params->firma['paypal_mail']);

            $paypal->bezahlen($preis, $_SESSION['bestellnummer'], $this->user->user, $artikel_nummern);
            unset($_SESSION['gesamt_show']);
            // (kein) Redirect durch Paypal-Klasse
            // nachfolgender Code wird ausgeführt !!!
            break;

         // Lastschrift
         case 3:
            break;

         //Nachnahme
         case 4:
            break;

         // Rechnung
         case 5:
            if (Helper::getData('za_automatik', 'n') == 'y') {
               $re_nr  = $this->db->getRechnungsnummer();
               $re_dat = date('Y-m-d');

               $this->db->query("UPDATE #__rechnung SET
                                   `rechnungsnummer` = '$re_nr',
                                   `rechnungsdatum`  = '$re_dat',
                                   `status`          = 2,
                                   `pdf`             = 'r'
                                 WHERE `id`  = $re_id");

               $mail = Control::getMail();
               $mail->sendBestellung($email, $re_id, true);
               $this->im_export->exportBuchungenAuto($re_id);
            }

            break;

         // Bar
         case 6:
            break;

         // Sofortüberweisung
         case 7:
//            ini_set('display_errors', 0);
            // Verursacht Deprecated
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

         //KKLastschrift
         case 9:
            break;

         // PaypalPlus
         case 10:
            // Redirect durch Modul PaypalPlus
            // za_automatik wird im Modul behandelt
            // nachfolgender Code wird ausgeführt !!!
            // KANPAICLASSIC_modulPaypalPlus::pppExec
            break;

         // Amazon
         case 111:
            $amazon = Control::getAmazon();
            $amazon->bezahlen($preis, $_SESSION['bestellnummer'], $this->user->user['email']);
            unset($_SESSION['gesamt_show']);
            // Redirect durch Amazon-Klasse
            // nachfolgender Code wird ausgeführt !!!
            break;

         // Twint
         case 12:
            break;

         // EasyCredit
         case 13:
            // Daten für Ratenkauf in Bestellung eintragen
            $this->easycreditNotify($re_id);

            unset($_SESSION['vorgangskennung']);
            unset($_SESSION['easycredit_finanzierung']);
            unset($_SESSION['easycredit_vorvertrag']);
            unset($_SESSION['easycredit_check']);
            unset($_SESSION['easycredit_deny']);
            $_SESSION['zahlungsart'] = 0;
            break;

         // Klarna
         case 14:
            $klarna    = Control::getModuleKlarna();
            $reference = $klarna->confirm($_SESSION['klarna_order_id']);
            $re_dat    = date('Y-m-d');
            $sql       = '';

            // ZA-Automatik bei Klarna
            if (Helper::getData('za_automatik', 'n') == 'y') {
               $re_nr = $this->db->getRechnungsnummer();

               $sql = "UPDATE #__rechnung SET
                        `zahlungsinfo1`   = 'Klarna (nicht aktiviert)',
                        `zahlungsinfo2`   = '".$_SESSION['klarna_order_id'].'::'.$reference."',
                        `rechnungsnummer` = '$re_nr',
                        `rechnungsdatum`  = '$re_dat',
                        `zahlungdatum`    = '$re_dat',
                        `zahlung`         = '$preis',
                        `status`          = 3,
                        `pdf`             = 'r'
                       WHERE `id` = '$re_id'";

               $this->db->query($sql);
               $klarna->acknowledge($_SESSION['klarna_order_id'], $re_id);

               $mail = Control::getMail();
               $mail->sendBestellung($this->user->user['email'], $re_id, true);

               $test = $klarna->capture($_SESSION['klarna_order_id']);

               if ($test) {
                  $this->db->query("UPDATE #__rechnung SET zahlungsinfo1 = 'Klarna aktiviert' WHERE id = $re_id");
               }

               $this->im_export->exportBuchungenAuto($re_id);
            }

            else {
               $sql = "UPDATE #__rechnung SET
                        `zahlungsinfo1`       = 'Klarna (nicht aktiviert)',
                        `zahlungsinfo2`       = '".$_SESSION['klarna_order_id'].'::'.$reference."',
                        `zahlungdatum`        = '$re_dat',
                        `zahlung`             = '$preis'
                       WHERE `id` = '$re_id'";

               $this->db->query($sql);
               $klarna->acknowledge($_SESSION['klarna_order_id']);
            }

            break;

         // Paydirekt
         case 15:
            $paydirekt = Control::getModulePaydirekt();

            if ($paydirekt->getToken()) {
               $redirect = $paydirekt->checkout($re_id);

               if ($redirect != '') {
                  // kein Exit !!!
                  header('Location: '.$redirect);
               }
            }

            break;

         // WIR
         case 16:
            break;

         // Postfinance
         case 17:
            $re_dat = date('Y-m-d');
            $sql       = '';

            // ZA-Automatik bei Klarna
            if (Helper::getData('za_automatik', 'n') == 'y') {
               $re_nr = $this->db->getRechnungsnummer();

               $sql = "UPDATE #__rechnung SET
                        `rechnungsnummer` = '$re_nr',
                        `rechnungsdatum`  = '$re_dat',
                        `zahlungdatum`    = '$re_dat',
                        `zahlung`         = '$preis',
                        `status`          = 3,
                        `pdf`             = 'r'
                       WHERE `id` = '$re_id'";

               $this->db->query($sql);

               $mail = Control::getMail();
               $mail->sendBestellung($this->user->user['email'], $re_id, true);
               $this->im_export->exportBuchungenAuto($re_id);
            }

            else {
               $sql = "UPDATE #__rechnung SET
                        `zahlungdatum`        = '$re_dat',
                        `zahlung`             = '$preis'
                       WHERE `id` = '$re_id'";

               $this->db->query($sql);
            }

            break;

         default:
            break;
      }
   }

   // Antwort von Paypal, wenn Kunde bezahlt hat
   public function paypalNotify() {
      if (defined('CONF_PAYPAL_DEBUG')) {
         $fp = fopen(SHOP_PATH.'/classes/modules/xdebug/log/paypal_notify.txt', 'a');
         fwrite($fp, date('d.m.Y H:i:s')."\n".print_r($_REQUEST, true)."\n");
         fclose($fp);
      }

      $best_nr    = $_REQUEST['item_name'];
      $payer_id   = $_REQUEST['payer_id'];
      $payer_mail = $_REQUEST['payer_email'];
      $track_id   = $_REQUEST['ipn_track_id'];
      $betrag     = $_REQUEST['mc_gross'];
      $user_id    = $_REQUEST['custom'];
      $re_dat     = date('Y-m-d');
      $sql        = '';

      $re_data  = $this->db->querySingleObject("SELECT id, email, zahlungsinfo1 FROM #__rechnung WHERE bestellnummer = '$best_nr'");

      if ($re_data->zahlungsinfo1 != '') {
         exit;
      }

      $re_id    = $re_data->id;
      $re_email = $re_data->email;

      // ZA-Automatik bei Paypal
      if (Helper::getData('za_automatik', 'n') == 'y') {
         $re_nr = $this->db->getRechnungsnummer();

         $sql = "UPDATE #__rechnung SET
                  `zahlungsinfo1`   = '$track_id',
                  `zahlungsinfo2`   = '$payer_mail',
                  `rechnungsnummer` = '$re_nr',
                  `rechnungsdatum`  = '$re_dat',
                  `zahlungdatum`    = '$re_dat',
                  `zahlung`         = '$betrag',
                  `status`          = 3,
                  `pdf`             = 'r'
                 WHERE `bestellnummer` = '$best_nr'";

         $this->db->query($sql);

         $mail = Control::getMail();
         $mail->sendBestellung($re_email, $re_id, true);
         $this->im_export->exportBuchungenAuto($re_id);
      }

      else {
         $sql = "UPDATE #__rechnung SET
                  `zahlungsinfo1`       = '$track_id',
                  `zahlungsinfo2`       = '$payer_mail',
                  `zahlungdatum`        = '$re_dat',
                  `zahlung`             = '$betrag'
                 WHERE `bestellnummer` = '$best_nr'";

         $this->db->query($sql);
      }

      if (defined('CONF_PAYPAL_DEBUG')) {
         $fp = fopen(SHOP_PATH.'/classes/modules/xdebug/log/paypal_notify.txt'.(Helper::getData('za_automatik', 'n') == 'y' ? ' ZA-Automatik' : ''), 'a');
         fwrite($fp, date('d.m.Y H:i:s')."\nNotify End: OK");
         fclose($fp);
      }

      if ((int)$user_id != 0) {
         $this->user->setPaypal($user_id, $payer_mail, $payer_id);
      }

      echo '<!DOCTYPE html><html><head><title><title><head><body></body></html>';

       // Mail mit DL->Link senden
      if (Helper::getData('paypal_xtn', 'y') == 'y') {
         // Downloadlink in DB eintragen und per Mail versenden - (aus params.class)
         $mail  = Control::getMail();
         $dl    = Control::getDownload();
         $links = [];
         $links = $dl->getLinks($re_id);

         if ($links && count($links) > 0) {
            for ($i = 0; $i < count($links); $i++) {
               $mail->sendDownloadLink($re_email, $re_id, $links[$i]);
            }
         }
      }

      exit;
   }

   // Antwort von Paypalv2, wenn Kunde bezahlt hat
   public function paypalv2Notify() {
      header('Content-Type: application/json');
      $data = json_decode($_REQUEST['data']);

      if (defined('CONF_PAYPALV2_DEBUG')) {
         $fp = fopen(SHOP_PATH.'/classes/modules/xdebug/log/paypalv2_notify.txt', 'a');
         fwrite($fp, date('d.m.Y H:i:s')."\n".print_r($_REQUEST, true)."\n");
         fclose($fp);
      }
      
      if (isset($_REQUEST['ppv2_button'])){
         
         //customer details
         $given_name = $data->payer->name->given_name;
         $surname    = $data->payer->name->surname;
         $payer_id   = $data->payer->payer_id;
         $payer_mail = $data->payer->email_address;

         $payer_address  = $data->payer->address ?? '';
         $payer_address_line1 = $payer_address->address_line_1 ?? '';
         $payer_address_street = '';
         $payer_address_street_number = '';

         if (strpos($payer_address_line1,' ') !== false) {
            $adress = explode(' ',$payer_address_line1);
            $payer_address_street = $adress[0];
            $payer_address_street_number = $adress[1];
         }else{
            $payer_address_street = $payer_address_line1;
         }

         $payer_admin_area_2  = $payer_address->admin_area_2 ?? '';
         $payer_admin_area_1  = $payer_address->admin_area_1 ?? '';
         $payer_postal_code   = $payer_address->postal_code ?? '';
         $payer_country_code  = $payer_address->country_code ?? '';
      
         //shipping details
         $shipping_full_name     = $data->purchase_units[0]->shipping->name->full_name ?? '';
         $shipping_vorname       = '';
         $shipping_nachname      = '';

         if (strpos($shipping_full_name,' ') !== false) {
            $names = explode(' ',$shipping_full_name);
            $shipping_vorname = $names[0];
            $shipping_nachname = $names[1];
         }else{
            $shipping_vorname = $shipping_full_name;
         }
         
         $shipping_address  = $data->purchase_units[0]->shipping->address ?? '';
         $shipping_address_line1 = $shipping_address->address_line_1 ?? '';
         $shipping_address_street = '';
         $shipping_address_street_number = '';

         if (strpos($shipping_address_line1,' ') !== false) {
            $adress = explode(' ',$shipping_address_line1);
            $shipping_address_street = $adress[0];
            $shipping_address_street_number = $adress[1];
         }else{
            $shipping_address_street = $shipping_address_line1;
         }

         $shipping_admin_area_2  = $shipping_address->admin_area_2 ?? '';
         $shipping_admin_area_1  = $shipping_address->admin_area_1 ?? '';
         $shipping_postal_code   = $shipping_address->postal_code ?? '';
         $shipping_country_code  = $shipping_address->country_code ?? '';

         $order_id   = $data->id;
         $best_nr    = $data->purchase_units[0]->invoice_id;
         $payment_id = $data->purchase_units[0]->payments->captures[0]->id;
         $betrag     = $data->purchase_units[0]->amount->value;
         $re_dat     = date('Y-m-d');
         
         //update rechnung customer data information from Paypal
         $sql = "UPDATE #__rechnung SET
         `vorname`         = '$given_name',
         `nachname`        = '$surname',
         `adresse`         = '$payer_address_street',
         `lf_hausnr`       = '$payer_address_street_number',
         `plz`             = '$payer_postal_code',
         `ort`             = '$payer_admin_area_2',
         `email`           = '$payer_mail',
         `lf_vorname`      = '$shipping_vorname',
         `lf_nachname`     = '$shipping_nachname',
         `lf_adresse`      = '$shipping_address_street',
         `lf_hausnr`       = '$shipping_address_street_number',
         `lf_plz`          = '$shipping_postal_code',
         `lf_ort`          = '$shipping_admin_area_2'
            WHERE `bestellnummer` = '$best_nr'";

         $this->db->query($sql);
         $sql        = '';

         $re_data  = $this->db->querySingleObject("SELECT id, zahlungsinfo1 FROM #__rechnung WHERE bestellnummer = '$best_nr'");
         if ($re_data->zahlungsinfo1 != '') {
            exit;
         }
         $re_id    = $re_data->id;
         $re_email = $payer_mail;

      }else{
         $best_nr    = $data->purchase_units[0]->invoice_id;
         $payer_id   = $data->payer->payer_id;
         $payer_mail = $data->payer->email_address;
         $order_id   = $data->id;
         $payment_id = $data->purchase_units[0]->payments->captures[0]->id;
         $betrag     = $data->purchase_units[0]->amount->value;
         $user_id    = $data->purchase_units[0]->custom_id;
         $re_dat     = date('Y-m-d');
         $sql        = '';

         $re_data  = $this->db->querySingleObject("SELECT id, email, zahlungsinfo1 FROM #__rechnung WHERE bestellnummer = '$best_nr'");

         if ($re_data->zahlungsinfo1 != '') {
            exit;
         }
         $re_id    = $re_data->id;
         $re_email = $re_data->email;
      }

      // ZA-Automatik bei Paypal
      if (Helper::getData('za_automatik', 'n') == 'y') {
         $re_nr = $this->db->getRechnungsnummer();

         $sql = "UPDATE #__rechnung SET
                  `zahlungsinfo1`   = '$order_id',
                  `zahlungsinfo2`   = '$payer_mail',
                  `rechnungsnummer` = '$re_nr',
                  `rechnungsdatum`  = '$re_dat',
                  `zahlungdatum`    = '$re_dat',
                  `zahlung`         = '$betrag',
                  `status`          = 3,
                  `pdf`             = 'r'
               WHERE `bestellnummer` = '$best_nr'";

         $this->db->query($sql);

         $mail = Control::getMail();
         $mail->sendBestellung($re_email, $re_id, true);
         $this->im_export->exportBuchungenAuto($re_id);
      }

      else {
         $sql = "UPDATE #__rechnung SET
                  `zahlungsinfo1`       = '$order_id',
                  `zahlungsinfo2`       = '$payer_mail',
                  `zahlungdatum`        = '$re_dat',
                  `zahlung`             = '$betrag'
               WHERE `bestellnummer` = '$best_nr'";

         $this->db->query($sql);
      }

      if (defined('CONF_PAYPALV2_DEBUG')) {
         $fp = fopen(SHOP_PATH.'/classes/modules/xdebug/log/paypalv2_notify.txt'.(Helper::getData('za_automatik', 'n') == 'y' ? ' ZA-Automatik' : ''), 'a');
         fwrite($fp, date('d.m.Y H:i:s')."\nNotify End: OK");
         fclose($fp);
      }
      echo json_encode(array("status" => "ok"));
      
      exit;
   }

   // paypalPlusNotify: KANPAICLASSIC_modulPaypalPlus:pppExec

   // Nachschauen, ob Notify von Paypal erfolgreich war
   //
   public function checkPaypal($id, $loop = 0) {
      $data = $this->db->querySingleObject("SELECT zahlungsinfo2 FROM #__rechnung WHERE bestellnummer = '".$this->db->escape($id)."'");

      if (defined('CONF_PAYPAL_DEBUG')) {
         $fp = fopen(SHOP_PATH.'/classes/modules/xdebug/log/paypal_check.txt', 'a');
         fwrite($fp, date('d.m.Y H:i:s')."\nid : $id, data : ".print_r($data, true)."\n");
         fclose($fp);
      }

      // Test auf Antwort Paypal
      if (!$data) {
         return false;
      }

      if ($data->zahlungsinfo2 == '') {
         if (defined('CONF_PAYPAL_DEBUG')) {
            $fp = fopen(SHOP_PATH.'/classes/modules/xdebug/log/paypal_check.txt', 'a');
            fwrite($fp, date('d.m.Y H:i:s')."\nKeine Aktualisierung in DB\n");
            fclose($fp);
         }

         $loop++;
         if ($loop > 5) {
            if (defined('CONF_PAYPAL_DEBUG')) {
               $fp = fopen(SHOP_PATH.'/classes/modules/xdebug/log/paypal_check.txt', 'a');
               fwrite($fp, date('d.m.Y H:i:s')."\nWarten abgebrochen\n");
               fclose($fp);
            }
/* Funktioniert nicht richtig
            $mail = Control::getPhpMailer();
            $mail->ClearAddresses();
            $mail->ClearAttachments();
            $mail->CharSet = 'UTF-8';
            $mail->AddAddress($this->params->firma['email']);
            $mail->SetFrom($this->params->firma['email'], $this->params->firma['mailfrom']);
            $mail->Subject = 'Probleme bei Bezahlung mit PayPal';
            $mail->MsgHTML('<p>Bestellnummer: '.$id.'<br />Zahlungsbestätigung von PayPal verzögert oder nicht erhalten. Wenn Transaction-ID vorhanden ist alles OK.</p>');
            $mail->Send();
*/
            return true;
         }

         else {
            sleep(2);

            if (defined('CONF_PAYPAL_DEBUG')) {
               $fp = fopen(SHOP_PATH.'/classes/modules/xdebug/log/paypal_check.txt', 'a');
               fwrite($fp, date('d.m.Y H:i:s')."\nLoop: ".$loop."\n");
               fclose($fp);
            }
            return $this->checkPaypal($id, $loop);
         }
      }

      else {
         if (defined('CONF_PAYPAL_DEBUG')) {
            $fp = fopen(SHOP_PATH.'/classes/modules/xdebug/log/paypal_check.txt', 'a');
            fwrite($fp, date('d.m.Y H:i:s')."\nOK\n");
            fclose($fp);
         }

         return true;
      }
   }

   // Nachschauen, ob Notify von Paypal erfolgreich war
   public function checkPostfinance($id, $loop = 0) {
      $data = $this->db->querySingleObject("SELECT zahlungsinfo2 FROM #__rechnung WHERE bestellnummer = '".$this->db->escape($id)."'");

      //if (defined('CONF_PAYPAL_DEBUG')) {
      //   $fp = fopen(SHOP_PATH.'/classes/modules/xdebug/log/paypal_check.txt', 'a');
      //   fwrite($fp, date('d.m.Y H:i:s')."\nid : $id, data : ".print_r($data, true)."\n");
      //   fclose($fp);
      //}

      // Test auf Antwort Paypal
      if (!$data) {
         return false;
      }

      if ($data->zahlungsinfo2 == '') {
         if (defined('CONF_PAYPAL_DEBUG')) {
            $fp = fopen(SHOP_PATH.'/classes/modules/xdebug/log/paypal_check.txt', 'a');
            fwrite($fp, date('d.m.Y H:i:s')."\nKeine Aktualisierung in DB\n");
            fclose($fp);
         }

         $loop++;
         if ($loop > 5) {
            if (defined('CONF_PAYPAL_DEBUG')) {
               $fp = fopen(SHOP_PATH.'/classes/modules/xdebug/log/paypal_check.txt', 'a');
               fwrite($fp, date('d.m.Y H:i:s')."\nWarten abgebrochen\n");
               fclose($fp);
            }

            $mail = Control::getPhpMailer();
            $mail->ClearAddresses();
            $mail->ClearAttachments();
            $mail->CharSet = 'UTF-8';
            $mail->AddAddress($this->params->firma['email']);
            $mail->SetFrom($this->params->firma['email'], $this->params->firma['mailfrom']);
            $mail->Subject = 'Probleme bei Bezahlung mit PayPal';
            $mail->MsgHTML('<p>Bestellnummer: '.$id.'<br />Zahlungsbestätigung von PayPal verzögert oder nicht erhalten. Wenn Transaction-ID vorhanden ist alles OK.</p>');
            $mail->Send();

            return true;
         }

         else {
            sleep(2);

            if (defined('CONF_PAYPAL_DEBUG')) {
               $fp = fopen(SHOP_PATH.'/classes/modules/xdebug/log/paypal_check.txt', 'a');
               fwrite($fp, date('d.m.Y H:i:s')."\nLoop: ".$loop."\n");
               fclose($fp);
            }
            return $this->checkPaypal($id, $loop);
         }
      }

      else {
         if (defined('CONF_PAYPAL_DEBUG')) {
            $fp = fopen(SHOP_PATH.'/classes/modules/xdebug/log/paypal_check.txt', 'a');
            fwrite($fp, date('d.m.Y H:i:s')."\nOK\n");
            fclose($fp);
         }
         return true;
      }
   }

   // Wird von Params aufgerufen, nicht Easycredit
   public function easycreditNotify($re_id) {
      // Ratenkauf bestätigen
      $easycredit = Control::getModuleEasycredit();
      $data       = $easycredit->bestaetigen();

      $tvk        = $data->wsMessages->messages[0]->params[0];
      $fvk        = $data->wsMessages->messages[0]->params[1];
      $zinsen     = $_SESSION['easycredit_finanzierung']->ratenplan->zinsen->anfallendeZinsen;
      $betrag     = (float)$_SESSION['easycredit_finanzierung']->finanzierung->bestellwert;
      $re_dat     = date('Y-m-d');
      $sql        = '';

      // ZA-Automatik bei EasyCredit
      if (Helper::getData('za_automatik', 'n') == 'y') {
         $re_nr  = $this->db->getRechnungsnummer();

         $sql = "UPDATE #__rechnung SET
                    `zahlungsinfo1`   = '".$tvk." / ".$fvk."',
                    `zahlungsinfo2`   = '$zinsen',
                    `rechnungsnummer` = '$re_nr',
                    `rechnungsdatum`  = '$re_dat',
                    `zahlungdatum`    = '$re_dat',
                    `zahlung`         = '$betrag',
                    `status`          = 3,
                    `pdf`             = 'r'
                 WHERE `id` = '$re_id'";

         $this->db->query($sql);

         $re_data  = $this->db->querySingleObject("SELECT id, email FROM #__rechnung WHERE id = '$re_id'");
         $re_email = $re_data->email;

         $mail = Control::getMail();
         $mail->sendBestellung($re_email, $re_id, true);
         $this->im_export->exportBuchungenAuto($re_id);
      }

      else {
         $sql = "UPDATE #__rechnung SET
                    `zahlungsinfo1`   = '".$tvk." / ".$fvk."',
                    `zahlungsinfo2`   = '$zinsen',
                    `zahlungdatum`    = '$re_dat',
                    `zahlung`         = '$betrag'
                 WHERE `id` = '$re_id'";

         $this->db->query($sql);
      }

      return;
   }

   // Antwort von VRpay, wenn Kunde bezahlt hat
   public function vrpayNotify() {
      if (defined('CONF_VRPAY_DEBUG')) {
         $fp = fopen(SHOP_PATH.'/classes/modules/xdebug/log/vrpay_notify.txt', 'a');
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
               $re_dat         = date('Y-m-d');
               $sql            = '';

               // ZA-Automatik bei VR-Pay
               if (Helper::getData('za_automatik', 'n') == 'y') {
                  $re_nr = $this->db->getRechnungsnummer();

                  $sql = "UPDATE #__rechnung SET
                             `zahlungsinfo1`   = '$trx_brand',
                             `zahlungsinfo2`   = '$trx_cardno / $trx_expiredate',
                             `rechnungsnummer` = '$re_nr',
                             `rechnungsdatum`  = '$re_dat',
                             `zahlungdatum`    = '$re_dat',
                             `zahlung`         = '$betrag',
                             `status`          = 3,
                             `pdf`             = 'r'
                          WHERE `bestellnummer` = '$best_nr'";

                  $this->db->query($sql);

                  $re_data  = $this->db->querySingleObject("SELECT id, email FROM #__rechnung WHERE bestellnummer = '$best_nr'");
                  $re_id    = $re_data->id;
                  $re_email = $re_data->email;

                  $mail = Control::getMail();
                  $mail->sendBestellung($re_email, $re_id, true);
                  $this->im_export->exportBuchungenAuto($re_id);
               }

               else {
                  $sql = "UPDATE #__rechnung SET
                             `zahlungsinfo1`   = '$trx_brand',
                             `zahlungsinfo2`       = '$trx_cardno / $trx_expiredate',
                             `zahlungdatum`        = '$re_dat',
                             `zahlung`             = '$betrag'
                          WHERE `bestellnummer` = '$best_nr'";

                  $this->db->query($sql);
               }

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
            $fp = fopen(SHOP_PATH.'/classes/modules/xdebug/log/vrpay_check.txt', 'a');
            fwrite($fp, date('d.m.Y H:i:s')."\nKein Eintrag in DB\n");
            fclose($fp);
         }
         return false;
      }

      $data = $this->db->getObject();
      if ($data->zahlungsinfo2 == '') {
         if (defined('CONF_VRPAY_DEBUG')) {
            $fp = fopen(SHOP_PATH.'/classes/modules/xdebug/log/vrpay_check.txt', 'a');
            fwrite($fp, date('d.m.Y H:i:s')."\nKeine Bestellnummer in DB\n");
            fclose($fp);
         }
         return false;
      }

      if (defined('CONF_VRPAY_DEBUG')) {
         $fp = fopen(SHOP_PATH.'/classes/modules/xdebug/log/vrpay_check.txt', 'a');
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

      if (defined('CONF_SOFORT_DEBUG')) {
         $fp = fopen("sofort_notify_1.txt","w");
         fwrite($fp, date('d.m.Y H:i:s')."\n".print_r($notification, true)."\n");
         fclose($fp);
      }

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
      $re_dat   = date('Y-m-d');
      $sql      = '';

      if (defined('CONF_SOFORT_DEBUG')) {
         $fp = fopen("sofort_notify_2.txt","w");
         fwrite($fp, date('d.m.Y H:i:s')."\n".print_r($data, true)."\n");
         fclose($fp);
      }

      // ZA-Automatik bei Sofortüberweisung
      if (Helper::getData('za_automatik', 'n') == 'y') {
         $re_nr = $this->db->getRechnungsnummer();

         $sql = "UPDATE #__rechnung SET `zahlungsinfo1`   = '$trans_id',
                                        `zahlungsinfo2`   = '$status',
                                        `rechnungsnummer` = '$re_nr',
                                        `rechnungsdatum`  = '$re_dat',
                                        `zahlungdatum`    = '$re_dat',
                                        `zahlung`         = '$betrag',
                                        `status`          = 3,
                                        `pdf`             = 'r'
                 WHERE `bestellnummer` = '$best_nr'";

         $this->db->query($sql);

         $re_data  = $this->db->querySingleObject("SELECT id, email FROM #__rechnung WHERE bestellnummer = '$best_nr'");
         $re_id    = $re_data->id;
         $re_email = $re_data->email;

         $mail = Control::getMail();
         $mail->sendBestellung($re_email, $re_id, true);
         $this->im_export->exportBuchungenAuto($re_id);
      }

      else {
         $sql = "UPDATE #__rechnung SET `zahlungsinfo1`   = '$trans_id',
                                        `zahlungsinfo2`   = '$status',
                                        `zahlungdatum`    = '$re_dat',
                                        `zahlung`         = '$betrag'
                 WHERE `bestellnummer` = '$best_nr'";

         $this->db->query($sql);
      }

      exit;
   }

   // Nachschauen, ob Notify von Sofortüberweisung erfolgreich war
   public function checkSofort($id) {
      // Warten auf Notification
      for ($i = 0; $i < 7; $i++) {
         $sql = "SELECT zahlungsinfo1 FROM #__rechnung WHERE bestellnummer = '$id'";

         if ($this->db->query($sql) != 1) {
            if (defined('CONF_SOFORT_DEBUG')) {
               $fp = fopen(SHOP_PATH.'/classes/modules/xdebug/log/sofort_check.txt', 'a');
               fwrite($fp, date('d.m.Y H:i:s')."\nKein Eintrag in DB - Rechnung-ID: ".$id."\n");
               fclose($fp);
            }

            $mail = Control::getPhpMailer();
            $mail->ClearAddresses();
            $mail->ClearAttachments();
            $mail->CharSet = 'UTF-8';
            $mail->AddAddress($this->params->firma['email']);
            $mail->SetFrom($this->params->firma['email'], $this->params->firma['mailfrom']);
            $mail->Subject = 'Probleme bei Bezahlung mit SOFORT-Überweisung';
            $mail->MsgHTML('<p>Bestellnummer: '.$id.'<br />Kein Eintrag in der Datenbank.</p>');
            $mail->Send();

            return false;
         }

         $data = $this->db->getObject();

         if ($data->zahlungsinfo1 == '') {
            sleep(1);
         }

         else {
            if (defined('CONF_SOFORT_DEBUG')) {
               $fp = fopen(SHOP_PATH.'/classes/modules/xdebug/log/sofort_check.txt', 'a');
               fwrite($fp, date('d.m.Y H:i:s')."\nOK\n");
               fclose($fp);
            }
            return true;
         }
      }

      if (defined('CONF_SOFORT_DEBUG')) {
         $fp = fopen(SHOP_PATH.'/classes/modules/xdebug/log/sofort_check.txt', 'a');
         fwrite($fp, date('d.m.Y H:i:s')."\nKeine Bestellnummer zurück erhalten\n");
         fclose($fp);
      }

      $mail = Control::getPhpMailer();
      $mail->ClearAddresses();
      $mail->ClearAttachments();
      $mail->CharSet = 'UTF-8';
      $mail->AddAddress($this->params->firma['email']);
      $mail->SetFrom($this->params->firma['email'], $this->params->firma['mailfrom']);
      $mail->Subject = 'Probleme bei Bezahlung mit SOFORT-Überweisung';
      $mail->MsgHTML('<p>Bestellnummer: '.$id.'<br />Zahlungsbestätigung von SOFORT verzögert oder nicht erhalten. Wenn Transaction-ID vorhanden ist alles OK.</p>');
      $mail->Send();

      return true;
   }

   public function amazonNotify() {
      $put  = file_get_contents('php://input');
      $json = json_decode($put);
      $msg  = $json->Message;
      $jmsg = json_decode($msg);

      $xml = new SimpleXMLElement($jmsg->NotificationData);

      // Bezahlung genehmigt
      if (isset($xml->AuthorizationDetails)) {
         $auth_id      = $xml->AuthorizationDetails->AmazonAuthorizationId;
         $reference_id = $xml->AuthorizationDetails->AuthorizationReferenceId;

         $sql = "UPDATE #__rechnung SET
                    `zahlungsinfo1`     = 'Auth: ".$auth_id."',
                    `zahlungsinfo2`     = 'in Prüfung'
                 WHERE `bestellnummer`  = '$reference_id'";
         $this->db->query($sql);
      }

      if (isset($xml->CaptureDetails)) {
         $capture_id   = $xml->CaptureDetails->AmazonCaptureId;
         $reference_id = $xml->CaptureDetails->CaptureReferenceId;
         $betrag       = $xml->CaptureDetails->CaptureAmount->Amount;
         $status       = $xml->CaptureDetails->CaptureStatus->State;
         $re_dat       = date('Y-m-d');
         $sql          = '';

         if (defined('CONF_AMAZON_DEBUG')) {
            $fp = fopen(SHOP_PATH.'/classes/modules/xdebug/log/amazon_notify.txt', 'a');
            fwrite($fp, date('d.m.Y H:i:s')." Done.\n");
            fclose($fp);
         }

         // ZA-Automatik bei Amazon-Payments
         if (Helper::getData('za_automatik', 'n') == 'y') {
            $re_nr = $this->db->getRechnungsnummer();

            $sql = "UPDATE #__rechnung SET
                     `zahlungsinfo2`       = 'Capt: ".$capture_id."',
                     `rechnungsnummer`     = '$re_nr',
                     `rechnungsdatum`      = '$re_dat',
                     `zahlungdatum`        = '$re_dat',
                     `zahlung`             = '$betrag',
                     `status`              = 3,
                     `pdf`                 = 'r'
                 WHERE `bestellnummer` = '$reference_id'";

            $this->db->query($sql);

            $re_data  = $this->db->querySingleObject("SELECT id, email FROM #__rechnung WHERE bestellnummer = '$reference_id'");
            $re_id    = $re_data->id;
            $re_email = $re_data->email;

            $mail = Control::getMail();
            $mail->sendBestellung($re_email, $re_id, true);
            $this->im_export->exportBuchungenAuto($re_id);
         }

         else {
            $sql = "UPDATE #__rechnung SET
                     `zahlungsinfo2`       = 'Capt: ".$capture_id."',
                     `zahlungdatum`        = '$re_dat',
                     `zahlung`             = '$betrag'
                    WHERE `bestellnummer`  = '$reference_id'";

            $this->db->query($sql);
         }

         if (defined('CONF_AMAZON_DEBUG')) {
            $fp = fopen(SHOP_PATH.'/classes/modules/xdebug/log/amazon_notify.txt', 'a');
            fwrite($fp, date('d.m.Y H:i:s')."\n".$capture_id.' : '.$reference_id.' : '.$status."\n");
            fclose($fp);
         }

         echo '<!DOCTYPE html><html><head><title><title><head><body></body></html>';
      }

      exit;
   }

   // Nachschauen, ob Notify von Amazon erfolgreich war
   public function XXXcheckAmazon($id) {
      $sql = "SELECT zahlungsinfo2 FROM #__rechnung WHERE bestellnummer = '$id'";
      if ($this->db->query($sql) != 1) {
         if (defined('CONF_AMAZON_DEBUG')) {
            $fp = fopen(SHOP_PATH.'/classes/modules/xdebug/log/amazon_check.txt', 'a');
            fwrite($fp, date('d.m.Y H:i:s')."\nKein Eintrag in DB\n");
            fclose($fp);
         }
         return false;
      }

      $data = $this->db->getObject();
      if ($data->zahlungsinfo2 == '') {
         if (defined('CONF_AMAZON_DEBUG')) {
            $fp = fopen(SHOP_PATH.'/classes/modules/xdebug/log/amazon_check.txt', 'a');
            fwrite($fp, date('d.m.Y H:i:s')."\nKein Transaktionscode\n");
            fclose($fp);
         }
         return false;
      }

      if (defined('CONF_AMAZON_DEBUG')) {
         $fp = fopen(SHOP_PATH.'/classes/modules/xdebug/log/amazon_check.txt', 'a');
         fwrite($fp, date('d.m.Y H:i:s')."\nOK\n");
         fclose($fp);
      }
      return true;
   }

   public function twint($re_id) {
      $bezahlt = $_SESSION['twint_amont'];
   }

   public function checkPaydirekt() {
      $paydirekt = Control::getModulePaydirekt();
      // Betrag einziehen
      $json      = $paydirekt->capture();

      if (isset($json->_embedded->state->confirmed) && $json->_embedded->state->confirmed === true) {
         $re_id      = (int)$json->merchantOrderReferenceNumber;
         $re_dat     = date('Y-m-d');
         $snippet_id = $json->diSnippetId;
         $betrag     = $json->totalAmount;

         // ZA-Automatik bei VR-Pay
         if (Helper::getData('za_automatik', 'n') == 'y') {
            $re_nr = $this->db->getRechnungsnummer();

            $this->db->query("UPDATE #__rechnung SET
                       `zahlungsinfo1`   = '$snippet_id',
                       `rechnungsnummer` = '$re_nr',
                       `rechnungsdatum`  = '$re_dat',
                       `zahlungdatum`    = '$re_dat',
                       `zahlung`         = '$betrag',
                       `status`          = 3,
                       `pdf`             = 'r'
                    WHERE `id`           = '$re_id'");

            $re_data  = $this->db->querySingleObject("SELECT id, email FROM #__rechnung WHERE id = '$re_id'");
            $re_id    = $re_data->id;
            $re_email = $re_data->email;

            $mail = Control::getMail();
            $mail->sendBestellung($re_email, $re_id, true);
            $this->im_export->exportBuchungenAuto($re_id);
         }

         else {
            $this->db->query("UPDATE #__rechnung SET
                       `zahlungsinfo1`   = '$snippet_id',
                       `zahlungdatum`    = '$re_dat',
                       `zahlung`         = '$betrag'
                    WHERE `id`           = '$re_id'");
         }

         return true;
      }

      return false;
   }
}
