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

if (!defined('KANPAICLASSIC')) {
   //   die ("This file cannot run outside the KANPAICLASSIC&reg; Shopsystem");
}

class KANPAICLASSIC_vrpay {
   private $vrpay_test = 'https://payinte.vr-epay.de/service/trx';
   private $vrpay_live = 'https://pay.vr-epay.de/service/trx';
   private $vrpay_url;
    
   private $vrpay_ok;
   private $vrpay_cancel;
   private $vrpay_notify;
   private $vrpay_fail;
   private $vrpay_agb;

   // Testzugang
   private $vrpay_number = '1000010843';
   private $vrpay_user = 'sendpay';
   private $vrpay_pass = 'jo25karo';
    
   private $db;
   private $params;
    
   function __construct() {
      $this->db = Control::getDB();
      $this->params = Control::getParams();

      $sql = "SELECT vrpay_number, vrpay_pass, vrpay_url
            FROM #__firma
            WHERE id = 1";
       
      $this->db->query($sql);
      $data = $this->db->getObject();

      // rvpay-Daten setzen, abhängig ob nummer angegeben (Live)
      if ($data->vrpay_number != '') {
         $this->vrpay_url = $this->vrpay_live;
         $this->vrpay_number = $data->vrpay_number;
         $this->vrpay_pass = $data->vrpay_pass;
      }
      else {
         $this->vrpay_url = $this->vrpay_test;
      }

      $this->vrpay_notify = 'https://'.$data->vrpay_url;
      
      $this->vrpay_ok     = SHOP_URL_IDX.'/vrpay_ok';
      $this->vrpay_cancel = SHOP_URL_IDX.'/vrpay_fail';
      $this->vrpay_fail   = SHOP_URL_IDX.'/vrpay_fail';
      $this->vrpay_agb    = SHOP_URL_IDX.'/agb/';
   }
    
   // Anfrage liefert URL für Redirect
   public function bezahlen ($betrag, $best_nr) {
      $secret = bin2hex(openssl_random_pseudo_bytes(5));
      $_SESSION['secret'] = $secret;
      // Preis in Cent, muss bei Testserver ganzer €-Betrag sein
      $preis = (float)str_replace(array('.', '.'), '', $betrag) * 100;

      $post  = 'service=VRPAYPAGE';
      $post .= '&auth_partnerno='.$this->vrpay_number;
      $post .= '&auth_user='.$this->vrpay_user;
      $post .= '&auth_password='.$this->vrpay_pass;
      $post .= '&trx_partnerno='.$this->vrpay_number;
      $post .= '&trx_refno='.$best_nr;
      $post .= '&trx_amount='.$preis;
      $post .= '&trx_currency=EUR';
      $post .= '&trx_action=PURCHASE';
      $post .= '&notify_shopurl='.$this->vrpay_notify;
      $post .= '&shop_urlsuccess='.$this->vrpay_ok;
      $post .= '&shop_urlfailure='.$this->vrpay_fail;
      $post .= '&shop_urlcancel='.$this->vrpay_cancel;
      $post .= '&shop_urlterms='.$this->vrpay_agb;
      $post .= '&notify_secret='.$secret;
      $post .= '&notify_profile=PAY';
      
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $this->vrpay_url);
      curl_setopt($ch, CURLOPT_HTTP_VERSION, 1.1);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
      curl_setopt($ch, CURLOPT_SSLVERSION, 3);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($ch, CURLOPT_HEADER, 1);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
      
      
      // send request to VR pay virtuell
      $ret = curl_exec($ch);
      
      //check response
      if ($ret == false) {
          curl_close($ch);
          echo 'Fehler: Paymentserver nicht erreichbar ' . '(Falsche Payment-Server-URL oder Timeout nach 20 Sek., ' . 'da Firewall nicht freigeschaltet)';
      } 
      else {
         $info = curl_getinfo($ch); 
         $redirect = $this->_filterURL(curl_multi_getcontent($ch));
         if ($redirect != "") {
            header("Location: " . $redirect);
            curl_close($ch);
            // KEIN EXIT!!!
         }
         else {
            echo $this->vrpay_url.'<br />';
            print_r($post);
            echo '<br />';
            switch ($info['http_code']) {
               case "200": // error occured - details in content
                  $message = str_replace("\r", "", $ret);
                  $content = explode("\n\n", $message);
                  echo $content[1];
                  break;
               case "401": // authentication failure
                  echo '401: Anmeldung am Paymentserver fehlgeschlagen: ' . 'Bitte prüfen Sie die Anmeldedaten (Partner-Nr, ' . 'sendpay-Passwort) und die URL zum Payment-Server.';
                  break;
               case "403": // authentication failure
                   echo '403: Anmeldung am Paymentserver fehlgeschlagen: ' . 'Bitte prüfen Sie die Anmeldedaten (Partner-Nr, ' . 'sendpay-Passwort) und die URL zum Payment-Server.';
                   break;
               default: // more HTTP-codes - system failure
                   echo 'Ein Systemfehler ist aufgetreten.';
                   break;
              }
            exit;
          }
      }
   }

   private function _filterURL($data) {
         $returnURL = "";
       $row = explode("\r\n", $data);
       if (!isset($row[1])) {
           $row = explode("\n", $data);
       }
       
       foreach ($row as $datacontent) {
           if (substr($datacontent, 0, 9) == 'Location:') {
               $returnURL_temp = explode(': ', $datacontent);
               $returnURL      = $returnURL_temp[1];
           }
       }
       
       return $returnURL;
   }

}
?>
