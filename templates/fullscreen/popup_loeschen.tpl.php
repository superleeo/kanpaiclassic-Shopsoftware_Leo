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


$html .= '<div class="text_max fliesstext" style="text-align:center; padding:10px 0; line-height:50px;">'.$text->get('popup', 'loeschen').'</div>'.CR;
$html .= '<div style="position:relative;">';
$html .= '   <div style="display:inline-block; width:50%; float:left; box-sizing:border-box; padding-right:0.5px;">'.CR;
$html .= '     <div class="bg_button col_button text_max flieestext button55" style="display:inline-block; width:100%; text-align:center;" onclick="multibox.close();">'.$text->get('button', 'abbruch').'</div>'.CR;
$html .= '   </div>';
$html .= '   <div style="display:inline-block; width:50%; float:left; box-sizing:border-box; padding-left:0.5px;">'.CR;
$html .= '      <div class="bg_button col_button text_max fliesstext button55" style="display:inline-block; width:100%; text-align:center;" onclick="location.href=\''.SHOP_URL_IDX.'/delete\';">'.$text->get('button', 'loeschen').'</div>'.CR;
$html .= '   </div>';
$html .= '   <div class="clear"></div>';
$html .= '</div>';
