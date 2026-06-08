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
         <div class='txt_tit'><a class="help_kanpaiclassic" href="<?php echo HELP_LINK; ?>/tools/gutscheine-newsletter/" target="_blank"></a>Gutscheine & Newsletterversand</div>
         <div class="save_button" onclick="Tools.gutscheineSave();">speichern</div>
      </div>

      <div id="gutscheine" class="maincontent content_box content_box_bottom">
         <div id="content_top">
         </div>

         <?php // Gutscheien ?>
         <div class="box_tools">

            <div class="titelzeile2">
               <span class="span_title txt_tit">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
               <input type="checkbox" class="newdesign" id="gutschein_aktiv" name="gutschein_aktiv" <?php echo $data->gutschein_aktiv == 'y' ? ' checked="checked"' : ''; ?>  />
               <label for="gutschein_aktiv" class="newsletter fliesstext"></label>bei Bestellung Newsletterabfrage<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="checkbox" class="newdesign" id="newsletter_footer" name="newsletter_footer" <?php echo $data->newsletter_footer == 'y' ? ' checked="checked"' : ''; ?> />
                <label for="newsletter_footer" class="newsletter fliesstext"></label>Newsletterbutton im Footer
                <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="checkbox" class="newdesign" id="activate_voucher" name="activate_voucher" <?php echo $data->activate_voucher == 'y' ? ' checked="checked"' : ''; ?> />
                <label for="activate_voucher" class="newsletter fliesstext"></label>Gutscheine aktivieren

                <br><br>
               <div class="gs_pos_8 ellipsis">
                  <input type="checkbox" class="newdesign" id="sonderpreis_ausschliessen" name="sonderpreis_ausschliessen" <?php echo \KANPAICLASSIC\Helper::getData('sonderpreis_ausschliessen', 'n') == 'y' ? ' checked="checked"' : ''; ?>  />
                  <label for="sonderpreis_ausschliessen" class="newsletter fliesstext"></label> Sonderangebote bei %-Gutscheinen ausschließen
               </div>
            </div>

            <?php // Gutscheine einbinden ?>
            <div id="gutscheine_nl">
               <?php include_once ADMIN_PATH.'/templates/tools_gutscheine_inc.tpl.php'; echo $html; ?>
            </div>
               <div class="clear">&nbsp;</div>
               <h2 class="txt_tit">&nbsp;Design-Newslettersystem</h2><br>
			   <div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://newslettersystem.com" class="link ci_color" title="Newslettersystem" target="_blank">newslettersystem.com</a> für Massenmailing</div>
            <hr />

             <?php if(defined('CONF_MODULE_BONUSPROGRAMM')){ ?>

             
                <div class="mobile_slide" style="min-height:74px">
                    <div class="mobile_slide_inner">


                        <div class="gs_line">

                            <div class="gs_pos_01 right">
                                <span class="span_title txt_tit">Bonusprogramm </span>
                            </div>
                            <div class="gs_pos_2" style="width:48px">
                                <input type="checkbox" class="newdesign" id="bonusprogramm_aktiv" name="bonusprogramm_aktiv" <?php echo $data->bonusprogramm_aktiv == 'y' ? ' checked="checked"' : ''; ?> />
                                <label style="padding-left:1px;" for="bonusprogramm_aktiv" class="newsletter fliesstext"></label>
                            </div>
                            <div class="gs_pos_2">
                                &nbsp;&nbsp;&nbsp;Gutschrift
                            </div>
                            <div class="gs_pos_4" style="width:100px;padding-right:10px;">

                                <input class="txt_inp right" type="text" name="bonusprogramm_prozent" id="bonusprogramm_prozent" value="<?php echo $data->bonusprogramm_prozent; ?>" />

                            </div>
                            <div class="gs_pos_4">
                                % von Einkaufswert
                            </div>
                            <div class="gs_pos_8 txt_bez">Hinweis</div>
                            <div class="clear"></div>
                        </div>
                        <div class="gs_inner">
                            <div class="gs_text">
                                <div>
                                    Das Bonusprogramm gilt nur für Kunden mit Kunden-
                                    <br />account, da nur hier Gutschriften gespeichert werden
                                    <br />können. <a href="https://help.kanpaiclassic.com/o93/bonusprogramm/" class="link ci_color" title="Hilfe & Tipps" target="_blank">Hilfe bei Problemen zum Bonusprogramm</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


             <?php } ?>

             
         </div>
         <div class="clear">&nbsp;</div>
		 <hr />
         <?php // Print-Gutscheien ?>
         <?php if (defined('CONF_MODULE_GUTSCHEINPRINT')) { ?>
            <?php require_once SHOP_PATH.'/classes/modules/gutscheine_print/gutscheine_print.module.php'; ?>
            <?php $gs_print = new KANPAICLASSIC\KANPAICLASSIC_modulGutscheinePrint(); ?>
            <?php $gs_data  = $gs_print->load(); ?>
         <div class="box_tools">
            <div class="titelzeile2">
               <h2 class="txt_tit">&nbsp;Print-Gutscheine</h2>
               <!-- <div class="button_ci txt_but right" onclick="Tools.gsPrintSave()">speichern</div> -->
            </div>

            <?php // Modul gutscheine_print einbinden ?>
            <?php $html = ''; ?>
            <?php include_once SHOP_PATH.'/classes/modules/gutscheine_print/gutscheine_print.tpl.php'; ?>
            <div id="gutscheine_print">
               <?php echo $html; ?>
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
var shop_url      = '<?php echo SHOP_URL; ?>';
var template_url  = '<?php echo TEMPLATE_URL; ?>';
var max_file_size = '<?php echo max(KANPAICLASSIC\Helper::mbytesToBytes(ini_get('upload_max_filesize')), KANPAICLASSIC\Helper::mbytesToBytes(ini_get('post_max_size'))); ?>';
var editor_css    = "<?php echo TEMPLATE_URL; ?>/css/editor.css";
</script>
<script src="<?php echo SHOP_URL; ?>/js/jquery3.min.js"></script>
<script src="<?php echo SHOP_URL; ?>/js/jquery-ui.min.js"></script>
<script src="<?php echo ADMIN_URL; ?>/js/admin.js"></script>
<script src="<?php echo ADMIN_URL; ?>/js/jquery.minicolors.min.js"></script>
<?php include ADMIN_PATH.'/editor_seiten.inc.php'; ?>
</body>
</html>
