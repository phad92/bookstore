
<?php if(isset($flash)) print $flash;?>
<div>
    <span style="margin-button: 30px;"><h1>Manage Items</h1></span>
    <a href="<?php echo base_url('tax/create')?>" class="btn btn-primary">Add New Tax</a>
</div>
<div style="margin-top: 30px">
    <div class="row-fluid sortable">		
				<div class="box span12">
					<div class="box-header" data-original-title>
						<h2><i class="halflings-icon white tag"></i><span class="break"></span>Tax Inventory</h2>
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
								  <th>#</th>
								  <th>Tax Type</th>
								  <th>Tax Rate</th>
								  <th class="span3">Actions</th>
							  </tr>
						  </thead>   
						  <tbody>
                        <?php $count = 0; foreach($query->result() as $row): 
                            $count++;
                            $edit_tax_url = base_url('tax/create/'.$row->id);
                            $delete_tax_url = base_url('tax/deleteconf/'.$row->id);
                            $is_percent = $row->is_percent;
                            $tax_rate = $row->tax_rate;
                            if($is_percent == 1){
                                $status_label = "success";
                                $status_desc = $tax_rate."%";
                            }else{
                                $status_label = "default";
                                $status_desc = "$".$tax_rate;
                            }
                            ?>
							<tr>
								<td><?php echo $count?></td>
								<td class="center"><?php echo $row->tax_type?></td>
								<td class="center"><?php echo $status_desc?></td>
								<td class="center">
									<a class="btn btn-info" href="<?php echo $edit_tax_url;?>">
										<i class="halflings-icon white edit"></i>  
									</a>
									<a class="btn btn-danger" href="">
										<i class="halflings-icon white trash"></i>  
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

	