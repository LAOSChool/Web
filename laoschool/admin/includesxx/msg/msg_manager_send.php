<?php
include("../../config.php");
	ensure_permission('msg');
	ensure_role('mod,sadmin,admin');
		
	$send_type = $_REQUEST['send_type'];
	$filter_class_lists = $_POST['filter_class_list'];
	$filter_class_list = implode(',',$filter_class_lists);
	$filter_roles = implode(',',$_POST['filter_roles']);
	$students = $_POST['student'];
	$student = implode(',',$students);
	$message = $_REQUEST['message'];
	
	$err = '';
	if($message=='') $err = 'ຂໍ້​ຄວາມ can not be blank';
	if($send_type=='') $err = 'Please choose Send Type';
	if($send_type==1 && $filter_class_list=='') $err = 'Please chosse at least 1 class(es)';
	if($send_type==1 && $filter_roles=='') $err = 'Please chosse at least 1 reciver type';
	if($send_type==3 && $student=='') $err = 'Please chosse at least 1 student(s)';
					
	if($err==""){
		$headers = array();
		$auth_key = $_SESSION[$config_session]['auth_key'];
		$headers[] = "auth_key: $auth_key";
		$headers[] = "api_key: TEST_API_KEY";
		
		if($send_type==0){
			$send_type=1;
			$gets['from_row'] = 0;
			$gets['max_result'] = 9999;
			$clsaip = callapi($headers,'',$gets,'api/classes');
			$clsdatas = explode("\n",$clsaip['output']);
			$clsdata = json_decode($clsdatas[14]);
			$filter_class_lists = array();
			foreach($clsdata->list as $lists){
				$filter_class_lists[] = $lists->id;
			}
			$filter_class_list = implode(',',$filter_class_lists);
		}
	
		$userdata = callapi($headers,'','','api/users/myprofile');
		$userdatas = explode("\n",$userdata['output']);
		$myprofile = json_decode($userdatas[14]);

		
		
		if($send_type==3){
			$messages['school_id'] = $myprofile->school_id;
			$messages['class_id'] = 1;
			$messages['to_usr_id'] = $students[0];
			$messages['content'] = $message;
			$messages['cc_list'] = $student;
			$message = json_encode($messages);
			$headers[] = "Content-Type: application/json";
			$headers[] = "cache-control: no-cache";
			$headers[] = "Content-Length:".strlen($message);
		
			$posts = $message;
			$sendapi = callapi($headers,$posts,'','api/messages/create');
		}else{
			$messages['school_id'] = $myprofile->school_id;
			$messages['content'] = $message;
			$messages['dest_type'] = $send_type;
			$messages['class_id'] = $filter_class_lists[0];
			$messages['cc_list'] = $filter_class_list;
			$message = json_encode($messages);
			$headers[] = "Content-Type: application/json";
			$headers[] = "Content-Length:".strlen($message);
			
			$posts = $message;
			$sendapi = callapi($headers,$posts,'','api/messages/create_ext');
		}
		//print_r($sendapi);
		//echo $message;
		//echo $sendapi['output'];
		
		if($sendapi['http_code']!=200) $status[] = $sendapi['http_code'];
	
		if(count($status)>0){
			$statu = implode(', ',$status);
			echo "<span class='label label-important'>
					<i class='icon-bug'></i> Get error: $statu
				</span>";
		}else{
			$statu = "OKIE";
			
			echo "<span class='label label-success'><i class='icon-ok-sign'></i> ຂໍ້​ຄວາມ Sent!</span>
				<script language='javascript'>
					loadform('includes/msg/msg_manager_db.php','#entry','#loadtext');
				</script>
			";
		}

	}else{
		echo "<span class='label label-important'><i class='icon-info-sign'></i> $err</span>";
	}; 
?>
