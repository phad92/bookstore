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
					<h2><i class="halflings-icon white tag"></i><span class="break"></span>More Options</h2>
					<div class="box-icon">
						<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
						<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
					</div>
				</div>
				<div class="box-content">
					<?php if($update_id > 2):?>
                    <a href="<?php echo base_url('webpages/deleteconf/'.$update_id)?>" class="btn btn-danger">Delete Page</a> 
<?php  endif;?>   
					<a href="<?php echo base_url('webpages/view/'.$page_url)?>" class="btn btn-default">View Page</a>    
				</div>
			</div><!--/span-->
				
    </div><!--/row-->
<?php endif?>
<div class="row-fluid sortable">
	<div class="box span12">
		<div class="box-header" data-original-title>
			<h2><i class="halflings-icon white edit"></i><span class="break"></span>Page Details</h2>
			<div class="box-icon">
				<a href="#" class="btn-setting"><i class="halflings-icon white wrench"></i></a>
				<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
				<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
			</div>
		</div>
		<div class="box-content">
                     <?php $form_location = base_url('webpages/create/').$update_id;?>
			<form class="form-horizontal" action="<?php echo $form_location;?>" method="post">
			  <fieldset>
				<div class="control-group">
				  <label class="control-label" for="page_title">Page Title </label>
				  <div class="controls">
					<input type="text" class="span6" id="page_title" name="page_title" value="<?php echo $page_title?>">
				  </div>
				</div>
                <div class="control-group">
                    <label class="control-label" for="page_keywords">Page Keywords</label>
                    <div class="controls">
                        <textarea class="" name="page_keywords" id="page_keywords" cols="20"><?php echo $page_keywords?></textarea>
                    </div>
                </div>
                
				<div class="control-group">
                    <label class="control-label" for="page_description">Item Description</label>
                    <div class="controls">
					<textarea class="" name="page_description" id="page_description" rows="3"><?php echo $page_description?></textarea>
                </div>
                <div class="control-group">
                    <label class="control-label" for="page_content">Item Description</label>
                    <div class="controls">
                    <textarea class="cleditor" name="page_content" id="page_content" rows="3"><?php echo $page_content?></textarea>
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
