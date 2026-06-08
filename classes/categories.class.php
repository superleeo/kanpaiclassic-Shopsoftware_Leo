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

class KANPAICLASSIC_categories extends KANPAICLASSIC_categoriesBase {
   public  $curr_cat        = 0;
   public  $markenfilter    = 'n';
   public  $alter_check     = 'n';
   private $id_list        = [];
   public  $titel_tag      = '';
   private $kat_linien     = ' no_kat_lines';
   private $menu_count     = 0;
   private $menus          = 0;                    // Anzahl Menüs
   private $active_maincat = 0;
   private $is_horizontal  = false;
   private $is_mixer       = false;

   public function __construct() {
      parent::__construct();
      if ($this->params->firma['linien_kat'] == 'y') {
         $this->kat_linien = ' kat_lines';
      }

      if ($this->params->firma['kategorien_links'] != 'd') {
         $this->getTree();
      }

      // Unterkategorien als .js-Datei erstellen bei horizontalem Menü
      $lang        = $this->params->selected_lang;

      if (!file_exists(SHOP_PATH.'/tmp/cat_cache_'.$lang.'.js')) {
         $this->makeCatCache();
      }

   }

   // Kategriebaum unterhalb Level 0 ausgeben (Responsive-Designs)
   public function loadKategorie($cat_id) {
      return $this->renderTreeList($cat_id);
   }

   // Kategorien als Baum anzeigen
   public function renderTree($cat_id = 0, $is_horizontal = false, $removeInactive = false) {



      $this->is_horizontal = $is_horizontal;
      $cat_path       = '';
      $stack          = [];
      $this->curr_cat = $cat_id;    // aktuelle (gewählte) Kategorie
      // Alle aktiven Parents auslesen
      $this->setCatlist($stack);
      $html           = '';

      $id_list = [];

      for ($i = 0; $i < count($stack); $i++) {
         $id_list[] = $stack[$i]['id'];
      }

      $this->id_list = $id_list;

      // Array für Kategorie-Passwörter
      $cat_pass    = isset($_SESSION['cat_pass']) ? $_SESSION['cat_pass'] : [];
      $this->menus = 0;    // Zähler für Anzahl Menüs (Berechnung Abstand nach unten)

      // Letztes Element holen (Hauptkategorie)
      if (count($stack)) {
         $cat_data = array_pop($stack);
      }

      $html = '<ul class="level0">'.CR;

      // Alle Kategorien Level 0 durchgehen
      foreach ($this->categories as $level0) {
          if ($this->params->multishop || $removeInactive) {
              $aktiv = $this->db->querySingleValue("SELECT active FROM #__categories WHERE id = ".$level0[0]['id']);

            // Inaktive Hauptkategorien überspringen
            if ($aktiv != 'y') {
               continue;
            }
         }

         // Keine Kategorie gewählt, Startseite -> nur Hauptmenüs anzeigen
         if ($this->curr_cat == 0 || $is_horizontal) {
            $this->menus++;

            $class  = '';
            $childs = '';
            $active = '';

            if($level0[0]['childs']) {
               $childs = ' childs';
               $class  = ' rechts';
            }

            $user_pass = isset($cat_pass[$level0[0]['id']]) ? $cat_pass[$level0[0]['id']] : '';
            $html .= $this->_getLi(
                                    $level0[0]['name'],
                                    $this->params->getLink('kategorie', $level0[0]['id'], $level0[0]['name']),
                                    $level0[0]['id'],
                                    0,
                                    $level0[0]['childs'],

                                    $level0[0]['cat_pass'],
                                    $user_pass,
                                    $childs,
                                    ($this->is_horizontal ? 'horiz_kat horiz_kat_c' : 'vertikal_kat haupt_kat_c').' text_max'.$childs,
                                    $class,
                                    $level0[0]['filter_active']
                                  );
            $html .= "</li>";
            $cat_path = $level0[0]['name'];
         }

         // Hauptmenü und/oder Unternenü davon nicht gewählt
         else if (isset($cat_data) && $cat_data['id'] != $level0[0]['id']) {
            $this->menus++;

            // Wenn Untermenü vorhanden, Pfeil rechts
            $class  = '';
            $childs = '';
            $active = '';

            if ($level0[0]['childs']) {
               $childs = ' childs';
               $class  = ' rechts';
            }

            $user_pass = isset($cat_pass[$level0[0]['id']]) ? $cat_pass[$level0[0]['id']] : '';
            $html .= $this->_getLi(
                                    $level0[0]['name'],
                                    $this->params->getLink('kategorie', $level0[0]['id'], $level0[0]['name']),
                                    $level0[0]['id'],
                                    0,
                                    $level0[0]['childs'],

                                    $level0[0]['cat_pass'],
                                    $user_pass,
                                    $childs,
                                    ($this->is_horizontal ? 'horiz_kat horiz_kat_C' : 'vertikal_kat haupt_kat_c').'  text_max'.$childs.$active,
                                    $class,
                                    $level0[0]['filter_active']
                                  );
            $html .= "</li>";
         }

         // Hauptmenü mit aktivem Untermenü
         else {
            // keine oder keine aktiven Unterkategorien unterhalb Level 0, Nur Level 0 anzeigen
            if (!count($stack)) {
               $this->menus++;

               $class  = '';
               $childs = '';
               $active = '';

               if($level0[0]['childs']) {
                  $childs = ' childs';
                  $class  = ' runter';
               }

               if ((int)$level0[0]['id'] == $this->curr_cat) {
                  $active = ' current';
               }

               // Hauptmenü anzeigen
               $user_pass = isset($cat_pass[$level0[0]['id']]) ? $cat_pass[$level0[0]['id']] : '';

               $html .= $this->_getLi($level0[0]['name'],
                                      $this->params->getLink('kategorie', $level0[0]['id'], $level0[0]['name']),
                                      $level0[0]['id'],
                                      0,
                                      $level0[0]['childs'],

                                      $level0[0]['cat_pass'],
                                      $user_pass,
                                      'selected'.$childs,
                                      ($this->is_horizontal ? 'horiz_kat horiz_kat_c' : 'vertikal_kat haupt_kat_c').' text_max selected'.$childs.$active,
                                      $class,
                                      $level0[0]['filter_active']
                                      );

               $cat_path = $level0[0]['name'];

               // Unterkategorien vorhanden
               if ($level0[0]['childs']) {
                  if (!$this->is_horizontal) {
                     $html .= '<ul class="level1">'.CR;
                  }

                  // Untermenüs Level 1 durchgehen
                  $first = true;

                  foreach ($level0 as $level1) {
                     // Hauptkategorie (index 0) überspringen
                     if ($first) {
                        $first = false;
                        continue;
                     }

                     $this->menus++;

                     // Bei weiteren Unterkategorien Pfeil rechts
                     $class  = '';
                     $childs = '';
                     $active = '';

                     if($level1['childs']){
                        $childs = ' childs';
                        $class  = ' rechts';
                     }

                     if ((int)$level1['id'] == $this->curr_cat) {
                        $active = ' current';
                     }

                     $user_pass = isset($cat_pass[$level1['id']]) ? $cat_pass[$level1['id']] : '';

                     if (!$this->is_horizontal) {
                        $html .= $this->_getLi(
                                                $level1['name'],
                                                $this->params->getLink('kategorie', $level1['id'], $cat_path.'/'.$level1['name']),
                                                $level1['id'],
                                                1,
                                                $level1['childs'],

                                                $level1['cat_pass'],
                                                $user_pass,
                                                'selected'.$childs,
                                                'unter_kat unter_kat_c text_gross selected'.$childs.$active,
                                                $class,
                                                $level1['filter_active']
                                              );
                        $html .= "</li>";
                     }
                  } // foreach Level 1

                  if (!$this->is_horizontal) {
                     $html .= '</ul>'.CR;
                  }
               }

               if (!$this->is_horizontal) {
                  $html .= "</li>";
               }
            } // Keine Untermenüs aktiv

            // Unterkategorien aktiv
            else {
               $this->menus++;

               $class  = '';
               $childs = '';

               if($level0[0]['childs']){
                  $childs = ' childs';
                  $class  = ' runter';
               }

               // Hauptmenü anzeigen
               $user_pass = isset($cat_pass[$level0[0]['id']]) ? $cat_pass[$level0[0]['id']] : '';

               $html .= $this->_getLi(
                                       $level0[0]['name'],
                                       $this->params->getLink('kategorie', $level0[0]['id'], $level0[0]['name']),
                                       $level0[0]['id'],
                                       0,
                                       $level0[0]['childs'],

                                       $level0[0]['cat_pass'],
                                       $user_pass,
                                       'active'.$childs,
                                       'vertikal_kat haupt_kat_c text_max active'.$childs,
                                       $class,
                                       $level0[0]['filter_active']
                                      );

               $this->active_maincat = $level0[0]['id'];
               $cat_path .= $level0[0]['name'];

               // Level 1 holen
               $cat_data_l1 = array_pop($stack);

               // Level1 anzeigen
               if (!$this->is_horizontal) {
                  $html .= '<ul class="level1">'.CR;
               }

               $first = true;

               foreach ($level0 as $level1) {
                  if ($first) {
                     $first = false;
                     continue;
                  }

                  // Level1-Kategorie nicht aktiv
                  if (($cat_data_l1['id'] != $level1['id'])) {
                     $this->menus++;

                     $class  = '';
                     $childs = '';
                     $active = '';

                     if($level1['childs']){
                        $class = ' rechts';
                        $childs = ' childs';
                     }

                     if ((int)$level1['id'] == $this->curr_cat) {
                        $active = ' 1 current';
                     }

                     // Level 1 anzeigen
                     $user_pass = isset($cat_pass[$level1['id']]) ? $cat_pass[$level1['id']] : '';

                     if (!$this->is_horizontal) {
                        $html .= $this->_getLi(
                                                $level1['name'],
                                                $this->params->getLink('kategorie', $level1['id'], $cat_path.'/'.$level1['name']),
                                                $level1['id'],
                                                1,
                                                $level1['childs'],

                                                $level1['cat_pass'],
                                                $user_pass,
                                                $childs,
                                                'unter_kat unter_kat_c text_gross'.$childs.$active,
                                                $class,
                                                $level1['filter_active']
                                               );
                        $html .= "</li>";
                     }
                  }

                  // Level1 gewählt
                  else if ($level1['id'] == $this->curr_cat) {
                     $this->menus++;

                     $class = '';
                     $childs = '';
                     $active = '';

                     if($level1['childs']){
                        $childs = ' childs';
                        $class = ' runter';
                     }

                     if ((int)$level1['id'] == $this->curr_cat) {
                        $active = ' 2 current';
                     }

                     $user_pass = isset($cat_pass[$level1['id']]) ? $cat_pass[$level1['id']] : '';

                     if (!$this->is_horizontal) {
                        $html .= $this->_getLi(
                                                $level1['name'],
                                                $this->params->getLink('kategorie', $level1['id'], $cat_path.'/'.$level1['name']),
                                                $level1['id'],
                                                1,
                                                $level1['childs'],

                                                $level1['cat_pass'],
                                                $user_pass,
                                                'selected'.$childs,
                                                'unter_kat unter_kat_c text_gross selected'.$childs.$active,
                                                $class,
                                                $level1['filter_active']
                                               );
                     }

                     $html .= $this->_getSubList($stack, $level1['id'], 2, $cat_path.'/'.$level1['name']);

                     if (!$this->is_horizontal) {
                        $html .= "</li>";
                     }
                  }

                  // Unterkategorien aktiv
                  else {
                     if ($level1['childs']) {
                        $this->menus++;
                        $class = ' runter';

                        // Level 1 anzeigen
                        $user_pass = isset($cat_pass[$level1['id']]) ? $cat_pass[$level1['id']] : '';

                        $html .= $this->_getLi($level1['name'],
                                               $this->params->getLink('kategorie', $level1['id'] , $cat_path.'/'.$level1['name']),
                                               $level1['id'],
                                               1,
                                               $level1['childs'],
                                               $level1['cat_pass'],
                                               $user_pass,
                                               'active childs',
                                               'unter_kat haupt_kat_c text_gross active childs',
                                               $class,
                                               $level1['filter_active']);

                        // weitere Unterkategorien anzeigen
                        $cat_path .= '/'.$level1['name'];

                        $html .= $this->_getSubList($stack, $level1['id'], 2, $cat_path);

                        if (!$this->is_horizontal) {
                           $html .= "</li>";
                        }
                     }
                  }

                  $html .= "</li>";
               }

               if (!$this->is_horizontal) {
                  $html .= '</ul>'.CR;
               }
            }
         } // Hauptmenü mit aktiven Unterkategorien
      }

      $html .= "<li class='clear'><li>".CR;
      $html .= "</ul>".CR;

      // Abstände nach unten immer auf derselben Höhe wie Artikelliste
      $pixels = CONF_MENU_HOEHE;
      $this->params->promo_artikel2 = 0;

      if (!($this->params->task == 'artikel' or $this->params->task == 'inwarenkorb')) {
         if ($this->params->task == 'kategorie' or $this->params->task == '') {
            $pixels = CONF_ARTIKEL_HOEHE - (($this->menus * CONF_MENU_HOEHE) % CONF_ARTIKEL_HOEHE);
         }

         $anz_kats = $this->menus * CONF_ARTIKEL_HOEHE / CONF_ARTIKELZEILE;
         $this->params->anz_kats = (int)(floor($anz_kats) + ($anz_kats - floor($anz_kats) > 0) ? 1 : 0);
         $this->params->promo_artikel = (int)($this->params->art_anzahl / CONF_ARTIKELZEILE - ($this->menus * CONF_MENU_HOEHE) / CONF_ARTIKEL_HOEHE);
      }

      // Promo-Artikel
      else if (defined('CONF_SPALTE_RECHTS') && $this->params->task == 'artikel') {
         if ($this->menus * CONF_MENU_HOEHE < CONF_DETAIL_HOEHE) {
            $pixels = CONF_DETAIL_HOEHE - (($this->menus * CONF_MENU_HOEHE) % CONF_ARTIKEL_HOEHE);
         }

         $this->params->promo_artikel2 = (int)($this->params->art_anzahl / CONF_ARTIKELZEILE);
      }

      $html .= "<div class='abstand_kategorien' style='position:relative; height:${pixels}px;'></div>";

      return $html;
}

   // Kategorien als Liste (alle geöffnet) / Responsive Desktop/Submenü
   private function renderTreeList($cat_id) {
      // Bei Responsive-Menü oder linkes Menü (< Desktop) altes Menü zurück geben
      if ($this->params->postString('mode') == 'responsive' || $this->params->postString('mode') == 'left_cats') {
         return ($this->renderTree($cat_id, false));
      }

      $html                = '<div class="kategorie_sub_inner">';
      $this->is_horizontal = true;
      $stack               = [];
      $cat_path            = '';
      $this->curr_cat      = $cat_id;    // aktuelle (gewählte) Kategorie

      $this->setCatlist($stack);
      $lang                = $this->params->selected_lang;
      $cat_pass            = isset($_SESSION['cat_pass']) ? $_SESSION['cat_pass'] : [];

      // Aktiven Verzeichnisbaum finden
      $found_cats = [];

      for ($i = 0; $i < 10; $i++) {
         $found_cats[$i] = (['id' => 0, 'parent_id' => 0]);
      }

      // Daten gewählte Kategorie auslesen
      $data = $this->db_extern->querySingleObject("SELECT id, parent_id, level, ordered, childs, name_$lang AS cat_name, desc_$lang  AS cat_text, cat_pass, hide_articles, show_text  FROM #__categories WHERE id = $cat_id");

      if (!$data) {
         return '';
      }

      $level         = (int)$data->level;
      $search_parent = $data->parent_id;
      $max_level     = (int)$data->level;
      $cat_name      = $data->cat_name;

      if ($data->hide_articles == 'y') {
         $this->params->hide_articles = true;
      }

      $img_data  = $this->loadImages($data->id, $lang);
      $img_arr   = $img_data->images;
      $options   = json_decode($img_data->options);
      $mode      = $options->mode;

      $cat_html = '';
      $images   = false;

      foreach ($img_arr as $img) {
         if ($img->image != '') {
            $images = true;
            break;
         }
      }

      // Kategorie-Beschreibung
      if ($data->show_text == 'y' && $data->cat_text != '' && $data->cat_text != '[TRENNER]' || $data->show_text == 'y'  && $images) {
         include TEMPLATE_PATH.'/categories.tpl.php';
      }

      $this->params->cat_text = $cat_html;

      // Daten merken
      $found_cats[$level] = ['id' => $data->id, 'parent_id' => $data->parent_id];

      // Alle Parents bis Level0 durchgehen.(rückwärts)
      for ($l = $max_level - 1; $l >= 0; $l--) {
         $data           = $this->db_extern->querySingleObject("SELECT id, parent_id FROM #__categories WHERE id = ".$search_parent);
         $found_cats[$l] = ['id' => $data->id, 'parent_id' => $data->parent_id];
         $search_parent  = $data->parent_id;
      }

      // Evtl. foreach, falls Kats inaktiv
      if (!is_object($data)) {
         return '';
      }

      // Ausgabe mit Level 1 beginnen
      $data_l1 = null;

      foreach ($this->categories as $index => $cat_arr) {
         if ($this->categories[$index][0]['id'] == $found_cats[0]['id']) {
            // gefunden
            // Teilbaum aus categories
            $data_l1 = $this->categories[$index];
            break;
         }
      }

      // Level 1 ausgeben / hide/show nicht verwendet
      if (is_array($data_l1) && count($data_l1) > 1) {
         $z = 0;
         $html .= '<div class="cat_line">';

         foreach ($data_l1 as $index => $c) {
            $cat_path = $cat_name;

            // Hauptkategorie überspringen
            if ($index == 0) {
               continue;
            }

            // Anzahl Blöcke / Zeile
            if ($z == 3) {
               $html .= '</div><div class="cat_line">';
               $z = 0;
            }

            $z++;

            $cat = (object)$c;

            if ($this->params->firma['linien_kat'] == 'y') {
               $html .= '<div class="cat_block_lines">';
            }

            else {
               $html .= '<div class="cat_block">';
            }

            $html     .= '<ul class="level1">';
            $user_pass = isset($cat_pass[$cat->id]) ? $cat_pass[$cat->id] : '';
            $active    = '';

            if ((int)$found_cats[(int)$cat->level]['id'] == (int)$cat->id) {
               if ((int)$cat->level == $max_level) {
                  $active = ' selected';
               }

               else {
                  $active = ' active';
               }
            }

            $html .= $this->_getLi(
                                    $cat->name,
                                    $this->params->getLink('kategorie', $cat->id, $cat_path.'/'.$cat->name),
                                    $cat->id,
                                    1,
                                    $cat->childs,

                                    $cat->cat_pass,
                                    $user_pass,
                                    '',
                                    'haupt_kat_c sub_kat text_gross'.$active,
                                    '',
                                    $cat->filter_active
                                   );
            $html .= '</li></ul>';
            $cat_path .= '/'.$cat->name;


            // Level 2 ausgeben, falls vorhanden / hide/show ist abhängig vom nächsten Level !!!
            if ($cat->childs > 0 && isset($this->childs[$cat->id])) {
               $data_l2 = $this->childs[$cat->id];

               // Subkategorie vorhanden?
               if (count($data_l2) > 0) {
                  $html .= '<ul>';

                  // Alle Subkategorien durchgehen
                  foreach ($data_l2 as $index => $c) {
                     $cat_l2 = (object)$c;
                     $user_pass = isset($cat_pass[$cat_l2->id]) ? $cat_pass[$cat_l2->id] : '';

                     $status = '';
                     $active = '';
                     $childs  = '';

                     // Kategorie im Pfad ?
                     if ((int)$found_cats[(int)$cat_l2->level]['id'] == (int)$cat_l2->id) {
                        if ((int)$cat_l2->level == $max_level) {
                           $active = ' selected';
                        }
                        else {
                           $active = ' active';
                        }

                        if ($cat_l2->childs > 0) {
                           $childs = 'childs';
                        }
                     }

                     else if ($cat_l2->childs > 0) {
                        $childs = 'childs';
                     }


                     $html .= $this->_getLi(
                                             $cat_l2->name,
                                             $this->params->getLink('kategorie', $cat_l2->id, $cat_path.'/'.$cat_l2->name),
                                             $cat_l2->id,
                                             2,
                                             $cat_l2->childs,

                                             $cat_l2->cat_pass,
                                             $user_pass,
                                             $childs,
                                             'unter_kat_c sub_kat text_gross '.$childs.$status,
                                             '',
                                             $cat_l2->filter_active
                                            );

                     // Falls childs vorhanden, diese (rekursiv) ausgeben
                     if ($cat_l2->childs > 0) {
                        $html .= $this->_listSub($cat_l2->id, $found_cats, $max_level, $cat_path.'/'.$cat_l2->name);
                     }

                     $html .= "</li>";
                  }

                  $html .= '</ul>';
               }
            }
            $html .= '</div>';
         }

         $html .= '</div>';
         $html .= '</div>';
      }

      return $html;
   }

   public function renderTreeSelect($cat_id) {
      $lang = $this->params->selected_lang;
      $html       = '<div id="cat_title_select">';
      $html      .= '   <div id="cat_title" class="text_max horiz_kat_no_over">'.$this->text->get('menu', 'kategorien').':&nbsp;</div>';
      // Keine Kategorie gewählt, nur Hauptkategorien anzeigen
      if ($cat_id == 0) {
         $html .= $this->_renderTreeSelectSub(0, 0, true);
         $html .= '<div id="cat_spinner" style="display:none;"><span class="fas fa-spinner fa-spin"></span></div>';

         return $html;
      }



      // Gesuchte Kategorie
      $category = $this->db_extern->querySingleObject("SELECT id, parent_id, childs, level, desc_$lang AS cat_text, hide_articles, show_text FROM #__categories WHERE id = $cat_id");
      $category->more_cats = false;

      // Kategorie vorhanden
      if (!$category) {
         $html = $this->_renderTreeSelectSub(0, 0, true);

         return $html.'<div class="clear"></div></div>';
      }

      $cat_arr    = [];
      $cat_arr[]  = $category;
      $level      = (int)$category->level;
      $childs     = (int)$category->childs;
      $cat_html = '';

      $img_data = $this->loadImages($category->id, $lang);
      $img_arr  = $img_data->images;
      $cat_html = '';
      $images   = false;
      $options  = json_decode($img_data->options);
      $mode     = $options->mode;
      $this->params->cat_mode = $mode;

      $cat_html = '';

      if ($category->show_text == 'y') {
         // Bilder vorhanden?
         foreach ($img_arr as $img) {
            if (isset($img->image) && $img->image != '') {
               $images = true;
               break;
            }
         }

         if ($category->cat_text != '' && $category->cat_text != '[TRENNER]' || $images) {
            $data = $category;
            include TEMPLATE_PATH.'/categories.tpl.php';
         }

         $this->params->cat_text = $cat_html;
      }

      if ($childs > 0) {
         $this->params->select_has_childs = true;
      }

      for ($i = 0; $i < $level; $i++) {

          if($removeInactive){
              $extrasql = " active = 'y' and ";
          }

          $data     = $this->db_extern->querySingleObject("SELECT id, parent_id, childs, level FROM #__categories WHERE id = ".$cat_arr[$i]->parent_id);
         $data->more_cats = false;
         $cat_arr[]= $data;
      }

      $cat_arr = array_reverse($cat_arr);

      if ($childs > 0) {
          $data = $this->db_extern->querySingleObject("SELECT id, parent_id, childs, level FROM #__categories WHERE parent_id = $category->id");
            $data->more_cats = true;
            $cat_arr[]= $data;
      }

      for ($i = 0; $i < count($cat_arr); $i++) {
         // Hauptkategorie
         if ($i == 0) {
            $html .= $this->_renderTreeSelectSub(0, $cat_arr[$i]->id,  $cat_arr[$i]->more_cats);
         }

         else {
            if ($cat_arr[$i]->more_cats) {
               $html .= $this->_renderTreeSelectSub($cat_arr[$i]->parent_id, 0, $cat_arr[$i]->more_cats);
            }

            else {
               $html .= $this->_renderTreeSelectSub($cat_arr[$i]->parent_id, $cat_arr[$i]->id, $cat_arr[$i]->more_cats);
            }
         }
      }

      if ($category->hide_articles == 'y') {
         $this->params->hide_articles = true;
         $html .= '<div id="cat_spinner" style="display:none;"><span class="fas fa-spinner fa-spin"></span></div>';

         return $html.'<div class="clear"></div></div>';
      }

      $cats_arr   = [];
      $cats_arr[] = $cat_id;

      if ($childs > 0) {
         $this->_setCatlistSelect($cat_id, $cats_arr);
      }

      $this->params->cat_list = implode(',', $cats_arr);
      $html .= '<div id="cat_spinner" style="display:none;"><span class="fas fa-spinner fa-spin"></span></div>';

      return $html.'<div class="clear"></div></div>';
   }

   private function _renderTreeSelectSub($parent_id, $search_id, $more_cats) {
      $html = '';
      $lang = $this->params->selected_lang;

      $data = $this->db_extern->queryAllObjects("SELECT id, parent_id, childs, name_$lang AS name, mixer_check FROM #__categories WHERE parent_id = $parent_id ORDER BY ordered, id");

      if ($data) {
         $html .= '<div class="cat_select">';
         $html .= '   <span class="select_wrapper">';
         $html .= '      <span class="selectbox">';
         $html .= '         <select class="text_max fliesstext" name="categorie" onchange="catSelectChanged(this);">';

         if ($more_cats) {
            $html .= '            <option class="text_max fliesstext" value="0" selected="selected" style="text-align:center;">'.$this->text->get('bitte', 'waehlen').'</option>';
         }

         foreach($data as $d) {
            if ((int)$d->id == $search_id) {
               $html .= '            <option class="text_max fliesstext" value="'.$d->id.'" selected="selected" data-childs="'.$d->childs.'">'.$d->name.'</option>';
            }

            else {
               $html .= '            <option class="text_max fliesstext" value="'.$d->id.'" data-childs="'.$d->childs.'">'.$d->name.'</option>';
            }
         }

         $html .= '         <select>';
         $html .= '      </span>';
         $html .= '   </span>';
         $html .= '</div>';
      }

      return $html;
   }

   private function _listSub($id, $found_cats, $max_level, $cat_path) {
      $data = $this->childs[$id];

      if (count($data) > 0) {
         $html  = '<ul>';
         foreach ($data as $index => $c) {
            $cat = (object)$c;
            $level = 'level'.$cat->level;
            $user_pass = isset($cat_pass[$cat->id]) ? $cat_pass[$cat->id] : '';

            $active = '';
            $childs = '';
            // Kategorie im Pfad ?
            if ((int)$found_cats[(int)$cat->level]['id'] == (int)$cat->id) {
               if ((int)$cat->level == $max_level) {
                  $active = ' selected';
               }
               else {
                  $active = ' active';
               }
            }

            if ($cat->childs > 0) {
               $childs = 'childs';
            }

            $html .= $this->_getLi(
                                    $cat->name,
                                    $this->params->getLink('kategorie', $cat->id, $cat_path.'/'.$cat->name),
                                    $cat->id,
                                    $cat->level,
                                    $cat->childs,

                                    $cat->cat_pass,
                                    $user_pass,
                                    $childs.$active,
                                    'unter_kat_c sub_kat text_gross '.$childs.$active,
                                    '',
                                    $cat->filter_active
                                   );

            if ($cat->childs > 0) {
               $html .= $this->_listSub($cat->id, $found_cats, $max_level, $cat_path.'/'.$cat->name);
            }

            $html .= "</li>";
         }

         $html .= '</ul>';
      }

      return $html;
   }

   // Unterkategorien > Level 1 anzeigen
   private function _getSubList(&$stack, $parent, $level, $cat_path) {
      // $this->is_horizontal = false;
      $html   = '';
      $childs = null;

      // Weiter Unterkategorien vorhanden
      if (count($stack)){
         $cat_data = array_pop($stack);

         if (isset($this->childs[$cat_data['parent']])) {
            $childs = $this->childs[$cat_data['parent']];
         }

         // 17.08.2017
         else {
            $childs = $this->childs[$parent];
         }
      }

      // Letzte Unterkategorie
      else if (isset($this->childs[$parent])) {
         $childs = $this->childs[$parent];
      }

      else {
         return '';
      }

      $cat_pass = isset($_SESSION['cat_pass']) ? $_SESSION['cat_pass'] : [];
      $level_list = $level;

      // Bei Multishop ist Hauptkategorie Menü-Eintrag -> entsprechend korrigieren
      if ($this->params->firma['multishop'] == 'y') {
         $level_list--;
      }

      $unter_kat = ' unter_kat';

      // Nur bei Multishop
      if ($level_list < 1) {
         $unter_kat = ' vertikal_kat';
      }

      // keine weitere Unterkategorien
      if (count($childs) < 1) {
         return '';
      }

      if (!$this->is_horizontal) {
         $html .= "<ul class='level$level_list'>";
      }

      // Unterkategorien durchgehen
      foreach ($childs as $level_x) {
         // Keine Unterkategorien
         if (!$level_x['childs']) {
            $active = '';

            if ($level_x['id'] == $this->curr_cat) {
               $active = ' current';
            }

            $this->menus++;
            $user_pass = isset($cat_pass[$level_x['id']]) ? $cat_pass[$level_x['id']] : '';

            if (!$this->is_horizontal) {
               $html .= $this->_getLi(
                                       $level_x['name'],
                                       $this->params->getLink('kategorie', $level_x['id'], $cat_path.'/'.$level_x['name']),
                                       $level_x['id'],
                                       $level_list,
                                       $level_x['childs'],

                                       $level_x['cat_pass'],
                                       $user_pass,
                                       '',
                                       $unter_kat.'_c '.$unter_kat.' text_gross'.$active,
                                      '',
                                       $level_x['filter_active']
                                      );
               $html .= "</li>";
            }
         }

         // Unterkategorien vorhanden
         elseif (in_array($level_x['id'], $this->id_list) || $level_x['id'] == $this->curr_cat) {
            $this->menus++;
            $class = '';
            $active = '';

            if($level_x['childs']) {
               $class = ' runter';
               $active = ' active';
            }

            if ($level_x['id'] == $this->curr_cat) {
               $active = ' current';
            }

            if ($level_x['id'] == $this->curr_cat) {
               $active = ' current';
            }

            $user_pass = isset($cat_pass[$level_x['id']]) ? $cat_pass[$level_x['id']] : '';

            if (!$this->is_horizontal) {
               $html .= $this->_getLi(
                                       $level_x['name'],
                                       $this->params->getLink('kategorie', $level_x['id'], $cat_path.'/'.$level_x['name']),
                                       $level_x['id'],
                                       $level_list,
                                       $level_x['childs'],

                                       $level_x['cat_pass'],
                                       $user_pass,
                                       '',
                                       $unter_kat.'_c '.$unter_kat.' text_gross'.$active,
                                       $class,
                                       $level_x['filter_active']
                                      );
            }

            // Rekursion
            if ((int)$level_x['id'] == $this->curr_cat || count($stack) > 0) {
               $cat_path .= '/'.$level_x['name'];
               $html .= $this->_getSubList($stack, $level_x['id'], $level + 1, $cat_path);
            }

            if (!$this->is_horizontal) {
              $html .= "</li>";
            }
         }

         // Unterkategorie vorhanden, nicht aktiv
         else {
            $class = '';

            if($level_x['childs']) {
               $class = ' rechts';
            }

            $user_pass = isset($cat_pass[$level_x['id']]) ? $cat_pass[$level_x['id']] : '';

            if (!$this->is_horizontal) {
               $html .= $this->_getLi(
                                       $level_x['name'],
                                       $this->params->getLink('kategorie', $level_x['id'], $cat_path.'/'.$level_x['name']),
                                       $level_x['id'],
                                       $level_list,
                                       $level_x['childs'],

                                       $level_x['cat_pass'],
                                       $user_pass,
                                       '',
                                       'unter_kat unter_kat_c text_gross',
                                       $class,
                                       $level_x['filter_active']
                                      );
               $html .= "</li>";
            }
         }
      }

      if (!$this->is_horizontal) {
         $html .= "</ul>";
      }

      return $html;
   }

   // Array aktiver parents erstellen. Level 0 ist letztes Element
   // und Liste aktiver Kategorien erstellen für params
   // Artikel-Details
   private function setCatlist(&$stack) {
      //$this->is_horizontal = false;

      // keine Kategorie gewählt
      if ($this->curr_cat == 0 && $this->params->firma['multishop'] == 'n') {
         return;
      }

      if ($this->curr_cat == 0 && $this->params->firma['multishop'] == 'y') {
         return;
      }

      // Aktuell Kategorie aus DB lesen
      $lang = $this->params->selected_lang;
      $data = $this->db_extern->querySingleObject("SELECT id, parent_id, level, ordered, childs, name_$lang AS cat_name, desc_$lang  AS cat_text, cat_pass, hide_articles, show_text FROM #__categories WHERE id = $this->curr_cat");

      if (!$data) {
         return false;
      }

      $kat_id      = (int)$data->id;
      $parent      = (int)$data->parent_id;
      $level       = (int)$data->level;
      $level_found = $level;
      $ordered     = (int)$data->ordered;
      $childs      = (int)$data->childs;
      $this->params->cat_name = $data->cat_name;

      if ($data->hide_articles == 'y') {
         $this->params->hide_articles = true;
      }

      $img_data = $this->loadImages($data->id, $lang);
      $img_arr  = $img_data->images;
      $cat_html = '';
      $images   = false;
      $options  = json_decode($img_data->options);
      $mode     = $options->mode;
      $this->params->cat_mode = $mode;

      // Bilder vorhanden?
      foreach ($img_arr as $img) {
         if (isset($img->image) && $img->image != '') {
            $images = true;
            break;
         }
      }

      if ($data->show_text == 'y' && ($data->cat_text != '' && $data->cat_text != '[TRENNER]' || $images)) {
         $cat_html = '';

         include TEMPLATE_PATH.'/categories.tpl.php';
      }

      $this->params->cat_text = $cat_html;

      // Aus Childs lesen (Level > 1)
      while ($level > 1) {
         $stack[] = ['id' => $kat_id, 'parent' => $parent, 'childs' => $childs];
         $found   = false;

         foreach ($this->childs as $k => $v) {
            for ($i = 1; $i <= count($v); $i++) {
               if ($v[$i]['id'] == $parent) {
                  $kat_id   = (int)$v[$i]['id'];
                  $parent   = (int)$v[$i]['parent_id'];
                  $ordered  = (int)$v[$i]['ordered'];
                  $cat_pass = $v[$i]['cat_pass'];
                  $found    = true;
                  break;
               }

               if ($found) {
                  break;
               }
            }

            if (!$found) {
            }
         }

         $level--;
      }

      // Level 1 lesen
      if ($level == 1) {
         foreach ($this->categories as $level0) {
            $first = true;

            foreach ($level0 as $level1) {
               if($first) {
                  $first = false;
                  continue;
               }

               if  (($level_found == 1 and $kat_id == $level1['id']) or ($level_found > 1 and $level1['id'] == $parent)) {
                  $parent = $level1['parent_id'];
                  $stack[] = ['id' => $level1['id'], 'parent' => $parent, 'childs' => $level1['childs']];
                  $level1_found = $level1;
                  break(2);
               }
            }
         }
      }

      // Level 0 lesen
      if ($this->categories) {
         foreach ($this->categories as $level0) {
            if (($level_found == 0 and $kat_id == $level0[0]['id']) or ($level_found > 0 and $level0[0]['id'] == $parent)) {
               $stack[]      = ['id' => $level0[0]['id'], 'parent' => $level0[0]['parent_id'], 'childs' => $level0[0]['childs']];
               $level0_found = $level0;
               break;
            }
         }

         $catlist = (isset($stack[0]['id']) ? $stack[0]['id'] : '');

         // Unterkategorien, die berücksichtigt werden müssen
         if (isset($stack[0]['childs'])) {
            if ($level_found == 0) {
               $first = true;
               foreach ($level0_found as $categorie) {
                  if ($first) {
                     $first = false;
                     continue;
                  }

                  $catlist .= ",".$categorie['id'];

                  if ($categorie['childs']) {
                     $catlist .= $this->getSubId($categorie['id']);
                  }
               }
            }

            elseif ($level_found == 1) {
               if ($level1_found['childs']) {
                  $catlist .= $this->getSubId($level1_found['id']);
               }
            }

            else {
               $catlist .= $this->getSubId($stack[0]['id']);
            }
         }

         $this->params->cat_list = $catlist;
      }

      return;
   }

   private function _setCatlistSelect($cat_id, &$cats_arr) {
      $data = $this->db->queryAllObjects("SELECT id, childs, hide_articles FROM #__categories WHERE parent_id = $cat_id");

      if ($data) {
         foreach ($data as $d) {
            $cats_arr[] = $d->id;

            if ((int)$d->childs > 0 && $d->hide_articles == 'n' ) {
               $this->_setCatlistSelect($d->id, $cats_arr);
            }
         }
      }

      else {
         return;
      }
   }

   private function getSubId($kat_id) {
      $catlist = '';

      foreach ($this->childs as $categorie_ord) {
         foreach ($categorie_ord as $categorie) {
            // Geschützte Kategorien übergehen
            if ($categorie['cat_pass'] != '') {
               continue;
            }

            if ($categorie['parent_id'] == $kat_id) {
               $catlist .= ",".$categorie['id'];

               if ($categorie['childs']) {
                  $catlist .= $this->getSubId($categorie['id']);
               }
            }
         }
      }

      return $catlist;
   }

   public function getMarkenfilter() {
      $markenfilter = 'n';

      if ((int)$this->curr_cat > 0) {
         $markenfilter = $this->db_extern->querySingleValue("SELECT markenfilter FROM #__categories WHERE id = $this->curr_cat");
      }

      return $markenfilter;
   }

   public function getAlterCheck() {
      $alter_check = 'n';

      if ((int)$this->curr_cat > 0) {
         $alter_check = $this->db_extern->querySingleValue("SELECT alter_check FROM #__categories WHERE id = $this->curr_cat");
      }

      return $alter_check;
   }

   // Module Kategoriefilter
   public function getFilterCheck($cat_id) {
      $check = $this->db_extern->querySingleObject("SELECT filter_active, filter_json FROM #__categories WHERE id = $cat_id");

      if ($check && $check->filter_active == 'y' && $check->filter_json != '') {
         return true;
      }

      return false;
   }

   // Module Mixer
   public function getMixerCheck($cat_id) {
      $data = $this->db_extern->querySingleObject("SELECT c.mixer_check, c.mixer_gewicht, c.gewicht_check, c.naehrwerte_check, i.mixer1, i.mixer2
                                               FROM #__categories AS c
                                            LEFT JOIN #__categorie_images AS i
                                               ON c.id = i.cat_id
                                            WHERE c.id = $cat_id");

      return $data;
   }

   public function checkCatpass($id, $cat_pass) {
      $test = $this->db_extern->querySingleValue("SELECT cat_pass FROM #__categories WHERE id = $id");

      if ($test !== false && $test === $cat_pass) {
         $_SESSION['cat_pass'][$id] = $cat_pass;
         return true;
      }

      return false;
   }

   private function _getLi($name, $link, $id, $level, $css_data, $cat_pass, $user_pass, $li_class, $div_class, $a_class, $filter = 'n') {
      // Anzahl childs
      if ($css_data == '') {
         $css_data = 0;
      }

      // Klasse, ob childs vorhanden sind (<div>)

      // Artikelname kürzen, abhängig vom Level
      if ((int)$level >= 0) {
         $title = $name;
         $hor   = $this->is_horizontal;
         $zm    = (int)$this->params->firma['zeichen_main'];
         $zs    = (int)$this->params->firma['zeichen_sub'];

         if ($this->params->firma['kategorien_links'] == 'y') {
            switch ((int)$level) {
               case 0:
                  $name = Helper::truncate($name, $zm);
                  break;
               case 1:
                  $name = Helper::truncate($name, $zs);
                  break;
               case 2:
                  $name = Helper::truncate($name, $zs - 2);
                  break;
               case 3:
                  $name = Helper::truncate($name, $zs - 4);
                  break;
               case 4:
                  $name = Helper::truncate($name, $zs - 6);
                  break;
               case 5:
                  $name = Helper::truncate($name, $zs - 8);
                  break;
               case 6:
                  $name = Helper::truncate($name, $zs - 10);
                  break;
               case 7:
               default:
                  break;
            }
         }

         else {
            switch ((int)$level) {
               case 0:
                  $name = Helper::truncate($name, $zm);
                  break;
               case 1:
                  $name = Helper::truncate($name, $zm);
                  break;
               default:
                  $name = Helper::truncate($name, $zs);
                  break;
            }
         }
      }

      $catpass = false;

      if (defined('CONF_MODULE_KATEGORIEPASS') && $cat_pass != '' ) {
         $catpass = true;
      }

      $html  = '<li class="level'.$level.' '.$this->kat_linien.' '.$li_class.'">';
      // catpass: bei Menüs mit flexibler Breite notwendig, um Padding rechts zu setzen
      $html .= '<div class="level'.$level.' '.$div_class.($catpass ? ' catpass' : '').'"  data-cat_id="'.$id.'" data-link="'.$link.'" data-childs="'.$css_data.'">';

      $html .= '<span></span>';
      //0 = Hauptcategorie
      if ($level == 0 ){
         $html .= (defined('CONF_HAUPTCAT') && CONF_HAUPTCAT == '1')? '<h1>' : '<h2>';
      }elseif ($level > 0){
         $html .= '<h3>';
      }

      $html .= $name;

      if ($level == 0 ){
         $html .= (defined('CONF_HAUPTCAT') && CONF_HAUPTCAT == '1')? '</h1>' : '</h2>';
      }elseif ($level > 0){
         $html .= '</h3>';
      }

      $html .= '<a class="link'.($a_class != '' ? ' '.$a_class : '').'" href="'.$link.'" data-filter="'.$filter.'"></a>';

      if ($catpass) {
         if ($user_pass === $cat_pass) {
            $html .=    '<div class="pw_ok"></div>';
         }
         else if ($user_pass !== $cat_pass) {
            $html .=    '<a href="#catpass_box" class="pw_fail" data-id="'.$id.'"></a>';
         }
      }

      $html .= '</div>';

      // Kein </li>, da evtl. Unterkategorien innerhalb sein müssen
      return $html;
   }

   // /tmp/cat_cache_<lang>.js erstellen
   private function makeCatCache() {
      $lang    = $this->params->selected_lang;
      $cat_arr = [];

      $categories = [];

      if ($this->params->multishop) {
         $categories = $this->db_extern->queryAllObjects("SELECT id FROM #__categories WHERE parent_id = 0 AND active = 'y' ORDER BY level, ordered");
      }

      else {
         $cats = $this->db_extern->queryAllObjects("SELECT id FROM #__categories WHERE parent_id = 0 AND active = 'y' ORDER BY level, ordered");

         if($cats != null)
         foreach ($cats as $c) {
            if ($this->db->querySingleValue("SELECT active FROM #__categories WHERE id = $c->id") != 'y') {
               continue;
            }

            set_time_limit(30);
            $cat_arr[$c->id] = $this->loadKategorie($c->id);
         }
      }

      file_put_contents(SHOP_PATH.'/tmp/cat_cache_'.$lang.'.js', json_encode($cat_arr));
   }

   function getCategoryBreadcrumb($activeCat){

       if($this->params->firma['show_breadcrumbs'] != 'y'){
           return "";
       }

       $activeCat = $this->params->kat_id;

       //echo $this->params->kat_id; die();

       //var_dump($GLOBALS["categories"]);die();

       $catobjs = $this->db_extern->queryAllObjects("select id, name_".$this->params->selected_lang." as name, level, parent_id from #__categories");


       foreach($catobjs as $cat){

           $catarr[$cat->id]=array("id"=>$cat->id, "name"=>$cat->name, "level"=>$cat->level, "parent_id"=>$cat->parent_id);


       }


       $breadcrumb = "";

       if($activeCat != 0){

           $currentCat = $catarr[$activeCat];


           $first=true;

           $maxDepth = 100;

           $catWithParents = array();

           while($maxDepth>0){

               $cat = $currentCat;


               $catWithParents[]=$cat;

               if($cat['level'] == 0)break;

               $currentCat = $catarr[$cat['parent_id']];

               $maxDepth--;

           }

           $catWithParents = array_reverse($catWithParents);

           foreach($catWithParents as $cat)
           {
               $breadcrumbpart = "";

               if($first){ $first=false; }
               else{
                   $breadcrumbpart.=" / ";
               }

               $breadcrumbpart .= "<a href ='/k".$cat["id"]."'>".$cat["name"]."</a>";



               $breadcrumb .= $breadcrumbpart;

           }

       }

       return "<div>".$breadcrumb."</div>";

   }

}
