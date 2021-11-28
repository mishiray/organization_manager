<?php 

if( !in_array( $userinfo->userrole, array('level0', 'level1', 'level3', 'level2','level4')) ):
	redirect("home");
endif;
if ( in_array($sitePage, array("add_sms_subscriber")) && ($requestMethod == 'POST') && isset($Site["post"]['add_sms_list']) ) {
	$fail="";
	$err=0;
	$posts = (object) $Site["post"];
	if( empty(trim($posts->lists)) or !in_array($posts->lists, array("single","bulk"))):
		$err++;
		$fail.='<p class="border badge-pill border-danger">Choose a format</p>';
	elseif( $posts->lists=='single'):
		if(empty($posts->singlename)):
			$err++;
			$fail.='<p class="border badge-pill border-danger">Kindly enter name!</p>';
		endif;
		if(empty($posts->singlenumber)):
			$err++;
			$fail.='<p class="border badge-pill border-danger">Kindly enter phone number!</p>';
		endif;
		if(empty($posts->sms_group_1)):
			$err++;
			$fail.='<p class="border badge-pill border-danger">Kindly enter sms group!</p>';
		endif;
		if($err==0):
			if (!empty($ezDb->get_row("SELECT * FROM `bulk_sms` WHERE `number`='$posts->singlenumber'"))) {
				if($ezDb->query("UPDATE `bulk_sms` SET `name`='$posts->singlename', `sms_group` ='$posts->sms_group_1', `dateupdated`='$dateNow' WHERE `number`='$posts->singlenumber'")){
					$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>SMS Subscriber was successfully updated.</p></div>';
				}else{
					$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Error while trying to add, Please try again</p></div>';
				}
			}else{
				if($ezDb->query("INSERT INTO `bulk_sms` (`name`,`sms_group`,`number`,`dateupdated`) VALUES ('$posts->singlename', '$posts->sms_group_1','$posts->singlenumber','$dateNow');")){
					$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>New SMS Subscriber was successfully added.</p></div>';
				}else{
					$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Error while trying to add, Please try again</p></div>';
				}
			}
		endif;
	elseif( $posts->lists=='bulk'):
		if(empty($posts->multinumber)):
			$err++;
			$fail.='<p class="border badge-pill border-danger">Kindly enter phone numbers!</p>';
		endif;
		if(empty($posts->sms_group_2)):
			$err++;
			$fail.='<p class="border badge-pill border-danger">Kindly enter sms group!</p>';
		endif;

		if($err==0):
			$myArray = explode(',', $posts->multinumber);
			//print_r($myArray);
			$count = 0; 
			if(!empty($myArray)){
				foreach ($myArray as $key => $number) {
					$number = trim($number);
					if (!empty($ezDb->get_row("SELECT * FROM `bulk_sms` WHERE `number`='$number'"))) {
						if($ezDb->query("UPDATE `bulk_sms` SET `sms_group` ='$posts->sms_group_2', `dateupdated`='$dateNow' WHERE `number`='$number'")){
						$count++;
						}else{
							$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Error while trying to add, Please try again</p></div>';
						}
					}else{
						if($ezDb->query("INSERT INTO `bulk_sms` (`sms_group`,`number`,`dateupdated`) VALUES ('$posts->sms_group_2','$number','$dateNow');")){
						$count++;
						}else{
							$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Error while trying to add, Please try again</p></div>';
						}
					}
				}
				$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>'.$count.' New SMS Subscribers were successfully added.</p></div>';
				
			}else{
				$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Please Enter Numbers and separate with comma</p></div>';
				
			}
		endif;

	endif;
}
