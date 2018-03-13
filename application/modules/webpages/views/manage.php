
<?php if(isset($flash)) print $flash;?>
<div>
    <span style="margin-button: 30px;"><h1>Content Management System</h1></span>
    <a href="<?php echo base_url('webpages/create')?>" class="btn btn-primary">Create New Web Page</a>
</div>
<div style="margin-top: 30px">
    <div class="row-fluid sortable">		
				<div class="box span12">
					<div class="box-header" data-original-title>
						<h2><i class="halflings-icon white file"></i><span class="break"></span>Pages</h2>
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
								  <th>Page Title</th>
								  <th>Headline</th>
								  <th>Page URL</th>
								  <th class="span2">Actions</th>
							  </tr>
						  </thead>   
						  <tbody>
                        <?php foreach($query->result() as $row): 
                            $edit_page_url = base_url('webpages/create/'.$row->id);
                            ?>
							<tr>
								<td><?php echo $row->page_title?></td>
								<td class="center"><?php echo $row->page_headline?></td>
								<td class="center">
									<span class=""><?php echo base_url().$row->page_url;?></span>
								</td>
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

	