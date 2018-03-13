
<div class="row-fluid">
    <div class="span12 statbox blue" style="height: auto !important;background-color: #c0c0c0 !important;color: black;border:1px solid #333 !important;">
        <div class="span4">
            <img src="<?php echo base_url('public/images/icons/paypal-logo.png')?>" alt="">
        </div>
        <div class="span8">
            <h2>FEEDBACK FROM PAYPAL</h2>
            <p>
                <strong>Transmission Time: </strong><?php echo $date_created?></br>
                <strong>Payment Status: </strong><?php echo $payment_status?></br>
                <strong>Transaction ID: </strong><?php echo $txn_id?></br>
                <strong>Payment Gross: </strong><?php echo $mc_gross?></br>
                <strong>Payer ID: </strong><?php echo $payer_id?></br>
                <strong>Payer Email: </strong><?php echo $payer_email?></br>
                <strong>Payer Status: </strong><?php echo $payer_status?></br>
                <strong>Payment Date: </strong><?php echo $payment_date?></br>

                <!-- payer's details -->
                <strong>Payer's Name: </strong><?php echo $first_name.' '.$last_name?></br>
                <strong>Payer's Company: </strong><?php echo $address_name?></br>
                <strong>Address Line 1: </strong><?php echo $address_street?></br>
                <strong>City: </strong><?php echo $address_city?></br>
                <strong>State: </strong><?php echo $address_state?></br>
                <strong>PostCode/ Zip: </strong><?php echo $address_zip?></br>
                <strong>Country: </strong><?php echo $address_country?></br>


            </p>
        </div>
        <div class="footer" style="background-color: #999 !important;">
            <a href="http://www.paypal.com"> More Info</a>
        </div>
    </div>
</div>