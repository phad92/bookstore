<style>
    .borderless td, .borderless th {
    border: none !important;
    }
    .checkout-container{
        font-size: 1.1em;
        font-weight: bold;
    }

    .font-style{
    } 
</style>
<div style="padding: 20px;margin-top: 24px; border: 1px solid silver;">
    <?php echo form_open('store_basket/add_to_basket');
    ?>
    <table class="table borderless checkout-container">
        <h4 class="text-center"><strong>Buy This Book</strong> </h4>
        <tr>
            <td class="">Format</td>
            <td><?php echo ucfirst($book_format)?></td>
        </tr>
        <tr>
            <td class="">Price</td>
            <td><?php echo $currency_symbol.$book_price?> <sup style="font-size: 12px;text-decoration: line-through; font-weight: bold; color: red;"><?php (isset($was_price) ? print $currency_symbol.$was_price : null)?></sup></td>
        </tr>
        <!-- <tr>
            <td>Qty</td>
            <td>
                <div class="col-sm-6">
                    <input name="item_qty" type="text"  class="form-control">
                </div>
            </td>
        </tr> -->
        <tr>
            <td colspan="2" style="text-align: center;">
                <button name="submit" type="submit" value="Submit" class="btn no-border-radius btn-block btn-primary">Add To Basket</button>
                <button name="submit" type="submit" style="background-color: black;" value="Buy_book" class="btn no-border-radius btn-block btn-primary"></span> Book Now</button>
                </td>
            </tr>
        </table>
    <?php 
    echo form_hidden('item_id',$book_id);
    echo form_close();
    ?>
</div>