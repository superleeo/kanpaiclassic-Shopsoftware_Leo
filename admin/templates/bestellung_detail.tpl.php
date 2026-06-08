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
$menu                 = KANPAICLASSIC\Control::getMenu();
$admin_config         = $menu->loadDesign();

$laender  = KANPAICLASSIC\Control::getLaender();
//$help     = KANPAICLASSIC\Control::getHelp();
$readonly = '';
$disabled = '';

if ($data->status == 3 || $data->status == 4 || $data->status == 5 || (defined('CONF_MODULE_BESTELLZUSAMMENFASSUNG') && (isset($data->collected) && $data->collected == 'y'))) {
   $readonly = ' readonly="readonly"';
   $disabled = ' disabled="disabled"';
}

$gutschein_code   = $data->gutschein_code;
$gutschein_brutto = (float)$data->gutschein_brutto;
$gutschein_steuer = (float)$data->gutschein_steuer;
$gutschein_netto  = $gutschein_brutto - $gutschein_steuer;
$versand          = number_format($data->versand, 2, ',', '.');
$zahlart_add      = number_format($data->zahlart_add, 2, ',', '.');
$rabatt           = number_format($data->rabatt, 2, ',', '.');
$rabatt_prozent   = (float)$data->user_rabatt;
$gutschrift       = number_format($data->gutschrift + $gutschein_netto, 2, ',', '.');
$cs_adresse       = md5($data->anrede . $data->vorname . $data->nachname . $data->firma . $data->ustid . $data->adresse . $data->hausnr . $data->plz . $data->ort . $data->buland . $data->telefon . $data->staat . $data->staat2 . $data->email);
$cs_lieferung     = md5($data->lieferadresse . $data->lf_anrede . $data->lf_vorname . $data->lf_nachname . $data->lf_firma . $data->lf_postnr . $data->lf_adresse . $data->lf_hausnr . $data->lf_plz . $data->lf_ort . $data->lf_buland . $data->lf_staat . $data->lf_staat2);
$cs_bank          = md5($data->bank_inhaber . $data->bank_name . $data->bank_iban . $data->bank_bic);
$cs_artsummen     = md5($versand . $zahlart_add . $rabatt . $gutschrift . $data->gewerbe);
$cs_text          = md5($data->msg_kunde . $data->msg_admin);
$anzahlEtiketten  = $this->_getAnzEtiketten();

$zahlungsart      = $data->zahlungsart;
$zahlungstext     = KANPAICLASSIC\Helper::getZahlartText($zahlungsart);

$data_status = '';

switch ((int)$data->status) {
   case 1: $data_status = 'best_neu'; $data_text = 'neu'; break;
   case 2: $data_status = 'best_offen'; $data_text = 'bestätigt'; break;
   case 3: $data_status = 'best_bereit'; $data_text = 'bereit'; break;
   case 4: $data_status = 'best_erledigt'; $data_text = 'versendet'; break;
   case 5: $data_status = 'best_erledigt'; $data_text = 'storniert'; break;
   case 0: $data_status = 'best_neu'; $data_text = 'Einlesen'; break;
   default: $data_status = 'best_neu'; $data_text = 'Pending'; break;
}

// Bestellungen von Amazon eingelesen
if (substr($data->ebay_order, 0, 2) == 'a:') {
   $zahlungstext = 'Amazon';
}

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
</head>

<body>
<div id="page" class="admin_bg">
   <?php echo $menu->printHeader(); ?>
   <div id="menu">
      <?php echo $menu->menuData(); ?>
   </div>

   <div id="content">
      <div id="titelzeile" class="titelzeile">
         <div class='txt_tit'>
            <a class="help_kanpaiclassic" href="<?php echo HELP_LINK; ?>/bestellungen/" target="_blank"></a>
            <?php echo KANPAICLASSIC\Helper::sqlDatum($data->created); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $data->bestellnummer; ?>
         </div>
         <div class="save_button" onclick="$('input').attr('disabled', false); $('select').attr('disabled', false); $('#mode').val('speichern'); $('#form_best_details').submit();">speichern</div>
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

      <div id="bestellung_detail" class="maincontent">
         <form id="form_best_details" method="post" action="<?php echo ADMIN_URL_IDX; ?>/bestellungen/save" onsubmit="$('input').attr('disabled', false); $('select').attr('disabled', false);">
            <div class="top_blocks content_box content_box_bottom">
               <input type="hidden" id="id" name="id" value="<?php echo $data->id; ?>" />
               <input type="hidden" id="mode" name="mode" value="" />
               <input type="hidden" name="user_id" value="<?php echo $data->user_id; ?>" />
               <input type="hidden" name="cs_adresse" value="<?php echo $cs_adresse; ?>" />
               <input type="hidden" name="cs_lieferung" value="<?php echo $cs_lieferung; ?>" />
               <input type="hidden" name="cs_bank" value="<?php echo $cs_bank; ?>" />
               <input type="hidden" name="cs_text" value="<?php echo $cs_text; ?>" />
               <input type="hidden" name="cs_artsummen" value="<?php echo $cs_artsummen; ?>" />
               <input type="hidden" id="bearbeiten" name="bearbeiten" value="<?php echo ($readonly == '' ? 'y' : 'n'); ?>" />
               <input type="hidden" id="is_storno" name="is_storno" value="<?php echo ($data->status == 5 && defined('CONF_MODULE_BESTELLZUSAMMENFASSUNG') && ($data->collector == 'y' || $data->collected == 'y') ? 'y' : 'n'); ?>" />
               <input type="hidden" id="select_status" name="status" value="<?php echo $data->status; ?>" />

               <?php if (defined('CONF_MODULE_PORTAL')) { ?>
               <input type="hidden" id="haendler_id" value="<?php echo $haendler->user_id; ?>" />
               <?php } ?>

               <div class="block_buttons">
                  <?php if (!defined('CONF_MODULE_BESTELLZUSAMMENFASSUNG') || !isset($data->collected) || $data->collected == 'n') { ?>
                  <div class="buttons_left">
                     <div class="button_ci txt_but" onclick="$('#mode').val('bestaetigen'); $('#form_best_details').submit();">(1) bestätigen</div>
                     <div class="button_ci txt_but" onclick="$('#mode').val('pdf');         $('#form_best_details').submit();">(2) RE erstellen</div>
                     <div class="button_ci txt_but" onclick="$('#mode').val('pdf_senden');  $('#form_best_details').submit();">(3) versendet</div>
                  </div>
                  <?php } ?>

                  <div class="buttons_right">
                     <div id="title_status" class="<?php echo $data_status; ?>"><?php echo $data_text; ?></div>
                     <div class="list_edit fas fa-pencil-alt pointer" onclick="Bestellungen.popup(<?php echo $data->id; ?>);"></div>
                     <div class="button txt_but" onclick="$('#mode').val('storno'); $('#form_best_details').submit();">stornieren</div>

                     <?php if ($readonly != '' && !defined('CONF_MODULE_BESTELLZUSAMMENFASSUNG') || (defined('CONF_MODULE_BESTELLZUSAMMENFASSUNG') && ($data->collector == 'n' && $data->collected == 'n'))) { // Bei Bestellzusammenfassung nicht anzeigen ?>
                     <div id="button_bearbeiten" class="button txt_but" onclick="Bestellungen.bearbeiten()">bearbeiten</div>
                     <?php } ?>
<!--                     <div class="button txt_but" onclick="location.href = '<?php echo ADMIN_URL_IDX; ?>/bestellungen';">zurück</div> -->
                  </div>
                  <div class="clear"></div>

                  <?php if (!defined('CONF_MODULE_BESTELLZUSAMMENFASSUNG') || $data->collected == 'n') { ?>
                  <hr />
                  <div class="buttons_left">
                     <div class="line">
                        <span class="selectbox30">
                           <select name="printconfig" id="printconfig" >
                              <option value="1" selected="selected">Lieferschein</option>
                              <option value="2">DHL Paket &amp; Päckchen</option>
                              <option value="3">Hermes Paket</option>
                              <option value="4">DPD Paket</option>
                              <option value="5">GLS Paket</option>
                              <option value="11">Adress-Aufkleber sofort</option>
                              <option value="12">Adress-Aufkleber sammeln</option>
                              <option value="13" name="refresh">Sammel-Aufkleber (<?php echo $anzahlEtiketten; ?>) drucken</option>
                           </select>
                        </span>
                     </div>
                  </div>

                  <div class="buttons_right">
                     <div class="line">
                        <div class="button txt_but" onclick="Bestellungen.drucken(<?php echo $data->id; ?>);">drucken</div>
                        <div id="printconfig_edit" class="list_edit fas fa-pencil-alt pointer" onclick="Bestellungen.printconfig();"></div>
                     </div>
                  </div>
                  <div class="clear"></div>
                  <?php } ?>
               </div>

               <div class="block_zahlungsart">
                  <div class="line">
                     <div class="line_txt right">Zahlungsart:</div>
                     <div class="line_inp">
                        <span class="selectbox30">
                           <select name="zahlart" id="zahlart" <?php echo $disabled; ?>>
                              <?php echo KANPAICLASSIC\Helper::getZahlartOptions((int)$zahlungsart, 0, 1, 0, 0); ?>
                           </select>
                        </span>
                     </div>
                  </div>

                  <?php // Bestellung in Fremdwährung ?>
                  <?php if ((int)$data->waehrung_id != 1) { ?>
                  <p>Kunde bezahlt in <?php echo KANPAICLASSIC\Helper::waehrungText($this->params->firma['waehrung'.$data->waehrung_id], 2); ?></p>
                  <?php } ?>

                  <?php // Admin / Bestellungen: Bestellungen über andere Plattformen ?>
                  <?php if ($data->ebay_order != '') { ?>
                     <?php if (substr($data->ebay_order, 0, 2) == 'a:') { ?>
                  <div class="line">
                     <div class="line_txt right">Zahlungsart:</div>
                     <div class="line_inp"></div>
                  </div>
                     <p>Bestellt über Amazon</p>
                     <p>Order-Id: <?php echo substr($data->ebay_order, 2); ?></p>
                     <?php } else { ?>
                  <div class="line">
                     <div class="line_txt right">Zahlungsart:</div>
                     <div class="line_inp"></div>
                  </div>
                     <p>Bestellt über Ebay</p>
                     <p>Order-Id: <?php echo $data->ebay_order; ?></p>
                     <?php } ?>
                  <?php } ?>

                  <?php // Zusatzinfo Zahlarten ?>
                  <?php // Paypal / track_id / payer_email?>
                  <?php if ($data->zahlungsart == 2) { ?>
                  <div class="line">
                     <div class="line_txt right">Paypal TXN:</div>
                     <div class="line_inp" title="Paypal-Email: <?php echo $data->zahlungsinfo2; ?>"><?php echo $data->zahlungsinfo1; ?></div>
                  </div>
                  <?php } ?>

                  <?php // Sofort ?>
                  <?php if ($data->zahlungsart == 7) { ?>
                  <div class="line">
                     <div class="line_txt right">Zahlungsart:</div>
                     <div class="line_inp"></div>
                  </div>
<!--
                        <p>Transaktions-ID: <?php echo $data->zahlungsinfo1; ?></p>
-->
                  <?php } ?>

                  <?php // VR-Pay ?>
                  <?php if ($data->zahlungsart == 8) { ?>
                  <div class="line">
                     <div class="line_txt right">Zahlungsart:</div>
                     <div class="line_inp"></div>
                  </div>
<!--
                        <p>VRpay: <?php echo $data->zahlungsinfo1; ?></p>
                        <p><?php echo $data->zahlungsinfo2; ?></p>
-->
                  <?php } ?>

                  <?php // PayPal plus ?>
                  <?php if ($data->zahlungsart == 10) { ?>
                  <div class="line">
                     <div class="line_txt right">Zahlungsart:</div>
                     <div class="line_inp"></div>
                  </div>
<!--
                        <p>über<span class="paypalplus_logo">&nbsp;</span></p>
                        <p><?php echo $data->zahlungsinfo1; ?></p>
                        <p>TXN: <?php echo $data->zahlungsinfo2; ?></p>
-->
                  <?php } ?>

                  <?php // Amazon ?>
                  <?php if ($data->zahlungsart == 11) { ?>
                  <div class="line">
                     <div class="line_txt right">Zahlungsart:</div>
                     <div class="line_inp"></div>
                  </div>
<!--
                        <p><?php echo $data->zahlungsinfo1; ?></p>
                        <p><?php echo $data->zahlungsinfo2; ?></p>
-->
                  <?php } ?>

                  <?php // Twint ?>
                  <?php if ($data->zahlungsart == 12) { ?>
                  <div class="line">
                     <div class="line_txt right">Twint</div>
                     <div class="line_inp"></div>
                  </div>
<!--
                        <p>über<span class="twint_logo">&nbsp;</span></p>
-->
                  <?php } ?>

                  <?php // EasyCredit / tvk-fvk/zinsen?>
                  <?php if ($data->zahlungsart == 13) { ?>
                     <?php list($part1, $part2) = explode(' / ', $data->zahlungsinfo1); ?>
                  <div class="line">
                     <div class="line_txt right">Zahlungsart:</div>
                     <div class="line_inp"></div>
                  </div>
<!--
                        <p>Ratenkauf über<span class="easycredit_logo">&nbsp;easyCredit</span></p>
                        <p title="Ref. technisch<?php echo $part1; ?>">Referenz: <?php echo $part2; ?></p>
                        <p>Zinsen: <?php echo $data->zahlungsinfo2; ?></p>
-->
                  <?php } ?>

                  <?php // Klarna ?>
                  <?php if ($data->zahlungsart == 14) {
                     $order_id    = '';
                     $klarna_re   = '';
                     $klarna_user = $this->params->firma['klarna_user'];

                     list($merchants) = explode('_', $klarna_user);

                     if ($data->zahlungsinfo2 != '') {
                        list($order_id, $klarna_re) = explode('::', $data->zahlungsinfo2);
                        $url = 'https://'.(defined('CONF_KLARNA_SANDBOX') ? 'playground.' : '').'eu.portal.klarna.com/orders/merchants/'.$merchants.'/orders/'.$order_id;
                     ?>
                  <div class="line">
                     <div class="line_txt right">&nbsp;</div>
                     <div class="line_inp"><?php echo $data->zahlungsinfo1; ?></div>
                  </div>
                  <div class="line">
                     <div class="line_txt right">&nbsp;</div>
                     <div class="line_inp"><?php echo '<a href="'.$url.'" target="_blank">'.$klarna_re.'</a>'; ?></div>
                  </div>
                     <?php } ?>
                  <?php } ?>

                  <?php //  ?>
                  <?php if ($data->zahlungsart == 15) { ?>
                  <div class="line">
                     <div class="line_txt right">giropay/paydirekt:</div>
                     <div class="line_inp" title="Snippet-ID"><?php echo $data->zahlungsinfo1; ?></div>
                  </div>
                  <?php } ?>

                  <?php // Paypal V2 / Order_id / payer_email?>
                  <?php if ($data->zahlungsart == 18) { ?>
                  <div class="line">
                     <div class="line_txt right">Paypal V2 Order ID:</div>
                     <div class="line_inp" title="Paypal V2-Email: <?php echo $data->zahlungsinfo2; ?>"><?php echo $data->zahlungsinfo1; ?></div>
                  </div>
                  <?php } ?>

                  <?php // Mollie / Payment Id / payer_email?>
                  <?php if ($data->zahlungsart == 19) { ?>
                  <div class="line">
                     <div class="line_txt right">Mollie Payment ID:</div>
                     <div class="line_inp" title="Mollie Payer-Email: <?php echo $data->zahlungsinfo2; ?>"><?php echo $data->zahlungsinfo1; ?></div>
                  </div>
                  <?php } ?>
                  
                  <div class="line">
                     <div class="line_txt right">Zahlungseingang:</div>
                     <div class="line_inp">
                        <input type="text" name="zahlungseingang" value="<?php echo $data->zahlungdatum > 0 ? KANPAICLASSIC\Helper::sqlDatum($data->zahlungdatum) : ''; ?>" />
                     </div>
                  </div>

                  <div class="line">
                     <div class="line_txt right">Rechnungsnummer:</div>
                     <div class="line_inp">
                        <input <?php echo $readonly; ?> class="inp70 inline" type="text" name="rechnungsnummer" value="<?php echo $data->rechnungsnummer != '' ? $data->rechnungsnummer : ''; ?>" />
                     </div>
                  </div>

                  <div class="line">
                     <div class="line_txt right">Rechnungsdatum:</div>
                     <div class="line_inp">
                        <input <?php echo $readonly; ?> class="inp70 inline" type="text" name="rechnungsdatum" value="<?php echo $data->rechnungsdatum > 0 ? KANPAICLASSIC\Helper::sqlDatum($data->rechnungsdatum) : ''; ?>" />
                     </div>
                  </div>

                  <div class="line">
                     <div class="line_txt right">Lieferdatum</div>
                     <div class="line_inp">
                        <input type="text" id="lieferdatum" name="lieferdatum" value="<?php echo $data->lieferdatum > 0 ? KANPAICLASSIC\Helper::sqlDatum($data->lieferdatum) : ''; ?>" />
                     </div>
                  </div>
               </div>
               <div class="clear"></div>
            </div>

            <div class="content_box_abstand"></div>
            <div class="content_box">
               <?php // ****************** Adresse ************************************************************ ?>
               <div class="adressen">
                  <?php // ****************** Rechnungs-Adresse ************************************************************ ?>
                  <div class="adresse_rechnung">
                     <div class="line">
                        <div class="line_txt right">
                        <?php if ($data->user > 0) { ?>
                           <?php if ((int)$data->role < 11) { ?>
                              <div class="list_kunde fas fa-user pointer" onclick="Kunden.details(<?php echo $data->user_id; ?>);"></div>
                           <?php } else if ((int)$data->role > 10 && (int)$data->role < 18) { ?>
                              <div class="list_kunde_rabatt fas fa-user pointer" onclick="Kunden.details(<?php echo $data->user_id; ?>);"></div>
                           <?php } else { ?>
                              <div class="list_kunde_vip fas fa-user pointer" onclick="Kunden.details(<?php echo $data->user_id; ?>);"></div>
                           <?php } ?>
                        <?php } ?>
                        </div>
                        <div class="line_inp txt_tit ellipsis">Rechnungsanschrift <small>(zur Zeit der Bestellung)</small></div>
                     </div>

                     <div class="line">
                        <div class="line_txt right">Name</div>
                        <div class="line_inp">
                           <span class="selectbox30 pos3_1">
                              <select <?php echo $disabled; ?> name="anrede_sel" id="anrede_sel" onChange="$('#anrede').val(this.value);">
                                 <?php echo KANPAICLASSIC\Helper::getAnredeOption($data->anrede); ?>
                              </select>
                           </span>
                           <input type="hidden" name="anrede" id="anrede" value="<?php echo $data->anrede; ?>" />
                           <span class="pos3_2"><input type="text" <?php echo $readonly; ?> name="vorname" id="vorname" value="<?php echo $data->vorname; ?>" /></span>
                           <span class="pos3_3"><input type="text" <?php echo $readonly; ?> name="nachname" id="nachname" value="<?php echo $data->nachname; ?>" /></span>
                           <span class="clear"></span>
                        </div>
                     </div>

                     <div class="line right">
                        <div class="line_txt">Firma</div>
                        <div class="line_inp"><input type="text" <?php echo $readonly; ?> name="firma" id="firma" value="<?php echo $data->firma; ?>" /></div>
                     </div>

                     <div class="line">
                        <div class="line_txt right">Ust.-IdNr.</div>
                        <div class="line_inp"><input type="text" <?php echo $readonly; ?> name="ustid" id="ustid" value="<?php echo $data->ustid; ?>" /></div>
                     </div>

                     <div class="line">
                        <div class="line_txt right">Straße</div>
                        <div class="line_inp">
                           <span class="pos2_3">
                              <input type="text" <?php echo $readonly; ?> name="adresse" id="adresse" value="<?php echo $data->adresse; ?>" />
                           </span>
                           <span class="pos2_4">
                              <input type="text" <?php echo $readonly; ?> name="hausnr" id="hausnr" value="<?php echo $data->hausnr; ?>" />
                           </span>
                           <span class="clear"></span>
                        </div>
                     </div>

                     <div class="line">
                        <div class="line_txt right">PLZ / Stadt</div>
                        <div class="line_inp">
                           <span class="pos2_1">
                              <input type="text" <?php echo $readonly; ?> name="plz" id="plz" value="<?php echo $data->plz; ?>" />
                           </span>
                           <span class="pos2_2">
                              <input type="text" <?php echo $readonly; ?> name="ort" id="ort" value="<?php echo $data->ort; ?>" />
                           </span>
                           <span class="clear"></span>
                        </div>
                     </div>

                     <div class="line">
                        <div class="line_txt right">Bundesland</div>
                        <div class="line_inp"><input type="text" <?php echo $readonly; ?> name="buland" id="buland" value="<?php echo $data->buland; ?>" /></div>
                     </div>

                     <div class="line">
                        <div class="line_txt right">Land</div>
                        <div class="line_inp">
                           <span class="selectbox30 pos2_5">
                              <select <?php echo $disabled; ?> name="staat" id="staat"><?php echo $laender->getOption($data->staat); ?></select>
                           </span>
                           <span class="pos2_6" id="no_eu" style="display:none;">
                              <input type="text" <?php echo $readonly; ?> name="staat2" id="staat2" value="<?php echo $data->staat2; ?>" />
                           </span>
                           <span class="clear"></span>
                        </div>
                     </div>

                     <div class="line">
                        <div class="line_txt right">Telefon</div>
                        <div class="line_inp"><input type="text" <?php echo $readonly; ?> name="telefon" id="telefon" value="<?php echo $data->telefon; ?>" /></div>
                     </div>

                     <div class="line">
                        <div class="line_txt right">
                           <a href="mailto:<?php echo $data->email; ?>"><span class="ci_color far fas fa-envelope pointer"></span></a>
                           E-Mail
                        </div>
                        <div class="line_inp"><input type="text" <?php echo $readonly; ?> name="email" id="email" value="<?php echo $data->email; ?>" /></div>
                     </div>

                     <?php if (isset($data->ds_gvo_check) && $data->ds_gvo_check == 'y') { ?>
                     <div class="line">
                        <div class="line_txt right">&nbsp;</div>
                        <div class="line_inp dsgvo_yes">E-Mail & Telefon dürfen zum Versand weitergegeben werden.</div>
                     </div>
                     <?php } else { ?>
                     <div class="line">
                        <div class="line_txt right">&nbsp;</div>
                        <div class="line_inp dsgvo_no">E-Mail & Telefon dürfen nicht weitergegeben werden.</div>
                     </div>
                     <?php } ?>
                  </div>

                  <?php // ****************** Liefer-Adresse ************************************************************ ?>
                  <div class="adresse_lieferung">
                     <div class="line">
                        <div class="line_txt right"></div>
                        <div class="line_inp txt_tit ellipsis">Lieferanschrift <small>(zur Zeit der Bestellung)</small></div>
                     </div>

                     <div class="line">
                        <div class="line_txt right">Name</div>
                        <div class="line_inp">
                           <span class="selectbox30 pos3_1">
                              <select <?php echo $disabled; ?> name="lf_anrede_sel" id="lf_anrede_sel" onChange="$('#lf_anrede').val(this.value);">
                                 <?php echo KANPAICLASSIC\Helper::getAnredeOption($data->lf_anrede); ?>
                              </select>
                           </span>
                           <input type="hidden" name="lf_anrede" id="lf_anrede" value="<?php echo $data->lf_anrede; ?>" />
                           <span class="pos3_2"><input type="text" <?php echo $readonly; ?> name="lf_vorname" id="lf_vorname" value="<?php echo $data->lf_vorname; ?>" /></span>
                           <span class="pos3_3"><input type="text" <?php echo $readonly; ?> name="lf_nachname" id="lf_nachname" value="<?php echo $data->lf_nachname; ?>" /></span>
                           <span class="clear"></span>
                        </div>
                     </div>

                     <div class="line">
                        <div class="line_txt right">Firma</div>
                        <div class="line_inp"><input type="text" <?php echo $readonly; ?> name="lf_firma" id="lf_firma" value="<?php echo $data->lf_firma; ?>" /></div>
                     </div>

                     <div class="line">
                        <div class="line_txt right">Postnummer</div>
                        <div class="line_inp"td><input type="text" <?php echo $readonly; ?> name="lf_postnr" id="lf_postnr" value="<?php echo $data->lf_postnr; ?>" /></div>
                     </div>

                     <div class="line">
                        <div class="line_txt right">Straße</div>
                        <div class="line_inp">
                           <span class="pos2_3">
                              <input type="text" name="lf_adresse" id="lf_adresse" value="<?php echo $data->lf_adresse; ?>" <?php echo $readonly; ?> />
                           </span>
                           <span class="pos2_4">
                              <input type="text" name="lf_hausnr"  id="lf_hausnr"  value="<?php echo $data->lf_hausnr; ?>"  <?php echo $readonly; ?> />
                           </span>
                           <span class="clear"></span>
                        </div>
                     </div>

                     <div class="line">
                        <div class="line_txt right">PLZ / Stadt</div>
                        <div class="line_inp">
                           <span class="pos2_1">
                              <input type="text" <?php echo $readonly; ?> name="lf_plz" id="lf_plz" value="<?php echo $data->lf_plz; ?>" />
                           </span>
                           <span class="pos2_2">
                              <input type="text" <?php echo $readonly; ?> name="lf_ort" id="lf_ort" value="<?php echo $data->lf_ort; ?>" />
                           </span>
                           <span class="clear"></span>
                        </div>
                     </div>

                     <div class="line">
                        <div class="line_txt right">Bundesland</div>
                        <div class="line_inp"><input type="text" <?php echo $readonly; ?> name="lf_buland" id="lf_buland" value="<?php echo $data->lf_buland; ?>" /></div>
                     </div>

                     <div class="line">
                        <div class="line_txt right">Land</div>
                        <div class="line_inp">
                           <div class="selectbox30 pos2_5">
                              <select <?php echo $disabled; ?> name="lf_staat" id="lf_staat">
                                 <?php echo $laender->getOption($data->lf_staat); ?>
                              </select>
                           </div>
                           <span class="pos2_6" id="lf_no_eu" style="display:none;">
                              <input type="text" <?php echo $readonly; ?> name="lf_staat2" id="lf_staat2" value="<?php echo $data->lf_staat2; ?>" />
                           </span>
                           <span class="clear"></span>
                        </div>
                     </div>

                     <?php if ($data->dhl_send_check == 'y') { ?>
                     <div class="line margin10_c">
                        <div class="line_txt right">Tracking-Code</div>
                        <div class="line_inp"><?php echo $data->dhl_intraship; ?></div>
                     </div>
                     <?php } ?>

                     <?php // ****************** Kontodaten ************************************************************ ?>
                     <?php if ($zahlungsart == 3) { ?>
                     <div class="line">
                        <div class="line_txt right"></div>
                        <div class="line_inp txt_bez">Bankverbindung bei Einzugsermächtigung</div>
                     </div>

                     <div class="line">
                        <div class="line_txt right">Inhaber / Bank</div>
                        <div class="line_inp">
                           <span class="pos2_5">
                              <input type="text" <?php echo $readonly; ?> name="bank_inhaber" id="bank_inhaber" value="<?php echo $data->bank_inhaber; ?>" />
                           </span>
                           <span class="pos2_6">
                              <input type="text" <?php echo $readonly; ?> name="bank_name" id="bank_name" value="<?php echo $data->bank_name; ?>" />
                           </span>
                           <span class="clear"></span>
                        </div>
                     </div>

                     <div class="line">
                        <div class="line_txt right">IBAN /BIC</div>
                        <div class="line_inp">
                           <span class="pos2_5">
                              <input type="text" <?php echo $readonly; ?> name="bank_iban" id="bank_iban" value="<?php echo $data->bank_iban; ?>" />
                           </span>
                           <span class="pos2_6">
                              <input type="text" <?php echo $readonly; ?> name="bank_bic" id="bank_bic" value="<?php echo $data->bank_bic; ?>" />
                           </span>
                           <span class="clear"></span>
                        </div>
                     </div>
                     <?php } ?>

                     <?php // ****************** Kreditkartendaten ************************************************************ ?>
                     <?php if ($zahlungsart == 9) {
                        $pruef = '';
                        $datum = '';

                        if (stripos($data->bank_bic, ':::') > 1) {
                           list($dat, $pruef) = explode(':::', $data->bank_bic);
                           $datum = substr($data->bank_bic, 5, 2).'/'.substr($data->bank_bic, 0, 4);
                        }
                        else {
                           $datum = substr($data->bank_bic, 8, 2).'.'.substr($data->bank_bic, 5, 2).'.'.substr($data->bank_bic, 0, 4);
                        }
                     ?>
                     <div class="line">
                        <div class="line_txt right"></div>
                        <div class="line_inp txt_bez">Kreditkartendaten bei Einzugsermächtigung</div>
                     </div>

                     <div class="line">
                        <div class="line_txt right">Karte / Inhaber</div>
                        <div class="line_inp">
                           <span class="pos2_5">
                              <input type="text" <?php echo $readonly; ?> name="bank_name" id="bank_name" value="<?php echo $data->bank_name; ?>" />
                           </span>
                           <span class="pos2_6">
                              <input type="text" <?php echo $readonly; ?> name="bank_inhaber" id="bank_inhaber" value="<?php echo $data->bank_inhaber; ?>" />
                           </span>
                           <span class="clear"></span>
                        </div>
                     </div>

                     <div class="line">
                        <div class="line_txt right">Karte / Inhaber</div>
                        <div class="line_inp">
                           <span class="pos2_5">
                              <input type="text" <?php echo $readonly; ?> name="bank_iban" id="bank_iban" value="<?php echo trim(KANPAICLASSIC\Helper::checkString($data->bank_iban)); ?>" />
                           </span>
                           <span class="pos2_6">
                              <input type="text" <?php echo $readonly; ?> name="bank_bic" id="bank_bic" value="<?php echo $datum; ?>" />
                           </span>
                           <span class="clear"></span>
                        </div>
                     </div>

                     <?php if ($pruef != '') { ?>
                     <div class="line">
                        <div class="line_txt right">Prüfnummer</div>
                        <div class="line_inp">
                           <span class="pos2_5">
                              <input type="text" <?php echo $readonly; ?> name="pruf" id="pruef" value="<?php echo $pruef; ?>" />
                           </span>
                           <span class="pos2_6">
                           </span>
                           <span class="clear"></span>
                        </div>
                     </div>
                     <?php } ?>
                  <?php } ?>
                  </div>
                  <div class="clear"></div>
               </div>

               <hr />

               <?php // ****************** Artikel ************************************************************ ?>
               <div id="artikel_list">
                  <?php include 'templates/best_detail_artikel.tpl.php'; ?>
                  <?php echo $html_artikel; ?>
                  <div class="clear"></div>
               </div>

               <?php // ****************** Nachrichten ************************************************************ ?>
               <div class="block_messages">
                  <div class="messages_kunde">
                     <div class="nachricht txt_bez">Nachricht auf die Bestellung / Rechnung / Versandmail<?php echo ($data->lang_kunde != $this->params->firma['default_lang'] ? ' Kunde spricht '.strtoupper($data->lang_kunde) : ''); ?></div>
                     <textarea <?php echo $readonly; ?> name="msg_admin" id="msg_admin"><?php echo $data->msg_admin; ?></textarea>
                  </div>
                  <div class="messages_admin">
                     <div class="nachricht txt_bez">Nachricht vom Kunden</div>
                     <textarea <?php echo $readonly; ?> name="msg_kunde" id="msg_kunde"><?php echo $data->msg_kunde; ?></textarea>
                  </div>
                  <div class="clear"></div>
               </div>
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
var site_width    = <?php echo $admin_config->admdsgn_width; ?>;
</script>
<script src="<?php echo SHOP_URL; ?>/js/jquery3.min.js"></script>
<script src="<?php echo SHOP_URL; ?>/js/jquery-ui.min.js"></script>
<script src="<?php echo ADMIN_URL; ?>/js/admin.js"></script>
<?php echo (isset($_SESSION['pdf_script']) ? $_SESSION['pdf_script'] : ''); ?>
<?php unset($_SESSION['pdf_script']); ?>
</body>
</html>
