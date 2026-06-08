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

$loginerror = (isset($this) ? $this->params->loginerror : $params->loginerror);
?><!DOCTYPE html>
<html lang="de">
<head>
<title>Kanpai Classic <?php echo (!defined('CONF_MODULE_PORTAL') ? 'Shopsystem' : 'Shopportal'); ?> - Admin Login</title>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
<meta http-equiv="pragma" content="no-cache" />
<meta http-equiv="cache-control" content="no-cache" />
<meta http-equiv="expires" content="0" />
<meta name="referrer" content="always" />
<style><?php include_once ADMIN_PATH.'/css/login.css'; ?></style>
</head>

<body onLoad="document.getElementById('username').focus()">
<div id="seite">
   <div id="login">
      <div id="rahmen">
         <div id="rahmen-innen">
            <form id="loginform" method="post" enctype="application/x-www-form-urlencoded" action="<?php echo ADMIN_URL_IDX; ?>/login">
               <div>
                  <?php if (defined('CONF_LOGIN_FAILED')) { ?>
                     <?php if ($loginerror > 0 && $loginerror <= round(CONF_LOGIN_FAILED / 2)) { ?>
                     <p class="loginerror" id="login_error">Benutzername oder Passwort falsch!<br>Sie haben noch <?php echo (CONF_LOGIN_FAILED - $loginerror); ?> Versuche</p>
                     <?php } else if ($loginerror > round(CONF_LOGIN_FAILED / 2) && $loginerror < CONF_LOGIN_FAILED) { ?>
                     <p class="loginerror" id="login_error">Benutzername oder Passwort falsch! Sie haben noch <?php echo (CONF_LOGIN_FAILED - $loginerror); ?> Versuche bis der Account gesperrt wird.</p>
                     <?php } else if ($loginerror > CONF_LOGIN_FAILED - 1) { ?>
                     <p class="loginerror" id="login_error">Account gesperrt.</p>
                     <?php } ?>
                  <?php } else { ?>
                     <?php if ($loginerror == true) { ?>
                     <p class='loginerror' id='login_error'>Benutzername oder Passwort falsch!</p>
                     <?php } ?>
                  <?php } ?>

                  <p class="logintext">Benutzername</p>
                     <input type="text" autofocus id="username" name="username" id="username" value="" tabindex="1" />
                  <p class="logintext">Passwort</p>
                  <input type="password" name="password" value="" tabindex="2" />
                  <input type="hidden" name="task" value="login" />
                  <input type="hidden" name="func" value="login" />
                  <div class="login-btn">
                     <input type="submit" name="button" value="Login" />
                  </div>
                  <div id="login_msg"></div>
                  <div id="login_forgotten" onclick="passForgotten();">Passwort vergessen?</div>
                  <?php echo (isset($this) ? $this->params->li_image : $params->li_image); ?>
               </div>
            </form>
         </div>
      </div>
   </div>
</div>
<script>
var admin_url_idx = '<?php echo ADMIN_URL_IDX; ?>';
var admin_url     = '<?php echo ADMIN_URL; ?>';
var shopurl_idx   = '<?php echo SHOP_URL_IDX; ?>';
var shop_url      = '<?php echo SHOP_URL; ?>';
var template_url  = '<?php echo TEMPLATE_URL; ?>';
</script>
<script src="<?php echo SHOP_URL; ?>/js/jquery3.min.js"></script>
<script src="<?php echo ADMIN_URL; ?>/js/admin.js"></script>
</body>
</html>
