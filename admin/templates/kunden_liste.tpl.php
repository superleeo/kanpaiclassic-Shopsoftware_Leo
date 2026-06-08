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

$dir    = ($_SESSION['kunden_dir'] == 'asc' ? '-asc' : '-desc');
$dir_fa = ($_SESSION['kunden_dir'] == 'asc' ? '-up' : '-down');

if ($_SESSION['kunden_sort'] == 1) {
   $sort = 'sort'.$dir;
}
else {
   $sort = 'sort-no';
}

?><!DOCTYPE html>
<html lang="de">
<head>
<title>Kanpai Classic <?php echo (!defined('CONF_MODULE_PORTAL') ? 'Shopsystem' : 'Shopportal'); ?> - Administration - Kunden Übersicht</title>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
<style>
<?php include_once ADMIN_PATH.'/css/'.(is_file(ADMIN_PATH.'/css/admin.css') ? 'admin.css' : 'admin_easy.css'); ?>
</style>
<link rel="stylesheet" href="<?php echo SHOP_URL; ?>/fonts/fontawesome/css/all.css" />
<link rel="stylesheet" href="<?php echo ADMIN_URL; ?>/css/jquery-ui.css_xxx" />
</head>

<body>
<div id="page" class="admin_bg">
   <?php echo $menu->printHeader(); ?>
   <div id="menu">
      <?php echo $menu->menuData(); ?>
   </div>

   <div id="content">
      <div id="titelzeile" class="titelzeile">
         <div class='txt_tit'><a class="help_kanpaiclassic" href="<?php echo HELP_LINK; ?>/kunden/" target="_blank"></a>Kunden</div>
      </div>

      <div id="kunden_liste" class="maincontent content_box no_border_top">
         <div id="content_top">
            <div class="kunden_suche">
               <input class="txt_inp" type="text" id="suche" name="suche" value="" onkeyup="(event.keyCode === 13 ? Kunden.find($(this).val(), 1) : '');"/>
               <div class="fas fa-search" onclick="console.log(123); Kunden.find($('#suche').val(), 1);"></div>
               <div id="find_reset" class="fas fa-power-off" onclick="Kunden.liste();"></div>
            </div>
            <div class="buttons_top_left">
               <div class="button_ci button_neu" onclick="location.href='<?php echo ADMIN_URL_IDX; ?>/kunden/detail/0';">neu</div>
            </div>
            <div class="top_line"></div>
         </div>


         <div id="pager_oben">
            <div class="pager"><?php echo $this->getCounter(); ?></div>
            <div class="clear"></div>
            <hr />
         </div>


         <div class="mobile_slide">
            <div id="listcontent" class="mobile_slide_inner">
               <div id="best_titel" class="list_title">
                  <div class="kunde_list_left"></div>

                  <div class="kunde_list_right<?php echo (defined('CONF_MODULE_BESTELLUNGFRONT') ? '_front' : ''); ?>">
                     <?php $sort = ($_SESSION['kunden_sort'] == 1 ? 'fa-sort'.$dir_fa : 'fa-sort'); ?>
                     <div class="kunde_list1 list_col" onclick="Kunden.sort(1);">
                        <span class="ellipsis txt_bez list_text pointer">Datum<span id="art_sort1_symbol" class="list_icon fas <?php echo $sort; ?>"></span></span>
                     </div>

                     <?php $sort = ($_SESSION['kunden_sort'] == 2 ? 'fa-sort'.$dir_fa : 'fa-sort'); ?>
                     <div class="kunde_list2 list_col" onclick="Kunden.sort(2);">
                        <span class="ellipsis txt_bez list_text pointer">Name<span id="art_sort2_symbol" class="list_icon fas <?php echo $sort; ?>"></span></span>
                     </div>

                     <?php $sort = ($_SESSION['kunden_sort'] == 3 ? 'fa-sort'.$dir_fa : 'fa-sort'); ?>
                     <div class="kunde_list3 list_col" onclick="Kunden.sort(3);">
                        <span class="ellipsis txt_bez list_text pointer">Vorname<span id="art_sort3_symbol" class="list_icon fas <?php echo $sort; ?>"></span></span>
                     </div>

                     <?php $sort = ($_SESSION['kunden_sort'] == 4 ? 'fa-sort'.$dir_fa : 'fa-sort'); ?>
                     <div class="kunde_list4 list_col" onclick="Kunden.sort(4);">
                        <span class="ellipsis txt_bez list_text pointer">Firma<span id="art_sort4_symbol" class="list_icon fas <?php echo $sort; ?>"></span></span>
                     </div>

                     <?php $sort = ($_SESSION['kunden_sort'] == 5 ? 'fa-sort'.$dir_fa : 'fa-sort'); ?>
                     <div class="kunde_list5 list_col" onclick="Kunden.sort(5);">
                        <span class="ellipsis txt_bez list_text pointer">Adresse<span id="art_sort5_symbol" class="list_icon fas <?php echo $sort; ?>"></span></span>
                     </div>

                     <?php $sort = ($_SESSION['kunden_sort'] == 6 ? 'fa-sort'.$dir_fa : 'fa-sort'); ?>
                     <div class="kunde_list6 list_col" onclick="Kunden.sort(6);">
                        <span class="ellipsis txt_bez list_text pointer">Ort<span id="art_sort6_symbol" class="list_icon fas <?php echo $sort; ?>"></span></span>
                     </div>

                     <?php $sort = ($_SESSION['kunden_sort'] == 7 ? 'fa-sort'.$dir_fa : 'fa-sort'); ?>
                     <div class="kunde_list7 list_col" onclick="Kunden.sort(7);">
                        <span class="ellipsis txt_bez list_text pointer">Land<span id="art_sort7_symbol" class="list_icon fas <?php echo $sort; ?>"></span></span>
                     </div>

                     <?php $sort = ($_SESSION['kunden_sort'] == 8 ? 'fa-sort'.$dir_fa : 'fa-sort'); ?>
                     <div class="kunde_list8 list_col" onclick="Kunden.sort(8);">
                        <span class="ellipsis txt_bez list_text pointer">E-Mail<span id="art_sort8_symbol" class="list_icon fas <?php echo $sort; ?>"></span></span>
                     </div>

                     <?php $sort = ($_SESSION['kunden_sort'] == 9 ? 'fa-sort'.$dir_fa : 'fa-sort'); ?>
                     <div class="kunde_list9 list_col" onclick="Kunden.sort(9);" title="verifiziert">
                        <span class="ellipsis txt_bez list_text pointer">v<span id="art_sort9_symbol" class="list_icon fas <?php echo $sort; ?>"></span></span>
                     </div>
                     <div class="clear"></div>
                  </div>

                  <div class="kunde_list_extra<?php echo (defined('CONF_MODULE_BESTELLUNGFRONT') ? '_front' : ''); ?>">
                     <div class="list_col ellipsis txt_bez">Bestellung</div>
                 </div>
                 <div class="clear"></div>
               </div>
               <div class="clear"></div>

            <div id='kundenListe'>
               <?php echo $this->_printListe(); ?>
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

<script src="<?php echo SHOP_URL; ?>/js/jquery3.min.js"></script>
<script src="<?php echo SHOP_URL; ?>/js/jquery-ui.min.js"></script>
<script src="<?php echo ADMIN_URL; ?>/js/admin.js"></script>
<script>
var langs         = "<?php echo implode(';', $this->params->langs); ?>";
var sel_lang      = 'deu';
var admin_url_idx = '<?php echo ADMIN_URL_IDX; ?>';
var admin_url     = '<?php echo ADMIN_URL; ?>';
var shop_url_idx  = '<?php echo SHOP_URL_IDX; ?>';
var shop_url      = '<?php echo SHOP_URL; ?>';
</script>
</body>
</html>
