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
	$uploadedfile = $_REQUEST['uploadedfile'];
	$caption = $_REQUEST['caption'];
	$err = '';
	
	if($uploadedfile=='') $err = 'ອັບ​ໂຫຼດ file please';
	if($message=='') $err = 'Notification can not be blank';
	if($title=='') $err = 'ຊື່​ໂຮງ​ຮຽນ can not be blank';
	if($send_type=='') $err = 'Please choose Send Type';
	if($filter_class_list=='') $err = 'Please chosse at least 1 class(es)';
	if($send_type==1 && $filter_roles=='') $err = 'Please chosse at least 1 reciver type';
					
	if($err==""){
		$headers = array();
		$auth_key = $_SESSION[$config_session]['auth_key'];
		$headers[] = "auth_key: $auth_key";
		$headers[] = "api_key: TEST_API_KEY";
	
		$userdata = callapi($headers,'','','api/users/myprofile');
		$userdatas = explode("\n",$userdata['output']);
		$myprofile = json_decode($userdatas[14]);

		$messages['school_id'] = $myprofile->school_id;
		$messages['class_id'] = $filter_class_lists[0];
		$messages['content'] = $message;
		$messages['dest_type'] = $send_type;
		$messages['title'] = $title;

		$message = json_encode($messages);
	
		$posts['json_in_string'] = $message;
		$posts['file'] = '@'.realpath("../../uploads/$uploadedfile");
		$posts['caption'] = $caption;
		$posts['order'] = 1;
		$sendapi = callapi($headers,$posts,'','api/notifies/create');
	
		if($sendapi['http_code']!=200) $status[] = $sendapi['http_code'];
	
		if(count($status)>0){
			$statu = implode(', ',$status);
			echo "<span class='label label-important'>
					<i class='icon-bug'></i> Get error: $statu
				</span>";
		}else{
			$statu = "OKIE";
			
			echo "<span class='label label-success'><i class='icon-ok-sign'></i> Notification Sent!</span>
				<script language='javascript'>
					loadform('includes/ntf/ntf_manager_db.php?lang=<?php echo $lang ?>','#entry','#loadtext');
				</script>
			";
		}

	}else{
		echo "<span class='label label-important'><i class='icon-info-sign'></i> $err</span>";
	}; 
?>
