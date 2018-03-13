<h1><?php echo $headline;?></h1>
<?php echo validation_errors('<p style="color:red;">','</p>');
    if(isset($flash)){
        echo $flash;
    }
?>   
<?php if(is_numeric($update_id)):?>
    <div class="row-fluid sortable">		
			<div class="box span12">
				<div class="box-header" data-original-title>
					<h2><i class="halflings-icon white tag"></i><span class="break"></span>Book Options</h2>
					<div class="box-icon">
						<a href="#" class="btn-setting"><i class="halflings-icon white wrench"></i></a>
						<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
						<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
					</div>
				</div>
				<div class="box-content">
				
						<?php 
							if(isset($detail_check->book_id)){
								$title = "Update";
								$uri_str = "update";
							}else{
								$title = "Add";
								$uri_str = "create";
							}
						?>
					<a href="<?php echo base_url('sliders/manage')?>" class="btn btn-default">Back To Manage Page</a>    
					<a href="<?php echo base_url('slides/update_group/'.$update_id)?>" class="btn btn-primary">Update Associated Slides</a>    
					<a href="<?php echo base_url('sliders/deleteconf/'.$update_id)?>" class="btn btn-danger">Delete Slider</a>    
					<a href="<?php echo base_url()?>" class="btn btn-default">View Homepage</a>    
				</div>
			</div><!--/span-->
				
    </div><!--/row-->
<?php endif?>
<div class="row-fluid sortable">
	<div class="box span12">
		<div class="box-header" data-original-title>
			<h2><i class="halflings-icon white icon-align-justify"></i><span class="break"></span>Create New Slider</h2>
			<div class="box-icon">
				<a href="#" class="btn-setting"><i class="halflings-icon white wrench"></i></a>
				<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
				<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
			</div>
		</div>
		<div class="box-content">
                     <?php $form_location = base_url('sliders/create/').$update_id;?>
			<form class="form-horizontal" action="<?php echo $form_location;?>" method="post">
			  <fieldset>
         
				<div class="control-group">
				  <label class="control-label" for="slider_title">Slider Title</label>
				  <div class="controls">
					<input type="text" class="span6" id="slider_title" name="slider_title" value="<?php echo $slider_title?>">
				  </div>
				</div>
				<div class="control-group">
				  <label class="control-label" for="target_url">Target URL</label>
				  <div class="controls">
					<input type="text" class="span6" id="target_url" name="target_url" value="<?php echo $target_url?>">
				  </div>
				</div>
				
				<div class="form-actions">
				  <button type="submit" name="submit" value="Submit" class="btn btn-primary">Save changes</button>
				  <button type="submit" class="btn" name="submit" value="Cancel">Cancel</button>
				</div>
			  </fieldset>
			</form>   
		</div>
	</div><!--/span-->
</div><!--/row-->
