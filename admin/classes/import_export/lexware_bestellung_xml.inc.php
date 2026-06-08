<?php
/*
###################################################################################
  Kanpai Classic Shopsoftware Entwicklungsstand: 14.01.2021 Version 11

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

$lex  = '<?xml version="1.0" encoding="UTF-8"?'.'>'.CR;
$lex .= '<ORDER_LIST>'.CR;

for ($r = 0; $r < count($data); $r++) {
   $articles = $data[$r]->articles;
   $articles_count = $data[$r]->articles_count;

   if (($data[$r]->lieferadresse == 'n' || $data[$r]->lieferadresse == 'y' && $data[$r]->staat == $data[$r]->lf_staat) && $data[$r]->staat == $shop_staat) {
      $tax_area = 'Merchant';
   }

   else {
      $tax_area = 'eu';
   }

   $lex .= '   <ORDER xmlns="http://www.opentrans.org/XMLSchema/1.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" version="1.0" type="standard">'.CR;
   $lex .= '      <ORDER_HEADER>'.CR;
   $lex .= '         <CONTROL_INFO>'.CR;
   $lex .= '            <GENERATOR_INFO>Shop 2.0</GENERATOR_INFO>'.CR;
   $lex .= '            <GENERATION_DATE>'.date('c').'</GENERATION_DATE>'.CR;
   $lex .= '         </CONTROL_INFO>'.CR;
   $lex .= '         <ORDER_INFO>'.CR;
   $lex .= '            <ORDER_ID>'.$data[$r]->bestellnummer.'</ORDER_ID>'.CR;
   // 28.09.2020: Wenn kein Rechnungsdatum, dann aktuelles Datum
   $lex .= '            <ORDER_DATE>'.date('c',(int)$data[$r]->rdatum > 0 ? $data[$r]->rdatum : time()).'</ORDER_DATE>'.CR;
   $lex .= '            <ORDER_PARTIES>'.CR;

   // Lieferadresse
   $lex .= '               <BUYER_PARTY>'.CR;
   $lex .= '                  <PARTY>'.CR;
   $lex .= '                     <ADDRESS>'.CR;
   $lex .= '                        <NAME>'.$this->_lexToAscii($data[$r]->lf_firma).'</NAME>'.CR;
   $lex .= '                        <NAME2>'.$this->_lexToAscii($data[$r]->lf_nachname).'</NAME2>'.CR;
   $lex .= '                        <NAME3>'.$this->_lexToAscii($data[$r]->lf_vorname).'</NAME3>'.CR;
   $lex .= '                        <STREET>'.$this->_lexToAscii(rtrim($data[$r]->lf_adresse.' '.$data[$r]->lf_hausnr)).'</STREET>'.CR;
   $lex .= '                        <ZIP>'.$data[$r]->lf_plz.'</ZIP>'.CR;
   $lex .= '                        <CITY>'.$this->_lexToAscii($data[$r]->lf_ort).'</CITY>'.CR;
   $lex .= '                        <COUNTRY>'.strtoupper($data[$r]->lf_iso).'</COUNTRY>'.CR;
   $lex .= '                        <PHONE type="other"/>'.CR;
   $lex .= '                        <FAX/>'.CR;
   $lex .= '                        <EMAIL>'.$data[$r]->email.'</EMAIL>'.CR;
   $lex .= '                     </ADDRESS>'.CR;
   $lex .= '                  </PARTY>'.CR;
   $lex .= '               </BUYER_PARTY>'.CR;

   // Rechnungsadresse
   $lex .= '               <INVOICE_PARTY>'.CR;
   $lex .= '                  <PARTY>'.CR;
   $lex .= '                     <ADDRESS>'.CR;
   $lex .= '                        <NAME>'.$this->_lexToAscii($data[$r]->firma).'</NAME>'.CR;
   $lex .= '                        <NAME2>'.$this->_lexToAscii($data[$r]->nachname).'</NAME2>'.CR;
   $lex .= '                        <NAME3>'.$this->_lexToAscii($data[$r]->vorname).'</NAME3>'.CR;
   $lex .= '                        <STREET>'.$this->_lexToAscii($data[$r]->adresse.' '.$data[$r]->hausnr).'</STREET>'.CR;
   $lex .= '                        <ZIP>'.$data[$r]->plz.'</ZIP>'.CR;
   $lex .= '                        <CITY>'.$this->_lexToAscii($data[$r]->ort).'</CITY>'.CR;
   $lex .= '                        <COUNTRY>'.strtoupper($data[$r]->iso).'</COUNTRY>'.CR;
   $lex .= '                        <EMAIL>'.$data[$r]->email.'</EMAIL>'.CR;
   $lex .= '                        <PHONE type="other"/>'.CR;
   $lex .= '                        <FAX/>'.CR;
   $lex .= '                        <VAT_ID>'.$data[$r]->ustid.'</VAT_ID>'.CR;
   $lex .= '                     </ADDRESS>'.CR;
   $lex .= '                  </PARTY>'.CR;
   $lex .= '               </INVOICE_PARTY>'.CR;
   // Rechnungsadresse - wird nicht übernommen
   $lex .= '               <SUPPLIER_PARTY>'.CR;
   $lex .= '                  <PARTY>'.CR;
   $lex .= '                     <ADDRESS>'.CR;
   $lex .= '                        <NAME>'.$this->_lexToAscii($data[$r]->firma).'</NAME>'.CR;
   $lex .= '                        <NAME2>'.$this->_lexToAscii($data[$r]->nachname).'</NAME2>'.CR;
   $lex .= '                        <NAME3>'.$this->_lexToAscii($data[$r]->vorname).'</NAME3>'.CR;
   $lex .= '                        <STREET>'.$this->_lexToAscii($data[$r]->adresse.' '.$data[$r]->hausnr).'</STREET>'.CR;
   $lex .= '                        <ZIP>'.$data[$r]->plz.'</ZIP>'.CR;
   $lex .= '                        <CITY>'.$this->_lexToAscii($data[$r]->ort).'</CITY>'.CR;
   $lex .= '                        <COUNTRY>'.strtoupper($data[$r]->iso).'</COUNTRY>'.CR;
   $lex .= '                        <EMAIL>'.$data[$r]->email.'</EMAIL>'.CR;
   $lex .= '                        <VAT_ID>'.$data[$r]->ustid.'</VAT_ID>'.CR;
   $lex .= '                     </ADDRESS>'.CR;
   $lex .= '                  </PARTY>'.CR;
   $lex .= '               </SUPPLIER_PARTY>'.CR;
   $lex .= '            </ORDER_PARTIES>'.CR;

   $lex .= '            <PRICE_CURRENCY>EUR</PRICE_CURRENCY>'.CR;
   $lex .= '            <PAYMENT>'.CR;

   // Vorkasse
   if ($data[$r]->zahlungsart == 1) {
      $lex .= '               <CASH>'.CR;
      $lex .= '                  <PAYMENT_TERM TYPE="unece">25</PAYMENT_TERM>'.CR;
      $lex .= '               </CASH>'.CR;
   }

   // Nachname
   else if ($data[$r]->zahlungsart == 4) {
      $lex .= '               <CASH>'.CR;
      $lex .= '                  <PAYMENT_TERM TYPE="unece">52</PAYMENT_TERM>'.CR;
      $lex .= '               </CASH>'.CR;
   }

   // Rechnung
   else if ($data[$r]->zahlungsart == 5) {
      $lex .= '               <CASH>'.CR;
      $lex .= '                  <PAYMENT_TERM TYPE="unece">10</PAYMENT_TERM>'.CR;
      $lex .= '               </CASH>'.CR;
   }

   // Barzahlung
   else if ($data[$r]->zahlungsart == 6) {
      $lex .= '               <CASH>'.CR;
      $lex .= '                  <PAYMENT_TERM TYPE="unece">56</PAYMENT_TERM>'.CR;
      $lex .= '               </CASH>'.CR;
   }

   // Bankeinzug
   else if ($data[$r]->zahlungsart == 9) {
      $lex .= '               <ACCOUNT>'.CR;
      $lex .= '                  <HOLDER></HOLDER>'.CR;
      $lex .= '                  <BANK_NAME></BANK_NAME>'.CR;
      $lex .= '                  <BANK_COUNTRY>DE</BANK_COUNTRY>'.CR;
      $lex .= '                  <BANK_CODE></BANK_CODE>'.CR;
      $lex .= '                  <BANK_ACCOUNT></BANK_ACCOUNT>'.CR;
      $lex .= '                  <PAYMENT_TERM TYPE="unece">54</PAYMENT_TERM>'.CR;
      $lex .= '               </ACCOUNT>'.CR;
   }

   else {
      $lex .= '               <CHECK></CHECK>'.CR;
   }

   $lex .= '            </PAYMENT>'.CR;
   $lex .= '            <REMARK type="delivery_method"></REMARK>'.CR;
   $lex .= '            <REMARK type="shipping_fee">'.number_format(((float)$data[$r]->versand + (float)$data[$r]->zahlart_add), 2, '.', '').'</REMARK>'.CR;
   $lex .= '            <REMARK type="tax_area">'.$tax_area.'</REMARK>'.CR;
   $lex .= '            <REMARK type="order"></REMARK>'.CR;
   $lex .= '            <REMARK type="additional_costs">0.00</REMARK>'.CR;
   $lex .= '         </ORDER_INFO>'.CR;
   $lex .= '      </ORDER_HEADER>'.CR;
   $lex .= '      <ORDER_ITEM_LIST>'.CR;

   for ($a = 0; $a < (is_array($articles) ? count($articles) : 0); $a++) {
      $lex .= '         <ORDER_ITEM>'.CR;
      $lex .= '            <LINE_ITEM_ID>'.$a.'</LINE_ITEM_ID>'.CR;
      $lex .= '            <ARTICLE_ID>'.CR;
      $lex .= '               <SUPPLIER_AID>'.$articles[$a]->artikel_nummer.'</SUPPLIER_AID>'.CR;
//      $lex .= '               <DESCRIPTION_SHORT><![CDATA['.$this->_lexToAscii($articles[$a]->name_shop).']]></DESCRIPTION_SHORT>'.CR;
// TODO: Eintrag doppelt, macht aber keine Probleme. Bei nächster Änderung löschen
      $lex .= '               <DESCRIPTION_LONG></DESCRIPTION_LONG>'.CR;
      $lex .= '               <DESCRIPTION_LONG><![CDATA['.$this->_lexToAscii($this->_html2txt($articles[$a]->desc_shop)).']]></DESCRIPTION_LONG>'.CR;
      $lex .= '            </ARTICLE_ID>'.CR;
      $lex .= '            <QUANTITY>'.$articles[$a]->menge.'</QUANTITY>'.CR;
      $lex .= '            <ORDER_UNIT>1</ORDER_UNIT>'.CR;
      $lex .= '            <ARTICLE_PRICE type="net_list">'.CR;
      $lex .= '               <PRICE_AMOUNT>'.number_format((float)$articles[$a]->artikel_preis, 2, '.', '').'</PRICE_AMOUNT>'.CR;
      $lex .= '               <PRICE_LINE_AMOUNT>'.number_format((float)$articles[$a]->artikel_preis, 2, '.', '').'</PRICE_LINE_AMOUNT>'.CR;
      $lex .= '               <TAX>0.'.$data[$r]->{'steuersatz'.$articles[$a]->steuersatz}.'</TAX>'.CR;
      $lex .= '            </ARTICLE_PRICE>'.CR;
      $lex .= '         </ORDER_ITEM>'.CR;
   }

   $lex .= '      </ORDER_ITEM_LIST>'.CR;
   $lex .= '      <ORDER_SUMMARY>'.CR;
   $lex .= '         <TOTAL_ITEM_NUM>'.$articles_count.'</TOTAL_ITEM_NUM>'.CR;
   $lex .= '         <TOTAL_AMOUNT>'.number_format((float)$data[$r]->netto, 2, '.', '').'</TOTAL_AMOUNT>'.CR;
   $lex .= '      </ORDER_SUMMARY>'.CR;
   $lex .= '   </ORDER>'.CR;
}
$lex .= '</ORDER_LIST>'.CR;
header('Content-type: text/xml');
header('Content-Disposition: attachment; filename="LEXWARE_XML_'.date('d_m_Y').'.xml"');
echo $lex;
exit;
