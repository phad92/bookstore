
<?php if(isset($flash)) {echo $flash;}?>
<div>
    <span style="margin-button: 30px;"><h1>Your <?php echo $folder_type;?></h1></span>
    <a href="<?php echo base_url('your_messages/create')?>" class="btn btn-primary">Compose Message</a>
</div>
<div style="margin-top: 30px">
    <div class="row-fluid sortable">		
				<div class="box span12">
					<div class="box-header" data-original-title>
						<div class="box-icon">
							<a href="#" class="btn-setting"><i class="halflings-icon white wrench"></i></a>
							<a href="#" class="btn-minimize"><i class="halflings-icon white chevron-up"></i></a>
							<a href="#" class="btn-close"><i class="halflings-icon white remove"></i></a>
						</div>
					</div>
					<div class="box-content">
						<table class="table table-striped table-bordered bootstrap-datatable datatable">
						  <thead>
							  <tr style="background: #666;color: #fff">
								  <td>&nbsp;</td>
								  <td>Date Sent</td>
								  <td>Sent By</td>
								  <td>Subject</td>
								  <td>Actions</td>
							  </tr>
						  </thead>   
						  <tbody>

                        <?php
                            $this->load->module('site_settings');
                            $this->load->module('store_accounts'); 
                            $team_name = $this->site_settings->_get_support_team_name();
                            $this->load->module('timedate'); 
                            foreach($query->result() as $row): 
                                $view_url = base_url('your_messages/view/'.$row->code);
                                $opened = $row->opened;
                                $data['firstname'] = (empty($row->firstname))? null: print $row->firstname;
                                $data['lastname'] = (empty($row->lastname))? null: print $row->lastname;
                                $data['company'] = $row->company;
                                
                                if($opened == 1){
                                    $icon = "<i class='glyphicon glyphicon-envelope'></i>";
                                }else{
                                    $icon = "<i class='glyphicon glyphicon-envelope' style='color: orange'></i>";
                                }
                                $date_sent = $this->timedate->get_nice_date($row->date_created,'mini');
                                if($row->sent_by == 0){
                                    $sent_by = $team_name;
                                }else{
                                    $sent_by = $this->store_accounts->_get_customer_name($row->sent_by,$data);
                                }
                            ?>
							<tr>
								<td class="span1"><?php echo $icon;?></td>
								<td><?php echo $date_sent;?></td>
								<td><?php echo $sent_by;?></td>
								<td><?php echo $row->subject;?></td>
								
								
								<td class="center span1">
									<!-- <a class="btn btn-success" href="#">
										<i class="halflings-icon white zoom-in"></i>  
									</a> -->
									<a class="btn btn-xs btn-default" href="<?php echo $view_url;?>">
										<!-- <i class="glyphicon glyphicon-edit"></i> -->
                                        view
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

	