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

$widerruf = KANPAICLASSIC\Control::getWiderruf();
?>
<br /><br />
<div id="widerruf" class="col_single bg_flaechen">
   <div class="col_inner"></div>

   <div class="col_single_center">
      <div class="line ueberschrift text_max"><?php echo $text->get('widerruf', 'form'); ?></div>
      <br />

      <div class="line shop_adr">
         <div class="line_shop"><?php echo $text->get('widerruf', 'an'); ?>:&nbsp;</div>
         <div class="line_adr">
            <span class="fliesstext text_normal"><?php echo str_replace(' ', '&nbsp;', $params->firma['shop_name']); ?>,</span>
            <span class="fliesstext text_normal"><?php echo str_replace(' ', '&nbsp;', $params->firma['first_name']).' '. $params->firma['last_name']; ?>,</span>
            <span class="fliesstext text_normal"><?php echo str_replace(' ', '&nbsp;', $params->firma['street'].' '.$params->firma['haus_nr']); ?>,</span>
            <span class="fliesstext text_normal"><?php echo str_replace(' ', '&nbsp;', $params->firma['postal_code'].' '.$params->firma['city']); ?>,</span>
            <span class="fliesstext text_normal"><?php echo str_replace(' ', '&nbsp;', ($params->firma['fax'] != '' ? 'Fax '.$params->firma['fax'].', ' : '')); ?></span>
            <span class="fliesstext text_normal"><?php echo str_replace(' ', '&nbsp;', $params->firma['email']); ?></span>
         </div>
      </div>

      <div class="line fliesstext text_mormal ware_dl"><?php echo $text->get('widerruf', 'vertrag'); ?></div>

      <form method="post" action="">
         <?php $err = ($widerruf->error_name ? ' form_err' : ''); ?>
         <div class="line">
            <div class="line_left fliesstext text_normal<?php echo $err; ?>"><?php echo $text->get('widerruf', 'verbraucher'); ?></div>
            <div class="line_stern fliesstext text_normal">*</div>
            <div class="line_right"><input type="text" class="text_formular text_normal<?php echo $err; ?>" name="name" value="<?php echo $widerruf->name; ?>" /></div>
         </div>

         <?php $err = ($widerruf->error_email ? ' form_err' : ''); ?>
         <div class="line">
            <div class="line_left fliesstext text_normal<?php echo $err; ?>"><?php echo $text->get('widerruf', 'email'); ?></div>
            <div class="line_stern fliesstext text_normal">*</div>
            <div class="line_right"><input type="text" class="line_left text_formular text_normal<?php echo $err; ?>" name="email" value="<?php echo $widerruf->email; ?>" /></div>
         </div>

         <?php $err = ($widerruf->error_adresse ? ' form_err' : ''); ?>
         <div class="line">
            <div class="line_left fliesstext text_normal<?php echo $err; ?>"><?php echo $text->get('widerruf', 'anschrift'); ?></div>
            <div class="line_stern fliesstext text_normal">*</div>
            <div class="line_right"><input type="text" class="line_left text_formular text_normal<?php echo $err; ?>" name="adresse" value="<?php echo $widerruf->adresse; ?>" /></div>
            <input type="hidden" name="formular_loaded" value="y">
            </div>

         <div class="line18">&nbsp;</div>

         <div class="line">
            <div class="line_left">&nbsp;</div>
            <div class="line_stern"></div>
            <div class="line_right fliesstext text_normal ware_dl"><strong><?php echo $text->get('widerruf', 'kauf'); ?></strong></div>
         </div>

         <div class="line">
            <div class="line_left fliesstext text_normal"><?php echo $text->get('adresse', 'bestnr'); ?></div>
            <div class="line_stern"></div>
            <div class="line_right"><input type=text class="line_left text_formular text_normal" name="bestnr_1" value="<?php echo $widerruf->bestnr_1; ?>" /></div>
         </div>

         <div class="line">
            <div class="line_left fliesstext text_normal"><?php echo $text->get('widerruf', 'artikelbez'); ?></div>
            <div class="line_stern"></div>
            <div class="line_right"><input type=text class="line_left text_formular text_normal" name="bez_1" value="<?php echo $widerruf->bez_1; ?>" /></div>
         </div>

         <div class="line">
            <div class="line_left fliesstext text_normal"><?php echo $text->get('artikel', 'menge'); ?></div>
            <div class="line_stern"></div>
            <div class="line_right"><input type=text class="line_left text_formular text_normal" name="menge_1" value="<?php echo $widerruf->menge_1 ?>" /></div>
         </div>

         <div class="line">
            <div class="line_left fliesstext text_normal"><?php echo $text->get('widerruf', 'grund'); ?></div>
            <div class="line_stern"></div>
            <div class="line_right"><input type=text class="line_left text_formular text_normal" name="grund_1" value="<?php echo $widerruf->grund_1 ?>" /></div>
         </div>

         <div class="line18">&nbsp;</div>

         <div class="line">
            <div class="line_left">&nbsp;</div>
            <div class="line_stern"></div>
            <div class="line_right fliesstext text_normal ware_dl"><strong><?php echo $text->get('widerruf', 'dl'); ?></strong></div>
         </div>

         <div class="line">
            <div class="line_left fliesstext text_normal"><?php echo $text->get('adresse', 'bestnr'); ?></div>
            <div class="line_stern fliesstext text_normal"></div>
            <div class="line_right"><input type=text class="line_left text_formular text_normal" name="bestnr_2" value="<?php echo $widerruf->bestnr_2; ?>" /></div>
         </div>

         <div class="line">
            <div class="line_left fliesstext text_normal"><?php echo $text->get('widerruf', 'dienstl'); ?></div>
            <div class="line_stern fliesstext text_normal"></div>
            <div class="line_right"><input type=text class="line_left text_formular text_normal" name="bez_2" value="<?php echo $widerruf->bez_2; ?>" /></div>
         </div>

         <div class="line">
            <div class="line_left fliesstext text_normal"><?php echo $text->get('artikel', 'menge'); ?></div>
            <div class="line_stern"></div>
            <div class="line_right"><input type=text class="line_left text_formular text_normal" name="menge_2" value="<?php echo $widerruf->menge_2 ?>" /></div>
         </div>

         <div class="line">
            <div class="line_left fliesstext text_normal"><?php echo $text->get('widerruf', 'grund'); ?></div>
            <div class="line_stern fliesstext text_normal"></div>
            <div class="line_right"><input type=text class="line_left text_formular text_normal" name="grund_2" value="<?php echo $widerruf->grund_2 ?>" /></div>
         </div>

         <div class="line18">&nbsp;</div>

         <?php $err = ($widerruf->error_bestellt ? ' form_err' : ''); ?>
         <div class="line">
            <div class="line_left fliesstext text_normal<?php echo $err; ?>"><?php echo $text->get('widerruf', 'bestellt'); ?></div>
            <div class="line_stern fliesstext text_normal">*</div>
            <div class="line_right"><input type="text" class="text_formular text_normal<?php echo $err; ?>" name="bestellt" value="<?php echo $widerruf->bestellt; ?>" /></div>
         </div>

         <?php $err = ($widerruf->error_erhalten ? ' form_err' : ''); ?>
         <div class="line shop_adr">
            <div class="line_left fliesstext text_normal<?php echo $err; ?>"><?php echo $text->get('widerruf', 'erhalten'); ?></div>
            <div class="line_stern fliesstext text_normal">*</div>
            <div class="line_right"><input type="text" class="text_formular text_normal<?php echo $err; ?>" name="erhalten" value="<?php echo $widerruf->erhalten; ?>" /></div>
         </div>

   <?php if ($widerruf->mail_send == '') { ?>
      <?php if ($widerruf->captcha == '' || $widerruf->check_error !== false) { ?>
         <?php $err = ($widerruf->error_captcha ? ' form_err' : ''); ?>
         <div class="line18">&nbsp;</div>

         <div class="line">
            <div class="line_left">&nbsp;</div>
            <div class="line_stern"></div>
            <div class="line_right center captcha"><img src="<?php echo $_SESSION['captcha']['image_src']; ?>" alt="" /></div>
         </div>

         <div class="line">
            <div class="line_left<?php echo $err; ?>"><?php echo $text->get('kontakt', 'scode'); ?></div>
            <div class="line_stern fliesstext text_normal">*</div>
            <div class="line_right"><input type="text" class="text_formular text_normal<?php echo $err; ?>" name="captcha" value="<?php echo $widerruf->captcha; ?>" /></div>
         </div>

         <div class="line">
            <div class="line_left fliesstext text_normal<?php echo $err; ?>">* <?php echo $text->get('kontakt', 'muss'); ?></div>
            <div class="line_stern fliesstext text_normal"></div>
            <div class="line_right">&nbsp;</div>
         </div>

         <div class="line18">&nbsp;</div>
         <button class="col_single col_button bg_button text_gross button55"><?php echo $text->get('button', 'senden'); ?></button>
      <?php } else { ?>
         <input type="hidden" name="captcha" value="<?php echo $widerruf->captcha; ?>" />
      <?php } ?>

   <?php } else { ?>
      <?php if ($widerruf->mail_send == 'send') { ?>
         <div class="line form_ok fliesstext text_max center"><?php echo $text->get('kontakt', 'versendet'); ?></div>
      <?php } else { ?>
         <div class="line form_err fliesstext text_max center">Error while sending</div>
      <?php } ?>
   <?php } ?>
      </form>
   <div class="clear"></div>
   </div>
</div>
