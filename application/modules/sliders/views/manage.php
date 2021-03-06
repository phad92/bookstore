
<?php if(isset($flash)) print $flash;?>
<div>
    <span style="margin-button: 30px;"><h1>Manage Sliders</h1></span>
    <a href="<?php echo base_url('sliders/create')?>" class="btn btn-primary">Create New Slider</a>
</div>
<?php if(count($query->result()) < 1):?>
	<p style="margin-top: 14px;">No Sliders Available Now</p>;
<?php else:?>
<div style="margin-top: 30px">
    <div class="row-fluid sortable">		
				<div class="box span12">
					<div class="box-header" data-original-title>
						<h2><i class="halflings-icon white icon-align-justify"></i><span class="break"></span>Existing Sliders</h2>
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
								  <th>Slider Title</th>
								  <th>Target URL</th>
								  <th class="span2">Actions</th>
							  </tr>
						  </thead>   
						  <tbody>
                        <?php foreach($query->result() as $row): 
							$edit_page_url = base_url('sliders/create/'.$row->id);
							
                            ?>
							<tr>
								<td class="center"><?php echo $row->slider_title?></td>
								<td><?php echo base_url().$row->target_url?></td>
								
								<td class="center">
									<a class="btn btn-success" href="#">
										<i class="halflings-icon white zoom-in"></i>  
									</a>
									<a class="btn btn-info" href="<?php echo $edit_page_url;?>">
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
<?php endif?>
	