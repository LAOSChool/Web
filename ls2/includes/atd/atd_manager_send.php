<?php
include("../../config.php");
	ensure_permission('pnt');
	ensure_role('mod,sadmin,admin');

	$djson = $_REQUEST['djson'];
	$ddjson = json_decode($djson);
	//echo $djson;
	$err = '';
	
	$arr['school_id'] = $_REQUEST['school_id'];
	$arr['class_id'] = $_REQUEST['class_id'];
	$arr['att_dt'] = $_REQUEST['att_dt'];
	$arr['student_id'] = $_REQUEST['student_id'];
	$arr['subject_id'] = $_REQUEST['subject_id'];
	$arr['absent'] = $_REQUEST['absent'];
	$arr['excused'] = $_REQUEST['excused'];
	$arr['late'] = $_REQUEST['late'];
	$arr['notice'] = $_REQUEST['notice'];

	$djson = json_encode($arr);

	$err = '';
	foreach($_REQUEST as $key=>$r){
		if($key=='submit') continue;
		if($r=='') $err = "$lang_pleasefill $key";
	}
	
	if($err==""){
		$headers = array();
		$auth_key = $_SESSION[$config_session]['auth_key'];
		$headers[] = "auth_key: $auth_key";
		$headers[] = "api_key: TEST_API_KEY";
		$headers[] = "Content-Type: application/json";
		
		$posts = $djson;
		
		//$posts = '{"id":2,"school_id":1,"class_id":1,"exam_id":null,"exam_name":null,"exam_type":1,"exam_dt":null,"subject_id":2,"teacher_id":null,"student_id":14,"student_name":"Student 14","notice":"Normal exam","sresult":"1","term_id":1,"exam_month":9,"exam_year":2016,"term_val":null,"sch_year_id":0,"subject":"Ly","teacher":null,"term":"HK 1"}';
		
		$sendapi = callapi($headers,$posts,'','api/attendances/create');

		$sendapidatas = explode("\n",$sendapi['output']);
		$sendapidata = json_decode(end($sendapidatas));

		if($sendapi['http_code']!=200){
			if($sendapi['http_code']==400) $status[] = $sendapidata->developerMessage;
			else $status[] = $sendapi['http_code'];
		} 
	
		if(count($status)>0){
			$statu = implode(', ',$status);
			echo "<span class='label label-important'>
					<i class='icon-bug'></i> $lang_error: $statu
				</span>";
		}else{
			$statu = "OKIE";
			
			echo "<span class='label label-success'><i class='icon-ok-sign'></i> Absent for Reason: $notice. Done!</span>
			";
		}

	}else{
		echo "<span class='label label-important'><i class='icon-info-sign'></i> $err</span>";
	}; 
?>