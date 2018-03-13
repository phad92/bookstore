
 <?php 
        $this->load->module('store_book_categories');
        foreach($parent_categories as $key => $value):
        // $parent_cat_id = $key;
        $parent_cat_title = $value;
    ?>
 <div class="row hr">
     <div style="margin-bottom: -9px !important;">
         <p style="font-size:1.8em;font-weight: normal;margin: 0;">$parent_cat_title</p>
         <p style="text-align: right;margin: 0;">show more</p>
     </div>
     <hr>
     <div class="owl-carousel owl-theme">
             
      <div class="product-slide">
        <div>
            <a href="http://localhost/store/Show/book/Good-Housekeeping-400-Flat-Tummy-Recipes-Tips"><img class="img-responsive" title="Good Housekeeping 400 Flat-Tummy Recipes & Tips" src="http://localhost/store/public/images/books/big_pics/9781618372383_p0_v2_s600x595.jpg" style="width: 170px; height: 200px;"></a>
        </div>
        <h6>Good Housekeeping 400 Flat-Tummy Recipes & Tips</h6>
        <div style="clear: both; color:red;font-weight: bold;">
            $199.00                <span style="color: #999; text-decoration: line-through;font-weight: bold;">$250.00</span>
        </div>
      </div>
             
     </div>
 </div>

<ul class="nav navbar-nav">
    <?php 
        $this->load->module('store_book_categories');
        foreach($parent_categories as $key => $value):
        // $parent_cat_id = $key;
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
      <li><a href="#">One more separated link</a></li> -->
    </ul>
  </li>
  <?php endforeach?>
</ul>