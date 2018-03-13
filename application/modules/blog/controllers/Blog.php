<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Blog extends MX_Controller
{

function __construct() {
parent::__construct();
}

 function _draw_feed_hp($is_mobile = FALSE){
     $this->load->helper('text');
     $mysql_query = "SELECT * FROM blog ORDER BY date_published DESC limit 0,3";
     $data['query'] = $this->_custom_query($mysql_query);

     if($is_mobile == FALSE){
         $view_file = 'feed_hp';
     }else{
         $view_file = 'feed_hp_jqm';
     }
     $this->load->view($view_file,$data);
 }

 function feed(){
     $mysql_query = "SELECT * FROM blog ORDER BY date_published DESC limit 0,3";
     $data['query'] = $this->_custom_query($mysql_query);

     $this->load->module('templates');
    $data['view_file'] = "blog_feed";
    $this->templates->public_bootstrap($data);
 }

 function delete_image($update_id){
         if(!is_numeric($update_id)){
            redirect('site_security/not_allowed');
        }
        
        $this->load->library('session');
        $this->load->module('site_security');
        $this->site_security->_make_sure_is_admin();
        $data = $this->fetch_data_from_db($update_id);
        $picture = $data['picture'];

        $big_pic_path = './public/images/blog/'.$picture;
        $small_picture = str_replace('.','_thumb.',$picture);
        $small_pic_path = './public/images/blog/'.$small_picture;
        // echo $small_pic_path; die();
        // $this->output->set_content_type('application/json')->set_output(json_encode($small_picture));die();
        
        //attempt to remove img
        if(file_exists($big_pic_path)){
            unlink($big_pic_path);
        }
        if(file_exists($small_pic_path)){
            unlink($small_pic_path);
        }
       

        //updating the db
        unset($data);
        $data['picture']= "";
        $this->_update($update_id,$data);

        $flash_msg = "Blog Image delete successful";
        $value = '<div class="alert alert-success" role="alert">'.$flash_msg.'</div>';
        $this->session->set_flashdata('item', $value);
        redirect('blog/create/'.$update_id);
    }

function _generate_thumbnail($filename){
    $config['image_library'] = 'gd2';
    $config['source_image'] = './public/images/blog/'.$filename;
    $config['new_image'] = './public/images/blog/'.$filename;
    $config['create_thumb'] = TRUE;
    $config['maintain_ratio'] = TRUE;
    $config['width']         = 200;
    $config['height']       = 250;
    $this->load->library('image_lib', $config);
    $this->image_lib->resize();
}

function test(){
    $this->load->module('timedate');
    $nowtime = time();
    $datapicker_time = $this->timedate->get_nice_date($nowtime,'datepicker_us');
    //convert 
    echo $datapicker_time;
    $timestamp =  $this->timedate->make_timestamp_from_datepicker($datapicker_time);
    echo $timestamp;
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
            redirect('blog/create/'.$update_id); 
        }
        
        $config['upload_path']          = './public/images/blog/';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 200;
        $config['max_width']            = 2024;
        $config['max_height']           = 1268;
        $config['file_name']  = $this->site_security->generate_random_string(16);

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
            
            //get raw name and file_ext
            $raw_name = $upload_data['raw_name'];
            $file_ext = $upload_data['file_ext'];

            //generate a thumbname name
            // $thumbnail_name = $raw_name."_thumb".$file_ext;

            $file_name = $upload_data['file_name'];

            $this->_generate_thumbnail($file_name);
            //updating database
            $update_data['picture'] = $file_name;
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
    // $data['view_module'] = "blog";
    $data['view_file'] = "upload_image";
    $this->load->module('templates');
    $this->templates->admin($data);
}


function _process_delete($update_id){
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
        redirect('blog/create/'.$update_id);
    }else {
        $this->_process_delete($update_id);
        
        $flash_msg = "Blog Delete Successfull";
        $value = "<div class='alert alert-success' role='alert'>".$flash_msg."</div>";
        $data['flash'] = $this->session->flashdata('item',$value);
        redirect('blog/manage');
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

    function create(){
            $this->load->library('session'); 
            $this->load->module('site_security');
            $this->site_security->_make_sure_is_admin();
            
            $update_id = $this->uri->segment(3);
            
            $submit = $this->input->post('submit',true);
            $this->load->module('timedate');
            if($submit == 'Submit'){
                //process form
                $this->load->library('form_validation');
                $this->form_validation->set_rules('date_published', 'Date Published', 'required');
                $this->form_validation->set_rules('page_title', 'Page Title', 'required|max_length[250]');
                $this->form_validation->set_rules('page_content', 'Page Content', 'required');
                $this->form_validation->set_rules('author', 'Author', 'required');
                
                if($this->form_validation->run() == true){
                    //get form variables
                    $data = $this->fetch_data_from_post();
                    //convert datepicer into unix timestamp
                    $data['date_published'] =  $this->timedate->make_timestamp_from_datepicker_us($data['date_published']);
                    $data['page_url'] = url_title($data['page_title']);
                    if(is_numeric($update_id)){
                        
                        //update the item detail
                        $this->_update($update_id,$data);
                        $flash_msg = "Article updated successfully";
                        $value = '<div class="alert alert-success" role="alert">'.$flash_msg.'</div>';
                        $this->session->set_flashdata('item', $value);
                        redirect('blog/create/'.$update_id);
                    }else{
                        // inser new item
                        $this->_insert($data);
                        $update_id = $this->get_max();//gets the ID of new item
                        $flash_msg = "Article Inserted Successfully";
                        $value = '<div class="alert alert-success" role="alert">'.$flash_msg.'</div>';
                        $data['flash'] = $this->session->set_flashdata('item', $value);
                        redirect('blog/create/'.$update_id);
                    }
                }
            }elseif($submit == 'Cancel'){
                redirect('blog/manage');
            }
            
            
            if($data['date_published'] > 0){
                $data['date_published'] = $this->timedate->get_nice_date($data['date_published'],'datepicker_us');
            }
            if(!is_numeric($update_id)){
                $data['headline'] = "Create Blog Article";
            }else{
                $data['headline'] = "Update Blog Article";
            }


            $data['update_id'] = $update_id;
            $data['flash'] = $this->session->flashdata('item');
            // $data['view_module'] = "blog";
            $data['view_file'] = "create";
            $this->load->module('templates');
            $this->templates->admin($data);
        }

    function manage(){
        $this->load->module('site_security');
        $this->site_security->_make_sure_is_admin();
        
        $data['query'] = $this->get('date_published desc');
        //$data['view_module'] = "blog";
        $data['headline'] = "Manage Item";
        $data['flash'] = $this->session->set_flashdata('item');
        $data['view_file'] = "manage";
        $this->load->module('templates');
        $this->templates->admin($data);
    }

    function fetch_data_from_post(){
        $data['page_title'] = $this->input->post('page_title');
        $data['page_url'] = $this->input->post('page_url');
        $data['page_keywords'] = $this->input->post('page_keywords');
        $data['page_description'] = $this->input->post('page_description');
        $data['page_content'] = $this->input->post('page_content');
        $data['date_published'] = $this->input->post('date_published');
        $data['author'] = $this->input->post('author');
        $data['picture'] = $this->input->post('picture');
        return $data;
    }

    function fetch_data_from_db($update_id){
        $this->load->module('timedate');
        if(!is_numeric($update_id)){
                redirect('site_security/not_allowed');
            }
        $query = $this->get_where($update_id);
        foreach ($query->result() as $row) {
            $data['page_title'] = $row->page_title;
            $data['page_url'] = $row->page_url;
            $data['page_keywords'] = $row->page_keywords;
            $data['page_description'] = $row->page_description;
            $data['page_content'] = $row->page_content;
            $data['date_published'] = $row->date_published;
            $data['author'] = $row->author;
            $data['picture'] = $row->picture;
        }

        if(!isset($data)){
            $data="";
        }
        return $data;
    }



function get($order_by) {
$this->load->model('Mdl_blog');
$query = $this->Mdl_blog->get($order_by);
return $query;
}

function get_with_limit($limit, $offset, $order_by) {
$this->load->model('Mdl_blog');
$query = $this->Mdl_blog->get_with_limit($limit, $offset, $order_by);
return $query;
}

function get_where($id) {
$this->load->model('Mdl_blog');
$query = $this->Mdl_blog->get_where($id);
return $query;
}

function get_where_custom($col, $value) {
$this->load->model('Mdl_blog');
$query = $this->Mdl_blog->get_where_custom($col, $value);
return $query;
}

function _insert($data) {
$this->load->model('Mdl_blog');
$this->Mdl_blog->_insert($data);
}

function _update($id, $data) {
$this->load->model('Mdl_blog');
$this->Mdl_blog->_update($id, $data);
}

function _delete($id) {
$this->load->model('Mdl_blog');
$this->Mdl_blog->_delete($id);
}

function count_where($column, $value) {
$this->load->model('Mdl_blog');
$count = $this->Mdl_blog->count_where($column, $value);
return $count;
}

function get_max() {
$this->load->model('Mdl_blog');
$max_id = $this->Mdl_blog->get_max();
return $max_id;
}

function _custom_query($mysql_query) {
$this->load->model('Mdl_blog');
$query = $this->Mdl_blog->_custom_query($mysql_query);
return $query;
}

}