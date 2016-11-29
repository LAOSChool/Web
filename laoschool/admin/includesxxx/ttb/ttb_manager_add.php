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
	$myprofile = json_decode($userdatas[14]);
	
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

	if($arr['subject_id']=='') $err = 'Please choose Subject';
	if($arr['teacher_id']=='') $err = 'Please choose Teacher';
	
	if($err==""){
		$posts = $djson;
		
		$sendapi = callapi($headers,$posts,'',"/api/timetables/create");
	
		$sendapidatas = explode("\n",$sendapi['output']);
		$sendapidata = json_decode($sendapidatas[15]);

	//print_r($sendapi);
	
	
		if($sendapi['http_code']!=200){
			if($sendapi['http_code']==400) $status[] = $sendapidata->developerຂໍ້​ຄວາມ;
			else $status[] = $sendapi['http_code'];
			$status[] = $sendapidata->developerຂໍ້​ຄວາມ;
		} 
	
		if(count($status)>0){
			$statu = implode(', ',$status);
			echo "<span class='label label-important'>
					<i class='icon-bug'></i> Get error: $statu
				</span>";
		}else{
			$statu = "OKIE";
			echo "<span class='label label-success'><i class='icon-ok-sign'></i> Timetable added</span>
				<script language='javascript'>
					loadform('includes/ttb/ttb_manager_db.php?filter_class_id=$class_id','#entry','#loadtext');
				</script>
			";
		}

	}else{
		echo "<span class='label label-important'><i class='icon-ttbo-sign'></i> $err</span>";
	}; 
?>