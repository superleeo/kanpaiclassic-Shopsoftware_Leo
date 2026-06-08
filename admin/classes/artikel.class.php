<?php
/*
###################################################################################
  Kanpai Classic Shopsoftware Entwicklungsstand: 14.01.2021 Version 11

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
require_once SHOP_PATH.'/classes/base/articles_base.class.php';

class KANPAICLASSIC_artikel extends KANPAICLASSIC_articlesBase
{
   // DEBUG
   private $last_sql                 = '';

   // Artikel-Liste
   private $listmode                 = 'artikel';     // Artikel-Liste oder Artikel auswählen
   private $bildmode                 = '';            // ID oder Bild in Liste anzeigen
   private $pager                    = '';            // HTML für Pager (Liste)
   private $article_list             = '';            // HTML für Liste
   private $search                   = '';            // Suchstring für _dataList

   // Artikle-Details
   private $main                     = null;          // (objekt) Hauptartikel / wir in articleDetail() gesetzt
   private $mode                     = '';            // detail oder foto
   private $seo                      = null;          // Speicherung seo_data
   private $editors                  = '';            // Inhalt für Editor (Artikeltext)

// ersetzt  private $parent                   = 0;
//   private $parent_id                = -1;            //

   private $ebay_data                = [];            // Speicherung ebay_data

//    private $parent_data              = [];
   private $merkmal1                 = 0;             // ???
   private $merkmal2                 = 0;             // ???
   private $modul_id                 = 0;             // Livedesigner2

   function __construct() {
      parent::__construct();
      $this->bildmode = (\KANPAICLASSIC\Helper::getData('image_id', 'y') == 'y' ? 'id' : 'bild');
//      $this->testAjax();
   }

   // Einzige Function, die aufgerufen werden darf
   public function getContent() {
      // Funktionen Artikel-Liste
      switch ($this->params->func) {
         // Startseite Artikel - Artikelliste
         case '':
            $this->listmode = 'artikel';
            $_SESSION['listcategorie'] = false;
            unset($_SESSION['listcategorie_catid']);
            unset($_SESSION['listcategorie_catname']);

            // Pager generieren
            $this->_getCounter();

            $print_title = true;
            include ADMIN_PATH.'/templates/artikel_liste.tpl.php';
            return;

           // break;

         case 'imageId':
            \KANPAICLASSIC\Helper::setData('image_id', 'y');
            exit(header('Location: '.ADMIN_URL_IDX.'/artikel'));

           // break;

         case 'imageBild':
            \KANPAICLASSIC\Helper::setData('image_id', 'n');
            exit(header('Location: '.ADMIN_URL_IDX.'/artikel'));

           // break;

         // Liste als Popup ausgeben / Ajax
         case 'listePopup':
            $this->listmode = $this->params->postString('listmode');
            $parent_id      = $this->params->postInt('parent_id');
            $print_title    = true;
            $html           = '';

            $this->_getCounter();

            // Lädt Daten !!!
            include ADMIN_PATH.'/templates/artikel_liste_sub.tpl.php';

            echo json_encode(['status' => 'ok', 'html' => $html]);
            exit;
           // break;

         // Inhalt Liste aktualisieren / Ajax
         case 'liste':
            $this->listmode = $this->params->postString('listmode');
            $parent_id      = $this->params->postInt('parent_id');
            $this->modul_id = $this->params->postInt('modul_id');
            $html           = '';

            // Nur bei Hauptartikel Counter generieren
            if ($parent_id == 0) {
               $this->_getCounter();
            }

            // Lädt Daten !!!
            include ADMIN_PATH.'/templates/artikel_liste_sub.tpl.php';

            echo json_encode(['status' => 'ok', 'html' => $this->article_list, 'pager' => $this->pager]);
            exit;
           // break;

         //
         //
         case 'subArticles':
            $this->modul_id = $this->params->postInt('modul_id');
            echo json_encode(['status' => 'ok', 'inhalt' => $this->articleListSub()]);
            exit;

         // Artikel suchen
         case 'find':
            $this->search   = $this->params->postString('search');
            $this->listmode = $this->params->postString('listmode');
//            // $articleList = $this->_printList(0);
            $this->_printList(0);
            $this->_getCounter();
            $this->modul_id = $this->params->postInt('modul_id');

            echo json_encode(['status' => 'ok', 'html' => $this->article_list, 'pager' => $this->pager]);
            exit;
          //  break;

         // Anzahl Artikel pro Seite ändern / Ajax - Seite wird von Ajax aktualisiert
         case 'count':
            $this->modul_id = $this->params->postInt('modul_id');

            if ($this->params->postInt('count') > 0) {
               $_SESSION['artikel_limit'] = $this->params->postInt('count');
            }

            $_SESSION['admin_artikel_seite'] = 0;
            echo json_encode(['status' => 'ok']);
            exit;
          //  break;

         // Aktuelle Seiten-Nr. ändern / Ajax - Seite wird von Ajax aktualisiert
         case 'seite':
           $this->modul_id = $this->params->postInt('modul_id');
           $seite = $this->params->postInt('seite');
            $_SESSION['admin_artikel_seite'] = $seite;

            if ($_SESSION['listcategorie']) {
               $_SESSION['listcategorie'] = false;
               unset($_SESSION['listcategorie_catid']);
               unset($_SESSION['listcategorie_catname']);
            }

            echo json_encode(['status' => 'ok']);
            exit;
          //  break;

         // Artikel sortiert anzeigen / Ajax
         case 'sort':
            $this->modul_id = $this->params->postInt('modul_id');
            $_SESSION['artikel_sort'] = $this->params->postInt('sort_id');
            $_SESSION['artikel_dir']  = $this->params->postString('sort_dir');
            $search                   = ($this->params->postString('search') == '' ? false : true);
            $this->listmode           = $this->params->postString('listmode');

            $this->_printList(0);
            $html         = $this->article_list;

            echo json_encode(['status' => 'ok', 'inhalt' => $html]);
            exit;

         // Artikel / Variante löschen (Liste und Details)
         case 'listeDelete':
//            $this->delete($this->params->postInt('parent_id'), $this->params->postInt('article_id'));
            $this->listeDelete($this->params->postInt('article_id'));

            echo(json_encode(['status' => 'ok']));
            $this->sitemap();
            exit;
          //  break;

         // Online-Status ändern
         case 'online':
            $this->_online();
            break;

         case 'saveList':
            $this->saveList();
            echo json_encode(['status' => 'ok']);
            exit;
           // break;

         case 'addBestellung':
            $articleList = $this->articleList();
            $this->getCounter();
            $this->listmode = 'bestellungen';
            include ADMIN_PATH.'/templates/artkel_liste.tpl.php';
            echo json_encode(['status' => 'ok', 'html' => $html]);
            exit;

         // Artikel einer Kategorie anzeigen
         case 'listcategorie':
            $cat_id = $this->params->postInt('cat_id');
            $this->listmode = 'artikel';

            if ($cat_id >= 0) {
               if ($this->params->postInt('listcategorie_back') == 0) {
                  $_SESSION['admin_artikel_seite'] = 0;
               }

               $_SESSION['listcategorie']         = true;
               $_SESSION['listcategorie_catid']   = $this->params->postInt('cat_id');
               $_SESSION['listcategorie_catname'] = $this->params->postString('cat_name');
            }

            // Pager generieren
            $this->_getCounter();

            $print_title = true;
            include ADMIN_PATH.'/templates/artikel_liste.tpl.php';
            return;

         /* *********** Modul Pdfkatalog ******* */
         // Konfigurations-Popup anzeigen
         // 01.01.2018
         case 'pdfkatalogPopup':
            $pdfkatalog = Control::getModulePdfkatalog();
            $config = $pdfkatalog->getConfig();

            echo json_encode(['status' => 'ok', 'html' => $config]);
            exit;

         // Konfiguration speichern
         // 01.01.2018
         case 'pdfkatalogSave':
            $pdfkatalog = Control::getModulePdfkatalog();
            $config = $pdfkatalog->saveConfig();
            return;

         // PDF ausgeben (Updload in Modul)
         // 01.01.2019
         case 'pdfkatalogUpload':
            $pdfkatalog = Control::getModulePdfkatalog();
            $config = $pdfkatalog->upload();
            return;

         case 'pdfkatalogPrint':
            $pdfkatalog = Control::getModulePdfkatalog();
            $config = $pdfkatalog->printPdf();
            return;
      }

      // Funktionen Artikel-Detailseite
      switch ($this->params->func) {
         case 'detail':
            // Aufruf ohne parent_id
            if (!isset($this->params->add_params[0])) {
               header('Location: '.ADMIN_URL);
               exit;
            }

            $parent_id = $this->params->add_params[0];

            if ((int)$parent_id == 0) {
               $this->_newMainArticle();
            }

            // Kategorien des Artikels
            $catList   = '';
            $cat_array = [];
            $cat_ids   = $this->_getCatIds($parent_id);   // Kategorien aus shop_article_to_cats

            if(!$cat_ids) {
               $cat_ids = [];
               $cat_ids[0] = (object)['cat_id' => 0];
            }

            $category  = Control::getKategorie();
            $max_cats  = $category->max_cats;

            // Hauptkategorie als Select-Box anzeigen
            // Anzahl vorhandener Kategorien < CONF_MAX_KAT (config.inc.php) - max. Anzahl Kategorien für Auswahl
            if ($max_cats < CONF_MAX_KAT) {
               // Selectbox für Hauptkategorie
               $catList = $category->catList($cat_ids[0]->cat_id, false, false);

               if (count($cat_ids) > 0) {
                  // Hauptkategorie übergehen
                  for ($i = 1; $i < count($cat_ids); $i++) {
                     $cat_array[] = $category->catListMax($cat_ids[$i]->cat_id, false, false);
                  }
               }

               $catclone = $category->catListMax(0, false, false);
            }

            // Alle Kategorien anzeigen
            else {
               if (is_array($cat_ids) && count($cat_ids) > 0) {
                  for ($i = 0; $i < count($cat_ids); $i++) {
                     if ($i == 0) {
                        $cat_array[] = $category->catListMax($cat_ids[$i]->cat_id, true, true);
                     }

                     else {
                        $cat_array[] = $category->catListMax($cat_ids[$i]->cat_id, true, false);
                     }
                  }
               }

               $maincat_id   = $category->maincat_id;
               $maincat_name = $category->maincat_name;
               $catclone     = $category->catListMax(0, false, false);  // Vorlage für 'Kategorie hinzufügen'
            }

            $gshop   = Control::getImportExport();
            $details = $this->articleDetail($parent_id);

            include ADMIN_PATH.'/templates/artikel_details.tpl.php';
            return;

         // Hauptartikel anlegen, neue parent_id zurück liefern
         // 24.07.2019
         case 'getParentId':
            $_POST['parent_id']  = 0;
            $_POST['steuersatz'] = 1;
            // Kein break !!!

         // Artikel speichern, mit Varianten
         // 24.07.2019
         case 'articleSave':
            $back = $this->articleSave();

            if ($back[0] === true) {
               echo json_encode(['status' => 'ok', 'new_id' => $back[1]]);
               $this->sitemap();
            }

            else {
               echo json_encode(['status' => 'error', 'msg' => $back[1]]);
            }

            exit;
          //  break;

         // Neu Variante vis AJAX hinzufügen
         // 21.06.2019
         case 'varianteNew':
            $parent_id = $this->params->postInt('parent_id');
            $html = $this->articleDetailSub($parent_id, true);
            exit(json_encode(['status' => 'ok', 'html' => $html]));
          //  break;

         // Variante löschen
         // 21.06.2019
         case 'deleteVariante':
            $this->deleteVariante();
            break;

         // Haupt- und Sub-Artikel neu laden (AJAX), evtl. mit neuem Sub-Artikel (addsub = 1)
         case 'DELrefresh':
            $parent = $this->params->postInt('parent_id'); // ToDo ändern article / parent - ID
            $oldlang = $this->params->selected_lang;
            $this->params->selected_lang = $this->params->postString('sellang', 'deu');

            if (strlen($this->params->selected_lang) < 3) {
               $this->params->selected_lang = 'deu';
            }

            $back1      = $this->articleDetail($parent);
            $back2      = $this->articleDetailSub($parent_id);
            $staffelung = $this->getStaffelung($parent_id);

            // Neue Variante hinzufügen
            if ($this->params->postInt('addsub') == 1) {
               $this->merkmaleOptionsListe(0);
               $this->wertList(0);
               $parent_artnr = $this->params->postString('parent_artnr');
               $back2 .= $this->articleDetailSub($parent, true, $parent_artnr);
            }

            $seo = $this->db_extern->querySingleObject("SELECT metatitle, metadesc, metakey FROM #__articles_seo WHERE parent_id = $parent AND lang = '".$this->params->selected_lang."'");
            $this->params->selected_lang = $oldlang;
            echo json_encode(['status' => 'ok', 'main' => $back1, 'inhalt' => $back2, 'staffelung' => $staffelung, 'google' => $this->_getGoogle(), 'metatitle' => $seo->metatitle, 'metadesc' => $seo->metadesc, 'metakey' => $seo->metakey]);
            exit;
            break;

            // Artikel duplizieren
            // 08.08.2019
            case 'articleCopy':
            $this->articleCopy();
            break;

         // Bilder hochladen
         // 12.06.2019
         case 'imageUpload':
            $this->imageUpload();
            break;

         // Bilder hochladen
         // 12.06.2019
         case 'moreImages':
            $more_images = $this->moreImages($this->params->postInt('parent_id'));

            exit(json_encode(['status' => 'ok', 'html' => $more_images['html'].$more_images['script']]));
            break;

         // Bild löschen
         // 12.06.2019
         case 'imageDelete':
            $this->imageDelete();
            break;

         case 'videoUpload':
            header('Content-Type: application/json');
            $this->videoUpload();
            break;

         // Video löschen
         // 11.02.2021
         case 'videoDelete':
             $this->videoDelete();
             break;

         // Artikel-GrafikBild löschen
         // 12.06.2019
         case 'imageDeleteAg':
            $this->imageDeleteAg();
            break;

         // Bild löschen
         // 12.06.2019
         case 'imageDeleteFileupload':
            $this->imageDeleteFileupload();
            break;

         // Bild Sortierung ändern
         // 04.07.2019
         case 'fileinputSort':
            $this->fileinputSort();
            break;

         case 'videoSort':
            $this->videoSort();
            break;

         // Bilder sortieren
         // 12.06.2019
         case 'sortImage':
            $this->sortImage();
            break;

         // Poppup für Eingabe der Werte ausgeben
         // 13.06.2019
         case 'merkmalePopup':
            exit(json_encode(['status' => 'ok', 'html' => $this->merkmalePopup()]));
            break;

         // Merkmale aus Popup speichern
         // 13.06.2019
         case 'merkmaleSave':
            $ids = $this->merkmaleSave();
            $merkmal1_html = $this->_merkmaleOptionsListe($this->params->postInt('merkmal1_id'), 1);
            $merkmal2_html = $this->_merkmaleOptionsListe($this->params->postInt('merkmal2_id'), 2);

            exit(json_encode(['status' => 'ok', 'merkmal1_html' => $merkmal1_html, 'merkmal2_html' => $merkmal2_html, 'update' => $ids, 'debug' => $this->last_sql]));
            break;

         case 'merkmalChanged':
            $html = $this->_werteOptionsListe(0, $this->params->postInt('merkmal_id'), $this->params->postInt('pos'));
            exit(json_encode(['status' => 'ok', 'html' => $html]));
            break;

         case 'wertImageDelete':
            echo $this->wertImageDelete();
            break;

         case 'wertePopup':
            echo $this->wertePopup();
            break;

         case 'werteSave':
            if ($this->werteSave()) {
               echo json_encode(['status' => 1]);
            }
            else {
               echo json_encode(['status' => 0, 'msg' => 'DB-Fehler', 'debug' => $this->db_extern->last_sql]);
            }
            return;

         case 'werteNew':
            $this->werteNew();
            return;

         case 'getmerkmal':
            $out = $this->editGetMerkmalList();
            if ($out) {
               echo json_encode(['status' => 1, 'inhalt' => $out, 'debug' => $this->db_extern->last_sql]);
            }
            else {
               echo json_encode(['status' => 0, 'msg' => 'DB-Fehler', 'debug' => $this->db_extern->last_sql]);
            }
            return;

         case 'wertUpload':
            echo $this->_werteUploadSave();
            exit;

         case 'newWert':
            echo $this->_newWert();
            exit;

         case 'wertImgDelete':
            echo $this->_wertImgDelete();
            exit;

         case 'eanCheck':
            $status = $this->params->postCheckbox('status');
            $mode   = $this->params->postString('mode');

            if ($mode == 'ean') {
               $this->db->query("UPDATE #__firma SET ean_check = '$status' WHERE id = 1");
            }

            else {
               $this->db->query("UPDATE #__firma SET downloads = '$status' WHERE id = 1");
            }

            exit(\json_encode(['status' => 'ok']));
            break;

         case 'grundeinheitenPopup':
            $this->grundeinheitenPopup();
            break;

         case 'grundeinheitenSave':
            $parent_id      = $this->params->postInt('parent_id');
            $grundeinheit   = $this->params->postString('grundeinheit');
            $ge_netto_aktiv = $this->params->postCheckbox('ge_netto_aktiv');

            $this->db_extern->query("UPDATE #__articles_info SET grundeinheit = '$grundeinheit', ge_netto_aktiv = '$ge_netto_aktiv' WHERE id = $parent_id");
            exit(json_encode(['status' => 'ok']));
            break;

         case 'rechnerPopup':
            $this->rechnerPopup();
            break;

         // Neue Zeile Staffelung hinzufügen
         // 05.07.2019
         case 'staffelungAdd':
            $parent_id = $this->params->postInt('parent_id');
            $html      = $this->getStaffelung($parent_id);

            if ($html != '') {
               exit(json_encode(['status' => 'ok', 'html' => $html]));
            }

            else {
               exit(json_encode(['status' => 'error', 'msg' => 'Fehler 123']));
            }

            break;


         /* ****************** Google (in Tools de/aktiviert) ************************* */
         // Google-Kategorien per Ajax senden
         case 'googlecats':
            $imex = Control::getImportExport();
            echo json_encode(['status' => 'ok', 'googlecats' => $imex->getGoogleCatOptions($this->params->postString('cats'))]);
            return;


      }

      // Funktionen Module
      switch ($this->params->func) {
         /* ****************** Modul Foto ************************* */
         // Artikel aus Uploaud-Verzeichnis generieren
         case 'saveFotoartikel':
            $this->_saveFotoartikel();
            return;

         case 'fotoClean';
            $out = $this->_fotoClean();
            return;

         // Status aus Datei zurückgeben
         case 'fotoStatus':
            $datei = SHOP_PATH.'/tmp/fotos.txt';

            if (file_exists($datei)) {
               $fh = fopen($datei, 'r');
               echo fread($fh, filesize($datei));
               fclose($fh);
            }

            else { // AJAX stoppen
               echo json_encode(['status' => 'stop', 'msg' => 'Status konnte nicht festgestellt werden']);
            }

            exit;
            break;

         /* ****************** Modul Dateiupload ************************* */
         // Downloadartikel speichern
         case 'downloadArticleUpload':
            echo $this->downloadArticleUpload();
            break;

         // Downloadartikel löschen
         case 'downloadArticleDelete':
            echo $this->downloadArticleDelete();
            break;

         // Downloadartikel löschen
         case 'downloadArticleDownload':
            echo $this->downloadArticleDownload();
            break;

         /* ********** Modul Preismatrix ******** */
         // 10.07.2019
         case 'matrixPopup':
            $matrix = Control::getModuleMatrix();
            $matrix->popup($this->params->postInt('article_id'));
            break;

         // Matrix Hauptartikel zu Variante kopieren
         // 10.07.2017
         case 'matrixCopy':
            $matrix = Control::getModuleMatrix();
            $matrix->copy($this->params->postInt('article_id'));
            break;

         // Popup-Artikelmatrix speichern
         // 10.07.2019
         case 'matrixSave':
            $matrix = Control::getModuleMatrix();
            $matrix->save($this->params->postInt('art_id'));
            break;

         // Artikle-Matrix CSV-Datei importieren
         //
         case 'matrixImport':
            $matrix = Control::getModuleMatrix();
            $matrix->importCsv();
            break;

         /* ****************** Modul Megakonfigurator ************************* */
         case 'configuratorMerkmalePopup':
            $this->configurator->merkmalePopup();
            return;

         case 'configuratorMerkmaleSave':
            $this->configurator->merkmaleSave();
            return;

         case 'configuratorWertePopup':
            echo $this->configurator->wertePopup();
            return;

         case 'configuratorWertePopupSave':
            $this->configurator->werteSave();
            return;

         case 'configuratorUpload':
            echo $this->configurator->werteUpload();
            exit;

         case 'configuratorWerteOptions':
            $wert = $this->configurator->WerteOptions($this->params->postInt('merkmal_id'), $this->params->postInt('wert_id'));
            echo json_encode(['status' => 'ok', 'wert' => $wert]);
            exit;

         case 'configuratorTextePopup':
            $this->configurator->textePopup();
            return;

         case 'configuratorSaveText':
            $this->configurator->texteSave();
            return;

         case 'configuratorImgDelete':
            echo $this->configurator->wertImgDelete();
            exit;

         case 'loadConfiguratorLine':
            $this->configurator->loadConfiguratorLine();
            exit;


         // *********** Modul mixer_artikel ******************
         // Mixer-Popup für Artikelliste
         case 'mixerPopup':

             $_SESSION['listcategorie'] = false;
             unset($_SESSION['listcategorie_catid']);
             unset($_SESSION['listcategorie_catname']);

            $this->listmode = 'mixer';
            $parent_id      = 0;
            $html           = '';

//            $this->_getCounter();

            $print_title = true;
            include ADMIN_PATH.'/templates/artikel_liste_sub.tpl.php';

            echo json_encode(['status' => 'ok', 'html' => $html]);
            exit;
            break;

         // Mixer-Artikel der Liste hinzufügen
         case 'mixerAdd':
            $this->_mixerAdd($this->params->postInt('parent_id'), $this->params->postInt('article_id'));
            break;

         // Sortierung Ähnliche geändert
         case 'mixerSave':
            $this->_mixerSave($this->params->postInt('parent_id'));
            break;

         // Mixer löschen
         case 'mixerDelete':
            $this->_mixerDelete($this->params->postInt('db_id'), $this->params->postInt('parent_id'));
            break;

         /* ****************** Modul Nährwerte ************************* */
         case 'saveNaehrwerte':
            $this->_saveNaehrwerte();
            $this->_saveZutaten();
            echo json_encode(['status' => 'ok']);
            exit;

         /* ********** Modul 360° ******** */
         case 'load360':
            $_360grad = Control::getModule360grad();
            $_360grad->loadImages(false);
            break;

         case 'refresh360':
            $_360grad = Control::getModule360grad();
            $_360grad->loadImages(true);
            break;

         case 'imageSave360':
            $_360grad = Control::getModule360grad();
            $_360grad->saveImage();
            break;

         case 'delete360':
            $_360grad = Control::getModule360grad();
            $_360grad->deleteImages();
            break;


         // *********** Modul Musikplayer
         // Upload -> imageUpload
         case 'musikplayerSave':
            $musikplayer = Control::getModuleMusikplayer();
            $musikplayer->save();
            breaak;

         case 'musikplayerDelete':
            $musikplayer = Control::getModuleMusikplayer();
            $musikplayer->delete();
            break;

         case 'DELmusikplayerUpload';
            $musikplayer = Control::getModuleMusikplayer();
            $this->imageUpload();
//            $musikplayer->upload();
            break;

         // *********** Modul zubehoermodul ******************
         // Popup Zubehör anzeigen (artikel_liste_sub.tpl.php)
         // 15.07.2019
         case 'zubehoerPopup':
            $this->listmode = 'zubehoer';
            $parent_id      = 0;
            $html           = '';

            $this->_getCounter();

            $print_title = true;
            include ADMIN_PATH.'/templates/artikel_liste_sub.tpl.php';

            exit(json_encode(['status' => 'ok', 'html' => $html, 'pager' => $this->pager]));
            break;

         case 'livedesigner2Popup':


             $_SESSION['listcategorie'] = false;
             unset($_SESSION['listcategorie_catid']);
             unset($_SESSION['listcategorie_catname']);


            $this->modul_id = $this->params->postInt('modul_id');
            $this->listmode = 'livedesigner2';
            $parent_id      = 0;
            $html           = '';

            $this->_getCounter();

            $print_title = true;
            include ADMIN_PATH.'/templates/artikel_liste_sub.tpl.php';

            exit(json_encode(['status' => 'ok', 'html' => $html, 'pager' => $this->pager]));
            break;

         // Popup Zubehör-Artikel speichern (aus Popup)
         // 15.07.2019
         case 'zubehoerAdd':
            $this->zubehoerAdd($this->params->postInt('art_id'), $this->params->postInt('zubehoer_id'));
            break;

         // Popup Zubehör-Artikel speichern (aus Popup)
         // 15.07.2019
         case 'zubehoerSave':
            $this->zubehoerSave($this->params->postInt('parent_id'));
            break;

         // Zubehör-Artikel löschen
         // 15.07.2019
         case 'zubehoerDelete':
            $this->zubehoerDelete($this->params->postInt('db_id'), $this->params->postInt('parent_id'));
            break;

         // *********** Modul aehnliche_artikel ******************
         // Popup Ähnliche anzeigen (artikel_liste_sub.tpl.php)
         // 15.07.2019
         case 'aehnlichePopup':
            $this->listmode = 'aehnliche';
            $parent_id      = 0;
            $html           = '';

            $this->_getCounter();

            $print_title = true;
            include ADMIN_PATH.'/templates/artikel_liste_sub.tpl.php';

            echo json_encode(['status' => 'ok', 'html' => $html, 'pager' => $this->pager]);
            exit;
            break;

         // Popup Ähnliche-Artikel speichern (aus Popup)
         // 15.07.2019
         case 'aehnlicheAdd':
            $this->aehnlicheAdd($this->params->postInt('art_id'), $this->params->postInt('aehnliche_id'));
            break;

         // Sortierung Ähnliche geändert
         case 'aehnlicheSave':
            $this->aehnlicheSave($this->params->postInt('parent_id'));
            break;

         // Ähnliche aus Liste löschen
         case 'aehnlicheDelete':
            $this->aehnlicheDelete($this->params->postInt('db_id'), $this->params->postInt('parent_id'));
            break;

         /* ****************** Modul Zubehör-Slider / Crosspromo ************************* */
         // Artikel-Slider speichern
         case 'zubehoersliderSave':
            $slider = Control::getModuleZubehoerSlider();
            $slider->save();
            break;

         // Artikel-Slider Bild-Upload
         case 'DELsliderUpload':
            $slider = Control::getModuleZubehoerSlider();
            $slider->upload();
            break;

         // Artikel-Slider Bild löschen
         case 'zubehoersliderDelete':
            $slider = Control::getModuleZubehoerSlider();
            $slider->delete();
            break;

         /* ****************** Modul Ebay ************************* */
         // Ebay-Kategorien per Ajax senden
         case 'ebaycats':
            $ebay = Control::getEbay();
            echo json_encode(['status' => 'ok', 'ebaycats' => $ebay->getCatList($_POST['parent_id'], $_POST['e_cats']), 'ebay_options' => $ebay->ebay_options]);
            return;

         // Ebay-Einstellungen speichern
         case 'ebaysave':
            $ebay = Control::getEbay();
            $ebay->saveEbay();
            $data = $ebay->getData($this->params->postInt('e_id'));
            echo json_encode(['status' => 1, 'data' => $ebay->printEbayDetails($this->params->postInt('e_id'), $data)]);
            exit;

         // Tools: Ebay Kategorien neu lesen (von Ebay)
         case 'loadEbay':
            $ebay = Control::getEbay();
            $data = $ebay->GetCategories();
            return;

         case 'ebayShopOptions':
            $ebay = Control::getEbay();
            $ebay->getSellerProfiles();
            return;

         case 'ebayShopOptionsFile':
            $ebay = Control::getEbay();
            $ebay->getSellerProfiles(true);
            return;

         // Ebay Token erneuern
         case 'ebayShopOptionsSave':
            $ebay = Control::getEbay();
            $ebay->shopOptionsSave();
            return;

         // Ebay Token erneuern
         case 'resetEbay':
            $ebay = Control::getEbay();
            $data = $ebay->resetToken();

            return;

         // Artikel zu Ebay hochladen
         case 'ebayAdd':
            $ebay = Control::getEbay();
            $ebay->articleToEbay();
            return;

         /* ****************** Modul Artikel-Timer ************************* */
         case 'timerSave':
            $this->timerSave();
            break;

         case 'timerSync':
            exit(json_encode(['status' => 'ok', 'time' => round(microtime(true) * 1000)]));
            break;

            /* ****************** Modul AmazonOrders ************************* */
         // Artikel zu Amazon hochladen
         case 'amazonAdd':
            $amazon = Control::getModuleAmazon();
            $amazon->articleToAmazon();
            return;

         // Modul artikel_defragmentieren
         // Artikel in DB Sortieren / Reorganisieren
         // 01.03.2019
         case 'reorg':
            echo json_encode(['status' => 'ok', 'inhalt' => $this->_reorg()]);
            exit;

         // Modul bildformat von Design/Einstellungen aufgerufen - Neuerstellung Bilder starten
         // 29.05.2019
         case 'rebuildImages':
            // Keine Ausgabe !!!
            $test = $this->_rebuildImages();

            if (!$test) {
               exit(json_encode(['status' => 'stop', 'msg' => 'Keine Artikel gefunden']));
            }

            return;

         // Modul bildformat von Design/Einstellungen aufgerufen - Neuerstellung Bilder Status amzeigen
         // 29.05.2019
         case 'rebuildStatus':
            $datei = SHOP_PATH.'/tmp/rebuild.txt';

            if (file_exists($datei)) {
               $json      = file_get_contents($datei);
               $statistik = json_decode($json);

               if (!isset($statistik->status)) {
                  var_dump($statistik);
               }
               $status     = $statistik->status;

               // Erfolgreich abgeschlossen
               if ($status == 'stop') {
                  unlink($datei);
                  exit($json);
               }

               exit($json);
            }

            else { // AJAX stoppen
               exit(json_encode(['status' => 'failed', 'msg2' => 'Bitte nochmals starten', 'msg' => 'Fehler bei der Verarbeitung. Bitte nochmals starten.']));
            }

            return;

         // Modul Portal
         // Artikel zur Bestellung hinzufügen
         case 'haendler':
            $_SESSION['admin_haendler_id'] = $this->params->add_params[0];
            $this->haendler_id             = $this->params->add_params[0];
            $_SESSION['haendler_id']       = $this->params->add_params[0];
            $_POST['haendler_id']          = $this->params->add_params[0];
            $this->getCounter();

            include ADMIN_PATH.'/templates/artikel_liste_sub.tpl.php';
            return;

         // Nicht verwendet: Artikel suchen Zubehör Suche während Eingabe / Ajax
         //case 'searchStartZ':
         //   echo json_encode(['status' => 'ok', 'inhalt' => $this->searchStart('zubehoerAdd')]);
         //   return;

         // Varianten anzeigen / Liste / Ajax
      }

      // Unbekannte Ajax-Funktion
      if ($this->params->isAjax) {
         exit('AJAX-Funktion '.$this->params->func.'() ist unbekannt');
      }

      return;

   }
/* ************************* Funktionen Liste ************************************** */

   // Liste / Hauptartikel anzeigen
   // 08.07.2019
   // $parent_id == 0 -> alle Hauptartikel: $parent_id != 0 -> Varianten zu Hauptartikel mit $parent_id
   private function _printList($parent_id) {
      $listmode        = $this->listmode;
      $haendler_hidden = '';
      $module          = 0;
      $html            = '';

      // Module nur bei Artikel anzeigen
      if ($listmode == 'artikel') {
         if (defined('CONF_MODULE_EBAY')) {
            $module++;
         }
      }

      $datas = null;

      // Hauptartikel
      if ($parent_id == 0) {
         $datas = $this->_dataList();
      }

      // Varianten
      else {
         $datas = $this->_dataListSub($parent_id);
      }

      if ($datas) {
         // Alle (Haupt)Artikel durchgehen

         // Varianten-Liste
         if ($parent_id > 0) {
            $html .= '<div class="artikel_sub">'.CR;
         }

         for ($i = 0; $i < count($datas); $i++) {
            // data.id        -> ID aus articles
            // data.parent_id -> ID aus article_info

            $data     = $datas[$i];
            $childs   = (int)$data->childs;
            $bildmode = false;

            $ebay_enabled    = false;

            // Check, ob Ebay verfügbar ist
            if (defined('CONF_MODULE_EBAY') && $this->params->firma['ebay_api'] == 'y' && $data->ebay_cats != '') {
               $ebay_enabled = true;
            }

            $pointer = '';
            $bild    = urlencode($data->image);
            $click   = $data->image;

            if ($bild != '') {
               $bildmode = ($this->bildmode == 'bild' ? true : false);

               // Bild extern
               if (substr($bild, 0, 4) == 'http') {
                  $pointer = ' style="cursor:url('.str_replace('.jpg', '', $bild).'_td.jpg), pointer;" ';
               }

               else {
                  // Bild auf Server
                  if (!$this->params->multishop) {
                     if (is_file(SHOP_PATH.'/pictures/'.str_replace('.jpg', '', $bild).'_cur.jpg')) {
                        $pointer = ' style="cursor:url('.SHOP_URL.'/pictures/'.str_replace('.jpg', '', $bild).'_cur.jpg), pointer;" ';
                     }

                     else {
                        $pointer = ' style="cursor:url('.SHOP_URL.'/pictures/'. str_replace('.jpg', '', $bild).'_td.jpg), pointer;" ';
                     }
                  }
                  // auf Multishop-Master
                  else {
                     $pointer = ' style="cursor:url('.\KANPAICLASSIC\Helper::getData('multishop_images').'/pictures/'.str_replace('.jpg', '', $bild).'_td.jpg), pointer;" ';
                  }
               }
            }

            if ($click !== '') {
               if (!(substr($click, 0, 4) == 'http')) {
                  // Bild auf Server
                  if (!$this->params->multishop) {
                     $click = PICTURE_URL.$click.'.jpg';
                  }
                  // Multishop-Master
                  else {
                     $click = \KANPAICLASSIC\Helper::getData('multishop_images').'/pictures/'.$click.'.jpg';
                  }
               }
            }

            // Hauptartikel, Mindesthöhe abhängig von image_id;  y -> ID; n -> Bild statt ID
            if ($parent_id == 0) {
               $html .= '<div id="parentid_'.$data->parent_id.'" class="list_line'.($bildmode ? ' block_bildmode': '').'" data-parent_id ="'.$data->parent_id.'" data-childs="'.((int)$data->childs > 1 ? 'y' : 'n').'">'.CR;
               $html .= '   <div class="artikel_main block_start" data-article_id="'.$data->article_id.'" data-parent="1" data-changed="0">'.CR;
            }

            // Varianten
            else {
               $html .= '   <div class="sub block_start" data-article_id="'.$data->article_id.'" data-parent="0" data-changed="0">'.CR;
            }
// 1. Zuerst Hauptspalte (
            // Hauptspalte - muss an Anfang, da sonst linke Spalte überdeckt wird
            $html .= '      <div class="art_list_right module_'.$module.'">'.CR;
            $html .= $this->_articleListHelper($data, $parent_id, (int)$data->childs, $pointer, $bild, $click, $bildmode);
            $html .= '      </div>'.CR;

// 2. Linke Buttons (bis vor ID)
            // Linke Spalte - Artikel hinzufügen
            $html .= '      <div class="art_list_left">'.CR;
            $html .= '         <div class="'.($listmode == 'artikel' ? 'list_hide' : 'list_show').'">'.CR;
            $html .= '            <div class="add_bestellung button_ci" onclick="Bestellungen.bestellungAdd('.$data->id.')" title="Artikel der Bestellung hinzufügen">+</div>'.CR;
            $html .= '            <div class="add_zubehoer button_ci" onclick="Zubehoer.add('.$data->id.')" title="Artikel als Zubehör hinzufügen">+</div>'.CR;
            $html .= '            <div class="add_aehnliche button_ci" onclick="Aehnliche.add('.$data->id.')" title="Artikel zu Ähnlichen Artikeln hinzufügen">+</div>'.CR;
            $html .= '            <div class="add_mixer button_ci" onclick="Artikelmixer.add('.$data->id.')" title="Artikel dem Mixer hinzufügen">+</div>'.CR;
            $html .= '            <div class="add_livedesigner2 button_ci" onclick="Livedesigner2.addArticleSelected('.$this->modul_id.', '.$data->id.')" title="Artikel dem Modul hinzufügen">+</div>'.CR;

            // Online
            if ($childs > 1) {
               $html .= '            <div class="list_online'.($listmode == 'artikel'? ' pointer' : '').' has_clients '.(($data->online == 'y') ? 'fas fa-check' : 'fas fa-times').'"></div>'.CR;
            }

            // Keine Varianten
            else {
               $html .= '            <div class="list_varianten '.(($data->online == 'y') ? 'fas fa-check' : 'fas fa-times').'"></div>'.CR;
            }

            $html .= '         </div>'.CR;

            // Nur bei normaler Anzeige
            // Linke Spalte - Bestellung hinzufügen
            // Edit
            $html .= '         <div class="'.($listmode == 'artikel' ? 'list_show' : 'list_hide').'">'.CR;

            // Hauptartikel
            if ($parent_id == 0) {
               $html .= '            <div class="list_edit fas fa-pencil-alt pointer"><a href="'.ADMIN_URL_IDX.'/artikel/detail/'.$data->parent_id.'" title="bearbeiten"></a></div>'.CR;
            }

            // Varianten
            else {
               $html .= '            <div class="list_edit fas "></div>'.CR;
            }

            // Online / Varianten
            if ($parent_id == 0 && $childs > 1) {
               $html .= '            <div class="list_online pointer '.(($data->online == 'y') ? 'fas fa-check' : 'fas fa-times').'" onclick="Artikel.online(this, '.((int)$data->sort == 1 ? $data->parent_id : 0).', '.$data->id.', '.$data->childs.');" title="'.($data->online == 'y' ? 'alle Varianten deaktivieren' : 'alle Varianten aktivieren').'"></div>'.CR;
            }

            else {
               $html .= '            <div class="list_online pointer '.(($data->online == 'y') ? 'fas fa-check' : 'fas fa-times').'" onclick="Artikel.online(this, '.((int)$data->sort == 1 ? $data->parent_id : 0).', '.$data->id.', '.$data->childs.');" title="'.($data->online == 'y' ? 'deaktivieren' : 'aktivieren').'"></div>'.CR;
            }

            // Löschen
            $html .= '            <div class="list_del far fa-trash-alt pointer" onclick="Artikel.listeDelete(this, '.$data->id.', '.($parent_id == 0 ? $data->parent_id : 0).');" title="'.($parent_id == 0 ? 'Artikel löschen' : 'Variante löschen').'"></div>'.CR;
            $html .= '         </div>'.CR;

            // Linke Spalte gemeinsam - Erweitern
            // Keine Varianten
            if ($childs < 2) {
               $html .= '         <div class="list_open fas"></div>'.CR;
            }

            // Mit Varianten  + / - anzeigen
            else {
               $html .= '         <div title="Varianten anzeigen" class="list_open fas fa-plus pointer" id="open_'.$data->parent_id.'" onclick="Artikel.varianten(this, '.$data->parent_id.');"></div>'."\n";
            }

            $html .= '        <div class="clear"></div>'.CR;
            $html .= '      </div>'.CR; // Linke Spalte Ende

// 3. Shop-Module (ganz rechts
            // Spalte Module / rechts
            $html .= '      <div class="art_list_extra module_'.$module.'">'.CR;

            if ($ebay_enabled && $data->ebay_cats != '') {
               if ($data->varianten == 'y' || ($data->auktion == 'y' && (float)$data->startpreis > 0.00) || ($data->auktion == 'n' && (float)$data->festpreis > 0.00)) {
                  $html .= '<div class="export_ebay ebay_symbol pointer" onclick="Ebay.ebayAdd('.$data->parent_id.');" title="zu Ebay hochladen"></div>';
               }
            }

            $html .= '        <div class="clear"></div>'.CR;
            $html .= '      </div>'.CR;
            $html .= '   </div>'.CR;

            if ($parent_id == 0) {
               $html .= '</div>'.CR;
            }
         } // for

         if ($parent_id > 0) {
            $html .= '</div>'.CR;
         }
      }

      else {
         $html .= '';
      }
      $this->article_list = $html;

      return;
   }

   // Liste / gemeinsam für Haupt-/ Sub-Artikel anzeigen (art_list_right)
   // 30.12.2018
   private function _articleListHelper($data, $parent_id, $childs = 0,  $pointer = '', $bild = '', $click = '', $bildmode = false) {
      $menge           = (float)$data->menge;
      $name            = $data->name;
      $sort            = (int)$data->sort;
      $html            = '';
      $haendler_hidden = '';
      $is_foto         = '';

      if ($data->is_foto == 'y') {
         $is_foto = ' disabled="disabled"';
      }

      // Pointer bei Artikel-Nr
      // Bei Google-Shops
      if ($parent_id == 0 && (int)$data->g_id > 0 && $data->g_cat != '') {
         $name .= ' (g)';
      }

      $html .= '   <div class="fixed_left">'.CR;

      // ID
      // Falls Sub-Artikel "-" ausgeben
      if ($childs > 0 && (int) $data->sort == 1) {
         // Hauptartikel
         if ($bildmode) {
//            $html .= '      <div class="art_list1 list_col ellipsis" onclick="showImage(\''.$click.'\');">'.CR;
            $html .= '      <div class="art_list1 list_col ellipsis" onclick="showImage(\''.$click.'\', \''.$data->parent_id.($childs > 1 ? '-1' : '').'\');">'.CR;
            $html .= '         <img class="list_img pointer" src="'.PICTURE_URL.$bild.'_td.jpg" />'.CR;
            $html .= '         <div class="list_id button" style="display:none;">'.$data->parent_id.($childs > 1 ? '-1' : '').'</div>'.CR;
            $html .= '      </div>'.CR;
         }

         else {
            $html .= '      <div class="art_list1 list_col ellipsis"'.$pointer.' onclick="showImage(\''.$click.'\');">'.$data->parent_id.($childs > 1 ? '-1' : '').'</div>'.CR;
         }
      }

      // Keine Subartikel
      else {
         $html .= '      <div class="art_list1 list_col ellipsis">'.$data->parent_id.'-'.$data->sort.'</div>'.CR;
      }

      // Sortierung
      // Sortierung nur bei Hauptartikel
// TODO Bei Portal nicht änderbar (außer Masteradmin)
      if ($sort == 1) {
         $html .= '      <div class="art_list2 list_col ellipsis">'.CR;
         $html .= '         <input class="txt_inp sortirung" type="text" class="sortierung" value="'.$data->sortierung.'" onchange="$(this).closest(\'.block_start\').attr(\'data-changed\', 1);" />'.CR;
         $html .= '      </div>'.CR;
      }

      else {
         $html .= '      <div class="art_list2 list_col ellipsis"></div>'.CR;
      }

      // Art-Nr
      $html .= '      <div class="art_list3 list_col ellipsis" title="'.$data->art_nr.'">'.$data->art_nr.'</div>'.CR;
      $html .= '   </div>'.CR;

      // Artikel
      $html .= '   <div class="art_list4 list_col ellipsis">'.$name.'</div>'.CR;

      // Wert1
      $html .= '   <div class="art_list5 list_col ellipsis">'.$data->wert1.'</div>'.CR;

      // Wert2
      $html .= '   <div class="art_list6 list_col ellipsis">'.$data->wert2.'</div>'.CR;

      $html .= '   <div class="fixed_right">'.CR;

      // netto
      $html .= '      <div class="art_list7 list_col ellipsis">'.CR;
      $html .= '         <input type="text" '.$is_foto.' class="txt_inp netto_show" value="'.number_format((float)$data->netto, 2, ',', '.').'" onchange="Artikel.compute(this, \'netto\', '.$data->id.')" />'.CR;
      $html .= '         <input type="hidden" class="netto" value="'.$data->netto.'" />'.CR;
      $html .= '      </div>'.CR;

      // Angebot
      $html .= '      <div '.$is_foto.' class="art_list8 list_col ellipsis">'.CR;
      $html .= '         <input type="checkbox" class="newdesign check" id="check_'.$data->article_id.'" '.($data->angebot_active == 'y' ? ' checked="checked"' : '').'onchange="Artikel.compute(this, \'check\', '.$data->id.');" /><label for="check_'.$data->article_id.'"></label>'.CR;
      $html .= '         <input type="text" class="txt_inp angebot_show" value="'.number_format((float)$data->angebot, 2, ',', '.').'" onchange="Artikel.compute(this, \'angebot\', '.$data->id.');" />'.CR;
      $html .= '         <input type="hidden" class="angebot" value="'.$data->angebot.'" />'.CR;
      $html .= '      </div>'.CR;


      // Brutto
      if ($data->angebot_active == 'y') {
         $rechnen = $data->angebot;
      }
      else {
         $rechnen = $data->netto;
      }

      if ($this->params->firma['kleingewerbe'] == 'y' || $this->params->firma['tax_active'] == 'n') {
         $html .= '      <div class="art_list9 list_col ellipsis">keine MwSt'.CR;
         $html .= '         <input type="hidden" class="brutto_show" value="" />'.CR;
         $html .= '         <input type="hidden" class="brutto" value="" />'.CR;
         $html .= '      </div>'.CR;
      }

      else {
         $html .= '      <div class="art_list9 list_col ellipsis article_brutto">'.CR;
         $html .= '         <input type="text" '.$is_foto.' class="txt_inp brutto_show" value="'.number_format((float)$rechnen * (1 + $this->params->firma['tax'.$data->steuersatz] / 100), 2, ',', '').'" onchange="Artikel.compute(this, \'brutto\');" />'.CR;
         $html .= '         <input type="hidden" class="brutto" value="'.($rechnen * (1 + $this->params->firma['tax'.$data->steuersatz] / 100)).'" />'.CR;
         $html .= '         <input type="hidden" class="steuer" value="'.$this->params->firma['tax'.$data->steuersatz].'" />'.CR;
         $html .= '      </div>'.CR;
      }


      // Lager
      $html .= '      <div class="art_list10 list_col ellipsis">'.CR;
      $html .= '         <input type="text" class="txt_inp menge" value="'.number_format($menge, ($data->masse_check == 'y' ? (int)$data->masse_komma : 0), ',', '').'" onchange="$(this).closest(\'.block_start\').attr(\'data-changed\', 1); '.($data->masse_check == 'y' ? 'this.value = point2komma(parseFloat(komma2point(this.value)).toFixed('.$data->masse_komma.'));' : '').'" />'.CR;
      $html .= '      </div>'.CR;
      $html .= '   </div>'.CR;
      $html .= '   <div class="clear"></div>'.CR;

      return $html;
   }

   // Daten für Listen / Hauptartikel zusammenstellen
   // $search !== '' : Artikel suchen ($_POST['search']
   // 30.12.2018
   private function _dataList () {
      $search = $this->search;
      $lang   = $this->params->selected_lang;
      $suche  = '';

      // Verknüpfung mit Ebay, wenn Modul ebay vorhanden
      $ebay_fields = '';
      $ebay_join   = '';

      if (defined('CONF_MODULE_EBAY') && $this->params->firma['ebay_api'] == 'y') {
         $ebay_fields = ", e.cat_ids AS ebay_cats, e.auktion, e.festpreis, e.startpreis, e.varianten ";
         $ebay_join   = " LEFT JOIN #__articles_to_ebaycats AS e ON e.article_id = a.parent_id ";
      }

      // Bilder
//      $pics = '';

      $sql =  "SELECT i.id AS parent_id, i.shop_id, i.childs, i.steuersatz, i.name_$lang as name, i.masse_check, i.masse_komma, i.rechner_check,
                               i.rechner_mode, i.gewicht, i.grundeinheit, i.ge_netto_aktiv, image,
                               a.id, a.id as article_id, i.sortierung, a.online, a.art_nr, a.netto, a.angebot, a.angebot_active, a.gtin as ean,
                               a.menge, a.ge_menge, i.is_foto, a.sort, a.startbild, ge_netto, ge_menge,
                               m.merkmal_$lang as merkmal1, w.wert_$lang as wert1, mm.merkmal_$lang as merkmal2, ww.wert_$lang as wert2,
                               g.parent_id AS g_id, g.categories AS g_cat
                               $ebay_fields
                  FROM #__articles_info as i
               LEFT JOIN #__articles as a
                  ON a.parent_id = i.id
               LEFT JOIN #__merkmale as m
                  ON a.merkmal1 = m.id
               LEFT JOIN #__werte as w
                  ON a.wert1 = w.id
               LEFT JOIN #__merkmale as mm
                  ON a.merkmal2 = mm.id
               LEFT JOIN #__werte as ww
                  ON a.wert2 = ww.id
               LEFT JOIN #__articles_to_googlecats AS g
                  ON a.parent_id = g.parent_id
               $ebay_join
               WHERE i.childs > 0 AND a.sort = 1 ";    // Hauptartikel


      // Artikel einer Kategorie
      // zubehoer / aehnliche Kategorie nicht einschränken
      if (isset($_SESSION['listcategorie']) && $_SESSION['listcategorie'] === true && $this->listmode != 'zubehoer' && $this->listmode != 'aehnliche') {
         $sql .= " AND a.parent_id IN (SELECT ac.parent_id FROM #__article_to_cats AS ac WHERE ac.cat_id = ".$_SESSION['listcategorie_catid'].")";
      }

      // Sortierung auf/absteigend - Default aufsteigend
      if (!isset($_SESSION['artikel_dir'])) {
         $_SESSION['artikel_dir'] = 'asc';
      }

      $dir = $_SESSION['artikel_dir'];

      // Sortierung der Artikel
      if (!isset($_SESSION['artikel_sort'])) {
         $_SESSION['artikel_sort'] = 3;
      }

      $sort = $_SESSION['artikel_sort'];

      // Suche nach Artikeln
      if ($search) {
         $suche = $this->params->postString('search', '', 'sql');

         if ($suche != '') {
            // Suche in Beschreibung und Bestellnummer
            if ($this->params->postInt('all') == 1) {
               $sql .= " AND (i.name_$lang LIKE '%" . $suche ."%' ";
//               $sql .= " OR a.art_nr LIKE '%" . $suche ."%' ";
//               $sql .= " OR a.gtin LIKE '%" . $suche ."%' ";  // EAN
//               $sql .= " OR a.mpn LIKE '%" . $suche ."%' ";
               $sql .= " OR i.marke LIKE '%" . $suche ."%') ";
               $sql .= " OR i.marke LIKE '%" . $suche ."%' ";
               $sql .= " OR (SELECT COUNT(*) FROM #__articles WHERE parent_id = a.parent_id AND art_nr LIKE '%".$suche."%') > 0";
               $sql .= " OR (SELECT COUNT(*) FROM #__articles WHERE parent_id = a.parent_id AND gtin LIKE '%".$suche."%') > 0";
               $sql .= " OR (SELECT COUNT(*) FROM #__articles WHERE parent_id = a.parent_id AND mpn LIKE '%".$suche."%') > 0 ";
            }

            // Suche einzelnen Artikel nach ID
            else {
               $sql .= " AND i.id = " . $this->params->postInt('search') . " ";
            }
         }
      }

      // Sortierung nach ID, ArtNr Beschreibung usw
      switch ($sort) {
         case "1":   // Online
            $sql .= " GROUP BY a.id ORDER BY a.sort, a.online $dir, a.id $dir ";
            break;

         case "2":   // ID
            $sql .= " GROUP BY a.id ORDER BY a.sort, i.id $dir ";
            break;

         case "3":   //Sortierung
            $sql .= " GROUP BY a.id ORDER BY a.sort, i.sortierung $dir, a.id $dir ";
            break;

         case "4":   // Artikel-Nr.
            $sql .= " GROUP BY a.id ORDER BY a.sort, a.art_nr $dir ";
            break;

         case "5":   // Artikelname
            $sql .= " GROUP BY a.id ORDER BY a.sort, i.name_$lang $dir ";
            break;

         case "6":   // Menge
            $sql .= " GROUP BY a.id ORDER BY a.sort, a.menge $dir, a.id $dir ";
            break;
      }

      if (isset($_SESSION['artikel_limit'])) {
         $limit = $_SESSION['artikel_limit'];
      }

      else {
         $limit = CONF_ART_PER_SITE;
         $_SESSION['artikel_limit'] = $limit;
      }

      if (isset($_SESSION['admin_artikel_seite'])) {
         $seite = $_SESSION['admin_artikel_seite'];
      }

      else {
         $seite = 0;
         $_SESSION['admin_artikel_seite'] = $seite;
      }

      $sql  .= " LIMIT " . $seite * $limit . ", $limit";
      $datas = $this->db_extern->queryAllObjects($sql);

      if ($suche != '' && $datas) {
         $data = $datas;
         $datas = [];

         foreach ($data as $d) {
            if ((int)$d->sort > 1) {
               continue;
            }

            $datas[] = $d;
         }

      }


      return $datas;
   }

   // Daten für Listen / Varianten zusammenstellen
   // 30.12.2018
   private function _dataListSub($parent_id) {
//      $parent = $this->params->postInt('parent_id');
        $lang  = $this->params->selected_lang;
//      $html  = '';

      $ebay_fields = '';
      $ebay_join   = '';

      if ($this->params->firma['ebay_api'] == 'y') {
         $ebay_fields = ", e.cat_ids AS ebay_cats, e.auktion, e.festpreis, e.startpreis, e.varianten ";
         $ebay_join = " LEFT JOIN #__articles_to_ebaycats AS e ON e.article_id = a.parent_id ";
      }

      $sql = "SELECT a.id, a.id AS article_id, a.parent_id, a.art_nr, a.sort, a.online as online, a.netto, a.angebot, a.angebot_active, a.menge, a.ge_menge, ge_netto, a.startbild,
                     i.steuersatz, i.masse_check, i.masse_komma, i.rechner_check, i.rechner_mode, 0 AS childs,
                     i.image, i.sortierung, i.name_$lang as name, i.is_foto, i.grundeinheit, i.ge_netto_aktiv,
                     m.merkmal_$lang as merkmal1, w.wert_$lang as wert1, mm.merkmal_$lang as merkmal2, ww.wert_$lang as wert2
                     $ebay_fields
              FROM #__articles as a
              LEFT JOIN #__articles_info as i
                 ON a.parent_id = i.id
              LEFT JOIN #__merkmale AS m
                 ON a.merkmal1 = m.id
              LEFT JOIN #__werte AS w
                 ON a.wert1 = w.id
              LEFT JOIN #__merkmale AS mm
                 ON a.merkmal2 = mm.id
              LEFT JOIN #__werte AS ww
                 ON a.wert2 = ww.id
              $ebay_join
              WHERE a.parent_id = $parent_id AND a.sort > 1
              ORDER BY a.sort ASC";

      $datas = $this->db_extern->queryAllObjects($sql);

      return $datas;
   }

   // Liste / Anzeige Anzahl Seiten usw.
   // 30.12.2018
   private function _getCounter() {
      $seite = isset($_SESSION['admin_artikel_seite']) ? $_SESSION['admin_artikel_seite'] : 0; // aktuelle Seite z. Anzeigen
      $limit = isset($_SESSION['artikel_limit']) ? $_SESSION['artikel_limit'] : CONF_ART_PER_SITE;
      $lang  = $this->params->selected_lang;

      $html  = '<div class="pager_left"><span class="erg_text">Ergebnisse pro Seite</span>'.CR;

      // Liste Anzahl Artikel pro Seite
      for ($i = CONF_ART_PER_SITE; $i <= CONF_ART_MAX; $i += CONF_ART_PER_SITE) {
         $html .= '<span class="rahmen'.($i == $limit ? ' counter_active' : '').'" onclick="Artikel.count('.$i.');">'.$i.'</span>'.CR;
      }

      $html .= '</div>'.CR;

      $sql_haendler = '';
      $sql_suche    = '';

      // Suche in Beschreibung und Bestellnummer
      if ($this->search !== '') {
         $suche = $this->search;
         $sql_suche .= " AND (i.name_$lang LIKE '%" . $suche ."%' ";
         $sql_suche .= " OR a.art_nr LIKE '%" . $suche ."%' ";
         $sql_suche .= " OR a.gtin LIKE '%" . $suche ."%') ";
      }

      // Suche
      if ($sql_suche != '') {
//         $sql = "SELECT COUNT(DISTINCT i.id) as anzahl FROM #__articles_info AS i, #__articles AS a WHERE a.parent_id = i.id AND i.childs > 0 $sql_haendler $sql_suche";
         $sql = "SELECT COUNT(DISTINCT i.id) as anzahl FROM #__articles_info AS i, #__articles AS a WHERE a.parent_id = i.id AND i.childs > 0 $sql_haendler $sql_suche";
      }

      // Alle Artikel
      else {
//         $sql = "SELECT COUNT(i.id) as anzahl FROM #__articles_info AS i, #__articles AS a WHERE a.parent_id = i.id AND i.childs > 0 $sql_haendler";
         $sql = "SELECT COUNT(i.id) as anzahl FROM #__articles_info AS i, #__articles AS a WHERE a.parent_id = i.id AND a.sort = 1 $sql_haendler";
      }

      // Artikel einer Kategorie
      // zubehoer / aehnliche Kategorie nicht einschränken
      if (isset($_SESSION['listcategorie']) && $_SESSION['listcategorie'] === true && $this->listmode != 'zubehoer' && $this->listmode != 'aehnliche') {
         $sql .= " AND i.id IN (SELECT ac.parent_id FROM #__article_to_cats AS ac WHERE ac.cat_id = ".$_SESSION['listcategorie_catid'].")";
      }

      if ($this->search != '') {

      }

      $anzahl = $this->db_extern->querySingleValue($sql);
      $html  .= '<div class="pager_right">'.CR;

      if ($anzahl) {
         $start = 0;                              // Start mit Seite
         $von   = $seite * $limit + 1;            // Art. von
         $bis   = ($seite + 1) * $limit;          // Art. bis
         $ende  = (int)floor(($anzahl - 1) / $limit);   // max. Seiten

         // Korrekturen bei letzer Seite
         if ($seite == $ende && ($ende * $limit < $anzahl)) {
            $bis = $anzahl;
         }

         if ($seite > 0) {
            $html .= '<div class="first fas fa-angle-double-left active" onclick="Artikel.seite(0);"></div>'.CR;
         }

         else {
            $html .= '<div class="first fas fa-angle-double-left inactive"></div>'.CR;
         }

         if ($seite > 0) {
            $html .= '<div class="back fas fa-angle-left active" onclick="Artikel.seite('.($seite - 1).');"></div>'.CR;
         }

         else {
            $html .= '<div class="back fas fa-angle-left inactive"></div>'.CR;
         }

         $html .= '<div class="vonbis">'.$von.' - '.$bis.' von '.$anzahl.'</div>'.CR;

         if ($seite  < $ende) {
            $html .= '<div class="next fas fa-angle-right active" onclick="Artikel.seite('.($seite + 1).');"></div>'.CR;
         }

         else {
            $html .= '<div class="next fas fa-angle-right inactive"></div>'.CR;
         }

         if ($seite < $ende) {
            $html .= '<div class="end fas fa-angle-double-right active" onclick="Artikel.seite('.$ende.');"></div>'.CR;
         }
         else {
            $html .= '<div class="end fas fa-angle-double-right inactive"></div>'.CR;
         }
      }
      else {
         $html .= 'keine Artikel vorhanden'.CR;
      }

      $html .= '</div>'.CR;
      $html .= '<div class="clear"></div>'.CR;

      $this->pager = $html;
   }

   // Aus Liste: Artikel löschen, bei sort == 1 gesamten Artikel, sonst nur Variante mit article_id
   // 09.07.2019
   private function listeDelete($article_id) {
      $parent    = $this->db_extern->querySingleObject("SELECT parent_id, sort FROM #__articles WHERE id = $article_id");
      $parent_id = (int)(isset($parent->parent_id) ? (int)$parent->parent_id : 0);

      if ($parent_id > 0) {
         $sort      = (int)$parent->sort;

         // Variante löschen
         $this->db_extern->query("DELETE FROM #__articles WHERE id = $article_id");

         // Bei sort == 1 wird Artikel / Variante gelöscht, hier nicht notwendig
         if ($sort > 1) {
            // Anzahl childs anpassen
            $this->db_extern->query("UPDATE #__articles_info SET childs = childs - 1 WHERE id = $parent_id");
            // Sortierung anpassen
            $this->db->query("UPDATE #__articles SET sort = sort -1 WHERE parent_id = $parent_id AND sort > $sort");

            // Modul Ähnliche Verknüpfung mit Variante
            $this->db_extern->query("DELETE FROM #__articles_aehnliche WHERE parent_id = $parent_id");

            // Modul Zubehör Verknüpfung mit Variante
            $this->db_extern->query("DELETE FROM #__articles_zubehoer WHERE parent_id = $parent_id");

            // Modul Preismatrix Verknüpfung mit Variante
            $this->db_extern->query("DELETE FROM #__matrix WHERE art_id = $article_id");

            return true;
         }

         // Hauptartikel (sort == 1)
         $image     = $this->db_extern->querySingleValue("SELECT image FROM #__articles_info WHERE id = $parent_id");
         $images    = $this->db_extern->queryAllObjects("SELECT image FROM #__articles_images WHERE parent_id = $parent_id ORDER BY sort");

         // Bilder löschen
         $uploaddir = SHOP_PATH.'/'.CONF_PICT_PATH;

         if (is_array($images)) {
            $images[] = (object)['image' => $image];
         }

         else {
            $images   = [];
            $images[] = (object)['image' => $image];
         }

         for ($i = 0; $i < count($images); $i++) {
            $picture = $images[$i]->image;

            if (file_exists($uploaddir.'original/'.$picture.'.jpg')) {
               unlink($uploaddir.'original/'.$picture.'.jpg');
               $files = glob($uploaddir.$picture.'*.*');

               for ($f = 0; $f < count($files); $f++) {
                  unlink($files[$f]);
               }
            }
         }

         // Hauptartikel und Varianten löschen
         $varianten = $this->db_extern->queryAllObjects("SELECT id FROM #__articles WHERE parent_id = $parent_id");
         $this->db_extern->query("DELETE FROM #__articles WHERE parent_id = $parent_id");
         $this->db_extern->query("DELETE FROM #__articles_info WHERE id = $parent_id");
         $this->db_extern->query("DELETE FROM #__articles_seo WHERE parent_id = $parent_id");

         // Verknüpfung mit Kategorien löschen
         $this->db_extern->query("DELETE FROM #__article_to_cats WHERE parent_id = $parent_id");

         // Modul Ähnliche
         $this->db_extern->query("DELETE FROM #__articles_aehnliche WHERE parent_id = $parent_id");

         // Modul Zubehör
         $this->db_extern->query("DELETE FROM #__articles_zubehoer WHERE parent_id = $parent_id");
         $this->db_extern->query("DELETE FROM #__articles_zubehoer_lang WHERE parent_id = $parent_id");

         // Modul Mixer-Artikel
         $this->db_extern->query("DELETE FROM #__articles_mixer WHERE parent_id = $parent_id");

         // Module Naehrwerte/Zutaten
         $this->db_extern->query("DELETE FROM #__articles_naehrwerte WHERE parent_id = $parent_id");
         $this->db_extern->query("DELETE FROM #__articles_zutaten WHERE parent_id = $parent_id");

         // Modul MusikPlayer
         if (defined('CONF_MODULE_MUSIKPLAYER')) {
            $musikplayer = Control::getModuleMusikplayer();
            $musikplayer->deleteAll($parent_id);
         }

         // Modul 360grad
         if (defined('CONF_MODULE_360GRAD')) {
            // DB wird nicht verwendet!!!
            // $images = $this->db->queryAllObjects("SELECT img_name FROM #__articles_360grad WHERE parent_id = $parent_id");

            for ($i = 1; $i < 37; $i++) {
;               if (is_file(SHOP_PATH.'/'.CONF_PICT_PATH.'360grad/'.$parent_id.'/image_'.sprintf('%03d', $i).'.jpg')) {
                  unlink(SHOP_PATH.'/'.CONF_PICT_PATH.'360grad/'.$parent_id.'/image_'.sprintf('%03d', $i).'.jpg');
               }

               if (is_file(SHOP_PATH.'/'.CONF_PICT_PATH.'360grad/'.$parent_id.'/original/image_'.sprintf('%03d', $i).'.jpg')) {
                  unlink(SHOP_PATH.'/'.CONF_PICT_PATH.'360grad/'.$parent_id.'/original/image_'.sprintf('%03d', $i).'.jpg');
               }
            }

            if (is_dir(SHOP_PATH.'/'.CONF_PICT_PATH.'360grad/'.$parent_id.'/original/')) {
               rmdir(SHOP_PATH.'/'.CONF_PICT_PATH.'360grad/'.$parent_id.'/original/');
               rmdir(SHOP_PATH.'/'.CONF_PICT_PATH.'360grad/'.$parent_id.'/');
            }
         }

         // Module Ebay
         if (defined('CONF_MODULE_EBAY')) {
            $this->db_extern->query("DELETE FROM #__articles_to_ebaycats WHERE article_id = $parent_id");
         }

         // Modul Preismatrix Verknüpfung mit Varianten
         if (defined('CONF_MODULE_MATRIX') && !empty($varianten)) {
            foreach ($varianten as $article_id) {
               $this->db_extern->query("DELETE FROM #__matrix WHERE art_id = $article_id->id");
            }
         }

         // Google-Katgorien
         $this->db_extern->query("DELETE FROM #__articles_to_googlecats WHERE parent_id = $parent_id");

         // Sitemap neu erstellen
         $this->sitemap($oldstatus = '');

         return true;
      }

      return false;
   }

   // Artikel On-/Offline setzen
   // 30.12.2018
   private function _online() {
      $parent_id  = $this->params->postInt('parent_id');
      $article_id = $this->params->postint('article_id');
      $online     = $this->params->postCheckbox('online');
      $sub        = $this->params->postString('sub');

      // Hauptartikel - alle Varianten aktivieren / deaktivieren
      if ($parent_id > 0 && $sub == 'all') {
         $this->db_extern->query("UPDATE #__articles SET online = '$online' WHERE parent_id = $parent_id");
      }

      else {
         $this->db_extern->query("UPDATE #__articles SET online = '$online' WHERE id = $article_id");
      }

      echo json_encode(['status' => 'ok']);
      $this->sitemap();
      exit;
   }

   private function saveList() {
      $article_id    = $this->params->postInt('article_id');
      $netto         = $this->params->postFloat('netto');
      $angebot       = $this->params->postFloat('angebot');
      $menge         = $this->params->postFloat('menge');
      $check_angebot = $this->params->postCheckbox('check');

      if ($article_id > 0) {
         $data = $this->db_extern->querySingleObject("SELECT  a.netto, a.angebot, a.angebot_active, a.ge_menge, a.ge_netto, a.parent_id, a.sort, i.ge_netto_aktiv, i.grundeinheit
                                                         FROM #__articles AS a, #__articles_info AS i
                                                      WHERE a.id = $article_id AND a.parent_id = i.id");

         $ge_netto     = (float)$data->ge_netto;

         // Nur wenn Grundpreise aktiv - Grundpreis neu berechnen
//         if ($data->ge_netto_aktiv == 'y' && ($netto != $data->netto || $angebot != (float)$data->angebot)) {
         if ($data->ge_netto_aktiv == 'y' && ($data->angebot_active != $check_angebot || $netto != $data->netto || $angebot != (float)$data->angebot)) {
            // Achtung Kehrwert !
            $db_netto     = (float)($data->angebot_active == 'n' ? $data->netto : $data->angebot);
            $grundeinheit = $data->grundeinheit;

            $ber_netto = ($check_angebot == 'n' ? $netto : $angebot);
            $ge_netto  = $ber_netto * (float)$data->ge_menge;

            // Auf Grundeinheit korrigieren
            switch ($grundeinheit) {
               case '10g':
               case '10ml':
               case '10cm':  $ge_netto *= 10; break;
               case '100g':
               case '100ml':
               case '100cm': $ge_netto *= 100; break;
               case 'cm2':   $ge_netto *= 100; break;
               case 'dm2':   $ge_netto *= 10000; break;
               case 'cm3':   $ge_netto *= 1000; break;
               case 'dm3':   $ge_netto *= 1000000; break;
            }
         }
      }

      $this->db_extern->query("UPDATE #__articles SET
                           netto          = '$netto',
                           angebot        = '$angebot',
                           angebot_active = '$check_angebot',
                           menge          = '$menge',
                           ge_netto       = '$ge_netto'
                        WHERE id = $article_id");

      // Sortierung bei Hauptartikel
      if ((int)$data->sort == 1) {
         $this->db_extern->query("UPDATE #__articles_info SET sortierung = '".$this->params->postInt('sortierung')."' WHERE id = $data->parent_id");
      }

      return true;
   }

/* ************************* Funktionen Details ************************************** */
   // Artikel-Detail-Seite anzeigen
   private function articleDetail($parent_id) {
      $this->mode = 'detail';
      $lang       = $this->params->selected_lang;
      $childs     = 0;

      // (Haupt)artikel vorhanden -> aus DB lesen
      if ($parent_id > 0) {
         // alle vorhandenen Sparachen
          $seo_data  = $this->db_extern->querySingleObject("SELECT metaauto, metatitle, metadesc, metakey FROM #__articles_seo WHERE parent_id = $parent_id AND lang = '$lang'");

          // SEO aus DB
          if ($seo_data) {
              $this->seo = ['auto' => $seo_data->metaauto, 'title' => $seo_data->metatitle, 'desc' => $seo_data->metadesc, 'key' => $seo_data->metakey];
         }

         // Default-Werte für SEO
         else {
            $this->seo = ['auto' => 'y', 'title' => '', 'desc' => '', 'key' => ''];
         }

         // Hauptartikel aus DB lesen ($this->main)
         $sql = "SELECT
                    i.id AS parent_id, i.childs, i.steuersatz, i.staffelung, i.motiv_uploadp_check, i.motiv_uploadt_check,
                    i.grundeinheit, i.grundeinheit_rechner, i.spalten2_check, i.ge_netto_aktiv, i.spalten2_check, i.versand_preis,
                    i.masse_check, i.masse_min, i.masse_komma, i.rechner_check, i.rechner_mode,
                    i.widerruf, i.lieferfrist, i.gewicht, i.gew_check, i.name_$lang AS name, i.desc_$lang AS `desc`, i.is_foto, i.foto_set, org_set, i.artikelgruppe, i.image, i.image_hover,
                    i.marke, i.vpe, i.vpm, i.configurator_check, i.configurator, i.configurator_artnr_check, i.config_einheit_check,
                    i.config_menge_check, i.show_object, i.fsk_check, i.neu_check, i.ab_check, i.spedition, i.marke_aktiv,
                    i.versandfrei_check, i.artikelgrafik1_check, i.artikelgrafik2_check, i.artikelgrafik3_check, i.artikelgrafik4_check, i.artikelgrafik5_check, i.artikelgrafik6_check,
                    i.naehrwerte_check, i.mixer_gewicht_check, i.mixer_gewicht, i.mixer_artikel_check, i.mixer_naehrwerte_check,
                    a.id as article_id, a.online, a.art_nr, a.netto, a.angebot, a.angebot_active, a.menge, a.ge_menge,
                    a.merkmal1, a.wert1, a.merkmal2, a.wert2, a.filename, a.haendler_netto, a.gtin, a.mpn, a.ge_netto, a.matrix, a.sort, i.energy_efficiency, i.energy_efficiency_image
                 FROM #__articles_info as i
                 LEFT JOIN #__articles as a
                    ON a.parent_id = $parent_id
                 WHERE i.id = $parent_id
                    AND a.sort = 1";

         $main = $this->db_extern->querySingleObject($sql);
         $main->images = $this->db_extern->queryAllObjects("SELECT id, sort, image FROM #__articles_images WHERE parent_id = $parent_id ORDER BY sort");

         // Artikel nicht gefunden
         if (!isset($main->childs)) {
            exit(header('Location: '.ADMIN_URL_IDX.'/artikel'));
         }

         $childs = $main->childs;

         if (defined('CONF_FOTOGRAF')) {
            $this->mode = ($main->is_foto == 'y' ? 'foto' : 'detail');
         }

         // Zugehörige Daten aus articles_to_googlecats
         $google = $this->db_extern->querySingleObject("SELECT * FROM #__articles_to_googlecats WHERE parent_id = $parent_id");

         // Werte für Google-Shopping
         if ($google) {
            $main->g_id      = $google->parent_id;
            $main->g_cats    = $google->categories;
            $main->g_zustand = $google->zustand;
         }

         if (defined('CONF_MODULE_EBAY') && $this->params->firma['ebay_api'] == 'y') {
            $ebay = Control::getEbay();
            $main->ebay_data = $ebay->getData($parent_id);
         }


         $this->main = $main;
      }

      // Neuer (Haupt)artikel
      else {
         $this->main = $this->_newMainArticle();
         $this->main->sort = 0;
      }

      if ((int)$this->main->steuersatz == 0) {
         $this->main->steuersatz = 1;
      }

      $this->main->steuer = $this->_getSteuerSelect($this->main->steuersatz);
      $this->main->module_musikplayer = (int)$this->db->querySingleValue("SELECT count(id) FROM #__musikplayer WHERE parent_id = $parent_id AND filename != ''");
      $this->main->module_zubehoer    = (int)$this->db_extern->querySingleValue("SELECT count(id) FROM #__articles_zubehoer  WHERE parent_id = $parent_id");
      $this->main->module_aehnliche   = (int)$this->db_extern->querySingleValue("SELECT count(id) FROM #__articles_aehnliche WHERE parent_id = $parent_id");
      $this->main->module_slider      = $this->db_extern->querySingleValue("SELECT active FROM #__crosspromo WHERE parent_id = $parent_id");
      $html = '';

      // Hauptartikel / Normale Artikel
      if ($this->main->is_foto != 'y') {
         // Zeile Hauptartikel oben
         $html .= '<div class="article_main block_start'.(defined('CONF_MODULE_MATRIX') == 'y' ? ' matrix' : '').($this->params->firma['downloads'] == 'y' ? ' download' : '').'" data-article_id="'.$this->main->article_id.'" data-changed="'.($parent_id > 0 ? 0 : 1).'">'.CR;
         $html .= '   <input type="hidden" name="parent_id" id="parent_id" value="'.$parent_id.'" />'.CR;
         $html .= '   <input type="hidden" name="grundeinheit" id="grundeinheit" value="'.$this->main->grundeinheit.'" />'.CR;
         $html .= '   <input type="hidden" name="ge_netto_aktiv" id="ge_netto_aktiv" value="'.$this->main->ge_netto_aktiv.'" />'.CR;
         $html .= '   <input type="hidden" name="is_foto" id="is_foto" value="n" />'.CR;
         $html .= '   <input type="hidden" name="foto_set" id="foto_set" value="'.$this->main->foto_set.'" />'.CR;
         $html .= '   <input type="hidden" name="foto_mode" id="foto_mode" value="0" />'.CR;

         $html .= '   <div class="zeile_oben">'.CR;
         $html .= '      <div class="xleft">'.CR;
         $html .= '         <div class="xsymbol">'.CR;
         $html .= '            <input type="checkbox" class="newdesign art_online" id="art_online'.$this->main->sort.'"'.($this->main->online == 'y' ? ' checked="checked"' : '').' onchange="Artikel.articleChange(this);" />'.CR;
         $html .= '            <label class="xonline" for="art_online'.$this->main->sort.'"></label>'.CR;
         $html .= '         </div>'.CR;
         $html .= '         <input type="hidden" class="art_startbild" value="1" />'.CR;
         $html .= '         <span class="xartnr"><input type="text" id="art_artnr" class="art_artnr txt_inp" placeholder="automatisch" value="'.$this->main->art_nr.'" onchange="$(\'#art_artnr2\').val($(this).val()); Artikel.articleChange(this);" /></span>'.CR;
         $html .= '         <span class="xname"><input type="text" id="artikelname" class="art_name txt_inp" id="artikelname" value="'.$this->main->name.'" onchange="$(\'#artikelname2\').val($(this).val());" /></span>'.CR;
         $html .= '      </div>'.CR;

         // Werte/Merkmale / Preise / Menge
         $html .= $this->_articleDetailHelper((int)$this->main->article_id, $this->main->sort, (float)$this->main->netto,
                                             (float)$this->main->angebot, $this->main->angebot_active, (int)$this->main->steuersatz, (int)$this->main->merkmal1,
                                             (int)$this->main->wert1, (int)$this->main->merkmal2, (int)$this->main->wert2, (float)$this->main->menge,
                                             $this->main->masse_check, (int)$this->main->masse_komma, $this->main->filename, $this->main->matrix, true);
         $html .= '   </div>'.CR;
         $html .= '   <div class="clear"></div>'.CR;
         $html .= '   <div class="ean_line easy"'.($this->params->firma['ean_check'] != 'y' ? ' style="display:none;"' : '').'>'.CR;
         $html .= $this->_articleEan ((int)$this->main->steuersatz, (float)$this->main->haendler_netto, $this->main->gtin,
                                         $this->main->mpn, (float)$this->main->ge_netto, $this->main->ge_netto_aktiv, $this->main->grundeinheit,
                                         (float)$this->main->ge_menge, $this->main->gew_check, $this->main->matrix, true);
         $html .= '   </div>'.CR;
         $html .= '</div>'.CR;

         // Artikel hat Subartikel -> alle anzeigen
         if ((int)$this->main->childs > 1) {
            $html .= $this->articleDetailSub($parent_id);
         }
      }

      // Fotoartikel
      else {
         $this->mode = 'foto';
         $html .= $this->articleDetailFoto($this->main->article_id, $parent_id, $this->main->online, $this->main->angebot, $this->main->angebot_active, $this->main->steuersatz, $main->menge, $main->art_nr, $this->main->name, (int)$this->main->foto_set, (int)$this->main->org_set);
      }

      return $html;
   }

   // Varianten ($new = false; sort > 0) anzeigen oder neue Variante ($new = true)
   // 21.06.2019
   private function articleDetailSub($parent_id, $new = false, $parent_artnr = 0) {
      $html = '';
//      $lang = $this->params->selected_lang;
      $subdata = null;

//      $bg = false;
      // Artikel vorhanden
      if (!$new) {
         $sql = "SELECT a.id AS article_id, a.art_nr, a.online, a.sort, a.netto, a.angebot, a.angebot_active, a.startbild, a.sort,
                        a.menge, a.ge_menge, a.merkmal1, a.wert1, a.merkmal2, a.wert2, i.gewicht, i.gew_check, a.filename, a.haendler_netto, a.gtin, a.mpn, a.matrix,
                        i.masse_check, i.masse_komma, i.rechner_check, i.rechner_mode, i.steuersatz, a.ge_netto, i.ge_netto_aktiv, i.grundeinheit, i.gew_check
                 FROM #__articles as a
                    LEFT JOIN #__articles_info as i
                 ON a.parent_id = i.id
                    WHERE a.parent_id = $parent_id AND a.sort > 1
                 ORDER BY a.sort";

         $subdata = $this->db_extern->queryAllObjects($sql);
      }

      else {
         $sql = "SELECT 0 AS article_id, '' AS art_nr, 'y' AS online, 0 AS sort, 0 AS netto, 0 AS angebot, 'n' AS angebot_active, 1 AS startbild, 0 AS ge_netto,
                        1 AS menge, 0 AS ge_menge, 0 AS merkmal1, 0 AS wert1, 0 AS merkmal2, 0 AS wert2, gewicht, gew_check, '' AS filename, 0 AS haendler_netto, '' AS gtin, '' AS mpn, 'n' AS matrix,
                        masse_check, masse_komma, rechner_check, rechner_mode, steuersatz, ge_netto_aktiv, grundeinheit, gew_check
                 FROM #__articles_info
                    WHERE id = $parent_id";

         $maindata = $this->db_extern->querySingleObject($sql);

         // Hauptartikel existiert
         if ($maindata) {
            if ((int)$maindata->steuersatz == 0) {
               $maindata->steuersatz = 1;
            }
            // Einstellungen aus Hauptartikel übernehmen
            $vardata = $this->db_extern->querySingleObject("SELECT art_nr, merkmal1, merkmal2 FROM #__articles WHERE parent_id = $parent_id AND sort = 1");

            if ($vardata) {
               $maindata->merkmal1 = $vardata->merkmal1;
               $maindata->merkmal2 = $vardata->merkmal2;
               $maindata->sort = mt_rand();

               if ($maindata->art_nr !== '') {
                  if (substr($parent_artnr, -2) == '-1') {
                     $maindata->art_nr .= '-'.((int)$this->db_extern->querySingleValue("SELECT MAX(sort) FROM #__articles WHERE parent_id = $parent_id") + 1);
                  }
               }

               $subdata[0] = $maindata;

            }
         }

         // Neuen Hauptartikel anlegen
         else {
            $subdata[0] = new \stdClass();
            $subdata[0] = $this->_newMainArticle();
            $subdata[0]->article_id     = 0;
            $subdata[0]->sort           = 0;
            $subdata[0]->art_nr         = '';
            $subdata[0]->online         = 'y';
            $subdata[0]->sort           = 0;
            $subdata[0]->netto          = 0;
            $subdata[0]->angebot        = 0;
            $subdata[0]->angebot_active = 'n';
            $subdata[0]->startbild      = 1;
            $subdata[0]->ge_netto       = 0;
            $subdata[0]->menge          =  1;
            $subdata[0]->ge_menge       = 0;
            $subdata[0]->wert1          = 0;
            $subdata[0]->wert2          = 0;
            $subdata[0]->filename       = '';
            $subdata[0]->haendler_netto = 0;
            $subdata[0]->gtin           = '';
            $subdata[0]->mpn            = '';
            $subdata[0]->matrix         = 'n';
         }
      }

      // Bei neuer Variante existiert $this->main nicht
      if (!is_object($this->main)) {
         $this->main = new \stdClass();
      }

      $this->main->images = $this->db_extern->queryAllObjects("SELECT id, sort, image FROM #__articles_images WHERE parent_id = $parent_id ORDER BY sort");

      if ($subdata) {
         foreach($subdata as $data) {
            $html .= '<div class="article_variante block_start'.(defined('CONF_MODULE_MATRIX') == 'y' ? ' matrix' : '').($this->params->firma['downloads'] == 'y' ? ' download' : '').'" data-article_id="'.$data->article_id.'" data-changed="'.((int)$data->article_id == 0 ? 1 : 0).'">'.CR;
            $html .= '   <div class="zeile_oben">'.CR;
            $html .= '      <div class="xleft">'.CR;
            $html .= '         <div class="xartnr">'.CR;
            $html .= '            <div class="xsymbol">'.CR;
            $html .= '               <input type="checkbox" class="newdesign art_online" id="art_online'.$data->sort.'"'.($data->online == 'y' ? ' checked="checked"' : '').' onchange="Artikel.articleChange(this);" />'.CR;
            $html .= '               <label class="xonline" for="art_online'.$data->sort.'"></label>'.CR;
            $html .= '               <span class="xdelete pointer far fa-trash-alt" onclick="Artikel.deleteVariante($(this).closest(\'.block_start\'));"></span>'.CR;
            $html .= '            </div>'.CR;
            $html .= '            <input type="text" class="xartnr2 art_artnr txt_inp" value="'.$data->art_nr.'" placeholder="Artikelnummer" onchange="Artikel.articleChange(this);" />'.CR;
            $html .= '         </div>'.CR;

            $html .= '         <div class="xname">'.CR;

            if (defined('CONF_MODULE_VARIANTENBILDER')) {
               $html .= '            <span class="xstartbild">'.$this->_startbildOption((int)$data->startbild).'</span>'.CR;
            }

            else {
               $html .= '            <span class="xstartbild"><input type="hidden" class="art_startbild" name="art_startbild" value="1" /></span>'.CR;
            }

            $html .= '            <span class="xvariantenr">'.((int)$data->article_id > 0 ? 'Var. '.$data->sort : 'neu').'</span>'.CR;
            $html .= '         </div>'.CR;
            $html .= '      </div>'.CR;

            $html .= $this->_articleDetailHelper((int)$data->article_id, mt_rand(), (float)$data->netto,
                                                (float)$data->angebot, $data->angebot_active, (float)$data->steuersatz, (int)$data->merkmal1,
                                                (int)$data->wert1, (int)$data->merkmal2, (int)$data->wert2, (float)$data->menge,
                                                $data->masse_check, (int)$data->masse_komma, $data->filename, $data->matrix, false);
            $html .= '   </div>'.CR;
            $html .= '   <div class="clear"></div>'.CR;
            $html .= '   <div class="ean_line easy"'.($this->params->firma['ean_check'] != 'y' ? ' style="display:none;"' : '').'>';
            $html .= $this->_articleEan((int)$data->steuersatz, $data->haendler_netto, $data->gtin, $data->mpn, $data->ge_netto, $data->ge_netto_aktiv, $data->grundeinheit, $data->ge_menge, $data->gew_check, $data->matrix, false);
            $html .= '   </div>'.CR;
            $html .= '</div>'.CR;
         }
      }

      return $html;
   }

   // Detail / Gemeinsame Ausgaben für Haupt- und Subartikel
   // 21.06.2019
   private function _articleDetailHelper ($article_id, $sort, $netto, $angebot, $angebot_active, $steuersatz,
                                         $merkmal1, $wert1, $merkmal2, $wert2, $menge,
                                         $masse_check, $masse_komma, $filename, $matrix, $main) {

      $steuer = (float)$this->params->firma['tax'.$steuersatz];
      $html   = '      <div class="xcenter">'.CR;

      // Merkmale 1
      if ($main) {
         $html  .= '         <div class="xmerkmal1">'.$this->_merkmaleOptionsListe($merkmal1, 1).'</div>'.CR;
      }

      else {
         $html  .= '         <div class="xmerkmal1">'.$this->_merkmalVal($merkmal1).'</div>'.CR;
      }

      // Werte 1
      $html .= '         <div class="xwert1">'.$this->_werteOptionsListe($wert1, $merkmal1, 1).'</div>'.CR;

      // Merkmale 2
      if ($main) {
         $html .= '         <div class="xmerkmal2">'.$this->_merkmaleOptionsListe($merkmal2, 2).'</div>'.CR;
      }

      else {
         $html .= '         <div class="xmerkmal2">'.$this->_merkmalVal($merkmal2).'</div>'.CR;
      }

      // Werte 2
      $html .= '            <div class="xwert2">'.$this->_werteOptionsListe($wert2, $merkmal2, 2).'</div>'.CR;

      // netto
      $html .= '            <div class="xnetto">'.CR;

      if ($main) {
         $html .= '               <input type="text" class="netto_show right txt_inp" value="'.number_format((float)$netto, 2, ',', '.').'" onchange="Artikel.compute(this, \'netto\')" />'.CR;
         $html .= '               <input type="hidden" class="art_netto netto" value="'.$netto.'" />'.CR;
      }

      else {
         $html .= '               <input type="text" class="netto_show right txt_inp" value="'.number_format((float)$netto, 2, ',', '.').'" onchange="Artikel.compute(this, \'netto\')" />'.CR;
         $html .= '               <input type="hidden" class="art_netto netto" value="'.$netto.'" />'.CR;
      }

      $html .= '            </div>'.CR;

      // Angebot
      $html .= '            <div class="xangebot">'.CR;

      if ($main) {
         $html .= '               <input type="checkbox" class="newdesign art_check_angebot check" id="check1" '.($angebot_active == 'y' ? ' checked="checked"' : '').'onchange="Artikel.compute(this, \'check\');" />'.CR;
         $html .= '               <label for="check1"></label>'.CR;
         $html .= '               <input type="text" class="angebot_show right txt_inp" value="'.number_format((float)$angebot, 2, ',', '.').'" onchange="Artikel.compute(this, \'angebot\');" />'.CR;
         $html .= '               <input type="hidden" class="art_angebot angebot" value="'.$angebot.'" />'.CR;
      }

      else {
         $html .= '               <input type="checkbox" class="newdesign art_check_angebot check" id="check_'.$sort.'" '.($angebot_active == 'y' ? ' checked="checked"' : '').'onchange="Artikel.compute(this, \'check\');" />'.CR;
         $html .= '               <label for="check_'.$sort.'"></label>'.CR;
         $html .= '               <input type="text" class="angebot_show right txt_inp" value="'.number_format((float)$angebot, 2, ',', '.').'" onchange="Artikel.compute(this, \'angebot\');" />'.CR;
         $html .= '               <input type="hidden" class="art_angebot angebot" value="'.$angebot.'" />'.CR;
      }

      $html .= '            </div>'.CR;

      // Brutto aus Netto oder Angebot berechnen
      $rechnen = ($angebot_active == 'y' ? $angebot : $netto);

      if ($this->params->firma['kleingewerbe'] == 'y' || $this->params->firma['tax_active'] == 'n') {
         $html .= '            <div class="xbrutto">keine MwSt'.CR;
         $html .= '               <input type="hidden" class="brutto_show" value="" onchange="Artikel.compute(this, \'brutto\');" />'.CR;
         $html .= '               <input type="hidden" class="brutto" value="" />'.CR;
         $html .= '            </div>'.CR;
      }

      else {
         $html .= '            <div class="xbrutto">'.CR;

         if ($main) {
            $html .= '               <input type="text" class="brutto_show right txt_inp brutto1" value="'.number_format((float)$rechnen * (1 + $steuer / 100), 2, ',', '').'" onchange="Artikel.compute(this, \'brutto\');" />'.CR;
            $html .= '               <input type="hidden" class="brutto" value="'.($rechnen * (1 + $steuer / 100)).'" />'.CR;
         }

         else {
            $html .= '               <input type="text" class="brutto_show right txt_inp" value="'.number_format((float)$rechnen * (1 + $steuer / 100), 2, ',', '').'" onchange="Artikel.compute(this, \'brutto\');" />'.CR;
            $html .= '               <input type="hidden" class="brutto" value="'.($rechnen * (1 + $steuer / 100)).'" />'.CR;
         }

         $html .= '            </div>'.CR;
      }

      $html .= '      </div>'.CR;

      $html .= '      <div class="xright">'.CR;

      // Lager
      $html .= '         <div class="xlager">'.CR;
      $html .= '            <div class="xdl_menge">';

      if ($filename != '') {
         $html .= '               <span class="xdownload download_button pointer" onclick="Artikel.downloadArticleDownload(this, '.$article_id.');" title="'.($filename != '' ? $filename : 'Datei für Downloadartikel hochladen').'"></span>';
         $html .= '               <span class="xdelete pointer far fa-trash-alt" onclick="Artikel.downloadArticleDelete(this, '.$article_id.');" title="Datei für Downloadartikel löschen"></span>';
      }

      else {
         $html .= '               <span class="xdownload upload_button pointer" onclick="Artikel.downloadArticleUpload(this, '.$article_id.');" title="Datei für Downloadartikel hochladen"></span>';
         $html .= '               <span class="xdelete_no far fa-trash-alt"></span>';
      }

      $html .= '            </div>';

      $html .= '            <span class="xmenge">'.CR;
      $html .= '               <input type="text" class="art_menge txt_inp right"
                                         onchange="$(this).closest(\'.block_start\').attr(\'data-changed\', 1);
                                         $(this).val(point2komma(parseFloat(komma2point($(this).val())).toFixed('.($masse_check == 'y' ? $masse_komma : 0).'))); $(this).closest(\'.block_start\').attr(\'data-changed\', 1);'.($main ? ' $(\'#menge2\').val($(this).val());" id="menge1"' : '"').'
                                         value="'.number_format($menge, ($masse_check == 'y' ? (int)$masse_komma : 0), ',', '').'" />'.CR;
      $html .= '            </span>'.CR;
      $html .= '         </div>'.CR;

      if (defined('CONF_MODULE_MATRIX')) {
         $html .= '         <div class="xmatrix"><span class="txt_but '.($matrix == 'y' ? 'button_ci' : 'button button_border').'" onclick="Matrix.popup(this, '.$article_id.');">Matrix</span></div>';
      }

      $html .= '      </div>'.CR;
      $html .= '      <div class="clear"></div>'.CR;

      return $html;
   }

   // Detail EAN-Zeile
   // 21.06.2019
   private function _articleEan ($steuersatz, $haendler_netto, $gtin, $mpn, $ge_netto, $ge_netto_aktiv, $grundeinheit, $ge_menge, $gew_check, $matrix,  $is_parent) {
      $steuer = (float)$this->params->firma['tax'.$steuersatz];
      $html  = '         <div class="xleft">'.CR;
      // Bei 1. Variante gtin/mpn mit Google-Shopping synchronisieren
      if ($is_parent) { // Synchronisation mit Google-Shopping / Ebay ???
         $html .= '         <span class="xgtin">';
         $html .= '           <input type="text" class="art_gtin txt_inp" id="gtin_parent" value="'.$gtin.'" placeholder="EAN" onchange="Artikel.articleChange(this); $(\'#g_gtin\').val($(this).val());" />';
         $html .= '         </span>'.CR;
         $html .= '         <span class="xmpn">';
         $html .= '            <input type="text" class="art_mpn txt_inp"  id="mpn_parent" value="'.$mpn.'" placeholder="MPN Lieferant" onchange="Artikel.articleChange(this); $(\'#g_mpn\').val($(this).val());" />';
         $html .= '         </span>'.CR;
      }
      else {
         $html .= '         <span class="xgtin">';
         $html .= '            <input type="text"class="art_gtin txt_inp" value="'.$gtin.'" placeholder="EAN" onchange="Artikel.articleChange(this);" />';
         $html .= '         </span>'.CR;
         $html .= '         <span class="xmpn">';
         $html .= '            <input type="text"class="art_mpn txt_inp"  value="'.$mpn.'" placeholder="MPN-Teilenummer" onchange="Artikel.articleChange(this);" />';
         $html .= '         </span>'.CR;
      }

      $html .= '         </div>'.CR;
      $html .= '         <div class="xcenter">'.CR;

      // Grundpreis Edit (nur Hauptartikel)
      if ($is_parent) {
         // Popup Grundmenge anzeigen
         $html .= '            <div class="xgeedit master">';
         $html .= '               <span class="art_ge_edit ge_edit pointer fas fa-pencil-alt" onclick="Artikel.grundeinheitenPopup(this);"></span>';
      }

      else {
         $html .= '            <div class="xgeedit">';
      }

      // Grundpreis / Titel/Mouseover = Anzeige FE
      $ge_brutto     = ((float)$ge_netto * (1 + $steuer / 100)) ;
      $einheit_namen = $this->_geNameGrundeinheit($grundeinheit);

      $html .= '               <span class="xgeedittxt ge_edit_txt" title="entspricht: '.number_format($ge_brutto / $einheit_namen[2], 2, ',', '.').' / '.$this->text->get('ge', $grundeinheit).'">Grundpreis</span>'.CR;

      // Grundpreis anzeigen, wenn ge_netto_aktiv == 'y' -> im Popup
      $html .= '               <div class="ge_edit_hide"'.($ge_netto_aktiv == 'n' ? ' style="display:none;"' : '').'>'.CR;
      $html .= '                  <div class="ge_edit_name">'.$einheit_namen[0].':</div>'.CR;
      $html .= '                  <div class="ge_edit_menge">'.CR;
      // Menge - Aus historischen Gründen Kehrwert
      // Menge - Aus historischen Gründen Kehrwert
      $html .= '                     <input type="text" class="txt_inp ge_menge_show right" value="'.((float)$ge_menge > 0 ? str_replace('.', ',', (sprintf('%01.3f', 1 / $ge_menge))) : '').'" onchange="Artikel.checkGewicht();" />'.CR;
      $html .= '                     <input type="hidden" class="art_ge_menge" value="'.((float)$ge_menge > 0 ? $ge_menge : '').'"/>'.CR;
      $html .= '                     <input type="hidden" class="art_ge_netto" value="'.$ge_netto.'" />';
      $html .= '                  </div>'.CR;
      $html .= '                  <div class="ge_edit_einheit">'.$this->text->get('ge', $einheit_namen[1]).'</div>'.CR;
      $html .= '               </div>'.CR;
      $html .= '            </div>'.CR;



      // Einkaufspreis
      $html .= '            <div class="xhaendler_preis">'.CR;
      $html .= '               <span class="xhaendlernetto">';
      $html .= '                  <input type="text" class="art_haendler_netto txt_inp right" value="'.str_replace('.', ',', (sprintf('%01.2f',$haendler_netto))).'" onchange="Artikel.compute(this, \'haendler_netto\');"  />'.CR;
      $html .= '               </span>';
      $html .= '               <input type="hidden" class="art_haendler_netto_real" value="'.$haendler_netto.'" />'.CR;
      $html .= '               <div class="xhaendlertxt center">&lt; EK-Preis &gt;</div>'.CR;

      if ($this->params->firma['kleingewerbe'] == 'y' || $this->params->firma['tax_active'] == 'n') {
         $html .= '               <span class="xhaendlerbrutto art_haendler_brutto">keine MwSt</span>'.CR;
         $html .= '               <input type="hidden" class="haendler_brutto_real" value=" />'.CR;
         $html .= '               <input type="hidden" class="haendler_netto" value="" />'.CR;
      }

      else {
         $html .= '               <span class="xhaendlerbrutto">'.CR;
         $html .= '                  <input type="text" class="txt_inp art_haendler_brutto right" value="'.str_replace('.', ',', (sprintf('%01.2f',(float)$haendler_netto * (1 + $steuer / 100)))).'" onchange="Artikel.compute(this, \'haendler_brutto\');" />'.CR;
         $html .= '               </span>'.CR;
         $html .= '               <input type="hidden" class="art_haendler_brutto_real" value="'.((float)$haendler_netto * (1 + $steuer / 100)).'" />'.CR;
      }

      $html .= '            </div>'.CR;
      $html .= '         </div>'.CR;

      $html .= '         <div class="right"></div>'.CR;
      $html .= '         <div class="clear"></div>'.CR;

      return $html;
   }

   // Leeren Hauptartikel generieren
   // 30.05.2015
   private function _newMainArticle() {
      $this->db_extern->query("INSERT INTO #__articles_info SET
                                  sortierung = 1,
                                  childs = 1,
                                  steuersatz = 1,
                                  grundeinheit             = 'stk',
                                  grundeinheit_rechner     = 'stk'
                              ;");
      $parent_id = $this->db_extern->getNewId();
      $this->db_extern->query("INSERT INTO #__articles Set sort = 1, parent_id = $parent_id, menge = 1");

      exit(header('Location: '.ADMIN_URL_IDX.'/artikel/detail/'.$parent_id));
   }

   // Artikel mit Varianten speichern
   // Bei Fotoartikel werden Einstellungen in #__articles korrigiert
   // 21.06.2019
   private function articleSave() {
      $parent_id = $this->params->postInt('parent_id');
      $is_new    = ($parent_id == 0 ? true : false);
      $new_id    = 0;
      $sort      = 0;
      $gshop     = Control::getImportExport();
      $lang      = $this->params->selected_lang;

      // Neuen Artikel anlegen, wenn nicht vorhanden
      if ($is_new) {

         // Neuen Artikel anlegen - $parent_id = neuer Artikel
         $this->db_extern->query("INSERT INTO #__articles_info SET
                              steuersatz  = ".$this->params->postInt('steuersatz').",
                              childs      = 0");
         $new_id    = $this->db_extern->getNewId();
         $parent_id = $new_id;
      }

      // Kategorien
      $cat_id    = $this->params->postInt('category');
      $cats      = $this->params->postArray('categories');

      // Alle Einträge Artikel/Kategorien löschen und neue Eintrage speichern
      $this->db_extern->query("DELETE FROM #__article_to_cats WHERE parent_id = $parent_id");
      $this->db_extern->query("INSERT INTO #__article_to_cats SET parent_id = $parent_id, cat_id = $cat_id, sort = 0");

      // Zusätzlich Kategorien
      for ($c = 0; $c < count($cats); $c++) {
         if ((int)$cats[$c] > 0) {
            $sort++;
            $this->db_extern->query("INSERT INTO #__article_to_cats SET parent_id = $parent_id, cat_id = $cats[$c], sort = $sort");
         }
      }

      $name  = htmlspecialchars($this->db->escape($this->params->postString('name')), ENT_COMPAT);
      $desc  = $this->db->escape($this->params->postString('desc', '', 'none'));

      if ($this->params->postCheckbox('spalten2_check') == 'y') {
         $desc = $this->db->escape($this->params->postString('desc_l', '', 'none')).'[TRENNER]'.$this->db->escape($this->params->postString('desc_r', '', 'none'));
      }

      // Artikel SEO - $desc wird verwendet!
      $seo_auto  = $this->params->postCheckbox('seo_auto');
      $metatitle = '';
      $metadesc  = '';
      $metakey   = '';

      if ($seo_auto == 'y') {
         $metatitle = $name;
         $metadesc  = htmlspecialchars(preg_replace('/\s\s+/', ' ', str_replace(['[TRENNER]', '\n'], ' ', Helper::truncate(str_replace("\xc2\xa0", '', html_entity_decode(strip_tags($desc))), 160))), ENT_COMPAT);
         $metakey   = $metadesc;
      }

      else {
         $metatitle = $this->db->escape($this->params->postString('metatitle'));
         $metadesc  = $this->db->escape($this->params->postString('metadesc'));
         $metakey   = $this->db->escape($this->params->postString('metakey'));
      }

      // Einträge in shop_articles_seo
      $this->db_extern->query("INSERT INTO #__articles_seo SET parent_id = $parent_id, lang = '$lang',
                                  metaauto = '$seo_auto', metatitle = '$metatitle', metadesc = '$metadesc', metakey = '$metakey'
                               ON DUPLICATE KEY UPDATE
                                  metaauto = '$seo_auto', metatitle = '$metatitle', metadesc = '$metadesc', metakey = '$metakey'");

      // Update des Artikels (shop_articles -> gemeinsame Einstellungen)
      $sql  = "UPDATE #__articles_info SET
                 steuersatz               = ".$this->params->postInt('steuersatz').",
                 name_$lang               = '$name',
                 desc_$lang               = '$desc',
                 staffelung               = '".$this->_staffelungSort($this->params->postString('staffelung', '', 'none'))."',
                 grundeinheit             = '".$this->params->postString('grundeinheit')."',
                 spalten2_check           = '".$this->params->postCheckbox('spalten2_check')."',
                 versand_preis            = '".$this->params->postFloat('versandpreis')."',

                 grundeinheit_rechner     = '".$this->params->postString('grundeinheit_rechner')."',
                 masse_check              = '".$this->params->postCheckbox('masse_check')."',
                 masse_min                = '".$this->params->postFloat('masse_min')."',
                 masse_komma              = '".$this->params->postInt('masse_komma')."',
                 rechner_check            = '".$this->params->postCheckbox('rechner_check')."',
                 rechner_mode             = '".$this->params->postString('rechner_mode')."',

                 widerruf                 = '".$this->params->postInt('widerruf')."',
                 lieferfrist              = '".$this->params->postString('lieferfrist')."',
                 gewicht                  = '".$this->params->postFloat('gewicht')."',
                 gew_check                = '".$this->params->postCheckbox('gew_check')."',
                 show_object              = '".$this->params->postCheckbox('show_object')."',
                 fsk_check                = '".$this->params->postCheckbox('fsk_check')."',
                 neu_check                = '".$this->params->postCheckbox('neu_check')."',
                 ab_check                 = '".$this->params->postCheckbox('ab_check')."',
                 motiv_uploadp_check      = '".$this->params->postCheckbox('motiv_uploadp_check')."',
                 motiv_uploadt_check      = '".$this->params->postCheckbox('motiv_uploadt_check')."',
                 artikelgruppe            = '".$this->params->postInt('artikelgruppe')."',
                 marke                    = '".$this->params->postString('marke')."',
                 marke_aktiv              = '".$this->params->postCheckbox('marke_aktiv')."',
                 vpe                      = '".$this->params->postString('vpe')."',
                 vpm                      = '".$this->params->postString('vpm')."',
                 configurator_check       = '".$this->params->postCheckbox('configurator_check')."',
                 configurator_artnr_check = '".$this->params->postCheckbox('configurator_artnr_check')."',
                 config_einheit_check     = '".$this->params->postCheckbox('config_einheit_check')."',
                 config_menge_check       = '".$this->params->postCheckbox('config_menge_check')."',
                 configurator             = '".$this->params->postString('configurator_val')."',
                 mixer_artikel_check      = '".$this->params->postCheckbox('mixer_artikel_check')."',
                 naehrwerte_check         = '".$this->params->postCheckbox('naehrwerte_check')."',
                 spedition                = '".$this->params->postInt('spedition')."',
                 versandfrei_check        = '".$this->params->postCheckbox('versandfrei_check')."',
                 artikelgrafik1_check     = '".$this->params->postCheckbox('artikelgrafik1_check')."',
                 artikelgrafik2_check     = '".$this->params->postCheckbox('artikelgrafik2_check')."',
                 artikelgrafik3_check     = '".$this->params->postCheckbox('artikelgrafik3_check')."',
                 artikelgrafik4_check     = '".$this->params->postCheckbox('artikelgrafik4_check')."',
                 artikelgrafik5_check     = '".$this->params->postCheckbox('artikelgrafik5_check')."',
                 artikelgrafik6_check     = '".$this->params->postCheckbox('artikelgrafik6_check')."',
                 artikelgrafik6_check     = '".$this->params->postCheckbox('artikelgrafik6_check')."',
                 energy_efficiency        = '".$this->params->postString('energy_efficiency')."'
              WHERE id = $parent_id";

      // energy_efficiency_image  = '".$this->params->postString('energy_efficiency_image')."'

      if ($this->db_extern->query($sql) !== false) {
         $gshop->saveList($parent_id);
      }

      // Fotoartikel
      if (defined('CONF_FOTOGRAF') && $this->params->postString('is_foto') == 'y') {
         // Menge korrigieren, da keine Unterkategorien gespeichert werden.
         $menge   = $this->params->postFloat('menge');
         $netto   = $this->params->postArray('netto_foto');
         $netto_0 = (float)$netto[0];
         $netto_1 = (float)$netto[1];
         $netto_2 = (float)$netto[2];
         $netto_3 = (float)$netto[3];
         $netto_4 = (float)$netto[4];
         $netto_5 = (float)$netto[5];
         $netto_6 = (float)$netto[6];

         $foto_set   = $this->params->postInt('foto_set');
         $org_set    = $this->params->postInt('org_set');
         $preis_mode = $this->params->postInt('preis_mode');
         $old_mode   = $this->params->postInt('old_mode');
         $article_nr = $this->params->postString('art_nr');

         $this->db_extern->query("UPDATE #__articles SET art_nr = '$article_nr', menge = '".$menge."', netto = '$netto_0' WHERE parent_id = $parent_id AND sort = 1");

         // Fotoartikel werden an anderer Stelle angelegt, nur Änderungen
         $foto = Control::getModuleFoto();

         // Preis-Mode geändert
         // Preis-Sets korrigieren und Artikel / netto korrigieren für Artikel-Listen (FE und BE)
         if ($preis_mode != $old_mode) {
            // Individuelle Preise -> Global oder Set: löschen
            if ($old_mode == 3) {
               $foto->delSet($foto_set);
               $this->db_extern->query("UPDATE #__articles_info SET foto_set = org_set WHERE id = $parent_id");
            }

            // Set -> Globla: Alle Artikel im Set auf Preis Default-Set
            else if ($preis_mode == 1) {
               $foto->delSet($org_set);
               $set = $foto->updateSet($org_set, 1, $netto_0, $netto_1, $netto_2, $netto_3, $netto_4, $netto_5, $netto_6);
               $this->db_extern->query("UPDATE #__articles_info SET foto_set = org_set WHERE id = $parent_id");
            }

            // Global -> Set: Set-Preise speichern
            else if ($preis_mode == 2) {
               // Preise Set auf Set-Preis
               $set = $foto->updateSet($org_set, $org_set, $netto_0, $netto_1, $netto_2, $netto_3, $netto_4, $netto_5, $netto_6);
               $this->db_extern->query("UPDATE #__articles_info SET foto_set = org_set WHERE id = $parent_id");
            }

            // individueller Preis
            else if ($preis_mode == 3) {
               $set = $foto->newSet(0, $netto_0, $netto_1, $netto_2, $netto_3, $netto_4, $netto_5, $netto_6);
               $this->db_extern->query("UPDATE #__articles_info SET foto_set = $set WHERE id = $parent_id");
            }
         }

         // Mode2 nicht geändert, Preise speichern und Preis Set aktualisieren
         else {
            if ($preis_mode == 2){
               $foto->updateSet($foto_set, $org_set, $netto_0, $netto_1, $netto_2, $netto_3, $netto_4, $netto_5, $netto_6);
               // Bei Änderungen SET alle Preise anpassen
               $this->db_extern->query("UPDATE #__articles AS a LEFT JOIN #__articles_info AS i ON a.parent_id = i.id SET netto = '$netto_0' WHERE i.foto_set = $foto_set");
            }

            // Mode3 nicht geändert, nur Preise speichern
            else if ($preis_mode == 3){
               $foto->newSet($foto_set, $netto_0, $netto_1, $netto_2, $netto_3, $netto_4, $netto_5, $netto_6);
            }
         }
      }

      // Varianten speichern, wenn nicht Fotoartikel
      else {
         $varianten = $this->params->postArray('varianten');

         if (is_array($varianten) && count($varianten) > 0) {
//            $varianten = $this->params->postArray('varianten');
            $merkmal1  = $this->params->postInt('merkmal1');
            $merkmal2  = $this->params->postInt('merkmal2');
            $ge        = $this->db_extern->querySingleobject("SELECT ge_netto_aktiv, grundeinheit FROM #__articles_info WHERE id = $parent_id");

            for ($i = 0; $i < count($varianten); $i++) {
               $v = json_decode($varianten[$i]);

               $article_id     = (int)$v->article_id;
               // $parent_id
               // sort
               $online         = ($v->online == 'on' ? 'y' : 'n');
               $art_nr         = $v->artnr;
               $netto          = $v->netto;
               $ge_netto       = (float)$v->ge_netto;
               $haendler_netto = $v->haendler_netto;
               $angebot        = $v->angebot;
               $angebot_active = $v->angebot_active;
               $menge          = Helper::checkFloat($v->menge);
               $ge_menge       = $v->ge_menge;
               // merkmal1
               $wert1          = $v->wert1;
               // merkmal2
               $wert2          = $v->wert2;
               // gewicht
               // filename
               // filetyp
               $gtin           = $v->gtin;
               $mpn            = $v->mpn;
               // imported
               $startbild      = $v->startbild;
               // matrix

               $db_netto       = 0;

               // Nur wenn Grundpreise aktiv
               if ($ge->ge_netto_aktiv == 'y') {
                  // Achtung Kehrwert !
                  $db_netto     = (float)($angebot_active == 'n' ? $netto : $angebot);
                  $grundeinheit = $ge->grundeinheit;

                  // Neuer Preis
                  $ber_netto = (float)($angebot_active == 'n' ? $netto : $angebot);
                  $ge_netto  = $ber_netto * (float)$ge_menge;

                  // Auf Grundeinheit korrigieren
                  switch ($grundeinheit) {
                     case '10g'  :
                     case '10ml' :
                     case '10cm' : $ge_netto *= 10; break;
                     case '100g' :
                     case '100ml':
                     case '100cm': $ge_netto *= 100; break;
                     case 'cm2'  : $ge_netto *= 100; break;
                     case 'dm2'  : $ge_netto *= 10000; break;
                     case 'cm3'  : $ge_netto *= 1000; break;
                     case 'dm3'  : $ge_netto *= 1000000; break;
                  }
               }

               if ($art_nr == '') {
                  $art_nr = date('y').$parent_id;
               }

               // INSERT bei neuer Variante
               if ($article_id == 0) {
                  $sql = "INSERT INTO #__articles ";
               }

               // sonst UPDATE
               else {
                  $sql = "UPDATE #__articles ";
               }

               $sql .= "SET
                  art_nr         = '$art_nr',
                  online         = '$online',
                  netto          = '$netto',
                  angebot        = '$angebot',
                  angebot_active = '$angebot_active',
                  menge          = '$menge',
                  merkmal1       = $merkmal1,
                  wert1          = $wert1,
                  merkmal2       = $merkmal2,
                  wert2          = $wert2,
                  haendler_netto = '$haendler_netto',
                  gtin           = '$gtin',
                  mpn            = '$mpn',
                  startbild      = $startbild,
                  ge_netto       = '$ge_netto',
                  ge_menge       = '$ge_menge'";


               if ($article_id == 0) {
                  $sql .= ", parent_id = $parent_id";
                  $sql .= ", sort = (SELECT childs + 1 FROM #__articles_info WHERE id = $parent_id) ";
               }

               else {
                  $sql .= " WHERE id = $article_id";
               }

               $this->db_extern->query($sql);

               // Anzahl childs bei neuem Artikel korrigieren
               if ($article_id == 0) {
                  $this->db_extern->query("UPDATE #__articles_info SET childs = childs +1 WHERE id = $parent_id");
               }
            }
         }

         else if ($new_id > 0) {
            $this->db_extern->query("INSERT INTO #__articles SET
                  art_nr         = '',
                  online         = 'y',
                  netto          = '0',
                  angebot        = '0',
                  angebot_active = 'n',
                  menge          = '1',
                  merkmal1       = 0,
                  wert1          = 0,
                  merkmal2       = 0,
                  wert2          = 0,
                  haendler_netto = '0',
                  gtin           = '',
                  mpn            = '',
                  startbild      = 1,
                  ge_netto       = '0',
                  ge_menge       = '0',
                  parent_id      = $parent_id,
                  sort           = 1");

            $this->db_extern->query("UPDATE #__articles_info SET childs = 1 WHERE id = $parent_id");
         }
      }

      $isnaehrwert = $this->params->postInt('isnaehrwert');

      if ($isnaehrwert > 0) {
         $this->_saveNaehrwerte($parent_id);
         $this->_saveZutaten($parent_id);
      }

      return [true, $new_id];
   }

   // Details - Variante löschen
   // 21.06.2019
   private function deleteVariante() {
      $article_id = $this->params->postInt('article_id');
      $parent     = $this->db_extern->querySingleObject("SELECT parent_id, sort FROM #__articles WHERE id = $article_id");

      if ($parent) {
         $parent_id  = (int)$parent->parent_id;
         $sort       = (int)$parent->sort;

         $this->db_extern->query("DELETE FROM #__articles WHERE id = $article_id");
         $this->db_extern->query("UPDATE #__articles SET sort = sort -1 WHERE sort > $sort AND parent_id = $parent_id ORDER BY sort");
         $this->db_extern->query("UPDATE #__articles_info SET childs = childs -1 WHERE id = $parent_id");

         exit(json_encode(['status' => 'ok']));
      }

      exit(json_encode(['status' => 'error', 'msg' => 'Artikel konnte nicht gelöscht werden']));
   }

   // Details / Select-Box für Merkmale generieren
   // 13.06.2019
   private function _merkmaleOptionsListe($merkmal_id, $pos) {
      // wird mehrmals benötigt, nur 1 DB-Abfrage
      static $opt_list = [];
      $html           = '';

      if (!$opt_list) {
         $opt_list = $this->db_extern->queryAllObjects("SELECT id, merkmal_".$this->params->default_lang." AS merkmal FROM #__merkmale WHERE id > 0");
      }

      $html .= '<span class="selectbox30">'.CR;
      $html .= '   <select class="merkmal'.$pos.'" id="merkmal'.$pos.'" name="merkmal_'.$pos.'" onchange="Artikel.merkmalChange('.$pos.');">'.CR;
      $html .= '      <option value="0"'.($merkmal_id == 0 ? ' selected="selected"' : '').'>keine</option>'.CR;

      if (!empty($opt_list)) {
         foreach ($opt_list as $option) {
            $html .= '      <option value="'.$option->id.'"'.($merkmal_id == $option->id ? ' selected="selected"' : '').'>'.$option->merkmal.'</option>'.CR;
         }
      }

      $html .= '   </select>'.CR;
      $html .= '</span>'.CR;

      return $html;
   }

   // Edit / Select-Box für Merkmale generieren
   // 21.06.2019
   private function _merkmalVal($merkmal_id) {
      $merkmal = 'keine';

      $data = $this->db_extern->querySingleValue("SELECT merkmal_".$this->params->default_lang." AS merkmal FROM #__merkmale WHERE id = $merkmal_id");

      if ($data) {
         $merkmal = $data;
      }

//      $html = '<span class="xartmerkmal" name="merkmal">'.$merkmal.'</span>'.CR;
//      return $html;
      return $merkmal;
   }

   // Merkmalliste Popup
   // 14.06.2019
   private function merkmalePopup() {
      $html   = '';
      $sql    = '';
      $i      = 0;
      $anzahl = 0;

      foreach ($this->params->langs as $lang) {
         $sql .= "merkmal_$lang, ";
         $anzahl++;
      }

      $data = $this->db_extern->queryAllObjects("SELECT $sql id FROM #__merkmale WHERE id > 0 ORDER BY id");


      $html .= '<div id="merkmale_popup" style="min-width:'.max(350, ($anzahl * 125)).'px">'.CR;
      $html .= '   <h1 class="txt_tit">'.CR;
      $html .= '      <a class="help_kanpaiclassic" style="margin-right:0;" href="'.HELP_LINK.'/o60/artikelvarianten-merkmale-werte/" target="_blank" alt=""></a>&nbsp'.CR;
      $html .= '      Artikelmerkmale&nbsp;<span class="fliesstext">(für&nbsp;gesamten&nbsp;Shop)</span>'.CR;
      $html .= '   </h1>'.CR;

      $html .= '   <div class="line line_title">'.CR;

      foreach ($this->params->langs as $langs) {
         $html .= '      <span>'.strtoupper($langs).'</span>'.CR;
         $i++;
      }

      $html .= '      <div class="clear"></div>'.CR;
      $html .= '   </div>'.CR;

      $html .= '   <div id="merkmale_block">'.CR;

      if ($data) {
         foreach ($data as $d) {
            $html .= '      <div class="line" data-merkmal_id="'.$d->id.'" data-changed="0">'.CR;

            foreach ($this->params->langs as $langs) {
               $text = 'merkmal_'.$langs;
               $html .= '         <input class="txt_inp" type="text" id="xmerkmal_'.$langs.'_'.$d->id.'" value="'.$d->$text.'" onchange="$(this).closest(\'.line\').attr(\'data-changed\', 1);" />'.CR;
            }

            $html .= '         <div class="clear"></div>'.CR;
            $html .= '      </div>'.CR;
         }

         $html .= '   </div>'.CR;
      }

      $html .= '   <div class="button_new button_ci txt_but" onclick="Artikel.merkmalNew();">neu</div>'.CR;

      // Vorlage für neue Merkmal
      $html .= '   <div id="neuezeile" class="line" data-merkamal_id="0" data-changed="1" style="display:none;">'.CR;

      foreach ($this->params->langs as $lang) {
         $html .= '      <input class="txt_inp" type="text" id="xmerkmal_'.$lang.'_0" value="" />'.CR;
      }

      $html .= '   </div>'.CR;

      $html .= '   <div class="buttonzeile">'.CR;
      $html .= '      <div class="button button_left txt_but" onclick="Multibox.close()">abbrechen</div>'.CR;
      $html .= '      <div class="button_ci button_righttxt_but" onclick="Artikel.merkmaleSave()">speichern</div>'.CR;
      $html .= '   </div>'.CR;
      $html .= '</div>'.CR;

      return $html;
   }

   // Änderungen Merkmalliste speichern
   // 14.09.2019
   private function merkmaleSave() {
      $merkmale = $this->params->postString('merkmale');
      $data     = json_decode($merkmale);
      $update   = [];

      for ($i = 0; $i < (is_array($data) ? count($data) : 0); $i++) {
         $m          = $data[$i];
         $merkmal_id = (int)$m->merkmal_id;
         $text       = '';
         $check      = '';

         for($l = 0; $l < count($this->params->langs); $l++) {
            $text  .= " merkmal_".$this->params->langs[$l]." = '".$m->vals[$l]."',";
            $check .= $m->vals[$l];
         }

         $texte = rtrim($text, ',');

         if ($check == '') {
            // Neues Merkmal
            if ($merkmal_id == 0) {
               $update[] = 0;
            }

            else {
               $this->db_extern->query("DELETE FROM #__merkmale WHERE id = $merkmal_id");
               $this->db_extern->query("UPDATE #__articles SET merkmal1 = 0, wert1 = 0 WHERE merkmal1 = $merkmal_id");
               $this->db_extern->query("UPDATE #__articles SET merkmal2 = 0, wert2 = 0 WHERE merkmal2 = $merkmal_id");
            }
         }

         else {
            // Neuer Eintrag einfügen
            if ($merkmal_id == 0) {
               $this->db_extern->query("INSERT INTO #__merkmale SET $texte");
               $update[] = $this->db_extern->getNewId();
            }

            else {
               $this->db_extern->query("UPDATE #__merkmale SET $texte WHERE id = $merkmal_id");
            }
         }
      }

      $this->last_sql = $this->db_extern->last_sql;

      return $update;
   }

   // Details / Select-Boxen für Werte generieren
   // 21.06.2019
   private function _werteOptionsListe($wert_id, $merkmal_id, $pos) {
      // wird mehrmals benötigt, nur 1 DB-Abfrage
      $html     = '';
      $opt_list = $this->db_extern->queryAllObjects("SELECT id, merkmal_id, wert_".$this->params->default_lang." AS wert FROM #__werte WHERE merkmal_id = $merkmal_id ORDER BY wert");

//      if (!empty($list_id)) {
      $html .= '<span class="selectbox30">'.CR;
      $html .= '   <select class="art_wert'.$pos.'" name="wert_'.$pos.'" onchange="Artikel.articleChange(this);">'.CR;

      if ($wert_id == 0 || $wert_id == '') {
         $html .= '      <option value="0" selected="selected">keine</option>'.CR;
      }

      else {
         $html .= '      <option value="0">keine</option>'.CR;
      }

      if (!empty($opt_list)) {
         foreach ($opt_list as $option) {
            $html .= '      <option value="'.$option->id.'" '.((int)$wert_id == (int)$option->id ? ' selected="selected"' : '').'>'.$option->wert.'</option>'.CR;
         }
      }

      $html .= '   </select>'.CR;
      $html .= '</span>'.CR;

      return $html;
   }

   // Wertelist zum Editieren ausgeben
   // 24.06.2019
   private function wertePopup() {
      $html            = '';
      $merkmal_options = '';
//      $optionlist      = '';

      // Abgebrochene Einträge löschen
      // $this->db_extern->query("DELETE FROM #__werte WHERE merkmal_id = 0");

//      $merkmale = $this->db_extern->queryAllObjects("SELECT id, merkmal_" . $this->params->default_lang ." AS merkmal FROM #__merkmale WHERE id > 0");
      $merkmale    = $this->db_extern->queryAllObjects("SELECT id, merkmal_" . $this->params->default_lang ." AS merkmal FROM #__merkmale");
      $werte_langs = '';
      $anzahl = 0;

      foreach ($this->params->langs as $lang) {
         $werte_langs .= "wert_$lang, ";
         $anzahl++;
      }

      // Options für Merkmale erstellen
      for ($i = 0; $i < count($merkmale); $i++) {
         // #data[i]->id# für spatere Ersetzung selected="selected"
         if ((int)$merkmale[$i]->id == 0) {
            $merkmal_options .= "<option value='".$merkmale[$i]->id."'#".$merkmale[$i]->id."#>---</option>";
         }

         else {
            $merkmal_options .= "<option value='".$merkmale[$i]->id."'#".$merkmale[$i]->id."#>".$merkmale[$i]->merkmal."</option>";
         }
      }

      // Box und Titel ausgeben
      $min_width = max(400, (165 + $anzahl * 125));
      $html .= '<div id="werte_popup" style="min-width:'.$min_width.'px;">'.CR;
      $html .= '   <h1 class="txt_tit">'.CR;
      $html .= '      <a class="help_kanpaiclassic pointer" href="'.HELP_LINK.'/o60/artikelvarianten-merkmale-werte/" target="_blank" alt=""></a>'.CR;
      $html .= '      Zuordnung der Werte <span class="fliesstex">(für gesamten Shop)</span>'.CR;
      $html .= '   </h1>';

      $html .= '   <div class="line line_title">'.CR;
      $html .= '      <span class="txt_bez">Merkmale</span>'.CR;
      $html .= '      <span class="txt_bez">Werte</span>'.CR;
      $html .= '   </div>';

      // Zeile mit Sprachen
      $html .= '   <div class="line line_title2">'.CR;

      // Zeile Sprachen
      foreach ($this->params->langs as $langs) {
         $html .= '      <span>'.strtoupper($langs).'</span>'.CR;
      }

      if (defined('CONF_MODULE_MW')) {
         $html .= '      <span class="symbole_img">'.CR;
      }

      $html .= '      <div class="clear"></div>'.CR;
      $html .= '   </div>'.CR;

      $html .= '   <div id="werte_block">'.CR;

      // Merkmale lesen
      $werte = $this->db_extern->queryAllObjects("SELECT id, $werte_langs merkmal_id, wert_img FROM #__werte WHERE id > 0 ORDER BY merkmal_id, wert_".$this->params->default_lang);

      // Und ausgeben
      if ($werte) {
         for ($i = 0; $i < count($werte); $i++) {
            // #data[i]->id# ersetzen
            $optionlist = $merkmal_options;

            if ((int)$werte[$i]->merkmal_id == 0) {
               $optionlist = '<option value="0" selected="selected">---</option>'.$optionlist;
            }
            $optionlist = str_replace('#'.$werte[$i]->merkmal_id.'#', ' selected="selected"', $optionlist);
            $optionlist = preg_replace('/(#.*?#)/', '', $optionlist);

            $html .= '      <div class="line" data-wert_id="'.$werte[$i]->id.'" data-changed="0">'.CR;
            $html .= '         <span class="selectbox30">'.CR;
            $html .= '            <select class="txt_inp onchange="$(this).closest(\'line\').attr(\'data-change\', 1)">'.$optionlist.'</select>'.CR;
            $html .= '         </span>'.CR;
            $html .= '         <span class="pfeil">&lt</span>'.CR;

            // Input Sprachen ausgeben
            foreach ($this->params->langs as $langs) {
               $text = $werte[$i]->{'wert_'.$langs};
               $html .= '         <span><input type="text" class="txt_inp" value="'.$text.'" onchange="$(this).closest(\'line\').attr(\'data-change\', 1)" /></span>'.CR;
            }

            // Erweiterung für Module merkmale_werte
            if (defined('CONF_MODULE_MW')) {
               $html .= '         <span class="symbole_img">'.CR;
               $html .= '            <span class="werte_img">'.CR;

               if (is_file(TEMPLATE_PATH.'/images/grafische_werte/'.$werte[$i]->wert_img)) {
                  $html .= '               <img src="'.TEMPLATE_URL.'/images/grafische_werte/'.$werte[$i]->wert_img.'" alt="" style="cursor:url('.TEMPLATE_URL.'/images/grafische_werte/'.$werte[$i]->wert_img.'), pointer" />'.CR;
               }

               else {
                  $html .= '               <img src="'.ADMIN_URL.'/img/nopic.png" alt="" />'.CR;
               }

               $html .= '            </span>'.CR;

               $html .= '            <span class="upload upload_button pointer" onclick="Artikel.wertImageUpload(this)"></span>'.CR;
               $html .= '            <span class="delete pointer far fa-trash-alt" onclick="Artikel.wertImageDelete(this);"></span>'.CR;
               $html .= '         </span>'.CR;
            }

            $html .= '         <div class="clear"></div>'.CR;
            $html .= '      </div>'.CR;
         }
      }

      $html .= '   </div>'.CR;
      $html .= '   <div id="werte_neu" class="button_new button_ci txt_but" onclick="Artikel.wertNew();">neu</div>'.CR;
      $html .= '   <p style="text-align:center;" class="txt_bez">Vor dem Speichern bitte Ihren Artikel speichern.</p>'.CR;

      // Neuer Eintrag
      $optionlist = preg_replace('/(#.*?#)/', '', $merkmal_options);
      $optionlist = '<option value="0" selected="selected">---</option>'.$optionlist.CR;

      // Vorlage für neuen Wert
      $html .= '   <div id="neuezeile" class="line" data-wert_id="0" data-changed="0" style="display:none;">'.CR;
      $html .= '      <span class="selectbox30"><select class="txt_inp xmerkmalid" onchange="Artikel.listChange($(this).parents(\'div\').attr(\'id\').replace(\'wert_\', \'\'));">'.$optionlist.'</select></span>'.CR;
      $html .= '      <span class="pfeil">&lt</span>'.CR;

      foreach ($this->params->langs as $lang) {
         $html .= '      <input type="text" class="txt_inp value="" onchange="$(this).closest(\'line\').attr(\'data-change\', 1)" />'.CR;
      }

      // Erweiterung für Module merkmale_werte
      if (defined('CONF_MODULE_MW')) {
         $html .= '      <span class="symbole_img">'.CR;
         $html .= '         <span class="werte_img"><img src="'.ADMIN_URL.'/img/nopic.png" alt="" /></span>'.CR;

         $html .= '         <span class="upload upload_button pointer" onclick="Artikel.wertImageUpload(this);"></span>'.CR;
         $html .= '         <span class="delete pointer far fa-trash-alt" onclick="Artikel.wertImageDelete(this);"></span>'.CR;
         $html .= '      </span>'.CR;
      }

      $html .= '   </div>'.CR;

      $html .= '   <div class="buttonzeile">'.CR;
      $html .= '      <div class="button button_left txt_but" onclick="Multibox.close()">abbrechen</div>'.CR;
      $html .= '      <div class="button_ci button:right txt_but" onclick="Artikel.werteSave()">speichern</div>'.CR;
      $html .= '   </div>'.CR;
      $html .= '</div>'.CR;

      exit(json_encode(['status' => 'ok', 'html' => $html]));
   }

   // Werteliste Änderungen speichern
   // 24.06.2019
   private function werteSave() {
      $werte       = json_decode($this->params->postString('werte'));
      $sql         = '';
      $merkmal1_id = $this->params->postInt('merkmal1_id');
      $merkmal2_id = $this->params->postInt('merkmal2_id');

      if (is_array($werte) && count($werte) > 0) {
         foreach ($werte as $w) {
            $merkmal_id = $w->merkmal_id;
            $wert_id    = $w->wert_id;
            $lang_sql   = '';
            $del        = '';

            for ($i = 0; $i < count($this->params->langs); $i++) {
               $lang_sql .= " ,wert_".$this->params->langs[$i]." = '".$w->vals[$i]."'";
               $del .= $w->vals[$i];
            }

            if ($del == '') {
//               $this->db_extern->query("DELETE FROM #__werte WHERE id = $wert_id");
// TODO: Bild löschen
            }

            else {
               // Neuer Wert
               if ($wert_id == 0) {
                  $this->db_extern->query("INSERT INTO #__werte SET merkmal_id = '$merkmal_id' $lang_sql");
               }

               // Wert ändern
               else {
                  $this->db_extern->query("UPDATE #__werte SET merkmal_id = $merkmal_id $lang_sql WHERE id = $wert_id");
               }
            }
         }
      }

      $werte1 = $this->_werteOptionsListe(0, $merkmal1_id, 1);
      $werte2 = $this->_werteOptionsListe(0, $merkmal2_id, 2);

      exit (json_encode(['status' => 'ok', 'werte1' => $werte1, 'werte2' => $werte2]));
/*
      if ($merkmal == 0) {
         return true;
      }

      if ($wert == 0) {
         $sql = "INSERT INTO #__werte SET merkmal_id = $merkmal, ";
      }
      else {
         $sql = "UPDATE #__werte SET merkmal_id = $merkmal, ";
      }

      $first = true;
      foreach ($this->params->langs as $lang) {
         if ($first) {
            // Wenn Wert = LöSCHEN
            if ($this->params->postString("wert_" . $lang, '', 'none') == 'LöSCHEN') {
               $this->db_extern->query("DELETE FROM #__werte WHERE id = $wert");
               return true;
            }
            $first = false;
         }
         else {
            $sql .= " , ";
         }
         $sql .= "wert_$lang = '" . $this->params->postString("wert_" . $lang, '', 'none')."' ";
      }

      if ($wert != 0) {
         $sql .= " WHERE id = $wert";
      }

      if ($this->db_extern->query($sql)) {
         return true;
      }
      return false;
*/
   }

   // Werteliste Bild löschen
   // 24.06.2019
   private function wertImageDelete() {
      $wert_id = $this->params->postInt('wert_id');
      $img     = $this->db_extern->querySingleValue("SELECT wert_img FROM #__werte WHERE id = $wert_id");

      if ($img != '') {
         $images = (int)$this->db_extern->querySingleValue("SELECT count(*) FROM #__werte WHERE wert_img = '$img'");
         $this->db_extern->query("UPDATE #__werte SET wert_img = '' WHERE id = $wert_id");

         // Bild löschen, wenn nur bei diesem Wert vorhanden
         if ($images == 1) {
            unlink(TEMPLATE_PATH.'/images/grafische_werte/'.$img);
//            exit(json_encode(['status' => 'ok']));
         }
      }

      exit(json_encode(['status' => 'ok']));
   }

   // Artikel kopieren mit Varianten
   // 08.07.2019
   private function articleCopy() {
      // Kopie als neue Tabelle erstellen
      $parent_id = $this->params->postInt('parent_id');
      $rand      = rand();

      // article_info ($parent_id) in temporäre Tabelle kopieren
      $this->db_extern->query("CREATE TABLE tmp_$rand LIKE #__articles_info");
      $this->db_extern->query("INSERT INTO tmp_$rand SELECT * FROM #__articles_info WHERE id = $parent_id");

      // auto_increment entfernen und ID auf NULL
      $this->db_extern->query("ALTER TABLE tmp_$rand CHANGE id id INT");
      $this->db_extern->query("UPDATE tmp_$rand SET id = NULL");

      // Clickzähler auf 0
      $this->db_extern->query("UPDATE tmp_$rand SET clicks = 0");

      // Und als neuen Artikel wieder einfügen
      $this->db_extern->query("INSERT INTO #__articles_info SELECT * FROM tmp_$rand");

      // ID (parent) merken
      $new_id = $this->db_extern->getNewId();

      // Temporäre Tabellen löschen
      $this->db_extern->query("DROP TABLE IF EXISTS tmp_$rand");

      if ($new_id) {
         // Kopie aller (Sub-)Artikel als neue Tabelle
         $this->db_extern->query("CREATE TABLE tmpx_$rand LIKE #__articles");
         $this->db_extern->query("INSERT INTO tmpx_$rand SELECT * FROM #__articles WHERE parent_id = $parent_id");

         // auto_increment und index entfernen und ID auf NULL
         $this->db_extern->query("ALTER TABLE tmpx_$rand CHANGE id id INT");
         $this->db->query("ALTER TABLE tmpx_$rand DROP PRIMARY KEY");
         // und Einträge korrigieren
         $this->db_extern->query("UPDATE tmpx_$rand SET id = NULL, art_nr = CONCAT(art_nr, '-new'), parent_id = $new_id");
         // temp-Tabelle in Artikel einfügen
         $this->db_extern->query("INSERT INTO #__articles SELECT * FROM tmpx_$rand;");
         $this->db_extern->query("DROP TABLE IF EXISTS tmpx_$rand");

         // Verknüpfungen mit Kategorien kopieren
         $cats = $this->db_extern->queryAllObjects("SELECT * FROM #__article_to_cats WHERE parent_id = $parent_id");

         // Verknüpfung Kategorien -> Neuer Artikel anlegen
         for ($c = 0; $c < count($cats); $c++) {
            $this->db_extern->query("INSERT INTO #__article_to_cats SET parent_id = $new_id, cat_id = ".$cats[$c]->cat_id.", sort = ".$cats[$c]->sort);
         }

         // Bilder kopieren und Namen korrigieren
         $pfad = SHOP_PATH.'/'.CONF_PICT_PATH;

         // Bild des zu kopierenden Artikels
         $start_image = $this->db_extern->querySingleValue("SELECT image FROM #__articles_info WHERE id = $parent_id");

         // Wenn Bild auf Server
         if (strpos($start_image, 'http') === false && $start_image != 'nopic.png' && $start_image != '') {
            // Originalbild
            if (file_exists($pfad.'original/'.$start_image.'.jpg')) {
               copy($pfad.'original/'.$start_image.'.jpg', $pfad.'original/'.$new_id.'_01.jpg');
               $this->db_extern->query("UPDATE #__articles_info SET image = '".$new_id.'_01'."' WHERE id = $new_id");
            }

            $image = $this->db_extern->querySingleValue("SELECT image FROM #__articles_info WHERE id = $new_id");

            if (file_exists($pfad.$start_image.'.jpg')) {
               copy($pfad.$start_image.'.jpg', $pfad.$new_id.'_01.jpg');
            }

            // Detail-Thumbs kopieren
            if (file_exists($pfad.$start_image.'_td.jpg')) {
               copy($pfad.$start_image.'_td.jpg', $pfad.$new_id.'_01_td.jpg');
            }

            if (file_exists($pfad.$start_image.'_tn.jpg')) {
               copy($pfad.$start_image.'_tn.jpg', $pfad.$new_id.'_01_tn.jpg');
            }

            if (file_exists($pfad.$start_image.'_tp.jpg')) {
               copy($pfad.$start_image.'_tp.jpg', $pfad.$new_id.'_01_tp.jpg');
            }
         }

         // Zusätzliche Bilder
         $images = $this->db_extern->queryAllObjects("SELECT image, sort FROM #__articles_images WHERE parent_id = $parent_id ORDER BY sort");

         if ($images) {
            for ($i = 0; $i < count($images); $i++) {
               $img       = $images[$i];
               $image     = $img->image;
               $image_new = $new_id.'_'.sprintf('%02d', $i + 2);

               // Bild lokal gespeichert -> kopieren
               if (strpos($image, 'http') === false && $image != 'nopic.png' && $image != '') {
                  $this->db_extern->query("INSERT INTO #__articles_images SET parent_id = $new_id, sort = $img->sort, image = '$image_new'");

                  // Original kopieren
                  if (file_exists($pfad.'original/'.$image.'.jpg')) {
                     copy($pfad.'original/'.$image.'.jpg', $pfad.'original/'.$image_new.'.jpg');
                  }

                  // Bild kopieren
                  if (file_exists($pfad.$image.'.jpg')) {
                     copy($pfad.$image.'.jpg', $pfad.$image_new.'.jpg');
                  }

                  // Thumbnail Details kopieren
                  if (file_exists($pfad.$image.'_td.jpg')) {
                     copy($pfad.$image.'_td.jpg', $pfad.$image_new.'_td.jpg');
                  }

                  // Thumbnail kopieren
                  if (file_exists($pfad.$image.'_tn.jpg')) {
                     copy($pfad.$image.'_tn.jpg', $pfad.$image_new.'_td.jpg');
                  }

                  // Thumbnail proportional kopieren
                  if (file_exists($pfad.$image.'_tp.jpg')) {
                     copy($pfad.$image.'_tp.jpg', $pfad.$image_new.'_td.jpg');
                  }
               }

               // Kein Bild vorhanden oder auf anderem Server
               else {
                  $this->db_extern->query("INSERT INTO #__articles_images SET parent_id = $new_id, sort = $img->sort, image = '$image'");
               }
            }
         }

         // Preismatrix kopieren
         if (defined('CONF_MODULE_MATRIX')) {
            $matrix = Control::getModuleMatrix();
            $matrix->copyArticle($parent_id, $new_id);
         }
         //Artikel SEO
         // article_seo ($parent_id) in temporäre Tabelle kopieren
         $this->db_extern->query("CREATE TABLE tmps_$rand LIKE #__articles_seo");
         $this->db_extern->query("INSERT INTO tmps_$rand SELECT * FROM #__articles_seo WHERE parent_id = $parent_id");

         // auto_increment entfernen und ID auf NULL
         $this->db_extern->query("ALTER TABLE tmps_$rand CHANGE id id INT");
         $this->db->query("ALTER TABLE tmps_$rand DROP PRIMARY KEY");
         $this->db_extern->query("UPDATE tmps_$rand SET parent_id = $new_id");
         $this->db_extern->query("UPDATE tmps_$rand SET id = NULL");

         // Und als neuen Artikel SEO wieder einfügen
         $this->db_extern->query("INSERT INTO #__articles_seo SELECT * FROM tmps_$rand");

         // Temporäre Tabellen löschen
         $this->db_extern->query("DROP TABLE IF EXISTS tmps_$rand");
      }

      exit(json_encode(['status' => 'ok', 'new_id' => $new_id]));
   }

   // Bilder hochladen
   // 05.07.2019
   private function imageUpload() {
      $image_typ = $this->params->postString('param1');




      if ($image_typ == 'energyefficiency_image') {

          Helper::setData('article_cache', time());
          $uploaddir = SHOP_PATH.'/pictures/energieeffizienz/';


          if(!file_exists($uploaddir))mkdir($uploaddir);

          $parent_id = $this->params->postInt('param2');
          $filename  = $this->params->postString('param1').'_'.$parent_id.'.jpg';
          $img       = $this->params->postString('param1').'_'.$parent_id.'.jpg';

          if (move_uploaded_file($_FILES['file']['tmp_name'], $uploaddir.$filename)) {

              // Helper::imageResize($uploaddir.$filename, $uploaddir.$filename, 0, 0, 'png', false, false, false, 300, 300, true, false);

              $img = SHOP_URL.'/pictures/energieeffizienz/'.$filename."?date=".time();
              $this->db_extern->query("UPDATE #__articles_info SET energy_efficiency_image = '$filename' WHERE id = '$parent_id'");
              exit(json_encode(['status' => 'ok', 'html' => $img, 'target' => 'img_src']));
          }

      }




      // Artikel Startbild
      if ($image_typ == 'startbild') {
         Helper::setData('article_cache', time());
         $parent_id = $this->params->postInt('param2');
         $art_name  = $this->db->querySingleValue("SELECT name_".$this->params->default_lang." FROM #__articles_info WHERE id = $parent_id");
         $uploaddir = SHOP_PATH.'/'.CONF_PICT_PATH;
         $filename  = ($art_name != '' ? Helper::checkFilename($art_name).'_' : '').$parent_id.'_01';

         // vorhandene Bilder Löschen, da Artikel-Name geändert sein könnte
         $del_name  = $this->db_extern->querySingleValue("SELECT image FROM #__articles_info WHERE id = $parent_id");
         @unlink($uploaddir.$del_name.'.jpg');
         @unlink($uploaddir.'original/'.$del_name.'.jpg');
         @unlink($uploaddir.$del_name.'_td.jpg');
         @unlink($uploaddir.$del_name.'_tn.jpg');
         @unlink($uploaddir.$del_name.'_tp.jpg');
         @unlink($uploaddir.$del_name.'_cur.jpg');

         if (move_uploaded_file($_FILES['file']['tmp_name'], $uploaddir.'original/'.$filename.'.jpg')) {
            Helper::makeThumbnails($uploaddir, $filename, 'jpg', $parent_id, 1);
            $this->db_extern->query("UPDATE #__articles_info SET image = '$filename' WHERE id = $parent_id");
            $img = SHOP_URL.'/'.CONF_PICT_PATH.$filename."_tn.jpg?date=".time();

            exit(json_encode(['status' => 'ok', 'html' => $img, 'target' => 'img_src']));
         }
      }

      // Artikel Startbild Hover
      if ($image_typ == 'startbild_hover') {
         Helper::setData('article_cache', time());
         $parent_id = $this->params->postInt('param2');
         $art_name  = $this->db->querySingleValue("SELECT name_".$this->params->default_lang." FROM #__articles_info WHERE id = $parent_id");
         $uploaddir = SHOP_PATH.'/'.CONF_PICT_PATH;
         $filename  = ($art_name != '' ? Helper::checkFilename($art_name).'_' : '').$parent_id.'_hover';

         // vorhandene Bilder Löschen, da Artikel-Name geändert sein könnte
         $del_name  = $this->db_extern->querySingleValue("SELECT image_hover FROM #__articles_info WHERE id = $parent_id");

         if ($del_name != '') {
            @unlink($uploaddir.$del_name.'.jpg');
            @unlink($uploaddir.'original/'.$del_name.'.jpg');
            @unlink($uploaddir.$del_name.'_td.jpg');
            @unlink($uploaddir.$del_name.'_tn.jpg');
            @unlink($uploaddir.$del_name.'_tp.jpg');
         }

         if (move_uploaded_file($_FILES['file']['tmp_name'], $uploaddir.'original/'.$filename.'.jpg')) {
            Helper::makeThumbnails($uploaddir, $filename, 'jpg', $parent_id, 0);
            $this->db_extern->query("UPDATE #__articles_info SET image_hover = '$filename' WHERE id = $parent_id");
            $img = SHOP_URL.'/'.CONF_PICT_PATH.$filename."_tn.jpg?date=".time();

            exit(json_encode(['status' => 'ok', 'html' => $img, 'target' => 'img_src']));
         }
      }

      if ($image_typ == 'artikelgrafik') {
         Helper::setData('article_cache', time());
         $uploaddir = TEMPLATE_PATH.'/images/';
         $parent_id = $this->params->postInt('param2');
         $filename  = 'artikelgrafik'.$this->params->postString('param3').'_'.$this->params->selected_lang.'.png';
         $img       = 'artikelgrafik'.$this->params->postString('param3').'_'.$this->params->selected_lang.'.png';

         if (move_uploaded_file($_FILES['file']['tmp_name'], $uploaddir.$filename)) {
            Helper::imageResize($uploaddir.$filename, $uploaddir.$filename, 0, 0, 'png', false, false, false, 300, 300, true, false);
            $img = TEMPLATE_URL.'/images/'.$filename."?date=".time();

            exit(json_encode(['status' => 'ok', 'html' => $img, 'target' => 'img_src']));
         }
      }

      if ($image_typ == 'musikplayer') {
         $parent_id  = $this->params->postInt('param2');
         $audio_id   = $this->params->postInt('param3');
         $audio_sort = $this->params->postInt('param4');
         $audio_pos  = $this->params->postString('param5');
         $audio_dir = SHOP_PATH.'/downloads/audio/';
         $msg       = '';

         if (!file_exists(SHOP_PATH.'/downloads/.htaccess') || filesize(SHOP_PATH.'/downloads/.htaccess') > 3) {
            file_put_contents(SHOP_PATH.'/downloads/.htaccess', '');
         }

         if (!is_dir($audio_dir)) {
            mkdir($audio_dir);
         }

         // Neue Datei - Eintrag anlegen
         if ($audio_id == 0) {
//            $sort++ = $this->db_extern->query("SELECT MAX(sort) FROM #__musikplayer WHERE parent_id = $parent_id AND position = '$audio_pos'");
            $this->db_extern->query("INSERT INTO #__musikplayer SET parent_id = $parent_id, position = '$audio_pos', type = 'file'");
            $audio_id = $this->db_extern->getNewId();
         }

         // Datei vorhanden / geändert -> loschen
         else {
            // alte Datei löschen
            $mp3 = $this->db_extern->querySingleValue("SELECT filename FROM #__musikplayer WHERE id = $audio_id");

            if ($mp3 != '') {
               @unlink($audio_dir.$mp3);
            }
         }

         $tmp_name = $_FILES['file']['tmp_name'];
         $error    = (int)$_FILES['file']['error'];

         if ($error != 0) {
            $msg = 'Fehler beim Upload '.$error;

            if ($error == 1) {
               $msg = 'Datei ist größer als \'upload_max_filesize\'.';
            }
         }

         $filename = $_FILES['file']['name'];
         $tmp      = explode('.', $filename);
         $ext      = \strtolower($tmp[1]);

         if ($ext != 'mp3' && $ext != 'wav' && $ext != 'ogg' && $ext != 'aac' && $ext != 'wma') {
            exit(json_encode(['status' => 'failed', 'msg' => 'Datei ist keine Audio-Datei !!!']));
         }

         $name = $audio_id.'_'.str_replace([' ', "'", '"'], ['-', ''], $filename);
         move_uploaded_file($tmp_name, "{$audio_dir}{$name}");
         $this->db_extern->query("UPDATE #__musikplayer SET filename = '".$this->db->escape($name)."' WHERE id = $audio_id");

         exit(json_encode(['status' => 'ok', 'html' => SHOP_URL.'/downloads/audio/'.urlencode($name), 'target' => 'musikplayer', 'audio_id' => $audio_id, 'sql' => $this->db_extern->last_sql]));
      }

      Helper::setData('image_cache', time());

      // Bilder Werte
      if ($image_typ == 'werte_image') {
         $wert_id   = $this->params->postInt('param3');
         $uploaddir = TEMPLATE_PATH.'/images/grafische_werte/';

         // Verzeichnis anlegen, falls noch nicht existiert
         if (!is_dir($uploaddir)) {
            mkdir($uploaddir);
         }

         if (!isset($_FILES['file'])) {
            exit(json_encode(['status' => 'error']));
         }

         $filename = strtolower($_FILES['file']['name']);

         if ($wert_id == 0) {
            $this->db_extern->query("INSERT INTO #__werte SET merkmal_id = 0");
            $wert_id = $this->db_extern->getNewId();
         }

         if (move_uploaded_file($_FILES['file']['tmp_name'], $uploaddir.$filename)) {
            $this->db_extern->query("UPDATE #__werte SET wert_img = '$filename' WHERE id = $wert_id");

            $img = TEMPLATE_URL.'/images/grafische_werte/'.$filename;

            exit(json_encode(['status' => 'ok', 'html' => $img.'?'.time(), 'target' => 'img_src', 'wert_id' => $wert_id]));
         }
      }

      // Zubehoerslider
      if ($image_typ == 'zubehoerslider') {
         $slider    = Control::getModuleZubehoerSlider();
         $parent_id = $this->params->postInt('param2');
         $pic_nr    = $this->params->postInt('param3');
         $lang      = $this->params->selected_lang;
         $data      = null;
         $slide_id  = 0;
         $uploaddir = TEMPLATE_PATH.'/images/zubehoerslider/';

         if (!is_dir($uploaddir)) {
            mkdir($uploaddir);
         }


         // Vorhandene Daten lesen, wenn verfügbar
         $json = $this->db_extern->querySingleObject("SELECT id, data FROM #__crosspromo WHERE parent_id = $parent_id AND lang = '$lang'");

         if ($json != null) {
            $data = json_decode($json->data, true);
            $slide_id = $json->id;
         }

         // Sonst neues Array
         else {
            $data = $slider->newSlider();
            $this->db_extern->query("INSERT INTO #__crosspromo SET parent_id = $parent_id, lang = '$lang', data = '".$this->db->escape(json_encode(($data)))."'");
            $slide_id = $this->db_extern->getNewId();
         }

         $temp        = array_keys($_FILES);
         $tempname    = $temp[0];
         $filename    = 'slide_'.$parent_id.'_'.$pic_nr.'_'.$lang.'.jpg';
         $data[$pic_nr]['image'] = $filename;

         move_uploaded_file($_FILES[$tempname]['tmp_name'], $uploaddir.$filename);

         $test = Helper::resizeSlider($uploaddir.$filename, $uploaddir.$filename, 0, CONF_THUMB_Y);
         $img  = TEMPLATE_URL.'/images/zubehoerslider/slide_'.$parent_id.'_'.$pic_nr.'_'.$lang.'.jpg';

         // Slider in DB aktualisieren
         $this->db_extern->query("UPDATE #__crosspromo SET data = '".$this->db->escape(json_encode($data))."' WHERE parent_id = $parent_id AND lang = '$lang'");
//         $data = json_decode($this->db_extern->querySingleValue("SELECT data FROM #__crosspromo WHERE parent_id = $parent_id AND lang = '$lang'"), true);

         exit(json_encode(['status' => 'ok', 'html' => $img.'?'.time(), 'target' => 'img_src']));
//         exit(\json_encode(['status' => 'ok', 'html' => <script>window.top.window.Royalart.sliderUploaded("ok", "'.TEMPLATE_URL.'/images/zubehoerslider/'.$data[$pic_nr]['image'].'?'.time().'");</script>';
      }

      if ($image_typ == 'configurator_wert') {
         $uploaddir = TEMPLATE_PATH.'/images/mega_konfigurator/';
         $uploadurl = TEMPLATE_URL.'/images/mega_konfigurator/';

         // Verzeichnis anlegen, falls noch nicht existiert
         if (!is_dir($uploaddir)) {
            mkdir($uploaddir);
         }

         $merkmal_id = $this->params->postInt('param2');
         $wert_id    = $this->params->postInt('param3');

         if ($merkmal_id > -2) {
            if ($wert_id == 0) {
               $this->db_extern->query("INSERT INTO #__configurator_werte SET merkmal_id = $merkmal_id");
               $wert_id = $this->db_extern->getNewId();
            }

            $filename = 'wert_'.$wert_id.'.jpg';
            $this->db_extern->query("UPDATE #__configurator_werte SET wert_img = '$filename' WHERE id = $wert_id");
            move_uploaded_file($_FILES['file']['tmp_name'], $uploaddir.$filename);
            $test = Helper::resizeSlider($uploaddir.$filename, $uploaddir.$filename, 0, 300);
            \KANPAICLASSIC\Helper::imageResize($uploaddir.$filename, $uploaddir.$filename, 0, 0, 'jpg', false, false, false, 0, 300, false, false);
            $img = $uploadurl.$filename;

            exit(json_encode(['status' => 'ok', 'html' => $img.'?'.time(), 'target' => 'img_src', 'wert_id' => $wert_id]));
         }
      }

      // Modul 360grad ???
      // zusätzliche Bilder
      if (isset($_FILES['file_data'])) {
         $parent_id = $this->params->postInt('parent_id');
         $uploaddir = SHOP_PATH.'/'.CONF_PICT_PATH;
         $uploadurl = SHOP_URL.'/'.CONF_PICT_PATH;

         $count = (int)$this->db_extern->querySingleValue("SELECT IFNULL(MAX(count), 2) FROM #__articles_images WHERE parent_id = $parent_id");
         $sort  = (int)$this->db_extern->querySingleValue("SELECT IFNULL(MAX(sort), 0) FROM #__articles_images WHERE parent_id = $parent_id");
         $sort++;

         if ($count <= $sort) {
            $count = $sort;
         }

         $this->db_extern->query("INSERT INTO #__articles_images SET parent_id = $parent_id,
                                    sort = $sort,
                                    image = ''");
         $db_id = $this->db_extern->getNewId();

         $art_name = $this->db->querySingleValue("SELECT name_".$this->params->default_lang." FROM #__articles_info WHERE id = $parent_id");
         // $parent_id_1 ist Startbild
//         $sort     = 1 + (int)$this->db_extern->querySingleValue("SELECT sort FROM #__articles_images WHERE id = $db_id");
         $filename = ($art_name != '' ? Helper::checkFilename($art_name).'_' : '').$parent_id.'_'.sprintf('%02d', ($count));

         $this->db_extern->query("UPDATE #__articles_images SET image = '$filename' WHERE id = $db_id");
         $count++;
         $this->db_extern->query("UPDATE #__articles_images SET count = $count WHERE parent_id = $parent_id");

         if (move_uploaded_file($_FILES['file_data']['tmp_name'], $uploaddir.'original/'.$filename.'.jpg')) {
            Helper::makeThumbnails($uploaddir, $filename, 'jpg', $parent_id, 2);

            $data             = $this->db_extern->queryAllObjects("SELECT * FROM #__articles_images WHERE parent_id = $parent_id ORDER BY sort");
            $img              = $uploadurl.$filename.'_td.jpg?'.time();

            exit(json_encode(['initialPreview' => [],
                              'initialPreviewConfig' => [],
                              'append' => false]));
         }
      }

      exit(\json_encode(['status' => 'error']));
   }

   private function moreImages($parent_id = 0) {
      Helper::setData('article_cache', time());

      if ($parent_id > 0 && $this->main === null) {
         $this->main = new \stdClass();
         $this->main->images = $this->db_extern->queryAllObjects("SELECT id, sort, image FROM #__articles_images WHERE parent_id = $parent_id ORDER BY sort");
      }

      $html  = '<input type="file" id="more_images" multiple="multiple" />'.CR;
      $multi = ($this->params->multishop ? 'false' : 'true');

      $script = '<script>
      $("#more_images").fileinput({
         uploadAsync           : true,
         uploadUrl             : admin_url_idx+"/ajax/artikel/imageUpload",
         uploadExtraData       : { parent_id : $(\'#parent_id\').val() },
         allowedFileExtensions : ["jpg", "jpeg", "png"],
         showUpload            : true,
         uploadClass           : "button",
         browseOnZoneClick     : '.$multi.',
         autoOrientImage       : false,
         browseIcon            : "<i class=\'button far fa-folder-open\'></i>",
         browseClass           : "button",
         removeClass           : "remove button",
         removeIcon            : "<i class=\'button fas fa-trash-alt\'></i>",
         removeFromPreviewOnError : true,
         uploadClass           : "multishop upload button_orange",
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
      '.CR;

      if ($this->main->images) {
         $img_url = ($this->params->multishop ? \KANPAICLASSIC\Helper::getData('multishop_images') : SHOP_URL).'/'.CONF_PICT_PATH;
         $script .= '   initialPreview: [ '.CR;

         foreach ($this->main->images as $pic) {
            $thumb = (substr($pic->image, 0, 4) !== 'http' ? $img_url.$pic->image.'_td.jpg' : $pic->image).'?'.time();
            $image = (substr($pic->image, 0, 4) !== 'http' ? $img_url.'original/'.$pic->image.'.jpg' : str_replace('/pictures/', '/pictures/original/', $pic->image)).'?'.time();

            $script .= '      \'<img src="'.$thumb.'" class="file-preview-image pointer show_image" alt="" data-src="'.$image.'" data-sort="'.$pic->sort.'" />\','.CR;
         }

         $script .= '   ], '.CR;
         $script .= '   initialPreviewConfig: [ '.CR;

         foreach ($this->main->images as $pic) {
            $script .= '   {
               caption         : "Bild '.((int)$pic->sort + 1).'",
               width           : "78px",
               url             : "'.ADMIN_URL_IDX.'/ajax/artikel/imageDeleteFileupload",
               key             : '.$pic->id.',
            }, '.CR;
         }

         $script .= '], '.CR;
      }

      $script .= '   language              : "de",'.CR;
//               $script .= '}); '.CR;

      $script .= '// Sortierung '.CR;
//               $script .= '$("#more_images").on("filesorted", function(event, params) { '.CR;
      $script .= '}).on("filesorted", function(event, params) { '.CR;
      $script .= '   $.post(admin_url_idx+"/ajax/artikel/fileinputSort", { '.CR;
      $script .= '      oldIndex  : params.oldIndex + 1, '.CR;
      $script .= '      newIndex  : params.newIndex + 1, '.CR;
      $script .= '      parent_id : $("#parent_id").val() '.CR;
      $script .= '   }, function(data) { '.CR;
      $script .= '      if (data.status === "ok") {'.CR;
      $script .= '         var sort = 1;'.CR;
      $script .= '         $(".kv-preview-thumb", $("#fileinput")).each(function() { '.CR;
      $script .= '            $("img", $(this)).attr("data-sort", sort);'.CR;
      $script .= '            sort++;'.CR;
      $script .= '            $(".file-footer-caption", $(this)).attr("title", "Bild "+sort);'.CR;
      $script .= '            $(".file-caption-info", $(this)).html("Bild "+sort);'.CR;
      $script .= '         });'.CR;
      $script .= '      }'.CR;
      $script .= '   }, "json");'.CR;
//               $script .= '});'.CR;

//               $script .= '$("#more_images").on("filedeleted", function() { '.CR;
      $script .= '}).on("filedeleted", function() { '.CR;
      $script .= '   setTimeout(function() {'.CR;
      $script .= '      var sort = 1;'.CR;
      $script .= '      $(".kv-preview-thumb", $("#fileinput")).each(function() { '.CR;
      $script .= '         $("img", $(this)).attr("data-sort", sort);'.CR;
      $script .= '         sort++;'.CR;
      $script .= '         $(".file-footer-caption", $(this)).attr("title", "Bild "+sort);'.CR;
      $script .= '         $(".file-caption-info", $(this)).html("Bild "+sort);'.CR;
      $script .= '      });'.CR;
      $script .= '   }, 1000);'.CR;
//      $script .= '});'.CR;

      // Kein Upload bei neuem Artikel
      $script .= '}).on("filebrowse", function() {'.CR;
      $script .= '   if (parseInt($("#parent_id").val()) === 0) {'.CR;
      $script .= '      alertbox(Artikel.neuMsg);'.CR;
      $script .= '      $("#more_images").fileupload("cancel");'.CR;
      $script .= '      return false;'.CR;
      $script .= '   }'.CR;

      // Alle Dateien hochgeladen
      $script .= '}).on("filebatchuploadcomplete", function(event) { '.CR;
      $script .= '   Artikel.moreImages();';
      $script .= '}); ';

      $script .= '</script> ';

      return ['html' => $html, 'script' => $script];
   }

   private function videoUpload(){
      $output = [];
      $productid = $this->params->postInt('productid');
      if(!empty($_FILES["videoupload"])){
         $success = Control::getModuleVideo()->handleUpload($productid, $_FILES["videoupload"]);
         unset($_FILES);
         $listVideos = Control::getModuleVideo()->listVideos($productid);
         $videosArr = $videosArrConfig = [];
         foreach($listVideos as $id=>$video){
            $videolink = Control::getModuleVideo()->getVideoUrl($productid, $video);
            $videosArr[] = $videolink;
            $videosArrConfig[] = ['type' => 'video', 'filetype' => 'video/mp4', 'key' => $productid.'-'.$video, 'extra' => ['productid' => $productid, 'videoname' => $video]];
         }
         $output = ['initialPreview' => $videosArr, 'initialPreviewConfig' => $videosArrConfig, 'initialPreviewAsData' => true];
         
      }
      exit(json_encode($output));
      
   }

   private function videoDelete(){


       $productid = $this->params->postInt('productid');
       $videoname      = $this->params->postString('videoname');
       $success = Control::getModuleVideo()->deleteVideo((int)$productid, $videoname);
       exit(json_encode(['status' => $success?'ok':'failed', 'test' => $productid." ".$videoname]));

   }

   private function imageDelete() {

      $parent_id = $this->params->postInt('parent_id');
      $type      = $this->params->postString('type');

      if ($type == 'energyefficiency_image') {

          $uploaddir = SHOP_PATH.'/pictures/energieeffizienz/';

          $parent_id = $this->params->postInt('bild_nr');
          $filename  = $this->params->postString('type').'_'.$parent_id.'.jpg';
          $image       = $this->params->postString('type').'_'.$parent_id.'.jpg';

          @unlink($uploaddir.$filename);

          // $this->db_extern->query("UPDATE #__articles_info SET image = '', image_hover = '' WHERE id = $parent_id");

          exit(json_encode(['status' => 'ok', 'test' => $uploaddir.$filename]));
      }



      if ($type == 'artikelgrafik') {
         $image = TEMPLATE_PATH.'/images/artikelgrafik'.$this->params->postInt('bild_nr').'_'.$this->params->selected_lang.'.png';
         @unlink($image);

         exit(json_encode(['status' => 'ok', 'test' => $image]));
      }

      if ($type == 'startbild') {
         $image_path  = SHOP_URL.CONF_PICT_PATH;
         $images      = $this->db_extern->querySingleObject("SELECT image, image_hover from #__articles_info WHERE id = $parent_id");
         $image       = $images->image;
         $image_hover = $images->image_hover;

         $this->db_extern->query("UPDATE #__articles_info SET image = '', image_hover = '' WHERE id = $parent_id");

         if (substr($image,0 , 4) !== 'http') {
            @unlink($image_path.$image.'.jpg');
            @unlink($image_path.'originale/'.$image.'.jpg');
            @unlink($image_path.$image.'_tn.jpg');
            @unlink($image_path.$image.'_td.jpg');
            @unlink($image_path.$image.'_tp.jpg');
            @unlink($image_path.$image.'_cur.jpg');
         }

         if ($image_hover != '' && substr($image_hover,0 , 4) !== 'http') {
            @unlink($image_path.$image_hover.'.jpg');
            @unlink($image_path.'originale/'.$image_hover.'.jpg');
            @unlink($image_path.$image_hover.'_tn.jpg');
            @unlink($image_path.$image_hover.'_td.jpg');
            @unlink($image_path.$image_hover.'_tp.jpg');
         }

         exit(json_encode(['status' => 'ok']));
      }

   }

   // Zusätzliches Bild löschen / Sortierung korrigieren
   // 05.07.2019
   private function imageDeleteFileupload() {
      $image_path = SHOP_URL.CONF_PICT_PATH;
      $img_id     = $this->params->postInt('key');
      $data       = $this->db_extern->querySingleObject("SELECT parent_id, sort, image FROM #__articles_images WHERE id = $img_id");
      $image      = $data->image;
      $parent_id  = $data->parent_id;
      $sort       = $data->sort;

      @unlink($image_path.$image.'.jpg');
      @unlink($image_path.'originale/'.$image.'.jpg');
      @unlink($image_path.$image.'_tn.jpg');
      @unlink($image_path.$image.'_td.jpg');
      @unlink($image_path.$image.'_tp.jpg');
      @unlink($image_path.$image.'_cur.jpg');

      $this->db_extern->query("DELETE FROM #__articles_images WHERE id = $img_id");
      $this->db_extern->query("UPDATE #__articles_images SET sort = sort - 1 WHERE parent_id = $parent_id AND sort > $sort ORDER BY sort");

      exit(json_encode(['sort' => $sort]));
   }

   // Reihenfolge zusätzlicher Bilder mit der Maus verschoben
   // 05.07.2019

   private function fileinputSort() {
      $parent_id = $this->params->postInt('parent_id');
      $oldsort   = $this->params->postInt('oldIndex');
      $newsort   = $this->params->postInt('newIndex');
      $el        = $this->db_extern->querySingleValue("SELECT id FROM #__articles_images WHERE parent_id = $parent_id AND sort = $oldsort");
      $this->db_extern->query("UPDATE #__articles_images SET sort = 255 WHERE id = $el");

      // nach hinten verschieben
      if ($oldsort < $newsort) {
//echo 'nach hinten';
         $this->db_extern->query("UPDATE #__articles_images SET sort = sort - 1 WHERE parent_id = $parent_id AND sort >= $oldsort AND sort <= $newsort");
      }

      // Nach vorn verschieben
      else if ($newsort < $oldsort) {
//echo 'nach vorn';
         $this->db_extern->query("UPDATE #__articles_images SET sort = sort + 1 WHERE parent_id = $parent_id AND sort >= $newsort AND sort <= $oldsort ORDER BY sort DESC");
      }

//echo $this->db_extern->last_sql;
      $this->db_extern->query("UPDATE #__articles_images SET sort = $newsort WHERE id = $el");
      exit(json_encode(['status' => 'ok']));
   }

   // Sort Artikel Videos
   private function videoSort() {
      $articleid   = $this->params->postInt('parent_id');
      $newSort     = $this->params->postArray('newSort');
      Control::getModuleVideo()->editVideoSort($articleid,$newSort);
      exit(json_encode(['status' => 'ok']));
   }

   // Details / HTML für Steuern generieren
   private function _getSteuerSelect($steuersatz) {
      $html  = '';

      // Bei Kleingewerbe + USt nicht aktiv überschreiben
      if ($this->params->firma['kleingewerbe'] == 'y' && $this->params->firma['tax_active'] == 'n') {
         $html .= '<div id="steuern">'.CR;
         $html .= '   <input type="hidden" id="show_netto" value="y" />';
         $html .= '   <div class="steuersatz txt_bez">Endpreis</div>'.CR;
         $html .= '   <input type="hidden" id="steuersatz" name="steuersatz" value="1">'.CR;
         $html .= '</div>'.CR;
      }

      // Bei Kleingewerbe überschreiben
      else if ($this->params->firma['kleingewerbe'] == 'y') {
         $html .= '<div id="steuern">'.CR;
         $html .= '   <input type="hidden" id="show_netto" value="y" />';
         $html .= '   <div class="steuersatz txt_bez">Endpreis</div>'.CR;
         $html .= '   <input type="hidden" id="steuersatz" name="steuersatz" value="1">'.CR;
         $html .= '</div>'.CR;
      }

      // Bei USt nicht aktiv überschreiben
      else if ($this->params->firma['tax_active'] == 'n') {
         $html .= '<div id="steuern" style="position:relative; width:150px;">'.CR;
         $html .= '   <div class="steuersatz">MwSt. ist deaktiv</div>'.CR;
         $html .= '   <input type="hidden" id="show_netto" value="y" />';
         $html .= '   <input type="hidden" id="steuersatz" name="steuersatz" value="1">'.CR;
         $html .= '</div>'.CR;
      }

      // Steuersatzauswahl
      else {
         $html .= '<div id="steuern">'.CR;
         $html .= '   <input type="hidden" id="steuer" value="'.$this->params->firma['tax'.$steuersatz].'" />'.CR;
         $html .= '   <input type="hidden" id="show_netto" value="n" />';
         $html .= '   <span class="selectbox30">'.CR;
         $html .= '      <select id="steuersatz" name="steuersatz" onchange="Artikel.changeSteuer(this.value);">'.CR;

         for ($i = 1; $i < 4; $i++) {
            $html .= '         <option value="'.$i.'"'.($steuersatz == $i ? ' selected="selected"' : '').'>'.$this->params->firma['tax'.$i].' %</option>'.CR;
         }

         $html .= '      </select>'.CR;
         $html .= '   </span>'.CR;
         $html .= '</div>'.CR;
      }


      return $html;
   }

   // Preis pro Grundeinheit
   // Verwendet in article_edit.tpl.php
   // und Modul Foto
   private function grundeinheitenPopup() {
      $parent_id = $this->params->postInt('parent_id');
      $data      = $this->db_extern->querySingleObject("SELECT ge_netto_aktiv, grundeinheit FROM #__articles_info WHERE id = $parent_id");

      // Grundeinheit Default = stk;
      if ($data->grundeinheit == '') {
         $data->grundeinheit = 'stk';
      }

      $html  = '<div id="popup_grundeinheiten">'.CR;
      $html .= '   <h1 class="txt_tit">Grundpreis je Einheit</h1>'.CR;
      $html .= '   <div class="subtitle">Gilt für alle Varianten dieses Artikels</div>'.CR;

      $html .= '   <div class="title2">'.CR;
      $html .= '      <span class="txt_bez">Grundpreis</span>'.CR;
      $html .= '      <input type="checkbox" class="newdesign" id="popup_ge_netto_aktiv"'.($data->ge_netto_aktiv == 'y' ? ' checked="checked"' : '').'
                         onchange="($(this).prop(\'checked\') ? $(\'#grundpreis_text\').show() : $(\'#grundpreis_text\').hide());
                                   ($(this).prop(\'checked\') ? $(\'.ge_edit_hide\').show() : $(\'.ge_edit_hide\').hide());" />'.CR;
      $html .= '      <label for="popup_ge_netto_aktiv"></label>anzeigen<br />'.CR;
      $html .= '      <input type="hidden" id="popup_grundeinheit" value="'.$data->grundeinheit.'" />'.CR;
      $html .= '      <input type="hidden" id="popup_gewicht_ge" value="" />'.CR;
      $html .= '      <div id="grundpreis_text" style="display:'.($data->ge_netto_aktiv == 'y' ? '' : 'none').';">anschließend je Variante das Gewicht bzw. Länge eintragen</div>'.CR;
      $html .= '   </div>';

      // Ausgabe Grundeinheit
      $html .= '   <div class="grundeinheiten">'.CR;
      $html .= '      <div class="txt_bez">'.CR;
      $html .= '         <span class="ge-netto-l txt_bez">Grundeinheit in</span>'.CR;
      $html .= '      </div>'.CR;
      $html .= $this->_grundeinheit('', $data->grundeinheit);
      $html .= '      <div class="clear"></div>'.CR;
      $html .= '   </div>'.CR;

      $html .= '   <div class="buttonzeile">'.CR;
      $html .= '      <span class="button button_left txt_but" onclick="Multibox.close();">abbrechen</span>'.CR;
      $html .= '      <span class="button_ci button_right txt_but" onclick="Artikel.grundeinheitenSave();">speichern</span>'.CR;
      $html .= '   </div>'.CR;
      $html .= '</div>'.CR;

      exit(json_encode(['status' => 'ok', 'html' => $html]));
   }

   private function rechnerPopup() {
      $parent_id = $this->params->postInt('parent_id');
      $data      = $this->db_extern->querySingleValue("SELECT grundeinheit_rechner FROM #__articles_info WHERE id = $parent_id");

      // Grundeinheit Default = stk;
      if ($data == '') {
         $data = 'stk';
      }

      $html  = '<div id="popup_grundeinheiten">'.CR;
      $html .= '   <h1 class="txt_tit">Maßeinheit</h1>'.CR;
      $html .= '   <input type="hidden" id="popup_grundeinheit" value="'.$data.'" />'.CR;
      $html .= '   <input type="hidden" id="popup_grundeinheit_name" value="'.($data == '' ? 'Stück' : $this->text->get('ge', $data)).'" />'.CR;
      $html .= $this->_grundeinheit('_rechner', $data);
      $html .= '   <div class="buttonzeile">'.CR;
      $html .= '      <span class="button button_left txt_but" onclick="Multibox.close();">abbrechen</span>'.CR;
      $html .= '      <span class="button_ci button_right txt_but" onclick="Artikel.popupRechnerSave();">übernehmen</span>'.CR;
      $html .= '   </div>'.CR;
      $html .= '</div>'.CR;

      exit(json_encode(['status' => 'ok', 'html' => $html]));
   }

   private function _grundeinheit($typ, $ge) {
      $js = 'popupRechnerChange';

      if ($typ != '_rechner') {
         $js = 'popupGrundeinheitenChange';
      }

      $html  = '      <div class="ge">'.CR;
      $html .= '         <input type="radio" class="newdesign" id="grundeinheit_1" onchange="Artikel.'.$js.'(this, \'Kg\');" name="grundeinheit'.$typ.'" value="kg"'.($ge == 'kg' ? ' checked="checked"' : '').' />'.CR;
      $html .= '         <label for="grundeinheit_1">Kg</label>'.CR;
      $html .= '      </div>'.CR;
      $html .= '      <div class="ge">'.CR;
      $html .= '         <input type="radio" class="newdesign" id="grundeinheit_2" onchange="Artikel.'.$js.'(this, \'g\');" name="grundeinheit'.$typ.'" value="100g"'.($ge == "100g" ? " checked='checked'" : '').' />'.CR;
      $html .= '         <label for="grundeinheit_2">100g</label>'.CR;
      $html .= '      </div>'.CR;
      $html .= '      <div class="ge ge_hidden">'.CR;
      $html .= '         <input type="radio" class="newdesign" id="grundeinheit_3" onchange="Artikel.'.$js.'(this, \'g\');" name="grundeinheit'.$typ.'" value="10g"'.($ge == "10g" ? " checked='checked'" : '').' />'.CR;
      $html .= '         <label for="grundeinheit_3">10g</label>'.CR;
      $html .= '      </div>'.CR;
      $html .= '      <div class="ge ge_hidden">'.CR;
      $html .= '         <input type="radio" class="newdesign" id="grundeinheit_4" onchange="Artikel.'.$js.'(this, \'g\');" name="grundeinheit'.$typ.'" value="g"'.($ge == "g" ? " checked='checked'" : '').' />'.CR;
      $html .= '         <label for="grundeinheit_4">g</label>'.CR;
      $html .= '      </div>'.CR;
      $html .= '      <div class="clear"></div>'.CR;

      $html .= '      <div class="ge">'.CR;
      $html .= '         <input type="radio" class="newdesign" id="grundeinheit_5" onchange="Artikel.'.$js.'(this, \'liter\');" name="grundeinheit'.$typ.'" value="liter"'.($ge == "liter" ? " checked='checked'" : '').' />'.CR;
      $html .= '         <label for="grundeinheit_5">liter</label>'.CR;
      $html .= '      </div>'.CR;
      $html .= '      <div class="ge">'.CR;
      $html .= '         <input type="radio" class="newdesign" id="grundeinheit_6" onchange="Artikel.'.$js.'(this, \'ml\');" name="grundeinheit'.$typ.'" value="100ml"'.($ge == "100ml" ? " checked='checked'" : '').' />'.CR;
      $html .= '         <label for="grundeinheit_6">100ml</label>'.CR;
      $html .= '      </div>'.CR;
      $html .= '      <div class="ge ge_hidden">'.CR;
      $html .= '         <input type="radio" class="newdesign" id="grundeinheit_7" onchange="Artikel.'.$js.'(this, \'ml\');" name="grundeinheit'.$typ.'" value="10ml"'.($ge == "10ml" ? " checked='checked'" : '').' />'.CR;
      $html .= '         <label for="grundeinheit_7">10ml</label>'.CR;
      $html .= '      </div>'.CR;
      $html .= '      <div class="ge ge_hidden">'.CR;
      $html .= '         <input type="radio" class="newdesign" id="grundeinheit_8" onchange="Artikel.'.$js.'(this, \'ml\');" name="grundeinheit'.$typ.'" value="ml"'.($ge == "ml" ? " checked='checked'" : '').' />'.CR;
      $html .= '         <label for="grundeinheit_8">ml</label>'.CR;
      $html .= '      </div>'.CR;
      $html .= '      <div class="clear"></div>'.CR;

      $html .= '      <div class="ge">'.CR;
      $html .= '         <input type="radio" class="newdesign" id="grundeinheit_9" onchange="Artikel.'.$js.'(this, \'\');" name="grundeinheit'.$typ.'" value="m"'.($ge == "m" ? " checked='checked'" : '').' />'.CR;
      $html .= '         <label for="grundeinheit_9">m</label>'.CR;
      $html .= '      </div>'.CR;
      $html .= '      <div class="ge ge_hidden">'.CR;
      $html .= '         <input type="radio" class="newdesign" id="grundeinheit_10" onchange="Artikel.'.$js.'(this, \'\');" name="grundeinheit'.$typ.'" value="dm"'.($ge == "dm" ? " checked='checked'" : '').' />'.CR;
      $html .= '         <label for="grundeinheit_10">dm</label>'.CR;
      $html .= '      </div>'.CR;
      $html .= '      <div class="ge ge_hidden">'.CR;
      $html .= '         <input type="radio" class="newdesign" id="grundeinheit_11" onchange="Artikel.'.$js.'(this, \'\');" name="grundeinheit'.$typ.'" value="cm"'.($ge == "cm" ? " checked='checked'" : '').' />'.CR;
      $html .= '         <label for="grundeinheit_11">cm</label>'.CR;
      $html .= '      </div>'.CR;
      $html .= '      <div class="ge ge_hidden">'.CR;
      $html .= '         <input type="radio" class="newdesign" id="grundeinheit_12" onchange="Artikel.'.$js.'(this, \'\');" name="grundeinheit'.$typ.'" value="mm"'.($ge == "mm" ? " checked='checked'" : '').' />'.CR;
      $html .= '         <label for="grundeinheit_12">mm</label>'.CR;
      $html .= '      </div>'.CR;
      $html .= '      <div class="clear"></div>';

      $html .= '      <div class="ge">'.CR;
      $html .= '         <input type="radio" class="newdesign" id="grundeinheit_13" onchange="Artikel.'.$js.'(this, \'\');" name="grundeinheit'.$typ.'" value="m2"'.($ge == "m2" ? " checked='checked'" : '').' />'.CR;
      $html .= '         <label for="grundeinheit_13">m²</label>'.CR;
      $html .= '      </div>'.CR;
      $html .= '      <div class="ge ge_hidden">'.CR;
      $html .= '         <input type="radio" class="newdesign" id="grundeinheit_14" onchange="Artikel.'.$js.'(this, \'\');" name="grundeinheit'.$typ.'" value="dm2"'.($ge == "dm2" ? " checked='checked'" : '').' />'.CR;
      $html .= '         <label for="grundeinheit_14">dm²</label>'.CR;
      $html .= '      </div>'.CR;
      $html .= '      <div class="ge ge_hidden">'.CR;
      $html .= '         <input type="radio" class="newdesign" id="grundeinheit_15" onchange="Artikel.'.$js.'(this, \'\');" name="grundeinheit'.$typ.'" value="cm2"'.($ge == "cm2" ? " checked='checked'" : '').' />'.CR;
      $html .= '         <label for="grundeinheit_15">cm²</label>'.CR;
      $html .= '      </div>'.CR;
      $html .= '      <div class="ge ge_hidden">'.CR;
      $html .= '         <input type="radio" class="newdesign" id="grundeinheit_16" onchange="Artikel.'.$js.'(this, \'\');" name="grundeinheit'.$typ.'" value="mm2"'.($ge == "mm2" ? " checked='checked'" : '').' />'.CR;
      $html .= '         <label for="grundeinheit_16">mm²</label>'.CR;
      $html .= '      </div>'.CR;
      $html .= '      <div class="clear"></div>';

      $html .= '      <div class="ge">'.CR;
      $html .= '         <input type="radio" class="newdesign" id="grundeinheit_17" onchange="Artikel.'.$js.'(this, \'g\');" name="grundeinheit'.$typ.'" value="m3"'.($ge == "m3" ? " checked='checked'" : '').' />'.CR;
      $html .= '         <label for="grundeinheit_17">m³</label>'.CR;
      $html .= '      </div>'.CR;
      $html .= '      <div class="ge ge_hidden">'.CR;
      $html .= '         <input type="radio" class="newdesign" id="grundeinheit_18" onchange="Artikel.'.$js.'(this, \'\');" name="grundeinheit'.$typ.'" value="dm3"'.($ge == "dm3" ? " checked='checked'" : '').' />'.CR;
      $html .= '         <label for="grundeinheit_18">dm³</label>'.CR;
      $html .= '      </div>'.CR;
      $html .= '      <div class="ge ge_hidden">'.CR;
      $html .= '         <input type="radio" class="newdesign" id="grundeinheit_19" onchange="Artikel.'.$js.'(this, \'\');" name="grundeinheit'.$typ.'" value="cm3"'.($ge == "cm3" ? " checked='checked'" : '').' />'.CR;
      $html .= '         <label for="grundeinheit_19">cm³</label>'.CR;
      $html .= '      </div>'.CR;
      $html .= '      <div class="ge ge_hidden">'.CR;
      $html .= '         <input type="radio" class="newdesign" id="grundeinheit_20" onchange="Artikel.'.$js.'(this, \'\');" name="grundeinheit'.$typ.'" value="mm3"'.($ge == "mm3" ? " checked='checked'" : '').' />'.CR;
      $html .= '         <label for="grundeinheit_20">mm³</label>'.CR;
      $html .= '      </div>'.CR;
      $html .= '      <div class="clear"></div>';

      $html .= '      <div class="ge">'.CR;
      $html .= '         <input type="radio" class="newdesign" id="grundeinheit_21" onchange="Artikel.'.$js.'(this, \'\');" name="grundeinheit'.$typ.'" value="stk"'.($ge == "stk" ? " checked='checked'" : '').' />'.CR;
      $html .= '         <label for="grundeinheit_21">Stück</label>'.CR;
      $html .= '      </div>'.CR;
      $html .= '      <div class="clear"></div>';

      return $html;
   }

   /*
   // Liste / Suche bei Ajax
   // Nicht verwendet
   private function searchStart($func = 'bestellungAdd') {
      $lang = $this->params->selected_lang;
      $searchstring = $this->params->postString('search', '', 'sql');
      $html = '';
      $data =  $this->db_extern->queryAllObjects("SELECT a.id, a.art_nr, i.name_$lang as name, w.wert_$lang as wert1, ww.wert_$lang as wert2
                                              FROM #__articles as a
                                           LEFT JOIN #__articles_info AS i
                                              ON a.parent_id = i.id
                                           LEFT JOIN #__werte as w
                                              ON a.wert1 = w.id
                                           LEFT JOIN #__werte as ww
                                              ON a.wert2 = ww.id
                                           WHERE i.name_$lang LIKE '$searchstring%' OR a.art_nr LIKE '$searchstring%' LIMIT 0, 30");

      for ($i = 0; $i < count($data); $i++) {
         $html .= '<div class="search-list" onclick="Royalart.'.$func.'('.$data[$i]->id.', 0);">'.$data[$i]->art_nr.' '.$data[$i]->name.($data[$i]->wert1 != '' ? ', '.$data[$i]->wert1 : '').($data[$i]->wert2 != '' ? ', '.$data[$i]->wert2 : '').'</div>';
      }

      if ($html =='') {
         $html = 'not found';
      }

      $html .= "<div class='searchclose' onclick=\"this.parentNode.style.display=('none');\">Schließen</div>";
      return $html;
   }
   */

   // Details / Kategorien des Artikels aus DB lesen
   // 11.01.2019
   private function _getCatIds($parent) {
      if ($parent == 0) {
         // muss gültiger Wert sein
         return [0 => (object)['cat_id' => 0]];
      }

      $data = $this->db_extern->queryAllObjects("SELECT cat_id FROM #__article_to_cats WHERE parent_id = $parent ORDER BY sort");
      return $data;
   }

   // Staffelpreise nach Menge sortieren
   // Format in DB und Formular: Aktiv;menge;nachlass#aktiv;menge;nachlass#...
   // 05.07.2019
   private function _staffelungSort($staffelung) {
      if ($staffelung == '') {
         return $staffelung;
      }

      // DB in Array
      $staff_array = [];
      $staff_tmp   = explode('#', $staffelung);
      $tmp         = [];

      for ($i = 0; $i < count($staff_tmp); $i++) {
         $test = explode(';', $staff_tmp[$i]);

         if ((int)$test[1] > 0) {
            $staff_array[] = $test;
         }
      }

      // Menge in Array einlesen und danach Original-Array sortieren
      foreach ($staff_array as $arr) {
         $tmp[] = $arr[1];
      }

      array_multisort($tmp, SORT_NUMERIC, SORT_ASC, $staff_array);

      // Array wieder in DB-Format wandeln
      $staff_temp = [];

      for ($i = 0; $i < count($staff_array); $i++) {
         $staff_temp[$i] = implode(';', $staff_array[$i]);
      }

      return implode('#', $staff_temp);
   }


   // Staffelung-HTML zurück geben
   // 05.07.2019
   private function getStaffelung($parent_id) {
      $html       = '';
      $staff_new  = false;
      $neu        = $this->params->postInt('neu');
      $steuersatz = 1;
      $staffelung = '';

      // Aufruf von Details / Daten via Ajax
      if ($neu) {
         $staffelung = $this->params->postString('staffelung');

         // Artikel schon gespeichert, Steuersatz auslesen
         if ($parent_id > 0) {
            $steuersatz = $this->db_extern->querySingleValue("SELECT steuersatz FROM #__articles_info WHERE id = $parent_id");
         }
      }

      // Aufruf für Template / Daten aus DB
      else {
         $staffelung = $this->main->staffelung;
         $steuersatz = $this->main->steuersatz;

         if ($staffelung == 'undefined') {
            $staffelung = '';
            $this->main->staffelung = '';
         }

         else {
            $staffelung = $this->_staffelungSort($staffelung);
         }
      }

      $staff_tmp  = explode('#', $staffelung);

      if ($staff_tmp[0] == '') {
         $staff_tmp = [];
      }

      if ($neu) {
         $staff_tmp[] = "y;0;0";
//         $this->staffelung .= "#y;0;0";
         $staffelung .= "#y;0;0";
         $staff_new = true;
      }

      elseif ($staffelung == '') {
         $staff_tmp[] = "n;100;-10";
         $staff_new = true;
      }


      $idx = 0;
      $staff_array = [];

      for ($i = 1; $i <= count($staff_tmp); $i++) {
         $staff_array = explode(';', $staff_tmp[$i - 1]);
         $class       = '';

         // Neuer Eintrag am Ende
         if ($staff_new && count($staff_tmp) == $i) {
            $class         = ' is_new';
         }

         $html .= '<div class="staffelung_line'.$class.'">'.CR;
         $html .= '   <input type="checkbox" class="staffelung_online newdesign"'.($staff_array[0] == 'y' ? ' checked=\'checked\'' : '') . ' id="staff_online'.$i.'" name="staff_online'.$i.'" autocomplete="off" onchange="Artikel.staffelungChange(this, \'online\');" />';
         $html .= '   <label for="staff_online'.$i.'"></label>'.CR;
         $html .= '   <span class="staffelung_delete far fa-trash-alt" onclick="Artikel.staffelungChange(this, \'delete\');"></span>'.CR;
         $html .= '   <input type="text" class="staffelung_stueck txt_inp right" name="staff_stueck'.$i.'" value="' . $staff_array[1].'" autocomplete="off" onchange="Artikel.staffelungChange(this, \'stueck\');" />'.CR;
         $html .= '   <input type="hidden" class="index" value="'.$idx.'" />'.CR;

         // Nur Netto
         if ($this->params->firma['kleingewerbe'] == 'y' || $this->params->firma['tax_active'] == 'n') {
            $html .= '   <input type="text" class="staffelung_netto txt_inp right" name="staff_netto'.$i.'" value="'.number_format((float)$staff_array[2], 2, ',', '.').'" autocomplete="off" onchange="Artikel.staffelungChange(this, \'klein\');" />'.CR;
            $html .= '   <input type="hidden" class="staffelung_brutto" name="staffelung_brutto'.$i.'" value="'.number_format((float)$staff_array[2], 2, ',', '.').'" autocomplete="off" />'.CR;
            $html .= '   <input type="hidden" class="staffelung_netto_real" name="staff_netto_real" value="' . $staff_array[2].'" autocomplete="off" />'.CR;

         }

         // Netto und Brutto
         else {
            $html .= '   <input type="text" class="staffelung_netto txt_inp right"  name="staffelung_netto'.$i.'"  value="'.number_format((float)$staff_array[2], 2, ',', '.').'" autocomplete="off" onchange="Artikel.staffelungChange(this, \'netto\');" />'.CR;
            $html .= '   <input type="text" class="staffelung_brutto txt_inp right" name="staffelung_brutto'.$i.'" value="'.number_format((float)$staff_array[2] * (1 + (float)$this->params->firma['tax'.$steuersatz] / 100), 2, ',', '.').'" autocomplete="off" onchange="Artikel.staffelungChange(this, \'brutto\');" />'.CR;
            $html .= '   <input type="hidden" class="staffelung_netto_real"  name="staff_netto_real'.$i.'" value="'.$staff_array[2].'" autocomplete="off" />'.CR;
         }

         $html .= '   <div class="clear"></div>'.CR;
         $html .= '</div>'.CR;
         $idx++;
      }

      $html .=    '<input type="hidden" id="staffelung_val" name="staffelung_val" value="'.$staffelung.'" />'.CR;

      return $html;
   }


   private function checkName($name) {
      $name = str_replace('/', '_', $name);
      $name = str_replace('\\', '_', $name);
      $name = str_replace('*', '_', $name);
      $name = str_replace('.', '_', $name);
      $name = str_replace('?', '_', $name);
      return $name;
   }

   // Datei für Downloadartikel speichern
   private function downloadArticleUpload() {
      $article_id  = $this->params->postInt('param3');
      $oldfilename = $this->db->querySingleValue("SELECT filename FROM #__articles WHERE id = $article_id");
      $uploaddir   = SHOP_PATH.'/downloads/';

      // Fehler bei Übertragung
      if (!isset($_FILES['file'])) {
         exit(json_encode(['target' => 'download_article', 'status' => 'ok', 'x_status' => 'error', 'html' => '', 'msg' => 'Datei für Upload zu groß']));
      }

      $filename = strtolower($_FILES['file']['name']);
      $filename = str_replace("'", '', $filename);
      $filename = str_replace(" ", '_', $filename);
      $fullname = $uploaddir.$filename;
      $filetype = $_FILES['file']['type'];

      // Test, ob Datei bereits vorhanden (bei anderem Artikel)
      if (file_exists($fullname)) {
         if ($filename != $oldfilename) {
            exit(json_encode(['target' => 'download_article', 'status' => 'ok', 'x_status' => 'used', 'html' => '', 'msg' => 'Datei existiert bereits für einen anderen Artikel']));
         }

         else if ($oldfilename != '') {
            unlink(SHOP_PATH.'/downloads/'.$oldfilename);
         }
      }

      if (move_uploaded_file($_FILES['file']['tmp_name'], $fullname)) {
         $this->db->query("UPDATE #__articles SET filename = '$filename', filetyp = '$filetype' WHERE id = $article_id");
      }

      else {
         exit(json_encode(['target' => 'download_article', 'status' => 'ok', 'x_status' => 'error', 'html' => '', 'msg' => 'Datei konnte nicht gespeichert werden']));
      }

      exit(json_encode(['target' => 'download_article', 'status' => 'ok', 'x_status' => 'ok', 'html' => '<span class="xdownload pointer download_button" onclick="Artikel.downloadArticleDownload(this, '.$article_id.');" title="'.$filename.'"></span><span class="xdelete pointer far fa-trash-alt" onclick="Artikel.downloadArticleDelete(this, '.$article_id.');" title="Datei für Downloadartikel löschen"></span>', 'msg' => '']));
   }

   // Datei für Downloadartikel löschen
   public function downloadArticleDelete() {
      $article_id = $this->params->postInt('article_id');
      $filename   = $this->db->querySingleValue("SELECT filename FROM #__articles WHERE id = $article_id");

      $this->db->query("UPDATE #__articles SET filename = '', filetyp = '' WHERE id = $article_id");

      if ($filename != '' && file_exists(SHOP_PATH.'/downloads/'.$filename)) {
         unlink(SHOP_PATH.'/downloads/'.$filename);
      }

      exit(json_encode(['status' => 'ok', 'datei' => $filename, 'html' => '<span class="xdownload pointer upload_button" onclick="Artikel.downloadArticleUpload(this, '.$article_id.');" title="Datei für Downloadartikel hochladen"></span><span class="xdelete_no far fa-trash-alt"></span>', 'msg' => '']));
   }

   // Datei für Downloadartikel hochladen
   private function downloadArticleDownload() {
      $article_id  = $this->params->params3;
      $file        = $this->db->querySingleObject("SELECT filename, filetyp FROM #__articles WHERE id = $article_id");

      if ($file) {
         header('Content-Type: '.$file->filetyp);
         header('Content-Transfer-Encoding: Binary');
         header('Content-disposition: attachment; filename="'.$file->filename.'"');
         readfile(SHOP_PATH.'/downloads/'.$file->filename);
      }

      exit;
   }

   /* ********** Google-Shopping ********** */
   // 23.07.2019
   private function _getGoogle() {
      $gshop = Control::getImportExport();

      $html  = '            <div class="google_title">'.CR;
      $html .= '               <a class="help_kanpaiclassic" href="'.HELP_LINK.'/o15/google-shopping/" target="_blank" alt=""></a>'.CR;
      $html .= '               <span class="txt_bez">Google-Shopping</span>'.CR;
      $html .= '            </div>'.CR;

      $html .= '            <div class="google_left">'.CR;
      $html .= '               <div>Google-Kategorie</div>'.CR;
      $html .= '               <div id="googlecats">'.CR;
      $html .=                    $gshop->getGoogleCatOptions($this->main->g_cats);
      $html .= '               </div>'.CR;
      $html .= '            </div>'.CR;

      $html .= '            <div class="google_center">'.CR;
      $html .= '               <table>'.CR;
      $html .= '                  <tr>'.CR;
      $html .= '                     <td>Zustand des Artikels&nbsp;</td>'.CR;
      $html .= '                     <td>'.CR;
      $html .= '                        <div class="google_sel_div txt_inp">'.CR;
      $html .= '                           <span class="selectbox30">'.CR;
      $html .= '                              <select class="googlezustand" autocomplete="off" name="g_zustand" id="g_zustand">'.CR;
      $html .= '                                 <option value="n"'.($this->main->g_zustand == 'n' ? ' selected="selected"' : '').'>neu</option>'.CR;
      $html .= '                                 <option value="g"'.($this->main->g_zustand == 'g' ? ' selected="selected"' : '').'>gebraucht</option>'.CR;
      $html .= '                              </select>'.CR;
      $html .= '                           </span>'.CR;
      $html .= '                        </div>'.CR;
      $html .= '                     </td>'.CR;
      $html .= '                     <td>&nbsp;</td>'.CR;
      $html .= '                  </tr>'.CR;
      $html .= '                  <tr>'.CR;
      $html .= '                     <td>&nbsp;</td>'.CR;
      $html .= '                     <td>&nbsp;</td>'.CR;
      $html .= '                     <td>&nbsp;</td>'.CR;
      $html .= '                  </tr>'.CR;
      $html .= '                  <tr>'.CR;
      $html .= '                     <td>Marke des Artikels&nbsp;</td>'.CR;
      $html .= '                     <td><input type="text" class="txt_inp" name="g_marke" id="g_marke" value="'.$this->main->marke.'" title="Synchronisiert mit Hauptartikel" onchange="$(\'#marke\').val($(this).val());" /></td>'.CR;
      $html .= '                     <td>&nbsp;</td>'.CR;
      $html .= '                  </tr>'.CR;
      $html .= '                  <tr>'.CR;
      $html .= '                     <td>GTIN (EAN)</td>'.CR;
      $html .= '                     <td><input type="text" class="txt_inp" name="g_gtin" id="g_gtin" value="'.$this->main->gtin.'" title="Synchronisiert mit Hauptartikel" onchange="$(\'#gtin_parent\').val($(this).val());" /></td>'.CR;
      $html .= '                     <td>&nbsp;</td>'.CR;
      $html .= '                  </tr>'.CR;
      $html .= '                  <tr>'.CR;
      $html .= '                     <td>MPN (Teilenummer)</td>'.CR;
      $html .= '                     <td><input class="txt_inp" style="width:138px;" type="text" name="g_mpn" id="g_mpn" value="'.$this->main->mpn.'" title="Synchronisiert mit Hauptartikel" onchange="$(\'#mpn_parent\').val($(this).val());" /></td>'.CR;
      $html .= '                     <td>&nbsp;</td>'.CR;
      $html .= '                  </tr>'.CR;
      $html .= '               </table>'.CR;
      $html .= '            </div>'.CR;

      $html .= '            <div class="google_right">'.CR;
      $html .= '                <div class="button_unten">'.CR;
      $html .= '                  <a href="'.HELP_LINK.'/o15/google-shopping/" target="_blank" alt="">'.CR;
      $html .= '                      <span class="button button_border txt_but">Hinweise</span>'.CR;
      $html .= '                   </a>'.CR;
      $html .= '                   <div class="txt_bez">&nbsp;</div>'.CR;
      $html .= '                   <p>'.CR;
      $html .= '                   Google nimmt nur Preise inkl. MwSt. an!'.CR;
      $html .= '                   Marke, EAN-Code, Versandgewicht und indiv. Versandkosten müssen ausgefüllt sein!'.CR;
      $html .= '                   <br /><br />Die google-Shopping XML können Sie im Menupunkt TOOLS erstellen und bei google hochladen'.CR;
      $html .= '                  </p>'.CR;
      $html .= '               </div>'.CR;
      $html .= '            </div>'.CR;
      $html .= '            <div class="clear"></div>'.CR;

      return $html;
   }

   private function _rebuildImages() {
      // Als Cronjob eintragen, wenn cronjob.php vorhanden
      if (file_exists(SHOP_PATH.'/cronjob.php')) {

         // Alte Einträge löschen
         $anz = $this->db->querySingleValue("SELECT COUNT(id) FROM #__cronjobs WHERE haendler_id = 0 AND done = 'n'");
         $this->db->query("DELETE FROM #__cron_articles WHERE cronjob_id IN (SELECT id FROM #__cronjobs WHERE haendler_id = 0 AND done = 'n')");
         $this->db->query("UPDATE #__cronjobs SET done = 'y', status = 'Beendet durch neuen Cronjob' WHERE haendler_id = 0 AND done = 'n'");

         // Neuer Eintrag iin cronjob
         $this->db->query("INSERT INTO #__cronjobs SET haendler_id = 0, type = 'rebuild', import_url = '', import_images = 'n', overwrite = 'n'");

         // Rückgabemeldungen
         $msg = '';

         if ((int)$anz > 0) {
            $msg = '<br />Vorhandener Cronjob wurde beendet';
         }

         exit(json_encode(['status' => 'cronjob', 'msg' => 'Cronjob wurde aktiviert'.$msg]));
      }

      // Sonst direkt ausführen
      $statistik = [];
      $start = 0;

      // Wenn Datei existiert, Startwert daraus entnehmen
      if (file_exists(SHOP_PATH.'/tmp/rebuild.txt')) {
         $statistik = json_decode(file_get_contents(SHOP_PATH.'/tmp/rebuild.txt'));
         $start     = $statistik->start;
      }

      else {
         $statistik = (object)['start'     => 0,
                       'status'    => 'running',
                       'anzahl'    => -1,
                       'articles'  => 0,
                       'pictures'  => 0,
                       'failed'    => 0,
                       'msg'       => '',
                       'msg2'      => '',
                       'starttime' =>  0
                      ];

         file_put_contents(SHOP_PATH.'/tmp/rebuild.txt', json_encode($statistik));
      }

      $statistik->starttime = microtime(true);

// Artikel laden
      $data   = $this->db_extern->queryAllObjects("SELECT id, image FROM #__articles_info WHERE id > $start ORDER BY id");
      $anzahl = (is_array($data) ? count($data) : 0);

      // 1. Durchlauf
      if ($statistik->anzahl == -1) {
         $statistik->anzahl = $anzahl;
      }

      // Keine Artikel gefunden
      if ($anzahl == 0) {
         $statistik->status = 'stop';
         $statistik->msg = 'Keine Artikel vorhanden';

         file_put_contents(SHOP_PATH.'/tmp/rebuild.txt', json_encode($statistik));
         return false;
      }

      $dir        = SHOP_PATH.'/'.CONF_PICT_PATH;

      foreach ($data as $v) {
         $artikel_id = $v->id;
         $pic_name   = $v->image;

         if ($pic_name != '' && $pic_name != 'nopic.png' && preg_match('|(http?://)|', $pic_name) === 0) {
            if (file_exists($dir.'original/'.$pic_name.'.jpg')) {
               Helper::makeThumbnails($dir, $pic_name, 'jpg', $artikel_id, 1);
               $statistik->pictures++;
            }

            else {
               $statistik->failed++;
            }
         }

         else {
            $statistik->failed++;
         }

         // Ergebnis in Datei schreiben für Restart (und Ajax-Polling)
         $statistik->articles++;
         $time = number_format(microtime(true) - $statistik->starttime, 3, ',', '');
         $msg  = $statistik->articles.' Artikel von '.$statistik->anzahl.' bearbeitet<br />Es wurden '.$statistik->pictures.' Bilder neu erstellt.<br />Laufzeit: '.$time;

         $statistik->status = 'running';
         $statistik->start  = $artikel_id;
         $statistik->msg    = $msg;

         file_put_contents(SHOP_PATH.'/tmp/rebuild.txt', json_encode($statistik));
      }

      // Ergebnis in Datei schreiben füf Ajax-Polling
      $statistik->status = 'stop';
      $statistik->msg    = $statistik->articles.' Artikel bearbeitet.<br />Es wurden '.$statistik->pictures.' Bilder neu erstellt.<br />Laufzeit: '.$time;

      file_put_contents(SHOP_PATH.'/tmp/rebuild.txt', json_encode($statistik));

      return true;
   }

   // Auswahl Startbild Varianten
   // 13.06.2019
   private function _startbildOption($bildnr) {
      $images = $this->main->images;
      $pics   = (isset($images) ? count($images) + 1 : 0);


      $html  = '<span class="selectbox30">'.CR;
      $html .= '   <select class="art_startbild">'.CR;
      $html .= '      <option value="1"'.($bildnr == 1 ? ' selected="selected"' : '').'>Bild 1</option>'.CR;

      for ($i = 2; $i <= $pics; $i++) {
         $html .= '      <option value="'.$i.'"'.($bildnr == $i ? ' selected="selected"' : '').'>Bild '.$i.'</option>'.CR;
      }

      $html .= '   </select>'.CR;
      $html .= '</span>'.CR;

      return $html;
   }

   // Artikel neu sortieren
   // 01.03.2019
   private function _reorg() {
      $this->db_extern->query("DROP TABLE IF EXISTS #__articles_sort");
      $this->db_extern->query("CREATE TABLE IF NOT EXISTS #__articles_sort LIKE #__articles");
      $this->db_extern->query("INSERT INTO #__articles_sort (SELECT * FROM `#__articles` ORDER BY `parent_id`,`sort`)");
      $this->db_extern->query("TRUNCATE TABLE #__articles");
      $this->db_extern->query("INSERT INTO #__articles (SELECT * FROM `#__articles_sort`)");
/*
      $data = $this->db_extern->queryAllObjects("SELECT DISTINCT parent_id FROM `shop_articles` WHERE sort > 1 Group by parent_id, sort");

      if ($data) {
         foreach($data as $d) {
            $this->db_extern->query("UPDATE `shop_articles` SET sort = sort - 1 WHERE parent_id = $d->parent_id");
         }
      }
*/

      return true;
   }

   // Grundeinheit Gruppennamen und Abkürzungen zurück geben (Array)
   // 19.06.2019
   private function _geNameGrundeinheit($grundeinheit) {
      $name      = '';
      $show_name = '';
      $faktor    = 1;

      switch($grundeinheit) {
         case 'kg'  : $name = 'Artikelgewicht'; $show_name = 'kg'; break;
         case '100g': $name = 'Artikelgewicht'; $show_name = 'g'; $faktor = 100; break;
         case '10g' : $name = 'Artikelgewicht'; $show_name = 'g'; $faktor = 10; break;
         case 'g'   : $name = 'Artikelgewicht'; $show_name = 'g'; break;

         case 'liter': $name = 'Artikelvolumen'; $show_name = 'liter'; break;
         case '100ml': $name = 'Artikelvolumen'; $show_name = 'ml'; $faktor = 100; break;
         case '10ml' : $name = 'Artikelvolumen'; $show_name = 'ml'; $faktor = 10; break;
         case 'ml'   : $name = 'Artikelvolumen'; $show_name = 'ml'; break;

         case 'm' : $name = 'Artikelgröße'; $show_name = 'm'; break;
         case 'dm': $name = 'Artikelgröße'; $show_name = 'mm'; $faktor = 10000; break;
         case 'cm': $name = 'Artikelgröße'; $show_name = 'mm'; $faktor = 100; break;
         case 'mm': $name = 'Artikelgröße'; $show_name = 'mm'; break;

         case 'm2' : $name = 'Artikelfläche'; $show_name = 'm2'; break;
         case 'dm2': $name = 'Artikelfläche'; $show_name = 'mm2'; $faktor = 1000000; break;
         case 'cm2': $name = 'Artikelfläche'; $show_name = 'mm2'; $faktor = 1000; break;
         case 'mm2': $name = 'Artikelfläche'; $show_name = 'mm2'; break;

         case 'm3' : $name = 'Artikelvolumen'; $show_name = 'm3'; break;
         case 'dm3': $name = 'Artikelvolumen'; $show_name = 'mm3'; break;
         case 'cm3': $name = 'Artikelvolumen'; $show_name = 'mm3'; break;
         case 'mm3': $name = 'Artikelvolumen'; $show_name = 'mm3'; break;

         case 'stk':
            $name = 'Artikelanzahl'; $show_name = 'stk'; break;
      }

      // return [$name,  $show_name, $faktor];
      return [$name,  $show_name, 1];
   }

   // Link zum Artikel / FE
   // 30.05.2019
   private function getFeLink($parent_id) {
      if ($parent_id > 0) {
         $data = $this->db_extern->querySingleObject("SELECT a.id AS article_id, name_deu AS name FROM #__articles_info AS i, #__articles AS a WHERE i.id = $parent_id AND i.id = a.parent_id && a.sort = 1");
         return '<a id="kundenansicht" href="'.$this->params->getLink('artikel', $data->article_id, $data->name, '').'" target="_blank" title="Kundenansicht"><span class="pointer far fa-eye"></span></a>';
      }
   }


/* ************************* Module ************************************** */
// Modul Zubehörartikel

   // Im Template verwendet
   // 15.07.2019
   private function _zubehoerLoad($parent_id) {
      $zubehoer = Control::getModuleZubehoer();
      $z_data = $zubehoer->getData($parent_id);

      return $z_data;
   }

   // Im Template verwendet
   // 15.07.2019
   private function _zubehoerLangData($parent_id) {
      $zubehoer = Control::getModuleZubehoer();
      $z_lang = $zubehoer->getLangData($parent_id);

      return $z_lang;
   }

   // Popup Zubehör-Artikel speichern (aus Popup)
   // 15.07.2019
   private function zubehoerSave($parent_id) {
      $letzte = $this->params->postCheckbox('letzte');
      $this->db->query("UPDATE #__firma SET letzte = '$letzte'");
      $this->params->getFirmData();

      $zubehoer = Control::getModuleZubehoer();
      $zubehoer->saveSortData($parent_id);

      $z_data = $zubehoer->getData($parent_id);
      include SHOP_PATH.'/classes/modules/zubehoermodul/zubehoermodul.tpl.php';
      echo json_encode(['status' => 'ok', 'html' => $html]);
      exit;

   }

   // Popup Zubehör-Artikel speichern (aus Popup)
   // 15.07.2019
   private function zubehoerAdd($parent_id, $zubehoer_id) {
      $zubehoer = Control::getModuleZubehoer();

      $test = $zubehoer->saveData($parent_id, $zubehoer_id);

      if ($test) {
         $z_data = $zubehoer->getData($parent_id);
         include SHOP_PATH.'/classes/modules/zubehoermodul/zubehoermodul.tpl.php';
         echo json_encode(['status' => 'ok', 'html' => $html]);
      }

      else {
         echo json_encode(['status' => 'failed', 'msg' => 'Artikel bereits in der Liste']);
      }

      exit;
   }

   // Zubehör-Artikel löschen
   // 15.07.2019
   private function zubehoerDelete($db_id, $parent_id) {
      $zubehoer = Control::getModuleZubehoer();
      $zubehoer->zubehoerDelete($db_id);

      $z_data = $zubehoer->getData($parent_id);
      include SHOP_PATH.'/classes/modules/zubehoermodul/zubehoermodul.tpl.php';

      exit(json_encode(['status' => 'ok', 'html' => $html]));

   }

// Modul Ähnliche Artikel
   // Im Template verwendet
   // 15.07.2019
   private function _aehnlicheLoad($parent_id) {
      $aehnliche = Control::getModuleAehnliche();
      $ae_data = $aehnliche->getData($parent_id);

      return $ae_data;
   }

   // Im Template verwendet
   // 15.07.2019
   private function _aehnlicheLangData($parent_id) {
      $aehnliche = Control::getModuleAehnliche();
      $ae_lang = $aehnliche->getLangData($parent_id);

      return $ae_lang;
   }

   // Popup Ähnliche-Artikel speichern (aus Popup)
   // 15.07.2019
   private function aehnlicheSave($parent_id) {
      $aehnliche = Control::getModuleAehnliche();
      $aehnliche->saveSortData($parent_id);

      $ae_data = $aehnliche->getData($parent_id);
      include SHOP_PATH.'/classes/modules/aehnliche_artikel/aehnliche_artikel.tpl.php';
      echo json_encode(['status' => 'ok', 'html' => $html]);
      exit;

   }

   // Popup Zubehör-Artikel speichern (aus Popup)
  // 15.07.2019
  private function aehnlicheAdd($parent_id, $aehnliche_id) {
      $aehnliche = Control::getModuleAehnliche();

      $test = $aehnliche->saveData($parent_id, $aehnliche_id);

      if ($test) {
         $ae_data = $aehnliche->getData($parent_id);
         include SHOP_PATH.'/classes/modules/aehnliche_artikel/aehnliche_artikel.tpl.php';
         echo json_encode(['status' => 'ok', 'html' => $html]);
      }

      else {
         echo json_encode(['status' => 'failed', 'msg' => 'Artikel bereits in der Liste']);
      }

      exit;
   }

   // Ähnliche-Artikel löschen
   // 15.07.2019
   private function aehnlicheDelete($db_id, $parent_id) {
      $aehnliche = Control::getModuleAehnliche();

      $aehnliche->zubehoerDelete($db_id);

      $ae_data = $aehnliche->getData($parent_id);
      include SHOP_PATH.'/classes/modules/aehnliche_artikel/aehnliche_artikel.tpl.php';
      echo json_encode(['status' => 'ok', 'html' => $html]);
      exit;
   }

// Modul ArtikelTimer
   // Daten für Anzeige Artikel-Details
   // 30.06.2019
   private function _timerLoad($parent_id, $save = false) {
      $data = $this->db_extern->querySingleObject("SELECT timer_check, UNIX_TIMESTAMP(timer_end) AS timer_end, timer_menge, timer_anzeige, timer_art_disable FROM #__articles_info WHERE id = $parent_id");

      // Timer nicht aktuell
      if (!isset($data->timer_end) ||  $data->timer_end == 0) {
         $tag       = '';
         $monat     = '';
         $jahr      = '';
         $stunde    = '';
         $minute    = '';
         $timer_end = 0;

         $data = new \stdClass;
         $data->timer_check = 'n';
         $data->timer_menge = 0;
         $data->timer_art_disable = 'y';
         $data->timer_anzeige = 'n';
      }

      // Timer aktiv
      else {
         $tag         = date('d', $data->timer_end);
         $monat       = date('m', $data->timer_end);
         $jahr        = date('Y', $data->timer_end);
         $stunde      = date('H', $data->timer_end);
         $minute      = date('i', $data->timer_end);
         $timer_end   = $data->timer_end;
      }

      if ($save) {
         return [$tag, $monat, $jahr, $stunde, $minute];
      }

      return ['timer_check' => $data->timer_check, 'timer_menge' => $data->timer_menge, 'timer_end' =>$timer_end, 'tag' => $tag, 'monat' => $monat, 'jahr' => $jahr, 'stunde'=> $stunde, 'minute' => $minute, 'timer_anzeige' => $data->timer_anzeige, 'timer_art_disable' => $data->timer_art_disable];
   }

   // Timer speichern (articles_info)
   // 30.06.2019
   private function timerSave() {
      $parent_id         = $this->params->postInt('parent_id');
      $timer_check       = $this->params->postCheckbox('timer_check');
      $timer_menge       = $this->params->postInt('timer_menge');
      $t_jahr            = $this->params->postInt('t_jahr');
      $t_monat           = $this->params->postInt('t_monat');
      $t_tag             = $this->params->postInt('t_tag');
      $t_stunde          = $this->params->postInt('t_stunde');
      $t_minute          = $this->params->postInt('t_minute');
      $timer_anzeige     = $this->params->postCheckbox('timer_anzeige');
      $timer_art_disable = $this->params->postCheckbox('timer_art_disable');

      // Bei 2-stelliger Eingabe korrigieren
      if ($t_jahr < 100) {
         $t_jahr += 2000;
      }

      $timer_end = $t_jahr.'-'.$t_monat.'-'.$t_tag.' '.$t_stunde.':'.$t_minute.':59';

      if (!checkdate($t_monat, $t_tag, $t_jahr || $t_stunde < 0 || $t_stunde > 23 || $t_minute < 0 || $t_minute > 59)) {
         exit(json_encode(['status' => 'failed', 'msg' => 'Datum / Zeit ungültig !'.$timer_end]));
      }

      $timer_end = $t_jahr.'-'.$t_monat.'-'.$t_tag.' '.$t_stunde.':'.$t_minute.':59';
      $this->db_extern->query("UPDATE #__articles_info SET timer_check = '$timer_check', timer_end = '$timer_end', timer_menge = $timer_menge, timer_anzeige = '$timer_anzeige', timer_art_disable='$timer_art_disable' WHERE id = $parent_id");

      exit(json_encode(['status'      => 'ok',
                        'msg'         => 'Artikel-Restzeit gespeichert'.($timer_check != 'y' ? "<br /><br /><div style='color:#cc0000;'>Nicht Aktiviert!</div>" : ''),
                        'timer_check' => $timer_check,
                        't_jahr'      => sprintf('%04d', $t_jahr),
                        't_monat'     => sprintf('%02d', $t_monat),
                        't_tag'       => sprintf('%02d', $t_tag),
                        't_stunde'    => sprintf('%02d', $t_stunde),
                        't_minute'    => sprintf('%02d', $t_minute),
                        't_menge'     => $timer_menge,
                        'timer_end'   => strtotime($timer_end),
                        'info'        => $this->db_extern->last_sql
                       ]));
   }


// Modul foto
   // Artikel aus Upload-Verzeichnis erstellen / Von Tools aufgerufen
   private function _saveFotoartikel($haendler_id = 0) {
      $foto_art_id      = $this->params->postCheckbox('foto_art_id');      // 'y' ->Art-Id als Art-Nr, 'n' -> foto_artnr verwenden
      $foto_artnr       = $this->params->postString('foto_artnr');
      $foto_artname     = $this->params->postString('foto_artname');

      $foto_keywords_on = $this->params->postCheckbox('foto_keywords_on');    // 'y' -> aus Fotodaten, 'n' -> foto_keywords verwenden
      $foto_keywords    = $this->params->postString('foto_keywords');
      $foto_desc        = $this->params->postString('foto_desc');

      $foto_cat         = $this->params->postInt('foto_cat');
      $dir              = $this->params->postString('foto_dir');
      $foto_price       = $this->params->postFloat('foto_price');

      $pfad             = SHOP_PATH.'/downloads'.$dir;
      $file_arr         = [];
      $cron             = false;

      // Bei Cronjob
      if (is_file(SHOP_PATH.'/cronjob.php')) {
         $cron = true;

         // Tabelle anlegen, falls nicht existiert
         $this->db->query("CREATE TABLE IF NOT EXISTS #__articles_foto_tmp (
                              `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                              `art_id` INT UNSIGNED NOT NULL,
                              `org_file` VARCHAR(1024) NOT NULL,
                              `pic_01` VARCHAR(32) NOT NULL,
                              PRIMARY KEY (`id`)
                           ) ENGINE =MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;");
      }

      // Da Brutto-Preis angezeigt wird
      $netto = $foto_price;

      if ($this->params->firma['tax_active'] == 'y') {
         $netto = $foto_price / (1 + (float)$this->params->firma['tax1'] / 100);
      }

      // Verzeichnis einlesen
      if (($dh = opendir($pfad))) {
          while (false !== ($file = readdir($dh))) {
              if ($file != "." && $file != ".." && is_file($pfad.'/'.$file)) {
                 if (substr(strtolower($file), -4) === '.jpg' || substr(strtolower($file), -5) === '.jpeg') {
                     $file_arr[] = '/'.$file;
                 }
              }
          }

          closedir($dh);
      }

      $anzahl = (is_array($file_arr) ? count($file_arr) : 0);

      // Keine Bilder vorhanden
      if (!$anzahl) {
         echo json_encode(['status' => 'stop', 'msg' => "Keine Bilder gefunden"]);
         exit;
      }

//      echo json_encode(['status' => 'start', 'msg' => "$anzahl Fotos werden verarbeitet"]);

      $fotomodule = Control::getModuleFoto();
      $max_set1   = (int)$this->db_extern->querySingleValue("SELECT MAX(foto_set) FROM #__articles_info");
      $max_set2   = $fotomodule->getMaxSet();
      $foto_set   = max($max_set1, $max_set2) + 1;

//      $gshop      = Control::getImportExport();
      $c          = 0;

      foreach ($file_arr as $v) {
         $c++;

         $name_deu     = $foto_artname;

         $metatitle    = $foto_artname;
         $metadesc     = $foto_artname.' '.$foto_keywords;
         $metakey      = $foto_keywords;
         $info         = '';

         // Feedback / Datei für AJAX-Poll aktualisieren / Status
         $msg = "$c von $anzahl Dateien verarbeitet";
         $fh  = fopen(SHOP_PATH.'/tmp/fotos.txt', 'w');
         fwrite($fh, json_encode(['status' => 'ok', 'msg' => $msg]));
         fclose($fh);

         list($size_x, $size_y) = getimagesize($pfad.$v, $info);

         if ($foto_keywords_on == 'y') {
            // IPTC-Daten aus Foto lesen
            if (isset($info['APP13'])) {
               // https://www.php.net/manual/de/function.iptcparse.php
               $iptc = iptcparse($info['APP13']);

               $metatitle = (isset($iptc['2#005']) ? implode(' ', $iptc['2#005']) : $metatitle);   // Document titel
               $metadesc  = (isset($iptc['2#120']) ? implode(' ', $iptc['2#120']) : $metadesc);    // Caption / Description
               $metakey   = (isset($iptc['2#025']) ? implode(' ', $iptc['2#025']) : $metakey);     // Keywords

               if (!isset($iptc['2#025'][1])) {
                  $metakey = str_replace(['*', ';', ':'], ',', $metakey);
               }

               if ($foto_art_id == 'y' && $metatitle != '') {
                  $name_deu = $metatitle;
               }
            }
         }

         $desc_deu     = ($foto_desc != '' ? '<p>'.nl2br($foto_desc).'</p>' : $foto_keywords);

         // 26.02.2019: Widerruf von 1 -> 5 (Downloadartikel)
         $this->db->query("INSERT INTO #__articles_info SET
                               childs          = 1,
                               name_deu        = '$name_deu',
                               desc_deu        = '$desc_deu',
                               steuersatz      = 1,
                               widerruf        = 5,
                               lieferfrist     = '1',
                               ab_check        = 'y',
                               artikelgruppe   = 0,
                               is_foto         = 'y',
                               foto            = '".$this->db->escape($dir.$v)."',
                               foto_set        = $foto_set,
                               org_set         = $foto_set,
                               foto_size_x     = $size_x,
                               foto_size_y     = $size_y");

         $newId       = $this->db->getNewId();
         $bild_name   = $newId.'_01';
         $art_nr      = ($foto_art_id == 'y' ? $newId : $foto_artnr.'-'.$c);


         $this->db->query("INSERT INTO #__articles SET parent_id = $newId, sort = 1, art_nr = '$art_nr', online = 'y', netto = '$netto', menge = 999, gtin = '$metakey'");
         $this->db->query("INSERT INTO #__article_to_cats SET parent_id = $newId, cat_id = $foto_cat, sort = 0");
         $this->db->query("INSERT INTO #__articles_seo SET
                                          parent_id = $newId,
                                          lang      = 'deu',
                                          metaauto  = 'n',
                                          metatitle = '".$metatitle."',
                                          metadesc  = '".$metadesc."',
                                          metakey   = '".$metakey."'");

         // Bildbearbeitung sofort
         if (!$cron) {
            // Alle Bilder aus Original erstellen
            Helper::makeFotoThumb($pfad.$v, $bild_name);
            $this->db->query("UPDATE #__articles_info SET image = '$bild_name' WHERE id = $newId");
         }

         // Bildbearbeitung durch Cronjob
         else {
            $this->db->query("INSERT INTO #__articles_foto_tmp SET art_id = $newId, org_file = '".$this->db->escape($pfad.$v)."', pic_01 = '$bild_name'");
         }
      }

      // Datei für AJAX-Poll aktualisieren / ENDE
      $fh = fopen(SHOP_PATH.'/tmp/fotos.txt', 'w');

      if (!$cron) {
         fwrite($fh, json_encode(['status' => 'stop', 'msg' => $c.' Fotos/Artikel wurden hinzugefügt']));
      }

      // Laufenden Cronjob beenden
      else {
         $this->db->query("UPDATE #__cronjobs SET done = 'y', status = 'Beendet durch neuen Cronjob' WHERE haendler_id = $haendler_id AND done = 'n'");
         $this->db->query("INSERT INTO #__cronjobs SET type = 'foto', haendler_id = $haendler_id, import_url = '', import_images = 'n', overwrite = 'n', statistik = '".json_encode([])."'");
         fwrite($fh, json_encode(['status' => 'stop', 'msg' => $c.' Artikel wurden hinzugefügt<br />Bilder werden per Cronjob erstellt.']));
      }
      fclose($fh);
   }

   // Fotoartikel löschen, von denen kein Original vorhanden ist
   // 01.04.2010
   private function _fotoClean() {
      $pfad    = SHOP_PATH.'/downloads';
      $fotos   = $this->db->queryAllObjects("SELECT id, foto FROM #__articles_info WHERE is_foto = 'y'");
      $counter = 0;

      for ($i = 0; $i < (is_array($fotos) ? count($fotos) :0); $i++) {
         if (!file_exists($pfad.''.$fotos[$i]->foto)) {
            $this->db->query("DELETE FROM #__articles_info WHERE id = ".$fotos[$i]->id);
            $this->db->query("DELETE FROM #__articles WHERE parent_id = ".$fotos[$i]->id);

            @unlink(SHOP_PATH.'/'.CONF_PICT_PATH.$fotos[$i]->id.'_01.jpg');
            @unlink(SHOP_PATH.'/'.CONF_PICT_PATH.$fotos[$i]->id.'_01_tn.jpg');
            @unlink(SHOP_PATH.'/'.CONF_PICT_PATH.$fotos[$i]->id.'_01_td.jpg');
            @unlink(SHOP_PATH.'/'.CONF_PICT_PATH.$fotos[$i]->id.'_01_tp.jpg');
            @unlink(SHOP_PATH.'/'.CONF_PICT_PATH.'original/'.$fotos[$i]->id.'_01.jpg');
            $counter++;
         }
      }

      if ($counter == 0) {
         echo json_encode(['status' => 'ok', 'msg' => 'Keine Änderungen Fotos/Artikel']);
      }
      else {
         echo json_encode(['status' => 'ok', 'msg' => $counter.' Artikel wurden gelöscht']);
      }
   }

   // Edit / Ausgaben für Fotoartikel
   private function articleDetailFoto ($art_id, $parent, $online, $angebot, $angebot_active, $steuersatz, $menge, $art_nr, $namen, $foto_set, $org_set) {
      $html          = '';
      $preis_mode    = 2;
      $preise_global = $this->db->queryAllObjects("SELECT price FROM #__foto_data WHERE foto_set = 1 ORDER BY sort");
      $preise_set    = $this->db->queryAllObjects("SELECT price FROM #__foto_data WHERE foto_set = $org_set ORDER BY sort");
      $preise_bild;

      // Set Preis = Globaler Preis, wenn nicht vorhanden
      if (!is_array($preise_set) || count($preise_set) == 0) {
         $preise_set = $preise_global;
         $preis_mode = 1;
      }

      if ($foto_set != $org_set) {
         $preis_mode = 3;
         $preise_bild = $this->db->queryAllObjects("SELECT price FROM #__foto_data WHERE foto_set = $foto_set ORDER BY sort");

         if (count($preise_bild) == 0) {
            $preise_bild = $preise_set;
         }
      }

      else {
         $preise_bild = $preise_set;
      }

      $script = 'var foto_preis = new Array(); ';

      for ($i = 0; $i < 7; $i ++) {
         $script .= 'foto_preis['.$i.'] = new Array('.$preise_global[$i]->price.', '.$preise_set[$i]->price.', '.$preise_bild[$i]->price.'); ';
      }

      $script .= '$(function() { Artikel.fotoMode('.$preis_mode.'); }); ';

      $preise       = null;
      $data         = null;
      $module_foto  = Control::getModuleFoto();
      $module_foto->getData($data, $preise, $art_id);
      $akt_foto_set = $module_foto->foto_set;

      $foto_data = [];
      $max_foto = max((int)$data->foto_size_x, (int)$data->foto_size_y);
      $faktor = (int)$data->foto_size_x / $max_foto;

      $articles_set = (int)$this->db->querySingleValue("SELECT count(id) FROM #__articles_info WHERE foto_set = $foto_set");
      $module_foto = Control::getModuleFoto();
      $module_foto->getData($data, $preise, $art_id);
      $akt_foto_set = $module_foto->foto_set;

      $foto_data = [];
      $max_foto = max((int)$data->foto_size_x, (int)$data->foto_size_y);
      $faktor = (int)$data->foto_size_x / $max_foto;

      for ($i = 0; $i < count($preise); $i++) {
         $foto_x = 0;
         $foto_y = 0;
         $size = (int)$preise[$i]->size;

         // Landscape
         if ((int)$data->foto_size_x > (int)$data->foto_size_y) {
            $foto_x = $size;
            $foto_y = floor((int)$data->foto_size_y * $size / (int)$data->foto_size_x);
         }

         // Portait
         else {
            $foto_x = floor((int)$data->foto_size_x * $size / (int)$data->foto_size_y);
            $foto_y = $size;
         }

         $too_big = false;
         if ($size > $max_foto) {
            if (strstr($preise[$i]->name, '[MAX]')) {
               $foto_x = $data->foto_size_x;
               $foto_y = $data->foto_size_y;
            }
            else {
               $too_big = true;
            }
         }

         $preis = $preise_global[$i]->price;
         if ($preis_mode == 2) {
            $preis = $preise_set[$i]->price;
         }
         if ($preis_mode == 3) {
            $preis = $preise_bild[$i]->price;
         }

         $foto_data[$i] = [$preise[$i]->name, $foto_x.' x '.$foto_y, (float)$preis, $preise[$i]->foto_set, $preise[$i]->sort, $too_big];
      }

      $angebot = 0;
      $id = $art_id;

      $html .= "<input type='hidden' name='parent_id' id='parent_id' value='$parent' />\n";
      $html .= "<input type='hidden' name='is_foto' id='is_foto' value='y' />\n";
      $html .= "<input type='hidden' name='foto_set' id='foto_set' value='$foto_set' />\n";
      $html .= "<input type='hidden' name='org_set' id='org_set' value='$org_set' />\n";
      $html .= "<input type='hidden' name='preis_mode' id='preis_mode' value='".$preis_mode."' />\n";
      $html .= "<input type='hidden' name='old_mode' id='old_mode' value='".$preis_mode."' />\n";
      $html .= "<input type='hidden' name='merkmal1_1' id='merkmal1_1' value='0' />\n";
      $html .= "<input type='hidden' name='merkmal2_1' id='merkmal2_1' value='0' />\n";
      $html .= "<input type='hidden' name='wert1_$id' id='wert1_$id' value='0' />\n";
      $html .= "<input type='hidden' name='wert2_$id' id='wert2_$id' value='0' />\n";
      $html .= "<input type='hidden' name='seo_lang' id='seo_lang' value='".$this->params->selected_lang."' />\n";

      for ($i = 0; $i < count($foto_data); $i++) {
         $preis_name = $foto_data[$i][0];
         $size = $foto_data[$i][1];
         $netto = $foto_data[$i][2];
         $too_big = $foto_data[$i][5];


         // !.Artikel ,it Art-Nr. Name
         if ($i == 0) {
            $html .= '<div class="article_main block_start"'.($too_big ? ' style="color:#cccccc"' : '').'>'.CR;
            $html .= '   <div class="xleft foto_first">'.CR;
            $html .= '      <span class="xartnr_foto foto_first">'.CR;
            $html .= '         <input id="art_nr" class="txt_inp" type="text" value="'.$art_nr.'" />'.CR;
            $html .= '      </span>'.CR;
            $html .= '      <span class="xname_foto foto_first">'.CR;
            $html .= '         <input id="artikelname"class="txt_inp" type="text" value="'.$namen.'" />'.CR;
            $html .= '      </span>'.CR;
            $html .= '   </div>'.CR;
         }

         else {
            $html .= '<div class="article_sub block_start"'.($too_big ? ' style="color:#cccccc"' : '').'>'.CR;
            $html .= '   <div class="xleft">&nbsp;</div>'.CR;
         }

         $html .= '   <div class="xcenter">'.CR;
         $html .= '      <div class="xfoto_groesse art-merk1"'.($too_big ? ' style="color:#cccccc"' : '').'>'.$preis_name.'</div>'.CR;
         $html .= '      <div class="xfoto_pixel art-merk2"'.($too_big ? ' style="color:#cccccc"' : '').'>'.$size.'</div>'.CR;
         $html .= '      <div class="xnetto art-netto">'.CR;
         $html .= '         <input class="txt_inp foto_input netto_show right" type="text" value="'.number_format($netto, 2, ',', '.').'" onchange="Artikel.compute(this, \'netto\', '.$i.');" />'.CR;
         $html .= '      </div>'.CR;
         $html .= '      <input type="hidden" class="art_netto netto" id="netto_real_'.$i.'" value="'.$netto.'" />'.CR;
         $html .= '      <div style="visibility:hidden;"><input class="xangebot" type="text" value="'.str_replace('.', ',', (sprintf('%01.2f',$angebot))).'" onchange="Artikel.compute(this, \'angebot\', '.$i.');" /></div>'.CR;

         if ($angebot_active == 'y') {
            $rechnen = $angebot;
         }

         else {
            $rechnen = $netto;
         }

         if ($this->params->firma['kleingewerbe'] == 'y' || $this->params->firma['tax_active'] == 'n') {
            $html .= '      <div class="xbrutto">keine MwSt</div>'.CR;
         }

         else {
            $html .= '      <div class="xbrutto">'.CR;
            $html .= '         <input type="text" class="brutto_show right txt_inp foto_input brutto1" value="'.number_format($rechnen * (1 + $this->params->firma['tax'.$data->steuersatz] / 100), 2 ,'.', '.').'" onchange="Artikel.compute(this, \'brutto\');" />'.CR;
            $html .= '     </div>'.CR;
         }

         $html .= '   </div>'.CR;

         $html .= '   <div class="xright foto_first">'.CR;

         if ($i == 0) {
            $html .= '      <div class="x_menge art-menge">'.CR;
            $html .= '         <input class="txt_inp right" type="text" id="foto_menge" name="foto_mengemenge" value="'.(int)$menge.'" />'.CR;
            $html .= '      </div>'.CR;
         }
         else {
            $html .= '      <div class="xmenge art-menge" style="visibility:hidden;">'.CR;
            $html .= '         <input disabled="disabled" class="txt_inp" type="text" id="menge_'.$id.'" name="menge_'.$id.'" value="'.$menge.'" />'.CR;
            $html .= '      </div>'.CR;
         }

         $html .= '      <input type="hidden" id="steuer_'.$parent.'" value="'.$this->params->firma['tax'.$data->steuersatz].'" />'.CR;
         $html .= '   </div>'.CR;
         $html .= '</div>'.CR;
      }

      $html .= '<div style="margin:14px 0 0 526px;">';
      $html .= '   <span style="display:inline-block; width:50px;" class="txt_bez">Preise</span>';
      $html .= '   <span style="display:inline-block; position:relative; top:2px;"><input type="radio" class="newdesign" id="foto_mode1" name="foto_mode" value="1"'.($preis_mode == 1 ? ' checked="checked"' : '').' onclick="Artikel.fotoMode(1)" /><label for="foto_mode1"></label></span>';
      $html .= '   <span style="display:inline-block; width:50px;">global</span>';
      $html .= '   <span style="display:inline-block; position:relative; top:2px;"><input type="radio" class="newdesign" id="foto_mode2" name="foto_mode" value="2"'.($preis_mode == 2 ? ' checked="checked"' : '').'  onclick="Artikel.fotoMode(2)" /><label for="foto_mode2"></span></label>';
      $html .= '   <span style="display:inline-block; width:50px;">Set</span>';
      $html .= '   <span style="display:inline-block; position:relative; top:2px;"><input type="radio" class="newdesign" id="foto_mode3" name="foto_mode" value="3"'.($preis_mode == 3 ? ' checked="checked"' : '').'  onclick="Artikel.fotoMode(3)" /><label for="foto_mode3"></label></span>';
      $html .= '   <span style="display:inline-block; width:80px;">dieses Bild</span>';
      $html .= '</div>';

      $this->foto_script = '<script>'.$script.'</script>';

      return $html;
   }


// Modul mixer_artikel Mixer-Artikel laden
   private function _mixerLoad($parent_id) {
      $mixer      = Control::getModuleMixerArtikel();
      $mixer_data = $mixer->getData($parent_id);

      return $mixer_data;
   }

   private function _mixerAdd($parent_id, $article_id) {
      $mixer = Control::getModuleMixerArtikel();
      $test  = $mixer->saveData($parent_id, $article_id);

      // Artikel wurde hinzugefügt
      if ($test) {
         $mixer_data = $mixer->getData($parent_id);

         include SHOP_PATH.'/classes/modules/mixer_artikel/mixer_articles.tpl.php';
         echo json_encode(['status' => 'ok', 'html' => $html]);
      }

      // Artikel bereits in der Liste
      else {
         echo json_encode(['status' => 'failed', 'msg' => 'Artikel bereits in der Liste']);
      }

      exit;
   }

   private function _mixerDelete($db_id, $parent_id) {
      $mixer = Control::getModuleMixerArtikel();

      $mixer->mixerDelete($db_id);

      $mixer_data = $mixer->getData($parent_id);
      include SHOP_PATH.'/classes/modules/mixer_artikel/mixer_articles.tpl.php';
      echo json_encode(['status' => 'ok', 'html' => $html]);
      exit;
   }

   private function _mixerSave($parent_id) {
      $mixer = Control::getModuleMixerArtikel();
      // Daten speichern
      $mixer->saveSortData($parent_id);

      // Daten lesen
      $mixer_data = $mixer->getData($parent_id);

      // HTML aus Daten generiren
      include SHOP_PATH.'/classes/modules/mixer_artikel/mixer_articles.tpl.php';

      exit(json_encode(['status' => 'ok', 'html' => $html]));
   }

// Modul naehrwerte
   private function naehrwerte($parent_id) {
      $nw = $this->db_extern->querySingleObject("SELECT * FROM #__articles_naehrwerte WHERE parent_id = $parent_id");

      if (!$nw) {
         $nw = new \stdClass();
         $nw->brennwert = 0;
         $nw->fett      = 0;
         $nw->f_saeure  = 0;
         $nw->k_hydrate = 0;
         $nw->zucker    = 0;
         $nw->ballast   = 0;
         $nw->eiweiss   = 0;
         $nw->salz      = 0;
      }

      return $nw;
   }

   private function zutaten($parent_id) {
      $zutaten = [];

      foreach ($this->params->langs as $lang) {
         $zutaten[$lang] = [];

         for ($i = 1; $i <= 12; $i++) {
            $zutaten[$lang]['zutat_'.$i] = '';
         }

         $zutaten[$lang]['zutat_allergiker'] = '';

         $nw = $this->db_extern->queryAllObjects("SELECT * FROM #__articles_zutaten WHERE parent_id = $parent_id AND lang = '$lang' ORDER BY title");

         if ($nw) {
            for ($i = 0; $i < 12; $i++) {
               if (isset($nw[$i])) {
                  $zutaten[$lang][$nw[$i]->title] = $nw[$i]->value;
               }
            }

            if (isset($nw[12])) {
               $zutaten[$lang][$nw[12]->title] = $nw[12]->value;
            }
         }
      }

      return $zutaten;
   }

   private function _saveNaehrwerte() {
      $parent_id        = $this->params->postInt('parent_id');
      $naehrwerte_check = $this->params->postCheckbox('naehrwerte_check');
      $brennwert        = $this->params->postFloat('brennwert');
      $fett             = $this->params->postFloat('fett');
      $f_saeure         = $this->params->postFloat('f_saeure');
      $k_hydrate        = $this->params->postFloat('k_hydrate');
      $zucker           = $this->params->postFloat('zucker');
      $ballast          = $this->params->postFloat('ballast');
      $eiweiss          = $this->params->postFloat('eiweiss');
      $salz             = $this->params->postFloat('salz');

      $test = $this->db_extern->querySingleValue("SELECT parent_id FROM #__articles_naehrwerte WHERE parent_id = $parent_id");

      if ($test) {
         $this->db_extern->query("UPDATE #__articles_naehrwerte SET
                              brennwert = '$brennwert',
                              fett      = '$fett',
                              f_saeure  = '$f_saeure',
                              k_hydrate = '$k_hydrate',
                              zucker    = '$zucker',
                              ballast   = '$ballast',
                              eiweiss   = '$eiweiss',
                              salz      = '$salz'
                           WHERE parent_id = $parent_id");
      }

      else {
         $this->db_extern->query("INSERT INTO  #__articles_naehrwerte SET
                              parent_id = $parent_id,
                              brennwert = '$brennwert',
                              fett      = '$fett',
                              f_saeure  ='$f_saeure',
                              k_hydrate = '$k_hydrate',
                              zucker    = '$zucker',
                              ballast   = '$ballast',
                              eiweiss   = '$eiweiss',
                              salz      = '$salz'");

      }

      $this->db_extern->query("UPDATE #__articles_info SET naehrwerte_check = '$naehrwerte_check' WHERE id = $parent_id");
   }

   private function _saveZutaten() {
      $parent_id  = $this->params->postInt('parent_id');

      foreach ($this->params->langs as $lang) {
         for ($i = 1; $i <= 12; $i++) {
            $name  = 'zutat_'.$lang.'_'.$i;
            $value = $this->params->postString($name);

            $this->db_extern->query("INSERT INTO #__articles_zutaten SET parent_id = $parent_id, title = 'zutat_$i', lang = '$lang', value = '$value'
                                ON DUPLICATE KEY UPDATE value = '$value'");
         }

         // Hinweis für Allergiker
         $name = 'zutat_'.$lang.'_allergiker';
         $value = $this->params->postString($name);

         $this->db_extern->query("INSERT INTO #__articles_zutaten SET parent_id = $parent_id, title = 'zutat_allergiker', lang = '$lang', value = '$value'
                             ON DUPLICATE KEY UPDATE value = '$value'");
      }
   }


// Modul Portal
   private function _getHaendlerInfo($article_id, $haendler_id = 0) {
      $user_id = 0;

      if ($article_id == 0 && $haendler_id > 0) {
         $user_id = $haendler_id;
      }

      else {
         $user_id = $this->db->querySingleValue("SELECT haendler_id FROM #__articles_info WHERE id = $article_id");

         if ($user_id === null) {
            $user_id = 0;
         }
      }

      return $this->db->querySingleObject("SELECT u.*, h.* FROM #__users AS u, #__haendler AS h WHERE u.id = $user_id AND u.id = h.user_id");
   }

   private function _haendlerList($haendler_id, $callback) {
      $html = '<select id="haendler_id" name="haendler_id" style="border: 1px solid #d7d7d7; width:120px; height:20px;"'." onchange='".$callback."'>";
      $html .= '<option value="0"'.($haendler_id == 0 ? ' selected="selected"' : '').'>alle</option>';

      $haendler = $this->db->queryAllObjects("SELECT h.user_id, h.haendler_nr, u.firma, h.website FROM #__haendler AS h, #__users AS u  WHERE h.user_id = u.id AND u.gesperrt != 'y'");

      for ($i = 0; $i < (is_array($haendler) ? count($haendler) : 0); $i++) {
         $html .= '<option value="'.$haendler[$i]->user_id.'" title="'.$haendler[$i]->firma.'"'.($haendler_id == (int)$haendler[$i]->user_id ? ' selected="selected"' : '').'>'.$haendler[$i]->haendler_nr.' '.str_replace(['http://', 'https://'], '', $haendler[$i]->website).'</option>';
      }
      $html .= '</select>';
      return $html;
   }

   private function _getHaendler($haendler_id) {
      $data = $this->db->querySingleObject("SELECT haendler_nr, website FROM #__haendler WHERE user_id = $haendler_id");
      return '<span title="'.str_replace(['http://', 'https://'], '', $data->website).'">Händler: '.$data->haendler_nr.'</span>';
   }

   // Alle Artikel eines Händlers löschen
   public function articleDeleteHaendler($haendler_id) {
      if ($haendler_id > 0) {
         $data = $this->db->queryAllObjects("SELECT a.id FROM #__articles AS a, #__articles_info AS i WHERE i.haendler_id = $haendler_id AND a.parent_id = i.id AND a.sort = 1");

         if ($data) {
            foreach ($data as $article_id) {
               $this->_articleDelete($article_id->id);
            }
         }

         // Artikel aus Warenkorb löschen
         $this->db->query("DELETE FROM #__warenkorb WHERE haendler_id = $haendler_id");
      }
   }

   //
   public function sitemap($oldstatus = '') {
      $status = $this->params->firma['sitemap_articles'];
      $lang   = $this->params->default_lang;
      $html   = '';
      $xml    = '';

      if ($oldstatus == $status) {
         return;
      }

      if ($status == 'y') {
         $articles = $this->db->queryAllObjects("SELECT a.id, i.name_$lang AS name,
                                                   (select metatitle from shop_articles_seo where parent_id = a.parent_id and lang = '$lang') as titletag
                                                   FROM #__articles_info AS i
                                                LEFT JOIN #__articles AS a
                                                   ON i.id = a.parent_id
                                                WHERE a.sort = 1 AND a.online = 'y'
                                                ORDER BY i.sortierung, i.id");

         if ($articles) {
            $datum = date('Y-m-d');

            foreach ($articles as $a) {
               $html .= '<div class="article">'.CR;
               $html .= '   <a href="'.$this->params->getLink('artikel', $a->id, $a->name).'"><span class="fliesstext text_normal ellipsis"'.($this->params->firma['sitemap_title'] == 'y' ? ' title="'.$a->titletag.'"' : '').'>'.$a->name.'&nbsp;</span></a>'.CR;
               $html  .= '</div>';

               $xml  .= '   <url>'."\n";
               $xml  .= '      <loc>'.$this->params->getLink('artikel', $a->id, $a->name).'</loc>'."\n";
               $xml  .= '      <lastmod>'.$datum.'</lastmod>'."\n";
               $xml  .= '      <changefreq>weekly</changefreq>'."\n";
               $xml  .= '      <priority>0.8</priority>'."\n";
               $xml  .= '   </url>'."\n";
            }

            \file_put_contents(SHOP_PATH.'/sitemap_articles.html', $html);
            \file_put_contents(SHOP_PATH.'/sitemap_articles.xml', $xml);
         }
      }

      else {
         @unlink(SHOP_PATH.'/sitemap_articles.html');
         @unlink(SHOP_PATH.'/sitemap_articles.xml');
      }

      $sitemap = Control::getSitemap();
      $sitemap->sitemapXml();
   }
}
