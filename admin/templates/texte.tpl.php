<?php
/*
###################################################################################
  Kanpai Classic Shopsoftware Entwicklungsstand: 05.08.2020 Version III 8.0

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

$script         = '';
$post_max       = KANPAICLASSIC\Helper::mbytesToBytes(ini_get('post_max_size'));
$file_max       = KANPAICLASSIC\Helper::mbytesToBytes(ini_get('upload_max_filesize'));
$max_size       = ($post_max < $file_max ? $post_max : $file_max);
$header_img     = '';

if (file_exists(TEMPLATE_PATH.'/images/mailheader.png')) {
   $header_img = TEMPLATE_URL.'/images/mailheader.png';
}

else if (file_exists(TEMPLATE_PATH.'/images/mailheader.jpg')) {
   $header_img = TEMPLATE_URL.'/images/mailheader.jpg';
}
?><!DOCTYPE html>
<html lang="de">
<head>
<title>Kanpai Classic <?php echo (!defined('CONF_MODULE_PORTAL') ? 'Shopsystem' : 'Shopportal'); ?> - Administration - Systemtexte</title>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
<link rel="stylesheet" href="<?php echo SHOP_URL; ?>/fonts/fontawesome/css/all.css" />
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
      <div id="texte" class="maincontent">
         <div id="titelzeile" class="titelzeile">
            <div class='txt_tit'><a class="help_kanpaiclassic" href="<?php echo HELP_LINK; ?>/einstellungen/systemtexte/" target="_blank"></a>Systemtexte & Newsletter</div>
            <div class="save_button" onclick="forms.textform.submit();">speichern</div>
            <div class="language"><?php echo $menu->langData(); ?></div>
         </div>

         <form method="post" id="textform" action="<?php echo ADMIN_URL_IDX; ?>/texte/update">
            <div class="content_box content_box_bottom">
               <div id="content_top">
                  <div class="buttons_top_right">
                     <div class="button txt_but" onclick="Texte.reset();">RESET</div>
                  </div>

                  <div class="buttons_top_left">
                     <div id="mail_header_footer" class="element_up_wrapper">
                        <span class="header_img">
                           <img id="header_img" src="<?php echo $header_img; ?>" alt="" id="mail_header_img" />
                        </span>
                        <span class="header_upload upload_button pointer" onclick="Texte.headerUplaod()"></span>
                        <span class="header_delete far fa-trash-alt" onclick="Texte.headerDelete()" style="vertical-align: top;padding-top: 16px;"></span>
                        <span class="header_txt">Logo (png)</span>

                         <div class="secondrow_settings_texte" style="width:250px;display:inline-block;padding-left:20px;">
                             <span class="footer_check" style="display:block;height:30px;">
                                 <input type="checkbox" class="newdesign" id="mail_footer_check" name="mail_footer_check" <?php echo (KANPAICLASSIC\Helper::getData('mail_footer_check', 'n') == 'y' ? 'checked="checked"' : ''); ?> onchange="Texte." />
                                 <label for="mail_footer_check" class="mail_footer_txt">Kontaktdaten anhängen</label>
                             </span>
                             <?php if (defined('CONF_MODULE_EXTENDED')) { ?>
                           <span class="attach_images_mail">
                                 <input type="checkbox" class="newdesign" id="mail_attach_images_mail" name="mail_attach_images_mail" <?php echo (KANPAICLASSIC\Helper::getData('mail_attach_images_mail', 'n') == 'y' ? 'checked="checked"' : ''); ?> onchange="Texte." />
                                 <label for="mail_attach_images_mail" class="mail_footer_txt">Artikelfotos in E-Mails anzeigen</label>
                             </span>
                             <?php } ?>
                         </div>
</div>
                  </div>
               </div>

               <?php $first = true; ?>
               <?php if ($this->data_text1 === null) { $this->_writeData(); $this->_getData(); } ?>
               <?php for ($i = 0; $i < count($this->data_text1); $i += 2) { ?>
                  <?php $data = $this->data_text1[$i]; ?>
                  <?php $class = ' bg_even'; ?>
                  <?php if ($first) { $first = false; } else { $class = ' bg_odd'; $first = true; } ?>

                  <div class="text_block <?php echo $class; ?>">
                     <div class="text_block_left">
                        <div class="text_titel txt_bez">
                           <?php echo ($data->art == 'best_best' ? '<span class="help ci_color pointer" title="Manuell verschickte E-Mail (außer es ist automatische Rechnungsstellung aktiv)"></span>' : ''); ?>
                           <?php echo ($data->art == 'anfrage_best' ? '<span class="help ci_color pointer" title="automatisch versendete E-Mail"></span>' : ''); ?>
                           <?php echo $this->getName($data->art); ?>
                        </div>

                        <div class="text_betreff_inp">
                           <?php if ($data->art == 'lastschrift' || $data->art == 'newsletter') { ?>
                           <input type="text" style="display:none;" name="<?php echo $data->art.'_betr'; ?>" id="<?php echo $data->art.'_betr'; ?>" value="" />
                           <?php } else { ?>
                           <div class="text_betreff">Betreff</div>
                           <input type="text" name="<?php echo $data->art.'_betr'; ?>" id="<?php echo $data->art.'_betr'; ?>" value="<?php echo $data->betreff; ?>" />
                           <?php } ?>
                        </div>
                        <textarea id="<?php echo $data->art; ?>" name="<?php echo $data->art; ?>"><?php echo $data->text; ?></textarea>
                     </div>

                     <?php if (isset($this->data_text1[$i + 1])) { ?>
                        <?php $data  = $this->data_text1[$i + 1]; ?>
                     <div class="text_block_right">
                        <div class="text_titel txt_bez">
                           <?php echo ($data->art == 'best_best' ? '<span class="help ci_color pointer" title="Manuell verschickte E-Mail (außer es ist automatische Rechnungsstellung aktiv)"></span>' : ''); ?>
                           <?php echo ($data->art == 'anfrage_best' ? '<span class="help ci_color pointer" title="automatisch versendete E-Mail"></span>' : ''); ?>
                           <?php echo $this->getName($data->art); ?>
                        </div>

                        <div class="text_betreff_inp">
                           <?php if ($data->art == 'lastschrift' || $data->art == 'newsletter') { ?>
                           <input type="text" style="display:none;" name="<?php echo $data->art.'_betr'; ?>" id="<?php echo $data->art.'_betr'; ?>" value="" />
                           <?php } else { ?>
                           <div class="text_betreff">Betreff</div>
                           <input type="text" name="<?php echo $data->art.'_betr'; ?>" id="<?php echo $data->art.'_betr'; ?>" value="<?php echo $data->betreff; ?>" />
                           <?php } ?>
                        </div>

                        <textarea id="<?php echo $data->art; ?>" name="<?php echo $data->art; ?>"><?php echo $data->text; ?></textarea>
                     </div>
                     <?php } ?>
                     <div class="clear"></div>
                  </div>
                  <div class=clear"></div>
               <?php } ?>
            </div>

            <div class="titelzeile titelzeile2">
                <div class='txt_tit'><a class="help_kanpaiclassic" href="<?php echo HELP_LINK; ?>/tools/gutscheine-newsletter/" target="_blank"></a>Gutschein-Newsletter</div>
             </div>

            <div class="content_box">
               <div class="news_tools">Die Newsletter versenden Sie unter <span class="strong">TOOLS | Gutschein-Newsletter.</span></div>
            <?php $first = true; ?>
            <?php if ($this->data_text2 === null) { $this->_writeData(); $this->_getData(); } ?>
            <?php for ($i = 0; $i < count($this->data_text2); $i += 2) { ?>
               <?php $data  = $this->data_text2[$i]; ?>
               <?php $class = ' bg_even'; ?>
               <?php if ($first) { $first = false; } else { $class = ' bg_odd'; $first = true; } ?>

               <div class="text_block <?php echo $class; ?>">
                  <div class="text_block_left">
                     <div class="text_titel txt_bez"><?php echo $this->getName($data->art); ?></div>

                     <div class="text_betreff_inp">
                        <div class="text_betreff">Betreff</div>
                        <input type="text" name="<?php echo $data->art.'_betr'; ?>" id="<?php echo $data->art.'_betr'; ?>" value="<?php echo $data->betreff; ?>" />
                     </div>

                     <textarea id="<?php echo $data->art; ?>" name="<?php echo $data->art; ?>"><?php echo $data->text; ?></textarea>
                  </div>

                  <?php if (isset($this->data_text2[$i + 1])) { ?>
                     <?php $data  = $this->data_text2[$i + 1]; ?>
                  <div class="text_block_right">
                     <div class="text_titel txt_bez"><?php echo $this->getName($data->art); ?></div>
                     <div class="text_betreff_inp">
                        <div class="text_betreff">Betreff</div>
                        <input type="text" name="<?php echo $data->art.'_betr'; ?>" id="<?php echo $data->art.'_betr'; ?>" value="<?php echo $data->betreff; ?>" />
                     </div>

                     <textarea id="<?php echo $data->art; ?>" name="<?php echo $data->art; ?>"><?php echo $data->text; ?></textarea>
                  </div>
                  <?php } ?>
                  <div class="clear"></div>
               </div>

               <div class=clear"></div>
               <?php } ?>
            </div>
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
<!-- <script src="<?php echo ADMIN_URL; ?>/js/jquery.minicolors.min.js"></script> -->
<?php include ADMIN_PATH.'/editor_systemtexte.inc.php'; ?>
</body>
</html>
