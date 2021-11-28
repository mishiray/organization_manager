<?php 
// For test payments we want to enable the sandbox mode. If you want to put live
// payments through then this setting needs changing to `false`.
$enableSandbox = true;

// PayPal settings. Change these to your account details and the relevant URLs
// for your site.
$paypalConfig = [
    'email' => 'amajoyeogbe@hoffehteimtechnologies.com',
    'return_url' => $siteProtocol."$domainName/dashboard/paypal_success",
    'cancel_url' =>  $siteProtocol."$domainName/dashboard/paypal_cancelled",
    'notify_url' =>  $siteProtocol."$domainName/dashboard/enroll"
];

$paypalUrl = $enableSandbox ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';

// Product being purchased.
$itemName = $itemName;
$itemAmount = $amount;

$data = [];
foreach ($_POST as $key => $value) {
    $data[$key] = stripslashes($value);
}

// Set the PayPal account.
$data['business'] = $paypalConfig['email'];

// Set the PayPal return addresses.
$data['return'] = stripslashes($paypalConfig['return_url']);
$data['cancel_return'] = stripslashes($paypalConfig['cancel_url']);
$data['notify_url'] = stripslashes($paypalConfig['notify_url']);

// Set the details about the product being purchased, including the amount
// and currency so that these aren't overridden by the form data.
$data['item_name'] = $itemName;
$data['amount'] = $itemAmount;
$data['currency_code'] = 'USD';

// Add any custom fields for the query string.
//$data['custom'] = USERID;

// Build the query string from the data.
$queryString = http_build_query($data);

// Redirect to paypal IPN
header('location:' . $paypalUrl . '?' . $queryString);
exit();