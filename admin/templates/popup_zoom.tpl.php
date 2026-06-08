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

if (!defined('KANPAICLASSIC')) {
   die ("This file cannot run outside the Kanpai Classic Shopsoftware");
}

$image_url  = TEMPLATE_URL.'/images/';
$image_path = TEMPLATE_PATH.'/images/';
$no_img     = ADMIN_URL.'/img/nopic.png';

$html .= '<div id="popup_zoom" class="easy design">'.CR;
$html .= '   <div class="txt_tit title">Zoom auf Artikelseite <span class="fliesstext"> (globale Einstellung)</span></div>'.CR;

$html .= '   <div class="detail_zoom">'.CR;
$html .= '      <p class="line_design">'.CR;
$html .= '         <input type="radio" class="newdesign" id="detailbild1" name="detailbild"
                       onchange="$(\'#pic_detailbild\').attr(\'src\', \''.$image_url.'/system/detailbild_'.'\'+$(this).val()+\'.jpg?'.time().'\');"
                       value="1"'.($this->json['detailbild'] <= 1 ? ' checked="checked"' : '').' />'.CR;
$html .= '         <label for="detailbild1">ohne Zoom</label>'.CR;
$html .= '      </p>'.CR;
$html .= '      <p class="line_design">'.CR;
$html .= '         <input type="radio" class="newdesign" id="detailbild2" name="detailbild"
                       onchange="$(\'#pic_detailbild\').attr(\'src\', \''.$image_url.'/system/detailbild_'.'\'+$(this).val()+\'.jpg?'.time().'\');"
                       onchange="$(\'#pic_detailbild\').attr(\'src\', \''.$image_url.'/system/detailbild_'.'\'+$(this).val()+\'.jpg?'.time().'\');"
                       value="2"'.($this->json['detailbild'] == 2 ? ' checked="checked"' : '').' />'.CR;
$html .= '         <label for="detailbild2">+ Zoom außen</label>'.CR;
$html .= '      </p>'.CR;
$html .= '      <p class="line_design">'.CR;
$html .= '         <input type="radio" class="newdesign" id="detailbild3" name="detailbild"
                       onchange="$(\'#pic_detailbild\').attr(\'src\', \''.$image_url.'/system/detailbild_'.'\'+$(this).val()+\'.jpg?'.time().'\');"
                       value="3"'.($this->json['detailbild'] == 3 ? ' checked="checked"' : '').' />'.CR;
$html .= '         <label for="detailbild3">+ Zoom im Bild</label>'.CR;
$html .= '      </p>'.CR;
$html .= '      <p class="line_design">'.CR;
$html .= '         <input type="radio" class="newdesign" id="detailbild4" name="detailbild"
                       onchange="$(\'#pic_detailbild\').attr(\'src\', \''.$image_url.'/system/detailbild_'.'\'+$(this).val()+\'.jpg?'.time().'\');"
                       value="4"'.($this->json['detailbild'] == 4 ? ' checked="checked"' : '').' />'.CR;
$html .= '         <label for="detailbild4">+ großem Zoom im Bild</label>'.CR;
$html .= '      </p>'.CR;
$html .= '   </div>'.CR;

$zoom_img = (file_exists($image_path."/system/detailbild_".($this->json['detailbild'] > 0 ? $this->json['detailbild'] : 1).'.jpg') ? $image_url."/system/detailbild_".($this->json['detailbild'] > 0 ? $this->json['detailbild'] : 1).'.jpg' : $no_img);
$html .= '   <div class="detail_pic">'.CR;
$html .= '      <img id="pic_detailbild" src="'.$zoom_img.'?'.time().'" alt="" />'.CR;
$html .= '   </div>'.CR;
$html .= '   <div class="clear"></div>'.CR;

$html .= '   <div class="buttonzeile">';
$html .= '      <div class="button button_left" onclick="Multibox.close();">abbrechen</div>';
$html .= '      <div class="button_ci button_center" onclick="Artikel.zoomPopupSave();">speichern</div>';
$html .= '   </div>';
$html .= '</div>'.CR;

/*
$html .= '   <div class="detail_tab template_none show_1 <?php echo $template_show; ?>">'.CR;
$html .= '      <div class="line_design">'.CR;
$html .= '         <input type="radio" class="newdesign" id="" name="bild_tab" value="y" '.($this->json['bild_tab'] == 'y' ? ' checked="checked"': '').' />'.CR;
$html .= '         &nbsp;als separ. Tab'.CR;
$html .= '      </div>'.CR;
$html .= '      <div class="line_design">'.CR;
$html .= '         <input type="radio" class="newdesign" id="" name="bild_tab" value="n" <?php echo ($this->json['bild_tab'] != 'y' ? ' checked="checked"': ''); ?> />'.CR;
$html .= '         &nbsp;Artikeltext unterm Bild'.CR;
$html .= '      </div>'.CR;
$html .= '      <div class="clear"></div>'.CR;
$html .= '   </div>'.CR;
$html .= '   <div class="clear"></div>'.CR;
$html .= '   <hr />'.CR;
*/
