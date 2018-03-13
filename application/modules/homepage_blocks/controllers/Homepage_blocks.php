<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Homepage_blocks extends MX_Controller
{
    // Redo Paginations 
    function __construct() {
        parent::__construct();
    } 

    function _draw_cta_1(){
        $this->load->view('cta_1');
    }
    function _draw_cta_2(){
        $this->load->view('cta_2');
    }
    function _draw_cta_3(){
        $this->load->view('cta_3');
    }

    function _draw_blocks($is_mobile = FALSE){
        //draw offer blocks on homepage
        $data['query'] = $this->get('priority');
        $num_rows = $data['query']->num_rows();

        if($num_rows > 0){
            if($is_mobile == TRUE){
                $view_file = 'homepage_blocks_jqm';
            }else{
                $view_file = 'homepage_blocks';
            }
            $this->load->view($view_file,$data);
        }
    }

    function _process_delete($update_id){
        //delete any items associated with this offer block
        $this->load->module('homepage_offers');
        $mysql_query = "DELETE FROM homepage_offers WHERE block_id = $update_id";
        $this->_custom_query($mysql_query);
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
        redirect('homepage_blocks/create/'.$update_id);
    }else {
        $this->_process_delete($update_id);
        
        $flash_msg = "Blog Delete Successfull";
        $value = "<div class='alert alert-success' role='alert'>".$flash_msg."</div>";
        $data['flash'] = $this->session->flashdata('item',$value);
        redirect('homepage_blocks/manage');
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
    
    // $data['view_module'] = "blog";
    $data['view_file'] = "deleteconf";
    $this->load->module('templates');
    $this->templates->admin($data);
}

    function test(){
        $users['han'] = 24;
        $users['luke'] = 144;
        $users['yoda'] = 255;
        $users['zoda'] = 25;

        echo "<h1>who is oldest?</h1>";
        $older = $this->get_oldest_user($users);
        echo $older;
        
    }

    function get_oldest_user($target_array){
        foreach ($target_array as $key => $value) {
            if(!isset($highest_value)){
                $highest_value = $key;
            }elseif ($value > $target_array[$highest_value]) {
                $highest_value = $key;
            }
        }
        return $highest_value;
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

        //fetch the items for this page
        $use_limit = true;
        $mysql_query = $this->_generate_mysql_query($update_id,$use_limit);
     
        $data['pagination'] = $this->custom_pagination->_generate_pagination('public_bootstrap');
        $data['query'] = $this->_custom_query($mysql_query);
        $data['item_segment'] = $this->site_settings->_get_item_segments();
        // echo $data['item_segment'];die();
        $data['currency_symbol'] = $this->site_settings->_get_currency_symbol();
        //fetch items belonging to this cat
        $data['view_module'] = 'homepage_blocks';
        $data['headline'] = "Confirm Delete";
        $data['update_id'] = $update_id;
        $data['flash'] = $this->session->flashdata('item');
        $data['view_file'] = "view";
        // print_r(json_encode($data));die();
        $this->load->module('templates');
        $this->templates->public_bootstrap($data);
    }

    function get_target_pagination_target_url(){
        $first_bit = $this->uri->segment(1);
        $second_bit = $this->uri->segment(2);
        $third_bit = $this->uri->segment(3);
        $target_base_url = base_url().$first_bit.'/'.$second_bit.'/'.$third_bit;
        return $target_base_url;
    }

    function _generate_mysql_query($update_id,$use_limit){
        $mysql_query = "
            SELECT
            store_books.book_title,
            store_books.book_url,
            store_books.book_price,
            store_books.small_pic,
            store_books.was_price
            FROM
            store_books
            INNER JOIN book_cat_assign ON book_cat_assign.book_id = store_books.id
            WHERE
            book_cat_assign.cat_id = $update_id AND
            store_books.`status` = 1

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

   
    function sort(){
        $this->load->module('site_security');
        $this->site_security->_make_sure_is_admin();
         $number = $this->input->post('number',true);
        $test = $_POST;
        for($i = 1;$i <= $number; $i++){
            $update_id = $_POST['order'.$i];
            $data['priority'] = $i;
            $this->_update($update_id,$data);
        }
    }

  

  
    function _get_block_title($update_id){
        $data = $this->fetch_data_from_db($update_id); 
        return $block_title = $data['block_title'];
        
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
            $this->form_validation->set_rules('block_title', 'Block Title', 'required|max_length[240]');
            
            if($this->form_validation->run() == true){
                //get form variables
                // $data['book_cat_url'] = url_title($data['block_title']);
                if(is_numeric($update_id)){
                    //update the category detail
                    $data = $this->fetch_data_from_post();
                    $this->_update($update_id,$data);
                    $flash_msg = "Block Title updated successfully";
                    $value = '<div class="alert alert-success" role="alert">'.$flash_msg.'</div>';
                    $this->session->set_flashdata('item', $value);
                    redirect('homepage_blocks/create/'.$update_id);
                }else{
                    
                    $data = $this->fetch_data_from_post();
                    $this->_insert($data);
                    $flash_msg = "Block Title Inserted Successfully";
                    $value = '<div class="alert alert-success" role="alert">'.$flash_msg.'</div>';
                    $data['flash'] = $this->session->set_flashdata('item', $value);
                    redirect('homepage_blocks/create/'.$update_id);
                }
            }
        }elseif($submit == 'Cancel'){
            redirect('homepage_blocks/manage');
        }

        if(is_numeric($update_id)){
            $data = $this->fetch_data_from_db($update_id);
        }else{
            $data = $this->fetch_data_from_post();
            // print_r($data);
        }
         
        if(!is_numeric($update_id)){
            $data['headline'] = "Add New Homepage Block";
        }else{
            $data['headline'] = "Update Homepage Block Details";
        }

        $data['update_id'] = $update_id;
        $data['flash'] = $this->session->flashdata('item');
        $data['view_file'] = "create";
        $this->load->module('templates');
        $this->templates->admin($data);
    }
    function fetch_data_from_post(){
        $data['block_title'] = $this->input->post('block_title');
        // $data['book_parent_cat_id'] = $this->input->post('book_parent_cat_id');
        
        return $data;
    }

     function _draw_sortable_list(){
        // $data['query'] = $this->get_where_custom('book_parent_cat_id',$parent_cat_id);
        $mysql_query = "SELECT * FROM homepage_blocks ORDER BY priority";
        $data['query'] = $this->_custom_query($mysql_query);
        $this->load->view('sortable_list',$data);
    }

    function manage(){
        $this->load->module('site_security');
        $this->site_security->_make_sure_is_admin();
        
        

        // $data['query'] = $this->get_where_custom('',$);
        $data['query'] = $this->get('priority');

        $data['sort_this'] = true;
        $data['headline'] = "Manage Homepage Offers";
        $data['flash'] = $this->session->set_flashdata('item');
        $data['view_file'] = "manage";
        $this->load->module('templates');
        $this->templates->admin($data);
    }

   

    function fetch_data_from_db($update_id){
        if(!is_numeric($update_id)){
                redirect('site_security/not_allowed');
            }
        $query = $this->get_where($update_id);
        foreach ($query->result() as $row) {
            $data['block_title'] = $row->block_title;
            // $data['book_cat_url'] = $row->book_cat_url;
            // $data['book_parent_cat_id'] = $row->book_parent_cat_id;
        }

        if(!isset($data)){
            $data="";
        }
        return $data;
    }

    function get($order_by) {
        $this->load->model('Mdl_homepage_blocks');
        $query = $this->Mdl_homepage_blocks->get($order_by);
        return $query;
    }

    function get_with_limit($limit, $offset, $order_by) {
        $this->load->model('Mdl_homepage_blocks');
        $query = $this->Mdl_homepage_blocks->get_with_limit($limit, $offset, $order_by);
        return $query;
    }

    function get_where($id) {
        $this->load->model('Mdl_homepage_blocks');
        $query = $this->Mdl_homepage_blocks->get_where($id);
        return $query;
    }

    function get_where_custom($col, $value) {
        $this->load->model('Mdl_homepage_blocks');
        $query = $this->Mdl_homepage_blocks->get_where_custom($col, $value);
        return $query;
    }

    function _insert($data) {
        $this->load->model('Mdl_homepage_blocks');
        $this->Mdl_homepage_blocks->_insert($data);
    }

    function _update($id, $data) {
        $this->load->model('Mdl_homepage_blocks');
        $this->Mdl_homepage_blocks->_update($id, $data);
    }

    function _delete($id) {
        $this->load->model('Mdl_homepage_blocks');
        $this->Mdl_homepage_blocks->_delete($id);
    }

    function count_where($column, $value) {
        $this->load->model('Mdl_homepage_blocks');
        $count = $this->Mdl_homepage_blocks->count_where($column, $value);
        return $count;
    }

    function get_max() {
        $this->load->model('Mdl_homepage_blocks');
        $max_id = $this->Mdl_homepage_blocks->get_max();
        return $max_id;
    }

    function _custom_query($mysql_query) {
        $this->load->model('Mdl_homepage_blocks');
        $query = $this->Mdl_homepage_blocks->_custom_query($mysql_query);
        return $query;
    }

}