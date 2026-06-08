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

$mode               = $this->postString('mode');
$html               = '';
$show_lang          = ($this->postString('show_lang') != '' ? $this->postString('show_lang') : $this->selected_lang);
$cookie_settings    = \KANPAICLASSIC\Helper::cookieSettings($show_lang);

$cookie_wesentlich  = true;
$cookie_social      = (isset($_SESSION['cookie_social']) && $_SESSION['cookie_social'] ? true : false);
$cookie_marketing   = (isset($_SESSION['cookie_marketing']) && $_SESSION['cookie_marketing'] ? true : false);
$cookie_funktionell = (isset($_SESSION['cookie_funktionell']) && $_SESSION['cookie_funktionell'] ? true : false);
$langs              = explode(';', $this->firma['langs']);
$reload             = ($this->postCheckbox('reload') == 'y' ? true : false);

if (!$reload) {
   $html .= '<div id="cookie_popup">'.CR;
   $html .= '   <div class="cookie_header">'.CR;
   $html .= '      <div class="cookie_header_symbol"></div>'.CR;
   $html .= '      <div class="cookie_header_title text_gross">Cookie Settings</div>'.CR;
//   $html .= '      <div class="cookie_header_close pointer" onclick="multibox.close();"></div>'.CR;
   $html .= '   </div>'.CR;

   $html .= '   <div id="cookie_main">'.CR;
}

$html .= '      <div id="cookie_lang" data-speichern="'.$this->text->get('button', 'speichern', $show_lang).'">'.CR;

foreach ($langs as $l) {
   if ($l == 'deu' || $l == 'eng') {
      $html .= '         <span class="flagge pointer flagge_'.$l.'-over" onclick="Cookies.reload(\''.$l.'\');"></span>'.CR;
   }

   else {
      $html .= '         <span class="flagge pointer flagge_'.$l.'-over"
                             onclick="Cookies.reload(\''.$l.'\');"
                             onmouseover="this.style.backgroundImage = \'url('.TEMPLATE_URL.'/images/flaggen/'.$l.'.jpg\');"
                             onmouseout="this.style.backgroundImage  = \'url('.TEMPLATE_URL.'/images/flaggen/'.$l.'-over.jpg\');"
                             style="background-image:url(\''.\TEMPLATE_URL.'/images/flaggen/'.$l.'-over.jpg\');"></span>'.CR;
   }
}

$html .= '      </div>'.CR;

$html .= '      <div class="cookie_block">'.CR;
$html .= '         <div class="cookie_text pointer" onclick="$(\'.cookie_open_close\', $(this).closest(\'.cookie_block\')).click();">essentiell</div>'.CR;
$html .= '         <div class="cookie_open_close pointer fas fa-caret-right" onclick="Cookies.toggle(this);"></div>'.CR;
$html .= '         <div class="cookie_check"><input type="checkbox" id="cookie_wesentlich_check" '.($cookie_wesentlich ? 'checked="checked"' : '').' disabled="disabled" /><span class="checkbox"></span></div>'.CR;
$html .= '         <div class="cookie_open" style="display:none">'.nl2br($cookie_settings->wesentlich_text).'</div>'.CR;
$html .= '      </div> '.CR;

if ($this->firma['cookie_check'] != 'n') {
   $html .= '      <div class="cookie_block">'.CR;
   $html .= '         <div class="cookie_text pointer" onclick="$(\'.cookie_open_close\', $(this).closest(\'.cookie_block\')).click();">Social Media</div>'.CR;
   $html .= '         <div class="cookie_open_close pointer fas fa-caret-right" onclick="Cookies.toggle(this);"></div>'.CR;
   $html .= '         <div class="cookie_check"><input type="checkbox" id="cookie_social_check" '.($cookie_social ? 'checked="checked"' : '').' /><span class="checkbox"></span></div>'.CR;
   $html .= '         <div class="cookie_open" style="display:none">'.nl2br($cookie_settings->social_text).'</div>'.CR;
   $html .= '      </div>'.CR;
}

if ($cookie_settings->marketing_title != '') {
   $html .= '      <div class="cookie_block">'.CR;
   $html .= '         <div class="cookie_text pointer" onclick="$(\'.cookie_open_close\', $(this).closest(\'.cookie_block\')).click();">'.$cookie_settings->marketing_title.'</div>'.CR;
   $html .= '         <div class="cookie_open_close pointer fas fa-caret-right" onclick="Cookies.toggle(this);"></div>'.CR;
   $html .= '         <div class="cookie_check"><input type="checkbox" id="cookie_marketing_check" '.($cookie_marketing ? 'checked="checked"' : '').' /><span class="checkbox"></span></div>'.CR;
   $html .= '         <div class="cookie_open" style="display:none">'.nl2br($cookie_settings->marketing_text).'</div>'.CR;
   $html .= '      </div>'.CR;
}

if ($cookie_settings->funktionell_title != '') {
   $html .= '      <div class="cookie_block">'.CR;
   $html .= '         <div class="cookie_text pointer" onclick="$(\'.cookie_open_close\', $(this).closest(\'.cookie_block\')).click();">'.$cookie_settings->funktionell_title.'</div>'.CR;
   $html .= '         <div class="cookie_open_close pointer fas fa-caret-right" onclick="Cookies.toggle(this);"></div>'.CR;
   $html .= '         <div class="cookie_check"><input type="checkbox" id="cookie_funktionell_check" '.($cookie_funktionell ? 'checked="checked"' : '').' /><span class="checkbox"></span></div>'.CR;
   $html .= '         <div class="cookie_open" style="display:none">'.nl2br($cookie_settings->funktionell_text).'</div>'.CR;
   $html .= '      </div>'.CR;
}

$html .= '      <div class="cookie_block cookie_block center">'.CR;
$html .= '         <a class="" href="'.SHOP_URL_IDX.'/datenschutz/'.$show_lang.'/" target="_blank">'.\KANPAICLASSIC\Helper::getSeite('datenschutz', $show_lang).'</a>'.CR;
$html .= '      </div>'.CR;


if (!$reload) {
   $html .= '   </div>'.CR;

   $html .= '   <div id="cookie_speichern" class="cookie_footer text_gross pointer button_ovr col_button" onclick="Cookies.cookieSave();">Speichern</div>'.CR;
   $html .= '</div>'.CR;
}

return $html;