<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Ahenfie extends MX_Controller
{

    function __construct() {
    parent::__construct();
     $this->load->library('form_validation');
        $this->form_validation->CI =& $this;
    }

    function index(){
        $data['username'] = $this->input->post('username',true);
        $data['view_file'] = "create";
        $this->load->module('templates');
        $this->templates->login($data);
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
                
                $this->_in_you_go();
            }else{
                echo validation_errors();
                print_r('error');
                // $this->start();
            }           

        }
    }

    function _in_you_go(){
        //login type can be long or short term
        //set a session variable
        $this->session->set_userdata('is_admin','1');
        
        //send your to the private area or user account dashboard
        redirect('dashboard/home');
        // echo "you are in";
    }

    function logout(){
        unset($_SESSION['is_admin']);
        // $this->load->module('site_cookies');
        // $this->site_cookies->_destroy_cookie();
        redirect(base_url());
    }

    function username_check($str){
        //create users table to manage admin users
        // $this->load->module('store_accounts');
        $this->load->module('site_security');
        $pword  = $this->input->post('pword',true);
        // $username = $this->input->post('username',true);
        // print_r($result);die();
        // echo 'password: '.$pword . 'str: '. $str;die();
        // var_dump($this->site_security->_check_admin_login_details($str,$pword));die();
        $result = $this->site_security->_check_admin_login_details($str,$pword);
        if($result == TRUE){
            return True;
        }else{
            $this->form_validation->set_message('username_check',"Wrong username or password");
            return False;
        }
    }










}