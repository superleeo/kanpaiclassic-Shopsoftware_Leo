<?php
/*
###################################################################################
  Kanpai Classic Shopsoftware Entwicklungsstand: 05.08.2020 Version III 8.0

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
use \PayWithAmazon as PWA;

if (!defined('KANPAICLASSIC')) {
   die ("This file cannot run outside the Flow&reg; Shopsoftware");
}

require_once __DIR__.'/PayWithAmazon/Client.php';


class KANPAICLASSIC_moduleAmazon
{
   public  $widgets_url = '';
   private $widgets_url_sandbox = 'https://static-eu.payments-amazon.com/OffAmazonPayments/de/sandbox/js/Widgets.js?sellerId=';
   private $widgets_url_live    = 'https://static-eu.payments-amazon.com/OffAmazonPayments/de/js/Widgets.js?sellerId=';

   public  $button_url         = '';
//   private $button_url_sandbox = 'https://payments-sandbox.amazon.de/gp/widgets/button?sellerId=';
   private $button_url_sandbox = 'https://static-eu.payments-amazon.com/OffAmazonPayments/de/sandbox/lpa/js/Widgets.js';
//   private $button_url_live    = 'https://payments.amazon.de/gp/widgets/button?sellerId=';
   private $button_url_live = 'https://static-eu.payments-amazon.com/OffAmazonPayments/de/lpa/js/Widgets.js';

   public  $seller_id      = '';
   public  $client_id      = '';
   private $mws_access_key = '';
   private $mws_secret     = '';
   private $sandbox        = false;

   // Zugangsdaten Sandbox
   private $seller_id_sandbox      = '';   // Seller-Id (placeholder)
   // Bezahlen
   private $mws_access_key_sandbox = '';  // (placeholder)
   private $mws_secret_sandbox     = '';  // (placeholder)
   // Login
   private $client_id_sandbox      = '';  // (placeholder)
   private $client_secret_sandbox  = '';  // (placeholder)

   private $params;

   function __construct() {
      $this->params = Control::getParams();

      if (defined('CONF_MODULE_AMAZON_SANDBOX')) {
         $this->sandbox        = true;
         $this->widgets_url    = $this->widgets_url_sandbox;
         $this->button_url     = $this->button_url_sandbox;
         $this->seller_id      = $this->seller_id_sandbox;
         $this->mws_access_key = $this->mws_access_key_sandbox;
         $this->mws_secret     = $this->mws_secret_sandbox;
         $this->client_id      = $this->client_id_sandbox;
      }

      else {
         $this->widgets_url    = $this->widgets_url_live;
         $this->button_url     = $this->button_url_live;
         $this->seller_id      = $this->params->firma['amazon_seller'];
         $this->mws_access_key = $this->params->firma['amazon_access'];
         $this->mws_secret     = $this->params->firma['amazon_secret'];
         $this->client_id      = $this->params->firma['amazon_client'];
      }

      $this->params = Control::getParams();
   }


   public function checkPayment() {
      $config = array('merchant_id' => $this->seller_id,
                      'access_key'  => $this->mws_access_key,
                      'secret_key'  => $this->mws_secret,
                      'client_id'   => $this->client_id,
                      'region'      => 'de',
                      'sandbox'     => $this->sandbox);

      // Instantiate the client class with the config type
      $client = new PWA\Client($config);

      $requestParameters = array();

      // Adding the parameters values to the respective keys in the array
      // $requestParameters['amazon_reference_id'] = 'AMAZON_REFERENCE_ID';

      // Or
      // If $requestParameters['amazon_reference_id'] is not provided,
      // either one of the following ID input is needed
      $requestParameters['amazon_order_reference_id']   = $this->params->postString('amazonOrderReferenceId');
      // $requestParameters['amazon_billing_agreement_id'] = 'AMAZON_BILLING_AGREEMENT_ID';

      $requestParameters['seller_id'] = null;
      $requestParameters['charge_amount'] = $_SESSION['wk_netto'] + $_SESSION['wk_steuer1'] + $_SESSION['wk_steuer2'] + $_SESSION['wk_steuer3'] + $_SESSION['versand_ust'] + $_SESSION['zahlart_ust'];
//      $requestParameters['currency_code'] = 'EUR';
      $requestParameters['currency_code'] = \Helper::waehrungText($this->params->firma['waehrung'.$this->params->waehrung_id], 2);
      $requestParameters['authorization_reference_id'] = $_SESSION['bestellnummer'];
      $requestParameters['transaction_timeout'] = 0;
      $requestParameters['capture_now'] = true; //`true` for Digital goods
      $requestParameters['charge_note'] = $_SESSION['bestellnummer'];
      $requestParameters['charge_order_id'] = $_SESSION['bestellnummer'];
      $requestParameters['store_name'] = $this->params->firma['shop_name'];
      $requestParameters['platform_Id'] = null;
      $requestParameters['custom_information'] = $_SESSION['bestellnummer'];
      $requestParameters['mws_auth_token'] = null;

      // Get the Authorization response from the charge method
      $response = $client->charge($requestParameters);
      $arr = $response->toArray();

      if (defined('CONF_MODULE_AMAZON_DEBUG')) {
         $fp = fopen(DEBUG_LOG_DIR.'/amazon_request_back.txt', 'a');
         fwrite($fp, date('d.m.Y H:i:s').' a- '.print_r($arr, true).CR);
         fclose($fp);
      }
/*
      if (!isset($arr['Error'])) {
         $result = $arr['AuthorizeResult']['AuthorizationDetails']['AuthorizationStatus'];

         if ($result['State'] == 'Closed' && $result['ReasonCode'] == 'MaxCapturesProcessed') {
            $_SESSION['AmazonAuthorizationId'] = substr($arr['AuthorizeResult']['AuthorizationDetails']['AmazonAuthorizationId'], 0, 18);
            $_SESSION['AmazonWarning'] = '';

            return true;
         }

         if ($result['State'] == 'Declined' && $result['ReasonCode'] == 'InvalidPaymentMethod') {
            $_SESSION['AmazonAuthorizationId'] = substr($arr['AuthorizeResult']['AuthorizationDetails']['AmazonAuthorizationId'], 0, 18);
            $_SESSION['AmazonWarning'] = ' <span style="color:#cc0000;">Bitte prüfen</span>';

            $mail = Control::getPhpMailer();
            $mail->ClearAddresses();
            $mail->ClearAttachments();
            $mail->CharSet = 'UTF-8';
            $mail->AddAddress($this->params->firma['email']);
            $mail->SetFrom($this->params->firma['email'], $this->params->firma['mailfrom']);
            $mail->Subject = 'Probleme bei Bezahlung mit Amazon';
            $mail->MsgHTML('<p>Bestellnummer: '.$_SESSION['bestellnummer'].'<br />Zahlungsbestätigung von Amazon mit Problemen bei Zahlungsmethode. Bitte Zahlungseingang bei Amazon überprüfen.</p>');
            $mail->Send();

            return true;
         }
      }

*/

//      return false;
      return true;
   }
}
