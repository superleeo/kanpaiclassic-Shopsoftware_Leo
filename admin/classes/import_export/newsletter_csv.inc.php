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
   $csv = '';
   
   $sql = "SELECT anrede, email, vorname, nachname, firma
              FROM #__users
           WHERE newsletter = 'y' AND newsletter_check = 'ok'";
   $data = $this->db->queryAllObjects($sql);

   if ($config->csv_head == 'y') {
      // shop_article
      $head  = $wt.'Anrede'.$wt.$trenner;
      $head .= $wt.'Vorname'.$wt.$trenner;
      $head .= $wt.'Nachname'.$wt.$trenner;
      $head .= $wt.'E-Mail'.$wt.$trenner;
      $head .= $wt.'Sonstiges'.$wt;
      $csv .= $head;
   }

   for ($i = 0; $i < count($data); $i++) {
      $csv .= $wt.$text->get('kunde', $data[$i]->anrede).$wt.$trenner;
      $csv .= $wt.$data[$i]->vorname.$wt.$trenner;
      $csv .= $wt.$data[$i]->nachname.$wt.$trenner;
      $csv .= $wt.$data[$i]->email.$wt.$trenner;
      $csv .= ($wt.$data[$i]->firma != '' ? $wt.$data[$i]->firma : '   ').$wt.$trenner;
   }
}

/******* IMPORT ************************************************************************************/
if ($mode == 'import') {
   $newsletter = file($file);
//   $trenner = $config->trenner;
   $trenner = ';';
   $old_id = '';

   for ($i = $start; $i <count($newsletter); $i++) {
      $zeile = trim($newsletter[$i], "\n,\r");
      $zeile = trim($newsletter[$i], '"');
      
      if ($zeile == '') {
         continue;
      }

      $act_user = explode($trenner, $zeile);
      
      // ' und " entfernen
      for ($u = 0; $u < count($act_user); $u++) {
         $act_user[$u] = trim($act_user[$u], "\"'");
      }

      $anrede   = ($act_user[0] == 'Frau' ? 'frau' : 'herr');
      $vorname  = $act_user[1];
      $nachname = $act_user[2];
      $email    = $act_user[3];

      // Email vorhanden?
      $test = $this->db->querySingleValue("SELECT count(*) FROM #__users WHERE email = '$email'");

      if ((int)$test > 0) {
         $this->db->query("UPDATE #__users SET newsletter = 'y', newsletter_check = 'ok', anrede = '$anrede', vorname = '$vorname', nachname = '$nachname'  WHERE email = '$email'");
      }
   
      // Neuer Eintrag
      else {
         $this->db->query("INSERT INTO #__users SET anrede = '$anrede', email='$email', role = 9, vorname = '$vorname', nachname = '$nachname', newsletter = 'y', newsletter_check = 'ok'");
      }
   }
   
   exit(json_encode(['status' => 'ok', 'msg' => 'Datei erfolgreich importiert']));
}
