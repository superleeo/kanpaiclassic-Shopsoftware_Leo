<div class="col_single">
   <div class="col_single_center">
      <div class="col_single ueberschrift text_gross center">
         <?php echo $text->get('bezahlung_19', 'subtitel'); ?>
      </div>

      <div class="col_single">
            <div class="line">&nbsp;</div>

            <div class="line">
               <div class="line_left ueberschrift text_max"><?php echo $text->get('bezahlung', 'gesamt'); ?></div>
               <div class="line_center"></div>

               <?php if ($params->firma['price_login'] == 'y' && $params->user_id == 0) { ?>
               <div class="line_right fliesstext text_normal"><img src="<?php echo TEMPLATE_URL . '/images/system/btn_preis_nl_' . $params->selected_lang . '.jpg'; ?>" /></div>
               <?php } else { ?>
               <div class="line_right ueberschrift text_max"><?php echo KANPAICLASSIC\Helper::number_format($gesamt_show, 2, ',', '.'). ' '.$params->waehrung; ?></div>
               <?php } ?>
            </div>

            <div class="line">
               <div class="line_left fliesstext text_gross"><?php echo $text->get('adresse', 'bestnr'); ?></div>
               <div class="line_center"></div>
               <div class="line_right fliesstext text_gross"><?php echo $_SESSION['bestellnummer']; ?></div>
            </div>

            <div class="line">&nbsp;</div>
            </script>
            <div id="mollie_spinner" style="display:none; text-align:center; padding: 15px 0 15px 0"><h2>Loading...</h2> <span class="fas fa-spinner fa-spin fa-2x"></span></div>
            <div id="button-container" style="width:100%; text-align:center">
                  <button id="btn_mollie" onClick="sendMollie()" style="background-color:black; color:white; padding:10px;">Mollie</button>
            </div>
            <script>
               function showSpinner(){
                  document.getElementById('mollie_spinner').style.display = 'block';
               }
               function hideSpinner(){
                  document.getElementById('mollie_spinner').style.display = 'none';
               }
               function sendMollie(){
                  return fetch(shop_url_idx+"/ajax/bestellt")
                        .then(response => response.json())
                        .then(data => {
                              if (data.html){
                                 var response = JSON.parse(data.html);
                                 if (response['_links']){
                                    document.location.replace(response['_links']['checkout']['href']);
                                 }
                              }else{
                                 console.log('error');
                              }
                           }  
                        );
               }               
               
            </script>
         
      </div>
   </div>
</div>

