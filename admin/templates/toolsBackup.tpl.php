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
<title>Kanpai Classic <?php echo (!defined('CONF_MODULE_PORTAL') ? 'Shopsystem' : 'Shopportal'); ?> - Administration - Tools</title>
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
         <div class='txt_tit'><a class="help_kanpaiclassic" href="<?php echo HELP_LINK; ?>/o49/backup-modul/" target="_blank"></a>Shop-Sicherung</div>
      </div>

      <div id="tools" class="tools_backup maincontent content_box content_box_bottom">
         <div id="content_top"></div>

         <?php // Module Backup ?>
         <?php if (isset($_SESSION['backup']) || defined('CONF_MODULE_BACKUP')) { ?>
         <div class="box_tools">
            <div class="box_left">
               <div class="tools_line txt_bez">Datenbank-Sicherung</div>
               <?php $backups = $this->getDbBackups(); ?>
               <?php for ($i = 0; $i < count($backups); $i++) { ?>
               <div class="tools_line">
                  <span><a class="far fa-trash-alt" href="<?php echo ADMIN_URL_IDX.'/toolsBackup/del_db_backup/'.basename($backups[$i]); ?>"></a></span>
                  <span class="download download_button pointer"><a href="<?php echo ADMIN_URL_IDX.'/toolsBackup/up_db_backup/'.basename($backups[$i]); ?>"></a></span>
                  <span><?php echo basename($backups[$i]); ?></span>
               </div>
               <?php } ?>
               <div class="tools_line">
                  <a class="button_ci" onclick="Tools.backup();" href="<?php echo ADMIN_URL_IDX.'/toolsBackup/dbBackup'; ?>">neu</a>
               </div>
            </div>

            <div class="box_right">
               <div style="position:relative; width:400px; float:left;">
                  <div class="tools_line txt_bez">Dateien-Sicherung</div>
                  <?php $backups = $this->getShopBackups(); ?>
                  <?php for ($i = 0; $i < count($backups); $i++) { ?>
                  <div class="tools_line">
                     <span><a class="far fa-trash-alt"  href="<?php echo ADMIN_URL_IDX.'/toolsBackup/del_shop_backup/'.$backups[$i]; ?>"></a></span>
                     <span class="download download_button pointer"><a href="<?php echo ADMIN_URL_IDX.'/toolsBackup/up_shop_backup/'.$backups[$i]; ?>"></a></span>
                     <span>

                     </span>
                     <span><?php echo basename($backups[$i]); ?></span>
                  </div>
                  <?php } ?>
                  <div class="tools_line"><span class="hinweis">Hinweis: Funktion der Dateien-Sicherung ist abhängig von der Server-Konfiguration und Anzahl Bilder.</span></div>
                  <div class="tools_line">
                     <a class="button_ci" onclick="Tools.backup();" href="<?php echo ADMIN_URL_IDX.'/toolsBackup/shopBackup'; ?>">neu</a>
                  </div>
               </div>
            </div>

            <div class="clear"></div>
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
var shopurl       = '<?php echo SHOP_URL; ?>';
var template_url  = '<?php echo TEMPLATE_URL; ?>';
var max_file_size = '<?php echo max(KANPAICLASSIC\Helper::mbytesToBytes(ini_get('upload_max_filesize')), KANPAICLASSIC\Helper::mbytesToBytes(ini_get('post_max_size'))); ?>';
var editor_css    = "<?php echo TEMPLATE_URL; ?>/css/editor.css";
</script>
<script src="<?php echo SHOP_URL; ?>/js/jquery3.min.js"></script>
<script src="<?php echo SHOP_URL; ?>/js/jquery-ui.min.js"></script>
<script src="<?php echo ADMIN_URL; ?>/js/admin.js"></script>

<script src="<?php echo SHOP_URL; ?>/js/fancybox/jquery.fancybox.js"></script>
<script src="<?php echo ADMIN_URL; ?>/js/jquery.minicolors.min.js"></script>
