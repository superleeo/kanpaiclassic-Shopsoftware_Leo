<?php
/* Top Navigation — Tepan Restaurant Style
   Added restaurant nav links to the existing menu */
if (!defined('KANPAICLASSIC')) { define('KANPAICLASSIC', true); }

$hide_ml  = false; $hide_wk = false; $hide_anm = false;
if (defined('CONF_MODULE_WEBSITE') && $params->firma['hide_wk'] == 'y') { $hide_ml = true; $hide_wk = true; }
if (defined('CONF_MODULE_WEBSITE') && $params->firma['hide_anm'] == 'y') { $hide_anm = true; }

$button0 = str_replace(' ', '&nbsp;', $text->get('menu', 'kategorie'));
$button1 = str_replace(' ', '&nbsp;', $text->get('menu', 'home'));
$button2 = KANPAICLASSIC\Helper::getUeberUns(1);
$button3 = KANPAICLASSIC\Helper::getUeberUns(2);
$button4 = KANPAICLASSIC\Helper::getUeberUns(3);
$button5 = \KANPAICLASSIC\Helper::getSeite('kontakt');

$button6a  = str_replace(' ', '&nbsp;', $text->get('menu', 'login'));
$button6b  = str_replace(' ', '&nbsp;', $text->get('menu', 'logout'));
$button7   = str_replace(' ', '&nbsp;', $text->get('menu', 'konto'));
$button8   = '<span class="hide_mobile hide_tablet menu_oben text_gross">'.str_replace(' ', '&nbsp;', $text->get('menu', 'warenkorb')).'</span>&nbsp;<strong id="wk_count">'.($wkAnzahl > 0 ? $wkAnzahl : '').'&nbsp;</strong>';
$button8a  = $text->get('menu', 'warenkorb');
$button8b  = '&nbsp;<strong id="wk_count">'.($wkAnzahl > 0 ? $wkAnzahl : '').'</strong>&ensp;';
$button9   = $text->get('menu', 'merkliste');

// Restaurant navigation items — inline for Tepan design
$rest_nav = [
  ['label' => '餐厅介绍', 'url' => SHOP_URL_IDX.'/restaurant_home'],
  ['label' => '菜单',     'url' => SHOP_URL_IDX.'/menu'],
  ['label' => '预订',     'url' => SHOP_URL_IDX.'/reservation'],
  ['label' => '代金券',   'url' => SHOP_URL_IDX.'/vouchers'],
  ['label' => '商店',     'url' => SHOP_URL_IDX.'/merch'],
];
$impressum_link = SHOP_URL_IDX.'/impressum';

$logomenu = ''; $offset_top = 0; $breite = 0; $hoehe1 = 45; $hoehe2 = 45;
if (file_exists(TEMPLATE_PATH.'/images/logomenu_'.$lang.'.png')) {
   $logomenu = TEMPLATE_URL.'/images/logomenu_'.$lang.'.png';
   list($breite, $hoehe1) = getimagesize(TEMPLATE_PATH.'/images/logomenu_'.$lang.'.png');
   $hoehe2 = 26;
}
if (file_exists(TEMPLATE_PATH.'/images/logomenu_'.$lang.'.jpg')) {
   $logomenu = TEMPLATE_URL.'/images/logomenu_'.$lang.'.jpg';
   list($breite, $hoehe1) = getimagesize(TEMPLATE_PATH.'/images/logomenu_'.$lang.'.jpg');
   $hoehe2 = 26;
}
$hoehe1 = round($hoehe1 / 2); $breite = round($breite / 2); $offset_top = ($hoehe2 - 26) / 2;

$home_check     = (isset($params->firma['homebutton_check']) ? $params->firma['homebutton_check'] : 'y');
$kontakt_check  = (isset($params->firma['kontakt_check']) ? $params->firma['kontakt_check'] : 'y');
$anmelden_mode  = (isset($params->firma['anmelden_mode']) ? (int)$params->firma['anmelden_mode'] : 1);
$merkliste_mode = (isset($params->firma['merkliste_mode']) ? (int)$params->firma['merkliste_mode'] : 2);
$warenkorb_mode = (isset($params->firma['warenkorb_mode']) ? (int)$params->firma['warenkorb_mode'] : 1);
$suchfeld_mode  = (isset($params->firma['suchfeld_mode']) ? (int)$params->firma['suchfeld_mode'] : 1);
$flaggen_mode   = (isset($params->firma['flaggen_mode']) ? (int)$params->firma['flaggen_mode'] : 1);
$shop_burger    = ($params->firma['kategorien_links'] !== 'y' && $params->firma['kategorien_links'] !== 'l' && isset($params->firma['shop_check']) && $params->firma['shop_check'] == 'y' ? 'y' : 'n');
$icon_farbe     = (isset($params->firma['icon_farbe']) ? $params->firma['icon_farbe'] : 'weiss');

if ($anmelden_mode == 3) { $hide_anm = true; }
if ($merkliste_mode == 3) { $hide_ml = true; }
if ($warenkorb_mode == 3) { $hide_wl = true; }

$farbe = ($icon_farbe == 'weiss' ? '' : ' dunkel');
$farbe_inv = ($icon_farbe == 'weiss' ? ' dunkel' : ' weiss');
$show_curr = ($params->firma['check_w2'] == 'y' || $params->firma['check_w3'] == 'y' || $params->firma['check_w4'] == 'y');
$menu_img = '';
if (file_exists(TEMPLATE_PATH.'/images/system/btn_menu_'.strtolower($lang).'.png')) {
   $menu_img = ' style="background-image:url('.TEMPLATE_URL.'/images/system/btn_menu_'.strtolower($lang).'.png);"';
}
$flaggen = '';
?>
<!-- ====== TOP BAR: TEPAN RESTAURANT NAV ====== -->
<div id="tp-topbar" style="background:var(--tp-gray-900);color:var(--tp-white);font-family:var(--tp-font-sans);z-index:1000;position:relative;">
  <div class="tp-container" style="display:flex;align-items:center;justify-content:space-between;padding-top:0.75rem;padding-bottom:0.75rem;flex-wrap:wrap;gap:0.5rem;">
    <a href="/restaurant_home" style="color:var(--tp-white);text-decoration:none;font-family:var(--tp-font-serif);font-size:1.3rem;font-weight:700;white-space:nowrap;">
      妙味 <span style="color:var(--tp-red);">鉄板焼</span>
    </a>
    <nav style="display:flex;gap:0.25rem;flex-wrap:wrap;align-items:center;">
      <?php foreach($rest_nav as $item): ?>
      <a href="<?php echo $item['url']; ?>" style="color:var(--tp-gray-300);text-decoration:none;padding:0.4rem 0.9rem;border-radius:var(--tp-radius);font-size:0.9rem;font-weight:500;transition:all 0.2s;"
         onmouseover="this.style.color='#fff';this.style.background='rgba(255,255,255,0.1)'"
         onmouseout="this.style.color='';this.style.background=''"
      ><?php echo $item['label']; ?></a>
      <?php endforeach; ?>
      <a href="<?php echo $impressum_link; ?>" style="color:var(--tp-gray-400);text-decoration:none;padding:0.4rem 0.9rem;border-radius:var(--tp-radius);font-size:0.85rem;transition:all 0.2s;"
         onmouseover="this.style.color='#fff';this.style.background='rgba(255,255,255,0.1)'"
         onmouseout="this.style.color='';this.style.background=''"
      >Impressum</a>
    </nav>
  </div>
</div>

<!-- ====== ORIGINAL FLOW MENU (hidden on restaurant pages) ====== -->
<?php if (!in_array($params->task, ['restaurant_home','menu','reservation','vouchers','merch'])): ?>
<div id="top_menu_wrapper" class="<?php echo ($is_flaeche_header ? 'menuleiste ' : ''); ?><?php echo ($device == 'desktop' ? 'full' : 'small'); ?>"<?php echo $menu_img; ?>>
   <style>#flex3{width:100%;}</style>
   <div class="abstand_oben" style="position:relative;">
      <?php if (isset($livedesigner) && $params->firma['flaeche'] != 'y') { ?>
      <div id="livedesigner_abstandoben"><div id="live_abstandoben"><?php echo $live_abstandoben; ?></div></div>
      <?php } ?>
   </div>
   <div class="top_menu content_center<?php echo (!$is_flaeche_header ? ' menuleiste' : ''); ?>"<?php echo (!$device_detect ? ' style="opacity:0;"' : ''); ?>>
      <?php if (isset($livedesigner)) { ?>
      <div id="livedesigner_menu" class="content_center"><div id="live_menu_left"><?php echo $live_menu_left; ?></div><div id="live_menu_right"><?php echo $live_menu_right; ?></div></div>
      <?php } ?>
      <div class="top_menu_outer<?php echo $farbe; ?>">
         <div id="menu_h1" style="position:relative;display:flex;flex-flow:row wrap;">
            <?php if ($logomenu !== '') { ?>
            <div style="position:relative;z-index:20;flex:1 1 0%;max-width:<?php echo $breite; ?>px;height:<?php echo $hoehe1; ?>px;margin:auto;padding-right:10px;">
               <div class="logomenu"><a href="<?php echo SHOP_URL; ?>"><img src="<?php echo $logomenu.$params->firma['image_cache']; ?>" title="<?php echo $params->links['logomenuseo']; ?>" style="max-height:<?php echo $hoehe1; ?>px;" alt="<?php echo $params->links['logomenuseo']; ?>"/></a></div>
            </div>
            <?php } ?>
            <div id="flex2" style="position:relative;width:100%;flex:1 1 0%;align-self:center;height:<?php echo $hoehe2; ?>px;<?php echo ($suchfeld_mode == 4 && $logomenu != '' ? ' margin-left:-'.($breite + 20).'px;' : ''); ?>">

               <?php if ($shop_burger == 'y') { ?>
                  <?php if ($params->task == '') { ?>
               <div id="menupos_1x" class="menu_kat menu_katx <?php echo ($device == 'desktop' ? 'small' : 'full'); ?><?php echo ($device == 'mobile' ? ' mobile' : ''); ?>" style="<?php echo ($suchfeld_mode == 4 && $logomenu != '' ? 'margin-left:'.($breite + 20).'px;' : ''); ?>" onclick="Royalart.showShop();">
                  <span class="menu_oben menu_item text_gross" style="margin-top:<?php echo $offset_top; ?>px;"><span class="burger_text"><?php echo $button0; ?></span><span class="burger_menu"></span></span>
               </div>
                  <?php } else { ?>
               <div id="menupos_1x" class="menu_kat menu_katx <?php echo ($device == 'desktop' ? 'small' : 'full'); ?><?php echo ($device == 'mobile' ? ' mobile' : ''); ?>" style="<?php echo ($suchfeld_mode == 4 && $logomenu != '' ? 'margin-left:'.($breite + 20).'px;' : ''); ?>" onclick="location.href='<?php echo SHOP_URL_IDX; ?>/shop';">
                  <span class="menu_oben menu_item text_gross" style="margin-top:<?php echo $offset_top; ?>px;"><span class="burger_text"><?php echo $button0; ?></span><span class="burger_menu"></span></span>
               </div>
                  <?php } ?>
               <div id="menupos_1" class="menu_kat <?php echo ($device == 'desktop' ? 'full' : 'small'); ?><?php echo ($device == 'mobile' ? ' mobile' : ''); ?>" style="<?php echo ($suchfeld_mode == 4 && $logomenu != '' ? 'margin-left:'.($breite + 20).'px;' : ''); ?>">
                  <span id="resp_kat_show" class="menu_oben menu_item text_gross" style="margin-top:<?php echo $offset_top; ?>px;"><span class="burger_text"><?php echo $button0; ?></span><span class="burger_menu"></span></span>
               </div>
               <?php } else { ?>
               <div id="menupos_1" class="menu_kat <?php echo ($device == 'desktop' ? 'full' : 'small'); ?><?php echo ($device == 'mobile' ? ' mobile' : ''); ?>" style="<?php echo ($suchfeld_mode == 4 && $logomenu != '' ? 'margin-left:'.($breite + 20).'px;' : ''); ?>">
                  <span id="resp_kat_show" class="menu_oben menu_item text_gross" style="margin-top:<?php echo $offset_top; ?>px;"><span class="burger_text"><?php echo $button0; ?></span><span class="burger_menu"></span></span>
               </div>
               <?php } ?>

               <?php if($home_check == 'y') { ?>
               <div class="menu_no_kat <?php echo ($device == 'desktop' ? 'full' : 'small'); ?>" id="menupos_2" style="margin-top:<?php echo $offset_top; ?>px;<?php echo ($suchfeld_mode == 4 && $logomenu != '' && $shop_burger != 'y' ? ' margin-left:'.($breite + 20).'px;' : ''); ?>">
                  <span class="menu_oben"><a class="menu_oben menu_item text_gross" href="<?php echo SHOP_URL; ?>"><?php echo $button1; ?></a></span>
               </div>
               <?php } ?>

               <div class="menu_left <?php echo ($device == 'desktop' ? 'full' : 'small'); ?>" id="menupos_3" style="margin-top:<?php echo $offset_top; ?>px;<?php echo ($suchfeld_mode == 4 && $logomenu != '' && $shop_burger != 'y' && $home_check != 'y' ? ' margin-left:'.($breite + 20).'px;' : ''); ?>">
                  <?php if ($button2 != 'not found' && $button2 != 'not_active') { ?>
                  <span class="menu_oben"><a class="menu_oben menu_item menu_full text_gross" href="<?php echo $params->getLink(KANPAICLASSIC\Helper::checkLink($button2)); ?>"><?php echo $button2; ?> </a></span>
                  <?php } ?>
                  <?php if ($button3 != 'not found' && $button3 != 'not_active') { ?>
                     <span class="menu_oben"><a class="menu_oben menu_item menu_full text_gross" href="<?php echo $params->getLink(KANPAICLASSIC\Helper::checkLink($button3)); ?>"><?php echo $button3; ?> </a></span>
                  <?php } ?>
                  <?php if ($button4 != 'not found' && $button4 != 'not_active') { ?>
                     <span class="menu_oben"><a class="menu_oben menu_item menu_full text_gross" href="<?php echo $params->getLink(KANPAICLASSIC\Helper::checkLink($button4)); ?>"><?php echo $button4; ?> </a></span>
                  <?php } ?>
                  <?php if ($button5 != 'not found' && $button5 != 'not_active') { ?>
                     <span class="menu_oben"><a class="menu_oben menu_item menu_full text_gross" href="<?php echo $params->getLink('kontakt'); ?>"><?php echo $button5; ?> </a></span>
                  <?php } ?>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?php endif; ?>
