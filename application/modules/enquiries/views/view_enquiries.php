
<style>
	.urgent{
		color: red;
	}
</style>
<?php if(isset($flash)) print $flash;?>
<div>
    <span style="margin-button: 30px;"><h1>Your <?php echo $folder_type;?></h1></span>
    <a href="<?php echo base_url('enquiries/create')?>" class="btn btn-primary">Compose Message</a>
</div>
<div style="margin-top: 30px">
    <div class="row-fluid sortable">		
				<div class="box span12">
					<div class="box-header" data-original-title>
						<h2><i class="halflings-icon white briefcase"></i><span class="break"></span><?php echo $folder_type?></h2>
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
									<td>&nbsp;</td>
									<td>Ranking</td>
									<td>Date Sent</td>
									<td>Sent By</td>
									<td>Subject</td>
									<td>Actions</td>
							  	</tr>
						  </thead>   
						  <tbody>

                        <?php
                            $this->load->module('store_accounts'); 
                            $this->load->module('timedate'); 
                            foreach($query->result() as $row): 
                                $view_url = base_url('enquiries/view/'.$row->id);
                                $opened = $row->opened;
                                $data['firstname'] = (empty($row->firstname))? null: $row->firstname;
                                $data['lastname'] = (empty($row->lastname))? null: $row->lastname;
                                $data['company'] = $row->company;
                                $ranking = $row->ranking;
                                if($opened == 1){
                                    $icon = "<i class='icon-envelope'></i>";
                                }else{
                                    $icon = "<i class='icon-envelope-alt' style='color: orange'></i>";
                                }
                                $date_sent = $this->timedate->get_nice_date($row->date_created,'full');
								$urgent = $row->urgent;
								if($row->sent_by == 0){
                                    $sent_by = "Admin";
                                }else{
									if(!is_numeric($row->sent_by)){
										$sent_by = $this->store_accounts->_get_customer_name($row->sent_by,$data);
									}
                                }
                            ?>
							<tr <?php if($urgent == 1){echo " class='urgent'";}?>>
								<td class="span1"><?php echo $icon;?></td>
								<td style="text-align: center"><?php if($ranking < 1){
									echo '-';
									}else{
										for($i = 0;$i < $ranking;$i++){

											echo "<i class='icon-star'></i>";
										}
									};?></td>
								<td><?php echo $date_sent;?></td>
								<td><?php echo $sent_by;?></td>
								<td><?php echo $row->subject;?></td>
								
								
								<td class="center span1">
									<!-- <a class="btn btn-success" href="#">
										<i class="halflings-icon white zoom-in"></i>  
									</a> -->
									<a class="btn btn-info" href="<?php echo $view_url;;?>">
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

	