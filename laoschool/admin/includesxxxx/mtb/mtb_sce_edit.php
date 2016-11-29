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
	
	$arr['id'] = $_REQUEST['id'];
	$arr['school_id'] = $myprofile->school_id;
	$arr['ex_displayname'] = $_REQUEST['ex_displayname'];
	$arr['term_val'] = $_REQUEST['term_val'];
	$arr['ex_month'] = $_REQUEST['ex_month'];
	$arr['ex_type'] = $_REQUEST['ex_type'];
	$arr['ex_name'] = $_REQUEST['ex_name'];
	$arr['cls_levels'] = $_REQUEST['cls_levels'];
	$arr['ex_key'] = $_REQUEST['ex_key'];
	$arr['min'] = (int) $_REQUEST['min'];
	$arr['max'] = (int) $_REQUEST['max'];


	$djson = json_encode($arr);
	if(!is_int($arr['min']) || $arr['min']=='') $err = "Min must be interger";
	if(!is_int($arr['max']) || $arr['max']=='') $err = "Max must be interger";
	//echo "<textarea>$djson</textarea>";
	
	if($err==""){
		$posts = $djson;
		
		$sendapi = callapi($headers,$posts,'',"api/schools/exams/update");
	
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
					<i class='icon-bug'></i> $lang_error: $statu
				</span>";
		}else{
			$statu = "OKIE";
			echo "<span class='label label-success'><i class='icon-ok-sign'></i> ອັບ​ເດດd</span>
				<script language='javascript'>
					loadform('includes/mtb/mtb_sce_db.php?table=$table','#entry','#loadtext');
				</script>
			";
		}

	}else{
		echo "<span class='label label-important'><i class='icon-mtbo-sign'></i> $err</span>";
	}; 
?>