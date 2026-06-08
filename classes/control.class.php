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

if (!defined('CR')) {
   define('CR', "\n");
}

$ctr_dir = dirname(dirname(__FILE__));

if (!file_exists($ctr_dir.'/admin/config.inc.php')) {
   exit('<h1 style="text-align:center; position:absolute; top:50%; width:100%;">Shop wurde noch nicht installiert</h1>');
}

include_once $ctr_dir.'/admin/config.inc.php';

if (!defined('KANPAICLASSIC')) {
   define('KANPAICLASSIC', true);
}

if (!defined('OBADJA')) {
   define('OBADJA', true);
}

if (!defined('KANPAICLASSIC')) {
   die('<h1>Shop wurde noch nicht installiert</h1>');
}

require_once $ctr_dir.'/classes/base/database.class.php';
require_once $ctr_dir.'/classes/base/helper.class.php';
// Muss nicht unbedingt vorhanden sein
require_once $ctr_dir.'/classes/modules/modulesconfig.inc.php';

// Über diese statische Klasse finden alle Klasseninitialisierunge statt
// Verhindert, dass Klassen mehrmals geladen/initialisiert werden
// Klassen werden nur geladen, wenn sie auch benötigt werden

class Control {
   public static function init() {
      Helper::init();
      $params = self::getParams();
      require_once TEMPLATE_PATH.'/template_conf.inc.php';
      $params->init2();

      // Geht noch nicht in params_base
      if (isset($_SESSION['user_waehrung_id'])) {
         $params->waehrung     = Helper::waehrungText($params->firma['waehrung'.$_SESSION['user_waehrung_id']], 1);
         $params->waehrung_iso = Helper::waehrungText($params->firma['waehrung'.$_SESSION['user_waehrung_id']], 2);
         $params->waehrung_id  = $_SESSION['user_waehrung_id'];
         $params->w_faktor     = (float)$params->firma['kurs'.$_SESSION['user_waehrung_id']];
      }

      else {
         $params->waehrung = Helper::waehrungText($params->firma['waehrung1'], 1);
         $params->waehrung_id = 1;
         $params->w_faktor = 1.00;
      }

     

   }

   public static function &getDB() {
      static $instance = null;

      if (!$instance) {
         global $ctr_dir;
         include_once $ctr_dir.'/classes/base/database.class.php';
         $instance = new KANPAICLASSIC_database();
      }
      return $instance;
   }

   static public function &getExternDB() {
      static $instance = null;

      if (!is_object($instance)) {
         if (defined('CONF_MODULE_MULTISHOP') && Helper::getData('multishop') == 'y') {
            $instance = new KANPAICLASSIC_database(Helper::getData('multishop_server'), Helper::getData('multishop_user'), Helper::getData('multishop_pass'), Helper::getData('multishop_db'), Helper::getData('multishop_port'));
         }

         else {
            $instance = self::getDB();
         }
      }

      return $instance;
   }

   public static function &getParams() {
      static $instance = null;

      if (!$instance) {
         global $ctr_dir;
         require_once $ctr_dir.'/classes/base/params_base.class.php';
         require_once $ctr_dir.'/classes/base/session.class.php';
         require_once $ctr_dir.'/classes/params.class.php';
         $instance = new KANPAICLASSIC_params();
      }

      return $instance;
   }

   public static function &getText() {
      static $instance = null;

      if (!$instance) {
         global $ctr_dir;
         require_once $ctr_dir.'/classes/language.class.php';
         $instance = new KANPAICLASSIC_language();
      }

      return $instance;
   }

   public static function &getCategories() {
      static $instance = null;

      if (!$instance) {
         global $ctr_dir;
         require_once $ctr_dir.'/classes/base/categories_base.class.php';
         require_once $ctr_dir.'/classes/categories.class.php';
         $instance = new KANPAICLASSIC_categories();
      }

      return $instance;
   }

   public static function &getArticles() {
      static $instance = null;

      if (!$instance) {
         global $ctr_dir;
         require_once $ctr_dir.'/classes/base/articles_base.class.php';
         require_once $ctr_dir.'/classes/articles.class.php';
         $instance = new KANPAICLASSIC_articles();
      }

      return $instance;
   }

   public static function &getUser() {
      static $instance = null;

      if (!$instance) {
         global $ctr_dir;
         require_once $ctr_dir.'/classes/user.class.php';
         $instance = new KANPAICLASSIC_user();
      }

      return $instance;
   }

   public static function &getWk() {
      static $instance = null;

      if (!$instance) {
         global $ctr_dir;
         require_once $ctr_dir.'/classes/warenkorb.class.php';
         $instance = new KANPAICLASSIC_warenkorb();
      }

      return $instance;
   }

   public static function &getML() {
      static $instance = null;

      if (!$instance) {
         global $ctr_dir;
         require_once $ctr_dir.'/classes/merkliste.class.php';
         $instance = new KANPAICLASSIC_merkliste();
      }

      return $instance;
   }

   public static function &getBerechnungen() {
      static $instance = null;

      if (!$instance) {
         global $ctr_dir;
         require_once $ctr_dir.'/classes/base/berechnungen.class.php';
         $instance = new KANPAICLASSIC_berechnungen();
      }

      return $instance;
   }

   public static function &getBestellung() {
      static $instance = null;

      if (!$instance) {
         global $ctr_dir;
         require_once $ctr_dir.'/classes/base/bestellungen_base.class.php';
         require_once $ctr_dir.'/classes/bestellung.class.php';
         $instance = new KANPAICLASSIC_bestellung();
      }

      return $instance;
   }

   static public function &getLaender() {
      static $instance = null;

      if (!is_object($instance)) {
         global $ctr_dir;
         require_once $ctr_dir.'/classes/base/laender.class.php';
         $instance = new KANPAICLASSIC_laender();
      }

      return $instance;
   }

   public static function &getMail() {
      static $instance = null;

      if (!is_object($instance)) {
         global $ctr_dir;
         require_once $ctr_dir.'/classes/base/mail.class.php';
         $instance = new KANPAICLASSIC_mail();
      }

      return $instance;
   }

   public static function &getPhpMailer() {
//      global $ctr_dir;
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

   static public function &getHelp() {
      static $instance = null;

      if (!is_object($instance)) {
         global $ctr_dir;
         require_once $ctr_dir.'/classes/base/help.class.php';
         $instance = new KANPAICLASSIC_help();
      }

      return $instance;
   }

   static public function &getKonto() {
      static $instance = null;

      if (!is_object($instance)) {
         global $ctr_dir;
         require_once $ctr_dir.'/classes/konto.class.php';
         $instance = new KANPAICLASSIC_konto();
      }

      return $instance;
   }

   static public function &getPdf() {
      static $instance = null;

      if (!is_object($instance)) {
         global $ctr_dir;
         require_once $ctr_dir.'/classes/pdf/tcpdf.php';
         require_once $ctr_dir.'/classes/base/pdf.class.php';
         $instance = new KANPAICLASSIC_pdf();
      }

      return $instance;
   }

   static public function &getPdfLastschrift() {
      static $instance = null;

      if (!is_object($instance)) {
         global $ctr_dir;
         require_once $ctr_dir.'/classes/pdf/tcpdf.php';
         require_once $ctr_dir.'/classes/pdf_lastschrift.class.php';
         $instance = new KANPAICLASSIC_pdfLastschrift();
      }

      return $instance;
   }

   static public function &getPaypal() {
      static $instance = null;

      if (!is_object($instance)) {
         global $ctr_dir;
         require_once $ctr_dir.'/classes/base/paypal.class.php';
         $instance = new KANPAICLASSIC_paypal();
      }

      return $instance;
   }

   static public function &getPaypalv2() {
      static $instance = null;

      if (!is_object($instance)) {
         global $ctr_dir;
         require_once $ctr_dir.'/classes/modules/paypal/paypal.module.php';
         $instance = new KANPAICLASSIC_modulPaypalv2();
      }

      return $instance;
   }
   static public function &getMollie() {
      static $instance = null;

      if (!is_object($instance)) {
         global $ctr_dir;
         require_once $ctr_dir.'/classes/modules/mollie/mollie.module.php';
         $instance = new KANPAICLASSIC_modulMollie();
      }

      return $instance;
   }
   static public function &getPaypalPlus() {
      static $instance = null;

      if (!is_object($instance)) {
         global $ctr_dir;
         require_once $ctr_dir.'/classes/modules/paypalplus/paypalplus.module.php';
         $instance = new KANPAICLASSIC_modulPaypalPlus();
      }

      return $instance;
   }

   static public function &getSofortUeberweisung() {
      static $instance = null;

      if (!is_object($instance)) {
         global $ctr_dir;
         require_once $ctr_dir.'/classes/base/sofortueberweisung.class.php';
         $instance = new KANPAICLASSIC_sofort();
      }

      return $instance;
   }

   static public function &getVrpay() {
      static $instance = null;

      if (!is_object($instance)) {
         global $ctr_dir;
         require_once $ctr_dir.'/classes/modules/vrpay/vrpay.module.php';
         $instance = new KANPAICLASSIC_vrpay();
      }

      return $instance;
   }

   static public function &getAmazon() {
      static $instance = null;

      if (!is_object($instance)) {
         global $ctr_dir;
         require_once $ctr_dir.'/classes/modules/amazon_payment/amazon.module.php';
         $instance = new KANPAICLASSIC_moduleAmazon();
      }

      return $instance;
   }

   static public function &getImportExport() {
      static $instance = null;

      if (!is_object($instance)) {
         global $ctr_dir;
         require_once $ctr_dir.'/admin/classes/im_export.class.php';
         $instance = new KANPAICLASSIC_importExport();
      }

      return $instance;
   }

   static public function &getDownload() {
      static $instance = null;

      if (!is_object($instance)) {
         global $ctr_dir;
         require_once $ctr_dir.'/classes/base/download.class.php';
         $instance = new KANPAICLASSIC_download();
      }

      return $instance;
   }

   static public function &getSofortLib($api_key) {
      static $instance = null;

      if (!is_object($instance)) {
         global $ctr_dir;
         require_once $ctr_dir.'/classes/SofortLib/sofortLib.php';
         $instance = new \SofortLib_Multipay($api_key);
      }
      return $instance;
   }

   static public function &getSofortLibNotification() {
      static $instance = null;

      if (!is_object($instance)) {
         global $ctr_dir;
         require_once $ctr_dir.'/classes/SofortLib/sofortLib.php';
         $instance = new SofortLib_Notification();
      }

      return $instance;
   }

   static public function &getSofortLibData($api_key) {
      static $instance = null;

      if (!is_object($instance)) {
         global $ctr_dir;
         require_once $ctr_dir.'/classes/SofortLib/sofortLib.php';
         $instance = new SofortLib_TransactionData($api_key);
      }

      return $instance;
   }

   static public function getShopExtended() {
      static $instance = null;

      if (!is_object($instance)) {
         global $ctr_dir;
         require_once $ctr_dir.'/classes/modules/extended/shopextended.class.php';
         $instance = new KANPAICLASSIC_shopExtended();
      }

      return $instance;
   }

   public static function &getWiderruf() {
      static $instance = null;
      if (!$instance) {
         global $ctr_dir;
         require_once $ctr_dir.'/classes/widerruf.class.php';
         $instance = new KANPAICLASSIC_widerruf();
      }

      return $instance;
   }

   public static function &getModuleFoto() {
      static $instance = null;

      if (!$instance) {
         global $ctr_dir;
         require_once $ctr_dir.'/classes/modules/foto/foto.module.php';
         $instance = new KANPAICLASSIC_modulFoto();
      }

      return $instance;
   }

   public static function &getModuleCrosspromo() {
      static $instance = null;

      if (!$instance) {
         global $ctr_dir;
         require_once $ctr_dir.'/classes/modules/zubehoerslider/zubehoerslider.module.php';
         $instance = new KANPAICLASSIC_modulZubehoerSlider();
      }

      return $instance;
   }

   public static function &getModuleConfigurator() {
      static $instance = null;
      if (!$instance) {
         global $ctr_dir;
         require_once $ctr_dir.'/classes/modules/mega_konfigurator/mega_konfigurator.module.php';
         $instance = new KANPAICLASSIC_modulConfigurator();
      }

      return $instance;
   }

   public static function &getModuleZubehoer() {
      static $instance = null;

      if (!$instance) {
         global $ctr_dir;
         require_once $ctr_dir.'/classes/modules/zubehoermodul/zubehoermodul.module.php';
         $instance = new KANPAICLASSIC_modulZubehoer();
      }

      return $instance;
   }

   public static function &getModuleAehnliche() {
      static $instance = null;

      if (!$instance) {
         global $ctr_dir;
         require_once $ctr_dir.'/classes/modules/aehnliche_artikel/aehnliche_artikel.module.php';
         $instance = new KANPAICLASSIC_modulAehnliche();
      }

      return $instance;
   }

   static public function &getPdfCollector() {
      static $instance = null;

      if (!is_object($instance)) {
         global $ctr_dir;
         self::getPdf();
         require_once $ctr_dir.'/classes/modules/bestellzusammenfassung/pdf_collector.class.php';
         $instance = new KANPAICLASSIC_pdfCollector();
      }

      return $instance;
   }

   public static function &getModulePersocheck() {
      static $instance = null;

      if (!$instance) {
         global $ctr_dir;
         require_once $ctr_dir.'/classes/modules/persocheck/persocheck.module.php';
         $instance = new KANPAICLASSIC_modulPersocheck();
      }

      return $instance;
   }

   public static function &getModuleTwint() {
      static $instance = null;

      if (!$instance) {
         global $ctr_dir;
         require_once $ctr_dir.'/classes/modules/twint/twint.module.php';
         $instance = new KANPAICLASSIC_modulTwint();
      }

      return $instance;
   }

   public static function &getModuleMusikplayer() {
      static $instance = null;

      if (!$instance) {
         global $ctr_dir;
         require_once $ctr_dir.'/classes/modules/musikplayer/musikplayer.module.php';
         $instance = new KANPAICLASSIC_modulMusikplayer();
      }

      return $instance;
   }

   public static function &getModuleEasycredit() {
      static $instance = null;

      if (!$instance) {
         global $ctr_dir;
         require_once $ctr_dir.'/classes/modules/easycredit/easycredit.module.php';
         $instance = new KANPAICLASSIC_modulEasycredit();
      }

      return $instance;
   }

   /**
    * Summary of getModuleEnergieeffizienzlabel
    * @return null|KANPAICLASSIC_modulEnergieeffizienzlabel
    */
   public static function &getModuleEnergieeffizienzlabel() {
       static $instance = null;
       if (!$instance) {

           global $ctr_dir;

           require_once $ctr_dir.'/classes/modules/energieeffizienzlabel/energieeffizienzlabel.module.php';
           $instance = new KANPAICLASSIC_modulEnergieeffizienzlabel();

       }

       return $instance;
   }

     /**
    * Summary of getModuleEnergieeffizienzlabel
     * @return null|KANPAICLASSIC_modulBonusprogramm
    */
   public static function &getModuleBonusprogramm() {
       static $instance = null;
       if (!$instance) {

           global $ctr_dir;

           require_once $ctr_dir.'/classes/modules/bonusprogramm/bonusprogramm.module.php';
           $instance = new KANPAICLASSIC_modulBonusprogramm();

       }

       return $instance;
   }


   /**
    * Summary of getModulevideo
     * @return null|KANPAICLASSIC_modulVideo
    */
   public static function &getModuleVideo() {
       static $instance = null;
       if (!$instance) {

           global $ctr_dir;

           require_once $ctr_dir.'/classes/modules/video/video.module.php';
           $instance = new KANPAICLASSIC_modulVideo();

       }

       return $instance;
   }

   public static function &getModuleMatrix() {
      static $instance = null;

      if (!$instance) {
         global $ctr_dir;
         require_once $ctr_dir.'/classes/modules/preismatrix/preismatrix.module.php';
         $instance = new KANPAICLASSIC_modulMatrix();
      }

      return $instance;
   }

   public static function &getModuleFilter() {
      static $instance = null;

      if (!$instance) {
         global $ctr_dir;
         require_once $ctr_dir.'/classes/modules/filter/filter.module.php';
         $instance = new KANPAICLASSIC_modulFilter();
      }

      return $instance;
   }

   public static function &getModuleMixerKategorie() {
      static $instance = null;

      if (!$instance) {
         global $ctr_dir;
         require_once $ctr_dir.'/classes/modules/mixer_kategorie/mixer_kategorie.module.php';
         $instance = new KANPAICLASSIC_modulMixerKategorie();
      }

      return $instance;
   }

   public static function &getModuleMixerArtikel() {
      static $instance = null;

      if (!$instance) {
         global $ctr_dir;
         require_once $ctr_dir.'/classes/modules/mixer_artikel/mixer_artikel.module.php';
         $instance = new KANPAICLASSIC_modulMixerArtikel();
      }

      return $instance;
   }

   public static function &getNaehrwertePdf() {
      static $instance = null;

      if (!$instance) {
         global $ctr_dir;
         require_once $ctr_dir.'/classes/pdf/tcpdf.php';
         require_once $ctr_dir.'/classes/modules/naehrwerte/pdf.class.php';
         $instance = new KANPAICLASSIC_modulNaehrwertePdf();
      }

      return $instance;
   }

   public static function &getModuleShopsiegel() {
      static $instance = null;

      if (!$instance) {
         global $ctr_dir;
         require_once $ctr_dir.'/classes/modules/shopsiegel/shopsiegel.module.php';
         $instance = new KANPAICLASSIC_modulShopsiegel();
      }

      return $instance;
   }

   public static function &getModuleBillbee() {
      static $instance = null;

      if (!$instance) {
         global $ctr_dir;
         require_once $ctr_dir.'/classes/modules/billbee/billbee.module.php';
         $instance = new KANPAICLASSIC_modulBillbee();
      }

      return $instance;
   }

   public static function &getModuleKlarna() {
      static $instance = null;

      if (!$instance) {
         global $ctr_dir;
         require_once $ctr_dir.'/classes/modules/klarna/klarna.module.php';
         $instance = new KANPAICLASSIC_modulKlarna();
      }

      return $instance;
   }

   public static function &getModulePaydirekt() {
      static $instance = null;

      if (!$instance) {
         global $ctr_dir;
         require_once $ctr_dir.'/classes/modules/paydirekt/paydirekt.module.php';
         $instance = new KANPAICLASSIC_modulPaydirekt();
      }

      return $instance;
   }

   public static function &getModule360grad() {
      static $instance = null;

      if (!$instance) {
         global $ctr_dir;
         require_once $ctr_dir.'/classes/modules/360grad/360grad.module.php';
         $instance = new KANPAICLASSIC_modul360grad();
      }

      return $instance;
   }
}