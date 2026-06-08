            <div id="tabs3">
               <input type="radio" id="tabs3_tab1" class="tab" name="tabs3" autocomplete="off" checked="XXXchecked">
               <label id="tabs3_label1" for="tabs3_tab1">Bilder & Beschreibung<span id="tabs_extra_bilder"></span></label>

               <?php if (defined('CONF_MODULE_TIMER')) { ?>
               <input type="radio" id="tabs3_tab2" class="tab" name="tabs3" autocomplete="off">
               <label id="tabs3_label2" for="tabs3_tab2">Artikeltimer<span id="tabs_extra_timer" class="ci_color"></span></label>
               <?php } ?>

               <input type="radio" id="tabs3_tab3" class="tab" name="tabs3" autocomplete="off">
               <label id="tabs3_label3" for="tabs3_tab3">SEO<span id="tabs_extra_seo"></span></label>
               <div class="clear"></div>

               <section id="tabs3_content1">
                  <?php // Vorschaubilder  ?>
                  <?php
                  $thumb   = '';
                  $thumb_x = 162;
                  $thumb_y = 122;
                  $top     = 0;

                  $image     = $this->main->image;
                  $is_img    = true;
                  $img_hover = $this->main->image_hover;
                  $is_hover  = false;

                  // Kein Bild vorhanden
                  if ($image == 'nopic.png' || $image == '') {
                     $image = ADMIN_URL.'/img/nopic.png';
                     $image_tn = $image;
                     $is_img = false;
                  }

                  // Bild auf anderem Server
                  else if (substr($image, 0, 4) == 'http') {
                     $image_tn = str_replace('.jpg', '', $image).'_tn.jpg';
                     $image    = str_replace('/pictures/', '/pictures/original/', $image);
                  }

                  // Bei Multishop auf anderem Server
                  else if ($this->params->multishop) {
                     $image_tn = \KANPAICLASSIC\Helper::getData('multishop_images').'/pictures/'.$image.'_tn.jpg';
                     $image    = \KANPAICLASSIC\Helper::getData('multishop_images').'/pictures/original/'.$image.'.jpg';
                  }

                  // Bild lokal vorhanden
                  else {
                     $image_tn = $img_url.$image.'_tn.jpg?'.time();
                     $image    = $img_url.'original/'.$image.'.jpg?'.time();
                  }

                  if (defined('CONF_MODULE_ARTIKELGRAFIK') && $img_hover != '') {
                     $img_hover = $img_url.$img_hover.'_tn.jpg?'.time();
                  }
                  $versandfrei    = ($this->main->versandfrei_check == 'y' && ((int)$this->params->firma['versandart_1'] == 1 || (int)$this->params->firma['versandart_1']) && (float)$this->main->versand_preis == 0 ? true : false);
                  $artikelgrafik1 = ($this->main->artikelgrafik1_check == 'y' ? true : false);
                  $artikelgrafik2 = ($this->main->artikelgrafik2_check == 'y' ? true : false);
                  $artikelgrafik3 = ($this->main->artikelgrafik3_check == 'y' ? true : false);
                  $artikelgrafik4 = ($this->main->artikelgrafik4_check == 'y' ? true : false);
                  $artikelgrafik5 = ($this->main->artikelgrafik5_check == 'y' ? true : false);
                  $artikelgrafik6 = ($this->main->artikelgrafik6_check == 'y' ? true : false);

                  $ag_img1 = (is_file(TEMPLATE_PATH.'/images/artikelgrafik1_'.$this->params->selected_lang.'.png') ? TEMPLATE_URL.'/images/artikelgrafik1_'.$this->params->selected_lang.'.png' : ADMIN_URL.'/img/nopic.png');
                  $ag_img2 = (is_file(TEMPLATE_PATH.'/images/artikelgrafik2_'.$this->params->selected_lang.'.png') ? TEMPLATE_URL.'/images/artikelgrafik2_'.$this->params->selected_lang.'.png' : ADMIN_URL.'/img/nopic.png');
                  $ag_img3 = (is_file(TEMPLATE_PATH.'/images/artikelgrafik3_'.$this->params->selected_lang.'.png') ? TEMPLATE_URL.'/images/artikelgrafik3_'.$this->params->selected_lang.'.png' : ADMIN_URL.'/img/nopic.png');
                  $ag_img4 = (is_file(TEMPLATE_PATH.'/images/artikelgrafik4_'.$this->params->selected_lang.'.png') ? TEMPLATE_URL.'/images/artikelgrafik4_'.$this->params->selected_lang.'.png' : ADMIN_URL.'/img/nopic.png');
                  $ag_img5 = (is_file(TEMPLATE_PATH.'/images/artikelgrafik5_'.$this->params->selected_lang.'.png') ? TEMPLATE_URL.'/images/artikelgrafik5_'.$this->params->selected_lang.'.png' : ADMIN_URL.'/img/nopic.png');
                  $ag_img6 = (is_file(TEMPLATE_PATH.'/images/artikelgrafik6_'.$this->params->selected_lang.'.png') ? TEMPLATE_URL.'/images/artikelgrafik6_'.$this->params->selected_lang.'.png' : ADMIN_URL.'/img/nopic.png');

                  $bg_faktor = round(85 / 240);
                  //$bg_size1 =
                  // Checkbox versandkosten anzeifen (true)
                  $show_versandkosten = ((((int)$this->params->firma['versandart_1'] == 1 || (int)$this->params->firma['versandart_1']) == 5) && $this->params->firma['vers_grafik_check'] != 'y'/* && (float)$this->main->versand_preis == 0*/? true : false);
                  // $versandkosten_incl = ($show_versandkosten && $this->main->versandfrei_check == 'y' && (float)$this->main->versand_preis == 0 ? true : false);
                  $versandkosten_incl = ($show_versandkosten && $this->main->versandfrei_check == 'y' ? true : false);
                  //$pos_links = true;
                  ?>             
                   <div id="bild_editor">
                     <div id="vorschau">
                        <div id="startbild">
                           <div class="startbild">
                              <img id="startbild_img" class="show_image pointer" src="<?php echo $image_tn; ?>"
                                   data-original="<?php echo $image_tn; ?>"
                                   data-hover="<?php echo $img_hover; ?>"
                                   data-src="<?php echo $image; ?>"
                                   <?php if (defined('CONF_MODULE_ARTIKELGRAFIK')) { ?>
                                   onmouseover="$(this).attr('data-hover') !== '' && $(this).attr('src', $(this).attr('data-hover'));"
                                   onmouseout="$(this).attr('src', $(this).attr('data-original'));"
                                   <?php } ?>
                                   alt="" />
                              <?php if (!defined('CONF_MODULE_ARTIKELGRAFIK')) { // ohne Modul?>
                                 <div class="multishop upload button_upload_big pointer" title="hochladen" onclick="Artikel.imageUpload('startbild', 0, 'startbild_img')"></div>
                              <?php } else {?>
                                 <div class="upload2 button_upload_big pointer" title="Bild hochladen" onclick="Artikel.imageUpload('startbild', 0, 'startbild_img')"></div>
                                 <div class="upload_hover button_upload_big_hover pointer" title="Mouseover-Bild hochladen" onclick="Artikel.imageUpload('startbild_hover', 0, 'startbild_img')"></div>
                              <?php } ?>
                              <div class="multishop delete button_delete" title="löschen" onclick="Artikel.imageDelete('startbild');"></div>
                           </div>

                           <?php if (defined('CONF_MODULE_ARTIKELGRAFIK')) { ?>
                           <div class="ag_size"><img src="<?php echo $ag_img6; ?>" id="img_artikelgrafik6" class="ag_pos_fix<?php echo ($artikelgrafik6 ? ' is_ag6' : ''); ?>" /></div>
                           <div class="ag_size"><img src="<?php echo $ag_img5; ?>" id="img_artikelgrafik5" class="ag_pos_fix<?php echo ($artikelgrafik5 ? ' is_ag5' : ''); ?>" /></div>
                           <div class="ag_size"><img src="<?php echo $ag_img4; ?>" id="img_artikelgrafik4" class="ag_pos_fix<?php echo ($artikelgrafik4 ? ' is_ag4' : ''); ?>" /></div>
                           <div class="ag_size"><img src="<?php echo $ag_img3; ?>" id="img_artikelgrafik3" class="ag_pos_fix<?php echo ($artikelgrafik3 ? ' is_ag3' : ''); ?>" /></div>
                           <div class="ag_size"><img src="<?php echo $ag_img2; ?>" id="img_artikelgrafik2" class="ag_pos_fix<?php echo ($artikelgrafik2 ? ' is_ag2' : ''); ?>" /></div>
                           <div class="ag_size"><img src="<?php echo $ag_img1; ?>" id="img_artikelgrafik1" class="ag_pos_fix<?php echo ($artikelgrafik1 ? ' is_ag1' : ''); ?>" /></div>
                           <?php } ?>
                           <div id="img_versandfrei"    class="<?php echo ($versandfrei || $versandkosten_incl ? 'is_versandfrei' : ''); ?>" id="isversandfrei"></div>
                           <div id="img_neu"            class="<?php echo ($this->main->neu_check == 'y' ? 'is_new' : ''); ?>"></div>

                           <?php if (defined('CONF_MODULE_ARTIKELGRAFIK')) { ?>
                           <div class="artikelgrafik_left">
                              <div class="artikelgrafik artikelgrafik_pos1">
                                 <input type="checkbox" class="newdesign" id="artikelgrafik1_check" name="artikelgrafik1_check"<?php echo ($this->main->artikelgrafik1_check !== 'n' ? ' checked="checked"' : ''); ?>  onchange="$(this).prop('checked') ? $('#img_artikelgrafik1').addClass('is_ag1') : $('#img_artikelgrafik1').removeClass('is_ag1');" />
                                 <label for="artikelgrafik1_check"></label>
                                 <div class="artikelgrafik_block">
                                    <span class="upload_ag upload_button pointer" onclick="Artikel.imageUpload('artikelgrafik', 1, 'artikelgrafik_preview1', 'png');" title="png hochladen"></span>
                                    <span class="delete_ag pointer far fa-trash-alt<?php echo ($ag_img1 == ADMIN_URL.'/img/nopic.png' ? ' ag_not_active' : ''); ?>" onclick="Artikel.imageDelete('artikelgrafik', 1, 'artikelgrafik_preview1', 'img_artikelgrafik1');" title="löschen"></span>
                                    <span class="artikelgrafik_preview"><img id="artikelgrafik_preview1" src="<?php echo $ag_img1; ?>" alt="" /></span>
                                 </div>
                              </div>
                              <div class="artikelgrafik artikelgrafik_pos2">
                                 <input type="checkbox" class="newdesign" id="artikelgrafik2_check" name="artikelgrafik2_check"<?php echo ($this->main->artikelgrafik2_check !== 'n' ? ' checked="checked"' : ''); ?>  onchange="$(this).prop('checked') ? $('#img_artikelgrafik2').addClass('is_ag2') : $('#img_artikelgrafik2').removeClass('is_ag2');" />
                                 <label for="artikelgrafik2_check"></label>
                                 <div class="artikelgrafik_block">
                                    <span class="upload_ag upload_button pointer" onclick="Artikel.imageUpload('artikelgrafik', 2, 'artikelgrafik_preview2', 'png');" title="png hochladen"></span>
                                    <span class="delete_ag pointer far fa-trash-alt<?php echo ($ag_img2 == ADMIN_URL.'/img/nopic.png' ? ' ag_not_active' : ''); ?>" onclick="Artikel.imageDelete('artikelgrafik', 2, 'artikelgrafik_preview2', 'img_artikelgrafik2');" title="löschen"></span>
                                    <span class="artikelgrafik_preview"><img id="artikelgrafik_preview2" src="<?php echo $ag_img2; ?>" alt="" /></span>
                                 </div>
                              </div>
                              <div class="artikelgrafik artikelgrafik_pos3">
                                 <input type="checkbox" class="newdesign" id="artikelgrafik3_check" name="artikelgrafik3_check"<?php echo ($this->main->artikelgrafik3_check !== 'n' ? ' checked="checked"' : ''); ?>  onchange="$(this).prop('checked') ? $('#img_artikelgrafik3').addClass('is_ag3') : $('#img_artikelgrafik3').removeClass('is_ag3');" />
                                 <label for="artikelgrafik3_check"></label>
                                 <div class="artikelgrafik_block">
                                    <span class="upload_ag upload_button pointer" onclick="Artikel.imageUpload('artikelgrafik', 3, 'artikelgrafik_preview3', 'png');" title="png hochladen"></span>
                                    <span class="delete_ag pointer far fa-trash-alt<?php echo ($ag_img3 == ADMIN_URL.'/img/nopic.png' ? ' ag_not_active' : ''); ?>" onclick="Artikel.imageDelete('artikelgrafik', 3, 'artikelgrafik_preview3', 'img_artikelgrafik3');" title="löschen"></span>
                                    <span class="artikelgrafik_preview"><img id="artikelgrafik_preview3" src="<?php echo $ag_img3; ?>" alt="" /></span>
                                 </div>
                              </div>
                              <div class="artikelgrafik artikelgrafik_pos4">
                                 <input type="checkbox" class="newdesign" id="artikelgrafik4_check" name="artikelgrafik4_check"<?php echo ($this->main->artikelgrafik4_check !== 'n' ? ' checked="checked"' : ''); ?>  onchange="$(this).prop('checked') ? $('#img_artikelgrafik4').addClass('is_ag4') : $('#img_artikelgrafik4').removeClass('is_ag4');" />
                                 <label for="artikelgrafik4_check"></label>
                                 <div class="artikelgrafik_block">
                                    <span class="upload_ag upload_button pointer" onclick="Artikel.imageUpload('artikelgrafik', 4, 'artikelgrafik_preview4', 'png', 'img_artikelgrafik4');" title="png hochladen"></span>
                                    <span class="delete_ag pointer far fa-trash-alt<?php echo ($ag_img4 == ADMIN_URL.'/img/nopic.png' ? ' ag_not_active' : ''); ?>" onclick="Artikel.imageDelete('artikelgrafik', 4, 'artikelgrafik_preview4');" title="löschen"></span>
                                    <span class="artikelgrafik_preview"><img id="artikelgrafik_preview4" src="<?php echo $ag_img4; ?>" alt="" /></span>
                                 </div>
                              </div>
                              <div class="artikelgrafik artikelgrafik_pos5">
                                 <input type="checkbox" class="newdesign" id="artikelgrafik5_check" name="artikelgrafik5_check"<?php echo ($this->main->artikelgrafik5_check !== 'n' ? ' checked="checked"' : ''); ?>  onchange="$(this).prop('checked') ? $('#img_artikelgrafik5').addClass('is_ag5') : $('#img_artikelgrafik5').removeClass('is_ag5');" />
                                 <label for="artikelgrafik5_check"></label>
                                 <div class="artikelgrafik_block">
                                    <span class="upload_ag upload_button pointer" onclick="Artikel.imageUpload('artikelgrafik', 5, 'artikelgrafik_preview5', 'png', 'img_artikelgrafik5');" title="png hochladen"></span>
                                    <span class="delete_ag pointer far fa-trash-alt<?php echo ($ag_img5 == ADMIN_URL.'/img/nopic.png' ? ' ag_not_active' : ''); ?>" onclick="Artikel.imageDelete('artikelgrafik', 5, 'artikelgrafik_preview5');" title="löschen"></span>
                                    <span class="artikelgrafik_preview"><img id="artikelgrafik_preview5" src="<?php echo $ag_img5; ?>" alt="" /></span>
                                 </div>
                              </div>
                              <div class="artikelgrafik artikelgrafik_pos6">
                                 <input type="checkbox" class="newdesign" id="artikelgrafik6_check" name="artikelgrafik6_check"<?php echo ($this->main->artikelgrafik6_check !== 'n' ? ' checked="checked"' : ''); ?>  onchange="$(this).prop('checked') ? $('#img_artikelgrafik6').addClass('is_ag6') : $('#img_artikelgrafik6').removeClass('is_ag6');" />
                                 <label for="artikelgrafik6_check"></label>
                                 <div class="artikelgrafik_block">
                                    <span class="upload_ag upload_button pointer" onclick="Artikel.imageUpload('artikelgrafik', 6, 'artikelgrafik_preview6', 'png', 'img_artikelgrafik6');" title="png hochladen"></span>
                                    <span class="delete_ag pointer far fa-trash-alt<?php echo ($ag_img6 == ADMIN_URL.'/img/nopic.png' ? ' ag_not_active' : ''); ?>" onclick="Artikel.imageDelete('artikelgrafik', 6, 'artikelgrafik_preview6');" title="löschen"></span>
                                    <span class="artikelgrafik_preview"><img id="artikelgrafik_preview6" src="<?php echo $ag_img6; ?>" alt="" /></span>
                                 </div>
                              </div>
                              <div  class="artikelgrafik_help">
                                 <span class="help ci_color" title="Grafik (png) oben links ausgerichtet - Empfehlung bis 120 x 120px"></span>
                              </div>
                           </div>
                           <?php } else { ?>
                           <div class="hidden">
                              <input type="checkbox" id="artikelgrafik1_check" name="artikelgrafik1_check" />
                              <input type="checkbox" id="artikelgrafik2_check" name="artikelgrafik2_check" />
                              <input type="checkbox" id="artikelgrafik3_check" name="artikelgrafik3_check" />
                              <input type="checkbox" id="artikelgrafik4_check" name="artikelgrafik4_check" />
                              <input type="checkbox" id="artikelgrafik5_check" name="artikelgrafik5_check" />
                              <input type="checkbox" id="artikelgrafik6_check" name="artikelgrafik6_check" />
                           </div>
                           <?php } ?>

                           <div class="artikelgrafik_right<?php echo (!defined('CONF_MODULE_ARTIKELGRAFIK') ? '_no_module' : ''); ?>">
                              <div class="ab_check easy">
                                 <input type="checkbox" class="newdesign" id="ab_check" name="ab_check"<?php echo ($this->main->ab_check !== 'n' ? ' checked="checked"' : ''); ?> />
                                 <label for="ab_check">&bdquo;ab&ldquo;-Preise&nbsp;</label>
                                 <span class="help ci_color" title ="Wort &bdquo;ab&ldquo; in Artikelauflistung anzeigen"></span>
                              </div>

                              <div class="new_check easy">
                                 <input type="checkbox" class="newdesign" id="neu_check" name="neu_check"<?php echo ($this->main->neu_check == 'y' ? ' checked="checked"' : ''); ?> onchange="$(this).prop('checked') ? $('#img_neu').addClass('is_new') : $('#img_neu').removeClass('is_new');" />
                                 <label for="neu_check">neu</label>
                              </div>

                              <div id="versandfrei" class="versandfrei"<?php echo ($show_versandkosten || $versandkosten_incl ? '' : ' style="display:none;"'); ?>>
                                 <input type="checkbox" class="newdesign" id="versandfrei_check" name="versandfrei_check"<?php echo ($this->main->versandfrei_check !== 'n' ? ' checked="checked"' : ''); ?>  onchange="$(this).prop('checked') ? $('#img_versandfrei').addClass('is_versandfrei') : $('#img_versandfrei').removeClass('is_versandfrei');" onchange="Artikel.checkVersandfrei();" />
                                 <label for="versandfrei_check">versandkostenfrei&nbsp;</label>
                                 <span class="infotext" title="nur optisch, bitte Versandkosten kontrollieren"></span>
                              </div>
                           </div>

                           <div class="article_zoom pointer easy" onclick="Artikel.zoomPopup();" title="Zoom-Einstellungen"></div>
                           <div  class="multishop more_images button_ci txt_btn pointer" onclick="$('#more_images').click();" title="zusätzliche Bilder"><span class="far fa-folder-open"></span>&nbsp;&nbsp;auswählen</div>

                           <?php if (defined('CONF_MODULE_360GRAD')) { ?>
                           <div  class="multishop bg_360grad button_ci txt_btn pointer"  title="360°-Animation" onclick="Mod360grad.load()">&nbsp;&nbsp;360°&nbsp;&nbsp;</div>
                           <?php } ?>
                           <div class="clear"></div>
                        </div>

                        <div id="fileinput">
                           <?php $more_images = $this->moreImages(); ?>
                           <?php echo $more_images['html']; ?>
                           <?php $script .= $more_images['script']; ?>
                        </div>
                         
                         
                        <div class="clear">  <h2 id="videoupload_anchor"></h2></div>
                      

                         
                    <?php if(defined('CONF_MODULE_VIDEO'))
                          {
                              
                       
                            $productid = $this->main->parent_id;
                           
                            if(!empty($_FILES["videoupload"])){

                                KANPAICLASSIC\Control::getModuleVideo()->handleUpload($productid, $_FILES["videoupload"]);

                                unset($_FILES);

                            }
                          
                           $videosFound =  KANPAICLASSIC\Control::getModuleVideo()->listVideos($productid);
                        

                    ?>
                            <style>
                                .videoicons{
                                    padding-right:5px;
                                }

                                .videoicons, .videoname{
                                    display:inline-block;
                                }

                                .videoicon{
                                    color:white;
                                    padding-right:7px;
                                }

                                .videoupload_wrapper{
                                    padding-top:10px;
                                    padding-bottom:10px;
                                }

                                #fileinput{
                                    margin-bottom:0 !important;  
                                    height:210px;
                                }

                                .selectfilebutton{

                                }
                                .videowrapper .file-loading::before{
                                   content :'';
                                   background: unset;
                                }
                                .videowrapper .btn-file, .videowrapper .file-caption{
                                   display: none;
                                }


                                 .videowrapper { position:relative; width:100%; margin-bottom:10px;   margin-top:100px;}
                                 .videowrapper .file-preview { border-color:#9e9e9e; box-sizing: border-box; }
                                 .videowrapper .file-preview .file-caption {  }
                                 .videowrapper .file-preview-frame { margin:4px; }
                                 .videowrapper .file-drop-zone { border:none !important; }
                                 .videowrapper .file-drop-zone-title { font-size:1em !important; }
                                 .videowrapper .file-preview .glyphicon { display:none; }
                                 .videowrapper .file-preview .kv-file-content { position:relative; height:128px; display: flex; justify-content: center; flex-direction: column; }
                                 .videowrapper .file-preview .kv-file-content video {max-height:128px; }
                                 .videowrapper .krajee-default:not(.file-preview-initial) { background-color:#dedede; }
                                 .videowrapper .krajee-default:not(.file-preview-initial) .file-caption-info { margin: 0 25px 0 0 !important; line-height: 20px; }
                                 .videowrapper .file-preview-image { max-width:128px; max-height:128px; }$text
                                 .videowrapper .file_preview_text { position: relative; height: 178px; }
                                 .videowrapper .file_preview_color { display: inline-block; height: 30px; position: absolute; bottom: 0; left:0; right:0; padding:0 10px; line-height:30px; }

                                 .videowrapper .file-upload-indicator {display:none; }

                                 .videowrapper .input-group .file-caption-name { display:none !important; }
                                 .videowrapper .btn-file  { display:none !important; }
                                 .videowrapper .fileinput-cancel-button  { display:none !important; }
                                 .videowrapper .file-size-info  { display:none !important; }
                                 .videowrapper .upload i { color:inherit; }
                                 .videowrapper .upload span { color:inherit; }
                                 .videowrapper .file-caption-name  { display:none !important; }
                                 .videowrapper .file-caption-info  { margin: 0 25px 0 25px; line-height: 20px; }

                                 .videowrapper .file-thumbnail-footer { position:relative; height:32px; text-align:center; }
                                 .videowrapper .file-thumbnail-footer .file-footer-caption { display:none; }
                                 .videowrapper .file-thumbnail-footer .file-thumb-progress {  }
                                 .videowrapper .progress { position:relative; top:-57px; }
                                 .videowrapper .kv-upload-progress { display:none !important; }

                                 .videowrapper .file-actions { position: absolute; width: 100%; top: 8px; height:16px; }
                                 .videowrapper .fileinput-remove-button  { display:none !important; }
                                 .videowrapper .file-drag-handle { position:absolute; left:4px; top:8px; float: left; margin-top:0; width: 16px; height: 16px; }
                                 .videowrapper .seo_button_single { position:absolute; width:20px; left:54px; top:0px; height:16px; }
                                 .videowrapper .seo_button { position:absolute; width:20px; left:40px; top:0px; height:16px; }
                                 .videowrapper .color_button { position:absolute; width:16px; left:70px; top:-1px; height:16px; border:1px solid #888; border-radius:3px; line-height:18px; font-family:arial; font-weight:normal; font-size:12px; text-align: center; }
                                 .videowrapper .krajee-default:not(.file-preview-initial) .seo_button { display:none; }
                                 .videowrapper .krajee-default:not(.file-preview-initial) .seo_button_single { display:none; }
                                 .videowrapper .krajee-default:not(.file-preview-initial) .color_button { display:none; }
                                 .videowrapper .file-actions .file-footer-buttons .remove { background-color:inherit; top:0; height:16px; }
                                 .videowrapper .selectfilebutton {margin-bottom: 20px;}
                            </style>
                         
                         <script>
                           
                         </script>

                         <div class="videowrapper" >

                           <div class="videoupload_wrapper file-loading">
                           


                                       <form id="videouploadform" action="#videoupload_anchor" method="post"   enctype="multipart/form-data">
                                       <button type="button" class="selectfilebutton multishop more_images button_ci txt_btn pointer"><i class="videoicon fa fa-video"></i>auswählen</button>                                 

                                      <input type="file" style="display:none"
                                id="videoupload" name="videoupload"
                                accept="video/mp4,video/quicktime,.mov" data-browse-on-zone-click="true" multiple>
                                

                                <button type="submit" class="multishop more_images button_ci txt_btn pointer" style="display:none;background-color:#ff9c00">jetzt hochladen</button>

                                                </form>
                              </div>
                                 <?php 
                                 $print = $printConfig = '';
                                 foreach($videosFound as $id=>$video){
                                     $videolink = KANPAICLASSIC\Control::getModuleVideo()->getVideoUrl($productid, $video);
                                     $print .= (($print == '')?'':','). '"'.$videolink.'"';
                                     $printConfig .= (($printConfig == '')?'':',').  '{type: "video", filetype: "video/mp4", key : "'.$productid.'-'.$video.'", extra: { productid:"'.$this->main->parent_id.'", videoname:"'.$video.'"}}';
                                 }
                              ?>

                         </div>
                         <script type="text/javascript">
                         //wait for jquery to load
                           function defer(method) {
                              if (window.jQuery)
                                 initVideoUpload();
                              else
                                 setTimeout(function() { defer(initVideoUpload) }, 50);
                              }
                              defer();
                           //init fileinput configuration
                           function initVideoUpload (){
                              $(document).ready(function() {
                                 $("#videoupload").fileinput({
                                    language: 'de',
                                    uploadAsync: true,
                                    uploadUrl: admin_url_idx+"/ajax/artikel/videoUpload",
                                    uploadExtraData       : { productid : <?php echo $this->main->parent_id?> },
                                    deleteUrl: admin_url_idx+"/ajax/artikel/videoDelete",
                                    allowedFileExtensions : ["mp4","mov"],
                                    overwriteInitial: false,
                                    initialPreview: [<?php echo $print;?> ],
                                    initialPreviewAsData: true, 
                                    initialPreviewFileType: 'image',
                                    initialPreviewConfig: [<?php echo $printConfig;?>],
                                    dropZoneTitle: 'mp4/mov-Dateien hierher ziehen oder auswählen',
                                    dropZoneClickTitle : '',
                                    showZoom: false,
                                    showUpload:true,
                                    uploadClass           : "multishop upload button_orange",
                                    // Symbole in Icon
                                    fileActionSettings    : {
                                       showRemove         : true,
                                       removeClass        : "multishop remove pointer far fa-trash-alt",
                                       removeIcon         : "",
                                       showDrag           : true,
                                       dragTitle          : "",
                                       dragIcon           : "<i class=\'fas fa-arrows-alt\'></i>",
                                    }
                                 });
                                 $('#videoupload').on('fileuploaded', function(event, data, previewId, index) {
                                       window.location.reload();
                                 })
                                 .on("filesorted", function(event, params) { 
                                    console.log(params.stack);
                                    let newArr = [];
                                    params.stack.map((item,index) => {
                                       const pos = item['key'].indexOf("-");
                                       const videolink = item['key'].substr(pos+1, item['key'].length);
                                       newArr[index] = videolink;
                                    })

                                    $.post(admin_url_idx+"/ajax/artikel/videoSort", { 
                                       newSort  : newArr, 
                                       parent_id : <?php echo $this->main->parent_id?> 
                                    }, function(data) { 
                                       if (data.status === "ok") {
                                          console.log('sorted');
                                       }
                                    }, "json");
                                 });
                              });
                           }
                         </script>





                  <?php } ?>
                     </div>
                 
                     <div id="editoren">  
                        <div class="article">
                           <div class="input_left">
                              <div class="input_line_top">
                                 <div class="pos1">&nbsp;</div>
                                 <div class="pos2"></div>
                                 <div class="clear"></div>
                              </div>

                              <div class="input_line">
                                 <div class="pos1">

                                    <span class="txt_tit">Artikelname:</span>
                                 </div>
                                 <div class="pos2_4">
                                    <input type="text" id="artikelname2" class="txt_tit" value="<?php echo $this->main->name; ?>" onchange="$('#artikelname').val($(this).val());" />
                                 </div>
                                 <div class="clear"></div>
                              </div>

                              <div class="input_line">
                                 <div class="pos1" title="Bei aktiv auf der Front anzeigen">
                                    <input type="checkbox" class="newdesign" id="marke_aktiv" name="marke_aktiv"<?php echo ($this->main->marke_aktiv == 'y' ? ' checked="checked"' : ''); ?> />
                                    <label for="marke_aktiv"></label> Marke
                                 </div>
                                 <div class="pos2">
                                    <input type="text" class="txt_inp" id="marke" name="marke" value="<?php echo $this->main->marke; ?>" onchange="$('#g_marke').val($(this).val());" />
                                 </div>
                                 <div class="pos3">Art.-Nr.</div>
                                 <div class="pos4">
                                    <input type="text" class="txt_inp" id="art_artnr2" name="art_artnr2" value="<?php echo $this->main->art_nr; ?>" onchange="$('#art_artnr').val($(this).val());" />
                                 </div>
                                 <div class="clear"></div>
                              </div>

                              <div class="input_line">
                                 <div class="pos1">Lagermenge:</div>
                                 <div class="pos2">
                                    <input type="text" id="menge2" value="<?php echo number_format((float)$this->main->menge, ($this->main->masse_check == 'y' ? (int)$this->main->masse_komma : 0), ',', '') ?>"
                                           onchange="$('.article_main').closest('.block_start').attr('data-changed', 1); $(this).val(point2komma(parseFloat(komma2point($(this).val())).toFixed(<?php echo ($this->main->masse_check == 'y' ? $this->main->masse_komma : 0); ?>))); $('#menge1').val($(this).val())" />
                                 </div>
                                 <div class="clear"></div>
                              </div>

                              <div class="input_line">
                                 <div class="pos1">Lieferzeit(Tage):</div>
                                 <div class="pos2">
                                    <input type="text" id="lieferfrist2" value="<?php echo $this->main->lieferfrist; ?>" maxlength="8" onchange="$('#lieferfrist').val($(this).val());" />
                                 </div>
                                 <div class="clear"></div>
                              </div>

                            <!-- energy efficiency start -->


                            
                                 <?php 
                                 
                                 if( defined('CONF_MODULE_ENERGIEEFFIZIENZLABEL') ){ ?> 

                                  <div class="input_line">
                                     <div class="pos1">Energieeffizienz:</div>
                                     <div class="pos2">
                                        <span class="selectbox30">

                                            <select id="energy_efficiency" name="energy_efficiency">

                                            <option value=""<?php echo (empty($this->main->energy_efficiency) ? ' selected="selected"' : ''); ?>>Bitte wählen</option>
                                            <option value="a+++"<?php echo ($this->main->energy_efficiency == "a+++" ? ' selected="selected"' : ''); ?>>A+++</option>
                                            <option value="a++"<?php echo ($this->main->energy_efficiency == "a++" ? ' selected="selected"' : ''); ?>>A++</option>
                                            <option value="a+"<?php echo ($this->main->energy_efficiency == "a+" ? ' selected="selected"' : ''); ?>>A+</option>
                                            <option value="a"<?php echo ($this->main->energy_efficiency == "a" ? ' selected="selected"' : ''); ?>>A</option>
                                            <option value="b"<?php echo ($this->main->energy_efficiency == "b" ? ' selected="selected"' : ''); ?>>B</option>
                                            <option value="c"<?php echo ($this->main->energy_efficiency == "c" ? ' selected="selected"' : ''); ?>>C</option>
                                            <option value="d"<?php echo ($this->main->energy_efficiency == "d" ? ' selected="selected"' : ''); ?>>D</option>
                                            <option value="e"<?php echo ($this->main->energy_efficiency == "e" ? ' selected="selected"' : ''); ?>>E</option>
                                            <option value="f"<?php echo ($this->main->energy_efficiency == "f" ? ' selected="selected"' : ''); ?>>F</option>
                                            <option value="g"<?php echo ($this->main->energy_efficiency == "g" ? ' selected="selected"' : ''); ?>>G</option>


                                            </select>
                                        </span>
                                     </div>
                               
                                      <div class="pos3">
                                      <div  class="energyefficiency_image energyefficiency_image_pos4">       
                                          
                                        <?php

                                     $id = $this->main->parent_id;

                                     $energy_url =  '/pictures/energieeffizienz/energyefficiency_image_'.$id.'.jpg';
                                     $energy_path = '/pictures/energieeffizienz/energyefficiency_image_'.$id.'.jpg';


                                     $energyefficiency_image = is_file(SHOP_PATH.$energy_path)?SHOP_URL.$energy_path:ADMIN_URL.'/img/nopic.png';
                                     
                                        ?>

                                        <div class="artikelgrafik_block" id="energieeffizienz_bild_loeschen">
                                        <span style="margin-top:4px;" class="upload_ag upload_button pointer energyefficiency_preview" onclick="Artikel.imageUpload('energyefficiency_image', <?php echo $id; ?>, 'energyefficiency_preview', 'jpg');" title="png/jpg hochladen"></span>
                                        <span id="energyefficiency_preview_delete" class="delete_ag pointer far fa-trash-alt <?php echo ($energyefficiency_image == ADMIN_URL.'/img/nopic.png' ? ' ag_not_active' : ''); ?>" onclick="Artikel.imageDelete('energyefficiency_image', <?php echo $id; ?>, 'energyefficiency_preview');" title="löschen"></span>

                                        <span onclick='jQuery(".energyefficiency_img_box").show(); ' class="energyefficiency_preview" style="max-height:25px;position: absolute;padding-left:5px;"><img class="energyefficiency_preview" style="max-height:25px" id="energyefficiency_preview" src="<?php echo $energyefficiency_image; ?>?date=<?php echo time();?>" alt="Preview Energieeffizienz" /></span>
                   
                                        <div class='energyefficiency_img_box' onClick='jQuery(this).hide();'><img class="energyefficiency_preview" src='<?php echo $energyefficiency_image; ?>?date=<?php echo time();?>' /></div>

<style>

</style>                  

                                        </div>

                                    </div>
                                </div>
                            </div>

                            

                            <?php } ?>

                           </div>

                            <!-- energy efficiency end -->




                           <div class="input_right">
                              <?php // $netto = number_format((float)$angebot, 2, ',', '.'); ?>
                              <?php $steuer         = (float)$this->params->firma['tax'.$this->main->steuersatz]; ?>
                              <?php $netto          = number_format((float)$this->main->netto, 2, ',', '.'); ?>
                              <?php $brutto         = number_format((float)$this->main->netto * (1 + $steuer / 100), 2, ',', '.'); ?>
                              <?php $angebot_netto  = number_format((float)$this->main->angebot, 2, ',', '.'); ?>
                              <?php $angebot_brutto = number_format((float)$this->main->angebot * (1 + $steuer /100), 2, ',', '.'); ?>

                              <div class="input_line_top">
                                 <div class="pos1">&nbsp;</div>
                                 <div class="pos2<?php echo (!$show_brutto ? ' hidden' : ''); ?>">netto</div>
                                 <div class="pos3<?php echo (!$show_brutto ? ' hidden' : ''); ?>">brutto</div>
                                 <div class="clear"></div>
                              </div>

                              <div class="input_line">
                                 <div class="pos1">Preis:</div>
                                 <div class="pos2">
                                    <input type="text" id="netto2" class="right<?php echo ($this->main->angebot_active == 'y' ? ' durchgestrichen' : ''); ?>" value="<?php echo $netto; ?>" onchange="Artikel.compute('bild_beschreibung', 'netto');" />
                                    <input type="hidden" id="netto2_hidden" value="<?php echo $this->main->netto; ?>" />
                                 </div>
                                 <div class="pos3<?php echo (!$show_brutto ? ' hidden' : ''); ?>"><input type="text" id="brutto2" class="right<?php echo ($this->main->angebot_active == 'y' ? ' durchgestrichen' : ''); ?>" value="<?php echo $brutto; ?>" onchange="Artikel.compute('bild_beschreibung', 'brutto');" /></div>
                                 <div class="clear"></div>
                              </div>

                              <div class="input_line">
                                 <div class="pos1">
                                    <input type="checkbox" class="newdesign" id="angebot_aktiv2" name="angebot_aktiv2"<?php echo ($this->main->angebot_active == 'y' ? ' checked="checked"' : ''); ?> onchange="Artikel.compute('bild_beschreibung', 'check');" />
                                    <label for="angebot_aktiv2"></label>Angebot:
                                 </div>
                                 <div class="pos2">
                                    <input type="text" id="angebot_netto2" class="right<?php echo ($this->main->angebot_active == 'n' ? ' durchgestrichen' : ''); ?>" value="<?php echo $angebot_netto; ?>" onchange="Artikel.compute('bild_beschreibung_netto', 'angebot');" />
                                 </div>
                                 <div class="pos3<?php echo (!$show_brutto ? ' hidden' : ''); ?>"><input type="text" id="angebot_brutto2" class="right<?php echo ($this->main->angebot_active == 'n' ? ' durchgestrichen' : ''); ?>" value="<?php echo $angebot_brutto; ?>" onchange="Artikel.compute('bild_beschreibung_brutto', 'angebot');" /></div>
                                 <div class="clear"></div>
                              </div>

                              <div class="input_line">
                                 <div class="pos1">Steuersatz:</div>
                                 <div class="pos2">
                                    <?php echo $this->main->steuer; ?>
                                 </div>
                                 <div class="clear"></div>
                              </div>

                              <?php if ((int)$this->params->firma['versandart_1'] != 1 && (int)$this->params->firma['versandart_1'] != 5) { ?>
                              <div class="input_line">
                                 <div class="pos1">Versandgewicht:</div>
                                 <div class="pos2">
                                    <input type="text" id="gewicht_oben" class="right" value="<?php echo number_format((float)$this->main->gewicht, 3, ',', '.'); ?>" onchange="this.value = point2komma(parseFloat(komma2point(this.value)).toFixed(3)); Artikel.checkGewicht(); $('#gewicht').val($(this).val());" />
                                 </div>
                                 <div class="pos3">Kg <span class="help ci_color pointer" title="nur nötig, wenn gewichtsabhängiger Versand eingestellt ist."></span></div>
                                 <div class="clear"></div>
                              </div>
                              <?php } else { ?>
                              <div class="input_line input_line_ind">
                                 <div class="pos1">indiv. Versandpreis:</div>
                                 <div class="pos2">
                                    <input type="text" class="val txt_inp right" id="versand_preis_oben" <?php echo ((int)$this->main->spedition > 0 ? 'disabled="disabled"' : ''); ?> value="<?php echo number_format((float)$this->main->versand_preis, 2, ',', '.'); ?>" onchange="Artikel.indVersand('netto_tab3');" />
                                 </div>
                                 <div class="pos3">
                                    <input type="text" class="val txt_inp right" id="versand_preis_brutto" <?php echo ((int)$this->main->spedition > 0 ? 'disabled="disabled"' : ''); ?> value="<?php echo number_format((float)$this->main->versand_preis * (1 + (float)$this->params->firma['tax1'] / 100), 2, ',', '.'); ?>" onchange="Artikel.indVersand('brutto');" />
                                    <input type="hidden" id="ind_versand_steuer" value="<?php echo (1 + (float)$this->params->firma['tax1'] / 100); ?>" />
                                 </div>
                                 <div class="clear"></div>
                              </div>
                              <?php } ?>
                           </div>
                           <div class="clear"></div>
                        </div>
                        <div class="clear"></div>

                        <?php $spalten2_check = $this->main->spalten2_check; ?>
                        <?php $editor_single = str_replace('[TRENNER]', '', $this->main->desc); ?>
                        <?php list($editor_l, $editor_r) = explode('[TRENNER]', $this->main->desc.'[TRENNER]'); ?>

                        <div id="editor_div">
                           <div id="edit_single"<?php echo $spalten2_check == 'y' ? 'style="display:none;"' : ''; ?>>
                              <textarea class="editorarea"  id="editor_s" name="editor_s" cols="128" rows="30"><?php echo $editor_single; ?></textarea>
                           </div>
                        </div>

                        <div id="spalten_wider1">
                        <?php if ($spalten2_check != 'y') { ?>
                           <div class="spalten_wider">
                              <a class="help_kanpaiclassic" href="<?php echo HELP_LINK; ?>/seiten/" target="_blank"></a>
                              <span>&nbsp;Widerruf&nbsp;</span>
                              <span class="selectbox30">
                                 <select id="widerruf" name="widerruf">
                                    <option value="1"<?php echo ($this->main->widerruf == 1 ? ' selected="selected"' : ''); ?>>Standard1</option>
                                    <option value="2"<?php echo ($this->main->widerruf == 2 ? ' selected="selected"' : ''); ?>>Standard2</option>
                                    <option value="3"<?php echo ($this->main->widerruf == 3 ? ' selected="selected"' : ''); ?>>Spedition</option>
                                    <option value="4"<?php echo ($this->main->widerruf == 4 ? ' selected="selected"' : ''); ?>>Dienstleistung</option>
                                    <option value="5"<?php echo ($this->main->widerruf == 5 ? ' selected="selected"' : ''); ?>>Downloadartikel</option>
                                 </select>
                              </span>

                              <div class="widerruf right">
                                 <div class="zweispalten">
                                    <input type="radio" class="newdesign" id="spalten2_check1" name="spalten2_check" value="off"<?php echo $this->main->spalten2_check != 'y' ? ' checked="checked"' : ''; ?> onchange="Artikel.changeEditor('single')" />
                                    <label for="spalten2_check1"></label>einspaltig&nbsp;&nbsp;&nbsp;
                                    <input type="radio" class="newdesign" id="spalten2_check2" name="spalten2_check" value="on"<?php echo $this->main->spalten2_check == 'y' ? ' checked="checked"' : ''; ?> onchange="Artikel.changeEditor('multi')" />
                                    <label for="spalten2_check2"></label>zweispaltig
                                 </div>
                              </div>
                           </div>
                        <?php } ?>
                        </div>
                        <div class="clear"></div>
                     </div>
                     <div class="clear"></div>

                     <div id="edit_multi"<?php echo $spalten2_check != 'y' ? ' style="display:none;"' : ''; ?>>
                        <div class="editor_div_l">
                           <textarea class="editorarea2 editorarea_l" id="editor_l" name="editor_l" cols="64" rows="30"><?php echo $editor_l; ?></textarea>
                        </div>

                        <div class="editor_div_r">
                           <textarea class="editorarea2 editorarea_r" id="editor_r" name="editor__r" cols="64" rows="30"><?php echo $editor_r; ?></textarea>
                        </div>
                        <div class="clear"></div>
                        <div id="spalten_wider2">
                        <?php if ($spalten2_check == 'y') { ?>
                           <div class="spalten_wider">
                              <a class="help_kanpaiclassic" href="<?php echo HELP_LINK; ?>/seiten/" target="_blank"></a>
                              <span>&nbsp;Widerruf&nbsp;</span>
                              <span class="selectbox30">
                                 <select id="widerruf" name="widerruf">
                                    <option value="1"<?php echo ($this->main->widerruf == 1 ? ' selected="selected"' : ''); ?>>Standard1</option>
                                    <option value="2"<?php echo ($this->main->widerruf == 2 ? ' selected="selected"' : ''); ?>>Standard2</option>
                                    <option value="3"<?php echo ($this->main->widerruf == 3 ? ' selected="selected"' : ''); ?>>Spedition</option>
                                    <option value="4"<?php echo ($this->main->widerruf == 4 ? ' selected="selected"' : ''); ?>>Dienstleistung</option>
                                    <option value="5"<?php echo ($this->main->widerruf == 5 ? ' selected="selected"' : ''); ?>>Downloadartikel</option>
                                 </select>
                              </span>

                              <div class="widerruf right">
                                 <div class="zweispalten">
                                    <input type="radio" class="newdesign" id="spalten2_check1" name="spalten2_check" value="off"<?php echo $this->main->spalten2_check != 'y' ? ' checked="checked"' : ''; ?> onclick="Artikel.changeEditor('single')" />
                                    <label for="spalten2_check1"></label>einspaltig&nbsp;&nbsp;&nbsp;&nbsp;
                                    <input type="radio" class="newdesign" id="spalten2_check2" name="spalten2_check" value="on"<?php echo $this->main->spalten2_check == 'y' ? ' checked="checked"' : ''; ?> onclick="Artikel.changeEditor('multi')" />
                                    <label for="spalten2_check2"></label>zweispaltig
                                 </div>
                              </div>
                           </div>
                        <?php } ?>
                        </div>
                        <div class="clear"></div>
                     </div>
                  </div>
               </section>

               <?php if (defined('CONF_MODULE_TIMER')) { ?>
               <section id="tabs3_content2">
                  <?php $t_data = $this->_timerLoad($parent_id); ?>
                  <div id="timer">
                     <div class="title_line">
                        <a href="<?php echo HELP_LINK; ?>/o53/artikeltimer/" class="help_kanpaiclassic" target="_blank"></a>
                        <h1 class="txt_tit">Artikel-Restzeit</h1>
                        <div class="button_ci txt_but" onclick="Timer.save()">Speichern</div>
                     </div>

                     <div id="timer_content">
                        <div id="timer_left">
                           <div class="timer_line abstand">
                              <span class="timer_check pos1">
                                 <input type="checkbox" class="newdesign" id="timer_check" name="timer_check"<?php echo ($t_data['timer_check'] == 'y' ? ' checked="checked"' : '');?>>
                                 <label for="timer_check"></label>
                                 <span class="">Ablaufdatum:</span>
                              </span>

                              <span class="pos2">
                                 <input type="hidden" id="timer_end" value="<?php echo $t_data['timer_end']; ?>" />
                                 <input type="text" class="txt_inp" id="t_tag" maxlength="2" name="t_tag" value="<?php echo $t_data['tag']; ?>" placeholder="TT" />
                                 <input type="text" class="txt_inp" id="t_monat" maxlength="2" name="t_monat" value="<?php echo $t_data['monat']; ?>" placeholder="MM" />
                                 <input type="text" class="txt_inp breit" id="t_jahr" maxlength="4" name="t_jahr" value="<?php echo $t_data['jahr']; ?>" placeholder="JAHR" />
                                 &nbsp;
                                 <input type="text" class="txt_inp" id="t_stunde" name="t_stunde" maxlength="2" value="<?php echo $t_data['stunde']; ?>" placeholder="hh" />
                                 <input type="text" class="txt_inp" id="t_minute" name="t_minute" maxlength="2" value="<?php echo $t_data['minute']; ?>" placeholder="mm" />&nbsp; Uhr
                              </span>
                           </div>

                           <div class="timer_line">
                              <span class="pos1">nach Ablauf:</span>
                              <span class="pos2">
                                 <input type="radio" class="newdesign" id="timer_art_disable" name="timer_art_disable" value="n"<?php echo ($t_data['timer_art_disable'] == 'n' ? ' checked="checked"' : ''); ?> />
                                 <label for="timer_art_disable"></label>Normalpreis anzeigen
                              </span>
                           </div>

                           <div class="timer_line abstand">
                              <span class="pos1 leer"></span>
                              <span class="pos2">
                                 <input type="radio" class="newdesign" id="timer_art_disable2" name="timer_art_disable" value="y"<?php echo ($t_data['timer_art_disable'] == 'y' ? ' checked="checked"' : ''); ?> />
                                 <label for="timer_art_disable2"></label>Artikel deaktivieren
                              </span>
                           </div>
                        </div>

                        <div id="timer_right">
                           <div class="timer_line">
                              <span class="pos3">
                                 <input type="checkbox" class="newdesign" id="timer_anzeige" name="timer_anzeige" <?php echo ($t_data['timer_anzeige'] == 'y' ? 'checked="checked"' : '');?>>
                                 <label for="timer_anzeige"></label><span>Verfügbarkeitsanzeige</span>
                              </span>
                           </div>

                           <div class="timer_line">
                              <span class="pos3"><span class="padding_checkbox">ursprüngliche Menge:</span></span>
                              <span class="pos">
                                 <input type="text" class="newdesign txt_inp breit" id="t_menge" name="t_menge" value="<?php echo $t_data['timer_menge']; ?>" />
                              </span>
                           </div>

                           <div class="timer_line">
                           </div>
                        </div>
                        <div class="clear"></div>
                     </div>
                  </div>
               </section>
               <?php } ?>

               <section id="tabs3_content3">
                  <div id="seo">
                     <div class="seo_title"><a href="<?php echo HELP_LINK; ?>/seo/" target="_blank"><span class="help_kanpaiclassic"></span><a><span class="txt_tit">Suchmaschinenoptimierung</span></div>

                     <div class="seo_auto">
                        <input type="checkbox" class="newdesign" id="seo_auto"<?php echo (!isset($this->seo['auto']) || $this->seo['auto'] == 'y' ? ' checked="checked"' : ''); ?> />
                        <label for="seo_auto"></label>SEO automatisieren
                        <span class="help ci_color" title="Aus Artikelname und Artikelbeschreibung werden TItleTag und Description generiert. Individuelle SEO ist natürlich besser als eine Automatik, daher künftig deaktivieren, speichern und dann nachbessern."></span>
                     </div>

                     <div class="seo_block seo_block_left">
                        <p>Title Tag</p>
                        <textarea id="seo_title"><?php echo (isset($this->seo['title']) ? $this->seo['title'] : ''); ?></textarea>
                     </div>
                     <div class="seo_block seo_block_center">
                        <p><b>Description Tag</b></p>
                        <textarea id="seo_desc"><?php echo (isset($this->seo['desc']) ? $this->seo['desc'] : ''); ?></textarea>
                     </div>
                     <div class="seo_block seo_block_right">
                        <p>Keywords <span class="help ci_color" title="Keywords werden von Google seit 2003 nicht mehr verwendet, optimieren Sie daher Title &amp; Description"></span></p>
                        <textarea id="seo_key"><?php echo (isset($this->seo['key']) ? $this->seo['key'] : ''); ?></textarea>
                     </div>
                     <div class="clear"></div>

                     <div class="seo_button"><a href="<?php  echo HELP_LINK; ?>/seo/" target="_blank" class="button button_border">SEO-Hinweise</a></div>
                  </div>
               </section>
            </div>
