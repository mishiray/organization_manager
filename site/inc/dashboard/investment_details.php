<?php

$whereClause="";

$investment_details=$ezDb->get_results("SELECT * from `investment_subscription` WHERE `email` = '$userinfo->email' ORDER BY `reg_id` DESC");
if(!empty($investment_details) and is_array($investment_details)):
	foreach ($investment_details as $value):
		$value->invest=$ezDb->get_row("SELECT * FROM `investments` WHERE `id`='$value->investments_id'");
		if($value->invest->percentage>=30){
			$end_date = date('Y-m-d', strtotime("+12 months", strtotime($value->reg_date)));
			$value->end_date = date('Y-m-d', strtotime("-1 day", strtotime($end_date)));
		}else{
			$end_date = date('Y-m-d', strtotime("+6 months", strtotime($value->reg_date)));
			$value->end_date = date('Y-m-d', strtotime("-1 day", strtotime($end_date)));
		}
		$value->isDone = ($value->paid==1) ? (strtotime($value->end_date)<strtotime(date('Y-m-d'))) ? 'YES' : 'NO' : "Not Paid";
		$value->payment=$ezDb->get_row("SELECT `amount`, SUM(amount) AS `amt` FROM `payments` WHERE `token`='$value->token'");
	endforeach;
endif;
	
$fail="";
$err=0;
if(in_array($sitePage, array("investment_details")) && ($requestMethod == 'POST') && !empty($posts->triggers) && $posts->triggers=='invest_end_options'){
	if( empty($posts->invest_token)):
		$err++;
		$fail.='<p>Investment Not Found</p>';
	endif;
	if( empty($posts->investoptions)):
		$err++;
		$fail.='<p>Please Choose an Option</p>';
	endif;
	if($err == 0){
		$option = $posts->investoptions;
		$mail_message = "";
		$inv=$ezDb->get_row("SELECT * from `investment_subscription` WHERE `token` = '$posts->invest_token'");
		$inv->invest = $ezDb->get_row("SELECT * FROM `investments` WHERE `id`='$inv->investments_id'");
		$client_name = $inv->surname.' '.$inv->firstname;
		$client_email = $inv->email;
		$balance = 0;
		if(!empty($inv)){
			if($option == 'cashout'){
				if($ezDb->query("UPDATE `investment_subscription` SET `status` = 1 WHERE `token` = '$inv->token'")){
					$fail='<div class="alert alert-success alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p>Your cashout checque will be emailed to you</p></div>';
					$mail_message = 'Client has decided to cash out investment on the property '. $inv->product.'';
					alertUser($userinfo->email,0,"Your cashout cheque will be emailed to you");
					alertAccountingManager(0,"Investment Client has decided to cash out investment on the property. Reference:'. $inv->token");
				}
			}elseif($option == 'reinvest'){
					if ($posts->new_invest_amount > $inv->invest->max_principal){
						$err++;
						$fail.='<p>ReInvestment Value More than Max Value</p>';
					}
					if ($posts->new_invest_amount > $inv->maturity){
						$err++;
						$fail.='<p>Re Investment Value More than Holding amount</p>';
					}
					
					if($err == 0){
						$balance = $inv->maturity - $posts->new_invest_amount;
						$token = getToken(8);
						$query = "INSERT INTO `investment_subscription`(
							`token`,
							`investments_id`,
							`product`,
							`img_filename`, 
							`title`,
							`surname`, 
							`middlename`, 
							`firstname`, 
							`sex`, 
							`marital_status`, 
							`spouse_surname`, 
							`spouse_firstname`, 
							`mobile`, 
							`phone`, 
							`email`,
							`address`, 
							`city`, 
							`lga`, 
							`state`, 
							`country`, 
							`dob`, 
							`id_mode`, 
							`id_mode_others`, 
							`id_number`, 
							`nationality`, 
							`state_of_origin`, 
							`occupation`, 
							`emp_name`, 
							`emp_address`,
							`nok_surname`,
							`nok_middlename`, 
							`nok_firstname`, 
							`nok_phone`, 
							`nok_address`, 
							`ref_fullname`, 
							`ref_phone`, 
							`ref_email`,
							`payment_type`,
							`percentage`,
							`category`,
							`principal`,
							`roi`,
							`maturity`,
							`total_amount`,
							`total_paid`,
							`paid`
							) VALUES (
							'$token',
							'$inv->investments_id',
							'$inv->product',
							'$inv->img_filename',
							'$inv->title',
							'$inv->surname',
							'$inv->middlename',
							'$inv->firstname',
							'$inv->sex',
							'$inv->marital_status',
							'$inv->spouse_surname',
							'$inv->spouse_firstname',
							'$inv->mobile',
							'$inv->phone',
							'$inv->email',
							'$inv->address',
							'$inv->city',
							'$inv->lga',
							'$inv->state',
							'$inv->country',
							'$inv->dob',
							'$inv->id_mode',
							'$inv->id_mode_others',
							'$inv->id_number',
							'$inv->nationality',
							'$inv->state_of_origin',
							'$inv->occupation',
							'$inv->emp_name',
							'$inv->emp_address',
							'$inv->nok_surname',
							'$inv->nok_middlename',
							'$inv->nok_firstname',
							'$inv->nok_phone',
							'$inv->nok_address',
							'$inv->ref_fullname',
							'$inv->ref_phone',
							'$inv->ref_email',
							're-investment',
							'$inv->percentage',
							'$inv->category',
							'$posts->new_invest_amount',
							'$posts->roi',
							'$posts->maturity',
							'$posts->new_invest_amount',
							'$posts->new_invest_amount',
							1);";
						
						if($ezDb->query($query)):	
							$ezDb->query("UPDATE `investment_subscription` SET `status` = 2 WHERE `token` = '$inv->token'");
							$transid= getToken(8);

							$queryy = "INSERT INTO `payments` (`token`,`transid1`,`amount`, `transdate`,`gateway`, `paidby`, `status`,`status1`, `purpose`) VALUES ('$token','$transid','$posts->new_invest_amount','$dateNow','admin','$userinfo->email',1,'success','investment');";
							echo $queryy;
							$ezDb->query($queryy);
							$fail='<div class="alert alert-success alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p>You have successfully reinvested to this property</p></div>';
							$mail_message = 'Client has re-invested on the property '. $inv->product.'';
							alertUser($userinfo->email,0,"You have successfully re-invested to this property");
							alertAccountingManager(0,"Investment Client has re-invested on the property. Reference:'. $inv->token");
							//echo $balance;
						else:
							$fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Error occured while trying to submit form. kindly re-invest</p></div>';
						endif;
					}else{
						$fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 99999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error Messages</h3> '.$fail.'</div>';
					}
			}elseif($option == 'takeland'){
				if($ezDb->query("UPDATE `investment_subscription` SET `status` = 3 WHERE `token` = '$inv->token'")){
					$fail='<div class="alert alert-success alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p>Congratulations, please contact admin to follow up with your newly acquired property</p></div>';
					$mail_message = 'Client has decided to claim the property '. $inv->product.'';
					alertUser($userinfo->email,0,"Congratulations, please contact admin to follow up with your newly acquired property");
					alertAccountingManager(0,"Investment Client has decided to claim property. Reference:'. $inv->token");
				}
			}
			require_once "mail_admin_invest.php";
		}else{
				$fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p>Investment data not found</p></div>';
		}
	}else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 99999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error Messages</h3> '.$fail.'</div>';
	}
}

$investment_details=$ezDb->get_results("SELECT * from `investment_subscription` WHERE `email` = '$userinfo->email' ORDER BY `reg_id` DESC");
if(!empty($investment_details) and is_array($investment_details)):
	foreach ($investment_details as $value):
		$value->invest=$ezDb->get_row("SELECT * FROM `investments` WHERE `id`='$value->investments_id'");
		$duration = "+".$value->duration ."months";

		$end_date = date('Y-m-d', strtotime($duration, strtotime($value->reg_date)));
		$value->end_date = date('Y-m-d', strtotime("-1 day", strtotime($end_date)));
		

		$value->isDone = ($value->paid==1) ? (strtotime($value->end_date)<strtotime(date('Y-m-d'))) ? 'YES' : 'NO' : "Not Paid";
		$value->payment=$ezDb->get_row("SELECT `amount`, SUM(amount) AS `amt` FROM `payments` WHERE `token`='$value->token'");
	endforeach;
endif;

$smarty->assign("investment_details", $investment_details);