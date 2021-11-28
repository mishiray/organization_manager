<?php 
echo "Success";

if (!(!isset($_POST["txn_id"]) && !isset($_POST["txn_type"]))) {
	// Handle the PayPal response.

	// Create a connection to the database.
	$db = new mysqli($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['name']);

	// Assign posted variables to local data array.
	$data = [
	    'item_name' => $_POST['item_name'],
	    'item_number' => $_POST['item_number'],
	    'payment_status' => $_POST['payment_status'],
	    'payment_amount' => $_POST['mc_gross'],
	    'payment_currency' => $_POST['mc_currency'],
	    'txn_id' => $_POST['txn_id'],
	    'receiver_email' => $_POST['receiver_email'],
	    'payer_email' => $_POST['payer_email'],
	    'custom' => $_POST['custom'],
	];

	// We need to verify the transaction comes from PayPal and check we've not
	// already processed the transaction before adding the payment to our
	// database.
	if (verifyTransaction($_POST) && checkTxnid($data['txn_id'])) {
	    if (addPayment($data) !== false) {
	        // Payment successfully added.
	    }
	}
}