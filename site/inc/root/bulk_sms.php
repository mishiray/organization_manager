<?php 
if( !in_array( $userinfo->userrole, array('level0', 'level1', 'level2','level3', 'level4')) ):
	redirect("home");
endif;
$whereClause=" AND `status`=1";

$fail="";
$err=0;

if ( in_array($sitePage, array("bulk_sms")) && ($requestMethod == 'POST') && isset($Site["post"]['bulk_sms']) ) {
		$posts = (object) $Site["post"];
		if ( !(in_array( $userinfo->userrole, array('level0','level1','level2')) and  $userinfo->active==true)):
			$err++;
			$fail.='<p class="border badge-pill border-danger">You are not authorized to send bulk sms!</p>';
		endif;
		if(empty(trim($posts->content)) ):
			$err++;
			$fail.='<p class="border badge-pill border-danger">Enter newsletter detail please.!</p>';
		endif;
		if(empty(trim($posts->sendto)) ):
			$err++;
			$fail.='<p class="border badge-pill border-danger">Enter newsletter detail please.!</p>';
		endif;
		if($posts->sendto == 'manual'){
			if(!empty($posts->numbers)):
				if($err==0){
					$myArray = explode(',', $posts->numbers);
					if(!empty($myArray)){
						
						$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Bulk SMS processing</p></div>';
						
					}else{
						$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Please try again</p></div>';
						
					}
				}else{
					$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
				}
			else: 
					$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Please try again and input numbers correctly</p></div>';
			endif;
		}else{
			$sms_group = $posts->sendto;
			//echo $sms_group;
			$numbers = $ezDb->get_results("SELECT `number` FROM `bulk_sms` WHERE `sms_group` = '$sms_group' AND `status`=1");
			$val = $ezDb->get_var("SELECT COUNT(`number`) as 'val' FROM `bulk_sms` WHERE `sms_group` = '$sms_group' AND `status`=1");
			if(!empty($numbers)){	
				//$count = mysql_num_rows($numbers);
				//echo $val;
				$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Bulk SMS of '.$val.' numbers processing</p></div>';
			}else{
				$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>No numbers found</p></div>';
			
			}
		}		
	}

$bulk_sms=tableRoutine("bulk_sms", '*' , " `id`!=0 $whereClause", '`id`', 'DESC', '`id`', 10);
$smarty->assign("bulk_sms", $bulk_sms);