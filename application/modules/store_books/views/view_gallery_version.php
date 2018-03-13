<?php 
echo Modules::run('templates/_draw_breadcrumbs',$breadcrumbs_data);
if(isset($flash)){echo $flash;}
?>
<script type="text/javascript">
    var myApp = angular.module('myApp',[]);
    myApp.controller('myController',['$scope',function($scope){
        $scope.defaultPic = '<?php echo base_url('public/images/books/big_pics/'.$big_pic)?>';
        $scope.change = function(newPic){
            $scope.defaultPic = newPic;
        }
    }])
</script>
<div class="row" ng-controller="myController">
  <div class="col-md-1" style="margin-top:24px;">
    <?php foreach($gallery_pics as $thumbnail):?>
        <img class="img-responsive" ng-click="change('<?php echo $thumbnail?>')" src="<?php echo $thumbnail?>" width="100" alt="<?php echo $big_pic?>">
    <?php endforeach?>
  </div>
  <div class="col-md-3" style="margin-top:24px;">
    <a href="#" data-featherlight="{{ defaultPic }}">
        <img class="img-responsive" src="{{ defaultPic }}" style="height: 450px;">
    </a>
  </div>
  <div class="col-md-5">
      <div class="container-fluid">
        <h3><?php echo $book_title?></h3>
                <span style="color: silver;">By </span> <?php echo 'author'?>
      </div><hr>
      <div class="clearfix">
          <div class="container-fluid">
            <?php 
            if($book_category != ""){
                "<span>Category: $book_category</span><br>";
            }
            if($book_format != ""){
                "<span>Format: $book_format </span><br>";
            }
            ?>
            <!-- <span>Price: <?php //echo $currency_symbol.$price?></span><br> -->
              <h4>Description</h4>
              <?php echo nl2br(htmlspecialchars_decode($book_description)) ?>
          </div>
      </div>
  </div>
  <div class="col-md-3">
      <?php echo Modules::run('cart/_draw_add_to_cart',$update_id)?>
  </div>
</div> 
</br></br>
<div class="row">
    <div class="container">
        <div class="row">
            <h3>Readers Also Buy</h3><hr>
            <div class="owl-carousel owl-theme">
                <?php foreach($books->result() as $row):
                    $book_url = base_url().$url_segment.$row->book_url;
                    ?>
                <div class="product-slide">
                    <div>
                        <a href="<?php echo $book_url?>"><img class="img-responsive" title="<?php echo $row->book_title?>" src="<?php echo base_url('public/images/books/big_pics/').$row->small_pic?>" style="width: 170px; height: 200px;"></a>
                    </div>
                    <h6><?php echo $row->book_title?></h6>
                    <div style="clear: both; color:red;font-weight: bold;">
                        <?php echo $book_price;?> <span style="color: #999; text-decoration: line-through;font-weight: bold;"><?php echo $was_price?></span>
                    </div>
                </div>
                <?php endforeach?>
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
<!-- <div class="row">
    <div class="container">
        <div class="row nav-tab-row">
            <div id="exTab1" class="container">	
                <div class="col-md-8 col-sm-12 col-md-offset-2">
                    <ul  class="nav nav-pills">
                        <li class="active">
                            <a  href="#1a" data-toggle="tab">Overview</a>
                        </li>
                        <li>
                            <a href="#2a" data-toggle="tab">Book Details</a>
                        </li>
                        <li>
                            <a href="#3a" data-toggle="tab">About the Author</a>
                        </li>
                        <!- <li>
                            <a href="#4a" data-toggle="tab">Background color</a>
                        <!- </li> -->
                    <!-- </ul>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-sm-12 col-md-offset-3">
                <div class="tab-content clearfix">
                    <div class="tab-pane active" id="1a">
                        <h3>Content's background color is the same for the tab</h3>
                    </div>
                    <div class="tab-pane" id="2a">
                        <h3>We use the class nav-pills instead of nav-tabs which automatically creates a background color for the tab</h3>
                    </div>
                    <div class="tab-pane" id="3a">
                        <h3>We applied clearfix to the tab-content to rid of the gap between the tab and the content</h3>
                    </div> --> 
                    <!-- <div class="tab-pane" id="4a">
                        <h3>We use css to change the background color of the content to be equal to the tab</h3>
                    </div> -->
                <!-- </div>
            </div>
        </div>
    </div> -->
<!-- </div> -->

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
