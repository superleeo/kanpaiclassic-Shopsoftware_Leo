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

$inhaber_err = '';
$iban_err = '';
$bic_err = '';
$name_err = '';

if(isset($_SESSION['err_bank_inhaber'])) {
   $inhaber_err = 'style="color:#ee0000;"';
}
if (isset($_SESSION['err_bank_iban'])) {
   $iban_err = 'style="color:#ee0000;"';
}
if (isset($_SESSION['err_bank_bic'])) {
   $bic_err = 'style="color:#ee0000;"';
}
if (isset($_SESSION['err_bank_name'])) {
   $name_err = 'style="color:#ee0000;"';
}

// Kontoinhaber mit Vorname Nachname vorbelegen, falls leer
$inhaber = $data['bank_inhaber'];
if ($inhaber == '') {
   $inhaber = $data['vorname'] . ' ' . $data['nachname'];
}
?>
<div class="col_single">
   <div class="col_single_center">
      <div class="col_single ueberschrift text_gross center">
         <?php echo $text->get('bezahlung_3', 'subtitel'); ?>
      </div>

      <div class="col_single" style="padding-left:20px; box-sizing:padding-box">
         <form method="post" action="<?php echo SHOP_URL_IDX; ?>/bestellt">
            <div class="line">&nbsp;</div>

            <div class="line">
               <div class="line_left ueberschrift text_max"><?php echo $text->get('bezahlung_0', 'betrag', 'lang'); ?></div>
               <div class="line_center"></div>
               <?php if ($params->firma['price_login'] == 'y' && $params->user_id == 0) { ?>
               <div class="line_right fliesstext text_normal"><img src="<?php echo TEMPLATE_URL . '/images/system/btn_preis_nl_' . $params->selected_lang . '.jpg'; ?>" /></div>
               <?php } else { ?>
               <div class="line_right ueberschrift text_max"><?php echo KANPAICLASSIC\Helper::number_format($gesamt_show, 2, ',', '.'). ' '.$params->waehrung; ?></div>
               <?php } ?>
            </div>

            <div class="line">
               <div class="line_left fliesstext text_normal"><?php echo $text->get('bezahlung_0', 'zweck'); ?></div>
               <div class="line_center"></div>
               <div class="line_right fliesstext text_gross"><?php echo $_SESSION['bestellnummer']; ?></div>
            </div>

            <?php $err = (isset($_SESSION['err_bank_name']) ? ' form_err' : ''); ?>
            <div class="line">
               <div class="line_left fliesstext text_gross<?php echo $err; ?>"><?php echo $text->get('shop', 'bank'); ?>*</div>
               <div class="line_center"></div>
               <div class="line_right"><input type="text" class="text_formular text_gross<?php echo $err; ?>" name="bank_name" id="bank_name" value="<?php echo $data['bank_name']; ?>" /></div>
            </div>

            <?php $err = (isset($_SESSION['err_bank_inhaber']) ? ' form_err' : ''); ?>
            <div class="line">
               <div class="line_left fliesstext text_gross<?php echo $err; ?>"><?php echo $text->get('shop', 'inhaber'); ?>*</div>
               <div class="line_center"></div>
               <div class="line_right"><input type="text" class="text_formular text_gross<?php echo $err; ?>" name="bank_inhaber" id="bank_inhaber" value="<?php echo $inhaber; ?>" /></div>
            </div>

            <?php $err = (isset($_SESSION['err_bank_iban']) ? ' form_err' : ''); ?>
            <div class="line">
               <div class="line_left fliesstext text_gross<?php echo $err; ?>"><?php echo $text->get('shop', 'iban'); ?>*</div>
               <div class="line_center"></div>
               <div class="line_right"><input type="text" class="text_formular text_gross<?php echo $err; ?>" name="bank_iban" id="bank_iban" value="<?php echo $data['bank_iban']; ?>" /></div>
            </div>

            <?php $err = (isset($_SESSION['err_bank_bic']) ? ' form_err' : ''); ?>
            <div class="line">
               <div class="line_left fliesstext text_gross<?php echo $err; ?>"><?php echo $text->get('shop', 'bic'); ?>*</div>
               <div class="line_center"></div>
               <div class="line_right"><input type="text" class="text_formular text_gross<?php echo $err; ?>" name="bank_bic" id="bank_bic" value="<?php echo $data['bank_bic']; ?>" /></div>
            </div>

            <div class="line">&nbsp;</div>

            <?php if ($params->firma['lastschrift_pdf_check'] == 'y') { ?>
            <div class="line fliesstext text_normal ls_text"><?php echo KANPAICLASSIC\Helper::getLastschrift(); ?></div>

            <?php $err = (isset($_SESSION['err_bank_check']) ? ' form_err' : ''); ?>
            <div class="line fliesstext text_normal">
               <input type="checkbox" class="<?php echo $err; ?>" name="bank_check" id="bank_check" style="width:15px !important; left:-20px; display:inline-block; position:absolute; top:10px;" /><p class="ls_text" style="padding:10px 0;"><span class="<?php echo $err; ?>"> <?php echo $text->get('bezahlung_3', 'bestaetigung'); ?></span></p>
            </div>
            <button class="line bg_button col_button text_gross button55"><?php echo $text->get('button', 'zahlpfl'); ?></button>
            <?php } else { ?>
            <div id="vis1">
               <button class="line bg_button col_button text_gross button55" onclick="$('#vis1').hide(); $('#vis2').show(); form.submit();"><?php echo $text->get('button', 'ls_pdf'); ?></button>
            </div>

            <div id="vis2" style="display:none;">
               <div class="line bg_button button55 center">
                  <a class="col_button text_gross lh55" href="<?php echo SHOP_URL_IDX; ?>/bestelltpdf"><?php echo $text->get('button', 'zahlpfl'); ?></a>
               </div>
            </div>
            <?php } ?>
         </form>
      </div>
   </div>
</div>
