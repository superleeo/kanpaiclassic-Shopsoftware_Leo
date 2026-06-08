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
header('Access-Control-Allow-Origin: *');
$menu         = KANPAICLASSIC\Control::getMenu();
$admin_config = $menu->loadDesign();
// $editors      = $this->getEditors($catid);
$img_path     = SHOP_PATH.'/'.CONF_PICT_PATH.'/kategorien/';
$img_url      = ($this->params->multishop ? \KANPAICLASSIC\Helper::getData('multishop_images') : SHOP_URL).'/'.CONF_PICT_PATH.'kategorien/';
$no_img       = ADMIN_URL.'/img/nopic.png';
$text         = explode('[TRENNER]', $val_desc);
$text1        = $text[0];
$text2        = (isset($text[1]) ? $text[1] : '');
?><!DOCTYPE html>
<html lang="de">
<head>
<title>Kanpai Classic <?php echo (!defined('CONF_MODULE_PORTAL') ? 'Shopsystem' : 'Shopportal'); ?> - Administration - Kategorie bearbeiten</title>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
<meta http-equiv="Access-Control-Allow-Origin" content="*" />
<meta http-equiv="Access-Control-Allow-Headers" content="X-Requested-With" />
<link rel="stylesheet" href="<?php echo SHOP_URL; ?>/fonts/fontawesome/css/all.css" />
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
var multishop     = "<?php echo $this->params->multishop; ?>";
</script>
<script src="<?php echo SHOP_URL;  ?>/js/jquery3.min.js"></script>
<script src="<?php echo ADMIN_URL; ?>/js/fileinput/plugins/sortable.min.js"></script>
<script src="<?php echo ADMIN_URL; ?>/js/fileinput/fileinput.min.js"></script>
<script src="<?php echo ADMIN_URL; ?>/js/fileinput/locales/de.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo ADMIN_URL; ?>/css/jquery.minicolors.css" />
<script src="<?php echo ADMIN_URL; ?>/js/jquery.minicolors.min.js"></script>
<style>
<?php include_once ADMIN_PATH.'/css/'.(is_file(ADMIN_PATH.'/css/admin.css') ? 'admin.css' : 'admin_easy.css'); ?>
</style>
<link rel="stylesheet" href="<?php echo SHOP_URL; ?>/fonts/fontawesome/css/all.css" />
</head>

<body>
<div id="page" class="admin_bg">
   <?php echo $menu->printHeader(); ?>
   <div id="menu">
      <?php echo $menu->menuData(); ?>
   </div>

   <div id="content">
      <div id="titelzeile" class="titelzeile">
         <div class='txt_tit'><a class="help_kanpaiclassic" href="<?php echo HELP_LINK; ?>/kategorien/" target="_blank"></a>Kategorie bearbeiten</div>
         <div class="save_button" onclick="Kategorie.detailSave(<?php echo $cat_id; ?>, true);"><?php echo ($cat_id != 0 ? 'speichern' : 'speichern'); ?></div>
         <div class="language">
            <?php echo $menu->langData(); ?>
            <div class="button_cancel txt_but">
               <a href="<?php echo ADMIN_URL_IDX.'/kategorien'; ?>">abbrechen</a>
            </div>
         </div>
      </div>

      <div id="category_details" class="maincontent">
         <form id="cat_details_form">
            <input type='hidden' name="oldsort"    id="oldsort"    value='<?php echo $sortierung; ?>' />
            <input type='hidden' name="cat_id"     id="cat_id"     value='<?php echo $cat_id; ?>' />
            <input type='hidden' name="oldparent"  id="oldparent"  value='<?php echo $parent; ?>' />
            <input type="hidden" name="net_id"     id="net_id"     value="<?php // echo $net_id; ?>" />
            <input type="hidden" name="net_old_id" id="net_old_id" value="<?php // echo $net_id; ?>" />
            <input type="hidden" name="lang"       id="lang"       value="<?php echo $lang; ?>" />

            <div class="content_box content_box_bottom">
               <div id="content_top"></div>

               <div class="editor_left">
                  <div class="input_line">
                     <span class="txt_tit pos1">Kategoriename</span>
                     <span class="pos_name"><input type="text" id="name" name="name" class="txt_inp txt_tit" value="<?php echo $val_name; ?>" placeholder="Bezeichnung" /></span>
                  </div>
                  <div class="input_line">
                     <span class="pos1 ellipsis">Position / Ebene</span>
                     <span class="selectbox30 pos3">
                        <select name="newparent" id="newparent">
                           <?php echo $this->catList($cat_id); ?>
                        </select>
                     </span>
                     <span class="pos4 help ci_color" title="Unter dieser Kategorie wird angelegt."></span>
                  </div>
                  <div class="input_line">
                     <span class="pos1 ellipsis">Sortierung</span>
                     <span class="pos_sort"><input type='text' id='newsort' name='newsort' value='<?php echo $sortierung; ?>' /></span>
                     <span class="pos4 help ci_color" title="Reelle Position innerhalb der Ebene"></span>
                  </div>
               </div>

               <div class="editor_right">
                  <div class="keywords">
                     <h3>Titel-Tag</h3>
                     <span><input type="text" name="titletag" id="titletag" value="<?php echo $val_titletag; ?>" placeholder="<?php echo TEXT_KEYT; ?>" /></span>
                  </div>
                  <div class="keywords">
                     <h3>Description-Tag</h3>
                     <span><input type="text" name="description" id="description" value="<?php echo $val_description; ?>" placeholder="<?php echo TEXT_DESR; ?>" /></span>
                  </div>
                  <div class="keywords">
                     <h3>Keywords</h3>
                     <span><input type="text" name="keywords" id="keywords" value="<?php echo $val_keywords; ?>" placeholder="<?php echo TEXT_KEYW; ?>" /></span>
                  </div>
               </div>
               <div class="clear"></div>

               <div class="editor_left pos_bottom">
                  <div class="input_line">
                     <?php if ($cat_id != 0) { ?>
                     <input type="checkbox" class="newdesign" id="show_text" name="show_text"<?php echo ($show_text == 'y' ? ' checked="checked"' : ''); ?> onclick="$('.hide_me').toggle();" />
                     <label for="show_text">Kategorie-Bild/Text</label>
                     <?php } else { ?>
                     <input type="checkbox" class="newdesign" id="show_text" name="show_text" disabled="disabled" />
                     <label for="show_text">Kategorie-Bild/Text (nach erstellen verfügbar)</label>
                     <?php } ?>
                  </div>
                  <div class="input_line">
                     <input type="checkbox" class="newdesign" id="hide_articles" name="hide_articles"<?php echo ($hide_articles == 'y' ? ' checked="checked"' : ''); ?> />
                     <label for="hide_articles" class="">Artikel ausblenden</label>
                  </div>

                  <?php if (false && defined('CONF_MODULE_FILTER')) { ?>
                  <div id="kategoriefilter" class="input_line">
                     <input type="checkbox" class="newdesign" id="filter_active" name="filter_active"<?php echo ($filter_active == 'y' ? ' checked="checked"' : ''); ?> onchange="($(this).prop('checked') ? $('#filter_edit').removeClass('hidden'): $('#filter_edit').addClass('hidden'));" />
                     <label for="filter_active">Filter aktivieren</label>
                     <span id="filter_edit" class="<?php echo ($filter_active !== 'y' ? 'hidden ' : ''); ?>filter_popup pointer fas fa-pencil-alt" onclick="Kategorie.katfilterPopup(<?php echo $cat_id; ?>);"></span>
                  </div>
                  <?php } ?>
                  <?php if (defined('CONF_MODULE_MIXER_KATEGORIE') && $cat_id > 0) { ?>
                  <div class="input_line">
                     <input type="checkbox" class="newdesign" id="mixer_check" name="mixer_check"<?php echo ($mixer_check == 'y' ? ' checked="checked"' : ''); ?> onchange="($(this).prop('checked') ? $('#cat_mixer_hide').show() : $('#cat_mixer_hide').hide());" />
                     <label for="mixer_check" >Mixer</label>
                  </div>
                  <?php } ?>
               </div>

               <div class="clear"></div>
             </div>
            <div class="clear"></div>

            <div class="hide_me"<?php echo ($show_text != 'y' ? ' style="display:none;"' : ''); ?>>
               <div class="content_box_abstand"></div>
               <div class="titelzeile">
               <div class="txt_tit"><a class="help_kanpaiclassic" href="<?php echo HELP_LINK; ?>/o40/kategorie-bilder-text/" target="_blank"></a>Kategorie Bild/Text</div>
                  <div class="save_button" onclick="Kategorie.detailSave(<?php echo $cat_id; ?>, false);"><?php echo ($cat_id != 0 ? 'speichern' : 'speichern'); ?></div>              
               </div>
               <div class="box_tools content_box content_box_bottom">
                  <div class="image_blocks">
                     <div id="popup_bild2" class="livedesigner_uploader" data-modul_id="'.$modul_id.'">
                        <div id="title_block">
                           <div id="title_left">
                              <span class="pos1 ellipsis">Kategoriebilder hochladen</span>
                              <div class="title_line">
                                 <div class="button_ci pointer" onclick="$('#more_images').click();">
                                    <span class="far fa-folder-open"></span> auswählen
                                 </div>
                              </div>
                           </div>

                           <div id="title_right">
                              <div class="title_line">
                                 <div class="pos11"><b>Bilder je Reihe</b></div>
                                 <div class="pos12">
                                    <input type="radio" class="newdesign" id="bild_mode0" name="bild_mode" value="10"<?php echo ($options->mode === 10 ? ' checked="checked"' : ''); ?>>
                                    <label for="bild_mode0"></label> bildschirmbreit
                                    <span class="help ci_color" title="Nur wenn Kategorien auf horizontal eingestellt sind"></span>
                                 </div>
                                 <div class="pos13">
                                    <input type="radio" class="newdesign" id="bild_mode1" name="bild_mode" value="1"<?php echo ($options->mode === 1 ? ' checked="checked"' : ''); ?>>
                                    <label for="bild_mode1"></label> 1
                                 </div>
                                 <div class="pos14">
                                    <input type="radio" class="newdesign" id="bild_mode2" name="bild_mode" value="2"<?php echo ($options->mode === 2 ? ' checked="checked"' : ''); ?>>
                                    <label for="bild_mode2"></label> 2
                                 </div>
                                 <div class="pos15">
                                    <input type="radio" class="newdesign" id="bild_mode3" name="bild_mode" value="3"<?php echo ($options->mode === 3 ? ' checked="checked"' : ''); ?>>
                                    <label for="bild_mode3"></label> 3
                                 </div>
                                 <div class="pos16">
                                    <input type="radio" class="newdesign" id="bild_mode4" name="bild_mode" value="4"<?php echo ($options->mode === 4 ? ' checked="checked"' : ''); ?>>
                                    <label for="bild_mode4"></label> 4
                                 </div>
                                 <div class="pos17">
                                    <input type="radio" class="newdesign" id="bild_mode5" name="bild_mode" value="5"<?php echo ($options->mode === 5 ? ' checked="checked"' : ''); ?>>
                                    <label for="bild_mode5"></label> 5
                                 </div>
                                 <div class="clear"></div>
                              </div>

                              <div class="title_line">
                                 <div class="pos11">&nbsp;</div>
                                 <div class="pos10">
                                    <input type="checkbox" class="newdesign" id="bild_zuschneiden" name="bild_zuschneiden"<?php echo ($options->zuschneiden === 'y' ? ' checked="checked"' : ''); ?>>
                                    <label for="bild_zuschneiden"></label> automatisch zuschneiden
                                 </div>
                                 <div class="clear"></div>
                              </div>
                           </div>
                           <div class="clear"></div>
                        </div>

                        <div id="file_uploader">
                           <?php echo $this->getImages($cat_id); ?>
                        </div>
                     </div>

                     <div class="editor_left">
                     <textarea class="editorarea2" id="desc1" name="desc1"><?php echo $text1; ?></textarea>
                  </div>
                  <div class="editor_right">
                     <textarea class="editorarea2" id="desc2" name="desc2"><?php echo $text2; ?></textarea>
                  </div>
                  <div class="clear"></div>
               </div>
            </div>
            </div>

            <?php // Modul Kategorie-Mixer ?>
            <?php if (defined('CONF_MODULE_MIXER_KATEGORIE') && $cat_id > 0) { ?>
            <?php $images_obj = $this->loadImages($cat_id, 'deu'); ?>
            <div id="cat_mixer"></div>

            <div id="cat_mixer_hide"<?php echo ($mixer_check != 'y' ? ' style="display:none;"' : ''); ?>>
               <div class="content_box_abstand"></div>
               <div class="titelzeile">
                  <div class="txt_tit"><a class="help_kanpaiclassic" href="<?php echo HELP_LINK; ?>/o45/kategorie-mixer/" target="_blank"></a>Mixer</div>
               </div>  
          
               <div class="box_tools content_box content_box_bottom">
                  <div class="mixer_images editor_left">
                     <div class="image_block">
                        <div class="img_m_title">Titelbild links</div>
                        <div class="image_block_inner">
                           <div class="img_title txt_bez">1.</div>
                           <div class="upload upload_button pointer" title="Bild laden" onclick="Kategorie.uploadImg(1, <?php echo $cat_id; ?>, 'mixer1');"></div>
                           <div class="delete far fa-trash-alt" title="Bild löschem" onclick="Kategorie.deleteImg('mixer1', <?php echo $cat_id; ?> );"></div>
                           <div class="image img78" id="img_mixer1"><img id="mixer1"  src="<?php echo ($images_obj->mixer1 != '' ? $img_url.$images_obj->mixer1.'_tn.jpg' : $no_img); ?>" alt="" /></div>
                           <input type="hidden" id="image_mixer1" name="image_mixer1" value="<?php echo $images_obj->mixer1; ?>" />
                        </div>
                     </div>

                     <div class="image_block">
                        <div class="img_m_title">Titelbild rechts</div>
                        <div class="image_block_inner">
                           <div class="img_title txt_bez">2.</div>
                           <div class="upload upload_button pointer" title="Bild laden" onclick="Kategorie.uploadImg(2, <?php echo $cat_id; ?>, 'mixer2');"></div>
                           <div class="delete far fa-trash-alt" title="Bild löschen" onclick="Kategorie.deleteImg('mixer2', <?php echo $cat_id; ?>);"></div>
                           <div class="image img78" id="img_mixer2"><img id="mixer2" src="<?php echo ($images_obj->mixer2 != '' ? $img_url.$images_obj->mixer2.'_tn.jpg' : $no_img); ?>" alt="" /></div>
                           <input type="hidden" id="image_mixer2" name="image_mixer2" value="<?php echo $images_obj->mixer2; ?>" />
                        </div>
                     </div>

                     <div class="image_block">
                        <div class="img_m_title">Artikelbild im WK</div>
                        <div class="image_block_inner">
                           <div class="img_title txt_bez">3.</div>
                           <div class="upload upload_button pointer" title="Bild laden" onclick="Kategorie.uploadImg(3, <?php echo $cat_id; ?>, 'mixer3');"></div>
                           <div class="delete far fa-trash-alt" title="Bild löschem" onclick="Kategorie.deleteImg('mixer3', <?php echo $cat_id; ?>);"></div>
                           <div class="image img78" id="img_mixer3"><img id="mixer3"  src="<?php echo ($images_obj->mixer3 != '' ? $img_url.$images_obj->mixer3.'_tn.jpg' : $no_img); ?>" alt="" /></div>
                           <input type="hidden" id="image_mixer3" name="image_mixer3" value="<?php echo $images_obj->mixer3; ?>" />
                        </div>
                     </div>
                     <div class="clear"></div>
                  </div>

                  <div class="editor_right">
                     <div id="mixer_gewicht">
                        <input type="checkbox" class="newdesign" id="gewicht_check" name="gewicht_check"<?php echo ($gewicht_check == 'y' ? ' checked="checked"' : ''); ?> />&nbsp
                        <label for="gewicht_check">Gesamtgewicht</label>
                        <input type="text" id="mixer_gewicht" name="mixer_gewicht" value="<?php echo number_format($mixer_gewicht, 0, '', ''); ?>" />&nbsp&nbsp

                        <input type="radio" class="newdesign" id="mixer_einheit_g" value="g" name="mixer_einheit"<?php echo (!isset($mixer_einheit_g) || $mixer_einheit_g == 'g' ? ' checked="checked"' : ''); ?>>
                        <label for="mixer_einheit_g">g</label>&nbsp;&nbsp
                        <input type="radio" class="newdesign" id="mixer_einheit_kg" value="kg" name="mixer_einheit"<?php echo (isset($mixer_einheit_g) && $mixer_einheit_g == 'kg' ? ' checked="checked"' : ''); ?>>
                        <label for="mixer_einheit_kg">Kg</label>&nbsp;&nbsp;
                        <input type="radio" class="newdesign" id="mixer_einheit_ml" value="ml" name="mixer_einheit"<?php echo (isset($mixer_einheit_g) && $mixer_einheit_g == 'ml' ? ' checked="checked"' : ''); ?>>
                        <label for="mixer_einheit_ml">ml</label>&nbsp;&nbsp;
                        <input type="radio" class="newdesign" id="mixer_einheit_l" value="l" name="mixer_einheit"<?php echo (isset($mixer_einheit_g) && $mixer_einheit_g == 'l' ? ' checked="checked"' : ''); ?>>
                        <label for="mixer_einheit_l">l</label>
                     </div>
                     <div class="clear"></div>

                     <div id="mixer_grundeinheit">
                        <input type="checkbox" class="newdesign" id="naehrwerte_check" name="naehrwerte_check"<?php echo ($naehrwerte_check == 'y' ? ' checked="checked"' : ''); ?> />&nbsp;
                        <label for="naehrwerte_check">Grundeinheit und Nährwert</label>
                     </div>
                  </div>
                  <div class="clear"></div>
               </div>
               <div class="clear"></div>
            </div>
            <?php } ?>
         </form>
      </div>
   </div>
   <?php $menu->footer(); ?>
</div>


<script src="<?php echo SHOP_URL;  ?>/js/jquery-ui.min.js"></script>
<script src="<?php echo ADMIN_URL; ?>/js/admin.js"></script>
<!-- <script src="<?php echo ADMIN_URL; ?>/js/jquery.minicolors.min.js"></script> -->
<?php $editor_mode = 'categories'; ?>
<?php include 'editor_article_cat.inc.php'; ?>
</body>
</html>
