<?php
/*
###################################################################################
  Kanpai Classic Shopsoftware Entwicklungsstand: 14.01.2021 Version 11

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

namespace KANPAICLASSIC;

if (!defined('KANPAICLASSIC')) {
   die ("This file cannot run outside the Kanpai Classic Shopsoftware");
}

class KANPAICLASSIC_menu
{
   private $db;
   private $params;
   private $text;

   public function __construct() {
      $this->db = Control::getDB();
      $this->params = Control::getParams();
      $this->text = Control::getText();
   }

   public function getContent() {
     if ($this->params->func == 'adminSize') {
        $size = $this->params->postInt('size');
        $this->db->query("UPDATE #__firma SET admin_size = $size");

        echo json_encode(['status' => 'ok']);
        exit;
     }

     else {
        echo json_encode(['status' => 'error', 'msg' => $this->params->func]);
     }

   }

   // Daten für Menü aus DB einlesen und Menü generieren
   // 06.12.2018
   public function menuData() {
      // Default-Seite
      $aktiv = $this->params->task;

      if ($aktiv == '' || $aktiv == 'login' or $aktiv == 'home') {
         $aktiv = 'bestellungen';
      }

      if ($aktiv == 'einstellungen') {
         $aktiv = 'shopinhaber';
      }

      $menu_arr = [];
      $menu_arr[] = ['id' =>  10, 'script' => 'bestellungen',          'name' => 'Bestellungen',        'group' => 1, 'bg' => 'menu_bg1', 'childs' => 'n', 'parent' => 0,  'haendler' => 'y', 'mobile' => 'fas fa-list'];
      $menu_arr[] = ['id' =>  15, 'script' => 'portal',                'name' => 'Profil',              'group' => 1, 'bg' => 'menu_bg1', 'childs' => 'n', 'parent' => 0,  'haendler' => 'y', 'mobile' => ''];
      $menu_arr[] = ['id' =>  20, 'script' => 'kunden',                'name' => 'Kunden',              'group' => 1, 'bg' => 'menu_bg1', 'childs' => 'n', 'parent' => 0,  'haendler' => 'y', 'mobile' => 'fas fa-user'];
      $menu_arr[] = ['id' =>  30, 'script' => 'artikel',               'name' => 'Artikel',             'group' => 1, 'bg' => 'menu_bg1', 'childs' => 'n', 'parent' => 0,  'haendler' => 'y', 'mobile' => 'fas fa-dice-d6'];

      // Portal / Freigabe durch config.inc.php
      if (defined('CONF_MODULE_PORTAL') && defined('CONF_MODULE_PORTAL_IMPORT')) {
         $menu_arr[] = ['id' =>  35, 'script' => 'portalImport',           'name' => 'Import',              'group' => 1, 'bg' => 'menu_bg1', 'childs' => 'n', 'parent' => 0,  'haendler' => 'y', 'mobile' => ''];
      }

      $menu_arr[] = ['id' =>  40, 'script' => 'kategorien',             'name' => 'Kategorien',          'group' => 1, 'bg' => 'menu_bg1', 'childs' => 'n', 'parent' => 0,  'haendler' => 'n', 'mobile' => 'far fa-folder-open'];
      $menu_arr[] = ['id' =>  50, 'script' => 'seiten',                 'name' => 'Seiten',              'group' => 1, 'bg' => 'menu_bg1', 'childs' => 'n', 'parent' => 0,  'haendler' => 'n', 'mobile' => 'fas fa-file-alt'];
      $menu_arr[] = ['id' =>  60, 'script' => 'design',                 'name' => 'Design',              'group' => 1, 'bg' => 'menu_bg1', 'childs' => 'y', 'parent' => 0,  'haendler' => 'n', 'mobile' => 'fas fa-palette'];

      if (\defined('CONF_MODULE_LIVEDESIGNER')) {
         $menu_arr[] = ['id' => 61, 'script' => 'designLivedesigner',      'name' => 'Livedesigner',        'group' => 1, 'bg' => 'menu_bg1', 'childs' => 'n', 'parent' => 60, 'haendler' => 'n', 'mobile' => ''];
      }

      $menu_arr[] = ['id' =>  62, 'script' => 'designTemplate',         'name' => 'Templatedesign',      'group' => 1, 'bg' => 'menu_bg1', 'childs' => 'n', 'parent' => 60, 'haendler' => 'n', 'mobile' => ''];

      if (defined('CONF_MODULE_EXTENDED') && (!is_file(SHOP_PATH.'/classes/modules/livedesigner/livedesigner.module.php') || !is_file(SHOP_PATH.'/classes/modules/livedesigner_ext/livedesigner_ext.module.php'))) {
         $menu_arr[] = ['id' =>  63, 'script' => 'designExtended',         'name' => 'Karussell Accord...',            'group' => 1, 'bg' => 'menu_bg1', 'childs' => 'n', 'parent' => 60, 'haendler' => 'n', 'mobile' => ''];
      }

      if (defined('\CONF_MODULE_POPUP')) {
         $menu_arr[] = ['id' =>  64, 'script' => 'designExtended',         'name' => 'Popup',                          'group' => 1, 'bg' => 'menu_bg1', 'childs' => 'n', 'parent' => 60, 'haendler' => 'n', 'mobile' => ''];
      }

      $menu_arr[] = ['id' =>  65, 'script' => 'designColors',           'name' => 'Farben & Schrift',    'group' => 1, 'bg' => 'menu_bg1', 'childs' => 'n', 'parent' => 60, 'haendler' => 'n', 'mobile' => ''];
      $menu_arr[] = ['id' =>  66, 'script' => 'designGeschaeftspapier', 'name' => 'Geschäftspapier',     'group' => 1, 'bg' => 'menu_bg1', 'childs' => 'n', 'parent' => 60, 'haendler' => 'n', 'mobile' => ''];

      if (\defined('CONF_MODULE_ADMINDESIGN')) {
         $menu_arr[] = ['id' => 69, 'script' => 'designAdmin',             'name' => 'Admindesign',         'group' => 1, 'bg' => 'menu_bg1', 'childs' => 'n', 'parent' => 60, 'haendler' => 'n', 'mobile' => ''];
      }

      $menu_arr[] = ['id' =>  70, 'script' => 'tools',                  'name' => 'Tools',               'group' => 1, 'bg' => 'menu_bg1', 'childs' => 'y', 'parent' => 0,  'haendler' => 'n', 'mobile' => 'fas fa-wrench'];
      $menu_arr[] = ['id' =>  71, 'script' => 'toolsFunktionen',        'name' => 'Funktionen',          'group' => 1, 'bg' => 'menu_bg1', 'childs' => 'n', 'parent' => 70, 'haendler' => 'n', 'mobile' => ''];
      $menu_arr[] = ['id' =>  72, 'script' => 'toolsGutscheine',        'name' => 'Gutscheine & NL',     'group' => 1, 'bg' => 'menu_bg1', 'childs' => 'n', 'parent' => 70, 'haendler' => 'n', 'mobile' => ''];
      $menu_arr[] = ['id' =>  73, 'script' => 'toolsSchnittstellen',    'name' => 'Schnittstellen',      'group' => 1, 'bg' => 'menu_bg1', 'childs' => 'n', 'parent' => 70, 'haendler' => 'n', 'mobile' => ''];

      if (defined('CONF_MODULE_FOTO')) {
         $menu_arr[] = ['id' =>  74, 'script' => 'toolsFoto',              'name' => 'Fotolizenz-Artikel', 'group' => 1, 'bg' => 'menu_bg1', 'childs' => 'n', 'parent' => 70, 'haendler' => 'n', 'mobile' => ''];
      }

      if (defined('CONF_MODULE_RABATTE')) {
         $menu_arr[] = ['id' =>  75, 'script' => 'toolsRabattgruppen',     'name' => 'Rabattgruppen',       'group' => 1, 'bg' => 'menu_bg1', 'childs' => 'n', 'parent' => 70, 'haendler' => 'n', 'mobile' => ''];
      }

      if (defined('CONF_MODULE_BACKUP')) {
         $menu_arr[] = ['id' =>  76, 'script' => 'toolsBackup',            'name' => 'Sicherung',           'group' => 1, 'bg' => 'menu_bg1', 'childs' => 'n', 'parent' => 70, 'haendler' => 'n', 'mobile' => ''];
      }

      // Pro-Version, wenn pro.class.php vorhanden ist
      if (defined('CONF_MODULE_EXTENDED')) {
//         $menu_arr[] = ['id' =>  80, 'script' => 'extend',                 'name' => 'Extend',              'group' => 1, 'bg' => 'menu_bg1', 'childs' => 'n', 'parent' => 0,  'haendler' => 'n', 'mobile' => 'fas fa-file-image'];
      }

      if (!defined('CONF_MODULE_PORTAL')) {
         $menu_arr[] = ['id' => 100, 'script' => 'einstellungen',          'name' => 'Einstellungen',       'group' => 1, 'bg' => 'menu_bg2', 'childs' => 'n', 'parent' => 0,  'haendler' => 'n', 'mobile' => 'fas fa-cog'];
      }
      else {
         $menu_arr[] = ['id' => 100, 'script' => 'einstellungen',          'name' => '',                    'group' => 1, 'bg' => 'menu_bg2 fas fa-cog', 'childs' => 'n', 'parent' => 0,  'haendler' => 'n', 'mobile' => 'fas fa-cog'];
      }

      $menu_arr[] = ['id' => 200, 'script' => 'home',                    'name' => 'Home',                'group' => 2, 'bg' => 'menu_bg1', 'childs' => 'n', 'parent' => 0,  'haendler' => 'n', 'mobile' => 'fas fa-home'];
      $menu_arr[] = ['id' => 210, 'script' => 'shopinhaber',             'name' => 'Shopinhaber',         'group' => 2, 'bg' => 'menu_bg2', 'childs' => 'n', 'parent' => 0,  'haendler' => 'n', 'mobile' => 'fas fa-user-tie'];
      $menu_arr[] = ['id' => 220, 'script' => 'versandart',              'name' => 'Versandart',          'group' => 2, 'bg' => 'menu_bg2', 'childs' => 'n', 'parent' => 0,  'haendler' => 'n', 'mobile' => 'posthorn'];
      $menu_arr[] = ['id' => 230, 'script' => 'zahlungsart',             'name' => 'Zahlungsart',         'group' => 2, 'bg' => 'menu_bg2', 'childs' => 'n', 'parent' => 0,  'haendler' => 'n', 'mobile' => 'fas fa-euro-sign'];
      $menu_arr[] = ['id' => 240, 'script' => 'lagerhaltung',            'name' => 'Lagerhaltung',        'group' => 2, 'bg' => 'menu_bg2', 'childs' => 'n', 'parent' => 0,  'haendler' => 'n', 'mobile' => 'fas fa-cube'];
      $menu_arr[] = ['id' => 250, 'script' => 'steuer',                  'name' => 'Steuer & Gewerbe',    'group' => 2, 'bg' => 'menu_bg2', 'childs' => 'n', 'parent' => 0,  'haendler' => 'n', 'mobile' => 'paragraph'];
      $menu_arr[] = ['id' => 260, 'script' => 'laender',                 'name' => 'Länder',              'group' => 2, 'bg' => 'menu_bg2', 'childs' => 'n', 'parent' => 0,  'haendler' => 'n', 'mobile' => 'fas fa-globe'];
      $menu_arr[] = ['id' => 270, 'script' => 'texte',                   'name' => 'Texte',               'group' => 2, 'bg' => 'menu_bg2', 'childs' => 'n', 'parent' => 0,  'haendler' => 'n', 'mobile' => 'fas fa-envelope'];
      $menu_arr[] = ['id' => 280, 'script' => 'reservations',            'name' => 'Reservierungen',      'group' => 2, 'bg' => 'menu_bg2', 'childs' => 'n', 'parent' => 0,  'haendler' => 'n', 'mobile' => 'fas fa-calendar-alt'];

      // Menügruppe suchen
      $group = 0;

      // Auswahl anhand $group / 1 -> Home; 2 -> Einstellungen
      for ($i = 0; $i < count($menu_arr); $i++) {
         if ($menu_arr[$i]['script'] == $aktiv) {
            $group = $menu_arr[$i]['group'];
            break;
         }
      }

      $sub = 0;

      $html  = '<div id="menu_inner">';
      $html .= '   <div class="menu_left '.($group == 1 ? 'home' : 'config').'">';
      $html .= "      <ul>";

      for ($i = 0; $i < count($menu_arr); $i++) {
         if ($menu_arr[$i]['group'] != $group) {
            continue;
         }

         // Bei Händlern überspringen
         if (defined('CONF_MODULE_PORTAL') && $_SESSION['haendler'] == 'y' && $menu_arr[$i]['portal'] != 'y') {
            continue;
         }

         if (!defined('CONF_MODULE_PORTAL') && $menu_arr[$i]['script'] == 'portal') {
            continue;
         }

         $name     = str_replace('&', '&amp;', $menu_arr[$i]['name']);
         $name_sub = str_replace('&', '&amp;', $menu_arr[$i]['name']);
         $link     = ADMIN_URL_IDX.'/'.$menu_arr[$i]['script'];
         $class    = $menu_arr[$i]['bg'];
         $id       = $menu_arr[$i]['id'];
         $mobile   = $menu_arr[$i]['mobile'];
         $active   = $this->_checkActive($menu_arr, $i, $aktiv, $id);

         if ($active) {
            $class .= " menu_active";
         }

         // Ende Untermenü (war vorheriger Eintrag)
         if ($sub > 0 && $menu_arr[$i]['parent'] != $sub) {
            $html .= '      </ul>'.CR;
            $html .= '         </li>'.CR;
            $sub = 0;
         }
         // Kein Untermenü
         if ($menu_arr[$i]['childs'] == 'n') {
            if ($mobile == '') {
               // Ohne Untermenü
               if ($menu_arr[$i]['parent'] == 0) {
                  $html .= '         <li>'.CR;
                  $html .= '           <div class="mainitem '.$class.' pointer" onclick="location.href=\''.$link.'\'">'.($sub > 0 ? $name_sub : $name).'</div>'.CR;
                  $html .= '           <span class="colorbar '.$class.'"></span>'.CR;
                  $html .= '         </li>'.CR;
               }

               else {
                  $html .= '         <li>'.CR;
                  $html .= '            <div class="subitem '.$class.' pointer" onclick="location.href=\''.$link.'\'">'.($sub > 0 ? $name_sub : $name).'</div>'.CR;
                  $html .= '         </li>'.CR;
               }
            }

            // Mit Untermenü
            else {
               if ($menu_arr[$i]['parent'] == 0) {
                  $html .= '         <li'.($mobile == 'display_none' ? ' class="3 desktop"' : '').'>'.CR;
                  $html .= '            <div class="mainitem '.$menu_arr[$i]['script'].' '.$class.' pointer" onclick="location.href=\''.$link.'\'">'.CR;
                  $html .= '               <span class="desktop '.$menu_arr[$i]['script'].'">'.($sub > 0 ? $name_sub : $name).'</span><span id="mobile_'.$name.'" class="mobile '.$mobile.'" title="'.($sub > 0 ? $name_sub : $name).'"></span>'.CR;
                  $html .= '            </div>'.CR;
                  // $html .= '            <span class="colorbar '.$class.'"></span>'.CR;
                  $html .= '         </li>'.CR;
               }

               else {
                  $html .= '         <li'.($mobile == 'display_none' ? ' class="desktop"' : '').'>'.CR;
                  $html .= '            <div class="mainitem '.$class.' pointer" onclick="location.href=\''.$link.'\'">'.CR;
                  $html .= '               <span class="desktop">'.($sub > 0 ? $name_sub : $name).'</span><span id="mobile_'.$name.'" class="mobile '.$mobile.'" title="'.($sub > 0 ? $name_sub : $name).'"></span>'.CR;
                  $html .= '            </div>'.CR;
                  $html .= '         </li>'.CR;
               }
            }
         }

         // Kategorie hat Untermenü - bei Klick öffnen
         else {
            if ($mobile == '') {
               $html .= '         <li onclick="if (navigator.userAgent.match(/(iPod|iPhone|iPad)/)) { ($(\'ul\', $(this)).height() == 0 ? $(\'ul\', $(this)).css(\'height\', \'auto\') : $(\'ul\', $(this)).css(\'height\', 0)) ; }">'.CR;
               $html .= '            <div class="mainitem '.$class.'">'.$name.'</div>'.CR;
            }

            else {
               $html .= '         <li'.($mobile == 'display_none' ? ' class="desktop"' : '').' onclick="if (navigator.userAgent.match(/(iPod|iPhone|iPad)/)) { ($(\'ul\', $(this)).height() == 0 ? $(\'ul\', $(this)).css(\'height\', \'auto\') : $(\'ul\', $(this)).css(\'height\', 0)) ; }">'.CR;
               $html .= '            <div class="mainitem '.$class.'">'.CR;
               $html .= '               <span class="6 desktop">'.$name.'</span>'.CR;
               $html .= '               <span class="mobile '.$mobile.'" title="'.($sub > 0 ? $name_sub : $name).'"></span>'.CR;
               // $html .= '               <span class="colorbar '.$class.'"></span>'.CR;
               $html .= '            </div>'.CR;
            }

            $html .= '            <ul class="sub_menu">'.CR;
            $sub = $id;
         }
      }

      $html .= '      </ul>';
      $html .= '   </div>';
      $html .= '   <div class="clear"></div>';

//      $html .= '   <div id="logout" onclick="location.href=\''.ADMIN_URL_IDX.'/logout\';" title="Logout"><span class="desktop pointer">Logout</span><span class="logoff pointer fas fa-power-off"></span></div>';
      $html .= '   <div id="logout" onclick="location.href=\''.ADMIN_URL_IDX.'/logout\';" title="Logout"><span class="logoff pointer fas fa-power-off"></span></div>';
      $html .= '</div>';

      return $html;
   }

   // Zeile Länderauswahl
   // 01.01.2019
   public function langData() {
      $langdata = '';

      if (is_array($this->params->langs) && count($this->params->langs) > 1) {
         foreach ($this->params->langs as $lang) {
            $langdata .= '<a class="lang_item'.($this->params->selected_lang == $lang ? ' selected' : '').' lang_'.strtolower($lang).'" href="'.ADMIN_URL_IDX.'/lang/'. $lang.'">'.strtoupper($lang).'</a>'.CR;
         }

         $langdata .= '<span class="lang_abstand"></span>';
      }

      return $langdata;
   }

   // Aktiven Menüpunk suchen, true / false
   // 06.12.2018
   private function _checkActive($menu_arr, $i, $aktiv, $id) {
      if ($menu_arr[$i]['script'] == $aktiv) {
         return true;
      }

      else if ($menu_arr[$i]['script'] != $aktiv) {
         for ($j = $i+1; $j < count($menu_arr); $j++) {
            if ($menu_arr[$j]['parent'] != $id) {
               return false;
            }

            if ($menu_arr[$j]['script'] == $aktiv) {
               return true;
            }
         }
      }
      return false;
   }

   public function printheader() {
   ?>
   <div id="header">
      <div id="header_inner">
         <img class="logo_left" src="<?php echo ADMIN_URL; ?>/img/admin_banner.jpg" alt="" />
         <?php if (is_file(ADMIN_PATH.'/img/admin_banner_center.jpg')) { ?>
         <div class="logo_center">
            <img class="logo_center" src="<?php echo ADMIN_URL; ?>/img/admin_banner_center.jpg" alt="" />
         </div>
         <?php } ?>
      </div>
   </div>
   <?php
   }

   public function footer() {
?>
      <div id="admin_footer">
         <div id="admin_footer1" class="admin_footer">
            <?php if (!defined('ADMIN_MODE') || ADMIN_MODE == 'update') { ?>
            <a class="pointer" href="<?php echo CONF_ADMIN_BTN2_LINK; ?>" target="_blank">
               <span class="">Updates</span>
               <span class="fas fa-exclamation-triangle ci_color"></span>
            </a>
            <?php } else if (ADMIN_MODE == 'demo') { ?>
            <a class="pointer" href="<?php echo CONF_ADMIN_BTN2_LINK; ?>" target="_blank">
               <span class="">jetzt kaufen</span>
               <span class="far fa-smile"></span>
            </a>
            <?php } else if (ADMIN_MODE == 'miete') { ?>
            <a class="pointer" href="<?php echo CONF_ADMIN_BTN2_LINK; ?>" target="_blank">
               <span class="">Hilfeseite</span>
            </a>
            <?php } else if (ADMIN_MODE == 'partner') { ?>
            <a class="pointer" href="<?php echo CONF_ADMIN_BTN2_LINK; ?>" target="_blank">
               <span class="">Updates</span>
               <span class="fas fa-exclamation-triangle ci_color"></span>
            </a>
            <?php } ?>
         </div>

         <div id="admin_footer2" class="admin_footer">
            <a class="pointer" href="https://www.kanpaiclassic.com" target="_blank">
               <span class="">Kanpai Classic Shopsoftware</span>
            </a>
               <br />
            <a class="pointer" href="<?php echo CONF_ADMIN_BTN2_LINK; ?>" target="_blank">
               <span class=""><?php echo ADMIN_VERSION; ?></span>
            </a>
         </div>

         <div id="admin_footer3" class="admin_footer">
            <a class="pointer" href="<?php echo CONF_ADMIN_BTN1_LINK; ?>" target="_blank">
               <span class="">Neue Module</span>
               <span class="far fa-smile"></span>
            </a>
         </div>
         <div class="clear"></div>
      </div>

      <div id="admin_footer4" class="content">
         <?php
         $footer_img  = '';
         $footer_link = '';

         if (!defined('ADMIN_MODE') || ADMIN_MODE == 'update') {
            $footer_img  = ADMIN_FOOTER_UPDATE;
            $footer_link = ADMIN_LINK_UPDATE;
         }

         else if (ADMIN_MODE == 'miete') {
            $footer_img  = ADMIN_FOOTER_MIETE;
            $footer_link = ADMIN_LINK_MIETE;
         }

         else if (ADMIN_MODE == 'partner') {
            $footer_img  = ADMIN_FOOTER_PARTNER;
            $footer_link = ADMIN_LINK_PARTNER;
         }

         else {
            $footer_img  = ADMIN_FOOTER_DEMO;
            $footer_link = ADMIN_LINK_DEMO;
         }
         ?>
         <div id="iframe">
         <!-- <iframe style="width:100%;" onload="startIframe(this)" src="<?php echo $footer_link; ?>.html"></iframe> -->
         </div>
         <script>
            var footer_link = '<?php echo $footer_link; ?>.html';
         </script>
      </div>
<?php
   }

   // Einstellungen für Admin-Design laden
   // 06.12.2018
   public function loadDesign() {
      $admin_config = new \stdClass();
      $admin_config->admdsgn_width                 = 1200;
      $admin_config->admdsgn_ci_col                = '#ffffff';
      $admin_config->admdsgn_ci_bg                 = '#008dcb';
      $admin_config->admdsgn_font_normal           = 100;

      // Einstellungen aus DB lesen, bei Defaultwerten auskommentiert und in Install-DB
      $data = $this->db->queryAllObjects("SELECT type, data FROM #__data WHERE type LIKE 'admdsgn_%'");

      if (is_array($data)) {
         foreach ($data as $d) {
            $admin_config->{$d->type} = $d->data;
         }
      }

      $admin_config->admdsgn_font_input             = $admin_config->admdsgn_font_normal;
      $admin_config->admdsgn_font_input_size         = 14; // OK
      $admin_config->admdsgn_font_menu              = $admin_config->admdsgn_font_normal;
      $admin_config->admdsgn_font_menu_size         = 20;
      $admin_config->admdsgn_font_button            = $admin_config->admdsgn_font_normal;
      $admin_config->admdsgn_font_button_size       = 15;

      // Hintergrund Header / Logo
      $admin_config->admdsgn_logo_bg                = '#eeeeee'; // OK
      $admin_config->admdsgn_logo_bgopc             = -1; // OK
      $admin_config->admdsgn_font_normal_col        = '#555555'; // OK
      $admin_config->admdsgn_font_tit_col           = '#555555'; // OK
      $admin_config->admdsgn_font_bez_col           = '#555555'; // OK
      $admin_config->admdsgn_font_inp_col           = '#555555'; // OK
      $admin_config->admdsgn_input_border           = '#cecece'; // OK

      // Menü Hintergrund, Schrift und Buttons
      // CI-Farbe
      //$admin_config->admdsgn_menu_bg                = '#f6f6f6'; // unter Master
      $admin_config->admdsgn_menu_bgopc             = '-1';
      $admin_config->admdsgn_menu_font              = '100';

      $admin_config->admdsgn_menu_btn1_col          = '#333333';
      $admin_config->admdsgn_menu_btn1_colopc       = '-1';
      $admin_config->admdsgn_menu_btn1_bg           = '#f6f6f6';
      $admin_config->admdsgn_menu_btn1_bgopc        = '-1';
      $admin_config->admdsgn_menu_btn1_ovr_col      = $admin_config->admdsgn_ci_col;
      $admin_config->admdsgn_menu_btn1_ovr_colopc   = '-1';
      $admin_config->admdsgn_menu_btn1_ovr_bg       = $admin_config->admdsgn_ci_bg;
      $admin_config->admdsgn_menu_btn1_ovr_bgopc    = '-1';

      $admin_config->admdsgn_menu_btn2_col          = '#ffffff';
      $admin_config->admdsgn_menu_btn2_colopc       = '-1';
      $admin_config->admdsgn_menu_btn2_bg           = '#333333';
      $admin_config->admdsgn_menu_btn2_bgopc        = '-1';
      $admin_config->admdsgn_menu_btn2_ovr_col      = $admin_config->admdsgn_ci_col;
      $admin_config->admdsgn_menu_btn2_ovr_colopc   = '-1';
      $admin_config->admdsgn_menu_btn2_ovr_bg       = $admin_config->admdsgn_ci_bg;
      $admin_config->admdsgn_menu_btn2_ovr_bgopc    = '-1';

      $admin_config->admdsgn_menu_btnact_col        = $admin_config->admdsgn_ci_col;
      $admin_config->admdsgn_menu_btnact_colopc     = '-1';
      $admin_config->admdsgn_menu_btnact_bg         = $admin_config->admdsgn_ci_bg;
      $admin_config->admdsgn_menu_btnact_bgopc      = '-1';
      $admin_config->admdsgn_menu_btnact_ovr_col    = $admin_config->admdsgn_ci_col;
      $admin_config->admdsgn_menu_btnact_ovr_colopc = '-1';
      $admin_config->admdsgn_menu_btnact_ovr_bg     = $admin_config->admdsgn_ci_bg;
      $admin_config->admdsgn_menu_btnact_ovr_bgopc  = '-1';

      $admin_config->admdsgn_button_ci_col         = $admin_config->admdsgn_ci_col;
      $admin_config->admdsgn_button_ci_colopc      = '-1';
      $admin_config->admdsgn_button_ci_bg          = $admin_config->admdsgn_ci_bg;
      $admin_config->admdsgn_button_ci_bgopc       = '-1';
      $admin_config->admdsgn_button_ci_ovr_col     = $admin_config->admdsgn_ci_col;
      $admin_config->admdsgn_button_ci_ovr_colopc  = '-1';
      $admin_config->admdsgn_button_ci_ovr_bg      = $admin_config->admdsgn_ci_bg;
      $admin_config->admdsgn_button_ci_ovr_bgopc   = '-1';

      $admin_config->admdsgn_button_col         = '#333333';
      $admin_config->admdsgn_button_colopc      = '-1';
      $admin_config->admdsgn_button_bg          = '#eeeeee';
      $admin_config->admdsgn_button_bgopc       = '-1';
      $admin_config->admdsgn_button_ovr_col     = '#333333';
      $admin_config->admdsgn_button_ovr_colopc  = '-1';
      $admin_config->admdsgn_button_ovr_bg      = '#eeeeee';
      $admin_config->admdsgn_button_ovr_bgopc   = '-1';

      // Farbverlauf Titelzeile
      $admin_config->admdsgn_titl_bg1           = '#fcfcfc';
      $admin_config->admdsgn_titl_bg2           = '#eeeeee';

      // Rand Inhalt / Titelzeile
      $admin_config->admdsgn_ctnt_border        = '#cccccc';

      return $admin_config;
   }
}
