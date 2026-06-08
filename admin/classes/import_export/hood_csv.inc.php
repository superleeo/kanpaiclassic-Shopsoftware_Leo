<?php
/*
###################################################################################
  Kanpai Classic Shopsoftware II
  Entwicklungsstand: 20.06.2020 Version 80

  (c) Copyright by Kanpai Classic - Web Development
  Kanpai Classic
  https://www.kanpaiclassic.com
  https://www.kanpaiclassic.com

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

DEFINE ('FORMAT', 7);
DEFINE ('AKTION', 'update');
DEFINE ('ARTIKEL_NR', '');
DEFINE ('SONDERANFERTIGUNG', 0);
DEFINE ('KATEGORIE1', '');
DEFINE ('KATEGORIE2', '');
DEFINE ('SHOP_KATEGORIE1', '');
DEFINE ('SHOP_KATEGORIE2', '');
DEFINE ('SHOP_KATEGORIE3', '');
DEFINE ('ZUSTAND', 'neu');
DEFINE ('LAUFZEIT', '14');
DEFINE ('STARTPREIS', 0);
DEFINE ('ZAHLUNGSARTEN', '1*13');
DEFINE ('DHL_PAKET_AT', '15,9');
DEFINE ('DHL_PAKET_CH', '39');
DEFINE ('DHL_PAKET_EU', '15,9');
DEFINE ('DHL_PAKET_INT', '39');
DEFINE ('DHL_PAKET_NAT', '6,9');
DEFINE ('HERMES_NAT', '');
DEFINE ('VERSANDKOSTENRABATT', '');
DEFINE ('WIEDEREINSTELLEN', 0);
DEFINE ('FETTSCHRIFT', 0);
DEFINE ('WERBEFREI', 0);
DEFINE ('BILD_STARTSEITE', 0);
DEFINE ('STARTSEITE', 0);
DEFINE ('KATEGORIE', 0);
DEFINE ('HINTERGRUNDFARBE', 0);
DEFINE ('GALERIE', 0);
DEFINE ('XXL', 0);
DEFINE ('UNTERTITEL', 0);
DEFINE ('SHOP_HOME', $this->params->shopurl);
DEFINE ('AUSVERKAUFT', 0);
DEFINE ('LAGERND_VON', '');
DEFINE ('LAGERND_BIS', '');
DEFINE ('NICHT_LAGERND_VON', '');
DEFINE ('NICHT_LAGERND_BIS','');
DEFINE ('PRODUKTDATENBLATT','');
DEFINE ('ENERGIELABEL','');

/******* EXPORT ************************************************************************************/
if ($mode == 'export') {
   $trenner = $config->trenner;
   $wt = $config->worttrenner;
   $sql = "SELECT i.id, ac.cat_id, i.steuersatz, i.name_$lang AS name, i.desc_$lang AS beschreibung,
                  i.pict01, i.pict02, i.pict03, i.pict04, i.pict05, i.pict06, i.pict07, i.pict08, i.pict09, i.pict10, i.pict11,
                  i.versand_preis, i.gewicht,
                  a.id AS artikel_id,
                  c.name_deu AS catname, i.widerruf, i.lieferfrist,
                  g.categories AS gcat, g.zustand, i.marke, a.gtin, a.mpn
              FROM #__articles_info AS i
           LEFT JOIN #__articles AS a
              ON i.id = a.parent_id
           LEFT JOIN #__article_to_cats AS ac
              ON ac.parent_id = i.id
           LEFT JOIN #__categories AS c
              ON c.id = ac.cat_id
           LEFT JOIN #__articles_to_googlecats AS g
              ON  g.article_id = i.id
           WHERE a.sort = 1
              AND ac.sort = 0";
   $info = $this->db->queryAllObjects($sql);

   $csv = '';
   
   if ($config->csv_head == 'y') {
      // shop_article
      $head  = 'Format;Aktion;Hood Nr;Artikel Nr;EAN;';
      $head .= 'ISBN;MPN;Hersteller;Sonderanfertigung;Titel;';
      $head .= 'Untertitel;Beschreibung;Kurzbeschreibung;Energieeffizienzklasse;Material;';
      $head .= 'Farbe;Kategorie Nr;Kategorie Nr 2;Shop Kategorie;Shop Kategorie 2;';
      $head .= 'Shop Kategorie 3;Menge;Zustand;Laufzeit;Preis;';
      $head .= 'Einheit Grundpreis;Enthaltene Einheiten;EK;Eigenschaft;Ausfuehrungen;';
      $head .= 'UVP;Gewicht;Einheit;Startpreis;MwSt;';
      $head .= 'Zahlungsarten;DHLPacket_at;DHLPacket_ch;DHLPacket_eu;DHLPacket_int;';
      $head .= 'DHLPacket_nat;hermes_nat;Versandkostenrabatt;Bild URL;wiedereinstellen;';
      $head .= 'Fettschrift;Werbefrei;Bild Startseite;Startseite;Kategorie;';
      $head .= 'Hintergrundfarbe;Galerie;XXL;Untertitel in Listen;Shop Homepage;';
      $head .= 'ausverkauft;lagernd von;lagernd bis;nicht lagernd von;nicht lagernd bis;';
      $head .= 'Artikel URL;Produktdatenblatt;Energielabel'.CRLF;
      $csv .= $head;
   }

   for ($i = 0; $i < count($info); $i++) {
      // Bei Kleingewerbe Steuersatz3 (0%)
      if ($this->params->firma['kleingewerbe'] == 'y') {
         $info[$i]->steuersatz = 3;
      }
   
      $sql = "SELECT a.*,
                     m1.merkmal_$lang AS mm_name1, m2.merkmal_$lang AS mm_name2, w1.wert_$lang AS w_name1, w2.wert_$lang AS w_name2
                 FROM #__articles AS a
              LEFT JOIN #__merkmale AS m1
                 ON m1.id = a.merkmal1
              LEFT JOIN #__merkmale AS m2
                 ON m2.id = a.merkmal2
              LEFT JOIN #__werte AS w1
                 ON w1.id = a.wert1
              LEFT JOIN #__werte AS w2
                 ON w2.id = a.wert2
             WHERE parent_id = ".$info[$i]->id."
                AND sort = 1";
   //          ORDER BY sort";

   //   $this->db->query($sql);
   //   $data = array();

   //   while ($tmp = $this->db->getObject()) {
   //      if ($tmp) {
   //         $data[] = $tmp;
   //      }
   //   }

   //      for ($d = 0; $d< count($data); $d++) {

      $d = $this->db->querySingleObject($sql);
      
      $brutto     = number_format(round((float)$d->netto * (1 + $this->params->firma['tax'.(int)$info[$i]->steuersatz] / 100), 2), 2, '.', '');
      $ang_brutto = number_format(round((float)$d->angebot * (1 + $this->params->firma['tax'.(int)$info[$i]->steuersatz] / 100), 2), 2, '.', '');
      $steuersatz = number_format($this->params->firma['tax'.(int)$info[$i]->steuersatz]);
      
      //$head  = 'Format;Aktion;Hood Nr;Artikel Nr;EAN';
      $csv .= FORMAT.';';
      $csv .= AKTION.';';
      $csv .= ';';
      $csv .= $d->art_nr.';';
      $csv .= $d->gtin.';';

      //$head .= 'ISBN;MPN;Hersteller;Sonderanfertigung;Titel;';
      $csv .= ''.';';
      $csv .= $d->mpn.';';
      $csv .= '"'.$info[$i]->marke.'";';
      $csv .= SONDERANFERTIGUNG.';';
      $csv .= '"'.$info[$i]->name.'";';


      //$head .= 'Untertitel;Beschreibung;Kurzbeschreibung;Energieeffizienzklasse;Material;';
      $csv .= ''.';';
      $csv .= '"'.$info[$i]->beschreibung.'";';
      $csv .= '"'.$info[$i]->beschreibung.'";';
      $csv .= ''.';';
      $csv .= ($d->mm_name2 == 'Material' ? $d->w_name2 : '').';';

      //$head .= 'Farbe;Kategorie Nr;Kategorie Nr 2;Shop Kategorie;Shop Kategorie 2;';
      $csv .= ($d->mm_name1 == 'Farbe' ? $d->w_name1 : '').';';
      $csv .= KATEGORIE1.';';
      $csv .= KATEGORIE2.';';
      $csv .= SHOP_KATEGORIE1.';';
      $csv .= SHOP_KATEGORIE2.';';

      //$head .= 'Shop Kategorie 3;Menge;Zustand;Laufzeit;Preis;';
      $csv .= SHOP_KATEGORIE3.';';
      $csv .= (int)$d->menge.';';
      $csv .= ZUSTAND.';';
      $csv .= LAUFZEIT.';';
      $csv .= $brutto.';';

      $head .= 'Einheit Grundpreis;Enthaltene Einheiten;EK;Eigenschaft;Ausfuehrungen;';
      $csv .= ''.';';
      $csv .= ''.';';
      $csv .= '0'.';';
      $csv .= ''.';';
      $csv .= ''.';';

      $head .= 'UVP;Gewicht;Einheit;Startpreis;MwSt;';
      $csv .= ''.';';
      $csv .= ''.';';
      $csv .= ''.';';
      $csv .= STARTPREIS.';';
      $csv .= $steuersatz.';';

      //$head .= 'Zahlungsarten;DHLPacket_at;DHLPacket_ch;DHLPacket_eu;DHLPacket_int;';
      $csv .= ZAHLUNGSARTEN.';';
      $csv .= DHL_PAKET_AT.';';
      $csv .= DHL_PAKET_CH.';';
      $csv .= DHL_PAKET_EU.';';
      $csv .= DHL_PAKET_INT.';';

      //$head .= 'DHLPacket_nat;hermes_nat;Versandkostenrabatt;Bild URL;wiedereinstellen;';
      $csv .= DHL_PAKET_NAT.';';
      $csv .= HERMES_NAT.';';
      $csv .= VERSANDKOSTENRABATT.';';
      $csv .= $this->params->shopurl.'/galerie/'.$info[$i]->pict01.'.jpg;';
      $csv .= WIEDEREINSTELLEN.';';

      //$head .= 'Fettschrift;Werbefrei;Bild Startseite;Startseite;Kategorie;';
      $csv .= FETTSCHRIFT.';';
      $csv .= WERBEFREI.';';
      $csv .= BILD_STARTSEITE.';';
      $csv .= STARTSEITE.';';
      $csv .= KATEGORIE.';';

      //$head .= 'Hintergrundfarbe;Galerie;XXL;Untertitel in Listen;Shop Homepage;';
      $csv .= HINTERGRUNDFARBE.';';
      $csv .= GALERIE.';';
      $csv .= XXL.';';
      $csv .= UNTERTITEL.';';
      $csv .= SHOP_HOME.';';

      //$head .= 'ausverkauft;lagernd von;lagernd bis;nicht lagernd von;nicht lagernd bis;';
      $csv .= AUSVERKAUFT.';';
      $csv .= LAGERND_VON.';';
      $csv .= LAGERND_BIS.';';
      $csv .= NICHT_LAGERND_VON.';';
      $csv .= NICHT_LAGERND_BIS.';';

      //$head .= 'Artikel URL;Produktdatenblatt;Energielabel'.CRLF;
      $csv .= $this->params->shopurl.'/'.$d->id.';';
      $csv .= PRODUKTDATENBLATT.';';
      $csv .= ENERGIELABEL.';'.CRLF;


         // Hauptartikel
//         if ($d == 0) {
//            $csv .= $variante;
//         }


//      $name = str_replace($wt, $wt.$wt, $info[$i]->name);
//      $beschreibung = '';
//      if ($config->html == 'text') {
//         $beschreibung = $this->_html2txt(($info[$i]->beschreibung));
//      }
//      else {
//         $beschreibung = str_replace(CR, '', nl2br($info[$i]->beschreibung));
//      }

//      $beschreibung = str_replace($wt, $wt.$wt, $beschreibung);

   }
}

/******* IMPORT ************************************************************************************/
if ($mode == 'import') {
}

?>