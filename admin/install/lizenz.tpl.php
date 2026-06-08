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
<title>Kanpai Classic Shopsoftware - Installation Step1</title>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="install.css" />
</head>

<body>
   <div id="seite">
      <div id="seite2">
         <div id="rahmen" style="width:534px;">
            <div id="rahmen-innen">
               <div>
                  <iframe style="width:464px; height:364px; border:1px solid #cccccc; background-color:#ffffff;" src="//www.kanpaiclassic.com/shoplizenz.html"></iframe>
               </div>
               <div style="width:464px; margin-top:12px;">
                  <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                     <span style="display:inline-block; width:30px; height:40px; float:left;">
                        <input type="checkbox" name="licence_check" style="vertical-align: top;"/>
                     </span>
                     <span style="display:inline-block; font-size: 12px;"> hiermit bestätige ich, dass ich die Lizenzvereinbarungen aufmerksam<br />gelesen habe und akzeptiere diese.</span>
                     <div style="width:464px; text-align:center; margin-top:12px;">
                        <input type="hidden" name="task" value="lizenz" />
                        <input type="hidden" name="func" value="lizenz" />
                        <input type="image" style="cursor:pointer; width:149px; height:35px; position:relative; display:inline-block;" src="install_btn1.jpg" alt="Installieren" />
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
</body>
</html>
