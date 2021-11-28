<?php

$userinfo=$Site["session"]["User"]["userinfo"];
if( !in_array( $userinfo->userrole, array('level0','level1')) ):
	redirect("home");
endif;

$whereClause="";

$officials=tableRoutine("userprofile", ' `username`, `email`, `firstname`, `lastname`, `middlename`, `phone`, `usercat`, `userrole`, `dateadded`' , " `usertype`='admin' AND ( `userrole`!='level0') $whereClause", '`id`');
if( !empty($officials)){
    foreach($officials as $value){
        $value->employeeid = $ezDb->get_var("SELECT `employeeid` FROM  `employees` WHERE `email` = '$value->email'");
    }
}

$smarty->assign("officials", $officials)->assign("urole", array("level0"=>"Super Admin", "level1"=>"BDM", "level2"=>"Manager","level3"=>"Supervisor", "level4"=>"Employee"));