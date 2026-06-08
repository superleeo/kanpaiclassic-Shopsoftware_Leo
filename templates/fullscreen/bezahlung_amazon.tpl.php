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

$amazon = KANPAICLASSIC\Control::getAmazon();
?>
<div class="col_single">
   <div class="col_single_center">
      <div class="col_single ueberschrift text_gross center">
         <?php echo $text->get('bezahlung_11', 'subtitel'); ?>
      </div>

      <div class="col_single">
         <form id="form_bestellt" method="post" action="<?php echo SHOP_URL_IDX; ?>/bestellt">
            <div class="line">&nbsp;</div>

            <div class="line">
               <div class="line_left fliesstext text_normal"><?php echo $text->get('bezahlung', 'gesamt'); ?></div>
               <div class="line_center"></div>

               <?php if ($params->firma['price_login'] == 'y' && $params->user_id == 0) { ?>
               <div class="line_right fliesstext text_normal"><img src="<?php echo TEMPLATE_URL.'/images/system/btn_preis_nl_' . $params->selected_lang . '.jpg'; ?>" /></div>
               <?php } else { ?>
               <div class="line_right fliesstext text_normal"><?php echo \KANPAICLASSIC\Helper::number_format($gesamt_show, 2, ',', '.'). ' '.$params->waehrung; ?></div>
               <?php } ?>
            </div>

            <div class="line">
               <div class="line_left fliesstext text_normal"><?php echo $text->get('adresse', 'bestnr'); ?></div>
               <div class="line_center"></div>
               <div class="line_right fliesstext text_normal"><?php echo $_SESSION['bestellnummer']; ?></div>
            </div>

            <div class="line">&nbsp;</div>


            <div id="payWithAmazonDiv" style="text-align:center;">
               <!-- <img src='<?php echo $amazon->button_url.$amazon->seller_id; ?>&size=x-large&color=orange' style="cursor: pointer;" /> -->
            </div>

            <div id="walletWidgetDiv" style="width:300px; height:300px; text-align:center; display:none;">
            </div>
            <input type="hidden" id="amazonOrderReferenceId" name="amazonOrderReferenceId" value="" />
            <div class="line">&nbsp;</div>

            <div class="line">
               <div id="button1" class="bg_button col_button text_gross button55" onclick="Royalart.checkAmazon(this);" style="width:100%; display:none;"><?php echo $text->get('warenkorb', 'senden'); ?><?php echo (defined('CONF_PAYPAL_SANDBOX') ? ' (Sandbox)' : ''); ?></div>
            </div>
         </form>
      </div>
   </div>
</div>
<script src="<?php echo $amazon->widgets_url.$amazon->seller_id; ?>"></script>
<script>
var amazonOrderReferenceId;

new OffAmazonPayments.Widgets.Button ({
   sellerId: '<?php echo $amazon->seller_id; ?>',
   useAmazonAddressBook: false,
   onSignIn: function(orderReference) {
      amazonOrderReferenceId = orderReference.getAmazonOrderReferenceId();
      getWallet();
      $('#amazonOrderReferenceId').val(amazonOrderReferenceId);
      $('#walletWidgetDiv').width($('#payWithAmazonDiv').width());
      $('#walletWidgetDiv').show();
      $('#button1').show();
      $('#payWithAmazonDiv img').hide();
   },
   design: {
      designMode: 'responsive'
   },
   onError: function(error) {
      // your error handling code
   }
}).bind("payWithAmazonDiv");
</script>

<script>
// Wallet widget
function getWallet() {
   new OffAmazonPayments.Widgets.Wallet({
      sellerId               : '<?php echo $amazon->seller_id; ?>',
      amazonOrderReferenceId : amazonOrderReferenceId,
      design: {
         designMode: 'responsive'
      }
   }).bind( 'walletWidgetDiv' );
}
</script>
