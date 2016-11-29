<?php
include("../../config.php");

ensure_permission('pnt');
ensure_role('mod,sadmin,admin');

$page = $_REQUEST['page'];
$limit = $_REQUEST['limit'];

if( $limit =='') $limit = 20;
if($page=='') $page = 1;
$pages = ($page-1) * $limit;

$filter_exam_month = $_REQUEST['filter_exam_month'];
$filter_year_id = $_REQUEST['filter_year_id'];
$filter_class_id = $_REQUEST['filter_class_id'];
$filter_subject_id = $_REQUEST['filter_subject_id'];

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
if($filter_subject_id!='') $gets['filter_subject_id'] = $filter_subject_id;
//if($filter_year_id!='') $gets['filter_year_id'] = $filter_year_id;
if($filter_exam_month!='') $gets['filter_exam_month'] = $filter_exam_month;

$gets['from_row'] = $pages;
$gets['max_result'] = $limit;
//print_r($gets);
$pntapi = callapi($headers,'',$gets,'api/exam_results');
//print_r($pntapi);
$pntdatas = explode("\n",$pntapi['output']);
$pntdata = json_decode(end($pntdatas));
//print_r($pntdata->messageObject[0]);
echo<<<eot

<div class="dataTables_wrapper form-inline">
	<div class="row-fluid">
		<div class="span6">
			<div class="limit">
				<label>
					<select class="input-mini" name="limit">
						$slt
					</select> $lang_recordperpage
					<span id='limitloadtext'></span>
				</label>
			</div>
		</div>
	</div>
	
	<table class="table table-bordered table-hover dataTable">
		<thead> 
			<tr> 
				<th>$lang_student</th>
				<th>$lang_subject</th>
eot;

$sceapi = callapi($headers,'',$gets,"api/schools/exams");
$scedatas = explode("\n",$sceapi['output']);
$scedata = json_decode(end($scedatas));

for($i=1;$i<=15;$i++){
	foreach($scedata->messageObject as $lists){
		if($lists->ex_key=="m$i") $keyname = $lists->ex_name;
	};
	echo "<th class='rotate'><div><span>$keyname</span></div></th>";
}

echo "</tr>
		</thead>";
		
	echo "<tbody>";
		foreach($pntdata->messageObject as $lists){
			//$exam_type = ($lists->exam_type==1)?"Normal Exam":"Final Exam";
			
			echo "<tr id='row$row[id]'>
				<td>$lists->std_fullname</td>
				<td>$lists->subject_name</td>";
				
				for($i=1;$i<=15;$i++){
					$m1 = "m$i";
					$m = json_decode($lists->$m1);
					//print_r($m);
					$arr = "";
					$arr['id'] = $lists->id;
					$arr['school_id'] = $lists->school_id;
					$arr['class_id'] = $filter_class_id;
					$arr['student_id'] = $lists->student_id;
					$arr['student_name'] = $lists->student_name;
					$arr['subject_id'] = $lists->subject_id;
					$arr['subject_name'] = $lists->subject_name;
					$arr['notice'] = $lists->notice;
					$arr['sch_year_id'] = $lists->sch_year_id;
					$arr['filter_exam_month'] = $i;
					$date = date('Y-m-d');
					$arr[$m1] = "{\"sresult\":\"xxxx\",\"notice\":\"\",\"exam_dt\":\"$date\"}";
					$arr['std_photo'] = $lists->std_photo;
					$arr['std_nickname'] = $lists->std_nickname;
					$json_encode = json_encode($arr);
					echo "<td id='e-$lists->id'><input id='$lists->id' d-json='$json_encode' type='text' style='width:20px' class='exam_r' value='$m->sresult'></td>";
				}
			echo "</tr>";

		}
	echo "</tbody>";
	echo "</table>";
	
	$ceil = ceil($pntdata->total_count / $limit);
	$next = $page+1; $back = $page-1;
	if($back<=0) $back = 1;
	if($next>$ceil) $next = $ceil;
	
echo <<<eot
<script language='javascript'>
	$('.changepage').click(function(){
		loadform('includes/pnt/pnt_manager_db.php?lang=$lang&$requestparam&page='+$(this).attr('page')+'&limit=$limit','#entry','#pageloadtext');
	});
	
	$("select[name=limit]").change(function() {
		loadform('includes/pnt/pnt_manager_db.php?lang=$lang&$requestparam&page=1&limit='+$(this).val(),'#entry','#limitloadtext')
	});
	
	$('.tooltips').tooltip();
</script>
eot;
?>