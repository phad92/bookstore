<h1>Please Create An Account</h1>
<p>You do not need to create an account with us, however,
 if you do then you'll be able to enjoy:</p>
 <p>
    <ul>
        <!-- <li>Order Tracking</li> -->
        <li>Save your books</li>
        <li>Add books to wishlist</li>
        <li>Rent a book</li>
    </ul>
</p>

<p>Create an account only takes a minute, Would you like to create a account?</p>

<div class="col-md-10" style="margin-top: 36px;">
<?php echo form_open('cart/submit_choice')?>
    <button class="btn btn-success" name="submit" value="Yes" type="submit">
        <span class="glyphicon glyphicon-thumbs-up"></span>
        Yes - Let's Do It
    </button>

    <button class="btn btn-danger" name="submit" value="No" type="submit">
        <span class="glyphicon glyphicon-thumbs-down"></span>
        No Thanks
    </button>

    <a href="<?php echo base_url('your_account/login')?>">
        <button class="btn btn-primary" name="submit"  type="button">
            <span class="glyphicon glyphicon-log-in"></span>
            Already Have Account(Signin)
        </button>
    </a>

<?php echo form_hidden('checkout_token',$checkout_token); 
      echo form_close();?>
</div>