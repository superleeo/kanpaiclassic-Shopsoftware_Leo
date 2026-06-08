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

$img_url      = ($this->params->multishop ? \KANPAICLASSIC\Helper::getData('multishop_images') : SHOP_URL).'/'.CONF_PICT_PATH;
$menu         = KANPAICLASSIC\Control::getMenu();
$menudata     = $menu->menuData();
$admin_config = $menu->loadDesign();

$lang         = $this->params->selected_lang;
$mixer_gewicht_ge = '';

if ($this->main->grundeinheit == 'kg') {
   $mixer_gewicht_ge = 'Kg';
}

else if ($this->main->grundeinheit == 'liter') {
   $mixer_gewicht_ge = 'liter';
}

else if ($this->main->grundeinheit == 'ml' || $this->main->grundeinheit == '10ml' || $this->main->grundeinheit == '100ml') {
   $mixer_gewicht_ge = 'ml';
}

$show_brutto = ($this->params->firma['kleingewerbe'] == 'y' || $this->params->firma['tax_active'] == 'n' ? false : true);

$versandart = (int)$this->params->firma['versandart_1'];
$versandart_text = '';

switch ($versandart) {
   case 1:
      $versandart_text = 'indiv. Versandkosten (addiert)';
      break;

   case 2:
      $versandart_text = 'pauschale Versandkosten';
      break;

   case 3:
      $versandart_text = 'gewichtsabhängig';
      break;

   case 4:
      $versandart_text = 'Versandkosten pro Stück';
      break;

   case 5:
      $versandart_text = 'indiv. Versandkosten (höchste)';
      break;
}

$script   = '';

header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time()));
header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
header("X-Content-Type-Options: nosniff");

?><!DOCTYPE html>
<html lang="de">
<head>
<title>Kanpai Classic <?php echo (!defined('CONF_MODULE_PORTAL') ? 'Shopsystem' : 'Shopportal'); ?> - Administration - Artikel bearbeiten</title>
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
      <div id="artikel_detail">
         <div id="titelzeile" class="titelzeile titelzeile_artikel">
            <div class="txt_tit">
               <a class="help_kanpaiclassic" href="<?php echo HELP_LINK; ?>/artikel/" target="_blank"></a>
               Artikel
               <?php echo $this->getFeLink($this->main->parent_id); ?>
               <?php if (defined('CONF_MODULE_WEBSITE') && defined('CONF_TEMPLATE_ID') && CONF_TEMPLATE_ID == 2) { ?>
                  &nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" class="newdesign" name="show_object" id="show_object"<?php echo ($this->main->show_object == 'y' ? ' checked="checked"' : ''); ?> />
                  <label for="show_object"></label><span class="fliesstext">als Objekt anzeigen</span>
               <?php } else { ?>
               <input type="hidden" name="show_object" id="show_object" value="<?php echo ($this->main->show_object == 'y' ? 'on' : ''); ?>" />
               <?php } ?>
            </div>
            <div class="language"><?php echo $menu->langData(); ?></div>
            <div id="top_buttons">
               <?php // Artikel einer Kategorie anzeigen ?>
               <?php if (isset($_SESSION['listcategorie']) && $_SESSION['listcategorie']) { ?>
               <div id="back" class="button_cancel pointer txt_but" onclick="$('#cat_id').val(<?php echo $_SESSION['listcategorie_catid']; ?>); $('#cat_name').val('<?php echo $_SESSION['listcategorie_catname']; ?>'); $('#listcategorie').submit();">zurück</div>
               <?php } else { ?>
               <div id="back" class="button_cancel pointer txt_but" onclick="location.href='<?php echo ADMIN_URL_IDX; ?>/artikel';">zurück</div>
               <?php } ?>
               <div id="duplicate" class="button_cancel pointer txt_but" onclick="Artikel.articleCopy();">duplizieren</div>
               <div class="save_button" onclick="Artikel.saveArticle(<?php echo $parent_id; ?>);">speichern</div>
            </div>
            <?php if (defined('CONF_MODULE_PORTAL') && $_SESSION['haendler'] == 'n') { ?>
            <div class="haendler_name">'.$haendler->vorname.' '.$haendler->nachname.'</div>
            <?php } ?>

            <?php if (defined('CONF_MODULE_PORTAL')) { ?>
               <?php if ($_SESSION['haendler'] == 'n') { ?>
            <div>
                  <?php if (is_object($haendler)) { ?>Verkäufer
               <div class="kunde_icon" onclick="Royalart.haendlerEdit(<?php echo $haendler->user_id; ?>);" title="<?php echo ($haendler->firma != '' ? $haendler->firma : $haendler->vorname.' '.$haendler->nachname); ?>"></div>
               &nbsp;&nbsp;<?php echo str_replace(['http://', 'https://'], '', rtrim($haendler->website, '/')); ?>
               <input type="hidden" id="haendler_id" value="<?php echo $haendler->user_id; ?>" />
                  <?php } else { ?>
               Artikel ist keinem Händler zugeordnet!
                  <?php } ?>
            </div>
               <?php } else { ?>
            <input type="hidden" id="haendler_id" value="<?php echo $haendler->user_id; ?>" />
               <?php } ?>
            <?php } else { ?>
            <input type="hidden" id="haendler_id" value="0" />
            <?php } ?>
         </div>

          <div id="maincontent" class="maincontent">
              <div class=" content_box content_box_bottom">
                  <div id="content_top">
                      <div class="buttons_top_right"></div>

                      <div class="cat_block">
                          <div class="pos_kategorie txt_bez">
                              <span class="kategorie_text txt_bez">Kategorie</span>
                              <?php if ($max_cats > CONF_MAX_KAT) { // config.inc.php / kategorien.class.php ?>
                              <span id="maincat" class="input_box ellipsis">
                                  <?php echo $maincat_name; ?>
                              </span>
                              <span class="easy cat_show pointer fas fa-plus" onclick="Artikel.catShow();"></span>
                              <?php } else { ?>
                              <span class="selectbox30">
                                  <select name="category" id="category">
                                      <?php echo $catList; ?>
                                  </select>
                              </span>
                              <span class="easy cat_show pointer fas fa-plus" onclick="Artikel.catShow();"></span>
                              <?php } ?>

                              <?php if (defined('CONF_MODULE_PERSOCHECK')) { ?>
                              <input type="checkbox" class="newdesign" name="fsk_check" id="fsk_check" <?php echo ($this->main->fsk_check == 'y' ? ' checked="checked"' : ''); ?> />
                              <label for="fsk_check">Alterskontrolle</label>
                              <?php } else { ?>
                              <input type="hidden" name="fsk_check" id="fsk_check" value="<?php echo ($this->main->fsk_check == 'y' ? 'on' : ''); ?>" />
                              <?php } ?>
                          </div>
                          <div class="clear"></div>
                      </div>
                  </div>
                  <?php // Varianten  ?>
                  <?php include ADMIN_PATH.'/templates/artikel_details_tab1.tpl.php'; ?>
                  <?php // Megakonfigurator ?>
                  <?php include ADMIN_PATH.'/templates/artikel_details_tab2.tpl.php'; ?>

                  <!--            </div>

            <div class="content_box_abstand"></div>
            <div class="content_box">
-->
                  <div class="content_strich"></div>
                  <?php //Artikelbilder ?>
                  <?php include ADMIN_PATH.'/templates/artikel_details_tab3.tpl.php'; ?>
                  <?php // Musik_player ?>
                  <?php include ADMIN_PATH.'/templates/artikel_details_tab4.tpl.php'; ?>
              </div>
          </div>
      </div>
   </div>
   <?php $menu->footer(); ?>
</div>

<?php // Kategorie-Popup -> HTML für Multibox ?>
<div id="catlist_popup_placeholder" style="display:none;">
   <div id="catlist_popup" class="catlist_popup">
      <div id="catlist_outer">
      <?php if ($max_cats > CONF_MAX_KAT) { // config.inc.php / kategorien.class.php ?>
         <h1 class="txt_tit">Kategorien wählen</h1>
      <?php } else { ?>
         <h1 class="txt_tit">In weitere Kategorien einstellen</h1>
      <?php } ?>
         <div id="catlist" class="catlist">
         <?php for ($i = 0; $i < count($cat_array); $i++) { ?>
            <?php echo $cat_array[$i]; ?>
         <?php } ?>
         </div>
      </div>
      <div class="clear"></div>

      <div class="left">
         <div class="button_ci txt_but" onclick="Artikel.catAdd();">+ Kategorie</div>
      </div>

      <div class="buttonzeile">
         <div class="button button_left txt_but" onclick="Multibox.close();">abbrechen</div>
         <div class="button_ci button_right txt_but" onclick="Artikel.catStore();">übernehmen</div>
      </div>
      <div class="vergessen">Artikel speichern nicht vergessen.</div>
      <div class="catcopy" style="display:none;"><?php echo $catclone; ?></div>
   </div>
</div>
<?php // Für Artikel einer Kategorie / Zurück?>
<div style="display:none">
   <form id="listcategorie" action="<?php echo ADMIN_URL_IDX; ?>/artikel/listcategorie" method="post">
      <input type="hidden" name="cat_id" id="cat_id" value="0">
      <input type="hidden" name="cat_name" id="cat_name" value="">
      <input type="hidden" name="listcategorie_back" id="listcategorie_back" value="1">
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
var site_width    = <?php echo $admin_config->admdsgn_width; ?>;
var editor_css    = "<?php echo TEMPLATE_URL; ?>/css/editor.css";
</script>
<script src="<?php echo SHOP_URL; ?>/js/jquery3.min.js"></script>
<script src="<?php echo SHOP_URL; ?>/js/jquery-ui.min.js"></script>
<script src="<?php echo SHOP_URL; ?>/admin/js/admin.js"></script>
<script src="<?php echo ADMIN_URL; ?>/js/fileinput/plugins/sortable.min.js"></script>
<script src="<?php echo ADMIN_URL; ?>/js/fileinput/fileinput.min.js"></script>
<script src="<?php echo ADMIN_URL; ?>/js/fileinput/locales/de.js"></script>

<?php echo $script; ?>
<?php if ($this->mode == 'foto') { echo $this->foto_script; } ?>
<?php include_once ADMIN_PATH.'/editor_article_cat.inc.php'; ?>
<?php if (defined('CONF_MODULE_MUSIKPLAYER')) { ?>
   <?php include SHOP_PATH.'/classes/modules/musikplayer/musikplayer_editor.inc.php'; ?>
<?php } ?>
</body>
</html>
