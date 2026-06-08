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
?>

      <?php if ($params->firma['newsletter_footer']     == 'y' || $params->firma['social_status'] != 'nein' || isset($livedesigner)) { ?>
      <div class="abstand_social">
         <div class="social_bottom content_center_nopad" data-desktop_id="<?php echo ( $params->firma['social_status'] == 'rechts' ? 'social_right' : 'social_bottom'); ?>" data-tablet_id="social_bottom" data-phone_id="social_bottom">
         <?php if (isset($livedesigner)) { ?>
            <div id="livedesigner_netzwerk">
               <div id="live_netzwerk"><?php echo $live_netzwerk; ?></div>
            </div>
         <?php }


            if ($params->firma['newsletter_footer']     == 'y'){
                        ?>
             <div id="newsletter_content" class="inlineblock">
                 <?php echo KANPAICLASSIC\Helper::getFooterNewsletter(); ?>
             </div>

             <?php

            }

            if ($params->firma['social_status'] != 'nein') { ?>
             <div id="social_content" class="inlineblock">
                 <?php echo KANPAICLASSIC\Helper::getSocial(); ?>
             </div>
         <?php } ?>
         </div>
         <div class="clear"></div>
      </div>
      <?php } ?>

      <?php if ($banner2_on && ($banner2_h > 0 || isset($livedesigner))) { ?>
         <div id="banner_2" class="livedesigner_pos padding_top" style="max-width:100%;">
      <?php if (isset($livedesigner)) { ?>
         <div id="livedesigner_banner2">
            <div id="live_bannerunten"><?php echo $live_bannerunten; ?></div>
         </div>
      <?php } ?>
            <?php if ($banner2_link != '') { ?>
            <a href="<?php echo $banner2_link; ?>"<?php echo ($banner2_intern ? ' target="_blank"' : '' ); ?>>
               <img src="<?php echo $banner2; ?>" id="banner2_img" data-responsive_nopad="" alt="<?php echo ($banner2_h > 0 ? $bannerseo2 : ''); ?>" title="<?php echo $bannerseo2; ?>" />
            </a>
            <?php } else { ?>
            <img src="<?php echo $banner2; ?>" id="banner2_img" data-responsive_nopad="" alt="<?php echo ($banner2_h > 0 ? $bannerseo2 : ''); ?>" title="<?php echo $bannerseo2; ?>" />
            <?php } ?>
         </div>
      <?php } ?>

      <footer id="footer1" class="<?php echo ($is_flaeche_footer ? 'bg_footer' : ''); ?>">
         <div class="footer content_center<?php echo (!$is_flaeche_footer ? ' bg_footer' : ''); ?>">
            <div class="col_single">
               <div class="footer_menu menu_unten col_lsl_l" style="position:relative;">
                  <?php if (isset($livedesigner)) { ?>
                  <div id="livedesigner_footerlinks">
                     <div id="live_footerlinks"><?php echo $live_footerlinks; ?></div>
                  </div>
                  <?php } ?>
                  <?php echo $menu_unten; ?>
               </div>
               <div class="col_lsl_m menu_unten"></div>
               <div class="col_lsl_r menu_unten footer_boxen">
                  <?php include SHOP_PATH.'/templates/boxen.tpl.php'; ?>
               </div>
               <div class="clearfix"></div>
            </div>

            <div id="footer_icons" class="livedesigner_pos">
            <?php if (isset($livedesigner)) { ?>
               <div id="livedesigner_icons">
                  <div id="live_icons"><?php echo $live_icons; ?></div>
               </div>
            <?php } ?>
               <div id="ld_icons">
                  <?php $footer_farbe = (isset($params->firma['footer_farbe']) && $params->firma['footer_farbe'] !== 'antrazit' ? $params->firma['footer_farbe'] : ''); ?>
                  <?php $pf = $params->firma; ?>
                  <?php $html = ''; require_once TEMPLATE_PATH.'/footer_icons.tpl.php'; echo $html; ?>
               </div>
            </div>

            <!-- <div class="footer_text fliesstext text_normal"> -->
            <div class="footer_text text_normal menu_unten_text edited livedesigner_pos">
               <?php if (isset($livedesigner)) { ?>
               <div id="livedesigner_footer">
                  <div id="live_footer"><?php echo $live_footer; ?></div>
               </div>
               <?php } ?>
               <div class=" text_normal menu_unten_text">
                  <?php echo $footer; ?>
               </div>
            </div>
            <div class="clear"></div>
            <div id="footer_abstand"></div>
            <p class="shopsoftware">
               <a class="text_klein menu_unten"
                  href="https://www.kanpaiclassic.com"
                  onClick="window.open(this.href, 'https://www.kanpaiclassic.com'); return false;"
                  data-toggle="tooltip"
                  data-placement="top"
                  data-html="true"
                  title="Kanpai Classic Shopsoftware,&#10;Shopsysteme, Webshop,&#10;Onlineshop erstellen">
                  Kanpai Classic KANPAICLASSIC
               </a>
               <br /><br /><br /><br />
            </p>
         </div>
   <?php
   if ($params->firma['show_coupon'] == 'y') {
      $hidden1 = '';
      $hidden2 = 'style="display:none;"';
      $user = \KANPAICLASSIC\Control::getUser();

      if ($params->user_id > 0 && $user->user['newsletter'] == 'y') {
         $hidden1 = 'style="display:none;"';
         $hidden2 = '';
      } ?>

         <div id="coupon" class="content_center">
            <img src="<?php echo TEMPLATE_URL ?>/images/system/coupon.png" alt="" />
            <div class="coupon_mail"><input type="email" class="text_formular text_normal" onClick="Royalart.checkCoupon('in')" onBlur="Royalart.checkCoupon('out')" id="coupon_mail" value="" placeholder="Your E-Mail" /></div>
            <input type="hidden" id="coupon_test" value="YOUR E-MAIL" />
            <div <?php echo $hidden1; ?> id="coupon_ok1" onClick="Royalart.coupon();"><img src="<?php echo TEMPLATE_URL.'/images/system/btn_coupon_'.strtolower($lang).'.jpg' ?>" alt="" /></div>
            <div <?php echo $hidden2; ?> id="coupon_ok2"><img src="<?php echo TEMPLATE_URL.'/images/system/btn_coupon_ok.jpg' ?>" alt="" /></div>
            <div class="coupon_ds fliesstext"><?php echo $text->get('kunde', 'lesen1'); ?>
               <a href="<?php echo SHOP_URL_IDX; ?>/datenschutz" target="_blank" class=" fliesstext"><?php echo $text->get('kunde', 'daten'); ?></a>
               <?php echo $text->get('kunde', 'lesen2'); ?>
            </div>
         </div>

   <?php  } ?>
      </footer> <?php // Page Bottom ?>
