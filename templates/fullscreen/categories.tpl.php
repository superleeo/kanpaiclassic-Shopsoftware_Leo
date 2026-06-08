<?php
/*
###################################################################################
  KANPAI CLASSIC Shopsoftware - Entwicklungsstand 06.2025

  Web Development - Agentur f�r Softwaregestaltung
  https://www.kanpaiclassic.com
  https://www.kanpaiclassic.com

  c Copyright by Kanpai Classic - Kanpai Classic Web Development


  Copyrightvermerke duerfen NICHT entfernt werden!

  ------------------------------------------------------------------------
  Dieses Programm ist eine Software von Kanpai Classic Web Development.
  Diese Software/Website ist eine Einzelplatzlizenz und f�r den Betrieb auf einem Speicherplatz 1 Installation berechtigt.
  Die Veroeffentlichung dieses Programms erfolgt OHNE IRGENDEINE GARANTIE, sogar ohne
  die implizite Garantie der MARKTREIFE oder der VERWENDBARKEIT FUER EINEN BESTIMMTEN ZWECK.
  Diese Script darf nicht veroeffentlicht oder weitergeben werden. Es gilt das Urheberrecht.
  Diese Software darf nur mit schritflicher Genehmigung modifizieren werden.
  Es gelten die Ihnen mitgeteilten Lizenzbestimmungen.
  ------------------------------------------------------------------------
  Bei Versto� gegen die Lizenzbedingungen kann die Lizenz jederzeit entzogen werden. Der Kaufpreises wird nicht erstattet.
  Wer gegen die Lizenzbedingungen verstoesst insbesondere bei illegalem Vertrieb oder Mehrfachnutzung des Scriptes  muss mit einer Vertragsstrafe von 50.000 Euro je Einzeldelikt rechnen!

##################################################################################
  Copyrightvermerke duerfen NICHT entfernt werden!
*/

if (!defined('KANPAICLASSIC')) {
   define('KANPAICLASSIC', true);
}

//
global $cat_left, $is_flaeche_mitte;
$img_url  = ($this->params->multishop ? \KANPAICLASSIC\Helper::getData('multishop_images') : SHOP_URL).'/'.CONF_PICT_PATH.'kategorien/';

$cat_html .= '<div class="'.($mode == 10 ? 'padding_top' : 'padding_top').'">';
$cat_html .= '<div class="'.($mode != 10 ? 'col_inner' : '').' bg_flaechen">';

if ($images && is_array($img_arr)) {
   $cat_html .= '<div id="col_img" class="col_img_'.$mode.(!$cat_left ? ' col_img' : ' col_img').'" data-cat_mode="'.($mode == 10 ? 'wide' : 'small').'"  data-cat_left="'.($cat_left ? 'y' : 'n').'" data-is_flaeche_mitte="'.($is_flaeche_mitte ? 'y' : 'n').'">';

   foreach ($img_arr as $img) {
      $cat_html .= '   <div class="cat_img_wrapper cat_img_wrapper_'.$mode.'">'.CR;
      $img_html = '      <img src="'.$img_url.$img->image.'.jpg'.$this->params->firma['image_cache'].'" alt="'.$img->seo.'" title="'.$img->seo.'" />';

      if ($img->link != '') {
         $cat_html .= '      <a href="'.str_ireplace('[SHOP]', SHOP_URL_IDX, $img->link).'" target="'.($img->intern == 'y' ? '_self' : '_blank').'" title="'.$img->seo.'">'.$img_html.'</a>'.CR;
      }

      else {
         $cat_html .= $img_html.CR;
      }

      $cat_html .= '   </div>';
   }

   $cat_html .= '</div>';
   $cat_html .= '<div class="clear"></div>';
}

$text = explode('[TRENNER]', $data->cat_text);

if (isset($text[1]) && $text[1] != '') {
   $cat_html .= '<div class="content_center_nopad cat_mode'.$mode.($is_flaeche_mitte ? 'full' : '').'">';
   $cat_html .= '   <div class="col_lsl_l text_normal fliesstext">'.\KANPAICLASSIC\Helper::checkTextToggle($text[0]).'</div>';
   $cat_html .= '   <div class="col_lsl_m"></div>';
   $cat_html .= '   <div class="col_lsl_r text_normal fliesstext">'.\KANPAICLASSIC\Helper::checkTextToggle($text[1]).'</div>';
   $cat_html .= '   <div class="clear"></div>';
   $cat_html .= '</div>';
}

else {
   $cat_html .= '<div class="col_single text_normal fliesstext">'.\KANPAICLASSIC\Helper::checkTextToggle($text[0]).'</div>';
}

$cat_html .= '</div>';
$cat_html .= '</div>';
