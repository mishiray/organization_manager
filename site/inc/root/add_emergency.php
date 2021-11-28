<?php 

$employees=$ezDb->get_results("SELECT e.employeeid as empid , e.surname as surname, e.first_name as first_name from `employees` as e LEFT JOIN `payroll` as p on e.employeeid = p.employeeid WHERE p.employeeid IS NULL ");

$smarty->assign("employees", $employees);

if( !in_array( $userinfo->userrole, array('level0', 'level1', 'level3', 'level2','level4')) ):
	redirect("home");
endif;
if ( in_array($sitePage, array("add_disciplinary")) && ($requestMethod == 'POST') && isset($Site["post"]['add_disciplinary']) ) {

	$fail="";
	$err=0;
	$posts = (object) $Site["post"];
	$files= (object) $Site["files"];
	if( empty(trim($posts->employeeid)) ):
		$err++;
		$fail.='<p>Select an Employee please!</p>';
	endif;
	if( empty(trim($posts->name)) ):
		$err++;
		$fail.='<p>Enter Employee name please!</p>';
	endif;
	if( empty(trim($posts->Date_of_Warning)) ):
		$err++;
		$fail.='<p>Enter Warning Date please!</p>';
	endif;
	if( empty(trim($posts->Date_of_Misconduct)) ):
		$err++;
		$fail.='<p>Enter Date of Misconduct please!</p>';
	endif;
	if( empty(trim($posts->Manager)) ):
		$err++;
		$fail.='<p>Enter Manager please!</p>';
	endif;
	if( empty(trim($posts->HR_Head)) ):
		$err++;
		$fail.='<p>Enter HR_Head please!</p>';
	endif;
	if( empty(trim($posts->Type_of_Warnings)) ):
		$err++;
		$fail.='<p>Enter Type_of_Warnings please!</p>';
	endif;
	if( empty(trim($posts->Description_of_Warnings)) ):
		$err++;
		$fail.='<p>Enter Description_of_Warnings please!</p>';
	endif;
	if( empty(trim($posts->Employee_Conscent)) ):
		$err++;
		$fail.='<p>Enter personal objectives please!</p>';
	endif;
	if( empty(trim($posts->Action_Plan)) ):
		$err++;
		$fail.='<p>Enter Action_Plan please!</p>';
	endif;
		if(!in_array($userinfo->userrole, array('level0', 'level1', 'level3','level2'))):
		$err++;
		$fail.='<p>Your user level is not authorized to use this section!</p>';
	endif;
	if ($err==0) {
		$query = "INSERT INTO `Disciplinary`(`employeeid`, `name`, `Date_of_Warning`, `Date_of_Misconduct`, `Manager`, `HR_Head`, `Type_of_Warnings`, `Description_of_Warnings`, `Employee_Conscent`, `Action_Plan`) VALUES ('$posts->employeeid','$posts->name','$posts->Date_of_Warning','$posts->Date_of_Misconduct','$posts->Manager','$posts->HR_Head','$posts->Type_of_Warnings','$posts->Description_of_Warnings','$posts->Employee_Conscent','$posts->Action_Plan');";
		if($ezDb->query($query)){
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Your salary record has been successfully added.</p></div>';
		}
		else{
			$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> Failed to add disciplinary records</div>';
		}
	}else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	}
}