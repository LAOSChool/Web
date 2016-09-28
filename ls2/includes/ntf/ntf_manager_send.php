<?php
include("../../config.php");

	ensure_permission('ntf');
	ensure_role('mod,sadmin,admin');
		
	$send_type = $_REQUEST['send_type'];
	$filter_class_lists = $_POST['filter_class_list'];
	$filter_class_list = implode(',',$filter_class_lists);
	$filter_roles = implode(',',$_POST['filter_roles']);
	$message = $_REQUEST['message'];
	$title = $_REQUEST['title'];
	$uploadedfiles = $_REQUEST['uploadedfile'];
	$captions = $_REQUEST['caption'];
	$err = '';

	foreach($_REQUEST as $key=>$r){
		if($key=='submit' || $key=='file_upload') continue;
		if($r=='') $err = "$lang_pleasefill $key";
	}
					
	if($err==""){
		$headers = array();
		$auth_key = $_SESSION[$config_session]['auth_key'];
		$headers[] = "auth_key: $auth_key";
		$headers[] = "api_key: TEST_API_KEY";
	
		$userdata = callapi($headers,'','','api/users/myprofile');
		$userdatas = explode("\n",$userdata['output']);
		$myprofile = json_decode(end($userdatas));

		$messages['school_id'] = $myprofile->school_id;
		$messages['class_id'] = $filter_class_lists[0];
		$messages['content'] = $message;
		$messages['dest_type'] = $send_type;
		$messages['title'] = $title;

		$message = json_encode($messages);
	
		$posts['json_in_string'] = $message;
	
		$i=1;
		
		foreach($uploadedfiles as $key=>$uploadedfile){
			if($uploadedfile=='' && $captions[$key]=='') continue;
			$posts["order$i"] =  $i;
			$posts["file$i"] =  new \CurlFile(realpath("../../uploads/$uploadedfile"), 'image/png', $uploadedfile);
			$posts["caption$i"] = $captions[$key];
			$i++;
		};
		
		print_r($posts);
	
		$sendapi = callapi($headers,$posts,'','api/notifies/create_php');
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
			
			echo "<span class='label label-success'><i class='icon-ok-sign'></i> $lang_ntfsent</span>
				<script language='javascript'>
					loadform('includes/ntf/ntf_manager_db.php?lang=$lang','#entry','#loadtext');
				</script>
			";
		}

	}else{
		echo "<span class='label label-important'><i class='icon-info-sign'></i> $err</span>";
	}; 
?>
