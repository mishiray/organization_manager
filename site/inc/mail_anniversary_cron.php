<?php 
require("/home/atobengm/public_html/lib/mailer/vendor/phpmailer/phpmailer/src/PHPMailer.php");
require("/home/atobengm/public_html/lib/mailer/vendor/phpmailer/phpmailer/src/SMTP.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendanniv($name, $mailto){
	global $ezDb, $siteName, $dateNow, $Site, $domainName, $companyName, $mailConfig;

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
		
		$mailer->setFrom("notification@$domainName", "");
		$mailer->addAddress($mailto, $name);

		$mailer->isHTML(true);
		$mailer->Subject = " Happy Anniversary From "." $companyName ";
		$mailer->Body = '<html>
			<head>
				<title> Anniversary</title>
				<link rel="stylesheet" href="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/bootstrap/bootstrap.min.css" />
				<link rel="stylesheet" href="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/font-awesome/css/font-awesome.min.css">
				<link rel="stylesheet" href="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/css/style.css" type="text/css" />
				<style>
				</style>
			</head>
			<body>
				<div class="container">
					<div class="card">
					<div class="card-header " style="color: #fff !important; background-color: #4268d6; padding:10px">
						<h3>Happy Anniversary!</h3>
					</div>
					<div class="card-body">
						<blockquote class="blockquote mb-0">
						<h4> Dear '.ucwords($name).'</h4>
							<p>
								You make a difference every day, and we are grateful for every day you spend with us. Happy workiversary!
							</p>
						<p>
						'.$companyName .'
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
		$mailer->AltBody ="Sender : notification@$companyName\n\nMessage: ".PHP_EOL;
		if ($mailer->send()) :
			$fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Your message has been successfully sent.<br/>Thanks!</p></div>';
		else:
			$fail='<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error 601:</h3><p>Unable to connect to send message</p></div>';
		endif;
		$mailer->ClearAllRecipients();
		//echo $fail;
	} catch (Exception $e) {
		// echo "EMAIL SENDING FAILED. INFO: " . $mailer->ErrorInfo;
		$fail.='<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error 601:</h3> <p>Unable to connect to send message</p></div>';
	}

}