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
	
	$arr['id'] = $_REQUEST['id'];
	$arr['school_id'] = $myprofile->school_id;
	$arr['sso_id'] = $_REQUEST['sso_id'];
	$arr['password'] = $_REQUEST['password'];
	$arr['fullname'] = $_REQUEST['fullname'];
	$arr['nickname'] = $_REQUEST['nickname'];
	$arr['state'] = $_REQUEST['state'];
	$arr['roles'] = $_REQUEST['roles'];
	$arr['addr1'] = $_REQUEST['addr1'];
	$arr['addr2'] = $_REQUEST['addr2'];
	$arr['phone'] = $_REQUEST['phone'];
	$arr['ext'] = $_REQUEST['ext'];
	$arr['birthday'] = $_REQUEST['birthday'];
	$arr['gender'] = $_REQUEST['gender'];
	$arr['email'] = $_REQUEST['email'];
	$arr['std_contact_phone'] = $_REQUEST['std_contact_phone'];
	$arr['std_contact_name'] = $_REQUEST['std_contact_name'];
	$arr['std_contact_email'] = $_REQUEST['std_contact_email'];
	$arr['std_parent_name'] = $_REQUEST['std_parent_name'];
	$arr['cls_level'] = $_REQUEST['cls_level'];
	$uploadedfile = $_REQUEST['uploadedfile'];

	$djson = json_encode($arr);
	$err = '';
	foreach($_REQUEST as $key=>$r){
		if($key=='submit' || $key=='uploadedfile'||$key=='photo'|| $key=='std_contact_phone'|| $key=='std_contact_email') continue;
		if($key=='std_parent_name' || $key=='std_contact_name') continue;
		if($r=='') $err = "$lang_pleasefill $key";
	}
	
	if($err==""){

		$posts = $djson;
		$sendapi = callapi($headers,$posts,'',"api/users/update");
		$sendapidatas = explode("\n",$sendapi['output']);
		$sendapidata = json_decode(end($sendapidatas));

		if($sendapi['http_code']!=200){
			if($sendapi['http_code']==400) $status[] = $sendapidata->developerMessage;
			else $status[] = $sendapi['http_code'];
			$status[] = $sendapidata->developerMessage;
		} 
	
	
		//ອັບ​ໂຫຼດ photo
		if($uploadedfile!=''){
			$headers = array();
			$auth_key = $_SESSION[$config_session]['auth_key'];
			$headers[] = "auth_key: $auth_key";
			$headers[] = "api_key: TEST_API_KEY";
			$headers[] = "Content-Type: multipart/form-data;";
			
			$posts = array();
			//$posts['file'] = '@'.realpath("../../uploads/$uploadedfile");
			$posts['file'] = new \CurlFile(realpath("../../uploads/$uploadedfile"), 'image/png', $uploadedfile);
			
			$posts['user_id'] = $arr['id'];
			
			//print_r($posts);
			$sendapi = callapi($headers,$posts,'','api/users/upload_photo');
			$sendapidatas = explode("\n",$sendapi['output']);
			$sendapidata = json_decode(end($sendapidatas));
			
			if($sendapi['http_code']!=200){
				$status[] = $sendapi['http_code'];
				$status[] = $sendapidata->developerMessage;
			}
		}
			
		if(count($status)>0){
			$statu = implode(', ',$status);
			echo "<span class='label label-important'>
					<i class='icon-bug'></i> $lang_error: $statu
				</span>";
		}else{
			$statu = "OKIE";
			echo "<span class='label label-success'><i class='icon-ok-sign'></i> $lang_usredited.</span>
				<script language='javascript'>
					loadform('includes/usr/usr_manager_db.php?lang=$lang&table=$table','#entry','#loadtext');
				</script>
			";
		}

	}else{
		echo "<span class='label label-important'><i class='icon-usro-sign'></i> $err</span>";
	}; 
?>