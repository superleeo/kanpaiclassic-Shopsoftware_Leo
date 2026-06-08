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

/******* EXPORT ************************************************************************************/
if ($mode == 'export') {
   $text = \KANPAICLASSIC\Control::getText();
   $trenner = $config->trenner;
   $wt = $config->worttrenner;
   $sql = "SELECT anrede, email, role, vorname, nachname, firma, adresse, hausnr, plz, ort, buland, staat, staat2, gebdatum, ustid, telefon, newsletter,
              l.name AS land
              FROM #__users AS u, #__laender AS l
           WHERE u.id > 1
              AND l.id = u.staat";
   $data = $this->db->queryAllObjects($sql);

   $csv = '';
   if ($config->csv_head == 'y') {
      // shop_article
      $head  = $wt.'Anrede'.$wt.$trenner;
      $head .= $wt.'Email'.$wt.$trenner;
      $head .= $wt.'Rolle'.$wt.$trenner;
      $head .= $wt.'Vorname'.$wt.$trenner;
      $head .= $wt.'Nachname'.$wt.$trenner;
      $head .= $wt.'Firma'.$wt.$trenner;
      $head .= $wt.'Adresse'.$wt.$trenner;
      $head .= $wt.'Hausnr'.$wt.$trenner;
      $head .= $wt.'PLZ'.$wt.$trenner;
      $head .= $wt.'Ort'.$wt.$trenner;
      $head .= $wt.'BuLand'.$wt.$trenner;
      $head .= $wt.'Land'.$wt.$trenner;
      $head .= $wt.'Geb.datum'.$wt.$trenner;
      $head .= $wt.'UStID'.$wt.$trenner;
      $head .= $wt.'Telefon'.$wt.$trenner;
      $head .= $wt.'Newsletter'.$wt;
      $head .= "\r\n";
      $csv .= $head;
   }

   for ($i = 0; $i < count($data); $i++) {
      $land = $data[$i]->land;
      if ((int)$data[$i]->staat == 10) {
         $land = $data[$i]->staat2;
      }

      $csv .= $wt.$text->get('kunde', $data[$i]->anrede).$wt.$trenner;
      $csv .= $wt.$data[$i]->email.$wt.$trenner;
      $csv .= $wt.$data[$i]->role.$wt.$trenner;
      $csv .= $wt.$data[$i]->vorname.$wt.$trenner;
      $csv .= $wt.$data[$i]->nachname.$wt.$trenner;
      $csv .= $wt.$data[$i]->firma.$wt.$trenner;
      $csv .= $wt.$data[$i]->adresse.$wt.$trenner;
      $csv .= $wt.$data[$i]->hausnr.$wt.$trenner;
      $csv .= $wt.$data[$i]->plz.$wt.$trenner;
      $csv .= $wt.$data[$i]->ort.$wt.$trenner;
      $csv .= $wt.$data[$i]->buland.$wt.$trenner;
      $csv .= $wt.$land.$wt.$trenner;
      $csv .= $wt.$data[$i]->gebdatum.$wt.$trenner;
      $csv .= $wt.$data[$i]->ustid.$wt.$trenner;
      $csv .= $wt.$data[$i]->telefon.$wt.$trenner;
      $csv .= $wt.$data[$i]->newsletter.$wt;
      $csv .= "\r\n";
   }
}

/******* IMPORT ************************************************************************************/
if ($mode == 'import') {
   $kunden = file($file);
   $trenner = $config->trenner;

/*
 0 - anrede
 1 - email
 2 - role
 3 - vorname
 4 - nachname
 5 - firma
 6 - adresse
 7 - hausnr
 8 - plz
 9 - ort
10 - buland
11 - land
12 - gebdatum
13 - ustid
14 - telefon
15 - newsletter
*/

   for ($i = $start; $i <count($kunden); $i++) {
      $zeile = trim($kunden[$i], "\n,\r");
      $zeile = trim($kunden[$i], '"');

      if ($zeile == '') {
         continue;
      }

      $act_user = explode($trenner, $zeile);

      // ' und " entfernen
      for ($u = 0; $u < count($act_user); $u++) {
         $act_user[$u] = trim($act_user[$u], "\"'");
      }

      $land   = $act_user[11];
      $staat  = 160;
      $staat2 = '';

      if ($land != '') {
         $test = $this->db->querySingleValue("SELECT id FROM #__laender WHERE name = '$land'");
         if ((int)$test > 0) {
            $staat = $test;
         }
         else {
            $staat = 10;
            $staat2 = $land;
         }
      }

      // Email vorhanden? Aktualisieren
      $test = $this->db->querySingleValue("SELECT id FROM #__users WHERE email = '".$act_user[1]."'");
      if ((int)$test > 0) {
         $user_sql = "anrede     = '".($act_user[0] == 'Frau' ? 'frau' : 'herr')."',
                      role       = '".((int)$act_user[4] < 5 ? 9 : $act_user[2])."',
                      vorname    = '".$act_user[3]."',
                      nachname   = '".$act_user[4]."',
                      firma      = '".$act_user[5]."',
                      adresse    = '".$act_user[6]."',
                      hausnr     = '".$act_user[7]."',
                      plz        = '".$act_user[8]."',
                      ort        = '".$act_user[9]."',
                      buland     = '".$act_user[10]."',
                      staat      = '".$staat."',
                      staat2     = '".$staat2."',
                      gebdatum   = '".$act_user[12]."',
                      ustid      = '".$act_user[13]."',
                      telefon    = '".$act_user[14]."',
                      newsletter = '".$act_user[15]."'";

         $this->db->query("UPDATE #__users SET $user_sql WHERE id = $test");
         continue;
      }

      else {
         // Neuer Eintrag
         $password = md5('');

         $user_sql = "anrede     = '".($act_user[0] == 'Frau' ? 'frau' : 'herr')."',
                      email      = '".$act_user[1]."',
                      password   = '".$password."',
                      role       = '".((int)$act_user[4] < 5 ? 9 : $act_user[2])."',
                      vorname    = '".$act_user[3]."',
                      nachname   = '".$act_user[4]."',
                      firma      = '".$act_user[5]."',
                      adresse    = '".$act_user[6]."',
                      hausnr     = '".$act_user[7]."',
                      plz        = '".$act_user[8]."',
                      ort        = '".$act_user[9]."',
                      buland     = '".$act_user[10]."',
                      staat      = '".$staat."',
                      staat2     = '".$staat2."',
                      gebdatum   = '".$act_user[12]."',
                      ustid      = '".$act_user[13]."',
                      telefon    = '".$act_user[14]."',
                      newsletter = '".$act_user[15]."'";

         $this->db->query("INSERT INTO #__users SET $user_sql");
      }
   }

   exit(json_encode(['status' => 'ok', 'msg' => 'Datei erfolgreich importiert']));
}

?>