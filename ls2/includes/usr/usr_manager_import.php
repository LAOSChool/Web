<?php
include("../../config.php");
//die("err");
	ensure_permission('usr');
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
	
	
	$class_id = $_REQUEST['class_id'];
	$uploadedfile = $_REQUEST['uploadedfile'];

	$err = '';
	foreach($_REQUEST as $key=>$r){
		if($key=='submit') continue;
		if($key=='cls_level' ||$key=='std_parent_name' || $key=='std_contact_name' || $key=='sso_id') continue;
		if($r=='') $err = "$lang_pleasefill $key";
	}
	
	if($err==""){

		$headers = array();
		$auth_key = $_SESSION[$config_session]['auth_key'];
		$headers[] = "auth_key: $auth_key";
		$headers[] = "api_key: TEST_API_KEY";
		$headers[] = "Content-Type: multipart/form-data;";
		
		$posts = array();
		$posts['file'] = new \CurlFile(realpath("../../uploads/$uploadedfile"), 'image/png', $uploadedfile);
		$posts['class_id'] = $class_id;

		//print_r($posts);
		$sendapi = callapi($headers,$posts,'','api/users/upload_file');
		$sendapidatas = explode("\n",$sendapi['output']);
		$sendapidata = json_decode(end($sendapidatas));
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
			echo "<span class='label label-success'><i class='icon-ok-sign'></i> $lang_usrimported</span>
				<script language='javascript'>
					loadform('includes/usr/usr_manager_db.php?lang=$lang&table=$table&filter_user_role=STUDENT','#entry','#loadtext');
				</script>
			";
		}

	}else{
		echo "<span class='label label-important'><i class='icon-usro-sign'></i> $err</span>";
	}; 
?>