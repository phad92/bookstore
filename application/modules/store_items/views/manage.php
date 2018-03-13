
<?php if(isset($flash)) print $flash;?>
<div>
    <span style="margin-button: 30px;"><h1>Manage Items</h1></span>
    <a href="<?php echo base_url('store_items/create')?>" class="btn btn-primary">Add New Item</a>
</div>
<div style="margin-top: 30px">
    <div class="row-fluid sortable">		
				<div class="box span12">
					<div class="box-header" data-original-title>
						<h2><i class="halflings-icon white tag"></i><span class="break"></span>Item Inventory</h2>
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
								  <th>Item Title</th>
								  <th>Price</th>
								  <th>Was Price</th>
								  <th>Status</th>
								  <th>Actions</th>
							  </tr>
						  </thead>   
						  <tbody>
                        <?php foreach($query->result() as $row): 
                            $edit_item_url = base_url('store_items/create/'.$row->id);
                            $status = $row->status;
                            if($status == 1){
                                $status_label = "success";
                                $status_desc = "Active";
                            }else{
                                $status_label = "default";
                                $status_desc = "Inactive";
                            }
                            ?>
							<tr>
								<td><?php echo $row->item_title?></td>
								<td class="center"><?php echo $row->item_price?></td>
								<td class="center"><?php echo $row->was_price?></td>
								<td class="center">
									<span class="label label-<?php echo $status_label;?>"><?php echo $status_desc;?></span>
								</td>
								<td class="center">
									<a class="btn btn-success" href="#">
										<i class="halflings-icon white zoom-in"></i>  
									</a>
									<a class="btn btn-info" href="<?php echo $edit_item_url;?>">
										<i class="halflings-icon white edit"></i>  
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

	