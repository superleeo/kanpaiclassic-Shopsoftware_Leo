<?php
/*
###################################################################################
  Kanpai Classic Shopsoftware Entwicklungsstand: 09.02.2020 Version 2

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

// Debug-Verzeichnisse anlegen, falls diese nicht existieren
if (!is_dir(dirname(__FILE__).'/xdebug')) {
   mkdir(dirname(__FILE__).'/xdebug');
}

if (!is_dir(dirname(__FILE__).'/xdebug/log')) {
   mkdir(dirname(__FILE__).'/xdebug/log');
}

define ('DEBUG_LOG_DIR', dirname(__FILE__).'/xdebug/log');

// Einstellungen für Debug nicht als Module, im normalen Script// Einstellungen für Debug - Module und integrierte Funktionenen
if (is_file(dirname(__FILE__).'/xdebug/front_session_log')) {
   define('FRONT_SESSION_LOG', true);
}

if (is_file(dirname(__FILE__).'/xdebug/paypal_log')) {
   define('CONF_PAYPAL_DEBUG', true);
}

if (is_file(dirname(__FILE__).'/xdebug/paypal_sandbox')) {
   define('CONF_PAYPAL_SANDBOX', 'SANDBOX'); // Sandbox sonst Live
}

if (is_file(dirname(__FILE__).'/xdebug/paypalv2_sandbox')) {
   define('CONF_PAYPALV2_SANDBOX', 'SANDBOX'); // Sandbox sonst Live
}

if (is_file(dirname(__FILE__).'/xdebug/paypalv2_log')) {
   define('CONF_PAYPALV2_DEBUG', true);
}

if (is_file(dirname(__FILE__).'/xdebug/paypalplus_sandbox')) {
   define('CONF_PAYPALPLUS_SANDBOX', 'SANDBOX'); // Sandbox sonst Live
}

if (is_file(dirname(__FILE__).'/xdebug/paypalplus_log')) {
   define('CONF_PAYPALPLUS_DEBUG', true);
}

if (is_file(dirname(__FILE__).'/xdebug/amazon_log')) {
   define('CONF_MODULE_AMAZON_DEBUG', true); // /amazon.txt erstellen
}

if (is_file(dirname(__FILE__).'/xdebug/amazon_sandbox')) {
   define('CONF_MODULE_AMAZON_SANDBOX', 'SANDBOX'); // Sandbox sonst Live
}

if (is_file(dirname(__FILE__).'/xdebug/amazonorders_log')) {
   define('CONF_MODULE_AMAZONORDERS_DEBUG', true); // /amazonorders.txt erstellen
}

if (is_file(dirname(__FILE__).'/xdebug/amazonorders_sandbox')) {
   define('CONF_MODULE_AMAZONORDERS_SANDBOX', 'SANDBOX'); // Sandbox sonst Live
}

if (is_file(dirname(__FILE__).'/xdebug/sofort_log')) {
   define('CONF_SOFORT_DEBUG', true);
}

if (is_file(dirname(__FILE__).'/xdebug/sofortident_log')) {
   define('CONF_SOFORTIDENT_DEBUG', true);
}

if (is_file(dirname(__FILE__).'/xdebug/sofort_sandbox')) {
   define('CONF_SOFORT_SANDBOX', true);
}

if (is_file(dirname(__FILE__).'/xdebug/sofortident_sandbox')) {
   define('CONF_SOFORTIDENT_SANDBOX', true);
}

if (is_file(dirname(__FILE__).'/xdebug/ebay_log')) {
    define ('CONF_EBAY_DEBUG', true);
}

if (is_file(dirname(__FILE__).'/xdebug/ebay_sandbox')) {
    define ('CONF_EBAY_SANDBOX', 'SANDBOX');
}

if (is_file(dirname(__FILE__).'/xdebug/dhl_log')) {
    define ('CONF_DHL_DEBUG', true);
}

if (is_file(dirname(__FILE__).'/xdebug/dhl_sandbox')) {
    define ('CONF_DHL_SANDBOX', true);
}

if (is_file(dirname(__FILE__).'/xdebug/twint_log')) {
    define ('CONF_TWINT_DEBUG', true);
}

if (is_file(dirname(__FILE__).'/xdebug/twint_sandbox')) {
    define ('CONF_TWINT_SANDBOX', true);
}

if (is_file(dirname(__FILE__).'/xdebug/postfinance_log')) {
    define ('CONF_MODULE_POSTFINANCE_DEBUG', true);
}

if (is_file(dirname(__FILE__).'/xdebug/postfinance_sandbox')) {
    define ('CONF_MODULE_POSTFINANCE_SANDBOX', true);
}

if (is_file(dirname(__FILE__).'/xdebug/paydirekt_log')) {
   define('CONF_MODULE_PAYDIREKT_DEBUG', true);
}

if (is_file(dirname(__FILE__).'/xdebug/paydirekt_sandbox')) {
   define('CONF_MODULE_PAYDIREKT_SANDBOX', true);
}

if (is_file(dirname(__FILE__).'/xdebug/wiso_log')) {
   define('CONF_WISO_DEBUG', true);
}

if (is_file(dirname(__FILE__).'/xdebug/easycredit_log')) {
    define ('CONF_EASYCREDIT_DEBUG', true);
}

if (is_file(dirname(__FILE__).'/xdebug/easycredit_sandbox')) {
    define ('CONF_EASYCREDIT_SANDBOX', true);
}

if (is_file(dirname(__FILE__).'/xdebug/klarna_log')) {
    define ('CONF_KLARNA_DEBUG', true);
}

if (is_file(dirname(__FILE__).'/xdebug/klarna_sandbox')) {
    define ('CONF_KLARNA_SANDBOX', true);
}

if (is_file(dirname(__FILE__).'/xdebug/vrpay_log')) {
   define ('CONF_MODULE_VRPAY_DEBUG', true);
}

if (is_file(dirname(__FILE__).'/xdebug/vrpay_sandbox')) {
   define ('CONF_MODULE_VRPAY_SANDBOX', true);
}

if (is_file(dirname(__FILE__).'/xdebug/billbee_log')) {
   define('CONF_BILLBEE_DEBUG', true);
}

if (is_file(dirname(__FILE__).'/xdebug/mail_log')) {
   define('MAIL_DEBUG', true);
}

if (is_file(dirname(__FILE__).'/xdebug/mollie_sandbox')) {
   define('CONF_MOLLIE_SANDBOX', 'SANDBOX'); // Sandbox sonst Live
}

if (is_file(dirname(__FILE__).'/xdebug/mollie_log')) {
   define('CONF_MOLLIE_DEBUG', true);
}
// *********************** Module für Artikel ********************************
if (is_file(dirname(__FILE__).'/motiv_upload/motiv_upload.module.php')) {
   define ('CONF_MODULE_MOTIVUL', true);
}

if (!defined('CONF_FOTOGRAF')) {
   if (is_file(dirname(__FILE__).'/foto/foto.module.php')) {
      define ('CONF_FOTOGRAF', true);
      define ('CONF_MODULE_FOTO', true);
      define('CONF_FOTO_ZOOM', 1.8); // Als float 1.234, Punkt kein Komma!
      define('CONF_FOTO_PROMO', 4);  // Anzahl Artikel aus Set statt Details/Artikelbilder rechte Spalte
   }
}

if (is_file(dirname(__FILE__).'/extended/extended.module.php')) {
   define ('CONF_MODULE_EXTENDED', true);
}

if (is_file(dirname(__FILE__).'/vrpay/vrpay.module.php')) {
   define ('CONF_VRPAY', true);
   define ('CONF_MODULE_VRPAY', true);
}

if (is_file(dirname(__FILE__).'/masseingabe/masseingabe.module.php')) {
   define ('CONF_MODULE_MASSEINGABE', true);
}

if (is_file(dirname(__FILE__).'/kategorie_pass/kategorie_pass.module.php')) {
   define ('CONF_MODULE_KATEGORIEPASS', true);
}

if (is_file(dirname(__FILE__).'/backup/backup.module.php')) {
   define ('CONF_MODULE_BACKUP', true);
}

if (is_file(dirname(__FILE__).'/admindesign/admindesign.module.php')) {
   define ('CONF_MODULE_ADMINDESIGN', true);
}

if (is_file(dirname(__FILE__).'/multishop/multishop.module.php')) {
   define ('CONF_MODULE_MULTISHOP', true);
}

if (is_file(dirname(__FILE__).'/grafische_werte/grafische_werte.module.php')) {
   define ('CONF_MODULE_MW', true);
   include_once dirname(__FILE__).'/grafische_werte/grafische_werte.module.php';
}
// Modul Rabatte / Unterschiedlich für shop und portal
if (is_file(dirname(__FILE__).'/rabattgruppen/rabatte.module.php')) {
   define ('CONF_MODULE_RABATTE', true);
}

// Crosspromotion / Artikel-Slider für Detail-Ansicht
if (is_file(dirname(__FILE__).'/zubehoerslider/zubehoerslider.module.php')) {
   define ('CONF_MODULE_CROSSPROMO', true);
}

// Ähnliche Artikel / Artikel-Slider für Detail-Ansicht
if (is_file(dirname(__FILE__).'/aehnliche_artikel/aehnliche_artikel.module.php')) {
   define ('CONF_MODULE_AEHNLICHE', true);
}

// Modul Bildformat
if (is_file(dirname(__FILE__).'/bildformat/bildformat.module.php')) {
   define ('CONF_MODULE_BILDFORMAT', true);
}

// Modul Configurator
if (is_file(dirname(__FILE__).'/mega_konfigurator/mega_konfigurator.module.php')) {
   define ('CONF_MODULE_MEGACONFIGURATOR', true);
}

// Modul Markenfilter
if (is_file(dirname(__FILE__).'/markenfilter/markenfilter.module.php')) {
   define ('CONF_MODULE_MARKENFILTER', true);
}

// Modul Timer
if (is_file(dirname(__FILE__).'/artikeltimer/timer.module.php')) {
   define ('CONF_MODULE_TIMER', true);
}

// Modul Statistik
if (is_file(dirname(__FILE__).'/statistik/statistik.module.php')) {
   define ('CONF_MODULE_STATISTIK', true);
}

// Modul Variantenbilder
if (is_file(dirname(__FILE__).'/variantenbilder/variantenbilder.module.php')) {
   define ('CONF_MODULE_VARIANTENBILDER', true);
}

// Modul Webseite
if (is_file(dirname(__FILE__).'/website/website.module.php')) {
   define ('CONF_MODULE_WEBSITE', true);
}

// Modul Artikel Defragmentieren (Tabelle Artikel sortiert neu erstellen)
if (is_file(dirname(__FILE__).'/artikel_defragmentieren/artikel_defragmentieren.module.php')) {
   define ('CONF_ART_REORG', true);
}

// Modul Zubehoer(artikel)
if (is_file(dirname(__FILE__).'/zubehoermodul/zubehoermodul.module.php')) {
   define ('CONF_MODULE_ZUBEHOER', true);
}

// Modul Bestellzusammenfassung / nicht bei Portal
if (!defined('CONF_MODULE_PORTAL') && is_file(dirname(__FILE__).'/bestellzusammenfassung/bestellzusammenfassung.module.php')) {
   define ('CONF_MODULE_BESTELLZUSAMMENFASSUNG', true);
   require_once dirname(__FILE__).'/bestellzusammenfassung/bestellzusammenfassung.inc.php';
}

// Modul Bestellung Front / nicht bei Portal
if (is_file(dirname(__FILE__).'/bestellung_front/bestellung_front.module.php')) {
   define ('CONF_MODULE_BESTELLUNGFRONT', true);
}

// Modul Check_Perso / Altersüberprüfung mit PA
if (is_file(dirname(__FILE__).'/persocheck/persocheck.module.php')) {
   define ('CONF_MODULE_PERSOCHECK', true);
}
// EinsAShop / Kundenspezifisch
if (is_file(dirname(__FILE__).'/einsashop/einsashop.module.php')) {
   include dirname(__FILE__).'/einsashop/einsashop.module.php';
   define ('CONF_MODULE_EINSASHOP', true);
}

// Händlerbund
if (is_file(dirname(__FILE__).'/rechtstexte/haendlerbund.module.php')) {
   define ('CONF_MODULE_HAENDLERBUND', true);
}

// IT Recht Kanzlei
if (is_file(dirname(__FILE__).'/rechtstexte/itrechtkanzlei.module.php')) {
   define ('CONF_MODULE_ITRECHTKANZLEI', true);
}

// DHL-Modul im Portal nicht aktivieren
if (!defined('CONF_MODULE_PORTAL') && is_file(dirname(__FILE__).'/dhl_haendler/intraship.module.php')) {
   define ('CONF_MODULE_DHLHAENDLER', true);
}

// Modul Mein Büro Shop-Connector
if (is_file(dirname(__FILE__).'/wiso_mein_buero/mb_osc.php')) {
   define ('CONF_MODULE_MEINBUERO', true);
}

// Modul Orgamax Shop-Connector
if (is_file(dirname(__FILE__).'/orgamax/orgamax_osc.php')) {
   define ('CONF_MODULE_ORGAMAX', true);
}

// Modul Conversion
if (is_file(dirname(__FILE__).'/conversion_code/conversion_code.module.php')) {
   define ('CONF_MODULE_CONVERSION', true);
}

// Modul Gutscheine-Print
if (is_file(dirname(__FILE__).'/gutscheine_print/gutscheine_print.module.php')) {
   define ('CONF_MODULE_GUTSCHEINPRINT', true);
}

// Modul Musikplayer
if (is_file(dirname(__FILE__).'/musikplayer/musikplayer.module.php')) {
   define ('CONF_MODULE_MUSIKPLAYER', true);
}

// Modul Pdfkatalog
if (is_file(dirname(__FILE__).'/pdfkatalog/pdfkatalog.module.php')) {
   define ('CONF_MODULE_PDFKATALOG', true);
}

// Modul Sortierung
if (is_file(dirname(__FILE__).'/sortierung/sortierung.module.php')) {
   define ('CONF_MODULE_SORTIERUNG', true);
}

// Modul USK18
if (is_file(dirname(__FILE__).'/usk18/usk18.module.php')) {
   define ('CONF_MODULE_USK18', true);
}

// Modul Popup
if (is_file(dirname(__FILE__).'/popup/popup.module.php')) {
   define ('CONF_MODULE_POPUP', true);
}
// Modul Preismatrix
if (is_file(dirname(__FILE__).'/preismatrix/preismatrix.module.php')) {
   define ('CONF_MODULE_MATRIX', true);
}

// Modul Kategoriefilter
if (is_file(dirname(__FILE__).'/filter/filter.module.php')) {
//   define ('CONF_MODULE_KATEGORIEIFILTER', true);
   define ('CONF_MODULE_FILTER', true);
}

// Modul Headerscript
if (is_file(dirname(__FILE__).'/headerscript/headerscript.module.php')) {
   define ('CONF_MODULE_HEADERSCRIPT', true);
}

// Modul Mixer-Kategorien
if (is_file(dirname(__FILE__).'/mixer_kategorie/mixer_kategorie.module.php')) {
   define ('CONF_MODULE_MIXER_KATEGORIE', true);
}

// Modul Mixer-Artikel
if (is_file(dirname(__FILE__).'/mixer_artikel/mixer_artikel.module.php')) {
   define ('CONF_MODULE_MIXER_ARTIKEL', true);
}

// Modul Naehrwerte
if (is_file(dirname(__FILE__).'/naehrwerte/naehrwerte.module.php')) {
   define ('CONF_MODULE_NAEHRWERTE', true);
}


// Modul 360grad
if (is_file(dirname(__FILE__).'/360grad/360grad.module.php')) {
   define ('CONF_MODULE_360GRAD', true);
}

// Modul Spedition
if (is_file(dirname(__FILE__).'/spedition/spedition.module.php')) {
   define ('CONF_MODULE_SPEDITION', true);
}

// Modul WIR
if (is_file(dirname(__FILE__).'/wir/wir.module.php')) {
   define ('CONF_MODULE_WIR', true);
}



// *********************** Module Bezahlsysteme / Shopanbindung ********************************
if (is_file(dirname(__FILE__).'/kreditkarteneinzug/kreditkarteneinzug.module.php')) {
   define ('CONF_MODULE_KKEINZUG', true);
}

if (is_file(dirname(__FILE__).'/ebay/ebay.module.php')) {
   define ('CONF_MODULE_EBAY', true);
}

if (is_file(dirname(__FILE__).'/ebayorders/ebayorders.module.php')) {
   define ('CONF_MODULE_EBAYORDERS', true);
}

// Modul PaypalV2
if (is_file(dirname(__FILE__).'/paypal/paypal.module.php')) {
   define ('CONF_MODULE_PAYPALV2', true);
}

// Modul Mollie
if (is_file(dirname(__FILE__).'/mollie/mollie.module.php')) {
   define ('CONF_MODULE_MOLLIE', true);
}

// Modul PaypalPlus
if (is_file(dirname(__FILE__).'/paypalplus/paypalplus.module.php')) {
   define ('CONF_MODULE_PAYPALPLUS', true);
}

// Modul Amazon
if (is_file(dirname(__FILE__).'/amazon_payment/amazon.module.php')) {
   define ('CONF_MODULE_AMAZON', true);
}
// Modul Twint-Paiyemt
if (is_file(dirname(__FILE__).'/twint/twint.module.php')) {
   define ('CONF_MODULE_TWINT', true);
}

// Modul Üostfinance-Paiyemt
if (is_file(dirname(__FILE__).'/postfinance/postfinance.module.php')) {
   define ('CONF_MODULE_POSTFINANCE', true);
}

// Modul Easy-Credit
if (is_file(dirname(__FILE__).'/easycredit/easycredit.module.php')) {
   define ('CONF_MODULE_EASYCREDIT', true);
}

// Modul Amazon Bestellungen
if (is_file(dirname(__FILE__).'/amazonorders/amazonorders.module.php')) {
   define ('CONF_MODULE_AMAZONORDERS', true);
}

// Modul Sofort_Ident
if (is_file(dirname(__FILE__).'/sofort_ident/sofort_ident.module.php')) {
   define ('CONF_MODULE_SOFORTIDENT', true);
}

// Modul Trustedshops
if (is_file(dirname(__FILE__).'/trustedshops/trustedshops.module.php')) {
   define ('CONF_MODULE_TRUSTEDSHOPS', true);
}

// Modul Shopsiegel
if (is_file(dirname(__FILE__).'/shopsiegel/shopsiegel.module.php')) {
   define ('CONF_MODULE_SHOPSIEGEL', true);
   require_once dirname(__FILE__).'/shopsiegel/shopsiegel.module.php';
}

// Modul Billbee
if (is_file(dirname(__FILE__).'/billbee/billbee.module.php')) {
   define ('CONF_MODULE_BILLBEE', true);
}

// Modul Klarna
if (is_file(dirname(__FILE__).'/klarna/klarna.module.php')) {
   define ('CONF_MODULE_KLARNA', true);
}

// Modul PAYDIRECT
if (is_file(dirname(__FILE__).'/paydirekt/paydirekt.module.php')) {
   define ('CONF_MODULE_PAYDIREKT', true);
}

// Modul Livedesigner
if (is_file(dirname(__FILE__).'/livedesigner/livedesigner.module.php')) {
   define ('CONF_MODULE_LIVEDESIGNER', true);
}

// Modul Livedesigner 2
if (is_file(dirname(__FILE__).'/livedesigner2/livedesigner2.module.php')) {
   define ('CONF_MODULE_LIVEDESIGNER2', true);
}

// Modul Livedesigner Extended
if (is_file(dirname(__FILE__).'/livedesigner_ext/livedesigner_ext.module.php')) {
    define ('CONF_MODULE_LIVEDESIGNER_EXT', true);
}

// Modul Individuelle Grafik Artiklliste
if (is_file(dirname(__FILE__).'/artikelgrafik/artikelgrafik.module.php')) {
   define ('CONF_MODULE_ARTIKELGRAFIK', true);
}

// Modul Dropdownkategorien
if (is_file(dirname(__FILE__).'/dropdownkategorien/dropdownkategorien.module.php')) {
   define ('CONF_MODULE_DROPDOWNKATEGORIEN', true);
}

// Modul energieeffizienzlabel
if (is_file(dirname(__FILE__).'/energieeffizienzlabel/energieeffizienzlabel.module.php')) {
   define ('CONF_MODULE_ENERGIEEFFIZIENZLABEL', true);
}

// Modul energieeffizienzlabel
if (is_file(dirname(__FILE__).'/bonusprogramm/bonusprogramm.module.php')) {
   define ('CONF_MODULE_BONUSPROGRAMM', true);
}

// Modul energieeffizienzlabel
if (is_file(dirname(__FILE__).'/video/video.module.php')) {
    define ('CONF_MODULE_VIDEO', true);
}
