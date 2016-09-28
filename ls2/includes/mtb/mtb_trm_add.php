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
	$arr['year_id'] = $_REQUEST['year_id'];
	$arr['start_dt'] = $_REQUEST['start_dt']." 00:00:00";
	$arr['end_dt'] = $_REQUEST['end_dt']." 00:00:00";
	$arr['term_val'] = $_REQUEST['term_val'];
	$arr['notice'] = $_REQUEST['notice'];

	$djson = json_encode($arr);
	$err = '';
	foreach($_REQUEST as $key=>$r){
		if($key=='submit') continue;
		if($r=='') $err = "$lang_pleasefill $key";
	}
	
	if($err==""){
		$posts = $djson;
		$sendapi = callapi($headers,$posts,'',"api/terms/create");
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
			echo "<span class='label label-success'><i class='icon-ok-sign'></i> $lang_added</span>
				<script language='javascript'>
					loadform('includes/mtb/mtb_trm_db.php?lang=$lang&table=$table','#entry','#loadtext');
				</script>
			";
		}

	}else{
		echo "<span class='label label-important'><i class='icon-mtbo-sign'></i> $err</span>";
	}; 
?>