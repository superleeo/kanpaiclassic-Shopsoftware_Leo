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
<title>Kanpai Classic <?php echo (!defined('CONF_MODULE_PORTAL') ? 'Shopsystem' : 'Shopportal'); ?> - Administration - Länder</title>
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
         <div class='txt_tit'><a class="help_kanpaiclassic" href="<?php echo HELP_LINK; ?>/einstellungen/laender-sprachen-waehrung/" target="_blank"></a>Ländereinstellungen</div>
         <div class="save_button" onclick="forms.laenderform.submit()">speichern</div>
      </div>

      <div id="laender" class="maincontent">
         <div class="content_box content_box_bottom">
            <form method="post" id="laenderform" action="<?php echo ADMIN_URL_IDX; ?>/laender/update">
               <div id="sprachen">
                  <div class="waehrung_zeile txt_bez">Sprachen</div>
                  <div>Weitere Sprachen können hinzugebucht werden:<br />
                     <a href="https://www.kanpaiclassic.com/k_deu_24/Module" class="ci_color" target="_blank">www.shopsoftware.com</a>
                     <br /><br />
                  </div>
                  <?php foreach ($this->langs as $lang) { ?>
                  <div class="lang-line">
                     <?php if (array_search($lang, $this->select_langs) !== false) { ?>
                        <?php $checked = " checked='checked'"; ?>
                     <?php } else { ?>
                        <?php $checked = ''; ?>
                     <?php } ?>
                     <div class="laender_line">
                        <input type='checkbox' class="newdesign" id="lang_check-<?php echo $lang; ?>" name="lang_check[]" value="<?php echo $lang; ?>"<?php echo $checked; ?> />
                        <label for="lang_check-<?php echo $lang; ?>" class="laender_text"><?php echo $this->laender[$lang].' ('.$lang.')'; ?></div>
                     </div>
                  <?php } ?>
               </div>

               <div id="waehrung">
                  <div class="waehrung_zeile">
                     <div class="waehrung_art txt_bez">Währung</div>
                     <div class="waehrung_input txt_bez">Kurs</div>
                     <div class="clear"></div>
                  </div>

                  <div class="waehrung_zeile">
                     <div class="waehrung_art ellipsis">Hauptwährung</div>
                     <div class="waehrung_input_fix">1,00000000</div>
                     <div class="waehrung_name"><?php echo $this->_selectWaehrung($this->waehrung['waehrung1'], 1);?></div>
                     <div class="clear"></div>
                  </div>

                  <?php for ($i = 2; $i < 5; $i++) { ?>
                  <div class="waehrung_zeile">
                     <div class="waehrung_art ellipsis">
                        <input type="checkbox" class="newdesign" name="check_w<?php echo $i; ?>" id="check_w<?php echo $i; ?>"<?php echo $this->waehrung['check_w'.$i] == 'y' ? ' checked="checked"' : '';?> />
                        <label for="check_w<?php echo $i; ?>">Zusatzwährung</label>
                     </div>
                     <div class="waehrung_input">
                        <input type="text" name="kurs<?php echo $i; ?>" id="kurs<?php echo $i; ?>" value="<?php echo str_replace('.', ',', (sprintf('%01.8f', $this->waehrung['kurs'.$i]))); ?>" onblur='this.value = point2komma(parseFloat(komma2point(this.value)).toFixed(8));' />
                     </div>
                     <div class="waehrung_name"><?php echo $this->_selectWaehrung($this->waehrung['waehrung'.$i], $i);?></div>
                     <div class="clear"></div>
                  </div>
                  <?php } ?>
               </div>
               <div class="clear"></div>

               <div id="laender_div">
                  <h2 class="txt_tit">Heimatland &amp; Versandländer</h2>
                  <?php for ($l = 1; $l < 3; $l++) { ?>
                  <div class="laender_<?php echo ($l == 1 ? 'left' : 'right'); ?>">
                     <div class="laender_line<?php echo ($l == 2 ? ' hide_mobile' : ''); ?>">
                        <div class="title_sort ellipsis">Sortierung</div>
                        <div class="title_aufschlag ellipsis">Aufschlag (<span class="vers_gruen">netto</span>)</div>
                        <div class="clear"></div>
                     </div>

                      <?php for ($i = 0; $i < count($this->{'data'.$l}); $i++) {

                      // if($this->{'data'.$l}[$i]->id ==1)continue;

                     $class_deak = $this->{'data'.$l}[$i]->sort == 0 ? 'land_deak show_eu' : 'show_eu'; ?>
                     <div class="laender_line">
                        <span class="land <?php echo $class_deak; ?> ellipsis"><?php echo $this->{'data'.$l}[$i]->name; ?></span>
                        <span class="<?php echo ($this->{'data'.$l}[$i]->region == 'eu' ? 'is_eu' : 'not_eu'); ?>"></span>
                        <span class="sort">
                           <input class="inp_sort" type="text" name="land_sort[]" value="<?php echo $this->{'data'.$l}[$i]->sort; ?>" />
                        </span>
                        <span class="aufschlag">
                           <input type="text" class="txt_inp" name="land_versand[]" value="<?php echo $this->{'data'.$l}[$i]->versand; ?>" onBlur="this.value = point2komma(parseFloat(komma2point(this.value)).toFixed(2));" onDblClick="this.value = point2komma(parseFloat(komma2point($('#netto_calc').val())).toFixed(2));" />
                           <input type="hidden" name="land_id[]" value="<?php echo $this->{'data'.$l}[$i]->id; ?>" />
                        </span>
                     </div>
                     <?php } ?>
                  </div>
                  <?php } ?>
                  <div class="clear"></div>
               </div>

               <div id="laender_info_rechner">
                  <div id="laender_info">
                     <div class="land_info txt_bez">Hinweise zur Sortierung</div>
                     <ul>
                        <li>Sortiernummer &bdquo;0&rdquo; = Versandland nicht aktiv </li>
                        <li>Land mit niedrigster Sortiernummer ungleich 0 ist Heimatland<br />Schweizer stellen die Schweiz also auf 1 etc.</li>
                     </ul>
                  </div>

                  <div id="laender_rechner">
                     <div class="rechner">
                        <div class="land_info txt_bez">Brutto-Netto-Rechner</div>
                        <div class="calc_brutto">Brutto<br />
                           <input type="text" name="brutto_calc" id="brutto_calc" value="0,00" onkeyup="$('#netto_calc').val(point2komma(runden(komma2point(this.value) / <?php echo (float)(1 + ($this->params->firma['tax1'] / 100)); ?>, 2)));" />
                        </div>
                        <div class="calc_netto">Netto<br />
                             <input type="text" name="netto_calc" id="netto_calc" value="0,00" onkeyup="$('#brutto_calc').val(point2komma(runden(komma2point(this.value) * <?php echo (float)(1 + ($this->params->firma['tax1'] / 100)); ?>, 2)));" />
                        </div>
                     </div>
                  </div>
                  <div class="clear"></div>
               </div>
               <div class="clear"></div>
            </form>
         </div>
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
<!-- <script src="<?php echo SHOP_URL;  ?>/js/jquery-ui.min.js"></script> -->
<script src="<?php echo ADMIN_URL; ?>/js/admin.js"></script>
<!-- <script src="<?php echo ADMIN_URL; ?>/js/jquery.minicolors.min.js"></script> -->
</body>
</html>
