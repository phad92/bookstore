<?php echo form_open($form_location)?>
<div class="ui-body ui-body-a ui-corner-all">
<h1>Contact Us</h1>
    <?php echo validation_errors("<p style='color:red;'>","</p>")?>
    <label for="yourname">:</label>
    <input type="text" name="yourname" id="yourname" placeholder="Your Name" value="<?php echo $yourname?>">
    
    <label for="email">Email:</label>
    <input type="text" name="email" id="email" placeholder="Email" value="<?php echo $email?>">
    
    <label for="telnum">Telephone Number:</label>
    <input type="text" name="telnum" id="telnum" placeholder="Telephone Number" value="<?php echo $telnum?>">

    <label for="message">Message:</label>
    <textarea cols="40" rows="8" name="message" id="message"><?php echo $message ?></textarea>

    <button type="submit" name="submit" value="Submit" id="submit-5" class="ui-shadow ui-btn ui-corner-all ui-mini">Submit</button>
</div>
<?php echo form_close()?> 