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

if (!defined('OBADJA')) {
   die ("This file cannot run outside the Kanpai Classic Shopsoftware");
}

$re_id = $params->re_id;
?>
<div class="col_single site_head bg_flaechen">
   <div class="ueberschrift text_max">
      <?php echo $text->get('ppp', 'title'); ?>
   </div>
</div>

<div id="bez_mod_ppp">
   <div class="col_single bg_flaechen">
      <div class="col_single_center">
         <div class="line ueberschrift text_gross">
            <?php echo $text->get('bestellt', 'subtitel'); ?>
         </div>
         <div class="line fliesstext text_normal">&nbsp;</div>
         <div id="ppp_fail" style="display:none;">
            <div class="line fliesstext text_normal">
                <?php echo $text->get('bezahlung_error', 'pre_text').' '.$text->get('bezahlung_error', 'post_text'); ?>
            </div>
            <div class="line fliesstext text_normal">
                <?php echo $text->get('bezahlung_fail', 'support'); ?>
            </div>
            <div class="line fliesstext text_normal">&nbsp;</div>
          </div>
         <div id="ppp_info">
            <div class="line fliesstext text_normal"><?php echo $text->get('ppp', 'info'); ?></div>
            <div class="line fliesstext text_normal">&nbsp;</div>
         </div>
         <div id="ppp_check" class="line fliesstext text_normal center"><?php echo $text->get('ppp', 'wait'); ?></div>
         <div id="ppp_exec" style="display:none;" class="col_button bg_button button55 text_gross center" onclick="execPpp(<?php echo $re_id; ?>)"><?php echo $text->get('button', 'ppp_exec'); ?></div>
         <div id="ppp_ok" style="display:none;" class="bg_button button55 text_gross center">
           <a class="col_button text_gross center" href="<?php echo SHOP_URL_IDX; ?>/ppp_ok"><?php echo $text->get('button', 'weiter'); ?></a>
         </div>
         <div id="ppp_ok_failed" style="display:none;" class="bg_button button55 text_gross center">
           <a class="col_button text_gross center" href="<?php echo SHOP_URL; ?>"><?php echo $text->get('button', 'weiter'); ?></a>
         </div>
      </div>
   </div>
</div>
<?php $script .= '<script>$(function() { checkPpp('.$re_id.'); });</script>';
