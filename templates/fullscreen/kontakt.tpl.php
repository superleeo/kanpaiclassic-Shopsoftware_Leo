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

$code = '';
include_once SHOP_PATH.'/classes/captcha/captcha.php';

// code sichern, bevor er durch neues Captcha überschrieben wird
if (isset($_SESSION['captcha'])) {
   $code = $_SESSION['captcha']['code'];
}

$_SESSION['captcha'] = \KANPAICLASSIC\captcha();

$is_error = false;
$kontakt_name = '';
$kontakt_mail = '';

if ($params->email != '') {
   $kontakt_mail = $this->params->email;
}

$kontakt_telefon    = '';
$kontakt_nachricht = '';
$kontakt_ds = 'n';

$err_name = false;
$err_mail = false;
$err_telefon = false;
$err_nachricht = false;
$err_captcha = false;
$err_ds = false;
$first_check = $params->postString('check');

if ($first_check == '1') {
   $kontakt_name       = $params->postString('kontakt_name');
   $kontakt_mail       = $params->postString('kontakt_mail');
   $kontakt_telefon    = $params->postString('kontakt_telefon');
   $kontakt_nachricht  = $params->postString('kontakt_nachricht', '', 'html');
   $kontakt_captcha    = $params->postString('kontakt_captcha');
   $kontakt_ds         = $params->postCheckbox('kontakt_ds');

/* 12.09. 2018 Überprüfung Name deaktiviert
   if ($kontakt_name == '') {
      $err_name = true;
      $is_error = true;
   }
*/

//   if (!preg_match("/^[_a-zA-Z0-9-](\.{0,1}[_a-zA-Z0-9-])*@([_a-zA-Z0-9-äöüß]{2,63}\.){0,}[_a-zA-Z0-9-äöüß]{2,63}(\.[_a-zA-Z]{2,}){1,2}$/i", $kontakt_mail)) {
   if (!preg_match("/^[_a-zA-Z0-9-](\.{0,1}[_a-zA-Z0-9-])*@([_a-zA-Z0-9-äöüß]{2,63}\.){0,}[_a-zA-Z0-9-äöüß]{2,63}(\.[_a-zA-Z]{2,})$/i", $kontakt_mail)) {
      $err_mail = true;
      $is_error = true;
   }

   if ($kontakt_nachricht == '') {
      $err_nachricht = true;
      $is_error = true;
   }

   if ($kontakt_captcha != $code) {
      $err_captcha = true;
      $is_error = true;
   }

   if (defined('CONF_HAEKCHEN') && $kontakt_ds != 'y') {
      $err_ds = true;
      $is_error = true;
   }
}

$pic_html  = '';
$img_path  = TEMPLATE_URL.'/images/';
$file_path = TEMPLATE_PATH.'/images/';
$image1    = KANPAICLASSIC\Helper::getData('ueberuns12_'.$lang.'_image1');
$image2    = KANPAICLASSIC\Helper::getData('ueberuns12_'.$lang.'_image2');
$link1     = KANPAICLASSIC\Helper::getData('ueberuns12_'.$lang.'_link1');
$link2     = KANPAICLASSIC\Helper::getData('ueberuns12_'.$lang.'_link2');
$intern1   = KANPAICLASSIC\Helper::getData('ueberuns12_'.$lang.'_intern1');
$intern2   = KANPAICLASSIC\Helper::getData('ueberuns12_'.$lang.'_intern2');
$search1   = KANPAICLASSIC\Helper::getData('ueberuns12_'.$lang.'_search1');
$search2   = KANPAICLASSIC\Helper::getData('ueberuns12_'.$lang.'_search2');

// Bilder
if ($image1 != '' && file_exists($file_path.$image1.'.jpg') || $image2 != '' && file_exists($file_path.$image2.'.jpg')) {
   $pic_html .= '<div class="col_single col_img">';
   $pic_html .= '   <div class="col_lsl_l style="min-height:1px;">';

   if ($image1 != '' && file_exists($file_path.$image1.'.jpg')) {
      $img_html = '<img src="'.$img_path.$image1.'.jpg" alt="'.$search1.'" title="'.$search1.'" />';

      if ($link1 != '') {
         $pic_html .= '<a href="'.$link1.'" target="'.($intern1 == 'y' ? '_self' : '_blank').'" title="'.$search1.'">'.$img_html.'</a>';
      }

      else {
         $pic_html .= $img_html;
      }
   }

   $pic_html .= '   </div>';
   $pic_html .= '   <div class="col_lsl_m"></div>';
   $pic_html .= '   <div class="col_lsl_r">';

   if ($image2 != ''&& file_exists($file_path.$image2.'.jpg')) {
      $img_html = '<img src="'.$img_path.$image2.'.jpg" alt="'.$search2.'"  title="'.$search2.'" />';
      if ($link2 != '') {
         $pic_html .= '<a href="'.$link2.'" target="'.($intern2 == 'y' ? '_self' : '_blank').'"title="'.$search2.'">'.$img_html.'</a>';
      }
      else {
         $pic_html .= $img_html;
      }
   }

   $pic_html .= '   </div>';
   $pic_html .= '</div>';
   $pic_html .= '<div class="clear"></div><br />';
}
// Kontakt-Formular
?>
<div class="x_kontakt">
<div class="col_single site_head bg_flaechen">
   <div class="ueberschrift text_max">
      <?php echo $infotitel; ?>
   </div>
</div>

<div id="kontakt" class="bg_flaechen">
   <div class="col_inner">
      <?php echo $pic_html; ?>

      <div class="col_single">
         <?php if ($params->firma['kontakt_inhaber'] == "y") { ?>
         <div id="info-inhalt" class="fliesstext text_normal">
            <div class="col_single fliesstext text_normal">
               <?php echo $inhaber; ?>
            </div>
         </div>
         <?php } ?>
         <div class="col_single">
         <?php if ($text_check ==='y') { // 'y' -> rechts / 'n' -> unten' ?>
            <div class="col_lsl_l fliesstext text_normal">
         <?php } else { ?>
            <div class="fliesstext text_normal">
         <?php } ?>
               <?php echo $kontakt_text; ?>
         <?php if ($text_check ==='y') { ?>
               <br />
            </div>
         <?php } else { ?>
            </div>
         <?php } ?>

         <?php if ($text_check ==='y') { ?>
            <div class="col_lsl_m"> </div>
         <?php } ?>

         <?php if ($text_check ==='y') { ?>
            <div class="col_lsl_r">
               <div class="" style="width:80%; max-width:500px; margin:auto;">
         <?php } else { ?>
            <div class="">
               <div class="" style="width:90%; max-width:500px; margin:auto;">
         <?php } ?>
                  <form action="" method="post">
                     <input type="hidden" name="check" value="1" />

                     <?php $err = $err_name ? " form_err" : ""; ?>
                     <div class="line">
                         <input type="text" class="text_formular text_normal<?php echo $err; ?>" name="kontakt_name" value="<?php echo $kontakt_name; ?>" placeholder="<?php echo $text->get('kontakt', 'name'); ?>" />
                     </div>

                     <?php $err = $err_mail ? " form_err" : ""; ?>
                     <div class="line">
                         <input type="text" class="text_formular text_normal<?php echo $err; ?>" name="kontakt_mail" value="<?php echo $kontakt_mail; ?>" placeholder="<?php echo $text->get('login', 'ihremail'); ?>&nbsp;*" />
                     </div>

                     <?php $err = $err_telefon ? " form_err" : ""; ?>
                     <div class="line">
                         <input type="text" class="text_formular text_normal<?php echo $err; ?>" name="kontakt_telefon" value="<?php echo $kontakt_telefon ?>" placeholder="<?php echo $text->get('kunde', 'telefon'); ?>" />
                     </div>

                     <?php $err = $err_nachricht ? " form_err" : ""; ?>
                     <div class="line">
                        <textarea class="text_formular text_normal<?php echo $err; ?>" name="kontakt_nachricht" placeholder="<?php echo $text->get('kunde', 'nachricht'); ?>&nbsp;*"><?php echo str_replace('<br />', "\n", $kontakt_nachricht); ?></textarea>
                     </div>

                  <?php if ($is_error || $first_check == '') { ?>
                     <div class="line center">
                        <img src="<?php echo $_SESSION['captcha']['image_src']; ?>" />
                     </div>

                     <?php $err = $err_captcha ? " form_err" : ""; ?>
                     <div class="line">
                         <input type="text" class="text_formular text_normal<?php echo $err; ?>" name="kontakt_captcha" value="" placeholder="<?php echo $text->get('kontakt', 'scode'); ?>&nbsp;*" />
                     </div>

                     <?php $err = $err_ds ? " form_err" : ""; ?>
                     <div class="line checkline<?php echo $err; ?>">
                     <?php if (defined('CONF_HAEKCHEN')) { ?>
                        <span class="kontakt_ds_text fliesstext text_normal"><?php echo $text->get('kunde', 'lesen1', 'lang').' <a href="'.SHOP_URL_IDX.'/datenschutz" class="fliesstext" target="_blank"><strong>'.$text->get('kunde', 'daten', 'lang').'</strong></a> '.$text->get('kunde', 'lesen2', 'lang'); ?></span>
                        <input type="checkbox" name="kontakt_ds" id="kontakt_ds"<?php echo ($kontakt_ds == 'y' ? ' checked="checked"' : ''); ?> />
                        <span class="checkbox"></span>
                     <?php } else { ?>
                        <span class="fliesstext text_normal"><?php echo $text->get('kunde', 'lesen1', 'lang').' <a href="'.SHOP_URL_IDX.'/datenschutz" class="fliesstext" target="_blank"><strong>'.$text->get('kunde', 'daten', 'lang').'</strong></a> '.$text->get('kunde', 'lesen2', 'lang'); ?></span>
                     <?php } ?>
                     </div>
                  <?php } ?>

                     <?php if ($is_error || $first_check != 1) { ?>
                     <div class="line">
                        <button class="col_single col_button bg_button text_gross button55"><?php echo $text->get('button', 'senden'); ?></button>
                     </div>
                     <?php } else {
                        // $mail = KANPAICLASSIC\Control::getPhpMailer();
                        // $mail->CharSet = 'UTF-8';
                        $mail_to   = '';
                        $mail_from = '';


                        if (!defined('CONF_KONTAKT_KUNDE')) {
//                           $mail->AddAddress($params->firma['email']);
                           $mail_to = $params->firma['email'];
//                           $mail->SetFrom($params->firma['email'], $params->firma['mailfrom']);
                           $mail_from = [$params->firma['email'], $params->firma['mailfrom']];
                        }

                        else {
                           // $mail->AddAddress($params->firma['email']);
                           $mail_to = $params->firma['email'];
                           // $mail->SetFrom($kontakt_mail);
                           $mail_from = [$kontakt_mail];
                        }

                        // $mail->Subject = 'Kontaktanfrage von '. $params->firma['shop_name'];
                        $mail_subject = 'Kontaktanfrage von '. $params->firma['shop_name'];
                        // $mail->MsgHTML(nl2br($kontakt_nachricht).'<br /><br />'.$kontakt_name.'<br /><a href="mailto:'.$kontakt_mail.'">'.$kontakt_mail.'</a><br />Telefon: '.$kontakt_telefon);
                        $mail_html = (nl2br($kontakt_nachricht).'<br /><br />'.$kontakt_name.'<br /><a href="mailto:'.$kontakt_mail.'">'.$kontakt_mail.'</a><br />Telefon: '.$kontakt_telefon);

                        $mailer = KANPAICLASSIC\Control::getMail();
                        if (!$mailer->sendDirect($mail_to, $mail_from, $mail_subject, $mail_html)) {
//                        if(!$mail->Send()) { ?>
                     <div class="line center">
                        <span class="fliesstext text_normal form_err"><?php echo $text->get('kontakt', 'fehler'); ?></span>
                     </div>
                        <?php }
                        else { ?>
                     <div class="line center">
                        <span class="fliesstext text_gross center form_ok"><?php echo $text->get('kontakt', 'versendet'); ?></span>
                     </div>
                        <?php }
                     } ?>
                  </form>
         <?php if ($text_check !=='y') { ?>
               </div>
            </div>
         <?php } else { ?>
               </div>
            </div>
         <?php } ?>
            <div class="clear"></div>
         </div>
      </div>
   </div>
</div>
</div>
