<style>
    .sort{
        list-style: none;
        border: 1px #ccc solid;
        padding: 10px;
        color: #333;
        margin-bottom: 4px;     
    }
</style>
<ul id="sortlist">
    <?php
    $this->load->module('store_categories');
    foreach($query->result() as $row): 
    $num_sub_cats = $this->store_categories->_count_sub_cats($row->id);
    $edit_item_url = base_url('store_categories/create/'.$row->id);
    $view_item_url = base_url('store_categories/view/'.$row->id);
    if($row->parent_cat_id == 0){
        $parent_cat_title = " ";
    }else {
        $parent_cat_title = $this->store_categories->_get_cat_title($row->parent_cat_id);
    }
    ?>
    <li class="sort" id="<?php echo $row->id?>">
        <i class="icon-sort"></i> 
        <?php echo $row->cat_title?>
        <?php echo $parent_cat_title;
            $num_sub_cats = $this->store_categories->_count_sub_cats($row->id);
            $edit_item_url = base_url('store_categories/create/'.$row->id);
            $view_item_url = base_url('store_categories/view/'.$row->id);
            if($row->parent_cat_id == 0){
                $parent_cat_title = " ";
            }else { 
                $parent_cat_title = $this->store_categories->_get_cat_title($row->parent_cat_id);
            }
        ?>
         <?php 
            ($num_sub_cats == 1) ?  $entity = "Category" : $entity = "Categories";
        ?>
        <?php if($num_sub_cats > 1):?>
        <a class="btn btn-info" href="<?php echo base_url('store_categories/manage/'.$row->id)?>">
            <i class="icon-eye-open"></i> <?php echo $num_sub_cats.' Sub '.$entity?>
        </a>
        <a class="btn btn-primary" href="<?php echo $edit_item_url;?>">
			<i class="halflings-icon white edit"></i>  
		</a>
        <?php else:?>
            <?php echo " "?>
        <?php endif?>
    </li>
    <?php endforeach?>
</ul>