
<ul class="nav navbar-nav">
    <?php 
        $this->load->module('store_book_categories');
        foreach($parent_categories as $key => $value):
        $parent_cat_id = $key;
        $parent_cat_title = $value;
    ?>
  <li class="dropdown">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $parent_cat_title?><?php //echo $parent_cat_title?> <span class="caret"></span></a>
    <ul class="dropdown-menu">
        <?php 
            $query = $this->store_book_categories->get_where_custom('book_parent_cat_id',$parent_cat_id);
            foreach($query->result() as $row):
                $cat_url = $row->book_cat_url;
        ?>
      <li class="dropdown">
        <a href="<?php  echo $target_url_start.$cat_url;?>">
          <?php echo $row->cat_title?>
        </a>
        
      </li>
        <?php endforeach?>
      <!-- <li><a href="#">Another action</a></li>
      <li><a href="#">Something else here</a></li>
      <li role="separator" class="divider"></li>
      <li><a href="#">Separated link</a></li>
      <li role="separator" class="divider"></li>
      <li><a href="#">One more separated link</a></li> -->
    </ul>
  </li>
  <?php endforeach?>
</ul>