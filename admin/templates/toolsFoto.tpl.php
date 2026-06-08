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
      <div id="titelzeile" class="titelzeile">
         <div class='txt_tit'><a class="help_kanpaiclassic" href="<?php echo HELP_LINK; ?>/tools/fotolizenz-artikel/" target="_blank"></a>Fotolizenz-Artikel</div>
      </div>

      <div id="tools" class="maincontent content_box content_box_bottom">
         <div id="content_top">
         </div>

         <form id="toolsform" action="<?php echo ADMIN_URL_IDX; ?>/tools/save" method="post">

         <?php // Module Fotograf ?>
         <?php if (defined('CONF_FOTOGRAF')) { ?>
            <?php $foto_data = $this->getFotoData(); ?>
            <?php $category  = \KANPAICLASSIC\Control::getKategorie(); ?>
            <?php $catlist   = $category->catList(0, false, false); ?>
            <?php $dirlist   = $this->getFotoDirs(); ?>
         <div id="foto_modul" class="box_tools">
            <div class="box_left">
               <div class="titelzeile2">
                  <h2 class="txt_tit">Fotolizenzen</h2>
                  <div class="clear"></div>
               </div>

               <div class="tools_fotoline">Folgende Werte gelten global für alle Fotos</div>
               <div class="tools_fotoline">
                  <div class="foto_pos1_tit"><strong>Größe</strong></div>
                  <div class="foto_pos2_tit right"><strong>Pixel</strong> (längste Seite)</div>
                  <div class="foto_pos3_tit right"><strong>Preis</strong></div>
                  <div class="clear"></div>
               </div>

               <?php for ($f = 0; $f < 7; $f++) { ?>
               <div class="tools_fotoline">
                  <div class="foto_pos1"><input type="text" id="foto_name_<?php echo $f; ?>" value="<?php echo $foto_data[$f]->name; ?>" /></div>
                  <div class="foto_pos2"><input type="text" class="right" id="foto_size_<?php echo $f; ?>" value="<?php echo $foto_data[$f]->size; ?>" /></div>
                  <div class="foto_pos3"><input type="text" class="right" id="foto_price_<?php echo $f; ?>" value="<?php echo number_format($foto_data[$f]->price, 2, ',', '.'); ?>" /></div>
                  <div class="clear"></div>
               </div>
               <?php } ?>

               <div class="tools_fotoline button_line">
                  <div class="button_ci txt_but" onclick="Tools.fotoSave();">speichern</div>
               </div>
            </div>
            <div class="box_center">
               <div class="titelzeile2">
                  <h2 class="txt_tit ellipsis">Fotoartikel-Set generieren</h2>
               </div>

               <div class="tools_fotoline ellipsis">
                  <input type="radio" class="newdesign" value="on" id="foto_art_id1" name="foto_art_id" checked="checked" />
                  <label for="foto_art_id1">&nbsp;&nbsp;Artikel-ID als Artikelnummer nutzen</label>
                  <div class="clear"></div>
               </div>

               <div class="tools_fotoline foto_pos45">
                  <input type="radio" class="newdesign" id="foto_art_id2" name="foto_art_id" value="off" />
                  <label for="foto_art_id2" class="foto_pos4 ellipsis">&nbsp;&nbsp;Art.-Nr (für Set)</label>
                  <label for="foto_art_id2" class="foto_pos5 ellipsis">Artikelname (für  Set)</label>
                  <div class="clear"></div>
               </div>

               <div class="tools_fotoline foto_pos67">
                  <span class="foto_pos6"><input type="text" class="txt_inp" id="foto_artnr" value="" onkeyup="(this.value.length > 0 ? $('input:radio[name=foto_art_id]').val(['off']) : $('input:radio[name=foto_art_id]').val(['on']));" /></span>
                  <span class="foto_pos7"><input type="text" class="txt_inp" id="foto_artname" value="" /></span>
                  <div class="clear"></div>
               </div>

               <div class="tools_fotoline">
                  <input type="radio" class="newdesign" id="foto_keywords1" name="foto_keywords_on" value="on" checked="checked" />
                  <label for="foto_keywords1">Keywords aus Fotodateien (für Shopsuche)</label>
                  <div class="clear"></div>
               </div>
               <div class="tools_fotoline pos88">
                  <input type="radio" class="newdesign" id="foto_keywords2" name="foto_keywords_on" value="off" />
                  <label for="foto_keywords2">
                     <span class="foto_pos8">
                        <input type="text" class="txt_inp" id="foto_keywords" value="" placeholder="eigene Keywords" onMouseOver="$(this).attr('placeholder') != '' ? $(this).attr('data-placeholder', $(this).attr('placeholder')) : ''; $(this).attr('placeholder', '');" onMouseOut="$(this).attr('placeholder', $(this).data('placeholder'));" />
                     </span>
                  </label>
                  <div class="clear"></div>
               </div>

               <div class="tools_fotoline">
                  <textarea id="foto_desc" placeholder="Artikel-Beschreibung oder Keywords wenn leer"></textarea>
               </div>

               <div class="tools_fotoline">In folgende Kategorie einstellen</div>
               <div class="tools_fotoline"><span class="selectbox30"><select id="foto_cat"><?php echo $catlist; ?></select></span></div>
               <?php if ($dirlist != '') { ?>
                  <div class="tools_fotoline">Set-Ordner vom Server wählen (/downloads/...)</div>
                  <div class="tools_fotoline"><span class="selectbox30"><select id="foto_dir"><?php echo $dirlist; ?></select></span></div>
               <?php } else { ?>
                  <div class="tools_fotoline">Kein Verzeichnis gefunden</div>
                  <div class="tools_fotoline">&nbsp;</div>
               <?php } ?>
               <div class="clear"></div>

               <div class="tools_line button_line">
                  <div class="button button_ci txt_but" onclick="Tools.fotoArtikel();">Generieren</div>
                  <div class="button txt_but right" onclick="Tools.fotoClean();">Bereinigen</div>
                  <div class="button txt_but right" onclick="Tools.cronClean();">CronJob leeren</div>
               </div>
            </div>

            <div class="box_right">
               <div id="wasserzeichen">
                  <h2 class="txt_tit">Wasserbild</h2>

                  <span class="upload upload_button pointer" onclick="Tools.wasserzeichenUpload()"></span>
                  <span class="delete pointer far fa-trash-alt" onclick="Tools.wasserzeichenDelete();"></span>
                  <div id="wasser_div">
                     <img src="<?php echo (is_file(ADMIN_PATH.'/img/wasserzeichen.png') ? ADMIN_URL.'/img/wasserzeichen.png' : ADMIN_URL.'/img/nopic78.jpg'); ?>" alt="" />
                  </div>
                  <div class="wasser_info">PNG ca. 500x300 px</div>
                  <div class="clear"></div>
               </div>

            </div>
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
<script src="<?php echo ADMIN_URL; ?>/js/jquery.minicolors.min.js"></script>
</body>
</html>
