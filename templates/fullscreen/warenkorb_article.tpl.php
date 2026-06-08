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
$grundeinheit         = $text->get('ge' ,$my_wk->grundeinheit);
$grundeinheit_rechner = $text->get('ge' ,$my_wk->grundeinheit_rechner);
$rechner_einheit      = $text->get('ge' ,$my_wk->rechner_einheit);

$ge_preis = 0;
$ge_text  = '';
$ge_netto = $my_wk->ge_netto;

$link = '';

// Normaler Artikel
if (!isset($my_wk->cat_id) || $my_wk->cat_id == 0) {
   $link = $params->getLink('artikel', $my_wk->art_id, $my_wk->artikel_name);
}

// Kategorie-Mixer
else {
   $link = $params->getLink('kategorie', $my_wk->cat_id, $my_wk->artikel_name);;
}

if ($my_wk->ge_netto_aktiv == 'y') {
   if ($tax_active && $params->firma['tax_show'] == 'y') {
      $ge_preis = KANPAICLASSIC\Helper::number_format((float)$ge_netto * (1 + (float)$my_wk->steuer / 100), 2, ',', '.');
   }
   else {
      $ge_preis = KANPAICLASSIC\Helper::number_format($ge_netto, 2, ',', '.');
   }

   $ge_text = '<span id="ge_wrapper">'.$ge_preis . '</span> ' . $params->waehrung . ' ' . $text->get('article', 'je') . ' ' . $grundeinheit;
}

$picture = '';
$image   = $my_wk->image;

if ((int)$my_wk->startbild > 1) {
   if (isset($my_wk->images) && !empty($my_wk->images)) {
      foreach ($my_wk->images as $i) {
         if ((int)$i->sort == ((int)$my_wk->startbild - 1)) {
            $image = $i->image;
            break;
         }
      }
   }
}

// Importiertes Bild
if (strpos($image, 'http://') !== false || strpos($image, 'https://') !== false) {

   if ((int)$my_wk->cat_id == 0) {
      //$picture = str_replace('.jpg', '', $image).'_tn.jpg';
      if ($image != '' && $image != 'nopic.png') {
         $picture = str_replace('.jpg', '_tn.jpg', $image);
      }
   }

   // Mixer
   else {

//      $picture = $my_wk->image.'_tn.jpg';
      $picture = $my_wk->image;
   }
}

else {
   if ($params->multishop) {
      $picture = \KANPAICLASSIC\Helper::getData('multishop_images').'/'.CONF_PICT_PATH.$image.'_tn.jpg';
   }

   else {
      $picture = \KANPAICLASSIC\Helper::testPicture($image.'.jpg');
   }
}

$html_wk .= '<div class="pic_wrapper col_fix">'.CR;
$html_wk .= '   <div class="wk_picture">'.CR;
$html_wk .= '      <div class="bg_artikelbild">'.CR;

$html_wk .= '         <a href="'.$link.'">';
$html_wk .= '            <img src="'.$picture.'"'.(strpos($picture, 'nopic.png') !== false ? ' style="max-width:100%; height:162px;"' : '').' alt="" />';
$html_wk .= '         </a>'.CR;

if ($my_wk->angebot_active == 'y' && (!defined('CONF_NO_PROZENT_IMG') || CONF_NO_PROZENT_IMG == 'n')) {
   $tax_active = false;

   if (\KANPAICLASSIC\Helper::checkSteuer($_SESSION['rechnung_land'], $_SESSION['wk_land']) && $params->firma['tax_active'] == 'y') {
      $tax_active = true;
   }

   $is_brutto = true;

    if ($params->firma['kleingewerbe'] == 'y' || $params->firma['tax_show'] == 'n') {
      $is_brutto = false;
   }

   else if ($tax_active) {
     // Steuersatz 0%
     if ((int)$my_wk->steuersatz == 3) {
         $is_brutto = false;
      }
   }

   $artikel = \KANPAICLASSIC\Control::getArticles();
   $artikel->getPrice($my_wk, $tax_active);
   $html_wk .= '         <div class="sonderpreis_img fliesstext text_max">'.CR;
   $html_wk .= '            <span>'.$artikel->sonderpreis_prozent.'%</span>'.CR;
   $html_wk .= '         </div>'.CR;
   $html_wk .= '         <div class="ang_gp">'.CR;
   $html_wk .= '            <div class="art_sonderpreis angebot text_klein">'.$text->get('article', 'statt').'&nbsp;&nbsp;<span>'.\KANPAICLASSIC\Helper::number_format(($is_brutto ? $my_wk->statt_brutto : $my_wk->statt_netto), 2, ',', '.') . ' ' . $params->waehrung.'</div>'.CR;
   $html_wk .= '         </div>'.CR;
}

$html_wk .= '      </div>'.CR;

if (defined('CONF_MODULE_ZUBEHOER')) {
   $articles  = KANPAICLASSIC\Control::getArticles();
   $parent_id = $articles->getParentByArtid($my_wk->art_id);
   $anzahl    = $articles->loadArticlesZubehoer($parent_id);

   if ($anzahl > 0) {
      $ztitle = $articles->getZubehoertitle($parent_id);
      $html_wk .= '      <div class="zubehoer bg_button col_button" onclick="location.href=\''.$params->getLink('artikel', $my_wk->art_id, $my_wk->artikel_name).'\'";>'.CR;
      $html_wk .= '         <div class="div_zub_text col_button bg_button text_gross">'.$ztitle.'</div>'.CR;
      $html_wk .= '         <div class="div_zub_symbol"></div>'.CR;
      $html_wk .= '      </div>'.CR;
   }
}

$html_wk .= '   </div>'.CR;
$html_wk .= '</div>'.CR;

$html_wk .= '<div class= "info_wrapper col_rest">'.CR;
$html_wk .= '   <div class= "info_wrapper_inner">'.CR;
$html_wk .= '      <form id="wk_form_artikel_'.$my_wk->wk_id.'" method="post" action="'.SHOP_URL_IDX.'/wk_akt/'.$my_wk->wk_id.'" onsubmit="wkAktualisieren(this, '.$my_wk->wk_id.'); return false;">'.CR;
$html_wk .= '         <div class="wk_menge_left">'.CR;

if ($my_wk->masse_check == 'y' && $my_wk->rechner_check != 'y') {
   $html_wk .= '            <span class="wk_ge fliesstext text_gross">'.$text->get('artikel', 'angabe').' '.$grundeinheit_rechner.'</span>'.CR;
   $html_wk .= '            <input type="hidden" name="masse_check" value="on" />'.CR;
}

else {
   $html_wk .= '            <span class="wk_ge fliesstext text_gross">'.$text->get('artikel', 'menge').':</span>'.CR;
   $html_wk .= '            <div class="mixer_menge fliesstext text_gross">'.(isset($my_wk->mixer_menge) ? $my_wk->mixer_menge : '').'</div>'.CR;
   $html_wk .= '            <input type="hidden" name="masse_check" value="off" />'.CR;
}

$html_wk .= '         </div>'.CR;

$html_wk .= '         <div class="wk_menge_right">'.CR;
$html_wk .= '            <input type="text" class="wk_menge text_formular text_gross center" name="anzahl" value="'.number_format($my_wk->artikel_menge, ($my_wk->masse_check == 'y' && $my_wk->rechner_check != 'y' ? $my_wk->masse_komma : 0), ',', '').'" />'.CR;
// Keine Leerzeichen !!!
$html_wk .= '            <span class="wk_aktualisieren" onclick="wkAktualisieren(this, '.$my_wk->wk_id.');"></span>';
//$html_wk .= '<a class="wk_delete" href="'.SHOP_URL_IDX.'/wk_del/'.$my_wk->wk_id.'"></a>'.CR;
$html_wk .= '<span class="wk_delete pointer" onclick="$(\'.wk_menge\', $(this).parent()).val(0); wkAktualisieren(this, '.$my_wk->wk_id.');"></span>'.CR;
$html_wk .= '         </div>'.CR;
$html_wk .= '         <div class="clear"></div>'.CR;
$html_wk .= '      </form>'.CR;

if ($my_wk->rechner_check == 'y') {
   $html_wk .= '      <span class="wk_rechner fliesstext text_gross">';
   $html_wk .=           number_format($my_wk->rechner_breite, $my_wk->masse_komma, ',', '').
                         ((int)$my_wk->rechner_mode == 1 ? ' '.$rechner_einheit
                         :
                         $rechner_einheit.' x '.number_format($my_wk->rechner_hoehe, $my_wk->masse_komma, ',', '').
                         ((int)$my_wk->rechner_mode > 2 ? $rechner_einheit.' x '.
                             number_format($my_wk->rechner_tiefe, $my_wk->masse_komma, ',', '') : '').
                         $rechner_einheit.' = '.
                         // number_format($my_wk->rechner_breite * $my_wk->rechner_hoehe, $my_wk->masse_komma, ',', '').
                         number_format($my_wk->rechner_breite * $my_wk->rechner_hoehe * $my_wk->rechner_tiefe, $my_wk->masse_komma, ',', '').
                         $grundeinheit_rechner
                         );
   $html_wk .= '      </span>'.CR;
}

$html_wk .= '      <div class="wk_titel"><a class="ueberschrift text_max artikel_link" href="'.$link.'">'.$my_wk->artikel_name.'</a></div>'.CR;
$html_wk .= '      <div class="wk_artnr wk_artnr1 fliesstext text_klein">'.$text->get('artikel', 'best_nr').': '.$my_wk->art_nr.'</div>'.CR;

if ($my_wk->naehrwerte_check == 'y' && defined('CONF_MODULE_NAEHRWERTE')) {
   if ($my_wk->mixer != '' && $my_wk->cat_id > 0) {
      // Kategorie-Mixer
      $html_wk .= '<div class="wk_naehrwerte wk_artnr text_bold text_klein" onclick="Mixer1.naehrwerteWk('.$my_wk->wk_id.');">'.$text->get('artikel', 'naehrwerte').'</div>'.CR;
   }

   // Artikel-Mixer
   else if ($my_wk->mixer != '') {
      $html_wk .= '<div class="wk_naehrwerte wk_artnr text_bold text_klein" onclick="Mixer2.naehrwerteWk('.$my_wk->wk_id.');">'.$text->get('mixer', 'mixer_nw').'</div>'.CR;
   }

   // Normaler Artikel
   else {
      $html_wk .= '<div class="wk_naehrwerte wk_artnr text_bold text_klein" onclick="popupNaehrwerte('.$my_wk->parent_id.');">'.$text->get('artikel', 'naehrwerte').'</div>'.CR;
   }
}

$html_wk .= '      <div class="clear"></div>'.CR;

if ($my_wk->wert1_name && $my_wk->merkmal1_name) {
   $html_wk .= '      <div class="wk_artnr wk_sub_title fliesstext text_klein">';
   $html_wk .=           $my_wk->merkmal1_name.' '.$my_wk->wert1_name;

   if ($my_wk->merkmal2_name && $my_wk->wert2_name) {
      $html_wk .= '         , '.$my_wk->merkmal2_name.' '.$my_wk->wert2_name;
   }

   $html_wk .= '      </div>'.CR;
}

if (defined('CONF_MODULE_MATRIX') && $my_wk->preismatrix != '') {
   $matrix     = json_decode($my_wk->preismatrix);
   $m_breite   = '';
   $m_hoehe    = '';
   $m_einheit  = '';

   if (isset($matrix->{'breite_'.$lang})) {
      $m_breite   = $matrix->{'breite_'.$lang};
      $m_hoehe    = $matrix->{'hoehe_'.$lang};
      $m_einheit  = $matrix->{'einheit_'.$lang};
   }

   $html_wk .= '      <div class="wk_artnr fliesstext text_klein">'.$m_breite.' x '.$m_hoehe.' ('.$m_einheit.') : '.number_format($matrix->breite, $matrix->komma, ',', '').' x '.number_format($matrix->hoehe, $matrix->komma, ',', '').'</div>'.CR;
}

if (defined('CONF_MODULE_MEGACONFIGURATOR') && $my_wk->configurator != '') {
   $configurator = KANPAICLASSIC\Control::getModuleConfigurator();
   $html_wk .= $configurator->getConfiguratorByName($my_wk->configurator);
}

$html_wk .= '      <div class="wk_beschr fliesstext text_klein">'.KANPAICLASSIC\Helper::truncate( strip_tags(str_replace('[TRENNER]', ' ', $my_wk->artikel_text)), 300 ).'</div>'.CR;

// WK-Artiktl Preise und Steuer
if ($params->firma['price_login'] == 'y' && $params->user_id == 0) {
}

else {
   if ($ge_text != '') {
      $html_wk .= '      <div class="wk_grundeinheit fliesstext text_klein">';
      $html_wk .= '         <br /><div class="fliesstext art_menge text_klein">'.$ge_text.'</div>';
      $html_wk .= '      </div>'.CR;
   }

   $html_wk .= '      <div class="wk_steuer fliesstext text_klein">';

   if ($params->firma['kleingewerbe'] == 'y') {
      $text->get('article', 'preis_kleing');
   }

   else if ($tax_active) {
     // Steuersatz 0%
     if ((int)$my_wk->steuersatz == 3) {
         $html_wk .= $text->get('article', 'preis_kleing');
      }

      else if ($params->firma['tax_show'] == 'y') {
         $html_wk .= $text->get('article', 'preis_brutto');
      }

   else {
      $html_wk .= $text->get('article', 'preis_netto');
   }
}

else {
   $html_wk .= $text->get('article', 'preis_ausland');
}

$html_wk .= '      </div>'.CR;
$html_wk .= '      <div class="preis_img fliesstext text_max"><span class="new_price">'.KANPAICLASSIC\Helper::number_format($my_wk->preis, 2, ',', '.').'</span>&nbsp;'.$params->waehrung.'</div>'.CR;
$html_wk .= '      <div class="clear"></div>'.CR;

$html_wk .= '      <div class="preis_menge fliesstext text_klein" style="text-align:right; padding-bottom:10px;'.($my_wk->artikel_menge == 1 ? ' display:none;' : '').';">'.CR;
$html_wk .= '         <div class="menge_preis_l fliesstext text_klein">'.$text->get('artikel', 'zw_summe').':</div>'.CR;
$html_wk .= '         <input type="hidden" class="preis_menge_val" value="'.round($my_wk->preis, 2).'" />'.CR;
$html_wk .= '         <div class="menge_preis_r fliesstext text_klein">'.CR;
$html_wk .= '            <span class="menge_preis">'.KANPAICLASSIC\Helper::number_format(round($my_wk->preis, 2) * $my_wk->artikel_menge, 2, ',', '.').'</span>'.CR;
$html_wk .= '            &nbsp;'.$params->waehrung;
$html_wk .= '         </div>'.CR;
$html_wk .= '         <div class="clear"></div>'.CR;
$html_wk .= '      </div>'.CR;
}

$html_wk .= '   </div>'.CR;
$html_wk .= '</div>'.CR;
$html_wk .= '<div class="clear"></div>'.CR;
