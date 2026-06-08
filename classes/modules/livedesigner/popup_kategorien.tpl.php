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
$html .= '<div id="popup_kategorien">'.CR;
$html .= '   <div class="title txt_tit">Kategorien</div>'.CR;

$html .= '   <div class="colors">'.CR;
// oberste Zeile
$html .= '      <div class="line">'.CR;
$html .= '         <div class="line_pos1">'.CR;
$html .= '            <input type="radio" class="newdesign" id="kategorien_links1" name="kategorien_links" value="h" '.($this->json['kategorien_links'] == 'h' || $this->json['kategorien_links'] == 'n' ? ' checked="checked"': '').' />'.CR;
$html .= '            <label for="kategorien_links1"></label>horizontal'.CR;
$html .= '         </div>'.CR;
$html .= '         <div class="line_pos2">'.CR;
$html .= '            <input type="checkbox" class="newdesign" id="schatten" name="schatten"'.($this->json['schatten'] == 'y' ? ' checked="checked"' : '').' />'.CR;
$html .= '            <label for="schatten"></label>Schatten'.CR;
$html .= '         </div>'.CR;
$html .= '         <div class="line_pos3">'.CR;

if (defined('CONF_MODULE_EXTENDED')) {
   $html .= '            <input type="checkbox" class="newdesign" id="shop_check" name="shop_check"'.($this->json['shop_check'] == 'y' ? ' checked="checked"': '').' onchange="($(this).prop(\'checked\') ? $(\'.shop_check\').removeClass(\'fe_icon_shop_inactive\').addClass(\'fe_icon_shop\') : $(\'.shop_check\').removeClass(\'fe_icon_shop\').addClass(\'fe_icon_shop_inactive\'));" />'.CR;
   $html .= '            <label for="shop_check"></label>Icon (3 Striche) statt Kategorien'.CR;
}

$html .= '         </div>'.CR;
$html .= '      </div>'.CR;
$html .= '      <div class="clear"></div>'.CR;

// mittlere Zeile

if (defined('CONF_MODULE_DROPDOWNKATEGORIEN')) {
   $html .= '      <div class="line">'.CR;
   $html .= '         <div class="line_pos1">'.CR;
   $html .= '            <input type="radio" class="newdesign" id="kategorien_links2" name="kategorien_links" value="d" '.($this->json['kategorien_links'] == 'd' ? ' checked="checked"': '').' />'.CR;
   $html .= '            <label for="kategorien_links2"></label>Dropdown'.CR;
   $html .= '         </div>'.CR;
   $html .= '         <div class="line_pos2">&nbsp;</div>'.CR;
   $html .= '         <div class="line_pos3"></div>'.CR;
   $html .= '         <div class="clear"></div>'.CR;
   $html .= '      </div>'.CR;
}

// untere Zeile
$html .= '      <div class="line">'.CR;
$html .= '         <div class="line_pos1">'.CR;
$html .= '            <input type="radio" class="newdesign" id="kategorien_links3" name="kategorien_links" value="l" '.($this->json['kategorien_links'] == 'l' || $this->json['kategorien_links'] == 'y' ? ' checked="checked"': '').' />'.CR;
$html .= '            <label for="kategorien_links3"></label>vertikal'.CR;
$html .= '         </div>'.CR;
$html .= '         <div class="line_pos2">'.CR;
$html .= '            <input type="checkbox" class="newdesign" id="linien_kat" name="linien_kat"'.($this->json['linien_kat'] == 'y' ? ' checked="checked"': '').' />'.CR;
$html .= '            <label for="linien_kat"></label>Trennlinie'.CR;
$html .= '         </div>'.CR;
$html .= '         <div class="line_pos2">&nbsp;</div>'.CR;
$html .= '         <div class="line_pos3"></div>'.CR;
$html .= '         <div class="clear"></div>'.CR;
$html .= '      </div>'.CR;
$html .= '   </div>'.CR;

$html .= '   <div class="line">'.CR;
$html .= '      <div class="line_txt1"><b>Button-Flächen</b></div>'.CR;
$html .= '      <div class="line_col1">&nbsp;</div>'.CR;
$html .= '      <div class="line_txt2"><b>Schrift</b></div>'.CR;
$html .= '      <div class="line_col2">&nbsp;</div>'.CR;
$html .= '      <div class="clear"></div>'.CR;
$html .= '   </div>'.CR;

$html .= '   <div class="line">'.CR;
$html .= '      <div class="line_txt1">horiz_kat <span class="txt_bez css_flaechen">03</span></div>'.CR;
$html .= '      <div class="line_col1">'.CR;
$html .= '         <input type="hidden" class="opacity" id="horiz_kat_opacity" name="horiz_kat_opacity" value="'.$css['horiz_kat']['opacity'].'" />'.CR;
$html .= '         <input type="text" class="txt_inp minicolors" id="horiz_kat" name="horiz_kat" value="'.$css['horiz_kat']['val'].'" data-opacity="'.$css['horiz_kat']['opacity'].'" />'.CR;
$html .= '      </div>'.CR;
$html .= '      <div class="line_txt2">horiz_kat <span class="txt_bez css_schrift">18</span></div>'.CR;
$html .= '      <div class="line_col2">'.CR;
$html .= '         <input type="text" class="txt_inp minicolors" id="horiz_kat_c" name="horiz_kat_c" value="'.$css['horiz_kat_c']['val'].'" />'.CR;
$html .= '      </div>'.CR;
$html .= '      <div class="clear"></div>'.CR;
$html .= '   </div>'.CR;

$html .= '   <div class="line">'.CR;
$html .= '      <div class="line_txt1">horiz_aktiv <span class="txt_bez css_flaechen">04</span></div>'.CR;
$html .= '      <div class="line_col1">'.CR;
$html .= '         <input type="hidden" class="opacity" id="horiz_aktiv_opacity" name="horiz_aktiv_opacity" value="'.$css['horiz_aktiv']['opacity'].'" />'.CR;
$html .= '         <input type="text" class="txt_inp minicolors" id="horiz_aktiv" name="horiz_aktiv" value="'.$css['horiz_aktiv']['val'].'" data-opacity="'.$css['horiz_aktiv']['opacity'].'" />'.CR;
$html .= '      </div>'.CR;
$html .= '      <div class="line_txt2">horiz_aktiv <span class="txt_bez css_schrift">19</span></div>'.CR;
$html .= '      <div class="line_col2">'.CR;
$html .= '         <input type="text" class="txt_inp minicolors" id="horiz_kat_c_ovr" name="horiz_kat_c_ovr" value="'.$css['horiz_kat_c_ovr']['val'].'" />'.CR;
$html .= '      </div>'.CR;
$html .= '      <div class="clear"></div>'.CR;
$html .= '   </div>'.CR;

$html .= '   <div class="line">'.CR;
$html .= '      <div class="line_txt1">vertikal_kat <span class="txt_bez css_flaechen">05</span></div>'.CR;
$html .= '      <div class="line_col1">'.CR;
$html .= '         <input type="hidden" class="opacity" id="vertikal_kat_opacity" name="vertikal_kat_opacity" value="'.$css['vertikal_kat']['opacity'].'" />'.CR;
$html .= '         <input type="text" class="txt_inp minicolors" id="vertikal_kat" name="vertikal_kat" value="'.$css['vertikal_kat']['val'].'" data-opacity="'.$css['vertikal_kat']['opacity'].'" />'.CR;
$html .= '      </div>'.CR;
$html .= '      <div class="line_txt2">vertikal_kat <span class="txt_bez css_schrift">20</span></div>'.CR;
$html .= '      <div class="line_col2">'.CR;
$html .= '         <input type="text" class="txt_inp minicolors" id="haupt_kat_c" name="horiz_kat_c" value="'.$css['haupt_kat_c']['val'].'" />'.CR;
$html .= '      </div>'.CR;
$html .= '      <div class="clear"></div>'.CR;
$html .= '   </div>'.CR;

$html .= '   <div class="line">'.CR;
$html .= '      <div class="line_txt1">unter_kat <span class="txt_bez css_flaechen">06</span></div>'.CR;
$html .= '      <div class="line_col1">'.CR;
$html .= '         <input type="hidden" class="opacity" id="unter_kat_opacity" name="unter_kat_opacity" value="'.$css['unter_kat']['opacity'].'" />'.CR;
$html .= '         <input type="text" class="txt_inp minicolors" id="unter_kat" name="unter_kat" value="'.$css['unter_kat']['val'].'" data-opacity="'.$css['unter_kat']['opacity'].'" />'.CR;
$html .= '      </div>'.CR;
$html .= '      <div class="line_txt2"></div>'.CR;
$html .= '      <div class="line_col2">'.CR;
//$html .= '         <input type="text" class="txt_inp minicolors" id="horiz_kat_c" name="horiz_kat_c" value="'.$css['horiz_kat_c']['val'].'" />'.CR;
$html .= '      </div>'.CR;
$html .= '      <div class="clear"></div>'.CR;
$html .= '   </div>'.CR;

$html .= '   <div class="line">'.CR;
$html .= '      <div class="line_txt1">over_kat <span class="txt_bez css_flaechen">07</span></div>'.CR;
$html .= '      <div class="line_col1">'.CR;
$html .= '         <input type="hidden" class="opacity" id="over_kat_opacity" name="over_kat_opacity" value="'.$css['over_kat']['opacity'].'" />'.CR;
$html .= '         <input type="text" class="txt_inp minicolors" id="over_kat" name="over_kat" value="'.$css['over_kat']['val'].'" data-opacity="'.$css['over_kat']['opacity'].'" />'.CR;
$html .= '      </div>'.CR;
$html .= '      <div class="line_txt2">over_kat <span class="txt_bez css_schrift">21</span></div>'.CR;
$html .= '      <div class="line_col2">'.CR;
$html .= '         <input type="text" class="txt_inp minicolors" id="haupt_kat_c_ovr" name="haupt_kat_c_ovr" value="'.$css['haupt_kat_c_ovr']['val'].'" />'.CR;
$html .= '      </div>'.CR;
$html .= '      <div class="clear"></div>'.CR;
$html .= '   </div>'.CR;


$html .= '   <div class="buttonzeile">';
$html .= '      <div class="button button_left" onclick="Multibox.close();">abbrechen</div>';
$html .= '      <div class="button_ci button_center" onclick="Livedesigner.saveKategorien();">speichern</div>';
$html .= '   </div>'.CR;
$html .= '</div>'.CR;
