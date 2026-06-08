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
   define('KANPAICLASSIC', true);
}

$hide_ml  = false;
$hide_wk  = false;
$hide_anm = false;

if (defined('CONF_MODULE_WEBSITE') && $params->firma['hide_wk'] == 'y') {
   $hide_ml = true;
   $hide_wk = true;
}

if (defined('CONF_MODULE_WEBSITE') && $params->firma['hide_anm'] == 'y') {
   $hide_anm = true;
}

// Burger-Menu
$button0    = str_replace(' ', '&nbsp;', $text->get('menu', 'kategorie'));
$button1    = str_replace(' ', '&nbsp;', $text->get('menu', 'home'));
$button2    = KANPAICLASSIC\Helper::getUeberUns(1);
$button3    = KANPAICLASSIC\Helper::getUeberUns(2);
$button4    = KANPAICLASSIC\Helper::getUeberUns(3);
$button5    = \KANPAICLASSIC\Helper::getSeite('kontakt');

$button6a   = str_replace(' ', '&nbsp;', $text->get('menu', 'login'));
$button6b   = str_replace(' ', '&nbsp;', $text->get('menu', 'logout'));
$button7    = str_replace(' ', '&nbsp;', $text->get('menu', 'konto'));

$button8    = '<span class="hide_mobile hide_tablet menu_oben text_gross">'.str_replace(' ', '&nbsp;', $text->get('menu', 'warenkorb')).'</span>&nbsp;<strong id=\'wk_count\'>'.($wkAnzahl > 0 ? $wkAnzahl : '').'&nbsp;</strong>';
$button8a   = $text->get('menu', 'warenkorb');
$button8b   = '&nbsp;<strong id=\'wk_count\'>'.($wkAnzahl > 0 ? $wkAnzahl : '').'</strong>&ensp;';
$button9    = $text->get('menu', 'merkliste');

$logomenu    = '';
$offset_top  = 0;
$breite      = 0;
$hoehe1      = 45;
$hoehe2      = 45;

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

$hoehe1 = round($hoehe1 / 2);
$breite = round($breite / 2);

$offset_top    = ($hoehe2 - 26) / 2;

$home_check     = (isset($params->firma['homebutton_check']) ? $params->firma['homebutton_check'] : 'y');
$kontakt_check  = (isset($params->firma['kontakt_check']) ? $params->firma['kontakt_check'] : 'y');
$anmelden_mode  = (isset($params->firma['anmelden_mode']) ? (int)$params->firma['anmelden_mode'] : 1);
$merkliste_mode = (isset($params->firma['merkliste_mode']) ? (int)$params->firma['merkliste_mode'] : 2);
$warenkorb_mode = (isset($params->firma['warenkorb_mode']) ? (int)$params->firma['warenkorb_mode'] : 1);
$suchfeld_mode  = (isset($params->firma['suchfeld_mode'])  ? (int)$params->firma['suchfeld_mode'] : 1);
$flaggen_mode   = (isset($params->firma['flaggen_mode']) ? (int)$params->firma['flaggen_mode'] : 1);
$shop_burger    = ($params->firma['kategorien_links'] !== 'y' && $params->firma['kategorien_links'] !== 'l' && isset($params->firma['shop_check']) && $params->firma['shop_check'] == 'y' ? 'y' : 'n');
$icon_farbe     = (isset($params->firma['icon_farbe']) ? $params->firma['icon_farbe'] : 'weiss');

if ($anmelden_mode == 3) {
   $hide_anm = true;
}

if ($merkliste_mode == 3) {
   $hide_ml = true;
}

if ($warenkorb_mode == 3) {
   $hide_wl = true;
}

$farbe     = ($icon_farbe == 'weiss' ? '' : ' dunkel');
$farbe_inv = ($icon_farbe == 'weiss' ? ' dunkel' : ' weiss');

$show_curr      = false;

if ($params->firma['check_w2'] == 'y' || $params->firma['check_w3'] == 'y' || $params->firma['check_w4'] == 'y') {
   $show_curr = true;
}

$menu_img = '';

if (file_exists(TEMPLATE_PATH.'/images/system/btn_menu_'.strtolower($lang).'.png')) {
   $menu_img = ' style="background-image:url('.TEMPLATE_URL.'/images/system/btn_menu_'.strtolower($lang).'.png);"';
}

$flaggen = '';
?>
<div id="top_menu_wrapper" class="<?php echo ($is_flaeche_header ? 'menuleiste ' : ''); ?><?php echo ($device == 'desktop' ? 'full' : 'small'); ?>"<?php echo $menu_img; ?>>
   <style>
   #flex3 { width:100%; }
   </style>
   <div class="abstand_oben" style="position:relative;">
      <?php if (isset($livedesigner) && $params->firma['flaeche'] != 'y') { // Header bildschirmbreit ?>
      <div id="livedesigner_abstandoben">
         <div id="live_abstandoben"><?php echo $live_abstandoben; ?></div>
      </div>
   <?php } ?>
   </div>
   <div class="top_menu content_center<?php echo (!$is_flaeche_header ? ' menuleiste' : ''); ?>"<?php echo (!$device_detect ? ' style="opacity:0;"' : ''); ?>>
      <?php if (isset($livedesigner)) { ?>
      <div id="livedesigner_menu" class="content_center">
         <div id="live_menu_left"><?php echo $live_menu_left; ?></div>
         <div id="live_menu_right"><?php echo $live_menu_right; ?></div>
      </div>
      <?php } ?>
      <div class="top_menu_outer<?php echo $farbe; ?>">
         <!--   <menu> -->
         <div id="menu_h1" style="position:relative; display:flex; flex-flow:row wrap;">
            <?php // *********** Logo im Menu ?>
            <?php if ($logomenu !== '') { ?>
            <div style="position:relative; z-index:20; flex:1 1 0%; max-width:<?php echo $breite; ?>px; height:<?php echo $hoehe1; ?>px; margin:auto; padding-right:10px;">
               <div class="logomenu"><a href="<?php echo SHOP_URL; ?>"><img src="<?php echo $logomenu.$params->firma['image_cache']; ?>" title="<?php echo $params->links['logomenuseo']; ?>" style="max-height:<?php echo $hoehe1; ?>px;" alt="<?php echo $params->links['logomenuseo']; ?>"/></a></div>
            </div>
            <?php } ?>
            <div id="flex2" style="position:relative; width:100%; flex:1 1 0%; align-self:center; height:<?php echo $hoehe2; ?>px;<?php echo ($suchfeld_mode == 4 && $logomenu != '' ? ' margin-left:-'.($breite + 20).'px;' : ''); ?>">
               <?php // Startseite Shop-Check bei Desktop ?>

               <?php if ($shop_burger == 'y') { ?>
                  <?php if ($params->task == '') { ?>
               <div id="menupos_1x" class="menu_kat menu_katx <?php echo ($device == 'desktop' ? 'small' : 'full'); ?><?php echo ($device == 'mobile' ? ' mobile' : ''); ?>" style="<?php echo ($suchfeld_mode == 4 && $logomenu != '' ? 'margin-left:'.($breite + 20).'px;' : ''); ?>" onclick="Royalart.showShop();"><?php // Kategorien / Mobile, Tablet ?>
                  <span class="menu_oben menu_item text_gross" style="margin-top:<?php echo $offset_top; ?>px;"><span class="burger_text"><?php echo $button0; ?></span><span class="burger_menu"></span></span>
               </div>
                  <?php } else { ?>
               <div id="menupos_1x" class="menu_kat menu_katx <?php echo ($device == 'desktop' ? 'small' : 'full'); ?><?php echo ($device == 'mobile' ? ' mobile' : ''); ?>" style="<?php echo ($suchfeld_mode == 4 && $logomenu != '' ? 'margin-left:'.($breite + 20).'px;' : ''); ?>" onclick="location.href='<?php echo SHOP_URL_IDX; ?>/shop';">
                  <span class="menu_oben menu_item text_gross" style="margin-top:<?php echo $offset_top; ?>px;"><span class="burger_text"><?php echo $button0; ?></span><span class="burger_menu"></span></span>
               </div>
                  <?php } ?>

               <div id="menupos_1" class="menu_kat <?php echo ($device == 'desktop' ? 'full' : 'small'); ?><?php echo ($device == 'mobile' ? ' mobile' : ''); ?>" style="<?php echo ($suchfeld_mode == 4 && $logomenu != '' ? 'margin-left:'.($breite + 20).'px;' : ''); ?>"><?php // Kategorien / Mobile, Tablet ?>
                  <span id="resp_kat_show" class="menu_oben menu_item text_gross" style="margin-top:<?php echo $offset_top; ?>px;"><span class="burger_text"><?php echo $button0; ?></span><span class="burger_menu"></span></span>
               </div>
               <?php } else { ?>
               <div id="menupos_1" class="menu_kat <?php echo ($device == 'desktop' ? 'full' : 'small'); ?><?php echo ($device == 'mobile' ? ' mobile' : ''); ?>" style="<?php echo ($suchfeld_mode == 4 && $logomenu != '' ? 'margin-left:'.($breite + 20).'px;' : ''); ?>"><?php // Kategorien / Mobile, Tablet ?>
                  <span id="resp_kat_show" class="menu_oben menu_item text_gross" style="margin-top:<?php echo $offset_top; ?>px;"><span class="burger_text"><?php echo $button0; ?></span><span class="burger_menu"></span></span>
               </div>
               <?php } ?>

               <?php if($home_check == 'y') { ?>
               <div class="menu_no_kat <?php echo ($device == 'desktop' ? 'full' : 'small'); ?>" id="menupos_2" style="margin-top:<?php echo $offset_top; ?>px;<?php echo ($suchfeld_mode == 4 && $logomenu != '' && $shop_burger != 'y' ? ' margin-left:'.($breite + 20).'px;' : ''); ?>"><?php // Home / Desktop ?>
                  <span class="menu_oben"><a class="menu_oben menu_item text_gross" href="<?php echo SHOP_URL; ?>"><?php echo $button1; ?></a></span>
               </div>
               <?php } ?>

               <div class="menu_left <?php echo ($device == 'desktop' ? 'full' : 'small'); ?>" id="menupos_3" style="margin-top:<?php echo $offset_top; ?>px;<?php echo ($suchfeld_mode == 4 && $logomenu != '' && $shop_burger != 'y' && $home_check != 'y' ? ' margin-left:'.($breite + 20).'px;' : ''); ?>"><?php // Über uns / Desktop, Template ?>
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

               <?php if ($suchfeld_mode == 4) { ?>
               <div id="menu_center">
                  <form id="suchform_mitte" method="post" action="<?php echo rtrim(SHOP_URL_IDX, '/'); ?>?suchen=" onsubmit="$('#suche_mitte').val() !== '' && $('#suchform_mitte').attr('action', $('#suchform_mitte').prop('action')+$('#suche_mitte').val());">
                     <div class="suche_center">
                        <div class="suche_center_icon<?php echo $farbe_inv; ?>" onclick="$('#suche_mitte').val() !== '' && $('#suchform_mitte').submit();"></div>
                        <input type="text" class="text_formular text_normal" id="suche_mitte" name="suche" value="" />
                     </div>
                  </form>
               </div>
               <?php } ?>

               <div class="menu_right <?php echo ($device == 'desktop' ? 'full' : 'small'); ?><?php echo ($device == 'mobile' ? ' mobile' : ''); ?>" id="menupos_4" style="margin-top:<?php echo $offset_top; ?>px;"><?php // rechts ?>
                  <?php if (!$hide_anm) { ?>
                     <?php if ($params->user_id > 0 && session_name() != 'flow_admin') { // angemeldet ?>
                     <span class="menu_oben"><a class="menu_oben menu_item login text_gross" href="<?php echo SHOP_URL_IDX; ?>/konto"><?php echo $button7; ?> </a></span>
                     <?php } else { // nicht angemeldet?>
                     <a class="menu_item menu_full menu_login text_gross <?php echo ($anmelden_mode == 2 ? 'login_icon' : 'menu_oben'); ?>"
                        href="<?php echo SHOP_URL_IDX; ?>/login"
                        <?php echo ($anmelden_mode == 2 ? 'data-toggle="tooltip" data-placement="bottom" title="'.$button6a.'"' : ''); ?>
                     <?php if ($anmelden_mode == 1 && $merkliste_mode == 2) { ?>
                        style="margin-right:0;"
                     <?php } ?>
                        ><?php echo ($anmelden_mode != 2 ? $button6a : ''); ?> </a>
                     <?php } ?>
                  <?php } ?>

                  <?php if (!$hide_ml) { ?>
                     <span class="menu_oben"><a class="menu_item menu_full menu_merkliste text_gross <?php echo ($merkliste_mode == 2 ? 'merkliste_icon' : 'menu_oben'); ?>"
                        href="<?php echo SHOP_URL_IDX; ?>/merkliste"
                        <?php echo ($merkliste_mode == 2 ? 'data-toggle="tooltip" data-placement="bottom" title="'.$button9.'"' : ''); ?>
                     >
                        <?php echo ($merkliste_mode == 1 ? $button9 : ''); ?>
                     </a></span>
                  <?php } ?>

                  <?php if (!$hide_wk) { ?>
                     <span class="menu_oben"><a class="menu_item menu_full text_gross menu_oben <?php echo ($warenkorb_mode == 2 ? 'warenkorb_icon' : 'menu_warenkorb'); ?>"
                        href="<?php echo SHOP_URL_IDX; ?>/warenkorb"
                        <?php echo ($warenkorb_mode == 2 ? 'data-toggle="tooltip" data-placement="bottom" title="'.$button8a.'"' : ''); ?>
                     ><?php echo ($warenkorb_mode == 1 ? $button8 : '');
                     echo ($warenkorb_mode != 1 ? $button8b : ''); ?></a></span>
<?php /* nur Danielsen:                     <span id="wk_msg" style="display:inline-block; position:absolute; left:25%; bottom:0; width:1px; height:1px;" data-toggle="tooltip" data-placement="bottom" title="<?php echo $text->get('article', 'popup'); ?>"></span> */ ?>
                  <?php }

                  if ($suchfeld_mode == 1 || $suchfeld_mode == 2) {
                  // Suchfeld-Responsive am Ende der Datei
                  ?><form id="suchform" method="post" action="<?php echo rtrim(SHOP_URL_IDX, '/'); ?>?suchen=" onsubmit="$('#suche').val() !== '' && $('#suchform').attr('action', $('#suchform').prop('action')+$('#suche').val());">
                     <div id="suche_wrapper" class="<?php echo ($suchfeld_mode == 2 ? 'suchfeld_icon' : ''); ?>">
                        <?php // JS-Funktion auf top_menu_suche ?>
                        <div id="top_menu_suche" onclick="$('#suche').val() !== '' && $('#suchform').submit();"></div>
                        <div class="suche_input">
                           <input type="text" class="top_menu_input text_formular text_normal" id="suche" name="suche" value="" />
                        </div>
                     </div>
                  </form>
                  <?php } ?>

                  <?php // Währungen ?>
                  <?php if ($show_curr) { ?>
                     <div class="menu_oben text_gross" id="menu_waehrung">
                        <span class="waehrung"><?php echo KANPAICLASSIC\Helper::waehrungText($params->firma['waehrung'.$params->waehrung_id], 2); ?></span>
                        <span class="waehrung_mobile"><?php echo KANPAICLASSIC\Helper::waehrungText($params->firma['waehrung'.$params->waehrung_id], 1); ?></span>
                        <div id="menu_waehrung_sub" class="bg_responsive shadow" style="display:none;">
                           <?php if ($params->waehrung_id !== 1) { ?>
                           <a href="<?php echo SHOP_URL_IDX.'/currency/1'; ?>"><em class="haupt_kat_c sub_kat text_gross"><?php echo KANPAICLASSIC\Helper::waehrungText($params->firma['waehrung1'], 2); ?></em></a>
                           <?php } ?>
                           <?php for ($i = 2; $i < 5; $i++) { ?>
                              <?php if ($params->firma['check_w'.$i] == 'y' && $params->waehrung_id != $i) { ?>
                              <a href="<?php echo SHOP_URL_IDX.'/currency/'.$i; ?>"><em class="haupt_kat_c sub_kat text_gross"><?php echo KANPAICLASSIC\Helper::waehrungText($params->firma['waehrung'.$i], 2); ?></em></a>
                              <?php } ?>
                           <?php } ?>
                        </div>
                     </div>
                  <?php } ?>

                  <?php // Flaggen nur anzeigen, wenn mehr als 1 Sprache gewählt ist ?>
                  <?php if (count($langs) > 1 && $flaggen_mode != 3) {
                     foreach ($langs as $btn_lang) {
                        $ext = '';

                        if ($btn_lang == $lang) {
                           $ext = '-over';
                        }

                        if ($btn_lang == 'deu' || $btn_lang == 'eng') {
                           $flaggen .= '<span>';
                           $flaggen .= '   <a class="flagge flagge_'.$btn_lang.$ext.'" href="'.(!isset($livedesigner) ? SHOP_URL_IDX : ADMIN_URL_IDX).'/lang/'.$btn_lang.'"></a>'.CR;
                           $flaggen .= '</span>';
                        }
                        else {
                           $flaggen .= '<span>';
                           $flaggen .= '            <a class="flagge" onmouseover="this.style.backgroundImage = \'url('.TEMPLATE_URL.'/images/flaggen/'.$btn_lang.$ext.'.jpg)\';"'.CR;
                           $flaggen .= '                              onmouseout="this.style.backgroundImage = \'url('.TEMPLATE_URL.'/images/flaggen/'.$btn_lang.$ext.'.jpg)\';"'.CR;
                           $flaggen .= '                              style="background-image:url(\''.\TEMPLATE_URL.'/images/flaggen/'.$btn_lang.$ext.'.jpg\');"'.CR;
                           $flaggen .= '                              href="'.(!isset($livedesigner) ? SHOP_URL_IDX : ADMIN_URL_IDX).'/lang/'.$btn_lang.'"></a>'.CR;
                           $flaggen .= '</span>';
                        }
                     }
                     ?>

                     <?php if ($flaggen_mode == 1) { ?>
                  <div id="menu_flaggen">
                     <?php echo $flaggen; ?>
                  </div>
                     <?php } ?>
                  <div id="menu_flaggen_small"<?php echo ($flaggen_mode == 2 ? ' class="flaggen_icon"' : ''); ?>></div>
                  <?php } ?>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!--  </menu> -->

   <?php // JS-Funktion auf suchen_responsive ?>
   <div id="suchen_responsive" class="bg_responsive">
      <div class="content_center suche_input">
         <div class="content_suche">
            <form id="suchform2" method="post" action="<?php echo rtrim(SHOP_URL_IDX, '/'); ?>?suchen=" onsubmit="$('#suche2').val() !== '' && $('#suchform2').attr('action', $('#suchform2').prop('action')+$('#suche2').val());">
               <div class="suche2">
               <span class="bg_suche bg_responsive">
                  <input type="text" class="top_menu_input text_formular text_gross" id="suche2" name="suche" value="" />
               </span>
               </div>
            </form>
         </div>
      </div>
   </div>

   <?php if (count($langs) > 1) { ?>
   <div id="flaggen_responsive" class="bg_responsive">
      <div id="flaggen_menu" class="content_center">
         <div class="flaggen">
            <span class="bg_flaggen bg_responsive" style="width:<?php echo count($langs) *35; ?>px;">
            <?php echo $flaggen; ?>
            </span>
         </div>
         <div class="clearfix"></div>
      </div>
   </div>
   <?php } ?>
</div>

<?php if ($suchfeld_mode == 4) { ?>
<div id="menu_center_small" class="menuleiste">
   <form id="suchform_small" method="post" action="<?php echo rtrim(SHOP_URL_IDX, '/'); ?>?suchen=" onsubmit="$('#suche_small').val() !== '' && $('#suchform_small').attr('action', $('#suchform_small').prop('action')+$('#suche_small').val());">
      <div class="suche_center">
         <div class="suche_center_icon<?php echo $farbe_inv; ?>" onclick="$('#suche_small').val() !== '' && $('#suchform_small').submit();"></div>
         <input type="text" class="text_formular text_normal<?php echo $farbe_inv; ?>" id="suche_small" name="suche" value="" />
      </div>
   </form>
</div>
<?php }
