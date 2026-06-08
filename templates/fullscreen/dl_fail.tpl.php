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

$val = base64_encode((int)$params->re_id * 57 - 29);
?>
<div class="site_head bg_flaechen">
   <div class="col_single ueberschrift text_max"><?php echo $text->get('download', 'fail_titel'); ?></div>
</div>

<div class="col_single bg_flaechen">
   <div class="default_subtitle txt_tit">
      <?php //echo $text->get('bestellt', 'subtitel', 'lang'); ?>
   </div>
   <div class="bestellnr txt_bez"><?php //echo $text->get('download', 'fail_1'); ?><br /><?php echo $text->get('download', 'fail_shop'); ?> Fehler <?php echo $fehler; ?>
   </div>
   <div class="button_zeile">
      <a href="<?php echo SHOP_URL; ?>"><img
         src="<?php echo TEMPLATE_URL.'/images/system/btn_weiter_' . $params->selected_lang . '.jpg'; ?>" />
      </a>
   </div>
</div>
