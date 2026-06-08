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

if (!defined('OBADJA')) {
   die ("This file cannot run outside the Kanpai Classic Shopsoftware");
}
// Text für Titelzeile
$template = '';
// Wird nur verwendet, wenn in params nicht unter "case "bezahlung":' aufgeführt (1, 2, 3, 4, 5, 6, 7, 8, 13, 14)

switch ($_SESSION['zahlungsart']) {
   // Not used
   case 1:
      $bez_text = $text->get('bezahlung', 'vorkasse');
      $template = '/bezahlung_ueberweisung.tpl.php';
      break;

   // Not used
   case 2:
      $bez_text = $text->get('zahlart', 'paypal');
      $template = '/bezahlung_paypal.tpl.php';
      break;

   // !!!
   case 3:
      $bez_text = $text->get('zahlart', 'lastschrift');
      $template = '/bezahlung_lastschrift.tpl.php';
      break;

   // Not used
   case 4:
      $bez_text = $text->get('zahlart', 'nachnahme');
      $template = '/bezahlung_nachnahme.tpl.php';
      break;

   // Not used
   case 5:
      $bez_text = $text->get('zahlart', 'rechnung');
      $template = '/bezahlung_rechnung.tpl.php';
      break;

   // Not used
   case 6:
      $bez_text = $text->get('zahlart', 'bar');
      $template = '/bezahlung_bar.tpl.php';
      break;

   // Not used
   case 7:
      $bez_text = $text->get('zahlart', 'sofort');
      $template = '/bezahlung_sofort.tpl.php';
      break;

   // Not used
   case 8:
      $bez_text = $text->get('zahlart', 'vrpay');
      $template = '/bezahlung_vrpay.tpl.php';
      break;

   // !!!
   case 9:
      $bez_text = $text->get('zahlart', 'kk_lastschrift');
      $template = '/bezahlung_kklastschrift.tpl.php';
      break;

   // !!!
   case 10:
      $bez_text = $text->get('zahlart', 'paypalplus');
      $template = '/bezahlung_paypalplus.tpl.php';
      break;

   // !!!
   case 11:
      $bez_text = $text->get('zahlart', 'amazon');
      $template = '/bezahlung_amazon.tpl.php';
      break;

   // !!!
   case 12:
      $bez_text = $text->get('zahlart', 'twint');
      $template = '/bezahlung_twint.tpl.php';
      break;

   //
   case 14:
      $bez_text = $text->get('zahlart', 'klarna');
      $template = '/bezahlung_klarna.tpl.php';
      break;
/*
   case 15:
      $bez_text = $text->get('zahlart', 'paydirekt');
      $template = '/bezahlung_paydirekt.tpl.php';
      break;
*/

   // Postfinance
   case 17:
      $bez_text = $text->get('zahlart', 'postfinance');
      $template = '/bezahlung_postfinance.tpl.php';
      break;

   // !!! Paypal v2
   case 18:
      $bez_text = $text->get('zahlart', 'paypalv2');
      $template = '/bezahlung_paypalv2.tpl.php';
      break;

   // !!! Mollie
   case 19:
      $bez_text = $text->get('zahlart', 'mollie');
      $template = '/bezahlung_mollie.tpl.php';
      break;

}

$gesamt_show =   $_SESSION['wk_summe_netto'] + $_SESSION['wk_steuer1'] + $_SESSION['wk_steuer2'] + $_SESSION['wk_steuer3']
               - ($_SESSION['wk_rabatt'] + $_SESSION['wk_rabatt_ust'])
               + $_SESSION['wk_versand'] + $_SESSION['versand_ust']
               + $_SESSION['zahlart_preis'] + $_SESSION['zahlart_ust']
               - $_SESSION['wk_gutschrift']
               - $_SESSION['gutschein'];
?>
<div class="col_single site_head bg_flaechen">
   <div class="ueberschrift text_max">
      <a class="txt_tit ueberschrift" href="<?php echo SHOP_URL_IDX; ?>/lieferung"><?php echo $text->get('lieferung', 'titel', 'lang'); ?></a> /
      <?php echo $bez_text; ?>
   </div>
</div>

<div id="bezahlart">
   <div class="col_single bg_flaechen">
      <?php  require TEMPLATE_PATH . $template; ?>
   </div>
</div>
