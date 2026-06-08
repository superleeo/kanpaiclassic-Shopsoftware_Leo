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

class KANPAICLASSIC_konto
{
   private $db = null;
   private $params = null;

   public $datum = array();
   public $rechnung = array();
   public $bestellung = array();

   public function __construct() {
      $this->db = Control::getDB();
      $this->params = Control::getParams();
      $sql = '';

      if (!defined('CONF_MODULE_BESTELLZUSAMMENFASSUNG')) {
         $sql = "SELECT id, bestellnummer, rechnungsnummer, rechnungsdatum, created, status, 'n' AS collected, 'n' AS collector
                    FROM #__rechnung
                 WHERE user_id = " .$this->params->user_id." AND deleted = 'n' AND status < 5
                 ORDER BY created DESC";
         $data = $this->db->queryAllObjects($sql);

         $this->db->query($sql);

         for ($i = 0; is_array($data) && $i < count($data); $i++) {
            $val = base64_encode((int)$data[$i]->id * 57 - 29);
            if ($data[$i]->rechnungsnummer != '' && $data[$i]->status >= 2) {
               $this->datum[$i] = $data[$i]->created;
               $this->rechnung[$i][0] = "Re-" . $data[$i]->rechnungsnummer;
               $this->rechnung[$i][1] = $val;
               $this->rechnung[$i][2] = $data[$i]->collected;
            }

            else {
               $this->datum[$i] = $data[$i]->created;
               $this->rechnung[$i][0] = '';
            }

            $this->bestellung[$i][0] = $data[$i]->bestellnummer;
            $this->bestellung[$i][1] = $val;
            $this->bestellung[$i][2] = $data[$i]->collected;
            $this->bestellung[$i][3] = $data[$i]->collector;
            $this->bestellung[$i][5] = $data[$i]->status;
         }
      }

      // Bei Modul Bestellzusammenfassung
      else {
         $z = 0;
         $data = array();
         $sql = "SELECT id, bestellnummer, rechnungsnummer, rechnungsdatum, created, status, collected, collector
                    FROM #__rechnung
                 WHERE user_id = " .$this->params->user_id."
                    AND deleted = 'n'
                    AND collected = 'n'
                    AND status < 5
                 ORDER BY created DESC";
         $data1 = $this->db->queryAllObjects($sql);

         if ($data1) {
            for ($d1 = 0; is_array($data1) && $d1 < count($data1); $d1++) {
//               $data[] = $data1[$d1];

               // Beu Bestellzusammenfassung zugehörige Bestellungen auslesen
               if ($data1[$d1]->collector == 'y') {
                  $sql = "SELECT id, bestellnummer, rechnungsnummer, rechnungsdatum, created, status, collected, collector
                             FROM #__rechnung
                          WHERE id IN (
                              SELECT rechnung_id
                                 FROM #__rechnung_collector
                              WHERE collector_id = ".$data1[$d1]->id."
                          )
                          ORDER BY created DESC";
                  $data2 = $this->db->queryAllObjects($sql);

                  if ($data2) {
                     $sub = array();

                     for ($d2 = 0; is_array($data2) && $d2 < count($data2); $d2++) {
                        $sub[] = $data2[$d2];
                     }

                     $data1[$d1]->count = count($data2);
                     $data1[$d1]->sub = $sub;
                     $data[] = $data1[$d1];
                  }

                  else {
                     $data1[$d1]->count = 0;
                     $data[] = $data1[$d1];
                  }
               }

               else {
                  $data1[$d1]->count = 0;
                  $data[] = $data1[$d1];
               }
            }

            for ($i = 0; is_array($data) && $i < count($data); $i++) {
               $val = base64_encode((int)$data[$i]->id * 57 - 29);

               if (($data[$i]->rechnungsnummer != '' && ($data[$i]->status == 3 || $data[$i]->status == 4)) || $data[$i]->collector == 'y' || $data[$i]->collected == 'y') {
                  $this->datum[$i][0] = $data[$i]->created;
                  $this->rechnung[$i][0] = "Re-" . ($data[$i]->rechnungsnummer != '' ? $data[$i]->rechnungsnummer : $data[$i]->bestellnummer);
                  $this->rechnung[$i][1] = $val;
               }

               else {
                  $this->datum[$i][0] = $data[$i]->created;
                  $this->rechnung[$i][0] = '';
               }

               $this->bestellung[$i][0] = $data[$i]->bestellnummer;
               $this->bestellung[$i][1] = $val;
               $this->bestellung[$i][2] = $data[$i]->collected;
               $this->bestellung[$i][3] = $data[$i]->collector;
               $this->bestellung[$i][4] = 0;
               $this->bestellung[$i][5] = $data[$i]->status;

               if ($data[$i]->count > 0) {
                  $ds    = $data[$i]->sub;
                  $count = $data[$i]->count;
                  $this->bestellung[$i][4] = $count;

                  for ($s = 0; $s < $data[$i]->count; $s++) {
                     $val = base64_encode((int)$ds[$s]->id * 57 - 29);

                     if (($ds[$s]->rechnungsnummer != '' && ($ds[$s]->status == 3 || $ds[$s]->status == 4))) {
                        $this->datum[$i][5][$s] = $ds[$s]->created;
                        $this->rechnung[$i][5][$s][0] = "Re-" . ($ds[$s]->rechnungsnummer != '' ? $ds[$s]->rechnungsnummer : $ds[$s]->bestellnummer);
                        $this->rechnung[$i][5][$s][1] = $val;
                     }

                     else {
                        $this->datum[$i][5][$s] = ''; $ds[$s]->created;
                        $this->rechnung[$i][5][$s][0] = '';
                     }

                     $this->bestellung[$i][5][$s][0] = $ds[$s]->bestellnummer;
                     $this->bestellung[$i][5][$s][1] = $val;
                     $this->bestellung[$i][5][$s][2] = $ds[$s]->collected;
                     $this->bestellung[$i][5][$s][3] = $ds[$s]->collector;
                  }
               }
            }
         }
      }
   }
}