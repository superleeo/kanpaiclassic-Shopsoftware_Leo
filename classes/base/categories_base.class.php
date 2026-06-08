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

class KANPAICLASSIC_categoriesBase
{

   public    $db;
   public    $db_extern;
   public    $params;
   public    $text;

   public    $categories  = [];
   public    $childs      = [];
   protected $active_cats = [];
   private   $generated   = false;
   private   $search_del  = 0;
   public    $count       = 0;
   public    $max_cats    = 0;
   public    $treemode    = '';

   public function __construct() {
      $this->db        = Control::getDB();
      $this->db_extern = Control::getExternDB();
      $this->params    = Control::getParams();
      $this->text      = Control::getText();

      $this->max_cats  = $this->db_extern->querySingleValue("SELECT count(id) FROM #__categories WHERE `active` = 'y'");
   }

   // Kategorien Level 0/1 in Array $categories, Rest in Array $childs einlesen
   // Bei FE deaktivierte Kategorien filtern
   // active_cats setzen
   public function getTree($categories = '#__categories') {
      $start_debug = microtime(true);

      // verhindern, dass mehrmals eingelesen wird
      if ($this->generated) {
         return;
      }

      $this->generated  = true;

      $this->categories = [];
      $lang             = $this->params->selected_lang;
      $active           = '';
      $catpass          = '';

      // Sitemap in Shopsprache
      if ($this->treemode == 'sitemap') {
         $lang = $this->params->default_lang;
      }

      // Netzwerkkategorien
      if ($categories == '#__net_categories') {
         $lang = 'deu';
      }

      // Shopkategorien
      else {
         $active  = 'a.active, ';
         $catpass = 'a.cat_pass, ';
      }

      // Shop
      $sql = "SELECT a.id, a.parent_id, $active a.name_$lang as name, a.level, a.ordered, a.childs, a.network_id, $catpass a.markenfilter, a.alter_check,
                     b.name_$lang AS title1, b.parent_id AS parent_id1, b.level as level2,
                     b.ordered as ordered1, b.childs as childs2, a.filter_active, a.mixer_check, a.title_$lang AS titletag
              FROM $categories AS a
              LEFT OUTER JOIN $categories AS b
                 ON a.parent_id = b.id
              ORDER BY a.level, a.ordered";

      // Admin
      $sql2 = "SELECT a.id, a.parent_id, $active a.name_$lang as name, a.level, a.ordered, a.childs, a.network_id, $catpass a.markenfilter, a.alter_check, a.active,
                     b.name_$lang AS title1, b.parent_id AS parent_id1, b.level as level2,
                     b.ordered as ordered1, b.childs as childs2, a.filter_active, a.mixer_check,
                     (SELECT count(ac.parent_id) FROM #__article_to_cats AS ac WHERE ac.cat_id = a.id) AS artikel
                 FROM $categories AS a
              LEFT OUTER JOIN $categories AS b
                 ON a.parent_id = b.id
              ORDER BY a.level, a.ordered";

      // Admin - nicht bei Sitemap
      if ($this->treemode != 'sitemap' && $this->params->isAdmin) {
         if ($this->db_extern->query($sql2)) {
            while ($kategorie = $this->db_extern->getObject()) {
               $this->count++;

               // Bei neuen Kategorien im Master-Shop
               if ($this->params->multishop && $categories == '#__categories') {
                  $cat_id = $kategorie->id;
                  $test   = (int)$this->db->querySingleValue("SELECT id FROM #__categories WHERE id = $cat_id");

                  // Kategorie aus Mastershop lokal eintragen
                  if ($test == 0) {
                     $this->db->query("INSERT INTO #__categories SET id = $cat_id, active = 'y'");
                  }
               }

               // Haupteintrag und Level1
               if ((int)$kategorie->level < 2) {
                  if (!(int)$kategorie->parent_id) {
                     $level = (int)$kategorie->ordered;
                     $order = 0;
                  }

                  else {
                     $level = (int)$kategorie->ordered1;
                     $order = (int)$kategorie->ordered;
                  }

                  $this->categories[$level][$order] = [
                           'id'            => (int)$kategorie->id,
                           'parent_id'     => (int)$kategorie->parent_id,
                           'active'        => (!$this->params->multishop ? $kategorie->active : $this->db->querySingleValue("SELECT active FROM #__categories WHERE id = ".(int)$kategorie->id)),
                           'name'          => $kategorie->name,
                           'level'         => (int)$kategorie->level,
                           'ordered'       => (int)$kategorie->ordered,
                           'childs'        => (int)$kategorie->childs,
                           'netid'         => (int)$kategorie->network_id,
                           'cat_pass'      => $kategorie->cat_pass,
                           'markenfilter'  => $kategorie->markenfilter,
                           'alter_check'   => $kategorie->alter_check,
                           'artikel'       => (int)$kategorie->artikel,
                           'filter_active' => $kategorie->filter_active,
                           'mixer_check'   => $kategorie->mixer_check
                  ];
               }

               // Untereinträge
               else {
                  $this->childs[$kategorie->parent_id][$kategorie->ordered] = [
                           'id'            => (int)$kategorie->id,
                           'parent_id'     => (int)$kategorie->parent_id,
//                           'active'        => $kategorie->active,
                           'active'        => (!$this->params->multishop ? $kategorie->active : $this->db->querySingleValue("SELECT active FROM #__categories WHERE id = ".(int)$kategorie->id)),
                           'name'          => $kategorie->name,
                           'level'         => (int)$kategorie->level,
                           'ordered'       => (int)$kategorie->ordered,
                           'childs'        => (int)$kategorie->childs,
                           'netid'         => (int)$kategorie->network_id,
                           'cat_pass'      => $kategorie->cat_pass,
                           'markenfilter'  => $kategorie->markenfilter,
                           'alter_check'   => $kategorie->alter_check,
                           'artikel'       => (int)$kategorie->artikel,
                           'filter_active' => $kategorie->filter_active,
                           'mixer_check'   => $kategorie->mixer_check
                  ];
               }
            }
         }
      }

      // Shop und Sitemap -> bis Ende, dann noch return
      else {
         $del_arr_ids     = [];
         $del_arr_parents = [];

         // Kategorien in Arrays einlesen
         $data = $this->db_extern->queryAllObjects($sql);

         if ($data) {
            // ... und durchgehen
            foreach ($data as $kategorie) {
               // Bei Multishop 'active' aus lokaler DB
//               if ($this->params->multishop) {
//                  $kategorie->active = $this->db->querySingleValue("SELECT active FROM #__categories WHERE id = $kategorie->id");
//               }

               $deleted = false;

               // Haupteintrag und 1 darunter (Level 0/1)
               if ($kategorie->level < 2) {
                  if (!$kategorie->parent_id) {
                     // Haupteintrag
                     if ($kategorie->active == 'n') {
                        $del_arr_ids[]     = $kategorie->id;
                        $del_arr_parents[] = $kategorie->parent_id;
                        $deleted           = true;
                     }

                     $level = $kategorie->ordered;
                     $order = 0;
                  }

                  // Eintrag unter Hauptmenü / Level1
                  else {
                     // Wenn Hauptkategorie weiter
                     if (in_array($kategorie->parent_id, $del_arr_ids, true) || $kategorie->active == 'n') {
                        $del_arr_ids[]     = $kategorie->id;
                        $del_arr_parents[] = $kategorie->parent_id;
                        $deleted = true;
                     }

                     $level = $kategorie->ordered1;
                     $order = $kategorie->ordered;
                  }

                  if (!$deleted) {
                     $this->categories[$level][$order] = [
                     'id'            => (int)$kategorie->id,
                     'parent_id'     => (int)$kategorie->parent_id,
                     'active'        => $kategorie->active,
                     'name'          => $kategorie->name,
                     'level'         => (int)$kategorie->level,
                     'ordered'       => (int)$kategorie->ordered,
                     'childs'        => (int)$kategorie->childs,
                     'netid'         => (int)$kategorie->network_id,
                     'cat_pass'      => $kategorie->cat_pass,
                     'filter_active' => $kategorie->filter_active,
                     'mixer_check'   => $kategorie->mixer_check,
                     'titletag'      => $kategorie->titletag
                     ];
                  }
               }

               // Untereinträge, Level = 2 und höher
               else {
                  if (in_array($kategorie->parent_id, $del_arr_ids, true) || $kategorie->active == 'n') {
                     $del_arr_ids[]     = $kategorie->id;
                     $del_arr_parents[] = $kategorie->parent_id;
                     $deleted           = true;
                  }

                  if (!$deleted) {
                     $this->childs[$kategorie->parent_id][$kategorie->ordered] = [
                     'id'            => (int)$kategorie->id,
                     'parent_id'     => (int)$kategorie->parent_id,
                     'active'        => $kategorie->active,
                     'name'          => $kategorie->name,
                     'level'         => (int)$kategorie->level,
                     'ordered'       => (int)$kategorie->ordered,
                     'childs'        => (int)$kategorie->childs,
                     'netid'         => (int)$kategorie->network_id,
                     'cat_pass'      => $kategorie->cat_pass,
                     'filter_active' => $kategorie->filter_active,
                     'mixer_check'   => $kategorie->mixer_check,
                     'titletag'      => $kategorie->titletag
                     ];
                  }
               }
            } // foreach

            $del_arr_parents = array_unique($del_arr_parents);
            $this->params->debug .= '<br />getTree/Base vor löschen: '.number_format((microtime(true) - $start_debug), 3, ',', '.').' Sek';

            foreach ($del_arr_parents as $d) {
               $this->search_del = $d;
               $this->active_cats = [];
               $this->check_child($this->categories);
               $this->check_child($this->childs);
            }

            $this->params->debug .= '<br />getTree/Base nach löschen: '.number_format((microtime(true) - $start_debug), 3, ',', '.').' Sek';

            $all_cats = [];
            $sql = "SELECT id FROM $categories";

            if ($this->db_extern->query($sql)) {
               while ($temp = $this->db_extern->getObject()) {
                  if ($temp) {
                     $all_cats[] = $temp->id;
                  }
               }
            }

            $cats_active = array_diff($all_cats, $del_arr_ids);
            $first = true;
            $cat_str = '';

            foreach ($cats_active as $v) {
               if ($first) {
                  $cat_str = "'$v'";
                  $first = false;
               }
               else {
                  $cat_str .= ",'$v'";
               }
            }

            $this->params->cats_active = $cat_str;
         }
      }

      $this->params->debug .= '<br />getTree/Base: '.number_format((microtime(true) - $start_debug), 3, ',', '.').' Sek';
      return;
   }

   private function check_child(&$c) {
      if (isset($c['id'])) {
         if ((int)$c["id"] == $this->search_del) {
            $childs = (int)$c["childs"];
            if ($childs > 0) {
               $childs--;
            }
            $c["childs"] = $childs;
            return true;
         }
         else {
            $this->active_cats[] = $c['id'];
         }
      }

      else {
         $keys = array_keys($c);
         for ($i = 0; $i < count($keys); $i++) {
            $this->check_child($c[$keys[$i]]);
         }
      }
   }

   // Bilder / SEO einer Kategorie einlesen
   // FE und BE
   protected function loadImages($cat_id, $lang) {
      // Default-Werte setzen, um Fehlermeldungen zu verhindern
      $images = (object)[];

      // Kategorie vorhanden
      if ($cat_id > 0) {
         $img_data = $this->db_extern->querySingleObject("SELECT * FROM #__categorie_images WHERE cat_id = $cat_id AND lang = '$lang'");

         // Eintrag in DB vorhanden
         if ($img_data) {
            // Neue Version noch nicht vorhanden -> DB-Eintrag anpassen
            if ($img_data->images == '') {
               $cat_name = Helper::checkFilename($this->db_extern->querySingleValue("SELECT name_$lang FROM #__categories WHERE id = $cat_id"));
               $image_arr           = [];
               $image_arr['images'] = [];
               $anzahl              = 0;

               // Vorhandene Bilder übernehmen
               if ($img_data->img1) {
                  $anzahl++;
                  $filename = $cat_name.'_'.$lang.'_'.$cat_id.'_'.$anzahl;
                  rename(PICTURE_PATH.'kategorien/original/'.$img_data->img1.'.jpg', PICTURE_PATH.'kategorien/original/'.$filename.'.jpg');
                  rename(PICTURE_PATH.'kategorien/'.$img_data->img1.'.jpg', PICTURE_PATH.'kategorien/'.$filename.'.jpg');
                  @unlink(PICTURE_PATH.'kategorien/'.$img_data->img1.'_tn.jpg');
                  $this->_convertThumbnail($filename);
                  $image_arr['images'][] = (object)['image'  => $filename, 'link'   => $img_data->link1, 'intern' => $img_data->intern1, 'seo' => $img_data->search1];
               }

               if ($img_data->img2) {
                  $anzahl++;
                  $filename = $cat_name.'_'.$lang.'_'.$cat_id.'_'.$anzahl;
                  rename(PICTURE_PATH.'kategorien/original/'.$img_data->img2.'.jpg', PICTURE_PATH.'kategorien/original/'.$filename.'.jpg');
                  rename(PICTURE_PATH.'kategorien/'.$img_data->img2.'.jpg', PICTURE_PATH.'kategorien/'.$filename.'.jpg');
                  @unlink(PICTURE_PATH.'kategorien/'.$img_data->img2.'_tn.jpg');
                  $this->_convertThumbnail($filename);
                  $image_arr['images'][] = (object)['image'  => $filename, 'link'   => $img_data->link2, 'intern' => $img_data->intern2, 'seo' => $img_data->search2];
               }

               if ($img_data->img3) {
                  $anzahl++;
                  $filename = $cat_name.'_'.$lang.'_'.$cat_id.'_'.$anzahl;
                  rename(PICTURE_PATH.'kategorien/original/'.$img_data->img3.'.jpg', PICTURE_PATH.'kategorien/original/'.$filename.'.jpg');
                  rename(PICTURE_PATH.'kategorien/'.$img_data->img3.'.jpg', PICTURE_PATH.'kategorien/'.$filename.'.jpg');
                  $this->_convertThumbnail($filename);
                  @unlink(PICTURE_PATH.'kategorien/'.$img_data->img3.'.jpg');
                  @unlink(PICTURE_PATH.'kategorien/'.$img_data->img3.'_tn.jpg');
                  $image_arr['images'][] = (object)['image'  => $filename, 'link'   => $img_data->link3, 'intern' => $img_data->intern3, 'seo' => $img_data->search3];
               }

               if ($img_data->img4) {
                  $anzahl++;
                  $filename = $cat_name.'_'.$lang.'_'.$cat_id.'_'.$anzahl;
                  rename(PICTURE_PATH.'kategorien/original/'.$img_data->img4.'.jpg', PICTURE_PATH.'kategorien/original/'.$filename.'.jpg');
                  rename(PICTURE_PATH.'kategorien/'.$img_data->img4.'.jpg', PICTURE_PATH.'kategorien/'.$filename.'.jpg');
                  @unlink(PICTURE_PATH.'kategorien/'.$img_data->img4.'_tn.jpg');
                  $this->_convertThumbnail($filename);
                  $image_arr['images'][] = (object)['image'  => $filename, 'link'   => $img_data->link4, 'intern' => $img_data->intern4, 'seo' => $img_data->search4];
               }

               if ($img_data->img5) {
                  $anzahl++;
                  $filename = $cat_name.'_'.$lang.'_'.$cat_id.'_'.$anzahl;
                  rename(PICTURE_PATH.'kategorien/original/'.$img_data->img5.'.jpg', PICTURE_PATH.'kategorien/original/'.$filename.'.jpg');
                  rename(PICTURE_PATH.'kategorien/'.$img_data->img5.'.jpg', PICTURE_PATH.'kategorien/'.$filename.'.jpg');
                  @unlink(PICTURE_PATH.'kategorien/'.$img_data->img5.'_tn.jpg');
                  $this->_convertThumbnail($filename);
                  $image_arr['images'][] = (object)['image'  => $filename, 'link'   => $img_data->link5, 'intern' => $img_data->intern5, 'seo' => $img_data->search5];
               }

               if ($img_data->img6) {
                  $anzahl++;
                  $filename = $cat_name.'_'.$lang.'_'.$cat_id.'_'.$anzahl;
                  rename(PICTURE_PATH.'kategorien/original/'.$img_data->img6.'.jpg', PICTURE_PATH.'kategorien/original/'.$filename.'.jpg');
                  rename(PICTURE_PATH.'kategorien/'.$img_data->img6.'.jpg', PICTURE_PATH.'kategorien/'.$filename.'.jpg');
                  @unlink(PICTURE_PATH.'kategorien/'.$img_data->img6.'_tn.jpg');
                  $this->_convertThumbnail($filename);
                  $image_arr['images'][] = (object)['image'  => $filename, 'link'   => $img_data->link6, 'intern' => $img_data->intern6, 'seo' => $img_data->search6];
               }

               $options = (object)['mode' => 2, 'zuschneiden' => 'y'];
               // und speichern
               $this->db_extern->query("UPDATE #__categorie_images SET anzahl = $anzahl, images = '".json_encode($image_arr)."', options = '".json_encode($options)."' WHERE id = $img_data->id");

               $images = $this->db_extern->querySingleObject("SELECT * FROM #__categorie_images WHERE cat_id = $cat_id AND lang = '$lang'");
            }

            // Neuer Version vorhanden
            else {
               $images = $img_data;
               $json   = json_decode($images->images);

               // Aber kein Objekt
               if (empty($json)) {
                  $image_obj       = (object)['images' => []];
                  $images          = (object)[];

                  $images->images  = json_encode($image_obj);
                  $images->mixer1  = '';
                  $images->mixer2  = '';
                  $images->mixer3  = '';
                  $images->id      = $img_data->id;
                  $images->anzahl  = 0;
                  $images->options = json_encode((object)['mode' => 2, 'zuschneiden' => 'y']);
               }
            }
         }

         // Kein Eintrag in DB enthalten
         else {
            $image_obj       = (object)['images' => []];
            $images          = (object)[];

            $images->images  = json_encode($image_obj);
            $images->mixer1  = '';
            $images->mixer2  = '';
            $images->mixer3  = '';
            $images->id      = 0;
            $images->anzahl  = 0;
            $images->options = json_encode((object)['mode' => 2, 'zuschneiden' => 'y']);
         }
      }

      // Neue Kategorie (nur BE möglich)
      else {
         $image_obj       = (object)['images' => []];
         $images          = (object)[];

         $images->images  = json_encode($image_obj);
         $images->mixer1  = '';
         $images->mixer2  = '';
         $images->mixer3  = '';
         $images->mixer3  = '';
         $images->id      = 0;
         $images->anzahl  = 0;
         $images->options = json_encode((object)['mode' => 2, 'zuschneiden' => 'y']);
      }

      // Image-Objekt erstellen
      $json = json_decode($images->images);

      $image_obj           = $json; // $images->images[]
      $image_obj->mixer1   = $images->mixer1;
      $image_obj->mixer2   = $images->mixer2;
      $image_obj->mixer3   = $images->mixer3;
      $image_obj->id       = $images->id;
      $image_obj->anzahl   = $images->anzahl;
      $image_obj->options  = $images->options;

      return $image_obj;
   }

   // Kategorie-Pfad, nur FE
   public function getPath($cat_id, $katalog = false) {
      $cat_path = '';
      $cat      = $this->db_extern->querySingleObject("SELECT parent_id, name_".$this->params->selected_lang." AS name FROM #__categories WHERE id = $cat_id");

      if ($cat) {
         // PDF-Katalog
         if ($katalog) {
            if ((int)$cat->parent_id != 0) {
               $cat_path .= $this->getPath($cat->parent_id, $katalog).'||';
            }

            return trim($cat_path.$cat->name);
         }


         // Für URL
         else {
            if ((int)$cat->parent_id != 0) {
               $cat_path .= '-'.$this->getPath($cat->parent_id).'||';
            }

            return trim($cat_path.'-'.str_replace(' ', '-', $cat->name), '-');
         }
      }

      return '';
   }

   protected function _makeImage($filename, $mode, $zuschneiden) {
      $original = PICTURE_PATH.'kategorien/original/'.$filename.'.jpg';
      $new_img  = PICTURE_PATH.'kategorien/'.$filename.'.jpg';
      $hoehe_org    = 0;
      $breite_org   = 0;
      $breite_neu   = 0;
      $hoehe_neu    = 0;

      list($breite_org, $hoehe_org) = \getimagesize($original);

      switch($mode) {
         // contentbreit - Schopbreite (firma['max_width']) / zuschneiden 1:2
         case 1:
            $breite_neu  = $this->params->firma['max_width'];

            if ($breite_org < $breite_neu) {
               $breite_neu = $breite_org;
            }

            if ($zuschneiden == 'y') {
               $hoehe_neu = round($breite_neu / 2);

               if ($hoehe_org < $hoehe_neu) {
                  $hoehe_neu = $hoehe_org;
                  $breite_neu = round($hoehe_neu * 2);
               }

               if ($breite_org < $breite_neu || $hoehe_org < $hoehe_neu) {
                  Helper::resizeImageSlideshow($original, $new_img, $breite_neu, $hoehe_neu, false);
               }

               else {
                  Helper::resizePicCenter($original, $new_img, $breite_neu, $hoehe_neu, 'jpg', false, false);
               }
            }

            else {
               $hoehe_neu = round($hoehe_org * $breite_neu / $breite_org);
               // Helper::resizeImageSlideshow($original, $new_img, $breite_neu, $hoehe_neu, false);
               Helper::imageResize($original, $new_img, $breite_neu, $hoehe_neu, 'jpg', false, false, false, $breite_neu, 0, false, false);
            }

            break;

         // 2 Bilder - Collage / zuschneiden 2:3
         case 2:
            $breite_neu = round(591 / 1183 * $this->params->firma['max_width']);

            if ($breite_org < $breite_neu) {
               $breite_neu = $breite_org;
            }

            if ($zuschneiden == 'y') {
               $hoehe_neu = round($breite_neu * 2 / 3);

               if ($hoehe_org < $hoehe_neu) {
                  $hoehe_neu = $hoehe_org;
                  $breite_neu = round($hoehe_neu* 3 / 2);
               }

               // Vergrößern
               if ($breite_org < $breite_neu || $hoehe_org < $hoehe_neu) {
                  Helper::resizeImageSlideshow($original, $new_img, $breite_neu, $hoehe_neu, false);
               }

               // Verkleinern
               else {
                  Helper::resizePicCenter($original, $new_img, $breite_neu, $hoehe_neu, 'jpg', false, false);
                  // Helper::imageResize($original, $new_img, $breite_neu, $hoehe_neu, 'jpg', false, false, false, $breite_neu, 0, false, false);
               }
            }

            else {
               $hoehe_neu = round($hoehe_org / $breite_org * $breite_neu);
               // Helper::resizeImageSlideshow($original, $new_img, $width, $height, false);
               // static public function imageResize($orgfile, $newfile, $breite_org, $hoehe, $new_ext = '', $delete = true, $schneiden = false, $align_left = false, $maxbreite = 0, $maxhoehe = 0, $transparent = false, $background = false) {
               Helper::imageResize($original, $new_img, $breite_neu, $hoehe_neu, 'jpg', false, false, false, $breite_neu, 0, false, false);
            }

            break;

         // 3 Bilder - Artikel groß / zuschneiden 2:3
         case 3:
            $breite_neu = 700;

            if ($zuschneiden == 'y') {
               $hoehe_neu = round($breite_neu * 2 / 3);

               if ($hoehe_neu == $breite_neu) {
                  $hoehe_neu -= 3;
               }

               // Vergrößern
               if ($breite_org < $breite_neu || $hoehe_org < $hoehe_neu) {
                  Helper::resizeImageSlideshow($original, $new_img, $breite_neu, $hoehe_neu, false);
                  // Helper::imageResize($original, $new_img, $breite_neu, $hoehe_neu, 'jpg', false, true, false, $breite_neu, $hoehe_neu, false, false);
                  // Helper::imageResize($original, $new_img, $breite_neu, $hoehe_neu, 'jpg', false, false, false, $breite_neu, $hoehe_neu, false, false);
                  // Helper::imageResize($original, $new_img, $breite_neu, 0, 'jpg', false, false, false, 0, $hoehe_neu, false, false);
                  // Helper::imageResize($original, $new_img, $breite_neu, $hoehe_neu, 'jpg', false, false, false, 0, 0, false, false);
               }

               // Verkleinern
               else {
                  Helper::resizePicCenter($original, $new_img, $breite_neu, $hoehe_neu, 'jpg', false, false);
                  // Helper::imageResize($original, $new_img, $breite_neu, $hoehe_neu, 'jpg', false, false, false, 0, 0, false, false);
               }
            }

            else {
               $hoehe_neu = round($hoehe_org * $breite_neu / $breite_org);

               Helper::imageResize($original, $new_img, $breite_neu, $hoehe_neu, 'jpg', false, false, false, $breite_neu, 0, false, false);
            }

            break;

         // 4 / 5 Bilder - Artikel normal / zuschneiden 2:3
         case 4:
         case 5:
            $breite_neu = 540;

            if ($zuschneiden == 'y') {
               $hoehe_neu = round($breite_neu * 2 / 3);

               if ($breite_org < $breite_neu || $hoehe_org < $hoehe_neu) {
                  // Helper::resizeImageSlideshow($original, $new_img, $breite_neu, $hoehe_neu, false);
                  Helper::imageResize($original, $new_img, $breite_neu, $hoehe_neu, 'jpg', false, false, false, 0, 0, false, false);
               }

               else {
                  Helper::resizePicCenter($original, $new_img, $breite_neu, $hoehe_neu, 'jpg', false, false);
                  // Helper::imageResize($original, $new_img, $breite_neu, $hoehe_neu, 'jpg', false, false, false, $breite_neu, 0, false, false);
               }
            }

            else {
               $hoehe_neu = round($hoehe_org * $breite_neu / $breite_org);
               // Helper::resizeImageSlideshow($original, $new_img, $breite_neu, $hoehe_neu, false);
               Helper::imageResize($original, $new_img, $breite_neu, $hoehe_neu, 'jpg', false, false, false, $breite_neu, 0, false, false);
            }

            break;

         // bildschirmbreit (2200px) / zuschneiden 1:3
         case 10:
            $breite_neu = 2200;

            if ($breite_org < $breite_neu) {
               $breite_neu = $breite_org;
            }

            if ($zuschneiden == 'y') {
               $hoehe_neu = round($breite_neu / 3);

               if ($hoehe_org < $hoehe_neu) {
                  $hoehe_neu = $hoehe_org;
                  $breite_neu = round($hoehe_neu* 3 / 2);
               }

               if ($breite_org < $breite_neu || $hoehe_org < $hoehe_neu) {
                  Helper::resizeImageSlideshow($original, $new_img, $breite_neu, $hoehe_neu, false);
               }

               else {
                  Helper::resizePicCenter($original, $new_img, $breite_neu, $hoehe_neu, 'jpg', false, false);
               }
            }

            else {
               $hoehe_neu = round($hoehe_org * $breite_neu / $breite_org);
               Helper::resizeImageSlideshow($original, $new_img, $breite_neu, $hoehe_neu, false);
            }

            break;
      }
   }

   protected function NEU_makeImage($filename, $mode, $zuschneiden) {
/*      $original     = PICTURE_PATH.'kategorien/original/'.$filename.'.jpg';
      $new_img      = PICTURE_PATH.'kategorien/'.$filename.'.jpg';
      $hoehe_org    = 0;
      $breite_org   = 0;
      $breite_neu   = 0;
      $hoehe_neu    = 0;

      list($breite_org, $hoehe_org) = \getimagesize($original);

      switch($mode) {
         // contentbreit - Schopbreite (firma['max_width']) / zuschneiden 1:2
         case 1:
            $breite_neu  = $this->params->firma['max_width'];

            if ($zuschneiden == 'y') {
               $hoehe_neu = round($breite_neu / 2);
            }

            else {
               $hoehe_neu = round($hoehe_org * $breite_neu / $breite_org);
            }

            break;

         // 2 Bilder - Collage / zuschneiden 2:3
         case 2:
            $breite_neu = round(591 / 1183 * $this->params->firma['max_width']);

            if ($zuschneiden == 'y') {
               $hoehe_neu = round($breite_neu * 2 / 3);
            }

            else {
               $hoehe_neu = round($hoehe_org / $breite_org * $breite_neu);
            }

            break;

         // 3 Bilder - Artikel groß / zuschneiden 2:3
         case 3:
            $breite_neu = 800;

            if ($zuschneiden == 'y') {
               $hoehe_neu = round($breite_neu * 2 / 3);
            }

            else {
               $hoehe_neu = round($hoehe_org * $breite_neu / $breite_org);
            }

            break;

         // 4 / 5 Bilder - Artikel normal / zuschneiden 2:3
         case 4:
         case 5:
            $breite_neu = 540;

            if ($zuschneiden == 'y') {
               $hoehe_neu = round($breite_neu * 2 / 3);
            }

            else {
               $hoehe_neu = round($hoehe_org * $breite_neu / $breite_org);
            }

            break;

         // bildschirmbreit (2200px) / zuschneiden 1:3
         case 10:
            $breite_neu = 2200;

            if ($zuschneiden == 'y') {
               $hoehe_neu = round($breite_neu / 3);
            }

            else {
               $hoehe_neu = round($hoehe_org * $breite_neu / $breite_org);
            }

            break;
      }

      $image = null;
      switch($extension) {
         case 'png':
            $image = imagecreatefrompng($orgfile);
            break;

         case 'jpg':
            $image = imagecreatefromjpeg($orgfile);
            break;

         case 'gif':
            $image = imagecreatefromgif($orgfile);
            break;

         default:
            return false;
      }

      $new_image  = imagecreatetruecolor($breite_neu, $hoehe_neu);
      imagecopyresampled($new_image, $image, $offset_neu_x, $offset_neu_y, $offset_Org_x, $offset_org_y, $breite_neu, $hoehe_neu, $breite_org, $hoehe_org);
      imagejpeg($new_image, $new_img);
*/
   }

   // Größe Thumbnails (admin/kategorien) an File-Uploader anpassen (78x78 -> 128x128)
   private function _convertThumbnail($filename) {
      $img_dir      = PICTURE_PATH.'/kategorien/';
      Helper::resizePicCenter($img_dir.'original/'.$filename.'.jpg', $img_dir.$filename.'_tn.jpg', 150, 150, 'jpg' );

   }
}
