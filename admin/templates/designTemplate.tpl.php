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

$template_id = 1;

if (defined('CONF_TEMPLATE_ID')) {
   $template_id = CONF_TEMPLATE_ID;
}

// Info: Teile nur für bestimmte Templates anzeigen:
// <div class="template_none show_1 show_2 ... <?php echo $template_show; ? >"> -> $template_show: template_2

$menu                 = KANPAICLASSIC\Control::getMenu();
$admin_config         = $menu->loadDesign();
$image_url            = TEMPLATE_URL.'/images/';
$image_path           = TEMPLATE_PATH.'/images/';
$sel_lang             = $this->params->selected_lang;
$no_img               = ADMIN_URL.'/img/nopic.png';
$help                 = KANPAICLASSIC\Control::getHelp();
$template_show        = 'template_'.$template_id;
$startseite_breite    = $this->json['startseite_breite'];
$startseite_artikel   = $this->json['startseite_artikel'];
$slideshow_on         = $this->json['slideshow_on'];
$collage_on           = $this->json['collage_on'];

// Höhen Logo, Banner1
$logomenu            = $no_img;
$logomenu_w          = 1;
$skalierung          = $this->json['max_width'] / 900;
$logo_banner         = $no_img;
$logo_banner_w       = 900;
$logo_banner_h       = 110;
$banner_unten        = $no_img;
$banner_unten_w      = 900;
$banner_unten_h      = 110;

$image_bg            = (file_exists($image_path.'bg_tn.jpg') ? $image_url.'bg_tn.jpg?'.time() : $no_img);

if (is_file($image_path.'logomenu_'.$sel_lang.'_tn.png')) {
   $logomenu         = $image_url.'logomenu_'.$sel_lang.'_tn.png';
   list($logomenu_w) = getimagesize($image_path.'logomenu_'.$sel_lang.'_tn.png');
}

else if (is_file($image_path.'logomenu_'.$sel_lang.'_tn.jpg')) {
   $logomenu = $image_url.'logomenu_'.$sel_lang.'_tn.jpg';
   list($logomenu_w) = getimagesize($image_path.'logomenu_'.$sel_lang.'_tn.png');
}

if (is_file($image_path.'logo_'.$sel_lang.'.png')) {
   $size          = getimagesize($image_path.'logo_'.$sel_lang.'.png');
   $logo_banner   = $image_url.'logo_'.$sel_lang.'.png';
   $logo_banner_w = floor($size[0] / $skalierung);
   $logo_banner_h = floor($size[1] / $skalierung);
}

else if (is_file($image_path.'logo_'.$sel_lang.'.jpg')) {
   $size   = getimagesize($image_path.'logo_'.$sel_lang.'.jpg');
   $logo   = $image_url.'logo_'.$sel_lang.'.jpg';
   $logo_w = floor($size[0] / $skalierung);
   $logo_h = floor($size[1] / $skalierung);
}

if (is_file($image_path.'banner2_'.$sel_lang.'.jpg')) {
   $size          = getimagesize($image_path.'banner2_'.$sel_lang.'.jpg');
   $banner_unten  = $image_url.'banner2_'.$sel_lang.'.jpg?'.time();
   $banner_unten_w = floor($size[0] / $skalierung);
   $banner_unten_h = floor($size[1] / $skalierung);
}
$this->params->getLinks($sel_lang);

$startbild_video = (file_exists($image_path.'startbild_video_'.$sel_lang.'_tn.jpg') ? $image_url.'startbild_video_'.$sel_lang.'_tn.jpg' : $no_img);

if (file_exists($image_path.'startbild_video_'.$sel_lang.'.mp4') || file_exists($image_path.'startbild_video_'.$sel_lang.'.webm') || file_exists($image_path.'startbild_video_'.$sel_lang.'.mov')) {
   $startbild_video = ADMIN_URL.'/img/video.png';
}

header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time()));
header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");

?><!DOCTYPE html>
<html lang="de">
<head>
<title>Kanpai Classic <?php echo (!defined('CONF_MODULE_PORTAL') ? 'Shopsystem' : 'Shopportal'); ?> - Administration - Design</title>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
<style>
<?php include_once TEMPLATE_PATH.'/css/colors.css'; ?>
<?php include_once ADMIN_PATH.'/css/'.(is_file(ADMIN_PATH.'/css/admin.css') ? 'admin.css' : 'admin_easy.css'); ?>
</style>
<link rel="stylesheet" type="text/css" href="<?php echo ADMIN_URL; ?>/css/jquery.minicolors.css" />
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
         <div class='txt_tit'>
            <a class="help_kanpaiclassic" href="<?php echo HELP_LINK; ?>/design/einstellungen/" target="_blank"></a>
            Templatedesign
            <a href="<?php echo SHOP_URL; ?>" target="_blank">
               <span id="auge" class="pointer far fa-eye"></span>
            </a>
         </div>
         <div class="language"><?php echo $menu->langData(); ?></div>
         <div class="save_button" onclick="$('#designform').submit();">speichern</div>
      </div>

      <div id="design_einstellungen" class="maincontent">
         <form method="post" id="designform" action="<?php echo ADMIN_URL_IDX; ?>/designTemplate/save">
            <div class="content_box content_box_bottom">
               <div id="content_top"></div>

               <input type="hidden" name="content_padding" value="<?php echo $this->json['content_padding']; ?>" />
               <input type="hidden" name="sel_lang" value="<?php echo $sel_lang; ?>" />

               <div id="shop_header" class="design">
                  <?php // +++++++++++++++++++++++++++ Template ++++++++++++++++++++++++++++++ ?>
                  <?php if (is_array($this->templates) && count($this->templates) > 1) { ?>
                  <div id="template">
                     <div class="box_left">
                        <div class="txt_bez">Template</div>
                     </div>

                     <div class="box_right line_header">
                        <?php for ($i = 0; $i < count($this->templates); $i++) { ?>
                           <?php $template = $this->templates[$i]; ?>
                        <span>
                           <input type="radio" class="newdesign" id="template<?php echo $i; ?>" name="template" value="<?php echo $template; ?>"<?php echo ($this->params->firma['template'] == $template ? ' checked="checked"' : ''); ?> onchange="Design.designChange(this.value);" />
                           <label for="template<?php echo $i; ?>"><?php echo $template; ?></label>
                        </span>
                        <?php } ?>
                     </div>
                     <div class="clear"></div>
                  </div>
                  <?php } ?>

                  <div class="box_left">
                     <div class="txt_bez line_header">Shopbreite</div>
                     <div class="easy txt_bez line_header<?php echo ($this->json['flaeche'] == 'y' ? 'display:none;' : ''); ?>">oberer Abstand</div>
                  </div>

                  <div class="box_right">
                     <div id="breite">
                        <?php // +++++++++++++++++++++++++++ Breite ++++++++++++++++++++++++++++++ ?>
                        <div class="line_header">
                           <?php // nur Template1 ?>
                           <div class="template_none show_1 <?php echo $template_show; ?>">
                              <?php // nur Tempalte1 ?>
                              <input type="radio" class="newdesign" id="startseite_breite" name="startseite_breite" value="kategorien" <?php echo ($startseite_breite == 'kategorien') ? 'checked="checked" ' : ''; ?> />
                              <label for="startseite_breite"></label>
                              <div class="inline_box">Kategorien + <?php echo (CONF_BANNERBREITE - 269); ?>px</div>
                              &nbsp;&nbsp;&nbsp;&nbsp;
                              <input type="radio" class="newdesign" id="" name="startseite_breite" value="breit" <?php echo ($startseite_breite == 'breit') ? 'checked="checked" ' : ''; ?> />
                              <label for=""></label>
                              <div class="inline_box">
                                 <span id="startseite_breite"> <?php echo CONF_BANNERBREITE; ?></span> px Breite
                              </div>
                           </div>

                           <?php // nur Tempalte2 ?>
                           <div class="line_header">
                              <div class="template_none show_2 <?php echo $template_show; ?>">
                                 <input type="text" id="max_width" class="txt_inp inp_50_right" name="max_width" value="<?php echo $this->json['max_width']; ?>" /> px
                              </div>
                           </div>
                        </div>

                        <?php // +++++++++++++++++++++++++++ Abstand oben (bei Menü-Logo flaeche deaktiviert) ++++++++++++++++++++++++++++++ ?>
                        <div class="easy">
                           <div id="abstand_oben_breit"<?php echo ($this->json['flaeche'] == 'y' ? ' style="display:none;"' : ''); ?>>
                              <input type="text" class="txt_inp inp_50_right" name="abstand_oben" value="<?php echo $this->json['abstand_oben']; ?>" /> px
                           </div>
                           <div id="abstand_oben_schmal"<?php echo ($this->json['flaeche'] != 'y' ? ' style="display:none;"' : ''); ?>>
                              <span class="help ci_color" title="Wird bei Logobanner / bildschirmbreit nicht verwendet."></span>
                           </div>
                        </div>
                     </div>

                     <div class="clear"></div>
                  </div>
                  <div class="clear"></div>

                  <?php // +++++++++++++++++++++++++++ Shop on/off ++++++++++++++++++++++++++++++ ?>
                  <div id="shop_on_off">
                     <span class="shop_on_off pointer fas fa-power-off shop_<?php echo $this->params->firma['shop_on_check'] === 'y' ? 'on' : 'off'; ?>" onclick="Design.shopOnOff();"></span>
                  </div>
               </div>
               <hr />
               <?php // +++++++++++++++++++++++++++ Hintergrund ++++++++++++++++++++++++++++++ ?>
               <div id="hintergrund" class="design">
                  <div class="box_left">
                     <div class="txt_bez">Hintergrund</div>
                     <div class="upload_block_horiz">
                        <span>jpg</span>
                        <span class="upload upload_button pointer" onclick="Design.uploadImg('bg', 0, 'bg_img');" title="hochladen"></span>
                        <span class="delete pointer far fa-trash-alt" onclick="Design.deleteImg('bg', 0, 'bg_img');" title="löschen"></span>
                     </div>
                     <div class="easy bg_flaeche_logo">
                        <div class="upload_block_horiz">
                           <input type="checkbox" class="newdesign" id="flaeche_mitte" name="flaeche_mitte"<?php echo ($this->json['flaeche_hg'] == 'y') ? ' checked="checked"' : ''; ?>  onchange="($(this).is(':checked') ? $('#hintergrund_img2').prop('src', '<?php echo ADMIN_URL; ?>/img/flaeche_hor.png') : $('#hintergrund_img2').prop('src', '<?php echo ADMIN_URL; ?>/img/flaeche_vert.png'));" />
                           <label for="flaeche_mitte" class="after">Fläche</label>
                        </div>
                     </div>
                  </div>

                  <div class="box_right">
                     <div id="hintergrund_right">
                        <div class="hintergrund_img">
                           <img id="bg_img" src="<?php echo $image_bg.$this->params->firma['image_cache']; ?>" alt="" />
                           <img id="hintergrund_img2" src="<?php echo ADMIN_URL; ?>/img/flaeche_<?php echo ($this->json['flaeche_hg'] == 'y' ? 'hor' : 'vert'); ?>.png" />
                        </div>

                        <div class="bg_pos2 easy">
                           <div>
                              <span>&nbsp;</span>
                              <span>
                                 <input type="radio" class="newdesign" id="bg_fixed1" name="bg_fixed" value="n"<?php echo ($this->json['bg_fixed'] == 'n') ? ' checked="checked"' : ''; ?> />
                                 <label for="bg_fixed1">mitscrollend</label>
                              </span>
                              <span>
                                <input type="radio" class="newdesign" id="bg_fixed2" name="bg_fixed" value="y"<?php echo ($this->json['bg_fixed'] == 'y') ? ' checked="checked"' : ''; ?> />
                                 <label for="bg_fixed2">fixiert</label>
                              </span>
                              <div class="clear"></div>
                           </div>
                           <div>
                              <span>Kacheln</span>
                              <span>
                                 <input type="radio" class="newdesign" id="bg_repeat1" name="bg_repeat" value="z"<?php echo ($this->json['bg_repeat'] == 'z') ? ' checked="checked"' : ''; ?> />
                                 <label for="bg_repeat1">xy</label>
                              </span>
                              <span>
                                 <input type="radio" class="newdesign" id="bg_repeat2" name="bg_repeat" value="x"<?php echo ($this->json['bg_repeat'] == 'x') ? ' checked="checked"' : ''; ?> />
                                 <label for="bg_repeat2">x</label>
                              </span>
                              <span>
                                 <input type="radio" class="newdesign" id="bg_repeat3" name="bg_repeat" value="y"<?php echo ($this->json['bg_repeat'] == 'y') ? ' checked="checked"' : ''; ?> />
                                 <label for="bg_repeat3">y</label>
                              </span>
                              <span>
                                 <input type="radio" class="newdesign" id="bg_repeat4" name="bg_repeat" value="n"<?php echo ($this->json['bg_repeat'] == 'n') ? ' checked="checked"' : ''; ?> />
                                 <label for="bg_repeat4">aus</label>
                              </span>
                              <div class="clear"></div>
                           </div>
                        </div>
                     </div>

                     <?php // +++++++++++++++++++++++++++ Startbild / Video ++++++++++++++++++++++++++++++ ?>
                     <div id="startbild_video" class="easy">
                        <?php if (defined('CONF_MODULE_EXTENDED')) { ?>
                        <div id="startbild_video1" class="design">
                           <div class="video_left">
                              <div class="txt_bez">Startbild/Video</div>
                              <div class="upload_block_horiz">
                                 <span class="upload upload_button pointer" onclick="Design.uploadImg('startbild_video', 0, 'startbild_video_img', 'jpg, png, mp4, webm, mov');" title="hochladen"></span>
                                 <span class="delete pointer far fa-trash-alt" onclick="Design.deleteImg('startbild_video', 0, 'startbild_video_img');" title="löschen"></span>
                              </div>
                           </div>

                           <div class="video_right">
                              <div class="startbild_video">
                                 <img id="startbild_video_img" src="<?php echo $startbild_video.$this->params->firma['image_cache']; ?>" />
                              </div>
                           </div>
                        </div>
                        <?php } ?>
                     </div>
                     <div class="clear"></div>
                  </div>
                  <div class="clear"></div>
               </div>
            </div>

            <div class="content_box_abstand"></div>

            <div class="titelzeile txt_tit no_help">Startseite <span class="fliesstext">(Shopmitte)</span></div>
            <div class="content_box content_box_bottom">
               <?php // +++++++++++++++++++++++++++ Favicon und Headerscript ++++++++++++++++++++++++++++++ ?>
               <div id="favicon" class="design">
                  <div class="box_left">
                     <div class="txt_bez">Favicon</div>
                     <div class="upload_block_horiz">
                        <span>jpg</span>
                        <span class="upload upload_button pointer" onclick="Design.uploadImg('favicon', 0, 'favicon_img', 'png,jpg');" title="hochladen"></span>
                        <span class="delete pointer far fa-trash-alt" onclick="Design.deleteImg('favicon', 0, 'favicon_img');" title="löschen"></span>
                        <span class="link far"></span>
                     </div>
                  </div>

                  <div class="box_right">
                     <div class="favicon">
                        <img id="favicon_img" src="<?php echo (is_file($image_path.'favicon-32x32.png') ? $image_url.'favicon-32x32.png'.$this->params->firma['image_cache'] : $no_img); ?>" alt="" />
                     </div>
                     <div id="header_script" class="button txt_but easy" onclick="Design.loadHeaderscript();">Headerscript</div>
                  </div>
               </div>


               <?php // +++++++++++++++++++++++++++ Menü und Menü-Logo ++++++++++++++++++++++++++++++ ?>
               <div id="header_menu" class="design site_full<?php echo ($this->json['flaeche'] == 'y' ? ' menuleiste' : ''); ?>">
                  <div class="box_left">
                     <div class="txt_bez">Icon / Menü</div>
                     <div class="upload_block_horiz">
                        <span class="upload upload_button pointer" onclick="Design.uploadImg('logomenu', 0, 'logomenu_img', 'png,jpg');" title="hochladen"></span>
                        <span class="delete pointer far fa-trash-alt" onclick="Design.deleteImg('logomenu', 0, 'logomenu_img');" title="löschen"></span>
                        <span class="edit fas"></span>
                     </div>
                  </div>

                  <div class="box_right">
                     <div class="menu_box_right bg_banner_width<?php echo ($this->json['flaeche'] != 'y' ? ' menuleiste' : ''); ?>">
                        <div id="fe_icons" class="bg_banner_width">
                           <div class="fe_icons_left">
                              <span class="logomenu_img">
                                 <img id="logomenu_img" src="<?php echo $logomenu.$this->params->firma['image_cache']; ?>" alt="" />
                              </span>
                              <a href="<?php echo ADMIN_URL_IDX; ?>/seiten" title="Menüpunkt SEITEN öffnen?" target="_blank">
                                 <span class="shop_check menu_oben fe_icon_shop<?php echo ($this->params->firma['shop_check'] != 'y' ? '_inactive' :''); ?>"></span>
                                 <span class="homebutton_check menu_oben fe_icon_home<?php echo ($this->params->firma['homebutton_check'] != 'y' ? '_inactive' :''); ?>">Home </span>
                                 <span class="kontakt_check menu_oben fe_icon_kontakt<?php echo ($this->params->firma['kontakt_check'] != 'y' ? '_inactive' :''); ?>">Kontakt </span>
                                 <span class="edit ci_color fas fa-pencil-alt"></span>
                              </a>
                              <div class="clear"></div>
                           </div>

                           <div class="fe_icons_right<?php echo ($this->params->firma['icon_farbe'] != 'weiss' ? ' dunkel' : ''); ?>"><?php
                              ?><span class="edit ci_color pointer fas fa-pencil-alt" onclick="Design.loadMenuPopup();" title="Menü-Einstellungen"></span><?php
                              ?><span class="anmelden_mode menu_oben fe_icon_anmelden<?php echo $this->params->firma['anmelden_mode']; ?>"> Anmelden&nbsp;</span><?php
                              ?><span class="merkliste_mode menu_oben fe_icon_merkliste<?php echo $this->params->firma['merkliste_mode']; ?>">Merkliste&nbsp;</span><?php
                              ?><span class="warenkorb_mode menu_oben fe_icon_warenkorb<?php echo $this->params->firma['warenkorb_mode']; ?>">Warenkorb&nbsp;</span><?php
                              ?><span class="suchfeld_mode fe_icon_suchfeld<?php echo $this->params->firma['suchfeld_mode']; ?>">&nbsp;</span><?php
                              ?><span class="flaggen_mode fe_icon_flaggen<?php echo $this->params->firma['flaggen_mode']; ?>">&nbsp;</span><?php
                           ?></div>
                        </div>
                     </div>
                  </div>
               </div>

               <?php // +++++++++++++++++++++++++++ Logobanner ++++++++++++++++++++++++++++++ ?>
               <div id="logobanner" class="design site_full<?php echo ($this->json['flaeche'] == 'y' ? ' bg_header' : ''); ?>">
                  <div class="box_left">
                     <div class="txt_bez">Logobanner</div>
                     <div class="upload_block_horiz">
                        <span class="upload upload_button pointer" onclick="Design.uploadImg('logobanner', 0, 'logobanner_img', 'png,jpg');" title="hochladen"></span>
                        <span class="delete pointer far fa-trash-alt" onclick="Design.deleteImg('logo', 0, 'logobanner_img');" title="löschen"></span>
                        <span class="link pointer fas fa-link" onclick="Design.linkPopup('logobanner');" title="SEO"></span>
                        <input type="hidden" id="logobanner_seo" name="logobanner_seo" value="<?php echo $this->params->links['logoseo']; ?>" />
                     </div>
                     <div class="easy relative">
                        <div class="upload_block_horiz">
                           <input type="checkbox" class="newdesign" id="flaeche" name="flaeche"<?php echo ($this->json['flaeche'] == 'y') ? ' checked="checked"' : ''; ?> onchange="Design.bildschirmbreit(this);" />
                           <label for="flaeche" class="after">bildschirmbreit</label>
                        </div>
                     </div>
                  </div>

                  <div class="box_right">
                     <div class="menu_box_right bg_banner_width<?php echo ($this->json['flaeche'] != 'y' ? ' bg_header' : ''); ?>">
                        <div id="banner_box_right" class="bg_banner_width bg_header<?php echo ($this->json['flaeche'] != 'y' ? ' bg_header' : ''); ?>">
                           <div id="menu_box_right" class="bg_banner_width">
                              <div class="logobanner_pic" style="height:<?php echo $logo_banner_h; ?>px">
                                 <img id="logobanner_img" style="width:<?php echo $logo_banner_w; ?>px; max-width:100%; max-height:<?php echo $logo_banner_h; ?>px;" src="<?php echo $logo_banner.$this->params->firma['image_cache']; ?>" />
                              </div>
                           </div>
                           <div class="clear"></div>
                        </div>
                     </div>
                  </div>
               </div>

               <?php // +++++++++++++++++++++++++++ Kategorien ++++++++++++++++++++++++++++++ ?>
               <div id="kategorien" class="design">
                  <div class="box_left">
                     <div class="txt_bez">Kategorien</div>
                  </div>

                  <div class="box_right">
                     <?php // Template1 ?>
                     <div class="line_design template_none show_1 easy <?php echo $template_show; ?>">
                        <input type="checkbox" name="multishop" id="multishop" <?php echo ($this->params->firma['multishop'] == 'y' ? 'checked="checked"' : ''); ?> onClick="Royalart.multishop();" />
                        <label for="">Kategorien horizontal (Untermenüs links) Multishopfunktion</label>
                     </div>

                     <?php // Template2 ?>
                     <div class="line_design template_none show_2 <?php echo $template_show; ?>">
                        <input type="radio" class="newdesign" id="kategorien_links1" name="kategorien_links" value="h" <?php echo ($this->json['kategorien_links'] == 'n' || $this->json['kategorien_links'] == 'h' ? ' checked="checked"': ''); ?> onclick="$('#bildschirmbreit').prop('disabled', false); $('#bildschirmbreit').parent().css('color', '#555555');" />
                        <label for="kategorien_links1">horizontal</label>

                        <input type="checkbox" class="newdesign easy" id="schatten" name="schatten"<?php echo ($this->json['schatten'] == 'y') ? ' checked="checked"' : ''; ?> />
                         <label class="easy" for="schatten">Schatten</label>

                        <?php if (defined('CONF_MODULE_EXTENDED')) { ?>
                        <input type="checkbox" class="newdesign" id="shop_check" name="shop_check"<?php echo ($this->json['shop_check'] == 'y' ? ' checked="checked"': ''); ?>
                            onchange="($(this).prop('checked') ?
                               $('.shop_check').removeClass('fe_icon_shop_inactive').addClass('fe_icon_shop') :
                               $('.shop_check').removeClass('fe_icon_shop').addClass('fe_icon_shop_inactive'));" />
                        <label for="shop_check">Icon (3 Striche) statt Kategorien</label>
                        <?php } ?>
                     </div>

                     <?php if (defined('CONF_MODULE_DROPDOWNKATEGORIEN')) { ?>
                        <div class="line_design">
                           <input type="radio" class="newdesign" id="kategorien_links2" name="kategorien_links" value="d" <?php echo ($this->json['kategorien_links'] == 'd' ? ' checked="checked"': ''); ?> onclick="$('#bildschirmbreit').prop('disabled', true); $('#bildschirmbreit').parent().css('color', '#cccccc'); $('#bildschirmbreit').attr('checked', false);" />
                           <label for="kategorien_links2">Dropdown</label>
                        </div>
                     <?php } ?>

                     <div class="line_design">
                        <input type="radio" class="newdesign" id="kategorien_links3" name="kategorien_links" value="l" <?php echo ($this->json['kategorien_links'] == 'y' || $this->json['kategorien_links'] == 'l' ? ' checked="checked"': ''); ?> onclick="$('#bildschirmbreit').prop('disabled', true); $('#bildschirmbreit').parent().css('color', '#cccccc'); $('#bildschirmbreit').attr('checked', false);" />
                        <label for="kategorien_links3">vertikal</label>
                        
                        <input type="checkbox" class="newdesign easy" id="linien_kat" name="linien_kat" <?php echo ($this->json['linien_kat'] == 'y' ? ' checked="checked"': ''); ?> />
                         <label class="easy" for="linien_kat">Trennlinie</label>
                     </div>
                  </div>
                  <div class="clear"></div>
               </div>

               <?php // +++++++++++++++++++++++++++ Abstand ++++++++++++++++++++++++++++++ ?>
               <div id="abstand" class="easy design">
                  <div class="box_left">
                     <div class="txt_bez">Abstand</div>
                  </div>

                  <div class="box_right">
                     <div class="">
                        <input type="text" class="txt_inp inp_50_right" name="abstand" value="<?php echo $this->json['abstand']; ?>" /> px
                     </div>
                  </div>
               </div>

               <hr />

               <?php // +++++++++++++++++++++++++++ Slideshow +++++++++++++++++++++++ ?>
               <?php
                  $slideshow_mode = 'normal';
                  $bild           = '';
                  $bg_normal      = ADMIN_URL.'/img/slideshow_normal.jpg';
                  $bg_right       = ADMIN_URL.'/img/slideshow_links.jpg';
                  $bg_fullscreen  = ADMIN_URL.'/img/slideshow_bildschirmbreit.jpg';

                  // Angezeigtes Bild beim Start
                  if( file_exists($image_path.'slide1_'.$sel_lang.'.jpg')) {
                     $bild = $image_url.'slide1w_'.$sel_lang.'.jpg?'.time();

                     if ($this->json['fullscreen_slide'] == 'y') {
                        $slideshow_mode = 'fullscreen';
                        $bild = $image_url.'slide1l_'.$sel_lang.'.jpg';
                     }

                     else if ($this->params->firma['slideshow_r_check'] == 'y') {
                        $slideshow_mode = 'right';
                        $bild           = $image_url.'slide1_'.$sel_lang.'.jpg';
                     }
                  }

                  // Background, falls nicht vorhanden
                  else {
                     $bild = $bg_normal;

                     if ($this->json['fullscreen_slide'] == 'y') {
                        $slideshow_mode = 'fullscreen';
                        $bild = $bg_fullscreen;
                     }

                     else if ($this->params->firma['slideshow_r_check'] == 'y') {
                        $slideshow_mode = 'right';
                        $bild           = $bg_right;
                     }
                  }
               ?>
               <div id="slideshow" class="design" data-bg_normal="<?php echo $bg_normal; ?>" data-bg_right="<?php echo $bg_right; ?>" data-bg_fullscreen="<?php echo $bg_fullscreen; ?>">
                  <div class="box_left">
                     <div class="slider_titel">
                        <input type="checkbox" class="newdesign" id="slideshow_on" name="slideshow_on" <?php echo ($slideshow_on == 'y') ? 'checked="checked" ' : ''; ?> onchange="($(this).is(':checked') ? $('#slideshow_hidden').show() : $('#slideshow_hidden').hide())" />
                        <label for="slideshow_on"></label>
                        <span class="txt_bez pointer">Slideshow</span>
                     </div>
                     <div class="clear"></div>
                  </div>

                  <div class="mobile_slide">
                     <div class="mobile_slide_inner">
                        <?php $html = ''; require_once ADMIN_PATH.'/templates/design_slideshow.tpl.php'; echo $html; ?>
                     </div>
                  </div>
               </div>
               <hr />

               <?php // +++++++++++++++++++++++++++ Startseite HTML ++++++++++++++++++++++++++++++ ?>
               <div id="startseite" class="design">
                  <div class="box_left">
                     <div class="slider_titel">
                        <input type="checkbox" class="newdesign" id="starthtml_on" name="starthtml_on"<?php echo ($this->json['starthtml_on'] == 'y' ? 'checked="checked" ' : ''); ?> onchange="($(this).is(':checked') ? $('#starthtml_hidden').show() : $('#starthtml_hidden').hide())" />
                        <label for="starthtml_on"></label>
                        <span class="txt_bez">Startseitentext</span>
                     </div>
                     <div class="clear"></div>
                  </div>

                  <div id="starthtml_hidden" class="box_right"<?php echo ($this->json['starthtml_on'] == 'n' ? ' style="display:none;"' : ''); ?>>
                     <div id="starthtml">
                        <div class="editor_wrapper">
                           <textarea class="edit_breit" id="starthtml_text" name="starthtml_text" rows="10" cols="100"><?php echo $text_array[0]; ?></textarea>
                        </div>
                     </div>
                     <div class="clear"></div>
                  </div>
                  <div class="clear"></div>
                  <hr />
               </div>

               <?php // +++++++++++++++++++++++++++ Collage ++++++++++++++++++++++++++++++ ?>
               <div id="collage" class="easy design">
                  <div class="box_left">
                     <div class="slider_titel txt_bez">
                        <input type="checkbox" class="newdesign" id="collage_on" name="collage_on"<?php echo ($collage_on == 'y' ? 'checked="checked" ' : ''); ?> onchange="($(this).is(':checked') ? $('#collage_hidden').show() : $('#collage_hidden').hide())" />
                        <label for="collage_on"></label>Kollage
                     </div>
                     <div class="clear"></div>
                  </div>
                  <div class="mobile_slide">
                     <div class="mobile_slide_inner">
                        <?php $html = ''; require_once ADMIN_PATH.'/templates/design_collage.tpl.php'; echo $html; ?>
                     </div>
                  </div>
                  <div class="clear"></div>
               </div>
               <hr />

               <?php // +++++++++++++++++++++++++++ Artikel-Liste ++++++++++++++++++++++++++++++ ?>
               <div id="artikelliste" class="easy design">
                  <div class="box_left txt_bez">
                    <input type="checkbox" class="newdesign" id="artikelliste_on" name="artikelliste_on"<?php echo ($this->json['artikelliste_on'] == 'y' ? 'checked="checked" ' : ''); ?> />
                    <label for="artikelliste_on"></label>Artikelliste
                  </div>

                  <div class="box_right">
                     <div class="al_left">
                        <div class="design_line">
                           <input type="radio" class="newdesign" id="startseite_artikel1" name="startseite_artikel" value="reihen" <?php echo ($startseite_artikel == 'reihen') ? 'checked="checked" ' : ''; ?> onclick="$('#startseite_reihen').prop('readonly', false);" />
                           <label for="startseite_artikel1"></label>
                           <input type="text" class="inp40 center" id="startseite_reihen" name="startseite_reihen" value="<?php echo $this->json['startseite_reihen']; ?>"<?php echo ($startseite_artikel == 'artikel' ? ' readonly="readonly"' : ''); ?> />
                           &nbsp;Artikelreihen auf Startseite
                        </div>

                        <div class="design_line">
                           <input type="radio" class="newdesign" id="startseite_artikel2" name="startseite_artikel" value="artikel" <?php echo ($startseite_artikel == 'artikel' || $startseite_artikel == '') ? 'checked="checked" ' : ''; ?> onClick="$('#startseite_reihen').prop('readonly', true);" />
                           <label for="startseite_artikel2">Artikel fortlaufend auf Startseite</label>
                        </div>

                        <div class="design_line"<?php echo ($this->json['kategorien_links'] == 'y' ? ' style="color:#cccccc;"': ''); ?>>
                           <input id="bildschirmbreit" class="newdesign" name="bildschirmbreit" type="checkbox"<?php echo ($this->json['bildschirmbreit'] == 'y' ? ' checked="checked"' : ''); ?> />
                           <label for="bildschirmbreit">bildschirmbreit</label>
                         </div>
                     </div>

                     <div class="al_center">
                        <div class="design_line">
                           <input type="checkbox" class="newdesign" id="zoom_artikel" name="zoom_artikel" <?php echo ($this->json['zoom_artikel'] == 'y' ? ' checked="checked"': ''); ?> />
                           <label for="zoom_artikel">mit Zoom in Artikelliste</label>
                        </div>

                        <div class="design_line">
                           <input type="checkbox" class="newdesign" id="thumb_over_check" name="thumb_over_check" <?php echo ($this->json['thumb_over_check'] == 'y' ? ' checked="checked"' : ''); ?> />
                           <label for="thumb_over_check">Preis und Name bei Mouseover</label>
                        </div>

                        <div class="design_line">
                           <input type="checkbox" class="newdesign" id="merkmal_over_check" name="merkmal_over_check" <?php echo ($this->json['merkmal_over_check'] == 'y' ? ' checked="checked"' : ''); ?> />
                           <label for="merkmal_over_check">Artikelvarianten bei Mouseover</label>
                        </div>
                     </div>

                     <div class="al_right">
                        <div class="design_line">
                           <span class="text_filter">Start Animation</span>
                           <span class="selectbox30 cbp_animation"><select name="cbp_display"><?php echo $this->_designOptions($this->json['cbp_display']); ?></select></span>
                        </div>

                        <?php if (defined('CONF_MODULE_MARKENFILTER')) { ?>
                        <div class="design_line">
                           <span class="text_filter">Filter Animation</span>
                           <span class="selectbox30 cbp_animation"><select name="cbp_animation"><?php echo $this->_animationOptions($this->json['cbp_animation']); ?></select></span>
                        </div>
                        <?php } ?>

                        <?php if (defined('CONF_POPUP')) { ?>
                        <div class="design_line">
                           <input type="checkbox" class="newdesign" id="wk_popup_check" name="wk_popup_check" <?php echo ($this->json['wk_popup_check'] == 'y' ? ' checked="checked"' : ''); ?> />
                           <label for="wk_popup_check">"Weiter Einkaufen" Popup</label>
                        </div>
                        <?php } else { ?>
                        <input type="hidden" name="wk_popup_check" value="off" />
                        <?php } ?>

                     </div>
                     <div class="clear"></div>

                     <?php if ($template_id == 2) { ?>
                     <div class="box_abstand"></div>
                     <div class="line_design">
                        <div class="format format_1">
                           <div class="format_left">
                              <img src="<?php echo ADMIN_URL.'/img/format_1.jpg'; ?>" alt="" />
                              <input type="radio" class="newdesign" id="cpf_size1" name="cpf_size" value="klein"<?php echo ($this->json['cpf_size'] == 'klein' ? ' checked="checked" ' : ''); ?> />
                              <label for="cpf_size1">klein</label>
                           </div>
                           <div class="format_right">
                              <img src="<?php echo ADMIN_URL.'/img/format_2.jpg'; ?>" alt="" />
                              <input type="radio" class="newdesign" id="cpf_size2" name="cpf_size" value="normal"<?php echo ($this->json['cpf_size'] == 'normal' || $this->json['cpf_size'] == '' ? ' checked="checked" ' : ''); ?> />
                              <label for="cpf_size2">normal</label>
                           </div>
                        </div>

                        <div class="format format_2">
                           <div class="format_left">
                              <img src="<?php echo ADMIN_URL.'/img/format_3.jpg'; ?>" alt="" />
                              <input type="radio" class="newdesign" id="cpf_size3" name="cpf_size" value="klein_prop"<?php echo ($this->json['cpf_size'] == 'klein_prop' ? ' checked="checked" ' : ''); ?> />
                              <label for="cpf_size3">klein</label>
                           </div>
                           <div class="format_right">
                              <img src="<?php echo ADMIN_URL.'/img/format_4.jpg'; ?>" alt="" />
                              <input type="radio" class="newdesign" id="cpf_size4" name="cpf_size" value="normal_prop"<?php echo ($this->json['cpf_size'] == 'normal_prop' ? ' checked="checked" ' : ''); ?> />
                              <label for="cpf_size4">normal</label>
                           </div>
                        </div>

                        <div class="format format_2">
                           <div class="format_left">
                              <img src="<?php echo ADMIN_URL; ?>/img/format_5.jpg" alt="" />
                              <input type="radio" class="newdesign" id="cpf_size5" name="cpf_size" value="gross"<?php echo ($this->json['cpf_size'] == 'gross') ? ' checked="checked" ' : ''; ?> />
                              <label for="cpf_size5">groß</label>
                           </div>
                           <div class="format_right">
                              <img src="<?php echo ADMIN_URL.'/img/format_6.jpg'; ?>" alt="" />
                              <input type="radio" class="newdesign" id="cpf_size6" name="cpf_size" value="riesig"<?php echo ($this->json['cpf_size'] == 'riesig') ? ' checked="checked" ' : ''; ?> />
                              <label for="cpf_size6">riesig</label>
                           </div>
                        </div>
                     </div>
                     <div class="clear"></div>

                     <?php if (defined('CONF_MODULE_BILDFORMAT') && $template_id == 2) { ?>
                     <div class="line_design">
                        <div class="modul_bildformat">
                           <span title="Breite : Höhe">Neue Artikellisten-Fotos: 1 zu </span>
                           <input type="text" class="txt_inp inp50" name="image_ratio" value="<?php echo number_format((1 / $this->json['image_ratio']), 2, ',', ''); ?>" />
                           <?php // +++++++++++++ "Erstellen"-Button entfernt, der via CronJob die neuen Bildformate generiert +++++++++++++++ ?>
                           <?php // ++++++++ das Anfang/Ende rote php muss ich hier auch entfernen, wenn Button wieder aktiv sein soll +++++++ ?>
                           <?php // <div id="rebuild" class="button txt_but" onClick="Design.rebuildImages();">Erstellen</div> ?>
                        </div>
                     </div>
                     <div class="clear"></div>
                     <?php // ++++++++ <div class="line_design modul_bildformat hinweis">Hinweis: vor dem &bdquo;Erstellen&rdquo; speichern</div> +++++++ ?>
                     <?php } else { ?>
                     <div style="display:none;">
                        <input type="hidden" name="image_ratio" value="<?php echo (1 / $this->json['image_ratio']); ?>" />
                     </div>
                     <?php } ?>
                  <?php } ?>
                  </div>
                  <div class="clear"></div>
                  <hr />
               </div>
               <div class="clear"></div>

               <?php // +++++++++++++++++++++++++++ Banner unten ++++++++++++++++++++++++++++++ ?>
               <div id="banner_unten" class="easy design">
                  <div class="box_left">
                     <div class="txt_bez">
                        <input type="checkbox" class="newdesign" id="bannerunten_on" name="bannerunten_on"<?php echo ($this->json['bannerunten_on'] == 'y' ? 'checked="checked" ' : ''); ?> />
                        <label for="bannerunten_on"></label>Banner unten
                     </div>
                     <div class="upload_block_horiz">
                        <div class="upload upload_button pointer" onclick="Design.uploadImg('banner2', 0, 'banner_unten_img');" title="Datei hochladen"></div>
                        <div class="delete pointer far fa-trash-alt" onclick="Design.deleteImg('banner2', 0, 'banner_unten_img');" title="löschen"></div>
                        <div class="link pointer fas fa-link" onclick="Design.linkPopup('banner2', '<?php echo $sel_lang; ?>')" title="verlinken / SEO"></div>
                        <input type="hidden" id="banner2_link"   name="banner2_link"   value="<?php echo $this->params->links['bannerlink2']; ?>" />
                        <input type="hidden" id="banner2_intern" name="banner2_intern" value="<?php echo $this->params->links['banner2_intern']; ?>" />
                        <input type="hidden" id="banner2_seo"    name="banner2_seo"    value="<?php echo $this->params->links['bannerseo2']; ?>" />
                     </div>
                  </div>

                  <div id="pic_banner2" class="box_right bg_banner_width">
                     <div class="bg_banner_show bg_banner">
                        <!-- <img id="banner_unten_img" style="width:<?php echo $banner_unten_w; ?>px; max-width:100%; max-height:<?php echo $banner_unten_h; ?>px;" src="<?php echo $banner_unten.$this->params->firma['image_cache']; ?>" /> -->
                        <img id="banner_unten_img" src="<?php echo $banner_unten.$this->params->firma['image_cache']; ?>" />
                     </div>
                  </div>
                  <div class="clear"></div>
               </div>
               <div class="box_abstand easy"></div>
            </div>

            <div class="content_box_abstand"></div>
            <div class="titelzeile txt_tit no_help">Footer</div>
            <div class="content_box content_box_bottom">
               <?php // +++++++++++++++++++++++++++ Social Buttons ++++++++++++++++++++++++++++++ ?>
               <?php // $social_offset = 80 + $template_id * 10; ?>
               <?php // $social_index  = 25 - 8 + $template_id * 4; ?>
               <div id="social_buttons" class="design">
                  <div class="box_left">
                     <div class="txt_bez">Social Media</div>
                  </div>

                  <div id="social_html" class="box_right">
                     <div class="social_radio_line">
                        <div class="social_radio">
                           <input type="radio" class="newdesign" id="social_status1" name="social_status" onclick="$('#social_icons').hide();" value="nein" <?php echo ($this->params->firma['social_status'] == 'nein' ? 'checked="checked"' : ''); ?> />
                           <label for="social_status1">nicht anzeigen</label>
                        </div>
                        <div class="social_radio">
                           <input type="radio" class="newdesign" id="social_status2" name="social_status" onclick="$('#social_icons').show();" value="rechts" <?php echo ($this->params->firma['social_status'] == 'rechts' ? 'checked="checked"' : ''); ?> />
                           <label for="social_status2">rechts anzeigen</label>
                        </div>
                        <div class="social_radio">
                           <input type="radio" class="newdesign" id="social_status3" class="newdesign" id="" name="social_status" onclick="$('#social_icons').show();" value="unten" <?php echo ($this->params->firma['social_status'] == 'unten' ? 'checked="checked"' : ''); ?> />
                           <label for="social_status3">unten anzeigen</label>
                        </div>
                        <div class="call_me">
                           <input type="checkbox" class="newdesign" id="call_check" name="call_check" onchange="Design.callCheck();"<?php echo ($this->params->firma['call_check'] == 'y' ? ' checked="checked"' : ''); ?> />
                           <label for="call_check"><span class="call_me_icn"></span></label>
                        </div>
                     </div>
                     <div class="clear"></div>

                     <div id="social_icons"<?php echo ($this->params->firma['social_status'] == 'nein' ? ' style="display:none;"' : ''); ?>>
                        <?php echo $this->socialIconsHtml($social); ?>
                     </div>
                     <div class="clear"></div>
                  </div>
                  <div class="clear"></div>
               </div>
               <hr />

               <?php // +++++++++++++++++++++++++++ Footer ++++++++++++++++++++++++++++++ ?>
               <div id="footer" class="design">
                  <div class="box_left">
                     <div class="txt_bez">Footer</div>
                     <div class="easy relative">
                        <div class="upload_block_horiz">
                           <input type="checkbox" class="newdesign" id="flaeche_footer"  name="flaeche_footer"<?php echo ($this->json['flaeche_footer'] == 'y') ? ' checked="checked"' : ''; ?> />
                           <label for="flaeche_footer" class="after">bildschirmbreit</label>
                        </div>
                     </div>
                     <div class="cookiepopup button" onclick="Seiten.cookiePopup();">Cookiepopup</div>
                  </div>

                  <div class="box_right">
                     <div class="footer_mode">
                        <div class="footer_radios_img easy">
                           <div class="footer_radio left">
                              <input type="radio" class="newdesign" id="footer_mode1" name="footer_mode" value="freundlich"<?php echo ($this->json['footer_mode'] !== 'komplex' ? ' checked="checked"' : ''); ?> />
                              <label for="footer_mode1">benutzerfreundlich</label>
                           </div>
                           <div class="footer_radio_left_img left"></div>
                        </div>

                        <div class="footer_radios_img left easy">
                           <div class="footer_radio">
                              <input type="radio" class="newdesign" id="footer_mode2" name="footer_mode" value="komplex"<?php echo ($this->json['footer_mode'] == 'komplex' ? ' checked="checked"' : ''); ?> />
                              <label for="footer_mode2">komplex</label>
                           </div>
                           <div class="footer_radio_right_img left"></div>
                        </div>

                        <div class="footer_edit right easy">
                           <span>Zahlung/Versand-Icons</span>&nbsp;&nbsp;<div class="footer_radio_edit pointer fas fa-pencil-alt" onclick="Design.footerPopup();"></div>
                        </div>

                        <div id="footer_editor">
                           <div class="editor_wrapper">
                              <textarea class="edit_breit" id="footer_text" name="footer_text" rows="10" cols="100"><?php echo $text_array[1]; ?></textarea>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="clear"></div>
               </div>
            </div>
         </form>
      </div>
   </div>
   <?php $menu->footer(); ?>
</div>
<script>
var langs         = '<?php echo implode(';', $this->params->langs); ?>'; // vorhandene Sprachen - Nicht bei allen Templates notwendig
//var sel_lang      = 'deu'; // gewählte Sprache - nicht bei allen Templates notwendig
var sel_lang      = '<?php echo $this->params->selected_lang; ?>';
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
<?php include ADMIN_PATH.'/editor_seiten.inc.php'; ?>
<script>
   initEditor2();
</script>
</body>
</html>
