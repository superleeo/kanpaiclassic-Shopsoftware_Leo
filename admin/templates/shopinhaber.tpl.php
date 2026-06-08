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
   die ("This file cannot run outside the Kanpai Classic Shopsoftware");
}

header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time()));
header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
$menu           = KANPAICLASSIC\Control::getMenu();
$admin_config   = $menu->loadDesign();

$db_size = $this->db->querySingleValue("SELECT sum(round(((data_length + index_length) / 1024 / 1024), 2)) FROM information_schema.TABLES WHERE table_schema = '".CONF_DATABASE."'");
?><html lang="de">
<head>
<title>Kanpai Classic <?php echo (!defined('CONF_MODULE_PORTAL') ? 'Shopsystem' : 'Shopportal'); ?> - Administration - Shopinhaber</title>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<link rel="stylesheet" href="<?php echo SHOP_URL; ?>/fonts/fontawesome/css/all.css" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
<style>
<?php include_once ADMIN_PATH.'/css/'.(is_file(ADMIN_PATH.'/css/admin.css') ? 'admin.css' : 'admin_easy.css'); ?>
</style>
<link rel="stylesheet" href="<?php echo SHOP_URL; ?>/fonts/fontawesome/css/all.css" />
</head>

<body>
<div id="page" class="admin_bg">
   <?php echo $menu->printHeader(); ?>
   <div id="menu">
      <?php echo $menu->menuData(); ?>
   </div>

   <div id="content">
      <div id="titelzeile" class="titelzeile">
         <div class='txt_tit'><a class="help_kanpaiclassic" href="<?php echo HELP_LINK; ?>/einstellungen/shopinhaber/" target="_blank"></a>Shopinhaber</div>
         <div class="save_button" onClick="Shopinhaber.save();">speichern</div>
      </div>

      <div id="shopinhaber" class="maincontent">
         <div class="content_box content_box_bottom">
            <form id="inhaber_daten" method="post" action="<?php echo ADMIN_URL_IDX.'/shopinhaber/update'; ?>">
               <div class="box_left">
                  <div class="zeile">
                     <span class="shop_text_check">
                        <label class="shop_text txt_bez after">Benutzer</label>
                     </span>
                     <span class="shop_inp"><input type="text" name="user" value="<?php echo $_SESSION['user_name']; ?>" /></span>
                  </div>

                  <div class="zeile">
                     <span class="shop_text_check">
                        <label class="shop_text txt_bez after">Passwort</label>
                     </span>
                     <span class="shop_inp"><input type="text" id="pass1" name="password" value="" onFocus="$('#passwort2').css('visibility', 'visible');" onBlur="($(this).val() == '' ? $('#passwort2').css('visibility', 'hidden') : '');" onChange="Shopinhaber.checkPasswords();" /></span>
                  </div>
                  <div class="zeile" id="passwort2">
                     <span class="shop_text_check">
                        <label class="shop_text txt_bez after">PW-Wiederh.</label>
                     </span>
                     <span class="shop_inp"><input type="text" id="pass2" name="password2" value="" onBlur="($('#pass2').val() === '' ? $('#passwort2').css('visibility', 'hidden') : '');" onChange="Shopinhaber.checkPasswords();" /></span>
                  </div>

                  <div class="zeile">
                     <span class="shop_text_check">
                        <input type="checkbox" class="newdesign" id ="shop_name_check" name="shop_name_check"<?php echo ($this->shop->shop_name_check == 'y' ? ' checked="checked"' : ''); ?> />
                        <label for="shop_name_check" class="shop_text txt_bez after">Shopname</label>
                     </span>
                     <span class="shop_inp"><input type="text" name="shop_name" value="<?php echo $this->shop->shop_name; ?>" /></span>
                  </div>

                  <div class="zeile">
                     <span class="shop_text_check">
                        <input type="checkbox" class="newdesign" id ="firm_name_check" name="firm_name_check"<?php echo ($this->shop->firm_name_check == 'y' ? ' checked="checked"' : ''); ?> />
                        <label for="firm_name_check" class="shop_text txt_bez after">Firma</label>
                     </span>
                     <span class="shop_inp"><input type="text" name="firm_name" value="<?php echo $this->shop->firm_name; ?>" /></span>
                  </div>

                  <div class="zeile">
                     <span class="shop_text_check">
                        <input type="checkbox" class="newdesign" id ="first_name_check" name="first_name_check"<?php echo ($this->shop->first_name_check == 'y' ? ' checked="checked"' : ''); ?> />
                        <label for="first_name_check" class="shop_text txt_bez after">Vorname</label>
                     </span>
                     <span class="shop_inp"><input type="text" name="first_name" value="<?php echo $this->shop->first_name; ?>" /></span>
                  </div>

                  <div class="zeile">
                     <span class="shop_text_check">
                        <input type="checkbox" class="newdesign" id ="last_name_check" name="last_name_check"<?php echo ($this->shop->last_name_check == 'y' ? ' checked="checked"' : ''); ?> />
                        <label for="last_name_check" class="shop_text txt_bez after">Nachname</label>
                     </span>
                     <span class="shop_inp"><input type="text" name="last_name" value="<?php echo $this->shop->last_name; ?>" /></span>
                  </div>

                  <div class="zeile">
                     <span class="shop_text_check">
                        <input type="checkbox" class="newdesign" id ="street_check" name="street_check"<?php echo ($this->shop->street_check == 'y' ? ' checked="checked"' : ''); ?> />
                        <label for="street_check" class="shop_text txt_bez after">Straße/Nr.</label>
                     </span><span class="shop_inp">
                        <span class="shop_inp_left"><input type="text" name="street" value="<?php echo $this->shop->street;?>" /></span>
                        <span class="shop_inp_right"><input type="text" name="haus_nr" value="<?php echo $this->shop->haus_nr;?>" /></span>
                        <span class="clear"></span>
                     </span>
                  </div>

                  <div class="zeile">
                     <span class="shop_text_check">
                        <input type="checkbox" class="newdesign" id ="postal_code_check" name="postal_code_check"<?php echo ($this->shop->postal_code_check == 'y' ? ' checked="checked"' : ''); ?> />
                        <label for="postal_code_check" class="shop_text txt_bez after">PLZ</label>
                     </span>
                     <span class="shop_inp"><input type="text" name="postal_code" value="<?php echo $this->shop->postal_code; ?>" /></span>
                  </div>

                  <div class="zeile">
                     <span class="shop_text_check">
                        <input type="checkbox" class="newdesign" id ="city_check" name="city_check"<?php echo ($this->shop->city_check == 'y' ? ' checked="checked"' : ''); ?> />
                        <label for="city_check" class="shop_text txt_bez after">Ort</label>
                     </span>
                     <span class="shop_inp"><input type="text" name="city" value="<?php echo $this->shop->city; ?>" /></span>
                  </div>

                  <div class="zeile">
                     <span class="shop_text_check">
                        <input type="checkbox" class="newdesign" id ="country_check" name="country_check"<?php echo ($this->shop->country_check == 'y' ? ' checked="checked"' : ''); ?> />
                        <label for="country_check" class="shop_text txt_bez after">Land</label>
                     </span>
                     <span class="shop_inp"><input type="text" name="country" value="<?php echo $this->shop->country; ?>" /></span>
                  </div>
               </div>

               <div class="box_center">
                  <div class="zeile">
                     <span class="shop_text_check">
                        <input type="checkbox" class="newdesign" id ="email_check" name="email_check"<?php echo ($this->shop->email_check == 'y' ? ' checked="checked"' : ''); ?> />
                        <label for="email_check" class="shop_text txt_bez after">Shop-E-Mail</label>
                     </span>
                     <span class="shop_inp">
                        <input type="text" id="shop_email" class="email_input" name="email" value="<?php echo $this->shop->email; ?>" />
                        <span class="<?php echo (\KANPAICLASSIC\Helper::getData('smtp_check', 'n') == 'y' ? 'button_ci' : 'button'); ?> txt_but email_button" onclick="Shopinhaber.popupSmtp();">SMTP</span>
                        <span class="clear"></span>
                     </span>
                  </div>

                  <div class="zeile">
                     <span class="shop_text_check">
                        <input type="checkbox" class="newdesign" id ="mailfrom_check" name="mailfrom_check"<?php echo ($this->shop->mailfrom_check == 'y' ? ' checked="checked"' : ''); ?> />
                        <label for="mailfrom_check" class="shop_text txt_bez after">E-Mail von...</label>
                     </span>
                     <span class="shop_inp"><input type="text" name="mailfrom" value="<?php echo $this->shop->mailfrom; ?>" /></span>
                  </div>

                  <div class="zeile"></div>

                  <div class="zeile">
                     <span class="shop_text_check">
                        <input type="checkbox" class="newdesign" id ="bank1_check" name="bank1_check"<?php echo ($this->shop->bank1_check == 'y' ? ' checked="checked"' : ''); ?> />
                        <label for="bank1_check" class="shop_text txt_bez after">Bankinstitut</label>
                     </span>
                     <span class="shop_inp"><input type="text" name="bank1" value="<?php echo $this->shop->bank1; ?>" /></span>
                  </div>

                  <div class="zeile">
                     <span class="shop_text_check">
                        <input type="checkbox" class="newdesign" id ="bank1_iban_check" name="bank1_iban_check"<?php echo ($this->shop->bank1_iban_check == 'y' ? ' checked="checked"' : ''); ?> />
                        <label for="bank1_iban_check" class="shop_text txt_bez after">IBAN</label>
                     </span>
                     <span class="shop_inp"><input type="text" name="bank1_iban" value="<?php echo $this->shop->bank1_iban; ?>" /></span>
                  </div>

                  <div class="zeile">
                     <span class="shop_text_check">
                        <input type="checkbox" class="newdesign" id ="bank1_bic_check" name="bank1_bic_check"<?php echo ($this->shop->bank1_bic_check == 'y' ? ' checked="checked"' : ''); ?> />
                        <label for="bank1_bic_check" class="shop_text txt_bez after">BIC</label>
                     </span>
                     <span class="shop_inp"><input type="text" name="bank1_bic" value="<?php echo $this->shop->bank1_bic; ?>" /></span>
                  </div>

                  <div class="zeile">
                     <span class="shop_text_check">
                        <input type="checkbox" class="newdesign" id ="bank1_inhaber_check" name="bank1_inhaber_check"<?php echo ($this->shop->bank1_inhaber_check == 'y' ? ' checked="checked"' : ''); ?> />
                        <label for="bank1_inhaber_check" class="shop_text txt_bez after">Kontoinhaber</label>
                     </span>
                     <span class="shop_inp"><input type="text" name="bank1_inhaber" value="<?php echo $this->shop->bank1_inhaber; ?>" /></span>
                  </div>

                  <div class="zeile"></div>

                  <div class="zeile">
                     <span class="shop_text_check">
                        <input type="checkbox" class="newdesign" id ="finanzamt_check" name="finanzamt_check"<?php echo ($this->shop->finanzamt_check == 'y' ? ' checked="checked"' : ''); ?> />
                        <label for="finanzamt_check" class="shop_text txt_bez after">Finanzamt</label>
                     </span>
                     <span class="shop_inp"><input type="text" name="finanzamt" value="<?php echo $this->shop->finanzamt; ?>" /></span>
                  </div>

                  <div class="zeile">
                     <span class="shop_text_check">
                        <label class="shop_text txt_bez after">St.-Nr.</label>
                     </span>
                     <span class="shop_inp"><input type="text" name="steuernr" value="<?php echo $this->shop->steuernr; ?>" /></span>
                  </div>

                  <div class="zeile">
                     <span class="shop_text_check">
                        <input type="checkbox" class="newdesign" id ="ustid_check" name="ustid_check"<?php echo ($this->shop->ustid_check == 'y' ? ' checked="checked"' : ''); ?> />
                        <label for="ustid_check" class="shop_text txt_bez after">USt.-IdNr</label>
                     </span>
                     <span class="shop_inp"><input type="text" name="ustid" value="<?php echo $this->shop->ustid; ?>" /></span>
                  </div>
               </div>

               <div class="box_right">
                  <div class="zeile">
                     <span class="shop_text_check">
                        <input type="checkbox" class="newdesign" id ="paypal_mail_check" name="paypal_mail_check"<?php echo ($this->shop->paypal_mail_check == 'y' ? ' checked="checked"' : ''); ?> />
                        <label for="paypal_mail_check" class="shop_text txt_bez after">PayPal-E-Mail</label>
                     </span>
                     <span class="shop_inp"><input type="text" name="paypal_mail" value="<?php echo $this->shop->paypal_mail; ?>" /></span>
                  </div>

                  <div class="zeile"></div>

                  <div class="zeile"></div>

                  <div class="zeile">
                     <span class="shop_text_check">
                        <input type="checkbox" class="newdesign" id ="telefon_check" name="telefon_check"<?php echo ($this->shop->telefon_check == 'y' ? ' checked="checked"' : ''); ?> />
                        <label for="telefon_check" class="shop_text txt_bez after">Telefon</label>
                     </span>
                     <span class="shop_inp"><input type="text" name="telefon" value="<?php echo $this->shop->telefon; ?>" /></span>
                  </div>

                  <div class="zeile">
                     <span class="shop_text_check">
                        <input type="checkbox" class="newdesign" id ="fax_check" name="fax_check"<?php echo ($this->shop->fax_check == 'y' ? ' checked="checked"' : ''); ?> />
                        <label for="fax_check" class="shop_text txt_bez after">Fax</label>
                     </span>
                     <span class="shop_inp"><input type="text" name="fax" value="<?php echo $this->shop->fax; ?>" /></span>
                  </div>

                  <div class="zeile">
                     <span class="shop_text_check">
                        <input type="checkbox" class="newdesign" id ="email2_check" name="email2_check"<?php echo ($this->shop->email2_check == 'y' ? ' checked="checked"' : ''); ?> />
                        <label for="email2_check" class="shop_text txt_bez after">E-Mail</label>
                     </span>
                     <span class="shop_inp"><input type="text" name="email2" value="<?php echo $this->shop->email2; ?>" /></span>
                  </div>

                  <div class="zeile">
                     <span class="shop_text_check">
                        <input type="checkbox" class="newdesign" id ="web_check" name="web_check"<?php echo ($this->shop->web_check == 'y' ? ' checked="checked"' : ''); ?> />
                        <label for="web_check" class="shop_text txt_bez after">Web</label>
                     </span>
                     <span class="shop_inp"><input type="text" name="web" value="<?php echo $this->shop->web; ?>" /></span>
                  </div>

                  <div class="zeile"></div>

                  <div class="zeile">
                     <span class="shop_text_check">
                        <input type="checkbox" class="newdesign" id ="shop_frei1_check" name="shop_frei1_check"<?php echo (KANPAICLASSIC\Helper::getData('shop_frei1_check', 'n') == 'y' ? ' checked="checked"' : ''); ?> />
                        <label for="shop_frei1_check" class="shop_text txt_bez after">
                          <input type="text" name="shop_frei1_titel" value="<?php echo KANPAICLASSIC\Helper::getData('shop_frei1_titel', ''); ?>" />
                         </label>
                     </span>
                     <span class="shop_inp"><input type="text" name="shop_frei1_text" value="<?php echo KANPAICLASSIC\Helper::getData('shop_frei1_text', ''); ?>" /></span>
                  </div>

                  <div class="zeile">
                     <span class="shop_text_check">
                        <input type="checkbox" class="newdesign" id ="shop_frei2_check" name="shop_frei2_check"<?php echo (KANPAICLASSIC\Helper::getData('shop_frei2_check', '') == 'y' ? ' checked="checked"' : ''); ?> />
                        <label for="shop_frei2_check" class="shop_text txt_bez after">
                           <input type="text" name="shop_frei2_titel" value="<?php echo KANPAICLASSIC\Helper::getData('shop_frei2_titel', ''); ?>" />
                        </label>
                     </span>
                     <span class="shop_inp"><input type="text" name="shop_frei2_text" value="<?php echo KANPAICLASSIC\Helper::getData('shop_frei2_text', ''); ?>" /></span>
                 </div>

                  <div class="zeile">
                     <span class="shop_text_check">
                        <input type="checkbox" class="newdesign" id ="shop_frei3_check" name="shop_frei3_check"<?php echo (KANPAICLASSIC\Helper::getData('shop_frei3_check', '') == 'y' ? ' checked="checked"' : ''); ?> />
                        <label for="shop_frei3_check" class="shop_text txt_bez after">
                          <input type="text" name="shop_frei3_titel" value="<?php echo KANPAICLASSIC\Helper::getData('shop_frei3_titel', ''); ?>" />
                         </label>
                     </span>
                     <span class="shop_inp"><input type="text" name="shop_frei3_text" value="<?php echo KANPAICLASSIC\Helper::getData('shop_frei3_text', ''); ?>" /></span>
                  </div>
               </div>
               <div class="clear"></div>
            </form>
         </div>

         <?php if (defined('CONF_MODULE_STATISTIK')) { ?>
         <div class="titelzeile titelzeile2">
            <div class='txt_tit'><a class="help_kanpaiclassic" href="<?php echo HELP_LINK; ?>/seo/" target="_blank"></a>Statistik</div>
         </div>

         <div class="content_box content_box_bottom">
            <div id="statistik_umsatz">
               <h1 class="txt_tit">Umsatz-Statistik</h1>
               <div class="year_selector"><?php echo $year_select; ?></div>

               <div id="legende">
                  <div class="legende">
                     <span class="legende1">USt.</span>
                     <span class="legende2 col_alt_ust">&nbsp;</span>
                     <span class="legende3 col_aktuell_ust">&nbsp;</span>
                  </div>
                  <div class="legende">
                     <span class="legende1">Netto</span>
                     <span class="legende2 col_alt">&nbsp;</span>
                     <span class="legende3 col_aktuell">&nbsp;</span>
                  </div>
                  <div class="legende_2">
                     <span class="legende1">&nbsp;</span>
                     <span id="stat_last" class="legende2"><?php echo date('Y') - 1; ?></span>
                     <span id="stat_aktuell" class="legende3"><?php echo date('Y'); ?></span>
                  </div>
               </div>

               <div class="mobile_slide">
                  <div class="mobile_slide_inner">

                     <div id="statistik"><?php // Wird durch JS aktualisiert ?>
                     <?php echo $statistik_umsatz; ?>
                     </div>
                     <div class="clear"></div>
                  </div>
               </div>
            </div>

            <hr />
            <div id="statistik_clicks">
               <h1 class="txt_tit">Klick-Statistik</h1>
               <div class="bestseller_buttons">
                  <div class="button txt_but"><a href="<?php echo HELP_LINK; ?>/seo/" target="_blank">Google-Tipps</a></div>
               </div>

               <div class="mobile_slide">
                  <div class="mobile_slide_inner">
                     <div id="bestseller_article"><?php echo $bestseller_article; ?></div>
                  </div>
               </div>

               <div class="mobile_slide">
                  <div class="mobile_slide_inner">
                     <div id="bestseller_categories"><?php echo $bestseller_categories; ?></div>
                  </div>
               </div>
            </div>

            <hr />
            <div id="statistik_monat">
               <h1 class="txt_tit">Monatliche Klicks</h1>
                  <div id="use_statistic" class="pointer fas fa-power-off statistic_<?php echo (\KANPAICLASSIC\Helper::getData('use_statistic', 'n') == 'y' ? 'on' : 'off'); ?>" onclick="Shopinhaber.useStatistic();"></div>
               <div class="statistik_pos">
                  <div id="db_size">Datenbankgröße <span style="color:#cc0000;"><?php echo number_format($db_size, 2, ',', '.'); ?> MB</span> von 1000 MB</div>
                  <div class="button txt_but" onclick="(confirm('Klick- und Userstatistik löschen?') ? window.location.href='<?php echo ADMIN_URL_IDX; ?>/shopinhaber/deleteStatistik' : '');">Statistik-Reset</div>
               </div>
               <div class="clear"></div>
               <div class="year_selector"><?php echo $year_select_clicks; ?></div>

               <div id="legende_clicks">
                  <div class="legende">
                     <span id="user_klicks" class="legende1"><?php echo $user_klicks; ?></span>
                     <span class="legende2 col_alt">&nbsp;</span>
                     <span class="legende3 col_aktuell">&nbsp;</span>
                  </div>
                  <div class="legende_2">
                     <span class="legende1">&nbsp;</span>
                     <span id="stat_last_clicks" class="legende2"><?php echo $clicks_year -1 ; ?></span>
                     <span id="stat_aktuell_clicks" class="legende3"><?php echo $clicks_year; ?></span>
                  </div>
               </div>

               <div class="mobile_slide">
                  <div class="mobile_slide_inner">
                     <div id="statistik_monat_clicks">
                        <?php echo $statistik_clicks; ?>
                        <div class="clear"></div>
                     </div>

                  </div>
               </div>

               <div class="c_statistics">
                  <span class="txt_bez">Providerstatistik</span>
                  <br />
                  <span>Ihr Provider hat ebenfalls ausführliche Statistiken. Erkundigen Sie sich hierzu gern bei Ihrem Provider.</span>
               </div>
            </div>
         </div>
         <?php } ?>

      </div>
   </div>
   <?php $menu->footer(); ?>
</div>

<script>
var langs         = '<?php echo implode(';', $this->params->langs); ?>'; // vorhandene Sprachen - Nicht bei allen Templates notwendig
var sel_lang      = 'deu'; // gewählte Sprache - nicht bei allen Templates notwendig
var default_lang  = '<?php echo $this->params->default_lang; ?>';
var admin_url_idx = '<?php echo ADMIN_URL_IDX; ?>';
var admin_url     = '<?php echo ADMIN_URL; ?>';
var shopurl_idx   = '<?php echo SHOP_URL_IDX; ?>';
var shop_url      = '<?php echo SHOP_URL; ?>';
var template_url  = '<?php echo TEMPLATE_URL; ?>';
var max_file_size = '<?php echo max(KANPAICLASSIC\Helper::mbytesToBytes(ini_get('upload_max_filesize')), KANPAICLASSIC\Helper::mbytesToBytes(ini_get('post_max_size'))); ?>';
var editor_css    = "<?php echo TEMPLATE_URL; ?>/css/editor.css";
</script>

<script src="<?php echo SHOP_URL;  ?>/js/jquery3.min.js"></script>
<script src="<?php echo SHOP_URL;  ?>/js/jquery-ui.min.js"></script>
<script src="<?php echo ADMIN_URL; ?>/js/admin.js"></script>
<!-- <script src="<?php echo ADMIN_URL; ?>/js/jquery.minicolors.min.js"></script> -->
</body>
</html>
