<h1><?php echo $headline;?></h1>
<?php echo validation_errors('<p style="item_id:red;">','</p>');
    if(isset($flash)){
        echo $flash;
    }
?> 

<div class="row-fluid sortable">
	<div class="box span12">
		<div class="box-header" data-original-title>
			<h2><i class="halflings-icon white edit"></i><span class="break"></span>Update Homepage Offer</h2>
			<div class="box-icon">
				<a href="#" class="btn-setting"><i class="halflings-icon white wrench"></i></a>
				<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
				<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
			</div>
		</div>
		<div class="box-content">
           <p>Please press Finish after submitting</p> 
                     <?php $form_location = base_url('store_book_author/submit/').$update_id;?>
			<form class="form-horizontal" action="<?php echo $form_location;?>" method="post">
			  <fieldset>
                <div class="control-group">
				  <label class="control-label" for="name">Author's Name</label>
				  <div class="controls">
					<input type="text" class="span6" id="name" name="name" placeholder="Enter Author's Name Here" value="">
				  </div>
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
						<h2><i class="halflings-icon white tag"></i><span class="break"></span>Existing Homepage Offers Options</h2>
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
								  <th>Author(s)</th>
								  <th>Actions</th>
							  </tr>
						  </thead>   
						  <tbody>
												<?php 
														$this->load->module('store_book_author');
														foreach($query->result() as $row): 
                            $delete_url = base_url('store_book_author/delete/'.$row->id.'/'.$row->author_id);
														$count++;
														$author = $row->name;
                            ?>
							<tr>
								<td><?php echo $count?></td>
								<td class="center"><?php echo $author?></td>
								
								<td class="center">
									<a class="btn btn-danger" href="<?php echo $delete_url; ?>">
										<i class="halflings-icon white trash"></i> Remove Homepage Offers
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
	