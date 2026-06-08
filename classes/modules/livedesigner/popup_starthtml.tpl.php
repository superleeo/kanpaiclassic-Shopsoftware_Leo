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
$html .= '<div id="starthtml">'.CR;
$html .= '   <div class="txt_tit">'.CR;
$html .= '      <span class="checkbox">'.CR;
$html .= '         <input type="checkbox" class="newdesign" id="starthtml_on"'.($this->json['starthtml_on'] == 'y' ? ' checked="checked"' : '').' />'.CR;
$html .= '         <label for="starthtml_on"></label>'.CR;
$html .= '      </span>'.CR;
$html .= '      Startseitentext'.CR;
$html .= '   </div>'.CR;

$html .= '   <div class="editor_wrapper">'.CR;
$html .= '      <textarea class="edit_breit" id="starthtml_text" name="starthtml_text" rows="10" cols="100">'.$text_array[0].'</textarea>'.CR;
$html .= '   </div>'.CR;

$html .= '   <div class="buttonzeile">'.CR;
$html .= '      <div class="button button_left" onclick="Multibox.close();">abbrechen</div>'.CR;
$html .= '      <div class="button_ci button_center" onclick="Livedesigner.saveStarthtml();">speichern</div>'.CR;
$html .= '   </div>'.CR;
$html .= '</div>'.CR;
