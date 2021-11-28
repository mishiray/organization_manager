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
	
	$mailer->setFrom("hr@$domainName"," ");
	$mailer->addAddress($jobDetails->email, $mailConfig->jobs["subject"]);

    $mailer->isHTML(true);
    $mailer->Subject = $mailConfig->jobs["subject"]." ($jobDetails->jobtitle)";
    $mailer->Body = '<html>
	     <head>
	      	<title>Jobs</title>
	      	<link rel="stylesheet" href="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/bootstrap/bootstrap.min.css" />
			<link rel="stylesheet" href="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/font-awesome/css/font-awesome.min.css">
			<link rel="stylesheet" href="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/css/style.css" type="text/css" />
			<style>
			</style>
	      </head>
      	  <body>
      	  	<div class="container">
	      	  	<div class="card">
				  <div class="card-header" style="color: #fff !important; background-color: #4268d6; padding: 10px;">
				    <h3>Job Application</h3>
				  </div>
				  <h5>From '.$companyName.': Job Application Request ('.$jobDetails->jobtitle.')</h5>
					<p>An Applicant had recently request to fill the position titled ('.$jobDetails->jobtitle.') of your job post.</p>
					<div class="row justify-content-center">
						<div class="col-sm-8 table-responsive">
							<table class="table table-striped table-hover">
								<tbody>
									<tr>
										<th>Applicant Name: </th><td>'.ucwords($posts->names).'</td>
									</tr>
									<tr>
										<th>Contact Number: </th><td>'.$posts->phone.'</td>
									</tr>
									<tr>
										<th>Applicant Email: </th><td>'.$posts->email.'</td>
									</tr>
									<tr>
										<th>Briefing: </th><td>'.$posts->comment.'</td>
									</tr>
									<tr>
										<th>Attachment: </th><td> <a target="_BLANK" href="'.$Site['siteProtocol'].$Site['domainName'].'/'.$targetFile.'">Attachment Here</a> </td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<p><strong>Kindly find the applicant details and CV/Resume attachement as below for review.</strong><p>
					<p>    Soucre: <i>
									<a href="https://britproperties.ng" target="__blank">
										https://britproperties.ng
									</a>
								</i> a product of 
								<a href="http://hoffenheimtechnologies.com" target="__blank">
									<em class="text-secondary">HOFFENHEIM TECHNOLOGIES</em>
								</a>
					</p>
				</div>
      	  	</div>
			<script src="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/jquery/jquery-3.3.1.js"></script>
			<script src="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/bootstrap/bootstrap.min.js"></script>
			<script src="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/js/script.js"></script>
      	  </body>
	  </html>';
	$text = ' Applicant Name: '.ucwords($posts->names).'
	Contact Number: '.$posts->phone.'
	Applicant Email: '.$posts->email.'
	Briefing: '.$posts->comment;
	//$mailer->Body = 'Message to be sent';
	$mailer->AltBody ="Sender : hr@$domainName\n\nMessage: ".$text.PHP_EOL;
	//$mailer->addAttachment($attachment, 'Applicant_CV_Resume_'.$fileCVName); 
	
	if (!empty($files->cv['tmp_name']) and file_exists($files->cv['tmp_name'])) {
		$mailer->addAttachment($files->cv['tmp_name'],$files->cv['name']);
		//$mime->addAttachment($files->file_upload['tmp_name']);
	}
	//error_log($mailer->Body);

	if ($mailer->send()) :
		$fail='<div class="alert alert-success alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p> Job post has been notified by mail. Kindly drop us a message @ <a href="mailto:hr@britproperties.ng" target="__blank"> hr@britproperties.ng</a> for more inquiry</p></div>';
	else:
		$fail='<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error 601:</h3><p>Unable to connect to send message '. $mailer->ErrorInfo.'</p></div>';
	endif;
	$mailer->ClearAllRecipients();
	//echo $fail;
} catch (Exception $e) {
    // echo "EMAIL SENDING FAILED. INFO: " . $mailer->ErrorInfo;
    $fail.='<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error 601:</h3> <p>Unable to connect to send message '. $mailer->ErrorInfo.'</p></div>';
}
