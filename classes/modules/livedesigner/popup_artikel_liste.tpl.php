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
$html .= '<div id="artikelliste">'.CR;
$html .= '   <div class="txt_tit">'.CR;
$html .= '      <span class="checkbox">'.CR;
$html .= '         <input type="checkbox" class="newdesign" id="artikelliste_on2"'.($this->json['artikelliste_on'] == 'y' ? ' checked="checked"' : '').' />'.CR;
$html .= '         <label for="artikelliste_on2"></label>'.CR;
$html .= '      </span>'.CR;
$html .= '      Artikelliste'.CR;
$html .= '   </div>'.CR;

$html .= '   <div class="box_right">'.CR;
$html .= '      <div class="al_left">'.CR;
$html .= '         <div class="design_line">'.CR;
$html .= '            <input type="radio" class="newdesign" id="startseite_artikel1" name="startseite_artikel" value="reihen" '.($this->json['startseite_artikel'] == 'reihen' ? 'checked="checked" ' : '').' onclick="$(\'#startseite_reihen\').prop(\'readonly\', false);" />'.CR;
$html .= '            <label for="startseite_artikel1"></label>'.CR;
$html .= '            <input type="text" class="inp40 center" id="startseite_reihen" name="startseite_reihen" value="'.$this->json['startseite_reihen'].'"'.($this->json['startseite_artikel'] == 'artikel' ? ' readonly="readonly"' : '').' />'.CR;
$html .= '            &nbsp;Artikelreihen auf Startseite'.CR;
$html .= '         </div>'.CR;

$html .= '         <div class="design_line">'.CR;
$html .= '            <input type="radio" class="newdesign" id="startseite_artikel2" name="startseite_artikel" value="artikel" '.($this->json['startseite_artikel'] == 'artikel' || $this->json['startseite_artikel'] == '' ? 'checked="checked" ' : '').' onclick="$(\'#startseite_reihen\').prop(\'readonly\', true);" />'.CR;
$html .= '            <label for="startseite_artikel2">Artikel fortlaufend auf Startseite</label>'.CR;
$html .= '         </div>'.CR;

$html .= '         <div class="design_line"'.($this->json['kategorien_links'] == 'y' || $this->json['kategorien_links'] == 'l' ? ' style="color:#cccccc;"': '').'>'.CR;
$html .= '         </div>'.CR;
$html .= '      </div>'.CR;

$html .= '      <div class="al_center">'.CR;
$html .= '         <div class="design_line">'.CR;
$html .= '            <input type="checkbox" class="newdesign" id="zoom_artikel" name="zoom_artikel" '.($this->json['zoom_artikel'] == 'y' ? ' checked="checked"': '').' />'.CR;
$html .= '            <label for="zoom_artikel">mit Zoom in Artikelliste</label>'.CR;
$html .= '         </div>'.CR;

$html .= '         <div class="design_line">'.CR;
$html .= '            <input type="checkbox" class="newdesign" id="thumb_over_check" name="thumb_over_check" '.($this->json['thumb_over_check'] == 'y' ? ' checked="checked"' : '').' />'.CR;
$html .= '            <label for="thumb_over_check">Preis und Name bei Mouseover</label>'.CR;
$html .= '         </div>'.CR;

$html .= '         <div class="design_line">'.CR;
$html .= '            <input type="checkbox" class="newdesign" id="merkmal_over_check" name="merkmal_over_check" '.($this->json['merkmal_over_check'] == 'y' ? ' checked="checked"' : '').' />'.CR;
$html .= '            <label for="merkmal_over_check">Artikelvarianten bei Mouseover</label>'.CR;
$html .= '         </div>'.CR;
$html .= '      </div>'.CR;

$html .= '      <div class="al_right">'.CR;
$html .= '         <div class="design_line">'.CR;
$html .= '            <span class="text_filter">Start Animation</span>'.CR;
$html .= '            <span class="selectbox30 cbp_animation"><select id="cbp_display" name="cbp_display">'.$design->_designOptions($this->json['cbp_display']).'</select></span>'.CR;
$html .= '         </div>'.CR;

if (defined('CONF_MODULE_MARKENFILTER')) {
   $html .= '         <div class="design_line">'.CR;
   $html .= '            <span class="text_filter">Filter Animation</span>'.CR;
   $html .= '            <span class="selectbox30 cbp_animation"><select id="cbp_animation" name="cbp_animation">'.$design->_animationOptions($this->json['cbp_animation']).'</select></span>'.CR;
   $html .= '         </div>'.CR;
}

if (defined('CONF_POPUP')) {
   $html .= '         <div class="design_line">'.CR;
   $html .= '            <input type="checkbox" class="newdesign" id="wk_popup_check" name="wk_popup_check" '.($this->json['wk_popup_check'] == 'y' ? ' checked="checked"' : '').' />'.CR;
   $html .= '            <label for="wk_popup_check">"Weiter Einkaufen" Popup</label>'.CR;
   $html .= '         </div>'.CR;
   $html .= '         <?php } else { ?>'.CR;
   $html .= '         <input type="hidden" name="wk_popup_check" value="off" />'.CR;
}

$html .= '      </div>'.CR;
$html .= '      <div class="clear"></div>'.CR;

$html .= '      <div class="box_abstand"></div>'.CR;
$html .= '      <div class="line_design">'.CR;
$html .= '         <div class="format format_1">'.CR;
$html .= '            <div class="format_left format_image">'.CR;
$html .= '               <img src="'.ADMIN_URL.'/img/format_1.jpg" alt="" />'.CR;
$html .= '               <input type="radio" class="newdesign" id="cpf_size1" name="cpf_size" value="klein"'.($this->json['cpf_size'] == 'klein' ? ' checked="checked" ' : '').' onchange="Livedesigner.saveImageChange(this, \'klein\');" />'.CR;
$html .= '               <label for="cpf_size1">klein</label>'.CR;
$html .= '            </div>'.CR;
$html .= '            <div class="format_right format_image">'.CR;
$html .= '               <img src="'.ADMIN_URL.'/img/format_2.jpg" alt="" />'.CR;
$html .= '               <input type="radio" class="newdesign" id="cpf_size2" name="cpf_size" value="normal"'.($this->json['cpf_size'] == 'normal' || $this->json['cpf_size'] == '' ? ' checked="checked" ' : '').' onchange="Livedesigner.saveImageChange(this, \'normal\');" />'.CR;
$html .= '               <label for="cpf_size2">normal</label>'.CR;
$html .= '            </div>'.CR;
$html .= '         </div>'.CR;

$html .= '         <div class="format format_2">'.CR;
$html .= '            <div class="format_left format_image">'.CR;
$html .= '               <img src="'.ADMIN_URL.'/img/format_3.jpg" alt="" />'.CR;
$html .= '               <input type="radio" class="newdesign" id="cpf_size3" name="cpf_size" value="klein_prop"'.($this->json['cpf_size'] == 'klein_prop' ? ' checked="checked" ' : '').' onchange="Livedesigner.saveImageChange(this, \'klein_prop\');" />'.CR;
$html .= '               <label for="cpf_size3">klein</label>'.CR;
$html .= '            </div>'.CR;
$html .= '            <div class="format_right" format_image>'.CR;
$html .= '               <img src="'.ADMIN_URL.'/img/format_4.jpg" alt="" />'.CR;
$html .= '               <input type="radio" class="newdesign" id="cpf_size4" name="cpf_size" value="normal_prop"'.($this->json['cpf_size'] == 'normal_prop' ? ' checked="checked" ' : '').' onchange="Livedesigner.saveImageChange(this, \'normal_prop\');" />'.CR;
$html .= '               <label for="cpf_size4">normal</label>'.CR;
$html .= '            </div>'.CR;
$html .= '         </div>'.CR;

$html .= '         <div class="format format_2">'.CR;
$html .= '            <div class="format_left format_image">'.CR;
$html .= '               <img src="'.ADMIN_URL.'/img/format_5.jpg" alt="" />'.CR;
$html .= '               <input type="radio" class="newdesign" id="cpf_size5" name="cpf_size" value="gross"'.($this->json['cpf_size'] == 'gross' ? ' checked="checked" ' : '').' onchange="Livedesigner.saveImageChange(this, \'gross\');" />'.CR;
$html .= '               <label for="cpf_size5">groß</label>'.CR;
$html .= '            </div>'.CR;
$html .= '            <div class="format_right format_image">'.CR;
$html .= '               <img src="'.ADMIN_URL.'/img/format_6.jpg" alt="" />'.CR;
$html .= '               <input type="radio" class="newdesign" id="cpf_size6" name="cpf_size" value="riesig"'.($this->json['cpf_size'] == 'riesig' ? ' checked="checked" ' : '').' onchange="Livedesigner.saveImageChange(this, \'riesig\');" />'.CR;
$html .= '               <label for="cpf_size6">riesig</label>'.CR;
$html .= '            </div>'.CR;
$html .= '         </div>'.CR;
$html .= '      </div>'.CR;
$html .= '      <div class="clear"></div>'.CR;

if (defined('CONF_MODULE_BILDFORMAT')) {
   $html .= '      <div class="line_design">'.CR;
   $html .= '         <div class="modul_bildformat">'.CR;
   $html .= '            <span title="Breite : Höhe">Neue Artikellisten-Fotos: 1 zu </span>'.CR;
   $html .= '            <input type="text" class="txt_inp inp50" id="image_ratio" name="image_ratio" value="'.number_format((1 / $this->json['image_ratio']), 2, ',', '').'" />'.CR;
/*   $html .= '            <div id="rebuild" class="button txt_but" onClick="Design.rebuildImages();">Erstellen</div>'.CR; */
   $html .= '         </div>'.CR;
   $html .= '      </div>'.CR;
   $html .= '      <div class="clear"></div>'.CR;
}

else {
   $html .= '      <div style="display:none;">'.CR;
   $html .= '         <input type="hidden" name="image_ratio" value="'.(1 / $this->json['image_ratio']).'" />'.CR;
   $html .= '      </div>'.CR;
}

$html .= '   </div>'.CR;
$html .= '   <div class="clear"></div>'.CR;

$html .= '   <div class="buttonzeile">';
$html .= '      <div class="button button_left" onclick="Multibox.close();">abbrechen</div>';
$html .= '      <div class="button_ci button_center" onclick="Livedesigner.saveArtikelListe();">speichern</div>';
$html .= '   </div>';
$html .= '</div>'.CR;
