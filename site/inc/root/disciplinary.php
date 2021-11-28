<?php

$disciplinaryid=(!empty($gets->id)? $gets->id: "");

$disciplinary=$ezDb->get_row("SELECT * from `disciplinary` WHERE `token` = '$disciplinaryid';");
if(!empty($disciplinary)){
    $employee=$ezDb->get_row("SELECT * from `employees` WHERE `employeeid` = '$disciplinary->employeeid';");
    $disciplinary->department=$ezDb->get_var("SELECT `name` from  `department` WHERE `id` = $employee->department_id;");

    if (!empty($posts->triggers) and $posts->triggers=='update_concent') {
		
		$fail="";
		$err=0;
		$posts = (object) $Site["post"];
		if( empty(trim($posts->concent)) ):
			$err++;
			$fail.='<p>Enter concent</p>';
		endif;

		if ($err==0) {

			$query = "UPDATE `disciplinary` SET `employee_concent` = '$posts->concent' WHERE `token` = '$disciplinaryid'";
			
			if($ezDb->query($query)):
                $disciplinary=$ezDb->get_row("SELECT * from `disciplinary` WHERE `token` = '$disciplinaryid';");
                $employee=$ezDb->get_row("SELECT * from `employees` WHERE `employeeid` = '$disciplinary->employeeid';");
                $disciplinary->department=$ezDb->get_var("SELECT `name` from  `department` WHERE `id` = $employee->department_id;");
                alertManager($userinfo->email,0,"$disciplinary->employeeid has added his concent to the disciplinary received");
                alertHRManager(0,"$disciplinary->employeeid has added his concent to the disciplinary received");
				logEvent($userinfo->email, "updated-disciplinary", $userinfo->usertype, 'disciplinary', $disciplinary);
				$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Concent was successfully added</p></div>';
			else:
				$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
			endif;

		}else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
		}
	}

}
$smarty->assign("record", $disciplinary);
