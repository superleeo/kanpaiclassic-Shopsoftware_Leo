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
$is_flaeche_mitte = false;


// Von Startseite-Template aufgerufen (über articles.class.php
if (isset($this->params)) {
   $is_flaeche_mitte = ($this->params->firma['bildschirmbreit'] == 'n'|| $this->params->firma['kategorien_links'] == 'y' ? false : true);
}

// Via AJAX nachgeladen (params.class.php
else {
   $is_flaeche_mitte = ($this->firma['bildschirmbreit'] == 'n'|| $this->firma['kategorien_links'] == 'y' ? false : true);
}

$mod_sort  = '<div id="mod_sortierung" style="position:relative; width:160px; height:36px; margin-top:-43px; float:right;'.($is_flaeche_mitte ? ' margin-right:3px;' : ' padding-right:3px;').'">';
$mod_sort .= '   <span class="select_wrapper" style="border: 1px solid #ececec;">';
$mod_sort .= '      <span class="selectbox">';
$mod_sort .= '         <select class="" onchange="modSort($(this).val());" style="width:160px; height:30px; margin-bottom:10px; font: 400 12px/29px \'Open Sans\', sans-serif; color:#888888;">';
$mod_sort .= '            <option value="1"'.($_SESSION['module_sortierung'] == 1 ? ' selected="selected"' : '').' style="font: 400 12px/29px \'Open Sans\', sans-serif; color:#888888;">&nbsp;'.$this->text->get('sort', '1').'</option>';
$mod_sort .= '            <option value="2"'.($_SESSION['module_sortierung'] == 2 ? ' selected="selected"' : '').' style="font: 400 12px/29px \'Open Sans\', sans-serif; color:#888888;">&nbsp;'.$this->text->get('sort', '2').'</option>';
$mod_sort .= '            <option value="3"'.($_SESSION['module_sortierung'] == 3 ? ' selected="selected"' : '').' style="font: 400 12px/29px \'Open Sans\', sans-serif; color:#888888;">&nbsp;'.$this->text->get('sort', '3').'</option>';
$mod_sort .= '            <option value="4"'.($_SESSION['module_sortierung'] == 4 ? ' selected="selected"' : '').' style="font: 400 12px/29px \'Open Sans\', sans-serif; color:#888888;">&nbsp;'.$this->text->get('sort', '4').'</option>';
$mod_sort .= '            <option value="5"'.($_SESSION['module_sortierung'] == 5 ? ' selected="selected"' : '').' style="font: 400 12px/29px \'Open Sans\', sans-serif; color:#888888;">&nbsp;'.$this->text->get('sort', '5').'</option>';
$mod_sort .= '         </select>';
$mod_sort .= '      </span>';
$mod_sort .= '   </span>';
$mod_sort .= '</div>';
