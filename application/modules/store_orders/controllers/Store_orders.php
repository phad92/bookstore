<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Store_orders extends MX_Controller
{

function __construct() {
parent::__construct();
}

function _send_auto_notify($update_id){
    //Note: notifies customer when an order status is updated
    $this->load->module('Enquiries');
    $this->load->module('site_security');
    $this->load->module('store_order_status');
    $query = $this->get_where($update_id);
    foreach($query->result() as $row){
        $order_status = $row->order_status;
        $order_ref = $row->order_ref;
        $shopper_id = $row->shopper_id;
    }

    //get order status title
    $status_title = $this->store_order_status->_get_status_title($order_status);
    $msg = "Order $order_ref has just been updated.";
    $msg .= "The new status for your order is $status_title";
    //send message
    $data['subject'] = "Order Status Update";
    $data['message'] = $msg;
    $data['sent_to'] = $shopper_id;

    $data['date_created'] = time();
    $data['sent_by'] = 0;
    $data['opened'] = 0;
    $data['code'] = $this->site_security->generate_random_string(6);
    $this->enquiries->_insert($data);
}

function _set_to_opened($update_id){
    $data['opened'] = 1;
    
    $this->_update($update_id,$data);
}

function _get_order_status_title(){
    //Note: this gets called by browse and figures out the sttus title
    $order_status = $this->uri->segment(3);
    $this->load->module('store_order_status');

    $order_status = str_replace('status','',$order_status);
    if(!is_numeric($order_status)){
        $order_status = 0;
    }
    if($order_status == 0){
       $status_title = "Order Submitted";
    }else{
        $status_title = $this->store_order_status->_get_status_title($order_status);
    } 

    return $status_title;
}

function submit_order_status(){
    $update_id = $this->uri->segment(3);
    $this->load->module('site_security');
    $this->site_security->_make_sure_is_admin();
    
    $submit = $this->input->post('submit');
    $order_status = $this->input->post('order_status');
    if($submit == "Cancel"){
        //get the currenct status for this order
        $query = $this->get_where($update_id);
        foreach($query->result() as $row){
            $order_status = $row->order_status;
        }

        $target_url = base_url("Store_orders/browse/status".$order_status);
        redirect($target_url);
    }elseif($submit == 'Submit'){
        $data['order_status'] = $order_status;
        $this->_update($update_id,$data);

        $this->_send_auto_notify($update_id);//notify the customer of this update
        $flash_msg = "Order Status updated successfully";
        $value = '<div class="alert alert-success" role="alert">'.$flash_msg.'</div>';
        $this->session->set_flashdata('item', $value);
        redirect('store_orders/view/'.$update_id);
    }
}

function view(){
        $this->load->library('session'); 
        $this->load->module('site_security');
        $this->load->module('store_accounts');
        $this->load->module('cart');
        $this->load->module('store_order_status');
        $this->load->module('timedate');

        $this->site_security->_make_sure_is_admin();
        
        $update_id = $this->uri->segment(3);
        $this->_set_to_opened($update_id);
       $query = $this->get_where($update_id);
       foreach($query->result() as $row){
           $data['order_ref'] = $row->order_ref;
           $date_created= $row->date_created;
           $data['paypal_id'] = $row->paypal_id;
           $session_id = $row->session_id;
           $data['opened'] = $row->opened;
           $order_status = $row->order_status;
           $data['shopper_id'] = $row->shopper_id;
           $data['amount_paid'] = $row->amount_paid;
       }
        
       $data['store_accounts_data'] = $this->store_accounts->fetch_data_from_db($data['shopper_id']);
       $data['date_created'] = $this->timedate->get_nice_date($date_created,'full');
       if($order_status == 0){
           $data['status_title'] = "Order Submitted";
        }else{
            $data['status_title'] = $this->store_order_status->_get_status_title($order_status);
        } 
        //fetch the content of the shopping cart
        $table = 'store_shoppertrack';
        $data['query_cc'] = $this->cart->_fetch_cart_content($session_id,$data['shopper_id'],$table);
        $data['order_status'] = $order_status;
        $data['options'] = $this->store_order_status->_get_dropdown_options();
        $data['customer_address'] = $this->store_accounts->_get_shopper_address($data['shopper_id'],'</br>');
        $data['headline'] = "Order ".$data['order_ref'];
        $data['update_id'] = $update_id;
        $data['flash'] = $this->session->flashdata('item');
        // $data['view_module'] = "store_items";
        $data['view_file'] = "view";
        $this->load->module('templates');
        $this->templates->admin($data);
    }

function browse(){
    $this->load->module('site_security');
    $this->site_security->_make_sure_is_admin();
    
    $data['query'] = $this->_custom_query($this->_generate_mysql_query());
    
    
    //$data['view_module'] = "store_items";
    $data['current_order_status_title'] = $this->_get_order_status_title();
    $data['headline'] = "Manage Item";
    $data['flash'] = $this->session->set_flashdata('item');
    $data['view_file'] = "browse";
    $this->load->module('templates');
    $this->templates->admin($data);
}

function get_customer_name($firstname,$lastname,$company){
    $firstname = trim(ucfirst($firstname));
    $lastname = trim(ucfirst($lastname));
    $company = trim(ucfirst($company));

    $company_length = strlen($company);
    if($company_length > 2){
        $customer_name = $company;
    }else {
        $customer_name = $firstname.' '.$lastname;
    }

    return $customer_name;
}


function _generate_mysql_query($use_limit = null){
    $order_status = $this->uri->segment(3);
    $order_status = str_replace('status','',$order_status);
    if(!is_numeric($order_status)){
        $order_status = 0;
    }
    if($order_status > 0){
        $mysql_query = "
            SELECT
            store_orders.id,
            store_orders.order_ref,
            store_orders.date_created,
            store_orders.opened,
            store_orders.amount_paid,
            store_accounts.firstname,
            store_accounts.lastname,
            store_accounts.company,
            store_order_status.status_title
            FROM
            store_orders
            INNER JOIN store_order_status ON store_orders.order_status = store_order_status.id
            INNER JOIN store_accounts ON store_orders.shopper_id = store_accounts.id
            WHERE
            store_orders.order_status = $order_status
            ORDER BY
            store_orders.date_created DESC
        ";
    }else{
        $mysql_query = "
            SELECT
            store_orders.id,
            store_orders.order_ref,
            store_orders.date_created,
            store_orders.opened,
            store_orders.amount_paid,
            store_accounts.firstname,
            store_accounts.lastname,
            store_accounts.company
            FROM
            store_orders
            INNER JOIN store_accounts ON store_orders.shopper_id = store_accounts.id
            WHERE
            store_orders.order_status = $order_status
            ORDER BY
            store_orders.date_created DESC
        ";
    }
    return $mysql_query;
}

function get_limit(){
    $limit = 20;
    return $limit;
}

function get_offset(){
    $offset = $this->uri->segment(4);
    if(!is_numeric($offset)){
        $offset = 0;
    }
    return $offset;
}

function get_target_pagination_base_url(){
    $first_bit = $this->uri->segment(1);
    $second_bit = $this->uri->segment(2);
    $third_bit = $this->uri->segment(3);
    $target_base_url = $base_url().$first_bit.'/'.$second_bit.'/'.$third_bit;
    return $target_base_url;
}

function get_where_custom($col, $value) {
$this->load->model('Mdl_store_orders');
$query = $this->Mdl_store_orders->get_where_custom($col, $value);
return $query;
}

function _get_amount_paid($paypal_id){
    //find the total amount taken from this order
    $this->load->module('paypal');
    $query = $this->paypal->get_where($paypal_id);
    foreach($query->result() as $row){
        $posted_info = $row->posted_info;
    }

    if(!isset($posted_info)){
        $amount_paid = 0;
    }else{
        $posted_info = unserialize($posted_info);
        $amount_paid = $posted_info['amount_paid'];
    }

    return $amount_paid;
}

function _get_shopper_id($customer_session_id){
    $this->load->module('store_basket');
    $query = $this->store_basket->get_where_custom('session_id',$customer_session_id);
    foreach($query->result() as $row){
        $shopper_id = $row->shopper_id;
    }

    if(!isset($shopper_id)){
        $shopper_id = 0;
    }

    return $shopper_id;
}

function _auto_generate_order($paypal_id,$customer_session_id){
    //this gets called from the paypal IPN listener, when an order is placed
    $this->load->module('site_security');
    $order_ref = $this->site_security->generate_random_string(6);
    $order_ref = strtoupper($order_ref);

    $data['order_ref'] = $order_ref;
    $data['date_created'] = time();
    $data['payment_method'] = $payment_method;
    $data['stripe_id'] = $stripe;
    $data['paypal_id'] = $paypal_id;
    $data['session_id'] = $customer_session_id;
    $data['quantity'] = $quantity;
    $data['opened'] = 0;
    $data['order_status'] = 0;
    $data['shopper_id'] = $this->_get_shopper_id($customer_session_id);
    $data['amount_paid'] = $this->_get_amount_paid($paypal_id);
    
    $this->_insert($data);
    //transfers from store_basket to store_shoppertrack
    $this->load->module('store_shoppertrack');
    $this->store_shoppertrack->_transfer_from_basket($customer_session_id);
}

function _generate_order($data){
    //this gets called from the paypal IPN listener, when an order is placed
    $this->load->module('site_security');
    $order_ref = $this->site_security->generate_random_string(6);
    $order_ref = strtoupper($order_ref);

    // $data['order_ref'] = $order_ref;
    // $data['date_created'] = time();
    // $data['payment_method'] = $payment_method;
    // $data['stripe_id'] = $stripe;
    // $data['paypal_id'] = $paypal_id;
    // $data['session_id'] = $customer_session_id;
    // $data['quantity'] = $quantity;
    // $data['opened'] = 0;
    // $data['order_status'] = 0;
    // $data['shopper_id'] = $this->_get_shopper_id($customer_session_id);
    // $data['amount_paid'] = $this->_get_amount_paid($paypal_id);
    
    $this->_insert($data);
    //transfers from store_basket to store_shoppertrack
    $this->load->module('store_shoppertrack');
    $this->store_shoppertrack->_transfer_from_basket($data['session_id']);
}


function get_with_double_condition($col1, $value1,$col2,$value2) {
$this->load->model('Mdl_store_orders');
$query = $this->Mdl_store_orders->get_where_custom($col1, $value1,$col2,$value2);
return $query;
}

function get($order_by) {
$this->load->model('Mdl_store_orders');
$query = $this->Mdl_store_orders->get($order_by);
return $query;
}

function get_with_limit($limit, $offset, $order_by) {
$this->load->model('Mdl_store_orders');
$query = $this->Mdl_store_orders->get_with_limit($limit, $offset, $order_by);
return $query;
}

function get_where($id) {
$this->load->model('Mdl_store_orders');
$query = $this->Mdl_store_orders->get_where($id);
return $query;
}

function _insert($data) {
$this->load->model('Mdl_store_orders');
$this->Mdl_store_orders->_insert($data);
}

function _update($id, $data) {
$this->load->model('Mdl_store_orders');
$this->Mdl_store_orders->_update($id, $data);
}

function _delete($id) {
$this->load->model('Mdl_store_orders');
$this->Mdl_store_orders->_delete($id);
}

function count_where($column, $value) {
$this->load->model('Mdl_store_orders');
$count = $this->Mdl_store_orders->count_where($column, $value);
return $count;
}

function get_max() {
$this->load->model('Mdl_store_orders');
$max_id = $this->Mdl_store_orders->get_max();
return $max_id;
}

function _custom_query($mysql_query) {
$this->load->model('Mdl_store_orders');
$query = $this->Mdl_store_orders->_custom_query($mysql_query);

return $query;
}

}