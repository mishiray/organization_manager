<?php
// redirect("home");
$settings=getSettings();
$userinfo=$Site['session']['User']['userinfo'];
if (!in_array($userinfo->userrole, array('level0','level1')) and $userinfo->active==true) {
	redirect("home");
}

$fail="";
$err=0;
$setting=new stdClass();
if ( in_array($sitePage, array("settings")) && ($requestMethod == 'POST') && isset($Site["post"]['subsetting']) && $posts->subsetting=='subsetting') {
	// redirect("home");
	if( empty(trim($posts->renewal)) or !in_array($posts->renewal, array("weekly", "monthly", "quarterly", "yearly"))):
		$err++;
		$fail.='<p class="border bg-white border-danger px-2 py-1 rounded">Select a valid renewal period!</p>';
	endif;
	if( empty(trim($posts->amount)) or is_nan($posts->amount) ):
		$err++;
		$fail.='<p class="border bg-white border-danger px-2 py-1 rounded">Enter a avalid amount!</p>';
	endif;
	if( is_nan($posts->discount) or $posts->discount<0 or $posts->discount>100 ):
		$err++;
		$fail.='<p class="border bg-white border-danger px-2 py-1 rounded">Enter a valid package discount please!</p>';
	endif;
	if( $err==0 ):
		$setting->renewal=$posts->renewal;
		$setting->discount=$posts->discount;
		$setting->amount=$posts->amount;
		$setting->updatedBy=$userinfo->email;
		$setting->dateUpdated=$dateNow;

		$setting=json_encode($setting);
		if (empty($settings) or empty((array)$settings)) {
			$ezDb->query("INSERT INTO `settings` (`subscription`, `updated`) VALUES ('$setting', '$dateNow')");
		}else{
			$ezDb->query("UPDATE `settings` SET `subscription`='$setting'");
		}
		$settings->subscription=$setting;
		$settings->updated=$dateNow;
		$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p class="border bg-white border-success px-2 py-1 rounded">Subscription settings was successfully updated.</p></div>';
	else:
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	endif;
}

if ( in_array($sitePage, array("settings")) && ($requestMethod == 'POST') && isset($Site["post"]['jersey']) && $posts->jersey=='jersey') {
	redirect("settings");
	if( empty(trim($posts->renewal)) or !in_array($posts->renewal, array("weekly", "monthly", "quarterly", "yearly", "once"))):
		$err++;
		$fail1.='<p class="border bg-white border-danger px-2 py-1 rounded">Select a valid renewal period!</p>';
	endif;
	if( empty(trim($posts->amount)) or is_nan($posts->amount) ):
		$err++;
		$fail1.='<p class="border bg-white border-danger px-2 py-1 rounded">Enter a avalid amount!</p>';
	endif;
	if( is_nan($posts->discount) or $posts->discount<0 or $posts->discount>100 ):
		$err++;
		$fail1.='<p class="border bg-white border-danger px-2 py-1 rounded">Enter a valid package discount please!</p>';
	endif;
	if( $err==0 ):
		$setting->renewal=$posts->renewal;
		$setting->discount=$posts->discount;
		$setting->amount=$posts->amount;
		$setting->updatedBy=$userinfo->email;
		$setting->dateUpdated=$dateNow;

		$setting=json_encode($setting);
		if (empty($settings) or empty((array)$settings)) {
			$ezDb->query("INSERT INTO `settings` (`jersey`, `updated`) VALUES ('$setting', '$dateNow')");
		}else{
			$ezDb->query("UPDATE `settings` SET `jersey`='$setting'");
		}
		$settings->jersey=$setting;
		$settings->updated=$dateNow;
		$fail1='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p class="border bg-white border-success px-2 py-1 rounded">Jersey settings was successfully updated.</p></div>';
	else:
		$fail1='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail1.'</div>';
	endif;
	$smarty->assign("fail1", $fail1);
}

if ( in_array($sitePage, array("settings")) && ($requestMethod == 'POST') && isset($Site["post"]['working_hour']) && $posts->working_hour=='working_hour') {
	$fail2='';
	$err=0;
	
	if( empty(trim($posts->open_hour)) /*or strtotime($posts->open_hour)*/):
		$err++;
		$fail2.='<p class="border bg-white border-danger px-2 py-1 rounded">Enter a valid time for  Opening Hour!</p>';
	endif;
	if( empty(trim($posts->close_hour)) /*or strtotime($posts->close_hour)*/):
		$err++;
		$fail2.='<p class="border bg-white border-danger px-2 py-1 rounded">Enter a valid time for  Closing Hour!</p>';
	endif;
	if( $err==0 ):
	    $setting->open_hour_string=$posts->open_hour;
	    $setting->open_hour=strtotime($posts->open_hour);
	    $setting->close_hour_string=$posts->close_hour;
	    $setting->close_hour=strtotime($posts->close_hour);
		$setting->updatedBy=$userinfo->email;
		$setting->dateUpdated=$dateNow;

		$setting=json_encode($setting);
		if (empty($settings->working_hours)) {
			$ezDb->query("UPDATE `settings` SET `working_hours`='$setting'");
		}else{
			$ezDb->query("UPDATE `settings` SET `working_hours`='$setting'");
		}
		$settings->working_hours=$setting;
		$settings->updated=$dateNow;
		$fail2='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p class="border bg-white border-success px-2 py-1 rounded">Working hour was successfully updated.</p></div>';
		//redirect("settings");
	
	else:
		$fail2='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail2.'</div>';
	endif;
	$smarty->assign("fail2", $fail2);
}

if ( in_array($sitePage, array("settings")) && ($requestMethod == 'POST') && isset($Site["post"]['subscription_change']) && $posts->subscription_change=='subscription_change') {
	$fail3='';
	$err=0;
	
	if( empty(trim($posts->days)) /*or strtotime($posts->open_hour)*/):
		$err++;
		$fail3.='<p class="border bg-white border-danger px-2 py-1 rounded">Enter number of days for subscription change limit!</p>';
	endif;
	if( $err==0 ):
	    $setting->days=$posts->days;
		$setting->updatedBy=$userinfo->email;
		$setting->dateUpdated=$dateNow;

		$setting=json_encode($setting);
		if (empty($settings->subscription_change)) {
			$ezDb->query("UPDATE `settings` SET `subscription_change`='$setting'");
			//$ezDb->query("INSERT INTO `settings` (`subscription_change`, `updated`) VALUES ('$setting', '$dateNow')");
		}else{
			$ezDb->query("UPDATE `settings` SET `subscription_change`='$setting'");
		}
		$settings->subscription_change=$setting;
		$settings->updated=$dateNow;
		$fail3='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p class="border bg-white border-success px-2 py-1 rounded">Subscription Change Limit was successfully updated.</p></div>';
		//redirect("settings");
	
	else:
		$fail3='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail2.'</div>';
	endif;
	$smarty->assign("fail3", $fail3);
}
if ( in_array($sitePage, array("settings")) && ($requestMethod == 'POST') && isset($Site["post"]['inv_commission']) && $posts->inv_commission=='inv_commission') {
	$fail5='';
	$err=0;
	
	if( empty(trim($posts->pension))):
		$err++;
		$fail5.='<p class="border bg-white border-danger px-2 py-1 rounded">Enter pension for commission deduction</p>';
	endif;
	if( empty(trim($posts->taxes))):
		$err++;
		$fail5.='<p class="border bg-white border-danger px-2 py-1 rounded">Enter tax amount for commission deduction</p>';
	endif;
	if( $err==0 ):
	    $setting->taxes=$posts->taxes;
	    $setting->pension=$posts->pension;
		$setting->updatedBy=$userinfo->email;
		$setting->dateUpdated=$dateNow;

		$setting=json_encode($setting);
		if (empty($settings->inv_commission)) {
			$ezDb->query("UPDATE `settings` SET `inv_commission`='$setting'");
		}else{
			$ezDb->query("UPDATE `settings` SET `inv_commission`='$setting'");
		}
		$settings->inv_commission=$setting;
		$settings->updated=$dateNow;
		$fail5='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p class="border bg-white border-success px-2 py-1 rounded">Commission deductions was successfully updated.</p></div>';
		//redirect("settings");
	
	else:
		$fail5='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail5.'</div>';
	endif;
	$smarty->assign("fail5", $fail5);
}

if ( in_array($sitePage, array("settings")) && ($requestMethod == 'POST') && isset($Site["post"]['amg_commission']) && $posts->amg_commission=='amg_commission') {
	$fail6='';
	$err=0;
	
	if( empty(trim($posts->level0))):
		$err++;
		$fail6.='<p class="border bg-white border-danger px-2 py-1 rounded">Enter commission % for marketer</p>';
	endif;
	if( empty(trim($posts->level1))):
		$err++;
		$fail6.='<p class="border bg-white border-danger px-2 py-1 rounded">Enter commission % for level1 (immediate referee)</p>';
	endif;
	if( empty(trim($posts->level2))):
		$err++;
		$fail6.='<p class="border bg-white border-danger px-2 py-1 rounded">Enter commission % for level2 </p>';
	endif;
	if( $err==0 ):
	    $setting->level0=$posts->level0;
	    $setting->level1=$posts->level1;
	    $setting->level2=$posts->level2;
		$setting->updatedBy=$userinfo->email;
		$setting->dateUpdated=$dateNow;

		$setting=json_encode($setting);
		if (empty($settings->amg_commission)) {
			$ezDb->query("UPDATE `settings` SET `amg_commission`='$setting'");
		}else{
			$ezDb->query("UPDATE `settings` SET `amg_commission`='$setting'");
		}
		$settings->amg_commission=$setting;
		$settings->updated=$dateNow;
		$fail6='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p class="border bg-white border-success px-2 py-1 rounded">AMG Commission was successfully updated.</p></div>';
		//redirect("settings");
	
	else:
		$fail5='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail5.'</div>';
	endif;
	$smarty->assign("fail6", $fail6);
}
if ( in_array($sitePage, array("settings")) && ($requestMethod == 'POST') && isset($Site["post"]['commissions']) && $posts->commissions=='commissions') {
	$fail4='';
	$err=0;
	
	if( empty(trim($posts->period))):
		$err++;
		$fail4.='<p class="border bg-white border-danger px-2 py-1 rounded">Enter period of employee employment for commission</p>';
	endif;
	if( empty(trim($posts->percent_comm))):
		$err++;
		$fail4.='<p class="border bg-white border-danger px-2 py-1 rounded">Enter percentage for initial commission</p>';
	endif;
	if( empty(trim($posts->percent_comm_2))):
		$err++;
		$fail4.='<p class="border bg-white border-danger px-2 py-1 rounded">Enter percentage for secondary commission</p>';
		$posts->percent_comm_2 = $posts->percent_comm;
	endif;
	if( empty(trim($posts->sqm_comm))):
		$err++;
		$fail4.='<p class="border bg-white border-danger px-2 py-1 rounded">Enter percentage for secondary commission</p>';
		$posts->percent_comm_2 = $posts->percent_comm;
	endif;
	if( $err==0 ):
	    $setting->employee_period=$posts->period;
	    $setting->initial=$posts->percent_comm;
	    $setting->secondary=$posts->percent_comm_2;
	    $setting->commission_sqm=$posts->sqm_comm;
	    $setting->manager=$posts->manager_comm;
		$setting->updatedBy=$userinfo->email;
		$setting->dateUpdated=$dateNow;

		$setting=json_encode($setting);
		if (empty($settings->commission)) {
			$ezDb->query("UPDATE `settings` SET `commission`='$setting'");
			//$ezDb->query("INSERT INTO `settings` (`subscription_change`, `updated`) VALUES ('$setting', '$dateNow')");
		}else{
			$ezDb->query("UPDATE `settings` SET `commission`='$setting'");
		}
		$settings->commission=$setting;
		$settings->updated=$dateNow;
		$fail2='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p class="border bg-white border-success px-2 py-1 rounded">Commissions bas been successfully updated.</p></div>';
		//redirect("settings");
	
	else:
		$fail2='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail2.'</div>';
	endif;
	$smarty->assign("fail4", $fail4);
}

if ( in_array($sitePage, array("settings")) && ($requestMethod == 'POST') && isset($Site["post"]['parkspace']) && $posts->parkspace=='parkspace') {
	redirect("settings");
	$fail3='';
	$err=0;
	
	if( empty(trim($posts->total)) or is_nan($posts->total)/*or strtotime($posts->open_hour)*/):
		$err++;
		$fail3.='<p class="border bg-white border-danger px-2 py-1 rounded">Enter a valid total parking space!</p>';
	endif;
	if( $err==0 ):
	    $setting->total=$posts->total;
		$setting->updatedBy=$userinfo->email;
		$setting->dateUpdated=$dateNow;

		if (empty($settings)) {
	    	$setting->used=array();
			$setting=json_encode($setting);
			$ezDb->query("INSERT INTO `settings` (`parkspace`, `updated`) VALUES ('$setting', '$dateNow')");
		}else{
	    	$setting->used=((!empty($setting->parkspace->used) and count($setting->parkspace->used)>0)? $setting->parkspace->used:array());
			$setting=json_encode($setting);
			$ezDb->query("UPDATE `settings` SET `parkspace`='$setting'");
		}
		$settings->parkspace=$setting;
		$settings->updated=$dateNow;
		$fail3='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p class="border bg-white border-success px-2 py-1 rounded">HFP parkinh space was successfully updated.</p></div>';
	else:
		$fail3='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail3.'</div>';
	endif;
	$smarty->assign("fail3", $fail3);
}

if ( in_array($sitePage, array("settings")) && ($requestMethod == 'POST') && isset($Site["post"]['codegen']) && $posts->codegen=='codegen') {
	redirect("settings");
	$fail1='';
	$err=0;
	if( empty(trim($posts->status)) or !in_array($posts->status, array("1", "0"))):
		$err++;
		$fail1.='<p class="border bg-white border-danger px-2 py-1 rounded">Select a valid status!</p>';
	endif;
	if( $err==0 ):
		$setting->status=$posts->status;
		$setting->updatedBy=$userinfo->email;
		$setting->dateUpdated=$dateNow;

		$setting=json_encode($setting);
		if (empty($settings)) {
			$ezDb->query("INSERT INTO `settings` (`codegen`, `updated`) VALUES ('$setting', '$dateNow')");
		}else{
			$ezDb->query("UPDATE `settings` SET `codegen`='$setting'");
		}
		$settings->codegen=$setting;
		$settings->updated=$dateNow;
		$fail1='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p class="border bg-white border-success px-2 py-1 rounded">Auto code generation status was successfully updated.</p></div>';
	else:
		$fail1='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail1.'</div>';
	endif;
	$smarty->assign("fail1", $fail1);
}

// $settings=getSettings();
$settings=getSettings();
$smarty->assign("settings", $settings);