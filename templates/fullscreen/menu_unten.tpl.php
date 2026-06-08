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

$hide_anm = false;
if (defined('CONF_MODULE_WEBSITE') && $params->firma['hide_anm'] == 'y') {
   $hide_anm = true;
}

$anmelden_check = $params->firma['anmelden_check'];

$button1    = \KANPAICLASSIC\Helper::getSeite('impressum');
$button2    = \KANPAICLASSIC\Helper::getSeite('kontakt2');
$button6r   = \KANPAICLASSIC\Helper::getSeite('datenschutz');
$button3a   = $text->get('menu', 'login');
$button3b   = $text->get('menu', 'logout');
$button4    = $text->get('menu', 'konto');
$button5    = \KANPAICLASSIC\Helper::getUeberUns(1);
$button6    = \KANPAICLASSIC\Helper::getUeberUns(2);
$button7    = \KANPAICLASSIC\Helper::getUeberUns(3);
$button8    = \KANPAICLASSIC\Helper::getUeberUns(4);
$button9    = \KANPAICLASSIC\Helper::getUeberUns(5);

$button9r   = \KANPAICLASSIC\Helper::getSeite('versand');
$button1r   = \KANPAICLASSIC\Helper::getSeite('agb');
$button2r   = \KANPAICLASSIC\Helper::getWiderruf(1);
$button3r   = \KANPAICLASSIC\Helper::getWiderruf(2);
$button4r   = \KANPAICLASSIC\Helper::getWiderruf(3);
$button5r   = \KANPAICLASSIC\Helper::getWiderruf(4);
$button8r   = \KANPAICLASSIC\Helper::getWiderruf(5);
$button7r   = \KANPAICLASSIC\Helper::getSeite('kundeninfo');
?>
<?php // Footer-Freundlich ?>
<?php if (!isset($params->firma['footer_mode']) || $params->firma['footer_mode'] == 'freundlich') { ?>
<div class="col_in_lsl_l">
   <?php if ($button1 != 'not found' && $button1 != 'not_active') { ?>
   <span class="menu_unten"><a class="menu_unten text_gross" href="<?php echo $params->getLink('impressum'); ?>"><?php echo $button1; ?></a></span>
   <?php } ?>
   <?php if ($button6r != 'not found' && $button6r != 'not_active') { ?>
      <span class="menu_unten"><a class="menu_unten text_gross" href="<?php echo $params->getLink('datenschutz'); ?>"><?php echo $button6r; ?></a></span>
   <?php } ?>
   <?php if ($button2 != 'not found' && $button2 != 'not_active') { ?>
      <span class="menu_unten"><a class="menu_unten text_gross" href="<?php echo $params->getLink('kontakt'); ?>"><?php echo $button2; ?></a></span>
   <?php } ?>
   <?php if (!$hide_anm && $anmelden_check == 'y') { ?>
      <?php if ($params->user_id > 0 && session_name() != 'flow_admin') { ?>
      <span class="menu_unten"><a class="menu_unten text_gross" href="<?php echo SHOP_URL_IDX; ?>/konto"><?php echo $button4; ?> </a></span>
      <span class="menu_unten"><a class="menu_unten text_gross" href="<?php echo SHOP_URL_IDX; ?>/logout"><?php echo $button3b; ?> </a></span>
   <?php } else { ?>
      <span class="menu_unten"><a class="menu_unten text_gross" href="<?php echo SHOP_URL_IDX; ?>/login"><?php echo $button3a; ?> </a></span>
      <?php } ?>
   <?php } ?>

   <div class="show_tablet">
   <?php if ($button5 != 'not found' && $button5 != 'not_active') { ?>
      <span class="menu_unten"><a class="menu_unten text_gross" href="<?php echo $params->getLink(KANPAICLASSIC\Helper::checkLink($button5)); ?>"><?php echo $button5; ?> </a></span>
   <?php } ?>
   <?php if ($button6 != 'not found' && $button6 != 'not_active') { ?>
      <span class="menu_unten"><a class="menu_unten text_gross" href="<?php echo $params->getLink(KANPAICLASSIC\Helper::checkLink($button6)); ?>"><?php echo $button6; ?> </a></span>
   <?php } ?>
   <?php if ($button7 != 'not found' && $button7 != 'not_active') { ?>
      <span class="menu_unten"><a class="menu_unten text_gross" href="<?php echo $params->getLink(KANPAICLASSIC\Helper::checkLink($button7)); ?>"><?php echo $button7; ?> </a></span>
   <?php } ?>
   </div>
   <?php if ($button8 != 'not found' && $button8 != 'not_active') { ?>
      <span class="menu_unten"><a class="menu_unten text_gross" href="<?php echo $params->getLink(KANPAICLASSIC\Helper::checkLink($button8)); ?>"><?php echo $button8; ?> </a></span>
   <?php } ?>
   <?php if ($button9 != 'not found' && $button9 != 'not_active') { ?>
      <span class="menu_unten"><a class="menu_unten text_gross" href="<?php echo $params->getLink(KANPAICLASSIC\Helper::checkLink($button9)); ?>"><?php echo $button9; ?> </a></span>
   <?php } ?>
</div>
<div class="col_in_lsl_m"></div>
<div class="col_in_lsl_r">
   <?php if ($params->firma['hide_anm'] != 'y') { ?>
      <?php if ($button9r != 'not found' && $button9r != 'not_active') { ?>
      <span class="menu_unten"><a href="<?php echo $params->getLink('versand'); ?>" class="menu_unten text_gross"><?php echo $button9r; ?> </a></span>
      <?php } ?>
   <?php } ?>
   <?php if ($button1r != 'not found' && $button1r != 'not_active') { ?>
   <span class="menu_unten"><a class="menu_unten text_gross" href="<?php echo $params->getLink('agb'); ?>"><?php echo $button1r; ?></a></span>
   <?php } ?>
   <?php if ($params->firma['kundeninfo_check'] == 'y') { ?>
      <?php if ($button7r != 'not found' && $button7r != 'not_active') { ?>
      <span class="menu_unten"><a class="menu_unten text_gross" href="<?php echo SHOP_URL_IDX; ?>/kundeninfo"><?php echo $button7r; ?> </a></span>
      <?php } ?>
   <?php } ?>
   <?php if ($params->firma['widerruf1_check'] == 'y') { ?>
   <span class="menu_unten"><a class="menu_unten text_gross" href="<?php echo $params->getLink(KANPAICLASSIC\Helper::checkLink('widerruf1')); ?>"><?php echo $button2r; ?></a></span>
   <?php } ?>
   <?php if ($params->firma['widerruf2_check'] == 'y') { ?>
   <span class="menu_unten"><a class="menu_unten text_gross" href="<?php echo $params->getLink(KANPAICLASSIC\Helper::checkLink('widerruf2')); ?>"><?php echo $button3r; ?></a></span>
   <?php } ?>
   <?php if ($params->firma['widerruf3_check'] == 'y') { ?>
   <span class="menu_unten"><a class="menu_unten text_gross" href="<?php echo $params->getLink(KANPAICLASSIC\Helper::checkLink('widerruf3')); ?>"><?php echo $button4r; ?></a></span>
   <?php } ?>
   <?php if ($params->firma['widerruf4_check'] == 'y') { ?>
   <span class="menu_unten"><a class="menu_unten text_gross" href="<?php echo $params->getLink(KANPAICLASSIC\Helper::checkLink('widerruf4')); ?>"><?php echo $button5r; ?></a></span>
   <?php } ?>
   <?php if ($params->firma['widerruf5_check'] == 'y') { ?>
   <span class="menu_unten"><a class="menu_unten text_gross" href="<?php echo $params->getLink(KANPAICLASSIC\Helper::checkLink('widerruf5')); ?>"><?php echo $button8r; ?></a></span>
   <?php } ?>
   <?php if ($params->firma['sitemap_check'] == 'y') { ?>
   <span class="menu_unten"><a class="menu_unten text_gross" href="<?php echo SHOP_URL_IDX; ?>/sitemap.html">Sitemap</a></span>
   <?php } ?>
   <?php if (file_exists(SHOP_PATH.'/classes/modules/xdebug/reset_on')) { ?>
   <span class="menu_unten"><a class="menu_unten text_gross" href="<?php echo SHOP_URL_IDX; ?>/reset">RESET</a></span>
   <?php } ?>
</div>
<?php } else { ?>
<?php //Footer-Komplex ?>
<div class="">
   <?php if ($button1 != 'not found' && $button1 != 'not_active') { ?>
   <span class="menu_unten"><a class="menu_unten text_gross" href="<?php echo $params->getLink('impressum'); ?>"><?php echo $button1; ?></a></span>
   <?php } ?>
   <?php if ($button6r != 'not found' && $button6r != 'not_active') { ?>
   <span class="menu_unten"><a class="menu_unten text_gross" href="<?php echo $params->getLink('datenschutz'); ?>"><?php echo $button6r; ?></a></span>
   <?php } ?>
   <?php if ($button2 != 'not found' && $button2 != 'not_active') { ?>
   <span class="menu_unten"><a class="menu_unten text_gross" href="<?php echo $params->getLink('kontakt'); ?>"><?php echo $button2; ?></a></span>
   <?php } ?>

   <?php if (!$hide_anm && $anmelden_check == 'y') { ?>
      <?php if ($params->user_id > 0 && session_name() != 'flow_admin') { ?>
      <span class="menu_unten"><a class="menu_unten text_gross" href="<?php echo SHOP_URL_IDX; ?>/konto"><?php echo $button4; ?> </a></span>
      <span class="menu_unten"><a class="menu_unten text_gross" href="<?php echo SHOP_URL_IDX; ?>/logout"><?php echo $button3b; ?> </a></span>
   <?php } else { ?>
      <span class="menu_unten"><a class="menu_unten text_gross" href="<?php echo SHOP_URL_IDX; ?>/login"><?php echo $button3a; ?> </a></span>
      <?php } ?>
   <?php } ?>
   <br />

   <div class="show_tablet">
   <?php if ($button5 != 'not found' && $button5 != 'not_active') { ?>
      <span class="menu_unten"><a class="menu_unten text_gross" href="<?php echo $params->getLink(KANPAICLASSIC\Helper::checkLink($button5)); ?>"><?php echo $button5; ?> </a></span>
   <?php } ?>
   <?php if ($button6 != 'not found' && $button6 != 'not_active') { ?>
      <span class="menu_unten"><a class="menu_unten text_gross" href="<?php echo $params->getLink(KANPAICLASSIC\Helper::checkLink($button6)); ?>"><?php echo $button6; ?> </a></span>
   <?php } ?>
   <?php if ($button7 != 'not found' && $button7 != 'not_active') { ?>
      <span class="menu_unten"><a class="menu_unten text_gross" href="<?php echo $params->getLink(KANPAICLASSIC\Helper::checkLink($button7)); ?>"><?php echo $button7; ?> </a></span>
   <?php } ?>
   </div>

   <?php if ($button8 != 'not found' && $button8 != 'not_active') { ?>
      <span class="menu_unten"><a class="menu_unten text_gross" href="<?php echo $params->getLink(KANPAICLASSIC\Helper::checkLink($button8)); ?>"><?php echo $button8; ?> </a></span>
   <?php } ?>
   <?php if ($button9 != 'not found' && $button9 != 'not_active') { ?>
      <span class="menu_unten"><a class="menu_unten text_gross" href="<?php echo $params->getLink(KANPAICLASSIC\Helper::checkLink($button9)); ?>"><?php echo $button9; ?> </a></span>
   <?php } ?>
<br />
</div>
<div class="">
   <?php if ($params->firma['hide_anm'] != 'y') { ?>
      <?php if ($button9r != 'not found' && $button9r != 'not_active') { ?>
      <span class="menu_unten"><a href="<?php echo $params->getLink('versand'); ?>" class="menu_unten text_gross"><?php echo $button9r; ?> </a></span>
      <?php } ?>
   <?php } ?>
   <?php if ($button1r != 'not found' && $button1r != 'not_active') { ?>
   <span class="menu_unten"><a class="menu_unten text_gross" href="<?php echo $params->getLink('agb'); ?>"><?php echo $button1r; ?></a></span>
   <?php } ?>
   <?php if ($params->firma['kundeninfo_check'] == 'y') { ?>
   <span class="menu_unten"><a class="menu_unten text_gross" href="<?php echo SHOP_URL_IDX; ?>/kundeninfo"><?php echo $button7r; ?> </a></span>
   <?php } ?>
   <?php if ($params->firma['widerruf1_check'] == 'y') { ?>
   <span class="menu_unten"><a class="menu_unten text_gross" href="<?php echo $params->getLink(KANPAICLASSIC\Helper::checkLink('widerruf1')); ?>"><?php echo $button2r; ?></a></span>
   <?php } ?>
   <?php if ($params->firma['widerruf2_check'] == 'y') { ?>
   <span class="menu_unten"><a class="menu_unten text_gross" href="<?php echo $params->getLink(KANPAICLASSIC\Helper::checkLink('widerruf2')); ?>"><?php echo $button3r; ?></a></span>
   <?php } ?>
   <?php if ($params->firma['widerruf3_check'] == 'y') { ?>
   <span class="menu_unten"><a class="menu_unten text_gross" href="<?php echo $params->getLink(KANPAICLASSIC\Helper::checkLink('widerruf3')); ?>"><?php echo $button4r; ?></a></span>
   <?php } ?>
   <?php if ($params->firma['widerruf4_check'] == 'y') { ?>
   <span class="menu_unten"><a class="menu_unten text_gross" href="<?php echo $params->getLink(KANPAICLASSIC\Helper::checkLink('widerruf4')); ?>"><?php echo $button5r; ?></a></span>
   <?php } ?>
   <?php if ($params->firma['widerruf5_check'] == 'y') { ?>
   <span class="menu_unten"><a class="menu_unten text_gross" href="<?php echo $params->getLink(KANPAICLASSIC\Helper::checkLink('widerruf5')); ?>"><?php echo $button8r; ?></a></span>
   <?php } ?>
   <?php if ($params->firma['sitemap_check'] == 'y') { ?>
   <span class="menu_unten"><a class="menu_unten text_gross" href="<?php echo SHOP_URL_IDX; ?>/sitemap.html">Sitemap</a></span>
   <?php } ?>
   <?php if (file_exists(SHOP_PATH.'/classes/modules/xdebug/reset_on')) { ?>
      <span class="menu_unten"><a class="menu_unten text_gross" href="<?php echo SHOP_URL_IDX; ?>/reset">RESET</a></span>
   <?php } ?>
</div>
<?php } ?>
<div class="clear"></div>
