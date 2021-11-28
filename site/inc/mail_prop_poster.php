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

    $divElements='
		<h5>From '.$companyName.': Interest Request On Your Real Estate Post Titled ('.$restate->title.')</h5>
		<p>I '.$posts->names.' will be interested to discuss more over your real estate property ('.$restate->title.') <strong>'.$catTitle[$restate->category].'</strong> for '.$forProp[$restate->purchase].'.</p>
		<div class="row justify-content-center">
            <p> 
                <strong> Client Name: </strong> '.$posts->names.'
            </p>
            <p> 
                <strong> Client Number: </strong> '.$posts->phone.'
            </p>
            <p> 
                <strong> Client Email: </strong> '.$posts->email.'
            </p>
            <p> 
                <strong> Briefing: </strong> '.$comments.'
            </p>
		</div>
		Soucre: <i><a href="'.$domainName.'" target="__blank">'.$domainName.'</a></i> a product of <a href="http://hoffenheimtechnologies.com" target="__blank"><em class="text-secondary">HOFFENHEIM TECHNOLOGIES</em></a>
		</p>';

    $text="Sender : Lola & Clover Properties\n\nMessage: I` '.$posts->names.' will be interested to discuss more over your real estate property ('.$restate->title.') ".$catTitle[$restate->category]." for ".$forProp[$restate->purchase]."
\n\n
Client Name: $posts->names\n
Contact Number: $posts->phone\n
Client Email: $posts->email\n
Briefing: $comments
\n\nKindly find the client`s contact details as above.
\n\n Thanks".PHP_EOL;

    $mailer->Host = 'localhost';
    $mailer->SMTPDebug  = 0;
    $mailer->SMTPAuth = true;
    $mailer->Username = $mailConfig->auth["email"];
    $mailer->Password = $mailConfig->auth["password"];
    $mailer->SMTPSecure = 'tls';
    $mailer->Port = 587;

    $mailer->setFrom($mailConfig->contact["email"], $mailConfig->contact["title"]);
    $mailer->addAddress($restate->email, $mailConfig->contact["subject"]);

    $mailer->isHTML(true);
    $mailer->Subject = $mailConfig->contact["subject"]." ($restate->title)";
    $mailer->Body = '
        <html>
		    <head>
		      	<title>Real Estate</title>
		      	<link rel="stylesheet" href="http://ajahcity.com.ng/lib/common/bootstrap/css/bootstrap.min.css" />
				<link rel="stylesheet" href="http://ajahcity.com.ng/lib/common/font-awesome/css/font-awesome.min.css">
				<link rel="stylesheet" href="http://ajahcity.com.ng/lib/common/css/style.css" type="text/css" />
		    </head>
			<body>
      	  		'.$divElements.'
			</body>
		</html>';
	$mailer->AltBody = $text;
	// $mailer->addAttachment($attachment, 'Applicant_CV_Resume_'.$fileCVName); 

    $mailer->send();
    $mailer->ClearAllRecipients();
    // echo "MAIL HAS BEEN SENT SUCCESSFULLY";
    $fail='<div class="alert alert-success alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Property owner hand been notified by mail. Kindly drop us a message @ <a href="mailto:properties@atobe.ng" target="__blank"> properties@atobe.ng</a> for more inquiry</p></div>';

} catch (Exception $e) {
    // echo "EMAIL SENDING FAILED. INFO: " . $mailer->ErrorInfo;
    $fail.='<div class="alert alert-info alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Unable to notify property owner. Kindly drop us a message @ <a href="mailto:properties@atobe.ng" target="__blank"> properties@atobe.ng</a></p></div>';
}