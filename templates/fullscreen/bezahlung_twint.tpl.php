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

$_SESSION['twint_amount'] = number_format($gesamt_show, 2, '.', '');
$twint = \KANPAICLASSIC\Control::getModuleTwint();
$test = $twint->getPairing();
if ($test['status'] == 'ok') {
   $pairing_uuid = $test['pairing_uuid'];
   $token = $test['token'];
   $qrcode = $test['qrcode'];
}

?>
<link href="<?php echo SHOP_URL; ?>/classes/modules/twint/twint.css" rel="stylesheet" type="text/css" media="all" />
<div id="twint" class="col_single">
   <div class="col_single" style="padding:0 10%; box-sizing: border-box;">
      <div class="col_single">
         <form method="post" id="twint_form" action="<?php echo SHOP_URL_IDX; ?>/bestellt">
            <input type="hidden" id="pairing_uuid" value="<?php echo $pairing_uuid; ?>" />
            <div class="line">&nbsp;</div>



            <div class="line">
               <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
               <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
               <link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
               <div class="section nav">
                  <div class="wrap">
                     <div class="menu-item one-third column text-left text-uppercase">&nbsp;
                        <!-- <a class="back-link" href="#" target="_self">Zurück zur Bestellung</a> -->
                     </div>
                     <div class="menu-item one-third column text-center text-uppercase">
                        <a href="http://www.twint.ch" target="_blank"><div class="icon-info"></div><span class=text_normal fliesstext">Infos zu Twint</span></a>
                     </div>
                     <div class="menu-item one-third column text-right">
                        <a href="http://www.datatrans.ch" target="_blank"><div class="datatrans-logo"></div></a>
                     </div>
                  </div>
               </div>
               <div class="section nav-mobile hidden">
                  <div class="wrap">
                     <div class="menu-item one-third column text-left">
                        <a class="logo" href="http://www.twint.ch" target="_blank"><div class="twint-logo"></div></a>
                     </div>
                     <div class="menu-item one-third column text-center">
                        <a href="http://www.postfinance.ch" target="_blank" class=""><div class="postfinance-logo"></div></a>
                     </div>
                     <div class="menu-item one-third column text-right">
                        <a href="http://www.datatrans.ch" target="_blank" class=""><div class="datatrans-logo"></div></a>
                     </div>
                  </div>
               </div>
               <div class="section header">
                  <div class="wrap">
                     <div class="header-item text-left" style="width:100px;">
                        <a class="logo" href="http://www.twint.ch" target="_blank"><div class="twint-logo"></div></a>
                     </div>
                     <div class="header-item column" style="position:absolute; left:0; right:0; text-align:center;">
            <div class="line">
               <div class="line_left ueberschrift text_max"><?php echo $text->get('bezahlung', 'gesamt'); ?></div>
               <div class="line_center"></div>

               <?php if ($params->firma['price_login'] == 'y' && $params->user_id == 0) { ?>
               <div class="line_right fliesstext text_normal" style="text-align:left;"><img src="<?php echo TEMPLATE_URL.'/images/system/btn_preis_nl_' . $params->selected_lang . '.jpg'; ?>" /></div>
               <?php } else { ?>
               <div class="line_right ueberschrift text_max" style="text-align:left;"><?php echo \KANPAICLASSIC\Helper::number_format($gesamt_show, 2, ',', '.'). ' '.$params->waehrung; ?></div>
               <?php } ?>
            </div>
            <div class="line">
               <input type="hidden" name="bezahlung" value="twint" />
               <div class="line_left fliesstext text_gross"><?php echo $text->get('adresse', 'bestnr'); ?></div>
               <div class="line_center"></div>
               <div class="line_right fliesstext text_gross" style="text-align:left;"><?php echo $_SESSION['bestellnummer']; ?></div>
            </div>
                     </div>
                     <div class="header-item column text-right" style="position:absolute; right:0;">
                        <a href="http://www.postfinance.ch" target="_blank" class=""><div class="postfinance-logo"></div></a>
                     </div>
                  </div>
               </div>
               <div class="section header-mobile hidden">
                  <div class="row text-center">
            <div class="line">
               <div class="line_left ueberschrift text_max"><?php echo $text->get('bezahlung', 'gesamt'); ?></div>
               <div class="line_center"></div>

               <?php if ($params->firma['price_login'] == 'y' && $params->user_id == 0) { ?>
               <div class="line_right fliesstext text_normal" style="text-align:left;"><img src="<?php echo TEMPLATE_URL.'/images/system/btn_preis_nl_' . $params->selected_lang . '.jpg'; ?>" /></div>
               <?php } else { ?>
               <div class="line_right ueberschrift text_max" style="text-align:left;"><?php echo \KANPAICLASSIC\Helper::number_format($gesamt_show, 2, ',', '.'). ' '.$params->waehrung; ?></div>
               <?php } ?>
            </div>
            <div class="line">
               <div class="line_left fliesstext text_gross"><?php echo $text->get('adresse', 'bestnr'); ?></div>
               <div class="line_center"></div>
               <div class="line_right fliesstext text_gross" style="text-align:left;"><?php echo $_SESSION['bestellnummer']; ?></div>
            </div>
                  </div>
               </div>
               <div class="section page">
                     <div class="row grey-bg text-center">
                        <div class="wrap">
                           <div class="credentials">
                              <div class="credentials-box credential-token text-center text-uppercase float-lt">
                                 <p class="text_normal fliesstext">Token</p>
                                 <div class="credentials-item">
                                    <p class="credential-number"><?php echo substr($token, 0, 1); ?></p>
                                    <p class="credential-number"><?php echo substr($token, 1, 1); ?></p>
                                    <p class="credential-number"><?php echo substr($token, 2, 1); ?></p>
                                    <p class="credential-number"><?php echo substr($token, 3, 1); ?></p>
                                    <p class="credential-number"><?php echo substr($token, 4, 1); ?></p>
                                 </div>
                              </div>
                              <div class="credentials-box credential-txt text-center"><p class="text_normal fliesstext">oder</p></div>
                              <div class="credentials-box credential-qrcode text-center text-uppercase float-rt">
                                 <p class="text_normal fliesstext">QR - Code</p>
                                 <div class="credentials-item">
                                    <img src="<?php echo $qrcode; ?>" alt="qrcode" width="160" height="160">
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="row">
                           <div class="description-box">
                              <div><h1 class="description-title text_gross fliesstext">TWINT Zahlung bestätigen</h1></div>
                              <div class="description-item">
                                 <div class="description-icon icon-app"></div><div class="description-text text_normal fliesstext">1. Öffne die TWINT App auf deinem Smartphone</div>
                              </div>
                              <div class="description-item">
                                 <div class="description-icon icon-pay"></div><div class="description-text text_normal fliesstext">2. Wähle in der TWINT App das Menü «Bezahlen»</div>
                              </div>
                              <div class="description-item">
                                 <div class="description-icon icon-cam"></div><div class="description-text text_normal fliesstext">3. Gib dort den TOKEN ein oder scanne den QR-Code via Kamera-Icon</div>
                              </div>
                           </div>
                     </div>
               </div>
            </div>

            <div class="line center">
               <br />
            </div>

            <div class="bg_button col_button text_gross button55" onclick="location.href='<?php echo SHOP_URL_IDX; ?>/warenkorb';"><?php echo $text->get('button', 'abbruch'); ?></div>
         </form>
      </div>
   </div>
</div>
<?php $script .= '<script>twintWaitPairing();</script>';
