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

$mode               = $this->postString('mode');
$html               = '';
$show_lang          = ($this->postString('show_lang') != '' ? $this->postString('show_lang') : $this->selected_lang);
$text = \KANPAICLASSIC\Control::getText();


$newsletter_erhalten = $text->get('kunde', 'newsletter');
$bitte_waehlen = $text->get('bitte', 'waehlen');
$herr = $text->get('kunde', 'herr');
$frau = $text->get('kunde', 'frau');



$nachname = $text->get('kunde', 'nachname');
$vorname = $text->get('kunde', 'vorname');

$email = $text->get('login', 'email');
$ich_habe_die = $text->get('kunde', 'lesen1');
$datenschutzbestimmungen = $text->get('kunde', 'daten');
$zur_kenntnis_genommen = $text->get('kunde', 'lesen2');
$anmelden = $text->get('button', 'anmelden');
$sie_erhalten_eine_mail_mit_link = $text->get('anmeldenok', 'msg');

$form_ok = $text->get('kontakt', 'versendet');

global $db;

$newsletter_text = readText('newsletter');
ob_start();

?>

<div>
    <div class="text_gross ueberschrift" style="text-align:center;padding-bottom:10px;">

        <?php
        echo $newsletter_erhalten;
        ?>

       
    </div>
</div>

<style>

    #newsletterpopup_geschlecht {
        border: 1px solid #888888;
        display: inline-block;
        height: 34px;
        line-height: 32px;
        padding: 0 5px;
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box;
        box-sizing: border-box;
        width:100%;
        padding-left:0;
        
        margin-bottom:3px;
    }

    .newsletter_popup_form input{
        width:100%;
        margin-bottom:3px;
    }

    .newsletter_popup_form input[type='checkbox']{
        width:initial;
    }

</style>

<div class="col_lsl" style="">
    <div class="" style="width:100%;  margin:auto;">
        <form id="newsletter_popup_form" class='newsletter_popup_form' action="" method="post" >

            
            <div class="line">
            <select class="text_formular text_normal" id="newsletterpopup_geschlecht" name="newsletterpopup_geschlecht">
               <option value=""><?php echo $bitte_waehlen; ?></option>
               <option value="herr"><?php echo $herr; ?></option>
               <option value="frau"><?php echo $frau; ?></option>               
            </select>
            </div>

            <div class="line">
                <input type="text" class="text_formular text_normal" id="newsletterpopup_firstname" name="newsletterpopup_firstname" value="" placeholder="<?php echo $vorname; ?>" />
            </div>

            <div class="line">
                <input type="text" class="text_formular text_normal" id="newsletterpopup_lastname" name="newsletterpopup_lastname" value="" placeholder="<?php echo $nachname; ?>" />
            </div>

            <div class="line">
                <input type="email" class="text_formular text_normal" id="newsletterpopup_mail" name="newsletterpopup_mail" value="" placeholder="<?php echo $email; ?>" required/>
            </div>


            <div class="line checkline" style="margin-top:5px;">
                
                <span class="checkbox"><input id="newsletterpopup_ds" name="newsletterpopup_ds" type="checkbox"  required /></span>

                <span class="kontakt_ds_text fliesstext text_klein">
                    
                    <p><?php echo $newsletter_text; ?></p>

                   <?php echo $ich_habe_die; ?>
                    <a href="/datenschutz" class="fliesstext" target="_blank">
                        <strong><?php echo $datenschutzbestimmungen; ?></strong>
                    </a> <?php echo $zur_kenntnis_genommen; ?>
                </span>
               
          
                <button type="submit" class="col_button bg_button text_gross button55" style="width:100%;margin-top:15px;">
                    <?php echo $anmelden; ?>
                </button>

                <span style="visibility:hidden;padding-top:15px;display:block;" class="fliesstext text_gross center form_ok show_after_newsletter_sent_success"><?php echo $form_ok; ?></span>


            </div>

            
        </form>

    </div>

</div>




<?php

$html = ob_get_clean();

return $html;

?>