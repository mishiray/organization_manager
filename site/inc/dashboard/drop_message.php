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
			$text="Tite: $posts->title\n Name: $userinfo->firstname $userinfo->lastname \n Email: $unserinfo->email \n Message: $posts->content \n".PHP_EOL;

		    $mailer->Host = 'localhost';
		    $mailer->SMTPDebug  = 0;
		    $mailer->SMTPAuth = true;
		    $mailer->Username = $mailConfig->auth["email"];
		    $mailer->Password = $mailConfig->auth["password"];
		    $mailer->SMTPSecure = 'tls';
		    $mailer->Port = 587;

		    $mailer->setFrom($userinfo->email, "$userinfo->firstname $userinfo->lastname");
		    $mailer->addAddress($mailConfig->dropmsg["email"], $companyName);

		    $mailer->isHTML(true);
		    $mailer->Subject = $mailConfig->dropmsg["subject"];
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
		      	  	<div class="container">
			      	  	<div class="card">
						  <div class="card-header" style="color: #fff !important; background-color: #f47d3594;">
						    <img src="'.$Site['siteProtocol'].$Site['.domainName'].'/site/media/i/site-logo.png" alt="" style="height: 100px;" class="img-fluid float-left"> 
						    <h3>Dunis Edu Online Message From Student</h3>
						  </div>
						  <div class="card-body">
						    <blockquote class="blockquote mb-0">
						      <h4>'.$posts->title.'</h4>
						      <p>Student Name: '."$userinfo->firstname $userinfo->lastname".' </p>
				      	  	  <p>Email: '.$userinfo->email.' </p>
				      	  	  <p>Message: '.testInputReverse($posts->content).' </p>
						      <footer class="blockquote-footer">Date Sent: <cite title="Source Title">'.$dateNow.'</cite></footer>
						    </blockquote>
						  </div>
						</div>
		      	  	</div>
					<script src="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/jquery/jquery-3.3.1.js"></script>
					<script src="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/bootstrap/bootstrap.min.js"></script>
					<script src="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/js/script.js"></script>
		      	  </body>
		      </html>'."\n$text";

			if ($mailer->send()) :
				// error_log("message0");
			else:
				// error_log("message1");
				$fail='<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error 601:</h3> <p>Unable to send message to email</p></div>';
			endif;
	    	$mailer->ClearAllRecipients();

		} catch (Exception $e) {
		    // echo "EMAIL SENDING FAILED. INFO: " . $mailer->ErrorInfo;
		    // error_log("EMAIL SENDING FAILED. INFO: " . $mailer->ErrorInfo);
		    $fail.='<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error 601:</h3> <p>Unable to send message to email</p></div>';
		}
