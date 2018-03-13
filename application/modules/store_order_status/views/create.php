<h1><?php echo $headline;?></h1>
<?php echo validation_errors('<p style="color:red;">','</p>');
    if(isset($flash)){
        echo $flash;
    }
?>   

<div class="row-fluid sortable">
	<div class="box span12">
		<div class="box-header" data-original-title>
			<h2><i class="halflings-icon white edit"></i><span class="break"></span>Order Status Option</h2>
			<div class="box-icon">
				<a href="#" class="btn-setting"><i class="halflings-icon white wrench"></i></a>
				<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
				<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
			</div>
		</div>
		<div class="box-content">
                     <?php $form_location = base_url('store_order_status/create/').$update_id;?>
			<form class="form-horizontal" action="<?php echo $form_location;?>" method="post">
			  <fieldset>
<div class="control-group"> <label class="control-label" for="status_title">Status Title</label> <div class="controls"> <input type="text" class="span6" id="status_title" name="status_title" value="<?php echo $status_title?>"> </div> </div>
				<div class="form-actions">
				  <button type="submit" name="submit" value="Submit" class="btn btn-primary">Save changes</button>
				  <button type="submit" class="btn" name="submit" value="Cancel">Cancel</button>
				</div>
			  </fieldset>
			</form>   
		</div>
	</div><!--/span-->
</div><!--/row-->
