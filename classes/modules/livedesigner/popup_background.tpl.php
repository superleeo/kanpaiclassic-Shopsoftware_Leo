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
$no_img               = ADMIN_URL.'/img/nopic.png';
$image_bg            = (file_exists(TEMPLATE_PATH.'/images/bg_tn.jpg') ? TEMPLATE_URL.'/images/bg_tn.jpg?'.time() : $no_img);

$html .= '<div id="popup_background">'.CR;
$html .= '   <div class="title_zeile txt_tit">Hintergrund</div>'.CR;

$html .= '   <div class="zeile">'.CR;
$html .= '      <div class="pos1 txt_bez">Farbe <span class="fliesstext">(statt Bild)</span></div>'.CR;
$html .= '      <div class="pos2">'.CR;
$html .= '         <span class="css_flaechen txt_bez">08<span>'.CR;
$html .= '      </div>'.CR;
$html .= '      <div class="pos3">'.CR;
$html .= '         <input type="hidden" class="opacity" value="'.$css['bg_aussen']['opacity'].'" id="bg_aussen_opacity" name="bg_aussen_opacity" />'.CR;
$html .= '         <input type="text" class="txt_inp minicolors" data-opacity="'.$css['bg_aussen']['opacity'].'" value="'.$css['bg_aussen']['val'].'" id="bg_aussen" name="bg_aussen" />'.CR;
$html .= '      </div>'.CR;
$html .= '      <div class="clear"></div>'.CR;
$html .= '   </div>'.CR;

$html .= '   <div class="zeile">'.CR;
$html .= '      <div class="pos1 txt_bez">Bild'.CR;
$html .= '         <span class="fliesstext">(jpg)</span>'.CR;
$html .= '         <br />'.CR;
$html .= '         <span class="txt_bez">Fläche</span>'.CR;
$html .= '      </div>'.CR;
$html .= '      <div class="pos2 icon_block">'.CR;
$html .= '         <span class="upload upload_button pointer" onclick="Design.uploadImg(\'bg\', 0, \'bg_img\');" title="hochladen"></span>'.CR;
$html .= '         <span class="delete pointer far fa-trash-alt" onclick="Design.deleteImg(\'bg\', 0, \'bg_img\');" title="löschen"></span>'.CR;
$html .= '         <br />'.CR;
$html .= '         <input type="checkbox" class="newdesign" id="ld_flaeche_mitte" name="ld_flaeche_mitte" '.($this->json['flaeche_hg'] === 'y' ? 'checked="checked"' : '').' onchange="($(this).is(\':checked\') ? $(\'#hintergrund_img2\').prop(\'src\', \''.ADMIN_URL.'/img/flaeche_hor.png\') : $(\'#hintergrund_img2\').prop(\'src\', \''.ADMIN_URL.'/img/flaeche_vert.png\'));">'.CR;
$html .= '         <label for="ld_flaeche_mitte" style="text-align: right;right: -3px;"></label>'.CR;
$html .= '      </div>'.CR;

$html .= '      <div class="pos3">'.CR;
$html .= '         <div class="hintergrund_img">'.CR;
$html .= '            <img id="bg_img" src="'.$image_bg.'" alt="" />'.CR;
$html .= '            <img id="hintergrund_img2" src="'.ADMIN_URL.'/img/flaeche_'.($json['flaeche_hg'] == 'y' ? 'hor' : 'vert').'.png" />'.CR;
$html .= '         </div>'.CR;
$html .= '      </div>'.CR;

$html .= '      <div class="pos4">'.CR;
$html .= '         <input type="radio" class="newdesign" id="bg_fixed1" name="bg_fixed" value="n"'.($json['bg_fixed'] == 'n' ? ' checked="checked"' : '').' />'.CR;
$html .= '         <label for="bg_fixed1"></label> mitscrollend<br />'.CR;
$html .= '         <input type="radio" class="newdesign" id="bg_fixed2" name="bg_fixed" value="y"'.($json['bg_fixed'] == 'y' ? ' checked="checked"' : '').' />'.CR;
$html .= '         <label for="bg_fixed2"></label> fixiert'.CR;
$html .= '      </div>'.CR;
$html .= '   <div>'.CR;
$html .= '   <div class="clear"></div>'.CR;

$html .= '   <div class="zeile">'.CR;
$html .= '      <div class="pos1 txt_bez">Struktur</div>'.CR;
$html .= '      <div class="pos2">Kacheln</div>'.CR;
$html .= '      <span class="pos5">';
$html .= '         &nbsp;<input type="radio" class="newdesign" id="bg_repeat1" name="bg_repeat" value="z"'.($this->json['bg_repeat'] == 'z' ? ' checked="checked"' : '').' />'.CR;
$html .= '         <label for="bg_repeat1"></label>xy&nbsp;&nbsp;&nbsp;'.CR;
$html .= '         <input type="radio" class="newdesign" id="bg_repeat2" name="bg_repeat" value="x"'.($this->json['bg_repeat'] == 'x' ? ' checked="checked"' : '').' />'.CR;
$html .= '         <label for="bg_repeat2"></label>x&nbsp;&nbsp;&nbsp;'.CR;
$html .= '         <input type="radio" class="newdesign" id="bg_repeat3" name="bg_repeat" value="y"'.($this->json['bg_repeat'] == 'y' ? ' checked="checked"' : '').' />'.CR;
$html .= '         <label for="bg_repeat3"></label>y&nbsp;&nbsp;&nbsp;'.CR;
$html .= '         <input type="radio" class="newdesign" id="bg_repeat4" name="bg_repeat" value="n"'.($this->json['bg_repeat'] == 'n' ? ' checked="checked"' : '').' />'.CR;
$html .= '         <label for="bg_repeat4"></label>aus'.CR;
$html .= '     </span>'.CR;
$html .= '     <div class="clear"></div>'.CR;

$html .= '   <div class="buttonzeile">';
$html .= '      <div class="button button_left" onclick="Multibox.close();">abbrechen</div>';
$html .= '      <div class="button_ci button_center" onclick="Livedesigner.saveBackground();">speichern</div>';
$html .= '   </div>';
$html .= '</div>'.CR;
