
<div class="row">
    <div class="col-sm-8">
        <p style="margin-top: 24px">Message sent on <?= $date_created?></p>
        <p style="margin-top: 25px">
            <a href="<?php echo base_url('your_messages/create/'.$code)?>">
                <button type button class="btn btn-default">Reply</button>
            </a>
        </p>
        <h4><?= $subject ?></h4>
        <p><?= strip_tags($message) ?></p>
    
    </div>
</div>