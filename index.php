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
$start_debug = microtime(true);

if (is_file(__DIR__.'/error_reporting_nicht_bei_kunden')) {
   ini_set('display_errors', 1);
   error_reporting(E_ALL);
}

else {
   ini_set('display_errors', 0);
}

// Ausgabe in UTF-8 erzwingen
ini_set('default_charset', 'UTF-8');
ini_set('serialize_precision', 12);

// Für IE, falls Shop im Frameset läuft
if(@strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false) {
   @header('P3P: CP="CAO COR CURa ADMa DEVa OUR IND ONL COM DEM PRE"');
}

// CORS setzen - wenn www. in Domain -> entfernen
$cors = $_SERVER['SERVER_NAME'];

if (substr($cors, 0, 4) == 'www.') {
   $cors = substr($cors, 4);
}

else {
//   $cors = 'www.'.$cors;
}

// TEST
//$cors = 'shop.hcns.de';
header('Access-Control-Allow-Origin: '.$cors);
//$startzeit = microtime(true);
require_once 'classes/control.class.php';
KANPAICLASSIC\Control::init();

$db          = KANPAICLASSIC\Control::getDB();
$params      = KANPAICLASSIC\Control::getParams();
$text        = KANPAICLASSIC\Control::getText();
// Parameter laden / $params->taask festlegen
$params->setParams();

if (!file_exists(TEMPLATE_PATH.'/css/template.json') || !isset($params->firma['bildschirmbreit'])) {
   echo '<h1>Design/Templatedesign muss gespeichert werden</h1>';
}

// /admin/css/admin.css / /admin/js/admin.js erstellen (aus /admin/developer)
if (is_file(TEMPLATE_PATH.'/developer_nicht_bei_kunden/make_css_js.php')) {
   require_once TEMPLATE_PATH.'/developer_nicht_bei_kunden/make_css_js.php';
}

// Shop deaktiviert
if ($params->firma['shop_on_check'] == 'n' && $params->task !== 'validateadmin') {
?>
<html>
<head>
   <title>Shop offline</title>
   <style>
   body { background-color:#ffffff; }
   img { text-align: center; position: absolute; margin: auto; top: 0; right: 0; bottom: 0; left: 0; image-orientation:from-image; }
   </style>
</head>
<body>
<a href="https://www.kanpaiclassic.com" title="Kanpai Classic Shopsoftware"><img alt="Kanpai Classic Shopsoftware" title="Kanpai Classic Shopsoftware" src="https://www.kanpaiclassic.com/shop_off.jpg" /></a>
</body>
</html>
<?php exit; }
// Achtung!!! Hier kein Zeilenumbruch für HTML-Ausgabe !!!
KANPAICLASSIC\Helper::checkClick($params->task, '', 0);

// vertikale Kategorienen
$cat_left            = ($params->firma['kategorien_links'] == 'y' || $params->firma['kategorien_links'] == 'l' ? true : false);
// Header bildschirmbreit
$is_flaeche_header   = ($params->firma['flaeche'] == 'n' ? false : true);
// Shopmitte bildschirmbreit
$is_flaeche_mitte    = ($params->firma['flaeche_hg'] == 'n'|| $cat_left ? false : true);
// Artikelliste bildschirmbreit
$is_flaeche_liste    = ($params->firma['bildschirmbreit'] == 'y' && ($params->task == 'kategorie' || $params->task == '' || !$cat_left) ? true :false);
// Footer bildschirmbreit
$is_flaeche_footer   = ($params->firma['flaeche_footer'] == 'n' ? false : true);

$device_detect       = false;

if (isset($_SESSION['device'])) {
   $device_detect = true;
}

else {
   $_SESSION['device'] = '';
}

$device       = $_SESSION['device'];
$langs        = $params->langs;
$lang         = $params->selected_lang;
$lang_back    = $params->selected_lang;

if ($params->task_sub != '') {
   $params->selected_lang = $params->task_sub;
}

$site_lang    = $params->site_lang;

$titel_tag    = '';
$description  = '';
$keywords     = '';

$promotext    = '';
$promotext2   = '';
$infotitel    = '';
$infotext     = '';
$isInfo       = false;

$artikel      = ['', ''];
$outtext      = '';
$promotext    = '';
$countertext  = '';
$script       = '';
$artikel_main = '';     // Hauptbereich Ausgabe
$text_check   = '';

// Links für Collage und Slideshow laden
$params->getLinks($lang);

$isExtended   = false;
if (defined('CONF_MODULE_EXTENDED')) {
   $isExtended = \KANPAICLASSIC\Control::getShopExtended();
}

// Texte, wenn vorhanden setzen params->task
$check_text = checkText($infotitel, $infotext);

// Werte für Titel, Keywords und Beschreibung holen
$key_obj = KANPAICLASSIC\Helper::getKeywords($params->task, $lang);

if (is_object($key_obj)) {
   $titel_tag   = $key_obj->titeltag;
   $description = $key_obj->description;
   $keywords    = $key_obj->keywords;
}

// Texte ausgeben und Beenden
if ($check_text) {
   // Falls titel_tag nicht gesetzt (aus Tabelle Keywords)
   if ($titel_tag == '' && $infotitel != '') {
      $titel_tag = $infotitel;
   }

   if ($params->isNew) {
      include TEMPLATE_PATH.'/info_new.tpl.php';
      return;
   }

   if ($params->task != 'kontakt') {
      $isInfo = true;
   }
}

// Validierungslink
if ($params->task == 'validate' || $params->task == 'validateadmin' || $params->task == 'validatenl') {
   include TEMPLATE_PATH . '/validate.tpl.php';
   return;
}

if ($params->task == 'download') {
   $dl = KANPAICLASSIC\Control::getDownload();
   $fehler = $dl->startDl($params->dl_link);

   if ($fehler == 0) {
      exit;
   }

   // Bei Fehler ausgeben
   include TEMPLATE_PATH . '/dl_fail.tpl.php';
   return;
}
	// ==================================================================
	// Restaurant route handlers
	// ==================================================================

	// Reservation handler: POST booking, GET display form
	if ($params->task == 'reservation') {
	   // POST: process booking
	   if ($params->getString('func') == 'book' && $_SERVER['REQUEST_METHOD'] == 'POST') {
	      // CSRF protection
	      if (!isset($_SESSION['reservation_token']) || !isset($_POST['token']) || $_SESSION['reservation_token'] !== $_POST['token']) {
	         $err = 'Sicherheitsuberprufung fehlgeschlagen. Bitte aktualisieren Sie die Seite.';
	         ob_start();
	         echo '<div class="error">'.htmlspecialchars($err, ENT_QUOTES, 'UTF-8').'</div>';
	         include TEMPLATE_PATH . '/reservation.tpl.php';
	         $artikel_main = ob_get_contents();
	         ob_clean();
	         return;
	      }

	      require_once 'classes/reservation.class.php';
	      $reservation = new KANPAICLASSIC\Reservation($db);
	      $result = $reservation->bookFromArray($_POST);

	      if (isset($result['success']) && $result['success']) {
	         // Send confirmation emails (if mail configured)
	         if (isset($params->firma['email']) && $params->firma['email'] != '') {
	            $mail = KANPAICLASSIC\Control::getMail();
	            $safeName  = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
	            $safeDate  = htmlspecialchars($_POST['date'], ENT_QUOTES, 'UTF-8');
	            $safeTime  = htmlspecialchars($_POST['time'], ENT_QUOTES, 'UTF-8');
	            $safePhone = htmlspecialchars(isset($_POST['phone']) ? $_POST['phone'] : '', ENT_QUOTES, 'UTF-8');
	            $persons   = (int)$_POST['persons'];

	            $customer_body  = "<h2>Reservierungsbestatigung</h2>";
	            $customer_body .= "<p>Vielen Dank fur Ihre Reservierung bei Miaowei Teppanyaki!</p>";
	            $customer_body .= "<table><tr><td><strong>Datum:</strong></td><td>{$safeDate}</td></tr>";
	            $customer_body .= "<tr><td><strong>Zeit:</strong></td><td>{$safeTime}</td></tr>";
	            $customer_body .= "<tr><td><strong>Personen:</strong></td><td>{$persons}</td></tr>";
	            if ($safePhone) $customer_body .= "<tr><td><strong>Telefon:</strong></td><td>{$safePhone}</td></tr>";
	            $customer_body .= "</table><p>Bei Anderungswunschen kontaktieren Sie uns bitte.</p>";
	            $customer_body .= "<p>Miaowei Teppanyaki<br>Augustaanlage 15, 68161 Mannheim</p>";
	            $mail->sendMail($_POST['email'], 'Reservierungsbestatigung - Miaowei Teppanyaki', $customer_body);

	            // Notify restaurant
	            $restaurant_body  = "<h2>Neue Reservierung</h2>";
	            $restaurant_body .= "<p><strong>Name:</strong> {$safeName}</p>";
	            $restaurant_body .= "<p><strong>Email:</strong> ".htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8')."</p>";
	            $restaurant_body .= "<p><strong>Telefon:</strong> {$safePhone}</p>";
	            $restaurant_body .= "<p><strong>Datum:</strong> {$safeDate} | <strong>Zeit:</strong> {$safeTime} | <strong>Personen:</strong> {$persons}</p>";
	            $mail->sendMail($params->firma['email'], 'Neue Reservierung - '.$safeName.' '.$safeDate, $restaurant_body);
	         }

	         // Clear CSRF token
	         unset($_SESSION['reservation_token']);

	         // Redirect with success flag
	         header('Location: '.strtok($_SERVER['REQUEST_URI'], '?').'?task=reservation&res_ok=1');
	         exit;
	      }
	      else {
	         $err = isset($result['error']) ? $result['error'] : 'Reservierung fehlgeschlagen';
	         ob_start();
	         echo '<div class="error">'.htmlspecialchars($err, ENT_QUOTES, 'UTF-8').'</div>';
	         include TEMPLATE_PATH . '/reservation.tpl.php';
	         $artikel_main = ob_get_contents();
	         ob_clean();
	         return;
	      }
	   }

	   // GET: display reservation form
	   $_SESSION['reservation_token'] = bin2hex(random_bytes(32));
	   ob_start();
	   $res_ok = (isset($_GET['res_ok']) && $_GET['res_ok'] == '1');
	   include TEMPLATE_PATH . '/reservation.tpl.php';
	   $artikel_main = ob_get_contents();
	   ob_clean();
	   return;
	}

	// Restaurant home page
	if ($params->task == 'restaurant_home') {
	   ob_start();
	   include TEMPLATE_PATH . '/restaurant_home.tpl.php';
	   $artikel_main = ob_get_contents();
	   ob_clean();
	   return;
	}

	// Menu page
	if ($params->task == 'menu') {
	   ob_start();
	   include TEMPLATE_PATH . '/menu_restaurant.tpl.php';
	   $artikel_main = ob_get_contents();
	   ob_clean();
	   return;
	}

	// Vouchers page
	if ($params->task == 'vouchers') {
	   ob_start();
	   include TEMPLATE_PATH . '/vouchers.tpl.php';
	   $artikel_main = ob_get_contents();
	   ob_clean();
	   return;
	}

	// Merch page
	if ($params->task == 'merch') {
	   ob_start();
	   include TEMPLATE_PATH . '/merch.tpl.php';
	   $artikel_main = ob_get_contents();
	   ob_clean();
	   return;
	}


/****** Start Normale Ausgabe *******************************************/
if (defined('CONF_ARTIKEL_PRO_REIHE') && !isset($_SESSION['artikel_pro_reihe'])) {
   $_SESSION['artikel_pro_reihe'] = CONF_ARTIKEL_PRO_REIHE;

   if ($_SESSION['artikel_pro_reihe'] == 1) {
      $_SESSION['artikel_pro_reihe'] = 12;
   }
}

if (defined('CONF_RESPONSIVE') && (!isset($_SESSION['artikel_reihen']) || $_SESSION['artikel_reihen'] < CONF_ARTZEILEN_MIN)) {
   $_SESSION['artikel_reihen'] = CONF_ARTZEILEN_DEFAULT;
}

$categories = KANPAICLASSIC\Control::getCategories();
// Inhalt Menü (<li>'s)
$kategorie  = '';

// Kategorien links
if ($params->firma['kategorien_links'] == 'l' || $params->firma['kategorien_links'] == 'y') {
   $kategorie = $categories->renderTree($params->kat_id, false);
}

// Kategorien horizontal
else if ($params->firma['kategorien_links'] == 'n' || $params->firma['kategorien_links'] == 'h') {
//   $kategorie = $categories->renderTree(0, true);
   $kategorie = $categories->renderTree($params->kat_id, true);
}

// Kategorien Dropdown
else {
   $kategorie = $categories->renderTreeSelect($params->kat_id);
}

$filter_check = false;
$mixer        = false;

if (defined('CONF_MODULE_FILTER')) {
   $filter_check = $categories->getFilterCheck($params->kat_id);
}

if ($params->kat_id > 0 && defined('CONF_MODULE_MIXER_KATEGORIE')) {
   $mixer_vals  = $categories->getMixerCheck($params->kat_id);
   $mixer       = ($mixer_vals && $mixer_vals->mixer_check == 'y' ? true : false);
}

// artikal erst nach kategorie, wenn gewählte Kategorie bekannt !!!
$articles = KANPAICLASSIC\Control::getArticles();

// Startseite, Artikel, Suche (kategorie) oder Kategorien
if ($params->task == 'kategorie' || $params->task == 'artikel' || $params->task == '') {
   if(isset($_SESSION['suche'])) {
      $articles->loadSuche();
   }

   // Startseiten Anzahl Artikel / nicht bei Mixer
   else if ($params->task != 'artikel' && !$mixer) {
       // Startseite und Artikelliste aus -> Artikel nicht laden

       if ($params->task != '' || !isset($params->firma['artikelliste_on']) || $params->firma['artikelliste_on'] != 'n') {
           $articles->loadArticles();
       }

   }

   // Artikel für Kategorien mit mixer_check = y
   if ($mixer) {
      $module_mixer = KANPAICLASSIC\Control::getModuleMixerKategorie();
      $artikel_main = $module_mixer->render($params->kat_id, $mixer_vals);
   }

   // Artikel normale Kategorie
   else {
      // Artikel auf Startseite deaktiviert
      if ($params->task == '' && isset($params->firma['artikelliste_on']) &&  $params->firma['artikelliste_on'] == 'n') {
          $artikel_main = '';
      }

      else {
         $articles->render($params->art_id, $artikel);
         $artikel_main = $artikel[0];
      }
   }

   if (isset($_SESSION['suche']) && $artikel_main == '' || $artikel_main == 'not found old' || $artikel_main == 'not found deactivated') {
      if (file_exists(TEMPLATE_PATH . '/article_not_found.tpl.php')) {
         include TEMPLATE_PATH . '/article_not_found.tpl.php';
      }

      else {
         header('Location: '.SHOP_URL);
         exit;
      }
   }

   // Titel und Description
   if ($params->task == 'artikel') {
      //$outtext = $params->art_text;
      $outtext = '';
   }

   else if ($params->task == 'kategorie') {
      if (!isset($_SESSION['suche'])) {
         $outtext = $params->cat_text;

         if (!$mixer) {
            $countertext = $params->hide_articles ? '' : $articles->getCounter();
         }
      }
   }

   // Startseite
   if ($params->task == '') {
      if (defined('CONF_RESPONSIVE')) {
         $countertext = ($params->firma['startseite_artikel'] == 'artikel' ? $articles->getCounter() : '');
      }
      else {
         $countertext = ($params->firma['startseite_artikel'] == 'artikel' ? $articles->getCounter() : '');
      }
   }

   if (defined('CONF_MODULE_PERSOCHECK') && $params->firma['fsk_show'] == 'y' && $params->task == 'kategorie' && $categories->getAlterCheck() == 'y' && !$_SESSION['alter_ok']) {
      $html = '';
      include TEMPLATE_PATH . '/alter.tpl.php';
      $artikel_main = $html;
      $outtext = '';
      $countertext = '';
   }
   else {
      unset($_SESSION['suche']);
   }

   $_SESSION['web_name'] = $titel_tag;
}

// Promo-Artikel erst nach Artikel, wenn Artikelanzahl bekannt.
if (!defined('CONF_RESPONSIVE')) {
   $promotext = $articles->promoArticle(-1);

   if (defined('CONF_RECHTS_PROMO')) {
      $promotext2 = $articles->promoArticle(CONF_RECHTS_PROMO);
   }
}

if ($params->task == 'login') {
   ob_start();
   include TEMPLATE_PATH . '/login.tpl.php';
   $artikel_main = ob_get_contents();
   ob_clean();
}

if ($params->task == 'bestellungfront') {
   ob_start();
   if (defined('CONF_MODULE_BESTELLUNGFRONT')) {
      include SHOP_PATH.'/classes/modules/bestellung_front/login.tpl.php';
   }
   else {
      include TEMPLATE_PATH.'/login.tpl.php';
   }

   $artikel_main = ob_get_contents();
   ob_clean();
}

// Portal erweitert
if ($params->task == 'anmelden') {
   ob_start();
   $script .= KANPAICLASSIC\Helper::htmlScript('   var anm_mode = 0;'."\n");
   // leeres User-Objekt (zum Eintragen)
   $user = KANPAICLASSIC\Control::getUser();
   $data = $_SESSION['user'];
   $data_err = $user->user_err;

   if ($params->task == 'anmelden') {
      $newsletter_text = readText('newsletter');
      include TEMPLATE_PATH . '/register.tpl.php';
   }

   $artikel_main = ob_get_contents();
   ob_clean();
}

if ($params->task == 'checkanmeldung') {
   ob_start();
   include TEMPLATE_PATH . '/register_ok.tpl.php';
   $artikel_main .= ob_get_contents();
   ob_clean();
}

if ($params->task == 'forgotten') {
   ob_start();
   include TEMPLATE_PATH . '/forgotten.tpl.php';
   $artikel_main .= ob_get_contents();
   ob_clean();
}

if ($params->task == 'sitemap.html') {
   ob_start();
   include TEMPLATE_PATH . '/sitemap.tpl.php';
   $artikel_main .= ob_get_contents();
   ob_clean();
}

// Artikel dem Warenkorb hinzufügen
if ($params->task == 'inwarenkorb') {
   $wk = KANPAICLASSIC\Control::getWk();
   $wk->addArticle();
   return;
}

// Warenkorb anzeigen / Aktualisieren
if ($params->task == 'warenkorb') {
   $wk_first        = ' wk_first';
   $wk              = KANPAICLASSIC\Control::getWk();
   $berechnung      = KANPAICLASSIC\Control::getBerechnungen();
   $laender         = KANPAICLASSIC\Control::getLaender();
   $newsletter_text = readText('newsletter');
   $paypal_mail     = $params->firma['paypal_mail'];
   $minpreis        = false;

   if (!isset($_SESSION['user_msg'])) {
      $_SESSION['user_msg'] = '';
   }

   // Artikel-Daten als Array von getArticleById() mit Daten aus WK aktualisiert/überschrieben als $wk_arr['data']
   $wk_arr     = $wk->berechneWk();
   $rabatt_pzt = 0;
   $tab        = KANPAICLASSIC\Helper::versandMode();

   if ($params->user_id > 0 && isset($_SESSION['user'])) {
      $rabatt_pzt = (float)$_SESSION['user']['rabatt'] / 100;
   }

   // data enthalt Array aus Artikeln
   $data = $wk_arr['data'];

   $anzahl_wk    = (is_array($data) ? count($data) : 0);
   $new_price    = 0.00;
   $new_price_ge = 0.00;
   $gesamt       = 0;

   if ($anzahl_wk > 0) {
      if (!isset($_SESSION['zahlart_error'])) { $_SESSION['zahlart_error'] = ''; }
      if (!isset($_SESSION['staat_error'])) { $_SESSION['staat_error'] = false; }

      // Im WK-Template finden keine Berechnungen statt
      $wk_steuer1      = $wk_arr['wk_steuer1'];
      $wk_steuer2      = $wk_arr['wk_steuer2'];
      $wk_steuer3      = $wk_arr['wk_steuer3'];
      $versand_land    = $wk_arr['versand_land'];
      $zahlart         = $wk_arr['zahlart']; // Index

      $rechnung_land   = $_SESSION['rechnung_land'];
      $lieferung_land  = $_SESSION['lieferung_land'];
      $wk_land         = $_SESSION['wk_land'];
      $staat_error     = $_SESSION['staat_error'];
      $zahlart_error   = $_SESSION['zahlart_error'];
      $user_msg        = $_SESSION['user_msg'];

      $widerruf_wk     = $params->widerruf_wk;

      // Preise mit USt anzeigen
      $steuersatz = 0;

      if ($wk_steuer1 != 0) {
         $steuersatz = $params->firma['tax1'];
      }

      else if ($wk_steuer2 != 0) {
         $steuersatz = $params->firma['tax2'];
      }

      else {
         $steuersatz = $params->firma['tax3'];
      }


      $abholung      = $berechnung->berechnePreis($params->firma['abholung_preis_'.$tab], $steuersatz, false);
      $nachnahme     = $berechnung->berechnePreis($params->firma['nachnahme_preis'], $steuersatz, false);
      $min_preis     = (float)$params->firma['min_preis_'.$tab];

      // Preise mit USt anzeigen
      if ($params->firma['tax_show'] == 'y') {
         $wk_summe            = $wk_arr['wk_summe_brutto'];
         $wk_netto            = $wk_arr['wk_summe_netto'];
         $versand_preis       = $wk_arr['versand_netto'] + $wk_arr['versand_ust'];
         $zahlart_preis       = $wk_arr['zahlart_netto'] + $wk_arr['zahlart_ust'];
         $gutschein           = $wk_arr['gutschein_netto'] + $wk_arr['gutschein_ust'];
         $gutschrift          = $wk_arr['gutschrift_netto'] + $wk_arr['gutschrift_ust'];
         $rabatt              = $wk_arr['rabatt_netto'] + $wk_arr['rabatt_steuer'];
         $gesamt              = $wk_arr['wk_summe_brutto'] + $versand_preis + $zahlart_preis - $gutschein - $gutschrift -$rabatt;

         if (isset($zahlart) && $zahlart != 6 && $params->firma['min_preis_check_'.$tab] == 'y' && round((float)$params->firma['min_preis_'.$tab], 2) > round($wk_summe, 2)) {
            $minpreis = true;
         }

         // '-0,00' verhindern
         if ($gesamt < 0 && $gesamt > -0.005) {
            $gesamt = 0;
         }

         // Für Option Zahlart
         $abholpreis          = $abholung['brutto'];
         $nachnahme_preis     = $nachnahme['brutto'];

         if ($params->wk_changed) {
            $new_price    = $data[$params->wk_changed_id]->preis_brutto;
            $price_ge     = $berechnung->berechnePreis($data[$params->wk_changed_id]->ge_netto, $steuersatz, false, true);
            $new_price_ge = $price_ge['brutto'];
         }
      }

      // Preise ohne USt anzeigen / B2B
      else {
         $wk_summe            = $wk_arr['wk_summe_netto'];
         $wk_netto            = $wk_arr['wk_summe_netto'];
         $versand_preis       = $wk_arr['versand_netto'];
         $zahlart_preis       = $wk_arr['zahlart_netto'];
         $gutschein           = $wk_arr['gutschein_netto'];
         $gutschrift          = $wk_arr['gutschrift_netto'];
         $rabatt              = $wk_arr['rabatt_netto'];
         $gesamt              = $wk_arr['wk_gesamt_netto'];

         if (isset($zahlart) && $zahlart != 6 && $params->firma['min_preis_check_'.$tab] == 'y' && round((float)$params->firma['min_preis_'.$tab], 2) > round($wk_netto, 2)) {
            $minpreis = true;
         }

         // Für Option Zahlart
         $abholpreis          = $abholung['netto'];
         $nachnahme_preis     = $nachnahme['netto'];

         if ($params->wk_changed) {
            $new_price    = $data[$params->wk_changed_id]->preis_netto;
            $new_price_ge = $data[$params->wk_changed_id]->ge_netto;
         }
      }

      $gutschrift      = $gutschrift + $gutschein;
      $agb_text        = readText('agb');

      // Widerruf auf Gültigkeit prüfen
      if ($params->widerruf_wk < 1 || $params->widerruf_wk > 5) {
         $params->widerruf_wk = 1;
      }

      $widerruf_text   = readText('widerruf'.$params->widerruf_wk);
      $gutschein_wert  = $wk_arr['gutschein_netto'] + $wk_arr['gutschein_ust'];
   }

   // Bei Preisänderung Klarna vermerken, egal welche Zahlart
   if (isset($_SESSION['klarna_price']) && $_SESSION['klarna_price'] != $gesamt) {
      $_SESSION['klarna_price'] = 0;
   }

   ob_start();
   include TEMPLATE_PATH . '/warenkorb.tpl.php';
   $wk_first = '';
   $artikel_main .= ob_get_contents();
   ob_clean();

   // nur Preise über Ajax aktualisieren
   if ($params->isAjax) {
      if (defined('CONF_RESPONSIVE')) {
         $refresh = 0;

         if (!isset($wk_land) || $_SESSION['last_versandland'] != $wk_land || $params->ajax_menge == 0) {
            $refresh = 1;
         }

         echo json_encode(['status'        => 'ok',
                           'wk_summe'      => $artikel_main,
                           'menge'         => $params->ajax_menge,
                           'menge_price'   => KANPAICLASSIC\Helper::number_format(str_replace([','], ['.'], $params->ajax_menge) * round($new_price, 2), 2, ',', '.'),
                           'menge_ok'      => $params->ajax_menge_ok,
                           'price_changed' => $params->wk_changed,
                           'new_price'     => KANPAICLASSIC\Helper::number_format($new_price, 2, ',', '.'),
                           'new_price_ge'  => KANPAICLASSIC\Helper::number_format($new_price_ge, 2, ',', '.'),
                           'minpreis'      => $minpreis,
                           'refresh'       => $refresh]);
         exit;
      }

      echo $artikel_main;
      exit;
   }
}

// Merkliste anzeigen / Aktualisierung
if ($params->task == 'merkliste') {
   $ml = KANPAICLASSIC\Control::getMl();

   $data = $ml->getML();
   ob_start();
   include TEMPLATE_PATH . '/merkliste.tpl.php';
   $artikel_main = ob_get_contents();
   ob_clean();
}

if ($params->task == 'bezahlung') {
   $user = KANPAICLASSIC\Control::getUser();
   $data = $user->user;
   ob_start();
   include TEMPLATE_PATH . '/bezahlung.tpl.php';
   $artikel_main = ob_get_contents();
   ob_clean();
}

// Template nicht vorhanden!!!
if ($params->task == 'bestellung') {
   ob_start();
   include TEMPLATE_PATH . '/bestellung.tpl.php';
   $artikel_main = ob_get_contents();
   ob_clean();
}

// Bestellung erfolgreich abgeschlossen
if ($params->task == 'bestellt') {
   ob_start();
   include TEMPLATE_PATH . '/bestellt.tpl.php';
   $artikel_main = ob_get_contents();
   ob_clean();
   unset($_SESSION['best_ok']);
}

// Bestellung erfolgreich - reg. Kunden oder Button geklickt.
if ($params->task == 'konto') {
   ob_start();
   $laender         = KANPAICLASSIC\Control::getLaender();
   $help            = KANPAICLASSIC\Control::getHelp();
   $user            = KANPAICLASSIC\Control::getUser();
   $data            = $user->user;
   $data_err        = $user->user_err;
   $konto           = KANPAICLASSIC\Control::GetKonto();
   $newsletter_text = readText('newsletter');
   include TEMPLATE_PATH . '/konto.tpl.php';
   $artikel_main    = ob_get_contents();
   ob_clean();
   unset($_SESSION['best_ok']);
}

// Formular Lieferung (Adresse + Lieferadresse) anzeigen
// Bestellung ohne Konto oder WK Adressen aktualisieren
if ($params->task == 'lieferung') {
   $user = KANPAICLASSIC\Control::getUser();

   // Bestellzusammenfassung mit leerer Lieferadresse starten
   if (defined('CONF_MODULE_BESTELLZUSAMMENFASSUNG') && (!isset($_SESSION['bestzus_adresse']) || $_SESSION['bestzus_adresse'] != 'ok')) {
      $user->user['lf_anrede']   = '';
      $user->user['lf_vorname']  = '';
      $user->user['lf_nachname'] = '';
      $user->user['lf_firma']    = '';
      $user->user['lf_adresse']  = '';
      $user->user['lf_hausnr']   = '';
      $user->user['lf_postnr']   = '';
      $user->user['lf_plz']      = '';
      $user->user['lf_ort']      = '';
      $user->user['lf_buland']   = '';
      $user->user['lf_staat']    = '160';
      $user->user['lf_staat2']   = '';

      $_SESSION['user'] = $user->user;
      $_SESSION['bestzus_adresse'] = 'ok';
   }

   $data     = $user->user;
   $data_err = $user->user_err;
   $newsletter_text = readText('newsletter');
   ob_start();
   include TEMPLATE_PATH . '/lieferung.tpl.php';
   $artikel_main = ob_get_contents();
   ob_clean();
}

if ($params->task == 'kontakt') {
   ob_start();

   $infotitel    = $text->get('menu', 'kontakt');
   $inhaber      = KANPAICLASSIC\Helper::getKontakt();
   $kontakt_text = readText('kontakt');

   include TEMPLATE_PATH . '/kontakt.tpl.php';

   $artikel_main = ob_get_contents();
   ob_clean();
}

if ($params->task == 'alter') {
   ob_start();
   include TEMPLATE_PATH . '/alter.tpl.php';
   $artikel_main = ob_get_contents();
   ob_clean();
}

if ($params->task == 'profil') {
   ob_start();
   require_once $params->filepath.'/classes/modules/portal/profil.tpl.php';
   $artikel_main = ob_get_contents();
   ob_clean();
}

if ($params->task == 'easycredit') {
   ob_start();
   include TEMPLATE_PATH . '/bez_module_easycredit_ok.tpl.php';
   $artikel_main = ob_get_contents();
   ob_clean();
}

// CB: Bezahlung mit Paydirekt ok
if ($params->task == 'payd_back') {
	ob_start();
	include TEMPLATE_PATH . '/bez_module_payd_ok.tpl.php';
	$artikel_main = ob_get_contents();
	ob_clean();
}

// Bezahlung mit Paypal Plus ok
if ($params->task == 'ppp_back') {
   ob_start();
   include TEMPLATE_PATH . '/bez_module_ppp_ok.tpl.php';
   $artikel_main = ob_get_contents();
   ob_clean();
}

// Bezahlung mit Amazon ok
if ($params->task == 'amazon_login') {
   ob_start();
   include TEMPLATE_PATH . '/bez_module_amazon.tpl.php';
   $artikel_main = ob_get_contents();
   ob_clean();
}

// Bezahlung per Paypal / Sofortüberweisung / VRpay usw abgebrochen
if ($params->task == 'amazon_fail' ||
    $params->task == 'paypal_fail' ||
    $params->task == 'ppp_fail' ||
    $params->task == 'vrpay_fail' ||
    $params->task == 'sofort_fail' ||
    $params->task == 'paydirekt_fail')
   {
   $bez_module_text = '';
   ob_start();
   include TEMPLATE_PATH . '/bez_module_fail.tpl.php';
   $artikel_main = ob_get_contents();
   ob_clean();
}

// Fehler bei der Übertragung der Daten von Paypal / Sofortüberweisung / VRpay
if ($params->task == 'paypal_error' || $params->task == 'sofort_error' || $params->task == 'vrpay_error') {
   $bez_module_text = '';
   ob_start();
   include TEMPLATE_PATH . '/bez_module_error.tpl.php';
   $artikel_main = ob_get_contents();
   ob_clean();
}

// Texte ausgeben
if ($isInfo) {
   ob_start();
   include TEMPLATE_PATH . '/info.tpl.php';
   $artikel_main = ob_get_contents();
   ob_clean();
}

$wk = KANPAICLASSIC\Control::getWk();
$wkAnzahl = $wk->getAnzahl();

ob_start();
include TEMPLATE_PATH . '/menu_oben.tpl.php';
$menu_oben = ob_get_contents();
ob_clean();

ob_start();
include TEMPLATE_PATH . '/menu_unten.tpl.php';
$menu_unten = ob_get_contents();
ob_clean();

$footer = KANPAICLASSIC\Helper::getFooter(true);
include TEMPLATE_PATH . '/template.tpl.php';
$params->selected_lang = $lang_back;
return;
/******** ENDE ********************************************/

function checkText(&$infotitel, &$infotext) {
   $db         = KANPAICLASSIC\Control::getDB();
   $params     = KANPAICLASSIC\Control::getParams();
   $text       = KANPAICLASSIC\Control::getText();

   $titel      = '';
   $found      = false;
   $firma_text = '';
   $htmlout    = '';
   $script     = '';

   $uns1 = KANPAICLASSIC\Helper::getUeberUns(1);
   $uns2 = KANPAICLASSIC\Helper::getUeberUns(2);
   $uns3 = KANPAICLASSIC\Helper::getUeberUns(3);
   $uns4 = KANPAICLASSIC\Helper::getUeberUns(4);
   $uns5 = KANPAICLASSIC\Helper::getUeberUns(5);

   switch (urldecode($params->task)) {
      case 'kontakt':
         $titel = $text->get('menu', 'kontakt');
         $firma_text = '';
         $found = true;
         $params->task = 'kontakt';
         break;

      case 'impressum':
         $titel = $text->get('menu', 'impressum');
         $firma_text = KANPAICLASSIC\Helper::getImpressum();
         $found = true;
         $params->task = 'impressum';
         break;

      case 'versand':
         $titel = $text->get('menu', 'versand');
         $found = true;
         $params->task = 'versand';
         break;

      case 'widerruf1':
         $titel = KANPAICLASSIC\Helper::getWiderruf(1);
         $found = true;
         $params->task = 'widerruf1';
         break;

      case 'widerruf2':
         $titel = KANPAICLASSIC\Helper::getWiderruf(2);
         $found = true;
         $params->task = 'widerruf2';
         break;

      case 'widerruf3':
         $titel = KANPAICLASSIC\Helper::getWiderruf(3);
         $found = true;
         $params->task = 'widerruf3';
         break;

      case 'widerruf4':
         $titel = KANPAICLASSIC\Helper::getWiderruf(4);
         $found = true;
         $params->task = 'widerruf4';
         break;

      case 'widerruf5':
         $titel = KANPAICLASSIC\Helper::getWiderruf(5);
         $found = true;
         $params->task = 'widerruf5';
         break;

      case 'agb':
         $titel = $text->get('menu', 'agb');
         $found = true;
         $params->task = 'agb';
         break;

      case 'kundeninfo':
         $titel = $text->get('menu', 'kundeninfo');
         $found = true;
         $params->task = 'kundeninfo';
         break;

      case 'agbh':
         $titel = $text->get('kunde', 'agbh');
         $found = true;
         $params->task = 'agbh';
         break;

      case 'datenschutz':
         $titel = $text->get('menu', 'datenschutz');
         $found = true;
         $params->task = 'datenschutz';

         break;

      case KANPAICLASSIC\Helper::checkLink($uns1, 'UTF-8'):
         $titel = $uns1;
         $found = true;
         $params->task = 'ueberuns1';
         break;

      case KANPAICLASSIC\Helper::checkLink($uns2, 'UTF-8'):
         $titel = $uns2;
         $found = true;
         $params->task = 'ueberuns2';
         break;

      case KANPAICLASSIC\Helper::checkLink($uns3, 'UTF-8'):
         $titel = $uns3;
         $found = true;
         $params->task = 'ueberuns3';
         break;

      case KANPAICLASSIC\Helper::checkLink($uns4, 'UTF-8'):
         $titel = $uns4;
         $found = true;
         $params->task = 'ueberuns4';
         break;

      case KANPAICLASSIC\Helper::checkLink($uns5, 'UTF-8'):
         $titel = $uns5;
         $found = true;
         $params->task = 'ueberuns5';
         break;
   }

   if ($found) {
      if (!defined('CONF_RESPONSIVE')) {
         $titel = mb_strtoupper($titel, 'UTF-8');
      }

      // Text aus DB lesen
      $_SESSION['task'] = $params->task;

      $sql = "SELECT text FROM #__seiten WHERE lang = '$params->selected_lang' AND art = '$params->task'";
      $test = 1;

      if (!$db->query($sql)) {
         // Text in gewünschter Sprache nicht vorhanden, dann Default-Sprache des Shops
         $sql = "SELECT text FROM #__seiten WHERE lang = '$params->default_lang' AND art = '$params->task'";
         $test = $db->query($sql);
      }

      if ($test) {
         $data        = $db->getObject();
         $inhalt      = $data->text;
         $description = KANPAICLASSIC\Helper::truncate($data->text, 250);
      }

      else {
         $inhalt = 'Kein Text vorhanden.';
      }

      if ($firma_text) {
         $firma_text .= '<div class="clear"></div>';
      }

      $infotitel = $titel;
      $infotext = \KANPAICLASSIC\Helper::checkTextToggle($firma_text.$inhalt);

   }
   return $found;
}

// Text aus text_seiten lesen und Text ersetzen, Skript extrahieren
function readText($art) {
   global $script, $text_check;
   $db     = KANPAICLASSIC\Control::getDB();
   $params = KANPAICLASSIC\Control::getParams();
   $data   = '';

   if ($art == 'newsletter') {
      $data = $db->querySingleObject("SELECT `text` FROM #__system_texte  WHERE lang = '$params->selected_lang' AND art = '$art'");

      if (!$data) {
         $data = (object)['text' => ''];
      }

      $data->check = 'y';
   }

   else {
      $data = $db->querySingleObject("SELECT `text`, `check` FROM #__seiten  WHERE lang = '$params->selected_lang' AND art = '$art'");
   }

   if ($data) {
      $tmp        = '';
      $text       = \KANPAICLASSIC\Helper::checkTextToggle($data->text);
      $text_check = $data->check;

      preg_match_all('|(<script.*?</script>)|ims', $text, $tmp);

      if (isset($tmp[1])) {
         $text = preg_replace('|(<script.*?</script>)|ims', '', $text);

         for ($i = 0; $i < count($tmp[1]); $i++) {
            $script .= $tmp[1][$i];
         }
      }

      return str_ireplace(
         ['[Shopname]', '[Shop-name]', '[Shop_name]', '[E-Mail]', '[E_Mail]', '[EMail]'],
         [$params->firma['shop_name'], $params->firma['shop_name'], $params->firma['shop_name'], $params->firma['email'], $params->firma['email'], $params->firma['email']],
         $text);
   }

   return '';
}
