<!-- the store_book_categories module is already loaded in the header of the page -->
<style>
    
</style>
<ul id="cat_list">
    <?php  
        $order_by = 'cat_title';
        // print_r($parent_categories);die();
        foreach ($parent_categories as $key => $value):
        $parent_cat_id = $key;
        $parent_cat_title = $value;
        
    ?>
    <li id="<?php echo str_replace(' ','-',$parent_cat_title);?>"><span><?php echo ucwords($parent_cat_title)?> </span><span class="pull-right"><i class="fa fa-chevron-right" aria-hidden="true"></i></span>
        <ul>
            <?php 
                $query = $this->store_book_categories->_get_categories($parent_cat_id,$order_by);
                foreach($query->result() as $row):
                    $cat_title = $row->cat_title;
                    $cat_url = $row->book_cat_url;
            ?>
            <li><span><a href="<?php echo $target_url_start.$cat_url?>"><?php echo ucwords($cat_title)?></a></span></li>
            <?php endforeach?>
        </ul>
   </li>
    <?php endforeach?>
</ul>
<script>
$(function(){
    $('#cat_list').find('span').parent().children('ul').toggle();

    $('#cat_list').find('span').click(function(e){
        $(this).parent().children('ul').toggle();
    });
});
</script>