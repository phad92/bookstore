<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Site_security extends MX_Controller
{

    function __construct() {
    parent::__construct();
    }

    function _check_admin_login_details($username,$pword){
        $target_username = "admin";
        $target_pass = "password";

        if(($username === $target_username) && ($pword === $target_pass)){
            return true;
        }else{
            return false;
        }
    }
 
    function _make_sure_logged_id(){
        //make sure customer is logged in
        $user_id = $this->_get_user_id();
        //  echo $user_id;die();
        if(!is_numeric($user_id)){
           redirect('your_account/login');
        }
        return $user_id;
    }

    function _is_logged_in(){
        $logged_in = true;
        $user_id = $this->_get_user_id();

        if(!is_numeric($user_id)){
            $logged_in = false;
        }

        return $logged_in;
    }
    
    function _get_user_id(){
        //attempt to get the id of the user
        //start by checking for session variable
        $user_id = $this->session->userdata('user_id');
        // $user_id = $_SESSION['user_id'];
        // print_r($_SESSION);die();
        
        if(!is_numeric($user_id)){
            //check for a valid cookie
            $this->load->module('site_cookies');
            $user_id = $this->site_cookies->_attempt_get_id();
            // echo $user_id;die();
        }
        return $user_id;
    }

    function generate_random_string($length){
        $characters = '23456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for($i = 0; $i < $length; $i++){
            $randomString .= $characters[rand(0,strlen($characters) - 1)];
        }
        return $randomString;
    }

    function _create_token($string){
        $encrypted_string = $this->_encrypt_string($string);

        //remove disallowed characters
        $token = str_replace('+','-plus-',$encrypted_string);
        $token = str_replace('/','-fwrd-',$token);
        $token = str_replace('=','-eqls-',$token);
        return $token;
    }

    function _get_token($token){
        //remove disallowed characters
        $string = str_replace('-plus-','+',$token);
        $string = str_replace('-fwrd-','/',$string);
        $string = str_replace('-eqls-','=',$string);
        $string = $this->_decrypt_string($string);
        return $string;
    }

    function _decrypt_string($string){
        $this->load->library('encryption');
        $decrypted_string = $this->encryption->decrypt($string);
        return $decrypted_string;
    }

    function _encrypt_string($string){
        $this->load->library('encryption');
        $encrypted_string = $this->encryption->encrypt($string);
        return $encrypted_string;
    }

    function _hash_string($str){
        $hashed_string = password_hash($str,PASSWORD_BCRYPT,array(
            'cost' => 11
        ));
        return $hashed_string;
    }

    function _verify_hash($plain_text,$hashed_string){
        $result = password_verify($plain_text,$hashed_string);
        return $result;
    }

    public function _make_sure_is_admin(){
        // $is_admin = FALSE;
        $is_admin = $this->session->userdata('is_admin');
        if($is_admin != ""){
           return true;
        }else{
            // echo "No Session variable set";
            redirect('site_security/not_allowed');
        } 
    }

    public function _make_sure_session_is_valid(){
        $current_session_id = $this->session->session_id;
        $mysql_query = "SELECT * FROM ci_sessions WHERE id = '$current_session_id'";
        $query = $this->db->query($mysql_query);
        $session = $query->num_rows();
        if($session < 1){
            redirect();
        }
        
        return true;
    }

    function not_allowed(){
        echo "you are not allowed to be here";
    }
}