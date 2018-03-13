<?php echo Modules::run('checkout/index')?>
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        <?php //foreach($query->result() as $row):?>
        <p><?php //echo $row->item_title?></p>
        <?php //endforeach?>
        <div class="row">
            <h1>Email Confirmation</h1>
            <style>
                #confirm-email{
                    -webkit-box-shadow: none !important;
                    -moz-box-shadow: none !important;
                    box-shadow: none !important;
                    outline: none !important;
                    border: none !important;
                    background-color: #f5f5f5 !important;
                }

                #edt-email{
                    background-color: #e2e2e2 !important;
                }

                .add-focus {
                    border-color:black;
                    /* box-shadow: none; */
                    outline: none !important;
                    border: none !important;
                    background-color: #f5f5f5 !important;
                    box-shadow: 0 0 5px black !important;
                }
            </style>
            <div class="disclaimer-block">
                <h3 class="text-center"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i>Warning</h3>
                <p>The download link book(s) will be sent to this email address. Please <b>Confirm</b> ensure it is the right email address. Thank You!!!</p>
            </div>
                
            <div class="clearfix" style="border: 2px solid black; padding: 12px; border-radius: 5px;">
                <!-- <p style="font-size: 24px; font-weight: bold; margin: 0;"><?php //echo $email;?></p> -->
                <form action="">
                    <div class="input-group" id="change-email">
                        <input type="text" class="form-control input-lg no-border-radius" id="confirm-email" value="<?php echo $email?>">
                        <span class="input-group-btn">
                            <button class="btn btn-default btn-lg no-border-radius" type="button" id="edt-email">Change</button>
                        </span>
                    </div><!-- /input-group -->
                </form>
            </div>

        </div>
        <div class="row mini-cart">
            <div class="container-fluid" style="padding: 50px;">
                <div class="row">
                    <div>
                        
                        <a href="<?php echo base_url('checkout/complete_purchase')?>" class="btn btn-lg btn-default btn-block">Complete Purchase</a>
                        <h1 class="text-center">Order Confirmation</h1><hr><br>
        
                    </div>
                    
                    <?php 
                    // print_r($query->result());die();
                        $num_rows = $query->num_rows();
                     foreach($query->result() as $row):
                        $book_title = character_limiter($row->item_title, 50);
                        $format = $row->format;
                        $image_url = base_url('public/images/books/big_pics/'.$row->small_pic);
                        $book_url = base_url($url_segments.$row->book_url);
                    ?>
                        <div class="row mini-cart-items">
                            <div class="col-md-9 col-sm-6 col-xm-12">
                                <a href="<?php echo $book_url?>"><h4><?php echo $book_title?></h4></a>
                                <p style="font-size: 0.9em;">
                                    <span style="color: black;"><b><?php echo ucfirst($row->format)?></b></span><br>
                                    <span style="color: black;"><?php echo $row->item_price?></span>
                                </p>
                            </div>
                        </div>
                        <hr>
                    <?php endforeach?>
                </div>
                <div class="row">
                    <div>
                       <div class="row calc-summary">
                           <div class="col-xs-8 calc_summary_labels">
                                <h3>Billing Address For:</h3>
                                <p><?php echo ucwords($firstname). ' '.ucwords($lastname)?><br>
                                <span><?php echo $country?></span><br>
                                <span><?php echo $town.','.$address1?></span><br>
                                <span><?php echo $address2?></span><br>
                                <?php echo $telnum?></p>
                           </div>
                       </div>
                   </div>
                </div>
                
                <div class="row">
                    <div>
                       <div class="row calc-summary">
                           <div class="col-xs-8 calc_summary_labels">
                              Number of Items
                           </div>
                           <div class="col-xs-4 calc_summary_values">
                                <?php echo $num_rows;?>
                           </div>
                       </div>
                       <div class="row calc-summary">
                           <div class="col-xs-8 calc_summary_labels">
                              Sub Total
                           </div>
                           <div class="col-xs-4 calc_summary_values">
                                <?php echo $sub_total;?>
                           </div>
                       </div>
                       <div class="row calc-summary">
                           <div class="col-xs-8 calc_summary_labels">
                              Tax
                           </div>
                           <div class="col-xs-4 calc_summary_values">
                                <?php echo $tax;?>
                           </div>
                       </div>
                       <div class="row calc-summary">
                           <div class="col-xs-8 calc_summary_labels">
                              Grand Total
                           </div>
                           <div class="col-xs-4 calc_summary_values">
                                <?php echo $grand_total;?>
                           </div>
                       </div>
       
                   </div>
                </div>
                <div class="row">
                    <div style="margin-top: 20px;">
                       <a href="<?php echo base_url('checkout/complete_purchase/')?>" class="btn btn-lg btn-default btn-block">Complete Purchase</a>
                   </div>
                </div>
            </div> <!--end of container-fluid -->
            
        </div> 
    </div>
</div>

    </div>
</div>

<script>
  
    $(document).ready(function(){

        
        $("#confirm-email").prop("readonly", true);
        $('#edt-email').click(function(){
            
            var btn_text = $(this).text();
            if(btn_text == 'Change'){
                $("#confirm-email").prop("readonly",false);
                $("#confirm-email").focus(function(){
                    $(this).css('background','black');
                });
                // $("#confirm-email").css('background','black');
                $(this).text("Save");
                // $(this).text("Save");
            }else if(btn_text == 'Save'){
                
                $("#confirm-email").prop("readonly",true);
                $("#confirm-email").blur(function(){
                    $(this).css('background','black');
                });
                $(this).text("Change");
                change_email();
                // $(this).text("Change");
            }
        })
    });

    function change_email(){
        email = $("#confirm-email").val();
        $.post("<?php echo base_url("checkout/change_email/")?>",{email: email},function(resp){
            if(resp.status == true){
                $("#confirm-email").prop("readonly",true);
                $("#confirm-email").blur(function(){
                    $(this).css('background','black');
                });
                $(this).text("Change");
            }
        })
    }
</script>