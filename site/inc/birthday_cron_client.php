<?php 

    require_once 'mail_birthday_cron.php';

    $birthdays = $ezDb->get_results("SELECT * from `subscription` WHERE `dob` <> '' AND DATE_FORMAT(dob, '%m-%d') = DATE_FORMAT(NOW(),'%m-%d') GROUP BY email ASC");
    
    foreach($birthdays as $birth){
        sendBirthday($birth->name,$birth->email);
    }

?>