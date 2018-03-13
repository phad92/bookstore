<h1><?php echo $headline;?></h1>
<?php echo validation_errors('<p style="color:red;">','</p>');
    if(isset($flash)){
        echo $flash;
    }
?> 

<div class="row-fluid sortable">
	<div class="box span12">
		<div class="box-header" data-original-title>
			<h2><i class="halflings-icon white edit"></i><span class="break"></span>New Category</h2>
			<div class="box-icon">
				<a href="#" class="btn-setting"><i class="halflings-icon white wrench"></i></a>
				<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
				<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
			</div>
		</div>

		<div class="box-content">
           <p>Choose a New Category</p> 
                     <?php $form_location = base_url('book_cat_assign/submit/').$book_id;?>
			<form class="form-horizontal" action="<?php echo $form_location;?>" method="post">
			  <fieldset>
                
                <div class="control-group">
				  <label class="control-label" for="color">Book Category </label>
				  <?php 
                         $state = isset($status) ? $status : "" ;
                         $additional_dd_code = "id='selectError3'";
                        
                         echo form_dropdown('cat_id', $options,$state, $additional_dd_code);
                        ?>
			    </div>  
              
				<div class="form-actions">
				  <button type="submit" name="submit" value="Submit" class="btn btn-primary">Save changes</button>
				  <button type="submit" class="btn" name="submit" value="Finished">Finished</button>
				</div>
			  </fieldset>
			</form>   
		</div>
	</div><!--/span-->
</div><!--/row-->
<?php if($num_rows > 0): $count = 0;?>
<div style="margin-top: 30px">
    <div class="row-fluid sortable">		
				<div class="box span12">
					<div class="box-header" data-original-title>
						<h2><i class="halflings-icon white tag"></i><span class="break"></span>Existing Colour Options</h2>
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
								  <th>Count</th>
									<th>Parent Category</th>
								  <th>Category Title</th>
								  <th>Actions</th>
							  </tr>
						  </thead>   
						  <tbody>
												<?php 
														$count = 0;
														$this->load->module('store_book_categories');
														foreach($query->result() as $row): 
															$count++;
                              $delete_url = base_url('book_cat_assign/delete/'.$row->id);
															$cat_title = $this->store_book_categories->_get_cat_title($row->cat_id);
															$parent_cat_title = $this->store_book_categories->_get_parent_cat_title($row->cat_id);
														?>
							<tr>
								<td><?php echo $count?></td>
								<td><?php echo $parent_cat_title?></td>
								<td class="center"><?php echo $cat_title?></td>
								
								<td class="center">
									<a class="btn btn-danger" href="<?php echo $delete_url; ?>">
										<i class="halflings-icon white trash"></i> Remove Colour
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
	