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

//$obj = (isset($params) ? $params : $this);
$txt = KANPAICLASSIC\Control::getText();

if (!defined('OBADJA')) {
   die ("This file cannot run outside the Kanpai Classic Shopsoftware");
}

$html .= '<div class="padding_top content_center">';
//$html .= '<div class="bg_innen" style="padding:10px; position:relative; top:10px;">';
$html .= '<div class="col_inner bg_flaechen">';

if (isset($_SESSION['alter_failed'])) {
   $html .= '<div class="col_single_center">';
   $html .= '   <h1 class="ueberschrift text_max center">Alter nicht erreicht</h1>';
   $html .= '   <a class="ueberschrift text_max center" href="'.SHOP_URL.'">Startseite</a>';
   $html .= '</div>';
}

else {
   $html .= '   <div class="col_single_center" style="text-align:center;">';
   $html .= '      <div class="ueberschrift text_max center" style="margin-bottom:20px;">Alternachweis über Personalausweis</div>';
   $html .= '      <img src="'.TEMPLATE_URL.'/images/system/personalausweis.png" alt="" />';
   if ($txt->get('kunde', 'perso_txt') != '') {
      $html .= '      <br/><br/><br/><div class="text_normal fliesstext">'.$txt->get('kunde', 'perso_txt').'</div>';
   }
   $html .= '   </div>';

   $html .= '   <div class="col_single_center" style="margin-top:30px; margin-bottom:30px; line-height:36px;">';
   $html .= '      <div class="col_lsl_l ueberschrift text_max" style="text-align:right">'.$txt->get('kunde', 'perso_nr').'</div>';
   $html .= '      <div class="col_lsl_m"></div>';
   $html .= '      <div class="col_lsl_r"><input type="text" style="width:100%;" class="text_formular text_max" name="perso_nr" id="perso_nr" value="" /></div>';
   $html .= '      <div class="clear"></div>';
   $html .= '   </div>';

   $html .= '   <div class="col_single">';
   $html .= '      <div class="col_button bg_button text_gross button55" onclick="checkPerso();">'.$txt->get('button', 'aktualisierenK').'</div>';
   $html .= '   </div>';
   $html .= '   <div class="clear"></div>';

}

$html .= '</div>';
$html .= '</div>';
