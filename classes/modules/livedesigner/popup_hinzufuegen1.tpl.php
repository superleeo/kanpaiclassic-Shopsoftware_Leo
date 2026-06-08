<?php
/*
###################################################################################
  Kanpai Classic Shopsoftware Entwicklungsstand: 09.02.2020 Version 2

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
/*
$params->firma['slideshow_on'] == 'y'
$params->firma['fullscreen_slide'] == 'y'
$params->firma['collage_on'] == 'y'
*/
$startseite_active = ($this->json['startseite_artikel'] == 'y' ? true : false);
$slideshow_active  = ($this->json['slideshow_on'] == 'y' ? true : false);
$collage_active    = ($this->json['collage_on'] == 'y' ? true : false);
$carussell_active  = false;
$accordion_active  = false;
$slider_active     = false;
$popup_active      = (defined('CONF_MODULE_POPUP') && $this->params->firma['popup_check'] == 'y' ? true : false);

$carussell_conf    = null;
$accordion_conf    = null;
$slider_conf       = null;
$carussell_pos     = 'center';
$accordion_pos     = 'center';
$slider_pos        = 'center';
$conf1             = $this->db->querySingleObject("SELECT data FROM #__pro WHERE typ = 'carussell_conf'");
$conf2             = $this->db->querySingleObject("SELECT data FROM #__pro WHERE typ = 'accordion_conf'");
$conf3             = $this->db->querySingleObject("SELECT data FROM #__pro WHERE typ = 'slider_conf'");

$module_data       = \KANPAICLASSIC\Helper::getData('module1', '');
$module            = null;

$option            = '';
// $startseite_breite    = $this->json['startseite_breite'];

if (is_object($conf1)) {
   $carussell_conf = json_decode($conf1->data);

   if ($carussell_conf->active_desktop == 'y' || $carussell_conf->active_tablet == 'y' || $carussell_conf->active_mobile == 'y') {
      $carussell_active = true;
   }
}

if (is_object($conf2)) {
   $accordion_conf = json_decode($conf2->data);

   if ($accordion_conf->active_desktop == 'y' || $accordion_conf->active_tablet == 'y' || $accordion_conf->active_mobile == 'y') {
      $accordion_active = true;
   }
}

if (is_object($conf3)) {
   $slider_conf = json_decode($conf3->data);

   if ($slider_conf->active_desktop == 'y' || $slider_conf->active_tablet == 'y' || $slider->active_mobile == 'y') {
      $slider_active = true;
   }
}

if ($module_data != '') {
   $module = \json_decode($module_data);

}

else {
   $module = [
      [1, 'startseite', 'Name Startseite'],
      [2, 'slideshow', 'Name Slideshow'],
      [3, 'collage', 'Name Collage'],
      [4, 'carussell', 'Name Karrusell'],
      [5, 'slider', 'Name Slider'],
      [6, 'accordion', 'Name Akkordeon'],
      [7, 'popup', 'Name Popup']
   ];

   \KANPAICLASSIC\Helper::setData('module1', json_encode($module));
}
// var_dump($module_data, $module);


//var_dump($carussell_conf, $accordion_conf, $slider_conf, $this->json);

$html .= '<div id="popup_hinzufuegen">'.CR;
$html .= '   <div class="txt_tit">Hinzufügen</div>'.CR;

$html .= '   <div id="startbild_video" class="easy">'.CR;

if (defined('CONF_MODULE_EXTENDED')) {
   $html .= '      <div id="startbild_video1" class="design">'.CR;
   $html .= '         <div class="video_left">'.CR;
   $html .= '            <div class="txt_bez">Startbild/Video</div>'.CR;
   $html .= '            <div class="upload_block_horiz">'.CR;
   $html .= '               <span class="upload pointer fas fa-caret-square-up ci_color" onclick="Design.uploadImg(\'startbild_video\', 0, \startbild_video_img\, \'jpg, png, mp4, webm, mov\');" title="hochladen"></span>'.CR;
   $html .= '               <span class="delete pointer far fa-trash-alt" onclick="Design.deleteImg(\'startbild_video\', 0, \'startbild_video_img\');" title="löschen"></span>'.CR;
   $html .= '            </div>'.CR;
   $html .= '         </div>'.CR;

   $html .= '         <div class="video_right">'.CR;
   $html .= '            <div class="startbild_video">'.CR;
//   $html .= '               <img id="startbild_video_img" src="'.$startbild_video.'" />'.CR;
   $html .= '            </div>'.CR;
   $html .= '         </div>'.CR;
   $html .= '      </div>'.CR;
}

$html .= '   </div>'.CR;
$html .= '   <div class="clear"></div>'.CR;

$html .= '   <div>'.print_r($module, true).'</div>'.CR;
$html .= '   <div class="clear"></div>'.CR;

$html .= '   <div id="module_box">'.CR;

foreach ($module AS $k => $m) {
   $tmp = $m[1].'_active';
   if (${$tmp}) {
      $html .= '      <div class="modul_zeile">';
      $html .= '         <div class="modul">'.$m[2].'</div>';
      $html .= '      </div>';
   }

   else {
      $option .= '<option value="">'.$m[2].'</option>';
   }
}

$html .= '   </div>'.CR;
$html .= '   <div class="clear"></div>'.CR;

$html .= '   <div id="select_box">'.CR;

if ($option == '') {
   $html .= 'keine weiteren Module';
}

else {
   $html .= '      <span class="selectbox30">';
   $html .= '         <select id="module">';
   $html .= $option;
   $html .= '         </selct>'.CR;
   $html .= '      </span>'.CR;
}

$html .= '   </div>'.CR;
$html .= '   <div class="clear"></div>'.CR;


$html .= '   <div class="buttonzeile">';
$html .= '      <div class="button button_left" onclick="Multibox.close();">abbrechen</div>';
$html .= '      <div class="button_ci button_center" onclick="Livedesigner.saveBreite();">speichern</div>';
$html .= '   </div>';
$html .= '</div>'.CR;
