
<?php if(isset($flash)) print $flash;?>
<div>
    <span style="margin-button: 30px;"><h1>Manage Homepage Offers</h1></span>
    <a href="<?php echo base_url('homepage_blocks/create')?>" class="btn btn-primary">Create Homepage Offer</a>
</div>
<div style="margin-top: 30px">
    <div class="row-fluid sortable">		
				<div class="box span12">
					<div class="box-header" data-original-title>
						<h2><i class="halflings-icon white icon-align-justify"></i><span class="break"></span>Existing Offers</h2>
						<div class="box-icon">
							<a href="#" class="btn-setting"><i class="halflings-icon white wrench"></i></a>
							<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
							<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
						</div>
					</div>
					<div class="box-content">
						<?php 
							echo Modules::run('homepage_blocks/_draw_sortable_list')
                        ?>
						          
					</div>
		</div><!--/span-->
			
    </div><!--/row-->
</div>

	