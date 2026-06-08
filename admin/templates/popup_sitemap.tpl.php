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
/*
         sitemap_menu     : ($('#sitemap_menu').prop('checked') ? 'on' : 'off'),
         sitemap_agb      : ($('#sitemap_agb').prop('checked') ? 'on' : 'off'),
         sitemap_cat      : ($('#sitemap_cat').prop('checked') ? 'on' : 'off'),
         sitemap_cat_lev1 : ($('#sitemap_cat_lev1').prop('checked') ? 'on' : 'off'),
         sitemap_cat_lev2 : ($('#sitemap_cat_lev2').prop('checked') ? 'on' : 'off'),
         sitemap_articles : ($('#sitemap_articles').prop('checked') ? 'on' : 'off'),
         sitemap_xml      : ($('#sitemap_xml').prop('checked') ? 'on' : 'off')

*/
$html  = '<div id="sitemap_popup">'.CR;
$html .= '   <div class="title txt_tit">Sitemap'.CR;
$html .= '   </div>'.CR;

$html .= '   <div class="sitemap_line">'.CR;
$html .= '      <div class="line_left">'.CR;
$html .= '         <input type="checkbox" class="newdesign" id="sitemap_menu" name="sitemap_menu"'.($this->params->firma['sitemap_menu'] == 'y' ? ' checked="checked"' : '').' />'.CR;
$html .= '         <label for="sitemap_menu"></label>Menü'.CR;
$html .= '      </div>'.CR;

$html .= '      <div class="line_right">'.CR;
$html .= '         <input type="checkbox" class="newdesign" id="sitemap_agb" name="sitemap_agb"'.($this->params->firma['sitemap_agb'] == 'y' ? ' checked="checked"' : '').' />'.CR;
$html .= '         <label for="sitemap_agb"></label>Impressum, AGB etc.'.CR;
$html .= '      </div>'.CR;
$html .= '      <div class="clear"></div>'.CR;
$html .= '   </div>'.CR;
$html .= '   <div class="trenner"></div>'.CR;

$html .= '   <div class="sitemap_line">'.CR;
$html .= '      <input type="checkbox" class="newdesign" id="sitemap_cat" name="sitemap_cat"'.($this->params->firma['sitemap_cat'] == 'y' ? ' checked="checked"' : '').' />'.CR;
$html .= '      <label for="sitemap_cat"></label>Kategorien'.CR;
$html .= '   </div>'.CR;

$html .= '   <div class="sitemap_line">'.CR;
$html .= '      <input type="checkbox" class="newdesign" id="sitemap_cat_lev1" name="sitemap_cat_lev1"'.($this->params->firma['sitemap_cat_lev1'] == 'y' ? ' checked="checked"' : '').' />'.CR;
$html .= '      <label for="sitemap_cat_lev1"></label>Unterkategorien (1. Ebene)'.CR;
$html .= '   </div>'.CR;

$html .= '   <div class="sitemap_line">'.CR;
$html .= '      <input type="checkbox" class="newdesign" id="sitemap_cat_lev2" name="sitemap_cat_lev2"'.($this->params->firma['sitemap_cat_lev2'] == 'y' ? ' checked="checked"' : '').' />'.CR;
$html .= '      <label for="sitemap_cat_lev2"></label>Unterkategorien (2. und weiter Ebenen)'.CR;
$html .= '   </div>'.CR;
$html .= '   <div class="trenner"></div>'.CR;

$html .= '   <div class="sitemap_line">'.CR;
$html .= '      <input type="checkbox" class="newdesign" id="sitemap_articles" name="sitemap_articles"'.($this->params->firma['sitemap_articles'] == 'y' ? ' checked="checked"' : '').' />'.CR;
$html .= '      <label for="sitemap_articles"></label>Artikel'.CR;
$html .= '   </div>'.CR;
$html .= '   <div class="trenner"></div>'.CR;

$html .= '      <div class="sitemap_line">'.CR;
$html .= '         <input type="checkbox" class="newdesign" id="sitemap_title" name="sitemap_title"'.($this->params->firma['sitemap_title'] == 'y' ? ' checked="checked"' : '').' />'.CR;
$html .= '         <label for="sitemap_title"></label>Title-Tag anzeigen'.CR;
$html .= '      </div>'.CR;

$html .= '   <div class="sitemap_line">'.CR;
$html .= '      <input type="checkbox" class="newdesign" id="sitemap_xml" name="sitemap_xml"'.($this->params->firma['sitemap_xml'] == 'y' ? ' checked="checked"' : '').' />'.CR;
$html .= '      <label for="sitemap_xml"></label>sitemap.xml erstellen'.CR;
$html .= '   </div>'.CR;

$html .= '   <div class="buttonzeile">'.CR;
$html .= '      <div class="button_left button txt_but" onclick="Multibox.close();" >abbrechen</div>'.CR;
$html .= '      <div class="button_right button_ci txt_but" onclick="Seiten.savePopupSitemap();" >speichern</div>'.CR;
$html .= '      <div class="clear"></div>'.CR;
$html .= '   </div>'.CR;
$html .= '</div>'.CR;
