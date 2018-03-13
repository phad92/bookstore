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
    foreach($query->result() as $row): 
    $edit_item_url = base_url('btm_nav/create/'.$row->id);
    $delete_item_url = base_url('btm_nav/delete/'.$row->id);
    
    ?>
    <li class="sort" id="<?php echo $row->id?>"><strong>Page URL: </strong><?php echo $row->page_title?> | <strong>Page Title:</strong> <?php echo $row->page_title?>
        <i class="icon-sort"></i> 
       
         <?php 
            // $num_items = $this->homepage_offers->count_where('block_id',$row->id); 
            // ($num_items == 0) ?  $entity = "Homepage Offer" : $entity = "Bottom Navigation";
            
        ?>
       <?php if(!in_array($row->page_id,$special_pages)):?>
        <a class="btn btn-danger" href="<?php echo $delete_item_url;?>">
			<i class="halflings-icon white trash"></i>  
        </a>
        <?php endif?>
    </li>
    <?php endforeach?>
</ul>