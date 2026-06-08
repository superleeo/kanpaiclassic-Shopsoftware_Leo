<?php
if (isset($pf['footer_dhl'])          && $pf['footer_dhl'] == 'y')          { $html .= '<span class="footer_icons '.$footer_farbe.' footer_dhl"><a href="'.SHOP_URL_IDX.'/versand/" target="_blank"></a></span>'; }
if (isset($pf['footer_dpd'])          && $pf['footer_dpd'] == 'y')          { $html .= '<span class="footer_icons '.$footer_farbe.' footer_dpd"><a href="'.SHOP_URL_IDX.'/versand/" target="_blank"></a></span>'; }
if (isset($pf['footer_hermes'])       && $pf['footer_hermes'] == 'y')       { $html .= '<span class="footer_icons '.$footer_farbe.' footer_hermes"><a href="'.SHOP_URL_IDX.'/versand/" target="_blank"></a></span>'; }
if (isset($pf['footer_gls'])          && $pf['footer_gls'] == 'y')          { $html .= '<span class="footer_icons '.$footer_farbe.' footer_gls"><a href="'.SHOP_URL_IDX.'/versand/" target="_blank"></a></span>'; }
if (isset($pf['footer_ups'])          && $pf['footer_ups'] == 'y')          { $html .= '<span class="footer_icons '.$footer_farbe.' footer_ups"><a href="'.SHOP_URL_IDX.'/versand/" target="_blank"></a></span>'; }
if (isset($pf['footer_post'])         && $pf['footer_post'] == 'y')         { $html .= '<span class="footer_icons '.$footer_farbe.' footer_post"><a href="'.SHOP_URL_IDX.'/versand/" target="_blank"></a></span>'; }

if (isset($pf['footer_ssl'])          && $pf['footer_ssl'] == 'y')          { $html .= '<span class="footer_icons '.$footer_farbe.' footer_ssl"><a href="'.SHOP_URL_IDX.'/versand/" target="_blank"></a></span>'; }

if (isset($pf['footer_bar'])          && $pf['footer_bar'] == 'y')          { $html .= '<span class="footer_icons '.$footer_farbe.' footer_bar"><a href="'.SHOP_URL_IDX.'/versand/" target="_blank"></a></span>'; }
if (isset($pf['footer_ueberweisung']) && $pf['footer_ueberweisung'] == 'y') { $html .= '<span class="footer_icons '.$footer_farbe.' footer_ueberweisung"><a href="'.SHOP_URL_IDX.'/versand/" target="_blank"></a></span>'; }
if (isset($pf['footer_rechnung'])     && $pf['footer_rechnung'] == 'y')     { $html .= '<span class="footer_icons '.$footer_farbe.' footer_rechnung"><a href="'.SHOP_URL_IDX.'/versand/" target="_blank"></a></span>'; }
if (isset($pf['footer_nachnahme'])    && $pf['footer_nachnahme'] == 'y')    { $html .= '<span class="footer_icons '.$footer_farbe.' footer_nachnahme"><a href="'.SHOP_URL_IDX.'/versand/" target="_blank"></a></span>'; }
if (isset($pf['footer_paypal'])       && $pf['footer_paypal'] == 'y')       { $html .= '<span class="footer_icons '.$footer_farbe.' footer_paypal"><a href="'.SHOP_URL_IDX.'/versand/" target="_blank"></a></span>'; }
if (isset($pf['footer_paypalplus'])   && $pf['footer_paypalplus'] == 'y')   { $html .= '<span class="footer_icons '.$footer_farbe.' footer_paypalplus"><a href="'.SHOP_URL_IDX.'/versand/" target="_blank"></a></span>'; }
if (isset($pf['footer_visa'])         && $pf['footer_visa'] == 'y')         { $html .= '<span class="footer_icons2 '.$footer_farbe.' footer_visa"><a href="'.SHOP_URL_IDX.'/versand/" target="_blank"></a></span>'; }
if (isset($pf['footer_sofort'])       && $pf['footer_sofort'] == 'y')       { $html .= '<span class="footer_icons '.$footer_farbe.' footer_sofort"><a href="'.SHOP_URL_IDX.'/versand/" target="_blank"></a></span>'; }
if (isset($pf['footer_klarna'])       && $pf['footer_klarna'] == 'y')       { $html .= '<span class="footer_icons '.$footer_farbe.' footer_klarna"><a href="'.SHOP_URL_IDX.'/versand/" target="_blank"></a></span>'; }
if (isset($pf['footer_amazon'])       && $pf['footer_amazon'] == 'y')       { $html .= '<span class="footer_icons '.$footer_farbe.' footer_amazon"><a href="'.SHOP_URL_IDX.'/versand/" target="_blank"></a></span>'; }

if (isset($pf['footer_easycredit'])   && $pf['footer_easycredit'] == 'y')   { $html .= '<span class="footer_icons2 '.$footer_farbe.' footer_easycredit"><a href="'.SHOP_URL_IDX.'/versand/" target="_blank"></a></span>'; }
// Alternative easycredit
//if (isset($pf['footer_ratenkauf'])    && $pf['footer_ratenkauf'] == 'y')    { $html .= '<span class="footer_icons2 '.$footer_farbe.' footer_ratenkauf"><a href="'.SHOP_URL_IDX.'/versand/" target="_blank"></a></span>'; }
if (isset($pf['footer_paydirekt'])    && $pf['footer_paydirekt'] == 'y')    { $html .= '<span class="footer_icons2 '.$footer_farbe.' footer_paydirekt"><a href="'.SHOP_URL_IDX.'/versand/" target="_blank"></a></span>'; }
if (isset($pf['footer_postfinance'])  && $pf['footer_postfinance'] == 'y')  { $html .= '<span class="footer_icons2 '.$footer_farbe.' footer_postfinance"><a href="'.SHOP_URL_IDX.'/versand/" target="_blank"></a></span>'; }
if (isset($pf['footer_twint'])        && $pf['footer_twint'] == 'y')        { $html .= '<span class="footer_icons2 '.$footer_farbe.' footer_twint"><a href="'.SHOP_URL_IDX.'/versand/" target="_blank"></a></span>'; }
if (isset($pf['footer_wir'])          && $pf['footer_wir'] == 'y')          { $html .= '<span class="footer_icons2 '.$footer_farbe.' footer_wir"><a href="'.SHOP_URL_IDX.'/versand/" target="_blank"></a></span>'; }
//if (isset($pf['footer_swisspay'])     && $pf['footer_swisspay'] == 'y')     { $html .= '<span class="footer_icons2 '.$footer_farbe.' footer_swisspay"><a href="'.SHOP_URL_IDX.'/versand/" target="_blank"></a></span>'; }

$html .= '<div class="clear"></div>';
