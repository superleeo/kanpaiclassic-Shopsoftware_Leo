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

$picture = '';
$first = true;
$pos = 'left';
?>
<?php  // Merkliste leer ? ?>
<?php if (!count($params->my_merkliste)) { ?>
<div id="merkliste">
   <div class="col_single">
      <div id="detail_image" class="col_lsl_l">
         <div class="bg_flaechen bg_fullheight">
            <div class="site_head">
               <div class="ueberschrift text_max">
                  <?php echo $text->get('menu', 'merkliste'); ?>
               </div>
            </div>
         </div>
      </div>

      <div class="col_lsl_m"></div>

      <div id="wk_right" class="col_lsl_r">
         <div class="bg_flaechen bg_fullheight">
            <div class="site_head">
               <div class=" fliesstext text_gross">
                  <?php echo $text->get('merkliste', 'leer'); ?>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<?php return; ?>
<?php } ?>

<!-- Merkliste -->
<?php // Merkliste nicht leer ?>
<div id="merkliste">
   <div class="col_single bg_flaechen site_head">
      <div class="ueberschrift text_max"><?php echo $text->get('menu', 'merkliste'); ?></div>
   </div>

   <?php // Ohne Login Anmelden anzeigen ?>
   <?php if ($params->user_id < 1) { ?>
   <div class="col_single">
      <div class="col_lsl_l">
         <div class="bg_flaechen site_head_small">
            <span class="fliesstext text_normal"><?php echo $text->get('merkliste', 'login'); ?></span>
         </div>
      </div>
      <div class="col_lsl_m"></div>
      <div class="col_lsl_r anmelden bg_button">
         <a href="<?php echo SHOP_URL_IDX; ?>/login">
            <span class="col_button fliesstext text_gross"><?php echo $text->get('button', 'anmelden'); ?></span>
         </a>
      </div>
      <div class="clear"></div>
   </div>
   <?php } ?>

   <?php // Artikel anzeigen ?>
   <?php foreach ($data as $my_ml) { ?>
      <?php
      $grundeinheit = $text->get('ge' ,$my_ml->grundeinheit);
      $grundeinheit_rechner = $text->get('ge' ,$my_ml->grundeinheit_rechner);
      $rechner_einheit      = $text->get('ge' ,$my_ml->rechner_einheit);

      $ge_preis  = 0;
      $ge_text   = '';
      $auf_lager = true;
      $online    = true;
      $deleted   = false;
      
      
      $test = $db->querySingleObject("SELECT online, menge FROM #__articles WHERE id = $my_ml->art_id");
      
      // Artikel vorhanden
      if ($test) {
         if ($test->online != 'y') {
            $online = false;
         }

         if ((float)$test->menge <= 0) {
            // Nicht auf Lager
            $auf_lager = false;
         }
      }
      
      // Artikel gelöscht / nicht bei Mixer-Kategorie
      else if ((int)$my_ml->cat_id == 0) {
         $deleted = true;
      }

      if ($my_ml->ge_netto_aktiv == 'y') {
         if ($params->firma['tax_active'] == 'y') {
            $ge_preis = KANPAICLASSIC\Helper::number_format($my_ml->ge_netto * (1 + (float)str_replace(',', '.', $my_ml->steuer) / 100), 2, ',', '.');
         }
         
         else {
            $ge_preis = KANPAICLASSIC\Helper::number_format($my_ml->ge_netto, 2, ',', '.');
         }
         
         $ge_text = $ge_preis . ' ' . $params->waehrung . ' ' . $text->get('article', 'je') . ' ' . $grundeinheit;
      }
   
      if (strpos($my_ml->pict01, 'http://') !== false || strpos($my_ml->pict01, 'https://') !== false) {
         if ((int)$my_ml->cat_id > 0) {
            $picture = $my_ml->pict01;
         }
         
         else {
            $picture = str_replace('.jpg', '_tn.jpg', $my_ml->pict01);
         }
      }
      
      else {
         $picture = KANPAICLASSIC\Helper::testPicture($my_ml->pict01.'_tn.jpg');
      }

      $vs_text = "";
  
                            
        $versandkosten_incl = $my_ml->versandkosten_incl;//($params->firma['vers_grafik_check'] == 'y' && $params->versandkosten_incl || (empty($params->firma['vers_grafik_check']) || $params->firma['vers_grafik_check'] == 'n') && $my_ml->versandfrei_check == 'y');

        if (defined('CONF_MODULE_PORTAL')) {
            $vs_text = '            <a  class="fliesstext" href="'.SHOP_URL_IDX.'/profil/'.$params->haendler_id.'" target="_blank"><span style="text-decoration:underline;">'.$my_ml->text->get('article', (/*$this->versandkosten_incl*/ $versandkosten_incl ? 'versand_inkl' : 'versand')).'</span></a>'.CR;
        }
        else {
            $vs_text = '            <a  class="fliesstext" href="'.SHOP_URL_IDX . '/versand" target="_blank"><span style="text-decoration:underline;">'.$text->get('article', ( /*$this->versandkosten_incl*/ $versandkosten_incl ? 'versand_inkl' : 'versand')).'</span></a>'.CR;
        }
  



      ?>

      <?php if ($pos == 'left') { ?>
   <div class="col_single">
      <?php } else { ?>
   <div class="col_lsl_m"></div>
      <?php } ?>
      <div class="col_lsl_<?php echo ($pos == 'left' ? 'l col_left_height' : 'r col_right_height'); ?> ml_artikel">
         <div class="bg_flaechen bg_fullheight">
            <?php if (!$first) { ?>
            <div class="line_top">
               <hr class="line_top" />
            </div>
            <?php } ?>
            <?php $first = ($first && $pos == 'right' ? false : true) ?>
            <div class="pic_wrapper col_fix">
               <div class="wk_picture">
                  <div class="bg_artikelbild">
                  <?php if ($my_ml->art_id > 0) { ?>
                     <a href="<?php echo $params->getLink('artikel', $my_ml->art_id, $my_ml->artikel_name); ?>"><img src="<?php echo $picture; ?>"<?php echo (strpos($picture, 'nopic.png') !== false ? ' style="width:215px; height:162px;"' : ''); ?> /></a>
                  <?php } else { ?>
                     <a href="<?php echo $params->getLink('kategorie', $my_ml->cat_id, $my_ml->artikel_name); ?>"><img src="<?php echo $picture; ?>"<?php echo (strpos($picture, 'nopic.png') !== false ? ' style="width:215px; height:162px;"' : ''); ?> /></a>
                  <?php } ?>
                  </div>
               </div>
            </div>

            <div class= "info_wrapper col_rest">
               <div class= "info_wrapper_inner">
                  <a class="wk_delete" href="<?php echo SHOP_URL_IDX; ?>/ml_del/<?php echo $my_ml->wk_id; ?>"></a>
                  <?php if ($my_ml->art_id > 0) { ?>
                  <div class="wk_titel"><a class="ueberschrift text_max artikel_link" href="<?php echo $params->getLink('artikel', $my_ml->art_id, $my_ml->artikel_name); ?>"><?php echo $my_ml->artikel_name; ?></a></div>
                  <?php } else { ?>
                  <div class="wk_titel"><a class="ueberschrift text_max artikel_link" href="<?php echo $params->getLink('kategorie', $my_ml->cat_id, $my_ml->artikel_name); ?>"><?php echo $my_ml->artikel_name; ?></a></div>
                  <?php } ?>
                  <?php if ($my_ml->rechner_check == 'y') { ?>
                  <span class="wk_rechner fliesstext text_gross">
                     <?php echo number_format($my_ml->rechner_breite, $my_ml->masse_komma, ',', '').
                                ((int)$my_ml->rechner_mode == 1 ? ' '.$grundeinheit
                                : 
                                   $rechner_einheit.' x '.number_format($my_ml->rechner_hoehe, $my_ml->masse_komma, ',', '').
                                   ((int)$my_ml->rechner_mode > 2 ? $rechner_einheit.' x '.
                                       number_format($my_ml->rechner_tiefe, $my_ml->masse_komma, ',', '') : '').
                                   $rechner_einheit.' = '.
                                   // number_format($my_ml->rechner_breite * $my_ml->rechner_hoehe, $my_ml->masse_komma, ',', '').
                                   number_format($my_ml->rechner_breite * $my_ml->rechner_hoehe * $my_ml->rechner_tiefe, $my_ml->masse_komma, ',', '').
                                   $grundeinheit_rechner
                                ); ?>
                  </span>
                  <?php } ?>

                  <div class="wk_artnr fliesstext text_klein"><?php echo $text->get('artikel', 'best_nr'); ?>: <?php echo $my_ml->art_nr?></div>
                  
                  <?php if (defined('CONF_MODULE_MATRIX') && $my_ml->preismatrix != '') { ?>
                     <?php $matrix = json_decode($my_ml->preismatrix); ?>
                  <div class="wk_artnr fliesstext text_klein"><?php echo $matrix->{'breite_'.$lang}.' x '.$matrix->{'hoehe_'.$lang}.' ('.$matrix->{'einheit_'.$lang}.') : '.number_format($matrix->breite, $matrix->komma, ',', '').' x '.number_format($matrix->hoehe, $matrix->komma, ',', ''); ?></div>
                  <?php } ?>
    
                  <?php if (defined('CONF_MODULE_MEGACONFIGURATOR') && $my_ml->configurator != '') { ?>
                     <?php $configurator = KANPAICLASSIC\Control::getModuleConfigurator(); ?>
                     <?php echo $configurator->getConfiguratorByName($my_ml->configurator); ?>
                  <?php } ?>
                  <div class="wk_beschr fliesstext text_normal"><?php echo KANPAICLASSIC\Helper::truncate( strip_tags(str_replace('[TRENNER]', ' ', $my_ml->artikel_text)), 300 );?></div>
                  <?php if ($params->firma['price_login'] == 'y' && $params->user_id == 0) { ?>
                  <?php } else { ?>
                     <?php
                     
                     if ($ge_text != '' || $vs_text != '') { ?>
                          <div class="wk_grundeinheit fliesstext text_klein">
                             <div style="display:inline;" class="fliesstext art_menge text_klein"><?php echo $ge_text; ?></div>
                             <div style="display:inline;float:right;" class="fliesstext art_menge text_klein"><?php echo $vs_text; ?></div>
                          </div>
                     <?php } ?>

                  <div class="wk_steuer fliesstext text_klein">
                  <?php echo $params->firma['kleingewerbe'] == 'y' ? $text->get('article', 'preis_kleing') : ($params->firma['tax_active'] == 'y' ? ($params->firma['tax_show'] == 'y' ? $text->get('article', 'preis_brutto') : $text->get('article', 'preis_netto')) : ''); ?>
                  </div>

      




                  <div class="preis_img ueberschrift text_max"><?php echo KANPAICLASSIC\Helper::number_format($my_ml->preis, 2, ',', '.').' '.$params->waehrung; ?></div>
                  <div class="clear"></div>
                  <?php } ?>
               </div>
            </div>
            <div class="clear"></div>
            <div class="col_single">
               <?php // Artikel gelöscht ?>
               <?php if ($deleted) { // Artikel gelöscht ?>
                 <div class="bg_button col_button button_inwk fliesstext text_gross bg_button_no"><?php echo $text->get('button', 'lager'); ?></div>
               
               <?php } else if (!$online) { ?>
                 <div class="bg_button col_button button_inwk fliesstext text_gross bg_button_no"><?php echo $text->get('button', 'lager'); ?></div>
               
               <?php } else { ?>
                  <?php if ($auf_lager || $params->firma['lager_leer'] == 'y'&& $params->firma['lager_bestell_check'] == 'n') { // In Warenkorb ?>
                  <div class="bg_button col_button button_inwk fliesstext text_gross" onclick="mlInWk(<?php echo $my_ml->wk_id; ?>);"><?php echo $text->get('button', 'in_wk'); ?></div>
               
                  <?php } else if ($params->firma['lager_leer'] == 'y' && $params->firma['lager_bestell_check'] == 'y') { // Vorbestellen ?>
                  <div class="bg_button col_button button_inwk fliesstext text_gross" onclick="mlInWk(<?php echo $my_ml->wk_id; ?>);"><?php echo $text->get('button', 'vorbestellen'); ?></div>
                  
                  <?php } else { // Menge nicht verfügbar ?>
                  <div class="bg_button col_button button_inwk fliesstext text_gross bg_button_no"><?php echo $text->get('button', 'lager'); ?></div>
                  <?php } ?>
               <?php } ?>
            </div>
         </div>
      </div>
      <?php if ($pos == 'right') { ?>
   </div>
   <div class="clear"></div>
      <?php } ?>
      <?php $pos = ($pos == 'left' ? 'right' : 'left'); ?>
   <?php } // foreach ?>
   <?php if ($pos == 'right') { ?>
   <div class="clear"></div>
   </div>
   <?php } else { ?>
   <?php } ?>
</div>
<!-- /Merkliste -->
