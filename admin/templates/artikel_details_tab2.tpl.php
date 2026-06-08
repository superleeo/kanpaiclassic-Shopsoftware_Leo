            <div id="tabs2">
               <?php if (defined('CONF_MODULE_MEGACONFIGURATOR')) { ?>
               <input type="radio" id="tabs2_tab1" class="tab" name="tabs2" autocomplete="off" />
               <label id="tabs2_label1" for="tabs2_tab1"  onclick="setTimeout(function() { Megakonfigurator.sameHeight(); }, 100);">Megakonfigurator<span id="tabs_extra_mega" class="ci_background <?php echo ($this->main->configurator_check == 'y' ? ' active' : ''); ?>"></span></label>
               <?php } ?>

               <?php if (defined('CONF_MODULE_MIXER_ARTIKEL')) { ?>
               <input type="radio" id="tabs2_tab2" class="tab" name="tabs2" autocomplete="off">
               <label id="tabs2_label2" for="tabs2_tab2">Mixer<span id="tabs_extra_mixer" class="ci_background<?php echo ($this->main->mixer_artikel_check == 'y' ? ' active' : ''); ?>"></span></label>
               <?php } ?>

               <?php if (defined('CONF_MODULE_NAEHRWERTE')) { ?>
               <input type="radio" id="tabs2_tab3" class="tab" name="tabs2" autocomplete="off">
               <label id="tabs2_label3" for="tabs2_tab3">Nährwerte pro 100g<span id="tabs_extra_naehrwerte" class="ci_background<?php echo($this->main->naehrwerte_check == 'y' ? ' active' : ''); ?>"></span></label>
               <?php } ?>

               <?php if (defined('CONF_MODULE_MASSEINGABE')) { ?>
               <input type="radio" id="tabs2_tab4" class="tab" name="tabs2" autocomplete="off">
               <label id="tabs2_label4" for="tabs2_tab4">Maßeingabe<span id="tabs_extra_masseingabe" class="ci_background<?php echo ($this->main->masse_check == 'y' ? ' active' : ''); ?>"></span></label>
               <?php } ?>

               <?php if (defined('CONF_MODULE_MOTIVUL')) { ?>
               <input type="radio" id="tabs2_tab5" class="tab" name="tabs2" autocomplete="off">
               <label id="tabs2_label5" for="tabs2_tab5">Motiv/Text-Upload<span id="tabs_extra_motiv" class="ci_background<?php echo ($this->main->motiv_uploadt_check == 'y' || $this->main->motiv_uploadp_check == 'y' ? ' active' : ''); ?>"></span></label>
               <?php } ?>

               <div class="clear"></div>

               <?php if (defined('CONF_MODULE_MEGACONFIGURATOR')) { ?>
               <section id="tabs2_content1">
               <?php $this->configurator->getArticleConfigurator($this->main); ?>
               </section>
               <?php }

               if (defined('CONF_MODULE_MIXER_ARTIKEL')) { ?>
               <section id="tabs2_content2">
               <?php // Artikel-Mixer ?>
                  <div id="mixer">
                     <div class="titelzeile_mixer">
                        <a target="_blank" href="<?php echo HELP_LINK; ?>/o46/artikel-mixer/" class="help_kanpaiclassic"></a>
                        <input type="checkbox" class="newdesign" id="mixer_artikel_check" name="mixer_artikel_check"<?php echo ($this->main->mixer_artikel_check == 'y' ? ' checked="checked"' : ''); ?> onchange="($(this).prop('checked') ? $('#tabs_extra_mixer').addClass('active') : $('#tabs_extra_mixer').removeClass('active'));" />
                        <label for="mixer_artikel_check"></label>
                        <span class="txt_tit">Mixer</span>
                        <div class="button_ci txt_but" onclick="Artikelmixer.save()">speichern</div>
                     </div>

                     <div id="mixer_block">
                        <div class="mixer_left">
                           <div class="mixer_line">
                              <input type="checkbox" class="newdesign" id="mixer_gewicht_check" name="mixer_gewicht_check"<?php echo($this->main->mixer_gewicht_check == 'y' ? ' checked="checked"' : ''); ?>>
                              <label for="mixer_gewicht_check"></label>&nbsp;Gesamtgewicht
                              <input type="text" id="mixer_gewicht" name="mixer_gewicht" class="txt_inp" value="<?php echo number_format($this->main->mixer_gewicht, 0, '', ''); ?>" />
                              <span id="mixer_gewicht_ge"><?php echo $mixer_gewicht_ge; ?></span>
                           </div>
                           <div class="mixer_line">
                              <input type="checkbox" class="newdesign" id="mixer_naehrwerte_check" name="mixer_naehrwerte_check"<?php echo($this->main->mixer_naehrwerte_check == 'y' ? ' checked="checked"' : ''); ?>>
                              <label for="mixer_naehrwerte_check"></label>&nbsp;Grundeinheit und Nährwerte
                           </div>
                        </div>

                        <div class="mixer_right">
                           <div id="mixer_list">
                              <?php $mixer_data = $this->_mixerLoad($parent_id); ?>
                              <?php require_once SHOP_PATH.'/classes/modules/mixer_artikel/mixer_articles.tpl.php'; ?>
                              <?php echo $html; ?>
                              <div class="clear"></div>
                           </div>
                           <div id="new_mixer_article" class="button_ci txt_but pointer cursor" onclick="Artikelmixer.popup();">hinzufügen</div>
                        </div>
                        <div class="clear"></div>
                     </div>
                  </div>
               </section>
               <?php } ?>

               <?php if (defined('CONF_MODULE_NAEHRWERTE')) { ?>
               <section id="tabs2_content3">
                  <?php // Nährwerte und Zutaten ?>
                  <?php $naehrwerte = $this->naehrwerte($parent_id); ?>
                  <?php $zutaten    = $this->zutaten($parent_id); ?>
                  <?php include_once ADMIN_PATH.'/templates/article_naehrwerte.tpl.php'; ?>
                  <?php echo $html; ?>
               </section>
               <?php } ?>

               <?php if (defined('CONF_MODULE_MASSEINGABE')) { ?>
               <section id="tabs2_content4">
                  <div id="masseingabe">
                     <div class="mass_line">
                        <div class="txt_bez">
                           <input type="checkbox" class="newdesign" name="masse_check" id="masse_check"<?php echo ($this->main->masse_check == 'y' ? ' checked="checked"' : ''); ?> onchange="($(this).prop('checked') ? $('#tabs_extra_masseingabe').addClass('active') : $('#tabs_extra_masseingabe').removeClass('active'));" />
                           <label for="masse_check"></label>Kunde soll Maß eingeben
                        </div>
                     </div>
                     <div class="mass_line">
                        <div class="pos1">Mindestmaß</div>
                        <div class="pos2">
                           <input class="txt_inp right" type="text" id="masse_min" name="masse_min" value="<?php echo number_format($this->main->masse_min, $this->main->masse_komma, ',', '.'); ?>" onblur="this.value = point2komma(parseFloat(komma2point(this.value)).toFixed($('#masse_komma').val()));" />
                        </div>
                        <div class="pos3">
                           1 = <span id="grundeinheit_rechner_name"><?php echo ($this->text->get('ge', ($this->main->grundeinheit_rechner != '' ? $this->main->grundeinheit_rechner : 'stk'))); ?></span>
                           <span class="fas fa-pencil-alt pointer" onclick="Artikel.rechnerPopup();"></span>
                           <input type="hidden" id="grundeinheit_rechner" value="<?php echo $this->main->grundeinheit_rechner; ?>" />
                        </div>
                     </div>

                     <div class="mass_line">
                        <div class="pos1">Kommastellen</div>
                        <div class="pos2">
                           <input type="text" class="txt_inp right" id="masse_komma" name="masse_komma" value="<?php echo $this->main->masse_komma; ?>" onblur="$('#masse_min').val( point2komma(parseFloat(komma2point($('#masse_min').val())).toFixed(this.value)) );" />
                        </div>
                     </div>

                     <div class="easy mass_line">
                        <div class="pos1">Rechner</div>
                        <div class="pos2_3">
                           <input type="checkbox" class="newdesign" id="rechner_check" name="rechner_check" onchange="($(this).prop('checked') ? $('#rechner_mode').show() && $('#rechner_show').show() : $('#rechner_mode').hide() && $('#rechner_show').hide());"<?php echo ($this->main->rechner_check == 'y' ? ' checked="checked"' : ''); ?> />
                           <label for="rechner_check"></label>&nbsp;&nbsp;
                           <span id="rechner_mode"<?php echo ($this->main->rechner_check != 'y' ? ' style="display:none;"' : ''); ?>>
                              <input type="radio" class="newdesign" class="txt_inp" id="rechner_mode1" name="rechner_mode" value="1"<?php echo ((int)$this->main->rechner_mode ==  1 ? ' checked="checked"' : ''); ?> />
                              <label for="rechner_mode1"></label><span>(B)</span>&nbsp;&nbsp;
                              <input type="radio" class="newdesign" class="txt_inp" id="rechner_mode2" name="rechner_mode" value="2"<?php echo ((int)$this->main->rechner_mode ==  2 ? ' checked="checked"' : ''); ?> />
                              <label for="rechner_mode2"></label><span>(B x H)</span>&nbsp;&nbsp;
                              <input type="radio" class="newdesign" class="txt_inp" id="rechner_mode3" name="rechner_mode" value="3"<?php echo ((int)$this->main->rechner_mode ==  3 ? ' checked="checked"' : ''); ?> />
                              <label for="rechner_mode3"></label><span>(B x H x T)</span>
                           </span>
                        </div>
                     </div>
                  </div>
               </section>
               <?php } else { ?>
               <input type="checkbox" style="display:none;" name="masse_check" id="masse_check" />
               <input type="checkbox" style="display:none" id="rechner_check" name="rechner_check" />
               <input type="hidden" id="masse_min" name="masse_min" value="<?php echo $this->main->masse_min; ?>" />
               <input type="hidden" id="masse_komma" name="masse_komma" value="<?php echo $this->main->masse_komma; ?>" />
               <?php } ?>

               <?php if (defined('CONF_MODULE_MOTIVUL')) { ?>
               <section id="tabs2_content5">
                  <div id="motivupload">
                     <div>
                        <input type="checkbox" class="newdesign" id="motiv_uploadt_check" name="motiv_uploadt_check" <?php echo ($this->main->motiv_uploadt_check == 'y' ? ' checked="checked"' : ''); ?> onclick="Artikel.motivExtra()" />
                        <label for="motiv_uploadt_check"></label>Kunde soll Text hochladen
                     </div>
                     <div>
                        <input type="checkbox" class="newdesign" id="motiv_uploadp_check" name="motiv_uploadp_check" <?php echo ($this->main->motiv_uploadp_check == 'y' ? ' checked="checked"' : ''); ?> onclick="Artikel.motivExtra()" />
                        <label for="motiv_uploadp_check"></label>Kunde soll Bild hochladen
                     </div>
                  </div>
               </section>
               <?php } else { // Checkbox versteckt ?>
               <span style="display:none;">
                  <input type="checkbox" id="motiv_uploadp_check" name="motiv_uploadp_check" />
                  <input type="checkbox" id="motiv_uploadt_check" name="motiv_uploadt_check" />
               </span>
               <?php } ?>
            </div>
