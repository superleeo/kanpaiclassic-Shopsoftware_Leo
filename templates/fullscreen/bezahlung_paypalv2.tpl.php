<div class="col_single">
   <div class="col_single_center">
      <div class="col_single ueberschrift text_gross center">
         <?php echo $text->get('bezahlung_18', 'subtitel'); ?>
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
            <?php
               //get paypalv2 client id based on sandbox or live
               $ppv2 = KANPAICLASSIC\Control::getPaypalv2();
               $clientID = $ppv2->getClientID();
            ?>
            <script src="https://www.paypal.com/sdk/js?client-id=<?php echo $clientID?>&currency=EUR&disable-funding=credit,card"> 
            </script>
            <div id="paypalv2_spinner" style="display:none; text-align:center; padding: 15px 0 15px 0"><h2>Loading...</h2> <span class="fas fa-spinner fa-spin fa-2x"></span></div>
            <div id="paypal-button-container"></div>
            <script>
               function showSpinner(){
                  document.getElementById('paypalv2_spinner').style.display = 'block';
               }
               function hideSpinner(){
                  document.getElementById('paypalv2_spinner').style.display = 'none';
               }

               paypal.Buttons({
                  createOrder: function(data, actions) {
                     //show loading spinner
                     showSpinner();
                     //get order JSON string from server
                     return fetch(shop_url_idx+"/ajax/bestellt")
                        .then(response => response.json())
                        .then(data => {
                              if (data.status === 'ok'){
                                 return actions.order.create(JSON.parse(data.html))
                              }else{
                                 return false;  
                              }
                           }  
                        );
                  },
                  onApprove: function(data, actions) {
                     
                     return actions.order.capture().then(function(details) {
                        // if is complete, redirect to paypal_ok
                        if (details.status === 'COMPLETED'){
                           const updateRechnung = new FormData();
                           updateRechnung.append('data', JSON.stringify(details));
                           fetch(shop_url_idx+"/ajax/paypalv2_notify", {
                                 method: 'POST',
                                 body: updateRechnung
                              })
                              .then(response => response.json())
                              .then(data => {
                                    //redirect
                                    document.location.replace("<?php echo SHOP_URL_IDX.'/paypal_ok'?>");
                                 }  
                           );
                        }
                        
                     });
                  },
                  onError : function (err){
                     //redirect to paypal_error 
                     document.location.replace("<?php echo SHOP_URL_IDX.'/paypal_error'?>");
                     console.log('error' +err);
                  },
                  onCancel: function (data) {
                     hideSpinner();
                     console.log('canceled');
                  }
               }).render('#paypal-button-container');
               
               
            </script>
         
      </div>
   </div>
</div>
