<h1>create account</h1>
<?php 
$form_location = base_url().'your_account/submit';

?>
<form class="form-horizontal" action="<?php echo $form_location?>" method="post">
<?php echo validation_errors('<p style="color: red;">',"</p>");?>
<fieldset>

<!-- Form Name -->
<legend>Please Enter Your Details below</legend>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="username">Username</label>  
  <div class="col-md-4">
  <input id="username" name="username" value="<?php echo $username?>" type="text" placeholder="Username" class="form-control input-md" required="">
    
  </div>
</div>
<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="email">E-mail</label>  
  <div class="col-md-4">
  <input id="email" name="email" value="<?php echo $email?>" type="text" placeholder="Email" class="form-control input-md">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="pword">Password</label>  
  <div class="col-md-4">
  <input id="pword" name="pword" type="password" value="<?php echo $pword?>" placeholder="Password" class="form-control input-md">
    
  </div>
</div>

<!-- Text input-->
<div class="form-group">
  <label class="col-md-4 control-label" for="repeat_pword">Repeat Password</label>  
  <div class="col-md-4">
  <input id="repeat_pword" name="repeat_pword" type="password" value="<?php echo $repeat_pword?>" placeholder="Repeat Password" class="form-control input-md">
    
  </div>
</div>

<!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label" for="create">Create Account?</label>
  <div class="col-md-4">
    <button id="create" name="submit"  value="Submit" class="btn btn-primary">Yes</button>
  </div>
</div>

</fieldset>
</form>
