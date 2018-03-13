<h1>Thank You</h1>
<p>Your form was successfully submitted.</p>
<table class="table table-striped table-bordered">
        <?php foreach($query->result() as $row):?>
            <tr>
                <td class="col-md-2">
                                    <img class="img-responsive" style="width: 124px;max-height: 170px;" src="http://localhost/store/public/images/books/big_pics/9781618372383_p0_v2_s600x595.jpg" alt="Good Housekeeping 400 Flat-Tummy Recipes & Tips" title="Good Housekeeping 400 Flat-Tummy Recipes & Tips">
                                </td>
                <td class="col-md-8">
                    Item Number: 5<br>
                    <b><?php echo $row->item_title?></b><br>
                    Item Price: <?php echo $row->item_price?><br>
                    Quantity: <?php echo $row->item_qty?> <br><br>
                    
                    <a href="http://localhost/store/store_basket/remove/85">Remove</a>                </td>
                <td class="col-md-2">$398.00</td>
            </tr>
            <?php endforeach?>
                      
                        <tr style="font-weight: bold;">
                <td colspan="2" style="text-align: right">Grand Total</td>
                <td>$597.00</td>
            </tr>
        </table>
