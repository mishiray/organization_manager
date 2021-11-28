<?php 

    require_once 'mail_anniversary_cron.php';

    $anniv = $ezDb->get_results("SELECT `firstname`,`email` from `userprofile` WHERE DATE_FORMAT(datehired, '%m-%d') = DATE_FORMAT(NOW(),'%m-%d') AND `active` = 1  GROUP BY email ASC");
    
    foreach($anniv as $birth){
        sendanniv($birth->firstname,$birth->email);
    }

?>