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
// Hintergrund / Flächen: flaeche_hg
// Logobanner: flaeche
// Artikliste: bildschirmbreit
// Footer: flaeche_footer
$html .= '<div id="popup_breite" class="design">'.CR;
$html .= '   <div class="title txt_tit">Inhaltsbereich</div>'.CR;
$html .= '   <div class="line">'.CR;
$html .= '      <div class="line_left">Shopbreite</div>'.CR;
$html .= '      <div class="line_center"><input type="text" class="txt_inp right" id="ld_max_width" name="ld_max_width" value="'.$json['max_width'].'">&nbsp;px</div>'.CR;
$html .= '      <div class="clear"></div>'.CR;
$html .= '   </div>'.CR;

$html .= '   <div class="sub_title txt_tit">Hintergrundflächen</div>'.CR;

$html .= '   <div class="line">'.CR;
$html .= '      <div class="line_left">Header</div>'.CR;
$html .= '      <div class="line_center">'.CR;
$html .= '         <input type="checkbox" class="newdesign" id="ld_flaeche_header" name="ld_flaeche_header"'.($json['flaeche'] == 'y' ? ' checked="checked"' : '').' />'.CR;
$html .= '         <label for="ld_flaeche_header"></label>bildschirmbreit'.CR;
$html .= '      </div>'.CR;
//$html .= '      <div class="line_right">'.CR;
//$html .= '         <span class="pos1">bg_header</span>'.CR;
//$html .= '         <span class="pos2 txt_bez css_flaechen">02</span>'.CR;
//$html .= '         <span class="pos3">'.CR;
//$html .= '            <input type="hidden" class="opacity" value="'.$css['bg_header']['opacity'].'" id="ld_bg_header_opacity" name="ld_bg_header_opacity" />'.CR;
//$html .= '            <input type="text" class="txt_inp minicolors" data-opacity="'.$css['bg_header']['opacity'].'" value="'.$css['bg_header']['val'].'" id="ld_bg_header" name="bg_header" />'.CR;
//$html .= '         </span>'.CR;
//$html .= '      </div>'.CR;
$html .= '      <div class="clear"></div>'.CR;
$html .= '   </div>'.CR;

// Bei Kategorien vertikal wird "Shopmitte" und "Artikelliste" ausgeblendet
if ($this->params->firma['kategorien_links'] == 'y' || $this->params->firma['kategorien_links'] == 'l') {
   $html .= '   <div style="display:none;">'.CR;
}
$html .= '   <div class="line">'.CR;
$html .= '      <div class="line_left">Shopmitte</div>'.CR;
$html .= '      <div class="line_center">'.CR;
$html .= '         <input type="checkbox" class="newdesign" id="ld_flaeche_mitte" name="ld_flaeche_mitte"'.($json['flaeche_hg'] == 'y' ? ' checked="checked"' : '').' />'.CR;
$html .= '         <label for="ld_flaeche_mitte"></label>bildschirmbreit'.CR;
$html .= '      </div>'.CR;
//$html .= '      <div class="line_right line_right_middle">'.CR;
//$html .= '         <span class="pos1">bg_innen</span>'.CR;
//$html .= '         <span class="pos2 txt_bez css_flaechen">09</span>'.CR;
//$html .= '         <span class="pos3">'.CR;
//$html .= '            <input type="hidden" class="opacity" value="'.$css['bg_innen']['opacity'].'" id="ld_bg_innen_opacity" name="ld_bg_innen_opacity" />'.CR;
//$html .= '            <input type="text" class="txt_inp minicolors" data-opacity="'.$css['bg_innen']['opacity'].'" value="'.$css['bg_innen']['val'].'" id="ld_bg_innen" name="ld_bg_innen" />'.CR;
//$html .= '         </span>'.CR;
//$html .= '      </div>'.CR;
$html .= '      <div class="clear"></div>'.CR;
$html .= '      </div>'.CR;

$html .= '   <div class="line">'.CR;
$html .= '      <div class="line_left">Artikelliste</div>'.CR;
$html .= '      <div class="line_center">'.CR;
$html .= '         <input type="checkbox" class="newdesign" id="ld_flaeche_liste" name="ld_flaeche_liste"'.($json['bildschirmbreit'] == 'y' ? ' checked="checked"' : '').' />'.CR;
$html .= '         <label for="ld_flaeche_liste"></label>bildschirmbreit'.CR;
$html .= '      </div>'.CR;
$html .= '      <div class="line_right"></div>'.CR;
$html .= '      <div class="clear"></div>'.CR;
$html .= '   </div>'.CR;
if ($this->params->firma['kategorien_links'] == 'y' || $this->params->firma['kategorien_links'] == 'l') {
   $html .= '   </div>'.CR;
}

$html .= '   <div class="line">'.CR;
$html .= '      <div class="line_left">Footer</div>'.CR;
$html .= '      <div class="line_center">'.CR;
$html .= '         <input type="checkbox" class="newdesign" id="ld_flaeche_footer" name="ld_flaeche_footer"'.($json['flaeche_footer'] == 'y' ? ' checked="checked"' : '').' />'.CR;
$html .= '         <label for="ld_flaeche_footer"></label>bildschirmbreit'.CR;
$html .= '      </div>'.CR;
//$html .= '      <div class="line_right">'.CR;
//$html .= '         <span class="pos1">bg_footer</span>'.CR;
//$html .= '         <span class="pos2 txt_bez css_flaechen">13</span>'.CR;
//$html .= '         <span class="pos3">'.CR;
//$html .= '            <input type="hidden" class="opacity" value="'.$css['bg_footer']['opacity'].'" id="ld_bg_footer_opacity" name="ld_bg_footer_opacity" />'.CR;
//$html .= '            <input type="text" class="txt_inp minicolors" data-opacity="'.$css['bg_footer']['opacity'].'" value="'.$css['bg_footer']['val'].'" id="ld_bg_footer" name="ld_bg_footer" />'.CR;
//$html .= '         </span>'.CR;
//$html .= '      </div>'.CR;
$html .= '      <div class="clear"></div>'.CR;
$html .= '   </div>'.CR;

$html .= '   <div class="buttonzeile">';
$html .= '      <div class="button button_left" onclick="Multibox.close();">abbrechen</div>';
$html .= '      <div class="button_ci button_center" onclick="Livedesigner.saveBreite();">speichern</div>';
$html .= '   </div>';
$html .= '</div>'.CR;
