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

if (!defined('KANPAICLASSIC')) {
   die ("This file cannot run outside the Kanpai Classic Shopsoftware");
}

class KANPAICLASSIC_steuer
{
   public $db;
   public $params;
   public $text;
   private $steuer = [];

   function __construct() {
      $this->db     = Control::getDB();
      $this->params = Control::getParams();
      $this->text   = Control::getText();

//      $this->params->no_extern_db_error = true;
   }


   public function getContent() {
      if ($this->params->func == 'update') {
         $this->writeData();
         // $this->getData();

         $_SESSION['steuer_update'] = true;
         header('Location: '.ADMIN_URL_IDX.'/steuer');
         exit;
      }

      else if ($this->params->func == 'multishopChange') {
         require_once SHOP_PATH.'/classes/modules/multishop/multishop.module.php';
         $multishop = new KANPAICLASSIC_moduleMultishop();
         $multishop->change();
         exit;
      }

      else if ($this->params->func == 'multishopPopup') {
         require_once SHOP_PATH.'/classes/modules/multishop/multishop.module.php';
         $multishop = new KANPAICLASSIC_moduleMultishop();
         $multishop->popup();
         exit;
      }

      else if ($this->params->func == 'multishopSave') {
         require_once SHOP_PATH.'/classes/modules/multishop/multishop.module.php';
         $multishop = new KANPAICLASSIC_moduleMultishop();
         $multishop->save();
         exit;
      }

      else {
         $this->getData();
      }
      include 'templates/steuer.tpl.php';
      return;
   }


   // Daten lesen
   private function getData() {
      $sql = "SELECT  kleingewerbe, tax_active, tax_show, price_login, account_manual,
                      tax1, check_tax1, tax2, check_tax2, tax3, check_tax3, tax_ch_check, tax_eu_check, hide_wk, hide_anm, frage_check, b2b_check, b2b_widerruf FROM #__firma WHERE id = 1";
      $this->db->query($sql);
      $data = $this->db->getObject();

      $this->steuer['kleingewerbe']    = $data->kleingewerbe;
      $this->steuer['tax_active']      = $data->tax_active;
      $this->steuer['tax_show']        = $data->tax_show;
      $this->steuer['price_login']     = $data->price_login;
      $this->steuer['account_manual']  = $data->account_manual;
      $this->steuer['tax1']            = $data->tax1;
      $this->steuer['check_tax1']      = $data->check_tax1;
      $this->steuer['tax2']            = $data->tax2;
      $this->steuer['check_tax2']      = $data->check_tax2;
      $this->steuer['tax3']            = $data->tax3;
      $this->steuer['tax_ch_check']    = $data->tax_ch_check;
      $this->steuer['tax_eu_check']    = $data->tax_eu_check;
      $this->steuer['check_tax3']      = $data->check_tax3;
      $this->steuer['hide_wk']         = $data->hide_wk;
      $this->steuer['hide_anm']        = $data->hide_anm;
      $this->steuer['frage_check']     = $data->frage_check;
      $this->steuer['b2b_check']       = $data->b2b_check;
      $this->steuer['b2b_widerruf']    = $data->b2b_widerruf;

      return;
   }

   // Daten speichern
   private function writeData() {
      $kleingewerbe    = $this->params->postString('kleingewerbe') != '' ? 'y' : 'n';
      $tax_active      = $this->params->postString('tax_active') != '' ? 'y' : 'n';
      $tax_show        = $this->params->postString('tax_show') != '' ? 'y' : 'n';
      $price_login     = $this->params->postString('price_login') != '' ? 'y' : 'n';
      $account_manual  = $this->params->postString('account_manual') != '' ? 'y' : 'n';
      $tax1            = $this->params->postString('tax1');
      $check_tax1      = 'y'; //$this->params->postCheckbox('check_tax1');
      $tax2            = $this->params->postString('tax2');
      $check_tax2      = 'y'; //$this->params->postCheckbox('check_tax2');
      $tax3            = $this->params->postString('tax3');
      $check_tax3      = 'y'; //$this->params->postCheckbox('check_tax3');
      $tax_ch_check    = $this->params->postCheckbox('tax_ch_check');
      $tax_eu_check    = $this->params->postCheckbox('tax_eu_check');
      $hide_wk         = $this->params->postCheckbox('hide_wk');
      $hide_anm        = $this->params->postCheckbox('hide_anm');
      $frage_check     = $this->params->postCheckbox('frage_check');
      $b2b_check       = $this->params->postCheckbox('b2b_check');
      $b2b_widerruf    = $this->params->postCheckbox('b2b_widerruf');

      Helper::setData('frage_check_objekt', $this->params->postCheckbox('frage_check_objekt'));

      $sql = "UPDATE #__firma SET
                              kleingewerbe   = '$kleingewerbe',
                              tax_active     = '$tax_active',
                              tax_show       = '$tax_show',
                              price_login    ='$price_login',
                              account_manual = '$account_manual',
                              tax1           = '$tax1',
                              check_tax1     = '$check_tax1',
                              tax2           = '$tax2',
                              check_tax2     = '$check_tax2',
                              tax3           = '$tax3',
                              check_tax3     = '$check_tax3',
                              tax_ch_check   = '$tax_ch_check',
                              tax_eu_check   = '$tax_eu_check',
                              hide_wk        = '$hide_wk',
                              hide_anm       = '$hide_anm',
                              frage_check    = '$frage_check',
                              b2b_check      = '$b2b_check',
                              b2b_widerruf   = '$b2b_widerruf'
             WHERE id = 1";
      $this->db->query($sql);

      return;
   }
}
