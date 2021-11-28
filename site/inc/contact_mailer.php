<?php global $sitePage;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$posteds =(object)$Site['post'];
$options="";
if ($requestMethod == 'POST' and !empty($posteds->trigger) and $posteds->trigger=='contact'):
	$err=0;
	if ( empty($posteds->names) ):
		$err++;
		$fail .= '<p>Invalid name: enter sender\'s name!</p>';
	endif;
	if ( empty($posteds->email) or !checkEmail($posteds->email)):
		$err++;
		$fail .= '<p>Invalid email: Kindly enter a valid email!</p>';
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
			$text="Subject: $posteds->subject\n Name: $posteds->names".(!empty($posteds->phone) ? "\n Phone Number: $posteds->phone " : "")."\n Email: $posteds->email \n Message: $posteds->message \n".PHP_EOL;

		    $mailer->Host = 'localhost';
		    $mailer->SMTPDebug  = 0;
		    $mailer->SMTPAuth = true;
		    $mailer->Username = $mailConfig->auth["email"];
		    $mailer->Password = $mailConfig->auth["password"];
		    $mailer->SMTPSecure = 'tls';
		    $mailer->Port = 587;

		    $mailer->setFrom($posteds->email, $posteds->names);
		    $mailer->addAddress($mailConfig->contact["email"], $companyName);

		    $mailer->isHTML(true);
		    $mailer->Subject = $posteds->subject;
		    $mailer->Body = '<html>
			     <head>
			      	<title>'.$companyName.' Contact</title>
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
						    <img src="'.$Site['siteProtocol'].$Site['domainName'].'/site/media/i/logo1.JPG" alt="" style="height: 100px;" class="img-fluid float-left"> 
						    <h3>'.$companyName.'</h3>
						  </div>
						  <div class="card-body">
						    <blockquote class="blockquote mb-0">
						      <h4>'.$posteds->subject.'</h4>
						      <p>Name: '.$posteds->names.' </p>
						      '.(!empty($posteds->phone) ? " <p>Phone Number: $posteds->phone</p>" : "").'
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
		      </html>';
		    $mailer->AltBody = "$text"; 
			if ($mailer->send()) :
				if (!empty($ezDb->get_row("SELECT * FROM `newsletter` WHERE `email`='$posteds->email'"))) {
					$ezDb->query("UPDATE `newsletter` SET `subject`='$posteds->subject', `names`='$posteds->names', `message`='$posteds->message', `dateupdated`='$dateNow' WHERE `email`='$posteds->email'");
				}else{
					$ezDb->query("INSERT INTO `newsletter` (`names`, `message`, `subject`, `dateupdated`, `email`) VALUES ('$posteds->names', '$posteds->message', '$posteds->subject', '$dateNow', '$posteds->email');");
				}
				$fail='<div class="alert alert-success alert-dismissible" role="alert" style=" position: absolute; top: 20%; margin: 10%; z-index: 1;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Your message had been successfully sent.<br/>Thanks!</p></div>';
			else:
				$fail='<div class="alert alert-warning alert-dismissible" role="alert" style=" position: absolute; top: 20%; margin: 10%; z-index: 1;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error 601:</h3> <p>Unable to connect to send message</p></div>';
			endif;
	    	$mailer->ClearAllRecipients();

		} catch (Exception $e) {
		    // echo "EMAIL SENDING FAILED. INFO: " . $mailer->ErrorInfo;
		    $fail.='<div class="alert alert-warning alert-dismissible" role="alert" style=" position: absolute; top: 20%; margin: 10%; z-index: 1;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error 601:</h3> <p>Unable to connect to send message</p></div>';
		}
	else:
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error:</h3> '.$fail.'</div>';
	endif;
	$smarty->assign("posts", $posteds)->assign("msg", $fail);
	$fail='';
endif;

if ($requestMethod == 'POST' and !empty($posteds->trigger) and $posteds->trigger=='newsletter'):
	$err=0;
	if ( empty($posteds->email) or !checkEmail($posteds->email)):
		$err++;
		$fail .= '<p>Invalid email: Kindly enter a valid email!</p>';
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
			      	<title>'.$companyName.' Newsletter</title>
			      	<link rel="stylesheet" href="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/bootstrap/bootstrap.min.css" />
					<link rel="stylesheet" href="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/font-awesome/css/font-awesome.min.css">
					<link rel="stylesheet" href="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/css/style.css" type="text/css" />
					<style>
					</style>
			      </head>
		      	  <body>
		      	  	<div class="container">This is to notify that the email: '.$posteds->email.' had just recently subscribe to a newsletter form your website <br> <a href=\''.$siteProtocol."$domainName".'\'>'.$companyName.'</a></div>
					<script src="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/jquery/jquery-3.3.1.js"></script>
					<script src="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/bootstrap/bootstrap.min.js"></script>
					<script src="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/js/script.js"></script>
		      	  </body>
		      </html>';
		    $mailer->AltBody = "$text"; 

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
				$fail='<div class="alert text-dark alert-success alert-dismissible" role="alert" style=" position: absolute; top: 20%; margin: 10%; z-index: 1;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Your newsletter subscription request is successful.<br/>Thanks!</p></div>';
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

if ($requestMethod == 'POST' and !empty($posteds->trigger) and $posteds->trigger=='site_inspection'):
	$err=0;
	if ( empty($posteds->fullname) ):
		$err++;
		$fail .= '<p>Invalid name: enter full name!</p>';
	endif;
	if ( empty($posteds->email) or !checkEmail($posteds->email)):
		$err++;
		$fail .= '<p>Invalid email: Kindly enter a valid email!</p>';
	endif;
	if ( empty($posteds->phone)):
		$err++;
		$fail .= '<p>Invalid Phone: Kindly enter a Phone Number!</p>';
	endif;
	if ( empty($posteds->site)):
		$err++;
		$fail .= '<p>Invalid Site: Kindly enter a site for inspection!</p>';
	endif;
	if ( empty($posteds->date) ):
		$err++;
		$fail .= '<p>Invalid Date: Kindly enter a date!</p>';
	endif;
	if ( empty($posteds->subject) ):
		$posteds->subject= 'Site Inspection';
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
			$text="Subject: $posteds->subject\n Name: $posteds->fullname".(!empty($posteds->phone) ? "\n Phone Number: $posteds->phone " : "")."\n Email: $posteds->email \n Site: $posteds->site \n".PHP_EOL;

		    $mailer->Host = 'localhost';
		    $mailer->SMTPDebug  = 0;
		    $mailer->SMTPAuth = true;
		    $mailer->Username = $mailConfig->auth["email"];
		    $mailer->Password = $mailConfig->auth["password"];
		    $mailer->SMTPSecure = 'tls';
		    $mailer->Port = 587;

		    $mailer->setFrom($posteds->email, $posteds->fullname);
		    $mailer->addAddress($mailConfig->contact["email"], $companyName);

		    $mailer->isHTML(true);
		    $mailer->Subject = $posteds->subject;
		    $mailer->Body = '<html>
			     <head>
			      	<title>'.$companyName.' Site Inspection</title>
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
						    <img src="'.$Site['siteProtocol'].$Site['domainName'].'/site/media/i/logo1.JPG" alt="" style="height: 100px;" class="img-fluid float-left"> 
						    <h3>'.$companyName.'</h3>
						  </div>
						  <div class="card-body">
						    <blockquote class="blockquote mb-0">
						      <h4>'.$posteds->subject.'</h4>
						      <p>Name: '.$posteds->fullname.' </p>
						      '.(!empty($posteds->phone) ? " <p>Phone Number: $posteds->phone</p>" : "").'
				      	  	  <p>Email: '.$posteds->email.' </p>
				      	  	  <p>Site: '.$posteds->site.' </p>
				      	  	  <p>Date & Time: '.$posteds->date.' </p>
						      <footer class="blockquote-footer">Date Sent: <cite title="Source Title">'.$dateNow.'</cite></footer>
						    </blockquote>
						  </div>
						</div>
		      	  	</div>
					<script src="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/jquery/jquery-3.3.1.js"></script>
					<script src="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/bootstrap/bootstrap.min.js"></script>
					<script src="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/js/script.js"></script>
		      	  </body>
		      </html>';
			  
		    $mailer->AltBody = "$text"; 
			if ($mailer->send()) :
				echo "<script> alert('Your message has been sent!'); </script>";
				//$fail='<div class="alert alert-success alert-dismissible" role="alert" style=" position: absolute; top: 20%; margin: 10%; z-index: 1;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Your message had been successfully sent.<br/>Thanks!</p></div>';
			else:
				echo "<script> alert('Error 601: Unable to connect to send message'); </script>";
				$fail='<div class="alert alert-warning alert-dismissible" role="alert" style=" position: absolute; top: 20%; margin: 10%; z-index: 1;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error 601:</h3> <p>Unable to connect to send message</p></div>';
			endif;
	    	$mailer->ClearAllRecipients();

		} catch (Exception $e) {
		    // echo "EMAIL SENDING FAILED. INFO: " . $mailer->ErrorInfo;
		    $fail.='<div class="alert alert-warning alert-dismissible" role="alert" style=" position: absolute; top: 20%; margin: 10%; z-index: 1;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error 601:</h3> <p>Unable to connect to send message</p></div>';
		}
	else:
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error:</h3> '.$fail.'</div>';
	endif;
	$smarty->assign("posts", $posteds)->assign("msg", $fail);
	$fail='';
endif;


/*if (!empty($posts->downloadPdf) and $posts->downloadPdf=='download'):
    if(empty($posts->fullname)) {
        $global_err='<p>Invalid Full Name: Kindly enter your full name</p>';
    }
    if(empty($posts->useremail) or !filter_var($posts->useremail, FILTER_VALIDATE_EMAIL)) {
        $global_err.='<p>Invalid Email Address: Kindly a valid email</p>';
    }
    if(empty($posts->phonenumber)) {
        $global_err.='<p>Invalid Phone Number: Kindly enter a valid phone number</p>';
    }
    if (empty($global_err)) {
        require_once "mail_pdf.php";
        header("location:pdf.php");
    }else{
        $global_err='<div class="form-group">
            <div class="alert alert-info alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                 <h4 class="alert-heading">Error</h4>
                '.$global_err.'
            </div>
        </div>';
    }
endif;*/