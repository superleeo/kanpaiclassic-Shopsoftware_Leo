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

header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time()));
header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
$menu           = \KANPAICLASSIC\Control::getMenu();
$admin_config   = $menu->loadDesign();
$help           = \KANPAICLASSIC\Control::getHelp();

?><!DOCTYPE html>
<html lang="de">
<head>
<title>Kanpai Classic <?php echo (!defined('CONF_MODULE_PORTAL') ? 'Shopsystem' : 'Shopportal'); ?> - Administration - Versandeinstellungen</title>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
<link rel="stylesheet" href="<?php echo SHOP_URL; ?>/fonts/fontawesome/css/all.css" />
<style>
<?php include_once ADMIN_PATH.'/css/'.(is_file(ADMIN_PATH.'/css/admin.css') ? 'admin.css' : 'admin_easy.css'); ?>
</style>
<link rel="stylesheet" href="<?php echo SHOP_URL; ?>/fonts/fontawesome/css/all.css" />
</head>

<body>
<div id="page" class="admin_bg">
   <?php echo $menu->printHeader(); ?>
   <div id="menu">
      <?php echo $menu->menuData(); ?>
   </div>

   <div id="content">
      <div id="titelzeile" class="titelzeile">
         <div class='txt_tit'><a class="help_kanpaiclassic" href="<?php echo HELP_LINK; ?>/einstellungen/versandart/" target="_blank"></a>Versandeinstellungen</div>
         <div class="save_button" onclick="forms.versandform.submit()">speichern</div>
      </div>

      <div id="versandart" class="maincontent">
         <div id="content_top"></div>

         <div class="content_boxx">
            <form method="post" id="versandform" action="<?php echo ADMIN_URL_IDX; ?>/versandart/update">
               <div class="mobile_slide">
                  <div class="mobile_slide_inner">
                  <div class="content_box_top">
                     <div id="versand_div">
                        <?php for ($i = 1; $i < 4; $i++) { ?>
                        <div class="versand_list<?php echo ($this->region == 'eu' ? '_eu' : ''); ?> versand_list<?php echo $i; ?>">
                           <h2 class="txt_tit<?php echo ($i > 1 ? ' pointer' : ''); ?>">
                           <?php if ($i == 1) { echo 'Heimatland'; } ?>
                           <?php if ($i == 2) { ?>
                              <?php if ($this->region == 'eu') { echo 'Europäische Union'; } else { echo 'Ausland'; } ?>
                           <?php } if ($i == 3) { ?>
                              <?php if ($this->region == 'eu') { echo 'Außerhalb EU'; } else { echo '</div>'; continue; } ?>
                           <?php } ?>
                           </h2>
                           <hr class="first" />

                           <?php if ($i > 1) { // löschen ?>
                           <div class="versandlist_toggle" data-versand_show="<?php echo $i; ?>">
                              <div class="button_ci txt_but">einblenden</div>
                              <hr />
                           </div>
                           <?php } ?>

                           <div class="versandlist_no_input<?php echo ($i > 1 ? ' show_none' : ''); ?>">
                              <div class="versand_line easy">
                                 <input type="radio" class="newdesign" id="versandart1_<?php echo $i; ?>" name="versandart_<?php echo $i; ?>" value="1" <?php echo $this->{'versand_'.$i}['versandart'] == 1 ? ' checked="checked"' : ''; ?> />
                                 <label for="versandart1_<?php echo $i; ?>">indiv. Versandkosten (addiert)</label>
                                 <?php if ($i == 1) { ?>
                                    <div class="help ci_color" onmouseover='helptipOn(this, "<?php echo $help->getText(2); ?>");' onMouseOut="helptipOff();"></div>
                                 <?php } ?>
                              </div>

                              <div class="versand_line easy">
                                 <input type="radio" class="newdesign" id="versandart2_<?php echo $i; ?>" name="versandart_<?php echo $i; ?>" value="5" <?php echo $this->{'versand_'.$i}['versandart'] == 5 ? ' checked="checked"' : ''; ?> />
                                 <label for="versandart2_<?php echo $i; ?>">indiv. Versandkosten (höchste)</label>
                                 <?php if ($i == 1) { ?>
                                    <div class="help ci_color" onmouseover='helptipOn(this, "<?php echo $help->getText(2); ?>");' onMouseOut="helptipOff();"></div>
                                 <?php } ?>
                              </div>

                              <hr class="versand_line_hr easy" />

                              <div class="versand_line">
                                 <input type="radio" class="newdesign" id="versandart3_<?php echo $i; ?>" name="versandart_<?php echo $i; ?>" value="2" <?php echo $this->{'versand_'.$i}['versandart'] == 2 ? ' checked="checked"' : ''; ?> />
                                 <label for="versandart3_<?php echo $i; ?>">pauschale Versandkosten (<span class="vers_gruen">Netto Warenwert</span>)</label>
                                 <?php if ($i == 1) { ?>
                                    <div class="help ci_color" onmouseover='helptipOn(this, "<?php echo $help->getText(3); ?>");' onMouseOut="helptipOff();"></div>
                                 <?php } ?>
                              </div>

                              <div class="versand_line">
                                 <span class="versand_1">Warenkorb bis</span>
                                 <span class="versand_2"><input type="text" id="versandwert2_<?php echo $i; ?>" name="versandwert2_<?php echo $i; ?>" value="<?php echo number_format($this->{'versand_'.$i}['versandwert2'], 2, ',', ''); ?>" onBlur="Versandart.checkVersandwert(1, <?php echo $i; ?>);" /></span>
                                 <span class="versand_3"><?php echo $this->params->waehrung_iso; ?></span>
                                 <span class="versand_4"><input type="text" name="versandkosten1_<?php echo $i; ?>" id="versandkosten1_<?php echo $i; ?>" value="<?php echo number_format($this->{'versand_'.$i}['versandkosten1'], 2, ',', ''); ?>" onblur='this.value = point2komma(parseFloat(komma2point(this.value)).toFixed(2));' /></span>
                                 <span class="versand_5"><?php echo $this->params->waehrung_iso; ?> Versand</span>
                              </div>
                              <div class="versand_line">
                                 <span class="versand_1">Warenkorb bis</span>
                                 <span class="versand_2"><input type="text" name="versandwert4_<?php echo $i; ?>" id="versandwert4_<?php echo $i; ?>" value="<?php echo number_format($this->{'versand_'.$i}['versandwert4'], 2, ',', ''); ?>" onBlur="Versandart.checkVersandwert(2, <?php echo $i; ?>);" /></span>
                                 <span class="versand_3"><?php echo $this->params->waehrung_iso; ?></span>
                                 <span class="versand_4"><input type="text" name="versandkosten2_<?php echo $i; ?>" id="versandkosten2_<?php echo $i; ?>" value="<?php echo number_format($this->{'versand_'.$i}['versandkosten2'], 2, ',', ''); ?>" onBlur="this.value = point2komma(parseFloat(komma2point(this.value)).toFixed(2));" /></span>
                                 <span class="versand_5"><?php echo $this->params->waehrung_iso; ?> Versand</span>
                              </div>
                              <div class="versand_line margin_bottom">
                                 <span class="versand_1">Warenkorb ab</span>
                                 <span class="versand_2" id="versandwert5_<?php echo $i; ?>"><?php echo number_format($this->{'versand_'.$i}['versandwert4'] + 0.01, 2, ',', ''); ?></span>
                                 <span class="versand_3"><?php echo $this->params->waehrung_iso; ?></span>
                                 <span class="versand_4"><input type="text" name="versandkosten3_<?php echo $i; ?>" id="versandkosten3_<?php echo $i; ?>" value="<?php echo number_format((float)$this->{'versand_'.$i}['versandkosten3'], 2, ',', ''); ?>" onBlur="this.value = point2komma(parseFloat(komma2point(this.value)).toFixed(2));" /></span>
                                 <span class="versand_5"><?php echo $this->params->waehrung_iso; ?> Versand</span>
                              </div>

                              <hr class="versand_line_hr" />

                              <div class="versand_line">
                                 <input type="radio" class="newdesign" id="versandart4_<?php echo $i; ?>" name="versandart_<?php echo $i; ?>" value="3" <?php echo $this->{'versand_'.$i}['versandart'] == 3 ? ' checked="checked"' : ''; ?> />
                                 <label for="versandart4_<?php echo $i; ?>">gewichtsabhängig (<span class="vers_gruen">Netto</span>)</label>
                                 <?php if ($i == 1) { ?>
                                    <div class="help ci_color" onmouseover='helptipOn(this, "<?php echo $help->getText(4); ?>");' onMouseOut="helptipOff();"></div>
                                 <?php } ?>
                              </div>
                              <div class="versand_line">
                                 <span class="versand_span">
                                    <input type="checkbox" class="newdesign" name="gewicht_detail_check<?php echo $i; ?>" id="gewicht_detail_check<?php echo $i; ?>"<?php echo ($this->params->firma['gewicht_detail_check'] == 'y' ? ' checked="checked"' : ''); ?> onchange="($(this).prop('checked') ? $('.gewicht_detail_check').prop('checked', true) : $('.gewicht_detail_check').prop('checked', false));" />
                                    <label for="gewicht_detail_check<?php echo $i; ?>">Gewicht auf Artikelseite anzeigen</label>
                                 </span>
                              </div>
                              <div class="versand_line">
                                 <span class="versand_1">Gewicht bis</span>
                                 <span class="versand_2"><input type="text" id="gewichtwert1_<?php echo $i; ?>" name="gewichtwert1_<?php echo $i; ?>" id="gewichtwert1" value="<?php echo number_format($this->{'versand_'.$i}['gewichtwert1'], 3, ',', ''); ?>" onBlur="Versandart.checkGewichtswert(1, <?php echo $i; ?>);" /></span>
                                 <span class="versand_3">Kg</span>
                                 <span class="versand_4"><input type="text" name="gewichtkosten1_<?php echo $i; ?>" id="gewichtkosten1_<?php echo $i; ?>" value="<?php echo number_format($this->{'versand_'.$i}['gewichtkosten1'], 2, ',', ''); ?>" onblur='this.value = point2komma(parseFloat(komma2point(this.value)).toFixed(2));' /></span>
                                 <span class="versand_5"><?php echo $this->params->waehrung_iso; ?> Versand</span>
                              </div>
                              <div class="versand_line">
                                 <span class="versand_1">Gewicht bis</span>
                                 <span class="versand_2">
                                     <input type="text" id="gewichtwert2_<?php echo $i; ?>" name="gewichtwert2_<?php echo $i; ?>" id="gewichtwert2" value="<?php  echo number_format($this->{'versand_'.$i}['gewichtwert2'], 3, ',', ''); ?>" onblur="Versandart.checkGewichtswert(2, <?php echo $i; ?>);" /></span>
                                 <span class="versand_3">Kg</span>
                                 <span class="versand_4"><input type="text" name="gewichtkosten2_<?php echo $i; ?>" id="gewichtkosten2_<?php echo $i; ?>" value="<?php echo number_format($this->{'versand_'.$i}['gewichtkosten2'], 2, ',', ''); ?>" onBlur="this.value = point2komma(parseFloat(komma2point(this.value)).toFixed(2));" /></span>
                                 <span class="versand_5"><?php echo $this->params->waehrung_iso; ?> Versand</span>
                              </div>
                              <div class="versand_line">
                                 <span class="versand_1">Gewicht bis</span>
                                 <span class="versand_2"><input type="text" id="gewichtwert3_<?php echo $i; ?>" name="gewichtwert3_<?php echo $i; ?>" id="gewichtwert3" value="<?php echo number_format($this->{'versand_'.$i}['gewichtwert3'], 3, ',', ''); ?>" onBlur="Versandart.checkGewichtswert(3, <?php echo $i; ?>);" /></span>
                                 <span class="versand_3">Kg</span>
                                 <span class="versand_4"><input type="text" name="gewichtkosten3_<?php echo $i; ?>" id="gewichtkosten3_<?php echo $i; ?>" value="<?php echo number_format($this->{'versand_'.$i}['gewichtkosten3'], 2, ',', ''); ?>" onBlur="this.value = point2komma(parseFloat(komma2point(this.value)).toFixed(2));" /></span>
                                 <span class="versand_5"><?php echo $this->params->waehrung_iso; ?> Versand</span>
                              </div>
                              <div class="versand_line">
                                 <span class="versand_1">Gewicht bis</span>                                 <span class="versand_2"><input type="text" id="gewichtwert4_<?php echo $i; ?>" name="gewichtwert4_<?php echo $i; ?>" id="gewichtwert4" value="<?php echo number_format($this->{'versand_'.$i}['gewichtwert4'], 3, ',', ''); ?>" onBlur="Versandart.checkGewichtswert(4, <?php echo $i; ?>);" /></span>
                                 <span class="versand_3">Kg</span>
                                 <span class="versand_4"><input type="text" name="gewichtkosten4_<?php echo $i; ?>" id="gewichtkosten4_<?php echo $i; ?>" value="<?php echo number_format($this->{'versand_'.$i}['gewichtkosten4'], 2, ',', ''); ?>" onBlur="this.value = point2komma(parseFloat(komma2point(this.value)).toFixed(2));" /></span>
                                 <span class="versand_5"><?php echo $this->params->waehrung_iso; ?> Versand</span>
                              </div>
                              <div class="versand_line margin_bottom">
                                 <span class="versand_1">Gewicht ab</span>
                                 <span class="versand_2" id="gewichtwert5_<?php echo $i; ?>"><?php echo number_format($this->{'versand_'.$i}['gewichtwert4'] + 0.001, 3, ',', ''); ?></span>
                                 <span class="versand_3">Kg</span>
                                 <span class="versand_4"><input type="text" name="gewichtkosten5_<?php echo $i; ?>" id="gewichtkosten5_<?php echo $i; ?>" value="<?php echo number_format($this->{'versand_'.$i}['gewichtkosten5'], 2, ',', ''); ?>" onBlur="this.value = point2komma(parseFloat(komma2point(this.value)).toFixed(2));" /></span>
                                 <span class="versand_5"><?php echo $this->params->waehrung_iso; ?> Versand</span>
                              </div>

                              <hr class="versand_line_hr" />

                              <div class="versand_line" style="display:none;">
                                 <input type="radio" class="newdesign" id="versandart_<?php echo $i; ?>" name="versandart_<?php echo $i; ?>" value="4" <?php echo $this->{'versand_'.$i}['versandart'] == 4 ? ' checked="checked"' : ''; ?> />
                                 <label for="versandart_<?php echo $i; ?>">Versandkosten pro Stück</label>
                                 <?php if ($i == 1) { ?>
                                    <div class="help ci_color" onmouseover='helptipOn(this, "<?php echo $help->getText(5); ?>");' onMouseOut="helptipOff();"></div>
                                 <?php } ?>
                              </div>


                               <?php if($i == 1){ ?>
                              <div class="versand_line">
                                 <input type="checkbox" class="newdesign" name="abholung_check_<?php echo $i; ?>" id="abholung_check_<?php echo $i; ?>" <?php echo $this->{'versand_'.$i}['abholung_check'] == 'y' ? ' checked="checked"' : ''; ?> />
                                 <label for="abholung_check_<?php echo $i; ?>" class="versand_text">Abholung</label>

                                 <span class="versand_2_netto vers_gruen">Netto</span>
                                 <span class="versand_4">
                                    <input type="text" name="abholung_preis_<?php echo $i; ?>" id="abholung_preis_<?php echo $i; ?>" value="<?php echo str_replace('.', ',', (sprintf('%01.2f', $this->{'versand_'.$i}['abholung_preis']))); ?>" onblur='this.value = point2komma(parseFloat(komma2point(this.value)).toFixed(2));' />
                                 </span>
                                 <span class="versand_5"><?php echo $this->params->waehrung_iso; ?></span>
                              </div>
                               <?php }else{ ?>
                                         
                               <div class="versand_line">
                                   <input style="visibility:hidden" type="checkbox" class="newdesign" name="abholung_check_<?php echo $i; ?>" id="abholung_check_<?php echo $i; ?>" <?php echo $this->{'versand_'.$i}['abholung_check'] == 'y' ? ' checked="checked"' : ''; ?> />
                                   <label style="visibility:hidden"  for="abholung_check_<?php echo $i; ?>" class="versand_text">Abholung</label>

                                   <span class="versand_2_netto vers_gruen" style="visibility:hidden">Netto</span>
                                   <span class="versand_4" style="visibility:hidden">
                                       <input type="text" name="abholung_preis_<?php echo $i; ?>" id="abholung_preis_<?php echo $i; ?>" value="<?php echo str_replace('.', ',', (sprintf('%01.2f', $this->{'versand_'.$i}['abholung_preis']))); ?>" onblur='this.value = point2komma(parseFloat(komma2point(this.value)).toFixed(2));' />
                                   </span>
                                   <span class="versand_5" style="visibility:hidden">
                                       <?php echo $this->params->waehrung_iso; ?>
                                   </span>
                               </div>      
                                         
                                <?php } ?>

                              <div class="versand_line easy">
                                 <input type="checkbox" class="newdesign" name="check_vers_frei_<?php echo $i; ?>" id="check_vers_frei_<?php echo $i; ?>" <?php echo $this->{'versand_'.$i}['check_vers_frei'] == 'y' ? ' checked="checked"' : ''; ?> />
                                 <label for="check_vers_frei_<?php echo $i; ?>">versandkostenfrei ab</label>
                                 <span class="versand_2_netto vers_gruen"><?php echo ($this->params->firma['tax_show'] == 'y' ? 'Brutto' : 'Netto'); ?></span>
                                 <div class="versand_4">
                                    <input type="text" name="vers_frei_<?php echo $i; ?>" id="vers_frei_<?php echo $i; ?>" value="<?php echo str_replace('.', ',', (sprintf('%01.2f', $this->{'versand_'.$i}['vers_frei']))); ?>" onblur='this.value = point2komma(parseFloat(komma2point(this.value)).toFixed(2));' />
                                 </div>
                                 <span class="versand_5"><?php echo $this->params->waehrung_iso; ?></span>
                              </div>

                              <div class="versand_line easy">
                                 <input type="checkbox" class="newdesign" name="min_preis_check_<?php echo $i; ?>" id="min_preis_check_<?php echo $i; ?>" <?php echo $this->{'versand_'.$i}['min_preis_check'] == 'y' ? ' checked="checked"' : ''; ?> />
                                 <label for="min_preis_check_<?php echo $i; ?>">Mindestbestellwert</label>
                                 <span class="versand_2_netto vers_gruen"><?php echo ($this->params->firma['tax_show'] == 'y' ? 'Brutto' : 'Netto'); ?></span>
                                 <div class="versand_4">
                                    <input type="text" name="min_preis_<?php echo $i; ?>" id="min_preis_<?php echo $i; ?>" value="<?php echo str_replace('.', ',', (sprintf('%01.2f', $this->{'versand_'.$i}['min_preis']))); ?>" onblur='this.value = point2komma(parseFloat(komma2point(this.value)).toFixed(2));' />
                                 </div>
                                 <span class="versand_5"><?php echo $this->params->waehrung_iso; ?></span>
                              </div>


                              <?php if (defined('CONF_MODULE_SPEDITION')) { ?>
                              <hr class="versand_line_hr" />
                              <div class="spedition">
                                 <div class="versandlist_no_input">
                                    <?php if ($i > 1) { ?>
                                    <div class="versandlist_toggle" data-versand_show="<?php echo $i; ?>"></div>
                                    <?php } ?>

                                    <div class="versand_line">
                                       <span class="versand_text">Spedition 1</span>
                                       <?php if ($i == 1) { ?>
                                          <span class="help ci_color" onmouseover='helptipOn(this, "<?php echo $help->getText(11); ?>");' onMouseOut="helptipOff();"></span>
                                          <span class="versand_2_netto vers_gruen">Netto</span>
                                       <?php } ?>
                                       <span class="versand_4">
                                          <input type="text" name="spedition<?php echo $i; ?>_preis_1" id="spedition<?php echo $i; ?>_preis_1" value="<?php echo str_replace('.', ',', (sprintf('%01.2f', $this->{'versand_'.$i}['spedition_preis_1']))); ?>" onblur='this.value = point2komma(parseFloat(komma2point(this.value)).toFixed(2));' />
                                       </span>
                                       <span class="versand_5"><?php echo $this->params->waehrung_iso; ?> Versand</span>
                                    </div>

                                    <div class="versand_line">
                                       <span class="versand_text">Spedition 2</span>
                                       <?php if ($i == 1) { ?>
                                          <span class="versand_2_netto vers_gruen">Netto</span>
                                       <?php } ?>
                                       <span class="versand_4">
                                          <input type="text" name="spedition<?php echo $i; ?>_preis_2" id="spedition<?php echo $i; ?>_preis_2" value="<?php echo str_replace('.', ',', (sprintf('%01.2f', $this->{'versand_'.$i}['spedition_preis_2']))); ?>" onblur='this.value = point2komma(parseFloat(komma2point(this.value)).toFixed(2));' />
                                       </span>
                                       <span class="versand_5"><?php echo $this->params->waehrung_iso; ?> Versand</span>
                                    </div>

                                    <div class="versand_line">
                                       <span class="versand_text">Spedition 3</span>
                                       <?php if ($i == 1) { ?>
                                          <span class="versand_2_netto vers_gruen">Netto</span>
                                       <?php } ?>
                                       <span class="versand_4">
                                          <input type="text" name="spedition<?php echo $i; ?>_preis_3" id="spedition<?php echo $i; ?>_preis_3" value="<?php echo str_replace('.', ',', (sprintf('%01.2f', $this->{'versand_'.$i}['spedition_preis_3']))); ?>" onblur='this.value = point2komma(parseFloat(komma2point(this.value)).toFixed(2));' />
                                       </span>
                                       <span class="versand_5"><?php echo $this->params->waehrung_iso; ?> Versand</span>
                                    </div>
                                 </div>
                              </div>
                              <?php } ?>
                              <div class="clear"></div>
                              <hr class="last" />
                           </div>
                        </div>
                        <?php } ?>
                        <div class="clear"></div>
                     </div>
                  </div>
                  </div>
               </div>

               <div class="content_box_bottom">
                  <div class="laender">
                     <a class="button txt_but" href="<?php echo ADMIN_URL_IDX; ?>/laender">Länderaufschlag</a>
                  </div>

                  <div id="rechner_left">
                     <div class="netto_eingabe">
                        Alle Werte bitte in Netto eingeben. Hierdurch werden Rundungsprobleme zu 100% ausgeschlossen. Zur Unterstützung der Eingabe nutzen Sie folgenden <strong>Brutto-Netto-Rechner:</strong><br /><br />
                     </div>

                     <div class="rechner_versandfrei">
                        <div class="rechner">
                           <div class="calc_brutto">Brutto<br />
                              <input type="text" name="brutto_calc" id="brutto_calc" value="0,00" onKeyUp="document.getElementById('netto_calc').value = point2komma(runden(komma2point(this.value) / <?php echo (float)(1 + ($this->params->firma['tax1'] / 100)); ?>, 2));" />
                           </div>
                           <div class="calc_netto">Netto<br />
                                <input type="text" name="netto_calc" id="netto_calc" value="0,00" onKeyUp="document.getElementById('brutto_calc').value = point2komma(runden(komma2point(this.value) * <?php echo (float)(1 + ($this->params->firma['tax1'] / 100)); ?>, 2));" />
                           </div>
                        </div>
                     </div>
                  </div>

                  <div id="rechner_right">
                     <div class="versandfrei easy">
                        <input type="checkbox" class="newdesign" name="vers_grafik_check" id="vers_grafik_check"<?php echo ($this->params->firma['vers_grafik_check'] == 'y' ? ' checked="checked"' : ''); ?> />
                        <label for="vers_grafik_check"></label>
                        <span class="label_text">"versandkostenfrei"-Grafik global&nbsp;
                           <span class="help ci_color pointer" title=" nur bei indiv. Versandkosten oder Versandgewicht 0 Kg"></span>
                        </span>
                     </div>

                     <div class="versandfrei">
                        <input type="checkbox" class="newdesign" name="mindest_check" id="mindest_check"<?php echo ($this->params->firma['mindest_check'] == 'y' ? ' checked="checked"' : ''); ?> />
                        <label for="mindest_check"></label>
                        <span class="label_text">Mindestbestellwert Hinweis&nbsp;
                           <span class="help ci_color pointer" title="Hinweis&nbsp;im&nbsp;Artikel,&nbsp;wenn&nbsp;Artikelpreis kleiner&nbsp;als&nbsp;Mindestbestellwert&nbsp;ist (von&nbsp;Anwälten&nbsp;empfohlen)"></span>
                        </span>
                     </div>
                  </div>
                  <div class="clear"></div>
               </div>
            </form>
         </div>
      </div>
   </div>
   <?php $menu->footer(); ?>
</div>
<script>
var langs         = '<?php echo implode(';', $this->params->langs); ?>'; // vorhandene Sprachen - Nicht bei allen Templates notwendig
var sel_lang      = 'deu'; // gewählte Sprache - nicht bei allen Templates notwendig
var default_lang  = '<?php echo $this->params->default_lang; ?>';
var admin_url_idx = '<?php echo ADMIN_URL_IDX; ?>';
var admin_url     = '<?php echo ADMIN_URL; ?>';
var shopurl_idx   = '<?php echo SHOP_URL_IDX; ?>';
var shop_url      = '<?php echo SHOP_URL; ?>';
var template_url  = '<?php echo TEMPLATE_URL; ?>';
var max_file_size = '<?php echo max(KANPAICLASSIC\Helper::mbytesToBytes(ini_get('upload_max_filesize')), KANPAICLASSIC\Helper::mbytesToBytes(ini_get('post_max_size'))); ?>';
var editor_css    = "<?php echo TEMPLATE_URL; ?>/css/editor.css";
</script>

<script src="<?php echo SHOP_URL;  ?>/js/jquery3.min.js"></script>
<script src="<?php echo SHOP_URL;  ?>/js/jquery-ui.min.js"></script>
<script src="<?php echo ADMIN_URL; ?>/js/admin.js"></script>
<!-- <script src="<?php echo ADMIN_URL; ?>/js/jquery.minicolors.min.js"></script> -->
</body>
</html>
