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
$user_id        = $this->user_id;
$username       = '';

$ebay_button    = defined('CONF_MODULE_EBAYORDERS') && $this->params->firma['ebay_api'] == 'y';
$amazon_button  = defined('CONF_MODULE_AMAZONORDERS') && KANPAICLASSIC\Helper::getData('amazonorders_enabled') == 'y';
// $dawanda_button = defined('CONF_MODULE_DAWANDA') && KANPAICLASSIC\Helper::getData('dawanda_enabled') == 'y';
// $billbee_button = defined('CONF_MODULE_BILLBEE') && KANPAICLASSIC\Helper::getData('billbee_shop_id') != '';

if ($user_id) {
   $username = ' '.$this->db->querySingleValue("SELECT nachname FROM #__users WHERE id = $user_id");
}

$bestell_liste = $this->liste($user_id);

$dir    = ($_SESSION['bestell_dir'] == 'asc' ? '-asc' : '-desc');
$dir_fa = ($_SESSION['bestell_dir'] == 'asc' ? '-up' : '-down');
?><!DOCTYPE html>
<html lang="de">
<head>
<title>Kanpai Classic <?php echo (!defined('CONF_MODULE_PORTAL') ? 'Shopsystem' : 'Shopportal'); ?> - Administration - Bestellungen Übersicht</title>
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
         <div class='txt_tit'><a class="help_kanpaiclassic" href="<?php echo HELP_LINK; ?>/bestellungen/" target="_blank"></a>Bestellungen</div>
         <?php if (defined('CONF_MODULE_PORTAL') && $_SESSION['haendler'] == 'n') { ?>
         <form id="form_admin" method="post">
            <div id="bestellung_liste_portal_kunden">
               <span class="selectbox32">
                  <?php echo $this->_haendlerList($this->haendler_id, 'form_admin.submit()'); ?>
               </span>
            </div>
         </form>
         <?php } ?>
      </div>

      <div id="bestellung_liste" class="maincontent content_box no_border_top">
         <div id="content_top">
            <div class="best_suche">
               <input class="txt_inp" type="text" id="suche" name="suche" value="" onkeyup="(event.keyCode === 13 ? Bestellungen.find($(this).val(), 1) : '');"/>
               <div class="fas fa-search" onclick="console.log(123); Bestellungen.find($('#suche').val(), 1);"></div>
               <div id="find_reset" class="fas fa-power-off" onclick="Bestellungen.liste();"></div>
            </div>
            <div class="buttons_top_left">
               <div class="button_neu button_ci" onclick="location.href='<?php echo ADMIN_URL_IDX; ?>/bestellungen/neu/0';">neu</div>
            </div>
            <div class="buttons_top_right">
               <?php if (!$user_id && ($ebay_button)) { ?>
               <div id="orders">
                  <?php if ($ebay_button) { ?>
                  <div class="order_item button button" onClick="Bestellungen.getEbayBest();">Ebay-Best.</div>
                  <?php } ?>
<?php /*
                  <?php if ($dawanda_button) { ?>
                  <div class="order_item button" onClick="Bestellungen.getDawandaBest();">Dawanda-Best.</div>
                  <?php } ?>
*/ ?>
                  <?php if ($amazon_button) { ?>
                  <div class="order_item button" onClick="Bestellungen.getAmazonBest();">Amazon-Best.</div>
                  <?php } ?>
<?php /*

                  <?php if ($billbee_button) { ?>
                  <div class="order_item button" onClick="Bestellungen.billbeeSync();">Billbee Sync</div>
                  <?php } ?>
*/ ?>
               </div>
               <?php } ?>
            </div>
            <?php if (defined('CONF_MODULE_BESTELLZUSAMMENFASSUNG')) { ?>
               <div class="button buendeln"><a href="<?php echo ADMIN_URL_IDX ?>/bestellungen/collect" />bündeln</a></div>
            <?php } ?>
         </div>

         <div id="pager_oben">
            <div class="pager"><?php echo $this->getCounter(); ?></div>
            <div class="clear"></div>
            <hr />
         </div>

         <div class="mobile_slide">
            <div id="listcontent" class="mobile_slide_inner">
               <div id="best_titel" class="list_title">
                  <div class="best_list_left"></div>

                  <div class="best_list_right">
                     <?php $sort = ($_SESSION['bestell_sort'] == 1 ? 'fa-sort'.$dir_fa : 'fa-sort'); ?>
                     <div class="best_list1 list_col pointer" onclick="Bestellungen.sort(1, <?php echo $user_id; ?>);">
                        <span class="ellipsis txt_bez list_text">Status<span id="art_sort1_symbol" class="list_icon fas <?php echo $sort; ?>"></span></span>
                     </div>

                     <?php $sort = ($_SESSION['bestell_sort'] == 2 ? 'fa-sort'.$dir_fa :'fa-sort'); ?>
                     <div class="best_list2 list_col pointer" onclick="Bestellungen.sort(2, <?php echo $user_id; ?>);">
                        <span class="ellipsis txt_bez list_text">Be-Nr<span id="art_sort2_symbol" class="list_icon fas <?php echo $sort; ?>"></span></span>
                     </div>

                     <?php $sort = ($_SESSION['bestell_sort'] == 3 ? 'fa-sort'.$dir_fa :'fa-sort'); ?>
                     <div class="best_list3 list_col pointer" onclick="Bestellungen.sort(3, <?php echo $user_id; ?>);">
                        <span class="ellipsis txt_bez list_text">Be-Datum<span id="art_sort3_symbol" class="list_icon fas <?php echo $sort; ?>"></span></span>
                     </div>

                     <div class="best_list4 list_col txt_bez ellipsis">Re</div>

                     <?php $sort = ($_SESSION['bestell_sort'] == 4 ? 'fa-sort'.$dir_fa :'fa-sort'); ?>
                     <div class="best_list5 list_col pointer" onclick="Bestellungen.sort(4, <?php echo $user_id; ?>);">
                        <span class="ellipsis txt_bez list_text">Re-Nr<span id="art_sort4_symbol" class="list_icon fas <?php echo $sort; ?>"></span></span>
                     </div>

                     <?php $sort = ($_SESSION['bestell_sort'] == 5 ? 'fa-sort'.$dir_fa :'fa-sort'); ?>
                     <div class="best_list6 list_col pointer" onclick="Bestellungen.sort(5, <?php echo $user_id; ?>);">
                        <span class="ellipsis txt_bez list_text">Re-Datum<span id="art_sort5_symbol" class="list_icon fas <?php echo $sort; ?>"></span></span>
                     </div>

                     <?php $sort = ($_SESSION['bestell_sort'] == 6 ? 'fa-sort'.$dir_fa :'fa-sort'); ?>
                     <div class="best_list7 list_col pointer" onclick="Bestellungen.sort(6, <?php echo $user_id; ?>);">
                        <span class="text_icon ellipsis txt_bez list_text">E-Mail<span id="art_sort6_symbol" class="list_icon fas <?php echo $sort; ?>"></span></span>
                     </div>

                     <?php $sort = ($_SESSION['bestell_sort'] == 7 ? 'fa-sort'.$dir_fa :'fa-sort'); ?>
                     <div class="best_list8 list_col pointer" onclick="Bestellungen.sort(7, <?php echo $user_id; ?>);">
                        <span class="text_icon ellipsis txt_bez list_text">Name<span id="art_sort7_symbol" class="list_icon fas <?php echo $sort; ?>"></span></span>
                     </div>

                     <div class="best_list9 list_col txt_bez ellipsis">Firma</div>
                     <div class="best_list10 list_col txt_bez ellipsis">Land</div>
                     <div class="clear"></div>
                  </div>
                  <div class="clear"></div>
               </div>
               <div class="clear"></div>

               <div id="bestell_liste" class="">
                  <?php echo $bestell_liste; ?>
               </div>
            </div>
         </div>

         <div id="pager_unten">
            <div class="pager"><?php echo $this->getCounter(); ?></div>
            <div class="clear"></div>
         </div>
      </div>
   </div>

   <?php $menu->footer(); ?>
</div>

<script>
var langs          = '<?php echo implode(';', $this->params->langs); ?>';
var sel_lang       = 'deu';
var admin_url_idx  = '<?php echo ADMIN_URL_IDX ?>';
</script>
<script src="<?php echo SHOP_URL; ?>/js/jquery3.min.js"></script>
<script src="<?php echo SHOP_URL; ?>/js/jquery-ui.min.js"></script>
<script src="<?php echo ADMIN_URL; ?>/js/admin.js"></script>
</body>
</html>
