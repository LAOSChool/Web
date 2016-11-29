<?php
	include("../../config.php");
	
	$page = $_REQUEST['page'];
	if($page=='') $page = time();
	
	$mm=date('m',$page);
	$mon=date('F',$page);
	$yy = date('Y',$page);
	$tt = date('t',$page);
	
	$next = $page + (31*24*60*60);
	$back = $page - (31*24*60*60);

	echo<<<eot
	<div class="pagination pagination-small">
		<ul>
			<li><a href="javascript:;" onclick="loadform('includes/dashboard/dashboard_revenue_hourly_revenue.php?page=$back','#hourly_revenue','#hourly_load')"><i class="icon-chevron-left"></i></a></li>
			<li><a href="javascript:;" class='tooltips' data-original-title='Viewing report for $mon-$yy'>$mon, $yy</a></li>
			<li><a href="javascript:;"  onclick="loadform('includes/dashboard/dashboard_revenue_hourly_revenue.php?page=$next','#hourly_revenue','#hourly_load')"><i class="icon-chevron-right"></i></a></li>
			<li><span href="javascript:;" id='hourly_load' class='hide'></span></li>
		</ul>
	</div>
	
	<table class="table table-bordered table-striped">
	<thead> 
		<tr style="font-weight:bold" align=center>
			<th>Date</th>
eot;
			for($i=0;$i<=23;$i++){
				if($i<10) $hh = "0$i";
				else $hh = $i;
				echo "<th>$hh</th>";
			}
		
		echo "</tr> </thead>";
	
	
	$data = array(array());
	
	for($j=$tt;$j>=1;$j--){
	//	echo "select total_call, hour, date from rp_revenue_sumary where date ='$yy-$mm-$j' order by hour asc";
		$query = $db->query("SELECT total_revenue, hour, date FROM rp_revenue_sumary WHERE date ='$yy-$mm-$j' ORDER BY hour ASC");
		while($row=$query->fetchRow()){
			$data[$row['date']][$row['hour']] = $row['total_revenue'];
		}
	};
	
echo "<tbody>";
	foreach($data as $key=>$datas){
		if($key!=0){
			$keys = explode('-',$key);
			$key = $keys[2];
			echo "<tr>";
				echo "<td><b>$key</b></td>";
				for($i=0;$i<=23;$i++){
					$datas[$i] = ($datas[$i]=='')?0:$datas[$i];
					$d = $datas[$i];
					$hh = ($i<10)?"0$i":$i;
					$class = ($i==date('G') && $key == date('d'))?"class='odd'":"";
					
					echo "<td><a href='javascript:;' class='tooltips' data-original-title='Revenue ($key - $mon - $yy) $hh: $d'>$d</a></td>";
				}
			echo "</tr>";
		}
	}
echo "</tbody>";

echo "</table>";

?>
<script language='javascript'>
	$('.tooltips').tooltip();
</script>
