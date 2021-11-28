<?php 

// include($libraries.'/stripe-php-7.17.0/init.php');
require_once($libraries.'/stripe-php-7.21.0/init.php');

use \Stripe\Stripe;
use \Stripe\Customer;

class StripePayment{

	private $apiKey;

    private $stripeService;

    public function __construct()
    {
        require_once "config.php";
        $this->apiKey = 'sk_test_qIEJ9lE0imEcn6AYcRrwuGBs00WrOlDTUF';
        $this->stripeService = new \Stripe\Stripe();
        $this->stripeService->setVerifySslCerts(false);
        $this->stripeService->setApiKey($this->apiKey);
    }

  	public function connectStripe()
  	{
  		
  	}

}