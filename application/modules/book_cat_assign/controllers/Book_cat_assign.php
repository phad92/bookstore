<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Book_cat_assign extends MX_Controller
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
    
    //fetch this book
    $query = $this->get_where($update_id);
    foreach ($query->result() as $row) {
        $book_id = $row->book_id;
    }
    
    $this->_delete($update_id);

    $flash_msg = "Assigned Category for this book was removed";
    $value = '<div class="alert alert-success" role="alert">'.$flash_msg.'</div>';
    $this->session->set_flashdata('book', $value);
    redirect('book_cat_assign/update/'.$book_id);
}

function submit($book_id){
    if(!is_numeric($book_id)){
        redirect('site_security/not_allowed');
    }
    $this->load->library('session'); 
    $this->load->module('site_security');
    $this->site_security->_make_sure_is_admin();

    $submit = $this->input->post('submit');
    $cat_id = trim($this->input->post('cat_id'));

    if($submit == "Finished"){
        redirect('store_books/create/'.$book_id);
    }elseif($submit == "Submit"){
        if($cat_id != ""){
            $data['book_id'] = $book_id;
            $data['cat_id'] = $cat_id;
            $this->_insert($data);

            $this->load->module('store_book_categories');
            $cat_title= $this->store_book_categories->_get_cat_title($cat_id);
    
            $flash_msg = "Item was successfully assigned to the ".$cat_title." category.";
            $value = '<div class="alert alert-success" role="alert">'.$flash_msg.'</div>';
            $this->session->set_flashdata('book', $value);
        }
        
    }
    
    redirect('book_cat_assign/update/'.$book_id);
}

function update($book_id){
    if(!is_numeric($book_id)){
        redirect('site_security/not_allowed');
    }
    // print($book_id);die();
    $this->load->library('session'); 
    $this->load->module('site_security');
    $this->site_security->_make_sure_is_admin();
    //get array of all sub categories on the site
    $this->load->module('store_book_categories');
    $sub_categories = $this->store_book_categories->_get_all_sub_cats_for_dropdown();
    // $query = $this->store_book_categories->get_where_custom('parent_cat_id !=','0');
    // print_r($sub_categories);die();
    //get an array of all assigned categories
    $query = $this->get_where_custom('book_id',$book_id);
    $data['query'] = $query;
    $data['num_rows'] = $query->num_rows();
    foreach($query->result() as $row){
        $cat_title = $this->store_book_categories->_get_cat_title($row->cat_id);
        $parent_cat_title = $this->store_book_categories->_get_parent_cat_title($row->cat_id);
        $assigned_categories[$row->cat_id] = $parent_cat_title.">".$cat_title;
    }
    // print_r($query->result());die();

    if(!isset($assigned_categories)){
        $assigned_categories = ""; 
    }else{
        // the book has been assign to atleast on function
        // $sub_categories = array_diff($sub_categories,$assigned_categories);
    
        $sub_categories = array_diff($sub_categories,$assigned_categories);
    }
    
    // $this->output->set_content_type('application/json')->set_output(json_encode($sub_categories));

    
    $data['options'] = $sub_categories;
    $data['book_id'] = $book_id;
    $data['headline'] = "Assign Category";
    $data['flash'] = $this->session->flashdata('book');
    // $data['view_module'] = "store_books";
    $data['view_file'] = "update";
    $this->load->module('templates');
    $this->templates->admin($data);

}

function get_categories_for_book($book_id){
    $query = $this->get_where_custom('book_id',$book_id);
    return $query;
}

function get($order_by) {
$this->load->model('Mdl_book_cat_assign');
$query = $this->Mdl_book_cat_assign->get($order_by);
return $query;
}

function get_with_limit($limit, $offset, $order_by) {
$this->load->model('Mdl_book_cat_assign');
$query = $this->Mdl_book_cat_assign->get_with_limit($limit, $offset, $order_by);
return $query;
}

function get_where($id) {
$this->load->model('Mdl_book_cat_assign');
$query = $this->Mdl_book_cat_assign->get_where($id);
return $query;
}

function get_where_custom($col, $value) {
$this->load->model('Mdl_book_cat_assign');
$query = $this->Mdl_book_cat_assign->get_where_custom($col, $value);
return $query;
}

function _insert($data) {
$this->load->model('Mdl_book_cat_assign');
$this->Mdl_book_cat_assign->_insert($data);
}

function _update($id, $data) {
$this->load->model('Mdl_book_cat_assign');
$this->Mdl_book_cat_assign->_update($id, $data);
}

function _delete($id) {
$this->load->model('Mdl_book_cat_assign');
$this->Mdl_book_cat_assign->_delete($id);
}

function count_where($column, $value) {
$this->load->model('Mdl_book_cat_assign');
$count = $this->Mdl_book_cat_assign->count_where($column, $value);
return $count;
}

function get_max() {
$this->load->model('Mdl_book_cat_assign');
$max_id = $this->Mdl_book_cat_assign->get_max();
return $max_id;
}

function _custom_query($mysql_query) {
$this->load->model('Mdl_book_cat_assign');
$query = $this->Mdl_book_cat_assign->_custom_query($mysql_query);
return $query;
}

}