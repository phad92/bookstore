<div class="ui-body">
    <ul data-role="listview">
    <?php
        foreach ($query->result() as $row):
        $book_title = $row->book_title;
        $small_pic = $row->big_pic;
        $book_price = $row->book_price;
        $was_price = $row->was_price;
        $item_page = base_url().$item_segments.$row->book_url;
        $small_pic_path = base_url().'public/images/books/big_pics/'.$small_pic;
        $book_price = number_format($book_price,2);
        $currency_symbol = $this->site_settings->_get_currency_symbol();
    ?>
    <li>
        <a href="<?php echo $item_page;?>" rel="external" class="cars" id="bmw">
            <img src="<?php echo $small_pic_path?>" alt="<?php echo $book_title;?>">
                <h2><?php echo $book_title;?></h2>
                <p>Our Price <?php echo $currency_symbol.$book_price;?>
            </p>
        </a>
    </li>
    <?php endforeach;?>
    </ul>
</div>
