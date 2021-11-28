<?php
$counts=new stdClass;
// $counts->courses=$ezDb->get_var("SELECT IFNULL(COUNT(`id`),0) FROM `registered_courses` WHERE `user`='$userinfo->email';");


$userinfo=$Site["session"]["User"]["userinfo"];	


//read alerts
if(!empty($gets->alertid)){
    $alerttoken=(!empty($gets->alertid)? $gets->alertid: "");
    $alert=$ezDb->get_row("SELECT * FROM `alerts` WHERE `receivers` LIKE'%\"$userinfo->email\"%' AND `token`='$alerttoken';");
    if (!empty($alert)) {
        $alert->receivers=json_decode($alert->receivers);
        $alert->readers=json_decode($alert->readers);
        //echo $message->readers;
        if (!in_array($userinfo->email, $alert->readers)) {
            array_push($alert->readers, $userinfo->email);
            $ezDb->query("UPDATE `alerts` SET `readers`='".json_encode($alert->readers)."' WHERE `token`='$alerttoken';");
        }
        //$smarty->assign("alert", $alert);
    }
    header('Location: home');
}

$smarty->assign("userinfo", $userinfo);