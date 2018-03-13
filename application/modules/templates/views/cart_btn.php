

<a class="cart-button">
    <i class="glyphicon glyphicon-shopping-cart"></i> (<?php echo $num_rows;?>)
</a>

<ul class="dropdown-menu dropdown-cart" role="menu">
<?php 
    $this->load->module('site_settings');
    $cart_url = base_url('cart');
    foreach($cart_info->result() as $row):
        $img_path = base_url('public/images/books/big_pics/'.$row->small_pic);
        $item_name = character_limiter($row->item_title,13);
        $currency_symbol = $this->site_settings->_get_currency_symbol();
        $item_price = number_format($row->item_price,2);
        $price_desc = $currency_symbol.$item_price;
        ?>                          
<li>
    <span class="item">
      <span class="item-left">
          <img src="<?php echo $img_path?>" width="55px" height="60px" alt="<?php echo $row->item_title?>" title="<?php echo $row->item_title?>" class="img-responsive"/>
          <span class="item-info">
              <span><strong><?php echo $item_name?></strong></span>
              <span><?php echo $price_desc?></span>
          </span>
      </span>
      <span class="item-right">
          <a class="pull-right" href="#" style="color: #ad2929;"><i class="glyphicon glyphicon-remove"></i></a>
      </span>
  </span>
</li>
<?php endforeach;?>
<?php if($num_rows !== 0):?>
<li class="divider"></li>
<li><a class="text-center" href="<?php echo $cart_url?>" style="padding: 5px 0; margin: 0;">View Cart</a></li>
<?php endif?>
</ul>
