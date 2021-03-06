<?php

if (!empty($sessions->regToken)) {
  
	$curl = curl_init();
    $reference = isset($_GET['reference']) ? $_GET['reference'] : '';
    if(!$reference){
      // die('No reference supplied');
      $fail.='<p>Error cannot verify payment: No reference supplied</p>';
      $ezDb->query("UPDATE `payments` SET `transdate`='null', `status`='0', `status1`='failed' WHERE `token`='$sessions->regToken';");
    
    }else{
      curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . rawurlencode($reference),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
          "accept: application/json",
          "authorization: Bearer ".$paymentConfig->paystack["test_sk"],//replace payment gateway key 
          "cache-control: no-cache"
        ],
      ));

      $response = curl_exec($curl);
      $err = curl_error($curl);

      if($err){
        $errr++;
      	// there was an error contacting the Paystack API
        //die('Curl returned error: ' .$err );
        $fail.='<p>Curl returned error: '.$err.'</p>';
      	$ezDb->query("UPDATE `payments` SET `transid1`='".$tranx->data->reference."', `transid`='".$tranx->data->reference."', `transdate`='null' WHERE `token`='$sessions->regToken';");
        }else{

        $tranx = json_decode($response);

        if(!$tranx->status){ 
          $errr++;
          // there was an error from the API
          // die('API returned error: ' .$tranx->message );
          $fail.='<p>API returned error: '.$tranx->message.'</p>';
      	  //$ezDb->query("UPDATE `pay_hits` SET `data_received`='".(json_encode($tranx))."', `status`='error_api_return' WHERE `token`='$sessions->cartKey' AND `source`='ps';");
        }

        if('success' == $tranx->data->status){
          
          // please check other things like whether you already gave value for this ref
          // if the email matches the customer who owns the product etc
          // Give value

          /*Update All necessary tables when done*/
          $subItem=$ezDb->get_row("SELECT * FROM `subscription` WHERE `token` = '$sessions->regToken'");
          $totalsold=$ezDb->get_var("SELECT `totalsold` FROM `projects` WHERE `name` = '$sessions->product'");

          $newTotalSold = $totalsold + $subItem->plot_number;

          $ezDb->query("UPDATE `projects` SET `totalsold`='$newTotalSold'  WHERE `name`='$sessions->product';");
        
          $ezDb->query("UPDATE `payments` SET `transid1`='".$tranx->data->reference."', `transid`='".$tranx->data->reference."', `transdate`='$dateNow', `status`= 1, `status1`='success' WHERE `token`='$sessions->regToken';");
        
			    	    
          //id	deliveryid	email	billingdetail	charge	dateadded
          //$ezDb->query("UPDATE `payment` SET `transdate`='$datenow', `status`='1', `status1`='success' WHERE `token`='$sessions->token';");
    
          // Send Mail to client
          require_once 'mail_client.php';

          $fail.='<div class="alert alert-success alert-dismissible" role="alert" style="position: absolute; z-index: 1100; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Success!</h3> <p>SUbscription Successfully Made, kindly check your email for payment receipt</p></div>';
      	  //$ezDb->query("UPDATE `pay_hits` SET `data_received`='".(json_encode($tranx))."', `status`='".$tranx->data->status."' WHERE `token`='$sessions->cartKey' AND `source`='ps';");

      	  //if( !empty($Site["session"]["User"]["client"]["Token"]) and sessionExists($Site["session"]["User"]["client"]["Token"])==true  and !empty($Site["session"]['loggedInUser']) ){
      	  //	redirect( $Site['siteProtocol'].$Site['domainName']."/dashboard/receipt" );
          //  return;
      	  //}

          $payItem=$ezDb->get_row("SELECT * FROM `payments` WHERE `token`='$sessions->regToken'");
          $subItem=$ezDb->get_row("SELECT * FROM `subscription` WHERE `token` = '$sessions->regToken' ");
		
          $subscription=$ezDb->get_row("SELECT * FROM `subscription` WHERE `token` = '$sessions->regToken' ");
      
          unset($Site["session"]['ptoken']);
          unset($Site["session"]['regToken']);
          unset($Site["session"]['name']);
          unset($Site["session"]['amount']);
          unset($Site["session"]['pslug']);
          unset($Site["session"]['discount']);
          unset($Site["session"]['token']);
          $_SESSION=$Site["session"];
          
          $sessions= (object)$Site["session"];

          $smarty->assign('fail',$fail)->assign('tranx',$tranx)->assign('payDetail', $payItem)->assign('subDetail', $subscription);
        }
        if($errr>0){
          $fail.='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 1100; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error Messages</h3> '.$fail.'</div>';
        }
      }
	}
}