<ol class="breadcrumb" style="margin-top: 10px;">
  <?php 
    foreach ($breadcrumbs_array as $key => $value): ?>
    <li><a href="<?php echo $key?>"><?php echo $value?></a></li>
    

  <?php endforeach?>
  <li class="active"><?php echo $current_page_title?></li>
</ol>