<h1><?php echo $headline;?></h1>
<div class="row-fluid sortable">		
		<div class="box span12">
			<div class="box-header" data-original-title>
				<h2><i class="halflings-icon white tag"></i><span class="break"></span>Delete Item</h2>
				<div class="box-icon">
					<a href="#" class="btn-setting"><i class="halflings-icon white wrench"></i></a>
					<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
					<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
				</div>
			</div>
			<div class="box-content">
               <div class="alert alert-danger">Are you sure you want to delete Slide!</div>
                
               <!-- <a href="<?php //echo base_url('slides/delete/'.$update_id)?>" class="btn btn-danger" name="submit" value="Delete">Delete Item</a> -->
			   
			  <?php echo form_open('slides/delete/'.$update_id)?>
               <button  class="btn btn-danger" type="submit" name="submit" value="Delete">Delete Item</button>
                <button type="submit" class="btn btn-default" name="submit" value="Cancel">Cancel</button>
				<?php echo form_close()?>
            </div>
		</div><!--/span-->
			
    </div><!--/row-->
