<?php

require_once "Mail.php"; // PEAR Mail package
require_once ('Mail/mime.php'); // PEAR Mail_Mime packge


$from = $posts->email; //enter your email address
$to = $project->email; //enter the email address of the contact your sending to
$subject = $companyName.' Contact'; // subject of your email
$recipients=$to ;


$headers = array ('From' => $from, 'Subject' => $subject);

$text = "Sender: $posts->names\nSender Phone Number: $posts->phone\n$posts->comment\n\n".PHP_EOL; // text versions of email.
$html = '<html>
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
        <h5>Sender: '.$posts->names.' </h5>
        <h5>Sender Phone Number: '.$posts->phone.' </h5>
        <p>'.$posts->comment.'</p>
      </div>
    <script src="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/jquery/jquery-3.3.1.js"></script>
    <script src="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/bootstrap/bootstrap.min.js"></script>
    <script src="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/js/script.js"></script>
  </body>
</html>'; // html versions of email.

$crlf = "\n";

$mime = new Mail_mime($crlf);
$mime->setTXTBody($text);
$mime->setHTMLBody($html);
/*if (!empty($files->file_upload['tmp_name']) and file_exists($files->file_upload['tmp_name'])) {
  $mime->addAttachment($files->file_upload['tmp_name']);
}*/

//do not ever try to call these lines in reverse order
$body = $mime->get();
$headers = $mime->headers($headers);
$host = "localhost"; // all scripts must use localhost
$username = $mailConfig->auth["email"]; //  your email address (same as webmail username)
$password = $mailConfig->auth["password"]; // your password (same as webmail password) remember to change me

$smtp = Mail::factory('smtp', array ('host' => $host, 'auth' => true,
'username' => $username,'password' => $password));

$mail = $smtp->send($recipients, $headers, $body);

if (PEAR::isError($mail)) :
  $fail='<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error 601:</h3> <p>Unable to connect to send message</p></div>';
    // error_log($mail->getMessage());
else:
 $fail='<div class="alert alert-success alert-dismissible" role="alert" style="position: absolute; z-index: 9999; vertical-align: middle; align-self: center; width: 70% !important; top: 140px; margin-left: 15%;"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><p class="border border-success p-1 rounded"> Your message was successfully sent.</p></div>';
endif;