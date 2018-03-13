<?php if(isset($flash)){echo $flash;}?>
<div class="row">
  <div class="col-md-4" style="margin-top:24px;">
    <img class="img-responsive" src="<?php echo base_url('public/images/big_pics/'.$big_pic)?>" alt="<?php echo $item_title?>">
  </div>
  <div class="col-md-5">
      <h1><?php echo $item_title?></h1>
      <div class="clearfix">
          <div class="container-fluid">
              <?php echo nl2br($item_description) ?>
          </div>
      </div>
  </div>
  <div class="col-md-3">
      <?php echo Modules::run('cart/_draw_add_to_cart',$update_id)?>
  </div>
</div>