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
$background     = 0;
$sandbox        = false;

if ((defined('CONF_MODULE_TWINT')       && defined('CONF_TWINT_SANDBOX')) ||
    (defined('CONF_PAYPAL_SANDBOX')) ||
    (defined('CONF_MODULE_PAYPALV2')    && defined('CONF_PAYPALV2_SANDBOX')) ||
    (defined('CONF_MODULE_PAYPALPLUS')  && defined('CONF_PAYPALPLUS_SANDBOX')) ||
    (defined('CONF_MODULE_SOFORT')      && defined('CONF_SOFORT_SANDBOX')) ||
    (defined('CONF_MODULE_VRPAY')       && defined('CONF_VRPAY_SANDBOX')) ||
    (defined('CONF_MODULE_EASYCREDIT')  && defined('CONF_EASYCREDIT_SANDBOX')) ||
    (defined('CONF_MODULE_AMAZON')      && defined('CONF_MODULE_AMAZON_SANDBOX')) ||
    (defined('CONF_MODULE_KLARNA')      && defined('CONF_KLARNA_SANDBOX')) ||
    (defined('CONF_MODULE_MOLLIE')    && defined('CONF_MOLLIE_SANDBOX')) ||
    (defined('CONF_MODULE_PAYDIRECT')   && defined('CONF_MODULE_PAYDIREKT_SANDBOX')) ||
    (defined('CONF_MODULE_POSTFINANCE') && defined('CONF_MODULE_POSTFINANCE_SANDBOX'))) {
   $sandbox = true;
}
?><!DOCTYPE html>
<html lang="de">
<head>
<title>Kanpai Classic <?php echo (!defined('CONF_MODULE_PORTAL') ? 'Shopsystem' : 'Shopportal'); ?> - Administration - Zahlungsart</title>
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
         <div class='txt_tit'><a class="help_kanpaiclassic" href="<?php echo HELP_LINK; ?>/einstellungen/zahlungsart/" target="_blank"></a>Zahlungsart</div>
         <div class="save_button" onclick="forms.zahlungsartform.submit()">speichern</div>
      </div>

      <div id="zahlungsart" class="maincontent">
         <form method="post" id="zahlungsartform" action="<?php echo ADMIN_URL_IDX; ?>/zahlungsart/update">
            <div class="inner_block content_box content_box_bottom">
               <?php // ********** Bitte wählen / - ********** ?>
               <div class="zahlart_line">
                  <div class="zahlart_title ellipsis">
                     <?php if ($sandbox) { ?>
                     <span class="fas"></span>
                     <?php } ?>
                     <input type="checkbox" class="newdesign" id="za_waehlen_check" name="za_waehlen_check"<?php echo ($this->shop->za_waehlen_check == 'y' ? ' checked="checked"' : ''); ?> />
                     <label for="za_waehlen_check" class="txt_bez ellipsis">"bitte wählen"-Zeile</label>
                  </div>
                  <div class="clear"></div>
               </div>

               <?php // ********** Bar / 6 ********** ?>
               <div class="zahlart_line" data-check_id="6">
                  <div class="zahlart_title ellipsis" data-check_id="0">
                     <?php if ($sandbox) { ?>
                     <span class="fas"></span>
                     <?php } ?>
                     <input type="checkbox" class="newdesign" id="bar_check" name="bar_check"<?php echo ($this->shop->bar_check == 'y' ? ' checked="checked"' : ''); ?> />
                     <label for="bar_check" class="txt_bez ellipsis">Bar</label>
                  </div>
                  <div class="gebuehr">
                     <span class="gebuehr_txt">Skonto ( % )</span>
                     <input type="text" class="gebuehr_inp" name="bar_preis" value="<?php echo number_format($this->shop->bar_preis, '2', ',', '.'); ?>" />
                  </div>
                  <div class="clear"></div>
               </div>

               <?php // ********** Überweisung / 1 ********** ?>
               <div class="zahlart_line" data-check_id="1">
                  <div class="zahlart_title ellipsis">
                     <?php if ($sandbox) { ?>
                     <span class="fas"></span>
                     <?php } ?>
                     <input type="checkbox" class="newdesign" id="vorkasse_check" name="vorkasse_check"<?php echo ($this->shop->vorkasse_check == 'y' ? ' checked="checked"' : ''); ?> />
                     <label for="vorkasse_check" class="txt_bez ellipsis">Überweisung</label>
                  </div>
                  <div class="gebuehr">
                     <span class="gebuehr_txt">Skonto ( % )</span>
                     <input class="gebuehr_inp" type="text" name="vorkasse_preis" value="<?php echo number_format($this->shop->vorkasse_preis, '2', ',', '.'); ?>" />
                  </div>
                  <div class="config">
                     <div><div class="button txt_but"><a href="<?php echo ADMIN_URL_IDX; ?>/shopinhaber">Kontodaten</a></div></div>
                  </div>
                  <div class="clear"></div>
               </div>

               <?php // ********** Rechnung / 5 ********** ?>
               <div class="zahlart_line" data-check_id="5">
                  <div class="zahlart_title ellipsis">
                     <?php if ($sandbox) { ?>
                     <span class="fas"></span>
                     <?php } ?>
                     <input type="checkbox" class="newdesign" id="rechnung_check" name="rechnung_check"<?php echo ($this->shop->rechnung_check == 'y' ? ' checked="checked"' : ''); ?> />
                     <label for="rechnung_check" class="txt_bez ellipsis">auf Rechnung</label>
                  </div>
                  <div class="gebuehr">
                     <span class="gebuehr_txt">Skonto ( % )</span>
                     <input class="gebuehr_inp" type="text" name="rechnung_preis" value="<?php echo number_format($this->shop->rechnung_preis, '2', ',', '.'); ?>" />
                  </div>
                  <div class="config">
                     <div class="label">
                        <input type="checkbox" class="newdesign" id="rechnung_check_user" name="rechnung_check_user"<?php echo ($this->shop->rechnung_check_user == 'y' ? ' checked="checked"' : ''); ?> />
                        <label for="rechnung_check_user">kundenbezogen</label>&nbsp;<span class="help ci_color" title="im Kundenaccount aktivieren"></span>
                     </div>
                     <div class="label">
                        <input type="checkbox" class="newdesign" id="rechnung_check_country" name="rechnung_check_country"<?php echo ($this->shop->rechnung_check_country == 'y' ? ' checked="checked"' : ''); ?> />
                        <label for="rechnung_check_country" class="">nur in <?php echo $this->versandart_land; ?> anbieten</label>
                     </div>
                  </div>
                  <div class="clear"></div>
               </div>

               <?php // ********** Nachnahme / 4 ********** ?>
               <div class="zahlart_line" data-check_id="4">
                   <div class="zahlart_title ellipsis">
                      <?php if ($sandbox) { ?>
                      <span class="fas"></span>
                      <?php } ?>
                     <input type="checkbox" class="newdesign" id="nachnahme_check" name="nachnahme_check"<?php echo ($this->shop->nachnahme_check == 'y' ? ' checked="checked"' : ''); ?> />
                     <label for="nachnahme_check" class="txt_bez ellipsis">Nachnahme</label>
                  </div>
                  <div class="gebuehr">
                     <span class="gebuehr_txt">Gebühr ( netto )</span>
                     <input class="gebuehr_inp" type="text" name="nachnahme_preis" value="<?php echo number_format($this->shop->nachnahme_preis, '2', ',', '.'); ?>" />
                  </div>
                  <div class="config">
                     <div class="label">
                        <input type="checkbox" class="newdesign" id="nachnahme_check_user" name="nachnahme_check_user"<?php echo ($this->shop->nachnahme_check_user == 'y' ? ' checked="checkd"' : ''); ?> />
                        <label for="nachnahme_check_user">nur Stammkunden</label>
                     </div>
                     <div class="label">
                        <input type="checkbox" class="newdesign" id="nachnahme_check_country" name="nachnahme_check_country"<?php echo ($this->shop->nachnahme_check_country == 'y' ? ' checked="checked"' : ''); ?> />
                        <label for="nachnahme_check_country">nur in <?php echo $this->versandart_land; ?> anbieten</label>
                     </div>
                  </div>
                  <div class="clear"></div>
               </div>

               <?php // ********** Twint / 12 ********** ?>
               <?php if (defined('CONF_MODULE_TWINT')) { ?>
               <div class="zahlart_line" data-check_id="12">
                  <div class="zahlart_title ellipsis">
                     <?php if ($sandbox) { ?>
                     <span class="fas<?php echo (defined('CONF_TWINT_SANDBOX') ? ' fa-parachute-box ' : ''); ?>" title="Sandbox aktiv"></span>
                     <?php } ?>
                     <input type="checkbox" class="newdesign" id="twint_check" name="twint_check"<?php echo ($this->shop->twint_check == 'y' ? ' checked="checked"' : ''); ?> />
                     <label for="twint_check" class="txt_bez ellipsis">TWINT PostFinance</label>
                  </div>
                  <div class="gebuehr">
                     <span class="gebuehr_txt">Skonto ( % )</span>
                     <input class="gebuehr_inp" type="text" name="twint_preis" value="<?php echo number_format($this->shop->twint_preis, '2', ',', '.'); ?>" />
                  </div>
                  <div class="config">
                     <div><span class="pos1">UUID</span>&nbsp;<input type="password" class="txt_inp pw_auge" name="twint_uuid" value="<?php echo $this->shop->twint_uuid; ?>" /></div>
                     <div><span class="pos2"></span><div class="button" onclick="Zahlart.twintCert()">Zertifikat hochladen</div></div>
                  </div>
                  <div class="clear"></div>
               </div>
               <?php } ?>

               <?php // ********** WIR / 16 ********** ?>
               <?php if (defined('CONF_MODULE_WIR')) { ?>
               <div class="zahlart_line" data-check_id="16">
                  <div class="zahlart_title ellipsis">
                     <?php if ($sandbox) { ?>
                     <span class="fas"></span>
                     <?php } ?>
                     <input type="checkbox" class="newdesign" id="wir_check" name="wir_check"<?php echo ($this->shop->wir_check == 'y' ? ' checked="checked"' : ''); ?> />
                     <label for="wir_check" class="txt_bez ellipsis">WIR</label>
                  </div>
                  <div class="gebuehr"></div>
               </div>
               <?php } ?>

               <?php // ********** Paypal / 2 ********** ?>
               <div class="zahlart_line" data-check_id="2">
                  <div class="zahlart_title ellipsis">
                     <?php if ($sandbox) { ?>
                     <span class="fas<?php echo (defined('CONF_PAYPAL_SANDBOX') ? ' fa-parachute-box ' : ''); ?>" title="Sandbox aktiv"></span>
                     <?php } ?>
                     <input type="checkbox" class="newdesign" id="paypal_check" name="paypal_check"<?php echo ($this->shop->paypal_check == 'y' ? ' checked="checked"' : ''); ?> />
                     <label for="paypal_check" class="txt_bez ellipsis">PayPal (V1)</label>
                  </div>
                  <div class="gebuehr">
                     <span class="gebuehr_txt">Skonto ( % )</span>
                     <input class="gebuehr_inp" type="text" name="paypal_preis" value="<?php echo number_format($this->shop->paypal_preis, '2', ',', '.'); ?>" />
                  </div>
                  <div class="config">
                     <div><span class="pos1">E-Mail</span>&nbsp<input type="password" class="pw_auge" name="paypal_mail" value="<?php echo $this->shop->paypal_mail; ?>" /></div>
                     <input type="hidden" name="pp_test_user" value="<?php echo $this->shop->pp_test_user; ?>" />
                  </div>
                  <div class="clear"></div>
               </div>

               <?php // ********** PaypalV2 / 18 ********** ?>
               <?php if (defined('CONF_MODULE_PAYPALV2')) { ?>
               <div class="zahlart_line" data-check_id="18">
                  <div class="zahlart_title ellipsis">
                     <?php if ($sandbox) { ?>
                     <span class="fas<?php echo (defined('CONF_PAYPALV2_SANDBOX') ? ' fa-parachute-box ' : ''); ?>" title="Sandbox aktiv"></span>
                     <?php } ?>
                     <input type="checkbox" class="newdesign" id="paypalv2_check" name="paypalv2_check"<?php echo ($this->shop->paypalv2_check == 'y' ? ' checked="checked"' : ''); ?> />
                     <label for="paypalv2_check" class="txt_bez ellipsis">PayPal (V2)</label>
                  </div>
                  <div class="gebuehr">
                     <span class="gebuehr_txt">Skonto ( % )</span>&nbsp;<input class="gebuehr_inp" type="text" name="paypalv2_preis" value="<?php echo number_format($this->shop->paypalv2_preis, '2', ',', '.'); ?>" />
                  </div>
                  <div class="config">
                     <div><span class="pos1">Client-Id</span>&nbsp;<input type="password" class="pw_auge" name="ppv2_client_id" value="<?php echo $this->shop->ppv2_client_id; ?>" /></div>
                     <div><span class="pos2">Secret</span>&nbsp;<input type="password" class="pw_auge" name="ppv2_client_secret" value="<?php echo $this->shop->ppv2_client_secret; ?>" /></div>
                     <input type="checkbox" class="newdesign" id="ppv2_check_button" name="ppv2_check_button"<?php echo ($this->shop->ppv2_check_button == 'y' ? ' checked="checked"' : ''); ?> /><label for="ppv2_check_button" class="">Button im WK</label>
                  </div>
                  <div class="clear"></div>
               </div>
               <?php } ?>


               <?php // ********** PaypalPlus / 10 ********** ?>
               <?php if (defined('CONF_MODULE_PAYPALPLUS')) { ?>
               <div class="zahlart_line" data-check_id="10">
                  <div class="zahlart_title ellipsis">
                     <?php if ($sandbox) { ?>
                     <span class="fas<?php echo (defined('CONF_PAYPALPLUS_SANDBOX') ? ' fa-parachute-box ' : ''); ?>" title="Sandbox aktiv"></span>
                     <?php } ?>
                     <input type="checkbox" class="newdesign" id="paypalplus_check" name="paypalplus_check"<?php echo ($this->shop->paypalplus_check == 'y' ? ' checked="checked"' : ''); ?> />
                     <label for="paypalplus_check" class="txt_bez ellipsis">PayPal PLUS</label>
                  </div>
                  <div class="gebuehr">
                     <span class="gebuehr_txt">Skonto ( % )</span>&nbsp;<input class="gebuehr_inp" type="text" name="paypalplus_preis" value="<?php echo number_format($this->shop->paypalplus_preis, '2', ',', '.'); ?>" />
                  </div>
                  <div class="config">
                     <div><span class="pos1">Client-Id</span>&nbsp;<input type="password" class="pw_auge" name="ppp_client_id" value="<?php echo $this->shop->ppp_client_id; ?>" /></div>
                     <div><span class="pos2">Secret</span>&nbsp;<input type="password" class="pw_auge" name="ppp_client_secret" value="<?php echo $this->shop->ppp_client_secret; ?>" /></div>
                  </div>
                  <div class="clear"></div>
               </div>
               <?php } ?>

               <?php // ********** Sofort / 7 ********** ?>
               <div class="zahlart_line" data-check_id="7">
                  <div class="zahlart_title ellipsis">
                     <?php if ($sandbox) { ?>
                     <span class="fas<?php echo (defined('CONF_SOFORT_SANDBOX') ? ' fa-parachute-box ' : ''); ?>" title="Sandbox aktiv"></span>
                     <?php } ?>
                     <input type="checkbox" class="newdesign" id="sofort_check" name="sofort_check"<?php echo ($this->shop->sofort_check == 'y' ? ' checked="checked"' : ''); ?> />
                     <label for="sofort_check" class="txt_bez ellipsis">SOFORT</label>
                  </div>
                  <div class="gebuehr">
                     <span class="gebuehr_txt">Skonto ( % )</span>
                     <input class="gebuehr_inp" type="text" name="sofort_preis" value="<?php echo number_format($this->shop->sofort_preis, '2', ',', '.'); ?>" />
                  </div>
                  <div class="config">
                     <div><span class="pos1">Schlüssel:</span>&nbsp;<input type="password" class="inp_pp pw_auge" name="sofort_key" value="<?php echo $this->shop->sofort_key; ?>" placeholder="Kunden-Id:Projekt-Id:API-Key" /></div>
                  </div>
                  <div class="clear"></div>
               </div>

               <?php // ********** VRpay / 8 ********** ?>
               <?php if (defined('CONF_VRPAY')) { ?>
               <div class="zahlart_line" data-check_id="8">
                  <div class="zahlart_title ellipsis">
                     <?php if ($sandbox) { ?>
                     <span class="fas<?php echo (defined('CONF_MODULE_VRPAY_SANDBOX') ? ' fa-parachute-box ' : ''); ?>" title="Sandbox aktiv"></span>
                     <?php } ?>
                     <input type="checkbox" class="newdesign" id="vrpay_check" name="vrpay_check"<?php echo ($this->shop->vrpay_check == 'y' ? ' checked="checked"' : ''); ?> />
                     <label for="vrpay_check" class="txt_bez ellipsis">VRpay (Master, Visa...)</label>
                  </div>
                  <div class="gebuehr">
                     <span class="gebuehr_txt">Skonto ( % )</span>
                     <input class="gebuehr_inp" type="text" name="vrpay_preis" value="<?php echo number_format($this->shop->vrpay_preis, '2', ',', '.'); ?>" />
                  </div>
                  <div class="config">
                     <div><span class="pos1">URL: https://</span>&nbsp;<input type="text" class="inp_pp" name="vrpay_url" value="<?php echo $this->shop->vrpay_url; ?>" style="margin-right:40px;" /></div>
                     <div><span class="pos2">Partner:</span>&nbsp;<input type="password" class="pw_auge" name="vrpay_number" value="<?php echo $this->shop->vrpay_number; ?>" /></div>
                     <div class="clear"></div>
                     <div><span class="pos2">Pass:</span>&nbsp<input type="password" class="pw_auge" name="vrpay_pass" value="<?php echo $this->shop->vrpay_pass; ?>" /></div>
                  </div>
                  <div class="clear"></div>
               </div>
               <?php } ?>

               <?php // ********** Lastschrift / 3 ********** ?>
               <div class="zahlart_line easy" data-check_id="3">
                  <div class="zahlart_title ellipsis">
                     <?php if ($sandbox) { ?>
                     <span class="fas"></span>
                     <?php } ?>
                     <input type="checkbox" class="newdesign" id="lastschrift_check" name="lastschrift_check"<?php echo ($this->shop->lastschrift_check == 'y' ? ' checked="checked"' : ''); ?> />
                     <label for="lastschrift_check" class="txt_bez ellipsis">SEPA-Lastschrift</label>
                  </div>
                  <div class="gebuehr">
                     <span class="gebuehr_txt">Skonto ( % )</span>
                     <input class="gebuehr_inp" type="text" name="lastschrift_preis" value="<?php echo number_format($this->shop->lastschrift_preis, '2', ',', '.'); ?>" />
                  </div>
                  <div class="config">
                     <div class="label">
                        <input type="checkbox" class="newdesign" id="lastschrift_pdf_check" name="lastschrift_pdf_check"<?php echo ($this->shop->lastschrift_pdf_check == 'y' ? ' checked="checked"' : ''); ?> />
                        <label for="lastschrift_pdf_check" class="">ohne PDF zur Unterschrift</label>
                     </div>
                     <div class="label">
                        <input type="checkbox" class="newdesign" id="lastschrift_check_user" name="lastschrift_check_user"<?php echo ($this->shop->lastschrift_check_user == 'y' ? ' checked="checked"' : ''); ?> />
                        <label for="lastschrift_check_user" class="">nur Stammkunden</label>
                     </div>
                     <div class="label">
                        <input type="checkbox" class="newdesign" id="lastschrift_check_country" name="lastschrift_check_country"<?php echo ($this->shop->lastschrift_check_country == 'y' ? ' checked="checked"' : ''); ?> />
                        <label for="lastschrift_check_country" class="">nur in <?php echo $this->versandart_land; ?> anbieten</label>
                     </div>
                  </div>
                  <div class="clear"></div>
               </div>

               <?php // ********** KK-Einzug /  ********** ?>
               <?php if (defined('CONF_MODULE_KKEINZUG')) { ?>
               <div class="zahlart_line" data-check_id="9">
                  <div class="zahlart_title ellipsis">
                     <?php if ($sandbox) { ?>
                     <span class="fas"></span>
                     <?php } ?>
                     <input type="checkbox" class="newdesign" id="kklastschrift_check" name="kklastschrift_check"<?php echo ($this->shop->kklastschrift_check == 'y' ? ' checked="checked"' : ''); ?> />
                     <label for="kklastschrift_check" class="txt_bez ellipsis">Einzug Kreditkarten</label>
                  </div>
                  <div class="gebuehr">
                     <span class="gebuehr_txt">Skonto ( % )</span>
                     <input class="gebuehr_inp" type="text" name="kklastschrift_preis" value="<?php echo number_format($this->shop->kklastschrift_preis, '2', ',', '.'); ?>" />
                  </div>
                  <div class="clear"></div>
               </div>
               <?php } ?>

               <?php // ********** EasyCredit / 13 ********** ?>
               <?php if (defined('CONF_MODULE_EASYCREDIT')) { ?>
               <div class="zahlart_line" data-check_id="13">
                  <div class="zahlart_title ellipsis">
                     <?php if ($sandbox) { ?>
                     <span class="fas<?php echo (defined('CONF_EASYCREDIT_SANDBOX') ? ' fa-parachute-box ' : ''); ?>" title="Sandbox aktiv"></span>
                     <?php } ?>
                     <input type="checkbox" class="newdesign" id="easycredit_check" name="easycredit_check"<?php echo ($this->shop->easycredit_check == 'y' ? ' checked="checked"' : ''); ?> />
                     <label for="easycredit_check" class="txt_bez ellipsis">EasyCredit</label>
                  </div>
                  <div class="gebuehr">
                     <span class="gebuehr_txt">Skonto ( % )</span>
                     <input class="gebuehr_inp" type="text" name="easycredit_preis" value="<?php echo number_format($this->shop->easycredit_preis, '2', ',', '.'); ?>" />
                  </div>
                  <div class="config">
                     <div><span class="pos1">WebShop-ID:</span>&nbsp;<input type="password" class="inp pw_auge" name="easycredit_api_id" value="<?php echo $this->shop->easycredit_api_id; ?>" placeholder="Webshop-ID" /></div>
                     <div><span class="pos2">Passwort:</span>&nbsp;<input type="password" class="inp pw_auge" name="easycredit_token" value="<?php echo $this->shop->easycredit_token; ?>" placeholder="Passwort" /></div>
                  </div>
                  <div class="clear"></div>
               </div>
               <?php } ?>

               <?php // ********** Amazon / 11 ********** ?>
               <?php if (defined('CONF_MODULE_AMAZON')) { ?>
               <div class="zahlart_line" data-check_id="11">
                  <div class="zahlart_title ellipsis">
                     <?php if ($sandbox) { ?>
                     <span class="fas<?php echo (defined('CONF_MODULE_AMAZON_SANDBOX') ? ' fa-parachute-box ' : ''); ?>" title="Sandbox aktiv"></span>
                     <?php } ?>
                     <input type="checkbox" class="newdesign" id="amazon_check" name="amazon_check"<?php echo ($this->shop->amazon_check == 'y' ? ' checked="checked"' : ''); ?> />
                     <label for="amazon_check" class="txt_bez ellipsis">Amazon Payments</label>
                  </div>
                  <div class="gebuehr">
                     <span class="gebuehr_txt">Skonto ( % )</span>
                     <input class="gebuehr_inp" type="text" name="amazon_preis" value="<?php echo number_format($this->shop->amazon_preis, '2', ',', '.'); ?>" />
                  </div>
                  <div class="config">
                     <div><span class="pos1">Händler_ID</span>&nbsp;<input type="password" class="inp pw_auge" name="amazon_seller" value="<?php echo $this->shop->amazon_seller; ?>" /></div>
                     <div><span class="pos2">Client-ID</span>&nbsp;<input type="password" class="inp pw_auge" name="amazon_client" value="<?php echo $this->shop->amazon_client; ?>" /></div>
                     <div><span class="pos3">Access_key</span>&nbsp;<input type="password" class="inp pw_auge" name="amazon_access" value="<?php echo $this->shop->amazon_access; ?>" /></div>
                     <div><span class="pos4">Secret_key</span>&nbsp;<input type="password" class="inp pw_auge" name="amazon_secret" value="<?php echo $this->shop->amazon_secret; ?>" /></div>
                     <div><span class="pos5">Händler&#x2011;URL:&nbsp;<?php echo SHOP_URL_IDX; ?>/amazonNotify&nbsp;(bei&nbsp;Amazon&nbsp;reinkopieren)</span></div>
                  </div>
                  <div class="clear"></div>
               </div>
               <?php } ?>

               <?php // ********** Klarna / 14 ********** ?>
               <?php if (defined('CONF_MODULE_KLARNA')) { ?>
               <div class="zahlart_line" data-check_id="14">
                  <div class="zahlart_title ellipsis">
                     <?php if ($sandbox) { ?>
                     <span class="fas<?php echo (defined('CONF_KLARNA_SANDBOX') ? ' fa-parachute-box ' : ''); ?>" title="Sandbox aktiv"></span>
                     <?php } ?>
                     <input type="checkbox" class="newdesign" id="klarna_check" name="klarna_check"<?php echo ($this->shop->klarna_check == 'y' ? ' checked="checked"' : ''); ?> />
                     <label for="klarna_check" class="txt_bez ellipsis">Klarna</label>
                  </div>
                  <div class="gebuehr">
                     <span class="gebuehr_txt">Skonto ( % )</span>
                     <input class="gebuehr_inp" type="text" name="klarna_preis" value="<?php echo number_format($this->shop->klarna_preis, '2', ',', '.'); ?>" />
                  </div>
                  <div class="config">
                     <?php if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') { ?>
                     <div><span class="pos1">Username:</span>&nbsp;<input type="password" class="pw_auge" name="klarna_user" value="<?php echo $this->shop->klarna_user; ?>" placeholder="" /></div>
                     <div><span class="pos2">Passwort:</span>&nbsp;<input type="password" class="pw_auge" name="klarna_pass" value="<?php echo $this->shop->klarna_pass; ?>" placeholder="" /></div>
                     <?php } else { ?>
                     <input type="hidden" name="klarna_user" value="<?php echo $this->shop->klarna_user; ?>" />
                     <input type="hidden" name="klarna_pass" value="<?php echo $this->shop->klarna_pass; ?>" />
                     <span class="pos5">Ohne SSL-Zertifikat nicht möglich</span>
                     <?php } ?>
                  </div>
                  <div class="clear"></div>
               </div>
               <?php } ?>

               <?php // ********** Mollie / 19 ********** ?>
               <?php if (defined('CONF_MODULE_MOLLIE')) { ?>
               <div class="zahlart_line" data-check_id="19">
                  <div class="zahlart_title ellipsis">
                     <?php if ($sandbox) { ?>
                     <span class="fas<?php echo (defined('CONF_MOLLIE_SANDBOX') ? ' fa-parachute-box ' : ''); ?>" title="Sandbox aktiv"></span>
                     <?php } ?>
                     <input type="checkbox" class="newdesign" id="mollie_check" name="mollie_check"<?php echo ($this->shop->mollie_check == 'y' ? ' checked="checked"' : ''); ?> />
                     <label for="mollie_check" class="txt_bez ellipsis">Mollie</label>
                  </div>
                  <div class="gebuehr">
                     <span class="gebuehr_txt">Skonto ( % )</span>&nbsp;<input class="gebuehr_inp" type="text" name="mollie_preis" value="<?php echo number_format($this->shop->mollie_preis, '2', ',', '.'); ?>" />
                  </div>
                  <div class="config">
                     <?php if (defined('CONF_MOLLIE_SANDBOX')){ ?>
                     <div><span class="pos1">Test API Key</span>&nbsp;<input type="password" class="pw_auge" name="mollie_test_key" value="<?php echo $this->shop->mollie_test_key; ?>" /></div>
                     <?php }else{ ?> 
                     <div><span class="pos2">Live API Key</span>&nbsp;<input type="password" class="pw_auge" name="mollie_live_key" value="<?php echo $this->shop->mollie_live_key; ?>" /></div>
                     <?php } ?>
                  </div>
                  <div class="clear"></div>
               </div>
               <?php } ?>

               <?php // ********** giropay/paydirekt / 15 ********** ?>
               <?php if (defined('CONF_MODULE_PAYDIREKT')) { ?>
               <div class="zahlart_line" data-check_id="15">
                  <div class="zahlart_title ellipsis">
                     <?php if ($sandbox) { ?>
                     <span class="fas<?php echo (defined('CONF_MODULE_PAYDIREKT_SANDBOX') ? ' fa-parachute-box ' : ''); ?>" title="Sandbox aktiv"></span>
                     <?php } ?>
                     <input type="checkbox" class="newdesign" id="paydirekt_check" name="paydirekt_check"<?php echo ($this->shop->paydirekt_check == 'y' ? ' checked="checked"' : ''); ?> />
                     <label for="paydirekt_check" class="txt_bez ellipsis">giropay/paydirekt</label>
                  </div>
                  <div class="gebuehr">
                     <span class="gebuehr_txt">Skonto ( % )</span>
                     <input class="gebuehr_inp" type="text" name="paydirekt_preis" value="<?php echo number_format($this->shop->paydirekt_preis, '2', ',', '.'); ?>" />
                  </div>
                  <div class="config">
                     <div><span class="pos1">API-Key </span>&nbsp;<input type="password" class="inp pw_auge" name="paydirekt_key" value="<?php echo $this->shop->paydirekt_key; ?>" placeholder="" /></div>
                     <div><span class="pos2">Secret-Key </span>&nbsp;<input type="password" class="inp pw_auge" name="paydirekt_secret" value="<?php echo $this->shop->paydirekt_secret; ?>" placeholder="" /></div>
                  </div>
                  <div class="clear"></div>
               </div>
               <?php } ?>

               <?php // ********** Postfinance / 17 ********** ?>
               <?php if (defined('CONF_MODULE_POSTFINANCE')) { ?>
               <div class="zahlart_line" data-check_id="17">
                  <div class="zahlart_title ellipsis">
                     <?php if ($sandbox) { ?>
                     <span class="fas<?php echo (defined('CONF_MODULE_POSTFINANCE_SANDBOX') ? ' fa-parachute-box ' : ''); ?>" title="Sandbox aktiv"></span>
                     <?php } ?>
                     <input type="checkbox" class="newdesign" id="postfinance_check" name="postfinance_check"<?php echo ($this->shop->postfinance_check == 'y' ? ' checked="checked"' : ''); ?> />
                     <label for="postfinance_check" class="txt_bez ellipsis">Postfinance</label>
                  </div>
                  <div class="gebuehr">
                     <span class="gebuehr_txt">Skonto ( % )</span>
                     <input class="gebuehr_inp" type="text" name="postfinance_preis" value="<?php echo number_format($this->shop->postfinance_preis, '2', ',', '.'); ?>" />
                  </div>
                  <div class="config">
                     <div><span class="pos1">PSPID</span>&nbsp;<input type="password" class="pw_auge" name="postfinance_pspid" value="<?php echo $this->shop->postfinance_pspid; ?>" placeholder="" /></div>
                     <div><span class="pos2">SHA-IN</span>&nbsp;<input type="password" class="pw_auge" name="postfinance_hash_in" value="<?php echo $this->shop->postfinance_hash_in; ?>" placeholder="" /></div>
                  </div>
                  <div class="clear"></div>
               </div>
               <?php } ?>

               <div class="za_edit_auto">
                  <div class="za_edit_text txt_bez">
                     <span class="za_edit fas fa-pencil-alt pointer" onclick="Zahlart.popup();"></span>&nbsp;&nbsp;Zahlungsart-Texte
                  </div>
                  <div class="za_automatik">
                     <input type="checkbox" class="newdesign" id="za_automatik" name="za_automatik"<?php echo (KANPAICLASSIC\Helper::getData('za_automatik', 'n') == 'y' ? ' checked="checked"' : ''); ?> />
                     <label for="za_automatik">automatische Rechnungserstellung</label>&nbsp;<span class="help ci_color" title="Betrifft Paypal, PaypalPlus, Klarna, Amazon usw."></span>
                  </div>
                  <div class="clear"></div>
               </div>
               <div class="rechnung_server">
                  <div class="za_edit_text txt_bez">                  </div>
                  <div class="za_automatik">
                     <input type="checkbox" class="newdesign" id="rechnung_server" name="rechnung_server"<?php echo (KANPAICLASSIC\Helper::getData('rechnung_server', 'n') == 'y' ? ' checked="checked"' : ''); ?> />
                     <label for="rechnung_server">Rechnungen auf Server ablegen</label>&nbsp;<span class="help ci_color" title="/downloads/rechnungen"></span>
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
</body>
</html>
