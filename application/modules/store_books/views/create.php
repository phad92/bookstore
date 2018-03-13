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
					<h2><i class="halflings-icon white tag"></i><span class="break"></span>Book Options</h2>
					<div class="box-icon">
						<a href="#" class="btn-setting"><i class="halflings-icon white wrench"></i></a>
						<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
						<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
					</div>
				</div>
				<div class="box-content">
					<?php if($got_gallery_pic == TRUE):
						$gallery_btn_theme = 'success';
						?>
					<div class="alert alert-info">
						<p>NOTE you have atleast one gallery for this book</p>
					</div>
					<?php else: 
						$gallery_btn_theme = 'primary'?>
					<?php endif?>

						<?php 
							if(isset($detail_check->book_id)){
								$title = "Update";
								$uri_str = "update";
							}else{
								$title = "Add";
								$uri_str = "create";
							}
						?>

						
					<!-- <a href="<?php //echo base_url('store_book_detail/'.$uri_str.'/'.$update_id)?>" class="btn btn-primary"><?php echo $title;?> Book Details</a> -->
					<a href="<?php echo base_url('store_book_author/update/'.$update_id)?>" class="btn btn-primary">Update Book Author(s)</a>
					<a href="<?php echo base_url('item_galleries/update_group/'.$update_id)?>" class="btn btn-<?php echo $gallery_btn_theme?>">Manage Item Gallery</a>
					<a href="<?php echo base_url('book_cat_assign/update/'.$update_id)?>" class="btn btn-primary">Update Book Categories</a>
					<a href="<?php echo base_url('store_books/deleteconf/'.$update_id)?>" class="btn btn-danger">Delete Book</a>    
					<a href="<?php echo base_url('store_books/view/'.$update_id)?>" class="btn btn-default">View Book In Store</a>  
					<?php if($big_pic == ""):?>
						<a href="<?php echo base_url('store_books/upload_image/'.$update_id)?>" class="btn btn-primary">Upload Book Image</a>
						<?php else:?>
						<a href="<?php echo base_url('store_books/delete_image/'.$update_id)?>" class="btn btn-danger">Delete Book Image</a>
						<?php endif?>  
				</div>
			</div><!--/span-->
				
    </div><!--/row-->
<?php endif?> 
<div class="row-fluid sortable">
	<div class="box span12">
		<div class="box-header" data-original-title>
			<h2><i class="halflings-icon white edit"></i><span class="break"></span><?php echo $headline?></h2>
			<div class="box-icon">
				<a href="#" class="btn-setting"><i class="halflings-icon white wrench"></i></a>
				<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
				<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
			</div>
		</div>
		<div class="box-content">
										 <?php $form_location = base_url('store_books/create/').$update_id;
										 		$attributes = array('class' => 'form-horizontal');
										 ?>
			<!-- <form class="form-horizontal" action="<?php //echo $form_location;?>" method="post"> -->
			<?php echo form_open_multipart($form_location,$attributes);?>
			<div class="alert alert-warning">
				<?php echo $author_rem?>
			</div>
				<input type="hidden" class="span1" id="was_price" name="was_price" value="<?php //echo $id?>">

			  <fieldset>
				<div class="control-group">
				  <label class="control-label" for="book_title">Book Title </label>
				  <div class="controls">
					<input type="text" class="span6" id="book_title" name="book_title" value="<?php echo $book_title?>">
				  </div>
				</div>
				<div class="control-group">
				  <label class="control-label" for="book_price">Book Price </label>
				  <div class="controls">
					<input type="text" class="span1" id="book_price" name="book_price" value="<?php echo $book_price?>">
				  </div>
				</div>
				<div class="control-group">
				  <label class="control-label" for="was_price">Was Price <span style="color: green;">(optional)</span></label>
				  <div class="controls">
					<input type="text" class="span1" id="was_price" name="was_price" value="<?php echo $was_price?>">
				</div>
				
        </div>
				<div class="control-group">
					<label class="control-label" for="status">Status</label>
					<div class="controls">
						<?php 
							$state = isset($status) ? $status : "" ;
							$additional_dd_code = "id='selectError3'";
							$options = array(
									'' => 'Please Select...',
									'1'=> 'Active',
									'0'=> 'Inactive'
							);
							echo form_dropdown('status', $options,$state, $additional_dd_code);
							
						?>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="book_format">Format</label>
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

				<div class="control-group">
						<label class="control-label" for="bookfile">Upload Book</label>
						<div class="controls">
								<input class="input-file uniform_on" name="bookfile" id="bookfile" type="file">
						</div>
				</div>
                         
				<div class="control-group hidden-phone">
				  <label class="control-label" for="book_description">Book Description</label>
				  <div class="controls">
					<textarea class="cleditor" name="book_description" id="book_description" rows="3"><?php echo $book_description?></textarea>
				  </div>
				</div>
				<div class="form-actions">
				  <!-- <a href="<?php //echo $next_url;?>" type="submit" name="submit" value="Next" class="btn btn-primary">Next</a> -->
				  <button type="submit" name="submit" value="Submit" class="btn btn-primary">Save changes</button>
					<?php if(is_numeric($update_id)):?>
						<a href="<?php echo base_url('store_book_detail/'.$uri_str.'/'.$update_id)?>" class="btn btn-info"><?php echo $title;?> Book Details</a>
					<?php endif?>
				  <button type="submit" class="btn" name="submit" value="Cancel">Cancel</button>
				</div>
			  </fieldset>
			</form>   
		</div>
	</div><!--/span-->
</div><!--/row-->
<?php if(is_numeric($update_id)):?>
					
<?php endif?>
<?php if($big_pic != ""):?>
<div class="row-fluid sortable">		
			<div class="box span12">
				<div class="box-header" data-original-title>
					<h2><i class="halflings-icon white tag"></i><span class="break"></span>Book Image</h2>
					<div class="box-icon">
						<a href="#" class="btn-setting"><i class="halflings-icon white wrench"></i></a>
						<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
						<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
					</div>
				</div>
			<div class="box-content">
				<img src="<?php echo base_url('public/images/books/big_pics/'.$big_pic)?>" alt="<?php echo $big_pic?>">
			</div>
			</div><!--/span-->
				
    </div><!--/row-->
<?php endif?>