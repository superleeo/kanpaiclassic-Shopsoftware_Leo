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

class KANPAICLASSIC_tools
{
   private $db     = null;
   private $params = null;
   private $text = null;

   function __construct() {
      $this->db     = Control::getDb();
      $this->params = Control::getParams();
      $this->text   = Control::getText();
   }


   // Einzige Function, die aufgerufen werden darf
   //
   public function getContent() {
//      $func = $this->params->func;
      switch($this->params->func) {
/* ********** Einstellungen ********** */
         // Tools / Funktionen speichern
         // 05.03.2019
         case 'save':
            $this->_save();

            header('Location: '.ADMIN_URL_IDX.'/tools');
            break;

/* ********** Gutscheine ********** */
         // Gutscheine speichern
         // 01.04.2019
         case 'gutscheineSave':
            $this->_gutscheineSave();
            break;

         // Gutschein senden, Status wird in Datei geschrieben
         case 'gutscheinSend':
            $this->_gutscheinSend();
            break;

         // Status für gutscheinSend aus Datei zurückgeben
         case 'gutscheinStatus':
            $datei = SHOP_PATH.'/tmp/gutschein_status.txt';

            if (file_exists($datei)) {
               $fh = fopen($datei, 'r');
               echo fread($fh, filesize($datei));
               fclose($fh);
            }

            else { // AJAX stoppen
               echo json_encode(['status' => 'stop', 'msg' => 'Status konnte nicht festgestellt werden']);
            }

            break;

         /* ********** Modul Print-Gutscheien ********** */
         // Print-Gutscheien speichern
         // 07.03.2019
         case 'gutscheinePrintSave':
            require_once SHOP_PATH.'/classes/modules/gutscheine_print/gutscheine_print.module.php';
            $gs_print = new KANPAICLASSIC_modulGutscheinePrint();
            $gs_print->save();
            $gs_data  = $gs_print->load();

            $html = '';
            require_once SHOP_PATH.'/classes/modules/gutscheine_print/gutscheine_print.tpl.php';

            exit (json_encode(['status' => 'ok', 'html' => $html]));
            break;

         // Einzelnen Print-Gutscheien löschen
         // 07.03.2019
         case 'gutscheinePrintDel':
            require_once SHOP_PATH.'/classes/modules/gutscheine_print/gutscheine_print.module.php';
            $gs_print = new KANPAICLASSIC_modulGutscheinePrint ();
            $gs_print->delete($this->params->postInt('gs_print_del'));

            echo json_encode(['status' => 'ok']);
            exit;
            break;

/* ********** Schnitstellen ********** */
         // Exportieren-Parameter vor Export setzen
         // 05.04.2019
         case 'exportParams':
            $_SESSION['export_template'] = $this->params->postString('export');
            $_SESSION['export_param1']   = $this->params->postString('param1');
            $_SESSION['export_param2']   = $this->params->postString('param2');
            $_SESSION['export_param3']   = $this->params->postString('param3');
            exit;
            break;

         // Artikel Exporieren / Download (FLOW, XML CSV mit/ohne html, GX2, DynCSDV, Lager, Newsletter, Kunden
         // 05.04.2019
         case 'exportArtikel':
            $imex = Control::getImportExport();
            $imex->export($_SESSION['export_template']);
            break;

         // Artikel / Bestellungen Lexware exporieren
         // 05.04.2019
         // Buchungen EasyCashTax exporieren
         // 05.04.2019
         case 'exportEesycash':
            $imex = Control::getImportExport();
            $imex->exportBuchungen('easycash', $_SESSION['export_template'], ($_SESSION['export_param1'] == 'on' ? 'y' : 'n'));
            break;

         // Buchungen DATEV exporieren
         // 05.04.2019
         case 'exportDatev':
            $imex = Control::getImportExport();
            $imex->exportBuchungen('datev', $_SESSION['export_templat'], ($_SESSION['export_param1'] == 'on' ? 'y' : 'n'));
            break;

         case 'importArtikel':
            $imex = Control::getImportExport();
            // shop, overwrite, catname, handler_id
            $imex->importArtikel($this->params->postString('param1'), $this->params->postString('param12', 'y'), $this->params->postString('param13', 'y'), $this->params->postInt('param5'));
            break;

         case 'export_obadja':
            $imex = Control::getImportExport();
            $imex->exportObadja('portal', $this->params->postString('modeobadja'));
            break;

         case 'googleExport':
            $imex = Control::getImportExport();
            $imex->googleExport();



         case 'einsashopImport':
            $imex = Control::getImportExport();
            $imex->einsashopImport($this->params->postString('cronjob_url'), $this->params->postString('cronjob_overwrite'), $this->params->postCheckbox('cronjob_images'), $this->params->postInt('cronjob_haendler_id'));
            break;

         // Alle Bestellungen löschen
         case 'deleteBestellungen':
            $this->_deleteBestellungen();
            exit(json_encode(['status' => 'ok', 'html' => '']));
            break;

         // Alle Artikel löschen (Shop)
         case 'allArticlesDelete':
            $imex = Control::getImportExport();
            $imex->allArticlesDelete();
            break;

         // Alle Artikel eines  Händlers löschen (Portal)
         case 'deleteArticlesHaendler':
            $imex = Control::getImportExport();
            $imex->allArticlesDeleteHaendler($this->params->postInt('haendler_id'));
            break;

         /* ********** Modul DHL-Händler ********** */
         case 'dhlGewicht':
            $this->_dhlSavegewicht();
            exit(json_encode(['status' => 'ok']));
            break;

         // Zugangsparameter DHL speichern
         case 'dhlSave':
            $this->_dhlSaveparams();
            echo json_encode(['status' => 'ok']);
            break;

         // Rechnungen nach Datum auswählen (Tag)
         case 'dhlDatum':
            $dhl       = Control::getModuleDhlHaendler();
            $opt_array = $dhl->getBestellungenByDate($this->params->postString('dhl_datum'));

            echo json_encode(['status' => 'ok', 'start' => $opt_array['start'], 'ende' => $opt_array['ende']]);
            break;

         // Labelerstellung starten
         case 'dhlPrintlabel':
            $this->_dhlPrintlabel();
            break;

         //
         case 'dhlPdf':
            $dhl = Control::getModuleDhlHaendler();
            $dhl->dhlzip();
            break;

         // Staus der Label-Erstellung abfragen (via AJAX-Poll)
         case 'dhlLabelstatus':
            $dhl = Control::getModuleDhlHaendler();
            $dhl->labelstatus();
            break;

         // Labelerstellung abbrechen
         case 'dhlAbort':
            $dhl = Control::getModuleDhlHaendler();
            $dhl->dhlabort();
            break;

         /* ********** Modul Wiso ********** */
         // 13.03.2019
         case 'meinBueroSave':
            $this->meinBueroSave();
            break;

         case 'orgamaxSave':
            $this->orgamaxSave();
            break;

         case 'cleanOrders':

             $success = $this->cleanOrders();

             if($success == true)
                 exit(json_encode(['status' => 'ok', 'html' => '']));
             else{
                 exit(json_encode(['status' => 'error', 'html' => '']));
             }
             break;

         /* ********** Google-Shopping ********** */
         case 'saveGoogle':
            $this->db->query("UPDATE `#__firma` SET schnittstellen = '".$this->params->postCheckbox('google_shopping')."'");

            exit(json_encode(['status' => 'ok']));
            break;

         /* ********** Modul Ebay ********** */
         case 'saveEbay':
            $this->db->query("UPDATE `#__firma` SET ebay_api = '".$this->params->postCheckbox('ebay_api')."'");

            exit(json_encode(['status' => 'ok']));
            break;

         /* ********** Modul Amazonorders ********** */
         case 'saveAmazonOrders':
            $amazon_orders = Control::getModuleAmazonorders();
            $amazon_orders->saveParams();
            $html = $amazon_orders->getTools();

            echo json_encode(['status' => 'ok', 'html' => $html]);
            exit;
            break;

         /* ********** Modul Billbee ********** */
         case 'saveBillbee':
            $billbee = Control::getModuleBillbee();
            $billbee->saveTools();
            exit;
            break;

/* ********** Foto-Shop ********** */
         /* ********** Modul Foto ********** */
         case 'saveFotodata':
            $this->saveFotoData();
            break;

         case 'wasserzeichenDelete':
            $this->_wasserzeichenDel();
            break;

         case 'wasserzeichenUpload':
            // Antwort an iFrame
            $this->_wasserzeichenUpload();
            break;

         case 'cronClean':
            $this->db->query("TRUNCATE #__cronjobs");
            $this->db->query("TRUNCATE #__cron_articles");
            $this->db->query("TRUNCATE #__cron_crash");
            $this->db->query("DROP TABLE IF EXISTS #__articles_foto_tmp");

            exit(json_encode(['status' => 'ok', 'msg' => 'Alle Cronjobs wurden gelöscht']));
            break;

/* ********** Rabatte ********** */
         case 'rabatteSave':
            $this->_rabatteSave();
            break;

/* ********** Backup ********** */
         // 18.05.2019
         case 'dbBackup':
            $this->dbBackup();
            break;

         // 18.05.2019
         case 'shopBackup':
            $this->shopBackup();
            break;

         case 'del_db_backup':
            $this->deleteBackup($this->params->add_params[0], 'db');
            header('location: '.ADMIN_URL_IDX.'/toolsBackup');
            break;

         case 'del_shop_backup':
            $this->deleteBackup($this->params->add_params[0], 'shop');
            header('location: '.ADMIN_URL_IDX.'/toolsBackup');
            break;

         case 'up_db_backup':
            $this->fileDownload($this->params->add_params[0], 'db');
            break;

         case 'up_shop_backup':
            $this->fileDownload($this->params->add_params[0], 'shop');
            break;

         case 'haendlerbundSave':
            require_once SHOP_PATH.'/classes/modules/rechtstexte/haendlerbund.module.php';

            $haendlerbund = new \KANPAICLASSIC\KANPAICLASSIC_modulHaendlerbund();
            $haendlerbund->save();
            break;

         // Tools - Funktionen
         default:
            if (!defined('TOOLS_TEMPLATE')) {
               define ('TOOLS_TEMPLATE', 'toolsFunktionen');
            }

            $this->printTools();
            break;
      }
   }

   // Seite ausgeben
   private function printTools() {
      $overwrite  = $this->params->postString('overwrite', 'y', 'sql');
      $catname    = $this->params->postString('catname', 'n', 'sql');
      $data       = $this->db->querySingleObject("SELECT `staffelpreise`, `grundeinheit`, `downloads`, `schnittstellen`, `ebay_api`,
                                                         `gast_aktiv`, `statistik`, gutschein_aktiv, bonusprogramm_aktiv, bonusprogramm_prozent, activate_voucher, newsletter_footer, show_coupon, ean_check, telefon_aktiv
                                                  FROM #__firma WHERE `id` = 1");
      $export     = $this->_getExport();
      $gutscheine = [];

      if (TOOLS_TEMPLATE == 'toolsGutscheine') {
         // Gutscheine auslesen
         $gutschein  = $this->db->queryAllObjects("SELECT * FROM #__gutscheine ORDER BY gutschein_id");

         // Index mit 1 beginnen
         for ($i = 1; $i < 6; $i++) {
            $gutscheine[$i] = $gutschein[$i - 1];
         }
      }

      include 'templates/'.TOOLS_TEMPLATE.'.tpl.php';
      return;
   }

   // Tools speichern
   // 05.03.2019
   private function _save() {
      $sql = "UPDATE `#__firma` SET
                     `staffelpreise`       = '".$this->params->postCheckbox('staffelpreise')."',
                     `downloads`           = '".$this->params->postCheckbox('downloads')."',
                     `gast_aktiv`          = '".$this->params->postCheckbox('gast_aktiv')."',
                     `telefon_aktiv`       = '".$this->params->postCheckbox('telefon_aktiv')."',
                     `ean_check`           = '".$this->params->postCheckbox('ean_check')."',

                     `schnittstellen`      = '".$this->params->postCheckbox('schnittstellen')."'  /* Google-Shopping */
              WHERE `id` = 1";
      $this->db->query($sql);

      Helper::setData('paypal_xtn', $this->params->postCheckbox('paypal_xtn'));
      Helper::setData('paypal_danke', $this->params->postCheckbox('paypal_danke'));
      Helper::setData('seo_utf8', $this->params->postCheckbox('seo_utf8'));

      if (defined('CONF_MODULE_AMAZONORDERS')) {
         Helper::setData('amazonorders_enabled', $this->params->postCheckbox('amazonorders_enabled'));
      }

      $this->params->getFirmData();

      // SEO erzwingen über Datei, da bei Fehler sonst keine Möglichkeit mehr existiert, dies rückgängig zu machen
      if ($this->params->postCheckbox('ssl_force') == 'y') {
         $url = SHOP_URL;

         if (substr(SHOP_URL, 0, 5) != 'https') {
            $url = str_replace('http', 'https', ADMIN_URL);
         }

         $test = @file_get_contents($url.'/index.php');

         if ($test === false) {
            $_SESSION['ssl_error'] = true;
         }

         else {
            file_put_contents(SHOP_PATH.'/force_ssl', '');
         }
      }

      else {
         @unlink(SHOP_PATH.'/force_ssl');
      }

      return;
   }

   private function _gutscheineSave() {
      $html = '';
      // Gutscheine

      \KANPAICLASSIC\Helper::setData('sonderpreis_ausschliessen', $this->params->postCheckbox('sonderpreis_ausschliessen'));

      for ($i = 1; $i < 6; $i++) {
         $gs_code = $this->params->postString('gs_'.$i.'_code', '', 'sql');
         $gs_wert = (float)str_replace(',', '.', $this->params->postString('gs_'.$i.'_wert', '', 'sql'));


         $gs_mode = $this->params->postInt('gs_'.$i.'_mode');
         $gs_datum = $this->params->postString('gs_'.$i.'_datum', '', 'sql');

         if ($gs_datum == '-  -') {
            $gs_datum = '0000-00-00';
         }

         $gs_min = $this->params->postFloat('gs_'.$i.'_min');

         $sql = "UPDATE #__gutscheine SET
                    `code` = '$gs_code',
                    `wert` ='$gs_wert',
                    `mode` = '$gs_mode',
                    `datum` = '$gs_datum',
                    `min` = '$gs_min'
                 WHERE `gutschein_id` = ".$i;
         $this->db->query($sql);
      }

      $bonus_wert = (float)str_replace(',', '.', $this->params->postString('bonusprogramm_prozent', '', 'sql'));


      $sql = "UPDATE `#__firma` SET
                     `show_coupon`     = '".$this->params->postCheckbox('show_coupon')."',
                     `gutschein_aktiv` = '".$this->params->postCheckbox('aktiv')."',
                     `activate_voucher` = '".$this->params->postCheckbox('activate_voucher')."',
                     `bonusprogramm_aktiv` = '".$this->params->postCheckbox('bonusprogramm_aktiv')."',
                     `bonusprogramm_prozent` = '".$bonus_wert."',
                     `newsletter_footer` = '".$this->params->postCheckbox('newsletter_footer')."'
              WHERE `id` = 1";

      $this->db->query($sql);

      $data      = $this->db->querySingleObject("SELECT bonusprogramm_aktiv, bonusprogramm_prozent, gutschein_aktiv, show_coupon, activate_voucher, newsletter_footer FROM #__firma WHERE `id` = 1");
      // Gutscheine auslesen
      $gutschein = $this->db->queryAllObjects("SELECT * FROM #__gutscheine ORDER BY gutschein_id");

      // Index mit 1 beginnen
      for ($i = 1; $i < 6; $i++) {
         $gutscheine[$i] = $gutschein[$i - 1];
      }

      include_once ADMIN_PATH.'/templates/tools_gutscheine_inc.tpl.php';
      echo json_encode(['status' => 'ok', 'html' => $html, 'bonusprogramm_prozent'=>$data->bonusprogramm_prozent]);
      exit;
      return;

   }

   private function _gutscheinSend() {
      $gid   = $this->params->postInt('gid');
      $code  = $this->params->postString('code');
      $mode  = $this->params->postInt('mode');
      $wert  = $this->params->postFloat('wert');
      $datum = $this->params->postString('datum');

      // falls Datum 0 (dauerhaft)
      if ($datum == '0000-00-00' || str_replace(' ', '', trim($datum)) == '--') {
         $datum = '0000-00-00';
      }

      else {
         // Überprüfen auf Falscheingaben
         list($jahr, $monat, $tag) = explode('-', $datum);

         if (!checkdate((int)$monat, (int)$tag,(int)$jahr)) {
            echo json_encode(['status' => 'stop', 'msg' => 'Datum ist ungültig']);
            return;
         }

         $datum = sprintf('%04d-%02d-%02d', $jahr, $monat, $tag);
      }

      if ($code == '') {
         echo json_encode(['status' => 'stop', 'msg' => 'Gutschein-Code fehlt']);
         return;
      }

      if ($wert <= 0) {
         echo json_encode(['status' => 'stop', 'msg' => 'Wert ist 0 oder negativ']);
         return;
      }


      // Gutschein in DB eintragen, falls vergessen wird zu speichern
      $this->db->query("INSERT INTO #__gutscheine SET `gutschein_id` = $gid, `code`= '$code', `wert`= '$wert', `mode`= $mode, `datum`= '$datum' ON DUPLICATE KEY UPDATE `code`= '$code', `wert`= '$wert', `mode`= $mode, `datum`= '$datum'");

      // User für Email-Aktion suchen
      $data = $this->db->queryAllObjects("SELECT id, email FROM #__users WHERE newsletter = 'y' AND id > 1 AND newsletter_check = 'ok'");

      // Keine Kunden gefunden
      if (!$data) {
         echo json_encode(['status' => 'stop', 'msg' => 'Keine Kunden für Gutschein-Email angemeldet']);
         return;
      }

      $anz_mails = count($data);

      // Mails versenden
      echo json_encode(['status' => 'start', 'msg' => "$anz_mails Mails werden versendet"]);
      $mail      = Control::getMail();
      $anz_mails = count($data);
      $c         = 0;

      foreach ($data as $k => $v) {
         $c++;
         $mail->sendEmailGutschein($v->email, 'gutschein'.$gid, $code, $wert, $mode);
         $msg = "$c von $anz_mails versandt";
         $fh = fopen(SHOP_PATH.'/tmp/gutschein_status.txt', 'w');
         fwrite($fh, json_encode(['status' => 'ok', 'msg' => $msg]));
         fclose($fh);
      }

      $fh = fopen(SHOP_PATH.'/tmp/gutschein_status.txt', 'w');
      fwrite($fh, json_encode(['status' => 'stop', 'msg' => $c.' Mails wurden versendet']));
      fclose($fh);

      // Kontroll-Mail an Admin hizufügen
      $mail->sendEmailGutschein($this->params->firma['email'], 'gutschein'.$gid, $code, $wert, $mode);
      return;
   }

   // Von Google-Shopping verwendet
   // 13.03.2019
   public function getIsoList($land = '') {
      if ($land == '') {
         $land = 'DE';
      }

      $sql = "SELECT name, UPPER(domain) AS domain FROM #__laender WHERE id > 90 ORDER BY sort";
      $this->db->query($sql);

      $land_arr = [];
      while ($tmp = $this->db->getObject()) {
         $land_arr[] = $tmp;
      }

      $html  = '<span class="selectbox30"><select class="googlezustand" id="land" onchange=\'$("#g_land").val(this.value);\'>';
      for ($i = 0; $i < count($land_arr); $i++) {
         if ($land_arr[$i]->domain == $land) {
            $html .= '<option value="'.$land_arr[$i]->domain.'" selected="selected">'.$land_arr[$i]->name.'</option>';
         }
         else {
            $html .= '<option value="'.$land_arr[$i]->domain.'">'.$land_arr[$i]->name.'</option>';
         }
      }
      $html .= '</select></span>';
      return $html;
   }

   // Von Google-Shopping verwendet
   // 13.03.2019
   public function getWaehrungList() {
      $html  = '<span class="selectbox30"><select class="amazon" id="waehrung" onchange=\'$("#g_waehrung").val(this.value);\'>';
      $html .= '<option value="EUR" selected="selected">Euro</option>';
      $html .= '<option value="GBP">Pfund Sterling</option>';
      $html .= '<option value="USD">US-Dollar</option>';
      $html .= '<option value="CHF">Schweizer Franken</option>';
      $html .= '<option value="RUB">Russischer Rubel</option>';
      $html .= '</select></span>';
      return $html;
   }

   // Alle Bestelluugen löschen
   private function _deleteBestellungen() {
      $this->db->query("TRUNCATE TABLE #__rechnung");
      $this->db->query("TRUNCATE TABLE #__rechnung_artikel");

      if (defined('CONF_MODULE_AMAZONORDERS')) {
         $module = Control::getModuleAmazonorders();
         method_exists($module, 'resetData') && $module->resetData();
      }

      if (defined('CONF_MODULE_EBAYORDERS')) {
         $module = Control::getEbayOrders();
         method_exists($module, 'resetData') && $module->resetData();
      }
   }

   private function _getExport() {
      $imex = Control::getImportExport();
      return $imex->getExport();
   }

   /* ********** Modul Wiso ********** */

   /**
    * inaktiv schalten von leeren / fehlerhaften Bestellungen für WISO Steuerbüro
    * @return bool Erfolg oder Misserfolg/false (wirft Fehlermeldung nach Buttondruck)
    */
   private function cleanOrders(){


       $sql = "update #__rechnung set status = 5 WHERE status = 1 and ( user_id = '' || user_id is null || user_id = 0 || bestellnummer = '' || bestellnummer is null )";
       $this->db->query($sql);
       return true;


   }

   // Einstellungen speichern und wiso_mein_buero/inc/config.php neu erstellen
   // 13.03.2019
   private function meinBueroSave() {
      $id           = $this->params->postString('mb_id');
      $pass         = $this->params->postString('mb_pass');
      $pass_check   = $this->params->postCheckbox('mb_pass_check');
      $gesamtbrutto = $this->params->postCheckbox('mb_gesamtbrutto');

      Helper::setData('mb_id', $id);
      Helper::setData('mb_pass', $pass);
      Helper::setData('mb_pass_check', $pass_check);
      Helper::setData('mb_gesamtbrutto', $gesamtbrutto);

      if ($id == '' || ($pass_check == 'y' && $pass == '')) {
         echo json_encode(['status' => 'failed', 'msg' => 'Identifikationskennung oder Passwort leer']);
         return;
      }

      $datei  = '<'.'?php'.CR;
      $datei .= '/***************************************************************************'."\\".CR;
      $datei .= '*'.CR;
      $datei .= '*  Copyright (c) 2013 deltra Buisness Software GmbH & Co. KG'.CR;
      $datei .= '*  http://www.deltra.de'.CR;
      $datei .= '*  Zuletzt bearbeitet am: 2013-07-04'.CR;
      $datei .= '*  Zuletzt bearbeitet am: 2015-04-22 / Obadja'.CR;
      $datei .= '*'.CR;
      $datei .= '\***************************************************************************/'.CR;
      $datei .= ''.CR;
      $datei .= '/**'.CR;
      $datei .= ' * Sicherheitskonstanten'.CR;
      $datei .= ' */'.CR;

      if ($pass_check == 'y') {
         $datei .= 'define("SCHLUESSEL", "'.$pass.'"); // #Passwort'.CR;
         $datei .= 'define("VERSCHLUESSELN", "y"); '.CR;
      }

      else {
         $datei .= 'define("SCHLUESSEL", ""); // #Passwort'.CR;
         $datei .= 'define("VERSCHLUESSELN", ""); // #Algorithmus - vorgegeben'.CR;
      }

      $datei .= 'define("VEKTOR", "jxpPjWLPIlLXWxrc"); // vorgegeben - nicht ändern!'.CR;
      $datei .= 'define("OMX_AGENT", "'.$id.'"); // #Identifikationskennung'.CR;
      $datei .= 'define("SHOW_INI_WARNINGS", true); // zum Abschalten der Warnungen:    define("SHOW_INI_WARNINGS", false);'.CR;
      $datei .= 'define("GESAMTBRUTTO", '.($gesamtbrutto == 'y' ? 'true' : 'false').');'.CR;
      $datei .= '/**'.CR;
      $datei .= ' * Sicherheitskonstanten Ende'.CR;
      $datei .= ' */'.CR;
      $datei .= ''.CR;
      $datei .= '//Versionsnummer der Anbindung'.CR;
      $datei .= '$GLOBALS["VERSION"] = "18.03.28";'.CR;
      $datei .= '$GLOBALS["SETUP_SHOP"] = "individuell"; // #change_SETUP_SHOP'.CR;

      file_put_contents('../classes/modules/wiso_mein_buero/inc/config.php', $datei);
      echo json_encode(['status' => 'ok', 'msg' => 'Daten wurden gespeichert']);
      return;

   }

   private function orgamaxSave() {
      $id           = $this->params->postString('orgamax_id');
      $pass         = $this->params->postString('orgamax_pass');
      $pass_check   = $this->params->postCheckbox('orgamax_pass_check');
      $gesamtbrutto = $this->params->postCheckbox('orgamax_gesamtbrutto');

      Helper::setData('orgamax_id', $id);
      Helper::setData('orgamax_pass', $pass);
      Helper::setData('orgamax_pass_check', $pass_check);
      Helper::setData('orgamax_gesamtbrutto', $gesamtbrutto);

      if ($id == '' || ($pass_check == 'y' && $pass == '')) {
         echo json_encode(['status' => 'failed', 'msg' => 'Identifikationskennung oder Passwort leer']);
         return;
      }

      $datei  = '<'.'?php'.CR;
      $datei .= '/***************************************************************************'."\\".CR;
      $datei .= '*'.CR;
      $datei .= '*  Copyright (c) 2013 deltra Buisness Software GmbH & Co. KG'.CR;
      $datei .= '*  http://www.deltra.de'.CR;
      $datei .= '*  Zuletzt bearbeitet am: 2013-07-04'.CR;
      $datei .= '*  Zuletzt bearbeitet am: 2015-04-22 / Obadja'.CR;
      $datei .= '*'.CR;
      $datei .= '\***************************************************************************/'.CR;
      $datei .= ''.CR;
      $datei .= '/**'.CR;
      $datei .= ' * Sicherheitskonstanten'.CR;
      $datei .= ' */'.CR;

      if ($pass_check == 'y') {
         $datei .= 'define("SCHLUESSEL", "'.$pass.'"); // #Passwort'.CR;
         $datei .= 'define("VERSCHLUESSELN", "y"); '.CR;
      }

      else {
         $datei .= 'define("SCHLUESSEL", ""); // #Passwort'.CR;
         $datei .= 'define("VERSCHLUESSELN", ""); // #Algorithmus - vorgegeben'.CR;
      }

      $datei .= 'define("VEKTOR", "jxpPjWLPIlLXWxrc"); // vorgegeben - nicht ändern!'.CR;
      $datei .= 'define("OMX_AGENT", "'.$id.'"); // #Identifikationskennung'.CR;
      $datei .= 'define("SHOW_INI_WARNINGS", true); // zum Abschalten der Warnungen:    define("SHOW_INI_WARNINGS", false);'.CR;
      $datei .= 'define("GESAMTBRUTTO", '.($gesamtbrutto == 'y' ? 'true' : 'false').');'.CR;
      $datei .= '/**'.CR;
      $datei .= ' * Sicherheitskonstanten Ende'.CR;
      $datei .= ' */'.CR;
      $datei .= ''.CR;
      $datei .= '//Versionsnummer der Anbindung'.CR;
      $datei .= '$GLOBALS["VERSION"] = "18.03.28";'.CR;
      $datei .= '$GLOBALS["SETUP_SHOP"] = "individuell"; // #change_SETUP_SHOP'.CR;

      file_put_contents('../classes/modules/orgamax/inc/config.php', $datei);
      echo json_encode(['status' => 'ok', 'msg' => 'Daten wurden gespeichert']);
      return;

   }

   /* ********** Portale ********** */


   /* ********** Modul Foto ********** */
   // Verzeichnisse in /downloads einlesen (rekursiv)
   // 13.03.2019
   public function getFotoDirs($dir = '', $depth = 0) {
      $html = '';

      if ($dir == '') {
         $html .= '<option value="0">Bitte wählen ...</option>';
         $pfad = SHOP_PATH.'/downloads';
      }

      else {
         $pfad = SHOP_PATH.'/downloads/'.$dir;
      }

      $dh = opendir($pfad);
      $dir_arr = [];

      if ($dh) {
          while (false !== ($file = readdir($dh))) {
              if ($file != "." && $file != ".." && !is_file($pfad.'/'.$file)) {
                  $dir_arr[] = [$dir.'/'.$file, $file];
              }
          }

          closedir($dh);
      }

      if (count($dir_arr) > 0) {
         foreach ($dir_arr as $search) {
            $html .= '<option value="'.$search[0].'">'.str_repeat('&nbsp;', $depth * 3).$search[1].'</option>';
            $html .= $this->getFotoDirs($search[0], $depth + 1);
         }
      }

      return $html;
   }

   // Default Fotoset lesen (Tools/Foto)
   // Preise werden als netto gepeichert, aber als Brutto angezeigt
   // 01.04.2019
   public function getFotoData() {
      $module_foto = Control::getModuleFoto();
      return $module_foto->getDataDefault();
   }

   // Preise als Netto speichern
   // Hinweis: Ändern der Steuerklasse auch bei admin/articles.class.php/_saveFotoartikel
   // 01.04.2019
   public function saveFotoData() {
      $module_foto = Control::getModuleFoto();
      return $module_foto->saveDataDefault();
   }

   // Modul Foto - Wasserzeichen hochladen
   // 01.04.2019
   private function _wasserzeichenUpload() {
      // Namen aus $_FILES lesen
      $tempname = $_FILES['file']['tmp_name'];
      $uploaddir = ADMIN_PATH.'/img/wasserzeichen.png';
      move_uploaded_file($tempname, $uploaddir);

      $bild = (is_file(ADMIN_PATH.'/img/wasserzeichen.png') ? ADMIN_URL.'/img/wasserzeichen.png?'.time() : ADMIN_URL.'/img/nopic78.jpg');
      echo json_encode(['status' => 'ok', 'html' => $bild, 'target' => 'img_src']);
      exit;
   }

   // Modul Foto - Wasserzeichen löschen
   // 01.04.2019
   private function _wasserzeichenDel() {
      @unlink(ADMIN_PATH.'/img/wasserzeichen.png');

      $bild = ADMIN_URL.'/img/nopic78.jpg';
      $html ="<img src='".$bild."' alt='' />";
      echo json_encode(['status' => 'ok', 'html' => $html]);
      return;
   }

   /* ********** Modul DHL-Händler ********** */
   private function _dhlSavegewicht() {
      Helper::setData('dhl_gewicht', $this->params->postInt('dhl_gewicht'));
      Helper::setData('dhl_versicherung', $this->params->postString('dhl_versicherung'));

      return true;
   }

   private function _dhlSaveparams() {
      Helper::setData('dhl_is_ekp', $this->params->postString('dhl_is_ekp'));
      Helper::setData('dhl_is_user', $this->params->postString('dhl_is_user'));
      Helper::setData('dhl_is_sign', $this->params->postString('dhl_is_sign'));
      Helper::setData('dhl_teilnehmer', $this->params->postString('dhl_teilnehmer'));
      Helper::setData('dhl_api_version', $this->params->postInt('dhl_api_version'));

      return true;
   }

   private function _dhlPrintLabel() {
      $dhl                      = Control::getModuleDhlHaendler();
      $start_id                 = $this->params->postInt('start_id');
      $ende_id                  = $this->params->postInt('ende_id');
      $dhl_datum                = $this->params->postString('dhl_datum');
      $dhl_mode                 = $this->params->postString('dhl_mode');
      $_SESSION['dhl_laenge']   = $this->params->postInt('dhl_laenge');
      $_SESSION['dhl_breite']   = $this->params->postInt('dhl_breite');
      $_SESSION['dhl_hoehe']    = $this->params->postInt('dhl_hoehe');

      $dhl->printLabels($start_id, $ende_id, $dhl_datum, $dhl_mode);
      return;
   }

   public function deleteBackup($filename, $typ) {
      $backup_path = '';

      if ($typ == 'db') {
         $backup_path = ADMIN_PATH.'/backup/db_backup/';
      }

      else if ($typ == 'shop') {
         $backup_path = ADMIN_PATH.'/backup/shop_backup/';
      }

      if (is_file($backup_path.$filename)) {
         unlink($backup_path.$filename);
      }
   }

   public function fileDownload($filename, $typ) {
      $backup_path = '';

      if ($typ == 'db') {
         $backup_path = ADMIN_PATH.'/backup/db_backup/';
      }

      else if ($typ == 'shop') {
         $backup_path = ADMIN_PATH.'/backup/shop_backup/';
      }

      if (is_file($backup_path.$filename)) {
         header('Content-Type: application/octet-stream');
         header("Content-Transfer-Encoding: Binary");
         header("Content-disposition: attachment; filename=" . $filename);
         readfile($backup_path.$filename);
      }

      exit;
   }

   public function dbBackup() {
      $backup_path = ADMIN_PATH.'/backup';

      if (!is_dir($backup_path)) {
         mkdir($backup_path);
      }

      $backup_path = ADMIN_PATH.'/backup/db_backup';
      if (!is_dir($backup_path)) {
         mkdir($backup_path);
         copy(SHOP_PATH.'/downloads/.htaccess', $backup_path.'/.htaccess');
      }

      $backup = 'db_backup_'.date('Y_m_d_H-i').'.sql';
      $db_save = '';

      $tables = $this->db->queryAllObjects('SHOW TABLES');

      foreach($tables as $t) {
         $t     = (array)$t;
         $key   = array_keys($t);
         $table = $t[$key[0]];

         if (strpos ($table, CONF_DBPREFIX) === 0) {
            $data = $this->db->queryAllObjects("SELECT * FROM $table");

            $row2     = $this->db->querySingleValue("SHOW CREATE TABLE .$table", 1);
            $db_save .= 'DROP TABLE IF EXISTS '.$table.';';
            $db_save .= "\n\n".$row2.";\n\n";

            for ($i = 0; $i < (is_array($data) ? count($data) : 0); $i++) {
               $d = (array)$data[$i];
               $db_save .= 'INSERT INTO '.$table.' VALUES(';

               $count = count($d);
               $c     = 0;

               foreach ($d as $v) {
                  $db_save .= '"'.$this->db->escape($v).'"';
                  $c++;

                  if ($c < $count) {
                     $db_save .= ',';
                  }
               }

               $db_save .= ");\n";
            }

            $db_save .="\n\n";
         }
      }

      $zip = new \ZipArchive;
      $z = $zip->open($backup_path.'/'.$backup.'.zip', \ZipArchive::CREATE);
      if ($z === true) {
         $zip->addFromString($backup, $db_save);
         $zip->close();
      }

/*
      exec("mysqldump -u ".CONF_DBUSER." -p".CONF_DBPASS." --host ".CONF_DBHOST." --add-drop-table ".CONF_DATABASE." > ".$backup);

      if (is_file($backup) && filesize($backup) > 0) {
         exec("bzip2 $backup ".$backup.'bz2');
         @unlink($backup);
      }
*/

      header('Location: '.ADMIN_URL_IDX.'/toolsBackup/');
   }

   // Shop-Backup
   // 18.05.2019
   public function shopBackup() {
      $_SESSION['backup'] = true;
      $backup_path        = ADMIN_PATH.'/backup';

      if (!is_dir($backup_path)) {
         mkdir($backup_path);
      }

      $backup_path = ADMIN_PATH.'/backup/shop_backup';

      if (!is_dir($backup_path)) {
         mkdir($backup_path);
         copy(SHOP_PATH.'/downloads/.htaccess', $backup_path.'/.htaccess');

         $excl_file = $backup_path."/exclude.txt";
         $ausnahme  = "*tar.gz\n";
         $ausnahme .= "*tar.bz2\n";
         $ausnahme .= "*.sql.gz\n";
         $ausnahme .= "*.sql.bz2\n";
         $ausnahme .= "* /Doku\n";
         $ausnahme .= "* /!privat\n";
         $ausnahme .= "* /.svn\n";
         $ausnahme .= "* /.bak\n";
         $ausnahme .= "*Kopie*\n";
         $ausnahme .= "* /xtra_kein_shop\n";
         $ausnahme .= "*.project\n";
         $ausnahme .= "*.buildpath\n";
         $ausnahme .= "*alt*\n";

         file_put_contents($excl_file, $ausnahme);
      }

      $backup = $backup_path.'/shop_backup_'.date('Y_m_d_H-i').'.tar.bz2';
      exec("tar cjf ".$backup." -X ".$backup_path."/exclude.txt ./../");

      if (is_file($backup) && filesize($backup) > 0) {
         header('Location: '.ADMIN_URL_IDX.'/toolsBackup/');
         exit;
      }

      @unlink($backup);
      exit('Backup konnte nicht durchgführt werden');
   }

   // DB-Backup
  // 18.05.2019
   public function getDbBackups () {

      $db_backups = [];
      $backup_path = ADMIN_PATH.'/backup/db_backup';

      if(!file_exists($backup_path)){
          mkdir($backup_path);
      }

      if (is_dir($backup_path)) {
         if ($handle = opendir($backup_path)) {
            while (false !== ($file = readdir($handle))) {
               if ($file != "." && $file != ".." && $file != '.htaccess') {
                  if (is_file($backup_path.'/'.$file)) {
                     $db_backups[] = $backup_path.'/'.$file;
                  }
               }
            }

            closedir($handle);
            return $db_backups;
         }
      }

      exit("Backup konnte nicht durchgführt werden, Verzeichnis $backup_path ist nicht vorhanden und konnte nicht angelegt werden.");
   }

   public function getShopBackups () {
      $shop_backups = [];
      $backup_path = ADMIN_PATH.'/backup/shop_backup';
      if (is_dir($backup_path)) {
         if ($handle = opendir($backup_path)) {
            while (false !== ($file = readdir($handle))) {
               if ($file != "." && $file != ".." && $file != '.htaccess' && $file != 'exclude.txt') {
                  if (is_file($backup_path.'/'.$file)) {
                     $shop_backups[] = $file;
                  }
               }
            }
            closedir($handle);
         }
      }
      return $shop_backups;
   }

   /* ********** Modul Rabatte ********** */
   public function getRabatte() {
      $rabatte = Control::getModuleRabatte();
      return $rabatte->getRabatte(0);
   }

   private function _rabatteSave() {
      $rabatte = Control::getModuleRabatte();
      $rabatte->save(0);

      require_once SHOP_PATH.'/classes/modules/rabattgruppen/rabatte.tpl.php';

      exit(json_encode(['status' => 'ok', 'html' => $html]));
   }
}
