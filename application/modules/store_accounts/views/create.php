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
					<h2><i class="halflings-icon white tag"></i><span class="break"></span>Account Options</h2>
					<div class="box-icon">
						<a href="#" class="btn-setting"><i class="halflings-icon white wrench"></i></a>
						<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
						<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
					</div>
				</div>
				<div class="box-content">
				
					<a href="<?php echo base_url('store_accounts/update_pword/'.$update_id)?>" class="btn btn-primary">Update Password</a>
					<a href="<?php echo base_url('store_accounts/deleteconf/'.$update_id)?>" class="btn btn-danger">Update Account</a>
				</div>
			</div><!--/span-->
				
    </div><!--/row-->
<?php endif?> 
<div class="row-fluid sortable">
	<div class="box span12">
		<div class="box-header" data-original-title>
			<h2><i class="halflings-icon white edit"></i><span class="break"></span>Add New Account</h2>
			<div class="box-icon">
				<a href="#" class="btn-setting"><i class="halflings-icon white wrench"></i></a>
				<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
				<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
			</div>
		</div>
		<div class="box-content">
                     <?php $form_location = base_url('store_accounts/create/').$update_id;?>
			<form class="form-horizontal" action="<?php echo $form_location;?>" method="post">
			  <fieldset>
    <div class="control-group"> <label class="control-label" for="username">Username</label> <div class="controls"> <input type="text" class="span6" id="username" name="username" value="<?php echo $username?>"> </div> </div>
    <div class="control-group"> <label class="control-label" for="firstname">First Name</label> <div class="controls"> <input type="text" class="span6" id="firstname" name="firstname" value="<?php echo $firstname?>"> </div> </div>
<div class="control-group"> <label class="control-label" for="lastname">Last Name</label> <div class="controls"> <input type="text" class="span6" id="lastname" name="lastname" value="<?php echo $lastname?>"> </div> </div>
<div class="control-group"> <label class="control-label" for="company">Company</label> <div class="controls"> <input type="text" class="span6" id="company" name="company" value="<?php echo $company?>"> </div> </div>
<div class="control-group"> <label class="control-label" for="address1">Address Line 1</label> <div class="controls"> <input type="text" class="span6" id="address1" name="address1" value="<?php echo $address1?>"> </div> </div>
<div class="control-group"> <label class="control-label" for="address2">Address Line 2</label> <div class="controls"> <input type="text" class="span6" id="address2" name="address2" value="<?php echo $address2?>"> </div> </div>
<div class="control-group"> <label class="control-label" for="town">Town</label> <div class="controls"> <input type="text" class="span6" id="town" name="town" value="<?php echo $town?>"> </div> </div>
<div class="control-group"> <label class="control-label" for="country">Country</label> <div class="controls"> <input type="text" class="span6" id="country" name="country" value="<?php echo $country?>"> </div> </div>
<div class="control-group"> <label class="control-label" for="postcode">Postcode</label> <div class="controls"> <input type="text" class="span6" id="postcode" name="postcode" value="<?php echo $postcode?>"> </div> </div>
<div class="control-group"> <label class="control-label" for="telnum">Telnum</label> <div class="controls"> <input type="text" class="span6" id="telnum" name="telnum" value="<?php echo $telnum?>"> </div> </div>
<div class="control-group"> <label class="control-label" for="email">Email</label> <div class="controls"> <input type="text" class="span6" id="email" name="email" value="<?php echo $email?>"> </div> </div>
				<div class="form-actions">
				  <button type="submit" name="submit" value="Submit" class="btn btn-primary">Save changes</button>
				  <button type="submit" class="btn" name="submit" value="Cancel">Cancel</button>
				</div>
			  </fieldset>
			</form>   
		</div>
	</div><!--/span-->
</div><!--/row-->
