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

$laender = KANPAICLASSIC\Control::getLaender();
$help = KANPAICLASSIC\Control::getHelp();
?>
<div class="site_head bg_flaechen">
   <div class="ueberschrift text_max">
      <?php echo $text->get('anmelden', 'titel'); ?>
   </div>
</div>

<div id="anmeldung" class="col_single">
   <form method="post" action="<?php echo SHOP_URL_IDX; ?>/checkanmeldung">
      <div class="col_single">
         <input type="hidden" name="lieferadresse" value= "on" />
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
                           <select class="text_formular text_normal" name="anrede" id="anrede" onchange="if ($('#lf_nachname').val() === '') { $('#lf_anrede').val($(this).val()); }"> <?php echo KANPAICLASSIC\Helper::getAnredeOption($data['anrede']); ?></select>
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
<?php /* */ ?>
               <?php if ((defined('CONF_ALTER_PFLICHT') || !defined('CONF_ALTER_PFLICHT') && $_SESSION['fsk_artikel']) && defined('CONF_MODULE_PERSOCHECK')) { ?>
                  <?php if ($params->firma['fsk_show'] != 'y' && !$_SESSION['alter_check'] && $_SESSION['fsk_artikel']) { ?>
                  <?php $err = $data_err['perso_err'] ? " form_err" : ""; ?>
               <div class="line">
                  <div class="line_left fliesstext text_normal<?php echo $err; ?>" style="cursor:help;" onclick="helpPerso();"><img src="<?php echo TEMPLATE_URL; ?>/images/system/help3.png" alt="" style="display:inline-block; position:relative; top:5px;" /> <?php echo $text->get('kunde', 'perso_nr'); ?></div>
                  <div class="line_center"></div>
                  <div class="line_right"><input type="text" class="text_formular text_normal<?php echo $err; ?>" name="perso_nr" id="perso_nr" value="<?php echo (isset($_SESSION['perso_nr']) ? $_SESSION['perso_nr'] : ''); ?>" placeholder="<?php echo $text->get('kunde', 'perso_ph'); ?>" /></div>
               </div>
                  <?php } ?>
               <?php } ?>
<?php /*
                  <?php } else { ?>
                     <?php $gebdatum = $data['gebdatum'];
                     $disabled = '';
                     if (isset($_SESSION['alter_ok_date'])) {
                        $err  = '';
                        $gebdatum = $_SESSION['alter_ok_date'];
                        $disabled = ' disabled="disabled"';
                     } else {
                        $err = $data_err['gebdatum_err'] ? " form_err" : "";
                     } ?>
               <div class="line">
                  <div class="line_left fliesstext text_normal"><?php echo $text->get('kunde', 'gebdatum'); ?>*</div>
                  <div class="line_center"></div>
                  <div class="line_right">
                     <input type="text" class="geb_tag text_formular text_gross<?php echo $err; ?>"   name="gebdatum_tag"   id="gebdatum_tag"   <?php echo $disabled; ?> value="<?php echo substr($gebdatum, 8 , 2);?>" />
                     <input type="text" class="geb_monat text_formular text_gross<?php echo $err; ?>" name="gebdatum_monat" id="gebdatum_monat" <?php echo $disabled; ?> value="<?php echo substr($gebdatum, 5, 2); ?>" />
                     <input type="text" class="geb_jahr text_formular text_gross<?php echo $err; ?>"  name="gebdatum_jahr"  id="gebdatum_jahr"  <?php echo $disabled; ?> value="<?php echo substr($gebdatum, 0, 4); ?>" />
                  </div>
               </div>
                  <?php } ?>
               <?php } else { ?>
                  <?php $err = $data_err['gebdatum_err'] ? " form_err" : ""; ?>
                  <?php $gebdatum = $data['gebdatum']; ?>
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
*/ ?>
               <div class="emptyline"></div>

               <?php $err = ($params->firma['b2b_check'] == 'y' ? ($data_err['firma_err'] ? " form_err" : "") : '') ?>
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
                           <select name="staat" id="staat" class="text_formular text_normal"  onchange="if ($('#ort').val() == $('#lf_ort').val()) { checkLfLand($(this).val()); }" onblur="if ($('#ort').val() == $('#lf_ort').val()) { checkLfLand($(this).val()); }"><?php echo $laender->getOptionEu($data['staat'])?></select>
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

               <?php if ($data_err['email_err']) { ?>
               <div class="emptyline fliesstext text_klein form_err" style="text-align:right; padding-right:18px; box-sizing:border-box;"><?php echo $data_err['email_msg']; ?></div>
               <?php } ?>

               <?php $err = $data_err['email2_err'] ? " form_err" : ""; ?>
               <div class="line">
                  <div class="line_left fliesstext text_normal<?php echo $err; ?>"><?php echo $text->get('kunde', 'password2', 'lang'); ?>*</div>
                  <div class="line_center"></div>
                  <div class="line_right"><input type="text" class="text_formular text_normal<?php echo $err; ?>" name="email2" id="email2" value="<?php echo $data['email2']; ?>" /></div>
               </div>

               <?php $err = $data_err['password1_err'] ? " form_err" : ""; ?>
               <div class="line">
                  <div class="line_left fliesstext text_normal<?php echo $err; ?>"><?php echo $text->get('kunde', 'password1'); ?>*</div>
                  <div class="line_center"></div>
                  <div class="line_right">
                     <input type="password" class="text_formular text_gross<?php echo $err; ?>" name="password1" id="password1" value="<?php echo @$data['password1']; ?>" autocomplete="off" />
                     <span class="auge text_formular" onclick="($('#password1').prop('type') === 'password' ? $('#password1').prop('type', 'text') : $('#password1').prop('type', 'password'));"></span>
                  </div>
               </div>

               <div class="emptyline"></div>

               <?php $err = $data_err['daten_err'] ? " form_err" : ""; ?>
               <div class="line">
                  <div class="abg_text fliesstext text_normal<?php echo $err ?>">
                     <?php echo $text->get('kunde', 'lesen1').' <a href="'.SHOP_URL_IDX.'/datenschutz" target="_new" class="fliesstext text_normal"><strong>'.$text->get('kunde', 'daten').'</strong></a> '.$text->get('kunde', 'lesen2'); ?>
                  </div>
                  <?php if (defined('CONF_HAEKCHEN')) { ?>
                  <div class="agb_check">
                     <input type="checkbox" name="daten" <?php echo $data['daten'] == 'y' ? 'checked="checked"' : ''; ?> />
                  </div>
                  <?php } ?>
               </div>

               <?php $err = $data_err['agb_err'] ? " form_err" : ""; ?>
               <div class="line">
                  <div class="abg_text fliesstext text_normal<?php echo $err ?>">
                     <?php echo $text->get('kunde', 'lesen1').' <a href="'.SHOP_URL_IDX.'/agb" target="_new" class="fliesstext text_normal"><strong>'.$text->get('kunde', 'agb').'</strong></a> '.$text->get('kunde', 'lesen2'); ?>
                  </div>
                  <?php if (defined('CONF_HAEKCHEN')) { ?>
                  <div class="agb_check">
                     <input type="checkbox" name="agb" <?php echo $data['agb'] == 'y' ? 'checked="checked"' : ''; ?> />
                  </div>
                  <?php } ?>
               </div>

               <?php if ($params->firma['gutschein_aktiv'] ==  'y') { ?>
               <div class="line">
                  <div class="gutschein_text fliesstext text_normal"><?php echo $text->get('kunde', 'newsletter'); ?></div>
                  <!-- <div class="gutschein_check"><input type="checkbox" name="newsletter" id="newsletter" <?php echo ($_SESSION['newsletter'] == 'y' ? 'checked="checked"' : ''); ?> /></div> -->
                  <div class="gutschein_check"><input type="checkbox" name="newsletter" id="newsletter" <?php echo ($data['newsletter'] == 'y' ? 'checked="checked"' : ''); ?> /></div>
               </div>
               <div class="gutschein_box">
                  <div class="gutschein_box_inner text_formular text_normal">
                     <?php echo $newsletter_text; ?>
                  </div>
               </div>
               <?php } ?>
               <div class="clear"></div>
            </div>
         </div>

         <div class="col_lsl_m"></div>

         <div class="col_lsl_r col_right_height">
            <div class="bg_flaechen bg_fullheight">
               <div class="title_line">
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
                              <select class="text_formular text_normal" name="lf_staat" id="lf_staat"><option value="0" style="display:none;"></option><?php echo $laender->getOption(($data['lf_staat'] != 0 ? $data['lf_staat'] : $data['staat']), false); ?></select>
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
               </div>
            </div>
         </div>
         <div class="clear"></div>
      </div>

      <div class="col_single">
            <button class="col_button bg_button text_gross button55" type="submit"><?php echo $text->get('button', 'anmelden_nk'); ?></button>
      </div>
   </form>
</div>
<?php
$script .= <<< EOT
<script>
   $(function() {
      $('#gebdatum_tag').keyup(function() {
         if ($('#gebdatum_tag').val().length == 2) {
            $('#gebdatum_monat').val('');
            $('#gebdatum_monat').focus();
         }
      });
   });

   $(function() {
      $('#gebdatum_monat').keyup(function() {
         if ($('#gebdatum_monat').val().length == 2) {
            $('#gebdatum_jahr').val('');
            $('#gebdatum_jahr').focus();
         }
      });
   });

   $(function() {
      $('#staat').change(function() { Royalart.checkStaat(0); });

      if ($('#staat option:selected').val() == '10') {
         $('#noeu').show();
      }
   });
</script>
EOT;
