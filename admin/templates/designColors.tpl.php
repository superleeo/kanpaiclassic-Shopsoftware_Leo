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

header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time()));
header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
$menu           = KANPAICLASSIC\Control::getMenu();
$admin_config   = $menu->loadDesign();



$template_show = 'template_1';
if (defined('CONF_TEMPLATE_ID')) {
   $template_show = 'template_'.CONF_TEMPLATE_ID;
}

$class = 'txt_bez css_flaechen';
$is_color = false;
$template_images      = TEMPLATE_URL.'/images/';
$template_images_file = TEMPLATE_PATH.'/images/';

$template_id = 1;
if (defined('CONF_TEMPLATE_ID')) {
   $template_id = CONF_TEMPLATE_ID;
}

$help         = KANPAICLASSIC\Control::getHelp();

?><!DOCTYPE html>
<html lang="de">
<head>
<title>Kanpai Classic <?php echo (!defined('CONF_MODULE_PORTAL') ? 'Shopsystem' : 'Shopportal'); ?> - Administration - Design</title>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
<style>
<?php include_once ADMIN_PATH.'/css/'.(is_file(ADMIN_PATH.'/css/admin.css') ? 'admin.css' : 'admin_easy.css'); ?>
</style>
<link rel="stylesheet" href="<?php echo SHOP_URL; ?>/fonts/fontawesome/css/all.css" />
<link rel="stylesheet" type="text/css" href="<?php echo ADMIN_URL; ?>/css/jquery.minicolors.css" />
<style>
<?php echo $this->_getFontCSS(); ?>
</style>
</head>

<body>
<div id="page" class="admin_bg">
   <?php echo $menu->printHeader(); ?>
   <div id="menu">
      <?php echo $menu->menuData(); ?>
   </div>

   <div id="content">
      <div id="titelzeile" class="titelzeile">
         <div class='txt_tit'><a class="help_kanpaiclassic" href="https://help.kanpaiclassic.com/design/farben-schrift/" target="_blank"></a>Farben & Schrift</div>
         <div class="save_button" onClick="forms.designform.submit()">speichern</div>
      </div>

      <div id="design_farben" class="maincontent">
         <div id="content_top"></div>
         <form method="post" id="designform" action="<?php echo ADMIN_URL_IDX; ?>/designColors/save">
            <div class="design content_box content_box_bottom">
               <?php // +++++++++++++++++++++++++++ Farben / Flächen ++++++++++++++++++++++++++++++ ?>
               <div class="design_box design_box_top">
                  <div class="design_colors">
                     <div class="colors_bg">
                        <div class="css_title csstitel_flaechen txt_bez">Flächenfarben</div>

                        <div class="colors_white colors_first">
                           <div id="colgroup_menu"></div>
                        <?php for ($i = 0; $i < 2; $i++) { ?>
                           <?php $css_name = $this->colors_bg[$i]['css_name']; ?>
                           <div class="css_zeile">
                              <?php if ($this->css[$css_name]['name'] != '') { ?>
                              <div class='css-item'>
                                 <span class="fliesstext"><?php echo $this->css[$css_name]['name']; ?></span>
                                 <span class="txt_bez css_flaechen"><?php echo $this->css[$css_name]['sort']; ?></span>
                                 <input type="hidden" class="opacity" value="<?php echo $this->css[$css_name]['opacity']; ?>" name="<?php echo $this->css[$css_name]['name']; ?>_opacity" />
                                 <input type="text" class="txt_inp minicolors" data-opacity="<?php echo $this->css[$css_name]['opacity']; ?>" value="<?php echo $this->css[$css_name]['val']; ?>" name="<?php echo $css_name; ?>" title="Deckkraft: <?php echo round($this->css[$css_name]['opacity'] * 100); ?>%" />
                              </div>
                              <?php } else { ?>
                              <div class='css_item_leer'></div>
                              <?php } ?>
                           </div>
                        <?php } ?>
                        </div>

                        <div class="colors_grey">
                           <div id="colgroup_kat"></div>
                        <?php for ($i = 2; $i < 7; $i++) { ?>
                           <?php $css_name = $this->colors_bg[$i]['css_name']; ?>
                           <div class="css_zeile">
                              <?php if ($this->css[$css_name]['name'] != '') { ?>
                              <div class='css-item'>
                                 <span class="fliesstext"><?php echo $this->css[$css_name]['name']; ?></span>
                                 <span class="txt_bez css_flaechen"><?php echo $this->css[$css_name]['sort']; ?></span>
                                 <input type="hidden" class="opacity" value="<?php echo $this->css[$css_name]['opacity']; ?>" name="<?php echo $this->css[$css_name]['name']; ?>_opacity" />
                                 <input type="text" class="txt_inp minicolors" data-opacity="<?php echo $this->css[$css_name]['opacity']; ?>" value="<?php echo $this->css[$css_name]['val']; ?>" name="<?php echo $css_name; ?>" title="Deckkraft: <?php echo round($this->css[$css_name]['opacity'] * 100); ?>%" />
                              </div>
                              <?php } else { ?>
                              <div class='css_item_leer'></div>
                              <?php } ?>
                           </div>
                        <?php } ?>
                        </div>

                        <div class="colors_white">
                           <div id="colgroup_inhalt"></div>
                        <?php for ($i = 7; $i < 12; $i++) { ?>
                           <?php $css_name = $this->colors_bg[$i]['css_name']; ?>
                           <div class="css_zeile">
                              <?php if ($this->css[$css_name]['name'] != '') { ?>
                              <div class='css_item'>
                                 <span class="fliesstext"><?php echo $this->css[$css_name]['name']; ?></span>
                                 <span class="txt_bez css_flaechen"><?php echo $this->css[$css_name]['sort']; ?></span>
                                 <input type="hidden" class="opacity" value="<?php echo $this->css[$css_name]['opacity']; ?>" name="<?php echo $this->css[$css_name]['name']; ?>_opacity" />
                                 <input type="text" class="txt_inp minicolors" data-opacity="<?php echo $this->css[$css_name]['opacity']; ?>" value="<?php echo $this->css[$css_name]['val']; ?>" name="<?php echo $css_name; ?>" title="Deckkraft: <?php echo round($this->css[$css_name]['opacity'] * 100); ?>%" />
                              </div>
                              <?php } else { ?>
                              <div class='css_item_leer'></div>
                              <?php } ?>
                           </div>
                        <?php } ?>
                        </div>

                        <div class="colors_grey">
                           <div id="colgroup_footer"></div>
                        <?php for ($i = 12; $i < 14; $i++) { ?>
                           <?php $css_name = $this->colors_bg[$i]['css_name']; ?>
                           <div class="css_zeile">
                              <?php if ($this->css[$css_name]['name'] != '') { ?>
                              <div class='css-item'>
                                 <span class="fliesstext"><?php echo $this->css[$css_name]['name']; ?></span>
                                 <span class="txt_bez css_flaechen"><?php echo $this->css[$css_name]['sort']; ?></span>
                                 <input type="hidden" class="opacity" value="<?php echo $this->css[$css_name]['opacity']; ?>" name="<?php echo $this->css[$css_name]['name']; ?>_opacity" />
                                 <input type="text" class="txt_inp minicolors" data-opacity="<?php echo $this->css[$css_name]['opacity']; ?>" value="<?php echo $this->css[$css_name]['val']; ?>" name="<?php echo $css_name; ?>" title="Deckkraft: <?php echo round($this->css[$css_name]['opacity'] * 100); ?>%" />
                              </div>
                              <?php } else { ?>
                              <div class='css_item_leer'></div>
                              <?php } ?>
                           </div>
                        <?php } ?>
                        </div>
                     </div>

                     <div class="colors">
                        <div class="css_title csstitel_schrift txt_bez">Schriftfarben</div>

                        <div class="colors_white colors_first">
                        <?php for ($i = 0; $i < 2; $i++) { ?>
                           <?php $css_name = $this->colors[$i]['css_name']; ?>
                           <div class="css_zeile">
                              <?php if ($this->css[$css_name]['name'] != '') { ?>
                              <div class='css-item'>
                                 <span class="fliesstext"><?php echo $this->css[$css_name]['name']; ?></span>
                                 <span class="txt_bez css_schrift"><?php echo $this->css[$css_name]['sort']; ?></span>
                                 <input type="text" class="txt_inp minicolors" value="<?php echo $this->css[$css_name]['val']; ?>" name="<?php echo $css_name; ?>" />
                              </div>
                              <?php } else { ?>
                              <div class='css_item_leer'></div>
                              <?php } ?>
                           </div>
                        <?php } ?>
                        </div>

                        <div class="colors_grey">
                        <?php for ($i = 2; $i < 7; $i++) { ?>
                           <?php $css_name = $this->colors[$i]['css_name']; ?>
                           <div class="css_zeile">
                              <?php if ($this->css[$css_name]['name'] != '') { ?>
                              <div class='css-item'>
                                 <span class="fliesstext"><?php echo $this->css[$css_name]['name']; ?></span>
                                 <span class="txt_bez css_schrift"><?php echo $this->css[$css_name]['sort']; ?></span>
                                 <input type="text" class="txt_inp minicolors" value="<?php echo $this->css[$css_name]['val']; ?>" name="<?php echo $css_name; ?>" />
                              </div>
                              <?php } else { ?>
                              <div class='css_item_leer'></div>
                              <?php } ?>
                           </div>
                        <?php } ?>
                        </div>

                        <div class="colors_white">
                        <?php for ($i = 7; $i < 12; $i++) { ?>
                           <?php $css_name = $this->colors[$i]['css_name']; ?>
                           <div class="css_zeile">
                              <?php if ($this->css[$css_name]['name'] != '') { ?>
                              <div class='css-item'>
                                 <span class="fliesstext"><?php echo $this->css[$css_name]['name']; ?></span>
                                 <span class="txt_bez css_schrift"><?php echo $this->css[$css_name]['sort']; ?></span>
                                 <input type="text" class="txt_inp minicolors" value="<?php echo $this->css[$css_name]['val']; ?>" name="<?php echo $css_name; ?>" />
                              </div>
                              <?php } else { ?>
                              <div class='css_item_leer'></div>
                              <?php } ?>
                           </div>
                        <?php } ?>
                        </div>

                        <div class="colors_grey">
                        <?php for ($i = 12; $i < 14; $i++) { ?>
                           <?php $css_name = $this->colors[$i]['css_name']; ?>
                           <div class="css_zeile">
                              <?php if ($this->css[$css_name]['name'] != '') { ?>
                              <div class='css-item'>
                                 <span class="fliesstext"><?php echo $this->css[$css_name]['name']; ?></span>
                                 <span class="txt_bez css_schrift"><?php echo $this->css[$css_name]['sort']; ?></span>
                                 <input type="text" class="txt_inp minicolors" value="<?php echo $this->css[$css_name]['val']; ?>" name="<?php echo $css_name; ?>" />
                              </div>
                              <?php } else { ?>
                              <div class='css_item_leer'></div>
                              <?php } ?>
                           </div>
                        <?php } ?>
                        </div>
                     </div>
                  </div>

                  <div class="design_img">
                     <div class="css_title txt_bez">Farbfamilien <a id="farblink" href="https://help.kanpaiclassic.com/o34/farbfamilien/" target="_blank"></a></div>
                     <?php echo (file_exists($template_images_file."system/template1.jpg")) ? "<img src='" . $template_images."system/template1.jpg' />"  : ""; ?>
                  </div>
                  <div class="clear"></div>
               </div>

               <div class="design_box design_box_bottom">
                  <div class="design_colors">
                     <div class="colors_bg">
                        <div class="csstitel_flaechen txt_bez hidden">Flächenfarben</div>
                        <div class="colors_white colors_first">
                           <div id="colgroup_liste"></div>
                        <?php for ($i = 14; $i < 17; $i++) { ?>
                           <?php $css_name = $this->colors_bg[$i]['css_name']; ?>
                           <div class="css_zeile">
                              <?php if ($this->css[$css_name]['name'] != '') { ?>
                              <div class='css-item'>
                                 <?php if ($this->css[$css_name]['name'] == 'bg_artikelbild') { ?>
                                 <span class="help ci_color" title="Ergänzungsfarbe bei Hochformatfotos:&#10;Bei weißen Artikelfotos tragen Sie weiß ein."></span>
                                 <?php } ?>
                                 <span class="fliesstext"><?php echo $this->css[$css_name]['name']; ?></span>
                                 <span class="txt_bez css_flaechen"><?php echo $this->css[$css_name]['sort']; ?></span>
                                 <input type="hidden" class="opacity" value="<?php echo $this->css[$css_name]['opacity']; ?>" name="<?php echo $this->css[$css_name]['name']; ?>_opacity" />
                                 <input type="text" class="txt_inp minicolors" data-opacity="<?php echo $this->css[$css_name]['opacity']; ?>" value="<?php echo $this->css[$css_name]['val']; ?>" name="<?php echo $css_name; ?>" title="Deckkraft: <?php echo round($this->css[$css_name]['opacity'] * 100); ?>%" />
                              </div>
                              <?php } else { ?>
                              <div class='css_item_leer'></div>
                              <?php } ?>
                           </div>
                        <?php } ?>
                        </div>
                     </div>

                     <div class="colors">
                        <div class="css_title csstitel_schrift txt_bez hidden">Schriftfarben</div>
                        <div class="colors_white colors_first">
                        <?php for ($i = 14; $i < 17; $i++) { ?>
                           <?php $css_name = $this->colors[$i]['css_name']; ?>
                           <div class="css_zeile">
                              <?php if ($this->css[$css_name]['name'] != '') { ?>
                              <div class='css-item'>
                                 <span class="css_name"><?php echo $this->css[$css_name]['name']; ?></span>
                                 <span class="txt_bez css_schrift"><?php echo $this->css[$css_name]['sort']; ?></span>
                                 <input type="text" class="txt_inp minicolors" value="<?php echo $this->css[$css_name]['val']; ?>" name="<?php echo $css_name; ?>" />
                              </div>
                              <?php } else { ?>
                              <div class='css_item_leer'></div>
                              <?php } ?>
                           </div>
                        <?php } ?>
                        </div>
                     </div>
                  </div>

                  <div class="design_img">
                     <div class="css_title css_title txt_bez hidden">Farbfamilien <a id="farblink" href="https://colors.kanpaiclassic.com" target="_blank"></a></div>
                     <?php echo (file_exists($template_images_file."system/template2.jpg")) ? "<img src='" . $template_images."system/template2.jpg' />"  : ""; ?>
                  </div>
                  <div class="clear"></div>
               </div>
               <div class="clear"></div>
            </div>

            <?php // +++++++++++++++++++++++++++ Schrift ++++++++++++++++++++++++++++++ ?>
            <?php if ($template_id != 1) { ?>
               <?php $font1 = (isset($googlefonts[$this->json['fontfamily1']][2]) ? $googlefonts[$this->json['fontfamily1']][2] : $this->json['fontfamily1']); ?>
               <?php $fontsize1 = $this->json['fontsize1']; ?>
               <?php $font2 = (isset($googlefonts[$this->json['fontfamily2']][2]) ? $googlefonts[$this->json['fontfamily2']][2] : $this->json['fontfamily2']); ?>
               <?php $fontsize2 = $this->json['fontsize2']; ?>
               <?php $font3 = (isset($googlefonts[$this->json['fontfamily3']][2]) ? $googlefonts[$this->json['fontfamily3']][2] : $this->json['fontfamily3']); ?>
               <?php $fontsize3 = $this->json['fontsize3']; ?>
               <?php $font4 = (isset($googlefonts[$this->json['fontfamily4']][2]) ? $googlefonts[$this->json['fontfamily4']][2] : $this->json['fontfamily4']); ?>
               <?php $fontsize4 = $this->json['fontsize4']; ?>
            <div class="content_box_abstand"></div>

            <div class="titelzeile">
               <a class="help_kanpaiclassic" href="<?php echo HELP_LINK; ?>/o36/schriften/" target="_blank"></a>
               <h1 class="txt_tit">Schriften</h1>
            </div>
            <div class="design_box content_box content_box_bottom">
               <div id="zeichen">
                  <span class="zeichen_haupt">Hauptkategorien
                     <input type="text" class="txt_inp" name="zeichen_main" id="zeichen_main" value="<?php echo $this->zeichen_main; ?>" />&nbsp;Zeichen
                  </span>
                  <span class="zeichen_unter">Unterkategorien
                     <input type="text" class="txt_inp" name="zeichen_sub"  id="zeichen_sub"  value="<?php echo $this->zeichen_sub;  ?>" />
                     &nbsp;Zeichen
                  </span>
                  <span class="zeichen_zeilen">
                     <span class="zeichen_name">Artikelname</span>
                     <span class="selectbox30">
                        <select id="art_zeilen" name="art_zeilen">
                           <option value="1"<?php echo ($this->json['art_zeilen'] == 1 ? ' selected="selected"' : ''); ?>>einzeilig</option>
                           <option value="2"<?php echo ($this->json['art_zeilen'] == 2 ? ' selected="selected"' : ''); ?>>zweizeilig</option>
                        </select>
                     </span>
                  </span>
                  <div class="clear"></div>
               </div>

               <div id="fonts">
                  <div class="txt_bez fonts_titel">Schriftart</div>

                  <div class="design_fonts">
                     <span>Hauptkategorien / Titel</span><br />
                     <span class="selectbox30 fontfamily">
                        <select id="fontfamily1" name="fontfamily1" onChange="$('#fontshow1').css('font-family', $('#fontfamily1 option:selected').data('fontfamily')); $('#fontweight1').val($('#fontfamily1 option:selected').data('fontweight'));">
                           <?php echo $this->_getFontfamily($this->json['fontfamily1']); ?>
                        </select>
                     </span>
                     <span class="selectbox30 fontsize">
                        <select id="fontsize1" name="fontsize1" onChange="$('#fontshow1').css('font-size', $('#fontsize1 option:selected').val()+'px');">
                           <?php echo $this->_getFontsize($this->json['fontsize1']); ?>
                        </select>
                     </span>
                     <span id="fontshow1" class="fontshow ellipsis" style="font-family:<?php echo $font1 ?>; font-size:<?php echo $fontsize1; ?>px;">äöüßÄÖÜ Grumpy wizards make toxic brew for the evil Queen and Jack.</span>
                  </div>

                  <div class="design_fonts">
                     <span>Buttons / Menü</span><br />
                     <span class="selectbox30 fontfamily">
                        <select id="fontfamily2" name="fontfamily2" onChange="$('#fontshow2').css('font-family', $('#fontfamily2 option:selected').data('fontfamily')); $('#fontweight2').val($('#fontfamily2 option:selected').data('fontweight'));">
                           <?php echo $this->_getFontfamily($this->json['fontfamily2']); ?>
                        </select>
                     </span>
                     <span class="selectbox30 fontsize">
                        <select id="fontsize2" name="fontsize2" onChange="$('#fontshow2').css('font-size', $('#fontsize2 option:selected').val()+'px');">
                           <?php echo $this->_getFontsize($this->json['fontsize2']); ?>
                        </select>
                     </span>
<!--                        <input type="hidden" id="fontweight2" name="fontweight2" value="<?php echo $this->json['fontweight2']; ?>"> -->
                     <span id="fontshow2" class="fontshow ellipsis" style="font-family:<?php echo $font2 ?>; font-size:<?php echo $fontsize2; ?>px;">äöüßÄÖÜ Grumpy wizards make toxic brew for the evil Queen and Jack.</span>
                  </div>

                  <div class="design_fonts">
                     <span>Fliesstext</span><br />
                     <span class="selectbox30 fontfamily">
                        <select id="fontfamily3" name="fontfamily3" onChange="$('#fontshow3').css('font-family', $('#fontfamily3 option:selected').data('fontfamily')); $('#fontweight3').val($('#fontfamily3 option:selected').data('fontweight'));">
                           <?php echo $this->_getFontfamily($this->json['fontfamily3']); ?>
                        </select>
                     </span>
                     <span class="selectbox30 fontsize">
                        <select id="fontsize3" name="fontsize3" onChange="$('#fontshow3').css('font-size', $('#fontsize3 option:selected').val()+'px');">
                           <?php echo $this->_getFontsize($this->json['fontsize3']); ?>
                        </select>
                     </span>
<!--                        <input type="hidden" id="fontweight3" name="fontweight3" value="<?php echo $this->json['fontweight3']; ?>"> -->
                     <span id="fontshow3" class="fontshow ellipsis" style="font-family:<?php echo $font3 ?>; font-size:<?php echo $fontsize3; ?>px;">äöüßÄÖÜ Grumpy wizards make toxic brew for the evil Queen and Jack.</span>
                  </div>

                  <div class="design_fonts">
                     <span>Kleines</span><br />
                     <span class="selectbox30 fontfamily">
                        <select id="fontfamily4" name="fontfamily4" onChange="$('#fontshow4').css('font-family', $('#fontfamily4 option:selected').data('fontfamily')); $('#fontweight4').val($('#fontfamily4 option:selected').data('fontweight'));">
                           <?php echo $this->_getFontfamily($this->json['fontfamily4']); ?>
                        </select>
                     </span>
                     <span class="selectbox30 fontsize">
                        <select id="fontsize4" name="fontsize4" onChange="$('#fontshow4').css('font-size', $('#fontsize4 option:selected').val()+'px');">
                           <?php echo $this->_getFontsize($this->json['fontsize4']); ?>
                        </select>
                     </span>
<!--                        <input type="hidden" id="fontweight4" name="fontweight4" value="<?php echo $this->json['fontweight4']; ?>"> -->
                     <span id="fontshow4" class="fontshow ellipsis" style="font-family:<?php echo $font4 ?>; font-size:<?php echo $fontsize4; ?>px;">äöüßÄÖÜ Grumpy wizards make toxic brew for the evil Queen and Jack.</span>
                  </div>
               </div>
            </div>
            <div class="clear"></div>
            <?php } ?>
         </form>
      </div>
   </div>
   <?php $menu->footer(); ?>
</div>
<script>
var langs         = '<?php echo implode(';', $this->params->langs); ?>'; // vorhandene Sprachen - Nicht bei allen Templates notwendig
var sel_lang      = 'deu'; // gewählte Sprache - nicht bei allen Templates notwendig
var default_lang  = '<?php echo $this->params->default_lang; ?>';
var admin_url_idx = '<?php echo ADMIN_URL_IDX; ?>';
var admin_url     = '<?php echo ADMIN_URL; ?>';
var shopurl_idx   = '<?php echo SHOP_URL_IDX; ?>';
var shop_url      = '<?php echo SHOP_URL; ?>';
var template_url  = '<?php echo TEMPLATE_URL; ?>';
var max_file_size = '<?php echo max(KANPAICLASSIC\Helper::mbytesToBytes(ini_get('upload_max_filesize')), KANPAICLASSIC\Helper::mbytesToBytes(ini_get('post_max_size'))); ?>';
var editor_css    = "<?php echo TEMPLATE_URL; ?>/css/editor.css";
</script>

<script src="<?php echo SHOP_URL;  ?>/js/jquery3.min.js"></script>
<script src="<?php echo SHOP_URL;  ?>/js/jquery-ui.min.js"></script>
<script src="<?php echo ADMIN_URL; ?>/js/admin.js"></script>
<script src="<?php echo ADMIN_URL; ?>/js/jquery.minicolors.min.js"></script>
</body>
</html>
