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
if (!isset($pro_script)) { $pro_script = ''; }
$active_desktop = 'n';
$active_tablet  = 'n';
$active_mobile  = 'n';

if (isset($slider['conf']->active_desktop)) {
   $active_desktop = $slider['conf']->active_desktop;
   $active_tablet  = $slider['conf']->active_tablet;
   $active_mobile  = $slider['conf']->active_mobile;
}

else {
   $active_desktop = $slider['conf']->active;
   $active_tablet  = $slider['conf']->active;
   $active_mobile  = $slider['conf']->active;
}
?>
<div class="content_box_abstand"></div>
<div class="titelzeile">
   <div class='txt_tit'><a class="help_kanpaiclassic" href="<?php echo HELP_LINK; ?>/design/extended/" target="_blank"></a>Artikel-Slider</div>
   <div class="save_button" onclick="Design.saveSlider()">speichern</div>
   <?php $menu  = \KANPAICLASSIC\Control::getMenu(); ?>
   <div class="language"><?php echo $menu->langData(); ?></div>
</div>

<div class="content_box content_box_bottom">
   <form method="post" id="pro_carussel" action="<?php echo ADMIN_URL_IDX; ?>/designExtended/update_slider">
      <div class="slider_conf">
         <div class="conf_1">
            <div class="conf_line">
               <span class="pro_pos1">
                  <input type="checkbox" class="newdesign" id="s_active_desktop" name="active_desktop"<?php echo ($active_desktop == 'y' ? ' checked="checked"' : ''); ?> />
                  <label for="s_active_desktop">aktiv</label>
               </span>
               <span class="pro_pos2">Position</span>
               <span class="pro_pos3">
                  <input type="radio" class="newdesign" id="s_position1" name="position" value="top" <?php echo ($slider['conf']->position == 'top' ? ' checked="checked"' : ''); ?> />
                  <label for="s_position1">immer oben</label>
               </span>
            </div>
            <div class="conf_line">
               <span class="pro_pos1">
                  <?php /*<input type="checkbox" class="newdesign" id="s_active_tablet" name="active_tablet"<?php echo ($active_tablet == 'y' ? ' checked="checked"' : ''); ?> />
                  <label for="s_active_tablet">aktiv Tablet</label>*/?>
               </span>
               <span class="pro_pos2"></span>
               <span class="pro_pos3">
                  <input type="radio" class="newdesign" id="s_position2" name="position" value="center" <?php echo ($slider['conf']->position == 'center' ? ' checked="checked"' : ''); ?> />
                  <label for="s_position2">nur auf Startseite</label>
               </span>
            </div>
            <div class="conf_line">
               <span class="pro_pos1">
                  <?php /*<input type="checkbox" class="newdesign" id="s_active_mobile" name="active_mobile"<?php echo ($active_mobile == 'y' ? ' checked="checked"' : ''); ?> />
                  <label for="s_active_mobile">aktiv Mobile</label>*/?>
               </span>
               <span class="pro_pos2"></span>
               <span class="pro_pos3">
                  <input type="radio" class="newdesign" id="s_position3" name="position" value="bottom" <?php echo ($slider['conf']->position == 'bottom' ? ' checked="checked"' : ''); ?> />
                  <label for="s_position3">immer unten</label>
               </span>
            </div>
         </div>

         <div class="conf_2">
            <div class="conf_line">
               <span class="pro_pos4">Abstand Enden</span>
               <span class="pro_pos5">
                  <input type="text" class="number" name="startEndDistance" value="<?php echo $slider['conf']->startEndDistance; ?>" /> px
               </span>
            </div>
            <div class="conf_line">
               <span class="pro_pos4">Abstand Bilder</span>
               <span class="pro_pos5">
                  <input type="text" class="number" name="itemOffset" value="<?php echo $slider['conf']->itemOffset; ?>" /> px
               </span>
            </div>
            <div class="conf_line">
               <span class="pro_pos4">&nbsp;</span>
               <span class="pro_pos5">&nbsp;</span>
            </div>
         </div>

         <div class="conf_3">
            <div class="conf_line">
               <span class="pro_pos6">Speedlimit</span>
               <span class="pro_pos7"><input type="text" class="number" name="speedLimiter" value="<?php echo $slider['conf']->speedLimiter; ?>" /></span>
            </div>
            <div class="conf_line">
               <span class="pro_pos6">Spiegel-Transparenz</span>
               <span class="pro_pos7"><input type="text" class="number" name="reflectionAlpha" value="<?php echo $slider['conf']->reflectionAlpha; ?>" /> %</span>
           </div>
            <div class="conf_line">
               <span class="pro_pos6">Spiegel-Farbe</span>
               <span class="pro_pos7">
                  <input type="text" class="minicolors" name="color" value="<?php echo $slider['conf']->color; ?>" />
               </span>
            </div>
         </div>
         <div class="clear"></div>
      </div>
      <div class="line_horizontal"></div>

      <div class="slider_images">
         <?php for ($i=1; $i <= 15; $i++) { ?>
         <div class="img_box">
            <span class="img_nr">Bild <?php echo $i; ?></span>
            <span class="upload upload_button pointer" href="#pro_upload" onclick="Design.uploudProImg('slider', <?php echo $i; ?>, 'slider_<?php echo $i; ?>');"></span>
            <span class="delete pointer far fa-trash-alt" onclick="Design.deleteProImg('slider', <?php echo $i; ?>, 'slider_<?php echo $i; ?>')" ></span>
            <span class="intern">
               <input type="checkbox" class="newdesign" id="s_intern_<?php echo $i; ?>" name="intern_<?php echo $i; ?>"<?php echo ($slider['img_'.$i]->intern == 'y' ? ' checked="checked"' : ''); ?> />
               <label for="s_intern_<?php echo $i; ?>" name="intern_<?php echo $i; ?>" class="after">intern</label>
            </span>
            <span class="slider_img" href="<?php echo $slider['img_'.$i]->image; ?>">
               <img id="slider_<?php echo $i; ?>" class="image_bg" src="<?php echo $slider['img_'.$i]->image.'?'.time(); ?>" alt="" style="width:<?php echo $slider['img_'.$i]->width; ?>px; height:<?php echo $slider['img_'.$i]->height; ?>px;" />
            </span>
            <span class="linkurl"><input type="text" name="link_<?php echo $i; ?>" value="<?php echo $slider['img_'.$i]->link; ?>" placeholder="http://domain.de" /></span>
            <span class="tooltip"><input type="text" name="tooltip_<?php echo $i; ?>" value="<?php echo $slider['img_'.$i]->tooltip; ?>" placeholder="Beschriftung" /></span>
         </div>
         <?php }?>
         <div class="clear"></div>
      </div>
   </form>
</div>
