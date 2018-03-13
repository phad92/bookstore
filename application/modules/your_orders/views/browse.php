<h1>Your Orders</h1> 
<!-- <table class="table table-striped table-bordered bootstrap-datatable datatable">
						  <thead>
							  <tr style="background: #666;color: #fff"> -->
<?php if($num_rows < 1):?>
<p>You Have Not Place Any Orders So Far</p>
<?php else: ?>

	<table class="table table-striped table-bordered bootstrap-datatable datatable">
	  <thead>
		  <tr style="background: #666;color: #fff">
			  <th>Order Reference</th>
			  <th>Order Value</th>
			  <th>Date Created</th>
			  <th>Order Status</th>
			  <th>Actions</th>
		  </tr>
	  </thead>   
	  <tbody>
      <?php 
          $this->load->module('store_orders');
          $this->load->module('timedate');
          foreach($query->result() as $row): 
            $ref = $row->order_ref;
            $view_order_url = base_url('Your_orders/view/'.$ref);
            $date_created = $this->timedate->get_nice_date($row->date_created,'cool');
            $order_status = $row->order_status;
            $order_status_title = $order_status_options[$order_status];
          ?>
		<tr>
			<td class="center"><?php echo $ref;?></td>
			<td class="center"><?php echo '$'.number_format($row->amount_paid,2);?></td>
			<td class="center"><?php echo $date_created;?></td>
			<td class="center"><?php echo $order_status_title;?></td>
			<td class="center">
				<a class="btn btn-default" href="<?php echo $view_order_url;?>">
                    <!-- <i class="glyphicon glyphicon-edit"></i> -->
                    view
                </a>

			</td>
			
		</tr>
          <?php endforeach;?>
	  </tbody>
	</table>  
<?php endif;?>