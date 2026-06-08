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


if (!defined('KANPAICLASSIC')) {
   die ("This file cannot run outside the Kanpai Classic Shopsoftware");
}

header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time()));
header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
$menu                 = KANPAICLASSIC\Control::getMenu();
$admin_config         = $menu->loadDesign();

$cron = false;

 if (is_file(SHOP_PATH.'/cronjob.php')) {
    $cron = true;
 }

?><!DOCTYPE html>
<html lang="de">
<head>
<title>Kanpai Classic <?php echo (!defined('CONF_MODULE_PORTAL') ? 'Shopsystem' : 'Shopportal'); ?> - Administration - Tools</title>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
<link rel="stylesheet" href="<?php echo SHOP_URL; ?>/fonts/fontawesome/css/all.css" />
<style>
<?php include_once ADMIN_PATH.'/css/'.(is_file(ADMIN_PATH.'/css/admin.css') ? 'admin.css' : 'admin_easy.css'); ?>
</style>
<link rel="stylesheet" href="<?php echo SHOP_URL; ?>/fonts/fontawesome/css/all.css" />
</head>


<body>
<div id="page" class="admin_bg">
   <?php echo $menu->printHeader(); ?>
   <div id="menu">
      <?php echo $menu->menuData(); ?>
   </div>

   <div id="content">
      <div id="tools" class="maincontent">
         <div class="titelzeile">
            <div class='txt_tit'><a class="help_kanpaiclassic" href="<?php echo HELP_LINK; ?>/tools/schnittstellen/" target="_blank"></a>Schnittstellen</div>
         </div>

         <div class="content_box content_box_bottom">
            <div id="content_top"></div>

            <?php // Schnittstellen ?>
            <div id="schnittstellen" class="box_tools">
               <div class="box_left">
                  <div class="import_block">
                     <div class="sub_titel ellipsis">
                        <h2 class="txt_bez ellipsis">Artikel-Export</h2>
                        <a class="help_kanpaiclassic ci_color" href="<?php echo HELP_LINK; ?>/o4/artikel-csv/" target="_blank"></a>
                     </div>

                     <div class="schnitt_zeile">
                        <?php // Artikel-Export XML ?>
                        <span class="button txt_but xmlexport" onclick="Tools.exportArticle(this, 'flow_xml_html');">Flow XML</span>
                        &nbsp;
                        <?php // Artikel-Export CSV HTML?>
                        <span class="button txt_but xml_export" onclick="Tools.exportArticle(this, 'flow_csv_html');"<?php echo (defined('CONF_ARTIKEL_EXPORT_DEACTIVATED') ? ' title="Download-Link: '.SHOP_URL.'/export/artikel_export_text.csv"' : ''); ?>>CSV mit html</span>
                        &nbsp;
                        <?php // Artikel-Export CSV Text ?>
                        <span class="button txt_but xml_export" onclick="Tools.exportArticle(this, 'flow_csv_text');"<?php echo (defined('CONF_ARTIKEL_EXPORT_DEACTIVATED') ? ' title="Download-Link: '.SHOP_URL.'/export/artikel_export_html.csv"' : ''); ?>>CSV ohne html</span>
                        <div class="clear"></div>
                     </div>

					<?php /*
                     <div class="schnitt_zeile">
                        <?php // Artikel-Export GX2 ?>
                        <span class="button txt_but xmlexport" onclick="Tools.exportArticle(this, 'gx2_csv');">GX2</span>
                        &nbsp;
                        <?php // Artikel-Export Dyn CSV ?>
                        <span class="button txt_but xmlexport" onclick="Tools.exportArticle(this, 'dyn_csv');">Dyn CSV</span>
                     </div>
					*/ ?>

                     <div class="schnitt_zeile">
                        <?php // Lexware-Export Artikel  XML ?>
                        <span class="button txt_but xmlexport" onclick="Tools.exportArticle(this, 'lex_artikel_xml');">Lexware XML</span>
                        &nbsp;
                        <?php // Lexware-Export Artikel  CSV ?>
                        <span class="button txt_but xmlexport" onclick="Tools.exportArticle(this, 'lex_artikel_csv');">Lexware CSV</span>
                     </div>
                  </div>

                  <div class="import_block">
                     <div class="sub_titel ellipsis">
                        <h2 class="txt_bez ellipsis">Bestellungen-Export</h2>
                        <a class="help_kanpaiclassic ci_color" href="<?php echo HELP_LINK; ?>/o10/lexware/" target="_blank"></a>
                     </div>

                     <div class="schnitt_zeile">
                        <?php // Bestellungen CSV in export erstellen ?>
                        <span class="button txt_but xmlexport" onclick="Tools.exportArticle(this, 'bestell_csv');">Bestell.-CSV</span>
                        &nbsp;
                        <?php // Alle Bestellungen löschen ?>
                        <span class="button_ci txt_but xmlexport" onclick="Tools.deleteBestellungen();">alle löschen</span>
                     </div>

                     <div class="schnitt_zeile">
                        <?php // Lexware-Export Bestellungen XML ?>
                        <span class="button txt_but xmlexport" onclick="Tools.exportArticle(this, 'lex_best_xml');">Lexware XML</span>
                        &nbsp;
                        <?php // Lexware-Export Bestellungen CSV ?>
                        <span class="button txt_but xmlexport" onclick="Tools.exportArticle(this, 'lex_best_csv');">Lexware CSV</span>
                     </div>
                  </div>

                  <div class="import_block">
                     <div class="sub_titel ellipsis">
                        <h2 class="txt_bez ellipsis">Buchungen-Export <span class="no_bold">(csv für EasyCashTax etc.)</span></h2>
                        <a class="help_kanpaiclassic ci_color" href="<?php echo HELP_LINK; ?>/o3/buchungen-bestellungen-csv/" target="_blank"></a>
                     </div>

                     <div class="schnitt_zeile_check">
                        <input type="checkbox" class="newdesign" id="datev2_ceck">
                        <label for="datev2_ceck">gemischte USt. 2-zeilig</label>
                     </div>

                     <div class="schnitt_zeile">
                        <?php // Export Buchungen letztes Jahr EasyCashTax ?>
                        <span class="button txt_but xmlexport" onclick="Tools.exportEasycash(this, 'easycash_y', ($('#datev2_ceck').prop('checked') ? 'on' : 'off'));">letztes Jahr</span>
                        &nbsp;
                        <?php // Export Buchungen letztes Quartal EasyCashTax ?>
                        <span class="button txt_but xmlexport" onclick="Tools.exportEasycash(this, 'easycash_q', ($('#datev2_ceck').prop('checked') ? 'on' : 'off'));">letztes Quartal</span>
                        &nbsp;
                        <?php // Export Buchungen letztes Jahr EasyCashTax ?>
                        <span class="button txt_but xmlexport" onclick="Tools.exportEasycash(this, 'easycash_m', ($('#datev2_ceck').prop('checked') ? 'on' : 'off'));">letzter Monat</span>
                        &nbsp;
                        <?php // Export Buchungen letzte EasyCashTax?>
                        <span class="button txt_but xmlexport" onclick="Tools.exportEasycash(this, 'easycash_a', ($('#datev2_ceck').prop('checked') ? 'on' : 'off'));">letzte</span>
                        <div class="clear"></div>
                     </div>

                     <div class="schnitt_zeile">
                        <?php // DATEV letztes Jahr ?>
                        <span class="button txt_but xmlexport" onclick="Tools.exportDatev(this, 'datev_y', ($('#datev2_ceck').prop('checked') ? 'on' : 'off'));">DATEV Jahr</span>
                        &nbsp;
                        <?php // DATEV letztes Quartal ?>
                        <span class="button txt_but xmlexport" onclick="Tools.exportDatev(this, 'datev_q', ($('#datev2_ceck').prop('checked') ? 'on' : 'off'));">DATEV Quartal</span>
                        &nbsp;
                        <?php // DATEV letzter Monat ?>
                        <span class="button txt_but xmlexport" onclick="Tools.exportDatev(this, 'datev_m', ($('#datev2_ceck').prop('checked') ? 'on' : 'off'));">DATEV Monat</span>
                        &nbsp;
                        <?php // DATEV letzte Buchungen ?>
                        <span class="button txt_but xmlexport" onclick="Tools.exportDatev(this, 'datev_a', ($('#datev2_ceck').prop('checked') ? 'on' : 'off'));">letzte</span>
                        <div class="clear"></div>
                     </div>
                  </div>
               </div>

               <div class="box_right">
                  <div class="import_block">
                     <div class="sub_titel ellipsis">
                        <h2 class="txt_bez ellipsis">Artikel-Import</h2>
                        <a class="help_kanpaiclassic ci_color" href="<?php echo HELP_LINK; ?>/o4/artikel-csv/" target="_blank"></a>
                     </div>

                     <div class="float_block">
                        <div class="float">
                           <div class="radio_block">
                              <input type="radio" class="newdesign" id="article_overwrite1" name="article_overwrite" value="y" <?php echo ($overwrite == 'y' ? 'checked="checked"' : ''); ?> />
                              <label for="article_overwrite1">überschreiben</label>
                              <br />
                              <input type="radio" class="newdesign" id="article_overwrite2" name="article_overwrite" value="n" <?php echo ($overwrite == 'n' ? 'checked="checked"' : ''); ?> />
                              <label for="article_overwrite2">neue Artikel-ID</label>
                           </div>
                        </div>

                        <div class="float">
                           <div class="radio_block">
                              <input type="radio" class="newdesign" id="cat_name1" name="cat_name" value="n" <?php echo ($catname != 'y' ? 'checked="checked"' : ''); ?> />
                              <label for="cat_name1">Kategorie-ID</label>
                              <br />
                              <input type="radio" class="newdesign" id="cat_name2" name="cat_name" value="y" <?php echo ($catname == 'y' ? 'checked="checked"' : ''); ?> />
                              <label for="cat_name2">Kategorie-Name</label>
                           </div>
                        </div>

                        <div class="float">
                           <div class="radio_block">
                              <input type="checkbox" class="newdesign" id="picload_check" name="picload_check" id="picload_check" />
                              <label for="picload_check">mit Bilddateien</label>
                           </div>
                        </div>
                        <div class="clear"></div>
                     </div>

                     <div class="schnitt_zeile">
                        <?php // Artikel-Import XML ?>
                        <span class="button txt_but xmlexport" onclick="Tools.uploadArticle(this, 'flow_xml', 'xml', 'picload');">Flow XML</span>
                        &nbsp;
                        <?php // Artikel-Import CSV netto?>
                        <span class="button txt_but xmlexport" onclick="Tools.uploadArticle(this, 'flow_csv_netto', 'csv', 'picload');">CSV netto</span>
                        &nbsp;
                        <?php // Artikel-Import CSV ohne html?>
                        <span class="button txt_but xmlexport" onclick="Tools.uploadArticle(this, 'flow_csv_brutto', 'csv', 'picload');">CSV brutto</span>
                        <div class="clear"></div>
                     </div>

                     <div class="schnitt_zeile">
                        <?php /*
						<?php // Artikel-Import GX2 ?>
                        <span class="button txt_but xmlexport" onclick="Tools.uploadArticle(this, 'gx2_csv', 'csv');">GX2</span>
                        &nbsp;
                        <?php // Artikel-Import Dyn CSV ?>
                        <span class="button txt_but xmlexport" onclick="Tools.uploadArticle(this, 'dyn_csv', 'csv');">Dyn CSV</span>
                        &nbsp;
					    */ ?>
                        <?php // Artikel löschen ?>
                        <span class="button_ci txt_but xmlexport" onclick="Tools.allArticlesDelete();">alle löschen</span>
                     </div>
                     <div class="clear"></div>
                  </div>

                  <div class="import_block">
                  <?php if (defined('CONF_MODULE_EXTENDED')) { ?>
                     <div class="sub_titel ellipsis">
                        <h2 class="txt_bez ellipsis">CSV Lagermengen- &amp; Preisabgleich nach</h2>
                        <a class="help_kanpaiclassic ci_color" href="<?php echo HELP_LINK; ?>/o19/lager-preis-csv/" target="_blank"></a>
                     </div>

                     <div class="schnitt_zeile">
                       <?php // Lager-Export CSV mit ID ?>
                        <span class="button txt_but xmlexport" onclick="Tools.exportArticle(this, 'lager');">Export ID</span>
                        &nbsp;
                        <?php // Lager-Import CSV nach ID ?>
                        <span class="button txt_but xmlexport" onclick="Tools.uploadArticle(this, 'lager', 'csv');">Import ID</span>
                        &nbsp;
                        <?php // Lager-Export CSV mit Name ?>
                        <span class="button txt_but xmlexport" onclick="Tools.exportArticle(this, 'lager_name');">Export ArtName</span>
                         &nbsp;
                        <?php // Lager-Import CSV nach Name ?>
                        <span class="button txt_but xmlexport" onclick="Tools.uploadArticle(this, 'lager_name', 'csv');">Import ArtName</span>
                        &nbsp;
                        <?php // Lager-Export CSV mit ArtNr ?>
                        <span class="button txt_but xmlexport" onclick="Tools.exportArticle(this, 'lager_artnr');">Export ArtNr</span>
                         &nbsp;
                        <?php // Lager-Import CSV nach ArtNr ?>
                        <span class="button txt_but xmlexport" onclick="Tools.uploadArticle(this, 'lager_artnr', 'csv');">Import ArtNr</span>
                        <div class="clear"></div>
                     </div>
                  <?php } ?>
                  </div>

                  <div class="import_block">
                     <div class="sub_titel ellipsis">
                        <h2 class="txt_bez ellipsis">E-Mail Export für Design-Newsletter: <a class="link fliesstext ci_color" href="https://www.newslettersystem.com" target="_blank"> www.newslettersystem.com</a></h2>
                        <a class="help_kanpaiclassic ci_color" href="<?php echo HELP_LINK; ?>/o33/design-newslettersystem/" target="_blank"></a>
                     </div>

                     <div class="schnitt_zeile">
                       <?php // Email-Export CSV ?>
                        <span class="button txt_but xmlexport" onclick="Tools.exportArticle(this, 'newsletter');">CSV-Export</span>
                        &nbsp;
                        <?php // E-Mail-Import CSV ?>
                        <span class="button txt_but xmlexport" onclick="Tools.uploadArticle(this, 'newsletter', 'csv');">CSV-Import</span>
                        <div class="clear"></div>
                     </div>
                  </div>

                  <div class="import_block">
                     <div class="sub_titel ellipsis">
                        <h2 class="txt_bez ellipsis">Kunden Export</h2>
                        <a class="help_kanpaiclassic ci_color" href="<?php echo HELP_LINK; ?>/o32/kunden-csv/" target="_blank"></a>
                     </div>

                     <div class="schnitt_zeile">
                        <?php // Email-Export CSV ?>
                        <span class="button txt_but xmlexport" onclick="Tools.exportArticle(this, 'kunden');">CSV-Export</span>
                        &nbsp;
                        <?php // Artikel-Import CSV ?>
                        <span class="button txt_but xmlexport" onclick="Tools.uploadArticle(this, 'kunden', 'csv');">CSV-Import</span>
                     </div>
                  </div>
               </div>
               <div class="clear"></div>
            </div>
         </div>

         <?php // Module DHL-Händler ?>
         <?php if (defined('CONF_MODULE_DHLHAENDLER')) { ?>
         <?php $dhl             = KANPAICLASSIC\Control::getModuleDhlHaendler(); ?>
         <?php $opt_array       = $dhl->getBestellungenByDate(date('Y-m-d')); ?>
         <?php $dhl_api_version = (int)KANPAICLASSIC\Helper::getData('dhl_api_version', 2); ?>
         <?php $dhl_paketart = (isset($_SESSION['dhl_paketart']) ? (int)$_SESSION['dhl_paketart'] : 1); ?>
         <div class="content_box_abstand"></div>
         <div class="titelzeile">
            <div class='txt_tit'><a class="help_kanpaiclassic" href="<?php echo HELP_LINK; ?>/o31/dhl-geschaeftskunden/" target="_blank"></a>DHL Geschäftskunden</div>
         </div>
         <div class="box_tools content_box content_box_bottom">
            <div id="dhl_haendler">
               <div class="box_left">
                  <div class="tools_line dhl_line1">
                     <div class="dhl_pos_left right">Gewicht der Verpackung</div>
                     <div class="dhl_pos_right">
                        <input type="text" id="dhl_gewicht" class="txt_inp right" value="<?php echo KANPAICLASSIC\Helper::getData('dhl_gewicht', ''); ?>" />
                        <div class="dhl_pos_g">g</div>
                     </div>
                     <div class="clear"></div>
                  </div>

                  <div class="tools_line dhl_line1">
                     <div class="dhl_pos_left right">Bestellungen über 550 €</div>
                     <div class="dhl_pos_right">
                        <span class="selectbox30">
                           <select id="dhl_versicherung" name="dhl_versicherung">
                              <option value="n"<?php echo (KANPAICLASSIC\Helper::getData('dhl_versicherung', 'n') == 'n' ? ' selected="selected"' : ''); ?>>standard Versicherung</option>
                              <option value="y"<?php echo (KANPAICLASSIC\Helper::getData('dhl_versicherung', 'n') == 'y' ? ' selected="selected"' : ''); ?>>höher versichert</option>
                           </select>
                        </span>
                     </div>
                     <div class="clear"></div>
                  </div>

                  <div class="tools_line dhl_line1 button_line">
                     <div class="dhl_pos_left right">&nbsp;</div>
                     <div class="dhl_pos_right">
                        <span id="dhl_savegewicht" class="button_ci txt_but">speichern</span>
                     </div>
                  </div>

                  <div class="tools_line"></div>

                  <div class="tools_line dhl_line1">
                     <span class="txt_bez">Bestellungen mit Bestelldatum vom:&nbsp;&nbsp;</span>
                     <div id="datepicker_starter" class="fas fa-calendar-alt pointer">
                        <input type="text" id="dhl_datum" value="<?php echo date('Y-m-d'); ?>" />
                     </div>
                     <div class="clear"></div>
                  </div>

                  <div class="tools_line dhl_line1">
                     <div class="dhl_pos_left right">ab</div>
                     <div class="dhl_pos_right">
                        <span class="selectbox30">
                           <select id="dhl_start"><?php echo $opt_array['start']; ?></select>
                        </span>
                     </div>
                     <div class="clear"></div>
                  </div>

                  <div class="tools_line dhl_line1">
                     <div class="dhl_pos_left right">bis</div>
                     <div class="dhl_pos_right">
                        <span class="selectbox30">
                           <select id="dhl_ende"><?php echo $opt_array['ende']; ?></select>
                        </span>
                     </div>
                     <div class="clear"></div>
                  </div>

                  <div class="tools_line dhl_line1 dhl_line_abstand">
                     <div class="dhl_pos_left right">Paketgröße (L x B x H)</div>
                     <div class="dhl_pos_right">
                        <input type="text" class="txt_inp right" id="dhl_laenge" value="<?php echo (isset($_SESSION['dhl_laenge']) ? $_SESSION['dhl_laenge'] : '60'); ?>" />
                        <input type="text" class="txt_inp right" id="dhl_breite" value="<?php echo (isset($_SESSION['dhl_breite']) ? $_SESSION['dhl_breite'] : '30'); ?>" />
                        <input type="text" class="txt_inp right" id="dhl_hoehe"  value="<?php echo (isset($_SESSION['dhl_hoehe']) ? $_SESSION['dhl_hoehe'] : '30'); ?>" />
                        <div class="dhl_pos_g">cm</div>
                     </div>
                     <div class="clear"></div>
                  </div>

                  <div class="tools_line dhl_line1">
                     <div class="dhl_pos_left right">Paketart</div>
                     <div class="dhl_pos_right">
                        <span class="selectbox30">
                           <select id="dhl_paketart" name="dhl_paketart">
                              <option value="1"<?php echo ($dhl_paketart == 1 ? ' selected="selected"' : ''); ?>>DHL Paket (V01PAK)</option>
                              <option value="2"<?php echo ($dhl_paketart == 2 ? ' selected="selected"' : ''); ?>>DHL Europaket (V54EPAK)</option>
                              <option value="3"<?php echo ($dhl_paketart == 3 ? ' selected="selected"' : ''); ?>>DHL Paket International (V53WPAK)</option>
                              <option value="4"<?php echo ($dhl_paketart == 4 ? ' selected="selected"' : ''); ?>>DHL Paket Connect (V55PAC)</option>
                              <?php if ($dhl_api_version == 3) { ?>
                              <option value="5"<?php echo ($dhl_paketart == 5 ? ' selected="selected"' : ''); ?>>Warenpost (V62WP)</option>
                              <?php } ?>
                           </select>
                        </span>
                     </div>
                     <div class="clear"></div>
                  </div>

                  <div class="tools_line dhl_line1 button_line">
                     <div class="dhl_pos_left right">&nbsp;</div>
                     <div class="dhl_pos_right">
                       <!-- <span id="dhl_printlabel_send" class="button_ci txt_but">Übertragen</span> -->
                        <span id="dhl_printlabel_print" class="button_ci txt_but">Label erstellen</span>
                        <!-- <span id="dhl_printlabel_csv" class="button_ci txt_but">CSV-Datei</span> -->
                     </div>
                     <div class="clear"></div>
                  </div>

                  <div class="box_abstand"></div>
               </div>

               <div class="box_right">
                  <div class="tools_line"><span class="txt_bez">Schnittstellenbedingungen</span></div>

                  <ul class="dhl_text">
                     <li>Inlandlieferungen</li>
                     <li>Sammelposten</li>
                     <li>Pakete bis maximal 120x60x60cm</li>
                     <li>Pakete werden täglich automatisch abgeholt</li>
                     <li>jedem Artikel muss ein Versandgewicht gegeben werden</li>
                  </ul>

                  <div class="tools_line dhl_line2">
                     <div class="dhl_pos_left">DHL KundenNr.</div>
                     <div class="dhl_pos_right">
                        <input type="text" class="txt_inp" id="dhl_is_ekp" value="<?php echo KANPAICLASSIC\Helper::getData('dhl_is_ekp', ''); ?>" />
                     </div>
                  </div>

                  <div class="tools_line dhl_line2">
                     <div class="dhl_pos_left">Teilnehmer-Nr.</div>
                     <div class="dhl_pos_right">
                        <input type="text" id="dhl_teilnehmer" class="txt_inp" value="<?php echo KANPAICLASSIC\Helper::getData('dhl_teilnehmer', '01'); ?>" />
                     </div>
                     <div class="clear"></div>
                  </div>

                  <div class="tools_line dhl_line2">
                     <div class="dhl_pos_left">Benutzername</div>
                     <div class="dhl_pos_right">
                        <input type="text" class="txt_inp" id="dhl_is_user" value="<?php echo KANPAICLASSIC\Helper::getData('dhl_is_user', ''); ?>" />
                     </div>
                  </div>

                  <div class="tools_line dhl_line2">
                     <div class="dhl_pos_left">Passwort</div>
                     <div class="dhl_pos_right">
                        <input type="text" class="txt_inp" id="dhl_is_sign" value="<?php echo KANPAICLASSIC\Helper::getData('dhl_is_sign', ''); ?>" />
                     </div>
                  </div>

                  <div class="tools_line dhl_line2">
                     <div class="dhl_pos_left">API-Version</div>
                     <div class="dhl_pos_right">
                        <input type="radio" class="newdesign dhl_api_version" id="dhl_api_version_2" name="dhl_api_version" value="2" <?php echo ((int)KANPAICLASSIC\Helper::getData('dhl_api_version', 2) == 2 ? ' checked="checked"' : ''); ?> />
                        <label for="dhl_api_version_2"></label>2
                        <input type="radio" class="newdesign dhl_api_version" id="dhl_api_version_3" name="dhl_api_version" value="3" <?php echo ((int)KANPAICLASSIC\Helper::getData('dhl_api_version', 2) == 3 ? ' checked="checked"' : ''); ?> />
                        <label for="dhl_api_version_3"></label>3
                        <input type="hidden" id ="dhl_old_api" value="<?php echo KANPAICLASSIC\Helper::getData('dhl_api_version', 2); ?>" />
                     </div>
                  </div>

                  <div class="tools_line dhl_line2 button_line">
                     <div class="dhl_pos_left">&nbsp;</div>
                     <div class="dhl_pos_right">
                        <span id="dhl_params" class="button txt_but">aktivieren</span>
                     </div>
                  </div>
                  <div class="clear"></div>
               </div>
               <div class="clear"></div>
            </div>
         </div>
         <?php } ?>

         <?php if (defined('CONF_MODULE_EINSASHOP') && !defined('CONF_MODULE_PORTAL')) { ?>
         <div class="content_box_abstand"></div>
         <div class="titelzeile">
            <div class='txt_tit'><a class="help_kanpaiclassic" href="<?php echo HELP_LINK; ?>/o48/einsashop/" target="_blank"></a><?php echo CONF_MODULE_IMPORT_NAME; ?></div>
         </div>
         <div class="box_tools content_box content_box_bottom">
            <div id="einsashop" class="tools_line button_line">
               <span class="einsaschop_text">Link zur Großhandels-CSV</span>
               <span class="einsaschop_link"><input type="text" class="txt_inp" name="cronjob_url" id="cronjob_url" value="<?php echo (defined('CONF_MODULE_IMPORT_URL') ? CONF_MODULE_IMPORT_URL : ''); ?>" /></span>
               <input type="hidden" id="einsashop_script"      value="<?php echo CONF_MODULE_IMPORT_SCRIPT; ?>" />
               <input type="hidden" id="einsashop_overwrite"   value="<?php echo CONF_MODULE_IMPORT_OVERWRITE; ?>" />
               <input type="hidden" id="einsashop_images"      value="<?php echo CONF_MODULE_IMPORT_IMAGES; ?>" />
               <input type="hidden" id="einsashop_haendler_id" value="0" />
               <span class="einsaschop_btn button txt_but right" onclick="Tools.einsashopImport();">CSV-Import</span>
            </div>
            <div class="clear"></div>
         </div>
         <?php } ?>

         <?php if (defined('CONF_MODULE_HAENDLERBUND') || defined('CONF_MODULE_ITRECHTKANZLEI')) { ?>
         <div class="content_box_abstand"></div>
         <div class="titelzeile">
            <div class='txt_tit'><a class="help_kanpaiclassic" href="https://help.kanpaiclassic.com/o78/haendlerbund-it-recht-kanzlei-rechtstexte-api/" target="_blank"></a>Rechtstexte</div>
         </div>
         <div class="box_tools content_box">
            <?php if (defined('CONF_MODULE_HAENDLERBUND')) { ?>
            <?php require_once SHOP_PATH.'/classes/modules/rechtstexte/haendlerbund.module.php'; ?>
            <?php $haendlerbund = new \KANPAICLASSIC\KANPAICLASSIC_modulHaendlerbund(); ?>
            <div id="heandlerbund"><?php echo $haendlerbund->tools(); ?></div>
            <div class="clear"></div>
            <?php } ?>
            <?php if (defined('CONF_MODULE_HAENDLERBUND') || defined('CONF_MODULE_ITRECHTKANZLEI')) { ?>
            <div class="rechtstexte_abstand"></div>
            <?php } ?>
            <?php if (defined('CONF_MODULE_ITRECHTKANZLEI')) { ?>
               <?php require_once SHOP_PATH.'/classes/modules/rechtstexte/itrechtkanzlei.module.php'; ?>
               <?php $itrechtkanzlei = new \KANPAICLASSIC\KANPAICLASSIC_modulItrechtkanzlei(); ?>
            <div id="itrechtkanzlei"><?php echo $itrechtkanzlei->tools(); ?></div>
            <div class="clear"></div>
            <?php } ?>
         </div>
         <?php } ?>

         <?php if (defined('CONF_MODULE_MEINBUERO')) { // Daten werden als Datei im Modul gespeichert ?>
         <div class="content_box_abstand"></div>
         <div class="titelzeile">
            <div class='txt_tit'><a class="help_kanpaiclassic" href="<?php echo HELP_LINK; ?>/o9/wiso-mein-buero/" target="_blank"></a>WISO mein Büro</div>
            <div class="save_button" onclick="Tools.meinBueroSave();">speichern</div>
         </div>
         <div class="box_tools content_box content_box_bottom">
            <div id="meinbuero">
               <div class="mb_url tools_line">Internetadresse für die Shopanbindung: <?php echo SHOP_URL.'/classes/modules/wiso_mein_buero/'; ?></div>
               <div class="tools_line">
                  <span>
                     Identifikationskennung&nbsp;
                     <input type="text" class="social_link inline" id="mb_id" value="<?php echo \KANPAICLASSIC\Helper::getData('mb_id', ''); ?>" />
                     &nbsp;&nbsp;&nbsp;
                  </span>

                  <span>
                  <?php if (extension_loaded('openssl')) { ?>
                     <input type="checkbox" class="newdesign" id="mb_pass_check"<?php echo (\KANPAICLASSIC\Helper::getData('mb_pass_check', 'n') == 'y' ? ' checked="checked"' : ''); ?> />
                     <label for="mb_pass_check">Verschlüsselung</label>
                     <span class="mb_pass_hidden">&nbsp;Passwort&nbsp;<input type="password" id="mb_pass" value="<?php echo \KANPAICLASSIC\Helper::getData('mb_pass', ''); ?>" onmousedown="$(this).attr('type', 'text');" onmouseup="$(this).attr('type', 'password');" /></span>
                  <?php } else { ?>
                     Verschlüselung auf diesem Server nicht möglich
                  <?php } ?>
                     &nbsp;&nbsp;&nbsp;
                  </span>
                  <span>
                     <input type="checkbox" class="newdesign inline" id="mb_gesamtbrutto"<?php echo (\KANPAICLASSIC\Helper::getData('mb_gesamtbrutto', 'n') == 'y' ? ' checked="checked"' : ''); ?> />
                     <label for="mb_gesamtbrutto">Bruttosumme übertragen (von WISO nicht erlaubt)</label>
                  </span>
               </div>
                     <div class="tools_line button_line">
                     Bestellungen <span class="button txt_but" onclick="Tools.cleanOrders();">bereinigen</span>
                  </div>

               <div class="clear"></div>
            </div>
            <div class="clear"></div>
         </div>
         <?php } ?>

         <?php if (defined('CONF_MODULE_ORGAMAX')) { // Daten werden als Datei im Modul gespeichert ?>
         <div class="content_box_abstand"></div>
         <div class="titelzeile">
            <div class='txt_tit'><a class="help_kanpaiclassic" href="<?php echo HELP_LINK; ?>/o72/orgamax/" target="_blank"></a>orgaMAX</div>
            <div class="save_button" onclick="Tools.orgamaxSave();">speichern</div>
         </div>
         <div class="box_tools content_box content_box_bottom">
            <div id="meinbuero">
               <div class="mb_url tools_line">Internetadresse für die Shopanbindung: <?php echo SHOP_URL.'/classes/modules/orgamax/'; ?></div>
               <div class="tools_line">
                  <span>
                     Identifikationskennung&nbsp;
                     <input type="text" class="social_link inline" id="orgamax_id" value="<?php echo \KANPAICLASSIC\Helper::getData('orgamax_id', ''); ?>" />
                     &nbsp;&nbsp;&nbsp;
                  </span>

                  <span>
                  <?php if (extension_loaded('openssl')) { ?>
                     <input type="checkbox" class="newdesign" id="orgamax_pass_check"<?php echo (\KANPAICLASSIC\Helper::getData('orgamax_pass_check', 'n') == 'y' ? ' checked="checked"' : ''); ?> />
                     <label for="orgamax_pass_check">Verschlüsselung</label>
                     <span class="mb_pass_hidden">&nbsp;Passwort&nbsp;<input type="password" id="orgamax_pass" value="<?php echo \KANPAICLASSIC\Helper::getData('orgamax_pass', ''); ?>" onmousedown="$(this).attr('type', 'text');" onmouseup="$(this).attr('type', 'password');" /></span>
                  <?php } else { ?>
                     Verschlüselung auf diesem Server nicht möglich
                  <?php } ?>
                     &nbsp;&nbsp;&nbsp;
                  </span>
                  <span>
                     <input type="checkbox" class="newdesign inline" id="orgamax_gesamtbrutto"<?php echo (\KANPAICLASSIC\Helper::getData('orgamax_gesamtbrutto', 'n') == 'y' ? ' checked="checked"' : ''); ?> />
                     <label for="orgamax_gesamtbrutto">Bruttosumme übertragen (von orgaMAX nicht erlaubt)</label>
                  </span>
               </div>
               <div class="clear"></div>
            </div>
            <div class="clear"></div>
         </div>
         <?php } ?>

         <?php // Portale - Nur Shop / nicht Portal?>
         <?php if (!defined('CONF_MODULE_PORTAL')) { ?>
         <div class="content_box_abstand easy"></div>
         <div class="titelzeile easy">
            <div class='txt_tit'><a class="help_kanpaiclassic" href="<?php echo HELP_LINK; ?>/tools/schnittstellen/" target="_blank"></a>Portale</div>
         </div>
         <div class="content_box content_box_bottom easy">
         <div id="portale" class="box_tools">
            <div id="portale1" class="box_left">
               <?php $bg_col = true; ?>
               <?php // 77Marken ?>
               <div id="_77marken" class="portal_block <?php echo ($bg_col ? 'bg_odd' : 'bg_even'); ?>">
                  <div class="sub_titel ellipsis">
                     <h2 class="txt_bez ellipsis"><a class="txt_bez" href="http://www.77marken.de" title="Link öffnet in neuem Fenster" target="_blank">77marken.de</a> - Artikel aller Art</h2>
                     <a class="help_kanpaiclassic ci_color" href="<?php echo HELP_LINK; ?>/o6/77marken/" target="_blank"></a>
                  </div>
                  <ul>
                     <li>unter KATEGORIEN ordnen Sie Ihre passenden Kategorien zu</li>
                     <li>dann CSV exportieren und kostenfrei hochladen (als Verkäufer anmelden):</li>
                     <li><a href="http://www.77marken.de" class="link ci_color" title="Link öffnet in neuem Fenster" target="_blank">www.77marken.de</a></li>
                  </ul>
                  <div>
                     <!-- <a onclick="$('#modeobadja').val('csv'); forms.toolsobadja.submit(); return false;" href=''> -->
                     <span onclick="Tools.exportArticle(this, 'portal_csv');">
                        <span class="button txt_but xmlexport">CSV-Export</span>
                     </span>
                     &nbsp;
<!--                     <a onclick="$('#modeobadja').val('xml'); forms.toolsobadja.submit(); return false;" href=''> -->
                     <span onclick="Tools.exportArticle(this, 'portal_xml');">
                        <span class="button txt_but xmlexport">XML-Export</span>
                     </span>
                  </div>
               </div>

               <?php $bg_col = !$bg_col; ?>
               <?php // Google-Shopping ?>
               <div id="google_shopping" class="portal_block <?php echo ($bg_col ? 'bg_odd' : 'bg_even'); ?>">
                  <div class="sub_titel">
                     <a class="help_kanpaiclassic ci_color" href="<?php echo HELP_LINK; ?>/o15/google-shopping/" target="_blank"></a>
                     <input type="checkbox" class="newdesign" id="google_shopping_check" name="google_shopping_check" <?php echo $data->schnittstellen == 'y' ? ' checked="checked"' : ''; ?> />
                     <label for="google_shopping_check"></label>
                     <span class="txt_bez">Google-Shopping</span>
                  </div>
                  <div class="tools_line">Bitte vor Export prüfen:</div>
                  <ul>
                     <li><span>Ist der Shop auf USt eingestellt? Google nimmt nur Preise mit USt.</span></li>
                     <li><span>Sind bei jedem Artikel <span class="txt_red">alle</span> Daten ausgefüllt, inkl. ind. Versandpreis und -gewicht?</span></li>
                     <li><span>Sind das 1. und 2. Artikelbild hochgeladen?</span></li>
                  </ul>
                  <div class="tools_line">
                     <div class="google_shopping_left">Zielland</div>
                     <div class="google_shopping_right">
                        <div class="txt_inp"><?php echo $this->getIsoList(); ?></div>
                     </div>
                  </div>
                  <div class="tools_line">
                     <div class="google_shopping_left">Währung</div>
                     <div class="google_shopping_right">
                        <div class="txt_inp"><?php echo $this->getWaehrungList(); ?></div>
                     </div>
                  </div>
                  <?php // Google-Export CSV/XML ?>
                  <div class="tools_line button_line">
                     <span class="button txt_but" onclick="Tools.googleExport('csv');">CSV-Export</span>
                  </div>
                  <div class="clear"></div>
                  <div class="tools_line button_line">
                     <div class="button_ci txt_but" onclick="Tools.saveGoogle();">speichern</div>
                  </div>
               </div>

               <?php // Zugangsdaten für Module ebay / ebay_template und ebay_orders ?>
               <?php if (defined('CONF_MODULE_EBAY')) { ?>
               <?php $bg_col = !$bg_col; ?>
               <div id="ebay_module" class="portal_block <?php echo ($bg_col ? 'bg_odd' : 'bg_even'); ?>">
                  <div class="sub_titel">
                     <a class="help_kanpaiclassic ci_color" href="<?php echo HELP_LINK; ?>/o16/ebay-modul/" target="_blank"></a>
                     <input type="checkbox" class="newdesign" id="ebay_api" name="ebay_api" <?php echo $this->params->firma['ebay_api'] == 'y' ? ' checked="checked"' : ''; ?> />
                     <label for="ebay_api"></label>
                     <span  class="txt_bez">Ebay-Schnittstelle</span>
                  </div>

                  <div class="tools_line button_line">
                     <div class="ebay_left">
                        <div id="ebay_login" class="button txt_but" onclick="Ebay.toolsLoad();"><?php echo $this->params->firma['ebay_token'] != '' ? 'Modul laden' : 'Ebay Test'; ?></div>
                     </div>
                     <div class="ebay_right">
                        <span class="ebay_token fliesstext">Token abgelaufen?</span>
                        <div id="ebay_reset" class="button txt_but" onclick="Ebay.toolsReset();">Reset</div>
                        <?php if ($this->params->firma['ebay_token'] !== '') { ?>
                           <div>Anschließend auf &bdquo;Ebay-Test&rdquo; klicken</div>
                           <br />
                        <?php } else { ?>
                           <div>Anschließend auf &bdquo;Ebay-Test&rdquo; klicken</div>
                        <?php } ?>
                     </div>
                  </div>
                  <div id="ebay_shop" class="<?php echo (KANPAICLASSIC\Helper::getData('ebayOptionsList', '') != '' ? 'listsloaded ' : ''); ?>button txt_but" onclick="Ebay.toolsShopOptions(false);">Ebay-Shop</div>
                  <div id="ebay_options"></div>
                  <div class="tools_line button_line">
<?php
//      $html .= '<div class="button_unten">';
//      $html .= '   <div class="button txt_but" onclick="Ebay.toolsShopOptionsSave();">Speichern</div>';
//      $html .= '   <div class="clear"></div>';
//      $html .= '</div>';
?>
                     <div class="button_ci txt_but" onclick="Tools.saveEbay(); Ebay.toolsShopOptionsSave();">speichern</div>
                  </div>
               </div>
               <?php } ?>

               <?php // Amazon-Orders ?>
               <?php if (defined('CONF_MODULE_AMAZONORDERS')) { ?>
               <?php $amazon_orders = \KANPAICLASSIC\Control::getModuleAmazonorders(); ?>
               <?php $bg_col = !$bg_col; ?>
               <div id="module_amazonorders" class="portal_block <?php echo ($bg_col ? 'bg_odd' : 'bg_even'); ?>">
                  <?php echo $amazon_orders->getTools(); ?>
               </div>
               <?php } ?>

               <?php if (defined('CONF_MODULE_BILLBEE')) { ?>
               <?php $bg_col = !$bg_col; ?>
               <div id="module_billbee" class="portal_block <?php echo ($bg_col ? 'bg_odd' : 'bg_even'); ?>">
                  <?php $billbee = KANPAICLASSIC\Control::getModuleBillbee(); ?>
                  <?php echo $billbee->getTools(); ?>
               </div>
               <?php } ?>
               <div class="clear"></div>
            </div>

            <div id="portale2" class="box_right">
            <?php $bg_col = false; ?>

            <?php // Daten von ImExport::getExport(); Weitere Portale vorhanden aber auskommentiert ?>
            <?php if (is_array($export) && count($export) > 0) { ?>
               <?php foreach ($export as $k => $v) { ?>
                  <?php $bg_col = !$bg_col; ?>
               <div class="portal_block <?php echo ($bg_col ? 'bg_odd' : 'bg_even'); ?>">
                  <div class="titelzeile2">
                     <a class="help ci_color" href="<?php echo HELP_LINK; ?>/kapitel07portale.html" target="_blank"></a>
                     <h2 class="txt_bez"><?php echo $export[$k][0]; ?></h2>
                     <?php if ($cron && $export[$k][6] == 'y') { ?>
                     <div class="cron_export">
                        <input type="checkbox" class="newdesign" id="export_<?php echo $k; ?>" class="newdesign" id="" <?php echo ($export[$k][7] == 'y' ? 'checked="checked" ' : ''); ?>/>
                        <label for="export_<?php echo $k; ?>">automatisch bereitstellen</label>
                     </div>
                     <?php } ?>
                     <span class="button txt_but right" onclick="Tools.exportShops(this, '<?php echo $export[$k][1]; ?>');"><?php echo $export[$k][3]; ?></span>
                  </div>
                  <?php if ($export[$k][5] != '') { ?>
                  <div class="tools_line">Letzter Export: <?php echo $export[$k][5]; ?></div>
                  <?php } ?>
                  <div class="tools_line"><?php echo $export[$k][4]; ?></div>
               </div>
               <?php } ?>
            <?php } ?>

               <div class="portal_block">
                  <a href="<?php echo HELP_LINK; ?>/tools/schnittstellen/" target="_blank" ><img class="portale_jpg" src="<?php echo HELP_LINK; ?>/portale.jpg" alt="" /></a>
               </div>
            </div>
            <div class="clear"></div>
         </div>
         </div>
         <?php } ?>
      </div>
   </div>
   <?php $menu->footer(); ?>
</div>

<script>
var langs         = '<?php echo implode(';', $this->params->langs); ?>'; // vorhandene Sprachen - Nicht bei allen Templates notwendig
var sel_lang      = 'deu'; // gewählte Sprache - nicht bei allen Templates notwendig
var default_lang  = '<?php echo $this->params->default_lang; ?>';
var admin_url_idx = '<?php echo ADMIN_URL_IDX; ?>';
var admin_url     = '<?php echo ADMIN_URL; ?>';
var shopurl_idx   = '<?php echo SHOP_URL_IDX; ?>';
var shop_url      = '<?php echo SHOP_URL; ?>';
var template_url  = '<?php echo TEMPLATE_URL; ?>';
var max_file_size = '<?php echo max(KANPAICLASSIC\Helper::mbytesToBytes(ini_get('upload_max_filesize')), KANPAICLASSIC\Helper::mbytesToBytes(ini_get('post_max_size'))); ?>';
var editor_css    = "<?php echo TEMPLATE_URL; ?>/css/editor.css";
var cronjob       = <?php echo (is_file(SHOP_PATH.'/cronjob.php') ? 'true' : 'false') ?>;
</script>

<script src="<?php echo SHOP_URL;  ?>/js/jquery3.min.js"></script>
<script src="<?php echo SHOP_URL;  ?>/js/jquery-ui.min.js"></script>
<script src="<?php echo ADMIN_URL; ?>/js/admin.js"></script>
<script src="<?php echo ADMIN_URL; ?>/js/jquery.minicolors.min.js"></script>
</body>
</html>
