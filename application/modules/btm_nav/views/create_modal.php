
     
<button type="button" class="btn btn-info" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo">Create a New Slide</button> 

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Create Link</h4>
      </div>
      <div class="modal-body">
            
        <form action="<?php echo $form_location;?>" method="post">
           <div class="control-group">
				<label class="control-label" for="status">Page URL: </label>
				<div class="controls">
                   <?php 
                     $state = isset($status) ? $status : "" ;
                     $additional_dd_code = "id='selectError3' class='span12'";
                    
                     echo form_dropdown('page_id', $options,'', $additional_dd_code);
                    ?>
				</div>
            </div>
         <div class="form-group pull-right">
            <button type="button" name="submit" value="Cancel" class="btn btn-default" data-dismiss="modal">Close</button>
            <button  value="Submit" class="btn btn-primary" name="submit">Submit</button>
         </div>
        </form>
      </div>
    </div>
  </div>
</div>
