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

$html = '';
for ($i = 0; $i  < (is_array($z_data) ? count($z_data) : 0); $i++) {
   $html .= '<div class="zubehoer_wrapper">';
   $html .= '   <div class="zubehoer_line'.($z_data[$i]->online != 'y' ? ' deaktiviert" title="Artikel deaktiviert"' : '"').'>';
   $html .= '      <span class="sort fas fa-arrows-alt-v"></span>';
   $html .= '      <span class="zubehoer_name">'.$z_data[$i]->art_name.'</span>';
   $html .= '      <span class="zubehoer_pos"> ('.$z_data[$i]->parent.($z_data[$i]->childs > 1 ? '-'.$z_data[$i]->sort : '').') '.number_format($z_data[$i]->netto, 2, ',', '.').' '.$this->params->waehrung.'</span>';
   $html .= '      <input type="hidden" class="zdb_id" value="'.$z_data[$i]->db_id.'" />';
   $html .= '      <input type="hidden" class="zzubehoer_id" value="'.$z_data[$i]->id.'" />';
   $html .= '   </div>';
   $html .= '   <a class="edit pointer fas fa-pencil-alt" href="'.ADMIN_URL_IDX.'/artikel/detail/'.$z_data[$i]->parent.'" target="_blank"></a>';
   $html .= '   <span class="delete pointer far fa-trash-alt" onclick="Zubehoer.delete(this, '.$z_data[$i]->db_id.')"></span>';
   $html .= '</div>';
}
