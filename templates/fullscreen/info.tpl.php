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

$widerruf_flag = false;

if ($params->task == 'widerruf1' && $params->firma['widerruf1_form'] == 'y' ||
    $params->task == 'widerruf2' && $params->firma['widerruf2_form'] == 'y' ||
    $params->task == 'widerruf3' && $params->firma['widerruf3_form'] == 'y' ||
    $params->task == 'widerruf4' && $params->firma['widerruf4_form'] == 'y' ||
    $params->task == 'widerruf5' && $params->firma['widerruf5_form'] == 'y')
{
   $widerruf_flag = true;
}
?>
<div class="x_info">
<div class="col_single site_head bg_flaechen">
   <div class="ueberschrift text_max">
      <?php echo $infotitel; ?>
   </div>
</div>

<div id="info">
   <div class="col_single bg_flaechen">
      <div class="col_inner">
<?php
if (substr($params->task, 0, 8) == 'ueberuns' || $params->task == 'impressum' || $params->task == 'kontakt') {
   $cat_html  = '';
   $img_path  = TEMPLATE_URL.'/images/';
   $file_path = TEMPLATE_PATH.'/images/';
   $uns       = 1;

   if ($params->task == 'ueberuns2') { $uns = 2; }
   if ($params->task == 'ueberuns3') { $uns = 3; }
   if ($params->task == 'ueberuns4') { $uns = 4; }
   if ($params->task == 'ueberuns5') { $uns = 5; }
   if ($params->task == 'impressum') { $uns = 11; }
   if ($params->task == 'kontakt')   { $uns = 12; }

   $image1  = KANPAICLASSIC\Helper::getData('ueberuns'.$uns.'_'.$lang.'_image1');
   $image2  = KANPAICLASSIC\Helper::getData('ueberuns'.$uns.'_'.$lang.'_image2');
   $link1   = KANPAICLASSIC\Helper::getData('ueberuns'.$uns.'_'.$lang.'_link1');
   $link2   = KANPAICLASSIC\Helper::getData('ueberuns'.$uns.'_'.$lang.'_link2');
   $intern1 = KANPAICLASSIC\Helper::getData('ueberuns'.$uns.'_'.$lang.'_intern1');
   $intern2 = KANPAICLASSIC\Helper::getData('ueberuns'.$uns.'_'.$lang.'_intern2');
   $search1 = KANPAICLASSIC\Helper::getData('ueberuns'.$uns.'_'.$lang.'_search1');
   $search2 = KANPAICLASSIC\Helper::getData('ueberuns'.$uns.'_'.$lang.'_search2');

   if ($image1 != '' && file_exists($file_path.$image1.'.jpg') || $image2 != ''&& file_exists($file_path.$image2.'.jpg')) {
      $cat_html .= '         <div class="col_single col_img">';
      $cat_html .= '            <div class="col_lsl_l" style="min-height:1px;">';

      if ($image1 != ''&& file_exists($file_path.$image1.'.jpg')) {
         $img_html = '<img src="'.$img_path.$image1.'.jpg" alt="'.$search1.'" title="'.$search1.'" />';

         if ($link1 != '') {
            $cat_html .= '               <a href="'.$link1.'" target="'.($intern1 == 'y' ? '_self' : '_blank').'" title="'.$search1.'">'.$img_html.'</a>';
         }

         else {
            $cat_html .= $img_html;
         }
      }

      $cat_html .= '            </div>';
      $cat_html .= '            <div class="col_lsl_m"></div>';
      $cat_html .= '            <div class="col_lsl_r">';

      if ($image2 != ''&& file_exists($file_path.$image2.'.jpg')) {
         $img_html = '<img src="'.$img_path.$image2.'.jpg" alt="'.$search2.'"  title="'.$search2.'" />';
         if ($link2 != '') {
            $cat_html .= '               <a href="'.$link2.'" target="'.($intern2 == 'y' ? '_self' : '_blank').'"title="'.$search2.'">'.$img_html.'</a>';
         }
         else {
            $cat_html .= $img_html;
         }
      }

      $cat_html .= '            </div>';
      $cat_html .= '         </div>';
      $cat_html .= '         <div class="clear"></div>';
      $cat_html .= '         <br />';

      echo $cat_html;
   }
}
?>
         <div id="info-inhalt" class="fliesstext text_normal">
            <?php echo str_replace('[NEUESEITE]', '<br />', $infotext); ?>
            <?php
            if ($widerruf_flag) {
               include TEMPLATE_PATH.'/widerruf_form.tpl.php';
            }
            ?>
         </div>
      </div>
   </div>
</div>
</div>
