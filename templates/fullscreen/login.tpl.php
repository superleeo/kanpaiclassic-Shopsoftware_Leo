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
<?php
$hclass = '';
if (defined('CONF_MODULE_PORTAL')) {
   $hclass = ' loginh';
}
?>
<div id="login">
   <div class="col_single site_head bg_flaechen">
      <p class="ueberschrift text_max"><?php echo $text->get('menu', 'login'); ?>
         <span class="fliesstext text_normal form_err" ><?php echo (isset($_SESSION['anm_msg']) ? $_SESSION['anm_msg'] : ''); ?></span>
      </p>
      <?php unset($_SESSION['anm_msg']); ?>
   </div>

   <div class="col_single<?php echo $hclass; ?>">
      <div class="col_lsl_l col_left_height site_body">
         <div class="bg_flaechen">
            <div class="login_l<?php echo $hclass; ?>">
               <div class="txt_tit ueberschrift text_gross center">
                  <?php echo $text->get('login', 'kundeneu', 'lang'); ?>
               </div>
               <div class="center50">
                  <a class="col_button bg_button login_button text_gross" href="<?php echo SHOP_URL_IDX; ?>/anmelden"><?php echo $text->get('button', 'anmelden'); ?></a>
                  <?php if ($params->firma['amazon_login_check'] == 'y') { ?>
                     <div id="LoginWithAmazon" style="text-align:center;"></div>
                  <?php } ?>
                  <p class="vorteile fliesstext text_normal abstand_05"><br>
                     <?php echo $text->get('login', 'vorteil', 'lang'); ?>
                  </p>
                  <ul>
                     <li class="fliesstext text_normal"><span>-</span><?php echo $text->get('login', 'vorteil1', 'lang'); ?>
                     </li>
                     <li class="fliesstext text_normal"><span>-</span><?php echo $text->get('login', 'vorteil2', 'lang'); ?>
                     </li>
                     <li class="fliesstext text_normal"><span>-</span><?php echo $text->get('login', 'vorteil3', 'lang'); ?>
                     </li>
                     <li class="fliesstext text_normal"><span>-</span><?php echo $text->get('login', 'vorteil4', 'lang'); ?>
                     </li>
                     <li class="fliesstext text_normal"><span>-</span><?php echo $text->get('login', 'vorteil5', 'lang'); ?>
                     </li>
                  </ul>
               </div>
               <br /><br />

               <?php  if ($params->firma['gast_aktiv'] == 'y') { ?>
               <div class="center">
                  <div class="txt_tit ueberschrift text_gross center abstand_u10">
                     <?php echo $text->get('login', 'schnellkauf', 'lang'); ?>
                  </div>
                  <div class="center50">
                     <a class="col_button bg_button login_button text_gross" href="<?php echo SHOP_URL_IDX; ?>/schnellkauf"><?php echo $text->get('button', 'weiter'); ?></a>
                  </div>
               </div>
               <?php } ?>
            </div>
         </div>
      </div>

      <div class="col_lsl_m"></div>

      <div class="col_lsl_r col_right_height site_body">
         <div class="bg_flaechen">
            <div class="login_r<?php echo $hclass; ?>">
            <div class="txt_tit ueberschrift text_gross center">
               <?php echo $text->get('login', 'kundealt'); ?>
            </div>
            <div class="center50">
               <?php if ($params->loginerror === true) {  ?>
               <div class="fliesstext text_normal center form_err">
                  <?php if ($params->valid_user) {
                     echo $text->get('login', 'freischalten', 'lang');
                  }
                  else if (!$params->loginh) {
                     echo $text->get('login', 'fehler', 'lang');
                  } ?>
               </div>
               <?php } ?>
               <form action="<?php echo SHOP_URL_IDX; ?>/checklogin" method="post">
                  <div class="input_left">
                     <div class="fliesstext text_normal logintext"><?php echo $text->get('login', 'email', 'lang'); ?>&nbsp;</div>
                     <input type="text" class="text_formular text_normal" name="email" value="" />
                  </div>
                  <div class="input_left">
                     <div class="fliesstext text_normal text_normal logintext"><?php echo $text->get('login', 'pw', 'lang'); ?>&nbsp;</div>
                     <input type="password" class="text_formular text_normal" name="pass" value="" />
                  </div>
                  <button type="submit" class="col_button bg_button login_button text_gross" value=""><?php echo $text->get('button', 'anmelden'); ?></button>
               </form>
            </div>
            <br /><br />
            <div class="txt_tit ueberschrift text_gross center">
               <?php echo $text->get('login', 'vergessen', 'lang'); ?>
            </div>
            <div class="center50">
               <form action="<?php echo SHOP_URL_IDX; ?>/forgotten" method="post">
                  <div class="input_left">
                     <div class="fliesstext text_normal logintext"><?php echo $text->get('login', 'email', 'lang'); ?>&nbsp;</div>
                     <input type="text" class="text_formular text_normal" name="email" value="" />
                  </div>
                  <button type="submit"class="col_button bg_button login_button text_gross" value=""><?php echo $text->get('button', 'zusenden'); ?></button>
               </form>
            </div>
         </div>
         </div>
      </div>
      <div class="clear"></div>
   </div>

   <?php if (defined('CONF_MODULE_PORTAL')) { ?>
   <div class="login_body<?php echo $hclass; ?> col_single" style="margin-top:18px;">
      <div class="col_lsl_l col_right_height">
         <div class="bg_flaechen bg_fullheight">
            <div class="login_l login_h bg">
            <div class="txt_tit ueberschrift text_gross center abstand_u10">
               <?php echo $text->get('login', 'haendler'); ?>
            </div>
            <div class="center50">
               <a class="col_button bg_button login_button text_gross" href="<?php echo SHOP_URL_IDX; ?>/anmelden_haendler"><?php echo $text->get('button', 'anmelden'); ?></a>
               <br />
               <ul>
                  <li class="fliesstext text_normal"><span>-</span><?php echo $text->get('login', 'vorteilh1', 'lang'); ?></li>
                  <li class="fliesstext text_normal"><span>-</span><?php echo $text->get('login', 'vorteilh2', 'lang'); ?></li>
               </ul>
            </div>
         </div>
         </div>
      </div>

      <div class="col_lsl_m"></div>

      <div class="col_lsl_r col_left_height">
         <div class="bg_flaechen bg_fullheight">
            <div class="login_r login_h" >
               <div class="txt_tit ueberschrift txt_tit text_gross center abstand_u10">
                  <?php echo $text->get('login', 'kundealth'); ?>
               </div>
               <div class="center50">
                  <?php if ($params->loginerror === true) {  ?>
                  <div class="fliesstext abstand_u10 center text_normal adr_err">
                     <?php if ($params->valid_user) {
                        echo $text->get('login', 'freischalten', 'lang');
                     }
                     else if ($params->loginh) {
                        echo $text->get('login', 'fehler', 'lang');
                     } ?>
                  </div>
                  <?php } ?>
                  <form action="<?php echo SHOP_URL_IDX; ?>/checkloginh" method="post">
                     <div class="input_left">
                        <div class="fliesstext logintext text_normal"><?php echo $text->get('login', 'email', 'lang'); ?>&nbsp;</div>
                        <input type="text" class="text_formular text_normal" name="email" value="" />
                     </div>
                     <div class="input_left">
                        <div class="fliesstext logintext text_normal"><?php echo $text->get('login', 'pw', 'lang'); ?>&nbsp;</div>
                        <input type="password" class="text_formular text_normal" name="pass" value="" />
                     </div>
                     <button type="submit" class="col_button bg_button login_button text_gross" value=""><?php echo $text->get('button', 'anmelden'); ?></button>
                  </form>
               </div>
            </div>
         </div>
      </div>
      <div class="clear"></div>
   </div>
   <?php } ?>
   <div class="clear"></div>
</div>
<?php if ($params->firma['amazon_login_check'] == 'y') { ?>
<?php $amazon = KANPAICLASSIC\Control::getAmazon(); ?>
<script>
  window.onAmazonLoginReady = function() {
    amazon.Login.setClientId('<?php echo $amazon->client_id; ?>');
  };

  window.onAmazonPaymentsReady = function(){
    // render the button here
    var authRequest;

    OffAmazonPayments.Button('LoginWithAmazon', '<?php echo $amazon->seller_id; ?>', {
      type:  'LwA',
      color: 'DarkGray',
      size:  'medium',
      language: 'de-DE',

      authorization: function() {
        loginOptions = {scope: 'profile payments:widget payments:billing_address payments:shipping_address', popup: true};
        authRequest = amazon.Login.authorize (loginOptions, '<?php echo SHOP_URL_IDX; ?>/amazonLogin');
      },

      onError: function(error) {
         alert("The following error occurred: "+ error.getErrorCode()+ ' - ' + error.getErrorMessage());
      }
   });
}
</script>
<?php // <script async="async" src='https://static-eu.payments-amazon.com/OffAmazonPayments/de/sandbox/lpa/js/Widgets.js'></script> ?>
<?php } ?>
