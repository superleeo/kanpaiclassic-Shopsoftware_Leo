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

class KANPAICLASSIC_versandart
{
   public $db;
   public $params;
   public $text;
   private $region = '';
   private $versand_1 = [];
   private $versand_2 = [];
   private $versand_3 = [];

   function __construct() {
      $this->db = Control::getDB();
      $this->params = Control::getParams();
      $this->text = Control::getText();
   }


   public function getContent() {
      if ($this->params->func == 'update') {
         $this->writeData();

         header('Location: '.ADMIN_URL_IDX.'/versandart');
         exit;
      }

      else {
         $this->getData();
      }

      include 'templates/versandart.tpl.php';
      return;
   }


   private function getData() {
      $this->region = $this->db->querySingleValue("SELECT region FROM #__laender WHERE id = ".$this->params->firma['versandart_land']);

      $sql = "SELECT  versandart_1, versandkosten_1, versand_gewicht_1, versand_stueck_1, abholung_check_1,
                      abholung_preis_1, check_vers_frei_1, vers_frei_1, min_preis_check_1, min_preis_1,
                      versandart_2, versandkosten_2, versand_gewicht_2, versand_stueck_2, abholung_check_2,
                      abholung_preis_2, check_vers_frei_2, vers_frei_2, min_preis_check_2, min_preis_2,
                      versandart_3, versandkosten_3, versand_gewicht_3, versand_stueck_3, abholung_check_3,
                      abholung_preis_3, check_vers_frei_3, vers_frei_3, min_preis_check_3, min_preis_3
                 FROM #__firma
              WHERE id = 1";
      $data = $this->db->querySingleObject($sql);

      for ($i = 1; $i < 4; $i++) {
         $this->{'versand_'.$i}['versandart']      = $data->{'versandart_'.$i};
         $this->{'versand_'.$i}['versand_gewicht'] = $data->{'versand_gewicht_'.$i};
         $this->{'versand_'.$i}['versand_stueck']  = $data->{'versand_stueck_'.$i};
         $this->{'versand_'.$i}['abholung_preis']  = $data->{'abholung_preis_'.$i};
         $this->{'versand_'.$i}['abholung_check']  = $data->{'abholung_check_'.$i};
         $this->{'versand_'.$i}['check_vers_frei'] = $data->{'check_vers_frei_'.$i};
         $this->{'versand_'.$i}['vers_frei']       = $data->{'vers_frei_'.$i};
         $this->{'versand_'.$i}['min_preis_check'] = $data->{'min_preis_check_'.$i};
         $this->{'versand_'.$i}['min_preis']       = $data->{'min_preis_'.$i};

         $versk = null;

         // Versandkosten als Dezimal (alt / Default nach Installation)
         // Nummereierung durch Änderungen (alt: von - bis)
         if (empty($data->{'versandkosten_'.$i}) || is_numeric($data->{'versandkosten_'.$i}) && $data->{'versandkosten_'.$i} > 0)  //$data->{'versandkosten_'.$i} > 0 || ($data->{'versandkosten_'.$i} == '' || preg_match('|^(\d)|', $data->{'versandkosten_'.$i}))) {
         {
            $this->{'versand_'.$i}['versandkosten1'] = 0;
            $this->{'versand_'.$i}['versandkosten2'] = 0;
            $this->{'versand_'.$i}['versandkosten3'] = $data->{'versandkosten_'.$i};
            $this->{'versand_'.$i}['versandwert2']   = 0;
            $this->{'versand_'.$i}['versandwert4']   = 0;
         }

         // Als JSON
         else {
            $versk = json_decode($data->{'versandkosten_'.$i});
            $this->{'versand_'.$i}['versandkosten1'] = $versk->versandkosten1;
            $this->{'versand_'.$i}['versandkosten2'] = $versk->versandkosten2;
            $this->{'versand_'.$i}['versandkosten3'] = $versk->versandkosten3;
            $this->{'versand_'.$i}['versandwert2']   = $versk->versandwert2;
            $this->{'versand_'.$i}['versandwert4']   = $versk->versandwert4;
         }


         // Gewichtskosten als Dezimal (alt / Default nach Installation)
         if (empty($versk))//!isset($versk->gewichtwert1)) {
         {
            if ((float)$data->{'versand_gewicht_'.$i} > 0) {
               $this->{'versand_'.$i}['gewichtkosten1'] = $data->{'versand_gewicht_'.$i};
               $this->{'versand_'.$i}['gewichtkosten2'] = $data->{'versand_gewicht_'.$i};
               $this->{'versand_'.$i}['gewichtkosten3'] = $data->{'versand_gewicht_'.$i};
               $this->{'versand_'.$i}['gewichtkosten4'] = $data->{'versand_gewicht_'.$i};
               $this->{'versand_'.$i}['gewichtkosten5'] = $data->{'versand_gewicht_'.$i};
            }

            else {
               $this->{'versand_'.$i}['gewichtkosten1'] = 2.44;
               $this->{'versand_'.$i}['gewichtkosten2'] = 3.28;
               $this->{'versand_'.$i}['gewichtkosten3'] = 5.88;
               $this->{'versand_'.$i}['gewichtkosten4'] = 5.88;
               $this->{'versand_'.$i}['gewichtkosten5'] = 5.88;
            }

            $this->{'versand_'.$i}['gewichtwert1']   = 0.100;
            $this->{'versand_'.$i}['gewichtwert2']   = 1.000;
            $this->{'versand_'.$i}['gewichtwert3']   = 1.000;
            $this->{'versand_'.$i}['gewichtwert4']   = 1.000;
         }

         // Als JSON
         else {
            $versk = json_decode($data->{'versandkosten_'.$i});
            $this->{'versand_'.$i}['gewichtkosten1'] = $versk->gewichtkosten1;
            $this->{'versand_'.$i}['gewichtkosten2'] = $versk->gewichtkosten2;
            $this->{'versand_'.$i}['gewichtwert1']   = $versk->gewichtwert1;

            if (isset($versk->gewichtwert3)) {
               $this->{'versand_'.$i}['gewichtkosten3'] = $versk->gewichtkosten3;
               $this->{'versand_'.$i}['gewichtkosten4'] = $versk->gewichtkosten4;
               $this->{'versand_'.$i}['gewichtkosten5'] = $versk->gewichtkosten5;
               $this->{'versand_'.$i}['gewichtwert2']   = $versk->gewichtwert2;
               $this->{'versand_'.$i}['gewichtwert3']   = $versk->gewichtwert3;
               $this->{'versand_'.$i}['gewichtwert4']   = $versk->gewichtwert4;
            }

            // Alte Versandkosten (vor Erweiterung auf 5) übernehmen
            else {
               $this->{'versand_'.$i}['gewichtkosten3'] = $versk->gewichtkosten2;
               $this->{'versand_'.$i}['gewichtkosten4'] = $versk->gewichtkosten2;
               $this->{'versand_'.$i}['gewichtkosten5'] = $versk->gewichtkosten3;
               $this->{'versand_'.$i}['gewichtwert2']   = $versk->gewichtwert2;
               $this->{'versand_'.$i}['gewichtwert3']   = $versk->gewichtwert2;
               $this->{'versand_'.$i}['gewichtwert4']   = $versk->gewichtwert2;
            }

            if (isset($versk->spedition_preis1)) {
               $this->{'versand_'.$i}['spedition_preis_1'] = $versk->spedition_preis1;
               $this->{'versand_'.$i}['spedition_preis_2'] = $versk->spedition_preis2;
               $this->{'versand_'.$i}['spedition_preis_3'] = $versk->spedition_preis3;
            }

            else {
               $this->{'versand_'.$i}['spedition_preis_1'] = 0;
               $this->{'versand_'.$i}['spedition_preis_2'] = 0;
               $this->{'versand_'.$i}['spedition_preis_3'] = 0;
            }
         }
      }

      return;
   }

   private function writeData() {
      $mindest_check     = $this->params->postCheckbox('mindest_check');
      $vers_grafik_check = $this->params->postCheckbox('vers_grafik_check');

      $versandart_1      = $this->params->postString('versandart_1');
      $versandkosten1    = $this->params->postFloat('versandkosten1_1');
      $versandkosten2    = $this->params->postFloat('versandkosten2_1');
      $versandkosten3    = $this->params->postFloat('versandkosten3_1');
      $versandwert2      = $this->params->postFloat('versandwert2_1');
      $versandwert4      = $this->params->postFloat('versandwert4_1');

      $gewichtkosten1    = $this->params->postFloat('gewichtkosten1_1');
      $gewichtkosten2    = $this->params->postFloat('gewichtkosten2_1');
      $gewichtkosten3    = $this->params->postFloat('gewichtkosten3_1');
      $gewichtkosten4    = $this->params->postFloat('gewichtkosten4_1');
      $gewichtkosten5    = $this->params->postFloat('gewichtkosten5_1');
      $gewichtwert1      = $this->params->postFloat('gewichtwert1_1');
      $gewichtwert2      = $this->params->postFloat('gewichtwert2_1');
      $gewichtwert3      = $this->params->postFloat('gewichtwert3_1');
      $gewichtwert4      = $this->params->postFloat('gewichtwert4_1');

      $versand_gewicht_1 = -1;
      $versand_stueck_1  = $this->params->postFloat('versand_stueck_1');
      $abholung_check_1  = $this->params->postCheckbox('abholung_check_1');
      $abholung_preis_1  = $this->params->postFloat('abholung_preis_1');
      $check_vers_frei_1 = $this->params->postString('check_vers_frei_1') != '' ? 'y' : 'n';
      $vers_frei_1       = $this->params->postFloat('vers_frei_1');
      $min_preis_check_1 = $this->params->postCheckbox('min_preis_check_1');
      $min_preis_1       = $this->params->postFloat('min_preis_1');

      $spedition_preis1  = $this->params->postFloat('spedition1_preis_1');
      $spedition_preis2  = $this->params->postFloat('spedition1_preis_2');
      $spedition_preis3  = $this->params->postFloat('spedition1_preis_3');

      if ($versandwert2 >= $versandwert4) {
         $versandwert4 = $versandwert2 + 1;
      }

      if ($gewichtwert2 <= $gewichtwert1) {
         $gewichtwert2 = $gewichtwert1 + 0.01;
      }

      if ($gewichtwert3 <= $gewichtwert2) {
         $gewichtwert3 = $gewichtwert2 + 0.01;
      }

      if ($gewichtwert4 <= $gewichtwert3) {
         $gewichtwert4 = $gewichtwert3 + 0.01;
      }

      $gewicht_detail_check = $this->params->postCheckbox('gewicht_detail_check1');

      $versandkosten_1 = json_encode((object)(['versandkosten1'    => $versandkosten1,
                                                    'versandkosten2'    => $versandkosten2,
                                                    'versandkosten3'    => $versandkosten3,
                                                    'versandwert2'      => $versandwert2,
                                                    'versandwert4'      => $versandwert4,
                                                    'gewichtkosten1'    => $gewichtkosten1,
                                                    'gewichtkosten2'    => $gewichtkosten2,
                                                    'gewichtkosten3'    => $gewichtkosten3,
                                                    'gewichtkosten4'    => $gewichtkosten4,
                                                    'gewichtkosten5'    => $gewichtkosten5,
                                                    'gewichtwert1'      => $gewichtwert1,
                                                    'gewichtwert2'      => $gewichtwert2,
                                                    'gewichtwert3'      => $gewichtwert3,
                                                    'gewichtwert4'      => $gewichtwert4,
                                                    'spedition_preis1'  => $spedition_preis1,
                                                    'spedition_preis2'  => $spedition_preis2,
                                                    'spedition_preis3'  => $spedition_preis3

      ]));

      $versandart_2      = $this->params->postString('versandart_2');
      $versandkosten1    = $this->params->postFloat('versandkosten1_2');
      $versandkosten2    = $this->params->postFloat('versandkosten2_2');
      $versandkosten3    = $this->params->postFloat('versandkosten3_2');
      $versandwert2      = $this->params->postFloat('versandwert2_2');
      $versandwert4      = $this->params->postFloat('versandwert4_2');

      $gewichtkosten1    = $this->params->postFloat('gewichtkosten1_2');
      $gewichtkosten2    = $this->params->postFloat('gewichtkosten2_2');
      $gewichtkosten3    = $this->params->postFloat('gewichtkosten3_2');
      $gewichtkosten4    = $this->params->postFloat('gewichtkosten4_2');
      $gewichtkosten5    = $this->params->postFloat('gewichtkosten5_2');
      $gewichtwert1      = $this->params->postFloat('gewichtwert1_2');
      $gewichtwert2      = $this->params->postFloat('gewichtwert2_2');
      $gewichtwert3      = $this->params->postFloat('gewichtwert3_2');
      $gewichtwert4      = $this->params->postFloat('gewichtwert4_2');

      $versand_gewicht_2 = -1;
      $versand_stueck_2  = $this->params->postFloat('versand_stueck_2');
      $abholung_check_2  = $this->params->postCheckbox('abholung_check_2');
      $abholung_preis_2  = $this->params->postFloat('abholung_preis_2');
      $check_vers_frei_2 = $this->params->postString('check_vers_frei_2') != '' ? 'y' : 'n';
      $vers_frei_2       = $this->params->postFloat('vers_frei_2');
      $min_preis_check_2 = $this->params->postCheckbox('min_preis_check_2');
      $min_preis_2       = $this->params->postFloat('min_preis_2');

      $spedition_preis1  = $this->params->postFloat('spedition2_preis_1');
      $spedition_preis2  = $this->params->postFloat('spedition2_preis_2');
      $spedition_preis3  = $this->params->postFloat('spedition2_preis_3');

      if ($versandwert2 >= $versandwert4) {
         $versandwert4 = $versandwert2 + 1;
      }

      if ($gewichtwert2 <= $gewichtwert1) {
         $gewichtwert2 = $gewichtwert1 + 0.01;
      }

      if ($gewichtwert3 <= $gewichtwert2) {
         $gewichtwert3 = $gewichtwert2 + 0.01;
      }

      if ($gewichtwert4 <= $gewichtwert3) {
         $gewichtwert4 = $gewichtwert3 + 0.01;
      }
      $versandkosten_2 = json_encode((object)(['versandkosten1'    => $versandkosten1,
                                                    'versandkosten2'    => $versandkosten2,
                                                    'versandkosten3'    => $versandkosten3,
                                                    'versandwert2'      => $versandwert2,
                                                    'versandwert4'      => $versandwert4,
                                                    'gewichtkosten1'    => $gewichtkosten1,
                                                    'gewichtkosten2'    => $gewichtkosten2,
                                                    'gewichtkosten3'    => $gewichtkosten3,
                                                    'gewichtkosten4'    => $gewichtkosten4,
                                                    'gewichtkosten5'    => $gewichtkosten5,
                                                    'gewichtwert1'      => $gewichtwert1,
                                                    'gewichtwert2'      => $gewichtwert2,
                                                    'gewichtwert3'      => $gewichtwert3,
                                                    'gewichtwert4'      => $gewichtwert4,
                                                    'spedition_preis1'  => $spedition_preis1,
                                                    'spedition_preis2'  => $spedition_preis2,
                                                    'spedition_preis3'  => $spedition_preis3
      ]));

      $versandart_3      = $this->params->postString('versandart_3');
      $versandkosten1    = $this->params->postFloat('versandkosten1_3');
      $versandkosten2    = $this->params->postFloat('versandkosten2_3');
      $versandkosten3    = $this->params->postFloat('versandkosten3_3');
      $versandwert2      = $this->params->postFloat('versandwert2_3');
      $versandwert4      = $this->params->postFloat('versandwert4_3');

      $gewichtkosten1    = $this->params->postFloat('gewichtkosten1_3');
      $gewichtkosten2    = $this->params->postFloat('gewichtkosten2_3');
      $gewichtkosten3    = $this->params->postFloat('gewichtkosten3_3');
      $gewichtkosten4    = $this->params->postFloat('gewichtkosten4_3');
      $gewichtkosten5    = $this->params->postFloat('gewichtkosten5_3');
      $gewichtwert1      = $this->params->postFloat('gewichtwert1_3');
      $gewichtwert2      = $this->params->postFloat('gewichtwert2_3');
      $gewichtwert3      = $this->params->postFloat('gewichtwert3_3');
      $gewichtwert4      = $this->params->postFloat('gewichtwert4_3');

      $versand_gewicht_3 = -1;
      $versand_stueck_3  = $this->params->postFloat('versand_stueck_3');
      $abholung_check_3  = $this->params->postCheckbox('abholung_check_3');
      $abholung_preis_3  = $this->params->postFloat('abholung_preis_3');
      $check_vers_frei_3 = $this->params->postString('check_vers_frei_3') != '' ? 'y' : 'n';
      $vers_frei_3       = $this->params->postFloat('vers_frei_3');
      $min_preis_check_3 = $this->params->postCheckbox('min_preis_check_3');
      $min_preis_3       = $this->params->postFloat('min_preis_3');

      $spedition_preis1  = $this->params->postFloat('spedition3_preis_1');
      $spedition_preis2  = $this->params->postFloat('spedition3_preis_2');
      $spedition_preis3  = $this->params->postFloat('spedition3_preis_3');

      if ($versandwert2 >= $versandwert4) {
         $versandwert4 = $versandwert2 + 1;
      }

      if ($gewichtwert2 <= $gewichtwert1) {
         $gewichtwert2 = $gewichtwert1 + 0.01;
      }

      if ($gewichtwert3 <= $gewichtwert2) {
         $gewichtwert3 = $gewichtwert2 + 0.01;
      }

      if ($gewichtwert4 <= $gewichtwert3) {
         $gewichtwert4 = $gewichtwert3 + 0.01;
      }
      $versandkosten_3 = json_encode((object)(['versandkosten1'    => $versandkosten1,
                                                    'versandkosten2'    => $versandkosten2,
                                                    'versandkosten3'    => $versandkosten3,
                                                    'versandwert2'      => $versandwert2,
                                                    'versandwert4'      => $versandwert4,
                                                    'gewichtkosten1'    => $gewichtkosten1,
                                                    'gewichtkosten2'    => $gewichtkosten2,
                                                    'gewichtkosten3'    => $gewichtkosten3,
                                                    'gewichtkosten4'    => $gewichtkosten4,
                                                    'gewichtkosten5'    => $gewichtkosten5,
                                                    'gewichtwert1'      => $gewichtwert1,
                                                    'gewichtwert2'      => $gewichtwert2,
                                                    'gewichtwert3'      => $gewichtwert3,
                                                    'gewichtwert4'      => $gewichtwert4,
                                                    'spedition_preis1'  => $spedition_preis1,
                                                    'spedition_preis2'  => $spedition_preis2,
                                                    'spedition_preis3'  => $spedition_preis3
      ]));

      $sql = "UPDATE #__firma SET
                versandart_1         = '$versandart_1',
                versandkosten_1      = '$versandkosten_1',
                versand_gewicht_1    = '$versand_gewicht_1',
                versand_stueck_1     = '$versand_stueck_1',
                abholung_check_1     = '$abholung_check_1',
                abholung_preis_1     = '$abholung_preis_1',
                check_vers_frei_1    = '$check_vers_frei_1',
                vers_frei_1          = '$vers_frei_1',
                min_preis_check_1    = '$min_preis_check_1',
                min_preis_1          = '$min_preis_1',
                versandart_2         = '$versandart_2',
                versandkosten_2      = '$versandkosten_2',
                versand_gewicht_2    = '$versand_gewicht_2',
                versand_stueck_2     = '$versand_stueck_2',
                abholung_check_2     = '$abholung_check_2',
                abholung_preis_2     = '$abholung_preis_2',
                check_vers_frei_2    = '$check_vers_frei_2',
                vers_frei_2          = '$vers_frei_2',
                min_preis_check_2    = '$min_preis_check_2',
                min_preis_2          = '$min_preis_2',
                versandart_3         = '$versandart_3',
                versandkosten_3      = '$versandkosten_3',
                versand_gewicht_3    = '$versand_gewicht_3',
                versand_stueck_3     = '$versand_stueck_3',
                abholung_check_3     = '$abholung_check_3',
                abholung_preis_3     = '$abholung_preis_3',
                check_vers_frei_3    = '$check_vers_frei_3',
                vers_frei_3          = '$vers_frei_3',
                min_preis_check_3    = '$min_preis_check_3',
                min_preis_3          = '$min_preis_3',
                vers_grafik_check    = '$vers_grafik_check',
                mindest_check        = '$mindest_check',
                gewicht_detail_check = '$gewicht_detail_check'
             WHERE id = 1";
      $this->db->query($sql);

      // Firmendaten neu einlesen
      $this->params->getFirmData();
   }
}
