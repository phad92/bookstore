<style>
    .map-responsive{
        overflow:hidden;
        padding-bottom: 56.25%;
        position: relative;
        height: 0;
    }

    .map-responsive iframe{
        left:0;
        top:0;
        height: 100%;
        width: 100%;
        position: absolute;
    }
</style>
<div class="container">
    <div class="row">
    <div class="col-md-12">
        <h1>Contact Us</h1>
        <div class="" style="clear: both;">
            <div class="container-fluid">
                <div class="container">
                    <div class="row">
                            <div class="col-md-8" style="padding: 0;margin: 0;">
                                <div class="well well-sm">
                                <?php echo validation_errors("<p style='color:red;'>","</p>")?>
                                    <form action="<?php echo $form_location?>" method="post">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="yourname">
                                                    Name</label>
                                                <input type="text" value="<?php echo $yourname?>" name="yourname" class="form-control" id="yourname" placeholder="Enter yourname" required="required" />
                                            </div>
                                            <div class="form-group">
                                                <label for="email">
                                                    Email Address</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span>
                                                    </span>
                                                    <input type="text" value="<?php echo $email?>" class="form-control" id="email" name="email" placeholder="Enter email"  /></div>
                                            </div>
                                            <div class="form-group">
                                                <label for="telnum">
                                                    Phone</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon"><span class="glyphicon glyphicon-phone"></span>
                                                    </span>
                                                    <input type="text" value="<?php echo $telnum?>" name="telnum" class="form-control" id="telnum" placeholder="Enter telnum"  /></div>
                                            </div>
                                            <!-- <div class="form-group">
                                                <label for="subject">
                                                    Subject</label>
                                                <select id="subject" name="subject" class="form-control" required="required">
                                                    <option value="na" selected="">Choose One:</option>
                                                    <option value="service">General Customer Service</option>
                                                    <option value="suggestions">Suggestions</option>
                                                    <option value="product">Product Support</option>
                                                </select>
                                            </div> -->
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name">
                                                    Message</label>
                                                <textarea name="message" id="message" class="form-control" rows="9" cols="25" required="required"
                                                    placeholder="Message"><?php echo $message?></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-primary pull-right" name="submit" value="Submit" id="btnContactUs">
                                                Send Message</button>
                                        </div>
                                    </div>
                                    </form>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <form>
                                <legend><span class="glyphicon glyphicon-globe"></span> Our office</legend>
                                <address>
                                    <strong><?php echo $our_name;?></strong><br>
                                    <?php echo $our_address;?>
                                    
                                </address>
                                <address>
                                    <strong>Telephone</strong><br>
                                    <?php echo $our_telnum;?>
                                </address>
                                </form>
                            </div>
                    </div>
                </div> 
            </div>
        </div>
    </div>
    
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="map-responsive">
                <?php echo $map_code?>
            </div>
        </div>
    </div>
</div>
