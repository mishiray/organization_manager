<?php

require_once "Mail.php"; // PEAR Mail package
require_once ('Mail/mime.php'); // PEAR Mail_Mime packge


$from = $mailConfig->newsletter["email"]; //enter your email address
$to = $newsletters[0]->email; //enter the email address of the contact your sending to
$subject = $companyName.' Newsletter'; // subject of your email
$recipients="";

foreach ($newsletters as $key => $newsletter) {
  $recipients.=($key>0? ", $newsletter->email": "$newsletter->email");
    // $mailer->addAddress($newsletter->email, (empty($newsletter->names)? 'Newsletter Subscriber': $newsletter->names));
}

$headers = array ('From' => $from, 'Subject' => $subject);

$text = "$posts->title\n$posts->content\n".(empty($posts->author)?'': "Author: $posts->author")."\n".PHP_EOL; // text versions of email.
$html = '<html>
     <head>
      <title>Newsletter</title>
      <link rel="stylesheet" href="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/bootstrap/bootstrap.min.css" />
      <link rel="stylesheet" href="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/font-awesome/css/font-awesome.min.css">
      <link rel="stylesheet" href="'.$Site['siteProtocol'].$Site['domainName'].'/lib/common/css/style.css" type="text/css" />
      <style>
      </style>
    </head>
    <body>
      <div class="container">
        <h5>'.$posts->title.' </h5>
        <div>'.$posts->content_stripe.'</div>
        '.(empty($posts->author)?'': "<strong>Author: $posts->author</strong>").'
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
if (!empty($files->file_upload['tmp_name']) and file_exists($files->file_upload['tmp_name'])) {
  $mime->addAttachment($files->file_upload['tmp_name']);
}

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
  $fail='<div class="alert alert-warning alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><h3>Error 601:</h3> <p>Unable to connect to send message</p><p>'. $mail->getMessage() .'</p></div>';
else:
  $fail='<div class="alert alert-success alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> <p class="border badge-pill border-success"> newsletter was successfully mailed.</p></div>';
endif;