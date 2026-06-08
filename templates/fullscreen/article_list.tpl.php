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

$over_check         = $this->params->firma['thumb_over_check'] == 'y';
$show_price         = ($this->params->firma['price_login'] == 'n' || $this->params->user_id > 0);
$show_timer         = false;
$show_timer_menge   = false;
$timer_menge        = 0;
$timer_preis        = 0;
$timer_art_disable;
$merkmal_over_check = false; // Artikel-Varianten / Merkmale anzeigen
$artikel_arr        = false;
$has_childs         = ((int)$artikel->childs > 1 ? true : false);
$is_foto            = ($artikel->is_foto == 'y' ? true : false);
$is_in_ml           = false;

$titel_2zeilig      = (isset($this->params->firma['art_zeilen']) && $this->params->firma['art_zeilen'] == 2 ? true : false);
$is_mixer           = false;

if (isset($_SESSION['my_merkliste']) && count($_SESSION['my_merkliste']) > 0) {
   foreach ($_SESSION['my_merkliste'] as $ml) {
      if ($ml['art_id'] == $artikel->id) {
         $is_in_ml = true;
      }
   }
}

if (defined('CONF_MODULE_MIXER_ARTIKEL') && $artikel->mixer_artikel_check == 'y') {
   $is_mixer = true;
}


if ($this->params->firma['merkmal_over_check'] == 'y' && $has_childs) {
   $artikel_arr = \KANPAICLASSIC\Helper::getArticlesAllMerkmal($artikel->id, $art_name);

   if (is_array($artikel_arr) && count($artikel_arr) > 0 && $artikel_arr[0] != '') {
      $merkmal_over_check = true;
      $anz_zeichen = 0;

      for ($i = 0; $i < count($artikel_arr); $i++) {
         $anz_zeichen += mb_strlen($artikel_arr[$i][0]);
      }

      if ($anz_zeichen > 35) {
         $artikel_arr = array(array($this->text->get('varianten', 'viele'), $artikel_arr[0][1]));
      }
   }
}

if (defined('CONF_MODULE_TIMER') && $artikel->timer_check == 'y') {
   $show_timer       = true;
   $show_timer_menge = ((int)$artikel->timer_menge > 0 && $artikel->timer_anzeige == 'y' ? true : false);
   $timer_menge      = ($show_timer_menge ? round((float)$artikel->menge / (float)$artikel->timer_menge * 100) : 0);

   if ($timer_menge > 100) {
      $timer_menge = 100;
   }

   $timer_preis       = \KANPAICLASSIC\Helper::number_format($this->preis, 2, ',', '.');
   $timer_art_disable = $artikel->timer_art_disable;
}

// Module Website
$hide_wk     = false;
$show_object = false;

if (defined('CONF_MODULE_WEBSITE')) {
   if ($artikel->show_object == 'y') {
      $show_object = true;
      $hide_wk = true;
   }

   if ($this->params->firma['hide_wk'] == 'y') {
      $hide_wk = true;
   }
}

// Module PersoCheck
$fsk_check = 'n';
if (defined('CONF_MODULE_PERSOCHECK')) {
   if ($artikel->fsk_check == 'y') {
      $fsk_check = 'y';
   }

   if ($_SESSION['alter_ok']) {
      $fsk_check = 'c';
   }
}

// Start Ausgabe
$html .= '<div itemscope itemtype="http://schema.org/Product" class="art_box cbp-item'.($is_mixer ? ' mixer' : '').($titel_2zeilig ? ' zweizeilig' : '').($markenfilter == 'y' && $marke != '' ? ' filter_'.str_replace(' ', '_', $marke) : '').'">'.CR;
$html .= '   <meta itemprop="name" content="'.$art_name.'" />'.CR;
$html .= '   <meta itemprop="url" content="'.$link.'" />'.CR;
$html .= '   <meta itemprop="image" content="'.$picture_big.'" />'.CR;
$html .= '   <meta itemprop="category" content="'.$artikel->cat_name.'" />'.CR;
$html .= '   <meta itemprop="brand" content="'.($artikel->marke != '' ? $artikel->marke : 'markenlos').'" />'.CR;
$html .= '   <meta itemprop="description" content="'.(trim($artikel->artikel_text) != '' ? str_replace('"', "'", \KANPAICLASSIC\Helper::truncate(str_replace('[TRENNER]', '', strip_tags($artikel->artikel_text)), 100)) : 'keine Beschreibung').'" />'.CR;
$html .= '   <meta itemprop="sku" content="'.($artikel->gtin != '' ? $artikel->gtin : 'ohne').'" />'.CR;
$html .= '   <div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating" style="display:none;">'.CR;
$html .= '      <span itemprop="ratingValue">5</span>'.CR;
$html .= '      <span itemprop="reviewCount">100</span>'.CR;
$html .= '   </div>'.CR;
$html .= '   <div itemprop="review" itemscope itemtype="http://schema.org/Review" style="display:none;">'.CR;
$html .= '      <span itemprop="name">'.$art_name.'</span>'.CR;
$html .= '      <span itemprop="author">'.$this->params->firma['shop_name'].'</span>'.CR;
$html .= '   </div>'.CR;
$html .= '      <div itemprop="offers" itemscope itemtype="http://schema.org/Offer" style="display:none;">'.CR;
$html .= '         <meta itemprop="price" content="'.($sonderpreis ? \KANPAICLASSIC\Helper::number_format($this->sonderpreis, 2, '.', '') : \KANPAICLASSIC\Helper::number_format($this->preis, 2, '.', '')).'" />'.CR;
$html .= '         <meta itemprop="priceCurrency" content="'.$this->params->waehrung.'" />'.CR;
$html .= '         <meta itemprop="availability" content="InStock" />'.CR;
$html .= '         <meta itemprop="priceValidUntil" content="'.date('c', time() + 24*3600).'" />'.CR;
$html .= '         <meta itemprop="url" content="'.$link.'" />'.CR;
$html .= '   </div>'.CR;

$html .= '   <div class="art_box_inner">'.CR;
$html .= '      <div class="altercheck_'.$fsk_check.'"></div>'.CR;

// Timer über Bild
if ($show_timer) {
   $html .= '      <div class="countdown countdown_wrapper'.($sonderpreis ? '' : ' center').($show_timer_menge ? ' countdown_menge' : '').'"
                        data-endtime="'.strtotime($artikel->timer_end).'"
                        '.($sonderpreis ? ' data-timer_preis="'.$timer_preis.'"' : '').'
                        data-art_disable="'.$timer_art_disable.'">'.CR;
   $html .= '         <div class="titelzeile_timer">'.CR;
   $html .= '            <div class="tage text_klein">'.$this->text->get('timer', 'tage').'</div>'.CR;
   $html .= '            <div class="stunden text_klein">'.$this->text->get('timer', 'stunden').'</div>'.CR;
   $html .= '            <div class="minuten text_klein">'.$this->text->get('timer', 'minuten').'</div>'.CR;
   $html .= '            <div class="sekunden text_klein">'.$this->text->get('timer', 'sekunden').'</div>'.CR;
   $html .= '            <div class="clear"></div>'.CR;
   $html .= '         </div><br />'.CR;
   $html .= '         <div class="timerzeile">'.CR;
   $html .= '            <div class="tage_z red_8"></div>'.CR;
   $html .= '            <div class="tage_e red_8"></div>'.CR;
   $html .= '            <div class="stunden_z blue_8"></div>'.CR;
   $html .= '            <div class="stunden_e blue_8"></div>'.CR;
   $html .= '            <div class="minuten_z blue_8"></div>'.CR;
   $html .= '            <div class="minuten_e blue_8"></div>'.CR;
   $html .= '            <div class="sekunden_z blue_8"></div>'.CR;
   $html .= '            <div class="sekunden_e blue_8"></div>'.CR;
   $html .= '            <div class="clear"></div>'.CR;
   $html .= '         </div><br />'.CR;

   if ($show_timer_menge) {
      $html .= '         <div class="timer_menge">'.CR;
      $html .= '            <div class="timer_menge_text text_klein">'.$this->text->get('timer', 'verfuegbar').' '.$timer_menge.'%</div>'.CR;
      $html .= '            <div class="timer_menge_aussen">'.CR;
      $html .= '               <div class="timer_menge_innen" style="width:'.$timer_menge.'%;"></div>'.CR;
      $html .= '            </div>'.CR;
      $html .= '         </div>'.CR;
   }
   $html .= '      </div>'.CR;
}
// Timer Ende

if (defined('CONF_MODULE_ARTIKELGRAFIK')) {
   if ($artikel->artikelgrafik6_check == 'y' && is_file(TEMPLATE_PATH.'/images/artikelgrafik6_'.$this->params->selected_lang.'.png')) {
      list($width, $height) = getimagesize(TEMPLATE_PATH.'/images/artikelgrafik6_'.$this->params->selected_lang.'.png');
      $html .= '<div class="artikelgrafik6" style="width:'.$width.'px; height:'.$height.'px; background-image:url('.TEMPLATE_URL.'/images/artikelgrafik6_'.$this->params->selected_lang.'.png);"></div>'.CR;
   }
   if ($artikel->artikelgrafik5_check == 'y' && is_file(TEMPLATE_PATH.'/images/artikelgrafik5_'.$this->params->selected_lang.'.png')) {
      list($width, $height) = getimagesize(TEMPLATE_PATH.'/images/artikelgrafik5_'.$this->params->selected_lang.'.png');
      $html .= '<div class="artikelgrafik5" style="width:'.$width.'px; height:'.$height.'px; background-image:url('.TEMPLATE_URL.'/images/artikelgrafik5_'.$this->params->selected_lang.'.png);"></div>'.CR;
   }
   if ($artikel->artikelgrafik4_check == 'y' && is_file(TEMPLATE_PATH.'/images/artikelgrafik4_'.$this->params->selected_lang.'.png')) {
      list($width, $height) = getimagesize(TEMPLATE_PATH.'/images/artikelgrafik4_'.$this->params->selected_lang.'.png');
      $html .= '<div class="artikelgrafik4" style="width:'.$width.'px; height:'.$height.'px; background-image:url('.TEMPLATE_URL.'/images/artikelgrafik4_'.$this->params->selected_lang.'.png);"></div>'.CR;
   }
   if ($artikel->artikelgrafik3_check == 'y' && is_file(TEMPLATE_PATH.'/images/artikelgrafik3_'.$this->params->selected_lang.'.png')) {
      list($width, $height) = getimagesize(TEMPLATE_PATH.'/images/artikelgrafik3_'.$this->params->selected_lang.'.png');
      $html .= '<div class="artikelgrafik3" style="width:'.$width.'px; height:'.$height.'px; background-image:url('.TEMPLATE_URL.'/images/artikelgrafik3_'.$this->params->selected_lang.'.png);"></div>'.CR;
   }
   if ($artikel->artikelgrafik2_check == 'y' && is_file(TEMPLATE_PATH.'/images/artikelgrafik2_'.$this->params->selected_lang.'.png')) {
      list($width, $height) = getimagesize(TEMPLATE_PATH.'/images/artikelgrafik2_'.$this->params->selected_lang.'.png');
      $html .= '<div class="artikelgrafik2" style="width:'.$width.'px; height:'.$height.'px; background-image:url('.TEMPLATE_URL.'/images/artikelgrafik2_'.$this->params->selected_lang.'.png);"></div>'.CR;
   }
   if ($artikel->artikelgrafik1_check == 'y' && is_file(TEMPLATE_PATH.'/images/artikelgrafik1_'.$this->params->selected_lang.'.png')) {
      list($width, $height) = getimagesize(TEMPLATE_PATH.'/images/artikelgrafik1_'.$this->params->selected_lang.'.png');
      $html .= '<div class="artikelgrafik1" style="width:'.$width.'px; height:'.$height.'px; background-image:url('.TEMPLATE_URL.'/images/artikelgrafik1_'.$this->params->selected_lang.'.png);"></div>'.CR;
   }
}

if ($artikel->neu_check == 'y') {
   $html .= '<div class="article_new"></div>';
}

$versandkosten_incl = ($this->params->firma['vers_grafik_check'] == 'y' && $this->versandkosten_incl || (empty($this->params->firma['vers_grafik_check']) || $this->params->firma['vers_grafik_check'] == 'n') && $artikel->versandfrei_check == 'y');

//if (($this->params->firma['vers_grafik_check'] == 'y' || $artikel->versandfrei_check == 'y') && $this->versandkosten_incl) {
if ($versandkosten_incl){
   $html .= '<div class="article_versandfrei"></div>';
}

$html .= '      <div class="cbp-caption">'.CR;


// Artikel-Bild mit Links
if (!defined('CONF_MODULE_ARTIKELGRAFIK') || $thumb_hover == '') {
   $html .= '         <div class="cbp-caption-defaultWrap">'.CR;
   $html .= '            <img class="art_box_pic" src="' . $thumb . '" title="'.$art_name.'" width="'.$thumb_x.'" height="'.$thumb_y.'" alt="'.$art_name.'" />'.CR;
   $html .= '         </div>'.CR;
   $html .= '         <div class="art_box_over cbp-caption-activeWrap">'.CR;
   $html .= '            <a class="art_box_link" href="'.$link.'" title="'.$art_name.'"></a>';
   $html .= '         </div>'.CR;
}

else {
   $html .= '         <div class="cbp-caption-defaultWrap no_opacity">'.CR;
   $html .= '            <img class="art_box_pic" src="' . $thumb . '" title="'.$art_name.'" width="'.$thumb_x.'" height="'.$thumb_y.'" alt="'.$art_name.'" />'.CR;
   $html .= '         </div>'.CR;
   $html .= '         <div class="art_box_over cbp-caption-activeWrap no_opacity">'.CR;
   $html .= '            <a class="art_box_link" href="'.$link.'" title="'.$art_name.'"
                            data-original="'.$thumb.'"
                            data-hover="'.$thumb_hover.'"
                            onmouseover="listImageHover(this, \'in\');"
                            onmouseout="listImageHover(this, \'out\');"
                         ></a>'.CR;
   $html .= '         </div>'.CR;
}


// Sonderpreis und Stern Merkliste
if (!$show_object) {
   if ($sonderpreis) {
      $html .= '<div class="is_ml_angebot ml_marker'.($is_in_ml ? ' ml_stern' : '').'" data-article_id="'.$artikel->id.'" onclick="location.href = \''.SHOP_URL_IDX.'/merkliste\'" data-toggle="tooltip" data-placement="left" data-original-title="'.str_replace(' ', '&nbsp;', $this->text->get('article', 'merkliste')).'"></div>'.CR;
   }

   else {
      $html .= '<div class="is_ml ml_marker'.($is_in_ml ? ' ml_stern' : '').'" data-article_id="'.$artikel->id.'" onclick="location.href = \''.SHOP_URL_IDX.'/merkliste\'" data-toggle="tooltip" data-placement="left" data-original-title="'.str_replace(' ', '&nbsp;', $this->text->get('article', 'merkliste')).'"></div>'.CR;
   }
}

// Symbole in der Mitte
if (!$hide_wk) {
   // Bei Artikeln mit Varianten, Configurator, Motiv-Upload oder deaktiviert ohne Varianten, Mixer-Artikel nur Details
   if ($is_foto
       || $has_childs
       || $configurator_check == 'y'
       || $rechner_check == 'y'
       || defined('CONF_MODULE_MOTIVUL') && ($artikel->motiv_uploadp_check == 'y'
       || $artikel->motiv_uploadt_check == 'y')
       || ($artikel->menge < 1 && $this->params->firma['lager_leer'] == 'n')
       || $artikel->mixer_artikel_check == 'y')
   {
      $html .= '         <div class="art_over_symbol_conf" style="display:none;">'.CR;
   }

   else if ($show_price) {
      $html .= '         <div class="art_over_symbol">'.CR;
   }

   else {
      $html .= '         <div class="art_over_symbol_nl">'.CR;
   }

   // Icon Details
   $html .= '            <div class="art_over_show '.($this->params->firma['zoom_artikel'] == 'y' ? 'art_w' :'art_b').'">'.CR;
   $html .= '               <a href="'.$link.'" data-toggle="tooltip" data-original-title="'.str_replace(' ', '&nbsp;', $this->text->get('button', 'details')).'"></a>'.CR;
   $html .= '            </div>'.CR;

   // Icon Merkliste
   if ($is_in_ml) {
      $html .= '               <div class="art_over_merk_sel pointer '.($this->params->firma['zoom_artikel'] == 'y' ? 'merk_w' :'merk_b').'" data-toggle="tooltip" data-original-title="'.str_replace(' ', '&nbsp;', $this->text->get('article', 'merkliste')).'"
                                    onclick="location.href = \''.SHOP_URL_IDX.'/merkliste\'"></div>'.CR;
   }

   else {
      $html .= '               <div class="art_over_merk pointer '.($this->params->firma['zoom_artikel'] == 'y' ? 'merk_w' :'merk_b').'" data-toggle="tooltip" data-original-title="'.str_replace(' ', '&nbsp;', $this->text->get('button', 'in_ml')).'"
                                    onclick="articleInWk(this, '.$artikel->id.', false);"></div>'.CR;
   }

   // Icon Warenkorb
   if ($show_price) {
      $html .= '               <div class="art_over_korb pointer '.($this->params->firma['zoom_artikel'] == 'y' ? 'korb_w' :'korb_b').'" data-toggle="tooltip"
                                 data-original-title="'.str_replace(' ', '&nbsp;', str_replace(' ' , '&nbsp;', $this->text->get('button', 'in_wk'))).'"
                                 onclick="articleInWk(this, '.$artikel->id.', true);"></div>'.CR;
   }

   $html .= '         </div>'.CR;
}

$html .= '      </div>'.CR;  // End cbp-cation


// Preis und Versand

// Name bei Mouseover (oben)
if ($over_check) {
   $html .= '      <h3 itemprop="name" class="art_box_top_over bg_preise artikelname text_gross"><a class="art_link_top artikelname" href="'.$link.'">'.\KANPAICLASSIC\Helper::truncate($art_name, 50) . '</a></h3>'.CR;

   if (!$show_object) {
      // Varianten als Symbole
      if ($merkmal_over_check) {
         $html .= '      <div class="merkmal_over_oben bg_preise">'.CR;
         for ($i = 0; $i < count($artikel_arr); $i++) {
            $html .= '         <a class="merkmal_link fliesstext text_normal" href="'.$artikel_arr[$i][1].'">'.$artikel_arr[$i][0].'</a>'.CR;
         }
         $html .= '      </div>'.CR;
      }

      $html .= '      <div class="preis_box_over bg_preise">'.CR;
   }

   else {
      $html .= '      <div class="preis_box_over bg_preise_object">'.CR;
   }
}

// Name nicht Mouseover
else {
   if (!$show_object) {
      $html .= '      <div class="preis_box_text bg_preise">'.CR;

      // Varianten als Symbole
      if ($merkmal_over_check) {
         $html .= '         <div class="merkmal_over bg_preise">'.CR;
         for ($i = 0; $i < count($artikel_arr); $i++) {
            $html .= '            <a class="merkmal_link fliesstext text_normal" href="'.$artikel_arr[$i][1].'">'.$artikel_arr[$i][0].'</a>'.CR;
         }
         $html .= '         </div>'.CR;
      }
   }
   else if ($this->params->firma['cpf_size'] == 'klein' || $this->params->firma['cpf_size'] == 'normal'){
      $html .= '      <div class="preis_box_text bg_preise">'.CR;
   }
   else {
      $html .= '      <div class="preis_box_text_object bg_preise">'.CR;
   }
}

// Ausgabe Grundeinheit/Preis und Sonderangebot (über Preis)
if (!$show_object && $show_price) {
   // Hintergrund-Grafik
   $bg_image = '';

   if ($sonderpreis || $this->grundeinheit != '' ) {
      // Hintergrund-Grafik auswählen
      if ($sonderpreis && $this->grundeinheit == '') {
         $bg_image = TEMPLATE_URL . '/images/system/angebot.png';
      }

      else if (!$sonderpreis && $this->grundeinheit != '') {
         $bg_image = TEMPLATE_URL . '/images/system/grundpreis.png';
      }

      else {
         $bg_image = TEMPLATE_URL . '/images/system/angebot_grundpreis.png';
      }

      $html .= '         <div class="ang_gp" style="background-image:url('.$bg_image.');" onclick="location.href=\''.$link.'\'">'.CR;

         // Grundeinheit vorhanden ?
      if ($this->grundeinheit != '') {
         $html .= '            <div class="angebot art_menge text_klein">' . $this->grundeinheit . '</div>'.CR;
      }

      // Sonderpreis?
      if ($sonderpreis) {
         $html .= '            <div class="art_sonderpreis angebot text_klein">'.$this->text->get('article', 'statt').' <span style="position:unset; padding:0;" class="art_menge angebot text_klein">'.\KANPAICLASSIC\Helper::number_format($this->preis, 2, ',', '.').' '.$this->params->waehrung.'</span></div>'.CR;
      }

      $html .= '         </div>'.CR;
   }
}

if ($show_price) {
   // Preisbox unter Artikelbild mit Preisen
   $html .= '         <a class="art_box_link2" href="'.$link.'" title="'.$art_name.'"></a>'.CR;

   if (!$over_check) {
      $html .= '         <h3 class="art_marker art_name artikelname text_gross" data-artikelname="'.$art_name.'" data-2zeilig="'.($titel_2zeilig ? 'y' : 'n').'">' . \KANPAICLASSIC\Helper::truncate($art_name, 23) . '</h3>'.CR;
   }

   if (!$show_object) {
      if ($is_foto) {
         $html .= '         <div class="art_ust_foto info text_klein">'.$this->ust_txt.'</div>'.CR;
      }

      else {
         $html .= '         <div class="art_ust info text_klein">'.$this->ust_txt.'</div>'.CR;
         $html .= '         <div class="art_versand"><a class="info text_klein" href="'.SHOP_URL_IDX . '/versand">'.$this->text->get('article', (/*$this->versandkosten_incl*/$versandkosten_incl ? 'versand_inkl' : 'versand')) . '</a></div>'.CR;
      }

      $html .= '         <div class="art_preis artikelname text_max"><span class="timer_preis artikelname text_max">'.($artikel->ab_check == 'y' ? '<span class="preis_ab info text_klein">'.$this->text->get('preis', 'ab').'</span>' : '').($sonderpreis ? \KANPAICLASSIC\Helper::number_format($this->sonderpreis, 2, ',', '.') : \KANPAICLASSIC\Helper::number_format($this->preis, 2, ',', '.')).'</span>&nbsp;'. $this->params->waehrung.'</div>'.CR;
   }
}

else {
   // Preisbox unter Artikelbild ohne Preis
   $html .= '         <a class="art_box_link2" href="' . $link . '" title="'.$art_name.'">'.CR;
   if (!$over_check) {
      $html .= '            <h3 class="art_marker art_name artikelname text_gross" data-artikelname="'.$art_name.'" data-2zeilig="'.($titel_2zeilig ? 'y' : 'n').'">' . \KANPAICLASSIC\Helper::truncate($art_name, 23) . '</h3>'.CR;
   }
   $html .= '         </a>'.CR;
}

 $html .= '      </div>'.CR;
// Ende  Preis und Versand

// Symbol Sonderpreis
if (!$show_object) {
   // Sonderpreis-Image oben
   if ($sonderpreis && (!defined('CONF_NO_PROZENT_IMG') || CONF_NO_PROZENT_IMG == 'n')) {
      $html .= '      <div class="sonderpreis_img">'.CR;
      $html .= '         <span class="text_max">'.$this->sonderpreis_prozent.'%</span>'.CR;
      $html .= '      </div>'.CR;
   }
}

// Striche
if ($this->params->firma['linien_horz'] == 'y') {
   $html .= '      <span data-line_h="y"></span>'.CR;
}
if ($this->params->firma['linien_vert'] == 'y') {
   $html .= '      <span data-line_v="y"></span>'.CR;
}

$html .= '   </div>'; // inner Kein Zeilenumbruch!!!!
$html .= '</div>'; // Kein Zeilenumbruch!!!!
