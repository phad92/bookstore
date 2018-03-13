<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Your_messages extends MX_Controller
{

    function __construct() {
        parent::__construct();
        //work on cookies

    }

    function message_sent(){
        $data['headline'] = "Message Sent";

        // $data['view_module'] = "blog";
        $data['view_file'] = "message_sent";
        $this->load->module('templates');
        $this->templates->public_bootstrap($data);
    }

    function create(){
        $this->load->library('session'); 
        $this->load->module('store_accounts');
        $this->load->module('Enquiries');
        $this->load->module('site_security');
        $this->load->module('timedate');
        $this->site_security->_make_sure_logged_id();
        
        $code = $this->uri->segment(3);
        $data = $this->fetch_data_from_post();
        $submit = $this->input->post('submit',true);
        $customer_id = $this->site_security->_get_user_id();
        if($submit == 'Submit'){
            //process form
            $this->load->library('form_validation');
            $this->form_validation->set_rules('subject', 'Subject', 'required|max_length[250]');
            $this->form_validation->set_rules('message', 'Message', 'required');
            
            if($this->form_validation->run() == true){
                //get form variables

                if((!is_numeric($customer_id)) OR ($customer_id == 0)){
                    $token = $this->input->post('token',true);
                    $customer_id = $this->store_accounts->_get_customer_id_from_token($token);
                    $not_logged_in = true;
                }
                // $data = $this->fetch_data_from_post();
                    // inser new item
                    $data['date_created'] = time();
                    $data['sent_by'] = $customer_id; //user id
                    $data['sent_to'] = 0;//always sent to admin
                    $data['opened'] = 0;
                    $data['code'] = $this->site_security->generate_random_string(6);
                    if($data['urgent'] != 1){
                        $data['urgent'] = 0;
                    }
                    if($customer_id > 0){
                        $this->enquiries->_insert($data);
                        $flash_msg = "The Message Successfully Sent.";
                        $value = '<div class="alert alert-success" role="alert">'.$flash_msg.'</div>';
                        $data['flash'] = $this->session->set_flashdata('item', $value);
                    }

                    if(isset($not_logged_in)){
                        $target_url = base_url("your_messages/message_sent");
                    }else{
                        $target_url = base_url("your_account/welcome");
                    }
                    // print_r($data);die();
                    redirect($target_url);
                
            }
        }elseif($code != ''){
            $data = $this->enquiries->_attempt_get_data_from_code($customer_id,$code);
            $data['message'] = "-----------------------------------------------------<br>".$data['message'];
        }

        // if((is_numeric($update_id)) && ($submit != 'Submit')){
        //     $data = $this->fetch_data_from_db($update_id);
        // }else {
        //     $data = $this->fetch_data_from_post();
        // }
        
        // if(!is_numeric($update_id)){
        //     $data['headline'] = "Compose New Message";
        // }
        $this->site_security->_make_sure_logged_id();
        $data['token'] = $this->store_accounts->_generate_token($customer_id);
        if($code == ""){
            $data['headline'] = "Compose New Message";
            // $data['headline'] = ""
        }else{
            $data['headline'] = "Reply To Message";
        }
        $data['message'] = $this->_format_msg($data['message']);
        $data['code'] = $code;
        // $data['options'] = $this->_fetch_customers_as_options();
        // $data['update_id'] = $update_id;
        $data['flash'] = $this->session->flashdata('item');
        // $data['view_module'] = "blog";
        $data['view_file'] = "create";
        $this->load->module('templates');
        $this->templates->public_bootstrap($data);
    }

    function _format_msg($msg){
        //formats a message string
$replace = '
        
';
        $msg = str_replace('<br>',$replace,$msg);
        $msg = strip_tags($msg);
        return $msg;
    }

    function view(){
        $this->load->module('Enquiries');
        $this->load->module('site_security');
        $this->load->module('timedate');
        // $this->site_security->_make_sure_logged_id();
        
        // echo $s;die();
        $code = $this->uri->segment(3);
        $col1 = 'sent_to';
        $value1 = $_SESSION['user_id'];
        $col2 = 'code';
        $value2 = $code;
        $query = $this->enquiries->get_with_double_condition($col1,$value1,$col2,$value2);
        $num_rows = $query->num_rows();
        // print_r($query->result());die();
        // $this->enquiries->_set_to_opened($code);
        $mysql_query = "UPDATE enquiries SET opened = 1 WHERE code = '$code'";
        $this->enquiries->_custom_query($mysql_query);  
        if($num_rows < 1){
            redirect('site_security/not_allowed');
        }


        foreach($query->result() as $row){
            $update_id = $row->id;
            $data['subject'] = $row->subject;
            $data['message'] = nl2br($row->message);
            $data['sent_to'] = $row->sent_to;
            $date_created = $row->date_created;
            $data['opened'] = $row->opened;
            $data['sent_by'] = $row->sent_by;
        }

        $data['code'] = $code;
        $data['date_created'] = $this->timedate->get_nice_date($date_created,'full');
        // if(!is_numeric($code)){
        //     redirect('enquiries/inbox');
        // }

        // $data['query'] = $this->get_where($code);
        // $data['headline'] = "Enquiries Detail ".$update_id;
        // $data['query'] = $this->get('lastname');
        $data['view_file'] = "view";
        $this->load->module('templates');
        $this->templates->public_bootstrap($data);
    }


    function fetch_data_from_post(){
        $data['sent_to'] = $this->input->post('sent_to');
        $data['subject'] = $this->input->post('subject',true);
        $data['message'] = $this->input->post('message',true);
        $data['urgent'] = $this->input->post('urgent',true);
        return $data;
    }

    function fetch_data_from_db($update_id){
        $this->load->module('timedate');
        if(!is_numeric($update_id)){
                redirect('site_security/not_allowed');
            }
        $query = $this->get_where($update_id);
        foreach ($query->result() as $row) {
            $data['subject'] = $row->subject;
            $data['message'] = $row->message;
            $data['sent_to'] = $row->sent_to;
            $data['date_created'] = $row->date_created;
            $data['opened'] = $row->opened;
            $data['sent_by'] = $row->sent_by;
        }

        if(!isset($data)){
            $data="";
        }
        return $data;
    }

}