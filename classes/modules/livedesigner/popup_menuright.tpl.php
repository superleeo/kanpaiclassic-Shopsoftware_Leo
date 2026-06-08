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
$html .= '<div id="popup_menu">'.CR;
$html .= '   <form id="design_menu_form">'.CR;
$html .= '      <div class="txt_tit">Menü rechts</div>'.CR;

$html .= '      <br /><div class="pc_block" id="'.$this->params->firma['anmelden_mode'].'">'.CR;
$html .= '         <div>'.CR;
$html .= '            <input type="radio" class="newdesign" id="anmelden_mode1" name="anmelden_mode" value="1"'.((int)$this->params->firma['anmelden_mode'] == 1 ? ' checked="checked"' : '').' />'.CR;
$html .= '            <label for="anmelden_mode1"></label>Anmelden'.CR;
$html .= '         </div>'.CR;
$html .= '         <div>'.CR;
$html .= '            <input type="radio" class="newdesign" id="anmelden_mode2" name="anmelden_mode" value="2"'.((int)$this->params->firma['anmelden_mode'] == 2 ? ' checked="checked"' : '').' />'.CR;
$html .= '            <label for="anmelden_mode2"></label>als Icon'.CR;
$html .= '         </div>'.CR;
$html .= '         <div>'.CR;
$html .= '            <input type="radio" class="newdesign" id="anmelden_mode3" name="anmelden_mode" value="3"'.((int)$this->params->firma['anmelden_mode'] == 3 ? ' checked="checked"' : '').' />'.CR;
$html .= '            <label for="anmelden_mode3"></label>kein Icon'.CR;
$html .= '         </div>'.CR;
$html .= '      </div>'.CR;

$html .= '      <div class="pc_block">'.CR;
$html .= '         <div>'.CR;
$html .= '            <input type="radio" class="newdesign" id="merkliste_mode1" name="merkliste_mode" value="1"'.((int)$this->params->firma['merkliste_mode'] == 1 ? ' checked="checked"' : '').' />'.CR;
$html .= '            <label for="merkliste_mode1"></label>Merkliste'.CR;
$html .= '         </div>'.CR;
$html .= '         <div>'.CR;
$html .= '            <input type="radio" class="newdesign" id="merkliste_mode2" name="merkliste_mode" value="2"'.((int)$this->params->firma['merkliste_mode'] == 2 ? ' checked="checked"' : '').' />'.CR;
$html .= '            <label for="merkliste_mode2"></label>als Icon'.CR;
$html .= '         </div>'.CR;
$html .= '         <div>'.CR;
$html .= '            <input type="radio" class="newdesign" id="merkliste_mode3" name="merkliste_mode" value="3"'.((int)$this->params->firma['merkliste_mode'] == 3 ? ' checked="checked"' : '').' />'.CR;
$html .= '            <label for="merkliste_mode3"></label>kein Icon'.CR;
$html .= '         </div>'.CR;
$html .= '      </div>'.CR;

$html .= '      <div class="pc_block">'.CR;
$html .= '         <div>'.CR;
$html .= '            <input type="radio" class="newdesign" id="warenkorb_mode1" name="warenkorb_mode" value="1"'.((int)$this->params->firma['warenkorb_mode'] == 1 ? ' checked="checked"' : '').' />'.CR;
$html .= '            <label for="warenkorb_mode1"></label>Warenkorb'.CR;
$html .= '         </div>'.CR;
$html .= '         <div>'.CR;
$html .= '            <input type="radio" class="newdesign" id="warenkorb_mode2" name="warenkorb_mode" value="2"'.((int)$this->params->firma['warenkorb_mode'] == 2 ? ' checked="checked"' : '').' />'.CR;
$html .= '            <label for="warenkorb_mode2"></label>als Icon'.CR;
$html .= '         </div>'.CR;
$html .= '         <div>'.CR;
$html .= '            <input type="radio" class="newdesign" id="warenkorb_mode3" name="warenkorb_mode" value="3"'.((int)$this->params->firma['warenkorb_mode'] == 3 ? ' checked="checked"' : '').' />'.CR;
$html .= '           <label for="warenkorb_mode3"></label>kein Icon'.CR;
$html .= '         </div>'.CR;
$html .= '      </div>'.CR;

$html .= '      <div class="pc_block">'.CR;
$html .= '         <div>'.CR;
$html .= '            <input type="radio" class="newdesign" id="suchfeld_mode1" name="suchfeld_mode" value="1"'.((int)$this->params->firma['suchfeld_mode'] == 1 ? ' checked="checked"' : '').' />'.CR;
$html .= '            <label for="suchfeld_mode1"></label>Suchfeld rechts'.CR;
$html .= '         </div>'.CR;
$html .= '         <div>'.CR;
$html .= '            <input type="radio" class="newdesign" id="suchfeld_mode2" class="newdesign" id="" name="suchfeld_mode" value="4"'.((int)$this->params->firma['suchfeld_mode'] == 4 ? ' checked="checked"' : '').' />'.CR;
$html .= '            <label for="suchfeld_mode2"></label>Suchfeld mitte'.CR;
$html .= '         </div>'.CR;
$html .= '         <div>'.CR;
$html .= '            <input type="radio" class="newdesign" id="suchfeld_mode3" name="suchfeld_mode" value="2"'.((int)$this->params->firma['suchfeld_mode'] == 2 ? ' checked="checked"' : '').' />'.CR;
$html .= '            <label for="suchfeld_mode3">als Icon</label>'.CR;
$html .= '         </div>'.CR;
$html .= '         <div>'.CR;
$html .= '            <input type="radio" class="newdesign" id="suchfeld_mode4" class="newdesign" id="" name="suchfeld_mode" value="3"'.((int)$this->params->firma['suchfeld_mode'] == 3 ? ' checked="checked"' : '').' />'.CR;
$html .= '            <label for="suchfeld_mode4">aus</label>'.CR;
$html .= '         </div>'.CR;
$html .= '      </div>'.CR;

$html .= '      <div class="pc_block">'.CR;
$html .= '         <div>'.CR;
$html .= '            <input type="radio" class="newdesign" id="flaggen_mode1" class="newdesign" id="flaggen_mode1" name="flaggen_mode" value="1"'.((int)$this->params->firma['flaggen_mode'] == 1 ? ' checked="checked"' : '').' />'.CR;
$html .= '            <label for="flaggen_mode1">Flaggen</label>'.CR;
$html .= '            <span class="help ci_color" title="Ist nur 1 Sprache aktiv, erscheint generell keine Flagge"></span>'.CR;
$html .= '         </div>'.CR;
$html .= '         <div>'.CR;
$html .= '            <input type="radio" class="newdesign" id="flaggen_mode2" name="flaggen_mode" value="2"'.((int)$this->params->firma['flaggen_mode'] == 2 ? ' checked="checked"' : '').' />'.CR;
$html .= '            <label for="flaggen_mode2">als Globus</label>'.CR;
$html .= '         </div>'.CR;
$html .= '         <div>'.CR;
$html .= '            <input type="radio" class="newdesign" id="flaggen_mode3" name="flaggen_mode" value="3"'.((int)$this->params->firma['flaggen_mode'] == 3 ? ' checked="checked"' : '').' />'.CR;
$html .= '            <label for="flaggen_mode3">kein Icon</label>'.CR;
$html .= '          </div>'.CR;
$html .= '      </div>'.CR;
$html .= '      <div class="clear"></div>'.CR;

$html .= '      <div class="txt_bez">Farben</div><br />'.CR;
$html .= '      <div class="color_box_left pc_block">'.CR;
$html .= '         <div class="">'.CR;
$html .= '            <input type="radio" class="newdesign" id="icon_farbe1" name="icon_farbe" value="antrazit"'.($this->params->firma['icon_farbe'] == 'antrazit' ? ' checked="checked"' : '').' />'.CR;
$html .= '            <label for="icon_farbe1"></label>&nbsp;Icons antrazit'.CR;
$html .= '         </div>'.CR;
$html .= '         <div class="clear"></div>'.CR;

$html .= '         <div class="">'.CR;
$html .= '            <input type="radio" class="newdesign" id="icon_farbe2" name="icon_farbe" value="weiss"'.($this->params->firma['icon_farbe'] == 'weiss' ? ' checked="checked"' : '').' />'.CR;
$html .= '            <label for="icon_farbe2"></label>&nbsp;Icons weiß'.CR;
$html .= '         </div>'.CR;
$html .= '         <div class="clear"></div>'.CR;
$html .= '      </div>'.CR;

$html .= '      <div class="color_box_right">'.CR;
$html .= '         <div class="color_line">'.CR;
$html .= '            <div class="pos1">'.CR;
$html .= '               Schrift'.CR;
$html .= '               <span class="css_schrift txt_bez">16</span>'.CR;
$html .= '               <input type="text" class="txt_inp minicolors" value="'.$css['menu_oben']['val'].'" id="menu_oben" name="menu_oben" />'.CR;
$html .= '            </div>'.CR;
$html .= '            <div class="clear"></div>'.CR;
$html .= '         </div>'.CR;

$html .= '         <div class="color_line">'.CR;
$html .= '            <div class="pos1">'.CR;
$html .= '               Schrift-over'.CR;
$html .= '               <span class="css_schrift txt_bez">17</span>'.CR;
$html .= '               <input type="text" class="txt_inp minicolors" value="'.$css['over_oben']['val'].'" id="over_oben" name="over_oben" />'.CR;
$html .= '            </div>'.CR;
$html .= '            <div class="clear"></div>'.CR;
$html .= '         </div>'.CR;

$html .= '         <div class="color_line">'.CR;
$html .= '            <div class="pos1">'.CR;
$html .= '               Hintergrund'.CR;
$html .= '               <span class="css_flaechen txt_bez">01</span>'.CR;
$html .= '               <input type="hidden" class="opacity" value="'.$css['menuleiste']['opacity'].'" id="menuleiste_opacity" name="menuleiste_opacity" />'.CR;
$html .= '               <input type="text" class="txt_inp minicolors" data-opacity="'.$css['menuleiste']['opacity'].'" value="'.$css['menuleiste']['val'].'" id="menuleiste" name="menuleiste" />'.CR;
$html .= '            </div>'.CR;
$html .= '            <div class="clear"></div>'.CR;
$html .= '         </div>'.CR;
$html .= '         <div class="clear"></div>'.CR;
$html .= '      </div>'.CR;
$html .= '      <div class="clear"></div>'.CR;

$html .= '   </form>'.CR;
$html .= '   <div class="buttonzeile">'.CR;
$html .= '      <div class="button button_left txt_but" onclick="Multibox.close();">abbrechen</div>'.CR;
$html .= '      <div class="button_ci button_right txt_but" onclick="Livedesigner.saveMenuRight();">speichern</div>'.CR;
$html .= '      <div class="clear"></div>'.CR;
$html .= '   </div>'.CR;
$html .= '</div>'.CR;
