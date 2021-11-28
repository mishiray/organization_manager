<?php 

$employee = $ezDb->get_row("SELECT * FROM `employees` WHERE `email` = '$userinfo->email'");
$settings=$ezDb->get_var("SELECT `working_hours` FROM `settings`;");
if(!empty($settings)){
    //echo 'ok';
    $settings = json_decode($settings);
    //print_r($settings);
    $startTime = $settings->open_hour_string;
    $startTime = new DateTime($startTime);
    $startTime = $startTime->format('H:i');
    $startTime = strtotime($startTime);
}else{
    $startTime = $startTime;
}

$check = $ezDb->get_row("SELECT * FROM `timesheet` WHERE DATE_FORMAT(`dateadded`, '%D%M%Y') = DATE_FORMAT(CURDATE(), '%D%M%Y') and `email` = '$userinfo->email'");
    if ($check){
        $signed_in = 'yes';
        $signed_out = 'no';
        if(!empty($check->logout)){
            $signed_out = 'yes';
            //echo 'signed out';
        }
    }else{
        $signed_in = 'no';
        $signed_out = 'no';
    }

$smarty->assign("signed_in", $signed_in)->assign("signed_out", $signed_out);


$longitude_base = '';
$latitude_base = '';


if(!empty($posts->triggers) and $posts->triggers=='sign_in'):
   // echo "ok";
   
    $fail="";
	$err=0;
	$posts = (object) $Site["post"];
	if( empty(trim($posts->latitude)) ):
		$err++;
		$fail.='<p>Can\'t get location</p>';
	endif;
	if( empty(trim($posts->longitude)) ):
		$err++;
		$fail.='<p>Can\'t get longitude</p>';
	endif;
	if ($err==0) {
        $latitude_base = $posts->latitude;
        $longitude_base = $posts->longitude;
            
        $location = array( 
        "longitude" =>$longitude_base, 
        "latitude" => $latitude_base, 
        );
        $location = json_encode($location);
        $query = "INSERT INTO `timesheet`(`email`,`location`) VALUES ('$userinfo->email','$location')";
           if ($ezDb->query($query)) {

               //if late deduct 500 naira
               $date = new DateTime($dateNow);
               $newDate = $date->format('H:i');
               $late = ($startTime < strtotime($newDate)) ? true : false;
                if($late && !in_array($userinfo->userrole, array('level0'))){
                    $late_fee = $ezDb->get_var("SELECT `late_fee` FROM `employment_salary_records` WHERE `employeeid` = '$userinfo->employeeid'");
                    $late_fee = $late_fee + 500;
                    $query = "UPDATE `employment_salary_records` SET `late_fee` = '$late_fee' WHERE `employeeid` = '$userinfo->employeeid' ";
                    $ezDb->query($query);
                    alertManager2($userinfo->email,1,"Late Sign In Attendance by ".$userinfo->employeeid,"timesheet");
			    }else{
                    alertManager2($userinfo->email,0,"Sign In Attendance by ".$userinfo->employeeid,"timesheet");
                }

               $fail='<div class="alert alert-success alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> You sign in attendance has been recorded.</p></div>';
               header('Location: timesheet');
           }else{
                $fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Try again Please.</p></div>';
           
           }
    }else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	}
   
endif;

if(!empty($posts->triggers) and $posts->triggers=='sign_out'):
   //echo "ok";
   
    $fail="";
	$err=0;
	$posts = (object) $Site["post"];
	if( empty(trim($posts->latitude)) ):
		$err++;
		$fail.='<p>Can\'t get location</p>';
	endif;
	if( empty(trim($posts->longitude)) ):
		$err++;
		$fail.='<p>Can\'t get longitude</p>';
	endif;
	if ($err==0) {
        $latitude_base = $posts->latitude;
        $longitude_base = $posts->longitude;
            
        $today = $ezDb->get_row("SELECT * FROM `timesheet` WHERE `email` = '$userinfo->email' and DATE_FORMAT(`dateadded`, '%D%M%Y') = DATE_FORMAT(CURDATE(), '%D%M%Y')");
        //print_r($today);
        $location = array( 
        "longitude" =>$longitude_base, 
        "latitude" => $latitude_base, 
        );
        $location = json_encode($location);
        
        $query = "UPDATE `timesheet` SET `logout`=CURRENT_TIMESTAMP(), `location_out`='$location' where `id` = '$today->id'";
           if ($ezDb->query($query)) {
               $fail='<div class="alert alert-success alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> You have signed out for today</p></div>';
               header('Location: timesheet');
            $signed_out = 'yes'; 
           }else{
                $fail='<div class="alert alert-danger alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Try again Please.</p></div>';
           
           }
           
    }else{
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
	}
   
endif;