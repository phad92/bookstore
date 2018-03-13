<div class="row">
           <div class="container">
               <?php echo Modules::run('homepage_blocks/_draw_cta_1');?>
           </div>
       </div>
       <div class="row">
           <div class="container">
               <div class="clearfix" style="margin-top: 20px;min-height: 45px; padding-top: 5px;">
                   <div class="col-md-12">
                       <!-- <div class="col-md-4"><span class="badge">42</span></div> -->
                       <div class="col-md-8">
                           <span class="badge" style="margin-right: 10px; font-size: 1.1em;">124</span>
                           <a href="<?php echo base_url('store_books/all_books')?>" class="btn btn-default">Featured Books</a>
                           <a href="<?php echo base_url('store_books/all_books/')?>" class="btn btn-default">Audio Books</a>
                           <a href="#" class="btn btn-default">Ebooks Books</a>
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    Sort By
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                    <li><a href="#">Best Sellers</a></li>
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