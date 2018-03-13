
<?php 
    if($customer_id > 0){
      include('customer_panel_top.php');
    }
   echo Modules::run('enquiries/_draw_customer_inbox',$customer_id);
    // echo anchor('your_account/logout','logout')?>