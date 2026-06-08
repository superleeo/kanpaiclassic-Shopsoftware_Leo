<?php
$html .= '';
$html .= '<div id="popup_bestellung_status">'.CR;
$html .= '   <h1>Status korrigieren</h1>'.CR;
$html .= '   <div class="subtitle">(ohne E-Mailversand)</div>'.CR;
$html .= '   <div class="status_line">'.CR;
$html .= '      <input type="radio" class="newdesign" id="status1" name="statusx" value="1"'.($data->status == 1 ? ' checked="checked"' : '').' />'.CR;
$html .= '      <label for="status1"><span class="best_status best_neu">neu</span></label><span class="ellipsis">neu</span>'.CR;
$html .= '   </div>'.CR;

$html .= '   <div class="status_line">'.CR;
$html .= '      <input type="radio" class="newdesign" id="status2" name="statusx" value="2"'.($data->status == 2 ? ' checked="checked"' : '').' />'.CR;
$html .= '      <label for="status2"><span class="best_status best_offen">bestätigt</span></label><span class="ellipsis">auf Zahlung wartend</span>'.CR;
$html .= '   </div>'.CR;

$html .= '   <div class="status_line">'.CR;
$html .= '      <input type="radio" class="newdesign" id="status3" name="statusx" value="3"'.($data->status == 3 ? ' checked="checked"' : '').' />'.CR;
$html .= '      <label for="status3"><span class="best_status best_bereit">bereit</span></label><span class="ellipsis">versandbereit</span>'.CR;
$html .= '   </div>'.CR;

$html .= '   <div class="status_line">'.CR;
$html .= '      <input type="radio" class="newdesign" id="status4" name="statusx" value="4"'.($data->status == 4 ? ' checked="checked"' : '').' />'.CR;
$html .= '      <label for="status4"><span class="best_status best_erledigt">versendet</span></label><span class="ellipsis">erledigt</span>'.CR;
$html .= '   </div>'.CR;

$html .= '   <div class="status_line">'.CR;
$html .= '      <input type="radio" class="newdesign" id="status5" name="statusx" value="5"'.($data->status == 5 ? ' checked="checked"' : '').' />'.CR;
$html .= '      <label for="status5"><span class="best_status best_erledigt">storniert</span></label><span class="ellipsis">erledigt</span>'.CR;
$html .= '   </div>'.CR;

if ($data->status == 0) {
   $html .= '   <div class="status_line">'.CR;
   $html .= '      <input type="radio" class="newdesign" id="status6" name="statusx" value="0"'.($data->status == 0 ? ' checked="checked"' : '').' />'.CR;
   $html .= '      <label for="status6"><span class="best_status best_neu inline">Einlesen</span></label><span class="ellipsis">Amazon: nicht vollständig eingelesen</span>'.CR;
   $html .= '   </div>'.CR;
}

if ($data->status < 0 || $data->status > 5) {
   $html .= '   <div class="status_line">'.CR;
   $html .= '      <input type="radio" class="newdesign" id="status7" name="statusx" value="-1"'.($data->status < 0 || $data->status > 5 ? ' checked="checked"' : '').' />'.CR;
   $html .= '      <label for="status7"><span class="best_status best_neu inline">Pending</span></label><span class="ellipsis">Amazon: Daten noch nicht verfügbar</span>'.CR;
   $html .= '   </div>';
}

$html .= '   <div class="button_zeile">';
$html .= '      <div class="button_left button" onclick="Multibox.close();">abbrechen</div>';
$html .= '      <div class="button_left button_ci" onclick="$(\'#select_status\').val($(\'input[name=statusx]:checked\', \'#popup_bestellung_status\').val()); Bestellungen.save();">speichern</div>';
$html .= '   </div>';
$html .= '</div>';

//$(\'#popup_bestellung_status input[name=statusx]:checked\')
/*
                           <div class="line">
                              <span class="selectbox30">
                                 <select name="status" id="status">
                                    <option value="1" <?php echo $data->status == 1 ? ' selected="selected"' : ''; ?>>neu</option>
                                    <option value="2" <?php echo $data->status == 2 ? ' selected="selected"' : ''; ?>>offen</option>
                                    <option value="3" <?php echo $data->status == 3 ? ' selected="selected"' : ''; ?>>versandbereit</option>
                                    <option value="4" <?php echo $data->status == 4 ? ' selected="selected"' : ''; ?>>erledigt</option>
                                    <option value="5" <?php echo $data->status == 5 ? ' selected="selected"' : ''; ?>>storniert</option>
                                 </select>
                              </span>
                           </div>
*/
