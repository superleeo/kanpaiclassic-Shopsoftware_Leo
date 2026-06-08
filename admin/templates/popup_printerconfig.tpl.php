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

$html  = '';
$haendler_suffix    = '';

if (defined('CONF_MODULE_PORTAL') && isset($_SESSION['haendler_id'])) {
   $haendler_suffix = '_'.$_SESSION['haendler_id'];
}

$print_dhl_left        = (int)\KANPAICLASSIC\Helper::getData('print_dhl_left'.$haendler_suffix, 0);
$print_dhl_top         = (int)\KANPAICLASSIC\Helper::getData('print_dhl_top'.$haendler_suffix, 0);

$print_hermes_left     = (int)\KANPAICLASSIC\Helper::getData('print_hermes_left'.$haendler_suffix, 0);
$print_hermes_top      = (int)\KANPAICLASSIC\Helper::getData('print_hermes_top'.$haendler_suffix, 0);

$print_dpd_left        = (int)\KANPAICLASSIC\Helper::getData('print_dpd_left'.$haendler_suffix, 0);
$print_dpd_top         = (int)\KANPAICLASSIC\Helper::getData('print_dpd_top'.$haendler_suffix, 0);
$print_dpd_land        = \KANPAICLASSIC\Helper::getData('print_dpd_land'.$haendler_suffix, 'DE');
$print_dpd_klasse      = \KANPAICLASSIC\Helper::getData('print_dpd_klasse'.$haendler_suffix, 'S');

$print_gls_left        = (int)\KANPAICLASSIC\Helper::getData('print_gls_left'.$haendler_suffix, 0);
$print_gls_top         = (int)\KANPAICLASSIC\Helper::getData('print_gls_top'.$haendler_suffix, 0);
$print_gls_klasse      = \KANPAICLASSIC\Helper::getData('print_gls_klasse'.$haendler_suffix, 'S');
$print_gls_inhalt      = \KANPAICLASSIC\Helper::getData('print_gls_inhalt'.$haendler_suffix, '');

$print_etikett_left    = (int)\KANPAICLASSIC\Helper::getData('print_etikett_left'.$haendler_suffix, 0);
$print_etikett_top     = (int)\KANPAICLASSIC\Helper::getData('print_etikett_top'.$haendler_suffix, 0);
$print_etikett_dirup   = \KANPAICLASSIC\Helper::getData('print_etikett_dirup'.$haendler_suffix, 'n');
$print_etikett_x       = (int)\KANPAICLASSIC\Helper::getData('print_etikett_x'.$haendler_suffix, 1);
$print_etikett_y       = (int)\KANPAICLASSIC\Helper::getData('print_etikett_y'.$haendler_suffix, 1);
$print_etikett_spalten = (int)\KANPAICLASSIC\Helper::getData('print_etikett_spalten'.$haendler_suffix, 3);
$print_etikett_zeilen  = (int)\KANPAICLASSIC\Helper::getData('print_etikett_zeilen'.$haendler_suffix, 8);
$print_etikett_offsetx = (int)\KANPAICLASSIC\Helper::getData('print_etikett_offsetx'.$haendler_suffix, 0);
$print_etikett_offsety = (int)\KANPAICLASSIC\Helper::getData('print_etikett_offsety'.$haendler_suffix, 0);

$html .= '<div id="printerconfig">';
$html .= '<h1>Druck-Optimierung</h1>';
$html .= '<div class="print_block_left">';
$html .= '   <div class="print_block_title txt_bez">DHL</div>';
$html .= '   <div class="dhl_block">';
$html .= '      <div class="inp_top"><input type="text" class="txt_inp" id="print_dhl_top" name="print_dhl_top" value="'.$print_dhl_top.'" /> mm</div>';
$html .= '      <div class="inp_left"><input type="text" class="txt_inp" id="print_dhl_left" name="print_dhl_left" value="'.$print_dhl_left.'" /></div>';
$html .= '   </div>';

$html .= '   <div class="print_block_title txt_bez">Hermes</div>';
$html .= '   <div class="hermes_block">';
$html .= '      <div class="inp_top"><input type="text" class="txt_inp" id="print_hermes_top" name="print_hermes_top" value="'.$print_hermes_top.'" /> mm</div>';
$html .= '      <div class="inp_left"><input type="text" class="txt_inp" id="print_hermes_left" name="print_hermes_left" value="'.$print_hermes_left.'" /></div>';
$html .= '   </div>';
$html .= '</div>';

$html .= '<div class="print_block_left">';
$html .= '   <div class="print_block_title txt_bez">DPD</div>';
$html .= '   <div class="dpd_block">';
$html .= '      <div class="inp_top"><input type="text" class="txt_inp" id="print_dpd_top" name="print_dpd_top" value="'.$print_dpd_top.'" /> mm</div>';
$html .= '      <div class="inp_left"><input type="text" class="txt_inp" id="print_dpd_left" name="print_dpd_left" value="'.$print_dpd_left.'" /></div>';
$html .= '      <div class="dpd_land">Land <input type="text" class="txt_inp" id="print_dpd_land" name="print_dpd_land" value="'.$print_dpd_land.'" /></div>';
$html .= '      <div class="dpd_select">Paketklasse';
$html .= '         <span class="selectbox">';
$html .= '            <select class="txt_inp" id="print_dpd_klasse" name="print_dpd_klasse">';
$html .= '               <option value="S"'.($print_dpd_klasse == 'S' ? ' selected="selected"' : '').'">S</option>';
$html .= '               <option value="M"'.($print_dpd_klasse == 'M' ? ' selected="selected"' : '').'">M</option>';
$html .= '               <option value="L"'.($print_dpd_klasse == 'L' ? ' selected="selected"' : '').'">L</option>';
$html .= '               <option value="XL"'.($print_dpd_klasse == 'XL' ? ' selected="selected"' : '').'">XL</option>';
$html .= '            </select>';
$html .= '         </span>';
$html .= '      </div>';
$html .= '   </div>';

$html .= '   <div class="print_block_title txt_bez">GLS</div>';
$html .= '   <div class="gls_block">';
$html .= '      <div class="inp_top"><input type="text" class="txt_inp" id="print_gls_top" name="print_gls_top" value="'.$print_gls_top.'" /> mm</div>';
$html .= '      <div class="inp_left"><input type="text" class="txt_inp" id="print_gls_left" name="print_gls_left" value="'.$print_gls_left.'" /></div>';
$html .= '      <div class="gls_select">Paketklasse';
$html .= '         <span class="selectbox">';
$html .= '            <select class="txt_inp" id="print_gls_klasse" name="print_gls_klasse">';
$html .= '               <option value="S"'.($print_gls_klasse == 'S' ? ' selected="selected"' : '').'">S</option>';
$html .= '               <option value="M"'.($print_gls_klasse == 'M' ? ' selected="selected"' : '').'">M</option>';
$html .= '               <option value="L"'.($print_gls_klasse == 'L' ? ' selected="selected"' : '').'">L</option>';
$html .= '               <option value="XL"'.($print_gls_klasse == 'XL' ? ' selected="selected"' : '').'">XL</option>';
$html .= '            </select>';
$html .= '         </span>';
$html .= '      </div>';
$html .= '      <div class="gls_inhalt"><input type="text" class="txt_inp" id="print_gls_inhalt" name="print_gls_inhalt" value="'.$print_gls_inhalt.'" placeholder="Inhalt" /></div>';
$html .= '   </div>';
$html .= '</div>';

$html .= '<div class="print_block_right">';
$html .= '   <div class="print_block_title txt_bez">(Sammel-) Etiketten</div>';
$html .= '   <div class="etikett_block">';
$html .= '      <div class="inp_top"><input type="text" class="txt_inp" id="print_etikett_top" name="print_etikett_top" value="'.$print_etikett_top.'" /> mm</div>';
$html .= '      <div class="inp_left"><input type="text" class="txt_inp" id="print_etikett_left" name="print_etikett_left" value="'.$print_etikett_left.'" /></div>';
//$html .= '      <div class="etikett_dirup">';
//$html .= '         Druck von oben nach unten';
//$html .= '         <input type="checkbox" id="print_etikett_dirup" name="print_etikett_dirup"'.($print_etikett_dirup == 'y' ? ' checked="checked"' : '').' onchange="Bestellungen.printPreview();" />';
//$html .= '      </div>';
$html .= '      <div class="etikett_dirup">';
$html .= '         <input type="checkbox" class="newdesign" id="print_etikett_dirup" name="print_etikett_dirup"'.($print_etikett_dirup == 'y' ? ' checked="checked"' : '').' onchange="Bestellungen.printPreview();" />';
$html .= '         <label for="print_etikett_dirup">Druck von oben nach unten</label>';
$html .= '      </div>';
$html .= '      <div id="print_preview"></div>';
$html .= '   </div>';

$html .= '   <div class="etikett_extra">';
$html .= '      <div class="extra_line">';
$html .= '         <div class="etikett_text1">Anzahl</div>';
$html .= '         <div class="etikett_spalten">Spalten <input type="text" class="etikett_inp txt_inp" id="print_etikett_spalten" name="print_etikett_spalten" value="'.$print_etikett_spalten.'" onchange="Bestellungen.printPreview();" /></div>';
$html .= '         <div class="etikett_zeilen">Reihen <input type="text" class="etikett_inp txt_inp" id="print_etikett_zeilen" name="print_etikett_zeilen" value="'.$print_etikett_zeilen.'" onchange="Bestellungen.printPreview();" /></div>';
$html .= '      </div>';
$html .= '      <div class="extra_line">';
$html .= '         <div class="etikett_text2">Zwischenräume</div>';
$html .= '         <div class="etikett_offsetx">x <input type="text" class="etikett_inp txt_inp" id="print_etikett_offsetx" name="print_etikett_offsetx" value="'.$print_etikett_offsetx.'" /></div>';
$html .= '         <div class="etikett_offsety">y <input type="text" class="etikett_inp txt_inp" id="print_etikett_offsety" name="print_etikett_offsety" value="'.$print_etikett_offsety.'" /></div>';
$html .= '         <input type="hidden" id="print_etikett_x" name="print_etikett_x" value="'.$print_etikett_x.'" />';
$html .= '         <input type="hidden" id="print_etikett_y" name="print_etikett_y" value="'.$print_etikett_y.'" />';
$html .= '      </div>';
$html .= '   </div>';
$html .= '</div>';
$html .= '<div class="clear"></div>';

$html .= '<div class="buttonzeile">';
$html .= '   <div class="button_left button txt_but" onclick="Multibox.close();">abbrechen</div>';
$html .= '   <div class="button_right button_ci txt_but" onclick="Bestellungen.savePrintconfig();">speichern</div>';
$html .= '</div>';
$html .= '<div class="clear"></div>';
$html .= '</div>';
