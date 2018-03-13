
     
<button type="button" class="btn btn-info" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo">Create a New Slide</button> 

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Create Slide</h4>
      </div>
      <div class="modal-body">
            
        <form action="<?php echo $form_location;?>" method="post">
          <div class="control-group" >
		    <label class="control-label" for="target_url">Subject (OPTIONAL): </label>
		    <div class="controls">
		  	<input type="text" class="span12" id="target_url" name="target_url" placeholder="(OPTIONAL: enter the target url)" value="">
		  </div>
          <div class="control-group">
		    <label class="control-label" for="alt_text">Alt Text (OPTIONAL): </label>
		    <div class="controls">
		  	<input type="text" class="span12" id="alt_text" name="alt_text" placeholder="(OPTIONAL: enter the ALT TEXT)" value="">
		  </div>
          <!-- <div class="form-group">
            <label for="comment" class="control-label">Comment:</label>
            <textarea style="width: 98%;" rows="4" class="form-control" id="comment" name="comment"></textarea>
          </div> -->
		  </div>
          <?php echo form_hidden('comment_type','e');
                echo form_hidden('parent_id',$parent_id);
         ?>
         <div class="form-group pull-right">
            <button type="button" name="submit" value="Cancel" class="btn btn-default" data-dismiss="modal">Close</button>
            <button  value="Submit" class="btn btn-primary" name="submit">Submit</button>
         </div>
        </form>
      </div>
    </div>
  </div>
</div>
