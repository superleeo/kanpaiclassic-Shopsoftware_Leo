<?php
/*
###################################################################################
  Kanpai Classic Shopsoftware
  Entwicklungsstand: 12.03.2018 Version 6.2

  Kanpai Classic - Web Development
  https://www.kanpaiclassic.com
  https://www.kanpaiclassic.com
  
  (c) Copyright by Kanpai Classic - Kanpai Classic Web Development

  Copyrightvermerke duerfen NICHT entfernt werden!
  ------------------------------------------------------------------------
  Bei Verstoß gegen die Lizenzbedingungen kann die Lizenz jederzeit entzogen werden.
  Der Kaufpreises wird nicht erstattet. Wer gegen die Lizenzbedingungen verstoesst, muss
  mit einer Vertragsstrafe von 50.000 Euro je Einzeldelikt rechnen!
  ------------------------------------------------------------------------
  Dieses Programm ist eine Software von Kanpai Classic, Kanpai Classic Web Development.
  Diese Software darf nicht veroeffentlicht, weitergeben und/oder modifizieren werden.
  Es gelten die Ihnen mitgeteilten Lizenzbestimmungen.
  Diese Software/Website ist eine Einzellizenz und für den Betrieb auf einem Speicherplatz
  (Webspace) berechtigt.
  Die Veroeffentlichung dieses Programms erfolgt OHNE IRGENDEINE GARANTIE, sogar ohne
  die implizite Garantie der MARKTREIFE oder der VERWENDBARKEIT FUER EINEN BESTIMMTEN ZWECK.

##################################################################################
  Copyrightvermerke duerfen NICHT entfernt werden!
*/

if (!isset($_SESSION['module_sortierung'])) {
//   $_SESSION['module_sortierung'] = 1;
} 

$mod_sort  = '<div id="mod_sortierung" style="position:absolute; right:0; top:10px; width:165px;">';
$mod_sort .= '   <span class="select_wrapper" style="border: 2px solid #ECECEC;">';
$mod_sort .= '      <span class="selectbox">';
$mod_sort .= '         <select class="" onchange="modSort($(this).val());" style="width:160px; margin-bottom:10px; font: 400 12px/29px \'Open Sans\', sans-serif; color:#888888;">';
$mod_sort .= '            <option value="1"'.($_SESSION['module_sortierung'] == 1 ? ' selected="selected"' : '').' style="font: 400 12px/29px \'Open Sans\', sans-serif; color:#888888;">&nbsp;'.$this->text->get('sort', '1').'</option>';
$mod_sort .= '            <option value="2"'.($_SESSION['module_sortierung'] == 2 ? ' selected="selected"' : '').' style="font: 400 12px/29px \'Open Sans\', sans-serif; color:#888888;">&nbsp;'.$this->text->get('sort', '2').'</option>';
$mod_sort .= '            <option value="3"'.($_SESSION['module_sortierung'] == 3 ? ' selected="selected"' : '').' style="font: 400 12px/29px \'Open Sans\', sans-serif; color:#888888;">&nbsp;'.$this->text->get('sort', '3').'</option>';
$mod_sort .= '            <option value="4"'.($_SESSION['module_sortierung'] == 4 ? ' selected="selected"' : '').' style="font: 400 12px/29px \'Open Sans\', sans-serif; color:#888888;">&nbsp;'.$this->text->get('sort', '4').'</option>';
$mod_sort .= '            <option value="5"'.($_SESSION['module_sortierung'] == 5 ? ' selected="selected"' : '').' style="font: 400 12px/29px \'Open Sans\', sans-serif; color:#888888;">&nbsp;'.$this->text->get('sort', '5').'</option>';
$mod_sort .= '         </select>';
$mod_sort .= '      </span>';
$mod_sort .= '   </span>';
$mod_sort .= '</div>';
