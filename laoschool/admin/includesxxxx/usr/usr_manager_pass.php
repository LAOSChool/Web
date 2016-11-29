<?php
include("../../config.php");
//die("err");
	ensure_permission('usr');
	ensure_role('mod,sadmin,admin');


	$err = '';
	$sso_id = $_REQUEST['sso_id'];
	if($err==""){
		
		//echo "api/users/reset_pass/$sso_id";
		$headers = array();
		$auth_key = $_SESSION[$config_session]['auth_key'];
		$headers[] = "auth_key: $auth_key";
		$headers[] = "api_key: TEST_API_KEY";
		$headers[] = "Content-Type: application/json";

		$post['send'] = "send";
		$sendapi = callapi($headers,$post,'',"api/users/reset_pass/$sso_id");
		$sendapidatas = explode("\n",$sendapi['output']);
		$sendapidata = json_decode($sendapidatas[17]);

		//print_r($sendapi);
		
		if($sendapi['http_code']!=200){
			$status[] = $sendapi['http_code'];
			$status[] = $sendapidata->developerຂໍ້​ຄວາມ;
		} 
	
	//print_r($sendapidatas);
		$sendapidata = $sendapidatas[16];
		//print_r($sendapidatas);
		if(count($status)>0){
			$statu = implode(', ',$status);
			echo "<span class='label label-important'>
					<i class='icon-bug'></i> $lang_error: $statu
				</span>";
		}else{
			$statu = "OKIE";
			echo "<span class='label label-success'><i class='icon-ok-sign'></i> $sendapidata.</span>
				<script language='javascript'>
					loadform('includes/usr/usr_manager_db.php?table=$table','#entry','#loadtext');
				</script>
			";
		}

	}else{
		echo "<span class='label label-important'><i class='icon-usro-sign'></i> $err</span>";
	}; 
?>