<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Store_book_detail extends MX_Controller
{
    
    function __construct() {
        parent::__construct();
    }

    function create(){
        $book_id = $this->uri->segment(3);
        if(!is_numeric($book_id)){
            redirect('store_book_datail/manage');
        }
        $this->load->library('session'); 
        $this->load->module('site_security');
        $this->site_security->_make_sure_is_admin();
        $submit = $this->input->post('submit');
        
        if($submit == 'Submit'){
            $this->load->library('form_validation');
            $this->form_validation->set_rules('isbn_10', 'ISBN', 'required|max_length[240]');
            if($this->form_validation->run() == true){
                $data = $this->fetch_data_from_post();
                $data['book_id'] = $book_id;
                //update the item detail
                $this->_insert($data);
                
                $flash_msg = "Book's Detail updated successfully";
                $value = '<div class="alert alert-success" role="alert">'.$flash_msg.'</div>';
                $this->session->set_flashdata('item', $value);
                redirect('store_books/manage/');
            }
            
        }elseif ($submit == "Cancel") {
            redirect('store_books/create/'.$book_id);
        }
        
        // if((is_numeric($update_id)) && ($submit == 'Submit')){
            
        // }else {
        //     $data = $this->fetch_data_from_post();
        // }
        
        $data['headline'] = "Add Book Detail";
        $data['update_id'] = $book_id;
        $data['flash'] = $this->session->flashdata('item');
        // $data['view_module'] = "store_items";
        $data['view_file'] = "create";
        $this->load->module('templates');
        $this->templates->admin($data);
    }

    function update(){
        $book_id = $this->uri->segment(3);
        if(!is_numeric($book_id)){
            //  redirect('store_book_datail/manage');
             var_dump($book_id);
        }
        $submit = $this->input->post('submit');
        $data = $this->fetch_data_from_db($book_id);

        if($submit == "Submit"){
            $this->load->library('form_validation');
            $this->form_validation->set_rules('isbn_10', 'ISBN', 'required|max_length[240]');
            if($this->form_validation->run() === true){
                $data = $this->fetch_data_from_post();
                $this->_update($data['id'],$data);
            }
        }elseif($submit == "Cancel"){
            redirect('store_books/create/'.$book_id);
        }

        $data['headline'] = "Update Book Detail";
        $data['update_id'] = $book_id;
        $data['flash'] = $this->session->flashdata('item');
        $data['view_module'] = "store_book_detail";
        $data['view_file'] = "update";
        $this->load->module('templates');
        $this->templates->admin($data);
    }

    function autogen(){
        $mysql_query = "SHOW columns FROM store_book_detail";
        $query = $this->_custom_query($mysql_query);
        foreach ($query->result() as $row) {
            $title = $row->Field;
            if($title != 'id'){
                echo "$"."data['$title'] = "."$"."this->input->post('$title'); </br> ";
            }
        }
        echo "<hr>";
        foreach ($query->result() as $row) {
            $title = $row->Field;
            if($title != 'id'){
                echo "$"."data['$title'] = "."$"."row->$title; </br> ";
            }
        }
        echo "<hr>";
        foreach ($query->result() as $row) {
            $title = $row->Field;
            if($title != 'id'){
                echo "$"."data['$title'] = "."$"."row->$title; </br> ";
            }
        }
        echo "<hr>";
        foreach ($query->result() as $row) {
            $title = $row->Field;
            if($title != 'id'){
                $html = "
                <div class='control-group'>
                <label class='control-label' for='$title'>$title</label> 
                <div class='controls'> 
                    <input type='text' class='span6' id='$title' name='$title' value='<?php echo $"."$title?>'> 
                    </div>
                </div>
                
                ";

                echo htmlentities($html);
                echo "</br>";
            }
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
    $color = trim($this->input->post('color'));

    if($submit == "Finished"){
        redirect('store_items/create/'.$update_id);
    }elseif($submit == "Submit"){
        $data['book_id'] = $update_id;
        $data['color'] = $color;
        $this->_insert($data);
    
        $update_id = $this->get_max();//gets the ID of new item
        $flash_msg = "color Inserted Successfully";
        $value = '<div class="alert alert-success" role="alert">'.$flash_msg.'</div>';
        $this->session->set_flashdata('item', $value);
    }
    
    redirect('store_book_detail/update/'.$update_id);
}

function fetch_data_from_post(){
    $data['id'] = $this->input->post('id'); 
    $data['isbn_10'] = $this->input->post('isbn_10'); 
    $data['isbn_13'] = $this->input->post('isbn_13'); 
    $data['book_format'] = $this->input->post('book_format'); 
    $data['publisher'] = $this->input->post('publisher'); 
    $data['published_date'] = $this->input->post('published_date'); 
    $data['edition'] = $this->input->post('edition'); 
    $data['dimension'] = $this->input->post('dimension'); 
    return $data;
    
}

function fetch_data_from_db($book_id){

    if(!is_numeric($book_id)){
        redirect('site_security/not_allowed');
    }
    $mysql_query = "SELECT * FROM store_book_detail WHERE book_id = $book_id";
    $query = $this->_custom_query($mysql_query);
    foreach ($query->result() as $row) {
        $data['id'] = $row->id;
        $data['isbn_10'] = $row->isbn_10; 
        $data['isbn_13'] = $row->isbn_13; 
        
        $data['publisher'] = $row->publisher; 
        $data['published_date'] = $row->published_date; 
        $data['edition'] = $row->edition; 
        $data['dimension'] = $row->dimension; 
        $data['book_id'] = $book_id;
        
    }

    if(!isset($data)){
        $data="";
    }
    return $data;
}

function _delete_for_item($book_id){
     $mysql_query = "DELETE FROM store_book_detail WHERE book_id = $book_id";
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
        $book_id = $row->book_id;
    }
    
    $this->_delete($update_id);

    $flash_msg = "Colour Deleted Successfully";
    $value = '<div class="alert alert-success" role="alert">'.$flash_msg.'</div>';
    $this->session->set_flashdata('item', $value);
    redirect('store_book_detail/update/'.$book_id);
}




function get($order_by) {
$this->load->model('Mdl_store_book_detail');
$query = $this->Mdl_store_book_detail->get($order_by);
return $query;
}

function get_with_limit($limit, $offset, $order_by) {
$this->load->model('Mdl_store_book_detail');
$query = $this->Mdl_store_book_detail->get_with_limit($limit, $offset, $order_by);
return $query;
}

function get_where($id) {
$this->load->model('Mdl_store_book_detail');
$query = $this->Mdl_store_book_detail->get_where($id);
return $query;
}

function get_where_custom($col, $value) {
$this->load->model('Mdl_store_book_detail');
$query = $this->Mdl_store_book_detail->get_where_custom($col, $value);
return $query;
}

function _insert($data) {
$this->load->model('Mdl_store_book_detail');
$this->Mdl_store_book_detail->_insert($data);
}

function _update($id, $data) {
$this->load->model('Mdl_store_book_detail');
$this->Mdl_store_book_detail->_update($id, $data);
}

function _delete($id) {
$this->load->model('Mdl_store_book_detail');
$this->Mdl_store_book_detail->_delete($id);
}

function count_where($column, $value) {
$this->load->model('Mdl_store_book_detail');
$count = $this->Mdl_store_book_detail->count_where($column, $value);
return $count;
}

function get_max() {
$this->load->model('Mdl_store_book_detail');
$max_id = $this->Mdl_store_book_detail->get_max();
return $max_id;
}

function _custom_query($mysql_query) {
$this->load->model('Mdl_store_book_detail');
$query = $this->Mdl_store_book_detail->_custom_query($mysql_query);
return $query;
}

}