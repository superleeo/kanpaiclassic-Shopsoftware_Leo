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
$langdata = '';
foreach ($params->langs as $lang) {
   $class = ($params->selected_lang == $lang) ? 'class="selected "' : '';
   $langdata .= '<a ' . $class . 'href="' . $params->getScript() . '/lang/' . $lang .'">' . strtoupper($lang) . "</a>";
}

if (!$params->isAjax) {
   $menu = KANPAICLASSIC\Control::getMenu();
   $menudata = $menu->menuData();
   $admin_config   = $menu->loadDesign();
}
$content = "Funktion $params->task nicht vorhanden. $params->func";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Kanpai Classic Shopsoftware - Administration</title>
<meta http-equiv="Content-Type" content="text/HTML; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
<style>
<?php include_once ADMIN_PATH.'/css/admin.css'; ?>
</style>
<script type="text/javascript" src="<?php echo $params->basepath; ?>/admin/js/admin.js"></script>
</head>

<body>
   <div id="page">
      <div id="rahmen1"></div>
      <div id="rahmen2"></div>
      <div id="rahmen3">
      <?php echo $menu->printHeader(); ?>
         <div id="menu">
            <?php echo $menudata; ?>
         </div>
         <div id="main">
            <div id="content">
               <div class="txt_tit">Funktion noch nicht vorhanden</div>
            </div>
         </div>
      </div>
   </div>
</body>
</html>
