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

$default_font      = 'Arial';
$default_size      = 14;

$params            = KANPAICLASSIC\Control::getParams();
$fonts_css         = '';
$font_size_formats = '"8px 9px 10px 11px 12px 13px 14px 15px 16px 18px 20px 24px 28px 32px 36px 40px"';

// Google-Fonts einbinden
$font_url  = SHOP_URL.'/fonts';
require SHOP_PATH.'/classes/base/googlefonts.inc.php';

// Default-fonts TinyMCE ohne font_formats
$fonts = [
     "Andale Mono='andale mono', times;",
     "Arial=arial, helvetica, sans-serif;",
     "Arial Black='arial black', 'avant garde';",
     "Book Antiqua='book antiqua', 'palatino';",
     "Comic Sans MS='comic sans ms', sans-serif;",
     "Courier New='courier new', courier;",
     "Century Gothic=century_gothic;",
     "Georgia=georgia, palatino;",
     "Helvetica=helvetica;",
     "Impact=impact, chicago;",
     "Iskola Pota=iskoola_pota;",
     "Iskola Pota Bold=iskoola_pota_bold;",
     "Symbol=symbol;",
     "Tahoma=tahoma, arial, helvetica, sans-serif;",
     "Terminal=terminal,monaco;",
     "Times New Roman='times new roman', times;",
     "Trebuchet MS='trebuchet ms', geneva;",
     "Verdana=verdana, geneva;",
     "Webdings=webdings;",
     "Wingdings=wingdings, 'zapf dingbats';"
];

if (is_numeric($params->firma['fontfamily1']) && (int)$params->firma['fontfamily1'] > 100) {
   $fonts[]    = $googlefonts[$params->firma['fontfamily1']][0].'='.$googlefonts[$params->firma['fontfamily1']][2].';';
   $fonts1     = $googlefonts[$params->firma['fontfamily1']][0].'='.$googlefonts[$params->firma['fontfamily1']][2].';';
   $fonts_css .= $googlefonts[$params->firma['fontfamily1']][5].CR;
}

if (is_numeric($params->firma['fontfamily2']) && (int)$params->firma['fontfamily2'] > 100) {
   $fonts[] = $googlefonts[$params->firma['fontfamily2']][0].'='.$googlefonts[$params->firma['fontfamily2']][2].';';

   // Doppeltes Einbinden verhindern
   if (strpos($fonts_css, $googlefonts[$params->firma['fontfamily2']][5]) === false) {
      $fonts_css .= $googlefonts[$params->firma['fontfamily2']][5].CR;
   }
}

if (is_numeric($params->firma['fontfamily3']) && (int)$params->firma['fontfamily3'] > 100) {
   $fonts[]      = $googlefonts[$params->firma['fontfamily3']][0].'='.$googlefonts[$params->firma['fontfamily3']][2].';';
   $default_font = $googlefonts[$params->firma['fontfamily3']][2];
   $default_size = $params->firma['fontsize3'];

   if (strpos($fonts_css, $googlefonts[$params->firma['fontfamily3']][5]) === false) {
      $fonts_css .= $googlefonts[$params->firma['fontfamily3']][5].CR;
   }
}

if (is_numeric($params->firma['fontfamily4']) && (int)$params->firma['fontfamily4'] > 100) {
   $fonts[] = $googlefonts[$params->firma['fontfamily4']][0].'='.$googlefonts[$params->firma['fontfamily4']][2].';';


   if (strpos($fonts_css, $googlefonts[$params->firma['fontfamily4']][5]) === false) {
      $fonts_css .= $googlefonts[$params->firma['fontfamily4']][5].CR;
   }
}

// Zeichensätze alphabetisch sortieren
$fonts = array_unique($fonts);
sort($fonts);
$fonts[count($fonts) - 1] = rtrim($fonts[count($fonts) - 1], ';');

// Für Editor-Inhalt speichern
if (!file_exists(TEMPLATE_PATH.'/save/editor_content.css')) {
   if (!is_dir(TEMPLATE_PATH.'/save')) {
      mkdir(TEMPLATE_PATH.'/save');
   }

   file_put_contents(TEMPLATE_PATH.'/save/editor_content.css', $fonts_css);
}

if ($fonts_css != '') {
   echo '<style>'.CR;
   echo $fonts_css;
   echo '</style>'.CR;
}
?>
<script src="<?php echo ADMIN_URL; ?>/js/tinymce/jquery.tinymce.min.js"></script>
<script>
function extendedInit() {
   if (typeof(tinymce) === 'object' && typeof(tinymce.get) === 'function') {
      tinymce.remove();
   }

   $('textarea.accordion_editor').tinymce({
      script_url : admin_url+'/js/tinymce/tinymce.gzip.php',
      theme      : "modern",
      branding   : false,
      language   : "de",
      schema     : "html5",
      statusbar  : true,
      height     :200,
      width      : 'auto',

      plugins: "advlist,code,colorpicker,contextmenu,image,imagetools,link,lists,media,nonbreaking,paste,searchreplace,tabfocus,table,textcolor,visualblocks,visualchars",
      menu: {
             table:  {title: 'Table',  items: 'inserttable, tableprops, deletetable, cell, row, column'},
             insert: {title: 'Insert', items: 'media, image, link, unlink, |, charmap, hr, anchor, pagebreak, insertdatetime, visualblocks, formats, template'}
      },
      toolbar: "undo redo | cut copy paste | forecolor backcolor | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | preview | removeformat | code",
      convert_urls : false,
      invalid_elements : 'form',
      extended_valid_elements : 'script[src],iframe[align|class|frameborder|height|id|marginheight|marginwidth|name|sandbox|scrolling|src|style|width]',
      content_css: ['<?php echo TEMPLATE_URL; ?>/save/editor_content.css',
                    '<?php echo TEMPLATE_URL; ?>/css/editor.css'
                   ],
      fontsize_formats : <?php echo $font_size_formats; ?>,
      <?php
      echo 'font_formats : "';

      for($i = 0; $i < count($fonts); $i++) {
         echo trim($fonts[$i], '"');
      }

      echo '",'.CR;
      ?>

      setup : function(ed) {
         ed.on('init', function() {
            this.getDoc().body.style.fontFamily = "<?php echo $default_font; ?>";
            this.getDoc().body.style.fontSize   = "<?php echo $default_size; ?>px";
         });
      }
   });

   $('input.minicolors', $('#accordion_html')).minicolors({
      control: 'wheel',
      swatchPosition: 'right'
   });

}

extendedInit();
</script>
