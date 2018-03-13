<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Your_account extends MX_Controller
{

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->form_validation->CI =& $this;
    }

    function logout(){
        unset($_SESSION['user_id']);
        $this->load->module('site_cookies');
        $this->site_cookies->_destroy_cookie();
        redirect(base_url());
    }

    function welcome(){
        $this->load->module('site_security');
        $this->load->module('site_settings');

        $this->site_security->_make_sure_logged_id();
        $is_mobile = $this->site_settings->is_mobile();
        if($is_mobile == TRUE){
            $template = 'public_jqm';
        }else {
            $template = 'public_bootstrap';
        }
        // $data = $this->fetch_data_from_post();
        $data['name'] = "fadlu";
        // $data['flash'] = $this->session->flashdata('item');
        $data['view_file'] = "welcome";
        $this->load->module('templates');
        $this->templates->$template($data);
        
    }

    function test1(){
        $this->session->set_userdata('your_name', '$your_name');
    }

    function login(){
        $data['username'] = $this->input->post('username',true);
        $data['view_file'] = "create";
        $this->load->module('templates');
        $this->templates->login($data);
    }

    function submit_login(){
        $submit = $this->input->post('submit',true);
        if($submit == 'Submit'){
            //process form
            $this->load->library('form_validation');
            $this->form_validation->set_rules('username', 'Username', 'required|max_length[240]|callback_username_check');
            $this->form_validation->set_rules('pword', 'Password', 'required|max_length[240]');
            $this->load->module('store_accounts');
            if($this->form_validation->run() == true){
                //figure out user id
                $col1 = 'username';
                $value1 = $this->input->post('username',true);
                $col2 = 'email';
                $value2 = $this->input->post('email',true);;
                $query = $this->get_with_double_condition($col1,$value1,$col2,$value2);  
                $num_rows = $query->num_rows();
                if($num_rows > 0){
                    foreach($query->result() as $row){
                        $user_id = $row->id;
                    }
                    $remember = $this->input->post('remember',true);
                    if($remember == "remember-me"){
                        $login_type = "longterm";
                    }else{
                        $login_type = "shortterm";
                    }

                    $data['last_login'] = time();
                    $this->store_accounts->_update($user_id,$data);
                    //send them to the private page
                    $this->_in_you_go($user_id,$login_type);
                }
 
            }else{
                echo validation_errors();
                print_r('error');
                // $this->start();
            }           

        }
    }

    function _in_you_go($user_id,$login_type){
        $_SESSION['user_id'] = $user_id;
        //login type can be long or short term
        // if($login_type = "longterm"){
        //     //set a cookis
        //     $this->load->module('store_cookies');
        //     $this->site_cookies->_set_cookie($user_id);

        // }else{
        //     //set a session variable
        //     // $this->session->set_userdata('user_id',$user_id);
        //     $_SESSION['user_id'] = (int) $user_id;
        //     print_r($_SESSION);die();
        // }
        //attempt to update cart and divert back to cart
        $this->_attempt_cart_divert($user_id);
        //send your to the private area or user account dashboard
        redirect('Your_account/welcome');
        // echo "you are in";
    }

    function _attempt_cart_divert(){
        $this->load->module('store_basket');
        $customer_session_id = $this->session->session_id;
        $col1 = 'session_id';
        $value1 = $customer_session_id;
        $col2 = 'shopper_id';
        $value2 = 0;
        $query = $this->store_basket->get_with_double_condition($col1,$value1,$col2,$value2);
        
        $num_rows = $query->num_rows();
        if($num_rows > 0){
            $mysql_query = "UPDATE store_basket SET shopper_id='$user_id' WHERE session_id='$customer_session_id'";
            $query = $this->store_basket->_custom_query($mysql_query);
            redirect('cart');
        }
    }

    function submit(){
        $submit = $this->input->post('submit',true);

        if($submit == 'Submit'){
            //process form
            $this->load->library('form_validation');
            //use callback to make sure userid is unique
            $this->form_validation->set_rules('username', 'Username', 'required|max_length[240]');
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('pword', 'Password', 'required|max_length[240]');
            $this->form_validation->set_rules('repeat_pword', 'Repeat Password', 'required|max_length[240]|matches[pword]');
            
            if($this->form_validation->run() === true){
                //get form variables

                $this->_process_create_account();
                echo "<h1>Account Created</h1>";
                echo "<p>Please Sign In</p>";
            }else{
                $this->start();
            }           

        }
    }

    function _process_create_account(){
        $this->load->module('store_accounts');
        $data = $this->fetch_data_from_post();
        unset($data['repeat_pword']);

        $pword = $data['pword'];
        $this->load->module('site_security');
        $data['pword'] = $this->site_security->_hash_string($pword);
        $this->store_accounts->_insert($data);
    }

    function start(){
        $data = $this->fetch_data_from_post();
        $data['flash'] = $this->session->flashdata('book');
        $data['view_file'] = "start";
        $this->load->module('templates');
        $this->templates->public_bootstrap($data);
    }

    function fetch_data_from_post(){
        $data['username'] = $this->input->post('username',true);
        $data['email'] = $this->input->post('email',true);
        $data['pword'] = $this->input->post('pword',true);
        $data['repeat_pword'] = $this->input->post('repeat_pword',true);
        return $data;
    }

    function get($order_by) {
    $this->load->model('Mdl_your_account');
    $query = $this->Mdl_your_account->get($order_by);
    return $query;
    }

    function get_with_limit($limit, $offset, $order_by) {
    $this->load->model('Mdl_your_account');
    $query = $this->Mdl_your_account->get_with_limit($limit, $offset, $order_by);
    return $query;
    }

    function get_where($id) {
    $this->load->model('Mdl_your_account');
    $query = $this->Mdl_your_account->get_where($id);
    return $query;
    }

    function get_where_custom($col, $value) {
    $this->load->model('Mdl_your_account');
    $query = $this->Mdl_your_account->get_where_custom($col, $value);
    return $query;
    }
    function get_with_double_condition($col1, $value1,$col2,$value2) {
    $this->load->model('Mdl_store_accounts');
    $query = $this->Mdl_store_accounts->get_where_custom($col1, $value1,$col2,$value2);
    return $query;
    }

    function _insert($data) {
    $this->load->model('Mdl_your_account');
    $this->Mdl_your_account->_insert($data);
    }

    function _update($id, $data) {
    $this->load->model('Mdl_your_account');
    $this->Mdl_your_account->_update($id, $data);
    }

    function _delete($id) {
    $this->load->model('Mdl_your_account');
    $this->Mdl_your_account->_delete($id);
    }

    function count_where($column, $value) {
    $this->load->model('Mdl_your_account');
    $count = $this->Mdl_your_account->count_where($column, $value);
    return $count;
    }

    function get_max() {
    $this->load->model('Mdl_your_account');
    $max_id = $this->Mdl_your_account->get_max();
    return $max_id;
    }

    function _custom_query($mysql_query) {
    $this->load->model('Mdl_your_account');
    $query = $this->Mdl_your_account->_custom_query($mysql_query);
    return $query;
    }

    function username_check($str){
        $this->load->module('store_accounts');
        $this->load->module('site_security');
        $col1 = 'username';
        $value1 = $str;
        $col2 = 'email';
        $value2 = $str;
        $query = $this->get_with_double_condition($col1,$value1,$col2,$value2);  
        $num_rows = $query->num_rows();
        // print_r($num_rows);die();
        // $error_msg = "Wrong username or password";
        if($num_rows < 1){
            $this->form_validation->set_message('username_check',"Wrong username or password");
            return FALSE;
        }
        
        foreach($query->result() as $row){
            $pword_on_table = $row->pword;
        }
        
        $pword  = $this->input->post('pword',true);
        $result = $this->site_security->_verify_hash($pword,$pword_on_table);
        
        if($result == TRUE){
            return True;
        }else{
            $this->form_validation->set_message('username_check',"Wrong username or password");
            return False;
        }
    }
}


// function item_check($str){
        
        
//         if($str != "phad1"){
//             $this->form_validation->set_message('item_check','The Item you submitted is not available');
//             return FALSE;
//         }else {
//             return TRUE;
//         }
//     }