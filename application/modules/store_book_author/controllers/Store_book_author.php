<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Store_book_author extends MX_Controller
{

function __construct() {
parent::__construct();
}

function view($update_id){
        if(!is_numeric($update_id)){
            redirect('site_security/not_allowed');
        }
        
        $this->load->module('site_settings');
        $this->load->module('custom_pagination');
        //fetch the cat details 
        $data = $this->fetch_data_from_db($update_id);
        // print_r($data);die();
        $use_limit = false;
        $mysql_query = $this->_generate_mysql_query($update_id,$use_limit);
        $query = $this->_custom_query($mysql_query);
        $total_items = $query->num_rows();

        $data['author_segment'] = $this->site_settings->_get_author_segments();
        // echo $data['item_segment'];die();
        $data['currency_symbol'] = $this->site_settings->_get_currency_symbol();
        //fetch items belonging to this cat
        $data['view_module'] = 'store_book_categories';
        $data['headline'] = "Confirm Delete";
        $data['update_id'] = $update_id;
        $data['flash'] = $this->session->flashdata('item');
        $data['view_file'] = "view";
        // print_r(json_encode($data));die();
        $this->load->module('templates');
        $this->templates->public_bootstrap($data);
    }

function _get_author_id_from_author_url($author_id){
    $query = $this->get_where_custom('author_url',$author_id);
    foreach ($query->result() as $row) {
        $cat_id = $row->id;
    }

    if(!isset($cat_id)){
        $cat_id = 0;
    }
    return $cat_id;
}
function submit($update_id){
    if(!is_numeric($update_id)){
        redirect('site_security/not_allowed');
    }
    $this->load->library('session'); 
    $this->load->module('site_security');
    $this->site_security->_make_sure_is_admin();

    $submit = $this->input->post('submit');
    $name = trim($this->input->post('name'));
    
    if($submit == "Finished"){
        redirect('store_books/create/'.$update_id);
    }elseif($submit == "Submit"){

        if($name != ""){
            // $data['book_id'] = $update_id;
            $data['name'] = $name;
            $data['author_url'] =  url_title(strtolower($name));
            $this->_insert($data);

            $author_id = $this->get_max();
            $this->_assign_book_to_author($update_id, $author_id);
            // $update_id = $this->get_max();//gets the ID of new item
            $flash_msg = "Book's Author was Inserted Successfully";
            $value = '<div class="alert alert-success" role="alert">'.$flash_msg.'</div>';
            $this->session->set_flashdata('item', $value);
        }
    }
    
    redirect('store_book_author/update/'.$update_id);
}

function _assign_book_to_author($book_id, $author_id){
    $this->load->module('store_book_assign_author');
    $data['book_id'] = $book_id;
    $data['author_id'] = $author_id;
    return $this->store_book_assign_author->_insert($data);
}

function delete(){
    $book_id = $this->uri->segment(3);
    $update_id = $this->uri->segment(4);

    if(!is_numeric($update_id)){
        redirect('site_security/not_allowed');
    }
    $this->load->library('session'); 
    $this->load->module('site_security');
    $this->site_security->_make_sure_is_admin();
    
    //fetch this item
    $query = $this->get_where($update_id);
    $author = $query->row();
    $author_id = $author->id;

    $this->_delete($update_id);
    $mysql_query = "DELETE FROM store_book_assign_author WHERE book_id = $book_id AND author_id = $update_id";
    $my_query = $this->_custom_query($mysql_query);

    $flash_msg = "Book's Author was Deleted Successfully";
    $value = '<div class="alert alert-success" role="alert">'.$flash_msg.'</div>';
    $this->session->set_flashdata('item', $value);
    redirect('store_book_author/update/'.$book_id);
}

function update($update_id){
    if(!is_numeric($update_id)){
        redirect('site_security/not_allowed');
    }
    $this->load->module('store_book_assign_author');
    $this->load->library('session'); 
    $this->load->module('site_security');
    $this->site_security->_make_sure_is_admin();

    // $mysql_query= $this->store_book_assign_author->get_where_custom('book_id',$update_id);
    $mysql_query = "
        SELECT
            store_books.id,
            store_book_author.`name`,
            store_book_author.author_url,
            store_book_author.id AS author_id
            FROM
            store_book_assign_author
            INNER JOIN store_book_author ON store_book_author.id = store_book_assign_author.author_id
            INNER JOIN store_books ON store_books.id = store_book_assign_author.book_id
            WHERE
            store_books.id = $update_id
        ";
    $data['query'] = $this->_custom_query($mysql_query);
    // print_r($query->result());die();
    
    $data['num_rows'] = $data['query']->num_rows();

    $data['update_id'] = $update_id;
    $data['headline'] = "New Homepage Offers";
    $data['flash'] = $this->session->flashdata('item');
    // $data['view_module'] = "store_items";
    $data['view_file'] = "update";
    $this->load->module('templates');
    $this->templates->admin($data);
}

function _generate_mysql_query($update_id,$use_limit){
    $mysql_query = "
            SELECT
            store_books.*,
            store_book_author.`name`,
            store_book_author.author_url,
            store_book_author.id AS author_id
            FROM
            store_book_assign_author
            INNER JOIN store_book_author ON store_book_author.id = store_book_assign_author.author_id
            INNER JOIN store_books ON store_books.id = store_book_assign_author.book_id
            WHERE
            store_books.id = $update_id
            ";
        if($use_limit = TRUE){
            $limit = $this->get_limit();
            $offset = $this->get_offset();
            $mysql_query .= " limit ".$offset.", ".$limit;
        }
        return $mysql_query;
}

function get_limit(){
    $limit = 5;
    return $limit;
}

function get_offset(){
    $offset = $this->uri->segment(4);
    if(!is_numeric($offset)){
        $offset = 0;
    }
    return $offset;
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
        $data['book_id'] = $row->book_id;
    }
    if(!isset($data)){
        $data="";
    }
    // print_r($query);die();
    return $data;
}


function get($order_by) {
$this->load->model('Mdl_store_book_author');
$query = $this->Mdl_store_book_author->get($order_by);
return $query;
}

function get_with_limit($limit, $offset, $order_by) {
$this->load->model('Mdl_store_book_author');
$query = $this->Mdl_store_book_author->get_with_limit($limit, $offset, $order_by);
return $query;
}

function get_where($id) {
$this->load->model('Mdl_store_book_author');
$query = $this->Mdl_store_book_author->get_where($id);
return $query;
}

function get_where_custom($col, $value) {
$this->load->model('Mdl_store_book_author');
$query = $this->Mdl_store_book_author->get_where_custom($col, $value);
return $query;
}

function _insert($data) {
$this->load->model('Mdl_store_book_author');
$this->Mdl_store_book_author->_insert($data);
}

function _update($id, $data) {
$this->load->model('Mdl_store_book_author');
$this->Mdl_store_book_author->_update($id, $data);
}

function _delete($id) {
$this->load->model('Mdl_store_book_author');
$this->Mdl_store_book_author->_delete($id);
}

function count_where($column, $value) {
$this->load->model('Mdl_store_book_author');
$count = $this->Mdl_store_book_author->count_where($column, $value);
return $count;
}

function get_max() {
$this->load->model('Mdl_store_book_author');
$max_id = $this->Mdl_store_book_author->get_max();
return $max_id;
}

function _custom_query($mysql_query) {
$this->load->model('Mdl_store_book_author');
$query = $this->Mdl_store_book_author->_custom_query($mysql_query);
return $query;
}

}