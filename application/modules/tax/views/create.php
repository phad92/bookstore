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
					<a href="<?php echo base_url('tax/create/')?>" class="btn btn-default">Manage Tax</a>
					<a href="<?php echo base_url('tax/deleteconf/'.$update_id)?>" class="btn btn-danger">Delete Tax</a>
				</div>
			</div><!--/span-->
				
    </div><!--/row-->
<?php endif?> 
<div class="row-fluid sortable">
	<div class="box span12">
		<div class="box-header" data-original-title>
			<h2><i class="halflings-icon white edit"></i><span class="break"></span>Please Fill The Form Below</h2>
			<div class="box-icon">
				<a href="#" class="btn-setting"><i class="halflings-icon white wrench"></i></a>
				<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
				<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
			</div>
		</div>
		<div class="box-content">
                     <?php $form_location = base_url('tax/create/').$update_id;?>
			<form class="form-horizontal" action="<?php echo $form_location;?>" method="post">
			  <fieldset>
				<div class="control-group">
				  <label class="control-label" for="tax_type">Tax Type </label>
				  <div class="controls">
					<input type="text" class="span6" id="tax_type" name="tax_type" value="<?php echo $tax_type?>">
				  </div>
				</div>
				<div class="control-group">
				  <label class="control-label" for="tax_rate">Tax Rate </label>
				  <div class="controls">
					<input type="text" class="span1" id="tax_rate" name="tax_rate" value="<?php echo $tax_rate?>">
				  </div>
				</div>
				<div class="control-group">
				  <label class="control-label" for="is_percent">Is Percent ?</label>
				  <div class="controls">
                    <?php //echo form_checkbox('is_percent', '', false);?>
					<?php 
                                 $state = isset($is_percent) ? $is_percent : "" ;
                                 $additional_dd_code = "id='selectError3'";
                                 $options = array(
                                     '' => 'Please Select...',
                                     '1'=> 'Yes',
                                     '0'=> 'No'
                                 );
                                 echo form_dropdown('is_percent', $options,$state, $additional_dd_code);
                                 
                               ?>
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
