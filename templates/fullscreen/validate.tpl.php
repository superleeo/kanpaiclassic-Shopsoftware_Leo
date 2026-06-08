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
   define('KANPAICLASSIC', true);
}

$fonts     = [];
$fonts_css = '';
$font_url  = SHOP_URL.'/fonts';
require SHOP_PATH.'/classes/base/googlefonts.inc.php';

// is_numeric wegen Fehlermeldungen bei Umstellung
$fonts[] = (is_numeric($params->firma['fontfamily1']) ? $googlefonts[$params->firma['fontfamily1']] : array('', 400, 'Arial', '', ''));
$fonts[] = (is_numeric($params->firma['fontfamily2']) ? $googlefonts[$params->firma['fontfamily2']] : array('', 400, 'Arial', '', ''));
$fonts[] = (is_numeric($params->firma['fontfamily3']) ? $googlefonts[$params->firma['fontfamily3']] : array('', 400, 'Arial', '', ''));
$fonts[] = (is_numeric($params->firma['fontfamily4']) ? $googlefonts[$params->firma['fontfamily4']] : array('', 400, 'Arial', '', ''));

// String für Google-Fonts bauen
foreach ($fonts as $font) {
   if ($font[3] != '') {
      if ($fonts_css != '') {
         $fonts_css .= '|';
      }

      $fonts_css .= $font[3];
   }
}

$fontsize1 = 22;
if (isset($params->firma['fontsize1'])) {
   $fontsize1 = $params->firma['fontsize1'];
}
$fontsize2 = 18;
if (isset($params->firma['fontsize2'])) {
   $fontsize2 = $params->firma['fontsize2'];
}
$fontsize3 = 14;
if (isset($params->firma['fontsize3'])) {
   $fontsize3 = $params->firma['fontsize3'];
}
$fontsize4 = 12;
if (isset($params->firma['fontsize4'])) {
   $fontsize4 = $params->firma['fontsize4'];
}
?><html>
<head>
<title>Validierung</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="x-ua-compatible" content="IE=edge" />
<?php if ($params->validate1 == 'changed') { ?>
<meta http-equiv="refresh" content="5; url=<?php echo SHOP_URL_IDX; ?>/login" />
<?php } ?>
<?php if ($params->validate1 == 'newsletter_ok') { ?>
<meta http-equiv="refresh" content="5; url=<?php echo SHOP_URL; ?>" />
<?php } ?>
<link rel="stylesheet" href="<?php echo TEMPLATE_URL; ?>/css/template.css" />
<?php if ($fonts_css) { ?>
<link href='https://fonts.googleapis.com/css?family=<?php echo $fonts_css; ?>' rel='stylesheet' type='text/css' />
<?php } ?>
<style>
.text_max { font-family:<?php echo $fonts[0][2]; ?>; font-weight:<?php echo $fonts[0][1]; ?>; font-size:<?php echo $fontsize1; ?>px; }
.text_gross { font-family:<?php echo $fonts[1][2]; ?>; font-weight:<?php echo $fonts[1][1]; ?>; font-size:<?php echo $fontsize2; ?>px; }
.text_normal { font-family:<?php echo $fonts[2][2]; ?>; font-weight:<?php echo $fonts[2][1]; ?>; font-size:<?php echo $fontsize3; ?>px; }
.text_klein { font-family:<?php echo $fonts[3][2]; ?>; font-weight:<?php echo $fonts[3][1]; ?>; font-size:<?php echo $fontsize4; ?>px; }
<?php include_once TEMPLATE_PATH.'/css/colors.css'; ?>
</style>
</head>
<body>
   <div id="validate">
      <div class="login">
         <div id="rahmen">
            <div id="rahmen-innen" class="bg_flaechen">
            <?php if ($params->validate1 == 'newsletter_fail' || $params->validate1 == 'fail') { ?>
               <form action="<?php echo SHOP_URL; ?>" method="post">
                  <div class="ueberschrift text_max center"><?php echo $text->get('vali', 'fail'); ?></div>
                  <br /><br />
                  <button class="bg_button col_button text_gross button55 center"><?php echo $text->get('button', 'weiter'); ?></button>
               </form>

            <?php } else if ($params->validate1 == 'anmeldung') { ?>
               <?php if ($params->is_haendler) { ?>
               <form action="<?php echo SHOP_URL; ?>" method="post">
                  <div class="ueberschrift text_max center"><?php echo $text->get('vali', 'ok'); ?></div>
                  <br /><br />
                  <button class="bg_button col_button text_gross button55 center"><?php echo $text->get('button', 'weiter'); ?></button>
               </form>
               <?php } else { ?>
               <form action="<?php SHOP_URL_IDX; ?>/login" method="post">
                  <div class="ueberschrift text_max center"><?php echo $text->get('vali', 'ok'); ?></div>
                  <br /><br />
                  <button class="bg_button col_button text_gross button55 center"><?php echo $text->get('button', 'weiter'); ?></button>
               </form>
               <?php } ?>

            <?php } else if ($params->validate1 == 'newsletter_ok') { ?>
               <?php if ($params->is_haendler) { ?>
               <form action="<?php echo SHOP_URL; ?>" method="post">
                  <div class="ueberschrift text_max center"><?php echo $text->get('vali', 'ok'); ?></div>
                  <br /><br />
                  <button class="bg_button col_button text_gross button55 center"><?php echo $text->get('button', 'weiter'); ?></button>
               </form>
               <?php } else { ?>
               <form action="<?php echo SHOP_URL_IDX; ?>/konto" method="post">
                  <div class="ueberschrift text_max center"><?php echo $text->get('vali', 'ok'); ?></div>
                  <br /><br />
                  <button class="bg_button col_button text_gross button55 center"><?php echo $text->get('button', 'weiter'); ?></button>
               </form>
               <?php } ?>

            <?php } else if ($params->validate1 == 'login') { ?>
               <form action="<?php echo SHOP_URL; ?>" method="post">
                  <div class="ueberschrift text_max center"><?php echo $text->get('vali', 'ok'); ?></div>
                  <br /><br />
                  <button class="bg_button col_button text_gross button55 center"><?php echo $text->get('button', 'weiter'); ?></button>
               </form>

            <?php } else if ($params->validate1 == 'changed') { ?>
               <?php if ($params->is_haendler) { ?>
               <form action="<?php echo SHOP_URL; ?>" method="post">
                  <div class="ueberschrift text_max center"><?php echo $text->get('vali', 'pw_neu'); ?></div>
                  <br /><br />
                  <button class="bg_button col_button text_gross button55 center"><?php echo $text->get('button', 'weiter'); ?></button>
               </form>
               <?php } else { ?>
               <form action="<?php echo SHOP_URL_IDX; ?>/konto" method="post">
                  <div class="ueberschrift text_max center"><?php echo $text->get('vali', 'pw_neu'); ?></div>
                  <br /><br />
                  <button class="bg_button col_button text_gross button55 center"><?php echo $text->get('button', 'weiter'); ?></button>
               </form>
               <?php } ?>

            <?php } else if ($params->validate1 == 'password' || $params->validate1 == 'pw_fail') { ?>
               <form action="<?php echo SHOP_URL_IDX.'/validate/'.$params->validate; ?>" method="post" onsubmit="return checkPws();">
                  <div class="ueberschrift text_max center"><?php echo $text->get('vali', 'pw_tit'); ?></div>
                  <div class="halfline"></div>
                  <div class="line">
                     <input type="hidden" name="pwcheck" value="1" />
                     <div class="line_left fliesstext text_normal"><?php echo $text->get('kunde', 'password1'); ?></div>
                     <div class="line_center"></div>
                     <div class="line_right"><input type="password" class="text_formular text_gross" id="password1" name="password1" value="" autofocus /></div>
                     <div class="clear"></div>
                  </div>

                  <div class="line">
                     <div class="line_left fliesstext text_normal"><?php echo $text->get('kunde', 'password1'); ?></div>
                     <div class="line_center"></div>
                     <div class="line_right"><input type="password" class="text_formular text_gross" id="password2" name="password2" value="" autofocus /></div>
                     <div class="clear"></div>
                  </div>
                  <div class="halfline"></div>
                  <?php if ($params->validate1 == 'pw_fail') { ?>
                  <div class="fliesstext text_normal center form_err"><?php echo $text->get('vali', 'pw_fail'); ?></div>
                  <?php } ?>
                  <button class="bg_button col_button text_gross button55 center"><?php echo $text->get('button', 'weiter'); ?></button>
               </form>
            <?php } ?>

            </div>
         </div>
      </div>
   </div>
<script type="text/javascript" src="<?php echo $params->basepath; ?>/js/jquery3.js"></script>
<script>
   function checkPws() {
      if ($('#password1').val() !== $('#password2').val()) {
         $('#password2').css('color', '#cc0000');
         $('.line_left', $('#password2').closest('.line')).css('color', '#cc0000');
         return false;
      }

      return true;
   }
</script>
</body>
</html>
