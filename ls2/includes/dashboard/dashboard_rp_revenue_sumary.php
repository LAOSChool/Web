<?php
ini_set('display_errors', 'Off');
error_reporting(0);
	
include("/var/www/html/cooking/config.php");

$config_revperbock = 200; //200 kip / 1 bock;

$datetime = $argv[1];

if($datetime!=''){
	$datetimes=explode(':',$datetime);
	$date = $datetimes[0];
	$hour = $datetimes[1];
}else{
	$date = date('Y-m-d');
	$hour = date('H');
}
				
function doitnow($date, $hour){
	global $db, $config_revperbock;

	$start = time();
	echo "Run for $date, $hour: ";
	$dates = explode('-',$date);
	
	$m = ltrim($dates[1],'0');
	$res = $db->query("SELECT '3000' as method, SUM(`duration_seconds`) as total_duration,COUNT(*) as total_call,date(start_time) as `date`,hour(start_time) as `hour` FROM `cdr` 
				WHERE DATE(start_time) = '$date' AND HOUR(start_time) = '$hour' GROUP BY DATE(start_time), HOUR(start_time)");

	$i = 0;
	while($res->fetchInto($row)){
		
		if($row['hour']=='') continue;
		$res2 = $db->query("select * from rp_revenue_sumary where `date` = '$row[date]' and `hour`='$row[hour]' and method = '$row[method]'");

		if($res2->numRows()==0){
			;
		}else{
			$res3 = $db->query("delete from rp_revenue_sumary where `date` = '$row[date]' and `hour`='$row[hour]' and method = '$row[method]'");
			if(PEAR::isError($res3)) die($res3->getຂໍ້​ຄວາມ()."-3");
		}

		$total_block = ceil($row['total_duration'] / 60);
		$total_revenue = $total_block * $config_revperbock;
		
		$res4 = $db->query("insert into rp_revenue_sumary(date, hour, method, total_duration,total_call, total_block, total_revenue) 
							values('$row[date]','$row[hour]','$row[method]','$row[total_duration]','$row[total_call]','$total_block','$total_revenue')");
		if(PEAR::isError($res4)) die($res4->getຂໍ້​ຄວາມ()."-4");
		$i++;
	};
	
	$end = time();
	$processtime = $end-$start;

	$start = date('Y-m-d h:i:s',$start);
	$end = date('Y-m-d h:i:s',$end);
	echo "From $start to $end with $i records in $processtime (s)\n";
}


if($argv[1]=='month'){
	$month = date('m')-1;
	$year = date('Y');
	$endmonth = date('t');
	for($i=1;$i<=$endmonth;$i++){
		for($j=0;$j<=23;$j++){
			doitnow("$year-$month-$i",$j);
		}
	}
	exit();
}

if($argv[1]=='day'){
	$date = $argv[2];
	//echo $date."xxx";
	if($date=='') $date = date('Y-m-d',strtotime('-1 day'));
	for($j=0;$j<=23;$j++){
		doitnow("$date",$j);
	}
	exit();
}

if( $hour>23 || $hour < 0 || count(explode('-',$date))!=3 || $hour=='' || $date==''){
	die ("Wrong time format. It must be: Year-Month-Date:Hour\nExample: 2012-03-30:18\n");
}

doitnow($date, $hour);
$dates = explode('-',$date);
$x = mktime($hour,0,0,$dates[1],$dates[2],$dates[0]) -3600;
$date = date('Y-m-d',$x);
$hour = date('H',$x);
doitnow($date, $hour);
?> 