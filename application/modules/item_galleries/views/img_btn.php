<div class="row-fluid sortable">		
	<div class="box span12">
		<div class="box-header" data-original-title>
			<h2><i class="halflings-icon white picture"></i><span class="break"></span>Image Options</h2>
			<div class="box-icon">
				<a href="#" class="btn-setting"><i class="halflings-icon white wrench"></i></a>
				<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
				<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
			</div>
		</div>
		<div class="box-content">
			<p><?php echo $btn_info?></p>
            <?php if($got_pic == FALSE):?>
			<a href="<?php echo base_url('item_galleries/upload_image/'.$update_id)?>" class="btn btn-primary">Upload Image</a>
            <?php else:?>
            <img src="<?php echo $pic_path?>" alt="" class="img-responsive">
            <?php endif?>
			<a href="<?php echo base_url('item_galleries/deleteconf/'.$update_id)?>" class="btn btn-danger" <?php echo $btn_style?>>Delete Item Image</a>
				
		  
		</div>
	</div><!--/span-->
			
</div><!--/row-->