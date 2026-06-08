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

$html .= '<div id="popup_smtp">'.CR;
$html .= '   <div class="title txt_tit">E-Mailversand</div>'.CR;

$html .= '   <div class="smtp">'.CR;
$html .= '      <div class="smtp_left">'.CR;
$html .= '         <div class="check">'.CR;
$html .= '            <input type="radio" class="newdesign" id="smtp_check1" name="smtp_check"'.($smtp_check == 'n' ? ' checked="checked"' : '').' / >'.CR;
$html .= '            <label for="smtp_check1"></label>'.CR;
$html .= '         </div>'.CR;
$html .= '         <span class=txt_bez>per sendMail</span>'.CR;
$html .= '      </div>'.CR;
$html .= '      <div class="smtp_right">&nbsp;</div>'.CR;
$html .= '      <div class="clear"></div>'.CR;
$html .= '   </div>'.CR;

$html .= '   <div class="smtp_text1">Hinweis: Nur verwenden, wenn sich die Domain Ihrer E-Mail auf Ihrem Server befindet.</div>'.CR;

$html .= '   <div class="smtp">'.CR;
$html .= '      <div class="smtp_left">'.CR;
$html .= '         <div class="check">'.CR;
$html .= '            <input type="radio" class="newdesign" id="smtp_check2" name="smtp_check"'.($smtp_check == 'y' ? ' checked="checked"' : '').' / >'.CR;
$html .= '            <label for="smtp_check2"></label>'.CR;
$html .= '         </div>'.CR;
$html .= '         <span class=txt_bez>per SMTP</span>'.CR;
$html .= '      </div>'.CR;
$html .= '      <div class="smtp_right">'.CR;
$html .= '      </div>'.CR;
$html .= '      <div class="clear"></div>'.CR;
$html .= '   </div>'.CR;

$html .= '   <div class="smtp">'.CR;
$html .= '      <div class="smtp_left">'.CR;
$html .= '         <div class="check"></div>'.CR;
$html .= '         <span class=fliesstext>Benutzer</span>'.CR;
$html .= '      </div>'.CR;
$html .= '      <div class="smtp_right">'.CR;
$html .= '        <input type="text" id="smtp_user" value="'.$smtp_user.'" />'.CR;
$html .= '      </div>'.CR;
$html .= '      <div class="clear"></div>'.CR;
$html .= '   </div>'.CR;

$html .= '   <div class="smtp">'.CR;
$html .= '      <div class="smtp_left">'.CR;
$html .= '         <div class="check"></div>'.CR;
$html .= '         <span class=fliesstext>Passwort</span>'.CR;
$html .= '      </div>'.CR;
$html .= '      <div class="smtp_right">'.CR;
$html .= '        <input type="password" id="smtp_pass" class ="pw_auge" value="'.$smtp_pass.'" />'.CR;
$html .= '         <span class="pw_auge_show far fa-eye" onclick="Shopinhaber.showPass(this);"></span>'.CR;
$html .= '      </div>'.CR;
$html .= '      <div class="clear"></div>'.CR;
$html .= '   </div>'.CR;

$html .= '   <div class="smtp">'.CR;
$html .= '      <div class="smtp_left">'.CR;
$html .= '         <div class="check"></div>'.CR;
$html .= '         <span class=fliesstext>Serverhost</span>'.CR;
$html .= '      </div>'.CR;
$html .= '      <div class="smtp_right">'.CR;
$html .= '         <input type="text" id="smtp_server" value="'.$smtp_server.'" />'.CR;
$html .= '      </div>'.CR;
$html .= '      <div class="clear"></div>'.CR;
$html .= '   </div>'.CR;

$html .= '   <div class="smtp">'.CR;
$html .= '      <div class="smtp_left">'.CR;
$html .= '         <div class="check"></div>'.CR;
$html .= '         <span class=fliesstext>Port</span>'.CR;
$html .= '      </div>'.CR;
$html .= '      <div class="smtp_right">'.CR;
//$html .= '         <input type="text" id="smtp_port" value="'.$smtp_port.'" />'.CR;
$html .= '         <span class="selectbox30">'.CR;
$html .= '            <select id="smtp_port" value="'.$smtp_port.'">'.CR;
$html .= '              <option value=""'.($smtp_port == '' ? 'selected="selected"' : '').'>Unverschlüsselt (25)</option>'.CR;
$html .= '              <option value="ssl"'.($smtp_port == 'ssl' ? 'selected="selected"' : '').'>SSL (465)</option>'.CR;
$html .= '              <option value="tls"'.($smtp_port == 'tls' ? 'selected="selected"' : '').'>TLS (587)</option>'.CR;
$html .= '            </select>'.CR;
$html .= '         </span>'.CR;
$html .= '         <div class="clear"></div>'.CR;
$html .= '      </div>'.CR;
$html .= '      <div class="clear"></div>'.CR;
$html .= '   </div>'.CR;

//$html .= '   <div class="smtp_text2">Hinweis: Wert für SMTP_SECURE wird nicht geprüft. Valide Werte sind ausschließlich "ssl" und "tls", falls Verschlüsselung vorliegt.</div>'.CR;

$html .= '   <div class="buttonzeile">'.CR;
$html .= '      <div class="button button_left txt_but" onclick="Multibox.close();">abbrechen</div>'.CR;
$html .= '      <div class="button_ci button_right txt_but" onclick="Shopinhaber.popupSmtpSave();">speichern</div>'.CR;
$html .= '      <div class="clear"></div>'.CR;
$html .= '   </div>'.CR;
$html .= '</div>'.CR;