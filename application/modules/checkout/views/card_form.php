 
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">
        //set your publishable key
        Stripe.setPublishableKey('pk_test_i7R3jZBShy66ZwPka3X2xCko');
        // Stripe.setPublishableKey('YOUR_PUBLISHABLE_KEY');
        
        //callback to handle the response from stripe
        function stripeResponseHandler(status, response) {
            if (response.error) {
                //enable the submit button
                $('#payBtn').removeAttr("disabled");
                //display the errors on the form
                // $('#payment-errors').attr('hidden', 'false');
                $('#payment-errors').addClass('alert alert-danger');
                $("#payment-errors").html(response.error.message);
            } else {
                var form$ = $("#paymentFrm");
                //get token id
                var token = response['id'];
                //insert the token into the form
                form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
                //submit form to the server
                form$.get(0).submit();
            }
        }
        $(document).ready(function() {
            //on form submit
            $("#paymentFrm").submit(function(event) {
                //disable the submit button to prevent repeated clicks
                $('#payBtn').attr("disabled", "disabled");
                
                //create single-use token to charge the user
                Stripe.createToken({
                    number: $('#card_num').val(),
                    cvc: $('#card-cvc').val(),
                    exp_month: $('#card-expiry-month').val(),
                    exp_year: $('#card-expiry-year').val()
                }, stripeResponseHandler);
                
                //submit from callback
                return false;
            });
        });
    </script>

<div class="col-md-8 col-sm-12">
                <?php 
                    
                $form_location = base_url().'checkout/submit_card_data';

                ?>
                <div id="payment-errors"></div>
                <form class="form-horizontal" id="paymentFrm" enctype="multipart/form-data" action="<?php echo $form_location?>" method="post">
                <?php echo validation_errors('<p style="color: red;">',"</p>");?>
                <fieldset>
                <!-- Text input-->
                <!-- <div class="form-group">
                    <div class="col-md-12">
                        <label class="control-label" for="email">Email</label>  
                        <input type="email" name="email" class="form-control" placeholder="email@you.com" value="<?php //echo $email; ?>" required />
                    </div>
                </div> -->
                <div class="form-group">
                    <div class="col-md-12">
                        <label class="control-label" for="name">Card Owner</label>  
                        <input type="text" name="name" class="form-control" placeholder="Name" value="<?php echo $name; ?>" required>       
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <label class="control-label" for="card_num">Card Number</label>  
                     <input type="number" name="card_num" id="card_num" class="form-control" placeholder="Card Number" autocomplete="off" value="<?php echo $card_num; ?>" required>
                        
                    </div>
                </div>

                <!-- Text input-->
                <div class="form-group">
                    <div class="col-md-4">
                        <label class="control-label" for="card_exp_month">Month</label>  
                        <input type="text" name="card_exp_month" maxlength="2" class="form-control" id="card-expiry-month" placeholder="MM" value="<?php echo $card_exp_month; ?>" required>
                                        
                    </div>
                    <div class="col-md-4">
                        <label class="control-label" for="card_exp_year">Year</label>  
                        <input type="text" name="card_exp_year" class="form-control" maxlength="4" id="card-expiry-year" placeholder="YYYY" required="" value="<?php echo $card_exp_year; ?>">
                    </div>
                    <div class="col-md-4">
                        <label class="control-label" for="card_cvc">CVC</label>  
                        <input type="text" name="card_cvc" id="card-cvc" maxlength="3" class="form-control" autocomplete="off" placeholder="CVC" value="<?php echo $card_cvc; ?>" required>
                    </div>
                </div>


                <!-- Button -->
                <div class="form-group">
                
                     <!-- <div class="form-group text-right">
                          <button class="btn btn-secondary" type="reset">Reset</button>
                          <button type="submit" id="payBtn" class="btn btn-success">Submit Payment</button>
                        </div> -->
                 <div class="col-md-12">
                    <input type="hidden" name="checkout_token" value="<?php //echo $checkout_token?>">
                    <!-- <input type="hidden" name="stripe_id" value="<?php //if(is_numeric($id)){echo $id;} ?>"> -->
                    <button id="payBtn" type="submit"  class="btn btn-primary pull-right">Review Order<i class="fa fa-icon-chevron-right"></i></button>
                </div> 
                </div>
                </fieldset>
                
                </form>

        </div>
    </div>
    </div>
</style>
<script>
    
 
</script>