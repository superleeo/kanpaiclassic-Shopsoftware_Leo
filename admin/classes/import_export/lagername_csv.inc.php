<?php
/*
###################################################################################
  Kanpai Classic Shopsoftware Entwicklungsstand: 14.01.2021 Version 11

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

/******* EXPORT ************************************************************************************/
if ($mode == 'export') {
   $text = \KANPAICLASSIC\Control::getText();
   $trenner = $config->trenner;
   $wt = $config->worttrenner;
   $sql = "SELECT a.id, a.online, a.art_nr, a.netto, a.angebot, a.angebot_active, a.menge,
                  i.steuersatz, i.name_deu as name
              FROM #__articles AS a
           LEFT JOIN #__articles_info AS i
              ON i.id = a.parent_id
           ORDER BY a.parent_id, a.sort";

   $data = $this->db->queryAllObjects($sql);

   $csv = '';
   if ($config->csv_head == 'y') {
      $head="";
      $head .= $wt.'name'.$wt.$trenner;
      $head .= $wt.'art_nr'.$wt.$trenner;
      $head .= $wt.'online'.$wt.$trenner;
      $head .= $wt.'brutto'.$wt.$trenner;
      //$head .= $wt.'angebot_aktiv'.$wt.$trenner;
      //$head .= $wt.'angebot_brutto'.$wt.$trenner;
      $head .= $wt.'lager'.$wt;
      $head .= "\r\n";
      $csv .= $head;
   }

   for ($i = 0; $i < count($data); $i++) {
      $d        = $data[$i];
      $netto    = (float)$d->netto;
      $angebot  = (float)$d->angebot;
      $steuer   = (float)$this->params->firma['tax'.($d->steuersatz !== null ? $d->steuersatz : 2)] / 100;
      $brutto   = $netto * (1 + $steuer);
      $a_brutto = $angebot * (1 + $steuer);


      $csv .= $wt.$d->name.$wt.$trenner;
      $csv .= $wt.$d->art_nr.$wt.$trenner;
      $csv .= $wt.$d->online.$wt.$trenner;
      $csv .= $wt.number_format($brutto, 2, ',', '').$wt.$trenner;
      //$csv .= $wt.$d->angebot_active.$wt.$trenner;
      //$csv .= $wt.number_format($a_brutto, 2, ',', '').$wt.$trenner;
      $csv .= $wt.str_replace('.', ',', $d->menge).$wt;
      $csv .= "\r\n";
   }
}

/******* IMPORT ************************************************************************************/
if ($mode == 'import') {
   $artikel = file($file);
   $trenner = $config->trenner;
   $has_name  = false;

   for ($i = 0; $i <count($artikel); $i++) {

      $zeile = trim($artikel[$i], "\n,\r");
      $zeile = trim($artikel[$i], '"');

      if ($zeile == '') {
         continue;
      }

      $act_artikel = explode($trenner, $zeile);

      // ' und " entfernen
      for ($a = 0; $a < count($act_artikel); $a++) {
         $act_artikel[$a] = trim($act_artikel[$a], "\"'");
      }

      if ($i == 0 && $act_artikel[0] == 'name' ) {
          $has_name = true;
          continue;
      }

      $test = null;

      if ($act_artikel[0] == '') {
        continue;
      }

      $sql = "SELECT a.id, i.steuersatz
                                            FROM #__articles AS a
                                        LEFT JOIN #__articles_info AS i
                                            ON a.parent_id = i.id
                                        WHERE i.name_deu like '".$act_artikel[0]."'";


      $test_arr = $this->db->queryAllObjects($sql);

      if(count($test_arr)>1){

          // mehrere werte, also Abgleich Artikelnummer
          $test = $this->db->querySingleObject("SELECT a.id, i.steuersatz
                                                  FROM #__articles AS a
                                               LEFT JOIN #__articles_info AS i
                                                  ON a.parent_id = i.id
                                               WHERE i.name_deu like '".$act_artikel[0]."' and a.art_nr like '".$act_artikel[1]."'");
         
      }else{
          
          if ($test_arr != null) {  // einer gefunden
              $test=current($test_arr);
             
          }else{
              // keinen gefunden
          }

      }

      if ($test != null) {

         $steuer = (float)$this->params->firma['tax'.($test->steuersatz !== null ? $test->steuersatz : 2)] / 100;

         $brutto   = (float)str_replace(',', '.', $act_artikel[3]);
         $menge    = (float)str_replace(',', '.', $act_artikel[4]);
         $netto    = $brutto / (1 + $steuer);

         $this->db->query("UPDATE #__articles SET
                                online         = '".($act_artikel[2] == 'y' ? 'y' : 'n')."',
                                netto          = '".$netto."',
                                menge          = '".$menge."'
                            WHERE id = '".$test->id."'");


      }
   }

   exit(json_encode(['status' => 'ok', 'msg' => 'Datei erfolgreich importiert']));

}
