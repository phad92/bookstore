<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Perfectcontroller extends MX_Controller
{

function __construct() {
parent::__construct();
}

function get($order_by) {
$this->load->model('Mdl_perfectcontroller');
$query = $this->Mdl_perfectcontroller->get($order_by);
return $query;
}

function get_with_limit($limit, $offset, $order_by) {
$this->load->model('Mdl_perfectcontroller');
$query = $this->Mdl_perfectcontroller->get_with_limit($limit, $offset, $order_by);
return $query;
}

function get_where($id) {
$this->load->model('Mdl_perfectcontroller');
$query = $this->Mdl_perfectcontroller->get_where($id);
return $query;
}

function get_where_custom($col, $value) {
$this->load->model('Mdl_perfectcontroller');
$query = $this->Mdl_perfectcontroller->get_where_custom($col, $value);
return $query;
}

function _insert($data) {
$this->load->model('Mdl_perfectcontroller');
$this->Mdl_perfectcontroller->_insert($data);
}

function _update($id, $data) {
$this->load->model('Mdl_perfectcontroller');
$this->Mdl_perfectcontroller->_update($id, $data);
}

function _delete($id) {
$this->load->model('Mdl_perfectcontroller');
$this->Mdl_perfectcontroller->_delete($id);
}

function count_where($column, $value) {
$this->load->model('Mdl_perfectcontroller');
$count = $this->Mdl_perfectcontroller->count_where($column, $value);
return $count;
}

function get_max() {
$this->load->model('Mdl_perfectcontroller');
$max_id = $this->Mdl_perfectcontroller->get_max();
return $max_id;
}

function _custom_query($mysql_query) {
$this->load->model('Mdl_perfectcontroller');
$query = $this->Mdl_perfectcontroller->_custom_query($mysql_query);
return $query;
}

}