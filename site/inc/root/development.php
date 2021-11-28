<?php 


$countries=getCountries();

$menu=(!empty($gets->p)? $gets->p: "");

if( !in_array( $userinfo->userrole, array('level0','level1','level2', 'level3','level4')) and $userinfo->active==true ):
	redirect("clients");
endif;

$clientemail=(!empty($gets->id)? $gets->id: "");
$client=$ezDb->get_row("SELECT * FROM `userprofile` WHERE `username`='$clientemail' OR `email`='$clientemail' AND `usertype`='client';");

if (!empty($client) and is_object($client)) {
	$studentDetail= new stdClass;
	$student=$ezDb->get_row("SELECT * FROM `students` WHERE `email`='$client->email';");
	$student=(empty($student)?new stdClass: $student);
	$token=getToken(5).$ezDb->get_var("SELECT (IFNULL(`id`,0)+1) AS `did` FROM `students` ORDER BY `id` DESC LIMIT 1;");
	if ( empty($menu) or !in_array($menu, array('parent','emergency','medical','consent','kit','terms','development', 'sport')) ) {

		$fail="";
		$err=0;
		if ( in_array($sitePage, array("development")) && ($requestMethod == 'POST') && isset($Site["post"]['personal']) ) {
			/*if( empty(trim($posts->firstname)) ):
				$err++;
				$fail.='<p class="border bg-white border-danger px-2 py-1 rounded">Kindly enter your first name!</p>';
			endif;
			if( empty(trim($posts->lastname)) ):
				$err++;
				$fail.='<p class="border bg-white border-danger px-2 py-1 rounded">Kindly enter your surname!</p>';
			endif;*/
			if( empty(trim($posts->day)) or $posts->day<1 or $posts->day>31):
				$err++;
				$fail.='<p class="border bg-white border-danger px-2 py-1 rounded">Kindly enter a valid day of birth!</p>';
			endif;
			if( empty(trim($posts->month))  or $posts->month<1 or $posts->month>12):
				$err++;
				$fail.='<p class="border bg-white border-danger px-2 py-1 rounded">Kindly enter a valid month of birth!</p>';
			endif;
			if( empty(trim($posts->year))  or $posts->year < ($dateY-30) or $posts->year > ($dateY-4)):
				$err++;
				$fail.='<p class="border bg-white border-danger px-2 py-1 rounded">Kindly enter a valid year of birth!</p>';
			endif;
			$dobs="$posts->year-$posts->month-$posts->day";
			if( empty(trim($dobs)) or !@date(strtotime($dobs))):
				$err++;
				$fail.='<p class="border bg-white border-danger px-2 py-1 rounded">Kindly choose a valid date of birth, selected combination is invalid!</p>';
			endif;
			if( $ezDb->get_var("SELECT TIMESTAMPDIFF(YEAR, '$dobs', '$dateNow');")<4 ):
				$err++;
				$fail.='<p class="border bg-white border-danger px-2 py-1 rounded">Date of birth entered has to be four (4) years and above !</p>';
			endif;
			if( empty(trim($posts->country)) or empty("SELECT `name` FROM `countries` WHERE LCASE(`name`)='".strtolower($posts->country)."';")):
				$err++;
				$fail.='<p class="border bg-white border-danger px-2 py-1 rounded">Kindly select a valid country!</p>';
			endif;
			if( empty(trim($posts->phone)) ):
				$err++;
				$fail.='<p class="border bg-white border-danger px-2 py-1 rounded">Kindly enter a valid phone number!</p>';
			endif;

		    if ($err==0) {
				$studentDetail->firstname=$client->firstname;
				$studentDetail->surname=$client->lastname;
				$studentDetail->phone=$posts->phone;
				$studentDetail->country=$posts->country;
				$studentDetail->dob=new stdClass;
				$studentDetail->dob->day=$posts->day;
				$studentDetail->dob->month=$posts->month;
				$studentDetail->dob->year=$posts->year;
				$studentDetail->dob->string=$dobs;
		    	if(!empty($student) and is_object($student) and count((array)$student)>1){
			    	$ezDb->query("UPDATE `students` SET `personal`='".json_encode($studentDetail)."' WHERE `token`='$student->token' AND `email`='$client->email';");
		    	}else{
		    		$ezDb->query("INSERT INTO `students`  (`personal`, `email`, `token`, `dateadded`) VALUES('".json_encode($studentDetail)."', '$client->email', '$token', '$dateNow')");
		    	}
		    	try{
		    		echo "<script>alert('changes successfully saved')</script>";
			    	$student->personal=@json_encode($studentDetail);
		    		sleep(2);
			   		redirect("development?id=$clientemail&p=parent");
		    	}catch(Exception $e){}

			}else{
				$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
			}
		}
		$student->personal=@json_decode($student->personal);
		$smarty->assign("student", $student->personal);
	}
	if ( !empty($menu) and in_array($menu, array('parent')) ) {
		$fail="";
		$err=0;
		if ( in_array($sitePage, array("development")) && ($requestMethod == 'POST') && isset($Site["post"]['parent']) ) {
			if( empty(trim($posts->title)) or !in_array($posts->title, array("mr","miss","mrs","dr","other")) ):
				$err++;
				$fail.='<p class="border bg-white border-danger px-2 py-1 rounded">Kindly select a valid title!</p>';
			endif;
			if( empty(trim($posts->reffname)) ):
				$err++;
				$fail.='<p class="border bg-white border-danger px-2 py-1 rounded">Kindly enter a valid parent first name!</p>';
			endif;
			if( empty(trim($posts->refsname)) ):
				$err++;
				$fail.='<p class="border bg-white border-danger px-2 py-1 rounded">Kindly enter a valid parent surname!</p>';
			endif;
			if( empty(trim($posts->refaddress)) ):
				$err++;
				$fail.='<p class="border bg-white border-danger px-2 py-1 rounded">Kindly enter a valid parent address!</p>';
			endif;
			if( empty(trim($posts->refemail)) or !checkEmail($posts->refemail)):
				$err++;
				$fail.='<p class="border bg-white border-danger px-2 py-1 rounded">Kindly enter a valid email!</p>';
			endif;
			if( empty(trim($posts->refphone)) or checkPhone($posts->refphone)!==true):
				$err++;
				$fail.='<p class="border bg-white border-danger px-2 py-1 rounded">Kindly enter a valid phone number!</p>';
			endif;
			if( empty(trim($posts->refaddress))):
				$err++;
				$fail.='<p class="border bg-white border-danger px-2 py-1 rounded">Kindly select a valid country!</p>';
			endif;
			if( empty(trim($posts->refphone)) ):
				$err++;
				$fail.='<p class="border bg-white border-danger px-2 py-1 rounded">Kindly enter a valid phone number!</p>';
			endif;

		    if ($err==0) {
		    	$studentDetail->title=$posts->title;
				$studentDetail->firstname=$posts->reffname;
				$studentDetail->surname=$posts->refsname;
				$studentDetail->phone=$posts->refphone;
				$studentDetail->email=$posts->refemail;
				$studentDetail->address=testInput(nl2br2(tb2sp2($posts->refaddress)));
				$studentDetail->relation=$posts->refrelation;
		    	if(!empty($student) and is_object($student) and count((array)$student)>1){
			    	$ezDb->query("UPDATE `students` SET `parent`='".json_encode($studentDetail)."' WHERE `token`='$student->token' AND `email`='$client->email';");
		    	}else{
		    		$ezDb->query("INSERT INTO `students`  (`parent`, `email`, `token`, `dateadded`) VALUES('".json_encode($studentDetail)."', '$client->email', '$token', '$dateNow');");
		    	}
		    	try{
		    		echo "<script>alert('changes successfully saved')</script>";
			    	$student->parent=@json_encode($studentDetail);
		    		sleep(2);
		    	}catch(Exception $e){}
			    redirect("development?id=$clientemail&p=emergency");

			}else{
				$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
			}
		}
		$student->parent=@json_decode($student->parent);
		$student->parent=(empty($student->parent)?new stdClass: $student->parent);
		$student->parent->address=br2nl2(testInputReverse($student->parent->address));
		$smarty->assign("parent", $student->parent);
	}
	if ( !empty($menu) and in_array($menu, array('emergency')) ) {
		$fail="";
		$err=0;
		if ( in_array($sitePage, array("development")) && ($requestMethod == 'POST') && isset($Site["post"]['emergency']) ) {
			if( empty(trim($posts->title)) or !in_array($posts->title, array("mr","miss","mrs","dr","other")) ):
				$err++;
				$fail.='<p class="border bg-white border-danger px-2 py-1 rounded">Kindly select a valid title!</p>';
			endif;
			if( empty(trim($posts->emfname)) ):
				$err++;
				$fail.='<p class="border bg-white border-danger px-2 py-1 rounded">Kindly enter a valid emergency first name!</p>';
			endif;
			if( empty(trim($posts->emsname)) ):
				$err++;
				$fail.='<p class="border bg-white border-danger px-2 py-1 rounded">Kindly enter a valid emergency surname!</p>';
			endif;
			if( empty(trim($posts->emaddress)) ):
				$err++;
				$fail.='<p class="border bg-white border-danger px-2 py-1 rounded">Kindly enter a valid emergency address!</p>';
			endif;
			if( empty(trim($posts->ememail)) or !checkEmail($posts->ememail)):
				$err++;
				$fail.='<p class="border bg-white border-danger px-2 py-1 rounded">Kindly enter a valid email!</p>';
			endif;
			if( empty(trim($posts->emphone)) or checkPhone($posts->emphone)!==true):
				$err++;
				$fail.='<p class="border bg-white border-danger px-2 py-1 rounded">Kindly enter a valid phone number!</p>';
			endif;

		    if ($err==0) {
		    	$studentDetail->title=$posts->title;
				$studentDetail->firstname=$posts->emfname;
				$studentDetail->surname=$posts->emsname;
				$studentDetail->phone=$posts->emphone;
				$studentDetail->email=$posts->ememail;
				$studentDetail->address=testInput(nl2br2(tb2sp2($posts->emaddress)));
				$studentDetail->relation=$posts->emrelation;
		    	if(!empty($student) and is_object($student) and count((array)$student)>1){
			    	$ezDb->query("UPDATE `students` SET `emergency`='".json_encode($studentDetail)."' WHERE `token`='$student->token' AND `email`='$client->email';");
		    	}else{
		    		$ezDb->query("INSERT INTO `students`  (`emergency`, `email`, `token`, `dateadded`) VALUES('".json_encode($studentDetail)."', '$client->email', '$token', '$dateNow');");
		    	}
		    	try{
		    		echo "<script>alert('changes successfully saved')</script>";
			    	$student->emergency=@json_encode($studentDetail);
		    		sleep(2);
		    	}catch(Exception $e){}
			    redirect("development?id=$clientemail&p=medical");

			}else{
				$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
			}
		}
		$student->emergency=@json_decode($student->emergency);
		$student->emergency=(empty($student->emergency)?new stdClass: $student->emergency);
		$student->emergency->address=br2nl2(testInputReverse($student->emergency->address));
		$smarty->assign("emergency", $student->emergency);
	}
	if ( !empty($menu) and in_array($menu, array('medical')) ) {
		$fail="";
		$err=0;
		if ( in_array($sitePage, array("development")) && ($requestMethod == 'POST') && isset($Site["post"]['medical1']) ) {
			if( $posts->medical=='yes' or $posts->hospitalized=='yes' or $posts->allergies=='yes' or $posts->special_need=='yes' ):
				if( empty(trim($posts->med_detail)) ):
					$err++;
					$fail.='<p class="border bg-white border-danger px-2 py-1 rounded">Kindly give further details on your medical condition!</p>';
				endif;
			endif;
			if( !in_array($posts->medical, array("yes","no")) ):
				$err++;
				$fail.='<p class="border bg-white border-danger px-2 py-1 rounded">Kindly select option for medical condition!</p>';
			endif;
			if( !in_array($posts->hospitalized, array("yes","no")) ):
				$err++;
				$fail.='<p class="border bg-white border-danger px-2 py-1 rounded">Kindly select option for if child was recently hospitalized!</p>';
			endif;
			if( !in_array($posts->allergies, array("yes","no")) ):
				$err++;
				$fail.='<p class="border bg-white border-danger px-2 py-1 rounded">Kindly select option for child allergies!</p>';
			endif;
			if( !in_array($posts->special_need, array("yes","no")) ):
				$err++;
				$fail.='<p class="border bg-white border-danger px-2 py-1 rounded">Kindly select option for if child is on medication!</p>';
			endif;

		    if ($err==0) {
			    $studentDetail->medical=$posts->medical;
			    $studentDetail->hospitalized=$posts->hospitalized;
			    $studentDetail->allergies=$posts->allergies;
			    $studentDetail->special_need=$posts->special_need;
			    $studentDetail->med_detail=testInput(nl2br2(tb2sp2($posts->med_detail)));
		    	if(!empty($student) and is_object($student) and count((array)$student)>1){
			    	$ezDb->query("UPDATE `students` SET `medical`='".json_encode($studentDetail)."' WHERE `token`='$student->token' AND `email`='$client->email';");
		    	}else{
		    		$ezDb->query("INSERT INTO `students`  (`medical`, `email`, `token`, `dateadded`) VALUES('".json_encode($studentDetail)."', '$client->email', '$token', '$dateNow');");
		    	}
		    	try{
		    		echo "<script>alert('changes successfully saved')</script>";
			    	$student->medical=@json_encode($studentDetail);
		    		sleep(2);
		    	}catch(Exception $e){}
			    redirect("development?id=$clientemail&p=consent");

			}else{
				$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
			}
		}
		$student->medical=@json_decode($student->medical);
		$student->medical=(empty($student->medical)?new stdClass: $student->medical);
		$student->medical->med_detail=br2nl2(testInputReverse($student->medical->med_detail));
		$smarty->assign("medical", $student->medical);
	}
	if ( !empty($menu) and in_array($menu, array('consent')) ) {
		$fail="";
		$err=0;
		if ( in_array($sitePage, array("development")) && ($requestMethod == 'POST') && isset($Site["post"]['consent']) ) {
			if( empty($posts->consent0) or empty($posts->consent1) ):
				$err++;
				$fail.='<p class="border bg-white border-danger px-2 py-1 rounded">You are to agree to all provided Parent / guardian consent!</p>';
			endif;

		    if ($err==0) {
		    	$studentDetail->consent0=isset($posts->consent0)?1:0;
			    $studentDetail->consent1=isset($posts->consent1)?1:0;
			    $studentDetail->consent2=isset($posts->consent2)?1:0;
			    $studentDetail->consent3=isset($posts->consent3)?1:0;
		    	if(!empty($student) and is_object($student) and count((array)$student)>1){
			    	$ezDb->query("UPDATE `students` SET `consents`='".json_encode($studentDetail)."' WHERE `token`='$student->token' AND `email`='$client->email';");
		    	}else{
		    		$ezDb->query("INSERT INTO `students`  (`consents`, `email`, `token`, `dateadded`) VALUES('".json_encode($studentDetail)."', '$client->email', '$token', '$dateNow');");
		    	}
		    	try{
		    		echo "<script>alert('changes successfully saved')</script>";
			    	$student->consents=@json_encode($studentDetail);
		    		sleep(2);
		    	}catch(Exception $e){}
			    redirect("development?id=$clientemail&p=kit");

			}else{
				$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
			}
		}
		$student->consents=@json_decode($student->consents);
		$smarty->assign("consents", $student->consents);
	}
	if ( !empty($menu) and in_array($menu, array('kit')) ) {
		$fail="";
		$err=0;
		if ( in_array($sitePage, array("development")) && ($requestMethod == 'POST') && isset($Site["post"]['kits']) ) {
			if( empty(trim($posts->kit_height)) ):
				$err++;
				$fail.='<p class="border bg-white border-danger px-2 py-1 rounded">Kindly enter player height in metres!</p>';
			endif;
			if( empty(trim($posts->topshirt)) ):
				$err++;
				$fail.='<p class="border bg-white border-danger px-2 py-1 rounded">Kindly choose t-shirt size!</p>';
			endif;
			if( empty(trim($posts->short)) ):
				$err++;
				$fail.='<p class="border bg-white border-danger px-2 py-1 rounded">Kindly choose short size!</p>';
			endif;
			if( empty(trim($posts->kit_name)) ):
				$err++;
				$fail.='<p class="border bg-white border-danger px-2 py-1 rounded">Kindly enter kit name!</p>';
			endif;
			if( empty(trim($posts->kit_number)) ):
				$err++;
				$fail.='<p class="border bg-white border-danger px-2 py-1 rounded">Kindly enter kit number!</p>';
			endif;
			if( !empty($ezDb->get_var("SELECT `email` FROM `students` WHERE `email`!='$client->email' AND `kits` LIKE'%\"kit_number\":\"$posts->kit_number\"%';")) ):
				$err++;
				$fail.='<p class="border bg-white border-danger px-2 py-1 rounded">The selected kit number had already been chosen by another user!</p>';
			endif;

		    if ($err==0) {

			    $studentDetail->kit_height=$posts->kit_height; 
			    $studentDetail->tshirt=""; 
			    $studentDetail->tshirt_measure=$posts->topshirt; 
			    $studentDetail->short=""; 
			    $studentDetail->short_measure=$posts->short; 
			    $studentDetail->kit_name=testInput($posts->kit_name); 
			    $studentDetail->kit_number=$posts->kit_number;
		    	if(!empty($student) and is_object($student) and count((array)$student)>1){
			    	$ezDb->query("UPDATE `students` SET `kits`='".json_encode($studentDetail)."' WHERE `token`='$student->token' AND `email`='$client->email';");
		    	}else{
		    		$ezDb->query("INSERT INTO `students`  (`kits`, `email`, `token`, `dateadded`) VALUES('".json_encode($studentDetail)."', '$client->email', '$token', '$dateNow');");
		    	}
		    	try{
		    		echo "<script>alert('changes successfully saved')</script>";
			    	$student->kits=@json_encode($studentDetail);
		    		sleep(2);
		    	}catch(Exception $e){}
			    redirect("development?id=$clientemail&p=terms");

			}else{
				$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
			}
		}
		$student->kits=@json_decode($student->kits);
		$smarty->assign("kit", $student->kits);
	}
	if ( !empty($menu) and in_array($menu, array('terms')) ) {
		$fail="";
		$err=0;
		if ( in_array($sitePage, array("development")) && ($requestMethod == 'POST') && isset($Site["post"]['term']) ) {
			if( !isset($posts->term) or !isset($posts->declare) ):
				$err++;
				$fail.='<p class="border bg-white border-danger px-2 py-1 rounded">You are to read through and agree to all provided declarations and terms!</p>';
			endif;

		    if ($err==0) {
		    	$studentDetail->terms=isset($posts->terms)?1:1;
			    $studentDetail->declare=isset($posts->declare)?1:1;
		    	if(!empty($student) and is_object($student) and count((array)$student)>1){
			    	$ezDb->query("UPDATE `students` SET `terms`='".json_encode($studentDetail)."' WHERE `token`='$student->token' AND `email`='$client->email';");
		    	}else{
		    		$ezDb->query("INSERT INTO `students`  (`terms`, `email`, `token`, `dateadded`) VALUES('".json_encode($studentDetail)."', '$client->email', '$token', '$dateNow');");
		    	}
		    	try{
		    		echo "<script>alert('changes successfully saved')</script>";
			    	$student->terms=@json_encode($studentDetail);
		    		sleep(2);
		    	}catch(Exception $e){}
			    // redirect("development?p=kit");
			    $fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p class="border bg-white border-success px-2 py-1 rounded">Record successfully saved!</p></div>';

			}else{
				$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
			}
		}
		$student->terms=@json_decode($student->terms);
		$smarty->assign("terms", $student->terms);
	}
	if ( !empty($menu) and in_array($menu, array('sport')) ) {
		$fail="";
		$sports=$ezDb->get_results("SELECT * FROM `sports`;");
		$err=0;
		if ( in_array($sitePage, array("development")) && ($requestMethod == 'POST') && isset($Site["post"]['sport']) ) {
			if( !isset($posts->sport) or empty($ezDb->get_var("SELECT `sportid` FROM `sports` WHERE `sportid`='$posts->sport';")) ):
				$err++;
				$fail.='<p class="border bg-white border-danger px-2 py-1 rounded">The selected sport does not exist. Kindly select a valsit sport center!</p>';
			endif;

		    if ($err==0) {
		    	$sport=$ezDb->get_row("SELECT * FROM `sports` WHERE `sportid`='$posts->sport';");
		    	$studentDetail->sportid=$sport->sportid;
			    $studentDetail->name=$sport->name;
			    $studentDetail->description=$sport->description;
			    $studentDetail->category=$sport->category;
			    $studentDetail->city=$sport->city;
			    $studentDetail->state=$sport->state;
			    $studentDetail->location=$sport->location;
			    $studentDetail->logo=$sport->logo;
		    	if(!empty($student) and is_object($student) and count((array)$student)>1){
			    	$ezDb->query("UPDATE `students` SET `sport`='".json_encode($studentDetail)."' WHERE `token`='$student->token' AND `email`='$client->email';");
		    	}else{
		    		$ezDb->query("INSERT INTO `students`  (`sport`, `email`, `token`, `dateadded`) VALUES('".json_encode($studentDetail)."', '$client->email', '$token', '$dateNow');");
		    	}
		    	try{
		    		echo "<script>alert('changes successfully saved')</script>";
			    	$student->terms=@json_encode($studentDetail);
		    		sleep(2);
		    		redirect("development?id=$clientemail&");
		    	}catch(Exception $e){}
			    
			}else{
				$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> '.$fail.'</div>';
			}
		}
		$student->sport=@json_decode($student->sport);
		$smarty->assign("sport", $student->sport)->assign("sports", $sports);
	}
}else{
	redirect("clients");
}

$smarty->assign("client", $client)->assign("menu", $menu)->assign("countries", $countries);