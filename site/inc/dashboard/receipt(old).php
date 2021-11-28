<?php 
// redirect("home");

require_once 'paystack_return.php';

$id=(!empty($gets->id)? $gets->id: "");
$payment=$ezDb->get_row("SELECT * FROM `payments` WHERE `token`='$id' AND `paidby`='$userinfo->email'");
if (empty($payment)) {
	redirect("subscriptions");
}
$payment->pslug=$payment->purpose;
$payment->purpose=ucwords(str_replace("-", " ", $payment->purpose));
if ($payment->pslug=='package'):
	$payment->programme=$ezDb->get_row("SELECT * FROM `packages` WHERE `packageid`='$payment->program';");
elseif ($payment->pslug=='course'):
	$payment->programme=$ezDb->get_row("SELECT * AS `programid` FROM `courses` WHERE `courseid`='$payment->program';");
endif;
$smarty->assign("payment", $payment);

/*$id=(!empty($gets->id)? $gets->id: "");
$payment=$ezDb->get_row("SELECT * FROM `payments` WHERE `transid`='$id' AND `paidby`='$userinfo->email'");
if (!empty($payment)) {
	if ($payment->programtype=='package') {
		$payment->programDetails=$ezDb->get_row("SELECT * FROM `packages` WHERE `packageid`='$payment->programid';");
	}elseif ($payment->programtype=='course') {
		$payment->programDetails=$ezDb->get_row("SELECT * FROM `courses` WHERE `courseid`='$payment->programid';");
	}
}else{
	redirect("transactions");
}
$smarty->assign("payment", $payment);*/