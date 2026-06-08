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

define ('CONF_START_IMG1', '../templates/1.png');
define ('CONF_START_LINK1', '');
define ('CONF_START_TITLE1', '');
define ('CONF_START_IMG2', '../templates/2.png');
define ('CONF_START_LINK2', '');
define ('CONF_START_TITLE2', '');

//define ('CONF_START_IMG3', $params->templateurl . '/images/logo_deu.png');    <- das ist ein indirekter Link zum Bild
//define ('CONF_START_LINK3', '#'); <- wenn man die Grafik nicht verlinken will
//define ('CONF_START_TITLE3', 'Shopsoftware');
?>
<?php if (defined('CONF_START_IMG1')) { ?>
   <div class="startbanner col_in_lsl_l"><br><a title="<?php echo CONF_START_TITLE1; ?>" href="<?php echo CONF_START_LINK1; ?>" target="_blank"><img class="load_image" src="" data-src="<?php echo CONF_START_IMG1; ?>" alt="" /></a></div>
<?php } ?>
   <div class="col_in_lsl_m"></div>
<?php if (defined('CONF_START_IMG2')) { ?>
   <div class="startbanner col_in_lsl_r"><a title="<?php echo CONF_START_TITLE2; ?>" href="<?php echo CONF_START_LINK2; ?>" target="_blank"><img class="load_image" src="" data-src="<?php echo CONF_START_IMG2; ?>" alt="" /></a></div>
<?php } ?>
<div class="clear"></div>
