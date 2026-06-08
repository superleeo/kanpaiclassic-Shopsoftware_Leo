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
?>

<?php if ($slideshow != 0 || isset($livedesigner)) { ?>
   <div class="xslideshow article_hide">
      <?php if (isset($livedesigner) && $slideshow_on) { ?>
         <div id="livedesigner_slideshow">
            <div id="live_slideshow"><?php echo $live_slideshow; ?></div>
         </div>
      <?php } ?>
      <div class="slider_wrapper">
      <?php if (!$slide_right) { ?>
         <div class="" style="max-width:<?php echo ($slideshow_full ? '100%' : $slide_width); ?>px; max-height:<?php echo ($slideshow_full ? '' : $slide_height.'px'); ?>; position:relative;">
      <?php  } else { ?>
         <div class="col66_lsl_l">
      <?php } ?>

   <div>
      <?php if ($slideshow == 1) { ?>
         <?php if ($params->links['link11'] != '') { ?>
            <div class="slideshow_text_wrapper">
               <a href="<?php echo $params->links['link11']; ?>" target="<?php echo ($params->links['link11_intern'] == 'y' ? '_self' : '_blank'); ?>">
         <?php } ?>
                  <img src="<?php echo TEMPLATE_URL.'/images/'.$startbild_single.$params->firma['image_cache']; ?>" style="width:100%;border:0;" alt="<?php echo $params->links['link11_seo']; ?>" title="<?php echo $params->links['link11_seo']; ?>"/>
         <?php if ($params->links['link11'] != '') { ?>
               </a>
         <?php } ?>
         <?php if ($params->links['link11_text'] != '') { ?>
               <?php $slide_color = KANPAICLASSIC\Helper::hex2rgba($params->links['link11_color_text'], $params->links['link11_color_text_opc']); ?>
               <?php $slide_bg    = KANPAICLASSIC\Helper::hex2rgba($params->links['link11_color_bg'], $params->links['link11_color_bg_opc']); ?>
               <div class="text_max slideshow_text" style="color:<?php echo $slide_color; ?>; background-color:<?php echo $slide_bg; ?>;"><?php echo $params->links['link11_text']; ?></div>
         <?php } ?>
         <?php if ($params->links['link11'] != '') { ?>
            </div>
         <?php } ?>
      <?php } ?>
   </div>
   <div>
      <?php if ($slideshow == 2) { ?>
            <div id="slideshow" style="overflow:hidden;">
            <?php for ($i = 0; $i < count($slide_pics); $i++) { ?>
               <?php $slide_color = KANPAICLASSIC\Helper::hex2rgba($params->links['link'.($i + 11).'_color_text'], $params->links['link'.($i + 11).'_color_text_opc']); ?>
               <?php $slide_bg    = KANPAICLASSIC\Helper::hex2rgba($params->links['link'.($i + 11).'_color_bg'], $params->links['link'.($i + 11).'_color_bg_opc']); ?>
               <img src="<?php echo TEMPLATE_URL.'/images/'.$slide_pics[$i][0].$params->firma['image_cache']; ?>"
                    style="width:100%;<?php echo ($params->links['link'.($slide_pics[$i][1] + 10)] != '' ? ' cursor:pointer;' : ''); ?><?php echo ($i > 0 ? ' display:none;' :'' ); ?>"
                    alt="<?php echo $params->links['link'.($i + 11).'_seo']; ?>"
                    title="<?php echo $params->links['link'.($i + 11).'_seo']; ?>"
                    <?php if ($params->links['link'.($i + 11).'_intern'] == 'n' && $params->links['link'.($slide_pics[$i][1] + 10)] != '') { ?>
                    onclick="window.open('<?php echo $params->links['link'.($i + 11)]; ?>');"
                    <?php } else if ($params->links['link'.($i + 11).'_intern'] == 'y' && $params->links['link'.($slide_pics[$i][1] + 10)] != ''){ ?>
                    onclick="window.location.href='<?php echo $params->links['link'.($slide_pics[$i][1] + 10)]; ?>';"
                    <?php } ?>
                    <?php if ($params->links['link'.($i + 11).'_text'] != '') { ?>
                    data-color="<?php echo $slide_color; ?>"
                    data-bg="<?php echo $slide_bg; ?>"
                    data-text="<?php echo $params->links['link'.($i + 11).'_text']; ?>"
                    <?php } ?>
               />
               <?php } ?>
               <div class="cycle-next"></div>
               <div class="cycle-prev"></div>
               <div class="cycle-pager"></div>
               <div id="slideshow_text" class="text_max"></div>
            </div>
      <?php } ?>
   </div>
      <?php if ($slide_right) { ?>
         </div>
         <div class="col66_lsl_m"></div>

         <div class="col66_lsl_r">
            <div class="slideshow_text_wrapper">
            <?php if ($params->links['link19'] != '') { ?>
               <a href="<?php echo $params->links['link19']; ?>" target="<?php echo ($params->links['link19_intern'] == 'y' ? '_self' : '_blank'); ?>">
                  <img src="<?php echo $slide_right_o.$params->firma['image_cache']; ?>" alt="<?php echo $params->links['link19_seo']; ?>" title="<?php echo $params->links['link19_seo']; ?>" id="slider_rechts_oben" />
               </a>
               <?php } else { ?>
               <img src="<?php echo $slide_right_o.$params->firma['image_cache']; ?>" alt="<?php echo $params->links['link19_seo']; ?>" title="<?php echo $params->links['link19_seo']; ?>" id="slider_rechts_oben" />
               <?php } ?>
               <?php if ($params->links['link19_text'] != '') { ?>
                  <?php $slide_color = KANPAICLASSIC\Helper::hex2rgba($params->links['link19_color_text'], $params->links['link19_color_text_opc']); ?>
                  <?php $slide_bg    = KANPAICLASSIC\Helper::hex2rgba($params->links['link19_color_bg'], $params->links['link19_color_bg_opc']); ?>
               <div class="text_max slideshow_text" style="color:<?php echo $slide_color; ?>; background-color:<?php echo $slide_bg; ?>;"><?php echo $params->links['link19_text']; ?></div>
                  <?php } ?>
            </div>
            <div class="slideshow_text_wrapper">
            <?php if ($params->links['link20'] != '') { ?>
               <a href="<?php echo $params->links['link20']; ?>" target="<?php echo ($params->links['link20_intern'] == 'y' ? '_self' : '_blank'); ?>">
                  <img src="<?php echo $slide_right_u.$params->firma['image_cache']; ?>" id="slider_rechts_unten" alt="<?php echo $params->links['link20_seo']; ?>" title="<?php echo $params->links['link20_seo']; ?>" <?php echo ($slide_right_o ? ' style="margin-top:2.6% !important;" data-margin="2.6%"' : ''); ?> />
               </a>
           <?php } else { ?>
               <img src="<?php echo $slide_right_u.$params->firma['image_cache']; ?>" id="slider_rechts_unten" alt="<?php echo $params->links['link20_seo']; ?>" title="<?php echo $params->links['link20_seo']; ?>" <?php echo ($slide_right_o ? ' style="margin-top:2.6% !important;" data-margin="2.6%"' : ''); ?> />
           <?php } ?>
           <?php if ($params->links['link20_text'] != '') { ?>
               <?php $slide_color = KANPAICLASSIC\Helper::hex2rgba($params->links['link20_color_text'], $params->links['link20_color_text_opc']); ?>
               <?php $slide_bg    = KANPAICLASSIC\Helper::hex2rgba($params->links['link20_color_bg'], $params->links['link20_color_bg_opc']); ?>
            <div class="text_max slideshow_text" style="color:<?php echo $slide_color; ?>; background-color:<?php echo $slide_bg; ?>;"><?php echo $params->links['link20_text']; ?></div>
            <?php } ?>
            </div>
         </div>
         <div class="clear"></div>
      <?php } else {?>
         </div>
      <?php } ?>
      </div>
   </div>
<?php }
