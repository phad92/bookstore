<div data-role="navbar">
    <ul>
        <?php foreach($top_nav_btns as $value):
                if($value['btn_target_url'] == $current_url){
                    $top_btn_css = 'class="ui-btn-active"';
                }else{
                    $top_btn_css = '';
                }

                if($value['text'] == 'Login'){
                    $top_btn_css .= 'rel="external"';
                }
            ?>
        <li><a href="<?php echo $value['btn_target_url']?>" data-icon="<?php echo $value['icon']?>" <?php echo $top_btn_css?>><?php echo $value['text']?></a></li>
        <?php endforeach?>
    </ul>
</div><!-- /navbar -->