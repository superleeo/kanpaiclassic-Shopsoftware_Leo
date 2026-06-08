            <div id="tabs1">
               <input type="radio" id="tabs1_tab1" class="tab" name="tabs1" <?php echo ($this->main->childs > 1 || $this->params->firma['ean_check'] == 'y' || $this->main->is_foto == 'y' ? ' checked="checked"' : ''); ?> autocomplete="off">
               <label id="tabs1_label1" for="tabs1_tab1">Varianten<?php echo (defined('CONF_MODULE_MATRIX') ? ' / Preismatrix' : ''); ?><span id="tabs_extra_varianten" class="ci_background<?php echo ((int)$this->main->childs > 1 ? ' active' : ''); ?>"></span></label>

               <?php if ($this->params->firma['staffelpreise'] == 'y') { ?>
               <input type="radio" id="tabs1_tab2" class="tab" name="tabs1" autocomplete="off">
               <label id="tabs1_label2" for="tabs1_tab2">Staffelpreise<span id="tabs_extra_staffelung" class="ci_background<?php echo ($this->main->staffelung != '' && $this->main->staffelung != 'n;100;-10' ? ' active' : ''); ?>"></span></label>
               <?php } ?>

               <?php if (defined('CONF_MODULE_RABATTE')) { ?>
               <input type="radio" id="tabs1_tab3" class="tab" name="tabs1" autocomplete="off">
               <label id="tabs1_label3" for="tabs1_tab3">Rabattgruppen<span id="tabs_extra_rabatte" class="ci_color"><?php echo chr((int)$this->main->artikelgruppe + 65); ?></span></label>
               <?php } ?>

               <input type="radio" id="tabs1_tab4" class="tab easy" name="tabs1" autocomplete="off">
               <label id="tabs1_label4" for="tabs1_tab4" class="easy">Versand<span id="tabs_extra_versand" class="ci_color"><?php echo ((int)$this->main->spedition > 0 ? 'Sp'.$this->main->spedition : ''); ?></span></label>

               <div class="clear"></div>

               <section id="tabs1_content1">
                  <div id="articles">
                     <div class="mobile_slide">
                        <div class="mobile_slide_inner">
                           <?php if ($this->mode != 'foto') { // Normaler Artikel ?>
                           <div id="art_details_title" class="<?php echo (defined('CONF_MODULE_MATRIX') == 'y' ? ' matrix' : '').($this->params->firma['downloads'] == 'y' ? ' download' : ''); ?>">
                              <div class="xleft">
                                 <div class="xartnr txt_bez ellipsis">Art.-Nr</div>
                                 <div class="xname txt_bez ellipsis">Art.-Name</div>
                              </div>
                              <div class="xcenter">
                                 <div class="xmerkmal1 txt_bez ellipsis">Merkmal 1</div>
                                 <div class="xwert1 txt_bez ellipsis">Wert 1</div>
                                 <div class="xmerkmal2 txt_bez ellipsis">Merkmal 2</div>
                                 <div class="xwert2 txt_bez ellipsis">Wert 2</div>
                                 <div class="xnetto txt_bez ellipsis">Preis netto</div>
                                 <div class="xangebot txt_bez ellipsis">Angebot netto</div>
                                 <div class="xbrutto txt_bez ellipsis">Preis brutto</div>
                              </div>
                              <div class="xright">
                                 <?php if (defined('')) { ?>
                                 <div class="xdownload txt_bez ellipsis"></div>
                                 <div class="xlager txt_bez ellipsis">Lager</div>
                                 <?php } else { ?>
                                 <div class="xdl_lager txt_bez ellipsis">Lager</div>
                                 <?php } ?>
                              </div>
                              <div class="clear"></div>
                           </div>

                           <?php } else { // Foto-Artikel?>
                           <div id="art_details_title" class="module_foto">
                              <div class="xleft">
                                 <div class="xartnr_foto txt_bez ellipsis">Art.-Nr</div>
                                 <div class="xname_foto txt_bez ellipsis">Art.-Name   </div>
                              </div>
                              <div class="xcenter">
                                 <div class="xmerkmal1 txt_bez ellipsis">Größe</div>
                                 <div class="xwert1 txt_bez ellipsis" style="visibility:hidden;">Wert 1</div>
                                 <div class="xmerkmal2 txt_bez ellipsis">Pixel</div>
                                 <div class="xwert2 txt_bez ellipsis" style="visibility:hidden;">Wert 2</div>
                                 <div class="xnetto txt_bez ellipsis">Preis (netto)</div>
                                 <div class="xangebot txt_bez ellipsis">Angebot (netto)</div>
                                 <div class="xbrutto txt_bez ellipsis">Preis (brutto)</div>
                              </div>
                              <div class="xright">
                                 <div class="xlager txt_bez">Lager</div>
                                 <div class="xdl_menge">&nbsp;</div>
                              </div>
                              <div class="clear"></div>
                           </div>
                           <?php } ?>

                           <?php // Artikel und Varianten anzeigen ?>
                           <div id="article_block">
                           <?php echo $details; ?>
                           </div>
                        </div>
                     </div>

                     <?php if ($this->mode != 'foto') { // Normaler Artikel ?>
                     <div id="articleDetailsAdd" class="<?php echo(defined('CONF_MODULE_MATRIX') == 'y' ? ' matrix' : ''); ?>"<?php echo ($this->mode == 'foto' ? ' style="display:none"' : ''); ?>>
                        <div id="addVariante" class="">
                           <div class="button_ci txt_but" onclick="Artikel.varianteNew();">+ Variante</div>
                        </div>

                        <?php if (!defined('CONF_MODULE_PORTAL') || defined('CONF_MODULE_PORTAL') && $_SESSION['haendler'] == 'n') { ?>
                        <div class="xcenter">
                           <div id="addMerkmal" class="xmerkmal1">
                              <div class="button button_border txt_but" onclick="Artikel.merkmalePopup();">Merkmale</div>
                           </div>
                           <div id="addWert" class="xwert1">
                              <div class="button button_border txt_but" onclick="Artikel.wertePopup();">Werte</div>
                           </div>
                        </div>
                        <?php } ?>

                        <div class="ean_dl_modify xright">
                           <div class="easy">
                              <input type="checkbox" class="newdesign" id="ean_check"<?php echo ($this->params->firma['ean_check'] == 'y' ? ' checked="checked"' : '' ); ?> onchange="Artikel.eanCheck('ean');" />
                              <label for="ean_check"></label>EAN, MPN Lieferant, Grundpreis, Einkaufspreis
                              <br />
                           </div>
                           <input type="checkbox" class="newdesign" id="download_check"<?php echo ($this->params->firma['downloads'] == 'y' ? ' checked="checked"' : '' ); ?> onchange="Artikel.eanCheck('dl');" />
                           <label for="download_check"></label>Downloadartikel-Upload
                        </div>
                        <div class="clear"></div>
                     </div>
                     <?php } ?>
                  </div>
               </section>

               <section id="tabs1_content2">
                  <?php // Staffelung ?>
                  <div id="staffelung"<?php echo ($this->params->firma['staffelpreise'] == 'n' ? ' style="visibility:hidden;"' : ''); ?>>
                     <div class="staffelung_titel">
                        <span class="txt_bez">Staffelung (Stück)</span>
                        <?php if ($this->params->firma['kleingewerbe'] == 'y' || $this->params->firma['tax_active'] == 'n') { ?>
                        <span class="txt_bez">Differenz</span>
                        <?php } else { ?>
                        <span class="txt_bez">Differenz (netto)</span>
                        <span class="txt_bez">Differenz (brutto)</span>
                        <?php } ?>
                     </div>
                     <div class="clear"></div>

                     <div id="staffelung_block">
                        <?php echo $this->getStaffelung($parent_id); ?>
                     </div>

                     <div class="button_ci txt_but" onclick="Artikel.staffelungAdd();">hinzufügen</div>
                  </div>
               </section>

               <section id="tabs1_content3">
               <?php if (defined('CONF_MODULE_RABATTE')) { ?>
                  <div id="rabatte">
                     <div class="rabatttitel"><a href="<?php echo HELP_LINK; ?>/tools/rabattgruppen/" target="_blank"><span class="help_kanpaiclassic"></span><a><span class="txt_tit">Rabattgruppe</span></a></div>
                     <div class="rabatt_icons">
                        <div class="rabattgruppe gruppe0<?php echo ((int)$this->main->artikelgruppe == 0 ? ' active' : ''); ?>" onclick="Artikel.changeRabatt(0);"></div>
                        <div class="rabattgruppe gruppe1<?php echo ((int)$this->main->artikelgruppe == 1 ? ' active' : ''); ?>" onclick="Artikel.changeRabatt(1);"></div>
                        <div class="rabattgruppe gruppe2<?php echo ((int)$this->main->artikelgruppe == 2 ? ' active' : ''); ?>" onclick="Artikel.changeRabatt(2);"></div>
                        <div class="rabattgruppe gruppe3<?php echo ((int)$this->main->artikelgruppe == 3 ? ' active' : ''); ?>" onclick="Artikel.changeRabatt(3);"></div>
                        <div class="rabattgruppe gruppe4<?php echo ((int)$this->main->artikelgruppe == 4 ? ' active' : ''); ?>" onclick="Artikel.changeRabatt(4);"></div>
                        <div class="rabattgruppe gruppe5<?php echo ((int)$this->main->artikelgruppe == 5 ? ' active' : ''); ?>" onclick="Artikel.changeRabatt(5);"></div>
                        <div class="rabattgruppe gruppe6<?php echo ((int)$this->main->artikelgruppe == 6 ? ' active' : ''); ?>" onclick="Artikel.changeRabatt(6);"></div>
                        <div class="rabattgruppe gruppe7<?php echo ((int)$this->main->artikelgruppe == 7 ? ' active' : ''); ?>" onclick="Artikel.changeRabatt(7);"></div>
                        <input type="hidden" id="rabattgruppe" name="rabattgruppe" value="<?php echo $this->main->artikelgruppe; ?>" />
                     </div>
                     <div class="clear"></div>
                  </div>
                  <div class="clear"></div>
               <?php } ?>
               </section>

               <section id="tabs1_content4">
                  <div id="versand">
                     <div id="versand_left">
                        <div class="easy versand_line">
                           <span class="title">indiv. Versandpreis <span class="help ci_color" title="Nur wenn individuelle Versandkosten genutzt werden"></span></span>
                           <span class="val">
                              <input type="text" class="val txt_inp right" id="versand_preis" name="versand_preis" <?php echo ((int)$this->main->spedition > 0 ? 'disabled="disabled"' : ''); ?> value="<?php echo number_format((float)$this->main->versand_preis, 2, ',', '.'); ?>" onchange="Artikel.indVersand('netto_tab1');" />
                           </span>
                           <span class="extra">(netto)</span>
                        </div>

                        <div class="versand_line">
                           <span class='title'>Versandgewicht <span class="help ci_color" title="Nur wenn gewichtsabhängige Versandkosten genutzt werden"></span></span>
                           <span class="val">
                              <input type="text" class="val txt_inp right" id="gewicht" name="gewicht" value="<?php echo number_format($this->main->gewicht, 3, ',', '.'); ?>"  onchange="this.value = point2komma(parseFloat(komma2point(this.value)).toFixed(3)); Artikel.checkGewicht(); $('#gewicht_oben').val($(this).val());" />
                           </span>
                           <span class='extra'>Kg</span>
                        </div>

                        <?php if (defined('CONF_MODULE_SPEDITION')) { ?>
                        <div class="versand_line">
                           <span class="title">Spedition <span class="no_help" title=""></span></span>
                           <span class="val selectbox30">
                              <select id="spedition" onchange="(parseInt($(this).val()) === 0 ? $('#tabs_extra_versand').html('') : $('#tabs_extra_versand').html('Sp'+$(this).val()));
                                                               $('#versand_preis').attr('disabled', parseInt($(this).val()) > 0 ? true : false);
                                                               $('#versand_preis_oben').attr('disabled', parseInt($(this).val()) > 0 ? true : false);
                                                               $('#versand_preis_brutto').attr('disabled', parseInt($(this).val()) > 0 ? true : false);">
                                 <option value="0"<?php echo($this->main->spedition == 0 ? ' selected="selected"' : '') ; ?> />aus</option>
                                 <option value="1"<?php echo($this->main->spedition == 1 ? ' selected="selected"' : '') ; ?> />Spedition1</option>
                                 <option value="2"<?php echo($this->main->spedition == 2 ? ' selected="selected"' : '') ; ?> />Spedition2</option>
                                 <option value="3"<?php echo($this->main->spedition == 3 ? ' selected="selected"' : '') ; ?> />Spedition3</option>
                              </select>
                           </span>
                        </div>
                        <?php } ?>
                     </div>

                     <div id="versand_right">
                        <div class="versand_line">
                           <span class="title">Lieferzeit</span>
                           <span class="val">
                              <input type="text" class="val txt_inp right" id="lieferfrist" name="lieferfrist" value="<?php echo $this->main->lieferfrist; ?>" maxlength="8" onchange="$('#lieferfrist2').val($(this).val());" />
                           </span>
                           <span class="extra">Tage</span>
                        </div>

                        <div class="easy versand_line">
                           <span class="title">Verkaufseinheit VE</span>
                           <span class="val">
                              <input type="text" id="vpe" class="val txt_inp right" value="<?php echo $this->main->vpe; ?>" />
                           </span>
                        </div>

                        <div class="easy versand_line">
                           <span class="title">Größe</span>
                           <input type="text" class="val txt_inp right" id="vpm" name="vpm" value="<?php echo $this->main->vpm; ?>" />
                        </div>
                     </div>
                     <div class="clear"></div>
                  </div>
               </section>
            </div>
