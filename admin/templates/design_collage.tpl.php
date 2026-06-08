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

// Auch von Livedesigner verwendet
$collage_arr = [];

for ($i = 1; $i <= 8; $i++) {
   $bild    = '';

   if( file_exists($image_path.'bild'.$i.'_'.$sel_lang.'.jpg')) {
      $bild = $image_url.'bild'.$i.'_'.$sel_lang.'.jpg?'.time();
   }

   else {
      $bild = 'no_img';
   }

   switch ($i) {
      case 1:
         $collage_arr[$i] = ['groesse' => 'Bild'.$i.' (700x438px)', 'bild' => $bild, 'name' => 'Upload Bild oben links', 'no_img' => ADMIN_URL.'/img/kollage_700x438.jpg'];
         break;
      case 2:
         $collage_arr[$i] = ['groesse' => 'Bild'.$i.' (700x438px)', 'bild' => $bild, 'name' => 'Upload Bild oben rechts', 'no_img' => ADMIN_URL.'/img/kollage_700x438.jpg'];
         break;
      case 3:
         $collage_arr[$i] = ['groesse' => 'Bild'.$i.' (700x438px)', 'bild' => $bild, 'name' => 'Upload Bild Reihe 2 links', 'no_img' => ADMIN_URL.'/img/kollage_700x438.jpg'];
         break;
      case 4:
         $collage_arr[$i] = ['groesse' => 'Bild'.$i.' (700x438px)', 'bild' => $bild, 'name' => 'Upload Bild Reihe 2 rechts', 'no_img' => ADMIN_URL.'/img/kollage_700x438.jpg'];
         break;
      case 5:
         $collage_arr[$i] = ['groesse' => 'Bild'.$i.' (961x438px)', 'bild' => $bild, 'name' => 'Upload Bild Reihe 3 links', 'no_img' => ADMIN_URL.'/img/kollage_961x438.jpg'];
         break;
      case 6:
         $collage_arr[$i] = ['groesse' => 'Bild'.$i.' (438x438px)', 'bild' => $bild, 'name' => 'Upload Bild Reihe 3 rechts', 'no_img' => ADMIN_URL.'/img/kollage_438x438.jpg'];
         break;
      case 7:
         $collage_arr[$i] = ['groesse' => 'Bild'.$i.' (438x438px)', 'bild' => $bild, 'name' => 'Upload Bild unten links', 'no_img' => ADMIN_URL.'/img/kollage_438x438.jpg'];
         break;
      case 8:
         $collage_arr[$i] = ['groesse' => 'Bild'.$i.' (961x438px)', 'bild' => $bild, 'name' => 'Upload Bild unten rechts', 'no_img' => ADMIN_URL.'/img/kollage_961x438.jpg'];
         break;
   }
}

$html .= '<div id="collage_hidden" class="box_right" '.($collage_on == 'n' ? ' style="display:none;"' : '').'>'.CR;
$html .= '   <div class="">'.CR;

for ($i = 1; $i <= 8; $i++) {
   $bild                = $collage_arr[$i]['bild'] != 'no_img' ? $collage_arr[$i]['bild'] : $collage_arr[$i]['no_img'];
   $link_text           = $this->params->links['link'.$i.'_text'];
   $link_color_text     = $this->params->links['link'.$i.'_color_text'];
   $link_color_text_opc = $this->params->links['link'.$i.'_color_text_opc'];
   $link_color_bg       = $this->params->links['link'.$i.'_color_bg'];
   $link_color_bg_opc   = $this->params->links['link'.$i.'_color_bg_opc'];

   if ($link_text == '' && $link_color_text == '' && $link_color_bg == '') {
      $link_color_text     = '#ffffff';
      $link_color_text_opc = '1';
      $link_color_bg       = '#000000';
      $link_color_bg_opc   = '0.7';
   }

   $html .= '      <div class="collage_block collage_block'.$i.'">'.CR;
   $html .= '         <div class="collage_block_inner">'.CR;
   $html .= '            <div class="collage_size">'.$collage_arr[$i]['groesse'].'</div>'.CR;
   $html .= '            <img id="collage_img_'.$i.'" src="'.$bild.$this->params->firma['image_cache'].'" alt="" data-no_img="'.$collage_arr[$i]['no_img'].'"/>'.CR;
   $html .= '            <div class="collage_icons">'.CR;
   $html .= '               <div class="slider_line">'.CR;
   $html .= '                  <span class="upload pointer upload_button" onclick="Design.uploadImg(\'collage\', '.$i.', \'collage_img_'.$i.'\');"></span>'.CR;
   $html .= '                  <span class="delete pointer far fa-trash-alt" onclick="Design.deleteImg(\'collage\', '.$i.', \'collage_img_'.$i.'\', $(this).closest(\'.upload_block_horiz\'));" title="löschen"></span>'.CR;
   $html .= '                  <span class="link pointer fas fa-link" onclick="Design.linkPopup(\'link'.$i.'\')" title="Bild '.$i.' verlinken / SEO"></span>'.CR;
   $html .= '                  <input type="hidden" id="link'.$i.'_link"           name="link'.$i.'_link"           value="'.$this->params->links['link'.$i].'" />'.CR;
   $html .= '                  <input type="hidden" id="link'.$i.'_intern"         name="link'.$i.'_intern"         value="'.$this->params->links['link'.$i.'_intern'].'" />'.CR;
   $html .= '                  <input type="hidden" id="link'.$i.'_seo"            name="link'.$i.'_seo"            value="'.$this->params->links['link'.$i.'_seo'].'" />'.CR;
   $html .= '                  <input type="hidden" id="link'.$i.'_text"           name="link'.$i.'_text"           value="'.$link_text.'" />'.CR;
   $html .= '                  <input type="hidden" id="link'.$i.'_color_text"     name="link'.$i.'_color_text"     value="'.$link_color_text.'" />'.CR;
   $html .= '                  <input type="hidden" id="link'.$i.'_color_text_opc" name="link'.$i.'_color_text_opc" value="'.$link_color_text_opc.'" />'.CR;
   $html .= '                  <input type="hidden" id="link'.$i.'_color_bg"       name="link'.$i.'_color_bg"       value="'.$link_color_bg.'" />'.CR;
   $html .= '                  <input type="hidden" id="link'.$i.'_color_bg_opc"   name="link'.$i.'_color_bg_opc"   value="'.$link_color_bg_opc.'" />'.CR;
   $html .= '                  <div class="clear"></div>'.CR;
   $html .= '               </div>'.CR;
   $html .= '            </div>'.CR;
   $html .= '         </div>'.CR;
   $html .= '      </div>'.CR;

   if ($i == 2 || $i == 4 || $i == 6 || $i == 8) {
      $html .= '      <div class="clear"></div>'.CR;
   }
}

$html .= '      <div class="slider_line red">Diese Beispielmaße gelten bei Shopbreite 1400px.</div>'.CR;
$html .= '   </div>'.CR;
$html .= '</div>'.CR;
