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
                     <?php $form_location = base_url('store_book_detail/update/').$update_id;?>
			<form class="form-horizontal" action="<?php echo $form_location;?>" method="post">
			  <fieldset>
			  	<input type="hidden" name="id" value="<?php echo $id?>">
                <div class='control-group'> <label class='control-label' for='isbn_10'>isbn_10</label> <div class='controls'> <input type='text' class='span6' id='isbn_10' name='isbn_10' value='<?php echo $isbn_10?>'> </div> </div> 
                <div class='control-group'> <label class='control-label' for='isbn_13'>isbn_13</label> <div class='controls'> <input type='text' class='span6' id='isbn_13' name='isbn_13' value='<?php echo $isbn_13?>'> </div> </div> 
                <!-- <div class='control-group'> <label class='control-label' for='book_format'>book_format</label> <div class='controls'> <input type='text' class='span6' id='book_format' name='book_format' value='<?php echo $book_format?>'> </div> </div>  -->
                <div class="control-group">
					<label class="control-label" for="book_format">Book Format</label>
					<div class="controls">
                               <?php 
                                $state = isset($book_format) ? $book_format : "" ;
                                 $additional_dd_code = "id='selectError3'";
                                 $options = array(
                                     '' => 'Please Select...',
                                     'audio'=> 'Audio',
                                     'digital'=> 'Digital'
                                 );
                                 echo form_dropdown('book_format', $options,$state, $additional_dd_code);
                                 
                               ?>
					</div>
				</div>
				<div class='control-group'> <label class='control-label' for='publisher'>publisher</label> <div class='controls'> <input type='text' class='span6' id='publisher' name='publisher' value='<?php echo $publisher?>'> </div> </div> 
                <div class='control-group'> <label class='control-label' for='published_date'>published_date</label> <div class='controls'> <input type='text' class='span6' id='published_date' name='published_date' value='<?php echo $published_date?>'> </div> </div> 
                <div class='control-group'> <label class='control-label' for='edition'>edition</label> <div class='controls'> <input type='text' class='span6' id='edition' name='edition' value='<?php echo $edition?>'> </div> </div> 
                <div class='control-group'> <label class='control-label' for='dimension'>dimension</label> <div class='controls'> <input type='text' class='span6' id='dimension' name='dimension' value='<?php echo $dimension?>'> </div> </div> 
                <div class="form-actions">
				  <button type="submit" name="submit" value="Submit" class="btn btn-primary">Save changes</button>
				  <button type="submit" class="btn" name="submit" value="Cancel">Cancel</button>
				</div>
			  </fieldset>
			</form>   
		</div>
	</div><!--/span-->
</div><!--/row-->
