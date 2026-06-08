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

// Doku: https://developer.paypal.com/docs/paypal-plus/germany/

// 1. auth: Vor jeder Verbindung
// 2. pppPayment: Bezahlung anmelden -> Parameter für JS
//    -> Bezahlung bei PPP durchgeführt
// 3. pppBack: Status der Bezahlung abfragen
//    -> Bestätigen anzeigen
// 4. pppExec: Bezahlung bestätigen

namespace KANPAICLASSIC;

if (!defined('KANPAICLASSIC')) {
   define('KANPAICLASSIC', true);
}
class KANPAICLASSIC_modulPaypalPlus {
   private $db;
   private $params;

   private $mode          = 'live';
   private $auth_url      = '';
   private $payment_url   = '';
   private $client_id     = '';
   private $client_secret = '';
   private $token         = '';

   function __construct() {
      $this->db     = Control::getDB();
      $this->params = Control::getParams();

      if (defined('CONF_PAYPALPLUS_SANDBOX')) {
         $this->mode = 'sandbox';
      }

      if ($this->mode == 'live') {
         $this->auth_url      = 'https://api.paypal.com/v1/oauth2/token';
         $this->payment_url   = 'https://api.paypal.com/v1/payments/payment';
         $this->client_id     = Helper::getData('ppp_client_id');
         $this->client_secret = Helper::getData('ppp_client_secret');
      }
      else {
         $this->auth_url      = 'https://api.sandbox.paypal.com/v1/oauth2/token';
         $this->payment_url   = 'https://api.sandbox.paypal.com/v1/payments/payment';
         $this->client_id     = 'AZrlEf--NOWYuyLmZBx2xHH4OgUyitQKH3JLR7w5glrVrRvTBYagOEIP0RllWgamv-ypvEIv2CmKeQjw';
         $this->client_secret = 'EIdb7akNCQCgYDOUKr9HXG0U4GPtvaLj1hT3PzJHItcbo_hlbaA6ICS5__X4NLBVjYCzSI1NMUKM7pg6';
      }
   }

   // Schritt 1: Von Bestellung-Formular aufgerufen -> liefert Login-Script (per AJAX) zurück
   // Auswahl Zahlungsmethode -> Weiterleitung Paypal
   public function pppPayment($re_id) {
      //if (!$this->_getAuth()) {
      $test = $this->_getAuth();

      if ($test !== true) {
         return $test;
      }

      // Rechnungsdaten erstellen
      $payment = $this->_getPayment($re_id);

      // Bezahlung anmelden
      $redirect_back = $this->_getRedirect($payment);

      if ($redirect_back === false) {
         return 'Verbindung mit Paypal nicht möglich (Redirect)';
      }

      $back_id = $redirect_back->id;
//      $back_intent = $redirect_back->intent;
//      $back_state = $redirect_back->state;
//      $back_payer = $redirect_back->payer;
//      $back_transactions = $redirect_back->transactions;
//      $back_create_time = $redirect_back->create_time;
      $back_links = $redirect_back->links;

      // Wird später benötigt 0 - GET (_getPaymentStatus), 1 - REDIRECT, 2 - POST()
      $this->db->query("UPDATE #__rechnung SET ppp_get_url = '".$back_links[0]->href."', ppp_post_url = '".$back_links[2]->href."', ppp_status = 10 WHERE id = $re_id");

      $html  = '<script src="https://www.paypalobjects.com/webstatic/ppplus/ppplus.min.js"type="text/javascript"></script>'.CR;
      $html .= '<div id="ppplus"></div>'.CR;
      $html .= '<script type="application/javascript">'.CR;
      $html .= '   var ppp;'.CR;
      $html .= '   (function testPPP() {'.CR;
      $html .= '      if(typeof(PAYPAL) == "undefined") {'.CR;
      $html .= '         setTimeout(testPPP, 100);'.CR;
      $html .= '      }'.CR;
      $html .= '      else {'.CR;
      $html .= '         ppp = PAYPAL.apps.PPP({'.CR;
      $html .= '            "approvalUrl"      : "'.$back_links[1]->href.'",'.CR;
      $html .= '            "placeholder"      : "ppplus",'.CR;
      $html .= '            "country"          : "DE",'.CR;
      $html .= '            "language"         : "de_DE",'.CR;
      $html .= '            "showPuiOnSandbox" : "true",'.CR;
      $html .= '            "useraction"       : "commit",'.CR;
//      $html .= '      "onLoad" : "callback",'.CR;
      $html .= '            "mode"             : "'.$this->mode.'"'.CR;
      $html .= '         });'.CR;
      $html .= '      }'.CR;
      $html .= '   })();'.CR;
      $html .= '</script>'.CR;

      return $html;
   }

   // Schritt 2: Redirect von Paypal -> Formular Bestätigung anzeigen
   public function pppBack($re_id) {
      $payment_done = false;
      $state = 'Fehler bei Verbindung (check_payment)';

      $paymentId = $_SESSION['paymentId'];
      $token     = $_SESSION['token'];
      $PayerID   = $_SESSION['PayerID'];

      if (!$this->_getAuth()) {
         return 'Verbindung mit Paypal nicht möglich (Auth)';
      }

      // state: created, approved, failed, canceled, expired, pending, or in_progress.
      // Zahlungsstatus abfragen
      $test = $this->_getPaymentStatus($re_id, $paymentId, $PayerID);

      if (isset($test->name) && $test->name == 'PAYMENT_ALREADY_DONE') {
         $payment_done = true;
      }

      else if (isset($test->state)) {
         $state = $test->state;

         if ($state == 'created') {
            return 'ok';
         }

         if ($state == 'approved') {
            return 'done';
         }
      }

      if ($payment_done) {
         return 'done';
      }

      return 'Status: '.$state;
   }

   // Schritt 3: Zahlung bestätigen
   public function pppExec($re_id) {
      $state = 'Fehler bei Verbindung (exec_payment)';

      if (!$this->_getAuth()) {
         return 'Verbindung mit Paypal nicht möglich (Auth)';
      }

      // Zahlung durchführen
      $paymentId = $_SESSION['paymentId'];
      $token     = $_SESSION['token'];
      $PayerID   = $_SESSION['PayerID'];
      $test      = $this->_execPayment($re_id, $PayerID, $paymentId);

      if (isset($test->name) && $test->name == 'PAYMENT_ALREADY_DONE') {
         return 'done';
      }

      // Bestellung in DB eintragen
      else if (isset($test->state) && $test->state == 'approved') {
         $email = $test->payer->payer_info->email;
         $info = $test->id;
         $re_nr = $this->db->getRechnungsnummer();
         $re_dat = date('Y-m-d');

         if (Helper::getData('za_automatik', 'n') == 'y') {
            $this->db->query("UPDATE #__rechnung SET
                                 `status`          = 2,
                                 `pdf`             = 'r',
                                 `zahlungsinfo1`   = '$email',
                                 `zahlungsinfo2`   = '$info',
                                 `ppp_status`      = 0,
                                 `rechnungsnummer` = '$re_nr',
                                 `rechnungsdatum`  = '$re_dat',
                                 `zahlungdatum`    = '$re_dat'
                              WHERE id = $re_id");
            $mail = Control::getMail();
            $mail->sendBestellung($_SESSION['email'], $re_id, true);
            $im_export = Control::getImportExport();
            $im_export->exportBuchungenAuto($re_id);
         }

         else {
            $this->db->query("UPDATE #__rechnung SET
                                 `status`          = 1,
                                 `zahlungsinfo1`   = '$email',
                                 `zahlungsinfo2`   = '$info',
                                 `ppp_status`      = 0,
                                 `rechnungsnummer` = '$re_nr',
                                 `rechnungsdatum`  = '$re_dat',
                                 `zahlungdatum`    = '$re_dat'
                              WHERE id = $re_id");
         }

         if (defined('CONF_PAYPALPLUS_DEBUG')) {
            $fp = fopen(DEBUG_LOG_DIR.'/ppp_4execPayment.txt', 'a');
            fwrite($fp, date('d.m.Y H:i:s').' EE '.print_r($test, true).' : '.$test->state.CR.$this->db->last_sql.CR);
            fclose($fp);
         }

         return 'ok';
      }

      return 'failed';
   }

   // Authentifizierung
   private function _getAuth() {
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($ch, CURLOPT_TIMEOUT, 60);
      curl_setopt($ch, CURLOPT_USERAGENT, 'PayPal-PHP-SDK');
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
      curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
//      curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST,'TLSv1');

      curl_setopt($ch, CURLOPT_URL, $this->auth_url);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');

      curl_setopt($ch, CURLOPT_USERPWD, $this->client_id.':'.$this->client_secret);
      curl_setopt($ch, CURLOPT_HEADER, false);
      curl_setopt($ch, CURLINFO_HEADER_OUT, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('User-Agent: PayPalSDK/PayPal-PHP-SDK 1.5.1 (lang=PHP;v=5.6.1;bit=64;os=Linux_3.16.7-21-default;machine=x86_64;openssl=1.0.1k-fips;curl=7.42.1)',
                                                 'Accept: */*',
                                                 'Accept-Language: en_US'
                                               //  'PayPal-Partner-Attribution-Id: AngellEYE_SP_WooCommerce'
                                           ));

      $curl_back = curl_exec($ch);

      if (defined('CONF_PAYPALPLUS_DEBUG')) {
         $fp = fopen(DEBUG_LOG_DIR.'/ppp_1auth.txt', 'a');
         fwrite($fp, date('d.m.Y H:i:s').' a- '.print_r($curl_back, true).CR);
         fclose($fp);
      }

      // Fehler bei Verbindung
      if ($curl_errno = curl_errno($ch)) {
         if (defined('CONF_PAYPALPLUS_DEBUG')) {
            $fp = fopen(DEBUG_LOG_DIR.'/ppp_1auth.txt', 'a');
            fwrite($fp, date('d.m.Y H:i:s').' A- '.print_r(curl_error($ch), true).CR);
            fclose($fp);
         }

//         return false;
         return 'Verbindung mit Paypal nicht möglich (Auth)';
      }

      curl_close($ch);

      $test = json_decode($curl_back);

      if (is_object($test) && isset($test->access_token)) {
         $this->token = $test->access_token;
         return true;
      }

//      return false;
      return 'Verbindungsparameter sind nicht korrekt';
   }

   // Bezahlung anmelden
   private function _getRedirect($payment) {
      $ch = curl_init($this->payment_url);
      curl_setopt($ch, CURLOPT_SSLVERSION, 1);
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($ch, CURLOPT_TIMEOUT, 60);
      curl_setopt($ch, CURLOPT_USERAGENT, 'PayPal-PHP-SDK');
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
      curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST,'TLSv1');

      curl_setopt($ch, CURLOPT_HEADER, false);
      curl_setopt($ch, CURLINFO_HEADER_OUT, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json',
//                                                 'User-Agent: PayPalSDK/PayPal-PHP-SDK 1.5.1 (lang=PHP;v=5.6.1;bit=64;os=Linux_3.16.7-21-default;machine=x86_64;openssl=1.0.1k-fips;curl=7.42.1)',
                                                 'Authorization: Bearer '.$this->token,
                                                 'PayPal-Request-Id: '.ip2long($_SERVER['REMOTE_ADDR']).getmypid().$_SERVER['REQUEST_TIME'] . mt_rand(0, 0xffff),
//                                                 'PayPal-Partner-Attribution-Id: AngellEYE_SP_WooCommerce'
                                                 )
                                             );

      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $payment);
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');

      $curl_back = curl_exec($ch);
      $curl_errno = curl_errno($ch);
      $curl_error = curl_error($ch);
      curl_close($ch);

      if ($curl_errno != 0) {
         if (defined('CONF_PAYPALPLUS_DEBUG')) {
            $fp = fopen(DEBUG_LOG_DIR.'/ppp_2get_redirect.txt', 'a');
            fwrite($fp, date('d.m.Y H:i:s').' R- '.$curl_back.CR);
            fclose($fp);
         }

         return false;
      }

      if (defined('CONF_PAYPALPLUS_DEBUG')) {
         $fp = fopen(DEBUG_LOG_DIR.'/ppp_2get_redirect.txt', 'a');
         fwrite($fp, date('d.m.Y H:i:s').' r- '.$curl_back.CR);
         fclose($fp);
      }

      $test = json_decode($curl_back);

      if (is_object($test)) {
         return $test;
      }

      return false;
   }

   // Informationen über Bezahlung abrufen
   private function _getPaymentStatus($re_id, $pay_id, $payer_id) {
      $url = $this->db->querySingleValue("SELECT ppp_get_url FROM #__rechnung WHERE id = $re_id");

      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_SSLVERSION, 1);
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($ch, CURLOPT_TIMEOUT, 60);
      curl_setopt($ch, CURLOPT_USERAGENT, 'PayPal-PHP-SDK');
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
      curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST,'TLSv1');

      curl_setopt($ch, CURLOPT_HEADER, false);
      curl_setopt($ch, CURLINFO_HEADER_OUT, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json',
                                                 'Authorization: Bearer '.$this->token,
                                                 'PayPal-Request-Id: '.ip2long($_SERVER['REMOTE_ADDR']).getmypid().$_SERVER['REQUEST_TIME'] . mt_rand(0, 0xffff),
                                                 )
                                             );

      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, '{"payer_id": "'.$payer_id.'"}');
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

      $curl_back = curl_exec($ch);
      $curl_errno = curl_errno($ch);
      $curl_error = curl_error($ch);
      curl_close($ch);

      if ($curl_errno != 0) {
         if (defined('CONF_PAYPALPLUS_DEBUG')) {
            $fp = fopen(DEBUG_LOG_DIR.'/ppp_3paymentStatus.txt', 'a');
            fwrite($fp, date('d.m.Y H:i:s').' S- '.$curl_error.CR.$curl_back.CR);
            fclose($fp);
         }

         return false;
      }

      if (defined('CONF_PAYPALPLUS_DEBUG')) {
         $fp = fopen(DEBUG_LOG_DIR.'/ppp_3paymentStatus.txt', 'a');
         fwrite($fp, date('d.m.Y H:m:s').' s- '.$curl_back.CR);
         fclose($fp);
      }

      $test = json_decode($curl_back);

      if (is_object($test)) {
         return $test;
      }

      return false;
   }

   // Bestellung bestätigen
   private function _execPayment($re_id, $PayerId, $paymentId) {
      $url = $this->db->querySingleValue("SELECT ppp_post_url FROM #__rechnung WHERE id = $re_id");

      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_SSLVERSION, 1);
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
      curl_setopt($ch, CURLOPT_TIMEOUT, 60);
      curl_setopt($ch, CURLOPT_USERAGENT, 'PayPal-PHP-SDK');
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
      curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST,'TLSv1');

      curl_setopt($ch, CURLOPT_HEADER, false);
      curl_setopt($ch, CURLINFO_HEADER_OUT, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json',
                                                 'Authorization: Bearer '.$this->token,
                                                 'PayPal-Request-Id: '.ip2long($_SERVER['REMOTE_ADDR']).getmypid().$_SERVER['REQUEST_TIME'] . mt_rand(0, 0xffff),
                                                 )
                                             );

      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, '{"payer_id": "'.$PayerId.'"}');
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');

      $curl_back = curl_exec($ch);
      $curl_errno = curl_errno($ch);
      $curl_error = curl_error($ch);
      curl_close($ch);

      $test = json_decode($curl_back);

      if ($curl_errno != 0) {
         if (defined('CONF_PAYPALPLUS_DEBUG')) {
            $fp = fopen(DEBUG_LOG_DIR.'/ppp_4execPayment.txt', 'a');
            fwrite($fp, date('d.m.Y H:i:s').' E- '.$curl_error.' - '.$curl_back.CR);
            fclose($fp);
         }

         return false;
      }

      if (defined('CONF_PAYPALPLUS_DEBUG')) {
         $fp = fopen(DEBUG_LOG_DIR.'/ppp_4execPayment.txt', 'a');
         fwrite($fp, date('d.m.Y H:i:s').' e- '.$curl_back.CR.print_r($test, true).CR);
         fclose($fp);
      }

      if (is_object($test)) {
         return $test;
      }

      return false;
   }

   // Adresse / Bestelldaten als JSON zurückgeben aus shop_rechnung/$re_id
   private function _getPayment($re_id) {
      $bestellung = Control::getBestellung();
      $bestellung->getDetailBestellung($re_id);
      $data = $bestellung->dataDetails;

      $sum  = $data->brutto;
      $sum -= $data->rabatt_brutto;
      $sum += $data->versand_brutto;
      $sum += $data->zahlart_brutto;
      $sum -= $data->gutschrift_brutto;
      // $sum -= $data->gutschein_brutto; -> bereits in $data->gutschrift_brutto enthalten

      $summe = number_format($sum, 2, '.', '');

      $pay  = '  {"redirect_urls":{';
      $pay .= '          "return_url":"'.SHOP_URL.'/ppp_back/'.$re_id.'/",';
      $pay .= '          "cancel_url":"'.SHOP_URL.'/ppp_cancel/'.$re_id.'/"';
      $pay .= '       },';
      $pay .= '       "intent":"sale",';
      $pay .= '       "payer":{';

      $pay .= '         "payment_method":"paypal",';
      $pay .= '         "payer_info":{';
      $pay .= '             "first_name":"'.$data->vorname.'",';
      $pay .= '             "last_name":"'.$data->nachname.'",';
      $pay .= '             "email":"'.$data->email.'",';
//      $pay .= '             "birthday":"'.$data->geb_datum.'",';
      $pay .= '             "billing_address":{';
      $pay .= '                "line1":"'.$data->adresse.' '.$data->hausnr.'",';
      $pay .= '                "line2":"",';
      $pay .= '                "city":"'.$data->ort.'",';
      $pay .= '                "postal_code":"'.$data->plz.'",';
      $pay .= '                "country_code":"DE",';
      $pay .= '                "phone":"'.Helper::cleanPhone($data->telefon).'",';
      $pay .= '                "state":""';
      $pay .= '             },';

      $pay .= '             "shipping_address":{';
      $pay .= '                "line1":"'.($data->lieferadresse == 'y' ? $data->lf_adresse.' '.$data->lf_hausnr : $data->adresse.' '.$data->hausnr).'",';
      $pay .= '                "line2":"",';
      $pay .= '                "city":"'.($data->lieferadresse == 'y' ? $data->lf_ort : $data->ort).'",';
      $pay .= '                "postal_code":"'.($data->lieferadresse == 'y' ? $data->lf_plz : $data->plz).'",';
      $pay .= '                "country_code":"DE",';
      $pay .= '                "phone":"'.Helper::cleanPhone($data->telefon).'",';
      $pay .= '                "state":"",';
      $pay .= '                "recipient_name":"'.$data->vorname.' '.$data->nachname.'"}';
      $pay .= '            }';
      $pay .= '         },';

      $pay .= '         "transactions":[';
      $pay .= '            {';
      $pay .= '               "amount":{';
      $pay .= '                  "currency":"EUR",';
      $pay .= '                  "total":"'.$summe.'",';
      $pay .= '                  "details":{';
      $pay .= '                     "subtotal":"'.$summe.'"';
      $pay .= '                  }';
      $pay .= '               },';
      $pay .= '               "description":"",';
      $pay .= '               "item_list":{';
      $pay .= '                  "items":[';
      $pay .= '                     {';
      $pay .= '                        "name":"Bestellung '.$data->bestellnummer.'",';
      $pay .= '                        "price":"'.$summe.'",';
      $pay .= '                        "currency":"EUR",';
      $pay .= '                        "quantity":"1"';
      $pay .= '                     }';
      $pay .= '                  ]';
      $pay .= '               },';

      $pay .= '               "invoice_number":"'.$re_id.'"';
      $pay .= '            }';
      $pay .= '         ]';
      $pay .= '      }';

      return $pay;
   }

}