<?php 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$developmentMode = true;
$mailer = new PHPMailer($developmentMode);
try {
    $mailer->SMTPDebug = 2;
    $mailer->isSMTP();
    $mailingDetail->divElements='<div class="container">
                                    <section class="row justify-content-center">
                                        <div class="col-md-7 card">
                                            <h4 class="card-heading bg-warning mt-2 text-center">
                                                <img class="img float-left" style="max-width: 100px;" src="'.$Site['siteProtocol'].''.$Site['domainName'].'/site/media/i/logo.png" alt="'.$Site['companyName'].'"> <h2 class="trans-title text-center">'.$Site['companyName'].' | '.$itemName.' Enrolment Receipt</h2>
                                            </h4>
                                            <div class="card-body row justify-content-center text-none">

                                                <div class="col-sm-6 float-left">
                                                    <h5 class="font-weight-bold">Course Detail</h5>
                                                    <h6>Program Title:  '.$programDetails->title.'</h6>
                                                    <h6>Program Type: '.$payment->purpose.'</h6>
                                                    <h6>Duration: '.$programDetails->duration.' months</h6>
                                                    '.( !empty($payment->discount)?('<h6>Discount: '.$payment->discount.'%</h6>'):'').'
                                                </div>
                                                <div class="col-sm-6 float-left">
                                                    <h5 class="font-weight-bold">Client Detail</h5>
                                                    <h6>Names: '.$userinfo->firstname.'  '.$userinfo->lastname.'</h6>
                                                    <h6>Email:  '.$userinfo->email.'</h6>
                                                    <h6>Phone No:  '.$userinfo->phone.'</h6>
                                                </div>
                                                <div class="col-sm-10 mb-2">
                                                    <div class="table-responsive">
                                                        <table class="table">
                                                            <caption>other information goes here</caption>
                                                            <thead class="bg-secondary">
                                                                <tr><th colspan="2" class="font-weight-bold">Transaction Detail</th></tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <th>Transaction ID:</th>
                                                                    <td>'.$payment->transid.'</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Transaction Date:</th>
                                                                    <td>'.$payment->transdate.'</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Payment Platform:</th>
                                                                    <td>'.$payment->gateway.'</td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Amount:</th>
                                                                    <td>$ '.$payment->amount.'</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 mt-2">
                                                    <p>Kindly login or click the link below to view this receipt in transaction history</p>
                                                    <h4><a href="'.$Site['siteProtocol'].''.$Site['domainName'].'/dashboard/receipt?id='.$sessions->regToken.'" target="__blank">Click Me</a></h4>
                                                </div>
                                            </div>
                                            <div class="card-footer text-none">
                                                <span>For more enquiry, contact us at <a href="mailto:contact@citysportsgroup.com">contact@citysportsgroup.com</a></span>
                                            </div>
                                        </div>
                                    </section>
                                </div>';

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

    $mailer->setFrom($mailConfig->payment["email"], $mailingDetail->title);
    $mailer->addAddress($userinfo->email, "$userinfo->firstname $userinfo->middlename $userinfo->lastname");

    $mailer->isHTML(true);
    $mailer->Subject = $Site['companyName'].': '.$mailingDetail->title;
    $mailer->Body = '<html>
	      <head>
	      	<title>'.$mailingDetail->title.'</title>
			'. $declURLS->styles .'
			<style>
				.trans-heading {
		            background-color: #b28525;
		        }
		        .trans-logo {
		            height: 100px;
		        }
		        .trans-title {
		            font-size: 1.2rem;
		            font-weight: 600;
		            padding: 2rem;
		        }
			</style>
	      </head>
      	  <body>
      	  	'.$mailingDetail->divElements.'
			'. $declURLS->scripts .'
      	  </body>
      </html>';

    $mailer->send();
    $mailer->ClearAllRecipients();
    // echo "MAIL HAS BEEN SENT SUCCESSFULLY";
    $fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p>'.$mailingDetail->success.'.<br/>Thanks!</p></div>';

} catch (Exception $e) {
    // echo "EMAIL SENDING FAILED. INFO: " . $mailer->ErrorInfo;
    $fail.='<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error 601:</h3> <p>Unable to send mail ('.$mailer->ErrorInfo.')</p></div>';
}