<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Homepage_offers extends MX_Controller
{

function __construct() {
parent::__construct();
}

function _draw_offers($block_data,$is_mobile= FALSE){
    // echo "<h1>Offers for $block_id</h1>";
    // $query = $this->get_where_custom('block_id',$block_id);
    $block_id= $block_data['block_id'];
    if($is_mobile == FALSE){
        $theme = $block_data['theme'];
    }
    $item_segments = $block_data['item_segments'];
    
    $mysql_query = "
        SELECT store_books.*
        FROM
        homepage_blocks
        INNER JOIN homepage_offers ON homepage_blocks.id = homepage_offers.block_id
        INNER JOIN store_books ON store_books.id = homepage_offers.item_id
        WHERE
        homepage_offers.block_id = $block_id; 
    ";
    $query = $this->_custom_query($mysql_query);
    $num_rows = $query->num_rows(); 
    if($num_rows > 0){
        $data['query'] = $query;
        $data['item_segments'] = $item_segments;
        if($is_mobile == FALSE){
            $data['theme'] = $theme;
            $view_file = 'offers';
        }else{
            $view_file = 'offers_jqm';
        }
        $this->load->view($view_file,$data);
    }
}

function _is_valid_item($item_id){
    if(!is_numeric($item_id)){
        return FALSE;
    }
    $this->load->module('store_books');
    $query = $this->store_books->get_where($item_id);
    $num_rows = $query->num_rows();
    if($num_rows > 0){
        return TRUE;
    }else {
            return FALSE;
    }
}

function submit($update_id){
    if(!is_numeric($update_id)){
        redirect('site_security/not_allowed');
    }
    $this->load->library('session'); 
    $this->load->module('site_security');
    $this->site_security->_make_sure_is_admin();

    $submit = $this->input->post('submit');
    $item_id = trim($this->input->post('item_id'));

    if($submit == "Finished"){
        redirect('homepage_blocks/manage');
    }elseif($submit == "Submit"){
        $is_valid_item = $this->_is_valid_item($item_id);
        if($is_valid_item == FALSE){
            $flash_msg = "The Item ID you submitted was not valid.";
            $value = '<div class="alert alert-danger" role="alert">'.$flash_msg.'</div>';
            $this->session->set_flashdata('item', $value);
            redirect('homepage_offers/update/'.$update_id);
        }

        if($item_id != ""){

            $data['block_id'] = $update_id;
            $data['item_id'] = $item_id;
            $this->_insert($data);
            
            // $update_id = $this->get_max();//gets the ID of new item
            $flash_msg = "New Offer was Inserted Successfully";
            $value = '<div class="alert alert-success" role="alert">'.$flash_msg.'</div>';
            $this->session->set_flashdata('item', $value);
        }
    }
    
    redirect('homepage_offers/update/'.$update_id);
}

function _delete_for_item($block_id){
     $mysql_query = "DELETE FROM homepage_offers WHERE block_id = $block_id";
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
    // print_r($query);
    foreach ($query->result() as $row) {
        $block_id = $row->block_id;
    }
   
    $this->_delete($update_id);

    $flash_msg = "Homepage Offers Deleted Successfully";
    $value = '<div class="alert alert-success" role="alert">'.$flash_msg.'</div>';
    $this->session->set_flashdata('item', $value);
    redirect('homepage_offers/update/'.$block_id);
}

function update($update_id){
    if(!is_numeric($update_id)){
        redirect('site_security/not_allowed');
    }
    $this->load->module('homepage_blocks');
    $this->load->library('session'); 
    $this->load->module('site_security');
    $this->site_security->_make_sure_is_admin();

    $data['query'] = $this->get_where_custom('block_id',$update_id);

    $data['num_rows'] = $data['query']->num_rows();

    $data['update_id'] = $update_id;
    $data['headline'] = "New Homepage Offers";
    $data['flash'] = $this->session->flashdata('item');
    // $data['view_module'] = "store_items";
    $data['view_file'] = "update";
    $this->load->module('templates');
    $this->templates->admin($data);
}

function get_item_title($item_id){
    $this->load->module('store_books');
    $query = $this->store_books->get_where($item_id);
    foreach($query->result() as $row){
        $book_title = $row->book_title;
    }

    return $book_title;
}

function fetch_data_from_post(){
    $data['cat_title'] = $this->input->post('cat_title');
    $data['book_parent_cat_id'] = $this->input->post('book_parent_cat_id');
    
    return $data;
}

function fetch_data_from_db($update_id){
    if(!is_numeric($update_id)){
            redirect('site_security/not_allowed');
        }
    $query = $this->get_where($update_id);
    foreach ($query->result() as $row) {
        $data['item_id'] = $row->item_id;
    }
    if(!isset($data)){
        $data="";
    }
    // print_r($query);die();
    return $data;
}


function get($order_by) {
$this->load->model('Mdl_homepage_offers');
$query = $this->Mdl_homepage_offers->get($order_by);
return $query;
}

function get_with_limit($limit, $offset, $order_by) {
$this->load->model('Mdl_homepage_offers');
$query = $this->Mdl_homepage_offers->get_with_limit($limit, $offset, $order_by);
return $query;
}

function get_where($id) {
$this->load->model('Mdl_homepage_offers');
$query = $this->Mdl_homepage_offers->get_where($id);
return $query;
}

function get_where_custom($col, $value) {
$this->load->model('Mdl_homepage_offers');
$query = $this->Mdl_homepage_offers->get_where_custom($col, $value);
return $query;
}

function _insert($data) {
$this->load->model('Mdl_homepage_offers');
$this->Mdl_homepage_offers->_insert($data);
}

function _update($id, $data) {
$this->load->model('Mdl_homepage_offers');
$this->Mdl_homepage_offers->_update($id, $data);
}

function _delete($id) {
$this->load->model('Mdl_homepage_offers');
$this->Mdl_homepage_offers->_delete($id);
}

function count_where($column, $value) {
$this->load->model('Mdl_homepage_offers');
$count = $this->Mdl_homepage_offers->count_where($column, $value);
return $count;
}

function get_max() {
$this->load->model('Mdl_homepage_offers');
$max_id = $this->Mdl_homepage_offers->get_max();
return $max_id;
}

function _custom_query($mysql_query) {
$this->load->model('Mdl_homepage_offers');
$query = $this->Mdl_homepage_offers->_custom_query($mysql_query);
return $query;
}

}