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

class KANPAICLASSIC_kunden
{
   private $email_fail = false;
   private $password_fail = false;

   // Konstruktor - Standardwerte SESSION/Sortierung setzen, falls nicht vorhanden
   function __construct() {
      $this->db = Control::getDB();
      $this->params = Control::getParams();

      // Sortierung nach
      if (!isset($_SESSION['kunden_sort'])) {
         $_SESSION['kunden_sort'] = 1;
      }

      // Sortierung auf/absteigend
      if (!isset($_SESSION['kunden_dir'])) {
         $_SESSION['kunden_dir'] = 'desc';
      }
   }


   // nach gewählter Funktion Daten bereitstellen und Template einbinden oder Rückgabe als JSON
   public function getContent() {
      switch($this->params->func) {
         // Neue Sortierung ausgeben
         // 25.12.2018
         case 'sort':
            $_SESSION['kunden_sort'] = $this->params->postInt('sort');
            $_SESSION['kunden_dir'] = $this->params->postString('dir');

            echo json_encode(['status' => 'ok', 'inhalt' => $this->_printListe()]);
            return;
            break;

        // Anzahl Artikel pro Seite ändern / Ajax - Seite wird von Ajax aktualisiert
         // 25.12.2018
         case 'count':
            $_SESSION['kunden_limit'] = $this->params->postInt('count');
            $_SESSION['kunden_seite'] = 0;
            echo json_encode(['status' => 'ok']);
            exit;
            break;

         // Anzahl Kunden / Seite
         // 25.12.2018
         case 'seite':
            $_SESSION['kunden_seite'] = $this->params->postInt('seite');
            echo json_encode(['status' => 'ok']);
            exit;
            break;

         // Liste als JSON ausgeben
         // 25.12.2018
         case 'liste':
            echo json_encode(['status' => 'ok', 'inhalt' => $this->_printListe(), 'pager' => $this->getCounter()]);
            exit;
            break;;

         // Kunde Löschen
         // 25.12.2018
         case 'delete':
            $this->delete();
            echo json_encode(['status' => 1]);
            exit;
            break;

         // Kunde Sperren
         // 25.12.2018
         case 'gesperrt':
            $this->_gesperrt();
            exit;
            break;

         // Kunde suchen während Eingabe - Nicht verwendet
         // 25.12.2018
         case 'suchen':
            echo json_encode(['status' => 'ok', 'inhalt' => $this->suchen()]);
            exit;
            break;

         // Suchen (Button) - nach ID oder Teilstring und anzeigen - Nicht verwendet
         case 'find':
            $all = $this->params->postInt('all');
            if ($all == 0) {
               $user = $this->params->postInt('search');
            }

            else {
               $user = $this->params->postString('search');
            }

            echo json_encode(['status' => 'ok', 'inhalt' => $this->_printListe($user, $all)]);
            exit;
            break;

         // Detail-Seite anzeigen
         case 'detail':
            $kunden_id = (isset($this->params->add_params[0]) ? (int)$this->params->add_params[0] : 0);
            $data      = $this->_getDetail($kunden_id);

            include 'templates/kunden_detail.tpl.php';
            break;

/* ******* Detailseite ************** */
         // Änderungen speichern
         case 'save':
            $user_id   = $this->params->postInt('user_id');
            $kunden_id = $this->_detailSave($user_id);

            header('Location: '.ADMIN_URL_IDX.'/kunden/detail/'.$kunden_id);
            exit;
            break;

         case 'checkMail':
            $this->_checkMail();
            break;

         // PW zurücksetzen und Validierungslink senden
         // 24.02.2019
         case 'forgotten':
            echo json_encode(['status' => 'ok', 'inhalt' => $this->_forgotten() ]);
            exit;
            break;

         // Nachricht senden
         // 01.03.2019
         case'nachricht':
            $this->_sendNachricht();
            break;

         // Gutschrift senden
         case 'sendGutschrift':
            $this->_sendGutschrift();
            break;

         // Persocheck setzen / löschen
         case 'alterCheck':
            $this->_alterCheck();
            break;

         // Default - Kundenliste anzeigen
         default:
            include 'templates/kunden_liste.tpl.php';
            break;
      }

      return;
   }

   // Kundenliste (sortiert) ausgegeben oder nach ID / Suchbegriff
   // 02.03.2019
   private function _printListe($search = false, $all = false) {
      $html = '';
//      $lang = $this->params->selected_lang;

      if (!defined('CONF_MODULE_PORTAL') || $_SESSION['haendler'] != 'y') {
         $sql = "SELECT u.id, u.email, u.vorname, u.nachname, u.firma, u.adresse, u.ort, u.role,
                        l.name AS staat1, u.staat, u.staat2, u.password, u.forgotten, u.gesperrt, created
               FROM #__users AS u
               LEFT JOIN #__laender AS l
               ON u.staat = l.id
               WHERE MOD(u.role, 100) > 8"; // ohne admin-user
      }
      else {
         $sql = "SELECT u.id, u.email, u.vorname, u.nachname, u.firma, u.adresse, u.ort, u.role AS role_a, kh.kundengruppe AS role,
                        l.name AS staat1, u.staat, u.staat2, u.password, u.forgotten, u.gesperrt, created
                    FROM #__users AS u
                 LEFT JOIN  #__kunde_haendler AS kh
                    ON u.id = kh.kunden_id
                 LEFT JOIN #__laender AS l
                    ON u.staat = l.id
                 WHERE kh.haendler_id = ".$_SESSION['user_id']; // ohne admin-user
      }

      if ($search) {
         if ($all == 0) {
            $sql .= " AND u.id = $search";
         }
         else {
            $sql .= " AND (u.vorname LIKE '$search%' OR u.nachname LIKE '$search%' OR u.firma LIKE '$search%')";
         }
      }

      // Sortierung
      switch ($_SESSION['kunden_sort']) {
         case "1":
            $sql .= " ORDER BY u.created ";
            break;

         case "2":
            $sql .= " ORDER BY u.nachname ";
            break;

         case "3":
            $sql .= " ORDER BY u.vorname ";
            break;

         case "4":
            $sql .= " ORDER BY u.firma ";
            break;

         case "5":
            $sql .= " ORDER BY u.adresse ";
            break;

         case "6":
            $sql .= " ORDER BY u.ort ";
            break;

         case "7":
            $sql .= " ORDER BY staat ";
            break;

         case "8":
            $sql .= " ORDER BY u.email ";
            break;

         case "9":
            if (!defined('CONF_MODULE_PORTAL') || $_SESSION['haendler'] != 'y') {
               $sql .= " ORDER BY MOD(u.role, 100) ";
            }

            else {
               $sql .= " ORDER BY kh.kundengruppe ";
            }
            break;
      }

      // Sortierung auf/absteigend
      if ($_SESSION['kunden_dir'] == 'asc') {
         $sql .= " ASC ";
      }

      else {
         $sql .= " DESC ";
      }

      if (isset($_SESSION['kunden_limit'])) {
         $limit = $_SESSION['kunden_limit'];
      }

      else {
         $limit = CONF_ART_PER_SITE;
         $_SESSION['kunden_limit'] = $limit;
      }

      if (isset($_SESSION['kunden_seite'])) {
         $seite = $_SESSION['kunden_seite'];
      }

      else {
         $seite = 0;
         $_SESSION['kunden_seite'] = $seite;
      }

      $sql .= " LIMIT " . $seite * $limit . ", $limit";

      $datas = $this->db->queryAllObjects($sql);

      if ($datas) {
         foreach ($datas as $data) {
            $staat_name         = Helper::getStaatName($data->staat, $data->staat2);
            $verifiziert        =  ($data->role > 9 && $data->role < 100 ? 'y' : 'n');
            $freischalten       = ($data->gesperrt == 'n' ? 'fa-check' : 'fa-times');
            $freischalten_title = ($data->gesperrt == 'n' ? 'Kunde sperren' : 'Kunde freischalten');

            $html .= '<div id="kunde_'.$data->id.'" class="list_line">'.CR;

            // Mitte
            $html .= '   <div class="kunde_list_right'.(defined('CONF_MODULE_BESTELLUNGFRONT') ? '_front' : '').'">'.CR;
            $html .= '      <div class="kunde_list1 list_col ellipsis">'.Helper::sqlDatumShort($data->created).'</div>'.CR;
            $html .= '      <div class="kunde_list2 list_col ellipsis">'.$data->nachname.'</div>'.CR;
            $html .= '      <div class="kunde_list3 list_col ellipsis">'.$data->vorname.'</div>'.CR;
            $html .= '      <div class="kunde_list4 list_col ellipsis">'.$data->firma.'</div>'.CR;
            $html .= '      <div class="kunde_list5 list_col ellipsis">'.$data->adresse.'</div>'.CR;
            $html .= '      <div class="kunde_list6 list_col ellipsis">'.$data->ort.'</div>'.CR;
            $html .= '      <div class="kunde_list7 list_col ellipsis">'.$staat_name.'</div>'.CR;
            $html .= '      <div class="kunde_list8 list_col ellipsis"><a href="mailto:'.$data->email.'" class="ellipsis'.($data->role == 1010 ? ' list_kunde_vip' : '').'">'.$data->email.'</a></div>'.CR;

            if ($verifiziert == 'y') {
               $html .= '      <div class="kunde_list9 list_col ellipsis list_verifiziert fas fa-check" title="verifiziert"></div>'.CR;
            }

            else {
               $html .= '      <div class="kunde_list9 list_col ellipsis"></div>'.CR;
            }

            $html .= '      <div class="clear"></div>'.CR;
            $html .= '   </div>'.CR;

            // Rechts
            $html .= '   <div class="kunde_list_extra'.(defined('CONF_MODULE_BESTELLUNGFRONT') ? '_front' : '').'">'.CR;
            $html .= '      <div class="kunde_list10 list_col pointer" onclick="location.href=\''.ADMIN_URL_IDX.'/bestellungen/neu/'.$data->id.'\';">';
            $html .= '         <span class="button">neu</span>';
            $html .= '      </div>'.CR;

            if (defined('CONF_MODULE_BESTELLUNGFRONT')) {
               $html .= '      <div class="kunde_list11 list_col button_round pointer" title="Bestellung über Kundenansicht">'.CR;
               $html .= '         <a href="'.SHOP_URL_IDX.'/bestellungfront/'.$data->id.'" target="_blank">'.CR;
               $html .= '            <span class="fas fa-shopping-cart"></span>'.CR;
               $html .= '         </a>'.CR;
               $html .= '      </div>'.CR;
            }

            $html .= '      <div class="clear"></div>'.CR;
            $html .= '   </div>'.CR;

            // Links
            $html .= '   <div class="kunde_list_left">'.CR;
            $html .= '      <div class="list_edit fas fa-pencil-alt pointer" title="bearbeiten"><a href="'.ADMIN_URL_IDX.'/kunden/detail/'.$data->id.'"></a></div>'.CR;
            $html .= '      <div class="list_gesperrt pointer fas '.$freischalten.'" onclick="Kunden.freischalten(this, '.$data->id.');" title="'.$freischalten_title.'"></div>'.CR;
            $html .= '      <div class="list_del far fa-trash-alt pointer" onclick="Kunden.delete('.$data->id.', this);" title="löschen"></div>'.CR;
            $html .= '      <div class="clear"></div>'.CR;
            $html .= '   </div>'.CR;
            $html .= '   <div class="clear"></div>'.CR;
            $html .= '</div>'.CR;
         }
      }

      return $html;
   }

   // Liste / Anzeige Anzahl Seiten usw.
   // 02.03.2019
   private function getCounter() {
      $html  = '<div class="pager_left">'.CR;
      $html .= '   <span class="erg_text">Ergebnisse pro Seite</span>'.CR;
      $seite = isset($_SESSION['kunden_seite']) ? $_SESSION['kunden_seite'] : 0; // aktuelle Seite z. Anzeigen
      $limit = isset($_SESSION['kunden_limit']) ? $_SESSION['kunden_limit'] : CONF_ART_PER_SITE;

      // Liste Anzahl pro Seite
      for ($i = CONF_ART_PER_SITE; $i <= CONF_ART_MAX; $i += CONF_ART_PER_SITE) {
         $class = ($i == $limit ? ' counter_active' : '');
         $html .= '   <span class="rahmen'.$class.'" onclick="Kunden.count('.$i.');">'.$i.'</span>'.CR;
      }

      $html .= '</div>'.CR;

      $html .= '<div class="pager_right">'.CR;
      $anzahl = $this->db->querySingleValue("SELECT count(id) as anzahl FROM #__users WHERE id > 1 AND role > 8");

      if ($anzahl) {
         $von = $seite * $limit + 1;            // Art. von
         $bis = ($seite + 1) * $limit;          // Art. bis
         $ende = (int)floor($anzahl / $limit);  // max. Seiten

         // Korrekturen bei letzer Seite
         if ($seite == $ende and ($ende * $limit < $anzahl)) {
            $bis = $anzahl;
         }

         if ($seite > 0) {
            $html .= '   <div class="first fas fa-angle-double-left active" onclick="Kunden.seite(0);"></div>'.CR;
         }
         else {
            $html .= '   <div class="first fas fa-angle-double-left inactive"></div>'.CR;
         }

         if ($seite > 0) {
            $html .= '   <div class="back fas fa-angle-left active" onclick="Kunden.seite('.($seite - 1).');"></div>'.CR;
         }
         else {
            $html .= '   <div class="back fas fa-angle-left inactive"></div>'.CR;
         }

         $html .= '   <div class="vonbis">'.$von.' - '.$bis.' von '.$anzahl.'</div>'.CR;

         if ($seite  < $ende) {
            $html .= '   <div class="next fas fa-angle-right active" onclick="Kunden.seite(' . ($seite + 1) . ');"></div>'.CR;
         }
         else {
            $html .= '   <div class="next fas fa-angle-right inactive"></div>'.CR;
         }

         if ($seite < $ende) {
            $html .= '   <div class="end fas fa-angle-double-right active" onclick="Kunden.seite('.$ende.');"></div>'.CR;
         }
         else {
            $html .= '   <div class="end fas fa-angle-double-right inactive"></div>'.CR;
         }
      }

      else {
         $html .= 'keine Kunden vorhanden'.CR;
      }

      $html .= '   <div class="clear"></div>'.CR;
      $html .= '</div>'.CR;
      $html .= '<div class="clear"></div>'.CR;

      return $html;
   }

   // Kunde Löschen
   // 28.12.2018
   private function delete() {
      $user_id = $this->params->postInt('user_id');
      $this->db->query("DELETE FROM #__users WHERE id = $user_id");

      echo json_encode(['status' => 'ok']);
      exit;
   }

   // Kundendaten aus DB lesen
   // 28.12.2018
   private function _getDetail($kunden_id) {
      $user_id = $this->params->postInt('user_id');
      if ($kunden_id == 0) {
         // Default-Werte
         return (object) [
                  'id'                    => '',
                  'anrede'                => '',
                  'name'                  => '',
                  'email'                 => '',
                  'password'              => '',
                  'role'                  => '9',
                  'role_a'                => '9',
                  'vorname'               => '',
                  'nachname'              => '',
                  'firma'                 => '',
                  'adresse'               => '',
                  'hausnr'                => '',
                  'plz'                   => '',
                  'ort'                   => '',
                  'buland'                => '',
                  'staat'                 => '',
                  'staat2'                => '',
                  'gebdatum'              => '',
                  'ustid'                 => '',
                  'telefon'               => '',
                  'newsletter'            => 'n',
                  'newsletter_check'      => '',
                  'daten'                 => 'n',
                  'agb'                   => 'n',
                  'info'                  => '',
                  'lieferadresse'         => 'n',
                  'lf_anrede'             => '',
                  'lf_vorname'            => '',
                  'lf_nachname'           => '',
                  'lf_firma'              => '',
                  'lf_postnr'             => '',
                  'lf_adresse'            => '',
                  'lf_hausnr'             => '',
                  'lf_plz'                => '',
                  'lf_ort'                => '',
                  'lf_buland'             => '',
                  'lf_staat'              => '160',
                  'lf_staat2'             => '',
                  'created'               => date('Y-m-d'),
                  'modified'              => '',
                  'last_login'            => '',
                  'forgotten'             => '',
                  'gutschrift'            => '0.00',
                  'rabatt'                => '0.00',
                  'gutschein_code'        => '',
                  'gutschein_wert_brutto' => '0.00',
                  'gesperrt'              => 'n',
                  'lang'                  => 'deu',
                  'pp_mail'               => '',
                  'pp_id'                 => '',
                  'bank_inhaber'          => '',
                  'bank_name'             => '',
                  'bank_iban'             => '',
                  'bank_bic'              => '',
                  'alter_check'           => ''
         ];
      }

      $data = $this->db->querySingleObject("SELECT * FROM #__users WHERE id = $kunden_id");

      if (!isset($data->role)) {
         exit(header('Location: '.ADMIN_URL_IDX.'/kunden'));
      }

      $data->role_a = $data->role;

      return $data;
   }

   // Auf Änderungen prüfen und Speichern
   // 02.03.2019
   private function _detailSave($kunden_id) {
      $newsletter       = $this->params->postCheckbox('newsletter');
      $newsletter_check = '';

      // Test auf newsletter_check
      if ($kunden_id > 0) {
         // Kunde vorhanden
         $test = $this->db->querySingleObject("SELECT newsletter, newsletter_check FROM #__users WHERE id = $kunden_id");

         // Manuell durch Admin aktiviert
         if (($test->newsletter == 'n' || ($test->newsletter == 'y' && $test->newsletter_check != 'ok')) && $newsletter == 'y') {
            $newsletter_check = 'ok';
         }
         // sonst Eintrag übernehmen
         else {
            $newsletter_check = $test->newsletter_check;
         }
      }

      // Neuer Kunde
      else {
         // Wenn bei neuem User gesetzt, Newsletter aktivieren
         if ($newsletter == 'y') {
            $newsletter_check = 'ok';
         }
      }

      $stammkunde     = $this->params->postInt('stammkunde');
      $email          = $this->params->postString('email');
      $anrede         = $this->params->postString('anrede');
      $vorname        = $this->params->postString('vorname');
      $nachname       = $this->params->postString('nachname');
      $gebdatum       = Helper::datumSql($this->params->postString('gebdatum'));
      $firma          = $this->params->postString('firma');
      $ustid          = $this->params->postString('ustid');
      $adresse        = $this->params->postString('adresse');
      $hausnr         = $this->params->postString('hausnr');
      $plz            = $this->params->postString('plz');
      $ort            = $this->params->postString('ort');
      $buland         = $this->params->postString('buland');
      $staat          = $this->params->postString('staat');
      $staat2         = $this->params->postString('staat2');
      $telefon        = $this->params->postString('telefon');
      $info           = trim($this->params->postString('notiz'));
      $lf_anrede      = $this->params->postString('lf_anrede');
      $lf_vorname     = $this->params->postString('lf_vorname');
      $lf_nachname    = $this->params->postString('lf_nachname');
      $lf_firma       = $this->params->postString('lf_firma');
      $lf_postnr      = $this->params->postString('lf_postnr');
      $lf_adresse     = $this->params->postString('lf_adresse');
      $lf_hausnr      = $this->params->postString('lf_hausnr');
      $lf_plz         = $this->params->postString('lf_plz');
      $lf_ort         = $this->params->postString('lf_ort');
      $lf_buland      = $this->params->postString('lf_buland');
      $lf_staat       = $this->params->postString('lf_staat');
      $lf_staat2      = $this->params->postString('lf_staat2');
      $rabatt         = $this->params->postFloat('rabatt');
      $gutschrift     = $this->params->postFloat('gutschrift');
      $bank_inhaber   = $this->params->postString('bank_inhaber');
      $bank_name      = $this->params->postString('bank_name');
      $bank_iban      = $this->params->postString('bank_iban');
      $bank_bic       = $this->params->postString('bank_bic');
      $rechnung_kunde = $this->params->postCheckbox('rechnung_kunde');

      // Neu
      if ($kunden_id == 0) {
         $sql = "INSERT INTO #__users ";
      }

      // Update
      else {
         $sql = "UPDATE #__users ";
      }

      $sql .= "SET email            = '".$this->db->escape($email)."',
                   anrede           = '$anrede',
                   vorname          = '".$this->db->escape($vorname)."',
                   nachname         = '".$this->db->escape($nachname)."',
                   firma            = '".$this->db->escape($firma)."',
                   adresse          = '".$this->db->escape($adresse)."',
                   hausnr           = '$hausnr',
                   plz              = '$plz',
                   ort              = '".$this->db->escape($ort)."',
                   buland           = '".$this->db->escape($buland)."',
                   staat            = '$staat',
                   staat2           = '".$this->db->escape($staat2)."',
                   gebdatum         = '$gebdatum',
                   ustid            = '$ustid',
                   telefon          = '$telefon',
                   newsletter       = '$newsletter',
                   newsletter_check = '$newsletter_check',
                   info             = '$info',
                   role             = $stammkunde,
                   rabatt           = $rabatt,
                   gutschrift       = $gutschrift,
                   bank_inhaber     = '".$this->db->escape($bank_inhaber)."',
                   bank_name        = '".$this->db->escape($bank_name)."',
                   bank_iban        = '$bank_iban',
                   bank_bic         = '$bank_bic',
                   rechnung_kunde   = '$rechnung_kunde' ";

      $password = $this->params->postString('password');

      // Passwort geändert
      if (strlen($password) > 0 && strlen($password) != 32 ) {
         $sql .= ", password  = '".md5($password)."'";
         $sql .= ", forgotten = ''";
      }

      else {
         if ($stammkunde > 9 ){
            $sql .= ", forgotten = ''";
         }
      }

      // Lieferadress angegeben
//      if ($lf_nachname && $lf_vorname && $lf_adresse && $lf_ort) {
      if ($lf_adresse) {
         $sql .=", lieferadresse = 'y',
                   lf_anrede     = '$lf_anrede',
                   lf_vorname    = '".$this->db->escape($lf_vorname)."',
                   lf_nachname   = '".$this->db->escape($lf_nachname)."',
                   lf_firma      = '".$this->db->escape($lf_firma)."',
                   lf_postnr     = '$lf_postnr',
                   lf_adresse    = '".$this->db->escape($lf_adresse)."',
                   lf_hausnr     = '$lf_hausnr',
                   lf_plz        = '$lf_plz',
                   lf_ort        = '".$this->db->escape($lf_ort)."',
                   lf_buland     = '".$this->db->escape($lf_buland)."',
                   lf_staat      = '$lf_staat',
                   lf_staat2     = '".$this->db->escape($lf_staat2)."'";
      }

      // sonst Rechnungsadresee als Lieferadresse übernehmen
      else {
         $sql .=", lieferadresse = 'y',
                   lf_anrede     = '$anrede',
                   lf_vorname    = '".$this->db->escape($vorname)."',
                   lf_nachname   = '".$this->db->escape($nachname)."',
                   lf_firma      = '".$this->db->escape($firma)."',
                   lf_postnr     = '',
                   lf_adresse    = '".$this->db->escape($adresse)."',
                   lf_hausnr     = '$hausnr',
                   lf_plz        = '$plz',
                   lf_ort        = '".$this->db->escape($ort)."',
                   lf_buland     = '".$this->db->escape($buland)."',
                   lf_staat      = '$staat',
                   lf_staat2     = '".$this->db->escape($staat2)."'";
      }

      if ($kunden_id != 0) {
         $sql .= " WHERE id = $kunden_id";
      }

      $this->db->query($sql);

      if ($kunden_id == 0) {
         $kunden_id = $this->db->getNewId();
         $this->params->setInt('user_id', $kunden_id);
      }

      return $kunden_id;
   }

   // Kunde sperren
   private function _gesperrt() {
      $user_id  = $this->params->postInt('user_id');
      $gesperrt = $this->params->postString('gesperrt');

      $this->db->query("UPDATE #__users SET gesperrt = '$gesperrt' WHERE id = $user_id");

      if ($gesperrt == 'n') {
         // Mail an Kunde senden
         if ($this->params->firma['account_manual'] == 'y') {
            $mail = Control::getMail();
            $mail->sendFreigeschaltet($user_id);
         }
      }

      $test = $this->db->querySingleValue("SELECT gesperrt FROM #__users WHERE id = $user_id");
      echo json_encode(['status' => 'ok', 'gesperrt' => $test]);
      exit;

   }

   // Neuen Verifizierungslink senden / PW löschen
   // 28.02.2019
   private function _forgotten() {
      $mail    = Control::getMail();
      $user_id = $this->params->postInt('user_id');
      $msg     = "Der Link konnte nicht versendet werden";

      $data    = $this->db->querySingleObject("SELECT email, password, forgotten, modified FROM #__users WHERE id = $user_id");

      if (isset($data->email)) {
         // Passwort löschen, Validierungslink senden
         $forgotten = md5('KANPAICLASSIC'.time());

         if ($this->db->query("UPDATE #__users SET forgotten = '$forgotten', modified = '".date('Y-m-d H:i:m')."' WHERE email = '$data->email'")) {
            $mail->sendForgotten($data->email, SHOP_URL_IDX.'/validate/'.$forgotten);
            $msg = "Passwort wurde zurückgesetzt und neuer Freischaltcode gesendet";
         }
      }

      return $msg;
   }

   // Nachricht an Kunde mailen
   // 01.03.2019
   private function _sendNachricht() {
      $user_id = $this->params->postInt('user_id');
      $nachricht = nl2br($this->params->postString('nachricht'));

      // Mailadresse suchen
      $sql = "SELECT email FROM #__users WHERE id = $user_id";
      $this->db->query($sql);
      $data = $this->db->getObject();

      // Mail senden
      $mail = Control::getMail();
      if ($mail->sendNachricht($data->email, $nachricht)) {
         echo json_encode(['status' => 'ok', 'msg' => 'Nachricht an '.$data->email.' wurde versendet']);
         exit;
      }

      echo json_encode(['status' => 'ok', 'msg' => 'Nachricht konnte nicht versendet werden!']);
      exit;
   }

   // Gutschrift-Mail an Kunde senden
   // 02.03.2019
   private function _sendGutschrift() {
      $user_id       = (int)$this->params->postInt('user_id');
      $email         = $this->params->postString('email');
      $gutschrift_db = $this->params->postFloat('gutschrift');
      $gutschrift    = number_format($gutschrift_db, 2, ',', '.');

      $this->db->query("UPDATE #__users SET gutschrift = $gutschrift_db WHERE id = $user_id");

      $mail = Control::getMail();
      if ($mail->sendGutschrift($email, $gutschrift, $user_id)) {
         echo json_encode(['status' => 'ok']);
         exit;
      }

         echo json_encode(['status' => 'error', 'msg' => 'Mail konnte nicht versendet werden']);
         exit;
   }

   private function _alterCheck() {
      $user_id    = (int)$this->params->postInt('user_id');
      $alter_check = $this->params->postCheckbox('alter_check');

      $this->db->query("UPDATE #__users SET alter_check = '".($alter_check == 'y' ? 'Admin' : '')."'  WHERE id = $user_id");

      exit(json_encode(['status' => 'ok', 'alter_check' => $alter_check]));
   }

   private function _checkMail() {
      $email = $this->params->postString('email');
      $kunden_id = $this->params->postInt('user_id');

      // Email übergehen
      if ($email == 'nomail') {
         echo json_encode(['status' => 'ok']);
         exit;
      }

      if (preg_match( '/^([a-z0-9]+([-_\.]?[a-z0-9])+)@[a-z0-9äöü]+([-_\.]?[a-z0-9äöü])+\.[a-z]{2,4}$/i', $email)) {
         $test = (int)$this->db->querySingleValue("SELECT count(*) FROM #__users WHERE email = '$email' AND id != $kunden_id");

         if ($test == 0) {
            echo json_encode(['status' => 'ok']);
            exit;
         }
      }

      echo json_encode(['status' => 'error', 'msg' => 'Mailadresse kann nicht verwendet werden']);
      exit;
   }

   // Nach Kunden suchen, die mit $search beginnen und Ergebnis zurück / nicht verwendet
   private function suchen() {
      //$lang = $this->params->selected_lang;
      $searchstring = $this->params->postString('search', '', 'sql');
      $html = '';
      $sql = "SELECT id, nachname, vorname, firma FROM #__users WHERE id > 1 AND (vorname LIKE '$searchstring%' OR nachname LIKE '$searchstring%' OR firma LIKE '$searchstring%') LIMIT 0, 20";
      if ($this->db->query($sql)) {
         while ($data = $this->db->getObject()) {
            $html .= "<div class='search-list' onclick='Royalart.kundeFind($data->id, 0);'>$data->nachname, $data->vorname, $data->firma</div>";
         }
      }

      if ($html =='') {
         $html = 'not found';
      }

      $html .= "<div class='searchclose' onclick=\"this.parentNode.style.display=('none');\">".$this->text->get('button', 'schliessen', 'deu')."</div>";
      return $html;
   }
}
