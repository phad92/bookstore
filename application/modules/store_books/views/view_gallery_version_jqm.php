<?php 
//echo Modules::run('templates/_draw_breadcrumbs',$breadcrumbs_data);
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
<style>
.ui-bar{
    border: 1px solid silver;
}
</style>
<h2 style="margin-top: 0px; margin-bottom: 4px; "><?php echo $book_title?></h2>
<div class="row" ng-controller="myController">
<div class="ui-grid-d">
    <?php $count=0;foreach($gallery_pics as $thumbnail):
        $count++;
            if($count > 5){
                $count = 1;
            }

            switch ($count) {
                case '1':
                    $block_val = 'a';
                    break;
                case '2':
                    $block_val = 'b';
                    break;
                case '3':
                    $block_val = 'c';
                    break;
                case '4':
                    $block_val = 'd';
                    break;
                case '4':
                    $block_val = 'e';
                    break;
            }
        
        ?>
        <div class="ui-block-<?php echo $block_val?>">
            <div class="ui-bar ui-bar" style="height:30px">
                <img ng-click="change('<?php echo $thumbnail?>')" src="<?php echo $thumbnail?>" width="100%" alt="<?php echo $big_pic?>">
            </div>
        </div>
        <?php endforeach?>
</div><!-- /grid-c -->

  <div class="col-md-3" style="margin-top:24px;">
    <a href="#" data-featherlight="{{ defaultPic }}">
        <img class="img-responsive" src="{{ defaultPic }}" style="height: 450px;">
    </a><br><br>
   <?php 
       if($book_category != ""){
           "<span>Category: $book_category</span><br>";
       }
       if($book_format != ""){
           "<span>Format: $book_format </span><br>";
       }
       ?>
       <h2>Price: <?php echo $currency_symbol.$price?></h2>
        <div style="margin-top:0px; border:1px solid red;">
          <h3 style="margin: 0;">Description</h3>
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
            
            <?php foreach($books->result() as $row):?>
            <div class="col-md-2 col-sm-12 mini-slider img-thumbnail">
                <div class="container mini-container">
                    <img class="align-bottom" src="<?php echo base_url('public/images/books/big_pics/').$row->small_pic?>" style="width: 150px;height: 200px;" alt="<?php echo $row->book_title?>">
                    <p><?php echo $row->book_title?></p>
                    <p>By <?php echo "author's name"?></p>
                </div>
                   
            </div>
            <?php endforeach?>
            
        </div>
    </div>
</div>
<div class="row">
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
                        <!-- <li>
                            <a href="#4a" data-toggle="tab">Background color</a>
                        </li> -->
                    </ul>
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
                    </div>
                    <!-- <div class="tab-pane" id="4a">
                        <h3>We use css to change the background color of the content to be equal to the tab</h3>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
</div>

