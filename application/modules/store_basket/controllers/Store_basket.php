<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Store_basket extends MX_Controller
{

function __construct() {
parent::__construct();
}

function _avoid_cart_conflicts($data){
    //Note: this makes sure not items on storeshoppertrack with same session id and shopper id

    // if there are items on shoppertrack with same session id and shopper id
    //then regenerate the session id for this user update the $data['session_id] variable
    $original_session_id = $data['session_id'];
    $shopper_id = $data['shopper_id'];
    $this->load->module('store_shoppertrack');
    $col1 = 'session_id';
    $value1 = $original_session_id;
    $col2 = 'shopper_id';
    $value2 = $shopper_id;
    $query = $this->store_shoppertrack->get_with_double_condition($col1,$value1,$col2,$value2);
    $num_rows = $query->num_rows();

    if($num_rows > 0){//items conflictin with store_shoppertrack
        // regerate session id
        session_regenerate_id();
        $new_session_id = $this->session->session_id;
        $data['session_id'] = $new_session_id;
    }

    return $data;
}

function remove(){
    $update_id = $this->uri->segment(3);
    $allowed = $this->_make_sure_remove_allowed($update_id);
    if($allowed == FALSE){
        return('cart');
    }
    $this->_delete($update_id);
    $refer_url = $_SERVER['HTTP_REFERER'];
    redirect($refer_url);
}

function _make_sure_remove_allowed($update_id){
    $query = $this->get_where($update_id);
    foreach($query->result() as $row){
        $session_id = $row->session_id;
        $shopper_id = $row->shopper_id;
    }

    if(!isset($shopper_id)){
        return FALSE;
    }
    $customer_session_id = $this->session->session_id;
    // $ip_address = $this->input->ip_address();
    $this->load->module('site_security');
    $customer_shopper_id = $this->site_security->_get_user_id();
    
    if(($session_id == $customer_session_id) OR ($shopper_id == $customer_shopper_id)){
        return TRUE;
    }else {
        return FALSE;
    }

}

function add_to_basket(){
    $this->load->library('session');
    $submit = $this->input->post('submit');
    if($submit == "Submit"){
        $this->load->library('form_validation');
        $this->form_validation->set_rules('item_color', 'Item Color', 'numeric');
        $this->form_validation->set_rules('item_size', 'Item Size', 'numeric');
        // $this->form_validation->set_rules('item_qty', 'Item Quantity', 'required|numeric');
        $this->form_validation->set_rules('item_id', 'Item ID', 'required|numeric');
        if($this->form_validation->run() == true){
            $data = $this->_fetch_data();
            $data = $this->_avoid_cart_conflicts($data);
            //  print_r($data);die();
            $this->_insert($data);
            $refer_url = $_SERVER['HTTP_REFERER'];
            redirect($refer_url);
        }else{
            $refer_url = $_SERVER['HTTP_REFERER'];
            $error_msg = validation_errors("<p style='color: red;'>","</p>");
            
            $this->session->set_flashdata('item', $error_msg);
            redirect($refer_url);
        }
    }elseif($submit == 'Buy_book'){
        $data = $this->_fetch_data();
        $data = $this->_avoid_cart_conflicts($data);
        
        // print_r($data);die();
        $this->_insert($data);
        redirect('cart');
        
    }
   
}




function _get_value($value_type,$update_id){
    //Note: value_type can be color or size
    $data = array();
    // if($value_type = 'size'){
    //     $this->load->module('store_item_sizes');
    //     // $query = $this->store_item_sizes->get_where($update_id);
    //     $num_rows = $query->num_rows();
    //     if($num_rows > 0){
            
    //         foreach($query->row() as $row){
    //             $item_size = $row->size;
    //             $data = $row->size;
    //         }
    //     }
    //     if(!isset($item_size)){
    //         $item_size = '';
    //     }
    //     $value = $item_size;
    // }else{
    //     $this->load->module('store_item_colors');
    //     $query = $this->store_item_colors->get_where($update_id);
    //     $num_rows = $query->num_rows();
    //     if($num_rows > 0){

    //         foreach($query->row() as $row){
    //             $item_color = $row->color;
    //             $data = $row->color;
    //         }
    //     }
    //     if(!isset($item_color)){
    //         $item_color = '';
    //     }
    //     $value = $item_color;
    // }
    
    return $value;
}

function _fetch_data(){
    $this->load->module('site_security');
    $this->load->module('store_books');
    $this->load->module('store_book_categories');
    $this->load->module('book_cat_assign');

    $item_id = $this->input->post('item_id',TRUE);
    $item_size = $this->input->post('item_size',TRUE);
    $item_color = $this->input->post('item_color',TRUE);
    // $item_qty = $this->input->post('item_qty',TRUE);
    $item_qty = 1;
    $item_data = $this->store_books->fetch_data_from_db($item_id);
    $item_price = $item_data['book_price'];
    $shopper_id = $this->site_security->_get_user_id();
    if(!is_numeric($shopper_id)){
        $shopper_id = 0;
        
    }
    $data['session_id'] = $this->session->session_id; 
    $data['item_title'] = $item_data['book_title']; 
    $data['item_qty'] = $item_qty; 
    $data['item_price'] = $item_price; 
    $data['format'] = $item_data['book_format'];
    $data['book'] = $item_data['book'];
    $data['tax'] = 0;
    $data['shipping'] = 0;
    $data['item_id'] = $item_id; 
    // $data['item_color'] = $this->_get_value('color',14); 
    // $data['item_size'] = $this->_get_value('size',14); 
    $data['date_added'] = time(); 
    $data['shopper_id'] = $shopper_id; 
    $data['ip_address'] = $this->input->ip_address();
    
    return $data;
}



function test(){
    $session_id = $this->session->session_id;
    $ip_address = $this->input->ip_address();
    echo $ip_address;
 
}

function get_with_double_condition($col1, $value1,$col2,$value2) {
    $this->load->model('Mdl_store_basket');
    $query = $this->Mdl_store_basket->get_with_double_condition($col1, $value1,$col2,$value2);
    return $query;
}

function get($order_by) {
$this->load->model('Mdl_store_basket');
$query = $this->Mdl_store_basket->get($order_by);
return $query;
}

function get_with_limit($limit, $offset, $order_by) {
$this->load->model('Mdl_store_basket');
$query = $this->Mdl_store_basket->get_with_limit($limit, $offset, $order_by);
return $query;
}

function get_where($id) {
$this->load->model('Mdl_store_basket');
$query = $this->Mdl_store_basket->get_where($id);
return $query;
}

function get_where_custom($col, $value) {
$this->load->model('Mdl_store_basket');
$query = $this->Mdl_store_basket->get_where_custom($col, $value);
return $query;
}

function _insert($data) {
$this->load->model('Mdl_store_basket');
$this->Mdl_store_basket->_insert($data);
}

function _update($id, $data) {
$this->load->model('Mdl_store_basket');
$this->Mdl_store_basket->_update($id, $data);
}

function _delete($id) {
$this->load->model('Mdl_store_basket');
$this->Mdl_store_basket->_delete($id);
}

function count_where($column, $value) {
$this->load->model('Mdl_store_basket');
$count = $this->Mdl_store_basket->count_where($column, $value);
return $count;
}

function get_max() {
$this->load->model('Mdl_store_basket');
$max_id = $this->Mdl_store_basket->get_max();
return $max_id;
}

function _custom_query($mysql_query) {
$this->load->model('Mdl_store_basket');
$query = $this->Mdl_store_basket->_custom_query($mysql_query);
return $query;
}

}