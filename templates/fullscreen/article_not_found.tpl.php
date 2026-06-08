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

$html  = '<div class="site_head col_single bg_flaechen">';
if (strpos($artikel_main, 'old') !== false) {
   $html .= '   <div class="ueberschrift text_max">'.$text->get('article', 'fail_old').'</div>';
}
if (strpos($artikel_main, 'deactivated') !== false) {
   $html .= '   <div class="ueberschrift text_max">'.$text->get('article', 'fail_deactivated').'</div>';
}

if ($artikel_main == '') {
   $html .= '   <div class="ueberschrift text_max">'.$text->get('article', 'fail_search').'</div>';
}

$html .= '</div>';

$html .= '<div id="not_found" class="col_single bg_flaechen">';
$html .= '   <div class="col_single_center">';
$html .= '      <form method="post" action='.SHOP_URL.'>';
$html .= '         <button class="line bg_button col_button text_gross button55 center">'.$text->get('button', 'start').'</button>';
$html .= '      </form>';
$html .= '   </div>';
$html .= '</div>';



$artikel_main = $html;
