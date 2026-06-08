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

class KANPAICLASSIC_sitemap
{
   public function __construct() {
      $this->articles   = Control::getArtikel();
      $this->categories = Control::getKategorie();
      $this->seiten     = Control::getSeiten();
      $this->params     = Control::getParams();
   }

   // Sitemap aktivieren oder deaktivieren (löschen)
   // 28.01.2020
   public function status($sitemap_check) {
      // Sitemaps löschen
      if ($sitemap_check == 'n') {
         @unlink(SHOP_PATH.'/sitemap_articles.html');
         @unlink(SHOP_PATH.'/sitemap_articles.xml');

         @unlink(SHOP_PATH.'/sitemap_categories.html');
         @unlink(SHOP_PATH.'/sitemap_categories.xml');

         @unlink(SHOP_PATH.'/sitemap_seiten.html');
         @unlink(SHOP_PATH.'/sitemap_seiten.xml');

         @unlink(SHOP_PATH.'/sitemap.xml');
      }

      // Seitemaps erstellen
      else if ($sitemap_check == 'y') {
         if ($this->params->firma['sitemap_articles'] == 'y') {
            $this->articles->sitemap();
         }

         if ($this->params->firma['sitemap_cat'] == 'y') {
            $this->categories->sitemap();
         }

         if ($this->params->firma['sitemap_menu'] == 'y' || $this->params->firma['sitemap_agb'] == 'y') {
            $this->seiten->sitemap();
         }

         $this->sitemapXml();
      }
   }

   public function check($status) {
      if ($this->params->firma['sitemap_check'] == 'y') {
         $this->articles->sitemap($status->sitemap_articles);
         $this->categories->sitemap($status->sitemap_cat, $status->sitemap_cat_lev1, $status->sitemap_cat_lev2);
         $this->seiten->sitemap($status->sitemap_menu, $status->sitemap_agb);

         $this->sitemapXml();
      }
   }

   public function sitemapXml() {
      if ($this->params->firma['sitemap_xml'] == 'y' && $this->params->firma['sitemap_check'] == 'y') {
         $xml  = '';
         $xml1 = (file_exists(SHOP_PATH.'/sitemap_articles.xml') ? file_get_contents(SHOP_PATH.'/sitemap_articles.xml') : '');
         $xml2 = (file_exists(SHOP_PATH.'/sitemap_categories.xml') ? file_get_contents(SHOP_PATH.'/sitemap_categories.xml') : '');
         $xml3 = (file_exists(SHOP_PATH.'/sitemap_seiten.xml') ? file_get_contents(SHOP_PATH.'/sitemap_seiten.xml') : '');


         $xml .= '<?xml version="1.0" encoding="UTF-8"?>'.CR;
         $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
            xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
            xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">'.CR;
         $xml .= $xml1.$xml2.$xml3;
         $xml .= '</urlset>'."\n";

         file_put_contents(SHOP_PATH.'/sitemap.xml', $xml);
         file_put_contents(SHOP_PATH.'/sitemap.xml.txt', $xml);
      }

      else {
         @unlink(SHOP_PATH.'/sitemap.xml');
      }
   }
}