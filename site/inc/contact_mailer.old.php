<?php global $sitePage;

$posteds =(object)$Site['post'];

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
			//recipient
			$to = "testmail@ajahcity.com.ng";

			//sender
			$from = $posteds->email;
			$fromName = $posteds->names;

			//email subject
			$subject = $posteds->subject; 


			//email body content
			$htmlContent = "<html><body>Name: $posteds->name <br> Email: $from <br> Message: $posteds->message <br></body></html>".PHP_EOL;

			//header for sender info
			$headers = "From: $fromName"." <".$from.">";

			$prefix     = "part_"; // This is an optional prefix
			/* Generate a unique boundary parameter value with our
			prefix using the uniqid() function. The second parameter
			makes the parameter value more unique. */
			$mime_boundary   = uniqid($prefix, true);

			//headers for attachment 
			$headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\""; 

			//multipart boundary 
			$message = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"UTF-8\"\n" .
			"Content-Transfer-Encoding: 8bit\n\n" . $htmlContent . "\n\n"; 

			//preparing attachment
			$message    = implode("\r\n", [ 
			    "--" . $mime_boundary, // header boundary delimiter line
			    'Content-Type: text/html; charset="iso-8859-1"',
			    "Content-Transfer-Encoding: 8bit",
			    $message,
			   /* "--" . $boundary, // content boundary delimiter line
			    'Content-Type: application/octet-stream; name="RenamedFile.pdf"',
			    "Content-Transfer-Encoding: base64",
			    "Content-Disposition: attachment",
			    $content,*/
			    "--" . $mime_boundary . "--" // closing boundary delimiter line
			]);
			$returnpath = "-f" . $from;

			//send email
			$mail = @mail($to, $subject, $message, $headers, $returnpath); 
		if (!$mail) :
			$fail='<div class="alert alert-warning alert-dismissible" role="alert" style=" position: absolute; top: 20%; margin: 10%; z-index: 1;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error 601:</h3> <p>Unable to connect to send message</p></div>';
		else:
			if (!empty($subDet=$ezDb->get_row("SELECT * FROM `newsletter` WHERE `email`='$posteds->email'"))) {
				$ezDb->query("UPDATE `newsletter` SET `names`='$posteds->names', `message`='$posteds->message', `subject`='$posteds->subject', `dateupdated`='$dateNow' WHERE `email`='$posteds->email'");
			}else{
				$ezDb->query("INSERT INTO `newsletter` (`names`, `message`, `subject`, `dateupdated`, `email`) VALUES ('$posteds->names', '$posteds->message', '$posteds->subject', '$dateNow', '$posteds->email');");
			}
			$fail='<div class="alert alert-success alert-dismissible" role="alert" style=" position: absolute; top: 20%; margin: 10%; z-index: 1;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Your message had been successfully sent.<br/>Thanks!</p></div>';
		endif;
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
		//recipient
		$to = "testmail@ajahcity.com.ng";
		//sender
		$from = $posteds->email;
		//email subject
		$subject = "Newsletter Subscription"; 
		//email body content
		$htmlContent = "<html><body>This is to notify that the  email: $from had just recently subscribe to your receive a newsletter form your website <br> a href='$siteProtocol".$domainName."' <br></body></html>".PHP_EOL;
		//header for sender info
		$headers = "From: Newsletter@$companyName"." <".$from.">";
		$prefix     = "part_"; // This is an optional prefix
		$mime_boundary   = uniqid($prefix, true);
		//headers for attachment 
		$headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\""; 
		//multipart boundary 
		$message = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"UTF-8\"\n" .
		"Content-Transfer-Encoding: 8bit\n\n" . $htmlContent . "\n\n"; 
		//preparing attachment
		$message    = implode("\r\n", [ 
		    "--" . $mime_boundary, // header boundary delimiter line
		    'Content-Type: text/html; charset="iso-8859-1"',
		    "Content-Transfer-Encoding: 8bit",
		    $message,
		    "--" . $mime_boundary . "--" // closing boundary delimiter line
		]);
		$returnpath = "-f" . $from;
		//send email
		$mail = @mail($to, $subject, $message, $headers, $returnpath); 
		if (!$mail) :
			$fail='<div class="alert alert-warning alert-dismissible" role="alert" style=" position: absolute; top: 20%; margin: 10%; z-index: 1;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error 601:</h3> <p>Unable to connect to send message</p></div>';
		else:
			if (!empty($ezDb->get_row("SELECT * FROM `newsletter` WHERE `email`='$posteds->email'"))) {
				$ezDb->query("UPDATE `newsletter` SET `status`='1', `dateupdated`='$dateNow' WHERE `email`='$posteds->email'");
			}else{
				$ezDb->query("INSERT INTO `newsletter` (`status`, `dateupdated`, `email`) VALUES ('1', '$dateNow', '$posteds->email');");
			}
			$fail='<div class="alert alert-success alert-dismissible" role="alert" style=" position: absolute; top: 20%; margin: 10%; z-index: 1;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>Your newsletter subscription request is successfull.<br/>Thanks!</p></div>';
		endif;
	else:
		$fail='<div class="alert alert-danger alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error:</h3> '.$fail.'</div>';
	endif;
	$smarty->assign("posts", $posteds)->assign("msg_nl", $fail);
	$fail='';
endif;