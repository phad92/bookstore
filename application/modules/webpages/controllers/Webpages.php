<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Webpages extends MX_Controller
{

function __construct() {
parent::__construct();
}

function _process_delete($update_id){
        //delete item record
        $this->_delete($update_id);
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
        redirect('webpages/create/'.$update_id);
    }else {
        $this->_process_delete($update_id);
        
        $flash_msg = "Item Delete Successfull";
        $value = "<div class='alert alert-success' role='alert'>".$flash_msg."</div>";
        $data['flash'] = $this->session->flashdata('item',$value);
        redirect('store_items/manage');
    }
}

function deleteconf($update_id){
    if(!is_numeric($update_id)){
        redirect('site_security/not_allowed');
    }elseif($update_id < 3){
        // Preventive measure from deleting home and contactus pages
        redirect('site_security/not_allowed');
    }
    $this->load->library('session'); 
    $this->load->module('site_security');
    $this->site_security->_make_sure_is_admin();
    
    
    $data['headline'] = "Confirm Delete";
    $data['update_id'] = $update_id;
    
    // $data['view_module'] = "store_items";
    $data['view_file'] = "deleteconf";
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
            $this->form_validation->set_rules('page_title', 'Page Title', 'required|max_length[250]');
            $this->form_validation->set_rules('page_content', 'Page Content', 'required');
            
            if($this->form_validation->run() == true){
                //get form variables
                $data = $this->fetch_data_from_post();
                $data['page_url'] = url_title($data['page_title']);
                if(is_numeric($update_id)){
                    if($update_id < 3){
                        unset($data['page_url']);
                    }
                    //update the item detail
                    $this->_update($update_id,$data);
                    $flash_msg = "Item updated successfully";
                    $value = '<div class="alert alert-success" role="alert">'.$flash_msg.'</div>';
                    $this->session->set_flashdata('item', $value);
                    redirect('webpages/create/'.$update_id);
                }else{
                    // inser new item
                    $this->_insert($data);
                    $update_id = $this->get_max();//gets the ID of new item
                    $flash_msg = "Item Inserted Successfully";
                    $value = '<div class="alert alert-success" role="alert">'.$flash_msg.'</div>';
                    $data['flash'] = $this->session->set_flashdata('item', $value);
                    redirect('webpages/create/'.$update_id);
                }
            }
        }elseif($submit == 'Cancel'){
            redirect('webpages/manage');
        }
        if((is_numeric($update_id)) && ($submit = 'Submit')){
            $data = $this->fetch_data_from_db($update_id);
        }else{
            $data = $this->fetch_data_from_post();
            // print_r($data);
        }
        
        if(!is_numeric($update_id)){
            $data['headline'] = "Create New Page";
        }else{
            $data['headline'] = "Update Page Details";
        }


        $data['update_id'] = $update_id;
        $data['flash'] = $this->session->flashdata('item');
        // $data['view_module'] = "store_items";
        $data['view_file'] = "create";
        $this->load->module('templates');
        $this->templates->admin($data);
    }

 function manage(){
    $this->load->module('site_security');
    $this->site_security->_make_sure_is_admin();
    
    $data['query'] = $this->get('page_url');
    // print_r($data);die();
    //$data['view_module'] = "store_items";
    $data['headline'] = "Manage Item";
    $data['flash'] = $this->session->set_flashdata('item');
    $data['view_file'] = "manage";
    $this->load->module('templates');
    $this->templates->admin($data);
}

 function fetch_data_from_post(){
        $data['page_title'] = $this->input->post('page_title');
        $data['page_url'] = $this->input->post('page_url');
        $data['page_keywords'] = $this->input->post('page_keywords');
        $data['page_description'] = $this->input->post('page_description');
        $data['page_content'] = $this->input->post('page_content');
        return $data;
    }

    function fetch_data_from_db($update_id){
        if(!is_numeric($update_id)){
                redirect('site_security/not_allowed');
            }
        $query = $this->get_where($update_id);
        foreach ($query->result() as $row) {
            $data['page_title'] = $row->page_title;
            $data['page_url'] = $row->page_url;
            $data['page_keywords'] = $row->page_keywords;
            $data['page_description'] = $row->page_description;
            $data['page_content'] = $row->page_content;
        }

        if(!isset($data)){
            $data="";
        }
        return $data;
    }



function get($order_by) {
$this->load->model('Mdl_webpages');
$query = $this->Mdl_webpages->get($order_by);
return $query;
}

function get_with_limit($limit, $offset, $order_by) {
$this->load->model('Mdl_webpages');
$query = $this->Mdl_webpages->get_with_limit($limit, $offset, $order_by);
return $query;
}

function get_where($id) {
$this->load->model('Mdl_webpages');
$query = $this->Mdl_webpages->get_where($id);
return $query;
}

function get_where_custom($col, $value) {
$this->load->model('Mdl_webpages');
$query = $this->Mdl_webpages->get_where_custom($col, $value);
return $query;
}

function _insert($data) {
$this->load->model('Mdl_webpages');
$this->Mdl_webpages->_insert($data);
}

function _update($id, $data) {
$this->load->model('Mdl_webpages');
$this->Mdl_webpages->_update($id, $data);
}

function _delete($id) {
$this->load->model('Mdl_webpages');
$this->Mdl_webpages->_delete($id);
}

function count_where($column, $value) {
$this->load->model('Mdl_webpages');
$count = $this->Mdl_webpages->count_where($column, $value);
return $count;
}

function get_max() {
$this->load->model('Mdl_webpages');
$max_id = $this->Mdl_webpages->get_max();
return $max_id;
}

function _custom_query($mysql_query) {
$this->load->model('Mdl_webpages');
$query = $this->Mdl_webpages->_custom_query($mysql_query);
return $query;
}

}