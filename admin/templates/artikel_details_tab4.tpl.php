            <div id="tabs4">
               <?php if (defined('CONF_MODULE_MUSIKPLAYER')) { ?>
               <input type="radio" id="tabs4_tab1" class="tab" name="tabs5" autocomplete="off">
               <label id="tabs4_label1" for="tabs4_tab1">Musik-Player<span id="tabs_extra_player" class="ci_background<?php echo ($this->main->module_musikplayer > 0 ? ' active' : ''); ?>"></span></label>
               <?php } ?>

               <?php if (defined('CONF_MODULE_ZUBEHOER')) { ?>
               <input type="radio" id="tabs4_tab2" class="tab" name="tabs5" autocomplete="off">
               <label id="tabs4_label2" for="tabs4_tab2">Zubehör<span id="tabs_extra_zubehoer" class="ci_background<?php echo ($this->main->module_zubehoer > 0 ? ' active' : ''); ?>"></span></label>
               <?php } ?>

               <?php if (defined('CONF_MODULE_AEHNLICHE')) { ?>
               <input type="radio" id="tabs4_tab3" class="tab" name="tabs5" autocomplete="off">
               <label id="tabs4_label3" for="tabs4_tab3">Ähnliche Artikel<span id="tabs_extra_aehnliche" class="ci_background<?php echo ($this->main->module_aehnliche > 0 ? ' active' : ''); ?>"></span></label>
               <?php } ?>

               <?php if (defined('CONF_MODULE_CROSSPROMO')) { ?>
               <input type="radio" id="tabs4_tab4" class="tab" name="tabs5" autocomplete="off">
               <label id="tabs4_label4" for="tabs4_tab4">Crosspromotion<span id="tabs_extra_slider" class="ci_background<?php echo ($this->main->module_slider == 'y' ? ' active' : ''); ?>"></span></label>
               <?php } ?>

               <?php if ($this->params->firma['schnittstellen'] == 'y') { ?>
               <input type="radio" id="tabs4_tab5" class="tab easy" name="tabs5" autocomplete="off">
               <label id="tabs4_label5" for="tabs4_tab5" class="easy">Google-Shopping<span id="tabs_extra_google" class="ci_background""></span></label>
               <?php } ?>

               <?php if (defined('CONF_MODULE_EBAY') && $this->params->firma['ebay_api'] == 'y') { ?>
               <input type="radio" id="tabs4_tab6" class="tab" name="tabs5" autocomplete="off">
               <label id="tabs4_label6" for="tabs4_tab6">Ebay<span id="tabs_extra_ebay" class="ci_background<?php echo (isset($this->main->ebay_data->cat_ids) && $this->main->ebay_data->cat_ids !== '' ? ' active' : ''); ?>"></span></label>
               <?php } ?>

               <div class="clear"></div>

               <?php if (defined('CONF_MODULE_MUSIKPLAYER')) { ?>
               <section id="tabs4_content1">
                  <div id="musikplayere">
                     <div class="titelzeile2">
                        <div class="left">
                           <a class="help_kanpaiclassic" target="_blank" href="<?php echo HELP_LINK; ?>/o58/hoerbeispiele-musikplayer/"></a>
                           <h1 class="txt_tit">Hörbeispiele</h1>
                        </div>
                        <div class="right">
                           <div id="articleSaveList" class="button_ci txt_but" onclick="Musikplayer.save(<?php echo $parent_id; ?>);">speichern</div>
                        </div>
                     </div>
                     <div id="musikplayer">
                     <?php $musikplayer = \KANPAICLASSIC\Control::getModuleMusikplayer(); ?>
                     <?php echo $musikplayer->renderBE($parent_id); ?>
                     </div>
                  </div>
                  <div class="clear"></div>
               </section>
               <?php } ?>

               <?php if (defined('CONF_MODULE_ZUBEHOER')) { ?>
               <section id="tabs4_content2">
                  <?php $z_data = $this->_zubehoerLoad($parent_id); ?>
                  <?php $z_lang = $this->_zubehoerLangData($parent_id); ?>
                  <div id="zubehoer">
                     <div class="zubehoer_title">
                        <div class="zubehoer_title_left">
                           <a class="help_kanpaiclassic" href="<?php echo HELP_LINK; ?>/o56/zubehoer-modul/" target="_blank"></a>
                           <h1>Zubehör / Empfehlung</h1>
                        </div>
                        <div class="zubehoer_title_right right">
                           <span class="button_ci pointer" onclick="Zubehoer.save();">speichern</span>
                        </div>
                        <div class="zubehoer_title_center">
                           <span>Überschrift (<?php echo $this->params->selected_lang; ?>):
                           <input type="text" class="txt_tit" id="ztitle" name="ztitle" value="<?php echo $z_lang->{$this->params->selected_lang}; ?>" />
                        </div>
                        <div class="clear"></div>
                     </div>

                     <div id="zubehoer_list">
                     <?php require_once SHOP_PATH.'/classes/modules/zubehoermodul/zubehoermodul.tpl.php'; ?>
                     <?php echo $html; ?>
                     <div class="clear"></div>
                     </div>

                     <div id="new_article" class="button_ci pointer txt_but" onclick="Zubehoer.popup();">hinzufügen</div>
                     <div class="txt_tit letzte">
                        <input type="checkbox" class="newdesign" name="letzte" id="letzte"<?php echo ($this->params->firma['letzte'] == 'y' ? ' checked="checked"' : ''); ?> />
                        <label for="letzte"></label><span class="help ci_color pointer" title="&bdquo;zuletzt angesehene Artikel&ldquo; erscheinen nur, wenn Design auf &bdquo;horiz. Kategorien&ldquo; eingestellt ist."></span>zuletzt angesehen
                     </div>
                  </div>
               </section>
               <?php } ?>

               <?php if (defined('CONF_MODULE_AEHNLICHE')) { ?>
               <section id="tabs4_content3">
                  <?php $ae_data = $this->_aehnlicheLoad($parent_id); ?>
                  <?php $ae_lang = $this->_aehnlicheLangData($parent_id); ?>
                  <div id="aehnliche">
                     <div class="zubehoer_title">
                        <div class="zubehoer_title_left">
                           <a class="help_kanpaiclassic" href="<?php echo HELP_LINK; ?>/o56/zubehoer-modul/" target="_blank"></a>
                           <h1>Ähnliche Artikel</h1>
                        </div>
                        <div class="zubehoer_title_right right">
                           <span class="button_ci pointer" onclick="Aehnliche.save();">speichern</span>
                        </div>
                        <div class="zubehoer_title_center">
                           <span>Überschrift (<?php echo $this->params->selected_lang; ?>):
                           <input type="text" class="txt_tit" id="aetitle" name="aetitle" value="<?php echo $ae_lang->{$this->params->selected_lang}; ?>" />
                        </div>
                        <div class="clear"></div>
                     </div>

                     <div id="aehnliche_list">
                     <?php require_once SHOP_PATH.'/classes/modules/aehnliche_artikel/aehnliche_artikel.tpl.php'; ?>
                     <?php echo $html; ?>
                     <div class="clear"></div>
                     </div>
                     <div id="new_ae_article" class="button_ci txt_but pointer" onclick="Aehnliche.popup();">hinzufügen</div>
                  </div>
               </section>
               <?php } ?>

               <?php if (defined('CONF_MODULE_CROSSPROMO')) { ?>
               <section id="tabs4_content4">
                  <?php $crosspromo = \KANPAICLASSIC\Control::getModuleZubehoerSlider(); ?>
                  <?php $as_slider = $crosspromo->getSliderBe($parent_id); ?>
                  <div id="zubehoerslider">
                     <div class="slider_title">
                        <input type="checkbox" class="newdesign" id="slider_active_check" name="slider_active_check"<?php echo ($as_slider[15] == 'y' ? ' checked="checked"' : ''); ?> onchange="($(this).prop('checked') ? $('#tabs_extra_slider').addClass('active') : $('#tabs_extra_slider').removeClass('active'));" />
                        <label for="slider_active_check"></label>
                        <a class="help_kanpaiclassic" href="<?php echo HELP_LINK; ?>/o57/crosspromotion-slider/" target="_blank" alt=""></a>
                        <h1 class="txt_tit">Crosspromotion</h1>
                        <span class="slider_ueberschrift">
                           Überschrift (<?php echo $this->params->selected_lang; ?>)
                           <input type="text" class="txt_tit" id="slider_text" name="slider_text" value="<?php echo ($as_slider[16]); ?>" />
                        </span>
                        <div class="button_ci txt_but" onclick="Zubehoerslider.save()">speichern</div>
                     </div>
                     <div class="clear"></div>
                     <div id="slider_wrapper">
                     <?php include SHOP_PATH.'/classes/modules/zubehoerslider/zubehoerslider.tpl.php'; ?>
                     </div>
                  </div>
               </section>
               <?php } ?>

               <section id="tabs4_content5" class="easy">
                  <div id="google">
                     <?php echo $this->_getGoogle(); ?>
                     <div class="clear"></div>
                  </div>
               </section>

               <?php if (defined('CONF_MODULE_EBAY') && $this->params->firma['ebay_api'] == 'y') { ?>
               <section id="tabs4_content6">
                  <?php $ebay = \KANPAICLASSIC\Control::getEbay(); ?>
                  <div id="ebay" <?php echo ($this->params->firma['ebay_api'] == 'n' ? 'style="display:none"' : ''); ?>>
                     <?php if ($parent_id > 0) { ?>
                     <?php echo $ebay->printEbayDetails($parent_id, $this->main->ebay_data); ?>
                     <?php } else { ?>
                     <div class="txt_bez">Ebay erst nach speichern verfügbar!<br /><br /></div>
                     <?php } ?>
                  </div>
               </section>
               <?php } ?>
            </div>
