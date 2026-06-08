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
$menu                 = KANPAICLASSIC\Control::getMenu();
$admin_config         = $menu->loadDesign();

$cron = false;

 if (is_file(SHOP_PATH.'/cronjob.php')) {
    $cron = true;
 }

?><!DOCTYPE html>
<html lang="de">
<head>
<title>Kanpai Classic <?php echo (!defined('CONF_MODULE_PORTAL') ? 'Shopsystem' : 'Shopportal'); ?> - Administration - Rabattgruppen</title>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<link rel="stylesheet" href="<?php echo SHOP_URL; ?>/fonts/fontawesome/css/all.css" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
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
         <div class='txt_tit'><a class="help_kanpaiclassic" href="<?php echo HELP_LINK; ?>/tools/rabattgruppen/" target="_blank"></a>Artikel-Rabatte in %</div>
         <div class="save_button" onclick="Tools.rabatteSave();">speichern</div>
      </div>

      <div id="tools" class="maincontent content_box content_box_bottom">
         <div id="content_top">
         </div>

         <?php // Module Rabatte ?>
         <?php if (defined('CONF_MODULE_RABATTE') && !defined('CONF_MODULE_PORTAL')) { ?>
         <div class="rabatte_vk box_tools">
            <form id="form_rabatte">
               <div id="rabatte">
                  <div class="mobile_slide">
                     <div class="mobile_slide_inner">
                        <div id="rabatte_inner">
                           <?php include_once SHOP_PATH.'/classes/modules/rabattgruppen/rabatte.tpl.php'; ?>
                           <?php echo $html; ?>
                        </div>
                     </div>
                  </div>
               </div>
            </form>
         </div>
         <?php } ?>
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
