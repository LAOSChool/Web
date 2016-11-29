<?php
include("../../config.php");


ensure_permission('ttb');
ensure_role('mod,sadmin,admin');

$filter_class_id = $_REQUEST['filter_class_id'];
if($filter_class_id=='') die("Please select Class");

$page = $_REQUEST['page'];
$limit = $_REQUEST['limit'];

if( $limit =='') $limit = 20;
if($page=='') $page = 1;
$pages = ($page-1) * $limit;

$requestparam .= "";
foreach($_REQUEST as $key=>$value){
	$requestparam .= "$key=$value&";
}
$requestparam = substr($requestparam,0,-1);

for($i=5;$i<=51;$i=$i*2){
	if($i==$limit) $sss = "selected";
	else $sss = "";
	$slt .= "<option $sss value='$i'>$i</option> \n";
}

$auth_key = $_SESSION[$config_session]['auth_key'];

$headers = array();
$headers[] = "auth_key: $auth_key";
$headers[] = "api_key: TEST_API_KEY";

$gets['from_row'] = $pages;
$gets['max_result'] = $limit;
$gets['filter_class_id'] = $filter_class_id;
$gets['filter_year_id'] = 1;

$ttbapi = callapi($headers,'',$gets,"api/timetables");
$ttbdatas = explode("\n",$ttbapi['output']);
$ttbdata = json_decode($ttbdatas[14]);

$trmaip = callapi($headers,'',$gets,'api/schools/terms');
$trmdatas = explode("\n",$trmaip['output']);
$trmdata = json_decode($trmdatas[14]);

$sesaip = callapi($headers,'',$gets,'api/masters/m_session');
$sesdatas = explode("\n",$sesaip['output']);
$sesdata = json_decode($sesdatas[14]);

$wkdaip = callapi($headers,'',$gets,'api/sys/sys_weekday');
$wkddatas = explode("\n",$wkdaip['output']);
$wkddata = json_decode($wkddatas[14]);

//print_r($wkddata->messageObject->list);
//print_r($ttbdata->list[0]);

foreach($trmdata->messageObject as $term_vals){
	
	echo "<h3>Term $term_vals->term_val</h3>";
	echo '<div class="dataTables_wrapper form-inline">
	<table class="table table-bordered dataTable">
		<thead>
			<tr style="background:#F5F5F5">
				<th style="width:12.5% !important"></th>';
		for($i=1;$i<=7;$i++){
			$z=0;
			foreach($wkddata->messageObject->list as $lists){
				if($lists->id==$i){
					echo "<td><b>$lists->sval</b></td>";
					$z=1;
					break;
				}
			}
			if($z==0) echo "<td>$i</td>";
		}
	echo '</tr>';
	echo "</thead>";
	for($i=1;$i<=12;$i++){
		echo "<tr>";
		$z=0;
		foreach($sesdata->messageObject->list as $lists){
			if($lists->id==$i){
				echo "<td style='background:#F5F5F5;text-align:center'><b>$lists->sval</b><br>$lists->notice</td>";
				$z=1;
				break;
			}
		}
		if($z==0) echo "<td>-</td>";
		
		for($j=1;$j<=7;$j++){
			$z=0;
			foreach($ttbdata->list as $lists){
				if($term_vals->term_val==$lists->term_val){
					if($lists->session_id==$i && $lists->weekday_id==$j){
						echo "<td style='text-align:center' class='editbutton' id='editbutton' qtable='$lists->class_id' qid='$lists->id'><b>$lists->subject</b><br>$lists->teacher_name</td>";
						$z=1;
					}
				}
			}
			if($z==0) echo "<td id='addbutton' class='addbutton' qtable='$filter_class_id|$j|$i|$term_vals->term_val'></td>";
		}
		echo "</tr>";
	}
	echo "</thead>";
	echo "</table>";
}


echo <<<eot
<script language='javascript'>
	$('.changepage').click(function(){
		loadform('includes/ttb/ttb_manager_db.php?$requestparam&page='+$(this).attr('page')+'&limit=$limit','#entry','#pageloadtext');
	});
	
	$("select[name=limit]").change(function() {
		loadform('includes/ttb/ttb_manager_db.php?$requestparam&page=1&limit='+$(this).val(),'#entry','#limitloadtext')
	});
	
	$('.editbutton, .addbutton').css('cursor','pointer');
	$('.editbutton, .addbutton').hover(function(){
		$(this).css("background","#F5F5F5");
	},function(){
		$(this).css("background","");
	});
	
	$('.tooltips').tooltip();
</script>
eot;
?>