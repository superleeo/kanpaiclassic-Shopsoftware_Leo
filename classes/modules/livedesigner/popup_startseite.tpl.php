<?php
/*
###################################################################################
  Kanpai Classic Shopsoftware Entwicklungsstand: 09.02.2020 Version 2

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
$html .= '<div id="popup_startseite">'.CR;
$html .= '   <h2 class="txt_tit">Elemente</h2><br />'.CR;
$html .= '   <div class="telefon">'.CR;
$html .= '      <input type="checkbox" class="newdesign" id="ld_telefon_on" name="ld_telefon_on"'.(\KANPAICLASSIC\Helper::getData('call_check', 'n') == 'y' ? ' checked="checked"' : '').' />'.CR;
$html .= '      <label for="ld_telefon_on"></label>'.CR;
$html .= '      <span class="call_me_icn"></span>'.CR;
$html .= '   </div>'.CR;

$html .= '   <div class="line">'.CR;
$html .= '      <span class="sort"></span>'.CR;
$html .= '      <span class="checkbox">'.CR;
$html .= '         <input type="checkbox" class="newdesign" id="slideshow_on"'.($this->json['slideshow_on'] == 'y' ? ' checked="checked"' : '').' />'.CR;
$html .= '         <label for="slideshow_on"></label>'.CR;
$html .= '      </span>'.CR;
$html .= '      <span class="edit pointer fas fa-pencil-alt" onclick="Livedesigner.popupSlideshow()"></span>'.CR;
$html .= '      <span class="name">Slideshow</span>'.CR;
$html .= '      <div class="clear"></div>'.CR;
$html .= '   </div>'.CR;

$html .= '   <div class="line">'.CR;
$html .= '      <span class="sort"></span>'.CR;
$html .= '      <span class="checkbox">'.CR;
$html .= '         <input type="checkbox" class="newdesign" id="starthtml_on"'.($this->json['starthtml_on'] == 'y' ? ' checked="checked"' : '').' />'.CR;
$html .= '         <label for="starthtml_on"></label>'.CR;
$html .= '      </span>'.CR;
$html .= '      <span class="edit pointer fas fa-pencil-alt" onclick="Livedesigner.popupStarthtml()"></span>'.CR;
$html .= '      <span class="name">Startseitentext</span>'.CR;
$html .= '      <div class="clear"></div>'.CR;
$html .= '   </div>'.CR;

$html .= '   <div class="line">'.CR;
$html .= '      <span class="sort"></span>'.CR;
$html .= '      <span class="checkbox">'.CR;
$html .= '         <input type="checkbox" class="newdesign" id="collage_on"'.($this->json['collage_on'] == 'y' ? ' checked="checked"' : '').' />'.CR;
$html .= '         <label for="collage_on"></label>'.CR;
$html .= '      </span>'.CR;
$html .= '      <span class="edit pointer fas fa-pencil-alt" onclick="Livedesigner.popupCollage()"></span>'.CR;
$html .= '      <span class="name">Kollage</span>'.CR;
$html .= '      <div class="clear"></div>'.CR;
$html .= '   </div>'.CR;

if (defined('CONF_MODULE_EXTENDED')) {
   $html .= '   <div class="line">'.CR;
   $html .= '      <span class="sort"></span>'.CR;
   $html .= '      <span class="checkbox">'.CR;
   //$html .= '         <input type="checkbox" class="newdesign" id="collage_on"'.($this->json['collage_on'] == 'y' ? ' checked="checked"' : '').' />'.CR;
   //$html .= '         <label for="collage_on"></label>'.CR;
   $html .= '         <span class="help ci_color" title="ab August gibt es den Livedesigner 2"></span>'.CR;
   $html .= '      </span>'.CR;
   $html .= '      <span class="edit pointer fas fa-pencil-alt" onclick="window.close(\'extended\'); Design.extended = window.open(\''.ADMIN_URL_IDX.'/designExtended\', \'extended\');"></span>'.CR;
   $html .= '      <span class="name ellipsis">Accordion, Karussell, Artikelslider</span>'.CR;
   $html .= '      <div class="clear"></div>'.CR;
   $html .= '   </div>'.CR;
}

// $html .= '   <div class="line"></div>'.CR;

$html .= '   <div class="line">'.CR;
$html .= '      <span class="sort"></span>'.CR;
$html .= '      <span class="checkbox">'.CR;
$html .= '         <input type="checkbox" class="newdesign" id="artikelliste_on"'.($this->json['artikelliste_on'] == 'y' ? ' checked="checked"' : '').' />'.CR;
$html .= '         <label for="artikelliste_on"></label>'.CR;
$html .= '      </span>'.CR;
$html .= '      <span class="edit pointer fas fa-pencil-alt" onclick="Livedesigner.popupArtikel()"></span>'.CR;
$html .= '      <span class="name">Artikelliste</span>'.CR;
$html .= '      <div class="clear"></div>'.CR;
$html .= '   </div>'.CR;

$html .= '   <div class="line">'.CR;
$html .= '      <span class="sort"></span>'.CR;
$html .= '      <span class="checkbox">'.CR;
$html .= '         <input type="checkbox" class="newdesign" id="bannerunten_on"'.($this->json['bannerunten_on'] == 'y' ? ' checked="checked"' : '').' />'.CR;
$html .= '         <label for="bannerunten_on"></label>'.CR;
$html .= '      </span>'.CR;
$html .= '      <span class="edit pointer fas fa-pencil-alt" onclick="Livedesigner.popupBannerunten()"></span>'.CR;
$html .= '      <span class="name">Banner unten (permanent)</span>'.CR;
$html .= '      <div class="clear"></div>'.CR;
$html .= '   </div>'.CR;

$html .= '   <div class="buttonzeile">'.CR;
$html .= '      <div class="button button_left" onclick="Multibox.close();">abbrechen</div>'.CR;
$html .= '      <div class="button_ci button_center" onclick="Livedesigner.saveStartseite();">speichern</div>'.CR;
$html .= '   </div>'.CR;

$html .= '</div>'.CR;
