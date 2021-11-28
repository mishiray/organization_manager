<?php 

$whereClause="`id`!=0 AND DATE_FORMAT(`dateadded`,'%Y-%m-%d')";

/*if (empty($posts->filter) or !in_array($posts->table, array("finance","daily","monthly","yearly"))) {
	// $posts->filter="finances";
	switch ($posts->filter):
		case 'daily':
			$whereClause="`id`!=0 GROUP BY DATE_FORMAT(`dateadded`,'%Y-%m-%d')";
			break;
		case 'monthly':
			$whereClause="`id`!=0 GROUP BY DATE_FORMAT(`dateadded`, '%Y%m')";
			break;
		case 'yearly':
			$whereClause="`id`!=0 GROUP BY DATE_FORMAT(`dateadded`, '%Y')";
			break;
		default:
			$whereClause="`id`!=0";
			break;
	endswitch;
}*/
$Site['post']= (array) $posts;

$column="`expenses1`, `expenses`, `title`, `description`, `amount`, DATE_FORMAT(`dateadded`,'%Y-%m-%d') AS `recorddate`";
$records=tableRoutine("finances", "$column" , " `id`!=0 ", "DATE_FORMAT(`dateadded`,'%Y-%m-%d')", 'DESC', "`id`", 10);
if(!empty($records) and is_array($records)):
	$sumtotal=new stdClass();
	$sumtotal->credit=0.0;
	$sumtotal->debit=0.0;
	$sumtotal->balance=0.0;
	foreach ($records as $record):
		$record->expenses1=(empty($record->expenses1)? new stdClass(): @json_decode($record->expenses1));
		$record->expenses1->credittotal=(empty($record->expenses1->credittotal)? '0.0' : $record->expenses1->credittotal);
		$sumtotal->credit+=($record->expenses1->credittotal);
		$record->expenses1->debittotal=(empty($record->expenses1->debittotal)? '0.0': $record->expenses1->debittotal);
		$sumtotal->debit+=($record->expenses1->debittotal);
		$record->expenses1->grandtotal=(empty($record->expenses1->grandtotal)? '0.0': $record->expenses1->grandtotal);
		$record->totallog=0;
		$sumtotal->balance+=($record->expenses1->grandtotal);
		if( !empty($record->expenses1->balance) and is_array($record->expenses1->balance)):
			foreach ($record->expenses1->balance  as $value):
				if(!empty($record->expenses1->balance)):
					$record->totallog++;
				endif;
			endforeach;
		endif;
	endforeach;
endif;
$smarty->assign("records", $records)->assign("sumtotal", $sumtotal);