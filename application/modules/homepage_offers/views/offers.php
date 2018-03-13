
<?php
    foreach ($query->result() as $row):
     $book_title = $row->book_title;
     $small_pic = $row->small_pic;
     $book_price = $row->book_price;
     $was_price = $row->was_price;
     $item_page = base_url().$item_segments.$row->book_url;
     $small_pic_path = base_url().'public/images/books/big_pics/'.$small_pic;
     $book_price = number_format($book_price,2);
     $currency_symbol = $this->site_settings->_get_currency_symbol();
?>

 <div class="col-xs-3 special-offers">
  <div class="offer offer-<?php echo $theme?> ">
    <div class="shape">
      <div class="shape-text">
        top								
      </div>
    </div>
    <div class="offer-content">
      <h4 class="lead" style="margin: 0;padding:10px 0; text-align: left; font-size: 1.2em;">
        <?php echo $currency_symbol.$book_price;?>
      </h4>					
      <p>
      <a href="<?php echo $item_page;?>">
        <img src="<?php echo $small_pic_path?>" alt="<?php echo $book_title?>" title="<?php echo $book_title?>" class="img-responsive">
      
      </a>
        <a href="<?php echo $item_page;?>"><?php echo $book_title;?></a>
      </p>
    </div>
  </div>
</div>
  
    <?php endforeach;?>