<?php if($num_rows > 0):?>
<li>
	<a class="dropmenu" href="#"><i class="icon-folder-close-alt"></i><span class="hidden-tablet"> Manage Orders</span></a>
	<ul>
        <li>
            <a class="submenu" href="<?php echo base_url('store_orders/browse/status0')?>">
                <i class="icon-file-alt"></i><span class="hidden-tablet"> <?php echo "Order Submitted";?></span>
            </a>
        </li>
        <?php 
            
          foreach($sqlquery->result() as $row):
            $target_url = base_url('store_orders/browse/status'.$row->id);
            ?>
		    <li>
                <a class="submenu" href="<?php echo $target_url?>">
                    <i class="icon-file-alt"></i><span class="hidden-tablet"> <?php echo $row->status_title;?></span>
                </a>
            </li>
        <?php endforeach?>
	</ul>	
</li>
<?php endif?>