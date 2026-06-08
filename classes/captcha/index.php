<?php
/*
###################################################################################
  FLOW Shopssoftware Entwicklungsstand: 05.10.2021 FLOW III Version 16.0

  RoyalArt - Agentur für Softwaregestaltung
  https://www.royalart.de
  https://www.shopsoftware.com

  c Copyright by Dipl. Des. FH Sven Scholz - RoyalArt Agentur


  Copyrightvermerke duerfen NICHT entfernt werden!

  ------------------------------------------------------------------------
  Dieses Programm ist eine Software von Dipl. Des. FH Sven Scholz, RoyalArt Agentur.
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

session_start();
$_SESSION = array();

include("simple-php-captcha.php");
$_SESSION['captcha'] = captcha();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Example &raquo; A simple PHP CAPTCHA script</title>
        <style type="text/css">
        	PRE {
        		border: solid 1px #BBB;
        		padding: 10px;
        		margin: 2em;
        	}
        	
        	IMG {
        		margin: 0 2em;
        	}
        </style>
    </head>
    <body>
        <h1>
            CAPTCHA Example
        </h1>
        
        <h2>Usage</h2>
        
        <p>
        	The following code will prepare a CAPTCHA image and keep the code in a session
        	variable for later use:
        </p>
        
<pre>
&lt;?php

session_start();
include("captcha.php");
$_SESSION['captcha'] = captcha();

?&gt;
</pre>
        
        <p>
        	Dump of <code>$_SESSION['captcha']</code>:
        </p>
        
<pre>
<?php
print_r($_SESSION['captcha']);
?>
</pre>
        
        <p>
        	To display the CAPTCHA image, create an HTML &lt;img&gt; using <code>$_SESSION['captcha']['image_src']</code> 
        	as the <code>src</code> attribute:
        </p>
        
        <p>
        	<?php
        	echo '<img src="' . $_SESSION['captcha']['image_src'] . '" alt="CAPTCHA" />';
        	
        	?>
        </p>
        
        <p>
        	To verify the CAPTCHA value, just test against the <code>$_SESSION['captcha']['code']</code>. Use 
        	<code>strtolower()</code> or <code>strtoupper()</code> to perform a case-insensitive match.
        </p>
        
        <h2>Configuration</h2>
        <p>Configuration is easy and all values are optional. To specify one or more options, do this:</p>
        
<pre>
&lt;?php

// Showing default values
$_SESSION['captcha'] = captcha( array(
	'min_length' => 5,
	'max_length' => 5,
	'png_backgrounds' => array('default.png', ...),
	'fonts' => array('times_new_yorker.ttf', ...),
	'characters' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',
	'min_font_size' => 24,
	'max_font_size' => 30,
	'color' => '#000',
	'angle_min' => 0,
	'angle_max' => 15,
	'shadow' => true,
	'shadow_color' => '#CCC',
	'shadow_offset_x' => -2,
	'shadow_offset_y' => 2
));

&gt;
</pre>
        
        <h2>Notes</h2>
        
        <ul>
        	<li>Make sure you call <code>session_start()</code> before calling the <code>captcha()</code> function</li>
        	<li>Backgound images must be in PNG format</li>
        	<li>Backgrounds and fonts must be specified using their full paths (tip: use $_SERVER['DOCUMENT_ROOT'] . [path-to-file] or a path relative to the script that is executing)</li>
        	<li>Angles should not exceed approximately 15 degrees, as the text will sometimes appear outside of the viewable area</li>
        </ul>
        
    </body>
</html>
