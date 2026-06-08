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
$banner_unten   = ADMIN_URL.'/img/nopic.png';
$banner_unten_w = 900;
$banner_unten_h = 110;
$sel_lang       = 'deu';
$this->params->getLinks($sel_lang);
$skalierung          = $this->json['max_width'] / 900;

if (is_file(TEMPLATE_PATH.'/images/banner2_'.$sel_lang.'.png')) {
   $size           = getimagesize(TEMPLATE_PATH.'/images/banner2_'.$sel_lang.'.png');
   $banner_unten   = TEMPLATE_URL.'/images/banner2_'.$sel_lang.'.png';
   $banner_unten_w = floor($size[0] / $skalierung);
   $banner_unten_h = floor($size[1] / $skalierung);
}

else if (is_file(TEMPLATE_PATH.'/images/banner2_'.$sel_lang.'.jpg')) {
   $size           = getimagesize(TEMPLATE_PATH.'/images/banner2_'.$sel_lang.'.jpg');
   $banner_unten   = TEMPLATE_URL.'/images/banner2_'.$sel_lang.'.jpg';
   $banner_unten_w = floor($size[0] / $skalierung);
   $banner_unten_h = floor($size[1] / $skalierung);
}

$html .= '<div id="popup_bannerunten" class="design">'.CR;
$html .= '   <div class="popup_title txt_tit">'.CR;
$html .= '      <span class="checkbox">'.CR;
$html .= '         <input type="checkbox" class="newdesign" id="bannerunten"'.($this->json['bannerunten_on'] == 'y' ? ' checked="checked"' : '').' />'.CR;
$html .= '         <label for="bannerunten"></label>'.CR;
$html .= '      </span>'.CR;
$html .= '      Banner unten'.CR;
$html .= '   </div>'.CR;

$html .= '   <div class="line">'.CR;
$html .= '      <div class="pos1">'.CR;
$html .= '         <span class="upload upload_button pointer" onclick="Design.uploadImg(\'banner2\', 0, \'pic_banner2\', \'png,jpg\');" title="hochladen"></span>'.CR;
$html .= '         <span class="delete pointer far fa-trash-alt" onclick="Design.deleteImg(\'banner2\', 0, \'pic_banner2\');" title="löschen"></span>'.CR;
$html .= '         <span class="link pointer fas fa-link" onclick="Design.linkPopup(\'banner2\', true, ($(\'#multibox2\').length ? true : false));" title="SEO"></span>'.CR;
$html .= '         <input type="hidden" id="banner2_seo" name="banner2_seo" value="'.$this->params->links['bannerseo2'].'" />'.CR;
$html .= '         <input type="hidden" id="banner2_intern" name="banner2_intern" value="'.$this->params->links['banner2_intern'].'" />'.CR;
$html .= '         <input type="hidden" id="banner2_link" name="banner2_link" value="'.$this->params->links['bannerlink2'].'" />'.CR;
$html .= '      </div>'.CR;

$html .= '      <div class="pos2">'.CR;
$html .= '         <div class="menu_box_right bg_banner_width">'.CR;
$html .= '            <div id="banner_box_right" class="bg_banner_width bg_banner">'.CR;
$html .= '               <div id="menu_box_right" class="bg_banner_width">'.CR;
//$html .= '                  <div class="logobanner_pic" style="height:'.$banner_unten_h.'px">'.CR;
$html .= '                  <div class="logobanner_pic">'.CR;
//$html .= '                     <img id="pic_banner2" style="width:'.$banner_unten_w.'px; max-width:100%; max-height:'.$banner_unten_h.'px;" src="'.$banner_unten.$this->params->firma['image_cache'].'" />'.CR;
$html .= '                     <img id="pic_banner2" style="max-width:100%;" src="'.$banner_unten.$this->params->firma['image_cache'].'" />'.CR;
$html .= '                  </div>'.CR;
$html .= '               </div>'.CR;
$html .= '               <div class="clear"></div>'.CR;
$html .= '            </div>'.CR;
$html .= '         </div>'.CR;
$html .= '      </div>'.CR;
$html .= '      <div class="clear"></div>'.CR;
$html .= '   </div>'.CR;

$html .= '   <div class="buttonzeile">';
$html .= '      <div class="button button_center" onclick="Multibox.close();">abbrechen</div>';
$html .= '      <div class="button_ci button_center" onclick="Livedesigner.saveBannerunten();">speichern</div>';
$html .= '   </div>';
$html .= '</div>'.CR;
