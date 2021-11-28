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

    $divElements='<div class="row justify-content-center">
    	<div class="container">
    		<div class="card col-md-6">
    			<div class="card-heading text-center"><h2 class="prodTitle"><img src="'.$siteProtocol.$domainName.'/site/media/i/logo.png" style="height: 70px;"></h2></div>
    			<div class="card-body">
    				<h5>Dear '.$employee->first_name.',</h5>
    				<p>
    					Thank you for joining us, '.$companyName.' at ('.$domainName.')!
    				</p>
                    <p>
                        Here are your login details <br>
                        <strong> Username:</strong> '.$posts->username.'<br>
                        <strong> Password:</strong> '.$posts->password.'  <br>
    				</p>
    			</div>
    		</div>
    	</div>
    </div>';

    $text="Sender : signup@$domainName  Message: Thank you for registering with $domainName. Please login with the above details";

    $mailer->Host = 'localhost';
    $mailer->SMTPDebug  = 0;
    $mailer->SMTPAuth = true;
    $mailer->Username = $mailConfig->auth["email"];
    $mailer->Password = $mailConfig->auth["password"];
    $mailer->SMTPSecure = 'tls';
    $mailer->Port = 587;

    $mailer->setFrom($mailConfig->signup["email"], $companyName);
    $mailer->addAddress($employee->email, $employee->first_name);

    $mailer->isHTML(true);
    $mailer->Subject = "Welcomes You To $companyName Family";
    $mailer->Body = '<html>
	     <head>
	      	<title>Signup</title>
	      	<link rel="stylesheet" href="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/bootstrap/bootstrap.min.css" />
			<link rel="stylesheet" href="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/font-awesome/css/font-awesome.min.css">
			<link rel="stylesheet" href="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/css/style.css" type="text/css" />
			<style>
			</style>
	      </head>
      	  <body>
      	  	'.$divElements.'
			<script src="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/jquery/jquery-3.3.1.js"></script>
			<script src="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/bootstrap/bootstrap.min.js"></script>
			<script src="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/js/script.js"></script>
      	  </body>
      </html>'."\n$text";

    $mailer->send();
    $mailer->ClearAllRecipients();
    // echo "MAIL HAS BEEN SENT SUCCESSFULLY";
    $fail='<div class="alert alert-success alert-dismissible" role="alert">
		<i class="fa fa-checked"></i> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h3>Success</h3><p class="border bg-white border-success px-2 py-1 rounded">Your '.$Site['companyName'].' login was successfully created, and an email with login details has been sent to the above address</p>
	</div>';

} catch (Exception $e) {
    echo "EMAIL SENDING FAILED. INFO: " . $mailer->ErrorInfo;
    $fail.='<div class="alert alert-warning alert-dismissible" role="alert" style=" position: absolute; top: 20%; margin: 10%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Oops!</h3> <p class="border bg-white border-danger px-2 py-1 rounded">Unable to connect to send message</p></div>';
}