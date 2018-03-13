<?php
  function _attempt_make_active($link_text){
    if(current_url() == base_url('Your_account/welcome') AND ($link_text == "Your Messages")){
      echo ' class="active"';
    }
  }
?>
<ul class="nav nav-tabs" style="margin-top: 24px;">
  <li role="presentation" <?php _attempt_make_active('Your Messages')?>>
    <a href="<?php echo base_url('Your_account/welcome')?>">Your Messages</a>
  </li>
  <li role="presentation"><a href="<?php echo base_url('Your_orders/browse')?>">Your Orders</a></li>
  <li role="presentation"><a href="#">Update Your Profile</a></li>
  <li role="presentation"><a href="<?php echo base_url('Your_account/logout')?>">logout</a></li>
</ul>
<?php // echo Modules::run('Enquiries/_draw_customer_inbox',$customer_id);?>