<?php 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$posteds =(object)$Site['post'];

$userinfo=$Site['session']['User']['userinfo'];
$fail="";
$err=0;
if ( in_array($sitePage, array("contact-instructor")) && ($requestMethod == 'POST') && isset($Site["post"]['instructor']) ) {
	if ( empty($posteds->names) ):
		$posteds->names="$userinfo->firstname $userinfo->lastname";
	endif;
	if ( empty($posteds->email) or !checkEmail($posteds->email)):
		$posteds->email="$userinfo->email";
	endif;
	if ( empty($posteds->message) ):
		$err++;
		$fail .= '<p>Invalid message: Kindly enter a message!</p>';
	endif;
	if ( empty($posteds->subject) ):
		$posteds->subject= 'Enquiry';
	endif;
	if($err==0):
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
			$text="Subject: $posteds->subject\n Name: $posteds->names \n Email: $posteds->email \n Message: $posteds->message \n".PHP_EOL;

		    $mailer->Host = 'localhost';
		    $mailer->SMTPDebug  = 0;
		    $mailer->SMTPAuth = true;
		    $mailer->Username = $mailConfig->auth["email"];
		    $mailer->Password = $mailConfig->auth["password"];
		    $mailer->SMTPSecure = 'tls';
		    $mailer->Port = 587;

		    $mailer->setFrom($posteds->email, $posteds->names);
		    $mailer->addAddress($mailConfig->instructor["email"], $companyName);

		    $mailer->isHTML(true);
		    $mailer->Subject = $posteds->subject;
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
						    <h3>Dunis Edu Online Contact Instructor Message</h3>
						  </div>
						  <div class="card-body">
						    <blockquote class="blockquote mb-0">
						      <h4>'.$posteds->subject.'</h4>
						      <p>Name: '.$posteds->names.' </p>
				      	  	  <p>Email: '.$posteds->email.' </p>
				      	  	  <p>Message: '.$posteds->message.' </p>
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
				error_log("message0");
				$ezDb->query("INSERT INTO `newsletter` (`names`, `message`, `subject`, `dateupdated`, `email`) VALUES ('$posteds->names', '$posteds->message', '$posteds->subject', '$dateNow', '$posteds->email');");
				$contents=new stdClass;
				$contents->subject=$posteds->subject;
				$contents->message=$posteds->message;
				$contents->phone=$posteds->phone;

				logEvent($userinfo->email, "message-instructor", $userinfo->usertype, 'newsletter', $contents);
				$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Your message had been successfully sent.<br/>Thanks!</p></div>';
			else:
				$fail='<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error 601:</h3> <p>Unable to connect to send message</p></div>';
			endif;
	    	$mailer->ClearAllRecipients();

		} catch (Exception $e) {
		    // echo "EMAIL SENDING FAILED. INFO: " . $mailer->ErrorInfo;
		    // error_log("EMAIL SENDING FAILED. INFO: " . $mailer->ErrorInfo);
		    $fail.='<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error 601:</h3> <p>Unable to connect to send message</p></div>';
		}

	else:
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error:</h3> '.$fail.'</div>';
	endif;
}
