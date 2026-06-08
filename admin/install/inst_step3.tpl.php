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

if (!defined('KANPAICLASSIC')) {
   die ("This file cannot run outside the Kanpai Classic Shopsoftware");
}

$script = "style='color:#aaaaaa;' onfocus=\"if (this.value.substring(0,1) == ' ') { this.value=''; }; this.style.color='#000000';\"";
$script_fail = "style='color:#e00000;' onfocus=\"if (this.value.substring(0,1) == ' ') { this.value=''; }; this.style.color='#000000';\"";
$fail = false;

$felder = [
         ['shop_name', 'Shopname', ' Websitetitel'],
         ['firm_name', 'Firma', ' Ihr Firmenname'],
         ['first_name', 'Vorname', ' Max'],
         ['last_name', 'Nachname', ' Mustermann'],
         ['street', 'Straße', ' Musterstr.'],
         ['haus_nr', 'Nr.', ' 123'],
         ['postal_code', 'PLZ', ' 12345'],
         ['city', 'Ort', ' Stadt'],
         ['country', 'Land', ' Deutschland'],
         ['email', 'eMailAdresse', ' email@domain.de'],
         ['mailfrom', 'eMail from...', ' Max Mustermann']
];
$html = '';

foreach ($felder as $feld) {
   $html .= '<p><label for="' . $feld[0] . '">' . $feld[1] . '</label>';
   if ($params->postTest($feld[0]) && $params->postString($feld[0]) != $feld[2]) { // Eingabe OK
      $html .= '<input type="text" name="' . $feld[0] . '" id="' . $feld[0] . '" value="' . $params->postString($feld[0]) . '" /></p>';
   }

   elseif (!$params->postTest($feld[0])) {
      $html .= '<input ' . $script . ' type="text" name="' . $feld[0] . '" id="' . $feld[0] . '" value="' . $feld[2] . '" /></p>';
   }

   else {
      $html .= '<input ' . $script_fail . ' type="text" name="' . $feld[0] . '" id="' . $feld[0] . '" value="' . $feld[2] . '" /></p>';
      $fail = true;
   }
}

if (!isset($version_db)) {
   include_once '../config.temp';
   include_once '../../classes/base/database.class.php';

   $db = new \KANPAICLASSIC\KANPAICLASSIC_database();
   $version_db = $db->querySingleValue("SELECT version FROM #__firma WHERE id = 1");
}
?><!DOCTYPE html>
<html lang="de">
<head>
<title>Kanpai Classic Shopsoftware - Installation Step 3</title>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="install.css" />
</head>

<body>
   <div id="seite">
      <div id="seite2">
         <div id="rahmen">
            <div id="rahmen-innen">
               <h1>Installationsassistent</h1>
               <p>Die Installation der Datenbank war erfolgreich.</p>
               <?php if (!$fail) { ?>
               <p>Version der Datenbank: <?php echo $version_db; ?></p>
               <?php } ?>
               <p>Tragen sie nun die Daten zum Betreiben Ihres Shops ein.</p>
               <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                  <div>
                     <?php echo $html; ?>
                     <input type="hidden" name="task" value="install" />
                     <input type="hidden" name="func" value="step4" />
                     <div style="text-align:center; margin:25px 0 0 0;">
                        <input type="image" style="cursor:pointer; width:149px; height:35px; position:relative; display:inline-block;" src="install_btn3.jpg" alt="Speichern" />
                     </div>';
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</body>
</html>
