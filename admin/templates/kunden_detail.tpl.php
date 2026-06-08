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

if (!defined('KANPAICLASSIC')) {
   die ("This file cannot run outside the Kanpai Classic Shopsoftware");
}

header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time()));
header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
$menu                 = KANPAICLASSIC\Control::getMenu();
$admin_config         = $menu->loadDesign();

$laender  = KANPAICLASSIC\Control::getLaender();
$help     = KANPAICLASSIC\Control::getHelp();
?><!DOCTYPE html>
<html lang="de">
<head>
<title>Kanpai Classic <?php echo (!defined('CONF_MODULE_PORTAL') ? 'Shopsystem' : 'Shopportal'); ?> - Administration - Seiten</title>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
<link rel="stylesheet" href="<?php echo SHOP_URL; ?>/fonts/fontawesome/css/all.css" />
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
         <div class="txt_tit"><a class="help_kanpaiclassic" href="<?php echo HELP_LINK; ?>/kunden/" target="_blank"></a>Kunden-Account</div>
<!--         <div class="save_button" onclick="$('#form_kunden').submit()">speichern</div> -->
         <div class="save_button" onclick="Kunden.checkForm()">speichern</div>
         <div class="language">
            <div class="button_cancel txt_but pointer" onclick="window.location.href = '<?php echo ADMIN_URL_IDX.'/kunden'; ?>'">zurück</div>
         </div>
         <?php if (defined('CONF_MODULE_PORTAL') && $_SESSION['haendler'] != 'y') { ?>
         <div style="line-height: 20px; margin-left: 160px; margin-top:-8px; float: left;">
            <?php $haendler = $this->_getHaendler($data->haendler_id); ?>
               <?php if (is_object($haendler)) { ?>
            Verkäufer <div class="kunde_icon"
                           style="display:inline-block; position:relative; height:21px; width:12px; top:8px;"
                           onclick="Royalart.haendlerEdit(<?php echo $haendler->user_id; ?>);"
                           title="<?php echo ($haendler->firma != '' ? $haendler->firma : $haendler->vorname.' '.$haendler->nachname); ?>">
            </div>
            <?php } else { ?>
            Bestellung ist keinem Verkäufer zugeordnet!
            <?php } ?>
         </div>
         <?php } ?>
      </div>

      <div id="kunden_detail" class="maincontent content_box content_box_bottom">
         <form id="form_kunden" method="post" action="<?php echo ADMIN_URL_IDX; ?>/kunden/save">
            <div class="block_status block_left">
               <input type="hidden" name="user_id" id="user_id" value="<?php echo $data->id; ?>" />

               <div class="block_inner">
                  <div class="line">
                     <div class="line_txt right">Status:</div>
                     <div class="line_inp">
                     <?php if (defined('CONF_MODULE_PORTAL') && $_SESSION['haendler'] == 'n') { ?>
                        <span><?php echo ($data->role_a == 9 ? 'nicht verifiziert' : 'verifiziert'); ?></span>
                        <input type="hidden" name="stammkunde" id="stammkunde" value="'.$data->role.'" />
                     <?php } else { ?>
                        <span class="selectbox30">
                           <select name="stammkunde" id="stammkunde">
                              <option value="9" <?php echo KANPAICLASSIC\Helper::selected($data->role, 9); ?>>nicht verifiziert</option>
                              <option value="10" <?php echo KANPAICLASSIC\Helper::selected($data->role, 10); ?>>Neukunde</option>
                              <?php if (!defined('CONF_MODULE_RABATTE')) { ?>
                              <option value="11" <?php echo KANPAICLASSIC\Helper::selected(($data->role > 10 && $data->role < 18 ? 11 : 0), 11); ?>>Stammkunde</option>
                              <?php } else { ?>
                              <option value="11" <?php echo KANPAICLASSIC\Helper::selected($data->role, 11); ?>>Rabattgruppe 1</option>
                              <option value="12" <?php echo KANPAICLASSIC\Helper::selected($data->role, 12); ?>>Rabattgruppe 2</option>
                              <option value="13" <?php echo KANPAICLASSIC\Helper::selected($data->role, 13); ?>>Rabattgruppe 3</option>
                              <option value="14" <?php echo KANPAICLASSIC\Helper::selected($data->role, 14); ?>>Rabattgruppe 4</option>
                              <option value="15" <?php echo KANPAICLASSIC\Helper::selected($data->role, 15); ?>>Rabattgruppe 5</option>
                              <option value="16" <?php echo KANPAICLASSIC\Helper::selected($data->role, 16); ?>>Rabattgruppe 6</option>
                              <option value="17" <?php echo KANPAICLASSIC\Helper::selected($data->role, 17); ?>>Rabattgruppe 7</option>
                              <?php } ?>
                              <option value="1010" <?php echo KANPAICLASSIC\Helper::selected($data->role, 1010); ?>>VIP (rot markiert)</option>
                           </select>
                        </span>
                     <?php } ?>
                        <input type="hidden" name="stammkunde_a" id="stammkunde_a" value="<?php echo $data->role_a; ?>" />
                     </div>
                     <div class="line_extra">
                        <div class="help ci_color" onMouseOver="helptipOn(this, '<?php echo $help->getText(1); ?>');" onMouseOut="helptipOff();"></div>
                     </div>
                  </div>

                  <div class="line">
                     <div class="line_inp">
                        <input type="checkbox" class="newdesign" name="newsletter" id="newsletter" <?php echo ($data->newsletter == 'y' && $data->newsletter_check == 'ok') ? ' checked="checked"' : ''; ?> />
                        <label for="newsletter">&nbsp;</label>Newsletter
                        <?php echo ($data->newsletter == 'y' && $data->newsletter_check != 'ok' ? '<span style="vertical-align:top;">nicht verifiziert</span>' : ''); ?>
                     </div>

                     <?php if ($this->params->firma['rechnung_check'] == 'y') { ?>
                     <div class="line_inp">
                        <input type="checkbox" class="newdesign" name="rechnung_kunde" id="rechnung_kunde" <?php echo ($data->rechnung_kunde == 'y' ? ' checked="checked"' : ''); ?> />
                        <label for="rechnung_kunde">&nbsp;</label>Zahlung per Rechnung
                     </div>
                     <?php } else { ?>
                     <input type="checkbox" name="rechnung_kunde" id="rechnung_kunde" <?php echo ($data->rechnung_kunde == 'y' ? ' checked="checked"' : ''); ?> style="display:none;" />
                     <div class="line_extra"></div>
                     <?php } ?>
                     <div class="line_extra"></div>
                  </div>

                  <?php if ($data->pp_mail != '') { ?>
                  <div class="line paypal_kunde">
                     <div class="line_txt right paypal_logo_kunde">Paypal</div>
                     <div class="line_inp"><?php echo $data->pp_mail; ?></div>
                  </div>
                  <div class="line paypal_kunde">
                     <div class="line_txt right"> </div>
                     <div class="line_inp">ID: <?php echo $data->pp_id; ?></div>
                  </div>
                  <?php } ?>
               </div>
            </div>

            <div class="block_info block_right">
               <div class="block_inner">
                  <div class="line">
                     <div class="line_txt right">registriert seit:</div>
                     <div class="line_inp"><input class="inp90" type="text" disabled="disabled" value="<?php echo date('d.m. Y', strtotime($data->created)); ?>" /></div>
                     <div class="line_extra"></div>
                  </div>

                  <?php
                  $alter = '';

                  if (checkdate((int)substr($data->gebdatum, 5,2), (int)substr($data->gebdatum, 8,2), (int)substr($data->gebdatum, 0, 4))) {
                     $heute  = new DateTime(date('Y-m-d'));
                     $geburt = new DateTime($data->gebdatum);
                     $alter  = (int)$geburt->diff($heute)->format('%y').' Jahre';
                  } ?>

                  <div class="line">
                     <div class="line_txt right alter" onmouseover="$('#alter').css('opacity', 1);" onmouseout="$('#alter').css('opacity', 0);">Geburtsdatum:</div>
                     <div class="line_inp">
                        <input type="text" name="gebdatum" id="gebdatum" value="<?php echo KANPAICLASSIC\Helper::sqlDatum($data->gebdatum); ?>" />
                     </div>
                     <div class="line_perso">&nbsp;
                     <?php if (defined('CONF_MODULE_PERSOCHECK')) { ?>
                        <input type="checkbox" class="newdesign" id="alter_check"<?php echo ($data->alter_check != '' ? ' checked="checked"' : ''); ?> onchange="Kunden.alterCheck()" data-alter_check="<?php echo $data->alter_check; ?>" />
                        <label for="alter_check"></label>
                        <span id="perso_text">
                        <?php if ($data->alter_check != 'Admin') { ?>
                           Perso gecheckt&nbsp;
                        <?php } else { ?>
                           manuell gecheckt&nbsp;
                        <?php } ?>
                        </span>
                     <?php } ?>
                        <span id="alter" onmouseover="$('#alter').css('opacity', 1);" onmouseout="$('#alter').css('opacity', 0);"><?php echo $alter; ?></span>
                     </div>
                  </div>

                  <div class="line">
                     <div class="line_txt right">Dauerrabatt in %:</div>
                     <div class="line_inp"><input type="text" name="rabatt" value="<?php echo number_format($data->rabatt, 2, ',', '.'); ?>" /></div>
                     <div class="line_extra"></div>
                  </div>

                  <div class="line">
                     <div class="line_txt right">Gutschrift in <?php echo $this->params->waehrung_iso; ?>:</div>
                     <div class="line_inp"><input type="text" name="gutschrift" id="gutschrift" value="<?php echo number_format($data->gutschrift, 2, ',', '.'); ?>" /></div>
                     <div class="line_extra">
                        <div class="button txt_but" onClick="Kunden.gutschrift(this);">senden</div>
                     </div>
                  </div>
               </div>
            <div class="clear"></div>
         </div>
            <div class="clear"></div>

            <?php // ****************** Adresse ************************************************************ ?>
            <div class="adressen">
               <?php // ****************** Rechnungs-Adresse ************************************************************ ?>
               <div class="adresse_rechnung block_left">
                  <div class="bg_block">
                     <div class="line">
                        <div class="line_txt right"></div>
                        <div class="line_inp txt_tit ellipsis">aktuelle Rechnungsanschrift</div>
                     </div>

                     <div class="line">
                        <div class="line_txt right">Name</div>
                        <div class="line_inp">
                           <span class="selectbox30 pos3_1">
                              <select name="anrede_sel" id="anrede_sel" onChange="$('#anrede').val(this.value);">
                                 <?php echo KANPAICLASSIC\Helper::getAnredeOption($data->anrede); ?>
                              </select>
                           </span>
                           <input type="hidden" name="anrede" id="anrede" value="<?php echo $data->anrede; ?>" />
                           <span class="pos3_2"><input type="text" name="vorname" id="vorname" value="<?php echo $data->vorname; ?>" /></span>
                           <span class="pos3_3"><input type="text" name="nachname" id="nachname" value="<?php echo $data->nachname; ?>" /></span>
                           <span class="clear"></span>
                        </div>
                     </div>

                     <div class="line right">
                        <div class="line_txt">Firma</div>
                        <div class="line_inp"><input type="text" name="firma" id="firma" value="<?php echo $data->firma; ?>" /></div>
                     </div>

                     <div class="line">
                        <div class="line_txt right">Ust.-IdNr.</div>
                        <div class="line_inp"><input type="text" name="ustid" id="ustid" value="<?php echo $data->ustid; ?>" /></div>
                     </div>

                     <div class="line">
                        <div class="line_txt right">Straße</div>
                        <div class="line_inp">
                           <span class="pos2_3">
                              <input type="text" name="adresse" id="adresse" value="<?php echo $data->adresse; ?>" />
                           </span>
                           <span class="pos2_4">
                              <input type="text" name="hausnr" id="hausnr" value="<?php echo $data->hausnr; ?>" />
                           </span>
                           <span class="clear"></span>
                        </div>
                     </div>

                     <div class="line">
                        <div class="line_txt right">PLZ / Stadt</div>
                        <div class="line_inp">
                           <span class="pos2_1">
                              <input type="text" name="plz" id="plz" value="<?php echo $data->plz; ?>" />
                           </span>
                           <span class="pos2_2">
                              <input type="text" name="ort" id="ort" value="<?php echo $data->ort; ?>" />
                           </span>
                           <span class="clear"></span>
                        </div>
                     </div>

                     <div class="line">
                        <div class="line_txt right">Bundesland</div>
                        <div class="line_inp"><input type="text" name="buland" id="buland" value="<?php echo $data->buland; ?>" /></div>
                     </div>

                     <div class="line">
                        <div class="line_txt right">Land</div>
                        <div class="line_inp">
                           <span class="selectbox30 pos2_5">
                              <select name="staat" id="staat"><?php echo $laender->getOption($data->staat); ?></select>
                           </span>
                           <span class="pos2_6" id="no_eu" style="display:none;">
                              <input type="text" name="staat2" id="staat2" value="<?php echo $data->staat2; ?>" />
                           </span>
                           <span class="clear"></span>
                        </div>
                     </div>

                     <div class="line">
                        <div class="line_txt right">Telefon</div>
                        <div class="line_inp"><input type="text" name="telefon" id="telefon" value="<?php echo $data->telefon; ?>" /></div>
                     </div>

                     <div class="line">

                        <div class="line_txt right">
                           <a href="mailto:<?php echo $data->email; ?>"><span class="ci_color far fas fa-envelope pointer"></span></a>&nbsp;E-Mail
                        </div>
                        <div class="line_inp">
                           <span class="pos_mail"><input type="text" name="email" id="email" value="<?php echo $data->email; ?>" onchange="Kunden.checkEmail();" /></span>
                           <span class="right pos_pw_text ellipsis">Passwort</span>
                           <span class="pos_pw"><input type="text" name="password" id="password" value=""></span>
                        </div>
                     </div>

                     <div class="line">
                        <div class="line_txt right">&nbsp;</div>
                        <div class="line_inp">
                           <span class="pos_mail">&nbsp;</span>
                           <span class="right pos_pw_text ellipsis">Vergessen?</span>
                           <span class="pos_pw"><span class="button button_border txt_but" onclick="Kunden.forgotten(this, <?php echo $data->id; ?>);">verify senden</span></span>
                        </div>
                        <div class="line_extra"></div>
                     </div>
                  </div>
               </div>

               <?php // ****************** Liefer-Adresse ************************************************************ ?>
               <div class="adresse_lieferung block_right">
                  <div class="bg_block">
                     <div class="line">
                        <div class="line_txt right"></div>
                        <div class="line_inp txt_tit ellipsis">Lieferanschrift</div>
                     </div>

                     <div class="line">
                        <div class="line_txt right">Name</div>
                        <div class="line_inp">
                           <span class="selectbox30 pos3_1">
                              <select name="lf_anrede_sel" id="lf_anrede_sel" onChange="$('#lf_anrede').val(this.value);">
                                 <?php echo KANPAICLASSIC\Helper::getAnredeOption($data->lf_anrede); ?>
                              </select>
                           </span>
                           <input type="hidden" name="lf_anrede" id="lf_anrede" value="<?php echo $data->lf_anrede; ?>" />
                           <span class="pos3_2"><input type="text" name="lf_vorname" id="lf_vorname" value="<?php echo $data->lf_vorname; ?>" /></span>
                           <span class="pos3_3"><input type="text" name="lf_nachname" id="lf_nachname" value="<?php echo $data->lf_nachname; ?>" /></span>
                           <span class="clear"></span>
                        </div>
                     </div>

                     <div class="line">
                        <div class="line_txt right">Firma</div>
                        <div class="line_inp"><input type="text" name="lf_firma" id="lf_firma" value="<?php echo $data->lf_firma; ?>" /></div>
                     </div>

                     <div class="line">
                        <div class="line_txt right">Postnummer</div>
                        <div class="line_inp"td><input type="text" name="lf_postnr" id="lf_postnr" value="<?php echo $data->lf_postnr; ?>" /></div>
                     </div>

                     <div class="line">
                        <div class="line_txt right">Straße</div>
                        <div class="line_inp">
                           <span class="pos2_3">
                              <input type="text" name="lf_adresse" id="lf_adresse" value="<?php echo $data->lf_adresse; ?>" />
                           </span>
                           <span class="pos2_4">
                              <input type="text" name="lf_hausnr"  id="lf_hausnr"  value="<?php echo $data->lf_hausnr; ?>"  />
                           </span>
                           <span class="clear"></span>
                        </div>
                     </div>

                     <div class="line">
                        <div class="line_txt right">PLZ / Stadt</div>
                        <div class="line_inp">
                           <span class="pos2_1">
                              <input type="text" name="lf_plz" id="lf_plz" value="<?php echo $data->lf_plz; ?>" />
                           </span>
                           <span class="pos2_2">
                              <input type="text" name="lf_ort" id="lf_ort" value="<?php echo $data->lf_ort; ?>" />
                           </span>
                           <span class="clear"></span>
                        </div>
                     </div>

                     <div class="line">
                        <div class="line_txt right">Bundesland</div>
                        <div class="line_inp"><input type="text" name="lf_buland" id="lf_buland" value="<?php echo $data->lf_buland; ?>" /></div>
                     </div>

                     <div class="line">
                        <div class="line_txt right">Land</div>
                        <div class="line_inp">
                           <div class="selectbox30 pos2_5">
                              <select name="lf_staat" id="lf_staat">
                                 <?php echo $laender->getOption($data->lf_staat); ?>
                              </select>
                           </div>
                           <span class="pos2_6" id="lf_no_eu" style="display:none;">
                              <input type="text" name="lf_staat2" id="lf_staat2" value="<?php echo $data->lf_staat2; ?>" />
                           </span>
                           <span class="clear"></span>
                        </div>
                     </div>
                  </div>

                  <div class="bg_block margin_top">
                     <div class="line">
                        <div class="line_txt right"></div>
                        <div class="line_inp txt_bez">Bankverbindung bei Einzugsermächtigung</div>
                     </div>

                     <div class="line">
                        <div class="line_txt right">Inhaber / Bank</div>
                        <div class="line_inp">
                           <span class="pos2_5">
                              <input type="text" name="bank_inhaber" id="bank_inhaber" value="<?php echo $data->bank_inhaber; ?>" />
                           </span>
                           <span class="pos2_6">
                              <input type="text" name="bank_name" id="bank_name" value="<?php echo $data->bank_name; ?>" />
                           </span>
                           <span class="clear"></span>
                        </div>
                     </div>

                     <div class="line">
                        <div class="line_txt right">IBAN /BIC</div>
                        <div class="line_inp">
                           <span class="pos2_5">
                              <input type="text" name="bank_iban" id="bank_iban" value="<?php echo $data->bank_iban; ?>" />
                           </span>
                           <span class="pos2_6">
                              <input type="text" name="bank_bic" id="bank_bic" value="<?php echo $data->bank_bic; ?>" />
                           </span>
                           <span class="clear"></span>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="clear"></div>
            </div>

            <?php // ****************** Nachrichten ************************************************************ ?>
            <div class="block_messages">
               <div class="messages_kunde block_left">
                  <div class="nachricht txt_bez">Kunden-Bestellungen</div>
                  <div class="button_line">
                     <span class="button_float button txt_but" onClick="Kunden.bestellung(<?php echo $data->id; ?>);">Bestellungen</span>
                     <span class="button_float pointer" onclick="location.href='<?php echo \ADMIN_URL_IDX; ?>/bestellungen/neu/<?php echo $data->id; ?>';">
                        <span class="button">neu</span>
                     </span>
                     <?php if (defined('CONF_MODULE_BESTELLUNGFRONT')) { ?>
                     <span class="button_float button_round pointer">
                        <a href="<?php echo SHOP_URL_IDX; ?>/bestellungfront/<?php echo $data->id; ?>" target="_blank"><span class="fas fa-shopping-cart"></span></a>
                     </span>
                     <?php } ?>
                     <div class="clear"></div>
                  </div>
                  <div class="clear"></div>
               </div>

               <div class="messages_admin block_right">
                  <div class="nachricht txt_bez">Interne Notiz zum Kunden</div>
                  <textarea name="notiz" id="notiz"><?php echo $data->info; ?></textarea>
               </div>
               <div class="clear"></div>
            </div>
         </form>
      </div>
   </div>
   <?php $menu->footer(); ?>
</div>

<script>
var langs         = '<?php echo implode(';', $this->params->langs); ?>'; // vorhandene Sprachen - Nicht bei allen Templates notwendig
var sel_lang      = 'deu'; // gewählte Sprache - nicht bei allen Templates notwendig
var baseurl_idx   = '<?php echo ADMIN_URL_IDX; ?>';
var baseurl       = '<?php echo ADMIN_URL; ?>';
var admin_url_idx = '<?php echo ADMIN_URL_IDX; ?>';
var admin_url     = '<?php echo ADMIN_URL; ?>';
var shopurl_idx   = '<?php echo SHOP_URL_IDX; ?>';
var shopurl       = '<?php echo SHOP_URL; ?>';
var linkurl       = '<?php echo SHOP_URL; ?>';
var max_file_size = '<?php echo max(KANPAICLASSIC\Helper::mbytesToBytes(ini_get('upload_max_filesize')), KANPAICLASSIC\Helper::mbytesToBytes(ini_get('post_max_size'))); ?>';
</script>
<script src="<?php echo SHOP_URL; ?>/js/jquery3.min.js"></script>
<script src="<?php echo SHOP_URL; ?>/js/jquery-ui.min.js"></script>
<script src="<?php echo ADMIN_URL; ?>/js/admin.js"></script>
</body>
</html>
