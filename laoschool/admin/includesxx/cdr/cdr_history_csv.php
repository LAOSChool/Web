<?php
include("../../config.php");
ensure_permission('cdr');
ensure_role('sadmin,admin');


$date = date('Y').date('m').date('d');
header("Content-Type: application/force-download"); 
header("Content-Disposition: attachment; filename=CDR_history_$date.csv");
	

function sec2hms($sec, $padHours = false){
	$hms = "";
	$hours = intval(intval($sec) / 3600); 
	$hms .= ($padHours) 
		  ? str_pad($hours, 2, "0", STR_PAD_LEFT). ":"
		  : $hours. ":";
	$minutes = intval(($sec / 60) % 60); 
	$hms .= str_pad($minutes, 2, "0", STR_PAD_LEFT). ":";
	$seconds = intval($sec % 60); 
	$hms .= str_pad($seconds, 2, "0", STR_PAD_LEFT);
	return $hms;
}

// Get condition
$msisdn = $_REQUEST['msisdn'];
$description = $_REQUEST['description'];
// Get date
$startdate = $_REQUEST['startdate'];
$enddate = $_REQUEST['enddate'];
if($startdate=="From") $startdate = "";
if($enddate=="To") $enddate = "";

//msisdn Query
if ($msisdn!=""){
	$msisdnquery = "msisdn like '%$msisdn%' ";
}else{
	$msisdnquery = "1";
}

//description Query
if ($description!=""){
	$desquery = "description like '%$description%' ";
}else{
	$desquery = "1";
}

//Time Query
if ($startdate!="" && $enddate!=""){
	$timequery = "date(cdr_start) >= '$startdate' and date(cdr_start) <= '$enddate'";
}elseif($enddate!=""){
	$timequery = "date(cdr_start) <= '$enddate'";
}elseif($startdate!="" ){
	$timequery = "date(cdr_start) >= '$startdate'";		
}else{
	$timequery = "1";
}
if ($startdate > $enddate) {
	$err .= " - To date can not less than From date";
	$timequery = "1";
}

	$res = $db->query("select * from cdr where ($msisdnquery) and ($timequery) and service <>'128' order by cdr_start desc");
	echo "Phone number,Date time,Duration,Service,Branch,Key log\n";
	while($res->fetchInto($row)){
		echo "\"$row[msisdn]\",\"$row[cdr_start]\",\"$row[duration]\",\"$row[service]\",\"$row[branch]\",\"$row[key_log]\"\n";
	}
?>