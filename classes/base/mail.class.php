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

// Gemeinsames Vorgehen für alle Mails:
// Außer der Empfängeradresse köönen noch optionale Werte übergeben werden,
// die bei der Auswertung benötigt werden.
// Für den Mailbody wird zuerst zuerst der Text aus der DB gelesen und danach
// alle  [...] durch die werte ersetzt

class KANPAICLASSIC_mail
{
   private $mail           = null;
   private $params         = null;
   private $db             = null;
   private $text           = null;
   private $link           = '';
   private $best_id        = 0;
   private $bestellung     = null;
   private $user           = null;
   private $gutschein_code = '';
   private $gutschein_wert = '';
   private $widerruf_id;
   public  $lang           = '';
   private $sendAdmin      = false;
   private $sendAdminMsg   = false;
   private $sendKundeMsg   = false;
   public  $siegel_ref     = '';

   public function __construct() {
      $this->params = Control::getParams();
      $this->mail = Control::getPhpMailer();
      $this->text = Control::getText();
      $this->db = Control::getDB();
      $this->lang = $this->params->selected_lang;
   }

   public function sendMail($from, $to, $subject, $html, $link = '', $user = 0, $function = '') {
      $this->link = $link;
      $this->user = $user;

      $this->mail->ClearAddresses();
      $this->mail->ClearAttachments();
      $this->mail->CharSet = 'UTF-8';
      $this->mail->AddAddress($email);

      if ($from == 'admin') {
         $this->mail->SetFrom($this->params->firma['email'], $this->params->firma['mailfrom']);
      }

      else {
         $this->mail->SetFrom($from);
      }

      $this->mail->Subject = $subject;
      $this->mail->MsgHTML($html);

      $sendmail = $this->_sendMail($function);

      if($sendmail != '') {
         // defined('MAIL_DEBUG') && file_put_contents(SHOP_PATH.'/classes/modules/xdebug/log/mail_error', 'sendMail: '.$sendmail, FILE_APPEND);
         return false;
      }

      return true;
   }

   // Verifizierungslink $link an $email senden
   public function sendAnmeldung($email, $link, $user) {
      $this->link = $link;
      $this->user = $user;

      $this->mail->ClearAddresses();
      $this->mail->ClearAttachments();
      $this->mail->CharSet = 'UTF-8';
      $this->mail->AddAddress($email);
      $this->mail->SetFrom($this->params->firma['email'], $this->params->firma['mailfrom']);
      $this->mail->Subject = $this->getSubject('anmeldung');
      $this->mail->MsgHTML($this->getBody($this->getBodyText('anmeldung')));

      $sendmail = $this->_sendMail('sendAnmeldung');

      if($sendmail != '') {
         // defined('MAIL_DEBUG') && file_put_contents(SHOP_PATH.'/classes/modules/xdebug/log/mail_error', 'sendMail: '.$sendmail, FILE_APPEND);
         return false;
      }

      return true;
   }

   // Mail an Admin Neuer Kunde bei manueller Freigabe
   public function sendAnmeldungKunde() {
      $this->mail->ClearAddresses();
      $this->mail->ClearAttachments();
      $this->mail->CharSet = 'UTF-8';
      $this->mail->AddAddress($this->params->firma['email']);
      $this->mail->SetFrom($this->params->firma['email'], $this->params->firma['mailfrom']);
      $this->mail->Subject = str_ireplace('[Shopname]',$this->params->firma['shop_name'], $this->text->get('adm_mail', 'neuk_subj'));
      $this->mail->MsgHTML($this->getBody('<p>'.$this->text->get('adm_mail', 'neuk_body').'</p>'));

      $sendmail = $this->_sendMail('sendAnmeldungKunde');

      if($sendmail != '') {
         //defined('MAIL_DEBUG') && file_put_contents(SHOP_PATH.'/classes/modules/xdebug/log/mail_error', 'sendMail: '.$sendmail, FILE_APPEND);
         return false;
      }

      return true;
   }

   // Mail an Kunde/Händler - Account freigeschaltet
   public function sendFreigeschaltet($user_id) {
      $data = $this->db->querySingleObject("SELECT email, lang FROM #__users WHERE id = $user_id");
      if ($data) {
         $this->user = $user_id;
         $this->mail->ClearAddresses();
         $this->mail->ClearAttachments();
         $this->mail->CharSet = 'UTF-8';
         $this->mail->AddAddress($data->email);
         $this->mail->SetFrom($this->params->firma['email'], $this->params->firma['mailfrom']);
         $this->mail->Subject = str_ireplace('[Shopname]',$this->params->firma['shop_name'], $this->text->get('adm_mail', 'frei_subj', $data->lang));
         $this->mail->MsgHTML($this->getBody('<p>'.$this->text->get('adm_mail', 'frei_body', $data->lang).'</p>'));

         $this->_sendMail('sendFreigeschaltet');

         if(!$this->mail->Send()) {
            // defined('MAIL_DEBUG') && file_put_contents($SHOP_PATH.'/classes/modules/xdebug/log/mail_error', 'sendFreigeschaltet: '.$this->mail->ErrorInfo, FILE_APPEND);
            return false;
         }
         return true;
      }
      return false;
   }

   // Verifizierungslink für Newsletteranmeldung $link an $email senden
   public function sendAnmeldungNL($email, $link) {
      $this->link = $link;
//      $this->user = $user;

      $this->mail->ClearAddresses();
      $this->mail->ClearAttachments();
      $this->mail->CharSet = 'UTF-8';
      $this->mail->AddAddress($email);
      $this->mail->SetFrom($this->params->firma['email'], $this->params->firma['mailfrom']);
      $this->mail->Subject = $this->getSubject('anmeldung_nl');
      $this->mail->MsgHTML($this->getBody($this->getBodyText('anmeldung_nl')));

      $sendmail = $this->_sendMail('sendAnmeldungNL');

      if($sendmail != '') {
         // defined('MAIL_DEBUG') && file_put_contents(SHOP_PATH.'/classes/modules/xdebug/log/mail_error', 'sendMail: '.$sendmail, FILE_APPEND);
         return false;
      }

      return true;
   }

   // Verifizierungslink $link an $email senden
   public function sendForgotten ($email, $link) {
      // User auf 1 Setzen, um keine Bestelldaten einzulesen bei getBodyText
      $this->user = 1;
      $this->link = $link;

      $this->mail->ClearAddresses();
      $this->mail->ClearAttachments();
      $this->mail->CharSet = 'UTF-8';
      $this->mail->AddAddress($email);
      $this->mail->SetFrom($this->params->firma['email'], $this->params->firma['mailfrom']);
      $this->mail->Subject = $this->getSubject('pw_vergessen');
      $this->mail->MsgHTML($this->getBody($this->getBodyText('pw_vergessen'), false));

      $sendmail = $this->_sendMail(sendForgotten);

      if($sendmail != '') {
         // defined('MAIL_DEBUG') && file_put_contents(SHOP_PATH.'/classes/modules/xdebug/log/mail_error', 'sendMail: '.$sendmail, FILE_APPEND);
         return false;
      }

      return true;
   }

   // Nachricht an Kunde senden - hier erfolgt keine Ersetzung
   public function sendNachricht($email, $nachricht) {
      $this->mail->ClearAddresses();
      $this->mail->ClearAttachments();
      $this->mail->CharSet = 'UTF-8';
      $this->mail->SetFrom($this->params->firma['email'], $this->params->firma['mailfrom']);
      $this->mail->AddAddress($email);
      $this->mail->Subject    = "Info";
      $this->mail->AltBody    = $nachricht;
      $this->mail->MsgHTML(str_replace('\n', '<br />', $nachricht));

      $sendmail = $this->_sendMail('sendNachricht');

      if($sendmail != '') {
         // defined('MAIL_DEBUG') && file_put_contents(SHOP_PATH.'/classes/modules/xdebug/log/mail_error', 'sendMail: '.$sendmail, FILE_APPEND);
         return false;
      }

      return true;
   }

   // Bestätigung Anfrage an Kunde - mit Bestelldaten und Anhängen
   public function sendAnfrage($email, $best_id) {
      $this->best_id      = $best_id;
      $this->widerruf_id  = (int)$_SESSION['widerruf_wk'];
      $this->sendKundeMsg = true;

      $this->mail->ClearAddresses();
      $this->mail->ClearAttachments();
      $this->mail->CharSet = 'UTF-8';
      $this->mail->AddAddress($email);
      $this->mail->SetFrom($this->params->firma['email'], $this->params->firma['mailfrom']);
      $this->mail->Subject = $this->getSubject('anfrage_best');
      $this->mail->MsgHTML($this->getBody($this->getBodyText('anfrage_best'), true));

      $lang = $this->params->selected_lang;

      // Widerruf-Pdf anhängen
      if ($this->params->firma['b2b_check'] != 'y') {
         if (!defined('CONF_MODULE_PORTAL')) {
            // Shop
            if ((int)$_SESSION['widerruf_wk'] == 1) {
               $this->mail->AddAttachment(SHOP_PATH.'/classes/pdf/WiderrufA_'.$lang.'.pdf', $this->text->get('widerruf', 'belehrung').'.pdf', 'base64', 'application/pdf');
            }

            if ((int)$_SESSION['widerruf_wk'] == 2) {
               $this->mail->AddAttachment(SHOP_PATH.'/classes/pdf/WiderrufB_'.$lang.'.pdf', $this->text->get('widerruf', 'belehrung').'.pdf', 'base64', 'application/pdf');
            }

            if ((int)$_SESSION['widerruf_wk'] == 3) {
               $this->mail->AddAttachment(SHOP_PATH.'/classes/pdf/WiderrufC_'.$lang.'.pdf', $this->text->get('widerruf', 'belehrung').'.pdf', 'base64', 'application/pdf');
            }

            if ((int)$_SESSION['widerruf_wk'] == 4) {
               $this->mail->AddAttachment(SHOP_PATH.'/classes/pdf/WiderrufD_'.$lang.'.pdf', $this->text->get('widerruf', 'belehrung').'.pdf', 'base64', 'application/pdf');
            }

            if ((int)$_SESSION['widerruf_wk'] == 5) {
               $this->mail->AddAttachment(SHOP_PATH.'/classes/pdf/WiderrufE_'.$lang.'.pdf', $this->text->get('widerruf', 'belehrung').'.pdf', 'base64', 'application/pdf');
            }

            if (is_file(SHOP_PATH.'/classes/pdf/agb_'.$lang.'.pdf')) {
               $this->mail->AddAttachment(SHOP_PATH.'/classes/pdf/agb_'.$lang.'.pdf', $this->text->get('menu', 'agb').'.pdf', 'base64', 'application/pdf');
            }

            if (is_file(SHOP_PATH.'/classes/pdf/versand_'.$lang.'.pdf')) {
               $this->mail->AddAttachment(SHOP_PATH.'/classes/pdf/versand_'.$lang.'.pdf', $this->text->get('menu', 'versand').'.pdf', 'base64', 'application/pdf');
            }
         }

         // Portal
         else {
            $haendler_id = $this->db->querySingleValue("SELECT haendler_id FROM #__rechnung WHERE id = $best_id");
            $haendler_nr = Helper::getHaendlerNrByUserId($haendler_id);
            $this->mail->AddAttachment(SHOP_PATH.'/classes/pdf/'.$haendler_nr.'_Widerruf_'.$lang.'.pdf', $haendler_nr.'_'.$this->text->get('widerruf', 'belehrung').'.pdf', 'base64', 'application/pdf');

            if (is_file(SHOP_PATH.'/classes/pdf/'.$haendler_nr.'_agb_'.$lang.'.pdf')) {
               $this->mail->AddAttachment(SHOP_PATH.'/classes/pdf/'.$haendler_nr.'_agb_'.$lang.'.pdf', $haendler_nr.'_'.$this->text->get('menu', 'agb').'.pdf', 'base64', 'application/pdf');
            }

            if (is_file(SHOP_PATH.'/classes/pdf/'.$haendler_nr.'_versand_'.$lang.'.pdf')) {
               $this->mail->AddAttachment(SHOP_PATH.'/classes/pdf/'.$haendler_nr.'_versand_'.$lang.'.pdf', $haendler_nr.'_'.$this->text->get('menu', 'versand').'.pdf', 'base64', 'application/pdf');
            }
         }
      }

      // Naehwerte als Anhang
      if (defined('CONF_MODULE_NAEHRWERTE') && !empty($this->bestellung->nw_zutaten)) {
         $nw_pdf = Control::getNaehrwertePdf();
         $this->mail->AddStringAttachment($nw_pdf->makePdf($this->bestellung->nw_zutaten), 'Naehrwerte.pdf', 'base64', 'application/pdf');
      }

      $sendmail = $this->_sendMail('sendAnfrage');

      if($sendmail != '') {
         // defined('MAIL_DEBUG') && file_put_contents(SHOP_PATH.'/classes/modules/xdebug/log/mail_error', 'sendMail: '.$sendmail, FILE_APPEND);
         return false;
      }

      return true;
   }

   // Bestellbestätigung an Kunden - mit Bestelldaten und Kontodaten
   // Bei Automatischer Rechnung: Rechnung-PDF anhängen.
   public function sendBestellung($email, $best_id, $send_pdf = false) {
      $this->best_id      = $best_id;
      $this->sendAdminMsg = true;
      $this->sendKundeMsg = true;
      $widerruf_id        = 1;
      $anhang             = '';

      $data              = $this->db->querySingleObject("SELECT widerruf, lang_kunde FROM #__rechnung WHERE id = $best_id");
      $widerruf_id       = $data->widerruf;
      $this->lang        = $data->lang_kunde;
      $this->widerruf_id = $widerruf_id;

      $this->mail->ClearAddresses();
      $this->mail->ClearAttachments();
      $this->mail->CharSet = 'UTF-8';
      $this->mail->AddAddress($email);
      $this->mail->SetFrom($this->params->firma['email'], $this->params->firma['mailfrom']);
      $this->mail->Subject = $this->getSubject('best_best');
      $this->mail->MsgHTML($this->getBody($this->getBodyText('best_best'), true));

      $lang = $this->params->selected_lang;

      if ($send_pdf) {
         $pdf    = Control::getPdf();
         $anhang = $pdf->makePdf ($best_id, 'rechnung', 'F', 'kunde');
//         $this->mail->AddAttachment($anhang);
      }

      // Widerruf-Pdf anhängen, falls nicht B2B
      if (!defined('CONF_MODULE_PORTAL')) {
         if ($this->params->firma['b2b_check'] != 'y') {
            if ($widerruf_id == 1) {
               $this->mail->AddAttachment(SHOP_PATH.'/classes/pdf/WiderrufA_'.$lang.'.pdf', $this->text->get('widerruf', 'belehrung').'.pdf', 'base64', 'application/pdf');
            }

            if ($widerruf_id == 2) {
               $this->mail->AddAttachment(SHOP_PATH.'/classes/pdf/WiderrufB_'.$lang.'.pdf', $this->text->get('widerruf', 'belehrung').'.pdf', 'base64', 'application/pdf');
            }

            if ($widerruf_id == 3) {
               $this->mail->AddAttachment(SHOP_PATH.'/classes/pdf/WiderrufC_'.$lang.'.pdf', $this->text->get('widerruf', 'belehrung').'.pdf', 'base64', 'application/pdf');
            }

            if ($widerruf_id == 4) {
               $this->mail->AddAttachment(SHOP_PATH.'/classes/pdf/WiderrufD_'.$lang.'.pdf', $this->text->get('widerruf', 'belehrung').'.pdf', 'base64', 'application/pdf');
            }
         }

         if (is_file(SHOP_PATH.'/classes/pdf/agb_'.$lang.'.pdf')) {
            $this->mail->AddAttachment(SHOP_PATH.'/classes/pdf/agb_'.$lang.'.pdf', $this->text->get('menu', 'agb').'.pdf', 'base64', 'application/pdf');
         }

         if (is_file(SHOP_PATH.'/classes/pdf/versand_'.$lang.'.pdf')) {
            $this->mail->AddAttachment(SHOP_PATH.'/classes/pdf/versand_'.$lang.'.pdf', $this->text->get('menu', 'versand').'.pdf', 'base64', 'application/pdf');
         }
      }

      // Portal
      else {
         if ($this->params->firma['b2b_check'] != 'y') {
            $haendler_id = $this->db->querySingleValue("SELECT haendler_id FROM #__rechnung WHERE id = $best_id");
            $haendler_nr = Helper::getHaendlerNrByUserId($haendler_id);
            $this->mail->AddAttachment(SHOP_PATH.'/classes/pdf/'.$haendler_nr.'_Widerruf_'.$lang.'.pdf', $haendler_nr.'_'.$this->text->get('widerruf', 'belehrung').'.pdf', 'base64', 'application/pdf');
         }

         if (is_file(SHOP_PATH.'/classes/pdf/'.$haendler_nr.'_agb_'.$lang.'.pdf')) {
            $this->mail->AddAttachment(SHOP_PATH.'/classes/pdf/'.$haendler_nr.'_agb_'.$lang.'.pdf', $haendler_nr.'_'.$this->text->get('menu', 'agb').'.pdf', 'base64', 'application/pdf');
         }

         if (is_file(SHOP_PATH.'/classes/pdf/'.$haendler_nr.'_versand_'.$lang.'.pdf')) {
            $this->mail->AddAttachment(SHOP_PATH.'/classes/pdf/'.$haendler_nr.'_versand_'.$lang.'.pdf', $haendler_nr.'_'.$this->text->get('menu', 'versand').'.pdf', 'base64', 'application/pdf');
         }
      }

      $sendmail = $this->_sendMail('sendBestellung');

      if($sendmail != '') {
         // defined('MAIL_DEBUG') && file_put_contents(SHOP_PATH.'/classes/modules/xdebug/log/mail_error', 'sendMail: '.$sendmail, FILE_APPEND);
         return false;
      }

      return true;
   }

   // Rechnung als Anhang an Kunden senden
   public function sendRechnung($email, $best_id) {
      $bestellung = Control::getBestellung();
      $bestellung->getDetailBestellung($best_id);
      $lang      = $bestellung->dataDetails->lang_kunde;
      $collector = $bestellung->dataDetails->collector;

      $pdf       = null;
      $anhang    = '';
      $send_pdf  = false;

      if ($bestellung->dataDetails->pdf == 'r') {
         $send_pdf = true;
      }

      if ($send_pdf) {
         if ($collector == 'n') {
            $pdf = Control::getPdf();
         }

         else {
            $pdf = Control::getPdfCollector();
         }

         $anhang = $pdf->makePdf ($best_id, 'rechnung', 'F', 'kunde');
      }

      $this->best_id = $best_id;

      $this->mail->ClearAddresses();
      $this->mail->ClearAttachments();
      $this->mail->CharSet = 'UTF-8';
      $this->mail->AddAddress($email);
      $this->mail->SetFrom($this->params->firma['email'], $this->params->firma['mailfrom']);
      $this->mail->Subject = $this->getSubject('rechnung');

      if ($send_pdf) {
         $this->mail->AddAttachment($anhang);

         // AGB als Anhang
         if (!defined('CONF_MODULE_PORTAL')) {
            if (is_file(SHOP_PATH.'/classes/pdf/agb_'.$lang.'.pdf')) {
               $this->mail->AddAttachment(SHOP_PATH.'/classes/pdf/agb_'.$lang.'.pdf', $this->text->get('menu', 'agb').'.pdf', 'base64', 'application/pdf');
            }
         }
         // Portal
         else {
            $haendler_id = $this->db->querySingleValue("SELECT haendler_id FROM #__rechnung WHERE id = $best_id");
            $haendler_nr = Helper::getHaendlerNrByUserId($haendler_id);

            if (is_file(SHOP_PATH.'/classes/pdf/'.$haendler_nr.'_agb_'.$lang.'.pdf')) {
               $this->mail->AddAttachment(SHOP_PATH.'/classes/pdf/'.$haendler_nr.'_agb_'.$lang.'.pdf', $haendler_nr.'_'.$this->text->get('menu', 'agb').'.pdf', 'base64', 'application/pdf');
            }
         }
      }

      $this->mail->MsgHTML($this->getBody($this->getBodyText('rechnung'), true));

      $sendmail = $this->_sendMail('sendRechnung');

      if($sendmail != '') {
         // defined('MAIL_DEBUG') && file_put_contents(SHOP_PATH.'/classes/modules/xdebug/log/mail_error', 'sendMail: '.$sendmail, FILE_APPEND);
         return false;
      }

      if ($send_pdf) {
         unlink($anhang);
      }

      return true;
   }

   // Nachricht über Bestellung an Admin
   public function sendAdmin($email_kunde, $best_id, $msg) {
      $this->best_id = $best_id;
      $this->sendAdmin = true;
      $this->sendKundeMsg = true;

      $email = $this->params->firma['email2'];
      $this->mail->ClearAddresses();
      $this->mail->ClearAttachments();
      $this->mail->CharSet = 'UTF-8';
      $this->mail->AddAddress($this->params->firma['email']);

      if (!defined('CONF_KONTAKT_KUNDE')) {
         $this->mail->SetFrom($this->params->firma['email'], $this->params->firma['mailfrom']);
      }
      else {
         $this->mail->SetFrom($email_kunde);
      }

      $this->mail->Subject = str_replace('[Shopname]',$this->params->firma['shop_name'], $this->getSubject('best_admin'));
      $this->mail->MsgHTML($this->getBody($this->getBodyText('best_admin')));

      $sendmail = $this->_sendMail('sendAdmin');

      if($sendmail != '') {
         // defined('MAIL_DEBUG') && file_put_contents(SHOP_PATH.'/classes/modules/xdebug/log/mail_error', 'sendMail: '.$sendmail, FILE_APPEND);
         return false;
      }

      return true;
   }

   public function sendHaendler($email_kunde, $best_id, $msg, $haendler_id) {
      $this->best_id = $best_id;

      $haendler_obj = $this->db->querySingleObject("SELECT u.email, h.* FROM #__users AS u, #__haendler AS h WHERE u.id = $haendler_id AND u.id = h.user_id");
      $this->mail->ClearAddresses();
      $this->mail->ClearAttachments();
      $this->mail->CharSet = 'UTF-8';
      $this->mail->AddAddress($haendler_obj->email);
      $this->mail->SetFrom($haendler_obj->email, '');
      $this->mail->Subject = str_replace('[Shopname]',$this->params->firma['shop_name'], $this->getSubject('best_admin'));
      $this->mail->MsgHTML($this->getBody($this->getBodyText('best_admin')).'<br /><br />'.nl2br($msg));

      $sendmail = $this->_sendMail();

      if($sendmail != '') {
         // defined('MAIL_DEBUG') && file_put_contents(SHOP_PATH.'/classes/modules/xdebug/log/mail_error', 'sendMail: '.$sendmail, FILE_APPEND);
         return false;
      }

      return true;
   }

   // Gutschrift senden
   public function sendGutschrift($email_kunde, $gutschrift, $user_id) {
      $this->user = Control::getUser();
      $this->user->read($user_id);

      $this->mail->ClearAddresses();
      $this->mail->ClearAttachments();
      $this->mail->CharSet = 'UTF-8';
      $this->mail->AddAddress($email_kunde);
      $this->mail->SetFrom($this->params->firma['email'], $this->params->firma['mailfrom']);
      $this->mail->Subject = str_replace('[Shopname]',$this->params->firma['shop_name'], $this->getSubject('gutschrift'));
      $this->mail->MsgHTML($this->getBody(str_replace('[Gutschrift]', $gutschrift, $this->getBodyText('gutschrift'))));

      $sendmail = $this->_sendMail('sendGutschrift');

      if($sendmail != '') {
         // defined('MAIL_DEBUG') && file_put_contents(SHOP_PATH.'/classes/modules/xdebug/log/mail_error', 'sendMail: '.$sendmail, FILE_APPEND);
         return false;
      }

      return true;
   }

   // Bewertung senden
   public function sendBewertung($email_kunde, $best_id) {
      $this->best_id = $best_id;

      // Mails wurden falsch versendet, deshalb zur Vorsicht aus rechnung holen
      $k_email = $this->db->querySingleValue("SELECT email FROM #__rechnung WHERE id = $this->best_id");

      $email = $this->params->firma['email2'];
      $this->mail->ClearAddresses();
      $this->mail->ClearAttachments();
      $this->mail->CharSet = 'UTF-8';
      $this->mail->AddAddress($k_email);
      $this->mail->SetFrom($this->params->firma['email'], $this->params->firma['mailfrom']);
      $this->mail->Subject = str_replace('[Shopname]',$this->params->firma['shop_name'], $this->getSubject('bewertung'));
      $this->mail->MsgHTML($this->getBody($this->getBodyText('bewertung')));

      $sendmail = $this->_sendMail('sendBewertung');

      if($sendmail != '') {
         // defined('MAIL_DEBUG') && file_put_contents(SHOP_PATH.'/classes/modules/xdebug/log/mail_error', 'sendMail: '.$sendmail, FILE_APPEND);
         return false;
      }

      return true;
   }

   // Download-Link senden
   public function sendDownloadLink($email_kunde, $best_id, $link) {
      $this->link    = $link;
      $this->best_id = $best_id;
      $this->mail->ClearAddresses();
      $this->mail->ClearAttachments();
      $this->mail->CharSet = 'UTF-8';
      $this->mail->AddAddress($email_kunde);
      $this->mail->SetFrom($this->params->firma['email'], $this->params->firma['mailfrom']);
      $this->mail->Subject = str_replace('[Shopname]',$this->params->firma['shop_name'], $this->getSubject('download'));
      $this->mail->MsgHTML($this->getBody($this->getBodyText('download')));

      $sendmail = $this->_sendMail('sendDownloadLink');

      if($sendmail != '') {
         // defined('MAIL_DEBUG') && file_put_contents(SHOP_PATH.'/classes/modules/xdebug/log/mail_error', 'sendMail: '.$sendmail, FILE_APPEND);
         return false;
      }
      return true;
   }

   // Gutschein-Mail senden
   public function sendEmailGutschein($email_kunde, $gid, $code, $wert, $mode) {
      // Einlesen Bestelldaten verhindern
      $this->user = 1;
      $this->best_id = false;

      $this->gutschein_code = $code;
      $this->gutschein_wert = number_format($wert, 2, ',', '.');
      $this->gutschein_mode = (int)$mode;
      $this->mail->ClearAddresses();
      $this->mail->ClearAttachments();
      $this->mail->CharSet = 'UTF-8';
      $this->mail->AddAddress($email_kunde);
      $this->mail->SetFrom($this->params->firma['email'], $this->params->firma['mailfrom']);
      $this->mail->Subject = str_replace('[Shopname]',$this->params->firma['shop_name'], $this->getSubject($gid));
      $this->mail->MsgHTML($this->getBody($this->getBodyText($gid)));

      $sendmail = $this->_sendMail('sendEmailGutschein');

      if($sendmail != '') {
         // defined('MAIL_DEBUG') && file_put_contents(SHOP_PATH.'/classes/modules/xdebug/log/mail_error', 'sendMail: '.$sendmail, FILE_APPEND);
         return false;
      }

      return true;
   }

   public function sendWiderrufDl($email, $best_id, $admin='') {
      if ($this->params->firma['b2b_check'] != 'y') {
         $this->best_id = $best_id;
         $this->mail->ClearAddresses();
         $this->mail->ClearAttachments();
         $this->mail->CharSet = 'UTF-8';
         $this->mail->AddAddress($email);
         $this->mail->SetFrom($this->params->firma['email'], $this->params->firma['mailfrom']);
         $this->mail->Subject = $this->getSubject('widerruf_dl_yes');
         $this->mail->MsgHTML($this->getBody($this->getBodyText('widerruf_dl_yes'), true));

         $sendmail = $this->_sendMail('sendWiderrufDl');

         if($sendmail != '') {
            // defined('MAIL_DEBUG') && file_put_contents(SHOP_PATH.'/classes/modules/xdebug/log/mail_error', 'sendMail: '.$sendmail, FILE_APPEND);
            return false;
         }
      }

      return true;
   }

   public function sendWiderrufDlNo($email, $best_id, $admin='') {
      if ($this->params->firma['b2b_check'] != 'y') {
         $this->best_id = $best_id;
         $this->mail->ClearAddresses();
         $this->mail->ClearAttachments();
         $this->mail->CharSet = 'UTF-8';
         $this->mail->AddAddress($email);
         $this->mail->SetFrom($this->params->firma['email'], $this->params->firma['mailfrom']);
         $this->mail->Subject = $this->getSubject('widerruf_dl_no');
         $this->mail->MsgHTML($this->getBody($this->getBodyText('widerruf_dl_no'), true));

         $sendmail = $this->_sendMail('sendWiderrufDlNo');

         if($sendmail != '') {
            // defined('MAIL_DEBUG') && file_put_contents(SHOP_PATH.'/classes/modules/xdebug/log/mail_error', 'sendMail: '.$sendmail, FILE_APPEND);
            return false;
         }
      }

      return true;
   }

   public function sendCheckfile($haendler_nr, $filename) {
      $this->mail->ClearAddresses();
      $this->mail->ClearAttachments();
      $this->mail->CharSet = 'UTF-8';
      $this->mail->AddAddress(($this->params->firma['email2'] != '' ? $this->params->firma['email2'] : $this->params->firma['email']));
      $this->mail->SetFrom($this->params->firma['email'], $this->params->firma['mailfrom']);
      $this->mail->Subject = 'Neue CSV-Datei zur Überprüfung eingegangen';
      $this->mail->MsgHTML('Händler-Nr. '.$haendler_nr.' hat Datei '.$filename.' zur Prüfung hochgeladen.', true);

      $sendmail = $this->_sendMail('sendCheckfile');

      if($sendmail != '') {
         // defined('MAIL_DEBUG') && file_put_contents(SHOP_PATH.'/classes/modules/xdebug/log/mail_error', 'sendMail: '.$sendmail, FILE_APPEND);
         return false;
      }

      return true;
   }

   // Kontaktformular, ...
   public function sendDirect($mail_to, $mail_from, $mail_subject, $mail_html) {
      $this->mail->ClearAddresses();
      $this->mail->ClearAttachments();
      $this->mail->CharSet = 'UTF-8';
      $this->mail->AddAddress($mail_to);

      if (isset($mail_from[1])) {
         $this->mail->SetFrom($mail_from[0], $mail_from[1]);
      }

      else {
         $this->mail->SetFrom($mail_from[0]);
      }

      $this->mail->Subject = $mail_subject;
      $this->mail->MsgHTML($mail_html, true);

      $sendmail = $this->_sendMail('sendDirect');

      if($sendmail != '') {
         //defined('MAIL_DEBUG') && file_put_contents(SHOP_PATH.'/classes/modules/xdebug/log/mail_error', 'sendMail: '.$sendmail, FILE_APPEND);
         return false;
      }

      return true;
   }


   // Betreff aus DB lesen. Falls nicht vorhanden in Default-Sprache Shop
   private function getSubject($art) {
      $text = '';
      $lang = $this->params->default_lang;
      $text = $this->db->querySingleValue("SELECT betreff FROM #__system_texte WHERE art = '$art' AND lang = '".$this->params->default_lang."'");

      if ($text) {
         $text = str_ireplace('[SHOPNAME]', $this->params->firma['shop_name'], $text);

         if ($this->best_id > 0) {
            $text = $this->getBody($text, $lang, false);
         }
      }

      return $text;
   }

   // Mail-texte aus DB lesen. Falls nicht vorhanden in Default-Sprache Shop
   private function getBodyText($art) {
      $text = '';
      $isText = false;
      // Test -> lang lesen
      $sql = "SELECT text FROM #__system_texte WHERE art = '$art' AND lang = '".$this->lang."'";
      $query = $this->db->query($sql);
      if ($query) {
         $isText = $this->db->getObject();
      }

      if ($isText) {
         $text = $isText->text;
      }
      return $text;
   }

   // bodyText durch tatsächlichen Text ersetzen
   private function getBody($text, $sellang = false, $html_mode = true) {
      $data_arr           = [];
      $widerrufsbelehrung = '';

      if (($this->params->isAdmin && !$this->user) || $this->best_id) {
         if (!$this->bestellung) {
            $this->bestellung = Control::getBestellung();
         }

         $this->bestellung->getDetailBestellung($this->best_id, $sellang);

         // Mail mit Bestelldaten
         if ($this->best_id > 0) {
            $data_arr['msg_admin']     = $this->bestellung->dataDetails->msg_admin;
            $data_arr['msg_kunde']     = $this->bestellung->dataDetails->msg_kunde;
            //$data_arr['lieferadresse'] = $this->bestellung->dataDetails->lieferadresse;
            $data_arr['lieferadresse'] = 'y';


            $data_arr['lf_mail']       = (isset($this->bestellung->dataDetails->lf_email) ? $this->bestellung->dataDetails->lf_email : '');
            $data_arr['lf_anrede']     = $this->bestellung->dataDetails->lf_anrede;
            $data_arr['lf_vorname']    = $this->bestellung->dataDetails->lf_vorname;
            $data_arr['lf_nachname']   = $this->bestellung->dataDetails->lf_nachname;
            $data_arr['lf_postnr']     = $this->bestellung->dataDetails->lf_postnr;
            $data_arr['lf_firma']      = $this->bestellung->dataDetails->lf_firma;
            $data_arr['lf_postnr']     = $this->bestellung->dataDetails->lf_postnr;
            $data_arr['lf_adresse']    = $this->bestellung->dataDetails->lf_adresse.' '.$this->bestellung->dataDetails->lf_hausnr;
            $data_arr['lf_plz']        = $this->bestellung->dataDetails->lf_plz;
            $data_arr['lf_ort']        = $this->bestellung->dataDetails->lf_ort;
            $data_arr['lf_buland']     = $this->bestellung->dataDetails->lf_buland;
            $data_arr['lf_land']       = $this->bestellung->dataDetails->lf_land;

            $data_arr['anrede']        = $this->bestellung->dataDetails->anrede;
            $data_arr['vorname']       = $this->bestellung->dataDetails->vorname;
            $data_arr['nachname']      = $this->bestellung->dataDetails->nachname;
            $data_arr['firma']         = $this->bestellung->dataDetails->firma;
            $data_arr['ustid']         = $this->bestellung->dataDetails->ustid;
            $data_arr['adresse']       = $this->bestellung->dataDetails->adresse.' '.$this->bestellung->dataDetails->hausnr;
            $data_arr['plz']           = $this->bestellung->dataDetails->plz;
            $data_arr['ort']           = $this->bestellung->dataDetails->ort;
            $data_arr['buland']        = $this->bestellung->dataDetails->buland;
            $data_arr['land']          = $this->bestellung->dataDetails->land;
            $data_arr['lang']          = $this->bestellung->dataDetails->lang_kunde;
            $data_arr['mail']          = $this->bestellung->dataDetails->email;
            $data_arr['telefon']       = $this->bestellung->dataDetails->telefon;
            $data_arr['zahlart']       = (int)$this->bestellung->dataDetails->zahlungsart;
            $data_arr['zahlungsinfo1'] = (int)$this->bestellung->dataDetails->zahlungsinfo1;
            $data_arr['zahlungsinfo2'] = (int)$this->bestellung->dataDetails->zahlungsinfo2;
            $data_arr['bst_datum']     = date('d.m.Y', strtotime($this->bestellung->dataDetails->created));
            $data_arr['dhl_intraship'] = $this->bestellung->dataDetails->dhl_intraship;
         }

         // Mail ohne Bestelldaten, z.B. bei forgotten aus Kunde
         else {
            $data_arr['lieferadresse'] = 'n';
            $data_arr['anrede']    = '';
            $data_arr['vorname']   = '';
            $data_arr['nachname']  = '';
            $data_arr['firma']     = '';
            $data_arr['ustid']     = '';
            $data_arr['adresse']   = '';
            $data_arr['plz']       = '';
            $data_arr['ort']       = '';
            $data_arr['lang']      = '';
            $data_arr['mail']      = '';
            $data_arr['telefon']   = '';
            $data_arr['msg_kunde'] = '';
         }
      }

      else {
         // Bei forgotten wird user auf 1 gesetzt ???
         if(is_object($this->user)) {
            $user = $this->user->user;
            $data_arr['lieferadresse'] = 'n';

            $data_arr['anrede']    = $user['anrede'];
            $data_arr['vorname']   = $user['vorname'];
            $data_arr['nachname']  = $user['nachname'];
            $data_arr['firma']     = $user['firma'];
            $data_arr['ustid']     = $user['ustid'];
            $data_arr['adresse']   = $user['adresse'].' '.$user['hausnr'];
            $data_arr['plz']       = $user['plz'];
            $data_arr['ort']       = $user['ort'];
            $data_arr['buland']    = $user['buland'];
            $data_arr['lang']      = $user['lang'];
            $data_arr['mail']      = $user['email'];
            $data_arr['telefon']   = '';
            $data_arr['msg_kunde'] = (isset($this->bestellung->dataDetails->msg_kunde) ? $this->bestellung->dataDetails->msg_kunde : '');
         }

         else { // für forgotten ???
            $usr = Control::getUser();
            $user = $usr->user;
            $data_arr['lieferadresse'] = 'n';

            $data_arr['anrede']    = $user['anrede'];
            $data_arr['vorname']   = $user['vorname'];
            $data_arr['nachname']  = $user['nachname'];
            $data_arr['firma']     = $user['firma'];
            $data_arr['ustid']     = $user['ustid'];
            $data_arr['adresse']   = $user['adresse'].' '.$user['hausnr'];
            $data_arr['plz']       = $user['plz'];
            $data_arr['ort']       = $user['ort'];
            $data_arr['buland']    = $user['buland'];
            $data_arr['lang']      = $user['lang'];
            $data_arr['mail']      = $user['email'];
            $data_arr['telefon']   = '';
            $data_arr['msg_kunde'] = ''; // $this->bestellung->dataDetails->msg_kunde;
         }
      }

      // Text-Variablen ersetzen
      $text = str_ireplace(['[Shopname]', '[shop-name]', '[shop_name]'], $this->params->firma['shop_name'], $text);

      if (stristr($text, '[SHOPDOMAIN]') || stristr($text, '[SHOP-DOMAIN]') || stristr($text, '[SHOP_DOMAIN]')) {
         $shopdomain = str_replace('/admin', '', SHOP_URL);
         $text       = str_ireplace(['[SHOPDOMAIN]', '[SHOP-DOMAIN]', '[SHOP_DOMAIN]'], $shopdomain, $text);
      }

      $text = str_ireplace(['[Anrede_Kunde]', '[Anrede-Kunde]'], $this->text->get('kunde', $data_arr['anrede'], $data_arr['lang']), $text);
      $text = str_ireplace(['[Vorname_Kunde]', '[Vorname-Kunde]'], $data_arr['vorname'], $text);

      if ($this->sendAdmin) {
         $text = str_ireplace(['[Email_Kunde]', '[Email-Kunde]'], '<a href="mailto:'.$data_arr['mail'].'">'.$data_arr['mail'].'</a>', $text);
      }

      else {
         $text = str_ireplace(['[Email_Kunde]', '[Email-Kunde]'], $data_arr['mail'], $text);
      }

      $text = str_ireplace(['[E-mail]', '[email]', '[e_mail]'], $this->params->firma['email'], $text);
      $text = str_ireplace(['[Nachname_Kunde]', '[Nachname-Kunde]'], $data_arr['nachname'], $text);
      $text = str_ireplace('[Shopinhaber]', $this->params->firma['first_name'].' '. $this->params->firma['last_name'], $text);

      if (stristr($text, '[Firma_Kunde]')) {
         if ($data_arr['firma']) {
            $text = str_ireplace('[Firma_Kunde]', $data_arr['firma'], $text);
         }
         else {
            $text = str_ireplace("[Firma_Kunde]<br />", '', $text);
         }
      }

      if (stristr($text, '[USTID]')) {
         if ($data_arr['ustid']) {
            $text = str_ireplace('[USTID]', $data_arr['ustid'], $text);
         }
         else {
            $text = str_ireplace(["[USTID]", "[USTID]<br />"], '', $text);
         }
      }

      if (stristr($text, '[Strasse_Kunde]')) {
         $text = str_ireplace('[Strasse_Kunde]', $data_arr['adresse'], $text);
      }

      if (stristr($text, '[PLZ_Kunde]')) {
         $text = str_ireplace('[PLZ_Kunde]', $data_arr['plz'], $text);
      }

      if (stristr($text, '[Ort_Kunde]')) {
         $text = str_ireplace('[Ort_Kunde]', $data_arr['ort'], $text);
      }

      if (stristr($text, '[Bundesland_Kunde]')) {
         if ($data_arr['buland'] != '') {
            $text = str_ireplace('[Bundesland_Kunde]', $data_arr['buland'], $text);
         }
         else {
            $text = str_ireplace("[Bundesland_Kunde]<br />", '', $text);
         }
      }

      if (stristr($text, '[Staat_Kunde]')) {
         $text = str_ireplace('[Staat_Kunde]', $data_arr['land'], $text);
      }

      if (stristr($text, '[Telefonnummer]')) {
         $text = str_ireplace('[Telefonnummer]', $data_arr['telefon'], $text);
      }

      // Lieferadresse
      if ($data_arr['lieferadresse'] == 'y') {
         if (stristr($text, '[Email_KundeL]')) {
            if (isset($data_arr['lf_email']) && $data_arr['lf_email'] != '') {
               $text = str_ireplace('[Email_KundeL]', $data_arr['lf_email'], $text);
            }
            else {
               $text = str_ireplace("[Email_KundeL]<br />", '', $text);
            }
         }

         $text = str_ireplace(['[Anrede_KundeL]', '[Anrede-KundeL]'], $this->text->get('kunde', $data_arr['lf_anrede'], $data_arr['lang']), $text);
         $text = str_ireplace(['[Vorname_KundeL]', '[Vorname-KundeL]'], $data_arr['lf_vorname'], $text);
         $text = str_ireplace(['[Nachname_KundeL]', '[Nachname-KundeL]'], $data_arr['lf_nachname'], $text);

         if (stristr($text, '[Firma_KundeL]')) {
            if ($data_arr['lf_firma']) {
               $text = str_ireplace('[Firma_KundeL]', $data_arr['lf_firma'], $text);
            }
            else {
               $text = str_ireplace("[Firma_KundeL]<br />", '', $text);
            }
         }


         if (stristr($text, '[Strasse_KundeL]')) {
            $text = str_ireplace('[Strasse_KundeL]', $data_arr['lf_adresse'], $text);
         }

         if (stristr($text, '[PLZ_KundeL]')) {
            $text = str_ireplace('[PLZ_KundeL]', $data_arr['lf_plz'], $text);
         }

         if (stristr($text, '[Ort_KundeL]')) {
            $text = str_ireplace('[Ort_KundeL]', $data_arr['lf_ort'], $text);
         }

         if (stristr($text, '[Postnummer_KundeL]')) {
            if ($data_arr['lf_postnr'] != '') {
               $text = str_ireplace('[Postnummer_KundeL]', $data_arr['lf_postnr'], $text);
            }
            else {
               $text = str_ireplace("[Postnummer_KundeL]<br />", '', $text);
            }
         }

         if (stristr($text, '[Postnummer_Kunde]')) {
            if ($data_arr['lf_postnr'] != '') {
               $text = str_ireplace('[Postnummer_Kunde]', $data_arr['lf_postnr'], $text);
            }
            else {
               $text = str_ireplace("[Postnummer_Kunde]<br />", '', $text);
            }
         }

         if (stristr($text, '[Bundesland_KundeL]')) {
            if ($data_arr['lf_buland'] != '') {
               $text = str_ireplace('[Bundesland_KundeL]', $data_arr['lf_buland'], $text);
            }
            else {
               $text = str_ireplace("[Bundesland_KundeL]<br />", '', $text);
            }
         }

         if (stristr($text, '[Staat_KundeL]')) {
            $text = str_ireplace('[Staat_KundeL]', $data_arr['lf_land'], $text);
         }
      }
      else {
         $text = str_ireplace(['[Email_KundeL]', '[Anrede_KundeL]', '[Vorname_KundeL]', '[Nachname_KundeL]',
          '[Firma_KundeL]','[Strasse_KundeL]', '[PLZ_KundeL]', '[Ort_KundeL]', '[Bundesland_KundeL]','[Staat_KundeL]', '[Postnummer_KundeL]'], '', $text);
      }

      if (stristr($text, '[NEUESEITE]')) {
         $text = str_ireplace('[NEUESEITE]', '<br /><br />', $text);
      }

      if (stristr($text, '[Ueberweisungsdaten]')) {
         $text = str_ireplace('[Ueberweisungsdaten]', $this->bankdaten(true), $text);
         $text = str_ireplace('<strong>', '<strong style="font-weight:700;">', $text);
      }

      if (stristr($text, '[Rechnungsnummer]')) {
         if (!$this->bestellung) {
            $this->bestellung = Control::getBestellung();
         }
         $this->bestellung->getDetailBestellung($this->best_id);
         $text = str_ireplace('[Rechnungsnummer]', $this->bestellung->dataDetails->rechnungsnummer, $text);
      }

      if (stristr($text, '[Bestellnummer]')) {
         if (!$this->bestellung) {
            $this->bestellung = Control::getBestellung();
         }
         $this->bestellung->getDetailBestellung($this->best_id);
         $text = str_ireplace('[Bestellnummer]', $this->bestellung->dataDetails->bestellnummer, $text);
      }

      if (stristr($text, '[Bestelldatum]')) {
         $text = str_ireplace('[Bestelldatum]', $data_arr['bst_datum'], $text);
      }

      if (stristr($text, '[Zahlungsart]')) {
         $text = str_ireplace('[Zahlungsart]', Helper::getZahlartText($data_arr['zahlart']), $text);
      }

      if (stristr($text, '[Artikel]')) {
         if (!$this->bestellung) {
            $this->bestellung = Control::getBestellung();
         }
         $this->bestellung->getDetailArtikel($this->best_id, $sellang);
         $text = str_ireplace('[Artikel]', $this->bestellung->mailArtikelList($this->best_id), $text);
      }

      if (stristr($text, '[Summe]')) {
         if ($this->sendKundeMsg) {
//            $text = str_ireplace('[Summe]', '[Summe][MSG_Kunde]', $text);
         }

         if ($this->sendAdminMsg) {
//            $text = str_ireplace('[Summe]', '[Summe][MSG_ADMIN]', $text);
         }

         if (!$this->bestellung) {
            $this->bestellung = Control::getBestellung();
         }
         $text = str_ireplace('[Summe]', $this->bestellung->mailSummenList($this->best_id, $sellang), $text);
      }

      if (stristr($text, '[MSG_Kunde]')) {
         $text = str_ireplace('[MSG_Kunde]', ($data_arr['msg_kunde'] == '' ? '' : '<br />'.str_replace("\n", '<br />', $data_arr['msg_kunde']).'<br />'), $text);
      }

      if (stristr($text, '[Nachricht_Kunde]')) {
         $text = str_ireplace('[Nachricht_Kunde]', ($data_arr['msg_kunde'] == '' ? '' : '<br />'.str_replace("\n", '<br />', $data_arr['msg_kunde']).'<br />'), $text);
      }

      if (stristr($text, '[MSG_ADMIN]')) {
         $text = str_ireplace('[MSG_ADMIN]', ($data_arr['msg_admin'] == '' ? '' : '<br />'.str_replace("\n", '<br />', $data_arr['msg_admin']).'<br />'), $text);
      }

      if (stristr($text, '[Nachricht_Admin]')) {
         $text = str_ireplace('[Nachricht_Admin]', ($data_arr['msg_admin'] == '' ? '' : '<br />'.str_replace("\n", '<br />', $data_arr['msg_admin']).'<br />'), $text);
      }

      if (stristr($text, '[Verifizierungslink]')) {
         $link = '<a href="'.$this->link.'">'.$this->link.'</a>';
         $text = str_ireplace('[Verifizierungslink]', $link, $text);
      }

      if (stristr($text, '[Downloadlink]')) {
         $url  = SHOP_URL.'/download/'.$this->link;
         $link = '<a href="'.$url.'">'.$url.'</a>';
         $text = str_ireplace('[Downloadlink]', $link, $text);
      }

      if (stristr($text, '[Widerrufsbelehrung]')) {
         //$lang = $this->params->selected_lang;
         $lang = $this->lang;

         $sql = '';

         if (!defined('CONF_MODULE_PORTAL')) {
            if ($this->widerruf_id == 1) {
               $sql = "SELECT text FROM #__seiten WHERE art = 'widerruf1' AND lang = '". $lang ."'";
            }

            if ($this->widerruf_id == 2) {
               $sql = "SELECT text FROM #__seiten WHERE art = 'widerruf2' AND lang = '". $lang ."'";
            }

            if ($this->widerruf_id == 3) {
               $sql = "SELECT text FROM #__seiten WHERE art = 'widerruf3' AND lang = '". $lang ."'";
            }

            if ($this->widerruf_id == 4) {
               $sql = "SELECT text FROM #__seiten WHERE art = 'widerruf4' AND lang = '". $lang ."'";
            }

            if ($sql == '') {
               $sql = "SELECT text FROM #__seiten WHERE art = 'widerruf1' AND lang = '". $lang ."'";
            }
         }

         else {
            $haendler_id = $this->db->querySingleValue("SELECT haendler_id FROM #__rechnung WHERE id = ".$this->best_id);
            $sql = "SELECT text FROM #__haendler_seiten WHERE haendler_id = $haendler_id AND lang = '$lang' AND art = 'widerruf'";
         }

         $this->db->query($sql);
         $data = $this->db->getObject();
         $sqltext = $data->text;
         // $text = str_ireplace('[Widerrufsbelehrung]', str_ireplace('[NEUESEITE]', '<br /><br />', $sqltext), $text);
         $text = str_ireplace('[Widerrufsbelehrung]', '', $text);
         $widerrufsbelehrung = str_ireplace('[NEUESEITE]', '<br /><br />', $sqltext);
      }

      if (stristr($text, '[Gutschein_Code]')) {
         $text = str_ireplace('[Gutschein_Code]', $this->gutschein_code, $text);
      }

      if (stristr($text, '[Agb]')) {
         $text = str_ireplace('[Agb]', Helper::getSeiten('agb', $this->lang), $text);
      }

      // alt, durch [Prozent_Hauptwaehrung] ersetzt. Nur Kompatibilität mit bestehenden Kunden
      if (stristr($text, '[Gutschein_Wert]')) {
         $text = str_ireplace('[Gutschein_Wert]', $this->gutschein_wert, $text);
      }

      if (stristr($text, '[Prozent_Hauptwaehrung]')) {
         if ($this->gutschein_mode == 1) {
            $text = str_ireplace('[Prozent_Hauptwaehrung]', $this->gutschein_wert.' '.Helper::waehrungText($this->params->firma['waehrung1'], 3), $text);
         }
         else {
            $text = str_ireplace('[Prozent_Hauptwaehrung]', $this->gutschein_wert.'%', $text);
         }
      }

      if (stristr($text, '[WiderrufDL]')) {
         $text = str_ireplace('[WiderrufDL]', $this->_widerrufDL((int)$this->bestellung->dataDetails->widerruf), $text);
      }

      if (stristr($text, '[DHL_INTRASHIP]')) {
         $text = str_ireplace('[DHL_INTRASHIP]', '<a href="https://nolp.dhl.de/nextt-online-public/set_identcodes.do?lang=de&idc='.$data_arr['dhl_intraship'].'&extendedSearch=true">'.$data_arr['dhl_intraship'].'</a>' , $text);
      }

      // Bewertungslink
      if (defined('CONF_MODULE_SHOPSIEGEL')) {
         if (stristr($text, '[BEWERTUNG]') || stristr($text, 'href="https://siegel.kanpaiclassic.com')) {
            $link = '<a title="Quality-Siegel" href="https://siegel.kanpaiclassic.com" target="_blank"><img src="https://siegel.kanpaiclassic.com/siegel/siegel2.png" alt="Quality-Siegel" border="0" /></a>';

            $l  = (object)[];
            $l->shop       = SHOP_URL;
            $l->bestellung = $this->bestellung->dataDetails->bestellnummer;

            $ref = base64_encode(json_encode($l));

            $img  = '<img src="'.SHOPSIEGEL_LINK.'/siegel/'.str_replace(['https://', 'http://'], '', SHOP_URL).'" alt="Quality-Siegel" />';
            $link = '<a href="'.SHOPSIEGEL_LINK.'/bewertung/'.$ref.'"  title="Quality-Siegel" target="_blank">'.$img.'</a>';

            $l->referenz   = $ref;
            $l->email      = $this->bestellung->dataDetails->email;
            $l->vorname    = $this->bestellung->dataDetails->vorname;
            $l->nachname   = $this->bestellung->dataDetails->nachname;
            $l->bestell_nr = $this->bestellung->dataDetails->bestellnummer;
            $l->website    = SHOP_URL;
            $l->shoptype   = 'flow';

            $this->siegel_ref = base64_encode(json_encode($l));
         }
      }
      if (stristr($text, '[Zahlungsart_Text]') || stristr($text, '[Zahlungsart-Text]')) {
         $lang = $this->lang;

         if (file_exists(SHOP_PATH.'/admin/zahlart.json')) {
            $za_text = json_decode(file_get_contents(SHOP_PATH.'/admin/zahlart.json'));
            $zahlart_text = '';

            if (isset($za_text->{'za'.(int)$data_arr['zahlart'].'_'.$lang})) {
               if ($za_text->{'za'.(int)$data_arr['zahlart'].'_'.$lang} != '') {
                  $zahlart_text = $za_text->{'za'.(int)$data_arr['zahlart'].'_'.$lang};

                  if ($data_arr['zahlungsinfo1'] != '') {
                     $zahlart_text .= ' '.$data_arr['zahlungsinfo1'];
                  }

                  else if ($data_arr['zahlungsinfo1'] != '') {
                     $zahlart_text .= ' '.$data_arr['zahlungsinfo1'];
                  }

                  $text = str_ireplace(['[Zahlungsart_Text]', '[Zahlungsart-Text]'], '<div style="width:660px;">'.$zahlart_text.'</div>', $text);
               }
            }

            else {
               $text = str_ireplace(['[Zahlungsart_Text]', '[Zahlungsart-Text]'], '', $text);
            }
         }

         else {
            $text = str_ireplace(['[Zahlungsart_Text]', '[Zahlungsart-Text]'], '', $text);
         }
      }

      if (stristr($text, '[Nährwerte]')) {
         $lang = $this->lang;
         $text = str_ireplace('[Nährwerte]', $this->text->get('mail', 'nw', $lang), $text);
      }

      if (stristr($text, '[Naehrwerte]')) {
         $lang = $this->lang;
         $text = str_ireplace('[Naehrwerte]', $this->text->get('mail', 'nw', $lang), $text);
      }

      if ($html_mode) {
         // Ausgabe starten
         $link = SHOP_URL_IDX;

         $htmlmail  = '<!DOCTYPE html>'.CR;
         $htmlmail  = '<html>'.CR;
         $htmlmail .= '<head>'.CR;
         $htmlmail .=     $this->_css();
         $htmlmail .= '</head>'.CR;
         $htmlmail .= '<body style="background-color:#f1f1f1;">'.CR;
         $htmlmail .= '   <div class="body">'.CR;
         $htmlmail .=        $this->_header($link);
         $htmlmail .= '      <div class="content">'.CR;
         $htmlmail .=           $text;
         $htmlmail .=           $this->_footer();
         $htmlmail .= '       </div>'.CR;
         $htmlmail .= '
            <div class="links_unten" style="position:relative; text-align:center;">
               <a class="link" href="'.$link.'/impressum">'.$this->text->get('menu', 'impressum', $this->lang).'</a> |
               <a class="link" href="'.$link.'/datenschutz">'.$this->text->get('menu', 'datenschutz', $this->lang).'</a> |
               <a class="link" href="'.$link.'/agb">'.$this->text->get('menu', 'agb', $this->lang).'</a>
            </div>';
   // Entfernt 25.02.2019:  | <a class="link" href="'.$link.'/widerruf1">'.$this->text->get('menu', 'widerruf', $this->lang).'</a>
         $htmlmail .= '   </div>';
         $htmlmail .= '   <br /></br />';

         if ($widerrufsbelehrung != '') {
            $htmlmail .= '   <div style="margin:0 10px 20px 10px;">'.$widerrufsbelehrung.'</div>';
         }

         $htmlmail .= '</body>';
         $htmlmail .= '</html>';

         return $htmlmail;
      }

      else {
         return $text;
      }
   }

   // Bankdaten generieren für Ersetzung
   private function bankdaten($sellang = false) {
      if (defined('CONF_MODULE_PORTAL')) {
         return $this->bankdatenHaendler($sellang);
      }

      if (!$sellang) {
         $lang = '';
      }
      else {
         if (!$this->bestellung) {
            $this->bestellung = Control::getBestellung();
         }

         $this->bestellung->getDetailBestellung($this->best_id);
         $lang = $this->bestellung->dataDetails->lang_kunde;
      }

      $back  = '<table class="tab3">';
      $back .= '   <tr>';
      $back .= '      <td class="td3_1">'.$this->text->get('shop', 'bank', $lang).'</td>';
      $back .= '      <td class="td3_2">'.$this->params->firma['bank1'].'</td>';
      $back .= '   </tr>';
      $back .= '   <tr>';
      $back .= '      <td class="td3_1">'.$this->text->get('shop', 'iban', $lang).'</td>';
      $back .= '      <td class="td3_2">'.$this->params->firma['bank1_iban'].'</td>';
      $back .= '   </tr>';
      $back .= '   <tr>';
      $back .= '      <td class="td3_1">'.$this->text->get('shop', 'bic', $lang).'</td>';
      $back .= '      <td class="td3_2">'.$this->params->firma['bank1_bic'].'</td>';
      $back .= '   </tr>';
      $back .= '   <tr>';
      $back .= '      <td class="td3_1">'.$this->text->get('shop', 'inhaber', $lang).'</td>';
      $back .= '      <td class="td3_2">'.$this->params->firma['bank1_inhaber'].'</td>';
      $back .= '   </tr>';
      $back .= '</table>';
      return $back;
   }
   // Bankdaten generieren für Ersetzung
   private function bankdatenHaendler($sellang) {
      if (!$sellang) {
         $lang = '';
      }
      else {
         if (!$this->bestellung) {
            $this->bestellung = Control::getBestellung();
         }
         $this->bestellung->getDetailBestellung($this->best_id);
         $lang = $this->bestellung->dataDetails->lang_kunde;
      }

      $haendler = $this->db->querySingleVAlue("SELECT haendler_id FROM #__rechnung WHERE id = ".$this->best_id);
      $data = $this->db->querySingleObject("SELECT h.inhaber, h.h_bank_name, h_bank_iban, h.h_bank_bic, u.vorname, u.nachname FROM #__users AS u, #__haendler AS h
                                            WHERE u.id = $haendler AND u.id = h.user_id");
      $inhaber = ($data->inhaber != '' ? $data->inhaber : $data->vorname.' '.$data->nachname);

      $back  = '<table class="tab3">';
      $back .= '   <tr>';
      $back .= '      <td class="td3_1">'.$this->text->get('shop', 'bank', $lang).'</td>';
      $back .= '      <td class="td3_2">'.$data->h_bank_name.'</td>';
      $back .= '   </tr>';
      $back .= '   <tr>';
      $back .= '      <td class="td3_1">'.$this->text->get('shop', 'inhaber', $lang).'</td>';
      $back .= '      <td class="td3_2">'.$inhaber.'</td>';
      $back .= '   </tr>';
      $back .= '   <tr>';
      $back .= '      <td class="td3_1">'.$this->text->get('shop', 'iban', $lang).'</td>';
      $back .= '      <td class="td3_2">'.$data->h_bank_iban.'</td>';
      $back .= '   </tr>';
      $back .= '   <tr>';
      $back .= '      <td class="td3_1">'.$this->text->get('shop', 'bic', $lang).'</td>';
      $back .= '      <td class="td3_2">'.$data->h_bank_bic.'</td>';
      $back .= '   </tr>';
      $back .= '</table>';
      return $back;
   }

   private function _widerrufDL($widerruf) {
      $back = '';

      if ($widerruf == 4) {
         $back .= 'Die Dienstleistung soll erst in 14 Tagen nach Ablauf der Widerrufsfrist beginnen.';
      }

      if ($widerruf == 14) {
         $back .= 'Die Dienstleistung soll vor Ende der Widerrufsfrist beginnen.<br />Bei vollständiger Vertragserfüllung erlischt das Widerrufsrecht des Kundens.';
      }

      return $back;
   }

   private function _header($link) {
      $html = '';
      $header_img = '';

      if (file_exists(TEMPLATE_PATH.'/images/mailheader.png')) {
         $header_img = '<img src="'.TEMPLATE_URL.'/images/mailheader.png?'.time().'" style="max-height:100%;"/>';
      }

      else if (file_exists(TEMPLATE_PATH.'/images/mailheader.jpg')) {
         $header_img = '<img src="'.TEMPLATE_URL.'/images/mailheader.jpg?'.time().'" style="max-height:100%;"/>';
      }


      $html .= '
         <div class="logo" style="position:relative; height:65px; padding:30px 0 10px 0; ">
            '.$header_img.'
            <div class="links_oben" style="position:absolute; right:0; bottom:10px; text-align:right;">
               <a class="link" href="'.$link.'/kontakt">'.$this->text->get('menu', 'kontakt', $this->lang).'</a> |
               <a class="link" href="'.$link.'/login">'.$this->text->get('menu', 'login', $this->lang).'</a>
            </div>
         </div>
      ';
      return $html;
   }

   private function _footer() {
      $html = '';

      $html .= '<div class="footer">';

      if (Helper::getData('mail_footer_check', 'n') == 'y') {
         $firma = (object)$this->params->firma;
         $staat = $firma->country;

         // $html .= '   <div style="height:2px; margin:10px 0; background-image:url('.TEMPLATE_URL.'/images/system/maillinie.png);"></div>';
         $html .= '   <div style="height:12px; margin:10px 0; overflow:hidden; white-space:nowrap;">'. str_repeat('. ', 120).'</div>';

         // Linke Spalte
         $html .= '   <div style="position:relative; width:50%; float:left; line-height:18px;">';
         if ($firma->shop_name_check == 'y') {
            $html .= '      <span>'.$firma->shop_name.'</span><br />';
         }

         if ($firma->firm_name_check == 'y') {
            $html .= '      <span>'.$firma->firm_name.'</span><br />';
         }

         if ($firma->first_name_check == 'y' || $firma->last_name_check == 'y') {
            $name = '';

            if ($firma->first_name_check == 'y') {
               $name .= $firma->first_name.' ';
            }

            if ($firma->last_name_check == 'y') {
               $name .= $firma->last_name.' ';
            }

            $html .= '      <span>'.$name.'</span><br />';
         }

         if ($firma->street_check == 'y') {
            $html .= '      <span>'.$firma->street.' '.$firma->haus_nr.'</span><br />';
         }

         if ($firma->postal_code_check == 'y' || $firma->city_check == 'y') {
            $name = '';

            if ($firma->postal_code_check == 'y') {
               $name .= $firma->postal_code.' ';
            }

            if ($firma->city_check == 'y') {
               $name .= $firma->city.' ';
            }

            $html .= '      <span>'.$name.'</span><br />';
         }

         if ($firma->country_check == 'y') {
            $html .= '      <span>'.$staat.'</span><br />';
         }

         $html .= '   </div>';
         $html .= '   <div style="position:relative; width:50%; float:left; text-align:right; line-height:18px;">';
         $html .= '      <div style="position:relative; display:inline-block; width:auto; text-align:left; padding-right:50px;">';

         if ($firma->telefon_check == 'y') {
            $html .= '         <span>Tel: '.$firma->telefon.'</span><br />';
         }

         if ($firma->fax_check == 'y') {
            $html .= '         <span>Fax: '.$firma->fax.'</span><br />';
         }

         if ($firma->email_check == 'y') {
            $html .= '         <span>E-Mail: '.$firma->email.'</span><br />';
         }

         if ($firma->web_check == 'y') {
            $html .= '         <span style="color:#295c9e;"><a href="http://'.$firma->web.'" style="color:#295c9e; text-decoration:none;">Web: '.$firma->web.'</a></span><br />';
         }

         $html .= '      </div>';
         $html .= '   </div>';
         $html .= '   <div style="clear:both; height:1px;"></div>';
      }

      // Wenn vorhanden Kunden-Mail-Footer ausgeben
      else if (file_exists(TEMPLATE_PATH.'/images/mailfooter.png')) {
         $html .= '   <img style="max-width:680px; margin-left:-10px; margin-right:-10px;" src="'.TEMPLATE_URL.'/images/mailfooter.png" alt="" />';
      }

      else if (file_exists(TEMPLATE_PATH.'/images/mailfooter.jpg')) {
         $html .= '   <img style="max-width:680px; margin-left:-10px; margin-right:-10px;" src="'.TEMPLATE_URL.'/images/mailfooter.jpg" alt="" />';
      }

      $html .= '</div>';

      return $html;
   }

   private function _css() {
      $fonts_css = '';
      $fontsize  = 12;

      $html  = '      <style>';
      $html .= '
         * { font-family:tamoha, arial, sans-serif; font-weight:400; font-size:'.$fontsize.'px; color:#444444; }
         .body { width:682px; position:relative; margin:auto; }
         .content { border:1px solid #d8d8d8; padding:10px; color:#444444; background-color:#ffffff; }
         table { width:660px; }
         .link { color:#444444; text-decoration:none; }
         strong, b { font-weight:700; }

         table { table-layout:fixed; border-collapse:collapse; border-spacing:0; }
         tr { vertical-align:top; }
         td { padding:0; }
         .td1_1 { width:95px; text-align:left; }
         .td1_2 { width:280px; text-align:left; }
         .td1_3 { width:85px; text-align:right; }
         .td1_4 { width:60px; text-align:center; }
         .td1_5 { width:100px; text-align:right; }
         .td1_6 { width:40px; text-align:right; }
         .td2_1 { width:560px; text-align:right; }
         .td2_2 { width:100px; text-align:right; }
         .td3_1 { width:160px; text-align:left; }
         .td3_2 { width:500px; text-align:left; }
      </style>';

      return $html;
   }

   private function _sendMail($function = '') {
      $_SESSION['MAIL_ERROR'] = '';
      $mail_error = '';
      $crlf = "\r\n";
      $this->mail->setLanguage('fr', SHOP_PATH.'/classes/PHPMailer/phpmailer.lang-de.php');
      $mail_from = $this->mail->From;
      $mail_to   = '';

      // SMTP verwenden
      if (Helper::getData('smtp_check', 'n') == 'y') {
         $user        = Helper::getData('smtp_user', '');
         $pass        = Helper::getData('smtp_pass', '');
         $server      = Helper::getData('smtp_server', '');
         $port_mode   = Helper::getData('smtp_port', '');
         $port        = 25;
         $mail_error = '';

         if ($port_mode == 'ssl') {
            $port = 465;
//            $secure = $this->mail::ENCRYPTION_SMTPS;
         }

         else if ($port_mode == 'tls') {
            $port = 587;
         }

         require_once SHOP_PATH.'/classes/PHPMailer/src/SMTP.php';
         require_once SHOP_PATH.'/classes/PHPMailer/src/Exception.php';

         $smtp = new \PHPMailer\PHPMailer\SMTP;

         try {
            // Debug
            // $this->mail->SMTPDebug = \PHPMailer\PHPMailer\SMTP::DEBUG_SERVER;                      // Enable verbose debug output
            // $smtp->Debugoutput  = 'html';

            $smtp->Timelimit = 10;
            $smtp->Timeout = 10;
            $this->mail->isSMTP();
            $this->mail->SMTPAuth   = true;
            $this->mail->Username   = $user;
            $this->mail->Password   = $pass;
            $this->mail->Host       = $server;
            $this->mail->Port       = $port;
            $this->mail->SMTPSecure = $port_mode;
            // $this->mail->addReplyTo($this->params->firma['email']);

            if (!$this->mail->send()) {
               $_SESSION['MAIL_ERROR'] = 'Mailer Error: ' . $this->mail->ErrorInfo;
               file_put_contents(SHOP_PATH.'/classes/modules/xdebug/log/mail_error', date('Y-m-d H:m').' smpt: '.$function.' : '.$mail_from.' : '.$mail_to.$crlf.$this->mail->ErrorInfo.$crlf, FILE_APPEND);
               $mail_error = $this->mail->ErrorInfo;
            }

         } catch (\Exception $e) {
            $_SESSION['MAIL_ERROR'] = "Message could not be sent. Mailer Error: $this->mail->ErrorInfo";
            $mail_error = $this->mail->ErrorInfo;
            file_put_contents(SHOP_PATH.'/classes/modules/xdebug/log/mail_error', date('Y-m-d H:m').' : smtp catch : '.$function.' : '.$mail_from.' : '.$mail_to.$crlf, FILE_APPEND);
         }
      }

      // PHP-Mail verwenden
      else {
         if (!$this->mail->send()) {
            $_SESSION['MAIL_ERROR'] = 'Mailer Error: ' . $this->mail->ErrorInfo;
            $mail_error = $this->mail->ErrorInfo;
            file_put_contents(SHOP_PATH.'/classes/modules/xdebug/log/mail_error', date('Y-m-d H:m').' : php '.$function.' : '.$mail_from.' : '.$mail_to.$crlf, FILE_APPEND);
         }
      }

      return $mail_error;
   }
}