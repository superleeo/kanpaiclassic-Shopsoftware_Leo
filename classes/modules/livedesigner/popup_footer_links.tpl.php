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

$html .= '<div id="popup_footerlinks">'.CR;
$html .= '   <div class="title txt_tit">Footer Seiten</div>'.CR;
$html .= '   <div class="footer_mode">'.CR;
$html .= '      <div class="footer_radios_img">'.CR;
$html .= '         <div class="footer_radio left">'.CR;
$html .= '            <input type="radio" class="newdesign" id="footer_mode1" name="footer_mode" value="freundlich"'.($this->json['footer_mode'] !== 'komplex' ? ' checked="checked"' : '').' />'.CR;
$html .= '            <label for="footer_mode1">benutzerfreundlich</label>'.CR;
$html .= '         </div>'.CR;
$html .= '         <div class="footer_radio_left_img_ld2 left"></div>'.CR;
$html .= '      </div>'.CR;

$html .= '      <div class="footer_radios_img left">'.CR;
$html .= '         <div class="footer_radio">'.CR;
$html .= '            <input type="radio" class="newdesign" id="footer_mode2" name="footer_mode" value="komplex"'.($this->json['footer_mode'] == 'komplex' ? ' checked="checked"' : '').' />'.CR;
$html .= '            <label for="footer_mode2">komplex</label>'.CR;
$html .= '         </div>'.CR;
$html .= '         <div class="footer_radio_right_img_ld2 left"></div>'.CR;
$html .= '      </div>'.CR;
$html .= '   </div>'.CR;

$html .= '   <div class="footer_colors">'.CR;
$html .= '      <div>Schrift <span class="css_schrift txt_bez">27</span>'.CR;
$html .= '         <input type="text" class="txt_inp minicolors" value="'.$css['menu_unten']['val'].'" id="ld_menu_unten" name="ld_menu_unten" />'.CR;
$html .= '      </div>'.CR;

$html .= '      <div>Schrift_over <span class="css_schrift txt_bez">28</span>'.CR;
$html .= '         <input type="text" class="txt_inp minicolors" value="'.$css['over_unten']['val'].'" id="ld_over_unten" name="ld_over_unten" />'.CR;
$html .= '      </div>'.CR;

$html .= '      <div>Hintergrund <span class="css_flaechen txt_bez">13</span>'.CR;
$html .= '         <input type="hidden" class="opacity" value="'.$css['bg_footer']['opacity'].'" id="ld_bg_footer_opacity" name="ld_bg_footer_opacity" />'.CR;
$html .= '         <input type="text" class="txt_inp minicolors" data-opacity="'.$css['bg_footer']['opacity'].'" value="'.$css['bg_footer']['val'].'" id="ld_bg_footer" name="ld_bg_footer" />'.CR;
$html .= '      </div>'.CR;
$html .= '   </div>'.CR;
$html .= '   <div class="clear"></div>'.CR;
$html .= '   <div class="trennlinie"></div>'.CR;

$html .= '   <div class="box_left">'.CR;

foreach ($seiten2 as $seite => $v) {
   $check = $seiten->_check($seite, $v['check']);
   $name  = $seiten->_checkName($seite, $v['name']);

   $html .= '            <div class="link_line '.$seite.'">'.CR;

   if ($seite !== 'anmelden') {
      $html .= '               <span class="edit pointer fas fa-pencil-alt" onclick="Livedesigner.editSeite(\''.$seite.'\');"></span>'.CR;
   }

   else {
      $html .= '               <span class="edit fas"></span>'.CR;
   }

   if (!defined('CONF_MODULE_WEBSITE') && ($seite == 'impressum' || $seite == 'datenschutz' || $seite == 'kontakt2' || $seite == 'anmelden')) {
      $html .= '               <span class="edit fas"></span>'.CR;
   }

   else {
      $html .= '               <span class="'.($check == 'y' ? 'active ' : '').'pointer fas '.($check !== 'y' ? 'fa-times' : 'fa-check').'" onclick="Seiten.active(this, \''.$seite.'\')"></span>'.CR;
   }

   $html .= '               <span class="site_name">'.$name.'</span>'.CR;
   $html .= '            </div>'.CR;
}

$html .= '   </div>'.CR;

$html .= '   <div class="box_right">'.CR;

foreach ($seiten3 as $seite => $v) {
   $check = $seiten->_check($seite, $v['check']);
   $name  = $seiten->_checkName($seite, $v['name']);

   $html .= '            <div class="link_line '.$seite.'">'.CR;
   $html .= '               <span class="edit pointer fas fa-pencil-alt" onclick="Livedesigner.editSeite(\''.$seite.'\');"></span>'.CR;

   if (!defined('CONF_MODULE_WEBSITE') && ($seite == 'versand' || $seite == 'agb')) {
      $html .= '               <span class="fas"></span>'.CR;
   }

   else {
      $html .= '               <span class="'.($check == 'y' ? 'active ' : '').'pointer fas '.($check !== 'y' ? 'fa-times' : 'fa-check').'" onclick="Seiten.active(this, \''.$seite.'\')"></span>'.CR;
   }

   $html .= '               <span class="site_name">'.$name.'</span>'.CR;
   $html .= '            </div>'.CR;
}

$html .= '            <div class="ellipsis site_item <?php echo $seite; ?>">'.CR;
$html .= '               <span class="edit pointer fas fa-pencil-alt" onclick="Seiten.popupSitemap();"></span>'.CR;
$html .= '               <span class="active pointer fas '.($this->params->firma['sitemap_check'] !== 'y' ? 'fa-times' : 'fa-check').'" onclick="Seiten.active(this, \'sitemap\');"></span>'.CR;
$html .= '               <span class="site_name" title="Je nach Anzahl Artikel / Kategorien kann bei Aktivierung die Antwort etwas dauern">Sitemap</span>'.CR;
$html .= '            </div>'.CR;
$html .= '   </div>'.CR;
$html .= '   <div class="clear"></div>'.CR;

$html .= '   <div class="buttonzeile">'.CR;
$html .= '      <div class="button button_left" onclick="Multibox.close();">abbrechen</div>'.CR;
$html .= '      <div class="button_ci button_center" onclick="Livedesigner.saveFooterlinks();">speichern</div>'.CR;
$html .= '   </div>'.CR;
$html .= '</div>'.CR;
