
<?php if(isset($flash)) print $flash;

?>
<div>
    <span style="margin-button: 30px;"><h1>Manage Orders</h1></span>
	<h2><?php echo $current_order_status_title?></h2>
	
    <a href="http://www.paypal.com" class="btn btn-primary">Visit Paypal</a>
</div>
<div style="margin-top: 30px">
    <div class="row-fluid sortable">		
				<div class="box span12">
					<div class="box-header" data-original-title>
						<h2><i class="halflings-icon white tag"></i><span class="break"></span>Your Orders</h2>
						<div class="box-icon">
							<a href="#" class="btn-setting"><i class="halflings-icon white wrench"></i></a>
							<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
							<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
						</div>
					</div>
					<div class="box-content">
						<table class="table table-striped table-bordered bootstrap-datatable datatable">
						  <thead>
							  <tr>
								  <th>Order Reference</th>
								  <th>Order Value</th>
								  <th>Date Created</th>
								  <th>Customer Name</th>
								  <th>Order Status</th>
								  <th>Opened</th>
								  <th>Actions</th>
							  </tr>
						  </thead>   
						  <tbody>
                        <?php 
                            $this->load->module('store_orders');
                            $this->load->module('timedate');
                            foreach($query->result() as $row): 
                            $view_order_url = base_url('store_orders/view/'.$row->id);
                            $opened = $row->opened;
                            $ref = $row->order_ref;
                            $date_created = $this->timedate->get_nice_date($row->date_created,'full');
                            
                            if($opened == 1){
                                $status_label = "success";
                                $status_desc = "Opened";
                            }else{
                                $status_label = "important";
                                $status_desc = "Unopened";
                            }
                            $customer_name = $this->store_orders->get_customer_name($row->firstname,$row->lastname,$row->company);
                            if(isset($row->status_title)){
                                $order_status = $row->status_title;
                            }else{
                                $order_status = "Order Submitted";
                            }
                            ?>
							<tr>
								<td class="center"><?php echo $ref;?></td>
								<td class="center"><?php echo '$'.number_format($row->amount_paid,2);?></td>
								<td class="center"><?php echo $date_created;?></td>
								<td class="center"><?php echo $customer_name;?></td>
								<td class="center"><?php echo $order_status;?></td>
								<td class="center">
									<span class="label label-<?php echo $status_label;?>"><?php echo $status_desc;?></span>
								</td>
								<td class="center">
									<a class="btn btn-success" href="<?php echo $view_order_url;?>">
										<i class="halflings-icon white zoom-in"></i>  
									</a>
								</td>
								
							</tr>
                            <?php endforeach;?>
						  </tbody>
					  </table>            
					</div>
		</div><!--/span-->
			
    </div><!--/row-->
</div>

	