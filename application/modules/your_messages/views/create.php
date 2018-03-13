<h1><?php 
$form_location = current_url();
echo $headline;
?></h1>
<?php echo validation_errors("<p style='color: red;'>","</p>");

?>
<div class="container">
    <div class="row">
        <div class="col-md-8">
        
            <form class="form-horizontal" action="<?php echo $form_location?>" method="post" style="margin-top: 24px;">
            <?php if($code==""):?>
            <div class="form-group">
                <label for="subject" class="col-sm-2 control-label">Subject</label>
                <div class="col-sm-10">
                <input type="text" name="subject" class="form-control" id="subject" placeholder="Subject" value="<?php echo $subject?>">
                </div>
            </div>
            <?php else:
              echo form_hidden('subject',$subject);
            endif?>
            <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">Message</label>
                <div class="col-sm-10">
                <textarea class="form-control" placeholder="Your Message" id="inputPassword3" name="message"><?php echo $message?></textarea>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-10">
                <div class="checkbox">
                    <label for="">
                        <input type="checkbox" value="1" name="urgent"> <?php 
                            if($urgent == 1){
                                echo "checked";
                            }
                        ?>Urgent
                    </label>
                
                </div>
                </div>
            
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-primary" name="submit" value="Submit">Send Message</button> 
                <button type="submit" class="btn btn-default" name="submit" value="Cancel">Cancel</button>
                <?php echo 
                form_hidden('token', $token);
                ?>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>