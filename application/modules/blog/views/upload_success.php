
<h1><?php echo $headline;?></h1>
<div class="row-fluid sortable">		
		<div class="box span12">
			<div class="box-header" data-original-title>
				<h2><i class="halflings-icon white tag"></i><span class="break"></span>Upload Success</h2>
				<div class="box-icon">
					<a href="#" class="btn-setting"><i class="halflings-icon white wrench"></i></a>
					<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
					<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
				</div>
			</div>
			<div class="box-content">
               <div class="alert alert-success">Your file was successfully uploaded!</div>

                <ul>
                <?php foreach ($upload_data as $item => $value):?>
                <li><?php echo $item;?>: <?php echo $value;?></li>
                <?php endforeach; ?>
                </ul>

                <a href="<?php echo base_url('blog/create/'.$update_id)?>" class="btn btn-primary">Return to Update Blog Page</a>
            </div>
		</div><!--/span-->
			
    </div><!--/row-->
