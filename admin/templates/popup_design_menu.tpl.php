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

$html .= '      <form id="design_menu_form">'.CR;
$html .= '         <div class="txt_tit">Menü-Icons</div>'.CR;
//$html .= '         <div class="txt_bez" style="padding:20px 0 10px;">Menü links</div>'.CR;
//$html .= '         <div style="padding-bottom:5px;">'.CR;
//$html .= '            <input type="checkbox" class="newdesign" id="homebutton_check" name="homebutton_check"'.($this->params->firma['homebutton_check'] == 'y' ? ' checked="checked"' : '').'  />';
//$html .= '            <label for="homebutton_check">Home</label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.CR;
//$html .= '            <input type="checkbox" class="newdesign" id="kontakt_check" name="kontakt_check"'.($this->params->firma['kontakt_check'] == 'y' ? ' checked="checked"' : '').'  />'.CR;
//$html .= '            <label for="kontakt_check">Konakt</label>'.CR;
//$html .= '            <div></div>'.CR;
//$html .= '         </div>'.CR;
//$html .= '         <div style="color:#a0a0a0;">Info: Auch das Logo linkt zur "Home".  "Kontakt" gibt es auch im Footer.</div>'.CR;

//$html .= '         <div class="txt_bez" style="padding:25px 0 4px 0;">Menü rechts</div>'.CR;
$html .= '         <div style="color:#a0a0a0; padding:20px 0;">Info: Ist nur 1 Sprache aktiv, erscheint generell keine Flagge.</div>'.CR;

$html .= '         <div class="pc_block">'.CR;
$html .= '            <div>'.CR;
$html .= '               <input type="radio" class="newdesign" id="anmelden_mode1" name="anmelden_mode" value="1"'.((int)$this->params->firma['anmelden_mode'] == 1 ? ' checked="checked"' : '').' />'.CR;
$html .= '               <label for="anmelden_mode1">Anmelden</label>'.CR;
$html .= '            </div>'.CR;
$html .= '            <div>'.CR;
$html .= '               <input type="radio" class="newdesign" id="anmelden_mode2" name="anmelden_mode" value="2"'.((int)$this->params->firma['anmelden_mode'] == 2 ? ' checked="checked"' : '').' />'.CR;
$html .= '               <label for="anmelden_mode2">als Icon</label>'.CR;
$html .= '            </div>'.CR;
$html .= '            <div>'.CR;
$html .= '               <input type="radio" class="newdesign" id="anmelden_mode3" name="anmelden_mode" value="3"'.((int)$this->params->firma['anmelden_mode'] == 3 ? ' checked="checked"' : '').' />'.CR;
$html .= '               <label for="anmelden_mode3">kein Icon</label>'.CR;
$html .= '            </div>'.CR;
$html .= '         </div>'.CR;

$html .= '         <div class="pc_block">'.CR;
$html .= '            <div>'.CR;
$html .= '               <input type="radio" class="newdesign" id="merkliste_mode1" name="merkliste_mode" value="1"'.((int)$this->params->firma['merkliste_mode'] == 1 ? ' checked="checked"' : '').' />'.CR;
$html .= '               <label for="merkliste_mode1">Merkliste</label>'.CR;
$html .= '            </div>'.CR;
$html .= '            <div>'.CR;
$html .= '               <input type="radio" class="newdesign" id="merkliste_mode2" name="merkliste_mode" value="2"'.((int)$this->params->firma['merkliste_mode'] == 2 ? ' checked="checked"' : '').' />'.CR;
$html .= '               <label for="merkliste_mode2">als Icon</label>'.CR;
$html .= '            </div>'.CR;
$html .= '            <div>'.CR;
$html .= '               <input type="radio" class="newdesign" id="merkliste_mode3" name="merkliste_mode" value="3"'.((int)$this->params->firma['merkliste_mode'] == 3 ? ' checked="checked"' : '').' />'.CR;
$html .= '               <label for="merkliste_mode3">kein Icon</label>'.CR;
$html .= '            </div>'.CR;
$html .= '         </div>'.CR;

$html .= '         <div class="pc_block">'.CR;
$html .= '            <div>'.CR;
$html .= '               <input type="radio" class="newdesign" id="warenkorb_mode1" name="warenkorb_mode" value="1"'.((int)$this->params->firma['warenkorb_mode'] == 1 ? ' checked="checked"' : '').' />'.CR;
$html .= '               <label for="warenkorb_mode1">Warenkorb</label>'.CR;
$html .= '            </div>'.CR;
$html .= '            <div>'.CR;
$html .= '               <input type="radio" class="newdesign" id="warenkorb_mode2" name="warenkorb_mode" value="2"'.((int)$this->params->firma['warenkorb_mode'] == 2 ? ' checked="checked"' : '').' />'.CR;
$html .= '               <label for="warenkorb_mode2">als Icon</label>'.CR;
$html .= '            </div>'.CR;
$html .= '            <div>'.CR;
$html .= '               <input type="radio" class="newdesign" id="warenkorb_mode3" name="warenkorb_mode" value="3"'.((int)$this->params->firma['warenkorb_mode'] == 3 ? ' checked="checked"' : '').' />'.CR;
$html .= '               <label for="warenkorb_mode3">kein Icon</label>'.CR;
$html .= '            </div>'.CR;
$html .= '         </div>'.CR;

$html .= '         <div class="pc_block">'.CR;
$html .= '            <div>'.CR;
$html .= '               <input type="radio" class="newdesign" id="suchfeld_mode1" name="suchfeld_mode" value="1"'.((int)$this->params->firma['suchfeld_mode'] == 1 ? ' checked="checked"' : '').' />'.CR;
$html .= '               <label for="suchfeld_mode1">Suchfeld rechts</label>'.CR;
$html .= '            </div>'.CR;
$html .= '            <div>'.CR;
$html .= '               <input type="radio" class="newdesign" id="suchfeld_mode2" class="newdesign" id="" name="suchfeld_mode" value="4"'.((int)$this->params->firma['suchfeld_mode'] == 4 ? ' checked="checked"' : '').' />'.CR;
$html .= '               <label for="suchfeld_mode2">Suchfeld mitte</label>'.CR;
$html .= '            </div>'.CR;
$html .= '            <div>'.CR;
$html .= '               <input type="radio" class="newdesign" id="suchfeld_mode3" name="suchfeld_mode" value="2"'.((int)$this->params->firma['suchfeld_mode'] == 2 ? ' checked="checked"' : '').' />'.CR;
$html .= '               <label for="suchfeld_mode3">als Icon</label>'.CR;
$html .= '            </div>'.CR;
$html .= '            <div>'.CR;
$html .= '               <input type="radio" class="newdesign" id="suchfeld_mode4" class="newdesign" id="" name="suchfeld_mode" value="3"'.((int)$this->params->firma['suchfeld_mode'] == 3 ? ' checked="checked"' : '').' />'.CR;
$html .= '               <label for="suchfeld_mode4">aus</label>'.CR;
$html .= '            </div>'.CR;
$html .= '         </div>'.CR;

$html .= '         <div class="pc_block">'.CR;
$html .= '            <div>'.CR;
$html .= '               <input type="radio" class="newdesign" id="flaggen_mode1" class="newdesign" id="flaggen_mode1" name="flaggen_mode" value="1"'.((int)$this->params->firma['flaggen_mode'] == 1 ? ' checked="checked"' : '').' />'.CR;
$html .= '               <label for="flaggen_mode1">Flaggen</label>'.CR;
$html .= '               <span class="help ci_color" title="Ist nur 1 Sprache aktiv, erscheint generell keine Flagge"></span>'.CR;
$html .= '            </div>'.CR;
$html .= '            <div>'.CR;
$html .= '               <input type="radio" class="newdesign" id="flaggen_mode2" name="flaggen_mode" value="2"'.((int)$this->params->firma['flaggen_mode'] == 2 ? ' checked="checked"' : '').' />'.CR;
$html .= '               <label for="flaggen_mode2">als Globus</label>'.CR;
$html .= '            </div>'.CR;
$html .= '            <div>'.CR;
$html .= '               <input type="radio" class="newdesign" id="flaggen_mode3" name="flaggen_mode" value="3"'.((int)$this->params->firma['flaggen_mode'] == 3 ? ' checked="checked"' : '').' />'.CR;
$html .= '               <label for="flaggen_mode3">kein Icon</label>'.CR;
$html .= '            </div>'.CR;
$html .= '         </div>'.CR;
$html .= '         <div class="clear"></div>'.CR;

$html .= '         <div style=" margin:10px 0;">'.CR;
$html .= '            <div class="pc_block">'.CR;
$html .= '               <div>'.CR;
$html .= '                  <input type="radio" class="newdesign" id="icon_farbe1" name="icon_farbe" value="antrazit"'.($this->params->firma['icon_farbe'] == 'antrazit' ? ' checked="checked"' : '').' />'.CR;
$html .= '                  <label for="icon_farbe1">Icons antrazit</label>'.CR;
$html .= '               </div>'.CR;
$html .= '            </div>'.CR;

$html .= '            <div class="pc_block">'.CR;
$html .= '               <div>'.CR;
$html .= '                  <input type="radio" class="newdesign" id="icon_farbe2" name="icon_farbe" value="weiss"'.($this->params->firma['icon_farbe'] == 'weiss' ? ' checked="checked"' : '').' />'.CR;
$html .= '                  <label for="icon_farbe2">Icons weiß</label>'.CR;
$html .= '               </div>'.CR;
$html .= '            </div>'.CR;
$html .= '         </div>'.CR;
$html .= '         <div class="clear"></div>'.CR;

$html .= '      </form>'.CR;
$html .= '      <div class="buttonzeile">'.CR;
$html .= '         <div class="button button_left txt_but" onclick="Multibox.close();">abbrechen</div>'.CR;
$html .= '         <div class="button_ci button_right txt_but" onclick="Design.saveMenuPopup();">speichern</div>'.CR;
$html .= '         <div class="clear"></div>'.CR;
$html .= '      </div>'.CR;
