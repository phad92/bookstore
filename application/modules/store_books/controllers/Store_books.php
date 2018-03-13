<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Store_books extends MX_Controller
{
    
    
    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->form_validation->CI =& $this;
        // $this->load->model('Mdl_store_books');
    }

    function _get_title($update_id){
        $data = $this->fetch_data_from_db($update_id);
        $book_title = $data['book_title'];
        return $book_title;
    }

    function _get_last_inserted_id(){
        $max_id = $this->get_max();
        return $max_id;
    }

    function _get_book_id_from_book_url($book_url){
        $query = $this->get_where_custom('book_url',$book_url);
        foreach ($query->result() as $row) {
            $book_id = $row->id;
        }

        if(!isset($book_id)){
            $book_id = 0;
        }
        return $book_id;
    }

    
    function all_books(){
        $this->load->module('site_settings');
        $this->load->module('custom_pagination');
        $this->load->module('store_book_categories');
        
        $format='';
        // COUNT NUM OF ITEMS
        $use_limit = FALSE;
        $mysql_query = $this->_generate_mysql_query($format,$use_limit);
        $query = $this->_custom_query($mysql_query);
        $total_items = $query->num_rows();
        $data['total_items'] = $total_items;
        
        // fetch all books for this page
        $use_limit = TRUE;
        $mysql_query = $this->_generate_mysql_query($format,$use_limit);
        $query = $this->_custom_query($mysql_query);
        // echo json_encode($query->result());die();
    
        
    
        // $query = $this->get('date_created');
        // print_r($data['query']->result());die();
        $data['item_segment'] = $this->site_settings->_get_item_segments();
        $data['currency_symbol'] = $this->site_settings->_get_currency_symbol();
        $pagination_data['template'] = 'public_bootstrap';
        $pagination_data['base_url'] = base_url('store_books/all_books');
        $pagination_data['target_base_url'] = $this->get_target_pagination_target_url(); 
        $pagination_data['total_rows'] = $total_items;
        $pagination_data['offset_segment'] = $this->site_settings->get_offset(3);
        $pagination_data['limit'] = $this->site_settings->get_limit();
        // print_r($pagination_data);die();
        $data['pagination'] = $this->custom_pagination->_generate_pagination($pagination_data);
        $data['query'] = $query;
        // var_dump($data['pa/gination']);die();
        $data['audio'] = 'audio';
        $data['ebook'] = 'digital';
        $data['view_file'] = "books";
        $this->load->module('templates');
        $this->templates->public_bootstrap($data);
    }

    function book_type(){
        $this->load->module('site_settings');
        $this->load->module('custom_pagination');
        $this->load->module('store_book_categories');
        // $this->load->module('sort');
        // $books = $this->sort->_get_books_by_format('digital');
        $format = $this->uri->segment(3);
        $sort = $this->uri->segment(4);
        
        $offset = 4;
        $use_limit = FALSE;
        $mysql_query = $this->_generate_mysql_query($format,$use_limit,$sort = '',$offset);
        $query = $this->_custom_query($mysql_query);
        // print_r($query);die();
        $total_items = $query->num_rows();
        $data['total_items'] = $total_items;
        // fetch all books for this page
        $use_limit = TRUE;
        $mysql_query = $this->_generate_mysql_query($format,$use_limit,$sort = '',$offset);
        $query = $this->_custom_query($mysql_query);
        
        $data['item_segment'] = $this->site_settings->_get_item_segments();
        // echo $data['item_segment'];die();
        $data['currency_symbol'] = $this->site_settings->_get_currency_symbol();
        $pagination_data['template'] = 'public_bootstrap';
        $pagination_data['base_url'] = base_url('store_books/book_type/'.$format);
        $pagination_data['offset_segment'] = $this->site_settings->get_offset($offset);
        $pagination_data['target_base_url'] = $this->get_target_pagination_target_url(); 
        $pagination_data['total_rows'] = $total_items;

        if(!empty($sort)){
            $offset = 5;
            $use_limit = FALSE;
            $mysql_query = $this->_generate_mysql_query($format,$use_limit,$sort);
            $query = $this->_custom_query($mysql_query);
            // print_r($query);die();
            $total_items = $query->num_rows();
            $data['total_items'] = $total_items;
            // print($total_items);die();
            // fetch all books for this page
            $use_limit = TRUE;
            $mysql_query = $this->_generate_mysql_query($format,$use_limit,$sort);
            $query = $this->_custom_query($mysql_query);
            $pagination_data['base_url'] = base_url('store_books/book_type/'.$format.$sort);
            $pagination_data['offset_segment'] = $this->site_settings->get_offset($offset);
        }
        
        
        
        $pagination_data['limit'] = $this->site_settings->get_limit();
        // print_r($pagination_data);die();
        $data['pagination'] = $this->custom_pagination->_generate_pagination($pagination_data);
        $data['query'] = $query;
        // var_dump($data['pagination']);die();
        $data['book_count'] = $total_items;
        $data['audio'] = 'audio';
        $data['ebook'] = 'digital';
        $data['view_file'] = "books";
        $this->load->module('templates');
        $this->templates->public_bootstrap($data);
    }

    function _sort_by_latest($sort){
        
    }

    function get_target_pagination_target_url(){
        $first_bit = $this->uri->segment(1);
        $second_bit = $this->uri->segment(2);
        $third_bit = $this->uri->segment(3);
        $forth_bit = $this->uri->segment(4);
        // if(is_numeric($third_bit)){
        //     $target_base_url = base_url().$first_bit.'/'.$second_bit.'/'.$third_bit.'/'.$forth_bit;
        //     print($third_bit);die();
        // }
        $target_base_url = base_url().$first_bit.'/'.$second_bit.'/'.$third_bit.'/'.$forth_bit;
        
        return $target_base_url;
    }



    function _generate_mysql_query($format = null,$use_limit = false,$sort = null,$offset = 3){
        $this->load->module('site_settings');
        $mysql_query = "SELECT *
                    -- store_book_author.`name`
                    FROM
                    store_books
                    -- INNER JOIN store_book_assign_author ON store_books.id = store_book_assign_author.book_id
                    -- INNER JOIN store_book_author ON store_book_author.id = store_book_assign_author.author_id
                    
                ";
        // $mysql_query = "
        //     SELECT * 
        //     FROM store_books";
        if($format !== ''){
            $mysql_query .= " WHERE store_books.book_format = '$format'";
        }
        if(!$sort == ''){
            $sort = $sort;
            $mysql_query .= " ORDER BY $sort";
        }
        if($use_limit === TRUE){
            $limit = $this->site_settings->get_limit();
            $offset = $this->site_settings->get_offset($offset);
            $mysql_query .= " limit ".$offset.", ".$limit;
        }
        return $mysql_query;
    }



    function _auto_generate_book(){
        //this gets called from the paypal IPN listener, when an order is placed
        $this->load->module('site_security');
        $this->load->module('store_book_assign_author');
        // $name= rand(6,25);
        // $order_ref = $this->site_security->generate_random_string(16);
        // $order_ref = strtoupper($order_ref);
        $author = rand(1,19);
        // $desc = "Lorem, ipsum dolor sit amet consectetur adipisicing elit. Saepe delectus, commodi enim nisi nam illo pariatur, a dolorum ipsam ullam eligendi. Nesciunt a officiis odit ipsum! Veritatis atque voluptatem excepturi.Lorem, ipsum dolor sit amet consectetur adipisicing elit. Saepe delectus, commodi enim nisi nam illo pariatur, a dolorum ipsam ullam eligendi. Nesciunt a officiis odit ipsum! Veritatis atque voluptatem excepturi.Lorem, ipsum dolor sit amet consectetur adipisicing elit. Saepe delectus, commodi enim nisi nam illo pariatur, a dolorum ipsam ullam eligendi. Nesciunt a officiis odit ipsum! Veritatis atque voluptatem excepturi.Lorem, ipsum dolor sit amet consectetur adipisicing elit. Saepe delectus, commodi enim nisi nam illo pariatur, a dolorum ipsam ullam eligendi. Nesciunt a officiis odit ipsum! Veritatis atque voluptatem excepturi.Lorem, ipsum dolor sit amet consectetur adipisicing elit. Saepe delectus, commodi enim nisi nam illo pariatur, a dolorum ipsam ullam eligendi. Nesciunt a officiis odit ipsum! Veritatis atque voluptatem excepturi.Lorem, ipsum dolor sit amet consectetur adipisicing elit. Saepe delectus, commodi enim nisi nam illo pariatur, a dolorum ipsam ullam eligendi. Nesciunt a officiis odit ipsum! Veritatis atque voluptatem excepturi.";
        $book = rand(17,254);
        $data['author_id'] = $author;
        $data['book_id'] = $book;
        // $data['book_price'] = $randnum;
        // $data['book'] = '';
        // $data['book_description'] = $desc;
        // $data['book_format'] = $format; 
        // $data['big_pic'] = "9780545586177_p0_v2_s600x595.jpg";
        // $data['small_pic'] = "9780545586177_p0_v2_s600x595.jpg";
        // $data['was_price'] = $was;
        // $data['status'] = 1;
        // print_r($data);die();
       
        $this->store_book_assign_author->_insert($data);
    }

    function _get_book_authors($update_id){
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

        $query = $this->_custom_query($mysql_query);
        $resp = $query->result();
        if($query->num_rows() < 1){
            $result = ' ';
        }else{
            foreach($resp as $row){
                $result[] = $row->name;
            }
        
        }

        return $result;
    }
   
    function test($number){
        //  $format = 0;
        for($i = 0; $i<$number;$i++){
            
            $this->_auto_generate_book();
       }
        
       echo "DONE!!!";
    }

    function view($update_id){
        if(!is_numeric($update_id)){
            redirect('site_security/not_allowed');
        }
        $this->load->module('site_settings');
        $this->load->module('site_security');
        $this->load->module('store_book_author');
        $is_mobile = $this->site_settings->is_mobile();
        //fetch the book details 
        $data = $this->fetch_data_from_db($update_id);
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
        // print_r($data);die();
        $query = $this->_custom_query($mysql_query);
        $resp = $query->result();
        if($query->num_rows() < 1){
            $data['res'] = ' ';
        }else{
            foreach($resp as $row){
                $res[] = $row->author_url;
            }
            $data['res'] = $res;
        }
        
        // print_r($resp);die();
            
        
        //print_r($query->result());die();
        // print_r($data);die();
        //breadcrumb data array
        $breadcrumbs_data['template'] = 'public_bootstrap';
        $breadcrumbs_data['current_page_title'] = $data['book_title'];
        $breadcrumbs_data['breadcrumbs_array'] = $this->_generate_breadcrumbs_array($update_id);
        $data['breadcrumbs_data'] = $breadcrumbs_data;
        
        $item_price =number_format($data['book_price'],2);
        $data['book_category'] = "";
        $data['price'] = str_replace('.00','',$item_price);
        $data['currency_symbol'] = $this->site_settings->_get_currency_symbol();
        $data['books'] = $this->get_with_limit(8,0,'book_title');
        if(!isset($data['book_format'])){
            $data['book_format'] = '';
        }
        
        $query_gallery_pics = $this->_get_gallery_pics($update_id);
        $num_rows = $query_gallery_pics->num_rows();
        // echo $num_rows;die();
        if($num_rows > 0){
            //we have atleast on gallery pic
            $data['use_angularjs'] = false;

            //build an array of all the gallery pics
            $count = 0;
            foreach($query_gallery_pics->result() as $row){
                $gallery_pics[$count] = base_url('public/images/gallery/'.$row->picture);
                $count++;
            }
             
            $data['gallery_pics'] = $gallery_pics;
            $data['view_file'] = 'view_gallery_version';
        }else {
            // print_r($data);die();
            //load normal page

            $data['view_file'] = 'view';
        }
        $data['use_angularjs'] = true;

        $data['url_segment'] = $this->site_settings->_get_item_segments();
        $data['view_module'] = 'store_books';
        $data['headline'] = "Confirm Delete";
        $data['update_id'] = $update_id;
        $data['flash'] = $this->session->flashdata('item');
        $data['use_featherlight'] = TRUE;
        $this->load->module('templates');
        if($is_mobile == false){
            $template = 'public_bootstrap';
            $this->templates->$template($data);
        }else{
            $template = 'public_jqm';
            
            $data['view_file'] .= '_jqm';
            // print_r($data);die();
            $this->templates->$template($data);
        }
        // $this->output->set_content_type('application/json')->set_output(json_encode($data));
        
    }
     
    function _generate_breadcrumbs_array($update_id){
        $homepage_url = base_url();
        $breadcrumbs_array[$homepage_url] = "Home";
        //get subcat id
        $sub_cat_id = $this->_get_sub_cat_id($update_id);
        // get cat title, sub cat title and url of the sub cat id

        $this->load->module('store_book_categories');
        // echo $sub_cat_id;die();
        $sub_cat_title = $this->store_book_categories->_get_cat_title($sub_cat_id);
        //get the sub cat url
        $sub_cat_url = $this->store_book_categories->_get_full_cat_url($sub_cat_id);
        $breadcrumbs_array[$sub_cat_url] = $sub_cat_title; 
        // $breadcrumbs_array['sub_cat_page'] = "sub cat title"; 
        // print_r($breadcrumbs_array);
        return $breadcrumbs_array;
    }
    
    function _get_sub_cat_id($update_id){
        $this->load->module('book_cat_assign');
        $query = $this->book_cat_assign->get_where_custom('book_id',$update_id);
        //print_r($query->result());die();
        foreach ($query->result() as $row) {
            $sub_cat_id = $row->cat_id;
        }
        // print_r($sub_cat_id);
        return $update_id;

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
        //attempt to delete book colours
        $this->load->module('store_book_colors');
        // $this->store_book_colors->_delete_for_book($update_id);
    
        //attempt to delete book sizes
        $this->load->module('store_book_sizes');
        // $this->store_book_colors->_delete_for_book($update_id);
        
        //fetch img
        $data = $this->fetch_data_from_db($update_id);
        $big_pic = $data['big_pic'];
        $small_pic = $data['small_pic'];

        //get img location
        $big_pic_path = './public/images/books/big_pics/'.$big_pic;
        $small_pic_path = './public/images/books/small_pics/'.$small_pic;
        
        //attempt to remove img
        if(file_exists($big_pic_path)){
            unlink($big_pic_path);
        }
        if(file_exists($small_pic_path)){
            unlink($small_pic_path);
        }
    
        //delete book record
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
            redirect('store_books/create/'.$update_id);
        }else {
            $this->_process_delete($update_id);
            
            $flash_msg = "Item Delete Successfull";
            $value = "<div class='alert alert-success' role='alert'>".$flash_msg."</div>";
            $data['flash'] = $this->session->flashdata('book',$value);
            redirect('store_books/manage');
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
        
        // $data['view_module'] = "store_books";
        $data['view_file'] = "deleteconf";
        $this->load->module('templates');
        $this->templates->admin($data);
    }

    /**
     * delete image
     */
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
        $this->session->set_flashdata('book', $value);
        redirect('store_books/create/'.$update_id);
    }

    
    /**
     * making an image upload
     */
    function do_upload($update_id){
        if(!is_numeric($update_id)){
            redirect('site_security/not_allowed');
        }
        $this->load->library('session');
        $this->load->module('site_security');
        $this->site_security->_make_sure_is_admin();

        $submit = $this->input->post('submit');
        if($submit == "Cancel"){
            redirect('store_books/create/'.$update_id); 
        }
        
        $config['upload_path']          = './public/images/books/big_pics/';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 100;
        $config['max_width']            = 1024;
        $config['max_height']           = 768;
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('userfile')){
            $data['error'] = array('error' => $this->upload->display_errors("<p style='color: red;'>","</p>"));
            $data['headline'] = "Upload Error!!!";
            $data['update_id'] = $update_id;
            $data['flash'] = $this->session->flashdata('book');
            $this->load->module('templates');
            $data['view_file'] = "upload_image";
            $this->templates->admin($data);
        }
    }

    /**
     * Loads the upload image page
     */
    function upload_image($update_id){
        if(!is_numeric($update_id)){
            redirect('site_security/not_allowed');
        }

        $this->load->library('session'); 
        $this->load->module('site_security');
        $this->site_security->_make_sure_is_admin();
        
        // $submit = $this->input->post('submit',true);

        $data['update_id'] = $update_id;
        $data['headline'] = "Upload Images";
        $data['flash'] = $this->session->flashdata('book');
        // $data['view_module'] = "store_books";
        $data['view_file'] = "upload_image";
        $this->load->module('templates');
        $this->templates->admin($data);
    }


    

    
    /**
     * Add / update book
     */
    function create(){
        $this->load->library('session'); 
        $this->load->module('site_security');
        $this->load->module('site_settings');
        $this->load->library('upload');
        $this->load->module('store_book_author');

        $this->site_security->_make_sure_is_admin();
        
        $update_id = $this->uri->segment(3);
        
        $submit = $this->input->post('submit',true);
        if($submit == 'Submit'){
            //process form
            $this->load->library('form_validation');
            $this->form_validation->set_rules('book_title', 'Item Title', 'required|max_length[240]|callback_book_check');
            $this->form_validation->set_rules('book_price', 'Item Price', 'required|numeric');
            $this->form_validation->set_rules('book_format', 'Book Format', 'required');
            $this->form_validation->set_rules('was_price', 'Was Price', 'numeric');
            // $this->form_validation->set_rules('userfile', 'Userfile', 'required');
            $this->form_validation->set_rules('status', 'Status', 'required|numeric');
            $this->form_validation->set_rules('book_description', 'Item Description', 'required');
            
            if($this->form_validation->run() == true){
                //get form variables
                if(is_numeric($update_id)){

                    $db_data = $this->fetch_data_from_db($update_id);
                    $old_book = $db_data['book'];
                    $data = $this->fetch_data_from_post();
                    $format = $data['book_format'];
                    $book_upload_config= $this->site_settings->_book_upload_config($format);
                    $this->upload->initialize($book_upload_config);
                    $check_upload = $this->upload->do_upload('bookfile');

                    if($check_upload){
                        $book = $this->upload->data('file_name');
                        $book_path = $this->site_settings->_get_book_path($format,$old_book);
                        // $e = file_exists($book_path);
                        if(file_exists($book_path)){
                            unlink($book_path);
                        }
                        $data['book'] = $book;
                        // print($data['book']);die();
                        $this->_update($update_id,$data);
                    }
                    
                     $this->_update($update_id,$data);
                    $flash_msg = "Item updated successfully";
                    $value = '<div class="alert alert-success" role="alert">'.$flash_msg.'</div>';
                    $this->session->set_flashdata('book', $value);
                    redirect('store_books/create/'.$update_id);
                }else{
                    // inser new book
                    $data = $this->fetch_data_from_post();
                    $data['book_url'] = url_title($data['book_title']);
                    // print_r($data);die();
                    $book_upload_config = $this->site_settings->_book_upload_config($data['book_format']);

                    // print_r($book_upload_config);die();            
                    $this->upload->initialize($book_upload_config);
                    if(!$this->upload->do_upload('bookfile')){
                        $data['error'] = array('error' => $this->upload->display_errors("<p style='color: red;'>","</p>"));
                        $data['headline'] = "Upload Error!!!";
                        $data['flash'] = $this->session->flashdata('book');
                        $this->load->module('templates');
                        $data['view_file'] = "create";
                        $this->templates->admin($data);
                    }
                    $data['book'] = $this->upload->data('file_name');
                    $this->_insert($data);
                    $update_id = $this->get_max();//gets the ID of new book
                    $flash_msg = "Item Inserted Successfully";
                    $value = '<div class="alert alert-success" role="alert">'.$flash_msg.'</div>';
                    $data['flash'] = $this->session->set_flashdata('book', $value);
                    redirect('store_books/create/'.$update_id);
                }
            }
        }elseif($submit == 'Cancel'){
            redirect('store_books/manage');
        }
        if((is_numeric($update_id)) && ($submit = 'Submit')){
            $data = $this->fetch_data_from_db($update_id);
        }else{
            $data = $this->fetch_data_from_post();
            $data['big_pic'] = '';
            // print_r($data);
        }
        
        if(!is_numeric($update_id)){
            $data['headline'] = "Add NewBook";
        }else{
            $data['headline'] = "UpdateBook Details";
            $sql_query = "SELECT * FROM store_book_detail WHERE book_id = $update_id";
            $q = $this->_custom_query($sql_query);
            $data['detail_check'] = $q->row();
        }
        
        $data['author_rem'] = "	<h3 style='color: red;'>NOTE: Please Provide Book Author's Name by clicking the first Button Above. Thank You.</h3>";
        $data['got_gallery_pic'] = $this->_got_gallery_pic($update_id);
        $data['update_id'] = $update_id;
        $data['flash'] = $this->session->flashdata('book');
        $data['view_module'] = "store_books";
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

    function _get_gallery_pics($update_id){
        $this->load->module('item_galleries');
        $query = $this->item_galleries->get_where_custom('parent_id',$update_id);
        $num_rows = $query->num_rows();
        return $query;
    }
    
    
    /**
     * displays book management page
     */
    function manage(){
        $this->load->module('site_security');
        $this->site_security->_make_sure_is_admin();
        
        $data['query'] = $this->get('book_title');

        //$data['view_module'] = "store_books";
        $data['headline'] = "ManageBook";
        $data['flash'] = $this->session->set_flashdata('book');
        $data['view_file'] = "manage";
        $this->load->module('templates');
        $this->templates->admin($data);
    }

    /**
     * Fetches data from post request
     */
    function fetch_data_from_post(){
        $data['book_title'] = $this->input->post('book_title');
        $data['book_price'] = $this->input->post('book_price');
        $data['was_price'] = $this->input->post('was_price');
        $data['book_format'] = $this->input->post('book_format');
        // $data['bookfile']
        // $data['file'] = $this->upload_book();
        $data['book_description'] = $this->input->post('book_description');
        $data['status'] = $this->input->post('status');
        return $data;
    }

    /**
     * Fetches data from database using book id into an array
     */
    function fetch_data_from_db($update_id){
        // if(!is_numeric($update_id)){
        //         redirect('site_security/not_allowed');
        //     }
        $query = $this->get_where($update_id);
        foreach ($query->result() as $row) {
            $data['id'] = $row->id;
            $data['book_title'] = $row->book_title;
            $data['book_url'] = $row->book_url;
            $data['book_price'] = $row->book_price;
            $data['book'] = $row->book;
            $data['book_description'] = $row->book_description;
            $data['book_format'] = $row->book_format; 
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

    /**
     * fetches all records from db
     */
    function get($order_by) {
        $this->load->model('Mdl_store_books');
        $query = $this->Mdl_store_books->get($order_by);
        return $query;
    }

    /**
     * fetch records with limits , offset, and order
     */
    function get_with_limit($limit, $offset, $order_by) {
        $this->load->model('Mdl_store_books');
        $query = $this->Mdl_store_books->get_with_limit($limit, $offset, $order_by);
        return $query;
    }

    function get_where($id) {
        $this->load->model('Mdl_store_books');
        $query = $this->Mdl_store_books->get_where($id);
        return $query;
    }

    function get_where_custom($col, $value) {
        $this->load->model('Mdl_store_books');
        $query = $this->Mdl_store_books->get_where_custom($col, $value);
        return $query;
    }

    function _insert($data) {
        $this->load->model('Mdl_store_books');
        $this->Mdl_store_books->_insert($data);
    }

    function _update($id, $data) {
        $this->load->model('Mdl_store_books');
        $this->Mdl_store_books->_update($id, $data);
    }

    function _delete($id) {
        $this->load->model('Mdl_store_books');
        $this->Mdl_store_books->_delete($id);
    }

    function count_where($column, $value) {
        $this->load->model('Mdl_store_books');
        $count = $this->Mdl_store_books->count_where($column, $value);
        return $count;
    }

    function get_max() {
        $this->load->model('Mdl_store_books');
        $max_id = $this->Mdl_store_books->get_max();
        return $max_id;
    }

    function _custom_query($mysql_query) {
        $this->load->model('Mdl_store_books');
        $query = $this->Mdl_store_books->_custom_query($mysql_query);
        return $query;
    }
    
    function book_check($str){
        $book_url = url_title($str);
        $mysql_query = "SELECT * FROM store_books WHERE book_title='$str' AND book_url='$book_url'";
        $update_id = $this->uri->segment(3);
        if(is_numeric($update_id)){
            //this is an update
            $mysql_query .= " AND id !=$update_id";
        }

        $query = $this->_custom_query($mysql_query);
        $num_rows = $query->num_rows();

        if($num_rows > 0){
            $this->form_validation->set_message('book_check','TheBook you submitted is not available');
            return FALSE;
        }else {
            return TRUE;
        }
    }
} 