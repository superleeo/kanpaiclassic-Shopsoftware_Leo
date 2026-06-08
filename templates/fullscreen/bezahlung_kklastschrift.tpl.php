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

$inhaber_err = false;
$name_err = false;
$nr_err = false;
$datum_err = false;
$pruef_err = false;

if(isset($_SESSION['err_kk_inhaber'])) {
   $inhaber_err = true;
}
if (isset($_SESSION['err_kk_name'])) {
   $name_err = true;
}
if (isset($_SESSION['err_kk_nr'])) {
   $nr_err = true;
}
if (isset($_SESSION['err_kk_datum'])) {
   $datum_err = true;
}

if (isset($_SESSION['err_kk_pruef'])) {
   $pruef_err = true;
}

// Kontoinhaber mit Vorname Nachname vorbelegen, falls leer
$inhaber = $data['bank_inhaber'];
if ($inhaber == '') {
   $inhaber = $data['vorname'] . ' ' . $data['nachname'];
}

list($kk_datum, $kk_pruef) = explode(':::', $data['kk_datum']);
list($kk_jahr, $kk_monat, $kk_tag) = explode('-', $kk_datum);
if((int)$kk_jahr == 0) {
   $kk_jahr = '';
}
if((int)$kk_monat == 0) {
   $kk_monat = '';
}
if((int)$kk_jahr == 0) {
   $kk_tag = '';
}
?>
<div class="col_single">
   <div class="col_single_center">
      <div class="col_single ueberschrift text_gross center">
         <?php echo $text->get('bezahlung_3', 'subtitel'); ?>
      </div>

      <div class="col_single">
         <form method="post" action="<?php echo SHOP_URL_IDX; ?>/bestellt">
            <div class="line">&nbsp;</div>

            <div class="line">
               <div class="line_left ueberschrift text_max"><?php echo $text->get('bezahlung_0', 'betrag'); ?></div>
               <div class="line_center"></div>

               <?php if ($params->firma['price_login'] == 'y' && $params->user_id == 0) { ?>
               <div class="line_right fliesstext text_normal"><img src="<?php echo TEMPLATE_URL.'/images/system/btn_preis_nl_' . $params->selected_lang . '.jpg'; ?>" /></div>
               <?php } else { ?>
               <div class="line_right ueberschrift text_max"><?php echo KANPAICLASSIC\Helper::number_format($gesamt_show, 2, ',', '.'). ' '.$params->waehrung; ?></div>
               <?php } ?>
            </div>

            <div class="line">
               <input type="hidden" name="bezahlung" value="lastschrift" />
               <div class="line_left fliesstext text_gross"><?php echo $text->get('adresse', 'bestnr'); ?></div>
               <div class="line_center"></div>
               <div class="line_right fliesstext text_gross"><?php echo $_SESSION['bestellnummer']; ?></div>
            </div>

            <?php $err = $name_err ? ' form_err' : ''; ?>
            <div class="line">
               <div class="line_left fliesstext text_normal<?php echo $err; ?>"><?php echo $text->get('shop', 'kk_name'); ?> *</div>
               <div class="line_center"></div>
               <div class="line_right"><input type="text" class="text_formular text_gross<?php echo $err; ?>" name="kk_name" id="kk_name" value="<?php echo $data['kk_name']; ?>" /></div>
            </div>

            <?php $err = $nr_err ? ' form_err' : ''; ?>
            <div class="line">
               <div class="line_left fliesstext text_normal<?php echo $err; ?>"><?php echo $text->get('shop', 'kk_nr'); ?> *</div>
               <div class="line_center"></div>
               <div class="line_right"><input type="text" class="text_formular text_gross<?php echo $err; ?>" name="kk_nr" id="kk_nr" value="<?php echo $data['kk_nr']; ?>" /></div>
            </div>

            <?php $err = $datum_err ? ' form_err' : ''; ?>
            <div class="line">
               <div class="line_left fliesstext text_normal<?php echo $err; ?>"><?php echo $text->get('shop', 'kk_datum'); ?> *</div>
               <div class="line_center"></div>
               <div class="line_right">
                  <input type="text" class="datum_monat text_formular text_gross<?php echo $err; ?>" name="kk_monat" id="kk_monat" value="<?php echo $kk_monat; ?>" placeholder="MM" />
                  <input type="text" class="datum_jahr text_formular text_gross<?php echo $err; ?>" name="kk_jahr" id="kk_jahr" value="<?php echo $kk_jahr; ?>" placeholder="YYYY" />
                  <div class="clear"></div>
               </div>
            </div>

            <?php $err = $pruef_err ? ' form_err' : ''; ?>
            <div class="line">
               <div class="line_left fliesstext text_normal<?php echo $err; ?>"><?php echo $text->get('shop', 'kk_pruef'); ?> *</div>
               <div class="line_center"></div>
               <div class="line_right"><input type="text" class="text_formular text_gross<?php echo $err; ?>" name="kk_pruef" id="kk_pruef" value="<?php echo $kk_pruef; ?>" /></div>
            </div>

            <?php $err = $inhaber_err ? ' form_err' : ''; ?>
            <div class="line">
               <div class="line_left fliesstext text_normal<?php echo $err; ?>"><?php echo $text->get('shop', 'kk_inhaber'); ?> *</div>
               <div class="line_center"></div>
               <div class="line_right"><input type="text" class="text_formular text_gross<?php echo $err; ?>" name="kk_inhaber" id="kk_inhaber" value="<?php echo $inhaber; ?>" /></div>
            </div>

            <div class="line">&nbsp;</div>

            <button class="line bg_button col_button text_gross button55"><?php echo $text->get('button', 'zahlpfl'); ?></button>
         </form>
      </div>
   </div>
</div>
