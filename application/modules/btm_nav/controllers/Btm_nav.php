<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Btm_nav extends MX_Controller
{
    // Redo Paginations 
    function __construct() {
        parent::__construct();
    }

    function _draw_btm_nav(){
        $mysql_query =  "SELECT btm_nav.id,
                               btm_nav.page_id,
                               btm_nav.priority,
                               webpages.page_url,
                               webpages.page_title
                        FROM btm_nav 
                        INNER JOIN webpages ON btm_nav.page_id = webpages.id 
                        ORDER BY btm_nav.priority";
        $data['query'] = $this->_custom_query($mysql_query);
        $this->load->view('btm_nav',$data);
    }

    function get_special_pages(){
        $special_pages[] = 5;
        $special_pages[] = 6;
        return $special_pages;
    }

    function delete($update_id){
        $this->load->module('site_security');
        $this->site_security->_make_sure_is_admin();
        if(!is_numeric($update_id)){
            die();
        }

        $this->_delete($update_id);
        redirect('btm_nav/manage');
    }

    function submit_create(){
        $this->load->module('site_security');
        $this->site_security->_make_sure_is_admin();

        $submit = $this->input->post('submit',TRUE);
        $page_id = $this->input->post('page_id',TRUE);

        if($submit == 'Cancel'){
            redirect('btm_nav/manage');
        }elseif($submit == "Submit"){
            $data['page_id'] = $page_id;
            $data['priority'] = 0;
            $this->_insert($data);
            redirect('btm_nav/manage');
        }

    }

    function _get_dropdown_options($selected_options){
        $this->load->module('webpages');
        $query = $this->webpages->get('page_url');
        foreach($query->result() as $row){
            if($row->page_url == ''){
                $row->page_url = 'Home';
            }
            if(!in_array($row->id, $selected_options)){
                $options[$row->id] = $row->page_url;
            }
        }

        
       
        if(!isset($options)){
            $options = '';
        }

        return $options;
    }

    function _draw_create_modal(){
        //modal for creating a new record
        $query = $this->get('priority');
        foreach($query->result() as $row){
            $selected_options[$row->page_id] = $row->page_id;
        }
        $data['options'] = $this->_get_dropdown_options($selected_options);
        $data['form_location'] = base_url('btm_nav/submit_create');
        $this->load->view('create_modal',$data);
    }

    function _draw_blocks(){
        //draw offer blocks on homepage
        $data['query'] = $this->get('priority');
        $num_rows = $data['query']->num_rows();
        
        if($num_rows > 0){
            $this->load->view('btm_nav',$data);
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
        $data['view_module'] = 'btm_nav';
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
                    redirect('btm_nav/create/'.$update_id);
                }else{
                    
                    $data = $this->fetch_data_from_post();
                    $this->_insert($data);
                    $flash_msg = "Block Title Inserted Successfully";
                    $value = '<div class="alert alert-success" role="alert">'.$flash_msg.'</div>';
                    $data['flash'] = $this->session->set_flashdata('item', $value);
                    redirect('btm_nav/create/'.$update_id);
                }
            }
        }elseif($submit == 'Cancel'){
            redirect('btm_nav/manage');
        }

        if(is_numeric($update_id)){
            $data = $this->fetch_data_from_db($update_id);
        }else{
            $data = $this->fetch_data_from_post();
            // print_r($data);
        }
         
        if(!is_numeric($update_id)){
            $data['headline'] = "Create New Bottom Navigation Link";
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
        $mysql_query = "SELECT btm_nav.id,
                               btm_nav.page_id,
                               btm_nav.priority,
                               webpages.page_url,
                               webpages.page_title
                        FROM btm_nav 
                        INNER JOIN webpages ON btm_nav.page_id = webpages.id 
                        ORDER BY btm_nav.priority";
        $data['query'] = $this->_custom_query($mysql_query);
        $data['special_pages'] = $this->get_special_pages();
        $this->load->view('sortable_list',$data);
    }

    function manage(){
        $this->load->module('site_security');
        $this->site_security->_make_sure_is_admin();
        
        

        // $data['query'] = $this->get_where_custom('',$);
        $data['query'] = $this->get('priority');

        $data['sort_this'] = true;
        $data['headline'] = "Manage Bottom Navigation";
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
        $this->load->model('Mdl_btm_nav');
        $query = $this->Mdl_btm_nav->get($order_by);
        return $query;
    }

    function get_with_limit($limit, $offset, $order_by) {
        $this->load->model('Mdl_btm_nav');
        $query = $this->Mdl_btm_nav->get_with_limit($limit, $offset, $order_by);
        return $query;
    }

    function get_where($id) {
        $this->load->model('Mdl_btm_nav');
        $query = $this->Mdl_btm_nav->get_where($id);
        return $query;
    }

    function get_where_custom($col, $value) {
        $this->load->model('Mdl_btm_nav');
        $query = $this->Mdl_btm_nav->get_where_custom($col, $value);
        return $query;
    }

    function _insert($data) {
        $this->load->model('Mdl_btm_nav');
        $this->Mdl_btm_nav->_insert($data);
    }

    function _update($id, $data) {
        $this->load->model('Mdl_btm_nav');
        $this->Mdl_btm_nav->_update($id, $data);
    }

    function _delete($id) {
        $this->load->model('Mdl_btm_nav');
        $this->Mdl_btm_nav->_delete($id);
    }

    function count_where($column, $value) {
        $this->load->model('Mdl_btm_nav');
        $count = $this->Mdl_btm_nav->count_where($column, $value);
        return $count;
    }

    function get_max() {
        $this->load->model('Mdl_btm_nav');
        $max_id = $this->Mdl_btm_nav->get_max();
        return $max_id;
    }

    function _custom_query($mysql_query) {
        $this->load->model('Mdl_btm_nav');
        $query = $this->Mdl_btm_nav->_custom_query($mysql_query);
        return $query;
    }

}