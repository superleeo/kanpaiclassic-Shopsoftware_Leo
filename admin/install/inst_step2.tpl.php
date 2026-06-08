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

$ok = true;
$html = '';
$inst_server = '';
$inst_user = '';
$inst_pass = '';
$inst_db = '';
$inst_port   = '';
$inst_socket = '';
$inst_prefix = 'shop_';
if (is_file(str_replace('/admin/install', '', dirname(__FILE__)).'/classes/modules/portal/haendler.module.php')) {
   $inst_prefix = 'portal_';
}

if ($sqlerror) {
   if ($sqlerror == 1) {
      $html = "<p class='error'>Es sind nicht alle Verbindungsparameter angegeben.</p>";
   }
   elseif ($sqlerror == 2005) {
      $html = "<p class='error'>Server unbekannt!</p>";
   }
   elseif ($sqlerror == 1049) {
      $html = "<p class='error'>Der Datenbankname ist falsch, DB-Benutzer und Passwort sind korrekt</p>";
   }
   elseif ($sqlerror == 1045) {
      $html = "<p class='error'>DB-Benutzer oder Passwort sind falsch</p>";
   }
   else {
      $html = "<p class='error'>DB-Installation nicht möglich</p>";
   }
}

$felder = [
            ['inst_server', 'Server', ' localhost od. rdbms.strato.de od. ...'],
            ['inst_user', 'Benutzer', ' z.B web123'],
            ['inst_pass', 'DB-Passwort', ' Datenbankpasswort'],
            ['inst_db', 'DB-Name', ' z.B. usr_web123_1'],
            ['inst_port', 'Port', ' optional'],
            ['inst_socket', 'Socket', ' optional']
          ];

for ($i = 0; $i < count($felder); $i++) {
   $feld = $felder[$i];
   $html .= '<p><label for="inst_server">' . $feld[1] . '</label>';
   if ($params->postTest($feld[0], '', 'none') && $params->postString($feld[0], '', 'none') != $feld[2]) {
      ${$feld[0]} = $params->postString($feld[0], '', 'none');
      $html .= '<input type="text" name="' . $feld[0] . '" id="' . $feld[0] . '" value="' . $params->postString($feld[0], '', 'none') . '" placeholder="'.$feld[2].'" /></p>';
   }

   else {
      $html .= "<input style='color:#aaaaaa;' onfocus=\"if (this.value.substring(0,1) == ' ') { this.value=''; }; this.style.color='#000000';\" type='text' name='$feld[0]' id='' value='$feld[2]' /></p>";
   }
   if ($i == 3) {
      $html .= '<p><label for="dummy">erweitert</label><input type="checkbox" style="display:inline-block; position:relative; top:3px;" id="dummy" name="dummy" onclick="$(this).is(\':checked\') ? $(\'#ports\').show() : $(\'#ports\').hide();"></p>';
      $html .= '<div id="ports" style="display:none">';
   }
}
$html .= '</div>';
$html .= '<input type="hidden" name="task" value="install" />';
$html .= '<input type="hidden" name="func" value="step3" />';
$html .= '<input type="hidden" name="inst_prefix" value="' . $inst_prefix . '" />';
$html .= '<div style="text-align:center; margin:25px 0 0 0;"><input type="image" style="cursor:pointer; width:149px; height:35px; position:relative; display:inline-block;" src="install_btn1.jpg" alt="Installieren" autocomplete="off" /></div>';

?>
<!DOCTYPE html>
<html lang="de">
<head>
<title>Kanpai Classic Shopsystem - Installation Step 2</title>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="install.css" />
</head>

<body>
   <div id="seite">
      <div id="seite2">
         <div id="rahmen">
            <div id="rahmen-innen">
               <h1>MySQL-Datenbankzugangsdaten</h1>
               <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>"
                  enctype="application/x-www-form-urlencoded">
                  <div>
                     <?php echo $html; ?>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
   <script src="../../js/jquery2.min.js"></script>
   <script>
      $(function() { $('#dummy').attr('checked') != true ? $('#dummy').attr('checked', false) : ''; });
   </script>
</body>
</html>
