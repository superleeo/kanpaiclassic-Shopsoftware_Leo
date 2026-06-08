<?php
$template_images      = TEMPLATE_URL.'/images/';
$template_images_file = TEMPLATE_PATH.'/images/';
$no_img               = ADMIN_URL.'/img/nopic.png';

$html .= '<div id="popup_seiten" class="'.$seite.'">'.CR;

if ($info !==  '') {
   $html .= '   <div id="popup_info">'.$info.'</div>'.CR;
}
$html .= '   <div class="text_edit_block">'.CR;

if  ($seite == 'starthtml') {
   $html .= '      <div class="starthtml_titel txt_tit">Home</div>'.CR;
   $html .= '         <div class="starthtml_zeile">'.CR;
   $html .= '            <span class="starthtml_left fliesstext">TitleTag</span>'.CR;
   $html .= '            <span class="starthtml_right"><input type="text" class="txt_inp" id="titeltag" name="titeltag" value="'.$data->titeltag.'" /></span>'.CR;
   $html .= '         </div>'.CR;

   $html .= '         <div class="starthtml_zeile">'.CR;
   $html .= '            <span class="starthtml_left fliesstext">DescriptionTag</span>'.CR;
   $html .= '            <span class="starthtml_right"><input type="text" class="txt_inp" id="description" name="description" value="'.$data->description.'" /></span>'.CR;
   $html .= '         </div>'.CR;

   $html .= '         <div class="starthtml_zeile">'.CR;
   $html .= '            <span class="starthtml_left fliesstext">Keywords</span>'.CR;
   $html .= '            <span class="starthtml_right"><input type="text" class="txt_inp" id="keywords" name="keywords" value="'.$data->keywords.'" /></span>'.CR;
   $html .= '         </div>'.CR;
   $html .= '      </div>'.CR;
}

else {
   $html .= '      <div class="text_block">'.CR;

   // Titelzeile Überuns
   if (strstr($seite, 'ueberuns') !== false) {
      $html .= '         '.CR;
      $html .= '         <div class="input_block">'.CR;
      $html .= '            <span class="keyword_text txt_tit">Seitenname</span>';
      $html .= '            <span class="keywords_inp">'.CR;
      $html .= '               <input type="text" id="title_name" class="txt_inp" name="title_name" value="'.$data->name.'" placeholder="" />'.CR;
      $html .= '            </span>'.CR;
      $html .= '         </div>'.CR;
   }

   // Titelzeile Widerruf
   else  if (strstr($seite, 'widerruf') !== false) {
      $html .= '         <div class="block_title txt_tit">'.CR;
      $html .= '            Widerrufsrecht <input type="text" id ="title_name" class="txt_inp" name="title_name" value="'.$data->name.'" />'.CR;
      $html .= '         </div>'.CR;
   }

   // Titelzeile andere Seiten
   else {
      $html .= '         <div class="block_title txt_tit">'.$this->_checkName($seite, '').'</div>'.CR;
   }

   // PDF-Vorschau
   if ($seite == 'versand' || $seite == 'agb' || strstr($seite, 'widerruf') !== false) {
      $html .= '         <div class="widerruf_pdf">'.CR;
      $html .= '            <span class="txt_bez no_bold pdf_text">PDF-Vorschau</span><a href="'.$pdf_link.'" target="_blank"><span class="has_pdf"></span></a>'.CR;
      $html .= '         </div>'.CR;
   }

   // Bilder (Kontakt, Impressum, ueberus1 - 5)
   if ($seite == 'kontakt' ||
       $seite == 'impressum' ||
       strstr($seite, 'ueberuns') !== false)
   {
      $image1  = \KANPAICLASSIC\Helper::getData('ueberuns'.$uns.'_'.$lang.'_image1');
      $link1   = \KANPAICLASSIC\Helper::getData('ueberuns'.$uns.'_'.$lang.'_link1');
      $intern1 = \KANPAICLASSIC\Helper::getData('ueberuns'.$uns.'_'.$lang.'_intern1');
      $seo1    = \KANPAICLASSIC\Helper::getData('ueberuns'.$uns.'_'.$lang.'_seo1');

      $image2  = \KANPAICLASSIC\Helper::getData('ueberuns'.$uns.'_'.$lang.'_image2');
      $link2   = \KANPAICLASSIC\Helper::getData('ueberuns'.$uns.'_'.$lang.'_link2');
      $intern2 = \KANPAICLASSIC\Helper::getData('ueberuns'.$uns.'_'.$lang.'_intern2');
      $seo2    = \KANPAICLASSIC\Helper::getData('ueberuns'.$uns.'_'.$lang.'_seo2');

      $data1 = 'ueberuns'.$uns.'_'.$lang.'_image1';
      $data2 = 'ueberuns'.$uns.'_'.$lang.'_image2';

      $html .= '         <div class="image_block">'.CR;
      $html .= '            <div class="image_left">'.CR;
      $html .= '               <div class="img_title txt_bez">1.</div>'.CR;
      $html .= '               <div class="upload upload_button pointer" title="Bild laden" onclick="Seiten.upload(1, '.$uns.', \'ueberuns_image1\');"></div>'.CR;
      $html .= '               <div class="delete far fa-trash-alt" title="Bild löschen" onclick="Seiten.delete(this, \'uns'.$uns.'_'.$lang.'_image1\', \'ueberuns'.$uns.'_'.$lang.'_image1\' );"></div>'.CR;
      $html .= '               <div class="link fas fa-link" onclick="Seiten.linkPopup(\''.$seite.'\', \'popup\', 1);" title="Bild verlinken / SEO"></div>'.CR;
      $html .= '               <div class="image nopic78">'.CR;
      $html .= '                  <img id="ueberuns_image1" src="'.($image1 != '' ? $template_images.$image1.'_tn.jpg' : $no_img).'?'.time().'" alt="" />'.CR;
      $html .= '               </div>'.CR;
      $html .= '               <input type="hidden" id="popup_image1"  name="popup_image1"  value="'.$image1.'" />'.CR;
      $html .= '               <input type="hidden" id="popup_link1"   name="popup_link1"   value="'.$link1.'" />'.CR;
      $html .= '               <input type="hidden" id="popup_intern1" name="popup_intern1" value="'.$intern1.'" />'.CR;
      $html .= '               <input type="hidden" id="popup_seo1"    name="popup_seo1"    value="'.$seo1.'" />'.CR;
      $html .= '            </div>'.CR;

      $html .= '            <div class="image_right">'.CR;
      $html .= '               <div class="img_title txt_bez">2.</div>'.CR;
      $html .= '               <div class="upload upload_button pointer" title="Bild laden" onclick="Seiten.upload(2, '.$uns.', \'ueberuns_image2\');"></div>'.CR;
      $html .= '               <div class="delete far fa-trash-alt" title="Bild löschen" onclick="Seiten.delete(this, \'uns'.$uns.'_'.$lang.'_image2\', \'ueberuns'.$uns.'_'.$lang.'_image2\' );"></div>'.CR;
      $html .= '               <div class="link fas fa-link" onclick="Seiten.linkPopup(\''.$seite.'\', \'popup\', 2);" title="Bild verlinken / SEO"></div>'.CR;
      $html .= '               <div class="image nopic78">'.CR;
      $html .= '                  <img id="ueberuns_image2" src="'.($image2 != '' ? $template_images.$image2.'_tn.jpg' : $no_img).'?'.time().'" alt="" />'.CR;
      $html .= '               </div>'.CR;
      $html .= '               <input type="hidden" id="popup_image2"  name="popup_image2"  value="'.$image2.'" />'.CR;
      $html .= '               <input type="hidden" id="popup_link2"   name="popup_link2"   value="'.$link2.'" />'.CR;
      $html .= '               <input type="hidden" id="popup_intern2" name="popup_intern2" value="'.$intern2.'" />'.CR;
      $html .= '               <input type="hidden" id="popup_seo2"    name="popup_seo2"    value="'.$seo2.'" />'.CR;
      $html .= '            </div>'.CR;
      $html .= '            <div class="clear"></div>'.CR;
      $html .= '         </div>'.CR;

      $html .= '         <div class="clear"></div>'.CR;
   }

   // Title, Keywords, Description
   if ($seite == 'kontakt' || $seite == 'impressum' || $seite == 'datenschutz' || $seite == 'versand' || $seite == 'agb' || strstr($seite, 'ueberuns') !== false) {
      $html .= '         <div class="keywords_block '.$seite.'">'.CR;
      $html .= '            <div class="keyword_zeile">'.CR;
      $html .= '               <span class="keyword_text fliesstext">TitleTag</span>'.CR;
      $html .= '               <span class="keywords_inp"><input type="text" class="txt_inp" id="titeltag" name="titeltag" value="'.$data->titeltag.'" /></span>'.CR;
      $html .= '            </div>'.CR;
      $html .= '            <div class="keyword_zeile">'.CR;
      $html .= '               <span class="keyword_text fliesstext">DescriptionTag</span>'.CR;
      $html .= '               <span class="keywords_inp"><input type="text" class="txt_inp" id="description" name="description" value="'.$data->description.'" /></span>'.CR;
      $html .= '            </div>'.CR;
      $html .= '            <div class="keyword_zeile">'.CR;
      $html .= '               <span class="keyword_text fliesstext">Keywords</span>'.CR;
      $html .= '               <span class="keywords_inp"><input type="text" class="txt_inp" id="keywords" name="keywords" value="'.$data->keywords.'" /></span>'.CR;
      $html .= '            </div>'.CR;
      $html .= '         </div>'.CR;
      $html .= '         <div class="clear"></div>'.CR;
   }

   // Inhaberdaten Checkbox
   if ($seite == 'kontakt' || $seite == 'impressum') {
      $inhaber_check = ($seite == 'kontakt' ? $this->params->firma['kontakt_inhaber'] : $this->params->firma['impressum_inhaber']);

      $html .= '         <div class="ext_block">'.CR;
      $html .= '            <span class="keyword_text" title="Nur in 1 Sprache verfügbar">Inhaberdaten</span>'.CR;
      $html .= '            <span class="keywords_inp">'.CR;
      $html .= '               <input type="checkbox" class="newdesign" id="inhaber_check" name="inhaber_check"'.($inhaber_check == 'y' ? ' checked="checked"' : '').' />'.CR;
      $html .= '               <label for="inhaber_check">anzeigen</label>'.CR;
      $html .= '            </span>'.CR;
      $html .= '         </div>'.CR;
   }

   // Formular re / unten (nur bei Kontakt)
   if ($seite == 'kontakt') {
      $check = $data->check;
      $html .= '         <div class="ext_block">';
      $html .= '            <span class="keyword_text">Formular</span>'.CR;
      $html .= '            <span class="keywords_inp">'.CR;
      $html .= '               <input type="radio" class="newdesign" id="kontakt_rechts2" name="kontakt_rechts" value="n"'.($check !== 'y' ? ' checked="checked"' : '').' />'.CR;
      $html .= '               <label for="kontakt_rechts2">unten</label>'.CR;
      $html .= '               <input type="radio" class="newdesign" id="kontakt_rechts1" name="kontakt_rechts" value="y"'.($check === 'y' ? ' checked="checked"' : '').' />'.CR;
      $html .= '               <label for="kontakt_rechts1">rechts</label>&nbsp;&nbsp;&nbsp;'.CR;
      $html .= '            </span>'.CR;
      $html .= '         </div>'.CR;
   }

   // Streitschlichtung (nur KundenInfo)
   //if ($seite == 'kundeninfo') {
   //   $html .= '         <div class="widerruf_block">'.CR;
   //   $html .= '            <div class="schlichtung">'.CR;
   //   $html .= '               <input type="checkbox" class="newdesign" id="schlichtung_check" name ="schlichtung_check"'.($this->params->firma['schlichtung_check'] == 'y' ? ' checked="checked"' : '').'" />'.CR;
   //   $html .= '               <label for="schlichtung_check"><span class="txt_bez ellipsis">Streitschlichtungsplattform-Link</span></label>'.CR;
   //   $html .= '            </div>'.CR;
   //   $html .= '         </div>'.CR;
   //}

   // Widerruf-Texte, Mustertext bei Widerruf1
   if (strstr($seite, 'widerruf') !== false) {
      $html .= '         <div class="widerruf_show">'.CR;
      $html .= '            <input type="checkbox" class="newdesign" id="widerruf_form" name="widerruf_form"'.($this->params->firma[$seite.'_form'] == 'y' ? ' checked="checked"' : '').' />'.CR;
      $html .= '            <label for="widerruf_form">Formular anzeigen</label>'.CR;
      $html .= '         </div>'.CR;

      $html .= '         <div class="widerruf_box txt_bez box_title">'.$wr_name.'</div>'.CR;
      $html .= '         <div class="widerruf_box fliesstext">'.$wr_txt.'</div>'.CR;

      // Mustertext
//      if ($seite == 'widerruf1') {
      if (strpos($seite, 'widerruf') !== false) {
         $html .= '         <div class="widerruf_mustertext">'.CR;
         $html .= '            <a class="button txt_btn" href="'.HELP_LINK.'/o1/widerruf-mustertexte/" target="_blank">Mustertexte</a>'.CR;
         $html .= '         </div>'.CR;
      }
   }

   $html .= '      </div>'.CR;

   // Editor überlagert linke Spalte
   $html .= '      <div class="clear"></div>'.CR;
   $html .= '      <div class="edit_block">'.CR;
   $html .= '         <textarea class="edit_schmal" id="text" name="text" rows="10" cols="100">'.$data->text.'</textarea>'.CR;
   $html .= '      </div>'.CR;
   $html .= '      <div class="clear"></div>'.CR;
   $html .= '   </div>'.CR;

   // Bei Datenschutz DS-GVO anzeigen + Telefon
   if ($seite == 'datenschutz') {
      $text  = $data2->text;
      $check = $data2->check;

      $html .= '   <div class="text_edit_block2">'.CR;
      $html .= '      <div class="text_block">'.CR;
      $html .= '         <div class="keyword_zeile">'.CR;
      $html .= '            <span class="keyword_text right"><a class="help ci_color" href="'.HELP_LINK.'/o38/datenschutz/" target="_blank"></a></span>'.CR;
      $html .= '            <span class="keywords_inp">';
      $html .= '               <input type="checkbox" class="newdesign" id="ds_gvo_check" name ="ds_gvo_check"'.($check == 'y' ? ' checked="checked"' : '').' />'.CR;
      $html .= '               <label for="ds_gvo_check" class="ellipsis">Häkchen Im Warenkorb</label>'.CR;
      $html .= '               <div class="ds_gvo_text">(EU-Datenschutz-GVO)</div>'.CR;
      $html .= '            </span>'.CR;
      $html .= '         </div>'.CR;

      $html .= '         <div class="keyword_zeile telefon_check">'.CR;
      $html .= '           <span class="keyword_text right">&nbsp;</span>'.CR;
      $html .= '            <span class="keywords_inp">';
      $html .= '              <input type="checkbox" class="newdesign" id="telefon_aktiv" name ="telefon_aktiv"'.($this->params->firma['telefon_aktiv'] == 'y' ? ' checked="checked"' : '').' />'.CR;
      $html .= '              <label for="telefon_aktiv" class="ellipsis">Telefon ist Pflichtfeld</label>'.CR;
      $html .= '            </span>'.CR;
      $html .= '         </div>'.CR;
      $html .= '         <div class="clear"></div>'.CR;
      $html .= '      </div>'.CR;

      $html .= '      <div id="ds_gvo" class="edit_block">'.CR;
      $html .= '         <textarea class="ds_gvo_textarea" id="ds_gvo_text" name="ds_gvo_text" rows="10" cols="100">'.$text.'</textarea>'.CR;
      $html .= '      </div>'.CR;
      $html .= '      <div class="clear"></div>'.CR;
      $html .= '   </div>'.CR;
   }

}

$html .= '   <div class="buttonzeile">';
$html .= '      <div class="button button_left" onclick="Multibox.close();">abbrechen</div>';
$html .= '      <div class="button_ci button_center" onclick="Seiten.savePopup(\''.$seite.'\');">speichern</div>';
$html .= '   </div>';
$html .= '</div>';
