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
	$myprofile = json_decode(end($userdatas));
	
	$err = '';
	
	$arr['school_id'] = $myprofile->school_id;
	$arr['years'] = $_REQUEST['years'];
	$arr['from_year'] = $_REQUEST['from_year'];
	$arr['to_year'] = $_REQUEST['to_year'];
	$arr['term_num'] = 2;
	$arr['term_duration'] = 4;
	$arr['start_dt'] = "$arr[from_year]-09-05 00:00:00";

	$djson = json_encode($arr);
	//echo "<textarea>$djson</textarea>";
	if($err==""){
		$posts = $djson;
		
		$sendapi = callapi($headers,$posts,'',"api/schools/years/create");
	
		$sendapidatas = explode("\n",$sendapi['output']);
		$sendapidata = json_decode(end($sendapidatas));

	//print_r($sendapi);
	
	
		if($sendapi['http_code']!=200){
			if($sendapi['http_code']==400) $status[] = $sendapidata->developerMessage;
			else $status[] = $sendapi['http_code'];
			$status[] = $sendapidata->developerMessage;
		} 
	
		if(count($status)>0){
			$statu = implode(', ',$status);
			echo "<span class='label label-important'>
					<i class='icon-bug'></i> $lang_error: $statu
				</span>";
		}else{
			$statu = "OKIE";
			echo "<span class='label label-success'><i class='icon-ok-sign'></i> $lang_added</span>
				<script language='javascript'>
					loadform('includes/mtb/mtb_scy_db.php?lang=$lang&table=$table','#entry','#loadtext');
				</script>
			";
		}

	}else{
		echo "<span class='label label-important'><i class='icon-mtbo-sign'></i> $err</span>";
	}; 
?>