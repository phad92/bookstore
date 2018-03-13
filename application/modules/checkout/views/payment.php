<?php echo Modules::run('checkout/index')?>
<h1>Which Payment Method Do You Prefer?</h1>
<div class="col-md-8 col-sm-12">
    <div class="row">
        <div>
        <h3>Pay with Paypal</h3>
         <?php echo Modules::run('checkout/_paypal_payment');?>
        
        </div>
    </div>
    <div class="row">
        <div>
        <h3>Or Enter your credit card details</h3>
        <?php echo Modules::run('checkout/_stripe_payment');?>
    </div>

    <!-- <a href="" class="btn btn-primary"><img src=""></a> -->
</div>
</div>
</div> 