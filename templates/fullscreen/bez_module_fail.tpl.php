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
<div class="col_single site_head bg_flaechen">
   <div class="ueberschrift text_max"><?php echo $text->get('bezahlung_fail', 'subtitel'); ?></div>
</div>

<div id="bez_mod_error">
   <div class="col_single bg_flaechen">
      <div class="col_single_center">
         <div class="line ueberschrift text_gross">
            <?php echo $text->get('bestellt', 'subtitel'); ?>
         </div>
         <div class="line fliesstext text_normal">&nbsp;</div>
         <div class="line fliesstext text_normal">
             <?php echo $text->get('bezahlung_fail', 'pre_text').' '.$text->get('bezahlung_fail', 'post_text'); ?>
         </div>
         <div class="line fliesstext text_normal">
             <?php echo $text->get('bezahlung_fail', 'support'); ?>
         </div>
         <div class="line fliesstext text_normal">&nbsp;</div>
         <div class="line bg_button button55">
           <a class="col_button text_gross center" href="<?php echo SHOP_URL; ?>"><?php echo $text->get('button', 'weiter'); ?></a>
         </div>
      </div>
   </div>
</div>
