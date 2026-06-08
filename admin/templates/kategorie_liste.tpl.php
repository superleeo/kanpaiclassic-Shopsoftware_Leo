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

?><!DOCTYPE html>
<html lang="de">
<head>
<title>Kanpai Classic <?php echo (!defined('CONF_MODULE_PORTAL') ? 'Shopsystem' : 'Shopportal'); ?> - Administration - Kategorien Übersicht</title>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
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
         <?php if ($this->params->multishop) { ?>
         <div class='txt_tit'><a class="help_kanpaiclassic" href="<?php echo HELP_LINK; ?>/kategorien/" target="_blank"></a><span class="ci_color txt_tit">Externe Kategorien</span></div>
         <?php } else { ?>
         <div class='txt_tit'><a class="help_kanpaiclassic" href="<?php echo HELP_LINK; ?>/kategorien/" target="_blank"></a>Kategorien</div>
         <?php } ?>
         <div class="language_right">
            <?php echo $menu->langData(); ?>
         </div>
      </div>

      <div id="category_list" class="maincontent content_box content_box_bottom">
         <div id="content_top">
            <div class="buttons_top_left">
               <a href="<?php echo ADMIN_URL_IDX; ?>/kategorien/details/0" class="button_ci txt_but">neu</a>
            </div>
            <div class="buttons_top_right">
               <span class="button txt_but" onclick="Kategorie.importCatXML();">Kat-Import</span>
               <span class="button txt_but" onclick="location.href = '<?php echo ADMIN_URL_IDX; ?>/ajax/kategorien/exportCatXml';">Kat-Export</span>
            </div>
         </div>

         <div id="category_list_left">
            <div class="mobile_slide">
               <div id="listcontent" class="mobile_slide_inner">
                  <?php echo $this->renderTree(5); ?>
               </div>
            </div>

            <?php if (defined('CONF_MODULE_EXTENDED')) { ?>
            <div id="bc_check" style="padding-top:20px;">
                <form id="saveBreadcrumbsForm" method="post" action="<?php echo ADMIN_URL_IDX; ?>/kategorien/saveBreadcrumbs#bc">
                    <div><a name="bc"></a>
                         <input onchange="jQuery('#saveBreadcrumbsForm').submit();" type="checkbox" class="newdesign" id="show_breadcrumbs" name="show_breadcrumbs" value="on" <?php echo ($this->params->firma['show_breadcrumbs'] == 'y' ? ' checked="checked"' : ''); ?> />
                         <label for="show_breadcrumbs">anklickbaren Kategoriepfad anzeigen</label>&nbsp;&nbsp;
                         <span class="help ci_color" title="Für einen modernen, cleanen Shop empfehlen wir, diese Funktion zu deaktivieren."></span>
                    </div>
                </form>
            </div>
            <?php } ?>

         </div>

         <?php if (defined('CONF_MODULE_PERSOCHECK')) { ?>
         <div id="category_list_right">
            <div id="alter_check">
               <form method="post" action="<?php echo ADMIN_URL_IDX; ?>/kategorien/savePersocheck">
                  <h1 class="txt_tit cat_titel" style="position:relative; left:0">Alterskontrolle</h1>
                  <div>
                     <input type="radio" class="newdesign" id="fsk_show_on" name="fsk_show" value="on"<?php echo ($this->params->firma['fsk_show'] == 'y' ? ' checked="checked"' : ''); ?> />
                     <label for="fsk_show_on">über Kategorien</label>&nbsp;&nbsp;
                     <input type="radio" class="newdesign" id="fsk_show_off" name="fsk_show" value="off"<?php echo ($this->params->firma['fsk_show'] == 'n' ? ' checked="checked"' : ''); ?> />
                     &nbsp;<label for="fsk_show_off">bei Anmeldung</label>
                  </div>
                  <div>mind. <input type="text" class="txt_inp" style="width:30px; padding:0 5px;" name="fsk" id="fsk" value="<?php echo $this->params->firma['fsk']; ?>" /> Jahren</div>
                  <button id="kategorie_save" class="button txt_but">speichern</button>
               </form>
            </div>
         </div>
         <?php } ?>
         <div class="clear"></div>
      </div>
   </div>
   <?php $menu->footer(); ?>
</div>
<?php // Aufruf Artikellist für Kategorie ?>
<div style="display:none">
   <form id="listcategorie" action="<?php echo ADMIN_URL_IDX; ?>/artikel/listcategorie" method="post">
      <input type="hidden" name="cat_id" id="cat_id" value="0" />
      <input type="hidden" name="cat_name" id="cat_name" value="" />
   </form>
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
<script src="<?php echo SHOP_URL; ?>/js/jquery3.min.js"></script>
<script src="<?php echo SHOP_URL; ?>/js/jquery-ui.min.js"></script>
<script src="<?php echo ADMIN_URL; ?>/js/admin.js"></script>
<script>
<?php if ($this->count > 300) { ?>
   $('.top_minus').click();
   $('.bottom_minus').click();
   $('.middle_minus').click();
<?php } ?>
</script>
<?php if (defined('DEBUG')) { ?>
<div><?php echo $this->params->debug; ?></div>
<?php } ?>
</body>
</html>
