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

class KANPAICLASSIC_widerruf
{
   private $params = null;
   private $text = null;

   public $msg = '';
   public $bestnr_1 = '';
   public $bestnr_2 = '';
   public $bestnr_3 = '';
   public $bestnr_4 = '';
   public $bestnr_5 = '';
   public $bestnr_6 = '';
   public $bestnr_7 = '';
   public $bez_1 = '';
   public $bez_2 = '';
   public $bez_3 = '';
   public $bez_4 = '';
   public $bez_5 = '';
   public $bez_6 = '';
   public $bez_7 = '';
   public $menge_1 = '';
   public $menge_2 = '';
   public $menge_3 = '';
   public $menge_4 = '';
   public $menge_5 = '';
   public $menge_6 = '';
   public $menge_7 = '';
   public $grund_1 = '';
   public $grund_2 = '';
   public $grund_3 = '';
   public $grund_4 = '';
   public $grund_5 = '';
   public $grund_6 = '';
   public $grund_7 = '';

   public $name = '';
   public $adresse = '';
   public $email = '';
   public $bestellt = '';
   public $erhalten = '';
   public $rechnung = '';
   public $captcha = '';

   public $error_captcha = true;
   public $error_name = false;
   public $error_adresse = false;
   public $error_email = false;
   public $error_art = false;
   public $error_bestellt = false;
   public $error_erhalten = false;

   public $mail_send = '';
   public $check_error = false;

   public function __construct() {
      $this->params = Control::getParams();

      if (isset($_SESSION['captcha'])) {
         $this->_checkCaptcha();
         $this->_check();

         if ($this->params->postCheckbox('formular_loaded') == 'y') {
            if (!$this->error_captcha && !$this->check_error) {
               $this->mail_send = $this->_sendMail();
            }
         }
      }

      else {
         $this->_checkCaptcha();
      }
   }

   private function _check() {
      // Artikeldaten übernehmen und auf Gültigkeit prüfen
      $art_error = 0;
      $art_count = 0;

      if ($this->params->postCheckbox('formular_loaded') == 'y') {
         for ($i = 1; $i < 3; $i++) {
            $bestnr = $this->params->postString('bestnr_'.$i);
            $bez    = $this->params->postString('bez_'.$i);
            $menge  = $this->params->postString('menge_'.$i);
            $grund  = $this->params->postString('grund_'.$i);
            $this->{'bestnr_'.$i} = $bestnr;
            $this->{'bez_'.$i} = $bez;
            $this->{'menge_'.$i} = $menge;
            $this->{'grund_'.$i} = $grund;

            if (!($bestnr == '' && $bez == '' || $menge == '' && $grund == '' ||
                $bestnr != '' && $bez != '' || $menge != '' && $grund != '')) {
               $art_error++;
            }
            else {
               $art_count++;
            }

            if ($art_error > 0 || $art_count < 1) {
               $this->art_error = true;
            }
         }

         // Plichteingaben überprüfen
         $this->name = $this->params->postString('name');

         if ($this->name == '') {
            $this->error_name = true;
            $this->check_error = true;
         }
         $this->adresse = $this->params->postString('adresse');

         if ($this->adresse == '') {
            $this->error_adresse = true;
            $this->check_error = true;
         }

         $this->email = $this->params->postString('email');

         if ($this->email == '' ||!preg_match( '/^([a-z0-9]+([-_\.]?[a-z0-9])+)@[a-z0-9äöü]+([-_\.]?[a-z0-9äöü])+\.[a-z]{2,4}$/i', $this->email)) {
            $this->error_email = true;
            $this->check_error = true;
         }

         $this->bestellt = $this->params->postString('bestellt');

         if ($this->bestellt == '') {
            $this->error_bestellt = true;
            $this->check_error = true;
         }

         $this->erhalten = $this->params->postString('erhalten');

         if ($this->erhalten == '') {
            $this->error_erhalten = true;
            $this->check_error = true;
         }
      }
   }

   private function _checkCaptcha() {
      include_once SHOP_PATH.'/classes/captcha/captcha.php';
      // Test, ob Captcha OK
      if (isset($_SESSION['captcha'])) {
         $code = $_SESSION['captcha']['code'];

         if ($this->params->postString('captcha') == $code) {
            $this->captcha = $code;

            if ($this->params->postCheckbox('formular_loaded') == 'y') {
               $this->error_captcha = false;
            }

            return;
         }

         else {
            $this->captcha = '';
            $_SESSION['captcha'] = captcha();
            return;
         }
      }
      // Sonst neues Captcha generieren
      $_SESSION['captcha'] = captcha();
      $this->error_captcha = false;
   }

   private function _sendMail() {
      $text = Control::getText();
      $params = $this->params;
      $html  = '';
      $html .= '<style>'.CR;
      $html .= '   .line { width:100%; line-height:24px; clear:both; margin-bottom:5px; }';
      $html .= '   .line18 { width:100%; line-height:16px; clear:both; }';
      $html .= '   .line_left { width:40%; line-height:24px; float:left; }';
      $html .= '   .line_right { width:58%; line-height:24px; float:left; padding-left:6px; box-sizing:border-box; }';
      $html .= '   .line_right.border { line-height:22px; border:1px solid #888888; padding-left:5px; }';
      $html .= '</style>'.CR;

      $html .= '<div style="position:relative; width:700px; margin:20px auto;">'.CR;
      $html .= '<div class="line ueberschrift text_max"><strong>'.$text->get('widerruf', 'form').'</strong></div>'.CR;
      $html .= '<br />'.CR;

      // Firmendaten
      $html .= '<div class="line shop_adr">'.CR;
      $html .= '   <div class="line_shop">'.$text->get('widerruf', 'an').':&nbsp;</div>'.CR;
      $html .= '   <div class="line_adr">'.CR;
      $html .= '      <span>'.str_replace(' ', '&nbsp;', $params->firma['shop_name']).',</span>'.CR;
      $html .= '      <span>'.str_replace(' ', '&nbsp;', $params->firma['first_name']).' '. $params->firma['last_name'].',</span>'.CR;
      $html .= '      <span>'.str_replace(' ', '&nbsp;', $params->firma['street'].' '.$params->firma['haus_nr']).',</span>'.CR;
      $html .= '      <span>'.str_replace(' ', '&nbsp;', $params->firma['postal_code'].' '.$params->firma['city']).',</span>'.CR;
      $html .= '      <span>'.str_replace(' ', '&nbsp;', ($params->firma['fax'] != '' ? 'Fax '.$params->firma['fax'].', ' : '')).'</span>'.CR;
      $html .= '      <span>'.str_replace(' ', '&nbsp;', $params->firma['email']).'</span>'.CR;
      $html .= '   </div>'.CR;
      $html .= '</div>'.CR;

      $html .= '<div class="line18">&nbsp;</div>'.CR;
      $html .= '<div class="line">'.$text->get('widerruf', 'vertrag').'</div>'.CR;
      $html .= '<div class="line18">&nbsp;</div>'.CR;

      $html .= '<div class="line">'.CR;
      $html .= '   <div class="line_left">'.$text->get('widerruf', 'verbraucher').'</div>'.CR;
      $html .= '   <div class="line_right border">'.$this->name.'</div>'.CR;
      $html .= '   <div style="clear:both;"></div>'.CR;
      $html .= '</div>'.CR;

      $html .= '<div class="line">'.CR;
      $html .= '   <div class="line_left">'.$text->get('widerruf', 'email').'</div>'.CR;
      $html .= '   <div class="line_right border">'.$this->email.'</div>'.CR;
      $html .= '   <div style="clear:both;"></div>'.CR;
      $html .= '</div>'.CR;

      $html .= '<div class="line">'.CR;
      $html .= '   <div class="line_left">'.$text->get('widerruf', 'anschrift').'</div>'.CR;
      $html .= '   <div class="line_right border">'.$this->adresse.'</div>'.CR;
      $html .= '   <div style="clear:both;"></div>'.CR;
      $html .= '</div>'.CR;

      $html .= '<div class="line18">&nbsp;</div>'.CR;

// Kauf
      $html .= '<div class="line">'.CR;
      $html .= '   <div class="line_left">&nbsp;</div>'.CR;
      $html .= '   <div class="line_right"><strong>'.$text->get('widerruf', 'kauf').'</strong></div>'.CR;
      $html .= '   <div style="clear:both;"></div>'.CR;
      $html .= '</div>'.CR;

      $html .= '<div class="line">'.CR;
      $html .= '   <div class="line_left fliesstext text_normal">'.$text->get('adresse', 'bestnr').'</div>'.CR;
      $html .= '   <div class="line_right border">'.$this->bestnr_1.'</div>'.CR;
      $html .= '   <div style="clear:both;"></div>'.CR;
      $html .= '</div>'.CR;

      $html .= '<div class="line">'.CR;
      $html .= '   <div class="line_left fliesstext text_normal">'.$text->get('widerruf', 'artikelbez').'</div>'.CR;
      $html .= '   <div class="line_right border">'.$this->bez_1.'</div>'.CR;
      $html .= '   <div style="clear:both;"></div>'.CR;
      $html .= '</div>'.CR;

      $html .= '<div class="line">'.CR;
      $html .= '   <div class="line_left">'.$text->get('artikel', 'menge').'</div>'.CR;
      $html .= '   <div class="line_right border">'.$this->menge_1.'</div>'.CR;
      $html .= '   <div style="clear:both;"></div>'.CR;
      $html .= '</div>'.CR;

      $html .= '<div class="line">'.CR;
      $html .= '   <div class="line_left">'.$text->get('widerruf', 'grund').'</div>'.CR;
      $html .= '   <div class="line_right border">'.$this->grund_1.'</div>'.CR;
      $html .= '   <div style="clear:both;"></div>'.CR;
      $html .= '</div>'.CR;

      $html .= '<div class="line18">&nbsp;</div>'.CR;

// Dienstleistung
      $html .= '<div class="line">'.CR;
      $html .= '   <div class="line_left">&nbsp;</div>'.CR;
      $html .= '   <div class="line_right"><strong>'.$text->get('widerruf', 'dl').'</strong></div>'.CR;
      $html .= '   <div style="clear:both;"></div>'.CR;
      $html .= '</div>'.CR;

      $html .= '<div class="line">'.CR;
      $html .= '   <div class="line_left">'.$text->get('adresse', 'bestnr').'</div>'.CR;
      $html .= '   <div class="line_right border">'.$this->bestnr_2.'</div>'.CR;
      $html .= '   <div style="clear:both;"></div>'.CR;
      $html .= '</div>'.CR;

      $html .= '<div class="line">'.CR;
      $html .= '   <div class="line_left">'.$text->get('widerruf', 'dienstl').'</div>'.CR;
      $html .= '   <div class="line_right border">'.$this->bez_2.'</div>'.CR;
      $html .= '   <div style="clear:both;"></div>'.CR;
      $html .= '</div>'.CR;

      $html .= '<div class="line">'.CR;
      $html .= '   <div class="line_left">'.$text->get('artikel', 'menge').'</div>'.CR;
      $html .= '   <div class="line_right border">'.$this->menge_2.'</div>'.CR;
      $html .= '   <div style="clear:both;"></div>'.CR;
      $html .= '</div>'.CR;

      $html .= '<div class="line">'.CR;
      $html .= '   <div class="line_left">'.$text->get('widerruf', 'grund').'</div>'.CR;
      $html .= '   <div class="line_right border">'.$this->grund_2.'</div>'.CR;
      $html .= '   <div style="clear:both;"></div>'.CR;
      $html .= '</div>'.CR;

      $html .= '<div class="line18">&nbsp;</div>'.CR;

// Datum
      $html .= '<div class="line">'.CR;
      $html .= '   <div class="line_left">'.$text->get('widerruf', 'bestellt').'</div>'.CR;
      $html .= '   <div class="line_right border">'.$this->bestellt.'</div>'.CR;
      $html .= '   <div style="clear:both;"></div>'.CR;
      $html .= '</div>'.CR;

      $html .= '<div class="line">'.CR;
      $html .= '   <div class="line_left">'.$text->get('widerruf', 'erhalten').'</div>'.CR;
      $html .= '   <div class="line_right border">'.$this->erhalten.'</div>'.CR;
      $html .= '   <div style="clear:both;"></div>'.CR;
      $html .= '</div>'.CR;
      $html .= '</div>'.CR;

      $mail = Control::getPhpMailer();
      $mail->CharSet = 'UTF-8';
      $mail->AddAddress($this->email);
      $mail->AddCC($this->params->firma['email']);
      $mail->SetFrom($this->params->firma['email'], $this->params->firma['mailfrom']);
      $mail->Subject = $text->get('widerruf', 'betreff');
      $mail->MsgHTML($html);

      if($mail->Send()) {
         unset($_SESSION['captcha']);
         return 'send';
      }

      return 'mailerror';

   }
}