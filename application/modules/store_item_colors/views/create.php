<h1><?php echo $headline;?></h1>
<?php echo validation_errors('<p style="color:red;">','</p>');
    if(isset($flash)){
        echo $flash;
    }
?> 

<div class="row-fluid sortable">
	<div class="box span12">
		<div class="box-header" data-original-title>
			<h2><i class="halflings-icon white edit"></i><span class="break"></span>Add New Colour</h2>
			<div class="box-icon">
				<a href="#" class="btn-setting"><i class="halflings-icon white wrench"></i></a>
				<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
				<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
			</div>
		</div>
		<div class="box-content">
           <p>Please press Finish after submitting a new colour option</p> 
                     <?php $form_location = base_url('store_item_colors/submit/').$update_id;?>
			<form class="form-horizontal" action="<?php echo $form_location;?>" method="post">
			  <fieldset>
                <div class="control-group">
				  <label class="control-label" for="color">Item Colour </label>
				  <div class="controls">
					<input type="text" class="span6" id="color" name="color" value="">
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
								  <th>Colour</th>
								  <th>Actions</th>
							  </tr>
						  </thead>   
						  <tbody>
                        <?php foreach($query->result() as $row): 
                            $delete_url = base_url('store_item_colors/delete/'.$row->id);
                            $count++;
                            ?>
							<tr>
								<td><?php echo $count?></td>
								<td class="center"><?php echo $row->color?></td>
								
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
	