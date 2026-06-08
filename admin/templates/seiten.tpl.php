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

header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time()));
header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
$menu                 = KANPAICLASSIC\Control::getMenu();
$admin_config         = $menu->loadDesign();
$template_images      = TEMPLATE_URL.'/images/';
$template_images_file = TEMPLATE_PATH.'/images/';
$sel_lang             = $this->params->selected_lang;
$img_base             = TEMPLATE_URL.'/images/';
$no_img               = ADMIN_URL.'/img/nopic.png';

$img1 = (file_exists(TEMPLATE_PATH.'/images/danke1.jpg') ? TEMPLATE_URL.'/images/danke1.jpg' : TEMPLATE_URL.'/images/system/danke_seite.png');
$img1 = (file_exists(TEMPLATE_PATH.'/images/danke1_'.$lang.'.jpg') ? TEMPLATE_URL.'/images/danke1_'.$lang.'.jpg' : $img1);
$img2 = (file_exists(TEMPLATE_PATH.'/images/danke2.jpg') ? TEMPLATE_URL.'/images/danke2.jpg' : TEMPLATE_URL.'/images/system/danke_seite.png');
$img2 = (file_exists(TEMPLATE_PATH.'/images/danke2_'.$lang.'.jpg') ? TEMPLATE_URL.'/images/danke2_'.$lang.'.jpg' : $img2);

?><!DOCTYPE html>
<html lang="de">
<head>
<title>Kanpai Classic <?php echo (!defined('CONF_MODULE_PORTAL') ? 'Shopsystem' : 'Shopportal'); ?> - Administration - Seiten</title>
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
         <div class='txt_tit'><a class="help_kanpaiclassic" href="<?php echo HELP_LINK; ?>/seiten/" target="_blank"></a>Seiten</div>
         <div class="language"><?php echo $menu->langData(); ?></div>
      </div>

      <div id="seiten" class="maincontent">
         <div class="content_box content_box_bottom">
            <div id="content_top"></div>
            <div id="seiten1">
               <?php foreach ($seiten1 as $seite => $v) { ?>
                  <?php $check = $this->_check($seite, $v['check']); ?>
                  <?php $name  = $this->_checkName($seite, $v['name']); ?>
               <div class="site_item <?php echo $seite; ?>">
                  <span class="edit pointer fas fa-pencil-alt<?php echo ($seite == 'homebutton' ? ' ci_color' : ''); ?>" onclick="Seiten.popup('<?php echo $seite; ?>');"></span>
                  <span class="active pointer fas <?php echo ($check !== 'y' ? 'fa-times' : 'fa-check'); ?>" onclick="Seiten.active(this, '<?php echo $seite; ?>');"></span>
                  <span class="site_name"><?php echo $name; ?></span>
                  <?php if ($seite == 'homebutton') { ?>
                  <span class="help ci_color" title="SEO ist immer aktiv, auch wenn Button HOME deaktiv ist. Logo&nbsp;und&nbsp;Logobanner linken ebenfalls auf die HOME-Seite. HOME&nbsp;kann daher gern deaktiviert werden (Clean-Design)."></span>
                  <?php } ?>
               </div>
               <?php } ?>
               <div class="clear"></div>
            </div>
            <div class="clear"></div>
            <hr />

            <div id="seiten2">
            <?php foreach ($seiten2 as $seite => $v) { ?>
               <?php if ($seite == 'ds_gvo') { continue; } ?>

               <?php $check = $this->_check($seite, $v['check']); ?>
               <?php $name  = $this->_checkName($seite, $v['name']); ?>
               <div class="ellipsis site_item <?php echo $seite; ?>">
                  <?php if ($seite == 'anmelden') { ?>
                  <span class="edit fas"></span>
                  <?php } else { ?>
                  <span class="edit pointer fas fa-pencil-alt" onclick="Seiten.popup('<?php echo $seite; ?>');"></span>
                  <?php } ?>


                  <?php if ($seite == 'impressum' || $seite == 'datenschutz' || $seite == 'kontakt2' || $seite == 'anmelden') { ?>
                     <?php if (defined('CONF_MODULE_WEBSITE')) { ?>
                  <span class="active pointer fas <?php echo ($check !== 'y' ? 'fa-times' : 'fa-check'); ?>" onclick="Seiten.active(this, '<?php echo $seite; ?>');"></span>
                     <?php } else { ?>
                  <span class="active fas"></span>
                     <?php } ?>
                  <?php } else { ?>
                  <span class="active pointer fas <?php echo ($check !== 'y' ? 'fa-times' : 'fa-check'); ?>" onclick="Seiten.active(this, '<?php echo $seite; ?>');"></span>
                  <?php } ?>

                  <span class="site_name"><?php echo $name; ?></span>
                  <?php if ($seite == 'datenschutz') { ?>
                  <?php } ?>
               </div>
               <?php } ?>
               <div>&nbsp;</div>
               <div>
                  <span class="cookiepopup button" onclick="Seiten.headerPopup();">Headerscript</span>
                  <span class="cookiepopup button" onclick="Seiten.cookiePopup();">Cookiepopup</span>
               </div>
               <div>&nbsp;</div>
               <div>
                  <span class="txt_bez ellipsis"><a href="http://chat-software.eu" title="Chat-Software" target="_blank" rel="noopener"><img src="../img/social_icons/03.png" alt="Chat-Software" width="25" height="25" /></a></span> <a href="http://chat-software.eu" class="link ci_color" title="Chat-Software" target="_blank">www.chat-software.eu</a>
               </div>           
            </div>

            <div id="seiten3">
            <?php foreach ($seiten3 as $seite => $v) { ?>
               <?php $check = $this->_check($seite, $v['check'], $seite); ?>
               <?php $name  = $this->_checkName($seite, $v['name']); ?>
               <div class="ellipsis site_item <?php echo $seite; ?>">
                  <span class="edit pointer fas fa-pencil-alt<?php echo ($seite == 'starthtml' ? ' ci_color' : ''); ?>" onclick="Seiten.popup('<?php echo $seite; ?>');"></span>

                  <?php if ($seite == 'versand' || $seite == 'agb') { ?>
                     <?php if (defined('CONF_MODULE_WEBSITE')) { ?>
                  <span class="active pointer fas <?php echo ($check !== 'y' ? 'fa-times' : 'fa-check'); ?>" onclick="Seiten.active(this, '<?php echo $seite; ?>');"></span>
                     <?php } else { ?>
                  <span class="active fas"></span>
                     <?php } ?>
                  <?php } else { ?>
                  <span class="active pointer fas <?php echo ($check !== 'y' ? 'fa-times' : 'fa-check'); ?>" onclick="Seiten.active(this, '<?php echo $seite; ?>');"></span>
                  <?php } ?>

                  <span class="site_name"><?php echo $name; ?></span>
               </div>
               <?php } ?>

               <div class="ellipsis site_item sitemap">
                  <span class="edit pointer fas fa-pencil-alt" onclick="Seiten.popupSitemap();"></span>
                  <span class="active pointer fas <?php echo ($this->params->firma['sitemap_check'] !== 'y' ? 'fa-times' : 'fa-check'); ?>" onclick="Seiten.active(this, 'sitemap');"></span>
                  <span class="site_name" title="Je nach Anzahl Artikel / Kategorien kann bei Aktivierung die Antwort etwas dauern">Sitemap</span>
               </div>
            </div>

            <div class="clear"></div>
         </div>

         <?php // if ($template_show == 'template_2') { ?>
         <?php if (true) { ?>
         <?php $this->params->getLinks($sel_lang); ?>
         <div id="danke" class="easy">
            <div class="content_box_abstand"></div>
            <div class="titelzeile">
               <div class='txt_tit'><a class="help_kanpaiclassic" href="<?php echo HELP_LINK; ?>/o51/dankeseite/" target="_blank"></a>Danke-Seite <span class="fliesstext"></span></div>
               <div class="language"><?php echo $menu->langData(); ?></div>
            </div>

            <?php // +++++++++++++++++++++++++++ Danke-Seite ++++++++++++++++++++++++++++++ ?>
            <div class="content_box">
            <?php if (defined('CONF_MODULE_CONVERSION') || defined('CONF_MODULE_SHOPSIEGEL') || defined('CONF_MODULE_TRUSTEDSHOPS')) { ?>
               <div id="extra_options">
                  <?php if (defined('CONF_MODULE_CONVERSION')) { ?>
                  <div id="conversion_script" class="button txt_but" onclick="Seiten.loadConversion();">Conversion</div>
                  <?php } ?>

                  <?php if (defined('CONF_MODULE_SHOPSIEGEL')) { ?>
                  <?php $json = json_encode(['vorname'    => $this->params->firma['first_name'],
                                             'nachname'   => $this->params->firma['last_name'],
                                             'email'      => $this->params->firma['email'],
                                             'firmenname' => $this->params->firma['firm_name'],
                                             'adresse'    => $this->params->firma['street'].' '.$this->params->firma['haus_nr'],
                                             'plz'        => $this->params->firma['postal_code'],
                                             'ort'        => $this->params->firma['city'],
                                             'bundesland' => $this->params->firma['last_name'],
                                             'land'       => $this->params->firma['country'],
                                             'telefon'    => $this->params->firma['telefon'],
                                             'website'    => SHOP_URL,
                                             'finanzamt'  => $this->params->firma['finanzamt'],
                                             'steuernr'   => $this->params->firma['steuernr'],
                                             'ustid'      => $this->params->firma['ustid'],
                                             'hrb'        => '',
                                             'hrb_nr'     => ''
                                           ]); ?>
                  <?php $json = base64_encode($json); ?>
                  <div id="shopsiegel" class="button txt_but" onclick="Seiten.loadShopsiegel('<?php echo SHOPSIEGEL_TITEL_BE; ?>', '<?php echo SHOPSIEGEL_LINK; ?>', '<?php echo $json; ?>');">Shopsiegel</div>
                  <?php } ?>

                  <?php if (defined('CONF_MODULE_TRUSTEDSHOPS')) { ?>
                  <div id="trustedshops" class="button txt_but" onclick="Seiten.loadTrustedshops();">Trusted-Shops</div>
                  <input type="hidden" id="trustedshop" value="<?php echo $this->params->firma['trustedshop']; ?>" />
                  <?php } ?>
               </div>
               <?php } ?>

               <div class="danke_block">
                  <div class="box_left">
                     <div class="pos">links (jpg)</div>
                     <div  class="image">
                        <img id="danke1_img" src="<?php echo $img1; ?>" alt="" />
                        <div class="upload_block">
                           <span class="upload upload_button pointer" onclick="Seiten.upload(1, 100, 'danke1_img');" title="Bild hochladen"></span>
                           <span class="delete pointer far fa-trash-alt" onclick="Seiten.delete(this, 'danke1', 'danke1');" title="Bild löschen"></span>
                           <span class="link pointer fas fa-link" onclick="Seiten.linkPopup('danke', 'linkdanke', 1);" title="Bild verlinken / SEO"></span>
                           <input type="hidden" id="linkdanke_link1"   name="linkdanke_link1"   value="<?php echo $this->params->links['danke1_link']; ?>" />
                           <input type="hidden" id="linkdanke_intern1" name="linkdanke_intern1" value="<?php echo $this->params->links['danke1_intern']; ?>" />
                           <input type="hidden" id="linkdanke_seo1"    name="linkdanke_seo1"    value="<?php echo $this->params->links['danke1_seo']; ?>" />
                        </div>
                     </div>
                  </div>

                  <div class="box_center"></div>

                  <div class="box_right">
                     <div class="pos">rechts (jpg)</div>
                     <div class="image">
                        <img id="danke2_img" src="<?php echo $img2; ?>" alt="" />
                        <div class="upload_block">
                           <span class="upload upload_button pointer" onclick="Seiten.upload(2, 100, 'danke2_img');" title="Bild hochladen"></span>
                           <span class="delete pointer far fa-trash-alt" onclick="Seiten.delete(this, 'danke2', 'danke2');" title="Bild löschen"></span>
                           <span class="link pointer fas fa-link" onclick="Seiten.linkPopup('danke', 'linkdanke', 2);" title="Bild verlinken / SEO"></span>
                           <input type="hidden" id="linkdanke_link2"   name="linkdanke_link2"   value="<?php echo $this->params->links['danke2_link']; ?>" />
                           <input type="hidden" id="linkdanke_intern2" name="linkdanke_intern2" value="<?php echo $this->params->links['danke2_intern']; ?>" />
                           <input type="hidden" id="linkdanke_seo2"    name="linkdanke_seo2"    value="<?php echo $this->params->links['danke2_seo']; ?>" />
                        </div>
                     </div>
                  </div>
                  <div class="clear"></div>
               </div>
            </div>
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
<?php include ADMIN_PATH.'/editor_seiten.inc.php'; ?>
</body>
</html>
