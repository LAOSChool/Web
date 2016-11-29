<?php
include("../../config.php");
$page = $_REQUEST['page'];
    if($page=='') $page = time();
    
    $month=date('m',$page);
    $mon=date('F',$page);
    $yy=date('Y',$page);
    $year=date('Y',$page);
    $tt = date('t', mktime(0,0,0,$month,1,$year));
    
    $next = $page + (30*24*60*60);
    $back = $page - (30*24*60*60);
///////////////////////////////////////////////////////////
$from = date('n',strtotime('-1 month')); ////
$to = date('n');
$y1 = date('Y',strtotime('-1 month')); /////////
$y2 = date('Y');
//echo "$from $to $y1 $y2";
$qqq = array();

if($y1==$y2){
		for($i=$to;$i>=$from;$i--) $qqq[] = "$i-$y1";
}else{
	for($i=$to;$i>=1;$i--) $qqq[] = "$i-$y2";
	for($i=12;$i>=$from;$i--) $qqq[] = "$i-$y1";
}
$data = array(array(array()));


$i=0;
foreach($qqq as $qq){	
	$qqs = explode('-',$qq);
	$qqs = explode('-',$qq);
	
	$mm = date('m',mktime(0, 0, 0, $qqs[0], 1, $qqs[1]));
	$y = $qqs[1];


	$query = $db->query("SELECT sum(total_duration) AS `duration`, sum(total_block) AS `block`, sum(total_call) AS `call`, sum(total_revenue) AS `revenue`, monthname(`date`) AS `monthh`, `date` AS `daay` FROM rp_revenue_sumary WHERE month(date) = '$month' AND year(date)='$year'");
	
	$t1 = 0;$t2 = 0;$t3 = 0;$t4 = 0;$t5 = 0;$t = 0;
	$tb1 = 0;$tb2 = 0;$tb3 = 0;$tb4 = 0;$tb5 = 0;$tb = 0;
	$t6691 = 0;$t6692 = 0;$t6693 = 0;$t6694 = 0;$t6695 = 0;$t669 = 0;
	
	if($query->numRows()==0) continue;
	
	
	while($row = $query->fetchRow()){
		if("$year-$month-01" <= $row['daay'] && $row['daay']<="$y-$month-07"){
			$c1 = $row['call'] + $c1;
			$d1 = $row['duration'] + $d1;
			$b1 = $row['block'] + $b1;
			$r1 = $row['revenue'] + $r1;
		}
		
		if("$year-$month-08" <= $row['daay'] && $row['daay']<="$year-$month-14"){
			$c2 = $row['call'] + $c2;
			$d2 = $row['duration'] + $d2;
			$b2 = $row['block'] + $b2;
			$r2 = $row['revenue'] + $r2;
		}
		
		if("$year-$month-15" <= $row['daay'] && $row['daay']<="$year-$month-21"){
			$c3 = $row['call'] + $c3;
			$d3 = $row['duration'] + $d3;
			$b3 = $row['block'] + $b3;
			$r3 = $row['revenue'] + $r3;
		}
		
		if("$year-$month-22" <= $row['daay'] && $row['daay']<="$year-$month-28"){
			$c4 = $row['call'] + $c4;
			$d4 = $row['duration'] + $d4;
			$b4 = $row['block'] + $b4;
			$r4 = $row['revenue'] + $r4;
		}
		
		if("$year-$month-29" <= $row['daay']){
			$c5 = $row['call'] + $c5;
			$d5 = $row['duration'] + $d5;
			$b5 = $row['block'] + $b5;
			$r5 = $row['revenue'] + $r5;
		}
		
		$c = $row['call'] + $c;
		$d = $row['duration'] + $d;
		$b = $row['block'] + $b;
		$r = $row['revenue'] + $r;

		$mmm = $row['monthh'];
		//echo $mm;
	};
	
	$c1 = number_format($c1,0,"."," ");
	$c2 = number_format($c2,0,"."," ");
	$c3 = number_format($c3,0,"."," ");
	$c4 = number_format($c4,0,"."," ");
	$c5 = number_format($c5,0,"."," ");
	$c = number_format($c,0,"."," ");

	$d1 = sec2hms($d1);
	$d2 = sec2hms($d2);
	$d3 = sec2hms($d3);
	$d4 = sec2hms($d4);
	$d5 = sec2hms($d5);
	$d = sec2hms($d);
	
	$b1 = number_format($b1,0,"."," ");
	$b2 = number_format($b2,0,"."," ");
	$b3 = number_format($b3,0,"."," ");
	$b4 = number_format($b4,0,"."," ");
	$b5 = number_format($b5,0,"."," ");
	$b = number_format($b,0,"."," ");
	
	$r1 = number_format($r1,0,"."," ");
	$r2 = number_format($r2,0,"."," ");
	$r3 = number_format($r3,0,"."," ");
	$r4 = number_format($r4,0,"."," ");
	$r5 = number_format($r5,0,"."," ");
	$r = number_format($r,0,"."," ");
	
	
	$last = date('t',mktime(0, 0, 0, $month, 1, $year));
		
	$data["$year-$mmm"]['Total Call'][1] = $c1;
	$data["$year-$mmm"]['Total Call'][2] = $c2;
	$data["$year-$mmm"]['Total Call'][3] = $c3;
	$data["$year-$mmm"]['Total Call'][4] = $c4;
	$data["$year-$mmm"]['Total Call'][5] = $c5;
	$data["$year-$mmm"]['Total Call'][0] = $c;
	
	$data["$year-$mmm"]['Total Duration'][1] = $d1;
	$data["$year-$mmm"]['Total Duration'][2] = $d2;
	$data["$year-$mmm"]['Total Duration'][3] = $d3;
	$data["$year-$mmm"]['Total Duration'][4] = $d4;
	$data["$year-$mmm"]['Total Duration'][5] = $d5;
	$data["$year-$mmm"]['Total Duration'][0] = $d;
	
	$data["$year-$mmm"]['Total Block'][1] = $b1;
	$data["$year-$mmm"]['Total Block'][2] = $b2;
	$data["$year-$mmm"]['Total Block'][3] = $b3;
	$data["$year-$mmm"]['Total Block'][4] = $b4;
	$data["$year-$mmm"]['Total Block'][5] = $b5;
	$data["$year-$mmm"]['Total Block'][0] = $b;
	
	$data["$year-$mmm"]['Total Revenue'][1] = $r1;
	$data["$year-$mmm"]['Total Revenue'][2] = $r2;
	$data["$year-$mmm"]['Total Revenue'][3] = $r3;
	$data["$year-$mmm"]['Total Revenue'][4] = $r4;
	$data["$year-$mmm"]['Total Revenue'][5] = $r5;
	$data["$year-$mmm"]['Total Revenue'][0] = $r;
};


echo<<<eot
	<div class="pagination pagination-small">
        <ul>
            <li><a href="javascript:;" onclick="loadform('includes/dashboard/dashboard_revenue_weekly_revenue.php?page=$back','#weekly_revenue','#weekly_load')"><i class="icon-chevron-left"></i></a></li>
            <li><a href="javascript:;" class='tooltips' data-original-title='Viewing report for $mon-$yy'>$mon, $yy</a></li>
            <li><a href="javascript:;" onclick="loadform('includes/dashboard/dashboard_revenue_weekly_revenue.php?page=$next','#weekly_revenue','#weekly_load')"><i class="icon-chevron-right"></i></a></li>
            <li><span href="javascript:;" class='hide' id='weekly_load'></span></li>
        </ul>
    </div>
    <script language='javascript'>
        $('.tooltips').tooltip();
    </script>
<table class="table table-bordered">
	<thead> 
		<tr> 
			<th colspan=2>Revenue</th>
			<th width=13%>1-7</th>
			<th width=13%>8-14</th>
			<th width=13%>15-21</th>
			<th width=13%>22-28</th>
			<th width=13%>29-end</th>
			<th width=13%>TOTAL</th>
		</tr> 
	</thead> 
eot;


echo "<tbody>";

	foreach($data as $key=>$datas){
		
		if($key!=0){
			$i=0;

			foreach($datas as $keys=>$datass){
				if($i==0) echo "<tr><td rowspan=4><b>$key</b></td>";
				else echo "<tr>";
				echo "<td width=11%><b>$keys</b></td>";
				echo "<td align=right>$datass[1]</td>";
				echo "<td align=right>$datass[2]</td>";
				echo "<td align=right>$datass[3]</td>";
				echo "<td align=right>$datass[4]</td>";
				echo "<td align=right>$datass[5]</td>";
				echo "<td align=right>$datass[0]</td>";
				echo "</tr>";
				$i++;
			};
		}
	}
echo "</tbody>";
echo "</table>";
?>