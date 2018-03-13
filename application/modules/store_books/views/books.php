<!-- <div class="row">
    <div class="container"> -->
       <div class="row">
           <div class="container">
               <?php echo Modules::run('homepage_blocks/_draw_cta_1');?>
           </div>
       </div>
       <?php 
            $second_bit = $this->uri->segment(2);
            $third_bit = $this->uri->segment(3);
            if($third_bit == 'audio'){
                $sort = 'audio';
                $latest_books_url= base_url('store_books/book_type/'.$sort.'/date_created');
            }elseif($third_bit == 'digital'){
                $sort = 'digital';
                $latest_books_url=base_url('store_books/book_type/'.$sort.'/date_created');
            }else{
                $latest_books_url=base_url('store_books/all_books/date_created');
                
            }
            $active = 'primary';
            $default = 'default';
       ?>
       <div class="row">
           <div class="container">
               <div class="clearfix" style="margin-top: 20px;min-height: 45px; padding-top: 5px;">
                   <div class="col-md-12">
                       <!-- <div class="col-md-4"><span class="badge">42</span></div> -->
                       <div class="col-md-8">
                           <span class="badge" style="margin-right: 10px; font-size: 1.1em;"><?php echo $total_items?></span>
                           <a href="<?php echo base_url('store_books/all_books')?>" class="btn btn-<?php ($second_bit == 'all_books') ? print $active : print($default)?>">Featured Books</a>
                           <a href="<?php echo base_url('store_books/book_type/'.$ebook)?>" class="btn btn-<?php ($third_bit == 'digital') ? print $active : print $default?>">Ebooks Books</a>
                           <a href="<?php echo base_url('store_books/book_type/'.$audio)?>" class="btn btn-<?php ($third_bit == 'audio') ? print $active : print $default?>">Audio Books</a>
                           
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    Sort By
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                    <li><a href="<?php echo $latest_books_url?>">Latest</a></li>
                                    <li><a href="#">Most Popular</a></li>
                                    <!-- <li><a href="#">Something else here</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="#">Separated link</a></li> -->
                                </ul>
                            </div>


                           <!-- <div class="well"></div> -->
                        </div>
                       <div class="col-md-4">
                           <input type="text" class="form-control" placeholder="Search Book...">
                       </div>
                   </div>
               </div>
           </div> 
       </div>
    <!-- </div>
</div> -->
<div class="row" style="">
    <div class="container">
        <div class="col-md-4">
            <div class="row" style="margin-top: 45px;">
                <div class="left-panel">
                    <div class="panel panel-defalut">
                        <div class="panel-body">
                            <h3>Categories</h3><hr>
                            <?php echo Modules::run('store_book_categories/_draw_side_nav')?>
                        </div>
                    </div>
                </div>
            </div>
        <!-- <div class="well"></div> -->
    </div>
    <div class="col-md-8">
            <h1><?php //echo $cat_title;?></h1>

            <?php echo $pagination?>
            <div class="panel panel-default">
               <div class="panel-body">
                   <div class="clearfix" style="vertical-align: middle;display: table-cell;">
                       <?php foreach($query->result() as $row):
                           $book_url = base_url().$item_segment.$row->book_url;
                           // echo $book_url;die();
                           ?>
                       <div class="col-md-3 col-sm-6 col-xs-6" style="display: table-cell;padding-top: 10px;height:auto;">
                               <div style="display: block;height: 300px;">
                                   <a href="<?php echo $book_url?>"><img class="img-responsive" title="<?php echo $row->book_title;?>" src="<?php echo base_url('public/images/books/big_pics/').$row->small_pic?>" style="width: 170px; height: 200px;"></a>
                               <h6><?php echo character_limiter($row->book_title, 65);?></h6>
                               <h6><?php $this->load->module('store_books');
                                       $data = $this->store_books->_get_book_authors($row->id);
                                       if(is_array($data)){
                                           $output = 'By '.implode(', ',$data);
                                           echo character_limiter($output,40);
                                       }
                                   ?></h6>
                               </div>
                               <div style="margin-top: 10px;color:red;font-weight: bold;margin-bottom: 0;">
                                   <?php echo "$currency_symbol".number_format($row->book_price,2) ?>
                                   <span style="color: #999; text-decoration: line-through;font-weight: bold;"><?php ($row->was_price > 0) ? print "$currency_symbol".$row->was_price : null;?></span>
                               </div>
                        </div>
                       <?php endforeach?>
                   </div>
               </div>
            </div>
            <!-- </ul> -->
            <!-- </nav>  -->
            <?php echo $pagination?>
    </div>
    </div>
</div>

