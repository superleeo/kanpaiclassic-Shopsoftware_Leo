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
//   die ("This file cannot run outside the Kanpai Classic Shopsoftware");
}

$menu           = KANPAICLASSIC\Control::getMenu();
$admin_config   = $menu->loadDesign();
$help           = KANPAICLASSIC\Control::getHelp();

?><!DOCTYPE html>
<html lang="de">
<head>
<title>Kanpai Classic <?php echo (!defined('CONF_MODULE_PORTAL') ? 'Shopsystem' : 'Shopportal'); ?> - Administration - Livesesigner</title>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<style>
<?php include_once ADMIN_PATH.'/css/'.(is_file(ADMIN_PATH.'/css/admin.css') ? 'admin.css' : 'admin_easy.css'); ?>
</style>
<link rel="stylesheet" href="<?php echo SHOP_URL; ?>/fonts/fontawesome/css/all.css" />
<link rel="stylesheet" type="text/css" href="<?php echo ADMIN_URL; ?>/css/jquery.minicolors.css" />
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_URL; ?>/classes/modules/livedesigner/livedesigner.css" />
<style>
   .livedesigner_pos1 { position:relative; max-width:<?php echo $this->json['max_width']; ?>px; margin:auto; }
</style>
<script src="<?php echo SHOP_URL;  ?>/js/jquery3.min.js"></script>
<script src="<?php echo TEMPLATE_URL; ?>/js/jquery.cubeportfolio.js"></script>
</head>

<body>
<div id="page" class="admin_bg">
   <div id="live_width"><?php echo $live_width; ?></div>
   <div id="live_startseite"><?php echo $live_startseite; ?></div>
   <?php echo $menu->printHeader(); ?>
   <div id="menu">
      <?php echo $menu->menuData(); ?>
   </div>

   <div id="shop_content">
      <?php echo $startseite; ?>
   </div>
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
var is_desktop    = <?php echo CONF_MINSIZE_DESKTOP; ?>;
var is_phone      = <?php echo CONF_MAXSIZE_PHONE; ?>;
var site_width    = <?php echo $admin_config->admdsgn_width; ?>;
</script>

<!-- <script src="<?php echo SHOP_URL;  ?>/js/jquery3.min.js"></script> -->
<script src="<?php echo SHOP_URL;  ?>/js/jquery-ui.min.js"></script>
<script src="<?php echo ADMIN_URL; ?>/js/admin.js"></script>
<?php /* <link rel="stylesheet" type="text/css" href="<?php echo SHOP_URL; ?>/classes/modules/livedesigner/livedesigner.css" /> */ ?>
<script src="<?php echo SHOP_URL; ?>/classes/modules/livedesigner/livedesigner.js"></script>
<?php if (file_exists(SHOP_PATH.'/classes/modules/livedesigner2/livedesigner2.module.php')) { ?>
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_URL; ?>/classes/modules/livedesigner2/livedesigner2.css" />
<script src="<?php echo SHOP_URL; ?>/classes/modules/livedesigner2/livedesigner2.js"></script>
<?php } ?>
<?php if (file_exists(SHOP_PATH.'/classes/modules/livedesigner_ext/livedesigner_ext.module.php')) { ?>
<link rel="stylesheet" type="text/css" href="<?php echo SHOP_URL; ?>/classes/modules/livedesigner_ext/livedesigner_ext.css" />
<script src="<?php echo SHOP_URL; ?>/classes/modules/livedesigner_ext/livedesigner_ext.js"></script>
<?php } ?>
<script src="<?php echo ADMIN_URL; ?>/js/jquery.minicolors.min.js"></script>
<?php include ADMIN_PATH.'/editor_seiten.inc.php'; ?>
</body>
</html>
