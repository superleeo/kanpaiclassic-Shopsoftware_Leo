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
$admin_dir = dirname(__DIR__);

if (!defined('KANPAICLASSIC')) {
   die ("This file cannot run outside the Kanpai Classic Shopsoftware");
}
$inst_server = $params->postString('inst_server', '', 'none');
$inst_user   = $params->postString('inst_user', '', 'none');
$inst_pass   = $params->postString('inst_pass', '', 'none');
$inst_db     = $params->postString('inst_db', '', 'none');
$inst_prefix = $params->postString('inst_prefix', '', 'none');
$inst_port   = str_replace(' optional', '', $params->postString('inst_port', '', 'none')); // Default 3306
$inst_socket = str_replace(' optional', '', $params->postString('inst_socket', '', 'none'));

$sqlerror    = 0;

// Test, ob alle Felder ausgefüllt wurden
if (!$inst_server or substr($inst_server, 0 ,1) == ' ') {
   $sqlerror = 1;
}

if (!$inst_user or substr($inst_user, 0 ,1) == ' ') {
   $sqlerror = 1;
}

if (!$inst_pass or substr($inst_pass, 0 ,1) == ' ') {
   $sqlerror = 1;
}

if (!$inst_db or substr($inst_db, 0 ,1) == ' ') {
   $sqlerror = 1;
}

// Wenn bisher Fehler aufgetreten sind - zurück, nochmals eingeben
if ($sqlerror) {
   include 'inst_step2.tpl.php';
   return;
}


// Alle Felder OK, Test, ob DB erreicbar ist
$sqlerror = '';
$db = null;

if ($inst_port == '' && $inst_socket == '') {
   $db = @new mysqli($inst_server, $inst_user, $inst_pass, $inst_db);
}
else if ($inst_port != '' && $inst_socket == '') {
   $db = @new mysqli($inst_server, $inst_user, $inst_pass, $inst_db, $inst_port);
}
else {
   $db = @new mysqli($inst_server, $inst_user, $inst_pass, $inst_db, $inst_port, $inst_socket);
}

if (mysqli_connect_error()) {
   $sqlerror = mysqli_connect_errno();
}

elseif (!$db->set_charset("utf8")) {
   $sqlerror .= $db->error;
}

// Wenn Verbindung mit DB fehlgeschlagen:
if ($sqlerror) {
   include 'inst_step2.tpl.php';
   return;
}

// Alles OK - Datenebank anlegen

// DB-Struktur
$sql = @file_get_contents('install.sql');
$i = 1;
if ($sql != '') {
   $sql = str_replace('#__', $inst_prefix, $sql);
   if ($db->multi_query($sql)) {
      do {
         $i++;
         /* store first result set */
         if ($result = $db->store_result()) {
            while ($row = $result->fetch_row()) {
            }
            $result->free();
         }
         if ($db->more_results()) {
         }
       } while (@$db->next_result());
   }

   if ($db->errno) {
      die ("<br /><span style='color:#cc0000'>Shop-Datenbank konnten nicht angelegt werden. Fehler bei Befehl $i: $db->error</span>");
   }
}

// Daten in DB speichern
$sql = @file_get_contents('install_daten.sql');
$i = 1;
if ($sql != '') {
   $sql = str_replace('#__', $inst_prefix, $sql);
   if ($db->multi_query($sql)) {
      do {
         $i++;
         /* store first result set */
         if ($result = $db->store_result()) {
            while ($row = $result->fetch_row()) {
            }
            $result->free();
         }
         if ($db->more_results()) {
         }
       } while (@$db->next_result());
   }

   if ($db->errno) {
      die ("<br /><span style='color:#cc0000'>Shop-Daten konnten nicht in DB gespeichert werden. Fehler bei Befehl $i: $db->error</span>");
   }
}

else {
   die("SQL-Datei 'Shop-Daten' konnte nicht gelesen werden");
}

// System-Texte in DB speichern
$sql = @file_get_contents('../reset_system_texte.sql');

if ($sql != '') {
   $sql = str_replace('#__', $inst_prefix, $sql);
   if ($db->multi_query($sql)) {
      do {
      } while (@$db->next_result());
      $sqlerror = $db->error;
   }
   else {
      die ("Systemtexte konnten nicht in DB gespeichert werden: " . $sqlerror);
   }
}
else {
   die("SQL-Datei 'Systemtexte' konnte nicht gelesen werden");
}

/*
// Netzkategorien Portal
if (is_file(str_replace('/admin/install', '', dirname(__FILE__)).'/classes/modules/portal/haendler.module.php')) {
   // Kategorien Portal anlegen
   $sql = @file_get_contents('install_categories_portal.sql');
   if ($sql) {
      $sql = str_replace('#__', $inst_prefix, $sql);
      if ($db->multi_query($sql)) {
         do {
         } while (@$db->next_result());
         $sqlerror = $db->error;
      }
      else {
         echo $db->error;
         die ("Datenbank Kategorie Portal konnte nicht angelegt werden");
      }
   }
   else {
      die ("Fehler, SQL-Datei 'Netzkategorien' konnte nicht gelesen werden.");
   }

   // Netzkategorie anlegen
   $sql = @file_get_contents('install_net_categories_portal.sql');
   if ($sql) {
      $sql = str_replace('#__', $inst_prefix, $sql);
      if ($db->multi_query($sql)) {
         do {
         } while (@$db->next_result());
         $sqlerror = $db->error;
      }
      else {
         echo $db->error;
         die ("Datenbank Netzkategorien Portal konnte nicht angelegt werden");
      }
   }
   else {
      die ("Fehler, SQL-Datei 'Netzkategorien' konnte nicht gelesen werden.");
   }
}

// Netzkategorien Shop
else {
   // Netzkategorie anlegen
   $sql = @file_get_contents('install_net_categories.sql');
   if ($sql) {
      $sql = str_replace('#__', $inst_prefix, $sql);
      if ($db->multi_query($sql)) {
         do {
         } while (@$db->next_result());
         $sqlerror = $db->error;
      }
      else {
         echo $db->error;
         die ("Datenbank Netzkategorien konnte nicht angelegt werden");
      }
   }
   else {
      die ("Fehler, SQL-Datei 'Netzkategorien' konnte nicht gelesen werden.");
   }
/*
   $sql = @file_get_contents('install_net_categories2.sql');
   if ($sql) {
      $sql = str_replace('#__', $inst_prefix, $sql);
      if ($db->multi_query($sql)) {
         do {
         } while (@$db->next_result());
         $sqlerror = $db->error;
      }
      else {
         echo $db->error;
         die ("Datenbank Netzkategorien konnte nicht angelegt werden");
      }
   }
   else {
      die ("Fehler, SQL-Datei 'Netzkategorien' konnte nicht gelesen werden.");
   }

}
*/
$sql = "INSERT INTO " . str_replace('#__', $inst_prefix, '#__firma')."
   (shop_name, firm_name, first_name, last_name, street, postal_code, city, country, email, mailfrom, default_lang, langs)
   VALUES ('', '', '', '', '', '', '', '', '', '', 'deu', '')";

$sql = "SELECT version FROM " . str_replace('#__', $inst_prefix, '#__firma') . " WHERE id = 1";
$res = $db->query($sql);
$data = $res->fetch_object();
$version_db = (int)$data->version;

$db->close();

// Konfigurationsdatei erstellen / speichern
$datei = @file_get_contents($admin_dir.'/install/config.install');
$erg = @file_put_contents($admin_dir.'/config.temp', "<?php\n" . $datei . "\ndefine('CONF_DBHOST', '$inst_server');\ndefine('CONF_DBUSER', '$inst_user');\ndefine('CONF_DBPASS', '$inst_pass');\ndefine('CONF_DATABASE', '$inst_db');\ndefine('CONF_DBPORT', '$inst_port');\ndefine('CONF_DBSOCKET', '$inst_socket');\ndefine('CONF_DBPREFIX', '$inst_prefix');\n?>\n");
if (!$erg) {
   die ("Fehler: Die Konfiguration konnte nicht gespeichert werden.");
}

@chmod($admin_dir.'/config.temp', 0666);
include $admin_dir.'/install/inst_step3.tpl.php';
