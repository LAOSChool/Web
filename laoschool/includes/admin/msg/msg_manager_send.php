<?php

die("ccc");

include("../../../config.php");
	
	$to_user_id = $_REQUEST['to_user_id'];
	$content = $_REQUEST['content'];
	
	if($to_user_id=='') $err = $lang_misssendto;
	if($err==""){
		$headers = array();
		$auth_key = $_SESSION[$config_session]['auth_key'];
		$headers[] = "auth_key: $auth_key";

		$messages['to_user_id'] = $to_user_id;
		$messages['content'] = $content;
		$message = json_encode($messages);
		$headers[] = "Content-Type: application/json";
		$headers[] = "Content-Length:".strlen($message);
		$posts = $message;
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
			
			echo "<span class='label label-success'><i class='icon-ok-sign'></i> $lang_msgsent</span>
				<script language='javascript'>
					loadform('includes/msg/msg_manager_db.php?lang=$lang','#entry','#loadtext');
				</script>
			";
		}

	}else{
		echo "<span class='label label-important'><i class='icon-info-sign'></i> $err</span>";
	}; 
?>
