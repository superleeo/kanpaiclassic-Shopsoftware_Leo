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

 * if (!defined('KANPAICLASSIC')) {
   define('KANPAICLASSIC', true);
}
 */

// Funktionen alle Templates
define ('CONF_TEMPLATE_ID', 2); // Optionen für Template anzeigen
define ('CONF_NOPICT', 'nopic.png');
define ('CONF_MAX_SIZE', 800);         // max. Höhe/Breite Detailbilder - wird ignoriert
define ('CONF_MENU_HOEHE', 27);        // Höhe Menüeintrag (für Ausrichtung an Artikel-Zeile)
define ('CONF_THUMB_X', 215);          // Default Breite Vorschaubilder
define ('CONF_THUMB_Y', 162);          // Default Höhe der Vorschaubilder
define ('CONF_ARTIKEL_HOEHE', 233);    // Default Höhe der Artikelbox mit Rand
define ('CONF_BANNERBREITE', 1183);    // Default Breite der Seite
define ('CONF_ARTIKELZEILE', 4);       // Reihen pro Seite
define ('CONF_RECHTS_PROMO', 0);       // Anzahl Promotionartikel auf Artikeldetailseite
define ('CONF_NO_PROZENT_IMG', 'n');   // Prozent-Image (Sonderanbegote) wird nicht angezeigt wenn 'y'. Wenn auskommentiert oder anderer Wert wird es angezeigt

define ('CONF_MAGICZOOM_2', 'zoom-position: #zoomWindowLeft; zoom-width:100%; zoom-height:250px');
define ('CONF_MAGICZOOM_3', 'zoom-position: inner');
define ('CONF_MAGICZOOM_4', 'disable-zoom: true; expand-size: width=1024px; group: panzoom');

// Template-spezifisch
define ('CONF_ARTIKEL_PRO_REIHE', 1);    // Artikel pro Zeile Startwert
define ('CONF_THUMB_ZOOM', 1.2);         // Vergrößerung Thumbnails
define ('CONF_RESPONSIVE', 1);           // Responsive Design
define ('CONF_MAINMENU_ANZAHL', 9);      // Anzahl Hauptkategorien, die angezeigt werden
define ('CONF_SUBMENU_ANZAHL', 5);       // Max. Anzahl Sub-Kategorien, dann [more...] / 0 = alle
define ('CONF_MAXSIZE_PHONE', 530);      // Max-Wert für Darstellung als Phone
define ('CONF_MINSIZE_DESKTOP', 1180);   // Min-Wert für Darstellung als Desktop, Dazwischen Darstellung als Tablet

define ('CONF_THUMBWIDTH_NORMAL', 300);  // Breite Thumbnails klein und normal (+20%)
define ('CONF_THUMBWIDTH_BIG', 450);     // Breite Thumbnails gross und riesig (nur proportional) (+20%)

define ('CONF_SLIDESHOW_HEIGHT1', 2.1);  // Slideshow Breite / Höhe ohne Bilder rechts
define ('CONF_SLIDESHOW_HEIGHT2', 1.69); // Slideshow Breite / Höhe mit Bilder rechts
define ('CONF_SHOW_MENU_OVER', true);    // Horizontales Menu bei mouseover anzeigen. Zum Deaktivieren auskommentieren.


// Früher extended.config.php
define('EXT_ACC_POWER', 'Power3');       // Power0 ... Power5
define('EXT_ACC_EASE', 'easeInOut');     // easeIn, easeOut, easeInOut
define('EXT_ACC_BORDER_COLOR', '#aaaaaa');

define('EXT_CAR_T_WIDTH', '1183');
define('EXT_CAR_T_HEIGHT', '250');
define('EXT_CAR_T_POSITION_X', '0');
define('EXT_CAR_T_POSITION_Y', '-25');
define('EXT_CAR_T_FRAMES', '40');
define('EXT_CAR_T_PERSPECTIVE', '0');

define('EXT_CAR_C_WIDTH', '914');
define('EXT_CAR_C_HEIGHT', '447');
define('EXT_CAR_C_HEIGHT_COLLAGE', '360');
define('EXT_CAR_C_POSITION_X', '0');
define('EXT_CAR_C_POSITION_Y', '-25');
define('EXT_CAR_C_FRAMES', '40');
define('EXT_CAR_C_PERSPECTIVE', '0');

define('EXT_CAR_B_WIDTH', '1183');
define('EXT_CAR_B_HEIGHT', '250');
define('EXT_CAR_B_POSITION_X', '0');
define('EXT_CAR_B_POSITION_Y', '-25');
define('EXT_CAR_B_FRAMES', '40');
define('EXT_CAR_B_PERSPECTIVE', '0');

define('EXT_SLI_T_WIDTH', '1183');
define('EXT_SLI_T_HEIGHT', '220');
define('EXT_SLI_T_TOOLTIP_X', '0');
define('EXT_SLI_T_TOOLTIP_Y', '0');
define('EXT_SLI_T_FRAMES', '60');
define('EXT_SLI_T_SLIDER_Y', '165');

define('EXT_SLI_C_WIDTH', '914');
define('EXT_SLI_C_HEIGHT', '220');
define('EXT_SLI_C_HEIGHT_COLLAGE', '220');
define('EXT_SLI_C_TOOLTIP_X', '0');
define('EXT_SLI_C_TOOLTIP_Y', '0');
define('EXT_SLI_C_FRAMES', '60');
define('EXT_SLI_C_SLIDER_Y', '165');

define('EXT_SLI_B_WIDTH', '1183');
define('EXT_SLI_B_HEIGHT', '220');
define('EXT_SLI_B_TOOLTIP_X', '0');
define('EXT_SLI_B_TOOLTIP_Y', '0');
define('EXT_SLI_B_FRAMES', '60');
define('EXT_SLI_B_SLIDER_Y', '165');

define('ART_SLI_WIDTH', '1183');
define('ART_SLI_HEIGHT', '220');
define('ART_SLI_TOOLTIP_X', '0');
define('ART_SLI_TOOLTIP_Y', '0');
define('ART_SLI_FRAMES', '60');
define('ART_SLI_SLIDER_Y', '165');
