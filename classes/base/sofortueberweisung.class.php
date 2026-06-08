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

class KANPAICLASSIC_sofort {
   private $params;
   private $user;

   // SofortUeberweisung: zahlart = 7
   function __construct() {
      $this->params = Control::getParams();
      $this->user   = Control::getUser();
   }

   // URL für Redirect auf Bezahlseite holen
   public function bezahlen ($preis, $best_nr, $mail, $waehrung = 'EUR') {
      $api_key = $this->params->firma['sofort_key'];
      $Sofort  = Control::getSofortLib($api_key);

      $Sofort->setSofortueberweisung();
      $Sofort->setVersion('obadja_v1.0');
      // $Sofort->setVersion('KANPAICLASSIC_v1.0');
      $Sofort->setAmount($preis, $waehrung);
      $Sofort->setReason($_SESSION['bestellnummer'], $this->user->user['nachname'].', '.$this->user->user['vorname']);
      $Sofort->setSuccessUrl(SHOP_URL_IDX.'/sofortueberweisung_ok');
      $Sofort->setAbortUrl(SHOP_URL_IDX.'/sofortueberweisung_fail');
      $Sofort->setTimeoutUrl(SHOP_URL_IDX.'/sofortueberweisung_fail');
      $Sofort->setNotificationUrl(SHOP_URL_IDX.'/sofortueberweisung_notify');
      $Sofort->setNotificationEmail($this->params->firma['email']);
      $Sofort->addUserVariable($_SESSION['bestellnummer']);
      $Sofort->sendRequest();

      if ($Sofort->isError()) {
         $fp = fopen(SHOP_PATH.'/sofort_start_fail.txt', 'a');
         fwrite($fp, date('d.m.Y H:i:s').' a- '.print_r($Sofort->getError(), true).CR.$preis.CR.$best_nr.CR.$mail.CR.$waehrung.CR);
         fclose($fp);

         //PNAG-API didn't accept the data
         //echo $Sofort->getError(); exit;
         header('Location: '.SHOP_URL_IDX.'/sofort_error');
         exit;
      }

      else {
         if (defined('CONF_SOFORT_DEBUG')) {
            $fp = fopen(SHOP_PATH.'/sofort_start_ok.txt', 'a');
            fwrite($fp, date('d.m.Y H:i:s').' a- '.print_r($Sofort, true).CR.$preis.CR.$best_nr.CR.$mail.CR.$waehrung.CR);
            fclose($fp);
         }
         $paymentUrl = $Sofort->getPaymentUrl();
         header('Location: '.$paymentUrl);
         // KEIN EXIT!!!
      }
   }
}
