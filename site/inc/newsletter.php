<?php  global $sitePage;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$posteds =(object)$Site['post'];

if ($requestMethod == 'POST' and !empty($posteds->trigger) and $posteds->trigger == 'newsletter'):
	$err=0;
	if ( empty($posteds->email) or !checkEmail($posteds->email)):
		$err++;
		$fail .= '<p>Invalid email: Kindly enter a valid email!</p>';
	endif;
	if($err==0):
		$Site["session"]['newsemail']=strtolower($posteds->email);
		$_SESSION=$Site["session"];
		$sessions= (object)$Site["session"];

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

			$text="Subject: Newsletter Subscription\n This is to notify that the email: $posteds->email had just recently subscribe to a newsletter form $companyName website".PHP_EOL;

		    $mailer->Host = 'localhost';
		    $mailer->SMTPDebug  = 0;
		    $mailer->SMTPAuth = true;
		    $mailer->Username = $mailConfig->auth["email"];
		    $mailer->Password = $mailConfig->auth["password"];
		    $mailer->SMTPSecure = 'tls';
		    $mailer->Port = 587;

		    $mailer->setFrom($posteds->email, "Newsletter@$companyName");
		    $mailer->addAddress($mailConfig->contact["email"], $companyName.' Newsletter Subscription');

		    $mailer->isHTML(true);
		    $mailer->Subject = 'Newsletter Subscription';
		    $mailer->Body = '<html>
			    <head>
			      	<title>Newsletter</title>
			      	<link rel="stylesheet" href="'.$siteProtocol.$domailName.'/lib/common/bootstrap/css/bootstrap.min.css" />
					<link rel="stylesheet" href="'.$siteProtocol.$domailName.'/lib/common/font-awesome/css/font-awesome.min.css">
					<link rel="stylesheet" href="'.$siteProtocol.$domailName.'/lib/common/css/style.css" type="text/css" />
					<style>
					</style>
			      </head>
		      	  <body>
		      	  	<div class="container">This is to notify that the email: '.$posteds->email.' had just recently subscribe to 	a newsletter form your website <br> <a href=\''.$siteProtocol."$domainName".'\'>'.$companyName.'</a></div>
					<script src="'.$siteProtocol.$domailName.'/lib/common/js/jquery-3.3.1.js"></script>
					<script src="'.$siteProtocol.$domailName.'/lib/common/bootstrap/js/bootstrap.min.js"></script>
					<script src="'.$siteProtocol.$domailName.'/lib/common/js/script.js"></script>
		      	  </body>
		    </html>'."\n$text";

		    /*$mailer->send();
		    $mailer->ClearAllRecipients();*/

			if (!$mailer->send()) :
				$fail='<div class="alert alert-warning alert-dismissible" role="alert" style=" position: absolute; top: 20%; margin: 10%; z-index: 1;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error 601:</h3> <p>Unable to connect to send message</p></div>';
			else:
				if (!empty($ezDb->get_row("SELECT * FROM `newsletter` WHERE `email`='$posteds->email'"))) {
					$ezDb->query("UPDATE `newsletter` SET `status`='1', `dateupdated`='$dateNow' WHERE `email`='$posteds->email'");
				}else{
					$ezDb->query("INSERT INTO `newsletter` (`status`, `dateupdated`, `email`) VALUES ('1', '$dateNow', '$posteds->email');");
				}
				require_once "mail_newsletter.php";
				//$fail='<div class="alert alert-success alert-dismissible" role="alert" style=" position: absolute; top: 20%; margin: 10%; z-index: 1;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Your newsletter subscription request is successfull.<br/>Thanks!</p></div>';
			endif;
	    	$mailer->ClearAllRecipients();
	    	
		} catch (Exception $e) {
		    // echo "EMAIL SENDING FAILED. INFO: " . $mailer->ErrorInfo;
		    $fail.='<div class="alert alert-warning alert-dismissible" role="alert" style=" position: absolute; top: 20%; margin: 10%; z-index: 1;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error 601:</h3> <p>Unable to connect to send message</p></div>';
		}
	else:
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error:</h3> '.$fail.'</div>';
	endif;
	$smarty->assign("posts", $posteds)->assign("msg_nl", $fail);
	$fail='';
endif;