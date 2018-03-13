<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Sliders extends MX_Controller
{
    // Redo Paginations 
    function __construct() {
        parent::__construct();
    }

    function draw_best_seller_slider(){
        $this->load->module('store_book_categories');
        $this->load->module('store_cat_assign');
        $mysql_query = $this->store_book_categories->get('priority');
        print_r($query->result());die();
        $this->load->module('site_settings');
        $items_segments = $this->site_settings->_get_items_segments();
        $data['target_url_start'] = base_url().$items_segments;
        $data['parent_categories'] = $parent_categories;
        $this->load->view('top_nav',$data);
    }

    function _draw_latest_books_slider(){
        $this->load->module('store_books');
        $this->load->module('store_cat_assign');
        $query = $this->store_books->get('date_created desc');
        
        // print_r($query->result());die();
        $this->load->module('site_settings');
        $items_segments = $this->site_settings->_get_item_segments();
        $data['url_segment'] = base_url().$items_segments;
        $data['query'] = $query;
        $this->load->view('latest_books',$data);
    }

    function _draw_popular_books(){
        $this->load->module('store_books');
        $this->load->module('store_book_categories');
        $this->load->module('store_cat_assign');
        $mysql_query = "SELECT id FROM store_book_categories WHERE book_parent_cat_id != 0";
        $store_cats = $this->_custom_query($mysql_query);
        $num_rows = $store_cats->num_rows();
        if($num_rows > 0){
           foreach ($store_cats->result() as $row) {
               $cat_ids [] = $row->id;
           }
           $rand_cat_id = array_rand($cat_ids);
        //    print_r($cat_ids[$rand_cat_id]);die();
           
            
            $new_query = $this->_generate_mysql_query1($cat_ids[$rand_cat_id]);
            $query = $this->_custom_query($new_query);
            // print_r($store_books->result());die();
            // echo $random_cat_id;
            $this->load->module('site_settings');
            $items_segments = $this->site_settings->_get_item_segments();
            // $data['q_num_rows']=$num_rows;
            $data['url_segment'] = base_url().$items_segments;
            $data['query'] = $query;
            $this->load->view('popular_books',$data);
        }
        
    }

    function _draw_books_by_author_slider(){

    }

    function _draw_literature_slider(){

    }

    function _draw_random_cat_slider(){

    }

    function get_random($type,$str_ln){
        $this->load->helper('string');
        $random_val = random_string($type, $str_ln);
        return $random_val;
    }

    function _generate_mysql_query1($update_id){
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
        return $mysql_query;
    }


    function _attempt_draw_slider(){
        $current_url = current_url();

        $query_ads = $this->get_where_custom('target_url',$current_url);
        $num_rows_ads = $query_ads->num_rows();
        if($num_rows_ads > 0){
            //draw the slider on this page
            $this->load->module('slides');

            //get the parent_id
            foreach($query_ads->result() as $row){
                $parent_id = $row->id;
            }

            $data['query_slides'] = $this->slides->get_where_custom('parent_id',$parent_id);
            // print_r($data['query_slides']->result());die();
            $this->load->view('slider',$data);
        }
    }

    function _draw_blocks(){
        //draw slide blocks on homepage
        $data['query'] = $this->get('target_url');
        $num_rows = $data['query']->num_rows();
        
        if($num_rows > 0){
            $this->load->view('sliders',$data);
        }
    }

    function _process_delete($update_id){
        //delete any items associated with this slide block
        $this->load->module('slides');
        $query = $this->slides->get_where_custom('parent_id',$update_id);
        foreach($query->result() as $row){
            $this->slides->_process_delete($row->id);
        }
        //delete item record
        $this->_delete($update_id);
    }


    function _get_title($update_id){
        $title = $this->_get_slider_title($update_id);
        return $title;
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
        redirect('sliders/create/'.$update_id);
    }else {
        $this->_process_delete($update_id);

        $flash_msg = "Slider Delete Successfull";
        $value = "<div class='alert alert-success' role='alert'>".$flash_msg."</div>";
        $data['flash'] = $this->session->flashdata('item',$value);
        redirect('sliders/manage');
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
        $data['view_module'] = 'sliders';
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
            $data['target_url'] = $i;
            $this->_update($update_id,$data);
        }
    }

  

  
    function _get_slider_title($update_id){
        $data = $this->fetch_data_from_db($update_id); 
        return $slider_title = $data['slider_title'];
        
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
            $this->form_validation->set_rules('slider_title', 'Slider Title', 'required|max_length[240]');
            $this->form_validation->set_rules('target_url', 'Target URL', 'required|max_length[240]');
            
            if($this->form_validation->run() == true){
                //get form variables
                // $data['book_cat_url'] = url_title($data['slider_title']);
                if(is_numeric($update_id)){
                    //update the category detail
                    $data = $this->fetch_data_from_post($update_id);
                    
                    $this->_update($update_id,$data);
                    $flash_msg = "Slider Title updated successfully";
                    $value = '<div class="alert alert-success" role="alert">'.$flash_msg.'</div>';
                    $this->session->set_flashdata('item', $value);
                    redirect('sliders/create/'.$update_id);
                }else{
                    
                    $data = $this->fetch_data_from_post();
                    $this->_insert($data);
                    $max_id = $this->get_max();
                    $flash_msg = "Slider Title Inserted Successfully";
                    $value = '<div class="alert alert-success" role="alert">'.$flash_msg.'</div>';
                    $data['flash'] = $this->session->set_flashdata('item', $value);
                    redirect('sliders/create/'.$max_id);
                }
            }
        }elseif($submit == 'Cancel'){
            redirect('sliders/manage');
        }
 
        if(is_numeric($update_id)){
            $data = $this->fetch_data_from_db($update_id);
        }else{
            $data = $this->fetch_data_from_post();
            // print_r($data);
        }
        
        if(!is_numeric($update_id)){
            $data['headline'] = "Add New Homepage Slider";
        }else{
            $data['headline'] = "Update Homepage Slider Details";
        }

        $data['update_id'] = $update_id;
        $data['flash'] = $this->session->flashdata('item');
        $data['view_file'] = "create";
        $this->load->module('templates');
        $this->templates->admin($data);
    }
    function fetch_data_from_post(){
        $data['slider_title'] = $this->input->post('slider_title');
        $data['target_url'] = $this->input->post('target_url');
        // $data['book_parent_cat_id'] = $this->input->post('book_parent_cat_id');
        
        return $data;
    }
 
     function _draw_sortable_list(){
        // $data['query'] = $this->get_where_custom('book_parent_cat_id',$parent_cat_id);
        $mysql_query = "SELECT * FROM sliders ORDER BY target_url";
        $data['query'] = $this->_custom_query($mysql_query);
        $this->load->view('sortable_list',$data);
    }

    function manage(){
        $this->load->module('site_security');
        $this->site_security->_make_sure_is_admin();

        // $data['query'] = $this->get_where_custom('',$);
        $data['query'] = $this->get('slider_title');
        $data['sort_this'] = true;
        $data['headline'] = "Manage Homepage Slides";
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
            $data['slider_title'] = $row->slider_title;
            $data['target_url'] = $row->target_url;
            // $data['book_cat_url'] = $row->book_cat_url;
            // $data['book_parent_cat_id'] = $row->book_parent_cat_id;
        }

        if(!isset($data)){
            $data="";
        }
        return $data;
    }

    function get($order_by) {
        $this->load->model('Mdl_sliders');
        $query = $this->Mdl_sliders->get($order_by);
        return $query;
    }

    function get_with_limit($limit, $offset, $order_by) {
        $this->load->model('Mdl_sliders');
        $query = $this->Mdl_sliders->get_with_limit($limit, $offset, $order_by);
        return $query;
    }

    function get_where($id) {
        $this->load->model('Mdl_sliders');
        $query = $this->Mdl_sliders->get_where($id);
        return $query;
    }

    function get_where_custom($col, $value) {
        $this->load->model('Mdl_sliders');
        $query = $this->Mdl_sliders->get_where_custom($col, $value);
        return $query;
    }

    function _insert($data) {
        $this->load->model('Mdl_sliders');
        $this->Mdl_sliders->_insert($data);
    }

    function _update($id, $data) {
        $this->load->model('Mdl_sliders');
        $this->Mdl_sliders->_update($id, $data);
    }

    function _delete($id) {
        $this->load->model('Mdl_sliders');
        $this->Mdl_sliders->_delete($id);
    }

    function count_where($column, $value) {
        $this->load->model('Mdl_sliders');
        $count = $this->Mdl_sliders->count_where($column, $value);
        return $count;
    }

    function get_max() {
        $this->load->model('Mdl_sliders');
        $max_id = $this->Mdl_sliders->get_max();
        return $max_id;
    }

    function _custom_query($mysql_query) {
        $this->load->model('Mdl_sliders');
        $query = $this->Mdl_sliders->_custom_query($mysql_query);
        return $query;
    }

}