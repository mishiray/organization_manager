<?php 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$developmentMode = true;
$mailer = new PHPMailer($developmentMode);

try {
    $mailer->SMTPDebug = 2;
    $mailer->isSMTP();

    if ($developmentMode) {
        $mailer->SMTPOptions = [
            'ssl'=> [
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ];
    }
	
    $mailer->Host = 'localhost';
    $mailer->SMTPDebug  = 0;
    $mailer->SMTPAuth = true;
    $mailer->Username = $mailConfig->auth["email"];
    $mailer->Password = $mailConfig->auth["password"];
    $mailer->SMTPSecure = 'tls';
	$mailer->Port = 587;
	
	$mailer->setFrom("newsletter@$domainName","");
	$mailer->addAddress($email, $companyName.' Newsletter Subscription');

    $mailer->isHTML(true);
    $mailer->Subject = "Newsletter Subscription";
    $mailer->Body = '<html>
	     <head>
	      	<title>Newsletter</title>
	      	<link rel="stylesheet" href="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/bootstrap/bootstrap.min.css" />
			<link rel="stylesheet" href="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/font-awesome/css/font-awesome.min.css">
			<link rel="stylesheet" href="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/css/style.css" type="text/css" />
			<style>
			</style>
	      </head>
      	  <body>
      	  	<div class="container">
	      	  	<div class="card">
				  <div class="card-header" style="color: #fff !important; background-color: #006dcb; padding: 10px;">
				    <h3>Newsletter Subscription</h3>
				  </div>
				  <div class="card-body">Your subscription request for our newsletter has been successfully confirmed.<br/><br/>If you this request appears to be off your awareness, kindly click on the link below unsubscribe.<br/><br/><a href='.$Site['siteProtocol'].$Site['domainName'].'/unsubscribe?token='.base64_encode($email).' target="__blank">Unscubscribe</a>.<br/><br/>Thanks!</a>
				  </div>
				</div>
      	  	</div>
			<script src="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/jquery/jquery-3.3.1.js"></script>
			<script src="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/bootstrap/bootstrap.min.js"></script>
			<script src="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/js/script.js"></script>
      	  </body>
	  </html>';
	$mailer->AltBody ="Sender : newsletter@$companyName\n\nMessage: ".PHP_EOL;
 
	if ($mailer->send()) :
		//$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Your newsletter subscription request has been successfully sent.<br/>Thanks!</p></div>';
	else:
		$fail='<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error 601:</h3><p>Unable to connect to send message</p></div>';
	endif;
	$mailer->ClearAllRecipients();
	//echo $fail;
} catch (Exception $e) {
    // echo "EMAIL SENDING FAILED. INFO: " . $mailer->ErrorInfo;
    $fail.='<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error 601:</h3> <p>Unable to connect to send message</p></div>';
}
