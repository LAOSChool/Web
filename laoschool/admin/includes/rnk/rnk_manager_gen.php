<?php
include("../../config.php");
//die("err");
	ensure_permission('rnk');
	ensure_role('mod,sadmin,admin');

	$headers = array();
	$auth_key = $_SESSION[$config_session]['auth_key'];
	$headers[] = "auth_key: $auth_key";
	$headers[] = "api_key: TEST_API_KEY";
	//$headers[] = "Content-Type: application/json";
	$headers[] = "Content-Type: multipart/form-data;";
	
	$userdata = callapi($headers,'','','api/users/myprofile');
	$userdatas = explode("\n",$userdata['output']);
	$myprofile = json_decode(end($userdatas));
	
	$err = '';
	
	$class_id = $_REQUEST['class_id'];
	$ex_key = $_REQUEST['ex_key'];

	if(!is_array($class_id)) $err = "$lang_pleasefill class_id";
	$class_ids = implode(',',$class_id);
	$err = '';
	foreach($_REQUEST as $key=>$r){
		if($key=='submit') continue;
		if($r=='') $err = "$lang_pleasefill $key";
	}
	
	if($err==""){
		$posts['class_ids'] = $class_ids;
		$posts['ex_key'] = $ex_key;
		
		//echo "api/exam_results/ranks/process?class_ids=$class_ids&ex_key=$ex_key";
		
		$sendapi = callapi($headers,$posts,'',"api/exam_results/ranks/process?class_ids=$class_ids&ex_key=$ex_key");
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
			echo "<span class='label label-success'><i class='icon-ok-sign'></i> $lang_genedrank!</span>
				<script language='javascript'>
					//loadform('includes/rnk/rnk_manager_db.php?lang=$lang&filter_class_id=$class_id','#entry','#loadtext');
				</script>
			";
		}

	}else{
		echo "<span class='label label-important'><i class='icon-rnko-sign'></i> $err</span>";
	}; 
	
?>