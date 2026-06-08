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

// Umrechnungsfaktor Kcal ->KJoule: 4.1868, auch in admin.js joule2cal() verwendet
//
$html  = '<div id="naehrwere_zutaten">';
$html .= '   <div class="title_naehrwerte txt_tit">';
$html .= '      <a href="'.HELP_LINK.'/kapitel03nährwerte.html" target="_blank" class="help_kanpaiclassic"></a>';
$html .= '      <input type="checkbox" class="newdesign" id="naehrwerte_check" name="naehrwerte_check"'.($this->main->naehrwerte_check == 'y' ? ' checked="checked"' : '').' onchange="$(this).prop(\'checked\') ? $(\'#tabs_extra_naehrwerte\').addClass(\'active\') : $(\'#tabs_extra_naehrwerte\').removeClass(\'active\');" />';
$html .= '      <label for="naehrwerte_check"></label>';
$html .= '      Nährwerte pro 100g';
$html .= '      <div class="title_naehrwerte_right button_ci txt_but" onclick="Naehrwerte.save()" >speichern</div>';
$html .= '   </div>';

$html .= '   <div id="nw_block">';

if (defined('CONF_MODULE_MIXER_ARTIKEL')) {
   $html .= '      <div id="naehrwerte_mixer_info" style="display:'.($this->main->mixer_artikel_check == 'y' ? 'block' : 'none').';">
                      Achtung: Mixer ist aktiv, diese Nährwerte / Zutaten werden nicht verwendet,<br />sondern aus dem Mix der Kunden berechnet.
                   </div>';
}

$html .= '      <div id="naehrwerte">';
$html .= '         <div class="nw_zeile">';
$html .= '            <span class="nw_title fliesstext">'.$this->text->get('artikel', 'brennwert', 'deu').'</span>';
$html .= '            <input type="text" class="nw_kcal txt_inp" name="brennwert_cal" id="brennwert_cal" value="'.number_format(($naehrwerte->brennwert / 4.1868), 0, '', '').'" onchange="Naehrwerte.joule2cal(1);" />';
$html .= '            <span class="nw_kcal_span">kcal</span>';
$html .= '            <input type="text" class="nw_kjoule txt_inp" name="brennwert" id="brennwert" value="'.number_format($naehrwerte->brennwert, 0, '', '').'" onchange="Naehrwerte.joule2cal(0);" />';
$html .= '            <span class="nw_span">kJ</span>';
$html .= '         </div>';

$html .= '         <div class="nw_zeile">';
$html .= '            <span class="nw_title fliesstext">'.$this->text->get('artikel', 'fett', 'deu').'</span>';
$html .= '            <input type="text" class="nw_value txt_inp" name="fett" id="fett" value="'.number_format($naehrwerte->fett, 2, ',', '.').'" onchange="Royalart.nwCheck(this);" />';
$html .= '            <span class="nw_span">g</span>';
$html .= '         </div>';

$html .= '         <div class="nw_zeile">';
$html .= '            <span class="nw_title fliesstext">'.$this->text->get('artikel', 'f_saeure', 'deu').'</span>';
$html .= '            <input type="text" class="nw_value txt_inp" name="f_saeure" id="f_saeure" value="'.number_format($naehrwerte->f_saeure, 2, ',', '.').'" onchange="Royalart.nwCheck(this);" />';
$html .= '            <span class="nw_span">g</span>';
$html .= '         </div>';

$html .= '         <div class="nw_zeile">';
$html .= '            <span class="nw_title fliesstext">'.$this->text->get('artikel', 'k_hydrate', 'deu').'</span>';
$html .= '            <input type="text" class="nw_value txt_inp" name="k_hydrate" id="k_hydrate" value="'.number_format($naehrwerte->k_hydrate, 2, ',', '.').'" onchange="Royalart.nwCheck(this);" />';
$html .= '            <span class="nw_span">g</span>';
$html .= '         </div>';

$html .= '         <div class="nw_zeile">';
$html .= '            <span class="nw_title fliesstext">'.$this->text->get('artikel', 'zucker', 'deu').'</span>';
$html .= '            <input type="text" class="nw_value txt_inp" name="zucker" id="zucker" value="'.number_format($naehrwerte->zucker, 2, ',', '.').'" onchange="Royalart.nwCheck(this);" />';
$html .= '            <span class="nw_span">g</span>';
$html .= '         </div>';

$html .= '         <div class="nw_zeile">';
$html .= '            <span class="nw_title fliesstext">'.$this->text->get('artikel', 'ballast', 'deu').'</span>';
$html .= '            <input type="text" class="nw_value txt_inp" name="ballast" id="ballast" value="'.number_format($naehrwerte->ballast, 2, ',', '.').'" onchange="Royalart.nwCheck(this);" />';
$html .= '            <span class="nw_span">g</span>';
$html .= '         </div>';

$html .= '         <div class="nw_zeile">';
$html .= '            <span class="nw_title fliesstext">'.$this->text->get('artikel', 'eiweiss', 'deu').'</span>';
$html .= '            <input type="text" class="nw_value txt_inp" name="eiweiss" id="eiweiss" value="'.number_format($naehrwerte->eiweiss, 2, ',', '.').'" onchange="Royalart.nwCheck(this);" />';
$html .= '            <span class="nw_span">g</span>';
$html .= '         </div>';

$html .= '         <div class="nw_zeile">';
$html .= '            <span class="nw_title fliesstext">'.$this->text->get('artikel', 'salz', 'deu').'</span>';
$html .= '            <input type="text" class="nw_value txt_inp" name="salz" id="salz" value="'.number_format($naehrwerte->salz, 2, ',', '.').'" onchange="Royalart.nwCheck(this);" />';
$html .= '            <span class="nw_span">g</span>';
$html .= '         </div>';
$html .= '      </div>';

$html .= '      <div id="zutaten">';
$html .= '         <div class="zut_zeile">';
$html .= '            <div class="txt_bez title">Zutaten</div>';

foreach ($this->params->langs as $zlang) {
   $html .= '            <div class="zutaten_block zutaten_'.$zlang.'" style="'.($zlang !== $this->params->selected_lang ? 'display:none;' : '').'">';

   for ($i = 1; $i <= 12; $i++) {
      $html .= '            <input type="text" class="txt_inp" id="zutat_'.$zlang.'_'.$i.'" name="zutat_'.$zlang.'_'.$i.'" value="'.$zutaten[$zlang]['zutat_'.$i].'" />';
   }

   $html .= '            </div>';
}

$html .= '         </div>';
$html .= '      </div>';
$html .= '      <div id="allergiker">'.CR;
$html .= '         <div class="zut_zeile">';
$html .= '            <span class="txt_bez title">Hinweise für<br />Allergiker</span>';

foreach ($this->params->langs as $zlang) {
   $html .= '               <div class="zutaten_block zutaten_'.$zlang.'" style="'.($zlang !== $this->params->selected_lang ? 'display:none;' : '').'">';
   $html .= '                  <input type="text" class="txt_inp" id="zutat_'.$zlang.'_allergiker" name="zutat_'.$zlang.'_allergiker" value="'.$zutaten[$zlang]['zutat_allergiker'].'" />';
   $html .= '               </div>';
}

$html .= '         </div>';
$html .= '         <div class="zut_zeile">&nbsp;</div>';
$html .= '      </div>';
$html .= '   </div>';
$html .= '</div>';
