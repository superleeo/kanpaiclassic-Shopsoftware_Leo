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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="##language##"
	lang="##language##">
<head>
<title><?php echo $titel_tag; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="robots" content="index, follow" />
<meta name="keywords" content="##keywords##" />
<meta name="description" content="##description##" />
<meta name="rights" content="Onlineshop, Webshop, Shopsysteme, Kanpai Classic Shopsoftware www.kanpaiclassic.com by Kanpai Classic Web Development" />
<meta name="language" content="##language##" />
<meta name="author" content="Onlineshop, Webshop, Shopsysteme, Kanpai Classic Shopsoftware www.kanpaiclassic.com by Kanpai Classic Web Development" />
<meta name="generator" content="Onlineshop, Webshop, Shopsysteme, Kanpai Classic Shopsoftware www.kanpaiclassic.com by Kanpai Classic Web Development" />
<link rel="stylesheet"
	href="<?php echo $params->basepath; ?>/templates/<?php echo $params->firma['template']; ?>/css/styles.css" />
<link rel="stylesheet" type="text/css"
	href="<?php echo $params->basepath; ?>/templates/<?php echo $params->firma['template']; ?>/css/colors.css" />
</head>
<body>
	<div
		style="position: absolute; display: table; height: 100%; width: 100%;">
		<div style="display: table-cell; vertical-align: middle;">
			<!--[if lte IE 7]>
<table style="width:100%; height:100%; vertical-align:middle;">
<tr style="vertical-align:middle;">
<td style="vertical-align:middle;">
<![endif]-->
			<div id="rahmen" class="bg_innen"
				style="margin: 0 auto; width: 600px; padding: 18px; border: 1px solid #000000;">
				<h1 class="txt_tit ueberschrift">
					<?php echo $infotitel; ?>
				</h1>
				<div style="margin-top: 20px;" id="info-inhalt" class="fliesstext">
					<?php echo $infotext; ?>
				</div>
			</div>
			<!--[if lte IE 7]>
</td>
</tr>
</table>
<![endif]-->
		</div>
	</div>
</body>
</html>
