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
$menu           = KANPAICLASSIC\Control::getMenu();
$admin_config   = $menu->loadDesign();

?><!DOCTYPE html>
<html lang="de">
<head>
<title>Kanpai Classic <?php echo (!defined('CONF_MODULE_PORTAL') ? 'Shopsystem' : 'Shopportal'); ?> - Administration - Steuer &amp; Gewerbe</title>
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
      <div id="titelzeile" class="titelzeile">
         <div class='txt_tit'><a class="help_kanpaiclassic" href="<?php echo HELP_LINK; ?>/einstellungen/steuer-gewerbe/" target="_blank"></a>Großhandel & Kleingewerbe</div>
         <div class="save_button" onclick="forms.steuerform.submit();">speichern</div>
      </div>

      <div id="steuer" class="maincontent content_box content_box_bottom">
         <form method="post" id="steuerform" action="<?php echo ADMIN_URL_IDX; ?>/steuer/update">
            <div class="steuer_block">
               <div class="steuer_block_left">
                  <div class="steuer_line ellipsis">
                     <input type="checkbox" class="newdesign" name="tax_active" id="tax_active" <?php echo $this->steuer['tax_active'] == 'y' ? ' checked="checked"' : ''; ?> />
                     <label for="tax_active">Mehrwertsteuer aktiv</label>
                  </div>

                  <div class="steuer_line ellipsis">
                     <input type="checkbox" class="newdesign" name="tax_show" id="tax_show" <?php echo $this->steuer['tax_show'] == 'y' ? ' checked="checked"' : ''; ?> />
                     <label for="tax_show">Preise mit Mehrwertsteuer anzeigen</label>
                  </div>

                  <div class="steuer_line ellipsis">
                     <input type="checkbox" class="newdesign" name="price_login" id="price_login" <?php echo $this->steuer['price_login'] == 'y' ? ' checked="checked"' : ''; ?> />
                     <label for="price_login">Preise nur nach Anmeldung anzeigen</label>
                  </div>

                  <div class="steuer_line ellipsis">
                     <input type="checkbox" class="newdesign" name="account_manual" id="account_manual"<?php echo $this->steuer['account_manual'] == 'y' ? ' checked="checked"' : ''; ?> />
                     <label for="account_manual">Kundenaccounts manuell freigeben</label>
                  </div>

                  <div class="steuer_line ellipsis">
                     <input type="checkbox" class="newdesign" name="b2b_check" id="b2b_check"<?php echo $this->steuer['b2b_check'] == 'y' ? ' checked="checked"' : ''; ?> />
                     <label for="b2b_check">B2B (keine Widerrufs-PDF in E-Mail & Webseite)</label>
                  </div>

                  <div class="steuer_line ellipsis">
                     <input type="checkbox" class="newdesign" name="b2b_widerruf" id="b2b_widerruf" <?php echo $this->steuer['b2b_widerruf'] == 'y' ? ' checked="checked"' : ''; ?> />
                     <label for="b2b_widerruf">Widerruflink auf Artikeldetailseite</label>&nbsp;<span class="help ci_color" title="Pflicht, wenn Sie mehr als ein Widerrufsrecht anbieten (z.B. Downloads / Spedition / Dienstleistung"></span>
                  </div>

                  <div class="steuer_line ellipsis">
                     <input type="checkbox" class="newdesign" name="kleingewerbe" id="kleingewerbe" <?php echo $this->steuer['kleingewerbe'] == 'y' ? ' checked="checked"' : ''; ?> />
                     <label for="kleingewerbe">Kleingewerbetext in Rechnung anzeigen</label>
                  </div>
               </div>

               <div class="steuer_block_right">
                  <div class="steuer_zeile txt_bez">Steuersätze</div>

                  <div class="steuer_zeile">
                     <div class="steuer_name">normal</div>
                     <div class="steuer_input"><input type="text" name="tax1" id="tax1" value="<?php echo $this->steuer['tax1']; ?>" /></div>
                     <div class="steuer_prozent">%</div>
                  </div>

                  <div class="steuer_zeile">
                     <div class="steuer_name">ermäßigt</div>
                     <div class="steuer_input"><input type="text" name="tax2" id="tax2" value="<?php echo $this->steuer['tax2']; ?>" /></div>
                     <div class="steuer_prozent">%</div>
                  </div>

                  <div class="steuer_zeile">
                     <div class="steuer_name">steuerfrei</div>
                     <div class="steuer_input">
                        <input type="hidden" name="tax3" id="tax3" value="<?php echo $this->steuer['tax3']; ?>" readonly />
                        <span id="tax3"><?php echo $this->steuer['tax3']; ?></span>
                     </div>
                     <div class="steuer_prozent">%</div>
                  </div>

                  <div class="steuer_zeile" style="display:none;">
                     <?php /*<input type="checkbox" name="tax_ch_check" id="tax_ch_check"<?php echo ($this->steuer['tax_ch_check'] == 'y' ? ' checked="checked"' : ''); ?> />
                     &nbsp;Schweiz umsatzsteuerfrei */ ?>
                     <input type="hidden" name="tax_ch_check" id="tax_ch_check" value="on" />
                  </div>

                  <div class="steuer_zeile" style="display:none;">
                     <div class="steuer_name">
                        <input type="checkbox" name="tax_eu_check" id="tax_eu_check"<?php echo ($this->steuer['tax_eu_check'] == 'y' ? ' checked="checked"' : ''); ?> />
                        Schweiz umsatzsteuerfrei
                     </div>
                  </div>
               </div>
               <div class="clear"></div>
            </div>

            <?php if (defined('CONF_MODULE_WEBSITE') || \defined('CONF_MODULE_MULTISHOP')) { ?>
            <div id="webseite" class="steuer_block">
               <?php if (defined('CONF_MODULE_WEBSITE')) { ?>
               <div class="steuer_block_left">
                  <div class="steuer_line txt_bez">Als Webseite betreiben</div>

                  <div class="steuer_line ellipsis">
                     <input type="checkbox" class="newdesign" id="hide_wk" name="hide_wk"<?php echo ($this->steuer['hide_wk'] == 'y'  ? ' checked="checked"' : ''); ?>/>
                     <label for="hide_wk">Warenkorb- & Merkliste-Button ausblenden</label>
                  </div>

                  <div class="steuer_line ellipsis">
                     <input type="checkbox" class="newdesign" id="hide_anm" name="hide_anm"<?php echo ($this->steuer['hide_anm'] == 'y'  ? ' checked="checked"' : ''); ?>/>
                     <label for="hide_anm">Anmelde-Button ausblenden</label>
                  </div>

                  <div class="steuer_line ellipsis">
                     <input type="checkbox" class="newdesign" id="frage_check" name="frage_check"<?php echo ($this->steuer['frage_check'] == 'y'  ? ' checked="checked"' : ''); ?>/>
                     <label for="frage_check">Button "Frage zum Produkt?" in Artikeln</label>
                  </div>

                  <div class="steuer_line ellipsis">
                     <input type="checkbox" class="newdesign" id="frage_check_objekt" name="frage_check_objekt"<?php echo (KANPAICLASSIC\Helper::getData('frage_check_objekt', 'n') == 'y'  ? ' checked="checked"' : ''); ?>/>
                     <label for="frage_check_objekt">Button "Frage zum Produkt?" in Objekten</label>
                  </div>
               </div>
               <?php } ?>

               <?php if (defined('CONF_MODULE_MULTISHOP')) { ?>
               <div class="steuer_block_right">
                  <div class="steuer_zeile txt_bez">Multishop</div>

                  <div class="steuer_line">
                     <input type="checkbox" class="newdesign" id="multishop"<?php echo (\KANPAICLASSIC\Helper::getData('multishop', 'n') == 'y' ? ' checked="checked"' : ''); ?> onchange="Steuer.multishopChange();" />
                     <label for="multishop"></label>
                     <span class="fas fa-pencil-alt pointer" onclick="Steuer.multishop();"></span>
                     andere Artikel-Datenbank nutzen
                  </div>
               </div>
               <?php } ?>
               <div class="clear"></div>
            </div>
            <?php } ?>
         </form>
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
</script>

<script src="<?php echo SHOP_URL;  ?>/js/jquery3.min.js"></script>
<script src="<?php echo SHOP_URL;  ?>/js/jquery-ui.min.js"></script>
<script src="<?php echo ADMIN_URL; ?>/js/admin.js"></script>
</body>
</html>
