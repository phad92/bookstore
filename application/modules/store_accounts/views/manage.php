
<?php if(isset($flash)) echo $flash;?>
<div>
    <span style="margin-button: 30px;"><h1>Manage Accounts</h1></span>
    <a href="<?php echo base_url('store_accounts/create')?>" class="btn btn-primary">Add New Account</a>
</div>
<div style="margin-top: 30px">
    <div class="row-fluid sortable">		
				<div class="box span12">
					<div class="box-header" data-original-title>
						<h2><i class="halflings-icon white briefcase"></i><span class="break"></span>Customer Accounts</h2>
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
								  <th>Username</th>
								  <th>Fistname</th>
								  <th>Lastname</th>
								  <th>Company</th>
								  <th>Date Created</th>
								  <th>Actions</th>
							  </tr>
						  </thead>   
						  <tbody>

                        <?php $this->load->module('timedate'); 
                            foreach($query->result() as $row): 
                            $edit_account_url = base_url('store_accounts/create/'.$row->id);
                            $view_account_url = base_url('store_accounts/create/'.$row->id);
                            $date_created = $this->timedate->get_nice_date($row->date_made,'cool');
                            ?>
							<tr>
								<td><?php echo $row->username?></td>
								<td><?php echo $row->firstname?></td>
								<td class="center"><?php echo $row->lastname?></td>
								<td class="center"><?php echo $row->company?></td>
								<td class="center"><?php echo $date_created?></td>
								
								<td class="center">
									<a class="btn btn-success" href="#">
										<i class="halflings-icon white zoom-in"></i>  
									</a>
									<a class="btn btn-info" href="<?php echo $edit_account_url;?>">
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

	