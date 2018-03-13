<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Checkout extends MX_Controller
{

    function __construct() {
        parent::__construct();
        $this->load->module('site_security');
    }

    function _generate_token($session_id){
        $token = $this->site_security->_create_token($session_id);
        return $token;
    }


    function complete_purchase(){
        $this->load->module('stripe');
        $this->load->module('downloads');
        $this->load->module('mail');

        $session_id = $this->session->session_id;
        $shopper_id = $this->_get_shopper_id();

        $mysql_query = "SELECT * FROM stripe WHERE session_id = '$session_id'";
        $query = $this->stripe->_custom_query($mysql_query);
        $stripe_card = $query->row();

        $config = $this->stripe->make_payment($stripe_card->id);
        $config = $this->stripe->initialize_stripe_payment($stripe_card->id);
        $this->stripe->stripe_charge($config);
        print_r($config);die();
        $token = $config['token'];
        $download_link = base_url('downloads/download_book/'.$token);
        $this->mail->send($download_link);
        // $token = $this->_generate_token($session_id);
        $data['token'] = $token;
        
        // $this->downloads->download_book($shopper_id);
        print_r($query->row());die();
    }


    
    function _check_and_get_session_id($checkout_token){
        $session_id = $this->_get_session_id_from_token($checkout_token);
        // echo $session_id;die();
        if($session_id == ''){
            redirect(base_url());
        }

        //check to see if this session id appears on store basket table
        $this->load->module('store_basket');
        $query = $this->store_basket->get_where_custom('session_id',$session_id);
        $num_rows = $query->num_rows();
        
        if($num_rows < 1){
            redirect(base_url());
        }

        return $session_id;
    }

    
    
    function index(){
        $this->load->module('site_settings');
        $this->load->module('cart');
        $this->output->enable_profiler(TRUE);
        $session_id = $this->session->session_id;
        $shopper_id = $this->site_security->_get_user_id();
        
        // if(!is_numeric($shopper_id)){
        //     $refer_url = $_SERVER['HTTP_REFERER'];
        //     redirect($refer_url);
        // }

        $tax_type = 'VAT';
        $calc_resp = $this->_calculate_cart_total($session_id,$shopper_id);
        $data['grand_total'] = $calc_resp['grand_total'];
        $data['tax'] = $calc_resp['tax'];
        $data['sub_total'] = $calc_resp['sub_total'];
        $data['query'] = $this->_fetch_cart_content($session_id,$shopper_id);
        $data['url_segments'] = $this->site_settings->_get_item_segments();
        // print_r($query->result());die();
        $this->load->view('checkout',$data);
        
    }

    function _fetch_cart_content($session_id,$shopper_id){
        $this->load->module('cart');
        $table = "store_basket";
        $query = $this->cart->_fetch_cart_content($session_id,$shopper_id,$table);
        return $query;
    }

    function _calculate_cart_total($session_id,$shopper_id){
        // $this->load->module('cart');
        $data = array();
        $table = "store_basket";
        $grand_total = 0;
        $add_shipping = false;

        $query = $this->_fetch_cart_content($session_id,$shopper_id,$table);
        $data['num_rows'] = $query->num_rows();
        $sub_total = 0;
        foreach($query->result() as $row){
            $price = $row->item_price*$row->item_qty;
            $sub_total = $sub_total + $price;
        }
        $grand_total = $grand_total + $sub_total;

        if($add_shipping == true){
            // $this->load->module('shipping');
            // $shipping = $this->shipping->_get_shipping();
            $shipping = 0;
        }else{
            $shipping = 0;
        }

        $data['sub_total'] = $sub_total;
        $data['tax'] = 0;
        $data['grand_total'] = $grand_total + $shipping;
        // print_r($data);die();
        return $data;
    }

    function _draw_checkout_btn($query){
        $this->load->module('site_settings');

        foreach($query->result() as $row){
            $session_id = $row->session_id;
        }

        $is_on_test_mode = $this->_is_on_test_mode();

        if($is_on_test_mode == TRUE){
            $data['form_location'] = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
        }else{
            $data['form_location'] = 'https://www.paypal.com/cgi-bin/webscr';
        }

        $data['on_test_mode'] = $is_on_test_mode;
        $data['cancel_return'] = base_url('paypal/cancel');
        $data['return'] = base_url('paypal/thankyou');
        $data['custom'] = $this->site_security->_encrypt_string($session_id);
        $data['paypal_email'] = $this->site_settings->_get_paypal_email();
        $data['currency_code'] = $this->site_settings->_get_currency_code();
        $data['query'] = $query;
        $this->load->view('checkout_btn',$data);
    }

    function payment(){
        
        $this->site_security->_make_sure_session_is_valid();
        $shopper_id = $this->_get_shopper_id();
       
        $this->output->enable_profiler(TRUE);
        $session_id = $this->session->session_id;
        $shopper = $this->_check_email_exist($shopper_id);
        if(!is_string($shopper)){
            $refer_url = $_SERVER['HTTP_REFERER'];
            redirect($refer_url);
        }

        $data['view_file'] = "payment";
        $this->load->module('templates');
        $this->templates->public_bootstrap($data);
    }

    function _paypal_payment(){
        $this->load->module('paypal');
        $session_id = $this->session->session_id;
        $shopper_id = $this->site_security->_get_user_id();
        $query = $this->_fetch_cart_content($session_id,$shopper_id);
        $this->paypal->_draw_paypal_btn($query);
    }

    function customer_details(){
        $this->load->module('store_accounts');
        $this->site_security->_make_sure_session_is_valid();
        $shopper_id = $this->site_security->_get_user_id();
        $session_id = $this->session->session_id;
        // print($session_id);die();
        if(is_numeric($shopper_id)){
            $data = $this->store_accounts->fetch_data_from_db($shopper_id);
        }else {
            // $this->store_accounts->get_max();
            $shopper_id = $this->_current_guess_user($session_id);
            
            if($shopper_id == 0 or $shopper_id == ''){
                // $data = $this->fetch_data_from_post();
                // print_r($data);die();
                $this->store_accounts->_generate_guest_account();
                $data = $this->store_accounts->fetch_data_from_post();
            }else{
                $data = $this->store_accounts->fetch_data_from_db($shopper_id);
                
            }
        }
        // print_r($data);die();
        // $data = $this->fetch_data_from_post();
        $data['view_file'] = "customer_details";
        $this->load->module('templates');
        $this->templates->public_bootstrap($data);
    }
    
    function submit_billing_info(){
        $this->load->module('store_accounts');
        $this->load->module('store_orders');
        $shopper_id = $this->_get_shopper_id();
        $customer_session_id = $this->session->session_id;
        $submit = $this->input->post('submit',true);
        if($submit == 'Submit'){
            //process form
            $this->load->library('form_validation');
            //use callback to make sure userid is unique
            $this->form_validation->set_rules('firstname', 'First Name', 'required|max_length[240]');
            if(!is_numeric($shopper_id)){
                $this->form_validation->set_rules('email', 'Email', 'required|is_unique[store_accounts.email]|max_length[240]');
            }
            $this->form_validation->set_rules('lastname', 'Last Name', 'required');
            $this->form_validation->set_rules('country', 'Country', 'required');
            $this->form_validation->set_rules('postcode', 'Postal Code', 'required|numeric');
            $this->form_validation->set_rules('town', 'City', 'required');
            
            $this->form_validation->set_message('is_unique', 'Provided %s is taken.');
            if($this->form_validation->run() === true){
                $data = $this->fetch_data_from_post();
                //get form variables
                if(is_numeric($shopper_id)){
                     $data['date_made'] = time();
                    // print_r($data);die();
                    $this->store_accounts->_update($shopper_id,$data);
                }
                redirect('checkout/payment');
            }else{
                $this->payment();
            }           

        }
    }

    function _stripe_payment(){
        $this->load->module('stripe');
        $shopper_id = $this->_get_shopper_id();
        $session_id = $this->session->session_id;
        $query = $this->stripe->get_where_custom('session_id',$session_id);
        $stripe = $query->row();
        $num_rows = $query->num_rows();
        
        // print_r($query->num_rows());die();
        if($num_rows > 0){
            $stripe_id = $stripe->id;
            $data = $this->stripe->fetch_data_from_db($stripe_id);
            $data['stripe_id'] = $stripe_id;
            // print_r($stripe_id);die();
        }else{
            $data = $this->stripe->fetch_data_from_post();
        }
        // print_r($stripe_id);die();
        $this->load->view('card_form',$data);
        // $this->stripe->_draw_stripe_form($strip_id);
    }

    function submit_stripe_form(){
        $this->output->enable_profiler(TRUE);
        $this->load->module('stripe');
        $this->load->module('store_orders');
        $this->site_security->_make_sure_session_is_valid();
        
        $order_data = $this->order_data();
        // print_r($order_data);die();
        // $this->store_orders->_generate_order($order_data);
        // $this->stripe->submit_card_data();
        $this->submit_card_data();
        redirect('checkout/confirmation');
        // $this->stripe->check();
    }

    function submit_card_data(){
        $this->load->module('stripe');
        $this->site_security->_make_sure_session_is_valid();
        // $this->load->module('store_orders');
        $shopper_id = $this->_get_shopper_id();
        $session_id = $this->session->session_id;
	    $email = $this->_check_email_exist($shopper_id);
        $stripe_id = $this->stripe->_get_stripe_id($session_id);
        //get form variables
        // var_dump($stripe_id);die();
        $data = $this->stripe->fetch_data_from_post();
        if(is_numeric($stripe_id)){
            $customer = $this->stripe->_check_customer_exist($stripe_id);
            $stripe_customer  = $this->stripe->check($email,$data['stripeToken']);
            if(!$customer){
                $data['customer'] = $stripe_customer->id;
            }
            print($data['stripeToken']);die();
            // $data['customer'] = $customer;
            unset($data['stripeToken']);
            $data['created'] = time();
            // var_dump($stripe_id);die();
            print_r($data);die();
            $this->stripe->_update($stripe_id,$data);
        }else{
            $data['session_id'] = $this->session->session_id;
            $data['shopper_id'] = $shopper_id;
            $data['email'] = $email;
            $stripeToken = $data['stripeToken']; 
            $stripe_customer  = $this->stripe->check($email,$stripeToken);
            $data['customer'] = $stripe_customer->id;
            unset($data['stripeToken']);
            // print_r($data);die();
            $this->stripe->_insert($data);
        }
        redirect('checkout/payment');
    }

    function confirmation(){
        $this->load->module('site_settings');
        $this->load->module('store_accounts');
        $this->load->module('stripe');
        $this->site_security->_make_sure_session_is_valid();
        $session_id = $this->session->session_id;
        $payment_id = $this->stripe->_get_stripe_id($session_id);
        
        if(!is_numeric($payment_id)){
            $refer_url = $_SERVER['HTTP_REFERER'];
            redirect($refer_url);
        }
        $shopper_id = $this->_get_shopper_id();
        // $data['payment_id'] = $this->_get_shopper_id();
        
        if(is_numeric($shopper_id)){
            $data = $this->store_accounts->fetch_data_from_db($shopper_id);
            $data['email'] = $this->stripe->_get_payment_email($shopper_id);
            // print_r($data);die();
        }
        
        $calc_resp = $this->_calculate_cart_total($session_id,$shopper_id);
        $query = $this->_fetch_cart_content($session_id,$shopper_id);

        $data['grand_total'] = $calc_resp['grand_total'];
        $data['tax'] = $calc_resp['tax'];
        $data['sub_total'] = $calc_resp['sub_total'];
        $data['num_rows'] = $query->num_rows();

        $token = $this->_generate_token($session_id);
        $data['token'] = $token;
        // print_r($data);die();
        $data['query'] = $query;
        $data['url_segments'] = $this->site_settings->_get_item_segments();
        $data['view_file'] = "confirmation";
        $this->load->module('templates');
        $this->templates->public_bootstrap($data);
    }

    function send_package(){
        $this->load->module('mail');


    }

    function change_email(){
        $this->load->module('stripe');

        $session_id = $this->session->session_id;
        $stripe_id = $this->stripe->_get_stripe_id($session_id);
        $email = $this->input->post('email',true);

        $data['email'] = $email;

        if($this->stripe->_update($stripe_id,$data)){
            $this->output->set_content_type('application/json')->set_output(json_encode(array('status' => true)));
        }else{
            $this->output->set_content_type('application/json')->set_output(json_encode(array('status' => false)));
        }
    }

    function _check_email_exist($shopper_id){
        $this->load->module('store_accounts');
        $data = $this->store_accounts->fetch_data_from_db($shopper_id);
        $email = $data['email'];
        return $email;
    }


    function _current_guess_user($session_id){
        $this->load->module('store_basket');
        $query = $this->store_basket->get_where_custom('session_id',$session_id);
        $shopper = $query->first_row();
        $shopper_id = $shopper->shopper_id;
        // print_r($session_id);die();
        return $shopper_id;
    }
    
    function _get_shopper_id(){
        $session_id = $this->session->session_id;
        $shopper_id = $this->site_security->_get_user_id();
        if(!is_numeric($shopper_id)){
            $shopper_id = $this->_current_guess_user($session_id);
            // print_r($shopper_id);die();
            return $shopper_id;
        }
        return $shopper_id;
    }

    function order_data(){
        $this->load->module('store_orders');
        $order_ref = $this->site_security->generate_random_string(6);
        $session_id = $this->session->session_id;
        $shopper_id = $this->_get_shopper_id();
        $cart_data = $this->_calculate_cart_total($session_id,$shopper_id);
        // print_r($cart_data);die();
        $payment_method = 'stripe';
        $data['order_ref'] = $order_ref;
        $data['date_created'] = time();
        $data['session_id'] = $session_id;
        $data['num_items'] = $cart_data['num_rows'];
        $data['opened'] = 0;
        $data['order_status'] = 0;
        $data['shopper_id'] = $shopper_id;
        $data['amount'] = $cart_data['grand_total'];
        
        return $data;
    }



    function fetch_data_from_post(){
        $data['firstname'] = $this->input->post('firstname',true);
        $data['lastname'] = $this->input->post('lastname',true);
        $data['country'] = $this->input->post('country',true);
        $data['email'] = $this->input->post('email',true);
        $data['postcode'] = $this->input->post('postcode',true);
        $data['town'] = $this->input->post('town',true);
        $data['address1'] = $this->input->post('address1',true);
        $data['address2'] = $this->input->post('address2',true);
        return $data;
    }



    
}