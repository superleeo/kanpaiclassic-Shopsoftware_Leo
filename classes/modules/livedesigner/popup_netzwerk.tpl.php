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
$social_offset = 80 + CONF_TEMPLATE_ID * 10;
$social_index  = 25 - 8 + CONF_TEMPLATE_ID * 4;

$html .= '<div id="popup_netzwerk" class="easy design">'.CR;
$html .= '   <div id="social_buttons" class="design">'.CR;
$html .= '      <div class="box_left">'.CR;
$html .= '         <div class="txt_tit">Social Media</div>'.CR;
$html .= '         <div class="telefon">'.CR;
$html .= '            <input type="checkbox" class="newdesign" id="ld_telefon_on" name="ld_telefon_on"'.(\KANPAICLASSIC\Helper::getData('call_check', 'n') == 'y' ? ' checked="checked"' : '').' />'.CR;
$html .= '            <label for="ld_telefon_on"></label>'.CR;
$html .= '            <span class="call_me_icn"></span>'.CR;
$html .= '         </div>'.CR;
$html .= '      </div>'.CR;

$html .= '      <div class="box_right">'.CR;
$html .= '         <div class="social_radio_line">'.CR;
$html .= '            <div class="social_radio">'.CR;
$html .= '               <input type="radio" class="newdesign" id="social_status1" name="social_status" onclick="$(\'#social_icons\').hide();" value="nein" '.($this->params->firma['social_status'] == 'nein' ? 'checked="checked"' : '').' />'.CR;
$html .= '               <label for="social_status1">nicht anzeigen</label>'.CR;
$html .= '            </div>'.CR;
$html .= '            <div class="social_radio">'.CR;
$html .= '               <input type="radio" class="newdesign" id="social_status2" name="social_status" onclick="$(\'#social_icons\').show();" value="rechts" '.($this->params->firma['social_status'] == 'rechts' ? 'checked="checked"' : '').' />'.CR;
$html .= '               <label for="social_status2">rechts anzeigen</label>'.CR;
$html .= '            </div>'.CR;
$html .= '            <div class="social_radio">'.CR;
$html .= '               <input type="radio" class="newdesign" id="social_status3" class="newdesign" id="" name="social_status" onclick="$(\'#social_icons\').show();" value="unten" '.($this->params->firma['social_status'] == 'unten' ? 'checked="checked"' : '').' />'.CR;
$html .= '               <label for="social_status3">unten anzeigen</label>'.CR;
$html .= '            </div>'.CR;
$html .= '         </div>'.CR;
$html .= '         <div class="clear"></div>'.CR;

$html .= '         <div id="social_icons"'.($this->params->firma['social_status'] == 'nein' ? ' style="display:none;"' : '').'>'.CR;

for ($i = 0; $i < count($social); $i++) {
   $not_active  = ' not_active';
   $social_icon = TEMPLATE_URL.'/images/'.$social[$i]->image.'.png?'.time();
   $social_path = TEMPLATE_PATH.'/images/'.$social[$i]->image.'.png';

   // Zusätzliche Icons
   if ((int)$social[$i]->id > 100) {
      if ($social[$i]->image == '' || !is_file($social_path)) {
         $social_icon = ADMIN_URL.'/img/social_icons/platzhalter.png?'.time();
      }

//      else {
//         $social_icon = $image_url.'/'.$social[$i]->image.'.png';
//      }
   }

   else {
      $social_icon = ADMIN_URL.'/img/social_icons/'.$social[$i]->image.'.png';
   }

   // Standard-Icons
   if ($social[$i]->detail1 != 'd' && $social[$i]->detail1 == 'y') {
      $not_active = '';
   }

   if ($social[$i]->detail2 != 'd' && $social[$i]->detail2 == 'y') {
      $not_active = '';
   }

   if ($social[$i]->footer == 'y') {
      $not_active = '';
   }

   $html .= '            <div title="'.$social[$i]->name.'" class="social_img" onclick="Design.socialPopup('.$social[$i]->id.');">'.CR;
   $html .= '               <img id="img_'.($social[$i]->id).'" class="'.$not_active.'" src="'.$social_icon.'" alt="" />'.CR;
   $html .= '            </div>'.CR;
}

$html .= '         </div>'.CR;
$html .= '         <div class="clear"></div>'.CR;
$html .= '      </div>'.CR;
$html .= '      <div class="clear"></div>'.CR;
$html .= '   </div>'.CR;
$html .= '   <div><br><span class="txt_bez ellipsis"><a href="http://chat-software.eu" title="Chat-Software" target="_blank" rel="noopener"><img src="../img/social_icons/03.png" alt="Chat-Software" width="25" height="25" /></a></span> <a href="http://chat-software.eu" class="link ci_color" title="Chat-Software" target="_blank">www.chat-software.eu</a>'.CR;
$html .= '   </div>'.CR;

$html .= '   <div class="buttonzeile">'.CR;
$html .= '      <div class="button button_left" onclick="Multibox.close();">abbrechen</div>'.CR;
$html .= '      <div class="button_ci button_center" onclick="Livedesigner.saveNetzwerk();">speichern</div>'.CR;
$html .= '   </div>'.CR;
$html .= '</div>'.CR;
