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
$visa = true;

$html  = '<div id="zahlart_popup">';
$html .= '   <h1>Zahlungsart-Texte</h1>';
$html .= '   <form id="za_text_form">';

foreach ($this->za_arr as $za) {
   $za_info = '';

   switch ($za) {
      case 0:  $za_info = 'Zahlungsart im Admin abgewählt'; break;
      case 1:  $za_info = 'Überweisung'; break;
      case 2:  $za_info = 'PayPal'; break;
      case 3:  $za_info = 'SEPA-Lastschrift'; break;
      case 4:  $za_info = 'Nachnahme'; break;
      case 5:  $za_info = 'Rechnung'; break;
      case 6:  $za_info = 'Bar bei Abholung'; break;
      case 7:  $za_info = 'SOFORT Überweisung'; break;
      case 8:  $za_info = 'VRPay'; break;
      case 9:  $za_info = 'Einzug Kreditkarten'; break;
      case 10: $za_info = 'PayPal PLUS'; break;
      case 11: $za_info = 'Amazon Payments'; break;
      case 12: $za_info = 'TWINT PostFinance'; break;
      case 13: $za_info = 'EasyCredit'; break;
      case 14: $za_info = 'Klarna'; break;
      case 15: $za_info = 'giropay/paydirekt'; break;
      case 16: $za_info = 'WIR'; break;
      case 17: $za_info = 'Postfinance'; break;
      case 18: $za_info = 'PaypalV2'; break;
      case 19: $za_info = 'Mollie'; break;
      case 99: $za_info = 'EU-Reverse Charge - Klausel'; break;
   }

   $html .= '      <div class="za_box'.($za == 99 ? '99' : '').' txt_tit"'.($za != 0 && $za != 99 ? ' data-check_id="'.$za.'"' : '').'>';
   $html .= '         <div class="za_info txt_bez">'.$za_info.'</div>';
   $html .= '         <input type="hidden" name="za'.$za.'_info" value="'.$za_info.'" />';

   foreach ($this->params->langs as $lang) {
      // Flagge vor Text
      $text2  = '';
      $text   = (isset($za_text->{'za'.$za.'_'.$lang}) ? $za_text->{'za'.$za.'_'.$lang} : '');
      $flagge = '         <img src="'.ADMIN_URL.'/img/flaggen/'.$lang.'.jpg" alt="">';

      if ($za == 1) {
         $text2 = (isset($za_text->{'za_re'.$za.'_'.$lang}) ? $za_text->{'za_re'.$za.'_'.$lang} : '');
      }

      // Normalfall
      if ($za != 99) {
         if ($za != 1) {
            $html  .= '         <div class="za_text"><span class="flagge">'.$flagge.'</span><input type="text" name="za'.$za.'_'.$lang.'" value="'.$text.'" class="txt_inp" /></div>';
         }

         else {
            $html  .= '         <div class="za_text_re"><span class="flagge">'.$flagge.'</span><span class="mail_re">Mail</span><input type="text" name="za'.$za.'_'.$lang.'" value="'.$text.'" class="txt_inp" /></div>';
            $html  .= '         <div class="za_text_re"><span class="flagge">'.$flagge.'</span><span class="mail_re">RE</span><input type="text" name="za_re'.$za.'_'.$lang.'" value="'.$text2.'" class="txt_inp" /></div>';
         }
      }

      // EU-Reverse-Charge 2-Zeilig
      else if (strpos($text, '[TRENNER]') !== false) {
         list($text1, $text2) = explode('[TRENNER]', $text);
         $html  .= '         <div class="za_text"><span class="flagge">'.$flagge.'</span><input type="text" name="za'.$za.'_1_'.$lang.'" value="'.$text1.'" class="txt_inp" /></div>';
         $html  .= '         <div class="za_text"><span class="flagge" style="opacity:0;">'.$flagge.'</span><input type="text" name="za'.$za.'_2_'.$lang.'" value="'.trim($text2).'" class="txt_inp" /></div>';
      }

      // EU-Reverse-Charge 1-Zeilig
      else {
         $html  .= '         <div class="za_text"><span class="flagge">'.$flagge.'</span><input type="text" name="za'.$za.'_1_'.$lang.'" value="'.$text.'" class="txt_inp" /></div>';
         $html  .= '         <div class="za_text"><span class="flagge" style="opacity:0;">'.$flagge.'</span><input type="text" name="za'.$za.'_2_'.$lang.'" value="" class="txt_inp" /></div>';
      }
   }

   $html .= '      </div>';
}

$html .= '      <div class="buttonzeile">';
$html .= '         <div class="button_left button txt_but" onclick="Multibox.close();" >abbrechen</div>';
$html .= '         <div class="button_right button_ci txt_but" onclick="Zahlart.popupSave();" >speichern</div>';
$html .= '         <div class="clear"></div>';
$html .= '      </div>';
$html .= '   </form>';
$html .= '</div>';
