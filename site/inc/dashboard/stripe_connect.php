<?php 

include($libraries.'/stripe-php-7.17.0/init.php');

use \Stripe\Stripe;
use \Stripe\Customer;
use \Stripe\ApiOperations\Create;
use \Stripe\Charge;
use \Stripe\PaymentIntent;

class StripePayment{

    private $apiKey;

    private $stripeService;

    public function __construct()
    {
        require_once "config.php";
        $this->apiKey = 'sk_test_qIEJ9lE0imEcn6AYcRrwuGBs00WrOlDTUF';
        $this->stripeService = new Stripe();
        $this->stripeService->setVerifySslCerts(false);
        $this->stripeService->setApiKey($this->apiKey);
    }

    public function addCustomer($customerDetailsAry)
    {
        
        $customer = new Customer(); 
        $customerDetails = $customer->create($customerDetailsAry);   
        return $customerDetails;
    }

    public function chargeAmountFromCard($cardDetails)
    {
        $customerDetailsAry = array(
            'email' => $cardDetails['email'],
            'source' => $cardDetails['token']
        );
        // error_log(json_encode($cardDetails));
        $customerResult = $this->addCustomer($customerDetailsAry);
        $charge = new Charge();
        $cardDetailsAry = array(
            'customer' => $customerResult->id,
            'amount' => $cardDetails['amount']*100 ,
            'currency' => $cardDetails['currency_code'],
            'description' => $cardDetails['item_name'],
            'metadata' => array(
                'order_id' => $cardDetails['item_number']
            )
        );
        $result = $charge->create($cardDetailsAry);

        return $result->jsonSerialize();
    }

    public function createPayment($cardDetails)
    {
        $paymentintent = new PaymentIntent();
        $result = $paymentintent->create([
          'amount' => $cardDetails['amount']*100 ,
          'currency' => $cardDetails['currency_code'],
          'payment_method_types' => ['card'],
        ]);
        return $result->jsonSerialize();
    }
}
// sk sk_test_qIEJ9lE0imEcn6AYcRrwuGBs00WrOlDTUF --development testing


/**
 * 
 */
/*class CreatePayment{
    public function __construct()
    {
        $this->apiKey = 'sk_test_qIEJ9lE0imEcn6AYcRrwuGBs00WrOlDTUF';
        $this->stripeService = new \Stripe\Stripe();
        $this->stripeService->setVerifySslCerts(false);
        $this->stripeService->setApiKey($this->apiKey);
    }

    public function create($value=array)
    {
        $paymentintent = new PaymentIntent();
        $paymentintent->create([
          'amount' => $value['amount'],
          'currency' => $value['usd'],
          'payment_method_types' => $value['card'],
        ]);
    }
}*/