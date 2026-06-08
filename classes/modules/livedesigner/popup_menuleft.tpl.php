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
$icon = (file_exists(TEMPLATE_PATH.'/images/logomenu_'.$this->params->selected_lang.'.png') ? TEMPLATE_URL.'/images/logomenu_'.$this->params->selected_lang.'.png' : ADMIN_URL.'/img/nopic.png');


$html .= '<div id="popup_menu">'.CR;
$html .= '   <form id="design_menu_form">'.CR;
$html .= '      <div class="txt_tit menu_pos_left">Menü links'.CR;
$html .= '         <span class="help ci_color" title=\'Clean-Design: Deaktivieren Sie gern alles. Auch das Logo-Icon linkt zur "Home" und "Kontakte" etc. gibt es auch im Footer\'></span>'.CR;
$html .= '      </div><br/>'.CR;

$html .= '      <div class="color_line">'.CR;
$html .= '         <div class="logomenu_icon">'.CR;
$html .= '            <img id="logomenu_icon" src="'.$icon.'?'.time().'">'.CR;
$html .= '         </div>'.CR;
$html .= '         <span class="upload_block">'.CR;
$html .= '            <span class="upload upload_button pointer" onclick="Design.uploadImg(\'logomenu\', 0, \'logomenu_icon\', \'png,jpg\');" title="hochladen"></span>'.CR;
$html .= '            <span class="delete pointer far fa-trash-alt" onclick="Design.deleteImg(\'logomenu\', 0, \'logomenu_icon\');" title="löschen"></span>&nbsp;Icon'.CR;
$html .= '         </span>'.CR;
$html .= '         <span class="cat_block">'.CR;
$html .= '            <input type="checkbox" class="newdesign" id="shop_check" name="shop_check"'.($this->params->firma['shop_check'] == 'y' ? ' checked="checked"' : '').' />'.CR;
$html .= '            <label for="shop_check"></label>'.CR;
$html .= '            <span class="fas fa-bars"></span> (Kat)'.CR;
$html .= '         </span>'.CR;
$html .= '         <div class="seiten1">'.CR;

foreach ($seiten1 as $seite => $v) {
   $check = $seiten->_check($seite, $v['check']);
   $name  = $seiten->_checkName($seite, $v['name']);

   $html .= '            <div class="site_item '.$seite.'">'.CR;
   $html .= '               <span class="edit pointer fas fa-pencil-alt" onclick="Livedesigner.editSeite(\''.$seite.'\');"></span>'.CR;
   $html .= '               <span class="'.($check == 'y' ? 'active ' : '').'pointer fas '.($check !== 'y' ? 'fa-times' : 'fa-check').'" onclick="Seiten.active(this, \''.$seite.'\')"></span>'.CR;
   $html .= '               <span class="site_name">'.$name.'</span>'.CR;
   $html .= '            </div>'.CR;
}

$html .= '            <div class="clear"></div>'.CR;
$html .= '         </div>'.CR;
$html .= '         <div class="clear"></div>'.CR;
$html .= '      </div>'.CR;



$html .= '      <br/><div class="txt_bez colors">Farben</div>'.CR;
$html .= '      <div class="color_box">'.CR;
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
$html .= '   </form>'.CR;

$html .= '   <div class="buttonzeile">'.CR;
$html .= '      <div class="button button_left txt_but" onclick="Multibox.close();">abbrechen</div>'.CR;
$html .= '      <div class="button_ci button_right txt_but" onclick="Livedesigner.saveMenuLeft();">speichern</div>'.CR;
$html .= '      <div class="clear"></div>'.CR;
$html .= '   </div>'.CR;
$html .= '</div>'.CR;
