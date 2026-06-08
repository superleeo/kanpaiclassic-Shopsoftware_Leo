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
$startbild_video = (file_exists(TEMPLATE_PATH.'/images/startbild_video_'.$this->params->selected_lang.'_tn.jpg') ? TEMPLATE_URL.'/images/startbild_video_'.$this->params->selected_lang.'_tn.jpg' : ADMIN_URL.'/img/nopic.png');

if (file_exists(TEMPLATE_PATH.'/images/startbild_video_'.$this->params->selected_lang.'.mp4') || file_exists(TEMPLATE_PATH.'/images/startbild_video_'.$this->params->selected_lang.'.webm') || file_exists(TEMPLATE_PATH.'/images/startbild_video_'.$this->params->selected_lang.'.mov')) {
   $startbild_video = ADMIN_URL.'/img/video.png';
}

$html .= '<div id="popup_video">'.CR;
$html .= '   <div class="txt_tit">Startbild/Video</div>'.CR;
$html .= '   <div class="video_center">'.CR;
$html .= '      <div class="startbild_video">'.CR;
$html .= '         <img id="startbild_video_img" src="'.$startbild_video.$this->params->firma['image_cache'].'" />'.CR;

$html .= '         <div class="video_icons">'.CR;
$html .= '            <span class="upload upload_button pointer" onclick="Design.uploadImg(\'startbild_video\', 0, \'startbild_video_img\', \'jpg, png, mp4, webm, mov\');" title="hochladen"></span>'.CR;
$html .= '            <span class="delete pointer far fa-trash-alt" onclick="Design.deleteImg(\'startbild_video\', 0, \'startbild_video_img\');" title="löschen"></span>'.CR;
$html .= '         </div>'.CR;
$html .= '      </div>'.CR;
$html .= '   </div>'.CR;

$html .= '   <div class="buttonzeile">';
$html .= '      <div class="button button_left" onclick="Multibox.close();">abbrechen</div>';
$html .= '      <div class="button_ci button_center" onclick="Livedesigner.saveVideo();">speichern</div>';
$html .= '   </div>';
$html .= '</div>'.CR;
