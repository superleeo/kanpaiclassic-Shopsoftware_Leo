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

// ********** Extended mitte **********
?>
      <?php if ($cat_left) { ?>
      <div id="content_right" class="x_mit_menu margin_menu content_right_cat col_right_height content_center_nopad bg_innen" data-margin="<?php echo $menu_width_nopad; ?>">
      <?php } else { ?>
      <div id="content_right" class="x_ohne menu content_right_cat col_right_height<?php echo ($is_flaeche_mitte ? ' bg_innen' : ''); ?>" data-margin="<?php echo $menu_width_nopad; ?>">
      <?php } ?>

<?php // ********** Slideshow ********** ?>
      <?php
      $css_class1 = '';
      $css_class2 = '';

      if ($cat_left) {
         $css_class1 = ' x_left content_center_nopad padding_top padding_bottom';
         $css_class2 = ' content_center';
      }

      else {
         if ($slideshow_full) {
            $css_class1 = ' x_full padding-bottom';
            $css_class2 = '';
         }

         else if ($is_flaeche_mitte) {
            $css_class1 = ' x_flaeche padding_top padding_bottom content_center_nopad';
            $css_class2 = ' content_center';
         }

         else {
            $css_class1 = ' x_ohne content_center_nopad padding_top bg_innen';
            $css_class2 = ' padding_bottom content_center';
         }
      }
      ?>
      <?php if ($startseite && $slideshow_on) { ?>
         <div class="x_slideshow article_hide<?php echo $css_class1; ?>"<?php echo ($slideshow_full == 'y' && !$cat_left ? ' style="width:100%;"' : ''); ?>>
            <div class="relative <?php echo $css_class2; ?>">
               <?php include_once TEMPLATE_PATH.'/template_slideshow.tpl.php'; ?>
            </div>
         </div>
      <?php } ?>

<?php // ********** Kein Livedesigner2: Modul Extended / StartHtml / Collage ********** ?>
      <div>
         <div class="x_extended <?php echo ($is_flaeche_mitte ? '' : ''); ?>">
            <?php // if ($extended_middle || $starthtml || (!$extended_middle && ($slider_reload == 'center' || $accordion_reload == 'center' || $carussell_reload == 'center'))) { ?>
            <div class="<?php echo ($cat_left ? ' content_center_nopad' : ($is_flaeche_mitte ? '' : 'content_center_nopad bg_innen')); ?>">

               <?php if ($slider_reload == 'center' || $accordion_reload == 'center' || $carussell_reload == 'center') { ?>
               <div id="html5_placeholder_center">
               <?php } ?>

      <?php // ********** Accordion Startseite  (extended) ********** ?>
                  <?php if ($accordion_center) { ?>
                  <div id="accordion_center" class="accordion article_hide padding_top content_center shows_desktop">
                     <?php if (isset($livedesigner) && isset($livedesigner_ext)) { ?>
                     <div id="livedesigner_accordion">
                        <div id="live_accordion"><?php echo $live_accordion; ?></div> <!-- End Accordion -->
                     </div>
                     <?php } ?>
                     <?php echo $isExtended->accordion_html; ?>
                  </div>
                  <?php } ?>
                  <?php if ($accordion_reload == 'center') { ?>
                  <div id="accordion_center" class="accordion_iframe padding_top content_center shows_desktop">
                     <div class="accordion_placeholder"></div>
                  </div>
                  <?php } ?>

   <?php // ********** StartHTML ********** ?>
                  <?php if ($starthtml != '') { ?>
                  <div id="start_html" class="starthtml article_hide livedesigner_pos content_center">
                     <?php if (isset($livedesigner)) { ?>
                     <div id="livedesigner_starthtml">
                        <div id="live_starthtml"><?php echo $live_starthtml; ?></div>
                     </div>
                     <?php } ?>
                     <div class="text_startseite">
                        <div class="bg_flaechen col_single startseite_html">
                           <div id="startseite_html" class="col_inner fliesstext text_normal"><?php echo $starthtml; ?></div>
                        </div>
                     </div>
                  </div>
                  <?php } ?>

   <?php // ********** Collage Startseite (extended) ********** ?>
                  <?php if (!isset($livedesigner2) && $collage != '') { ?>
                  <div id="collage" class="collage article_hide padding_top content_center livedesigner_pos article_hide">
                     <?php if (isset($livedesigner)) { ?>
                     <div id="livedesigner_collage">
                        <div id="live_collage"><?php echo $live_collage; ?></div>
                     </div>
                     <?php } ?>
                     <div class="col_single /* startseite_html */">
                        <div class="collage"><?php echo $collage; ?></div>
                     </div>
                  </div>
                  <?php } ?>

      <?php // ********** Carussell Startseiet (extended) ********** ?>
                  <?php if ($carussell_center) { ?>
                  <div id="carussell_center" class="carussell article_hide padding_top content_center padding_top">
                     <?php echo $isExtended->carussell_html; ?>
                  </div>
                  <?php } ?>
                  <?php if ($carussell_reload == 'center') { ?>
                  <div id="carussell_center" class="carussell_iframe padding_top content_center">
                     <div class="carussell_placeholder"></div>
                  </div>
                  <?php } ?>

   <?php // ********** Slider Startseite (extended)  ********** ?>
                  <?php if ($slider_center) { ?>
                  <div id="slider_center" class="slider article_hide padding_top content_center">
                     <?php echo $isExtended->slider_html; ?>
                  </div>
                  <?php } ?>
                  <?php if ($slider_reload == 'center') { ?>
                  <div id="slider_center" class="slider_iframe padding_top content_center">
                     <div class="slider_placeholder"></div>
                  </div>
                  <?php } ?>
                  <?php if ($slider_reload == 'center' || $accordion_reload == 'center' || $carussell_reload == 'center') { ?>
               </div>
               <?php } ?>
            </div>
         </div>
</div>
<?php //BreadCrumb
   $breadcrumb = KANPAICLASSIC\Control::getCategories()->getCategoryBreadcrumb($params->kat_id);
   echo '<div id="breadcrumb_container" class="breadcrumb_container content_center bg_innen anz_seiten_text fliesstext text_normal" style="position:relative">'.$breadcrumb.'</div>'.CR;
?>
<?php // ********** Hauptbereich - Achtung! auf Whitespaces achten! Sonst funktioniert :empty nicht! ********** ?>
         <?php // Kategorien-Beschreibung - muss immer vorhanden sein, wg. Nachladen Kategorien/Artikel ?>
         <div id="cat_detail1"
              class="xcat_desc<?php echo ($cat_left
                 ? ' content_center_nopad article_filter_container'
                 : ($params->cat_mode == 10
                       ? ' bg_innen'
                       : ($is_flaeche_mitte
                          ?' content_center_nopad'
                          : ' content_center_nopad bg_innen'
                        )
                   )
            ); ?>"><?php
            ?><div class="cat_detail_wrapper<?php echo ($params->cat_mode == 10 ? '' : ($is_flaeche_mitte ? 'content_center' : ' content_center')); ?>"><?php
               ?><div id="cat_detail"><?php echo ($outtext != '' ? $outtext : ''); ?></div><?php
            ?></div><?php
         ?></div>

<?php // ********** Livedesigner2 / Livedesigner Ext: Livedesigner Module ********** ?>
         <?php if($startseite) { ?>
         <div class="xlivedesigner2<?php echo ($cat_left ? ' content_center' : ''); ?>">
            <?php echo ($module1); ?>
         </div>
         <?php } ?>
<?php // ********** Artikelliste oder anderer Main-Inhalt ********** ?>
         <div id="main" data-logo_width="<?php echo $shop_width; ?>" data-inner_width="<?php echo $content_width; ?>">
            <div id="main_content" class="main_content">
<?php // ********** Restaurant pages - render first ********** ?>
               <?php if (in_array($params->task, array('restaurant_home', 'menu', 'reservation', 'vouchers', 'merch'))) { ?>
               <div class="content_center" style="max-width:100%; padding:0;">
                  <?php echo $artikel_main; ?>
                  <div class="clear"></div>
               </div>
               <?php $artikel_main = ''; ?>
               <?php } ?>
<?php // ********** Hauptbereich - nicht Artikelliste ********** ?>
               <?php if ($params->task != 'kategorie' && !$startseite && !in_array($params->task, array('restaurant_home', 'menu', 'reservation', 'vouchers', 'merch'))) { ?>
<!--               <div class="content_center article_hide<?php echo ($cat_left ? ( $module_unten == true ? ' padding_top' : ($is_flaeche_mitte ? ' padding_top padding_bottom' : ' padding_top')) : ($is_flaeche_mitte ? ' padding_top padding_bottom' : ' bg_innen padding_top padding_bottom bg_innen')); ?>"> -->
               <div class="content_center article_hide<?php echo ($cat_left ? ( $module_unten == true ? '' : ' padding_top padding_bottom') : ($is_flaeche_mitte ? ' padding_top padding_bottom' : ' bg_innen padding_top padding_bottom bg_innen')); ?>">
                  <?php //                           Kategorie links?   ja    nein / Fläche Liste          ja                     nein/ Fläche Mitee        ja                                nein ?>
                  <?php echo $artikel_main; ?>
                  <div class="clear"></div>
               </div>
               <?php $artikel_main = ''; ?>
               <?php } ?>
<?php // ********** Artikelliste muss immer vorhanden sein ********** ?>
               <div class="<?php echo ($cat_left ? '' : ($is_flaeche_liste ? '' : ' content_center_nopad')); ?>">
                  <div id="article_main" class="<?php echo ($cat_left ? ' content_center padding_bottom': ($is_flaeche_liste ? ($is_flaeche_mitte ? ' padding_lr_40 padding_bottom' : 'padding_lr_40 padding_bottom bg_innen') : ($is_flaeche_mitte ? ' content_center padding_bottom' : ' content_center padding_bottom bg_innen'))); ?>"><?php
                  ?><?php if (isset($livedesigner) && $artikel_main != '') { ?><?php
                     ?><div id="livedesigner_artikel"><?php
                        ?><div id="live_einfuegen_center"><?php echo $live_einfuegen_center; ?></div><?php
                     ?></div>
                     <?php if (!isset($this->params->firma['artikelliste_on']) || $this->params->firma['artikelliste_on'] == 'y') { ?><?php
                     ?><div id="live_artikelliste"><?php echo $live_artikelliste; ?></div><?php
                     ?><?php } ?>
                  <?php } ?><?php
                     ?><?php echo $artikel_main; ?><?php
                  ?></div>
                  <div class="clear"></div>
               </div>
            </div>
            <div class="clear"></div>

<?php // Counter ?>
            <?php // Wenn Startseite html, bild oder collage ist, Counter nicht anzeigen (:empty) ?>
            <?php // HTML muss für Nachladen vorhanden sein ?>
            <?php if (!$is_counter || $params->hide_articles) { ?>
               <?php $countertext = ''; ?>
            <?php } ?>
            <div class="<?php echo                      ($cat_left ? 'content_center' : ($is_flaeche_liste ? ($is_flaeche_mitte ? ' content_center' : ' bg_innen') : ($is_flaeche_mitte ? '' : ' content_center bg_innen'))); ?>">
               <div id="site_counter" class="<?php echo ($cat_left ? '' :               ($is_flaeche_liste ? ($is_flaeche_mitte ? '' : ' content_center') : ($is_flaeche_mitte ? ' content_center' : ' content_center'))); ?>">
                  <?php echo $countertext; ?>
               </div>
            </div>
         </div>

<?php // ********** Zubehör / Ähnliche Artikel ********** ?>
         <?php // if ($module_unten) { ?>
         <?php if ($is_zubehoer && $is_aehnliche) { ?>
         <div id="zubehoer_aehnliche" class="<?php echo ($cat_left ? ' padding_bottom content_center' : ($is_flaeche_mitte ? ' content_center padding_top' : ' content_center padding_top bg_innen')); ?>">
            <div class="<?php echo            ($cat_left ? ' '                              : ($is_flaeche_mitte ? ' content_center' : '')); ?>">
               <p id="p_zub"  class="ueberschrift text_max padding_top selected" onclick="$('#aehnliche').hide(); $('#zubehoer').show(); $('#p_aehn').removeClass('selected'); $('#p_zub').addClass('selected');"><?php echo $zubehoer_text; ?></p>
               <p id="p_aehn" class="ueberschrift text_max padding_top" onclick="$('#zubehoer').hide(); $('#aehnliche').show(); cpfRestartAe(); $('#p_zub').removeClass('selected'); $('#p_aehn').addClass('selected');"><?php echo $aehnliche_text; ?></p>
               <div class="clear"></div>
            </div>
         </div>

         <div id="zubehoer" class="<?php echo ($cat_left ? ' padding_bottom content_center' : ($is_flaeche_mitte ? ' content_center padding_bottom' : ' content_center padding_bottom bg_innen')); ?>">
            <div class="<?php echo            ($cat_left ? ' '                              : ($is_flaeche_mitte ? ' content_center' : '')); ?>">
               <?php echo $zubehoer[0]; ?>
            </div>
         </div>

         <div id="aehnliche" class="<?php echo ($cat_left ? ' padding_bottom content_center' : ($is_flaeche_mitte ? ' content_center padding_bottom' : ' content_center padding_bottom bg_innen')); ?>" style="display:none;">
            <div class="<?php echo            ($cat_left ? ' '                              : ($is_flaeche_mitte ? ' content_center' : '')); ?>">
               <?php echo $aehnliche[0]; ?>
            </div>
         </div>

         <?php } else if ($is_zubehoer) { ?>
         <div id="zubehoer" class="<?php echo ($cat_left ? ' padding_bottom content_center' : ($is_flaeche_mitte ? ' padding_bottom' : ' content_center padding_bottom bg_innen')); ?>">
            <div class="<?php echo            ($cat_left ? ' '                              : ($is_flaeche_mitte ? ' content_center' : '')); ?>">
               <p class="ueberschrift text_max padding_top"><?php echo $zubehoer_text; ?></p>
               <?php echo $zubehoer[0]; ?>
            </div>
         </div>

         <?php } else if ($is_aehnliche) { ?>
         <div id="aehnliche" class="<?php echo ($cat_left ? ' padding_bottom content_center' : ($is_flaeche_mitte ? ' content_center padding_bottom' : ' padding_bottombg_innen')); ?>">
            <div class="<?php echo            ($cat_left ? ' '                              : ($is_flaeche_mitte ? ' content_center' : '')); ?>">
               <p class="ueberschrift text_max padding_top"><?php echo $aehnliche_text; ?></p>
               <?php echo $aehnliche[0]; ?>
            </div>
         </div>
         <?php } ?>

         <?php if ($is_lastseen) { ?>
          <div id="lastseen" class="<?php if($params->task == "") echo ""; ?><?php echo ($cat_left ? ' padding_bottom content_center' : ($is_flaeche_mitte ? ' content_center padding_bottom' : ' content_center padding_bottom bg_innen')); ?>">
              <div class="<?php echo            ($cat_left ? ' '                              : ($is_flaeche_mitte ? ' content_center' : '')); ?>">
                  <p class="ueberschrift text_max padding_top">
                      <?php echo $lastseen_text; ?>
                  </p>
                  <?php echo $lastseen[0]; ?>
              </div>
          </div>
         <?php } ?>
         <div class="clear"></div>
      </div><?php // Content-Right ´?>

      <?php if (false) { ?>
      </div>
      <?php } ?>
<?php // ------- ENDE Hauptbereich
