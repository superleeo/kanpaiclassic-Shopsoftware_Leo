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

$re_id = $params->re_id;

?>
<div class="col_single site_head bg_flaechen">
   <div class="ueberschrift text_max">
      <?php echo $text->get('easycredit', 'title'); ?>
   </div>
</div>

<div id="bez_mod_ppp">
   <div class="col_single bg_flaechen">
      <div class="col_single_center">
         <div class="line ueberschrift text_gross">
         </div>
         <div class="line fliesstext text_normal">Bestellwert</div>
         <div class="line fliesstext text_normal"><?php echo $_SESSION['easycredit_finanzierung']->finanzierung->bestellwert; ?></div>

         <div class="line fliesstext text_normal">Zinsen für Ratenkauf by easyCredit</div>
         <div class="line fliesstext text_normal"><?php echo $_SESSION['easycredit_finanzierung']->ratenplan->zinsen->anfallendeZinsen; ?></div>

         <div class="line fliesstext text_normal">Gesamtsumme</div>
         <div class="line fliesstext text_normal"><?php echo $_SESSION['easycredit_finanzierung']->ratenplan->gesamtsumme; ?></div>
         <div id="tilgungsplan" class="tilgungsplan">
            <div>Tilgungsplan</div>
            <table>
               <tr> 
                  <td style="width:110px;">Bestellwert: </td>
                  <td><?php echo number_format($_SESSION['easycredit_preis'], 2, ',', '.'); ?> €</td>
               </tr>
               <tr>
                  <td style="width:110px;">Zinsen: </td>
                  <td><?php echo number_format($_SESSION['easycredit_finanzierung']->ratenplan->zinsen->anfallendeZinsen, 2, ',', '.'); ?> €</td>
               </tr>
               <tr>
                  <td style="width:110px;">Gesamt: </td>
                  <td><?php echo number_format((float)$_SESSION['easycredit_finanzierung']->ratenplan->gesamtsumme, 2, ',', '.'); ?> €</td>
               </tr>
               <tr>
                  <td style="width:110px;">Laufzeit: </td>
                  <td><?php echo $_SESSION['easycredit_finanzierung']->ratenplan->zahlungsplan->anzahlRaten; ?> Monate</td>
               </tr>
               <tr>
                  <td style="width:110px;"><?php echo number_format((int)$_SESSION['easycredit_finanzierung']->ratenplan->zahlungsplan->anzahlRaten - 1); ?> x </td>
                  <td><?php echo number_format((float)$_SESSION['easycredit_finanzierung']->ratenplan->zahlungsplan->betragRate, 2, ',', '.'); ?> €</td>
               </tr>
               <tr>
                  <td style="width:110px;">letzte Rate </td>
                  <td><?php echo number_format((float)$_SESSION['easycredit_finanzierung']->ratenplan->zahlungsplan->betragLetzteRate, 2, ',', '.'); ?> €</td>
               </tr>
               <tr>
                  <td style="width:110px;">Nominalzins </td>
                  <td><?php echo number_format((float)$_SESSION['easycredit_finanzierung']->ratenplan->zinsen->nominalzins, 2, ',', '.'); ?> %</td>
               </tr>
               <tr>
                  <td style="width:110px;">Effektivzins </td>
                  <td><?php echo number_format((float)$_SESSION['easycredit_finanzierung']->ratenplan->zinsen->effektivzins, 2, ',', '.'); ?> %</td>
               </tr>
            </table>
         </div>
         <br />
         <a target="_blank" href="https://ratenkauf.easycredit.de/ratenkauf/content/intern/vorvertraglicheInformationen.jsf?vorgangskennung=<?php echo $_SESSION['vorgangskennung']; ?>">Vorvertragliche Informationen zum Ratenkauf hier abrufen</a>
         <br />
         <br />
         <div class="bg_button button55 text_gross center">
           <a class="col_button text_gross center" href="<?php echo SHOP_URL_IDX; ?>/easycredit_ok"><?php echo $text->get('button', 'zahlpfl'); ?></a>
         </div>
      </div>
   </div>
</div>
