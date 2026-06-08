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

?>
<!DOCTYPE html>
<html lang="de">
<head>
<title>Kanpai Classic Shopsoftware - Installation Step 4</title>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="install.css" />
</head>

<body>
   <div id="seite">
      <div id="seite2">
         <div id="rahmen">
            <div id="rahmen-innen">
               <h1>Installationsassistent</h1>
               <p>Bitte legen Sie Ihren Zugang zur Shopsoftware fest. Bitte sofort
                  notieren und gut aufbewahren, sonst kommen Sie nie wieder in die
                  Shopsoftware</p>
               <form method="post" enctype="application/x-www-form-urlencoded"
                  action="<?php echo $_SERVER['PHP_SELF']; ?>">
                  <div>
                     <p>
                        <label class="label" for="username">Benutzer</label><input
                           type="text" name="username" value="" />
                     </p>
                     <p>
                        <label class="label" for="password">Passwort</label><input
                           type="text" name="password" value="" />
                     </p>
                     <input type="hidden" name="task" value="install" />
                     <input type="hidden" name="func" value="step5" />
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
