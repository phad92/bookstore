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
					<h2><i class="halflings-icon white tag"></i><span class="break"></span>Item Options</h2>
					<div class="box-icon">
						<a href="#" class="btn-setting"><i class="halflings-icon white wrench"></i></a>
						<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
						<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
					</div>
				</div>
				<div class="box-content">
				<?php if($got_gallery_pic == TRUE):
					$gallery_btn_theme = 'success';
					?>
				<div class="alert alert-info">
					<p>NOTE you have atleast one gallery for this book</p>
				</div>
				<?php else: 
					$gallery_btn_theme = 'primary'?>
				<?php endif?>
					<?php if($big_pic == ""):?>
						<a href="<?php echo base_url('store_items/upload_image/'.$update_id)?>" class="btn btn-<?php echo $gallery_btn_theme?>">Upload Item Image</a>
						<?php else:?>
						<a href="<?php echo base_url('store_items/delete_image/'.$update_id)?>" class="btn btn-danger">Delete Item Image</a>
						<?php endif?>
					<a href="<?php echo base_url('store_item_colors/update/'.$update_id)?>" class="btn btn-primary">Update Item Colours</a>
					<a href="<?php echo base_url('store_item_sizes/update/'.$update_id)?>" class="btn btn-primary">Update Item Sizes</a>
					<a href="<?php echo base_url('store_cat_assign/update/'.$update_id)?>" class="btn btn-primary">Update Item Categories</a>
					<a href="<?php echo base_url('store_items/deleteconf/'.$update_id)?>" class="btn btn-danger">Delete Item</a>    
					<a href="<?php echo base_url('store_items/view/'.$update_id)?>" class="btn btn-default">View Item In Store</a>    
				</div>
			</div><!--/span-->
				
    </div><!--/row-->
<?php endif?> 
<div class="row-fluid sortable">
	<div class="box span12">
		<div class="box-header" data-original-title>
			<h2><i class="halflings-icon white edit"></i><span class="break"></span>Form Elements</h2>
			<div class="box-icon">
				<a href="#" class="btn-setting"><i class="halflings-icon white wrench"></i></a>
				<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
				<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
			</div>
		</div>
		<div class="box-content">
                     <?php $form_location = base_url('store_items/create/').$update_id;?>
			<form class="form-horizontal" action="<?php echo $form_location;?>" method="post">
			  <fieldset>
				<div class="control-group">
				  <label class="control-label" for="item_title">Item Title </label>
				  <div class="controls">
					<input type="text" class="span6" id="item_title" name="item_title" value="<?php echo $item_title?>">
				  </div>
				</div>
				<div class="control-group">
				  <label class="control-label" for="item_price">Item Price </label>
				  <div class="controls">
					<input type="text" class="span1" id="item_price" name="item_price" value="<?php echo $item_price?>">
				  </div>
				</div>
				<div class="control-group">
				  <label class="control-label" for="was_price">Was Price <span style="color: green;">(optional)</span></label>
				  <div class="controls">
					<input type="text" class="span1" id="was_price" name="was_price" value="<?php echo $was_price?>">
				  </div>
                         </div>
				<div class="control-group">
					<label class="control-label" for="status">Status</label>
					<div class="controls">
                               <?php 
                                 $state = isset($status) ? $status : "" ;
                                 $additional_dd_code = "id='selectError3'";
                                 $options = array(
                                     '' => 'Please Select...',
                                     '1'=> 'Active',
                                     '0'=> 'Inactive'
                                 );
                                 echo form_dropdown('status', $options,$state, $additional_dd_code);
                                 
                               ?>
					</div>
				</div>
                         
				<div class="control-group hidden-phone">
				  <label class="control-label" for="item_description">Item Description</label>
				  <div class="controls">
					<textarea class="cleditor" name="item_description" id="item_description" rows="3"><?php echo $item_description?></textarea>
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
<?php if($big_pic != ""):?>
<div class="row-fluid sortable">		
			<div class="box span12">
				<div class="box-header" data-original-title>
					<h2><i class="halflings-icon white tag"></i><span class="break"></span>Item Image</h2>
					<div class="box-icon">
						<a href="#" class="btn-setting"><i class="halflings-icon white wrench"></i></a>
						<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
						<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
					</div>
				</div>
			<div class="box-content">
				<img src="<?php echo base_url('public/images/big_pics/'.$big_pic)?>" alt="<?php echo $big_pic?>">
			</div>
			</div><!--/span-->
				
    </div><!--/row-->
<?php endif?>