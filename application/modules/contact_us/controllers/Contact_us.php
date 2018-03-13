<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Contact_us extends MX_Controller
{

function __construct() {
parent::__construct();
}

function submit(){
    $this->load->module('site_security');
    $this->load->module('Enquiries');
    $submit = $this->input->post('submit');
    if($submit == 'Submit'){
        //process form
        $this->load->library('form_validation');
        $this->form_validation->set_rules('yourname', 'Your Name', 'required|max_length[240]');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('telnum', 'Tel Number', 'numeric|max_length[240]');
        $this->form_validation->set_rules('message', 'Message', 'required');
        // $this->form_validation->set_rules('item_description', 'Item Description', 'required');
        
        if($this->form_validation->run() == true){
            //get form variables
            // $data['item_url'] = url_title($data['item_title']);
            
            $posted_data = $this->fetch_data_from_post();
            $data['code'] = $this->site_security->generate_random_string(6);
            $data['subject'] = 'Contact Form';
            $data['message'] = $this->build_msg($posted_data);
            $data['sent_to'] = 0;
            $data['date_created'] = time();
            $data['opened'] = 0;
            $data['sent_by'] = 0;
            $data['urgent'] =0;


            // inser new item
            $this->enquiries->_insert($data);
            // $update_id = $this->get_max();//gets the ID of new item
            $flash_msg = "Item Inserted Successfully";
            $value = '<div class="alert alert-success" role="alert">'.$flash_msg.'</div>';
            $data['flash'] = $this->session->set_flashdata('item', $value);
            redirect('contact_us/thankyou');
           
        }else{
            $this->index();
        }
    }elseif($submit == 'Cancel'){
        redirect('contact_us/index');
    }
}

function build_msg($posted_data){
    $yourname = ucfirst($posted_data['yourname']);
    $msg = $yourname.' submitted the following information:<br><br>';
    $msg .= "Name: $yourname <br>";
    $msg .= "Email:".$posted_data['email']."<br>";
    $msg .= "Telephone Number: ".$posted_data['telnum']."<br>";
    $msg .= "Message:".$posted_data['message'];
    return $msg;
}

function index(){
    
    $this->load->module('site_settings');
    $is_mobile = $this->site_settings->is_mobile();

    $data = $this->fetch_data_from_post();  
    $data['our_address'] = $this->site_settings->_get_our_address();
    $data['our_name'] = $this->site_settings->_get_our_name();
    $data['our_telnum'] = $this->site_settings->_get_our_telnum();
    $data['map_code'] = $this->site_settings->_get_map_code();
    $data['form_location'] = base_url('contact_us/submit');
    $data['flase'] = $this->session->flashdata('item');
    $data['view_file'] = "contact_us";

    if($is_mobile == FALSE){
        $template = 'public_bootstrap';
    }else {
        $template = 'public_jqm';
        $data['view_file'] .= '_jqm';
    }

    $this->load->module('templates');
    $this->templates->$template($data);
}

function thankyou(){
    $this->load->module('site_settings');
    // $data['flash'] = $this->session->flashdata('item');
    $data['view_file'] = "thankyou";
    $is_mobile = $this->site_settings->is_mobile();
    if($is_mobile == false){
        $template = 'public_bootstrap';
    }else {
        $template = 'public_jqm';
        $data['view_file'] .= '_jqm';
    }
    $this->load->module('templates');
    $this->templates->$template($data);
}

function fetch_data_from_post(){
        $data['yourname'] = $this->input->post('yourname');
        $data['email'] = $this->input->post('email');
        $data['telnum'] = $this->input->post('telnum');
        $data['message'] = $this->input->post('message');
        // // $data['status'] = $this->input->post('status');
        return $data;
    }

    // function fetch_data_from_db($update_id){
    //     if(!is_numeric($update_id)){
    //             redirect('site_security/not_allowed');
    //         }
    //     $query = $this->get_where($update_id);
    //     foreach ($query->result() as $row) {
    //         $data['item_title'] = $row->item_title;
    //         $data['item_url'] = $row->item_url;
    //         $data['item_price'] = $row->item_price;
    //         $data['item_description'] = $row->item_description;
    //         $data['big_pic'] = $row->big_pic;
    //         $data['small_pic'] = $row->small_pic;
    //         $data['was_price'] = $row->was_price;
    //         $data['status'] = $row->status;
    //     }

    //     if(!isset($data)){
    //         $data="";
    //     }
    //     return $data;
    // }

















}