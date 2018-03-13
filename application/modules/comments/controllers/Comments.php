<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Comments extends MX_Controller
{

function __construct() {
parent::__construct();
}

function _draw_comments($comment_type, $update_id){
    $mysql_query = "SELECT * FROM comments WHERE comment_type = '$comment_type' AND update_id = '$update_id'";
    $mysql_query .= " ORDER BY date_created";
    $data['query'] = $this->_custom_query($mysql_query);
    $num_rows = $data['query']->num_rows();
    
    if($num_rows > 0){
        $this->load->view('comments',$data);
    }

    // echo "hello";
}
function test(){
    echo 'HELLO WORLD   ';
}

function submit(){
    $this->load->module('site_security');
    $this->site_security->_make_sure_is_admin();

    $data['comment'] = $this->input->post('comment_type',true);
    $data['update_id'] = $this->input->post('update_id',true);
    $data['comment'] = $this->input->post('comment',true);
    $data['date_created'] = time();

    $submit = $this->input->post('submit');
    if($data['comment'] != ''){
        $this->_insert($data);
        $msg = "Comment was successfully submitted";
        $value = "<div class='alert alert-success'>$msg</div>";
        $data['flash'] = $this->session->set_flashdata('item',$value);
        
    }
    
    
    $finish_url = $_SERVER['HTTP_REFERER'];
    redirect($finish_url);
}

function get($order_by) {
$this->load->model('Mdl_comments');
$query = $this->Mdl_comments->get($order_by);
return $query;
}

function get_with_limit($limit, $offset, $order_by) {
$this->load->model('Mdl_comments');
$query = $this->Mdl_comments->get_with_limit($limit, $offset, $order_by);
return $query;
}

function get_where($id) {
$this->load->model('Mdl_comments');
$query = $this->Mdl_comments->get_where($id);
return $query;
}

function get_where_custom($col, $value) {
$this->load->model('Mdl_comments');
$query = $this->Mdl_comments->get_where_custom($col, $value);
return $query;
}

function _insert($data) {
$this->load->model('Mdl_comments');
$this->Mdl_comments->_insert($data);
}

function _update($id, $data) {
$this->load->model('Mdl_comments');
$this->Mdl_comments->_update($id, $data);
}

function _delete($id) {
$this->load->model('Mdl_comments');
$this->Mdl_comments->_delete($id);
}

function count_where($column, $value) {
$this->load->model('Mdl_comments');
$count = $this->Mdl_comments->count_where($column, $value);
return $count;
}

function get_max() {
$this->load->model('Mdl_comments');
$max_id = $this->Mdl_comments->get_max();
return $max_id;
}

function _custom_query($mysql_query) {
$this->load->model('Mdl_comments');
$query = $this->Mdl_comments->_custom_query($mysql_query);
return $query;
}

}