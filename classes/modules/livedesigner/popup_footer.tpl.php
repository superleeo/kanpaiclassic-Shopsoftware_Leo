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
//$html .= '<div id="popup_footer" class="design">'.CR;
$html .= '<div id="popup_footer">'.CR;
$html .= '   <div class="title txt_tit">Footer</div>'.CR;

$html .= '   <div class="box_right_mode">'.CR;
$html .= '      <div class="footer_mode">'.CR;
$html .= '         <div class="footer_radios_img">'.CR;
$html .= '            <div class="footer_radio left">'.CR;
$html .= '               <input type="radio" class="newdesign" id="footer_mode1" name="footer_mode" value="freundlich"'.($this->json['footer_mode'] !== 'komplex' ? ' checked="checked"' : '').' />'.CR;
$html .= '               <label for="footer_mode1">benutzerfreundlich</label>'.CR;
$html .= '            </div>'.CR;
$html .= '            <div class="footer_radio_left_img_ld1 left"></div>'.CR;
$html .= '         </div>'.CR;

$html .= '         <div class="footer_radios_img left">'.CR;
$html .= '            <div class="footer_radio">'.CR;
$html .= '               <input type="radio" class="newdesign" id="footer_mode2" name="footer_mode" value="komplex"'.($this->json['footer_mode'] == 'komplex' ? ' checked="checked"' : '').' />'.CR;
$html .= '               <label for="footer_mode2">komplex</label>'.CR;
$html .= '            </div>'.CR;
$html .= '            <div class="footer_radio_right_img_ld1 left"></div>'.CR;
$html .= '         </div>'.CR;
$html .= '         <div class="clear"></div>'.CR;
$html .= '      </div>'.CR;

$html .= '      <div class="footer_colors">'.CR;
$html .= '         <div>'.CR;
$html .= '            Schrift'.CR;
$html .= '            <span class="css_schrift txt_bez">27</span>'.CR;
$html .= '            <input type="text" class="txt_inp minicolors" value="'.$css['menu_unten']['val'].'" id="ld_menu_unten" name="ld_menu_unten" />'.CR;
$html .= '         </div>'.CR;

$html .= '         <div>'.CR;
$html .= '            Schrift_over'.CR;
$html .= '            <span class="css_schrift txt_bez">28</span>'.CR;
$html .= '            <input type="text" class="txt_inp minicolors" value="'.$css['over_unten']['val'].'" id="ld_over_unten" name="ld_over_unten" />'.CR;
$html .= '         </div>'.CR;

$html .= '         <div>'.CR;
$html .= '            Hintergrund'.CR;
$html .= '            <span class="css_flaechen txt_bez">13</span>'.CR;
$html .= '            <input type="hidden" class="opacity" value="'.$css['bg_footer']['opacity'].'" id="ld_bg_footer_opacity" name="ld_bg_footer_opacity" />'.CR;
$html .= '            <input type="text" class="txt_inp minicolors" data-opacity="'.$css['bg_footer']['opacity'].'" value="'.$css['bg_footer']['val'].'" id="ld_bg_footer" name="ld_bg_footer" />'.CR;
$html .= '         </div>'.CR;
$html .= '         <div class="clear"></div>'.CR;
$html .= '      </div>'.CR;
$html .= '      <div class="clear"></div>'.CR;
$html .= '   </div>'.CR;

$html .= '   <div id="footer_editor">'.CR;
$html .= '      <textarea class="edit_breit" id="footer_text" name="footer_text" rows="10" cols="100">'.$text_array[1].'</textarea>'.CR;
$html .= '   </div>'.CR;

$html .= '   <div class="buttonzeile">'.CR;
$html .= '      <div class="button button_left" onclick="Multibox.close();">abbrechen</div>'.CR;
$html .= '      <div class="button_ci button_center" onclick="Livedesigner.saveFooter();">speichern</div>'.CR;
$html .= '   </div>'.CR;
$html .= '</div>'.CR;
