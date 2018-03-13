<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Cart extends MX_Controller
{

    function __construct() {
        parent::__construct();
    }

    function _calc_cart_total($cart_data){
        $shopper_id = $cart_data['shopper_id'];
        // print_r($cart_data);die();
        $session_id = $cart_data['customer_session_id'];
        $table = $cart_data['table'];
        $add_shipping = $cart_data['add_shipping'];

        $query = $this->_fetch_cart_content($session_id,$shopper_id,$table);
        $grand_total = 0;

        foreach($query->result() as $row){
            $sub_total = $row->item_price*$row->item_qty;
            $grand_total = $grand_total + $sub_total;
        }

        if($add_shipping == true){
            // $this->load->module('shipping');
            // $shipping = $this->shipping->_get_shipping();
            $shipping = 0;
        }else{
            $shipping = 0;
        }

        $grand_total = $grand_total + $shipping;
        return $grand_total;
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

    function _create_checkout_token($session_id){
        $this->load->module('site_security');
        $encrypted_string = $this->site_security->_encrypt_string($session_id);

        //remove disallowed characters
        $checkout_token = str_replace('+','-plus-',$encrypted_string);
        $checkout_token = str_replace('/','-fwrd-',$checkout_token);
        $checkout_token = str_replace('=','-eqls-',$checkout_token);
        return $checkout_token;
    }

    function _get_session_id_from_token($checkout_token){
        $this->load->module('site_security');
        
        //remove disallowed characters
        $session_id = str_replace('-plus-','+',$checkout_token);
        $session_id = str_replace('-fwrd-','/',$session_id);
        $session_id = str_replace('-eqls-','=',$session_id);
        $session_id = $this->site_security->_decrypt_string($session_id);
        return $session_id;
    }

    function test(){
        $this->load->module('site_security');
        $string = $this->session->session_id;
        
        $encrypted_string = $this->_create_checkout_token($string);
        $decrypted_string = $this->_get_session_id_from_token($encrypted_string);
        
        echo $string."<hr>";
        echo $encrypted_string."<hr>";
        echo $decrypted_string;
    }

    function _generate_guest_account($checkout_token){
        //customer clicked no thanks bottom
        $this->load->module('store_accounts');
        $this->load->module('site_security');
        $ref = $this->site_security->generate_random_string(4);
        $customer_session_id = $this->_get_session_id_from_token($checkout_token);
        //create guest acount
        $data['username'] = 'Guest-'.$ref;
        $data['firstname'] = "Guest";
        $data['lastname'] = "Account";
        $data['date_made'] = time();
        $data['pword'] = $checkout_token;

        $this->store_accounts->_insert($data);
        $new_account_id = $this->store_accounts->get_max();

        //update existing store_basket table
        $mysql_query = "UPDATE store_basket SET shopper_id = '$new_account_id' WHERE session_id = '$customer_session_id'";
        $query = $this->store_accounts->_custom_query($mysql_query);
    }

    function submit_choice(){
        $submit = $this->input->post('submit',TRUE);

        $checkout_token = $this->input->post('checkout_token',TRUE);
        if($submit == 'No'){
            $this->_generate_guest_account($checkout_token);
            redirect('cart/index/'.$checkout_token);
        }else{
            redirect('your_account/start');
        }
    }
    
    function go_to_checkout(){
        $this->load->module('site_security');
        // $session_id = $this->session->session_id;
        $shopper_id = $this->site_security->_get_user_id();

        if(is_numeric($shopper_id)){
            redirect('cart');
        }
        
        // print_r($_SESSION);die();
        $data['checkout_token'] = $this->uri->segment(3);
        $data['flash'] = $this->session->flashdata('item');
        // $data['view_module'] = "Checkout";
        $data['view_file'] = "go_to_checkout";
        $this->load->module('templates');
        $this->templates->public_bootstrap($data);
    }

    function _attempt_draw_checkout_btn($query){
        $data['query'] = $query;
        // print_r($query->result());die();
        $this->load->module('site_security');
        $shopper_id = $this->site_security->_get_user_id();
        $third_bit = $this->uri->segment(3);


        $this->_draw_checkout_btn_fake($query);
        // if((!is_numeric($shopper_id) AND ($third_bit == ''))){
        //     $this->_draw_checkout_btn_fake($query);
        // }else{
        //     $this->_draw_checkout_btn_real($query);
        // }
    }

    function _draw_checkout_btn_fake($query){
        foreach($query->result() as $row){
            $session_id = $row->session_id;
        }
        $data['checkout_token'] = $this->_create_checkout_token($session_id);
        $this->load->view('checkout_details',$data);
    }

    function _draw_checkout_btn_real($query){
        foreach($query->result() as $row){
            $session_id = $row->session_id;
        }
        $data['checkout_token'] = $this->_create_checkout_token($session_id);
        $data['view_module'] = 'Checkout';
        $data['view_file'] = "customer_details";
        $this->load->module('templates');
        $this->templates->public_bootstrap($data);
        // $this->load->module('paypal');
        // $this->paypal->_draw_paypal_btn($query);
    }

    function _draw_cart_contents($query, $user_type){
        //NOTE: user_type can be 'public' or 'admin'
        $this->load->module('site_settings');
        // $this->load->module('shipping');
        if($user_type == 'public'){
            $view_file = "cart_contents_public";
        }else{ 
            $view_file = "cart_contents_admin";
        }

        // $data['shipping'] = $this->shipping->_get_shipping();
        $data['currency_symbol'] = $this->site_settings->_get_currency_symbol();
        $data['query'] = $query;
        $this->load->view($view_file,$data);
    }



    function index(){
        $this->load->module('site_security');
        $shopper_id = $this->site_security->_get_user_id();
        $third_bit = $this->uri->segment(3);
        if($third_bit != ''){
            $session_id = $this->_check_and_get_session_id($third_bit); 
        }else{
            $session_id = $this->session->session_id;
        }

        if(!is_numeric($shopper_id)){
            $shopper_id = 0;
        }
        $table = 'store_basket';
        $data['query'] = $this->_fetch_cart_content($session_id,$shopper_id,$table);
        
        $data['num_rows'] = $data['query']->num_rows();
        $data['showing_statement'] = $this->_get_showing_statement($data['num_rows']);
        $data['flash'] = $this->session->flashdata('item');
        // print_r($_SESSION);die();
        $data['view_file'] = "cart";
        $this->load->module('templates');
        $this->templates->public_bootstrap($data);
    }

    function _get_showing_statement($num_items){
        if($num_items == 1){
            $showing_statement = "You have one item in your shopping basket";
        }else{
            $showing_statement = "You have $num_items items in your shopping basket";
        }
        return $showing_statement;
    }

    function _fetch_cart_content($session_id,$shopper_id,$table){
        $this->load->module('site_settings');
        $this->load->module('store_basket');
        $mysql_query = "
                SELECT
                $table.*,
                store_books.small_pic,
                store_books.book_url
                FROM
                $table
                LEFT JOIN store_books ON $table.item_id = store_books.id
        ";
        if($shopper_id > 0){
            $where_condition = " WHERE $table.shopper_id = '$shopper_id'";
        }else{
            $where_condition = " WHERE $table.session_id = '$session_id'";
        }
        $mysql_query .= $where_condition;
        $query = $this->store_basket->_custom_query($mysql_query);
        return $query;
    }

    function _draw_add_to_cart($book_id){
        $this->load->module('store_books');
        $this->load->module('site_settings');
        $data = $this->store_books->fetch_data_from_db($book_id);
         
        // print_r($query->result());
        $data['currency_symbol'] = $this->site_settings->_get_currency_symbol();
        $data['book_id'] = $book_id;
        $this->load->view('add_to_cart',$data);

    } 
    function _try_draw_add_to_cart($book_id){
        //fech color options for this item
        $submitted_color = $this->input->post('submitted_color');
        
        //if color option empty show 'select..'
        if($submitted_color ==""){
            $color_options[''] = "Select..";
        }
        //load store item colors module
        $this->load->module('store_item_colors');
        
        //fetch from db colors for this item
         $query = $this->store_item_colors->get_where_custom('item_id',$book_id);
         $data['num_colors'] = $query->num_rows();
         foreach ($query->result() as $row) {
             $color_options[$row->id] = $row->color;
         }


        //fetch size options for this item
        $submitted_size = $this->input->post('submitted_size');
        
        //if size option empty show 'select..'
        if($submitted_size ==""){
            $size_options[''] = "Select..";
        }
        //load store item size module
        //  $this->load->module('store_item_sizes');
         //fetch from db sizes for this item
        //  $query = $this->store_item_sizes->get_where_custom('item_id',$book_id);
         $data['num_sizes'] = $query->num_rows();
         foreach ($query->result() as $row) {
             $size_options[$row->id] = $row->size;
         }

        print_r($query->result());
        $data['submitted_color'] = $submitted_color;
        $data['submitted_size'] = $submitted_size;
        $data['color_options'] = $color_options;
        $data['size_options'] = $size_options;
        $data['book_id'] = $book_id;
        $this->load->view('add_to_cart',$data);

    } 
}