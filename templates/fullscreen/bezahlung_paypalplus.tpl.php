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

$mail = $data['email'];
if ($data['pp_mail'] != '') {
   $mail = $data['pp_mail'];
}
?>
<div class="col_single">
   <div class="col_single_center">
      <div class="col_single ueberschrift text_gross center">
         <?php echo $text->get('bezahlung_10', 'subtitel'); ?>
      </div>

      <div class="col_single">
         <form method="post" action="<?php echo SHOP_URL_IDX; ?>/bestellt" onsubmit="ppPlus(); return false;">
            <div class="line">&nbsp;</div>

            <div class="line">
               <div class="line_left ueberschrift text_max"><?php echo $text->get('bezahlung', 'gesamt'); ?></div>
               <div class="line_center"></div>

               <?php if ($params->firma['price_login'] == 'y' && $params->user_id == 0) { ?>
               <div class="line_right fliesstext text_normal"><img src="<?php echo TEMPLATE_URL . '/images/system/btn_preis_nl_' . $params->selected_lang . '.jpg'; ?>" /></div>
               <?php } else { ?>
               <div class="line_right ueberschrift text_max"><?php echo KANPAICLASSIC\Helper::number_format($gesamt_show, 2, ',', '.'). ' '.$params->waehrung; ?></div>
               <?php } ?>
            </div>

            <div class="line">
               <div class="line_left fliesstext text_gross"><?php echo $text->get('adresse', 'bestnr'); ?></div>
               <div class="line_center"></div>
               <div class="line_right fliesstext text_gross"><?php echo $_SESSION['bestellnummer']; ?></div>
            </div>

            <div class="line" style="display:none;">
               <div class="line_left fliesstext text_normal"><?php echo $text->get('bezahlung_2', 'pp_email', 'lang'); ?></div>
               <div class="line_center"></div>
               <div class="line_right"><input type="text" id="email" class="text_formular text_gross" value="<?php echo $mail; ?>" /></div>
            </div>

            <div class="line">&nbsp;</div>
            <div id="ppp_wait" style="display:none;">
               <div class="fliesstext text_normal center"><?php echo $text->get('ppp', 'wait'); ?></div>
            </div>
            <div id="ppp">
               <div class="line pp_button"><input type="image" name="submit" border="0" src="<?php echo TEMPLATE_URL.'/images/system/paypalplus_'.$params->selected_lang.'.jpg'; ?>" alt="PayPalPlus - The safer, easier way to pay online" /></div>
            </div>
         </form>
         <div id="hidden_form" style="display:none;"></div>
      </div>
   </div>
</div>
