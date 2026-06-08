<?php
/*
###################################################################################
  Kanpai Classic Shopsoftware Entwicklungsstand: 14.01.2021 Version 11

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

$ssl_force = file_exists(SHOP_PATH.'/force_ssl');

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
<title>Kanpai Classic <?php echo (!defined('CONF_MODULE_PORTAL') ? 'Shopsystem' : 'Shopportal'); ?> - Administration - Tools-Funktionen</title>
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
         <div class='txt_tit'><a class="help_kanpaiclassic" href="<?php echo HELP_LINK; ?>/tools/funktionen/" target="_blank"></a>Funktionen</div>
         <div class="save_button" onclick="Tools.save();">speichern</div>
      </div>

      <div id="tools" class="maincontent content_box content_box_bottom">
         <div id="content_top">
         </div>

         <form id="toolsform" action="<?php echo ADMIN_URL_IDX; ?>/tools/save" method="post">
            <div id="tools_einstellungen" class="box_tools">
               <input type="hidden" name="save" value="save" />
               <div class="box_left">
                  <div class="tools_line_big fliesstext">
                     <input type="checkbox" class="newdesign" id="staffelpreise" name="staffelpreise" <?php echo $data->staffelpreise == 'y' ? ' checked="checked"' : ''; ?> />
                     <label for="staffelpreise"></label>Staffelpreis im Admin anzeigen
                  </div>

                  <div class="easy tools_line_big fliesstext">
                     <input type="checkbox" class="newdesign" id="ean_check" name="ean_check" <?php echo $data->ean_check == 'y' ? ' checked="checked"' : ''; ?> />
                     <label for="ean_check"></label>EAN, MPN Lieferant, Grundpreis, Einkaufspreis
                  </div>

                  <div class="tools_line fliesstext">
                     <input type="checkbox" class="newdesign" id="downloads" name="downloads" <?php echo $data->downloads == 'y' ? ' checked="checked"' : ''; ?> />
                     <label for="downloads"></label>Downloadartikel (Upload-Button bei Artikelvariante einblenden)
                  </div>

                  <div class="tools_line_paypal fliesstext">
                     <input type="checkbox" class="newdesign" id="paypal_xtn" name="paypal_xtn" <?php echo KANPAICLASSIC\Helper::getData('paypal_xtn', 'y') == 'y' ? ' checked="checked"' : ''; ?> />
                     <label for="paypal_xtn"></label>Download-Mail versenden bei XTN-Code-Rückmeldung
                  </div>

                  <div class="tools_line_paypal fliesstext">
                     <input type="checkbox" class="newdesign" id="paypal_danke" name="paypal_danke" <?php echo KANPAICLASSIC\Helper::getData('paypal_danke', 'n') == 'y' ? ' checked="checked"' : ''; ?> />
                     <label for="paypal_danke"></label>Download-Mail versenden bei Paypal-Button "zurück zum Shop"<br /><br />
                  </div>

                  <div class="tools_line_big fliesstext">
                     <input type="checkbox" class="newdesign" id="gast_aktiv" name="gast_aktiv" <?php echo $data->gast_aktiv == 'y' ? ' checked="checked"' : ''; ?> />
                     <label for="gast_aktiv"></label>Gastbestellungen zulassen
                  </div>

                  <div class="tools_line_big fliesstext">
                     <input type="checkbox" class="newdesign" id="telefon_aktiv" name="telefon_aktiv" <?php echo $data->telefon_aktiv == 'y' ? ' checked="checked"' : ''; ?> />
                     <label for="telefon_aktiv"></label>Telefon ist Pflichtfeld
                  </div>
               </div>

               <div class="box_right">
                  <?php if (isset($_SESSION['ssl_error'])) { ?>
                  <div class="tools_line color_red">Sie müssen ein Zertifikat bei Ihrem Provider buchen.</div>
                  <?php unset($_SESSION['ssl_error']); ?>
                  <?php } ?>
                  <div class="tools_line_big fliesstext">
                     <input type="checkbox" class="newdesign" id="ssl_force" name="ssl_force" <?php echo $ssl_force ? ' checked="checked"' : ''; ?> />
                     <label for="ssl_force"></label>SSL-Weiterleitung aktivieren&nbsp;
                     <span class="help ci_color" title="SSL-Zertifikate buchen Sie bei Ihrem Provider.&#10; Ist SSL aufgeschalten, können Sie es hier weiterleiten."></span>
                  </div>

                  <div class="tools_line fliesstext">
                     <input type="radio" class="newdesign" id="seo_utf8" name="seo_utf8" value="on" <?php echo KANPAICLASSIC\Helper::getData('seo_utf8', 'n') == 'y' ? ' checked="checked"' : ''; ?> />
                     <label for="seo_utf8"></label>SEO-URL in UTF8-Standard (seit 2003)
                  </div>

                  <div class="tools_line fliesstext">
                     <input type="radio" class="newdesign" id="seo_ascii" name="seo_utf8" value="off" <?php echo KANPAICLASSIC\Helper::getData('seo_utf8', 'n') == 'n' ? ' checked="checked"' : ''; ?> />
                     <label for="seo_ascii"></label>SEO-URL ohne Umlaute<br /><br />
                  </div>

                  <div class="easy tools_line_big"><a class=" txt_but button" href="<?php echo HELP_LINK; ?>/seo/" target="_blank">SEO-Hinweise</a></div>
               </div>

               <div class="clear"></div>
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
<script src="<?php echo ADMIN_URL; ?>/js/jquery.minicolors.min.js"></script>
</body>
</html>
