<?php
include("../../config.php");
//die("err");
	ensure_permission('cls');
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
	$arr['id'] = $_REQUEST['id'];
	$arr['title'] = $_REQUEST['title'];
	$arr['location'] = $_REQUEST['location'];
	$arr['term'] = $_REQUEST['term'];
	$arr['start_dt'] = $_REQUEST['start_dt'];
	$arr['end_dt'] = $_REQUEST['end_dt'];
	$arr['class_type'] = $_REQUEST['class_type'];
	$arr['grade_type'] = $_REQUEST['grade_type'];
	$arr['fee'] = $_REQUEST['fee'];
	$arr['sts'] = $_REQUEST['sts'];
	$arr['head_teacher_id'] = $_REQUEST['head_teacher_id'];
	$arr['level'] = $_REQUEST['level'];
	$arr['year_id'] = $_REQUEST['year_id'];
	

	$djson = json_encode($arr);
	
	$err = '';
	foreach($_REQUEST as $key=>$r){
		if($key=='submit') continue;
		if($r=='') $err = "$lang_pleasefill $key";
	}
	if($err==""){
		$posts = $djson;
		//echo $posts;
		$sendapi = callapi($headers,$posts,'',"api/classes/update");
	
		$sendapidatas = explode("\n",$sendapi['output']);
		$sendapidata = json_decode(end($sendapidatas));

	//print_r($sendapi);
	
	
		if($sendapi['http_code']!=200){
			$status[] = $sendapi['http_code'];
			$status[] = $sendapidata->developerMessage;
		} 
	
		if(count($status)>0){
			$statu = implode(', ',$status);
			echo "<span class='label label-important'>
					<i class='icon-bug'></i> $lang_error: $statu
				</span>";
		}else{
			$statu = "OKIE";
			echo "<span class='label label-success'><i class='icon-ok-sign'></i> $lang_classedited</span>
				<script language='javascript'>
					loadform('includes/cls/cls_manager_db.php?lang=$lang&table=$table','#entry','#loadtext');
				</script>
			";
		}

	}else{
		echo "<span class='label label-important'><i class='icon-clso-sign'></i> $err</span>";
	}; 
?>