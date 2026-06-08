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

define ('SLIDE_HEIGHT', 800);

class KANPAICLASSIC_modulStatistik
{
   private $db;
   private $params;
   public  $clicks_year = 0;

   public function __construct() {
      $this->db     = Control::getDB();
      $this->params = Control::getParams();
   }

   // Balken Umsatz-Statistik generieren
   public function statisticUmsatz($year = 0) {
      // Wenn kein Jahr übergeben -> aktuelle Jahr
      if ($year == 0) {
         $year = (int)date('Y');
      }

      // Frühestes Datum aus Rechnungen
      $min_year = (int)date($this->db->querySingleValue("SELECT MIN(created) FROM #__rechnung WHERE deleted = 'n'"));

      // Jahre vor 2013 nicht berücksichtigen
      if ($year < 2013) {
         $year = 2013;
      }

      // Wenn nur 1 Jahr gefunden, anzeigen
      if ($year == $min_year) {
         $this->year_select = $year;
      }

      // Select-Box, wenn mehr als 1 Jahr
      else {
         $this->year_select = '<span class="selectbox30"><select id="statistik_year" onchange="Shopinhaber.statistikChanged()">';

         for ($i = $year; $i >= $min_year; $i--) {
            $this->year_select .= '<option value="'.$i.'"'.($i == $year ? ' selected="selected"' : '').'>'.$i.'</option>';
         }

         $this->year_select .= '</span></select>';
      }

      $year_arr      = [];
      $last_year_arr = [];
      $height        = 200;
      $max           = 1;
      $html          = '';

      // Arrays vorbelegen
      for ($i = 1; $i < 13; $i++) {
         $year_arr[$i]      = [0, 0, 0];
         $last_year_arr[$i] = [0, 0, 0];
      }

      // aktuelles/gewähltes Jahr
      $data1 = $this->db->queryAllObjects("SELECT SUM(netto) AS netto,
                                                 (SUM(steuer1) + SUM(steuer2)+SUM(steuer3)) AS steuer ,
                                                 MONTH(created) as monat
                                              FROM #__rechnung
                                           WHERE deleted = 'n' AND status < 5 AND created BETWEEN DATE('".$year."-01-01') AND DATE('".$year."-12-31')
                                           GROUP BY MONTH(created)");

      // Vorjahr davon
      $data2 = $this->db->queryAllObjects("SELECT SUM(netto) AS netto,
                                                 (SUM(steuer1) + SUM(steuer2)+SUM(steuer3)) AS steuer ,
                                                 MONTH(created) as monat
                                              FROM #__rechnung
                                           WHERE deleted = 'n' AND status < 5 AND created BETWEEN DATE('".($year - 1)."-01-01') AND DATE('".($year - 1)."-12-31')
                                           GROUP BY MONTH(created)");

      // Daten in Arrays (Jahr / Vorjahr) für Monate einlesen
      for ($i = 1; $i < 13; $i++) {
         if (isset($data1[$i - 1])) {
            $betrag = round($data1[$i - 1]->netto + $data1[$i - 1]->steuer);
            $max    = max($max, $betrag);

            $year_arr[(int)$data1[$i - 1]->monat] = [round($data1[$i - 1]->netto), round($data1[$i - 1]->steuer)];
         }

         if (isset($data2[$i - 1])) {
            $betrag = round($data2[$i - 1]->netto + $data2[$i - 1]->steuer);
            $max    = max($max, $betrag);

            $last_year_arr[(int)$data2[$i - 1]->monat] = [round($data2[$i - 1]->netto), round($data2[$i - 1]->steuer)];
         }
      }

      $test1 = (float)$max;
      $test2 = 0.1;

      // Skalierung berechnen
      while ($test1 > 1) {
         $test1 /= 10;
         $test2 *= 10;
      }

      $max = ceil($test1 * 10) * $test2;

      $html .= '<div class="statistic_max">'.number_format($max, 0, '', '.').' '.$this->params->waehrung.'</div>';
      $html .= '<div class="statistic_first" style="height:'.$height.'px;">&nbsp;</div>';

      for ($i = 1; $i < 13; $i++) {
         $netto       = (int)round($height / $max * $year_arr[$i][0]);
         $steuer      = (int)round($height / $max * $year_arr[$i][1]);
         $last_netto  = (int)round($height / $max * $last_year_arr[$i][0]);
         $last_steuer = (int)round($height / $max * $last_year_arr[$i][1]);

         $html .= '<div class="statistic_balken" style="height:'.$height.'px;">';
         $html .= '<div class="last">';
         $html .= '<div class="last_leer" style="height:'.($height - $last_netto - $last_steuer).'px;"></div>';
         $html .= '<div class="last_steuer col_alt_ust pointer" title="'.number_format(($last_year_arr[$i][0] + $last_year_arr[$i][1]), 0, '', '.').' '.$this->params->waehrung.' brutto" style="height:'.$last_steuer.'px;"></div>';
         $html .= '<div class="last_netto col_alt pointer" title="'.number_format($last_year_arr[$i][0], 0, '', '.').' '.$this->params->waehrung.' netto" style="height:'.$last_netto.'px;"></div>';
         $html .= '</div>';
         $html .= '<div class="aktuell">';
         $html .= '<div class="aktuell_leer" style="height:'.($height - $netto - $steuer).'px;"></div>';
         $html .= '<div class="steuer col_aktuell_ust pointer" title="'.number_format(($year_arr[$i][0] + $year_arr[$i][1]), 0, '', '.').' '.$this->params->waehrung.' brutto" style="height:'.$steuer.'px;"></div>';
         $html .= '<div class="netto col_aktuell pointer" title="'.number_format($year_arr[$i][0], 0, '', '.').' '.$this->params->waehrung.' netto" style="height:'.$netto.'px;"></div>';
         $html .= '</div>';
         $html .= '<div class="clear"></div>';
         $html .= '</div>';
      }

      $html .= '<div class="clear"></div>';

      $html .= '<div class="stat_fuss">';
      $html .= '   <div class="stat_fuss_null">0 '.$this->params->waehrung.'</div>';
      $html .= '   <img src="'.ADMIN_URL.'/img/stat_monate.jpg" alt="" />';
      $html .= '</div>';
      $html .= '<div class="clear"></div>';

      return $html;
   }

   public function bestsellerArticle() {
      $data = $this->db->queryAllObjects("SELECT a.art_nr AS id, i.name_".$this->params->selected_lang." AS name, i.clicks
                                             FROM #__articles_info AS i, #__articles AS a
                                          WHERE a.sort = 1
                                             AND a.parent_id = i.id
                                             AND i.clicks > 0
                                          ORDER BY i.clicks DESC, i.id LIMIT 0,20");
      $html = '';
      $max = 1;
      $width = 550;

      for ($i = 0; $i < (is_array($data) ? count($data) : 0); $i++) {
         $max = max($max, $data[$i]->clicks);
      }

      $test1 = (float)$max;
      $test2 = 0.1;

      while ($test1 > 1) {
         $test1 /= 10;
         $test2 *= 10;
      }

      $max = ceil($test1 * 10) * $test2;

      if ($max < 10) {
         $max = 10;
      }

      $html .= '<div class="bestseller_zeile">';
      $html .= '   <div class="bestseller_zeile_1 txt_bez ellipsis">Artikel-Nr</div>';
      $html .= '   <div class="bestseller_zeile_2 txt_bez ellipsis">Artikelname</div>';
      $html .= '   <div class="bestseller_zeile_3"> . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . ';
      $html .= '      <span class="txt_bez bestseller_zeile_3_left">Klicks</span>';
      $html .= '      <span class="txt_bez bestseller_zeile_3_right">'.number_format($max, 0, '', '.').' Klicks</span>';
      $html .= '   </div>';
      $html .= '</div>';

      for ($i = 0; $i < (is_array($data) ? count($data) : 0); $i++) {
         $breite = round($width / $max * $data[$i]->clicks);
         $html .= '<div class="bestseller_zeile">';
         $html .= '   <div class="bestseller_zeile_1 ellipsis">'.Helper::truncate($data[$i]->id, 30).'</div>';
         $html .= '   <div class="bestseller_zeile_2 ellipsis">'.Helper::truncate($data[$i]->name, 30).'</div>';
         $html .= '   <div class="bestseller_zeile_3"><div class="col_aktuell pointer" title="'.number_format($data[$i]->clicks, 0, '', '.').' Klicks" style="width:'.$breite.'px;"></div></div>';
         $html .= '</div>';
         $html .= '<div class="clear"></div>';
      }
      return $html;
   }

   public function bestsellerCategorie() {
      $data = $this->db->queryAllObjects("SELECT name_".$this->params->default_lang." AS name, clicks FROM #__categories WHERE clicks > 0 ORDER BY clicks DESC, id LIMIT 0,20");

      $html = '';
      $max = 1;
      $width = 550;

      for ($i = 0; $i < (is_array($data) ? count($data) : 0); $i++) {
         $max = max($max, $data[$i]->clicks);
      }

      $test1 = (float)$max;
      $test2 = 0.1;

      while ($test1 > 1) {
         $test1 /= 10;
         $test2 *= 10;
      }

      $max = ceil($test1 * 10) * $test2;
      if ($max < 10) {
         $max = 10;
      }

      $html .= '<div class="bestseller_zeile"></div>';
      $html .= '<div class="bestseller_zeile">';
      $html .= '<div class="bestseller_zeile_4 txt_bez ellipsis">Kategoriename</div>';
      $html .= '   <div class="bestseller_zeile_3"> . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . ';
      $html .= '      <span class="txt_bez bestseller_zeile_3_left">Klicks</span>';
      $html .= '      <span class="txt_bez bestseller_zeile_3_right">'.number_format($max, 0, '', '.').' Klicks</span>';
      $html .= '   </div>';
      $html .= '</div>';

      for ($i = 0; $i < (is_array($data) ? count($data) : 0); $i++) {
         $breite = round($width / $max * $data[$i]->clicks);
         $html .= '<div class="bestseller_zeile">';
         $html .= '<div class="bestseller_zeile_4 ellipsis">'.Helper::truncate($data[$i]->name, 25).'</div>';
         $html .= '<div class="bestseller_zeile_3"><div class="col_aktuell pointer" title="'.number_format($data[$i]->clicks, 0, '', '.').' Klicks" style="width:'.$breite.'px;"></div></div>';
         $html .= '</div>';
         $html .= '<div class="clear"></div>';
      }
      return $html;
   }

   public function statisticClicks($year = 0) {
      // Wenn kein Jahr übergeben -> aktuelle Jahr
      if ($year == 0 || $year == '') {
         $year = (int)date('Y');
      }

      // Frühestes Datum aus Rechnungen ((int)'2018-11-11 11:11:11 wird zu 2018)
      $min_year = (int)date($this->db->querySingleValue("SELECT MIN(date) FROM #__statistik WHERE robot = 'n'"));

      // Jahre vor 2018 nicht berücksichtigen
      if ($min_year < 2018) {
         $min_year = 2018;
      }

      if ($year < 2018) {
         $year = 2018;
      }

      $this->clicks_year = $year;

      // Select-Box nur, wenn mehr als 1 Jahr
      if ($year == $min_year) {
         $this->year_select_clicks = $year;
      }

      else {
         $this->year_select_clicks = '<span class="selectbox30"><select id="statistik_year_clicks" onchange="Shopinhaber.statistikClicks()">';

         for ($i = $year; $i >= $min_year; $i--) {
            $this->year_select_clicks .= '<option value="'.$i.'"'.($i == $year ? ' selected="selected"' : '').'>'.$i.'</option>';
         }

         $this->year_select_clicks .= '</span></select>';
      }

      $year_arr      = [];
      $last_year_arr = [];
      $height        = 200;
      $max           = 1;
      $html          = '';

      // Arrays vorbelegen
      for ($i = 0; $i < 12; $i++) {
         $year_arr[$i]      = 0;
         $last_year_arr[$i] = 0;
      }

      // aktuelles/gewähltes Jahr
      if (Helper::getData('statistik_mode', 'all') == 'all') {
         $data1 = $this->db->queryAllObjects("SELECT SUM(anzahl) AS anzahl, MONTH(date) AS monat
                                              FROM #__statistik
                                              WHERE robot = 'n' AND YEAR(date) = '$year'
                                              GROUP BY MONTH(date)");

         // Vorjahr davon
         $data2 = $this->db->queryAllObjects("SELECT SUM(anzahl) AS anzahl, MONTH(date) AS monat
                                             FROM #__statistik
                                             WHERE robot = 'n' AND YEAR(date) = '".($year - 1)."'
                                             GROUP BY MONTH(date)");
      }

      else {
         $data1 = $this->db->queryAllObjects("SELECT COUNT(id) AS anzahl, MONTH(date) AS monat
                                              FROM #__statistik
                                              WHERE robot = 'n' AND YEAR(date) = '$year'
                                              GROUP BY MONTH(date)");
         // Vorjahr davon
         $data2 = $this->db->queryAllObjects("SELECT COUNT(id) AS anzahl, MONTH(date) AS monat
                                             FROM #__statistik
                                             WHERE robot = 'n' AND YEAR(date) = '".($year - 1)."'
                                             GROUP BY MONTH(date)");
      }

      // Daten in Array für Monate einlesen
      for ($i = 0; $i < 12; $i++) {
         if (isset($data1[$i]->monat)) {
            $anzahl = (int)$data1[$i]->anzahl;
            $max = max($max, $anzahl);
            $year_arr[(int)$data1[$i]->monat - 1] = $anzahl;
         }

         if (isset($data2[$i])) {
            $anzahl = (int)$data2[$i]->anzahl;
            $max = max($max, $anzahl);
            $last_year_arr[(int)$data2[$i]->monat - 1] = $anzahl;
         }
      }

      $test1 = (float)$max;
      $test2 = 0.1;

      // Skalierung berechnen
      while ($test1 > 1) {
         $test1 /= 10;
         $test2 *= 10;
      }

      $max = ceil($test1 * 10) * $test2;

      // Skala Y
      $html .= '<div class="statistic_max">'.$max.'</div>';
      $html .= '<div class="statistic_first" style="height:'.$height.'px;">&nbsp;</div>';

      for ($i = 0; $i < 12; $i++) {
         $netto       = (int)round($height / $max * $year_arr[$i]);
         $last_netto  = (int)round($height / $max * $last_year_arr[$i]);

         $html .= '<div class="statistic_balken" style="height:'.$height.'px;">';
         $html .= '   <div class="last">';
         $html .= '      <div class="last_leer" style="height:'.($height - $last_netto).'px;"></div>';
         $html .= '      <div class="last_steuer col_alt pointer" title="'.$last_year_arr[$i].' '.(Helper::getData('statistik_mode', 'all') == 'all' ? 'Klicks' : 'User').'" style="height:'.$last_netto.'px;"></div>';
         $html .= '   </div>';
         $html .= '   <div class="aktuell">';
         $html .= '      <div class="aktuell_leer" style="height:'.($height - $netto).'px;"></div>';
         $html .= '      <div class="netto col_aktuell pointer" title="'.$year_arr[$i].' '.(Helper::getData('statistik_mode', 'all') == 'all' ? 'Klicks' : 'User').'" style="height:'.$netto.'px;"></div>';
         $html .= '   </div>';
         $html .= '   <div class="clear"></div>';
         $html .= '</div>';
      }

      $html .= '<div class="clear"></div>';

      $html .= '<div class="stat_fuss">';
      $html .= '   <div class="stat_fuss_null">0</div>';
      $html .= '   <img src="'.ADMIN_URL.'/img/stat_monate.jpg" alt="" />';
      $html .= '</div>';
      $html .= '<div class="clear"></div>';

      $html .= '<div class="statistik_clicks_wrapper">';
      $html .= $this->_getAnzahlClicks($year);
      $html .='</div>';
      $html .= '<div class="clear"></div>';

      return $html;
   }

   private function _getAnzahlClicks($year) {
      $anzahl_clicks = 0;

      if (Helper::getData('statistik_mode', 'all') == 'all') {
         $this->user_klicks = 'Klicks';
         $anzahl_clicks = (int)$this->db->querySingleValue("SELECT SUM(anzahl) FROM #__statistik WHERE  robot = 'n'");
      }

      else {
         $this->user_klicks = 'User';
         $anzahl_clicks = (int)$this->db->querySingleValue("SELECT count(id) FROM #__statistik WHERE robot = 'n'");
      }

      // Klicks in Array wandeln
      $anzahl_clicks_str = sprintf('%07s', $anzahl_clicks);

      for ($i = 0; $i < strlen($anzahl_clicks_str); $i++) {
         $anzahl_clicks_arr[] = $anzahl_clicks_str[$i];
      }

      // HTML für Counter generieren
      $html  = '<div id="statistik_clicks_summe">';
      $html .= '   <div class="statistik_clicks_legende"><span class=" txt_bez">Gesamte '.(Helper::getData('statistik_mode', 'all') == 'all' ? 'Klicks' : 'User').'&nbsp;&nbsp;&nbsp;</span>';
      $html .= '      <div class="statistik_clicks_values">';

      for ($i = 0; $i < count($anzahl_clicks_arr); $i++) {
         $html .= '         <span class="statistik_click_val statistik_click_val'.$anzahl_clicks_arr[$i].'"></span>';
      }

      $html .= '      </div>';
      $html .= '   </div>';

      // Auswahl Klicks / User
      $html .= '   <div class="statistik_clicks_mode">';
      $html .= '      <input type="radio" class="newdesign" id="statistik_mode_all" value="all" name="statistik_mode"'.(Helper::getData('statistik_mode', 'all') == 'all' ? ' checked="checked"' : '').' onchange="Shopinhaber.statistikClicks();" />';
      $html .= '      <label for="statistik_mode_all">Klicks</label>';
      $html .= '      &nbsp;&nbsp;';
      $html .= '      <input type="radio" class="newdesign" id="statistik_mode_user" value="session" name="statistik_mode"'.(Helper::getData('statistik_mode', 'all') == 'session' ? ' checked="checked"' : '').' onchange="Shopinhaber.statistikClicks();" />';
      $html .= '      <label for="statistik_mode_user">User (Sessions)</label>';
      $html .= '   </div>';
      $html .= '</div>';

      return $html;
   }

   public function statisticDelete() {
      $this->db->query("UPDATE #__articles_info SET clicks = 0");
      $this->db->query("UPDATE #__categories SET clicks = 0");
      $this->db->query("TRUNCATE TABLE #__statistik");
   }
}
