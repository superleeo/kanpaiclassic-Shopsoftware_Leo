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
   die ("This file cannot run outside the Kanpai Classic Shopsoftware");
}

// Titelzeile generiren und Listeninhalt einpinden ( _printList() )

// // listmode -> '', artikel -> Normale Artikelliste
// listmode -> bestellungen
// listmode -> zubehoer
// listmode -> aehnliche
// listmode -> mixer
// listmode ->
// listmode ->
// $_SESSION['listcategorie'] -> Artikel einer Kategorie

$listmode        = $this->listmode;
$print_title     = (isset($print_title) ? $print_title : false);
$dir             = (isset($_SESSION['artikel_asc']) ? ($_SESSION['artikel_asc'] == 'asc' ? '-asc' : '-desc') : '-asc');
$dir_fa          = ($dir == '-asc' ? '-up' : '-down');
$suche           = '';
$divname         = '';

$haendler_id     = 0;
$haendler_hidden = '';
$module          = 0;

if (defined('CONF_MODULE_PORTAL')) {
   if ($_SESSION['haendler'] !== 'n') {
      $haendler_hidden = ' style="visibility:hidden;"';
   }
}


// Artikel wird in artikel_liste.tpl.php eingebunden, sonst als Popup
if ($listmode == 'artikel') {
   $divname = 'article_list';
   $suche = '';

   if (defined('CONF_MODULE_EBAY')) {
      $module++;
   }
}

else if ($listmode == 'bestellungen') {
   $divname = 'article_list_best';
   $suche   = '<input class="txt_inp" type="text" id="suche" name="suche" onkeyup="Artikel.articleSearch(this.value);" value="" />';
}

else if ($listmode == 'zubehoer') {
   $divname = 'article_list_zubehoer';
   $suche = '<input class="txt_inp" type="text" id="suche" name="suche" onkeyup="Artikel.articleSearchZ(this.value);" value="" />';
}

else if ($listmode == 'aehnliche') {
   $divname = 'article_list_aehnliche';
   $suche   = '<input class="txt_inp" type="text" id="suche" name="suche" onkeyup="Artikel.articleSearchZ(this.value);" value="" />';
}

else if ($listmode == 'mixer') {
   $divname = 'article_list_mixer';
   $suche   = '<input class="txt_inp" type="text" id="suche" name="suche" onkeyup="Royalart.articleSearchZ(this.value);" value="" />';
}

else if ($listmode == 'livedesigner2') {
   $divname = 'article_list_livedesigner2';
   $suche   = '<input class="txt_inp" type="text" id="suche" name="suche" onkeyup="Royalart.articleSearchZ(this.value);" value="" />';
}

//$this->article_list erstellen
$this->_printList($parent_id);
$html = '';

// Bei Varianten nur Listeninhalt zurückgeben
if ($parent_id > 0) {
   return $this->article_list;
}

if ($print_title) {
   // Titelzeile, wenn nicht als Artikelliste
   if ($listmode !== 'artikel') {
      $html .= '<div id="artikel_popup">'.CR;
      $html .= '<div id="titelzeile2">'.CR;

      // zubehoer / aehnliche Kategorie nicht einschränken
      if (isset($_SESSION['listcategorie']) && $_SESSION['listcategorie'] === true && $listmode != 'zubehoer' && $listmode != 'aehnliche' && $listmode != 'livedesigner2_articles') {
         $html .= '   <div class="x_listcategorie txt_tit"><a class="help_kanpaiclassic" href="'.HELP_LINK.'/kapitel03.html" target="_blank"></a>Artikel für Kategorie '.$_SESSION['listcategorie_catname'].'</div>'.CR;
      }

      else if (($listmode === 'bestellungen')) {
         $html .= '   <div class="x_bestellungen txt_tit">&nbsp;&nbsp;Artikel hinzufügen</div>'.CR;
      }

      else {
         $html .= '   <div class="x_artikel txt_tit"><a class="help_kanpaiclassic" href="'.HELP_LINK.'/kapitel03.html" target="_blank"></a>Artikelliste</div>'.CR;
      }

      $html .= '   <div class="buttons_top_right">'.CR;
      $html .= '      <div id="articleNew" class="button txt_but" onclick="Multibox.close();">schließen</div>'.CR;
      $html .= '   </div>'.CR;
      $html .= '</div>'.CR;
      $html .= '</div>'.CR;
      $html .= '<div class="content_box">'.CR;
   }

   if ($listmode == 'artikel') {
      $html .= '<div class="content_box no_border_top">'.CR;
      $html .= '   <div id="'.$divname.'" class="maincontent article_list"  data-modul_id="'.$this->params->postInt('modul_id').'">'.CR;
      $html .= '      <div id="content_top">'.CR;
      $html .= '         <div class="artikel_suche">'.CR;
      $html .= '            <input class="txt_inp" type="text" id="suche" name="suche" value="" onkeyup="(event.keyCode === 13 ? Artikel.find($(this).val(), 1) : \'\');"/>'.CR;
      $html .= '            <div class="fas fa-search" onclick="Artikel.find($(\'#suche\').val(), 1);"></div>'.CR;
      $html .= '            <div id="find_reset" class="fas fa-power-off" onclick="$(\'#suche\').val(\'\'); Artikel.seite(0);"></div>'.CR;
      $html .= '         </div>'.CR;

      // Neuer Artikel
      $html .= '      <div class="buttons_top_left">'.CR;
      $html .= '         <div id="articleNew" class="button_ci" onclick="location.href=\''.ADMIN_URL_IDX.'/artikel/detail/0\';">neu</div>'.CR;
      $html .= '      </div>'.CR;

      $html .= '      <div class="buttons_top_right">'.CR;
      if (defined('CONF_ART_REORG')) {
         $html .= '         <div id="reorg" class="button" onclick="Artikel.reorg();">reorganisieren</div>'.CR;
      }

      if ($this->bildmode == 'id') {
         $html .= '         <div id="image_id" class="button" onclick="location.href=\''.ADMIN_URL_IDX.'/artikel/imageBild\';">Bild</div>'.CR;
      }

      else {
         $html .= '         <div id="image_id" class="button" onclick="location.href=\''.ADMIN_URL_IDX.'/artikel/imageId\';">ID</div>'.CR;
      }

      $html .= '      </div>'.CR;
    $html .= '   </div>'.CR;
   }

   else {
       $html .= '<div id="'.$divname.'" class="maincontent article_list" data-modul_id="'.$this->params->postInt('modul_id').'">'.CR;
      $html .= '   <div id="content_top">'.CR;
      $html .= '      <div class="artikel_suche">'.CR;
      $html .= '         <input class="txt_inp" type="text" id="suche" name="suche" value="" onkeyup="(event.keyCode === 13 ? Artikel.find($(this).val(), 1) : \'\');"/>'.CR;
      $html .= '         <div class="fas fa-search" onclick="Artikel.find($(\'#suche\').val(), 1);"></div>'.CR;
      $html .= '         <div id="find_reset" class="fas fa-power-off" onclick="$(\'#suche\').val(\'\'); Artikel.seite(0);"></div>'.CR;
      $html .= '      </div>'.CR;
      $html .= '   </div>'.CR;
   }
}

$html .= '   <div id="pager_oben">'.CR;
$html .= '      <div class="pager">'.$this->pager.'</div>'.CR;
$html .= '      <div class="clear"></div>'.CR;
$html .= '      <hr />'.CR;
$html .= '   </div>'.CR;

$html .= '   <div class="mobile_slide">'.CR;
$html .= '      <div id="listcontent" class="mobile_slide_inner" data-modul_id="'.$this->modul_id.'">'.CR;
$html .= '         <div id="art_titel" class="list_title">'.CR;
$html .= '            <div class="art_list_right module_'.$module.'">'.CR;

$html .= '               <div class="fixed_left">'.CR;
// ID
$sort2 = ($_SESSION['artikel_sort'] == 2 ? 'fa-sort'.$dir_fa : 'fa-sort');
$html .= '                  <div class="art_list1 list_col" onclick="Artikel.sort(2);">'.CR;
$html .= '                     <span class="ellipsis pointer txt_bez list_text">ID<span id="art_sort2_symbol" class="list_icon fas '.$sort2.'"></span></span>'.CR;
$html .= '                  </div>'.CR;

// Sortierung
$sort3 = ($_SESSION['artikel_sort'] == 3 ? 'fa-sort'.$dir_fa : 'fa-sort');
$html .= '                  <div class="art_list2 list_col" onclick="Artikel.sort(3);" title="Sortierung">'.CR;
$html .= '                     <span class="ellipsis pointer txt_bez list_text">Sortierung<span id="art_sort3_symbol" class="list_icon fas '.$sort3.'"></span></span>'.CR;
$html .= '                  </div>'.CR;
// Wichtig für Portal
//$html .= "      <div".$haendler_hidden." class='art-tit-sortierung txt_bez' onclick='Royalart.articleSort(4, $haendler_id);'>Sortier.<div id='art-sort4-symbol' class='$sort'></div></div>\n";

$sort4 = ($_SESSION['artikel_sort'] == 4 ? 'fa-sort'.$dir_fa : 'fa-sort');
$html .= '                  <div class="art_list3 list_col" onclick="Artikel.sort(4);">'.CR;
$html .= '                     <span class="ellipsis pointer txt_bez list_text">Art-Nr<span id="art_sort4_symbol" class="list_icon fas '.$sort4.'"></span></span>'.CR;
$html .= '                  </div>'.CR;
$html .= '               </div>'.CR;


$sort5 = ($_SESSION['artikel_sort'] == 5 ? 'fa-sort'.$dir_fa : 'fa-sort');
$html .= '               <div class="art_list4 list_col" onclick="Artikel.sort(5);">'.CR;
$html .= '                  <span class="ellipsis pointer txt_bez list_text">Artikel<span id="art_sort5_symbol" class="list_icon fas '.$sort5.'"></span></span>'.CR;
$html .= '               </div>'.CR;

$html .= '               <div class="art_list5 list_col ellipsis txt_bez">Wert 1</div>'.CR;
$html .= '               <div class="art_list6 list_col ellipsis txt_bez">Wert 2</div>'.CR;

$html .= '               <div class="fixed_right">'.CR;
$html .= '                  <div class="art_list7 list_col ellipsis txt_bez">Preis netto</div>'.CR;
$html .= '                  <div class="art_list8 list_col ellipsis txt_bez" title="Angebot netto">Angebot netto</div>'.CR;
$html .= '                  <div class="art_list9 list_col ellipsis txt_bez">Preis brutto</div>'.CR;

$sort6 = ($_SESSION['artikel_sort'] == 6 ? 'fa-sort'.$dir_fa : 'fa-sort');
$html .= '                  <div class="art_list10 list_col" onclick="Artikel.sort(6);">'.CR;
$html .= '                     <span class="ellipsis pointer txt_bez list_text">Lager<span id="art_sort6_symbol" class="list_icon fas '.$sort6.'"></span></span>'.CR;
$html .= '                  </div>'.CR;
$html .= '               </div>'.CR;
$html .= '               <div class="clear"></div>'.CR;
$html .= '            </div>'.CR;

$html .= '            <div class="art_list_left">'.CR;
$html .= '               <div class="art_list_show">'.CR;

// Online
$sort1 = ($_SESSION['artikel_sort'] == 1 ? 'fa-sort'.$dir_fa : 'fa-sort');
$html .= '                  <div class="list_col" onclick="Artikel.sort(1);">'.CR;
$html .= '                     <span class="ellipsis pointer txt_bez list_text">Online<span id="art_sort1_symbol" class="list_icon fas '.$sort1.'"></span></span>'.CR;
$html .= '                  </div>'.CR;

$html .= '                  <div class="clear"></div>'.CR;
$html .= '               </div>'.CR;
$html .= '            </div>'.CR;

$html .= '            <div class="art_list_extra module_'.$module.'"></div>'.CR;
$html .= '         </div>'.CR;

$html .= '         <div id="artikelList" data-listmode="'.$listmode.'" data-hendler_id="'.$haendler_id.'">'.CR;
$html .= $this->article_list;
$html .= '         </div>'.CR;
$html .= '      </div>'.CR; // listcontent / mobile_slide_inner
$html .= '   </div>'.CR;

$html .= '   <div id="pager_unten">'.CR;
$html .= '      <div class="pager">'.$this->pager.'</div>'.CR;
$html .= '   </div>'.CR;

if ($print_title) {
   if ($listmode == 'artikel') {
      if (defined('CONF_MODULE_PDFKATALOG')) {
         $html .= '   <div id="pdfkatalog">'.CR;
         $html .= '      <span class="pdfkatalog_config" onclick="Artikel.pdfkatalogPopup();"><span class="list_edit fas fa-pencil-alt pointer"></span></span>'.CR;
         $html .= '      <span class="pdfkatalog" onclick="location.href=\''.ADMIN_URL_IDX.'/ajax/artikel/pdfkatalogPrint\';"></span>'.CR;
         $html .= '      <span class="pdfkatalog_text txt_tit">PDF-Katalog</span>'.CR;
         $html .= '   </div>'.CR;
      }
   }
}

else {
   $html .= '</div>' . CR;
}

$html .= '</div>'.CR;
$html .= '</div>'.CR;
