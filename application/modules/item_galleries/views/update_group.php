
<?php if(isset($flash)) print $flash;?>
<div>
    <span style="margin-button: 30px;">
		<h1><?php echo $headline;?></h1>
		<p><?php echo $sub_headline?></p>
	</span>
    <?php echo Modules::run('item_galleries/_draw_create_modal',$parent_id)?>

</div>
<a href="<?php echo base_url('store_books/create/'.$parent_id)?>"><button class="btn btn-default">Previous Page</button></a>
     
<a href="<?php echo base_url('item_galleries/upload_image/'.$parent_id)?>" class="btn btn-info">Upload New Picture</a> 

<?php if($num_rows < 0):?>
    <p style="margin-top: 24px;"><?php echo $parent_title?> Has No <?php echo $entity_name?></p>
<?php else:?>
<div style="margin-top: 30px">
    <div class="row-fluid sortable">		
				<div class="box span12">
					<div class="box-header" data-original-title>
						<h2><i class="halflings-icon white file"></i><span class="break"></span>Pages</h2>
						<div class="box-icon">
							<a href="#" class="btn-setting"><i class="halflings-icon white wrench"></i></a>
							<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
							<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
						</div>
					</div>
					<div class="box-content">
						<table class="table table-striped table-bordered bootstrap-datatable datatable">
						  <thead>
							  <tr>
								  <th>Picture</th>
								  <th class="span2">Actions</th>
							  </tr>
						  </thead>   
						  <tbody>
						<?php 
							$this->load->module('timedate');
                            foreach($query->result() as $row): 
                            $delete_url = base_url('item_galleries/deleteconf/'.$row->id);
                            $picture = $row->picture;
                            $pic_path = base_url().'public/images/gallery/'.$picture;
							?>
							<tr>
								<td>
                                    <?php if($picture != ''):?>
                                    <img src="<?php echo $pic_path?>" alt="<?php echo $picture?>">
                                    <?php endif?>
                                </td>
								<td class="center">
                                
                                    <a class="btn btn-danger" href="<?php echo $delete_url;?>">
										<i class="halflings-icon white trash"></i>  
									</a>
								</td>
							
								
							</tr>
                            <?php endforeach;?>
						  </tbody>
					  </table>            
					</div>
		</div><!--/span-->
			
    </div><!--/row-->
</div>
<?php endif?>





	