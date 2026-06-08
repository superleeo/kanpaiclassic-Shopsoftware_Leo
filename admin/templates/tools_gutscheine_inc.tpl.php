<?php
$html  = '<div class="mobile_slide">'.CR;
$html .= '                     <div class="mobile_slide_inner">'.CR;
$html .= '                        <div class="gs_line">'.CR;
$html .= '                           <div class="gs_pos_01 txt_bez"></div>'.CR;
$html .= '                           <div class="gs_pos_2 txt_bez center">Aktionscode</div>'.CR;
$html .= '                           <div class="gs_pos_3 txt_bez center">Zufall</div>'.CR;
$html .= '                           <div class="gs_pos_4 txt_bez center">Gutscheinwert</div>'.CR;
$html .= '                           <div class="gs_pos_5 txt_bez center ellipsis">Mindestbestellwert</div>'.CR;
$html .= '                           <div class="gs_pos_6 txt_bez center">Ablaufdatum</div>'.CR;
$html .= '                           <div class="gs_pos_7 txt_bez">Newsletter</div>'.CR;
$html .= '                           <div class="gs_pos_8 txt_bez">Hinweis</div>'.CR;
$html .= '                           <div class="clear"></div>'.CR;
$html .= '                        </div>'.CR;

$html .= '                        <div class="gs_inner">'.CR;
$html .= '                           <div class="gs_text">'.CR;
$html .= '                              <div>'.CR;
$html .= '                                 Ist kein Datum gesetzt, so gilt der Gutschein dauerhaft.'.CR;
$html .= '                                 <br />Die Newsletter-E-Mails können Sie unter<br>EINSTELLUNGEN / TEXTE individualisieren.'.CR;
$html .= '                              </div>'.CR;
$html .= '                           </div>'.CR;

for ($i = 1; $i <= count($gutscheine); $i++) {
   if ($gutscheine[$i]->datum == '0000-00-00') {
      $gutscheine[$i]->datum = '    -  -  ';
   }

   $opt_list  = '<span class="selectbox30"><select id="gs_'.$i.'_mode" name="gs_'.$i.'_mode">';

   if ((int)$gutscheine[$i]->mode == 1) {
      $opt_list .= '<option value="1" selected="selected">Preis</option>';
      $opt_list .= '<option value="2">%</option>';
   }

   else {
      $opt_list .= '<option value="1">Preis</option>';
      $opt_list .= '<option value="2" selected="selected">%</option>';
   }

   $opt_list .= '</span></select>';
   $html .= '                           <div class="gs_line">'.CR;

   // 4 Gutscheien
   if ($i < 5) {
      $html .= '                              <div class="gs_pos_01 right">Gutschein '.$i.'</div>'.CR;
   }

   // Coupon-Ecke mit zusätzlicher Checkbox
   else {
      $html .= '                              <div class="gs_pos_01 right">'.CR;
      $html .= '                                 <input type="checkbox" class="newdesign" id="show_coupon" name="show_coupon"'.($data->show_coupon == 'y' ? ' checked="checked"' : '').' />'.CR;
      $html .= '                                 <label for="show_coupon">Coupon-Ecke</label>'.CR;
      $html .= '                              </div>'.CR;
   }

   $html .= '                              <div class="gs_pos_2">'.CR;
   $html .= '                                 <input class="code_inp txt_inp" type="text" name="gs_'.$i.'_code" id="gs_'.$i.'_code" value="'.$gutscheine[$i]->code.'" />'.CR;
   $html .= '                              </div>'.CR;
   $html .= '                              <div class="gs_pos_3 center gs_zufall pointer" onclick="Tools.gsZufall(this)"></div>'.CR;
   $html .= '                              <div class="gs_pos_4 ">'.CR;
   $html .= '                                 <span class="gs_pos_4_1"><input type="text" class="txt_inp right" name="gs_'.$i.'_wert" id="gs_'.$i.'_wert" value="'.$gutscheine[$i]->wert.'" /></span>'.CR;
   $html .= '                                 <span class="gs_pos_4_2">'.$opt_list.'</span>'.CR;
   $html .= '                              </div>'.CR;
   $html .= '                              <div class="gs_pos_5">'.CR;
   $html .= '                                 <input class="txt_inp right" type="text" name="gs_'.$i.'_min" id="gs_'.$i.'_min" value="'.number_format($gutscheine[$i]->min, 2, ',', '').'" />'.CR;
   $html .= '                              </div>'.CR;
   $html .= '                              <div class="gs_pos_6">'.CR;
   $html .= '                                 <span class="gs_pos_6_1"><input type="text" class="txt_inp right tag" id="gs_'.$i.'_tag" value="'.trim(substr($gutscheine[$i]->datum, 8,2)).'" onChange="Tools.gsDatum(this)" /></span>'.CR;
   $html .= '                                 <span class="gs_pos_6_2"><input type="text" class="txt_inp right monat" id="gs_'.$i.'_monat" value="'.trim(substr($gutscheine[$i]->datum, 5,2)).'" onChange="Tools.gsDatum(this)" /></span>'.CR;
   $html .= '                                 <span class="gs_pos_6_3"><input type="text" class="txt_inp right jahr"  id="gs_'.$i.'_jahr" value="'.trim(substr($gutscheine[$i]->datum, 0,4)).'" onChange="Tools.gsDatum(this)" /></span>'.CR;
   $html .= '                                 <input type="hidden" class="datum" name="gs_'.$i.'_datum" id="gs_'.$i.'_datum" value="'.$gutscheine[$i]->datum.'" />'.CR;
   $html .= '                              </div>'.CR;
   $html .= '                              <div class="gs_pos_7">'.CR;
   $html .= '                                 <span class="button_ci txt_but" onclick="Tools.gutscheinSend('.$i.')">senden</span>'.CR;
   $html .= '                              </div>'.CR;
   $html .= '                              <div class="gs_pos_8"></div>'.CR;
   $html .= '                              <div class="clear"></div>'.CR;
   $html .= '                           </div>'.CR;
}

$html .= '                        </div>'.CR;
$html .= '                     </div>'.CR;
$html .= '                  </div>'.CR;
