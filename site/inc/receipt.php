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
          //$push_email = (!empty($userinfo->email)) ? '$userinfo->email' : '$sessions->email';
          $push_email = $sessions->email;
          // please check other things like whether you already gave value for this ref
          // if the email matches the customer who owns the product etc
          // Give value

          /*Update All necessary tables when done*/
          if($sessions->purpose=='investment'){
            
            //Investment update tables
            logTransaction($sessions->token,$sessions->email,$sessions->amount,'investment_subscription','Investment Payment', 1);
				
            //updatelogTransaction($sessions->token,1);
            //send login mail info to client if not verified
            $verified = $ezDb->get_var("SELECT `verified` FROM  `userprofile` WHERE `email` = '$sessions->email'");
            if($verified == 0){
              $confirmkey=date("YmdHis").getToken('5').$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `keys` ORDER BY `id` DESC LIMIT 1;");
              $ezDb->query("INSERT INTO `keys` (`email`, `key`, `expiredon`) VALUES ('".strtolower($sessions->email)."', '$confirmkey', DATE_ADD('$dateNow', INTERVAL 2 DAY))");
              require_once 'mail_signup2.php';
            }

            $subItem=$ezDb->get_row("SELECT * FROM `investment_subscription` WHERE `token` = '$sessions->token'");
            $new_totalpaid = $subItem->total_paid + $sessions->amount;
            
            alertUser($push_email,0,'Your payment is successful');
            $investment=$ezDb->get_row("SELECT * FROM `investment_subscription` WHERE `token` = '$sessions->token' ");
            $ezDb->query("UPDATE `investment_subscription` SET `total_paid`='$new_totalpaid'  WHERE `token`='$sessions->token';");
            $investment->detail = $ezDb->get_row("SELECT * FROM `investments` WHERE `id` = '$investment->investments_id' ");
            //$ezDb->query("UPDATE `commission_payroll` SET `status` = 1 WHERE `investment_token` = '$sessions->token';");
            $investment=$ezDb->get_row("SELECT * FROM `investment_subscription` WHERE `token` = '$sessions->token' ");
           
            if($investment->total_paid>=$investment->total_amount){
              $ezDb->query("UPDATE `investment_subscription` SET `paid`= 1  WHERE `token`='$sessions->token';");
               alertUser($push_email,0,'You have completed your payment for Reference: '.$investment->token.' Investment');
            }
            
            $smarty->assign('invDetail', $investment);
            
            //commission issue
            if ($sessions->commission_ref == true){
              $employee = $sessions->commission_employee;
              $posted_data = $sessions->posted_data;
              $commission_line = getAMGLine($employee->userid);
              $payrollid = getToken(8);
              $settings = getSettings();
              if(!empty($settings->amg_commission)){
                $data = $settings->amg_commission;
                $deductions = $settings->inv_commission;
      
                //commission lines
                $level0_commission =  $data->level0;
                $level1_commission =  $data->level1;
                $level2_commission =  $data->level2;
      
                $total_pay = ($level0_commission/100) * $sessions->amount; 
                $pension = ($deductions->pension/100) * $total_pay;
                $new_bal = $total_pay - $pension; 
                $taxes = ($deductions->taxes/100) * $new_bal; 
                $final_bal = $new_bal - $taxes; 
                $commission_query_1 = "INSERT INTO `commission_payroll` (`payrollid`, `email`, `investment_token`, `month`, `year`, `data`, `commission`, `total_amount`, `total_pay`,`pension`,`tax`, `status`, `manager_review`, `md_review`) VALUES ('$payrollid','$posted_data->ref_email','$sesions->token','$dateM','$dateY','','$level0_commission','$sessions->amount','$total_pay','$pension','$taxes',1,'','')";
                $ezDb->query($commission_query_1);
                  
                if(!empty($commission_line->level1)){
                  $level1 = $commission_line->level1;
                  $total_pay = ($level1_commission/100) * $sessions->amount; 
                  $pension = ($deductions->pension/100) * $total_pay;
                  $new_bal = $total_pay - $pension; 
                  $taxes = ($deductions->taxes/100) * $new_bal; 
                  $final_bal = $new_bal - $taxes; 
                  
                  $commission_query_2 = "INSERT INTO `commission_payroll` (`payrollid`, `email`, `investment_token`, `month`, `year`, `data`, `commission`, `total_amount`, `total_pay`,`pension`,`tax`, `status`, `manager_review`, `md_review`) VALUES ('$payrollid','$level1->email','$sesions->token','$dateM','$dateY','','$level1_commission','$sessions->amount','$total_pay','$pension','$taxes',1,'','')";
                  $ezDb->query($commission_query_2);
                  
                  if(!empty($commission_line->level2)){
      
                    $level2 = $commission_line->level2;
                    $total_pay = ($level2_commission/100) * $sessions->amount; 
                    $pension = ($deductions->pension/100) * $total_pay;
                    $new_bal = $total_pay - $pension; 
                    $taxes = ($deductions->taxes/100) * $new_bal; 
                    $final_bal = $new_bal - $taxes; 
                    
                    $commission_query_3 = "INSERT INTO `commission_payroll` (`payrollid`, `email`, `investment_token`, `month`, `year`, `data`, `commission`, `total_amount`, `total_pay`,`pension`,`tax`, `status`, `manager_review`, `md_review`) VALUES ('$payrollid','$level2->email','$sesions->token','$dateM','$dateY','','$level2_commission','$sessions->amount','$total_pay','$pension','$taxes',1,'','')";
                    $ezDb->query($commission_query_3);
                  
                  }
                }
              }
            }
          }else{

            //Subscription update tables
            logTransaction($sessions->token,$sessions->email,$sessions->amount,$sessions->table_name,'Subscription Payment', 1);
            
            //send login mail info to client if not verified
            $verified = $ezDb->get_var("SELECT `verified` FROM  `userprofile` WHERE `email` = '$sessions->email'");
            if($verified == 0){
              $confirmkey=date("YmdHis").getToken('5').$ezDb->get_var("SELECT IF(`id`=NULL,'1',(`id`+1)) FROM `keys` ORDER BY `id` DESC LIMIT 1;");
              $ezDb->query("INSERT INTO `keys` (`email`, `key`, `expiredon`) VALUES ('".strtolower($sessions->email)."', '$confirmkey', DATE_ADD('$dateNow', INTERVAL 2 DAY))");
              require_once 'mail_signup2.php';
            }

            //Update subscription table
            $subItem=$ezDb->get_row("SELECT * FROM `$sessions->table_name` WHERE `token` = '$sessions->token'");
            $totalsold=$ezDb->get_var("SELECT `totalsold` FROM `projects` WHERE `token` = '$subItem->project_token'");
            $new_totalpaid = $subItem->total_paid + $sessions->amount;
            $newTotalSold = $totalsold + $subItem->plot_number;
            $ezDb->query("UPDATE `$sessions->table_name` SET `total_paid`='$new_totalpaid',`status` = 4  WHERE `token`='$sessions->token'");
            alertUser($push_email,0,'Your payment is successful');
            $ezDb->query("UPDATE `projects` SET `totalsold`='$newTotalSold' WHERE `token` = '$subItem->project_token'");
            
            $subscription=$ezDb->get_row("SELECT * FROM `$sessions->table_name` WHERE `token` = '$sessions->token' ");
            logFinance($sessions->token,$sessions->amount,'cash in bank','paystack','Atobe CC','income','income','sales','property subscription',$dateNow,1);
            
            if($subscription->total_paid>=$subscription->total_amount){
              $ezDb->query("UPDATE `$sessions->table_name` SET `paid`= 1, `status` = 5  WHERE `token`='$sessions->token';");
              alertUser($push_email,0,'You have completed your payment for Reference: '.$subscription->token.' Subscription. You will find your contract of sales in the subscriptions menu');
            }
            $smarty->assign('subDetail', $subscription);

            //commission issue
            if ($sessions->commission_ref == true){
              $employee = $sessions->commission_employee;
              $posted_data = $sessions->posted_data;
              $commission_line = getAMGLine($employee->userid);
              $payrollid = getToken(8);
              $settings = getSettings();
              if(!empty($settings->amg_commission)){
                $data = $settings->amg_commission;
                $deductions = $settings->inv_commission;
      
                //commission lines
                $level0_commission =  $data->level0;
                $level1_commission =  $data->level1;
                $level2_commission =  $data->level2;
      
                $total_pay = ($level0_commission/100) * $sessions->amount; 
                $pension = ($deductions->pension/100) * $total_pay;
                $new_bal = $total_pay - $pension; 
                $taxes = ($deductions->taxes/100) * $new_bal; 
                $final_bal = $new_bal - $taxes; 
                $commission_query_1 = "INSERT INTO `commission_payroll` (`payrollid`, `email`, `subscription_token`, `month`, `year`, `data`, `commission`, `total_amount`, `total_pay`,`pension`,`tax`, `status`, `manager_review`, `md_review`) VALUES ('$payrollid','$posted_data->ref_email','$sesions->token','$dateM','$dateY','','$level0_commission','$sessions->amount','$total_pay','$pension','$taxes',1,'','')";
                $ezDb->query($commission_query_1);
                  
                if(!empty($commission_line->level1)){
                  $level1 = $commission_line->level1;
                  $total_pay = ($level1_commission/100) * $sessions->amount; 
                  $pension = ($deductions->pension/100) * $total_pay;
                  $new_bal = $total_pay - $pension; 
                  $taxes = ($deductions->taxes/100) * $new_bal; 
                  $final_bal = $new_bal - $taxes; 
                  
                  $commission_query_2 = "INSERT INTO `commission_payroll` (`payrollid`, `email`, `subscription_token`, `month`, `year`, `data`, `commission`, `total_amount`, `total_pay`,`pension`,`tax`, `status`, `manager_review`, `md_review`) VALUES ('$payrollid','$level1->email','$sesions->token','$dateM','$dateY','','$level1_commission','$sessions->amount','$total_pay','$pension','$taxes',1,'','')";
                  $ezDb->query($commission_query_2);
                  
                  if(!empty($commission_line->level2)){
      
                    $level2 = $commission_line->level2;
                    $total_pay = ($level2_commission/100) * $sessions->amount; 
                    $pension = ($deductions->pension/100) * $total_pay;
                    $new_bal = $total_pay - $pension; 
                    $taxes = ($deductions->taxes/100) * $new_bal; 
                    $final_bal = $new_bal - $taxes; 
                    
                    $commission_query_3 = "INSERT INTO `commission_payroll` (`payrollid`, `email`, `subscription_token`, `month`, `year`, `data`, `commission`, `total_amount`, `total_pay`,`pension`,`tax`, `status`, `manager_review`, `md_review`) VALUES ('$payrollid','$level2->email','$sesions->token','$dateM','$dateY','','$level2_commission','$sessions->amount','$total_pay','$pension','$taxes',1,'','')";
                    $ezDb->query($commission_query_3);
                  
                  }
                }
              }
            }
          }

          $referenceid = $tranx->data->reference;
          $ezDb->query("UPDATE `payments` SET `transid`='$referenceid', `transdate`='$dateNow', `status`= 1, `status1`='success' WHERE `token`='$sessions->token' AND `transid1` = '$sessions->transid';");
          
          //id	deliveryid	email	billingdetail	charge	dateadded
          //$ezDb->query("UPDATE `payment` SET `transdate`='$datenow', `status`='1', `status1`='success' WHERE `token`='$sessions->token';");
    
          // Send Mail to client
          require_once 'mail_client.php';

          $fail.='<div class="alert alert-success alert-dismissible" role="alert" style="position: absolute; z-index: 1100; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Success!</h3> <p>Payment Successfully Made, kindly check your email for payment receipt</p></div>';
      	  

          $payItem=$ezDb->get_row("SELECT * FROM `payments` WHERE `token`='$sessions->token' AND `transid1` = '$sessions->transid';");
          //print_r($payItem);
          
          unset($Site["session"]['ptoken']);
          unset($Site["session"]['token']);
          unset($Site["session"]['regToken']);
          unset($Site["session"]['transid']);
          unset($Site["session"]['name']);
          unset($Site["session"]['amount']);
          unset($Site["session"]['pslug']);
          unset($Site["session"]['discount']);
					unset($Site["session"]['commission_ref']);
					unset($Site["session"]['table_name']);
					unset($Site["session"]['commission_employee']);
					unset($Site["session"]['posted_data']);
          $_SESSION=$Site["session"];
          
          $sessions= (object)$Site["session"];

          $smarty->assign('fail',$fail)->assign('tranx',$tranx)->assign('payDetail', $payItem);
        }

        if($errr>0){
          //$fail.='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 1100; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error Messages</h3> '.$fail.'</div>';
        }
      }
	}
}