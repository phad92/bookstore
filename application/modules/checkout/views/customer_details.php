<?php echo Modules::run('checkout/index')?>
<!-- <h1>customer details</h1> -->


        <div class="col-md-8 col-sm-12">
            <h1>create account</h1>
                <?php 
                if($firstname == "Guest"){
                    $firstname = '';
                }
                if($lastname == "Account"){
                    $lastname = '';
                }
                $form_location = base_url().'checkout/submit_billing_info';

                ?>
                <form class="form-horizontal" action="<?php echo $form_location?>" method="post">
                <?php echo validation_errors('<p style="color: red;">',"</p>");?>
                <fieldset>

                <!-- Form Name -->
                <legend>Please Enter Your Details below</legend>

                
                <!-- Text input-->
                <div class="form-group">
                    <div class="col-md-6">
                        <label class="control-label" for="firstname">First Name</label>  
                        <input id="firstname" name="firstname" value="<?php echo $firstname?>" type="text" placeholder="First Name" class="form-control input-md">
                        
                    </div>
                    <div class="col-md-6">
                        <label class="control-label" for="lastname">Last Name</label>  
                        <input id="lastname" name="lastname" value="<?php echo $lastname?>" type="text" placeholder="Last Name" class="form-control input-md">
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-md-12">
                        <label class="control-label" for="email">Email</label>  
                        <input id="email" name="email" type="email" value="<?php echo $email?>" placeholder="Email" class="form-control input-md">
                    </div>
                </div>

                <!-- Text input-->
                <div class="form-group">
                    <div class="col-md-9">
                        <label class="control-label" for="country">Country</label>  
                        <input id="country" name="country" type="text" value="<?php echo $country?>" placeholder="Country" class="form-control input-md">
                    </div>
                    <div class="col-md-3">
                        <label class="control-label" for="postcode">Postal Code</label>  
                        <input id="postcode" name="postcode" type="text" value="<?php echo $postcode?>" placeholder="Postal Code" class="form-control input-md">
                    </div>
                </div>

                <!-- Text input-->
                <div class="form-group">
                    <div class="col-md-12">
                        <label class="control-label" for="town">City</label>  
                        <input id="town" name="town" type="text" value="<?php echo $town?>" placeholder="City" class="form-control input-md">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-6">
                        <label class="control-label" for="address1">Address 1</label>  
                        <input id="address1" name="address1" type="text" value="<?php echo $address1?>" placeholder="Address 1" class="form-control input-md">
                    </div>
                    <div class="col-md-6">
                        <label class="control-label" for="address2">Address 2</label>  
                        <input id="address2" name="address2" type="text" value="<?php echo $address2?>" placeholder="Address 2" class="form-control input-md">
                    </div>
                </div>

                <!-- Button -->
                <div class="form-group">
                
                <div class="col-md-12">
                    <input type="hidden" name="checkout_token" value="<?php //echo $checkout_token?>">
                    <button id="create" name="submit"  value="Submit" class="btn btn-primary pull-right">Proceed To Payment Details <i class="fa fa-icon-chevron-right"></i></button>
                </div>
                </div>
                </fieldset>
                
                </form>

        </div>
    </div>



</div>
</div>

<script>
    <?php $url_seg = $this->uri->segment(2);
          if($url_seg == "customer_details"):
    ?>
    // $.post('<?php echo base_url("store_accounts/check_card_data/")?>',function(resp){
    //     if(resp.status == true){
    //         console.log(resp);
    //         $('#confirmation').click(function(e){
               
    //             $('#confirmation').addClass("disable-me");
    //             // return false;
    //         });

    //     }
    // })
    <?php endif?>
</script>