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
   define('KANPAICLASSIC', 1);
}
// admin
define ('CONF_DEFAULT_LANG', 'deu');
define ('CONF_ART_PER_SITE', 100);
define ('CONF_ART_MAX', 400);
define ('ADMIN_MODE', 'update'); // update
define ('CONF_ADMIN_BTN1_LINK', 'https://www.kanpaiclassic.com/k24/Flow-Shopsoftware-Module');
define ('CONF_ADMIN_BTN2_LINK', 'https://help.kanpaiclassic.com/flow3updates/');
define ('CONF_PICT_PATH', 'pictures/');
define ('CONF_MAX_KAT', 300);
define ('CONF_HAUPTCAT', 2);              // 1 = H1; 2 = Hauptkategorie ist H2
define ('CONF_ARTNAME_DETAIL', 1);        // 1 = H1; 2 = Artikelname auf Detailseite ist H2

// user
define ('CONF_ARTZEILEN_MIN', 4);
define ('CONF_ARTZEILEN_MAX', 16);
define ('CONF_ARTZEILEN_DEFAULT', 12);

define ('CONF_BEWERTUNG', 7);             // Anzahl Tage für Bewertungsmail
define ('CONF_BEWERTUNG_MODE', 'none'); // 'bestellung' oder 'none'
define ('CONF_DOWNLOAD_MAX', 7);          // Anzahl, wie oft der Download heruntergeladen werden kann, 0 => unendlich
define ('CONF_DOWNLOAD_DAYS', 7);         // Anzahl Tage, wie lange der Download-Link gültig ist, 0 => unendlich

#define ('CONF_WIDERRUF_DL', true);       // Anzeige bei Systemtexten
#define ('CONF_KONTAKT_KUNDE', 1);        // kein "#" am Anfang der Zeile -> Kunden-E-Mail (statt Shop-E-Mail) als Absender
#define ('CONF_ALTER_PFLICHT', true);     // kein "#" am Anfang der Zeile -> wird bei Anmeldung Geburtsdatum abgefragt
define ('CONF_CHANGE_STATUS', 'true');    // kein "#" am Anfang der Zeile -> Neukunde wird bei RE erstellen Stammkunde / Rabattgruppe 1
define ('CONF_POPUP', 1);                 // kein "#" am Anfang der Zeile -> Weiter-Einkaufen-Popup in Admin aktiv
#define ('CONF_WEITEREINKAUFEN', 1);      // kein "#" am Anfang der Zeile -> Weiter-Einkaufen-Button schließt Popup, ansonsten linkt Button zurück in Artikelliste
define ('CONF_HAEKCHEN', 1);              // kein "#" am Anfang der Zeile -> Häkchen in WK, Widerruf, Datenschutz anzeigen
define ('CONF_AUTO_BESTELLUNG', '');      // kein "#" am Anfang der Zeile -> bestellungen.csv wird nach jeder Bestellung ergänzt
#define ('CONF_AUTO_BUCHUNG', '');        // kein "#" am Anfang der Zeile -> Buchungen-Export Monat wird Tageweise gespeichert
define ('CONF_PORTAL_IMPORT', true);      // Portal: Admin-Menüpunkt Import anzeigen
define ('CONF_RE_PREFIX', 'KanpaiClassic_');   // Portal: Prefix für automatische Rechnungen
#define ('CONF_RECHNUNG_PREFIX', '');     // kein "#" am Anfang der Zeile -> Shop: Prefix für Rechnungsnummer
define ('CONF_CATLINKS', 2);              // kein "#" am Anfang der Zeile -> Anzahl Kategorienamen in Kategorie-URL, sonst alle Kategorienamen
#define ('CONF_ARTIKEL_EXPORT', 1);       // kein "#" am Anfang der Zeile -> Artikel-Export CSV speichern (html / text)

#define ('CONF_ADCELL', 1);
#define ('CONF_ADCELL_EVENTID', '1');
#define ('CONF_ADCELL_PID', '1');

#define ('CONF_USE_HTACCESS', 1);          // mit "#" am Anfang der Zeile, wenn Provider kein Rewrite hat
#define ('CONF_ART_DETAIL_NONAME', 1);     // mit "#" am Anfang der Zeile, wenn Provider kein Rewrite hat
define ('CONF_USE_HTTPS', false);         // false / true : erzwingt https://
define ('CONF_USEADMIN_HTTPS', false);    // false / true : erzwingt https:// für admin
#define ('CONF_USE_HTTP_HOST', true);     // Bei falsch konfigurierten Webserver (SERVER_NAME wird verändert www.server.de -> server.de)
#define ('CONF_LOGIN_FAILED', 20);        // kein "#" am Anfang der Zeile -> Anzahl erlaubter falscher Logins & bei Fehllogin E-Mail an Admin
define ('CONF_DBCRYPT', 'AS811');         // Passwort-Verschlüsselung
define('CONF_DBHOST', '127.0.0.1');
define('CONF_DBUSER', 'root');
define('CONF_DBPASS', '');
define('CONF_DATABASE', 'flow_shop');
define('CONF_DBPORT', '3306');
define('CONF_DBSOCKET', '');
define('CONF_DBPREFIX', 'shop_');
