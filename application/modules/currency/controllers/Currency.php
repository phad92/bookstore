<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Currency extends MX_Controller
{

function __construct() {
parent::__construct();
}

function _convert_dollar_to_cent($amount){
    $amt = $amount * 100;
    return $amt;
}

function _convert_cent_to_dollar($amount){
    return $amount / 100;
}

function get($order_by) {
$this->load->model('Mdl_currency');
$query = $this->Mdl_currency->get($order_by);
return $query;
}

function get_with_limit($limit, $offset, $order_by) {
$this->load->model('Mdl_currency');
$query = $this->Mdl_currency->get_with_limit($limit, $offset, $order_by);
return $query;
}

function get_where($id) {
$this->load->model('Mdl_currency');
$query = $this->Mdl_currency->get_where($id);
return $query;
}

function get_where_custom($col, $value) {
$this->load->model('Mdl_currency');
$query = $this->Mdl_currency->get_where_custom($col, $value);
return $query;
}

function _insert($data) {
$this->load->model('Mdl_currency');
$this->Mdl_currency->_insert($data);
}

function _update($id, $data) {
$this->load->model('Mdl_currency');
$this->Mdl_currency->_update($id, $data);
}

function _delete($id) {
$this->load->model('Mdl_currency');
$this->Mdl_currency->_delete($id);
}

function count_where($column, $value) {
$this->load->model('Mdl_currency');
$count = $this->Mdl_currency->count_where($column, $value);
return $count;
}

function get_max() {
$this->load->model('Mdl_currency');
$max_id = $this->Mdl_currency->get_max();
return $max_id;
}

function _custom_query($mysql_query) {
$this->load->model('Mdl_currency');
$query = $this->Mdl_currency->_custom_query($mysql_query);
return $query;
}

}