  <style>

  </style>
  <?php 
  //echo Modules::run('homepage_blocks/_draw_blocks')?>
    <div class="container-fluid">
      <div class="row">
        <div class="container-fluid item-rows">
            
            <div class="panel panel-default" style="padding: 0 20px;">
                <div class="panel-body">
                    <?php echo Modules::run('sliders/_draw_latest_books_slider'); ?>
                </div>
            </div>
                
            <?php echo Modules::run('homepage_blocks/_draw_cta_1');  ?>
            
            <div class="panel panel-default" style="padding: 0 20px;">
                <div class="panel-body">
                    <?php echo Modules::run('sliders/_draw_popular_books'); ?>
                </div>
            </div>

            <?php  echo Modules::run('homepage_blocks/_draw_cta_2');?>

            <!-- <div echo Modules::run('homepage_blocks/_draw_cta_1');class="row">
                <?php  //echo Modules::run('homepage_blocks/_draw_cta_1');?>
            </div> -->
            
            <div class="row">
                <h3>Popular Books</h3>
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
                        <div class="product-slide">
                          <div>
                              <a href="http://localhost/store/Show/book/Good-Housekeeping-400-Flat-Tummy-Recipes-Tips"><img class="img-responsive" title="Good Housekeeping 400 Flat-Tummy Recipes & Tips" src="http://localhost/store/public/images/books/big_pics/9781618372383_p0_v2_s600x595.jpg" style="width: 170px; height: 200px;"></a>
                          </div>
                          <h6>Good Housekeeping 400 Flat-Tummy Recipes & Tips</h6>
                          <div style="clear: both; color:red;font-weight: bold;">
                              $199.00                <span style="color: #999; text-decoration: line-through;font-weight: bold;">$250.00</span>
                          </div>
                        </div>
                        <div class="product-slide">
                          <div>
                              <a href="http://localhost/store/Show/book/Good-Housekeeping-400-Flat-Tummy-Recipes-Tips"><img class="img-responsive" title="Good Housekeeping 400 Flat-Tummy Recipes & Tips" src="http://localhost/store/public/images/books/big_pics/9781618372383_p0_v2_s600x595.jpg" style="width: 170px; height: 200px;"></a>
                          </div>
                          <h6>Good Housekeeping 400 Flat-Tummy Recipes & Tips</h6>
                          <div style="clear: both; color:red;font-weight: bold;">
                              $199.00                <span style="color: #999; text-decoration: line-through;font-weight: bold;">$250.00</span>
                          </div>
                        </div>
                        <div class="product-slide">
                          <div>
                              <a href="http://localhost/store/Show/book/Good-Housekeeping-400-Flat-Tummy-Recipes-Tips"><img class="img-responsive" title="Good Housekeeping 400 Flat-Tummy Recipes & Tips" src="http://localhost/store/public/images/books/big_pics/9781618372383_p0_v2_s600x595.jpg" style="width: 170px; height: 200px;"></a>
                          </div>
                          <h6>Good Housekeeping 400 Flat-Tummy Recipes & Tips</h6>
                          <div style="clear: both; color:red;font-weight: bold;">
                              $199.00                <span style="color: #999; text-decoration: line-through;font-weight: bold;">$250.00</span>
                          </div>
                        </div>
                        <div class="product-slide">
                          <div>
                              <a href="http://localhost/store/Show/book/Good-Housekeeping-400-Flat-Tummy-Recipes-Tips"><img class="img-responsive" title="Good Housekeeping 400 Flat-Tummy Recipes & Tips" src="http://localhost/store/public/images/books/big_pics/9781618372383_p0_v2_s600x595.jpg" style="width: 170px; height: 200px;"></a>
                          </div>
                          <h6>Good Housekeeping 400 Flat-Tummy Recipes & Tips</h6>
                          <div style="clear: both; color:red;font-weight: bold;">
                              $199.00                <span style="color: #999; text-decoration: line-through;font-weight: bold;">$250.00</span>
                          </div>
                        </div>
                        <div class="product-slide">
                          <div>
                              <a href="http://localhost/store/Show/book/Good-Housekeeping-400-Flat-Tummy-Recipes-Tips"><img class="img-responsive" title="Good Housekeeping 400 Flat-Tummy Recipes & Tips" src="http://localhost/store/public/images/books/big_pics/9781618372383_p0_v2_s600x595.jpg" style="width: 170px; height: 200px;"></a>
                          </div>
                          <h6>Good Housekeeping 400 Flat-Tummy Recipes & Tips</h6>
                          <div style="clear: both; color:red;font-weight: bold;">
                              $199.00                <span style="color: #999; text-decoration: line-through;font-weight: bold;">$250.00</span>
                          </div>
                        </div>
                       
                        
                       
              
                        <!-- <div> Your Content </div>
                        <div> Your Content </div>
                        <div> Your Content </div>
                        <div> Your Content </div>
                        <div> Your Content </div>
                        <div> Your Content </div> -->
                </div>
            </div>
        </div>
      </div>
    </div>


<script type="text/javascript">
  $(document).ready(function(){
    // $('.owl-carousel').owlCarousel();
    var owl = $('.owl-carousel');
    owl.owlCarousel({
        loop:true,
        margin:10,
        responsiveClass:true,
        responsive:{
            0:{
                items:1,
                nav:true,
            },
            600:{
                items:3,
                nav:true
            },
            1000:{
                items:5,
                nav:true,
                loop:false
            }
        },
        navText : ['<i class="glyphicon glyphicon-chevron-left" aria-hidden="true"></i>','<i class="glyphicon glyphicon-chevron-right" aria-hidden="true"></i>']
    });
    owl.on('mousewheel', '.owl-stage', function (e) {
    if (e.deltaY>0) {
        owl.trigger('next.owl');
    } else {
        owl.trigger('prev.owl');
    }
    e.preventDefault();
    });

  });
</script>
