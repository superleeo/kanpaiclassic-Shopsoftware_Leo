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
$slideshow_mode = 'normal';
$bild           = '';
$bg_normal      = ADMIN_URL.'/img/slideshow_normal.jpg';
$bg_right       = ADMIN_URL.'/img/slideshow_links.jpg';
$bg_fullscreen  = ADMIN_URL.'/img/slideshow_bildschirmbreit.jpg';

// Angezeigtes Bild beim Start
if( file_exists($image_path.'slide1_'.$sel_lang.'.jpg')) {
   $bild = $image_url.'slide1w_'.$sel_lang.'.jpg?'.$this->params->firma['image_cache'];

   if ($this->json['fullscreen_slide'] == 'y') {
      $slideshow_mode = 'fullscreen';
      $bild = $image_url.'slide1l_'.$sel_lang.'.jpg'.$this->params->firma['image_cache'];
   }

   else if ($this->params->firma['slideshow_r_check'] == 'y') {
      $slideshow_mode = 'right';
      $bild           = $image_url.'slide1_'.$sel_lang.'.jpg'.$this->params->firma['image_cache'];
   }
}

// Background, falls nicht vorhanden
else {
   $bild = $bg_normal;

   if ($this->json['fullscreen_slide'] == 'y') {
      $slideshow_mode = 'fullscreen';
      $bild = $bg_fullscreen;
   }

   else if ($this->params->firma['slideshow_r_check'] == 'y') {
      $slideshow_mode = 'right';
      $bild           = $bg_right;
   }
}

$html .= '                 <div id="slideshow_hidden" class="box_right'.($slideshow_mode != 'fullscreen' ? ' max_width900' : '').'"'.($slideshow_on == 'n' && $this->params->task != 'designLivedesigner' ? ' style="display:none;"' : '').'>'.CR;
$html .= '                     <div class="slideshow_right_check txt_bez"'.($slideshow_mode == 'fullscreen' ? ' style="display:none;"': '').'>'.CR;
$html .= '                        <input type="checkbox" class="newdesign" id="slideshow_r_check" name="slideshow_r_check"'.($this->json['slideshow_r_check'] == 'y' ? ' checked="checked"' : '').' onchange="Design.checkSlideshow()" />'.CR;
$html .= '                        <label for="slideshow_r_check"></label>Bilder rechts'.CR;
$html .= '                     </div>'.CR;

$html .= '                     <div id="slideshow_left" class="pos_'.$slideshow_mode.'">'.CR;
$html .= '                        <div>'.CR;
$html .= '                           <div id="slide_preview">'.CR;
$html .= '                              <img id="slide_img" src="'.$bild.$this->params->firma['image_cache'].'" alt="" />'.CR;
$html .= '                           </div>'.CR;
$html .= '                           <div id="preview_nr">1</div>'.CR;

//Slides 1 - 8 / link11 - link18
$html .= '                           <div class="slide_icons">'.CR;

for ($i = 1; $i <= 8; $i++) {
   $bild  = '';
   $bild1 = '';
   $bild2 = '';
   $bild3 = '';

   if (file_exists($image_path.'slide'.$i.'_'.$sel_lang.'.jpg')) {
      $bild1 = $image_url.'slide'.$i.'w_'.$sel_lang.'.jpg?'.$this->params->firma['image_cache'];
      $bild2 = $image_url.'slide'.$i.'_'.$sel_lang.'.jpg?'.$this->params->firma['image_cache'];
      $bild3 = $image_url.'slide'.$i.'l_'.$sel_lang.'.jpg?'.$this->params->firma['image_cache'];

      if ($slideshow_mode == 'normal') {
         $bild = $bild1;
      }

      else if ($slideshow_mode == 'right') {
         $bild = $bild2;
      }

      else if ($slideshow_mode == 'fullscreen') {
         $bild = $bild3;
      }
   }

   else {
      $bild1 = $bg_normal;
      $bild2 = $bg_right;
      $bild3 = $bg_fullscreen;

      if ($slideshow_mode == 'normal') {
         $bild = $bg_normal;
      }

      else if ($slideshow_mode == 'right') {
         $bild = $bg_right;
      }

      else if ($slideshow_mode == 'fullscreen') {
         $bild = $bg_fullscreen;
      }
   }
   $link_text           = $this->params->links['link1'.$i.'_text'];
   $link_color_text     = $this->params->links['link1'.$i.'_color_text'];
   $link_color_text_opc = $this->params->links['link1'.$i.'_color_text_opc'];
   $link_color_bg       = $this->params->links['link1'.$i.'_color_bg'];
   $link_color_bg_opc   = $this->params->links['link1'.$i.'_color_bg_opc'];

   if ($link_text == '' && $link_color_text == '' && $link_color_bg == '') {
      $link_color_text     = '#ffffff';
      $link_color_text_opc = '1';
      $link_color_bg       = '#000000';
      $link_color_bg_opc   = '0.7';
   }

   $html .= '                              <div class="slider_line">'.CR;
   $html .= '                                 <div class="upload_block_horiz"
                                                 data-src="'.$bild.'"
                                                   data-src_normal     = "'.$bild1.'"
                                                   data-src_right      = "'.$bild2.'"
                                                   data-src_fullscreen = "'.$bild3.'"
                                                   onmouseover="$(\'#slide_img\').attr(\'src\', $(this).attr(\'data-src\')); $(\'#preview_nr\').html(\''.$i.'\');">'.CR;
   $html .= '                                    <span class="upload upload_button pointer" onclick="Design.uploadImg(\'slide\', '.$i.', \'slide_img\');"></span>'.CR;
   $html .= '                                    <span class="delete pointer far fa-trash-alt" onclick="Design.deleteImg(\'slide\', '.$i.', \'slide_img\', $(this).closest(\'.upload_block_horiz\'));" title="löschen"></span>'.CR;
   $html .= '                                    <span class="link pointer fas fa-link" onclick="Design.linkPopup(\'link1'.$i.'\')" title="Bild '.$i.' verlinken / SEO"></span>'.CR;
   $html .= '                                    <input type="hidden" id="link1'.$i.'_link"           name="link1'.$i.'_link"           value="'.$this->params->links['link1'.$i].'" placeholder="http://" />'.CR;
   $html .= '                                    <input type="hidden" id="link1'.$i.'_intern"         name="link1'.$i.'_intern"         value="'.$this->params->links['link1'.$i.'_intern'].'" />'.CR;
   $html .= '                                    <input type="hidden" id="link1'.$i.'_seo"            name="link1'.$i.'_seo"            value="'.$this->params->links['link1'.$i.'_seo'].'" />'.CR;
   $html .= '                                    <input type="hidden" id="link1'.$i.'_text"           name="link1'.$i.'_text"           value="'.$link_text.'" />'.CR;
   $html .= '                                    <input type="hidden" id="link1'.$i.'_color_text"     name="link1'.$i.'_color_text"     value="'.$link_color_text.'" />'.CR;
   $html .= '                                    <input type="hidden" id="link1'.$i.'_color_text_opc" name="link1'.$i.'_color_text_opc" value="'.$link_color_text_opc.'" />'.CR;
   $html .= '                                    <input type="hidden" id="link1'.$i.'_color_bg"       name="link1'.$i.'_color_bg"       value="'.$link_color_bg.'" />'.CR;
   $html .= '                                    <input type="hidden" id="link1'.$i.'_color_bg_opc"   name="link1'.$i.'_color_bg_opc"   value="'.$link_color_bg_opc.'" />'.CR;
   $html .= '                                    <div class="clear"></div>'.CR;
   $html .= '                                 </div>'.CR;
   $html .= '                              </div>'.CR;
}

$html .= '                           </div>'.CR;
$html .= '                        </div>'.CR;
$html .= '                     </div>'.CR;

$html .= '                     <div id="slideshow_right" class="'.($slideshow_mode != 'right' ? 'fullscreen_hide' : '').'">'.CR;
$html .= '                        <div class="slideshow_right_top">'.CR;
$html .= '                           <div>'.CR;

// Slides 9 und 10 /link19 / link20
if (file_exists($image_path.'/slide9_'.$sel_lang.'.jpg')) {
   $bild = $image_url.'slide9_'.$sel_lang.'.jpg?'.$this->params->firma['image_cache'];
}
else {
   $bild = ADMIN_URL.'/img/slideshow_rechts.jpg';
}

$link_text           = $this->params->links['link19_text'];
$link_color_text     = $this->params->links['link19_color_text'];
$link_color_text_opc = $this->params->links['link19_color_text_opc'];
$link_color_bg       = $this->params->links['link19_color_bg'];
$link_color_bg_opc   = $this->params->links['link19_color_bg_opc'];

if ($link_text == '' && $link_color_text == '' && $link_color_bg == '') {
   $link_color_text     = '#ffffff';
   $link_color_text_opc = '1';
   $link_color_bg       = '#000000';
   $link_color_bg_opc   = '0.7';
}

$html .= '                              <div class="slider_right_top_img">'.CR;
$html .= '                                 <img id="slider_right_top_image" src="'.$bild.$this->params->firma['image_cache'].'" alt="">'.CR;
$html .= '                              </div>'.CR;
$html .= '                              <div class="slider_line slider_intern">'.CR;
$html .= '                                 <span class="upload upload_button pointer" onclick="Design.uploadImg(\'slide\', 9, \'slider_right_top_image\');"></span>'.CR;
$html .= '                                 <span class="delete pointer far fa-trash-alt" onclick="Design.deleteImg(\'slide\', 9, \'slider_right_top_image\');" title="löschen"></span>'.CR;
$html .= '                                 <span class="link pointer fas fa-link" onclick="Design.linkPopup(\'link19\');" title="Bild rechts oben verlinken / SEO"></span>'.CR;
$html .= '                                 <input type="hidden" id="link19_link"           name="link19_link"           value="'.$this->params->links['link19'].'" />'.CR;
$html .= '                                 <input type="hidden" id="link19_intern"         name="link19_intern"         value="'.$this->params->links['link19_intern'].'" />'.CR;
$html .= '                                 <input type="hidden" id="link19_seo"            name="link19_seo"            value="'.$this->params->links['link19_seo'].'" />'.CR;
$html .= '                                 <input type="hidden" id="link19_text"           name="link19_text"           value="'.$this->params->links['link19_text'].'" />'.CR;
$html .= '                                 <input type="hidden" id="link19_color_text"     name="link19_color_text"     value="'.$this->params->links['link19_color_text'].'" />'.CR;
$html .= '                                 <input type="hidden" id="link19_color_text_opc" name="link19_color_text_opc" value="'.$this->params->links['link19_color_text_opc'].'" />'.CR;
$html .= '                                 <input type="hidden" id="link19_color_bg"       name="link19_color_bg"       value="'.$this->params->links['link19_color_bg'].'" />'.CR;
$html .= '                                 <input type="hidden" id="link19_color_bg_opc"   name="link19_color_bg_opc"   value="'.$this->params->links['link19_color_bg_opc'].'" />'.CR;
$html .= '                                 <div class="clear"></div>'.CR;
$html .= '                              </div>'.CR;
$html .= '                           </div>'.CR;
$html .= '                        </div>'.CR;

$html .= '                        <div class="slideshow_right_bottom">'.CR;
$html .= '                           <div>'.CR;
if (file_exists($image_path.'slide10_'.$sel_lang.'.jpg')) {
   $bild = $image_url.'slide10_'.$sel_lang.'.jpg?'.$this->params->firma['image_cache'];
}
else {
   $bild = ADMIN_URL.'/img/slideshow_rechts.jpg';
}

$link_text           = $this->params->links['link20_text'];
$link_color_text     = $this->params->links['link20_color_text'];
$link_color_text_opc = $this->params->links['link20_color_text_opc'];
$link_color_bg       = $this->params->links['link20_color_bg'];
$link_color_bg_opc   = $this->params->links['link20_color_bg_opc'];

if ($link_text == '' && $link_color_text == '' && $link_color_bg == '') {
   $link_color_text     = '#ffffff';
   $link_color_text_opc = '1';
   $link_color_bg       = '#000000';
   $link_color_bg_opc   = '0.7';
}

$html .= '                              <div class="slider_right_button_img">'.CR;
$html .= '                                 <img id="slider_right_button_image" src="'.$bild.$this->params->firma['image_cache'].'" alt="">'.CR;
$html .= '                              </div>'.CR;
$html .= '                              <div class="slider_line slider_intern">'.CR;
$html .= '                                 <span class="upload upload_button pointer" onclick="Design.uploadImg(\'slide\', 10, \'slider_right_button_image\');"></span>'.CR;
$html .= '                                 <span class="delete pointer far fa-trash-alt" onclick="Design.deleteImg(\'slide\', 10, \'slider_right_button_image\');" title="löschen"></span>'.CR;
$html .= '                                 <span class="link pointer fas fa-link" onclick="Design.linkPopup(\'link20\');" title="Bild rechts unten verlinken / SEO"></span>'.CR;
$html .= '                                 <input type="hidden" id="link20_link"           name="link20_link"           value="'.$this->params->links['link20'].'" />'.CR;
$html .= '                                 <input type="hidden" id="link20_intern"         name="link20_intern"         value="'.$this->params->links['link20_intern'].'" />'.CR;
$html .= '                                 <input type="hidden" id="link20_seo"            name="link20_seo"            value="'.$this->params->links['link20_seo'].'" />'.CR;
$html .= '                                 <input type="hidden" id="link20_text"           name="link20_text"           value="'.$link_text.'" />'.CR;
$html .= '                                 <input type="hidden" id="link20_color_text"     name="link20_color_text"     value="'.$link_color_text.'" />'.CR;
$html .= '                                 <input type="hidden" id="link20_color_text_opc" name="link20_color_text_opc" value="'.$link_color_text_opc.'" />'.CR;
$html .= '                                 <input type="hidden" id="link20_color_bg"       name="link20_color_bg"       value="'.$link_color_bg.'" />'.CR;
$html .= '                                 <input type="hidden" id="link20_color_bg_opc"   name="link20_color_bg_opc"   value="'.$link_color_bg_opc.'" />'.CR;
$html .= '                                 <div class="clear"></div>'.CR;
$html .= '                              </div>'.CR;
$html .= '                           </div>'.CR;
$html .= '                        </div>'.CR;
$html .= '                     </div>'.CR;
$html .= '                     <div class="clear"></div>'.CR;

$html .= '                     <div class="slider_side">'.CR;
$html .= '                        <input type="checkbox" class="newdesign" id="rechts_slide" name="rechts_slide" '.($this->json['rechts_slide'] == 'y' ? 'checked="checked" ' : '').' />'.CR;
$html .= '                        <label for="rechts_slide">seitwärts</label>'.CR;
$html .= '                     </div>'.CR;

if (defined('CONF_MODULE_EXTENDED')) {
   $html .= '                     <div class="slider_side">'.CR;
   $html .= '                        <input type="checkbox" class="newdesign" id="fullscreen_slide" name="fullscreen_slide" '.($this->json['fullscreen_slide'] == 'y' ? 'checked="checked" ' : '').' onchange="Design.checkSlideshow(this);" />'.CR;
   $html .= '                        <label for="fullscreen_slide"><span>bildschirmbreit (nur bei hor. Kategorien)<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1900x750px</span></label>'.CR;
   $html .= '                     </div>'.CR;
}

$html .= '                  </div>'.CR;
$html .= '                  <div class="clear"></div>'.CR;
