<?php 

    require_once 'mail_client_cron.php';
    
    $clients = $ezDb->get_results("SELECT `email`,`firstname`,`lastname` FROM `userprofile` WHERE `usertype` = 'client' AND `password`= 'cGFzc3dvcmQ=' AND `verified` = 1 ");

    foreach($clients as $client){
        sendClientNotification($client->firstname, $client->email);
    }

?>