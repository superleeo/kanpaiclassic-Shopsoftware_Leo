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

class KANPAICLASSIC_warenkorb {
   private $db          = null;
   private $db_extern   = null;
   private $params      = null;
   private $artikel     = null;
   public  $summe       = '';
   public  $gesamt      = 0;
   public  $steuer1     = '';
   public  $steuer2     = '';
   public  $steuer3     ='';
   public  $versand     = '';
   public  $print_summe = false;
   public  $from_ml     = false;
   public  $from_ml_arr = array();

   // WK bei registrierten Usern in DB -> Session leer!, sonst in Session
   public function __construct() {
      $this->params    = Control::getParams();
      $this->db        = Control::getDb();
      $this->db_extern = Control::getExternDb();
      $this->artikel   = Control::getArticles();

      // Warenkorb aus DB laden, wenn notwendig WK-SESSION und WK-DB verbinden (nach login)
      if ($this->params->user_id > 0) {
         // Artikel nur Speichern
         if (isset($_SESSION['warenkorb']) && !empty($_SESSION['warenkorb'])) {
            $this->verbindeWK();
         }

         $this->loadWk();
      }

      // sonst aus Session
      else {
         $this->params->warenkorb = array();

         if (isset($_SESSION['warenkorb']) && is_array($_SESSION['warenkorb'])) {
            $this->params->warenkorb = $_SESSION['warenkorb'];
         }
         $temp = array();

         // Eintrag mit art_id = 0 löschen. Fehler in der Session-Verwaltung???
         for ($i = 0; $i < count($this->params->warenkorb); $i++) {
            $wk = $this->params->warenkorb[$i];
            if (isset($wk['art_id']) && ($wk['art_id'] != 0 || $wk['cat_id'] != 0)) {
               $temp[] = $this->params->warenkorb[$i];
            }
         }

         $this->params->warenkorb = $temp;
      }

      if (defined('CONF_MODULE_PORTAL')) {
         $this->params->haendler_arr = $this->_countHaendler();
      }
   }

   // Für 'Anzeige Anzahl im Warenkorb
   public function getAnzahl() {
      $anzahl = count($this->params->warenkorb);
      $menge = 0;

      if ($anzahl > 0) {
         foreach ($this->params->warenkorb as $v) {
            $menge += $v['art_menge'];
         }
      }

      // return $menge; // Gesamtmenge
      return $anzahl;
   }

   // WK Artikel hinzufügen und bei angemeld. Kunden speichern
   public function addArticle() {
      $this->_addWk();

      // Bei registrierten Kinden in DB speichern
      if ((int)$this->params->user_id > 0) {
         $this->saveWk();
      }
   }

   // WK aus DB laden (nur angemeldete Kunden)
   public function loadWk() {
      $this->params->warenkorb = array();
      $_SESSION['warenkorb'] = [];

      $sql = "SELECT id, art_id, cat_id, art_menge, haendler_id, foto_set, foto_sort, motiv_uploadp_check, motiv_uploadt_check, motiv_upload_name,
                     motiv_upload_user, motiv_upload_text, configurator, rechner_check, rechner_breite, rechner_hoehe, rechner_tiefe, rechner_mode, rechner_einheit, preismatrix, mixer
              FROM #__warenkorb WHERE user_id = ".$this->params->user_id." ORDER BY id";
      $query = $this->db->query($sql);

      if ($query) {
         while ($data = $this->db->getObject()) {
            $this->params->warenkorb[] = array(
                                               'art_id'              => (int)$data->art_id,
                                               'cat_id'              => (int)$data->cat_id,
                                               'art_menge'           => (float)$data->art_menge,
                                               'wk_id'               => (int)$data->id,
                                               'foto_set'            => (int)$data->foto_set,
                                               'foto_sort'           => (int)$data->foto_sort,
                                               'haendler_id'         => (int)$data->haendler_id,
                                               'wk_change'           => false,
                                               'motiv_uploadp_check' => $data->motiv_uploadp_check,
                                               'motiv_uploadt_check' => $data->motiv_uploadt_check,
                                               'motiv_upload_name'   => $data->motiv_upload_name,
                                               'motiv_upload_user'   => $data->motiv_upload_user,
                                               'motiv_upload_text'   => $data->motiv_upload_text,
                                               'configurator'        => $data->configurator,
                                               'rechner_check'       => $data->rechner_check,
                                               'rechner_breite'      => $data->rechner_breite,
                                               'rechner_hoehe'       => $data->rechner_hoehe,
                                               'rechner_tiefe'       => $data->rechner_tiefe,
                                               'rechner_mode'        => $data->rechner_mode,
                                               'rechner_einheit'     => $data->rechner_einheit,
                                               'preismatrix'         => $data->preismatrix,
                                               'mixer'               => $data->mixer
                                          );
         }
      }

      return;
   }

   // WK in DB speichern
   public function saveWk() {
      foreach ($this->params->warenkorb as $key => $wk) {
         // wk_id: 0 -> nicht in DB, 1 -> in DB
         if ($wk['wk_id'] && $wk['wk_change']) {
            $sql = "UPDATE #__warenkorb SET art_menge = ".$wk['art_menge']." WHERE id = ".$wk['wk_id'];
            $this->db->query($sql);
            $this->params->warenkorb[$key]['wk_change'] = '';
         }

         if ($wk['wk_id'] == 0 && ($wk['art_id'] > 0 || $wk['cat_id'] > 0)) {
            $sql = "INSERT INTO #__warenkorb SET
                       user_id             = ".$this->params->user_id.",
                       art_id              = ".$wk['art_id'].",
                       cat_id              = ".$wk['cat_id'].",
                       art_menge           = '".$wk['art_menge']."',
                       foto_set            = ".$wk['foto_set'].",
                       foto_sort           = ".$wk['foto_sort'].",
                       haendler_id         = ".$wk['haendler_id'].",
                       motiv_uploadp_check = '".$wk['motiv_uploadp_check']."',
                       motiv_uploadt_check = '".$wk['motiv_uploadt_check']."',
                       motiv_upload_name   = '".$wk['motiv_upload_name']."',
                       motiv_upload_user   = '".$wk['motiv_upload_user']."',
                       motiv_upload_text   = '".$wk['motiv_upload_text']."',
                       configurator        = '".$this->db->escape($wk['configurator'])."',
                       rechner_check       = '".$wk['rechner_check']."',
                       rechner_breite      = '".$wk['rechner_breite']."',
                       rechner_hoehe       = '".$wk['rechner_hoehe']."',
                       rechner_tiefe       = '".$wk['rechner_tiefe']."',
                       rechner_mode        = '".$wk['rechner_mode']."',
                       rechner_einheit     = '".$wk['rechner_einheit']."',
                       preismatrix         = '".$this->db->escape($wk['preismatrix'])."',
                       mixer               = '".$this->db->escape($wk['mixer'])."'";

            $this->db->query($sql);
//echo $this->db->last_sql;
            $this->params->warenkorb[$key]['wk_change'] = '';
            $this->params->warenkorb[$key]['wk_id'] = 1;
         }
      }

      return;
   }

   // Warenkörbe aus $_Session und DB verbinden, nur nach Login,
   // damit identische Artikel nicht mehrfach gespeichert werden
   // in diesem Fall Mengen addieren
   public function verbindeWk() {
      if (is_array($this->params->warenkorb)) {
         foreach ($this->params->warenkorb as $key => $wk) {
            $test = 0;
            $menge = $wk['art_menge'];

            // Nur Menge ändern
            if ($wk['configurator'] == '' && $wk['foto_set'] < 1 && $wk['mixer'] == '') {
               // Mengen zusammenfassen
               $sql = "UPDATE #__warenkorb SET art_menge = art_menge + " . $menge . " WHERE user_id = " . $this->params->user_id . " AND art_id = " .$wk['art_id'];
               // Update erfolgreich? Sonst als neuen Eintrag speichern
               $test = $this->db->query($sql);
            }

            if ($test == 0) {
               $sql = "INSERT INTO #__warenkorb SET
                          user_id             = ".$this->params->user_id.",
                          art_id              = ".$wk['art_id'].",
                          cat_id              = ".$wk['cat_id'].",
                          art_menge           = '".($wk['art_menge'] >= 0 ? $wk['art_menge'] : 0)."',
                          foto_set            = ".$wk['foto_set'].",
                          foto_sort           = ".$wk['foto_sort'].",
                          haendler_id         = ".$wk['haendler_id'].",
                          motiv_uploadp_check = '".$wk['motiv_uploadp_check']."',
                          motiv_uploadt_check = '".$wk['motiv_uploadt_check']."',
                          motiv_upload_name   = '".$wk['motiv_upload_name']."',
                          motiv_upload_user   = '".$wk['motiv_upload_user']."',
                          motiv_upload_text   = '".$wk['motiv_upload_text']."',
                          configurator        = '".$wk['configurator']."',
                          rechner_check       = '".$wk['rechner_check']."',
                          rechner_breite      = '".$wk['rechner_breite']."',
                          rechner_hoehe       = '".$wk['rechner_hoehe']."',
                          rechner_tiefe       = '".$wk['rechner_tiefe']."',
                          rechner_mode        = '".$wk['rechner_mode']."',
                          rechner_einheit     = '".$wk['rechner_einheit']."',
                          preismatrix         = '".$this->db->escape($wk['preismatrix'])."',
                          mixer               = '".$this->db->escape($wk['mixer'])."'";
               $this->db->query($sql);
            }

            $this->params->warenkorb[$key]['wk_change'] = '';
         }
      }

      return;
   }

   // Artikel aus WK löschen $wk_id ist index von params::warenkorb
   public function delWk($wk_id) {
      $temp = array();

      for ($i = 0; $i < count($this->params->warenkorb); $i++) {
         $wk = $this->params->warenkorb[$i];
         if ($wk_id != $i) {
            $temp[] = $this->params->warenkorb[$i];
         }
         elseif ($this->params->user_id) {
            $sql = "DELETE FROM #__warenkorb WHERE id = ".$wk['wk_id'];
            $this->db->query($sql);
         }
      }

      $this->params->warenkorb = $temp;
      $_SESSION['warenkorb'] = $temp;

      if ($this->params->user_id) {
         $this->saveWk();
      }
      return;
   }

   // WK Artikel hinzufügen (nicht DB, nur als geändert markieren für saveWk(), falls user eingelogged ist)
   private function _addWk() {
      $check           = false;
      $test            = '';
      $configurator    = '';
      $rechner_check   = $this->params->postCheckbox('rechner_check');
      $rechner_einheit = $this->params->postString('rechner_einheit');
      $rechner_mode    = $this->params->postInt('rechner_mode');
      $rechner_breite  = $this->params->postFloat('rechner_breite');
      $rechner_hoehe   = $this->params->postFloat('rechner_hoehe');
      $rechner_tiefe   = $this->params->postFloat('rechner_tiefe');
      $preismatrix     = '';
      $cat_id          = $this->params->postInt('cat_id');
      $mixer           = $this->params->postString('mixer');

      if (defined('CONF_MODULE_MEGACONFIGURATOR')) {
         // Daten Motivupload, mega_konfigurator
         if ($this->from_ml) {
            $configurator = $this->from_ml_arr['configurator'];

            if ($configurator != '') {
               $test = 'neu';
            }
         }

         // Test, ob Artikel Configurator-Eintrag hat
         else {
            $test_conf = $this->db_extern->querySingleValue("SELECT i.configurator FROM #__articles_info AS i, #__articles AS a WHERE i.id = a.parent_id AND a.id = ".$this->params->art_id);

            if ($test_conf != '') {
               $conf     = json_decode($this->params->postString('configurator'), true);
               $tmp      = array();
               $wert_arr = array();
               $merkmal  = 0;

               for ($i = 0; $i < (is_array($conf) ? count($conf) : 0); $i++) {
                  // Konfigurator mit Texten
                  if ($conf[$i] == null || $conf[$i] == 'null') {
                     break;
                  }

                  $merkmal = $conf[$i][0];
                  $wert_arr = array();

                  for ($j = 0; $j < (is_array($conf[$i][1]) ? count($conf[$i][1]) : 0); $j++) {
                     $wert = (int)$conf[$i][1][$j][0];
                     $test = explode(';', $conf[$i][1][$j][1]);

                     if (count($test) == 4) {
                        if (md5($test[1].$test[2].$test[3]) != $test[0]) {
                           continue;
                        }
                     }
                     else {
                        continue;
                     }

                     $wert_arr[] = array($wert, (float)$test[1], $test[2], $test[3]);
                  }

                  $tmp[] = array($merkmal, $wert_arr);
               }

               // Konfigurator mit Texten
               if (isset($conf[55]) && is_array($conf[55])) {
                  $tmp['texte'] = [];

                  foreach ($conf[55] as $k => $v) {
                     $tmp['texte'][] = ['text' => $v[0], 'text_id' => $v[1]];
                  }

               }

               $configurator = json_encode($tmp);
            }
         }
      }

      if (defined('CONF_MODULE_MATRIX')) {
         $preismatrix = trim(str_ireplace('&quot;', '"', $this->params->postString('preismatrix')), '"');
      }

      // falls Artikel vorhanden, Menge erhöhen
      for ($i = 0; $i < count($this->params->warenkorb); $i++) {
         $motiv_upload = array();
         $motiv_upload['motiv_upload_name'] = '';
         $motiv_upload['motiv_upload_user'] = '';
         $motiv_upload['motiv_upload_text'] = '';

         // Bei Fotoartikel / Configurator / preismatrix / Mixer mehrere gleiche Artikel ermöglichen, da Preis aus Set-Daten berechnet wird
         if ((int)$this->params->postInt('foto_sort') < 1 &&                  // Kein Fotoartikel
                  $test == '' &&                                              // Kein Megakonfigurator
                  $rechner_check == 'n' &&                                    // Kein Rechner
                  $this->params->warenkorb[$i]['motiv_upload_text'] == '' &&  // Kein Motiv-Text
                  $this->params->warenkorb[$i]['motiv_upload_name'] == '' &&  // Kein Motiv-Bild
                  $preismatrix == '' &&                                       // Keine Preismatrix
                  $mixer == '' &&                                             // Kein Artikelmixer
                  $cat_id == 0)                                               // Kein Kategoriemixer
         {
            if (isset($this->params->warenkorb[$i]['art_id'], $this->params->warenkorb[$i]['art_menge'], $this->params->warenkorb[$i]['wk_id'], $this->params->warenkorb[$i]['wk_change'])) {
               // Aktuell gewählter Artikel bereits im WK -> Menge erhöhen
               if ($this->params->warenkorb[$i]['art_id'] == $this->params->art_id) {
                  $this->params->warenkorb[$i]['art_menge'] = $this->checkLagermenge($this->params->warenkorb[$i]['art_id'], $this->params->warenkorb[$i]['art_menge'] + $this->params->art_menge);
                  $this->params->warenkorb[$i]['wk_change'] = true;
                  // Keine weiter bearbeitung
                  $check = true;
               }
            }
         } //foto_sort
      } // for

      // Artikel zum Warenkorb hinzufügen
      if (!$check) {
         $foto_set = $this->params->postInt('foto_set');
         $foto_sort = $this->params->postInt('foto_sort');
         $menge = 1;

         if ($this->params->art_id > 0) {
            $menge = $this->checkLagermenge($this->params->art_id, $this->params->art_menge);
         }

         // Kategorie-Mixer
         else if($this->params->art_id == 0 && $this->params->kat_id > 0) {
            $menge = $this->params->art_menge;
         }

         $motiv_uploadp_check = 'n';
         $motiv_uploadt_check = 'n';
         $motiv_upload_name   = '';
         $motiv_upload_user   = '';
         $motiv_upload_text   = '';

         if (defined('CONF_MODULE_MOTIVUL')) {
            // Aus Merkliste übernommen ?
            if ($this->from_ml) {
               $motiv_uploadp_check = $this->from_ml_arr['motiv_uploadp_check'];
               $motiv_uploadt_check = $this->from_ml_arr['motiv_uploadt_check'];
               $motiv_upload_name   = $this->from_ml_arr['motiv_upload_name'];
               $motiv_upload_user   = $this->from_ml_arr['motiv_upload_user'];
               $motiv_upload_text   = $this->from_ml_arr['motiv_upload_text'];
            }

            else {
               $mu_check = $this->db_extern->querySingleObject("SELECT i.motiv_uploadp_check, i.motiv_uploadt_check FROM #__articles AS a, #__articles_info AS i WHERE a.id = ".$this->params->art_id." AND i.id = a.parent_id");

               // Nicht bei Mixer-Kategorie
               if ($this->params->art_id > 0 && ($mu_check->motiv_uploadp_check == 'y' || $mu_check->motiv_uploadt_check == 'y')) {
                  $motiv_uploadp_check = $mu_check->motiv_uploadp_check;
                  $motiv_uploadt_check = $mu_check->motiv_uploadt_check;
                  $motiv_upload        = $this->_checkMotiveUpload();
                  $motiv_upload_name   = $motiv_upload['motiv_upload_name'];
                  $motiv_upload_user   = $motiv_upload['motiv_upload_user'];
                  $motiv_upload_text   = $motiv_upload['motiv_upload_text'];
               }
            }
         }

         $haendler_id = 0;

         $this->params->warenkorb[] = array(
                                          'art_id'              => $this->params->art_id,
                                          'cat_id'              => $cat_id,
                                          'art_menge'           => $menge,
                                          'wk_id'               => 0,
                                          'foto_set'            => $foto_set,
                                          'foto_sort'           => $foto_sort,
                                          'haendler_id'         => $haendler_id,
                                          'wk_change'           => true,
                                          'motiv_uploadp_check' => $motiv_uploadp_check,
                                          'motiv_uploadt_check' => $motiv_uploadt_check,
                                          'motiv_upload_name'   => $motiv_upload_name,
                                          'motiv_upload_user'   => $motiv_upload_user,
                                          'motiv_upload_text'   => $motiv_upload_text,
                                          'configurator'        => $configurator,
                                          'rechner_check'       => $rechner_check,
                                          'rechner_breite'      => $rechner_breite,
                                          'rechner_hoehe'       => $rechner_hoehe,
                                          'rechner_tiefe'       => $rechner_tiefe,
                                          'rechner_mode'        => $rechner_mode,
                                          'rechner_einheit'     => $rechner_einheit,
                                          'preismatrix'         => $preismatrix,
                                          'mixer'               => $mixer
                                       );
      } // if check

      // Warenkorb in Session übernehmen
      $_SESSION['warenkorb'] = $this->params->warenkorb;

      return;
   }

   // Daten für Anzeige WK generieren
   public function getWk($haendler_id = 0) {
      // Für Art des Widerrufs, wird nach Bestellung in params gelöscht
      if (!isset($_SESSION['widerruf_wk'])) {
         $_SESSION['widerruf_wk'] = 0;
      }

      $_SESSION['fsk_artikel'] = false;

      // Wenn Artikel im WK vorhanden
      $lang = $this->params->selected_lang;

      if (count($this->params->warenkorb)) {
         // Artikeldaten aus DB lesen
         $data       = array();
         $del_arr    = array();
         $count      = 0;
         $wk_changed = $this->params->wk_changed_id;
         $c = -1;

         // Artikeldaten auslesen
         for ($i = 0; $i < count($this->params->warenkorb); $i++) {
            $c++;
            $this->print_summe = true;
            $wk = $this->params->warenkorb[$i];

            if ((int)$wk['haendler_id'] != $haendler_id) {
               if (defined('CONF_MODULE_PORTAL') && $wk_changed > $c) {
                  $this->params->wk_changed_id--;
               }

               continue;
            }

            // Artikeldaten aus DB lesen
             $d = $this->artikel->getArticleById($wk['art_id'], '', '', (int)$wk['foto_set'], (int)$wk['foto_sort']);

            if ($d || (int)$wk['cat_id'] > 0) {

               // Normaler Artikel
               if ($wk['mixer'] == '') {
                  $data[$count] = $d;
                  $data[$count]->mixer = '';
               }

               // Mixer
               else {
                  // Mixer-Kategorie
                  if ((int)$wk['cat_id'] > 0) {
                     $mixer1 = Control::getModuleMixerKategorie();
                     $d = $mixer1->getArticleById($wk['cat_id'], $this->params->selected_lang, $this->params->selected_lang, (int)$wk['foto_set'], (int)$wk['foto_sort'], false, $wk['mixer']);
                     $data[$count] = $d;
                  }

                  // Artikel-Mixer
                  else {
                     $mixer2 = Control::getModuleMixerArtikel();
                     $d = $mixer2->getArticleById($wk['art_id'], $this->params->selected_lang, '', (int)$wk['foto_set'], (int)$wk['foto_sort'], false, $wk['mixer']);
                     $data[$count] = $d;
                  }
               }

               // Und mit Daten aus WK überschreiben
               $data[$count] = $d;
               $data[$count]->preis           = 0;
               $data[$count]->wk_id           = $i;
               $data[$count]->artikel_menge   = $wk['art_menge'];
               $data[$count]->rechner_menge   = $wk['rechner_breite'] * $wk['rechner_hoehe'] * $wk['rechner_tiefe'];
               $data[$count]->configurator    = $wk['configurator'];
               $data[$count]->rechner_check   = $wk['rechner_check'];
               $data[$count]->rechner_breite  = $wk['rechner_breite'];
               $data[$count]->rechner_hoehe   = $wk['rechner_hoehe'];
               $data[$count]->rechner_tiefe   = $wk['rechner_tiefe'];
               $data[$count]->rechner_mode    = $wk['rechner_mode'];
               $data[$count]->rechner_einheit = $wk['rechner_einheit'];
               $data[$count]->preismatrix     = $wk['preismatrix'];

               // Grundpreis bei Staffelpreisen korrigieren
               if ($this->params->firma['staffelpreise'] == 'y') {
                  $data[$count]->ge_netto = Helper::staffelpreis($data[$count]->ge_netto, $data[$count]->artikel_menge, $data[$count]->staffelung);
               }

               $widerruf = $data[$count]->widerruf;
               $this->params->widerruf_wk = max($this->params->widerruf_wk, $widerruf);

               // In SESSION speichern, da nachfolgend benötigt
               $_SESSION['widerruf_wk'] = $this->params->widerruf_wk;

               if ($data[$count]->fsk_check == 'y') {
                  $_SESSION['fsk_artikel'] = true;
               }

               $count++;
            }

            else {
               $del_arr[] = $i;
            }

            // In Session speichern
            $_SESSION['widerruf_wk'] = $this->params->widerruf_wk;
         } // end for

         if (count($del_arr) > 0) {
            foreach ($del_arr as $wk_id) {
               $this->delWk($wk_id);
            }
         }

         // Portal: Anzahl Händler (als Array-Eintrag je Händler)
         $berechnung = Control::getBerechnungen();
         // 20.11.2017: Wegen Problemen mit MinPreis false für Berechnung mit 9 statt 2 Nachkommastellen
         unset($_SESSION['sonderpreis_netto']);
         unset($_SESSION['sonderpreis_brutto']);
         unset($_SESSION['sonderpreis_steuer']);

         $back = $berechnung->berechneWkArtikel($data, $haendler_id, false);

         return $back;
      }
   }

   public function berechneWK($haendler = 0, $bestellung = false) {
      $user         = $_SESSION['user'];
      $versand_land = $this->params->firma['versandart_land'];

      if ($this->params->postTest('versand_land') && $this->params->postInt('versand_land') > 0) {
         $versand_land = $this->params->postInt('versand_land');
         $_SESSION['wk_land'] = $versand_land;
      }

      // Erweitertes Array aus getArticleById
      $data = $this->getWk($haendler);

      if ((is_array($data) ? count($data) : 0) > 0) {
         $berechnung = Control::getBerechnungen();
         $wk_arr = $berechnung->berechneWk($bestellung, $haendler);
      }

      else {
         $wk_arr = array();
      }

      $wk_arr['data'] = $data;
      return $wk_arr;
   }

   public function checkLagermenge($id, $menge) {
      if ($id == 0) {
         return number_format(1, 1, '.', '');
      }

      $lagermenge = $this->db_extern->querySingleObject("SELECT a.menge, i.masse_komma, i.masse_check FROM #__articles AS a, #__articles_info AS i WHERE a.id = $id AND i.id = a.parent_id");
      $komma = 0;

      if ($lagermenge->masse_check == 'y') {
         $komma = (int)$lagermenge->masse_komma;
      }

      if ($this->params->firma['lager_leer'] == 'n') {
         if ((float)$lagermenge->menge < (float)$menge) {
            $menge = (float)$lagermenge->menge;
         }
      }

      return number_format( (floor( $menge * pow(10 ,$komma) ) / pow(10 ,$komma)), $komma, '.', '' );
   }

   private function _countHaendler() {
      $haendler_arr = array();
      $data = $this->params->warenkorb;

      for ($i = 0; $i < count($data); $i++) {
         if (isset($haendler_arr[$data[$i]['haendler_id']])) {
            $haendler_arr[$data[$i]['haendler_id']]++;
         }
         else {
            $haendler_arr[$data[$i]['haendler_id']] = 1;
         }
      }

      $back_arr = array();
      foreach($haendler_arr as $k => $v) {
         $back_arr[] = array($k, $v);
      }

      return $back_arr;
   }

   public function cleanWk($haendler_id = 0) {
      // Warenkorb löschen und DB bei registrierten User
      if ($this->params->user_id > 0) {
         if ($haendler_id == 0) {
            $sql = "DELETE FROM #__warenkorb WHERE user_id = ".$this->params->user_id;
         }

         else {
            $sql = "DELETE FROM #__warenkorb WHERE user_id = ".$this->params->user_id." AND haendler_id = $haendler_id";
         }

         $query = $this->db->query($sql);
      }

      if ($haendler_id != 0) {
         $_SESSION['warenkorb'] = array();

         for ($i = 0; $i < count($this->params->warenkorb); $i++) {
            if ($this->params->warenkorb[$i]['haendler_id'] != $haendler_id) {
               $_SESSION['warenkorb'][] = $this->params->warenkorb[$i];
            }
         }

         $this->params->warenkorb = $_SESSION['warenkorb'];
      }

      else {
         $this->params->warenkorb = array();
         $_SESSION['warenkorb'] = array();
      }

      if ($this->params->warenkorb == '') {
         $this->params->warenkorb = array();
         $_SESSION['warenkorb'] = array();
      }
   }

   // Motiv-Upload nach /tmp
   private function _checkMotiveUpload($wk = null) {
      $dir = SHOP_PATH.'/tmp/';
      $motiv_upload = array();
      $motiv_upload['motiv_upload_name'] = '';
      $motiv_upload['motiv_upload_user'] = '';
      $motiv_upload['motiv_upload_text'] = ' ';

      if ($wk != null && is_file($dir.$wk['motiv_upload_name'])) {
         $motiv_upload['motiv_upload_name'] = $wk['motiv_upload_name'];
         $motiv_upload['motiv_upload_user'] = $wk['motiv_upload_user'];
      }

      // Text
//      if (isset($_POST['motiv_upload_text'])) {
//         $motiv_upload['motiv_upload_text'] = $this->params->postString('motiv_upload_text');
//      }
      $motiv_upload['motiv_upload_text'] = $this->params->postString('motiv_upload_text');

      // Bild
      if (isset($_FILES['motiv_upload_file']['name']) && $_FILES['motiv_upload_file']['name'] != '') {
         // Evtl. vorhandene Datei löschen
         if (isset($wk['motiv_upload_name']) && $wk['motiv_upload_name'] != '' && is_file($dir.$wk['motiv_upload_name'])) {
            unlink($dir.$wk['motiv_upload_name']);
         }

         // Datei in /tmp speichern. Wird erst nach Bestellung nach /downloads/motiv_dateien verschoben
         $tmp = explode('.', $_FILES['motiv_upload_file']['name']);

         if ($tmp[count($tmp) -1] == 'php' || $tmp[count($tmp) -1] == 'phtml' || $tmp[count($tmp) -1] == 'js' || $tmp[count($tmp) -1] == 'html' || $tmp[count($tmp) -1] == 'htm') {
            $tmp[count($tmp) -1] .= '.script';
            $_FILES['motiv_upload_file']['name'] .= '.script';
         }

         $datei = date('YmdHis').'.'.$tmp[count($tmp) - 1];

         $motiv_upload['motiv_upload_name'] = $datei;
         // Original-Filename;
         $motiv_upload['motiv_upload_user'] = $_FILES['motiv_upload_file']['name'];

         move_uploaded_file($_FILES["motiv_upload_file"]["tmp_name"], $dir.$datei);
      }
      return $motiv_upload;
   }
}
