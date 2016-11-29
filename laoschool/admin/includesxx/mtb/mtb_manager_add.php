<?php
include("../../config.php");
//die("err");
	ensure_permission('mtb');
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
	
	$arr['sval'] = $_REQUEST['sval'];
	$arr['notice'] = $_REQUEST['notice'];
	$arr['fval1'] = $_REQUEST['fval1'];
	$arr['fval2'] =  $_REQUEST['fval2'];
	$arr['lval'] = '';
	$arr['school_id'] = $myprofile->school_id;
	$table = $_REQUEST['table'];


	$djson = json_encode($arr);
	echo "<textarea>$djson</textarea>";
	if($err==""){
		$posts = $djson;
		
		$sendapi = callapi($headers,$posts,'',"api/masters/create/$table");
	
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
			echo "<span class='label label-success'><i class='icon-ok-sign'></i> Added</span>
				<script language='javascript'>
					loadform('includes/mtb/mtb_manager_db.php?table=$table','#entry','#loadtext');
				</script>
			";
		}

	}else{
		echo "<span class='label label-important'><i class='icon-mtbo-sign'></i> $err</span>";
	}; 
?>