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

// Verschiedene Hilfsfunktionen
class Helper
{
   static private $db              = null;
   static private $db_extern       = null;
   static private $params          = null;
   static private $text            = null;
   static public  $pic_status      = false;
   static public  $is_shopsoftware = '';
   static public  $cat_script      = '';

   public static function init() {
      self::$db        = Control::getDB();
      self::$db_extern = Control::getExternDB();
      self::$text      = Control::getText();
      self::$params    = Control::getParams();
   }

   static public function getFeedbackBox() {
      $cr = "\n";
      $html  = '<div id="feedback_box" style="display:none;">'.CR;
      $html .= '   <div class="feedback_rahmen">'.CR;
      $html .= '   <div class="feedback_close" onclick="Royalart.feedbackClose();"></div>'.CR;
      $html .= '      <div class="txt_tit ueberschrift text_gross" id="feedback_title"></div>'.CR;
      $html .= '      <div class="txt_tit fliesstext text_normal" id="feedback_time"></div>'.CR;

      // Frontend Responsive
      if (defined('CONF_RESPONSIVE') && self::$params->isAdmin === false) {
         $html .='       <div class="col_button bg_button text_gross button55" id="feedback_but" onclick="Royalart.feedbackClose()">'.self::$text->get('button', 'close').'</div>'.CR;
      }

      else {
         $html .= '      <div class="button-grau txt_but feedback_but" id="feedback_but" onclick="Royalart.feedbackClose();">'.self::$text->get('button', 'close').'</div>'.CR;
      }

      $html .= '   </div>'.CR;
      $html .= '</div>'.CR;

      return $html;
   }

   static public function getFeedbackBox2($msg = '', $class = '') {
      $cr = "\n";
      $html  = '<div id="feedback_box2">'.$cr;
      $html .= '   <div class="feedback_rahmen">'.$cr;
      $html .= '      <div class="txt_tit" id="feedback_title2"></div>'.$cr;
      $html .= '      <div id="fb2_msg"'.($class != '' ? ' class="'.$class.'"' : '').' style="display:none">$msg</div>';
      $html .= '      <div class="button-grau txt_but feedback_but" id="feedback_but2" onclick="Royalart.feedbackClose2()">Schließen</div>'.$cr;
      $html .= '   </div>'.$cr;
      $html .= '</div>'.$cr;

      return $html;
   }

   static public function getFeedbackBoxMl() {
      $text = Control::getText();
      $cr = "\n";
      $html  = '<div id="feedback_box_ml" style="display:none;">'.CR;
      $html .= '   <div class="feedback_rahmen">'.CR;
      $html .= '      <div class="wk_delete" onclick="Royalart.feedbackMlClose()"></div>'.$cr;
      $html .= '      <div class="txt_tit ueberschrift text_gross" id="feedback_ml_title"></div>'.CR;
      $html .= '      <div class="txt_tit fliesstext text_normal" id="feedback_ml_time"></div>'.CR;
      $html .= '      <a href="'.SHOP_URL_IDX.'/warenkorb" id="feedback_ml_but" class="col_button bg_button text_gross button55">'.$text->get('menu', 'warenkorb').'</a>'.$cr;
      $html .= '   </div>'.CR;
      $html .= '</div>'.CR;

      return $html;
   }

   static function checkEmail($email, $table = '', $spalte = '', $id = 0) {
      // email leer
      if ($email == '') {
         return 1;
      }

      // Email in DB gefunden
      if ($table != '' && $spalte != '') {
         $old_mail = '';

         if ($id > 0) {
            $old_mail = self::$db->querySingleValue("SELECT `$spalte` FROM `$table` WHERE `id` = $id");
         }

         $data = self::$db->querySingleObject("SELECT `$spalte` FROM `$table` WHERE `$spalte` = '$email'");

         if (is_object($data) && $old_mail != $email) {
            return 3;
         }
      }

      // email OK
      return 0;
   }

   static public function checkFile($name, $lang, $ext) {
      $filename = $name . '_' . $lang . '.' . $ext;

      if (file_exists(TEMPLATE_PATH.'/'.$filename)) {
         return TEMPLATE_URL.'/'.$filename;
      }
      if ($ext == 'swf') {
         return '';
      }
      return TEMPLATE_URL.'/images/system/nopic.png';
   }

   static public function checkImage($image, $path, $link, $default) {
      if (is_file($path.$image)) {
         return $link.$image.(self::$params->firma['use_cache'] == 'n' ? '?'.time() : '');
      }
      return $default;
   }

   static public function truncate($string, $limit = 12, $break = " ", $pad ="...") {
      mb_internal_encoding("UTF-8");
      if(mb_strlen($string) <= $limit) {
         return $string;
      }

      $string = preg_replace('|(\s+)|', ' ', $string);
      $string = mb_substr($string, 0, ($limit));
      return $string.$pad;
   }

   static public function XXX_old_truncate($string, $limit=12, $break=" ", $pad="...") {
      mb_internal_encoding("UTF-8");
      if(mb_strlen($string) <= $limit) {
         return $string;
      }

      $string = preg_replace('|(\s+)|', ' ', $string);
      $string = mb_substr($string, 0, ($limit - strlen($pad)));
      return $string . $pad;
   }

   static public function selected($val1, $val2){
      if($val1 == $val2){
         return ' selected="selected" ';
      }
   }

   // Impressumdaten für Impressum
   static public function getImpressum() {

      if (self::$params->firma['impressum_inhaber'] != "y") {
         return '';
      }

      $html = '<div class="col_single_center80">';
      $html .= '<div class="ki_block_left">';

      $dummy = self::getAnschrift();
      if ($dummy) {
         $html .= $dummy.'<div class="ki_abstand"></div>';
      }

      $dummy = self::getFinanzamt();
      if ($dummy) {
         $html .= $dummy.'<div class="ki_abstand"></div>';
      }

      $html .= '</div>';
      $html .= '<div class="ki_block_right">';

      $dummy = self::getTelefon();
      if ($dummy) {
         $html .= $dummy.'<div class="ki_abstand"></div>';
      }

      $dummy = self::getPaypal();
      if ($dummy) {
         $html .= $dummy.'<div class="ki_abstand"></div>';
      }

      $html .= '</div>';
      $html .= '<div class="clear"></div>';

      $html .= '</div>';
      return $html;
   }


   // Kontaktdaten für Kontakte
   static public function getKontakt() {
      if (self::$params->firma['kontakt_inhaber'] != "y") {
         return '';
      }

//      $html = '<div class="col_single_center80">';
      $html = '   <div class="ki_block_left">';

      $dummy = self::getAnschrift();
      if ($dummy) {
         $html .= $dummy.'      <div class="ki_abstand"></div>';
      }

      $dummy = self::getFinanzamt();
      if ($dummy) {
         $html .= $dummy.'      <div class="ki_abstand"></div>';
      }

      $html .= '   </div>';
      $html .= '   <div class="ki_block_right">';

      $dummy = self::getTelefon();
      if ($dummy) {
         $html .= $dummy.'      <div class="ki_abstand"></div>';
      }

      $dummy = self::getPaypal();
      if ($dummy) {
         $html .= $dummy.'      <div class="ki_abstand"></div>';
      }

      $html .= '   </div>';
      $html .= '   <div class="clear"></div>';
      return $html;
   }


   // Adressdaten für Info-Seiten (Impressum / Kontakt)
   static public function getAnschrift() {
      if (self::$params->firma['shop_name_check'] != 'y' &&
            self::$params->firma['firm_name_check'] != 'y' &&
            self::$params->firma['first_name_check'] != 'y' &&
            self::$params->firma['last_name_check'] != 'y' &&
            self::$params->firma['street_check'] != 'y' &&
            self::$params->firma['postal_code_check'] != 'y' &&
            self::$params->firma['city_check'] != 'y' &&
            self::$params->firma['country_check'] != 'y') {
         return '';
      }

      $check = false;
      $html  = '<div class="ki_block">';
      $html .= '<div class="ki_links">'.self::$text->get('widerruf', 'anschrift').':</div>';
      if (self::$params->firma['shop_name_check'] == 'y') {
         $html .= '<div class="ki_rechts">'.self::$params->firma['shop_name'].'</div>';
         $html .= '</div>';
         $check = true;
      }

      if (self::$params->firma['firm_name_check'] == 'y') {
         if (!$check) {
            $html .= '<div class="ki_rechts">'.self::$params->firma['firm_name'].'</div>';
            $html .= '</div>';
            $check = true;
         }
         else {
            $html .= '<div class="ki_block">';
            $html .= '<div class="ki_links"></div>';
            $html .= '<div class="ki_rechts">'.self::$params->firma['firm_name'].'</div>';
            $html .= '</div>';
         }
      }

      if (self::$params->firma['first_name_check'] == 'y' || self::$params->firma['last_name_check'] == 'y') {
         if (!$check) {
            $html .= '<div class="ki_rechts">';
            if (self::$params->firma['first_name_check'] == 'y') {
               $html .= self::$params->firma['first_name'].' ';
            }
            if (self::$params->firma['last_name_check'] == 'y') {
               $html .= self::$params->firma['last_name'].' ';
            }
            $html .= '</div>';
            $html .= '</div>';
            $check = true;
         }
         else {
            $html .= '<div class="ki_block">';
            $html .= '<div class="ki_links"></div>';
            $html .= '<div class="ki_rechts">';
            if (self::$params->firma['first_name_check'] == 'y') {
               $html .= self::$params->firma['first_name'].' ';
            }
            if (self::$params->firma['last_name_check'] == 'y') {
               $html .= self::$params->firma['last_name'].' ';
            }
            $html .= '</div>';
            $html .= '</div>';
         }
      }

      if (self::$params->firma['street_check'] == 'y') {
         if (!$check) {
            $html .= '<div class="ki_rechts">'.self::$params->firma['street'].' '.self::$params->firma['haus_nr'].'</div>';
            $html .= '</div>';
            $check = true;
         }
         else {
            $html .= '<div class="ki_block">';
            $html .= '<div class="ki_links"></div>';
            $html .= '<div class="ki_rechts">'.self::$params->firma['street'].' '.self::$params->firma['haus_nr'].'</div>';
            $html .= '</div>';
         }
      }

      if (self::$params->firma['postal_code_check'] == 'y' || self::$params->firma['city_check'] == 'y') {
         if (!$check) {
            $html .= '<div class="ki_rechts">';
            if (self::$params->firma['postal_code_check'] == 'y') {
               $html .= self::$params->firma['postal_code'].' ';
            }
            else {
               $html .= "&nbsp;";
            }
            if (self::$params->firma['city_check'] == 'y') {
               $html .= self::$params->firma['city'].' ';
            }
            else {
               $html .= "&nbsp;";
            }
            $html .= '</div>';
            $html .= '</div>';
            $check = true;
         }
         else {
            $html .= '<div class="ki_block">';
            $html .= '<div class="ki_links"></div>';
            $html .= '<div class="ki_rechts">';
            if (self::$params->firma['postal_code_check'] == 'y') {
               $html .= self::$params->firma['postal_code'].' ';
            }
            else {
               $html .= "&nbsp;";
            }
            if (self::$params->firma['city_check'] == 'y') {
               $html .= self::$params->firma['city'].' ';
            }
            else {
               $html .= "&nbsp;";
            }
            $html .= '</div>';
            $html .= '</div>';
         }

      }

      if (self::$params->firma['country_check'] == 'y') {
         if (!$check) {
            $html .= '<div class="ki_rechts">'.self::$params->firma['country'].'</div>';
            $html .= '</div>';
            $check = true;
         }
         else {
            $html .= '<div class="ki_block">';
            $html .= '<div class="ki_links"></div>';
            $html .= '<div class="ki_rechts">'.self::$params->firma['country'].'</div>';
            $html .= '</div>';
         }
      }

      if (!$check) {
         $html .= '<div class="ki_rechts"></div>';
         $html .= '</div>';
      }
      return $html;
   }


   // Telefondaten für Infoseiten (Impressum / Kontakt)
   static public function getTelefon() {
      if (self::$params->firma['telefon_check'] != 'y' &&
            self::$params->firma['fax_check'] != 'y' &&
            self::$params->firma['email_check'] != 'y' &&
            self::$params->firma['web_check'] != 'y') {
         return '';
      }

      $html = '';
      if (self::$params->firma['telefon_check'] == 'y') {
         $html .= '<div class="ki_block">';
         $html .= '<div class="ki_links">'.self::$text->get('agb', 'tel').':</div>';
         $html .= '<div class="ki_rechts">'.self::$params->firma['telefon'].'&nbsp;</div>';
         $html .= '</div>';
      }

      if (self::$params->firma['fax_check'] == 'y') {
         $html .= '<div class="ki_block">';
         $html .= '<div class="ki_links">'.self::$text->get('agb', 'fax').':</div>';
         $html .= '<div class="ki_rechts">'.self::$params->firma['fax'].'&nbsp;</div>';
         $html .= '</div>';
      }

      if (self::$params->firma['email2_check'] == 'y') {
         $html .= '<div class="ki_block">';
         $html .= '<div class="ki_links">'.self::$text->get('widerruf', 'email').':</div>';

         if (preg_match( '/^([a-z0-9]+([-_\.]?[a-z0-9])+)@[a-z0-9äöü]+([-_\.]?[a-z0-9äöü])+\.[a-z]{2,10}$/i', self::$params->firma['email2'])) {
            $html .= '<div class="ki_rechts"><a href="mailto:'.self::$params->firma['email2'].'">'.self::$params->firma['email2'].'&nbsp;</a></div>';
         }

         else {
            $html .= '<div class="ki_rechts">'.self::$params->firma['email2'].'&nbsp;</div>';
         }
         $html .= '</div>';
      }

      if (self::$params->firma['web_check'] == 'y' && self::$params->firma['web'] != '') {
         self::$params->firma['web'] = (strpos(self::$params->firma['web'], 'http') === false ? 'http://' : '').self::$params->firma['web'];

         $html .= '<div class="ki_block">';
         $html .= '<div class="ki_links">'.self::$text->get('agb', 'web').':</div>';
         $html .= '<div class="ki_rechts"><a href="'.self::$params->firma['web'].'">'.str_replace(['http://', 'https://'], '', self::$params->firma['web']).'&nbsp;</a></div>';
         $html .= '</div>';
      }

      $html .= '<div class="clear"></div>';
      return $html;
   }


   // PayPal-Daten für Info-Seiten (Impressum / Kontakt)
   static public function getPaypal() {
      if (self::$params->firma['paypal_mail_check'] != 'y' &&
            self::$params->firma['email_check'] != 'y' &&
            self::$params->firma['web_check'] != 'y') {
         return '';
      }

      $html = '';
      if (self::$params->firma['paypal_mail_check'] == 'y') {
         $html .= '<div class="ki_block">';
         $html .= '<div class="ki_links">'.self::$text->get('agb', 'ppemail').':</div>';
         $html .= '<div class="ki_rechts">'.self::$params->firma['paypal_mail'].'&nbsp;</div>';
         $html .= '</div>';
      }

      if (self::$params->firma['email_check'] == 'y') {
         $html .= '<div class="ki_block">';
         $html .= '<div class="ki_links">'.self::$text->get('agb', 'shopemail').':</div>';

         if (preg_match( '/^([a-z0-9]+([-_\.]?[a-z0-9])+)@[a-z0-9äöü]+([-_\.]?[a-z0-9äöü])+\.[a-z]{2,10}$/i', self::$params->firma['email'])) {
            $html .= '<div class="ki_rechts"><a href="mailto:'.self::$params->firma['email'].'">'.self::$params->firma['email'].'&nbsp;</a></div>';
         }
         else {
            $html .= '<div class="ki_rechts">'.self::$params->firma['email'].'&nbsp;</div>';
         }
         $html .= '</div>';
      }

      return $html;
   }


   // FA-Daten für Info-Seiten (Impressum / Kontakt)
   static public function getFinanzamt() {
      if (self::$params->firma['finanzamt_check'] != 'y' &&
            self::$params->firma['steuernr_check'] != 'y' &&
            self::$params->firma['ustid_check'] != 'y') {
         return '';
      }

      $html = '';

      if (self::$params->firma['finanzamt_check'] == 'y') {
         $html .= '<div class="ki_block">';
         //$html .= '<div class="ki_links">Finanzamt:</div>';
         $html .= '<div class="ki_links">'.self::$text->get('shop', 'finanzamt').'</div>';
         $html .= '<div class="ki_rechts">'.self::$params->firma['finanzamt'].'&nbsp;</div>';
         $html .= '</div>';
         $html .= '<div class="clear></div>';
      }

      if (self::$params->firma['steuernr_check'] == 'y') {
         $html .= '<div class="ki_block">';
//         $html .= '<div class="ki_links">St.-Nr.:</div>';
         $html .= '<div class="ki_links">'.self::$text->get('shop', 'steuernr').':</div>';
         $html .= '<div class="ki_rechts">'.self::$params->firma['steuernr'].'&nbsp;</div>';
         $html .= '</div>';
      }

      if (self::$params->firma['ustid_check'] == 'y') {
         $html .= '<div class="ki_block">';
//         $html .= '<div class="ki_links">USt-IdNr.:</div>';
         $html .= '<div class="ki_links">'.self::$text->get('shop', 'ustid').':</div>';
         $html .= '<div class="ki_rechts">'.self::$params->firma['ustid'].'&nbsp;</div>';
         $html .= '</div>';
      }

      return $html;
   }


   // Bankverbindung für Info-Seiten (Impressum / Kontakt)
   static public function getBank() {
      if (self::$params->firma['bank1_check'] != 'y') {
         return '';
      }

      $html = '';

      $html .= '<div class="ki_block">';
      $html .= '<div class="ki_links">Bankinstitut:</div>';
      $html .= '<div class="ki_rechts">'.self::$params->firma['bank1'].'&nbsp;</div>';
      $html .= '</div>';

      if (self::$params->firma['bank1_iban_check'] == 'y') {
         $html .= '<div class="ki_block">';
         $html .= '<div class="ki_links">IBAN:</div>';
         $html .= '<div class="ki_rechts">'.self::$params->firma['bank1_iban'].'&nbsp;</div>';
         $html .= '</div>';
      }

      if (self::$params->firma['bank1_bic_check'] == 'y') {
         $html .= '<div class="ki_block">';
         $html .= '<div class="ki_links">BIC:</div>';
         $html .= '<div class="ki_rechts">'.self::$params->firma['bank1_bic'].'&nbsp;</div>';
         $html .= '</div>';
      }

      if (self::$params->firma['bank1_inhaber_check'] == 'y') {
         $html .= '<div class="ki_block">';
         $html .= '<div class="ki_links">Inhaber:</div>';
         $html .= '<div class="ki_rechts">'.self::$params->firma['bank1_inhaber'].'&nbsp;</div>';
         $html .= '</div>';
      }

      return $html;
   }


   // Grafiktyp feststellen
   static public function getExtension($file) {
      $test     = explode(".", $file);
      $filetype = strtolower(end($test));

      if ($filetype == 'jpeg') {
         $filetype = 'jpg';
      }
      return $filetype;
   }

   // Funktion zum Bearbeiten von Bildern
   // $orgfile     -> Dateiname mit Pfad des Originals
   // $newfile     -> Dateiname mit Pfad des neuen Bilds
   // $breite      -> Breite des neuen Bilds (0 oder breite neues Bild)
   // $hoehe       -> Höhe des neuen Bilds (0 oder hoehe neues Bild)
   // $new_ext     -> (optional) Bildtyp des neuen Bilds default -> Typ des Originals wird beibehalten
   // $delete      -> (optional) true (default): Original löschen , false: Original behalten
   // $schneiden   -> (optional) true: Wenn maxbreite/hoehe überschritten wird Bild beschneiden (proportional), false (default):
   // $align_left  -> (optional) Am linken Rand ausrichten
   // $maxbreite   -> (optional) Bild wird auf maxbreite skaliert (wenn breite 0)
   // $maxhoehe    -> (optional) Bild wird auf maxhoehe skaliert (wenn hoehe 0)
   // $transparent -> (optional) false (default) nichts, true: Original kein png, neu png -> weiß als transparente Farbe verwenden
   //
   // Wird ein optionaler Parameter gesetzt, müssen die optionalen Parameter davor auch gesetzt werden!
   //
   // Rückgabe: Array(breite, hoehe neues Bild)
   //
   static public function imageResize($orgfile, $newfile, $breite, $hoehe, $new_ext = '', $delete = true, $schneiden = false, $align_left = false, $maxbreite = 0, $maxhoehe = 0, $transparent = false, $background = false) {
      $faktor = 1.0;
      $resize = false;

      $offset_x     = 0;
      $offset_y     = 0;
      // Falls Original < Neues Bild
      $breite_neu   = $breite;
      $hoehe_neu    = $hoehe;
      $offset_neu_x = 0;
      $offset_neu_y = 0;

      // Breite Höhe und Typ des Originals ermitteln
      list($breite_org, $hoehe_org, $typ) = getimagesize($orgfile);

      // Dateityp feststellen für neues Bild
      $extension = self::_imageResizeFiletyp($typ);
      if ($new_ext == 'auto') {
         $newfile .= '.'.$extension;
         $new_ext = $extension;
      }

      // Nur Bildtyp umwandeln
      if ($breite == 0 && $hoehe == 0 || $breite > $breite_org && $hoehe == 0 || $hoehe > $hoehe_org && $breite == 0) {
            $breite     = $breite_org;
            $breite_neu = $breite_org;
            $hoehe      = $hoehe_org;
            $hoehe_neu  = $hoehe_org;

            if ($maxbreite != 0 && $breite > $maxbreite) {
               $faktor = $breite_org / $maxbreite;
               $breite = $maxbreite;
               $breite_neu = $breite;
               $hoehe = $hoehe / $faktor;
               $hoehe_neu = $hoehe;
            }

            else if ($maxhoehe != 0 && $hoehe > $maxhoehe) {
               $faktor = $hoehe_org / $maxhoehe;
               $hoehe = $maxhoehe;
               $hoehe_neu = $hoehe;
               $breite = $breite / $faktor;
               $breite_neu = $breite;
            }
      }

      // Original < Vorgaben
      else if ($hoehe != 0 && $hoehe >= $hoehe_org && $breite != 0 && $breite >= $breite_org) {
         if ($schneiden) {
            // Bildgröße nicht ändern
            $breite     = $breite_org;
            $breite_neu = $breite_org;
            $hoehe      = $hoehe_org;
            $hoehe_neu  = $hoehe_org;
         }
         else {
            // Neues Bild, Originl darin zentriert mit Rand
            $offset_neu_x = (int)round(($breite - $breite_org) / 2);
            $offset_neu_y = (int)round(($hoehe - $hoehe_org) / 2);
            $breite = $breite_org;
            $hoehe = $hoehe_org;
         }
      }

      // Breite vorgegeben
      else if ($breite != 0 && $hoehe == 0) {
         // Höhen berechnen
         $faktor     = $breite / $breite_org;
         $hoehe = (int)round($hoehe_org * $faktor);
         $hoehe_neu = $hoehe;

         // Wird maxhoehe überschritten?
         if ($maxhoehe != 0 && $hoehe > $maxhoehe) {
            $faktor     = $hoehe / $maxhoehe;
            $hoehe_neu  = $maxhoehe;
            $hoehe      = $maxhoehe;
            $breite_neu = (int)round($breite_neu / $faktor);
            $breite     = (int)round($breite / $faktor);
         }
      }

      // Höhe vorgegeben
      else if ($breite == 0 && $hoehe != 0) {
         // Breiten berechnen
         $faktor     = $hoehe / $hoehe_org;
         $breite = (int)round($breite_org * $faktor);
         $breite_neu = $breite;

         // Wird maxhoehe überschritten?
         if ($maxbreite != 0 && $breite > $maxbreite) {
            $faktor     = $breite / $maxbreite;
            $breite_neu = $maxbreite;
            $breite     = $maxbreite;
            $hoehe_neu  = (int)round($hoehe_neu / $faktor);
            $hoehe      = (int)round($hoehe / $faktor);
         }
      }

      // Breite und Höhe vorgegeben
      else {
         // Skalierungsfaktoren berechnen. Wenn Original > Neues Image sind Faktoren > 1
         $faktor_x = $breite_org / $breite;
         $faktor_y = $hoehe_org / $hoehe;

         // Test, ob Original in Höhe und Breite >= Neues Bild ist
         if ($faktor_x >= 1 && $faktor_y >= 1) {
            // Bild auf Höhe skalieren und Breite beschneiden
            if ($faktor_x > $faktor_y) {
               if ($schneiden) {
                  $offset_x = (int)round(($breite_org - $breite) / $faktor_y / 2);
                  $breite_org = $breite * $faktor_y;
               }
               // Bild mit Rand oben und unten
               else {
                  $offset_neu_y = (int)round(($hoehe - $hoehe_org / $faktor_x)  / 2);
                  $hoehe = $hoehe_org / $faktor_x;
               }
            }

            // Bild auf Breite skalieren und Höhe beschneiden
            else if ($faktor_x < $faktor_y) {
               if ($schneiden) {
                  $offset_y = (int)round(($hoehe_org - $hoehe) / $faktor_x / 2);
                  $hoehe_org = (int)($hoehe * $faktor_x);
               }
               // Bild mit Rand links und rechts
               else {
                  $offset_neu_x = (int)round(($breite - $breite_org / $faktor_y) / 2);
                  $breite = $breite_org / $faktor_y;
               }
            }
         }

         else {
            // Höhe zu gering, Breite anpassen
            if ($faktor_x >= 1 && $faktor_y < 1) {
               $hoehe        = (int)round($hoehe_org / $faktor_x);
               $offset_neu_y = (int)round(($hoehe_neu - $hoehe) / 2);
               $breite       = (int)round($breite_org / $faktor_x);
               if ($schneiden) {
                  $hoehe_neu = $hoehe;
                  $offset_neu_y = 0;
               }
            }

            // Breite zu gering, Höhe anpassen
            else if ($faktor_x < 1 && $faktor_y >= 1) {
               $breite       = (int)round($breite_org / $faktor_y);
               $offset_neu_x = (int)round(($breite_neu - $breite) / 2);
               $hoehe        = (int)round($hoehe_org  / $faktor_y);
               if ($schneiden) {
                  $breite_neu = $breite;
                  $offset_neu_x = 0;
               }
            }
         }
      }

      // Am linken Rand ausrichten?
      if ($align_left) {
//         $offset_neu_x = 0;
      }

      // Neues Image aus orgfile erstellen
      $image = null;
      switch($extension) {
         case 'png':
            $image = imagecreatefrompng($orgfile);
            break;

         case 'jpg':
            $image = imagecreatefromjpeg($orgfile);
            break;

         case 'gif':
            $image = imagecreatefromgif($orgfile);
            break;

         default:
            return false;
      }

      $new_image  = imagecreatetruecolor(($breite_neu > 1 ? $breite_neu : 1), ($hoehe_neu > 1 ? $hoehe_neu : 1));
      // Neues Image mit weißem Hintergrund in neuer Größe erzeugen
      if ($transparent && $new_ext == 'png') {
         imagefill ($new_image, 0, 0, imagecolorallocatealpha($new_image, 255, 255, 255, 127));
         imagealphablending($new_image, false);
         imagesavealpha($new_image, true);
      }

      else {
         if ($background === false) {
            imagefill ($new_image, 0, 0, imagecolorallocate($new_image, 255, 255, 255));
         }
         else {
            imagefill ($new_image, 0, 0, imagecolorallocate($new_image, $background[0], $background[1], $background[2]));
         }
      }

      imagecopyresampled($new_image, $image, $offset_neu_x, $offset_neu_y, $offset_x, $offset_y, $breite, $hoehe, $breite_org, $hoehe_org);

      switch($new_ext) {
         case 'png':
            imagepng($new_image, $newfile);
            break;

         case 'jpg':
            imagejpeg($new_image, $newfile);
            break;

         case 'gif':
            imagegif($new_image, $newfile);
            break;
      }

      imagedestroy($new_image);
      imagedestroy($image);

      return [$breite, $hoehe];
   }

   // Seiten/Danke-Seite, Module html
   static public function resizePic($oldfile, $newfile, $breite = 0, $hoehe = 0, $newext = 'png', $delete = true) {
      $resize = false;
      $im     = null;
      list($breite_old, $hoehe_old, $typ) = getimagesize($oldfile);

      if ($breite_old > $breite) {
         $hoehe = floor($hoehe_old * $breite / $breite_old);
         $resize = true;
      }
      else {
         $hoehe = $hoehe_old;
         $breite = $breite_old;
      }

      if ($breite != 0 && $hoehe != 0) {
         $new_im = imagecreatetruecolor($breite, $hoehe);

         switch($typ) {
            case 3:
               if ($resize || $newext != 'png') {
                  $im = imagecreatefrompng($oldfile);
                  $resize = true;
               }

               break;

            case 2:
               if ($resize || $newext != 'jpg') {
                  $im = imagecreatefromjpeg($oldfile);
                  $resize = true;
               }

               break;

            case 1:
               if ($resize || $newext != 'gif') {
                  $im = imagecreatefromgif($oldfile);
                  $resize = true;
               }

               break;
         }

         if ($resize) {
            imagecopyresampled($new_im, $im, 0, 0, 0, 0, $breite, $hoehe, $breite_old, $hoehe_old);

            switch($newext) {
               case 'png':
                  imagepng($new_im, $newfile);
                  break;

               case 'jpg':
                  imagejpeg($new_im, $newfile);
                  break;

               case 'gif':
                  imagegif($new_im, $newfile);
                  break;
            }

            unset($im);
         }

         else {
            copy($oldfile, $newfile);
         }
      }

      else {
         copy($oldfile, $newfile);
      }

      if ($delete) {
         unlink($oldfile);
      }

      return $hoehe;
   }

   // Neu 14.11.2018: Details/Slideshow-Fullscreen auf Verhältnis $breite/$hoehe proportional anpassen, kein Rand mehr
   static public function resizeImageSlideshow($oldfile, $newfile, $breite, $hoehe, $delete = true) {
      $img_typ    = 0;
      $breite_neu = 0;
      $hoehe_neu  = 0;
      $offset_x   = 0;
      $offset_y   = 0;

      list($breite_org, $hoehe_org, $img_typ) = getimagesize($oldfile);

      $faktor     = $breite / $hoehe;
      $faktor_org = $breite_org / $hoehe_org;

      // Bild im richtigen Verhältnis, nicht zu breit und jpg
      if ($faktor == $faktor_org && $breite_org <= $breite && $img_typ == 2) {
         if ($delete == true) {
            rename ($oldfile, $newfile);
         }

         else {
            copy ($oldfile, $newfile);
         }

         return;
      }

      // Original zu hoch, Höhe beschneiden
      if ($faktor > $faktor_org) {
         $breite_neu = ($breite < $breite_org ? $breite : $breite_org);
         $hoehe_neu  = round($breite_neu / $faktor);
         $offset_y   = round(($hoehe_org - $hoehe_neu) / 2);
      }

      else {
         $hoehe_neu  = ($hoehe < $hoehe_org ? $hoehe : $hoehe_org);
         $breite_neu = round($hoehe_neu * $faktor);
         $offset_y   = round(($breite_org - $breite_neu) / 2);
      }

      if ($breite != 0 && $hoehe != 0) {
         $new_im = imagecreatetruecolor($breite_neu, $hoehe_neu);

         switch($img_typ) {
            case 3:  // PNG
               $im = imagecreatefrompng($oldfile);
               break;

            case 2:  // JPG
               $im = imagecreatefromjpeg($oldfile);
            break;

            case 1:  // GIF
               $im = imagecreatefromgif($oldfile);
               break;
         }

         imagecopyresampled($new_im, $im, 0, 0, $offset_x, $offset_y, $breite_neu, $hoehe_neu, $breite_org - 2 * $offset_x, $hoehe_org - 2 * $offset_y);
         imagejpeg($new_im, $newfile);

         if ($delete) {
            unlink($oldfile);
         }

         return $hoehe_neu;
      }

      return 0;
   }

   // Design / Kategorien / Seiten
   static public function resizePicCenter($orgfile, $newfile, $breite, $hoehe, $new_ext = 'jpg', $delete = true, $background = false) {
      // Breite und Höhe des Originals holen
      list($breite_org, $hoehe_org, $typ) = getimagesize($orgfile);
//      $extension = self::_imageResizeFiletyp($typ);

      // Proportional
      if ($hoehe == 0) {
         $hoehe = $hoehe_org / $breite_org * $breite;
      }

//      if ($new_ext == 'auto') {
//         $newfile .= '.'.$extension;
//      }

      // Skalierungsfaktoren berechnen. Wenn Original > Neues Image sind Faktoren > 1
      $faktor_x = $breite_org / $breite;     // >1: Bild breiter als Vorgabe: <1: Bild schmäler als Vorgabe
      $faktor_y = $hoehe_org / $hoehe;       // >1: Bild höher als Vorgabe: <1: Bild niedriger als Vorgabe

      // Werte für imagecopyresampled
      $offset_neu_x = 0;
      $offset_neu_y = 0;
      $offset_org_x = 0;
      $offset_org_y = 0;
      $breite_neu   = $breite;
      $hoehe_neu    = $hoehe;
      $breite_src   = $breite_org;
      $hoehe_src    = $hoehe_org;

      // Portrait - auf Höhe skalieren und Ränder li/re
      if ($faktor_y > 1 && $hoehe_org >= $breite_org && $background !== false) {
         $offset_neu_x = round(($breite_neu - ($breite_org / $faktor_y)) / 2);
         $breite_neu = $breite_org / $faktor_y;
         $hoehe_neu = $hoehe_org / $faktor_y;
         $offset_neu_x = round(($breite - $breite_neu) / 2);
      }

      // Test, ob Original in Höhe und Breite >= Neues Bild ist
      else if ($faktor_x >= 1 && $faktor_y >= 1) {
         // Bild auf Höhe skalieren und Breite beschneiden
         if ($faktor_x > $faktor_y) {
            // Breite aus Original im Verhältnis zur Höhe
            $breite_src = $breite * $faktor_y;
            // Verschiebung Ausschnitt nach rechts
            $offset_org_x = round(($breite_org - $breite_src) / 2);
         }
         // Bild auf Breite skalieren und Höhe beschneiden
         else if ($faktor_x < $faktor_y) {
            $hoehe_src = $hoehe * $faktor_x;
            $offset_org_y = round(($hoehe_org - $hoehe_src) / 2);
         }
         // Bild nur skalieren
         else {
            // nichts tun
         }
      }

      // Höhe zu gering, Breite anpassen
      else if ($faktor_x >= 1 && $faktor_y < 1) {
         // Original Offset links
         $offset_org_x = ($breite_org - $breite) / 2;
         // Breite aus Vorgabe
         $breite_src   = $breite;
         // Offset von oben neues Bild
         $offset_neu_y = ($hoehe - $hoehe_org) / 2;
         // Höhe Original
         $hoehe_src = $hoehe_org;
         $hoehe_neu = $hoehe_org;
      }

      // Breite zu gering, Höhe anpassen
      else if ($faktor_x < 1 && $faktor_y >= 1) {
         // Original Offset oben
         $offset_org_y = ($hoehe_org - $hoehe) / 2;
         // Höhe aus Vorgabe
         $hoehe_src   = $hoehe;
         // Offset von links neues Bild
         $offset_neu_x = ($breite - $breite_org) / 2;
         // Breite Original
         $breite_src = $breite_org;
         $breite_neu = $breite_org;
      }

      // Beide Seiten zu klein
      else {
         $hoehe_src = $hoehe_org;
         $hoehe_neu = $hoehe_org;
         $breite_src = $breite_org;
         $breite_neu = $breite_org;
         // Offset von links neues Bild
         $offset_neu_x = ($breite - $breite_org) / 2;
         // Offset von oben neues Bild
         $offset_neu_y = ($hoehe - $hoehe_org) / 2;
      }

      // Neues Image aus orgfile erstellen
      $image = null;

//      switch(strtolower($extension)) {
      switch($typ) {
//         case 'png':
         case IMAGETYPE_PNG:
            $image = imagecreatefrompng($orgfile);
            break;

//         case 'jpg':
         case IMAGETYPE_JPEG:
            $image = imagecreatefromjpeg($orgfile);
            break;

//         case 'gif':
         case IMAGETYPE_GIF:
            $image = imagecreatefromgif($orgfile);
            break;
      }

      // Neues Image mit weißem/$background Hintergrund in neuer Größe erzeugen
      $new_image  = imagecreatetruecolor($breite, $hoehe);

      if ($background === false) {
         ImageAlphaBlending($new_image, false);
         imagefill ($new_image, 0, 0, imagecolorallocatealpha ($new_image, 255, 255, 255, 127));
         ImageSaveAlpha($new_image, true);
      }

      else {
         imagefill ($new_image, 0, 0, imagecolorallocate($new_image, 255, 255, 255));
      }

      // Bild neu erstellen
      imagecopyresampled($new_image, $image, $offset_neu_x, $offset_neu_y, $offset_org_x, $offset_org_y, $breite_neu, $hoehe_neu, $breite_src, $hoehe_src);

      // und speichern als ...
      switch ($new_ext) {
         case 'jpg':
            imagejpeg($new_image, $newfile);
            break;

         case 'png':
            imagepng($new_image, $newfile);
            break;
      }

      imagedestroy($new_image);
      imagedestroy($image);
   }

   public static function imageCutAndCenterWidthOrHight($image, $x, $y) {
      list($breite, $hoehe, $imagetype) = getimagesize($image);

      if (($x >= $breite && $y >= $hoehe) || ($x == 0 && $y >= $hoehe) || ($y == 0 && $x >= $breite)) {
         return;
      }

      $breite_neu = $breite;
      $hoehe_neu  = $hoehe;
      $offset_x   = 0;
      $offset_y   = 0;

      if ($x > 0 && $breite > $x) {
         $offset_x = round(($breite - $x) / 2);
         $breite_neu = $x;
      }

      if ($y > 0 && $hoehe > $y) {
         $offset_y = round(($hoehe - $y) / 2);
         $hoehe_neu = $y;
      }

      $im     = null;
      $new_im = imagecreatetruecolor($breite_neu, $hoehe_neu);

      switch($imagetype) {
         case IMAGETYPE_PNG:
            $im = @imagecreatefrompng($image);
            imagecopyresampled($new_im, $im, 0, 0, $offset_x, $offset_y, $breite_neu, $hoehe_neu, $breite - 2 * $offset_x, $hoehe - 2 * $offset_y);
            imagepng($new_im, $image);
            break;

         case IMAGETYPE_JPEG:
            $im = @imagecreatefromjpeg($image);
            imagecopyresampled($new_im, $im, 0, 0, $offset_x, $offset_y, $breite_neu, $hoehe_neu, $breite - 2 * $offset_x, $hoehe - 2 * $offset_y);
            imagejpeg($new_im, $image);
            break;

         case IMAGETYPE_GIF:
            $im = @imagecreatefromgif($image);
            imagecopyresampled($new_im, $im, 0, 0, $offset_x, $offset_y, $breite_neu, $hoehe_neu, $breite - 2 * $offset_x, $hoehe - 2 * $offset_y);
            imagejpeg($new_im, $image);
            break;

         default:
      }

      unset($im);
      unset($new_im);
   }

   // Module html
   public static function resizeSlider($orgfile, $newfile, $breite = 0, $hoehe = 0) {
      $resize = false;
      list($breite_org, $hoehe_org, $typ) = getimagesize($orgfile);

      // Nur Format wandeln
      if ($breite == 0 && $hoehe == 0) {
         if ($typ == IMAGETYPE_PNG) {
            return;
         }
         $breite = $breite_org;
         $hoehe = $hoehe_org;
      }

      // Bildgröße anpassen?
      else if ($hoehe_org > $hoehe) {
         $breite = floor($breite_org * $hoehe / $hoehe_org);
         $resize = true;
      }

      else {
         $hoehe = $hoehe_org;
         $breite = $breite_org;
      }

      //
      if ($breite != 0 && $hoehe != 0) {
         $new_im = imagecreatetruecolor($breite, $hoehe);
         imagealphablending($new_im, false);
         imagesavealpha($new_im, true);

         switch($typ) {
            case IMAGETYPE_PNG:
                  $im = @imagecreatefrompng($orgfile);
                  $resize = true;
               break;

            case IMAGETYPE_JPEG:
                  $im = @imagecreatefromjpeg($orgfile);
                  $resize = true;
               break;

            case IMAGETYPE_GIF:
                  $im = @imagecreatefromgif($orgfile);
                  $resize = true;
               break;
         }

         if ($im === false) {
            return false;
         }

         if ($resize) {
            imagecopyresampled($new_im, $im, 0, 0, 0, 0, $breite, $hoehe, $breite_org, $hoehe_org);
            imagepng($new_im, $newfile);

            unset($im);
            unset($new_im);
         }

         else {
            copy($orgfile, $newfile);
         }
      }
      else {
         copy($orgfile, $newfile);
      }
      return $hoehe;
   }

   // $dir: Verzeichnis Originaldatei mit / am Ende
   // $filename: Dateiname ohne Extension
   // $extension; png, jpg oder gif
   // $parent -> nicht benutzt
   // $pictnr -> Bei 1 Thumbnail für Artikelliste erstellen
   // $make_thumbs: false -> nur Bild erstellen
   static public function makeThumbnails($dir, $filename, $extension, $parent, $pictnr, $make_thumbs = true) {
      // Original kopieren, wird später in der Größe geändert
      copy($dir.'original/'.$filename . "." . $extension, $dir.$filename . "." . $extension);

      if ($make_thumbs) {
         // Thumbs _td Detailseite (150x150px) erstellen
         Helper::makeThumbSmall($dir, $filename, $extension);
         // Cursor _cur Detailseite (150x150px) erstellen
         self::makeCursor($dir, $filename, 'jpg', '_cur');

         // Thumbs Artikelliste (215x162px) erstellen für Artikelvorschau, nur 1. Bild
         if ($pictnr == 1) {
            Helper::makeThumbStart($dir, $filename, $extension);
         }
      }

      //Hover-Bild
      if ($pictnr == 0) {
         Helper::makeThumbStart($dir, $filename, $extension);
      }

      // Deteilbild erstellen
      $template_id = 1;

      if (defined('CONF_TEMPLATE_ID')) {
         $template_id = CONF_TEMPLATE_ID;
      }

      list($breite, $hoehe, $real_ext) = getimagesize($dir.$filename . "." . $extension);
      // Größe max CONF_MAX_SIZE px - Hoehe oder Breite
      $faktor = 1;
      $conf_max_size = CONF_MAX_SIZE;

      if ($template_id == 2 && isset(self::$params->firma['max_width']) && self::$params->firma['max_width'] > 0) {
         $conf_max_size = ceil(self::$params->firma['max_width'] * 0.49);
         $conf_max_size = 800;
      }

      if ($template_id == 1) {
         if ($hoehe >= $breite && $hoehe > CONF_MAX_SIZE) {
            $faktor = $hoehe / CONF_MAX_SIZE;
         }
         elseif ($breite > $hoehe && $breite > CONF_MAX_SIZE) {
            $faktor = $breite / CONF_MAX_SIZE;
         }
      }

      if ($template_id == 2) {
         if ($breite > $conf_max_size) {
            $faktor = $breite / $conf_max_size;
         }
      }

      if ($faktor != 1) {
         $breite_neu = floor($breite / $faktor);
         $hoehe_neu  = floor($hoehe / $faktor);
         $new_im     = imagecreatetruecolor($breite_neu, $hoehe_neu);
         $im         = null;

//         switch(strtolower($extension)) {
         switch($real_ext) {
//            case 'png':
            case IMAGETYPE_PNG:
               $im   = imagecreatefrompng($dir.$filename . "." . $extension);
               $test = imagecopyresampled($new_im, $im, 0, 0, 0, 0, $breite_neu ,$hoehe_neu, $breite, $hoehe);
               $test = imagepng($new_im, $dir.$filename . "." . $extension);
               break;

//            case 'jpg':
            case IMAGETYPE_JPEG:
               $im   = imagecreatefromjpeg($dir.$filename . "." . $extension);
               $test = imagecopyresampled($new_im, $im, 0, 0, 0, 0, $breite_neu, $hoehe_neu, $breite, $hoehe);
               $test = imagejpeg($new_im, $dir.$filename . "." . $extension);
               break;

//            case 'gif':
            case IMAGETYPE_GIF:
               $im   = imagecreatefromgif($dir.$filename . "." . $extension);
               $test = imagecopyresampled($new_im, $im, 0, 0, 0, 0, $breite_neu, $hoehe_neu, $breite, $hoehe);
               $test = imagegif($new_im, $dir.$filename . "." . $extension);
               break;
         }

         imagejpeg($new_im, $dir.$filename . ".jpg");
         unset($im);
         unset($new_im);
      }
   return true;
   }

   static public function makeThumbStart($dir, $filename, $extension) {
      $template_id = 1;

      if (defined('CONF_TEMPLATE_ID')) {
         $template_id = CONF_TEMPLATE_ID;
      }

      // Template Beauty
      if ($template_id == 1) {
         self::makeThumbStartOld($dir, $filename, $extension, '_tn');
      }

      // Template Fullscreen
      else {
         // Thumb _tn in der Höhe begrenzt, Breite fest
         self::makeThumbStartFix($dir, $filename, $extension, '_tn');
      }

      // Beide Templates zusätzlich Thumbs proportional _tp, Breite fest
      // klein / normal
      self::makeThumbStartProp($dir, $filename, $extension, '_tp');
      // gross / riesig
//      self::makeThumbStartProp($dir, $filename, $extension, $ext = '_tb');
   }

   // Nur Template Beauty
   // Erstellt Thumbnail 'filename'.'ext'.extension mit Größe size_x x size_y aus 'filename'.'extension' im Verzeichnis uploaddir
   static public function DELmakeThumbStartOld($uploaddir, $filename, $extension, $ext = '_tn') {
      list($breite, $hoehe) = getimagesize($uploaddir.$filename . "." . $extension);
      $im1        = '';
      $hoehe_alt  = $hoehe;
      $breite_alt = $breite;
      $offset_x   = 0;
      $offset_y   = 0;

         $offset_x = 0;
         $links = 0;
         $faktor = $hoehe / CONF_THUMB_Y;

         $breite_neu = ceil($breite / $faktor);
         if ($breite_neu > CONF_THUMB_X) {
            $offset_x = ceil(($breite - CONF_THUMB_X * $faktor) / 2);
            $breite_neu = CONF_THUMB_X;
         }

         $breite_alt = ceil($breite - 2 * $offset_x);

         switch(strtolower($extension)) {
            case 'png':
               $im1 = imagecreatefrompng($uploaddir.$filename . "." . $extension);
               break;

            case 'jpg':
               $im1 = imagecreatefromjpeg($uploaddir.$filename . "." . $extension);
               break;

            case 'gif':
               $im1 = imagecreatefromgif($uploaddir.$filename . "." . $extension);
               break;
         }

         $new_im1 = imagecreatetruecolor($breite_neu, CONF_THUMB_Y);
         imagecopyresampled($new_im1, $im1, 0, 0, $offset_x, $offset_y, $breite_neu, CONF_THUMB_Y, $breite_alt, $hoehe_alt);
         imagejpeg($new_im1, $uploaddir.$filename . "_tn.jpg");
         unset($im1);
         unset($new_im1);
      return true;
   }

   // Thumpnail Startbild Feste Größe
   static public function makeThumbStartFix($dir, $filename, $extension, $ext = '_tn') {
      // Höhe wird bei Eingabe berechnet (admin/design.class.php)
      $size_x = ((int)self::$params->firma['thumb_width'] > 0 ?(int)self::$params->firma['thumb_width'] : CONF_THUMB_X);
      $size_y = ((int)self::$params->firma['thumb_height'] > 0 ? (int)self::$params->firma['thumb_height'] : CONF_THUMB_Y);
      list($image_x, $image_y, $image_typ) = getimagesize($dir.$filename . "." . $extension);

      $zoom = 1.0;

      if (defined('CONF_THUMB_ZOOM')) { // template_conf.inc.php
         $zoom = CONF_THUMB_ZOOM;
      }

      // Größe Thumbnail
      $thumb_x = (int)round($size_x * $zoom);
      $thumb_y = (int)round($size_y * $zoom);

      // Offset im Originalbild
      $offset_org_x = 0;
      $offset_org_y = 0;
      // Auschnitt im Thumb nach links / unten versetzt
      $thumb_links = 0;
      $thumb_oben = 0;
      // Größe Ausschnitt Thumb, in den das Original eingesetzt wird
      $temp_x = $thumb_x;
      $temp_y = $thumb_y;

      // Originalbild < Thumbnail -> Rand links / oben
      if ($image_x <= $thumb_x && $image_y <= $thumb_y) {
         $thumb_links = (int)round(($thumb_x - $image_x) / 2);
         $thumb_oben = (int)round(($thumb_y - $image_y) / 2);
         $temp_x = $thumb_x - 2 * $thumb_links;
         $temp_y = $thumb_y - 2 * $thumb_oben;
      }

      // Höhe zu gering -> Bild auf Breite skalieren, Rand oben
      else if ($image_y < $thumb_y) {
         $ratio = $image_x / $thumb_x;
         $thumb_oben = (int)round(($thumb_y - $image_y / $ratio) / 2);
         $temp_y = (int)round($thumb_y - 2 * $thumb_oben);
      }

      // Skalierung auf Höhe, evtl. Rand links
      else {
         $ratio = $image_y / $thumb_y;
         $temp_y = (int)round($image_y / $ratio);
         $temp_x = (int)round($image_x / $ratio);
         $thumb_links = (int)(round($thumb_x - $temp_x) / 2);
      }

      $image = false;
      switch(self::_imageResizeFiletyp($image_typ)) {
         case 'png':
            $image = imagecreatefrompng($dir.$filename . "." . $extension);
            break;

         case 'jpg':
            $image = imagecreatefromjpeg($dir.$filename . "." . $extension);
            break;

         case 'gif':
            $image = imagecreatefromgif($dir.$filename . "." . $extension);
            break;
      }

      if ($image === false) {
         return false;
      }

      $thumb = imagecreatetruecolor($thumb_x, $thumb_y);
      $r = hexdec(substr(self::$params->firma['bg_artikelbild'], 0, 2));
      $g = hexdec(substr(self::$params->firma['bg_artikelbild'], 2, 2));
      $b = hexdec(substr(self::$params->firma['bg_artikelbild'], 4, 2));
      $color = imagecolorallocate($thumb, $r, $g, $b);
      imagefill($thumb, 0, 0, $color);

      imagecopyresampled($thumb, $image, $thumb_links, $thumb_oben, 0, 0, $temp_x, $temp_y, $image_x, $image_y);
      imagejpeg($thumb, $dir.$filename.$ext.'.jpg');

      unset($image);
      unset($thumb);
      return true;


   }

   // Responsive Templates (Proportional) / Erstellt proportionale Startbild / Thumbnail
   static public function makeThumbStartProp($uploaddir, $filename, $extension, $ext = '_tp') {
      list($original_x, $original_y, $image_typ) = getimagesize($uploaddir.$filename . "." . $extension);
      $size_x = 300;
      $size_y = 0;

      // Konstanten erst ab Template fullscreen definiert
      if (defined('CONF_THUMBWIDTH_NORMAL')) {
         $size_x = CONF_THUMBWIDTH_NORMAL;
      }

      if ((self::$params->firma['cpf_size'] == 'gross' || self::$params->firma['cpf_size'] == 'riesig') && defined('CONF_THUMBWIDTH_BIG')) {
         $size_x = CONF_THUMBWIDTH_BIG;
      }
      else {
         $size_x = 450;
      }

      // Mur skalieren, wenn Original breiter als 350/450px ist
      if ($original_x > $size_x) {
         $size_y = (int)($size_x * $original_y / $original_x);
      }
      else {
         $size_y = $original_y;
      }

      $zoom = 1.0;
      if (defined('CONF_THUMB_ZOOM')) { // template_conf.inc.php
         $zoom = CONF_THUMB_ZOOM;
      }

      // Größe, in der das Bild erstellt wird
      $thumb_x = (int)round($size_x * $zoom);
      $thumb_y = (int)round($size_y * $zoom);

      // Größe Ausschnitt
      $temp_x = $thumb_x;
      $temp_y = $thumb_y;
      $links  = 0;

      // Breite zu gering ? Rand li/re
      if ($original_x < $thumb_x) {
         // $zoom berücksichtigen!
         $links = (int)round(($thumb_x - $original_x * $zoom) / 2);
         $temp_x = $thumb_x - 2 * $links;
      }

      $img = false;
//      switch(strtolower($extension)) {
      switch(self::_imageResizeFiletyp($image_typ)) {

         case 'png':
            $img = imagecreatefrompng($uploaddir.$filename . "." . $extension);
            break;

         case 'jpg':
            $img = imagecreatefromjpeg($uploaddir.$filename . "." . $extension);
            break;

         case 'gif':
            $img = imagecreatefromgif($uploaddir.$filename . "." . $extension);
            break;
      }

      if ($img === false) {
         return false;
      }

      $new_img = imagecreatetruecolor($thumb_x, $thumb_y);
      $r = hexdec(substr(self::$params->firma['bg_artikelbild'], 0, 2));
      $g = hexdec(substr(self::$params->firma['bg_artikelbild'], 2, 2));
      $b = hexdec(substr(self::$params->firma['bg_artikelbild'], 4, 2));
      $color = imagecolorallocate($new_img, $r, $g, $b);
      imagefill($new_img, 0, 0, $color);

      imagecopyresampled($new_img, $img, $links, 0, 0, 0, $temp_x, $temp_y, $original_x, $original_y);
      imagejpeg($new_img, $uploaddir.$filename.$ext.".jpg");

      unset($img);
      unset($new_img);

      return true;
   }

   // Thumbnails 107x107px für Detail-Vorschau (und Admin)
   static public function makeThumbSmall($uploaddir, $filename, $extension) {
      list($breite, $hoehe) = getimagesize($uploaddir.$filename.'.'.$extension);

      $zoom = 1.0;
      if (defined('CONF_THUMB_ZOOM')) { // aus template_conf.inc.php
         $zoom = CONF_THUMB_ZOOM;
      }

      // $thumbsize = round(107 * $zoom);
      $thumbsize = 150;

      $im = '';
      $offset_x = 0;
      $offset_y = 0;

      // Thumbnail Detailseite erstellen
      if ($hoehe >= $breite && $hoehe > $thumbsize) {
         $offset_y = ceil(($hoehe - $breite) / 2);
      }
      elseif ($breite > $hoehe && $breite > $thumbsize) {
         $offset_x = ceil(($breite - $hoehe) / 2);
      }

      $new_im = imagecreatetruecolor($thumbsize, $thumbsize);
      switch($extension) {
         case 'png':
            $im = @imagecreatefrompng($uploaddir.$filename . "." . $extension);
            break;

         case 'jpg':
            $im = @imagecreatefromjpeg($uploaddir.$filename . "." . $extension);
            break;

         case 'gif':
            $im = @imagecreatefromgif($uploaddir.$filename . "." . $extension);
            break;
      }

      if ($im === false) {
         return false;
      }

      imagecopyresampled($new_im, $im, 0, 0, $offset_x, $offset_y, $thumbsize, $thumbsize, $breite - 2 * $offset_x, $hoehe - 2 * $offset_y);
      imagejpeg($new_im, $uploaddir.$filename.'_td.jpg');
      unset($im);
      unset($new_im);

      return true;
   }

   static public function makeCursor($uploaddir, $filename, $extension) {
      list($breite, $hoehe) = getimagesize($uploaddir.$filename.'.'.$extension);
      $thumbsize = 128;

      $im = '';
      $offset_x = 0;
      $offset_y = 0;

      // Thumbnail Detailseite erstellen
      if ($hoehe >= $breite && $hoehe > $thumbsize) {
         $offset_y = ceil(($hoehe - $breite) / 2);
      }
      elseif ($breite > $hoehe && $breite > $thumbsize) {
         $offset_x = ceil(($breite - $hoehe) / 2);
      }

      $new_im = imagecreatetruecolor($thumbsize, $thumbsize);
      switch($extension) {
         case 'png':
            $im = @imagecreatefrompng($uploaddir.$filename . "." . $extension);
            break;

         case 'jpg':
            $im = @imagecreatefromjpeg($uploaddir.$filename . "." . $extension);
            break;

         case 'gif':
            $im = @imagecreatefromgif($uploaddir.$filename . "." . $extension);
            break;
      }

      if ($im === false) {
         return false;
      }

      imagecopyresampled($new_im, $im, 0, 0, $offset_x, $offset_y, $thumbsize, $thumbsize, $breite - 2 * $offset_x, $hoehe - 2 * $offset_y);
      imagejpeg($new_im, $uploaddir.$filename.'_cur.jpg');
      unset($im);
      unset($new_im);

      return true;
   }

   static public function makeFotoThumb($uploaddir, $filename) {
      // Original ist in /downloads/...

      $dir = SHOP_PATH.'/'.CONF_PICT_PATH.'/';
      list($width_foto, $height_foto) = getimagesize($uploaddir);


      // Größe max CONF_MAX_SIZE px - Hoehe oder Breite
      $faktor = 1;

      if ($height_foto > $width_foto && $height_foto > CONF_MAX_SIZE) {
         $faktor = $height_foto / CONF_MAX_SIZE;
      }

      elseif ($width_foto > $height_foto && $width_foto > CONF_MAX_SIZE) {
         $faktor = $width_foto / CONF_MAX_SIZE;
      }

      $zoom = 1;

      if (defined('CONF_FOTO_ZOOM')) {
         $zoom = CONF_FOTO_ZOOM;
      }

      $faktor /= $zoom;

      $width_new  = floor($width_foto / $faktor);
      $height_new = floor($height_foto / $faktor);

      $width_water  = 0;
      $height_water = 0;
      $x            = 0;
      $y            = 0;

      $img_foto = imagecreatefromjpeg($uploaddir);

      // Neues "Original" (für Magiczoom) aus Foto erstellen
      $new_org = imagecreatetruecolor($width_new, $height_new);
      imagecopyresampled($new_org, $img_foto, 0, 0, 0, 0, $width_new, $height_new, $width_foto, $height_foto);
      imagedestroy($img_foto);

      // Wenn Wasserzeichen vorhanden
      if (file_exists(SHOP_PATH.'/admin/img/wasserzeichen.png')) {
         $stamp        = imagecreatefrompng(SHOP_PATH.'/admin/img/wasserzeichen.png');
         $size         = getimagesize(SHOP_PATH.'/admin/img/wasserzeichen.png');
         $width_water  = $size[0];
         $height_water = $size[1];

         // Wasserzeichen einkopieren, wenn vorhanden
         if ($width_water > 0) {
            $x = floor($width_new / $width_water);
            $y = floor($height_new / $height_water);

            $x_water = floor(($width_new - ($x + 0.5) * $width_water) / 2);
            $y_water = floor(($height_new - $y * $height_water) / 2);

            for ($i = 0; $i < $x; $i++) {
               for ($j = 0; $j < $y; $j++) {
                  imagecopy($new_org, $stamp, $x_water + $i * $width_water + $width_water * ($j % 2 == 1 ? 0.5 : 0), $y_water + $j * $height_water, 0, 0, $width_water, $height_water);
               }
            }
         }
      }

      imagedestroy($stamp);
      imagejpeg($new_org, SHOP_PATH.'/'.CONF_PICT_PATH.'/original/'.$filename.'.jpg');

      // Detailbild
      if ($zoom != 1) {
         $new_img = imagecreatetruecolor($width_new/$zoom, $height_new/$zoom);
         imagecopyresampled($new_img, $new_org, 0, 0, 0, 0, $width_new/$zoom, $height_new/$zoom, $width_new, $height_new);
         imagedestroy($new_org);

         imagejpeg($new_img, SHOP_PATH.'/'.CONF_PICT_PATH.'/'.$filename.'.jpg');
         imagedestroy($new_img);
      }

      else {
         copy(SHOP_PATH.'/'.CONF_PICT_PATH.'/original/'.$filename.'.jpg', SHOP_PATH.'/'.CONF_PICT_PATH.'/'.$filename.'.jpg');
      }

      // Thumbnails erstellen
      self::makeThumbSmall($dir, $filename, 'jpg', '_td');
      self::makeCursor($dir, $filename, 'jpg', '_cur');
      self::makeThumbStartFix($dir, $filename, 'jpg', '_tn');
      self::makeThumbStartProp($dir, $filename, 'jpg', '_tp');
   }

   // ImageTyp dezimal als Ext. zurückgeben
   static private function _imageResizeFiletyp($typ) {
      $ext = '';

      switch ($typ) {
         case IMAGETYPE_PNG:
            $ext = 'png';
            break;

         case IMAGETYPE_BMP:
            $ext = 'bmp';
            break;

         case IMAGETYPE_JPEG:
         case IMG_JPG:
            $ext = 'jpg';
            break;

         case IMAGETYPE_GIF:
         case IMG_GIF:
            $ext = 'gif';
            break;

         default:
            $ext = 'none';
      }

      return $ext;
   }

   // Test, ob Bild existiert, sonst 'nopic' zurück liefern
   // 05.03.2019
   static public function testPicture($pic) {
      self::$pic_status = false;

      if (strpos($pic, 'http://') !== false || strpos($pic, 'https://') !== false) {
         self::$pic_status = true;
         return $pic;
      }

      if ($pic != '' && strstr($pic, 'nopic') == '' && file_exists(SHOP_PATH.'/'.CONF_PICT_PATH.$pic)) {
         self::$pic_status = true;
         return PICTURE_URL.$pic;
      }

      else {
         if (strstr($pic, '_td.jpg')) {
            return TEMPLATE_URL.'/images/system/nopic_td.png';
         }

         return TEMPLATE_URL.'/images/system/'.CONF_NOPICT;
      }
   }

   // Checkbox für HTML zurückgeben
   static public function getCheckbox($name, $check, $script = ''){
      if ($check == 'y') {
         return "<input type='checkbox' $script name='$name' id='$name' checked='checked' />";
      }
      else {
         return "<input type='checkbox' $script name='$name' id='$name'/>";
      }
   }

   // Option-Felder für Anrede generieren
   static public function getAnredeOption($selected) {
      $html = '';
      if ($selected == '') {
         $html  = "            <option value='' selected='selected'> </option>";
      }
      $html .= "            <option value='frau'" . ($selected == 'frau' ? 'selected="selected"' : '') . ">" . self::$text->get('kunde', 'frau') . "</option>";
      $html .= "            <option value='herr'" . ($selected == 'herr' ? 'selected="selected"' : '') . ">" . self::$text->get('kunde', 'herr') . "</option>";
      return $html;
   }

   // Größe Grafik ermitteln
   static public function getPicsize($datei, $default_h = 0) {
      $size = [];
      $pfad = SHOP_PATH.'/templates/'.self::$params->firma['template'].'/images/';
      if (!file_exists($pfad.$datei)) {
         $size[0] = 950;
         $size[1] = $default_h;
      }
      else {
         $size = getimagesize($pfad.$datei);
      }
      return $size;
   }

   // $symbol = 1: Währungssymbol, sonst aus Sprachen zurückgeben
   static public function waehrungText($wid, $symbol = 0) {
      $wid = (int)$wid;

      if ($wid == 1) {
         if ($symbol == 0) {
            return self::$text->get('waehrung', 'eur', self::$params->selected_lang);
         }
         else if ($symbol == 1) {
            return self::$text->get('waehr_symbol', 'eur', self::$params->selected_lang);
         }
         else if ($symbol == 2) {
            return self::$text->get('waehr_short', 'eur', self::$params->selected_lang);
         }
         else {
            return self::$text->get('waehr_short', 'eur', self::$params->selected_lang);
         }
      }

      if ($wid == 2) {
         if ($symbol == 0) {
            return self::$text->get('waehrung', 'gbp', self::$params->selected_lang);
         }
         else if ($symbol == 1) {
            return self::$text->get('waehr_symbol', 'gbp', self::$params->selected_lang);
         }
         else if ($symbol == 2) {
            return self::$text->get('waehr_short', 'gbp', self::$params->selected_lang);
         }
         else {
            return self::$text->get('waehr_short', 'gbp', self::$params->selected_lang);
         }
      }

      if ($wid == 3) {
         if ($symbol == 0) {
            return self::$text->get('waehrung', 'usd', self::$params->selected_lang);
         }
         else if ($symbol == 1) {
            return self::$text->get('waehr_symbol', 'usd', self::$params->selected_lang);
         }
         else if ($symbol == 2) {
            return self::$text->get('waehr_short', 'usd', self::$params->selected_lang);
         }
         else {
            return self::$text->get('waehr_short', 'usd', self::$params->selected_lang);
         }
      }

      if ($wid == 4) {
         if ($symbol == 0) {
            return self::$text->get('waehrung', 'chf', self::$params->selected_lang);
         }
         else if ($symbol == 1) {
            return self::$text->get('waehr_symbol', 'chf', self::$params->selected_lang);
         }
         else if ($symbol == 2) {
            return self::$text->get('waehr_short', 'chf', self::$params->selected_lang);
         }
         else {
            return self::$text->get('waehr_short', 'chf', self::$params->selected_lang);
         }
      }

      if ($wid == 5) {
         if ($symbol == 0) {
            return self::$text->get('waehrung', 'rub', self::$params->selected_lang);
         }
         else if ($symbol == 1) {
            return self::$text->get('waehr_symbol', 'rub', self::$params->selected_lang);
         }
         else if ($symbol == 2) {
            return self::$text->get('waehr_short', 'rub', self::$params->selected_lang);
         }
         else {
            return self::$text->get('waehr_short', 'rub', self::$params->selected_lang);
         }
      }

      if ($wid == 6) {
         if ($symbol == 0) {
            return self::$text->get('waehrung', 'kr', self::$params->selected_lang);
         }
         else if ($symbol == 1) {
            return self::$text->get('waehr_symbol', 'kr', self::$params->selected_lang);
         }
         else {
            return self::$text->get('waehr_short', 'kr', self::$params->selected_lang);
         }
      }

      if ($wid == 7) {
         if ($symbol == 0) {
            return self::$text->get('waehrung', 'li', self::$params->selected_lang);
         }
         else if ($symbol == 1) {
            return self::$text->get('waehr_symbol', 'li', self::$params->selected_lang);
         }
         else {
            return self::$text->get('waehr_short', 'li', self::$params->selected_lang);
         }
      }

      if ($wid == 8) {
         if ($symbol == 0) {
            return self::$text->get('waehrung', 'dh', self::$params->selected_lang);
         }
         else if ($symbol == 1) {
            return self::$text->get('waehr_symbol', 'dh', self::$params->selected_lang);
         }
         else {
            return self::$text->get('waehr_short', 'dh', self::$params->selected_lang);
         }
      }

   }

   static public function _($preis, $faktor = 0) {
      if ($faktor == 0) {
         return ($preis * self::$params->w_faktor);
      }
      else {
         return ($preis * $faktor);
      }
   }

   static public function number_format($preis, $stellen = 9, $komma = '', $punkt = '', $faktor = 0) {
      if ($faktor == 0) {
         return number_format($preis * self::$params->w_faktor, $stellen, $komma, $punkt);
      }
      else {
         return number_format($preis * $faktor, $stellen, $komma, $punkt);
      }
   }

   // Preis als Grafik anzeigen (span mit Hintergrundgrafik)
   // $preis kann float, string, ... sein
   static public function renderPreis($preis, $waehrung = 0) {
      $cr = "\n";
      // Preis in Array wandeln
      $spreis = str_split(sprintf('%10.2f', $preis));
      $html = '';

      // Zahlen und Komma
      foreach ($spreis as $preis) {
         switch ($preis) {
            case '1':
               $html .= "<img class='preis' src='" . TEMPLATE_URL . "/images/zahlen/1.png' alt='' />";
               break;
            case '2':
               $html .= "<img class='preis' src='" . TEMPLATE_URL . "/images/zahlen/2.png' alt='' />";
               break;
            case '3':
               $html .= "<img class='preis' src='" . TEMPLATE_URL . "/images/zahlen/3.png' alt='' />";
               break;
            case '4':
               $html .= "<img class='preis' src='" . TEMPLATE_URL . "/images/zahlen/4.png' alt='' />";
               break;
            case '5':
               $html .= "<img class='preis' src='" . TEMPLATE_URL . "/images/zahlen/5.png' alt='' />";
               break;
            case '6':
               $html .= "<img class='preis' src='" . TEMPLATE_URL . "/images/zahlen/6.png' alt='' />";
               break;
            case '7':
               $html .= "<img class='preis' src='" . TEMPLATE_URL . "/images/zahlen/7.png' alt='' />";
               break;
            case '8':
               $html .= "<img class='preis' src='" . TEMPLATE_URL . "/images/zahlen/8.png' alt='' />";
               break;
            case '9':
               $html .= "<img class='preis' src='" . TEMPLATE_URL . "/images/zahlen/9.png' alt='' />";
               break;
            case '0':
               $html .= "<img class='preis' src='" . TEMPLATE_URL . "/images/zahlen/0.png' alt='' />";
               break;
            case '.':
               $html .= "<img class='preis' src='" . TEMPLATE_URL . "/images/zahlen/punkt.png' alt='' />";
               break;
         }
      }

      // Währungssymbol
      if ($waehrung == 0) {
         $waehrung = self::$params->firma['waehrung1'];
      }

      if ($waehrung == 1) {
         $html .= "<img class='preis' src='" . TEMPLATE_URL . "/images/zahlen/w_euro.png' alt='' />";
      }
      elseif ($waehrung == 2) {
         $html .= "<img class='preis' src='" . TEMPLATE_URL . "/images/zahlen/w_pfund.png' alt='' />";
      }
      elseif ($waehrung == 3) {
         $html .= "<img class='preis' src='" . TEMPLATE_URL . "/images/zahlen/w_dollar.png' alt='' />";
      }
      elseif ($waehrung == 4) {
         $html .= "<img class='preis' src='" . TEMPLATE_URL . "/images/zahlen/w_schweizerfranken.png' alt='' />";
      }
      elseif ($waehrung == 5) {
         $html .= "<img class='preis' src='" . TEMPLATE_URL . "/images/zahlen/w_rubel.png' alt='' />";
      }

      elseif ($waehrung == 6) {
         $html .= "<img class='preis' src='" . TEMPLATE_URL . "/images/zahlen/w_kronen.png' alt='' />";
      }

      elseif ($waehrung == 7) {
         $html .= "<img class='preis' src='" . TEMPLATE_URL . "/images/zahlen/w_lire.png' alt='' />";
      }

      elseif ($waehrung == 8) {
         $html .= "<img class='preis' src='" . TEMPLATE_URL . "/images/zahlen/w_dirham.png' alt='' />";
      }

      return $html;
   }

   // Bei Artikeln mit Maßen Menge berücksichtigen
   static public function renderPreisMenge($preis, $check, $menge, $waehrung = 0, $text = true) {
      if ($check == 'y') {
         $preis = $preis * (float)$menge;
      }

      if ($text) {
         return self::renderPreis($preis, $waehrung);
      }

      return (float)$preis;
   }


   // $script einbinden
   static public function htmlScript($script) {
      $out  = '<script>'."\n";
      $out .= $script;
      $out .= '</script>'."\n";

      return $out;
   }

   // SQL-Datum in Deutsches Datumsformat
   static public function sqlDatum($date) {
      $back = '';
      if ($date != '0000-00-00' && $date != '0000-00-00 00:00:00' && $date != '1970-01-02 00:00:00') {
         $back = date('d.m.Y', strtotime($date));
      }

      return $back;
   }

   // SQL-Datum in Deutsches Datumsformat
   static public function sqlDatumShort($date) {
      $back = '';

      if ($date != '0000-00-00' && $date != '0000-00-00 00:00:00' && $date != '1970-01-02 00:00:00') {
         $back = date('d.m.y', strtotime($date));
      }

      return $back;
   }

   // Deutsche Datumsformat für MySQL wandeln
   static public function datumSql($date) {
      $date = str_replace(',', '.', $date);
      $datum = explode('.', $date);

      if (count($datum) == 3) {
         return "$datum[2]-$datum[1]-$datum[0]";
      }

      return '0000-00-00';
   }

   // Text aus #__seiten / starthtml ausgeben
   static public function getStartseite() {
      if ( self::$params->firma['starthtml_on'] != 'y') {
         return '';
      }

      $html = '';
      $text = self::$db->querySingleValue("SELECT text FROM #__seiten WHERE lang = '" . self::$params->selected_lang . "' AND art = 'starthtml'");

      if (strlen($text) < 10) {
         return '';
      }

      if (defined('CONF_RESPONSIVE')) {
         return self::checkTextToggle($text);
      }

      else {
         return '<div class="text_startseite">'.self::checkTextToggle($text).'</div>';
      }
   }

   // Footer aus DB lesen
   static public function getFooter($pos_footer = false) {
      $test = 1;
      $sql = "SELECT text FROM #__seiten WHERE lang = '".self::$params->selected_lang."' AND art = 'footer'";
      if (!self::$db->query($sql)) {
         // Text in gewünschter Sprache nicht vorhanden, dann Default-Sprache des Shops
         $sql = "SELECT text FROM #__seiten WHERE lang = '".self::$params->default_lang."' AND art = 'footer'";
         // Wenn kein Ergebnis, Rückgabe 0
         $test = self::$db->query($sql);
      }

      if ($test) {
         $data = self::$db->getObject();
         $footer = self::checkTextToggle($data->text, $pos_footer);
      }

      else {
         $footer = 'Kein Text vorhanden.';
      }
      return $footer;
   }

   // ueberuns1 - 5 Name zurück geben oder 'not found', falls nicht vorhanden oder deaktiviert
   // 08.07.2019
   static public function getUeberUns($ueberuns, $text = false) {
      $art = 'ueberuns'.$ueberuns;

      $data = self::$db->querySingleValue("SELECT name FROM #__seiten WHERE lang = '" . self::$params->selected_lang . "' AND art = '$art'");

      if ($data && $text) {
         return str_replace(' ', '&nbsp;', $data);
      }

      if ($data) {
         if($data != '' && self::$params->firma[$art.'_check'] == 'y') {
            return str_replace(' ', '&nbsp;', $data);
         }
      }

      return 'not found';
   }

   // widerruf1 - 5 Name zurück geben oder 'not found', falls nicht vorhanden oder deaktiviert
   // 08.07.2019
   static public function getWiderruf($widerruf) {
      $art = 'widerruf'.$widerruf;

      $w_ruf = self::$db->querySingleObject("SELECT name FROM #__seiten WHERE lang = '" . self::$params->selected_lang . "' AND art = '$art'");

      if ($w_ruf && $w_ruf != '' && self::$params->firma[$art.'_check']) {

         if (defined('CONF_RESPONSIVE')) {
            return self::$text->get('menu', 'widerruf').' '.$w_ruf->name;
         }

         return strtoupper(self::$text->get('menu', 'widerruf')).' '.$w_ruf->name;
      }

      return 'not found';
   }

   // Seiten Name zurück geben oder 'not found', falls nicht vorhanden oder deaktiviert
   // 08.07.2019
   static function getSeite($seite, $lang = '') {
      if ($lang == '') {
         $lang = self::$params->selected_lang;
      }

      if (self::$params->firma[$seite.'_check'] == 'y') {
         if ($seite == 'kontakt2') {
            $seite = 'kontakt';
         }

         return self::$text->get('menu', $seite, $lang);
      }

      return 'not_active';
   }

   static public function checkUeberuns($task, $oldlang, $newlang) {
      $data = [];
      $sql = "SELECT art, lang, name FROM #__seiten WHERE (lang = '$oldlang' OR lang = '$newlang') AND art LIKE 'ueberuns%'";
      if (self::$db->query($sql) > 0) {
         while ($tmp = self::$db->getObject()) {
            if($tmp) {
               $data[] = $tmp;
            }
         }

         for ($i = 0; $i < count($data); $i++) {
            if ($data[$i]->art == $task && $data[$i]->lang == $oldlang) {
               $art = $data[$i]->art;

               for ($j = 0; $j < count($data); $j++) {
                  if ($data[$j]->art == $art && $data[$j]->lang == $newlang) {
                     return self::checkLink($data[$j]->name);
                  }
               }
            }
         }
      }

      return "";
   }

   static public function checkLink($link) {
      $link = str_replace([' ', '&nbsp;'], '_', $link);

      if (strpos($link = htmlentities($link, ENT_QUOTES, 'UTF-8'), '&') !== false) {
        $link = html_entity_decode(preg_replace('~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|tilde|uml);~i', '$1', $link), ENT_QUOTES, 'UTF-8');
      }

      return str_replace('__', '_', $link);
   }

   static public function checkUrl($url) {
      if ($url == '' || preg_match('|(https?://)|', $url) > 0) {
         return $url;
      }
      return 'http://'.$url;
   }

   static public function getLastschrift() {
      $sql = "SELECT `text` FROM #__system_texte WHERE lang = '" . self::$params->selected_lang . "' AND art = 'lastschrift'";
      self::$db->query($sql);
      $data = self::$db->getObject();
      return str_replace('[Firmenname]', self::$params->firma['shop_name'], $data->text);
   }

   static public function staffelpreis($preis, $menge, $staffelung, $brutto = false, $steuersatz = 0) {
      if ($staffelung == '') {
         return $preis;
      }

      $preis_neu = $preis;
      $werte = explode("#", $staffelung);

      foreach ($werte as $wert) {
         $staffel = explode(';', $wert);

         if ($staffel[0] == 'y' && $menge >= (int)$staffel[1]) {
            // USt berücksichtigen
            if ($brutto) {
               $preis_neu = ($preis + ((float)$staffel[2]) * (1 + $steuersatz / 100));
            }
            // Netto
            else {
               $preis_neu = $preis + (float)$staffel[2];
            }
         }
      }

      return $preis_neu;
   }

   static public function staffelpreisGe($preis, $preis_ge, $menge, $staffelung, $brutto = false, $steuersatz = 0) {
      if ($staffelung == '') {
         return $preis_ge;
      }

      $preis_neu    = $preis;
      $preis_ge_neu = $preis_ge;
      $werte        = explode("#", $staffelung);
      $faktor       = 1;

      foreach ($werte as $wert) {
         $staffel = explode(';', $wert);

         if ($staffel[0] == 'y' && $menge >= (int)$staffel[1]) {
            // USt berücksichtigen
            if ($brutto) {
               $preis_neu = ($preis + ((float)$staffel[2]) * (1 + $steuersatz / 100));
            }
            // Netto
            else {
               $preis_neu = $preis + (float)$staffel[2];
            }

            $faktor = $preis / $preis_neu;
            $preis_ge_neu = $preis_ge / $faktor;
         }
      }

      return $preis_ge_neu;
   }

   static public function staffelmenge($staffelung, $menge) {
      if ($staffelung == '') {
         return '';
      }

      $txt = '';
      $werte = explode("#", $staffelung);

      foreach ($werte as $wert) {
         $staffel = explode(';', $wert);
         if ($staffel[0] == 'y' && $menge >= (int)$staffel[1]) {
            $txt = $staffel[1].' Stk.';
         }
      }

      return $txt;
   }

   // Social-Icons Artikel-Detail
   static public function getSocialDetails($url, $art_name = '', $art_text = '', $art_image = '') {
      $social = ['html' => null, 'article' => null, 'script' => null];
      $html   = '';
      $head   = '';
      $start  = 80 + 10 * CONF_TEMPLATE_ID;

      // Script lesen, wenn vorhanden
      $sql = "SELECT * FROM #__social WHERE script_check = 'y' AND detail_script != '' AND (id < 100 OR id > ".$start." AND id < ".($start + 9).") ORDER BY id";
      $data = self::$db->queryAllObjects($sql);

      for ($i = 0; $i < (is_array($data) ? count($data) : 0); $i++) {
         $social['script'][] = '<script>'.$data[$i]->detail_script.'</script>';
      }

      // Daten obere Zeile
      $sql = "SELECT * FROM #__social WHERE (detail1 = 'y' OR detail2 = 'y') AND (id < 100 OR id > ".$start." AND id < ".($start + 9).") ORDER BY id";
      $data = self::$db->queryAllObjects($sql);

      // Kunde hat noch nicht zugestimmt
      if (is_array($data) && count($data)) {
         $social['html'] = '<div id="social_img" onclick="Royalart.showSocials();">'.CR;

         for ($i = 0; $i < count($data); $i++) {

            if(Helper::socialidHasCookies((int)$data[$i]->id)){

                if ((int)$data[$i]->id < 100) {
                   $social['html'] .= '<span class="social_details sociald_'.$data[$i]->id.'"></span>'.CR;
                }

                else {
                   $social['html'] .= '<img class="social_img" src="'.TEMPLATE_URL.'/images/'.$data[$i]->image.'_teilen.png" alt=""/>'.CR;
                }

             }

         }

         $social['html'] .= '</div>';
         $social['html'] .= '<input type="hidden" id="social_url" value="'.$url.'" />';
         $social['html'] .= '<input type="hidden" id="social_art_image" value="'.$art_image.'" />';
         $social['html'] .= '<input type="hidden" id="social_art_name" value="'.$art_name.'" />';
         $social['html'] .= '<input type="hidden" id="social_art_text" value="'.str_replace('"', "'", $art_text).'" />';

      }

      //
      for ($i = 0; $i < (is_array($data) ? count($data) : 0); $i++) {
         if ($data[$i]->detail1 == 'y') {
            if ((int)$data[$i]->id <= 100) {
               $image = ADMIN_URL.'/img/social_icons/'.$data[$i]->image.'.jpg';
            }

            else {
               $image = TEMPLATE_URL.'/images/'.$data[$i]->image.'.png';
            }

            $d = Helper::socialImg((int)$data[$i]->id, $image, $url, $data[$i]->detail_link, $data[$i]->detail_script, $art_name, $art_text, $art_image);
            $social['article'][] = ['image' => $d['img'], 'hasCookies'=>Helper::socialidHasCookies((int)$data[$i]->id)];
            $social['script'][] = $d['script'];
            $head .= $d['head'];
         }

         if ($data[$i]->detail2 == 'y') {
            if ((int)$data[$i]->id <= 100) {
               $image = ADMIN_URL.'/img/social_icons/'.$data[$i]->image.'.jpg';
            }

            else {
               $image = TEMPLATE_URL.'/images/'.$data[$i]->image.'.png';
            }

            $d = Helper::socialImg((int)$data[$i]->id, $image, $url, $data[$i]->detail_link, $data[$i]->detail_script, $art_name, $art_text, $art_image, true);
            $social['article'][] = ['image' => $d['img'], 'hasCookies'=>Helper::socialidHasCookies((int)$data[$i]->id)];
            $social['script'][] = $d['script'];
            $head .= $d['head'];
         }
      }

      self::$params->head = $head;

      return $social;
   }


   static private function socialidHasCookies($id){

       if($id == 10){
           return false;
       }

       return true;

   }

   // von Articles / getSocialDetails über self::getSocialDetails aufgerufen
   static private function socialImg($id, $image, $url, $login, $detail_script, $art_name, $art_text, $art_image, $second = false) {

      $html   = '';
      $script = '';
      $head   = '';

      if ($id > 100) {
            $html .= '<li>'.($detail_script != '' ? str_replace('[URL]', $url, $detail_script ) : '<img class="social_img" src="'.$image.'" alt="" />').'</li>';
      }

      else {
         switch ($id) {
            case 2: // Mail
//               $html .= '<li><a href="mailto:'.self::$params->firma['email'].'?subject='.$art_name.'&body=%0a'.$art_name.'%0a'.$url.'%0a"><img class="social_img" src="'.$image.'" alt="" /></a></li>';
               $html .= '<li><a href="mailto:?subject='.$art_name.'&body=%0a'.$art_name.'%0a'.$url.'%0a"><img class="social_img" src="'.$image.'" alt="" /></a></li>';
               break;

            case 5: // Xing
               $html   .= '<li class="xxing"><div data-lang="de" data-shape="square" data-type="xing/share"></div></li>';
               $script .= '<script>(function (d, s) { var x = d.createElement(s), s = d.getElementsByTagName(s)[0]; x.src = "https://www.xing-share.com/plugins/share.js"; s.parentNode.insertBefore(x, s); })(document, "script");</script>';
               break;

            case 7: // Twitter
               if ($second === false) { // Twittern
                  $html   .= '<li class="xtwitter"><a href="https://twitter.com/share" class="twitter-share-button"{count} data-url="'.$url.'" data-text="'.$art_name.'" data-via="'.$login.'" data-lang="de" data-hashtags="hashtag">Twittern</a></li>';
                  $script .= "<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>";
   //               $script .= '<script>window.twttr = (function(d, s, id) { var js, fjs = d.getElementsByTagName(s)[0], t = window.twttr || {}; if (d.getElementById(id)) return t; js = d.createElement(s); js.id = id; js.src = "https://platform.twitter.com/widgets.js"; fjs.parentNode.insertBefore(js, fjs); t._e = []; t.ready = function(f) { t._e.push(f); }; return t; }(document, "script", "twitter-wjs"));</script>';
               }

               else { // Folgen
//                  $html   .= '<li><a href="https://twitter.com/'.$login.'" class="twitter-follow-button" data-show-count="false" data-lang="de" data-show-screen-name="false">folgen</a></li>';
//                  $html   .= '<li><a href="https://twitter.com/test" class="twitter-follow-button" data-show-count="false" data-show-screen-name="false">Follow @'.$login.'</a></li>';
                  $html   .= '<li><a href="https://twitter.com/'.$login.'" class="twitter-follow-button" data-show-count="false">Follow @'.$login.'</a></li>';
                  $script .= "<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>";
               }

               break;

            case 8: // Facebook

               if ($second === false) { // Gefällt mir
                  $html .= '<li class="xfacebook"><iframe src="https://www.facebook.com/plugins/like.php?href='.urlencode($url).'&amp;layout=button&amp;show_faces=true&amp;action=like&amp;colorscheme=light" style="width:84px;height:20px; display:inline-block" id="lkbtn" scrolling="no" frameborder="0" allowTransparency="true"></iframe></li>';


               }

               else { // Teilen
                  $html   .= '<li><div id="fb-root"></div></li>';
                  $html   .= '<li><div class="fb-share-button" data-href="'.$url.'" data-layout="button"></div></li>';

                  $script .= '<script>(function(d, s, id) {'.CR;
                  $script .= 'var js, fjs = d.getElementsByTagName(s)[0];'.CR;
                  $script .= 'if (d.getElementById(id)) return;'.CR;
                  $script .= 'js = d.createElement(s); js.id = id;'.CR;
                  $script .= 'js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5";'.CR;
                  $script .= 'fjs.parentNode.insertBefore(js, fjs);'.CR;
                  $script .= "}(document, 'script', 'facebook-jssdk'));</script>".CR;

                  $head   .= '<meta property="og:url" content="'.$url.'" />'.CR;
                  $head   .= '<meta property="og:type" content="website" />'.CR;
                  $head   .= '<meta property="og:title" content="'.$art_name.'" />'.CR;
                  $head   .= '<meta property="og:description" content="'.str_replace('"', "'", strip_tags($art_text)).'" />'.CR;
                  $head   .= '<meta property="og:image" content="'.$art_image.'" />'.CR;
               }

               break;

            case 10: // 18.02.2021 Google+ -> WhatsApp


                $data = Helper::getSocialData(10);

                $footer_link = $data->footer_link;



                $html .= '<li class="xwhatsapp">';
                if ($second === false) {
                    $html.=
                    '<a alt="Whatsapp Chat" target="_blank" href="http://'.$footer_link.'"><img src="'.TEMPLATE_URL.'/images/system/whatsapp_chat.png" alt="" width="65" height="20" /></a>';
                }else{
                    $html.=
                    '<a  alt="Whatsapp Share" href="whatsapp://send?text='.urlencode($art_name).' '.urlencode($url).'"><img src="'.TEMPLATE_URL.'/images/system/whatsapp_share.png" alt="" width="65" height="20" /></a>';
                }
               $html.= '</li>';

//             //  $script .= '<script type="text/javascript" src="//apis.google.com/js/plusone.js">{lang: "de", parsetags: "explicit"}</script><script type="text/javascript">gapi.plusone.go();</script>';
               break;

            case 17: // pinterest
               if ($second === false) {
                  $html   .= '<li class="xpinterest"><div style="inline-block"><a data-pin-do="buttonBookmark" href="https://www.pinterest.com/pin/create/button/"><img src="//assets.pinterest.com/images/pidgets/pinit_fg_en_rect_gray_20.png" /></a></div></li>';
                  $script .= '<script async defer src="//assets.pinterest.com/js/pinit.js"></script>';
               }
               else {
                  $html   .= '<li><div style="inline-block"><a data-pin-do="buttonFollow" href="https://www.pinterest.com/pinterest/">'.$login.'</a></div></li>';
                  $script .= '<script async defer src="//assets.pinterest.com/js/pinit.js"></script>';
               }
               break;

            case 20: // Instagramm
               $html   .= '<li class="xinstagramm"><span class="ig-follow" data-id="5479dee" data-handle="igfbdotcom" data-count="true" data-size="medium" data-username="true"></span><li>';
               // alternativ $script .= '<script>(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];g.src="//x.instagramfollowbutton.com/follow.js";s.parentNode.insertBefore(g,s);}(document,"script"));</script>';
               break;

            case 21: // Youtube
               $html .= '<li class="xyoutube" style="height:24px !important; margin-top:4px !important"><div class="g-ytsubscribe" data-channel="'.$login.'" data-layout="default" data-count="default"></div></li>';
               $script .= '<script src="https://apis.google.com/js/platform.js"></script>';
               break;

            case 9: // LinkedIn
            case 11: // MySpace
            case 12: // Tumblr
            case 15: // Slideshare
            case 16: // Scribed
            case 19: // Flickr
               $html .= str_replace('[URL]', $url, $detail_script);
               break;

            default:
               $html .= '<li class="xdefault"><img class="social_img" src="'.$image.'" alt="" /></li>';
               break;
         }
      }

      return ['img' => $html, 'script' => $script, 'head' => $head];
   }



   // Social-Icons Artikel-Liste
   static public function getSocialData($id) {

       $start = 80 + 10 * CONF_TEMPLATE_ID;

       $sql    = "SELECT * FROM #__social WHERE id = '$id' ORDER BY id";
       $social = self::$db->queryAllObjects($sql);

       if (!$social) {
           return '';
       }

       return current($social);

   }


   // Social-Icons Artikel-Liste
   static public function getSocial() {
      $html = '';
      $start = 80 + 10 * CONF_TEMPLATE_ID;

      $sql    = "SELECT * FROM #__social WHERE footer = 'y' AND (id < 100 OR id > ".$start." AND id < ".($start + 9).") ORDER BY `displayorder`, id";
      $social = self::$db->queryAllObjects($sql);

      if (!$social) {
         return '';
      }



      for ($i = 0; $i < count($social); $i++) {
         $responsive =  'title="'.$social[$i]->name.'"';

         if (defined('CONF_RESPONSIVE')) {
            $responsive = 'data-original-title="'.$social[$i]->name.'" data-toggle="tooltip"';
         }

         if ((int)$social[$i]->id == 1) {
            $html .= "<p class=\"social_".$social[$i]->id."\" ".$responsive." onclick=\"addFavorites('".SHOP_URL_IDX."', '".self::$params->firma['shop_name']."')\"></p>";
         }

         else if ((int)$social[$i]->id == 2) {
            $html .= '<p class="social_'.$social[$i]->id.'" '.$responsive.'><a href="mailto:'.self::$params->firma['email'].'"></a></p>';
         }

         else if((int)$social[$i]->id > 100) {
            $img1 = TEMPLATE_URL.'/images/'.$social[$i]->image.'.png';
            $img2 = TEMPLATE_URL.'/images/'.$social[$i]->image.'_teilen.png';
            $link = $social[$i]->footer_link;

            if ($link != '' && strpos($link, 'http') === false) {
               $link = 'http://'.$link;
            }

            $html .= '<p class="zusatzicon" style="background-image:url('.$img2.');" onmouseover="$(this).css(\'background-image\', \'url('.$img1.')\');" onmouseout="$(this).css(\'background-image\', \'url('.$img2.')\');" '.$responsive.'><a href="'.$link.'" target="_blank"></a></p>';
         }

         else {
            $link = $social[$i]->footer_link;
            if ($link != '' && strpos($link, 'http') === false) {
               $link = 'http://'.$link;
            }
            $html .= '<p class="social_'.$social[$i]->id.'" '.$responsive.'><a href="'.$link.'" target="_blank"></a></p>';
         }
      }
      return $html;
   }

   static public function getFooterNewsletter() { ?>
       <p class="footer_newsletter_icon" onclick="makeNewsletterPopup()"></p>
<?php
   }

   // Midestmenge
   static public function checkMenge($id, $anzahl) {
      $data  = self::$db_extern->querySingleObject("SELECT masse_min, masse_komma FROM #__articles AS a, #__articles_info AS i WHERE a.id = $id AND i.id = a.parent_id");
      $min   = (float)$data->masse_min;
//      $komma = (int)$data->masse_komma;

      if ($anzahl < $min) {
         $anzahl = $min;
      }

      return $anzahl;
   }

   static public function checkString($text) {
      return $text;
      $text2 = md5($_SERVER['SERVER_NAME']);
      $text3 = '';
      for ($i = strlen($text); $i < strlen($text2); $i++) {
         $text .= ' ';
      }

      for ($i = 0; $i < strlen($text2); $i++) {
         $text3 .= $text[$i] ^ $text2[$i];
      }

      return $text3;
   }

   static public function makeRechnungskopf($haendler_id, $lang) {
      $haendler_nr = self::getHaendlerNrByUserId($haendler_id);
      $data        = self::$db->querySingleObject("SELECT h.*, u.* FROM #__users AS u, #__haendler AS h WHERE u.id = $haendler_id AND h.user_id = u.id");

      $img         = null;
      $fontbold    = SHOP_PATH.'/classes/modules/portal/fonts/century-gothic-bold-2.ttf';
      $font        = SHOP_PATH.'/classes/modules/portal/fonts/century-gothic-2.ttf';

      if (!is_dir(TEMPLATE_PATH.'/images/portal')) {
         mkdir(TEMPLATE_PATH.'/images/portal');
      }

      if (is_file(TEMPLATE_PATH.'/images/portal/h'.$haendler_nr.'_rechnungskopf_'.$lang.'.jpg')) {
         copy(TEMPLATE_PATH.'/images/portal/h'.$haendler_nr.'_rechnungskopf_'.$lang.'.jpg', TEMPLATE_PATH.'/images/portal/'.$haendler_nr.'_rechnungskopf_'.$lang.'.jpg');
         return;
      }

      if (is_file(TEMPLATE_PATH.'/images/portal/rechnungskopf_'.$lang.'.jpg')) {
         $img = imagecreatefromjpeg(TEMPLATE_PATH.'/images/portal/rechnungskopf_'.$lang.'.jpg');
      }

      else {
         $img = imagecreatetruecolor(2480, 612);
         $color = imagecolorallocate($img, 255, 255, 255);
         imagefill($img, 0, 0, $color);
      }

      $land  = Control::getLaender();
      $staat = $land->getStaatById($data->staat);
      $color = ImageColorAllocate ($img, 88, 88, 88);

      $adresse_r = 248;
      ImageTTFText($img, 30, 0, $adresse_r, 170, $color, $fontbold, 'VERKÄUFER');
      ImageTTFText($img, 30, 0, $adresse_r, 270, $color, $font, $data->firma);
      ImageTTFText($img, 30, 0, $adresse_r, 320, $color, $font, $data->vorname.' '.$data->nachname);
      ImageTTFText($img, 30, 0, $adresse_r, 370, $color, $font, $data->adresse.' '.$data->hausnr);
      ImageTTFText($img, 30, 0, $adresse_r, 420, $color, $font, $data->plz.' '.$data->ort);
      ImageTTFText($img, 30, 0, $adresse_r, 470, $color, $font, $staat);

      imagejpeg($img, TEMPLATE_PATH.'/images/portal/'.$haendler_nr.'_rechnungskopf_'.$lang.'.jpg');
   }

   // Nur Portal?
   static public function makeRechnungsfuss($haendler_id, $lang) {
      $haendler_nr = self::getHaendlerNrByUserId($haendler_id);
      $data        = self::$db->querySingleObject("SELECT h.h_bank_name, h.h_bank_iban, h.h_bank_bic, h.*, u.* FROM #__users AS u, #__haendler AS h WHERE u.id = $haendler_id AND h.user_id = u.id");
      $img         = null;
      $font        = SHOP_PATH.'/classes/pdf/fonts/century-gothic-2.ttf';

      if (is_file(TEMPLATE_PATH.'/images/portal/h'.$haendler_nr.'_rechnungsfuss_'.$lang.'.jpg')) {
         copy(TEMPLATE_PATH.'/images/portal/h'.$haendler_nr.'_rechnungsfuss_'.$lang.'.jpg', TEMPLATE_PATH.'/images/portal/'.$haendler_nr.'_rechnungsfuss_'.$lang.'.jpg');
         return;
      }

      if (is_file(TEMPLATE_PATH.'/images/portal/rechnungsfuss_'.$lang.'.jpg')) {
         $img = imagecreatefromjpeg(TEMPLATE_PATH.'/images/portal/rechnungsfuss_'.$lang.'.jpg');
      }

      else {
         $img = imagecreatetruecolor(2480, 372);
         $color = imagecolorallocate($img, 255, 255, 255);
         imagefill($img, 0, 0, $color);
      }

      // Adressdaten
      $land = Control::getLaender();
      $staat = $land->getStaatById($data->staat);
      $color = ImageColorAllocate ($img, 88, 88, 88);

      // max. Breite Adressdaten berechnen
      $size     = imagettfbbox(30, 0, $font, $data->firma);
      $breite_a = $size[2];
      $size     = imagettfbbox(30, 0, $font, $data->vorname.' '.$data->nachname);
      $breite_a = max($breite_a, $size[2]);
      $size     = imagettfbbox(30, 0, $font, $data->adresse.' '.$data->hausnr);
      $breite_a = max($breite_a, $size[2]);
      $size     = imagettfbbox(30, 0, $font, $data->ort);
      $breite_a = max($breite_a, $size[2]);
      $size     = imagettfbbox(30, 0, $font, $staat);
      $breite_a = max($breite_a, $size[2]);

      $adresse_r = 248;

      ImageTTFText($img, 30, 0, $adresse_r, 70,  $color, $font, $data->firma);
      ImageTTFText($img, 30, 0, $adresse_r, 120, $color, $font, $data->vorname.' '.$data->nachname);
      ImageTTFText($img, 30, 0, $adresse_r, 170, $color, $font, $data->adresse.' '.$data->hausnr);
      ImageTTFText($img, 30, 0, $adresse_r, 220, $color, $font, $data->plz.' '.$data->ort);
      ImageTTFText($img, 30, 0, $adresse_r, 270, $color, $font, $staat);

      // Bankdaten

      // max. Breite Namen berechnen
      $size       = imagettfbbox(30, 0, $font, 'BANK');
      $breite_b   = $size[2];
      $breite_b_1 = $size[2];
      $size       = imagettfbbox(30, 0, $font, 'IBAN');
      $breite_b_2 = $size[2];
      $breite_b   = max($breite_b, $size[2]);
      $size       = imagettfbbox(30, 0, $font, 'BIC');
      $breite_b_3 = $size[2];
      $breite_b   = max($breite_b, $size[2]);
      $size       = imagettfbbox(30, 0, $font, 'INHABER');
      $breite_b_4 = $size[2];
      $breite_b   = max($breite_b, $size[2]);

      $color  = ImageColorAllocate ($img, 132, 5, 0);
      $bank_l = $adresse_r + $breite_a + 50 + $breite_b;
      $bank_r = $bank_l + 30;

      ImageTTFText($img, 30, 0, ($bank_l - $breite_b_1), 70, $color, $font, 'BANK');
      ImageTTFText($img, 30, 0, ($bank_l - $breite_b_2), 120, $color, $font, 'IBAN');
      ImageTTFText($img, 30, 0, ($bank_l - $breite_b_3), 170, $color, $font, 'BIC');

      if ($data->inhaber != '') {
         ImageTTFText($img, 30, 0, ($bank_l - $breite_b_4), 220, $color, $font, 'INHABER');
      }

      // Werte ausgeben und max. breite Werte berechnen
      $color    = ImageColorAllocate ($img, 88, 88, 88);
      ImageTTFText($img, 30, 0, $bank_r, 70, $color, $font, $data->h_bank_name);
      $size     = imagettfbbox(30, 0, $font, $data->h_bank_name);
      $breite_c = $size[2];
      ImageTTFText($img, 30, 0, $bank_r, 120, $color, $font, $data->h_bank_iban);
      $size     = imagettfbbox(30, 0, $font, $data->h_bank_iban);
      $breite_c = max($breite_c, $size[2]);
      ImageTTFText($img, 30, 0, $bank_r, 170, $color, $font, $data->h_bank_bic);
      $size     = imagettfbbox(30, 0, $font, $data->h_bank_bic);
      $breite_c = max($breite_c, $size[2]);
      if ($data->inhaber != '') {
         ImageTTFText($img, 30, 0, $bank_r, 220, $color, $font, $data->inhaber);
         $size     = imagettfbbox(30, 0, $font, $data->inhaber);
         $breite_c = max($breite_c, $size[2]);
      }

      // Steuer
      $data->steuernr = '';
      $color    = ImageColorAllocate ($img, 132, 5, 0);
      $breite_d = 0;
      $breite_e = 0;

      if ($data->ustid != '') {
         $size       = imagettfbbox(30, 0, $font, 'UST.-IDNR');
         $breite_d_1 = $size[2];
         $breite_d   = max($breite_d, $size[2]);
      }
      if ($data->steuernr != '') {
         $size       = imagettfbbox(30, 0, $font, 'STNR');
         $breite_d_2 = $size[2];
         $breite_d   = max($breite_d, $size[2]);
      }
      if ($data->h_paypal_mail != '') {
         $size       = imagettfbbox(30, 0, $font, 'PAYPAL');
         $breite_d_3 = $size[2];
         $breite_d   = max($breite_d, $size[2]);
      }
      $size       = imagettfbbox(30, 0, $font, 'VERKÄUFER-NR');
      $breite_d_4 = $size[2];
      $breite_d   = max($breite_d, $size[2]);

      $steuer_l = $bank_r + $breite_c + 50 + $breite_d;
      $steuer_r = $steuer_l + 30;
      $pos = 70;

      if ($data->ustid != '') {
         $size = imagettfbbox(30, 0, $font, 'UST.-IDNR');
         ImageTTFText ($img, 30, 0, ($steuer_l - $breite_d_1), $pos, $color, $font, 'UST.-IDNR');
         $pos += 50;
      }

      if ($data->steuernr != '') {
         $size = imagettfbbox(30, 0, $font, 'STNR');
         ImageTTFText ($img, 30, 0, ($steuer_l - $breite_d_2), $pos, $color, $font, 'STNR');
         $pos += 50;
      }

      if ($data->h_paypal_mail != '') {
         $size = imagettfbbox(30, 0, $font, 'PAYPAL');
         ImageTTFText ($img, 30, 0, ($steuer_l - $breite_d_3), $pos, $color, $font, 'PAYPAL');
         $pos += 50;
      }

      $size = imagettfbbox(30, 0, $font, 'VERKÄUFER-NR');
      ImageTTFText ($img, 30, 0, ($steuer_l - $breite_d_4), $pos, $color, $font, 'VERKÄUFER-NR');

      $color = ImageColorAllocate ($img, 88, 88, 88);
      $pos = 70;

      if ($data->ustid != '') {
         ImageTTFText ($img, 30, 0, $steuer_r, $pos, $color, $font, $data->ustid);
         $pos += 50;
      }

      if ($data->steuernr != '') {
         ImageTTFText ($img, 30, 0, $steuer_r, $pos, $color, $font, $data->steuernr);
         $pos += 50;
      }

      if ($data->h_paypal_mail != '') {
      ImageTTFText ($img, 30, 0, $steuer_r, $pos, $color, $font, $data->h_paypal_mail);
         $pos += 50;
      }

      ImageTTFText ($img, 30, 0, $steuer_r, $pos, $color, $font, $data->haendler_nr);

      imagejpeg($img, TEMPLATE_PATH.'/images/portal/'.$haendler_nr.'_rechnungsfuss_'.$lang.'.jpg');
   }

   // Shop: Rechnungsfuß für PDF erstellen
   static public function makeRechnungsfussShop($lang) {
      $firma     = (object)self::$params->firma;
      $adresse_t = 70;
      $font      = SHOP_PATH.'/classes/pdf/fonts/century-gothic-2.ttf';
      $fontsize  = 25;
//      $abstand   = 50;
      $abstand   = 20 + $fontsize;

      // Abstand von links
      $adresse_l = 248;
      // Abstabd von rechts
      $adresse_r  = 2480 - 200;

      // 300 dpi -> 210mm => 2480px, 3,15mm 0> 372px
      $img       = imagecreatetruecolor(2480, 372);

      // Weißer Hintergrund
      $color = imagecolorallocate($img, 255, 255, 255);
      imagefill($img, 0, 0, $color);

      // Schriftfarbe
      $color = ImageColorAllocate ($img, 88, 88, 88);

      // Adressdaten linke Seite
      $breite_l_l = 0;
      $text_l     = [];
      $breite_l   = 0;

      if ($firma->shop_name_check == 'y') {
         $size       = imagettfbbox($fontsize, 0, $font, $firma->shop_name);
         $breite_l_l = max($breite_l_l, $size[2]);
         $text_l[]   = $firma->shop_name;
      }

      if ($firma->firm_name_check == 'y') {
         $size       = imagettfbbox($fontsize, 0, $font, $firma->firm_name);
         $breite_l_l = max($breite_l_l, $size[2]);
         $text_l[]   = $firma->firm_name;
      }

      if ($firma->first_name_check == 'y' || $firma->last_name_check == 'y') {
         $name = '';

         if ($firma->first_name_check == 'y') {
            $name .= $firma->first_name.' ';
         }

         if ($firma->last_name_check == 'y') {
            $name .= $firma->last_name.' ';
         }

         $size       = imagettfbbox($fontsize, 0, $font, $name);
         $breite_l_l = max($breite_l_l, $size[2]);
         $text_l[]   = $name;
      }

      if ($firma->street_check == 'y') {
         $size       = imagettfbbox($fontsize, 0, $font, $firma->street.' '.$firma->haus_nr);
         $breite_l_l = max($breite_l_l, $size[2]);
         $text_l[]   = $firma->street.' '.$firma->haus_nr;
      }

      if ($firma->postal_code_check == 'y' || $firma->city_check == 'y') {
         $name = '';

         if ($firma->postal_code_check == 'y') {
            $name .= $firma->postal_code.' ';
         }

         if ($firma->city_check == 'y') {
            $name .= $firma->city.' ';
         }

         $size       = imagettfbbox($fontsize, 0, $font, $name);
         $breite_l_l = max($breite_l_l, $size[2]);
         $text_l[]   = $name;
      }

      if ($firma->country_check == 'y') {
         $size       = imagettfbbox($fontsize, 0, $font, $firma->country);
         $breite_l_l = max($breite_l_l, $size[2]);
         $text_l[]   = $firma->country;
      }

      $breite_l  = $adresse_l + $breite_l_l;

      // Text linkes in Bild einfügen
      for ($i = 0; $i < count($text_l); $i++) {
         ImageTTFText($img, $fontsize, 0, $adresse_l, $adresse_t + $i * $abstand, $color, $font, $text_l[$i]);
      }

      // Text rechts
      $text_r_l    = [];
      $text_r_r    = [];
      $breit_r_l   = [];
      $breit_r_r   = [];
      $breite_r_l  = 0;
      $breite_r_r  = 0;

      if ($firma->telefon_check == 'y') {
         $size1      = imagettfbbox($fontsize, 0, $font, 'Tel : ');
         $size2      = imagettfbbox($fontsize, 0, $font, $firma->telefon);
         $breite_r_l = max($breite_r_l, $size1[2]);
         $breite_r_r = max($breite_r_r, $size2[2]);

         $breit_r_l[] = $size1[2];
         $breit_r_r[] = $size2[2];
         $text_r_l[] = 'Tel : ';
         $text_r_r[] = $firma->telefon;
      }

      if ($firma->fax_check == 'y') {
         $size1      = imagettfbbox($fontsize, 0, $font, 'Fax : ');
         $size2      = imagettfbbox($fontsize, 0, $font, $firma->fax);
         $breite_r_l = max($breite_r_l, $size1[2]);
         $breite_r_r = max($breite_r_r, $size2[2]);

         $breit_r_l[] = $size1[2];
         $breit_r_r[] = $size2[2];
         $text_r_l[] = 'Fax : ';
         $text_r_r[] = $firma->fax;
      }

      if ($firma->email_check == 'y') {
         $size1      = imagettfbbox($fontsize, 0, $font, 'E-Mail : ');
         $size2      = imagettfbbox($fontsize, 0, $font, $firma->email);
         $breite_r_l = max($breite_r_l, $size1[2]);
         $breite_r_r = max($breite_r_r, $size2[2]);

         $breit_r_l[] = $size1[2];
         $breit_r_r[] = $size2[2];
         $text_r_l[] = 'E-Mail : ';
         $text_r_r[] = $firma->email;
      }

      if ($firma->web_check == 'y') {
         $size1      = imagettfbbox($fontsize, 0, $font, 'Web : ');
         $size2      = imagettfbbox($fontsize, 0, $font, $firma->web);
         $breite_r_l = max($breite_r_l, $size1[2]);
         $breite_r_r = max($breite_r_r, $size2[2]);

         $breit_r_l[] = $size1[2];
         $breit_r_r[] = $size2[2];
         $text_r_l[] = 'Web : ';
         $text_r_r[] = $firma->web;
      }

      if (Helper::getData('shop_frei1_check') == 'y') {
         $size1      = imagettfbbox($fontsize, 0, $font, Helper::getData('shop_frei1_titel').' : ');
         $size2      = imagettfbbox($fontsize, 0, $font, Helper::getData('shop_frei1_text'));
         $breite_r_l = max($breite_r_l, $size1[2]);
         $breite_r_r = max($breite_r_r, $size2[2]);

         $breit_r_l[] = $size1[2];
         $breit_r_r[] = $size2[2];
         $text_r_l[] = Helper::getData('shop_frei1_titel').' : ';
         $text_r_r[] = Helper::getData('shop_frei1_text');
      }

      if (Helper::getData('shop_frei2_check') == 'y') {
         $size1      = imagettfbbox($fontsize, 0, $font, Helper::getData('shop_frei2_titel').' : ');
         $size2      = imagettfbbox($fontsize, 0, $font, Helper::getData('shop_frei2_text'));
         $breite_r_l = max($breite_r_l, $size1[2]);
         $breite_r_r = max($breite_r_r, $size2[2]);

         $breit_r_l[] = $size1[2];
         $breit_r_r[] = $size2[2];
         $text_r_l[] = Helper::getData('shop_frei2_titel').' : ';
         $text_r_r[] = Helper::getData('shop_frei2_text');
      }

      if (Helper::getData('shop_frei3_check') == 'y') {
         $size1      = imagettfbbox($fontsize, 0, $font, Helper::getData('shop_frei3_titel').' : ');
         $size2      = imagettfbbox($fontsize, 0, $font, Helper::getData('shop_frei3_text'));
         $breite_r_l = max($breite_r_l, $size1[2]);
         $breite_r_r = max($breite_r_r, $size2[2]);

         $breit_r_l[] = $size1[2];
         $breit_r_r[] = $size2[2];
         $text_r_l[] = Helper::getData('shop_frei3_titel').' : ';
         $text_r_r[] = Helper::getData('shop_frei3_text');
      }

      // Text rchts in Bild einfügen
      for ($i = 0; $i < count($text_r_l); $i++) {
         $pos = $adresse_r - ($breite_r_l + $breite_r_r) + $breite_r_l - $breit_r_l[$i];
         ImageTTFText($img, $fontsize, 0, $pos, $adresse_t + $i * $abstand, $color, $font, $text_r_l[$i].$text_r_r[$i]);
      }

      $breite_r = $breite_r_l + $breite_r_r + 2480 - $adresse_r;

      // Text mitte
      $text_m_l    = [];
      $text_m_r    = [];
      $breit_m_l   = [];
      $breit_m_r   = [];
      $breite_m_l  = 0;
      $breite_m_r  = 0;

      if ($firma->bank1_check == 'y') {
         $size1      = imagettfbbox($fontsize, 0, $font, self::$text->get('shop', 'bank', $lang).' : ');
         $size2      = imagettfbbox($fontsize, 0, $font, $firma->bank1);
         $breite_m_l = max($breite_m_l, $size1[2]);
         $breite_m_r = max($breite_m_r, $size2[2]);

         $breit_m_l[] = $size1[2];
         $breit_m_r[] = $size2[2];
         $text_m_l[] = self::$text->get('shop', 'bank', $lang).' : ';
         $text_m_r[] = $firma->bank1;
      }

      if ($firma->bank1_iban_check == 'y') {
         $size1      = imagettfbbox($fontsize, 0, $font, self::$text->get('shop', 'iban', $lang).' : ');
         $size2      = imagettfbbox($fontsize, 0, $font, $firma->bank1_iban);
         $breite_m_l = max($breite_m_l, $size1[2]);
         $breite_m_r = max($breite_m_r, $size2[2]);

         $breit_m_l[] = $size1[2];
         $breit_m_r[] = $size2[2];
         $text_m_l[] = self::$text->get('shop', 'iban', $lang).' : ';
         $text_m_r[] = $firma->bank1_iban;
      }

      if ($firma->bank1_bic_check == 'y') {
         $size1      = imagettfbbox($fontsize, 0, $font, self::$text->get('shop', 'bic', $lang).' : ');
         $size2      = imagettfbbox($fontsize, 0, $font, $firma->bank1_bic);
         $breite_m_l = max($breite_m_l, $size1[2]);
         $breite_m_r = max($breite_m_r, $size2[2]);

         $breit_m_l[] = $size1[2];
         $breit_m_r[] = $size2[2];
         $text_m_l[] = self::$text->get('shop', 'bic', $lang).' : ';
         $text_m_r[] = $firma->bank1_bic;
      }

      if ($firma->bank1_inhaber_check == 'y') {
         $size1      = imagettfbbox($fontsize, 0, $font, self::$text->get('shop', 'inhaber', $lang).' : ');
         $size2      = imagettfbbox($fontsize, 0, $font, $firma->bank1_inhaber);
         $breite_m_l = max($breite_m_l, $size1[2]);
         $breite_m_r = max($breite_m_r, $size2[2]);

         $breit_m_l[] = $size1[2];
         $breit_m_r[] = $size2[2];
         $text_m_l[] = self::$text->get('shop', 'inhaber', $lang).' : ';
         $text_m_r[] = $firma->bank1_inhaber;
      }

      if ($firma->finanzamt_check == 'y') {
         $size1      = imagettfbbox($fontsize, 0, $font, self::$text->get('shop', 'finanzamt', $lang).' : ');
         $size2      = imagettfbbox($fontsize, 0, $font, $firma->finanzamt);
         $breite_m_l = max($breite_m_l, $size1[2]);
         $breite_m_r = max($breite_m_r, $size2[2]);

         $breit_m_l[] = $size1[2];
         $breit_m_r[] = $size2[2];
         $text_m_l[] = self::$text->get('shop', 'finanzamt', $lang).' : ';
         $text_m_r[] = $firma->finanzamt;
      }

      if ($firma->ustid_check == 'y') {
         $size1      = imagettfbbox($fontsize, 0, $font, self::$text->get('shop', 'ustid', $lang).' : ');
         $size2      = imagettfbbox($fontsize, 0, $font, $firma->ustid);
         $breite_m_l = max($breite_m_l, $size1[2]);
         $breite_m_r = max($breite_m_r, $size2[2]);

         $breit_m_l[] = $size1[2];
         $breit_m_r[] = $size2[2];
         $text_m_l[] = self::$text->get('shop', 'ustid', $lang).' : ';
         $text_m_r[] = $firma->ustid;
      }


      // Text mitte in Bild einfügen
      $offset = round((2480 - $breite_l - $breite_r - $breite_m_l - $breite_m_r) / 2);

      for ($i = 0; $i < count($text_m_l); $i++) {
//         $pos = 2480 / 2 - 50 - (($breite_m_l + $breite_m_r) / 2) + $breite_m_l - $breit_m_l[$i];
         $pos = $breite_l + $offset + $breite_m_l - $breit_m_l[$i];
         ImageTTFText($img, $fontsize, 0, $pos, $adresse_t + $i * $abstand, $color, $font, $text_m_l[$i].$text_m_r[$i]);
      }

      // Strich oben
//      ImageTTFText($img, 10, 0, $adresse_l, 20, $color, $font, str_repeat('. ', 265));
      ImageTTFText($img, 10, 0, $adresse_l, 20, $color, $font, str_repeat('. ', 265));

      imagejpeg($img, TEMPLATE_PATH.'/images/rechnungsfuss_'.$lang.'.jpg');
   }

   static function getStaatName($staat, $staat2, $lang = 'deu') {
      if ((int)$staat == 10) {
         return $staat2;
      }

      $land = '';
      if ((int)$staat > 0) {
         $land = self::$db->querySingleValue("SELECT ".($lang == 'deu' ? 'name' : 'name_shop')." FROM #__laender WHERE id = $staat");
      }
      return $land;
   }

   static function getKeywords($seite = 'starthtml', $lang = 'deu') {
      $data = (object)['titeltag' => '', 'keywords' => '', 'description' => ''];
      $task = $seite;
      $catname_found = false;

      if ($seite == '' || $seite == 'artikel' || $seite == 'kategorie') {
         $seite = 'starthtml';
      }

      if ($lang == '') {
         $lang = 'deu';
      }

      // keywords usw. normal verwenden
      $data2 = self::$db->querySingleObject("SELECT titeltag, keywords, description FROM #__keywords WHERE lang = '$lang' AND seite = '$seite'");

      if ($data2) {
         $data = $data2;
      }

      // Bei Titelseite korrigieren
      if ($data2 && $seite == 'starthtml' && $data->titeltag == '') {
         $data->titeltag = self::$params->firma['shop_name'];
      }

      // Bei texteSeiten nicht gefunden/angegeben -> starthtml
      if (!is_object($data) && $seite != 'starthtml') {
         $data = self::$db->querySingleObject("SELECT titeltag, keywords, description FROM #__keywords WHERE lang = '$lang' AND seite = 'starthtml'");
      }

      if ($task == 'artikel' && self::$params->art_id > 0) {
         $data2 = self::$db_extern->querySingleObject("SELECT s.metatitle AS titeltag, s.metadesc AS description, s.metakey AS keywords FROM #__articles_seo AS s, #__articles AS a WHERE a.id = ".self::$params->art_id." AND a.parent_id = s.parent_id AND s.lang = '$lang'");

         if ($data2) {
            $data = $data2;
         }

         // Article hat noch keinen Eintrag in articles_seo
         else {
            $data2 = self::$db_extern->querySingleObject("SELECT name_$lang AS titeltag, desc_$lang AS description, desc_$lang AS keywords FROM #__articles_info AS i, #__articles AS a WHERE a.id = ".self::$params->art_id." AND a.parent_id = i.id");

            if ($data2) {
               $data2->description = Helper::truncate(strip_tags($data2->description), 160);
               $data2->keywords    = Helper::truncate(strip_tags($data2->keywords), 160);
               $data = $data2;
            }
         }
      }

      // Nur bei Artikel / Kategorie: Daten aus Kategorie lesen
      // keywords_<lang> ist titletag_<lang>!!!
      $sql = '';
      $data2 = null;

      if ($task == 'kategorie') {
         if (self::$params->kat_id > 0) {
            $sql = "SELECT description_".$lang." AS description, title_".$lang." AS titeltag, name_".$lang." AS name, desc_".$lang." AS descr, keywords_$lang AS keywords
                       FROM #__categories
                    WHERE id = ".self::$params->kat_id;
         }

         if ($sql != '') {
            $data2 = self::$db_extern->querySingleObject($sql);
         }

         // Daten gefunden
         if ($data2) {
            // keywords_<lang> ist titletag_<lang>!!!
            //
            $cat_description = $data2->description;
            $cat_titeltag    = $data2->titeltag;
            $cat_desc        = str_ireplace('[TRENNER]', ' ', $data2->descr);
            $cat_name        = $data2->name;
            $data->keywords  = $data2->keywords;

            // Desctiption
            if ($cat_description != '' && $cat_description != ' ') {
               $data->description = $cat_description;
            }

            // Wenn nicht vorhanden Artikelbeschreibung
            else if ($cat_desc != '' && $cat_desc != ' ') {
               $data->description = Helper::truncate(strip_tags($cat_desc), 250);
            }

            // Wenn auch nicht vorhanden Kategoriename vor Default-Description setzen
            else if ($cat_name != '') {
               $data->description = $cat_name.' '.$data->description;
            }

            // Titeltag
            if ($cat_titeltag != '') {
               $catname_found = true;
               $data->titeltag = $cat_titeltag;
            }
            // Wenn nicht vorhanden Kategoriename
            else if ($cat_name != '') {
               $data->titeltag = $cat_name;
            }

            // Keywords - Kategorienam davor setzen
            else if ($cat_name != '') {
               $data->keywords = $cat_name.' '.$data2->keywords;
            }
         }

         // Bei Detailseite
         if (self::$params->art_id > 0) {
            $data2 = self::$db_extern->querySingleObject("SELECT i.name_$lang AS art_name, i.desc_$lang AS `description`
                                                      FROM #__articles_info as i, #__articles as a
                                                   WHERE a.id = ".self::$params->art_id."
                                                      AND i.id = a.parent_id");

            if ($data2) {
               $art_description = Helper::truncate(strip_tags($data2->description, 250));
               $art_titeltag    = $data2->art_name;

               if ($art_description != '') {
                  $data->description = $art_description;
               }

               if ($art_titeltag != '') {
                  $data->titeltag = $art_titeltag;
               }
            }
         }
      }

      return $data;
   }

   static public function werteImgList($list_arr, $liste, $selected, $breite, $padding, $links) {
      $html = '';
      $start = 0;
      $html .= '<div class="merkmal1_bg bg_flaechen">';

      foreach ($list_arr as $wert) {
         if (is_file(TEMPLATE_PATH.'/images/grafische_werte/'.$wert->{'wert_img'.$liste})) {
            $img     = TEMPLATE_PATH.'/images/grafische_werte/'.$wert->{'wert_img'.$liste};
            $img_url = TEMPLATE_URL.'/images/grafische_werte/'.$wert->{'wert_img'.$liste}.self::$params->firma['image_cache'];
         }
         else {
            $img     = TEMPLATE_PATH.'/images/system/nopic_mw.jpg';
            $img_url = TEMPLATE_URL.'/images/system/nopic_mw.jpg';
         }

         $size = [20,30];

         if (is_file($img)) {
            $size = getimagesize($img);
         }

         if (($breite > 0 && ($size[0] + $start + $links + 2*$padding) > $breite)) {
            $start = 0;
            $html .= '<div class="clear"></div>';
            $html .= '</div>';
            $html .= '<div style="height:1px; background-color:transparent"></div>';
            $html .= '<div class="merkmal1_bg bg_flaechen">';
         }

         if ($breite > 0 && $start == 0) {
            $html .= '<div style="position:relative; width:'.$links.'px; height:20px; float:left;"></div>';
         }

         $werte = self::$params->getWerte($wert->merkmal1, $wert->wert1, $wert->merkmal2, $wert->wert2);
         $start += $size[0] + 2*$padding;
         $html .= '<div class="wert_img_out'.($wert->id == $selected ? ' wert_selected' : '').'" style="padding-left:'.$padding.'px; padding-right:'.$padding.'px;">';
         $html .= '   <a href="'.self::$params->getlink('artikel', $wert->id, $wert->name, $werte, $wert->kategoriename, $werte).'" title="'.$wert->{'wert'.$liste}.'" style="display:inline-block; vertical-align:top; width:'.$size[0].'px; height:'.$size[1].'px;">';
         $html .= '      <img src="'.$img_url.self::$params->firma['image_cache'].'" alt="" style="width:'.$size[0].'px; height:'.$size[1].'px;" />';
         $html .= '   </a>';
         $html .= '</div>';
      }

      $html .= '<div class="clear"></div>';
      $html .= '</div>';
      return $html;
   }

   /* ********************** Funktionen für Zahlungsarten ******************
   *
   * Modul Wiso / individuell.php auch berücksichtigen
   * Modul billbee auch berücksichtigen */
   public static function getZahlartDefault() {
      $zahlart = -1;

      if (self::$params->firma['za_waehlen_check'] == 'y')         { $zahlart = 0; }
      else if (self::$params->firma['vorkasse_check'] == 'y')      { $zahlart = 1; }
      else if (self::$params->firma['bar_check'] == 'y')           { $zahlart = 6; }
      else if (self::$params->firma['rechnung_check'] == 'y')      { $zahlart = 5; }
      else if (self::$params->firma['nachnahme_check'] == 'y')     { $zahlart = 4; }
      else if (self::$params->firma['twint_check'] == 'y')         { $zahlart = 12; }
      else if (self::$params->firma['wir_check'] == 'y')           { $zahlart = 16; }
      else if (self::$params->firma['paypal_check'] == 'y')        { $zahlart = 2; }
      else if (self::$params->firma['paypalplus_check'] == 'y')    { $zahlart = 10; }
      else if (self::$params->firma['sofort_check'] == 'y')        { $zahlart = 7; }
      else if (self::$params->firma['vrpay_check'] == 'y')         { $zahlart = 8; }
      else if (self::$params->firma['lastschrift_check'] == 'y')   { $zahlart = 3; }
      else if (self::$params->firma['kklastschrift_check'] == 'y') { $zahlart = 9; }
      else if (self::$params->firma['easycredit_check'] == 'y')    { $zahlart = 13; }
      else if (self::$params->firma['amazon_check'] == 'y')        { $zahlart = 11; }
      else if (self::$params->firma['klarna_check'] == 'y')        { $zahlart = 14; }
      else if (self::$params->firma['paydirekt_check'] == 'y')     { $zahlart = 15; }
      else if (self::$params->firma['wir_check'] == 'y')           { $zahlart = 16; }
      else if (self::$params->firma['postfinance_check'] == 'y')   { $zahlart = 17; }
      else if (self::$params->firma['paypalv2_check'] == 'y')      { $zahlart = 18; }
      else if (self::$params->firma['mollie_check'] == 'y')      { $zahlart = 19; }

      return $zahlart;
   }

   public static function getZahlartText($zahlart, $lang = '', $mode = '') {
      $bez_text = '';

      if ($mode == '') {
         switch ((int)$zahlart) {
            case 0:
               $bez_text = self::$text->get('zahlart', 'leer', $lang);
               break;

            case 1:
               $bez_text = self::$text->get('zahlart', 'vorkasse', $lang);
               break;

            case 2:
               $bez_text = self::$text->get('zahlart', 'paypal', $lang);
               break;

            case 3:
               $bez_text = self::$text->get('zahlart', 'lastschrift', $lang);
               break;

            case 4:
               $bez_text = self::$text->get('zahlart', 'nachnahme', $lang);
               break;

            case 5:
               $bez_text = self::$text->get('zahlart', 'rechnung', $lang);
               break;

            case 6:
               $bez_text = self::$text->get('zahlart', 'bar', $lang);
               break;

            case 7:
               $bez_text = self::$text->get('zahlart', 'sofort', $lang);
               break;

            case 8:
               $bez_text = self::$text->get('zahlart', 'vrpay', $lang);
               break;

            case 9:
               $bez_text = self::$text->get('zahlart', 'kk_lastschrift', $lang);
               break;

            case 10:
               $bez_text = self::$text->get('zahlart', 'paypalplus', $lang);
               break;

            case 11:
               $bez_text = self::$text->get('zahlart', 'amazon', $lang);
               break;

            case 12:
               $bez_text = self::$text->get('zahlart', 'twint', $lang);
               break;

            case 13:
               $bez_text = self::$text->get('zahlart', 'easycredit', $lang);
               break;

            case 14:
               $bez_text = self::$text->get('zahlart', 'klarna', $lang);
               break;

            case 15:
               $bez_text = self::$text->get('zahlart', 'paydirekt', $lang);
               break;

            case 16:
               $bez_text = self::$text->get('zahlart', 'wir', $lang);
               break;

            case 17:
               $bez_text = self::$text->get('zahlart', 'postfinance', $lang);
               break;

            case 18:
               $bez_text = self::$text->get('zahlart', 'paypalv2', $lang);
               break;

            case 19:
               $bez_text = self::$text->get('zahlart', 'mollie', $lang);
               break;
         }
      }

      else {
         switch ((int)$zahlart) {
            case 1:
               $bez_text = 'VORKASSE';
               break;

            case 2:
               $bez_text = 'VORKASSE';
               break;

            case 3:
               $bez_text = 'VORKASSE';
               break;

            case 4:
               $bez_text = 'VORKASSE';
               break;

            case 5:
               $bez_text = 'VORKASSE';
               break;

            case 6:
               $bez_text = 'VORKASSE';
               break;

            case 7:
               $bez_text = 'VORKASSE';
               break;

            case 8:
               $bez_text = 'VORKASSE';
               break;

            case 9:
               $bez_text = 'VORKASSE';
               break;

            case 10:
               $bez_text = 'VORKASSE';
               break;

            case 11:
               $bez_text = 'VORKASSE';
               break;

            case 12:
               $bez_text = 'VORKASSE';
               break;

            case 13:
               $bez_text = 'VORKASSE';
               break;

            case 14:
               $bez_text = 'VORKASSE';
               break;

            case 15:
               $bez_text = 'VORKASSE';
               break;

            case 16:
               $bez_text = 'VORKASSE';
               break;

            case 17:
               $bez_text = 'VORKASSE';
               break;

            case 18:
               $bez_text = 'VORKASSE';
               break;

            case 19:
               $bez_text = 'VORKASSE';
               break;
         }
      }

      return $bez_text;
   }

   // $berechnung: Object: FE; 0: Admin/Bestellungen -> kein Preis anzeigen
   // $abholpreis -> Admin/Versandeinstellungen
   static public function getZahlartOptions($zahlart, $abholpreis, $tab = 1, $berechnung = null, $steuersatz = null) {
      $html = '';
      $versandart_land = (int)self::$params->firma['versandart_land'];
      $versand_land    = (int)self::$params->firma['versandart_land'];
      $user_id         = (int)self::$params->user_id;
      $role            = 8;

      if (isset($_SESSION['user']['role'])) {
         $role = (int)$_SESSION['user']['role'];
      }

      if (isset($_SESSION['wk_land'])) {
         $versand_land = $_SESSION['wk_land'];
      }

      if (self::$params->postInt('versand_land') > 0) {
         $versand_land = self::$params->postInt('versand_land');
      }

      // Nur FE, bei Admin/Bestellung verhindern
      if (is_object($berechnung) && self::$params->firma['za_waehlen_check'] == 'y') {
         $html .= '<option value="0"'.($zahlart == 0 ? ' selected="selected" ' : '').'>'.self::$text->get('bitte', 'waehlen').'</option>'.CR;
      }

      // Nicht FE, bei Admin/Bestellung anzeigen
      if (!is_object($berechnung)) {
         $html .= '<option value="0"'.($zahlart == 0 ? ' selected="selected" ' : '').'>ohne Zahlungsart</option>'.CR;
      }

      // Überweisung / Vorkasse
      if (self::$params->firma['vorkasse_check'] == 'y') {
         $sign  = ((float)self::$params->firma['vorkasse_preis'] > 0 ? '-' : ((float)self::$params->firma['vorkasse_preis'] == 0 ? '' : '+'));
         $preis = (self::$params->firma['vorkasse_preis'] != 0 ? $sign.number_format(abs(self::$params->firma['vorkasse_preis']), 2, ',', '').' %' : '');

         $html .= '<option value="1"'.($zahlart == 1 ? ' selected="selected" ' : '').'>'.self::$text->get('zahlart', 'vorkasse').' '.(!is_object($berechnung) ? '' : $preis).'</option>'.CR;
      }

      // Bar bei Abholung
      if (self::$params->firma['bar_check'] == 'y') {
         $abholung = self::$params->firma['abholung_check_'.$tab] == 'y' ? ($abholpreis != 0 ? " (".Helper::number_format($abholpreis, 2, ',', '').self::$params->waehrung.")" : '') : '';
         $sign     = (((float)self::$params->firma['bar_preis'] + $abholpreis) > 0 ? '-' : ((float)self::$params->firma['bar_preis'] == 0 ? '' : '+'));
         $preis    = (float)self::$params->firma['bar_preis'] != 0 ? ' '.$sign.number_format(abs(self::$params->firma['bar_preis']), 2, ',', '')." %" : '';

         $html .= '<option value="6"'.($zahlart == 6 ? ' selected="selected" ' : '').'>'.self::$text->get('zahlart', 'bar').(!is_object($berechnung) ? '' : $abholung.$preis).'</option>'.CR;
      }

      // Rechnung
      // Stammkunden / Nur Deutschland, Adminbestellung anzeigen
      // Admin-Bestellung oder Rechnung aktiv
      if (!is_object($berechnung) || self::$params->firma['rechnung_check'] == 'y') {
         // Admin-Betellung oder Kundebezogen oder
         if (!is_object($berechnung)
            ||
            (self::$params->firma['rechnung_check_user'] == 'y' && $_SESSION['user']['rechnung_kunde'] == 'y')  && self::$params->firma['rechnung_check_country'] == 'n'
            ||
            (self::$params->firma['rechnung_check_user'] == 'y' && $_SESSION['user']['rechnung_kunde'] == 'y' && self::$params->firma['rechnung_check_country'] == 'y' && (int)$versand_land === (int)$versandart_land)
            ||
            self::$params->firma['rechnung_check_user'] == 'n' && self::$params->firma['rechnung_check_country'] == 'n'
            ||
            self::$params->firma['rechnung_check_user'] == 'n' && self::$params->firma['rechnung_check_country'] == 'y' && (int)$versand_land === (int)$versandart_land
          )
         {

            $sign  = ((float)self::$params->firma['rechnung_preis'] > 0 ? '-' : ((float)self::$params->firma['rechnung_preis'] == 0 ? '' : '+'));
            $preis = (float)self::$params->firma['rechnung_preis'] != 0 ? ' '.$sign.number_format(abs(self::$params->firma['rechnung_preis']), 2, ',', '').' %' : '';

            $html .= '<option value="5"'.($zahlart == 5 ? ' selected="selected" ' : '').'>'.self::$text->get('zahlart', 'rechnung').(!is_object($berechnung) ? '' : $preis).'</option>'.CR;
         }
      }

      // Nachnahme - USt WK berücksichtigen
      // Betrag - Vorzeichen beachten / Positiver Wert wird addiert!
      // Stammkunden / Nur Deutschland,  Adminbestellung anzeigen
      if (!is_object($berechnung) || self::$params->firma['nachnahme_check'] == 'y'
         && (self::$params->firma['nachnahme_check_user'] == 'n' || self::$params->firma['nachnahme_check_user'] == 'y' && $role > 10 && $role < 17)
         && (self::$params->firma['nachnahme_check_country'] == 'n' || self::$params->firma['nachnahme_check_country'] == 'y' && $versand_land == $versandart_land))
      {
         $sign  = ((float)self::$params->firma['nachnahme_preis'] > 0 ? '+' : ((float)self::$params->firma['nachnahme_preis'] == 0 ? '' : '-'));
         $p     = !is_object($berechnung) ? ['brutto' => 0] : $berechnung->berechnePreis((float)self::$params->firma['nachnahme_preis'], $steuersatz, false);
         $preis = (float)self::$params->firma['nachnahme_preis'] != 0 ? ' '.$sign.number_format(abs($p['brutto']), 2, ',', '').' '.self::$params->waehrung : '';

         $html .= '<option value="4"'.($zahlart == 4 ? ' selected="selected" ' : '').'>'.self::$text->get('zahlart', 'nachnahme').(!is_object($berechnung) ? '' : $preis).'</option>'.CR;
      }

      // Twint
      if (self::$params->firma['twint_check'] == 'y') {
         $sign  = ((float)self::$params->firma['twint_preis'] > 0 ? '-' : ((float)self::$params->firma['twint_preis'] == 0 ? '' : '+'));
         $preis = (float)self::$params->firma['twint_preis'] != 0 ? ' '.$sign.number_format(abs(self::$params->firma['twint_preis']), 2, ',', '').' %' : '';

         $html .= '<option value="12"'.($zahlart == 12 ? ' selected="selected" ' : '').'>'.self::$text->get('zahlart', 'twint').(!is_object($berechnung) ? '' : $preis).'</option>'.CR;
      }

      // WIR
      if (self::$params->firma['wir_check'] == 'y') {
         $html .= '<option value="16"'.($zahlart == 16 ? ' selected="selected" ' : '').'>'.self::$text->get('zahlart', 'wir').(!is_object($berechnung) ? '' : $preis).'</option>'.CR;
      }

      // Paypal
      if (self::$params->firma['paypal_check'] == 'y') {
         $sign  = ((float)self::$params->firma['paypal_preis'] > 0 ? '-' : ((float)self::$params->firma['paypal_preis'] == 0 ? '' : '+'));
         $preis = (float)self::$params->firma['paypal_preis'] != 0 ? ' '.$sign.number_format(abs(self::$params->firma['paypal_preis']), 2, ',', '').' %' : '';

         $html .= '<option value="2"'.($zahlart == 2 ? ' selected="selected" ' : '').'>'.self::$text->get('zahlart', 'paypal').(!is_object($berechnung) ? '' : $preis).'</option>'.CR;
      }

      // Paypal v2
      if (self::$params->firma['paypalv2_check'] == 'y') {
         $sign  = ((float)self::$params->firma['paypalv2_preis'] > 0 ? '-' : ((float)self::$params->firma['paypalv2_preis'] == 0 ? '' : '+'));
         $preis = (float)self::$params->firma['paypalv2_preis'] != 0 ? ' '.$sign.number_format(abs(self::$params->firma['paypalv2_preis']), 2, ',', '').' %' : '';

         $html .= '<option value="18"'.($zahlart == 18 ? ' selected="selected" ' : '').'>'.self::$text->get('zahlart', 'paypalv2').(!is_object($berechnung) ? '' : $preis).'</option>'.CR;
      }

      // Paypal Plus
      if (self::$params->firma['paypalplus_check'] == 'y') {
         $sign  = ((float)self::$params->firma['paypalplus_preis'] > 0 ? '-' : ((float)self::$params->firma['paypalplus_preis'] == 0 ? '' : '+'));
         $preis = (float)self::$params->firma['paypalplus_preis'] != 0 ? ' '.$sign.number_format(abs(self::$params->firma['paypalplus_preis']), 2, ',', '').' %' : '';

         $html .= '<option value="10"'.($zahlart == 10 ? ' selected="selected" ' : '').'>'.self::$text->get('zahlart', 'paypalplus').(!is_object($berechnung) ? '' : $preis).'</option>'.CR;
      }

      // Sofortüberweisung
      if (self::$params->firma['sofort_check'] == 'y') {
         $sign  = ((float)self::$params->firma['sofort_preis'] > 0 ? '-' : ((float)self::$params->firma['sofort_preis'] == 0 ? '' : '+'));
         $preis = (float)self::$params->firma['sofort_preis'] != 0 ? ' '.$sign.number_format(abs(self::$params->firma['sofort_preis']), 2, ',', '').' %' : '';

         $html .= '<option value="7"'.($zahlart == 7 ? ' selected="selected" ' : '').'>'.self::$text->get('zahlart', 'sofort').(!is_object($berechnung) ? '' : $preis).'</option>'.CR;
      }

      // VR-Pay
      if (self::$params->firma['vrpay_check'] == 'y') {
         $sign  = ((float)self::$params->firma['vrpay_preis'] > 0 ? '-' : ((float)self::$params->firma['vrpay_preis'] == 0 ? '' : '+'));
         $preis = (float)self::$params->firma['vrpay_preis'] != 0 ? ' '.$sign.number_format(abs(self::$params->firma['vrpay_preis']), 2, ',', '').' %' : '';

         $html .= '<option value="8"'.($zahlart == 8 ? ' selected="selected" ' : '').'>'.self::$text->get('zahlart', 'vrpay').(!is_object($berechnung) ? '' : $preis).'</option>'.CR;
      }

      // Einzugsermächtigung / SEPA-Lastschrift
      // Stammkunden / Nur Deutschland
      if (self::$params->firma['lastschrift_check'] == 'y'
         && (self::$params->firma['lastschrift_check_user'] == 'n' || self::$params->firma['lastschrift_check_user'] == 'y' && $role > 10 && $role < 17)
         && (self::$params->firma['lastschrift_check_country'] == 'n' || self::$params->firma['lastschrift_check_country'] == 'y' && $versand_land == $versandart_land))
      {
         $sign  = ((float)self::$params->firma['lastschrift_preis'] > 0 ? '-' : ((float)self::$params->firma['lastschrift_preis'] == 0 ? '' : '+'));
         $preis = (float)self::$params->firma['lastschrift_preis'] != 0 ? ' '.$sign.number_format(abs(self::$params->firma['lastschrift_preis']), 2, ',', '').' %' : '';

         $html .= '<option value="3"'.($zahlart == 3 ? ' selected="selected" ' : '').'>'.self::$text->get('zahlart', 'lastschrift').(!is_object($berechnung) ? '' : $preis).'</option>'.CR;
      }

      // Einzug Kreditkarte
      if (self::$params->firma['kklastschrift_check'] == 'y') {
         $sign  = ((float)self::$params->firma['kklastschrift_preis'] > 0 ? '-' : ((float)self::$params->firma['kklastschrift_preis'] == 0 ? '' : '+'));
         $preis = (float)self::$params->firma['kklastschrift_preis'] != 0 ? ' '.$sign.number_format(abs(self::$params->firma['kklastschrift_preis']), 2, ',', '').' %' : '';

         $html .= '<option value="9"'.($zahlart == 9 ? ' selected="selected" ' : '').'>'.self::$text->get('zahlart', 'kk_lastschrift').(!is_object($berechnung) ? '' : $preis).'</option>'.CR;
      }

      // Easycredit
      if (self::$params->firma['easycredit_check'] == 'y' && (!isset($_SESSION['easycredit_deny']) || $_SESSION['easycredit_deny'] == '')) {
         $sign  = ((float)self::$params->firma['easycredit_preis'] > 0 ? '-' : ((float)self::$params->firma['easycredit_preis'] == 0 ? '' : '+'));
         $preis = (float)self::$params->firma['easycredit_preis'] != 0 ? ' '.$sign.number_format(abs(self::$params->firma['easycredit_preis']), 2, ',', '').' %' : '';

         $html .= '<option value="13"'.($zahlart == 13 ? ' selected="selected" ' : '').' data-toggle="tooltip" data-original-title="Erst nach Login verfügbar<br />Mindestbestellwert 200,00€">'.self::$text->get('zahlart', 'easycredit').(!is_object($berechnung) ? '' : $preis).'</option>'.CR;
      }

      // Amazon
      if (self::$params->firma['amazon_check'] == 'y') {
         $sign  = ((float)self::$params->firma['amazon_preis'] > 0 ? '-' : ((float)self::$params->firma['amazon_preis'] == 0 ? '' : '+'));
         $preis = (float)self::$params->firma['amazon_preis'] != 0 ? ' '.$sign.number_format(abs(self::$params->firma['amazon_preis']), 2, ',', '').' %' : '';

         $html .= '<option value="11"'.($zahlart == 11 ? ' selected="selected" ' : '').'>'.self::$text->get('zahlart', 'amazon').(!is_object($berechnung) ? '' : $preis).'</option>'.CR;
      }

      // Klarna
      if (self::$params->firma['klarna_check'] == 'y') {
         $sign  = ((float)self::$params->firma['klarna_preis'] > 0 ? '-' : ((float)self::$params->firma['klarna_preis'] == 0 ? '' : '+'));
         $preis = (float)self::$params->firma['klarna_preis'] != 0 ? ' '.$sign.number_format(abs(self::$params->firma['klarna_preis']), 2, ',', '').' %' : '';

         $html .= '<option value="14"'.($zahlart == 14 ? ' selected="selected" ' : '').'>'.self::$text->get('zahlart', 'klarna').(!is_object($berechnung) ? '' : $preis).'</option>'.CR;
      }

      // Mollie
      if (self::$params->firma['mollie_check'] == 'y') {
         $sign  = ((float)self::$params->firma['mollie_preis'] > 0 ? '-' : ((float)self::$params->firma['mollie_preis'] == 0 ? '' : '+'));
         $preis = (float)self::$params->firma['mollie_preis'] != 0 ? ' '.$sign.number_format(abs(self::$params->firma['mollie_preis']), 2, ',', '').' %' : '';

         $html .= '<option value="19"'.($zahlart == 19 ? ' selected="selected" ' : '').'>'.self::$text->get('zahlart', 'mollie').(!is_object($berechnung) ? '' : $preis).'</option>'.CR;
      }

      // PayDirekt
      if (self::$params->firma['paydirekt_check'] == 'y') {
         $sign  = ((float)self::$params->firma['paydirekt_preis'] > 0 ? '-' : ((float)self::$params->firma['paydirekt_preis'] == 0 ? '' : '+'));
         $preis = (float)self::$params->firma['paydirekt_preis'] != 0 ? ' '.$sign.number_format(abs(self::$params->firma['paydirekt_preis']), 2, ',', '').' %' : '';

         $html .= '<option value="15"'.($zahlart == 15 ? ' selected="selected" ' : '').'>'.self::$text->get('zahlart', 'paydirekt').(!is_object($berechnung) ? '' : $preis).'</option>'.CR;
      }

      // Postfinance
      if (self::getData('postfinance_check', 'n') == 'y') {
         $sign  = ((float)self::getData('postfinance_preis', 0) > 0 ? '-' : ((float)self::getData('postfinance_preis', 0) == 0 ? '' : '+'));
         $preis = (float)self::getData('postfinance_preis') != 0 ? ' '.$sign.number_format(abs(self::getData('postfinance_preis', 0)), 2, ',', '').' %' : '';
         $html .= '<option value="17"'.($zahlart == 17 ? ' selected="selected" ' : '').'>'.self::$text->get('zahlart', 'postfinance').(!is_object($berechnung) ? '' : $preis).'</option>'.CR;
      }

      return $html;
   }
   /* ********************** ENDE Funktionen für Zahlungsarten ****************** */

   public static function getHaendlerNrByUserId($user_id) {
      $haendler_nr = self::$db->querySingleValue("SELECT haendler_nr FROM #__haendler WHERE user_id = $user_id");
      return $haendler_nr;
   }

   // Min-Preis Gutscheine prüfen: true -> OK; false -> Minpreis nicht erreicht
   public static function checkGutschein($code, $preis) {
      $min_preis = null;

      // Kein Gutscheincode eingegeben
      if ($code == '') {
         // Mindestbestellwert (alle Gutscheine)
         $min_preis = (float)self::$db->querySingleValue("SELECT MIN(min) FROM #__gutscheine WHERE code != '' AND (datum = '0000-00-00' OR datum > NOW())");

         // Falls nicht gefunden, in Print-Gutscheine suchen
         if ($min_preis === null && defined('CONF_MODULE_GUTSCHEINPRINT')) {
            $min_preis = 0;
         }

         // kein Mindestpreis
         if ($min_preis === 0 || $min_preis === null) {
            return true;
         }
      }

      // Gutscheincode eingegeben
      else {
         $min_preis = (float)self::$db->querySingleValue("SELECT min FROM #__gutscheine WHERE code = '$code' AND (datum = '0000-00-00' OR datum > NOW())");

         if ($min_preis == null) {
            $min_preis = (float)self::$db->querySingleValue("SELECT min FROM #__gutscheine_print WHERE code = '$code'");
         }
      }

      if ($min_preis > $preis) {
         return false;
      }

      return true;
   }

   public static function getArticleboxImages($picture, $artiekle_id) {
      if (strpos($picture, 'http://') !== false || strpos($picture, 'https://') !== false) {
         $parent_id = self::$db_extern->querySingleValue("SELECT parent_id FROM #__articles WHERE id = $artikel_id");
         $picture_new = self::$downloadImage($picture, $parent_id);
//         if ($picture)
      }
   }

   public static function downloadImage($link, $parent_id, $pic = '01', $ending = '') {
      $url = str_replace(' ', '%20', $link);
      Helper::checkShopsoftware($url);

      if (Helper::$is_shopsoftware == 'y') {
         $url = str_replace('/pictures/', '/pictures/original/', $url);

         if ($ending != '') {
            $url = str_replace('.jpg', '.png', $url);
         }
      }

      $dir = SHOP_PATH.'/'.CONF_PICT_PATH;
      $filename = $parent_id.'_'.$pic.'.jpg';
      $ch = curl_init();

      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS  , 2000);
      $img =curl_exec($ch);
      $status = curl_errno($ch);
      curl_close ($ch);

      if ($status == 0 && strlen($img) > 100 && stristr($img, '<!DOCTYPE') === false) {
         if (file_exists($dir.'original/'.$filename)){
            unlink($dir.'original/'.$filename);
         }

         $fp = fopen($dir.'original/'.$filename,'w');
         fwrite($fp, $img);
         fclose($fp);

         $fp = fopen($dir.$filename,'w');
         fwrite($fp, $img);
         fclose($fp);


         // In jpeg wandeln, falls anderes Format
         if (strpos($link, '.jpg') === false) {
            // list($width, $height) = getimagesize($dir.'original/'.$filename);
         }

         if (Helper::makeThumbnails($dir, str_replace('.jpg', '', $filename), 'jpg', $parent_id, $pic)) {
            return $filename;
         }
      }

      if (self::$is_shopsoftware == 'y' && $ending == '') {
         return self::downloadImage($link, $parent_id, $pic, 'png');
      }

      return '';
   }

   public static function checkShopsoftware($url) {
      if (Helper::$is_shopsoftware != '') {
         return;
      }

      $url = str_replace('/pictures/', '/pictures/original/', $url);
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_HEADER, 0);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS  , 2000);
      $img =curl_exec($ch);
      $status = curl_errno($ch);
      curl_close ($ch);

      if ($status == 0 && strlen($img) > 100 && stristr($img, '<!DOCTYPE') === false) {
         Helper::$is_shopsoftware = 'y';
      }

      else {
         $url = str_replace('.jpg', '.png', $url);
         $ch = curl_init();
         curl_setopt($ch, CURLOPT_URL, $url);
         curl_setopt($ch, CURLOPT_HEADER, 0);
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
         curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
         curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS  , 2000);
         $img =curl_exec($ch);
         $status = curl_errno($ch);
         curl_close ($ch);

         if ($status == 0 && strlen($img) > 100 && stristr($img, '<!DOCTYPE') === false) {
            Helper::$is_shopsoftware = 'y';
         }

         else {
            Helper::$is_shopsoftware = 'n';
         }
      }
   }

   static function getArticlesAllMerkmal($artikel_id, $artikel_name = '') {
      $lang = self::$params->selected_lang;
      $ret_arr = [];
      $art_arr = self::$db_extern->queryAllObjects("SELECT i.name_$lang AS name, a.id,
                                                    a.wert1 AS w1_val, wert2 AS w2_val,
                                                    m.merkmal_$lang as merkmal1,
                                                    w.wert_$lang as wert1,
                                                    mm.merkmal_$lang as merkmal2,
                                                    ww.wert_$lang as wert2,
                                                    c.name_$lang AS cat_name
                                                FROM #__articles AS a
                                             LEFT JOIN #__articles_info as i
                                                ON a.parent_id = i.id
                                             LEFT JOIN #__article_to_cats AS ac
                                                ON i.id = ac.parent_id
                                             LEFT JOIN #__categories as c
                                                ON ac.cat_id = c.id
                                             LEFT JOIN #__merkmale as m
                                                ON a.merkmal1 = m.id
                                             LEFT JOIN #__werte as w
                                                ON a.wert1 = w.id
                                             LEFT JOIN #__merkmale as mm
                                                ON a.merkmal2 = mm.id
                                             LEFT JOIN #__werte as ww
                                                ON a.wert2 = ww.id
                                             WHERE a.parent_id = (
                                                   SELECT parent_id FROM #__articles
                                                      WHERE id = $artikel_id
                                                   )
                                                AND a.wert1 > 0
                                                AND a.online = 'y'
                                                AND ac.sort = 0
                                             GROUP BY a.wert1
                                             ORDER BY a.merkmal1, a.sort");

      if ($art_arr) {
         for ($i = 0; $i < count($art_arr); $i++) {
            $found = false;
            $test = $art_arr[$i]->w1_val;

            for ($j = 0; $j < count($ret_arr); $j++) {
               if ($ret_arr[$j][2] == $test) {
                  $found = true;
                  break;
               }
            }

            if (!$found) {
               $werte                = self::$params->getWerte($art_arr[$i]->merkmal1, $art_arr[$i]->wert1, '', '');
               $link                 = self::$params->getLink('artikel', $art_arr[$i]->id, $art_arr[$i]->name, $werte, $art_arr[$i]->cat_name);
               $ret_arr[] = [$art_arr[$i]->wert1, $link, $art_arr[$i]->id];
            }
         }

         return $ret_arr;
      }

      return false;
   }

   static function addClickCategorie($kat_id) {
      self::$db_extern->query("UPDATE #__categories SET clicks = clicks + 1 WHERE id = $kat_id");
   }

   static function addClickArticle($art_id) {
      self::$db_extern->query("UPDATE #__articles_info AS i, #__articles AS a SET i.clicks = i.clicks + 1 WHERE i.id = a.parent_id AND a.id = $art_id");
   }

   // Click für jede Seite speichern
   static function checkClick($typ, $subtyp = 0, $haendler_id = 0) {
      // Nur bei vorhandeme Modul Statistik
      if (!defined('CONF_MODULE_STATISTIK') || self::getData('use_statistic', 'n') == 'n') {
         return;
      }

      $robot      = 'n';

      if ($typ == 'robots.txt' ||
          $typ == '.inherit' ||
          $typ == '..inherit' ||
          $typ == '.well-known' ||
          $typ == '_phpMyAdmin' ||
          stristr($typ, '.php') !== false)
      {
         $robot = 'y';
      }

      $subtyp       = 0;
      $session_id   = session_id();
      $typ_id       = 0;
      $haendler_id  = 0;
      $statistik_id = 0;

      // Robots erkennen, falls Eintrag vorhanden
      $rbt = self::$db->querySingleObject("SELECT id, robot FROM #__statistik WHERE session_id = '$session_id'");

      if ($rbt != null) {
         $robot = $rbt->robot;
         $statistik_id = $rbt->id;
      }

      if ($statistik_id < 1 && $robot != 'y') {
         // Robots erkennen anhnd User-Agent
         if (self::checkRobots()) {
            $robot = 'y';
         }
      }

      if ($robot == 'y') {
         return;
      }

      if ($statistik_id < 1) {
         self::$db->query("INSERT INTO #__statistik SET
                              typ = '$typ',
                              typ_id = '$typ_id',
                              session_id = '$session_id',
                              haendler_id = $haendler_id,
                              anzahl = 1,
                              robot = '$robot'");
      }

      else {
         self::$db->query("UPDATE #__statistik SET
                              anzahl = anzahl + 1
                           WHERE id = $statistik_id");
      }
   }

   static function checkRobots() {
      $robots = [
                  'archiver',
                  'exabot',
                  'fast',
                  'firfly',
                  'googlebot',
                  'msnboot',
                  'bot',
                  'crawl',
                  'curl',
                  'dataprovider',
                  'search',
                  'get',
                  'spider',
                  'find',
                  'java',
                  'majesticseo',
                  'google',
                  'yahoo',
                  'teoma',
                  'contaxe',
                  'yandex',
                  'libwww-perl',
                  'facebookexternalhit',
                  'msnbot',
                  'rambler',
                  'abachobot',
                  'accoona',
                  'acoirobot',
                  'aspseek',
                  'croccrawler',
                  'dumbot',
                  'fast-webcrawler',
                  'geonabot',
                  'gigabot',
                  'lycos',
                  'msrbot',
                  'scooter',
                  'altavista',
                  'idbot',
                  'estyle',
                  'scrubby'
                ];
      $agent = strtolower($_SERVER['HTTP_USER_AGENT']);

      for ($i = 0; $i < count($robots); $i++) {
         if (stristr($agent, $robots[$i])) {
            return true;
         }
      }

      return false;
   }

   static function getData($name, $default = '') {
      $val = self::$db->querySingleValue("SELECT `data` FROM #__data WHERE `type` = '$name'");

      if (!$val) {
         return $default;
      }

      return $val;
   }

   static function setData($name, $val) {
      $test = self::$db->querySingleValue("SELECT `data` FROM #__data WHERE `type` = '$name'");

      if ($test === null) {
         self::$db->query("INSERT INTO #__data  SET `type` = '$name', `data` = '$val'");
      }

      else if ($test != $val) {
         self::$db->query("UPDATE #__data  SET `data` = '$val' WHERE `type` = '$name'");
      }
   }

   static function getSeiten($art, $lang = 'deu') {
      $text = self::$db->querySingleValue("SELECT text FROM #__seiten WHERE art = '$art' AND lang = '$lang'");

      if ($text) {
         return $text;
      }

      return '';
   }

   // Durch Popup_cookies ersetzt
   static function DELgetSocialDs() {
      $html  = '<div class="titel bg_flaechen fliesstext text_gross">Social Media - like & share</div>';
      $html .= '<div class="inhalt bg_flaechen fliesstext text_normal">'.self::getSeiten('datenschutz', self::$params->selected_lang).'</div>';
      $html .= '<div class="buttons">';
      $html .= '   <div class="bg_flaechen pointer bg_button_only_hover fliesstext text_gross" onClick="$.fancybox.close()">'.self::$text->get('button', 'abbruch').'</div>';
      $html .= '   <div class="bg_button col_button text_gross" onClick="Royalart.showSocialsOk();">'.self::$text->get('button', 'ok').'</div>';
      $html .= '</div>';

      return $html;
   }

   static public function name2ascii($name) {
      $name = mb_strtolower($name);
      $name = str_replace(
                 ['"', "'", 'ä',  'ö',  'ü',  'ß'],
                 ['',  '',  'ae', 'oe', 'ue', 'ss'],
                 $name);

      // Linux
      if (strpos(iconv_get_encoding ('input_encoding'), 'UTF-8') || strpos(iconv_get_encoding ('input_encoding'), 'de_DE.UTF-8')) {
         $test = str_replace('"', '', iconv("UTF-8", "ASCII//TRANSLIT", $name));

         if (strpos($test, '?') === false && $test != '') {
            return $test;
         }
      }

      if (strpos(iconv_get_encoding ('input_encoding'), 'de_DE.UTF-8') === false && @setlocale(LC_CTYPE, 'de_DE.UTF-8') !== false) {
         $test = str_replace('"', '', iconv("UTF-8", "ASCII//TRANSLIT", $name));

         if (strpos($test, '?') === false && $test != '') {
            return $test;
         }
      }

      if (strpos(iconv_get_encoding ('input_encoding'), 'UTF-8') !== false && @setlocale(LC_CTYPE, 'de_DE.UTF-8') !== false) {
         $test = str_replace('"', '', iconv("UTF-8", "ASCII//TRANSLIT", $name));

         if (strpos($test, '?') === false && $test != '') {
            return $test;
         }
      }

      // Solaris
      $test = str_replace('"', '', iconv("ISO-8859-1", "ASCII//TRANSLIT", strtolower(mb_convert_encoding($name, "ISO-8859-1", "UTF-8"))));
      return $test;

   }

   // USt verwenden => true; nicht verwenden => false
   static function checkSteuer($rechnung_land, $wk_land) {
      // Shop und Versandland gleich -> immer Steuer
      if ((int)self::$params->firma['versandart_land'] == $wk_land) {
         return true;
      }

      // Shop EU und Versandland == EU: Bei UStID keine Steuer, bei Privat Steuer
      if (self::$params->firma['region'] == 'eu' && in_array($wk_land, self::$params->eu_list)) {
         if (self::$params->user_id >= 0) {
            $user = $_SESSION['user'];

            // EU-Land und USt-ID vorhanden
            if ($user['ustid'] != '') {
               return false;
            }

            return true;
         }
      }

      // Alle anderen Länder keine Steuer
      return false;
   }

   static public function checkIBAN($iban) {
      $iban      = strtolower(str_replace(' ', '', $iban));
      $countries = ['al'=>28,'ad'=>24,'at'=>20,'az'=>28,'bh'=>22,'be'=>16,'ba'=>20,'br'=>29,'bg'=>22,'cr'=>21,'hr'=>21,'cy'=>28,'cz'=>24,'dk'=>18,'do'=>28,'ee'=>20,'fo'=>18,'fi'=>18,'fr'=>27,'ge'=>22,'de'=>22,'gi'=>23,'gr'=>27,'gl'=>18,'gt'=>28,'hu'=>28,'is'=>26,'ie'=>22,'il'=>23,'it'=>27,'jo'=>30,'kz'=>20,'kw'=>30,'lv'=>21,'lb'=>28,'li'=>21,'lt'=>20,'lu'=>20,'mk'=>19,'mt'=>31,'mr'=>27,'mu'=>30,'mc'=>27,'md'=>24,'me'=>22,'nl'=>18,'no'=>15,'pk'=>24,'ps'=>29,'pl'=>28,'pt'=>25,'qa'=>29,'ro'=>24,'sm'=>27,'sa'=>24,'rs'=>22,'sk'=>24,'si'=>19,'es'=>24,'se'=>24,'ch'=>21,'tn'=>24,'tr'=>26,'ae'=>23,'gb'=>22,'vg'=>24];
      $chars     = ['a'=>10,'b'=>11,'c'=>12,'d'=>13,'e'=>14,'f'=>15,'g'=>16,'h'=>17,'i'=>18,'j'=>19,'k'=>20,'l'=>21,'m'=>22,'n'=>23,'o'=>24,'p'=>25,'q'=>26,'r'=>27,'s'=>28,'t'=>29,'u'=>30,'v'=>31,'w'=>32,'x'=>33,'y'=>34,'z'=>35];


      if(strlen($iban) == $countries[substr($iban,0,2)]){
         // vordere 4 Stellen nach hinten
         $moved_chars    = substr($iban, 4).substr($iban,0,4);
         $moved_char_arr = str_split($moved_chars);
         $new            = "";

         foreach($moved_char_arr AS $key => $value){
            if(!is_numeric($moved_char_arr[$key])){
               $moved_char_arr[$key] = $chars[$moved_char_arr[$key]];
            }

            $new .= $moved_char_arr[$key];
         }

    $x = $new;
    $y = 97;
    $take = 4;
    $mod = '';

    do
    {
        $a = (int)$mod.substr( $x, 0, $take );
        $x = substr( $x, $take );
        $mod = $a % $y;
    }
    while ( strlen($x) );

// var_dump($mod); exit;
//         $new = (int)$new;
//         if (bcmod($new, '97') == 1) {
         if ($mod == 1) {
            return true;
         }

         else {
            return false;
         }
      }

      else{
         return false;
      }
   }

   // Ungülte Ziffer aus Telefon-Nr entfernen (Nur Zahlen erlaubt und + am Anfang)
   static public function cleanPhone($phone) {
      if ($phone == '') {
         return '012341234567';
      }

      $plus  = '';
      $phone = str_replace('(0)', '', $phone);

      if (substr($phone, 0, 1) == '+') {
         $plus = '+';
      }

      else if(substr($phone, 0, 1) != '0') {
         $plus = 0;
      }

      $phone = preg_replace('/[^0-9]+/', '', $phone);

      return $plus.$phone;
   }

   // Tabelle für Versandkosten herausfinden (Inland, EU, Nicht-EU)
   static function versandMode() {
      static $tab;

      if (!$tab) {
         $tab = 1;

         if (self::$params->firma['versandart_land'] != $_SESSION['wk_land']) {
            if (self::$params->firma['region'] != 'eu') {
               $tab = 2;
            }

            else {
               $region = self::$db->querySingleValue("SELECT region FROM #__laender WHERE id = ".$_SESSION['wk_land']);

               if ($region == 'eu') {
                  $tab = 2;
               }
               else {
                  $tab = 3;
               }
            }
         }
      }

      return $tab;
   }

   // Bestellung in /export/buchungen.csv speichern
   static function afterbuy($re_id, $best_id = 0) {
      $im_export = \KANPAICLASSIC\Control::getImportExport();

      if (defined('CONF_AUTO_BESTELLUNG')) {
         $im_export->exportBestellung('csv', 'default', 'auto');
      }

      return true;
   }

   static function mbytesToBytes($mbytes){
      $bytes1 = substr($mbytes, 0, -1);  // 10M
      $bytes2 = substr($mbytes, 0, -2);  // 10MB

      switch(strtoupper(substr($mbytes, -1))){
         case "K":
            return $bytes1 * 1024;
         case "M":
            return $bytes1 * pow(1024, 2);
         case "G":
            return $bytes1*pow(1024, 3);
         case "T":
            return $bytes1*pow(1024, 4);
         case "P":
            return $bytes1*pow(1024, 5);
      }

      switch(strtoupper(substr($mbytes, -2))){
         case "KB":
            return $bytes2 * 1024;
         case "MB":
            return $bytes2 * pow(1024, 2);
         case "GB":
            return $bytes2*pow(1024, 3);
         case "TB":
            return $bytes2*pow(1024, 4);
         case "PB":
            return $bytes2*pow(1024, 5);
      }

      return $mbytes;
   }

   public static function hex2rgba($color, $opacity) {
      if ($opacity < 0) {
         return $color;
      }

      $col = str_replace('#', '', $color);
      $r   = substr($col, 0, 2);
      $g   = substr($col, 2, 2);
      $b   = substr($col, 4, 2);

      return 'rgba('.hexdec($r).', '.hexdec($g).', '.hexdec($b).', '.(is_numeric($opacity) && $opacity < 1 ? $opacity : 1).')';
   }

   // Tracking-Code Danke-Seite generieren
   public static function trackingCode() {
      if (!is_file(TEMPLATE_PATH.'/save/save/trackingcode.txt')) {
         return '';
      }

      $tracking = file_get_contents(TEMPLATE_PATH.'/save/save/trackingcode.txt');

      if ($tracking == '') {
         return 'false';
      }

      $email       = $_SESSION['TRUSTEDSHOPS']['email_kunde'];
      $bestell_nr  = $_SESSION['TRUSTEDSHOPS']['order_id'];
      $netto       = round($_SESSION['TRUSTEDSHOPS']['netto'], 2);
      $brutto      = $_SESSION['TRUSTEDSHOPS']['brutto'];
      $waehrung    = $_SESSION['TRUSTEDSHOPS']['waehrung'];
      $zahlart     = $_SESSION['TRUSTEDSHOPS']['zahlart'];
      $lieferdatum = $_SESSION['TRUSTEDSHOPS']['lieferdatum'];

      return str_ireplace(['[EMAIL]', '[BESTELLNUMMER]', '[SUMMENETTO]', '[SUMMEBRUTTO]', '[WAEHRUNG]', '[ZAHLART]', '[LIEFERDATUM]'],
                            [$email, $bestell_nr, $netto, $brutto, $waehrung, $zahlart, $lieferdatum],
                            $tracking);
   }

   public static function checkTextToggle($text, $pos_footer = false) {
      $text = str_ireplace('[AUSKLAPPEN]', '<div class="text_toggle_start">', $text);
      $text = str_ireplace(
         '[\AUSKLAPPEN]',
         '<div class="text_toggle_line pointer fliesstext '.($pos_footer ? 'menu_unten_text' : 'text_normal').'"
               data-text_height="50"
               data-text_more="'.self::$text->get('text', 'more').'"
               data-text_less="'.self::$text->get('text', 'less').'"
         >'.self::$text->get('text', 'more').'</div></div>',
         $text
      );

      return $text;
   }

   public static function moduleColor($color) {
      $bg = [];

      if ($color == '' || $color == 'bg_innen') {
         require_once ADMIN_PATH.'/classes/designColors.class.php';
         $design_colors = new KANPAICLASSIC_designColors();
         $design_colors->loadCss();
         $css = $design_colors->css['bg_innen'];

         $bg['color']   = '#'.$css['val'];
         $bg['opacity'] = $css['opacity'];
         $bg['check']   = 'y';
         $bg['css']     = 'rgba('.hexdec(substr($css['val'], 0, 2)).', '.hexdec(substr($css['val'], 2, 2)).', '.hexdec(substr($css['val'], 4, 2)).', '.$css['opacity'].')';
      }

      else {
         $bg_parts = explode(',', $color);
         $r = dechex((int)$bg_parts[0]);
         $g = dechex((int)$bg_parts[1]);
         $b = dechex((int)$bg_parts[2]);
         $o = (isset($bg_parts[3]) && $bg_parts[3] !== '' ? $bg_parts[3] : 1.00);

         $r1 = (strlen($r) > 1 ? $r : '0'.$r);
         $g1 = (strlen($g) > 1 ? $g : '0'.$g);
         $b1 = (strlen($b) > 1 ? $b : '0'.$b);


         $bg['color']   = '#'.$r1.$g1.$b1;
         $bg['opacity'] = $o;
         $bg['check']   = 'n';
         $bg['css']     = 'rgba('.$bg_parts[0].', '.$bg_parts[1].', '.$bg_parts[2].', '.$o.')';
      }

      return (object)$bg;
   }

   public static function checkFilename($name) {
      // Wenn nicht UTF-8 ersetzen
      if (self::getData('seo_utf8', 'n') == 'n') {
         // PHP-Bug? Hänkel -> Hoenkel. Bei Aufteilung in 3 str_replace OK.
         $name = str_replace('Ä', 'Ae', $name);
         $name = str_replace('Ö', 'Oe', $name);
         $name = str_replace('Ü', 'Ue', $name);
         $name = str_replace('ä', 'ae', $name);
         $name = str_replace('ö', 'oe', $name);
         $name = str_replace('ü', 'ue', $name);
         $name = str_replace([' ', 'ß', '.jpg', '.jpeg', '.png'], ['-', 'ss', '', '', ''], $name);

         // Alle ungültigen Zeichen entfernen
         $name = preg_replace('#[^a-zA-Z0-9_\-\s]#', '', $name);
      }

      else {
         $name = str_replace([' ', '%', '*'], ['-', ''], $name);
         str_ireplace(['%2F', '%25', '+', '_', '/'], ['/', '-', '-', '-', '-', '-'], urlencode($name));
      }

      return $name;
   }

   // Identisch mit params_base / str2float
   public static function checkFloat($val) {
      $val  = (string)$val;

      $last = max(strrpos($val, ','), strrpos($val, '.'));

      if ($last !== false) {
         $val = strtr($val, ',.', '||');
         $val[$last] = '.';
         $val = str_replace('|', '', $val);
      }

      return (float)$val;
   }

   public static function cookieSettings($show_lang = '') {
      $lang = ($show_lang != '' ? $show_lang : self::$params->selected_lang);

      $cookies_settings = (object)[
         'wesentlich_chek'    => 'y',
         'social_chek'        => (isset($_SESSION['social_chek']) && $_SESSION['social_chek'] == 'y' ? 'y' : 'n'),
         'social_active'      => (self::$params->firma['social_status'] == 'nein' ? false : true),
         'marketing_check'    =>  (isset($_SESSION['social_chek']) && $_SESSION['social_chek'] == 'y' ? 'y' : 'n'),
         'funktionell_check'  =>  (isset($_SESSION['social_chek']) && $_SESSION['social_chek'] == 'y' ? 'y' : 'n'),

         // aus DB sprachabhängig
         'wesentlich_title'   => '',
         'wesentlich_text'    => '',
         'wesentlich_script'  => '',
         'social_title'       => '',
         'social_text'        => '',
         'social_script'      => '',
         'marketing_title'    => '',
         'marketing_text'     => '',
         'marketing_script'   => '',
         'funktionell_title'  => '',
         'funktionell_text'   => '',
         'funktionell_script' => '',
      ];

      $cookies_db = self::$db->queryAllObjects("SELECT bezeichnung, value FROM #__cookies WHERE lang = '$lang'");

      if ($cookies_db) {
         foreach ($cookies_db as $c) {
            $cookies_settings->{$c->bezeichnung} = $c->value;
         }
      }

      return $cookies_settings;
   }

   public static function shopLog($modul, $info1 = '', $info2 = '', $info3 = '', $info4 = '') {
      if (file_exists(SHOP_PATH.'/classes/modules/xdebug/shop_log')) {
         $time = date('d.m.Y H:i');

         file_put_contents(SHOP_PATH.'/classes/modules/xdebug/log/shop_log.txt', $time.': '.$modul.' : '.$info1.' : '.$info2.' : '.$info3.' : '.$info4."\n", FILE_APPEND);
      }
   }
}
