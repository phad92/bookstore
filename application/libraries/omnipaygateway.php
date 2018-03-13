<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use Omnipay\Omnipay;
use Omnipay\Common\CreditCard;

class Omnipaygateway extends CreditCard{

    protected $gateway = null;

    public function __construct($set_gateway='Paypal_pro',$test_mode = true){
        $this->gateway = Omnipay::create($set_gateway);
        $this->gateway->setUsername('fadl_1992-facilitator_api1.yahoo.com');
        $this->gateway->setPassword('SZSBEG832L8RXYLF');
        $this->gateway->setSignature('A3HZzQjxzoBA2-AmrrMtKbeXSlyhAD9BJUigV7FwAPwht3Si0FP3Hyy9');
        $this->gateway->setTestMode($test_mode);
    }

    public function sendPurchase($cartInput,$valTrans){
        $card = new CreditCard($cartInput);
        $payArray = array(
            'amount' => $valTrans['amount'],
            'transactionId' => $valTrans['transactionId'],
            'description' => $valTrans['description'],
            'currency' => $valTrans['currency'],
            'clientIp' => $valTrans['clientIp'],
            'returnUrl' => $valTrans['returnUrl'],
            'card' => $card
        );

        $response = $this->gateway->purchase($payArray)->send();
        if($response->isSuccessful()){
            $paypalResponse = $response->getData();
        }elseif ($response->isRedirect()) {
            $paypalResponse = $response->getRedirectData();
        }else{
            $paypalResponse = $response->getMessage();
        }
        return $paypalResponse;
    }
}