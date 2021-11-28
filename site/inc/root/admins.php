<?php

$userinfo=$Site["session"]["User"]["userinfo"];
if( !in_array( $userinfo->userrole, array('level0','level1')) ):
	redirect("home");
endif;

// $officials=$ezDb->get_results("SELECT `username`, `email`, `firstname`, `lastname`, `middlename`, `phone`, `usercat`, `userrole`, `dateadded` FROM `userprofile` WHERE `usertype`='admin' AND (`usercat`='official' OR `userrole`!='level0');"); //to improve when officials tabel exist


$whereClause="";

$officials=tableRoutine("userprofile", ' `username`, `email`, `firstname`, `lastname`, `middlename`, `phone`, `usercat`, `userrole`, `dateadded`' , " `usertype`='admin' AND (`usercat`='official' OR `userrole`!='level0') $whereClause", '`id`');

$smarty->assign("officials", $officials)->assign("urole", array("level1"=>"Super Admin", "level2"=>"Admin Level 2", "level3"=>"Admin", "level4"=>"Course Instructor"));