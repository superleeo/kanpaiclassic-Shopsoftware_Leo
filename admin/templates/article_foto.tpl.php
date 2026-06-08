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

if (!defined('OBADJA')) {
   die ("This file cannot run outside the Obadja&reg; Shopsystem");
}
$out  = '   <div id="pictform">Pictform</div>';
$out .= "   <div id='lang'>$this->langdataEdit</div>\n";
$out .= "   <div id='content'>\n";
$out .= "      <div id='titelzeile'>\n";
$out .= '         <a class="help_kanpaiclassic" href="'.HELP_LINK.'/kapitel03.html" target="_blank" alt=""><img src="'.ADMIN_URL.'/img/help3.png" /></a>';
$out .= "         <h1 class='txt_tit'>" . $this->text->get('art_edit', 'titel', 'deu') . "</h1>\n";
$out .= '         <input type="hidden" id="haendler_id" value="0" />';
$out .= "         <div class='right'>\n";
$out .= "            <div id='back' class='button-grau txt_but' onclick='Obadja.articleEditStop();'>" . $this->text->get('button', 'zurueck', 'deu') . "</div>\n";
$out .= "            <div id='duplicate'  class='button-grau txt_but' onclick='Obadja.articleDuplicate($parent);'>" . $this->text->get('button', 'duplikat', 'deu') . "</div>\n";
$out .= "            <div id='articleSaveList' class='button-gruen txt_but' onclick='Obadja.articleSave($parent);'>" . $this->text->get('button', 'speichern', 'deu') . "</div>\n";
$out .= "         </div>\n";
$out .= "      </div>\n";
$out .= '      <div class="cat-steuern">';
$out .= "         <p class='txt_bez'>" . $this->text->get('art_edit', 'kategorie', 'deu') . "</p>\n";
$out .= "         <div class='categories'><select name='category' id='category' onchange='Obadja.parentChange();'>$catList</select></div>\n";
$out .= "         <div class='clear'></div>\n";
$out .= "         <p class='txt_bez'>in 2. Kategorie verlinken</p>\n";
$out .= "         <div class='categories'><select name='category2' id='category2' onchange='Obadja.parentChange();'>$catList2</select></div>\n";
$out .=           $this->steuer;
$out .= "      </div>\n";

$out .= "      <div id='art-edit-title'>\n";
$out .= "         <div class='art-artnr-title txt_bez'>" . $this->text->get('art_edit', 'tit_artnr', 'deu') . "</div>\n";
$out .= "         <div class='art-name-title txt_bez'>" . $this->text->get('art_edit', 'tit_bez', 'deu') . "</div>\n";
$out .= "         <div class='art-merk1-title txt_bez'>Größe</div>\n";
$out .= "         <div class='art-wert1-title txt_bez' style='visibility: hidden;'>" . $this->text->get('art_edit', 'tit_wert', 'deu') . "1</div>\n";
$out .= "         <div class='art-merk2-title txt_bez'>Pixel</div>\n";
$out .= "         <div class='art-wert2-title txt_bez' style='visibility: hidden;'>" . $this->text->get('art_edit', 'tit_wert', 'deu') . "2</div>\n";
$out .= "         <div class='art-netto-title txt_bez'>" . $this->text->get('art_edit', 'tit_netto', 'deu') . "</div>\n";
$out .= "         <div class='art-angebot-title txt_bez'>" . $this->text->get('art_edit', 'tit_angeb', 'deu') . "</div>\n";
$out .= "         <div class='art-brutto-title txt_bez'>" . $this->text->get('art_edit', 'tit_brutto', 'deu') . "</div>\n";
$out .= "         <div class='art-menge-title txt_bez'>" . $this->text->get('art_edit', 'tit_menge', 'deu') . "</div>\n";
$out .= "      </div>\n";
$out .=        $details;

$out .= '<div style="display:none">';
$out .= $this->getVersand();
$out .= '<input type="hidden" id="staffelung_val" name="staffelung_val" value="" />';
$out .= $this->getGeNetto($parent);
$out .= '<input type="radio" onchange="document.getElementById(\'grundeinheit\').value = this.value; Obadja.parentChange();" name="grundeinheit" value="Kg"'   . ($this->grundeinheit == "Kg" ? " checked='checked'" : '')   .' />'."\n";
$out .= '<input type="radio" onchange="document.getElementById(\'grundeinheit\').value = this.value; Obadja.parentChange();" name="grundeinheit" value="100g"' . ($this->grundeinheit == "100g" ? " checked='checked'" : '') .' />'."\n";
$out .= '<input type="radio" onchange="document.getElementById(\'grundeinheit\').value = this.value; Obadja.parentChange();" name="grundeinheit" value="10g"'  . ($this->grundeinheit == "10g" ? " checked='checked'" : '')  .' />'."\n";
$out .= '<input type="radio" onchange="document.getElementById(\'grundeinheit\').value = this.value; Obadja.parentChange();" name="grundeinheit" value="g"'    . ($this->grundeinheit == "g" ? " checked='checked'" : '')    .' />'."\n";

$out .= '<input type="radio" onchange="document.getElementById(\'grundeinheit\').value = this.value; Obadja.parentChange();" name="grundeinheit" value="liter"' . ($this->grundeinheit == "liter" ? " checked='checked'" : '') .' />'."\n";
$out .= '<input type="radio" onchange="document.getElementById(\'grundeinheit\').value = this.value; Obadja.parentChange();" name="grundeinheit" value="100ml"' . ($this->grundeinheit == "100ml" ? " checked='checked'" : '') .' />'."\n";
$out .= '<input type="radio" onchange="document.getElementById(\'grundeinheit\').value = this.value; Obadja.parentChange();" name="grundeinheit" value="10ml"'  . ($this->grundeinheit == "10ml" ? " checked='checked'" : '')  .' />'."\n";
$out .= '<input type="radio" onchange="document.getElementById(\'grundeinheit\').value = this.value; Obadja.parentChange();" name="grundeinheit" value="ml"'    . ($this->grundeinheit == "ml" ? " checked='checked'" : '')    .' />'."\n";

$out .= '<input type="radio" onchange="document.getElementById(\'grundeinheit\').value = this.value; Obadja.parentChange();" name="grundeinheit" value="m"'  . ($this->grundeinheit == "m" ? " checked='checked'" : '')  .' />'."\n";
$out .= '<input type="radio" onchange="document.getElementById(\'grundeinheit\').value = this.value; Obadja.parentChange();" name="grundeinheit" value="dm"' . ($this->grundeinheit == "dm" ? " checked='checked'" : '') .' />'."\n";
$out .= '<input type="radio" onchange="document.getElementById(\'grundeinheit\').value = this.value; Obadja.parentChange();" name="grundeinheit" value="cm"' . ($this->grundeinheit == "cm" ? " checked='checked'" : '') .' />'."\n";
$out .= '<input type="radio" onchange="document.getElementById(\'grundeinheit\').value = this.value; Obadja.parentChange();" name="grundeinheit" value="mm"' . ($this->grundeinheit == "mm" ? " checked='checked'" : '') .' />'."\n";

$out .= '<input type="radio" onchange="document.getElementById(\'grundeinheit\').value = this.value; Obadja.parentChange();" name="grundeinheit" value="m2"'  . ($this->grundeinheit == "m2" ? " checked='checked'" : '')  .' />'."\n";
$out .= '<input type="radio" onchange="document.getElementById(\'grundeinheit\').value = this.value; Obadja.parentChange();" name="grundeinheit" value="dm2"' . ($this->grundeinheit == "dm2" ? " checked='checked'" : '') .' />'."\n";
$out .= '<input type="radio" onchange="document.getElementById(\'grundeinheit\').value = this.value; Obadja.parentChange();" name="grundeinheit" value="cm2"' . ($this->grundeinheit == "cm2" ? " checked='checked'" : '') .' />'."\n";
$out .= '<input type="radio" onchange="document.getElementById(\'grundeinheit\').value = this.value; Obadja.parentChange();" name="grundeinheit" value="mm2"' . ($this->grundeinheit == "mm2" ? " checked='checked'" : '') .' />'."\n";

$out .= '<input type="hidden" name="grundeinheit" id="grundeinheit" value="'.$this->grundeinheit.'" />'."\n";
$out .= "</div>\n";

$out .= '      <div style="border-top:1px dotted #808080; margin:24px -15px; padding:0 15px; position:relative;"></div>';
// Bilder und Editoren
$out .= "      <div id='editoren'>\n";
// Editoren anzeigen, Anzahl abhängig von Anzahl Sprachen
$out .= "         <div id='edit-left'>";
foreach ($this->params->langs as $lang) {
   $hidden = '';
   if ($lang != $this->params->selected_lang) {
      $hidden = ' style="display:none;"';
   }
   $out .= "            <div class='editor_div' id='editor_div_$lang'$hidden>\n";
   $out .= "               <textarea class='editorarea' id='editor_$lang' name='editor_$lang' cols='598' rows='600' onchange='Obadja.parentChange();'>".$this->editors[$lang]."</textarea>\n";
   $out .= "            </div>\n";
}
$out .= "         </div>\n";
$out .= "         <div id='edit-right'>";

// Bilder anzeigen
//$this->getPics();
$bild = '';
$pos_links = true;

// Bilder ausgeben
$image = $this->pict[1];

if ($image == 'nopic.png' || $image == '') {
   $image = ADMIN_URL.'/img/nopic.png';
   $image_tn = $image;
   $image_td = $image;
}
else if (substr($image, 0, 7) == 'http://' || substr($image, 0, 8) == 'https://') {
   $image_tn = $image.'_tn.jpg';
   $image_td = $image.'_td.jpg';
   $image    = $image.'.jpg';
}
else {
   $image_tn = SHOP_URL.'/'.CONF_PICT_PATH.$image.'_tn.jpg';
   $image_td = SHOP_URL.'/'.CONF_PICT_PATH.$image.'_td.jpg';
   $image    = SHOP_URL.'/'.CONF_PICT_PATH.$image.'.jpg';
}

$bg = ADMIN_URL.'/img/nopictitel.jpg';
$thumb = $image_tn;
$bild  = '               <a id="bild_a_1" href="' . $image.'" class="showpict">';
$bild .= '                  <img id="bild_img_1" src="'.$thumb.'" alt="" />';
$bild .= '               </a>'."\n";
$out .= '            <div class="startbild" style="background-image:url('.$bg.'); background-repeat:no-repeat;">';
$out .= '               <div class="art-bild_start" id="bild_1">' . $bild . '</div>';
$out .= '               <input type="hidden" name="pict_1" id="pict_1" value="' . $image . '" />';
//$out .= '               <div class="pict_up_start" id="bild_up_'.$i.'" ><a class="pic_upload" href="#form__'.$i.'"></a></div>';
//$out .= '               <div class="pict_del_start" id="bild_del_'.$i.'" onclick="Obadja.pictDel('.$i.')"></div>';
//$out .= '            </div>'."\n";

for ($i = 2; $i <= 11; $i++) {
   $out .= '               <input type="hidden" name="pict_' . $i . '" id="pict_' . $i . '" value="' . $this->pict[$i] . '" />';
}
$out .= '            </div>'."\n";


$out .= '         </div>'."\n";
$out .= '      </div>'."\n";
$out .= "      <div class='clear'></div>\n";


$out .= '         <div style="position:relative; height:20px; line-height:20px; top:10px;">'."\n";
$out .= '            <a class="help3a" href="'.HELP_LINK.'/o1/widerruf-mustertexte/" target="_blank" alt=""><img src="'.ADMIN_URL.'/img/help3.png" /></a>';
$out .= '            <span class="txt_bez" style="padding:0 20px 0 10px;";>Widerrufsbelehrung</span>'."\n";
$out .= '            <span style="display:inline-block; width:80px;"><input type="radio" style="display: inline-block; position: relative; top: 2px;" name="widerruf" value="1"'.($this->widerruf == 1 ? ' checked="checked"' : '').'> Standard1</span>'."\n";
$out .= '            <span style="display:inline-block; width:80px;"><input type="radio" style="display: inline-block; position: relative; top: 2px;" name="widerruf" value="2"'.($this->widerruf == 2 ? ' checked="checked"' : '').'> Standard2</span>'."\n";
$out .= '            <span style="display:inline-block; width:80px;"><input type="radio" style="display: inline-block; position: relative; top: 2px;" name="widerruf" value="3"'.($this->widerruf == 3 ? ' checked="checked"' : '').'> Spedition</span>'."\n";
$out .= '            <span style="display:inline-block; width:100px;"><input type="radio" style="display: inline-block; position: relative; top: 2px;" name="widerruf" value="4"'.($this->widerruf == 4 ? ' checked="checked"' : '').'> Dienstleistung</span>'."\n";
$out .= '         </div>'."\n";




$out .= '   </div>'."\n";

$out .= '      <div id="google" '.($this->params->firma['schnittstellen'] == 'n' ? ' style="display:none"' : '').'>';
$out .=           $this->_getGoogle();
$out .= '      </div>';


if ($this->params->firma['schnittstellen'] == 'n') {
   $out .= '      <br /><br />';
}
$out .= "         <div style='position:relative; width:90px; float:right;' class='button_unten'>\n";
//$out .= "            <div class='button-grau txt_but' onclick='Obadja.articleEditStop();'>" . $this->text->get('button', 'zurueck', 'deu') . "</div>\n";
$out .= "            <div id='articleSaveListU' class='button-gruen txt_but' onclick='Obadja.articleSave($parent);'>" . $this->text->get('button', 'speichern', 'deu') . "</div>\n";
$out .= "         </div>\n";
$out .= "         <div class='clear'></div>\n";
$out .= "      </div>\n";


if ($this->params->firma['ebay_api'] == 'y') {
   $out .= '<div id="ebay" '.($this->params->firma['ebay_api'] == 'n' ? 'style="display:none"' : '').'>';
   $out .= $this->ebay->printEbayDetails($this->ebay_data);
   $out .= '</div>';
//   $out .= '<br /><br />';
   $out .= "         <div style='position:relative; width:90px; float:right;' class='button_unten'>\n";
   $out .= "            <div class='button-gruen txt_but' onclick='Obadja.articleEbaySave($parent);'>" . $this->text->get('button', 'speichern', 'deu') . "</div>\n";
   $out .= "         </div>\n";
   $out .= "         <div class='clear'></div>\n";
   $out .= "      </div>\n";
}
$out .= "   </div>\n";

// Form für Fileupload ()
$out .= '<div style="display:none;">';
$out .= '   <div class="fileupload_form" id="fileupload_form">'."\n";
$out .= '      <div class="file-box-outer">'."\n";
$out .= '         <form method="post" id="fileUpload" action="'.ADMIN_URL_IDX.'/ajax/artikel/fileupload" enctype="multipart/form-data" target="fileuploadframe">'."\n";
$out .= '            <h1 class="txt_tit">Datei-Upload</h1>'."\n";
$out .= '            <input type="file" class="txt_inp" name="file" value="" ondblclick="Obadja.fileUpload(this);" />'."\n";
$out .= '            <input type="hidden" name="id" id="fileupload_id" value="" />';
$out .= '            <div class="file-box-button">'."\n";
$out .= "               <div class='button-gruen txt_but file-box' onclick='Obadja.fileUpload();'>Hochladen</div>"."\n";
$out .= '               <div class="button-rot txt_but file-box" onclick="$.fancybox.close();">Abbrechen</div>'."\n";
$out .= '            </div>'."\n";
$out .= '         </form>'."\n";
$out .= '      </div>'."\n";
$out .= '   </div>'."\n";
$out .= '</div>';
// Versteckt, nur um Antwort auf Bild-Upload zu empfangen
$out .= '<div><iframe name="uploadframe" id="uploadframe" style="width:0; height:0; display:none;"></iframe> </div>'."\n";
$out .= '<div><iframe name="fileuploadframe" id="fileuploadframe" style="width:0; height:0; display:none;"></iframe> </div>'."\n";
?>