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

$rel_path = dirname(dirname(__DIR__));

$ok       = true;
$func_gd  = false;
$html     = '';
$html_err = '';
$php_check = true;

if (version_compare(PHP_VERSION, '7.3.0', '<')) {
   $html .= '<div class="error">Sie verwenden PHP Version '.PHP_VERSION.'<br />Für die Installation ist PHP&nbsp;7.3 oder höher notwendig!.<br /><br /></div>';
   $php_check = false;
}

// else {

   // $makedirs = array($rel_path.'/downloads', $rel_path.'/export', $rel_path.'/pictures', $rel_path.'/pictures/originale', $rel_path.'/tmp');
   // foreach ($makedirs as $dir) {
   //    if (!is_dir($dir)) {
   //       mkdir($dir);
   //    }
   //    copy ('index.html', $dir.'/index.html');
   //    if (basename($dir) != 'export') {
   //       copy ('./htaccess', $dir.'/.htaccess');
   //    }
   // }

   $dirs = [$rel_path."/pictures", $rel_path."/admin", $rel_path."/tmp"];
   $files = [];

   if ($handle = opendir($rel_path.'/templates/')) {
      while (false !== ($dir = readdir($handle))) {
         if ($dir != "." && $dir != ".." && $dir != ".svn" && is_dir($rel_path.'/templates/'.$dir)) {
            $files = [$rel_path.'/templates/'.$dir.'/css/template.css'];
         }
      }
      closedir($handle);
   }

   foreach ($dirs as $dir) {
      $mode = sprintf("%03o", (@fileperms($dir) & 0777));
      if ($mode == "777" or $mode == "755") {
         //      $html .= '<div class="no-error">' . $dir . " (" . $mode . ")</div>";
      }
      else {
         $html_err .= '<div class="error">aktuell: ' . $dir . " (" . $mode . ")</div>bitte ändern in: (755)";
         $ok = false;
      }
   }

   if ($ok && !copy('banner.css', $rel_path.'/tmp/config.install')) {
      $html_err .= '<div class="error">Fehler beim Kopieren der Dateien</div>';
      $ok = false;
   }

   if ($ok) {
      foreach ($files as $dir) {
         $mode = sprintf("%03o", (@fileperms($dir) & 0777));
         if ($mode == "777" or $mode == "755" or $mode =="644" or $mode =="666") {
         }
         else {
            $html_err .= '<div class="error">aktuell: ' . str_replace($rel_path, '', $dir) . " (" . $mode . ")</div>bitte ändern in: (666)";
            $ok = false;
         }
      }
   }

   unlink($rel_path.'/tmp/config.install');

   if ($ok) {
      $html .= "<p>Rechte für Verzeichnisse und Dateien: OK</p>";
   }

   else {
      $html .= "<br /><p>Für folgende Verzeichnisse und Dateien müssen die Rechte über FTP angepasst werden:</p>\n" . str_replace($rel_path, '', $html).$html_err.'<br />';
   }

   $maxfile   =  ini_get('upload_max_filesize');
   ini_set('upload_max_filesize', '40M');
   $newfile  = ini_get('upload_max_filesize');
   $filetext = '';

   if ($maxfile != $newfile) {
       $filetext = " kann auf 40M oder mehr erhöht werden";
   }
   $html .= "<p>Max. Upload-Größe: $maxfile $filetext</p>";

   $ok1   = true;
   $html2 = '';

   if (!function_exists('gd_info')) {
      $ok1 = false;
      $html2 .=  "<div class='error'>GD2-Lib nicht vorhanden</div>\n";
   }

   // Bei falscher PHP-Version nicht anzeigen
   if ($php_check && $ok1) {
      $html .= "<p>Benötigte PHP-Funktionen: OK</p>";
      $html .= "<p>PHP-Version ".PHP_VERSION."</p>";
   }

   else {
      $html .= "<p>Folgende PHP-Funktionen sind nicht verfügbar.\n<br />\nObadja&reg; Shopsystem kann nicht installiert werden</p>\n$html2";
   }

   $input = '<input type="hidden" name="task" value="install" />';
   $input2 = '';

   if ($ok && $ok1) {
      $input .= '<input type="hidden" name="func" value="step2" />';
      $input .= '<input type="image" style="cursor:pointer; width:149px; height:35px;" src="install_btn2.jpg" alt="Installieren" />';
   }

   else {
      $input .= '<input type="hidden" name="func" value="step1" />';
      $input .= '<input type="image" style="cursor:pointer; width:149px; height:35px;" src="install_btn4.jpg" alt="Wiederholen" />';

      $input2 .= '<input type="hidden" name="task" value="install" />';
      $input2 .= '<input type="hidden" name="func" value="step2" />';
      $input2 .= '<input type="image" style="cursor:pointer; width:75px; height:16px; margin-top:10px;" src="install_btn5.jpg" alt="Überspringen" />';
   }
// }

function copydir($vonDir, $nachDir) {
   $ok = true;
   if (!is_writable($nachDir)) {
      $ok = false;
   }

   if (!is_dir($vonDir)) {
      $ok = false;
   }

   if (!$ok) {
      return false;
   }

   // . / .. überlesen
   $ausnahmen = ['.','..'];
   $von = '';
   $nach = '';
   $handle = opendir($vonDir);
   while (($datei = readdir($handle)) !== false) {
      if (!in_array($datei, $ausnahmen)) {
         // Slashes korrigieren
         $von  = str_replace('//', '/', $vonDir.'/'.$datei);
         $nach = str_replace('//', '/', $nachDir.'/'.$datei);
      }
      else {
         continue;
      }

      if (is_dir($von)) {
         if (@mkdir($nach)) {
            chmod($nach, 0777);
         }
         else {
            $ok = false;
         }

         if ($ok) {
            $ok = copydir($von, $nach);
         }
      }

      if (is_file($von)) {
         if (copy($von, $nach)) {
            chmod($nach, 0666);
         }
         else {
            $ok = false;
         }
      }
   }
   closedir($handle);
   return $ok;
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
<title>Kanpai Classic Shopsoftware - Installation Step 1</title>
<meta charset="UTF-8">
<link rel="stylesheet" type="text/css" href="install.css" />
</head>

<body>
   <div id="seite">
      <div id="seite2">
         <div id="rahmen">
            <div id="rahmen-innen">
               <h1>Installationsassistent</h1>
               <h2>Schritt 1</h2>
               <?php echo $html; ?>
               <br /> <br />
               <h2>Schritt 2</h2>
               <p>Bitte Legen Sie eine MySQL-Datenbank bei Ihrem Provider an und
                  schreiben Sie sich die Zugangsdaten auf:</p>

               <ul>
                  <li>Server- / Hostname</li>
                  <li>Benutzer</li>
                  <li>Passwort</li>
                  <li>Datenbankname</li>
               </ul>

               <div style="text-align:center; margin-top:20px;">
                  <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                     <?php echo $input; ?>
                  </form>
                  <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                     <?php echo $input2; ?>
                  </form>
               </div>
               <div style="text-align:center; margin-top:20px;">
                  <span>Oder Installation beauftragen:<br />
                  </span>
                  <a style="text-decoration:none; font-size:11px; color:#000000;" href="mailto:info@kanpaiclassic.com?subject=Installation Shopsoftware"><b>info@kanpaiclassic.com</b></a>
               </div>
            </div>
         </div>
      </div>
   </div>
</body>
</html>
