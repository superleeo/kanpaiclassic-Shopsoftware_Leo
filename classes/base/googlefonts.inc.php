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

// Bei Änderungen Sortierung / Index design_colors.class.php / _getFontCSS mit ändern
// Konverter: http://google-webfonts-helper.herokuapp.com
// Zukünftig nur noch .woff/.woff2 verwenden (wie bei Open Sans)
$googlefonts = [];
   // [
   //         1. Name,
   //         2. Size,        -> 400 - normal; 700 - bold;
   //         3. Font-family, -> Name
   //         4. Link,        -> für Link Google-Fonts
   //         5. Subset,      -> Info für Verfügbarkeit
   //         6. CSS-Code     -> Schriften wegen DSGVO lokal einbinden, statt Link
   // ]


// Default-Font, falls nichts gewählt
$googlefonts[100] = [
   'Arial',
    400,
   'Arial, sans-serif',
   '',
   '',
   ''
];

$googlefonts[200] = [
   'Open Sans Condensed',
    300,
   "'Open Sans Condensed', sans-serif",
   'Open+Sans+Condensed:300',
   'latin, latin-ext, greek, greek_ext, cyrillic, cyrillic_ext',
   "/* open-sans-condensed-300 - latin_cyrillic-ext_greek_latin-ext_cyrillic */
   @font-face {
     font-family: 'Open Sans Condensed';
     font-style: normal;
     font-weight: 300;
     src: url('".$font_url."/open-sans-condensed-v12-latin_cyrillic-ext_greek_latin-ext_cyrillic-300.eot'); /* IE9 Compat Modes */
     src: local('Open Sans Condensed Light'), local('OpenSansCondensed-Light'),
          url('".$font_url."/open-sans-condensed-v12-latin_cyrillic-ext_greek_latin-ext_cyrillic-300.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
          url('".$font_url."/open-sans-condensed-v12-latin_cyrillic-ext_greek_latin-ext_cyrillic-300.woff2') format('woff2'), /* Super Modern Browsers */
          url('".$font_url."/open-sans-condensed-v12-latin_cyrillic-ext_greek_latin-ext_cyrillic-300.woff') format('woff'), /* Modern Browsers */
          url('".$font_url."/open-sans-condensed-v12-latin_cyrillic-ext_greek_latin-ext_cyrillic-300.ttf') format('truetype'), /* Safari, Android, iOS */
          url('".$font_url."/open-sans-condensed-v12-latin_cyrillic-ext_greek_latin-ext_cyrillic-300.svg#OpenSansCondensed') format('svg'); /* Legacy iOS */
   }"
];

$googlefonts[300] = [
   'Poiret One',
   400,
   "'Poiret One', cursive",
   'Poiret+One',
   'latin, latin_ext, cyrillic',
   "/* poiret-one-regular - latin_latin-ext_cyrillic */
   @font-face {
     font-family: 'Poiret One';
     font-style: latin, latin_ext, cyrillic;
     font-weight: 400;
     src: url('".$font_url."/poiret-one-v5-latin_latin-ext_cyrillic-regular.eot'); /* IE9 Compat Modes */
     src: local('Poiret One'), local('PoiretOne-Regular'),
          url('".$font_url."/poiret-one-v5-latin_latin-ext_cyrillic-regular.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
          url('".$font_url."/poiret-one-v5-latin_latin-ext_cyrillic-regular.woff2') format('woff2'), /* Super Modern Browsers */
          url('".$font_url."/poiret-one-v5-latin_latin-ext_cyrillic-regular.woff') format('woff'), /* Modern Browsers */
          url('".$font_url."/poiret-one-v5-latin_latin-ext_cyrillic-regular.ttf') format('truetype'), /* Safari, Android, iOS */
          url('".$font_url."/poiret-one-v5-latin_latin-ext_cyrillic-regular.svg#PoiretOne') format('svg'); /* Legacy iOS */
   }"
];

$googlefonts[400] = [
   'Lobster',
   400,
   'Lobster, cursive',
   'Lobster',
   'latin, latin_ext, cyrillic',
    "/* lobster-regular - latin_cyrillic-ext_latin-ext_cyrillic */
   @font-face {
     font-family: 'Lobster';
     font-style: normal;
     font-weight: 400;
     src: url('".$font_url."/lobster-v20-latin_cyrillic-ext_latin-ext_cyrillic-regular.eot'); /* IE9 Compat Modes */
     src: local('Lobster Regular'), local('Lobster-Regular'),
          url('".$font_url."/lobster-v20-latin_cyrillic-ext_latin-ext_cyrillic-regular.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
          url('".$font_url."/lobster-v20-latin_cyrillic-ext_latin-ext_cyrillic-regular.woff2') format('woff2'), /* Super Modern Browsers */
          url('".$font_url."/lobster-v20-latin_cyrillic-ext_latin-ext_cyrillic-regular.woff') format('woff'), /* Modern Browsers */
          url('".$font_url."/lobster-v20-latin_cyrillic-ext_latin-ext_cyrillic-regular.ttf') format('truetype'), /* Safari, Android, iOS */
          url('".$font_url."/lobster-v20-latin_cyrillic-ext_latin-ext_cyrillic-regular.svg#Lobster') format('svg'); /* Legacy iOS */
   }"
];

$googlefonts[500] = [
   'Playfair Display',
   400,
   "'Playfair Display', serif",
   'Playfair+Display',
   'latin, latin_ext, cyrillic',
    "/* playfair-display-regular - latin_latin-ext_cyrillic */
   @font-face {
     font-family: 'Playfair Display';
     font-style: normal;
     font-weight: 400;
     src: url('".$font_url."/playfair-display-v13-latin_latin-ext_cyrillic-regular.eot'); /* IE9 Compat Modes */
     src: local('Playfair Display Regular'), local('PlayfairDisplay-Regular'),
          url('".$font_url."/playfair-display-v13-latin_latin-ext_cyrillic-regular.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
          url('".$font_url."/playfair-display-v13-latin_latin-ext_cyrillic-regular.woff2') format('woff2'), /* Super Modern Browsers */
          url('".$font_url."/playfair-display-v13-latin_latin-ext_cyrillic-regular.woff') format('woff'), /* Modern Browsers */
          url('".$font_url."/playfair-display-v13-latin_latin-ext_cyrillic-regular.ttf') format('truetype'), /* Safari, Android, iOS */
          url('".$font_url."/playfair-display-v13-latin_latin-ext_cyrillic-regular.svg#PlayfairDisplay') format('svg'); /* Legacy iOS */
   }"
];

$googlefonts[600] = [
   'Abel',
   400,
   'Abel, sans-serif',
   'Abel',
   'latin',
    "/* abel-regular - latin */
   @font-face {
     font-family: 'Abel';
     font-style: normal;
     font-weight: 400;
     src: url('".$font_url."/abel-v8-latin-regular.eot'); /* IE9 Compat Modes */
     src: local('Abel Regular'), local('Abel-Regular'),
          url('".$font_url."/abel-v8-latin-regular.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
          url('".$font_url."/abel-v8-latin-regular.woff2') format('woff2'), /* Super Modern Browsers */
          url('".$font_url."/abel-v8-latin-regular.woff') format('woff'), /* Modern Browsers */
          url('".$font_url."/abel-v8-latin-regular.ttf') format('truetype'), /* Safari, Android, iOS */
          url('".$font_url."/abel-v8-latin-regular.svg#Abel') format('svg'); /* Legacy iOS */
   }"
];

$googlefonts[700] = [
   'Orbitron',
   400,
   'Orbitron, sans-serif',
   'Orbitron',
   'latin',
   "/* orbitron-regular - latin */
   @font-face {
     font-family: 'Orbitron';
     font-style: normal;
     font-weight: 400;
     src: url('".$font_url."/orbitron-v9-latin-regular.eot'); /* IE9 Compat Modes */
     src: local('Orbitron Regular'), local('Orbitron-Regular'),
          url('".$font_url."/orbitron-v9-latin-regular.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
          url('".$font_url."/orbitron-v9-latin-regular.woff2') format('woff2'), /* Super Modern Browsers */
          url('".$font_url."/orbitron-v9-latin-regular.woff') format('woff'), /* Modern Browsers */
          url('".$font_url."/orbitron-v9-latin-regular.ttf') format('truetype'), /* Safari, Android, iOS */
          url('".$font_url."/orbitron-v9-latin-regular.svg#Orbitron') format('svg'); /* Legacy iOS */
   }"
];

$googlefonts[800] = [
   'Dancing Script',
   400,
   "'Dancing Script', cursive",
   'Dancing+Script',
   'latin',
   "/* dancing-script-regular - latin_latin-ext */
   @font-face {
     font-family: 'Dancing Script';
     font-style: normal;
     font-weight: 400;
     src: url('".$font_url."/dancing-script-v9-latin_latin-ext-regular.eot'); /* IE9 Compat Modes */
     src: local('Dancing Script Regular'), local('DancingScript-Regular'),
          url('".$font_url."/dancing-script-v9-latin_latin-ext-regular.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
          url('".$font_url."/dancing-script-v9-latin_latin-ext-regular.woff2') format('woff2'), /* Super Modern Browsers */
          url('".$font_url."/dancing-script-v9-latin_latin-ext-regular.woff') format('woff'), /* Modern Browsers */
          url('".$font_url."/dancing-script-v9-latin_latin-ext-regular.ttf') format('truetype'), /* Safari, Android, iOS */
          url('".$font_url."/dancing-script-v9-latin_latin-ext-regular.svg#DancingScript') format('svg'); /* Legacy iOS */
   }"
];

$googlefonts[900] = [
   'Gudea',
   400,
   'Gudea, sans-serif',
   'Gudea',
   'latin, latin_ext',
   "/* gudea-regular - latin_latin-ext */
   @font-face {
     font-family: 'Gudea';
     font-style: normal;
     font-weight: 400;
     src: url('".$font_url."/gudea-v5-latin_latin-ext-regular.eot'); /* IE9 Compat Modes */
     src: local('Gudea'),
          url('".$font_url."/gudea-v5-latin_latin-ext-regular.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
          url('".$font_url."/gudea-v5-latin_latin-ext-regular.woff2') format('woff2'), /* Super Modern Browsers */
          url('".$font_url."/gudea-v5-latin_latin-ext-regular.woff') format('woff'), /* Modern Browsers */
          url('".$font_url."/gudea-v5-latin_latin-ext-regular.ttf') format('truetype'), /* Safari, Android, iOS */
          url('".$font_url."/gudea-v5-latin_latin-ext-regular.svg#Gudea') format('svg'); /* Legacy iOS */
   }"
];

$googlefonts[1000] = [
   'Josefin Slab',
   400,
   "'Josefin Slab', serif",
   'Josefin+Slab',
   'latin',
   "/* josefin-slab-regular - latin */
   @font-face {
     font-family: 'Josefin Slab';
     font-style: normal;
     font-weight: 400;
     src: url('".$font_url."/josefin-slab-v8-latin-regular.eot'); /* IE9 Compat Modes */
     src: local('Josefin Slab Regular'), local('JosefinSlab-Regular'),
          url('".$font_url."/josefin-slab-v8-latin-regular.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
          url('".$font_url."/josefin-slab-v8-latin-regular.woff2') format('woff2'), /* Super Modern Browsers */
          url('".$font_url."/josefin-slab-v8-latin-regular.woff') format('woff'), /* Modern Browsers */
          url('".$font_url."/josefin-slab-v8-latin-regular.ttf') format('truetype'), /* Safari, Android, iOS */
          url('".$font_url."/josefin-slab-v8-latin-regular.svg#JosefinSlab') format('svg'); /* Legacy iOS */
   }"
];

$googlefonts[1100] = [
   'Amatic SC',
   400,
   "'Amatic SC', cursive",
   'Amatic+SC',
   'latin',
   "/* amatic-sc-regular - latin_latin-ext_cyrillic */
   @font-face {
     font-family: 'Amatic SC';
     font-style: normal;
     font-weight: 400;
     src: url('".$font_url."/amatic-sc-v11-latin_latin-ext_cyrillic-regular.eot'); /* IE9 Compat Modes */
     src: local('Amatic SC Regular'), local('AmaticSC-Regular'),
          url('".$font_url."/amatic-sc-v11-latin_latin-ext_cyrillic-regular.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
          url('".$font_url."/amatic-sc-v11-latin_latin-ext_cyrillic-regular.woff2') format('woff2'), /* Super Modern Browsers */
          url('".$font_url."/amatic-sc-v11-latin_latin-ext_cyrillic-regular.woff') format('woff'), /* Modern Browsers */
          url('".$font_url."/amatic-sc-v11-latin_latin-ext_cyrillic-regular.ttf') format('truetype'), /* Safari, Android, iOS */
          url('".$font_url."/amatic-sc-v11-latin_latin-ext_cyrillic-regular.svg#AmaticSC') format('svg'); /* Legacy iOS */
   }"
];

$googlefonts[1200] = [
   'Lobster Two',
   400,
   "'Lobster Two', cursive",
   'Lobster+Two',
   'latin',
   "/* lobster-two-regular - latin */
   @font-face {
     font-family: 'Lobster Two';
     font-style: normal;
     font-weight: 400;
     src: url('".$font_url."/lobster-two-v10-latin-regular.eot'); /* IE9 Compat Modes */
     src: local('Lobster Two'), local('LobsterTwo'),
          url('".$font_url."/lobster-two-v10-latin-regular.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
          url('".$font_url."/lobster-two-v10-latin-regular.woff2') format('woff2'), /* Super Modern Browsers */
          url('".$font_url."/lobster-two-v10-latin-regular.woff') format('woff'), /* Modern Browsers */
          url('".$font_url."/lobster-two-v10-latin-regular.ttf') format('truetype'), /* Safari, Android, iOS */
          url('".$font_url."/lobster-two-v10-latin-regular.svg#LobsterTwo') format('svg'); /* Legacy iOS */
   }"
];

$googlefonts[1300] = [
   'Courgette',
   400,
   'Courgette, cursive',
   'Courgette',
   'latin',
   "/* courgette-regular - latin_latin-ext */
   @font-face {
     font-family: 'Courgette';
     font-style: normal;
     font-weight: 400;
     src: url('".$font_url."/courgette-v5-latin_latin-ext-regular.eot'); /* IE9 Compat Modes */
     src: local('Courgette Regular'), local('Courgette-Regular'),
          url('".$font_url."/courgette-v5-latin_latin-ext-regular.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
          url('".$font_url."/courgette-v5-latin_latin-ext-regular.woff2') format('woff2'), /* Super Modern Browsers */
          url('".$font_url."/courgette-v5-latin_latin-ext-regular.woff') format('woff'), /* Modern Browsers */
          url('".$font_url."/courgette-v5-latin_latin-ext-regular.ttf') format('truetype'), /* Safari, Android, iOS */
          url('".$font_url."/courgette-v5-latin_latin-ext-regular.svg#Courgette') format('svg'); /* Legacy iOS */
   }"
];

$googlefonts[1400] = [
   'Satisfy',
   400,
   'Satisfy, cursive',
   'Satisfy',
   'latin',
   "/* satisfy-regular - latin */
   @font-face {
     font-family: 'Satisfy';
     font-style: normal;
     font-weight: 400;
     src: url('".$font_url."/satisfy-v8-latin-regular.eot'); /* IE9 Compat Modes */
     src: local('Satisfy Regular'), local('Satisfy-Regular'),
          url('".$font_url."/satisfy-v8-latin-regular.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
          url('".$font_url."/satisfy-v8-latin-regular.woff2') format('woff2'), /* Super Modern Browsers */
          url('".$font_url."/satisfy-v8-latin-regular.woff') format('woff'), /* Modern Browsers */
          url('".$font_url."/satisfy-v8-latin-regular.ttf') format('truetype'), /* Safari, Android, iOS */
          url('".$font_url."/satisfy-v8-latin-regular.svg#Satisfy') format('svg'); /* Legacy iOS */
   }"
];

$googlefonts[1500] = [
   'Shadows Into Light Two',
   400,
   "'Shadows Into Light Two', cursive",
   'Shadows+Into+Light+Two',
   'latin',
   "/* shadows-into-light-two-regular - latin_latin-ext */
   @font-face {
     font-family: 'Shadows Into Light Two';
     font-style: normal;
     font-weight: 400;
     src: url('".$font_url."/shadows-into-light-two-v5-latin_latin-ext-regular.eot'); /* IE9 Compat Modes */
     src: local('Shadows Into Light Two'), local('ShadowsIntoLightTwo-Regular'),
          url('".$font_url."/shadows-into-light-two-v5-latin_latin-ext-regular.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
          url('".$font_url."/shadows-into-light-two-v5-latin_latin-ext-regular.woff2') format('woff2'), /* Super Modern Browsers */
          url('".$font_url."/shadows-into-light-two-v5-latin_latin-ext-regular.woff') format('woff'), /* Modern Browsers */
          url('".$font_url."/shadows-into-light-two-v5-latin_latin-ext-regular.ttf') format('truetype'), /* Safari, Android, iOS */
          url('".$font_url."/shadows-into-light-two-v5-latin_latin-ext-regular.svg#ShadowsIntoLightTwo') format('svg'); /* Legacy iOS */
   }"
];

$googlefonts[1600] = [
   'Handlee',
   400,
   'Handlee, cursive',
   'Handlee',
   'latin',
   "/* handlee-regular - latin */
   @font-face {
     font-family: 'Handlee';
     font-style: normal;
     font-weight: 400;
     src: url('".$font_url."/handlee-v6-latin-regular.eot'); /* IE9 Compat Modes */
     src: local('Handlee Regular'), local('Handlee-Regular'),
          url('".$font_url."/handlee-v6-latin-regular.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
          url('".$font_url."/handlee-v6-latin-regular.woff2') format('woff2'), /* Super Modern Browsers */
          url('".$font_url."/handlee-v6-latin-regular.woff') format('woff'), /* Modern Browsers */
          url('".$font_url."/handlee-v6-latin-regular.ttf') format('truetype'), /* Safari, Android, iOS */
          url('".$font_url."/handlee-v6-latin-regular.svg#Handlee') format('svg'); /* Legacy iOS */
   }"
];

$googlefonts[1700] = [
   'Tangerine',
   400,
   'Tangerine, cursive',
   'Tangerine',
   'latin',
   "/* tangerine-regular - latin */
   @font-face {
     font-family: 'Tangerine';
     font-style: normal;
     font-weight: 400;
     src: url('".$font_url."/tangerine-v9-latin-regular.eot'); /* IE9 Compat Modes */
     src: local('Tangerine Regular'), local('Tangerine-Regular'),
          url('".$font_url."/tangerine-v9-latin-regular.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
          url('".$font_url."/tangerine-v9-latin-regular.woff2') format('woff2'), /* Super Modern Browsers */
          url('".$font_url."/tangerine-v9-latin-regular.woff') format('woff'), /* Modern Browsers */
          url('".$font_url."/tangerine-v9-latin-regular.ttf') format('truetype'), /* Safari, Android, iOS */
          url('".$font_url."/tangerine-v9-latin-regular.svg#Tangerine') format('svg'); /* Legacy iOS */
   }"
];

$googlefonts[1800] = [
   'Bad Script',
   400,
   "'Bad Script', cursive",
   'Bad+Script',
   'latin, cyrillic',
   "/* bad-script-regular - latin_cyrillic */
   @font-face {
     font-family: 'Bad Script';
     font-style: normal;
     font-weight: 400;
     src: url('".$font_url."/bad-script-v6-latin_cyrillic-regular.eot'); /* IE9 Compat Modes */
     src: local('Bad Script Regular'), local('BadScript-Regular'),
          url('".$font_url."/bad-script-v6-latin_cyrillic-regular.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
          url('".$font_url."/bad-script-v6-latin_cyrillic-regular.woff2') format('woff2'), /* Super Modern Browsers */
          url('".$font_url."/bad-script-v6-latin_cyrillic-regular.woff') format('woff'), /* Modern Browsers */
          url('".$font_url."/bad-script-v6-latin_cyrillic-regular.ttf') format('truetype'), /* Safari, Android, iOS */
          url('".$font_url."/bad-script-v6-latin_cyrillic-regular.svg#BadScript') format('svg'); /* Legacy iOS */
   }"
];

$googlefonts[1900] = [
   'Raleway',
   300,
   'Raleway, sans-serif',
   'Raleway:300',
   'latin',
   "/* raleway-300 - latin_latin-ext */
   @font-face {
     font-family: 'Raleway';
     font-style: normal;
     font-weight: 300;
     src: url('".$font_url."/raleway-v12-latin_latin-ext-300.eot'); /* IE9 Compat Modes */
     src: local('Raleway Light'), local('Raleway-Light'),
          url('".$font_url."/raleway-v12-latin_latin-ext-300.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
          url('".$font_url."/raleway-v12-latin_latin-ext-300.woff2') format('woff2'), /* Super Modern Browsers */
          url('".$font_url."/raleway-v12-latin_latin-ext-300.woff') format('woff'), /* Modern Browsers */
          url('".$font_url."/raleway-v12-latin_latin-ext-300.ttf') format('truetype'), /* Safari, Android, iOS */
          url('".$font_url."/raleway-v12-latin_latin-ext-300.svg#Raleway') format('svg'); /* Legacy iOS */
   }"
];

$googlefonts[2000] = [
   'Lato',
    300,
   "'Lato', sans-serif",
   'Lato:300',
   'latin, latin_ext, cyrillic',
   "/* lato-regular - latin_latin-ext */
   @font-face {
     font-family: 'Lato';
     font-style: normal;
     font-weight: 400;
     src: url('".$font_url."/lato-v14-latin_latin-ext-regular.eot'); /* IE9 Compat Modes */
     src: local('Lato Regular'), local('Lato-Regular'),
          url('".$font_url."/lato-v14-latin_latin-ext-regular.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
          url('".$font_url."/lato-v14-latin_latin-ext-regular.woff2') format('woff2'), /* Super Modern Browsers */
          url('".$font_url."/lato-v14-latin_latin-ext-regular.woff') format('woff'), /* Modern Browsers */
          url('".$font_url."/lato-v14-latin_latin-ext-regular.ttf') format('truetype'), /* Safari, Android, iOS */
          url('".$font_url."/lato-v14-latin_latin-ext-regular.svg#Lato') format('svg'); /* Legacy iOS */
   }"
];

$googlefonts[2100] = [
   'Open Sans',
    400,
   "'Open Sans', sans-serif",
   '',
   'latin, latin_ext, cyrillic',
   "@font-face {
      font-family: 'Open Sans';
      font-style: normal;
      font-weight: 400;
      src: local('Open Sans Regular'), local('OpenSans-Regular'),
         url('".$font_url."/open-sans-v17-greek_cyrillic-ext_latin_greek-ext_cyrillic_latin-ext-regular.woff2') format('woff2'), /* Chrome 26+, Opera 23+, Firefox 39+ */
         url('".$font_url."/open-sans-v17-greek_cyrillic-ext_latin_greek-ext_cyrillic_latin-ext-regular.woff') format('woff'); /* Chrome 6+, Firefox 3.6+, IE 9+, Safari 5.1+ */
   }"
];

$googlefonts[2200] = [
   'Dosis',
    300,
   "Dosis, sans-serif",
   'Dosis:400',
   'latin, latin_ext, cyrillic',
   "/* dosis-regular - latin-ext_latin */
   @font-face {
     font-family: 'Dosis';
     font-style: normal;
     font-weight: 400;
     src: url('".$font_url."/dosis-v7-latin-ext_latin-regular.eot'); /* IE9 Compat Modes */
     src: local('Dosis Regular'), local('Dosis-Regular'),
          url('".$font_url."/dosis-v7-latin-ext_latin-regular.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
          url('".$font_url."/dosis-v7-latin-ext_latin-regular.woff2') format('woff2'), /* Super Modern Browsers */
          url('".$font_url."/dosis-v7-latin-ext_latin-regular.woff') format('woff'), /* Modern Browsers */
          url('".$font_url."/dosis-v7-latin-ext_latin-regular.ttf') format('truetype'), /* Safari, Android, iOS */
          url('".$font_url."/dosis-v7-latin-ext_latin-regular.svg#Dosis') format('svg'); /* Legacy iOS */
   }"
];

$googlefonts[2300] = [
   'Roboto',
    300,
   "Roboto, sans-serif",
   'Roboto:300',
   'latin, latin_ext, cyrillic',
   "/* roboto-regular - cyrillic-ext_latin-ext_greek_latin_greek-ext_cyrillic */
   @font-face {
     font-family: 'Roboto';
     font-style: normal;
     font-weight: 400;
     src: url('".$font_url."/roboto-v18-cyrillic-ext_latin-ext_greek_latin_greek-ext_cyrillic-regular.eot'); /* IE9 Compat Modes */
     src: local('Roboto'), local('Roboto-Regular'),
          url('".$font_url."/roboto-v18-cyrillic-ext_latin-ext_greek_latin_greek-ext_cyrillic-regular.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
          url('".$font_url."/roboto-v18-cyrillic-ext_latin-ext_greek_latin_greek-ext_cyrillic-regular.woff2') format('woff2'), /* Super Modern Browsers */
          url('".$font_url."/roboto-v18-cyrillic-ext_latin-ext_greek_latin_greek-ext_cyrillic-regular.woff') format('woff'), /* Modern Browsers */
          url('".$font_url."/roboto-v18-cyrillic-ext_latin-ext_greek_latin_greek-ext_cyrillic-regular.ttf') format('truetype'), /* Safari, Android, iOS */
          url('".$font_url."/roboto-v18-cyrillic-ext_latin-ext_greek_latin_greek-ext_cyrillic-regular.svg#Roboto') format('svg'); /* Legacy iOS */
   }"
];

$googlefonts[2400] = [
   'Arapey',
    300,
   "Arapey, serif",
   'Arapey:400',
   'latin',
   "/* arapey-regular - latin */
   @font-face {
     font-family: 'Arapey';
     font-style: normal;
     font-weight: 400;
     src: url('".$font_url."/arapey-v6-latin-regular.eot'); /* IE9 Compat Modes */
     src: local('Arapey Regular'), local('Arapey-Regular'),
          url('".$font_url."/arapey-v6-latin-regular.eot?#iefix') format('embedded-opentype'), /* IE6-IE8 */
          url('".$font_url."/arapey-v6-latin-regular.woff2') format('woff2'), /* Super Modern Browsers */
          url('".$font_url."/arapey-v6-latin-regular.woff') format('woff'), /* Modern Browsers */
          url('".$font_url."/arapey-v6-latin-regular.ttf') format('truetype'), /* Safari, Android, iOS */
          url('".$font_url."/arapey-v6-latin-regular.svg#Arapey') format('svg'); /* Legacy iOS */
   }
"
];
