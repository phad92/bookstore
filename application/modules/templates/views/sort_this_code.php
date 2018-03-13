<?php 
    $first_bit = $this->uri->segment(1);
    $third_bit = $this->uri->segment(3);

    if($third_bit != ""){
        $start_of_target_url = '../../';
    }else {
        $start_of_target_url = '../';
    }
?> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
    // alert('hello world');
    $('#sortlist').sortable({
        stop: function(event,ui){saveChanges()}
    });
    $('#sortlist').disableSelection();
});
    

function saveChanges(){
    var num = $('#sortlist > li').size();
    var dataString = "number=" + num;
    for(var x=1; x <= num; x++){
        var catid = $('#sortlist li:nth-child('+ x +')').attr('id');
        dataString = dataString + "&order" + x+ "=" + catid;
    }
    // $.post("<?php //echo $start_of_target_url.$first_bit?>/sort",{data: dataString},function(res){
        
    //     console.log(res);
       
    //  })
     $.ajax({
         url: "<?php echo $start_of_target_url.$first_bit?>/sort",
         type: "POST",
         data: dataString,
         success:function(res){
             console.log(res)
         }
     });
     console.log(dataString)
    return false;
}
</script>