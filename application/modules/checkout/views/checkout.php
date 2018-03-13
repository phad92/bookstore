<?php 
    $uri_seg = $this->uri->segment(2);
    $set_active ="active";
    // print($grand_total);die();
?>
<div class="container-fluid">
	<div class="row">
        <article class="col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">
            <div id="wid-id-0">
                <!-- widget div-->
                <div role="content">
                    <!-- widget content -->
                    <div class="widget-body">

                        <div class="row">
                            <form id="wizard-1" novalidate="novalidate">
                                <div id="bootstrap-wizard-1" class="col-sm-12">
                                    <div class="form-bootstrapWizard">
                                        <ul class="bootstrapWizard form-wizard">
                                            
                                            <li class="<?php if($uri_seg === 'customer_details'){echo $set_active;}?>" data-target="#step1">
                                                <a href="<?php echo base_url('checkout/customer_details')?>"> <span class="step">1</span> <span class="title">Customer Information</span> </a>
                                            </li>
                                            <li class="<?php if($uri_seg === 'payment'){echo $set_active;}?>" id="payment" data-target="#step2" class="">
                                                <a href="<?php echo base_url('checkout/payment')?>" id="payment-btn"> <span class="step">2</span> <span class="title">Payment Details</span> </a>
                                            </li>
                                            <li class="<?php if($uri_seg === 'confirmation'){echo $set_active;}?>" id="confirmation" data-target="#step3" class="">
                                                <a href="<?php echo base_url('checkout/confirmation')?>" id="confirm-btn"> <span class="step">3</span> <span class="title">Confirm Order</span> </a>
                                            </li>
                                            <!-- <li class="<?php //if($uri_seg === 'customer_datails'){echo $set_active;}?>" data-target="#step4">
                                                <a href="#tab4" data-toggle="tab"> <span class="step">4</span> <span class="title">Save Form</span> </a>
                                            </li> -->
                                        </ul>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                    <!-- end widget content -->

                </div>
                <!-- end widget div -->

            </div>
            <!-- end widget -->

        </article>
    </div>
    
    <div class="row">
        <?php 
            $curr_url = $this->uri->segment(2);
                if($curr_url !== 'confirmation'):
            ?>
        <div class="col-md-4 pull-right mini-cart">
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
             <?php endif;?>
    
    





<!-- <h1>Please Create An Account</h1>
<p>You do not need to create an account with us, however,
 if you do then you'll be able to enjoy:</p>
 <p>
    <ul> -->
        <!-- <li>Order Tracking</li> -->
        <!-- <li>Save your books</li>
        <li>Add books to wishlist</li>
        <li>Rent a book</li>
    </ul>
</p> -->

<!-- <p>Create an account only takes a minute, Would you like to create a account?</p> -->

<!-- <div class="col-md-10" style="margin-top: 36px;">
<?php //echo form_open('cart/submit_choice')?>
    <button class="btn btn-success" name="submit" value="Yes" type="submit">
        <span class="glyphicon glyphicon-thumbs-up"></span>
        Yes - Let's Do It
    </button>

    <button class="btn btn-danger" name="submit" value="No" type="submit">
        <span class="glyphicon glyphicon-thumbs-down"></span>
        No Thanks
    </button> -->

    <!-- <a href="<?php //echo base_url('your_account/login')?>">
        <button class="btn btn-primary" name="submit"  type="button">
            <span class="glyphicon glyphicon-log-in"></span>
            Already Have Account(Signin)
        </button>
    </a> -->

<?php //echo form_hidden('checkout_token',$checkout_token); 
      //echo form_close();?>
<!-- </div> -->