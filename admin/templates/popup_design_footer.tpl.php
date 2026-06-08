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

$html .= '      <form id="footer_icons_form">'.CR;
$html .= '         <h2 class="txt_tit">Versand- & Zahlungsart-Icons</h2>'.CR;
$html .= '         <div class="">&nbsp;</div>'.CR;
$html .= '         <div class="icons_left">'.CR;
$html .= '            <div>'.CR;
$html .= '               <input type="checkbox" class="newdesign" id="footer_dhl" name="footer_dhl"'.($this->params->firma['footer_dhl'] == 'y' ? ' checked="checked"' : '').' />'.CR;
$html .= '               <label for="footer_dhl">DHL</label>'.CR;
$html .= '            </div>'.CR;
$html .= '            <div>'.CR;
$html .= '               <input type="checkbox" class="newdesign" id="footer_dpd" name="footer_dpd"'.($this->params->firma['footer_dpd'] == 'y' ? ' checked="checked"' : '').' />'.CR;
$html .= '               <label for="footer_dpd">DPD</label>'.CR;
$html .= '            </div>'.CR;
$html .= '            <div>'.CR;
$html .= '               <input type="checkbox" class="newdesign" id="footer_hermes" name="footer_hermes"'.($this->params->firma['footer_hermes'] == 'y' ? ' checked="checked"' : '').' />'.CR;
$html .= '               <label for="footer_hermes">Hermes</label>'.CR;
$html .= '            </div>'.CR;
$html .= '            <div>'.CR;
$html .= '               <input type="checkbox" class="newdesign" id="footer_gls" name="footer_gls"'.($this->params->firma['footer_gls'] == 'y' ? ' checked="checked"' : '').' />'.CR;
$html .= '               <label for="footer_gls">GLS</label>'.CR;
$html .= '            </div>'.CR;
$html .= '            <div>'.CR;
$html .= '               <input type="checkbox" class="newdesign" id="footer_ups" name="footer_ups"'.($this->params->firma['footer_ups'] == 'y' ? ' checked="checked"' : '').' />'.CR;
$html .= '               <label for="footer_ups">UPS</label>'.CR;
$html .= '            </div>'.CR;
$html .= '            <div>'.CR;
$html .= '               <input type="checkbox" class="newdesign" id="footer_post" name="footer_post"'.($this->params->firma['footer_post'] == 'y' ? ' checked="checked"' : '').' />'.CR;
$html .= '               <label for="footer_post">Deutsche Post</label>'.CR;
$html .= '            </div>'.CR;
$html .= '            <div>&nbsp;</div>'.CR;

$html .= '            <div>'.CR;
$html .= '               <input type="checkbox" class="newdesign" id="footer_ssl" name="footer_ssl"'.($this->params->firma['footer_ssl'] == 'y' ? ' checked="checked"' : '').' />'.CR;
$html .= '               <label for="footer_ssl">SSL</label>'.CR;
$html .= '            </div>'.CR;
$html .= '            <div class="clear"></div>'.CR;
$html .= '         </div>'.CR;

$html .= '         <div class="icons_right">'.CR;
$html .= '            <div>'.CR;
$html .= '               <input type="checkbox" class="newdesign" id="footer_bar" name="footer_bar"'.($this->params->firma['footer_bar'] == 'y' ? ' checked="checked"' : '').' />'.CR;
$html .= '               <label for="footer_bar">Bar bei Abholung</label>'.CR;
$html .= '            </div>'.CR;
$html .= '            <div>'.CR;
$html .= '               <input type="checkbox" class="newdesign" id="footer_ueberweisung" name="footer_ueberweisung"'.($this->params->firma['footer_ueberweisung'] == 'y' ? ' checked="checked"' : '').' />'.CR;
$html .= '               <label for="footer_ueberweisung">Überweisung</label>'.CR;
$html .= '            </div>'.CR;
$html .= '            <div>'.CR;
$html .= '               <input type="checkbox" class="newdesign" id="footer_rechnung" name="footer_rechnung"'.($this->params->firma['footer_rechnung'] == 'y' ? ' checked="checked"' : '').' />'.CR;
$html .= '               <label for="footer_rechnung">per Rechnung</label>'.CR;
$html .= '            </div>'.CR;
$html .= '            <div>'.CR;
$html .= '               <input type="checkbox" class="newdesign" id="footer_nachnahme" name="footer_nachnahme"'.($this->params->firma['footer_nachnahme'] == 'y' ? ' checked="checked"' : '').' />'.CR;
$html .= '               <label for="footer_nachnahme">Nachnahme</label>'.CR;
$html .= '            </div>'.CR;
$html .= '            <div>'.CR;
$html .= '               <input type="checkbox" class="newdesign" id="footer_paypal" name="footer_paypal"'.($this->params->firma['footer_paypal'] == 'y' ? ' checked="checked"' : '').' />'.CR;
$html .= '               <label for="footer_paypal">PayPal</label>'.CR;
$html .= '            </div>'.CR;

if (defined('CONF_MODULE_PAYPALPLUS')) {
   $html .= '            <div>'.CR;
   $html .= '               <input type="checkbox" class="newdesign" id="footer_paypalplus" name="footer_paypalplus"'.($this->params->firma['footer_paypalplus'] == 'y' ? ' checked="checked"' : '').' />'.CR;
   $html .= '               <label for="footer_paypalplus">PayPalPLUS</label>'.CR;
   $html .= '            </div>'.CR;
}

$html .= '            <div>'.CR;
$html .= '               <input type="checkbox" class="newdesign" id="footer_visa" name="footer_visa"'.($this->params->firma['footer_visa'] == 'y' ? ' checked="checked"' : '').' />'.CR;
$html .= '               <label for="footer_visa">VISA / MasterCard</label>'.CR;
$html .= '            </div>'.CR;

$html .= '            <div>'.CR;
$html .= '               <input type="checkbox" class="newdesign" id="footer_sofort" name="footer_sofort"'.($this->params->firma['footer_sofort'] == 'y' ? ' checked="checked"' : '').' />'.CR;
$html .= '               <label for="footer_sofort">Sofort</label>'.CR;
$html .= '            </div>'.CR;

if (defined('CONF_MODULE_KLARNA')) {
   $html .= '            <div>'.CR;
   $html .= '               <input type="checkbox" class="newdesign" id="footer_klarna" name="footer_klarna"'.($this->params->firma['footer_klarna'] == 'y' ? ' checked="checked"' : '').' />'.CR;
   $html .= '               <label for="footer_klarna">Klarna</label>'.CR;
   $html .= '            </div>'.CR;
}

if (defined('CONF_MODULE_AMAZON')) {
   $html .= '            <div>'.CR;
   $html .= '               <input type="checkbox" class="newdesign" id="footer_amazon" name="footer_amazon"'.($this->params->firma['footer_amazon'] == 'y' ? ' checked="checked"' : '').' />'.CR;
   $html .= '               <label for="footer_amazon">AmazonPayment</label>'.CR;
   $html .= '            </div>'.CR;
}

if (defined('CONF_MODULE_EASYCREDIT')) {
   $html .= '            <div>'.CR;
   $html .= '               <input type="checkbox" class="newdesign" id="footer_easycredit" name="footer_easycredit"'.($this->params->firma['footer_easycredit'] == 'y' ? ' checked="checked"' : '').' />'.CR;
   $html .= '               <label for="footer_easycredit">easyCredit</label>'.CR;
   $html .= '            </div>'.CR;
//   $html .= '            <div>'.CR;
//   $html .= '               <input type="checkbox" class="newdesign" id="footer_ratenkauf" name="footer_ratenkauf"'.($this->params->firma['footer_ratenkauf'] == 'y' ? ' checked="checked"' : '').' />'.CR;
//   $html .= '               <label for="footer_ratenkauf">Ratenkauf by easyCredit</label>'.CR;
//   $html .= '            </div>'.CR;
}

if (defined('CONF_MODULE_PAYDIREKT')) {
   $html .= '            <div>'.CR;
   $html .= '               <input type="checkbox" class="newdesign" id="footer_paydirekt" name="footer_paydirekt"'.($this->params->firma['footer_paydirekt'] == 'y' ? ' checked="checked"' : '').' />'.CR;
   $html .= '               <label for="footer_paydirekt">giropay/paydirekt</label>'.CR;
   $html .= '            </div>'.CR;
}

if (defined('CONF_MODULE_POSTFINANCE')) {
   $html .= '            <div>'.CR;
   $html .= '               <input type="checkbox" class="newdesign" id="footer_postfinance" name="footer_postfinance"'.($this->params->firma['footer_postfinance'] == 'y' ? ' checked="checked"' : '').' />'.CR;
   $html .= '               <label for="footer_postfinance">Postfinance</label>'.CR;
   $html .= '            </div>'.CR;
}

if (defined('CONF_MODULE_TWINT') || defined('CONF_MODULE_POSTFINANCE')) {
   $html .= '            <div>'.CR;
   $html .= '               <input type="checkbox" class="newdesign" id="footer_twint" name="footer_twint"'.($this->params->firma['footer_twint'] == 'y' ? ' checked="checked"' : '').' />'.CR;
   $html .= '               <label for="footer_twint">Twint</label>'.CR;
   $html .= '            </div>'.CR;
}

// if (defined('CONF_MODULE_SWISSPAY')) {
//if (defined('CONF_MODULE_POSTFINANCE')) {
//   $html .= '            <div>'.CR;
//   $html .= '               <input type="checkbox" class="newdesign" id="footer_swisspay" name="footer_swisspay"'.($this->params->firma['footer_swisspay'] == 'y' ? ' checked="checked"' : '').' />'.CR;
//   $html .= '               <label for="footer_swisspay">Swisspay</label>'.CR;
//   $html .= '            </div>'.CR;
//}

if (defined('CONF_MODULE_WIR')) {
   $html .= '            <div>'.CR;
   $html .= '               <input type="checkbox" class="newdesign" id="footer_wir" name="footer_wir"'.($this->params->firma['footer_wir'] == 'y' ? ' checked="checked"' : '').' />'.CR;
   $html .= '               <label for="footer_wir">WIR</label>'.CR;
   $html .= '            </div>'.CR;
}

$html .= '         </div>'.CR;
$html .= '         <div class="clear"></div>'.CR;

$html .= '         <div class="icons_block">'.CR;
$html .= '            <div>'.CR;
$html .= '               <input type="radio" class="newdesign" id="footer_farbe1" name="footer_farbe" value="antrazit"'.($this->params->firma['footer_farbe'] == 'antrazit' ? ' checked="checked"' : '').' />'.CR;
$html .= '               <label for="footer_farbe1">Icons antrazit</label>'.CR;
$html .= '            </div>'.CR;
$html .= '         </div>'.CR;

$html .= '         <div class="icons_block">'.CR;
$html .= '            <div>'.CR;
$html .= '               <input type="radio" class="newdesign" id="footer_farbe2" name="footer_farbe" value="weiss"'.($this->params->firma['footer_farbe'] == 'weiss' ? ' checked="checked"' : '').' />'.CR;
$html .= '               <label for="footer_farbe2">Icons weiß</label>'.CR;
$html .= '            </div>'.CR;
$html .= '         </div>'.CR;

$html .= '         <div class="icons_block">'.CR;
$html .= '            <div>'.CR;
$html .= '               <input type="radio" class="newdesign" id="footer_farbe3" name="footer_farbe" value="bunt"'.($this->params->firma['footer_farbe'] == 'bunt' ? ' checked="checked"' : '').' />'.CR;
$html .= '               <label for="footer_farbe3">Icons kinderbunt'.CR;
$html .= '            </div>'.CR;
$html .= '         </div>'.CR;
$html .= '         <div class="clear"></div>'.CR;
$html .= '      </form>'.CR;

$html .= '      <div class="buttonzeile">'.CR;
$html .= '         <div class="button button_left txt_but" onclick="Multibox.close();">abbrechen</div>'.CR;
$html .= '         <div class="button_ci button_right txt_but" onclick="Design.saveFooter();">speichern</div>'.CR;
$html .= '         <div class="clear"></div>'.CR;
$html .= '      </div>'.CR;

