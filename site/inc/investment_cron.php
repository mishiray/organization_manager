<?php 

    require_once 'mail_investment_cron.php';
    
    $investment_details=$ezDb->get_results("SELECT `product`,`email`,`surname`,`firstname`,`duration`,`principal`,`maturity`, DATEDIFF(NOW(), `reg_date`),`reg_date` from `investment_subscription` WHERE `paid` = 1 AND `status`= 0");
    $to_send = [];
    if(!empty($investment_details) and is_array($investment_details)):
        foreach ($investment_details as $value):
            $duration = "+".$value->duration ."months";
            $end_date = date('Y-m-d', strtotime($duration, strtotime($value->reg_date)));
            $value->end_date = date('Y-m-d', strtotime("-1 day", strtotime($end_date)));
            $value->isDone = (strtotime($value->end_date) < strtotime(date('Y-m-d'))) ? 'true' : 'false';

            $datetime1 = date_create($value->end_date);
            $datetime2 = date_create($dateNow);
            $interval = (array) date_diff($datetime2, $datetime1);

            if($interval['m'] == 0){
                if($interval['d'] == 20 || $interval['d'] == 10 || $interval['d']== 0){
                    array_push($to_send, $value);
                }
            }
        endforeach;
    endif;
   
    foreach($to_send as $investment){
        sendNotification2($investment->firstname,$investment->email,$investment->end_date,$investment->principal,$investment->maturity);
    }

?>