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

class KANPAICLASSIC_berechnungen
{
   private $params  = null;
   private $db      = null;
   private $text    = null;

   public function __construct() {
      $this->params = Control::getParams();
      $this->db = Control::getDB();
      $this->text = Control::getText();
   }


   // Alle Artikel im Warenkorb berechnen inkl. Versandkosten  Steuern und Gewicht.
   // Benötigte Parameter:
   // $artikel[]->steuersatz
   // $artikel[]->artikel_menge
   // $artikel[]->rechner_menge
   // $artikel[]->configurator
   // $artikel[]->rechner_check

   // firma['versandart_'.$tab] == 1 -> ind. Versandk. (addiert)
   // firma['versandart_'.$tab] == 2 -> pauschale Versandk.
   // firma['versandart_'.$tab] == 3 -> gew.abhängige Versandk.
   // firma['versandart_'.$tab] == 4 -> Versandk. pro Stück
   // firma['versandart_'.$tab] == 5 -> ind. Versandk. (höchste)

   // Verzweigung Brutto / Netto-Berechnung, abhängig ob von USt-Anzeige
   public function berechneWkArtikel($artikel, $haendler_id = 0, $runden = true) {
      unset($_SESSION['wk_summe_netto']);
      unset($_SESSION['wk_summe_brutto']);
      unset($_SESSION['wk_steuer1']);
      unset($_SESSION['wk_steuer2']);
      unset($_SESSION['wk_steuer3']);
      unset($_SESSION['wk_versand_preis']);
      unset($_SESSION['wk_spedition_preis']);

      if ($this->params->firma['tax_show'] == 'y') {
         return $this->berechneWkArtikelBrutto($artikel, $haendler_id, $runden);
      }

      else {
         return $this->berechneWkArtikelNetto($artikel, $haendler_id, $runden);
      }

   }

   // USt. deaktiviert
   public function berechneWkArtikelNetto($artikel, $haendler_id = 0, $runden = true) {
      $tab = Helper::versandMode();
      $stellen = 2;

      if (!$runden) {
         $stellen = 9;
      }

      $wk_summe_netto   = 0.00;
      $wk_steuer1       = 0.0;
      $wk_steuer2       = 0.0;
      $wk_steuer3       = 0.0;
      $versand_preis    = 0.0;
      $versand_gewicht  = 0.0;
      $spedition_preis1 = 0.0;
      $spedition_preis2 = 0.0;
      $spedition_preis3 = 0.0;
      $wk_back          = [];
      $tax_active       = false;

      // USt verwenden ?
      if (Helper::checkSteuer($_SESSION['rechnung_land'], $_SESSION['wk_land']) && $this->params->firma['tax_active'] == 'y') {
         $tax_active = true;
      }

      // Alle Artikel durchgehen
      for ($i = 0; $i < (is_array($artikel) ? count($artikel) : 0); $i++) {
         // Für Anzeige Artikel-Details
         $show_preis         = 0.00;
         $preis_netto        = 0.00;
         $preis_brutto       = 0.00;
         $preis_steuer       = 0.00;
         $configurator_preis = 0.00;

         $data               = $artikel[$i];
         $steuer_id          = $data->steuersatz;
         $steuersatz         = 0.0;
         $spedition_check    = false;
         $sonderpreis        = false;

         $statt_preis        = 0.00;
         $statt_netto        = 0.00;
         $statt_brutto       = 0.00;
         $statt_configur     = 0.00;

         if (defined('CONF_MODULE_SPEDITION')) {
            if (isset($artikel[$i]->spedition) && (int)$artikel[$i]->spedition > 0) {
               $spedition_check = true;
            }
         }

         $steuersatz = (float)$this->params->firma['tax'.$steuer_id];

         $preis              = round($data->netto, 2);

         // Preismatrix - Preis nach Fläche berechnen
         if (defined('CONF_MODULE_MATRIX') && isset($data->preismatrix) && $data->preismatrix != '') {
            $matrix      = Control::getModuleMatrix();
            $preismatrix = json_decode($data->preismatrix);
            $preis_m     = $matrix->getPriceWK($data->art_id, $preismatrix->breite, $preismatrix->hoehe);

            if ($preis_m !== null && (float)$preis_m > 0) {
               $preis = $preis_m;
            }
         }

         $statt_netto = $preis;

         // Angebot berücksichtigen
         if ($data->angebot_active == 'y') {
            $preis = round($data->angebot, 2);
            $sonderpreis = true;
         }

         // Staffelpreise berücksichtigen
         if ($this->params->firma['staffelpreise'] == 'y') {
            $preis = Helper::staffelpreis($preis, $data->artikel_menge, $data->staffelung);
         }

         // Aufpreis Mega-Configurator addieren
         if (defined('CONF_MODULE_MEGACONFIGURATOR')) {
            if ($data->configurator_check == 'y' && $data->configurator != '' && $data->configurator != '[]') {
               $configurator             = Control::getModuleConfigurator();
               $configurator_preis       = $configurator->getPriceAdd($data->configurator);
               $statt_configur           = $configurator->getPriceAdd($data->configurator);
            }
         }

         $artikelpreis        = $this->berechnePreis($preis, $steuersatz, false, false);
         $configuratorpreis   = $this->berechnePreis($configurator_preis, $steuersatz, false, false);
         $preis_netto         = $this->addConfigurator($preis, $configurator_preis, $data->rechner_menge, $data->config_menge_check, $data->rechner_check);
         $preis_brutto        = $this->addConfigurator($artikelpreis['brutto'], $configuratorpreis['brutto'], $data->rechner_menge, $data->config_menge_check, $data->rechner_check);
         $preis_steuer        = $preis_brutto - $preis_netto;

         $statt_preis       = $this->berechnePreis($statt_netto, $steuersatz, false, false);
         $stattconfigur     = $this->berechnePreis($statt_configur, $steuersatz, false, false);
         $statt_netto       = $this->addConfigurator($statt_preis['netto'], $stattconfigur['netto'], $data->rechner_menge, $data->config_menge_check, $data->rechner_check);
         $statt_brutto      = $this->addConfigurator($statt_preis['brutto'], $stattconfigur['brutto'], $data->rechner_menge, $data->config_menge_check, $data->rechner_check);

         // Daten für Artikel im WK
         $data->preis         = round($preis_netto, 2);
         $data->preis_netto   = round($preis_netto, 2);
         $data->preis_brutto  = round($preis_netto, 2);
         $data->preis_steuer  = 0;
         $data->steuer        = number_format($steuersatz, 2, ',', '');
         $data->steuer_betrag = 0;
         $data->statt_netto   = $statt_netto;
         $data->statt_brutto  = $statt_brutto;
         $wk_back[]           = $data;

         $wk_summe_netto += round($preis_netto, 2) * (float)$data->artikel_menge;
         ${'wk_steuer'.$steuer_id} += round($preis_netto, 2) * (float)$data->artikel_menge * ((float)$this->params->firma['tax'.$steuer_id] / 100);

         if ($sonderpreis) {
            $_SESSION['sonderpreis_netto'] = (isset($_SESSION['sonderpreis_netto'])  ? $_SESSION['sonderpreis_netto'] : 0)  + round($preis_netto, 2);
         }

      // Versandkosten Artikel berechnen
         // Spedition
         if ($spedition_check) {
            $versk = json_decode($this->params->firma['versandkosten_'.$tab]);
            $preis = $versk->{'spedition_preis'.$data->spedition};

            if ((int)$data->spedition == 1) { $spedition_preis1  = $preis; }
            if ((int)$data->spedition == 2) { $spedition_preis2  = $preis; }
            if ((int)$data->spedition == 3) { $spedition_preis3 += ($preis * (float)$data->artikel_menge); }
         }

         else {
            // Indiv. Versandkosten addieren
            if ((int)$this->params->firma['versandart_'.$tab] == 1) {
               // Menge berücksichtigt
               $versand_preis += (float)$data->versand_preis * $data->artikel_menge;
            }

            // Indiv. Versandkosten (höchste)
            if ((int)$this->params->firma['versandart_'.$tab] == 5) {
               $versand_preis = max((float)$versand_preis, (float)$data->versand_preis);
            }

            // Berechnung Gesamtmenge / Gesamtgewicht
//            $versand_stueck  += (float)$data->artikel_menge;
            $versand_gewicht += (float)$data->gewicht * (float)$data->artikel_menge;
         }
      } // end for

      // Versandkosten nach Gewicht / Nicht bei Spedition (12.04.2019)
      if ((int)$this->params->firma['versandart_'.$tab] == 3 && $versand_gewicht > 0) {
         // Alte Version
         if ((float)$this->params->firma['versand_gewicht_'.$tab] > 0) {
            $versand_preis = $versand_gewicht * (float)$this->params->firma['versand_gewicht_'.$tab];
         }

         // Inland / EU / außerhalb EU
         else {
            $versk = json_decode($this->params->firma['versandkosten_'.$tab]);

            if (!isset($versk->gewichtwert3)) {
               $versk->gewichtkosten5 = $versk->gewichtkosten3;
               $versk->gewichtkosten4 = $versk->gewichtkosten3;
               $versk->gewichtwert3   = $versk->gewichtwert2;
               $versk->gewichtwert4   = $versk->gewichtwert2;
            }

            if ($versand_gewicht <= $versk->gewichtwert1) {
               $versand_preis = $versk->gewichtkosten1;
            }

            else {
               $versand_preis = $versk->gewichtkosten2;

               if ($versand_gewicht > $versk->gewichtwert2) {
                  $versand_preis = $versk->gewichtkosten3;
               }

               if ($versand_gewicht > $versk->gewichtwert3) {
                  $versand_preis = $versk->gewichtkosten4;
               }

               if ($versand_gewicht > $versk->gewichtwert4) {
                  $versand_preis = $versk->gewichtkosten5;
               }
            }
         }
      }

      $this->params->setSession('wk_summe_netto', $wk_summe_netto);
      $this->params->setSession('wk_summe_brutto', $wk_summe_netto);
      $this->params->setSession('wk_steuer1', ($tax_active ? $wk_steuer1 : 0));
      $this->params->setSession('wk_steuer2', ($tax_active ? $wk_steuer2 : 0));
      $this->params->setSession('wk_steuer3', ($tax_active ? $wk_steuer3 : 0));

      $versand_preis = $this->checkSelbstabholung($versk, $versand_preis);

      $this->params->setSession('wk_versand_preis', $versand_preis);
      $this->params->setSession('wk_spedition_preis', $spedition_preis1 + $spedition_preis2 + $spedition_preis3);

      return $wk_back;
   }

   // USt. aktiviert
   public function berechneWkArtikelBrutto($artikel, $haendler_id = 0, $runden = true) {
      $tab = Helper::versandMode();
      $stellen = 2;

      if (!$runden) {
         $stellen = 9;
      }

      $wk_summe_netto   = 0.00;
      $wk_summe_brutto  = 0.00;
      $wk_steuer1       = 0.0;
      $wk_steuer2       = 0.0;
      $wk_steuer3       = 0.0;
      $show_steuer      = 0.0;

      $versand_preis    = 0.0;
      $versand_gewicht  = 0.0;
      $spedition_preis1 = 0.0;
      $spedition_preis2 = 0.0;
      $spedition_preis3 = 0.0;
      $tax_active       = false;
      $wk_back          = [];

      if (Helper::checkSteuer($_SESSION['rechnung_land'], $_SESSION['wk_land']) && $this->params->firma['tax_active'] == 'y') {
         $tax_active = true;
      }

      if (Helper::getData('spedition_check', 'n') == 'y') {
         $spedition_check = true;
      }

      // Alle Artikel durchgehen
      for ($i = 0; $i < count($artikel); $i++) {
         // Für Anzeige Artikel-Details
         $show_preis         = 0.00;
         $preis_netto        = 0.00;
         $preis_brutto       = 0.00;
         $preis_steuer       = 0.00;
         $configurator_preis = 0.00;

         $statt_netto        = 0.00;
         $statt_brutto       = 0.00;
         $statt_preis        = 0.00;
         $statt_configur     = 0.00;

         $data               = $artikel[$i];
         $steuer_id          = $data->steuersatz;
         $steuersatz         = 0.0;
         $spedition_check    = false;
         $sonderpreis        = false;

         if (defined('CONF_MODULE_SPEDITION')) {
            if (isset($data->spedition) && (int)$data->spedition > 0) {
               $spedition_check = true;
            }
         }

         if ($tax_active) {
            $steuersatz = (float)$this->params->firma['tax'.$steuer_id];
         }

         $preis              = $data->netto;

         // Preismatrix - Preis nach Fläche berechnen
         if (defined('CONF_MODULE_MATRIX') && isset($data->preismatrix) && $data->preismatrix != '') {
            $matrix      = Control::getModuleMatrix();
            $preismatrix = json_decode($data->preismatrix);
            $preis_m     = $matrix->getPriceWK($data->art_id, $preismatrix->breite, $preismatrix->hoehe);

            if ($preis_m !== null && (float)$preis_m > 0) {
               $preis = $preis_m;
            }
         }

         $statt_netto = $preis;

         // Angebot berücksichtigen
         if ($data->angebot_active == 'y') {
            $preis       = $data->angebot;
            $sonderpreis = true;
         }

         // Staffelpreise berücksichtigen
         if ($this->params->firma['staffelpreise'] == 'y') {
            $preis       = Helper::staffelpreis($preis, $data->artikel_menge, $data->staffelung);
            $statt_preis = Helper::staffelpreis($statt_preis, $data->artikel_menge, $data->staffelung);
         }

         // Aufpreis Mega-Configurator addieren
         if (defined('CONF_MODULE_MEGACONFIGURATOR')) {
            if ($data->configurator_check == 'y' && $data->configurator != '' && $data->configurator != '[]') {
               $configurator             = Control::getModuleConfigurator();
               $configurator_preis       = $configurator->getPriceAdd($data->configurator);
               $statt_configur           = $configurator->getPriceAdd($data->configurator);
            }
         }

         $artikelpreis      = $this->berechnePreis($preis, $steuersatz, false, false);
         $configuratorpreis = $this->berechnePreis($configurator_preis, $steuersatz, false, false);
         $preis_netto       = $this->addConfigurator($artikelpreis['netto'], $configuratorpreis['netto'], $data->rechner_menge, $data->config_menge_check, $data->rechner_check);
         $preis_brutto      = $this->addConfigurator($artikelpreis['brutto'], $configuratorpreis['brutto'], $data->rechner_menge, $data->config_menge_check, $data->rechner_check);

         $statt_preis       = $this->berechnePreis($statt_netto, $steuersatz, false, false);
         $stattconfigur     = $this->berechnePreis($statt_configur, $steuersatz, false, false);
         $statt_netto       = $this->addConfigurator($statt_preis['netto'], $stattconfigur['netto'], $data->rechner_menge, $data->config_menge_check, $data->rechner_check);
         $statt_brutto      = $this->addConfigurator($statt_preis['brutto'], $stattconfigur['brutto'], $data->rechner_menge, $data->config_menge_check, $data->rechner_check);

         $preis_netto       = round($preis_netto, $stellen);
         $preis_brutto      = round($preis_brutto, $stellen);
         $preis_steuer      = $preis_brutto - $preis_netto;
         $atatt_netto       = round($statt_netto, $stellen);
         $atatt_brutto      = round($statt_brutto, $stellen);

         // Kleingewerbe
         if ($this->params->firma['kleingewerbe'] == 'y') {
            $show_preis       = $preis_netto;
            $wk_summe_netto  += round($preis_netto, 2) * $data->artikel_menge;
            $wk_summe_brutto += round($preis_netto, 2) * $data->artikel_menge;

         }

         // USt mit berechnen
         else {
            $wk_summe_netto  += round($preis_netto, 2) * $data->artikel_menge;
            $wk_summe_brutto += round($preis_brutto,2) * $data->artikel_menge;
            $show_steuer      = $preis_steuer;

            // Ust. aktiv
            if ($tax_active) {
               // USt anzeigen
               if ($this->params->firma['tax_show'] == 'y') {
                  $show_preis  = $preis_brutto;
               }

               // USt nicht anzeigen
               else {
                  $show_preis = $preis_netto;
               }

               // Steuern summieren
               ${'wk_steuer'.$steuer_id} += round($preis_steuer, 2) * $data->artikel_menge;
            }

            else {
               $show_preis = $preis_netto;
            }
         }

         // Daten für Artikel im WK
         $data->preis         = $show_preis;
         $data->preis_netto   = $preis_netto;
         $data->preis_brutto  = $preis_brutto;
         $data->preis_steuer  = $preis_steuer;
         $data->steuer        = number_format($steuersatz, 2, ',', '');
         $data->steuer_betrag = $show_steuer;
         $data->statt_netto   = $statt_netto;
         $data->statt_brutto  = $statt_brutto;
         $wk_back[]           = $data;

         if ($sonderpreis) {
            $_SESSION['sonderpreis_netto'] = (isset($_SESSION['sonderpreis_netto'])  ? $_SESSION['sonderpreis_netto'] : 0)  + round($preis_netto, 2);
            $_SESSION['sonderpreis_brutto'] = (isset($_SESSION['sonderpreis_brutto']) ? $_SESSION['sonderpreis_brutto'] : 0) + round($preis_brutto, 2);
            $_SESSION['sonderpreis_steuer'.$this->params->firma['tax'.$steuer_id]] = (isset($_SESSION['sonderpreis_steuer'.$this->params->firma['tax'.$steuer_id]]) ? $_SESSION['sonderpreis_steuer'.$this->params->firma['tax'.$steuer_id]] : 0) + round($preis_steuer, 2);
         }

      // Versandkosten erfassen
         // Spedition
         if ($spedition_check) {
            // Kein Versandgewicht berücksichtigen
            $versk = json_decode($this->params->firma['versandkosten_'.$tab]);

            // Rundungsfehler beheben
            $preis = round($versk->{'spedition_preis'.$data->spedition} * 1.19) / 1.19;
            if ((int)$data->spedition == 1) { $spedition_preis1  = $preis; }
            if ((int)$data->spedition == 2) { $spedition_preis2  = $preis; }
            if ((int)$data->spedition == 3) { $spedition_preis3 += ($preis * (float)$data->artikel_menge); }
         }

         else {
            // Indiv. Versandkosten addieren
            if ((int)$this->params->firma['versandart_'.$tab] == 1) {
               // Menge berücksichtigt
               $versand_preis += (float)$data->versand_preis * $data->artikel_menge;
            }

            // Indiv. Versandkosten (höchste)
            if ((int)$this->params->firma['versandart_'.$tab] == 5) {
               $versand_preis = max((float)$versand_preis, (float)$data->versand_preis);
            }

            // Berechnung Gesamtmenge / Gesamtgewicht
            $versand_gewicht += (float)$data->gewicht * (float)$data->artikel_menge;
         }
      } // end for

      $versk = null;

      // Versandkosten nach Gewicht / Nicht bei Spedition (12.04.2019)
      if ((int)$this->params->firma['versandart_'.$tab] == 3 && $versand_gewicht > 0) {

         // Inland / EU / außerhalb EU
         $versk = json_decode($this->params->firma['versandkosten_'.$tab]);

         if (!isset($versk->gewichtwert3)) {
            $versk->gewichtkosten5 = $versk->gewichtkosten3;
            $versk->gewichtkosten4 = $versk->gewichtkosten3;
            $versk->gewichtwert3   = $versk->gewichtwert2;
            $versk->gewichtwert4   = $versk->gewichtwert2;
         }

         if ($versand_gewicht <= $versk->gewichtwert1) {
            $versand_preis = $versk->gewichtkosten1;
         }

         else {
            $versand_preis = $versk->gewichtkosten2;

            if ($versand_gewicht > $versk->gewichtwert2) {
               $versand_preis = $versk->gewichtkosten3;
            }

            if ($versand_gewicht > $versk->gewichtwert3) {
               $versand_preis = $versk->gewichtkosten4;
            }

            if ($versand_gewicht > $versk->gewichtwert4) {
               $versand_preis = $versk->gewichtkosten5;
            }
         }
      }

      $versand_preis = $this->checkSelbstabholung($versk, $versand_preis);


      // Summen in Session speichern
      // Summen Artikel WK, Versandkosten und USt in SESSION speichern
      $this->params->setSession('wk_summe_netto', $wk_summe_netto);
      $this->params->setSession('wk_summe_brutto', $wk_summe_brutto);
      $this->params->setSession('wk_steuer1', $wk_steuer1);
      $this->params->setSession('wk_steuer2', $wk_steuer2);
      $this->params->setSession('wk_steuer3', $wk_steuer3);
      $this->params->setSession('wk_versand_preis', $versand_preis);
      $this->params->setSession('wk_spedition_preis', $spedition_preis1 + $spedition_preis2 + $spedition_preis3);

      return $wk_back;

   }

   public function checkSelbstabholung($versk, $old_versand_preis){

       //var_dump($_SESSION);die();
       /*if($_SESSION["wk_land"] == 1){
         // TODO: hier den neuen Selbstabholungs Haken checken
           return $this->params->firma["abholung_preis_1"];
       }*/

       if(isset($_SESSION["abholung_checkbox"]) && $_SESSION["abholung_checkbox"] == 'y'){
           return $this->params->firma["abholung_preis_1"];
       }

       return $old_versand_preis;

   }

   // Endpreis berechnen - Berechnete Artikelpreise aus SESSION verwenden
   // Bei Warenkorb ist haendler_id = 0
   // haendler_id != 0 wird nur bei Bestellung / Rechnung im Portal verwendet
   // $bestellung nicht mehr verwendet
   public function berechneWk($bestellung = false, $haendler_id = 0) {
      $tab        = Helper::versandMode();   // 1 -> Heimatland; 2 -> EU; 3 -> außerhalb EU
      $laender    = Control::getLaender();
      $user       = $_SESSION['user'];
      $tax_active = false;

      // Stauer aktiv? abhängig von Einstellungen und Ländergruppe (Heimatland, EU, Welt)
      if (Helper::checkSteuer($_SESSION['rechnung_land'], $_SESSION['wk_land']) && $this->params->firma['tax_active'] == 'y') {
         $tax_active = true;
      }

      // Sicherstellen, dass Gutscheine in Session sind, verhindert Fehlermeldungen
      if (!isset($_SESSION['gutschein_code']) || $_SESSION['gutschein_code'] == '') {
         $_SESSION['gutschein_code'] = '';
         $_SESSION['gutschein_wert'] = '';
         $_SESSION['gutschein_mode'] = '';
      }

      // Berchnete Werte aus Warenkorb, von berechneWkArtikel() zuvor berechnet
      $wk_steuer1         = $_SESSION['wk_steuer1'];
      $wk_steuer2         = $_SESSION['wk_steuer2'];
      $wk_steuer3         = $_SESSION['wk_steuer3'];
      $wk_summe_netto     = $_SESSION['wk_summe_netto'];
      $wk_summe_brutto    = $_SESSION['wk_summe_brutto'];
      $wk_summe_show      = $_SESSION['wk_summe_brutto'];
      $wk_versand_preis   = $_SESSION['wk_versand_preis'];
      $wk_spedition_preis = $_SESSION['wk_spedition_preis'];

      $rabatt             = 0.00;
      $rabatt_netto       = 0.00;
      $rabatt_steuer      = 0.00;
      $rabatt_ust1        = 0.00;
      $rabatt_ust2        = 0.00;
      $rabatt_ust3        = 0.00;

      $gutschrift_brutto  = 0.00;
      $gutschrift_steuer  = 0.00;
      $gutschrift_ust1    = 0.00;
      $gutschrift_ust2    = 0.00;
      $gutschrift_ust3    = 0.00;

      $gutschein_brutto   = 0.00;
      $gutschein_steuer   = 0.00;
      $gutschein_ust1     = 0.00;
      $gutschein_ust2     = 0.00;
      $gutschein_ust3     = 0.00;
      $gutschein_begrenzt = false;  // true Gutschein > Warenwert

      $versand_netto      = 0.00;
      $versand_steuer     = 0.00;
      $versand_ust1       = 0.00;
      $versand_ust2       = 0.00;
      $versand_ust3       = 0.00;

      $zahlart_netto      = 0.00;
      $zahlart_steuer     = 0.00;
      $zahlart_ust1       = 0.00;
      $zahlart_ust2       = 0.00;
      $zahlart_ust3       = 0.00;

      // Gültige Zahlart oder im WK geändert?
      $zahlart            = $this->_checkZahlart();

// 1. Rabatt berechnen vom Warenwert (und Gutschrift erkennen)
      // Angemeldeter Kunde -> Rabatt berechnen und Gutschrift auslesen
      if (isset($this->params->user_id) && $this->params->user_id > 0) {
         $rabatt = $user['rabatt'];

         // Rabatt darf nicht negativ sein
         if ($rabatt < 0) {
            $rabatt = 0;
         }
         // Rabatt berechnen, auch Steuer berücksichtigen (vom Netto-Warenwert)
         if ($rabatt > 0) {
            $rabatt_netto = round($wk_summe_netto * $rabatt / 100, 9);
            $rabatt_ust1  = round($wk_steuer1 * $rabatt / 100, 9);
            $rabatt_ust2  = round($wk_steuer2 * $rabatt / 100, 9);
            $rabatt_ust3  = round($wk_steuer3 * $rabatt / 100, 9);

            $rabatt_steuer = $rabatt_ust1 + $rabatt_ust2 + $rabatt_ust3;
         }

         // Gutschrift darf nicht negativ sein
         $gutschrift_brutto = (float)$user['gutschrift'];

         // Gutschrift darf nicht negativ sein
         if ($gutschrift_brutto < 0) {
            $gutschrift_brutto = 0;
         }
      }

// 2. Gutschein-Wert berechnen, Rabatt wird berücksichtigt
      // Gutschein aus Warenwert - Dauerrabatt berechnen
      // Summe kann durch Änderung WK Min-Preis unterschreiten
      if ((defined('CONF_MODULE_GUTSCHEINEPRINT') || $this->params->firma['gutschein_aktiv']) && $_SESSION['gutschein_code'] != '' && Helper::checkGutschein($_SESSION['gutschein_code'], $wk_summe_brutto) !== false) {
         $gutschein_brutto = (float)$_SESSION['gutschein_wert'];

         // % in Wert umrechnen
         if ((int)$_SESSION['gutschein_mode'] == 2) {
            // Sonderpreis ausschließen
            if (isset($_SESSION['sonderpreis_netto']) && $this->params->firma['sonderpreis_ausschliessen'] == 'y') {
               $gutschein_brutto = ($wk_summe_brutto - ($rabatt_netto + $rabatt_steuer) - $_SESSION['sonderpreis_brutto']) * $gutschein_brutto / 100;
            }

            else {
               $gutschein_brutto = ($wk_summe_brutto - ($rabatt_netto + $rabatt_steuer)) * $gutschein_brutto / 100;
            }
         }

         // Gutschein Wert maximal Warenwert
         if ($gutschein_brutto > ($wk_summe_brutto - ($rabatt_netto + $rabatt_steuer))) {
            $gutschein_brutto   = $wk_summe_brutto - ($rabatt_netto + $rabatt_steuer);
            $gutschein_begrenzt = true;
         }

         // USt Gutschein berechnen
         if ($tax_active) {
            // Nur Artikel mit reduzierter USt ?
            if ($wk_steuer1 == 0) {
               if ($gutschein_begrenzt) {
                  $gutschein_ust2 = $wk_steuer2;
               }

               else {
                  $gutschein_preis = $this->berechnePreis($gutschein_brutto, $this->params->firma['tax2'], true);
                  $gutschein_ust2  = $gutschein_preis['steuer'];
               }
            }

            else if ($wk_steuer1 == 0 && $wk_steuer2 == 0) {
               if ($gutschein_begrenzt) {
                  $gutschein_ust3 = $wk_steuer3;
               }

               else {
                  $gutschein_preis = $this->berechnePreis($gutschein_brutto, $this->params->firma['tax3'], true);
                  $gutschein_ust3  = $gutschein_preis['steuer'];
               }
            }

            else {
               if ($gutschein_begrenzt) {
                  $gutschein_ust1 = $wk_steuer1;
               }
               else {
                  $gutschein_preis = $this->berechnePreis($gutschein_brutto, $this->params->firma['tax1'], true);
                  $gutschein_ust1  = $gutschein_preis['steuer'];
               }
            }

            $gutschein_steuer = $gutschein_ust1 + $gutschein_ust2 + $gutschein_ust3;
         }
      } // Gutschein

// 3. Preis für Versandberechnung, Rabatt, Gutschein berücksichtigt
      $versand_tmp = $wk_summe_netto - $gutschein_brutto + $gutschein_steuer - $rabatt_netto + $rabatt_steuer;

// 4. Pauschale Versandkosten
      // Versandkostenpauschale (3 Stufen) -> $wk_verssand_preis überschreiben
      if ((int)$this->params->firma['versandart_'.$tab] == 2) {
         // alt - nur einzelner Preis
         if ($this->params->firma['versandkosten_'.$tab] == '' || preg_match('|^(\d)|', $this->params->firma['versandkosten_'.$tab])) {
            $wk_versand_preis = (float)$this->params->firma['versandkosten_'.$tab];
         }

         else {
            $versk = json_decode($this->params->firma['versandkosten_'.$tab]);

            if ($versand_tmp <= $versk->versandwert2) {
               $wk_versand_preis = $versk->versandkosten1;
            }

            else if ($versand_tmp <= $versk->versandwert4) {
               $wk_versand_preis = $versk->versandkosten2;
            }

            else {
               $wk_versand_preis = $versk->versandkosten3;
            }
         }
      }

// 5. Aufschlag Versandland zu Versandpreis addieren
      // Kosten Versandland
      $versand_land_preis = $laender->getPrice($_SESSION['wk_land']);

      // Versandkosten Waren + Versandland
      $wk_versand_preis += $versand_land_preis;

// 6. Versandpreis bei Selbstabholung
      // Versandkosten bei Abholung
      if ($_SESSION['zahlungsart'] == 6 && $this->params->firma['abholung_check_'.$tab] == 'y') {
           $wk_versand_preis = $this->params->firma['abholung_preis_'.$tab];
           $zahlart_netto       = $this->params->firma['abholung_preis_'.$tab];
           $wk_spedition_preis  = 0;
      }

// 7. Versandkostenfrei
      // Versandkostenfrei ab...    nach Abzug Gutschein berechnen
      if ($this->params->firma['tax_show'] == 'y') {
         if ($this->params->firma['check_vers_frei_'.$tab] == 'y'  && $versand_tmp + $wk_steuer1 + $wk_steuer2 + $wk_steuer3 >= (float)$this->params->firma['vers_frei_'.$tab]) {
            $wk_versand_preis = 0;
         }
      }

      else {
         if ($this->params->firma['check_vers_frei_'.$tab] == 'y'  && $versand_tmp >= (float)$this->params->firma['vers_frei_'.$tab]) {
            $wk_versand_preis = 0;
         }
      }

// 7a. Speditionspreis verwenden, wenn > 0
      if ($wk_spedition_preis > 0) {
         $wk_versand_preis += $wk_spedition_preis;
      }

// 8. Versandkosten netto korrigieren, damit Brutto bei allen Steuersätzen gleich ist
      // Versandpreis netto korrigieren, damit gleiches Brutto wie bei Normaler USt berechnet wird
      if ($tax_active && $this->params->firma['kleingewerbe'] != 'y' ) {
         if ($wk_steuer1 == 0 && $wk_steuer2 != 0) {
            $wk_versand_preis = $wk_versand_preis * (1 + (float)$this->params->firma['tax1'] / 100)  / (1 + (float)$this->params->firma['tax2'] /100);
         }

         else if ($wk_steuer1 == 0 && $wk_steuer2 == 0) {
            $wk_versand_preis = $wk_versand_preis * (1 + (float)$this->params->firma['tax1'] / 100)  / (1 + (float)$this->params->firma['tax3'] /100);
         }
      }

      $versand_netto = $wk_versand_preis;

// 9. Kosten Zahlungsart berechnen
      // Kosten Zahlart berechnen (Festpreis oder %)
      $zahlart_netto = $this->zahlartPreis($zahlart, $wk_summe_netto);

// 10. zahlart_netto korrigieren, damit Brutto bei allen Steuersätzen gleich ist

// 11. Steuern zu Versand und Zahlart berechnen
      // USt für Versand- und Zahlungsart
      if ($tax_active && $this->params->firma['kleingewerbe'] != 'y') {
         // Nur Artikel mit reduzierter USt ?
         if ($wk_steuer1 != 0) {
            $versand_preis = $this->berechnePreis($versand_netto, $this->params->firma['tax1'], false, false);
            $versand_ust1  = $versand_preis['steuer'];
            $zahlart_preis = $this->berechnePreis($zahlart_netto, $this->params->firma['tax1'], false, false);
            $zahlart_ust1  = $zahlart_preis['steuer'];
         }

         else if ($wk_steuer2 != 0) {
            $versand_preis = $this->berechnePreis($versand_netto, $this->params->firma['tax2'], false, false);
            $versand_ust2  = $versand_preis['steuer'];
            $zahlart_preis = $this->berechnePreis($zahlart_netto, $this->params->firma['tax2'], false, false);
            $zahlart_ust2  = $zahlart_preis['steuer'];
         }

         else {
            $versand_preis = $this->berechnePreis($versand_netto, $this->params->firma['tax3'], false, false);
            $versand_ust3  = $versand_preis['steuer'];
            $zahlart_preis = $this->berechnePreis($zahlart_netto, $this->params->firma['tax3'], false, false);
            $zahlart_ust3  = $zahlart_preis['steuer'];
         }

         $versand_steuer = $versand_ust1 + $versand_ust2 + $versand_ust3;
         $zahlart_steuer = $zahlart_ust1 + $zahlart_ust2 + $zahlart_ust3;
      }

// 12. Print-Gutscheine
      // Bei Print-Gutscheinen Versankosten usw. berücksichtigen
      if (isset($_SESSION['gutschein_print']) && $_SESSION['gutschein_print']) {
         $gs_netto = $wk_summe_netto
                      - $rabatt_netto
                      + $versand_netto
                      + $zahlart_netto;

         $gs_steuer1 = $wk_steuer1 + $versand_ust1 + $zahlart_ust1 + $rabatt_steuer;
         $gs_steuer2 = $wk_steuer2 + $versand_ust2 + $zahlart_ust2;
         $gs_steuer3 = $wk_steuer3 + $versand_ust3 + $zahlart_ust3;
         $gs_brutto  = $gs_netto + $gs_steuer1 + $gs_steuer2 +$gs_steuer3;

         if ($_SESSION['gutschein_mode'] == 1) {
            if ($_SESSION['Print_Gutschein'] < $gs_brutto) {
               $gutschein_brutto  = $_SESSION['Print_Gutschein'];
               $gutschein_steuer  = ($gs_steuer1 + $gs_steuer2 + $gs_steuer3) / ($gs_brutto / $_SESSION['Print_Gutschein']) ;
            }

            else {
               $gutschein_brutto  = $gs_brutto;
               $gutschein_steuer  = $gs_steuer1 + $gs_steuer2 + $gs_steuer3;
            }
         }

         // Print Gutschein ist % / $_SESSION['Print_Gutschein'] korrigieren
         if ($_SESSION['gutschein_mode'] == 2) {
            $gs_prozent  = $_SESSION['gutschein_wert'] / 100;
            $gs_netto   *= $gs_prozent;
            $gs_steuer1 *= $gs_prozent;
            $gs_steuer2 *= $gs_prozent;
            $gs_steuer3 *= $gs_prozent;

//            $_SESSION['Print_Gutschein'] = $gs_netto + $gs_steuer1 + $gs_steuer2 + $gs_steuer3;
            $_SESSION['Print_Gutschein'] = $gutschein_brutto;
         }
      }


// 13. Gutschrift vom Endbetrag abziehen
      // Gutschrift (bei User in DB gespeichert) kann größer Summe WK sein
      if ($gutschrift_brutto > 0) {
         // Brutto
         $wk_gesamt = $wk_summe_netto + $wk_steuer1 + $wk_steuer2 + $wk_steuer3
                    + $versand_netto + $versand_steuer
                    + $zahlart_netto + $zahlart_steuer
                    - $gutschein_brutto - $gutschein_steuer;
                    - ($rabatt_netto + $rabatt_steuer);

         // Gutschrift kann höher als Gesamtsumme sein
         if ( $gutschrift_brutto >= ($wk_gesamt)) {
            $gutschrift_brutto = $wk_gesamt;
         }

         // USt aktiv ?
         if ($tax_active) {
            // Nur Artikel mit reduzierter USt ?
            if ($wk_steuer1 != 0) {
               $gutschrift_preis = $this->berechnePreis($gutschrift_brutto, $this->params->firma['tax1'], true, false);
               $gutschrift_ust1 = $gutschrift_preis['steuer'];
            }

            else if ($wk_steuer2 != 0) {
               $gutschrift_preis = $this->berechnePreis($gutschrift_brutto, $this->params->firma['tax2'], true, false);
               $gutschrift_ust2 = $gutschrift_preis['steuer'];
            }

            else {
               $gutschrift_preis = $this->berechnePreis($gutschrift_brutto, $this->params->firma['tax3'], true, false);
               $gutschrift_ust3 = $gutschrift_preis['steuer'];
            }

            $gutschrift_steuer = $gutschrift_ust1 + $gutschrift_ust2 + $gutschrift_ust3;
         }
      }

// 14. Daten für WK aufbereiten
      // Netto
      $wk_gesamt_netto = $wk_summe_netto
                         + $versand_netto
                         + $zahlart_netto
                         - (round($gutschein_brutto - $gutschein_steuer, 2))
                         - $rabatt_netto
                         - (round($gutschrift_brutto - $gutschrift_steuer, 2));

      // Array für Ausgabe Warenkorb (auch bei Portal)
      $wk_arr = array();
      $wk_arr['wk_summe_netto']      = $wk_summe_netto;
      $wk_arr['wk_summe_brutto']     = $wk_summe_brutto;
      $wk_arr['wk_steuer1']          = $wk_steuer1 + $versand_ust1 + $zahlart_ust1 - $gutschein_ust1 - $gutschrift_ust1 - $rabatt_ust1;
      $wk_arr['wk_steuer2']          = $wk_steuer2 + $versand_ust2 + $zahlart_ust2 - $gutschein_ust2 - $gutschrift_ust2 - $rabatt_ust2;
      $wk_arr['wk_steuer3']          = $wk_steuer3 + $versand_ust3 + $zahlart_ust3 - $gutschein_ust3 - $gutschrift_ust3 - $rabatt_ust3;
      $wk_arr['wk_gesamt_netto']     = round($wk_gesamt_netto, 2);

      $wk_arr['versand_netto']       = round($versand_netto, 2);
      $wk_arr['versand_land']        = (int)$_SESSION['wk_land'];
      $wk_arr['versand_ust']         = round($versand_steuer, 2);

      $wk_arr['zahlart']             = $zahlart;
      $wk_arr['zahlart_netto']       = round($zahlart_netto, 2);
      $wk_arr['zahlart_ust']         = round($zahlart_steuer, 2);

      $wk_arr['rabatt']              = round($rabatt_netto + $rabatt_steuer, 2);
      $wk_arr['rabatt_netto']        = round($rabatt_netto, 2);
      $wk_arr['rabatt_steuer']       = round($rabatt_steuer, 2);

      $wk_arr['gutschrift_netto']    = round($gutschrift_brutto - $gutschrift_steuer, 2);
      $wk_arr['gutschrift_ust']      = round($gutschrift_steuer, 2);

      $wk_arr['gutschein_netto']     = round($gutschein_brutto - $gutschein_steuer, 2);
      $wk_arr['gutschein_ust']       = round($gutschein_steuer, 2);

// 15. Daten in SESSION speichern für Rechnung
      $_SESSION['wk_summe_netto']    = $wk_summe_netto;             // Zwischensumme
      $_SESSION['wk_summe_brutto']   = $wk_summe_brutto;            // Zwischensumme
      $_SESSION['wk_steuer1']        = $wk_steuer1;       // Steuer normal
      $_SESSION['wk_steuer2']        = $wk_steuer2;       // Steuer reduziert
      $_SESSION['wk_steuer3']        = $wk_steuer3;       // Steuer 0%, wird nie angezeigt
      $_SESSION['wk_netto']          = $wk_gesamt_netto;            // Gesamtpreis Warenkorb (Artikel + Versand)

      $_SESSION['wk_versand']        = round($versand_netto, 2);    // Versandpreis netto
      $_SESSION['versand_ust']       = round($versand_steuer, 2);   // Steuer aus Versand
      $_SESSION['zahlungsart']       = $zahlart;                    // id zahlart
      $_SESSION['zahlart_preis']     = round($zahlart_netto, 2);
      $_SESSION['zahlart_ust']       = round($zahlart_steuer, 2);

      $_SESSION['wk_rabatt']         = round($rabatt_netto, 2);
      $_SESSION['wk_rabatt_ust']     = round($rabatt_steuer, 2);

      $_SESSION['wk_gutschrift']     = round($gutschrift_brutto - $gutschrift_steuer, 2) + round($gutschrift_steuer, 2);
      $_SESSION['wk_gutschrift_ust'] = round($gutschrift_steuer, 2);
      $_SESSION['gutschein']         = round($gutschein_brutto, 2);
      $_SESSION['gutschein_ust']     = round($gutschein_steuer, 2);

      return $wk_arr;
   }

   // Netto / Brutto / Steuer berechnen
   public function berechnePreis($preis, $steuersatz, $ist_brutto = false, $runden = true) {
      $round = 2;

      if (!$runden) {
         $round = 9;
      }

      $preis_back = array('netto'  => 0.00,
                          'brutto' => 0.00,
                          'steuer' => 0.00);

      // Aus Bruttopreis Netto und Steuer berechnen
      if ($ist_brutto) {
         $brutto = round($preis, $round);
         $netto  = round($preis / (1 + $steuersatz / 100), $round);
         $steuer = $brutto - $netto;

         $preis_back['netto']  = $netto;
         $preis_back['brutto'] = $brutto;
         $preis_back['steuer'] = $steuer;
      }

      // Aus Nettopreis Brutto und Steuer berechnen
      else {
         $netto                = round($preis, $round);
         $brutto               = round($preis * (1 + $steuersatz / 100), $round);
         $steuer               = $brutto - $netto;

         $preis_back['netto']  = $netto;
         $preis_back['brutto'] = $brutto;
         $preis_back['steuer'] = $steuer;
      }

      return $preis_back;
   }

   public function checkGutschein() {
      $code    = $_POST['gutschein'];
      $user_id = $this->params->user_id;
      $email   = $this->params->email;

      // Kein Code eingegeben
      if ($code == '') {
         return array('status' => 'failed', 'msg' => $this->text->get('gutschein', 'failed').'</span>', 'code' => $code);
      }

      // Nicht eingelöste Gutschein in DB löschen (Kompatibilität mit alter Version / einlösen)
      $this->db->query("DELETE FROM #__gutscheine_kunden WHERE eingeloest = 'n'");

      // Code in aktuellen Gutscheinen suchen
      $coupon = $this->db->querySingleObject("SELECT wert, mode, datum FROM #__gutscheine WHERE code = '".$this->db->escape($code)."'");

      // Kunde nicht angemeldet. Möglich, wenn Pring-Gutscheine aktiv
      if ($coupon && $this->params->user_id < 1) {
         return array('status' => 'failed', 'msg' => '<span class="form_err">'.$this->text->get('warenkorb', 'msg_anmelden').'</span>', 'code' => $code);
      }

      // Gutschein bereits eingelöst, wenn nicht dauerhaft?
      if ($coupon && $coupon->datum != '0000-00-00') {
         // Gutschein in #__gutscheine_kunden suchen, ob bereits eingelöst
         $data = $this->db->query("SELECT * FROM #__gutscheine_kunden WHERE user_id = $user_id AND $user_id > 0 AND code = '".$this->db->escape($code)."'");

         if ($data) {
            return array('status' => 'failed', 'msg' => '<span class="form_err">'.$this->text->get('gutschein', 'eingeloest').'</span>', 'code' => $code);
         }
      }

      // abgelaufene Gutscheine deaktivieren
      $this->db->query("UPDATE #__gutscheine_print SET outdated = 'y' WHERE datum < NOW()");
      $gs_print = $this->db->querySingleObject("SELECT wert, mode, datum FROM #__gutscheine_print WHERE code = '".$this->db->escape($code)."' AND deleted = 'n'");

      // Gutschein nicht gefunden
      if ($coupon === null && $gs_print === null) {
         return array('status' => 'failed', 'msg' => '<span class="form_err">'.$this->text->get('gutschein', 'failed').'</span>', 'code' => $code);
      }

      // Modul Gutscheine-Print
      if ($coupon === null && $gs_print !== null) {
         $coupon = $gs_print;

         // Print-Gutschein Wert merken für Restguthaben (reg. Kunden)
//         if ((int)$gs_print->mode == 1) {
            $_SESSION['Print_Gutschein'] = $gs_print->wert;
//         }

         // Markierung für Gutscheine-Print
         $this->params->setSession('gutschein_print', true);
      }

      $ablauf = new \DateTime($coupon->datum);
      $jetzt  = new \DateTime(date('Y-m-d'));

      // Gutschein abgelaufen
      if ($coupon->datum != '0000-00-00' && $ablauf < $jetzt) {
         return array('status' => 'failed', 'msg' => '<span class="form_err">'.$this->text->get('gutschein', 'alt'), 'code' => $code);
      }

      // Gutschein OK -> JS ruft WK_Berechnen auf
      $this->params->setSession('gutschein_code', $code);
      $this->params->setSession('gutschein_wert', (float)$coupon->wert);
      $this->params->setSession('gutschein_mode', (int)$coupon->mode);
      $this->params->setSession('gutschein_datum', $coupon->datum);
      return array('status' => 'ok');
   }

   public function zahlartPreis($zahlart, $wk_netto) {
      $tab = Helper::versandMode();
      $preis_zahlart = '0.00';

      switch ($zahlart) {
         // Vorkasse
         case 1:
            $preis_zahlart = -round((float)$this->params->firma['vorkasse_preis'] * $wk_netto / 100, 2);
            break;

         // Paypal
         case 2:
            $preis_zahlart = -round((float)$this->params->firma['paypal_preis'] * $wk_netto / 100, 2);
            break;

         // Lastschrift
         case 3:
            $preis_zahlart = -round((float)$this->params->firma['lastschrift_preis'] * $wk_netto / 100, 2);
            break;

         // Nachnahme
         case 4:
            $preis_zahlart = $this->params->firma['nachnahme_preis'];
            break;

         // Rechnung
         case 5:
            $preis_zahlart = -round((float)$this->params->firma['rechnung_preis'] * $wk_netto / 100, 2);
            break;

         // Barzahlung bei Abholung
         case 6:
            $preis_zahlart = -round((float)$this->params->firma['bar_preis'] * $wk_netto / 100, 2);
            break;

         // Sofortüberweisung
         case 7:
            $preis_zahlart = -round((float)$this->params->firma['sofort_preis'] * $wk_netto / 100, 2);
            break;

         // Einzugsermächtigung
         case 8:
            $preis_zahlart = -round((float)$this->params->firma['vrpay_preis'] * $wk_netto / 100, 2);
            break;

         // Einzug Kreditkarte
         case 9:
            $preis_zahlart = -round((float)$this->params->firma['kklastschrift_preis'] * $wk_netto / 100, 2);
            break;

         // PaypalPlus
         case 10:
            $preis_zahlart = -round((float)$this->params->firma['paypalplus_preis'] * $wk_netto / 100, 2);
            break;

         // Amazon
         case 11:
            $preis_zahlart = -round((float)$this->params->firma['amazon_preis'] * $wk_netto / 100, 2);
            break;

         // Twint
         case 12:
            $preis_zahlart = -round((float)$this->params->firma['amazon_twint'] * $wk_netto / 100, 2);
            break;

         // EasyCredit
         case 13:
            $preis_zahlart = -round((float)$this->params->firma['easycredit_preis'] * $wk_netto / 100, 2);
            break;

         // Klarna
         case 14:
            $preis_zahlart = -round((float)$this->params->firma['klarna_preis'] * $wk_netto / 100, 2);
            break;

         // PayDirekt
         case 15:
            $preis_zahlart = -round((float)$this->params->firma['paydirekt_preis'] * $wk_netto / 100, 2);
            break;

         // WIR
         case 16:
            break;

         // Postfinance
         case 17:
            $preis_zahlart = -round((float)Helper::getData('postfinance_preis', 0) * $wk_netto / 100, 2);
            break;

         //Paypal v2
         case 18:
            $preis_zahlart = -round((float)$this->params->firma['paypalv2_preis'] * $wk_netto / 100, 2);
            break;

         //Mollie
         case 19:
            $preis_zahlart = -round((float)$this->params->firma['mollie_preis'] * $wk_netto / 100, 2);
            break;

         // andere Zahlarten
         default:
            $preis_zahlart = '0.00';
            break;
      }

      return $preis_zahlart;
   }

   // Wird nur bei vorhandenem Modul Rabatte aufgerufen (/classes/article; /classes/base/article_base
   public function rabatt(&$artikel) {
      $artikelgruppe = (int)$artikel->artikelgruppe;
      $kundengruppe  = 0;

      // Livedesigner?
      if ($this->params->task != 'designLivedesigner') {
         $user          = Control::getUser();
      }

      if ($this->params->user_id > 1) {
         $kundengruppe = (isset($user->user['role']) ? (int)$user->user['role'] : 0);

         if ($kundengruppe > 1000) {
            $kundengruppe = $kundengruppe - 1000;
         }

         $kundengruppe = ($kundengruppe > 0 ? $kundengruppe - 10 : 0);
      }

      $rabatt_obj = $this->db->querySingleObject("SELECT rabatt, sonderpreis_check FROM #__rabatte WHERE haendler_id = 0 AND artikelgruppe = $artikelgruppe AND kundengruppe = $kundengruppe");

      // Rabattmodul
      if (is_object($rabatt_obj)) {
         $rabatt = (float)$rabatt_obj->rabatt;

         if ($rabatt != 0) {
            $check = $rabatt_obj->sonderpreis_check;

            // Sonderpreis berücksichtigen
            if ($check == 'y') {
               // Artikel ist Angebot
               if ($artikel->angebot_active == 'y') {
                  $angebot_org       = (float)$artikel->angebot;
                  $angebot           = (float)$artikel->angebot * (1.0 - $rabatt / 100);
                  $artikel->ge_netto = ($angebot_org > 0 ? (float)$artikel->ge_netto * ($angebot / $angebot_org) : 0);
                  $artikel->angebot  = $angebot;
               }

               // Artikel ist kein Angebot
               else {
                  // Neukunde
                  if ($kundengruppe == 0) {
                     $preis_netto       = (float)$artikel->netto * (1.0 - $rabatt / 100);
                     $artikel->ge_netto = ((float)$artikel->netto > 0 ? (float)$artikel->ge_netto * $preis_netto / (float)$artikel->netto : 0);
                     $artikel->netto    = $preis_netto;
                  }

                  // Bei andern Kunden als Angebot anzeigen
                  else {
                     $artikel->ge_netto       = ((float)$artikel->netto > 0 ? (float)$artikel->ge_netto * ($artikel->angebot / $artikel->netto) : 0);
                     $artikel->angebot        = (float)$artikel->netto * (1.0 - $rabatt / 100);
                     $artikel->angebot_active = 'y';
                  }
               }
            }

            // Sonderpreis nicht berücksichtigen
            else {
               if ($artikel->angebot_active == 'y') {
                  $ge_org            = (float)$artikel->ge_netto * (float)$artikel->angebot / (float)$artikel->netto;
                  $artikel->angebot  = (float)$artikel->netto * (1.0 - $rabatt / 100);
                  $artikel->ge_netto = $ge_org * $artikel->angebot / (float)$artikel->netto;
               }

               else {
                  $artikel->angebot = (float)$artikel->netto * (1.0 - $rabatt / 100);
// Kundengruppe von 1 auf 0 geändert
                  if ($kundengruppe > 0) {
                     $artikel->angebot_active = 'y';
                  }
               }
            }

            if ($artikel->angebot >= $artikel->netto) {
               $artikel->angebot_active = 'n';
            }
         }
      }
   }

   public function addConfigurator($preis, $config_preis, $rechner_menge, $conf_menge_check, $rechner_check) {
      if ($rechner_check == 'y' || $config_preis != 0) {
         if ($rechner_check == 'n') {
            $rechner_menge = 1;
         }

         if ($conf_menge_check == 'y') {
            $preis = ($preis + $config_preis) * $rechner_menge;
         }

         else {
            $preis = $preis * $rechner_menge + $config_preis;
         }
      }

      return $preis;
   }

   // Prüfung auf gültige Zahlart
   private function _checkZahlart() {
      $zahlart = 0;

      // Noch keine Zahlungsart gewählt Shop - Default setzen
      if (!isset($_SESSION['zahlungsart'])) {
         $zahlart = Helper::getZahlartDefault();
         $_SESSION['zahlungsart'] = $zahlart;
         $_SESSION['zahlart_error'] = false;
      }

      // Zahlart im  WK geändert
      if (isset($_POST['zahlart'])) {
         $zahlart = $this->params->postInt('zahlart');

         $_SESSION['zahlungsart'] = $zahlart;

         if ($zahlart > 0) {
            $_SESSION['zahlart_error'] = false;

         }

         else {
            $_SESSION['zahlart_error'] = true;
         }
      }

      // Zahlart aus Session übernehmen
      else {
         $zahlart = $_SESSION['zahlungsart'];
      }

      if ($zahlart > 0) {
         $_SESSION['zahlart_error'] = false;
      }

      return $zahlart;
   }
}
