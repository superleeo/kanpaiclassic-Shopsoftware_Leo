<?php
/*
###################################################################################
  Kanpai Classic Shopsoftware Entwicklungsstand: 14.01.2021 Version 11

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

if (isset($accordion['conf']->active_desktop)) {
   $active_desktop = $accordion['conf']->active_desktop;
   $active_tablet  = $accordion['conf']->active_tablet;
   $active_mobile  = $accordion['conf']->active_mobile;
}

else {
   $active_desktop = $accordion['conf']->active;
   $active_tablet  = $accordion['conf']->active;
   $active_mobile  = $accordion['conf']->active;
}
?>
<?php if (!isset($livedesigner)) { ?>
<div class="titelzeile">
   <div class='txt_tit'><a class="help_kanpaiclassic" href="<?php echo HELP_LINK; ?>/design/extended/" target="_blank"></a>Accordion</div>
   <div class="save_button" onclick="Design.saveAccordion()">speichern</div>
   <?php $menu  = \KANPAICLASSIC\Control::getMenu(); ?>
   <div class="language"><?php echo $menu->langData(); ?></div>
</div>

<div class="content_box content_box_bottom">
<?php } ?>
   <form method="post" id="pro_carussel" action="<?php echo ADMIN_URL_IDX; ?>/designExtended/update_accordion">
      <div class="accordion_conf">
         <div class="conf_1">
            <div class="conf_line">
               <span class="pro_pos1">
                  <input type="checkbox" class="newdesign" id="a_active_desktop" name="active_desktop"<?php echo ($active_desktop == 'y' ? ' checked="checked"' : ''); ?> />
                  <label for="a_active_desktop">aktiv</label>
               </span>
               <span class="pro_pos2">Position</span>
               <span class="pro_pos3">
                  <input type="radio" class="newdesign" id="a_position1" name="position" value="top" <?php echo ($accordion['conf']->position == 'top' ? ' checked="checked"' : ''); ?> />
                  <label for="a_position1">immer oben</label>
               </span>
            </div>
            <div class="conf_line">
               <span class="pro_pos1">
                  <?php /*<input type="checkbox" class="newdesign" id="a_active_tablet" name="active_tablet"<?php echo ($active_tablet == 'y' ? ' checked="checked"' : ''); ?> />
                  <label for="a_active_tablet">aktiv Tablet</label>*/?>
               </span>
               <span class="pro_pos2"></span>
               <span class="pro_pos3">
                  <input type="radio" class="newdesign" id="a_position2" name="position" value="center" <?php echo ($accordion['conf']->position == 'center' ? ' checked="checked"' : ''); ?> />
                  <label for="a_position2">nur auf Startseite</label>
               </span>
            </div>
            <div class="conf_line">
               <span class="pro_pos1">
                  <?php /*<input type="checkbox" class="newdesign" id="a_active_mobile" name="active_mobile"<?php echo ($active_mobile == 'y' ? ' checked="checked"' : ''); ?> />
                  <label for="a_active_mobile">aktiv Mobile</label>*/?>
               </span>
               <span class="pro_pos2"></span>
               <span class="pro_pos3">
                  <input type="radio" class="newdesign" id="a_position3" name="position" value="bottom" <?php echo ($accordion['conf']->position == 'bottom' ? ' checked="checked"' : ''); ?> />
                  <label for="a_position3">immer unten</label>
               </span>
            </div>
            <div class="conf_line">
               <span class="pro_pos1"></span>
               <span class="pro_pos2"></span>
               <span class="pro_pos3"></span>
            </div>
         </div>

         <div class="conf_2">
            <div class="conf_line">
               <span class="pro_pos4">Art</span>
               <span class="pro_pos5">
                  <input type="radio" class="newdesign" id="galerie1" name="galerie" value="g" <?php echo ($accordion['conf']->galerie == 'g' ? ' checked="checked"' : ''); ?> onchange="$('.galerie').toggle(); $('.abschnitt').toggle();" />
                  <label for="galerie1">als Galerie</label>
               </span>
            </div>
            <div class="conf_line">
               <span class="pro_pos4"></span>
               <span class="pro_pos5">
                  <input type="radio" class="newdesign" id="galerie2" name="galerie" value="a" <?php echo ($accordion['conf']->galerie == 'a' ? ' checked="checked"' : ''); ?> onchange="$('.galerie').toggle(); $('.abschnitt').toggle();" />
                  <label for="galerie2">als Html-Abschnitt</label>
               </span>
            </div>
            <div class="conf_line">
               <span class="pro_pos4"><span class="abschnitt"<?php echo ($accordion['conf']->galerie == 'g' ? ' style="display:none;"' : ''); ?>>Breite</span></span>
               <span class="pro_pos5">
                  <span class="abschnitt"<?php echo ($accordion['conf']->galerie == 'g' ? ' style="display:none;"' : ''); ?>>
                     <input type="text" class="number" name="a_breite" value="<?php echo $accordion['conf']->a_breite; ?>" /> px
                  </span>
               </span>
            </div>
            <div class="conf_line">
               <span class="pro_pos4">
                  <span class="abschnitt"<?php echo ($accordion['conf']->galerie == 'g' ? ' style="display:none;"' : ''); ?>>Höhe</span>
               </span>
               <span class="pro_pos5">
                  <span class="abschnitt"<?php echo ($accordion['conf']->galerie == 'g' ? ' style="display:none;"' : ''); ?>>
                     <input type="text" class="number" name="a_hoehe" value="<?php echo $accordion['conf']->a_hoehe; ?>" /> px
                  </span>
               </span>
            </div>
         </div>

         <div class="conf_3">
            <div class="conf_line">
               <span class="pro_pos6">Öffnung</span>
               <span class="pro_pos7">
                  <input type="radio" class="newdesign" id="mouseover1" name="mouseover" value="r" <?php echo ($accordion['conf']->mouseover == 'r' ? ' checked="checked"' : ''); ?> />
                  <label for="mouseover1">per roll over</label>
               </span>
            </div>
            <div class="conf_line">
               <span class="pro_pos6"></span>
               <span class="pro_pos7">
                  <input type="radio" class="newdesign" id="mouseover2" name="mouseover" value="m" <?php echo ($accordion['conf']->mouseover == 'm' ? ' checked="checked"' : ''); ?> />
                  <label for="mouseover2">per mouse click</label>
               </span>
            </div>
            <div class="conf_line">
               <span class="pro_pos6">Dauer</span>
               <span class="pro_pos7">
                  <input type="text" class="number" name="dauer" value="<?php echo $accordion['conf']->dauer; ?>" />
               </span>
            </div>
            <div class="conf_line">
               <span class="pro_pos6">Wechsel</span>
               <span class="pro_pos7"><input type="text" class="number" name="wechsel" value="<?php echo $accordion['conf']->wechsel; ?>" /></span>
            </div>
         </div>
         <div class="clear"></div>
      </div>
      <div class="line_horizontal"></div>

      <div id="accordion_images" class="galerie" <?php echo($accordion['conf']->galerie != 'g' ? ' style="display:none"' : ''); ?>>
         <?php for ($i = 1; $i <= 15; $i++) { ?>
         <div class="img_box">
            <span class="img_nr">Bild <?php echo $i; ?></span>
            <span class="upload upload_button pointer" onclick="Design.uploudProImg('accordion', <?php echo $i; ?>, 'accordion_<?php echo $i; ?>');"></span>
            <span class="delete pointer far fa-trash-alt" onclick="Design.deleteProImg('accordion', <?php echo $i; ?>, 'accordion_<?php echo $i; ?>')" ></span>
            <span class="intern">
               <input type="checkbox" class="newdesign" id="a_intern_<?php echo $i; ?>" name="intern_<?php echo $i; ?>"<?php echo ($accordion['img_'.$i]->intern == 'y' ? ' checked="checked"' : ''); ?> />
               <label for="a_intern_<?php echo $i; ?>" class="after">intern</label>
            </span>
            <span class="accordion_img" href="<?php echo $accordion['img_'.$i]->image; ?>">
               <img id="accordion_<?php echo $i; ?>" class="image_bg" src="<?php echo $accordion['img_'.$i]->image.'?'.time(); ?>" alt="" />
            </span>
            <span class="linkurl">
               <input type="text" name="link_<?php echo $i; ?>" value="<?php echo $accordion['img_'.$i]->link; ?>" placeholder="http://domain.de" />
            </span>
         </div>
         <?php }?>
         <div class="clear"></div>
      </div>

      <div id="accordion_html" class="abschnitt" <?php echo($accordion['conf']->galerie == 'g' ? ' style="display:none"' : ''); ?>>
         <?php for ($i=1; $i <= 6; $i++) { ?>
         <article class="html_abschnitt">
            <div class="html_block">
               <p>
                  <input type="checkbox" class="newdesign" id="html_<?php echo $i; ?>_active" name="html_<?php echo $i; ?>_active" <?php echo ($accordion['html_'.$i]->active == 'y' ? 'checked="checked"' : ''); ?> />
                  <label for="html_<?php echo $i; ?>_active"><strong>HTML-Abschnitt&nbsp;<?php echo $i; ?></strong></label>
               </p>
               <p>Farbe
                  <input type="text" class="minicolors" name="html_<?php echo $i; ?>_color" value="<?php echo $accordion['html_'.$i]->color; ?>" />
               </p>
            </div>

            <div class="html_text">
               <textarea class="accordion_editor" name="html_<?php echo $i; ?>"><?php echo $accordion['html_'.$i]->text; ?></textarea>
            </div>
         </article>
         <?php }?>
      </div>
      <div class="clear"></div>
   </form>
<?php if (!isset($livedesigner)) { ?>
</div>
<?php } ?>
<?php if ($this->params->isAjax) { ?>
<script>
   extendedInit();
</script>
<?php echo $pro_script; ?>
<?php }
