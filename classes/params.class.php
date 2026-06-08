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

class KANPAICLASSIC_params extends KANPAICLASSIC_session
{
   // aus Session-Variablen
   public $user_id = 0;
   public $kat_id = 0;
   public $art_id = 0;
   public $parent_id;

   public $art_anzahl = 0;   // Artikel pro Seite
   public $artikel_max = 0;
   public $warenkorb = array();
   public $ajax_mege = 0;
   public $my_merkliste = array();
   public $gast_merkliste = array();
   public $promo_artikel = 0;
   public $promo_artikel2 = 0;
   public $loginh = false;
   public $admin_user = 0;

   public $session_id = 0;
   public $task = '';
   public $task_sub = '';
   public $mode = '';
   public $loginerror = false;
   public $mailvorhanden = false;
   public $validate1 = '';
   public $validate = null;
   public $valid_user = false;

   public $isAjax              = false;
   public $isNew               = false;

   public $art_name            = '';
   public $art_text            = '';

   public $cat_name            = '';
   public $cat_text            = '';
   public $cat_mode            = 0;
   public $anz_kats            = 0;
   public $bestellnummer       = '';
   public $email               = '';
   public $re_id               = '';
   public $dl_link             = '';
   public $widerruf_wk         = 1;
   public $haendler_id         = 0;
   public $is_haendler         = false;
   public $haendler_arr        = array();
   public $haendler_p_arr      = array();
   public $foto_set            = 0;
   public $foto_set_list       = 0;
   public $paymenttext         = '';
   public $details_script      = '';
   public $ajax_menge          = 0;
   public $ajax_menge_ok       = 1;
   public $amazon_reference_id = '';

   public $wk_changed          = false;
   public $wk_changed_id       = 0;


   public $debug               = '';
   public $debugdemo           = '';
   public $text                = null;
   public $head                = '';
   public $locale              = '';
   public $html5_mode          = '';
   public $is_360grad          = false;
   Public $select_has_childs   = true;


   public function __construct() {
      parent::__construct();
      $server_name = $_SERVER['SERVER_NAME'];

      if (defined('CONF_USE_HTTP_HOST')) {
         $server_name = $_SERVER['HTTP_HOST'];
      }

      // Umleitung auf HTTPS durch Shop
      if (defined('CONF_USE_HTTPS') && CONF_USE_HTTPS && !isset($_SERVER['HTTPS'])) {
         header('HTTP/1.1 301 Moved Permanently');
         header('Location: https://'.$server_name.$_SERVER['REQUEST_URI']);
         exit;
      }

//      $protocol    = 'http://';

//      if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
//         $protocol = 'https://';
//      }

      // URL mit /index.php
//      if (!defined('CONF_USE_HTACCESS')) {
//         $this->baseurl = $protocol.$server_name.$this->basepath.'/index.php';
//      }

//      else {
//         if ($this->basepath == '/' || $this->basepath == '') {
//            $this->baseurl = $protocol.$server_name;
//         }

//         else {
//            $this->baseurl = $protocol.rtrim($server_name, '/').'/'.ltrim(rtrim($this->basepath, '/'), '/');
//         }
//      }

      // Nur für beauty: Nach Änderung Seite / Redirect akt. Seite setzen
      $this->artikel_seite = $_SESSION['artikel_seite'];


   }

   // Parameter nach index.php/../.. auswerten, falls vorhanden - !mod_rewrite
   public function setParams() {
      if (!isset($_SESSION['last_link'])) {
         $_SESSION['last_link'] = SHOP_URL;
      }


      $this->locale = $this->db->querySingleValue("SELECT locale FROM #__laender WHERE kurz = '".$this->selected_lang."'");
      $this->text = Control::getText();
      $funcs = array();

      if (isset($_SERVER['REQUEST_URI'])) {
         $my_funcs = trim(str_replace(str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']), '', $_SERVER['REQUEST_URI']), '/');
         $my_funcs = preg_replace('|\?&bid=\d+\-\d|', '', $my_funcs);

         $test = explode('?', $my_funcs);

         if (count($test) > 0) {
            $my_funcs = $test[0];
         }

         // Suche als URL
         if ($this->getString('suchen') != '') {
            $my_funcs = 'suchen/'.$this->getString('suchen');
         }

         if ($my_funcs) {
            $cat_string = '';
            $cat_name   = '';

            if (strpos($my_funcs, '/') === false) {
               $funcs[0] = $my_funcs;
            }

            else {
               $funcs = explode('/', $my_funcs);
            }

            // index.php überspringen
            if (isset($funcs[0]) && $funcs[0] == 'index.php') {
               array_shift($funcs);
            }

            // Bei AJAX wird ajax vorangestellt
            if (isset($funcs[0]) && $funcs[0] == 'ajax') {
               $this->isAjax = true;
               array_shift($funcs);
            }

            // Bei Ausgabe in neuem Fenster/Tab wird new vorangestellt
            if (isset($funcs[0]) && $funcs[0] == 'new') {
               $this->isNew = true;
               array_shift($funcs);
            }

            // Parameter auswerten, Daten bereitstellen und Aktion für index.php festlegen
            if (isset($funcs[0])) {
               // Artikel wird als /<lang>_<art_id>/<artikelname> übertragen
               if (substr($funcs[0], 3, 1) == '_' && is_numeric(substr($funcs[0], 4))) {
                  $this->selected_lang = $this->_checkLang(substr($funcs[0], 0, 3));
                  $funcs[1] = (int)substr($funcs[0], 4);
                  $funcs[0] = 'artikel';

                  $this->addLast($funcs[1]);
               }

               // Artikel bei lang = deu
               else if (is_numeric($funcs[0])) {
                  $this->selected_lang = 'deu';
                  $funcs[1] = (int)$funcs[0];
                  $funcs[0] = 'artikel';

                  $this->addLast($funcs[1]);
               }

               else if (substr($funcs[0], 3, 2) === '__' && is_numeric(substr($funcs[0], 5))) {
                  $this->foto_set_list = (int)substr($funcs[0], 5);
                  $funcs[1] = 0;
                  $funcs[0] = 'foto_list';
               }

               else if (substr($funcs[0], 0, 2) === '__' && is_numeric(substr($funcs[0], 2))) {
                  $this->foto_set_list = (int)substr($funcs[0], 2);
                  $funcs[1] = 0;
                  $funcs[0] = 'foto_list';
               }

               // Kategorie wird als /k<lang><kat_id-<seite>/<kategoriename> übertragen
               else if (preg_match('|^k([a-z]{3})?(\d+)\-*(\d*)|', $funcs[0], $test)) {
                  unset($_SESSION['kategoriefilter']);
                  unset($_SESSION['mixer']);
                  $cat_name = (isset($funcs[1]) ? '-'.$funcs[1] : '');
                  $this->selected_lang = $this->_checkLang($test[1]);
                  $funcs[0] = 'kategorie';
                  $funcs[1] = (int)$test[2];

                  // Seite
                  if ($test[3] == '') {
                     $test[3] = 1;
                  }

                  unset($_SESSION['haendler_id']);

                  $_SESSION['artikel_seite'] = (int)$test[3];
                  $_SESSION['last_link'] = SHOP_URL_IDX.'/k'.($this->selected_lang != 'deu' ? $this->selected_lang : '').$test[2].((int)$test[3] > 1 ? '-'.$test[3] : '').($cat_name != '' ? '/'.$cat_name : '');
               }

               // falls durch Fehler default_lang '' wird
               if ($this->selected_lang == '') {
                  $this->selected_lang = 'deu';
               }

               $_SESSION['lang'] = $this->selected_lang;
               $this->locale = $this->db->querySingleValue("SELECT locale FROM #__laender WHERE kurz = '".$this->selected_lang."'");

               // Startbild/Video nicht anzeigen / Horiz. Menü anzeigen
               if ($funcs[0] == 'shop') {
                  $this->set_offset = true;
                  $funcs[0] = '';
                  $this->kat_id = 0;
                  $this->art_id = 0;

                  $_SESSION['artikel_seite'] = 1;
                  $_SESSION['task'] = '';
                  $_SESSION['kat_id'] = 0;
                  $_SESSION['art_id'] = 0;
                  unset($_SESSION['suche']);
                  unset($_SESSION['kategoriefilter']);
               }

               if (defined('FRONT_SESSION_LOG')) {
                  if (!is_dir(DEBUG_LOG_DIR.'/session')) {
                     mkdir(DEBUG_LOG_DIR.'/session');
                  }

                  if ($funcs[0] == 'inwarenkorb' || $funcs[0] == 'warenkorb' || $funcs[0] == 'in_wk' || $funcs[0] == 'wk_akt' || $funcs[0] == 'lieferadresse' || $funcs[0] == 'lieferung' || $funcs[0] == 'bezahlung') {
                     $fh = fopen(DEBUG_LOG_DIR.'/session/'. session_id(), 'a');
                     fwrite($fh, date('d.m.Y H:i:s')."\n".print_r($funcs, true)."\n".print_r($_REQUEST, true)."\n");
                     fclose($fh);
                  }
               }

               switch ($funcs[0]) {
//                  case 'test':
//                     $paydirekt = Control::getModulePaydirekt();
//                     $paydirekt->test();
//                     exit;

                  case 'menuMode':
                     $_SESSION['device'] = $this->postString('menu_mode');
//                     $device_detect = $this->postString('device_detect');
//                     $device = $_SESSION['device'];

                     echo json_encode(['status' => 'ok']);
                     exit;
                     break;

                  case 'synctime':
                     echo round(microtime(true) * 1000); // micros in ms
                     exit;
                     break;

                  case 'catpass':
                     $categorie = Control::getCategories();
                     if ($categorie->checkCatpass($this->postInt('cat_id'), $this->postString('cat_pass', '', 'sql'))) {
                        echo json_encode(array('status' => 'ok'));
                     }
                     else {
                        echo json_encode(array('status' => 'fail'));
                     }
                     exit;
                     break;

                  case 'loadExtended':
                     $this->html5_mode = 'iframe';
                     $extended    = Control::getShopExtended();
                     $pos         = $this->postString('pos');

                     if ($pos != '') {
                        $slider      = '';
                        $slider_s    = '';
                        $accordion   = '';
                        $accordion_s = '';
                        $carussell   = '';
                        $carussell_s = '';

                        if ($extended->slider_active && $extended->slider_pos == $pos) {
   //                        $slider      = $extended->slider_html;
                           $slider      = '<iframe style="border:none; overflow:hidden;" width="'.$extended->slider_w.'" height="'.$extended->slider_h.'" src="'.SHOP_URL_IDX.'/ajax/loadExtended?pos='.$pos.'&typ=slider"></iframe>';
//                           $slider_s    = $extended->slider_script;

                        }

                        if ($extended->accordion_active && $extended->accordion_pos == $pos) {
   //                        $accordion   = $extended->accordion_html;
//                           $accordion      = '<iframe><html><head></head><body>'.$extended->accordion_html.$extended->accordion_script.'</body></html></iframe>';
                           $accordion = '<iframe style="border:none; overflow:hidden;" width="'.$extended->accordion_w.'" height="'.$extended->accordion_h.'" src="'.SHOP_URL_IDX.'/ajax/loadExtended?pos='.$pos.'&typ=accordion"></iframe>';
//                           $accordion_s = $extended->accordion_script;
                        }

                        if ($extended->carussell_active && $extended->carussell_pos == $pos) {
//                           $carussell   = $extended->carussell_html;
                           $carussell      = '<iframe style="border:none; overflow:hidden;" width="'.$extended->carussell_w.'" height="'.$extended->carussell_h.'" src="'.SHOP_URL_IDX.'/ajax/loadExtended?pos='.$pos.'&typ=carussell"></iframe>';
//                           $carussell_s = $extended->carussell_script;
                        }

                        echo json_encode(['status' => 'ok', 'slider' => $slider, 'slider_script' => $slider_s, 'accordion' =>$accordion, 'accordion_script' => $accordion_s, 'carussell' => $carussell, 'carussell_script' => $carussell_s]);
                        exit;
                     }

                     $pos = $_GET['pos'];
                     $typ = $_GET['typ'];
                     $html = '';
                     $script = '';

                     if ($typ == 'carussell') {
                        $html  = $extended->carussell_html;
                        $script = $extended->carussell_script;
                     }

                     if ($typ == 'slider') {
                        $html  = $extended->slider_html;
                        $script = $extended->slider_script;
                     }

                     if ($typ == 'accordion') {
                        $html  = $extended->accordion_html;
                        $script = $extended->accordion_script;
                     }

                     echo '<html><head>';
                     echo $script;
                     echo '</head><body>';
                     echo $html;
                     echo '</body>';

                     exit;
                     break;

                  // Responsive Designs / Kategorien und/oder Artikel nachladen
                  case 'loadKategorieArtikel':
                     unset($_SESSION['kategoriefilter']);
                     unset($_SESSION['mixer']);
                     // Damit nicht Startseite genommen wird
                     $this->task   = 'kategorie';
                     $artikel      = array('', '');
                     $counter      = '';
                     $cat_text     = '';
                     $artikel_text = '';
                     $titeltag     = '';
                     $link         = '';

                     $cat_id       = $this->postInt('cat_id');
                     $only_cats    = $this->postCheckbox('only_cats');
                     $mode         = $this->postString('mode');

                     // Bei Kategoriewechsel Seite auf 1
                     // Kategorie ändern
                     if ($only_cats == 'n') {
                        if ($_SESSION['kat_id'] != $cat_id && $mode != 'artikel') {
                           $_SESSION['artikel_seite'] = 1;
                           $_SESSION['kat_id'] = $cat_id;
                        }

                        if ($mode == 'artikel') {
                           $cat_id = $_SESSION['kat_id'];
                        }
                     }

                     $categorie = Control::getCategories();
                     $t         = $categorie->getMixerCheck($cat_id);

                     // Mixer-Kategorie ?
                     $mixer = (isset($t->mixer_check) && $t->mixer_check == 'y' ? true : false);
                     $cats  = $categorie->loadKategorie($cat_id);

                     $_SESSION['artikel_reihen'] = $this->postInt('artikel_reihen');
                     $artikel_pro_reihe          = $this->postInt('artikel_pro_reihe');

                     if ($artikel_pro_reihe == 0) {
                        $artikel_pro_reihe = 12;
                     }

                     $_SESSION['artikel_pro_reihe'] = $artikel_pro_reihe;

                     // Titel-Tag
                     $this->kat_id = $cat_id;
                     $key_obj      = Helper::getKeywords($this->task, $this->selected_lang);
                     $titeltag     = $key_obj->titeltag;

                     // Bei Kategorietext ist strlen > 100
                     if (strlen($cats) < 100) {
                        $cats = '';
                     }

                     // Artikel anzeigen
                     if ($only_cats != 'y') {
                        if (!$this->hide_articles && !$mixer) {



                           $articles  = Control::getArticles();
                           $articles->loadArticles();
                           $articles->render(0, $artikel);

                           if (!defined('CONF_MODULE_SORTIERUNG')) {
                              $artikel_text .= '<div id="filters_container" class="cbp-l-filters-button padding_top"></div>'.CR;
                           }

                           else {
                              include SHOP_PATH.'/classes/modules/sortierung/sortierung.module.php';
                              $artikel_text .= '<div id="filters_container" class="cbp-l-filters-button padding_top" style="min-height:54px; padding-right:165px;"></div>'.CR;
                              $artikel_text .= $mod_sort;
                           }

                           $artikel_text .= '<div id="article_container">'.$artikel[0].'</div>';
                           $counter = $this->hide_articles ? '' : $articles->getCounter();
                        }

                        $cat_text = ($this->cat_text != '' ? $this->cat_text : '');
                        $link     = SHOP_URL_IDX.'/k'.($this->selected_lang != 'deu' ? $this->selected_lang : '').$cat_id.($_SESSION['artikel_seite'] > 1 ? '-'.$_SESSION['artikel_seite'] : '').'/'.str_replace('||', '', Helper::checkFilename($categorie->getPath($cat_id)));

                        Helper::addClickCategorie($cat_id);
                        Helper::checkClick('kategorien', $cat_id, 0);
                     }

                     else {
                        Helper::addClickCategorie($cat_id);
                        Helper::checkClick('kategorien', $cat_id, 0);
                     }

                     if (isset($_SESSION['last_link'])) {
                        if ($link != '') {
                           $_SESSION['last_link'] = $link;
                        }
                     }

                     else {
                        $_SESSION['last_link'] = '';
                     }

                     // Kategorie mit Altersprüfung
                     if (defined('CONF_MODULE_PERSOCHECK') && $this->firma['fsk_show'] == 'y' && $artikel_text != '' && $categorie->getAlterCheck() == 'y' && !$_SESSION['alter_ok']) {
                        $html = '';

                        if ($this->select_has_childs) {
                           include TEMPLATE_PATH . '/alter.tpl.php';
                        }

                        $artikel_text = $html;
                        $cat_text     = '';
                        $counter      = '';
                     }


                     echo json_encode(array('status'         => 'ok',
                                            'cats'           => ($mode != 'artikel' ? $cats : ''),
                                            'breadcrumb'     => Control::getCategories()->getCategoryBreadcrumb($cat_id),
                                            'titeltag'       => ($mode != 'artikel' ? $titeltag : ''),
                                            'cat_text'       => $cat_text,
                                            'articles'       => $artikel_text,
                                            'counter'        => $counter,
                                            'artikel_max'    => $this->artikel_max,
                                            //'artikel_seite'  => $_SESSION['artikel_seite'] + 1,
                                            'artikel_seite'  => $_SESSION['artikel_seite'],
                                            'artikel_reihen' => $_SESSION['artikel_reihen'],
                                            'link'           => $link,
                                            'mixer'          => $mixer,
                                            'x_mode'         => $mode));
                     exit;
                     break;

                  case 'catSelectChanged': {
                     unset($_SESSION['kategoriefilter']);
                     unset($_SESSION['mixer']);
                     $this->task   = 'kategorie';

                     $artikel      = array('', '');
                     $counter      = '';
                     $cat_text     = '';
                     $artikel_text = '';
                     $titeltag     = '';
                     $link         = '';

                     $cat_id             = $this->postInt('cat_id');
                     $this->kat_id       = $cat_id;

                     $_SESSION['artikel_seite'] = 1;
                     $_SESSION['kat_id'] = $cat_id;
                     $_SESSION['artikel_reihen'] = $this->postInt('artikel_reihen');
                     $artikel_pro_reihe          = $this->postInt('artikel_pro_reihe');

                     if ($artikel_pro_reihe == 0) {
                        $artikel_pro_reihe = 12;
                     }

                     $_SESSION['artikel_pro_reihe'] = $artikel_pro_reihe;

                     $categorie          = Control::getCategories();
                     $t                  = $categorie->getMixerCheck($cat_id);

                     // Mixer-Kategorie ?
                     $mixer = (isset($t->mixer_check) && $t->mixer_check == 'y' ? true : false);

                     $cat_html     = $categorie->renderTreeSelect($cat_id);
                     // Titel-Tag
                     $key_obj      = Helper::getKeywords($this->task, $this->selected_lang);
                     $titeltag     = $key_obj->titeltag;

                     if (!$this->hide_articles && !$mixer) {
                        $articles  = Control::getArticles();
                        $articles->loadArticles();
                        $articles->render(0, $artikel);

                        if (!defined('CONF_MODULE_SORTIERUNG')) {
                           $artikel_text = '<div id="filters_container" class="cbp-l-filters-button padding_top"></div>'.CR;
                        }

                        else {
                           include SHOP_PATH.'/classes/modules/sortierung/sortierung.module.php';
                           $artikel_text = '<div id="filters_container" class="cbp-l-filters-button padding_top" style="min-height:54px; padding-right:165px;"></div>'.CR;
                           $artikel_text .= $mod_sort;
                        }

                        $artikel_text .= '<div id="article_container">'.$artikel[0].'</div>';
                        $counter = $this->hide_articles ? '' : $articles->getCounter();
                     }

                     $cat_text = ($this->cat_text != '' ? $this->cat_text : '');
                     // Kategorie mit Altersprüfung
                     if (defined('CONF_MODULE_PERSOCHECK') && $this->firma['fsk_show'] == 'y' && $artikel_text != '' && $categorie->getAlterCheck() == 'y' && !$_SESSION['alter_ok']) {
                        $html = '';
                        include TEMPLATE_PATH . '/alter.tpl.php';
                        $artikel_text = $html;
                        // $cat_text     = '';
                        $counter      = '';
                     }

                     $link = SHOP_URL_IDX.'/k'.($this->selected_lang != 'deu' ? $this->selected_lang : '').$cat_id.($_SESSION['artikel_seite'] > 1 ? '-'.$_SESSION['artikel_seite'] : '').'/'.str_replace('||', '', Helper::checkFilename($categorie->getPath($cat_id)));

                     exit(json_encode(array('status'         => 'ok',
                                            'cats'           => $cat_html,
                                            'titeltag'       => $titeltag,
                                            'cat_text'       => $cat_text,
                                            'articles'       => $artikel_text,
                                            'counter'        => $counter,
                                            'artikel_max'    => $this->artikel_max,
                                            //'artikel_seite'  => $_SESSION['artikel_seite'] + 1,
                                            'artikel_seite'  => $_SESSION['artikel_seite'],
                                            'artikel_reihen' => $_SESSION['artikel_reihen'],
                                            'link'           => $link,
                                            'mixer'          => $mixer,
                                            'filter'         => $this->db_extern->querySingleValue("SELECT filter_active FROM #__categories WHERE id = $cat_id")
                                       )
                         )
                     );
                  }

                  case 'art_haendler': {
                     unset($_SESSION['suche']);
                     unset($_SESSION['kategoriefilter']);
                     $_SESSION['haendler_id'] = (int)$funcs[1];
                     $this->task = 'kategorie';
                     $this->kat_id = 0;
                     $this->art_id = 0;
                     $_SESSION['task'] = 'kategorie';
                     $_SESSION['kat_id'] = 0;
                     $_SESSION['art_id'] = 0;
                     $_SESSION['artikel_seite'] = 1;
                     break;
                  }

                  // ???
                  case 'DELajaxpreis':
                     $zahlart       = $this->postInt('zahlart');
                     $zahlart_preis = 0.00;

                     if ($zahlart == 2) {
                        $zahlart_preis = $this->firma['paypal_preis'];
                     }

                     if ($zahlart == 4) {
                        $zahlart_preis = $this->firma['nachnahme_preis'];
                     }

                     if ($zahlart == 7) {
                        $zahlart_preis = $this->firma['sofort_preis'];
                     }

                     if ($zahlart == 8) {
                        $zahlart_preis = $this->firma['vrpay_preis'];
                     }

                     if ($zahlart == 10) {
                        $zahlart_preis = $this->firma['paypalplus_preis'];
                     }

                     if ($zahlart == 11) {
                        $zahlart_preis = $this->firma['amazon_preis'];
                     }

                     if ($zahlart == 12) {
                        $zahlart_preis = $this->firma['twint_preis'];
                     }

                     if ($zahlart == 14) {
                        $zahlart_preis = $this->firma['klarna_preis'];
                     }

                     if ($zahlart == 15) {
                        $zahlart_preis = $this->firma['paydirekt_preis'];
                     }

                     if ($zahlart == 16) {
                        // Kein Aufschlag
                     }

                     if ($zahlart == 17) {
                        $zahlart_preis = $this->firma['postfinance_preis'];
                     }

                     if ($zahlart == 18) {
                        $zahlart_preis = $this->firma['paypalv2_preis'];
                     }

                     $this->setSession('zahlungsart', $zahlart);
                     $this->task = 'warenkorb';

                     break;

                  case 'kategorie':
                  case ':':
                     unset($_SESSION['haendler_id']);
                     unset($_SESSION['suche']);
                     unset($_SESSION['kategoriefilter']);

                     $this->task   = 'kategorie';
                     $this->kat_id = (int)$funcs[1];
                     $this->art_id = 0;

                     if ($this->kat_id != $_SESSION['kat_id']) {
                        $_SESSION['artikel_seite'] = 1;
                     }

                     $_SESSION['task']   = 'kategorie';
                     $_SESSION['kat_id'] = $this->kat_id;
                     $_SESSION['art_id'] = 0;
                     // $_SESSION['artikel_seite'] = 1;
                     // Kategorie-Clicks zählen
                     Helper::addClickCategorie($this->kat_id);
                     break;

                  case 'kategorieseite':
                     $this->task = 'kategorie';
                     $this->kat_id = (int)$funcs[1];
                     $this->art_id = 0;
                     $_SESSION['task'] = 'kategorie';
                     $_SESSION['kat_id'] = $this->kat_id;
                     $_SESSION['art_id'] = 0;
                     break;

                  case 'artikel':
                     unset($_SESSION['suche']);
                     unset($_SESSION['kategoriefilter']);

                     $this->art_id       = (int)$funcs[1];
                     $this->task         = 'artikel';
                     $_SESSION['task']   = 'artikel';
                     $_SESSION['art_id'] = $this->art_id;

                     Helper::addClickArticle($this->art_id);
                     break;

                  case 'suchen':
                     unset($_SESSION['haendler_id']);
                     $this->art_id = 0;
                     $this->task = 'kategorie';
                     $this->article_search = true;
                     $_SESSION['task'] = 'kategorie';
                     $_SESSION['suche'] = 'suchen';
                     unset($_SESSION['kategoriefilter']);
                     break;

                  case 'foto_list':
                     $this->art_id = 0;
                     $this->task = 'kategorie';
                     $_SESSION['task'] = 'kategorie';
                     $_SESSION['suche'] = 'suchen';
                     unset($_SESSION['kategoriefilter']);
                     break;

                  // andere Sprache gewählt
                  case 'lang':
                     $this->task = Helper::checkUeberuns($_SESSION['task'], $this->selected_lang, $funcs[1]);

                     if ($this->task == '') {
                        $this->task = $_SESSION['task'];
                     }

                     $this->selected_lang = $this->_checkLang($funcs[1]);
                     $_SESSION['lang'] = $this->selected_lang;

                     if ($this->user_id  > 0) {
                        $user = Control::getUser();
                        $user->changeLang($this->user_id, $funcs[1]);
                     }

                     $anhang = $this->getAnhang(true);
                     header('Location: '.$this->getLink($this->task, $anhang[0], $anhang[1]));
                     exit;
                     break;

                  // andere Währung gewählt
                  case 'currency':
                     // Shop-Währung oder nicht möglich
                     if ((int)$funcs[1] < 2 || (int)$funcs[1] > 4) {
                        unset($_SESSION['user_waehrung_id']);
                     }

                     // Fremdwährung
                     else {
                        $_SESSION['user_waehrung_id'] = (int)$funcs[1];
                     }

                     $anhang = $this->getAnhang(true);
                     header('Location: '.$this->getLink($this->task, $anhang[0], $anhang[1]));
                     exit;
                     break;

                  // Anzahl Artikel / Seite - nicht verwendet?
                  case 'anzahl':
                     if ($this->isAjax) {
                        $reihen = $this->postInt('anzahl');
                        $pro_reihe = $this->postInt('artikel_pro_reihe');
                        $_SESSION['artikel_reihen'] = $reihen;
                        $_SESSION['artikel_pro_reihe'] = $pro_reihe;
                        echo json_encode(array('status' =>'ok', 'reihen' => $reihen));
                        exit;
                     }

                     else {
                        $this->art_anzahl = (int)$funcs[1];
                        $_SESSION['art_anzahl'] = $this->art_anzahl;
                        $this->task = $_SESSION['task'];
                        $anhang = $this->getAnhang();
                        header('Location: '.$this->getLink($this->task, $anhang[0], $anhang[1]));
                        exit;
                     }

                     break;

                  case 'setArtikel':
                     $_SESSION['artikel_reihen']    = $this->postInt('artikel_reihen');
                     $_SESSION['artikel_pro_reihe'] = $this->postInt('artikel_pro_reihe');

                     $startseite = $this->postString('startseite');

                     if ($startseite == 'true') {
                        $artikel = Control::getArticles();
                        $artikel->loadArticles();

                        exit(json_encode(['status' => 'ok', 'html' => $artikel->getCounter()]));
                     }

                     else {
                        exit(json_encode(['status' => 'ok', 'html' => '']));
                     }
                     break;

                  // Seite Nr. anzeigen
                  case 'seite':
                     if ($this->isAjax) {
                        $seite             = $this->postInt('seite');   // 0 -> Anfang; 1 -> -1; 2 -> +1; 3 -> Ende
                        $artikel_reihen    = ($this->postInt('artikel_reihen') > 0 ? $this->postInt('artikel_reihen') : 1);
                        $artikel_pro_reihe = ($this->postInt('artikel_pro_reihe') > 0 ? $this->postInt('artikel_pro_reihe') : 12);
                        $artikel_seite     = $_SESSION['artikel_seite'];
                        $artikel_anzahl    = $this->postInt('artikel_anzahl');
                        $artikel_max       = $this->postInt('artikel_max');

                        // Anfang
                        if ($seite == 0) {
                           $artikel_seite = 1;
                        }

                        // Zurück
                        if ($seite == 1) {
                           $artikel_seite--;
                        }

                        // Vor
                        if ($seite == 2) {

                           $artikel_seite++;
                        }

                        // Ende
                        if ($seite == 3) {
                           $artikel_seite = 999;
                        }

                        $max = ceil($artikel_max / $artikel_reihen / $artikel_pro_reihe);

                        if ($artikel_seite > $max) {
                           $artikel_seite = $max;
                        }

                        $_SESSION['artikel_seite'] = $artikel_seite;
                        $_SESSION['artikel_pro_reihe'] = $artikel_pro_reihe;
                        echo json_encode(array('status' =>'ok', 'seite' => $artikel_seite));
                        exit;
                     }

                     // Kein Ajax / Beauty
                     else {
                        $_SESSION['artikel_seite'] = (int)$funcs[1];
                        $this->task = $_SESSION['task'];
                        $anhang = $this->getAnhang();
                        header('Location: '.$this->getLink($this->task, $anhang[0], $anhang[1]));
                        exit;
                     }

                     break;

                  case 'modSort':
                        $_SESSION['module_sortierung'] = $this->postInt('sortierung');
                        $_SESSION['artikel_seite'] = 1;

                        echo json_encode(['status' => 'ok', 'sort' => $_SESSION['module_sortierung']]);
                        exit;
                        break;

                  case 'download':
                     $this->dl_link = $funcs[1];
                     $this->task = 'download';
                     return;
                     break;

                  case 'validate':
                  case 'validateadmin':
                     $this->isAjax = true;
                     $this->validate = $funcs[1];
                     $user = Control::getUser();

                     $test = $user->validate($this->validate);

                     // Validierung nach Registrierung
                     if ($test == 'anmeldung') {
                        $this->validate1 = 'anmeldung';
                        $this->task = $funcs[0];
                     }

                     // Account Manuell
                     elseif ($test == 'login') {
                        $this->validate1 = 'login';
                        $this->task = $funcs[0];
                     }

                     // Passwortabfrage im Formular anzeigen
                     elseif ($test == 'password') {
                        $this->task = $funcs[0];
                        $this->validate1 = 'password';
                     }

                     // Passwort erfolgreich geändert
                     elseif ($test == 'pwchanged') {
                        $this->task = $funcs[0];
                        $this->validate1 = 'changed';
                     }

                     // Passwort falsch oder zu kurz
                     elseif ($test == 'password_fail') {
                        $this->task = $funcs[0];
                        $this->validate1 = 'pw_fail';
                     }

                     else {
                        $this->task = $funcs[0];
                        $this->validate1 = 'fail';
                     }

                     break;

                  // Validierung Newsletter - Wird direkt aufgerufen
                  case 'validatenl':
                     $this->isAjax = true;
                     $validate = $funcs[1];
                     $user = Control::getUser();
                     $test = $user->validateNL($validate);

                     if ($test) {
                        $this->task = 'validate';
                        $this->validate1 = 'newsletter_ok';
                     }

                     else {
                        $this->task = 'validate';
                        $this->validate1 = 'newsletter_fail';
                     }
                     break;

                  // Artikel in WK legen
                  case 'inwarenkorb':
                     $mixer_vals = $this->postString('article_mixer_vals');

                     if ($this->postCheckbox('masse_check') != 'y') {
                        $menge = $this->postInt('menge');
                        if ($menge == 0) {
                           $menge = 1;
                        }
                     }

                     else {
                        $menge = $this->postFloat('masse_menge');
                        $menge = Helper::checkMenge($this->art_id, $menge);
                     }

                     $this->art_menge = $menge;
                     $this->art_id = (int)$funcs[1];

                     // in Merkliste
                     if (isset($_POST['merkliste_x']) || $this->postCheckbox('merkliste') == 'y') {
                        $_SESSION['task'] = 'merkliste';
                        $this->task = $_SESSION['task'];

                        $ml = Control::getML();
                        $ml->addArticle();

                        if ($this->isAjax) {
                           echo json_encode(array('status' => 'ok', 'titel' => str_replace(' ', ' ', $this->text->get('article', 'merkliste')), 'link' => SHOP_URL_IDX.'/merkliste'));
                        }

                        else {
                           header('Location: '.SHOP_URL_IDX.'/merkliste');
                        }
                     }

                     // in Warenkorb
                     else {
                        $_SESSION['task'] = 'warenkorb';
                        $this->task = $_SESSION['task'];

                        $wk = Control::getWk();
                        $wk->addArticle();

                        if ($this->isAjax) {
                           echo json_encode(array('status' => 'ok', 'titel' => str_replace(' ', ' ', $this->text->get('article', 'warenkorb')), 'link' => SHOP_URL_IDX.'/warenkorb', 'wk_count' => count($_SESSION['warenkorb'])));
                        }

                        else {
                           header('Location: '.SHOP_URL_IDX.'/warenkorb');
                        }
                     }

                     exit;
                     break;
/* Ab Version 10 deaktiviert
                  // Eintrag aus WK löschen
                  case 'wk_del':
                     unset($_SESSION['zahlungsplan']);
                     unset($_SESSION['modellrechnung']);

                     $this->task = 'warenkorb';
                     $_SESSION['task'] = 'warenkorb';
                     $wk = Control::getWk();
                     $wk->delWk((int)$funcs[1]);
                     header('Location: '.SHOP_URL_IDX.'/warenkorb');
                     exit;
                     break;
*/

                  // Menge im WK ändern
                  case 'wk_akt':
                     unset($_SESSION['zahlungsplan_idx']);
                     unset($_SESSION['modellrechnung']);

                     $_SESSION['agb_check']      = $this->postCheckbox(('agb_check'));
                     $_SESSION['widerruf_check'] = $this->postCheckbox(('widerruf_check'));
                     $_SESSION['widerruf_dl']    = $this->postCheckbox(('widerruf_dl'));
                     $_SESSION['widerruf_down']  = $this->postCheckbox(('widerruf_down'));
                     $_SESSION['ds_gvo_check']   = $this->postCheckbox(('ds_gvo_check'));
                     $_SESSION['abholung_checkbox']   = $this->postCheckbox(('abholung_checkbox'));
                     $_SESSION['newsletter']     = $this->postCheckbox('newsletter');
                     $_SESSION['versand_land']   = $this->postInt('versand_land');
                     $_SESSION['zahlart']        = $this->postInt('zahlart');

                     $anzahl = $this->postFloat('anzahl');

                     if ($this->isAjax) {
                        $wk_id                = $this->postInt('wk_id');
                        $this->wk_changed     = true;
                        $this->wk_changed_id  = $wk_id;
                     }

                     else {
                        $wk_id = ((int)$funcs[1]);
                     }

                     $this->task       = 'warenkorb';
                     $_SESSION['task'] = 'warenkorb';
                     $wk               = Control::getWk();

                     if ($this->postCheckbox('masse_check') == 'y' && $anzahl > 0) {
                        // Mindestmenge
                        $anzahl = Helper::checkMenge($this->warenkorb[$wk_id]['art_id'], $anzahl);
                     }

                     // Für Vergleich merken
                     $this->ajax_menge = $anzahl;

                     // Artikelmenge für Aktualierung via AJAX / index.php
                     if ($anzahl > 0) {
                        // Lagermenge überprüfen (nicht bei Kategorie-Mixer)
                        if ($this->warenkorb[$wk_id]['cat_id'] == 0) {
                           $anzahl = $wk->checkLagermenge($this->warenkorb[$wk_id]['art_id'], $anzahl);
                        }


                        // Farbe bei Änderung
                        if ((float)$anzahl < (float)$this->ajax_menge) {
                           $this->ajax_menge_ok = 0;
                        }

                        $this->ajax_menge = $anzahl;

                        // Zahlenformat Artikel-Menge anpassen
                        $artikel = Control::getArticles();
                        $wk_art  = $artikel->getArticleById($this->warenkorb[$wk_id]['art_id']);

                        // Artikel- oder Koategorie-Mixer
                        if ($this->warenkorb[$wk_id]['mixer'] != '') {
                           if ($this->warenkorb[$wk_id]['cat_id'] > 0) {
                              $mixer = Control::getModuleMixerKategorie();
                              $wk_art = $mixer->getArticleById($this->warenkorb[$wk_id]['cat_id'], $this->selected_lang, $this->selected_lang, 0, 0, false, $this->warenkorb[$wk_id]['mixer']);
                           }

                           else {
                              $mixer  = Control::getModuleMixerArtikel();
                              $wk_art = $mixer->getArticleById($this->art_id, $lang, $lang_kunde, 0, 0, false, (isset($_SESSION['mixer2']) ? $_SESSION['mixer2'] : ''));
                           }

                        }

                        $komma   = $wk_art->masse_komma;

                        if (defined('CONF_MODULE_MASSEINGABE') && $this->warenkorb[$wk_id]['rechner_check'] != 'y') {
                           $this->ajax_menge = number_format($this->ajax_menge, $komma, ',', '');
                        }
                        else {
                           $this->ajax_menge = (int)$this->ajax_menge;
                        }

                        $this->warenkorb[$wk_id]['art_menge'] = $anzahl;
                        $this->warenkorb[$wk_id]['wk_change'] = true;
                        $_SESSION['warenkorb']                = $this->warenkorb;

                        if ($this->user_id > 0) {
                           $wk->saveWk();
                        }
                     }

                     // Bei Menge 0 Artikel aus WK löschen
                     else {
                        $wk->delWk($wk_id);
                        $this->wk_changed = false;
                     }

                     return;

                  // Menü / Warenkorb
                  case 'warenkorb':
                     // Damit Variable vorhanden
                      $_SESSION['last_versandland'] = $_SESSION['wk_land'];


                      if(!empty($_POST)){
                          $_SESSION['agb_check']      = $this->postCheckbox(('agb_check'));
                          $_SESSION['widerruf_check'] = $this->postCheckbox(('widerruf_check'));
                          $_SESSION['widerruf_dl']    = $this->postCheckbox(('widerruf_dl'));
                          $_SESSION['widerruf_down']  = $this->postCheckbox(('widerruf_down'));
                          $_SESSION['ds_gvo_check']   = $this->postCheckbox(('ds_gvo_check'));
                          $_SESSION['abholung_checkbox']   = $this->postCheckbox(('abholung_checkbox'));
                          $_SESSION['newsletter']     = $this->postCheckbox('newsletter');
                          $_SESSION['versand_land']   = $this->postInt('versand_land');
                          $_SESSION['zahlart']        = $this->postInt('zahlart');
                      }

                     $this->task = 'warenkorb';
                     $_SESSION['task'] = 'warenkorb';
                     $_SESSION['back'] = '';

                     if ($this->postRadio('widerruf_dl') == 'y') {
                        $_SESSION['widerruf_dl'] = 'y';
                     }

                     if ($this->postRadio('widerruf_down') == 'y') {
                        $_SESSION['widerruf_down'] = 'y';
                     }

                     // EasyCredit
                     if ($this->postInt('zahlart') == 13) {
                        unset($_SESSION['zahlungsplan_idx']);
                        unset($_SESSION['modellrechnung']);
                     }

                     break;

                  case 'merkliste':
                     $this->task = 'merkliste';
                     $_SESSION['task'] = 'merkliste';
                     $_SESSION['back'] = '';
                     break;

                  case 'checkPrice':
                     $article = Control::getArticles();
                     $article->checkPrice();
                     break;

                  // Eintrag aus WK löschen
                  case 'ml_del':
                     $_SESSION['task'] = 'merkliste';
                     $this->task = $_SESSION['task'];
                     $ml = Control::getMl();
                     $ml->delML((int)$funcs[1]);
                     return;
                     break;

                  // Menge im WK ändern
                  case 'ml_akt':
                     $_SESSION['task'] = 'merkliste';
                     $this->task = $_SESSION['task'];
                     $ml = Control::getML();
                     $ml_id = ((int)$funcs[1]);
                     if ($this->postCheckbox('masse_check') != 'y') {
                        $anzahl = $this->postInt('anzahl');
                     }
                     else {
                        $anzahl = $this->postFloat('anzahl');
                     }

                     if ($anzahl > 0) {
                        $this->my_merkliste[$ml_id]['art_menge'] = $anzahl;
                        $this->my_merkliste[$ml_id]['wk_change'] = true;

                        if ($this->user_id > 0) {
                           $ml->saveML();
                        }
                        else {
                           $_SESSION['my_merkliste'] = $this->my_merkliste;
                        }
                     }

                     // Bei Menge 0 Artikel aus WK löschen
                     else {
                        $ml->delML((int)$funcs[1]);
                     }
                     return;
                     break;

                  case 'ml_inwk':
                     $ml = Control::getML();

                     if ($this->isAjax) {
                        $ml_id = $this->postInt('wk_id');
                        $test = $ml->mlWK($ml_id);

                        if($test) {
                           echo json_encode(array('status' => 'ok', 'msg' => $_SESSION['admin_msg'], 'wk_count' => count($_SESSION['warenkorb'])));
                           unset($_SESSION['admin_msg']);
                           exit;
                        }

                        else {
                           exit;
                        }
                     }

                     $ml_id = ((int)$funcs[1]);
                     $test = $ml->mlWK($ml_id);

                     if ($test) {
                        header('Location: '.SHOP_URL_IDX.'/merkliste');
                     }
                     else {
                        header('Location: '.SHOP_URL_IDX.'/merkliste');
                     }
                     exit;
                     break;

                  case 'wk_popup':
                     $breite        = $this->postInt('breite');
                     $articles      = Control::getArticles();
                     $zubehoer_text = '';
                     $anzahl        = 0;
                     $html          = '';

                     if ($breite > 700 && defined('CONF_MODULE_ZUBEHOER')) {
                        $anzahl = $articles->loadArticlesZubehoer($this->postInt('parent_id'), 2);

                        if ($anzahl > 0) {
                           $zubehoer = [];
                           $articles->render(0, $zubehoer, true, true, true);
                        }

                        $zubehoer_text = $articles->getZubehoerTitle($this->postInt('parent_id'));
                     }

                     include_once TEMPLATE_PATH.'/popup_weiter_einkaufen.tpl.php';
                     echo json_encode(['status'=> 'ok', 'html' => $html]);

                     exit;
                     break;

                  case 'checkgutschein':
                     $this->task = 'checkgutschein';
                     $_SESSION['task'] = 'warenkorb';
                     $berechnung = CONTROL::getBerechnungen();
                     echo json_encode($berechnung->checkGutschein());
                     exit;
                     break;

                  case 'delGutschein':
                     $_SESSION['task'] = 'warenkorb';
                     unset($_SESSION['Print_Gutschein']);
                     unset($_SESSION['gutschein_print']);
                     unset($_SESSION['gutschein_code']);
                     unset($_SESSION['gutschein_wert']);
                     unset($_SESSION['gutschein_mode']);
                     unset($_SESSION['gutschein_datum']);

                     exit(json_encode(['status' => 'ok']));
                     break;

                  case 'coupon':
                     $email = $this->postString('email', '', 'sql');
                     $user  = Control::getUser();
                     $test  = $user->coupon($email, 'coupon', 'gutschein5');

                     if ($test == 'nomail') {
                        echo json_encode(array('status' => 'Email ungültig'));
                        exit;
                     }

                     if ($test == 'mailvorhanden') {
                        echo json_encode(array('status' => 'Email wird bereits verwendet'));
                        exit;
                     }

                     $user->getMoreParams();
                     $this->loginerror = false;
                     $this->logged_in = true;
                     $_SESSION['logged_in'] = $this->logged_in;
                     $_SESSION['user_id'] = $this->user_id;
                     $_SESSION['user_name'] = $user->user['nachname'];
                     $_SESSION['email'] = $user->user['email'];

                     echo json_encode(array('status' => 'ok', 'msg' => $this->text->get('coupon', 'mail')));
                     exit;
                     break;

                  // Schnellkauf merken für Lieferung (kein Login)
                  case 'schnellkauf':
                     $_SESSION['sofortkauf'] = 'y';
                     header('Location: '.SHOP_URL_IDX.'/lieferung/nc');
                     exit;
                     break;

                  case 'nachrichtSend':
                     $_SESSION['user_msg'] = $this->postString('nachricht');

                     echo json_encode(['status' => 'ok']);
                     exit;
                     break;

                  // Aus Warenkorb -> Formular Lieferung anzeigen / Schnellkauf /
                  // Wenn nicht einglogged: -> Login (außer Schnellkauf)
                  case 'lieferadresse':
                     $this->task = 'lieferung';
                     return;
                     break;

                  case 'lieferung':
                     $this->task = 'lieferung';

                     $_SESSION['agb_check']      = $this->postCheckbox(('agb_check'));
                     $_SESSION['widerruf_check'] = $this->postCheckbox(('widerruf_check'));
                     $_SESSION['widerruf_dl']    = $this->postCheckbox(('widerruf_dl'));
                     $_SESSION['widerruf_down']  = $this->postCheckbox(('widerruf_down'));
                     $_SESSION['ds_gvo_check']   = $this->postCheckbox(('ds_gvo_check'));
                     $_SESSION['abholung_checkbox']   = $this->postCheckbox(('abholung_checkbox'));
                     $_SESSION['newsletter']     = $this->postCheckbox('newsletter');
                     $_SESSION['versand_land']   = $this->postInt('versand_land');
                     $_SESSION['zahlart']        = $this->postInt('zahlart');

                     // Bestellung ohne Konto / (WK) Button Adresse aktualisieren - Formular anzeigen - Keine Prüfung AGB/Widerruf
                     if (isset($funcs[1]) && $funcs[1] == 'nc') { // nc - no check
                        return;
                     }

                     $user = Control::getUser();

                     // Aufruf von WK "zahlungspflichtig bestellen"
                     $_SESSION['wk_check'] = $this->postCheckbox('wk_check');

                     if (!isset($_SESSION['sofortkauf'])) {
                        $_SESSION['sofortkauf'] = '';
                     }

                     // $haendler_id             = $this->postInt('haendler');
                     // $_SESSION['haendler_id'] = $haendler_id;
                     $_SESSION['haendler_id'] = 0;

                     $zahlungsart = -1;

                     if (isset($_SESSION['zahlungsart'])) {
                        $zahlungsart = $_SESSION['zahlungsart'];
                     }

                     // Wenn von WK Lieferung-Formular, nicht prüfen, nur anzeigen, wenn nicht reg. Kunde
                     if ($this->postCheckbox('check_lieferung') != 'y') {
                        $_SESSION['user_msg'] = $this->postString('nachricht');

                        // Newsletter
                        if ($this->firma['gutschein_aktiv'] ==  'y') {
                           $_SESSION['newsletter'] = $this->postCheckbox('newsletter');
                           $user->newsletterChanged($this->user_id, $_SESSION['newsletter']);
                        }

                        if ($this->user_id > 0 || $_SESSION['user']['nachname'] != '') {
                           // Widerruf Dienstleisung (Radio)
                           if ($this->postRadio('widerruf_dl') == 'y') {
                              $_SESSION['widerruf_dl'] = 'y';
                           }

                           // Widerruf Downloadartikel (Radio)
                           if ($this->postRadio('widerruf_down') == 'y') {
                              $_SESSION['widerruf_down'] = 'y';
                           }

                           $_SESSION['zahlart_error'] = false;

                           // Keine Zahlungsart gewählt
                           if ($zahlungsart < 1) {
                              $_SESSION['zahlart_error'] = true;
                           }



                           $_SESSION['ds_gvo_check'] = $this->postCheckbox('ds_gvo_check');
                           $_SESSION['abholung_checkbox']   = $this->postCheckbox(('abholung_checkbox'));

                           // Nur wenn CONF_HAEKCHEN aktiv
                           if (defined('CONF_HAEKCHEN')) {
                              $_SESSION['agb_check']      = $this->postCheckbox('agb_check');
                              $_SESSION['widerruf_check'] = $this->postCheckbox('widerruf_check');

                              // Eingaben fehlerhaft? - Zurück zum warenkorb
                              if ($_SESSION['agb_check'] == 'n' || $_SESSION['widerruf_check'] == 'n') {                                 
                                 exit(json_encode(['status' => 'redirect', 'redirect' => SHOP_URL_IDX.'/warenkorb', 'html' => '']));
                              }
                           }


                           // Eingaben fehlerhaft? - Zurück zum warenkorb
                           if ($_SESSION['zahlart_error']) {
                              exit(json_encode(['status' => 'redirect', 'redirect' => SHOP_URL_IDX.'/warenkorb', 'html' => '']));
                           }

                           $_SESSION['back'] = '';
                        }
                     }

                     // Formular prüfen
                     if ($this->postCheckbox('check_lieferung') == 'y') {
                        $user->user['newsletter']       = $_SESSION['newsletter'];
                        $_SESSION['user']['newsletter'] = $_SESSION['newsletter'];

                        // Userdaten auf Änderungen prüfen / Geändert -> true
                        $test1 = $user->checkAdresse();

                        // Auf Gültigkeit prüfen / Wenn OK -> true
                        $test2 = $user->checkBestellung();  // Userdaten gültig: true;

                        // Userdaten geändert und gültig / kein Soforkauf -> Speichern
                        if ($test1 && $test2 && $_SESSION['sofortkauf'] != 'y') {
                           $user->write('update');
                        }

                        if (!$test2) {
                           return;
                        }
                     }

                     // Login wenn nicht angemeldet oder Sofortkauf
                     if ($this->user_id < 1 && ($_SESSION['sofortkauf'] != 'y' && !isset($_POST['ppv2_button'])) ) {
                        $_SESSION['back'] = 'warenkorb';
                        exit(json_encode(['status' => 'redirect', 'redirect' => SHOP_URL_IDX.'/login', 'html' => '']));
                     }

                     // Gast
                     if ($this->postCheckbox('check_lieferung') != 'y' && $_SESSION['sofortkauf'] == 'y' && $user->user['email'] == '' && !isset($_POST['ppv2_button'])) {
                        exit(json_encode(['status' => 'redirect', 'redirect' => SHOP_URL_IDX.'/lieferadresse', 'html' => '']));
                     }

                     // Alterskontrolle
                     // 18: 6088465777D981116435112666
                     //  8: 6088465777D080611035112660
                     if (defined('CONF_MODULE_PERSOCHECK')) {
                        if ($this->firma['fsk_show'] != 'y' && !$_SESSION['alter_ok'] && $_SESSION['fsk_artikel']) {
                           // Perso-Nr ungültig -> Warenkorb
                           if (!$_SESSION['alter_check']) {
                              //return;
                              exit(json_encode(['status' => 'redirect', 'redirect' => SHOP_URL_IDX.'/lieferadresse', 'html' => '']));
                           }

                           // Perso-Nr gültig -> Eiter
                           $heute  = new \DateTime(date('Y-m-d'));
                           $geburt = new \DateTime($_SESSION['user']['gebdatum']);
                           $alter  = (int)$geburt->diff($heute)->format('%y');
                           $_SESSION['alter_ok_date'] = $user->user['gebdatum'];

                           if ($alter < (int)$this->firma['fsk']) {
                              $_SESSION['alter_ok'] = false;
                              $_SESSION['alter_failed'] = true;
                           }
                           else {
                              $_SESSION['alter_ok'] = true;
                              unset($_SESSION['alter_failed']);
                           }
                        }
                     }

                     // Test, ob Lieferland übereinstimmt. 1 (Abholung) ist ausgenommen
                     if ((int)$_SESSION['wk_land'] != (int)$_SESSION['lieferung_land'] /* && $_SESSION['wk_land'] != 1*/ ) {
                        $_SESSION['staat_error'] = true;
                        $_SESSION['wk_land'] = $_SESSION['lieferung_land'];

                        exit(json_encode(['status' => 'redirect', 'redirect' => SHOP_URL_IDX.'/warenkorb']));
                     }

                     // Bestellung ausführen
                     // Warenkorb mit Adresse angezeigt -> weiter zu bezahlung
                     if ($_SESSION['wk_check'] == 'y' &&
                        $_SESSION['user']['nachname'] != '' &&
                        $_SESSION['user']['lf_nachname'] != '' &&
                        $_SESSION['user']['lf_nachname'] != '' &&
                        (!defined('CONF_MODULE_BESTELLZUSAMMENFASSUNG') || defined('CONF_MODULE_BESTELLZUSAMMENFASSUNG') &&
                        isset($_SESSION['bestzus_adresse']) && $_SESSION['bestzus_adresse'] == 'ok')) {

                        // Bei Klarna ->checkout (Antwort: Snippet für Klarna-Popup) -> redirect
                        if ($zahlungsart == 14) {
                           $klarna  = Control::getModuleKlarna();
                           $snippet = $klarna->checkout();

                           // Klarna-Popup anzeigen
                           // Daten zu Klarna fehlerhaft, z.B kein Name
                           if ($snippet['status'] == 'error') {
                              echo json_encode(['status' => 'error', 'html' => $snippet['html'] ]);
                              exit;
                           }

                           // Daten zu Klarna OK
                           else if ($snippet['status'] == 'not_loggedin') {
                              exit(json_encode(['status' => $snippet['status'], 'html' => $snippet['html'] ]));
                           }

                           $_SESSION['klarna_order_id'] = $snippet['klarna_order_id'];

                           // Nach erfolgreicher Anmeldung Redirect durch Klarna -> klarna_confirm/<id>
                           exit(json_encode(['status' => 'popup', 'html' => $snippet['html']]));
                        }

                        // Bestellung durchführen
                        exit(json_encode(['status' => 'redirect', 'redirect' => SHOP_URL_IDX.'/bezahlung']));
                     }
                     //paypal v2 with button in Warenkorb
                     if ($zahlungsart == 18 && isset($_POST['ppv2_button'])) {
                        $_SESSION['bestellnummer'] = $this->db->getBestellnummer().'-'.'ga';
                        $_SESSION['msg_bestellnummer'] = $_SESSION['bestellnummer'];
                        $ppv2 = Control::getPaypalv2();
                        $order_string = $ppv2->createOrderFromWk();

                        exit(json_encode(['status' => 'ok', 'html' => $order_string]));
                     }
                     // Test, ob Lieferland übereinstimmt
                     if ((int)$_SESSION['wk_land'] != (int)$_SESSION['lieferung_land']) {
                        $_SESSION['staat_error'] = true;
                        $_SESSION['wk_land'] = $_SESSION['lieferung_land'];
                     }

                     if ($this->postCheckbox('check_lieferung') == 'y') {
                        exit(json_encode(['status' => 'redirect', 'redirect' => SHOP_URL_IDX.'/warenkorb']));
//                        header('Location: '.SHOP_URL_IDX.'/warenkorb');
//                        exit;
                     }

                     break;

                  // Nach Lieferung -> Bestellnummer erstellen und Formular abhängig von Zahlart anzeigen
                  case 'bezahlung':
                     $_SESSION['task'] = 'bezahlung';
                     $this->task       = 'bezahlung';
                     $this->wk         = Control::getWk();
                     $user_name        = $_SESSION['user_name'];
                     $haendler_id      = (isset($_SESSION['haendler_id']) ? $_SESSION['haendler_id'] : 0);

                     // gast mit ga abkürzen
                     if (strtolower($user_name) == 'gast') {
                        $user_name = 'ga';
                     }

                     // Verhindert, dass bei Aktualisieren Bestellnummer geändert wird
                     if (!isset($_SESSION['bestellnummer']) || !$_SESSION['bestellnummer']) {
                        $user_name = Helper::name2ascii($user_name);
                        $_SESSION['bestellnummer'] = $this->db->getBestellnummer().'-'.$user_name;
                     }

                     $zahlungsart = $_SESSION['zahlungsart'];

                     // Bestellung direkt durchführen, ohne Zwischenformular
                     if ($zahlungsart ==  1 || // Vorkasse
                         $zahlungsart ==  2 || // Paypal
                         $zahlungsart ==  3 || // Lastschrift
                         $zahlungsart ==  4 || // Nachnahme
                         $zahlungsart ==  5 || // Rechnung
                         $zahlungsart ==  6 || // Bar
                         $zahlungsart ==  7 || // Sofort
                         $zahlungsart ==  8 || // VrPay
                         $zahlungsart == 13 || // Easycredit
                         $zahlungsart == 14 || // Klarna
                         $zahlungsart == 15 || // PayDirekt
                         $zahlungsart == 16 || // WIR
                         $zahlungsart == 19)   // Mollie
                     {
                        $_SESSION['TRUSTEDSHOPS']['order_id']    = $_SESSION['bestellnummer'];
                        $_SESSION['TRUSTEDSHOPS']['email_kunde'] = $_SESSION['email'];
                        $_SESSION['TRUSTEDSHOPS']['netto']       = $_SESSION['wk_netto'];
                        $_SESSION['TRUSTEDSHOPS']['brutto']      = $_SESSION['wk_netto'] + $_SESSION['wk_steuer1'] + $_SESSION['wk_steuer2'] + $_SESSION['wk_steuer3'] + $_SESSION['versand_ust'] + $_SESSION['zahlart_ust'];
                        $_SESSION['TRUSTEDSHOPS']['waehrung']    = Helper::waehrungText((isset($_SESSION['user_waehrung_id']) ? $_SESSION['user_waehrung_id'] : $this->firma['waehrung1']), 2);
                        $_SESSION['TRUSTEDSHOPS']['zahlart']     = Helper::getZahlartText($_SESSION['zahlungsart'], '', 'trustedshops');
                        $_SESSION['TRUSTEDSHOPS']['lieferdatum'] = date('Y-m-d', time() + 172800);

                        // Bestellung durchführen -> Danke-Seite anzeigen
                        header('Location: '.SHOP_URL_IDX.'/bestellt');
                        exit;
                     }

                     break;

                  // Letzter Schritt Bestellvorgang / Bestellnummer in Session
                  // Bei Lastschrift erfolgt hier Überprüfung Bankdaten
                  case 'bestellt':




                     if (isset($_SESSION['paydirekt_checkoutId'])) {
                        $this->task = 'bestellt';
                        unset($_SESSION['paydirekt_checkoutId']);
                        unset($_SESSION['paydirekt_token']);
                        return;
                     }
                     $user        = Control::getUser();
                     $re_id       = 0;
                     $zahlungsart = (isset($_SESSION['zahlungsart']) ? $_SESSION['zahlungsart'] : 0);

                     if ($zahlungsart == 111) {
                        if (empty($_REQUEST)) {
                           $this->task = 'amazon_login';
                           $_SESSION['task'] = 'amazon_login';
                           break;
                        }
                     }

                     // Bestellnummer vorhanden
                     if (isset($_SESSION['bestellnummer']) && $_SESSION['bestellnummer'] != '') {
                        $this->task = 'bestellt';
                        $_SESSION['task'] = 'bestellt';

                        $_SESSION['TRUSTEDSHOPS']['email_kunde'] = $_SESSION['email'];
                        $_SESSION['TRUSTEDSHOPS']['order_id']    = $_SESSION['bestellnummer'];
                        $_SESSION['TRUSTEDSHOPS']['netto']       = $_SESSION['wk_netto'];
                        $_SESSION['TRUSTEDSHOPS']['brutto']      = $_SESSION['wk_netto'] + $_SESSION['wk_steuer1'] + $_SESSION['wk_steuer2'] + $_SESSION['wk_steuer3'] + $_SESSION['versand_ust'] + $_SESSION['zahlart_ust'];
                        $_SESSION['TRUSTEDSHOPS']['waehrung']    = Helper::waehrungText((isset($_SESSION['user_waehrung_id']) ? $_SESSION['user_waehrung_id'] : $this->firma['waehrung1']), 2);
                        $_SESSION['TRUSTEDSHOPS']['zahlart']     = Helper::getZahlartText($_SESSION['zahlungsart'], '', 'trustedshops');
                        $_SESSION['TRUSTEDSHOPS']['lieferdatum'] = date('Y-m-d', time() + (2 * 86400));
                     }

                     // Bestellung schon erfolgt, User hat Refresh geklickt
                     else {
                        $_SESSION['task'] = 'bestellt';
                        header('location: '.SHOP_URL);
                     }

                     // Nur bei Lastschrift: Überprüfungen Bankdaten
                     if ($zahlungsart == 3) {
                        $bank_err = false;
                        unset($_SESSION['err_bank_name']);
                        unset($_SESSION['err_bank_inhaber']);
                        unset($_SESSION['err_bank_iban']);
                        unset($_SESSION['err_bank_bic']);
                        unset($_SESSION['err_bank_check']);

                        $bank_inhaber = $this->postString('bank_inhaber');
                        $bank_name    = $this->postString('bank_name');
                        $bank_iban    = $this->postString('bank_iban');
                        $bank_bic     = $this->postString('bank_bic');

                        // Ohne PDF zur Unterschrift -> Checkbox überprüfen
                        if ($this->firma['lastschrift_pdf_check'] == 'y') {
                           $bank_check = $this->postCheckbox('bank_check');
                           if ($bank_check != 'y') {
                              $_SESSION['err_bank_check'] = 1;
                              $bank_err = true;
                           }
                        }

                        if ($bank_inhaber == '') {
                           $_SESSION['err_bank_inhaber'] = 1;
                           $bank_err = true;
                        }

                        if ($bank_iban == '' || !Helper::checkIBAN($bank_iban)) {
                           $_SESSION['err_bank_iban'] = 1;
                           $bank_err = true;
                        }

                        if ($bank_bic == '') {
                           $_SESSION['err_bank_bic'] = 1;
                           $bank_err = true;
                        }

                        if ($bank_name == '') {
                           $_SESSION['err_bank_name'] = 1;
                           $bank_err = true;
                        }

                        $user->user['bank_inhaber'] = $bank_inhaber;
                        $user->user['bank_name']    = $bank_name;
                        $user->user['bank_iban']    = $bank_iban;
                        $user->user['bank_bic']     = $bank_bic;

                        $_SESSION['user']['bank_inhaber'] = $bank_inhaber;
                        $_SESSION['user']['bank_name']    = $bank_name;
                        $_SESSION['user']['bank_iban']    = $bank_iban;
                        $_SESSION['user']['bank_bic']     = $bank_bic;

                        if ($bank_err) {
                           // Formular nochmals anzeigen
                           $_SESSION['task'] = 'bezahlung';
                           $this->task = 'bezahlung';
                           $this->wk = Control::getWk();
                           return;
                        }

                        $user->storeBank();
                     } // Lastschrift

                     // Nur bei Lastschrift Kreditkarte: Überprüfungen Kreditkartendaten
                     if ($zahlungsart == 9) {
                        $kk_err = false;
                        $kk_datum_failed = false;

                        $kk_inhaber     = $this->postString('kk_inhaber');
                        $kk_name        = $this->postString('kk_name');
                        $kk_nr          = $this->postString('kk_nr');
                        $kk_pruef       = $this->postString('kk_pruef');
                        $kk_jahr        = sprintf('%04d', $this->postInt('kk_jahr'));
                        $kk_monat       = sprintf('%02d', $this->postInt('kk_monat'));
                        $kk_tag         = sprintf('%02d', $kk_jahr > 2015 && $kk_monat > 0 && $kk_monat < 13 ? cal_days_in_month(CAL_GREGORIAN, $kk_monat, $kk_jahr) : 1);
                        $kk_datum       = $kk_jahr.'-'.$kk_monat.'-'.$kk_tag.':::'.$kk_pruef;
                        $kk_datum_failed = !checkdate($kk_monat, $kk_tag, $kk_jahr);

                        if (!$kk_datum_failed) {
                           $kk_datum_failed = (strtotime("+2 years") - strtotime($kk_jahr.'-'.$kk_monat.'-'.$kk_tag) < 0 ? true : false);
                        }

                        if (!$kk_datum_failed) {
                           $kk_datum_failed = (strtotime("now") - strtotime($kk_jahr.'-'.$kk_monat.'-'.$kk_tag) > 0 ? true : false);
                        }

                        if ($kk_nr == '' || $kk_datum_failed === true || $kk_name == '' || $kk_inhaber =='' || strlen($kk_pruef) != 3) {
                           unset($_SESSION['err_kk_inhaber']);
                           unset($_SESSION['err_kk_name']);
                           unset($_SESSION['err_kk_nr']);
                           unset($_SESSION['err_kk_pruef']);
                           unset($_SESSION['err_kk_datum']);

                           if ($kk_inhaber == '') {
                              $_SESSION['err_kk_inhaber'] = 1;
                           }
                           if ($kk_nr == '') {
                              $_SESSION['err_kk_nr'] = 1;
                           }
                           if ($kk_datum_failed === true) {
                              $_SESSION['err_kk_datum'] = 1;
                           }
                           if (strlen($kk_pruef) != 3) {
                              $_SESSION['err_kk_pruef'] = 1;
                           }
                           if ($kk_name == '') {
                              $_SESSION['err_kk_name'] = 1;
                           }
                           $kk_err = true;
                        }

                        $user->user['kk_inhaber'] = $kk_inhaber;
                        $user->user['kk_name']    = $kk_name;
                        $user->user['kk_nr']      = $kk_nr;
                        $user->user['kk_datum']   = $kk_datum;
                        $user->user['kk_pruef']   = $kk_datum;

                        $_SESSION['user']['kk_inhaber'] = $kk_inhaber;
                        $_SESSION['user']['kk_name']    = $kk_name;
                        $_SESSION['user']['kk_nr']      = $kk_nr;
                        $_SESSION['user']['kk_datum']   = $kk_datum;
                        $_SESSION['user']['kk_pruef']   = $kk_pruef;

                        if ($kk_err) {
                           // Formular nochmals anzeigen
                           $_SESSION['task'] = 'bezahlung';
                           $this->task = 'bezahlung';
                           $this->wk = Control::getWk();
                           return;
                        }

                        $user->storeBank();
                     } // Lastschrift

                     // Bestellung durchführen
                     $this->bestellungAction($zahlungsart);
                  break;

                  case 'checkPerso':
                     $perso_nr = $this->postString('perso_nr', 'url');

                     if ($perso_nr == '') {
                        echo json_encode(array('status' => 'failed'));
                     }

                     $perso = Control::getModulePersocheck();
                     $test  = $perso->check_perso($perso_nr);

                     if ($test->status) {
                        $_SESSION['alter_check'] = true;
                        $_SESSION['alter_ok_typ'] = $test->typ;
                        $_SESSION['alter_ok_date'] = $test->geb_datum;

                        $heute  = new \DateTime(date('Y-m-d'));
                        $geburt = new \DateTime($test->geb_datum);
                        $alter  = (int)$geburt->diff($heute)->format('%y');

                        if ($alter < (int)$this->firma['fsk']) {
                           $_SESSION['alter_ok'] = false;
                           $_SESSION['alter_failed'] = true;
                        }

                        else {
                           $_SESSION['alter_ok'] = true;
                        }

                        echo json_encode(array('status' => 'ok'));
                        exit;
                     }

                     else {
                        echo json_encode(array('status' => 'failed'));
                        exit;
                     }

                     break;

                  case 'reset':
                     $_SESSION = array();
                     $this->db->query("DELETE FROM #__sessions WHERE session_control != 1");
                     header('Location: '.SHOP_URL);
                     exit;
                     break;

                  // Nach Bestellung: PDF oder Mein Konto anzeigen
                  case 'bestelltpdf':
                     if ($this->user_id > 0) {
                        header('Location: '.SHOP_URL_IDX.'/konto');
                        exit;
                     }

                     $this->re_id = $_SESSION['re_id'];
                     $this->task = 'bestellt';
                     $_SESSION['task'] = 'bestellt';
                     break;

                  case 'showSocials':
                     require_once TEMPLATE_PATH.'/popup_cookies.tpl.php';
//                     echo json_encode(array('status' => 'ok', 'html' => Helper::getSocialDs()));
                     echo json_encode(array('status' => 'ok', 'html' => $html));
                     exit;
                     break;

                  case 'showSocialsOk':
                     // $_SESSION['social_ok'] = 'y';
                     $_SESSION['cookie_social'] = true;
                     $social_url       = $this->postString('social_url');
                     $social_art_image = $this->postString('social_art_image');
                     $social_art_name  = $this->postString('#social_art_name');
                     $social_art_text  = $this->postString('#social_art_text');

                     $social = Helper::getSocialDetails($social_url, $social_art_image, $social_art_name, $social_art_text);

                     $social_html   = '';
                     $social_script = '';

                     for ($i = 0; $i < count($social['article']); $i++) {
                        $social_html .= $social['article'][$i]['image'];
                     }

                     for ($i = 0; $i < count($social['script']); $i++) {
                        $social_script .= $social['script'][$i];
                     }

                     echo json_encode(array('status' => 'ok', 'social_html' => $social_html, 'social_script' => $social_script));
                     exit;
                     break;

                  // Click auf Cookie-Zeile unten (Auswahl)
                  case 'cookieCheck':
                     $cookie = $this->postString('check');

                     // Alle cookies akzeptiren
                     if ($cookie == 'accept') {
                        $_SESSION['cookie_wesentlich']  = true;
                        $_SESSION['cookie_social']      = true;
                        $_SESSION['cookie_marketing']   = true;
                        $_SESSION['cookie_funktionell'] = true;
                     }

                     $_SESSION['cookie_check'] = 'y';

                     // Bei Cookie-Consent-Manger wird dieser von JS aufgerufen
                     echo json_encode(['status' => 'ok']);
                     exit;
                     break;

                  // Cookie-Popup anzeigen (Cookie-Consent-Manager) (von Cookie-Bereich oder Cookie-Symbol
                  case 'cookiePopup':
//                     $show_lang = $this
                     $html = require_once TEMPLATE_PATH.'/popup_cookies.tpl.php';
                     exit(json_encode(['status' => 'ok', 'html' => $html]));
                     break;

                       // Cookie-Popup anzeigen (Cookie-Consent-Manager) (von Cookie-Bereich oder Cookie-Symbol
                  case 'newsletterPopup':
//                     $show_lang = $this
                     $html = require_once TEMPLATE_PATH.'/popup_newsletter.tpl.php';
                     exit(json_encode(['status' => 'ok', 'html' => $html]));
                     break;

                  case 'newsletterSave':



                      $anrede = $this->postString('newsletterpopup_geschlecht');
                      $vorname  = $this->postString('newsletterpopup_firstname');
                      $nachname  = $this->postString('newsletterpopup_lastname');
                      $email  = $this->postString('newsletterpopup_mail');

                      $newsletterpopup_ds = ($this->postCheckbox('newsletterpopup_ds') == 'y' ? true : false);

                      if($newsletterpopup_ds){
                          // Email vorhanden?
                          $test = $this->db->querySingleValue("SELECT count(*) FROM #__users WHERE email = '$email'");

                          if ((int)$test > 0) { // , anrede = '$anrede', vorname = '$vorname', nachname = '$nachname'
                              // $this->db->query("UPDATE #__users SET newsletter = 'y', newsletter_check = 'ok' WHERE email = '$email'");
                          }else { // Neuer Eintrag
                              $this->db->query("INSERT INTO #__users SET anrede = '$anrede', email='$email', role = 9, vorname = '$vorname', nachname = '$nachname', newsletter = 'n'");
                          }


                          $user_id = $this->db->querySingleValue("SELECT id FROM #__users WHERE email = '$email'");

                          if($user_id != NULL){

                              $newsletterNewState = 'y';

                              $user = Control::getUser();

                              $user->newsletterChanged($user_id, $newsletterNewState); // verify mail senden wenn notwendig


                          }else{
                              exit(json_encode(['status' => 'ok', 'msg'=>'userid not found']));
                          }


                          exit(json_encode(['status' => 'ok', 'msg'=>'all ok '.$user_id]));

                      }else{ // kein Datenschutz, darf eigentlich nicht passieren
                          exit(json_encode(['status' => 'ok', 'msg'=>'no ds'])); // error?
                      }
                 break;


                  // Cookie-Popup anzeigen (Cookie-Bereich oder Footer
                  case 'cookieSave':
                     $_SESSION['cookie_wesentlich']  = ($this->postCheckbox('cookie_wesentlich') == 'y' ? true : false);
                     $_SESSION['cookie_social']      = ($this->postCheckbox('cookie_social') == 'y' ? true : false);
                     $_SESSION['cookie_marketing']   = ($this->postCheckbox('cookie_marketing') == 'y' ? true : false);
                     $_SESSION['cookie_funktionell'] = ($this->postCheckbox('cookie_funktionell') == 'y' ? true : false);
                     exit(json_encode(['status' => 'ok']));
                     break;

                  case 'siegelCheck':
                     $re_id = $this->postInt('re_id');
                     $email = $this->db->querySingleValue("SELECT email FROM #__rechnung WHERE id = $re_id");

                     // Bewertungsmail eintragen
                     if (defined('CONF_BEWERTUNG_MODE') && CONF_BEWERTUNG_MODE == 'bestellung') {
                        $zeitraum = 7;

                        if (defined('CONF_BEWERTUNG')) {
                           $zeitraum = CONF_BEWERTUNG;
                        }

                        $sql = "INSERT INTO #__bewertung SET best_id = $re_id, datum = NOW() + INTERVAL ".$zeitraum." DAY, email = '$email' ON DUPLICATE KEY UPDATE datum = NOW() + INTERVAL ".CONF_BEWERTUNG." DAY";
                        $this->db->query($sql);
                     }

                     echo json_encode(['status' => 'ok']);
                     exit;
                     break;

                  case 'filterPopup':
                     $katfilter = Control::getModuleFilter();
                     $cat_id    = $this->postInt('cat_id');
                     $html      = $katfilter->popupFe($cat_id);

                     echo json_encode(['status' => 'ok', 'html' => $html]);
                     exit;
                     break;

                  case 'filterSearch':
                     $this->task                              = 'filterSearch';
                     $_SESSION['task']                        = 'filterSearch';
                     $_SESSION['kategoriefilter']             = [];
                     $_SESSION['kategoriefilter']['marken']   = (isset($_POST['marken']) ? $_POST['marken'] : []);
                     $_SESSION['kategoriefilter']['werte1']   = (isset($_POST['werte1']) ? $_POST['werte1'] : []);
                     $_SESSION['kategoriefilter']['werte2']   = (isset($_POST['werte2']) ? $_POST['werte2'] : []);
                     $_SESSION['kategoriefilter']['merkmal1'] = $this->postInt('merkmal1');
                     $_SESSION['kategoriefilter']['merkmal2'] = $this->postInt('merkmal2');

                     $cat_id    = $_SESSION['kat_id'];
                     $categorie = Control::getCategories();
                     $cats      = $categorie->loadKategorie($cat_id);

                     $artikel      = ['', ''];
                     $artikel_text = '';
                     $articles     = Control::getArticles();
                     $articles->loadArticles();
                     $articles->render(0, $artikel);

                     if (!defined('CONF_MODULE_SORTIERUNG')) {
                        $artikel_text .= '<div id="filters_container" class="cbp-l-filters-button padding_top"></div>'.CR;
                     }

                     else {
                        include SHOP_PATH.'/classes/modules/sortierung/sortierung.module.php';
                        $artikel_text .= '<div id="filters_container" class="cbp-l-filters-button padding_top" style="min-height:54px; padding-right:165px;"></div>'.CR;
                        $artikel_text .= $mod_sort;
                     }

                     $artikel_text .= '<div id="article_container">'.$artikel[0].'</div>';
                     $counter = $this->hide_articles ? '' : $articles->getCounter();

                     echo json_encode(array('status'             => 'ok',
                                            'articles'           => $artikel_text,
                                            'counter'            => $counter,
                                            'artikel_max'        => $this->artikel_max,
                                            'artikel_seite'      => $_SESSION['artikel_seite'],
                                            'artikel_reihen'     => $_SESSION['artikel_reihen'],
                                            '$artikel_pro_reihe' => $_SESSION['artikel_pro_reihe']
                     ));
                     exit;
                     break;

                  case 'filterReset':
                     unset($_SESSION['kategoriefilter']);
                     $this->task       = 'filterReset';
                     $_SESSION['task'] = 'filterReset';
                     $cat_id           = $_SESSION['kat_id'];
                     $categorie        = Control::getCategories();
                     $cats             = $categorie->loadKategorie($cat_id);

                     $articles  = Control::getArticles();
                     $articles->loadArticles();
                     $articles->render(0, $artikel);
                     $artikel_text = '';

                     if (!defined('CONF_MODULE_SORTIERUNG')) {
                        $artikel_text .= '<div id="filters_container" class="cbp-l-filters-button padding_top"></div>'.CR;
                     }

                     else {
                        include SHOP_PATH.'/classes/modules/sortierung/sortierung.module.php';
                        $artikel_text .= '<div id="filters_container" class="cbp-l-filters-button padding_top" style="min-height:54px; padding-right:165px;"></div>'.CR;
                        $artikel_text .= $mod_sort;
                     }

                     $artikel_text .= '<div id="article_container">'.$artikel[0].'</div>';
                     $counter = $this->hide_articles ? '' : $articles->getCounter();

                     echo json_encode(array('status'         => 'ok',
                                            'articles'       => $artikel_text,
                                            'counter'        => $counter,
                                            'artikel_max'    => $this->artikel_max,
                                            'artikel_seite'  => $_SESSION['artikel_seite'],
                                            'artikel_reihen' => $_SESSION['artikel_reihen']
                     ));

                     exit;
                     break;

                  case 'changeShopsiegel':
                     $_SESSION['SHOPSIEGEL_MODE'] = $this->postCheckbox('mode');

                     echo json_encode(['status' => 'ok', 'mode' => $_SESSION['SHOPSIEGEL_MODE']]);
                     exit;
                     break;

                  case 'popupCheck':
                     $_SESSION['popup_check'] = $this->postCheckbox('popup_check');

                     echo json_encode(['status' => 'ok', 'mode' => $_SESSION['popup_check']]);
                     exit;
                     break;

                  case 'popupNaehrwerte':
                     $article      = Control::getArticles();
                     $article_data = $article->getNaehrwerte($this->postInt('parent_id'));
                     require_once TEMPLATE_PATH.'/popup_naehrwerte.tpl.php';
                     echo json_encode(['status' => 'ok', 'html' => $html]);
                     exit;
                     break;

                  case 'mixerAddArticle':
                     $article_id  = $this->postInt('article_id');
                     $category_id = $this->postInt('category_id');
                     $mixer_id    = $this->postInt('mixer_id');
                     $base_art_id = $this->postInt('base_art_id');

                     if ($category_id > 0) {
                        $mixer = Control::getModuleMixerKategorie();
                        $mixer->addArticle($mixer_id, $category_id, $article_id);
                     }

                     else if ($base_art_id > 0) {
                        $mixer = Control::getModuleMixerArtikel();
                        $mixer->addArticle($mixer_id, $article_id);
                     }

                     break;

                  case 'mixerDelArticle':
                     $article_id  = $this->postInt('article_id');
                     $category_id = $this->postInt('category_id');
                     $mixer_id    = $this->postInt('mixer_id');
                     $base_art_id = $this->postInt('base_art_id');

                     // Kategorie-Mixer
                     if ($category_id > 0) {
                        $mixer = Control::getModuleMixerKategorie();
                        $mixer->delArticle($mixer_id, $category_id, $article_id);
                     }

                     else if ($category_id == 0 && $article_id > 0) {
                        $mixer = Control::getModuleMixerArtikel();
                        $mixer->delArticle($mixer_id, $article_id);
                     }

                     break;

                  case 'mixerPlus':
                     $article_id  = $this->postInt('article_id');
                     $category_id = $this->postInt('category_id');
                     $mixer_id    = $this->postInt('mixer_id');
                     $base_art_id = $this->postInt('base_art_id');

                     if ($category_id > 0) {
                        $mixer = Control::getModuleMixerKategorie();
                        $mixer->plus($mixer_id, $category_id, $article_id);
                     }

                     break;

                  case 'mixerMinus':
                     $article_id  = $this->postInt('article_id');
                     $category_id = $this->postInt('category_id');
                     $mixer_id    = $this->postInt('mixer_id');
                     $base_art_id = $this->postInt('base_art_id');

                     if ($category_id > 0) {
                        $mixer = Control::getModuleMixerKategorie();
                        $mixer->minus($mixer_id, $category_id, $article_id);
                     }

                     break;

                  case 'mixerChange':
                     $category_id = $this->postInt('category_id');
                     $mixer_id    = $this->postInt('mixer_id');
                     $mixer_menge = $this->postInt('mixer_menge');

                     $mixer = Control::getModuleMixerKategorie();
                     $mixer->change($mixer_id, $category_id, $mixer_menge);

                     break;

                  case 'mixer1InWk':
                     $category_id = $this->postInt('category_id');
                     $mixer_id    = $this->postInt('mixer_id');
                     $popup       = $this->postInt('popup');

                     // Kategorie-Mixer
                     if ($category_id > 0) {
                        $mixer = Control::getModuleMixerKategorie();
                        $mixer->articleInWk($mixer_id, $category_id, $popup);
                     }

                     else if ($base_art_id > 0) {
                        $mixer = Control::getModuleMixerArtikel();
                        $mixer->delArticle($mixer_id, $article_id);
                     }

                     break;

                  case 'mixer1InMl':
                     $category_id = $this->postInt('category_id');
                     $mixer_id    = $this->postInt('mixer_id');

                     // Kategorie-Mixer
                     if ($category_id > 0) {
                        $mixer = Control::getModuleMixerKategorie();
                        $mixer->articleInMl($mixer_id, $category_id);
                     }

                     break;

                  case 'mixer1Nw':
                     $lang         = $this->selected_lang;
                     $lang_kunde   = $this->firma['default_lang'];
                     $mixer        = Control::getModuleMixerKategorie();

                     $article_data = $mixer->getNaehrwerte($this->postInt('category_id'), $_SESSION['mixer'][$this->postInt('category_id')], $lang, $lang_kunde);

                     require_once TEMPLATE_PATH.'/popup_naehrwerte.tpl.php';
                     echo json_encode(['status' => 'ok', 'html' => $html]);
                     exit;
                     break;

                  case 'mixer1NwWk':
                     Control::getWk();
                     $lang         = $this->selected_lang;
                     $lang_kunde   = $this->firma['default_lang'];
                     $wk           = $this->warenkorb[$this->postInt('wk_id')];
                     $mixer        = Control::getModuleMixerKategorie();
                     $article_data = $mixer->getNaehrwerte($wk['cat_id'], $wk['mixer'], $lang, $lang_kunde);

                     require_once TEMPLATE_PATH.'/popup_naehrwerte.tpl.php';
                     echo json_encode(['status' => 'ok', 'html' => $html]);
                     exit;
                     break;

                  case 'mixer2Nw':
                     $lang         = $this->selected_lang;
                     $lang_kunde   = $this->firma['default_lang'];
                     $mixer        = Control::getModuleMixerArtikel();
                     $article      = $mixer->getArticleById($this->art_id, $lang, $lang_kunde, 0, 0, false, (isset($_SESSION['mixer2']) ? $_SESSION['mixer2'] : ''));
                     $article_data = (array)$article;
                     //$mixer_sum    = true;

                     require_once TEMPLATE_PATH.'/popup_naehrwerte.tpl.php';
                     echo json_encode(['status' => 'ok', 'html' => $html]);
                     exit;
                     break;

                  case 'mixer2NwWk':
                     Control::getWk();
                     $wk           = $this->warenkorb[$this->postInt('wk_id')];
                     $lang         = $this->selected_lang;
                     $lang_kunde   = $this->firma['default_lang'];
                     $art_id       = $wk['art_id'];

                     $mixer        = Control::getModuleMixerArtikel();
                     $article      = $mixer->getArticleById($art_id, $lang, $lang_kunde, 0, 0, false, $wk['mixer']);
                     $article_data = (array)$article;
                     //$mixer_sum    = true;

                     require_once TEMPLATE_PATH.'/popup_naehrwerte.tpl.php';
                     echo json_encode(['status' => 'ok', 'html' => $html]);
                     exit;
                     break;

// *** Zahlungsmodule *****************************************************************************
                  // Manueller Test, kann weg
                  case 'test':
                     require_once SHOP_PATH.'/classes/modules/haendlerbund/haendlerbund.module.php';
                     $haendlerbund = new \KANPAICLASSIC\KANPAICLASSIC_modulHaendlerbund();
                     $haendlerbund->checkApi();
//                     require_once SHOP_PATH.'/classes/modules/paydirekt/paydirekt.module.php';
//                     $paydirekt = new \KANPAICLASSIC\KANPAICLASSIC_modulPaydirekt();

//                     $paydirekt->getToken();

                     exit;
                     break;


                  // Callback von Paydirekt, wenn Zahlung genehmigt.
                  // Bezahlung muss vom Shop bestätigt (capture)
                  case 'paydirekt_ok':
                     $bestellung = Control::getBestellung();
                     $test = $bestellung->checkPaydirekt();

                     if ($test === true) {
                        exit(header('Location: '.SHOP_URL_IDX.'/bestellt'));
                     }

                     else {
                        $this->task = 'paydirekt_fail';
                     }

                     break;

                  case 'paydirekt_cancel':
                     $this->task = 'paydirekt_fail';

                     break;

                  case 'paydirekt_age_faled':
                  case 'paydirekt_reject':
                  case 'paydirekt_status':
                     $this->task = 'paydirekt_fail';

                     if (defined('CONF_MODULE_PAYDIREKT_DEBUG')) {
                        $r = (isset($funcs[1]) ? $funcs[1] : '');
                        $q = file_get_contents('php://input');
                        $fp = fopen(DEBUG_LOG_DIR.'/paydirekt', 'a');
                        fwrite($fp, date('d.m.Y H:i:s').' : '.$funcs[0].CR.print_r($r, true).CR.print_r($q, true).CR);
                        fclose($fp);
                     }

                     break;

                  case 'billbee_api':
                     $billbee = Control::getModuleBillbee();
                     $billbee->billbeeApi();
                     break;

                  case 'checkEasycredit':
                     $easycredit = Control::getModuleEasycredit();
                     $x = $easycredit->checkEasycredit($this->postFloat('price'));

                     if ($x == false) {
                        echo json_encode(['status' => 'modellrechnung']);
                        exit;
                     }

                     echo json_encode(['status' => $x[0], 'html' => $x[1], 'zinsen' => $x[2], 'gesamt' => $x[3]]);
                     exit;
                     break;

                  case 'easycreditAccept':
                     $easycredit = Control::getModuleEasycredit();
                     $easycredit->vorgang();
                     break;

                  // Zahlungsart Klarna gewählt
                  case 'checkKlarna':
                     if (defined('CONF_KLARNA_DEBUG')) {
                        $fp = fopen(SHOP_PATH.'/classes/modules/xdebug/log/klarna.txt', 'a');
                        fwrite($fp, date('d.m.Y H:i:s')."\nchecKlarna: ".print_r($_REQUEST, true)."\n");
                        fclose($fp);
                     }

                     $klarna  = Control::getModuleKlarna();
                     $snippet = $klarna->checkout();

                     // Klarna-Popup anzeigen
                     // Daten zu Klarna fehlerhaft, z.B kein Name
                     if ($snippet['status'] == 'error') {
                        echo json_encode(['status' => 'error', 'html' => $snippet['html'] ]);
                        exit;
                     }

                     // Daten zu Klarna OK, Popup anzeigen
                     else if ($snippet['status'] == 'not_loggedin') {
                        echo json_encode(['status' => $snippet['status'], 'html' => $snippet['html'] ]);
                        exit;
                     }

                     // Warenkorb geändert
                     else if ($snippet['status'] == 'update') {
                        echo json_encode(['status' => $snippet['status'], 'html' => $snippet['html'] ]);
                        exit;
                     }

                     $_SESSION['klarna_snippet']  = $snippet['html'];
                     $_SESSION['klarna_order_id'] = $snippet['klarna_order_id'];

                     // Nach erfolgreicher Anmeldung Redirect durch Klarna -> klarna_confirm/<id>
                     echo json_encode(['status' => 'popup', 'html' => $snippet['html']]);
                     exit;
                     break;

                  // AJAX-Polling
                  case 'klarnaStatus':
                     exit(json_encode(['status' => 'ok', 'klarna_status' => $this->db->querySingleValue("SELECT status FROM #__klarna WHERE klarna_order_id = '".$_SESSION['klarna_order_id']."'")]));
                     break;

                  // Von bestellt.tpl.php aufgerufen - $_SESSION['klarna_checkout_ok'] in neuem Fenster anzeigen und löschen
                  case 'klarna_checkout_back':
                     // Snippet Bestellinfo für bestellt.tpl.php
                     echo $_SESSION['klarna_checkout_back'];
                     unset($_SESSION['klarna_checkout_back']);
                     exit;
                     break;

                  // Redirect von Klarna nach checkout
                  // Klarna-Checkout OK - Bestätigung von Klarna nach Checkout
                  case 'klarna_confirm':  // erfolgreich
                     // Snippet2 merken, wird in bestellt angezeigt als Popup angezeigt
                     $klarna               = Control::getModuleKlarna();
                     $order_id             = $klarna->orderId($funcs[1]);
                     $klarna_checkout_back = $klarna->retrieve($order_id);

                     // Snippet Bestellinfo - wird bei Anzeige bestellt als Popup ausgegeben
                     $_SESSION['klarna_checkout_back'] = $klarna_checkout_back;

                     if (defined('CONF_KLARNA_DEBUG')) {
                        $fp = fopen(SHOP_PATH.'/classes/modules/xdebug/log/klarna_confirm.txt', 'a');
                        fwrite($fp, date('d.m.Y H:i:s')."\nklarna_confirm: ".print_r($klarna_checkout_back, true)."\n");
                        fclose($fp);
                     }

                     // Keine neue Seite anzeigen / AJAX-Polling läuft
                     exit(header("HTTP/1.0 204 No Content"));
                     break;

                  case 'klarna_valid':  // erfolgreich
                     // Snippet2 merken, wird in bestellt angezeigt als Popup angezeigt
                     $klarna               = Control::getModuleKlarna();
                     $order_id             = $klarna->orderId($funcs[1]);
                     $klarna_checkout_back = $klarna->retrieve($order_id);

                     // Snippet Bestellinfo - wird bei Anzeige bestellt als Popup ausgegeben
                     $_SESSION['klarna_checkout_back'] = $klarna_checkout_back;

                     if (defined('CONF_KLARNA_DEBUG')) {
                        $fp = fopen(DEBUG_LOG_DIR.'/klarna_params_klarna_confirm', 'a');
                        fwrite($fp, date('d.m.Y H:i:s').' : '.print_r($klarna_checkout_back, true).CR);
                        fclose($fp);
                     }

                     // Keine neue Seite anzeigen / AJAX-Polling läuft
                     header("HTTP/1.0 204 No Content");
                     exit;
                     break;

                  // Antwort von Klarna bestätigen
                  case 'klarna_push':     //
                  case 'klarna_cancel':   //
                  case 'klarna_notify':   //
                  case 'klarna_shipping': // Lieferadresse geändert
                  case 'klarna_address':  // Rechnungsadresse geändert
                  case 'klarna_country':  // Land Rechnungsadresse geändert
                     if (defined('CONF_KLARNA_DEBUG')) {
                        $r = $funcs[1];
                        $q = file_get_contents('php://input');
                        $fp = fopen(DEBUG_LOG_DIR.'/klarna', 'a');
                        fwrite($fp, date('d.m.Y H:i:s').' : '.$funcs[0].CR.print_r($r, true).CR.print_r($q, true).CR);
                        fclose($fp);
                     }

                     echo '';
                     exit;
                     break;

                  // Antwort von Amazon verarbeiten
                  case 'amazonNotify':
                     $bestellung = Control::getBestellung();
                     $bestellung->amazonNotify();
                     break;

                  case 'checkAmazon':
                     $amazon = Control::getAmazon();
                     $test = $amazon->checkPayment();

                     if ($test) {
                        echo json_encode(array('status' => 'ok'));
                        exit;
                     }

                     echo json_encode(array('status' => 'failed', 'msg' => 'Fehler bei der Übertragung'));
                     exit;
                     break;

                  case 'amazon_fail':
                     $this->paymenttext = $this->text->get('zahlart', 'amazon');
                     $this->task = 'amazon_fail';
                     break;

                  // Antwort von Mollie nach Bezahlung verarbeiten
                  case 'mollie_notify':
                     $mollie = Control::getMollie();
                     $mollie_id = $_REQUEST['id'];
                     $mollie->checkPaymentStatus($mollie_id);
                     break;

                  // Antwort von Paypal nach Bezahlung verarbeiten
                  case 'paypal_notify':
                     // if (defined('CONF_PAYPAL_DEBUG')) {
                     //    $fp = fopen(SHOP_PATH.'/classes/modules/xdebug/log/paypal_notify1.txt', 'a');
                     //    fwrite($fp, date('d.m.Y H:i:s')."\n".print_r($_REQUEST, true)."\n");
                     //    fclose($fp);
                     // }

                     $bestellung = Control::getBestellung();
                     $bestellung->paypalNotify();
                     break;

                  // Kunde hat Bezahlung durch Paypal abgebrochen
                  case 'paypal_fail':
                     $this->paymenttext = $this->text->get('zahlart', 'paypal');
                     $this->task = 'paypal_fail';
                     break;

                  // Antwort von Paypal v2 nach Bezahlung verarbeiten
                  case 'paypalv2_notify':

                     $bestellung = Control::getBestellung();
                     $bestellung->paypalv2Notify();
                     break;


                  case 'ppp_back':
                     $this->re_id           = $funcs[1];
                     $_SESSION['re_id']     = $this->re_id;
                     $_SESSION['paymentId'] = $_REQUEST['paymentId'];
                     $_SESSION['token']     = $_REQUEST['token'];
                     $_SESSION['PayerID']   = $_REQUEST['PayerID'];

                     $this->task = 'ppp_back';

                     break;

                  case 'checkPpp':
                     $re_id = $this->postInt('re_id');
                     $ppp = Control::getPaypalPlus();
                     $test = $ppp->pppBack($re_id);

                     if ($test == 'ok' || $test == 'done') {
                        echo json_encode(array('status' => 'ok', 'mode' => $test));
                     }

                     else {
                        echo json_encode(array('status' => 'error', 'msg' => $test));
                     }

                     exit();
                     break;

                  case 'execPpp':
                     $re_id = $this->postInt('re_id');
                     $ppp   = Control::getPaypalPlus();
                     $test  = $ppp->pppExec($re_id);

                     if ($test == 'ok' || $test == 'done') {
                        echo json_encode(array('status' => 'ok', 'mode' => $test));
                     }

                     else {
                        echo json_encode(array('status' => 'error', 'msg' => $test));
                     }

                     exit();
                     break;

                  case 'ppp_cancel':
                  case 'ppp_fail':
                  case 'ppp_hooks':
                     $this->paymenttext = $this->text->get('zahlart', 'paypalplus');
                     $this->task = 'ppp_fail';
                     break;

                  // Antwort von Sofortüberweisung verarbeiten
                  case 'sofortueberweisung_notify':
                     $bestellung = Control::getBestellung();
                     $bestellung->sofortueberweisungNotify();
                     break;

                  case 'sofortueberweisung_fail':
                     $this->task = 'sofort_fail';
                     break;

                  case 'sofort_error':
                     $this->task = 'sofort_error';
                     break;

                  // Antwort von VRpay verarbeiten
                  case 'vrpay_notify':
                     $bestellung = Control::getBestellung();
                     $bestellung->vrpayNotify();
                     break;

                  // Kunde hat Bezahlung durch Paypal abgebrochen
                  case 'vrpay_fail':
                     $this->task = 'vrpay_fail';
                     break;

                  case 'twintWaitPairing':
                     $pairing_uuid = $this->postString('pairing_uuid');
                     $twint = Control::getModuleTwint();

                     if ($twint->waitPairing($pairing_uuid)) {
                        $twint->startOrder($pairing_uuid);
                        echo json_encode(array('status' => 'order'));
                        exit;
                     }

                     else {
                        echo json_encode(array('status' => 'wait'));
                        exit;
                     }

                     break;

                  case 'twintWaitOrder':
                     $pairing_uuid = $this->postString('pairing_uuid');
                     $twint = Control::getModuleTwint();

                     if ($twint->waitOrder($pairing_uuid)) {
                        if ($twint->confirmOrder($pairing_uuid)) {
                           echo json_encode(array('status' => 'ok'));
                           exit;
                        }

                           echo json_encode(array('status' => 'failed'));
                           exit;
                     }

                     else {
                        echo json_encode(array('status' => 'wait', 'info' => $_SESSION['debug']));
                        exit;
                     }

                     break;

                  // Ratenkauf abgebrochen -> WK anzeigen
                  case 'easycredit_back':
                     $this->task = 'warenkorb';
                     $this->setSession('zahlungsart', 0);
                     break;

                  // Ratenkauf abgelehnt
                  case 'easycredit_no':
                     $_SESSION['easycredit_deny'] = $this->text->get('easycredit', 'deny');
                     $this->task = 'warenkorb';
                     break;

                  // Ratenkauf genehmigt
                  case 'easycredit_yes':
                     // Entscheidung abholen
                     $easycredit = Control::getModuleEasycredit();
                     $status = $easycredit->entscheidung();

                     if ($status == 'GRUEN') {
                        $this->task = 'warenkorb';
                        // Link Vorvertrag
                        $vorvertrag = $easycredit->vorvertrag();
                        $_SESSION['easycredit_vorvertrag']   = $vorvertrag->allgemeineVorgangsdaten->urlVorvertraglicheInformationen;
                        $_SESSION['easycredit_zahlungsplan'] = $vorvertrag->tilgungsplanText;
                        // Details zum Ratenplan
                        $_SESSION['easycredit_finanzierung'] = $easycredit->finanzierung();
                        // Als genehmigt markieren
                        $_SESSION['easycredit_check'] = 'y';
                        $_SESSION['easycredit_deny']  = '';
                     }

                     else {
                        $this->task = 'warenkorb';
                        $_SESSION['easycredit_deny'] = $this->text->get('easycredit', 'deny');
                     }

                     header('Location: '.SHOP_URL_IDX.'/warenkorb');
                     exit;
                     break;
                     // }
                  case 'postfinance_ok':
                     // $fp = fopen(SHOP_PATH.'/classes/modules/xdebug/log/postfinance_ok.txt', 'a');
                     // fwrite($fp, date('d.m.Y H:i:s')."\n".$funcs[0]."\n".print_r($_REQUEST, true)."\n".print_r(file_get_contents('php://input'))."\n");
                     // fclose($fp);

                     header('Location: '.SHOP_URL_IDX.'/bestellt');
                     exit;
                     break;

                  case 'postfinance_decline':
                  case 'postfinance_exeption':
                  case 'postfinance_cancel':
                     // $fp = fopen(SHOP_PATH.'/classes/modules/xdebug/log/postfinance_notify1.txt', 'a');
                     // fwrite($fp, date('d.m.Y H:i:s')."\n".$funcs[0]."\n".print_r($_REQUEST, true)."\n".print_r(file_get_contents('php://input'))."\n");
                     // fclose($fp);

                     header('Location: '.SHOP_URL_IDX.'/warenkorb');
                     exit;
                     break;

                  // Aufruf Bezahl-Module nach Bezahlung durch Kunde
                  case 'mollie_ok':
                  case 'paypal_ok':
                  case 'ppp_ok':
                  case 'sofortueberweisung_ok':
                  case 'vrpay_ok':
                  case 'easycredit_ok':
                     $bestellung = Control::getBestellung();
                     $this->task = 'bestellt';
                     $_SESSION['task'] = 'bestellt';

                     $this->re_id = $_SESSION['re_id'];
//                     $bestellung = Control::getBestellung();

                     // Paypal - warten auf TXN
                     if ($funcs[0] == 'paypal_ok') {
                        // Check, ob Daten von Paypal empfangen wurden. liefert immer true, bei Fehler Mail an Admin
                        if (!$bestellung->checkPaypal($_SESSION['msg_bestellnummer'])) {
                           $this->task = 'paypal_error';
                           return;
                        }
                     }

                     // Sofortüberweisung nicht OK
                     if ($funcs[0] == 'sofortueberweisung_ok') {
                        // Check, ob Daten von Sofortübeweisung empfangen wurden
                        if (!$bestellung->checkSofort($_SESSION['msg_bestellnummer'])) {
                           $this->task = 'sofort_error';
                           return;
                        }
                     }

                     // VR-Pay
                     if ($funcs[0] == 'vrpay_ok') {
                        // Check, ob Daten von VRpay empfangen wurden
                        if (!$bestellung->checkVrpay($_SESSION['msg_bestellnummer'])) {
                           $this->task = 'vrpay_error';
                           return;
                        }
                     }

                     // EasyCredit
                     if ($funcs[0] == 'easycredit_ok') {
                     }

                     // Bei Paypal und Zahlungsautomatik erfolgt DL-Mails in bestellungen.class.php
                     if ($funcs[0] != 'paypal_ok' || ($funcs[0] == 'paypal_ok' && Helper::getData('paypal_danke', 'n') == 'y')) {
                        // Downloadlink in DB eintragen und per Mail versenden
                        $dl    = Control::getDownload();
                        $links = [];
                        $links = $dl->getLinks($this->re_id);

                        if ($links && count($links) > 0) {
                           $mail = Control::getMail();
                           $email = $this->db->querySingleValue("SELECT email FROM #__rechnung WHERE id = $this->re_id");

                           for ($i = 0; $i < count($links); $i++) {
                              $mail->sendDownloadLink($email, $this->re_id, $links[$i]);
                           }
                        }
                     }

                     break;

                  // Formular login anzeigen (Neuer / Bestehender Kunde / Sofortkauf)
                  case 'bestellungfront':
                     $this->admin_user = (int)$funcs[1];
                     $this->task = 'bestellungfront';
                     break;

                  case 'login':
                     $this->task = 'login';
                     break;

                  case 'amazonLogin':
                  // Noch nicht implementiert
                     $access_token = $_REQUEST['access_token'];
                     //https://shop.hcns.de/amazonLogin?access_token=Atza|IQEBLzAtAhUAjmo8KVDUjxVhpyUM4HbpfTjF_usCFAE2c7KeccwJ2lIPv-PzPgySlQ13DmPL6ypqOSfY9TLrlgeWrAc-giq2dGCeU5qEqym7bC5mrgtRwk0QYJsGSu_hLbNLGx2g_TkuyC4ly2cRrs0_JVNs5nnknOqDBHOcIoR6hsihpysieP5ZShFrMTqk-5Uwp3bPkg8LTEKqwDOkGtVqaTefpymXaYnqy6hht97zZm2KXbXqevOfrEJrcU1mOGY6G2vk7aDLiiRsSgATLcwtoVvzuYb8Ew5UpJpLt3K79b7RjVvke7usSVwPwUCDysOqfmqtGT9-QD9QPc2G8-0_OlR9UvsBeRCXkAT4aW9sM45O_KuILlYB09UTNvozLbMMXceU7yfcPYLGFPklvoL4Dx95ep0uDjSxeAQDwbidcUQ9g4E19UBrWeyDNMC-mC1DJ6kMpCzFP1pse75XxUJykbRFDeemS9Ja-7PfCRrWc6ACg0JwnSdhtSPLxNFx0uMqVPX5VL6bFhbxYLlGMwaO055xNNeWFT36tVmpkP0Q4OehB3MLdrenHWlqCjZBfByodCjJ7VvAzIF2M9RsFl-rzznU7Dqp-HX8IsNYYDIn013hE-bjAb5UWsNQWjLGm2UlIxf8VLM3os7FyJC511koIpM6311nqbkTPI8AzPny73z1&token_type=bearer&expires_in=3600&scope=profile%20payments%3Awidget%20payments%3Abilling_address%20payments%3Ashipping_address
                     //                     $this->task = 'login';
                     $c = curl_init('https://api.sandbox.amazon.com/auth/o2/tokeninfo?access_token='. urlencode($_REQUEST['access_token']));
                     curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
                     $r = curl_exec($c);
                     curl_close($c);
                     $d = json_decode($r);
                     //if ($d->aud != 'YOUR-CLIENT-ID') {
                     //  // the access token does not belong to us
                     //  header('HTTP/1.1 404 Not Found');
                     //  echo 'Page not found';
                     //  exit;
                     //}

                     // exchange the access token for user profile
                     $c = curl_init('https://api.sandbox.amazon.com/user/profile');

                     curl_setopt($c, CURLOPT_HTTPHEADER, array('Authorization: bearer '.$_REQUEST['access_token']));
                     curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
                     $r = curl_exec($c);
                     curl_close($c);
                     $d = json_decode($r);
                     echo sprintf('%s %s %s<br />', $d->name, $d->email, $d->user_id);
                     print_r($d);

                     exit;
                  break;

                  // Formular Anmelden anzeigen (neuer Kunde)
                  case 'anmelden':
                     $this->task = 'anmelden';
                     break;

                  // Portal zusätzlich
                  // Formular Anmelden anzeigen (neuer Kunde)
                  case 'anmelden_haendler':
                     $this->task = 'anmelden_haendler';
                     break;

                  // Anmeldung neuer Kunde überprüfen
                  case 'checkanmeldung':
                     $user = Control::getUser();

                     // Formulardaten korrekt
                     $test = $user->checkAnmeldung($this->postInt('haendler'));

                     if ($test) {
                        $test = $user->write('insert');

                        if ($test === true) {
                           // Link für Validierung
                           $forgotten = $user->forgotten($user->user['email'], 'verify');

                           /*
                           // Haendleranmeldung ?
                           if ($this->postInt('haendler') == 1) {
                              $_SESSION['user_id'] = 0;
                              // User als Händler eintragen
                              $user->setHaendler($user->user['id'], $this->postString('website'), $user->user['email']);

                              if ($this->firma['haendler_manual'] == 'y') {
                                 $_SESSION['admin_msg'] = $this->text->get('adm_msg', 'anm_wait');
                                 header('Location: '.SHOP_URL);
                              }

                              else {
                                 $token = session_id();
                                 $this->db->query("REPLACE #__admin_login SET token = '$token', user = '".$user->user['email']."', pass = '".$this->postString('password1')."'");
                                 header('Location: '.ADMIN_URL_IDX.'/'.$token);
                              }

                              // Bestätigungsmail senden
                              $mail = Control::getMail();
                              $mail->sendAnmeldung($user->user['email'], SHOP_URL_IDX.'/validate/'.$forgotten, $user);
                              // if ($this->firma['haendler_manual'] == 'y') {
                              $mail->sendAnmeldungHaendler();
                              // }

                              // Bestätigungsmail Newsletter-Anmeldung, wenn newsletter = 'y'
                              if ($this->postCheckbox('newsletter') == 'y') {
                                 $link = $user->anmeldungNL($_SESSION['email']);
                                 $mail->sendAnmeldungNL($this->postString('email', '', 'sql'), SHOP_URL_IDX.'/validatenl/'.$link, $user);
                              }

                              // Händleranmeldung Ende
                              exit;
                           }
                           */

                           // Kunden-Anmeldung
                           $this->loginerror = false;
                           $this->logged_in  = true;
                           $this->user_id    = (int)$user->user['id'];

                           if ($this->firma['account_manual'] == 'y') {
                              $_SESSION['admin_msg'] = $this->text->get('adm_msg', 'anm_wait');
                           }

                           $_SESSION['logged_in'] = $this->logged_in;
                           $_SESSION['user_id']   = $this->user_id;
                           $_SESSION['user_name'] = $user->user['nachname'];
                           $_SESSION['email']     = $user->user['email'];

                           // Bestätigungsmail / Validierungslink senden
                           $mail = Control::getMail();
                           $mail->sendAnmeldung($user->user['email'], SHOP_URL_IDX.'/validate/'.$forgotten, $user);

                           if ($this->firma['account_manual'] == 'y') {
                              $mail->sendAnmeldungKunde();
                           }

                           // Bestätigungsmail Newsletter-Anmeldung, wenn newsletter = 'y'
                           if ($this->postCheckbox('newsletter') == 'y') {
                              $link = $user->anmeldungNL($_SESSION['email']);
                              $mail = Control::getMail();
                              // Email aus Übergabeparameter, falls Email geändert wurde
                              $mail->sendAnmeldungNL($this->postString('email', '', 'sql'), SHOP_URL_IDX.'/validatenl/'.$link, $user);
                           }

                           // Warenkorb in DB speichern
                           $this->warenkorb = $_SESSION['warenkorb'];
                           $wk = Control::getWk();

                           // Manuelle Freigabe
                           if ($user->user['gesperrt'] == 'y') {
                              $this->logged_in = false;
                              $this->user_id = 0;
                              unset ($_SESSION['user']);
                              $_SESSION['user_id'] = 0;
                              // $_SESSION['anm_msg'] = $this->text->get('anmelden', 'freischalten');
                              $this->user_id = 0;
                              header('Location: '.SHOP_URL_IDX.'/login');
                              exit;
                           }

                           // Während Bestellung
                           else if (count($this->warenkorb) > 0) {
                              header('Location: '.SHOP_URL_IDX.'/warenkorb');
                              exit;
                           }

                           else {
                              header('Location: '.SHOP_URL_IDX.'/konto');
                              exit;
                           }
                           exit;
                        }

                        else {
                           $this->task = 'anmelden';
                           if ($this->postInt('haendler') == 1) {
                              $this->task = 'anmelden_haendler';
                           }
                        }
                     }

                     // Daten waren fehlerhaft
                     else {
                        if ($this->valid_user) {
                           unset($_SESSION['user']);
                           // $_SESSION['anm_msg'] = $this->text->get('anmelden', 'freischalten');
                           $this->user_id = 0;
                           header('Location: '.SHOP_URL_IDX.'/login');
                           exit;
                        }

                        $_SESSION['user'] = $user->user;
                        $this->task = 'anmelden';

                        if ($this->postInt('haendler') == 1) {
                           $this->task = 'anmelden_haendler';
                        }

                        return;
                     }
                     break;

                  // Passwort vergessen
                  case 'forgotten':
                     $this->mailvorhanden = false;
                     $forgotten = '';
                     $email = $this->postString('email');
                     $user = Control::getUser();

                     if ($user->mailVorhanden($email) && $user->checkGesperrt($email)) {
                        $forgotten = $user->forgotten($email);

                        // Bestätigungsmail senden
                        $mail = Control::getMail();
                        $mail->sendForgotten($email, SHOP_URL_IDX.'/validate/'.$forgotten);
                        $this->task = 'forgotten';
                        $this->mailvorhanden = true;
                     }

                     // Mail nicht vorhanden
                     else {
                        $this->task = 'forgotten';
                     }

                     break;

                  // Händler-Login
                  case 'checkloginh':
                     $this->loginh = true;
                     // kein Break!!!
                  // Kunden-Login
                  case 'checklogin':
                     $checkLogin = $this->checkLogin();

                     if ($checkLogin) {
                        // Warenkorb laden, damit aktueller WK in DB gespeichert wird
                        if (isset($_SESSION['warenkorb'])) {
                           $this->warenkorb = $_SESSION['warenkorb'];
                        }

                        $wk = Control::getWk();
                        $ml = Control::getMl();

                        // Wenn Altersverifikation noch nicht durchgeführt ist
                        if (defined('CONF_MODULE_CHECKPERSO') && !$_SESSION['alter_check']) {
                           header('Location: '.SHOP_URL_IDX.'/konto');
                           break;
                        }

                        header('Location: '.SHOP_URL_IDX.'/warenkorb');

                        exit;

                     }else { // login falsch
                        $this->task = 'login';
                        $this->loginerror = true;
                        return;
                     }

                     break;

                  case 'checkadmin':
                     $checkLogin = $this->checkLogin(true);
                     $this->admin_user = $this->postInt('admin_user');

                     if ($checkLogin) {
                        $wk = Control::getWk();
                        $ml = Control::getMl();
                        $this->task = '';
                        header('Location: '.SHOP_URL);
                        exit;
                     }

                     // login falsch
                     else {
                        $this->task = 'bestellungfront';
                        $this->loginerror = true;
                        return;
                     }
                     break;

                  case 'downloadb':
                     $val = ((((int)base64_decode($funcs[1]) + 29) / 57));
                     if (is_int($val)) {
                        $pdf = Control::getPdf();
                        $pdf->makePdf($val, 'bestellung', 'D', 'kunde');
                     }
                     // kein break

                  case 'downloadcb':
                     $val = ((((int)base64_decode($funcs[1]) + 29) / 57));
                     if (is_int($val)) {
                        $pdf = Control::getPdfCollector();
                        $pdf->makePdf($val, 'bestellung', 'D', 'kunde');
                     }
                     // kein break

                  case 'downloadr':
                     $val = (((int)base64_decode($funcs[1]) + 29) / 57);
                     if (is_int($val)) {
                        $pdf = Control::getPdf();
                        $pdf->makePdf($val, 'rechnung', 'D', 'kunde');
                     }
                     // kein break - läuft bei fehler auf Logout

                  case 'downloadcr':
                     $val = (((int)base64_decode($funcs[1]) + 29) / 57);
                     if (is_int($val)) {
                        $pdf = Control::getPdfCollector();
                        $pdf->makePdf($val, 'rechnung', 'D', 'kunde');
                     }
                     // kein break - läuft bei fehler auf Logout

                  case 'profil':
                     $this->task = 'profil';
                     $this->haendler_id = $funcs[1];
                     break;

                  case 'logout':
                     $_SESSION['cat_pass'] = array();
                     $this->clearSession();
                     $this->delSession();
                     $this->user_id = 0;
                     header('Location: '.SHOP_URL_IDX.'/login');
                     exit;
                     break;

                  case 'deleteKunde':
                     $text = Control::getText();
                     $html = '';
                     require_once TEMPLATE_PATH.'/popup_loeschen.tpl.php';
                     echo json_encode(['status' => 'ok', 'html' => $html]);
                     exit;
                     break;

                  case 'delete':
                     $text = Control::getText();
                     $user = Control::getUser();
                     $user->delete($this->user_id);
                     $_SESSION['cat_pass'] = array();
                     $this->clearSession();
                     $this->delSession();
                     $this->user_id = 0;
                     $this->initSession();
                     $_SESSION['admin_msg'] = $text->get('msg', 'loeschen');

                     header('Location: '.SHOP_URL);
                     exit;
                     break;

                  case 'konto':
                     if ($this->user_id > 0 ) {
                        $_SESSION['task'] = 'konto';
                        $this->task = 'konto';
                        $this->mode = $this->postString('mode');

                        // Konto wurde angezeigt, auf Änderungen prüfen
                        if ($this->mode == 'changed') {
                           // Userdaten holen
                           $user = Control::getUser();

                           // Newsletter
                           if ($this->firma['gutschein_aktiv'] ==  'y') {
                              $_SESSION['newsletter'] = $this->postCheckbox('newsletter');
                              $user->newsletterChanged($this->user_id, $_SESSION['newsletter']);
                           }

//                           $_SESSION['newsletter'] = $this->postCheckbox('newsletter');

                           /*
                           if ($user->newsletterChanged($this->user_id, $this->postCheckbox('newsletter'))) {
                              $link = $user->anmeldungNL($_SESSION['email']);
                              $mail = Control::getMail();
                              // Email aus Übergabeparameter, falls Email geändert wurde
                              $mail->sendAnmeldungNL($this->postString('email', '', 'sql'), SHOP_URL_IDX.'/validatenl/'.$link, $user);
                           }
                           */

                           // Passwort ändern
                           if ($this->postString('password1') != '')  {
                              $user->checkPw();
                           }

                           // Test auf Änderungen, außer PWs
                           if ($user->checkAdresse()) {    // Userdaten geändert: true;
                              // Parameter auf Gültigkeit prüfen
                              $test = $user->checkBestellung();  // Userdaten gültig: true;

                              // Mail geändert und beide Felder ungleich
                              if ($user->mailchanged && $this->postString('email') != $this->postString('email2')) {
                                 $user->user_err['email2_err'] = true;
                                 return;
                              }


                              if ($test) {
                                 // Userdaten geändert und gültig - Speichern
                                 $user->write('update');
                              }
                           }

                           exit(header('Location: '.SHOP_URL));
                        }
                     }

                     else {
                        exit(header('Location: '.SHOP_URL.'/'));
                     }
                     break;

                  case 'DELpostfinance':
                     require_once SHOP_PATH.'/classes/modules/postfinance/postfinance.module.php';
                     $postfinance = new \KANPAICLASSIC\KANPAICLASSIC_modulPostfinance();
                     echo $postfinance->getForm();

                     exit('postfinance');
                     break;

                  case 'test_s':
                     echo session_gc();
                     exit;
                     break;

                  default:
                     // Kontakt, Impressum usw. aufrufen
                     $this->task     = $funcs[0];
                     $this->task_sub = (isset($funcs[1]) ? $funcs[1] : '');
                     unset($_SESSION['suche']);
                     unset($_SESSION['kategoriefilter']);
                     break;
               }  // switch
            } // if (isset($funcs[0]))

            // Defaultwerte, da 1. Aufruf der Seite
            else {
               $this->task = '';
               $this->kat_id = 0;
               $this->art_id = 0;
               $_SESSION['artikel_seite'] = 1;
               $_SESSION['task'] = '';
               $_SESSION['kat_id'] = 0;
               $_SESSION['art_id'] = 0;
               unset($_SESSION['suche']);
               unset($_SESSION['kategoriefilter']);
            }
         }  // if ($my_funcs)

         else {
            $this->task = '';
            $this->kat_id = 0;
            $this->art_id = 0;

            $_SESSION['artikel_seite'] = 1;
            $_SESSION['task'] = '';
            $_SESSION['kat_id'] = 0;
            $_SESSION['art_id'] = 0;
         }
      }


      else {
         $this->task = '';
         $this->kat_id = 0;
         $this->art_id = 0;

         $_SESSION['artikel_seite'] = 1;
         $_SESSION['task'] = '';
         $_SESSION['kat_id'] = 0;
         $_SESSION['art_id'] = 0;
         $_SESSION['artikel_seite'] = 1;
         unset($_SESSION['suche']);
         unset($_SESSION['kategoriefilter']);
      }

      $_SESSION['lang'] = $this->selected_lang;
   }

   private function bestellungAction($zahlungsart) {

      // EasyCredit
      if ($zahlungsart == 13 && (!isset($_SESSION['vorgangskennung']) || $_SESSION['vorgangskennung'] == '')) {
         header('Location: '.SHOP_URL_IDX.'/warenkorb');
         exit;
      }

      // Bestellung durchführen
      $this->task   = 'bestellt';
      $bestellung   = Control::getBestellung();
      $mail         = Control::getMail();
      $wk           = Control::getWk();

      // Bestellung in DB eintragen
      // Bei Paypal / Sofortüberweisung / RVpay usw. erfolgt REDIRECT, nachfolgender Code wird jedoch ausgeführt
      // PaypalPlus wird weiter unten script für iFrame zurückgegeben
      $re_id = $bestellung->bestellungNeu($_SESSION['user_msg'], 0);

      // TWINT
      if ($zahlungsart == 12) {
         $bestellung->twint($re_id);
      }

      $_SESSION['AFTERBUY_NR'] = $_SESSION['bestellnummer'];
      $_SESSION['AFTERBUY_ID'] = $re_id;

      // Rechnung für Upload erstellen
      Helper::afterbuy($_SESSION['AFTERBUY_ID'], $_SESSION['AFTERBUY_NR']);

      $this->re_id = $re_id;
      $_SESSION['re_id'] = $re_id;
      $_SESSION['adcell_netto'] = $_SESSION['wk_netto'];
      $_SESSION['adcell_bestellnummer'] = $_SESSION['bestellnummer'];

      // Anfragebestätigung an Kunde und Admin
      // Bei za_automatik und versch. Zahlungsarten keine Auftragsbestätigung, gleich Rechnung
      if (Helper::getData('za_automatik', 'n') == 'n' || (
          $zahlungsart !== 2 &&      // paypal
          $zahlungsart !== 5 &&      // rechnung
          $zahlungsart !== 7 &&      // sofort
          $zahlungsart !== 8 &&      // vrpay
          $zahlungsart !== 10 &&     // PaypalPlus
          $zahlungsart !== 11 &&     // amazon
          $zahlungsart !== 12 &&     // Twint
          $zahlungsart !== 13 &&     // easycredit
          $zahlungsart !== 14 &&     // Klarna
          $zahlungsart !== 15 &&     // Paydirekt
          $zahlungsart !== 17 &&     // Postfinance
          $zahlungsart !== 18 &&     // Paypal V2
          $zahlungsart !== 19))      // Mollie
      {
         $mail->sendAnfrage($_SESSION['email'], $re_id);
      }

      // Widerruf Dienstleistung
      $xy = '';

      // Widerruf bei Dienstleistung
      if ((int)$_SESSION['widerruf_wk'] == 4) {
         if (isset($_SESSION['widerruf_dl']) && $_SESSION['widerruf_dl'] == 'y') {
            $xy = $mail->sendWiderrufDl($_SESSION['email'], $re_id);
         }

         else {
            $xy = $mail->sendWiderrufDlNo($_SESSION['email'], $re_id);
         }
      }

      if ($_SESSION['bestellnummer'] !== '' && strlen($_SESSION['bestellnummer']) > 3) {
         $mail->lang = 'deu';
         $mail->sendAdmin($_SESSION['email'], $re_id, $_SESSION['user_msg']);

         // Rechnung für Upload erstellen
         Helper::afterbuy($_SESSION['AFTERBUY_ID'], $_SESSION['AFTERBUY_NR']);
      }

      $wk->cleanWk();

      // Klarna Zahlungsart zurücksetzten
      if ($zahlungsart == 14) {
         $_SESSION['zahlungsart'] = 0;

      }
      unset($_SESSION['user_msg']);
      unset($_SESSION['wk_check']);
      unset($_SESSION['gutschein_code']);
      unset($_SESSION['widerruf_wk']);
      unset($_SESSION['agb_check']);
      unset($_SESSION['widerruf_check']);
      unset($_SESSION['dhl_send_check']);

      $_SESSION['art_id'] = 0;
      $_SESSION['kat_id'] = 0;
      $_SESSION['back'] = '';
      $_SESSION['addr_err'] = false;
      $_SESSION['best_ok'] = 'ok';

      $_SESSION['msg_bestellnummer'] = $_SESSION['bestellnummer'];
      unset($_SESSION['bestellnummer']);
      // Für Box admin_msg im Template
      $_SESSION['admin_msgb']  =  $this->text->get('bestellt', 'bestellnr').' '.$_SESSION['msg_bestellnummer'];
      $_SESSION['admin_msgb'] .= '<br />'.$this->text->get('bestellt', 'email1').' '.$_SESSION['email'].' '.$this->text->get('bestellt', 'email2', 'lang');

      unset($_SESSION['zahlart_error']);
      unset($_SESSION['widerruf_dl']);
      unset($_SESSION['widerruf_down']);
      unset($_SESSION['klarna_order_id']);

      $_SESSION['wk_land']        = 0;
      $_SESSION['rechnung_land']  = 0;
      $_SESSION['lieferung_land'] = 0;

      // Userdaten vor Löschen für PDF-Lastschrift merken
      $userpdf = $_SESSION['user'];

      // Sofortkauf: Userdaten löschen
      if ($_SESSION['sofortkauf'] == 'y') {
         unset($_SESSION['user']);
         $this->user = array();
         $_SESSION['alter_check'] = false;
         $_SESSION['alter_ok'] = false;
      }

      unset($_SESSION['sofortkauf']);

      // PDF Lastschrift-Einzug ausgeben
      if ($_SESSION['zahlungsart'] == 3 && $this->firma['lastschrift_pdf_check'] == 'n') {
         $pdf = Control::getPdfLastschrift();
         $pdf->makePdf($re_id, $userpdf);
         exit;
      }

      // Bei registrierten Usern Redirect auf MeinKonto
      // Bei Zahlungsmodulen erfolgt Redirect an anderer Stelle -> z.B. paypal_ok von Paypal
      if ($_SESSION['zahlungsart'] != 2 &&
          $_SESSION['zahlungsart'] != 7 &&
          $_SESSION['zahlungsart'] != 8 &&
          $_SESSION['zahlungsart'] != 10 &&
          $_SESSION['zahlungsart'] != 15 && // CB: Paydirekt
          $_SESSION['zahlungsart'] != 18 &&
          $_SESSION['zahlungsart'] != 19 &&
          $_SESSION['zahlungsart'] != 111) {
      }

      // PaypalPlus
      else if ($_SESSION['zahlungsart'] == 10) {
         $ppp = Control::getPaypalPlus();
         $ppp_back = $ppp->pppPayment($re_id);
         echo json_encode(array('status' => 'ok', 'html' => $ppp_back));
         exit;
      }

      // Paypalv2
      else if ($_SESSION['zahlungsart'] == 18) {
         //get data and json
         $ppv2 = Control::getPaypalv2();
         $order_string = $ppv2->createOrder($re_id);
         echo json_encode(array('status' => 'ok', 'html' => $order_string));
         exit;
      }

      // Mollie
      else if ($_SESSION['zahlungsart'] == 19) {
         //get mollie and call Mollie Payment API
         $mollie = Control::getMollie();
         $response = $mollie->createPayment($re_id);
         exit;
      }

      else {
         $this->task = 'paypal_error';
      }
   }

   // Links generieren für Kategorien und Artikel ('artikel', $artikel->id, $artikel->cat_name.'/'.$art_name, $werte);
   public function DELgetLink($mode, $id = '', $name = '', $werte = '') {
      $name  = urlencode($name);
      $name  = str_replace(array('%2F', '%25', '+', '_', '/'), array('/', '-', '-', '-', '-', '-'), $name);
      $name  = preg_replace('#[^a-zA-Z0-9/_\-\%]#', '', $name);

      $werte = str_replace(['%2F', '%25', '+', '_'], array('/', '-', '-', '-', '-'), $werte);
      $werte = preg_replace('#[^a-zA-Z0-9/_\-\%]#', '', $werte);

      if ($mode == 'artikel') {
         $mode = '';
         $id = '/'.($this->selected_lang != 'deu' ? $this->selected_lang.'_' : '').$id;
      }

      else if ($mode == 'kategorie') {
         $mode    = '';
         $cat_len = 10;
         $cat_arr = explode('/', $name);
         $end     = count($cat_arr);
         $start   = 0;

         if (defined('CONF_CATLINKS')) {
            $cat_len = CONF_CATLINKS;
         }

         $start = $end - $cat_len;

         if ($start > 0 ) {
            $name = '';

            for ($i = $start; $i < $end; $i++) {
               $name .= '/'.$cat_arr[$i];
            }

            $name = ltrim($name, '/');
         }

         $id = '/k'.($this->selected_lang != 'deu' ? $this->selected_lang.'' : '').$id;
      }

      else {
         $mode = $mode != '' ? '/'.$mode : '';
      }

      $link = $name.($werte != '' ? '-'.$werte : '');
      return SHOP_URL_IDX.$mode.$id.'/'.$link;
   }

   public function DELgetWerte($m1, $w1, $m2, $w2) {
      $werte = '';

      if ($m1 != '' && $w1 != '') {
         $test   = urlencode($m1).'-'.urlencode($w1);
         $werte .= str_replace(array('%2F', '%25', ' ', '_'), array('/', '-'), $test);
      }

      if ($m2 != '' && $w2 != '') {
         $test   = urlencode($m2).'-'.urlencode($w2);
         $werte .= ($werte != '' ?  '-' : '').str_replace(array('%2F', '%25', ' ', '_'), array('/', '-'), $test);
      }

      return $werte;
   }

   // Anhang für URL generieren
   private function getAnhang($renew = false) {
      $anhang = array();
      $sql = '';

      if ($this->task == 'artikel' || $this->task == 'kategorie') {
         if ($this->task == 'artikel') {
            $anhang[0] = $_SESSION['art_id'];
            $sql = "SELECT i.name_".$this->selected_lang." AS web_name
                       FROM #__articles_info AS i
                    LEFT JOIN #__articles AS a
                       ON a.parent_id = i.id
                    WHERE a.id = ".$_SESSION['art_id'];
         }

         elseif ($this->task == 'kategorie') {
            $anhang[0] = $_SESSION['kat_id'];
            $sql = "SELECT name_".$this->selected_lang." AS web_name FROM #__categories WHERE id = ".$_SESSION['kat_id'];
         }

         if ($renew === true) {
            $this->db_extern->query($sql);
            $data = $this->db_extern->getObject();
            $_SESSION['web_name'] = $data->web_name;
         }

         $anhang[1] = $_SESSION['web_name'];
         return $anhang;
      }

      $anhang[0] = $this->task;
      $anhang[1] = '';

   }

   // Session bereinigen nach Bestellung
   private function clearSession() {
      unset($_SESSION['klarna_order_id']);
      $_SESSION['warenkorb'] = array();
      $this->warenkorb = array();
      $_SESSION['netto'] = 0.0;
      $_SESSION['steuer1'] = 0.0;
      $_SESSION['steuer2'] = 0.0;
      $_SESSION['steuer3'] = 0.0;
      $_SESSION['zahlungsart'] = 0;
      $_SESSION['bestellnummer'] = '';
      $this->bestellnummer = '';

      if ($this->user_id == 0) {
         $_SESSION['newsletter'] = 'n';
      }
   }

   // Prüfen, ob Login korrekt und Session-Parameter Kunde setzen
   private function checkLogin($mode = false) {
      $email = $this->postString('email');
      $pass  = $this->postString('pass');
      $data  = $this->db->querySingleObject("SELECT * FROM #__users WHERE email = '".$this->db->escape($email)."' OR (name = '".$this->db->escape($email)."' AND role < 9)");

      // Kunden-Login durch Admin-Bestellung
      if ($mode == true) {
         $email = $this->postString('admin_email');
         $pass  = $this->postString('admin_pass');
         $data1  = $this->db->querySingleObject("SELECT * FROM #__users WHERE email = name = '$email' AND role < 6");

         $admin_pw = (isset($data1->password) ? $data1->password : '');
         $data     = (isset($data1->password) ? $this->db->querySingleObject("SELECT * FROM #__users WHERE id = ".$this->postInt('admin_user')) : null);

         if ($data) {
            $data->password = $admin_pw;
         }

         else {
            $pass = '';
            $data = null;
         }
      }

      if ($data) {
         if ($data->gesperrt == 'n' && $data->password == md5($pass)) {
            if ($data->role > 7) {
               $this->user_id             = $data->id;
               $this->logged_in           = true;
               $_SESSION['user_id']       = $this->user_id;
               $_SESSION['logged_in']     = $this->logged_in;
               $_SESSION['user_name']     = $data->nachname;
               $_SESSION['email']         = $data->email;
               $_SESSION['lang']          = $data->lang;
               $_SESSION['schnellkauf']   = 'n';
               $_SESSION['rechnung_land'] = $data->staat;
               $_SESSION['wk_land']       = $data->lf_staat;

               unset($_SESSION['user']);
               $this->loginerror = false;
               $this->selected_lang = $data->lang;

               $sql = "UPDATE #__users SET last_login = '" . date('y-m-d H:i:s') . "' WHERE id = $this->user_id";
               $this->db->query($sql);
               return true;
            }

            else {
               return false;
            }
         }

         else if ($data->gesperrt == 'n' && $data->password == 'emailnewsletter') {
            header('Location: '.SHOP_URL_IDX.'/anmelden');
            exit;
         }

         else if ($data->gesperrt == 'y' && $data->password == md5($pass)) {
            $this->valid_user = true;
            $_SESSION['admin_msg'] = $this->text->get('login', 'freischalten');
         }
      }

      return false;
   }

   private function _checkLang($lang) {
      $langs = explode(';', $this->firma['langs']);

      if (in_array($lang, $langs)) {
         return $lang;
      }

      return 'deu';
   }

   private function addLast($article_id) {

      $last = $_SESSION['last_articles'];

      if (count($last) > 0) {
         for ($i = 0; $i < count($last); $i++) {
            // max. 10 Einträge
            if ($last[$i] == $article_id || $i > 10) {
               break;
            }

            if ($i == 0) {
               $_SESSION['last_articles'][0] = $article_id;
               $_SESSION['last_articles'][1] = $last[0];
            }

            else {
               $_SESSION['last_articles'][$i + 1] = $last[$i];
            }
         }
      }
      else {
         $_SESSION['last_articles'][0] = $article_id;
      }

   }

}
