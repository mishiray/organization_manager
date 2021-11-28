<?php 

    require_once 'mail_birthday_cron_employee.php';

    $birthdays = $ezDb->get_results("SELECT * from `employees` WHERE  `dob` <> '' AND DATE_FORMAT(dob, '%m-%d') = DATE_FORMAT(NOW(),'%m-%d') ORDER BY email ASC");
    
    foreach($birthdays as $birth){
        sendBirthdayEmployee($birth->name,$birth->email);
    }

?>