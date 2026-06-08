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

$more = false;
?>
<div class="site_head bg_flaechen">
   <div class="ueberschrift text_max">
      <div class="konto_head1"><?php echo $text->get('menu', 'konto'); ?></div>
      <div class="konto_head2">
         <span id="konto_btn1" class="bg_flaechen pointer bg_button_only_hover fliesstext text_gross button55" onclick="deleteKunde();"><?php echo $text->get('button', 'loeschen'); ?></span>
         <a id="konto_btn2" class="col_button bg_button text_gross button55" href="<?php echo SHOP_URL_IDX; ?>/logout"><?php echo $text->get('menu', 'logout'); ?></a>
      </div>
      <div class="clear"></div>
   </div>
</div>

<div id="konto">
   <div class="col_single bg_flaechen rechnung">
      <div style="width:100%;">
         <?php if (isset($_SESSION['user']['gutschrift']) && (float)$_SESSION['user']['gutschrift'] > 0) { ?>
         <div class="line40 gutschrift">
            <div class="line_left text_bold text_normal ellipsis"><?php echo $text->get('konto', 'gutschrift'); ?></div>
            <div class="line_center text_bold text_normal ellipsis"><?php echo number_format((float)$_SESSION['user']['gutschrift'], 2, ',', '.').' '.$params->waehrung; ?>&nbsp;&nbsp;<span class="text_normal"><?php echo $text->get('konto', 'gut_best'); ?></span></div>
            <div class="clear"></div>
         </div>
         <?php } ?>
         <div class="line40">
            <div class="line_left text_bold text_normal ellipsis"><?php echo $text->get('adresse', 'vom'); ?></div>
            <div class="line_center text_bold text_normal ellipsis"><?php echo $text->get('konto', 'bestellung'); ?></div>
            <div class="line_status text_bold text_normal"><?php echo $text->get('best', 'tit_status'); ?></div>
            <div class="line_right text_bold text_normal ellipsis"><?php echo $text->get('konto', 'rechnung'); ?></div>
            <div class="clear"></div>
         </div>

      <?php if (!defined('CONF_MODULE_BESTELLZUSAMMENFASSUNG')) { ?>
         <?php for ($i = 0; $i < count($konto->datum); $i++) { ?>
            <?php if ($i == 5) { ?>
               <?php $more = true; ?>
         <br />
         <div id="more" class="col_button bg_button text_gross button55" onclick="$('#more_body').fadeIn(); $('#more').hide();"><?php echo $text->get('shop', 'mehr'); ?></div>
         <div id="more_body" style="display:none;">
            <?php } ?>

         <div class="line41">
            <div class="line_left fliesstext text_normal"><?php echo KANPAICLASSIC\Helper::sqlDatum($konto->datum[$i]); ?></div>
            <div class="line_center fliesstext text_normal ellipsis">

            <?php if ($konto->bestellung[$i][0] != '') { ?>
               <a href="<?php echo SHOP_URL_IDX; ?>/downloadb/<?php echo $konto->bestellung[$i][1]; ?>">
                  <span><?php echo $konto->bestellung[$i][0]; ?></span>
               </a>
            <?php } ?>
            </div>
            <div class="line_status fliesstext text_normal ellipsis"><?php echo $text->get('status', (string)$konto->bestellung[$i][5]); ?></div>
            <div class="line_right fliesstext text_normal ellipsis">
            <?php if ($konto->rechnung[$i][0] != '') { ?>
               <a href="<?php echo SHOP_URL_IDX; ?>/downloadr/<?php echo $konto->rechnung[$i][1]; ?>">
                  <span><?php echo $konto->rechnung[$i][0]; ?></span>
               </a>
            <?php } ?>
            </div>
         </div>

         <?php } // for ?>
         <?php if ($more) { ?>
         </div>
         <?php } ?>

      <?php } else { ?>
         <?php $z = 0; ?>

         <?php for ($i = 0; $i < count($konto->datum); $i++) { ?>
            <?php if ($z == 5) { ?>
               <?php $more = true; ?>
         <br />
         <div id="more" class="col_button bg_button text_gross button55" onclick="$('#more_body').fadeIn(); $('#more').hide();"><?php echo $text->get('shop', 'mehr'); ?></div><div id="more_body" style="display:none;">
            <?php } ?>
         <div class="line41"<?php echo ($konto->bestellung[$i][4] > 0 ? ' style="height:auto; min-height:41px"' : ''); ?>>
            <div class="line_left fliesstext text_normal"><?php echo KANPAICLASSIC\Helper::sqlDatum($konto->datum[$i][0]); ?></div>
            <div class="line_center line_center_pdf fliesstext text_normal ellipsis">
               <?php echo ($konto->bestellung[$i][3] == 'y' ? '<div class="main_sub plus"></div>' : '<div class="main"></div>'); ?>

            <?php if ($konto->bestellung[$i][0] != '') { ?>
               <a href="<?php echo SHOP_URL_IDX; echo ($konto->bestellung[$i][3] == 'n' ? '/downloadb/' : '/downloadcb/');  echo $konto->bestellung[$i][1]; ?>">
                  <span><?php echo $konto->bestellung[$i][0]; ?></span>
               </a>
            <?php } ?>
            </div>

            <div class="line_status fliesstext text_normal ellipsis"><?php echo $text->get('status', (string)$konto->bestellung[$i][5]); ?></div>
            <div class="line_right fliesstext text_normal ellipsis">
            <?php if ($konto->rechnung[$i][0] != '') { ?>
               <a href="<?php echo SHOP_URL_IDX; echo ($konto->bestellung[$i][3] == 'n' ? '/downloadr/' : '/downloadcr/'); echo $konto->rechnung[$i][1]; ?>">
                  <span><?php echo $konto->rechnung[$i][0]; ?></span>
               </a>
            <?php } ?>
            </div>

            <?php if ($konto->bestellung[$i][4] > 0 ) { ?>
            <div class="sub" style="display:none;">
               <?php for ($s = 0; $s < $konto->bestellung[$i][4]; $s++) { ?>
               <div class="line41">
                  <div class="line_left fliesstext text_normal"><?php echo KANPAICLASSIC\Helper::sqlDatum($konto->datum[$i][6][$s]); ?></div>
                  <div class="line_center fliesstext text_normal ellipsis" style="left:40px;">
                  <?php if ($konto->bestellung[$i][6][$s][0] != '') { ?>
                     <a href="<?php echo SHOP_URL_IDX.($konto->bestellung[$i][6][$s][3] == 'n' ? '/downloadb/' : '/downloadcb/').$konto->bestellung[$i][6][$s][1]; ?>">
                        <span><?php echo $konto->bestellung[$i][6][$s][0]; ?></span>
                     </a>
                  <?php } ?>
                  </div>
                  <div class="line_status fliesstext text_normal ellipsis"><?php echo $text->get('status', (string)$konto->bestellung[$i][6]); ?></div>
                  <div class="line_right fliesstext text_normal ellipsis">
                  <?php if ($konto->rechnung[$i][6][$s][0] != '') { ?>
<!-- -->
                     <a href="<?php echo SHOP_URL_IDX; echo ($konto->bestellung[$i][65][$s][3] == 'n' ? '/downloadr/' : '/downloadcr/'); echo $konto->rechnung[$i][6][$s][1]; ?>">
                        <span><?php echo $konto->rechnung[$i][6][$s][0]; ?></span>
                     </a>
                  <?php } ?>
                  </div>
                  <div class="clear"></div>
               </div>
               <?php } ?>
            </div>
            <?php } ?>
         </div>
         <?php $z++; ?>
         <?php } // for ?>

         <?php if ($more) { ?>
         </div>
         <?php } ?>
      <?php } ?>
         <div class="clear"></div>
      </div>
   </div>
   <div class="clear"></div>

   <div class="col_single">
      <form method="post" action="<?php echo SHOP_URL_IDX; ?>/konto">
         <div class="col_single">
            <div class="col_lsl_l bg_flaechen col_left_height">
               <div class="title_line">
                  <input type="hidden" name="mode" id="mode" value="changed" />
                  <span class="text_bold fliesstext text_normal"><?php echo $text->get('lieferung', 'rechn_adr', 'lang'); ?></span>
               </div>

               <div class="line">
                  <div class="line_left fliesstext text_normal"><?php echo $text->get('kunde', 'anrede'); ?></div>
                  <div class="line_center"></div>
                  <div class="line_right">
                     <span class="select_wrapper">
                        <span class="selectbox">
                           <select class="text_formular text_normal" name="anrede" id="anrede">
                              <?php echo KANPAICLASSIC\Helper::getAnredeOption($data['anrede']); ?>
                           </select>
                        </span>
                     </span>
                  </div>
               </div>

               <?php $err = $data_err['vorname_err'] ? " form_err" : ""; ?>
               <div class="line">
                  <div class="line_left fliesstext text_normal"<?php echo $err; ?>><?php echo $text->get('kunde', 'vorname', 'lang'); ?>*</div>
                  <div class="line_center"></div>
                  <div class="line_right"><input type="text" class="text_formular text_normal<?php echo $err; ?>" name="vorname" id="vorname" value="<?php echo $data['vorname']; ?>" /></div>
               </div>

               <?php $err = $data_err['nachname_err'] ? " form_err" : ""; ?>
               <div class="line">
               <div class="line_left fliesstext text_normal"<?php echo $err; ?>><?php echo $text->get('kunde', 'nachname', 'lang'); ?>*</div>
                  <div class="line_center"></div>
                  <div class="line_right"><input type="text" class="text_formular text_normal<?php echo $err; ?>" name="nachname" id="nachname" value="<?php echo $data['nachname']; ?>" /></div>
               </div>
               <div class="emptyline"></div>

               <?php $err = ($params->firma['b2b_check'] == 'y' ? ($data_err['firma_err'] ? ' form_err' : '') : ''); ?>
               <div class="line">
                  <div class="line_left fliesstext text_normal<?php echo $err; ?>"><?php echo $text->get('kunde', 'firma', 'lang'); ?><?php echo ($params->firma['b2b_check'] == 'y' ? '*' : ''); ?></div>
                  <div class="line_center"></div>
                  <div class="line_right"><input type="text" class="text_formular text_normal<?php echo $err; ?>" name="firma" id="firma" value="<?php echo $data['firma']; ?>" /></div>
               </div>

               <div class="line">
                  <div class="line_left fliesstext text_normal"><?php echo $text->get('kunde', 'ustid', 'lang'); ?></div>
                  <div class="line_center"></div>
                  <div class="line_right"><input type="text" class="text_formular text_normal" name="ustid" id="ustid" value="<?php echo $data['ustid']; ?>" /></div>
               </div>

               <?php $err = $data_err['adresse_err'] ? " form_err" : ""; ?>
               <div class="line">
                  <div class="line_left fliesstext text_normal<?php echo $err; ?>"><?php echo $text->get('kunde', 'adresse', 'lang'); ?>*</div>
                  <div class="line_center"></div>
                  <div class="line_right">
                     <input type="text" class="text_formular text_normal adresse<?php echo $err; ?>" name="adresse" id="adresse" value="<?php echo $data['adresse']; ?>" />
                     <input type="text" class="text_formular text_normal hausnr<?php echo $err; ?>"  name="hausnr" id="hausnr" value="<?php echo $data['hausnr']; ?>" placeholder="<?php echo $text->get('kunde', 'hausnr_inp'); ?>" />
                  </div>
               </div>

               <?php $err = $data_err['plz_err'] ? " form_err" : ""; ?>
               <div class="line">
                  <div class="line_left fliesstext text_normal<?php echo $err; ?>"><?php echo $text->get('kunde', 'plz', 'lang'); ?>*</div>
                  <div class="line_center"></div>
                  <div class="line_right"><input type="text" class="text_formular text_normal<?php echo $err; ?>" name="plz" id="plz" value="<?php echo $data['plz']; ?>" /></div>
               </div>

                <?php $err = $data_err['ort_err'] ? " form_err" : ""; ?>
               <div class="line">
                  <div class="line_left fliesstext text_normal<?php echo $err; ?>"><?php echo $text->get('kunde', 'ort', 'lang'); ?>*</div>
                  <div class="line_center"></div>
                  <div class="line_right"><input type="text" class="text_formular text_normal<?php echo $err; ?>" name="ort" id="ort" value="<?php echo $data['ort']; ?>" /></div>
               </div>

               <div class="line">
                  <div class="line_left fliesstext text_normal"><?php echo $text->get('kunde', 'buland'); ?></div>
                  <div class="line_center"></div>
                  <div class="line_right"><input type="text" class="text_formular text_normal" name="buland" id="buland" value="<?php echo $data['buland']; ?>" /></div>
               </div>

               <?php $err = $data_err['staat_err'] ? " form_err" : ""; ?>
               <div class="line">
                  <div class="line_left fliesstext text_normal<?php echo $err; ?>"><?php echo $text->get('kunde', 'staat', 'lang'); ?>*</div>
                  <div class="line_center"></div>
                  <div class="line_right">
                     <span class="select_wrapper" class="<?php echo $err; ?>">
                        <span class="selectbox">
                           <select name="staat" id="staat" class="text_formular text_normal" onchange="sameHeight();"><?php echo $laender->getOptionEu($data['staat'])?></select>
                        </span>
                     </span>
                  </div>
               </div>

               <?php $err = $data_err['staat2_err'] ? " form_err" : ""; ?>
               <div class="line" id="noeu" style="display:none;">
                  <div class="line_left fliesstext text_normal<?php echo $err; ?>"><?php echo $text->get('kunde', 'staat'); ?>*</div>
                  <div class="line_center"></div>
                  <div class="line_right"><input type="text" class="text_formular text_normal<?php echo $err; ?>" name="staat2" id="staat2" value="<?php echo $data['staat2']; ?>" /></div>
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
                  <div class="line_left fliesstext text_normal<?php echo $err; ?>"><?php echo $text->get('kunde', 'email', 'lang'); ?></div>
                  <div class="line_center"></div>
                  <div class="line_right"><input type="text" onclick="$('#mail2').css('display', 'block'); sameHeight();"  class="text_formular text_normal<?php echo $err; ?>" name="email" id="email" value="<?php echo $data['email']; ?>" /></div>
               </div>

               <?php if ($data_err['email_err']) { ?>
               <div class="emptyline fliesstext text_klein form_err" style="text-align:right; padding-right:18px; box-sizing:border-box;"><?php echo $data_err['email_msg']; ?></div>
               <?php } ?>

               <?php $err = $data_err['email2_err'] ? " form_err" : ""; ?>
               <div id="mail2" class="line"<?php echo ($data_err['email2_err'] ? '' : ' style="display:none;"'); ?>>
                  <div class="line_left fliesstext text_normal<?php echo $err; ?>"><?php echo $text->get('kunde', 'password2'); ?></div>
                  <div class="line_center"></div>
                  <div class="line_right">
                     <input type="text" class="text_formular text_normal<?php echo $err; ?>" name="email2" id="email2" value="" />
                  </div>
               </div>

               <?php $err = $data_err['password1_err'] ? " form_err" : ""; ?>
               <div class="line">
                  <div class="line_left fliesstext text_normal<?php echo $err; ?>"><?php echo $text->get('kunde', 'password1'); ?></div>
                  <div class="line_center"></div>
                  <div class="line_right">
                     <input type="password" class="text_formular text_normal" name="password1" id="password1" value="" autocomplete="off" />
                     <span class="auge fa text_formular" onclick="($('#password1').prop('type') === 'password' ? $('#password1').prop('type', 'text') : $('#password1').prop('type', 'password'));"></span>
                  </div>
               </div>

               <?php if ($params->firma['gutschein_aktiv'] == 'y') { ?>
               <div class="line">
                  <div class="gutschein_text fliesstext text_normal"><?php echo $text->get('kunde', 'newsletter'); ?></div>
                  <div class="gutschein_check"><input type="checkbox" name="newsletter" id="newsletter" <?php echo ($data['newsletter'] == 'y' ? 'checked="checked"' : ''); ?> /></div>
               </div>
               <div class="gutschein_box">
                  <div class="gutschein_box_inner text_formular text_klein"><?php echo $newsletter_text; ?></div>
               </div>
               <?php } ?>
            </div>

            <div class="col_lsl_m"></div>

            <div class="col_lsl_r bg_flaechen col_right_height">
               <div class="title_line">
                  <input type="hidden" name="lieferadresse" value="on" />
                  <span class="text_bold fliesstexr text_normal<?php echo $err; ?>"><?php echo $text->get('lieferung', 'liefer_adr', 'lang'); ?></span>
               </div>

               <div id="lieferadresse">
                  <div class="line">
                     <div class="line_left fliesstext text_normal"><?php echo $text->get('kunde', 'anrede'); ?></div>
                     <div class="line_center"></div>
                     <div class="line_right">
                        <span class="select_wrapper">
                           <span class="selectbox">
                              <select class="text_formular text_normal" name="lf_anrede" id="lf_anrede">
                                 <?php echo KANPAICLASSIC\Helper::getAnredeOption($data['lf_anrede']); ?>
                              </select>
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
                     <div class="line_left fliesstext text_normal al"><?php echo $text->get('kunde', 'firma', 'lang'); ?></div>
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
                     <div class="line_right"><input type="text" class="text_formular text_normal<?php echo $err; ?>" class="text_formular text_normal" name="lf_plz" id="lf_plz" value="<?php echo $data['lf_plz']; ?>" /></div>
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
                     <div class="line_left fliesstext text_normal<?php echo $err; ?>"><?php echo $text->get('kunde', 'staat', 'lang'); ?>*</div>
                     <div class="line_center"></div>
                     <div class="line_right">
                        <span class="select_wrapper<?php echo $err; ?>">
                           <span class="selectbox">
                              <select class="text_formular text_normal" name="lf_staat" id="lf_staat"><?php echo $laender->getOption($data['lf_staat']); ?></select>
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
            <div class="clear"></div>
         </div>

         <div class="col_single">
            <button class="col_button bg_button text_gross button55"><?php echo $text->get('button', 'aktualisierenK'); ?></button>
         </div>
      </form>
   </div>
</div>
<?php
$script .= "<script>".CR;
$script .= "   $(function() {".CR;
$script .= "      $('#staat').change(function() { Royalart.checkStaat(0); });".CR;
$script .= "      $('#lf_staat').change(function() { Royalart.checkStaat(1); });".CR;
$script .= "".CR;
$script .= "      if ($('#staat option:selected').val() == '10') {".CR;
$script .= "         $('#noeu').show();".CR;
$script .= "      }".CR;
$script .= "".CR;
$script .= "      if ($('#lf_staat option:selected').val() == '10') {".CR;
$script .= "         $('#lf_noeu').show();".CR;
$script .= "      }".CR;
$script .= "   });".CR;
$script .= "".CR;
$script .= "   $(function() {".CR;
$script .= "      $('.main_sub').click(function() { ".CR;
$script .= "          if ($(this).hasClass('plus')) {".CR;
$script .= "             $(this).removeClass('plus').addClass('minus');".CR;
$script .= "             $(this).parent().parent().find('.sub').show()".CR;
$script .= "          } else { ".CR;
$script .= "             $(this).removeClass('minus').addClass('plus');".CR;
$script .= "             $(this).parent().parent().find('.sub').hide()".CR;
$script .= "          } ".CR;
$script .= "       }); ".CR;
$script .= "   });".CR;
$script .= "</script>".CR;
?>
