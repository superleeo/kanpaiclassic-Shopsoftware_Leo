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

// Nicht angemeldete User: Merkliste wird in $_SESSION['my_merkliste'] gespeichert
// Angemeldete User: Merkliste wird in DB.merkliste gespeichert, keine Merkliste in SESSION
// Während Programmablauf wird Merkliste in Params::$my_merkliste gespeichert
// Hat User nach Anmeldung Merkliste in SESSION, wird Merkliste mit DB verbunden und SESSION gelöscht

class KANPAICLASSIC_merkliste {
   private $db        = null;
   private $db_extern = null;
   private $params    = null;
   private $artikel   = null;

   public function __construct() {
      $this->params    = Control::getParams();
      $this->db        = Control::getDb();
      $this->db_extern = Control::getExternDB();
      $this->artikel   = Control::getArticles();

      // Merkliste aus DB laden, wenn notwendig ML-SESSION und ML-DB verbinden (nach login)
      if ($this->params->user_id > 0) {
         if (isset($_SESSION['my_merkliste'])) {
            $this->verbindeML();
         }

         $this->loadML();
      }

      // sonst aus Session
      else {
         if (!isset($_SESSION['my_merkliste'])) {
            $_SESSION['my_merkliste'] = array();
         }
         $this->params->my_merkliste = $_SESSION['my_merkliste'];
      }
   }

   public function loadML() {
      $this->params->my_merkliste = array();
      unset($_SESSION['my_merkliste']);

      $sql = "SELECT id, art_id, cat_id, art_menge, haendler_id, foto_set, foto_sort, motiv_uploadp_check, motiv_uploadt_check, motiv_upload_name,
                     motiv_upload_user, motiv_upload_text, configurator, rechner_check, rechner_breite, rechner_hoehe, rechner_tiefe, rechner_mode, rechner_einheit, preismatrix, mixer
                     FROM #__merkliste WHERE user_id = ".$this->params->user_id;
      $query = $this->db->query($sql);

      if ($query) {
         while ($data = $this->db->getObject()) {
            $this->params->my_merkliste[] = array(
                                               'art_id'              => (int)$data->art_id,
                                               'cat_id'              => (int)$data->cat_id,
                                               'art_menge'           => (float)$data->art_menge,
                                               'ml_id'               => (int)$data->id,
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

      $_SESSION['my_merkliste'] = $this->params->my_merkliste;
      return;
   }

   // ML in DB speichern
   public function saveML() {
      foreach ($this->params->my_merkliste as $key => $ml) {
         if ($ml['ml_id'] && $ml['wk_change']) {
            $sql = "UPDATE #__merkliste SET art_menge = ".$ml['art_menge']." WHERE id = ".$ml['ml_id'];
            $this->db->query($sql);
            $this->params->my_merkliste[$key]['wk_change'] = false;
         }

         if ($ml['ml_id'] == 0 && ($ml['art_id'] > 0 || $ml['cat_id'] > 0)) {
            $sql = "INSERT INTO #__merkliste SET
                       user_id             = ".$this->params->user_id.",
                       art_id              = ".$ml['art_id'].",
                       cat_id              = ".$ml['cat_id'].",
                       art_menge           = '".$ml['art_menge']."',
                       foto_set            = ".$ml['foto_set'].",
                       foto_sort           = ".$ml['foto_sort'].",
                       haendler_id         = ".$ml['haendler_id'].",
                       motiv_uploadp_check = '".$ml['motiv_uploadp_check']."',
                       motiv_uploadt_check = '".$ml['motiv_uploadt_check']."',
                       motiv_upload_name   = '".$ml['motiv_upload_name']."',
                       motiv_upload_user   = '".$ml['motiv_upload_user']."',
                       motiv_upload_text   = '".$ml['motiv_upload_text']."',
                       configurator        = '".$this->db->escape($ml['configurator'])."',
                       rechner_check       = '".$ml['rechner_check']."',
                       rechner_breite      = '".$ml['rechner_breite']."',
                       rechner_hoehe       = '".$ml['rechner_hoehe']."',
                       rechner_tiefe       = '".$ml['rechner_tiefe']."',
                       rechner_mode        = '".$ml['rechner_mode']."',
                       rechner_einheit     = '".$ml['rechner_einheit']."',
                       preismatrix         = '".$this->db->escape($ml['preismatrix'])."',
                       mixer               = '".$this->db->escape($ml['mixer'])."'";
            $this->db->query($sql);
            $this->params->my_merkliste[$key]['ml_change'] = '';
            $this->params->my_merkliste[$key]['ml_id'] = 1;
         }
      }

      $this->loadML();
      return;
   }

   // Merkliste aus $_Session und DB verbinden, nur nach Login,
   // damit identische Artikel nicht mehrfach gespeichert werden
   // in diesem Fall Mengen addieren
   public function verbindeML() {
      $test = 0;

      if (is_array($_SESSION['my_merkliste']) && count($_SESSION['my_merkliste']) > 0) {
         foreach ($_SESSION['my_merkliste'] as $key => $ml) {
            $menge = 0;
            $test = 0;

            if ($ml['ml_id'] > 0) {
               continue;
            }

            if ($ml['ml_id'] == 0) {
               $menge = $ml['art_menge'];
            }

            if ($ml['rechner_check'] != 'y') {
               // Mengen zusammenfassen
               $sql = "UPDATE #__merkliste SET art_menge = art_menge + " . $menge . " WHERE user_id = " . $this->params->user_id . " AND art_id = " .$ml['art_id'];
               $test = $this->db->query($sql);
            }

            // Update erfolgreich? Sonst als neuen Eintrag speichern
            if ($test == 0) {
               $sql = "INSERT INTO #__merkliste SET
                          user_id             = ".$this->params->user_id.",
                          art_id              = ".$ml['art_id'].",
                          art_menge           = '".$ml['art_menge']."',
                          foto_set            = ".$ml['foto_set'].",
                          foto_sort           = ".$ml['foto_sort'].",
                          haendler_id         = ".$ml['haendler_id'].",
                          motiv_uploadp_check = '".$ml['motiv_uploadp_check']."',
                          motiv_uploadt_check = '".$ml['motiv_uploadt_check']."',
                          motiv_upload_name   = '".$ml['motiv_upload_name']."',
                          motiv_upload_user   = '".$ml['motiv_upload_user']."',
                          motiv_upload_text   = '".$ml['motiv_upload_text']."',
                          configurator        = '".$ml['configurator']."',
                          rechner_check       = '".$ml['rechner_check']."',
                          rechner_breite      = '".$ml['rechner_breite']."',
                          rechner_hoehe       = '".$ml['rechner_hoehe']."',
                          rechner_tiefe       = '".$ml['rechner_tiefe']."',
                          rechner_mode        = '".$ml['rechner_mode']."',
                          rechner_einheit     = '".$ml['rechner_einheit']."',
                          preismatrix         = '".$this->db->escape($ml['preismatrix'])."',
                          mixer               = '".$this->db->escape($ml['mixer'])."'";
               $this->db->query($sql);
            }

            $this->params->my_merkliste[$key]['wk_change'] = false;
         }
      }
      return;
   }

   // WK Artikel hinzufügen und bei angemeld. Kunden speichern
   public function addArticle() {
      $this->_addML();

      if ($this->params->user_id > 0) {
         $this->saveML();
      }
   }

   // Artikel in WK übertragen
   public function mlWK($ml_id) {
      // Test, ob Artikel in ML
      if (!isset($this->params->my_merkliste[$ml_id]['art_id'])) {
         return false;
      }

      // Art-ID aus Merkliste holen und als POST-Parameter an WK übergeben
      $art_id                   = $this->params->my_merkliste[$ml_id]['art_id'];
      $_POST['foto_set']        = $this->params->my_merkliste[$ml_id]['foto_set'];
      $_POST['foto_sort']       = $this->params->my_merkliste[$ml_id]['foto_sort'];
      $_POST['rechner_breite']  = $this->params->my_merkliste[$ml_id]['rechner_breite'];
      $_POST['rechner_hoehe']   = $this->params->my_merkliste[$ml_id]['rechner_hoehe'];
      $_POST['rechner_tiefe']   = $this->params->my_merkliste[$ml_id]['rechner_tiefe'];
      $_POST['rechner_mode']    = $this->params->my_merkliste[$ml_id]['rechner_mode'];
      $_POST['rechner_check']   = $this->params->my_merkliste[$ml_id]['rechner_check'];
      $_POST['rechner_einheit'] = $this->params->my_merkliste[$ml_id]['rechner_einheit'];
      $_POST['preismatrix']     = $this->params->my_merkliste[$ml_id]['preismatrix'];
      $_POST['mixer']           = $this->params->my_merkliste[$ml_id]['mixer'];
      $_POST['cat_id']          = $this->params->my_merkliste[$ml_id]['cat_id'];
      // Test, ob Artikel noch vorhanden ist
      $test = $this->db_extern->querySingleValue("SELECT online FROM #__articles WHERE id = $art_id");

      // Dann Artikel in WK eintragen
      if ($test || $this->params->my_merkliste[$ml_id]['cat_id'] > 0) {
         $this->params->art_id = $art_id;
         $this->params->art_menge = $this->params->my_merkliste[$ml_id]['art_menge'];
         $ml_arr = array();
         $ml_arr['motiv_uploadp_check'] = $this->params->my_merkliste[$ml_id]['motiv_uploadp_check'];
         $ml_arr['motiv_uploadt_check'] = $this->params->my_merkliste[$ml_id]['motiv_uploadt_check'];
         $ml_arr['motiv_upload_name']   = $this->params->my_merkliste[$ml_id]['motiv_upload_name'];
         $ml_arr['motiv_upload_user']   = $this->params->my_merkliste[$ml_id]['motiv_upload_user'];
         $ml_arr['motiv_upload_text']   = $this->params->my_merkliste[$ml_id]['motiv_upload_text'];
         $ml_arr['configurator']        = $this->params->my_merkliste[$ml_id]['configurator'];

         $wk = Control::getWk();
         $wk->from_ml = true;
         $wk->from_ml_arr = $ml_arr;
         $wk->addArticle();
         $wk->from_ml = false;

         // Eintrag in ML löschen (deaktiviert, bleibt in Merkliste)
         // $this->delML($ml_id);
         $text = Control::getText();
         $_SESSION['admin_msg'] = '<div class="fliesstext text_gross" style="text-align:center; margin:30px 0 50px 0;">'.$text->get('merkliste', 'in_wk').'</div><a href="'.SHOP_URL_IDX.'/warenkorb" id="feedback_ml_but" class="col_button bg_button text_gross button55">Warenkorb</a>';
         return true;
      }
      return false;
   }

   // Artikel aus WK löschen
   public function delMl($ml_id) {
      $temp = array();

      for ($i = 0; $i < count($this->params->my_merkliste); $i++) {
         $ml = $this->params->my_merkliste[$i];

         if ($ml_id != $i) {
            $temp[] = $this->params->my_merkliste[$i];
         }

         elseif ($this->params->user_id) {
            $sql = "DELETE FROM #__merkliste WHERE id = ".$ml['ml_id'];
            $this->db->query($sql);
         }
      }


      if ($this->params->user_id > 0) {
         //$this->saveML();
         $this->loadML();
      }
      else {
         $this->params->my_merkliste = $temp;
         $_SESSION['my_merkliste'] = $temp;
      }


      return;
   }

   // ML Artikel hinzufügen (nicht DB, nur als geändert markieren für saveMl(), falls user eingelogged ist)
   private function _addML() {
      $check           = false;
      $configurator    = '';
      $rechner_breite  = $this->params->postFloat('rechner_breite');
      $rechner_hoehe   = $this->params->postFloat('rechner_hoehe');
      $rechner_tiefe   = $this->params->postFloat('rechner_tiefe');
      $rechner_mode    = $this->params->postFloat('rechner_mode');
      $rechner_check   = $this->params->postCheckbox('rechner_check');
      $rechner_einheit = $this->params->postString('rechner_einheit');
      $foto_set        = $this->params->postInt('foto_set');
      $foto_sort       = $this->params->postInt('foto_sort');
      $preismatrix     = $this->params->postString('preismatrix');
      $cat_id          = $this->params->postInt('cat_id');
      $mixer           = $this->params->postString('mixer');

      // falls Artikel vorhanden, Menge erhöhen
      for ($i = 0; $i < count($this->params->my_merkliste); $i++) {
         // Bei Fotoartikel mehrere gleiche Artikel ermöglichen, da Preis aus Set-Daten berechnet wird
         $motiv_upload = array();
         $motiv_upload['motiv_upload_name'] = '';
         $motiv_upload['motiv_upload_user'] = '';
         $motiv_upload['motiv_upload_text'] = '';

         $check        = false;
         $configurator_check = '';
         $configurator = '';

         if (defined('CONF_MODULE_MEGACONFIGURATOR')) {
            // Test, ob Artikel Configurator hat
            $configurator_check = $this->db_extern->querySingleValue("SELECT i.configurator FROM #__articles_info AS i, #__articles AS a WHERE i.id = a.parent_id AND a.id = ".$this->params->art_id);
         }

         // Kein Foto, kein Multikonfigurator, kein Rechner
         if ((int)$this->params->postInt('foto_sort') < 1 && $preismatrix == '' && $configurator_check != 'y' && $rechner_check == 'n') {
            if (isset($this->params->my_merkliste[$i]['art_id'], $this->params->my_merkliste[$i]['art_menge'], $this->params->my_merkliste[$i]['ml_id'], $this->params->my_merkliste[$i]['wk_change'])) {
               if ($this->params->my_merkliste[$i]['art_id'] == $this->params->art_id) {
                  $this->params->my_merkliste[$i]['art_menge'] =  1;
                  $this->params->my_merkliste[$i]['wk_change'] = true;
                  $check = true;

                  if (defined('CONF_MODULE_MOTIVUL')) {
                     $mu_check = $this->db_extern->querySingleObject("SELECT i.motiv_uploadp_check, i.motiv_uploadt_check FROM #__articles AS a, #__articles_info AS i WHERE a.id = ".$this->params->art_id." AND i.id = a.parent_id");

                     if ($mu_check && ($mu_check->motiv_uploadp_check == 'y' || $mu_check->motiv_uploadt_check == 'y')) {
                        $motiv_upload = $this->_checkMotiveUpload($this->params->my_merkliste[$i]);
                        $this->params->my_merkliste[$i]['motiv_uploadp_check'] = $mu_check->motiv_uploadp_check;
                        $this->params->my_merkliste[$i]['motiv_uploadt_check'] = $mu_check->motiv_uploadt_check;
                        $this->params->my_merkliste[$i]['motiv_upload_name'] = $motiv_upload['motiv_upload_name'];
                        $this->params->my_merkliste[$i]['motiv_upload_user'] = $motiv_upload['motiv_upload_user'];
                        $this->params->my_merkliste[$i]['motiv_upload_text'] = $motiv_upload['motiv_upload_text'];
                     }
                  }
               }
            }
         }
      }

      // Artikel war nicht in der Merkliste -> hinzufügen
      if (!$check) {
         $foto_set = $this->params->postInt('foto_set');
         $foto_sort = $this->params->postInt('foto_sort');

         if (defined('CONF_MODULE_MATRIX')) {
            $preismatrix = $this->params->postString('preismatrix');
            $preismatrix = trim(str_ireplace('&quot;', '"', $preismatrix), '"');
         }

         if (defined('CONF_MODULE_MEGACONFIGURATOR')) {
            // Test, ob Artikel Configurator hat
            $test = $this->db_extern->querySingleValue("SELECT i.configurator_check FROM #__articles_info AS i, #__articles AS a WHERE i.id = a.parent_id AND a.id = ".$this->params->art_id);

            if ($test == 'y') {
               $conf = json_decode($this->params->postString('configurator'), true);
               $tmp = array();
               $wert_arr = array();
               $merkmal = 0;

               for ($i = 0; $i < (is_array($conf) ? count($conf) : 0); $i++) {
                  // Konfigurator mit Texten
                  if ($conf[$i] == null || $conf[$i] == 'null') {
                     break;
                  }

                  $merkmal = $conf[$i][0];
                  $wert_arr = array();

                  for ($j = 0; $j < count($conf[$i][1]); $j++) {
                     $wert = (int)$conf[$i][1][$j][0];
                     $test = explode(';', $conf[$i][1][$j][1]);

                     if (count($test) == 4) {
                        if (md5((float)$test[1].$test[2].$test[3]) != $test[0]) {
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

         $menge = 1;

         if ($rechner_check == 'y') {
            $menge = $this->params->art_menge;
         }

         $motiv_uploadp_check = 'n';
         $motiv_uploadt_check = 'n';
         $motiv_upload_name   = '';
         $motiv_upload_user   = '';
         $motiv_upload_text   = '';

         if (defined('CONF_MODULE_MOTIVUL')) {
            $mu_check = $this->db_extern->querySingleObject("SELECT i.motiv_uploadp_check, i.motiv_uploadt_check FROM #__articles AS a, #__articles_info AS i WHERE a.id = ".$this->params->art_id." AND i.id = a.parent_id");

            if ($mu_check && ($mu_check->motiv_uploadp_check == 'y' || $mu_check->motiv_uploadt_check == 'y')) {
               $motiv_uploadp_check = $mu_check->motiv_uploadp_check;
               $motiv_uploadt_check = $mu_check->motiv_uploadt_check;
               $motiv_upload        = $this->_checkMotiveUpload();
               $motiv_upload_name   = $motiv_upload['motiv_upload_name'];
               $motiv_upload_user   = $motiv_upload['motiv_upload_user'];
               $motiv_upload_text   = $motiv_upload['motiv_upload_text'];
            }
         }

         $haendler_id = 0;
         if (defined('CONF_MODULE_PORTAL')) {
            $haendler_id = (int)$this->db_extern->querySingleValue("SELECT i.haendler_id FROM #__articles_info AS i, #__articles AS a WHERE a.parent_id = i.id AND a.id = ".$this->params->art_id);
         }

         $this->params->my_merkliste[] = array(
                                           'art_id'              => $this->params->art_id,
                                           'cat_id'              => $cat_id,
                                           'art_menge'           => $menge,
                                           'ml_id'               => 0,
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
      }

      if ($this->params->user_id < 1) {
         // Warenkorb in Session übernehmen
         $_SESSION['my_merkliste'] = $this->params->my_merkliste;
      }

      return;
   }

   // Daten für Anzeige ML generieren
   public function getML($haendler_id = 0) {
      // Wenn Artikel im WK vorhanden
      $lang = $this->params->selected_lang;

      if (count($this->params->my_merkliste)) {
         // Artikeldaten aus DB lesen
         $data    = array();
         $del_arr = array();
         $back    = false;

         // Artikeldaten auslesen
         for ($i = 0; $i < count($this->params->my_merkliste); $i++) {
            $this->print_summe = true;
            $ml = $this->params->my_merkliste[$i];

            if ($ml['mixer'] == '') {
               $d = $this->artikel->getArticleById($ml['art_id'], '', '', (int)$ml['foto_set'], (int)$ml['foto_sort']);
               $data[$i] = $d;
               $data[$i]->mixer = '';
            }

            else {
               // Mixer-Kategorie
               if ((int)$ml['cat_id'] > 0) {
                  $mixer1 = Control::getModuleMixerKategorie();
                  $d = $mixer1->getArticleById($ml['cat_id'], $this->params->selected_lang, $this->params->selected_lang, (int)$ml['foto_set'], (int)$ml['foto_sort'], false, $ml['mixer']);
                  $data[$i] = $d;
                  $data[$i]->menge = 1;
               }

               // Artikel-Mixer
               else {
                  $d = $this->artikel->getArticleById($ml['art_id'], '', '', (int)$ml['foto_set'], (int)$ml['foto_sort']);
                  $data[$i] = $d;
               }
            }

            // Und mit Daten aus WK überschreiben
            if ($d) {
//               $data[$i] = $d;
               $data[$i]->cat_id          = $ml['cat_id'];
               $data[$i]->preis           = 0;
               $data[$i]->foto_sort       = $ml['foto_sort'];
               $data[$i]->foto_set        = $ml['foto_set'];
               $data[$i]->wk_id           = $i;
               $data[$i]->artikel_menge   = $ml['art_menge'];
               // $data[$i]->rechner_menge   = $ml['rechner_breite'] * $ml['rechner_hoehe'];
               $data[$i]->rechner_menge   = $ml['rechner_breite'] * $ml['rechner_hoehe'] * $ml['rechner_tiefe'];
               $data[$i]->configurator    = $ml['configurator'];
               $data[$i]->rechner_check   = $ml['rechner_check'];
               $data[$i]->rechner_breite  = $ml['rechner_breite'];
               $data[$i]->rechner_hoehe   = $ml['rechner_hoehe'];
               $data[$i]->rechner_tiefe   = $ml['rechner_tiefe'];
               $data[$i]->rechner_mode    = $ml['rechner_mode'];
               $data[$i]->rechner_einheit = $ml['rechner_einheit'];
               $data[$i]->preismatrix     = $ml['preismatrix'];

               $show_versandkosten = ((((int)$this->params->firma['versandart_1'] == 1 || (int)$this->params->firma['versandart_1']) == 5) && $this->params->firma['vers_grafik_check'] != 'y'/* && (float)$this->main->versand_preis == 0*/? true : false);
               $versandkosten_incl = ($show_versandkosten && $data[$i]->versandfrei_check == 'y' ? true : false);
               $data[$i]->versandkosten_incl     = $versandkosten_incl;

            }

            else {
               $del_arr[] = $i;
            }
         } // end for

         if (is_array($del_arr) && count($del_arr) > 0) {
            foreach ($del_arr as $ml_id => $v) {
               $this->delMl($ml_id);
            }
         }

         if (is_array($data) && count($data) > 0) {
            $berechnung = Control::getBerechnungen();
            $back       = $berechnung->berechneWkArtikel($data, $haendler_id);
         }

         return $back;
      }
   }

   public function cleanML() {
      // Merkliste löschen und DB bei registrierten User
      if ($this->params->user_id > 0) {
         $sql = "DELETE FROM #__merkliste WHERE user_id = ".$this->params->user_id;
         $query = $this->db->query($sql);
      }

      $this->params->my_merkliste = array();
      unset($_SESSION['my_merkliste']);
   }

   private function _checkMotiveUpload($wk = null) {
      $dir = $this->params->filepath.'/tmp/';
      $motiv_upload = array();
      $motiv_upload['motiv_upload_name'] = '';
      $motiv_upload['motiv_upload_user'] = '';
      $motiv_upload['motiv_upload_text'] = $wk['motiv_upload_text'];

      if ($wk != null && is_file($dir.$wk['motiv_upload_name'])) {
         $motiv_upload['motiv_upload_name'] = $wk['motiv_upload_name'];
         $motiv_upload['motiv_upload_user'] = $wk['motiv_upload_user'];
      }

      if (isset($_POST['motiv_upload_text'])) {
         $motiv_upload['motiv_upload_text'] = $this->params->postString('motiv_upload_text');
      }
      if (isset($_FILES['motiv_upload_file']['name']) && $_FILES['motiv_upload_file']['name'] != '') {
         // Evtl. vorhandene Datei löschen
         if ($wk['motiv_upload_name'] != '' && is_file($dir.$wk['motiv_upload_name'])) {
            unlink($dir.$wk['motiv_upload_name']);
         }

         // Datei speichern
         $tmp = explode('.', $_FILES['motiv_upload_file']['name']);
         $datei = date('YmdHis').'.'.$tmp[count($tmp) - 1];
         $motiv_upload['motiv_upload_user'] = $_FILES['motiv_upload_file']['name'];
         $motiv_upload['motiv_upload_name'] = $datei;

         move_uploaded_file($_FILES["motiv_upload_file"]["tmp_name"], $dir.$datei);
      }
      return $motiv_upload;
   }
}
?>