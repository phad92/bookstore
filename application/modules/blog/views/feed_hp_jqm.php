<h3 class="ui-bar ui-bar-a">Blog Feed</h3>
<?php
$this->load->module('timedate');
foreach($query->result() as $row):
    $article_preview = word_limiter($row->page_content,25);
    $thumbnail_name = str_replace('.','_thumb.',$row->picture);
    $thumbnail_path = base_url().'public/images/blog/'.$thumbnail_name;
    $date_published = $this->timedate->get_nice_date($row->date_published,'mini');
    $blog_url = base_url().'blog/article/'.$row->page_url;
?>
<div data-role="collapsibleset" data-theme="a" data-content-theme="a">
    <div data-role="collapsible">
        <h3><?php echo $row->page_title?></h3>
        <p style="font-size: 0.9em;">
            <?php echo $row->author?> -
            <span style="color: black;"><?php echo $date_published?></span>
        </p>
        <p><?php echo $article_preview?></p>
        <p>
            <a href="<?php echo $blog_url?>">Read More</a>
        </p>
    </div>
</div>
<?php endforeach;?>