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

class KANPAICLASSIC_download
{
   private $db;
   private $db_extern;
   private $params;

   function __construct() {
      $this->db        = Control::getDB();
      $this->db_extern = Control::getExternDB();
      $this->params    = Control::getParams();
   }

   public function startDl($link) {
      $sql    = "SELECT * FROM #__downloads WHERE link ='$link'";
      $anzahl = $this->db->query($sql);

      if ($anzahl == 1) {
         $data = $this->db->getObject();
         $artikel_id = (int)$data->artikel_id;
         $sort = (int)$data->sort;

         // Download Datei
         if ($sort < 1) {
            if ($data->allowed == 'y' && (CONF_DOWNLOAD_MAX == 0 || (int)$data->count < CONF_DOWNLOAD_MAX)) {
               if (CONF_DOWNLOAD_DAYS == 0 || strtotime($data->valid) >= strtotime(date('Y-m-d'))) {
                  $filename = $data->filename;
                  $file = SHOP_PATH.'/downloads/'.$filename;

                  if (file_exists($file)) {
                     $sql = "UPDATE #__downloads SET count = count + 1, last_upload = NOW() WHERE link = '$link'";
                     $this->db->query($sql);
                     
                     if (ob_get_length()) ob_end_clean();

                     header('Content-Description: File Transfer');
                     header('Content-Type: application/octet-stream');
                     header('Content-Disposition: attachment; filename="'.$filename.'"');
                     header('Expires: 0');
                     header('Cache-Control: must-revalidate');
                     header('Pragma: public');
                     header('Content-Length: ' . filesize($file));
                     readfile($file);
                     exit;

//                     header("Content-Type: $data->mime_type");
//                     header("Content-Disposition: attachment; filename=\"$filename\"");
//                     readfile($file);
//                     return 0; // OK
                  }
                  return 3; // Datei nicht vorhanden
               }

               return 1; // Download abgelaufen (Zeit)
            }

            else {
               return 2; // Download abgelaufen (Anzahl)
            }
         }

         // Download Fotos
         else {
            if ($data->allowed == 'y' && (CONF_DOWNLOAD_MAX == 0 || (int)$data->count < CONF_DOWNLOAD_MAX)) {
               if (CONF_DOWNLOAD_DAYS == 0 || strtotime($data->valid) >= strtotime(date('Y-m-d'))) {
                  $sort = $data->sort;

                  $foto = $this->db_extern->querySingleObject("SELECT i.foto, f.name, a.art_nr, f.size
                                                           FROM #__articles_info AS i
                                                        LEFT JOIN #__articles AS a
                                                           ON a.parent_id = i.id
                                                        LEFT JOIN #__foto_data AS f
                                                           ON f.foto_set = i.foto_set
                                                        WHERE a.id = $artikel_id
                                                           AND f.sort = $sort");

                  // Falls Foto-Set nicht vorhanden, Default verwenden
                  if (!$foto) {
                      $foto = $this->db_extern->querySingleObject("SELECT i.foto, f.name, a.art_nr, f.size
                                                              FROM #__articles_info AS i
                                                           LEFT JOIN #__articles AS a
                                                              ON a.parent_id = i.id
                                                           LEFT JOIN #__foto_data AS f
                                                              ON f.foto_set = 1
                                                           WHERE a.id = $artikel_id
                                                              AND f.sort = $sort");
                  }

                  if ($foto) {
                     $name = str_replace('[MAX]', '', $foto->name);
                     $filename = $foto->foto;
                     $file = SHOP_PATH.'/downloads'.$filename;

                     // Original
                     if (file_exists($file)) {
                        if (strstr($foto->name, '[MAX]') !== false) {
                           $sql = "UPDATE #__downloads SET count = count + 1, last_upload = NOW() WHERE link = '$link'";
                           $this->db->query($sql);
                     
                           if (ob_get_length()) ob_end_clean();

                           header('Content-Description: File Transfer');
                           header('Content-Type: application/octet-stream');
                           header('Content-Disposition: attachment; filename="'.$filename.'"');
                           header('Expires: 0');
                           header('Cache-Control: must-revalidate');
                           header('Pragma: public');
                           header('Content-Length: ' . filesize($file));
                           readfile($file);
                           exit;

//                           header("Content-Type: image/jpeg");
//                           header("Content-Disposition: attachment; filename=\"".str_replace(' ', '_', $foto->art_nr.'_'.$name.'.jpg')."\"");
//                           readfile($file);
//                           return 0; // OK
                        }

                        // Bild auf Größe resamplen
                        else {
                           $faktor = 1;
                           $size = (int)$foto->size;
                           list($breite, $hoehe) = getimagesize($file);

                           if ($hoehe > $breite and $hoehe > $size) {
                              $faktor = $hoehe / $size;
                           }
                           elseif ($breite > $hoehe and $breite > $size) {
                              $faktor = $breite / $size;
                           }

                           $breite_neu = floor($breite / $faktor);
                           $hoehe_neu = floor($hoehe / $faktor);

                           $im = imagecreatefromjpeg($file);
                           $new_im = imagecreatetruecolor($breite_neu, $hoehe_neu);
                           imagecopyresampled($new_im, $im, 0, 0, 0, 0, $breite_neu, $hoehe_neu, $breite, $hoehe);

                           $downfile = $this->params->filepath.'/tmp/'.microtime(true);
                           imagejpeg($new_im, $downfile);
                           imagedestroy($im);
                           imagedestroy($new_im);

                           $imagesize = getimagesize($file, $info);

                           if (isset($info['APP13'])) {
                              $content = iptcembed($info['APP13'], $downfile);
                              unlink($downfile);

                              $fp = fopen($downfile, 'w');
                              fwrite($fp, $content);
                              fclose($fp);
                           }

                           header("Content-Type: image/jpeg");
                           header("Content-Disposition: attachment; filename=\"".str_replace(' ', '_', $foto->art_nr.'_'.$foto->name.'.jpg')."\"");
                           header('Content-Length: ' . filesize($downfile));
                           readfile($downfile);

                           unlink($downfile);

                           $sql = "UPDATE #__downloads SET count = count + 1, last_upload = NOW() WHERE link ='$link'";
                           $this->db->query($sql);
                           return 0;
                        }
                     }
                     return 3; // Datei nicht vorhanden
                  }

                  return 1; // Download abgelaufen (Zeit)
               }
            }

            else {
               return 2; // Download abgelaufen
            }
         }
      }

      if ($anzahl > 1) {
         return 4; // Mehrere DL-Einträge in DB
      }

      return 1; // Kein Eintrag in DB
   }

   public function getLinks($re_id) {
      $links = [];
      $data  = [];

      $sql = "SELECT artikel_id, filename, filetyp, foto_set, foto_sort FROM #__rechnung_artikel WHERE rechnung_id = $re_id";
      $this->db->query($sql);

      while ($tmp = $this->db->getObject()) {
         if ($tmp && ($tmp->filename != '' || (int)$tmp->foto_sort > 0)) {
            $data[] = $tmp;
         }
      }

      for ($i = 0; $i < (!empty($data) ? count($data) : 0); $i++) {
         if ((int)$data[$i]->foto_sort > 0 || strlen($data[$i]->filename) > 3) {
            $link = $this->_makeLink($data[$i]->filename);
            $sort = 0;

            if ((int)$data[$i]->foto_set > 0 && (int)$data[$i]->foto_sort > 0) {
               $sort = $data[$i]->foto_sort;
            }

            $this->saveDl($re_id, $data[$i]->artikel_id, $data[$i]->filename, $data[$i]->filetyp, $link, $sort);
            $links[] = $link;
         }
      }
      return $links;
   }

   private function _makeLink($filename) {
      if ($filename == '') {
         $chars = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
         for ($i = 0; $i < 10; $i++) {
//            $filename .= $chars(mt_rand(0, strlen($chars)));
            $filename .= mt_rand(0, strlen($chars));
         }
      }

      return md5(time().$filename);
   }

   private function saveDl($re_id, $artikel_id, $filename, $filetyp, $link, $foto_sort) {
      $sql = "INSERT INTO #__downloads SET `rechnung_id` = $re_id, `artikel_id` = $artikel_id, `filename` = '$filename', `mime_type` = '$filetyp', `link` = '$link', `sort` = $foto_sort, `count` = 0, `last_upload` = '000-00-00 00:00:00', `valid` = ADDDATE(CURRENT_DATE(), INTERVAL ".CONF_DOWNLOAD_DAYS." DAY), `allowed` = 'y'";
      $this->db->query($sql);
      return;
   }
}
