
<?php 
    echo form_open($form_location);
    echo form_hidden('upload','1');
    echo form_hidden('cmd','_cart');
    echo form_hidden('business',$paypal_email);
    echo form_hidden('currency_code',$currency_code);
    echo form_hidden('custom',$custom);
    echo form_hidden('return',$return);
    echo form_hidden('cancel_return',$cancel_return);

    $count = 0;
    foreach($query->result() as $row){
        $count++;
        $item_title = $row->item_title;
        $item_price = $row->item_price;
        $item_qty = $row->item_qty;
        // $item_size = $row->item_size;
        // $item_color = $row->item_color;

        $item_price = 0.01;

        echo form_hidden('item_name_'.$count, $item_title);
        echo form_hidden('amount_'.$count, $item_price);
        echo form_hidden('item_qty_'.$count, $item_qty);

        // if($item_color != ''){
        //     echo form_hidden('on0_'.$count, 'color');
        //     echo form_hidden('os0_'.$count, $item_color);
        //     // adding shipping on every item
        //     // echo form_hidden('shipping_'.$count,$shipping);
        // }
        // if($item_size != ''){
        //     echo form_hidden('on1_'.$count, 'size');
        //     echo form_hidden('os1_'.$count, $item_size);
        // }
    }
    // echo form_hidden('shipping_'.$count,$shipping);
    // echo form_submit('submit','Submit');
?>

<div class="row">
    <div class="col-md-10" style="text-align: left">
        <!-- <a style="cursor:pointer" onclick="window.open('<?php //echo base_url('checkout/customer_details')?>','',' scrollbars=yes,menubar=no,width=500, resizable=yes,toolbar=no,location=no,status=no')">Your text</a> -->
    <button class="btn btn-default paypal-btn" name="submit" value="Submit" type="submit">
        <img src="https://www.paypalobjects.com/webstatic/en_US/i/buttons/PP_logo_h_150x38.png" alt="PayPal Logo">
        <sub>checkout</sub>
        <!-- <span class="glyphicon glyphicon-shopping-cart"></span>
        Checkout -->
    </button>
    
    </div>
</div>
<?php
    echo form_close();
    // if($on_test_mode == TRUE):
?>
    <!-- <div style="clear: both; text-align: center; margin-top: 20px;">
        <?php //echo form_open('Paypal/submit_test');
              //echo "<p>Enter number of orders you'd like to submit</p>";
              //echo form_input('num_orders');
              //echo form_submit('submit','Submit');
              //echo form_hidden('custom',$custom);
              //echo form_close();
        ?>
         
    </div> -->
<?php //endif?>