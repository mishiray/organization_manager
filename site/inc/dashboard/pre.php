<?php
$declURLS=new stdClass();
$declURLS->styles='<!-- Google Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,500,600,700,700i|Montserrat:300,400,500,600,700" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/datatables/css/datatables.min.css" />
	<!-- Bootstrap CSS File -->
	<link href="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<!-- Libraries CSS Files -->
	<link href="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/font-awesome/css/font-awesome.min.css" rel="stylesheet">
	<link href="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/animate/animate.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/css/style.css">';

$declURLS->scripts='<script src="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/jquery/jquery.min.js"></script>
	<script src="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/jquery/jquery-migrate.min.js"></script>
	<script src="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/datatables/js/jquery.datatables.min.js"></script>
	<script src="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/datatables/js/datatables.min.js"></script>
	<script src="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/bootstrap/popper.js"></script>
	<script src="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- Template Main Javascript File -->
	<script src="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/js/main.js"></script>
	<script type="text/javascript" src="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/js/script.js"></script>';
/*--------------------------------------------------------------------------*/

$userinfo=$Site['session']['User']['userinfo'];

/*----------------------------
$settings=getSettings();
$dateIntervalStat='';
if ($settings->subscription->renewal=='weekly') {
	$dateIntervalStat="DATEDIFF('$dateNow',`transdate`)";
}elseif ($settings->subscription->renewal=='monthly') {
	$dateIntervalStat="TIMESTAMPDIFF(MONTH,`transdate`,'$dateNow')";
}elseif ($settings->subscription->renewal=='quarterly') {
	$dateIntervalStat="TIMESTAMPDIFF(MONTH,`transdate`,'$dateNow')";
}elseif ($settings->subscription->renewal=='yearly') {
	$dateIntervalStat="TIMESTAMPDIFF(YEAR,`transdate`,'$dateNow')";// AS `nofyear`, DATEDIFF('$dateNow',`transdate`)
}
----------------------------------------------*/
/*-----------------------------------

$lastreg=$ezDb->get_row("SELECT *, $dateIntervalStat AS `expired`, FALSE AS `isExpired`  FROM `payments` WHERE `paidby`='$userinfo->email' AND `purpose`='membership' ORDER BY `id` DESC LIMIT 1;");
// error_log(json_encode($lastreg));
if(!empty($lastreg) and is_object($lastreg)):
	if (
	($settings->subscription->renewal=='weekly' && $lastreg->expired>7) or 
	($settings->subscription->renewal=='monthly' && $lastreg->expired>0) or 
	($settings->subscription->renewal=='quarterly' && $lastreg->expired>4) or 
	($settings->subscription->renewal=='yearly' && $lastreg->expired>=1) or empty($lastreg)
	):
		$lastreg->isExpired=true;
		$userinfo->active=0;
	endif;
else:
	$lastreg=new stdClass();
	$lastreg->isExpired=true;
	$userinfo->active=0;
endif;
-----------------*/