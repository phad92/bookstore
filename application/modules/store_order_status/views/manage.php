
<?php if(isset($flash)) print $flash;?>
<div>
    <span style="margin-button: 30px;"><h1>Manage Order Status Options</h1></span>
    <a href="<?php echo base_url('store_order_status/create')?>" class="btn btn-primary">Add New Order Status Option</a>
</div>
<div style="margin-top: 30px">
    <div class="row-fluid sortable">		
				<div class="box span12">
					<div class="box-header" data-original-title>
						<h2><i class="halflings-icon white list-alt"></i><span class="break"></span>Current Order Status Options</h2>
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
								  <th>Status Title</th>
								  <th>Actions</th>
							  </tr>
						  </thead>   
						  <tbody>

                        <?php $this->load->module('timedate'); 
                            foreach($query->result() as $row): 
                            $edit_url = base_url('store_order_status/create/'.$row->id);
                            $view_url = base_url('store_order_status/create/'.$row->id);
                            ?>
							<tr>
								<td class="center"><?php echo $row->status_title?></td>
								
								<td class="center span3">
									<a class="btn btn-info" href="<?php echo $edit_url;?>">
											<i class="halflings-icon white edit"></i>  
										</a>
										<a class="btn btn-danger" href="<?php echo base_url('store_order_status/deleteconf/'.$row->id)?>">
											<i class="halflings-icon white remove"></i> Delete
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

	