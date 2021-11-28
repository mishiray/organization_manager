<?php

$userrecord= userInfo($posts->dataRecoverEmail);
$theSitePath = "$siteProtocol"."$domainName";
$theLink = "$theSitePath/reset?k=$recKey";

// $Site['siteProtocol'].$Site['domainName']."/resend?e=$posts->email"

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
				<div class="card-heading text-center"><h2 class="prodTitle"><img src="'.$Site['siteProtocol'].$Site['domainName'].'/site/media/i/logo.png" style="height: 70px;"></h2></div>
				<div class="card-body">
					<h5>Dear '.$userrecord->firstname.'</h5>
					<p>
						You recently request for password reset on your Atobe Account
					</p>
					<p>
						To complete your password reset process, we request that you Click or copy the link below open on a browser. <br/>
						<a href="'.$theLink.'">'.$theLink.'</a>
						<br/>
					    Note that the link is active for just only 24 hours.
					</p>
					<p>
						Thank You,<br/>
						The '.ucfirst($domainName).' team.
					</p>
				</div>
			</div>
		</div>
	</div>';

	$text="Dear $userrecord->firstname $userrecord->lastname
	You recently request for password reset on your Atobe Properties Account!\n\nTo complete your password reset process, we request that you Click or copy the link below open on a browser.\n\n\t$theLink\n\n\tNote that the link is active for just only 24 hours.\n\nThank You,\n\nThe ".ucfirst($domainName)." team.\n".PHP_EOL;

    $mailer->Host = 'localhost';
    $mailer->SMTPDebug  = 0;
    $mailer->SMTPAuth = true;
    $mailer->Username = $mailConfig->auth["email"];
    $mailer->Password = $mailConfig->auth["password"];
    $mailer->SMTPSecure = 'tls';
    $mailer->Port = 587;

    $mailer->setFrom($mailConfig->contact["email"], $companyName);
    $mailer->addAddress($posts->dataRecoverEmail, $posts->dataRecoverEmail);

    $mailer->isHTML(true);
    $mailer->Subject = "Password Recovery request from ".ucfirst($domainName);
    $mailer->Body = '<html>
	      <head>
	      	<title>Password Recovery</title>
			'. $declURLS->styles .'
			<style>
				
			</style>
	      </head>
      	  <body>
      	  	'.$divElements.'
			'. $declURLS->scripts .'
      	  </body>
      </html>'."\n$text";

    $mailer->send();
    $mailer->ClearAllRecipients();
    // echo "MAIL HAS BEEN SENT SUCCESSFULLY";
    $fail='<div class="alert alert-primary alert-dismissible" role="alert">
			<i class="fa fa-checked"></i> <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h3>Messages</h3><p>A password reset link had been sent to your email which expires in 24 hours from now.</p>
		</div>';

} catch (Exception $e) {
    // echo "EMAIL SENDING FAILED. INFO: " . $mailer->ErrorInfo;
    $fail.='<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error 601:</h3> <p>Unable to connect to send message ('.$mailer->ErrorInfo.')</p></div>';
}