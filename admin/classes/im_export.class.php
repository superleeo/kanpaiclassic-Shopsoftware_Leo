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

namespace KANPAICLASSIC;

if (!defined('KANPAICLASSIC')) {
   die ("This file cannot run outside the Kanpai Classic Shopsoftware");
}

if (!defined('BR')) {
    define('BR', "<br />");
}

class KANPAICLASSIC_importExport
{
   private $params        = null;
   private $db            = null;
   private $db_extern     = null;
   private $last_id       = 0;
   private $picdownloads  = 'n';
   private $sort_fixed    = false;
   private $search_art_nr = false;
   private $ge_netto      = '';
   private $is_ge_netto   = false;

   function __construct() {
      $this->params    = Control::getParams();
      $this->db        = Control::getDb();
      $this->db_extern = Control::getExternDB();
   }

   // Artikel, Lager, Newsletter oder Kunden als CSV- oder XML-Datei exportieren xml_html / csv_html kompletter Shop
   // 05.04.2019
   public function export($typ, $haendler_id = 0) {
      // Parameter 2 wird nur bei flow ausgewertet
      switch ($typ) {
         // Flow XML
         case 'flow_xml_html':
            $this->_exportArticles('flow', 'xml', 'html', $haendler_id);
            break;

         // Flow CSV mit html
         case 'flow_csv_html':
            $this->_exportArticles('flow', 'csv', 'html', $haendler_id);
            break;

         // Flow CSV mit html
         case 'flow_csv_text':
            $this->_exportArticles('flow', 'csv', 'text', $haendler_id);
            break;

         // 77Marken XML Text
         case 'portal_xml':
            $this->_exportArticles('portal', 'xml', 'text', $haendler_id);
            break;

         // 77Marken CSV Text
         case 'portal_csv':
            $this->_exportArticles('portal', 'csv', 'text', $haendler_id);
            break;

         // GX2 CSV Text
         case 'gx2_csv':
            $this->_exportArticles('gx2', 'csv', 'text',  $haendler_id);
            break;

         // dyn CSV Text
         case 'dyn_csv':
            $this->_exportArticles('dyn', 'csv', 'text',  $haendler_id);
            break;

         // Lexware Artikel XML Text
         case 'lex_artikel_xml':
            $this->_exportLexArtikelXml();
            break;

         // Lexware Artikel CSV Text
         case 'lex_artikel_csv':
            $this->_exportLexArtikelCsv();
            break;

         // Lexware Artikel XML Text
         case 'lex_best_xml':
            $this->_exportLexBestellung('xml');
            break;

         // Lexware Artikel CSV Text
         case 'lex_best_csv':
            $this->_exportLexBestellung('csv');
            break;

         // Lexware Artikel CSV Text
         case 'bestell_csv':
            $this->exportBestellung('csv');
            break;

         case 'newsletter':
            $this->_exportArticles('newsletter', 'csv', $haendler_id);
            break;

         case 'kunden':
            $this->_exportArticles('kunden', 'csv', $haendler_id);
            break;

         case 'lager':
            $this->_exportArticles('lager', 'csv', $haendler_id);
            break;

         case 'lager_name':
             $this->_exportArticles('lager_name', 'csv', $haendler_id);
             break;

         case 'lager_artnr':
             $this->_exportArticles('lager_artnr', 'csv', $haendler_id);
             break;

         case 'amazon_de':
            $this->_exportArticles('amazon_de', 'csv', $haendler_id);
            break;

         case 'billiger_de':
            $this->_exportArticles('billiger_de', 'csv', $haendler_id);
            break;

         case 'ciao_de':
            $this->_exportArticles('ciao_de', 'csv', $haendler_id);
            break;

         case 'guenstiger_de':
            $this->_exportArticles('guenstiger_de', 'csv', $haendler_id);
            break;

         case 'hood':
            $this->_exportArticles('hood', 'csv', $haendler_id);
            break;

         case 'idealo_de':
            $this->_exportArticles('idealo_de', 'csv', $haendler_id);
            break;

         case 'kelkoo.de':
            $this->_exportArticles('kelkoo', 'csv', $haendler_id);
            break;

         case 'wein_cc':
            $this->_exportArticles('wein_cc', 'csv', $haendler_id);
            break;

         case 'yatego_de':
            $this->_exportArticles('yatego_de', 'csv', $haendler_id);
            break;
      }
   }

   //   private function _exportArticles ($shop, $format = 'csv', $html = 'html', $all = 'all') {
   private function _exportArticles ($shop, $format, $html = 'html', $haendler_id = 0) {
      $json        = null;
      $trenner     = ',';
      $worttrenner = '"';
      $shopurl     = SHOP_URL;
      $lang        = 'deu';
      $picurl      = $shopurl;

      // Shop XML und CSV
      if ($shop == 'flow') {
         $json = [
            [
               'name'          => '',
               'file'          => 'flow_'.$format.'.inc.php',
               'format'        => $format,
               'html'          => $html,
               'all'           => 'all',
               'trenner'       => ';',
               'worttrenner'   => '"',
               'exp_preis'     => 'netto',
               'exp_stellen'   => '2',
               'exp_separator' => '.',
               'exp_sep1000'   => '',
               'csv_head'      => 'y',
               'outfile'       => 'artikel_export'.($format == 'csv' ? '_'.$html : '')
            ]
         ];
      }

      else if ($shop == 'portal') {
         if ($format == 'xml') {
            $json = [
               [
                  'name'          => '',
                  'file'          => 'portal_xml.inc.php',
                  'format'        => 'xml',
                  'html'          => 'html',
                  'all'           => 'all',
                  'trenner'       => ';',
                  'worttrenner'   => '"',
                  'exp_preis'     => 'netto',
                  'csv_head'      => 'y',
                  'outfile'       => '77marken'
               ]
            ];
         }

         else {
            $json = [
               [
                  'name'          => '',
                  'file'          => 'portal_csv.inc.php',
                  'format'        => $format,
                  'html'          => $html,
                  'all'           => 'all',
                  'trenner'       => ';',
                  'worttrenner'   => '"',
                  'exp_preis'     => 'netto',
                  'exp_stellen'   => '2',
                  'exp_separator' => '.',
                  'exp_sep1000'   => '',
                  'csv_head'      => 'y',
                  'outfile'       => '77marken'.($format == 'csv' ? '_'.$html : '')
               ]
            ];
         }
      }

      else if ($shop == 'gx2') {
         $json = [
            [
               'name'          => '',
               'file'          => 'gx2_csv.inc.php',
               'format'        => 'csv',
               'html'          => $html,
               'all'           => 'all',
               'trenner'       => '|',
               'worttrenner'   => '',
               'exp_preis'     => 'netto',
               'exp_stellen'   => '2',
               'exp_separator' => '.',
               'exp_sep1000'   => '',
               'csv_head'      => 'y',
               'charset_out'   => 'Windows-1252',
               'outfile'       => 'gx2'
            ]
         ];
      }

      else if ($shop == 'amazon_de') {
         $json = [
            [
               'name'          => '',
               'file'          => 'amazon_csv.inc.php',
               'format'        => 'csv',
               'html'          => 'text',
               'all'           => 'all',
               'trenner'       => ';',
               'worttrenner'   => '"',
               'exp_preis'     => 'netto',
               'exp_stellen'   => '2',
               'exp_separator' => '.',
               'exp_sep1000'   => '',
               'csv_head'      => 'y',
               'outfile'       => 'amazon',
               'export'        => 'y'
            ]
         ];
      }

      else if ($shop == 'newsletter') {
         $json = [
            [
               'name'          => '',
               'file'          => 'newsletter_csv.inc.php',
               'format'        => 'csv',
               'html'          => 'text',
               'all'           => 'all',
               'trenner'       => ';',
               'worttrenner'   => '',
               'exp_preis'     => 'netto',
               'exp_stellen'   => '2',
               'exp_separator' => '.',
               'exp_sep1000'   => '',
               'csv_head'      => 'n',
               'outfile'       => 'newsletter'
            ]
         ];
      }

      else if ($shop == 'kunden') {
         $json = [
            [
               'name'          => '',
               'file'          => 'kunden_csv.inc.php',
               'format'        => 'csv',
               'html'          => 'text',
               'all'           => 'all',
               'trenner'       => ';',
               'worttrenner'   => '',
               'exp_preis'     => 'netto',
               'exp_stellen'   => '2',
               'exp_separator' => '.',
               'exp_sep1000'   => '',
               'csv_head'      => 'y',
               'outfile'       => 'kunden'
            ]
         ];
      }

      else if ($shop == 'lager') {
         $json = [
            [
               'name'          => '',
               'file'          => 'lager_csv.inc.php',
               'format'        => 'csv',
               'html'          => 'text',
               'all'           => 'all',
               'trenner'       => ';',
               'worttrenner'   => '"',
               'exp_preis'     => 'netto',
               'exp_stellen'   => '2',
               'exp_separator' => '.',
               'exp_sep1000'   => '',
               'csv_head'      => 'y',
               'outfile'       => 'lager'
            ]
         ];
      }

      else if ($shop == 'lager_name') {
          $json = [
             [
                'name'          => '',
                'file'          => 'lagername_csv.inc.php',
                'format'        => 'csv',
                'html'          => 'text',
                'all'           => 'all',
                'trenner'       => ';',
                'worttrenner'   => '"',
                'exp_preis'     => 'netto',
                'exp_stellen'   => '2',
                'exp_separator' => '.',
                'exp_sep1000'   => '',
                'csv_head'      => 'y',
                'outfile'       => 'lager'
             ]
          ];
      }


      else if ($shop == 'lager_artnr') {
          $json = [
             [
                'name'          => '',
                'file'          => 'lagerartnr_csv.inc.php',
                'format'        => 'csv',
                'html'          => 'text',
                'all'           => 'all',
                'trenner'       => ';',
                'worttrenner'   => '"',
                'exp_preis'     => 'netto',
                'exp_stellen'   => '2',
                'exp_separator' => '.',
                'exp_sep1000'   => '',
                'csv_head'      => 'y',
                'outfile'       => 'lager'
             ]
          ];
      }

      // Tools / Portale
      else if ($shop == 'billiger_de') {
         $json = [
            [
               'name'          => '',
               'file'          => 'portale_billiger_csv.inc.php',
               'format'        => 'csv',
               'html'          => 'html',
               'all'           => 'all',
               'trenner'       => '|',
               'worttrenner'   => '',
               'exp_preis'     => 'brutto',
               'csv_head'      => 'y',
               'outfile'       => 'billiger',
               'export'        => 'y'
            ]
         ];
      }

      else if ($shop == 'ciao_de') {
         $json = [
            [
               'name'          => '',
               'file'          => 'portale_ciao_xml.inc.php',
               'format'        => 'xml',
               'html'          => 'html',
               'all'           => 'all',
               'trenner'       => ';',
               'worttrenner'   => '"',
               'exp_preis'     => 'netto',
               'csv_head'      => 'y',
               'outfile'       => 'ciao',
               'export'        => 'y'
            ]
         ];
      }

      // 24.05.2016
      else if ($shop == 'guenstiger_de') {
         $json = [
            [
               'name'          => '',
               'file'          => 'portale_guenstiger_csv.inc.php',
               'format'        => 'csv',
               'html'          => 'html',
               'all'           => 'all',
               'trenner'       => '|',
               'worttrenner'   => '',
               'exp_preis'     => 'brutto',
               'csv_head'      => 'y',
               'outfile'       => 'guenstiger',
               'export'        => 'y'
            ]
         ];
      }

      else if ($shop == 'hood') {
         $json = [
            [
               'name'          => '',
               'file'          => 'portale_hood_csv.inc.php',
               'format'        => 'csv',
               'html'          => 'text',
               'all'           => 'all',
               'trenner'       => ';',
               'worttrenner'   => '"',
               'exp_preis'     => 'netto',
               'exp_stellen'   => '2',
               'exp_separator' => '.',
               'exp_sep1000'   => '',
               'csv_head'      => 'y',
               'outfile'       => 'hood',
               'export'        => 'y'
            ]
         ];
      }

      else if ($shop == 'idealo_de') {
         $json = [
            [
               'name'          => '',
               'file'          => 'portale_idealo_xml.inc.php',
               'format'        => 'xml',
               'html'          => 'html',
               'all'           => 'all',
               'trenner'       => ';',
               'worttrenner'   => '"',
               'exp_preis'     => 'netto',
               'csv_head'      => 'y',
               'outfile'       => 'idealo',
               'export'        => 'y'
            ]
         ];
      }

      else if ($shop == 'kelkoo_de') {
         $json = [
            [
               'name'          => '',
               'file'          => 'portale_kelkoo_xml.inc.php',
               'format'        => 'xml',
               'html'          => 'html',
               'all'           => 'all',
               'trenner'       => ';',
               'worttrenner'   => '"',
               'exp_preis'     => 'netto',
               'csv_head'      => 'y',
               'outfile'       => 'kelkoo',
               'export'        => 'y'
            ]
         ];
      }

      else if ($shop == 'wein_cc') {
         $json = [
            [
               'name'          => '',
               'file'          => 'portale_wein_csv.inc.php',
               'format'        => 'csv',
               'html'          => 'text',
               'all'           => 'all',
               'trenner'       => ';',
               'worttrenner'   => '"',
               'exp_preis'     => 'netto',
               'exp_stellen'   => '2',
               'exp_separator' => '.',
               'exp_sep1000'   => '',
               'csv_head'      => 'y',
               'outfile'       => 'wein',
               'export'        => 'y'
            ]
         ];
      }

      else if ($shop == 'yatego_de') {
         $json = [
            [
               'name'          => '',
               'file'          => 'portale_yatego_xml.inc.php',
               'format'        => 'xml',
               'html'          => 'html',
               'all'           => 'all',
               'trenner'       => ';',
               'worttrenner'   => '"',
               'exp_preis'     => 'netto',
               'csv_head'      => 'y',
               'outfile'       => 'yatego',
               'export'        => 'y'
            ]
         ];
      }

      // Default verwenden
      if ($json == null) {
         $json = [
            [
               // Allgemein
               'name'          => '',
               'file'          => 'xtc_csv.inc.php',
               'format'        => 'csv',
               'html'          => 'text',
               'csv_head'      => 'y',
               'trenner'       => ';',
               'worttrenner'   => '',

               // Export
               'all'           => 'all',
               'exp_preis'     => 'brutto',
               'exp_stellen'   => '4',
               'exp_separator' => '.',
               'exp_sep1000'   => '',
               'outfile'       => 'xtc',
               'charset_out'   => 'UTF-8',

               // Import
               'imp_preis'     => 'netto',
               'charset_in'    => 'UTF-8',
               'image_load'    => 'n',
               'image_server'  => '',
               'imp_merkmal1'  => 'Größe',
               'imp_merkmal2'  => 'Farbe'
            ]
         ];
      }

      $config = (object)$json[0];
      $includefile = ADMIN_PATH.'/classes/import_export/'.$config->file;

      if (!file_exists($includefile)) {
         // Fehler
         echo "<script>parent.Royalart.uploadDone('error', 'Datei konnte nicht gelesen werden '.$includefile);</script>";
         exit;
      }

      $mode = 'export';
      include $includefile;

      // Datei speichern
      if (isset($config->export) && $config->export == 'y' || ($shop == 'flow' && $format == 'csv')) {
         $this->_removeExportFile($config->outfile);

         // Daten
         $fp = fopen(SHOP_PATH.'/export/'.$config->outfile.'.'.$config->format, 'w');
         fwrite($fp, ${$config->format});
         fclose($fp);

         // Info
         $fp = fopen(SHOP_PATH.'/export/'.$config->outfile.date('d_m_Y H-i').'.info', 'w');
         fwrite($fp, ' ');
         fclose($fp);
      }

      $charset = 'utf-8';
      if (isset($config->charset_out) && strtolower($config->charset_out) != 'utf-8') {
         $charset = strtolower($config->charset_out);
      }

      if ($config->format == 'xml') {
         header('Content-type: text/xml; charset='.$charset);
         header('Content-Disposition: attachment; filename="'.$config->outfile.'_'.date('d_m_Y').'.xml"');
         echo $xml;
      }

      if ($config->format == 'csv') {
         header('Content-type: text/csv; charset='.$charset);
         header('Content-Disposition: attachment; filename="'.$config->outfile.'_'.date('d_m_Y').'.csv"');
         echo $csv;
      }

      exit();
   }

   // Artikel für Lexware als XML-Datei exportieren ohne html, auf 300 Zeichen begrenzt
   // 15.03.2019
   public function _exportLexArtikelXml() {
      $lang = 'deu';
      // Zusatzinfo für Artikel auslesen
      $sql = "SELECT i.id, ac.cat_id, i.steuersatz, i.name_$lang AS name, i.desc_$lang AS beschreibung,
                     i.image,
                     i.versand_preis, i.gewicht,
                     c.name_deu AS catname,
                     g.zustand, i.marke, a.gtin, a.mpn
                 FROM shop_articles_info AS i
              LEFT JOIN #__articles AS a
                 ON a.parent_id = i.id
              LEFT JOIN #__article_to_cats AS ac
                 ON ac.parent_id = i.id
              LEFT JOIN #__categories AS c
                 ON c.id = ac.cat_id
              LEFT JOIN shop_articles_to_googlecats AS g
                 ON  g.parent_id = i.id
              WHERE a.sort = 1
                 AND ac.sort = 0
              ORDER BY i.id";
      $info = $this->db->queryAllObjects($sql);

      $out  = '<?xml version="1.0" encoding="UTF-8"?>'.CR;
      $out .= '<articles>'.CR;
      $out .= '   <lang>deu</lang>'.CR;

      for ($i = 0; $i < (is_array($info) ? count($info) : 0); $i++) {
         $info[$i]->images = $this->db->queryAllObjects("SELECT image FROM #__articles_images WHERE parent_id = ".$info[$i]->id." ORDER BY sort");

         // Bei Kleingewerbe Steuersatz3 (0%)
         if ($this->params->firma['kleingewerbe'] == 'y') {
            $info[$i]->steuersatz = 3;
         }

         $out .= '   <article>'.CR;
         $out .= '      <id>'.$info[$i]->id.'</id>'.CR;

         // Varianten lesen
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
                ORDER BY sort";

         $data = $this->db->queryAllObjects($sql);

         for ($d = 0; $d < (is_array($data) ? count($data) : 0); $d++) {
            $out .= '      <variant>'.CR;
            $out .= '         <sort>'.$data[$d]->sort.'</sort>'.CR;
            $out .= '         <artnr><![CDATA['.$data[$d]->art_nr.']]></artnr>'.CR;
            $out .= '         <merkmal1><![CDATA['.$this->_lexToAscii($data[$d]->mm_name1).']]></merkmal1>'.CR;
            $out .= '         <wert1><![CDATA['.$this->_lexToAscii($data[$d]->w_name1).']]></wert1>'.CR;
            $out .= '         <merkmal2><![CDATA['.$this->_lexToAscii($data[$d]->mm_name2).']]></merkmal2>'.CR;
            $out .= '         <wert2><![CDATA['.$this->_lexToAscii($data[$d]->w_name2).']]></wert2>'.CR;
            $out .= '         <preis>'.$data[$d]->netto.'</preis>'.CR;
            $out .= '         <angebot_active>'.$data[$d]->angebot_active.'</angebot_active>'.CR;
            $out .= '         <angebotspreis>'.$data[$d]->angebot.'</angebotspreis>'.CR;
            $out .= '         <lagerbestand>'.$data[$d]->menge.'</lagerbestand>'.CR;
            $out .= '      </variant>'.CR;
         }

         $info[$i]->name         = str_replace('"', '""', $info[$i]->name);
         $info[$i]->beschreibung = str_replace('"', '""', $info[$i]->beschreibung);

         $out .= '      <name><![CDATA['.$this->_lexToAscii($info[$i]->name).']]></name>'.CR;
         $out .= '      <beschreibung><![CDATA['.$this->_lexToAscii(Helper::truncate($info[$i]->beschreibung, 300)).']]></beschreibung>'.CR;
         $out .= '      <kategorie_id>'.$info[$d]->cat_id.'</kategorie_id>'.CR;
         $out .= '      <kategoriename><![CDATA['.$this->_lexToAscii($info[$d]->catname).']]></kategoriename>'.CR;
         $out .= '      <steuersatz>'.$info[$d]->steuersatz.'</steuersatz>'.CR;
         $out .= '      <versand>'.$info[$d]->versand_preis.'</versand>'.CR;
         $out .= '      <gewicht>'.$info[$d]->gewicht.'</gewicht>'.CR;
         $out .= '      <link>'.SHOP_URL_IDX.'/deu_'.$info[$i]->id.'/'.urldecode($info[$i]->name).'</link>'.CR;
         $out .= '      <bildlink>'.  $this->_checkPict($info[$i]->image).'</bildlink>'.CR;

         for ($p = 0; $p < 10; $p++) {
            $out .= '      <bildlink'.($p+2).'>'. $this->_checkPict(isset($info[$i]->images[$p]->image) ? $info[$i]->images[$p]->image : '').'</bildlink'.($p + 2).'>'.CR;
         }

         $out .= '      <marke>'.$info[$d]->marke.'</marke>'.CR;
         $out .= '      <gtin>'.$info[$d]->gtin.'</gtin>'.CR;
         $out .= '      <mpn>'.$info[$d]->mpn.'</mpn>'.CR;
         $out .= '   </article>'.CR;
      }

      $out .= '</articles>'.CR;

      header('Content-type: text/xml');
      header('Content-Disposition: attachment; filename="artikel_lexware_'.date('d_m_Y').'.xml"');
      header('Content-Length: '.strlen($out));
      echo $out;
      exit;
   }

   // Artikel für Lexware als CSV-Datei exportieren ohne html, auf 300 Zeichen begrenzt
   public function _exportLexArtikelCsv() {
      $trenner     = ',';
      $worttrenner = '"';
      $shopurl     = SHOP_URL;
      $lang        = 'deu';
      $picurl      = $shopurl;

      $json = [
         [
            'name' => '',
            'file'        => 'lexware_artikel_csv.inc.php',
            'format'      => 'csv',
            'html'        => 'text',
            'all'         => 'all',
            'trenner'     => ';',
            'worttrenner' => '"',
            'exp_preis'   => 'netto',
            'exp_stellen'   => '2',
            'exp_separator' => '.',
            'exp_sep1000'   => '',
            'csv_head'    => 'y',
            'outfile'     => 'lexware_csv'
         ]
      ];

      $config = (object)$json[0];
      $includefile = ADMIN_PATH.'/classes/import_export/'.$config->file;

      if (!file_exists($includefile)) {
         // Fehler
         echo "<script>parent.Royalart.uploadDone('error', 'Datei konnte nicht gelesen werden '.$includefile);</script>";
         exit;
      }

      $mode = 'export';
      include $includefile;

      $charset = 'utf-8';
      if (isset($config->charset_out) && strtolower($config->charset_out) != 'utf-8') {
         $charset = strtolower($config->charset_out);
      }

      header('Content-type: text/csv; charset='.$charset);
      header('Content-Disposition: attachment; filename="'.$config->outfile.'_'.date('d_m_Y').'.csv"');
      echo $csv;
      exit();

   }

   // Bestellung nach Kauf in Datei Speichern / Datei ausgeben ($auto != 'auto')
   public function exportBestellung($mode, $default = 'default', $auto = '') {
      $shop_staat = 160; // Deutschland

      $data = null;
      $lang = 'deu';

      if ($auto == 'auto') {
         // Aktuelle Bestellung Speichern (über Params -> Helper)
         $data = $this->db->queryAllObjects("SELECT r.*, UNIX_TIMESTAMP(r.rechnungsdatum) AS rdatum, l1.domain AS iso, l2.domain AS lf_iso
                   FROM #__rechnung AS r
                LEFT JOIN  #__laender AS l1
                   ON r.staat = l1.id
                LEFT JOIN  #__laender AS l2
                   ON r.lf_staat = l2.id
                WHERE r.id = ".$_SESSION['AFTERBUY_ID']);
      }

      else {
         // Alle Bestellungen speichern / ausgeben (über Tools)
         $data = $this->db->queryAllObjects("SELECT r.*, UNIX_TIMESTAMP(r.rechnungsdatum) AS rdatum, l1.domain AS iso, l2.domain AS lf_iso
                   FROM #__rechnung AS r
                LEFT JOIN  #__laender AS l1
                   ON r.staat = l1.id
                LEFT JOIN  #__laender AS l2
                   ON r.lf_staat = l2.id
                WHERE deleted = 'n'
                   AND created > '".date('Y-m-d H:m', time() - (7 * 24 * 3600))."'");
      }

      for ($r = 0; $r < (is_array($data) ? count($data) : 0); $r++) {
         $re_id = $data[$r]->id;
         $articles = $this->db->queryAllObjects("SELECT r.*, m1.merkmal_$lang AS merkmal1, w1.wert_$lang AS wert1, m2.merkmal_$lang AS merkmal2, w2.wert_$lang AS wert2
                                                    FROM #__rechnung_artikel AS r
                                                 LEFT JOIN #__werte as w1
                                                    ON r.wert1 = w1.id
                                                 LEFT JOIN #__werte as w2
                                                    ON r.wert2 = w2.id
                                                 LEFT JOIN #__merkmale as m1
                                                    ON r.merkmal1 = m1.id
                                                 LEFT JOIN #__merkmale as m2
                                                    ON r.merkmal2 = m2.id
                                                 WHERE r.rechnung_id = $re_id
                                                 ORDER BY r.id");

         $articles_count = (is_array($articles) ? count($articles) : 0);

         $data[$r]->articles       = $articles;
         $data[$r]->articles_count = $articles_count;
      }

      require_once ADMIN_PATH.'/classes/import_export/bestellung_auto_csv.inc.php';
   }

   // Datei für Lexware (Bestellungen) erstellen
   public function _exportLexBestellung($mode, $default = 'default', $auto = '') {
      if ($mode == '') {
         $mode = 'xml';
      }

      $shop_staat = 160; // Deutschland
      $data = $this->db->queryAllObjects("SELECT r.*, UNIX_TIMESTAMP(r.rechnungsdatum) AS rdatum, l1.domain AS iso, l2.domain AS lf_iso
                FROM #__rechnung AS r
             LEFT JOIN  #__laender AS l1
                ON r.staat = l1.id
             LEFT JOIN  #__laender AS l2
                ON r.lf_staat = l2.id
             WHERE r.status = 3
                AND deleted = 'n'");

      for ($r = 0; $r < count($data); $r++) {
         $articles       = $this->db->queryAllObjects("SELECT * FROM #__rechnung_artikel WHERE rechnung_id = ".$data[$r]->id);
         $articles_count = (isset($articles) ? count($articles) : 0);

         $data[$r]->articles       = $articles;
         $data[$r]->articles_count = $articles_count;
      }

      if ($mode == 'xml') {
         require_once ADMIN_PATH.'/classes/import_export/lexware_bestellung_xml.inc.php';
      }

      else {
         require_once ADMIN_PATH.'/classes/import_export/lexware_bestellung_csv.inc.php';
      }

      exit;
   }

   // Datei für Datev (Bestellungen) erstellen
   public function exportBuchungen($template, $mode, $_2zeilig_check = 'n', $tag = 0) {
      $filename    = '';
      $buchung_file    = '';

      $between   = '';
      $year_now  = (int)date('Y');
      $month_now = (int)date('m');
      $day_now   = (int)date('d');
      $status    = '';
      $file        = false;
      $buch_anzahl = 0;

      // Bestellungen aktueller Monat mit Status 3 (Bereit)
      if ($mode == 'datev_a' || $mode == 'easycash_a') {
         $status   = ' r.status = 3 ';

         if ($template == 'datev') {
            $filename = 'datev-vom_'.date('d_m_Y__H_i').'.csv';
         }

         else {
            $filename = 'buchungen-vom_'.date('d_m_Y__H_i').'.csv';
         }

         $between = " AND r.rechnungsdatum BETWEEN '".$year_now."-".$month_now."-01 00:00' AND '".$year_now."-".$month_now."-".$day_now." 23:59' ";
      }

      // Letzter Monat Status 4 (versendet)
      else if ($mode == 'datev_m' || $mode == 'easycash_m') {
         $month = $month_now;
         $year  = $year_now;

         if ($month == 1) {
            $month = 12;
            $year--;
         }

         else {
            $month--;
         }

         $day = 31;

         if ($month == 4 || $month == 6 || $month == 9 || $month == 11) {
            $day = 30;
         }

         if ($month == 2) {
            $day = 28;

            if ($year % 4 == 0) {
               $day = 29;
            }
         }

         $month = sprintf('%02d', $month);

         if (defined('CONF_AUTO_BUCHUNG')) {
            if (file_exists(SHOP_PATH.'/export/buchung.lock')) {
               $tag = (int)file_get_contents(SHOP_PATH.'/export/buchung.lock');
            }

            $tag++;
            $file = true;
            set_time_limit(ini_get('max_execution_time'));
            file_put_contents(SHOP_PATH.'/export/buchung.lock', $tag);

            // Datei ausgeben
            if ($tag > $day) {
               unlink(SHOP_PATH.'/export/buchung.lock');
               header('Content-type: text/csv');
               header('Content-Disposition: attachment; filename=buchungen_monat_'.$month.'_'.$year.'.csv');
               echo file_get_contents(SHOP_PATH.'/export/buchungen.csv');
               unlink(SHOP_PATH.'/export/buchungen.csv');
               exit;
            }

            $between = " AND r.rechnungsdatum BETWEEN '".$year."-".$month."-".$tag." 00:00' AND '".$year."-".$month."-".$tag." 23:59' ";
            $buchung_file = SHOP_PATH.'/export/buchungen.csv';
         }

         else {
            $between = " AND r.rechnungsdatum BETWEEN '".$year."-".$month."-01 00:00' AND '".$year."-".$month."-".$day." 23:59' ";
         }

         $status  = ' r.status = 4 ';

         if ($template == 'datev') {
            $filename = 'datev_monat_'.$month.'_'.$year.'.csv';
         }

         else {
            $filename = 'buchungen_monat_'.$month.'_'.$year.'.csv';
         }
      }

      // Letztes Quartal Status 4 (versendet)
      else if ($mode == 'datev_q' || $mode == 'easycash_q') {
         $month = $month_now;
         $m_end = $month_now;
         $year  = $year_now;
         $q     = 1;

         if ($month >= 10) {
            $m_start = 7;
            $m_end   = 9;
            $q       = 3;
         }

         else if ($month >= 7) {
            $m_start = 4;
            $m_end   = 6;
            $q       = 2;
         }

         else if ($month >= 4) {
            $m_start = 1;
            $m_end   = 3;
            $q       = 1;
         }

         else {
            $m_start = 10;
            $m_end   = 12;
            $q       = 4;
            $year--;
         }

         $day = 31;

         if ($m_end == 6 || $m_end == 9) {
            $day = 30;
         }

         $status  = ' r.status = 4 ';
         $between = " AND r.rechnungsdatum BETWEEN '".$year."-".$m_start."-01 00:00' AND '".$year."-".$m_end."-".$day." 23:59' ";

         if ($template == 'datev') {
            $filename = 'datev_quartal_'.$q.'_'.$year.'.csv';
         }

         else {
            $filename = 'buchungen_quartal_'.$q.'_'.$year.'.csv';
         }
      }

      // letztes Jahr Status 4 (versendet)
      else if ($mode == 'datev_y' || $mode == 'easycash_y') {
         $year    = $year_now - 1;
         $status  = ' r.status = 4 ';
         $between = " AND r.rechnungsdatum BETWEEN '".$year."-01-01 00:00' AND '".$year."-12-31 23:59' ";

         if ($template == 'datev') {
            $filename = 'datev_'.$year.'.csv';
         }

         else {
            $filename = 'buchungen_'.$year.'.csv';
         }
      }

      else {
         return 'Funktion nicht vorhanden';
      }

      if ($buchung_file == '') {
         unset($buchung_file);
      }

      $data = $this->db->queryAllObjects("SELECT r.*, UNIX_TIMESTAMP(r.rechnungsdatum) AS rdatum, l1.domain AS iso, l2.domain AS lf_iso
                FROM #__rechnung AS r
             LEFT JOIN  #__laender AS l1
                ON r.staat = l1.id
             LEFT JOIN  #__laender AS l2
                ON r.lf_staat = l2.id
             WHERE $status
                AND deleted = 'n' $between
             ORDER BY r.id");

      for ($r = 0; $r < (is_array($data) ? count($data) : 0); $r++) {
         $buch_anzahl++;
         $articles       = $this->db->queryAllObjects("SELECT * FROM #__rechnung_artikel WHERE rechnung_id = ".$data[$r]->id);
         $articles_count = (is_array($articles) ? count($articles) : 0);

         $data[$r]->articles       = $articles;
         $data[$r]->articles_count = $articles_count;
      }

      if ($template == 'datev') {
         $shop_staat = 160; // Deutschland
         require_once ADMIN_PATH.'/classes/import_export/datev_buchungen.inc.php';
      }
      else {
         $shop_staat = 160; // Deutschland
         require_once ADMIN_PATH.'/classes/import_export/easycash_buchungen.inc.php';
      }

      if ($tag > 0) {
         header('Content-Disposition: attachment; filename=Nochmals%20starten%20Tag%20'.$tag.'%20-%20'.$buch_anzahl.'%20Rechnungen');
         echo 'Nochmals starten Tag '.$tag.' - '.$buch_anzahl.' Rechnungen';
         exit;
      }
   }

   // Buchungen nach Rechnungserstellung speichern
   public function exportBuchungenAuto($re_id, $check = false, $delete = false, $status = 0, $status_old = 0) {
      if (!defined('CONF_AUTO_BUCHUNG')) {
         return;
      }

      $shop_staat     = 160; // Deutschland
      $_2zeilig_check = 'y';
      $file           = true;
      $year           = date('Y');
      $month          = date('m');


      $data[0] = $this->db->querySingleObject("SELECT r.*, UNIX_TIMESTAMP(r.rechnungsdatum) AS rdatum, l1.domain AS iso, l2.domain AS lf_iso
             FROM #__rechnung AS r
          LEFT JOIN  #__laender AS l1
             ON r.staat = l1.id
          LEFT JOIN  #__laender AS l2
             ON r.lf_staat = l2.id
          WHERE r.id = $re_id");

      $articles       = $this->db->queryAllObjects("SELECT * FROM #__rechnung_artikel WHERE rechnung_id = $re_id");
      $articles_count = (is_array($articles) ? count($articles) : 0);

      $data[0]->articles       = $articles;
      $data[0]->articles_count = $articles_count;
      $best_nr                 = $data[0]->bestellnummer;

      // EasyCash
      $buchung_file = SHOP_PATH.'/export/buchungen_monat_'.$month.'_'.$year.'.csv';

      if ($check && file_exists($buchung_file)) {
         $found     = false;
         $buchungen = file($buchung_file);

         foreach($buchungen as $k => $v) {
            if (\strpos($v, $best_nr) !== false) {
               unset($buchungen[$k]);

               if ($found) {
//                  break;
               }

               $found = true;
            }
         }

         if ($found) {
            // \file_put_contents($buchung_file, implode("\r\n", $buchungen));
            \file_put_contents($buchung_file, implode("", $buchungen));
         }
      }

      if (!$delete) {
         require_once ADMIN_PATH.'/classes/import_export/easycash_buchungen.inc.php';
      }

      // Datev
      $buchung_file = SHOP_PATH.'/export/datev_monat_'.$month.'_'.$year.'.csv';

      if ($check && file_exists($buchung_file)) {
         $found     = false;
         $buchungen = file($buchung_file);

         foreach($buchungen as $k => $v) {
            if (\strpos($v, $best_nr) !== false) {
               unset($buchungen[$k]);

               if ($found) {
//                  break;
               }

               $found = true;
            }
         }

         if ($found) {
            // \file_put_contents($buchung_file, implode("\r\n", $buchungen));
            \file_put_contents($buchung_file, implode("", $buchungen));
         }
      }

      if (!$delete) {
      require_once ADMIN_PATH.'/classes/import_export/datev_buchungen.inc.php';
      }

      return;
   }

   public function exportCategoriesXML() {
      $struktur = $this->db->queryAllObjects("DESCRIBE #__categories");
      $data = $this->db->queryAllObjects("SELECT * FROM #__categories ORDER BY level, ordered, id");
      $xml  = '<?xml version="1.0" encoding="UTF-8"?>'.CR;
      $xml .= '<categories>'.CR;
      foreach ($data as $item) {
         $xml .= '   <cat_item>'.CR;
         foreach ($struktur as $k => $v) {
            $xml .= '      <'.$v->Field.'><![CDATA['.str_replace(['<![CDATA[','// ]]>'], '', $item->{$v->Field}).']]></'.$v->Field.'>'.CR;
         }
         $xml .= '   </cat_item>'.CR;
      }
      $xml .= '</categories>'.CR;

      header('Content-type: text/xml');
      header('Content-Disposition: attachment; filename="KATEGORIEN_XML_'.date('d_m_Y').'.xml"');
      echo $xml;
      exit();
   }

   /* ********** Import-Funktionen ********** */
   // Import für 1A-Shop
   public function einsashopImport($cronjob_url, $cronjob_overwrite, $cronjob_images, $haendler_id = 0) {
      $statistik = [];

      // Alte Einträge löschen
      $this->db->query("DELETE FROM #__cron_articles WHERE cronjob_id IN (SELECT id FROM #__cronjobs WHERE haendler_id = $haendler_id AND done = 'n')");
      $this->db->query("UPDATE #__cronjobs SET done = 'y', status = 'Beendet durch neuen Cronjob' WHERE haendler_id = $haendler_id AND done = 'n'");

      // Neuer Eintrag
      $this->db->query("INSERT INTO #__cronjobs SET haendler_id = $haendler_id, import_url = '$cronjob_url', import_images = '$cronjob_images', overwrite = '$cronjob_overwrite', statistik = '".json_encode($statistik)."'");
      echo json_encode(['status' => 'ok', 'msg' => 'Cronjob wurde aktiviert']);
      exit;
   }

   public function importCronfile($file, $json, $overwrite, $cronjob, $is_cli, $haendler_id, $restart = 0) {
      $categorie    = Control::getKategorie();
      $mode         = 'import';
      $lang         = 'deu';

      $config       = (object)$json[0];
      $includefile  = ADMIN_PATH.'/classes/import_export/'.$config->file;
      $start        = 0;

      if ($config->csv_head == 'y') {
         $start = 1;
      }

      if (!file_exists($includefile)) {
         // Fehler
         echo "<script>parent.Royalart.uploadDone('error', 'Datei konnte nicht gelesen werden');</script>";
         exit;
      }

      include $includefile;
      return true;
   }

   public function importArtikel($shop, $overwrite = 'y', $catname = 'y', $haendler_id = 0) {
      $cronjob = 0;
//      $shop               = $this->params->postString('param1');
      $overwrite          = $this->params->postCheckbox('param2');   // overwrite
      $kategorie_neu      = $this->params->postCheckbox('param3');   // kategorien
      // Wenn 'y' Bilder von anderen Shops downloaden.
      $this->picdownloads = $this->params->postCheckbox('param4');

      // Portal zusätzlich
      $h_dir = SHOP_PATH.'/downloads/checkfiles';

      if (defined('CONF_MODULE_PORTAL')) {
         $haendler_nr = 0;

         if ($haendler_id == 0) {
            $haendler_id = $this->params->user_id;
         }

         $haendler_nr = $this->db->querySingleValue("SELECT haendler_nr FROM #__haendler WHERE user_id = $haendler_id");
      }

      if ($_FILES['file']['error'] > 0) {
         exit(json_encode(['status' => 'error',  'msg' => 'Datei für Import zu groß. Setzen Sie sich mit Ihrem Provider in Verbindung.']));
      }

      // Namen aus $_FILES lesen
      $filename  = ($_FILES['file']['name']);
      $uploaddir = SHOP_PATH.'/tmp/';
      $file      = $uploaddir.$filename;
      move_uploaded_file($_FILES['file']['tmp_name'], $file);

      if (file_exists($file)) {
         // BOM entfernen bei UTF-8
         $inhalt = file_get_contents($file);

         // UTF8 BOM entfernen
         if(substr($inhalt, 0, 3) == pack("CCC", 0xef, 0xbb, 0xbf)) {
            $inhalt = substr($inhalt, 3);
            file_put_contents($file, $inhalt);
         }

         // Wenn /cronjob.php vorhanden, nur Datei einlesen. Bilder per Cronjob laden
         if ($this->picdownloads == 'y' && is_file(SHOP_PATH.'/cronjob.php')) {
            $this->db->query("DELETE FROM #__cron_articles WHERE cronjob_id IN (SELECT id FROM #__cronjobs WHERE haendler_id = $haendler_id AND done = 'n')");
            $this->db->query("UPDATE #__cronjobs SET done = 'y', status = 'Beendet durch neuen Cronjob' WHERE haendler_id = $haendler_id");
            $statistik = [];
            $this->db->query("INSERT INTO #__cronjobs SET type = 'load_images', start = CURRENT_TIME, import_images = 'y', status = 'Artikel-Import', statistik = '".json_encode($statistik)."', haendler_id = $haendler_id");
            $cronjob = $this->db->getNewId();
         }

         if ($shop == 'portal') {
             $this->_importArticles($file, 'flow', 'xml', $overwrite, $catname, $cronjob, $haendler_id);
         }

         else if ($shop == 'flow_xml') {
            $this->_importArticles($file, 'flow', 'xml', $overwrite, $catname, $cronjob, $haendler_id);
         }

         else if ($shop == 'flow_csv_brutto') {
            $this->_importArticles($file, 'flow_brutto', 'csv', $overwrite, $catname, $cronjob, $haendler_id);
         }

         else if ($shop == 'flow_csv_netto') {
            $this->_importArticles($file, 'flow', 'csv', $overwrite, $catname, $cronjob, $haendler_id);
         }

         // Aus Portal / Verkäufer
         else if ($shop == 'haendler_csv') {
            $this->_importArticles($file, 'flow', 'csv', 'n', $catname, $cronjob, $haendler_id);
         }

         // Händler Sonstige Shopsoftware
         else if ($shop == 'haendler_csv_overwrite') {
            $this->allArticlesDeleteHaendler($haendler_id, false);
            $this->_importArticles($file, 'flow', 'csv', 'y', $catname, $cronjob, $haendler_id);
         }

         else if ($shop == 'haendler_csv_brutto') {
            $this->_importArticles($file, 'flow_brutto', 'csv', 'n', $catname, $cronjob, $haendler_id);
         }

         else if ($shop == 'haendler_csv_brutto_overwrite') {
            $this->allArticlesDeleteHaendler($haendler_id, false);
            $this->_importArticles($file, 'flow_brutto', 'csv', 'y', $catname, $cronjob, $haendler_id);
         }
         // Ende Aus Portal / Verkäufer

         else if ($shop == 'gx2') {
            $this->_importArticles($file, 'gx2', 'csv', 'y', $catname, $cronjob, $haendler_id);
         }

         // Aus Portal / Verkäufer
         else if ($shop == 'haendler_gx2') {
            $this->_importArticles($file, 'gx2', 'csv', 'n', $catname, $cronjob, $haendler_id);
         }

         // Portal - Gambio/XT-Shopsoftware
         else if ($shop == 'haendler_gx2_overwrite') {
            $this->allArticlesDeleteHaendler($haendler_id, false);
            $this->_importArticles($file, 'gx2', 'csv', 'y', $catname, $cronjob, $haendler_id);
         }
         // Ende Aus Portal / Verkäufer

         else if ($shop == 'dyn') {
            $this->_importArticles($file, 'dyn', 'csv', $overwrite, $catname, $cronjob, $haendler_id);
         }
         else if ($shop == 'newsletter') {
            $this->_importUsers($file, 'newsletter', 'csv', $overwrite, $catname, $cronjob, $haendler_id);
         }

         else if ($shop == 'kunden') {
            $this->_importUsers($file, 'kunden', 'csv', $overwrite, $catname, $cronjob, $haendler_id);
         }

         // 13.03.2019
         else if ($shop == 'lager') {
            $this->_importArticles($file, 'lager', 'csv', $overwrite, $catname, $cronjob, $haendler_id);
         }

         else if ($shop == 'lager_name') {
             $this->_importArticles($file, 'lager_name', 'csv', $overwrite, $catname, $cronjob, $haendler_id);
         }
         else if ($shop == 'lager_artnr') {
             $this->_importArticles($file, 'lager_artnr', 'csv', $overwrite, $catname, $cronjob, $haendler_id);
         }

      }

      else {
         echo json_encode(['status' => 'error', 'msg' => 'Datei konnte nicht gelesen werden']);
      }

      exit;
   }

   private function _importArticles($file, $shop, $format, $overwrite, $catname, $cronjob, $haendler_id) {
      $categorie   = Control::getKategorie();
      $json        = null;
      $trenner     = ',';
      $worttrenner = '"';
      $lang        = 'deu';

//      if (defined('CONF_USE_HTACCESS') && CONF_USE_HTACCESS > 0) {
//         $shopurl = str_replace('/index.php', '', $shopurl);
//      }

      if ($shop == 'flow' || $shop == 'flow_brutto') {
         $json = [
            [
               // Allgemein
               'name'          => '',
               'file'          => 'flow_'.$format.'.inc.php',
               'format'        => $format,
               'html'          => 'html',
               'csv_head'      => 'y',
               'trenner'       => ';',
               'worttrenner'   => '',

               // Export
               'all'           => 'all',
               'exp_preis'     => 'netto',
               'exp_stellen'   => '9',
               'exp_separator' => '.',
               'exp_sep1000'   => '',
               'outfile'       => 'portal',
               'charset_out'   => 'UTF-8',

               // Import
               'imp_preis'     => ($shop == 'flow' ? 'netto' : 'brutto'),
               'charset_in'    => 'UTF-8',
               'image_load'    => 'n',
               'image_server'  => '',
               'imp_merkmal1'  => '',
               'imp_merkmal2'  => ''
            ]
         ];
      }

      else if ($shop == 'portal') {
         $json = [
            [
               // Allgemein
               'name'          => '',
               'file'          => 'portal_xml.inc.php',
               'format'        => 'xml',
               'html'          => 'html',
               'csv_head'      => 'y',
               'trenner'       => ';',
               'worttrenner'   => '"',

               // Export
               'all'           => 'all',
               'exp_preis'     => 'netto',
               'exp_stellen'   => '9',
               'exp_separator' => '.',
               'exp_sep1000'   => '',
               'outfile'       => 'KanpaiClassic',
               'charset_out'   => 'UTF-8',

               // Import
               'imp_preis'     => 'netto',
               'charset_in'    => 'UTF-8',
               'image_load'    => 'n',
               'image_server'  => '',
               'imp_merkmal1'  => 'Größe',
               'imp_merkmal2'  => 'Farbe'
            ]
         ];
      }

      else if ($shop == 'gx2') {
         $json = [
            [
               // Allgemein
               'name'          => '',
               'file'          => 'gx2_csv.inc.php',
               'format'        => $format,
               'html'          => 'html',
               'csv_head'      => 'y',
               'trenner'       => '|',
               'worttrenner'   => '',

               // Export
               'all'           => 'all',
               'exp_preis'     => 'netto',
               'exp_stellen'   => '9',
               'exp_separator' => '.',
               'exp_sep1000'   => '',
               'outfile'       => 'gambio',
               'charset_out'   => 'UTF-8',

               // Import
               'imp_preis'     => 'netto',
               'charset_in'    => 'UTF-8',
               'image_load'    => 'n',
               'image_server'  => '',
               'imp_merkmal1'  => '',
               'imp_merkmal2'  => ''
            ]
         ];
      }

      else if ($shop == 'lager') {
         $json = [
            [
               // Allgemein
               'name'          => '',
               'file'          => 'lager_csv.inc.php',
               'format'        => $format,
               'html'          => 'html',
               'csv_head'      => 'y',
               'trenner'       => ';',
               'worttrenner'   => '"',

               // Import
               'imp_preis'     => 'brutto',
               'charset_in'    => 'UTF-8',
               'image_load'    => 'n',
               'image_server'  => '',
               'imp_merkmal1'  => '',
               'imp_merkmal2'  => ''
            ]
         ];
      }

      else if ($shop == 'lager_name') {

          $json = [
             [
                // Allgemein
                'name'          => '',
                'file'          => 'lagername_csv.inc.php',
                'format'        => $format,
                'html'          => 'html',
                'csv_head'      => 'y',
                'trenner'       => ';',
                'worttrenner'   => '"',

                // Import
                'imp_preis'     => 'brutto',
                'charset_in'    => 'UTF-8',
                'image_load'    => 'n',
                'image_server'  => '',
                'imp_merkmal1'  => '',
                'imp_merkmal2'  => ''
             ]
          ];
      }


      else if ($shop == 'lager_artnr') {

          $json = [
             [
                // Allgemein
                'name'          => '',
                'file'          => 'lagerartnr_csv.inc.php',
                'format'        => $format,
                'html'          => 'html',
                'csv_head'      => 'y',
                'trenner'       => ';',
                'worttrenner'   => '"',

                // Import
                'imp_preis'     => 'brutto',
                'charset_in'    => 'UTF-8',
                'image_load'    => 'n',
                'image_server'  => '',
                'imp_merkmal1'  => '',
                'imp_merkmal2'  => ''
             ]
          ];
      }


      else if (file_exists(ADMIN_PATH.'/config.json')) {
         $datei = file_get_contents(ASMIN_PATH.'/config.json');
         $json = json_decode($datei);
      }

      // Default verwenden
      if ($json == null) {

          $json = [
            [
               // Allgemein
               'name'          => '',
               'file'          => 'xtc_csv.inc.php',
               'format'        => 'csv',
               'html'          => 'text',
               'csv_head'      => 'y',
               'trenner'       => ';',
               'worttrenner'   => '',

               // Export
               'all'           => 'all',
               'exp_preis'     => 'brutto',
               'exp_stellen'   => '4',
               'exp_separator' => '.',
               'exp_sep1000'   => '',
               'outfile'       => 'xtc',
               'charset_out'   => 'UTF-8',

               // Import
               'imp_preis'     => 'netto',
               'charset_in'    => 'UTF-8',
               'image_load'    => 'n',
               'image_server'  => '',
               'imp_merkmal1'  => 'Größe',
               'imp_merkmal2'  => 'Farbe'
            ]
         ];
      }

      $config      = (object)$json[0];
      $includefile = ADMIN_PATH.'/classes/import_export/'.$config->file;
      $mode        = 'import';
      $start       = 0;

      if ($config->csv_head == 'y') {
         $start = 1;
      }

      if (!file_exists($includefile)) {
         // Fehler
         echo "<script>parent.Royalart.uploadDone('error', 'Datei konnte nicht gelesen werden');</script>";
         exit;
      }

      include $includefile;
   }

   private function _importUsers($file, $shop, $format, $overwrite, $catname, $cronjob, $haendler_id) {
      $categorie = Control::getKategorie();
      $json = null;
      $trenner = ',';
      $worttrenner = '"';
//      $shopurl = str_replace('/admin', '', $this->params->baseurl);
      $lang = 'deu';

//      if (defined('CONF_USE_HTACCESS') && CONF_USE_HTACCESS > 0) {
//         $shopurl = str_replace('/index.php', '', $shopurl);
//      }

      if ($shop == 'newsletter') {
         $json = [
            [
               // Allgemein
               'name'          => '',
               'file'          => 'newsletter_'.$format.'.inc.php',
               'format'        => $format,
               'html'          => 'html',
               'csv_head'      => 'n',
               'trenner'       => ';',
               'worttrenner'   => '"',

               // Export
               'all'           => 'all',
               'exp_preis'     => 'netto',
               'exp_stellen'   => '9',
               'exp_separator' => '.',
               'exp_sep1000'   => '',
               'outfile'       => 'newsletter',
               'charset_out'   => 'UTF-8',

               // Import
               'imp_preis'     => 'netto',
               'charset_in'    => 'UTF-8',
               'image_load'    => 'n',
               'image_server'  => '',
               'imp_merkmal1'  => '',
               'imp_merkmal2'  => ''
            ]
         ];
      }

      if ($shop == 'kunden') {
         $json = [
            [
               // Allgemein
               'name'          => '',
               'file'          => 'kunden_csv.inc.php',
               'format'        => 'csv',
               'html'          => 'html',
               'csv_head'      => 'y',
               'trenner'       => ';',
               'worttrenner'   => '',

               // Import
               'imp_preis'     => 'netto',
               'charset_in'    => 'UTF-8',
               'image_load'    => 'n',
               'image_server'  => '',
               'imp_merkmal1'  => '',
               'imp_merkmal2'  => ''
            ]
         ];
      }

      $config = (object)$json[0];
      $includefile = ADMIN_PATH.'/classes/import_export/'.$config->file;
      $mode = 'import';
      $start = 0;
      if ($config->csv_head == 'y') {
         $start = 1;
      }

      if (!file_exists($includefile)) {
         // Fehler
         echo "<script>parent.Royalart.uploadDone('error', 'Datei konnte nicht gelesen werden');</script>";
         exit;
      }

      include $includefile;
   }

   // Kategorien importieren (aus Kategorien-Liste)
   // 07.02.2019
   public function importCategoriesXML() {
      // Namen aus $_FILES lesen
      $temp      = array_keys($_FILES);
      $tempname  = $temp[0];
      $uploaddir = SHOP_PATH.'/tmp/';
      $file      = $uploaddir.$tempname;

      move_uploaded_file($_FILES[$tempname]['tmp_name'], $file);

      if (file_exists($file)) {
         // BOM entfernen bei UTF-8
         $inhalt = file_get_contents($file);

         if(substr($inhalt, 0, 3) == pack("CCC", 0xef, 0xbb, 0xbf)) {
            $inhalt = substr($inhalt, 3);
            file_put_contents($file, $inhalt);
         }
      }

      libxml_use_internal_errors(true);
      $xml       = simplexml_load_file($file);

      if (isset($xml->cat_item)) {
         $cat_items = $xml->cat_item;
         $struktur  = $this->db->queryAllObjects("DESCRIBE #__categories");

         if (count($cat_items) > 0) {
            $this->db->query("TRUNCATE TABLE #__categories");

            foreach ($cat_items as $cat_item) {
               $id = 0;
               $parent_id = 0;
               $active = 'n';
               $network_id = 0;
               $level = 0;
               $ordered = 0;
               $childs = 0;
               $sql = '';

               foreach ($struktur as $k => $v) {
                  $field = $v->Field;

                  if (isset($cat_item->{$field})) {
                     if ($field == 'id') { $id = $cat_item->{$field}; }
                     else if ($field == 'parent_id') { $parent_id = $cat_item->{$field}; }
                     else if ($field == 'active') { $active = $cat_item->{$field}; }
                     else if ($field == 'network_id') { $network_id = $cat_item->{$field}; }
                     else if ($field == 'level') { $level = $cat_item->{$field}; }
                     else if ($field == 'ordered') { $ordered = $cat_item->{$field}; }
                     else if ($field == 'childs') { $childs = $cat_item->{$field}; }
                     else { $sql .= ", $field = '".$this->db->escape($cat_item->{$field})."'"; }
                  }
               }

               $this->db->query("INSERT INTO #__categories SET id = $id, parent_id = $parent_id, active = '$active', network_id = $network_id, level = $level, ordered = $ordered, childs = $childs $sql");
            }

            echo json_encode(['status' => 'ok', 'msg' => 'Datei erfolgreich importiert']);
         }
      }

      else {
         echo json_encode(['status' => 'error', 'msg' => 'Fehler beim Import']);
      }

      exit;
   }

   // Fileupload und Methode für Import anhand der Art wählen (xml, csv oder xtc)
   public function allArticlesDelete($haendler_id = 0) {
      if ($haendler_id != 0) {
         return allArticlesDeleteHaendler($haendler_id);
      }

      $this->db->query("TRUNCATE table #__articles");
      $this->db->query("TRUNCATE table #__articles_info");
      $this->db->query("TRUNCATE table #__articles_to_googlecats");
      $this->db->query("TRUNCATE table #__article_to_cats");

      $test = $this->db->queryAllObjects("SHOW tables like '#__articles_to_ebaycats'");

      if ($test) {
         $this->db->query("TRUNCATE table #__articles_to_ebaycats");
      }

      $this->db->query("TRUNCATE table #__articles_seo");
      $this->db->query("TRUNCATE table #__articles_zubehoer");
      $this->db->query("TRUNCATE table #__articles_zubehoer_lang");
      $this->db->query("TRUNCATE table #__articles_aehnliche");
      $this->db->query("TRUNCATE table #__articles_aehnliche_lang");
      $this->db->query("TRUNCATE table #__articles_images");
      $this->db->query("DELETE FROM #__cron_articles WHERE cronjob_id IN (SELECT id FROM #__cronjobs WHERE haendler_id = $haendler_id AND done = 'n')");
      $this->db->query("UPDATE #__cronjobs SET done = 'y', status = 'Beendet durch Artikel löschen' WHERE haendler_id = $haendler_id");

      $dir = SHOP_PATH.'/'.CONF_PICT_PATH;
      $hdl = opendir($dir);

      while ($datei = readdir($hdl)) {
         if (is_file($dir.$datei) && !in_array($datei, ['.', '..', 'index.html'])) {
            unlink($dir.$datei);
         }
      }

      $dir = SHOP_PATH.'/'.CONF_PICT_PATH.'original/';
      $hdl = opendir($dir);

      while($datei = readdir($hdl)) {
         if (is_file($dir.$datei) && !in_array($datei, ['.', '..', 'index.html', ''])) {
            unlink($dir.$datei);
         }
      }

      $dir      = SHOP_PATH.'/downloads/';
      $startdir = $dir;
      $this->_deldir($dir, $startdir);

      echo json_encode(['status' => 'ok', 'msg' => 'Artikel wurden gelöscht']);
   }

   public function allArticlesDeleteHaendler($haendler_id = 0, $del_cron = true) {
      if ($haendler_id == 0) {
         return;
      }

      $data = $this->db->queryAllObjects("SELECT id, pict01, pict02, pict03, pict04, pict05, pict06, pict07, pict08, pict09, pict10, pict11 FROM #__articles_info WHERE haendler_id = $haendler_id");

      for ($i = 0; $i < count($data); $i++) {
         for ($p = 1; $p < 12; $p++) {
            $pic = $data[$i]->{'pict'.sprintf('%02d', $p)};

            if ($pic != '' && $pic != 'nopic.png' && $pic != 'nopic.pnp' && strpos($pic, 'http://') === false && strpos($pic, 'https://') === false) {
               @unlink (SHOP_PATH.'/'.CONF_PICT_PATH.str_replace('.jpg', '', $pic).'*');
               @unlink (SHOP_PATH.'/'.CONF_PICT_PATH.'original/'.str_replace('.jpg', '', $pic).'*');
            }
         }

         $this->db->query("DELETE FROM #__articles WHERE parent_id = ".$data[$i]->id);
         $this->db->query("DELETE FROM #__articles_info WHERE id = ".$data[$i]->id);
         $this->db->query("DELETE FROM #__articles_to_googlecats WHERE parent_id = ".$data[$i]->id);
         $this->db->query("DELETE FROM #__article_to_cats WHERE parent_id = ".$data[$i]->id);

         $test = $this->db->queryAllObjects("SHOW tables like '#__articles_to_ebaycats'");
         if ($test) {
            $this->db->query("DELETE FROM #__articles_to_ebaycats WHERE article_id = ".$data[$i]->id);
         }

         $test = $this->db->queryAllObjects("SHOW tables like '#__articles_to_dawanda'");
         if ($test) {
            $this->db->query("DELETE FROM #__articles_to_dawanda WHERE article_id = ".$data[$i]->id);
         }

         $this->db->query("DELETE FROM #__articles_seo WHERE parent_id = ".$data[$i]->id);
         $this->db->query("DELETE FROM #__articles_zubehoer WHERE parent_id = ".$data[$i]->id);
         $this->db->query("DELETE FROM #__articles_zubehoer_lang WHERE parent_id = ".$data[$i]->id);

         $this->db->query("DELETE FROM #__cron_articles WHERE cronjob_id IN (SELECT id FROM #__cronjobs WHERE haendler_id = $haendler_id AND done = 'n')");

         if ($del_cron) {
            $this->db->query("UPDATE #__cronjobs SET done = 'y', status = 'Beendet durch Artikel löschen' WHERE haendler_id = $haendler_id");
         }
      }

      if ($this->params->isAjax) {
         echo json_encode(['status' => 'ok', 'msg' => 'Artikel wurden gelöscht']);
      }
      return;
   }

   // Artikel-Details: Google-Kategorion Options
   public function getGoogleCatOptions($liste) {
      $level    = 0;
      $new_list = '';
      $parent   = 0;
      $html     = '<table>';

      if ($liste != '') {
         $list_arr = explode(';', $liste);

         for ($i = 0; $i < count($list_arr); $i++) {
            $id = $list_arr[$i];
            $html .= '   <tr>'.CR;
            $html .= '      <td>'.CR;
            $html .= '         <div class="google_sel_div txt_inp">'.CR;
            $html .= '            <span class="selectbox30">'.CR;
            $html .= '               <select autocomplete="off" class="googleselect" id="googleselect_'.$i.'" onchange="Google.change('.$level.');">'.CR;

            if ($i == 0) {
               $new_list = $id;

               if ($liste != '') {
                  if ($liste == '-1') {
                     $html .= '                  <option style="color:#ee0000;" value="-1 selected="selected">Entfernen</option>';
                     $html .= '                  <option value="0">Keine Kategorie</option>';
                  }

                  else if ($liste == '0'){
                     $html .= '                  <option style="color:#ee0000;" value="-1">Entfernen</option>'.CR;
                     $html .= '                  <option value="0" selected="selected">Keine Kategorie</option>'.CR;
                  }

                  else {
                     $html .= '                  <option style="color:#ee0000;" value="-1">Entfernen</option>'.CR;
                     $html .= '                  <option value="0">Keine Kategorie</option>'.CR;
                  }
               }
            }

            else {
               $new_list .= ';'.$id;
            }

            $level++;
            $sql = "SELECT id, name FROM #__google_cats WHERE level = $i AND parent = $parent";
            $this->db->query($sql);

            while ($temp = $this->db->getObject()) {
               if ($temp) {
                  if ($temp->id == $id) {
                     $parent = $temp->id;
                     $html .= '                  <option value="'.$temp->id.'" selected="selected">'.$temp->name.'</option>';
                  }

                  else {
                     $html .= '                  <option value="'.$temp->id.'">'.$temp->name.'</option>';
                  }
               }
            }

            $html .= '               </select>'.CR;
            $html .= '            </span>'.CR;
            $html .= '         </div>'.CR;
            $html .= '      </td>'.CR;
            $html .= '   </tr>'.CR;
         }
      }

      $sql = "SELECT id, name FROM #__google_cats WHERE level = $level AND parent = $parent";
      $anz = $this->db->query($sql);

      if ($anz > 0) {
         $html .= '   <tr>'.CR;
         $html .= '      <td>'.CR;
         $html .= '         <div class="google_sel_div txt_inp">'.CR;
         $html .= '            <span class="selectbox30">'.CR;
         $html .= '               <select autocomplete="off" class="googleselect" id="googleselect_'.$level.'" onchange="Google.change('.$level.');">'.CR;
         $html .= '                  <option value="0" selected="selected">Bitte wählen ...</option>'.CR;

         while ($temp = $this->db->getObject()) {
            if ($temp) {
               $html .= '                  <option value="'.$temp->id.'">'.$temp->name.'</option>';
            }
         }
         $html .= '               </select>'.CR;
         $html .= '            </span>'.CR;
         $html .= '         </div>'.CR;
         $html .= '      </td>'.CR;
         $html .= '   </td>'.CR;
      }

      $html .= '</table>'.CR;
      $html .= '<input type="hidden" name="g_cats" id="g_cats" value="'.$new_list.'" />';

      return $html;
   }

   // Artikel/Details - Google-Shopping
   public function saveList($parent) {
      $g_cats    = $this->params->postString('g_cats');
      if ($g_cats == '0') {
         $g_cats = '';
      }
      $g_zustand = $this->params->postString('g_zustand');

//      if ($g_cats != '' && $g_cats != '0' && $g_cats != '-1') {
      if ($g_cats != '-1') {
         $sql = "INSERT INTO #__articles_to_googlecats SET parent_id = $parent, categories = '$g_cats', zustand = '$g_zustand', updated = NOW()
                    ON DUPLICATE KEY UPDATE
                       categories = '$g_cats', zustand = '$g_zustand'";
         $this->db->query($sql);
      }

      else {
         $this->db->query("DELETE FROM #__articles_to_googlecats WHERE parent_id = $parent");
      }
      return;
   }

   // Wird Methode noch verwendet???
   public function saveGoogleData($parent, $gcats = '', $gzustand = '') {
      if ($gzustand != 'g') {
         $gzustand = 'n';
      }

      $sql = "INSERT INTO #__articles_to_googlecats SET parent_id = $parent, categories = '$gcats', zustand = '$gzustand', updated = NOW()
                  ON DUPLICATE KEY UPDATE
                     categories = '$gcats', zustand = '$gzustand'";
      $this->db->query($sql);
      return;
   }

   // Google-Shop Export
   public function googleExport() {
      $export   = $this->params->postString('export');
      $land     = $this->params->postString('land');
      $waehrung = $this->params->postString('waehrung');

      // Nicht verwendet
      if ($export == 'xml') {
         $out  = '<?xml version="1.0" encoding="UTF-8"?>'.CR.CR;
         $out .= '<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">'.CR;
         $out .= '<channel>'.CR;
         $out .= '<title>'.$this->params->firma['shop_name'].'</title>'.CR;
         $out .= '<link>'.SHOP_URL.'</link>'.CR;
         $out .= '<description>'.html_entity_decode($this->params->firma['description']).'</description>'.CR.CR;

         $lang = $this->params->selected_lang;

         $sql = "SELECT g.parent_id, g.categories, g.zustand, g.google_id,
                        i.marke, i.name_$lang AS name, i.desc_$lang AS `desc`, i.image AS pict01, '' AS i.pict02, i.steuersatz, i.versand_preis, i.gewicht,
                        a.gtin, a.mpn, a.netto, a.angebot, a.angebot_active, a.art_nr, a.menge,
                        c.name_$lang AS catname
                    FROM #__articles_info AS i
                 LEFT JOIN #__articles AS a
                    ON i.id = a.parent_id
                 LEFT JOIN #__article_to_cats AS ac
                    ON ac.parent_id = i.id
                 LEFT JOIN #__categories AS c
                    ON ac.cat_id = c.id
                 LEFT JOIN #__articles_to_googlecats AS g
                    ON g.parent_id = i.id
                 WHERE g.categories != ''
                    AND a.sort = 1
                    AND a.online = 'y'
                    AND ac.sort = 0";
         $data = $this->db->queryAllObjects($sql);

         for ($i = 0; $i < (is_array($data) ? count($data) : 0); $i++) {
//            $id      = $data[$i]->article_id;
            $id      = $data[$i]->google_id;
            $name    = $data[$i]->name;
            $art_nr  = $data[$i]->art_nr;

            $desc    = $this->db->escape($data[$i]->desc);
            $desc    = str_replace('&euro;', '€', $desc);
            $desc    = str_replace('<br', ' <br', $desc);
            $desc    = str_replace('<p', ' <p', $desc);
            $desc    = trim(Helper::truncate(strip_tags($desc), 500));

            $bild1   = SHOP_URL.'/'.CONF_PICT_PATH.$data[$i]->pict01.'.jpg';
//            $bild2   = SHOP_URL.'/'.CONF_PICT_PATH.$data[$i]->pict02.'.jpg';
            $bild2   = SHOP_URL.'/'.CONF_PICT_PATH.$this->db->QuerySingleValue("SELECT image FROM #__articles_images WHERE parent_id = $data[$i]->parent_id ORDER BY sort");

            $zustand = 'neu';

            if ($data[$i]->zustand == 'g') {
               $zustand = 'gebraucht';
            }

            $marke    = $data[$i]->marke;
            $gtin     = $data[$i]->gtin;
            $mpn      = $data[$i]->mpn;

            $tpreis   = $data[$i]->netto;
            $tangebot = $data[$i]->angebot;
            $tversand = $data[$i]->versand_preis;
            $lager    = 'auf Lager';

            if ($data[$i]->menge < 1) {
               $lager = 'nicht '.$lager;
            }

            // Kleingewerbe
            if ($this->params->firma['kleingewerbe'] == 'y') {
               $preis   = $tpreis;
               $angebot = $tangebot;
               $versand = $tversand;
            }

            else {
               // Ust. aktiv, Steuer berechnen
               if ($this->params->firma['tax_active'] == 'y') {
                  $preis   = $tpreis * (1 + $this->params->firma['tax'.$data[$i]->steuersatz] / 100);
                  $angebot = $tangebot * (1 + $this->params->firma['tax'.$data[$i]->steuersatz] / 100);
                  $versand = $tversand * (1 + $this->params->firma['tax1'] / 100);
               }
               else {
                  $preis   = $tpreis;
                  $angebot = $tangebot;
                  $versand = $tversand;
               }
            }

            $preis   = sprintf('%.2f', round((float)$preis, 2));
            $angebot = sprintf('%.2f', round((float)$angebot, 2));
            $versand = sprintf('%.2f', round((float)$versand, 2));

            $gewicht = sprintf('%.2f', round((float)$data[$i]->gewicht, 2));

            $catname = $data[$i]->catname;

            $cats    = str_replace(' > ', ' &gt; ', $this->_getCatNames($data[$i]->categories));
            $cats    = str_replace(' & ', ' &amp; ', $cats);

            $out .= '<item>'.CR;
            $out .= '<title>'.$name.'</title>'.CR;
            $out .= '<description>'.$desc.'</description>'.CR;
            $out .= '<link>'.SHOP_URL_IDX.'/deu_'.$id.'/'.$name.'</link>'.CR;
            $out .= '<g:image_link>'.$bild1.'</g:image_link>'.CR;
            $out .= '<g:additional_image_link>'.$bild2.'</g:additional_image_link>'.CR;
            $out .= '<g:google_product_category>'.$cats.'</g:google_product_category>'.CR;
            $out .= '<g:product_type>'.$catname.'</g:product_type>'.CR;
            $out .= '<g:id>'.$art_nr.'</g:id>'.CR;
            $out .= '<g:condition>'.$zustand.'</g:condition>'.CR;
            $out .= '<g:availability>'.$lager.'</g:availability>'.CR;
            $out .= '<g:brand>'.$marke.'</g:brand>'.CR;
            $out .= '<g:gtin>'.$gtin.'</g:gtin>'.CR;
            $out .= '<g:mpn>'.$mpn.'</g:mpn>'.CR;
            $out .= '<g:price>'.$preis.' '.$waehrung.'</g:price>'.CR;
            if ($data[$i]->angebot_active == 'y') {
               $out .= '<g:sale_price>'.$angebot.' '.$waehrung.'</g:sale_price>'.CR;
            }
            $out .= '<g:shipping>'.CR;
            $out .= '   <g:country>'.$land.'</g:country>'.CR;
            $out .= '   <g:service>Standard</g:service>'.CR;
            $out .= '   <g:price>'.$versand.' '.$waehrung.'</g:price>'.CR;
            $out .= '</g:shipping>'.CR;
            $out .= '<g:shipping_weight>'.$gewicht.' kg</g:shipping_weight>'.CR;
            $out .= '</item>'.CR.CR;
         }

         $out .= '</channel>'.CR;
         $out .= '</rss>'.CR;

         header('Content-type: text/xml');
         header('Content-Disposition: attachment; filename="GOOGLE_'.date('d_m_Y').'.xml"');
         echo $out;
         exit();
      }

      else {
         $csv  = '';
         $csvt = '';
         $lang = $this->params->selected_lang;
         $data = $this->db->queryAllObjects("SELECT g.parent_id as parent_id, g.categories, g.zustand,
                                                    i.childs, i.marke, i.name_$lang AS name, i.desc_$lang AS `desc`, i.image AS pict01, '' AS pict02, i.steuersatz, i.versand_preis, i.gewicht,
                                                    a.id AS article_id, a.gtin, a.mpn, a.netto, a.angebot, a.angebot_active, a.art_nr, a.menge,
                                                    c.name_$lang AS catname
                                                FROM #__articles_info AS i
                                             LEFT JOIN #__articles AS a
                                                ON i.id = a.parent_id
                                             LEFT JOIN #__article_to_cats AS ac
                                                ON ac.parent_id = i.id
                                             LEFT JOIN #__categories AS c
                                                ON ac.cat_id = c.id
                                             LEFT JOIN #__articles_to_googlecats AS g
                                                ON g.parent_id = i.id
                                             WHERE g.categories != ''
                                                AND a.sort = 1
                                                AND a.online = 'y'
                                                AND ac.sort = 0");

         if ($data) {
            // 13 Einträge
            $csvt .= '"id";"title";"description";"link";"image_link";';
            $csvt .= '"condition";"price";"availability";"brand";"gtin";';
            $csvt .= '"mpn";"google product category";"shipping(price)"'."\r\n";

            foreach ($data as $d) {





               $varianten = $this->db->queryAllObjects("SELECT a.id, a.gtin, a.mpn, a.netto, a.angebot, a.angebot_active, a.art_nr, a.menge,
                                                               w1.wert_$lang AS wert1, w2.wert_$lang AS wert2, m1.merkmal_$lang AS merkmal1, m2.merkmal_$lang AS merkmal2, a.startbild as startbild
                                                           FROM #__articles AS a
                                                        LEFT JOIN #__werte as w1
                                                           ON a.wert1 = w1.id
                                                        LEFT JOIN #__werte as w2
                                                           ON a.wert2 = w2.id
                                                        LEFT JOIN #__merkmale as m1
                                                           ON a.merkmal1 = m1.id
                                                        LEFT JOIN #__merkmale as m2
                                                           ON a.merkmal2 = m2.id
                                                        WHERE parent_id = $d->parent_id
                                                           AND a.online = 'y'
                                                        ORDER BY a.sort");


               $parent_id = $d->parent_id;

               for ($i = 0; $i < count($varianten); $i++) {

                   $v = $varianten[$i];

                   $startbild = $v->startbild;

                   $imagename = "";
                   if($startbild == 1){
                       $imagename = $d->pict01;
                   }else if($startbild > 1){ // Bilder über 1 hinaus werden in anderer Tabelle gespeichert
                       $imagename = $this->db_extern->querySingleValue("SELECT image FROM #__articles_images WHERE parent_id = '".$parent_id."' and sort = '".($startbild-1)."'");
                   }



                  $name     = $d->name;
                  $merkmale = '';

                  if ($v->wert1 != '' && $v->merkmal1) {
                     $merkmale = ' '.$v->merkmal1.' '.$v->wert1.',';
                  }

                  if ($v->wert2 != '' && $v->merkmal2) {
                     $merkmale = ' '.$v->merkmal2.' '.$v->wert2;
                  }

                  $merkmale = ltrim($merkmale, ',');
                  $name    .= $merkmale;
                  $bild     = SHOP_URL.'/'.CONF_PICT_PATH.$imagename.'.jpg';
                  $tpreis   = (float)$v->netto;
                  $tangebot = (float)$v->angebot;
                  $versand  = (float)$d->versand_preis;
                  $lager    = ((float)$v->menge > 0 ? 'Auf Lager' : '');

                  $preis    = ($v->angebot_active == 'y' ? $tangebot : $tpreis);

                  // Ust. aktiv, Steuer berechnen
                  if ($this->params->firma['tax_active'] == 'y') {
                     $preis   = $preis * (1 + $this->params->firma['tax'.$d->steuersatz] / 100);
                     $versand = $versand * (1 + $this->params->firma['tax1'] / 100);
                  }

                  $cats = explode(';', $d->categories);
                  $gcat = array_pop($cats);
//                  $google_cat = $gcat;
//var_dump($google_cat); exit;
                  // Update 65: Neue Google-Cats nach google_id
//                  if ((int)$this->params->firma['version'] > 64) {
                  $google_cat = $this->db->querySingleValue("SELECT google_id FROM #__google_cats WHERE id = $gcat");
//                  }

                  $beschreibung = str_ireplace(['[TRENNER]', '[ausklappen]'], '', $d->desc);
                  $beschreibung = str_replace(['</p>', '</div>'], ["</p>\n", "</div>\n"], $beschreibung);
                  $beschreibung = str_replace(['<br>', '<br/>', '<br />'], "\n", $beschreibung);
                  $beschreibung = strip_tags(trim(html_entity_decode($beschreibung)));

                  $csv .= '"'.$v->art_nr.'";';
                  $csv .= '"'.$name.'";';
                  $csv .= '"'.$beschreibung.'";';
                  $csv .= '"'.SHOP_URL_IDX.'/'.$v->id.'";';
                  $csv .= '"'.$bild.'";';

                  $csv .= '"Neu";';
                  $csv .= '"'.number_format($preis, 2, '.', '').' EUR";';
                  $csv .= '"'.$lager.'";';
                  $csv .= '"'.$d->marke.'";';
                  $csv .= '"'.$v->gtin.'";';

                  $csv .= '"'.$v->mpn.'";';
                  $csv .= '"'.$google_cat.'";';
                  $csv .= '"'.number_format($versand, 2, '.', '').' EUR"';

                  $csv .= "\r\n";
               }
            }
         }

         if ($csv != '') {
            $csv = $csvt.$csv;
         }

         else {
            $csv = 'keineArtikel gefunden';
         }

         header('Content-type: text/csv');
         header('Content-Disposition: attachment; filename="GOOGLE_'.date('d_m_Y').'.csv"');
         echo $csv;
         exit();
      }
   }

   // Artikel in #__article_info eintragen
   private function _insertArticle($data, $overwrite, $catname, $cronjob, $haendler_id = 0) {
//var_dump($data);
      $parent_id           = (int)$data['id'];
      $this->search_art_nr = false;
      $insert              = false;

      // ID = 0 => Neuer Artikel
      if ($parent_id == 0) {
         $insert = true;
      }

      // Test, ob Artikel in DB ist
      else {
         if ($parent_id != -1) {
            $sql = "SELECT id FROM #__articles_info WHERE id = ".$parent_id;
         }

         else {
            $sql = "SELECT i.id, a.art_nr FROM #__articles_info AS i, #__articles AS a WHERE i.id = a.parent_id AND a.art_nr = '".$data['art_nr']."'";
         }

         $test = $this->db->querySingleObject($sql);

         if ($test && (int)$test->id > 0) {
            $this->last_id = $test->id;
            $insert        = false;

            // Wenn Suche nach Artikel-ID, für Variante merken
            if (isset($test->art_nr)) {
               $this->search_art_nr = true;
            }
         }

         else {
            $insert = true;
         }
      }

      $kategorie = (int)$data['kategorie_id'];

      // Kategorie suchen, ggf anlegen (Name) bzw. Kategori mit kleinster ID
      // Kategorie nach Name
      if (($catname == 'y' || $this->params->postString('catname', 'n', 'sql') == 'y') && isset($data['kat_name']) && $data['kat_name'] != '') {

         $cat_arr = explode('=>', $data['kat_name']);
         $cat_class = Control::getKategorie();
         $kategorie = 0;
         $level = 0;



         foreach($cat_arr as $name) {
             $test = $this->db_extern->querySingleObject("SELECT id, parent_id FROM #__categories WHERE parent_id = '$kategorie' AND name_deu = '$name' AND level = $level");
             if ($test) {
                 $kategorie = $test->id;
             }else{
                 $kategorie = 0; // nicht in korrekter Zusammensetzung gefunden
             }
             $level++;
         }


         if($kategorie == 0 && count($cat_arr) == 1){
             // es ist nur eine einzelne Kategorie gesetzt, dann kann auch eine untergeordnete Kategorie verwendet werden
             $test = $this->db_extern->querySingleObject("SELECT id, parent_id FROM #__categories WHERE name_deu LIKE '$name'");

             if ($test) {
                 $kategorie = $test->id;
             }

         }


          if($kategorie == 0)
          {
              $level = 0;
              // suche die Kategorie in der korrekten Ebene oder lege sie an
             foreach($cat_arr as $name) {
                 $kategorie = $cat_class->checkCatName(trim($name), 'deu', $level, $kategorie);
                 $level++;
             }
          }


      }

      // Kategorie nach ID
      else {
         // Test, ob Kategorie-ID vorhanden ist
         if ($kategorie > 0) {
            $test = $this->db->querySingleValue("SELECT id FROM #__categories WHERE id = $kategorie");
            if ($test == null) {
               $kategorie = 0;
            }
         }

         // Kategorie-ID nicht vorhanden, Kategorie IMPORT, wenn vorhanden
         if ($kategorie < 1) {
            $test = $this->db->querySingleValue("SELECT id FROM #__categories WHERE name_deu = 'IMPORT'");

            if ($test) {
               $kategorie = $test;
            }
            // sonst min. cat_id
            else {
               $kategorie = $this->db->querySingleValue("SELECT MIN(id) FROM #__categories");
               if ((int)$kategorie < 1) {
                  $kategorie = 1;
               }
            }
         }
      }

      // Default-Werte setzen, falls nicht übertragen
      $marke = (isset($data['marke']) ? $data['marke'] : '');
      $vpe   = (isset($data['vpe']) ? $data['vpe'] : '');
      $vpm   = (isset($data['vpm']) ? $data['vpm'] : '');
      $sortierung = (isset($data['sortierung']) ? $data['sortierung'] : 1);

      if (!isset($data['grundeinheit_rechner'])) { $data['grundeinheit_rechner'] = ''; }
      if (!isset($data['rechner_check'])) { $data['rechner_check'] = 'n'; }
      if (!isset($data['gew_check'])) { $data['gew_check'] = 'n'; }
      if (!isset($data['rechner_mode'])) { $data['rechner_mode'] = '2'; }

      // Nicht verwendet
      // spalten2_check
      // is_foto
      // foto_set
      // org_set
      // foto_size_x
      // foto_size_y
      // motiv_uplaodp_check
      // motiv_uplaodt_check
      // artikelgruppe
      // configurator_check
      // configurator_artnr_check
      // configurator
      // config_einheit_check
      // config_menge_check
      // timer_check
      // timer_end
      // timer_anzeige
      // timer_art_disable
      // clicks
      // show_object
      // fsk_check

      // INSERT
      if ($insert) {
         $sql = "INSERT INTO #__articles_info SET ";

         if ((int)$data['id'] != 0) {
            $sql .= "id = ".$data['id'].", ";
         }

         // ge_netto für Varianten speichern (alte Daten)
         if (isset($data['ge_netto'])) {
            $this->ge_netto = $data['ge_netto'];
            $this->is_ge_netto = true;
         }
         else {
            $this->is_ge_netto = false;
         }

         $sql .= "  childs               = 0,
                    sortierung           = $sortierung,
                    steuersatz           = ".($data['steuersatz'] != '' ? $data['steuersatz'] : '1').",
                    name_deu             = '".$data['name']."',
                    desc_deu             = '".$data['beschreibung']."',
                    image                = '".$data['image']."',
                    staffelung           = '".$data['staffelung']."',
                    grundeinheit         = '".$data['grundeinheit']."',
                    ge_netto_aktiv       = '".$data['ge_netto_aktiv']."',
                    grundeinheit_rechner = '".$data['grundeinheit_rechner']."',
                    gew_check            = '".$data['gew_check']."',
                    rechner_check        = '".$data['rechner_check']."',
                    rechner_mode         = '".$data['rechner_mode']."',
                    versand_preis        = '".$data['versand_preis']."',
                    masse_check          = '".$data['masse_check']."',
                    masse_min            = '".$data['masse_min']."',
                    masse_komma          = '".$data['masse_komma']."',
                    gewicht              = '".$data['gewicht']."',
                    vpm                  = '".$vpm."',
                    vpe                  = '".$vpe."',
                    widerruf             = '".($data['widerruf'] != '' ? $data['widerruf'] : '1')."',
                    lieferfrist          = '".($data['lieferzeit'] != '' ? $data['lieferzeit'] : '1')."',
                    marke                = '".$marke."'";

         $this->db->query($sql);
         $parent_id = (int)$this->db->getNewId();
         $this->last_id = $parent_id;

         $this->db->query("INSERT INTO #__article_to_cats SET parent_id =  $this->last_id, cat_id = $kategorie, sort = 0");

         if (isset($data->images) && !empty(($data->images))) {
            $sort = 1;

            foreach (($data->images) as $img) {
               $this->db->query("INSERT INTO #__artiles_images SET parent_id = , sort = $sort, image = '$img'");
               $sort++;
            }
         }
      }

      // UPDATE
      else {


      // Bilderimport bei Update verhindern
         $sql = "UPDATE #__articles_info SET
                    name_deu             = '".$data['name']."',
                    desc_deu             = '".$data['beschreibung']."',
                    sortierung           = $sortierung,
                    steuersatz           = ".($data['steuersatz'] != '' ? $data['steuersatz'] : '1').",
/*                    cat_id               = $kategorie, */
                    staffelung           = '".$data['staffelung']."',
                    grundeinheit         = '".$data['grundeinheit']."',
                    ge_netto_aktiv       = '".$data['ge_netto_aktiv']."',
                    grundeinheit_rechner = '".$data['grundeinheit_rechner']."',
                    rechner_check        = '".$data['rechner_check']."',
                    rechner_mode         = '".$data['rechner_mode']."',
                    versand_preis        = '".$data['versand_preis']."',
                    masse_check          = '".$data['masse_check']."',
                    masse_min            = '".$data['masse_min']."',
                    masse_komma          = '".$data['masse_komma']."',
                    gewicht              = '".$data['gewicht']."',
                    vpm                  = '".$vpm."',
                    vpe                  = '".$vpe."',
                    widerruf             = '".($data['widerruf'] != '' ? $data['widerruf'] : '1')."',
                    lieferfrist          = '".($data['lieferzeit'] != '' ? $data['lieferzeit'] : '1')."',
                    marke                = '".$marke."'
                 WHERE id = ".$data['id'];

         $this->db->query($sql);

         // Kategorie neu setzen
         $this->db->query("delete from #__article_to_cats where parent_id =  {$data['id']}");
         $this->db->query("INSERT INTO #__article_to_cats SET parent_id =  {$data['id']}, cat_id = $kategorie, sort = 0");


         if ($this->picdownloads == 'y' && $cronjob == 0) {
            $this->db->query("UPDATE #__articles_info SET image = '".$data['image']."'");

            if (isset($data['images']) && !empty(($data['images']))) {
               $sort = 1;

               foreach (($data['images']) as $img) {
                  $this->db->query("INSERT INTO #__articles_images SET parent_id = $this->last_id, sort = $sort, image = '$img'
                                    ON DUPLICATE KEY UPDATE image = '$img'");
                  $sort++;
               }
            }
         }
      }

      // zusätzliche Daten eintragen
      $this->saveGoogleData($this->last_id, $data['gcat'], $data['zustand']);

      // Wenn gewählt, Bilder von anderen Servern downloaden
      if ($this->picdownloads == 'y' && $cronjob == 0) {
         if (strpos($data['image'], 'http://') !== false || strpos($data['image'], 'https://') !== false) {
            $img = Helper::downloadImage($data['image'], $this->last_id, 1);

            if ($img != '') {
               $img = str_replace('.jpg', '', $img);
               $this->db->query("UPDATE #__articles_info SET image = '$img' WHERE id = $this->last_id");
            }
         }

         if (isset($data['images']) && !empty(($data['images']))) {
            $sort = 2;

            foreach (($data['images']) as $image) {
               if (strpos($image, 'http://') !== false || strpos($image, 'https://') !== false) {
                  $img = Helper::downloadImage($image, $this->last_id, $sort);

                  if ($img != '') {
                     $img = str_replace('.jpg', '', $img);
                     $this->db->query("INSERT INTO #__articles_images SET parent_id = $this->last_id, sort = $sort, image = '$img'
                                       ON DUPLICATE KEY UPDATE image = '$img'");
                     $sort++;
                  }
               }
            }
         }
      }

      if ($cronjob > 0) {
         $this->db->query("INSERT INTO #__cron_articles SET parent_id = $this->last_id, cronjob_id = $cronjob");
         $this->db->query("UPDATE #__cronjobs SET `count` = `count` + 1 WHERE id = $cronjob");
      }
   }

   // Article in #__articles eintragen
   private function _insertVariant($data, $overwrite = false) {
      $lang = 'deu';
      $parent = $data['parent'];
      $sort = ($this->sort_fixed ? '1' : $data['sort']);
      $insert = true;
      $id = 0;

      if ($parent == 0) {
         if ($this->last_id == 0) {
            return false;
         }
         else {
            $parent = $this->last_id;
            $insert = true;
         }
      }

      // Test, ob Artikel in DB ist
      else {
         if (!$this->search_art_nr) {
            $sql = "SELECT id FROM #__articles WHERE parent_id = ".$data['parent']." AND sort = $sort";
         }
         else {
            $sql = "SELECT id FROM #__articles WHERE parent_id = ".$data['parent']." AND art_nr = '".$data['art_nr']."'";
         }
         $test = $this->db->query($sql);

         if ($test == 1) {
            $insert = false;
            $tmp = $this->db->getObject();
            $id = $tmp->id;
         }

         else {
            if ($sort == 0) {
               $sql = "SELECT max(sort) AS newsort FROM #__articles WHERE parent_id = $parent";
               $this->db->query($sql);
               $data2 = $this->db->getObject();
               $sort = (int)$data2->newsort + 1;
            }
            $insert = true;
         }
      }

      $merkmal1 = 0;
      $merkmal2 = 0;
      $wert1 = 0;
      $wert2 = 0;

      if ($data['mm_name1'] != '') {
         $merkmal1 = $this->_searchMerkmal($data['mm_name1'], $lang);
      }

      if ($data['mm_name2'] != '') {
         $merkmal2 = $this->_searchMerkmal($data['mm_name2'], $lang);
      }

      if ($data['w_name1'] != '') {
         $wert1 = $this->_searchWert($data['w_name1'], $merkmal1, $lang);
      }

      if ($data['w_name2'] != '') {
         $wert2 = $this->_searchWert($data['w_name2'], $merkmal2, $lang);
      }

      $online = 'y';
      if (isset($data['online']) && $data['online'] == 'n') {
         $online = 'n';
      }

      $angebot_active = 'n';
      if (isset($data['angebot_active']) && $data['angebot_active'] == 'y') {
         $angebot_active = 'y';
      }

      $menge = '1';
      if (isset($data['menge']) && $data['menge'] != '') {
         $menge = $data['menge'];
      }

      $haendler_netto = 0;
      if (isset($data['haendler_netto'])) {
         $haendler_netto = $data['haendler_netto'];
      }

      $gtin = '';
      if (isset($data['gtin']) && $data['gtin'] != '') {
         $gtin = $data['gtin'];
      }

      $mpn = '';
      if (isset($data['mpn']) && $data['mpn'] != '') {
         $mpn = $data['mpn'];
      }

      $ge_netto = 0;
      if (isset($data['ge_netto'])) {
         $ge_netto = $data['ge_netto'];
      }

      else if ($this->is_ge_netto) {
         $ge_netto = ($this->ge_netto != '0.00' ? $this->ge_netto : 0);
      }

      $ge_menge = 0;
      if (isset($data['ge_menge']) && $data['ge_menge'] != '') {
         $ge_menge = $data['ge_menge'];
      }

      $startbild = 1;
      if (isset($data['startbild'])) {
         $startbild = $data['startbild'];
      }

      if ($insert) {
         $sql = "INSERT INTO #__articles SET
                    parent_id      = $parent,
                    sort           = $sort,
                    online         = '$online',
                    art_nr         = '".$data['art_nr']."',
                    netto          = '".$data['netto']."',
                    angebot        = '".$data['angebot']."',
                    haendler_netto = '".$haendler_netto."',
                    angebot_active = '$angebot_active',
                    menge          = '$menge',
                    ge_netto       = '$ge_netto',
                    ge_menge       = '$ge_menge',
                    merkmal1       = $merkmal1,
                    wert1          = $wert1,
                    merkmal2       = $merkmal2,
                    wert2          = $wert2,
                    gewicht        = '0.00',
                    filename       = '',
                    filetyp        = '',
                    startbild      = '".$startbild."',
                    gtin           = '".$gtin."',
                    mpn            = '".$mpn."',
                    imported       = 'y'";
         $this->db->query($sql);
      }

      else {
         $sql = "UPDATE #__articles SET
                    online         = '$online',
                    art_nr         = '".$data['art_nr']."',
                    netto          = '".$data['netto']."',
                    angebot        = '".$data['angebot']."',
                    haendler_netto = '".$haendler_netto."',
                    angebot_active = '$angebot_active',
                    menge          = '$menge',
                    ge_netto       = '$ge_netto',
                    merkmal1       = $merkmal1,
                    wert1          = $wert1,
                    merkmal2       = $merkmal2,
                    wert2          = $wert2,
                    gewicht        = '0.00',
                    filename       = '',
                    filetyp        = '',
                    startbild      = '".$startbild."',
                    gtin           = '".$gtin."',
                    mpn            = '".$mpn."',
                    imported       = 'y'
                 WHERE parent_id = $parent AND sort = $sort";
         $this->db->query($sql);
      }
//echo $this->db->last_sql;
      $sql = "UPDATE #__articles_info SET childs = (SELECT count(id) FROM #__articles WHERE parent_id = $parent) WHERE id = $parent";
      $this->db->query($sql);
   }

   private function _getArtIdByArtNr($art_nr) {
      $parent_id = (int)$this->db->querySingleValue("SELECT parent_id FROM #__articles WHERE sort = 1 AND art_nr = '$art_nr'");
      return $parent_id;
   }

   private function _getArtKatByArtId($art_id) {
      return $this->db->querySingleValue("SELECT cat_id FROM #__article_to_cats WHERE parent_id = $art_id AND sort = 0");
   }

   private function _lexToAscii($string) {
      // return iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $string); // CP850, CP1252
      return $string;
   }

   // Google-Kategorien Namen suchen
   private function _getCatNames($liste) {
      $back = '';

      if ($liste != '') {
         $list_arr = explode(';', $liste);

         for ($i = 0; $i < count($list_arr); $i++) {
            $sql = "SELECT name FROM #__google_cats WHERE id = $list_arr[$i]";
            $this->db->query($sql);

            $data = $this->db->getObject();
            if ($i == 0) {
               $back = $data->name;
            }
            else {
               $back .= ' > ' . $data->name;
            }
         }
      }
      return $back;
   }

   // ID aus Merkmale anhand des Namens suchen
   private function _searchMerkmal($name, $lang) {
      // Bei Zahlen kein SOUNDS LIKE
      if (preg_match("/[0-9]+/",$name)) {
         $sql = "SELECT id FROM #__merkmale WHERE merkmal_$lang = '$name'";
      }
      else {
         $sql = "SELECT id FROM #__merkmale WHERE merkmal_$lang SOUNDS LIKE '$name'";
      }

      $test = $this->db->query($sql);

      if ($test == 1) {
         $data = $this->db->getObject();
         return $data->id;
      }

      else {
         $this->db->query("INSERT INTO #__merkmale SET merkmal_$lang = '$name'");
         return $this->db->getNewId();
      }
      return 0;
   }

   // ID aus Werte anhand des Namens suchen
   private function _searchWert($name, $merkmal_id, $lang) {
      // Bei Zahlen kein SOUNDS LIKE
      if (preg_match("/[0-9]+/",$name)) {
         $sql = "SELECT id FROM #__werte WHERE merkmal_id = $merkmal_id AND wert_$lang = '$name'";
      }
      else {
         $sql = "SELECT id FROM #__werte WHERE merkmal_id = $merkmal_id AND wert_$lang SOUNDS LIKE '$name'";
      }

      $test = $this->db->query($sql);
      if ($test == 1) {
         $data = $this->db->getObject();
         return $data->id;
      }
      else {
         $this->db->query("INSERT INTO #__werte SET merkmal_id = $merkmal_id, wert_$lang = '$name'");
         return $this->db->getNewId();
      }
      return 0;
   }

   private function _checkWerte($art, $wert, $lang = 'deu') {
      $sql = "SELECT id FROM #__merkmale WHERE merkmal_$lang = '$art'";
      $test = $this->db->query($sql);

      if ($test != 1) {
         return;
      }
      $data = $this->db->getObject();
      $merkmal = (int)$data->id;

      if ($merkmal == 0) {
         return;
      }

      $sql = "SELECT id FROM #__werte WHERE merkmal_id = $merkmal AND wert_$lang = '$wert'";
      $test = $this->db->query($sql);

      if ($test == 1) {
         return;
      }

      $sql = "INSERT INTO #__werte SET  merkmal_id = $merkmal, wert_$lang = '$wert'";
      $this->db->query($sql);
   }

   // Merkmale / Werte in DB suchen (Name) und ID zurück geben
   private function _checkMerkmalePortal($merkmal, $wert) {
      $merkmal_id = 0;

      if ($merkmal != '') {
         $merkmal_id = $this->db->querySingleValue("SELECT id FROM #__merkmale WHERE merkmal_deu = '$merkmal'");

         // Noch nicht vorhanden - eintragen
         if ($merkmal_id == null) {
            $this->db->query("INSERT INTO #__merkmale SET merkmal_deu = '$merkmal'");
            $merkmal_id = $this->db->getNewId();
         }
      }

      if ($wert != '') {
         $test = $this->db->querySingleValue("SELECT id FROM #__werte WHERE merkmal_id = $merkmal_id AND wert_deu = '$wert'");
         // Noch nicht vorhanden - eintragen
         if ($test == 0) {
            $this->db->query("INSERT INTO #__werte SET merkmal_id = $merkmal_id, wert_deu = '$wert'");
         }
      }
   }

   private function _html2txt($html) {
      $html = str_replace(['<br>', '<br/>', '<br />', '</p>', '</div>', '</td>'], ['<br/> ', '<br/> ', '<br/> ', '</p> ', '</div> ', '</td> '], $html);
      $text = strip_tags($html);
      $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
      $text = str_replace('  ', ' ', $text);
      return $text;
   }

   // Bildlink generieren
   // 15.03.2019
   private function _checkPict($bild) {
      // Kein Bild vorhanden
      if ($bild == '' || strpos($bild, 'nopic.png') !== false) {
         return '';
      }

      // Bild ist schon Link
      if (strpos($bild, 'http://') !== false || strpos($bild, 'https://') !== false) {
         return $bild;
      }

      // Bild-Link zurück geben (uralt: Bildname mit .jpg)
      return SHOP_URL.'/'.CONF_PICT_PATH.str_replace('.jpg', '', $bild).'.jpg';
   }

   private function _deldir($dir, $startdir) {
      $files = scandir($dir);
      foreach ($files as $file) {
         if ($file != '.' && $file != '..' && $file != 'index.html' && $file != '.htaccess') {
            if (filetype($dir.$file) == 'dir') {
               $this->_deldir($dir.$file.'/', $startdir);
            }
            else {
               unlink($dir.$file);
            }
         }
      }
      if ($dir != $startdir) {
         rmdir($dir);
      }
   }

   // Dateien "zur Überprüfung" lesen
   public function readCheckfiles($haendler_id, $typ) {
      $h_dir = SHOP_PATH.'/downloads/checkfiles';

      if (!is_dir($h_dir)) {
         mkdir($h_dir);
      }

      if (!is_dir($h_dir.'/xml')) {
         mkdir($h_dir.'/xml');
      }

      if (!is_dir($h_dir.'/xt')) {
         mkdir($h_dir.'/xt');
      }

      if (!is_dir($h_dir.'/shops')) {
         mkdir($h_dir.'/shops');
      }

      $files = [];
      $haendler_nr = 0;
      $haendler_nr = $this->db->querySingleValue("SELECT haendler_nr FROM #__haendler WHERE user_id = $haendler_id");

      $hdl = opendir(SHOP_PATH.'/downloads/checkfiles/'.$typ);
      while (($file = readdir($hdl)) !== false) {
         if ($file != '.' && $file != '..' && preg_match('|^('.$haendler_nr.')_|', $file) > 0) {
            $files[] = $file;
         }
      }
      closedir($hdl);

      if (count($files) == 0) {
         return '';
      }

      sort($files);

      $html = '';
      foreach ($files as $file) {
         $html .= '<div style="position:relative; height:24px; line-height:24px;">'.CR;
         $html .= '<a class="haendler_download_g" style="display:inline-block; position:relative; width:19px; height:19px; top:3px;" href="'.$this->params->basepath.'/admin/index.php/haendler/csvdownload/'.$typ.'/'.$file.'"></a>'.CR;
         $html .= '<span class="haendler_del" style="display:inline-block; position:relative; width:19px; height:19px; top:3px;" onclick="Royalart.csvDelete(this, \''.$file.'\', \''.$typ.'\')"></span>'.CR;
         $html .= Helper::truncate(substr($file, strlen($haendler_nr) + 1), 40).CR;
         $html .= '</div>'.CR;
      }

      return $html;
   }

   public function csvDownload($filename, $typ) {
      $filename = rawurldecode($filename);
      $file = SHOP_PATH.'/downloads/checkfiles/'.$typ.'/'.$filename;
      header('Content-type: application/octetstream');
      header('Content-Disposition: attachment; filename="'.$filename.'"');
      readfile($file);
   }

   public function csvDelete($filename, $typ) {
      $file = SHOP_PATH.'/downloads/checkfiles/'.$typ.'/'.$filename;
      if (file_exists($file)) {
         unlink($file);
         echo json_encode(['status' => 'ok']);
         return;
      }
      echo json_encode(['status' => 'error']);
   }

   private function _removeExportFile($name) {
      if ($dh = opendir(SHOP_PATH.'/export')) {
          while (false !== ($file = readdir($dh))) {
              if ($file != "." && $file != "..") {
                  if (strstr($file, $name) !== false) {
                     unlink(SHOP_PATH.'/export/'.$file);
                  }
              }
          }
          closedir($dh);
      }
   }

   // Tools/Portale
   public function getExport() {
      // 0 -> Name
      // 1 -> filename ohne Pfad und Erweiterung
      // 2 -> format
      // 3 -> Text Button
      // 4 -> path.file
      // 5 -> Datum letzter Export / Erstellung
      // 6 -> Cronjob möglich (n/y)
      // 7 Cronjob verwenden (n/y)
      $export = [
                  // id                     Name            datei           format    Button
//                  'amazon'     => array('Amazon.de',     'amazon_de',     'csv', 'CSV-Export', '', '', 'n'),
//                  'billiger'   => array('Billiger.de',   'billiger_de',   'xml', 'XML-Export', '', '', 'n'),
//                  'ciao'       => array('Ciao.de',       'ciao_de',       'xml', 'XML-Export', '', '', 'y'),
//                  'guenstiger' => array('Guenstiger.de', 'guenstiger_de', 'csv', 'CSV-Export', '', '', 'n'),
//                  'idealo'     => array('Idealo.de',     'idealo_de',     'xml', 'XML-Export', '', '', 'y'),
//                  'kelkoo'     => array('Kelkoo.de',     'kelkoo_de',     'xml', 'XML-Export', '', '', 'n'),
//                  'yatego'     => array('Yatego.de',     'yatego_de',     'xml', 'XML-Export', '', '', 'n'),
//                  'wein'       => array('Wein.cc',       'wein_cc',       'csv', 'CSV-Export', '', '', 'n'),
//                  'hood'       => array('Hood.de',       'hood',          'csv', 'CSV-Export', '', '', 'n')
                ];
      if (($dh = opendir(SHOP_PATH.'/export'))) {
         while (false !== ($file = readdir($dh))) {
            if ($file != "." && $file != "..") {
               if (strstr($file, 'amazon') !== false && isset($export['amazon'])) {
                  if (strstr($file, '.'.$export['amazon'][2]) !== false) {
                     $export['amazon'][4] = SHOP_URL.'/export/'.$file;
                  }
                  if (strstr($file, '.info') !== false) {
                     $export['amazon'][5] = str_replace(['amazon', '.info', '_', '-'], ['', '', '.', ':'], $file);
                  }
               }

               if (strstr($file, 'billiger') !== false && isset($export['billiger'])) {
                  if (strstr($file, '.'.$export['billiger'][2]) !== false) {
                     $export['billiger'][4] = SHOP_URL.'/export/'.$file;
                  }

                  if (strstr($file, '.info') !== false) {
                     $export['billiger'][5] = str_replace(['billiger', '.info', '_', '-'], ['', '', '.', ':'], $file);
                  }
               }

               if (strstr($file, 'ciao') !== false && isset($export['ciao'])) {
                  if (strstr($file, '.'.$export['billiger'][2]) !== false) {
                     $export['ciao'][4] = SHOP_URL.'/export/'.$file;
                  }

                  if (strstr($file, '.info') !== false) {
                     $export['ciao'][5] = str_replace(['ciao', '.info', '_', '-'], ['', '', '.', ':'], $file);
                  }
               }

               if (strstr($file, 'guenstiger') !== false && isset($export['guenstiger'])) {
                  if (strstr($file, '.'.$export['guenstiger'][2]) !== false) {
                     $export['guenstiger'][4] = SHOP_URL.'/export/'.$file;
                  }

                  if (strstr($file, '.info') !== false) {
                     $export['guenstiger'][5] = str_replace(['guenstiger', '.info', '_', '-'], ['', '', '.', ':'], $file);
                  }
               }

               if (strstr($file, 'idealo') !== false && isset($export['idealo'])) {
                  if (strstr($file, '.'.$export['idealo'][2]) !== false) {
                     $export['idealo'][4] = SHOP_URL.'/export/'.$file;
                  }

                  if (strstr($file, '.info') !== false) {
                     $export['idealo'][5] = str_replace(['idealo', '.info', '_', '-'], ['', '', '.', ':'], $file);
                  }
               }

               if (strstr($file, 'kelkoo') !== false && isset($export['kelkoo'])) {
                  if (strstr($file, '.'.$export['kelkoo'][2]) !== false) {
                     $export['kelkoo'][4] = SHOP_URL.'/export/'.$file;
                  }

                  if (strstr($file, '.info') !== false) {
                     $export['kelkoo'][5] = str_replace(['kelkoo', '.info', '_', '-'], ['', '', '.', ':'], $file);
                  }
               }

               if (strstr($file, 'yatego') !== false && isset($export['yatego'])) {
                  if (strstr($file, '.'.$export['yatego'][2]) !== false) {
                     $export['yatego'][4] = SHOP_URL.'/export/'.$file;
                  }

                  if (strstr($file, '.info') !== false) {
                     $export['yatego'][5] = str_replace(['yatego', '.info', '_', '-'], ['', '', '.', ':'], $file);
                  }
               }

               if (strstr($file, 'wein') !== false && isset($export['wein'])) {
                  if (strstr($file, '.'.$export['wein'][2]) !== false) {
                     $export['wein'][4] = SHOP_URL.'/export/'.$file;
                  }

                  if (strstr($file, '.info') !== false) {
                     $export['wein'][5] = str_replace(['wein', '.info', '_', '-'], ['', '', '.', ':'], $file);
                  }
               }

               if (strstr($file, 'hood') !== false && isset($export['hood'])) {
                  if (strstr($file, '.'.$export['hood'][2]) !== false) {
                     $export['hood'][4] = SHOP_URL.'/export/'.$file;
                  }

                  if (strstr($file, '.info') !== false) {
                     $export['hood'][5] = str_replace(['hood', '.info', '_', '-'], ['', '', '.', ':'], $file);
                  }
               }
            }
         }

         closedir($dh);

         if (isset($export['amazon']) && $export['amazon'][6] == 'y') {
            $export['amazon'][7] = Helper::getData('amazon_cron', 'n');
         }

         if (isset($export['billiger']) && $export['billiger'][6] == 'y') {
            $export['billiger'][7] = Helper::getData('billiger_cron', 'n');
         }

         if (isset($export['ciao']) && $export['ciao'][6] == 'y') {
            $export['ciao'][7] = Helper::getData('ciao_cron', 'n');
         }

         if (isset($export['guenstiger']) && $export['guenstiger'][6] == 'y') {
            $export['guenstiger'][7] = Helper::getData('guenstiger_cron', 'n');
         }

         if (isset($export['idealo']) && $export['idealo'][6] == 'y') {
            $export['idealo'][7] = Helper::getData('idealo_cron', 'n');
         }

         if (isset($export['kelkoo']) && $export['kelkoo'][6] == 'y') {
            $export['kelkoo'][7] = Helper::getData('kelkoo_cron', 'n');
         }

         if (isset($export['yatego']) && $export['yatego'][6] == 'y') {
            $export['yatego'][7] = Helper::getData('yatego_cron', 'n');
         }

         if (isset($export['wein']) && $export['wein'][6] == 'y') {
            $export['wein'][7] = Helper::getData('wein_cron', 'n');
         }
      }
      return $export;
   }
}
