<?php
include("../../config.php");

ensure_permission('rnk');
ensure_role('mod,sadmin,admin');

$page = $_REQUEST['page'];
$limit = $_REQUEST['limit'];
$filter_class_id = $_REQUEST['filter_class_id'];
$ex_key = $_REQUEST['ex_key'];

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
$gets['order_ex_key'] = $ex_key;

//print_r($gets);

$rnkapi = callapi($headers,'',$gets,"/api/exam_results/ranks");
$rnkdatas = explode("\n",$rnkapi['output']);
$rnkdata = json_decode(end($rnkdatas));

//print_r($rnkdata);
$expapi = callapi($headers,'',$gets,"/api/exam_results");
$expdatas = explode("\n",$expapi['output']);
$expdata = json_decode(end($expdatas));

$mtbapi = callapi($headers,'',$gets,"/api/masters/m_subject");
$mtbdatas = explode("\n",$mtbapi['output']);
$mtbdata = json_decode(end($mtbdatas));

//print_r($gets);
//foreach($expdata->messageObject as $list){
	//print_r($list);
	//die("c");
//}

//foreach($rnkdata->messageObject as $list){
	//echo $list->$ex_key."<br>";
	//die("c");
//}


//die("cc");

echo<<<eot

<div class="dataTables_wrapper form-inline">
	<table class="table table-bordered table-hover dataTable">
		<thead> 
			<tr> 
eot;
			echo "<th>$lang_stdname</th>";
			foreach($mtbdata->messageObject->list as $lists){
				echo "<th>$lists->sval</th>";
			}
			echo "<th>$lang_avgresult</th>";
			echo "<th>$lang_rank</th>";
			echo "<th>$lang_averagegrade</th>";
echo "</tr>
		</thead>";
		
	echo "<tbody>";
		$i=0;
		foreach($rnkdata->messageObject as $lists){
			$i++;
			$std_avg = json_decode($lists->$ex_key);
			//print_r($std_avg);
			echo "<tr id='row$row[id]'>
				<td>$lists->std_fullname</td>";
			foreach($mtbdata->messageObject->list as $mtblist){
				$exmresult="";
				foreach($expdata->messageObject as $explist){
					if($explist->student_id == $lists->student_id && $mtblist->id==$explist->subject_id){
						$exmresult = json_decode($explist->$ex_key);
					}
				}
				echo "<td>$exmresult->sresult</td>";
			}
			echo "<td><b>$std_avg->ave</b></td>";
			echo "<td><b>$std_avg->allocation</b></td>";
			echo "<td><b>$std_avg->grade</b></td>";
			echo "</tr>";
		}
	echo "</tbody>";
	echo "</table>";
	
	$ceil = ceil($rnkdata->total_count / $limit);
	$next = $page+1; $back = $page-1;
	if($back<=0) $back = 1;
	if($next>$ceil) $next = $ceil;
	
echo <<<eot
</div>

<script language='javascript'>
	$('.changepage').click(function(){
		loadform('includes/rnk/rnk_view_db.php?lang=$lang&$requestparam&page='+$(this).attr('page')+'&limit=$limit','#entry','#pageloadtext');
	});
	
	$("select[name=limit]").change(function() {
		loadform('includes/rnk/rnk_view_db.php?lang=$lang&$requestparam&page=1&limit='+$(this).val(),'#entry','#limitloadtext')
	});
	
	$('.tooltips').tooltip();
</script>
eot;
?>