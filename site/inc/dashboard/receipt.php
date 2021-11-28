<?php

if (!empty($sessions->token)) {
  
	$curl = curl_init();
    $reference = isset($_GET['reference']) ? $_GET['reference'] : '';
    if(!$reference){
      // die('No reference supplied');
      $fail.='<p>Error cannot verify payment: No reference supplied</p>';
      $ezDb->query("UPDATE `payments` SET `transdate`='null', `status`='0', `status1`='failed' WHERE `token`='$sessions->token' AND `transid1` = '$sessions->transid';");
    
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
      	$ezDb->query("UPDATE `payments` SET `transid`='".$tranx->data->reference."', `transdate`='null' WHERE `token`='$sessions->token' AND `transid1` = '$sessions->transid';");
      
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
          if($sessions->purpose=='investment'){

            $subItem=$ezDb->get_row("SELECT * FROM `investment_subscription` WHERE `token` = '$sessions->token'");
            
            $new_totalpaid = $subItem->total_paid + $sessions->amount;

            $ezDb->query("UPDATE `investment_subscription` SET `total_paid`='$new_totalpaid'  WHERE `token`='$sessions->token';");
            alertUser($userinfo->email,0,'Your payment is successful');
            $investment=$ezDb->get_row("SELECT * FROM `investment_subscription` WHERE `token` = '$sessions->token' ");
            $investment->detail = $ezDb->get_row("SELECT * FROM `investments` WHERE `id` = '$investment->investments_id' ");
            alertDept('Legal',1,'Client has made payment for '.$investment->detail->property.' Investment');
            if($investment->total_paid>=$investment->total_amount){
              $ezDb->query("UPDATE `investment_subscription` SET `paid`= 1  WHERE `token`='$sessions->token';");
              alertUser($userinfo->email,0,'You have completed your payment for '.$investment->detail->property.' Investment');
              alertDept('Legal',1,'Client has completed payment for '.$investment->detail->property.' Investment');
            }
            $smarty->assign('invDetail', $investment);
          }else{

            $subItem=$ezDb->get_row("SELECT * FROM `subscription` WHERE `token` = '$sessions->token'");
            
            $totalsold=$ezDb->get_var("SELECT `totalsold` FROM `projects` WHERE `token` = '$subItem->product_token'");
          
            $new_totalpaid = $subItem->total_paid + $sessions->amount;

            $newTotalSold = $totalsold + $subItem->plot_number;

            $ezDb->query("UPDATE `subscription` SET `total_paid`='$new_totalpaid'  WHERE `token`='$sessions->token';");
        
            $ezDb->query("UPDATE `projects` SET `totalsold`='$newTotalSold'  WHERE `token`='$subItem->product_token';");
            alertUser($userinfo->email,0,'Your payment is successful');
            $subscription=$ezDb->get_row("SELECT * FROM `subscription` WHERE `token` = '$sessions->token' ");
            if($subscription->total_paid>=$subscription->total_amount){
              $ezDb->query("UPDATE `subscription` SET `paid`= 1  WHERE `token`='$sessions->token';");
              alertUser($userinfo->email,0,'You have completed your payment for '.$subscription->product.' Subscription');
              alertDept('Legal',1,'Client has completed  payment for '.$subscription->product.' Subscription');
            }
            $smarty->assign('subDetail', $subscription);
          }
          $referenceid = $tranx->data->reference;
          $ezDb->query("UPDATE `payments` SET `transid`='$referenceid', `transdate`='$dateNow', `status`= 1, `status1`='success' WHERE `token`='$sessions->token' AND `transid1` = '$sessions->transid';");
        
			    	    
          //id	deliveryid	email	billingdetail	charge	dateadded
          //$ezDb->query("UPDATE `payment` SET `transdate`='$datenow', `status`='1', `status1`='success' WHERE `token`='$sessions->token';");
    
          // Send Mail to client
          require_once 'mail_client.php';
          alertUser($userinfo->email,0,'Check your email to see receipt');

          $fail.='<div class="alert alert-success alert-dismissible" role="alert" style="position: absolute; z-index: 1100; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Success!</h3> <p>SUbscription Successfully Made, kindly check your email for payment receipt</p></div>';
      	  
          $payItem=$ezDb->get_row("SELECT * FROM `payments` WHERE `token`='$sessions->regToken'");
          
          //$subscription=$ezDb->get_row("SELECT * FROM `subscription` WHERE `token` = '$sessions->regToken' ");
      
          unset($Site["session"]['ptoken']);
          unset($Site["session"]['token']);
          unset($Site["session"]['regToken']);
          unset($Site["session"]['transid']);
          unset($Site["session"]['name']);
          unset($Site["session"]['amount']);
          unset($Site["session"]['pslug']);
          unset($Site["session"]['discount']);
          $_SESSION=$Site["session"];
          
          $sessions= (object)$Site["session"];

          $smarty->assign('fail',$fail)->assign('tranx',$tranx)->assign('payDetail', $payItem)->assign('subDetail', $subscription);
        }
      }
	}
}