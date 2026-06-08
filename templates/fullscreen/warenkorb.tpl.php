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

$steuer_count = 0;
$html1        = '';
$tax_active   = false;

if (KANPAICLASSIC\Helper::checkSteuer($_SESSION['rechnung_land'], $_SESSION['wk_land']) && $params->firma['tax_active'] == 'y') {
   $tax_active = true;
}
$paypalv2_button = (KANPAICLASSIC\Helper::getData('ppv2_check_button') == 'y' && $_SESSION['user']['nachname'] == '') ? true : false;
?>

<?php if (!$params->isAjax || $params->isAjax && $anzahl_wk == 0) { ?>

<?php  // Warenkorb leer ? ?>
<?php if ($anzahl_wk == 0 || $params->firma['price_login'] == 'y' && $params->user_id == 0) { ?>
<div class="warenkorb<?php echo $wk_first; ?>" class="wk_block col_single">
   <div class="wk_block col_single">
      <div id="detail_image" class="col_lsl_l bg_flaechen">
         <div class="site_head">
            <div class="ueberschrift text_max">
               <?php echo $text->get('menu', 'warenkorb'); ?>
            </div>
         </div>
      </div>

      <div class="col_lsl_m"></div>

      <div id="detail_image" class="col_lsl_r bg_flaechen">
         <div class="site_head">
            <div class=" fliesstext text_gross">
               <?php echo $text->get('warenkorb', 'leer'); ?>
            </div>
         </div>
      </div>
      <div class="clear"></div>
   </div>
</div>
<?php return; ?>
<?php } ?>

<?php // Warenkorb nicht leer ?>
<div class="col_single site_head bg_flaechen">
   <?php if ($params->user_id > 0 || $_SESSION['user']['nachname'] != '') { ?>
   <div class="ueberschrift wk_ueberschrift text_max"><?php echo $text->get('warenkorb', 'zus_fsg'); ?> / <?php echo $text->get('menu', 'warenkorb'); ?></div>
   <?php } else { ?>
   <div class="ueberschrift text_max"><?php echo $text->get('menu', 'warenkorb'); ?></div>
   <?php } ?>
</div>

<div class="warenkorb wk_block col_single<?php echo $wk_first; ?>">
   <form class="form_wk" name="form_wk" id="form_wk" method="post" action="<?php echo SHOP_URL_IDX; ?>/lieferung">
   <?php // Nachricht und GS-Newsletter ?>
   <?php if ($params->user_id > 0 || $_SESSION['user']['nachname'] != '') { ?>
      <div class="col_single">
         <div class="col_ll_r bg_flaechen col_left_height">
            <div class="line fliesstext left18 text_normal"><?php echo $text->get('kunde', 'nachricht', 'lang'); ?></div>
            <div class="gutschein_box">
               <textarea class="text_formular text_klein" name="nachricht" id="nachricht" onblur="Royalart.nachrichtSend(this, 0);"><?php echo $_SESSION['user_msg']; ?></textarea>
            </div>
         </div>

         <div class="col_ll_r bg_flaechen col_right_height">
            <?php if ($params->firma['gutschein_aktiv'] ==  'y' && $params->user_id > 0) { ?>
            <div class="line">
               <div class="gutschein_text fliesstext text_normal"><?php echo $text->get('kunde', 'newsletter'); ?></div>
               <div class="gutschein_check">
                  <input type="checkbox" name="newsletter1" id="newsletter1" onchange="$('#newsletter').val($(this).is(':checked') ? 'on' : 'off');"<?php echo (isset($_SESSION['newsletter']) && $_SESSION['newsletter'] == 'y' ? ' checked="checked"' : ''); ?> /><span class="checkbox"></span>
                  <input type="hidden" name="newsletter" id="newsletter" value="<?php echo ($_SESSION['newsletter'] == 'y' ? 'on' : 'off'); ?>" />
               </div>
            </div>
            <div class="gutschein_box">
               <div class="gutschein_box_inner text_formular text_klein"><?php echo $newsletter_text; ?></div>
            </div>
            <?php } else { ?>
            <div class="line" style="display:none;">
               <input type="checkbox" style="position:absolute;" name="newsletter" id="newsletter" <?php echo ($data['newsletter'] == 'y' ? ' checked="checked"' : ''); ?> />
            </div>
            <?php } ?>
         </div>
         <div class="clear"></div>
      </div>
      <?php } // if ($params->user_id > 0 || $_SESSION['user']['nachname'] != '') ?>
      <?php // Widerruf, Ausführung, AGB und Daten an DHL ?>
      <div class="col_single bg_flaechen" <?php echo (!$wk_first ? 'style="margin-bottom:17px;"' : ''); ?>>
      <?php /* if ($params->user_id > 0 || $_SESSION['user']['nachname'] != '' || $widerruf_wk == 4 || $widerruf_wk == 5) { */ ?>
      <?php if ($params->user_id > 0 || $_SESSION['user']['nachname'] != '') { ?>
         <?php if ($params->user_id > 0 || $_SESSION['user']['nachname'] != '') { ?>
         <input type="hidden" name="wk_check" value="on" />
         <?php } ?>

         <?php // Widerruf ?>
         <div class="agb_wider_box<?php echo (isset($_SESSION['widerruf_check']) && $_SESSION['widerruf_check'] == 'n' ? ' form_err' : ''); ?>" style="line-height:25px;">
         <?php if (!($params->firma['price_login'] == 'y' && $params->user_id == 0)) { ?>
            
             <input type="hidden" name="abholung_checkbox" value="<?php echo !(empty($_SESSION['abholung_checkbox']) || $_SESSION['abholung_checkbox'] != 'n')?'n':'y'; ?>" />

            <input type="hidden" class="min_preis_check" value="<?php echo $params->firma['min_preis_check_'.$tab]; ?>" />
            <input type="hidden" class="min_preis" value="<?php echo $params->firma['min_preis_'.$tab]; ?>" />
            <div class="agb_text fliesstext text_klein">
               <?php if ($params->firma['b2b_check'] != 'y') { // Nicht B2B ?>
                  <?php if (!defined('CONF_HAEKCHEN') || (defined('CONF_HAEKCHEN') && ($params->user_id > 0 || $_SESSION['user']['nachname'] != ''))) { ?>
                     <?php if (defined('CONF_HAEKCHEN')) { ?>
                        <input type="checkbox" name="widerruf_check" id="widerruf_check"<?php echo (isset($_SESSION['widerruf_check']) && $_SESSION['widerruf_check'] == 'y' ? ' checked="checked"' : ''); ?> />
                        <span class="checkbox"></span>
                     <?php } ?>
                     <?php // Link in [...] einfügen  ?>
                     <?php echo preg_replace(['|\[1(.*?)\]|', '|\[2(.*?)\]|'],
                        ["<a href='".SHOP_URL_IDX."/widerruf".$widerruf_wk."' target='_blank'><strong class='underline'>\\1</strong></a>",
                         "<a href='".SHOP_URL."/classes/pdf/Widerruf".chr($widerruf_wk + 64)."_".$lang.".pdf' target='_blank'><strong class='underline'>\\1</strong></a>"
                        ],
                        $text->get('warenkorb', 'lesen1')); ?>
                  <?php } ?>
               <?php } else { // B2B?>
                  <input type="hidden" name="widerruf_check" id="widerruf_check" value="on" />
               <?php } ?>
            </div>
         </div>

         <?php // Dienstleistung ?>
         <?php if ($widerruf_wk == 4 && $_SESSION['user']['nachname'] != '') { ?>
         <div class="agb_wider_box">
            <div class="agb_check fliesstext text_klein">
               <input type="radio" name="widerruf_dl" value="n" <?php echo (!isset($_SESSION['widerruf_dl']) || $_SESSION['widerruf_dl'] != 'y' ? ' checked="checked"' : ''); ?> />
               <span><?php echo $text->get('warenkorb', 'widerruf_dl'); ?></span>
            </div>
            <div class="agb_check fliesstext text_klein">
               <input type="radio" name="widerruf_dl" value="y" <?php echo (isset($_SESSION['widerruf_dl']) && $_SESSION['widerruf_dl'] == 'y' ? ' checked="checked"' : ''); ?> />
               <span> <?php echo nl2br(file_get_contents(SHOP_PATH.'/language/widerruf_dl_'.$params->selected_lang.'.txt')); ?></span>
            </div>
         </div>
         <?php } ?>

         <?php // Downloadartikel ?>
         <?php if ($widerruf_wk == 5 && $_SESSION['user']['nachname'] != '') { ?>
         <div class="agb_wider_box">
            <div class="agb_text fliesstext text_klein">
               <input type="checkbox" id="widerruf5_check" name="widerruf_down"<?php echo (isset($_SESSION['widerruf_down']) && $_SESSION['widerruf_down'] == 'y' ? ' checked="checked"' : ''); ?> onchange="$(this).prop('checked') ? $('#download_error').hide() : $('#download_error').show();" />
               <span class="checkbox"></span>
               <!-- <label for="widerruf5_check" class="download_check"></label> -->
               <?php echo $text->get('warenkorb', 'download'); ?>
            </div>
         </div>
         <?php } ?>
      <?php } ?>

         <?php // AGB ?>
         <div class="agb_wider_box<?php echo (isset($_SESSION['agb_check']) && $_SESSION['agb_check'] == 'n' ? ' form_err' : ''); ?>" style="line-height:25px;">
            <div class="agb_text fliesstext text_klein">
               <?php if (!defined('CONF_HAEKCHEN') || (defined('CONF_HAEKCHEN') && ($params->user_id > 0 || $_SESSION['user']['nachname'] != ''))) { ?>
                  <?php if (defined('CONF_HAEKCHEN')) { ?>
                     <input type="checkbox" name="agb_check" id="agb_check"<?php echo (isset($_SESSION['agb_check']) && $_SESSION['agb_check'] == 'y' ? ' checked="checked"' : ''); ?> />
                     <!-- <input type="checkbox" name="agb_check1" id="agb_check1" onchange="$('#agb_check').val($(this).is(':checked') ? 'on' : 'off');"<?php echo (isset($_SESSION['agb_check']) && $_SESSION['agb_check'] == 'y' ? ' checked="checked"' : ''); ?> /> -->
                     <span class="checkbox"></span>
                     <!-- <input type="hidden" name="agb_check" id="agb_check" value="<?php echo (isset($_SESSION['agb_check']) && $_SESSION['agb_check'] == 'y' ? 'on' : 'off'); ?>" /> -->
                  <?php } ?>
                  <?php echo preg_replace(['|\[1(.*?)\]|', '|\[2(.*?)\]|', '|\[3(.*?)\]|', '|\[4(.*?)\]|'],
                     ["<a href='".SHOP_URL_IDX."/agb/' target='_blank'><strong class='underline'>\\1</strong></a>",
                      "<a href='".SHOP_URL_IDX."/versand/' target='_blank'><strong class='underline'>\\1</strong></a>",
                      "<a href='".SHOP_URL_IDX."/datenschutz/' target='_blank'><strong class='underline'>\\1</strong></a>",
                      "<a href='".SHOP_URL."/classes/pdf/agb_".$lang.".pdf' target='_blank' ><strong class='underline'>\\1</strong></a>"
                     ],
                     $text->get('warenkorb', 'lesen2')); ?>
               <?php } ?>
             </div>
         </div>
      <?php } ?>
                    
      <?php // Weitergabe Adressdaten ?>
      <?php if ($params->user_id > 0 || $_SESSION['user']['nachname'] != '') { ?>
         <?php $ds_gvo = $db->querySingleObject("SELECT `text`, `check` FROM #__seiten WHERE art = 'ds_gvo' AND lang = '$params->selected_lang'"); ?>
         <?php if ($ds_gvo->check == 'y') { ?>
         <div class="agb_wider_box">
            <div class="fliesstext text_klein agb_text" id="ds_gvo_text">
               <span class="check_box">
                  <input type="checkbox" name="ds_gvo_check" id="ds_gvo_check"<?php echo (isset($_SESSION['ds_gvo_check']) && $_SESSION['ds_gvo_check'] == 'y' ? ' checked="checked"' :''); ?> />
                  <span class="checkbox"></span>
               </span>
               <?php echo $ds_gvo->text; ?>
            </div>
         </div>
         <?php } else { ?>
         <input type="hidden" name="ds_gvo_check" id="ds_gvo_check" value="on" />
         <?php }  ?>
      <?php } // if ($params->user_id > 0 || $_SESSION['user']['nachname'] != '') ?>
      </div>
   </form>
   <?php // Adressen ?>
   <?php if ($params->user_id > 0 || $_SESSION['user']['nachname'] != '') { ?>
   <div class="col_single" style="margin:10px 0;">
      <div class="col_lsl_l col_left_height">
         <div class="bg_flaechen bg_fullheight">
            <div class="line fliesstext left18 text_normal"><?php echo $text->get('lieferung', 'rechn_adr'); ?></div>
            <div class="adress_button"><a class="button bg_button col_button fliesstext text_gross" href="<?php echo SHOP_URL_IDX; ?>/lieferung/nc"><?php echo $text->get('button', 'aktualisieren'); ?></a></div>
            <div class="adresse">
            <?php if ($_SESSION['user']['firma'] != '') { ?>
               <p class="fliesstext text_normal"><?php echo $_SESSION['user']['firma']; ?> </p>
            <?php } ?>
            <p class="fliesstext text_normal"><?php echo $_SESSION['user']['vorname'].' '.$_SESSION['user']['nachname']; ?> </p>
            <p class="fliesstext text_normal"><?php echo $_SESSION['user']['adresse'].' '.$_SESSION['user']['hausnr']; ?> </p>
            <p class="fliesstext text_normal"><?php echo $_SESSION['user']['plz'].' '.$_SESSION['user']['ort']; ?> </p>
            <?php if ($_SESSION['user']['buland'] != '') { ?>
               <p class="fliesstext text_normal"><?php echo $_SESSION['user']['buland']; ?> </p>
            <?php } ?>
            <p class="fliesstext text_normal"><?php echo KANPAICLASSIC\Helper::getStaatName($_SESSION['user']['staat'], $_SESSION['user']['staat2']); ?> </p>
            <?php if ($_SESSION['user']['ustid'] != '') { ?>
               <p class="fliesstext text_normal"><?php echo $_SESSION['user']['ustid']; ?> </p>
            <?php } ?>
            </div>
         </div>
      </div>

      <div class="col_lsl_m"></div>

      <div class="col_lsl_r col_right_height">
         <div class="bg_flaechen bg_fullheight">
            <div class="line fliesstext left18 text_normal"><?php echo $text->get('lieferung', 'liefer_adr'); ?></div>
            <div class="adress_button"><a class="button bg_button col_button fliesstext text_gross" href="<?php echo SHOP_URL_IDX; ?>/lieferung/nc"><?php echo $text->get('button', 'aktualisieren'); ?></a></div>
            <div class="adresse">
            <?php if (!defined('CONF_MODULE_BESTELLZUSAMMENFASSUNG') || (isset($_SESSION['bestzus_adresse']) && $_SESSION['bestzus_adresse'] == 'ok')) { ?>
               <?php if ($_SESSION['user']['lieferadresse'] == 'y') { ?>
                  <?php if ($_SESSION['user']['lf_firma'] != '') { ?>
               <p class="fliesstext text_normal"><?php echo $_SESSION['user']['lf_firma']; ?> </p>
                  <?php } ?>
                  <?php if ($_SESSION['user']['lf_postnr'] != '') { ?>
               <p class="fliesstext text_normal"><?php echo $_SESSION['user']['lf_postnr']; ?> </p>
                  <?php } ?>
               <p class="fliesstext text_normal"><?php echo $_SESSION['user']['lf_vorname'].' '.$_SESSION['user']['lf_nachname']; ?> </p>
               <p class="fliesstext text_normal"><?php echo $_SESSION['user']['lf_adresse'].' '.$_SESSION['user']['lf_hausnr']; ?> </p>
               <p class="fliesstext text_normal"><?php echo $_SESSION['user']['lf_plz'].' '.$_SESSION['user']['lf_ort']; ?> </p>
                  <?php if ($_SESSION['user']['lf_buland'] != '') { ?>
               <p class="fliesstext text_normal"><?php echo $_SESSION['user']['lf_buland']; ?> </p>
                  <?php } ?>
               <p class="fliesstext text_normal"><?php echo KANPAICLASSIC\Helper::getStaatName($_SESSION['user']['lf_staat'], $_SESSION['user']['lf_staat2']); ?> </p>
               <?php } else { ?>
               <p class="fliesstext text_normal"><?php echo $_SESSION['user']['vorname'].' '.$_SESSION['user']['nachname']; ?> </p>
               <p class="fliesstext text_normal"><?php echo $_SESSION['user']['adresse'].' '.$_SESSION['user']['hausnr']; ?> </p>
               <p class="fliesstext text_normal"><?php echo $_SESSION['user']['plz'].' '.$_SESSION['user']['ort']; ?> </p>
                  <?php if ($_SESSION['user']['buland'] != '') { ?>
               <p class="fliesstext text_normal"><?php echo $_SESSION['user']['buland']; ?> </p>
                  <?php } ?>
               <p class="fliesstext text_normal"><?php echo KANPAICLASSIC\Helper::getStaatName($_SESSION['user']['staat'], $_SESSION['user']['staat2']); ?> </p>
               <?php } ?>
            <?php } ?>
            </div>
         </div>
      </div>
      <div class="clear"></div>
   </div>
   <?php } // if ($params->user_id > 0 || $_SESSION['user']['nachname'] != '') ?>

   <div class="wk_block col_single">
      <div id="wk_left_top" class="col_lsl_l col_left_height">
         <div class="bg_flaechen bg_fullheight">
         <?php // Artikel anzeigen ?>
         <?php $first = true; ?>

         <?php // Alle Artikel ($data[]) im WK anzeigen ?>
         <?php foreach ($data as $my_wk) { ?>
            <div class="wk_artikel">
            <?php $html_wk = ''; ?>
            <?php if (!$first) { ?>
               <div class="line_top"><hr class="line_top" /></div>
            <?php } ?>

            <?php $first = false; ?>

            <?php require TEMPLATE_PATH.'/warenkorb_article.tpl.php'; ?>
            <?php echo $html_wk; ?>
            </div>
         <?php } // foreach ?>
         </div>
      </div>

      <div class="col_lsl_m"></div>

      <?php // Zusmmenfassung ?>
      <div id="wk_right_top" class="col_lsl_r no_auto_height">
         <div class="bg_fullheight">
            <div  class="wk_summen_wrapper bg_flaechen">
      <?php } // !ajax ?>

      <?php // Für Ajax wird erst ab hier ausgegeben / Summen ?>
               <div class="wk_summen">
               <?php if ($params->firma['price_login'] == 'y' && $params->user_id == 0) { ?>
                  <div class="wk_summen">
   <?php // TODO Login / nicht auf Lager ?>
                     <div class="preis_img_nl">
                        <a href="<?php echo SHOP_URL_IDX; ?>/login"><img src="<?php echo TEMPLATE_URL; ?>/images/system/btn_preis_nl_<?php echo $params->selected_lang; ?>.png" /></a>
                     </div>
                     <div class="wk_login">
                        <a href="<?php echo SHOP_URL_IDX; ?>/login"><img src="<?php echo TEMPLATE_URL; ?>/images/system/btn_anmelden_<?php echo $params->selected_lang; ?>.jpg" /></a>
                     </div>
                  </div>

               <?php } else { ?>
                  <?php $err = ''; ?>
                  <?php if ($staat_error) {
                     $err = ' form_err';
                     unset($_SESSION['staat_error']);
                     ?>
                  <div class="form_err fliesstext text_gross" style="text-align:right; margin-bottom:10px;"><?php echo $text->get('wk', 'lf_err'); ?></div>
                  <?php } ?>
                  <div class="select_left fliesstext text_gross<?php echo $err; ?>"><?php echo $text->get('warenkorb', 'vland'); /*if($wk_land == 0 || $wk_land == 1){echo " / ". $text->get('warenkorb', 'abholung'); }*/ ?></div>
                  <div class="select_right">
                     <span class="select_wrapper<?php echo $err; ?>">
                        <span class="selectbox">
                           <select class="wk_select text_formular text_gross versand_land_sel" name="versand_land" id="versand_land" onchange="Royalart.wkBerechnen(this);">
                               
                               

                           <?php echo $laender->GetOptionWk($wk_land); ?>
                           </select>
                        </span>
                     </span>
                  </div>
                  <div class="clear"></div>

                   <?php
                         $abholung = $db->querySingleValue("SELECT abholung_check_1 FROM #__firma WHERE id = 1");

                         if($abholung == 'y'){ ?>

                   <div class="select_left fliesstext text_gross<?php echo $err; ?>">
                       <?php echo $text->get('warenkorb', 'abholung'); /*if($wk_land == 0 || $wk_land == 1){echo " / ". $text->get('warenkorb', 'abholung'); }*/ ?>
                   </div>

                   <div class="select_right">
                    <span class="check_box">                  
                        <input onchange="Royalart.wkBerechnen($(this));" type="checkbox" name="abholung_checkbox" id="abholung_checkbox" <?php echo (isset($_SESSION['abholung_checkbox']) && $_SESSION['abholung_checkbox'] == 'y' ? ' checked="checked"' :''); ?> />
                        <span class="checkbox"></span>
                    </span></div>

                   <div class="clear"></div>

                   <?php } else { ?>
                   <input type="hidden" name="abholung_checkbox" id="abholung_checkbox" value="off" />
                   <?php }  ?>

                  <div class="select_left fliesstext text_gross<?php echo ($_SESSION['zahlart_error'] === true ? ' form_err' : ''); ?>"><?php echo $text->get('artikel', 'zahlart'); ?></div>
                  <div class="select_right">
                     <span class="select_wrapper<?php echo ($_SESSION['zahlart_error'] === true ? ' form_err' : ''); ?>">
                        <span class="selectbox">
                           <select class="wk_select text_formular text_gross zahlart_sel" name="zahlart" id="zahlart" onchange="Royalart.wkBerechnen($(this));">
                              <?php echo KANPAICLASSIC\Helper::getZahlartOptions($zahlart, $abholpreis, $tab, $berechnung, $steuersatz); ?>
                           </select>
                        </span>
                     </span>
                  </div>
                  <div class="clear"></div>
                   
                   
                  <?php // Gutscheine aktiv oder Modul Gutschein-Print********************************************************** ?>
                  <?php if ($params->firma['activate_voucher'] == 'y' || defined('CONF_MODULE_GUTSCHEINPRINT')) { ?>
                     <?php $gutschein_code = $_SESSION['gutschein_code']; ?>
                  <div id="gutschein">
                     <hr class="line_summe" />
                     <?php if (defined('CONF_MODULE_GUTSCHEINPRINT') || $params->user_id > 0 || isset($_SESSION['schnellkauf_coupon'])) { // Reihenfolge wichtig! ?>
                        <?php if (KANPAICLASSIC\Helper::checkGutschein($gutschein_code, $wk_summe) === false) { ?>
                           <?php if ($gutschein_code != '') { ?>
                           <div id="gutschein_txt">
                              <span class="fliesstext gutschein_na text_klein form_err f_1"><?php echo $text->get('warenkorb', 'gs_min'); ?></span>
                              <span class="far fa-trash-alt" style="float:right; line-height:inherit; font-size:16px; cursor:pointer;" onclick="Royalart.delGutschein(this);"></span>
                           </div>
                           <?php } ?>

                        <?php } else { ?>
                           <div id="gutschein_txt" class ="fliesstext text_klein f_2"><?php echo $text->get('warenkorb', 'gutschein'); ?><?php echo ($params->firma['sonderpreis_ausschliessen'] == 'y' && $_SESSION['gutschein_mode'] == 2 ? '<span>&nbsp('.$text->get('warenkorb', 'gs_info').')</span>' : ''); ?></div>
                           <form id="wk_gutschein">
                           <?php if ($gutschein_code != '') { // code in SESSION ?>
                              <div class="gutschein_code_ok"><input class="text_formular text_normal" type="text" id="gutschein_code" name="gutschein_code" readonly="readonly" value="<?php echo $_SESSION['gutschein_code']; ?>">
                                 <span class="far fa-trash-alt" onclick="Royalart.delGutschein(this);"></span>
                              </div>
                           <?php } else { // nicht in SESSION ?>
                              <div class="gutschein_code">
                                 <input class="text_formular text_normal" type="text" id="gutschein_code" name="gutschein_code" >
                              </div>
                              <div class="gutschein_button col_button bg_button text_gross" onclick="Royalart.checkGutschein(this);"><?php echo $text->get('button', 'einloesen'); ?></div>
                           <?php } ?>
                           </form>
                        <?php } ?>
                     <?php } else { // nicht angemeldet ?>
                        <div  class ="fliesstext text_normal f_3" id="gutschein_txt"><?php echo $text->get('warenkorb', 'msg_anmelden'); ?></div>
                     <?php } ?>
                  </div>
                  <div class="clear"></div>
                  <?php } // Gutscheine ?>

                  <table class="wk_summe_tab">
                     <tr>
                        <td class="wk_summe_links fliesstext text_klein"><?php echo $text->get('artikel', 'zw_summe'); ?>:</td>
                        <td class="wk_summe_rechts fliesstext text_klein"><span style="display:none;" id="wk_preis"><?php echo $wk_netto; ?></span><?php echo KANPAICLASSIC\Helper::number_format($wk_summe, 2, ',', '.') . ' ' . $params->waehrung; ?></td>
                     </tr>

                     <?php if ($rabatt > 0) { ?>
                     <tr>
                        <td class="wk_summe_links fliesstext text_klein"><?php echo $text->get('artikel', 'rabatt'); ?>:</td>
                        <td class="wk_summe_rechts fliesstext text_klein">- <?php echo KANPAICLASSIC\Helper::number_format($rabatt, 2 , ',', '.') . ' ' . $params->waehrung; ?></td>
                     </tr>
                     <?php }  ?>

                     <tr>
                        <td class="wk_summe_links fliesstext text_klein"><?php echo $text->get('artikel', 'versand'); ?>:</td>
                        <td class="wk_summe_rechts fliesstext text_klein"><?php echo KANPAICLASSIC\Helper::number_format($versand_preis, 2, ',', '.') . ' ' . $params->waehrung; ?></td>
                     </tr>

                     <tr>
                        <td class="wk_summe_links fliesstext text_klein"><?php echo $text->get('artikel', 'zahlart').' '.$text->get('zahlart', 'leer'); ?>:</td>
                        <td class="wk_summe_rechts fliesstext text_klein"><?php echo KANPAICLASSIC\Helper::number_format($zahlart_preis, 2 , ',', '.') . ' ' . $params->waehrung; ?></td>
                     </tr>

                     <?php if (defined('CONF_MODULE_EASYCREDIT')) { ?>
                        <?php if (isset($_SESSION['ec_error']) && $_SESSION['ec_error'] != '') { ?>
                     <tr>
                        <td colspan="2" style="color:#ee0000;" class="fliesstext text_klein"><?php echo $_SESSION['ec_error']; ?></td>
                     </tr>
                        <?php unset($_SESSION['ec_error']); ?>
                        <?php } else { ?>
                        <?php if (isset($_SESSION['easycredit_deny']) && $_SESSION['easycredit_deny'] != '') { ?>
                     <tr>
                        <td colspan="2"><div style="text-align:right;"><?php echo $_SESSION['easycredit_deny']; ?></div></td>
                     </tr>
                        <?php unset($_SESSION['easycredit_deny']); ?>
                        <?php } else { ?>
                     <tr style="display:none;">
                        <td colspan="2"><div id="modellrechnung" data-modellrechnung="<?php echo $gesamt; ?>"></div></td>
                     </tr>
                        <?php } ?>
                     <?php } ?>

                     <tr id="modellrechnung_tr" style="display:none;">
                        <td class="wk_summe_links fliesstext text_klein"><?php echo $text->get('easycredit', 'zinsen'); ?></td>
                        <td id="modellrechnung_zinsen" class="wk_summe_rechts fliesstext text_klein"></td>
                     </tr>
                     <?php } // ENDE CONF_MODULE_EASYCREDIT?>

                     <?php if (defined('CONF_MODULE_KLARNA')) { ?>
                     <tr style="display:none;">
                        <td colspan="2"><div id="klarna_check" data-klarna_html="<?php echo (isset($_SESSION['klarna_check']) ? $_SESSION['klarna_check'] : 'n'); ?>"></div></td>
                     </tr>
                     <?php } // ENDE CONF_MODULE_KLARNA?>

                     <?php if ($gutschrift > 0) { ?>
                     <tr>
                        <td class="wk_summe_links fliesstext text_klein"><?php echo $text->get('artikel', 'gutschrift'); ?>:</td>
                        <td class="wk_summe_rechts fliesstext text_klein">- <?php echo KANPAICLASSIC\Helper::number_format($gutschrift, 2 , ',', '.') . ' ' . $params->waehrung; ?></td>
                     </tr>
                     <?php } ?>

                     <tr>
                        <td class="wk_summe_links">&nbsp</td>
                        <td class="wk_summe_rechts">&nbsp;</td>
                     </tr>

                     <tr class="tr_summe">
                        <td class="wk_summe_links ueberschrift text_max"><?php echo $text->get('artikel', 'summe'); ?>:</td>
                        <td class="wk_summe_rechts">
                           <div class="waehrung_line">
                              <?php echo $html1; ?>
                              <span class="wk_summe_rechts ueberschrift text_max"><span id="gesamtsumme"><?php echo KANPAICLASSIC\Helper::number_format($gesamt, 2, ',', '.'); ?></span> <?php echo $params->waehrung; ?></span>
                           </div>
                        </td>
                     </tr>
                     <tr class="tr_strich">
                        <td class="wk_summe_links fliesstext text_klein">&nbsp;</td>
                        <td class="wk_summe_rechts fliesstext"></td>
                     </tr>

                     <?php // Text Kleingewerbe anzeigen ?>
                     <?php if ($params->firma['kleingewerbe'] == 'y' || !$tax_active) { ?>
                        <?php if ($params->firma['kleingewerbe'] == 'y' ) { ?>
                        <tr>
                           <td class="wk_summe_links fliesstext text_klein"></td>
                           <td class="wk_summe_rechts fliesstext text_klein"><?php echo $text->get('article', 'preis_kleing'); ?></td>
                        </tr>

                        <?php // Text Kleingewerbe anzeigen ?>
                        <?php } else { ?>
                        <tr>
                           <td class="wk_summe_links fliesstext text_klein"></td>
                           <td class="wk_summe_rechts fliesstext text_klein"><?php echo $text->get('article', 'preis_ausland'); ?></td>
                        </tr>
                        <?php } ?>
                     <?php } ?>
                     <?php if ($tax_active) {
                        if ($params->firma['tax_show'] == 'y') {
                           $tax_text = $text->get('artikel', 'ust_lang');
                        }
                        else {
                           $tax_text = $text->get('art-detail', 'preis_netto').' '.$text->get('artikel', 'ust');
                        }
                     ?>
                        <?php if ($wk_steuer3 != 0) { ?>
                           <?php $steuer_count++; ?>
                     <tr>
                        <td class="wk_summe_links fliesstext <?php echo ($params->firma['tax_show'] !== 'y' ? 'text_normal' : 'text_klein'); ?>"><?php echo $tax_text; ?> <?php echo $params->firma['tax3']; ?>%:</td>
                        <td class="wk_summe_rechts fliesstext <?php echo ($params->firma['tax_show'] !== 'y' ? 'text_normal' : 'text_klein'); ?>"><?php echo KANPAICLASSIC\Helper::number_format($wk_steuer3, 2 , ',', '.') . ' ' . $params->waehrung; ?></td>
                     </tr>
                        <?php } ?>

                        <?php if ($wk_steuer2 != 0) { ?>
                           <?php $steuer_count++; ?>
                     <tr>
                        <td class="wk_summe_links fliesstext  <?php echo ($params->firma['tax_show'] !== 'y' ? 'text_normal' : 'text_klein'); ?>"><?php echo $tax_text; ?> <?php echo $params->firma['tax2']; ?>%:</td>
                        <td class="wk_summe_rechts fliesstext  <?php echo ($params->firma['tax_show'] !== 'y' ? 'text_normal' : 'text_klein'); ?>"><?php echo KANPAICLASSIC\Helper::number_format($wk_steuer2, 2 , ',', '.') . ' ' . $params->waehrung; ?></td>
                     </tr>
                        <?php } ?>

                        <?php if ($wk_steuer1 != 0) { ?>
                           <?php $steuer_count++; ?>
                     <tr>
                        <td class="wk_summe_links fliesstext  <?php echo ($params->firma['tax_show'] !== 'y' ? 'text_normal' : 'text_klein'); ?>"><?php echo $tax_text; ?> <?php echo $params->firma['tax1']; ?>%:</td>
                        <td class="wk_summe_rechts fliesstext  <?php echo ($params->firma['tax_show'] !== 'y' ? 'text_normal' : 'text_klein'); ?>"><?php echo KANPAICLASSIC\Helper::number_format($wk_steuer1, 2 , ',', '.') . ' ' . $params->waehrung; ?></td>
                     </tr>
                        <?php } ?>
                     <?php } ?>
                  </table>
               <?php } ?>
               </div>
               <div class="clear"></div>

      <?php // Bei Ajax wird bis hier ausgegeben ?>


      <?php if (!$params->isAjax) { // bei AJAX nicht ausgeben ?>
            </div>

            <div class="wk_button_wrapper ">
               <div id="pos_weiter1" class="select_left">
                  <div class="bg_flaechen">
                     <div class="wk_button_back button55 text_gross fliesstext pointer bg_button_only_hover ellipsis" onclick="location.href='<?php echo SHOP_URL_IDX; ?>';"><?php echo $text->get('button', 'einkaufen'); ?></div>
                     <span class=""></span>
                  </div>
               </div>
               <div id="pos_buttons" class="select_right">
               <?php // Entweder Altercheck failed ?>
               <?php if (defined('CONF_MODULE_PERSOCHECK') && isset($_SESSION['alter_failed'])) { ?>
                  <div class="wk_buttons button55" style="position:relative;">
                     <div id="minpreis_button" class="minpreis_button col_single button bg_button_no col_button text_gross button55"><?php echo $text->get('warenkorb', 'alter'); ?></div>
                  </div>
               <?php } else { ?>
               <?php // Oder Adressdaten OK - Senden, sonst Weiter anzeigen?>
               <?php $btn_text = ($params->user_id > 0 || $_SESSION['user']['nachname'] != '' ? $text->get('warenkorb', 'senden') : $text->get('button', 'wk_weiter')); ?>
                  <?php // Buttons liegen übereinander, nur der letzte ist zu sehen ?>
                  <div class="wk_buttons button55" style="position:relative">
                     <?php // Normaler Button ?>
                     <div id="wk_button" class="col_single button bg_button col_button text_gross button55" onclick="checkZahlart();"><?php echo $btn_text; ?></div>

                     <?php // Download Fehler / Widerruf 5?>
                     <?php if ($widerruf_wk == 5 && $_SESSION['user']['nachname'] != '') { ?>
                     <div id="download_error" class="col_single button bg_button_no col_button text_gross button55 ellipsis"<?php echo (isset($_SESSION['widerruf_down']) && $_SESSION['widerruf_down'] == 'y' ? ' style="display:none;"' : ''); ?> title="<?php echo $text->get('warenkorb', 'download_btn'); ?>"><?php echo $text->get('warenkorb', 'download_btn'); ?></div>
                     <?php } ?>

                     <?php // Minpreis nicht erreicht ?>
                     <div id="minpreis_button" class="col_single button bg_button_no col_button text_gross button55" <?php echo ($minpreis === false ? 'style="display:none;"' : ''); ?>><?php echo KANPAICLASSIC\Helper::number_format($min_preis, 2, ',', '.').' '.$params->waehrung.' '.$text->get('warenkorb', 'minpreis'); ?></div>
                  </div>
                  <span class=""></span>
               <?php } ?>
               </div>
               <div class="clear"></div>
               <div id="pos_weiter2" style="display:none;" onresize="console.log($(this).height());">
                  <div class="bg_flaechen">
                     <div class="wk_button_back button55 text_gross fliesstext pointer bg_button_only_hover ellipsis" onclick="location.href='<?php echo SHOP_URL_IDX; ?>';"><?php echo $text->get('button', 'einkaufen'); ?></div>
                     <span class=""></span>
                  </div>
               </div>
            </div>
            <?php if ($zahlart == 18 && $paypalv2_button) {?>
               <div class="wk_button_wrapper">
               <div class="select_left"></div>
                  <div class="select_right">
                     <div class="wk_buttons" id="paypalv2_smart_button"> </div>
                  </div>
               </div>
            <?php } ?>
            <div class="clear"></div>

            <div id="zahlart_error_left" class="select_left_err"></div>
            <div id="zahlart_error_right" class="select_right_err">
            <?php // Hidden Warnung: Zahlart zusätzlich, restlicher Code nicht beeinflusst, von PHP ausgewertet ?>
               <div id="zahlart_error"  class="zahlart_error col_single text_gross form_err center"<?php echo ($_SESSION['zahlart_error'] ? '' : ' style="display:none;"'); ?>><?php echo $text->get('warenkorb', 'zahlart'); ?></div>
            </div>
            <div class="clear"></div>
      <?php } ?>

            <div class="clear"></div>
         </div>
      </div>
      <div class="clear"></div>
   </div>

   <?php // EasyCredit Tilgungsplan / zusätzliche Angaben anzeigen ?>
   <?php if (defined('CONF_MODULE_EASYCREDIT') && $zahlart == 13 && isset($_SESSION['easycredit_vorvertrag']) && ($_SESSION['easycredit_vorvertrag'] != '' || $_SESSION['easycredit_check'] == 'n')) { ?>
   <?php $zahlungsplan = $_SESSION['easycredit_finanzierung']->ratenplan->zahlungsplan; ?>
   <?php $zinsen       = $_SESSION['easycredit_finanzierung']->ratenplan->zinsen; ?>
   <?php $gesamtsumme  = $_SESSION['easycredit_finanzierung']->ratenplan->gesamtsumme; ?>
   <?php $bestellwert  = $_SESSION['easycredit_finanzierung']->finanzierung->bestellwert; ?>
   <div id="easycredit">
      <div class="col_single" style="margin-top:17px;">
         <div style="position:relative; padding:18px;">
            <div class="col_lsl_l col_left_height">
               <div class="bg_flaechen bg_fullheight">
                  <div style="position:relative; width:111px; height:32px; margin-bottom:10px; background-image:url(<?php echo TEMPLATE_URL; ?>/images/system/ratenkauf.png);"></div>
                  <div class="easycredit_titel text_normal" style="height:24px; line-height:24px;"><strong>Tilgungsplan</strong></div>
                  <div class="text_normal fliesstext" style="padding-top:10px;">
                     <table>
                        <tr><td style="width:110px;">Bestellwert: </td><td><?php echo number_format((float)$bestellwert, 2, ',', '.'); ?>€</td></tr>
                        <tr><td style="width:110px;">Zinsen: </td><td><?php echo number_format((float)$zinsen->anfallendeZinsen, 2, ',', '.'); ?>€</td></tr>
                        <tr><td style="width:110px;">Gesamt: </td><td><?php echo number_format((float)$gesamtsumme, 2, ',', '.'); ?>€</td></tr>
                        <tr><td style="width:110px;">Laufzeit: </td><td><?php echo $zahlungsplan->anzahlRaten; ?> Monate</td></tr>
                        <tr><td style="width:110px;"><?php echo ((int)$zahlungsplan->anzahlRaten - 1); ?> x </td><td><?php echo number_format((float)$zahlungsplan->betragRate, 2, ',', '.'); ?>€</td></tr>
                        <tr><td style="width:110px;">letzte Rate </td><td><?php echo number_format((float)$zahlungsplan->betragLetzteRate, 2, ',', '.'); ?>€</td></tr>
                        <tr><td style="width:110px;">Nominalzins </td><td><?php echo number_format((float)$zinsen->nominalzins, 2, ',', '.'); ?>%</td></tr>
                        <tr><td style="width:110px;">Effektivzins </td><td><?php echo number_format((float)$zinsen->effektivzins, 2, ',', '.'); ?>%</td></tr>
                     </table>
                  </div>
               </div>
            </div>

            <div class="col_lsl_m">&nbsp;</div>

            <div class="col_lsl_r col_right_height">
               <div class="bg_flaechen bg_fullheight">
                  <div style="position:absolute; left:18px; bottom:18px;">
                     <a style="padding-left:18px; text-decoration:underline;" class="text_normal fliesstext" href="<?php echo $_SESSION['easycredit_vorvertrag']; ?>" target="_blank">Vorvertragliche Informationen zum Ratenkauf hier abrufen</a>
                  </div>
                  <br />
                  <div style="padding-left:18px;" class="text_normal fliesstext">Alternativ übermittelter Text:</div>
                  <br />
                  <div style="padding-left:18px;" class="text_normal fliesstext"><?php echo $_SESSION['easycredit_zahlungsplan']; ?></div>
               </div>
            </div>
            <div class="clear"></div>
         </div>
      </div>
   </div>
   <?php } ?>

</div>

<?php // Klarna: checkout erfolgreich. Wenn vorhanden, wird checkout nicht nochmals durchgeführt ?>
<?php if (isset($_SESSION['klarna_order_id']) && $_SESSION['klarna_price'] > 0) { ?>
   <div id="klarna_checkout_ok" style="display:none;"></div>
<?php } ?>

<?php // Klarna: Snippet2 nach checkout in neuem Tab anzeigen ?>
<?php if (isset($_SESSION['klarna_checkout_back'])) { ?>
<script>
// $( document ).tooltip();
</script>
<?php } 
?>
<?php if ($zahlart == 18 && $paypalv2_button) { 
      $ppv2 = KANPAICLASSIC\Control::getPaypalv2();
      $clientID = $ppv2->getClientID();
?>      
   <script src="https://www.paypal.com/sdk/js?client-id=<?php echo $clientID?>&currency=EUR&disable-funding=credit,card"> </script>
   <script> 
      paypal.Buttons({ locale: 'de_DE',
      createOrder: function(data, actions) {
         //get order JSON string from server
         const updateRechnung = new FormData();
         updateRechnung.append('ppv2_button', 'true');
         return fetch(shop_url_idx+"/ajax/lieferung", {
            method: 'POST',
            body: updateRechnung
         })
            .then(response => response.json())
            .then(data => {
                  if (data.status === 'ok'){
                     return actions.order.create(JSON.parse(data.html))
                  }else{
                     return false;  
                  }
               }  
            );
      },

      onApprove: function(data, actions) {            
         return actions.order.capture().then(function(details) {
            // if is complete, redirect to paypal_ok
            if (details.status === 'COMPLETED'){
               const updateRechnung = new FormData();
               updateRechnung.append('data', JSON.stringify(details));
               updateRechnung.append('ppv2_button', 'true');
               fetch(shop_url_idx+"/ajax/paypalv2_notify", {
                     method: 'POST',
                     body: updateRechnung
                  })
                  .then(response => response.json())
                  .then(data => {
                        //redirect
                        document.location.replace("<?php echo SHOP_URL_IDX.'/paypal_ok'?>");
                     }  
               );
            }
            
         });
      },
      onError : function (err){
         //redirect to paypal_error 
         document.location.replace("<?php echo SHOP_URL_IDX.'/paypal_error'?>");
         console.log('error' +err);
      },
      onCancel: function (data) {
         console.log('canceled');
      }
      }).render('#paypalv2_smart_button');
</script>
<?php }?>

