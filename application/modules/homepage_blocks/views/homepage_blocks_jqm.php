<?php
    $count = 0;
    $this->load->module('homepage_offers');
    $this->load->module('site_settings');
    $item_segments = $this->site_settings->_get_item_segments();
    foreach($query->result() as $row):
        $count++;
        $num_items_on_block = $this->homepage_offers->count_where('block_id',$row->id);
        if($count > 4){
          $count = 1;  
        }
?>
<?php
  // check if items in special offer blocks > 1
  if($num_items_on_block > 0):?>
    <h3 class="ui-bar ui-bar-a"><?php echo $row->block_title;?></h3>
          <?php
            //  $this->homepage_offers->_draw_offers($row->id,$theme,$item_segments);
            $block_data['block_id'] = $row->id;
            $block_data['item_segments'] = $item_segments;
            echo modules::run('homepage_offers/_draw_offers',$block_data,TRUE);
          ?>
      <?php endif;?>
    <?php endforeach;?>
    