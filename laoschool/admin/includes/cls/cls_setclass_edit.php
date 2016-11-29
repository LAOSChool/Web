<?php
include("../../config.php");
 
	ensure_permission('cls');
	ensure_role('mod,sadmin,admin');
	
	$class_id = $_REQUEST['class_id'];
	$user_ids = $_REQUEST['user_id'];
	$submit = $_REQUEST['submit'];

	if($submit=='') die("");
	
	if($err==""){
		
		foreach($user_ids as $user_id){
			$headers = array();
			$auth_key = $_SESSION[$config_session]['auth_key'];
			$headers[] = "auth_key: $auth_key";
			$headers[] = "api_key: TEST_API_KEY";

			$posts['user_id'] = $user_id;
			$posts['class_id'] = $class_id;
			
			if($submit=='edit'){
				$sendapi = callapi($headers,$posts,'',"api/users/assign_to_class");
			}elseif($submit=='remove'){
				$sendapi = callapi($headers,$posts,'',"api/users/remove_frm_class");
			}
			
			$sendapidatas = explode("\n",$sendapi['output']);
			$sendapidata = json_decode(end($sendapidatas));
			//print_r($sendapidata);
	
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
			echo "<span class='label label-success'><i class='icon-ok-sign'></i> $lang_assigned</span>
				<script language='javascript'>
					loadform('includes/cls/cls_setclass_db.php?lang=$lang&filter_user_role=$filter_user_role','#entry','#loadtext');
				</script>
			";
		}

	}else{
		echo "<span class='label label-important'><i class='icon-clso-sign'></i> $err</span>";
	}; 
?>