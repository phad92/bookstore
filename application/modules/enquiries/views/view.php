<h1><?php echo $headline;?></h1>
<?php echo validation_errors('<p style="color:red;">','</p>');
    if(isset($flash)){
        echo $flash;
    }
    
    $this->load->module('store_accounts'); 
    $this->load->module('timedate'); 
    foreach($query->result() as $row){ 
        $view_url = base_url('enquiries/view/'.$row->id);
        $opened = $row->opened;
        if($opened == 1){
            $icon = "<i class='icon-envelope'></i>";
        }else{
            $icon = "<i class='icon-envelope-alt' style='color: orange'></i>";
         }
        $date_sent = $this->timedate->get_nice_date($row->date_created,'full');
        if($row->sent_by == 0){
            $sent_by = "Admin";
        }else{
            $sent_by = $this->store_accounts->_get_customer_name($row->sent_by);
        }
        $ranking = $row->ranking;
    }//                        
?> 

<div style="margin-top: 30px">
    <a href="<?php echo base_url('enquiries/create/'.$update_id)?>">
        <button class="btn btn-primary" type="button">Reply To Message</button> 
        <button type="button" class="btn btn-info" data-toggle="modal" data-target="#exampleModal" data-whatever="@mdo">Post a Comment</button>    
    </a>
   <div class="row-fluid sortable" style="margin-top: 15px;">
	<div class="box span12">
		<div class="box-header" data-original-title>
			<h2><i class="halflings-icon white edit"></i><span class="break"></span>Select Ranking</h2>
			<div class="box-icon">
				<a href="#" class="btn-setting"><i class="halflings-icon white wrench"></i></a>
				<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
				<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
			</div>
		</div>

		<div class="box-content">
           <!-- <p></p>  -->
            <?php $form_location = base_url('enquiries/submit_ranking/'.$update_id);?>
                <form class="form-horizontal" action="<?php echo $form_location;?>" method="post">
                <fieldset>
                    <div class="control-group">
                    <label class="control-label" for="color">Ranking: </label> 
                    <?php 

                        $additional_dd_code = "id='selectError3'";
                        
                        echo form_dropdown('ranking', $options,$ranking, $additional_dd_code);
                        
                        ?>
                    </div>  
                    <div class="form-actions">
                    <button type="submit" name="submit" value="Submit" class="btn btn-primary">Save changes</button>
                    <button type="submit" class="btn" name="submit" value="Cancel">Cancel</button>
                    </div>
                </fieldset>
                </form>   
		</div>
	</div><!--/span-->
</div><!--/row-->
    <div class="row-fluid sortable">		
				<div class="box span12">
					<div class="box-header" data-original-title>
						<h2><i class="halflings-icon white briefcase"></i><span class="break"></span>Messages</h2>
						<div class="box-icon">
							<a href="#" class="btn-setting"><i class="halflings-icon white wrench"></i></a>
							<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
							<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
						</div>
					</div>
					<div class="box-content">
                    
						<table class="table table-striped table-bordered bootstrap-datatable">
						  
						  <tbody>
							<tr>
                               
                                <tr>
                                    <td style="font-weight: bold">Ranking </td>
                                    <td><?php if($ranking < 1){
                                        echo '-';
                                    }else{
                                        for($i = 0; $i < $ranking; $i++){
                                            echo "<i class='icon-star'></i>";
                                        }
                                    };?></td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold">Date Sent</td><td><?php echo $date_sent;?></td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold">Sent By</td><td><?php echo $sent_by;?></td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold">Subject</td><td><?php echo $row->subject;?></td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold">Message</td><td><?php echo nl2br($row->message);?></td>
                                </tr>
                            </tr>
                            <?php //endforeach;?>
						  </tbody>
					  </table>            
					</div>
		</div><!--/span-->
			
    </div><!--/row-->
<?php echo Modules::run('comments/_draw_comments','e',$update_id)?>
<?php //echo modules::run('store_book_categories/_draw_top_nav'); ?>
</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Create Comment</h4>
      </div>
      <div class="modal-body">
            
        <form action="<?php echo base_url('comments/submit');?>" method="post">
          <div class="form-group">
            <label for="comment" class="control-label">Comment:</label>
            <textarea style="width: 98%;" rows="4" class="form-control" id="comment" name="comment"></textarea>
          </div>
          <?php echo form_hidden('comment_type','e');
                echo form_hidden('update_id',$update_id);
         ?>
         <div class="form-group pull-right">
            <button type="button" name="submit" value="Cancel" class="btn btn-default" data-dismiss="modal">Close</button>
            <button  value="Submit" class="btn btn-primary">Send message</button>
         </div>
        </form>
      </div>
    </div>
  </div>
</div>