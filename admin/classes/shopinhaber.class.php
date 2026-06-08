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

define('PASSWORD_CHANGED', 'Achtung: Passwort wurde geändert!');
define('MAIL_CHANGED',  'Achtung: Email-Adresse wurde geändert!');
define('LOGIN_CHANGED',  'Achtung: Login wurde geändert!');

if (!defined('KANPAICLASSIC')) {
   die ("This file cannot run outside the Kanpai Classic Shopsoftware");
}

class KANPAICLASSIC_shopinhaber
{
   private $db;
   private $params;
   private $text;
   private $shop;

   public function __construct() {
      $this->db = Control::getDB();
      $this->params = Control::getParams();
      $this->text = Control::getText();
   }


   public function getContent() {
      $statistik_umsatz     = '';
      $year_select          = '';
      $clicks_year          = '';
      $bestseller_article   = '';
      $bestseller_categorie = '';

      if ($this->params->func == 'update') {
         $this->_writeShop();
         header('Location: '.ADMIN_URL_IDX.'/shopinhaber');
         exit;
      }

      if ($this->params->func == 'popupSmtp') {
         $this->popup();
         exit;
      }

      if ($this->params->func == 'popupSmtpSave') {
         $this->popupSave();
         exit;
      }

      // Modul Statistik - Umsatz-Statistik Jahr geändert
      // 05.04.2019
      else if ($this->params->func == 'statistikChanged') {
         Helper::setData('statistik_mode', $this->params->postString('mode'));

         $mod_statistik = Control::getModuleStatistik();
         $year          = $this->params->postInt('year');
         $statistik     = $mod_statistik->statisticUmsatz($year);

         header('Content-Type: application/json');
         echo json_encode(['status' => 'ok', 'statistik' => $statistik, 'aktuell' => $year, 'last' => ($year - 1)]);
         exit;
      }

      else if ($this->params->func == 'statisticActive') {
         $use_statistic = $this->params->postCheckbox('use_statistic');

         Helper::setData('use_statistic', $use_statistic);

         exit(json_encode(['status' => 'ok', 'use_statisti' => $use_statistic]));

      }

      // Modul Statistik - Monatliche Klicks Jahr geändert oder Klicks/User umgeschaltet
      // 05.04.2019
      else if ($this->params->func == 'statistikClicks') {
         Helper::setData('statistik_mode', $this->params->postString('mode'));

         $mod_statistik = Control::getModuleStatistik();
         $year          = $this->params->postInt('year');
         $statistik     = $mod_statistik->statisticClicks($year);

         header('Content-Type: application/json');
         echo json_encode(['status' => 'ok', 'statistik' => $statistik, 'aktuell' => $year, 'last' => ($year - 1)]);
         exit;
      }

      // Modul Statistik - Statistik (Artikel- / Kategorien-Clicks) löschen
      else if ($this->params->func == 'deleteStatistik') {
         $mod_statistik = Control::getModuleStatistik();
         $mod_statistik->statisticDelete();

         header('Location: '.ADMIN_URL_IDX.'/shopinhaber');
         exit;
      }

      // Login - Passwort vergessen
      // 05.04.2019
      else if ($this->params->func == 'forgotten') {
         $email        = $this->params->firma['email'];
         $forgotten_db = md5('OBADJA'.time());
         $forgotten_link = SHOP_URL_IDX.'/validateadmin/'.$forgotten_db;

         // forgotten eintragen, zur Identifizierung
         $this->db->query("UPDATE #__users SET forgotten = '$forgotten_db', modified = '".date('Y-m-d H:i:m')."' WHERE role = 0");

         $mail = Control::getMail();
         $mail->sendForgotten($email, $forgotten_link);

         header('Content-Type: application/json');
         echo json_encode(['status' => 'ok', 'msg' => 'Es wurde an '.$email.' eine E-Mail verschickt.']);
         exit;
      }

      else {
         if (defined('CONF_MODULE_STATISTIK')) {
            $mod_statistik         = Control::getModuleStatistik();

            $statistik_umsatz      = $mod_statistik->statisticUmsatz();
            $year_select           = $mod_statistik->year_select;
            $statistik_clicks      = $mod_statistik->statisticClicks();
            $clicks_year           = $mod_statistik->clicks_year;
            $year_select_clicks    = $mod_statistik->year_select_clicks;
            $bestseller_article    = $mod_statistik->bestsellerArticle();
            $bestseller_categories = $mod_statistik->bestsellerCategorie();
            $user_klicks           = $mod_statistik->user_klicks;

            $this->getShop();
            include 'templates/shopinhaber.tpl.php';
         }

         else {
            $this->getShop();
            include 'templates/shopinhaber.tpl.php';
         }
      }

   }


   // Daten lesen
   private function getShop() {
      /*
      $sql = " SELECT `shop_name`, `shop_name_check`, `firm_name`, `firm_name_check`,
            `first_name`, `first_name_check`, `last_name`, `last_name_check`,
            `street`, haus_nr, `street_check`, `postal_code`, `postal_code_check`,
            `city`, `city_check`, `country`, `country_check`, `email`, `email_check`,
            `mailfrom`, `mailfrom_check`, `telefon`, `telefon_check`,
            `fax`, `fax_check`, `email2`, `email2_check`, `web`, `web_check`,
            `finanzamt`, `finanzamt_check`, `steuernr`, `steuernr_check`,
            `ustid`, `ustid_check`, `paypal_mail`, `paypal_mail_check`,
            `bank1`, `bank1_check`, `bank1_inhaber`, `bank1_inhaber_check`,
            `bank1_iban`, `bank1_iban_check`, `bank1_bic`, `bank1_bic_check`
            FROM `#__firma`
            WHERE `id` = 1";

      $this->db->query($sql);
      $this->shop = $this->db->getObject();
       *
       */
      $this->shop = (object)$this->params->firma;
   }

   // Daten Shopinhaber speichern speichern
   private function _writeShop() {
      $olduser = $this->db->querySingleObject("SELECT email, password FROM #__users WHERE role = 0");

      $sql = "UPDATE `#__firma` SET
               `shop_name`          = '" . $this->params->postString('shop_name') . "',
               `shop_name_check`    = '" . $this->params->postCheckbox('shop_name_check') . "',
               `firm_name`          = '" . $this->params->postString('firm_name') . "',
               `firm_name_check`    = '" . $this->params->postCheckbox('firm_name_check') . "',
               `first_name`         = '" . $this->params->postString('first_name') . "',
               `first_name_check`   = '" . $this->params->postCheckbox('first_name_check') . "',
               `last_name`          = '" . $this->params->postString('last_name') . "',
               `last_name_check`    = '" . $this->params->postCheckbox('last_name_check') . "',
               `street`             = '" . $this->params->postString('street') . "',
               `haus_nr`            = '" . $this->params->postString('haus_nr') . "',
               `street_check`       = '" . $this->params->postCheckbox('street_check') . "',
               `postal_code`        = '" . $this->params->postString('postal_code') . "',
               `postal_code_check`  = '" . $this->params->postCheckbox('postal_code_check') . "',
               `city`               = '" . $this->params->postString('city') . "',
               `city_check`         = '" . $this->params->postCheckbox('city_check') . "',
               `country`            = '" . $this->params->postString('country') . "',
               `country_check`      = '" . $this->params->postCheckbox('country_check') . "',
               `email`              = '" . $this->params->postString('email') . "',
               `email_check`        = '" . $this->params->postCheckbox('email_check') . "',
               `mailfrom`           = '" . $this->params->postString('mailfrom') . "',
               `mailfrom_check`     = '" . $this->params->postCheckbox('mailfrom_check') . "',
               `telefon`            = '" . $this->params->postString('telefon') . "',
               `telefon_check`      = '" . $this->params->postCheckbox('telefon_check') . "',
               `fax`                = '" . $this->params->postString('fax') . "',
               `fax_check`          = '" . $this->params->postCheckbox('fax_check') . "',
               `email2`             = '" . $this->params->postString('email2') . "',
               `email2_check`       = '" . $this->params->postCheckbox('email2_check') . "',
               `web`                = '" . $this->params->postString('web') . "',
               `web_check`          = '" . $this->params->postCheckbox('web_check') . "',
               `finanzamt`          = '" . $this->params->postString('finanzamt') . "',
               `finanzamt_check`    = '" . $this->params->postCheckbox('finanzamt_check') . "',
               `steuernr`           = '" . $this->params->postString('steuernr') . "',
               `steuernr_check`     = '" . $this->params->postCheckbox('steuernr_check') . "',
               `ustid`              = '" . $this->params->postString('ustid') . "',
               `ustid_check`        = '" . $this->params->postCheckbox('ustid_check') . "',
               `paypal_mail`        = '" . $this->params->postString('paypal_mail') . "',
               `paypal_mail_check`  = '" . $this->params->postCheckbox('paypal_mail_check') . "',
               `bank1`              = '" . $this->params->postString('bank1') . "',
               `bank1_check`        = '" . $this->params->postCheckbox('bank1_check') . "',
               `bank1_inhaber`      = '" . $this->params->postString('bank1_inhaber') . "',
               `bank1_inhaber_check`= '" . $this->params->postCheckbox('bank1_inhaber_check') . "',
               `bank1_iban`         = '" . $this->params->postString('bank1_iban') . "',
               `bank1_iban_check`   = '" . $this->params->postCheckbox('bank1_iban_check') . "',
               `bank1_bic`          = '" . $this->params->postString('bank1_bic') . "',
               `bank1_bic_check`    = '" . $this->params->postCheckbox('bank1_bic_check') . "'
            WHERE `id` = 1";

      $this->db->query($sql);

      Helper::setData('shop_frei1_titel', $this->params->postString('shop_frei1_titel'));
      Helper::setData('shop_frei1_check', $this->params->postCheckbox('shop_frei1_check'));
      Helper::setData('shop_frei1_text',  $this->params->postString('shop_frei1_text'));
      Helper::setData('shop_frei2_titel', $this->params->postString('shop_frei2_titel'));
      Helper::setData('shop_frei2_check', $this->params->postCheckbox('shop_frei2_check'));
      Helper::setData('shop_frei2_text',  $this->params->postString('shop_frei2_text'));
      Helper::setData('shop_frei3_titel', $this->params->postString('shop_frei3_titel'));
      Helper::setData('shop_frei3_check', $this->params->postCheckbox('shop_frei3_check'));
      Helper::setData('shop_frei3_text',  $this->params->postString('shop_frei3_text'));

      $user    = $this->params->postString('user');
      $user_id = $_SESSION['user_id'];
      $pass1   = $this->params->postString('password');
      $pass2   = $this->params->postString('password2');

      if ($pass1 != $pass2) {
         $this->message = '<span class="txt_bez txt_red">'.$this->text->get('shop_ih', 'errpass', 'lang').'</span>';
         return;
      }

      if ($user && $user!= $_SESSION['user_name']) {
         // Prüfen, dass username nicht vorhanden ist
         $sql = "SELECT id FROM #__users WHERE name = '$user'";
         $anzahl = $this->db->query($sql);

         if ($anzahl != 0) {
            $this->message = '<span class="txt_bez txt_red">'.$this->text->get('shop_ih', 'erruser', 'lang').'</span>';
            return;
         }

         else {
            $_SESSION['user_name'] = $user;
            $sql = "UPDATE #__users SET name = '$user' WHERE id = $user_id";
            $this->db->query($sql);

            Helper::shopLog('shopinhaber', LOGIN_CHANGED, $_SERVER['REMOTE_ADDR']);
            $mail = Control::getMail();
            $mail->sendNachricht($this->params->firma['email'], LOGIN_CHANGED);
         }
      }

      // Passwort geändert
      if ($pass1) {
         $sql = "UPDATE #__users SET password = '" . md5($pass1) . "' WHERE id = $user_id";
         $this->db->query($sql);

         Helper::shopLog('shopinhaber', PASSWORD_CHANGED, $_SERVER['REMOTE_ADDR']);
         $mail = Control::getMail();
         $mail->sendNachricht($this->params->firma['email'], PASSWORD_CHANGED);
      }

      // Shop-Email geändert
      if ($this->params->firma['email'] != $this->params->postString('email')) {
         Helper::shopLog('shopinhaber', MAIL_CHANGED, $_SERVER['REMOTE_ADDR']);
         $mail = Control::getMail();
         $mail->sendNachricht($this->params->firma['email'], MAIL_CHANGED);
         $mail->sendNachricht($this->params->postString('email'), MAIL_CHANGED);
      }

      $this->params->getFirmData();
   }

   private function popup() {
      $smtp_email  = $this->params->firma['email'];
      $smtp_check  = Helper::getData('smtp_check', 'n');
      $smtp_user   = Helper::getData('smtp_user', '');
      $smtp_pass   = Helper::getData('smtp_pass', '');
      $smtp_server = Helper::getData('smtp_server', '');
      $smtp_port   = Helper::getData('smtp_port', '');
      $html        = '';

      include_once ADMIN_PATH.'/templates/popup_smtp.tpl.php';

      //header('Content-Type: application/json');
      exit(json_encode(['status' => 'ok', 'html' => $html]));
   }

   private function popupSave() {
      $smtp_email  = $this->params->postString('smtp_email');
      $this->db->query("UPDATE #__firma SET email = '$smtp_email'");

      Helper::setData('smtp_check', $this->params->postCheckbox('smtp_check'));
      Helper::setData('smtp_user', $this->params->postString('smtp_user'));
      Helper::setData('smtp_pass', $this->params->postString('smtp_pass'));
      Helper::setData('smtp_server', $this->params->postString('smtp_server'));
      Helper::setData('smtp_port', $this->params->postString('smtp_port'));

      exit(json_encode(['status' => 'ok']));
   }
}
