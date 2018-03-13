<?php 
//echo Modules::run('templates/_draw_breadcrumbs',$breadcrumbs_data);
if(isset($flash)){echo $flash;}
?>
<style>
.ui-bar{
    border: 1px solid silver;
}
</style>
<h2 style="margin-top: 0px; margin-bottom: 4px; "><?php echo $book_title?></h2>
<div class="row">
  <div class="col-md-3" style="margin-top:24px;">
    <a href="#" data-featherlight="<?php echo base_url('public/images/books/big_pics/').$big_pic?>">
        <img class="img-responsive" src="<?php echo base_url('public/images/books/big_pics/').$big_pic?>" style="height: 450px;">
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

