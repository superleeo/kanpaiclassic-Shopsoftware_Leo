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

if (!defined('KANPAICLASSIC')) {
   define('KANPAICLASSIC', true);
}

define('DEBUG',           false);
define('SANDBOX_UUID',    "63e29afb-b31b-4b38-8a8f-8b17caae3509");
define('SANDBOX_URL',     'https://service-pat.twint.ch/merchant/service/TWINTMerchantServiceV2');
define('LIVE_URL',        'https://service.twint.ch/merchant/service/TWINTMerchantServiceV2');
define('CASHREGISTER_ID', "Royalart Shopsoftware");
define('TWINT_PASSPHRSE', "kanpaiclassiccert");

// used for the payment
define('OPERATION', "PAYMENT_IMMEDIATE"); //CREDIT, PAYMENT_IMMEDIATE, PAYMENT_DEFERRED

class KANPAICLASSIC_modulTwint
{
   private $db          = null;
   private $params      = null;
   private $uuid        = '';
   private $service_url = '';
   private $local_cert;
   private $wsdl_url    = '';

   public function __construct() {
      ini_set('soap.wsdl_cache_enabled',0);
      ini_set('soap.wsdl_cache_ttl',0);      $this->db     = \KANPAICLASSIC\Control::getDB();

      $this->params = \KANPAICLASSIC\Control::getParams();

      if (defined('CONF_TWINT_SANDBOX')) {
         $this->uuid        = SANDBOX_UUID;
         $this->service_url = SANDBOX_URL;
         $this->local_cert  = __DIR__.'/sandbox.pem';
         $this->wsdl_url    = __DIR__.'/TWINTMerchantInterfaceV2_0.wsdl';

      } else {
         $this->uuid        = \KANPAICLASSIC\Helper::getData('twint_uuid');
         $this->service_url = LIVE_URL;
         $this->local_cert  = __DIR__.'/twint.pem';
         $this->wsdl_url    = __DIR__.'/TWINTMerchantInterfaceV2_0.wsdl';
//         $this->wsdl_url    = '';
      }
   }


   public function getPairing() {
      $client = new \SoapClient($this->wsdl_url, $this->_soapOptions());
      // check if the System is there
      $system_status = $this->_check_system_status($client);
//var_dump($system_status);
      if ($system_status->Status == "OK") {
         $check = \KANPAICLASSIC\Helper::getData('twint_check');


         // register the cash register for all further actions
         if ($check == '') {
            $response = $this->_enroll_cash_register($client);

            if (! is_soap_fault($response)) {
               \KANPAICLASSIC\Helper::setData('twint_check', 'registered');
            }

            else {
               return array('status' => 'failed', 'msg' => 'Anmeldung Kasse fehlgeschlagen');
            }
         }


         // start the pairing
         $checkin_info   = $this->_request_checkin($client);
         $pairing_uuid   = $checkin_info->CheckInNotification->PairingUuid;
         $pairing_status = $checkin_info->CheckInNotification->PairingStatus;
         $token          = $checkin_info->Token;
         $qrcode         = $checkin_info->QRCode;

         return array('status' => 'ok', 'token' => $token, 'qrcode' => $qrcode, 'pairing_uuid' => $pairing_uuid);
      }

      else {
         return array('status' => 'failed', 'msg' => 'Twint-Server nicht erreichbar');
      }
   }

   public function waitPairing($pairing_uuid) {
      $client         = new \SoapClient($this->wsdl_url, $this->_soapOptions());
      $monitor_info   = $this->_monitor_checkin($client, $pairing_uuid); // fetch the current status
      $pairing_status = $monitor_info->CheckInNotification->PairingStatus;

      // if ($pairing_status == 'PAIRING_ACTIVE')
      return ($pairing_status == 'PAIRING_ACTIVE' ? true : false);
   }

   public function startOrder($pairing_uuid) {
      $client = new \SoapClient($this->wsdl_url, $this->_soapOptions());

      $order                  = $this->_start_order($client, $pairing_uuid);
      $_SESSION['order_uuid'] = $order->OrderUuid;
      $order_status           = $order->OrderStatus->Status->_;
      $status_reason          = $order->OrderStatus->Reason->_;

      if ($order_status != "IN_PROGRESS" && $status_reason != "ORDER_CONFIRMATION_PENDING") {
         return true;
      }

      return false;
   }

   public function waitOrder($pairing_uuid) {
      $client        = new \SoapClient($this->wsdl_url, $this->_soapOptions());
      $order         = $this->monitor_order($client, $_SESSION['order_uuid']);
      $order_status  = $order->Order->Status->Status->_;
      $status_reason = $order->Order->Status->Reason->_;

      if ($order_status == "IN_PROGRESS" && $status_reason == "ORDER_CONFIRMATION_PENDING") {
         return true;
      }

      return false;
   }

   public function confirmOrder() {
      $client                  = new \SoapClient($this->wsdl_url, $this->_soapOptions());
      $order                   = $this->_confirm_order($client, $_SESSION['order_uuid']);

      $order_status            = $order->Order->Status->Status->_;
      $status_reason           = $order->Order->Status->Reason->_;
      $_SESSION['twint_amont'] = (float)$order->Order->AuthorizedAmount->Amount;

      if ($order_status == "SUCCESS" && $status_reason == "ORDER_OK") {
         return true;
      }

      return false;
   }

   private function _soapOptions() {
      $soap_options = array(
         // general parameters
         'encoding'           => 'UTF-8', // encode requests in utf-8
         'connection_timeout' => 10, // wait 10s for a connection
         'exceptions'         => true, // throw Exceptions on Errors
         'compression'        => (SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP),

         // merchant specific options
         'local_cert'         => $this->local_cert, // point to your Client Key&Certificate
         'passphrase'         => TWINT_PASSPHRSE, // The password for the Key
         'location'           => $this->service_url,
         'soap_version'       => SOAP_1_1,
         'trace'              => true
      );

      return $soap_options;
   }

   // helper to create a uuid in V4 Format
   private function _guidv4() {
       $data = openssl_random_pseudo_bytes(16);

       $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
       $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

       return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
   }

   // helper to create a valid SOAPHeader for the TWINT Services
   private function _create_header() {
      $headers = array();
      $request_header = array(
         "MessageId"             => $this->_guidv4(),
         "ClientSoftwareName"    => CASHREGISTER_ID,
         "ClientSoftwareVersion" => "V1.0"
      );

      $headers[] = new \SOAPHeader('http://service.twint.ch/header/types/v2', 'RequestHeaderElement', $request_header);

      return $headers;
   }

   // helper to decode soap errors
   private function _exit_with_error($fault) {
      \KANPAICLASSIC\Helper::setData('twint_check', '');
      $msg = sprintf("ERROR: The TWINT Server returned Error-Code '%s' with the following message: %s ()", $fault->faultcode, $fault->faultstring);
      exit($msg);
   }

   /* *** WEBSERVICE FUNCTIONS *** */

   private function _check_system_status($client) {
      // Prepare the parameters for the request
      $params = [
        "MerchantInformation" => [
            "MerchantUuid"   => $this->uuid,
            "CashRegisterId" => CASHREGISTER_ID
         ]
      ];

      /* Invoke webservice method with your parameters, in this case: Function1 */
      $headers = $this->_create_header();
      $client ->__setSoapHeaders($headers);

      try {
         $response = $client->checkSystemStatus($params);
      }
      catch(\SoapFault $e) {
         if (DEBUG) { var_dump($params, $client); }

         $this->_exit_with_error($e);
      }

      /* show debug output */
      if (DEBUG) { var_dump($response); }

      return $response;
   }

   private function _enroll_cash_register($client) {
      // Prepare the parameters for the request
      $params = array(
         "MerchantInformation" => array(
            "MerchantUuid" => $this->uuid,
            "CashRegisterId" => CASHREGISTER_ID
        ),
        "CashRegisterType" => "EPOS"
      );

      /* Invoke webservice method with your parameters, in this case: Function1 */
      $headers = $this->_create_header();
      $client ->__setSoapHeaders($headers);

      try {
         $response = $client->enrollCashRegister($params);
      }
      catch(SoapFault $e) {
         if (DEBUG) { print_r($client); }

          $this->_exit_with_error($e);
      }

      /* show debug output */
      if (DEBUG) { var_dump($response); }

      return $response;
   }

   private function _request_checkin($client) {
      // Prepare the parameters for the request
      $params = array(
        "MerchantInformation" => array(
            "MerchantUuid" => $this->uuid,
            "CashRegisterId" => CASHREGISTER_ID
        ),
        "QRCodeRendering" => 1,
        "UnidentifiedCustomer" => "true"
      );

      /* Invoke webservice method with your parameters, in this case: Function1 */
      $headers = $this->_create_header();
      $client ->__setSoapHeaders($headers);

      try {
         $response = $client->requestCheckIn($params);
      }
      catch(SoapFault $e) {
         if (DEBUG) { print_r($client); }

         $this->_exit_with_error($e);
      }

      /* show debug output */
      if (DEBUG) { var_dump($response); }

      return $response;
   }

   private function _monitor_checkin($client, $pairing_uuid) {
      // Prepare the parameters for the request
      $params = array(
         "MerchantInformation" => array(
         "MerchantUuid" => $this->uuid,
         "CashRegisterId" => CASHREGISTER_ID
         ),
         "PairingUuid" => $pairing_uuid
      );

      /* Invoke webservice method with your parameters, in this case: Function1 */
      $headers = $this->_create_header();
      $client ->__setSoapHeaders($headers);

      try {
          $response = $client->monitorCheckIn($params);
      }
      catch(SoapFault $e) {
         if (DEBUG) { print_r($client); }

         $this->_exit_with_error($e);
      }

      /* show debug output */
      if (DEBUG) { var_dump($response); }

      return $response;
   }

   private function _start_order($client, $pairing_uuid) {
      // Prepare the parameters for the request
      $params = array(
         "MerchantInformation" => array(
            "MerchantUuid" => $this->uuid,
            "CashRegisterId" => CASHREGISTER_ID
         ),
         "Order" => array(
         "type" => OPERATION,
         "confirmationNeeded" => "true",
         "PostingType" =>  'GOODS',
         "RequestedAmount" => array(
            "Amount" => $_SESSION['twint_amount'],
            "Currency" => 'CHF'
         ),
         "MerchantTransactionReference" => array('_' => $this->_guidv4()),
         ),
         "PairingUuid" => $pairing_uuid
      );

      /* Invoke webservice method with your parameters, in this case: Function1 */
      $headers = $this->_create_header();
      $client ->__setSoapHeaders($headers);

      try {
         $response = @$client->startOrder($params);
      }
      catch(SoapFault $e) {
         if (DEBUG) { print_r($client); }

         $this->_exit_with_error($e);
      }

      /* show debug output */
      if (DEBUG) { var_dump($response); }

      return $response;
   }

   public function monitor_order($client, $order_uuid) {
      // Prepare the parameters for the request
      $params = array(
        "MerchantInformation" => array(
            "MerchantUuid" => $this->uuid,
            "CashRegisterId" => CASHREGISTER_ID
        ),
        "OrderUuid" => $order_uuid
      );

      /* Invoke webservice method with your parameters, in this case: Function1 */
      $headers = $this->_create_header();
      $client ->__setSoapHeaders($headers);
      try
      {
          $response = $client->monitorOrder($params);
      }
      catch(SoapFault $e)
      {
          if (DEBUG) { print_r($client); }
          $this->_exit_with_error($e);
      }

      /* show debug output */
      if (DEBUG) { var_dump($response); }

      return $response;
   }

   private function _confirm_order($client, $order_uuid) {
      // Prepare the parameters for the request
      $params = array(
         "MerchantInformation" => array(
            "MerchantUuid" => $this->uuid,
            "CashRegisterId" => CASHREGISTER_ID
         ),
         "OrderUuid" => $order_uuid,
         "RequestedAmount" => array(
           "Amount" => $_SESSION['twint_amount'],
            "Currency" => "CHF",
         )
      );

   /* Invoke webservice method with your parameters, in this case: Function1 */
   $headers = $this->_create_header();
   $client ->__setSoapHeaders($headers);
   try
   {
       $response = $client->confirmOrder($params);
   }
   catch(SoapFault $e)
   {
       if (DEBUG) { print_r($client); }
       exit_with_error($e);
   }

   /* show debug output */
   if (DEBUG) { var_dump($response); }

   return $response;
}

   public function cancel_order($client, $order_uuid) {
      // Prepare the parameters for the request
      $params = array(
        "MerchantInformation" => array(
            "MerchantUuid" => MERCHANT_UUID,
            "CashRegisterId" => CASHREGISTER_ID
        ),
        "OrderUuid" => $order_uuid
      );

      /* Invoke webservice method with your parameters, in this case: Function1 */
      $headers = create_header();
      $client ->__setSoapHeaders($headers);
      try
      {
          $response = $client->cancelOrder($params);
      }
      catch(SoapFault $e)
      {
          if (DEBUG) { print_r($client); }
          exit_with_error($e);
      }

      /* show debug output */
      if (DEBUG) { var_dump($response); }

      return $response;
   }

   public function cancel_checkin($client, $pairing_uuid) {
      // Prepare the parameters for the request
      $params = array(
        "MerchantInformation" => array(
            "MerchantUuid" => MERCHANT_UUID,
            "CashRegisterId" => CASHREGISTER_ID
        ),
        "Reason" => "PAYMENT_ABORT",
        "PairingUuid" => $pairing_uuid
      );

      /* Invoke webservice method with your parameters, in this case: Function1 */
      $headers = create_header();
      $client ->__setSoapHeaders($headers);
      try
      {
          $response = $client->cancelCheckIn($params);
      }
      catch(SoapFault $e)
      {
          if (DEBUG) { print_r($client); }
          exit_with_error($e);
      }

      /* show debug output */
      if (DEBUG) { var_dump($response); }

      return $response;
   }

   public function certificate() {
      $dir      = SHOP_PATH.'/classes/modules/twint/';
      $p12_key  = $this->params->postString('param1');
      $p12_file = $_FILES['file']['name'];
      $tmp_file = $_FILES['file']['tmp_name'];
      $pem_file = $dir.'twint.pem';
      @unlink($pem_file);

      move_uploaded_file($tmp_file, $dir.$p12_file);

      if ($p12_file == 'twint.pem') {
         exit(json_encode(['status' => 'ok', 'msg' => 'Zertifikat wurde gespeichert']));
      }
      $error    = '';

      if (function_exists('system')) {
         $line = "openssl pkcs12 -in ".$dir.$p12_file." -passin 'pass:".$p12_key."' -out ".$pem_file." -passout 'pass:".TWINT_PASSPHRSE."'";
         $retval = '';
         system($line, $retval);

         if ($retval != 0) {
            $error = 'Fehler system()';
         }
      }

      else {
         $results = array();
         $test    = openssl_pkcs12_read(file_get_contents($dir.$p12_file), $results, $p12_key);

         if($test) {
            $cert = $results['cert'];
            $pkey = '';
            openssl_pkey_export ($results['pkey'], $pkey, TWINT_PASSPHRSE);
            file_put_contents($pem_file, $pkey.$cert);
         }

         else {
            $error = openssl_error_string();
         }
      }

      @unlink($p12_file);
      exit(json_encode(['status' => ($error == '' ? 'ok' : 'error'), 'error' => $error, 'msg' => ($error == '' ? 'Neues Zertifikat erstellt' : 'Zerifikat konnte nicht erstellt werden')]));
   }
}
