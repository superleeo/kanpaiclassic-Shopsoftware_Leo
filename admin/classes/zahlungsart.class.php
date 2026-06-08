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

class KANPAICLASSIC_zahlungsart
{
   private $db;
   private $params;
   private $text;
   private $message;
   private $shop;
   private $versandart_land = '';
   private $za_arr = [0, 6, 1, 5, 4, 12, 2, 18, 10, 7, 8, 3, 9, 13, 11, 14, 15, 17, 16, 19, 99];

   public function __construct() {
      $this->db = Control::getDB();
      $this->params = Control::getParams();
      $this->text = Control::getText();
   }

   public function getContent() {
      if ($this->params->func == 'update') {
         $this->writeZahlungsart();
         $_SESSION['zahlungsart_update'] = true;
         header('Location: '.ADMIN_URL_IDX.'/zahlungsart');
         exit;
      }

      else if($this->params->func == 'getZahlartPopup') {
         $this->getZahlartPopup();
      }

      else if($this->params->func == 'saveZahlartPopup') {
         $this->saveZahlartPopup();
      }

      else if($this->params->func == 'twintCert') {
         $html = '';
         include_once SHOP_PATH.'/classes/modules/twint/popup_cert.tpl.php';
         exit(json_encode(['status' => 'ok', 'html' => $html]));
      }

      else if($this->params->func == 'twintCertUpload') {
         require_once SHOP_PATH.'/classes/modules/twint/twint.module.php';
         $twint = new \KANPAICLASSIC\KANPAICLASSIC_modulTwint();
         $twint->certificate();
      }

      else {
         $this->getShop();
      }

      include ADMIN_PATH.'/templates/zahlungsart.tpl.php';
   }

   // Seite Zahlungsart anzeigen
   private function getShop() {
      $sql = "SELECT *
            FROM `#__firma`
            WHERE `id` = 1";
      $this->db->query($sql);
      $this->shop = $this->db->getObject();

      // Vorauswahl
      if ($this->shop->vrpay_url == '') {
         $this->shop->vrpay_url = SHOP_URL_IDX.'/vrpay_notify';
      }

      $this->shop->ppp_client_id       = Helper::getData('ppp_client_id');
      $this->shop->ppp_client_secret   = Helper::getData('ppp_client_secret');
      $this->shop->ppv2_client_id      = Helper::getData('ppv2_client_id');
      $this->shop->ppv2_client_secret  = Helper::getData('ppv2_client_secret');
      $this->shop->ppv2_check_button  = Helper::getData('ppv2_check_button');
      $this->shop->mollie_test_key     = Helper::getData('mollie_test_key');
      $this->shop->mollie_live_key     = Helper::getData('mollie_live_key');
      $this->shop->twint_uuid          = Helper::getData('twint_uuid');

//      $this->shop->paydirekt_check     = Helper::getData('paydirekt_check', 'n');
      $this->shop->paydirekt_preis     = Helper::getData('paydirekt_preis', 0);
      $this->shop->paydirekt_key       = Helper::getData('paydirekt_key', '');
      $this->shop->paydirekt_secret    = Helper::getData('paydirekt_secret', '');

      $this->shop->postfinance_check   = Helper::getData('postfinance_check', 'n');
      $this->shop->postfinance_preis   = Helper::getData('postfinance_preis', 0);
      $this->shop->postfinance_pspid   = Helper::getData('postfinance_pspid', '');
      $this->shop->postfinance_hash_in = Helper::getData('postfinance_hash_in', '');

      $this->versandart_land           = $this->db->querySingleValue("SELECT name FROM #__laender WHERE id = ".$this->shop->versandart_land);
   }

   // Zahlungsart speichern
   private function writeZahlungsart() {
      $sql = "UPDATE `#__firma` SET
                `za_waehlen_check`          = '" . $this->params->postCheckbox('za_waehlen_check') . "',
                `bar_check`                 = '" . $this->params->postCheckbox('bar_check') . "',
                `bar_preis`                 = '" . $this->params->postFloat('bar_preis') . "',
                `vorkasse_check`            = '" . $this->params->postCheckbox('vorkasse_check') . "',
                `vorkasse_preis`            = '" . $this->params->postFloat('vorkasse_preis') . "',
                `rechnung_check`            = '" . $this->params->postCheckbox('rechnung_check') . "',
                `rechnung_preis`            = '" . $this->params->postFloat('rechnung_preis') . "',
                `rechnung_check_user`       = '" . $this->params->postCheckbox('rechnung_check_user') . "',
                `rechnung_check_country`    = '" . $this->params->postCheckbox('rechnung_check_country') . "',
                `nachnahme_check`           = '" . $this->params->postCheckbox('nachnahme_check') . "',
                `nachnahme_check_user`      = '" . $this->params->postCheckbox('nachnahme_check_user') . "',
                `nachnahme_check_country`   = '" . $this->params->postCheckbox('nachnahme_check_country') . "',
                `nachnahme_preis`           = '" . $this->params->postFloat('nachnahme_preis') . "',
                `twint_check`               = '" . $this->params->postCheckbox('twint_check') . "',
                `twint_preis`               = '" . $this->params->postFloat('twint_preis') . "',
                `wir_check`                 = '" . $this->params->postCheckbox('wir_check') . "',
                `paypal_check`              = '" . $this->params->postCheckbox('paypal_check') . "',
                `paypal_mail`               = '" . $this->params->postString('paypal_mail') . "',
                `paypal_preis`              = '" . $this->params->postFloat('paypal_preis') . "',
                `pp_test`                   = '" . $this->params->postCheckbox('pp_test') . "',
                `pp_test_user`              = '" . $this->params->postString('pp_test_user') . "',
                `paypalv2_check`            = '" . $this->params->postCheckbox('paypalv2_check') . "',
                `paypalv2_preis`            = '" . $this->params->postFloat('paypalv2_preis') . "',
                `mollie_check`            = '" . $this->params->postCheckbox('mollie_check') . "',
                `mollie_preis`            = '" . $this->params->postFloat('mollie_preis') . "',
                `paypalplus_check`          = '" . $this->params->postCheckbox('paypalplus_check') . "',
                `paypalplus_preis`          = '" . $this->params->postFloat('paypalplus_preis') . "',
                `sofort_check`              = '" . $this->params->postCheckbox('sofort_check') . "',
                `sofort_preis`              = '" . $this->params->postFloat('sofort_preis') . "',
                `sofort_key`                = '" . $this->params->postString('sofort_key') . "',
                `vrpay_check`               = '" . $this->params->postCheckbox('vrpay_check') . "',
                `vrpay_url`                 = '" . $this->params->postString('vrpay_url') . "',
                `vrpay_number`              = '" . $this->params->postString('vrpay_number') . "',
                `vrpay_pass`                = '" . $this->params->postString('vrpay_pass') . "',
                `vrpay_preis`               = '" . $this->params->postFloat('vrpay_preis') . "',
                `lastschrift_check`         = '" . $this->params->postCheckbox('lastschrift_check') . "',
                `lastschrift_preis`         = '" . $this->params->postFloat('lastschrift_preis') . "',
                `lastschrift_check_user`    = '" . $this->params->postCheckbox('lastschrift_check_user') . "',
                `lastschrift_check_country` = '" . $this->params->postCheckbox('lastschrift_check_country') . "',
                `lastschrift_pdf_check`     = '" . $this->params->postCheckbox('lastschrift_pdf_check') . "',
                `kklastschrift_check`       = '" . $this->params->postCheckbox('kklastschrift_check') . "',
                `kklastschrift_preis`       = '" . $this->params->postFloat('kklastschrift_preis') . "',
                `easycredit_check`          = '" . $this->params->postCheckbox('easycredit_check') . "',
                `easycredit_preis`          = '" . $this->params->postFloat('easycredit_preis') . "',
                `easycredit_api_id`         = '" . $this->params->postString('easycredit_api_id') . "',
                `easycredit_token`          = '" . $this->params->postString('easycredit_token') . "',
                `amazon_check`              = '" . $this->params->postCheckbox('amazon_check') . "',
                `amazon_login_check`        = '" . $this->params->postCheckbox('amazon_login_check') . "',
                `amazon_preis`              = '" . $this->params->postFloat('amazon_preis') . "',
                `amazon_seller`             = '" . $this->params->postString('amazon_seller') . "',
                `amazon_client`             = '" . $this->params->postString('amazon_client') . "',
                `amazon_access`             = '" . $this->params->postString('amazon_access') . "',
                `amazon_secret`             = '" . $this->params->postString('amazon_secret') . "',
                `paydirekt_check`           = '" . $this->params->postCheckbox('paydirekt_check') . "',
                `klarna_check`              = '" . $this->params->postCheckbox('klarna_check') . "',
                `klarna_preis`              = '" . $this->params->postFloat('klarna_preis') . "',
                `klarna_user`               = '" . $this->params->postString('klarna_user') . "',
                `klarna_pass`               = '" . $this->params->postString('klarna_pass') . "'
             WHERE `id` = 1";
      $this->db->query($sql);

      Helper::setData('ppp_client_id',       $this->params->postString('ppp_client_id'));
      Helper::setData('ppp_client_secret',   $this->params->postString('ppp_client_secret'));
      Helper::setData('ppv2_client_id',      $this->params->postString('ppv2_client_id'));
      Helper::setData('ppv2_client_secret',  $this->params->postString('ppv2_client_secret'));
      Helper::setData('ppv2_check_button',  $this->params->postCheckbox('ppv2_check_button'));
      Helper::setData('mollie_test_key',     $this->params->postString('mollie_test_key'));
      Helper::setData('mollie_live_key',     $this->params->postString('mollie_live_key'));
      Helper::setData('twint_uuid',          $this->params->postString('twint_uuid'));

      Helper::setData('paydirekt_check',     $this->params->postCheckbox('paydirekt_check'));
      Helper::setData('paydirekt_preis',     $this->params->postFloat('paydirekt_preis'));
      Helper::setData('paydirekt_key',       $this->params->postString('paydirekt_key'));
      Helper::setData('paydirekt_secret',    $this->params->postString('paydirekt_secret'));

      Helper::setData('postfinance_check',   $this->params->postCheckbox('postfinance_check'));
      Helper::setData('postfinance_preis',   $this->params->postFloat('postfinance_preis'));
      Helper::setData('postfinance_pspid',   $this->params->postString('postfinance_pspid'));
      Helper::setData('postfinance_hash_in', $this->params->postString('postfinance_hash_in'));

      Helper::setData('za_automatik',        $this->params->postCheckbox('za_automatik'));
      Helper::setData('rechnung_server',     $this->params->postCheckbox('rechnung_server'));
   }

   // Popup Zahlungs-Texte anzeigen
   public function getZahlartPopup() {
      $html    = '';
      $za_text = [];
      $langs   = (explode(';', $this->params->firma['langs']));

      for ($i = 0; $i < count($this->za_arr); $i++) {
         $za_text['za'.$this->za_arr[$i].'_info'] = '';

         for ($l = 0; $l < count($langs); $l++) {
            $za_text['za'.$this->za_arr[$i].'_'.$langs[$l]] = '';
         }
      }

      if (file_exists(ADMIN_PATH.'/zahlart.json')) {
         $za_json = (array)json_decode(file_get_contents(ADMIN_PATH.'/zahlart.json'));
      }

      $za_text = (object)array_merge($za_text, $za_json);

//var_dump($za_text); exit;
      require_once ADMIN_PATH.'/templates/popup_zahlart.tpl.php';

      header('Content-Type: application/json');
      exit(json_encode(['status' => 'ok', 'html' => $html]));
   }

   // Popup Zahlungs-Texte speichern
   public function saveZahlartPopup() {
      $za_text = new \stdClass();

      if (file_exists(ADMIN_PATH.'/zahlart.json')) {
         $za_text = json_decode(file_get_contents(ADMIN_PATH.'/zahlart.json'));
      }

      foreach ($this->za_arr as $za) {
         // Nur für Lesbarkeit
         $za_text->{'za'.$za.'_info'} = $this->params->postString('za'.$za.'_info');

         foreach ($this->params->langs as $lang) {
            if ($za == 99) {
               $za_text->{'za'.$za.'_'.$lang} = $this->params->postString('za'.$za.'_1_'.$lang).'[TRENNER]'.$this->params->postString('za'.$za.'_2_'.$lang);
            }

            else {
               $za_text->{'za'.$za.'_'.$lang} = $this->params->postString('za'.$za.'_'.$lang);

               if ($za == 1) {
                  $za_text->{'za_re'.$za.'_'.$lang} = $this->params->postString('za_re'.$za.'_'.$lang);
               }
            }
         }
      }

      file_put_contents(ADMIN_PATH.'/zahlart.json', json_encode($za_text, JSON_PRETTY_PRINT));
      header('Content-Type: application/json');
      echo json_encode(['status' => 'ok']);
      exit;
   }
}
