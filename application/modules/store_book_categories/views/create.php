<h1><?php echo $headline;?></h1>
<?php echo validation_errors('<p style="color:red;">','</p>');
    if(isset($flash)){
        echo $flash;
    }
?>   

<div class="row-fluid sortable">
	<div class="box span12">
		<div class="box-header" data-original-title>
			<h2><i class="halflings-icon white icon-align-justify"></i><span class="break"></span>Add New Category</h2>
			<div class="box-icon">
				<a href="#" class="btn-setting"><i class="halflings-icon white wrench"></i></a>
				<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
				<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
			</div>
		</div>
		<div class="box-content">
                     <?php $form_location = base_url('store_book_categories/create/').$update_id;?>
			<form class="form-horizontal" action="<?php echo $form_location;?>" method="post">
			  <fieldset>
                <?php if($num_dropdown_options > 1):?>
                <div class="control-group">
					<label class="control-label" for="status">Parent Category</label>
					<div class="controls">
                       <?php 
                         $state = isset($status) ? $status : "" ;
                         $additional_dd_code = "id='selectError3'";
                        
                         echo form_dropdown('book_parent_cat_id', $options,$state, $additional_dd_code);
                        ?>
					</div>
                </div>
                        <?php else: 
                            echo form_hidden('book_parent_cat_id',0);
                        ?>
                <?php endif?>
				<div class="control-group">
				  <label class="control-label" for="cat_title">Category Title </label>
				  <div class="controls">
					<input type="text" class="span6" id="cat_title" name="cat_title" value="<?php echo $cat_title?>">
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
