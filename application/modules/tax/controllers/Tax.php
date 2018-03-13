<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Tax extends MX_Controller
{

function __construct() {
parent::__construct();
}

function delete($update_id){
    if(!is_numeric($update_id)){
        redirect('site_security/not_allowed');
    }
    $this->load->library('session'); 
    $this->load->module('site_security');
    $this->site_security->_make_sure_is_admin();

    $submit = $this->input->post('submit');
    if($submit == 'Cancel'){
        redirect('tax/create/'.$update_id);
    }else {
        // $this->_process_delete($update_id);
        $this->_delete($update_id);
        $flash_msg = "Tax Delete Successfull";
        $value = "<div class='alert alert-success' role='alert'>".$flash_msg."</div>";
        $data['flash'] = $this->session->flashdata('item',$value);
        redirect('tax/manage');
    }
}

function deleteconf($update_id){
    if(!is_numeric($update_id)){
        redirect('site_security/not_allowed');
    }
    $this->load->library('session'); 
    $this->load->module('site_security');
    $this->site_security->_make_sure_is_admin();
    
    
    $data['headline'] = "Confirm Delete";
    $data['update_id'] = $update_id;
    
    // $data['view_module'] = "tax";
    $data['view_file'] = "deleteconf";
    $this->load->module('templates');
    $this->templates->admin($data);
}

function manage(){
    $this->load->module('site_security');
    $this->site_security->_make_sure_is_admin();
    
    $data['query'] = $this->get('tax_type');
    //$data['view_module'] = "tax";
    $data['headline'] = "Manage Tax";
    $data['flash'] = $this->session->set_flashdata('item');
    $data['view_file'] = "manage";
    $this->load->module('templates');
    $this->templates->admin($data);
}

function create(){
        $this->load->library('session'); 
        $this->load->module('site_security');
        $this->site_security->_make_sure_is_admin();
        
        $update_id = $this->uri->segment(3);
        
        $submit = $this->input->post('submit',true);
        if($submit == 'Submit'){
            //process form
            $this->load->library('form_validation');
            $this->form_validation->set_rules('tax_type', 'Tax Type', 'required|max_length[240]');
            $this->form_validation->set_rules('tax_rate', 'Tax Rate', 'required|numeric');
            $this->form_validation->set_rules('is_percent', 'Is Percent', 'required|numeric');
            
            if($this->form_validation->run() == true){
                //get form variables
                $data = $this->fetch_data_from_post();
                if(is_numeric($update_id)){
                    //update the item detail
                    $this->_update($update_id,$data);
                    $flash_msg = "Tax updated successfully";
                    $value = '<div class="alert alert-success" role="alert">'.$flash_msg.'</div>';
                    $this->session->set_flashdata('item', $value);
                    redirect('tax/create/'.$update_id);
                }else{
                    // print_r($data);die();
                    // inser new item
                    $this->_insert($data);
                    $update_id = $this->get_max();//gets the ID of new item
                    $flash_msg = "Tax Inserted Successfully";
                    $value = '<div class="alert alert-success" role="alert">'.$flash_msg.'</div>';
                    $data['flash'] = $this->session->set_flashdata('item', $value);
                    redirect('tax/create/'.$update_id);
                }
            }
        }elseif($submit == 'Cancel'){
            redirect('tax/manage');
        }
        if((is_numeric($update_id)) && ($submit = 'Submit')){
            $data = $this->fetch_data_from_db($update_id);
        }else{
            $data = $this->fetch_data_from_post();
            
        }
        
        if(!is_numeric($update_id)){
            $data['headline'] = "Add New Tax";
        }else{
            $data['headline'] = "Update Tax Details";
        }

        
        $data['flash'] = $this->session->flashdata('item');
         $data['update_id'] = $update_id;
        // $data['view_module'] = "tax";
        $data['view_file'] = "create";
        $this->load->module('templates');
        $this->templates->admin($data);
    }

    function fetch_data_from_post(){
        $data['tax_type'] = trim($this->input->post('tax_type'));
        $data['tax_rate'] = trim($this->input->post('tax_rate'));
        $data['is_percent'] = trim($this->input->post('is_percent'));
        
        return $data;
    }

    function fetch_data_from_db($update_id){
        if(!is_numeric($update_id)){
                redirect('site_security/not_allowed');
            }
        $query = $this->get_where($update_id);
        foreach ($query->result() as $row) {
            $data['tax_type'] = $row->tax_type;
            $data['tax_rate'] = $row->tax_rate;
            $data['is_percent'] = $row->is_percent;
        }

        if(!isset($data)){
            $data="";
        }
        return $data;
    }

function get($order_by) {
$this->load->model('Mdl_tax');
$query = $this->Mdl_tax->get($order_by);
return $query;
}

function get_with_limit($limit, $offset, $order_by) {
$this->load->model('Mdl_tax');
$query = $this->Mdl_tax->get_with_limit($limit, $offset, $order_by);
return $query;
}

function get_where($id) {
$this->load->model('Mdl_tax');
$query = $this->Mdl_tax->get_where($id);
return $query;
}

function get_where_custom($col, $value) {
$this->load->model('Mdl_tax');
$query = $this->Mdl_tax->get_where_custom($col, $value);
return $query;
}

function _insert($data) {
$this->load->model('Mdl_tax');
$this->Mdl_tax->_insert($data);
}

function _update($id, $data) {
$this->load->model('Mdl_tax');
$this->Mdl_tax->_update($id, $data);
}

function _delete($id) {
$this->load->model('Mdl_tax');
$this->Mdl_tax->_delete($id);
}

function count_where($column, $value) {
$this->load->model('Mdl_tax');
$count = $this->Mdl_tax->count_where($column, $value);
return $count;
}

function get_max() {
$this->load->model('Mdl_tax');
$max_id = $this->Mdl_tax->get_max();
return $max_id;
}

function _custom_query($mysql_query) {
$this->load->model('Mdl_tax');
$query = $this->Mdl_tax->_custom_query($mysql_query);
return $query;
}

}