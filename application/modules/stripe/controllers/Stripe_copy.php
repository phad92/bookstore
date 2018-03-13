<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Stripe extends MX_Controller
{
private $secret_key = "sk_test_w981F7LhUUQ3tMmTJZP3jDdP";
private $publishable_key = "pk_test_nYhMsFNc1PNAveqfXgTrIFaa";

function __construct() {
    parent::__construct();
}

public function index()
{
    // $data['view_file'] = "product_form";
    // $this->load->module('templates');
    // $this->templates->bootstrap($data);
	$this->load->view('product_form');		
}

public function check()
	{
		//check whether stripe token is not empty
		if(!empty($_POST['stripeToken']))
		{
			//get token, card and user info from the form
			$token  = $_POST['stripeToken'];
			$name = $_POST['name'];
			$email = $_POST['email'];
			$card_num = $_POST['card_num'];
			$card_cvc = $_POST['cvc'];
			$card_exp_month = $_POST['exp_month'];
			$card_exp_year = $_POST['exp_year'];
			
			//include Stripe PHP library
			require_once APPPATH."third_party/stripe/init.php";
			
			//set api key
			$stripe = array(
			  "secret_key"      => $this->secret_key,
			  "publishable_key" => $this->publishable_key
			);
			// //set api key
			// $stripe = array(
			//   "secret_key"      => "YOUR_SECRET_KEY",
			//   "publishable_key" => "YOUR_PUBLISHABLE_KEY"
			// );
			
			\Stripe\Stripe::setApiKey($stripe['secret_key']);
			
			//add customer to stripe
			$customer = \Stripe\Customer::create(array(
				'email' => $email,
				'source'  => $token
			));
			
			//item information
			$itemName = "Stripe Donation";
			$itemNumber = "PS123456";
			$itemPrice = 50;
			$currency = "usd";
			$orderID = "SKA92712382139";
			
			//charge a credit or a debit card
			$charge = \Stripe\Charge::create(array(
				'customer' => $customer->id,
				'amount'   => $itemPrice,
				'currency' => $currency,
				'description' => $itemNumber,
				'metadata' => array(
					'item_id' => $itemNumber
				)
			));
			
			//retrieve charge details
			$chargeJson = $charge->jsonSerialize();

			//check whether the charge is successful
			if($chargeJson['amount_refunded'] == 0 && empty($chargeJson['failure_code']) && $chargeJson['paid'] == 1 && $chargeJson['captured'] == 1)
			{
				//order details 
				$amount = $chargeJson['amount'];
				$balance_transaction = $chargeJson['balance_transaction'];
				$currency = $chargeJson['currency'];
				$status = $chargeJson['status'];
				$date = date("Y-m-d H:i:s");
			
				
				//insert tansaction data into the database
				$dataDB = array(
					'name' => $name,
					'email' => $email, 
					'card_num' => $card_num, 
					'card_cvc' => $card_cvc, 
					'card_exp_month' => $card_exp_month, 
					'card_exp_year' => $card_exp_year, 
					'item_name' => $itemName, 
					'item_number' => $itemNumber, 
					'item_price' => $itemPrice, 
					'item_price_currency' => $currency, 
					'paid_amount' => $amount, 
					'paid_amount_currency' => $currency, 
					'txn_id' => $balance_transaction, 
					'payment_status' => $status,
					'created' => $date,
					'modified' => $date
				);

				if ($this->db->insert('stripe', $dataDB)) {
					if($this->db->insert_id() && $status == 'succeeded'){
						$data['insertID'] = $this->db->insert_id();
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
        $data['firstname'] = $this->input->post('firstname',true);
        $data['lastname'] = $this->input->post('lastname',true);
        $data['country'] = $this->input->post('country',true);
        $data['postcode'] = $this->input->post('postcode',true);
        $data['town'] = $this->input->post('town',true);
        $data['address1'] = $this->input->post('address1',true);
        $data['address2'] = $this->input->post('address2',true);
        return $data;
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