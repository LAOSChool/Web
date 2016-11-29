<?php
include("../../config.php");
//die("err");
	ensure_permission('ttb');
	ensure_role('mod,sadmin,admin');

	$headers = array();
	$auth_key = $_SESSION[$config_session]['auth_key'];
	$headers[] = "auth_key: $auth_key";
	$headers[] = "api_key: TEST_API_KEY";
	$headers[] = "Content-Type: application/json";
	
	$userdata = callapi($headers,'','','api/users/myprofile');
	$userdatas = explode("\n",$userdata['output']);
	$myprofile = json_decode(end($userdatas));
	
	$err = '';
	
	$arr['school_id'] = $myprofile->school_id;
	$arr['class_id'] = $_REQUEST['class_id'];
	$arr['session_id'] = $_REQUEST['session_id'];
	$arr['weekday_id'] = $_REQUEST['weekday_id'];
	$arr['term_val'] = $_REQUEST['term_val'];
	$arr['subject_id'] = $_REQUEST['subject_id'];
	$arr['teacher_id'] = $_REQUEST['teacher_id'];
	$class_id = $_REQUEST['class_id'];

	$djson = json_encode($arr);
//print_r($djson);
	$err = '';
	foreach($_REQUEST as $key=>$r){
		if($key=='submit') continue;
		if($r=='') $err = "$lang_pleasefill $key";
	}
	
	if($err==""){
		$posts = $djson;
		
		$sendapi = callapi($headers,$posts,'',"/api/timetables/create");
	
		$sendapidatas = explode("\n",$sendapi['output']);
		$sendapidata = json_decode(end($sendapidatas));

	//print_r($sendapi);
	
	
		if($sendapi['http_code']!=200){
			if($sendapi['http_code']==400) $status[] = $sendapidata->developerMessage;
			else $status[] = $sendapi['http_code'];
			$status[] = $sendapidata->developerMessage;
		} 
	
		if(count($status)>0){
			$statu = implode(', ',$status);
			echo "<span class='label label-important'>
					<i class='icon-bug'></i> $lang_error: $statu
				</span>";
		}else{
			$statu = "OKIE";
			echo "<span class='label label-success'><i class='icon-ok-sign'></i> Timetable added</span>
				<script language='javascript'>
					loadform('includes/ttb/ttb_manager_db.php?lang=$lang&filter_class_id=$class_id','#entry','#loadtext');
				</script>
			";
		}

	}else{
		echo "<span class='label label-important'><i class='icon-ttbo-sign'></i> $err</span>";
	}; 
?>