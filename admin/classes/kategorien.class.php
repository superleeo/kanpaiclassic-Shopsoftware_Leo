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

namespace KANPAICLASSIC;

if (!defined('KANPAICLASSIC')) {
   die ("This file cannot run outside the Kanpai Classic Shopsoftware");
}

if (!defined('CONF_MAX_KAT')) {
   define ('CONF_MAX_KAT', 1000);

}

define('TEXT_KEYT', 'Für google Suchbegriffe eingeben, wichtigste Wörter zuerst');
define('TEXT_DESR', 'Für google Wortgruppen eingeben, wichtigste Wörter zuerst');
define('TEXT_KEYW', 'Hinweis: Google liest Metatag Keywords seit 2003 nicht mehr aus.');

include_once str_replace(['/classes', '/admin',DIRECTORY_SEPARATOR.'classes', DIRECTORY_SEPARATOR.'admin'], '', dirname(__FILE__)).'/classes/base/categories_base.class.php';

class KANPAICLASSIC_kategorien extends KANPAICLASSIC_categoriesBase
{

   private $striche          = [];  // Steuerung Tree-Grafiken
   private $last_maincat;
   private $menudata;
   private $langdata;
   private $parent;
   private $sortierung       = '';

   public  $maincat_id       = 0;
   public  $maincat_name     = '';

   private $maincat_childs   = false;     // Merker für Baum / Hauptkategorie letzes Hauptmenü
   private $cats2del         = [];        // Speicher für Kotegorien zum löschen

   function __construct() {
      parent::__construct();
//      $this->testAjax();
   }

   // Einzige Function, die aufgerufen werden darf
   public function getContent() {
      // Status aktiv / inaktiv ändern
      // 10.05.2019
      if ($this->params->func == 'changeActive') {
         $cat_id = $this->params->postString('cat_id');
         $test   = $this->changeActive($cat_id);

         if ($test != 'error') {
            $this->_delCache();
            $this->sitemap();

            echo json_encode(['status' => 'ok', 'changed' => $test]);
            exit;
         }

         else {
            exit(json_encode(['status' => 'error', 'message' => 'Kategorie nicht vorhanden, bitte Seite neu laden.']));
         }
      }

      // Status Markenfilter ändern
      // 10.05.2019
      else if ($this->params->func == 'changeMarkenfilter') {
         $cat_id = $this->params->postString('cat_id');
         $test   = $this->changeMarkenfilter($cat_id);

         if ($test == 'y' || $test == 'n') {
            $this->_delCache();
            $this->sitemap();

            echo json_encode(['status' => 'ok', 'mode' => $test]);
            exit;
         }

         else {
            echo json_encode(['status' => 'error']);
            exit;
         }
      }

      // Kategorie löschen
      // 20.05.2019
      else if ($this->params->func == 'deleteCat') {
         $this->deleteCat();
      }

      else if ($this->params->func == 'reload') {
         $this->getTree();  // parent class
         $html = $this->renderTree();
         exit(json_encode(['status' => 'ok', 'html' => $html]));
      }

      // Kategorie-Passwort speichern
      // 10.05.2019
      else if ($this->params->func == 'savePassword') {
         $cat_id   = $this->params->postInt('cat_id');
         $cat_pass = $this->params->postString('cat_pass');
         $test     = $this->setCatpass($cat_id, $cat_pass);

         if ($test) {
            $this->_delCache();
            $this->sitemap();

            echo json_encode(['status' => 'ok', 'inhalt' => $test]);
            exit;
         }

         echo json_encode(['status' => 'error', 'message' => "Passwort konnte nicht geändert werden."]);
         exit;
      }

      // Alterskontrolle Kategorie aktiv / inaktiv
      // 10.05.2019
      else if ($this->params->func == 'altercheck') {
         $catid = $this->params->postString('catid');
         $test  = $this->alterCheck($catid);

         if ($test == 'y') {
            $this->_delCache();
            $this->sitemap();

            echo json_encode(['status' => 'on']);
            exit;
         }

         else {
            $this->_delCache();
            $this->sitemap();

            echo json_encode(['status' => 'off']);
            exit;
         }
      }

      // Popup Kategoriefilter anzeigen
      // 10.05.2019
      else if ($this->params->func == 'katfilterPopup') {
         $catfilter = Control::getModuleKategoriefilter();
         $catpopup  = $catfilter->popup($this->params->postInt('cat_id'));
         exit(json_encode(['status' => 'ok', 'html' => $catpopup]));
      }

   // Filter-Popup Selectbox geändert -> Neue Checkbox-Liste laden
   // 10.05.2019
      else if ($this->params->func == 'katfilterWerte') {
         $catfilter = Control::getModuleKategoriefilter();
         $catpopup  = $catfilter->wertelist($this->params->postInt('merkmal_id'), []);

         exit(json_encode(['status' => 'ok', 'html' => $catpopup]));
      }

      // Popup Kategoriefilter speichern
      // 10.05.2019
      else if ($this->params->func == 'katfilterSave') {
         $catfilter = Control::getModuleKategoriefilter();
         $catpopup = $catfilter->save();
         $this->_delCache();
         $this->sitemap();

         $cat_id = $_POST['cat_id'];
         $filter_active = $this->db->querySingleValue("SELECT filter_active FROM #__categories WHERE id = '$cat_id'");

         exit(json_encode(['status' => $catpopup, 'filter_active'=>$filter_active, 'cat_id'=>$cat_id]));

      }

      // Kategorien exportieren
      else if ($this->params->func == 'exportCatXml') {
         $imex = Control::getImportExport();
         $imex->exportCategoriesXML();
         return;
      }

      // Kategorien importieren - Seite neu laden
      else if ($this->params->func == 'importCatXml') {
         $this->_delCache();
         $imex = Control::getImportExport();
         $imex->importCategoriesXML();
         return;
      }

      // Kategorien-Liste Modul Altercheck
      else if ($this->params->func == 'savePersocheck') {
         $this->savePersocheck();
         // $categories = $this->getTree();
         $this->_delCache();
         $this->sitemap();

         header('Location: '.ADMIN_URL_IDX.'/kategorien');
         // include 'templates/kategorie_liste.tpl.php';

         exit;
      }

      else if ($this->params->func == 'saveBreadcrumbs') {
          $this->saveBreadcrumbs();
         // $categories = $this->getTree();
         $this->_delCache();
         $this->sitemap();

         header('Location: '.ADMIN_URL_IDX.'/kategorien');
         // include 'templates/kategorie_liste.tpl.php';

         exit;
      }

/* ********** Kategorien Details ********** */
      // Kategori-Detail laden
      // 29.04.2019
      else if ($this->params->func == 'details') {
         $cat_id = (isset($this->params->add_params[0]) ? (int)$this->params->add_params[0] : 0);
         $this->details($cat_id);
         return;
      }

      // Details-Seite speichern
      // 30.04.2019
      else if ($this->params->func == 'detailSave') {
         $this->_delCache();
         $this->detailSave();
         $this->sitemap();
         // exit in Funktion
      }

      // Bilder Kategorein oder Kategorie-Mixer speichern
      // 15.05.2019
      else if ($this->params->func == 'imageUpload') {
         $this->imageUpload();
         return;
      }

      // Bilder Kategorein Laden
      // 15.05.2019
      else if ($this->params->func == 'bildRefresh') {
         $this->bildRefresh();
         return;
      }

      // Bilder Kategorein Laden
      // 15.05.2019
      else if ($this->params->func == 'bildDelete') {
         $this->bildDelete();
         return;
      }

      // Bilder Kategorein Laden
      // 15.05.2019
      else if ($this->params->func == 'bildSort') {
         $this->bildSort();
         return;
      }

      // Bilder Kategorein Laden
      // 15.05.2019
      else if ($this->params->func == 'bildSeo') {
         $this->bildSeo();
         return;
      }

      // Bilder Kategorein Laden
      // 15.05.2019
      else if ($this->params->func == 'bildSeoSave') {
         $this->bildSeoSave();
         return;
      }

      // Bilder Kategorein Laden
      // 15.05.2019
      else if ($this->params->func == 'bildColors') {
         $this->bildColors();
         return;
      }

      // Bilder Kategorein Laden
      // 15.05.2019
      else if ($this->params->func == 'bildColorsSave') {
         $this->bildColorsSave();
         return;
      }

      // Bilder Kategorein oder Kategorie-Mixer löschen
      // 15.05.2019
      else if ($this->params->func == 'deleteImg') {
         $this->deleteImg();
         echo json_encode(['status' => 'ok']);
         return;
      }

   // Bilder Kategorein: Links speichern
   // 15.05.2019
      else if ($this->params->func == 'saveLinks') {
         $this->saveLinks();
         exit;
      }

   // Popup Netzkategorie anzeigen
   // 10.05.2019
      else if ($this->params->func == 'networkPopup') {
         $this->networkPopup();
         return;
      }

   // Popup Netzkategorie aktualisieren
   // 10.05.2019
      else if ($this->params->func == 'networkChanged') {
         $this->networkPopup();
         return;
      }

   // Popup Netzkategorie speichern
   // 10.05.2019
      else if ($this->params->func == 'networkSave') {
         $this->networkSave();
         return;
      }

      else if ($this->params->func == 'check_categories') {
         $this->check_categories(0, 0);
         $categories = $this->getTree();
         include 'templates/kategorie_liste.tpl.php';
         return;
      }

      else if ($this->params->func == 'loadCatbox') {
         $data = $this->_catListMaxSub(0, 0, $this->params->postInt('cat_id'), false);
         echo json_encode(['status' => 'ok', 'data' => $data]);
         exit;
      }

      else if ($this->params->isAjax) {
         $categories = $this->getTree();
         echo json_encode(['status' => 1, 'inhalt' => $this->renderTree($categories)]);
      }

      // Kategorie-Liste
      else {
         // Kategoriebaum einlesen (Basisklasse)
         $categories = $this->getTree();  // parent class
         include 'templates/kategorie_liste.tpl.php';
         return;

      }

      return;
   }

   // Kategorien als Baum anzeigen (Kategorie-Liste)
   // 10.05.2019
   private function renderTree($mode = 'open') {
      if ($mode != 'open' && $mode != 'close' && $mode != 'list') {
         $mode = 'open';
      }

      $html         = '';
      $class        = '';
//      $img          = '';
      $level0_max   = count($this->categories);
      $level0_count = 0;

      // Hauptkategorien
      foreach ($this->categories as $level0) {
         $level0_count++;

         if ($level0_count == $level0_max) {
            $this->striche[0] = 1;
         }

         else {
            $this->striche[0] = 0;
         }

         if ((int)$level0[0]['childs'] > 0) {
            $html .= '<div class="catline maincat_childs">';
            $this->maincat_childs = true;
         }

         else {
            if ($this->maincat_childs) {
               $html .= '<div class="catline maincat_new">';
            }

            else {
               $html .= '<div class="catline maincat">';
            }

            $this->maincat_childs = false;

         }

         $html .= '   <div class="symbol_left">'.CR;
         $html .=        $this->_getTreeImage($level0[0], $level0_max, $level0_count);
         // Edit
         $html .= '      <a title="bearbeiten" class="edit list_edit fas fa-pencil-alt" href='.ADMIN_URL_IDX.'/kategorien/details/' . $level0[0]['id'] . '></a>'.CR;

//         if ((int)$level0[0]['childs'] > 0) {
//            $html .= '      <div class="XXXstrich"></div>';
//         }

         // Aktiv
         if ($level0[0]['active'] == 'y') {
            $html .= '      <div title="aktiv/inaktiv" class="active list_gesperrt fas fa-check" onclick="Kategorie.changeActive(this, '.$level0[0]['id'].');"></div>'.CR;
         }

         else {
            $html .= '      <div title="aktiv/inaktiv" class="active list_gesperrt fas fa-times" onclick="Kategorie.changeActive(this, ' . $level0[0]['id'] . ');"></div>'.CR;
         }

         // Löschen
         $html .= '      <div title="löschen" class="delete list_del far fa-trash-alt" onclick="Kategorie.delete(this, ' . $level0[0]['id'] . ');"></div>'.CR;
         $class = 'style="font-weight:bold;"';
         // Name
         $click = 'onclick="$(\'#cat_id\').val('.$level0[0]['id'].'); $(\'#cat_name\').val(\''.$level0[0]['name'].'\'); $(\'#listcategorie\').submit();"';
         $pointer = ' pointer';
         $html .= '      <div title="Sortierung: '.$level0[0]['ordered'].'" '.$click.' class="name ellipsis'.$pointer.'"'.$class.'>'.($level0[0]['name'] != '' ? $level0[0]['name'] : '&nbsp;').($level0[0]['mixer_check'] == 'y' ? ' (*Mixer*)' : '').($level0[0]['childs'] > 0 && $level0[0]['artikel'] > 0 ? '<span title="Artikel enthalten, bitte in Unterkategorie verschieben" style="color:#cc0000; cursor:pointer; font-weight:900;">&nbsp;!&nbsp;&nbsp;&nbsp;</style>' : '').'</div>'.CR;
         $html .= '      <div class="clear"></div>'.CR;
         $html .= '   </div>'.CR;

         $html .= '   <div class="symbol_right">'.CR;
         // Artikel vorhanden
         $html .= '      <div title="'.$level0[0]['artikel'].' Artikel" class="artikel list_artikel fas fa-cube '.((int)$level0[0]['artikel'] == 0 ? 'no_ci_color' : 'ci_color').'" onclick="$(\'#cat_id\').val('.$level0[0]['id'].'); $(\'#cat_name\').val(\''.$level0[0]['name'].'\'); $(\'#listcategorie\').submit();"></div>'.CR;




         // Markenfilter
         if (defined('CONF_MODULE_MARKENFILTER')) {
            $html .= '      <div title="Markenfilter" data-id="'.$level0[0]['id'].'" class="markenfilter list_filter '.($level0[0]['markenfilter'] == 'y' ? 'ci_color' : 'no_ci_color').'" onclick=\'Kategorie.changeMarkenfilter(this);\'></div>'.CR;
         }

         // Merkmalfilter
         if (defined('CONF_MODULE_FILTER')){
             $html .= '      <div title="Merkmalfilter" data-id="'.$level0[0]['id'].'" class="merkmalfilter list_filter '.($level0[0]['filter_active'] == 'y' ? 'ci_color' : 'no_ci_color').'" onclick=\'Kategorie.katfilterPopup('.$level0[0]['id'].');\'></div>';
         }

         // Kategorie-Passwort
         if (defined('CONF_MODULE_KATEGORIEPASS')) {
            $html .= '      <div title="Passwort" data-id="'.$level0[0]['id'].'" class="password list_pass fas fa-'.($level0[0]['cat_pass'] == '' ? 'unlock no_ci_color' : 'lock ci_color').'" onclick=\'Kategorie.changePassword(this);\'><input type="hidden" value="'.$level0[0]['cat_pass'].'" /></div>'.CR;
         }

         // Alter Pflicht
         if (defined('CONF_MODULE_PERSOCHECK') && $this->params->firma['fsk_show'] == 'y') {
            $html .= '      <div title="Alterskontrolle" data-id="'.$level0[0]['id'].'" class="altercheck list_alter '.($level0[0]['alter_check'] == 'y' ? 'altercheck_on' : 'altercheck_off').'" onclick=\'Kategorie.changeAltercheck(this);\'></div>'.CR;
         }

         $html .= '   </div>';
         $html .= '</div>';

         // Unterkategorien
         $html .= '<div id="cat'.$level0[0]['id'].'">';

         $level1_max   = $level0[0]['childs'];
         $level1_count = 0;

         // Hauptkategorie enthält weitere Kategorien (Level 1)
         foreach ($level0 as $level1) {
            $level1_count++;

            if ($level1_count != 1) {
               if ($level1['ordered'] == $level1_max) {
                  $this->striche[$level1['level']] = 1;
               }
               else {
                  $this->striche[$level1['level']] = 0;
               }

               $html .= '<div class="catline subcat">'.CR;
               $html .= '   <div class="symbol_left">'.CR;
               $html .=       $this->_getTreeImage($level1, $level1_max, $level1_count - 1);
               // Edit
               $html .= '      <a title="bearbeiten" class="edit list_edit fas fa-pencil-alt" href='.ADMIN_URL_IDX.'/kategorien/details/'.$level1['id'].'></a>'.CR;

               // Aktiv
               if ($level1['active'] == 'y') {
                  $html .= '      <div title="aktiv/inaktiv" class="active list_gesperrt fas fa-check" onclick="Kategorie.changeActive(this, '.$level1['id'].');"></div>'.CR;
               }

               else {
                  $html .= '      <div title="aktiv/inaktiv" class="active list_gesperrt fas fa-times" onclick="Kategorie.changeActive(this, '.$level1['id'].');"></div>'.CR;
               }

               // Löschen
               $html .= '      <div title="löschen" class="delete list_del far fa-trash-alt" onclick="Kategorie.delete(this, ' . $level1['id'] . ');"></div>'.CR;
               $class = '';
               // Name
               $click   = 'onclick="$(\'#cat_id\').val('.$level1['id'].'); $(\'#cat_name\').val(\''.$level1['name'].'\'); $(\'#listcategorie\').submit();"';
               $pointer = ' pointer';
               $html .= '      <div title="Sortierung: '.$level1['ordered'].'" '.$click.'class="name ellipsis'.$pointer.'"'.$class.'>'.$level1['name'].($level1['mixer_check'] == 'y' ? ' (*Mixer*)' : '').($level1['childs'] > 0 && $level1['artikel'] > 0 ? '<strong><span title="Artikel enthalten, bitte in Unterkategorie verschieben" style="color:#cc0000; cursor:pointer;">&nbsp;!&nbsp;&nbsp;&nbsp;</style></strong>' : '').'</div>'.CR;
               $html .= '      <div class="clear"></div>'.CR;
               $html .= '   </div>'.CR;

               $html .= '   <div class="symbol_right">'.CR;
               // Artikel vorhanden
               $html .= '      <div class="artikel list_artikel fas fa-cube '.((int)$level1['artikel'] == 0 ? 'no_ci_color' : 'ci_color').'" onclick="$(\'#cat_id\').val('.$level1['id'].'); $(\'#cat_name\').val(\''.$level1['name'].'\'); $(\'#listcategorie\').submit();" title="'.$level1['artikel'].' Artikel"></div>'.CR;



               // Markenfilter
               if (defined('CONF_MODULE_MARKENFILTER')) {
                  $html .= '      <div title="Markenfilter" data-id="'.$level1['id'].'" class="markenfilter list_filter '.($level1['markenfilter'] == 'y' ? 'ci_color' : 'no_ci_color').'" onclick=\'Kategorie.changeMarkenfilter(this);\'></div>'.CR;
               }



               // Merkmalfilter
               if (defined('CONF_MODULE_FILTER')){
                   $html .= '      <div title="Merkmalfilter" data-id="'.$level1['id'].'" class="merkmalfilter list_filter '.($level1['filter_active'] == 'y' ? 'ci_color' : 'no_ci_color').'" onclick=\'Kategorie.katfilterPopup('.$level1['id'].');\'></div>';
               }

               // Kategorie-Passwort
               if (defined('CONF_MODULE_KATEGORIEPASS')) {
                  $html .= '      <div title="Passwort" data-id="'.$level1['id'].'" class="password list_pass fas fa-'.($level1['cat_pass'] == '' ? 'unlock no_ci_color' : 'lock ci_color').'" onclick="Kategorie.changePassword(this);"><input type="hidden" value="'.$level1['cat_pass'].'" /></div>'.CR;
               }

               // Alter
               if (defined('CONF_MODULE_PERSOCHECK') && $this->params->firma['fsk_show'] == 'y') {
                  $html .= '      <div title="Alterskontrolle" data-id="'.$level1['id'].'" class="altercheck list_alter '.($level1['alter_check'] == 'y' ? 'altercheck_on' : 'altercheck_off').'" onclick=\'Kategorie.changeAltercheck(this);\'></div>'.CR;
               }

               $html .= '   </div>'.CR;
               $html .= "</div>";

               if ($level1['childs']) {
                  $html .= '<div id="cat'.$level1['id'] . '">';
                  $html .= $this->_renderTreeSub($this->childs[$level1['id']], $level1['childs']);
                  $html .= "</div>";
               }
            }
         }

         $html .= "</div>\n";
      }
      return $html;
   }

   // Unterkategorien in Baum einbinden
   // 10.05.2019
   private function _renderTreeSub($cats, $max) {
      $count = 0;
      $html  = '';

      foreach ($cats as $cat) {
         if ($cat['ordered'] == $max) {
            $this->striche[$cat['level']] = 1;
         }

         else {
            $this->striche[$cat['level']] = 0;
         }

         $count++;

         $html .= '<div class="catline subcat">'.CR;

         $html .= '   <div class="symbol_left">'.CR;
         $html .=        $this->_getTreeImage($cat, $max, $count);
         // Edit
         $html .= '      <a title="bearbeiten" class="edit list_edit fas fa-pencil-alt" href='.ADMIN_URL_IDX.'/kategorien/details/'.$cat['id'].'></a>'.CR;

         // Aktiv
         if ($cat['active'] == 'y') {
            $html .= '      <div title="aktiv/inaktiv" class="active list_gesperrt fas fa-check" onclick="Kategorie.changeActive(this, '.$cat['id'].');"></div>'.CR;
         }

         else {
            $html .= '      <div title="aktiv/inaktiv" class="active list_gesperrt fas fa-times" onclick="Kategorie.changeActive(this, '.$cat['id'].');"></div>'.CR;
         }

         // Löschen
         $html .= '      <div title="löschen" class="delete list_del far fa-trash-alt" onclick="Kategorie.delete(this, '.$cat['id'].');"></div>'.CR;
         $class = '';
         // Name
         $click = 'onclick="$(\'#cat_id\').val('.$cat['id'].'); $(\'#cat_name\').val(\''.$cat['name'].'\'); $(\'#listcategorie\').submit();"';
         $pointer = ' pointer';
         $html .= '      <div title="Sortierung: '.$cat['ordered'].'" '.$click.' class="name ellipsis'.$pointer.'"' . $class . '>' . $cat['name'] .($cat['mixer_check'] == 'y' ? ' (*Mixer*)' : ''). ($cat['childs'] > 0 && $cat['artikel'] > 0 ? '<strong><span title="Artikel enthalten, bitte in Unterkategorie verschieben" style="color:#cc0000; cursor:pointer;">&nbsp;!&nbsp;&nbsp;&nbsp;</style></strong>' : '').'</div>'.CR;
         $html .= '      <div class="clear"></div>'.CR;
         $html .= '   </div>'.CR;

         $html .= '   <div class="symbol_right">'.CR;
         $html .= '      <div title="'.$cat['artikel'].' Artikel" class="artikel list_artikel fas fa-cube '.((int)$cat['artikel'] == 0 ? ' no_ci_color' : 'ci_color').'" onclick="$(\'#cat_id\').val('.$cat['id'].'); $(\'#cat_name\').val(\''.$cat['name'].'\'); $(\'#listcategorie\').submit();"></div>'.CR;



         // Markenfilter
         if (defined('CONF_MODULE_MARKENFILTER')) {
            $html .= '      <div title="Markenfilter" data-id="'.$cat['id'].'" class="markenfilter list_filter '.($cat['markenfilter'] == 'y' ? 'ci_color' : 'no_ci_color').'" onclick=\'Kategorie.changeMarkenfilter(this);\'></div>';
         }



         // Merkmalfilter
         if (defined('CONF_MODULE_FILTER')){
             $html .= '      <div title="Merkmalfilter" data-id="'.$cat['id'].'" class="merkmalfilter list_filter '.($cat['filter_active'] == 'y' ? 'ci_color' : 'no_ci_color').'" onclick=\'Kategorie.katfilterPopup('.$cat['id'].');\'></div>';
         }

         // Passwort
         if (defined('CONF_MODULE_KATEGORIEPASS')) {
            $html .= '      <div title="Passwort" data-id="'.$cat['id'].'" class="password list_pass fas fa-'.($cat['cat_pass'] == '' ? 'unlock no_ci_color' : 'lock ci_color').'" onclick="Kategorie.changePassword(this);"><input type="hidden" value="'.$cat['cat_pass'].'" /></div>'.CR;
         }

         // Alter
         if (defined('CONF_MODULE_PERSOCHECK') && $this->params->firma['fsk_show'] == 'y') {
            $html .= '      <div title="Alterskontrolle" data-id="'.$cat['id'].'" class="altercheck list_alter '.($cat['alter_check'] == 'y' ? 'altercheck_on' : 'altercheck_off').'" onclick="Kategorie.changeAltercheck(this);"></div>'.CR;
         }

         $html .= '   </div>'.CR;
         $html .= '</div>'.CR;

         if ($cat['childs']) {
            $html .= '<div id="cat' . $cat['id'] . '">';
            $html .= $this->_renderTreeSub($this->childs[$cat['id']], $cat['childs']);
            $html .= "</div>";
         }
      }

      return $html;
   }

   // Grafiken fuer Baumausgabe waehlen (Striche, +, - usw.)
   // 10.05.2019
   private function _getTreeImage($cat, $max, $count) {
      $html   = '';
      $id     = $cat['id'];
      $level  = $cat['level'];
      $childs = $cat['childs'];
      $class  = '';
      $script = '';

      // Linke Striche ausgeben
      for ($i = 0; $i < $level; $i++) {
         if ($this->striche[$i] != 0) {
            $html .= '<div class ="tree_icon leer"></div>';
         }
         else {
            $html .= '<div class ="tree_icon linie"></div>';
         }
      }

      // Nur ein Eintrag vorhanden
      if ($max == 1) {
         if ($childs == 0 && $level == 0) {
            $class = "tree_icon first_bottom_minus";
         }
         elseif ($childs == 0) {
            $class = "tree_icon bottom";
         }
         elseif ($level == 0) {
            $class = "tree_icon first_bottom_minus";
         }
         else {
            $class = "tree_icon bottom_minus";
         }
      }

      // Erster Eintrag, Hauptkategorie
      elseif ($count == 1 && $level == 0) {
         if ($childs == 0) {
            $class = "tree_icon top";
         }
         else {
            $class = "tree_icon tree_icon top_minus";
         }
      }

      // Letzer Eintrag
      elseif ($count == $max){
         if ($childs == 0) {
            $class = "tree_icon bottom";
         }
         else {
            $class = "tree_icon bottom_minus";
         }
      }

      else {
         if ($childs == 0) {
            $class = "tree_icon middle";
         }
         else {
            $class = "tree_icon middle_minus";
         }
      }

      $script .= ($childs ? " onclick='Kategorie.openclose(this, \"cat$id\");'" : '');
      $html .= '<div class="' . $class . '"' . $script . '></div>';
      return $html;
   }

   // Kategorie aktiv / Inaktiv wechseln (bei Multishop lokal)
   // 05.08.2019
   private function changeActive($cat_id) {
      $active = $this->db->querySingleValue("SELECT active FROM #__categories WHERE id = $cat_id");

      // Kategorie nicht vorhanden
      if (!$active) {
         return 'error';
      }

      $val = 'n';

      if ($active == 'n') {
         $val = 'y';
      }

      $this->db->query("UPDATE #__categories SET active = '$val' WHERE id = $cat_id");

      return $val;
   }

   // Markenfilter aktiv / inaktiv
   // 10.05.2019
   private function changeMarkenfilter($cat_id) {
      $mode = 'n';
      $test = $this->db_extern->querySingleValue("SELECT markenfilter FROM #__categories WHERE id = $cat_id");

      if ($test == ('n')) {
         $mode = 'y';
      }

      $this->db_extern->query("UPDATE #__categories SET markenfilter = '$mode' WHERE id = $cat_id");

      return $mode;
   }

   // Kategorie löschen, Positionen der anderen korrigieren
   // 15.10.2019
   private function deleteCat() {
      $cat_id = $this->params->postString('cat_id');
      $delete  = $this->params->postTest('delete');

      // Test, ob Unterkategorien
      $articles  = 0;   // Alle Artikel
      $articles2 = 0;   // Artikel, welche auch anderen Kategorien zugehören
      $sub_cats  = 0;
      $maincat   = $this->db_extern->querySingleObject("SELECT id, name_deu AS name FROM #__categories WHERE mixer_check = 'n' AND id != $cat_id ORDER BY level, ordered");

      // Setzt $this->cats2del[], ohne gewählte Kategorie
      $this->_getCatsAndArticles($cat_id, $sub_cats, $articles, $articles2);

      if (!$delete) {
         if ($articles > 0) {
            exit(json_encode(['status' => 'check', 'msg' => $articles.' Artikel werden nach "'.$maincat->name.'" verschoben']));
         }

         else if ($sub_cats == 0) {
            exit(json_encode(['status' => 'check', 'msg' => '']));
         }
      }

      $level = $this->db_extern->querySingleValue("SELECT level FROM #__categories WHERE id = $cat_id");
      // Gewählte Kategorie hinzufügen
      $this->cats2del[(int)$level][] = $cat_id;
      // Kategorie mit größtem Level ist erstes Element -> Reihenfolge tauschen, gewählte Kategorie zuletzt löschen
      $cats2del = array_reverse($this->cats2del);

      foreach ($cats2del as $level) {
         foreach ($level as $cat_id) {
            $this->_deleteCategorie($cat_id, $maincat->id);
         }
      }

      $this->_delCache();
      $this->sitemap();

      exit(json_encode(['status' => 'ok', 'html' => ($articles == 0 && $sub_cats == 0 ? 'empty': 'reload')]));
   }

   // Anzahl Unterkategorien / Artikel + Artikel in Unterkategorien herausfinden
   // 15.10.2019
   private function _getCatsAndArticles($cat_id, &$cats, &$articles, &$articles2) {
      $articles  += (int)$this->db_extern->querySingleValue("SELECT count(parent_id) FROM shop_article_to_cats WHERE cat_id = $cat_id");
      $articles2 += (int)$this->db_extern->querySingleValue("SELECT count(parent_id) FROM shop_article_to_cats WHERE cat_id = $cat_id AND sort > 0");
      $sub_cats   = $this->db_extern->queryAllObjects("SELECT id, level, childs FROM #__categories WHERE parent_id = $cat_id");

      if ($sub_cats) {
         // Duplikate ausschließen
         foreach ($sub_cats as $sub) {
            if ((int)$cat_id == (int)$sub->id) {
               continue;
            }

            $cats += 1;
            $this->cats2del[(int)$sub->level][] = $sub->id;

            if ((int)$sub->childs > 0) {
               $sub_cats2  = $this->db_extern->queryAllObjects("SELECT id, level FROM #__categories WHERE parent_id = $sub->id");

               foreach ($sub_cats2 as $sub2) {
                  $this->cats2del[(int)$sub2->level][] = $sub2->id;
                  $this->_getCatsAndArticles($sub2->id, $cats, $articles, $articles2);
               }
            }

            else {
               $this->_getCatsAndArticles($sub->id, $cats, $articles, $articles2);
               $this->cats2del[(int)$sub->level][] = $sub->id;
            }
         }
      }
   }

   // Kategorie löschen, enthaltene Artikel verschieben, parent korrigieren, Bilder, SEO und Mixer löschen
   // 15.10.2019
   private function _deleteCategorie($cat_id, $new_cat) {
      $cat       = $this->db_extern->querySingleObject("SELECT ordered, parent_id FROM #__categories WHERE id = $cat_id");

      if ($cat) {
         $parent_id = (int)$cat->parent_id;
         $ordered   = (int)$cat->ordered;

         // Parent Anzahl childs Parent korrigieren, wenn nicht Hauptkategorie
         if ($parent_id > 0) {
            $this->db_extern->query("UPDATE #__categories SET childs = childs -1 WHERE id = $parent_id");
         }

         // Sortierung Geschwisterkategorien korrigieren
         $this->db_extern->query("UPDATE #__categories SET ordered = ordered - 1 WHERE parent_id = $parent_id AND ordered > $ordered");

         // Zusätzliche Kategorien löschen
         $this->db_extern->query("DELETE FROM #__article_to_cats WHERE cat_id = $cat_id AND sort > 1");

         // Artikel in andere Kategorie verschieben
         $this->db_extern->query("UPDATE #__article_to_cats SET cat_id = $new_cat WHERE cat_id = $cat_id");

         // Bilder löschen und SEO-Eintrag aus DB löschen / 1 DS pro Sprache
         $img_data = $this->db_extern->queryAllObjects("SELECT * FROM #__categorie_images WHERE cat_id = $cat_id");

         if ($img_data) {
            foreach ($img_data as $i) {
               $img = [];

               if ($i->images != '') {
                  $a = json_decode($i->images)->images;

                  if (isset($a) && !empty($a)) {
                     foreach ($a as $v) {
                        $img[] = $v->image;
                     }
                  }
               }

               $img[] = $i->mixer1;
               $img[] = $i->mixer2;
               $img[] = $i->mixer3;

               foreach ($img as $del_img) {
                  if ($del_img != '') {
                     @unlink(PICTURE_PATH.'/kategorien/original/'.$del_img.'.png');
                     @unlink(PICTURE_PATH.'/kategorien/original/'.$del_img.'.jpg');
                     @unlink(PICTURE_PATH.'/kategorien/'.$del_img.'.png');
                     @unlink(PICTURE_PATH.'/kategorien/'.$del_img.'.jpg');
                     @unlink(PICTURE_PATH.'/kategorien/'.$del_img.'_tn.png');
                     @unlink(PICTURE_PATH.'/kategorien/'.$del_img.'_tn.jpg');
                  }
               }

               $this->db_extern->query("DELETE FROM #__categorie_images WHERE id = $i->id");
            }
         }

         // Kategorie-Mixer

         $this->db_extern->query("DELETE FROM #__categories WHERE id = $cat_id");
      }
   }

   // Kategorie-Passwort speichern
   // 10.05.2019
   private function setCatpass($cat_id, $cat_pass) {
      $sql = "UPDATE #__categories SET cat_pass = '$cat_pass' WHERE id = $cat_id";
      $this->db_extern->query($sql);

      return true;
   }

   // Alterskontrolle aktiv / inaktiv
   // 10.05.2019
   private function alterCheck($catid) {
      $mode = 'n';
      $test = $this->db_extern->querySingleValue("SELECT alter_check FROM #__categories WHERE id = $catid");

      if ($test == ('n')) {
         $mode = 'y';
      }

      $this->db_extern->query("UPDATE #__categories SET alter_check = '$mode' WHERE id = $catid");

      return $mode;
   }

   // Modul Altercheck speichern
   // 10.05.2019
   private function savePersocheck() {
      $fsk = $this->params->postInt('fsk');
      $fsk_show = $this->params->postCheckbox('fsk_show');

      $this->db->query("UPDATE #__firma SET fsk = $fsk, fsk_show = '$fsk_show' WHERE id = 1");
      $this->params->getFirmData();

      return;
   }


   // Modul Altercheck speichern
   // 10.05.2019
   private function saveBreadcrumbs() {
       $show_breadcrumbs = $this->params->postCheckbox('show_breadcrumbs');

       $this->db->query("UPDATE #__firma SET show_breadcrumbs = '$show_breadcrumbs' WHERE id = 1");
       $this->params->getFirmData();

       return;
   }


   // Edit-Formular auswerten
   // 04.06.2019
   private function detailSave() {
      $lang = $this->params->selected_lang;

      $sql_lang = "name_$lang        = '".$this->params->postString("name")."',
                   desc_$lang        = '".$this->db->escape($this->params->postString('desc1', '', 'none').'[TRENNER]'.$this->params->postString('desc2', '', 'none'))."',
                   title_$lang       = '".$this->params->postString('titletag')."',
                   description_$lang = '".$this->params->postString('description')."',
                   keywords_$lang    = '".$this->params->postString('keywords', '', 'none')."',";

      $cat_id           = $this->params->postInt('cat_id');
      $oldparent        = $this->params->postInt('oldparent');
      $newparent        = $this->params->postInt('newparent');
      $newsort          = $this->params->postInt('newsort', -1); // default -1, falls leer bei neu
      $oldsort          = $this->params->postInt('oldsort');
      $net_id           = $this->params->postInt('net_id');
      $show_text        = $this->params->postCheckbox('show_text');
      $hide_articles    = $this->params->postCheckbox('hide_articles');
      $filter_active    = $this->params->postCheckbox('filter_active');

      $mixer_check      = $this->params->postCheckbox('mixer_check');
      $mixer_gewicht    = $this->params->postString('mixer_gewicht');
      $gewicht_check    = $this->params->postCheckbox('gewicht_check');
      $naehrwerte_check = $this->params->postCheckbox('naehrwerte_check');

      $mixer_einheit_g  = $this->params->postString('mixer_einheit');

      $bild_mode        = $this->params->postInt('bild_mode');
      $bild_zuschneiden = $this->params->postCheckbox('bild_zuschneiden');
      $options          = (object)['mode' =>  $bild_mode, 'zuschneiden' => $bild_zuschneiden];

      // insert / Neue Kategorie
      if ($cat_id == 0) {
         // Hauptkategorie?
         if ($newparent == 0) {
            $level = 0;
         }

         // Bei Unterkategorie Level suchen
         else {
            $level = $this->addParentChild($newparent);
         }

         $oldsort = $this->maxSort($newparent) + 1;
         $sql     = "INSERT INTO #__categories SET
                           parent_id = $newparent,
                           level = $level,
                           $sql_lang
                           ordered = $oldsort,
                           network_id = $net_id,
                           cat_pass = '',
                           show_text = '$show_text',
                           hide_articles = '$hide_articles',
                           mixer_check = '$mixer_check',
                           mixer_gewicht = '$mixer_gewicht',
                           mixer_einheit_g = '$mixer_einheit_g',
                           gewicht_check = '$gewicht_check',
                           naehrwerte_check = '$naehrwerte_check'";
         $this->db_extern->query($sql);
         $cat_id = $this->db_extern->getNewId();

         if ($newsort > 0 && $newsort < $oldsort) {
            $this->newSort($cat_id, $oldsort, $newsort, $newparent);
         }

         $this->db_extern->query("INSERT INTO #__categorie_images SET options ='". json_encode($options)."', cat_id = $cat_id, lang = '$lang'");
      }

      // update bestehende Kategorie
      else {
         // Eltern-Kategorie geändert?
         if ($oldparent != $newparent) {
            // Bei uebergeordneter Kategorie herausnehmen (Anz. childs)
            $oldlevel = $this->delParentChild($oldparent);
            // Bei neuer Kategorie eintragen und neuen level holen
            $level = $this->addParentChild($newparent);

            if ($oldlevel != $level) {
               $this->correctLevel($cat_id, $level - $oldlevel);
               $this->check_categories($cat_id, $level+1);
            }

            $newsort = $this->maxSort($newparent) + 1;
            $this->removeSort($oldparent, $oldsort);

            $sql_lang .= "level = $level, ";
         }

         // Reihenfolge geändert?
         elseif ($oldsort != $newsort) {
            $newsort = $this->newSort($cat_id, $oldsort, $newsort, $newparent);
         }

         $sql = "UPDATE #__categories SET
                        parent_id        = $newparent,
                        $sql_lang
                        ordered          = $newsort,
                        network_id       = $net_id,
                        show_text        = '$show_text',
                        hide_articles    = '$hide_articles',
                        filter_active    = '$filter_active',
                        mixer_check      = '$mixer_check',
                        mixer_gewicht    = '$mixer_gewicht',
                        mixer_einheit_g  = '$mixer_einheit_g',
                        gewicht_check    = '$gewicht_check',
                        naehrwerte_check = '$naehrwerte_check'
                 WHERE id = $cat_id";
         $this->db_extern->query($sql);

         $cat_options = $this->db_extern->querySingleObject("SELECT id, options FROM #__categorie_images WHERE cat_id = $cat_id AND lang = '$lang'");

         if ($cat_options) {
            $this->db_extern->query("UPDATE #__categorie_images SET options ='". json_encode($options)."' WHERE id = $cat_options->id");
            $old_options = $cat_options->options;
         }

         else {
            $this->db_extern->query("INSERT INTO #__categorie_images SET options ='". json_encode($options)."', cat_id = $cat_id, lang = '$lang'");
            $old_options = json_encode((object)['mode' => 0, 'zuschneiden' => '']);
         }

         $img_data = $this->db_extern->querySingleObject("SELECT id, images, options FROM #__categorie_images WHERE cat_id = $cat_id AND lang = '$lang'");

         $images  = json_decode($img_data->images);
         $options = json_decode($old_options);

         if ($images && ($options->mode != $bild_mode || $options->zuschneiden != $bild_zuschneiden)) {
            foreach ($images->images as $img) {
               $filename = $img->image;
               $this->_makeImage($filename, $bild_mode, $bild_zuschneiden);


            }
         }
      }

      $this->_delCache();
      $this->sitemap();

      exit(json_encode(['status' => 'ok', 'cat_id' => $cat_id]));
   }

   // Netzwerk-Kategorien als Popup anzeigen
   // 10.05.2019
   public function networkPopup() {
      if (!isset($_POST['net_id'])) {
         echo json_encode(['status' => 'error', 'html', 'Fehler bei Übertragung']);
         exit('net_id == 0');
      }

      $net_id     = $this->params->postInt('net_id');
      $old_id     = $this->params->postInt('old_id');
      $net_name   = '';
      $net_cats   = [];
      $add_childs = 0;
      $add_level  = 0;
      $add_parent = 0;
      $childs     = 0;
      $level      = 0;
      $parent     = 0;
      $last       = false;

      if ($net_id == 0 && $old_id > 0) {
         $net_id = $old_id;
      }

      // aktueller Level
      if ($net_id > 0) {
         $data     = $this->db->querySingleObject("SELECT id, parent_id, childs, level, active, name_deu FROM #__net_categories WHERE id = $net_id");
         $net_name = (isset($data->name_deu) ? $data->name_deu : 'Bitte wählen...');

         if ($data) {
            $add_childs       = (int)$data->childs;
            $add_parent       = (int)$data->id;
            $add_level        = (int)$data->level;
            $childs           = (int)$data->childs;
            $level            = (int)$data->level;
            $parent           = (int)$data->parent_id;
            $net_cats[$level] = [$parent, $childs];

            // Weitere Kategorien (Level absteigend), wenn vorhanden
            for ($l = $add_level - 1; $l >= 0; $l--) {
               $data             = $this->db->querySingleObject("SELECT id, parent_id, childs, level, active, name_deu FROM #__net_categories WHERE parent_id =
                                                                  (SELECT parent_id FROM #__net_categories WHERE id = $parent)");
               $childs           = (int)$data->childs;
               $level            = (int)$data->level;
               $parent           = (int)$data->parent_id;
               $net_cats[$level] = [$parent, $childs];
            }

            // Falls gewählte Kategorie noch Unterkategorien hat
            if ($add_childs > 0) {
               $data             = $this->db->querySingleObject("SELECT id, parent_id, childs, level, active, name_deu FROM #__net_categories WHERE parent_id = $add_parent");
               $childs           = (int)$data->childs;
               $level            = (int)$data->level;
               $parent           = (int)$data->parent_id;
               $net_cats[$level] = [$parent, $childs];
            }

            else {
               $last = true;
            }

            ksort($net_cats);
         }
      }

      // Keine Auswahl -> Hauptkategorien
      else {
         $net_cats[0] = [0, 1, false];
         $net_name = 'Bitte wählen...';
      }

      $html = '';
      $html .= '<div id="netcat_popup">';
      $html .= '   <h2 class="txt_tit">Auswahl Netzwerkkategorie</h2>'.CR;
      $html .= '   <div id="netcontent"></div>'.CR;

      // Netcats in umgekehrter Reihenfolge durchgehen, Hauptkategorien zuletzt.
      for ($i = 0; $i < count($net_cats) ; $i++) {
         $parent   = $net_cats[$i][0];
         $selected = false;

         $search_parent = (isset($net_cats[$i + 1]) ? $net_cats[$i + 1][0] : 0);
         $data          = $this->db->queryAllObjects("SELECT id, parent_id, active, name_deu FROM #__net_categories WHERE parent_id = $parent");

         $html .= '   <div class="selectbox30">'.CR;
         $html .= '      <select onchange="Kategorie.networkChanged(this.value);">'.CR;


         if (isset($net_cats[$i][2]) || $search_parent == 0) {
            $html .= '         <option value="0" selcted="selected">Bitte wählen</option>'.CR;
         }

         else if ($i == 0 && !isset($net_cats[$i][2])) {
            $html .= '         <option value="0">Entfernen</option>'.CR;
         }

         // Option-List erstellen
         foreach ($data as $val) {
            if ((int)$val->id == $net_id || (int)$val->id == $search_parent) {
               $selected = true;
            }

            else {
               $selected = false;
            }

            $html .= '         <option value="'.$val->id.'"'.($selected ? ' selected="selected"' : '').'>'.$val->name_deu.'</option>'.CR;
         }

         $html .= '      </select>'.CR;
         $html .= '   </div>'.CR;
      }

      $html .= '   <div class="buttonzeile">'.CR;
      $html .= '      <div class="button txt_but" onclick="Multibox.close();">abbrechen</div>'.CR;

      if ($last && $net_id !== 0) {
//         $html .= '      <div class="button_ci txt_but" onclick="$(\'#net_id\').val('.$net_id.'); $(\'#net_name\').html(\''.$net_name.'\'); Kategorie.networkSave();">übernehmen</div>'.CR;
         $html .= '      <div class="button_ci txt_but" onclick="$(\'#net_id\').val('.$net_id.'); $(\'#net_name\').html(\''.$net_name.'\'); Multibox.close()">übernehmen</div>'.CR;
      }

      $html .= '   </div>'.CR;
      $html .= '</div>'.CR;

      exit(json_encode(['status' => 'ok', 'html' => $html]));
   }

   // Netzwerk-Kategorien speichern
   // 10.05.2019
   private function DELnetworkSave() {
      $cat_id = $this->params->postInt('cat_id');
      $net_id = $this->params->postInt('net_id');

      if ($cat_id > 0) {
         $this->db_extern->query("UPDATE #__categories SET network_id = $net_id WHERE id = $cat_id");
         echo json_encode(['status' => 'ok']);
         exit;
      }

      echo json_encode(['status' => 'failed', 'msg' => 'Fehler beim Speichern']);
      exit;
   }

   // Google
   private function _getParentCat($parent, $level, &$level0, &$level1, &$level2, &$level3, &$level4, &$level5, &$level6) {
      $start = $level - 1;

      for ($i = $start; $i >= 0; $i--) {
         $data = $this->db->querySingleObject("SELECT * FROM #__google_cats WHERE parent_id = $parent");
         $id     = (int)$data->id;
         $parent = (int)$data->parent_id;

         if (isset(${'level'.$i}[$id])) {
            ${'level'.$i}[$id]->ordered = ${'level'.($i - 1)}[$parent]->childs;
            ${'level'.($i - 1)}[$parent]->childs++;
            break;
         }

         ${'level'.$i}[$id] = $data;
         ${'level'.$i}[$id]->childs = 0;
         ${'level'.$i}[$id]->ordered = 0;
      }
   }

   // Kategorien als Option-List ausgeben
   // Wird von Google verwendet !!!
   public function catList($catid, $maincat = true, $sub = false) {
      $this->getTree();
      $parent = 0;

      if ($catid != 0) {
         $parent = $this->getParent($catid);
      }

      $html = '';

      if ($maincat) {
         $html .= '<option value="0">Hauptkategorie</option>';
      }

      if ($sub) {
         $html .= '<option value="0">-</option>';
      }

      $max = count($this->categories);
      $count = 0;

      foreach ($this->categories as $cat0) {
         // in eigene Kategorie darf nicht verschoben werden
         if ($maincat && $cat0[0]['id'] == $catid) {
            continue;
         }

         $html .= "<option";
         if ($maincat && $cat0[0]['id'] == $parent) {
            $html .= " selected='selected'";
         }

         elseif (!$maincat && $cat0[0]['id'] == $catid) {
            $html .= " selected='selected'";
         }

         $html .= " value='" . $cat0[0]['id'] . "'>" . str_replace('&', '&amp;', $cat0[0]['name']) . "</option>";

         $max = $cat0[0]['childs'];
         unset($cat0[0]);

         $html .= $this->catListSub($cat0, $catid, $parent, $max, $maincat);
      }

      return $html;
   }

   // Netzwerk-Kategoriean als Option-Liste ausgeben
   // Achtung: BD-Struktur geändert (active)
   private function catListSub($cats, $catid, $parent, $max, $maincat = 0) {
      $html = '';
      $count = 0;

      foreach ($cats as $cat) {
         if ($maincat and $cat['id'] == $catid) {
            continue;
         }

         $html .= "<option";
         if ($maincat and $cat['id'] == $parent) {
            $this->netid = $cat['netid'];
            $html .= " selected='selected'";
         }
         elseif (!$maincat and $cat['id'] == $catid) {
            $html .= " selected='selected'";
         }

         $leer = str_repeat('&nbsp;&nbsp;&nbsp;', $cat['level']);
         $html .= " value='" . $cat['id'] . "'>" . $leer. str_replace('&', '&amp;', $cat['name']) . "</option>";

         if ($cat['childs']) {
            $html .= $this->catListSub($this->childs[$cat['id']], $catid, $parent, $cat['childs'], $maincat);
         }
      }

      return $html;
   }

   // Von Artikel-Details aufgerufen $catid : gesuchte Kategorie, $maincat: true bei 1. Kategorie, $catselect: true, wenn nachfolgende Selctbox angezeigt werden soll
   // 01.01.2019
   public function catListMax($catid, $catselect, $maincat = false) {
      // Rahmen um die Selectboxen
      $html  = '<div class="cat_box_wrapper'.($maincat ? ' maincat' : '').'">';

      // Bei 1. Kategorie ($maincat true)
      if ($maincat) {
         $html .= '<input type="hidden" class="cat_input_main" id="category" name="category" value="'.$catid.'">';
      }

      // Weitere Kategorien
      else {
         $html .= '<input type="hidden" class="cat_input" name="categories[]" value="'.$catid.'">';
      }

      $active_cats = $this->_getActiveTree((int)$catid);

      // Keine Kategorie gewählt
      if (empty($active_cats)) {
         if (!$maincat) {
            $active_cats[] = 0;
         }

         // Neuer Artikel
         else {
            $active_cats[] = $this->db_extern->querySingleValue("SELECT id FROM #__categories WHERE parent_id = 0 ORDER BY id");
            $catid = $active_cats[0];
         }
      }

      for ($i = 0; $i < count($active_cats); $i++) {
         // Falls noch keine Kategorie gewählt
         if ($i == 0) {
            if (count($active_cats) < 2) {
               $html .= $this->_catListMaxSub((int)$catid, $active_cats[$i], 0, $maincat);
            }

            else {
               continue;
            }
         }

         else {
            $html .= $this->_catListMaxSub((int)$catid, $active_cats[$i], $active_cats[$i - 1], $maincat);
         }
      }

      if ($catselect) {
         $my_parent = (int)$active_cats[count($active_cats) - 1];
         $html .= $this->_catListMaxSub(0, 0, $my_parent, false);
      }

      $html .= '</div>';
      return $html;
   }

   // $search - gewählte Kategorie, $cat_id - id aktive Kateg. in Selectbox
   // 01.01.2019
   private function _catListMaxSub($search, $cat_id, $parent, $maincat) {
      $html = '';
      $lang   = $this->params->selected_lang;

      $data = $this->db_extern->queryAllObjects("SELECT id, parent_id, childs, name_$lang AS name, mixer_check FROM #__categories WHERE parent_id = $parent ORDER BY ordered, id");

      if ($data) {
         $html .= '<span class="selectbox30">';
         $html .= '<select name="categorie" onchange="Artikel.catChanged(this);">';

         if (!$maincat) {
            $html .= '<option value="0" style="text-align:center;">---</option>';
         }

         foreach($data as $d) {
            if ((int)$d->id == $search) {
               $html .= '<option value="'.$d->id.'" selected="selected" data-childs="'.$d->childs.'">'.$d->name.($d->mixer_check == 'y' ? ' (*Mixer*)' : '').'</option>';

               if ($maincat) {
                  $this->maincat_id   = $d->id;
                  $this->maincat_name = $d->name;
               }
            }

            else if ((int)$d->id == $cat_id) {
               $html .= '<option value="'.$d->id.'" selected="selected" data-childs="'.$d->childs.'">'.$d->name.($d->mixer_check == 'y' ? ' (*Mixer*)' : '').'</option>';
            }

            else {
               $html .= '<option value="'.$d->id.'" data-childs="'.$d->childs.'">'.$d->name.($d->mixer_check == 'y' ? ' (*Mixer*)' : '').'</option>';
            }
         }

         $html .= '<select>';
         $html .= '</span>';
      }

      return $html;
   }

   // Daten für Detailseite aus DB lesen (netid lokal)
   // 05. 08.2019
   private function details ($cat_id) {
      $lang = $this->params->selected_lang;
      $show_text        = 'n';
      $hide_articles    = 'n';
      $filter_active    = 'n';
      $mixer_check      = 'n';
      $mixer_gewicht    = '';
      $mixer_einheit_g  = 'g';
      $gewicht_check    = 'n';
      $naehrwerte_check = 'n';

      if ($cat_id > 0) {
         $data          = $this->db_extern->querySingleObject("SELECT * FROM #__categories WHERE id = $cat_id");

         if(!$data) {
            exit(header('Location: '.ADMIN_URL_IDX.'/kategorien'));
         }

         $parent           = $data->parent_id;
         $sortierung       = $data->ordered;
         $show_text        = $data->show_text;
         $hide_articles    = $data->hide_articles;
         $filter_active    = $data->filter_active;
//         $net_id           = ($data2 != null ? (int)$data2->id : 0);
//         $net_name         = ($data2 != null ? $data2->name_deu : '');

         // Mixer
         $mixer_check      = (isset($data->mixer_check) ? $data->mixer_check : 'n');
         $mixer_gewicht    = (isset($data->mixer_gewicht) ? $data->mixer_gewicht : '');
         $mixer_einheit_g  = (isset($data->mixer_einheit_g) ? $data->mixer_einheit_g : 'g');
         $gewicht_check    = (isset($data->gewicht_check) ? $data->gewicht_check : 'n');
         $naehrwerte_check = (isset($data->naehrwerte_check) ? $data->naehrwerte_check : 'n');

         $val_name         = $data->{'name_'.$lang};
         $val_titletag     = $data->{'title_'.$lang};
         $val_desc         = $data->{'desc_'.$lang};
         $val_keywords     = $data->{'keywords_'.$lang};
         $val_description  = $data->{'description_'.$lang};

         $options = (object)['mode' => 2, 'zuschneiden' => 'y'];
         $json    = $this->db->querySingleValue("SELECT options FROM #__categorie_images WHERE cat_id = $cat_id AND lang = '$lang'");

         if (!empty($json)) {
            $options = json_decode($json);
         }
      }

      // Neue Kategorie
      else {
         $val_name        = '';
         $val_titletag    = '';
         $val_desc        = '';
         $val_keywords    = '';
         $val_description = '';
         $sortierung      = false;
         $parent          = 0;
         $net_id          = 0;
         $net_name        = '';
         $options         = (object)['mode' => 2, 'zuschneiden' => 'y'];
      }

      include 'templates/kategorie_details.tpl.php';
   }

   // Fuer Edit-Formular: Liste der Editoren
   private function getEditors($cat_id = 0) {
      $html     ='';
      $onfocus  = '';
      $class    = '';
      $onfocus3 = '';
      $class3   = '';
      $onfocus4 = '';
      $class4   = '';
      $img_url  = SHOP_URL.'/'.CONF_PICT_PATH.'kategorien/';
      $no_img   = ADMIN_URL.'/img/nopic.png';
// TODO
      if ($cat_id > 0) {
         // Kategoriedaten einlesen
         $sql = "SELECT c.*,
                        n.id AS netid, n.name_deu AS netname
                    FROM #__categories AS c
                 LEFT JOIN #__net_categories AS n
                    ON c.network_id = n.id
                 WHERE c.id = $cat_id";
         $data = $this->db->querySingleObject($sql);

         $parent        = $data->parent_id;
         $sortierung    = $data->ordered;
         $show_text     = $data->show_text;
         $hide_articles = $data->hide_articles;

         // Mixer
         $mixer_check         = (isset($data->mixer_check) ? $data->mixer_check : 'n');
         $mixer_gewicht       = (isset($data->mixer_gewicht) ? $data->mixer_gewicht : '');
         $mixer_einheit_g     = (isset($data->mixer_einheit_g) ? $data->mixer_einheit_g : 'g');
         $gewicht_check       = (isset($data->gewicht_check) ? $data->gewicht_check : 'n');
         $naehrwerte_check    = (isset($data->naehrwerte_check) ? $data->naehrwerte_check : 'n');

         $filter_active       = $data->filter_active;

         $netid         = ($data->netid != null ? (int)$data->netid : 0);
         $netname       = ($data->netname != null ? $data->netname : '');

         $lang = $this->params->selected_lang;
         $val_name = $data->{'name_'.$lang};
         $val_desc = $data->{'desc_'.$lang};
         $val_keywords = $data->{'keywords_'.$lang};
         $val_description = $data->{'description_'.$lang};
      }

      // Neue Kategorie
      else {
         $val1     = 'Kategoriebezeichnung';
         $val2     = '';
         $val3     = '';
         $val4     = '';
      }

      $images = $this->loadImages($cat_id, $lang);
      $text   = explode('[TRENNER]', $val_desc);
      $text1  = $text[0];
      $text2  = (isset($text[1]) ? $text[1] : '');

      require ADMIN_PATH.'/templates/kategorie_details_editor.tpl.php';

      echo $html;
   }

   // <input type="file" mit JS erstellen (Template und Upload
   private function getImages($cat_id, $lang = '') {
      if ($lang == '') {
         $lang = $this->params->selected_lang;
      }

      $imgdata = $this->loadImages($cat_id, $lang);
      $images = (isset($imgdata->images) ? $imgdata->images : []);
      $html  = '';

      $html .= '<input type="file" id="more_images" multiple="multiple" />'.CR;
      $html .= '<div class="clear"></div>'.CR;
      $html .= '<script>
         // var seo_button  = "<span class=\'seo_button fas fa-link pointer\' onclick=\'Kategorie.bildSeo(this, '.$cat_id.');\'></span><span class=\'color_button fas pointer\' onclick=\'Kategorie.bildColors(this, '.$cat_id.');\'>T</span>";
         var seo_button  = "<span class=\'seo_button_single fas fa-link pointer\' onclick=\'Kategorie.bildSeo(this, '.$cat_id.');\'></span>";

         $("#more_images").fileinput({
            uploadAsync           : true,
            uploadUrl             : admin_url_idx+"/ajax/kategorien/imageUpload",
            uploadExtraData       : { cat_id : $(\'#cat_id\').val() },
            allowedFileExtensions : ["jpg", "jpeg", "png"],
            showUpload            : true,
//            uploadClass           : "button",
            browseOnZoneClick     : true,
            autoOrientImage       : false,
            browseIcon            : "<i class=\'button far fa-folder-open\'></i>",
            browseClass           : "button",
            removeClass           : "remove button",
            removeIcon            : "<i class=\'button fas fa-trash-alt\'></i>",
            removeFromPreviewOnError : true,
            uploadClass           : "multishop button_orange fileinput-upload-button",
            uploadIcon            : "",
            showRemove            : true,
            showCaption           : true,
            showBrowse            : true,
            showPreview           : true,
            showZoom              : false,
            showUploadedThumbs    : true,
            overwriteInitial      : false,

            // Symbole in Icon
            fileActionSettings    : {
               showRemove         : true,
               removeClass        : "multishop remove pointer far fa-trash-alt",
               removeIcon         : "",
               showDrag           : true,
               dragTitle          : "",
               dragIcon           : "<i class=\'fas fa-arrows-alt\'></i>",
               showZoom           : true
            },

            otherActionButtons: seo_button,
            language              : "de",
   '.CR;

      if ($images) {
         $img_url = PICTURE_URL.'kategorien/';
         $sort    = 1;

         $html .= '   initialPreview: [ '.CR;

         foreach ($images as $img) {
            $bild     = html_entity_decode($img->image);
            $thumb    = $img_url.$bild.'_tn.jpg'.$this->params->firma['image_cache'];
            $image    = $img_url.'original/'.$bild.'.jpg'.$this->params->firma['image_cache'];

            $html .= '      \'<div class="file_preview_text">';
            $html .= '<img src="'.$thumb.'" class="file-preview-image pointer show_image" alt="" data-src="'.$image.'" data-sort="'.$sort.'" />';
            $html .= '</div>\','.CR;
            $sort++;
         }

         $sort  = 1;
         $html .= '   ], '.CR;
         $html .= '   initialPreviewConfig: [ '.CR;

         foreach ($images as $img) {
            $html .= '   {
               //caption         : "Bild '.$sort.'",
               caption         : "",
               width           : "78px",
               url             : "'.ADMIN_URL_IDX.'/ajax/kategorien/bildDelete/'.$cat_id.'",
               key             : "'.$img->image.'",
            }, '.CR;

            $sort++;
         }

         $html .= '], '.CR;
      }

      // Bild sortieren
      $html .= '// Sortierung '.CR;
      $html .= '}).on("filesorted", function(event, params) { '.CR;
$html .= 'console.log(params);'.CR;
      $html .= '   $.post(admin_url_idx+"/ajax/kategorien/bildSort", { '.CR;
      $html .= '      oldIndex  : params.oldIndex, '.CR;
      $html .= '      newIndex  : params.newIndex, '.CR;
      $html .= '      cat_id : '.$cat_id.CR;
      $html .= '   }, function(data) { '.CR;
      $html .= '      if (data.status === "ok") {'.CR;
      $html .= '         var sort = 1;'.CR;
      $html .= '         $(".kv-preview-thumb", $("#file_uploader")).each(function() { '.CR;
      $html .= '            $("img", $(this)).attr("data-sort", sort);'.CR;
      $html .= '            $(".file-footer-caption", $(this)).attr("title", "Bild "+sort);'.CR;
      $html .= '            $(".file-caption-info", $(this)).html("Bild "+sort);'.CR;
      $html .= '            sort++;'.CR;
      $html .= '         });'.CR;
      $html .= '      }'.CR;
      $html .= '   }, "json");'.CR;

      // bild löschen
      $html .= '}).on("filedeleted", function(a, b) { '.CR;
//      $html .= '   setTimeout(function() {'.CR;
      $html .= '      var sort = 1;'.CR;
$html .= 'console.log(b);'.CR;
      $html .= '      $(".kv-preview-thumb", $("#file_uploader")).each(function() { '.CR;
      $html .= '         if (b !== parseInt($(this).attr("data-sort"))) {';
      $html .= '            $("img", $(this)).attr("data-sort", sort);'.CR;
//      $html .= '      $(".file-preview-frame", $("#file_uploader")).each(function() { '.CR;
//      $html .= '         $("img", $(this)).attr("data-sort", sort);'.CR;
//      $html .= '         $(".file-footer-caption", $(this)).attr("title", "Bild "+sort);'.CR;
//      $html .= '         $(".file-caption-info", $(this)).html("Bild "+sort);'.CR;
$html .= 'console.log("deleted", sort);'.CR;
      $html .= '         sort++;'.CR;
      $html .= '         }'.CR;
      $html .= '      });'.CR;
  //    $html .= '   }, 300);'.CR;

      $html .= '}).on("filebatchselected", function(event, files) {'.CR;

      // Alle Dateien hochgeladen
      $html .= '}).on("filebatchuploadcomplete", function(event) { '.CR;
      $html .= '   Kategorie.bildRefresh('.$cat_id.');';
      $html .= '}); ';

      $html .= '</script> ';

      return $html;
   }

   // Sortierng korrigieren (Kategorien)
   private function removeSort($parent, $oldsort) {
      $sql = "UPDATE #__categories SET ordered = ordered - 1 WHERE parent_id = $parent and ordered >= $oldsort";
      $this->db_extern->query($sql);
   }

   // Level korrigieren, z.B umhängen Subtree
   private function correctLevel($catid, $level) {
      $subcat = [];
      $sql = "SELECT childs FROM #__categories WHERE id = $catid";
      if ($this->db_extern->query($sql)) {
         $sql = "SELECT id FROM #__categories WHERE parent_id = $catid";
         $this->db_extern->query($sql);
         while ($data = $this->db_extern->getObject()) {
            $subcat[$data->id] = $data->id;
         }

         $sql = "UPDATE #__categories SET level = level + $level WHERE parent_id = $catid";
         $this->db_extern->query($sql);
         foreach ($subcat as $cat) {
            $this->correctLevel($cat, $level);

         }
      }
   }

   // Sortierung beim Löschen / Umsortieren Kategorien korrigieren
   private function newSort($catid, $oldsort, $newsort, $newparent) {
      // min. und max. order finden
      if ($newsort < 1) {
         $newsort = 1;
      }

      else {
         $max = $this->maxSort($newparent);

         if ($newsort > $max) {
            $newsort = $max;
         }
      }

      // Andere Kategorien sortierung erhöhen, bzw verringern
      if ($newsort < $oldsort) {
//         $sql = "UPDATE #__categories SET ordered = ordered + 1 WHERE parent_id = $newparent AND ordered < $oldsort AND ordered >= $newsort";
         $sql = "UPDATE #__categories SET ordered = ordered + 1 WHERE parent_id = $newparent AND ordered != $oldsort AND ordered >= $newsort";
      }

      else {
//         $sql = "UPDATE #__categories SET ordered = ordered - 1 WHERE parent_id = $newparent AND ordered > $oldsort AND ordered <= $newsort";
         $sql = "UPDATE #__categories SET ordered = ordered - 1 WHERE parent_id = $newparent AND ordered != $oldsort AND ordered <= $newsort";
      }

      $this->db_extern->query($sql);

      $sql = "UPDATE #__categories SET ordered = $newsort WHERE id = $catid";
      $this->db_extern->query($sql);

      $test = $this->db->queryAllObjects("SELECT id FROM #__categories WHERE parent_id = $newparent ORDER BY ordered");

      if ($test && !empty($test)) {
         for ($i = 0; $i < count($test); $i++) {
            $this->db->query("UPDATE #__categories SET ordered = ".($i + 1)." WHERE id = ".$test[$i]->id);
         }
      }

      return $newsort;
   }

   // Neue Unterkategorie bei Parent-Kategorie eintragen
   private function addParentChild($parent) {
      // Nicht bei neuer Hauptkategorie
      if ($parent != 0) {
         $this->db_extern->query("UPDATE #__categories SET childs = childs + 1 WHERE id = $parent");

         $level = (int)$this->db_extern->querySingleValue("SELECT level FROM #__categories WHERE id = $parent");
         return ($level + 1);
      }

      else {
         return 0;
      }
   }

   // Subkategorie löschen und bei Parent-Kategorie vermerken
   private function delParentChild($parent) {
      $sql = "UPDATE #__categories SET childs = childs - 1 WHERE id = $parent";
      $this->db_extern->query($sql);

      if ($parent != 0) {
         $sql = "SELECT level FROM #__categories WHERE id = $parent";
         $this->db_extern->query($sql);
         $temp = $this->db_extern->getObject();
         return $temp->level + 1;
      }
      return 0;
   }

   // Elternknoten finden
   private function getParent($catid, $categorie = '#__categories') {
      if (!$catid) {
         return 0;
      }

      $sql = "SELECT parent_id FROM $categorie WHERE id = $catid";
      $this->db_extern->query($sql);
      $temp = $this->db_extern->getObject();
      if (is_object($temp)) {
         return $temp->parent_id;
      }
      return 0;
   }

   // Sortiernung überprüfen
   private function maxSort($parent) {
      $sort = $this->db_extern->querySingleValue("SELECT MAX(ordered) FROM #__categories WHERE parent_id = $parent");

      if ($sort) {
         return (int)$sort;
      }

      else {
         return 0;
      }
   }

   // Import Module
   public function getCategoryByName($name, $lang) {
      // Nach Name suchen
      $test = $this->db_extern->querySingleValue("SELECT id FROM #__categories WHERE name_$lang = '$name'");
      if ($test == '') {
         // Wenn nicht vorhandne nach IMPORT suchen
         $test = $this->db_extern->querySingleValue("SELECT id FROM #__categories WHERE name_$lang = 'IMPORT'");

         if ($test == '') {
            // Falls auch nicht vorhandne neuer Eintrag
            $oldsort = $this->maxSort(0) + 1;

            $sql = "INSERT INTO #__categories SET
                           parent_id  = 0,
                           active     = 'n',
                           level      = 0,
                           name_deu   = 'IMPORT',
                           name_eng   = 'IMPORT',
                           name_spa   = 'IMPORT',
                           ordered    = $oldsort,
                           network_id = 0,
                           cat_pass   = ''";
            $this->db_extern->query($sql);
            $test = $this->db_extern->getNewId();
         }
      }
      return $test;
   }

   // Import Module
   public function checkCatName($name, $lang, $level, $parent) {
//      $test = $this->db_extern->querySingleObject("SELECT id, parent_id FROM #__categories WHERE name_$lang = '$name' AND level = $level");
      $test = $this->db_extern->querySingleObject("SELECT id, parent_id FROM #__categories WHERE parent_id = $parent AND name_$lang = '$name' AND level = $level");

      if ($test) {
         return $test->id;
      }

      // Neue Kategorie eintragen
      $ordered = $this->db_extern->querySingleValue("SELECT MAX(c.ordered) FROM #__categories AS c WHERE c.parent_id = $parent");
      if ($ordered) {
         $ordered++;
      }
      else {
         $ordered = 1;
      }

      $this->db_extern->query("INSERT INTO #__categories SET parent_id = $parent, active = 'y', level = $level, ordered = $ordered, name_$lang = '$name', network_id = 0, cat_pass = ''");
      $last_id = $this->db_extern->getNewId();

      // Anzahl childs korrigieren, falls nicht Hauptkategorie
      if ($parent != 0) {
         $this->db_extern->query("UPDATE #__categories SET childs = childs + 1 WHERE id = $parent");
      }

      return $last_id;
   }

   // Bilder Kategorien oder Kategorie-Mixer Upload
   // 15.05.2019
   private function imageUpload() {
      $lang     = $this->params->selected_lang;
      $cat_id   = $this->params->postInt('cat_id');

      // Kategorie-Bild
      if (isset($_FILES['file_data'])) {
         Helper::setData('image_cache', time());

         $test = (int)$this->db_extern->querySingleValue("SELECT id FROM #__categorie_images WHERE cat_id = $cat_id AND lang = '$lang'");

         // Noch kein Eintrag vorhanden
         if ($test == 0 && $cat_id > 0) {
            $this->db_extern->query("INSERT INTO #__categorie_images SET cat_id = $cat_id, lang = '$lang' ON DUPLICATE KEY UPDATE mixer1 = ''");
         }

         $img_dir  = PICTURE_PATH.'/kategorien/';
         $img_url  = PICTURE_URL.'/kategorien/';

         // Verzeichnisse anlegen, falls noch nicht vorhanden
         if (!is_dir($img_dir)) {
            mkdir($img_dir);
            mkdir($img_dir.'/original');
         }

         $image_arr             = [];
         $imgdata               = $this->loadImages($cat_id, $lang);
         $image_arr['images']   = (isset($imgdata->images) ? $imgdata->images : []);
         $options               = json_decode($imgdata->options);

         $pic_nr                = 1 + (int)$imgdata->anzahl;

         $tempfile              = $_FILES['file_data']['tmp_name'];
         $name                  = $_FILES['file_data']['name'];
         $filename              = str_ireplace(['.jpg', '.png', '.jpeg', '.gif', '.tiff', '.bmp'], '', $name).'_'.$cat_id.'_'.$pic_nr.'_'.$lang;
         $filename              = Helper::checkFilename($this->db_extern->querySingleValue("SELECT name_$lang FROM #__categories WHERE id = $cat_id")).'_'.$cat_id.'_'.$pic_nr.'_'.$lang;

         $image_arr['images'][] = (object)['image'  => $filename,
                                           'link'   => '',
                                           'intern' => 'y',
                                           'search' => '',
                                           'seo'    => '',
                                           'color'  => '255,255,255,2',
                                           'bg'     => '128,128,128,0.5',
                                           'text'   => ''
                                          ];

         $this->db_extern->query("UPDATE #__categorie_images SET anzahl = $pic_nr, images = '". $this->db->escape(json_encode($image_arr))."' WHERE id = ".$imgdata->id);

         move_uploaded_file($tempfile, $img_dir.'original/'.$filename.'.jpg');

         list($breite) = getimagesize($img_dir.'original/'.$filename.'.jpg');

         if ($breite > (int)$this->params->firma['max_width']) {
            $breite = (int)$this->params->firma['max_width'];
         }

         Helper::resizePicCenter($img_dir.'original/'.$filename.'.jpg', $img_dir.$filename.'_tn.jpg', 128, 128, 'jpg' );
         $this->_makeImage($filename, $options->mode, $options->zuschneiden);


         exit(\json_encode(['status' => 'ok', 'html' => $img_url.$filename.'_tn.jpg?'.time(), 'target' => 'img_src']));
      }

      // Bilder Mixer
      else if (isset($_FILES['file'])) {
         // 1 -6 Kategoriebilder / 7 - 8 Mixer
         $cat_id   = $this->params->postInt('param2');
         $pic_nr   = $this->params->postInt('param1');
         $tempfile = $_FILES['file']['tmp_name'];
         $img_dir  = PICTURE_PATH.'/kategorien/';
         $img_url  = PICTURE_URL.'/kategorien/';

         $filename = $cat_id.'_mixer'.$pic_nr;
         $image    = 'mixer'.$pic_nr;
         $lang     = 'deu';

         move_uploaded_file($tempfile, $img_dir.'original/'.$filename.'.jpg');

         // Bilder Mixer
         list($breite) = getimagesize($img_dir.'original/'.$filename.'.jpg');

         if ($breite > (int)$this->params->firma['max_width']) {
            $breite = (int)$this->params->firma['max_width'];
         }

         Helper::resizePicCenter($img_dir.'original/'.$filename.'.jpg', $img_dir.$filename.'.jpg', $breite, 0, 'jpg' );
         Helper::resizePicCenter($img_dir.'original/'.$filename.'.jpg', $img_dir.$filename.'_tn.jpg', 78, 78, 'jpg' );
         Helper::resizePicCenter($img_dir.'original/'.$filename.'.jpg', $img_dir.$filename.'_tp.jpg', 360, 270, 'jpg' );

         // Image in DB speichern
         if ($cat_id > 0) {
            $test = $this->db->querySingleValue("SELECT id FROM #__categorie_images WHERE cat_id = $cat_id AND lang = '$lang'");

            if ($test) {
               $this->db->query("UPDATE #__categorie_images SET $image = '$filename' WHERE cat_id = $cat_id AND lang = '$lang'");
            }
            else {
               $this->db->query("INSERT INTO #__categorie_images SET cat_id = $cat_id, lang = '$lang', $image = '$filename'");
            }
         }

         exit(json_encode(['status' => 'ok', 'html' => $img_url.$filename.'_tn.jpg?'.time(), 'target' => 'img_src']));
      }

      echo json_encode(['status' => 'error', 'msg' => 'Fehler bei Übertragung']);
   }

   // Kategoriebild löschen
   private function bildDelete() {
      $cat_id   = $this->params->params3;
      $img_name = $this->params->postString('key');
      $lang     = $this->params->selected_lang;

      if ($cat_id > 0) {
         $img_data = $this->db_extern->querySingleObject("SELECT id, images FROM #__categorie_images WHERE cat_id = $cat_id AND lang = '$lang'");
         $images   = json_decode($img_data->images);
         $img_arr  = [];

         foreach ($images->images as $i) {
            if ($i->image != $img_name) {
               $img_arr[] = $i;
            }

            else {
               $filename = $i->image;

               @unlink(PICTURE_PATH.'kategorien/original/'.$filename.'.jpg');
               @unlink(PICTURE_PATH.'kategorien/'.$filename.'.jpg');
               @unlink(PICTURE_PATH.'kategorien/'.$filename.'_tn.jpg');
               @unlink(PICTURE_PATH.'kategorien/'.$filename.'_tp.jpg');
            }
         }

         $img_data->images = (object)['images' => $img_arr];
         $this->db_extern->query("UPDATE #__categorie_images SET images = '".$this->db->escape(json_encode($img_data->images))."' WHERE id = $img_data->id");

         exit (json_encode(['status' => 'ok']));
      }
   }

   private function bildRefresh() {
      $cat_id = $this->params->postInt('cat_id');
      $html   = $this->getImages($cat_id, $this->params->selected_lang);

      exit(json_encode(['status' => 'ok', 'html' => $html]));
   }

   public function bildSeo() {
      $cat_id    = $this->params->postInt('cat_id');
      $sort      = $this->params->postInt('sort');
      $lang      = $this->params->selected_lang;
      $html      = '';

      $images    = $this->db->querySingleObject("SELECT images FROM #__categorie_images WHERE cat_id = $cat_id AND lang = '$lang'");

      $image_arr = json_decode($images->images);
      $data_arr  = (object)$image_arr->images[$sort-1];
      $seo_data  = (object)['seo' => '', 'link' => '', 'intern' => 'y', 'text' => ''];

      if (isset($data_arr->seo)) {
         $seo_data = $data_arr;
      }

      $html .= '<div id="livedesigner_seo">'.CR;
      $html .= '   <div class="txt_tit">Bild verlinken</div>'.CR;
      $html .= '   <div class="searchbox_link">'.CR;
      $html .= '      <input type="text" class="txt_inp" id="livedesigner_seo_link" name="livedesigner_seo_link" value="'.$seo_data->link.'" placeholder="http://" />'.CR;
      $html .= '      <input type="hidden" id="modul_id" name="modul_id" value="'.$cat_id.'" />'.CR;
      $html .= '      <input type="hidden" id="modul_sort" name="modul_sort" value="'.$sort.'" />'.CR;
      $html .= '   </div>'.CR;

      // Checkbox intern
      $html .= '   <div class="searchbox_intern txt_bez">'.CR;
      $html .= '      <input type="checkbox" class="newdesign" id="livedesigner_seo_intern" name="livedesigner_seo_intern" '.($seo_data->intern === 'y' ? ' checked="checked"' : '').' />'.CR;
      $html .= '      <label for="livedesigner_seo_intern">im gleichen Browser-Tab öffnen</label>'.CR;
      $html .= '   </div>'.CR;

      // SEO
      $html .= '   <h2 class="fliesstext">Keywords des Bildes (wird als Title & Alt umgesetzt)</h2>'.CR;
      $html .= '   <input type="text" class="txt_inp" id="livedesigner_seo_seo" value="'.$seo_data->seo.'" />'.CR;
      $html .= '   <div class="buttonzeile">'.CR;
      $html .= '      <span class="button button_left txt_but" onclick="Multibox.close();">abbrechen</span>'.CR;
      $html .= '      <span class="button_ci button_right txt_btn" onclick="Kategorie.bildSeoSave();">speichern</span>'.CR;
      $html .= '   </div>'.CR;
      $html .= '</div>'.CR;

      exit(json_encode(['status' => 'ok', 'html' => $html]));
   }

   public function BildSeoSave() {
      $cat_id   = $this->params->postInt('cat_id');
      $sort     = $this->params->postInt('sort') - 1;
      $link     = htmlentities($this->params->postString('seo_link'));
      $intern   = $this->params->postCheckbox('seo_intern');
      $seo      = htmlentities($this->params->postString('seo_seo'));
      $lang     = $this->params->selected_lang;

      $data     = $this->db->querySingleValue("SELECT images FROM #__categorie_images WHERE cat_id = $cat_id AND lang = '$lang'");

      if ($data) {
//var_dump($data);
         $image_arr = json_decode($data);
         $image_arr->images[$sort]->seo = $seo;
         $image_arr->images[$sort]->link = $link;
         $image_arr->images[$sort]->intern = $intern;
//var_dump($image_arr->images);

//         foreach ($image_arr->images as $img) {
//            $img->image = html_entity_decode($img->image);
//         }

         $this->db->query("UPDATE #__categorie_images SET images = '".json_encode($image_arr, JSON_UNESCAPED_UNICODE)."' WHERE cat_id = $cat_id AND lang = '$lang'");

         exit(json_encode(['status' => 'ok']));
      }

      exit(json_encode(['status' => 'error', 'msg' => 'Fehler beim Speichern ']));
   }

   public function bildSort() {
      $cat_id   = $this->params->postInt('cat_id');
      $oldindex = $this->params->postInt('oldIndex');
      $newindex = $this->params->postInt('newIndex');
      $lang     = $this->params->selected_lang;

      $images = $this->db->querySingleValue("SELECT images FROM #__categorie_images WHERE cat_id = $cat_id AND lang = '$lang'");

      if ($images) {
         $image_arr  = json_decode($images);
         $new_arr    = (object)['images' => []];
         $img_length = count($image_arr->images) - 1;

         if (isset($image_arr->images[$oldindex]) && isset($image_arr->images[$newindex])) {
            $pos = $image_arr->images[$oldindex];

            foreach ($image_arr->images as $k => $img) {
               // Verschobenes Bild überspringen
               if ($k == $oldindex) {
                  continue;
               }

               // Bild wird nach links verschoben
               if ($newindex < $oldindex) {
                  if ($k == $newindex) {
                     $new_arr->images[] = $pos;
                  }

                  // Bild Kopieren
                  $new_arr->images[] = $img;
               }

               // Bild wird nach rechts verschoben, aber nicht ans Ende
               else {
                  // Bild Kopieren
                  $new_arr->images[] = $img;

                  // Bild wird nach links verschoben, aber nicht ans Ende
                  if ($k == $newindex) {
                     $new_arr->images[] = $pos;
                  }
               }
            }

            $json = $this->db->escape(json_encode($new_arr));

            if ($new_arr->images == '[]') {
               $json = '';
            }

            $this->db->query("UPDATE #__categorie_images SET images = '".$json."' WHERE cat_id = $cat_id AND lang = '$lang'");
         }

         exit(json_encode(['status' => 'ok']));
      }
   }

   // Nicht verwendet - funktioniert
   public function bildColors() {
      $cat_id    = $this->params->postInt('cat_id');
      $sort      = $this->params->postInt('sort');
      $lang      = $this->params->selected_lang;
      $html      = '';

      $images    = $this->db->querySingleObject("SELECT images FROM #__categorie_images WHERE cat_id = $cat_id AND lang = '$lang'");

      $image_arr = json_decode($images->images);
      $data_arr  = (object)$image_arr->images[$sort-1];
      $color     = \KANPAICLASSIC\Helper::moduleColor(isset($data_arr->color) ? $data_arr->color : '255,255,255,1');
      $bg        = \KANPAICLASSIC\Helper::moduleColor(isset($data_arr->bg) ? $data_arr->bg : '128,128,128,0.5');
      $text      = (isset($data_arr->text) ? $data_arr->text : '');

      $html .= '<div id="livedesigner_colors">'.CR;
      $html .= '   <div class="txt_tit">Bezeichnung</div>'.CR;
      $html .= '   <input type="hidden" id="modul_id" name="modul_id" value="'.$cat_id.'" />'.CR;
      $html .= '   <input type="hidden" id="modul_sort" name="modul_sort" value="'.$sort.'" />'.CR;

      // Bild anzeigen
      // $html .= '   <div id="livedesigner_colors_image">'.CR;
      // $html .= '      <img src="'.TEMPLATE_URL.'/images/module/'.$image.'_td.jpg?'.time().'">'.CR;
      // $html .= '      <span id="livedesigner_colors_text" style="color:'.$color->css.'; background-color:'.$bg->css.'">Beispieltext</span>';
      // $html .= '   </div>'.CR;

      // Text
      $html .= '      <div class="color_line">'.CR;
      $html .= '         <input type="text" class="txt_inp" id="bild_text" value="'.$text.'" />'.CR;
      $html .= '      </div>'.CR;

      // Schriftfarbe anzeigen
      $html .= '   <div class="text_color">'.CR;
      $html .= '      <div class="color_line">'.CR;
      $html .= '         <span class="text_title">Textfarbe:&nbsp;</span>'.CR;
      $html .= '         <input type="text" id="livedesigner_colors_color" class="txt_inp minicolors minicolors_input" id="livedesigner_seo_color" data-opacity="'.$color->opacity.'" value="'.$color->color.'" />'.CR;
      $html .= '      </div>'.CR;

      // Hintergrundfarbe anzeigen
      $html .= '      <div class="color_line">'.CR;
      $html .= '         <span class="text_title">Hintergrundfarbe:&nbsp;</span>'.CR;
      $html .= '         <input type="text" id="livedesigner_colors_bg" class="txt_inp minicolors minicolor_input" id="livedesigner_seo_bg"   data-opacity="'.$bg->opacity.'"   value="'.$bg->color.'" />'.CR;
      $html .= '      </div>'.CR;
      $html .= '   </div>'.CR;

      $html .= '   <div class="buttonzeile">'.CR;
      $html .= '      <span class="button button_left txt_but" onclick="Multibox.close();">abbrechen</span>'.CR;
      $html .= '      <span class="button_ci button_right txt_btn" onclick="Kategorie.bildColorsSave();">speichern</span>'.CR;
      $html .= '   </div>'.CR;
      $html .= '</div>'.CR;

      exit(json_encode(['status' => 'ok', 'html' => $html]));
   }

   // Nicht verwendet - funktioniert
   public function BildColorsSave() {
      $cat_id    = $this->params->postInt('cat_id');
      $sort      = $this->params->postInt('sort');
      $text      = $this->params->postString('text');
      $lang      = $this->params->selected_lang;

      $color_color = str_replace('#', '', $this->params->postString('color'));
      $color_opc   = $this->params->postString('color_opc');
      $bg_color    = str_replace('#', '', $this->params->postString('bg'));
      $bg_opc      = $this->params->postString('bg_opc');

      $color       = hexdec(substr($color_color, 0, 2)).','.hexdec(substr($color_color, 2, 2)).','.hexdec(substr($color_color, 4, 2)).','.$color_opc;
      $bg          = hexdec(substr($bg_color, 0, 2)).','.hexdec(substr($bg_color, 2, 2)).','.hexdec(substr($bg_color, 4, 2)).','.$bg_opc;

      $data     = $this->db->querySingleValue("SELECT images FROM #__categorie_images WHERE cat_id = $cat_id AND lang = '$lang'");

      if ($data) {
         $image_arr = json_decode($data);
         $image_arr->images[$sort - 1]->color = $color;
         $image_arr->images[$sort - 1]->bg    = $bg;
         $image_arr->images[$sort - 1]->text  = $text;

         $this->db->query("UPDATE #__categorie_images SET images = '".json_encode($image_arr)."' WHERE cat_id = $cat_id AND lang = '$lang'");

         exit(json_encode(['status' => 'ok']));
      }

      exit(json_encode(['status' => 'error', 'msg' => 'Fehler beim Speichern ']));
   }

   // Bilder Kategorie-Mixer löschen
   // 31.01.2021
   private function deleteImg() {
      $dir      = PICTURE_PATH.'/kategorien/';
      $cat_id   = $this->params->postInt('cat_id');
      $pic_nr   = $this->params->postString('pic_nr');
      $lang     = $this->params->selected_lang;

      $image    = $pic_nr;
      $filename = $cat_id.'_'.$pic_nr;


      @unlink($dir.'original/'.$filename.'.jpg');
      @unlink($dir.$filename.'.jpg');
      @unlink($dir.$filename.'_tn.jpg');
      // Mixer
      @unlink($dir.$filename.'_tp.jpg');

      $this->db->query("UPDATE #__categorie_images SET $image = '' WHERE cat_id = $cat_id");

      echo json_encode(['status' => 'ok', 'html' => ADMIN_URL.'/img/nopic.png']);
      exit;
   }

   // Bilder Kategorien Links speichern
   // 15.05.2019
   private function saveLinks() {
      $cat_id = $this->params->postInt('cat_id');
      $pic_nr = $this->params->postInt('pic_nr');
      $lang   = $this->params->selected_lang;
      $intern = $this->params->postCheckbox('intern');
      $link   = $this->params->postString('link');
      $search = $this->params->postString('search');

      if ($cat_id == 0) {
         echo json_encode(['status' => 'new', 'msg' => 'Neue Kategorie noch nicht gespeichert.']);
         exit;
      }

//      if ($intern == 'y' && substr($link, 0, 6) == '[SHOP]') {
      if ($intern == 'y' && mb_stripos($link, '[SHOP]') !== false) {
         $link = SHOP_URL_IDX.'/'.str_ireplace(['[SHOP]/', '[SHOP]'], '', $link);
      }

      if (substr($link, 0, 4) != 'http') {
         $link = 'http://'.$link;
      }

  //    else if (!$this->params->multishop && $link != '' && substr($link, 0, 4) != 'http') {
  //       $link = 'http://'.str_ireplace('[SHOP]', '', $link);
  //    }

      $test = (int)$this->db->querySingleValue("SELECT id FROM #__categorie_images WHERE cat_id = $cat_id AND lang = '$lang'");

      if ($test > 0) {
         $this->db->query("UPDATE #__categorie_images SET link$pic_nr = '$link', intern$pic_nr = '$intern', search$pic_nr = '$search' WHERE cat_id = $cat_id AND lang = '$lang'");
      }

      else {
         $this->db->query("INSERT INTO #__categorie_images SET link$pic_nr = '$link', intern$pic_nr = '$intern', search$pic_nr = '$search', cat_id = $cat_id, lang = '$lang'");
      }

      $test = $this->db->querySingleObject("SELECT link$pic_nr AS link, intern$pic_nr AS intern, search$pic_nr AS search FROM #__categorie_images WHERE cat_id = $cat_id AND lang = '$lang'");

      if ($test) {
         echo json_encode(['status' => 'ok', 'link' => $test->link, 'intern' => $test->intern, 'search' => $test->search, 'db' => $this->db->last_sql]);
      }

      else {
         echo json_encode(['status' => 'error', 'sql' => print_r($this->db->last_sql, true)]);
      }

      exit;
   }

   private function check_categories($parent, $level) {
      $childs = 0;

      if ($level > 8) {
         return $childs;
      }

      $data = $this->db_extern->queryAllObjects("SELECT id, parent_id, level, ordered, childs FROM #__categories WHERE parent_id = $parent ORDER BY ordered");

      if ($data && count($data) > 0) {
         $sort = 1;

         foreach ($data as $d) {
            $childs = $this->check_categories($d->id, (int)$d->level + 1);
            $this->db_extern->query("UPDATE #__categories SET ordered = $sort, childs = $childs, level = $level WHERE id = ".$d->id);
            $sort++;
         }

      }

      return (is_array ($data) ? count($data) : 0);
   }

   // Kategorie-Pfad von gew. Kategorie bis root auswählen
   private function _getActiveTree($cat_id, $first = true) {
      static $active_arr = [];

      if ($first) {
         $active_arr = [];
      }

      $data = $this->db_extern->querySingleObject("SELECT id, parent_id FROM #__categories WHERE id = $cat_id");

      if ($data) {
         $active_arr[] = (int)$cat_id;

         $cat_id = (int)$data->id;
         $parent = (int)$data->parent_id;

         if ($parent != 0) {
            $this->_getActiveTree($parent, false);
         }

         else {
            $active_arr[] = 0;
         }
      }

      if ($first) {
         return array_reverse($active_arr);
      }
   }

   // Cache für Submenüs / Horiontales Menü löschen. Wird von FE erstellt, wenn nicht vorhanden
   private function _delCache() {
      foreach ($this->params->langs as $lang) {
         if (file_exists(SHOP_PATH.'/tmp/cat_cache_'.$lang.'.js')) {
            unlink(SHOP_PATH.'/tmp/cat_cache_'.$lang.'.js');
         }
      }
   }

   public function sitemap($old_cat = '', $old_cat_lev1 = '', $old_cat_lev2 = '') {
      $status = $this->db->querySingleObject("SELECT sitemap_cat, sitemap_cat_lev1, sitemap_cat_lev2 FROM #__firma2 WHERE id = 1");

      // Keine Änderung
      if ($old_cat == $status->sitemap_cat && $old_cat_lev1 == $status->sitemap_cat_lev1 && $old_cat_lev2 == $status->sitemap_cat_lev2) {
         return;
      }

      if ($status->sitemap_cat == 'y') {
         // Kategoriebaum erstellen
         $this->treemode = 'sitemap';
         $this->getTree();

         $html  = '';
         $xml   = '';
         $cats  = $this->categories;
         $datum = date('Y-m-d');

         foreach($cats as $c) {
            $cat = (object)$c[0];

            $html .= '<div class="categorie_block">'.CR;
            $html .= '   <div class="cat_level cat_level0">'.CR;
            $html .= '      <a href="'.$this->params->getLink('kategorie', $cat->id, $cat->name).'"><span class="fliesstext text_normal ellipsis"'.($this->params->firma['sitemap_title'] == 'y' ? ' title="'.$cat->titletag.'"' : '').'>'.$cat->name.'&nbsp;</span></a>'.CR;
            $html .= '   </div>';

            $xml  .= '   <url>'."\n";
            $xml  .= '      <loc>'.$this->params->getLink('kategorie', $cat->id, $cat->name).'</loc>'."\n";
            $xml  .= '      <lastmod>'.$datum.'</lastmod>'."\n";
            $xml  .= '      <changefreq>weekly</changefreq>'."\n";
            $xml  .= '      <priority>0.8</priority>'."\n";
            $xml  .= '   </url>'."\n";

            if ($status->sitemap_cat_lev1 == 'y' && $cat->childs > 0) {
               for ($i = 1; $i <= $cat->childs; $i++ ) {
                  if (!isset($c[$i])) {
                     continue;
                  }

                  $cat1  = (object)$c[$i];
                  $html .= '   <div class="cat_level cat_level1">'.CR;
                  $html .= '      <a href="'.$this->params->getLink('kategorie', $cat1->id, $cat1->name).'"><span class="fliesstext text_normal ellipsis"'.($this->params->firma['sitemap_title'] == 'y' ? ' title="'.$cat1->titletag.'"' : '').'">'.$cat1->name.'&nbsp;</span></a>'.CR;
                  $html .= '   </div>';

                  if ($status->sitemap_cat_lev2 == 'y' && $cat1->childs > 0) {
                     $this->sitemapSub($html, $xml, $cat1->id, 2);
                  }

                  $xml  .= '   <url>'."\n";
                  $xml  .= '      <loc>'.$this->params->getLink('kategorie', $cat1->id, $cat1->name).'</loc>'."\n";
                  $xml  .= '      <lastmod>'.$datum.'</lastmod>'."\n";
                  $xml  .= '      <changefreq>weekly</changefreq>'."\n";
                  $xml  .= '      <priority>0.8</priority>'."\n";
                  $xml  .= '   </url>'."\n";
               }
            }

            $html  .= '</div>';
         }

         \file_put_contents(SHOP_PATH.'/sitemap_categories.html', $html);
         \file_put_contents(SHOP_PATH.'/sitemap_categories.xml', $xml);
      }

      else {
         @unlink(SHOP_PATH.'/sitemap_categories.html');
         @unlink(SHOP_PATH.'/sitemap_categories.xml');
      }

      $sitemap = Control::getSitemap();
      $sitemap->sitemapXml();
   }

   private function sitemapSub(&$html, &$xml, $parent, $level) {
      $childs = $this->childs;
      $datum  = date('Y-m-d');

      foreach ($childs[$parent] as $c) {
         $cat   = (object)$c;

         $html .= '   <div class="cat_level cat_level'.$level.'">'.CR;
         $html .= '      <a href="'.$this->params->getLink('kategorie', $cat->id, $cat->name).'"><span class="fliesstext text_normal ellipsis"'.($this->params->firma['sitemap_title'] == 'y' ? ' title="'.$cat->titletag.'"' : '').'>'.$cat->name.'</span></a>'.CR;
         $html .= '   </div>';

         $xml  .= '   <url>'."\n";
         $xml  .= '      <loc>'.$this->params->getLink('kategorie', $cat->id, $cat->name).'</loc>'."\n";
         $xml  .= '      <lastmod>'.$datum.'</lastmod>'."\n";
         $xml  .= '      <changefreq>weekly</changefreq>'."\n";
         $xml  .= '      <priority>0.8</priority>'."\n";
         $xml  .= '   </url>'."\n";

         if ($cat->childs > 0) {
            $this->sitemapSub($html, $xml, $cat->id, $level + 1);
         }
      }
   }
}
