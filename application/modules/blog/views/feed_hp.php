<h3>The Blog</h3>
<?php 
$this->load->module('timedate');
foreach($query->result() as $row):
    $article_preview = word_limiter($row->page_content,25);
    $thumbnail_name = str_replace('.','_thumb.',$row->picture);
    $thumbnail_path = base_url().'public/images/blog/'.$thumbnail_name;
    $date_published = $this->timedate->get_nice_date($row->date_published,'mini');
    $blog_url = base_url().'blog/article/'.$row->page_url;
?>

<div class="row" style="margin-bottom: 12px;">
    <div class="col-md-3">
        <img src="<?php echo $thumbnail_path?>" alt="<?php echo $thumbnail_name?>" class="img-responsive img-thumbnail">
    </div>
    <div class="col-md-9 col-sm-6 col-xm-12">
        <a href="<?php echo $blog_url?>"><?php echo $row->page_title?></h4></a><h4>
        <p style="font-size: 0.9em;">
            <span style="color: black;"><?php echo $row->author?></span> -
            
            <?php echo $date_published?>

        </p>
        <p><?php echo $article_preview?></p>
    </div>
</div>

<?php endforeach;?>