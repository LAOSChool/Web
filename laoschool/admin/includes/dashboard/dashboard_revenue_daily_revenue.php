<?php
	include("../../config.php");
	
	$page = $_REQUEST['page'];
	
	if($page=='') $page = time();
	
	$mm=date('m',$page);
	$mon=date('F',$page);
	$yy = date('Y',$page);
	$tt = date('t',$page);
	if(date('Y-m')==date('Y-m',$page)) $tt=ltrim(date('d'),0);
	
	
	$data = array(array());

	for($i=1;$i<=$tt;$i++){
		$data[$i][0] = 0;
		$data[$i][1] = 0;
	}

	$next = $page + (30*24*60*60);
	$back = $page - (30*24*60*60);
	
	echo<<<eot
	<div class="pagination pagination-small">
		<ul>
			<li><a href="javascript:;" onclick="loadform('includes/dashboard/dashboard_revenue_daily_revenue.php?page=$back','#daily_revenue','#daily_load')"><i class="icon-chevron-left"></i></a></li>
			<li><a href="javascript:;" class='tooltips' data-original-title='Viewing report for $mon-$yy'>$mon, $yy</a></li>
			<li><a href="javascript:;" onclick="loadform('includes/dashboard/dashboard_revenue_daily_revenue.php?page=$next','#daily_revenue','#daily_load')"><i class="icon-chevron-right"></i></a></li>
			<li><span href="javascript:;" id='daily_load' class='hide'></span></li>
		</ul>

		
	</div>
	<script language='javascript'>
		$('.tooltips').tooltip();
	</script>
	
	<table class="table table-bordered table-hover">
	<thead> 
		<tr align=center> 
			<th>Date</th>
			<th>Total call</th>
			<th>Total duration</th>
			<th>Total block</th>
			<th>Total revenue</th>
		</tr> 
	</thead>
eot;

	$query = $db->query("SELECT SUM(total_call) AS `call`, SUM(total_block) AS `block`, SUM(total_revenue) AS `revenue`, SUM(total_duration) AS `duration`, monthname(date) AS `monthh`, day(date) AS `daay` FROM rp_revenue_sumary WHERE month(date) = '$mm' AND year(date)='$yy' GROUP BY date");
	
	
	while($row = $query->fetchRow()){
		$data[$row['daay']][0] = $row['call'];
		$data[$row['daay']][1] = $row['duration'];
		$data[$row['daay']][2] = $row['block'];
		$data[$row['daay']][3] = $row['revenue'];
	
	}
	
echo "<tbody>";
	$k=1;
	for($i=$tt;$i>=1;$i--){
		$d0 = number_format($data[$i][0],0,"."," ");
		$d1 = sec2hms($data[$i][1]);
		$d2 = number_format($data[$i][2],0,"."," ");
		$d3 = number_format($data[$i][3],0,"."," ");
		
		//if($d0!=0 && $d1!=0){
		
			if($k%2==0) $bg = "class='event'";
			else $bg = "class='odd'";
			$k++;
		
			echo "<tr $bg>
				<td align=left><b>$i - $mon - $yy</b></td>
				<td align=right>$d0</td>
				<td align=right>$d1</td>
				<td align=right>$d2</td>
				<td align=right>$d3</td>
			</tr>";
		//}
	};
echo "</tbody>";

echo "</table>";

?>
 