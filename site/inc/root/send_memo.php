<?php

$userinfo=$Site["session"]["User"]["userinfo"];
if( !in_array( $userinfo->userrole, array('level0','level1', 'level2', 'level3', 'level4')) ):
	redirect("home");
endif;

$deptid = depttodepid($userinfo->department);
$whereClause = "";
if( !in_array($userinfo->userrole, array("level0","level1","level2"))){
	$whereClause .= " AND e.department_id = '$deptid' AND u.usertype = 'admin'";
}
$recipients=$ezDb->get_results("SELECT u.email,u.usertype,u.verified, CONCAT(u.firstname,' ', u.lastname) AS `names` FROM `userprofile` as u INNER JOIN `employees` as e ON e.email = u.email WHERE u.verified='1' AND  u.usertype='admin' $whereClause ORDER BY `names` ASC;");
$departments = $ezDb->get_results("SELECT * FROM `department` ");

if ( in_array($sitePage, array("send_memo")) && ($requestMethod == 'POST') && isset($Site["post"]['send_message']) ) {
	$receiver=array();
	if( empty(trim($posts->sendto))):
		$err++;
		$fail.='<p class="border badge-pill border-danger">Choose a valid send to!</p>';
	elseif( $posts->sendto=='#'):
		if(empty($posts->receivers) or !is_array($posts->receivers) ):
			$err++;
			$fail.='<p class="border badge-pill border-danger">Kindly choose receiver(s)!</p>';
		else:
			foreach ($posts->receivers as $key => $value):
				if(empty($value) or empty($ezDb->get_var("SELECT `email` FROM `userprofile` WHERE `email`='$value' AND `verified`='1'"))):
					$err++;
					$fail.='<p class="border badge-pill border-danger">This ('.$value.') receiver is not a valid registered member!</p>';
				elseif(!empty($value)):
					array_push($receiver, $value);
				endif;
			endforeach;
		endif;
	elseif( $posts->sendto=='*'):
		$receiver=$ezDb->get_col("SELECT `email` FROM `userprofile` WHERE `verified`='1' AND usertype = 'admin'");
	else:
		$token = $posts->sendto;
		$receiver=$ezDb->get_col("SELECT DISTINCT(u.email) FROM `employees` as e INNER JOIN `userprofile`as u ON e.email = u.email WHERE u.verified = '1' AND e.department_id = '$token'  AND u.usertype = 'admin'");
	endif;
	if( empty(trim($posts->content)) ):
		$err++;
		$fail.='<p class="border badge-pill border-danger">Enter post content please!</p>';
	endif;
	if( $err==0 ):
	    $posts->content=testInput($posts->content);
	    memoUser($receiver,$posts->content);
		$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Announcement succesfully sent.</p></div>';
	else:
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	endif;
}
$smarty->assign("recipients", $recipients)->assign("departments", $departments)->assign("deptid", $deptid);