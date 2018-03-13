<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Store_items extends MX_Controller
{
    
    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->form_validation->CI =& $this;
        // $this->load->model('Mdl_store_items');
    }

    function _get_item_id_from_item_url($item_url){
        $query = $this->get_where_custom('item_url',$item_url);
        foreach ($query->result() as $row) {
            $item_id = $row->id;
        }

        if(!isset($item_id)){
            $item_id = 0;
        }
        return $item_id;
    }
   
    function view($update_id){
        if(!is_numeric($update_id)){
            redirect('site_security/not_allowed');
        }

        $this->load->module('site_settings');
        //fetch the item details 
        $data = $this->fetch_data_from_db($update_id);

        $item_price =number_format($data['book_price'],2);
        $data['price'] = str_replace('.00','',$item_price);
        $data['currency_symbol'] = $this->site_settings->_get_currency_symbol();
        $data['view_module'] = 'store_items';
        $data['headline'] = "Confirm Delete";
        $data['update_id'] = $update_id;
        $data['flash'] = $this->session->flashdata('item');
        $data['view_file'] = "view";
        $this->load->module('templates');
        $this->templates->public_bootstrap($data);
    }

    function _generate_thumbnail($filename){
        $config['image_library'] = 'gd2';
        $config['source_image'] = './public/images/big_pics/'.$filename;
        $config['new_image'] = './public/images/small_pics/'.$filename;
        $config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = TRUE;
        $config['width']         = 200;
        $config['height']       = 250;

        $this->load->library('image_lib', $config);

        $this->image_lib->resize();
    }

    function _process_delete($update_id){
        //attempt to delete item colours
        $this->load->module('store_item_colors');
        $this->store_item_colors->_delete_for_item($update_id);
    
        //attempt to delete item sizes
        $this->load->module('store_item_sizes');
        $this->store_item_colors->_delete_for_item($update_id);
        
        //fetch img
        $data = $this->fetch_data_from_db($update_id);
        $big_pic = $data['big_pic'];
        $small_pic = $data['small_pic'];

        //get img location
        $big_pic_path = './public/images/big_pics/'.$big_pic;
        $small_pic_path = './public/images/small_pics/'.$small_pic;
        
        //attempt to remove img
        if(file_exists($big_pic_path)){
            unlink($big_pic_path);
        }
        if(file_exists($small_pic_path)){
            unlink($small_pic_path);
        }
    
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
            redirect('store_items/create/'.$update_id);
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

    function delete_image($update_id){
         if(!is_numeric($update_id)){
            redirect('site_security/not_allowed');
        }
        
        $this->load->library('session');
        $this->load->module('site_security');
        $this->site_security->_make_sure_is_admin();
        $data = $this->fetch_data_from_db($update_id);
        $big_pic = $data['big_pic'];
        $small_pic = $data['small_pic'];

        $big_pic_path = './public/images/big_pics/'.$big_pic;
        $small_pic_path = './public/images/small_pics/'.$small_pic;
        
        //attempt to remove img
        if(file_exists($big_pic_path)){
            unlink($big_pic_path);
        }
        if(file_exists($small_pic_path)){
            unlink($small_pic_path);
        }

        //updating the db
        unset($data);
        $data['big_pic']= "";
        $data['small_pic']= "";
        $this->_update($update_id,$data);

        $flash_msg = "Item Image delete successful";
        $value = '<div class="alert alert-success" role="alert">'.$flash_msg.'</div>';
        $this->session->set_flashdata('item', $value);
        redirect('store_items/create/'.$update_id);
    }
    
    function do_upload($update_id){
        if(!is_numeric($update_id)){
            redirect('site_security/not_allowed');
        }
        $this->load->library('session');
        $this->load->module('site_security');
        $this->site_security->_make_sure_is_admin();

        $submit = $this->input->post('submit');
        if($submit == "Cancel"){
            redirect('store_items/create/'.$update_id); 
        }
        
        $config['upload_path']          = './public/images/big_pics/';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 100;
        $config['max_width']            = 1024;
        $config['max_height']           = 768;
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('userfile')){
            $data['error'] = array('error' => $this->upload->display_errors("<p style='color: red;'>","</p>"));
            $data['headline'] = "Upload Error!!!";
            $data['update_id'] = $update_id;
            $data['flash'] = $this->session->flashdata('item');
            $this->load->module('templates');
            $data['view_file'] = "upload_image";
            $this->templates->admin($data);
        }
        else{
            // echo 'ok';
            $data = array('upload_data' => $this->upload->data());
            
            $upload_data = $data['upload_data'];
            $file_name = $upload_data['file_name'];

            $this->_generate_thumbnail($file_name);
            //updating database
            $update_data['big_pic'] = $file_name;
            $update_data['small_pic'] = $file_name;
            $this->_update($update_id,$update_data);

            $data['headline'] = "Upload Success!!!";
            $data['update_id'] = $update_id;
            $data['flash'] = $this->session->flashdata('item');
            $this->load->module('templates');
            $data['view_file'] = "upload_success";
            $this->templates->admin($data);
            // $this->load->view('upload_success', $data);
        }
    }

    function upload_image($update_id){
        if(!is_numeric($update_id)){
            redirect('site_security/not_allowed');
        }

        $this->load->library('session'); 
        $this->load->module('site_security');
        $this->site_security->_make_sure_is_admin();
        
        $submit = $this->input->post('submit',true);
    
    


        $data['update_id'] = $update_id;
        $data['headline'] = "Upload Image";
        $data['flash'] = $this->session->flashdata('item');
        // $data['view_module'] = "store_items";
        $data['view_file'] = "upload_image";
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
            $this->form_validation->set_rules('item_title', 'Item Title', 'required|max_length[240]|callback_item_check');
            $this->form_validation->set_rules('item_price', 'Item Price', 'required|numeric');
            $this->form_validation->set_rules('was_price', 'Was Price', 'numeric');
            $this->form_validation->set_rules('status', 'Status', 'required|numeric');
            $this->form_validation->set_rules('item_description', 'Item Description', 'required');
            
            if($this->form_validation->run() == true){
                //get form variables
                $data = $this->fetch_data_from_post();
                $data['item_url'] = url_title($data['item_title']);
                if(is_numeric($update_id)){
                    //update the item detail
                    $this->_update($update_id,$data);
                    $flash_msg = "Item updated successfully";
                    $value = '<div class="alert alert-success" role="alert">'.$flash_msg.'</div>';
                    $this->session->set_flashdata('item', $value);
                    redirect('store_items/create/'.$update_id);
                }else{
                    // inser new item
                    $this->_insert($data);
                    $update_id = $this->get_max();//gets the ID of new item
                    $flash_msg = "Item Inserted Successfully";
                    $value = '<div class="alert alert-success" role="alert">'.$flash_msg.'</div>';
                    $data['flash'] = $this->session->set_flashdata('item', $value);
                    redirect('store_items/create/'.$update_id);
                }
            }
        }elseif($submit == 'Cancel'){
            redirect('store_items/manage');
        }
        if((is_numeric($update_id)) && ($submit = 'Submit')){
            $data = $this->fetch_data_from_db($update_id);
        }else{
            $data = $this->fetch_data_from_post();
            $data['big_pic'] = '';
            // print_r($data);
        }
        
        if(!is_numeric($update_id)){
            $data['headline'] = "Add New Item";
        }else{
            $data['headline'] = "Update Item Details";
        }

        $data['got_gallery_pic'] = $this->_got_gallery_pic($update_id);
        $data['update_id'] = $update_id;
        $data['flash'] = $this->session->flashdata('item');
        // $data['view_module'] = "store_items";
        $data['view_file'] = "create";
        $this->load->module('templates');
        $this->templates->admin($data);
    }

    function _got_gallery_pic($update_id){
        $this->load->module('item_galleries');
        $query = $this->item_galleries->get_where_custom('parent_id',$update_id);
        $num_rows = $query->num_rows();
        if($num_rows > 0){
            return true; // we have atleast on picture
        }else{
            return false;
        }
    }

    function manage(){
        $this->load->module('site_security');
        $this->site_security->_make_sure_is_admin();
        
        $data['query'] = $this->get('item_title');

        //$data['view_module'] = "store_items";
        $data['headline'] = "Manage Item";
        $data['flash'] = $this->session->set_flashdata('item');
        $data['view_file'] = "manage";
        $this->load->module('templates');
        $this->templates->admin($data);
    }

    function fetch_data_from_post(){
        $data['item_title'] = $this->input->post('item_title');
        $data['item_price'] = $this->input->post('item_price');
        $data['was_price'] = $this->input->post('was_price');
        $data['item_description'] = $this->input->post('item_description');
        $data['status'] = $this->input->post('status');
        return $data;
    }

    function fetch_data_from_db($update_id){
        if(!is_numeric($update_id)){
                redirect('site_security/not_allowed');
            }
        $query = $this->get_where($update_id);
        foreach ($query->result() as $row) {
            $data['item_title'] = $row->item_title;
            $data['item_url'] = $row->item_url;
            $data['item_price'] = $row->item_price;
            $data['item_description'] = $row->item_description;
            $data['big_pic'] = $row->big_pic;
            $data['small_pic'] = $row->small_pic;
            $data['was_price'] = $row->was_price;
            $data['status'] = $row->status;
        }

        if(!isset($data)){
            $data="";
        }
        return $data;
    }

    function get($order_by) {
        $this->load->model('Mdl_store_items');
        $query = $this->Mdl_store_items->get($order_by);
        return $query;
    }

    function get_with_limit($limit, $offset, $order_by) {
        $this->load->model('Mdl_store_items');
        $query = $this->Mdl_store_items->get_with_limit($limit, $offset, $order_by);
        return $query;
    }

    function get_where($id) {
        $this->load->model('Mdl_store_items');
        $query = $this->Mdl_store_items->get_where($id);
        return $query;
    }

    function get_where_custom($col, $value) {
        $this->load->model('Mdl_store_items');
        $query = $this->Mdl_store_items->get_where_custom($col, $value);
        return $query;
    }

    function _insert($data) {
        $this->load->model('Mdl_store_items');
        $this->Mdl_store_items->_insert($data);
    }

    function _update($id, $data) {
        $this->load->model('Mdl_store_items');
        $this->Mdl_store_items->_update($id, $data);
    }

    function _delete($id) {
        $this->load->model('Mdl_store_items');
        $this->Mdl_store_items->_delete($id);
    }

    function count_where($column, $value) {
        $this->load->model('Mdl_store_items');
        $count = $this->Mdl_store_items->count_where($column, $value);
        return $count;
    }

    function get_max() {
        $this->load->model('Mdl_store_items');
        $max_id = $this->Mdl_store_items->get_max();
        return $max_id;
    }

    function _custom_query($mysql_query) {
        $this->load->model('Mdl_store_items');
        $query = $this->Mdl_store_items->_custom_query($mysql_query);
        return $query;
    }
    
    function item_check($str){
        $item_url = url_title($str);
        $mysql_query = "SELECT * FROM store_items WHERE item_title='$str' AND item_url='$item_url'";
        $update_id = $this->uri->segment(3);
        if(is_numeric($update_id)){
            //this is an update
            $mysql_query .= " AND id !=$update_id";
        }

        $query = $this->_custom_query($mysql_query);
        $num_rows = $query->num_rows();

        if($num_rows > 0){
            $this->form_validation->set_message('item_check','The Item you submitted is not available');
            return FALSE;
        }else {
            return TRUE;
        }
    }
} 