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
$logo_banner   = ADMIN_URL.'/img/nopic.png';
$logo_banner_w = 900;
$logo_banner_h = 110;
$sel_lang      = $this->params->selected_lang; // 'deu';
$this->params->getLinks($sel_lang);
//var_dump($this->params->links);
$skalierung          = $this->json['max_width'] / 900;

if (is_file(TEMPLATE_PATH.'/images/logo_'.$sel_lang.'.png')) {
   $size          = getimagesize(TEMPLATE_PATH.'/images/logo_'.$sel_lang.'.png');
   $logo_banner   = TEMPLATE_URL.'/images/logo_'.$sel_lang.'.png';
   $logo_banner_w = ($size[0] < 200 ? $size[0] : floor($size[0] / $skalierung));
   $logo_banner_h = ($size[0] < 200 ? $size[1] : floor($size[1] / $skalierung));
}

else if (is_file(TEMPLATE_PATH.'/images/logo_'.$sel_lang.'.jpg')) {
   $size   = getimagesize(TEMPLATE_PATH.'/images/logo_'.$sel_lang.'.jpg');
   $logo_banner   = TEMPLATE_URL.'/images/logo_'.$sel_lang.'.jpg';
   $logo_banner_w = ($size[0] < 200 ? $size[0] : floor($size[0] / $skalierung));
   $logo_banner_h = ($size[0] < 200 ? $size[1] : floor($size[1]/ $skalierung));
}

$html .= '<div id="popup_logobanner" class="design">'.CR;
$html .= '   <div class="txt_tit">Logobanner</div>'.CR;

$html .= '   <div class="line bg_banner_width bg_header'.($this->json['flaeche'] != 'y' ? ' bg_header' : '').'">'.CR;
$html .= '      <div id="banner_box_right" class="bg_banner_width" style="text-align:center;">'.CR;
$html .= '         <div class="logobanner_pic" style="min-height:32px; max-width:700px; display:inline-block;">'.CR;
$html .= '            <img id="logobanner_img" style="text-align:center; max-width:100%; max-height:100%;" src="'.$logo_banner.$this->params->firma['image_cache'].'" />'.CR;
$html .= '         </div>'.CR;
$html .= '         <div class="clear"></div>'.CR;
$html .= '      </div>'.CR;
$html .= '   </div>'.CR;

$html .= '   <div class="menu_box_right">'.CR;
$html .= '      <div class="line_left">'.CR;
$html .= '         <span class="upload upload_button pointer" onclick="Design.uploadImg(\'logobanner\', 0, \'logobanner_img\', \'png,jpg\');" title="hochladen"></span>'.CR;
$html .= '         <span class="delete pointer far fa-trash-alt" onclick="Design.deleteImg(\'logo\', 0, \'logobanner_img\');" title="löschen"></span>'.CR;
$html .= '         <span class="link pointer fas fa-link" onclick="Design.linkPopup(\'logobanner\', true);" title="SEO"></span>'.CR;
$html .= '         <input type="hidden" id="logobanner_seo" name="logobanner_seo" value="'.$this->params->links['logoseo'].'" />'.CR;
$html .= '      </div>'.CR;

$html .= '      <div class="line_right">'.CR;
$html .= '         <span class="pos1">bg_header <span class="txt_bez css_flaechen">02</span></span>'.CR;
$html .= '         <span class="pos2">'.CR;
$html .= '            <input type="hidden" class="opacity" id="bg_header_opacity" name="bg_header_opacity" value="'.$css['bg_header']['opacity'].'" />'.CR;
$html .= '            <input type="text" class="txt_inp minicolors" id="bg_header" name="header" value="'.$css['bg_header']['val'].'" data-opacity="'.$css['bg_header']['opacity'].'" />'.CR;
$html .= '         </span>'.CR;
$html .= '      </div>'.CR;
$html .= '   </div>'.CR;

$html .= '   <div class="buttonzeile">';
$html .= '      <div class="button button_left" onclick="Multibox.close();">abbrechen</div>';
$html .= '      <div class="button_ci button_right" onclick="Livedesigner.saveLogobanner();">speichern</div>';
$html .= '   </div>';
$html .= '</div>'.CR;
