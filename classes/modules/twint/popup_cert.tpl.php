<?php
/*
###################################################################################
  Kanpai Classic Shopsoftware Entwicklungsstand: 05.08.2020 Version III 8.0

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

$html .= '<div id="popup_twintcert">'.CR;
$html .= '   <div class="txt_tit" style="margin-bottom:20px;">Twint Zertifikat</div>'.CR;
$html .= '   <div class="" style="line-height:32px; margin-bottom:20px;">'.CR;
$html .= '      <input type="file" id="cert_upload" name="cert_upload" style="position:absolute; width:0px; height:0px;">'.CR;
$html .= '      <div class="button" onclick="$(\'#cert_upload\').click();">Datei wählen</div>'.CR;
$html .= '   </div>'.CR;
$html .= '   <div style="line-height:32px;">'.CR;
$html .= '      <span style="display:inline-block; margin-bottom:20px; padding-right:5px; cursor:help;" title="Passwort wird benötigt, um die Zertifikatsdatei zu entschlüsseln, da sie in einem anderen Format gespeichert werden muss">Passwort</span>'.CR;
$html .= '      <input type="text" style="width:200px;" id="cert_pass" name="cert_pass" value="" />'.CR;
$html .= '   </div>'.CR;

$html .= '   <div class="buttonzeile">'.CR;
$html .= '      <div class="button button_left" onclick="Multibox.close();">abbrechen</div>'.CR;
$html .= '      <div class="button_ci button_center" onclick="Zahlart.twintCertUpload();">speichern</div>'.CR;
$html .= '   </div>'.CR;
$html .= '</div>'.CR;
