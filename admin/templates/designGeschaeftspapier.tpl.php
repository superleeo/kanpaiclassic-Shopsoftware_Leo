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
$images_path_h  = TEMPLATE_PATH.'/images/rechnungskopf_'.$this->params->selected_lang.'.jpg';
$images_url_h   = TEMPLATE_URL.'/images/rechnungskopf_'.$this->params->selected_lang.'.jpg';
$images_path_f  = TEMPLATE_PATH.'/images/rechnungsfuss_'.$this->params->selected_lang.'.jpg';
$images_url_f   = TEMPLATE_URL.'/images/rechnungsfuss_'.$this->params->selected_lang.'.jpg';
$no_img         = ADMIN_URL.'/img/nopic.png';
?><!DOCTYPE html>
<html lang="de">
<head>
<title>Kanpai Classic <?php echo (!defined('CONF_MODULE_PORTAL') ? 'Shopsystem' : 'Shopportal'); ?> - Administration - Design</title>
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
      <div id="titelzeile" class="titelzeile">
         <div class='txt_tit'><a class="help_kanpaiclassic" href="<?php echo HELP_LINK; ?>/design/geschaeftspapier/" target="_blank"></a>Geschäftspapier</div>
         <div class="save_button" onclick="Design.papierSave();">speichern</div>
         <div class="language"><?php echo $menu->langData(); ?></div>
      </div>

      <div id="design_papier" class="maincontent content_box content_box_bottom">
         <div id="content_top"></div>

         <div class="papier_box papierbox_top">
            <div class="box_center">
               <a href="<?php echo HELP_LINK; ?>/design/geschaeftspapier/" target="_blank">
                  <span class="download_button pointer"></span> Vorlage downloaden
               </a>
               <div class="rechnung_nr">
                  Rechnungs-Nr.:&nbsp;&nbsp;<input class="txt_inp inp_100_right" type="text" name="rechnung" id="rechnung" onKeyUp="this.value = Royalart.checkZahl(this.value);" value="<?php echo $this->rechnung; ?>" />
               </div>
               <div class="clear"></div>
            </div>
         </div>

         <div class="papier_box papierbox_middle">
            <div class="box_left">
               <div class="txt_bez titel">Rechnungskopf
                  <div class="upload upload_button pointer" onclick="Design.papierUpload('rechnungskopf', 'papier_header');"></div>
                  <div class="delete pointer far fa-trash-alt" onclick="Design.papierDelete('rechnungskopf', 'papier_header');"></div>
               </div>
               <div class="zeile2">jpg&nbsp;2480x612 Pixel</div>
            </div>

            <div class="box_center">
               <div class="papier_header">
                  <img id="papier_header" src="<?php echo (file_exists($images_path_h) ? $images_url_h.'?'.time() : $no_img); ?>" alt="" />
               </div>
            </div>
         </div>

         <div class="papier_box">
            <div class="box_left">
               <div class="txt_bez titel">Rechnungsfuß
                  <div class="upload upload_button pointer" onclick="Design.papierUpload('rechnungsfuss', 'papier_footer');"></div>
                  <div class="delete pointer far fa-trash-alt" onclick="Design.papierDelete('rechnungsfuss', 'papier_footer');"></div>
               </div>
               <div class="zeile2">jpg&nbsp;2480x372 Pixel</div>
            </div>

            <div class="box_center">
               <div class="papier_footer">
                  <img id="papier_footer" src="<?php echo (file_exists($images_path_f) ? $images_url_f.'?'.time() : $no_img); ?>" alt="" />
               </div>
            </div>
         </div>

         <div class="papier_box papierbox_bottom">
            <div class="box_center">
               <span class="txt_bez">Rechnungsfuß erstellen: </span>
               <span>Rechnungsfuß löschen und 1x auf speichern.</span>
               <br /><span>Es werden die angehakten Daten aus EINSTELLUNGEN/SHOPINHABER verwendet.</span>
            </div>
         </div>
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
<!-- <script src="<?php echo SHOP_URL;  ?>/js/jquery-ui.min.js"></script> -->
<script src="<?php echo ADMIN_URL; ?>/js/admin.js"></script>
<!-- <script src="<?php echo ADMIN_URL; ?>/js/jquery.minicolors.min.js"></script> -->
</body>
</html>
