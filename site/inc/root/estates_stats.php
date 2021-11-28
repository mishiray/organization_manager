<?php 
if( !in_array( $userinfo->userrole, array('level0','level1', 'level2'))  && !in_array($userinfo->department, array('Accounting','Administrative'))):
	redirect("home");
endif;

$whereClause="";
//$events=$ezDb->get_results("SELECT * from `projects` where $whereClause ORDER BY `id` DESC ");
$maxestate=$ezDb->get_row("SELECT * FROM `projects` AS p INNER JOIN ( SELECT `product`, SUM(total_amount) as total_amount_paid FROM `subscription` WHERE `paid` = 1 GROUP BY `product` ORDER BY `total_amount_paid` DESC LIMIT 1) s ON s.`product` = p.`name`");

$estates=$ezDb->get_results("SELECT s.`product`, SUM(plot_number) AS t_plot, SUM(total_amount) AS t_amount, p.`totalplot`, p.`totalsold` FROM `subscription` AS s INNER JOIN `projects` AS p ON s.`product` = p.`name` WHERE s.`paid` = 1 GROUP BY s.`product`");
if(!empty($estates)){
    foreach($estates as $value){
        $value->available = $value->totalplot - $value->totalsold;
    }
}

$smarty->assign("stats", $estates)->assign("maxestate",$maxestate);
