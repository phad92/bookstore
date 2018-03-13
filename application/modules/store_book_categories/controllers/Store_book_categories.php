<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Store_book_categories extends MX_Controller
{
    // Redo Paginations 
    function __construct() {
        parent::__construct();
    }

    function _draw_side_nav(){
        $data['query'] = $this->_get_categories();
        $this->load->view('side_nav_cat',$data);
    }

    public function search_item(){
        $item = $this->uri->segment(3);
        $item = urldecode(strip_tags($item));
        // $sql_query = "
        //     SELECT
        //     store_books.book_title,
        //     store_books.book_url,
        //     store_books.book_price,
        //     store_books.small_pic,
        //     store_books.was_price
        //     FROM
        //     store_books
        //     INNER JOIN book_cat_assign ON book_cat_assign.book_id = store_books.id
        //     WHERE
        //     book_cat_assign.cat_id = $update_id AND
        //     store_books.`status` = 1

        //     ";
        $mysql_query = "SELECT * FROM store_books WHERE book_title LIKE ?";
        $query = $this->db->query($mysql_query, array("%$item%"));

        //  $query = $this->_custom_query($mysql_query);
        $this->output->set_content_type('application/json')->set_output(json_encode($query->result()));

    }

    function _get_full_cat_url($book_id){
        $this->load->module('site_settings');
        $data = $this->fetch_cat_from_db($book_id);
        $items_segments = $this->site_settings->_get_items_segments();
        
        $cat_url = $data['book_cat_url'];
        $full_cat_url = base_url().$items_segments.$cat_url;
        return $full_cat_url;
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
        // print($update_id);die();
        $this->load->module('site_settings');
        $this->load->module('custom_pagination');
        //fetch the cat details 
        // $update_id = $this->_get_assigned_update_id($update_id);
        $data = $this->fetch_data_from_db($update_id);
        $category = $data['cat_title'];
        // print($category);die();
        $use_limit = false;
        $mysql_query = $this->_generate_mysql_query($update_id,$use_limit);
        $query = $this->_custom_query($mysql_query);
        $total_items = $query->num_rows();
        $data['total_items'] = $total_items;
        
        //fetch the items for this page
        $use_limit = true;
        $mysql_query = $this->_generate_mysql_query($update_id,$use_limit);
        $data['items_segment'] = $this->site_settings->_get_items_segments();
        //data passed for paginating
        $data['item_segment'] = $this->site_settings->_get_item_segments();
        $data['currency_symbol'] = $this->site_settings->_get_currency_symbol();
        $pagination_data['template'] = 'public_bootstrap';
        $pagination_data['base_url'] = base_url($data['items_segment'].$category.'/');
        $pagination_data['target_base_url'] = $this->get_target_pagination_target_url(); 
        $pagination_data['total_rows'] = $total_items;
        $pagination_data['offset_segment'] = $this->site_settings->get_offset(4);
        $pagination_data['limit'] = $this->site_settings->get_limit();
        
        // print_r($pagination_data['base_url']);die();
        $data['pagination'] = $this->custom_pagination->_generate_pagination($pagination_data);
        $data['query'] = $this->_custom_query($mysql_query);
        // $data['item_segment'] = $this->site_settings->_get_item_segments();
        $data['currency_symbol'] = $this->site_settings->_get_currency_symbol();
        //fetch items belonging to this cat
        $data['view_module'] = 'store_books';
        // $data['view_module'] = 'store_book_categories';
        $data['headline'] = "Confirm Delete";
        $data['update_id'] = $update_id;
        $data['flash'] = $this->session->flashdata('item');
        $data['view_file'] = "books";
        // $data['view_file'] = "view";
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
        $this->load->module('site_settings');
        $mysql_query = "
            SELECT *
            FROM
                store_books
            INNER JOIN book_cat_assign ON book_cat_assign.book_id = store_books.id
            WHERE
            book_cat_assign.cat_id = $update_id AND
            store_books.`status` = 1
            ";
        if($use_limit = TRUE){
            $limit = $this->site_settings->get_limit();
            $offset = $this->site_settings->get_offset(4);
            $mysql_query .= " limit ".$offset.", ".$limit;
        }
        return $mysql_query;
    }

    // function get_limit($limit = 5){
    //     return $limit;
    // }

    // function get_offset($offset = 0){
    //     $offset = $this->uri->segment(4);
    //     if(!is_numeric($offset)){
    //         $offset = 0;
    //     }
    //     return $offset;
    // }

    function _get_cat_id_from_cat_url($cat_url){
        $query = $this->get_where_custom('book_cat_url',$cat_url);
        foreach ($query->result() as $row) {
            $cat_id = $row->id;
        }

        if(!isset($cat_id)){
            $cat_id = 0;
        }
        return $cat_id;
    }

    function _draw_top_nav(){
        $this->load->module('site_settings');
        $this->load->module('site_security');
        $mysql_query = "SELECT * FROM store_book_categories WHERE book_parent_cat_id = 0 order by cat_title";
        $query = $this->_custom_query($mysql_query);
        // if(!isset($query)){
        //    $parent_categories = []; 
        // }
        
        foreach($query->result() as $row){
            $parent_categories[$row->id] = $row->cat_title;
        }
        $items_segments = $this->site_settings->_get_items_segments();
        $data['is_logged_id'] = $this->site_security->_is_logged_in();
        $data['audio'] = 'audio';
        $data['ebook'] = 'digital';
        $data['target_url_start'] = base_url().$items_segments;
        $data['parent_categories'] = $parent_categories;
        $this->load->view('top_nav1',$data);
    }


    function _get_parent_cat_title($update_id){
        // print_r($update_id);die();
        $data = $this->fetch_data_from_db($update_id);
        $parent_cat_id = $data['book_parent_cat_id'];
        $parent_cat_title = $this->_get_cat_title($parent_cat_id);
        return $parent_cat_title;
    }

    function _get_all_sub_cats_for_dropdown(){
        //NOTE: this gets used on store cat assign
        $mysql_query = "SELECT * FROM store_book_categories WHERE book_parent_cat_id != 0 order by book_parent_cat_id, cat_title";
        $query = $this->_custom_query($mysql_query);
        // echo json_encode($query->result());die();
        foreach ($query->result() as $row) {
            $parent_cat_title = $this->_get_cat_title($row->book_parent_cat_id);
            // print($parent_cat_title);die();
            $sub_categories[$row->id] = $parent_cat_title . " > " . $row->cat_title; 
        }
        // var_dump($sub_categories);die();
        if(!isset($sub_categories)){
            $sub_categories = "";
        }
        return $sub_categories;
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

    function _draw_sortable_list($parent_cat_id){
        // $data['query'] = $this->get_where_custom('book_parent_cat_id',$parent_cat_id);
        $mysql_query = "SELECT * FROM store_book_categories WHERE book_parent_cat_id = $parent_cat_id ORDER BY priority";
        $data['query'] = $this->_custom_query($mysql_query);
        $this->load->view('sortable_list',$data);
    }

    function _count_sub_cats($update_id){
        //return the number of sub categories belonging to this category
        $query = $this->get_where_custom('book_parent_cat_id',$update_id);
        $num_rows = $query->num_rows();
        return $num_rows;
    }

    function _get_cat_title($book_id){
        $update_id = $this->_get_assigned_cat_id($book_id);
        // $data = $this->fetch_data_from_db($update_id); 
        // print($book_id);die();
        // if(!is_numeric($update_id)){
        //     return $cat_title = 'No Data'; 
        // }
        $category = $this->get_where($update_id);
        $data = $category->row_array();
        // var_dump($data['cat_title']);die();
        $cat_title = $data['cat_title'];
        return $cat_title; 
    }

     function _get_assigned_cat_id($book_id){
        // print($book_id);die();
        $mysql_query = "SELECT * FROM store_book_categories WHERE id = $book_id";
        
        $query = $this->_custom_query($mysql_query);
        
        $assigned_cat = $query->row();
        // print_r($assigned_cat);die();
        return isset($assigned_cat) ? $assigned_cat->id : FALSE;
        // return isset($assigned_cat) ? $assigned_cat->cat_id : FALSE;
    }


    function _get_dropdown_options($update_id){
        if(!is_numeric($update_id)){
            $update_id = 0;
        }

        $options[''] = "Please Select...";
        $mysql_query = "SELECT * FROM store_book_categories WHERE book_parent_cat_id=0 AND id != $update_id";
        $query = $this->_custom_query($mysql_query);
        foreach($query->result() as $row){
            $options[$row->id] = $row->cat_title;
        }
        return $options;
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
            $this->form_validation->set_rules('cat_title', 'Category Title', 'required|max_length[240]');
            
            if($this->form_validation->run() == true){
                //get form variables
                $data = $this->fetch_data_from_post();
                $data['book_cat_url'] = url_title($data['cat_title']);
                if(is_numeric($update_id)){
                    //update the category detail
                    $this->_update($update_id,$data);
                    $flash_msg = "Category updated successfully";
                    $value = '<div class="alert alert-success" role="alert">'.$flash_msg.'</div>';
                    $this->session->set_flashdata('item', $value);
                    redirect('store_book_categories/create/'.$update_id);
                }else{
                    // inser new category
                    // if(!is_numeric($update_id)){
                    //     $data['book_parent_cat_id'] = 0;
                    // }
                    $this->_insert($data);
                    $update_id = $this->get_max();//gets the ID of new category
                    $flash_msg = "Category Inserted Successfully";
                    $value = '<div class="alert alert-success" role="alert">'.$flash_msg.'</div>';
                    $data['flash'] = $this->session->set_flashdata('item', $value);
                    // redirect('store_book_categories/create/'.$update_id);
                    redirect('store_book_categories/create/');
                }
            }
        }elseif($submit == 'Cancel'){
            redirect('store_book_categories/manage');
        }
        if((is_numeric($update_id)) && ($submit = 'Submit')){
            $data = $this->fetch_data_from_db($update_id);
        }else{
            $data = $this->fetch_data_from_post();
            $data['big_pic'] = '';
            // print_r($data);
        }
        
        if(!is_numeric($update_id)){
            $data['headline'] = "Add New Category";
        }else{
            $data['headline'] = "Update Category Details";
        }

        $data['options'] = $this->_get_dropdown_options($update_id);
        $data['num_dropdown_options']= count($data['options']);
        $data['update_id'] = $update_id;
        $data['flash'] = $this->session->flashdata('item');
        $data['view_file'] = "create";
        $this->load->module('templates');
        $this->templates->admin($data);
    }

    function manage(){
        $this->load->module('site_security');
        $this->site_security->_make_sure_is_admin();
        
        $parent_cat_id = $this->uri->segment(3);
        if(!is_numeric($parent_cat_id)){ 
            $parent_cat_id = 0;
        }

        $data['query'] = $this->get_where_custom('book_parent_cat_id',$parent_cat_id);
        // $data['query'] = $this->get('cat_title');

        $data['sort_this'] = true;
        $data['parent_cat_id'] = $parent_cat_id;
        $data['headline'] = "Manage Category";
        $data['flash'] = $this->session->set_flashdata('item');
        $data['view_file'] = "manage";
        $this->load->module('templates');
        $this->templates->admin($data);
    }

    /**
     * Gets both parent categories and child categories.... all categories with id of 0 is a parent category
     * @param $cat_id @desc category id
     * @param $order_by @desc Order by statement
     */
    function _get_categories($cat_id = 0, $order_by='priority'){
        $mysql_query = "SELECT * FROM store_book_categories WHERE book_parent_cat_id = $cat_id order by $order_by";
        $query = $this->_custom_query($mysql_query);   
        return $query;
    }

    

    function fetch_cat_from_db($book_id){
        $update_id = $this->_get_assigned_cat_id($book_id);
        $refer_url = $_SERVER['HTTP_REFERER'];
        if(!is_numeric($update_id)){
            redirect($refer_url);
        }
        $query = $this->get_where($update_id);
        foreach ($query->result() as $row) {
            $data['cat_title'] = $row->cat_title;
            $data['book_cat_url'] = $row->book_cat_url;
            $data['book_parent_cat_id'] = $row->book_parent_cat_id;
        }

        if(!isset($data)){
            $data="";
        }
        return $data;
        
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
            $data['cat_title'] = $row->cat_title;
            $data['book_cat_url'] = $row->book_cat_url;
            $data['book_parent_cat_id'] = $row->book_parent_cat_id;
        }

        if(!isset($data)){
            $data="";
        }
        return $data;
    }

    function get($order_by) {
        $this->load->model('Mdl_store_book_categories');
        $query = $this->Mdl_store_book_categories->get($order_by);
        return $query;
    }

    function get_with_limit($limit, $offset, $order_by) {
        $this->load->model('Mdl_store_book_categories');
        $query = $this->Mdl_store_book_categories->get_with_limit($limit, $offset, $order_by);
        return $query;
    }

    function get_where($id) {
        $this->load->model('Mdl_store_book_categories');
        $query = $this->Mdl_store_book_categories->get_where($id);
        return $query;
    }

    function get_where_custom($col, $value) {
        $this->load->model('Mdl_store_book_categories');
        $query = $this->Mdl_store_book_categories->get_where_custom($col, $value);
        return $query;
    }

    function _insert($data) {
        $this->load->model('Mdl_store_book_categories');
        $this->Mdl_store_book_categories->_insert($data);
    }

    function _update($id, $data) {
        $this->load->model('Mdl_store_book_categories');
        $this->Mdl_store_book_categories->_update($id, $data);
    }

    function _delete($id) {
        $this->load->model('Mdl_store_book_categories');
        $this->Mdl_store_book_categories->_delete($id);
    }

    function count_where($column, $value) {
        $this->load->model('Mdl_store_book_categories');
        $count = $this->Mdl_store_book_categories->count_where($column, $value);
        return $count;
    }

    function get_max() {
        $this->load->model('Mdl_store_book_categories');
        $max_id = $this->Mdl_store_book_categories->get_max();
        return $max_id;
    }

    function _custom_query($mysql_query) {
        $this->load->model('Mdl_store_book_categories');
        $query = $this->Mdl_store_book_categories->_custom_query($mysql_query);
        return $query;
    }

}