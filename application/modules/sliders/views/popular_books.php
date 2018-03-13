
 
 <?php if($query->num_rows() > 0):?>
 <div class="row" style="margin-top:24px;">
     <div style="margin-bottom: -9px !important;">
         <p style="font-size:1.8em;font-weight: normal;margin: 0;">Popular Books</p>
         <a href="#"><p style="text-align: right;margin: 0;">show more</p></a>
     </div>
     <hr>
     <div class="owl-carousel owl-theme">
             
     <?php 
        $this->load->module('store_book_categories');
        foreach($query->result() as $row):
        // $parent_cat_id = $key;
        $book_url = $url_segment.$row->book_url;
        $book_title = $row->book_title;
        $img_url = base_url('public/images/books/big_pics/'.$row->small_pic);
        // $parent_cat_title = $value;
        $was_price = $row->was_price;
        $price = $row->book_price;
    ?>
      <div class="product-slide">
        <div>
            <a href="<?php echo $book_url;?>"><img class="img-responsive" title="<?php echo $book_title?>" src="<?php echo $img_url?>" style="width: 170px; height: 200px;"></a>
        </div>
        <h6><?php echo $book_title?></h6>
        <div style="clear: both; color:red;font-weight: bold;">
            <?php echo $price?>                <span style="color: #999; text-decoration: line-through;font-weight: bold;"><?php echo $was_price?></span>
        </div>
      </div>
             
      <?php endforeach?>
     </div>
 </div>
 <?php endif?>