<?php
/*
###################################################################################
  Kanpai Classic Shopsoftware
  Entwicklungsstand: 07.03.2019 Version 7.2

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

$sql = "SELECT
           r.id,
           r.bestellnummer,
           r.created as bestelldatum,
           r.zahlungsart,
           r.versand as versandnetto,
           r.msg_kunde as anmerkungenkunde,
           r.user_id as kundennid,
           r.anrede,
           r.firma as firmenname,
           r.nachname as nachname,
           r.vorname as vorname,
           CONCAT(r.adresse, ' ', r.hausnr) as adresse,
           r.adresse as strasse,
           r.hausnr as hausnummer,
           r.plz as postleitzahl,
           r.ort as ort,
           r.staat as land,
           r.email as email,
           r.telefon as telefon,
           r.ustid as ustid,
           r.bank_inhaber as bankinhaber,
           r.bank_iban as bankiban,
           r.bank_bic as bankbic,
           r.bank_name as bankname,
           r.lf_firma as liefer_firmenname,
           r.lf_nachname as liefer_nachname,
           r.lf_vorname as liefer_vorname,
           CONCAT(r.lf_adresse, ' ', r.lf_hausnr) as liefer_adresse,
           r.lf_adresse as liefer_strasse,
           r.lf_hausnr as liefer_hausnummer,
           r.plz as lief_postleitzahl,
           r.lf_ort as liefer_ort,
           r.lf_staat as liefer_land,
           r.lf_staat2,

           r.netto AS rechnung_netto,
           r.steuer1 AS rechnung_steuer1,
           r.steuer2 AS rechnung_steuer2,
           r.steuer3 AS rechnung_steuer3,
           r.steuersatz1 AS rechnung_steuersatz1,
           r.steuersatz2 AS rechnung_steuersatz2,
           r.steuersatz3 AS rechnung_steuersatz3,

           a.art_nr as artikelnummer,
           ra.menge as menge,
           ra.artikel_preis as preisnetto,
           if(ra.steuersatz = 1, r.steuersatz1, if(ra.steuersatz = 2, r.steuersatz2, r.steuersatz3)) as abweichendeMwStProzent
         FROM #__rechnung AS r
         INNER JOIN #__rechnung_artikel AS ra
           ON r.id = ra.rechnung_id
          LEFT OUTER JOIN #__articles AS a
            ON ra.artikel_id = a.id
         WHERE
           r.status = 1
         ORDER BY ra.id
       ";
