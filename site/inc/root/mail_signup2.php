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
	
    $mailer->setFrom("signup@$domainName", "");
    $mailer->addAddress($sessions->email, $sessions->name);

    $mailer->isHTML(true);
    $mailer->Subject = "$companyName "." Client Login Details";
    $mailer->Body = '<html>
	     <head>
	      	<title>Login</title>
	      	<link rel="stylesheet" href="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/bootstrap/bootstrap.min.css" />
			<link rel="stylesheet" href="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/font-awesome/css/font-awesome.min.css">
			<link rel="stylesheet" href="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/css/style.css" type="text/css" />
			<style>
			</style>
	      </head>
      	  <body>
      	  	<div class="container">
	      	  	<div class="card">
				  <div class="card-header" style="color: #fff !important; background-color: #006dcb;">
				    <img src="'.$Site['siteProtocol'].$Site['domainName'].'/site/media/i/logo.png" alt="" style="height: 100px;" class="img-fluid float-left"> 
				    <h3>Client Subscription Details</h3>
				  </div>
				  <div class="card-body">
				    <blockquote class="blockquote mb-0">
					  <h4> '."$companyName".' Notification</h4>
                      <p>USERNAME: '.$sessions->username.'</p>
                      <p>PASSWORD: '.$sessions->password.'</p>
				      <footer class="blockquote-footer">
					  <p>
					  To complete the signup process, we do require email authentication. We request that you activate your account by clicking below or copy and paste the link below: <br/>
					  <a href="'.$Site['siteProtocol'].$Site['domainName'].'/verify?k='.$confirmkey.'">'.$Site['siteProtocol'].$Site['domainName'].'/verify?k='.$confirmkey.'</a>
				  		</p>
				      </footer>
				    </blockquote>
				  </div>
				</div>
      	  	</div>
			<script src="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/jquery/jquery-3.3.1.js"></script>
			<script src="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/bootstrap/bootstrap.min.js"></script>
			<script src="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/js/script.js"></script>
      	  </body>
	  </html>';
	$mailer->AltBody ="Sender : signup@$companyName\n\nMessage: ".PHP_EOL;
 
	if ($mailer->send()) :
		/*$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Your message had been successfully sent.<br/>Thanks!</p></div>';*/
	else:
		$fail='<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error 601:</h3><p>Unable to connect to send message</p></div>';
	endif;
	$mailer->ClearAllRecipients();
	//echo $fail;
} catch (Exception $e) {
    // echo "EMAIL SENDING FAILED. INFO: " . $mailer->ErrorInfo;
    $fail.='<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error 601:</h3> <p>Unable to connect to send message</p></div>';
}
