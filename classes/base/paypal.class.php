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

// Doku: https://developer.paypal.com/docs/classic/paypal-payments-standard/integration-guide/Appx_websitestandard_htmlvariables/#technical-variables
// Doku: (https://developer.paypal.com/docs/api/payments/v1/#payment_create) REST-API (hier nicht verwendet)

namespace KANPAICLASSIC;

if (!defined('KANPAICLASSIC')) {
   die ("This file cannot run outside the Kanpai Classic Shopsoftware");
}

class KANPAICLASSIC_paypal {
   private $pp_redirect;
   private $pp_live_redirect = 'https://www.paypal.com/cgi-bin/webscr?';
   private $pp_test_redirect = 'https://www.sandbox.paypal.com/cgi-bin/webscr?';

   private $pp_test = 'n';
   private $pp_url;
   private $pp_user;

   private $db;
   private $params;

   function __construct() {
   }

   public function init($paypal_mail = '') {
      $this->db = Control::getDB();
      $this->params = Control::getParams();

      if (!defined('CONF_MODULE_PORTAL')) {
         if ($paypal_mail == '') {
            return false;
         }
      }

      else {
         $paypal_mail = $params->firma['paypal_mail'];
      }

      $sql = "SELECT pp_test, pp_test_user
            FROM #__firma
            WHERE id = 1";

      $this->db->query($sql);
      $data = $this->db->getObject();
      $this->pp_test = $data->pp_test;

      // Paypal-Daten setzen, abhängig ob Live oder Sandbox
      // LIVE
      if (!defined('CONF_PAYPAL_SANDBOX')) {
         $this->pp_user = $paypal_mail;
         $this->pp_plus_user = $paypal_mail;
         $this->pp_redirect = $this->pp_live_redirect;

      }

      // Sandbox
      else {
         $this->pp_user = $data->pp_test_user;
         $this->pp_plus_user = 'shop2@hcns.de';
         $this->pp_redirect = $this->pp_test_redirect;
      }

      return true;
   }

   public function bezahlen ($betrag, $best_nr, $user, $artikel_nummern) {
      $iso_lang  = strtoupper($this->db->querySingleValue("SELECT iso_lang FROM #__laender WHERE id = '".$user['staat']."'"));
      $iso_staat = strtoupper($this->db->querySingleValue("SELECT domain FROM #__laender WHERE id = '".$user['lf_staat']."'"));

      // Betrag in US-Format
      $betrag = number_format($betrag, 2, '.', '');

      $paypal  = 'cmd=_xclick';
      $paypal .= '&business=' . $this->pp_user;
      $paypal .= '&bn=' . urlencode($this->params->firma['firm_name']);
      $paypal .= '&item_name=' . utf8_decode($best_nr); // ID bei Notify!!!!!
      $paypal .= '&item_number=' . utf8_decode($artikel_nummern);
      $paypal .= '&amount=' . $betrag;
      $paypal .= '&currency_code='.Helper::waehrungText($this->params->firma['waehrung1'], 2);
      $paypal .= '&no_note=1';

      $paypal .= '&return='.urlencode(SHOP_URL_IDX.'/paypal_ok');
      $paypal .= '&notify_url='.urlencode(SHOP_URL_IDX.'/paypal_notify');
      $paypal .= '&cancel_return='.urlencode(SHOP_URL_IDX.'/paypal_fail');
      $paypal .= '&rm=1';  // POST für return

//      $paypal .= '&address_override=0';            // 1 =
//      $paypal .= '&no_shipping=1';                 // 1 = keine Adresseingabe
      $paypal .= '&email=' . $user['pp_mail'];
      $paypal .= '&first_name='.$user['vorname'];
      $paypal .= '&last_name='.$user['nachname'];
      $paypal .= '&address1='.$user['lf_adresse'].' '.$user['lf_hausnr'];
      $paypal .= '&address2=';
      $paypal .= '&city='.$user['lf_ort'];
      $paypal .= '&zip=' . $user['lf_plz'];
      $paypal .= '&country='.$iso_staat;
      $paypal .= '&lc='.$iso_lang;

      $paypal .= '&custom=' . $this->params->user_id;

      if (defined('CONF_PAYPAL_DEBUG')) {
         $fp = fopen(SHOP_PATH.'/classes/modules/xdebug/log/paypal_start.txt', 'a');
         fwrite($fp, date('d.m.Y H:i:s')."\n".$this->pp_redirect."\n".$paypal."\n");
         fclose($fp);
      }

      header('location: ' . $this->pp_redirect . $paypal);
      // KEIN EXIT!!!
   }
}
