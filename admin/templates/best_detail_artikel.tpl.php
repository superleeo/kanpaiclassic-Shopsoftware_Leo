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
$show_brutto = false;

if ($this->params->firma['tax_active'] == 'y' && $this->params->firma['tax_show'] == 'y') {
   $show_brutto = true;
}

$lang    = $this->params->firma['default_lang'];

// Tabelle bei Handyansicht verschiebbar
// Titelzeile
$html_artikel = '';
$html_artikel .= '<div id="listcontent" class="mobile_slide">';
$html_artikel .= '   <div id="listcontent" class="mobile_slide_inner">';
//$html_artikel .= '      <div class="txt_tit" style="padding:0 0 5px 63px;" onmousedown="$(\'.show_price\').hide(); $(\'.show_hidden\').show();" onmouseup="$(\'.show_hidden\').hide(); $(\'.show_price\').show();">Bestellte Artikel <span id="calc_new" class="fliesstext" style="display:none; color:#cc0000;">Bitte Speichern für Neuberechnung</span></div>'.CR;
$html_artikel .= '      <div class="artikel_title txt_tit" onmousedown="$(\'.show_price\').css(\'display\', \'none\'); $(\'.show_hidden\').css(\'display\', \'inline-block\');" '
        . '                                        onmouseup="$(\'.show_price\').css(\'display\', \'inline-block\'); $(\'.show_hidden\').css(\'display\', \'none\');">Bestellte Artikel <span id="calc_new" class="fliesstext" style="display:none; color:#cc0000;">Bitte Speichern für Neuberechnung</span></div>'.CR;
$html_artikel .= '      <div class="art_tab_title">'.CR;
$html_artikel .= '         <div class="art_line">'.CR;
$html_artikel .= '            <div class="art_symbole">&nbsp;</div>'.CR;
$html_artikel .= '            <div class="art_artnr ellipsis txt_bez">Artikelnummer</div>'.CR;
$html_artikel .= '            <div class="art_name ellipsis txt_bez">Bezeichnung</div>'.CR;
$html_artikel .= '            <div class="art_wert1 ellipsis txt_bez">Wert 1</div>'.CR;
$html_artikel .= '            <div class="art_wert2 ellipsis txt_bez">Wert 2</div>'.CR;
$html_artikel .= '            <div class="summe_wert art_preis right ellipsis txt_bez">Preis<span class="'.($show_brutto ? 'show_hidden' : 'show_price').'"> (netto)</span></div>'.CR;
$html_artikel .= '            <div class="summe_wert art_staffel right ellipsis txt_bez">Staffelpreis</div>'.CR;
$html_artikel .= '            <div class="summe_wert art_menge right ellipsis txt_bez summe_wert">Menge</div>'.CR;
$html_artikel .= '            <div class="art_summe right ellipsis txt_bez summe_wert">Summe</div>'.CR;
$html_artikel .= '         </div>'.CR;
$html_artikel .= '      </div>'.CR;

$ii = 0;    // Zähler für Rechner

// Artikel ausgeben
$html_artikel .= '      <div class="art_tab">'.CR;

if ((isset($data2) ? count($data2) : 0) > 0) {
   foreach ($data2 as $data1) {
      $is_megaconf    = false;
      $is_matrix      = false;
      $is_rechner     = false;
      $rechner_breite = 0;
      $rechner_hoehe  = 0;
      $rhtml          = '';
      $is_mixer1      = false;
      $is_mixer2      = false;

      $komma          = (int)$data1->masse_komma;
      $art_preis      = number_format($data1->artikel_netto, 9, ',', '.');
      $art_preis_2    = number_format($data1->artikel_netto, 2, ',', '.');
      $art_brutto     = number_format($data1->artikel_brutto , 2, ',', '.');
//      $art_brutto     = number_format(((int)$data->gewerbe == 2 ? $data1->artikel_netto : $data1->artikel_brutto) , 2, ',', '.');
      $art_menge      = number_format($data1->menge, (int)$data1->masse_komma, ',', '.');
      $grundeinheit   = $data1->grundeinheit;
      $ge_rechner     = $data1->grundeinheit_rechner;
      $img_cursor     = '';
      $bild           = $data1->pict;
      $click          = $data1->pict;

      // Hash über Artikel, um Änderungen zu erkennen
      $cs_artikel     = md5((float)$data1->menge.(float)$data1->artikel_netto.(float)$data1->rechner_breite.(float)$data1->rechner_hoehe.(float)$data1->rechner_tiefe.$data1->aktiv.'no');

      if ($data1->mixer != '' && (int)$data1->cat_id > 0) {
         $is_mixer1 = true;
      }

      if ($data1->mixer != '' && (int)$data1->artikel_id > 0) {
         $is_mixer2 = true;
      }

      // Cursor
      if ($bild != '') {
         if(substr($bild, 0, 4) === 'http') {
            $img_cursor = ' style="cursor:url('.str_replace('.jpg', '_td.jpg', $bild).'), pointer;" ';
         }

         else {
            if (!$this->params->multishop) {
               $img_cursor = ' style="cursor:url('.PICTURE_URL.$bild.'_td.jpg), pointer;" ';
            }

            else {
               $img_cursor = ' style="cursor:url('.($this->params->multishop ? \KANPAICLASSIC\Helper::getData('multishop_images') : SHOP_URL).'/pictures/'.str_replace('.jpg', '', $bild).'_td.jpg), pointer;" ';
            }
         }
      }

      // Klick auf Artikelnummer
      if ($click != '') {
         if(!(substr($click, 0, 4) == 'http')) {
            if (!$this->params->multishop) {
               // Bild auf Server
               $click = PICTURE_URL.$click.'.jpg';
            }
            // Multishop-Master
            else {
               $click = KANPAICLASSIC\Helper::getData('multishop_images').'/pictures/'.$click.'.jpg';
            }
         }
      }

      if (defined('CONF_MODULE_MEGACONFIGURATOR') && $data1->configurator != '' && $data1->configurator != '[]') {
         $is_megaconf = true;
      }

      if (defined('CONF_MODULE_MATRIX') && $data1->preismatrix != '') {
         $is_matrix = true;
      }

      // Rechner aktiv
      if ($data1->rechner_check == 'y') {
         $is_rechner = true;

         $rhtml .= '<div class="rechner">';

         // Rechner Breite
         if ($data1->rechner_mode == 1) {
            $rhtml .= '   <input type="text"   id="breite_'.$ii.'" name="rechner_breite[]" value="'.number_format($data1->rechner_breite, $komma, ',', '').'" '.$readonly.' />';
            $rhtml .= '   <input type="hidden" id="hoehe_'.$ii.'"  name="rechner_hoehe[]"  value="1" />';
            $rhtml .= '   <input type="hidden" id="tiefe_'.$ii.'"  name="rechner_tiefe[]"  value="1" />';
            $rhtml .= '   <span>&nbsp;'.$data1->rechner_einheit.'&nbsp;</span>';
         }

         // Rechner Breite / Höhe
         else if ($data1->rechner_mode == 2) {
            $rhtml .= '   <input type="text"   id="breite_'.$ii.'" name="rechner_breite[]" value="'.number_format($data1->rechner_breite, $komma, ',', '').'" '.$readonly.' onkeyup="Bestellungen.checkMenge('.$ii.')" />';
            $rhtml .= '   <span>&nbsp;'.$data1->rechner_einheit.'&nbsp;x&nbsp;</span>';
            $rhtml .= '   <input type="text"   id="hoehe_'.$ii.'" name="rechner_hoehe[]"   value="'.number_format($data1->rechner_hoehe, $komma, ',', '').'" '.$readonly.' onkeyup="Bestellungen.checkMenge('.$ii.')" />';
            $rhtml .= '   <span>&nbsp;'.$data1->rechner_einheit.'&nbsp;=&nbsp;</span>';
            $rhtml .= '   <input type="hidden" id="tiefe_'.$ii.'" name="rechner_tiefe[]"   value="1" />';
         }

         // Rechner Breite / Höhe / Tiefe
         else if ($data1->rechner_mode == 3) {
            $rhtml .= '   <input type="text" id="breite_'.$ii.'" name="rechner_breite[]" value="'.number_format($data1->rechner_breite, $komma, ',', '').'" '.$readonly.' onkeyup="Bestellungen.checkMenge('.$ii.')" />';
            $rhtml .= '   <span>&nbsp;'.$data1->rechner_einheit.'&nbsp;x&nbsp;</span>';
            $rhtml .= '   <input type="text" id="hoehe_'.$ii.'"  name="rechner_hoehe[]"  value="'.number_format($data1->rechner_hoehe, $komma, ',', '').'" '.$readonly.' onkeyup="Bestellungen.checkMenge('.$ii.')" />';
            $rhtml .= '   <span>&nbsp;'.$data1->rechner_einheit.'&nbsp;x&nbsp;</span>';
            $rhtml .= '   <input type="text" id="tiefe_'.$ii.'"  name="rechner_tiefe[]"  value="'.number_format($data1->rechner_tiefe, $komma, ',', '').'" '.$readonly.' onkeyup="Bestellungen.checkMenge('.$ii.')" />';
            $rhtml .= '   <span>&nbsp;'.$data1->rechner_einheit.'&nbsp;=&nbsp;</span>';
         }

         // Alte Artikel
         else {
            $rhtml .= '   <input type="hidden" id="rechner_komma_'.$ii.'" value="'.$komma.'" />';
         }

         // Produkt Breite x Höhe ( x Tiefe)
         if ($data1->rechner_mode != 1) {
            $rhtml .= '   <input type="text" class="inp68 inp_right rechner_menge" id="menge_'.$ii.'" name="art_conf_menge[]" value="'.number_format($data1->rechner_breite * $data1->rechner_hoehe, $komma, ',', '').'" disabled="disabled" />';
            $rhtml .= '   <span>&nbsp;'.$this->text->get('ge', $ge_rechner).'</span>';
            $rhtml .= '   <input type="hidden" id="rechner_komma_'.$ii.'" value="'.$komma.'" />';
            $rhtml .= '   <div class="clear"></div>';
         }

         $rhtml .= '</div>';
      }

      // Daten Rechner müssen vorhanden sein
      else {
         $rhtml .= '   <input type="hidden" id="breite_'.$ii.'" name="rechner_breite[]" value="1" />';
         $rhtml .= '   <input type="hidden" id="hoehe_'.$ii.'"  name="rechner_hoehe[]"  value="1" />';
         $rhtml .= '   <input type="hidden" id="tiefe_'.$ii.'"  name="rechner_tiefe[]"  value="1" />';
      }

      $html_artikel .= '         <div class="art_line"data-delete="0">'.CR;
      $html_artikel .= '            <input type="hidden" name="art_id[]" id="art_id'.$data1->id.'" value="'.$data1->id.'" />'.CR;
      $html_artikel .= '            <input type="hidden" name="cs_artikel[]" value="'.$cs_artikel.'" />'.CR;

      $html_artikel .= '            <div class="art_symbole">'.CR;

      // Symbole
      if ($readonly) {
         $html_artikel .= '            <div class="art_aktiv">'.CR;
         $html_artikel .=                 ($data1->aktiv == 'y' ? '<span class="check"></span>' : '').CR;
         $html_artikel .= '               <input type="checkbox" class="newdesign" id="art_aktiv_'.$data1->id.'" name="art_xaktiv[]"'.($data1->aktiv =='y' ? ' checked="checked"' : '').$disabled.' onchange="$(\'.active_article\', $(this).closest(\'.art_line\')).val($(this).prop(\'checked\') ? \'on\' : \'off\');" />'.CR;
         $html_artikel .= '               <label for ="art_aktiv_'.$data1->id.'"></label>'.CR;
         $html_artikel .= '               <input type="hidden" class="active_article" name="art_active[]" value="'.($data1->aktiv =='y' ? 'on' : 'off').'" />'.CR;
         $html_artikel .= '            </div>'.CR;
         $html_artikel .= '            <div class="art_del">'.CR;
         $html_artikel .= '               <span class="muell far fa-trash-alt pointer" style="display:none;" onclick="$(this).closest(\'.art_line\').hide(); $(\'.delete_article\', $(this).closest(\'.art_line\')).val(\'del\');"></span>'.CR;
         $html_artikel .= '               <input type="hidden" class="delete_article" name="art_del[]" value="no" />'.CR;
         $html_artikel .= '            </div>'.CR;
      }

      else {
         $html_artikel .= '            <div class="art_aktiv">'.CR;
         $html_artikel .= '               <input type="checkbox" class="newdesign" id="art_aktiv_'.$data1->id.'" name="art_xaktiv[]"'.($data1->aktiv =='y' ? ' checked="checked"' : '').' onchange="$(\'.active_article\', $(this).closest(\'.art_line\')).val($(this).prop(\'checked\') ? \'on\' : \'off\');" />'.CR;
         $html_artikel .= '               <label for ="art_aktiv_'.$data1->id.'"></label>'.CR;
         $html_artikel .= '               <input type="hidden" class="active_article" name="art_active[]" value="'.($data1->aktiv =='y' ? 'on' : 'off').'" />'.CR;
         $html_artikel .= '            </div>'.CR;
         $html_artikel .= '            <div class="art_del">'.CR;
         $html_artikel .= '               <span class="muell far fa-trash-alt pointer"  onclick="$(this).closest(\'.art_line\').hide(); $(\'.delete_article\', $(this).closest(\'.art_line\')).val(\'del\');"></span>'.CR;
         $html_artikel .= '               <input type="hidden" class="delete_article" name="art_del[]" value="no" />'.CR;
         $html_artikel .= '            </div>'.CR;
      }

      $html_artikel .= '            </div>'.CR;

      // Artikeldaten
      $html_artikel .= '            <div>'.CR;
      $html_artikel .= '            <div class="art_artnr ellipsis"'.$img_cursor.' onclick="showImage(\''.$click.'\')">'.KANPAICLASSIC\Helper::truncate($data1->artikel_nummer, 18).'</div>'.CR;
      $html_artikel .= '            <div class="art_name ellipsis">'.$data1->name_shop.'</div>'.CR;
      $html_artikel .= '            <div class="art_wert1 ellipsis">'.$data1->wert1.'</div>'.CR;
      $html_artikel .= '            <div class="art_wert2 ellipsis">'.$data1->wert2.'</div>'.CR;
      // Netto-Preis
      $html_artikel .= '            <div class="art_preis'.($show_brutto ? ' show_hidden' : ' show_price').'">';
      $html_artikel .= '               <input type="hidden" class="right inp_netto" name="art_preis[]" value="'.$art_preis.'" '.$readonly.' />';
      $html_artikel .= '               <input type="text"   class="right inp_netto_2" name="art_preis_2[]" value="'.$art_preis_2.'" '.$readonly.'
                                          onchange="netto2brutto($(this), $(this).parent().parent().find($(\'.inp_brutto\')), \''.$data1->satz.'\');
                                             console.log($(this).val());
                                          $(\'.inp_netto\', $(this).closest(\'.art_line\')).val(point2komma(komma2point($(this).val())));"/>';
      $html_artikel .= '            </div>'.CR;
      // Brutto-Preis
      $html_artikel .= '            <div class="art_preis'.($show_brutto ? ' show_price' : ' show_hidden').'">';
      $html_artikel .= '               <input type="text" class="right inp_brutto" name="art_brutto[]" value="'.$art_brutto.'" '.$readonly.'
                                          onchange="brutto2netto($(this), $(this).parent().parent().find($(\'.inp_netto\')), \''.$data1->satz.'\');
                                          $(this).parent().parent().find($(\'.inp_netto_2\')).val(point2komma(runden(komma2point($(this).val()))));" />';
      $html_artikel .= '            </div>'.CR;
      $html_artikel .= '            <div class="art_staffel ellipsis summe_wert">'.KANPAICLASSIC\Helper::staffelmenge($data1->staffelung, (int)$data1->menge).'</div>'.CR;
      $html_artikel .= '            <div class="art_menge"><input type="text" class="right" name="art_menge[]" value="'.$art_menge.'" '.$readonly.' /></div>'.CR;
      $html_artikel .= '            <div class="art_summe right summe_wert'.($show_brutto ? ' show_hidden' : ' show_price').'">'.($data1->aktiv =='y' ? number_format($data1->menge * round((float)$data1->artikel_preis, 2) , 2, ',', '') : '').'</div>'.CR;
      $html_artikel .= '            <div class="art_summe right summe_wert'.($show_brutto ? ' show_price' : ' show_hidden').'">'.($data1->aktiv =='y' ? number_format($data1->menge * round((float)$data1->artikel_brutto, 2), 2, ',', '') : '').'</div>'.CR;
      $html_artikel .= '            <div class="clear"></div>'.CR;
      $html_artikel .= '            </div>'.CR;
//      $html_artikel .= '         </div>'.CR;

      // Mixer
      if ($is_mixer1 || $is_mixer2) {
         $mix_data    = 0;
         $mix_gewicht     = 0;
         $mix_prozent = 0;

         // Kategorie-Mixer
         if ($is_mixer1) {
            $mixer    = KANPAICLASSIC\Control::getModuleMixerKategorie();
            $mix_data = $mixer->ArticleExtend($data1->mixer);
         }

         // Artikel-Mixer
         if ($is_mixer2) {
            $mixer    = KANPAICLASSIC\Control::getModuleMixerArtikel();
            $mix_data = $mixer->ArticleExtend($data1->mixer);
         }

         for ($m = 0; $m < count($mix_data); $m++) {
            $mix = $mix_data[$m];

            $mix_prozent += $mix->mix_menge;
            $mix_gewicht += $mix->gewicht;

            // Artikeldaten ausgeben
            $html_artikel .= '            <div class="art_line_mixer">';
            $html_artikel .= '               <div class="art_artnr"'.$img_cursor.'>'.KANPAICLASSIC\Helper::truncate($mix->artikel_nummer, 18).'</div>'.CR;
            $html_artikel .= '               <div class="art_name ellipsis">'.$mix->name_shop.'</div>'.CR;
            $html_artikel .= '               <div class="art_wert1 ellipsis">'.$mix->wert1.'</div>'.CR;
            $html_artikel .= '               <div class="art_wert2 ellipsis">'.$mix->wert2.'</div>'.CR;
            $html_artikel .= '               <div class="art_preis'.($show_brutto ? ' show_hidden' : ' show_price').'">'.$mix->artikel_preis.'</div>'.CR;
            $html_artikel .= '               <div class="art_preis'.($show_brutto ? ' show_price' : ' show_hidden').'">'.$mix->artikel_brutto.'</div>'.CR;
            $html_artikel .= '               <div class="art_menge right">'.$mix->menge.'</div>'.CR;
            $html_artikel .= '               <div class="art_summe"></div>'.CR;
            $html_artikel .= '               <div class="clear"></div>'.CR;
            $html_artikel .= '            </div>'.CR;
         }

         if ($is_mixer2) {
            // Mixer Summen ausgeben
            $html_artikel .= '            <div class="art_line_mixer">';
            $html_artikel .= '               <div class="art_artnr"></div>'.CR;
            $html_artikel .= '               <div class="art_name ellipsis">&nbsp;</div>'.CR;
            $html_artikel .= '               <div class="art_wert1 ellipsis">&nbsp;</div>'.CR;
            $html_artikel .= '               <div class="art_wert2 ellipsis">&nbsp;</div>'.CR;
            $html_artikel .= '               <div class="art_preis">&nbsp;</div>'.CR;
            $html_artikel .= '               <div class="art_menge right">'.$mix_gewicht.$mix->einheit.' ('.$mix_prozent.'%)</div>'.CR;
            $html_artikel .= '               <div class="art_summe"></div>'.CR;
            $html_artikel .= '               <div class="clear"></div>'.CR;
            $html_artikel .= '            </div>'.CR;
         }
      }

      // Modul Preismatrix
      if ($is_matrix) {
         $matrix = json_decode($data1->preismatrix);

         $html_artikel .= '            <div class="art_line_matrix">';
         $html_artikel .= '               <div class="matrix">'.$matrix->{'breite_'.$lang}.' x '.$matrix->{'hoehe_'.$lang}.' ('.$matrix->{'einheit_'.$lang}.') : '.number_format($matrix->breite, $matrix->komma, ',', '').' x '.number_format($matrix->hoehe, $matrix->komma, ',', '').'</div>';
         $html_artikel .= '               <div class="clear"></div>'.CR;
         $html_artikel .= '            </div>';
      }

      // Daten Konfiguraor ausgeben
      if ($is_megaconf) {
         $configurator = KANPAICLASSIC\Control::getModuleConfigurator();
         $conf         = json_decode($data1->configurator, true);
         $texte        = null;

         if (isset($conf['texte'])) {
            $texte = $conf['texte'];
            unset($conf['texte']);
         }

         $c = (is_array($conf) ? count($conf) - 1 : -1);

         // Megakonfigurator
         for ($k = 0; $k <= $c; $k++) {
            if ($configurator->configLineToText($conf[$k], true) !='') {
               $html_artikel .= '            <div class="xconfig_line art_line_'.($is_rechner ? 'rechner' : 'mega_val').'">';
               $html_artikel .= '               <div class="art_artnr"></div>';
               $html_artikel .= '               <div class="art_name_art_wert2 ellipsis">'.$configurator->configLineToText($conf[$k], true).'</div>';

               // Rechner
               if ($is_rechner) {
                  $html_artikel .= '               <div class="art_preis_art_summe">'.$rhtml.'</div>';
                  $is_rechner = false;
                  $rhtml = '';
               }

               else {
                  $html_artikel .= '               <div class="art_preis_art_summe"></div>';
               }

               $html_artikel .= '               <div class="clear"></div>';
               $html_artikel .= '            </div>';
            }
         }

         if ($texte !== null) {
            foreach ($texte as $t) {
               $html_artikel .= '            <div class="art_line_mega_text">';
               $html_artikel .= '               <div class="art_artnr"></div>';
               $html_artikel .= '               <div class="art_name_art_wert2">'.$configurator->textById($t['text_id'], $this->params->selected_lang).': '.nl2br($t['text']).'</div>';
               $html_artikel .= '               <div class="clear"></div>';
               $html_artikel .= '            </div>';
            }
         }
      }

      // Daten Rechner ausgeben, falls nicht zuvor
      if ($is_rechner) {
         $html_artikel .= '            <div class="art_line_rechner">';
         $html_artikel .= '               <div class="art_artnr"></div>';
         $html_artikel .= '               <div class="art_name_art_wert2 ellipsis"></div>';
         $html_artikel .= '               <div class="art_preis_art_summe">'.$rhtml.'</div>';
         $html_artikel .= '               <div class="clear"></div>';
         $html_artikel .= '            </div>';
      }

      else {
         $html_artikel .= $rhtml;
      }


      // Modul Motivupload Text / Datei anzeigen
      if (defined('CONF_MODULE_MOTIVUL') && ($data1->motiv_upload_name != '' || trim($data1->motiv_upload_text) != '')) {
         $dir   = SHOP_PATH.'/downloads/motiv_dateien/';
         $html_artikel .= '            <div class="art_line_motiv">';

         // Upload
         if ($data1->motiv_upload_name != '' && file_exists($dir.$data1->motiv_upload_name)) {
            $upload = ADMIN_URL_IDX.'/ajax/bestellungen/motivDownload/'.$data1->motiv_upload_name;
            $html_artikel .= '               <div class="motiv_link pointer ellipsis" onclick="location.href = \''.$upload.'\';">';
            $html_artikel .= '                  <span class="motiv_link_symbol download_button pointer"></span>';
            $html_artikel .= '                  <span class="motiv_link_file">Daten vom Kunden:</span>';
            $html_artikel .= '                  <span class="motiv_link_text">'.$data1->motiv_upload_name.'</span>';
            $html_artikel .= '               </div>';
         }

         else {
            $html_artikel .= '               <div class="motiv_link ellipsis"></div>';
         }

         if (trim($data1->motiv_upload_text) != '') {
            $html_artikel .= '               <div class="motiv_text">';
            $html_artikel .= '                  <span class="motiv_text_title">Text:</span>';
            $html_artikel .= '                  <span alt="'.$data1->motiv_upload_text.'" title="'.$data1->motiv_upload_text.'" class="motiv_text_val ci_text ellipsis">'.nl2br(trim($data1->motiv_upload_text)).'</span>';
            $html_artikel .= '               </div>';
         }

         else {
            $html_artikel .= '               <div class="motiv_text ellipsis"></div>';
         }

         $html_artikel .= '               <div class="clear"></div>';
         $html_artikel .= '            </div>';
      }

//      $html_artikel .= $html;
      $ii++;

      $html_artikel .= '            <div class="clear"></div>'.CR;
      $html_artikel .= '         </div>'.CR;
   }
}

$html_artikel .= '      </div>'.CR;
$html_artikel .= '   </div>'.CR;
$html_artikel .= '</div>'.CR;

// Artikel hinzufügen (Button-Position)


// ****************** Summen ausgeben ************************************************************
// Steuer / Radio-Buttons und Gesamtsumme ausgeben
$ust1     = 0.00;
$ust2     = 0.00;
$ust3     = 0.00;
$ust_calc = $data->steuersatz3;

// Alle Artikel reduzierter USt.Satz
if ($data->steuer1 == 0 && $data->steuer2 != 0 && $data->steuer3 == 0) {
   $ust2  = $data->steuer2 + $data->versand_ust + $data->zahlart_ust - round($data->rabatt_ust2, 2) - $data->gutschrift_ust;
   $ust_calc = $data->steuersatz2;
}

else {
   $ust1 = $data->steuer1 + $data->versand_ust + $data->zahlart_ust - round($data->rabatt_ust1, 2 ) - $data->gutschrift_ust;
   $ust2 = $data->steuer2 - round($data->rabatt_ust2, 2);
   $ust3 = $data->steuer3 - round($data->rabatt_ust3, 2);
}

if ($ust1 > 0) {
   $ust_calc = $data->steuersatz1;
}

$summe = $data->netto + $ust1 + $ust2 + $ust3 + $data->versand_netto + $data->zahlart_netto - $data->rabatt_netto - $data->gutschrift_netto;

$html_artikel .= '<div class="block_sum">'.CR;
$html_artikel .= '   <div id="new_article" class="button txt_but" onclick="Bestellungen.addArticle();">Artikel hinzufügen</div>'.CR;
$html_artikel .= '   <input type="hidden" id="summe" value="'.$data->netto.'" />';
$html_artikel .= '   <input type="hidden" id="steuer1" value="'.$data->steuer1.'" />';
$html_artikel .= '   <input type="hidden" id="steuer2" value="'.$data->steuer2.'" />';
$html_artikel .= '   <input type="hidden" id="steuer3" value="'.$data->steuer3.'" />';

// Zwischensumme
$html_artikel .= '   <div class="line_sum">'.CR;
$html_artikel .= '      <div class="summe_text right">Zwischensumme<span class="'.($show_brutto ? 'show_hidden' : 'show_price').'"> (netto)</span></div>'.CR;
$html_artikel .= '      <div class="summe_wert right">'.CR;
$html_artikel .= '         <span class="'.($show_brutto ? 'show_hidden' : 'show_price').'">'.number_format($data->netto, 2, ',', '.').'</span>'.CR;
$html_artikel .= '         <span class="'.($show_brutto ? ' show_price' : 'show_hidden').'">'.number_format($data->brutto, 2, ',', '.').'</span>'.CR;
$html_artikel .= '      </div>'.CR;
$html_artikel .= '   </div>'.CR;

// Rabatt
$html_artikel .= '   <div class="line_sum">'.CR;
$html_artikel .= '      <div class="summe_text right">Rabatt'.CR;
$html_artikel .= '         <span class="'.($show_brutto ? 'show_hidden' : 'show_price').'"> (netto)</span> '.CR;
$html_artikel .= '         <input type="text" class="rabatt_prozent right" id="rabatt_prozent" name="rabatt_prozent" onchange="Bestellungen.rabatt(0, \''.$ust_calc.'\');" '.$readonly.' class="inp54 inp_right" value="'.number_format($data->rabatt_prozent, 4, ',', '').'" /> %'.CR;
$html_artikel .= '      </div>'.CR;
$html_artikel .= '      <div class="summe_wert_inp minus">'.CR;
$html_artikel .= '         <input type="text" name="rabatt" id="rabatt_betrag"
                               class="right inp_netto'.($show_brutto ? ' show_hidden' : ' show_price').'"
                               onchange="Bestellungen.rabatt(1, \''.$ust_calc.'\');" '.$readonly.'
                               value="'.number_format($data->rabatt_netto, 2, ',', '.').'" />'.CR;
$html_artikel .= '          <input type="text" name="rabatt_brutto" id="rabatt_betrag_brutto"
                               class="right'.($show_brutto ? ' show_price' : ' show_hidden').'"
                               onchange="brutto2netto($(this), $(this).parent().find($(\'.inp_netto\')), \''.$ust_calc.'\'); Bestellungen.rabatt(1, \''.$ust_calc.'\');" '.$readonly.' '
        . '                    value="'.number_format($data->rabatt_brutto, 2, ',', '.').'" />'.CR;
$html_artikel .= '      </div>'.CR;
$html_artikel .= '   </div>'.CR;

// Versand
$html_artikel .= '   <div class="line_sum">'.CR;
$html_artikel .= '      <div class="summe_text">'.CR;

if( $data->abholung_checkbox == 'y' || $data->zahlungsart == 6 /*"Bar vor Ort"*/ ){
    $html_artikel .= '                         <div class="dsgvo_no">Achtung: Abholung</div>'.CR;
}

$html_artikel .= ' Porto & Versand<span class="'.($show_brutto ? 'show_hidden' : 'show_price').'"> (netto)</span></div>'.CR;
$html_artikel .= '      <div class="summe_wert_inp">'.CR;
$html_artikel .= '         <input type="text" '.$readonly.' class="right inp_netto'.($show_brutto ? ' show_hidden' : ' show_price').'" name="porto" id="porto" value="'.number_format($data->versand_netto, 2, ',', '.').'" />'.CR;
$html_artikel .= '         <input type="text" '.$readonly.' class="right'.($show_brutto ? ' show_price' : ' show_hidden').'" name="porto_brutto" id="porto_brutto" value="'.number_format($data->versand_brutto, 2, ',', '.').'" onchange="brutto2netto($(this), $(this).parent().find($(\'.inp_netto\')), \''.$ust_calc.'\');" />'.CR;
$html_artikel .= '      </div>'.CR;
$html_artikel .= '   </div>'.CR;

// Zahlart
$html_artikel .= '   <div class="line_sum">'.CR;
$html_artikel .= '      <div class="summe_text"> '.$zahlungstext.'<span class="'.($show_brutto ? 'show_hidden' : 'show_price').'"> (netto)</span></div>'.CR;
$html_artikel .= '      <div class="summe_wert_inp">'.CR;
$html_artikel .= '         <input type="text" '.$readonly.' class="right inp_netto'.($show_brutto ? ' show_hidden' : ' show_price').'" name="zahlart_add" id="zahlart_add" value="'.number_format($data->zahlart_netto, 2, ',', '.').'" />'.CR;
$html_artikel .= '         <input type="text" '.$readonly.' class="right'.($show_brutto ? ' show_price' : ' show_hidden').'" name="zahlart_add_brutto" id="zahlart_add_brutto" value="'.number_format($data->zahlart_brutto, 2, ',', '.').'" onchange="brutto2netto($(this), $(this).parent().find($(\'.inp_netto\')), \''.$ust_calc.'\');" />'.CR;
$html_artikel .= '      </div>'.CR;
$html_artikel .= '   </div>'.CR;

// Gutschin / -schrift
$html_artikel .= '   <div class="line_sum">'.CR;
$html_artikel .= '      <div class="summe_text right">Gutschrift / Gutschein<span class="'.($show_brutto ? 'show_hidden' : 'show_price').'"> (netto)</span></div>'.CR;
$html_artikel .= '      <div class="summe_wert_inp right minus">'.CR;
$html_artikel .= '         <input type="text" '.$readonly.' class="right inp_netto'.($show_brutto ? ' show_hidden' : ' show_price').'" name="gutschein" id="gutschein" value="'.number_format($data->gutschrift_netto, 2, ',', '.').'" />'.CR;
$html_artikel .= '        <input type="text" '.$readonly.' class="right'.($show_brutto ? ' show_price' : ' show_hidden').'" name="gutschein_brutto" id="gutschein_brutto" value="'.number_format($data->gutschrift_brutto, 2, ',', '.').'" onchange="brutto2netto($(this), $(this).parent().find($(\'.inp_netto\')), \''.$ust_calc.'\');" />'.CR;
$html_artikel .= '      </div>'.CR;
$html_artikel .= '   </div>'.CR;

// Gewerbe / USt
$html_artikel .= '   <div class="line_sum">'.CR;
$html_artikel .= '      <div class="summe_text">'.CR;
$html_artikel .= '         <input type="radio" class="newdesign" id="gewerbe1" name="gewerbe" value="3"'.($data->gewerbe == 3 ? ' checked="checked"' : '').$disabled.' />'.CR;
$html_artikel .= '         <label for="gewerbe1">Kleingewerbe</label>'.CR;
$html_artikel .= '         <input type="radio" class="newdesign" id="gewerbe" name="gewerbe" value="2"'.($data->gewerbe == 2 ? ' checked="checked"' : '').$disabled.' />'.CR;
$html_artikel .= '         <label for="gewerbe">Ausland</label>'.CR;
$html_artikel .= '         <input type="radio" class="newdesign" id="gewerbe3" name="gewerbe" value="1"'.($data->gewerbe == 1 ? ' checked="checked"' : '').$disabled.' />'.CR;
$html_artikel .= '         <label for="gewerbe3"><span class="'.($show_brutto ? 'show_price' : 'show_hidden').'">enth. </span>MwSt</label>'.CR;
$html_artikel .= '      </div>'.CR;
$html_artikel .= '      <div class="summe_wert right"'.($data->gewerbe != 1 ? 'display="hidden"' : '').'>'.number_format($ust1 + $ust2 + $ust3, 2, ',', '.').'</div>'.CR;
$html_artikel .= '   </div>'.CR;

$html_artikel .= '   <div class="line_sum">'.CR;
$html_artikel .= '      <div class="summe_strich">-------------------------------</div>'.CR;
$html_artikel .= '   </div>'.CR;

// Endsumme
$html_artikel .= '   <div class="line_sum">'.CR;
$html_artikel .= '      <div class="summe_text right"><strong>Zahlungsbetrag</strong></div>'.CR;
$html_artikel .= '      <div class="summe_wert padr1"><strong>'.number_format($summe, 2, ',', '.').'</strong></div>'.CR;

// Widerruf bei Dienstleistung ausgeben
if ($data->widerruf == 4) {
   $html_artikel .= '      <div class="widertext"><br />Die Dienstleistung soll erst in 14 Tagen nach Ablauf der Widerrufsfrist beginnen.</div>'.CR;
}

if ($data->widerruf == 14) {
   $html_artikel .= '      <div class="widertext">Die Dienstleistung soll vor Ende der Widerrufsfrist beginnen.<br />Bei vollständiger Vertragserfüllung erlischt das Widerrufsrecht des Kundens.</div>'.CR;
}

$html_artikel .= '   </div>'.CR;

// Falls Gutschein eingelöst wurde
if ($data->gutschein_code != '') {
   $html_artikel .= '   <div class="summen_zeile">'.CR;
   $html_artikel .= '      <div class="summe_wert">'.CR;
   $html_artikel .= '         Gutschein '.$data->gutschein_code.' Wert '.number_format($data->gutschein_brutto, 2, ',', '.').' wurde eingelöst'.CR;
   $html_artikel .= '      </div>'.CR;
   $html_artikel .= '   </div>'.CR;
}

$html_artikel .= '</div>'.CR;
