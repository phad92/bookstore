<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Store_item_colors extends MX_Controller
{

function __construct() {
parent::__construct();
}

function submit($update_id){
    if(!is_numeric($update_id)){
        redirect('site_security/not_allowed');
    }
    $this->load->library('session'); 
    $this->load->module('site_security');
    $this->site_security->_make_sure_is_admin();

    $submit = $this->input->post('submit');
    $color = trim($this->input->post('color'));

    if($submit == "Finished"){
        redirect('store_items/create/'.$update_id);
    }elseif($submit == "Submit"){
        $data['item_id'] = $update_id;
        $data['color'] = $color;
        $this->_insert($data);
        
        $update_id = $this->get_max();//gets the ID of new item
        $flash_msg = "color Inserted Successfully";
        $value = '<div class="alert alert-success" role="alert">'.$flash_msg.'</div>';
        $this->session->set_flashdata('item', $value);
    }
    
    redirect('store_item_colors/update/'.$update_id);
}

function _delete_for_item($item_id){
     $mysql_query = "DELETE FROM store_item_colors WHERE item_id = $item_id";
     $query =  $this->_custom_query($mysql_query);
}

function delete($update_id){
    if(!is_numeric($update_id)){
        redirect('site_security/not_allowed');
    }
    $this->load->library('session'); 
    $this->load->module('site_security');
    $this->site_security->_make_sure_is_admin();
    
    //fetch this item
    $query = $this->get_where($update_id);
    foreach ($query->result() as $row) {
        $item_id = $row->item_id;
    }
    
    $this->_delete($update_id);

    $flash_msg = "Colour Deleted Successfully";
    $value = '<div class="alert alert-success" role="alert">'.$flash_msg.'</div>';
    $this->session->set_flashdata('item', $value);
    redirect('store_item_colors/update/'.$item_id);
}

function update($update_id){
    if(!is_numeric($update_id)){
        redirect('site_security/not_allowed'); 
    }
    $this->load->library('session'); 
    $this->load->module('site_security');
    $this->site_security->_make_sure_is_admin();
    
    $data['query'] = $this->get_where_custom('item_id',$update_id);
    $data['num_rows'] = $data['query']->num_rows();
 

    $data['update_id'] = $update_id;
    $data['headline'] = "New Colour";
    $data['flash'] = $this->session->flashdata('item');
    // $data['view_module'] = "store_items";
    $data['view_file'] = "update";
    $this->load->module('templates');
    $this->templates->admin($data);
}


function get($order_by) {
$this->load->model('Mdl_store_item_colors');
$query = $this->Mdl_store_item_colors->get($order_by);
return $query;
}

function get_with_limit($limit, $offset, $order_by) {
$this->load->model('Mdl_store_item_colors');
$query = $this->Mdl_store_item_colors->get_with_limit($limit, $offset, $order_by);
return $query;
}

function get_where($id) {
$this->load->model('Mdl_store_item_colors');
$query = $this->Mdl_store_item_colors->get_where($id);
return $query;
}

function get_where_custom($col, $value) {
$this->load->model('Mdl_store_item_colors');
$query = $this->Mdl_store_item_colors->get_where_custom($col, $value);
return $query;
}

function _insert($data) {
$this->load->model('Mdl_store_item_colors');
$this->Mdl_store_item_colors->_insert($data);
}

function _update($id, $data) {
$this->load->model('Mdl_store_item_colors');
$this->Mdl_store_item_colors->_update($id, $data);
}

function _delete($id) {
$this->load->model('Mdl_store_item_colors');
$this->Mdl_store_item_colors->_delete($id);
}

function count_where($column, $value) {
$this->load->model('Mdl_store_item_colors');
$count = $this->Mdl_store_item_colors->count_where($column, $value);
return $count;
}

function get_max() {
$this->load->model('Mdl_store_item_colors');
$max_id = $this->Mdl_store_item_colors->get_max();
return $max_id;
}

function _custom_query($mysql_query) {
$this->load->model('Mdl_store_item_colors');
$query = $this->Mdl_store_item_colors->_custom_query($mysql_query);
return $query;
}

}