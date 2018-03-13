<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Slides extends MX_Controller
{

function __construct() {
parent::__construct();
}

function _get_parent_title($parent_id){
    $parent_module_name = 'sliders';
    $this->load->module($parent_module_name);
    $parent_title = $this->$parent_module_name->_get_title($parent_id);
    return $parent_title;
}

function _get_entity_name($type){
    //Not type can be singular or plural
    if($type == "singular"){
        $entity_name = 'slide';
    }else{
        $entity_name = 'slides';
    }

    return $entity_name;
}

function _get_update_group_headline($parent_id){
    $parent_title = ucfirst($this->_get_parent_title($parent_id));
    $entity_name = ucfirst($this->_get_entity_name('plural'));
    $headline = "Update $entity_name for $parent_title";
    return $headline;
}

function update_group($parent_id){
    //manage records belonging to a parent
    $this->load->module('site_security');
    $this->site_security->_make_sure_is_admin();
    
    $data['query'] = $this->get_where_custom('parent_id',$parent_id);
    $data['num_rows'] = $data['query']->num_rows();
    $data['entity_name'] = ucfirst($this->_get_entity_name('plural'));
    $data['parent_id'] = $parent_id;
    $data['parent_title'] = $this->_get_parent_title($data['parent_id']);
    $data['headline'] = $this->_get_update_group_headline($parent_id);
    
    $data['flash'] = $this->session->set_flashdata('item');
    $data['view_file'] = "update_group";
    $this->load->module('templates');
    $this->templates->admin($data);
}

function _draw_create_modal($parent_id){
    //modal for creating a new record
    $data['parent_id'] = $parent_id;
    $data['form_location'] = base_url('slides/submit_create');
    $this->load->view('create_modal',$data);
}

function submit_create(){
    //create a new record from submitted form
    $this->load->module('site_security');
    $this->site_security->_make_sure_is_admin();

    $data['parent_id'] = $this->input->post('parent_id',true);
    $data['target_url'] = $this->input->post('target_url',true);
    $data['alt_text'] = $this->input->post('alt_text',true);

    $this->_insert($data);

    $max_id = $this->get_max();
    redirect('slides/view/'.$max_id);
}

function view($update_id){
    //view details regarding this record & display form
    $this->load->module('site_security');
    $this->site_security->_make_sure_is_admin();
    
    
    $update_id = $this->uri->segment(3);
    
    $submit = $this->input->post('submit',true);
    if($submit == 'Submit'){

    }
      
    if($submit == 'Cancel'){
        redirect('slides/update_group/'.$parent_id);
    }
    if($submit != 'Submit'){
        $data = $this->fetch_data_from_db($update_id);
    }else{
        $data = $this->fetch_data_from_post();
        $data['picture'] = '';
        // print_r($data);
    }
    
    $entity_name = ucfirst($this->_get_entity_name('singular'));
    $data['entity_name'] = $entity_name;
    $data['headline'] = "Update ".$entity_name;
    $data['update_id'] = $update_id;
    $data['flash'] = $this->session->flashdata('book');
    // $data['view_module'] = "store_books";
    $data['view_file'] = "view";
    $this->load->module('templates');
    $this->templates->admin($data);

}

function _draw_img_btn($update_id){
    //draw an upload image button on top of the view page
    $data = $this->fetch_data_from_db($update_id);
    $picture = $data['picture'];
    if($picture == ''){
        $data['got_pic'] = FALSE;
        $data['btn_style'] = '';
        $data['btn_info'] = "No picture is uploaded for this slide";
    }else{
        $data['got_pic'] = TRUE;
        $data['btn_style'] = " style='clear: both; margin-top: 24px;'";
        $data['btn_info'] = "The picture that is being used for this slide is shown below";
        $data['pic_path'] = base_url('public/images/img/'.$picture);
    }

    $this->load->view('img_btn',$data);
}

function submit($update_id){
    //update record submitted from the view
    $this->load->module('site_security');
    $this->site_security->_make_sure_is_admin();
    $submit = $this->input->post('submit',true);
    $target_url = $this->input->post('target_url',true);
    $alt_text = $this->input->post('alt_text',true);

    if($submit == "Cancel"){
        $parent_id = $this->_get_parent_id($update_id);
        redirect('slides/update_group/'.$parent_id); 
    }elseif ($submit == "Submit") {
        $data['target_url'] = $target_url;
        $data['alt_text'] = $alt_text;
        $this->_update($update_id,$data);
        redirect('slides/view/'.$update_id);
    }
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
        redirect('slides/view/'.$update_id);
    }elseif($submit == "Delete") {
        // echo $submit;die();
        $parent_id = $this->_get_parent_id($update_id);
        $this->_process_delete($update_id);
        $entity_name = $this->_get_entity_name('singular');
        $flash_msg = "The $entity_name Delete Successfull";
        $value = "<div class='alert alert-success' role='alert'>".$flash_msg."</div>";
        $data['flash'] = $this->session->flashdata('item',$value);
        redirect('slides/update_group/'.$parent_id);
    }
}

  function deleteconf($update_id){
    if(!is_numeric($update_id)){
        redirect('site_security/not_allowed');
    }

    $this->load->library('session'); 
    $this->load->module('site_security');
    $this->site_security->_make_sure_is_admin();
    $submit = $this->input->post('submit',true);

    
    $data['headline'] = "Confirm Delete";
    $data['update_id'] = $update_id;
    
    // $data['view_module'] = "blog";
    $data['view_file'] = "deleteconf";
    $this->load->module('templates');
    $this->templates->admin($data);
}

 function _process_delete($update_id){
        
        //fetch img
        $data = $this->fetch_data_from_db($update_id);
        $picture = $data['picture'];

        //get img location
        $picture_path = './public/images/img/'.$picture;
        
        //attempt to remove img
        if(file_exists($picture_path)){
            unlink($picture_path);
        }
    
        //delete item record
        $this->_delete($update_id);
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
    
    function do_upload($update_id){
        if(!is_numeric($update_id)){
            redirect('site_security/not_allowed');
        }
        $this->load->library('session');
        $this->load->module('site_security');
        $this->site_security->_make_sure_is_admin();

        $submit = $this->input->post('submit');
        if($submit == "Cancel"){
            $parent_id = $this->_get_parent_id($update_id);
            redirect('slides/update_group/'.$parent_id); 
        }
        
        $config['upload_path']          = './public/images/img/';
        $config['allowed_types']        = 'gif|jpg|png';
        $config['max_size']             = 400;
        $config['max_width']            = 1624;
        $config['max_height']           = 968;
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

            //updating database
            $update_data['picture'] = $file_name;
            $this->_update($update_id,$update_data);

            
            // divert user back to view
            redirect('slides/view/'.$update_id);
        }
    }

    

function _get_parent_id($update_id){
    
    $data = $this->fetch_data_from_db($update_id);
    $parent_id = $data['parent_id'];
    return $parent_id;
}


function fetch_data_from_post(){
        $data['target_url'] = $this->input->post('target_url');
        $data['alt_text'] = $this->input->post('alt_text');
        $data['parent_id'] = $this->input->post('parent_id');
        // $data['picture'] = $this->input->post('picture');
        return $data;
    }

    function fetch_data_from_db($update_id){
        
        if(!is_numeric($update_id)){
                redirect('site_security/not_allowed');
            }
        // print_r($update_id);die();
        $query = $this->get_where($update_id);
        foreach ($query->result() as $row) {
            $data['target_url'] = $row->target_url;
            $data['alt_text'] = $row->alt_text;
            $data['picture'] = $row->picture;
            $data['parent_id'] = $row->parent_id;
        }

        if(!isset($data)){
            $data="";
        }
        return $data;
    }


function get($order_by) {
$this->load->model('Mdl_slides');
$query = $this->Mdl_slides->get($order_by);
return $query;
}

function get_with_limit($limit, $offset, $order_by) {
$this->load->model('Mdl_slides');
$query = $this->Mdl_slides->get_with_limit($limit, $offset, $order_by);
return $query;
}

function get_where($id) {
$this->load->model('Mdl_slides');
$query = $this->Mdl_slides->get_where($id);
return $query;
}

function get_where_custom($col, $value) {
$this->load->model('Mdl_slides');
$query = $this->Mdl_slides->get_where_custom($col, $value);
return $query;
}

function _insert($data) {
$this->load->model('Mdl_slides');
$this->Mdl_slides->_insert($data);
}

function _update($id, $data) {
$this->load->model('Mdl_slides');
$this->Mdl_slides->_update($id, $data);
}

function _delete($id) {
$this->load->model('Mdl_slides');
$this->Mdl_slides->_delete($id);
}

function count_where($column, $value) {
$this->load->model('Mdl_slides');
$count = $this->Mdl_slides->count_where($column, $value);
return $count;
}

function get_max() {
$this->load->model('Mdl_slides');
$max_id = $this->Mdl_slides->get_max();
return $max_id;
}

function _custom_query($mysql_query) {
$this->load->model('Mdl_slides');
$query = $this->Mdl_slides->_custom_query($mysql_query);
return $query;
}

}