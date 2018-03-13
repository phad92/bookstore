<?php $first_bit = $this->uri->segment(1);?>
<div class="row">
    <div class="col-md-10 col-md-offset-1" style="margin-top: 36px;">
        <table class="table table-striped table-bordered">
            <?php 
            $grand_total = 0;
            $grand_total_desc = 0;
            foreach($query->result() as $row):
                $sub_total = $row->item_price * $row->item_qty;
                $sub_total_desc = number_format($sub_total,2);
                $grand_total = $grand_total + $sub_total;
                $grand_total_desc = number_format($grand_total,2);
                ?>
            <tr>
                <td class="col-md-2">
                <?php if($row->small_pic != ''):?>
                    <img class="img-responsive" style="width: 124px;max-height: 170px;" src="<?php echo base_url('public/images/books/big_pics/'.$row->small_pic);?>" alt="<?php echo $row->item_title?>" title="<?php echo $row->item_title;?>">
                <?php else:?>
                    <p>No image preview available</p>
                <?php endif?>
                </td>
                <td class="col-md-8">
                    Item Number: <?php echo $row->item_id?><br>
                    <b><?php echo $row->item_title;?></b><br>
                    Item Price: <?php echo $currency_symbol.$row->item_price;?><br>
                    <?php if(!empty($row->format)){echo "Format ".$row->format."<br>";} ?>
                    Quantity: <?php echo $row->item_qty?> <br><br>
                    
                    <?php if($first_bit != "your_orders" || $first_bit != "Your_orders"){echo anchor('store_basket/remove/'.$row->id,'Remove');}?>
                </td>
                <td class="col-md-2"><?php echo $currency_symbol.$sub_total_desc;?></td>
            </tr>
            <?php endforeach?>
            <tr style="font-weight: bold;">
                <td colspan="2" style="text-align: right">Grand Total</td>
                <td><?php echo $currency_symbol.$grand_total_desc;?></td>
            </tr>
        </table>
    </div>
</div>