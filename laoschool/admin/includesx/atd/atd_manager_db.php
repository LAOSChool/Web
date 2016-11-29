<?php
include("../../config.php");

ensure_permission('atd');
ensure_role('mod,sadmin,admin');

$page = $_REQUEST['page'];
$limit = $_REQUEST['limit'];

if( $limit =='') $limit = 20;
if($page=='') $page = 1;
$pages = ($page-1) * $limit;

$filter_date = $_REQUEST['filter_date'];
$filter_class_id = $_REQUEST['filter_class_id'];

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


if($filter_class_id!='') $gets['filter_class_id'] = $filter_class_id;
if($filter_date!='') $gets['filter_date'] = $filter_date;

$atdapi = callapi($headers,'',$gets,'api/attendances/rollup');
$atddatas = explode("\n",$atdapi['output']);
$atddata = json_decode($atddatas[14]);

//print_r($atddata->messageObject->students[1]);

$_SESSION[$config_session]['timetables'] = $atddata->messageObject->timetables;
$_SESSION[$config_session]['atddata'] = $atddata;
$_SESSION[$config_session]['filter_class_id'] = $filter_class_id;


echo<<<eot

<div class="dataTables_wrapper form-inline">
	<div class="row-fluid">
		<div class="span6">
			<div class="limit">
				<label>
					<a title='Absent' class='btn btn-medium btn-info' id='listenbutton2' href='javascript:void(0)'><i class='icon-play'></i> Thông báo đi đủ</a>
				</label>
			</div>
		</div>
	</div>
	
	<table class="table table-bordered table-hover dataTable">
		<thead> 
			<tr> 
				<th>ID</th>
				<th>Student</th>
				<th>Phone</th>
				<th>ເຊັກ​ລາຍ​ຊື່​ນັກ​ນ​ຮຽນ​ຂາດ</th>
			</tr>
		</thead> 
eot;
	echo "<tbody>";
		$student =  "";
		foreach($atddata->messageObject->students as $lists){
			$exam_type = ($lists->exam_type==1)?"Normal Exam":"Final Exam";
			$json_encode = json_encode($lists);
			$student[]= $lists->id;
			echo<<<eot
			<tr id='row$row[id]'>
				<td>$lists->sso_id</td>
				<td><img src="$lists->photo" width=32 height=32> $lists->gender $lists->nickname</td>
				<td>$lists->phone</td>
				<td align=center width=20><a title='Absent' class='tooltips btn btn-mini btn-danger' id='listenbutton' qid='$lists->id' qtable="filter_user_role" href='javascript:void(0)'><i class='icon-volume-up'></i></a></td>
		</tr>
eot;
		}
	echo "</tbody>";
	echo "</table>";
	$_SESSION[$config_session]['student'] = $student;
	
	$ceil = ceil($atddata->total_count / $limit);
	$next = $page+1; $back = $page-1;
	if($back<=0) $back = 1;
	if($next>$ceil) $next = $ceil;
	
echo <<<eot
	<div class="row-fluid">
		<div class="span6">
			<div class="dataTables_info">
				Total <b>$rrr</b> records in <b>$ceil</b> pages, you are in page <b>$page</b>.
			</div>
		</div>
		<div class="span6">
			<div class="dataTables_paginate paging_bootstrap pagination">
				<ul>
					<li><span id="pageloadtext" class='hide'></span></li>
					<li><a href="javascript:;" class='changepage' page='$back'><i class="icon-chevron-left"></i></a></li>
					<li><a href="javascript:;" class='tooltips' data-original-title='Page $page of $ceil'><b>$page</b>/$ceil</a></li>
					<li><a href="javascript:;" class='changepage' page='$next'><i class="icon-chevron-right"></i></a></li>
				</ul>
			</div>
		</div>
	</div>
</div>

<script language='javascript'>
	$('.changepage').click(function(){
		loadform('includes/atd/atd_manager_db.php?$requestparam&page='+$(this).attr('page')+'&limit=$limit','#entry','#pageloadtext');
	});
	
	$("select[name=limit]").change(function() {
		loadform('includes/atd/atd_manager_db.php?$requestparam&page=1&limit='+$(this).val(),'#entry','#limitloadtext')
	});
	
	$('.tooltips').tooltip();
</script>
eot;
?>