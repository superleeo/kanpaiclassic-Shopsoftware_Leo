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

$geburtsdatum = false;
$laender      = KANPAICLASSIC\Control::getLaender();

if ($data['lf_anrede'] == '') {
   $data['lf_anrede'] = $data['anrede'];
}
?>
<div class="site_head bg_flaechen">
   <div class="ueberschrift text_max">
      <?php echo $text->get('lieferung', 'titel', 'lang'); ?>
   </div>
</div>

<div id="lieferung" class="col_single">
   <form method="post" action="<?php echo SHOP_URL; ?>/lieferung" onsubmit="lieferung(this); return false;">
      <div class="col_single">
         <input type="hidden" name="check_lieferung" value="on" />
         <div class="col_lsl_l col_left_height">
            <div class="bg_flaechen bg_fullheight">
               <div class="title_line">
                  <span class="text_bold fliesstext text_normal"><?php echo $text->get('lieferung', 'rechn_adr', 'lang'); ?></span>
               </div>

               <div class="line">
                  <div class="line_left fliesstext text_normal"><?php echo $text->get('kunde', 'anrede'); ?></div>
                  <div class="line_center"></div>
                  <div class="line_right">
                     <span class="select_wrapper">
                        <span class="selectbox">
                           <select class="text_formular text_normal" name="anrede" id="anrede" onchange="if ($('#lf_nachname').val() == '') { $('#lf_anrede').val($(this).val()); }"> <?php echo KANPAICLASSIC\Helper::getAnredeOption($data['anrede']); ?></select>
                        </span>
                     </span>
                  </div>
               </div>

               <?php $err = $data_err['vorname_err'] ? " form_err" : ""; ?>
               <div class="line">
                  <div class="line_left fliesstext text_normal<?php echo $err; ?>"><?php echo $text->get('kunde', 'vorname'); ?>*</div>
                  <div class="line_center"></div>
                  <div class="line_right"><input type="text" class="text_formular text_normal<?php echo $err; ?>" name="vorname" id="vorname" value="<?php echo $data['vorname']; ?>" onblur="if ($('#lf_vorname').val() == '') { $('#lf_vorname').val($(this).val()); }" /></div>
               </div>

               <?php $err = $data_err['nachname_err'] ? " form_err" : ""; ?>
               <div class="line">
               <div class="line_left fliesstext text_normal<?php echo $err; ?>"><?php echo $text->get('kunde', 'nachname'); ?>*</div>
                  <div class="line_center"></div>
                  <div class="line_right"><input type="text" class="text_formular text_normal<?php echo $err; ?>" name="nachname" id="nachname" value="<?php echo $data['nachname']; ?>"  onblur="if ($('#lf_nachname').val() == '') { $('#lf_nachname').val($(this).val()); }"/></div>
               </div>

               <?php if (defined('CONF_MODULE_PERSOCHECK')) { ?>
                  <?php if ($params->firma['fsk_show'] != 'y' && !$_SESSION['alter_check'] && $_SESSION['fsk_artikel']) { ?>
               <div class="emptyline"></div>
                  <?php $err = $data_err['perso_err'] ? " form_err" : " test"; ?>
               <div class="line">
                  <div class="line_left fliesstext text_normal<?php echo $err; ?>" style="cursor:help;" onclick="helpPerso();"><img src="<?php echo TEMPLATE_URL; ?>/images/system/help3.png" alt="" style="display:inline-block; position:relative; top:5px;" /> <?php echo $text->get('kunde', 'perso_nr'); ?></div>
                  <div class="line_center"></div>
                  <div class="line_right"><input type="text" class="text_formular text_normal form_err" name="perso_nr" id="perso_nr" value="<?php echo (isset($data['perso_nr']) ? $data['perso_nr'] : (isset($_SESSION['perso_nr']) ? $_SESSION['perso_nr'] : '')); ?>" placeholder="<?php echo $text->get('kunde', 'perso_ph'); ?>" /></div>
               </div>
                     <?php  if ($data_err['perso_err_msg'] != '') { ?>
               <div class="line">
                  <div class="line_left fliesstext text_normal">&nbsp;</div>
                  <div class="line_right fliesstext text_normal form_err" style="text-align:center;"><?php echo $text->get('perso', $data_err['perso_err_msg']); ?></div>
               </div>
                     <?php } ?>
                  <?php } else if ((int)substr($data['gebdatum'], 0, 4) > 1) { ?>
                     <?php $gebdatum = $data['gebdatum'];
                     if (isset($_SESSION['alter_ok_date'])) {
                        $gebdatum = $_SESSION['alter_ok_date'];
                     }
                     ?>
               <div class="line"<?php echo ($gebdatum == '' ? ' style="display:none;"' : ''); ?>>
                  <div class="line_left fliesstext text_normal"><?php echo $text->get('kunde', 'gebdatum'); ?></div>
                  <div class="line_center"></div>
                  <div class="line_right">
                     <input type="text" class="geb_tag text_formular text_normal"   name="gebdatum_tag"   id="gebdatum_tag"   disabled="disabled" value="<?php echo substr($gebdatum, 8 , 2);?>" />
                     <input type="text" class="geb_monat text_formular text_normal" name="gebdatum_monat" id="gebdatum_monat" disabled="disabled" value="<?php echo substr($gebdatum, 5, 2); ?>" />
                     <input type="text" class="geb_jahr text_formular text_normal"  name="gebdatum_jahr"  id="gebdatum_jahr"  disabled="disabled" value="<?php echo substr($gebdatum, 0, 4); ?>" />
                  </div>
               </div>
                  <?php } ?>
               <?php } ?>

               <?php if (!defined('CONF_MODULE_PERSOCHECK') || (defined('CONF_MODULE_PERSOCHECK') && $params->firma['fsk_show'] == 'y')) { ?>
               <?php $err = $data_err['gebdatum_err'] ? " form_err" : ""; ?>
               <?php $gebdatum = $data['gebdatum']; ?>
               <?php $geburtsdatum = (defined('CONF_ALTER_PFLICHT') ? true : false); ?>
               <div class="line"<?php echo (!defined('CONF_ALTER_PFLICHT') ? ' style="display:none;"' : ''); ?>>
                  <div class="line_left fliesstext text_normal<?php echo $err; ?>"><?php echo $text->get('kunde', 'gebdatum'); ?>*</div>
                  <div class="line_center"></div>
                  <div class="line_right">
                     <input type="text" class="geb_tag text_formular text_gross<?php echo $err; ?>"   name="gebdatum_tag"   id="gebdatum_tag"   placeholder="TT"   value="<?php echo substr($gebdatum, 8 , 2);?>" />
                     <input type="text" class="geb_monat text_formular text_gross<?php echo $err; ?>" name="gebdatum_monat" id="gebdatum_monat" placeholder="MM"   value="<?php echo substr($gebdatum, 5, 2); ?>" />
                     <input type="text" class="geb_jahr text_formular text_gross<?php echo $err; ?>"  name="gebdatum_jahr"  id="gebdatum_jahr"  placeholder="JJJJ" value="<?php echo substr($gebdatum, 0, 4); ?>" />
                  </div>
               </div>
               <?php } ?>

               <div class="emptyline"></div>

               <?php $err = ($params->firma['b2b_check'] == 'y' ? ($data_err['firma_err'] ? " form_err" : "") : ''); ?>
               <div class="line">
                  <div class="line_left fliesstext text_normal<?php echo $err; ?>"><?php echo $text->get('kunde', 'firma', 'lang'); ?><?php echo ($params->firma['b2b_check'] == 'y' ? '*' : ''); ?></div>
                  <div class="line_center"></div>
                  <div class="line_right"><input type="text" class="text_formular text_normal<?php echo $err; ?>" name="firma" id="firma" value="<?php echo $data['firma']; ?>"  onblur="if ($('#lf_firma').val() == '') { $('#lf_firma').val($(this).val()); }" /></div>
               </div>

               <div class="line">
                  <div class="line_left fliesstext text_normal"><?php echo $text->get('kunde', 'ustid', 'lang'); ?></div>
                  <div class="line_center"></div>
                  <div class="line_right"><input type="text" class="text_formular text_normal" name="ustid" id="ustid" value="<?php echo $data['ustid']; ?>" /></div>
               </div>

               <?php $err = $data_err['adresse_err'] ? " form_err" : ""; ?>
               <div class="line">
                  <div class="line_left fliesstext text_normal<?php echo $err; ?>"><?php echo $text->get('kunde', 'adresse'); ?>*</div>
                  <div class="line_center"></div>
                  <div class="line_right">
                     <input type="text" class="text_formular text_normal adresse<?php echo $err; ?>" name="adresse" id="adresse" value="<?php echo $data['adresse']; ?>"  onblur="if ($('#lf_adresse').val() == '') { $('#lf_adresse').val($(this).val()); }" />
                     <input type="text" class="text_formular text_normal hausnr<?php echo $err; ?>"  name="hausnr" id="hausnr" value="<?php echo $data['hausnr']; ?>" placeholder="<?php echo $text->get('kunde', 'hausnr_inp'); ?>"  onblur="if ($('#lf_hausnr').val() == '') { $('#lf_hausnr').val($(this).val()); }" />
                  </div>
               </div>

               <?php $err = $data_err['plz_err'] ? " form_err" : ""; ?>
               <div class="line">
                  <div class="line_left fliesstext text_normal<?php echo $err; ?>"><?php echo $text->get('kunde', 'plz', 'lang'); ?>*</div>
                  <div class="line_center"></div>
                  <div class="line_right"><input type="text" class="text_formular text_normal<?php echo $err; ?>" name="plz" id="plz" value="<?php echo $data['plz']; ?>"  onblur="if ($('#lf_plz').val() == '') { $('#lf_plz').val($(this).val()); }" /></div>
               </div>

                <?php $err = $data_err['ort_err'] ? " form_err" : ""; ?>
               <div class="line">
                  <div class="line_left fliesstext text_normal<?php echo $err; ?>"><?php echo $text->get('kunde', 'ort', 'lang'); ?>*</div>
                  <div class="line_center"></div>
                  <div class="line_right"><input type="text" class="text_formular text_normal<?php echo $err; ?>" name="ort" id="ort" value="<?php echo $data['ort']; ?>"  onblur="if ($('#lf_ort').val() == '') { $('#lf_ort').val($(this).val()); }" /></div>
               </div>

               <div class="line">
                  <div class="line_left fliesstext text_normal"><?php echo $text->get('kunde', 'buland'); ?></div>
                  <div class="line_center"></div>
                  <div class="line_right"><input type="text" class="text_formular text_normal" name="buland" id="buland" value="<?php echo $data['buland']; ?>"  onblur="if ($('#lf_buland').val() == '') { $('#lf_buland').val($(this).val()); }" /></div>
               </div>

               <?php $err = $data_err['staat_err'] ? " form_err" : ""; ?>
               <div class="line">
                  <div class="line_left fliesstext text_normal<?php echo $err; ?>"><?php echo $text->get('kunde', 'staat', 'lang'); ?>*</div>
                  <div class="line_center"></div>
                  <div class="line_right">
                     <span class="select_wrapper" class="<?php echo $err; ?>">
                        <span class="selectbox">
<!--                           <select name="staat" id="staat" class="text_formular text_normal" onchange="if ($('#ort').val() == $('#lf_ort').val()) { $('#lf_staat').val($(this).val()); $('#lf_staat').trigger('change'); }"><?php echo $laender->getOptionEu($data['staat'])?></select> -->
                           <select name="staat" id="staat" class="text_formular text_normal" onchange="if ($('#ort').val() == $('#lf_ort').val()) { checkLfLand($(this).val()); }" onblur="if ($('#ort').val() == $('#lf_ort').val()) { checkLfLand($(this).val()); }"><?php echo $laender->getOptionEu($data['staat'])?></select>
                        </span>
                     </span>
                  </div>
               </div>

               <?php $err = $data_err['staat2_err'] ? " form_err" : ""; ?>
               <div class="line" id="noeu" style="display:none;">
                  <div class="line_left fliesstext text_normal<?php echo $err; ?>"><?php echo $text->get('kunde', 'staat'); ?>*</div>
                  <div class="line_center"></div>
                  <div class="line_right"><input type="text" class="text_formular text_normal<?php echo $err; ?>" name="staat2" id="staat2" value="<?php echo $data['staat2']; ?>"  onblur="if ($('#lf_staat2').val() == '') { $('#lf_staat2').val($(this).val()); }" /></div>
               </div>

               <div class="emptyline"></div>

               <?php $err = ($params->firma['telefon_aktiv'] ==  'y' ? $data_err['telefon_err'] ? " form_err" : "" : ''); ?>
               <div class="line">
                  <div class="line_left fliesstext text_normal<?php echo $err; ?>"><?php echo $text->get('kunde', 'telefon', 'lang'); ?><?php echo ($params->firma['telefon_aktiv'] ==  'y' ? '*' : ''); ?></div>
                  <div class="line_center"></div>
                  <div class="line_right"><input type="text" class="text_formular text_normal<?php echo $err; ?>" name="telefon" id="telefon" value="<?php echo $data['telefon']; ?>" /></div>
               </div>

               <?php $err = $data_err['email_err'] ? " form_err" : ""; ?>
               <div class="line">
                  <div class="line_left fliesstext text_normal<?php echo $err; ?>"><?php echo $text->get('kunde', 'email', 'lang'); ?>*</div>
                  <div class="line_center"></div>
                  <div class="line_right"><input type="text" class="text_formular text_normal<?php echo $err; ?>" name="email" id="email" value="<?php echo $data['email']; ?>" /></div>
               </div>

               <div class="emptyline"></div>
            </div>
         </div>

         <div class="col_lsl_m"></div>

         <div class="col_lsl_r col_right_height">
            <div class="bg_flaechen bg_fullheight">
               <div class="title_line">
                  <input type="hidden" name="lieferadresse" value= "on" />
                  <input type="hidden" name="newsletter" value= "<?php echo (isset($_SESSION['newsletter']) && $_SESSION['newsletter'] == 'y' ? 'on' : 'off'); ?>" />
                  <span class="text_bold fliesstext text_normal"><?php echo $text->get('lieferung', 'liefer_adr', 'lang'); ?></span>
               </div>

               <div id="lieferadresse">
                  <div class="line">
                     <div class="line_left fliesstext text_normal"><?php echo $text->get('kunde', 'anrede'); ?></div>
                     <div class="line_center"></div>
                     <div class="line_right">
                        <span class="select_wrapper">
                           <span class="selectbox">
                              <select class="text_formular text_normal" name="lf_anrede" id="lf_anrede"><?php echo KANPAICLASSIC\Helper::getAnredeOption($data['lf_anrede']); ?></select>
                           </span>
                        </span>
                     </div>
                  </div>

                  <?php $err = $data_err['lf_vorname_err'] ? " form_err" : ""; ?>
                  <div class="line">
                     <div class="line_left fliesstext text_normal<?php echo $err; ?>"><?php echo $text->get('kunde', 'vorname', 'lang'); ?>*</div>
                     <div class="line_center"></div>
                     <div class="line_right"><input type="text" class="text_formular text_normal<?php echo $err; ?>" name="lf_vorname" id="lf_vorname" value="<?php echo $data['lf_vorname']; ?>" /></div>
                  </div>

                  <?php $err = $data_err['lf_nachname_err'] ? " form_err" : ""; ?>
                  <div class="line">
                     <div class="line_left fliesstext text_normal<?php echo $err; ?>"><?php echo $text->get('kunde', 'nachname', 'lang'); ?>*</div>
                     <div class="line_center"></div>
                     <div class="line_right"><input type="text" class="text_formular text_normal<?php echo $err; ?>" name="lf_nachname" id="lf_nachname" value="<?php echo $data['lf_nachname']; ?>" /></div>
                  </div>

                  <?php if ($geburtsdatum) { ?>
                  <div class="line"></div>
                  <?php } ?>

                  <div class="emptyline"></div>

                  <div class="line">
                     <div class="line_left fliesstext text_normal"><?php echo $text->get('kunde', 'firma', 'lang'); ?></div>
                     <div class="line_center"></div>
                     <div class="line_right"><input type="text" class="text_formular text_normal" name="lf_firma" id="lf_firma" value="<?php echo $data['lf_firma']; ?>" /></div>
                  </div>

                  <div class="line">
                     <div class="line_left fliesstext text_normal"><?php echo $text->get('kunde', 'postnr'); ?></div>
                     <div class="line_center"></div>
                     <div class="line_right"><input type="text" class="text_formular text_normal" name="lf_postnr" id="lf_postnr" value="<?php echo $data['lf_postnr']; ?>" /></div>
                  </div>

                  <?php $err = $data_err['lf_adresse_err'] ? " form_err" : ""; ?>
                  <div class="line">
                     <div class="line_left fliesstext text_normal<?php echo $err; ?>"><?php echo $text->get('kunde', 'adresse2', 'lang'); ?>*</div>
                     <div class="line_center"></div>
                     <div class="line_right">
                        <input type="text" class="text_formular text_normal adresse<?php echo $err; ?>" name="lf_adresse" id="lf_adresse" value="<?php echo $data['lf_adresse']; ?>" placeholder="<?php echo $text->get('kunde', 'adresse_inp'); ?>" />
                        <input type="text" class="text_formular text_normal hausnr<?php echo $err; ?>"  name="lf_hausnr" id="lf_hausnr" value="<?php echo $data['lf_hausnr']; ?>" placeholder="<?php echo $text->get('kunde', 'hausnr_inp'); ?>" />
                     </div>
                  </div>

                  <?php $err = $data_err['lf_plz_err'] ? " form_err" : ""; ?>
                  <div class="line">
                     <div class="line_left fliesstext text_normal<?php echo $err; ?>"><?php echo $text->get('kunde', 'plz', 'lang'); ?>*</div>
                     <div class="line_center"></div>
                     <div class="line_right"><input type="text" class="text_formular text_normal<?php echo $err; ?>" name="lf_plz" id="lf_plz" value="<?php echo $data['lf_plz']; ?>" /></div>
                  </div>

                  <?php $err = $data_err['lf_ort_err'] ? " form_err" : ""; ?>
                  <div class="line">
                     <div class="line_left fliesstext text_normal<?php echo $err; ?>"><?php echo $text->get('kunde', 'ort', 'lang'); ?>*</div>
                     <div class="line_center"></div>
                     <div class="line_right"><input type="text" class="text_formular text_normal<?php echo $err; ?>" name="lf_ort" id="lf_ort" value="<?php echo $data['lf_ort']; ?>" /></div>
                  </div>

                  <div class="line">
                     <div class="line_left fliesstext text_normal"><?php echo $text->get('kunde', 'buland'); ?></div>
                     <div class="line_center"></div>
                     <div class="line_right"><input type="text" class="text_formular text_normal" name="lf_buland" id="lf_buland" value="<?php echo $data['lf_buland']; ?>" /></div>
                  </div>

                  <?php $err = ($data_err['lf_staat_err'] ? " form_err" : ""); ?>
                  <div class="line">
                     <div class="line_left fliesstext text_normal<?php echo $err; ?>"><a href="<?php echo SHOP_URL_IDX; ?>/versand" target="_blank"><?php echo $text->get('warenkorb', 'vland', 'lang'); ?>*</a></div>
                     <div class="line_center"></div>
                     <div class="line_right">
                        <span class="select_wrapper<?php echo $err; ?>">
                           <span class="selectbox">
                              <select class="text_formular text_normal" name="lf_staat" id="lf_staat"><option value="0" style="display:none;"></option><?php echo $laender->getOption($data['lf_staat'] != 0 ? $data['lf_staat'] : $data['staat']); ?></select>
                           </span>
                        </span>
                     </div>
                  </div>

                  <?php $err = $data_err['lf_staat2_err'] ? " form_err" : ""; ?>
                  <div id="lf_noeu" class="line" style="display:none;">
                     <div class="line_left fliesstext text_normal<?php echo $err; ?>"><?php echo $text->get('kunde', 'staat'); ?>*</div>
                     <div class="line_center"></div>
                     <div class="line_right"><input type="text" class="text_formular text_normal<?php echo $err; ?>" name="lf_staat2" id="lf_staat2" value="<?php echo $data['lf_staat2']; ?>" /></div>
                  </div>

                  <div class="emptyline"></div>
               </div>
            </div>
         </div>
         <div class="clear"></div>
      </div>

      <div class="col_single">
         <button class="col_button bg_button text_gross button55"><?php echo $text->get('button', 'anmelden_nk'); ?></button>
      </div>
   </form>
</div>
<?php
$script = <<< EOT
<script>
$(function() {
   $('#staat').change(function() { Royalart.checkStaat(0); });
   $('#lf_staat').change(function() { Royalart.checkStaat(1); });

   if ($('#staat option:selected').val() == '10') {
      $('#noeu').show();
   }

   if ($('#lf_staat option:selected').val() == '10') {
      $('#lf_noeu').show();
   }

   sameHeight();
});
</script>
EOT;
?>
