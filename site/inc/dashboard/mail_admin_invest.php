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
	
    $mailer->setFrom("info@$domainName", "");
    $mailer->addAddress($mailConfig->auth["email"], 'Client Investment');

    $mailer->isHTML(true);
    $mailer->Subject = "Client Investment Notification";
    $mailer->Body = '<html>
	     <head>
	      	<title>Client Investment</title>
	      	<link rel="stylesheet" href="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/bootstrap/bootstrap.min.css" />
			<link rel="stylesheet" href="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/font-awesome/css/font-awesome.min.css">
			<link rel="stylesheet" href="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/css/style.css" type="text/css" />
			<style>
			</style>
	      </head>
      	  <body>
      	  	<div class="container">
	      	  	<div class="card">
				  <div class="card-header">
				    <img src="'.$Site['siteProtocol'].$Site['domainName'].'/site/media/i/logo.png" alt="" style="height: 100px;" class="img-fluid float-left"> 
				    <h3>Investment Details</h3>
				  </div>
				  <div class="card-body">
				    <blockquote class="blockquote mb-0">
                      <h4> '."$companyName".' Investment Notification</h4>
                      <p>'.$mail_message.'</p>
                      <br>
					  <p>
						<strong>First Name: </strong>'.$inv->firstname.' <br>
						<strong>Last Name: </strong>'.$inv->surname.' <br>
						<strong>Mobile: </strong>'.$inv->mobile.' <br>
						<strong>Email: </strong>'.$inv->email.' <br>
						<strong>Property Name: </strong>'.$inv->invest->property.'<br>
						<strong>Plan Percentage: </strong>'.$inv->invest->percentage.' <br>
						<strong>Plot Square Meter: </strong>'.$inv->invest->category.' <br>
						<strong>Principal: </strong>'.$inv->invest->principal.' <br>
						<strong>Rate Of Interest(ROI): </strong>'.$inv->invest->roi.' <br>
						<strong>Maturity: </strong>'.$inv->invest->matunity.' <br> <hr>
						<strong>Investment Balance: </strong>&#8358;'.$balance.' <br>
					  </p>
				    </blockquote>
				  </div>
				</div>
      	  	</div>
			<script src="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/jquery/jquery-3.3.1.js"></script>
			<script src="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/bootstrap/bootstrap.min.js"></script>
			<script src="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/js/script.js"></script>
      	  </body>
	  </html>';
	$mailer->AltBody ="Sender : info@$companyName\n\nMessage: ".PHP_EOL;
 
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
