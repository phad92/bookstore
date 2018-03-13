<h1>Thank You</h1>
<p>Your form was successfully submitted.</p>
<p>
    <?php if($shopper_id != ''):?>
    <span id="d"></span> Click  <a href="<?php echo base_url('downloads/show_books/'.$shopper_id)?>">Here</a>
    <?php else:?>
    <span id="d"></span> Click  <a href="<?php echo base_url()?>">Here</a>
    <?php endif?>
</p>
<?php echo $shopper_id;?>
<script>
var seconds =11;
<?php if($shopper_id != ''):?>
var url="<?php echo base_url('downloads/show_books/'.$shopper_id)?>";
<?php else:?>
var url="<?php echo base_url()?>";
<?php endif?>
function redirect(){
 if (seconds <=0){
 // redirect to new url after counter  down.
  window.location = url;
 }else{
  seconds--;
  var msg = "This Page will Redirect to the download page after after "+seconds+" seconds.";
  $('#d').text(msg);
  setTimeout(() => {
      redirect();
  }, 1000);
//  document.getElementById("d").innerHTML = "redirect after "+seconds+" seconds."
//  setTimeout("redirect()", 1000)
 }
}
redirect();
</script>