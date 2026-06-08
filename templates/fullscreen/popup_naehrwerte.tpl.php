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

// Umrechnungsfaktor Kcal ->KJoule: 4.1868, auch in admin.js joule2cal() verwendet
//


$naehrwerte = $article_data['naehrwerte'];
$zutat      = '';
$zutaten    = [];
$allergiker = '';
$mix        = (isset($mixer_sum) ? true : false);
$mix_sum    = ($mix ? ' mix_sum' : '');

if (isset($article_data['zutaten']['shop']) && is_array($article_data['zutaten']['shop'])) {
   foreach($article_data['zutaten']['shop'] as $v) {
      if ($v->value != '') {
         if ($v->title == 'zutat_allergiker') {
            $allergiker = $v->value;
         }

         else {
            $zutaten[] = $v->value;
         }
      }
   }
}

$html  = '<div id="naehrwere_zutaten" class="content">';
$html .= '   <div id="naehrwerte">';
$html .= '      <div class="nw_head ueberschrift text_max">';

if ($mix) {
   $html .= '         <span class="nw_title_h'.$mix_sum.'">'.$this->text->get('mixer', 'mixer_nw').'</span>';
}
else {
   $html .= '         <span class="nw_title_h'.$mix_sum.'">'.$this->text->get('artikel', 'naehrwerte').'</span>';
}

$html .= '         <div class="deine_werte_h'.$mix_sum.' text_normal">';
$html .= '            <span class="nw_value">'.$this->text->get('artikel', 'portion').'&nbsp;<input type="text" id="nw_menge" value="100" onkeyup="naehrwerteChange();" /></span>';
$html .= '            <span class="nw_span">&nbsp;g</span>';
$html .= '         </div>';
$html .= '         <div class="unsere_werte_h'.$mix_sum.' text_normal">';
$html .= '            <span class="nw_value">'.$this->text->get('article', 'pro').' 100</span>';
$html .= '            <span class="nw_span">&nbsp;g</span>';
$html .= '         </div>';
$html .= '      </div>';

$html .= '      <div class="nw_zeile fliesstext text_klein">';
$html .= '         <span class="nw_title fliesstext'.$mix_sum.'">'.$this->text->get('artikel', 'brennwert').'</span>';
$html .= '         <div class="deine_werte'.$mix_sum.'">';
$html .= '            <span class="nw_value">';
$html .= '               <span class="nw_value2" id="brennwert_cal_c">'.round(((float)$naehrwerte->brennwert / 4.1868), 0).'</span>';
$html .= '               <span class="nw_span1">&nbsp;kcal</span> / ';
$html .= '               <span class="nw_value1" id="brennwert_joule_c">'.round((float)$naehrwerte->brennwert, 0).'</span>';
$html .= '               <span class="nw_span1">&nbsp;kJ</span>';
$html .= '            </span>';
$html .= '         </div>';
$html .= '         <div class="unsere_werte'.$mix_sum.'">';
$html .= '            <span class="nw_value_c">';
$html .= '               <span class="nw_value2_c" id="brennwert_cal">'.round(((float)$naehrwerte->brennwert / 4.1868), 0).'</span>';
$html .= '               <span class="nw_span2">&nbsp;kcal</span> / ';
$html .= '               <span class="nw_value1_c" id="brennwert_joule">'.round((float)$naehrwerte->brennwert, 0).'</span>';
$html .= '               <span class="nw_span2">&nbsp;kJ</span>';
$html .= '            </span>';
$html .= '         </div>';
$html .= '      </div>';

$html .= '      <div class="nw_zeile fliesstext text_klein">';
$html .= '         <span class="nw_title'.$mix_sum.'">'.$this->text->get('artikel', 'fett').'</span>';
$html .= '         <div class="deine_werte'.$mix_sum.'">';
$html .= '            <span class="nw_value" id="nw_fett_c">'.number_format((float)$naehrwerte->fett, 2, ',', '.').'</span>';
$html .= '            <span class="nw_span">&nbsp;g</span>';
$html .= '         </div>';
$html .= '         <div class="unsere_werte'.$mix_sum.'">';
$html .= '            <span class="nw_value_c" id="nw_fett">'.number_format((float)$naehrwerte->fett, 2, ',', '.').'</span>';
$html .= '            <span class="nw_span_c">&nbsp;g</span>';
$html .= '         </div>';
$html .= '      </div>';

$html .= '      <div class="nw_zeile fliesstext text_klein">';
$html .= '         <span class="nw_title fliesstext'.$mix_sum.'">'.$this->text->get('artikel', 'f_saeure').'</span>';
$html .= '         <div class="deine_werte'.$mix_sum.'">';
$html .= '            <span class="nw_value fliesstext" id="nw_f_saeure_c">'.number_format((float)$naehrwerte->f_saeure, 2, ',', '.').'</span>';
$html .= '            <span class="nw_span">&nbsp;g</span>';
$html .= '         </div>';
$html .= '         <div class="unsere_werte'.$mix_sum.'">';
$html .= '            <span class="nw_value_c fliesstext" id="nw_f_saeure">'.number_format((float)$naehrwerte->f_saeure, 2, ',', '.').'</span>';
$html .= '            <span class="nw_span_c">&nbsp;g</span>';
$html .= '         </div>';
$html .= '      </div>';

$html .= '      <div class="nw_zeile fliesstext text_klein">';
$html .= '         <span class="nw_title fliesstext'.$mix_sum.'">'.$this->text->get('artikel', 'k_hydrate').'</span>';
$html .= '         <div class="deine_werte'.$mix_sum.'">';
$html .= '            <span class="nw_value fliesstext" id="nw_k_hydrate_c">'.number_format((float)$naehrwerte->k_hydrate, 2, ',', '.').'</span>';
$html .= '            <span class="nw_span">&nbsp;g</span>';
$html .= '         </div>';
$html .= '         <div class="unsere_werte'.$mix_sum.'">';
$html .= '            <span class="nw_value_c fliesstext" id="nw_k_hydrate">'.number_format((float)$naehrwerte->k_hydrate, 2, ',', '.').'</span>';
$html .= '            <span class="nw_span_c">&nbsp;g</span>';
$html .= '         </div>';
$html .= '      </div>';

$html .= '      <div class="nw_zeile fliesstext text_klein">';
$html .= '         <span class="nw_title'.$mix_sum.'">'.$this->text->get('artikel', 'zucker').'</span>';
$html .= '         <div class="deine_werte'.$mix_sum.'">';
$html .= '            <span class="nw_value" id="nw_zucker_c">'.number_format((float)$naehrwerte->zucker, 2, ',', '.').'</span>';
$html .= '            <span class="nw_span">&nbsp;g</span>';
$html .= '         </div>';
$html .= '         <div class="unsere_werte'.$mix_sum.'">';
$html .= '            <span class="nw_value_c" id="nw_zucker">'.number_format((float)$naehrwerte->zucker, 2, ',', '.').'</span>';
$html .= '            <span class="nw_span_c">&nbsp;g</span>';
$html .= '         </div>';
$html .= '      </div>';

$html .= '      <div class="nw_zeile fliesstext text_klein">';
$html .= '         <span class="nw_title'.$mix_sum.'">'.$this->text->get('artikel', 'ballast').'</span>';
$html .= '         <div class="deine_werte'.$mix_sum.'">';
$html .= '            <span class="nw_value" id="nw_ballast_c">'.number_format((float)$naehrwerte->ballast, 2, ',', '.').'</span>';
$html .= '            <span class="nw_span">&nbsp;g</span>';
$html .= '         </div>';
$html .= '         <div class="unsere_werte'.$mix_sum.'">';
$html .= '            <span class="nw_value_c" id="nw_ballast">'.number_format((float)$naehrwerte->ballast, 2, ',', '.').'</span>';
$html .= '            <span class="nw_span_c">&nbsp;g</span>';
$html .= '         </div>';
$html .= '      </div>';

$html .= '      <div class="nw_zeile fliesstext text_klein">';
$html .= '         <span class="nw_title'.$mix_sum.'">'.$this->text->get('artikel', 'eiweiss').'</span>';
$html .= '         <div class="deine_werte'.$mix_sum.'">';
$html .= '            <span class="nw_value" id="nw_eiweiss_c">'.number_format((float)$naehrwerte->eiweiss, 2, ',', '.').'</span>';
$html .= '            <span class="nw_span">&nbsp;g</span>';
$html .= '         </div>';
$html .= '         <div class="unsere_werte'.$mix_sum.'">';
$html .= '            <span class="nw_value_c" id="nw_eiweiss">'.number_format((float)$naehrwerte->eiweiss, 2, ',', '.').'</span>';
$html .= '            <span class="nw_span_c">&nbsp;g</span>';
$html .= '         </div>';
$html .= '      </div>';

$html .= '      <div class="nw_zeile fliesstext text_klein">';
$html .= '         <span class="nw_title'.$mix_sum.'">'.$this->text->get('artikel', 'salz').'</span>';
$html .= '         <div class="deine_werte'.$mix_sum.'">';
$html .= '            <span class="nw_value" id="nw_salz_c">'.number_format((float)$naehrwerte->salz, 2, ',', '.').'</span>';
$html .= '            <span class="nw_span">&nbsp;g</span>';
$html .= '         </div>';
$html .= '         <div class="unsere_werte'.$mix_sum.'">';
$html .= '            <span class="nw_value_c" id="nw_salz">'.number_format((float)$naehrwerte->salz, 2, ',', '.').'</span>';
$html .= '            <span class="nw_span_c">&nbsp;g</span>';
$html .= '      </div>';
$html .= '   </div>';

if (!empty($zutaten) && count($zutaten) > 0) {
   foreach ($zutaten as $z) {
      $zutat .= $z.', ';
   }

   $zutat = substr($zutat, 0, -2);

   $html .= '   <div id="zutaten">';
   $html .= '      <div class="zut_head ueberschrift text_max">'.$this->text->get('artikel', 'zutaten').'</div>';
   $html .= '      <div class="zut_zeile fliesstext text_klein">'.$zutat.'</div>';
   $html .= '   </div>';
}

if ($allergiker != '') {
   $html .= '   <div id="allergiker">';
   $html .= '      <div class="zut_zeile fliesstext text_klein"><b>'.$this->text->get('artikel', 'allergie').'</b> '.$allergiker.'</div>';
   $html .= '   </div>';
}

$html .= '</div>';
