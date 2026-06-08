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

$seiten     = false;
$kategorien = false;
?>
<div class="col_single site_head bg_flaechen">
   <div class="ueberschrift text_max">Sitemap</div>
</div>

<div id="sitemap">
   <div class="col_single bg_flaechen">
      <div class="col_inner">
         <a href="<?php echo SHOP_URL_IDX; ?>/sitemap.xml" style="display:none;">XML-Sitemap</a>
      <?php if (($params->firma['sitemap_menu'] == 'y' || $params->firma['sitemap_agb'] == 'y') && file_exists(SHOP_PATH.'/sitemap_seiten.html')) { ?>
         <?php $seiten = true; ?>
         <div id="sitemap_seiten">
            <?php echo file_get_contents(SHOP_PATH.'/sitemap_seiten.html'); ?>
            <div class="clear"></div>
         </div>
      <?php } ?>

      <?php if ($params->firma['sitemap_cat'] == 'y' && file_exists(SHOP_PATH.'/sitemap_categories.html')) { ?>
         <?php $kategorien = true; ?>
         <?php if ($seiten) { ?>
         <div class="abstand"></div>
         <?php } ?>
         <div class="text_bold title text_gross"><?php echo $text->get('menu', 'kategorien'); ?></div>
         <div id="sitemap_categories">
            <?php echo file_get_contents(SHOP_PATH.'/sitemap_categories.html'); ?>
            <div class="clear"></div>
         </div>
      <?php } ?>

      <?php if ($params->firma['sitemap_articles'] == 'y' && file_exists(SHOP_PATH.'/sitemap_articles.html')) { ?>
         <?php if ($seiten || $kategorien) { ?>
         <div class="abstand"></div>
         <?php } ?>
         <div id="sitemap_articles">
            <div class="text_bold title text_gross"><?php echo $text->get('menu', 'artikel'); ?></div>
            <?php echo file_get_contents(SHOP_PATH.'/sitemap_articles.html'); ?>
            <div class="clear"></div>
         </div>
      <?php } ?>
      </div>
   </div>
</div>
