<?php
	include("../../config.php");
	header('Content-Type: application/force-download');
	header('Content-Disposition: attachment;filename="revenue_daily('.date('d').'-'.date('m').'-'.date('Y').').csv"');
	header('Cache-Control: max-age=0');

	$mm=date('m');
	$mon=date('F');
	$yy = date('Y');
	$tt = date('t');
	
	$data = array(array());

	for($i=1;$i<=$tt;$i++){
		$data[$i][0] = 0;
		$data[$i][1] = 0;
	}
	
	echo "Date,Total Register,Total Unregister,Total Charge Ok,Total Revenue\n";

	$query = mysql_query("select sum(total_register) as total_register, sum(total_unregister) as total_unregister, sum(total_charge_ok) as total_charge_ok, sum(total_revenue) as total_revenue, monthname(date) as monthh, day(date) as daay from rp_subs_revenue_sumary where month(date) = '$mm' and year(date)='$yy' group by date") or die(mysql_error());
	
	
	
	while($row = mysql_fetch_array($query)){

		$data[$row['daay']][0] = $row['total_register'];
		$data[$row['daay']][1] = $row['total_unregister'];
		$data[$row['daay']][2] = $row['total_charge_ok'];
		$data[$row['daay']][3] = $row['total_revenue'];

		//echo $data[$row['daay']][1]."<br>";
	}

	$k=1;
	for($i=$tt;$i>=1;$i--){
		$d0 = $data[$i][0];
		$d1 = $data[$i][1];
		$d2 = $data[$i][2];
		$d3 = $data[$i][3];
		
		if($d0!=0){
		
			if($k%2==0) $bg = "class='event'";
			else $bg = "class='odd'";
			$k++;
		
			echo "$i - $mon - $yy,$d0,$d1,$d2,$d3\n";
		}
	};
?>