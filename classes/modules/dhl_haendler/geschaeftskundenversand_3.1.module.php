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

// Doku: https://entwickler.dhl.de/group/ep/wsapis/geschaeftskundenversand

namespace KANPAICLASSIC;

if (!defined('KANPAICLASSIC')) {
   die ("This file cannot run outside the Kanpai Classic Shopsoftware");
}

// Autoloader für API-Klassen
function autoload_dhl($class_name) {
   $class_name = str_replace('KANPAICLASSIC', '', $class_name);
   $filename = __DIR__.'/geschaeftskundenversand_api_3.1.2/'.str_replace('\\', '/', $class_name).'.php';

   if (file_exists($filename)) {
     require_once($filename);
   }

   else {
      echo 'Not found: '.$filename;
   }
}

spl_autoload_register('KANPAICLASSIC\autoload_dhl');



class KANPAICLASSIC_modulDhlHaendler
{
   private $db = null;
   private $params = null;
   private $mode = 'send';

   // Sandbox
   private $test_server     = 'https://cig.dhl.de/services/sandbox/soap';
   private $test_cis_user   = 'jochen-3-1';
   private $test_cis_pass   = 'Sandro2010!';

   private $test_dhl_user    = '2222222222_01';
   private $test_dhl_sign    = 'pass';
   private $test_dhl_ekp     = '2222222222';

   // Bei falschem Login: Authorization Required
   private $live_server     = 'https://cig.dhl.de/services/production/soap';
   private $live_cis_user   = 'FlowShopsoftware_1';               //Application-ID
   private $live_cis_pass   = 'SVp1jEQkqFP7OO0RiCyHn7qkR7lv2i';   //Application-Token

   // CIG-Authentifiziereng -> SOAP-Header
   private $cis_server      = '';
   private $cis_user        = '';
   private $cis_pass        = '';

   // User_Auth -> HTTP-Auth
   private $dhl_user         = '';        // Benutzername
   private $dhl_sign         = '';        // Passwort
   private $dhl_ekp          = '';        // DHL-Kunden-Nr

   private $service_id      = '01';       // aus Paketart
   private $partner_id      = '01';       // Teilnehmer-Nr.
   private $major_release   = 3;          // API-Version
   private $minor_release   = 1;          // API-Version
   private $dhl_product     = 'V01PAK';   // aus Paketart
   private $seq_nr          = null;
   private $build           = null;

   private $sandbox         = true;
   private $libpath         = '';

   public function __construct() {
      $this->db     = Control::getDb();
      $this->params = Control::getParams();

      $this->_setLogins();
   }

   // Wenn firma/dhl_is_user != '' Live-Server, sonst Test-Server
   private function _setLogins() {
      $this->partner_id = Helper::getData('dhl_teilnehmer', '01');

      // Testzugang
      if (defined('CONF_DHL_SANDBOX')) {
         $this->cis_server = $this->test_server;
         $this->cis_user   = $this->test_cis_user;
         $this->cis_pass   = $this->test_cis_pass;
         $this->dhl_user   = $this->test_dhl_user;
         $this->dhl_sign   = $this->test_dhl_sign;
         $this->dhl_ekp    = $this->test_dhl_ekp;
      }

      // Produktivzugang
      else {
         $this->cis_server = $this->live_server;
         $this->cis_user   = $this->live_cis_user;
         $this->cis_pass   = $this->live_cis_pass;
         $this->dhl_user   = Helper::getData('dhl_is_user', '');
         $this->dhl_sign   = Helper::getData('dhl_is_sign', '');
         $this->dhl_ekp    = substr(Helper::getData('dhl_is_ekp', ''), 0, 10);
         $this->sandbox = false;
      }

      return;
   }


   // Liefert Option-List für Start-Bestellung und Ende-Bestellung
   public function getBestellungenByDate($datum) {
      $first = '';
      $last  = '';

      $bestellungen = $this->db->queryAllObjects("SELECT id, bestellnummer, dhl_send_check FROM #__rechnung WHERE collector != 'y' AND deleted = 'n' AND status > 2 AND status < 5 AND created BETWEEN '".$datum." 00:00:00' AND '".$datum." 23:59:59' ORDER BY id");

      if ($bestellungen) {
         $ende = count($bestellungen) - 1;

         for($i = 0; $i <= $ende; $i++) {
            if ($i == 0) {
               $first .= '<option value="'.$bestellungen[$i]->id.'" selected="selected">'.($bestellungen[$i]->dhl_send_check == 'y' ? '*' : '').$bestellungen[$i]->bestellnummer.'</option>';
            }
            else {
               $first .= '<option value="'.$bestellungen[$i]->id.'">'.($bestellungen[$i]->dhl_send_check == 'y' ? '*' : '').$bestellungen[$i]->bestellnummer.'</option>';
            }

            if ($i == $ende) {
               $last .= '<option value="'.$bestellungen[$i]->id.'" selected="selected">'.($bestellungen[$i]->dhl_send_check == 'y' ? '*' : '').$bestellungen[$i]->bestellnummer.'</option>';
            }
            else {
               $last .= '<option value="'.$bestellungen[$i]->id.'">'.($bestellungen[$i]->dhl_send_check == 'y' ? '*' : '').$bestellungen[$i]->bestellnummer.'</option>';
            }
         }
      }

      else {
         $first = '<option value="0">keine Daten</option>';
         $last  = '<option value="0">keine Daten</option>';
      }

      return (['start' => $first, 'ende' => $last]);
   }

   // Labels für Datum mit Start-Bestellnr bis Ende-Bestellnr. übertragen und PDF holen
   public function printLabels($start, $ende, $datum, $mode) {
      $_SESSION['dhl_paketart'] = $this->params->postInt('dhl_paketart');
      // $mode = 'send';
      $this->mode = $mode;

      // 1 als Default - DHL-Paket
      $paketart = (isset($_SESSION['dhl_paketart']) ? $_SESSION['dhl_paketart'] : 1);

      // DHL-Europapaket
      if ($paketart == 2) {
         $this->service_id = '54';
         $this->dhl_product = 'V54EPAK';
      }

      // DHL Paket International
      else if ($paketart == 3) {
         $this->service_id = '53';
         $this->dhl_product = 'V53WPAK';
      }

      // DHL Paket Connect
      else if ($paketart == 4) {
         $this->service_id = '55';
         $this->dhl_product = 'V55PAC';
      }

      // Warenpost
      else if ($paketart == 5) {
         $this->service_id = '62';
         $this->dhl_product = 'V62WP';
      }


      if (file_exists(SHOP_PATH.'/tmp/dhl_status.txt')) {
         $tmp = file_get_contents(SHOP_PATH.'/tmp/dhl_status.txt');

         if (strpos($tmp, 'ENDE') === false) {
            exit(json_encode(['status' => 'running', 'msg' => 'Labelerstellung läuft bereits']));
         }
      }

      $bestellungen_eu = $this->db->queryAllObjects("SELECT id, bestellnummer, dhl_send_check, lf_staat
                                                     FROM #__rechnung
                                                  WHERE collector != 'y'
                                                     AND lf_staat != 160
                                                     AND status > 2
                                                     AND status < 5
                                                     AND created BETWEEN '".$datum." 00:00:00' AND '".$datum." 23:59:59'
                                                     AND id >= $start
                                                     AND id <= $ende
                                                  ORDER BY id");

      $bestellungen_de = $this->db->queryAllObjects("SELECT id, bestellnummer, dhl_send_check, lf_staat
                                                     FROM #__rechnung
                                                  WHERE collector != 'y'
                                                     AND lf_staat = 160
                                                     AND status > 2
                                                     AND status < 5
                                                     AND created BETWEEN '".$datum." 00:00:00' AND '".$datum." 23:59:59'
                                                     AND id >= $start
                                                     AND id <= $ende
                                                  ORDER BY id");

      // Arays zusammenführen, falls NULL -> leeres Array
      $bestellungen = array_merge((is_array($bestellungen_eu) ? $bestellungen_eu : []), (is_array($bestellungen_de) ? $bestellungen_de : []));

      // Text für AJAX-Poll in Datei speichern
      if ($bestellungen) {
         $anzahl        = count($bestellungen);
         $anzahl_ok     = 0;
         $anzahl_failed = 0;

         // Startmeldung für AJAX-Poll speichern
//         $fp = fopen(SHOP_PATH.'/tmp/dhl_status.txt', 'w+');
//         fputs($fp, 'Erstellung von '.$anzahl.' Labels gestartet');
//         fclose($fp);

         for($i = 0; $i < $anzahl; $i++) {
//            $last = $bestellungen[$i]->bestellnummer;

            // Datenübertragung starten
            $status = $this->shipmentRequest($bestellungen[$i]->id);

            if ($status == true) {
               $anzahl_ok++;
            }

            else {
               $anzahl_failed++;
            }

            // Zwischenmeldung für AJAX-Poll speichern
//            $fp = fopen(SHOP_PATH.'/tmp/dhl_status.txt', 'w+');
//            fputs($fp, ($i + 1).' von '.$anzahl.' Bestellungen bearbeitet');
//            fclose($fp);
         }

         // Ende Bearbeitung AJAX-Poll in Datei speichern
//         $fp = fopen(SHOP_PATH.'/tmp/dhl_status.txt', 'w+');
//         fputs($fp, "ENDEBeendet<br />".$anzahl." Bestellungen bearbeitet<br />".$anzahl_ok." Labels wurden erstellt".($anzahl_failed > 0 ? "<br />".$anzahl_failed." Adresse(n) fehlerhaft" : ''));
//         fclose($fp);

         exit(json_encode(['status' => 'ok']));
      }

      exit(json_encode(['status' => 'nothing', 'msg' => 'Keine Bestellungen vorhanden']));
   }

   public function shipmentRequest($re_id) {
      // Rechnungsdaten lesen
      $data         = $this->db->querySingleObject("SELECT * FROM #__rechnung WHERE id = $re_id");
      $country_data = $this->db->querySingleObject("SELECT domain, region FROM #__laender WHERE id = $data->lf_staat");

      // Korrketur für Großbritannien uk -> gb
      if ($country_data->domain == 'uk') { $country_data->domain = 'gb'; }

      $data->country_data = $country_data;

      // Eintrag in dhl_status anlegen
      $this->db->query("INSERT INTO #__dhl_status SET re_id = $re_id, startdate = '".date('Y-m-d H:i:s')."'");
      $dhl_status_id = $this->db->getNewId();

      if ((int)$dhl_status_id > 0) {
         $this->seq_nr = $dhl_status_id;
      }

      $login = [
                  'login'        => $this->cis_user,
                  'password'     => $this->cis_pass,
                  'location'     => $this->cis_server,
                  'soap_version' => SOAP_1_1,
                  'trace'        => 1,
                  'exception'    => 0
      ];

      $intraship   = new \SoapClient(__DIR__.'/geschaeftskundenversand_wsdl_3.1.2/geschaeftskundenversand-api-3.1.2.wsdl', $login);
      $auth        = (object)[
                     'user'      => $this->dhl_user,
                     'signature' => $this->dhl_sign
      ];

      $auth_header = new \SoapHeader('http://dhl.de/webservice/cisbase', 'Authentification', $auth);

      $intraship->__setSoapHeaders($auth_header);

      // Shipment Details
      $ShipmentDetails = $this->_shipmentDetails($data);

      // Daten Absender / Shop
      $Shipper = $this->_getShipper();

      // Daten Empfänger
      $Receiver           = $this->_getReceiver($data);
      $returnReceiver     = null;
      $ExportDoku         = null;
      $feederSystem       = null;

      $Shipment           = (object)[
                              'ShipmentDetails' => $ShipmentDetails,
                              'Shipper'         => $Shipper,
                              'Receiver'        => $Receiver,
                              'ReturnReceiver'  => $returnReceiver,
                              'ExportDocument'  => $ExportDoku,
                              'feederSystem'    => $feederSystem
      ];

      $Version            = (object)[
                              'majorRelease' => $this->major_release,
                              'minorRelease' => $this->minor_release,
                              'build'        => $this->build
      ];

      $ShipmentOrder      = (object)[
                              'sequenceNumber'      => $this->seq_nr,
                              'Shipment'            => $Shipment,
                              'PrintOnlyIfCodeable' => 'active="1"'
      ];

//      $labelResponseType  = 'URL';
      $labelResponseType  = 'none';
      $groupProfileName   = null;
      $labelFormat        = null;
      $labelFormatRetoure = null;
      $combinedPrinting   = null;

      $CreateShipment     = (object)[
                              'Version'            => $Version,
                              'ShipmentOrder'      => $ShipmentOrder,
                              'labelResponseType'  => $labelResponseType,
                              'groupProfileName'   => $groupProfileName,
                              'labelFormat'        => $labelFormat,
                              'labelFormatRetoure' => $labelFormatRetoure,
                              'combinedPrinting'   => $combinedPrinting
      ];

      $call_req           = null;

      try {
         $call_req = $intraship->__soapCall('createShipmentOrder', [$CreateShipment]);

         if (defined('CONF_DHL_DEBUG')) {
            $msg = print_r($call_req, true);
            $fp = fopen(DEBUG_LOG_DIR.'/dhl_log3.txt', 'a+');

            fputs($fp, date('d.m.Y H:i').': '.($this->sandbox ? 'sANDbOX' : 'LIVE').' : Bestellnummer: '.$data->bestellnummer."\n".
                    print_r($CreateShipment, true)."\n".
                    print_r($intraship->__getLastRequestHeaders(), true)."\n".print_r($intraship->__getLastRequest(), true)."\n".
                    print_r($intraship->__getLastResponseHeaders(), true)."\n".print_r($intraship->__getLastResponse(), true)."\n".$msg);
            fclose($fp);
         }
      }

      catch (exception $e) {
         $msg = print_r($e, true);
         $fp = fopen(DEBUG_LOG_DIR.'/dhl_log3_e.txt', 'a+');
         fputs($fp, date('d.m.Y H:i').': '.($this->sandbox ? 'sANDbOX' : 'LIVE').' : Bestellnummer: '.$data->bestellnummer."\n".
                 print_r($login, true)."\n".
                 print_r($intraship->__getLastRequestHeaders(), true)."\n".print_r($intraship->__getLastRequest(), true)."\n".
                 print_r($intraship->__getLastResponseHeaders(), true)."\n".print_r($intraship->__getLastResponse(), true)."\n".$msg);
         fclose($fp);
      }

      $status = $call_req->Status->statusCode;

      // Label erstellt?
      if ($status == 0) {
         $shipment_nr = $call_req->CreationState->shipmentNumber;
         $dhl_url     = $call_req->CreationState->LabelData->labelUrl;

         $this->db->query("UPDATE #__dhl_status SET sendungs_nr = '$shipment_nr', status = 'request_ok', msg = '$dhl_url' WHERE id = $dhl_status_id");

         // Label von DHL als PDF holen
         if ($this->_getLabel($dhl_url, $re_id.'-'.$data->bestellnummer)) {
            $this->db->query("UPDATE #__dhl_status SET status = 'ok', msg = '$dhl_url' WHERE id = $dhl_status_id");
            $this->db->query("UPDATE #__rechnung SET dhl_send_check = 'y', dhl_intraship = '$shipment_nr' WHERE id = $re_id");

            return true;
         }

         $this->db->query("UPDATE #__dhl_status SET status = 'failed', msg = '".json_encode($call_req)."' WHERE id = $dhl_status_id");

         if (defined('CONF_DHL_DEBUG')) {
            $msg = print_r($call_req, true);
            $fp = fopen(DEBUG_LOG_DIR.'/dhl_log3.txt', 'a+');
            fputs($fp, date('d.m.Y H:i').': '.($this->sandbox ? 'SandBox' : 'LIVE').' : Rückmeldung: '."\n".$msg);
            fclose($fp);
         }

         return false;
      }

      // Label abgewiesen
      else {
         $this->db->query("UPDATE #__dhl_status SET status = 'failed', msg = '".json_encode($call_req)."' WHERE id = $dhl_status_id");

         $fp = fopen(SHOP_PATH.'/classes/modules/dhl_haendler/dhl_pdf/'.$re_id.'-'.$data->bestellnummer.'.error.txt', 'w+');
         fputs($fp, str_replace("\n", "\r\n", print_r($intraship->__getLastRequest(), true))."\n".
                    str_replace("\n", "\r\n", print_r($intraship->__getLastResponse(), true)));
         fclose($fp);

         return false;
      }

      return false;
   }

   // Label abholen und speichern
   private function _getLabel($dhl_url, $re_id) {
      if (!is_dir(SHOP_PATH.'/classes/modules/dhl_haendler/dhl_pdf/')) {
         mkdir(SHOP_PATH.'/classes/modules/dhl_haendler/dhl_pdf/');
      }

      if ($this->mode == 'label') {
         $fp = fopen(SHOP_PATH.'/classes/modules/dhl_haendler/dhl_pdf/'.$re_id.'.pdf', 'w+');

         $ch = curl_init($dhl_url);

         curl_setopt($ch, CURLOPT_TIMEOUT, 10);
         curl_setopt($ch, CURLOPT_FILE, $fp);
         $data = curl_exec($ch);
         curl_close($ch);

         fclose($fp);

         return $data;
      }

      else {
         $fp = fopen(SHOP_PATH.'/classes/modules/dhl_haendler/dhl_pdf/'.$re_id.'.link.txt', 'w+');
         fwrite($fp, $dhl_url);
         fclose($fp);

         return true;
      }
   }

   // Absenderdaten aus params->firma generieren
  private function _getShipper() {
      $Country = (object)[
                  'country'        => null,
                  'countryISOCode' => 'DE',
                  'state'          => null
      ];

      $Name    = (object)[
                  'name1' => $this->params->firma['firm_name'],
                  'name2' => null,
                  'name3' => null
      ];

      $Address = (object)[
                  'streetName'             => $this->params->firma['street'],
                  'streetNumber'           => $this->params->firma['haus_nr'],
                  'addressAddition'        => null,
                  'dispatchingInformation' => null,
                  'zip'                    => $this->params->firma['postal_code'],
                  'city'                   => $this->params->firma['city'],
                  'province'               => null,
                  'Origin'                 => $Country
      ];

      $Communication = (object)[
                  'phone'         => $this->params->firma['telefon'],
                  'email'         => $this->params->firma['email'],
                  'contactPerson' => $this->params->firma['first_name'].' '.$this->params->firma['last_name']
      ];

      $Shipper = (object)[
                  'Name' => $Name,
                  'Address' => $Address,
                  'Communication' =>$Communication
      ];

      return $Shipper;
   }

   // Empfängerdaten generieren
   private function _getReceiver($data) {
      $Address       = null;
      $Packstation   = null;
      $Postfiliale   = null;

      $name1 = $data->lf_vorname.' '.$data->lf_nachname;
      $name2 = '';
      $name3 = '';

      if ($data->lf_firma != '') {
         $name2 = $data->lf_firma;
      }

      $Country = (object)[
                  'country'        => null,
                  'countryISOCode' => strtoupper( $data->country_data->domain),
                  'state'          => null
      ];

      // Postnummer vorhanden -> Packstation oder Postfiliale
      if ($data->lf_postnr != null) {
         // Auf Packstation
         if (preg_match('#p.*?k.*?st.*?at.*?n#i', $data->lf_adresse)) {
            $Packstation = (object)[
                              'postNumber'        => $data->lf_postnr,
                              'packstationNumber' => $data->lf_hausnr,
                              'zip'               => $data->lf_plz,
                              'city'              => $data->lf_ort,
                              'province'          => $data->lf_buland,
                              'Origin'            => $Country
            ];
         }

         // Und Postfiliale Testen
         else if (preg_match('#p.*?stf.*?l.*?le#i', $data->lf_adress)) {
            $Postfiliale = (object)[
                              'PostfilialNumber' => $data->lf_hausnr,
                              'PostNumber'       => $data->lf_postnr,
                              'Zip'              => $data->lf_plz,
                              'City'             => $data->lf_ort
            ];
         }
      }

      if ($Packstation == null && $Postfiliale == null) {
         $Address = (object)[
                     'name2'                  => $name2,
                     'name3'                  => $name3,
                     'streetName'             => $data->lf_adresse,
                     'streetNumber'           => $data->lf_hausnr,
                     'addressAddition'        => null,
                     'dispatchingInformation' => null,
                     'zip'                    => $data->lf_plz,
                     'city'                   => $data->lf_ort,
                     'province'               => $data->lf_buland,
                     'Origin'                 => $Country
         ];
      }

      // Übertragung Daten vom Kunden erlaubt
      $Communication = (object) [
                     'phone'         => ($data->ds_gvo_check == 'y' ? $data->telefon : null),
                     'email'         => ($data->ds_gvo_check == 'y' ? $data->email : null),
                     'contactPerson' => $name1
      ];

      $Receiver = (object)[
                     'name1'         => $name1,
                     'Address'       => $Address,
                     'Packstation'   => $Packstation,
                     'Postfiliale'   => $Postfiliale,
                     'Communication' => $Communication
      ];

      return $Receiver;
   }

   // Deetails zu Sendung
   private function _shipmentDetails($data) {
      $gewicht = 0.0;
      $gdata   = $this->db->queryAllObjects("SELECT menge, gewicht FROM #__rechnung_artikel WHERE rechnung_id = ".$data->id);

      // Gesamtgewicht Waren
      for ($i = 0; $i < count($gdata); $i++) {
         $gewicht += (float)$gdata[$i]->menge * (float)$gdata[$i]->gewicht;
      }

      // +Gewicht Verpackung (Gewicht in Kg)
      $gewicht += (float)(Helper::getData('dhl_gewicht', '100') / 1000);

      $today = new \DateTime();

      // Bei service_id != 01 () korrigieren
//      if ($this->service_id != '01') {
//         $region = $data->country_data->region;

         // Deutschland
//         if ((int)$data->lf_staat == 160) {
//            $this->service_id  = '01';
//            $this->dhl_product = 'V01PAK';
//         }

         // EU
//         else if ($region == 'eu') {
//            $this->service_id  = '54';
//            $this->dhl_product = 'V54EPAK';
//         }
//      }

      $dhl_laenge = (isset($_SESSION['dhl_laenge']) && $_SESSION['dhl_laenge'] > 0 ? $_SESSION['dhl_laenge'] : 60);
      $dhl_breite = (isset($_SESSION['dhl_breite']) && $_SESSION['dhl_breite'] > 0 ? $_SESSION['dhl_breite'] : 30);
      $dhl_hoehe  = (isset($_SESSION['dhl_hoehe'])  && $_SESSION['dhl_hoehe'] >  0 ? $_SESSION['dhl_hoehe']  : 30);


      $ShipmentItem                = (object)[
                                             'weightInKG' => $gewicht,
                                             'lengthInCM' => $dhl_laenge,
                                             'widthInCM'  => $dhl_breite,
                                             'heightInCM' => $dhl_hoehe
      ];

      $Service                     = null;

      if (Helper::getData('dhl_versicherung', 'n') == 'y') {
         $Service = (object)['AdditionalInsurance' => 'active="1"'];
      }

      // recipientEmailAddress
      $Notification                = null;
      $BankData                    = null;
      $Product                     = $this->dhl_product;
      $AccountNumber               = $this->dhl_ekp.$this->service_id.$this->partner_id;
      $CustomerReference           = null;
      $ShipmentDate                = $today->format('Y-m-d');
      $costCentre                  = null;
      $ReturnShipmentAccountNumber = null;
      $ReturnShipmentReference     = $data->bestellnummer;

      $shipment_details = (object)[
                           'ShipmentItem'                => $ShipmentItem,
                           'Service'                     => $Service,
                           'Notification'                => $Notification,
                           'BankData'                    => $BankData,
                           'product'                     => $Product,
                           'accountNumber'               => $AccountNumber,
                           'customerReference'           => $CustomerReference,
                           'shipmentDate'                => $ShipmentDate,
                           'costCentre'                  => $costCentre,
                           'returnShipmentAccountNumber' => $ReturnShipmentAccountNumber,
                           'returnShipmentReference'     => $ReturnShipmentReference
      ];
//var_dump($Product, $AccountNumber, $CustomerReference, $ShipmentDate, $ReturnShipmentAccountNumber, $ReturnShipmentReference, $ShipmentItem, $Service, $Notification, $BankData);

      return $shipment_details;
   }

   // Gespeicherte Datein Zippen und ausgeben
   public function dhlzip() {
      $file_path = SHOP_PATH.'/classes/modules/dhl_haendler/dhl_pdf';
      $files = [];

      // Datei-namen lesen
      if (is_dir($file_path)) {
         if ($handle = opendir($file_path)) {
            while (false !== ($file = readdir($handle))) {
               if ($file != "." && $file != ".." && $file != '.htaccess') {
                  if (is_file($file_path.'/'.$file)) {
                     $files[] = $file_path.'/'.$file;
                  }
               }
            }
            closedir($handle);
         }

         if (count($files) > 0) {
            $zip_name = 'Label_'.date('Y-m-d_H-m').'.zip';
            $zipfile = SHOP_PATH.'/tmp/'.$zip_name;
            $zip = new \ZipArchive;

            if ($zip->open($zipfile, \ZIPARCHIVE::CREATE) === true) {
               foreach ($files as $z) {
                  $zip->addFile($z, 'Label_'.date('Y-m-d_H-m').'/'.basename($z));
               }
               $zip->close();

               header('Content-Type: application/zip');
               header('Content-disposition: attachment; filename='.$zip_name);
               header('Content-Length: ' . filesize($zipfile));
               readfile($zipfile);

               unlink($zipfile);
               foreach ($files as $z) {
                  unlink($z);
               }
               exit();
            }
         }
      }
   }

   public function dhlabort() {
      @unlink(SHOP_PATH.'/tmp/dhl_status.txt');
      return;
   }

   public function labelstatus() {
      if (file_exists(SHOP_PATH.'/tmp/dhl_status.txt')) {
         $tmp = file_get_contents(SHOP_PATH.'/tmp/dhl_status.txt');

         if (strpos($tmp, 'ENDE') !== false) {
            exit(json_encode(['status' => 'end', 'msg' => str_replace('ENDE', '', $tmp)]));
         }

         else {
            exit(json_encode(['status' => 'ok', 'msg' => $tmp]));
         }
      }

      exit(json_encode(['status' => 'failed', 'msg' => 'Labelerstellung wurde manuell abgebrochen']));
   }
}
