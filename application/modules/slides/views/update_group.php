
<?php if(isset($flash)) print $flash;?>
<div>
    <span style="margin-button: 30px;"><h1><?php echo $headline;?></h1></span>
    <?php echo Modules::run('slides/_draw_create_modal',$parent_id)?>

</div>
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
                            $target_url = $row->target_url;
                            $edit_page_url = base_url('slides/view/'.$row->id);
                            if($target_url != ''){
                                $view_page_url = $target_url;
                            }
                            $picture = $row->picture;
                            $pic_path = base_url().'public/images/img/'.$picture;
							?>
							<tr>
								<td>
                                    <?php if($picture != ''):?>
                                    <img src="<?php echo $pic_path?>" alt="<?php echo $picture?>">
                                    <?php endif?>
                                </td>
								<td class="center">
                                <?php if(isset($view_page_url)):?>
									<a class="btn btn-success" href="<?php echo $view_page_url?>">
										<i class="halflings-icon white zoom-in"></i>  
									</a>
								<?php endif?>
                                    <a class="btn btn-info" href="<?php echo $edit_page_url;?>">
										<i class="halflings-icon white edit"></i>  
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





	