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

$html .= '<div id="wk_popup">'.CR;

// Popup mit Zubehör
if ($breite > 700 && $anzahl > 0) {
   // Wird gelöscht und an anderer Stelle per JS neu erstellt. Nur Test, ob vorhanden
   $html .= '   <div id="wk_popup_trenner" class="bg_innen bg_innen_no_trans"></div>'.CR;
   $html .= '   <div class="text_max ueberschrift" style="text-align:center; line-height:50px; height:60px">'.$this->text->get('article', 'popup').'</div>'.CR;
   $html .= '   <div class="text_max wk_popup_pics_top ueberschrift">'.$zubehoer_text.'</div>'.CR;

   $html .= '   <div id="popup_container" style="min-height:30px;">'.CR;
   $html .=        $zubehoer[0];
   $html .= '   </div>'.CR;

   $html .= '   <div class="text_max wk_popup_pics ueberschrift" onclick="multibox.close(); scrollto($(\'#zubehoer\'));">'.$this->text->get('button', 'weiter').'</div>'.CR;
}

// Ohne Artikel
else {
   $html .= '   <div class="text_max ueberschrift" style="text-align:center; padding-top:118px; padding-bottom:117px; line-height:25px;">'.$this->text->get('article', 'popup').'</div>';
}

// Buttons
$html .= '   <div class="col_single multibox_2_buttons">';

if (defined('CONF_WEITEREINKAUFEN')) {
   $html .= '      <div class="col_ll_l multibox_button_left">';
   $html .= '         <div class="button button55 col_button bg_button text_gross" onclick="multibox.close();">'.$this->text->get('button', 'einkaufen').'</div>';
   $html .= '      </div>';
}

else {
   $html .= '      <div class="col_ll_l multibox_button_left">';
   $html .= '         <div class="button button55 col_button bg_button text_gross" onclick="location.href=\''.$_SESSION['last_link'].'\';">'.$this->text->get('button', 'einkaufen').'</div>';
   $html .= '      </div>';
}

$html .= '      <div class="col_ll_r multibox_button_right">';
$html .= '         <div class="button button55 col_button bg_button text_gross" onclick="location.href = \''.SHOP_URL_IDX.'/warenkorb\';">'.$this->text->get('button', 'ansehen').'</div>';
$html .= '      </div>';
$html .= '      <div class="clear"></div>';
$html .= '   </div>';
$html .= '</div>';

$html .= '<script>'.CR;
$html .= '   $("#popup_container").cubeportfolio(cubeportfolioOptions_popup);'.CR;
$html .= '   resizeContent();'.CR;
$html .= '   multibox.resize();'.CR;
$html .= '</script>'.CR;
