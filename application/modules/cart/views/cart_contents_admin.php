<div class="row-fluid sortable">
	<div class="box span12">
		<div class="box-header" data-original-title>
			<h2><i class="halflings-icon white edit"></i><span class="break"></span>Customers Cart</h2>
			<div class="box-icon">
				<a href="#" class="btn-setting"><i class="halflings-icon white wrench"></i></a>
				<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
				<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
			</div>
		</div>
		<div class="box-content">
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
                    Quantity: <?php echo $row->item_qty?> <br><br>
                    
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
	</div><!--/span-->
</div><!--/row-->