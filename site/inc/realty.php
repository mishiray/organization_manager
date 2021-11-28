<?php 

$fail="";
$err=0;

if(!empty($posts->submit_request) and $posts->submit_request=='submit-request' and $sitePage=='realty'):
	if( empty($posts->date) or !validateDate($posts->date)):
		$fail.='<p>Invalid Date: selected is not entered or not valid</p>';
		$err++;
	endif;
	if( empty($posts->phone) or !checkPhone($posts->phone)):
		$fail.='<p>Invalid Phone Phone: not a valid  phone number</p>';
		$err++;
	endif;
	if( empty($posts->names) ):
		$fail.='<p>Invalid Name: Full name is not entered</p>';
		$err++;
	endif;
	if( empty($posts->email) ):
		$fail.='<p>Invalid Email: Email is not entered</p>';
		$err++;
	endif;
	if( empty($posts->location) ):
		$fail.='<p>Invalid Choice Location: Choice location is not entered</p>';
		$err++;
	endif;
	if( empty($posts->description)):
		$fail.='<p>Invalid Description: description is not entered</p>';
		$err++;
	endif;
	if( empty($posts->budget) or !is_numeric($posts->budget)):
		$fail.='<p>Invalid Budget: budget is not entered or not a number</p>';
		$err++;
	endif;
	if( empty($posts->timeframe) or !is_numeric($posts->timeframe)):
		$fail.='<p>Invalid Time Frame: time frame is not entered</p>';
		$err++;
	endif;
	if( empty($posts->remarks)):
		$fail.='<p>Invalid Remarks: remarks is not entered</p>';
		$err++;
	endif;

	if($err==0):
		$posts->description = testInputln($posts->description);
		$posts->location = testInputln($posts->location);
		$posts->remark = testInputln($posts->remarks);
		$token = getToken(5).$ezDb->get_var("SELECT IFNULL(COUNT(`id`+1), 1) FROM `property_request` ORDER BY `id` DESC LIMIT 1");
		$ezDb->query("INSERT INTO `property_request` (`token`, `prop_date`, `names`, `phone`, `location`, `description`, `budget`, `timeframe`, `remarks`, `dateadded`, `addedby`) VALUES ('$token', '$posts->date', '$posts->names', '$posts->phone', '$posts->location', '$posts->description', '$posts->budget', '$posts->timeframe', '$posts->remark', '$dateNow', '$posts->email');");
		alertDept("Marketing",0,"New Property Request");
		
		$fail.='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Request successfully submitted, you will be contacted as soon as it is accessed</p></div>';
	else:
		$fail='<div class="alert alert-danger text-justify">
			<i class="fa fa-warning"></i> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h3>Error Messages</h3><div class="alert alert-danger alert-dismissible" role="alert"> '.$fail.'</div>
		</div>';
	endif;
endif;