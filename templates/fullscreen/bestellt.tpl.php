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

list($breite, $hoehe) = getimagesize(TEMPLATE_PATH.'/images/system/bewerten_popup.jpg');

$script = '<style>
   .popup_img { height:100%; width:100%; max-height:'.$hoehe.'px; max-width:'.$breite.'px; background-size:100%; background-position:center; }
   .danke_btn1 { position:absolute; display:inline-block; width:25%; bottom:10%;  left:25%; height:40px; line-height:40px; text-align:center; cursor:pointer; }
   .danke_btn2 { position:absolute; display:inline-block; width:25%; bottom:10%; right:25%; height:40px; line-height:40px; text-align:center; cursor:pointer; }
   #multibox_content { overflow:unset !important; }
   @media screen and (max-width:500px) {
      .multibox_inner { overflow:unset !important; }
      .danke_btn1, .danke_btn2 { height:30px; line-height:30px; bottom:5%; }
   }
</style>';

$danke_img1 = TEMPLATE_URL.'/images/system/danke_seite.png';
$danke_img2 = TEMPLATE_URL.'/images/system/danke_seite.png';

if (file_exists(TEMPLATE_PATH.'/images/danke1.jpg')) {
   $danke_img1 = TEMPLATE_URL.'/images/danke1.jpg';
}

if (file_exists(TEMPLATE_PATH.'/images/danke1_'.$lang.'.jpg')) {
   $danke_img1 = TEMPLATE_URL.'/images/danke1_'.$lang.'.jpg';
}

if (file_exists(TEMPLATE_PATH.'/images/danke2.jpg')) {
   $danke_img2 = TEMPLATE_URL.'/images/danke2.jpg';
}

if (file_exists(TEMPLATE_PATH.'/images/danke2_'.$lang.'.jpg')) {
   $danke_img2 = TEMPLATE_URL.'/images/danke2_'.$lang.'.jpg';
}
?>
<?php // ********** Nur wenn Bewertungen aktiviert ?>
<?php if (defined('CONF_BEWERTUNG_MODE') && CONF_BEWERTUNG_MODE != 'none') { ?>
<div id="popup_danke" style="display:none;">
   <div id="popup_img">
      <div class="popup_img">
         <img id="popup_image" src="<?php echo TEMPLATE_URL; ?>/images/system/bewerten_popup.jpg" alt="" />
         <span class="bg_button col_button text_gross danke_btn1" onclick="siegelCheck(<?php echo $_SESSION['AFTERBUY_ID']; ?>);">Ja</span>
         <span class="text_gross danke_btn2" onclick="multibox.close();">Nein</span>
      </div>
   </div>
</div>
<?php } ?>
<div class="col_single col_height" id="bestellung">
   <div class="col_lsl_l">
      <div class="col_single bg_flaechen col_left_height_inner">
      <?php if ($params->links['danke1_link'] != '' ) { ?>
         <a href="<?php echo $params->links['danke1_link']; ?>"<?php echo ($params->links['danke1_intern'] == 'n' ? ' target="_blank"' : ''); ?>><img src="<?php echo $danke_img1; ?>" style="width:100%;" alt="<?php echo $params->links['danke1_seo']; ?>" title="<?php echo $params->links['danke1_seo']; ?>" /></a>
      <?php } else { ?>
         <img src="<?php echo $danke_img1; ?>" style="width:100%;" alt="<?php echo $params->links['danke1_seo']; ?>" title="<?php echo $params->links['danke1_seo']; ?>" />
      <?php } ?>
      </div>
      <div class="col_single">
         <?php if ($params->user_id > 0) { ?>
         <div class="bg_button col_button text_gross button55"><a class="col_button" href="<?php echo SHOP_URL_IDX; ?>/konto"><?php echo $text->get('button', 'bestellung'); ?></a></div>
         <?php } else { ?>
            <?php if (!defined('CONF_MODULE_PORTAL')) { ?>
            <?php $val = base64_encode((int)$params->re_id * 57 - 29); ?>
            <div class="bg_button col_button text_gross button55">
               <a class="col_button" href="<?php echo SHOP_URL_IDX.'/downloadb/'.$val; ?>"><?php echo $text->get('button', 'bestellung'); ?></a>
            </div>
            <?php } //else Portal { ?>
         <?php }?>
      </div>
   </div>

   <div class="col_lsl_m"></div>

   <div class="col_lsl_r">
      <div class="col_single bg_flaechen col_right_height_inner">
      <?php if ($params->links['danke2_link'] != '' ) { ?>
         <a href="<?php echo $params->links['danke2_link']; ?>"<?php echo ($params->links['danke2_intern'] == 'n' ? ' target="_blank"' : ''); ?>><img src="<?php echo $danke_img2; ?>" alt="" style="width:100%;" alt="<?php echo $params->links['danke2_seo']; ?>" title="<?php echo $params->links['danke2_seo']; ?>" /></a>
      <?php } else { ?>
         <img src="<?php echo $danke_img2; ?>" style="width:100%;"alt="<?php echo $params->links['danke2_seo']; ?>" title="<?php echo $params->links['danke2_seo']; ?>" />
      <?php } ?>
      </div>
      <div class="col_single">
         <div class="bg_button col_button text_gross button55"><a class="col_button" href="<?php echo SHOP_URL; ?>"><?php echo $text->get('button', 'einkaufen'); ?></a></div>
      </div>
   </div>
   <div class="clear"></div>

   <?php if (defined('CONF_MODULE_TRUSTEDSHOPS') && $params->firma['trustedshop'] != '') { ?>
   <div class="col_single">
      <div id="trustedShopsCheckout" style="display: none;">
      <span id="tsCheckoutOrderNr"><?php echo $_SESSION['TRUSTEDSHOPS']['order_id']; ?></span>
      <span id="tsCheckoutBuyerEmail"><?php echo $_SESSION['TRUSTEDSHOPS']['email_kunde']; ?></span>
      <span id="tsCheckoutOrderAmount"><?php echo $_SESSION['TRUSTEDSHOPS']['brutto']; ?></span>
      <span id="tsCheckoutOrderCurrency"><?php echo $_SESSION['TRUSTEDSHOPS']['waehrung']; ?></span>
      <span id="tsCheckoutOrderPaymentType"><?php echo $_SESSION['TRUSTEDSHOPS']['zahlart']; ?></span>
      <span id="tsCheckoutOrderEstDeliveryDate"><?php echo $_SESSION['TRUSTEDSHOPS']['lieferdatum']; ?></span></div>
   </div>
   <?php } ?>
</div>
<?php if (isset($_SESSION['danke_msg'])) { ?>
   <div class="col_single col_height" id="bestellung"><?php echo $_SESSION['danke_msg']; ?></div>
   <?php unset($_SESSION['danke_msg']); ?>
<?php } ?>
<?php if (defined('CONF_MODULE_CONVERSION')) { ?>
   <?php if (is_file(TEMPLATE_PATH.'/save/save/conversion.inc.php')) { ?>
      <?php include TEMPLATE_PATH.'/save/save/conversion.inc.php'; ?>
   <?php } ?>
   <?php $script .= \KANPAICLASSIC\Helper::trackingCode(); ?>
<?php } ?>
<?php unset($_SESSION['TRUSTEDSHOPS']); ?>
<?php if (isset($_SESSION['klarna_checkout_back'])) { ?>
<script>
   window.open('<?php echo SHOP_URL_IDX.'/ajax/klarna_checkout_back'; ?>', 'klarna_back', 'width=647,height=640,scrollbars=yes');
</script>

<?php // unset($_SESSION['klarna_checkout_back']); ?>
<?php }
