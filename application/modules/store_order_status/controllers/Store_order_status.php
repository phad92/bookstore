<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Store_order_status extends MX_Controller
{
    
    function __construct() {
        parent::__construct();
    }

    

    function _get_dropdown_options(){
        $options['0'] = "Order Submitted";
         $query = $this->get('status_title');
        foreach($query->result() as $row){
            $options[$row->id] = $row->status_title;
        }

        return $options;
    }

    function _get_status_title($update_id){
        $query = $this->get_where($update_id);
        foreach($query->result() as $row){
            $status_title = $row->status_title;
        }

        if(!isset($status_title)){
            $status_title = "Unknown";
        }

        return $status_title;
    }

    function _draw_left_nav_links(){
        $data['sqlquery'] = $this->get('status_title');
        $data['num_rows'] = $data['sqlquery']->num_rows();
        $this->load->view('left_nav_links',$data);
    }

    function _make_sure_delete_allowed($update_id){
        //returns TRUE OR FALSE

        //DO NOT ALLOWED DELETE IF order has this status
        $mysql_query = "SELECT * FROM store_orders WHERE order_status = $update_id";
        $query = $this->_custom_query($mysql_query);
        $num_rows = $query->num_rows();

        if($num_rows > 0){
            return FALSE; //delete not allowed since has items in basket
        }else{
            return TRUE;
        }
    
    }
    

    function deleteconf($update_id){
        if(!is_numeric($update_id)){
            redirect('site_security/not_allowed');
        }

        $this->load->library('session'); 
        $this->load->module('site_security');
        $this->site_security->_make_sure_is_admin();
        $submit = $this->input->post('submit');
        if($submit == 'Cancel'){
            redirect('store_order_status/create/'.$update_id);
        }
        $data['headline'] = "Confirm Delete";
        $data['update_id'] = $update_id;
        

        // $data['view_module'] = "blog";
        $data['view_file'] = "deleteconf";
        $this->load->module('templates');
        $this->templates->admin($data);
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
            redirect('store_order_status/create/'.$update_id);
        }else {
            $allowed = $this->_make_sure_delete_allowed($update_id);
            if($allowed == FALSE){
                $flash_msg = "You are not allowed to Delete this Status Option";
                $value = "<div class='alert alert-success' role='alert'>".$flash_msg."</div>";
                $data['flash'] = $this->session->flashdata('item',$value);
                redirect('store_accounts/manage');
            }
            $this->_delete($update_id);
            
            $flash_msg = "Store Account Entry Delete Successfull";
            $value = "<div class='alert alert-success' role='alert'>".$flash_msg."</div>";
            $data['flash'] = $this->session->flashdata('item',$value);
            redirect('store_order_status/manage');
        }
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
            $this->form_validation->set_rules('status_title', 'Status Title', 'required|max_length[240]');
            
            if($this->form_validation->run() == true){
                //get form variables
                $data = $this->fetch_data_from_post();
                if(is_numeric($update_id)){
                    //update the item detail
                    $this->_update($update_id,$data);
                    $flash_msg = "Order Status Options updated successfully";
                    $value = '<div class="alert alert-success" role="alert">'.$flash_msg.'</div>';
                    $this->session->set_flashdata('item', $value);
                    redirect('store_order_status/create/'.$update_id);
                }else{
                    // inser new item
                    
                    $this->_insert($data);
                    $update_id = $this->get_max();//gets the ID of new item
                    $flash_msg = "Order Status Options Inserted Successfully";
                    $value = '<div class="alert alert-success" role="alert">'.$flash_msg.'</div>';
                    $data['flash'] = $this->session->set_flashdata('item', $value);
                    redirect('store_order_status/create/'.$update_id);
                }
            }
        }elseif($submit == 'Cancel'){
            redirect('store_order_status/manage');
        }
        if((is_numeric($update_id)) && ($submit = 'Submit')){
            $data = $this->fetch_data_from_db($update_id);
        }else{
            $data = $this->fetch_data_from_post();
            $data['big_pic'] = '';
            // print_r($data);
        }
        
        if(!is_numeric($update_id)){
            $data['headline'] = "Add New Order Status Options";
        }else{
            $data['headline'] = "Update Order Status Options";
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
        
        $data['query'] = $this->get('status_title');
        
        //$data['view_module'] = "store_items";
        $data['headline'] = "Manage Order Status Options";
        $data['flash'] = $this->session->set_flashdata('item');
        $data['view_file'] = "manage";
        $this->load->module('templates');
        $this->templates->admin($data);
    }


    function fetch_data_from_post(){
        $data['status_title'] = $this->input->post('status_title');
     
        return $data;
    }

    function autogen(){
        $mysql_query = "SHOW columns FROM store_order_status";
        $query = $this->_custom_query($mysql_query);
        foreach($query->result() as $row){
            $column = $row->Field;
            if($column != "id"){
                $html = '<div class="control-group">
                  <label class="control-label" for="'.$column.'">'.ucfirst($column).'</label>
                  <div class="controls">
                    <input type="text" class="span6" id="'.$column.'" name="'.$column.'" value="<?php echo $'.$column.'?>">
                  </div>
                </div>';
                echo htmlentities($html);
                echo '</br>';
            }
        }
    }

    function fetch_data_from_db($update_id){
        if(!is_numeric($update_id)){
                redirect('site_security/not_allowed');
            }
        $query = $this->get_where($update_id);
        foreach ($query->result() as $row) {
            $data["status_title"] = $row->status_title;
           
        }

        if(!isset($data)){
            $data="";
        }
        return $data;
    }

    function get($order_by) {
        $this->load->model('Mdl_store_order_status');
        $query = $this->Mdl_store_order_status->get($order_by);
        return $query;
    }

    function get_with_limit($limit, $offset, $order_by) {
        $this->load->model('Mdl_store_order_status');
        $query = $this->Mdl_store_order_status->get_with_limit($limit, $offset, $order_by);
        return $query;
    }

    function get_where($id) {
        $this->load->model('Mdl_store_order_status');
        $query = $this->Mdl_store_order_status->get_where($id);
        return $query;
    }

    function get_where_custom($col, $value) {
        $this->load->model('Mdl_store_order_status');
        $query = $this->Mdl_store_order_status->get_where_custom($col, $value);
        return $query;
    }

    function _insert($data) {
        $this->load->model('Mdl_store_order_status');
        $this->Mdl_store_order_status->_insert($data);
    }

    function _update($id, $data) {
        $this->load->model('Mdl_store_order_status');
        $this->Mdl_store_order_status->_update($id, $data);
    }

    function _delete($id) {
    $this->load->model('Mdl_store_order_status');
    $this->Mdl_store_order_status->_delete($id);
    }

    function count_where($column, $value) {
    $this->load->model('Mdl_store_order_status');
    $count = $this->Mdl_store_order_status->count_where($column, $value);
    return $count;
    }

    function get_max() {
    $this->load->model('Mdl_store_order_status');
    $max_id = $this->Mdl_store_order_status->get_max();
    return $max_id;
    }

    function _custom_query($mysql_query) {
    $this->load->model('Mdl_store_order_status');
    $query = $this->Mdl_store_order_status->_custom_query($mysql_query);
    return $query;
    }

}