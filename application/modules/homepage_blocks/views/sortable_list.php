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
    $this->load->module('homepage_blocks');
    $this->load->module('homepage_offers');
    foreach($query->result() as $row): 
    $edit_item_url = base_url('homepage_blocks/create/'.$row->id);
    $view_item_url = base_url('homepage_blocks/view/'.$row->id);
    
    ?>
    <li class="sort" id="<?php echo $row->id?>"><?php echo $row->block_title?>
        <i class="icon-sort"></i> 
        <?php 
            $edit_item_url = base_url('homepage_blocks/create/'.$row->id);
            $view_item_url = base_url('homepage_blocks/view/'.$row->id);
            // $block_title = $row->block_title;
        ?>
         <?php 
            $num_items = $this->homepage_offers->count_where('block_id',$row->id); 
            ($num_items == 0) ?  $entity = "Homepage Offer" : $entity = "Homepage Offers";
            
        ?>
        <a class="btn btn-info" href="<?php echo base_url()?>">
            <i class="icon-eye-open"></i> <?php //echo //'hello'//$num_sub_cats.' Sub '.$entity?>
        </a>
        <a class="btn btn-primary" href="<?php echo $edit_item_url;?>">
			<i class="halflings-icon white edit"></i>  
		</a>
    </li>
    <?php endforeach?>
</ul>