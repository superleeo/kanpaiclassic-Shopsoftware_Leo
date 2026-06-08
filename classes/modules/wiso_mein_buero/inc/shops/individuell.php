<?php
/***************************************************************************\
*
*	Copyright (c) 2013 deltra Business Software GmbH & Co. KG
*	http://www.deltra.de
*
\***************************************************************************/
/***************************************************************************\
*
*	In dieser Datei haben Sie die Möglichkeit ein nicht unterstütztes Shop-
*	system anzupassen.
*
*	Informationen diesbez�glich k�nnen Sie der Schnittstellenbeschreibung
*	der ERP Shopanbindung entnehmen. Diese finden Sie im Handbuch
*	vom ERP im Bereich "Anhang - Schnittstellenbeschreibung Webshop-
*	modul
*
*	F�r individuelle Anpassungen an der ERP Shopanbindung kann unser
*	technischer Support weder Ausk�nfte erteilen noch Hilfestellung leisten.
*
*	Die Firma deltra Software �bernimmt keine Haftung für Probleme die
*	mit einer angepassten Version der Shopanbindung entstehen.
*
*	Mit der Anpassung der ERP Shopanbindung stimmen Sie diesen Bedingungen
*	automatisch zu.
*
\***************************************************************************/
/***************************************************************************\
*
*	Bitte beachten Sie bei der Anpassung, dass generell nur �nderungen an
*	dieser Datei "individuell.php" notwendig sind.
*
*	Tipp:
*
*	Beachten Sie bei individuellen Anpassungen unsere Pflichtfelder.
*	(Sehen Sie hierzu auch die Schnittstellenbeschreibung im Handbuch ein)
*
\***************************************************************************/

$GLOBALS['VERSION_SCF'] = "4.0.00";
error_reporting(E_ALL);
ini_set("error_reporting", 1);

// SQL für Abfrage Bestellungen generieren
function daten_holen(&$query) {
      $GLOBALS['datakind'] = 1;

      $query ="SELECT
         r.id as orderID,
         r.bestellnummer as BestellnummerShop,
         r.created as Bestelldatum,
         r.zahlungsart,
         r.msg_kunde as AnmerkungenBestellung,

         r.netto,
         r.steuer1,
         r.steuer2,
         r.steuer3,
         r.steuersatz1,
         r.steuersatz2,
         r.steuersatz3,
         r.gewerbe,

         r.versand as FrachtkostenNetto,
         r.versand_ust,
         r.zahlart_add as ZuschlagkostenNetto1,
         r.zahlart_ust,
         r.user_rabatt,
         r.rabatt,
         r.gutschrift,
         r.gutschein_brutto,
         r.gutschein_steuer,

         r.user_id as KundennummerWebshop,
         r.firma as Firmenname,
         r.anrede,
         r.nachname as PersonNachname,
         r.vorname as PersonVorname,
         CONCAT(r.adresse, ' ', r.hausnr) as Strasse,
         r.plz as Postleitzahl,
         r.ort as Ort,
         r.staat,
         r.staat2,
         r.email as Email,
         r.telefon as Telefon,
         r.ustid as Umsatzsteueridentnummer,

         r.bank_inhaber,
         r.bank_iban,
         r.bank_bic,
         r.bank_name,

         r.lieferadresse,
         r.lf_firma,
         r.lf_anrede,
         r.lf_nachname,
         r.lf_vorname,
         CONCAT(r.lf_adresse, ' ', r.lf_hausnr) as lf_strasse,
         r.lf_plz,
         r.lf_ort,
         r.lf_staat,
         r.lf_staat2,

         ra.artikel_nummer AS ArtikelnummerShop,
         ra.menge as Menge,
         ra.name_shop AS abweichenderArtikeltext,
         ra.artikel_preis as abweichenderEinzelpreisNetto,
         if(ra.steuersatz = 1, r.steuersatz1, if(ra.steuersatz = 2, r.steuersatz2, r.steuersatz3)) as abweichendeMwStProzent
      FROM #__rechnung AS r
      INNER JOIN #__rechnung_artikel AS ra
         ON r.id = ra.rechnung_id
      WHERE r.status = 1
         AND r.deleted = 'n'
      ORDER BY ra.id
   ";

   return $GLOBALS['datakind'];
}

// Aufgerufen von functions::PositionEintragen und functions::ArtikelEintragen
function row_ueberpruefen($row) {
   $netto       = (float)$row['netto'];
   $steuer1     = (float)$row["steuer1"];
   $steuer2     = (float)$row["steuer2"];
   $steuer3     = (float)$row["steuer3"];

   $steuersatz  = 0;
   $steuersatz1 = (float)$row["steuersatz1"];
   $steuersatz2 = (float)$row["steuersatz2"];
   $steuersatz3 = (float)$row["steuersatz3"];

   // Anpassungen Brutto/Netto
   if ($steuer2 == 0 && $steuer3 == 0) {
      $steuersatz = $steuersatz1;
   }

   else if ($steuer1 == 0 && $steuer3 == 0) {
      $steuersatz = $steuersatz2;
   }

   else {
      $steuersatz = $steuersatz3;
   }

// !!!   $rabatt           = (float)$row['rabatt'];

   $rabatt_prozent = (float)$row['user_rabatt'];
   $rabatt_netto   = round(($netto * $rabatt_prozent / 100), 2);
   $rabatt_ust1    = ($steuer1 * $rabatt_prozent / 100);
   $rabatt_ust2    = ($steuer2 * $rabatt_prozent / 100);
   $rabatt_ust3    = ($steuer3 * $rabatt_prozent / 100);
   $rabatt_ust     = round(($rabatt_ust1 + $rabatt_ust2 + $rabatt_ust3), 2);
   $rabatt_brutto  = $rabatt_netto + $rabatt_ust;

   // Berechnung Gutschrift/Gutschein
   $gutschrift       = 0;
   $gutschrift_netto = 0;
   $gutschrift_ust   = 0;
   // Nur Gutschein
   if ((float)$row['gutschrift'] == 0 && (float)$row['gutschein_brutto'] > 0) {
      $gutschrift = (float)$row['gutschein_brutto'];
   }

   // Gutschrift, enthält auch $data->gutschrift_brutto!!! In Admin/Bestellungen geimeinsames Eingabefeld
   $gutschrift_brutto = round((float)$row['gutschrift'], 2);

   // Nur reduzierte Steuer
   if ($steuer1 == 0 && $steuer2 != 0 && $steuer3 == 0) {
      $gutschrift_ust   = round(($gutschrift_brutto / (1 + $steuersatz2 / 100) * ($steuersatz2 / 100)), 2);
      $gutschrift_netto = $gutschrift_brutto - $gutschrift_ust;
   }

   // Normale Steuer
   else if ($steuer1 != 0) {
      $gutschrift_ust   = round(($data->gutschrift_brutto / (1 + $steuersatz1 / 100) * ($steuersatz1 / 100)), 2);
      $gutschrift_netto = $gutschrift_brutto - $gutschrift_ust;
   }

   // Keine Steuer
   else {
      $gutschrift_ust   = 0.00;
      $gutschrift_netto = $gutschrift_brutto;
   }

   // Gutschein Achtung: In Gutschrift bereits enthalten
   $gutschein_brutto = (float)$row['gutschein_brutto'];
   $gutschein_ust    = (float)$row['gutschein_steuer'];
   $gutschrift       = (float)$row['gutschrift'];


// !!   $gutschein_brutto = (float)$row['gutschein_brutto'];
// !!   $gutschein_steuer = (float)$row['gutschein_steuer'];

//   if (defined('GESAMTBRUTTO') && GESAMTBRUTTO == true) {
      $row['BestellwertBrutto'] = number_format($netto +
                                                $steuer1 + $steuer2 + $steuer3 +
                                                round((float)$row['FrachtkostenNetto'] * (1 + $steuersatz / 100), 2) +
                                                round((float)$row['ZuschlagkostenNetto1'] + (float)$row['zahlart_ust'], 2) -
                                                $rabatt_brutto -
                                                $gutschrift_brutto, 2);
//   }

   global $db;
   $zahlart = (int)$row['zahlungsart'];
   $row['Zahlungsart'] = '';

   switch ($zahlart) {
      case 1:
         $row['Zahlungsart'] = 'Vorkasse / Überweisung';
         break;

      case 2:
         $row['Zahlungsart'] = 'Paypal';
         break;

      case 3:
         $row['Zahlungsart'] = 'SEPA-Lastschrift';
         $row['BankkontoInhaber'] = $row['bank_inhaber'];
         $row['Bankkontonummer'] = '';
         $row['BankkontoBLZ'] = '';
         $row['BankkontoIBAN'] = $row['bank_iban'];
         $row['BankkontoBIC'] = $row['bank_bic'];
         $row['BankkontoBankName'] = $row['bank_name'];
         break;

      case 4:
         $row['Zahlungsart'] = 'Nachnahme';
         break;

      case 5:
         $row['Zahlungsart'] = 'Rechnung';
         break;

      case 6:
         $row['Zahlungsart'] = 'Bar bei Abholung';
         break;

      case 7:
         $row['Zahlungsart'] = 'SOFORT Überweisung';
         break;

      case 8:
         $row['Zahlungsart'] = 'VR-Pay';
         break;

      case 9:
         $row['Zahlungsart'] = 'Kreditkarte';
         break;

      case 10:
         $row['Zahlungsart'] = 'PayPalPlus';
         break;

      case 11:
         $row['Zahlungsart'] = 'Amazon Payment';
         break;

      case 12:
         $row['Zahlungsart'] = 'Twint';
         break;

      case 13:
         $row['Zahlungsart'] = 'EasyCrdit';
         break;

      case 14:
         $row['Zahlungsart'] = 'Klarna';
         break;

      case 15:
         $row['Zahlungsart'] = 'PayDirekt';
         break;
   }

   // Brutto
   if (GESAMTBRUTTO) {
      // $user_rabatt      = (float)$row['user_rabatt'];
      $row['FrachtkostenMwStProzent'] = $steuersatz;
      $row['FrachtkostenBrutto'] = number_format((float)$row['FrachtkostenNetto'] * (1 + $steuersatz / 100), 2);
//      $row['FrachtkostenNetto'] = 'DSC_IGNORE';

      $row['ZuschlagkostenMwStProzent1'] = $steuersatz;
      $row['ZuschlagkostenBrutto1'] = number_format((float)$row['ZuschlagkostenNetto1'] * (1 + $steuersatz / 100), 2);
      $row['ZuschlagkostenNetto1'] = 'DSC_IGNORE';

      $row['ZuschlagkostenMwStProzent2'] = $steuersatz;
      $row['ZuschlagkostenBrutto2'] = -number_format($gutschein_brutto);
      $row['ZuschlagkostenNetto2'] = 'DSC_IGNORE';

      $row['ZuschlagkostenMwStProzent3'] = $steuersatz;
      $row['ZuschlagkostenBrutto3'] = -number_format((rabatt + $gutschrift) * (1 + $steuersatz / 100), 2);
      $row['ZuschlagkostenNetto3'] = 'DSC_IGNORE';

      $row['abweichenderEinzelpreisBrutto'] = number_format((float)$row['abweichenderEinzelpreisNetto'] * (1 + ((float)$row["abweichendeMwStProzent"]) / 100), 2);
      $row['abweichenderEinzelpreisNetto'] = 'DSC_IGNORE';
   }

   // Netto
   else {
      // $user_rabatt      = (float)$row['user_rabatt'];
      $rabatt           = (float)$row['rabatt'];
      $gutschrift       = (float)$row['gutschrift'];
      $gutschein_brutto = (float)$row['gutschein_brutto'];
      $gutschein_steuer = (float)$row['gutschein_steuer'];

      $row['FrachtkostenMwStProzent'] = $steuersatz;
//      $row['FrachtkostenBrutto'] = 'DSC_IGNORE';
      $row['FrachtkostenBrutto'] = number_format((float)$row['FrachtkostenNetto'] * (1 + $steuersatz / 100), 2);

      $row['ZuschlagkostenMwStProzent1']    = $steuersatz;
      $row['ZuschlagkostenBrutto1'] = 'DSC_IGNORE';

      $row['ZuschlagkostenMwStProzent2'] = $steuersatz;
      $row['ZuschlagkostenBrutto2'] = 'DSC_IGNORE';
      $row['ZuschlagkostenNetto2'] = -number_format($gutschein_brutto - $gutschein_steuer);

      $row['ZuschlagkostenMwStProzent3'] = '0.00';
      $row['ZuschlagkostenBrutto3'] = 'DSC_IGNORE';
      $row['ZuschlagkostenNetto3'] = -number_format(rabatt + $gutschrift, 2);

      $row['abweichendeMwStProzent'] = $steuersatz;
      $row['abweichenderEinzelpreisBrutto'] = 'DSC_IGNORE';
   }

   // Korrekturen Adresse
   $row['PersonAnrede'] = 'Herr';
   if ($row['anrede'] == 'frau') {
      $row['PersonAnrede'] = 'Frau';
   }

   if ($row['staat'] == '') {
      $row['staat'] = 160;
   }

   $row['Land'] = $db->querySingleValue("SELECT name FROM #__laender WHERE id = ".$row['staat']);
   if ($row['staat'] == 10) {
      $row['Land'] = $row['staat2'];
   }

   // Lieferadresse und Korrekturen
   if ($row['lieferadresse'] == 'y') {
      $row['abweichendLieferungFirmenname'] = $row['lf_firma'];

      $row['abweichendLieferungPersAnrede'] = 'Herr';
      if ($row['lf_anrede'] == 'frau') {
         $row['abweichendLieferungPersAnrede'] = 'Frau';
      }

      $row['abweichendLieferungPersNachname'] = $row['lf_nachname'];
      $row['abweichendLieferungPersVorname'] = $row['lf_vorname'];
      $row['abweichendLieferungStrasse'] = $row['lf_strasse'];
      $row['abweichendLieferungPostleitzahl'] = $row['lf_plz'];
      $row['abweichendLieferungOrt'] = $row['lf_ort'];

      if ($row['lf_staat'] == '') {
         $row['lf_staat'] = 160;
      }

      $row['abweichendLieferungLand'] = $db->querySingleValue("SELECT name FROM #__laender WHERE id = ".$row['lf_staat']);
      if ($row['lf_staat'] == 10) {
         $row['abweichendLieferungLand'] = $row['lf_staat2'];
      }
   }

   // Korrekturen Artikel
   if ((float)$row['user_rabatt'] > 0) {
       $row['RabattProzent'] = $row['user_rabatt'];
   }

//   if (!$is_netto) {
// Brutto-Preis Artikel
//   $row['abweichenderEinzelpreisBrutto'] = number_format((float)$row['abweichenderEinzelpreisNetto'] * (1 + ((float)$row["abweichendeMwStProzent"]) / 100), 2);
//      $row['abweichendeMwStProzent'] = '0.00';
//   }

   return $row;
}

function artikeldaten_orgamax_zu_shop() {
   global $db;
   $articleFile = file_get_contents('php://input');

   if (defined('TEST')) {
      $articleFile = file_get_contents('artikel.xml');
   }

   if (is_file('../xdebug/wiso_log')) {
      $fh = fopen('../xdebug/log/wiso_artikel_von_omx', 'a');
      fwrite($fh, date('d.m.Y H:i:s')."\n".$articleFile."\n");
      fclose($fh);
   }

   if($articleFile != null && strlen($articleFile) > 0) {
      $articles     = [];
      $articles_arr = simplexml_load_string(($articleFile));

      if (isset($articles_arr->row)) {
         foreach ($articles_arr->row as $row) {
            $articles[] = $row;
         }
      }

      $amount_successfully_created = 0;
      $amount_articles             = (is_array($articles) ? count($articles) : 0);

      if ($amount_articles > 0) {
         foreach($articles as $article) {
            $netto           = 0.0;
            $gewicht         = 0.0;
            $haendler_netto  = 0.0;
            $steuer          = 19.0;
            $steuersatz      = 1;

            $artikelnummer                        = $article->Artikelnummer;
            $artikelnummer_webshop                = (string)$article->ArtikelnummerWebshop;
            $artikelbeschreibung                  = (string)$article->Artikelbeschreibung;
            $mwst_code                            = (int)$article->MwStCode;
            $mwst_value                           = (float)$article->MwStValue;
            $einheit                              = (string)$article->Einheit;
            $artikelkategorie                     = (string)$article->Artikelkategorie;
            $gewicht                              = (float)$article->Gewicht;
            $volumen                              = (float)$article->Volumen;
            $anmerkungen                          = (string)$article->Anmerkungen;
            $artikelpreis_waehrung                = (string)$article->ArtikelpreisWaehrung;
            $artikelpreis_netto                   = str_replace(',', '.', (string)$article->ArtikelpreisNetto);
            $Artikelpreis_brutto                  = str_replace(',', '.', (string)$article->ArtikelpreisBrutto);
            $artikel_preis_bezieht_sich_auf_menge = (string)$article->ArtikelPreisBeziehtSichAufMenge;
            $einkaufspreis                        = str_replace(',', '.', (string)$article->Einkaufspreis);
            $ertikelbild                          = (string)$article->Artikelbild;
            $individuelles_feld1                  = (string)$article->IndividuellesFeld1;
            $individuelles_feld2                  = (string)$article->IndividuellesFeld2;
            $individuelles_feld3                  = (string)$article->IndividuellesFeld3;
            $individuelles_feld4                  = (string)$article->IndividuellesFeld4;
            $individuelles_feld5                  = (string)$article->IndividuellesFeld5;
            $individuelles_feld6                  = (string)$article->IndividuellesFeld6;
            $individuelles_feld7                  = (string)$article->IndividuellesFeld7;
            $individuelles_feld8                  = (string)$article->IndividuellesFeld8;
            $individuelles_feld9                  = (string)$article->IndividuellesFeld9;
            $individuelles_feld10                 = (string)$article->IndividuellesFeld10;
            $individuelles_feld11                 = (string)$article->IndividuellesFeld11;
            $individuelles_feld12                 = (string)$article->IndividuellesFeld12;
            $individuelles_feld13                 = (string)$article->IndividuellesFeld13;
            $individuelles_feld14                 = (string)$article->IndividuellesFeld14;
            $individuelles_feld15                 = (string)$article->IndividuellesFeld15;
            $individuelles_feld16                 = (string)$article->IndividuellesFeld16;
            $individuelles_feld17                 = (string)$article->IndividuellesFeld17;
            $individuelles_feld18                 = (string)$article->IndividuellesFeld18;
            $individuelles_feld19                 = (string)$article->IndividuellesFeld19;
            $individuelles_feld20                 = (string)$article->IndividuellesFeld20;

            $merkmal1 = 0;
            $wert1    = 0;
            $merkmal2 = 0;
            $wert2    = 0;

            if ($individuelles_feld1.$individuelles_feld5 != '') {
               $merkmal1 = (int)$db->querySingleValue("SELECT id FROM #__merkmale WHERE merkmal_deu LIKE '$individuelles_feld1$individuelles_feld5'");
            }

            if ($merkmal1 != 0 && $individuelles_feld2.$individuelles_feld6 != '') {
               $wert1 = (int)$db->querySingleValue("SELECT id FROM #__werte WHERE wert_deu LIKE '$individuelles_feld2$individuelles_feld6'");
            }

            if ($individuelles_feld3.$individuelles_feld7 != '') {
               $merkmal2 = (int)$db->querySingleValue("SELECT id FROM #__merkmale WHERE merkmal_deu LIKE '$individuelles_feld3$individuelles_feld7'");
            }

            if ($merkmal2 != 0 && $individuelles_feld4.$individuelles_feld8 != '') {
               $wert2 = (int)$db->querySingleValue("SELECT id FROM #__werte WHERE wert_deu LIKE '$individuelles_feld4$individuelles_feld8'");
            }

            // Normale USt
            if ($mwst_code == 1) {
               $steuersatz = 1;
               $steuer = (float)$db->querySingleValue("SELECT tax1 FROM #__firma WHERE id = 1");
            }

            // Reduziert USt
            if ($mwst_code == 2) {
               $steuersatz = 2;
               $steuer = (float)$db->querySingleValue("SELECT tax2 FROM #__firma WHERE id = 1");
            }

            // Keine USt
            if ($mwst_code == 3) {
               $steuersatz = 3;
               $steuer = (float)$db->querySingleValue("SELECT tax3 FROM #__firma WHERE id = 1");
            }

            // Preis ist Netto
            if ($article->ArtikelpreisNetto != 'DSC_IGNORE') {
               $netto = $artikelpreis_netto;
            }

            // Preis ist Brutto -> in netto umwandeln
            else {
               $netto = artikelpreis_brutto / (1 + $steuer / 100);
            }

            if($einkaufspreis != 'DSC_IGNORE') {
               $haendler_netto = (float)$einkaufspreis;
            }

            if ($anmerkungen == 'DSC_IGNORE') {
               $anmerkungen = '';
            }

            //Prüfen ob der Artikel vorhanden ist
            $test = $db->querySingleObject("SELECT id FROM #__articles WHERE art_nr = '$artikelnummer_webshop'");

            // Insert
            if (!$test) {
               $catid = _getKategorieIdByName($artikelkategorie);
               $db->query("INSERT INTO #__articles_info SET
                              childs     = 1,
                              name_deu   ='$artikelbeschreibung',
                              desc_deu   = '$anmerkungen',
                              steuersatz = '$steuersatz'");
               $new_id = $db->getNewId();

               $db->query("INSERT INTO #__article_to_cats SET parent_id = $new_id, cat_id = $catid");

               $db->query("INSERT INTO #__articles SET
                              art_nr         = '".$artikelnummer_webshop."',
                              parent_id      = $new_id,
                              sort           = 1,
                              netto          = '$netto',
                              gewicht        = '$gewicht',
                              haendler_netto = '$haendler_netto',
                              merkmal1       = $merkmal1,
                              wert1          = $wert1,
                              merkmal2       = $merkmal2,
                              wert2          = $wert2,
                              online         = 'n'");

               $amount_successfully_created++;
            }

            // Update
            else {
               $test      = $db->querySingleObject("SELECT id, sort, parent_id FROM #__articles WHERE art_nr = '$artikelnummer_webshop'");
               $sort      = (int)$test->sort;
               $parent_id = $test->parent_id;
               $id        = $test->id;

               $db->query("UPDATE #__articles SET
                              netto          = '$netto',
                              haendler_netto = '$haendler_netto',
                              gewicht        = '$gewicht',
                              merkmal1       = $merkmal1,
                              wert1          = $wert1,
                              merkmal2       = $merkmal2,
                              wert2          = $wert2");

               if ($sort == 1) {
                  $db->query("UPDATE #__articles_info SET name_deu = '$artikelbeschreibung', ".($anmerkungen != '' ? " desc_deu = '$anmerkungen'," : "")." gewicht = '$gewicht' WHERE id = $parent_id");
               }

               $amount_successfully_created++;
            }
         }
      }
   }

   WriteXMLResult($amount_articles, $amount_successfully_created);
}

function _getKategorieIdByName($catname) {
   global $db;
   $cat = $db->querySingleValue("SELECT id FROM #__categories WHERE name_deu = '$catname'");

   if ((int)$cat > 0) {
      return $cat;
   }

   $cat = $db->querySingleValue("SELECT MIN(cat_id) FROM #__article_to_cats");

   if ((int)$cat > 0) {
      return $cat;
   }

   return 1;
}

function setze_lagerbestand_im_shop() {
   global $db;
   $articleFile = file_get_contents('php://input');
   $amount_successfully_created = 0;
   $amount_articles = 0;

   if ($articleFile != null && strlen($articleFile) > 0) {
      file_put_contents ('lager.txt', $articleFile);
      $articles = simplexml_load_string(($articleFile));
      $amount_articles = count($articles);

      if ($amount_articles > 0) {
         foreach($articles as $article) {
            $articleNr = $article->ArtikelnummerWebshop;
            $test = $db->querySingleValue("SELECT count(art_nr) FROM #__articles WHERE art_nr = '".$db->escape($articleNr)."'");

            // Nur wenn einmalig vorhanden
            if ((int)$test == 1) {
               $db->query("UPDATE #__articles SET menge = '".str_replace(',','.',$article->LagerBestandAktuell)."' WHERE art_nr = '".$db->escape($articleNr)."'");
               $amount_successfully_created++;
            }
         }
      }

      WriteXMLResult($amount_articles,$amount_successfully_created);
   }
}

function pruefeOffeneBestellungenImShop() {
   global $db;
   $test = $db->querySingleValue("SELECT count(id) FROM #__rechnung WHERE status = 1 AND deleted = 'n'");
   echo($test > 0 ? 1 : 0);
   return;

}

function hole_Artikelliste_fuer_export() {
   global $db;
   /** XML-Dokument erzeugen */
   $dom = new DomDocument('1.0');
   $dom->xmlStandalone = true;
   $dom->encoding="utf-8";

   $domArticles = $dom->appendChild($dom->createElement('ArtikelListeWebshop'));

   $data = $db->queryAllObjects("SELECT i.name_deu, a.art_nr FROM #__articles_info AS i, #__articles AS a where i.id = a.parent_id ORDER BY a.parent_id, a.sort");

   if(is_array($data) && count($data) > 0) {
      foreach ($data as $article) {
         $domArticle = $domArticles->appendChild($dom->createElement('row'));
         Add_NewDocElement($dom, $domArticle, 'ArtikelnummerWebshop', $article->art_nr);
         Add_NewDocElement($dom, $domArticle, 'Artikelbeschreibung', $article->name_deu);
      }
   }
   $dom->formatOutput = true;

   if (is_file('../xdebug/wiso_log')) {
      $fh = fopen('../xdebug/log/wiso_artikelliste', 'a');
      fwrite($fh, date('d.m.Y H:i:s')."\n".$dom->saveXML()."\n");
      fclose($fh);
   }

   //zurück geben
//   echo $dom->saveXML();
   return $dom->saveXML();
}

function setze_Artikelpreise_im_shop() {
   $articleFile = file_get_contents('php://input');
   // TEST   $articleFile = file_get_contents('preis.txt');
   $amount_successfully_created = 0;
   $amount_articles = 0;

   if($articleFile != null && strlen($articleFile) > 0) {
      // file_put_contents ('preis.txt', $articleFile);
      $articles = simplexml_load_string(($articleFile));
      $amount_articles = count($articles);

      if ($amount_articles > 0) {
         global $db;
         foreach($articles as $article) {
            $articleNr = $article->ArtikelnummerWebshop;
            $test = $db->querySingleValue("SELECT count(art_nr) FROM #__articles WHERE art_nr = '".$db->escape($articleNr)."'");

            // Nur wenn einmalig vorhanden
            if ((int)$test == 1) {
               $Preis = 0.0;
               $articleNr = $article->ArtikelnummerWebshop;

               if($article->ArtikelpreisNetto != 'DSC_IGNORE') {
                  $Preis = str_replace(',','.',$article->ArtikelpreisNetto);
               }
               else {
                  $Preis = str_replace(',','.',$article->ArtikelpreisBrutto);
                  $Preis = $Preis / 1.19;
               }

               $db->query("UPDATE #__articles SET netto = '$Preis' WHERE art_nr = '".$db->escape($articleNr)."'");
               $amount_successfully_created++;
            }
         }
      }
   }

   WriteXMLResult($amount_articles, $amount_successfully_created);
}

function artikeldaten_shop_zu_orgamax() {
   $GLOBALS['datakind'] = "1";

   $GLOBALS['query'] = "
   SELECT
      a.art_nr       as ArtikelnummerWebshop,
      i.name_deu     as Artikelbeschreibung,
      i.desc_deu     as Anmerkungen,
      i.steuersatz   as MwStCode,
      i.gewicht      as Gewicht,
      a.netto        as ArtikelpreisNetto,
      i.vpe          as Einheit,
      c.name_deu     as Artikelkategorie,
      i.image        as Artikelbild,
      m.merkmal_deu  as IndividuellesFeld1,
      w.wert_deu     as IndividuellesFeld2,
      mm.merkmal_deu as IndividuellesFeld3,
      ww.wert_deu    as IndividuellesFeld4
   FROM #__articles AS a
   LEFT OUTER JOIN #__articles_info AS i
      ON a.parent_id = i.id
   LEFT JOIN #__article_to_cats AS ac
      ON ac.parent_id = a.parent_id
   LEFT JOIN #__categories as c
      ON ac.cat_id = c.id
   LEFT JOIN #__merkmale as m
      ON a.merkmal1 = m.id
   LEFT JOIN #__werte as w
      ON a.wert1 = w.id
   LEFT JOIN #__merkmale as mm
      ON a.merkmal2 = mm.id
   LEFT JOIN #__werte as ww
      ON a.wert2 = ww.id
   ORDER BY
      a.parent_id, a.sort";
}

function artRow_ueberpruefen($artRow) {
   $artRow['Artikelbild'] = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].'/templates/fullscreen/images/'.$artRow['Artikelbild'].'.jpg';

   return $artRow;

}

function status_aendern($orderId) {
   global $db;
   $db->query("UPDATE #__rechnung SET status = 3 WHERE bestellnummer = '$orderId'");
   //var_dump($db->last_sql);
}

function lizenz() {
   return (time() < strtotime('1999-12-31 23:59') ? true : false);
}

function starten() {
   // Verbindung herstellen
   // DB-Klasse wird
}

function ende() {
   // Verbindung beenden
}
