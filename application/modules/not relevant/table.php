<table class="table table-striped table-bordered bootstrap-datatable datatable">
						  <thead>
							  <tr>
								  <th>Category Title</th>
								  <th>Parent Category</th>
								  <th>Sub Category</th>
								  <th>Actions</th>
							  </tr>
						  </thead>   
						  <tbody>
                        <?php 
                            $this->load->module('store_categories');
                            foreach($query->result() as $row): 
                            $num_sub_cats = $this->store_categories->_count_sub_cats($row->id);
                            $edit_item_url = base_url('store_categories/create/'.$row->id);
                            $view_item_url = base_url('store_categories/view/'.$row->id);
                            if($row->parent_cat_id == 0){
                                $parent_cat_title = "-";
                            }else {
                                $parent_cat_title = $this->store_categories->_get_cat_title($row->parent_cat_id);
                            }
                            ?>
							<tr>
								<td><?php echo $row->cat_title?></td>
								<td><?php echo $parent_cat_title?></td>
								<td>
                                    
                                    <?php 
                                        ($num_sub_cats == 1) ?  $entity = "Category" : $entity = "Categories";
                                    ?>
                                    <?php if($num_sub_cats > 1):?>
                                    <a class="btn btn-info" href="<?php echo base_url('store_categories/manage/'.$row->id)?>">
                                        <i class="icon-eye-open"></i> <?php echo $num_sub_cats.' '.$entity?>
                                    </a>
                                    <?php else:?>
                                        <?php echo "-"?>
                                    <?php endif?>
                                </td>
								
								<td class="center">
									<a class="btn btn-success" href="#">
										<i class="halflings-icon white zoom-in"></i>  
									</a>
									<a class="btn btn-primary" href="<?php echo $edit_item_url;?>">
										<i class="halflings-icon white edit"></i>  
									</a>
								</td>
							
								
							</tr>
                            <?php endforeach;?>
						  </tbody>
					  </table>  