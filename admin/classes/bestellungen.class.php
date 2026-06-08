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
require_once '../classes/base/bestellungen_base.class.php';

class KANPAICLASSIC_bestellungen extends KANPAICLASSIC_bestellungenBASE
{
   public  $user        = [];
   public  $user_id     = 0;
   private $haendler_id = 0;

   private $ausland     = false;
   private $schweiz     = false;
   private $suchanzahl  = -1;

   function __construct() {
      parent::__construct();
      if (!isset($_SESSION['bestell_sort'])) {
//         $_SESSION['bestell_sort'] = 2;
         $_SESSION['bestell_sort'] = Helper::getData('bestell_sort', '2');
      }

      // Sortierung auf/absteigend
      if (!isset($_SESSION['bestell_dir'])) {
//         $_SESSION['bestell_dir'] = 'desc';
         $_SESSION['bestell_dir'] = Helper::getData('bestell_dir', 'desc');
      }

      $this->params->selected_lang = 'deu';

      $this->berechnung = Control::getBerechnungen();
   }

   public function getContent() {
      $this->user_id = $this->params->postInt('user_id');

      switch ($this->params->func) {
         // Bestellung-Liste

         // Liste als JSON (Inhalt / Pager)
         // 05.12.2018
         case 'liste':
            // komplette Seite (Inhalt / Pager) neu laden (AJAX)
            echo json_encode(['status' => 'ok', 'inhalt' => $this->liste(), 'pager' => $this->getCounter()]);
            break;

         // Anzahl Rechnungen / Seite ändern
         // 05.12.2018
         case 'count':
            // Anzahl Artikel pro Seite ändern / Ajax - Seite wird von Ajax aktualisiert
            $_SESSION['bestell_limit'] = $this->params->postInt('count');
            $_SESSION['bestell_seite'] = 0;
            echo json_encode(['status' => 'ok']);
            break;

         // Seite ändern
         // 05.12.2018
         case 'seite':
            // Seite Nr. ausgeben
            $_SESSION['bestell_seite'] = $this->params->postInt('seite');
            echo json_encode(['status' => 'ok']);
            break;

         // Rechnung suchen
         // 20.02.2019
         case 'find':
            // gewählte Bestellung(en) ausgeben
            $all = $this->params->postInt('all');

            if ($all == 0) {
               $search = $this->params->postInt('search');
            }

            else {
               $search = $this->params->postString('search');
            }

            echo json_encode(['status' => 'ok', 'inhalt' => $this->liste(0, $search, $all), 'pager' => $this->getCounter()]);
            break;

         // Bestellliste sortieren (nur Änderung Sortierung, keine Ausgabe)
         // 05.12.2018
         case 'sort':
            $_SESSION['bestell_seite'] = 0;
            $this->_sort();
            break;

         // Rechnung löschen
         // 20.02.2019
         case 'delete':
            // Bestellung löschen
            $this->_delete();
            echo json_encode(['status' => 'ok']);
            break;

         // Livesuche - nicht verwendet
         case 'suchen':
            // Vorschlagsliste "suchen"
            echo json_encode(['status' => 1, 'inhalt' => $this->suchen()]);
            break;

         // PDF von Liste aus erstellen
         case 'pdfdirect':
            $re_id = $this->params->postInt('id');
            $this->_change(4, 'pdf');

            $pdf_id = $this->params->postInt('pdf');
            $pdf    = Control::getPdf();
            $pdf->makePdf($re_id, 'rechnung');

            $this->im_export->exportBuchungenAuto($re_id);
            exit;

            break;

         // Detailseite anzeigen, aufgerufen von Liste
         // 28.02.2019
         case 'detail':
            $id         = (isset($this->params->add_params[0]) ? (int)$this->params->add_params[0] : 0);
            $pdf_script = '';

            if ($id == 0 && isset($_SESSION['new_best'])) {
               die('Bestellungen Zeile 153: Anpassen');
               $id = (int)$_SESSION['new_best'];
               unset($_SESSION['new_best']);
            }

            // Neue Bestellung
            if ($id == 0) {
               $this->_change(1, 'neu');
            }

            // Edit-Seite anzeigen
            $test = $this->getDetailBestellung($id, 'admin');

            if (!$test) {
               exit(header('Location: '.ADMIN_URL_IDX.'/bestellungen'));
            }

            $this->getDetailArtikel($id, 'admin');

            $data =       $this->dataDetails;
            $data->role = $this->db->querySingleValue("SELECT role FROM #__users WHERE id = $data->user_id");
            $data2 =      $this->dataArtikel;

            // Artikel-Daten
            if ($data2) {
               foreach ($data2 as $d) {
                  $image = '';
                  $d->pict = '';

                  $info    = $this->db_extern->querySingleObject("SELECT a.startbild, i.image, i.id FROM #__articles_info AS i, #__articles AS a WHERE a.id = $d->artikel_id AND a.parent_id = i.id");

                  // Artikel könnte gelöscht sein
                  if ($info) {
                     $d->pict = $info->image;

                     if ((int)$info->startbild > 1) {
                        $sort  = (int)$info->startbild - 1;
                        $image = $this->db_extern->querySingleValue("SELECT image FROM #__articles_images WHERE parent_id = $info->id AND sort = $sort");

                        if ($image) {
                           $d->pict = $image;
                        }
                     }
                  }
               }
            }

            include ADMIN_PATH.'/templates/bestellung_detail.tpl.php';
            break;

         // Änderungen Detailseite speichern (speichern / Buttons (1/2/3) / Storno
         // 28.02.2019
         case 'save':
            // Keine Rechnungs-ID ? -> Bestellungen-Liste
            if (!isset($_POST['id'])) {
               header('Location: '.ADMIN_URL_IDX.'/bestellungen');
               exit;
            }

            // Details - Daten geändert Speichern, evtl Mail
            $re_id      = $this->params->postInt('id');
            $mode       = $this->params->postString('mode');   // Buttons - bestaetigen, pdf (Re. Erstellen), pdf_senden (versendet), speichern, storno
            $status     = $this->params->postInt('status');    // Selectbox, neu Popup: 1 - neu, 2 - bestätigt, 3 - versandbereit, 4 - versendet, 5 - storno
            $zahlart    = $this->params->postInt('zahlart');
            $bearbeiten = $this->params->postCheckbox('bearbeiten');
            $pdf_script = '';
            $status_old = 0;

            if (defined('CONF_AUTO_BUCHUNG') && $re_id > 0) {
               $status_old = (int)$this->db->querySingleValue("SELECT status FROM #__rechnung WHERE id = $re_id");
            }

            // Liefer-/Zahlungdatum immer möglich
            $lieferdatum  = str_replace(',', '.', $this->params->postString('lieferdatum'));
            $zahlungdatum = str_replace(',', '.', $this->params->postString('zahlungseingang'));

            // Immer speichern
            $this->_saveLieferdatum($re_id, $lieferdatum);
            $this->_saveZahlungdatum($re_id, $zahlungdatum);
            $this->_saveZahlart($re_id, $zahlart);

            // Neue Rechnung aus Kunden oder Select 'neu' und Button Speichern
            if ($mode == 'neu' || ($mode == 'speichern' && $status == 1)) {
               $this->_change(1, $mode);
            }

            // Bestätigen-Button oder manuell bestätigen
            else if ($mode == 'bestaetigen' || ($mode == 'speichern' && $status == 2)) {
               $this->_change(2, $mode);

               // Bei Bestätigen-Button: Bestätigungsmail senden
               if ($mode == 'bestaetigen') {
                  $mail = Control::getMail();
                  $mail->sendBestellung($this->params->postString('email'), $this->params->postInt('id'));
               }
            }

            // PDF-Rechnung erstellen und anzeigen
            // RE-Erstellen oder manuell versandbereit
            else if ($mode == 'pdf' || ($mode == 'speichern' && $status == 3)) {
               $this->_change(3, $mode, ($bearbeiten === 'y' ? true : false));

               // Button RE erstellen
               if ($mode == 'pdf') {
                  $pdf_script  = '<form id="form_pdf" method="post" action="' . ADMIN_URL_IDX . '/bestellungen/pdf">';
                  $pdf_script .= '<input type="hidden" name="pdf" value="' . $re_id . '" /></form>';
                  $pdf_script .= Helper::htmlScript("$('#form_pdf').submit()");
               }
            }

            // Button versendet oder manuell versendet
            else if ($mode == 'pdf_senden' || ($mode == 'speichern' && $status == 4)) {
               // 04.01.20 $this->_change(4, $mode);
               $this->_change(4, $mode, ($bearbeiten === 'y' ? true : false));

               // Button versendet
               if ($mode == 'pdf_senden') {
                  // Rechnung senden
                  $mail = Control::getMail();
                  $mail->sendRechnung($this->params->postString('email'), $this->params->postInt('id'), 'r');

                  // Klarna Versand bestätigen
                  if ($zahlart == 14) {
                     $order_id  = '';
                     $klarna_re = '';
                     list($order_id, $klarna_re) = explode('::', $this->dataDetails->zahlungsinfo2);

                     $klarna = Control::getModuleKlarna();
                     $test = $klarna->capture($order_id);

                     if ($test) {
                        $this->db->query("UPDATE #__rechnung SET zahlungsinfo1 = 'Klarna aktiviert' WHERE id = $re_id");
                     }
                  }
               }
            }

            // Storno
            else if ($mode == 'storno' || $status == 5) {
               $this->_change(5, $mode);
            }

            else if ($bearbeiten == 'y') {
               $this->_change($status, $mode, true);
            }

            // Daten neu lesen und ausgeben
            $this->getDetailBestellung();
            $this->getDetailArtikel(0, 'alle');
            $data       = $this->dataDetails;
            $data->role = $this->db->querySingleValue("SELECT role FROM #__users WHERE id = $data->user_id");
            $data2      = $this->dataArtikel;

            $_SESSION['pdf_script'] = $pdf_script;
            header('Location: '.ADMIN_URL_IDX.'/bestellungen/detail/'.$data->id);

            if (defined('CONF_AUTO_BUCHUNG') && $re_id > 0) {
               // Status korrigieren, Wenn Buttons geklickt
               if ($mode == 'neu') { $status = 1; }
               if ($mode == 'bestaetigen') { $status = 2; }
               if ($mode == 'pdf') { $status = 3; }
               if ($mode == 'pdf_senden') { $status = 4; }
               if ($mode == 'storno') { $status = 5; }

               // Bisher noch keine Rechnung erstellt, Durch Status wurde jetzt Rechnung erstellt
               if ($status_old < 3 && $status > 2) {
                  // Rechnung wurde erstellt
                  $this->im_export->exportBuchungenAuto($re_id, false, false, $status, $status_old);
               }

               // Rechnung bereits erstellt, Status jedoch herabgesetzt oder storniert -> löschen
               else if ($status_old > 2 && $status < 3 || $status == 5) {
                  $this->im_export->exportBuchungenAuto($re_id, true, true, $status, $status_old);
               }

               // Rechnung wurde bearbeitet
               else if ($bearbeiten == 'y') {
                  $this->im_export->exportBuchungenAuto($re_id, true, false, $status, $status_old);
               }
            }

            exit;
            break;

         // Aufgerufen von Kunden-Details
         case 'neu':
            $id                   = $this->_bestellungNeu($this->params->params3);
            $_SESSION['new_best'] = $id;

            header('Location: '.ADMIN_URL_IDX.'/bestellungen/detail/'.$id);
            exit;
            break;

         case 'popup':
            $html = '';
            $id   = $this->params->postInt('id');
            $data = $this->db->querySingleObject("SELECT status FROM #__rechnung WHERE id = $id");

            include_once ADMIN_PATH.'/templates/popup_bestellung.tpl.php';
            exit(json_encode(['status' => 'ok', 'html' => $html]));
            break;

         // Bestellliste eines Kunden anzeigen (von Kuden-Details aufgerufen
         // 05.12.2018
         case 'usermode':
            $user_id = (isset($this->params->add_params[0]) ? (int)$this->params->add_params[0] : 0);

            $_SESSION['usermode']       = $user_id;
            $this->user_id              = $user_id;
            $_SESSION['usermode_seite'] = $_SESSION['bestell_seite'];
            $_SESSION['bestell_seite']  = 0;

            $this->_getDefault();
            break;

         // PDF Upload
         // 28.02.2019
         case 'pdf':
            // PDF-Rechnung erstellen und anzeigen
            $pdf_id = $this->params->postInt('pdf');
            $mode = $this->params->postString('mode');
            $pdf = Control::getPdf();

            if ($mode != 'kunde') {
               $pdf->makePdf($pdf_id, 'rechnung');
            }
            else {
               $pdf->makePdf($pdf_id, 'rechnung', 'D', 'kunde');
            }

            break;

         // Button Drucken
         // 28.02.2019
         case 'drucken':
            $mode  = $this->params->postInt('mode');
            $re_id = $this->params->postInt('re_id');

            // Lieferschein
            if ($mode == 1) {
               $this->_sendLieferschein($re_id, $this->params->postString('lieferdatum'));
            }

            // DHL Paketaufkleber
            if ($mode == 2) {
               $this->_sendPaket($re_id, 'dhl');
            }

            // Hermes Paketaufkleber
            if ($mode == 3) {
               $this->_sendPaket($re_id, 'hermes');
            }

            // DPD Paketaufkleber
            if ($mode == 4) {
               $this->_sendPaket($re_id, 'dpd');
            }

            // GLS Paketaufkleber
            if ($mode == 5) {
               $this->_sendPaket($re_id, 'gls');
            }

            // Adressetikett sofort drucken
            if ($mode == 11) {
               $this->_sendEtikett($re_id, 'print');
            }

            // Adressetikett sammeln
            if ($mode == 12) {
               $this->_sendEtikett($re_id, 'save');
            }

            // Adressetikett gesammelte ausdrucken
            if ($mode == 13) {
               $this->_sendEtikett('', 'printall');
            }

            break;

         // Popup Printconfig anzeigen
         // 20.02.2019
         case 'getPrinterconfig':
            $html = '';
            include 'templates/popup_printerconfig.tpl.php';
            echo json_encode(['status' => 'ok', 'html' => $html]);
            break;

         // Popup Printconfig speichern
         // 20.02.2019
         case 'savePrinterconfig':
            $this->_savePrinterconfig();
            echo json_encode(['status' => 'ok', 'msg' => 'Konfiguration gespeichert']);
            break;

         case 'haendler':
            include 'templates/bestellung_liste.tpl.php';
            break;

         // Motiv-Datei herunterladen
         // 28.02.2019
         case 'motivDownload':
            $this->_motivDownload();
            break;

         // Artikel manuell hinzufügen (voa Popup)
         // 20.02.2019
         case 'bestellungAdd':
            $html_artikel = '';
            $re_id        = $this->params->postInt('re_id');
            $test         = $this->_addArticle($re_id, $this->params->postInt('art_id'));

            if ($test == 'ok') {
               // Daten neu einlesen
               $this->getDetailBestellung($re_id, 'admin');
               $this->getDetailArtikel($re_id, 'alle', 'default_lang');

               $data  = $this->dataDetails;
               $data2 = $this->dataArtikel;

               if ($data2) {
                  foreach ($data2 as $d) {
                     $d->pict = '';
                     $img     = $this->db_extern->querySingleObject("SELECT i.image, a.startbild FROM #__articles_info AS i, #__articles AS a WHERE a.id = $d->artikel_id AND a.parent_id = i.id");

                     if ((int)$img->startbild < 2) {
                        $img = $img->image;
                     }

                     else {
                        $img = $this->db_extern->querySingleObject("SELECT image FROM #__articles_images WHERE parent_id = $d->parent_id AND sort = ".($d->startbild - 1));
                     }
                  }
               }

               $readonly = '';
               $disabled= '';

               if ($data->status == 3) {
                  $readonly = ' readonly="readonly"';
                  $disabled = ' disabled="disabled"';
               }
               $zahlungsart      = $data->zahlungsart;
               $zahlungstext     = Helper::getZahlartText($zahlungsart);

               include 'templates/best_detail_artikel.tpl.php';
               echo json_encode(['status' => $test, 'html' => $html_artikel]);
            }

            else {
               if ($test == 'vorhanden') {
                  echo json_encode(['status' => 'vorhanden', 'msg' => "Artikel ist bereits in der Bestellung.\nBitte Menge ändern"]);
               }

               else {
                  echo json_encode(['status' => 'error', 'msg' => 'Artikel konnte nicht hinzugefügt werden']);
               }
            }

            break;

         // Ebay-Bestellungen abholen
         case 'getEbayBest':
            $ebay = Control::getEbayOrders();
            $ebay->getOrders();
            return;
            break;

/*
         // Dawanda-Bestellungen abholen
         case 'getDawandaBest':
            $dawanda = Control::getModuleDawanda();
            $dawanda->getOrders();
            return;
            break;
*/
         // Amazon-Bestellungen abholen
         case 'getAmazonBest':
            $amazon = Control::getModuleAmazonorders();
            $amazon->getOrdersList();
            return;
            break;

         // Billbee-Sync starten
         case 'billbeeSync':
            $billbee = Control::getModuleBillbee();
            $billbee->triggerOrderSync();
            return;
            break;

         // Module Bestellzusammenfassung
         case 'pdfCollector':
            // PDF-Rechnung erstellen und anzeigen
            $pdf_id = $this->params->postInt('pdf');
            $pdf = Control::getPdfCollector();
            $pdf->makePdf($pdf_id, 'rechnung');
            break;

         // Edit-Seite (Modul) Sammelbestellung anzeigen
         case 'collector':
            $id = 0;
            $this->getDetailBestellung($id, 'admin');
//            $this->getDetailArtikel($id);
            $data = $this->dataDetails;
//            $data2 = $this->dataArtikel;
//            include str_replace('/admin', '', SHOP_PATH).'/classes/modules/bestellzusammenfassung/bestellzusammenfassung.tpl.php';
            include ADMIN_PATH.'/templates/bestellung_liste.tpl.php';
            break;

         case 'changeCollector':
            // Edit - Daten geändert Speichern, evtl Mail
            $mode = $this->params->postString('mode');
            $id = $this->params->postInt('id');
            $status = $this->params->postInt('status');
            $lieferdatum = str_replace(',', '.', $this->params->postString('lieferdatum'));

            $this->_saveLieferdatum($id, $lieferdatum);

            if ($mode == 'neu' || ($mode == 'speichern' && $status == 1)) {
               $this->changeCollector(1, $mode);
            }

            // Bestätigen oder manuell offen
            else if ($mode == 'bestaetigen' || ($mode == 'speichern' && $status == 2)) {
               $this->changeCollector(2, $mode);
               if ($mode == 'bestaetigen') {
                  $mail = Control::getMail();
                  $mail->sendBestellung($this->params->postString('email'), $this->params->postInt('id'));
               }
            }

            // PDF-Rechnung erstellen und anzeigen
            // PDF-Rechnung oder manuell erledigt
            else if ($mode == 'pdf' || ($mode == 'speichern' && $status == 3)) {
               $this->changeCollector(3, $mode);

               if ($mode == 'pdf') {
                  $pdf  = '<form id="form_pdf" method="post" action="' . ADMIN_URL_IDX.'/bestellungen/pdfCollector">';
                  $pdf .= '<input type="hidden" name="pdf" value="' . $id . '" /></form>';
                  $pdf .= Helper::htmlScript("document.getElementById('form_pdf').submit()");
               }
            }

            else if ($mode == 'pdf_senden'|| ($mode == 'speichern' && $status == 4)) {
               $this->changeCollector(4, $mode);

               if ($mode == 'pdf_senden') {
                  $mail = Control::getMail();
                  //$mail->sendRechnungCollector($this->params->postString('email'), $this->params->postInt('id'), 'r');
                  $mail->sendRechnung($this->params->postString('email'), $this->params->postInt('id'));
               }
            }

            else if ($mode == 'storno' || ($mode == 'speichern' && $status == 5)) {
               $this->changeCollector(5, $mode);
            }

            // Daten neu lesen und ausgeben
            $this->getDetailBestellung();
            $data = $this->dataDetails;
//            include str_replace('/admin', '', SHOP_PATH).'/classes/modules/bestellzusammenfassung/bestellzusammenfassung.tpl.php';
            include ADMIN_PATH.'/templates/bestellung_liste.tpl.php';

            break;

         // Sammelbestellungen generieren
         case 'collect':
            $this->collect();
            $this->_getDefault();
            break;

         // Bestellung-Liste anzeigen (Start)
         default:
            // Wenn zuvor im Usermode, Seite wiederherstellen
            if (isset($_SESSION['usermode_seite'])) {
               $_SESSION['bestell_seite'] = $_SESSION['usermode_seite'];
               unset($_SESSION['usermode_seite']);
            }

            $_SESSION['usermode'] = 0;
            $this->_getDefault();
            break;
      }

      return;
   }

   // Bestellliste ausgeben / Überschrieben von Bestellzusammenfassung
   // Durch Bestellzusammenfassung überschrieben !!!
   // 05.12.2018
   protected function _getDefault() {
      unset($_SESSION['pdf_script']);

      if (defined('CONF_MODULE_PORTAL') && $_SESSION['haendler'] == 'n') {
         $_SESSION['admin_haendler_id'] = 0;
      }

      // Default - Bestellliste ausgeben
      include 'templates/bestellung_liste.tpl.php';
   }

   // Bestellliste Augeben (für AJAX) - komplett, Sortiert oder gesuchte / Überschrieben von Bestellzusammenfassung
   // Durch Bestellzusammenfassung überschrieben !!!
   // 05.12.2018
   protected function liste($user_id = 0, $search = false, $all = false) {
      $html = '';
      $lang = $this->params->selected_lang;

      $sql = "SELECT r.id, r.user_id, r.status, r.netto + r.steuer1 + r.steuer2 + r.steuer3 as preis,
                     r.netto, r.bestellnummer, r.created as bestelldatum, r.dhl_send_check, r.dhl_intraship AS sendungs_nr,
                     r.nachname, r.vorname, r.firma, r.email, r.adresse, r.hausnr, r.ort, r.hausnr, r.staat, r.staat2,
                     r.rechnungsnummer, r.rechnungsdatum, r.zahlung, r.zahlungdatum, r.pdf, r.zahlungsart, r.zahlungsinfo1,
                     u.id AS user, u.role
                 FROM #__rechnung AS r
              LEFT JOIN #__users AS u
                 ON r.user_id = u.id
              WHERE r.deleted = 'n'
                 AND bestellnummer != ''";

      // Zusätzlich Portal
      if (defined('CONF_MODULE_PORTAL')) {
         if ($_SESSION['haendler'] == 'y') {
            $sql .= " AND r.haendler_id = ".(int)$this->params->user_id;
         }

         else {
            if ($this->params->postInt('haendler_id') > 0) {
               $this->haendler_id = $this->params->postInt('haendler_id');
            }

            else {
               $this->haendler_id = $_SESSION['admin_haendler_id'];
               $_SESSION['admin_haendler_id'] = 0;
            }

            if ($this->haendler_id > 0) {
               $sql .= " AND r.haendler_id = ".$this->haendler_id;
            }
         }
      }

      // Bei Suche
      if ($search) {
         // Nach Rechnungsnummer suchen - woher?
         if ($all == 0) {
            $sql .= " AND r.id = '$search'";
         }
         else {
            $sql .= " AND (r.bestellnummer LIKE '%".$this->db->escape($search)."%' OR r.rechnungsnummer LIKE '".$this->db->escape($search)."%' OR r.nachname LIKE '".$this->db->escape($search)."%' OR r.firma LIKE '".$this->db->escape($search)."%' OR r.email LIKE '".$this->db->escape($search)."%')";
         }
      }

      else if ($_SESSION['usermode'] > 0) {
         $user_id = $_SESSION['usermode'];
         $sql .= " AND r.user_id = '$user_id'";
      }

      else if ($_SESSION['usermode'] == 0 && $user_id) {
         $sql .= " AND r.user_id = '$user_id'";
      }

      // Sortierung Feld
      switch ($_SESSION['bestell_sort']) {
         case "1":
            $sql .= " ORDER BY status ";
            break;

         case "2":
            //$sql .= " ORDER BY CAST(LEFT(TRIM(LEADING 'E' FROM TRIM(LEADING 'A' FROM TRIM(LEADING 'S' FROM bestellnummer))), LOCATE('-', bestellnummer)) AS UNSIGNED)";
            $sql .= " ORDER BY bestellnummer ";
            break;

         case "3":
            $sql .= " ORDER BY bestelldatum ";
            break;

         case "4":
            $sql .= " ORDER BY rechnungsnummer ";
            break;

         case "5":
            $sql .= " ORDER BY rechnungsdatum ";
            break;

         case "6":
            $sql .= " ORDER BY email ";
            break;

         case "7":
            $sql .= " ORDER BY nachname ";
            break;
      }

      // Sortierung auf/absteigend
      if ($_SESSION['bestell_dir'] == 'asc') {
         $sql .= " ASC ";
      }

      else {
         $sql .= " DESC ";
      }

      if (isset($_SESSION['bestell_limit'])) {
         $limit = $_SESSION['bestell_limit'];
      }

      else {
         // Default-Wert aus Konfiguration
         $limit = CONF_ART_PER_SITE;
         $_SESSION['bestell_limit'] = $limit;
      }

      if (isset($_SESSION['bestell_seite'])) {
         $seite = $_SESSION['bestell_seite'];
      }

      else {
         $seite = 0;
         $_SESSION['bestell_seite'] = $seite;
      }

      if (!$search) {
         $sql .= " LIMIT " . $seite * $limit . ", $limit";
      }

      $datas = $this->db->queryAllObjects($sql);

      if (is_array($datas) && count($datas)) {
         if ($search) {
            $this->suchanzahl = count($datas);
         }

         $html .= $this->_printListe($datas);
      }

      return $html;
   }

   // HTML-Generierung für $this->liste
   // 05.12.2018
   protected function _printListe($datas) {
      $html = '';

      foreach ($datas as $data) {
         $staat_name = Helper::getStaatName($data->staat, $data->staat2);
         //display TXN label for paypal and paypalv2 payment and mollie?
         $txn = '';
         if ( ( (int)$data->zahlungsart == 2 || (int)$data->zahlungsart == 18 ) && $data->zahlungsinfo1 != '' ) {
            $txn = 'TXN';
         }elseif ( (int)$data->zahlungsart == 19 && $data->zahlungsinfo1 != '' ) {
            $txn = 'mollie';
         }

         $html .= '<div id="best_'.$data->id.'" class="list_line">'.CR;
         $html .= '   <div class="best_list_right">'.CR;
         // Farbige Markierung nach Status
         if ($data->status == 1) {
            $html .= '      <div class="best_list1 pointer list_col best_neu" onclick="location.href=\''.ADMIN_URL_IDX.'/bestellungen/detail/'.$data->id.'\';">neu<span class="ebay_txn">'.$txn.'</span></div>'.CR;
         }

         else if ($data->status == 2) {
            $html .= '      <div class="best_list1 pointer list_col best_offen" onclick="location.href=\''.ADMIN_URL_IDX.'/bestellungen/detail/'.$data->id.'\';">bestätigt<span class="ebay_txn">'.$txn.'</span></div>'.CR;
         }

         else if ($data->status == 3) {
            $html .= '      <div class="best_list1 pointer list_col best_bereit" onclick="location.href=\''.ADMIN_URL_IDX.'/bestellungen/detail/'.$data->id.'\';">bereit<span class="ebay_txn">'.$txn.'</span></div>'.CR;
         }

         else if ($data->status == 4) {
            $html .= '      <div class="best_list1 pointer list_col best_erledigt" onclick="location.href=\''.ADMIN_URL_IDX.'/bestellungen/detail/'.$data->id.'\';">versendet</div>'.CR;
         }

         else if ($data->status == 5) {
            $html .= '      <div class="best_list1 pointer list_col best_erledigt" onclick="location.href=\''.ADMIN_URL_IDX.'/bestellungen/detail/'.$data->id.'\';">storniert</div>'.CR;
         }

         // Bei Bezahlmodul Paypal ???
         else if ($data->status == 0) {
            $html .= '      <div class="best_list1 pointer list_col best_neu" onclick="location.href=\''.ADMIN_URL_IDX.'/bestellungen/detail/'.$data->id.'\';">Einlesen<span class="ebay_txn">'.$txn.'</span></div>'.CR;
         }

         else {
            $html .= '      <div class="best_list1 pointer list_col best_neu" onclick="location.href=\''.ADMIN_URL_IDX.'/bestellungen/detail/'.$data->id.'\';">Pending<span class="ebay_txn">'.$txn.'</span></div>'.CR;
         }

         $html .= '      <div class="best_list2 list_col ellipsis">'.$data->bestellnummer.'</div>'.CR;
         $html .= '      <div class="best_list3 list_col ellipsis">'.Helper::sqlDatumShort($data->bestelldatum).'</div>'.CR;

         // PDF bereits erstellt
         if ($data->pdf == 'r') {
            $html .= '      <div class="best_list4 list_col" onclick="$(\'.form_pdf\', $(this)).submit();" title="Re-'.$data->rechnungsnummer.'.pdf">'.CR;
            $html .= '         <form class="form_pdf display_none" method="post" action="'.ADMIN_URL_IDX.'/bestellungen/pdf">'.CR;
            $html .= '            <input type="hidden" name="mode" value="admin" />'.CR;
            $html .= '            <input type="hidden" name="pdf" value="'.$data->id.'" />'.CR;
            $html .= '         </form>'.CR;
            $html .= '         <div class="has_pdf pointer"></div>'.CR;
            $html .= '      </div>'.CR;
         }

         // PDF direkt erstellen
         else if ($data->status > 0) {
            $html .= '      <div class="best_list4 list_col" title="Rechnung erstellen" onclick="$(\'.form_pdfdirect\', $(this)).submit(); setTimeout(function() { location.reload(); }, 1000);">'.CR;
            $html .= '         <form class="form_pdfdirect display_none" method="post" target="pdfback" action="'.ADMIN_URL_IDX.'/bestellungen/pdfdirect">'.CR;
            $html .= '            <input type="hidden" name="id" value="'.$data->id.'" />'.CR;
            $html .= '            <input type="hidden" name="email" value="'.$data->email.'">'.CR;
            $html .= '         </form>';
            $html .= '         <div class="fas fa-exclamation no_pdf pointer"></div>'.CR;
            $html .= '      </div>'.CR;
         }

         // PDF nicht möglich (Status 0 oder kleiner, bei Bezahlmodulen möglich, wenn noch keine Rückmeldung)
         else {
            $html .= '      <div class="best_list4 list_col no_pdf" title="Rechnung nicht vorhanden"></div>'.CR;
         }

         $html .= '      <div class="best_list5 list_col ellipsis">'.$data->rechnungsnummer.'</div>'.CR;
         $html .= '      <div class="best_list6 list_col ellipsis">'.Helper::sqlDatumShort($data->rechnungsdatum).'</div>'.CR;
         $html .= '      <div class="best_list7 list_col ellipsis"><a href="mailto:'.$data->email.'"'.((int)$data->role == 1010 ? ' class="list_kunde_vip"' : '').'>'.$data->email.'</a></div>'.CR;
         $html .= '      <div class="best_list8 list_col ellipsis">'.$data->nachname.', '.$data->vorname.'</div>'.CR;
         $html .= '      <div class="best_list9 list_col ellipsis">'.$data->firma.'</div>'.CR;
         $html .= '      <div class="best_list10 list_col ellipsis">'.$staat_name.'</div>'.CR;
         $html .= '      <div class="clear"></div>';
         $html .= '   </div>';
         $html .= '  <div class="best_list_left">'.CR;

         // DHL
         if ($data->dhl_send_check == 'y') {
            $html .= '      <div class="dhl_status" title="'.$data->sendungs_nr.'"></div>'.CR;
         }

         // Icons
         //$html .= '      <div class="best_liste_edit fas fa-pencil-alt" onclick="Bestellungen.detail('.$data->id.');" title="bearbeiten"></div>'.CR;
         $html .= '      <div class="list_edit fas fa-pencil-alt pointer" onclick="location.href=\''.ADMIN_URL_IDX.'/bestellungen/detail/'.$data->id.'\';" title="bearbeiten"></div>'.CR;

         // Registrierte User
         if ($data->user_id && $data->user > 0) {
            if ((int)$data->role < 11) {
               $html .= '      <div class="list_kunde fas fa-user pointer" onclick="Kunden.details('.$data->user_id.');" title="Kundenaccount"></div>'.CR;
            }

            else if ((int)$data->role < 18) {
               $html .= '      <div class="list_kunde_rabatt fas fa-user pointer" onclick="Kunden.details('.$data->user_id.');" title="Stammkunde"></div>'.CR;
            }

            else {
               $html .= '      <div class="list_kunde_vip fas fa-user pointer" onclick="Kunden.details('.$data->user_id.');" title="Kundenaccount"></div>'.CR;
            }
         }

         else {
            $html .= '      <div class="list_kunde_vip fas"></div>'.CR;
         }

         $html .= '      <div class="list_del far fa-trash-alt pointer" onclick="Bestellungen.delete('.$data->id.', '.$this->haendler_id.');" title="löschen"></div>'.CR;

         $html .= '   </div>'.CR;
         $html .= '   <div class="clear"></div>';
         $html .= '</div>';
      }

      return $html;
   }

   // Liste / Anzeige Anzahl Seiten usw.
   // Durch Bestellzusammenfassung überschrieben !!!
   // 05.12.2018
   protected function getCounter() {

      $seite  = isset($_SESSION['bestell_seite']) ? $_SESSION['bestell_seite'] : 0; // aktuelle Seite z. Anzeigen
      $limit  = isset($_SESSION['bestell_limit']) ? $_SESSION['bestell_limit'] : CONF_ART_PER_SITE;
      $anzahl = $this->suchanzahl;

      $html = '<div class="pager_left"><span class="erg_text">Ergebnisse pro Seite</span>'.CR;

      // Liste Anzahl Ergebnisse pro Seite
      for ($i = CONF_ART_PER_SITE; $i <= CONF_ART_MAX; $i += CONF_ART_PER_SITE) {
         $class = ($i == $limit ? ' counter_active' : '');
         $html .= '<span class="rahmen'.$class.'" onclick="Bestellungen.count('.$i.');">'.$i.'</span>'.CR;
      }

      $html .= '</div>'.CR;

      if ($this->suchanzahl == -1) {

            /*  $sql = "SELECT count(id) as anzahl FROM #__rechnung WHERE deleted = 'n'";

            if (defined('CONF_MODULE_PORTAL')) {
            if ($_SESSION['haendler'] == 'y') {
            $sql .= " AND haendler_id = ".(int)$this->params->user_id;
            }
            else if ($this->haendler_id > 0) {
            $sql .= " AND haendler_id = ".$this->haendler_id;
            }
            }

            if ($_SESSION['usermode'] > 0) {
            $sql .= " AND user_id = ".$_SESSION['usermode'];
            }
*/

         ///

         $sql = "SELECT count(r.id)
                 FROM #__rechnung AS r
              LEFT JOIN #__users AS u
                 ON r.user_id = u.id
              WHERE r.deleted = 'n'
                 AND bestellnummer != ''";

          // Zusätzlich Portal
          if (defined('CONF_MODULE_PORTAL')) {
             if ($_SESSION['haendler'] == 'y') {
                $sql .= " AND r.haendler_id = ".(int)$this->params->user_id;
             }

             else {
                if ($this->params->postInt('haendler_id') > 0) {
                   $this->haendler_id = $this->params->postInt('haendler_id');
                }

                else {
                   $this->haendler_id = $_SESSION['admin_haendler_id'];
                   $_SESSION['admin_haendler_id'] = 0;
                }

                if ($this->haendler_id > 0) {
                   $sql .= " AND r.haendler_id = ".$this->haendler_id;
                }
             }
          }

          // Bei Suche
          $user_id  = $this->user_id;

          $search = $this->params->postInt('search');
          if ($search) {
             // Nach Rechnungsnummer suchen - woher?
             if ($all == 0) {
                $sql .= " AND r.id = '$search'";
             }
             else {
                $sql .= " AND (r.bestellnummer LIKE '%".$this->db->escape($search)."%' OR r.rechnungsnummer LIKE '".$this->db->escape($search)."%' OR r.nachname LIKE '".$this->db->escape($search)."%' OR r.firma LIKE '".$this->db->escape($search)."%' OR r.email LIKE '".$this->db->escape($search)."%')";
             }
          }

          else if ($_SESSION['usermode'] > 0) {
             $user_id = $_SESSION['usermode'];
             $sql .= " AND r.user_id = '$user_id'";
          }

          else if ($_SESSION['usermode'] == 0 && $user_id) {
              $sql .= " AND r.user_id = '$user_id'";
          }







         $anzahl = $this->db->querySingleValue($sql);

      }

      else {
         $seite = 0;
         $limit = 10000;
      }


      $html .= '<div class="pager_right">'.CR;

      if ($anzahl) {
         $start = 0;                            // Start mit Seite
         $von = $seite * $limit + 1;            // Art. von
         $bis = ($seite + 1) * $limit;          // Art. bis
         $ende = (int)floor($anzahl / $limit);  // max. Seiten

         // Bei Suche
         if ($this->suchanzahl > -1) {
            $von = 1;
            $bis = $this->suchanzahl;
            $start = 0;
            $ende = 0;
            $limit = $this->suchanzahl;
         }

         // Korrekturen bei letzer Seite
         if ($seite == $ende && ($ende * $limit < $anzahl)) {
            $bis = $anzahl;
         }

         if ($seite > 0) {
            $html .= '<div class="first fas fa-angle-double-left active" onclick="Bestellungen.seite(0);"></div>'.CR;
         }

         else {
            $html .= '<div class="first fas fa-angle-double-left inactive"></div>'.CR;
         }

         if ($seite > 0) {
            $html .= '<div class="back fas fa-angle-left active" onclick="Bestellungen.seite('.($seite - 1).');"></div>'.CR;
         }

         else {
            $html .= '<div class="back fas fa-angle-left inactive"></div>'.CR;
         }

         $html .= '<div class="vonbis">'.$von.' - '.$bis.' von '.$anzahl.'</div>'.CR;

         if ($seite  < $ende) {
            $html .= '<div class="next fas fa-angle-right active" onclick="Bestellungen.seite('.($seite + 1).');"></div>'.CR;
         }

         else {
            $html .= '<div class="next fas fa-angle-right inactive"></div>'.CR;
         }

         if ($seite < $ende) {
            $html .= '<div class="end fas fa-angle-double-right active" onclick="Bestellungen.seite('.$ende.');"></div>'.CR;
         }

         else {
            $html .= '<div class="end fas fa-angle-double-right inactive"></div>'.CR;
         }
      }

      else {
         $html .= 'keine Bestellungen vorhanden'.CR;
      }

      $html .= '</div>'.CR;
      $html .= '<div class="clear"></div>'.CR;

      return $html;
   }

   // Bestellung löschen (als gelöscht markieren)
   // 28.02.2019
   private function _delete() {
      $re_id = $this->params->postInt('id');
      $this->db->query("UPDATE #__rechnung SET deleted = 'y' WHERE id = $re_id");

      return true;
   }

   // Alle Bestellungen eines Händlers löschen
   // 28.02.2019
   public function deleteBestellungHaendler($haendler_id) {
      if ($haendler_id > 0) {
         $bestellungen = $this->db->queryAllObjects("SELECT id FROM #__rechnung WHERE haendler_id = $haendler_id");

         if ($bestellungen) {
            foreach ($bestellungen as $bestellung) {
               $this->db->query("DELETE FROM #__rechnung_artikel WHERE rechnung_id = $bestellung->id");
               $this->db->query("DELETE FROM #__rechnung WHERE id = $bestellung->id");
            }
         }
      }
   }

   // Details - Änderungen speichern
   // 28.02.2019
   private function _change($status, $mode, $bearbeiten = false) {
      $re_id = $this->params->postInt('id');

      // Status auf versandbereit
      if ($status == 4 && $this->params->func != 'pdfdirect' && !$bearbeiten) {
         $this->db->query("UPDATE #__rechnung SET status = $status WHERE id = $re_id");

         return;
      }

      // Daten lesen
      $this->getDetailBestellung();
      $r_data          = $this->dataDetails;
      $oldstatus       = (int)$r_data->status > 10 ? (int)$r_data->status - 10 : (int)$r_data->status;

      //Bei storniert keine Änderung mehr möglich
      if ($oldstatus == 5 && $r_data->collector == 'y') {
         return;
      }

      // Zuerst in DB
      $rechnungsnummer = $r_data->rechnungsnummer;
      $rechnungsdatum  = substr($r_data->rechnungsdatum, 0, 10);
      $zahlungdatum    = substr($r_data->zahlungdatum, 0, 10);
      $lieferdatum     = substr($r_data->lieferdatum, 0, 10);

      // Dann Parameter
      $re_nr           = $this->params->postString('rechnungsnummer');
      $re_datum        = Helper::datumSql($this->params->postString('rechnungsdatum'));

      // Bei RE erstellen
//      if ($mode != 'speichern' && $oldstatus != 3 && $status == 3 || $this->params->func == 'pdfdirect') {
      if ($mode != 'speichern' && $status == 3 || $this->params->func == 'pdfdirect') {
         // Rechnungsnummer generieren, wenn keine vorgegeben.

         if (($re_datum == '' || $re_datum == '0000-00-00') && ($rechnungsdatum == '' || $rechnungsdatum == '0000-00-00')) {
            $rechnungsdatum = date('Y-m-d H:i:s');
         }

         // Bei Wechsel auf versendet, Rechnungsnummer generieren, wenn keine vorgegeben.
         if ($re_nr == '' && $rechnungsnummer == '') {
            if (!defined('CONF_MODULE_PORTAL')) {
               $rechnungsnummer = $this->db->getRechnungsnummer();
            }

            else {
               $haendler_id = $this->db->querySingleValue("SELECT haendler_id FROM #__rechnung WHERE id = $re_id");
               $rechnungsnummer = $this->db->getRechnungsnummerHaendler($haendler_id);
            }
         }

         // Downloadartikel
         $dl    = Control::getDownload();
         $links = [];
         $links = $dl->getLinks($re_id);

         if (is_array(($links)) && count($links) > 0) {
            $mail = Control::getMail();

            // Downloadlinks an Kunde senden
            for ($i = 0; $i < count($links); $i++) {
               $mail->sendDownloadLink($this->params->postString('email'), $re_id, $links[$i]);
            }
         }

         // Neukunde -> Stammkunde
         $this->_checkRole($re_id);
      }

      else {
         $rechnungsnummer = $re_nr;
         $rechnungsdatum  = $re_datum;
      }

      $this->db->query("UPDATE #__rechnung SET status = $status, rechnungsnummer = '$rechnungsnummer', rechnungsdatum = '$rechnungsdatum' WHERE id = $re_id");

      if ($this->params->func == 'pdfdirect') {
         return;
      }

      // Änderungen Bestellung speichern

      // Test, ob Texte geändert
      $msg_kunde = $this->params->postString('msg_kunde');
      $msg_admin = $this->params->postString('msg_admin');

      if ($this->params->postString('cs_text') != md5($msg_kunde . $msg_admin)) {
         $this->db->query("UPDATE #__rechnung SET
                              msg_kunde = '$msg_kunde',
                              msg_admin = '$msg_admin'
                           WHERE id = $re_id");
      }

      // Test, ob Kundendataen oder Artikeldaten geändert
      $adr_changed    = $this->_adrChanged();
      $lf_changed     = $this->_lfChanged();
      $bank_changed   = $this->_bankChanged();

      if ($adr_changed || $lf_changed || $bank_changed) {
         $this->_changeAdress($adr_changed, $lf_changed, $bank_changed, $re_id);
      }

      $summen_changed = $this->_summenChanged();
      $art_change     = [];
      $art_changed    = false;
      $berechnen      = false;

      $art_id         = $this->params->postArray('art_id');
      $art_menge      = $this->params->postArray('art_menge');
      $art_preis      = $this->params->postArray('art_preis');
      $art_aktiv      = $this->params->postArray('art_active');
      $rechner_breite = $this->params->postArray('rechner_breite');
      $rechner_hoehe  = $this->params->postArray('rechner_hoehe');
      $rechner_tiefe  = $this->params->postArray('rechner_tiefe');
      $art_del        = $this->params->postArray('art_del');
      $csum_artikel   = $this->params->postArray('cs_artikel');

      // Test, ob Artikel geändert
      if ($mode != 'speichern' && $status == 5) {
         for ($i = 0; $i < count($art_id); $i++) {
            $art_del[$i]    = ($art_del[$i] == 'del' ? 'del' : 'storno');
            $art_changed    = true;
            $art_change[$i] = true;
            $art_menge[$i]  = (float)str_replace(',', '.', str_replace('.', '', $art_menge[$i]));
         }
      }

      else {
         for ($i = 0; $i < count($art_id); $i++) {
            $a_art_id      = $art_id[$i];
            $tmp           = $this->db->querySingleObject("SELECT menge, masse_komma, rechner_check FROM #__rechnung_artikel WHERE id = $a_art_id");

            if ($tmp) {
               $komma         = (int)$tmp->masse_komma;
               $r_check       = $tmp->rechner_check;
               $a_menge       = (float)str_replace(',', '.', str_replace('.', '', $art_menge[$i]));

               $a_preis       = $this->params->str2float($art_preis[$i]);
               $a_breite      = !isset($rechner_breite[$i]) ? 0 : (float)str_replace(',', '.', str_replace('.', '', $rechner_breite[$i]));
               $a_hoehe       = !isset($rechner_breite[$i]) ? 0 : (float)str_replace(',', '.', str_replace('.', '', $rechner_hoehe[$i]));
               $a_tiefe       = !isset($rechner_tiefe[$i]) ? 0 : (float)str_replace(',', '.', str_replace('.', '', $rechner_tiefe[$i]));
               $menge_alt[$i] = (float)$tmp->menge;
               $art_menge[$i] = (float)str_replace(',', '.', str_replace('.', '', $art_menge[$i]));
               $a_art_aktiv   = ($art_aktiv[$i] == 'on' ? 'y' : 'n');

               // $md = md5($a_menge.$a_preis.$a_breite.$a_hoehe.$a_tiefe.$this->params->postCheckbox('art_aktiv_'.$art_id[$i]).$art_del[$i]);
               $md = md5($a_menge.$a_preis.$a_breite.$a_hoehe.$a_tiefe.$a_art_aktiv.$art_del[$i]);

               if ($csum_artikel[$i] != $md) {
                  $art_changed    = true;
                  $art_change[$i] = true;
               }
               else {
                  $art_change[$i] = false;
               }
            }

            // Artikel nicht mehr vorhanden
            else {

            }
         }
      }

      if ($summen_changed) {
         $sql = '';
         if ($summen_changed) {
            // Steuer auslesen - Wird benötigt, um gutschrift in Brutto zu berechnen
            $steuer      = $this->db->querySingleObject("SELECT steuer1, steuer2, steuer3, steuersatz1, steuersatz2, steuersatz3 FROM #__rechnung WHERE id = $re_id");
            $steuer1     = $steuer->steuer1;
            $steuer2     = $steuer->steuer2;
            $steuer3     = $steuer->steuer3;
            $steuersatz1 = $steuer->steuersatz1;
            $steuersatz2 = $steuer->steuersatz2;
            $steuersatz3 = $steuer->steuersatz3;

            $berechnen   = true;
            $versand     = sprintf('%0.2f', $this->params->postFloat('porto'));
            $rabatt      = sprintf('%0.2f', $this->params->postFloat('rabatt'));
            $user_rabatt = sprintf('%0.2f', $this->params->postFloat('rabatt_prozent'));
            $gutschrift  = $this->params->postFloat('gutschein');

            if ($steuer1 == 0 && $steuer2 != 0 && $steuer3 == 0) {
               $gutschrift = round($gutschrift * (1 + (float)$steuersatz2 / 100), 2);
            }

            else if ($steuer1 != 0) {
               $gutschrift = round($gutschrift * (1 + (float)$steuersatz1 / 100), 2);
            }

            $gewerbe    = $this->params->postInt('gewerbe');
            if ($sql) {
               $sql .= ", ";
            }
            $sql .= "gewerbe = $gewerbe, versand = '$versand', user_rabatt = '$user_rabatt', rabatt = '$rabatt', gutschrift = '$gutschrift'";
         }

         $sql = "UPDATE #__rechnung SET $sql WHERE id = $re_id";
         $this->db->query($sql);
      }

      // Änderungen Artikel speichern
      if ($art_changed) {
         $berechnen = true;

         // Artikel korrigieren
         for ($i = 0; $i < count($art_id); $i++) {
            if (!$art_change[$i]) {
               continue;
            }

            $artikel_id    = $this->db->querySingleValue("SELECT artikel_id FROM #__rechnung_artikel WHERE id = $art_id[$i]");

            // Artikel löschen
            if ($art_del[$i] == 'del') {
               $this->db->query("DELETE FROM #__rechnung_artikel WHERE id = $art_id[$i]");

               if ($this->params->firma['lager_abziehen'] == 'y') {
                  $this->db_extern->query("UPDATE #__articles SET menge = menge + $art_menge[$i] WHERE id = $artikel_id");
               }
            }

            else if ($art_del[$i] == 'storno') {
               $this->db->query("UPDATE #__rechnung_artikel SET menge = 0, aktiv = 'n' WHERE id = $art_id[$i]");

               if ($this->params->firma['lager_abziehen'] == 'y') {
                  $this->db_extern->query("UPDATE #__articles SET menge = menge + $art_menge[$i] WHERE id = $artikel_id");
               }
            }

            else {
               $a_menge     = $art_menge[$i];
               $a_preis     = $this->params->str2float($art_preis[$i]);
               $a_breite    = !isset($rechner_breite[$i]) ? 0 : sprintf('%0.5f', (float)str_replace(',', '.', $rechner_breite[$i]));
               $a_hoehe     = !isset($rechner_breite[$i]) ? 0 : sprintf('%0.5f', (float)str_replace(',', '.', $rechner_hoehe[$i]));
               $a_tiefe     = !isset($rechner_tiefe[$i])  ? 0 : sprintf('%0.5f', (float)str_replace(',', '.', $rechner_tiefe[$i]));
               $a_art_aktiv = ($art_aktiv[$i] == 'on' ? 'y' : 'n');

               // Menge bei Änderung korrigieren
               if ($this->params->firma['lager_abziehen'] == 'y') {
                  $diff_menge    = $art_menge[$i] - $menge_alt[$i];
                  $artikel_menge = $this->db->querySingleValue("SELECT menge FROM #__articles WHERE id = $artikel_id");
                  $artikel_menge = $artikel_menge - $diff_menge;
                  $this->db_extern->query("UPDATE  #__articles SET menge = '$artikel_menge' WHERE id = $artikel_id");
               }

               $this->db->query("UPDATE #__rechnung_artikel SET
                                    menge          = '$a_menge',
                                    artikel_preis  = '$a_preis',
                                    aktiv          = '$a_art_aktiv',
                                    rechner_breite = '$a_breite',
                                    rechner_hoehe  = '$a_hoehe',
                                    rechner_tiefe  = '$a_tiefe'
                                 WHERE id = $art_id[$i]");
            }
         } // for
      }

      $this->_berechnen($re_id);
   }

   // Bestellung neu berechnen
   // 28.02.2019
   private function _berechnen($id) {
      // Rechnung und Artikel neu lesen
      $this->getDetailBestellung($id);
      $this->getDetailArtikel($id, 'alle', 'default_lang');
      // Daten Rechnung
      $r_data      = $this->dataDetails;
      // Daten Rechnung-Artikel
      $a_data      = $this->dataArtikel;

      $steuersatz1 = (float)$r_data->steuersatz1;
      $steuersatz2 = (float)$r_data->steuersatz2;
      $steuersatz3 = (float)$r_data->steuersatz3;
      $gewerbe     = (int)$r_data->gewerbe;
//      $rabatt_pzt  = (float)$r_data->user_rabatt / 100;

      $rabatt      = (float)$r_data->rabatt;

      $netto       = 0.0;
      $steuer1     = 0.0;
      $steuer2     = 0.0;
      $steuer3     = 0.0;
      $versand     = 0.0;
//      $gewicht     = 0.0;
//      $staffelung  = '';

      if ($this->params->user_id > 0) {
         if ($this->params->firma['tax_ch_check'] == 'y' && (int)$r_data->lf_staat == 450) {
            $this->schweiz = true;
         }

         if ($gewerbe == 2) {
            $this->ausland = true;
         }
      }

      // Artikel neu berechnen
      if ($a_data) {
         foreach ($a_data as $data) {
            if ($data->aktiv == 'y') {
               $steuersatz = (int)$data->steuersatz;
               $steuer_satz = 0;

               if ($steuersatz == 1) {
                  $steuer_satz = $steuersatz1;
               }

               else if ($steuersatz == 2) {
                  $steuer_satz = $steuersatz2;
               }

               else if ($steuersatz == 3) {
                  $steuer_satz = $steuersatz3;
               }

               $menge = $data->menge;
               $preis = $this->berechnung->berechnePreis($data->artikel_preis, $steuer_satz, false, false);

               $gesamt = $menge * round($preis['netto'], 2);
               $steuer = $menge * round($preis['steuer'], 2);
               $netto += $gesamt;
//               $steuer = round($steuer, 2);

               if ($gewerbe == 1) {
                  if ($steuersatz == 1) {
                     $steuer1 += $steuer;
                  }

                  if ($steuersatz == 2) {
                     $steuer2 += $steuer;
                  }

                  if ($steuersatz == 3) {
                     $steuer3 += $steuer;
                  }
               }

               // individueller Versandpreis
               $versand += $data->versand_preis;
            }
          }
      }

      // Falls manuell gesetzt übernehmen, sonst berechnen
      $versand     = $this->params->postFloat('porto');
      $versand_ust = 0;

      if ($gewerbe == 1 ) {
         // Nur reduzierte USt, dann auch für Vers.kosten
         if ($steuer1 == 0) {
            $versand_ust = $versand * $this->params->firma['tax2'] / 100;
         }

         // Mind. 1 Artikel mit normaler USt
         else {
            $versand_ust = $versand * $this->params->firma['tax1'] / 100;
         }
      }

      $zahlart_add = $this->params->postFloat('zahlart_add');
      $zahlart_ust = 0.00;

      if ($zahlart_add != 0) {
         if ($gewerbe == 1 ) {
            // Nur reduzierte USt, dann auch für Vers.kosten
            if ($steuer1 == 0) {
               $zahlart_ust = $zahlart_add * $this->params->firma['tax2'] / 100;
            }

            // Mind. 1 Artikel mit normaler USt
            else {
               $zahlart_ust = $zahlart_add * (float)$this->params->firma['tax1'] / 100;
            }
         }
      }

      if ($netto == 0) {
         $steuer1 = 0;
         $steuer2 = 0;
         $steuer3 = 0;
         $rabatt = 0;
         // $versand = 0;
      }

      $sql = "UPDATE #__rechnung SET
                  netto       = '$netto',
                  steuer1     = '$steuer1',
                  steuer2     = '$steuer2',
                  steuer3     = '$steuer3',
                  rabatt      = '$rabatt',
                  versand     = '$versand',
                  versand_ust = '$versand_ust',
                  zahlart_add = '$zahlart_add',
                  zahlart_ust = '$zahlart_ust'
               WHERE id = $id";
      $this->db->query($sql);
   }

   // Adressen und Bankdaten speichern
   // 28.02.2019
   private function _changeAdress($adr_changed, $lf_changed, $bank_changed, $id) {
      $sql = '';

      if ($adr_changed) {
         $anrede   = $this->db->escape($this->params->postString('anrede'));
         $vorname  = $this->db->escape($this->params->postString('vorname'));
         $nachname = $this->db->escape($this->params->postString('nachname'));
         $firma    = $this->db->escape($this->params->postString('firma'));
         $ustid    = $this->db->escape($this->params->postString('ustid'));
         $adresse  = $this->db->escape($this->params->postString('adresse'));
         $hausnr   = $this->params->postString('hausnr');
         $plz      = $this->params->postString('plz');
         $ort      = $this->db->escape($this->params->postString('ort'));
         $buland   = $this->db->escape($this->params->postString('buland'));
         $telefon  = $this->params->postString('telefon');
         $staat    = $this->params->postString('staat');
         $staat2   = $this->db->escape($this->params->postString('staat2'));
         $email    = $this->db->escape($this->params->postString('email'));

         if ($sql) {
            $sql .= ", ";
         }

         $sql .= "email    = '$email',
                  anrede   = '$anrede',
                  vorname  = '$vorname',
                  nachname = '$nachname',
                  firma    = '$firma',
                  adresse  = '$adresse',
                  hausnr   = '$hausnr',
                  plz      = '$plz',
                  ort      = '$ort',
                  buland   = '$buland',
                  staat    = '$staat',
                  staat2   = '$staat2',
                  ustid    = '$ustid',
                  telefon  = '$telefon'";
      }

      if ($lf_changed) {
         $lieferadresse = $this->params->postCheckbox('lieferadresse');
         $lf_anrede     = $this->params->postString('lf_anrede');
         $lf_vorname    = $this->db->escape($this->params->postString('lf_vorname'));
         $lf_nachname   = $this->db->escape($this->params->postString('lf_nachname'));
         $lf_firma      = $this->db->escape($this->params->postString('lf_firma'));
         $lf_postnr     = $this->params->postString('lf_postnr');
         $lf_adresse    = $this->db->escape($this->params->postString('lf_adresse'));
         $lf_hausnr     = $this->params->postString('lf_hausnr');
         $lf_plz        = $this->params->postString('lf_plz');
         $lf_ort        = $this->db->escape($this->params->postString('lf_ort'));
         $lf_buland     = $this->db->escape($this->params->postString('lf_buland'));
         $lf_staat      = $this->params->postString('lf_staat');
         $lf_staat2     = $this->db->escape($this->params->postString('lf_staat2'));

         if ($sql) {
            $sql .= ", ";
         }

         if ($lf_anrede && $lf_nachname && $lf_vorname && $lf_adresse && $lf_ort) {
            $sql .="lieferadresse = '$lieferadresse',
                    lf_anrede     = '$lf_anrede',
                    lf_vorname    = '$lf_vorname',
                    lf_nachname   = '$lf_nachname',
                    lf_firma      = '$lf_firma',
                    lf_postnr     = '$lf_postnr',
                    lf_adresse    = '$lf_adresse',
                    lf_hausnr     = '$lf_hausnr',
                    lf_plz        = '$lf_plz',
                    lf_ort        = '$lf_ort',
                    lf_buland     = '$lf_buland',
                    lf_staat      = '$lf_staat',
                    lf_staat2     = '$lf_staat2'";
         }
         else {
            // Unvollständige Adresse
            // $sql .="lieferadresse = 'n', lf_vorname = '', lf_nachname = '', lf_firma = '', lf_adresse = '', lf_plz = '', lf_ort = '', lf_staat = ''";
            // vorerst immer speichern
            $sql .="lieferadresse = '$lieferadresse',
                    lf_anrede     = '$lf_anrede',
                    lf_vorname    = '$lf_vorname',
                    lf_nachname   = '$lf_nachname',
                    lf_firma      = '$lf_firma',
                    lf_postnr     = '$lf_postnr',
                    lf_adresse    = '$lf_adresse',
                    lf_hausnr     = '$lf_hausnr',
                    lf_plz        = '$lf_plz',
                    lf_ort        = '$lf_ort',
                    lf_buland     = '$lf_buland',
                    lf_staat      = '$lf_staat',
                    lf_staat2     = '$lf_staat2'";
         }
      }

      if ($bank_changed) {
         $bank_inhaber = $this->db->escape($this->params->postString('bank_inhaber'));
         $bank_name    = $this->db->escape($this->params->postString('bank_name'));
         $bank_iban    = $this->params->postString('bank_iban');
         $bank_bic     = $this->params->postString('bank_bic');

         if ($sql) {
            $sql .= ", ";
         }

         $sql .= "bank_inhaber = '$bank_inhaber',
                  bank_name    = '$bank_name',
                  bank_iban    = '$bank_iban',
                  bank_bic     = '$bank_bic'";
      }

      $this->db->query("UPDATE #__rechnung SET ".$sql." WHERE id = $id");
   }

   // Adresse auf Änderung testen
   // 28.02.2019
   private function _adrChanged() {
      if ($this->params->postString('cs_adresse') == '') {
         return false;
      }

      // Test, ob Adressdaten geändert
      $anrede   = $this->params->postString('anrede');
      $vorname  = $this->params->postString('vorname');
      $nachname = $this->params->postString('nachname');
      $firma    = $this->params->postString('firma');
      $ustid    = $this->params->postString('ustid');
      $adresse  = $this->params->postString('adresse');
      $hausnr   = $this->params->postString('hausnr');
      $plz      = $this->params->postString('plz');
      $ort      = $this->params->postString('ort');
      $buland   = $this->params->postString('buland');
      $telefon  = $this->params->postString('telefon');
      $staat    = $this->params->postString('staat');
      $staat2   = $this->params->postString('staat2');
      $email    = $this->params->postString('email');

      $md = md5($anrede . $vorname . $nachname . $firma . $ustid . $adresse . $hausnr. $plz . $ort . $buland . $telefon . $staat. $staat2 . $email);

      if ($this->params->postString('cs_adresse') != $md) {
         return true;
      }
      return false;
   }

   // Lieferadresse auf Änderung testen
   // 28.02.2019
   private function _lfChanged() {
      if ($this->params->postString('cs_lieferung') == '') {
         return false;
      }

      // Test, ob Lieferdaten geändert
      $lieferadresse = $this->params->postCheckbox('lieferadress');
      $lf_anrede     = $this->params->postString('lf_anrede');
      $lf_vorname    = $this->params->postString('lf_vorname');
      $lf_nachname   = $this->params->postString('lf_nachname');
      $lf_firma      = $this->params->postString('lf_firma');
      $lf_postnr     = $this->params->postString('lf_postnr');
      $lf_adresse    = $this->params->postString('lf_adresse');
      $lf_hausnr     = $this->params->postString('lf_hausnr');
      $lf_plz        = $this->params->postString('lf_plz');
      $lf_ort        = $this->params->postString('lf_ort');
      $lf_buland     = $this->params->postString('lf_buland');
      $lf_staat      = $this->params->postString('lf_staat');
      $lf_staat2     = $this->params->postString('lf_staat2');

      $md = md5($lieferadresse. $lf_anrede . $lf_vorname . $lf_nachname . $lf_firma . $lf_postnr . $lf_adresse . $lf_hausnr . $lf_plz . $lf_ort . $lf_buland . $lf_staat . $lf_staat2);

      if ($this->params->postString('cs_lieferung') != $md) {
         return true;
      }

      return false;
   }

   // Bankdaten auf Änderung testen
   // 28.02.2019
   private function _bankChanged() {
      if ($this->params->postString('cs_bank') == '') {
         return false;
      }

      // Test, ob Lieferdaten geändert
      $bank_inhaber = $this->params->postString('bank_inhaber');
      $bank_name    = $this->params->postString('bank_name');
      $bank_iban    = $this->params->postString('bank_iban');
      $bank_bic     = $this->params->postString('bank_bic');

      if ($this->params->postString('cs_bank') != md5($bank_inhaber . $bank_name . $bank_iban . $bank_bic)) {
         return true;
      }

      return false;
   }

   // Test, ob Änderung bei Versand. Rabatt, Gewerbe, Gutschein, Gebühren Versandart
   // 28.02.2019
   private function _summenChanged() {
      if ($this->params->postString('cs_artsummen') == '') {
         return false;
      }

      // Test, ob Daten zur Preisberechnung geändert wurden
      $versand        = sprintf('%0.2f', $this->params->postFloat('porto'));
      $zahlart_add    = sprintf('%0.2f', $this->params->postFloat('zahlart_add'));
      $rabatt         = sprintf('%0.2f', $this->params->postFloat('rabatt'));
      $rabatt_prozent = sprintf('%0.2f', $this->params->postFloat('rabatt'));
      $gutschrift     = sprintf('%0.2f', (float)$this->params->postFloat('gutschein'));
      $gewerbe        = $this->params->postInt('gewerbe');

      $md = md5(str_replace('.', ',', $versand) . str_replace('.', ',', $zahlart_add) . str_replace('.', ',', $rabatt) . str_replace('.', ',', $gutschrift) . $gewerbe);

      if ($this->params->postString('cs_artsummen') != $md) {
         return true;
      }

      return false;
   }

   // Liste sortiert ausgeben (und Einstellung in DB speichern)
   // 05.12.2018
   private function _sort() {
      $_SESSION['bestell_sort'] = $this->params->postInt('sort');
      $_SESSION['bestell_dir']  = $this->params->postString('dir');

      // Über Session-Ende speichern
      Helper::setData('bestell_sort', $_SESSION['bestell_sort']);
      Helper::setData('bestell_dir', $_SESSION['bestell_dir']);

      // Portal: für einzelnen User
      if ($this->user_id) {
         header("Content-type: application/json; charset=utf-8");
         echo json_encode(['status' => 'ok', 'inhalt' => $this->liste($this->user_id), 'pager' => $this->getCounter()]);
      }

      else {
         header("Content-type: application/json; charset=utf-8");
         echo json_encode(['status' => 'ok', 'inhalt' => $this->liste(), 'pager' => $this->getCounter()]);
      }

      return;
   }

   // Bewertungs-Email / Extern aufgerufen
   // 28.02.2019
   private function setBewertung($email, $id) {
      if (defined('CONF_BEWERTUNG_MODE') && CONF_BEWERTUNG_MODE == 'rechnung') {
         $zeitraum = 7;

         if (defined('CONF_BEWERTUNG')) {
            $zeitraum = CONF_BEWERTUNG;
         }
         $sql = "INSERT INTO #__bewertung SET best_id = $id, datum = NOW() + INTERVAL ".$zeitraum." DAY, email = '$email' ON DUPLICATE KEY UPDATE datum = NOW() + INTERVAL ".$zeitraum." DAY";
         $this->db->query($sql);
      }
   }

   // Bewertungs-Email löschen / Extern aufgerufen
   // 28.02.2019
   private function delBewertung($id) {
      $sql = "DELETE FROM #__bewertung WHERE best_id = $id";
      $this->db->query($sql);
   }

   // Lieferdatum speichern
   // 28.02.2019
   private function _saveLieferdatum($re_id, $lieferdatum) {
      $sql = "UPDATE #__rechnung SET lieferdatum = '".Helper::datumSql($lieferdatum)."' WHERE id = $re_id";
      $this->db->query($sql);
      return;
   }

   // Zahlungsart speichern
   // 28.02.2019
   private function _saveZahlart($re_id, $zahlart) {
      $sql = "UPDATE #__rechnung SET zahlungsart = $zahlart WHERE id = $re_id";
      $this->db->query($sql);
      return;
   }

   // Zahlungsdatum speichern
   // 28.02.2019
   private function _saveZahlungdatum($re_id, $zahlungdatum) {
      $sql = "UPDATE #__rechnung SET zahlungdatum = '".Helper::datumSql($zahlungdatum)."' WHERE id = $re_id";
      $this->db->query($sql);
      return;
   }

   // Lieferschein erstellen und hochladen
   // 28.02.2019
   private function _sendLieferschein($re_id, $lieferdatum) {
      $this->_saveLieferdatum($re_id, $lieferdatum);
      $pdf = Control::getPdf();
      $pdf->makePdf($re_id, 'lieferschein');
      return;
   }

   // Paketschein erstellen
   // 28.02.2019
   private function _sendPaket($re_id, $dienst) {
      $pdf = Control::getPdfPaket();

      if ($dienst == 'dhl') {
         $pdf->paketDHL($re_id);
      }

      if ($dienst == 'hermes') {
         $pdf->paketHermes($re_id);
      }

      if ($dienst == 'gls') {
         $pdf->paketGLS($re_id);
      }

      if ($dienst == 'dpd') {
         $pdf->paketDPD($re_id);
      }

      return;
   }

   // Versandaufkleber als PDF ausgeben
   // 28.02.2019
   private function _sendEtikett($re_id, $mode) {
      $haendler_suffix = '';
      // ReId als array speichern
      $print_etikett_data = [$re_id];

      if (defined('CONF_MODULE_PORTAL') && isset($_SESSION['haendler_id'])) {
         $haendler_suffix = '_'.$_SESSION['haendler_id'];
      }

      // gespeicherte ReIds lesen und aktuelle hinzufügen
      if ($mode == 'save' || $mode == 'printall') {
         $test = Helper::getData('print_etikett_data'.$haendler_suffix);
         if ($test != '') {
            $print_etikett_data = explode(';', $test);

            if ($re_id != '') {
               $print_etikett_data[] = $re_id;
            }
         }
      }

      // Speichern und Ende
      if ($mode == 'save') {
         $anz_etiketten = count($print_etikett_data);
         Helper::setData('print_etikett_data'.$haendler_suffix, implode(';', $print_etikett_data));

         echo json_encode(['status' => 'ok', 'option' => 'Sammel-Aufkleber ('.$anz_etiketten.') drucken', 'msg' => 'Etikett gespeichert '.$anz_etiketten.' Etiketten']);
         exit;
      }

      // Rechnungen im Array drucken
      $pdf = Control::getPdfPaket();
      $pdf->etikett($print_etikett_data);

      if ($mode == 'printall') {
         Helper::setData('print_etikett_data'.$haendler_suffix, '');
      }

      return;
   }

   // Printerkonfiguration (Popup) speichern
   // 28.02.2019
   private function _savePrinterconfig() {
      $haendler_suffix    = '';

      if (defined('CONF_MODULE_PORTAL') && isset($_SESSION['haendler_id'])) {
         $haendler_suffix = '_'.$_SESSION['haendler_id'];
      }

      $print_dhl_left        = $this->params->postInt('print_dhl_left');
      $print_dhl_top         = $this->params->postInt('print_dhl_top');

      $print_hermes_left     = $this->params->postInt('print_hermes_left');
      $print_hermes_top      = $this->params->postInt('print_hermes_top');

      $print_dpd_left        = $this->params->postInt('print_dpd_left');
      $print_dpd_top         = $this->params->postInt('print_dpd_top');
      $print_dpd_land        = $this->params->postString('print_dpd_land');
      $print_dpd_klasse      = $this->params->postString('print_dpd_klasse');

      $print_gls_left        = $this->params->postInt('print_gls_left');
      $print_gls_top         = $this->params->postInt('print_gls_top');
      $print_gls_inhalt      = $this->params->postString('print_gls_inhalt');
      $print_gls_klasse      = $this->params->postString('print_gls_klasse');

      $print_etikett_left    = $this->params->postInt('print_etikett_left');
      $print_etikett_top     = $this->params->postInt('print_etikett_top');
      $print_etikett_dirup   = $this->params->postCheckbox('print_etikett_dirup');
      $print_etikett_x       = $this->params->postInt('print_etikett_x');
      $print_etikett_y       = $this->params->postInt('print_etikett_y');
      $print_etikett_offsetx = $this->params->postInt('print_etikett_offsetx');
      $print_etikett_offsety = $this->params->postInt('print_etikett_offsety');
      $print_etikett_spalten = $this->params->postInt('print_etikett_spalten');
      $print_etikett_zeilen  = $this->params->postInt('print_etikett_zeilen');

      Helper::setData('print_dhl_left'.$haendler_suffix, $print_dhl_left);
      Helper::setData('print_dhl_top'.$haendler_suffix, $print_dhl_top);

      Helper::setData('print_hermes_left'.$haendler_suffix, $print_hermes_left);
      Helper::setData('print_hermes_top'.$haendler_suffix, $print_hermes_top);

      Helper::setData('print_dpd_left'.$haendler_suffix, $print_dpd_left);
      Helper::setData('print_dpd_top'.$haendler_suffix, $print_dpd_top);
      Helper::setData('print_dpd_land'.$haendler_suffix, $print_dpd_land);
      Helper::setData('print_dpd_klasse'.$haendler_suffix, $print_dpd_klasse);

      Helper::setData('print_gls_left'.$haendler_suffix, $print_gls_left);
      Helper::setData('print_gls_top'.$haendler_suffix, $print_gls_top);
      Helper::setData('print_gls_inhalt'.$haendler_suffix, $print_gls_inhalt);
      Helper::setData('print_gls_klasse'.$haendler_suffix, $print_gls_klasse);

      Helper::setData('print_etikett_left'.$haendler_suffix, $print_etikett_left);
      Helper::setData('print_etikett_top'.$haendler_suffix, $print_etikett_top);
      Helper::setData('print_etikett_dirup'.$haendler_suffix, $print_etikett_dirup);
      Helper::setData('print_etikett_x'.$haendler_suffix, $print_etikett_x);
      Helper::setData('print_etikett_y'.$haendler_suffix, $print_etikett_y);
      Helper::setData('print_etikett_offsetx'.$haendler_suffix, $print_etikett_offsetx);
      Helper::setData('print_etikett_offsety'.$haendler_suffix, $print_etikett_offsety);
      Helper::setData('print_etikett_spalten'.$haendler_suffix, $print_etikett_spalten);
      Helper::setData('print_etikett_zeilen'.$haendler_suffix, $print_etikett_zeilen);
   }

   // Motiv-Datei hochladen
   // 04.12.2019
   private function _motivDownload() {
      $filename = (isset($this->params->params3) ? urldecode($this->params->params3) : '');
      $dir      = SHOP_PATH.'/downloads/motiv_dateien/';

      if (file_exists($dir.$filename)) {
         header('Content-Description: File Transfer');
         header('Content-Type: application/octet-stream');
         header('Content-Disposition: attachment; filename='.str_replace(' ', '-', $filename));
         header('Content-Transfer-Encoding: binary');
         header('Expires: 0');
         header('Cache-Control: must-revalidate');
         header('Pragma: public');
         header('Content-Length: ' . filesize($dir.$filename));
         echo readfile($dir.$filename);
         exit;
      }

      else {
         header('Content-Description: File Transfer');
         header('Content-Type: application/octet-stream');
         header('Content-Disposition: attachment; filename=Datei_nicht_gefunden');
         header('Content-Transfer-Encoding: binary');
         header('Expires: 0');
         header('Cache-Control: must-revalidate');
         header('Pragma: public');
         echo 'Datei nicht gefunden';
      }
   }

   // Artikel der Bestellung hinzufügen (aus Popup Artikel)
   // 28.02.2019
   private function _addArticle($re_id, $art_id) {
      // Test, ob Artikel bereits in Rechnung enthalten
      $data = $this->db->querySingleValue("SELECT id FROM #__rechnung_artikel WHERE rechnung_id = $re_id AND artikel_id = $art_id");

      if ($data) {
         return 'vorhanden';
      }

      $wk                      = [];
      $wk['art_id']            = $art_id;
      $wk['art_menge']         = 1;
      $wk['foto_sort']         = 0;
      $wk['motiv_upload_name'] = '';
      $wk['motiv_upload_text'] = '';
      $wk['configurator']      = '';
      $wk['rechner_breite']    = 0;
      $wk['rechner_hoehe']     = 0;
      $wk['rechner_tiefe']     = 0;
      $wk['rechner_mode']      = 100;
      $wk['rechner_einheit']   = '';
      $wk['wk_id']             = 0;
      $wk['foto_set']          = 0;
      $wk['foto_sort']         = 0;
      $wk['preismatrix']       = '';
      $wk['mixer']             = '';
      $wk['cat_id']            = 0;

      $kunde_lang = $this->db->querySingleValue("SELECT lang_kunde FROM #__rechnung WHERE id = $re_id");
      $test = $this->_setArticle($re_id, 'deu', $kunde_lang, $wk);

      // Artikel in DB nicht gefunden
      if ($test === false) {
         return 'notfound';
      }

      $this->_berechnen($re_id);
      return 'ok';
   }

   // Neue Bestellung erstellen
   // 28.02.2019
   private function _bestellungNeu($user_id, $haendler_id = 0) {
      $k_data        = null;
      $data          = $this->db->querySingleObject("SELECT * FROM #__users WHERE id = $user_id");

      if ($data) {
         $k_data = $data;
      }

      // Wenn kein Eintrag in DB, Default-Werte setzen
      else {
         $k_data = new \stdClass();
         $k_data->lang          = 'deu';
         $k_data->anrede        = '';
         $k_data->vorname       = '';
         $k_data->nachname      = '';
         $k_data->email         = '';
         $k_data->telefon       = '';
         $k_data->firma         = '';
         $k_data->adresse       = '';
         $k_data->hausnr        = '';
         $k_data->plz           = '';
         $k_data->ort           = '';
         $k_data->buland        = '';
         $k_data->staat         = 160;
         $k_data->staat2        = '';
         $k_data->ustid         = '';
         $k_data->lieferadresse = '';
         $k_data->lf_anrede     = '';
         $k_data->lf_vorname    = '';
         $k_data->lf_nachname   = '';
         $k_data->lf_firma      = '';
         $k_data->lf_postnr     = '';
         $k_data->lf_adresse    = '';
         $k_data->lf_hausnr     = '';
         $k_data->lf_plz        = '';
         $k_data->lf_ort        = '';
         $k_data->lf_buland     = '';
         $k_data->lf_staat      = 160;
         $k_data->lf_staat2     = '';
      }

      $user_name     = Helper::name2ascii($k_data->nachname);
      $bestellnummer = $this->db->getBestellnummer().'-'.($user_name != '' ? $user_name : 'ga');
      $gewerbe       = 1;

      // Kleingewerbe
      if ($this->params->firma['kleingewerbe'] == 'y') {
         $gewerbe = 3;
      }

      $sql = "INSERT INTO #__rechnung SET
               haendler_id      = $haendler_id,
               user_id          = ".$user_id.",
               bestellnummer    = '".$this->db->escape($bestellnummer)."',
               gewerbe          = ".$gewerbe.",
               netto            = '0',
               versand          = '0',
               versand_ust      = '0',
               rabatt           = '0',
               user_rabatt      = '0',
               gutschrift       = '0',
               steuer1          = '0',
               steuer2          = '0',
               steuer3          = '0',
               zahlart_add      = '0',
               zahlart_ust      = '0',
               gutschein_code   = '',
               gutschein_brutto = '0',
               gutschein_steuer = '0',
               zahlungsart      = '6',
               zahlungsinfo1    = '',
               zahlungsinfo2    = '',
               msg_kunde        = '', ";

      if ($haendler_id == 0) {
         $sql .= " provision = 0,";
      }

      else {
         $sql .= "         provision = (SELECT provision FROM #__haendler WHERE user_id = $haendler_id),";
      }

      // Steuersätze und Kundendaten
      $sql.= " steuersatz1   = '".$this->params->firma['tax1']."',
               steuersatz2   = '".$this->params->firma['tax2']."',
               steuersatz3   = '".$this->params->firma['tax3']."',

               lang_kunde    = '".$k_data->lang."',
               anrede        = '".$k_data->anrede."',
               vorname       = '".$this->db->escape($k_data->vorname)."',
               nachname      = '".$this->db->escape($k_data->nachname)."',
               email         = '".$this->db->escape($k_data->email)."',
               telefon       = '".$this->db->escape($k_data->telefon)."',
               firma         = '".$this->db->escape($k_data->firma)."',
               adresse       = '".$this->db->escape($k_data->adresse)."',
               hausnr        = '".$this->db->escape($k_data->hausnr)."',
               plz           = '".$this->db->escape($k_data->plz)."',
               ort           = '".$this->db->escape($k_data->ort)."',
               buland        = '".$this->db->escape($k_data->buland)."',
               staat         = '".$k_data->staat."',
               staat2        = '".$k_data->staat2."',
               ustid         = '".$this->db->escape($k_data->ustid)."',
               lieferadresse = '".$k_data->lieferadresse."',
               lf_anrede     = '".$k_data->lf_anrede."',
               lf_vorname    = '".$this->db->escape($k_data->lf_vorname)."',
               lf_nachname   = '".$this->db->escape($k_data->lf_nachname)."',
               lf_firma      = '".$this->db->escape($k_data->lf_firma)."',
               lf_postnr     = '".$this->db->escape($k_data->lf_postnr)."',
               lf_adresse    = '".$this->db->escape($k_data->lf_adresse)."',
               lf_hausnr     = '".$this->db->escape($k_data->lf_hausnr)."',
               lf_plz        = '".$this->db->escape($k_data->lf_plz)."',
               lf_ort        = '".$this->db->escape($k_data->lf_ort)."',
               lf_buland     = '".$this->db->escape($k_data->lf_buland)."',
               lf_staat      = '".$k_data->lf_staat."',
               lf_staat2     = '".$k_data->lf_staat2."',
               bank_name     = '',
               bank_inhaber  = '',
               bank_iban     = '',
               bank_bic      = '',
               widerruf      = '1'";

      if (!$this->db->query($sql)) {
         return false;
      }

      $last_id = $this->db->getNewId();
      return $last_id;
   }

   // Neukunde in Stammkunde ändern (bei Re-Erstellen)
   // 28.02.2019
   private function _checkRole($re_id) {
      if (!defined('CONF_CHANGE_STATUS') || CONF_CHANGE_STATUS == true) {
         $user_id = (int)$this->db->querySingleValue("SELECT user_id FROM #__rechnung WHERE id = $re_id");

         if ($user_id > 1) {
            $role = (int)$this->db->querySingleValue("SELECT role FROM #__users WHERE id = $user_id");

            if ($role == 9) {
               $this->db->query("UPDATE #__users SET role = 10 WHERE id = $user_id");
            }
         }
      }
   }

   // Anzahl gespeicherter Etiketten (Anzeige Selectbox Lieferschein)
   // 28.02.2016
   private function _getAnzEtiketten() {
      // ReIds als array gespeichert
//      $haendler_suffix = '';

//      if (defined('CONF_MODULE_PORTAL') && isset($_SESSION['haendler_id'])) {
//         $haendler_suffix = '_'.$_SESSION['haendler_id'];
//      }

      $data = Helper::getData('print_etikett_data');

      if ($data == '') {
         return 0;
      }

      $etiketten = explode(';', $data);

      return count($etiketten);
   }

   protected function _haendlerList($haendler_id, $callback) {
      $html = '<select id="haendler_id" name="haendler_id" style="border: 1px solid #d7d7d7; width:120px; height:20px;" onchange="'.$callback.'">';
      $html .= '<option value="0"'.($haendler_id == 0 ? ' selected="selected"' : '').'>alle</option>';
      $haendler = $this->db->queryAllObjects("SELECT h.user_id, h.haendler_nr, h.website FROM #__haendler AS h, #__users AS u  WHERE h.user_id = u.id AND u.gesperrt != 'y'");

      for ($i = 0; $i < (is_array($haendler) ? count($haendler) : 0); $i++) {
         $html .= '<option value="'.$haendler[$i]->user_id.'"'.($haendler_id == (int)$haendler[$i]->user_id ? ' selected="selected"' : '').'>'.$haendler[$i]->haendler_nr.' '.str_replace(['http://', 'https://'], '', $haendler[$i]->website).'</option>';
      }
      $html .= '</select>';
      return $html;
   }

   private function _getHaendler($haendler_id) {
      $data = $this->db->querySingleObject("SELECT u.*, h.* FROM #__users AS u, #__haendler AS h WHERE u.id = $haendler_id AND u.id = h.user_id");
      return $data;
   }

   // Suche in DB während Eingabe
   private function XXXsuchen() {
      $lang = $this->params->selected_lang;
      $searchstring = $this->params->postString('search', '', 'sql');
      $html = '';
      $sql  = "SELECT id, nachname, vorname, firma, email, bestellnummer
                 FROM #__rechnung
              WHERE deleted = 'n'
                 AND (bestellnummer LIKE '$searchstring%'
                    OR nachname LIKE '$searchstring%'
                    OR firma LIKE '$searchstring%'
                    OR email LIKE '$searchstring%')";
      if (defined('CONF_MODULE_PORTAL') && $_SESSION['haendler'] == 'y') {
         $sql .= " AND haendler_id = ".(int)$this->params->user_id;
      }

      $sql .= " LIMIT 0, 20";

      if ($this->db->query($sql)) {
         while ($data = $this->db->getObject()) {
            $html .= "<div class='search-list' onclick='Royalart.bestellungFind($data->id, 0);'>$data->nachname, $data->vorname, $data->bestellnummer, $data->email</div>";
         }
      }
      if ($html =='') {
         $html = 'not found';
      }

      $html .= "<div class='searchclose' onclick=\"this.parentNode.style.display=('none');\">".$this->text->get('button', 'schliessen', 'deu')."</div>";
      return $html;
   }

   // Artikel-Menge korrigieren
   private function XXXmenge($id, $menge) {
      // Artikelmenge korrigieren
      if ($this->params->firma['lager_abziehen'] == 'y') {
         $sql = "UPDATE #__articles SET menge = (menge + $menge) WHERE id = $id";
         $query = $this->db_extern->query($sql);
      }
   }
}
