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
require_once TEMPLATE_PATH.'/template_params.inc.php';

if (!defined('KANPAICLASSIC')) {
   define('KANPAICLASSIC', true);
}

// Shop
if (!isset($livedesigner)) {
   $kat      = KANPAICLASSIC\Control::getCategories();
   $articles = KANPAICLASSIC\Control::getArticles();
?><!DOCTYPE html >
<html lang="<?php echo $text->get('iso', $lang); ?>" x-ms-format-detection="none">
<head>
<title><?php echo $titel_tag; ?></title>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.5">
<meta name="robots" content="index, follow" />
<meta name="googlebot" content="noodp">
<meta name="keywords" content="<?php echo str_replace('"', "'", $keywords); ?>" />
<meta name="description" content="<?php echo str_replace('"', "'", $description); ?>" />
<meta name="rights" content="Webshop Onlineshop Shopsystem Flow&reg; Shopsoftware" />
<meta name="language" content="<?php echo $site_lang; ?>" />
<meta name="author" content="<?php echo $params->firma['shop_name'].' - '.$params->firma['first_name'].' '.$params->firma['last_name']; ?>" />
<meta name="generator" content="<?php echo $params->firma['shop_name'].' - '.$params->firma['first_name'].' '.$params->firma['last_name']; ?>" />

<?php if (defined('CONF_MODULE_HEADERSCRIPT') && is_file(TEMPLATE_PATH.'/save/save/google_verification.inc.php')) { // Neuer Ort ?>
   <?php include TEMPLATE_PATH.'/save/save/google_verification.inc.php'; ?>
<?php } ?>
<?php } else {
   // Livedeaugner
   $categories = KANPAICLASSIC\Control::getCategories();
  
   $kategorie  = '';



   // Kategorien links
   if ($params->firma['kategorien_links'] == 'l' || $params->firma['kategorien_links'] == 'y') {
      $kategorie = $categories->renderTree(0, false, true);
   }

   // Kategorien horizontal
   else if ($params->firma['kategorien_links'] == 'n' || $params->firma['kategorien_links'] == 'h') {
       $kategorie = $categories->renderTree(0, true, true);
   }

   // Kategorien Dropdown
   else {
       $kategorie = $categories->renderTreeSelect($params->kat_id);
   }

} ?>

<?php echo $params->head; ?>
<?php if ($fonts_css) { ?>
<style>
<?php echo $fonts_css; ?>
</style>
<?php } ?>

<?php if (!isset($livedesigner) && file_exists(TEMPLATE_PATH.'/images/favicon-32x32.png')) { // Shop  und Favicons erstellt ?>
<link rel="apple-touch-icon" sizes="57x57" href="<?php echo TEMPLATE_URL; ?>/images/apple-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="<?php echo TEMPLATE_URL; ?>/images/apple-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="<?php echo TEMPLATE_URL; ?>/images/apple-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="<?php echo TEMPLATE_URL; ?>/images/apple-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="<?php echo TEMPLATE_URL; ?>/images/apple-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="<?php echo TEMPLATE_URL; ?>/images/apple-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="<?php echo TEMPLATE_URL; ?>/images/apple-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="<?php echo TEMPLATE_URL; ?>/images/apple-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo TEMPLATE_URL; ?>/images/apple-icon-180x180.png">
<link rel="icon" type="image/png" sizes="192x192"  href="<?php echo TEMPLATE_URL; ?>/images/android-icon-192x192.png">
<link rel="icon" type="image/png" sizes="32x32" href="<?php echo TEMPLATE_URL; ?>/images/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="96x96" href="<?php echo TEMPLATE_URL; ?>/images/favicon-96x96.png">
<link rel="icon" type="image/png" sizes="16x16" href="<?php echo TEMPLATE_URL; ?>/images/favicon-16x16.png">
<!-- <link rel="manifest" href="<?php echo TEMPLATE_URL; ?>/images/manifest.json"> -->
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="<?php echo TEMPLATE_URL; ?>/images/ms-icon-144x144.png">
<meta name="theme-color" content="#ffffff">
<meta name="msapplication-square70x70logo" content="<?php echo TEMPLATE_URL; ?>/images/tile-tiny.png"/>
<meta name="msapplication-square150x150logo" content="<?php echo TEMPLATE_URL; ?>/images/tile-square.png"/>
<meta name="msapplication-wide310x150logo" content="<?php echo TEMPLATE_URL; ?>/images/tile-wide.png"/>
<meta name="msapplication-square310x310logo" content="<?php echo TEMPLATE_URL; ?>/images/tile-large.png"/>
<?php // Berechnete Styles / WIDTH_ADD ist > 0 bei Kategorien links ?>
<?php } ?>
<style id="colors_css">
<?php include_once TEMPLATE_PATH.'/css/colors.css'; ?>
</style>
<style id="template_css">
<?php include_once TEMPLATE_PATH.'/css/template.css'; ?>
</style>
<style>
<?php include_once TEMPLATE_PATH.'/css/editor.css'; ?>
</style>
<style id="template_var_css">
<?php include_once TEMPLATE_PATH.'/css/template_var.css'; ?>
</style>
<?php if (!defined('CONF_DEBUG')) { ?>
<script src="<?php echo SHOP_URL; ?>/js/jquery3.min.js"></script>
<?php } else { ?>
<script src="<?php echo SHOP_URL; ?>/js/jquery3.js"></script>
<?php } ?>
<?php if (!isset($livedesigner)) { ?>
</head>
<body id="scroll_to" class="bg_aussen taskonload_<?php echo (empty($_SESSION["task"])?"home":$_SESSION["task"]); ?>" data-content_width="<?php echo $content_width; ?>" data-logo_width="<?php echo $shop_width; ?>">
<?php } else { // Livedesigner ?>
<div id="livedesigner">
   <div class="bg_aussen" data-content_width="<?php echo $content_width; ?>" data-logo_width="<?php echo $shop_width; ?>">
<?php } ?>


            <?php if (!isset($livedesigner) && ($params->firma['cookie_check'] == 'y' || $params->firma['cookie_check'] == 'p') && !isset($_SESSION['cookie_check'])) { ?>
            <div id="cookie_check">
               <div class="col_single">
                  <div class="cookie_left">
                     <div class="cookie_text1 text_normal"><?php echo $text->get('cookie', 't'.($params->firma['cookie_check'] == 'p' ? '2' : '1').'_1'); ?></div>
                     <div class="cookie_text2 text_normal"><a href="<?php echo SHOP_URL_IDX; ?>/datenschutz" class="text_normal">(<?php echo $text->get('cookie', 't'.($params->firma['cookie_check'] == 'p' ? '2' : '1').'_2'); ?>)</a>&nbsp;<?php echo $text->get('cookie', 't'.($params->firma['cookie_check'] == 'p' ? '2' : '1').'_3'); ?> </div>
                  </div>
                  <div class="cookie_right">

                  <?php if ($params->firma['cookie_check'] == 'p') { ?>
                     <div class="text_normal cookie_settings" onclick="Cookies.cookieCheck('settings');"><?php echo $text->get('button', 'blockieren'); ?></div>
                     <div class="text_normal cookie_accept"   onclick="Cookies.cookieCheck('accept');"><?php echo $text->get('button', 'akzeptieren'); ?></div>
                  <?php } else { ?>
                     <div class="text_normal cookie_accept" onclick="Cookies.cookieCheck('accept');"><?php echo $text->get('button', 'ok'); ?></div>
                  <?php } ?>
                  </div>
                  <div class="clear"></div>
               </div>
            </div>
            <?php } ?>


   <?php if (isset($_SESSION['admin_msgb'])) { ?>
   <div id="admin_msgb"><p><?php echo $_SESSION['admin_msgb']; ?></p></div>
   <?php if (defined('CONF_ADCELL') && isset($_SESSION['adcell_netto'])) { ?>
   <script src="//www.adcell.de/js/track.js?eventid=<?php echo CONF_ADCELL_EVENTID; ?>&pid=<?php echo CONF_ADCELL_PID; ?>&referenz=<?php echo $_SESSION['adcell_bestellnummer']; ?>&betrag=<?php echo $_SESSION['adcell_netto']; ?>" async></script>
   <noscript><img src="//www.adcell.de/event.php?pid=<?php echo CONF_ADCELL_PID; ?>&eventid=<?php echo CONF_ADCELL_EVENTID; ?>&referenz=<?php echo $_SESSION['adcell_bestellnummer']; ?>&betrag=<?php echo $_SESSION['adcell_netto']; ?>"></noscript>
   <?php } ?>
   <?php unset($_SESSION['admin_msgb']); ?>
   <?php unset($_SESSION['adcell_netto']); ?>
   <?php unset($_SESSION['adcell_bestellnummer']); ?>
   <?php } ?>
   <div id="shop_wrapper">
      <div id="bg_wrapper" data-type="background"<?php echo ($params->firma['bg_fixed'] != 'y' ? ' data-speed="10"' : ''); ?> class="bg_aussen" >
         <div class="relative">
         <?php if ($start__bild || $start_video) { ?>
            <?php if ($start__bild) { ?>
            <div id="startbild_video" onclick="Royalart.showShop()">
               <img <?php echo ($start_video_reload ? 'id="start_video_reload" ' : ''); ?>src="<?php echo $startbild_video.$params->firma['image_cache']; ?>" alt="" />
            </div>
            <?php } else { ?>
            <div id="startbild_video" onclick="Royalart.showShop();">
               <video autoplay loop muted playsinline style="min-height:100%; min-width:100%;">
                  <source src="<?php echo $startbild_video; ?>" type="video/mp4">
                  Your browser does not support HTML5 video.
               </video>
            </div>
            <?php } ?>
         <?php } ?>

   <?php // ********** Header mit Menu, Logo, Banner, Kategorien-Menü / Burger-Button ********** ?>
            <div id="responsive_menu" class="bg_responsive" style="display:none;">
               <nav id="short_menu_inner">
                  <?php echo $kategorie; ?>
               </nav>
               <div class="clear"></div>
               <div id="short_menu_close" class="haupt_kat"></div>
            </div>

            <header class="">
               <?php if (isset($XXXlivedesigner)) { ?>
               <div id="live_width"><?php echo $live_width; ?></div>
               <div id="live_startseite"><?php echo $live_startseite; ?></div>
               <?php  } ?>
               <?php if ($cat_left) { ?>
                  <?php  if (isset($livedesigner) && defined('CONF_MODULE_EXTENDED')) { ?>
               <div id="livedesigner_video_links">
                  <div id="live_video"><?php echo $live_video; ?></div>
               </div>
                  <?php  } ?>
                  <?php if (isset($livedesigner)) { ?>
               <div id="livedesigner_background_links">
                  <div id="live_background"><?php echo $live_background; ?></div>
               </div>
                  <?php } ?>
               <?php } ?>
               <?php // top_menu_wrapper (in $menu_oben) ist position:absolute ?>
               <div id="mmenu"></div>
               <?php echo $menu_oben; ?>

               <?php // Logo unter Menü (Desktop) ?>
               <div id="logo_wrapper_unten" class="<?php echo ($is_flaeche_header ? 'bg_header' : ''); ?>">
                  <div id="logo" class="content_center_nopad<?php echo (!$is_flaeche_header ? ' bg_header' : ''); ?>">
                  <?php if ($logo_h > 0 || isset($livedesigner)) { ?>
                     <a href="<?php echo $logolink; ?>" <?php ($logointern != 'y' ? 'target="_blank"' : ''); ?> title="<?php echo $logoseo; ?>">
                        <img id="logo_img" data-responsive_nopad="" data-image-width="<?php echo $logo_w; ?>" data-image-height="<?php echo $logo_h; ?>" src="<?php echo $logo.$params->firma['image_cache']; ?>"  alt="<?php echo $logoseo; ?>" title="<?php echo $logoseo; ?>" style="max-width:100%;" />
                     </a>
                  <?php } ?>
                  <?php if (isset($livedesigner)) { ?>
                     <div id="livedesigner_logo">
                        <div id="live_logobanner" class="<?php echo ($cat_left ? 'live_logobanner_left' : 'live_logobanner_right'); ?>"><?php echo $live_logobanner; ?></div>
                        <?php if (!$cat_left) { ?>
                        <div id="live_kategorien"><?php echo $live_kategorien; ?></div>
                        <?php } ?>
                     </div>
                  <?php } ?>
                  </div>
               </div>

               <?php // Horizontales Menü im Header ?>
               <?php if (!$cat_left) { ?>
               <div id="cat_menu_wrapper" class="<?php echo $is_flaeche_header ? 'bg_horiz_kat'.$schatten : ''; ?>"
                  style="background-image:url(<?php echo TEMPLATE_URL.'/images/system/btn_leiste_'.strtolower($lang).'.png'.$params->firma['image_cache']; ?>);
                  <?php echo($device != 'desktop' ? ' display:none;' : ''); ?>
                  <?php echo($shop_burger == 'y' && $params->task == '' && !$params->set_offset ? ' opacity:0; visibility:hidden; display:none; padding:0;' : ''); ?>">
                  <nav id="cat_menu" class="<?php echo ($params->firma['kategorien_links'] != 'd' ? 'content_center' : 'content_select'); ?><?php echo !$is_flaeche_header ? ' bg_horiz_kat'.$schatten : ''; ?>">
                     <?php echo $kategorie; ?>
                  </nav>

                  <div class="content_center">
                     <div id="kategorie_sub" class="bg_responsive shadow">
                        <div id="kategorie_over"></div>
                     </div>
                  </div>
               </div>
                  <?php  if (isset($livedesigner) && defined('CONF_MODULE_EXTENDED')) { ?>
               <div id="livedesigner_video">
                  <div id="live_video"><?php echo $live_video; ?></div>
               </div>
                  <?php  } ?>
                  <?php if (isset($livedesigner)) { ?>
               <div id="livedesigner_background">
                  <div id="live_background"><?php echo $live_background; ?></div>
               </div>
                  <?php } ?>
               <?php } ?>
            </header>

            <?php if ($start__bild || $start_video) { ?>
            <div id="shop" class="abstand" style="margin-top:100vh; top:50px;" data-is_startbild="1">
            <?php } else { ?>
            <div id="shop" class="abstand">
               <div id="content_width_nopad" class="content_width_nopad"></div>
            <?php } ?>
            <?php if (isset($livedesigner)) { ?>
               <div id="livedesigner_abstand"<?php echo (((int)$params->firma['abstand'] + (int)$params->firma['abstand_oben']) < 25 ? ' style="top:'.(25 - ((int)$params->firma['abstand'] + (int)$params->firma['abstand_oben'])).'px;"' : ''); ?>>
                  <div id="live_abstand"><?php echo $live_abstand; ?></div>
               </div>
            <?php } ?>
            <?php if ($start__bild || $start_video) { ?>
            </div>
            <?php } else { ?>
            </div>
            <?php } ?>

   <?php // ********** extended oben ********** ?>
            <?php if ($extended_top || (!$extended_top && ($slider_reload == 'top' || $accordion_reload == 'top' || $carussell_reload == 'top'))) { ?>
            <div class="shows_desktop<?php echo $is_flaeche_header ? ' bg_innen' : ''; ?>">
               <div class="content_center<?php echo !$is_flaeche_header ? ' bg_innen' : ''; ?>">
               <?php if ($slider_reload == 'top' || $accordion_reload == 'top' || $carussell_reload == 'top') { ?>
                  <div id="html5_placeholder_top">
               <?php } ?>
                  <?php if ($slider_top) { ?>
                     <div id="slider_top" class="padding_top">
                     <?php echo $isExtended->slider_html; ?>
                     </div>
                  <?php } ?>
                  <?php if ($slider_reload == 'top') { ?>
                     <div id="slider_top" class="padding_top">
                        <div class="slider_placeholder"></div>
                     </div>
                  <?php } ?>
                  <?php if ($accordion_top) { ?>
                     <div id="accordion_top" class="accordion padding_top">
                     <?php echo $isExtended->accordion_html; ?>
                     </div>
                  <?php } ?>
                     <div class="content_center<?php echo !$is_flaeche_header ? ' bg_innen' : ''; ?>">
                     <?php if ($accordion_reload == 'top') { ?>
                        <div id="accordion_top" class="accordion padding_top">
                           <div class="accordion_placeholder"></div>
                        </div>
                     <?php } ?>
                     <?php if ($carussell_top) { ?>
                        <div id="carussell_top"  class="carussell padding_top">
                        <?php echo $isExtended->carussell_html; ?>
                        </div>
                     <?php } ?>
                     <?php if ($carussell_reload == 'top') { ?>
                        <div id="carussell_top"  class="carussell padding_top">
                           <div class="carussell_placeholder"></div>
                        </div>
                     <?php } ?>
                        <div class="clear"></div>
                     </div>
               <?php if ($slider_reload == 'top' || $accordion_reload == 'top' || $carussell_reload == 'top') { ?>
                  </div>
               <?php } ?>
               </div>
            </div>
            <?php } ?>

   <?php // ********** Shop Mitte ********** ?>
            <?php if ($cat_left) { ?>
            <div class="x_mit_menu_left content_center_nopad">
               <?php if ($kategorie != '') { ?>
               <div id="kat_links" class="menu_width_nopad col_left_height bg_innen">
                  <div class="padding_top padding_bottom menu_width">
                     <div class="menu_content">
                        <div style="height:auto;">
                        <nav class="menu_width">
                           <?php echo $kategorie; ?>
                           <?php // Symbol Livedesigner ?>
                           <?php if (isset($livedesigner)) { ?>
                           <div id="live_kategorien_left">
                              <div class="livedesigner live_kategorien"><?php echo $live_kategorien; ?></div>
                           </div>
                           <?php } ?>
                        </nav>
                        </div>
                     </div>
                     <div class=clear"></div>
                  </div>
               </div>
               <?php } ?>
            <?php } ?>
            <?php include_once TEMPLATE_PATH.'/template_mitte.tpl.php'; ?>
            <!-- Ende Mitte -->
            <?php if ($cat_left) { ?>
            </div>
            <?php } ?>

   <?php // ********** Extended unten *********** ?>
            <?php if ($extended_bottom) { ?>
            <!-- extended -->
            <div class="extra_bottom_wrapper padding_bottom<?php echo ($is_flaeche_mitte ? ' bg_innen' : ''); ?>">
               <div class="extra_bottom content_center<?php echo (!$is_flaeche_mitte ? ' bg_innen padding_bottom' : ''); ?>">
               <?php if ($cross_slider) { // Modul Crossslider ?>
                  <div class="slider_u content_center_nopad cross_slider padding_top">
                     <?php echo $slider_arr[0]; ?>
                  </div>
               <?php } ?>
               <?php if ($carussell_bottom) { ?>
                  <div class="carussell content_center_nopad shows_desktop padding_top">
                     <?php echo $isExtended->carussell_html; ?>
                  </div>
               <?php } ?>
               <?php if ($accordion_bottom) { ?>
                  <div class="accordion content_center_nopad shows_desktop padding_top" style="min-height:50px;">
                     <?php echo $isExtended->accordion_html; ?>
                  </div>
               <?php } ?>
               <?php if ($slider_bottom) { ?>
                  <div class="slider_u content_center_nopad padding_top">
                     <?php echo $isExtended->slider_html; ?>
                  </div>
               <?php } ?>
               </div>
               <div class="clear"></div>
            </div>
            <?php } else if ($slider_reload == 'bottom' || $accordion_reload == 'bottom' || $carussell_reload == 'bottom') { ?>
            <div id="html5_placeholder_bottom" class="extra_bottom_wrapper padding_bottom<?php echo ($is_flaeche_mitte ? ' bg_innen' : ''); ?>">
               <div class="extra_bottom content_center<?php echo (!$is_flaeche_mitte ? ' bg_innen padding_bottom' : ''); ?>">
               <?php if ($carussell_reload == 'bottom') { ?>
                  <div class="carussell content_center_nopad shows_desktop padding_top">
                     <div class="carussell_placeholder"></div>
                  </div>
               <?php } ?>
               <?php if ($accordion_reload == 'bottom') { ?>
                  <div class="accordion content_center_nopad shows_desktop padding_top" style="min-height:50px;">
                     <div class="accordion_placeholder"></div>
                  </div>
               <?php } ?>
               <?php if ($slider_reload == 'bottom') { ?>
                  <div class="slider_u content_center_nopad padding_top">
                     <div class="slider_placeholder"></div>
                  </div>
               <?php } ?>
                  <div class="clear"></div>
               </div>
            </div>
            <?php } else { ?>
            <!-- extended else -->
            <?php } ?>

<?php // ********** Footer ********** ?>
            <?php if (!isset($params->firma['footer_mode']) || $params->firma['footer_mode'] == 'freundlich') { ?>
               <?php require_once(TEMPLATE_PATH.'/footer_freundlich.tpl.php'); ?>
            <?php } else { ?>
               <?php require_once(TEMPLATE_PATH.'/footer_komplex.tpl.php'); ?>
            <?php } ?>

       
         </div>
      </div>
      <div id="scroll_top_wrapper">
         <a href="#scroll_to" id="scroll_top" aria-label="Scroll to Top"></a>
      </div>
   </div>
<?php // ********** Popups/Inhalt für Multibox usw. *********** ?>
   <?php if (defined('CONF_MODULE_POPUP') && $params->firma['popup_check'] == 'y' && !isset($_SESSION['popup_check'])) { ?>
      <?php include SHOP_PATH.'/classes/modules/popup/popup.tpl.php'; ?>
   <?php } ?>

   <?php if ($is_call_check) { ?>
   <div id="call_check_wrapper" class="hover_add">
      <div id="call_check"></div>
      <a href="tel:<?php echo $params->firma['telefon']; ?>" class="text_gross" style="color:#ffffff;"><?php echo $params->firma['telefon']; ?></a>
   </div>
   <?php } ?>

   <?php if (defined('CONF_MODULE_FILTER')) { ?>
   <div id="kategoriefilter" data-filter_text="<?php echo $text->get('filter', 'active'); ?>" onClick="filterPopup();">
      <div id="filter_check" class="bg_button col_button text_gross<?php echo ($params->task == 'kategorie' && $filter_check ? ' filter_active' :'') ?>">
         <span id="filter_active"></span>
         <?php echo $text->get('filter', 'filter'); ?>
      </div>
   </div>
   <?php } ?>
   <div id="catpass_wrapper" style="display:none;" onclick="passbox.close()"></div>
   <div id="catpass_box" style="display:none;">
      <div class="bg_flaechen">
            <form onSubmit="checkCatPass(); return false;">
            <div class="rahmen">
               <div class="ueberschrift text_gross">Passwort eingeben</div>
               <div class="pass_input">
                  <input type="text" class="text_formular text_gross" id="cat_pass" value="" autofocus="autofocus" />
                  <input type="hidden" value="" id="cat_elem1" />
               </div>
               <div class="buttons">
                  <div class="bg_button col_button text_gross" onclick="passbox.close()">Abbrechen</div><div class="bg_button col_button text_gross" onClick="checkCatPass();">Öffnen</div>
               </div>
            </div>
          </form>
      </div>
   </div>
   <?php if (isset($livedesigner) || $params->firma['cookie_check'] == 'p') { ?>
   <?php $cookie_hidden = (!isset($_SESSION['cookie_check']) || $_SESSION['cookie_check'] != 'y' ? ' style="display:none"' : ''); ?>
      <?php if (!isset($livedesigner)) { ?>
   <div id="cookie_fixed" class="bg_footer"<?php echo $cookie_hidden; ?>>
      <div id="cookie_fixed_bg" class="pointer<?php echo ($params->firma['footer_farbe']  == 'weiss' ? ' weiss' : ''); ?>" onclick="Cookies.cookiePopup();"></div>
   </div>
      <?php } else { ?>
   <div id="cookie_fixed" class="bg_footer">
      <div id="cookie_fixed_bg" class="pointer<?php echo ($params->firma['footer_farbe']  == 'weiss' ? ' weiss' : ''); ?>" onclick="Seiten.cookiePopup();"></div>
   </div>
      <?php } ?>
   <?php } ?>

   <div id="tstdiv" class="artikelname text_gross" style="position:absolute; top:-5000px; left:-5000px;"></div>
   <?php  echo KANPAICLASSIC\Helper::getFeedbackbox(); ?>
   <?php  echo KANPAICLASSIC\Helper::getFeedbackboxMl(); ?>
   <?php if (defined('CONF_MODULE_EASYCREDIT')) { ?>
   <div id="zahlungsplan_wrapper" style="display:none; position:fixed; top:0; right:0; bottom:0; left:0;" onClick="hideZahlungsplan();"></div>
   <div id="zahlungsplan_box" style="display:none; position:fixed; top:50%; left:50%; margin-left:-250px; margin-top:-250px;"></div>
   <?php } ?>
   <?php
   if (defined('CFG_DEBUG')) {
      $params->debug .= '<br />Laufzeit gesamt: '.number_format((microtime(true) - $start_debug), 3, ',', '.').' Sek';
      echo '<div class="debug">';
      echo "DEBUG-Informationen: ".$params->debug;
      echo '<br />Anzahl DB-Abfragen: '.$db->count;
      echo '</div>';
   }
   else if (defined('CFG_PERFORMANCEDEMO')) {
      echo '<div class="debug">';
      $params->debug .= '<br />Laufzeit gesamt: '.number_format((microtime(true) - $start_debug), 3, ',', '.').' Sek';
      echo "Performance-Informationen (Gesamtlaufzeit aller(!) Scripte und DB-Abfragen): ".$params->debug;
      echo '<br />Anzahl DB-Abfragen: '.$db->count;
      echo '</div>';
   }
   ?>
   <?php // Variablen zur Verwendung im Script ?>
   <script>
   var matrix_delay;
   var scriptpath           = "<?php echo SHOP_URL; ?>";
   var baseurl              = "<?php echo SHOP_URL_IDX; ?>";
   var shop_url_idx         = "<?php echo SHOP_URL_IDX; ?>";
   var templateurl          = "<?php echo TEMPLATE_URL; ?>";
   var lang                 = "<?php echo $params->selected_lang; ?>";
   var helpperso            = "<?php echo $text->get('kunde', 'perso_txt'); ?>";
   var height_factor        = <?php echo str_replace(',', '.', $thumb_width / $thumb_height); ?>;
   var show_menu_over       = <?php echo (defined('CONF_SHOW_MENU_OVER') ? 'true' : 'false'); ?>;
   var menu_mode            = '<?php echo $device; ?>';
   var last_menu_size       = '<?php echo $device; ?>';
   var device_detect        = '<?php echo $device_detect; ?>';
   var is_desktop           = <?php echo CONF_MINSIZE_DESKTOP; ?>;
   var is_phone             = <?php echo CONF_MAXSIZE_PHONE; ?>;
   var min_width_content2   = 640;
   var min_width_content4   = 400;
   var filter_name_all      = '<?php echo $text->get('filter', 'all'); ?>';
   var is_categorie         = true;
   var mainmenu_anzahl      = <?php echo CONF_MAINMENU_ANZAHL;?>;
   var is_counter           = <?php echo ($params->task == 'kategorie' || ($startseite && $params->firma['startseite_artikel'] == 'artikel') ? 'true' : 'false'); ?>;
   var article_search       = <?php echo ($params->article_search ? 'true' : 'false'); ?>;
   var content_width        = <?php echo $params->firma['max_width']; ?>;
   var content_width_nopad  = <?php echo $content_width + 2 * $content_padding; ?>;
   var content_center       = <?php echo $content_width;; ?>;
   var content_center_nopad = <?php echo $content_width_nopad; ?>;
   var content_padding      = <?php echo $content_padding; ?>;

   <?php // Variablen für Responsive ?>
   var artikel_max        = <?php echo (!isset($livedesigner) ? $params->artikel_max : 50); ?>;
   var artikel_reihen     = <?php echo $_SESSION['artikel_reihen']; ?>;
   var start_reihen       = <?php echo ($params->task == '' && $params->firma['startseite_artikel'] == 'reihen' ? $params->firma['startseite_reihen'] : $_SESSION['artikel_reihen']); ?>;
   var startseite         = <?php echo ($params->task == '' ? 'true' : 'false'); ?>;
   //var is_kanpaiclassic        = true;
   var shopsiegel_mode    = "<?php echo (isset($_SESSION['SHOPSIEGEL_MODE']) ? $_SESSION['SHOPSIEGEL_MODE'] : 'n'); ?>";

   var artikel_pro_reihe  = 0;
   //var artikel_seite      = 1;
   var artikel_seite      = <?php echo $_SESSION['artikel_seite']; ?>;
   var artikel_anzahl     = 0;
   var cat_arr            = [];
   var address_ok         = <?php echo (isset($_SESSION['user']['nachname']) && $_SESSION['user']['nachname'] != '' ? 'true' : 'false'); ?>;

   <?php
   if (file_exists(SHOP_PATH.'/tmp/cat_cache_'.$lang.'.js')) {
      $cats = json_decode(file_get_contents(SHOP_PATH.'/tmp/cat_cache_'.$lang.'.js'));

      if ($cats) {
         foreach ($cats as $k => $v) {
            ?>cat_arr[<?php echo $k; ?>] = '<?php echo $v; ?>'; <?php echo CR;
         }
      }
   } ?>

   <?php if ($params->task == 'kategorie') { ?>
   var artikel_kategorie = <?php echo $params->kat_id; ?>;
   var filter_kategorie  = <?php echo $params->kat_id; ?>;
   <?php } else { ?>
   var artikel_kategorie = 0;
   var filter_kategorie  = 0;
   <?php } ?>
   var my_slider = 0;
   var my_accordion = 0;
   var my_caroussell = 0;
   <?php if ($slider_reload != '' || $accordion_reload != '' || $carussell_reload != '') { ?>
   var html5_reload = true
   <?php } else { ?>
   var html5_reload = false
   <?php } ?>
   </script>
   <?php // Scripte einbinden ?>
   <!--[if lt IE 9]>
      <script type="text/javascript" src="<?php echo SHOP_URL; ?>/js/jquery1.min.js"></script>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
   <![endif]-->
   <!--[if gt IE 9]> -->

   <?php if (!defined('CONF_DEBUG') || !is_dir(TEMPLATE_PATH.'/developer_nicht_bei_kunden/')) { // template.min.js einbinden ?>
   <!-- <![endif]-->
   <script src="<?php echo SHOP_URL; ?>/js/jquery-migrate3.min.js"></script>
   <script src="<?php echo SHOP_URL; ?>/js/jquery-ui.min.js"></script>
   <script src="<?php echo SHOP_URL; ?>/js/jquery.ui.touch-punch.min.js"></script>
   <script src="<?php echo TEMPLATE_URL; ?>/js/template.min.js"></script>
   <!-- <script src="<?php echo SHOP_URL; ?>/js/fancybox/jquery.fancybox.js"></script> -->
   <!-- <script src="<?php echo SHOP_URL; ?>/js/enscroll.min.js"></script> -->
   <?php } else { // Original-Dateien ?>
   <script src="<?php echo SHOP_URL; ?>/js/jquery-migrate3.min.js"></script>
   <script src="<?php echo TEMPLATE_URL; ?>/developer_nicht_bei_kunden/print_r.js"></script>
   <script src="<?php echo SHOP_URL; ?>/js/jquery-ui.min.js"></script>
   <script src="<?php echo SHOP_URL; ?>/js/jquery.ui.touch-punch.min.js"></script>
   <script src="<?php echo TEMPLATE_URL; ?>/developer_nicht_bei_kunden/jquery.cubeportfolio.js"></script>
   <!-- <script src="<?php echo SHOP_URL; ?>/js/fancybox/jquery.fancybox.js"></script> -->
   <script src="<?php echo TEMPLATE_URL; ?>/developer_nicht_bei_kunden/bootstrap_tooltip.js"></script>
   <script src="<?php echo TEMPLATE_URL; ?>/developer_nicht_bei_kunden/shop.js?<?php echo time(); ?>"></script>
   <script src="<?php echo TEMPLATE_URL; ?>/developer_nicht_bei_kunden/jquery.form.min.js"></script>
   <!-- <script src="<?php echo SHOP_URL; ?>/js/enscroll.min.js"></script> -->
   <?php } ?>
   <?php if (defined('CONF_MODULE_360GRAD') && $params->task == 'artikel' && $params->is_360grad) { ?>
   <link rel="stylesheet" href="<?php echo SHOP_URL; ?>/classes/modules/360grad/script/View360.min.css" />
   <script src="<?php echo SHOP_URL; ?>/classes/modules/360grad/script/View360.min.js"></script>
   <script>
      document.addEventListener( "DOMContentLoaded", onLoaded );
      function onLoaded (){
         var view = new View360();

         view.setConfig({
            mode                           :"responsive",
            imagesNumbering                : 1,
            imagesNumberingUpDown          : 1,
            autoRotate                     : false,
            oneTurnOnStartUp               : false,
            loadFullSizeImagesOnZoom       : true,
            loadFullSizeImagesOnFullscreen : true
         });
         view.setNavigationConfig({
            btnWidth       :30,
            btnHeight      :30,
            showButtons    : true,
            showTool       : true,
            showMove       : false,
            showRotate     : false,
            showPlay       : true,
            showPause      : true,
            showZoom       : true,
            showTurn       : true,
            turnSpeed      : 50,
            showFullscreen : true,
            type           : "round",
            btnImageSize   : "80%",
            btnMargin      :"3"
         });
         // view.setImagesPattern( "image_%ROW_%COL.jpg" );
         view.setImagesPattern( "image_%CCC%.jpg" );
         view.setImagesDirectory( "<?php echo SHOP_URL.'/'.CONF_PICT_PATH.'360grad/'.$params->parent_id; ?>" );
         view.setFullSizeImagesDirectory( "<?php echo SHOP_URL.'/'.CONF_PICT_PATH.'360grad/'.$params->parent_id; ?>/original" );
         view.start( document.getElementById("view360") );
      }
   </script>
    <?php } ?>
   <?php if (defined('CONF_MODULE_TRUSTEDSHOPS') && $params->firma['trustedshop'] != '') { ?>
   <script type="text/javascript">
   (function () {
      var _tsid = '<?php echo $params->firma['trustedshop']; ?>';
      _tsConfig = {
         'yOffset': '0', /* offset from page bottom */
         'variant': 'reviews', /* default, reviews, custom, custom_reviews */
         'customElementId': '', /* required for variants custom and custom_reviews */
         'trustcardDirection': '', /* for custom variants: topRight, topLeft, bottomRight, bottomLeft */
         'customBadgeWidth': '', /* for custom variants: 40 - 90 (in pixels) */
         'customBadgeHeight': '', /* for custom variants: 40 - 90 (in pixels) */
         'disableResponsive': 'false', /* deactivate responsive behaviour */
         'disableTrustbadge': 'false' /* deactivate trustbadge */
      };
      var _ts = document.createElement('script');
      _ts.type = 'text/javascript';
      _ts.charset = 'utf-8';
      _ts.async = true;
      _ts.src = '//widgets.trustedshops.com/js/' + _tsid + '.js';
      var __ts = document.getElementsByTagName('script')[0];
      __ts.parentNode.insertBefore(_ts, __ts);
   })();
   </script>
   <?php } ?>

   <?php // Bei Fehler Bild laden ?>
   <?php if ($params->task != '' && $params->task != 'kategorien') { ?>
   <script>
   //$(document).on("keydown", disableF5);
   </script>
   <?php } ?>

   <?php // Admin-Nachricht ?>
   <?php if (isset($_SESSION['admin_msg'])) { ?>
   <script>
   Royalart.feedback('<?php echo $_SESSION['admin_msg']; ?>', 360);
   Royalart.feedbackShow();
   </script>
   <?php unset($_SESSION['admin_msg']); ?>
   <?php } ?>

   <script>
   var gridContainer    = $('#article_container');
   var filtersContainer = $('#filters_container');

   var cube      = null;
   var reload    = false;
   var do_reload = true;

   var cubeportfolioOptions = {
      displayType: '<?php echo $params->firma['cbp_display'] != 'default' ? $params->firma['cbp_display'] : 'lazyLoading'; ?>',
   //   displayType: 'default',
   //   displayType: 'fadeIn',
   //   displayType: 'lazyLoading',      //Default
   //   displayType: 'fadeInToTop',
   //   displayType: 'sequentially',
   //   displayType: 'bottomToTop',

      displayTypeSpeed: 25, // ms, default:400

      <?php if (defined('CONF_MODULE_MARKENFILTER') && isset($params->firma['cbp_animation'])) { ?>
      animationType: '<?php echo $params->firma['cbp_animation']; ?>',
      <?php } ?>
      <?php
      $zoomfaktor = 1;
      if (defined('CONF_THUMB_ZOOM')) {
         $zoomfaktor = CONF_THUMB_ZOOM;
      }
      ?>
      <?php if ($params->firma['zoom_artikel'] == 'y') { ?>
      caption: 'zoom',
      <?php } else { ?>
   //   caption: 'pushTop',
   //   caption: 'pushDown',
   //   caption: 'revealBottom',
   //   caption: 'revealTop',
   //   caption: 'moveRight',
   //   caption: 'moveLeft',
   //   caption: 'overlayBottomPush',
   //   caption: 'overlayBottom',
   //   caption: 'overlayBottomReveal',
   //   caption: 'overlayBottomAlong',
   //   caption: 'overlayRightAlong',
   //   caption: 'minimal',
   //   caption: 'fadeIn',
   //   caption: 'zoom',
      caption: 'opacity',
      <?php } ?>

      gapHorizontal: <?php echo $artikel_abstand_h; ?>,
      gapVertical: <?php echo $artikel_abstand_v; ?>,
      gridAdjustment: 'responsive',
      singlePageDeeplinking: true,
      mediaQueries: [
   <?php if ($params->firma['cpf_size'] == 'klein' || $params->firma['cpf_size'] == 'klein_prop') { ?>
       {width: 3690, cols: 12},
       {width: 3320, cols: 11},
       {width: 2950, cols: 10},
       {width: 2580, cols: 9},
       {width: 2210, cols: 8},
       {width: 1840, cols: 7},
       {width: 1470, cols: 6},
       {width: 1100, cols: 5},
       {width: 800, cols: 4},
       {width: 730, cols: 3},
       {width: 550, cols: 2}, // Added
       {width: 380, cols: 1}
   <?php } else if ($params->firma['cpf_size'] == 'gross') { ?>
       {width: 3320, cols: 7},
       {width: 2950, cols: 6},
       {width: 2210, cols: 5},
       {width: 1840, cols: 4},
       {width: 1100, cols: 3},
       {width: 730, cols: 2},
       {width: 420, cols: 1}
   <?php } else if ($params->firma['cpf_size'] == 'riesig') { ?>
       {width: 3690, cols: 6},
       {width: 2950, cols: 5},
       {width: 2210, cols: 4},
       {width: 1470, cols: 3},
       {width: 730, cols: 2},
       {width: 420, cols: 1}
   <?php } else { ?>
       {width: 3690, cols: 11},
       {width: 3320, cols: 10},
       {width: 2950, cols: 9},
       {width: 2580, cols: 8},
       {width: 2210, cols: 7},
       {width: 1840, cols: 6},
       {width: 1470, cols: 5},
       {width: 1100, cols: 4},
       {width: 730, cols: 3},
       {width: 500, cols: 2},
       {width: 380, cols: 1}
   <?php } ?>
       ]
   };
   <?php if ($params->task == 'artikel') { ?>
   var cubeportfolioOptions_details = {
      displayType: 'bottomToTop',

      mediaQueries: [
         {width: 550, cols: 5},
         {width: 440, cols: 4},
         {width: 330, cols: 3},
         {width: 220, cols: 2}
      ],
      caption: 'zoom',
      displayTypeSpeed: 100, // ms, default:400
      gapHorizontal: 1,
      gapVertical: 1,
      gridAdjustment: 'responsive',
      singlePageDeeplinking: true
   };

   if ($('#details_container').length) {  // Artikel-Details
   //   $(window).on('load', function() {
      $(function() {
         // titleSize($('#details_container'));
         $('#details_container').cubeportfolio(cubeportfolioOptions_details);
      });
   }

   <?php } else { ?>
   if ( $('#article_container').length) { // Artikelliste
      $(window).on('load', function() {
         $('#article_container').cubeportfolio(cubeportfolioOptions, function() { checkArtikelAnzahlStart(); });
         titleSize($('#article_container'));
      });
   }
   <?php } ?>
   <?php if ($is_zubehoer) { // Zubehör-Artikel?>
   if ( $('#zubehoer_container').length) {
      $(window).on('load', function() {
         $('#zubehoer_container').cubeportfolio(cubeportfolioOptions);
         titleSize($('#zubehoer_container'));
      });
   }
   <?php } ?>
   <?php if ($is_aehnliche) { // Ähnliche Artikel?>
   if ( $('#aehnliche_container').length) {
      $(window).on('load', function() {
         $('#aehnliche_container').cubeportfolio(cubeportfolioOptions);
         titleSize($('#aehnliche_container'));
      });
   }
   <?php } ?>
   <?php if ($is_lastseen) { // Zuletzt angesehen?>
   if ( $('#lastseen_container').length) {
      $(window).on('load', function() {
         $('#lastseen_container').cubeportfolio(cubeportfolioOptions);
         titleSize($('#lastseen_container'));
      });
   }
   <?php } ?>
   <?php if ($params->task == 'artikel') { ?>
   var cubeportfolioOptions_popup = {
      displayType: 'bottomToTop',

      mediaQueries: [
         {width: 550, cols: 2},
         {width: 440, cols: 2},
         {width: 330, cols: 2},
         {width: 220, cols: 2}
      ],
      caption: 'zoom',
      displayTypeSpeed: 100, // ms, default:400
      gapHorizontal: 0,
      gapVertical: 10,
      gridAdjustment: 'responsive',
      singlePageDeeplinking: true
   };
   <?php } ?>
   </script>

   <?php echo $script; ?>

   <?php if (isset($articles->script) && $articles->script) { ?>
   <script>
   <?php echo $articles->script; ?>
   </script>
   <?php } ?>

   <?php if (isset($params->details_script) && $params->details_script != '') { ?>
   <?php echo $params->details_script; ?>
   <?php } ?>

   <?php // Script immer vorhanden ?>
   <?php if (defined('CONF_MODULE_HEADERSCRIPT') && is_file(TEMPLATE_PATH.'/save/save/headerscript.inc.php')) { ?>
   <?php include TEMPLATE_PATH.'/save/save/headerscript.inc.php'; ?>
   <?php } ?>

   <?php // Script nur nach Zustimmung Cookie ?>
   <?php if (defined('CONF_MODULE_HEADERSCRIPT') && isset($_SESSION['cookie_check']) && ($_SESSION['cookie_check'] == 'settings' || $_SESSION['cookie_check'] == 'y') && $params->firma['cookie_check'] == 'p') {
      if (is_file(TEMPLATE_PATH.'/save/save/headerscript2.inc.php')) {
         include TEMPLATE_PATH.'/save/save/headerscript2.inc.php';
      }

//      if (isset($_SESSION['cookie_marketing']) && $_SESSION['cookie_marketing'] || isset($_SESSION['cookie_funktionell']) && $_SESSION['cookie_funktionell']) {
      if (isset($_SESSION['cookie_marketing']) && $_SESSION['cookie_marketing']) {
         $cookie_settings = \KANPAICLASSIC\Helper::cookieSettings();
         echo $cookie_settings->marketing_script.CR;
         echo CR;
      }

      if (isset($_SESSION['cookie_funktionell']) && $_SESSION['cookie_funktionell']) {
         $cookie_settings = \KANPAICLASSIC\Helper::cookieSettings();
         echo $cookie_settings->funktionell_script.CR;
         echo CR;
      }
   } ?>

   <?php // Script für Slideshow ausgeben, wenn Slideshow aktiv ?>
   <?php if ($startseite && $slideshow == 2) { ?>
   <script src="<?php echo SHOP_URL; ?>/js/jquery.cycle2.js"></script>
   <script>
      var slideshow_started = false;

      <?php if ($params->firma['rechts_slide'] == 'n') { // fade ?>
      $(window).on('load', function() {
         $('img', $('#slideshow')).show();

         $('#slideshow').cycle({
            timeout      : 6000,
            speed        : 1000,
            manualSpeed  : 500,
            loader       : 'true',
            fx           : 'fade',
            easing       : 'linear',
            pagerEvent   : 'mouseover',
            pauseOnHover : true,
            maxZ         : 49,
         });
      });
      <?php } else { ?>
      $(window).on('load', function() { // rechts
         $('img', $('#slideshow')).show();

         $('#slideshow').cycle({
            timeout      : 6000,
            speed        : 2000,
            manualSpeed  : 1000,
            loader       : 'true',
            fx           : 'scrollHorz',
            easing       : 'easeOutCubic',
            pagerEvent   : 'mouseover',
            pauseOnHover : true
         });

         $('.cycle-next').show();
         $('.cycle-prev').show();
         $('.cycle-pager').show();

      });
      <?php } ?>

      // Nach Initialisierung und Slide-Wechsel
      $(window).on('cycle-update-view', function(e, opts) {
         // Höhe anpassen beim Start anpassen (notwendig bei Menü links)
         if (!slideshow_started && $('#slideshow').height() > 10 && $('#slideshow').height() < 10000) {
            sameHeight();
            slideshow_started = true;
         }

         var slide = $('.cycle-slide-active', $('#slideshow'));
         var slide_h = Math.round(slide.height() / 4);

         if (slide_h > 65) {
            slide_h = 65;
         }

         if ($(slide).attr('data-text') !== undefined) {
            $('#slideshow_text').html($(slide).attr('data-text'));
            $('#slideshow_text').css('color', $(slide).attr('data-color'));
            $('#slideshow_text').css('background-color', $(slide).attr('data-bg'));
            $('#slideshow_text').animate( {'height' : slide_h+'px' }, 1500, 'easeOutExpo');

         }
      });

      // Vor Slide-Wechsel
      $(window).on('cycle-before', function(e, opts) {
         $('#slideshow_text').animate( {'height' : 0 }, 250, 'swing');
      });
   </script>
   <?php } ?>

   <script>
   $(function () {
      $('[data-toggle="tooltip"]').tooltip();
   });
   </script>

   <?php // MagicZoom ?>
   <?php if ($params->task == 'artikel' && (int)$params->firma['detailbild'] > 1) { ?>
   <script src="<?php echo SHOP_URL; ?>/js/magiczoomplus/magiczoomplus.js"></script>
   <script type="text/javascript">
   $(function() {
      $(MagicZoomPlus.items)[0].attributes['href'].value = '';

      setTimeout(function() {
         $('a.MagicZoomPlus').each(function() {
            $(this).css('cursor', 'pointer').delay(1000);
         });
      });
   });
   </script>
   <?php } ?>

   <?php // Modul Extended, falls aktiv auf Seite ?>
   <?php
   if ($carussell_top || $carussell_bottom || $carussell_center) {
      echo $isExtended->carussell_script;
   }
   if ($accordion_top || $accordion_bottom || $accordion_center) {
      echo $isExtended->accordion_script;
   }
   if ($slider_top || $slider_bottom || $slider_center) {
      echo $isExtended->slider_script;
   }
   if ($cross_slider) {
      echo $art_slider->get_cslider_script();
   }
   ?>
   <?php if ($startseite) { ?>
   <script>
   $(window).on('load', function() {
      window.setTimeout(sameHeight(), 200);
   });
   </script>
   <?php } ?>
   <script>
   window.onload = function () {
      if ($('#popup_wrapper').length) {
         multibox.content($('#popup_wrapper').html());
         multibox.width("auto");
         //multibox.fixed(true);
         multibox.close_btn = true;
         multibox.bg_close = true;
         multibox.show();

         $('#multibox_close').on('click', function() { popupCheck(); });
      }

      if ($('#popup_danke').length) {
         multibox.content($("#popup_danke").html());
         multibox.width("auto");
         //multibox.fixed(true);
         multibox.show();
      }
   }
   </script>
   <script>
   $(window).on('load', function() {
      $('.load_image').each(function() {
         $(this).attr('src', $(this).attr('data-src'));
      });
   });
   </script>
   <?php echo $boxscript; ?>
   <?php if (is_file(SHOP_PATH.'/demo.png')) { ?>
   <div style="position:fixed; top:0; left:0; right:0; bottom:0; z-index:1000; pointer-events:none; background-image:url(<?php echo SHOP_URL; ?>/demo.png); background-position:center center; background-size:100% 100%;"></div>
   <?php } ?>
   <link rel="stylesheet" href="<?php echo TEMPLATE_URL; ?>/css/cubeportfolio.min.css<?php echo $params->firma['image_cache']; ?>" />
   <!-- <link rel="stylesheet" type="text/css" href="<?php echo SHOP_URL; ?>/js/fancybox/jquery.fancybox.css" /> -->
   <?php if ($params->task == 'artikel' && (int)$params->firma['detailbild'] > 1) { ?>
   <link rel="stylesheet" href="<?php echo SHOP_URL; ?>/js/magiczoomplus/magiczoomplus.css" />
   <?php } ?>
   <?php // if ($params->task == 'warenkorb') { ?>
   <link rel="stylesheet" href="<?php echo SHOP_URL; ?>/fonts/fontawesome/css/all.css" />
   <?php // } ?>
   <?php flush(); ?>
<?php if (!isset($livedesigner)) { ?>
   </div>
</body>
</html>
<?php } else { ?>
   </div>
</div>
<?php }
