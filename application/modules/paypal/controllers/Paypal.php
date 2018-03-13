<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Paypal extends MX_Controller
{

function __construct() {
parent::__construct();
// $this->load->library('omnipaygateway');
}

function index(){
    echo heading("paypal Integration",1);
    $cardInput = array(
        'firstName' => 'Fadlu',
        'lastName' => 'Haruna',
        'number' => '4111 1111 1111 1111',
        'cvv' => '132',
        'expiryMonth' => 06,
        'expiryYear' => 19,
        'email' => 'fadlu.haruna@gmail.com',
    );

    $valTrans = array(
        'amount' => number_format(100,'.',','),
        'transactionId' => 1,
        'currency' => 'USD',
        'clientIp' =>'',
        'returnUrl' =>'http://example.com',
    );
    $purchaseProc = new omnipaygateway('Paypal_Pro',true);
    $data = $purchaseProc->sendPurchase($cardInput,$valTrans);
    echo '<pre>';print_r($data);
}

function _display_summary_info($update_id){
    $this->load->module('timedate');
    $query = $this->get_where($update_id);
    foreach($query->result() as $row){
        $date_created = $row->date_created;
        $posted_info = $row->posted_info;
    }
    
    $data = unserialize($posted_info);
    $data['date_created'] = $this->timedate->get_nice_date($date_created,'full');
    if($data['payer_business_name'] == ""){
        $data['payer_business_name'] ="-";
    }
    $this->load->view('summary_info',$data);
}

function submit_test(){
    $on_test_mode = $this->_is_on_test_mode();
    
    $num_orders = $this->input->post('num_orders');
    $custom = $this->input->post('custom');

    if(($on_test_mode == FALSE) OR (!is_numeric($num_orders))){
        die();
    }
    //simulate order creation
    $paypal_id = 88;
    $this->load->module('store_basket');
    $this->load->module('store_orders');
    $this->load->module('site_security');
    $customer_session_id = $this->site_security->_decrypt_string($custom);

    $query = $this->store_basket->get_where_custom('session_id',$customer_session_id);
    foreach($query->result() as $row){
        $store_basket_data['session_id'] = $row->session_id;
        $store_basket_data['item_title'] = $row->item_title;
        $store_basket_data['item_price'] = $row->item_price;
        $store_basket_data['tax'] = $row->tax;
        $store_basket_data['item_id'] = $row->item_id;
        $store_basket_data['item_size'] = $row->item_size;
        $store_basket_data['item_qty'] = $row->item_qty;
        $store_basket_data['date_added'] = $row->date_added;
        $store_basket_data['item_color'] = $row->item_color;
        $store_basket_data['shopper_id'] = $row->shopper_id;
        $store_basket_data['ip_address'] = $row->ip_address;
    }

    for ($i=0; $i < $num_orders; $i++) { 
        $this->store_orders->_auto_generate_order($paypal_id,$customer_session_id);
        $this->store_basket->_insert($store_basket_data);
    }
    
    echo "Done";
}
function ipn_listener(){
//        header('HTTP/1.1 200 OK'); //ACKWOLEDGES THAT NOTIFICATION IS RECEIVED FROM PAYPAL
//        // STEP 1: read POST data
//        // Reading POSTed data directly from $_POST causes serialization issues with array data in the POST.
//        // Instead, read raw POST data from the input stream.
//        $raw_post_data = file_get_contents('php://input');
//        $raw_post_array = explode('&', $raw_post_data);
//        $myPost = array();
//        foreach ($raw_post_array as $keyval) {
//        $keyval = explode ('=', $keyval);
//        if (count($keyval) == 2)
//            $myPost[$keyval[0]] = urldecode($keyval[1]);
//        }
//        // read the IPN message sent from PayPal and prepend 'cmd=_notify-validate'
//        $req = 'cmd=_notify-validate';
//        if (function_exists('get_magic_quotes_gpc')) {
//        $get_magic_quotes_exists = true;
//        }
//        foreach ($myPost as $key => $value) {
//        if ($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
//            $value = urlencode(stripslashes($value));
//        } else {
//            $value = urlencode($value);
//        }
//        $req .= "&$key=$value";
//        }

//    //  // Step 2: POST IPN data back to PayPal to validate
//    // //  $ch = curl_init('https://ipnpb.paypal.com/cgi-bin/webscr');
//     //  $ch = curl_init('https://ipnpb.sandbox.paypal.com/cgi-bin/webscr');
//      $ch = curl_init('https://ipnpb.sandbox.paypal.com/cgi-bin/webscr');
//      curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
//      curl_setopt($ch, CURLOPT_POST, 1);
//      curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
//      curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
//      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
//      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
//      curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
//      curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
//      // In wamp-like environments that do not come bundled with root authority certificates,
//      // please download 'cacert.pem' from "https://curl.haxx.se/docs/caextract.html" and set
//      // the directory path of the certificate as shown below:
//      // curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
//      if ( !($res = curl_exec($ch)) ) {
//      // error_log("Got " . curl_error($ch) . " when processing IPN data");
//      curl_close($ch);
//      exit;
//      }
//      curl_close($ch);

//        // inspect IPN validation result and act accordingly
//        if (strcmp ($res, "VERIFIED") == 0) {
//        // The IPN is verified, process it
//          // the url that accepts things that paypal has posted. Use tokens for the url
//            $data['date_created'] = time();
//            $posted_info = '';
//            $this->load->module('site_security');
//            foreach($_POST as $key => $value){
//                if($key == 'custom'){
//                    $customer_session_id = $this->site_security->_decrypt_string($value);
//                    $value = $customer_session_id;
//                }

//                // $posted_info .= "Key of <strong>$key</strong> was posted with a Value of <strong>$value</strong> <br>";
//                $posted_info[$key] = $value;
//              }
//     //         print_r($posted_info);die();
        
//            // $data['posted_info'] = serialize($posted_info);
//          //   echo $res;die();
//         //  $this->_insert($data);
//         //  $max_id = $this->get_max();
//         //  $this->load->module('store_orders');
//         //  $this->store_orders->_auto_generate_order($max_id,$customer_session_id);
//          } else if (strcmp ($res, "INVALID") == 0) {
//         //  $this->load->helper('download');
          
//          //   echo $res;die();
//          echo "try again";
//           print_r($res);die();
//        // IPN invalid, log for manual investigation
//        }

    //the url that accepts things that paypal has posted. Use tokens for the url
    $data['date_created'] = time();
    $posted_info = '';
    foreach($_POST as $key => $value){
        $posted_info .= "Key of <strong>$key</strong> was posted with a Value of <strong>$value</strong> <br>";
    }
    $data['posted_info'] = $posted_info;
    // print_r($data);die();
    $this->_insert($data);

}
// function ipn_listener(){
//       header('HTTP/1.1 200 OK'); //ACKWOLEDGES THAT NOTIFICATION IS RECEIVED FROM PAYPAL
//       // STEP 1: read POST data
//       // Reading POSTed data directly from $_POST causes serialization issues with array data in the POST.
//       // Instead, read raw POST data from the input stream.
//       $raw_post_data = file_get_contents('php://input');
//       $raw_post_array = explode('&', $raw_post_data);
//       $myPost = array();
//       foreach ($raw_post_array as $keyval) {
//       $keyval = explode ('=', $keyval);
//       if (count($keyval) == 2)
//           $myPost[$keyval[0]] = urldecode($keyval[1]);
//       }
//       // read the IPN message sent from PayPal and prepend 'cmd=_notify-validate'
//       $req = 'cmd=_notify-validate';
//       if (function_exists('get_magic_quotes_gpc')) {
//       $get_magic_quotes_exists = true;
//       }
//       foreach ($myPost as $key => $value) {
//       if ($get_magic_quotes_exists == true && get_magic_quotes_gpc() == 1) {
//           $value = urlencode(stripslashes($value));
//       } else {
//           $value = urlencode($value);
//       }
//       $req .= "&$key=$value";
//       }

//     //  // Step 2: POST IPN data back to PayPal to validate
//     // //  $ch = curl_init('https://ipnpb.paypal.com/cgi-bin/webscr');
//       $ch = curl_init('https://ipnpb.sandbox.paypal.com/cgi-bin/webscr');
//       curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
//       curl_setopt($ch, CURLOPT_POST, 1);
//       curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
//       curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
//       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
//       curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
//       curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
//       curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
//       // In wamp-like environments that do not come bundled with root authority certificates,
//       // please download 'cacert.pem' from "https://curl.haxx.se/docs/caextract.html" and set
//       // the directory path of the certificate as shown below:
//       // curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cacert.pem');
//       if ( !($res = curl_exec($ch)) ) {
//       // error_log("Got " . curl_error($ch) . " when processing IPN data");
//       curl_close($ch);
//       exit;
//       }
//       curl_close($ch);

//       // inspect IPN validation result and act accordingly
//       if (strcmp ($res, "VERIFIED") == 0) {
//       // The IPN is verified, process it
//         // the url that accepts things that paypal has posted. Use tokens for the url
//           $data['date_created'] = time();
//           $posted_info = '';
//           $this->load->module('site_security');
//           foreach($_POST as $key => $value){
//               if($key == 'custom'){
//                   $customer_session_id = $this->site_security->_decrypt_string($value);
//                   $value = $customer_session_id;
//               }

//               // $posted_info .= "Key of <strong>$key</strong> was posted with a Value of <strong>$value</strong> <br>";
//               $posted_info[$key] = $value;
//             }
//             print_r($posted_info);die();
        
//           // $data['posted_info'] = serialize($posted_info);
//         //   echo $res;die();
//           $this->_insert($data);
//           $max_id = $this->get_max();
//           $this->load->module('store_orders');
//           $this->store_orders->_auto_generate_order($max_id,$customer_session_id);
//       } else if (strcmp ($res, "INVALID") == 0) {
//           $this->load->helper('download');

//         //   echo $res;die();
//         echo "try again";
//          print_r($res);die();
//       // IPN invalid, log for manual investigation
//       }

//     //the url that accepts things that paypal has posted. Use tokens for the url
//     // $data['date_created'] = time();
//     // $posted_info = '';
//     // foreach($_POST as $key => $value){
//     //     $posted_info .= "Key of <strong>$key</strong> was posted with a Value of <strong>$value</strong> <br>";
//     // }
//     // $data['posted_info'] = $posted_info;
//     // $this->_insert($data);

// }

function thankyou(){
    $data['show'] = $this->ipn_listener();
    $this->load->module('store_basket');
    $this->load->module('site_security');
    $shopper_id = null;
    $session_id = $this->session->session_id;
    $query = $this->store_basket->get_where_custom('session_id',$session_id);
    $data['query']= $query->row();
    foreach($query->result() as $row){
        $shopper_id = (string)$row->shopper_id;
    }
    $shopper_id_token = $this->site_security->_create_token($shopper_id);
    $data['shopper_id'] = $shopper_id_token;

    // $this->output->set_header('refresh:2; url='.base_url("downloads/download_book/"));
    $data['view_file'] = 'thankyou';
    $this->load->module('templates');
    $this->templates->public_bootstrap($data);
} 

function cancel(){
    $data['view_file'] = 'cancel';
    $this->load->module('templates');
    $this->templates->public_bootstrap($data);
}

function _is_on_test_mode(){
    return TRUE;//SET THIS TO FALSE IF WE ARE LIVE
}

function _draw_paypal_btn($query){
    $this->load->module('site_settings');
    $this->load->module('site_security');

    foreach($query->result() as $row){
        $session_id = $row->session_id; 
    }

    $is_on_test_mode = $this->_is_on_test_mode();

    if($is_on_test_mode == TRUE){
        $data['form_location'] = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
    }else{
        $data['form_location'] = 'https://www.paypal.com/cgi-bin/webscr';
    }

    $data['on_test_mode'] = $is_on_test_mode;
    $data['cancel_return'] = base_url('paypal/cancel');
    $data['return'] = base_url('paypal/thankyou');
    $data['custom'] = $this->site_security->_encrypt_string($session_id);
    $data['paypal_email'] = $this->site_settings->_get_paypal_email();
    $data['currency_code'] = $this->site_settings->_get_currency_code();
    $data['query'] = $query;
    // print_r($data);die();
    $this->load->view('checkout_btn',$data);
}

function get($order_by) {
$this->load->model('Mdl_paypal');
$query = $this->Mdl_paypal->get($order_by);
return $query;
}

function get_with_limit($limit, $offset, $order_by) {
$this->load->model('Mdl_paypal');
$query = $this->Mdl_paypal->get_with_limit($limit, $offset, $order_by);
return $query;
}

function get_where($id) {
$this->load->model('Mdl_paypal');
$query = $this->Mdl_paypal->get_where($id);
return $query;
}

function get_where_custom($col, $value) {
$this->load->model('Mdl_paypal');
$query = $this->Mdl_paypal->get_where_custom($col, $value);
return $query;
}

function _insert($data) {
$this->load->model('Mdl_paypal');
$this->Mdl_paypal->_insert($data);
}

function _update($id, $data) {
$this->load->model('Mdl_paypal');
$this->Mdl_paypal->_update($id, $data);
}

function _delete($id) {
$this->load->model('Mdl_paypal');
$this->Mdl_paypal->_delete($id);
}

function count_where($column, $value) {
$this->load->model('Mdl_paypal');
$count = $this->Mdl_paypal->count_where($column, $value);
return $count;
}

function get_max() {
$this->load->model('Mdl_paypal');
$max_id = $this->Mdl_paypal->get_max();
return $max_id;
}

function _custom_query($mysql_query) {
$this->load->model('Mdl_paypal');
$query = $this->Mdl_paypal->_custom_query($mysql_query);
return $query;
}

}