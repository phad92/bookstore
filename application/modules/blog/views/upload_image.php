
<h1><?php echo $headline;?></h1>
<?php if(is_numeric($update_id)):?>
    <div class="row-fluid sortable">		
		<div class="box span12">
			<div class="box-header" data-original-title>
				<h2><i class="halflings-icon white tag"></i><span class="break"></span>Upload Image</h2>
				<div class="box-icon">
					<a href="#" class="btn-setting"><i class="halflings-icon white wrench"></i></a>
					<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
					<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
				</div>
			</div>
			<div class="box-content">
                <div>
                    <?php if(isset($error)):?>
                        <?php foreach($error as $err):?>
                            <?php echo $err?>
                        <?php endforeach?>
                    <?php endif?>
                    <?php echo form_open_multipart('blog/do_upload/'.$update_id);?>
                        <fieldset>
                            <label class="control-label" for="fileInput">File input</label>
                                <div class="control-group">
                                <div class="controls">
                                    <input class="input-file uniform_on" name="userfile" id="fileInput" type="file">
                                </div>
                                </div>          
                                
                                <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Upload</button>
                                <button type="submit" class="btn" name="submit" value="Cancel">Cancel</button>
                            </div>
                        </fieldset>  
                    </form>

                </div>
            </div>
		</div><!--/span-->
			
    </div><!--/row-->
<?php endif?>