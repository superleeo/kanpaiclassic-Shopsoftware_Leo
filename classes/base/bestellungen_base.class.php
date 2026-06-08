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

class KANPAICLASSIC_bestellungenBASE
{
   public $db;
   public $db_extern;
   public $params;
   public $text;
   public $im_export;
   public $dataArtikel;
   public $dataDetails;
   public $berechnung = null;
   public $nw_zutaten = [];

   function __construct() {
      $this->db         = Control::getDB();
      $this->db_extern  = Control::getExternDB();
      $this->params     = Control::getParams();
      $this->text       = Control::getText();
      $this->berechnung = Control::getBerechnungen();
      $this->im_export  = Control::getImportExport();
   }

   // Rechnungsdaten User lesen
   public function getDetailBestellung($re_id = 0) {
      // Wenn Re-Id nicht übergeben, dann aus POST
      if ($re_id == 0) {
         $re_id = $this->params->postInt('id');
      }

      // Re-Daten lesen
      $lang = $this->params->selected_lang;
      $sql = "SELECT r.*, u.id AS user
                 FROM #__rechnung as r
              LEFT JOIN #__users AS u
                 ON u.id = r.user_id
              WHERE r.id = $re_id";
      $data = $this->db->querySingleObject($sql);

      // Rechnung nicht gefunden
      if (!$data) {
         return false;
      }

      // Länder-Name zur Länder-Id
      $data->land    = Helper::getStaatName($data->staat, $data->staat2, $lang);
      $data->lf_land = Helper::getStaatName($data->lf_staat, $data->lf_staat2, $lang);

      // Brutto, Versand, Zahlart kann direkt aus DB (netto + ust) übernommen werden
      $data->steuer1        = (float)$data->steuer1;
      $data->steuer2        = (float)$data->steuer2;
      $data->steuer3        = (float)$data->steuer3;
      $data->steuersatz1    = (float)$data->steuersatz1;
      $data->steuersatz2    = (float)$data->steuersatz2;
      $data->steuersatz3    = (float)$data->steuersatz3;
      $data->gewerbe        = (int)$data->gewerbe;

      // Zwischensumme / Warenwert
      $data->netto          = (float)$data->netto;
      $data->brutto         = $data->netto + $data->steuer1 + $data->steuer2 + $data->steuer3;

      // Versand
      $data->versand_netto  = (float)$data->versand;
      $data->versand_ust    = round((float)$data->versand_ust, 2);
      $data->versand_brutto = $data->versand_netto + $data->versand_ust;

      // Zahlart
      $data->zahlart_netto  = $data->zahlart_add;
      $data->zahlart_ust    = round((float)$data->zahlart_ust, 2);
      $data->zahlart_brutto = $data->zahlart_netto + $data->zahlart_ust;

      // Berechnung Rabatt
      $data->rabatt_prozent = (float)$data->user_rabatt;
      $data->rabatt_netto   = round(($data->netto * $data->rabatt_prozent / 100), 2);
      $data->rabatt_ust1    = ($data->steuer1 * $data->rabatt_prozent / 100);
      $data->rabatt_ust2    = ($data->steuer2 * $data->rabatt_prozent / 100);
      $data->rabatt_ust3    = ($data->steuer3 * $data->rabatt_prozent / 100);
      $data->rabatt_ust     = round(($data->rabatt_ust1 + $data->rabatt_ust2 + $data->rabatt_ust3), 2);
      $data->rabatt_brutto  = $data->rabatt_netto + $data->rabatt_ust;

      $data->widerruf       = $data->widerruf;

      // Berechnung Gutschrift/Gutschein
      // Nur reduzierte USt
      if ((float)$data->gutschrift == 0 && $data->gutschein_brutto > 0) {
         $data->gutschrift = $data->gutschein_brutto; // Gespeichert:
      }

      // Gutschrift, enthält auch $data->gutschrift_brutto!!! In Admin/Bestellungen geimeinsames Eingabefeld
      $data->gutschrift_brutto = round((float)$data->gutschrift, 2);

      // Nur reduzierte Steuer
      if ($data->steuer1 == 0 && $data->steuer2 != 0 && $data->steuer3 == 0) {
         $data->gutschrift_ust   = round(($data->gutschrift_brutto / (1 + $data->steuersatz2 / 100) * ($data->steuersatz2 / 100)), 2);
         $data->gutschrift_netto = $data->gutschrift_brutto - $data->gutschrift_ust;
      }

      // Normale Steuer
      else if ($data->steuer1 != 0) {
         $data->gutschrift_ust   = round(($data->gutschrift_brutto / (1 + $data->steuersatz1 / 100) * ($data->steuersatz1 / 100)), 2);
         $data->gutschrift_netto = $data->gutschrift_brutto - $data->gutschrift_ust;
      }

      // Keine Steuer
      else {
         $data->gutschrift_netto = $data->gutschrift_brutto;
         $data->gutschrift_ust   = 0.00;
      }

      // Gutschein Achtung: In Gutschrift bereits enthalten
      $data->gutschein_brutto = (float)$data->gutschein_brutto;
      $data->gutschein_ust    = (float)$data->gutschein_steuer;

      $this->dataDetails = $data;
      return true;
   }

   // $mode: kunde -> keine inaktiven Artikel, alle-> alle Artikel
   // $for:  leer: selected_lang, !leer: default_lang
   public function getDetailArtikel($re_id = 0, $mode = 'kunde', $for = '') {
      if ($mode != 'alle') {
         return $this->getDetailArtikelSub($re_id, $mode, $for);
      }

      else {
         return $this->getDetailArtikelSub($re_id, 'admin', $for);
      }
   }

   // Artikel mit Rechnungsdaten zu Re-Id lesen
   public function getDetailArtikelSub($re_id, $mode, $for) {
      $gewerbe = $this->dataDetails->gewerbe;
      $lang    = $this->params->selected_lang;

      if ($for != '') {
         $lang = $this->params->firma['default_lang'];
      }

      if ($re_id == 0) {
         $re_id = $this->params->postInt('id');
      }

      $lang_kunde = $this->db->querySingleValue("SELECT lang_kunde FROM #__rechnung WHERE id = $re_id");

      if ($lang_kunde == '') {
         $lang_kunde = 'deu';
      }

      // Namen Merkmale / Werte sprachabhängig shop/kunde aus DB lesen
      $sql = "SELECT ra.*, r.steuersatz1, r.steuersatz2, r.steuersatz3
                 FROM #__rechnung_artikel AS ra
              LEFT JOIN #__rechnung AS r
                 ON r.id = ra.rechnung_id
              WHERE ra.rechnung_id = $re_id";

      if ($mode != 'admin') {
         $sql .= " AND ra.aktiv = 'y'";
      }

      $data = $this->db->queryAllObjects($sql);

      $img_url = ($this->params->multishop ? \KANPAICLASSIC\Helper::getData('multishop_images') : SHOP_URL).'/'.CONF_PICT_PATH;

      for ( $i = 0; $i < (is_array($data) ? count($data) : 0); $i++) {
         $data[$i]->artikel_netto = (float)$data[$i]->artikel_preis;
         $data[$i]->gewerbe       = $gewerbe;
         $satz                    = 0;

         if (Helper::getData('mail_attach_images_mail', 'n') == 'y') {

             $parent_id = $this->db_extern->querySingleValue("SELECT parent_id FROM #__articles WHERE id = ".$data[$i]->artikel_id);

             $startbild = $this->db_extern->querySingleValue("SELECT startbild FROM #__articles WHERE id = ".$data[$i]->artikel_id);

             $image = "";
             if($startbild == 1){
                 $image = $this->db_extern->querySingleValue("SELECT image FROM #__articles_info WHERE id = ".$parent_id);
             }else if($startbild > 1){ // Bilder über 1 hinaus werden in anderer Tabelle gespeichert
                 $image = $this->db_extern->querySingleValue("SELECT image FROM #__articles_images WHERE parent_id = '".$parent_id."' and sort = '".($startbild-1)."'");
             }

             /*  var_dump($data);echo "<br>";

             echo "SELECT image FROM #__articles_info WHERE id = ".$data[$i]->artikel_id;echo "<br>";

             echo "image is '" . $image.'"';

             die();*/

             $is_img    = true;

             // Kein Bild vorhanden
             if ($image == 'nopic.png' || $image == '') {
                 $image = ADMIN_URL.'/img/nopic.png';
                 $image_td = $image;
                 $is_img = false;
             }

             // Bild auf anderem Server
             else if (substr($image, 0, 4) == 'http') {
                 $image_td = str_replace('.jpg', '', $image).'_td.jpg';
                 $image    = str_replace('/pictures/', '/pictures/original/', $image);
             }

             // Bei Multishop auf anderem Server
             else if ($this->params->multishop) {
                 $image_td = \KANPAICLASSIC\Helper::getData('multishop_images').'/pictures/'.$image.'_tn.jpg';
                 $image    = \KANPAICLASSIC\Helper::getData('multishop_images').'/pictures/original/'.$image.'.jpg';
             }

             // Bild lokal vorhanden
             else {
                 $image_td = $img_url.$image.'_td.jpg?'.time();
                 $image    = $img_url.'original/'.$image.'.jpg?'.time();
             }

             $data[$i]->image = $image ;
             $data[$i]->image_tn = $image_td;
             $data[$i]->is_img = $is_img;

         }

         // Bruttopreis berechnen
         if ((int)$data[$i]->steuersatz == 1) {
            $satz = (float)$data[$i]->steuersatz1;
         }

         else if ((int)$data[$i]->steuersatz == 2) {
            $satz = (float)$data[$i]->steuersatz2;
         }

         else if ((int)$data[$i]->steuersatz == 3) {
            $satz = (float)$data[$i]->steuersatz3;
         }

         if ($gewerbe != 3 && $gewerbe != 2) {
            $data[$i]->artikel_brutto = round((float)$data[$i]->artikel_preis * (1 + $satz / 100), 2);
         }

         // Kleingewerbe immer brutto = netto
         else {
            $data[$i]->artikel_brutto = (float)$data[$i]->artikel_preis;
         }

         $data[$i]->satz = $satz;

         if ((int)$data[$i]->merkmal1 == 0 || (int)$data[$i]->wert1 == 0) {
            $data[$i]->merkmal1       = '';
            $data[$i]->merkmal1_kunde = '';
            $data[$i]->wert1          = '';
            $data[$i]->wert1_kunde    = '';
         }

         else {
            $data[$i]->merkmal1_kunde = $this->db_extern->querySingleValue("SELECT merkmal_$lang_kunde FROM #__merkmale WHERE id = ".$data[$i]->merkmal1);
            $data[$i]->wert1_kunde    = $this->db_extern->querySingleValue("SELECT wert_$lang_kunde FROM #__werte WHERE id = ".$data[$i]->wert1);
            $data[$i]->merkmal1       = $this->db_extern->querySingleValue("SELECT merkmal_$lang FROM #__merkmale WHERE id = ".$data[$i]->merkmal1);
            $data[$i]->wert1          = $this->db_extern->querySingleValue("SELECT wert_$lang FROM #__werte WHERE id = ".$data[$i]->wert1);
         }

         if ((int)$data[$i]->merkmal2 == 0 || (int)$data[$i]->wert2 == 0) {
            $data[$i]->merkmal2       = '';
            $data[$i]->merkmal2_kunde = '';
            $data[$i]->wert2          = '';
            $data[$i]->wert2_kunde    = '';
         }

         else {
            $data[$i]->merkmal2_kunde = $this->db_extern->querySingleValue("SELECT merkmal_$lang_kunde FROM #__merkmale WHERE id = ".$data[$i]->merkmal2);
            $data[$i]->wert2_kunde    = $this->db_extern->querySingleValue("SELECT wert_$lang_kunde FROM #__werte WHERE id = ".$data[$i]->wert2);
            $data[$i]->merkmal2       = $this->db_extern->querySingleValue("SELECT merkmal_$lang FROM #__merkmale WHERE id = ".$data[$i]->merkmal2);
            $data[$i]->wert2          = $this->db_extern->querySingleValue("SELECT wert_$lang FROM #__werte WHERE id = ".$data[$i]->wert2);
         }

         // Für Erstelleung PDF-Anhang Naehrwerte merken
         if (defined('CONF_MODULE_NAEHRWERTE') &&  $data[$i]->mixer != '' && ($data[$i]->naehrwerte != '' || $data[$i]->zutaten != '')) {
            $this->nw_zutaten[] = $data[$i];
         }
      }

      $this->dataArtikel = $data;
      return;
   }

   // Artikel-Liste aus Rechnungen inkl. Zwischensumme für Mail aufbereiten
   // Zuvor muss getDetailBestellung() aufgerufen werden!
   function mailArtikelList($best_id, $sellang = false) {
      // Mail in Shopwährung
      $waehrung = Helper::waehrungText($this->params->firma['waehrung1'], 1);
      $gewerbe  = (int)$this->dataDetails->gewerbe;
      $lang     = $this->params->firma['default_lang'];

      if ($sellang) {
         $lang = $this->dataDetails->lang;
      }

      $html = "<table class='tab1'>\n";
      // Titelzeile
      $html .= "   <tr>\n";
      $html .= "      <td class='td1_1' style='font-weight:700;'>".$this->text->get('artikel', 'best_nr', $lang)."</td>\n";
      $html .= "      <td class='td1_2' style='font-weight:700;'>".$this->text->get('artikel', 'name', $lang)."</td>\n";

      if ($gewerbe == 1) {
         $html .= "      <td class='td1_3' style='text-align:right; font-weight:700;'>".$this->text->get('artikel', $this->params->firma['tax_show'] == 'y' ? 'brutto' : 'netto', $lang)."</td>\n";
         $html .= "      <td class='td1_6' style='text-align:right; font-weight:700;'>".$this->text->get('artikel', 'ust', $lang)."</td>\n";
      }

      else {
         $html .= "      <td class='td1_3' style='text-align:right; font-weight:700;'>".$this->text->get('artikel', 'brutto', $lang)."</td>\n";
         $html .= "      <td class='td1_6' style='text-align:right; font-weight:700;'></td>\n";
      }

      $html .= "      <td class='td1_4' style='text-align:right; font-weight:700;'>".$this->text->get('artikel', 'menge', $lang)."</td>\n";
      $html .= "      <td class='td1_5' style='text-align:right; font-weight:700;'>".$this->text->get('artikel', 'summe', $lang)."</td>\n";
      $html .= "   </tr>\n";
      // Linie
      $html .= "   <tr>\n";

      if ($gewerbe == 1) {
         $html .= "   <td colspan='6'><hr /></td>\n";
      }

      else {
         $html .= "   <td colspan='6'><hr /></td>\n";
      }

      $html .= "   </tr>\n";
      $bg = false;

      if ($this->dataArtikel && count($this->dataArtikel) > 0) {
         foreach ($this->dataArtikel as $data1) {

            if ($data1->aktiv != 'y') {
               continue;
            }

            $stellen              = 0;
            $komma                = 0;
            $is_conf              = false;
            $is_rechner           = false;
            $is_mixer             = false;
            $grundeinheit         = $this->text->get('ge', $data1->grundeinheit, $lang);
            $grundeinheit_rechner = $this->text->get('ge', $data1->grundeinheit_rechner, $lang);

            $ge_text              = $this->text->get('ge', $data1->rechner_einheit, $lang);
            $breite               = (float)$data1->rechner_breite;
            $hoehe                = (float)$data1->rechner_hoehe;
            $tiefe                = (float)$data1->rechner_tiefe;
            $re_mode              = (int)$data1->rechner_mode;

            if (defined('CONF_MODULE_MEGACONFIGURATOR') && $data1->configurator != '') {
               $is_conf = true;
            }

            if ($data1->rechner_check == 'y') {
               $is_rechner = true;
               $komma = (int)$data1->masse_komma;
            }

            // Als Mixer kennzeichnen
            if ($data1->mixer != '') {
               $is_mixer = true;
            }

            if ($data1->masse_check == 'y' && $data1->rechner_check != 'y') {
               $stellen = (int)$data1->masse_komma;
            }

            if ($bg) {
               $bgclass = 'bg_list1';
            }
            else {
               $bgclass = 'bg_list2';
            }
            $bg = !$bg;

            $art_preis = 0.0;

            // Außerhalb EU (Gewerbe) / Ausland alle
            if ($gewerbe == 2) {
               $art_preis = $data1->artikel_preis;
            }

            // Brutto
            else if ($this->params->firma['tax_show'] == 'y' && $gewerbe != 3) {
               $art_preis = $data1->artikel_brutto;
            }

            // Netto
            else {
               $art_preis = $data1->artikel_preis;
            }

            $art_summe = number_format(round($art_preis, 2) * $data1->menge, 2, ',', '.').' '.$waehrung;

            // Start Artikel
            $html .= "   <tr class='$bgclass'>\n";
            $html .= "      <td class='td1_1'>" . Helper::truncate($data1->artikel_nummer, 20) . "</td>\n";
            $html .= "      <td class='td1_2'>".$data1->name_kunde."&nbsp;".$data1->wert1."&nbsp;".$data1->wert2."</td>\n";
            $html .= "      <td class='td1_3' style='text-align:right;'>".number_format($art_preis, 2, ',', '.')." ".$waehrung."</td>\n";

            if ($gewerbe == 1) {
               $html .= "      <td class='td1_6' style='text-align:right;'>".$data1->satz."%</td>\n";
            }

            else {
               $html .= "      <td class='td1_6' style='text-align:right;'></td>\n";
            }

            if ($data1->masse_check == 'y') {
               $html .= "      <td class='td1_4' style='text-align:right;'>".number_format($data1->menge, $stellen, ',', '.').(!$is_rechner ? ' '.$grundeinheit_rechner : '')."</td>\n";
            }

            else {
               $html .= "      <td class='td1_4' style='text-align:right;'>".number_format($data1->menge, $stellen, ',', '.')."</td>\n";
            }

            $html .= "      <td class='td1_5' style='text-align:right;'>$art_summe</td>\n";
            $html .= "   </tr>\n";

            if (Helper::getData('mail_attach_images_mail', 'n') == 'y') {

                if($data1->is_img){

                    $image_tn = $data1->image_tn;
                    $image_orig = $data1->image;

                    $colspan = 5;
                    if ($gewerbe == 1 || $gewerbe == 2) { $colspan = 6; }

                    $html .= "   <tr class='$bgclass'>\n";
                    $html .= "      <td colspan='$colspan' style='width:88px;max-width:88px;'><img style='width:88px' width='88' alt='' src='$image_tn'></td>\n";
                    $html .= "   </tr>\n";

                }

            }

            if ($data1->motiv_upload_text != '') {
               $html .= "   <tr class='$bgclass'>\n";
               $html .= "      <td class='td1_1'></td>\n";
               $html .= "      <td class='td1_2'>".$data1->motiv_upload_text."</td>\n";
               $html .= "      <td class='td1_3' style='text-align:right;'></td>\n";

               if ($gewerbe == 1 || $gewerbe == 2) {
                  $html .= "      <td class='td1_6' style='text-align:right;'></td>\n";
               }

               $html .= "      <td class='td1_4' style='text-align:right;'></td>\n";
               $html .= "      <td class='td1_5' style='text-align:right;'></td>\n";
               $html .= "   </tr>\n";
            }

            // Artikel-Mixer und Kategorie-Mixer
            if ($is_mixer) {
               $mix = json_decode($data1->mixer);

               if (is_array($mix) && count($mix) > 0) {
                  $mix_prozent = 0;
                  $mix_gewicht = 0;
                  $mix_einheit = '';
                  $kat_mixer   = false;

                  $html .= '   <tr>'.CR;
                  $html .= '      <td style="width:95px"></td>'.CR;
                  $html .= '      <td style="width:465px">'.CR;
                  $html .= '         <table style="width:465px">'.CR;

                  for ($m = 0; $m < count($mix); $m++) {
                     // Nur Mixer-Kategorie hat steuersatz
                     if (isset($mix[$m]->steuersatz)) {
                        $kat_mixer   = true;
                     }

                     if (isset($mix[$m]->value)) {
                        $mix_prozent  += $mix[$m]->menge;
                        $mix_gewicht += (isset($mix[$m]->gewicht) ? $mix[$m]->gewicht : 0);

                        if (isset($mix[$m]->einheit)) {
                           $mix_einheit  = $mix[$m]->einheit;
                        }
                     }

                     $html .= '            <tr>'.CR;
                     $html .= '               <td style="width:365px; color:#888888;">'.$mix[$m]->artikel_name2.'</td>'.CR;
                     $html .= '               <td style="width:100px; color:#888888; text-align:right;">'.(isset($mix[$m]->value) ? $mix[$m]->value : $mix[$m]->menge).'</td>'.CR;
                     $html .= '            <tr>'.CR;
                  }

                  // Nur Admin -> jetzt immer
                  // if ($mix_prozent >= 0 && isset($_SESSION['show_mix_menge'])) {
                  //if ($mix_prozent >= 0) {
                  // Nur bei Mixer-Artikel
                  if (!$kat_mixer && $mix_prozent >= 0) {
                     $show_einheit = 'g';

                     if ($mix_gewicht > 2000) {
                        $mix_gewicht = number_format($mix_gewicht, 2, '', '');

                        if ($mix_einheit === 'g') {
                           $show_einheit = 'Kg';
                        }

                        else {
                           $show_einheit = 'l';
                        }
                     }

                     else {
                        $mix_gewicht = round($mix_gewicht);

                        if ($mix_einheit == 'l') {
                           $show_einheit = 'ml';
                        }
                     }

                     $html .= '            <tr>'.CR;
                     $html .= '               <td style="width:365px; color:#888888;"> </td>'.CR;
                     //$html .= '               <td style="width:100px; color:#888888;'.($mix_prozent !== 100 ? 'font-weight:bold;' : '').'">'.$mix_prozent.'%</td>'.CR;
                     $html .= '               <td style="width:100px; color:#888888; text-align:right;'.($mix_prozent !== 100 ? 'font-weight:bold;' : '').'">= '.($mix_gewicht >0 ? $mix_gewicht.$show_einheit : '').' ('.$mix_prozent.'%)</td>'.CR;
                     $html .= '            <tr>'.CR;
                  }

                  unset($_SESSION['show_mix_menge']);

                  $html .= '         </table>'.CR;
                  $html .= '      </td>'.CR;
                  $html .= '      <td style="width:100px"></td>'.CR;
                  $html .= '   </tr>'.CR;
               }
            }

            if (defined('CONF_MODULE_MATRIX') && $data1->preismatrix != '') {
               $matrix = json_decode($data1->preismatrix);
               $html .= "   <tr class='$bgclass'>\n";
               $html .= "      <td class='td1_1'></td>\n";
               $html .= "      <td class='td1_2'>".$matrix->{'breite_'.$lang}.' x '.$matrix->{'hoehe_'.$lang}.' ('.$matrix->{'einheit_'.$lang}.') : '.number_format($matrix->breite, $matrix->komma, ',', '').' x '.number_format($matrix->hoehe, $matrix->komma, ',', '').'</td>';
               $html .= "      <td class='td1_3' style='text-align:right;'></td>\n";

               if ($gewerbe == 1 || $gewerbe == 2) {
                  $html .= "      <td class='td1_6' style='text-align:right;'></td>\n";
               }

               $html .= "      <td class='td1_4' style='text-align:right;'></td>\n";
               $html .= "      <td class='td1_5' style='text-align:right;'></td>\n";
               $html .= "   </tr>\n";
            }

            if ($is_rechner) {
               $html .= "   <tr class='$bgclass'>\n";
               $html .= "      <td class='td1_1'></td>\n";
               $html .= "      <td class='td1_2'>".$this->text->get('ge', 'mode'.$re_mode, $lang)." ".($re_mode == 1 ? number_format($breite, $komma, ',', '.')." ".$ge_text :
                                                      number_format($breite, $komma, ',', '.')." ".$ge_text."
                                                      x ".number_format($hoehe, $komma, ',', '.')." ".$ge_text.
                                                      ((int)$re_mode > 2 ? " x ".number_format($tiefe, $komma, ',', '.')." ".$ge_text : '')."
                                                      = ".number_format($breite * $hoehe *$tiefe, $komma, ',', '.')." ".$grundeinheit_rechner
                                                    )."
                                                   </td>\n";
               $html .= "      <td class='td1_3' style='text-align:right;'></td>\n";

               if ($gewerbe == 1 || $gewerbe == 2) {
                  $html .= "      <td class='td1_6' style='text-align:right;'></td>\n";
               }

               $html .= "      <td class='td1_4' style='text-align:right;'></td>\n";
               $html .= "      <td class='td1_5' style='text-align:right;'></td>\n";
               $html .= "   </tr>\n";
            }

            // Modul Megakonfigurator
            if ($is_conf) {
               $configurator = Control::getModuleConfigurator();
               $conf         = json_decode($data1->configurator_kunde, true);
               $texte        = null;

               if (isset($conf['texte'])) {
                  $texte = $conf['texte'];
                  unset($conf['texte']);
               }

               $c            = (is_array($conf) ? count($conf) : 0) - 1;

               for ($k = 0; $k <= $c; $k++) {
                  if ($configurator->configLineToText($conf[$k], true) !='') {
                     $html .= "   <tr class='$bgclass'>\n";
                     $html .= "      <td class='td1_1'></td>\n";
                     $html .= "      <td class='td1_2'>".$configurator->configLineToText($conf[$k], true)."</td>\n";
                     $html .= "      <td class='td1_3' style='text-align:right;'></td>\n";

                     if ($gewerbe != 3) {
                        $html .= "      <td class='td1_6' style='text-align:right;'></td>\n";
                     }

                     $html .= "      <td class='td1_4' style='text-align:right;'></td>\n";
                     $html .= "      <td class='td1_5' style='text-align:right;'></td>\n";
                     $html .= "   </tr>\n";
                  }
               }

               if ($texte !== null) {
                  foreach($texte as $t) {
                     $html .= "   <tr class='$bgclass'>\n";
                     $html .= "      <td class='td1_1'></td>\n";
                     $html .= "      <td class='td1_2' colspan='3'>".$configurator->textById($t['text_id'], $this->params->selected_lang).": ".$t['text']."</td>\n";
                     $html .= "      <td class='td1_5'></td>\n";
                     $html .= "   </tr>\n";
                  }
               }
            }

            //
            if ((int)$data1->lager_zeit > 0) {
               $html .= "   <tr class='$bgclass'>\n";
               $html .= "      <td class='td1_1'></td>\n";
               $html .= "      <td class='td1_2'>".$this->text->get('art_detail', 'lieferzeit2').':&nbsp;'.$data1->lager_zeit.'&nbsp;'.$this->text->get('art_detail', 'tage')."</td>\n";
               $html .= "      <td class='td1_3' style='text-align:right;'></td>\n";
               if ($gewerbe != 3) {
                  $html .= "      <td class='td1_6' style='text-align:right;'></td>\n";
               }
               $html .= "      <td class='td1_4' style='text-align:right;'></td>\n";
               $html .= "      <td class='td1_5' style='text-align:right;'></td>\n";
               $html .= "   </tr>\n";
            }

            $html .= "   <tr>\n";
            // Ende Artikel

            // Linie
            if ($gewerbe == 1) {
               $html .= "   <td colspan='6'><hr /></td>\n";
            }

            else {
               $html .= "   <td colspan='6'><hr /></td>\n";
            }

            $html .= "   </tr>\n";
         } // foreach
      }

      $html .= "</table>\n";

      return $html;
   }

   // Summen, (Steuer usw., Gesamtsumme) für Mail aufbereiten
   function mailSummenList($best_id, $sellang = false) {
      // Mail in Shopwährung
      $waehrung = Helper::waehrungText($this->params->firma['waehrung1'], 1);
      $data     = $this->dataDetails;
      $lang     = 'deu';

      $isAbholung =  $data->abholung_checkbox == 'y';

      if ($sellang) {
         $lang = $this->dataDetails->lang_kunde;
      }

      $zahlarttext = Helper::getZahlartText((int)$data->zahlungsart, $lang).' '.$this->text->get('zahlart', 'leer', $lang);

      $html = '<table class="tab2">';

      // Brutto-Preise / Endkunden
      if ($this->params->firma['tax_show'] == 'y' || $this->params->firma['kleingewerbe'] == 'y') {
         // Zwischensumme
         $html .= "   <tr>\n";
         $html .= "      <td class='td2_1' style='text-align:right;'>".$this->text->get('artikel', 'zw_summe', $lang)."</td>\n";
         $html .= "      <td class='td2_2' style='text-align:right;'>".number_format($data->brutto, 2, ',', '.')." ".$waehrung."</td>\n";
         $html .= "   </tr>\n";

         // Leerzeile
         $html .= "   <tr>\n";
         $html .= "      <td class='td2_1' style='text-align:right;'></td>\n";
         $html .= "      <td class='td2_2' style='text-align:right;'><hr /></td>\n";
         $html .= "   </tr>\n";

         // Rabatt
         if ((float)$data->rabatt > 0) {
            $html .= "   <tr>\n";
            $html .= "      <td class='td2_1' style='text-align:right;'>".$this->text->get('artikel', 'rabatt', $lang)."</td>\n";
            $html .= "      <td class='td2_2' style='text-align:right;'>-".number_format($data->rabatt_brutto, 2, ',', '.')." ".$waehrung."</td>\n";
            $html .= "   </tr>\n";
         }

         // Versandkosten
         $html .= "   <tr>\n";

         if(!$isAbholung){
             $html .= "      <td class='td2_1' style='text-align:right;'>".$this->text->get('artikel', 'versand', $lang)."</td>\n";
         }else{
             $html .= "      <td class='td2_1' style='text-align:right;'>".$this->text->get('warenkorb', 'abholung', $lang)."</td>\n";
         }


         $html .= "      <td class='td2_2' style='text-align:right;'>".number_format($data->versand_brutto, 2, ',', '.')." ".$waehrung."</td>\n";
         $html .= "   </tr>\n";

         // Versandart
         $html .= "   <tr>\n";
         $html .= "      <td class='td2_1' style='text-align:right;'>".$zahlarttext."</td>\n";
         $html .= "      <td class='td2_2' style='text-align:right;'>".number_format($data->zahlart_brutto, 2, ',', '.')." ".$waehrung."</td>\n";
         $html .= "   </tr>\n";

         // Gutschrift
         if ($data->gutschrift_brutto > 0 ) {
            $html .= "   <tr>\n";
            $html .= "      <td class='td2_1' style='text-align:right;'>".$this->text->get('artikel', 'gutschrift', $lang)."</td>\n";
            $html .= "      <td class='td2_2' style='text-align:right;'>-".number_format($data->gutschrift_brutto, 2, ',', '.')." ".$waehrung."</td>\n";
            $html .= "   </tr>\n";
         }

         // Endsumme
         $html .= "   <tr>\n";
         $html .= "      <td class='td2_1' style='text-align:right;'></td>\n";
         $html .= "      <td class='td2_2' style='text-align:right;'><hr /></td>\n";
         $html .= "   </tr>\n";

         $html .= "   <tr>\n";
         $html .= "      <td class='td2_1' style='text-align:right; font-weight:700;'>".$this->text->get('artikel', 'g_summe', $lang)."</td>\n";
         $html .= "      <td class='td2_2' style='text-align:right; font-weight:700;'>".number_format($data->netto + $data->steuer1 + $data->steuer2 + $data->steuer3 + $data->versand_brutto + $data->zahlart_brutto - $data->rabatt_brutto - $data->gutschrift_brutto, 2, ',', '.')." ".$waehrung."</td>\n";
         $html .= "   </tr>\n";

         // Steuern
         if ((int)$data->gewerbe == 1) {
            $steuer1 = $data->steuer1 - $data->rabatt_ust1;
            $steuer2 = $data->steuer2 - $data->rabatt_ust2;
            $steuer3 = $data->steuer3 - $data->rabatt_ust3;

            // Nur reduzierte USt
            if ($data->steuer1 == 0 && $data->steuer2 > 0 && $data->steuer3 == 0) {
               $steuer2 += $data->versand_ust + $data->zahlart_ust - $data->gutschrift_ust;
            }

            else {
               $steuer1 += $data->versand_ust + $data->zahlart_ust - $data->gutschrift_ust;
            }

            if ($steuer1 > 0) {
               $steuer = $this->text->get('artikel', ($this->params->firma['tax_show'] == 'y' ? 'ust_lang' : 'ust'), $lang).'&nbsp;';
               $steuer .= number_format($data->steuersatz1, 2, ',', '.').'%';
               $html .= "   <tr>\n";
               $html .= "      <td class='td2_1' style='text-align:right;'>".$steuer."</td>\n";
               $html .= "      <td class='td2_2' style='text-align:right;'>".number_format($steuer1, 2, ',', '.')." ".$waehrung."</td>\n";
               $html .= "   </tr>\n";
            }

            if ($steuer2 > 0) {
               $steuer = $this->text->get('artikel',($this->params->firma['tax_show'] == 'y' ? 'ust_lang' : 'ust'), $lang).'&nbsp;';
               $steuer .= number_format($data->steuersatz2, 2, ',', '.').'%';
               $html .= "   <tr>\n";
               $html .= "      <td class='td2_1' style='text-align:right;'>".$steuer."</td>\n";
               $html .= "      <td class='td2_2' style='text-align:right;'>".number_format($steuer2, 2, ',', '.')." ".$waehrung."</td>\n";
               $html .= "   </tr>\n";
            }

            if ((float)$data->steuer3 > 0) {
               $steuer = $this->text->get('artikel', ($this->params->firma['tax_show'] == 'y' ? 'ust_lang' : 'ust'), $lang).'&nbsp;';
               $steuer .= number_format($data->steuersatz3, 2, ',', '.').'%';
               $html .= "   <tr>\n";
               $html .= "      <td class='td2_1' style='text-align:right;'>".$steuer."</td>\n";
               $html .= "      <td class='td2_2' style='text-align:right;'>".number_format($steuer3, 2, ',', '.')." ".$waehrung."</td>\n";
               $html .= "   </tr>\n";
            }
         }

         if ((int)$data->gewerbe == 3) {
            $html .= "   <tr>\n";
            $html .= "      <td class='td2_1' style='text-align:right;'></td>\n";
            $html .= "      <td class='td2_2' style='text-align:right;'></td>\n";
            $html .= "   </tr>\n";
         }

         if ((int)$data->waehrung_id != 1) {
            // Endsumme Kundenwährung
            $html .= "   <tr>\n";
            $html .= "      <td class='td2_1' style='text-align:right;'></td>\n";
            $html .= "      <td class='td2_2' style='text-align:right;'><hr /></td>\n";
            $html .= "   </tr>\n";

            $html .= "   <tr>\n";
            $html .= "      <td class='td2_1' style='text-align:right; font-weight:700;'>".$this->text->get('artikel', 'g_summe', $lang)."</td>\n";
            $html .= "      <td class='td2_2' style='text-align:right; font-weight:700;'>".Helper::number_format($data->netto + $data->steuer1 + $data->steuer2 + $data->steuer3 + $data->versand_brutto + $data->zahlart_brutto - $data->rabatt_brutto - $data->gutschrift_brutto, 2, ',', '.', (float)$data->w_faktor)." ".Helper::waehrungText($this->params->firma['waehrung'.$data->waehrung_id], 1)."</td>\n";
            $html .= "   </tr>\n";
         }
      }

      // Netto-Preise / B2B-Kunden
      else {
         // Zwischensumme
         $html .= "   <tr>\n";
         $html .= "      <td class='td2_1' style='text-align:right;'>".$this->text->get('artikel', 'zw_summe', $lang)."</td>\n";
         $html .= "      <td class='td2_2' style='text-align:right;'>".number_format($data->netto, 2, ',', '.')." ".$waehrung."</td>\n";
         $html .= "   </tr>\n";

         $html .= "   <tr>\n";
         $html .= "      <td class='td2_1' style='text-align:right;'></td>\n";
         $html .= "      <td class='td2_2' style='text-align:right;'><hr /></td>\n";
         $html .= "   </tr>\n";

         // Rabatt
         if ((float)$data->rabatt > 0) {
            $html .= "   <tr>\n";
            $html .= "      <td class='td2_1' style='text-align:right;'>".$this->text->get('artikel', 'rabatt', $lang)."</td>\n";
            $html .= "      <td class='td2_2' style='text-align:right;'>-".number_format($data->rabatt_netto, 2, ',', '.')." ".$waehrung."</td>\n";
            $html .= "   </tr>\n";
         }

         // Versandkosten
         $html .= "   <tr>\n";

         if(!$isAbholung){
             $html .= "      <td class='td2_1' style='text-align:right;'>".$this->text->get('artikel', 'versand', $lang)."</td>\n";
         }else{
             $html .= "      <td class='td2_1' style='text-align:right;'>".$this->text->get('warenkorb', 'abholung', $lang)."</td>\n";
         }

         $html .= "      <td class='td2_2' style='text-align:right;'>".number_format($data->versand_netto, 2, ',', '.')." ".$waehrung."</td>\n";
         $html .= "   </tr>\n";

         // Versandart
         $html .= "   <tr>\n";
         $html .= "      <td class='td2_1' style='text-align:right;'>".$zahlarttext."</td>\n";
         $html .= "      <td class='td2_2' style='text-align:right;'>".number_format($data->zahlart_netto, 2, ',', '.')." ".$waehrung."</td>\n";
         $html .= "   </tr>\n";

         // USt anzeigen
         if ((int)$data->gewerbe == 1) {
            $steuer1 = $data->steuer1 - $data->rabatt_ust1;
            $steuer2 = $data->steuer2 - $data->rabatt_ust2;
            $steuer3 = $data->steuer3 - $data->rabatt_ust3;

            // Nur reduzierte USt
            if ($data->steuer1 == 0 && $data->steuer2 > 0 && $data->steuer3 == 0) {
               $steuer2 += $data->versand_ust + $data->zahlart_ust - $data->gutschrift_ust;
            }

            else {
               $steuer1 += $data->versand_ust + $data->zahlart_ust - $data->gutschrift_ust;
            }

            if ($steuer1 > 0) {
               $steuer = $this->text->get('artikel',($this->params->firma['tax_show'] == 'y' ? 'ust_lang' : 'ust'), $lang).'&nbsp;'.number_format($data->steuersatz1, 2, ',', '.').'%';
               $html .= "   <tr>\n";
               $html .= "      <td class='td2_1' style='text-align:right;'>".$steuer."</td>\n";
               $html .= "      <td class='td2_2' style='text-align:right;'>".number_format($steuer1, 2, ',', '.')." ".$waehrung."</td>\n";
               $html .= "   </tr>\n";
            }

            if ($steuer2 > 0) {
               $steuer = $this->text->get('artikel', ($this->params->firma['tax_show'] == 'y' ? 'ust_lang' : 'ust'), $lang).'&nbsp;';
               $steuer .= number_format($data->steuersatz2, 2, ',', '.').'%';
               $html .= "   <tr>\n";
               $html .= "      <td class='td2_1' style='text-align:right;'>".$steuer."</td>\n";
               $html .= "      <td class='td2_2' style='text-align:right;'>".number_format($steuer2, 2, ',', ',')." ".$waehrung."</td>\n";
               $html .= "   </tr>\n";
            }

            if ((float)$data->steuer3 > 0) {
               $steuer = $this->text->get('artikel',($this->params->firma['tax_show'] == 'y' ? 'ust_lang' : 'ust'), $lang).'&nbsp;';
               $steuer .= number_format($data->steuersatz3, 2, ',', '.').'%';
               $html .= "   <tr>\n";
               $html .= "      <td class='td2_1' style='text-align:right;'>".$steuer."</td>\n";
               $html .= "      <td class='td2_2' style='text-align:right;'>".number_format($steuer3, 2, ',', '.')." ".$waehrung."</td>\n";
               $html .= "   </tr>\n";
            }
         }

         if ((int)$data->gewerbe == 3) {
            $html .= "   <tr>\n";
            $html .= "      <td class='td2_1' style='text-align:right;'></td>\n";
            $html .= "      <td class='td2_2' style='text-align:right;'></td>\n";
            $html .= "   </tr>\n";
         }

         if ($data->gutschrift > 0) {
            $html .= "   <tr>\n";
            $html .= "      <td class='td2_1' style='text-align:right;'>".$this->text->get('artikel', 'gutschrift', $lang)."</td>\n";
            $html .= "      <td class='td2_2' style='text-align:right;'>-".number_format($data->gutschrift_netto, 2, ',', '.')." ".$waehrung."</td>\n";
            $html .= "   </tr>\n";
         }

         $html .= "   <tr>\n";
         $html .= "      <td class='td2_1' style='text-align:right;'></td>\n";
         $html .= "      <td class='td2_2' style='text-align:right;'><hr /></td>\n";
         $html .= "   </tr>\n";

         $html .= "   <tr>\n";
         $html .= "      <td class='td2_1' style='text-align:right; font-weight:700;'>".$this->text->get('artikel', 'g_summe', $lang)."</td>\n";
         $html .= "      <td class='td2_2' style='text-align:right; font-weight:700;'>".number_format($data->netto + $data->steuer1 + $data->steuer2 + $data->steuer3 + $data->versand_brutto + $data->zahlart_brutto - $data->rabatt_brutto - $data->gutschrift_brutto, 2, ',', '.')." ".$waehrung."</td>\n";
         $html .= "   </tr>\n";

         if ((int)$data->waehrung_id != 1) {
            $html .= "   <tr>\n";
            $html .= "      <td class='td2_1' style='text-align:right; font-weight:700;'>".$this->text->get('artikel', 'g_summe', $lang)."</td>\n";
            $html .= "      <td class='td2_2' style='text-align:right; font-weight:700;'>".Helper::number_format($data->netto + $data->steuer1 + $data->steuer2 + $data->steuer3 + $data->versand_brutto + $data->zahlart_brutto - $data->rabatt_brutto - $data->gutschrift_brutto, 2, ',', '.', (float)$data->w_faktor)." ".Helper::waehrungText($this->params->firma['waehrung'.$data->waehrung_id], 1)."</td>\n";
            $html .= "   </tr>\n";
         }
      }
      $html .= "</table>\n";

      return $html;
   }

   // Artikel in rechnung_articles speichern
   protected function _setArticle($re_id, $shop_lang, $kunde_lang, $wk) {
      $artikel_id = $wk['art_id'];
      $artikel    = null;
      $artikel    = Control::getArticles();
      $data       = null;

      // Artikeldaten aus DB lesen
      // Normaler Artikel
      if ($wk['mixer'] == '') {
         $data = $artikel->getArticleById($wk['art_id'], '', '', (int)$wk['foto_set'], (int)$wk['foto_sort']);
         $data->mixer  = '';
      }

      else {
         // Kategorie-Mixer
         if ((int)$wk['cat_id'] > 0) {
            $mixer1 = Control::getModuleMixerKategorie();
            $data = $mixer1->getArticleById($wk['cat_id'], $this->params->selected_lang, $this->params->selected_lang, (int)$wk['foto_set'], (int)$wk['foto_sort'], false, $wk['mixer']);
         }

         // Artikel-Mixer
         else {
            $mixer2 = Control::getModuleMixerArtikel();
            $data = $mixer2->getArticleById($wk['art_id'], $this->params->selected_lang, $this->params->selected_lang, (int)$wk['foto_set'], (int)$wk['foto_sort'], false, $wk['mixer']);
         }
      }

      if ($data) {
         $configurator_shop  = '';
         $configurator_kunde = '';

         if (defined('CONF_MODULE_MEGACONFIGURATOR') && $wk['configurator'] != '') {
            $conf       = json_decode($wk['configurator']);
            $conf_texte = null;

            if (is_object($conf)) {
               if (isset($conf->texte)) {
                  $conf_texte = $conf->texte;
                  unset($conf->texte);
               }
            }

            $conf  = (array)$conf;
            $conf2 = [];

            if (is_array($conf)) {
               foreach($conf as $c) {
                  $conf2[] = $c;
               }
            }

            $conf       = $conf2;
            $conf_shop  = $conf;
            $conf_kunde = $conf;

            for ($i = 0; $i < count($conf); $i++) {
               // Konfigurator mit Text
               if ($conf[$i][0] === null || is_array($conf[$i][0])) {
                  continue;
               }

               $merkmale = $this->db->querySingleObject("SELECT merkmal_$shop_lang AS merkmal_shop, merkmal_$kunde_lang AS merkmal_kunde FROM #__configurator_merkmale WHERE id = ".$conf[$i][0]);

               $conf_shop[$i][0]  = $merkmale->merkmal_shop;
               $conf_kunde[$i][0] = $merkmale->merkmal_kunde;

               for ($j = 0; $j < count($conf[$i][1]); $j++) {
                  $wert                     = $conf[$i][1][$j][0];
                  $werte                    = $this->db->querySingleObject("SELECT wert_$shop_lang AS wert_shop, wert_$kunde_lang AS wert_kunde FROM #__configurator_werte WHERE id = $wert");
                  $conf_shop[$i][1][$j][0]  = $werte->wert_shop;
                  $conf_kunde[$i][1][$j][0] = $werte->wert_kunde;
               }
            }

            if ($conf_texte !== null) {
               $conf_shop = array_merge($conf_shop, ['texte' => $conf_texte]);
               $conf_kunde['texte'] = $conf_texte;
            }

            $configurator_shop  = json_encode($conf_shop);
            $configurator_kunde = json_encode($conf_kunde);
         }

         $grundeinheit_rechner = $data->grundeinheit_rechner;
         $rechner_check        = 'n';
         $rechner_breite       = $wk['rechner_breite'];
         $rechner_hoehe        = $wk['rechner_hoehe'];
         $rechner_tiefe        = $wk['rechner_tiefe'];
         $rechner_mode         = ($wk['rechner_mode'] == 100 ? $data->rechner_mode : $wk['rechner_mode']);
         $rechner_einheit      = $wk['rechner_einheit'];
         $preismatrix          = $wk['preismatrix'];
         $rechner_menge        = round($wk['rechner_breite'] * $wk['rechner_hoehe'] * $wk['rechner_tiefe'], (int)$data->masse_komma);

         if ($wk['rechner_mode'] == 100) {
            $rechner_mode    = $data->rechner_mode;
            $rechner_einheit = $data->grundeinheit_rechner;

            $laenge = (strlen($rechner_einheit) - 1);

            if (strlen($rechner_einheit) && ($rechner_einheit[$laenge] == '2' || $rechner_einheit[$laenge] == '3')) {
               $rechner_einheit = substr($rechner_einheit, 0, -1);
            }
         }

         if (isset($wk['rechner_check'])) {
            $rechner_check     = $wk['rechner_check'];
         }

         else if ($data->rechner_check == 'y') {
            $rechner_check     = 'y';
            $rechner_breite    = 1;
            $rechner_hoehe     = 1;
            $rechner_tiefe     = 1;
            $rechner_menge     = 1;
            $rechner_einheit   = $data->grundeinheit_rechner;

            if (strlen($rechner_einheit) && ($rechner_einheit[(strlen($rechner_einheit) - 1)] == '2' || $rechner_einheit[(strlen($rechner_einheit) - 1)] == '3')) {
               $rechner_einheit = substr($rechner_einheit, 0, -1);
            }
         }

         else {

         }

         $artikel_menge       = $wk['art_menge'];
         $motiv_upload_name   = $wk['motiv_upload_name'];
         $motiv_upload_text   = $wk['motiv_upload_text'];
         $configurator        = $wk['configurator'];

         $data->artikel_menge = $artikel_menge;
         $data->rechner_menge = $rechner_menge;
         $data->rechner_check = $rechner_check;
         $data->preis         = 0;
         $data->wk_id         = $wk['wk_id'];
         $data->configurator  = $wk['configurator'];
         $data->preismatrix   = $wk['preismatrix'];

         // Preis berechnen ($preise = $data / $wk_back in berechnungen )
         $preise              = $this->berechnung->berechneWkArtikel([$data], $data->haendler_id, false, false);
         $preis               = $preise[0]->preis_netto;
         $lagermenge          = (int)$data->menge;
         $menge               = $artikel_menge;
         $lager_zeit          = 0;

         $mixer_json          = '';
         $nw_json             = '';
         $zutaten_json        = '';
         $cat_id              = 0;

         if ($data->mixer != '') {
            $mixer_json = json_encode($data->mixer);
            $cat_id     = $data->cat_id;
         }

         if ($data->naehrwerte != '') {
            $nw_json = json_encode($data->naehrwerte);
         }

         if ($data->zutaten != '') {
            $zutaten_json = json_encode($data->zutaten);
         }

         if ($lagermenge < $menge && $this->params->firma['lager_abziehen'] == 'y' && $this->params->firma['lager_leer'] == 'y' && $this->params->firma['lager_abziehen'] == 'y') {
            $lager_zeit = $this->params->firma['lager_zeit'];
         }





         // und für Rechnungsdaten speichern
         $sql = "INSERT INTO #__rechnung_artikel SET
                  `rechnung_id`          = $re_id,
                  `artikel_id`           = $artikel_id,
                  `cat_id`               = $cat_id,
                  `artikel_nummer`       = '$data->art_nr',
                  `menge`                = '".$artikel_menge."',
                  `lager_zeit`           = '".$lager_zeit."',
                  `masse_check`          = '$data->masse_check',
                  `masse_komma`          = '$data->masse_komma',
                  `artikel_preis`        = '".$preis."',
                  `preis_wk`             = '".$data->netto."',
                  `steuersatz`           = $data->steuersatz,
                  `name_shop`            = '".$this->db->escape($data->artikel_name)."',
                  `desc_shop`            = '".$this->db->escape($data->artikel_text)."',
                  `name_kunde`           = '".$this->db->escape($data->artikel_name2)."',
                  `desc_kunde`           = '".$this->db->escape($data->artikel_text2)."',
                  `merkmal1`             = $data->merkmal1,
                  `wert1`                = $data->wert1,
                  `merkmal2`             = $data->merkmal2,
                  `wert2`                = $data->wert2,
                  `grundeinheit`         = '$data->grundeinheit',
                  `ge_netto`             = '$data->ge_netto',
                  `ge_netto_aktiv`       = '$data->ge_netto_aktiv',
                  `versand_preis`        = '$data->versand_preis',
                  `staffelung`           = '$data->staffelung',
                  `gewicht`              = '$data->gewicht',
                  `filename`             = '$data->filename',
                  `filetyp`              = '$data->filetyp',
                  `foto_set`             = ".$data->foto_set.",
                  `foto_sort`            = ".$wk['foto_sort'].",
                  `motiv_upload_name`    = '".$this->db->escape($motiv_upload_name)."',
                  `motiv_upload_text`    = '".$this->db->escape($motiv_upload_text)."',
                  `configurator`         = '".$this->db->escape($configurator_shop)."',
                  `configurator_kunde`   = '".$this->db->escape($configurator_kunde)."',
                  `configurator_wk`      = '".$this->db->escape($configurator)."',
                  `grundeinheit_rechner` = '$grundeinheit_rechner',
                  `rechner_check`        = '$rechner_check',
                  `rechner_breite`       = '$rechner_breite',
                  `rechner_hoehe`        = '$rechner_hoehe',
                  `rechner_tiefe`        = '$rechner_tiefe',
                  `rechner_mode`         = '$rechner_mode',
                  `rechner_einheit`      = '$rechner_einheit',
                  `preismatrix`          = '".$this->db->escape($preismatrix)."',
                  `mixer`                = '".$this->db->escape($mixer_json)."',
                  `naehrwerte`           = '".$this->db->escape($nw_json)."',
                  `zutaten`              = '".$this->db->escape($zutaten_json)."'";

         $query = $this->db->query($sql);

         if ($rechner_menge == 0) {
            $rechner_menge = 1;
         }

         // Bei Fotoartikel Lager nicht berücksichtigen
         if ((int)$data->foto_set == 0 || $wk['foto_sort'] == 0) {
            // Bestellte Artikel von Lagerbestand abziehen (lager_abziehen = y), Lagermenge korrigieren
            if ($this->params->firma['lager_abziehen'] == 'y') {
               $sql = "UPDATE #__articles SET menge = (menge - $artikel_menge * $rechner_menge) WHERE id = $artikel_id";
               $query = $this->db_extern->query($sql);
            }

            // Artikel deaktivieren, wenn Lagerbestand leer ist (lager_deaktiviet = y)
            if ($this->params->firma['lager_deaktiviert'] == 'y' && $menge >= $lagermenge) {
               $sql = "UPDATE #__articles SET online = 'n' WHERE id = $artikel_id";
               $query = $this->db_extern->query($sql);
            }
         }
      }

      if ($data->naehrwerte_check == 'y') {

      }

      return;
   }

   public function getArtikelnummern($best_id = 0) {
      $back = '';

      if ($best_id != 0) {
         $data = $this->db->queryAllObjects("SELECT artikel_nummer FROM #__rechnung_artikel WHERE rechnung_id = $best_id");

         if ($data) {
            $first = true;
            foreach ($data as $d) {
               if ($first) {
                  $first = false;
                  $back .= $d->artikel_nummer;
               }

               else {
                  $back .= ', '.$d->artikel_nummer;
               }
            }
         }
      }

      return $back;
   }

}
