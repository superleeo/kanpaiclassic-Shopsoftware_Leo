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

// Template wird von OBADJA_Articles::render() eingebunden
$template_url  = ($this->params->multishop ? \KANPAICLASSIC\Helper::getData('multishop_images').'/'.$this->db_extern->querySingleValue("SELECT template FROM #__firma WHERE id = 1").'/images/zubehoerslider/' : TEMPLATE_URL.'/images/zubehoerslider/');
$html          = '';
$html1         = '';
$staffelpreise = $this->getStaffelpreis();
$werte         = $this->params->getWerte($data->merkmal1_name, $data->wert1_name, $data->merkmal2_name, $data->wert2_name);
$link          = $this->params->getLink('artikel', $data->art_id, $data->cat_name.'/'.$data->artikel_name, $werte);

$lieferzeit    = $data->lieferfrist;
$tax_active    = false;

if (\KANPAICLASSIC\Helper::checkSteuer($_SESSION['rechnung_land'], $_SESSION['wk_land']) && $this->params->firma['tax_active'] == 'y') {
   $tax_active = true;
}

$angebot_active = $data->angebot_active;
$this->getPrice($data, $tax_active);
$preis = $this->preis;

if ($data->angebot_active == 'y') {
   $preis = $this->sonderpreis;
}


if ($data->menge <= 0 && $this->params->firma['lager_bestell_check'] == 'y' && (int)$this->params->firma['lager_zeit'] > 0) {
   $lieferzeit = $this->params->firma['lager_zeit'];
}

if ((int)$data->widerruf == 0) {
   $data->widerruf = 1;
}

// Module Website
$hide_wk       = false;
$show_object   = false;
$is_mixer      = false;

if (defined('CONF_MODULE_WEBSITE')) {
   if ($data->show_object == 'y') {
      $show_object = true;
      $hide_wk     = true;
   }

   if ($this->params->firma['hide_wk'] == 'y') {
      $hide_wk = true;
   }
}

if (defined('CONF_MODULE_MIXER_ARTIKEL') && $data->mixer_artikel_check == 'y') {
   $is_mixer = true;
}

$html .= '<div itemscope itemtype="http://schema.org/Product" id="artikel_details" class="col_single margin_bottom" >'.CR;
$html .= '   <meta itemprop="name" content="'.str_replace('"', "'", $data->artikel_name).'" />';
$html .= '   <meta itemprop="url" content="'.$link.'" />';
$html .= '   <meta itemprop="image" content="'.$startpic.'" />';
$html .= '   <meta itemprop="category" content="'.$data->cat_name.'" />';
$html .= '   <meta itemprop="brand" content="'.($data->marke != '' ? $data->marke : 'markenlos').'" />'.CR;
$html .= '   <meta itemprop="description" content="'.(trim($data->artikel_text) != '' ? str_replace('"', "'", \KANPAICLASSIC\Helper::truncate(str_replace('[TRENNER]', '', strip_tags($data->artikel_text)), 100)) : 'keine Beschreibung').'" />'.CR;
$html .= '   <meta itemprop="sku" content="'.($data->art_nr != '' ? $data->art_nr : 'ohne').'" />'.CR;
$html .= '   <meta itemprop="gtin" content="'.($data->gtin != '' ? $data->gtin : 'ohne').'" />'.CR;
//$html .= '   <meta itemprop="mpn" content="'.($data->mpn != '' ? $data->mpn : 'ohne').'" />'.CR;
$html .= '   <div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating" style="display:none;">'.CR;
$html .= '      <span itemprop="ratingValue">5</span>'.CR;
$html .= '      <span itemprop="reviewCount">100</span>'.CR;
$html .= '   </div>'.CR;
$html .= '   <div itemprop="review" itemscope itemtype="http://schema.org/Review" style="display:none;">'.CR;
$html .= '      <span itemprop="name">'.str_replace('"', "'", $data->artikel_name).'</span>'.CR;
$html .= '      <span itemprop="author">'.$this->params->firma['shop_name'].'</span>'.CR;
$html .= '   </div>'.CR;
$html .= '      <div itemprop="offers" itemscope itemtype="http://schema.org/Offer" style="display:none;">'.CR;
$html .= '         <meta itemprop="price" content="'.\KANPAICLASSIC\Helper::number_format($preis, 2, '.', '').'" />';
$html .= '         <meta itemprop="priceCurrency" content="'.$this->params->waehrung.'" />';
$html .= '         <meta itemprop="availability" content="InStock" />'.CR;
$html .= '         <meta itemprop="priceValidUntil" content="'.date('c', time() + 24*3600).'" />'.CR;
$html .= '         <meta itemprop="url" content="'.$link.'" />'.CR;
$html .= '   </div>'.CR;

// Linke Spalte (Detailbild / Vorschaubilder)
// $html .= '   <div id="image_preview" class="col_lsl_l col_left_height">'.CR;
$html .= '   <div id="image_preview" class="col_lsl_l">'.CR;
$html .= '      <div id="detail_image">'.CR;
$html .=           $detail;
$html .= '      </div>'.CR;

// kein Hintergrund
$html .= '      <div id="detail_preview">'.CR;

if (!defined('CONF_FOTOGRAF') || !isset($data->fotodata)) {
   // Nur Anzeigen, wenn mehr als 1 Vorschaubild
   if ($anz_thumbs > 1) {
      $html .= '         <div id="zoomWindowOuter"><div id="zoomWindowLeft"></div></div>';
      $html .=           $preview_html;
   }

   else {
      $html .= '         <div id="zoomWindowOuter"><div id="zoomWindowLeft"></div></div>';
      $html .=           $videos_image;  
   }
   //videos html
   if ($videos_html != '' ){
      $html .= $videos_html;
   }
}

$html .= '      </div>'.CR;
$html .= '      <div class="clear"></div>'.CR;


$html .= '   </div>'.CR;

// Mittlere Spalte (Abstand)
$html .= '   <div class="col_lsl_m"></div>'.CR;

// Grundeinheit
$grundeinheit    = $this->text->get('ge', $data->grundeinheit);

// Rechner (m x m x m)
$rechner_einheit = $data->grundeinheit_rechner;
// Rechner m3
$rechner_grundeinheit = $this->text->get('ge', $data->grundeinheit_rechner);

if (strlen($data->grundeinheit_rechner) && (substr($data->grundeinheit_rechner, - 1) == '2' || substr($data->grundeinheit_rechner, - 1) == '3')) {
   $rechner_einheit = substr($rechner_einheit, 0, -1);
}

// Normale Ausgabe
if (!defined('CONF_FOTOGRAF') || !isset($data->fotodata)) {
   // Rechte Spalte (Preis / Merkmale / Menge / Buttons / Beschreibung )
//   $html .= '   <div id="info_description" class="col_lsl_r col_right_height bg_flaechen">'.CR;
   $html .= '   <div id="info_description" class="col_lsl_r">'.CR;
   $html .= '      <div class="relative">'.CR;
   $html .= '         <form id="bestellform" action="'.SHOP_URL_IDX.'/inwarenkorb/'.$data->art_id.'" method="post" enctype="multipart/form-data">'.CR;
   $html .= '            <div id="detail_info">'.CR;
   $html .= '               <div class="art_detail_head bg_flaechen">'.CR;

   // Preise usw.
   if (!$show_object) { // Artikel normal anzeigen
      $tab = \KANPAICLASSIC\Helper::versandMode();

      if ($this->params->firma['price_login'] == 'y' && $this->params->user_id == 0) {
         $preis = '';
         $html1 = '';
         $angebot_active = '';
      }

      else {
/*
         $tax_active = false;

         if (\KANPAICLASSIC\Helper::checkSteuer($_SESSION['rechnung_land'], $_SESSION['wk_land']) && $this->params->firma['tax_active'] == 'y') {
            $tax_active = true;
         }
*/
/*
         $angebot_active = $data->angebot_active;
         $this->getPrice($data, $tax_active);
         $preis = $this->preis;

         if ($data->angebot_active == 'y') {
            $preis = $this->sonderpreis;
         }
*/
         // Mindestpreis, bei Brutto / Netto immer gleich
         if ($this->params->firma['mindest_check'] == 'y' && $this->params->firma['min_preis_check_'.$tab] == 'y') {
            $min_preis_brutto = (float)$this->params->firma['min_preis_'.$tab];
            $preis_s = round($this->preis, 2);

            if ($preis_s < $min_preis_brutto) {
               $html .= '            <div id="mindest_check">'.$this->text->get('art_detail', 'mindest').' '.number_format($min_preis_brutto, 2, ',', '.').' '. \KANPAICLASSIC\Helper::waehrungText($this->params->firma['waehrung'.$this->params->waehrung_id], 2).'</div>'.CR;
            }
         }

         // Sonderpreis
         if ($data->angebot_active == 'y') {
            $html .= '            <div class="preis_wrapper">'.$html1.'<div class="art_preis ueberschrift text_max">'.CR;
            $html .= '               <span id="art_preis">'.\KANPAICLASSIC\Helper::number_format($this->sonderpreis, 2, ',', '.').'</span>&nbsp;'.$this->params->waehrung.CR;
            $html .= '               </div>'.CR;
            $html .= '            </div>'.CR;
         }

         // Normaler Preis
         else {
            $html .= '            <div class="preis_wrapper">'.$html1.'<div class="art_preis ueberschrift text_max">';
            $html .= '               <span id="art_preis">'.\KANPAICLASSIC\Helper::number_format($this->preis, 2, ',', '.').'</span>&nbsp;'.$this->params->waehrung;
            $html .= '               </div>'.CR;
            $html .= '            </div>'.CR;
         }
      }
   }

   $html .= '            <div class="art_detail_links">'.CR;
   $html .= '               <div class="art_detail_title ueberschrift text_max">';
   $html .= (defined('CONF_ARTNAME_DETAIL') && CONF_ARTNAME_DETAIL == '2')? '<h2 class="text_max">'.$data->artikel_name.'</h2>' : '<h1 class="text_max">'.$data->artikel_name.'</h1>';
   $html .= '</div>'.CR;

   if (!$show_object) {
      if ($data->marke_aktiv == 'y') {
         $html .= '               <div class="art_detail_marke fliesstext text_klein">' . $this->text->get('filter', 'marke') .': '.$data->marke.'&nbsp;&nbsp;&nbsp;</div>'.CR;
      }

      $html .= '               <div class="art_detail_nr fliesstext text_klein">' . $this->text->get('art_detail', 'artikelnr') .': '.$data->art_nr.CR;

      if ($data->naehrwerte_check == 'y' && (defined('CONF_MODULE_NAEHRWERTE'))) {
         // Bei Mixer aus WK
         if ($is_mixer) {
            $html .= '                  &nbsp;<span class="art_detail_naehrwerte text_bold text_klein" onclick="Mixer2.naehrwerte('.$this->params->parent_id.');">'.$this->text->get('artikel', 'naehrwerte').'</span>'.CR;
         }

         // Sonst aus DB
         else {
            $html .= '                  &nbsp;<span class="art_detail_naehrwerte text_bold text_klein" onclick="popupNaehrwerte('.$this->params->parent_id.');">'.$this->text->get('artikel', 'naehrwerte').'</span>'.CR;
         }
      }

      $html .= '               </div>';

      if ($this->params->firma['lager_show'] == 'y') {
//         $html .= '               <div class="art_detail_lager fliesstext text_klein">' . $this->text->get('art_detail', 'lagermenge') . ': ' . number_format($data->menge, $data->masse_komma, ',', '').'</div>'.CR;
         $html .= '               <div class="art_detail_lager fliesstext text_klein">' . $this->text->get('art_detail', 'lagermenge') . ': ' . ($data->menge > 0 ? number_format($data->menge, $data->masse_komma, ',', '') : 0).'</div>'.CR;
      }

      $html .= '               <div class="art_detail_liefer fliesstext text_klein">'.CR;

      if (defined('CONF_MODULE_PORTAL')) {
         $html .= '                  <span><a href="'.SHOP_URL_IDX.'/profil/'.$data->haendler_id.'" target="_blank"><span style="text-decoration:underline;">'.$this->text->get('art_detail', 'lieferzeit').':</span>&nbsp;'.$lieferzeit.'&nbsp;'.$this->text->get('art_detail', 'tage').'</a></span>'.CR;
      }
      else {
          $html .= '                  <span><a href="'.SHOP_URL_IDX.'/versand" target="_blank"><span style="text-decoration:underline;">'.$this->text->get('art_detail', 'lieferzeit').':</span>&nbsp;'.$lieferzeit.'&nbsp;'.$this->text->get('art_detail', 'tage').'</a></span>'.CR;
      }
      $html .= '               </div>'.CR;



      if( defined('CONF_MODULE_ENERGIEEFFIZIENZLABEL') ){

          $id = $data->parent_id; //energy_efficiency;
          $energy_efficiency_img_url =  '/images/system/energieeffizienz/energielabel_'.$data->energy_efficiency.'.png';
          $energy_upload_url = SHOP_URL.'/pictures/energieeffizienz/';
          $boximage = $energy_upload_url.$data->energy_efficiency_image;
          if(!empty($data->energy_efficiency)){

              if(!empty($data->energy_efficiency_image)){
                  // box
                  $html .= "<div class='energyefficiency_img_box'
                    onClick='jQuery(this).hide();'><img alt='".$this->text->get('art_detail', 'energieeffizienzlabel').': '.strtoupper($data->energy_efficiency)."' title='".$this->text->get('art_detail', 'energieeffizienzlabel').': '.strtoupper($data->energy_efficiency)."' src='".$boximage."' /></div>".CR;
              }

              // Bild
              $html .= "<div  class='art_detail_energieeffizienz fliesstext text_klein ".(empty($data->energy_efficiency_image)?"noPicture":"hasPicture").'\'
                onMouseOver="jQuery(\'.energyefficiency_hover_box\').show()"
                onMouseOut="jQuery(\'.energyefficiency_hover_box\').hide()"
                onClick="jQuery(\'.energyefficiency_img_box\').show();">'."<img title='".$this->text->get('art_detail', 'energieeffizienzlabel').': '.strtoupper($data->energy_efficiency)."' alt='".$this->text->get('art_detail', 'energieeffizienzlabel').': '.strtoupper($data->energy_efficiency)."' src='".TEMPLATE_URL.$energy_efficiency_img_url."' /></div>".CR;

              $html .= "<div class='energyefficiency_hover_box' onClick='jQuery(this).hide();'><img title='".$this->text->get('art_detail', 'energieeffizienzlabel').': '.strtoupper($data->energy_efficiency)."' alt='".$this->text->get('art_detail', 'energieeffizienzlabel').': '.strtoupper($data->energy_efficiency)."' src='".$boximage."' /></div>".CR;

          }


      }




      if ($data->vpe != '') {
         $html .= '               <div class="art_detail_vpe fliesstext text_klein">'.CR;
         $html .= "                  <span>".$this->text->get('art_detail', 'vpe').':</span>&nbsp;'.$data->vpe.CR;
         $html .= '               </div>'.CR;
      }

      if ($data->vpm != '') {
         $html .= '               <div class="art_detail_vpm fliesstext text_klein">'.CR;
         $html .= "                  <span>".$this->text->get('art_detail', 'vpm').':</span>&nbsp;'.$data->vpm.CR;
         $html .= '               </div>'.CR;
      }
   }

   $html .= '            </div>'.CR;

   // rechte Spalte: Preis GE Steuer Versand
   $html .= '            <div class="art_detail_rechts'.($is_mixer ? ' mixer' : '').'">'.CR;

   if (!$show_object) {
      if ($this->params->firma['price_login'] == 'y' && $this->params->user_id == 0) {
      }

      else {
         $html .= '               <div class="art_preis_dummy"></div>'.CR;
         if ($angebot_active == 'y') {
            $html .= '            <div class="art_sonderpreis fliesstext text_klein">' . $this->text->get('art_detail', 'statt').'&nbsp;<span style="text-decoration:line-through;">'.\KANPAICLASSIC\Helper::number_format($this->preis, 2, ',', '.').'&nbsp;'.$this->params->waehrung.'</span></div>'.CR;
         }

         // Grundeinheit vorhanden ?
         if ($this->grundeinheit != '') {
            $html .= '            <div class="art_grundeinheit fliesstext art_menge text_klein">'.$this->text->get('art_detail', 'ge').' '.$this->grundeinheit_wk.'</div>'.CR;
         }

         $html .= '            <div class="art_ust fliesstext text_klein">'.$this->ust_txt.'</div>'.CR;

         $versandkosten_incl = ($this->params->firma['vers_grafik_check'] == 'y' && $this->versandkosten_incl || (empty($this->params->firma['vers_grafik_check']) || $this->params->firma['vers_grafik_check'] == 'n') && $data->versandfrei_check == 'y');

         if (defined('CONF_MODULE_PORTAL')) {
             $html .= '            <div class="art_versand fliesstext text_klein"><a class="fliesstext" href="'.SHOP_URL_IDX.'/profil/'.$data->haendler_id.'" target="_blank"><span style="text-decoration:underline;">'.$this->text->get('article', (/*$this->versandkosten_incl*/ $versandkosten_incl ? 'versand_inkl' : 'versand')).'</span></a></div>'.CR;
         }
         else {
             $html .= '            <div class="art_versand fliesstext text_klein"><a class="fliesstext" href="'.SHOP_URL_IDX . '/versand" target="_blank"><span style="text-decoration:underline;">'.$this->text->get('article', ( /*$this->versandkosten_incl*/ $versandkosten_incl ? 'versand_inkl' : 'versand')).'</span></a></div>'.CR;
         }

         if ($this->params->firma['gewicht_detail_check'] == 'y') {
            $html .= '            <div class="art_grundeinheit fliesstext text_klein">'.$this->text->get('article', 'gewicht').' '.number_format((float)$data->gewicht, 3, ',', '.').' '.$this->text->get('ge', 'kg').'</div>'.CR;
         }
      }
   }

   $html .= '            </div>'.CR;
   $html .= '            <div class="clear"></div>'.CR;
   $html .= '         </div>'.CR;

   // Module artikeltimer
   if (defined('CONF_MODULE_TIMER') && $data->timer_check == 'y' ) {
      $html .= '         <div class="detail_timer bg_flaechen abstand_box">'.CR;
      $html .= '            <div class="countdown_titel text_klein fliesstext">'.$this->text->get('timer', 'titel').'</div>';
      $html .= '            <div class="countdown countdown_wrapper" data-endtime="'.strtotime($data->timer_end).'">';
      $html .= '               <div class="titelzeile_timer">';
      $html .= '                  <div class="tage text_klein">'.$this->text->get('timer', 'tage').'</div>';
      $html .= '                  <div class="stunden text_klein">'.$this->text->get('timer', 'stunden').'</div>';
      $html .= '                  <div class="tage text_klein">'.$this->text->get('timer', 'minuten').'</div>';
      $html .= '                  <div class="sekunden text_klein">'.$this->text->get('timer', 'sekunden').'</div>';
      $html .= '                  <div class="clear"></div>';
      $html .= '               </div><br />';
      $html .= '               <div class="timerzeile">';
      $html .= '                  <div class="tage_z red_8"></div>';
      $html .= '                  <div class="tage_e red_8"></div>';
      $html .= '                  <div class="stunden_z blue_8"></div>';
      $html .= '                  <div class="stunden_e blue_8"></div>';
      $html .= '                  <div class="minuten_z blue_8"></div>';
      $html .= '                  <div class="minuten_e blue_8"></div>';
      $html .= '                  <div class="sekunden_z blue_8"></div>';
      $html .= '                  <div class="sekunden_e blue_8"></div>';
      $html .= '                  <div class="clear"></div>';
      $html .= '               </div>';
      $html .= '            </div>';
      $html .= '            <div class="clear"></div>'.CR;
      $html .= '         </div>'.CR;
   }

   // Merkmale (falls vorhanden), gafisch oder Selectbox
   if ($this->merkmal1 > 0 || $this->merkmal2 > 0) {
   // Werte als Symbole
      if (defined('CONF_MODULE_MW')) {
         if ($this->merkmal1 > 0) {
            $html .= '         <div class="detail_merkmale_symbol bg_flaechen abstand_box">'.CR;
            $html .= '            <div id="merkmal1" class="bg_flaechen">'.CR;
            $html .= '               <div class="merkmal_titel fliesstext text_klein">'.$this->merkmal1_txt_raw.'</div>'.CR;
            $html .= '               <div class="merkmal_symbol">'.CR;
            $html .= \KANPAICLASSIC\Helper::werteImgList($this->wert1_arr, 1, $this->params->art_id, 0, 7, 0).CR;
            $html .= '               </div>'.CR;
            $html .= '               <div class="clear"></div>'.CR;
            $html .= '            </div>'.CR;
            $html .= '         </div>'.CR;
         }

         if ($this->merkmal2 > 0) {
            $html .= '         <div class="detail_merkmale_symbol'.($this->merkmal1 == 0 ? '  bg_flaechen abstand_box' : '').'">'.CR;
            $html .= '            <div id="merkmal2" class="bg_flaechen">'.CR;
            $html .= '               <div class="merkmal_titel fliesstext text_klein">'.$this->merkmal2_txt_raw.'</div>'.CR;
            $html .= '               <div class="merkmal_symbol">'.CR;
            $html .= \KANPAICLASSIC\Helper::werteImgList($this->wert2_arr, 2, $this->params->art_id, 0, 7, 0).CR;
            $html .= '               </div>'.CR;
            $html .= '               <div class="clear"></div>';
            $html .= '            </div>'.CR;
            $html .= '         </div>'.CR;
         }
      }

      // Werte als Select-Box
      // Bei Klick Artikel neu laden
      else {
         if ($this->merkmal1) {
            $html .= '         <div class="detail_merkmale abstand_box">'.CR;
            $html .= '            <div class="col_in_ll_l bg_flaechen">'.CR;
            $html .= '               <div class="merkmal_titel fliesstext text_klein">'.$this->merkmal1_txt.'</div>'.CR;
            $html .= '            </div>'.CR;
            $html .= '            <div class="col_in_ll_r bg_flaechen">'.CR;
            $html .= '               <div class="merkmal_select">'.CR;
            $html .= '                  <span class="select_wrapper">'.CR;
            $html .= '                     <span class="selectbox text_formular text_gross">'.$this->wert1_opt.'</span>'.CR;
            $html .= '                  </span>'.CR;
            $html .= '               </div>'.CR;
            $html .= '            </div>'.CR;
            $html .= '            <div class="clear"></div>'.CR;
            $html .= '         </div>'.CR;
         }

         if ($this->merkmal2) {
            $html .= '         <div class="detail_merkmale">'.CR;
            $html .= '            <div class="col_in_ll_l bg_flaechen'.($this->merkmal1 == 0 ? ' abstand_box' : '').'">'.CR;
            $html .= '               <div class="merkmal_titel fliesstext text_klein">'.$this->merkmal2_txt.'</div>'.CR;
            $html .= '            </div>'.CR;
            $html .= '            <div class="col_in_ll_r bg_flaechen">'.CR;
            $html .= '               <div class="merkmal_select">'.CR;
            $html .= '                  <span class="select_wrapper">'.CR;
            $html .= '                     <span class="selectbox text_formular text_gross">'.$this->wert2_opt.'</span>'.CR;
            $html .= '                  </span>'.CR;
            $html .= '               </div>'.CR;
            $html .= '            </div>'.CR;
            $html .= '            <div class="clear"></div>'.CR;
            $html .= '         </div>'.CR;
         }
      }
   }

   // Modul multikonfigurator - außerhalb Formular -> input:configurator im Formular
   if (defined('CONF_MODULE_MATRIX') && $this->matrix == 'y') {
      $matrix    = KANPAICLASSIC\Control::getModuleMatrix();
      $m_data    = $matrix->getConfig($data->art_id, $this->params->parent_id);
      $m_breite  = '';
      $m_hoehe   = '';
      $m_einheit = '';

      if (isset($m_data->{'breite_'.$this->params->selected_lang})) {
         $m_breite  = $m_data->{'breite_'.$this->params->selected_lang};
         $m_hoehe   = $m_data->{'hoehe_'.$this->params->selected_lang};
         $m_einheit = $m_data->{'einheit_'.$this->params->selected_lang};
      }

      if ($m_data) {
            $html .= '         <div id="matrix" class="detail_merkmale bg_flaechen abstand_box">'.CR;
            $html .= '            <div class="col_in_ll_l bg_flaechen matrix_input">'.CR;
            $html .= '               <div class="merkmal_titel fliesstext text_klein">'.$m_breite.' x '.$m_hoehe.' ('.$m_einheit.')</div>'.CR;
            $html .= '            </div>'.CR;
            $html .= '            <div class="col_in_ll_r bg_flaechen matrix_input">'.CR;
            $html .= '               <div class="matrix_breite bg_flaechen">'.CR;
            $html .= '                  <input type="text" id="matrix_breite"
                                            class="text_formular text_gross"
                                            value="'.number_format((isset($_SESSION['MATRIX_BREITE']) ? $_SESSION['MATRIX_BREITE'] : $m_data->min_breite), (int)$m_data->komma, ',', '').'"
                                            data-preis=""
                                            onchange="checkPrice();"
                                            onclick="checkMatrixIn(this);"
                                            onfocus="checkMatrixIn(this);"
                                            onmouseout="checkmatrixOut(this);"
                                            onblur="checkmatrixOut(this);" />'.CR;
            $html .= '               </div>'.CR;
            $html .= '               <div class="matrix_x bg_flaechen text_formular text_gross">x</div>'.CR;
            $html .= '               <div class="matrix_hoehe bg_flaechen">'.CR;
            $html .= '                  <input type="text" id="matrix_hoehe"
                                           class="text_formular text_gross"
                                           value="'.number_format((isset($_SESSION['MATRIX_HOEHE']) ? $_SESSION['MATRIX_HOEHE'] : $m_data->min_hoehe), (int)$m_data->komma, ',', '').'"
                                           data-preis=""
                                           onchange="checkPrice();"
                                           onclick="checkMatrixIn(this);"
                                           onfocus="checkMatrixIn(this);"
                                           onmouseout="checkmatrixOut(this);"
                                           onblur="checkmatrixOut(this);" />'.CR;
            $html .= '               </div>'.CR;
            $html .= '               </div>'.CR;
            $html .= '               <div class="col_in_ll_l bg_flaechen matrix_error">'.CR;
            $html .= '            </div>'.CR;
            $html .= '            <div class="col_in_ll_r bg_flaechen matrix_error">'.CR;
            $html .= '               <div id="matrix_msg" class="fliesstext text_normal form_err" class="col_single"></div>'.CR;
            $html .= '            </div>'.CR;
            $html .= '            <div class="clear"></div>'.CR;
            $html .= '         </div>'.CR;
      }
   }

   // Modul multikonfigurator - außerhalb Formular -> input:configurator im Formular
   if (defined('CONF_MODULE_MEGACONFIGURATOR') && $this->configurator_check == 'y' && $this->configurator != '') {
      $config = [];
      $texts  = [];
      $config_obj = json_decode($this->configurator_val);

      if (is_object($config_obj)) {
         $config = $config_obj->vals;
         $texts  = (array)$config_obj->texts;
      }

      else {
         $config = $config_obj;
      }

      for ($i = 0; $i < (is_array($config) ? count($config) : 0); $i++) {
         $merkmal  = $this->configurator->getFeMerkmale($config[$i][0]);
         $dropdown = (isset($config[$i][2]) ? $config[$i][2] : 'm');

         // Symbole dropdown ein(s), (m)ehrere, n -> alt Checkbox
         if ($dropdown != '' && $dropdown != 'y') {
            list($werte, $werte_ids) = $this->configurator->getFeWerte($config[$i][1], $this->steuer);

            // Test, ob aktiver Wert vorhanden ist
            $test = false;

            for ($t = 0; $t < count($werte); $t++) {
               if ($werte[$t]->active == 'y') {
                  $test = true;
                  break;
               }
            }

            if ($test) {
               $html .= '         <div class="detail_configurator  bg_flaechen abstand_box" data-merkmal="'.$merkmal->id.'">'.CR;
               $html .= '            <div class="merkmal bg_flaechen">'.CR;
               $html .= '               <div class="merkmal_titel fliesstext text_klein">'.$merkmal->name.'</div>'.CR;
               $html .= '               <div class="merkmal_symbol">'.CR;

               // Symbole ausgeben
               $first = true;
               for ($j = 0; $j < count($werte); $j++) {
                  if ($werte[$j]->active == 'y') {
                     $tooltip = $werte[$j]->name.
                                ($werte[$j]->art_nr_check == 'y' ? ' ('.$this->text->get('artikel', 'best_nr').':&nbsp;'. $werte[$j]->art_nr.')' : '').
                                '<br />'.
                                ($werte[$j]->price_add < 0 ? '<br />-' : '+').
                                \KANPAICLASSIC\Helper::number_format((float)$werte[$j]->price_add, 2 , ',', '').
                                '&nbsp;'.
                                $this->params->waehrung.
                                ($data->config_einheit_check == 'y' ? '&nbsp;'.$this->text->get('article', 'je').'&nbsp;'.$grundeinheit : '');

                     $wert_str = htmlentities(md5($werte[$j]->price_netto.$werte[$j]->art_nr_check.$werte[$j]->art_nr).';'.$werte[$j]->price_netto.';'.$werte[$j]->art_nr_check.';'.$werte[$j]->art_nr);
                     $html .= '                  <span class="werte_item'.($first == true && $dropdown == 's' ? ' is_selected' : '').'"
                                                    onclick="Configurator.priceCheck(this, \''.$dropdown.'\');"
                                                    data-price_add="'.(float)$werte[$j]->price_add.'"
                                                    data-wert="'.$werte[$j]->id.'"
                                                    data-config="'.$wert_str.'"
                                                    data-toggle="tooltip"
                                                    data-html="true"
                                                    data-original-title="'.$tooltip.'">
                                                    <img src="'.$werte[$j]->wert_img.'" alt="" />
                                                 </span>';

                     $first = false;
                  }
               }

               $html .= '               </div>'.CR;
               $html .= '               <div class="clear"></div>'.CR;
               $html .= '            </div>'.CR;
               $html .= '         </div>'.CR;
            }
         }

         // Dropdown
         else {
            $dropdown = $this->configurator->getFeDropdown($config[$i][1], $this->steuer);

            if ($dropdown != '') {
               $html .= '         <div class="detail_configurator" data-merkmal="'.$merkmal->id.'">'.CR;
               $html .= '            <div class="detail_merkmale">'.CR;
               $html .= '               <div class="col_in_ll_l bg_flaechen">'.CR;
               $html .= '                  <div class="merkmal_titel_drop fliesstext text_klein">'.$merkmal->name.'</div>'.CR;
               $html .= '               </div>'.CR;
               $html .= '               <div class="col_in_ll_r bg_flaechen">'.CR;
               $html .= '                  <div class="merkmal_select">'.CR;
               $html .= '                     <span class="select_wrapper">'.CR;
               $html .= '                        <span class="selectbox text_formular text_gross"><select onchange="Configurator.calculate();">'.$dropdown.'</select></span>'.CR;
               $html .= '                     </span>'.CR;
               $html .= '                  </div>'.CR;
               $html .= '               </div>'.CR;
               $html .= '               <div class="clear"></div>'.CR;
               $html .= '            </div>'.CR;
               $html .= '         </div>'.CR;
            }
         }
      } // for

      // Megakonfigurator Texte
      if (is_array($texts) && count($texts) > 0) {
         foreach ($texts as $text_id) {
            $input_title = $this->configurator->getFeInputs($text_id);

            $html .= '         <div class="detail_configurator" data-merkmal="2">'.CR;
            $html .= '            <div class="detail_merkmale">'.CR;
            $html .= '               <div class="col_in_ll_l bg_flaechen" style="width: 50%;">'.CR;
            $html .= '                  <div class="merkmal_titel_drop fliesstext text_klein">'.$input_title.'</div>'.CR;
            $html .= '               </div>'.CR;
            $html .= '               <div class="col_in_ll_r bg_flaechen" style="width: 50%;">'.CR;
            $html .= '                  <input type="text" class="configurator_text text_formular text_gross" value="" onchange="Configurator.calculate();" data-text_id="'.$text_id.'" />'.CR;
            $html .= '               </div>'.CR;
            $html .= '               <div class="clear"></div>'.CR;
            $html .= '            </div>'.CR;
            $html .= '         </div>'.CR;
         }
      }
   }

   // Mixer einbinden
   if ($is_mixer) {
      $mixer = KANPAICLASSIC\Control::getModuleMixerArtikel();
      $html .= '         <div id="article_mixer" data-mixer_msg_max="'.$this->text->get('mixer', 'max').'" data-mixer_msg_min="'.$this->text->get('mixer', 'min').'" data-mixer_ok_button="'.$this->text->get('button', 'ok').'" data-mixer_button_weiter="'.$this->text->get('button', 'weiter').'" data-mixer_button_abbrechen="'.$this->text->get('button', 'abbruch').'" data-mixer_button_wk="'.$this->text->get('button', 'in_wk').'"></div>'.CR;
      $html .= '         <div class="clear"></div>'.CR;
      $html .= $mixer->articleDetail($data);
      $html .= '         <div class="clear"></div>'.CR;
   }

   // Menge und Formular
   if (!$hide_wk) {
      $html .= '         <div id="detail_menge" class="bg_flaechen abstand_box">'.CR;
      $html .= '            <input type="hidden" id="rechner_einheit" name="rechner_einheit" value="'.$rechner_einheit.'" />'.CR;
      $html .= '            <input type="hidden" id="rechner_menge" name="rechner_menge" value="'.number_format(1, $data->masse_komma, ',', '').'" />'.CR;
      $html .= '            <input type="hidden" id="input_komma" name="komma" value="'.$data->masse_komma.'" />'.CR;
      $html .= '            <input type="hidden" id="inp_ml" name="merkliste" value="n" />'.CR;
      $html .= '            <input type="hidden" id="configurator" name="configurator" value="" />'.CR;
      $html .= '            <input type="hidden" id="configurator_price" name="configurator_price" value="" />'.CR;
      $html .= '            <input type="hidden" id="article_id" name="article_id" value="'.$data->art_id.'" />';
      $html .= '            <input type="hidden" id="preismatrix" name="preismatrix" value="" />';

      // Modul masseingabe / Rechner
      if ($data->masse_check == 'y') {
         // Rechner aktiv
         if ($data->rechner_check == 'y') {
            $html .= '            <div id="div_rechner">'.CR;
            $html .= '               <div class="col_in_ll_l">'.CR;
            $html .= '                  <div class="menge_txt fliesstext text_klein">' . $this->text->get('article', 'breite'.$data->rechner_mode).' ('.$this->text->get('ge', $rechner_einheit).((int)$data->rechner_mode > 1 ? ' x '.$this->text->get('ge', $rechner_einheit) : '').((int)$data->rechner_mode > 2 ? ' x '.$this->text->get('ge', $rechner_einheit) : '').')</div>'.CR;
            $html .= '               </div>'.CR;

            $html .= '               <div class="col_in_ll_r">'.CR;
            $html .= '                  <div style="position:relative; text-align:right; margin-right:18px;">'.CR;
            $html .= '                    <input type="hidden" name="rechner_mode"  id="rechner_mode"  value="'.$data->rechner_mode.'" />'.CR;

            if ((int)$data->rechner_mode == 1) {
               $html .= '                     <div class="rechner_breite">'.CR;
               $html .= '                        <input type="text" onblur="checkMengeMin(this, '.$data->masse_min.');" class="text_formular text_gross" name="rechner_breite"  id="rechner_breite"  value="'.number_format(1, $data->masse_komma, ',', '').'" />'.CR;
               $html .= '                        <input type="hidden" name="rechner_hoehe"  id="rechner_hoehe"  value="1" />'.CR;
               $html .= '                        <input type="hidden" name="rechner_tiefe"  id="rechner_tiefe"  value="1" />'.CR;
               $html .= '                     </div>'.CR;
            }

            else if ((int)$data->rechner_mode == 2) {
               $html .= '                     <div class="rechner_breite">'.CR;
               $html .= '                        <input type="text" onblur="checkMengeMin(this, '.$data->masse_min.');" class="text_formular text_gross" name="rechner_breite"  id="rechner_breite"  value="'.number_format(1, $data->masse_komma, ',', '').'" />'.CR;
               $html .= '                     </div>'.CR;

               $html .= '                     <div class="rechner_m text_formular text_gross">x</div>'.CR;
               $html .= '                     <div class="rechner_breite">'.CR;
               $html .= '                        <input type="text" onblur="checkMengeMin(this, '.$data->masse_min.');" class="text_formular text_gross" name="rechner_hoehe"  id="rechner_hoehe"  value="'.number_format(1, $data->masse_komma, ',', '').'" />'.CR;
               $html .= '                        <input type="hidden" name="rechner_tiefe"  id="rechner_tiefe"  value="1" />'.CR;
               $html .= '                     </div>'.CR;
            }

            else {
               $html .= '                     <div class="rechner_breite">'.CR;
               $html .= '                        <input type="text" onblur="checkMengeMin(this, '.$data->masse_min.');" class="text_formular text_gross" name="rechner_breite"  id="rechner_breite"  value="'.number_format(1, $data->masse_komma, ',', '').'" />'.CR;
               $html .= '                     </div>'.CR;

               $html .= '                     <div class="rechner_m text_formular text_gross">x</div>'.CR;
               $html .= '                     <div class="rechner_breite">'.CR;
               $html .= '                        <input type="text" onblur="checkMengeMin(this, '.$data->masse_min.');" class="text_formular text_gross" name="rechner_hoehe"  id="rechner_hoehe"  value="'.number_format(1, $data->masse_komma, ',', '').'" />'.CR;
               $html .= '                     </div>'.CR;

               $html .= '                     <div class="rechner_m text_formular text_gross">x</div>'.CR;
               $html .= '                     <div class="rechner_breite">'.CR;
               $html .= '                        <input type="text" onblur="checkMengeMin(this, \''.$data->masse_min.'\');" class="text_formular text_gross" name="rechner_tiefe"  id="rechner_tiefe"  value="'.number_format(1, $data->masse_komma, ',', '').'" />'.CR;
               $html .= '                     </div>'.CR;
            }

            if ((int)$data->rechner_mode != 1) {
               $html .= '                     <div class="rechner_m text_formular text_gross">=</div>'.CR;
               $html .= '                     <div class="rechner_breite2 text_formular text_gross"><span id="span_rechner">'.number_format(1, $data->masse_komma, ',', '').'</span> '.$rechner_grundeinheit.'</div>'.CR;
            }

            $html .= '                     <div class="clear"></div>'.CR;
            $html .= '                  </div>'.CR;
            $html .= '               </div>'.CR;
            $html .= '               <input type="hidden" id="rechner_check" name="rechner_check" value="on" />'.CR;
            $html .= '               <div class="clear"></div>'.CR;

            $html .= '               <div class="col_single">'.CR;
            $html .= '                  <div class="menge_txt fliesstext text_klein">' . $this->text->get('art_detail', 'menge') . ':</div>'.CR;
            $html .= '                  <input type="hidden" name="masse_check" id="masse_check" value="off" />'.CR;
            $html .= '                  <div id="menge_minus" onclick="Royalart.addMenge(-1); checkPrice();"></div>'.CR;
            $html .= '                  <input type="text" onchange="checkPrice();" id="input_menge" class="menge_int fliesstext text_gross" name="menge" id="art_menge" value="1" />'.CR;
            $html .= '                  <div id="menge_plus" onclick="Royalart.addMenge(1); checkPrice();"></div>'.CR;
            $html .= '               </div>'.CR;

            $html .= '            </div>'.CR;
            $html .= '            <div class="clear"></div>'.CR;
         }

         // kein Rechner
         else {
            // Grundeinheit anzeigen
            $html .= '            <div style="width:50%; float:left;">'.CR;
            $html .= '               <div class="menge_txt fliesstext text_klein">' . $this->text->get('artikel', 'angabe').' '.$this->text->get('ge', $rechner_einheit).':</div>'.CR;
            $html .= '            </div>'.CR;

            $html .= '            <div style="width:50%; float:left;">'.CR;
            $html .= '               <div id="masse_msg"></div>'.CR;
            $html .= '               <div class="menge_txt">'.CR;
            $html .= '                  <input type="hidden" name="masse_check" id="masse_check" value="on" />'.CR;

            $html .= '                  <input type="text" class="menge_float input_masse text_formular text_gross" name="masse_menge" id="masse_menge" value="'.number_format($data->masse_min, $data->masse_komma, ',', '').'" onchange="Royalart.checkMengeMasse('.($data->menge > 0 ? number_format($data->menge, $data->masse_komma, '.', '') : 0).');checkPrice();" />'.CR;
            $html .= '               </div>'.CR;
            $html .= '                  <div class="menge_ueberschritten_err form_err" style="float:right;padding:10px;display:none;">'.$this->text->get('warenkorb', 'lagercheck').'</div>'.CR;
            $html .= '            </div>'.CR;
            $html .= '            <input type="hidden" id="rechner_check" name="rechner_check" value="off" />'.CR;
            $html .= '            <div class="clear"></div>'.CR;
         }
      }

      // Menge anzeigen (Normale ausgabe)
      else {
         $html .= '            <div class="menge_txt fliesstext text_klein">' . $this->text->get('art_detail', 'menge') . ':</div>'.CR;
         $html .= '            <input type="hidden" name="masse_check" id="masse_check" value="off" />'.CR;
         $html .= '            <div id="menge_minus" onclick="Royalart.addMenge(-1); checkPrice();" style="opacity:0.5;"></div>'.CR;
         $html .= '            <input type="text" id="input_menge" class="menge_int fliesstext text_gross" name="menge" id="art_menge" value="1" onchange="Royalart.addMenge(0, '.($this->params->firma['lager_leer'] == 'n' ? $data->menge : 1000000).'); checkPrice();" />'.CR;
         $html .= '            <div id="menge_plus" onclick="Royalart.addMenge(1, '.($this->params->firma['lager_leer'] == 'n' ? $data->menge : 1000000).'); checkPrice();"></div>'.CR;
         $html .= '            <div class="menge_ueberschritten_err form_err" style="float:right;padding:10px;display:none;">'.$this->text->get('warenkorb', 'lagercheck').'</div>'.CR;
         $html .= '            <input type="hidden" id="rechner_check" name="rechner_check" value="off" />'.CR;
      }

      $html .= '         </div>'.CR;
      $html .= '         <div class="clear"></div>'.CR;

   }

   // Modul Motiv-Upload - Upload bei "In den Warenkorb"
   if (defined('CONF_MODULE_MOTIVUL') && ($data->motiv_uploadp_check == 'y' || $data->motiv_uploadt_check == 'y')) {
      // Nachschauen, ob Artikel bereits im WK oder Merkliste
      $wk = KANPAICLASSIC\Control::getWk();
      $ml = KANPAICLASSIC\Control::getML();
      $motiv_upload_name = '';
      $motiv_upload_user = '';
      $motiv_upload_text = '';

      // Eintrag aus WK/ML übernehmen
      for ($i = 0; $i < count($this->params->warenkorb); $i++) {
         if ($this->params->warenkorb[$i]['art_id'] == $data->id) {
            $motiv_upload_name = $this->params->warenkorb[$i]['motiv_upload_name'];
            $motiv_upload_user = $this->params->warenkorb[$i]['motiv_upload_user'];
            $motiv_upload_text = $this->params->warenkorb[$i]['motiv_upload_text'];
         }
      }

      if ($motiv_upload_name == '' && $motiv_upload_text == '') {
         for ($i = 0; $i < count($this->params->my_merkliste); $i++) {
            if ($this->params->my_merkliste[$i]['art_id'] == $data->id) {
               $motiv_upload_name = $this->params->my_merkliste[$i]['motiv_upload_name'];
               $motiv_upload_user = $this->params->my_merkliste[$i]['motiv_upload_user'];
               $motiv_upload_text = $this->params->my_merkliste[$i]['motiv_upload_text'];
            }
         }
      }

      $html .= '      <div id="motiv_upload_box">'.CR;

      if ($data->motiv_uploadt_check == 'y') {
         $html .= '         <div class="col_single">'.CR;
         $html .= '            <div class="motiv_single bg_flaechen">'.CR;
         $html .= '               <textarea class="eingabe text_formular text_normal textarea_resize" name="motiv_upload_text" " maxlength="1000" data-min_height="75" placeholder="'.$this->text->get('motivul', 'text').'">'.$motiv_upload_text.'</textarea>';
         $html .= '            </div>'.CR;
         $html .= '         </div>'.CR;
      }

      if ($data->motiv_uploadp_check == 'y') {
         $html .= '         <div class="col_in_ll_l bg_flaechen">'.CR;
         $html .= '            <div class="motiv_left">'.CR;
         $html .= '               <div id="motiv_upload" class="motiv_text fliesstext text_normal">'.($motiv_upload_user != '' ? $motiv_upload_user : $this->text->get('motivul', 'bild')).'</div>'.CR;
         $html .= '            </div>'.CR;
         $html .= '         </div>'.CR;

         $html .= '         <div class="col_in_ll_r">'.CR;
         $html .= '            <div  id="file_click" class="motiv_right col_button bg_button">'.CR;
         $html .= '               <input type="file" id="motiv_upload_file" name="motiv_upload_file" onchange="$(\'#motiv_upload\').html(\'<strong>\'+$(this).val().replace(/C:\\\\fakepath\\\\/i, \'\')+\'</strong>\');" value="" style="bottom:0; left:0; opacity:0; position:absolute; right:0; top:0; z-index:100;" />';
         $html .= '               <div class="motiv_text col_button text_gross">'.$this->text->get('motivul', 'titel').'</div>'.CR;
         $html .= '               <div class="motiv_symbol"></div>'.CR;
         $html .= '            </div>'.CR;
         $html .= '         </div>'.CR;
      }

      $html .= '         <div class="clear"></div>';
      $html .= '      </div>';
   }

   // Buttons Merkliste / Warenkorb
   if (!$hide_wk) {
      // Button Merkliste anzeigen
      $html .= '         <div id="details_buttons" class="bg_flaechen abstand_box">'.CR;
      $html .= '            <div class="col_in_ll_l">'.CR;
      $html .= '               <div id="div_ml" class="bg_flaechen pointer bg_button_only_hover" onclick="$(\'#inp_ml\').val(\'y\'); Royalart.showWk('.$data->art_id.');">'.CR;
      $html .= '                  <div class="div_ml_text fliesstext text_gross">'.$this->text->get('button', 'in_ml').'</div>'.CR;
      $html .= '                  <div class="div_ml_symbol"></div>'.CR;
      $html .= '               </div>'.CR;
      $html .= '            </div>'.CR;

      $html .= '            <div class="col_in_ll_r">'.CR;

      // Button Warenkorb

      // Berechnen für Preismatrix
      $html .= '               <div id="div_wk_matrix" class="bg_button col_button" style="display:none;" onclick="checkPrice();">'.CR;
      $html .= '                  <div class="div_wk_text col_button bg_button text_gross" />'.$this->text->get('button', 'berechnen').'</div>'.CR;
      $html .= '               </div>'.CR;

      if (!($this->params->firma['price_login'] == 'y' && $this->params->user_id == 0)) {
         if ($inwk) {
            // Ohne PopUp
            if (!defined('CONF_POPUP') || $this->params->firma['wk_popup_check'] == 'n') {
               $html .= '               <div id="div_wk" class="bg_button col_button" onclick="$(\'#inp_ml\').val(\'n\'); Royalart.showWk('.$data->art_id.');">'.CR;
            }

            // Mit PopUp
            else {
               // PopUp mit Zubehörmodul
               if (defined('CONF_MODULE_ZUBEHOER')) {
                  $zubehoer = $this->loadArticlesZubehoer($this->params->parent_id);
                  $html .= '            <div id="div_wk" class="bg_button col_button" onclick="Royalart.showWkPopup(\''.($zubehoer > 0 ? 'y' : 'n').'\', '.$data->art_id.', '.$this->params->parent_id.');">'.CR;
                  $html .= '               <input type="hidden" name="zubehoer" value="'.($zubehoer > 0 ? 'y' : 'n').'" />';
               }

               // PopUp ohne Zubehörmodul
               else {
                  $html .= '            <div id="div_wk" class="bg_button col_button" onclick="Royalart.showWkPopup(\'n\', '.$data->art_id.', '.$this->params->parent_id.');">'.CR;
                  $html .= '               <input type="hidden" name="zubehoer" value="n" />';
               }

            }

            // In Warenkorb anzeigen
            if ($data->menge > 0 || $this->params->firma['lager_bestell_check'] == 'n') {
               $html .= '               <div class="div_wk_text col_button bg_button text_gross">'.$this->text->get('button', 'in_wk').'</div>'.CR;
               $html .= '               <div class="div_wk_symbol"></div>'.CR;
            }

            // Vorbestellen anzeigen
            else {
               $html .= '               <div class="div_wk_text col_button bg_button text_gross" />'.$this->text->get('button', 'vorbestellen').'</div>'.CR;
               $html .= '               <div class="div_wk_symbol"></div>'.CR;
            }

            $html .= '            </div>'.CR;
         }

         // Nicht auf Lager anzeigen
         else {
            $html .= '            <div id="div_wk" class="bg_button_no col_button">'.CR;
            $html .= '               <div class="div_wk_text col_button bg_button_no text_gross" />'.$this->text->get('button', 'lager').'</div>'.CR;
            $html .= '            </div>'.CR;
         }
      }

      // Preise nach Login -> zum Login
      else {
         $html .= '            <div id="div_wk" class="bg_button col_button">'.CR;
         $html .= '               <div class="div_wk_text col_button bg_button text_gross" /><a class="bg_button col_button" href="'.SHOP_URL_IDX.'/login">'.$this->text->get('button', 'preislogin').'</a></div>'.CR;
         $html .= '               <div class="div_wk_symbol"></div>'.CR;
         $html .= '            </div>'.CR;
      }

      $html .= '            </div>'.CR;
      $html .= '            <div class="clear"></div>'.CR;
      $html .= '         </div>'.CR;
   }

   if (!$show_object && $this->params->firma['frage_check'] === 'y') {
         $html .= '         <div id="details_button_frage" class="bg_flaechen">'.CR;
         $html .= '            <div class="col_in_ll_l">&nbsp;</div>'.CR;
         $html .= '            <div class="col_in_ll_r">'.CR;
         $html .= '               <div id="frage_artikel" class="bg_button col_button" onclick="articleFrage(\''.$this->params->firma['email'].'\', \''.$this->params->art_name.'\', \''.$link.'\');">'.CR;
         $html .= '                  <div class="div_frage col_button bg_button text_gross" />'.$this->text->get('button', 'frage_artikel').'</div>'.CR;
         $html .= '                  <div class="div_frage_symbol"></div>'.CR;
         $html .= '               </div>'.CR;
         $html .= '            </div>'.CR;
         $html .= '            <div class="clear"></div>'.CR;
         $html .= '         </div>'.CR;
      }

   // Frage zum Artikel bei Objekt
   if ($show_object && \KANPAICLASSIC\Helper::getData('frage_check_objekt', 'n') === 'y') {
      $html .= '         <div id="details_button_frage" class="bg_flaechen abstand_box">'.CR;
      $html .= '            <div class="col_in_ll_l">&nbsp;</div>'.CR;
      $html .= '            <div class="col_in_ll_r">'.CR;
      $html .= '               <div id="frage_artikel" class="bg_button col_button" onclick="articleFrage(\''.$this->params->firma['email'].'\', \''.$this->params->art_name.'\', \''.$link.'\');">'.CR;
      $html .= '                  <div class="div_frage col_button bg_button text_gross" />'.$this->text->get('button', 'frage_artikel').'</div>'.CR;
      $html .= '                  <div class="div_frage_symbol"></div>'.CR;
      $html .= '               </div>'.CR;
      $html .= '            </div>'.CR;
      $html .= '            <div class="clear"></div>'.CR;
      $html .= '         </div>'.CR;
   }

   // Staffelpreise anzeigen (nicht bei Anzeige als Object)
   if (!$show_object) {
      if (!($this->staffel_zahl == 0 || $this->staffelpreis == "" || ($this->params->firma['price_login'] == 'y' && $this->params->user_id == 0))) {
         $html .= '      <div id="details_staffel" class="col_single bg_flaechen abstand_box">'.CR;
         $html .= '         <div id="staffelpreise" class="col_in_ll_l">'.CR;
         $html .= '            <div id="art_detail_staffel" class="fliesstext text_klein">' . $staffelpreise . '</div>'.CR;
         $html .= '            <div class="clear"></div>'.CR;
         $html .= '         </div>'.CR;
         $html .= '         <div class="col_in_ll_r"></div>'.CR;
         $html .= '         <div class="clear"></div>'.CR;
         $html .= '      </div>'.CR;
      }
   }

   $html .= '         </form>'.CR;
// Artikel-Text einspaltig   $html .= '   </div>'.CR;
}
// ENDE Normale Ausgabe

// Ausgabe bei Foto-Modul
else {
   $html .= '   <div id="info_description" class="col_lsl_r">'.CR;
   $html .= '      <div id="detail_info">'.CR;

   $html .= '         <div class="art_detail_head_foto bg_flaechen">'.CR;
   $html .= '            <div class="art_detail_title ueberschrift text_max">'.\KANPAICLASSIC\Helper::truncate($data->art_name, 60).'</div>';
   $html .= '            <div class="col_in_ll_l">';
   $html .= '               <div class="foto_art_nr fliesstext text_klein">' . $this->text->get('art_detail', 'artikelnr') .': '.$data->art_nr.'</div>';

   if ($this->params->firma['lager_show'] == 'y') {
   $html .= '               <div class="foto_lager fliesstext text_klein">' . $this->text->get('art_detail', 'lagermenge') . ': ' . (int)$data->menge.'</div>';
   }

   $html .= '            </div>';

   $html .= '            <div class="col_in_ll_r">';

   if ($this->params->firma['kleingewerbe'] == 'y') {
      $html .= '               <div class="foto_price fliesstext text_klein">'.$this->text->get('article', 'preis_kleing').'</div>';
   }
   else if ($this->params->firma['tax_active'] == 'y') {
      if ($this->params->firma['tax_show'] == 'y') {
         $html .= '               <div class="foto_price fliesstext text_klein">'.$this->text->get('article', 'preis_brutto').'</div>';
      }
      else {
         $html .= '               <div class="foto_price fliesstext text_klein">'.$this->text->get('article', 'preis_netto').'</div>';
      }
   }
   else {
      $html .= '               <div class="foto_price fliesstext text_klein">'.$this->text->get('article', 'preis').'</div>';
   }

   $html .= '            </div>';
   $html .= '            <div class="clear"></div>';

   $html .= '            <div class="col_in_ll_l">';
   $html .= '               <div class="foto_name fliesstext text_normal"><span>'.$this->text->get('article', 'groesse').'</span></div>';
   $html .= '               <div class="foto_size fliesstext text_normal">'.$this->text->get('article', 'pixel').'</div>';
   $html .= '            </div>';
   $html .= '            <div class="col_in_ll_r"></div>';
   $html .= '            <div class="clear"></div>';
   $html .= '         </div>';
   $html .= '         <div class="clear"></div>';

   // Lücken durch fehlende Größen beheben
   $fotodata = array();
   foreach ($data->fotodata as $foto) {
      $fotodata[] = $foto;
   }

   for ($i = 0; $i < count($fotodata); $i++) {
      $foto = $fotodata[$i];
      $html .= '         <div class="foto_zeile">';
      $html .= '            <form id="bestellform_'.$i.'" action="'.SHOP_URL_IDX.'/inwarenkorb/'.$data->art_id.'" method="post">';
      $html .= '               <div class="col_in_ll_l bg_flaechen">';
      $html .= '                  <div class="foto_name fliesstext text_normal"><span>'.$foto[0].'</span></div>';
      $html .= '                  <div class="foto_size fliesstext text_normal">'.$foto[1].'</div>';
      $html .= '               </div>';

      $html .= '               <div class="col_in_ll_r foto_line_right">';
      $html .= '                  <div class="foto_price fliesstext text_max bg_flaechen">'.$foto[2].'&nbsp;'.\KANPAICLASSIC\Helper::waehrungText($this->params->firma['waehrung1'], 1).'</div>';

      if (!defined('CONF_POPUP') || $this->params->firma['wk_popup_check'] == 'n') {
         $html .= '                  <div class="foto_inml bg_flaechen bg_button_only_hover" title="'.$this->text->get('menu', 'merkliste').'" onclick="$(\'#inp_ml_'.$i.'\').val(\'y\'); forms.bestellform_'.$i.'.submit();">';
         $html .= '                     <div class="foto_inml_symbol"></div>';
         $html .= '                  </div>';
         $html .= '                  <div  class="foto_wk bg_button col_button" title="'.$this->text->get('menu', 'warenkorb').'" onclick="forms.bestellform_'.$i.'.submit();"></div>';
      }
      else {
         $html .= '                  <div class="foto_inml bg_flaechen bg_button_only_hover" title="'.$this->text->get('menu', 'merkliste').'" onclick="$(\'#inp_ml_'.$i.'\').val(\'y\'); forms.bestellform_'.$i.'.submit();">';
         $html .= '                     <div class="foto_inml_symbol"></div>';
         $html .= '                  </div>';
         $html .= '                  <div  class="foto_wk bg_button col_button" title="'.$this->text->get('menu', 'warenkorb').'" onclick="forms.bestellform_'.$i.'.submit();"></div>';
      }

      $html .= '                  <input type="hidden" id="inp_ml_'.$i.'" name="merkliste" value="n" />'.CR;
      $html .= '                  <input type="hidden" name="menge" value="1" />';
      $html .= '                  <input type="hidden" name="foto_set" value="'.$foto[3].'" />';
      $html .= '                  <input type="hidden" name="foto_sort"  value="'.$foto[4].'" />';
      $html .= '                  <div class="clear"></div>';
      $html .= '               </div>';
      $html .= '               <div class="clear"></div>';
      $html .= '            </form>';
      $html .= '         </div>';
   }
}
// ENDE Foto-Modul

// Social-Icons
if ($this->params->firma['social_status'] != 'nein') {
   $social = \KANPAICLASSIC\Helper::getSocialDetails($this->params->getLink('artikel', $data->art_id, $data->artikel_name), $data->artikel_name, str_replace('[TRENNER]', '', $data->artikel_text), $startpic);

   if ($social['html'] !== null) {
      $html .= '      <div class="col_single bg_flaechen abstand_box">'.CR;
      $html .= '         <div id="detail_social">'.CR;

//      if (!isset($_SESSION['social_ok'])) {

      $html .= '<ul>';

      for ($i = 0; $i < count($social['article']); $i++) {
          if(!$social['article'][$i]['hasCookies']){
              $html   .= $social['article'][$i]['image'];
              $script .= $social['script'][$i];
          }
      }

      //$html .= '</ul>';


      // Nicht zugestimmt
      if (!isset($_SESSION['cookie_social']) || !$_SESSION['cookie_social']) {

          $html .= '</ul>';
          $html .= $social['html'];
      }

      //Zugestimmt
      else {
         //$html .= '<ul>';

         for ($i = 0; $i < count($social['article']); $i++) {
             if($social['article'][$i]['hasCookies']){
                 $html   .= $social['article'][$i]['image'];
                 $script .= $social['script'][$i];
             }
         }

         $html .= '</ul>';
      }

      $html .= '         </div>'.CR;
      $html .= '      </div>'.CR;

   }

}

$html .= '         </div>'.CR;
$html .= '         <div class="clear"></div>'.CR;

// Artikel Beschreibung
// 1-spaltig
if ($data->spalten2_check != 'y') {
   $html .= '      <div id="detail_description" class="abstand_box bg_flaechen">'.CR;
   $html .= '         <div id="artikel_text" class="fliesstext">'.CR;
   $html .= '            <div class="fliesstext text_normal">'.\KANPAICLASSIC\Helper::checkTextToggle(str_replace('[TRENNER]', '', $data->artikel_text)).'</div>'.CR;
   $html .= '            <div class="clear details_widerruf">'.CR;

   // Widerruf anzeigen
   if (!$show_object) {
      if ($this->params->firma['b2b_widerruf'] == 'y') {
         $html .= '            <br />'.CR;
         $html .= '            <a href="'.SHOP_URL_IDX.'/widerruf'.$data->widerruf.'" target="_blank">'.CR;
         $html .= '               <span class="fliesstext text_normal">&#9656;'.$this->text->get('widerruf', 'belehrung').'</span>'.CR;
         $html .= '            </a>'.CR;
      }
   }

   $html .= '            </div>'.CR;
   $html .= '         </div>'.CR;
   $html .= '      </div>'.CR;
}

$html .= '      </div>'.CR;
$html .= '   </div>'.CR;   // Ende Artikel-Info - rechte Spalte
$html .= '   <div class="clear"></div>'.CR;
$html .= '</div>'.CR;

// 2-spaltig - Über gesamte Breite
if ($data->spalten2_check == 'y') {
   list($text_l, $text_r) = explode('[TRENNER]', $data->artikel_text.'[TRENNER]');

   $html .= '<div id="artikel_text" class="col_single col_height">'.CR;

   // Linke Spalte Text
   $html .= '   <div class="col_lsl_l bg_flaechen col_left_height">'.CR;
   $html .= '      <div class="fliesstext text_normal article_text">'.\KANPAICLASSIC\Helper::checkTextToggle(str_replace('[TRENNER]', '', $text_l)).'</div>'.CR;
   $html .= '   </div>'.CR;

   // Mittlere Spalte (Abstand)
   $html .= '   <div class="col_lsl_m"></div>'.CR;

   // Rechte Spalte Text
   $html .= '      <div class="col_lsl_r bg_flaechen col_right_height">'.CR;

   $html .= '         <div class="fliesstext text_normal article_text">'.\KANPAICLASSIC\Helper::checkTextToggle(str_replace('[TRENNER]', '', $text_r)).CR;

   if (!$show_object) {
      if ($this->params->firma['b2b_widerruf'] == 'y') {
         $html .= '         <br />'.CR;
         $html .= '         <a href="'.SHOP_URL_IDX.'/widerruf'.$data->widerruf.'" target="_blank">'.CR;
         $html .= '            <span class="fliesstext text_normal">&#9656;'.$this->text->get('widerruf', 'belehrung').'</span>'.CR;
         $html .= '         </a>'.CR;
      }
   }

   $html .= '      </div>'.CR;
   $html .= '   </div>'.CR;

   $html .= '   <div class="clear"></div>'.CR;
   $html .= '</div>'.CR;
}

if (defined('CONF_MODULE_MUSIKPLAYER')) {
   $musikplayer = KANPAICLASSIC\Control::getModuleMusikplayer();
   $html .= $musikplayer->render($this->params->parent_id, true);
}

$html .= '<form id="werteform" action="' . SHOP_URL_IDX . '/artikel/" method="post" enctype="multipart/form-data">'.CR;
$html .= '<div><input type="hidden" name="myname" id="myname" value="' . $this->params->art_name .'" /></div>'.CR;
$html .= '</form>'.CR;

$html .= '<div id="fotoForm" style="display:none;">'.CR;
$html .= '   <div class="file-box-outer">'.CR;
$html .= '      <div id="show_progress" style="text-align: center;">'.CR;
$html .= '         <progress id="progress" max="100" value="0" style="width:182px; height:40px; color:#000000; text-align:center;">100%</progress>'.CR;
$html .= '      </div>'.CR;
$html .= '   </div>'.CR;
$html .= '</div>'.CR;

