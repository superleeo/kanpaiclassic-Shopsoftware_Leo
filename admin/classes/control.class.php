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

if (!defined('DS')) {
   define ('DS', DIRECTORY_SEPARATOR);
}

if (!defined('KANPAICLASSIC')) {
   die ("This file cannot run outside the Kanpai Classic Shopsoftware");
}

define ('CR', "\n");
define ('CRLF', "\r\n");

$dir = str_replace([DS.'admin', DS.'classes'], '', dirname(__FILE__));

require_once $dir.'/admin/config.inc.php';
require_once $dir.'/classes/modules/modulesconfig.inc.php';
require_once $dir.'/classes/base/helper.class.php';
require_once $dir.'/classes/base/database.class.php';


class Control {
   static public $dir;


   static public function init() {
      self::$dir = str_replace([DS.'admin', DS.'classes'], '', dirname(__FILE__));
      self::getDB();
      Helper::init();
      $params = self::getParams();
      include_once TEMPLATE_PATH . '/template_conf.inc.php';
      $params->init2();
      // Geht noch nicht in params_base
      $params->waehrung     = Helper::waehrungText($params->firma['waehrung1'], 1);
      $params->waehrung_iso = Helper::waehrungText($params->firma['waehrung1'], 2);
      self::getExternDB();
   }

   static public function initCron() {
      self::$dir = str_replace(['/admin', '/classes'], '', dirname(__FILE__));      self::getDB();
   }

   static public function &getDB() {
      static $instance = null;

      if (!is_object($instance)) {
         $instance = new KANPAICLASSIC_database();
      }

      return $instance;
   }

   static public function &getExternDB() {
      static $instance = null;

      if (!is_object($instance)) {
         // Multishop aktiv
         if (defined('CONF_MODULE_MULTISHOP') && Helper::getData('multishop') == 'y') {
            $instance = new KANPAICLASSIC_database(Helper::getData('multishop_server'), Helper::getData('multishop_user'), Helper::getData('multishop_pass'), Helper::getData('multishop_db'), Helper::getData('multishop_port'));
         }

         // Normaler Shop
         else {
            $instance = self::getDB();
         }
      }

      return $instance;
   }

   static public function &getParams() {
      static $instance = null;

      if (!is_object($instance)) {
         require_once self::$dir.'/classes/base/params_base.class.php';
         require_once self::$dir.'/classes/base/session.class.php';
         require_once self::$dir.'/admin/classes/params.class.php';

         $instance = new KANPAICLASSIC_params();
         $instance->get_functions();
      }

      return $instance;
   }

   static public function &getText() {
      static $instance = null;

      if (!is_object($instance)) {
         require_once self::$dir.'/classes/base/language_base.class.php';
         require_once self::$dir.'/admin/classes/language.class.php';
         $instance = new KANPAICLASSIC_language();
      }

      return $instance;
   }

   static public function &getArtikel() {
      static $instance = null;

      if (!is_object($instance)) {
         require_once self::$dir.'/admin/classes/artikel.class.php';
         $instance = new KANPAICLASSIC_artikel();
      }

      return $instance;
   }

   // Artikel FE
   static public function &getArticles() {

      static $instance = null;

      if (!is_object($instance)) {
         require_once self::$dir.'/classes/articles.class.php';
         $instance = new KANPAICLASSIC_articles();
      }

      return $instance;
   }

   static public function &getKategorie() {
      static $instance = null;

      if (!is_object($instance)) {
         require_once self::$dir.'/admin/classes/kategorien.class.php';
         $instance = new KANPAICLASSIC_kategorien();
      }

      return $instance;
   }

   // Kategorein FE
   static public function &getCategories() {
      static $instance = null;

      if (!is_object($instance)) {
         require_once self::$dir.'/classes/categories.class.php';
         $instance = new KANPAICLASSIC_categories();
      }

      return $instance;
   }

   static public function &getSeiten() {
      static $instance = null;

      if (!is_object($instance)) {
         require_once self::$dir.'/admin/classes/seiten.class.php';
         $instance = new KANPAICLASSIC_seiten();
      }

      return $instance;
   }

   static public function &getMenu() {
      static $instance = null;

      if (!is_object($instance)) {
         require_once self::$dir.'/admin/classes/menu.class.php';
         $instance = new KANPAICLASSIC_menu();
      }

      return $instance;
   }

   static public function &getMail() {
      static $instance = null;

      if (!is_object($instance)) {
         require_once self::$dir.'/classes/base/mail.class.php';
         $instance = new KANPAICLASSIC_mail();
      }

      return $instance;
   }

   static public function &getPhpMailer() {
      static $instance = null;

      if (!is_object($instance)) {
//         require_once self::$dir.'/classes/phpmailer/class.phpmailer.php';
//         $instance = new PHPMailer();
// Doku: https://github.com/PHPMailer/PHPMailer
         require_once SHOP_PATH.'/classes/PHPMailer/src/PHPMailer.php';
         require_once SHOP_PATH.'/classes/PHPMailer/src/SMTP.php';
         require_once SHOP_PATH.'/classes/PHPMailer/src/Exception.php';
         $instance = new \PHPMailer\PHPMailer\PHPMailer();
      }

      return $instance;
   }

   static public function &getLaender() {
      static $instance = null;

      if (!is_object($instance)) {
         require_once self::$dir.'/classes/base/laender.class.php';
         $instance = new KANPAICLASSIC_laender();
      }

      return $instance;
   }

   static public function &getHelp() {
      static $instance = null;

      if (!is_object($instance)) {
         require_once self::$dir.'/classes/base/help.class.php';
         $instance = new KANPAICLASSIC_help();
      }

      return $instance;
   }

   static public function &getBestellung() {
      static $instance = null;

      if (!is_object($instance)) {
         require_once self::$dir.'/classes/base/bestellungen_base.class.php';
         require_once self::$dir.'/admin/classes/bestellungen.class.php';

         if (!defined('CONF_MODULE_BESTELLZUSAMMENFASSUNG')) {
            $instance = new KANPAICLASSIC_bestellungen();
         }

         // Bei Modul Bestellzusammenfassung
         else {
            require_once self::$dir.'/classes/modules/bestellzusammenfassung/bestellzusammenfassung.module.php';
            $instance = new KANPAICLASSIC_modulBestellzusammenfassung();
         }
      }

      return $instance;
   }

   public static function &getBerechnungen() {
      static $instance = null;

      if (!$instance) {
         require_once self::$dir.'/classes/base/berechnungen.class.php';
         $instance = new KANPAICLASSIC_berechnungen();
      }

      return $instance;
   }

   static public function &getPdf() {
      static $instance = null;

      if (!is_object($instance)) {
         require_once self::$dir.'/classes/pdf/tcpdf.php';
         require_once self::$dir.'/classes/base/pdf.class.php';
         $instance = new KANPAICLASSIC_pdf();
      }

      return $instance;
   }

   static public function &getPdfAdmin() {
      static $instance = null;

      if (!is_object($instance)) {
//         require_once self::$dir.'/classes/pdf/tfpdf.php';
         require_once self::$dir.'/classes/pdf/tcpdf.php';
         require_once self::$dir.'/classes/modules/portal/adminpdf.class.php';
         $instance = new KANPAICLASSIC_modulPortalPdfAdmin();
      }

      return $instance;
   }

   static public function &getPdfPaket() {
      static $instance = null;

      if (!is_object($instance)) {
         require_once self::$dir.'/classes/pdf/tcpdf.php';
         require_once self::$dir.'/admin/classes/paket_etiketten_pdf.class.php';
         $instance = new KANPAICLASSIC_pdfPaket();
      }

      return $instance;
   }

   static public function &getPdfWiderruf() {
      static $instance = null;

      if (!is_object($instance)) {
         require_once self::$dir.'/classes/pdf/tcpdf.php';
         require_once self::$dir.'/classes/base/pdfwiderruf.class.php';
         $instance = new KANPAICLASSIC_pdf();
      }

      return $instance;
   }

 

   static public function &getImportExport() {
      static $instance = null;

      if (!is_object($instance)) {
         require_once self::$dir.'/admin/classes/im_export.class.php';
         $instance = new KANPAICLASSIC_importExport();
      }

      return $instance;
   }

   static public function &getDownload() {
      static $instance = null;

      if (!is_object($instance)) {
         require_once self::$dir.'/classes/base/download.class.php';
         $instance = new KANPAICLASSIC_download();
      }

      return $instance;
   }

   static public function &getUser() {
      static $instance = null;

      if (!is_object($instance)) {
         require_once self::$dir.'/classes/user.class.php';
         $instance = new KANPAICLASSIC_user();
      }

      return $instance;
   }

   static public function &getSitemap() {
      static $instance = null;

      if (!is_object($instance)) {
         require_once self::$dir.'/admin/classes/sitemap.class.php';
         $instance = new KANPAICLASSIC_sitemap();
      }

      return $instance;
   }

   static public function &getEbay() {
      static $instance = null;

      if (!is_object($instance)) {
         require_once self::$dir.'/classes/modules/ebay/ebay.module.php';
         $instance = new \KANPAICLASSIC\KANPAICLASSIC_modulEbay();
      }

      return $instance;
   }

   static public function &getEbayOrders() {
      static $instance = null;

      if (!is_object($instance)) {
         require_once self::$dir.'/classes/modules/ebayorders/ebayorders.module.php';
         $instance = new KANPAICLASSIC_modulEbayOrders();
      }

      return $instance;
   }

   public static function &getModulePortal() {
      static $instance = null;

      if (!$instance) {
         require_once self::$dir.'/classes/modules/portal/portal.module.php';
         $instance = new KANPAICLASSIC_modulPortal();
      }

      return $instance;
   }

   public static function &getModuleFoto() {
      static $instance = null;

      if (!$instance) {
         require_once self::$dir.'/classes/modules/foto/foto.module.php';
         $instance = new KANPAICLASSIC_modulFoto();
      }

      return $instance;
   }

   public static function &getModuleRabatte() {
      static $instance = null;

      if (!$instance) {
         require_once self::$dir.'/classes/modules/rabattgruppen/rabatte.module.php';
         $instance = new KANPAICLASSIC_modulRabattgruppen();
      }

      return $instance;
   }

   public static function &getModuleZubehoerSlider() {
      static $instance = null;

      if (!$instance) {
         require_once self::$dir.'/classes/modules/zubehoerslider/zubehoerslider.module.php';
         $instance = new KANPAICLASSIC_modulZubehoerSlider();
      }

      return $instance;
   }

   public static function &getModuleDhlHaendler() {
      static $instance = null;

      if (!$instance) {
         require_once self::$dir.'/classes/modules/dhl_haendler/intraship.module.php';
         $instance = dhlApi();
      }

      return $instance;
   }

   public static function &getModuleBildformat() {
      static $instance = null;

      if (!$instance) {
         require_once self::$dir.'/classes/modules/bildformat/bildformat.module.php';
         $instance = new KANPAICLASSIC_modulBildformat();
      }

      return $instance;
   }

   public static function &getModuleConfigurator() {
      static $instance = null;

      if (!$instance) {
         require_once self::$dir.'/classes/modules/mega_konfigurator/mega_konfigurator.module.php';
         $instance = new KANPAICLASSIC_modulConfigurator();
      }

      return $instance;
   }

   public static function &getModuleStatistik() {
      static $instance = null;

      if (!$instance) {
         require_once self::$dir.'/classes/modules/statistik/statistik.module.php';
         $instance = new KANPAICLASSIC_modulStatistik();
      }

      return $instance;
   }

   public static function &getModuleZubehoer() {
      static $instance = null;

      if (!$instance) {
         require_once self::$dir.'/classes/modules/zubehoermodul/zubehoermodul.module.php';
         $instance = new KANPAICLASSIC_modulZubehoer();
      }

      return $instance;
   }

   public static function &getModuleAehnliche() {
      static $instance = null;

      if (!$instance) {
         require_once self::$dir.'/classes/modules/aehnliche_artikel/aehnliche_artikel.module.php';
         $instance = new KANPAICLASSIC_modulAehnliche();
      }

      return $instance;
   }

   public static function &getPdfCollector() {
      static $instance = null;

      if (!$instance) {
         self::getPdf();
         require_once self::$dir.'/classes/modules/bestellzusammenfassung/pdf_collector.class.php';
         $instance = new KANPAICLASSIC_modulPdfCollector();
      }

      return $instance;
   }

   public static function &getModuleDawanda() {
      static $instance = null;

      if (!$instance) {
         require_once self::$dir.'/classes/modules/dawanda/dawanda.module.php';
         $instance = new KANPAICLASSIC_modulDawanda();
      }

      return $instance;
   }

   public static function &getModuleAmazonorders() {
      static $instance = null;

      if (!$instance) {
         require_once self::$dir.'/classes/modules/amazonorders/amazonorders.module.php';
         $instance = new KANPAICLASSIC_modulAmazonorders();
      }

      return $instance;
   }

   public static function &getModuleAmazon() {
      static $instance = null;

      if (!$instance) {
         require_once self::$dir.'/classes/modules/amazon/amazon.module.php';
         $instance = new KANPAICLASSIC_modulDawanda();
      }

      return $instance;
   }

   public static function &getModuleMusikplayer() {
      static $instance = null;

      if (!$instance) {
         require_once self::$dir.'/classes/modules/musikplayer/musikplayer.module.php';
         $instance = new KANPAICLASSIC_modulMusikplayer();
      }

      return $instance;
   }

   public static function &getModulePdfkatalog() {
      static $instance = null;

      if (!$instance) {
         require_once self::$dir.'/classes/pdf/tcpdf.php';
         require_once self::$dir.'/classes/modules/pdfkatalog/pdfkatalog.module.php';
         $instance = new KANPAICLASSIC_modulPdfkatalog();
      }

      return $instance;
   }

   public static function &getModuleMatrix() {
      static $instance = null;

      if (!$instance) {
         require_once self::$dir.'/classes/modules/preismatrix/preismatrix.module.php';
         $instance = new KANPAICLASSIC_modulMatrix();
      }

      return $instance;
   }

   public static function &getModuleKategoriefilter() {
      static $instance = null;

      if (!$instance) {
         require_once self::$dir.'/classes/modules/filter/filter.module.php';
         $instance = new KANPAICLASSIC_modulFilter();
      }

      return $instance;
   }

   public static function &getModuleMixerKategorie() {
      static $instance = null;

      if (!$instance) {
         require_once self::$dir.'/classes/modules/mixer_kategorie/mixer_kategorie.module.php';
         $instance = new KANPAICLASSIC_modulMixerKategorie();
      }

      return $instance;
   }

   public static function &getModuleMixerArtikel() {
      static $instance = null;

      if (!$instance) {
         require_once self::$dir.'/classes/modules/mixer_artikel/mixer_artikel.module.php';
         $instance = new KANPAICLASSIC_modulMixerArtikel();
//         $instance = new KANPAICLASSIC_modulMixerArtikel();
      }

      return $instance;
   }

   public static function &getNaehrwertePdf() {
      static $instance = null;

      if (!$instance) {
         require_once self::$dir.'/classes/pdf/tcpdf.php';
         require_once self::$dir.'/classes/modules/naehrwerte/pdf.class.php';
         $instance = new KANPAICLASSIC_modulNaehrwertePdf();
      }

      return $instance;
   }

   public static function &getModulePopup() {
      static $instance = null;

      if (!$instance) {
         require_once self::$dir.'/classes/modules/popup/popup.module.php';
         $instance = new KANPAICLASSIC_modulPopup();
      }

      return $instance;
   }

   public static function &getModuleBillbee() {
      static $instance = null;

      if (!$instance) {
         require_once self::$dir.'/classes/modules/billbee/billbee.module.php';
         $instance = new KANPAICLASSIC_modulBillbee();
      }

      return $instance;
   }

   public static function &getModuleKlarna() {
      static $instance = null;

      if (!$instance) {
         require_once self::$dir.'/classes/modules/klarna/klarna.module.php';
         $instance = new KANPAICLASSIC_modulKlarna();
      }

      return $instance;
   }

   public static function &getModule360grad() {
      static $instance = null;

      if (!$instance) {
         require_once self::$dir.'/classes/modules/360grad/360grad.module.php';
         $instance = new KANPAICLASSIC_modul360GRAD();
      }

      return $instance;
   }

   // Für LiveDesigner
   public static function getShopExtended() {
      static $instance = null;

      if (!is_object($instance)) {
         require_once self::$dir.'/classes/modules/extended/shopextended.class.php';
         $instance = new KANPAICLASSIC_shopExtended();
      }

      return $instance;
   }

   // Für LiveDesigner
   public static function getModuleHaendlerbund() {
      static $instance = null;

      if (!is_object($instance)) {
         require_once self::$dir.'/classes/modules/handlerbund/hendlerbund.module.php';
         $instance = new KANPAICLASSIC_modulHaendlerbund();
      }

      return $instance;
   }


   /**
    * Summary of getModulevideo
    * @return null|KANPAICLASSIC_modulVideo
    */
   public static function &getModuleVideo() {
       static $instance = null;

       if (!is_object($instance)) {
           require_once self::$dir.'/classes/modules/video/video.module.php';
           $instance = new KANPAICLASSIC_modulVideo();
       }

       return $instance;
   }

}