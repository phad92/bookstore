
<style>

#carousel-example-generic{
  height: 350px; 
}
#carousel-example-generic .item{
    height: 350px !important;  
}

.item > a > img{
     /* border: 2px solid red; */
}

.carousel-control.left, .carousel-control.right {
   background-image:none !important;
   filter:none !important;
}
</style>
<div id="carousel-example-generic" class="carousel slide" data-ride="carousel" style="">
        <!-- Indicators -->
        <ol class="carousel-indicators">
        <?php   $count = 0;
                foreach($query_slides->result() as $row_slides):
                if($count == 0){
                    $additional_css = 'class="active"';
                }else{
                    $additional_css = '';
                }
            ?>
            
          <li data-target="#carousel-example-generic" data-slide-to="<?php echo $count?>" <?php echo $additional_css?>></li>
        <?php $count++;endforeach?>
        </ol>

        <!-- Wrapper for slides -->
        <div class="carousel-inner" role="listbox">
        <?php   $count = 0;
                foreach($query_slides->result()as $row_slides):
                    $pic_path = base_url('public/images/img/'.$row_slides->picture);
                    $target_url = base_url().$row_slides->target_url;
                    if($count == 0){
                        $additional_css = 'active';
                    }else{
                        $additional_css = '';
                    }
                ?>

          <div class="item <?php echo $additional_css?>">
            <?php if($target_url != ''): ?>
            <a href="<?php echo $target_url;?>">
                <img src="<?php echo $pic_path?>" alt="..." class="img-responsive">
            </a>
            <?php else:?>
                <img src="<?php echo $pic_path?>" alt="..." class="img-responsive">
            <?php endif?>
            <!-- <div class="carousel-caption">
              ...
            </div> -->
          </div>
            <?php $count++;endforeach;?>
          
        </div>

        <!-- Controls -->
        <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
          <!-- <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
          <span class="sr-only">Previous</span> -->
        </a>
        <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
          <!-- <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
          <span class="sr-only">Next</span> -->
        </a>
      </div>