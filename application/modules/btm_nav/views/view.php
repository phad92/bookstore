<div class="row">


<h1><?php echo $cat_title;?></h1>
<?php echo $pagination?>
    <?php foreach($query->result() as $row):
         $book_url = base_url().$item_segment.$row->book_url;
        // echo $book_url;die();
        ?>
    <div class="col-md-2 col-sm-6 col-xs-6 img-thumbnail" style="min-height:300px;margin:4px;">
            <div>
                <a href="<?php echo $book_url?>"><img class="img-responsive" title="<?php echo $row->book_title;?>" src="<?php echo base_url('public/images/books/big_pics/').$row->small_pic?>" style="width: 170px; height: 200px;"></a>
            </div>
            <h6><?php echo $row->book_title?></h6>
            <div style="clear: both; color:red;font-weight: bold;">
                <?php echo "$currency_symbol".number_format($row->book_price,2) ?>
                <span style="color: #999; text-decoration: line-through;font-weight: bold;"><?php ($row->was_price > 0) ? print "$currency_symbol".$row->was_price : null;?></span>
            </div>
        </div>
    <?php endforeach?>
    
</div>