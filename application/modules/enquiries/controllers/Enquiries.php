<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Enquiries extends MX_Controller
{

    function __construct() {
    parent::__construct();
    }

    function submit_ranking(){
        $this->load->module('site_security');
        $data['ranking'] = $this->input->post('ranking');
        $submit = $this->input->post('submit',true);
        if($submit == "Cancel"){
            redirect('enquiries/inbox');
        }
        
        $update_id = $this->uri->segment(3);
        $this->_update($update_id,$data);
        $msg = "Enquiry Ranking was successfully added";
        $value = "<div class='alert alert-success'>$msg</div>";
        $data['flash'] = $this->session->set_flashdata('item',$value);
        redirect('enquiries/view/'.$update_id);
    }

    function _attempt_get_data_from_code($customer_id,$code){
        //ensure customer is allowed to view and respond. fetch data
        $query = $this->get_where_custom('code',$code);
        $num_rows = $query->num_rows();
        
        foreach($query->result() as $row){
            $data['subject'] = $row->subject;
            $data['message'] = $row->message;
            $data['sent_to'] = $row->sent_to;
            $data['date_created'] = $row->date_created;
            $data['opened'] = $row->opened;
            $data['sent_by'] = $row->sent_by;
            $data['urgent'] = $row->urgent;
        }
        // echo $customer_id;
        // print_r($data);die();
        if(($num_rows < 1) and ($customer_id != $data['sent_to'])){
            redirect('site_security/not_allowed');
        }

        return $data;
    }

    function fix(){
        $query = $this->get('id');
        $this->load->module('site_security');
        foreach($query->result() as $row){
            $data['code'] = $this->site_security->generate_random_string(6);
            $this->_update($row->id,$data);
        }

        echo 'done';
    }

   
    function manage(){
        $this->load->module('site_security');
        $this->site_security->_make_sure_is_admin();
        
        $data['query'] = $this->get('date_published desc');
        //$data['view_module'] = "blog";
        $data['headline'] = "Manage Item";
        $data['flash'] = $this->session->set_flashdata('item');
        $data['view_file'] = "manage";
        $this->load->module('templates');
        $this->templates->admin($data);
    }
     
    function view(){
        $this->load->module('site_security');
        $this->site_security->_make_sure_is_admin();
        
        $update_id = $this->uri->segment(3);
        $this->_set_to_opened($update_id);
        if(!is_numeric($update_id)){
            redirect('enquiries/inbox');
        }
        
        //set options for the ranking..
        $options[''] = 'Select...';
        $options['1'] = 'One Star';
        $options['2'] = 'Two Stars';
        $options['3'] = 'Three Stars';
        $options['4'] = 'Four Stars';
        $options['5'] = 'Five Stars';

        $data['options'] = $options;
        $data['update_id'] = $update_id;
        $data['query'] = $this->get_where($update_id);
        $data['headline'] = "Enquiry ID ".$update_id;
        // $data['query'] = $this->get('lastname');
        $data['view_file'] = "View";
        $this->load->module('templates');
        $this->templates->admin($data);
    }

    function _set_to_opened($update_id){
        $data['opened'] = 1;
        $this->_update($update_id,$data);
    }

        
    function inbox(){
        $this->output->enable_profiler(true);
        $this->load->module('site_security');
        $this->site_security->_make_sure_is_admin();
        
        $folder_type = "inbox";
        $data['query'] = $this->_fetch_enquires($folder_type);
        $data['folder_type'] = ucfirst($folder_type);
        $data['flash'] = $this->session->set_flashdata('item');
        
        //$data['view_module'] = "store_items";
        $data['headline'] = "Manage Account";
        // $data['query'] = $this->get('lastname');
        $data['view_file'] = "view_enquiries";
        $this->load->module('templates');
        $this->templates->admin($data);
    }

    function _fetch_enquires($folder_type){
        $mysql_query = "
            SELECT
            enquiries.*,
            store_accounts.username,
            store_accounts.firstname,
            store_accounts.company
            FROM
            enquiries
            LEFT JOIN store_accounts ON enquiries.sent_by = store_accounts.id
            WHERE
            enquiries.sent_to = 0
            ORDER BY enquiries.date_created DESC
        ";
        $query = $this->_custom_query($mysql_query);
        // print_r($query->result());die();
        return $query;
    }

    function _draw_customer_inbox($customer_id){
        // print_r($_SESSION);die();
        $folder_type = "inbox";
        $data['customer_id'] = $customer_id;
        $data['query'] = $this->_fetch_customer_enquires($folder_type,$customer_id);
        $data['folder_type'] = ucfirst($folder_type);
        $data['flash'] = $this->session->set_flashdata('item');
        $this->load->view('customer_inbox',$data);
    }

    function _fetch_customer_enquires($folder_type,$customer_id){
        $mysql_query = "
            SELECT
            enquiries.*,
            store_accounts.username,
            store_accounts.firstname,
            store_accounts.company
            FROM
            enquiries
            INNER JOIN store_accounts ON enquiries.sent_to = store_accounts.id
            WHERE
            enquiries.sent_to = $customer_id
            ORDER BY enquiries.date_created DESC
        ";
        $query = $this->_custom_query($mysql_query);
        // print_r($query->result());die();
        // print_r($query->result());die();
        return $query;
    }

     function create(){
        $this->load->library('session'); 
        $this->load->module('site_security');
        $this->site_security->_make_sure_is_admin();
        
        $update_id = $this->uri->segment(3);
        
        $submit = $this->input->post('submit',true);
        $this->load->module('timedate');
        if($submit == 'Submit'){
            //process form
            $this->load->library('form_validation');
            $this->form_validation->set_rules('sent_to', 'Recipient', 'required');
            $this->form_validation->set_rules('subject', 'Subject', 'required|max_length[250]');
            $this->form_validation->set_rules('message', 'Message', 'required');
            
            if($this->form_validation->run() == true){
                //get form variables
                $data = $this->fetch_data_from_post();
                    // inser new item
                    $data['date_created'] = time();
                    $data['sent_by'] = 0;
                    $data['opened'] = 0;
                    $data['code'] = $this->site_security->generate_random_string(6);
                    $data['message'] = strip_tags($data['message']);
                    $this->_insert($data);
                    $flash_msg = "The Message Successfully Sent.";
                    $value = '<div class="alert alert-success" role="alert">'.$flash_msg.'</div>';
                    $data['flash'] = $this->session->set_flashdata('item', $value);
                    redirect('enquiries/inbox/');
                
            }
        }elseif($submit == 'Cancel'){
            redirect('enquiries/inbox');
        }

        if((is_numeric($update_id)) && ($submit != 'Submit')){
            $data = $this->fetch_data_from_db($update_id);
            $data['message'] = "<br><br>
            -------------------------------------------------------<br>
            The original message is below: <br></br>".$data['message'];
        }else {
            $data = $this->fetch_data_from_post();
        }
        
        if(!is_numeric($update_id)){
            $data['headline'] = "Compose New Message";
        }else{
            $data['headline'] = "Reply To Message";
        }

        $data['options'] = $this->_fetch_customers_as_options();
        $data['update_id'] = $update_id;
        $data['flash'] = $this->session->flashdata('item');
        // $data['view_module'] = "blog";
        $data['view_file'] = "create";
        $this->load->module('templates');
        $this->templates->admin($data);
    }

    function _fetch_customers_as_options(){
        //for the dropdown 
        $options[''] = "Please select..";
        $this->load->module('store_accounts');
        $query = $this->store_accounts->get('lastname');
        foreach($query->result() as $row){
            $customer_name = $row->firstname.' '.$row->lastname;
            $company_length = strlen($row->company);
            if($company_length  > 2){
                $customer_name .= " from ".$row->company;
            }

            $customer_name = trim($customer_name);
            if($customer_name != ""){
                $options[$row->id] = $customer_name;
            }

        }
        if(!isset($options)){$options = "";}
        return $options;

    }

    function fetch_data_from_post(){
        $data['sent_to'] = $this->input->post('sent_to');
        $data['subject'] = $this->input->post('subject',true);
        $data['message'] = $this->input->post('message',true);
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
            $data['urgent'] = $row->urgent;
        }

        if(!isset($data)){
            $data="";
        }
        return $data;
    }

    function get($order_by) {
    $this->load->model('Mdl_enquiries');
    $query = $this->Mdl_enquiries->get($order_by);
    return $query;
    }

    function get_with_limit($limit, $offset, $order_by) {
    $this->load->model('Mdl_enquiries');
    $query = $this->Mdl_enquiries->get_with_limit($limit, $offset, $order_by);
    return $query;
    }

    function get_where($id) {
    $this->load->model('Mdl_enquiries');
    $query = $this->Mdl_enquiries->get_where($id);
    return $query;
    }

    function get_with_double_condition($col1, $value1,$col2,$value2) {
    $this->load->model('Mdl_enquiries');
    $query = $this->Mdl_enquiries->get_where_custom($col1, $value1,$col2,$value2);
    return $query;
    }

    function get_where_custom($col, $value) {
    $this->load->model('Mdl_enquiries');
    $query = $this->Mdl_enquiries->get_where_custom($col, $value);
    return $query;
    }

    function _insert($data) {
    $this->load->model('Mdl_enquiries');
    $this->Mdl_enquiries->_insert($data);
    }

    function _update($id, $data) {
    $this->load->model('Mdl_enquiries');
    $this->Mdl_enquiries->_update($id, $data);
    }

    function _delete($id) {
    $this->load->model('Mdl_enquiries');
    $this->Mdl_enquiries->_delete($id);
    }

    function count_where($column, $value) {
    $this->load->model('Mdl_enquiries');
    $count = $this->Mdl_enquiries->count_where($column, $value);
    return $count;
    }

    function get_max() {
    $this->load->model('Mdl_enquiries');
    $max_id = $this->Mdl_enquiries->get_max();
    return $max_id;
    }

    function _custom_query($mysql_query) {
    $this->load->model('Mdl_enquiries');
    $query = $this->Mdl_enquiries->_custom_query($mysql_query);
    return $query;
    }

}