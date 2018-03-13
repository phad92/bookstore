<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Stripe extends MX_Controller
{
private $secret_key = "sk_test_3KipzGLcmBrUOoDss0HJRma8";
// private $secret_key = "sk_test_w981F7LhUUQ3tMmTJZP3jDdP";
private $publishable_key = "pk_test_i7R3jZBShy66ZwPka3X2xCko";
// private $publishable_key = "pk_test_nYhMsFNc1PNAveqfXgTrIFaa";

function __construct() {
	parent::__construct();
	require_once APPPATH."third_party/stripe/init.php";
	//set api key
		$stripe = array(
		  "secret_key"      => $this->secret_key,
		  "publishable_key" => $this->publishable_key
		);
		// $data = $this->fetch_data_from_post();
		\Stripe\Stripe::setApiKey($stripe['secret_key']);
}

public function index()
{
    // $data['view_file'] = "product_form";
    // $this->load->module('templates');
    // $this->templates->bootstrap($data);
	$this->load->view('product_form');		
}

function order_data($chargeJson,$stripe_id){
	$this->load->module('store_orders');
	$this->load->module('site_security');
	$customer_session_id = $this->session->session_id;
	$shopper_id = $this->site_security->_get_user_id();
	$cart_data = $this->fetch_cart_data();
	$payment_method = 'stripe';
	$order_ref = $chargeJson['source']['brand'];
	$card_type = $chargeJson['metadata']['item_id'];
	$data['order_ref'] = $order_ref;
    $data['date_created'] = time();
    $data['payment_method'] = $payment_method;
    $data['card_type'] = $card_type;
    $data['stripe_id'] = $stripe_id;
    // $data['paypal_id'] = $chargeJson['paypal_id'];
    $data['session_id'] = $customer_session_id;
    $data['num_items'] = $cart_data['num_rows'];
    $data['opened'] = 0;
    $data['order_status'] = 0;
    $data['shopper_id'] = $shopper_id;
	$data['amount_paid'] = $chargeJson['amount'];
	
	return $data;
}


public function check($email,$stripeToken){
	//check whether stripe token is not empty
	if(!empty($stripeToken)){
	// if(!empty($_POST['stripeToken'])){
		//include Stripe PHP library
		
		//add customer to stripe
		$customer = $this->stripe_customer($email,$stripeToken);
		// $customer = $this->stripe_customer($data['email'],$data['stripeToken']);
		return $customer;
	}
	
	return FALSE;
}

function _check_customer_exist($stripe_id){
	$data = $this->fetch_data_from_db($stripe_id);
	if(!empty($data['customer'])){
		return $data['customer'];
	}
	return false;
}

function make_payment($stripe_id){
	$this->load->module('site_security');
	$this->load->module('store_orders');
	
	// $order_ref = $this->site_security->generate_random_string(6);
	// $cart_data = $this->fetch_cart_data();

	// $amt = $this->convert_to_dollar($cart_data['grand_total']); 
	// $item_data = array(
	// 	'customer_id' => $customer->id,
	// 	'itemName'=> "Books",
	// 	'itemNumber' => strtoupper($order_ref),
	// 	// 'itemPrice' => 100,
	// 	'itemPrice' => $amt,
	// 	// 'amount' => $cart_data['grand_total'],
	// 	'currency'=> "usd",
	// 	'num_items' => $cart_data['num_rows'],
	// 	// 'order_ref' => $order_ref
	// );
	$item_data = $this->initialize_stripe_payment($stripe_id);

	//charge a credit or a debit card
	$charge = $this->stripe_charge($item_data);
			
	//retrieve charge details
	$chargeJson = $charge->jsonSerialize();
	print_r($chargeJson);die();
	// echo json_encode($chargeJson);die();
	//check whether the charge is successful
	if($chargeJson['amount_refunded'] == 0 && empty($chargeJson['failure_code']) && $chargeJson['paid'] == 1 && $chargeJson['captured'] == 1)
	{
		$dataDB = $this->stripe_response_data($chargeJson,$item_data);
				
		// echo json_encode($dataDB);die();
		// $dataDB['order_ref'] = $order_ref;
		if ($this->_update($stripe_id, $dataDB)) {
			if($dataDB['payment_status'] == 'succeeded'){
						
				$data['stripe_id'] = $stripe_id;
				$order_data = $this->order_data($chargeJson,$data['stripe_id']);
				// $this->store_orders->_update_order($order_data);
				$order_data['order_ref'] = "order_ref";
				$this->load->view('payment_success', $data);
				// redirect('Welcome/payment_success','refresh');
			}else{
				echo "Transaction has been failed";
			}
		}
		else
		{
			echo "not inserted. Transaction has been failed";
		}

		}
		else
		{
			echo "Invalid Token";
			$statusMsg = "";
		}
	
}

function initialize_stripe_payment($stripe_id){
	$this->load->module('site_security');

	$session_id = $this->session->session_id;
	$token = $this->site_security->_create_token($session_id);

	$data = $this->fetch_data_from_db($stripe_id);
	$customer = $data['customer'];
	$order_ref = $this->site_security->generate_random_string(6);
	$cart_data = $this->fetch_cart_data();

	$amt = $this->convert_to_dollar($cart_data['grand_total']); 
	$item_data = array(
		'customer_id' => $customer,
		'itemName'=> "Books",
		'itemNumber' => strtoupper($order_ref),
		// 'itemPrice' => 100,
		'itemPrice' => $amt,
		// 'amount' => $cart_data['grand_total'],
		'currency'=> "usd",
		'num_items' => $cart_data['num_rows'],
		'order_ref' => $order_ref,
		'token' => $token
	);

	// print_r($item_data);die();
	return $item_data;
}



function convert_to_dollar($amount){
	$this->load->module('currency');
	return $this->currency->_convert_dollar_to_cent($amount);
}
function convert_to_cent($amount){
	$this->load->module('currency');
	echo $this->currency->_convert_cent_to_dollar($amount);
}
function fetch_cart_data(){
	$this->load->module('checkout');
	$session_id = $this->session->session_id;
	$shopper_id = $this->site_security->_get_user_id();
	// echo $shopper_id;die();
	$query = $this->checkout->_calculate_cart_total($session_id,$shopper_id);
	// print_r($query);die();
	// $num_rows = $query->num_rows();
	return $query;
}

function _get_stripe_id($session_id){
	$query = $this->get_where_custom('session_id',$session_id);
	$stripe = $query->row();
	// print_r($stripe);die();
	if(isset($stripe))
		return $stripe->id;

	return false;
	
}

function stripe_response_data($chargeJson,$data){
	//order details 
	$amount = $chargeJson['amount'];
	$balance_transaction = $chargeJson['balance_transaction'];
	$currency = $chargeJson['currency'];
	$status = $chargeJson['status'];
	$charge_id = $chargeJson['id'];
	$customer = $chargeJson['customer'];
	$date = time();
	$shopper_id = $this->site_security->_get_user_id();
	$card_data = $this->fetch_data_from_post();
	$dataDB = array(
		'name' => $card_data['name'],
		'email' => $card_data['email'], 
		'card_num' => $card_data['card_num'], 
		'card_cvc' => $card_data['cvc'], 
		'card_exp_month' => $card_data['exp_month'], 
		'card_exp_year' => $card_data['exp_year'], 
		'item_name' => $data['itemName'], 
		'item_number' => $data['itemNumber'], 
		'item_price' => $data['itemPrice'], 
		'item_price_currency' => $data['currency'], 
		'charge_id' => $charge_id,
		'customer' => $customer,
		'paid_amount' => $amount, 
		'paid_amount_currency' => $currency, 
		'txn_id' => $balance_transaction, 
		'payment_status' => $status,
		'shopper_id' => $shopper_id,
		'created' => $date,
		'modified' => $date
	);
	return $dataDB;
}
function stripe_charge($data){
	$charge = \Stripe\Charge::create(array(
		'customer' => $data['customer_id'],
		'amount'   => $data['itemPrice'],
		'currency' => $data['currency'],
		'description' => $data['itemNumber'],
		'metadata' => array(
		'item_id' => $data['itemNumber']
		)
	));

	return $charge;
}

function stripe_customer($email,$stripeToken){
	$new = 'fadl_185@yahoo.com';
	$customer = \Stripe\Customer::create(array(
		'email' => $new,
		'source'  => $stripeToken
	));

	return $customer;
}



public function payment_success()
{
	$this->load->view('payment_success');
}
public function payment_error()
{
	$this->load->view('payment_error');
}
public function help()
{
	$this->load->view('help');
}

function fetch_data_from_post(){
		$this->load->module('checkout');
		$shopper_id = $this->checkout->_get_shopper_id();
		$email = $this->checkout->_check_email_exist($shopper_id);
        $data['stripeToken'] = $this->input->post('stripeToken',true);
        // $data['id'] = $this->input->post('id',true);
        $data['email'] = $this->input->post('email',true);
        $data['name'] = $this->input->post('name',true);
        $data['card_num'] = $this->input->post('card_num',true);
        $data['card_cvc'] = $this->input->post('card_cvc',true);
        $data['card_exp_month'] = $this->input->post('card_exp_month',true);
		$data['card_exp_year'] = $this->input->post('card_exp_year',true);
		$data['shopper_id'] = $shopper_id;
		$data['created'] = time();
        return $data;
	}
	
function fetch_data_from_db($update_id){
    if(!is_numeric($update_id)){
        redirect('site_security/not_allowed');
    }
    $query = $this->get_where($update_id);
    foreach ($query->result() as $row) {
        // $data['id'] = $row->id;
        $data['customer'] = $row->customer;
        $data['email'] = $row->email;
        $data['name'] = $row->name;
        $data['card_num'] = $row->card_num;
        $data['card_cvc'] = $row->card_cvc;
        $data['card_exp_month'] = $row->card_exp_month;
		$data['card_exp_year'] = $row->card_exp_year;
        $data['session_id'] = $row->session_id;
        $data['shopper_id'] = $row->shopper_id;
		
    }
    if(!isset($data)){
        $data="";
    }
    return $data;
}

function _get_payment_email(){
	$session_id = $this->session->session_id;
	$stripe_id = $this->_get_stripe_id($session_id);
	$data = $this->fetch_data_from_db($stripe_id);
	return $data['email'];
}


function get($order_by) {
$this->load->model('Mdl_stripe');
$query = $this->Mdl_stripe->get($order_by);
return $query;
}

function get_with_limit($limit, $offset, $order_by) {
$this->load->model('Mdl_stripe');
$query = $this->Mdl_stripe->get_with_limit($limit, $offset, $order_by);
return $query;
}

function get_where($id) {
$this->load->model('Mdl_stripe');
$query = $this->Mdl_stripe->get_where($id);
return $query;
}

function get_where_custom($col, $value) {
$this->load->model('Mdl_stripe');
$query = $this->Mdl_stripe->get_where_custom($col, $value);
return $query;
}

function _insert($data) {
$this->load->model('Mdl_stripe');
$this->Mdl_stripe->_insert($data);
}

function _update($id, $data) {
$this->load->model('Mdl_stripe');
$this->Mdl_stripe->_update($id, $data);
}

function _delete($id) {
$this->load->model('Mdl_stripe');
$this->Mdl_stripe->_delete($id);
}

function count_where($column, $value) {
$this->load->model('Mdl_stripe');
$count = $this->Mdl_stripe->count_where($column, $value);
return $count;
}

function get_max() {
$this->load->model('Mdl_stripe');
$max_id = $this->Mdl_stripe->get_max();
return $max_id;
}

function _custom_query($mysql_query) {
$this->load->model('Mdl_stripe');
$query = $this->Mdl_stripe->_custom_query($mysql_query);
return $query;
}

}