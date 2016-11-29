<?php
include("../../config.php");
//die("err");
	ensure_permission('inf');
	ensure_role('mod,sadmin,admin');

	$djson = $_REQUEST['djson'];
	$ddjson = json_decode($djson);
	//echo $djson;
	$err = '';
	
	$lang = $_REQUEST['lang'];
	$arr['id'] = $_REQUEST['id'];
	$arr['title'] = $_REQUEST['title'];
	$arr['found_dt'] = $_REQUEST['found_dt'];
	$arr['degree'] = $_REQUEST['degree'];
	$arr['addr1'] = $_REQUEST['addr1'];
	$arr['addr2'] = $_REQUEST['addr2'];
	$arr['prov_city'] = $_REQUEST['prov_city'];
	$arr['county'] = $_REQUEST['county'];
	$arr['dist'] = $_REQUEST['dist'];
	$arr['url'] = $_REQUEST['url'];
	$arr['phone'] = $_REQUEST['phone'];
	$arr['ext'] = $_REQUEST['ext'];
	$arr['fax'] = $_REQUEST['fax'];
	$arr['principal'] = $_REQUEST['principal'];
	$arr['state'] = $_REQUEST['state'];
	$uploadedfile = $_REQUEST['uploadedfile'];
	
	if($err==""){
		//ອັບ​ໂຫຼດ photo
		if($uploadedfile!=''){
			$headers = array();
			$auth_key = $_SESSION[$config_session]['auth_key'];
			$headers[] = "auth_key: $auth_key";
			$headers[] = "api_key: TEST_API_KEY";
			$headers[] = "Content-Type: multipart/form-data;";
			
			$posts['file'] = '@'.realpath("../../uploads/$uploadedfile");
			$sendapi = callapi($headers,$posts,'','api/schools/upload_photo');
			$sendapidatas = explode("\n",$sendapi['output']);
			$sendapidata = json_decode(end($sendapidatas));
			print_r($sendapidata);
			if($sendapi['http_code']!=200){
				if($sendapi['http_code']==400) $status[] = $sendapidata->developerMessage;
				else $status[] = $sendapi['http_code'];
				$status[] = $sendapidata->developerMessage;
			}
		}
		
		//ອັບ​ເດດ info
		$djson = json_encode($arr);
		$headers = array();
		$auth_key = $_SESSION[$config_session]['auth_key'];
		$headers[] = "auth_key: $auth_key";
		$headers[] = "api_key: TEST_API_KEY";
		$headers[] = "Content-Type: application/json";
		
		$posts = $djson;
		
		//$posts = '{"id":2,"school_id":1,"class_id":1,"exam_id":null,"exam_name":null,"exam_type":1,"exam_dt":null,"subject_id":2,"teacher_id":null,"student_id":14,"student_name":"Student 14","notice":"Normal exam","sresult":"1","term_id":1,"exam_month":9,"exam_year":2016,"term_val":null,"sch_year_id":0,"subject":"Ly","teacher":null,"term":"HK 1"}';
		
		$sendapi = callapi($headers,$posts,'','api/schools/update');
		$sendapidatas = explode("\n",$sendapi['output']);
		$sendapidata = json_decode(end($sendapidatas));

		if($sendapi['http_code']!=200){
			if($sendapi['http_code']==400) $status[] = $sendapidata->developerຂໍ້​ຄວາມ;
			else $status[] = $sendapi['http_code'];
		} 
	
		if(count($status)>0){
			$statu = implode(', ',$status);
			echo "<span class='label label-important'>
					<i class='icon-bug'></i> $lang_error: $statu
				</span>";
		}else{
			$statu = "OKIE";
			
			echo "
			<script language='javascript'>
				loadform('includes/inf/inf_manager_form.php?lang=$lang','#parameter','#loadtext1');
			</script>
			<span class='label label-success'><i class='icon-ok-sign'></i> $lang_updated</span>
			";
		}

	}else{
		echo "<span class='label label-important'><i class='icon-info-sign'></i> $err</span>";
	}; 
?>