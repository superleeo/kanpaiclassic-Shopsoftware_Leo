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

namespace KANPAICLASSIC;
define ('KANPAICLASSIC', true);

define ('ADMIN_VERSION', 'Version III 14.5');
define ('HELP_LINK', 'https://help.kanpaiclassic.com');
define ('ADMIN_FOOTER_UPDATE',  HELP_LINK.'/adminbanner_update.jpg');
define ('ADMIN_FOOTER_DEMO',    HELP_LINK.'/adminbanner_demo.jpg');
define ('ADMIN_FOOTER_MIETE',   HELP_LINK.'/adminbanner_miete.jpg');
define ('ADMIN_FOOTER_PARTNER', HELP_LINK.'/adminbanner_partner.jpg');
define ('ADMIN_LINK_UPDATE',    HELP_LINK.'/adminbanner_update.link');
define ('ADMIN_LINK_DEMO',      HELP_LINK.'/adminbanner_demo.link');
define ('ADMIN_LINK_MIETE',     HELP_LINK.'/adminbanner_miete.link');
define ('ADMIN_LINK_PARTNER',   HELP_LINK.'/adminbanner_partner.link');

if (is_file(dirname(__DIR__).'/error_reporting_nicht_bei_kunden') || is_file(dirname(__DIR__).'/error_reporting_admin_nicht_bei_kunden')) {
   ini_set('display_errors', 1);
   error_reporting(E_ALL);
}

else {
   ini_set('display_errors', 0);
}

// Ausgabe in UTF-8 erzwingen
ini_set('default_charset', 'UTF-8');
ini_set('serialize_precision', 12);

$startzeit = microtime(true);

// Test, ob Installation durchgeführt ist
if (!file_exists('config.inc.php') || file_exists('config.temp')) {
   if (file_exists('install/install.php')) {
      header('Location: install/install.php');
      exit;
   }

   else {
      die('Installations-Verzeichnis nicht vorhanden');
   }
}

include_once 'classes/control.class.php';
Control::init();

$db     = Control::getDB();
$params = Control::getParams();
$text   = Control::getText();

// /admin/css/admin.css / /admin/js/admin.js erstellen (aus /admin/developer)
if (is_file(ADMIN_PATH.'/developer_nicht_bei_kunden/make_admin_css_js.php')) {
   require_once ADMIN_PATH.'/developer_nicht_bei_kunden/make_admin_css_js.php';
}

// Logout
if ($params->task == 'logout') {
   $haendler            = $_SESSION['haendler'];
   $_SESSION['user_id'] = 0;
   $params->user_id     = 0;
   $cparams             = session_get_cookie_params();

   setcookie(session_name(), '', time() - 420000, $cparams["path"], $cparams["domain"], $cparams["secure"]);
   session_destroy();
   $_SESSION = [];

   header('Location: '.ADMIN_URL_IDX);
   exit;
}

// forgotten vor Test auf Login
if ($params->func == 'forgotten') {
   include "classes/$params->task.class.php";
   $classname    = "\KANPAICLASSIC\KANPAICLASSIC_".$params->task;
   $contentclass = new $classname();
   $content      = $contentclass->getContent();
}

// Test auf Login
if (!isset($_SESSION['user_id']) || (int)$_SESSION['user_id'] < 1 || $params->task == 'login') {
   // Login prüfen, dann Redirect
   $params->adminLogin();

   $loginerror = $params->loginerror;

   if (!$params->user_id || (int)$params->user_id == 0) {
      require_once ADMIN_PATH.'/templates/login.tpl.php';
   }

   exit;
}

$cat_left            = ($params->firma['kategorien_links'] == 'l' || $params->firma['kategorien_links'] == 'y' ? true : false);
$is_flaeche_header   = ($params->firma['flaeche'] == 'n' ? false : true);
$is_flaeche_mitte    = ($params->firma['flaeche_hg'] == 'n'|| $cat_left ? false : true);
$is_flaeche_liste    = ($params->firma['bildschirmbreit'] == 'y' && ($params->task == 'kategorie' || $params->task == 'designLivedesigner' || !$cat_left) ? true :false);
$is_flaeche_footer   = ($params->firma['flaeche_footer'] == 'n' ? false : true);

if ($params->task == 'home' or $params->task == '') {
   $params->task = "bestellungen";
}

if ($params->task == 'einstellungen') {
   $params->task = 'shopinhaber';
}

if ($params->task == 'pdf') {
   $pdf = Control::getPdf();
   $pdf->makePdf(1, 'rechnung');
   return;
}

// Bei Portal
else if ($params->task == 'haendler' && defined('CONF_MODULE_PORTAL')) {
   require_once "../classes/modules/portal/portal.module.php";
   $classname = "KANPAICLASSIC_" . strtoupper(substr($params->task, 0,1)) . substr($params->task,1);
   $contentclass = new $classname();

   if (is_object($contentclass)) {
      $content = $contentclass->getContent();
   }

   else {
      $content = "Klasse konnte nicht initialisiert werden.";
   }
}

else if ($params->task == 'portalImport' && defined('CONF_MODULE_PORTAL')) {
   require_once SHOP_PATH.'/classes/modules/portal/portalImport.class.php';
   $classname = 'KANPAICLASSIC\KANPAICLASSIC_'.$params->task;
   $contentclass = new $classname();

   if (is_object($contentclass)) {
      $content = $contentclass->getContent();
   }

   else {
      $content = "Klasse konnte nicht initialisiert werden.";
   }
}


else if ($params->task == 'bestellungen') {
   $contentclass = Control::getBestellung();
   $content = $contentclass->getContent();
}

// Klasse gewählter Funktion laden
else if (file_exists("classes/$params->task.class.php")) {
   include "classes/$params->task.class.php";
   $classname = 'KANPAICLASSIC\KANPAICLASSIC_'.$params->task;

   try {
      $contentclass = new $classname();
   }

   catch(Throwable $t) {
//      $classname = "Royalart_" . strtoupper(substr($params->task, 0,1)) . substr($params->task,1);
//      $contentclass = new $classname();
   }

   if (is_object($contentclass)) {
      $content = $contentclass->getContent();
   }

   else {
      $content = "Klasse konnte nicht initialisiert werden.";
   }
}

else {
   include 'templates/default.tpl.php';
}

if (!$params->isAjax) {
   //   echo '<div class="time">Scriptlaufzeit: ' . (microtime(true) - $startzeit) . 'Sekunden, davon DB-Abfragen: ' . $db->time . ' Sekunden / '. $db->count . ' Abfragen</div>';
}

return;
