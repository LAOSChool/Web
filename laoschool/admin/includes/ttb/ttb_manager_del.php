<?php

include("../../config.php");

ensure_permission('ttb');
ensure_role('mod,sadmin,admin');


if(isset($_REQUEST['id'])){

	$id = $_REQUEST['id'];
	$class_id = $_REQUEST['table'];

	$headers = array();
	$auth_key = $_SESSION[$config_session]['auth_key'];
	$headers[] = "auth_key: $auth_key";
	$headers[] = "api_key: TEST_API_KEY";
	$headers[] = "Content-Type: application/json";
	
	$post = "xxx";

	$sendapi = callapi($headers,$post,'',"/api/timetables/delete/$id");
	$sendapidatas = explode("\n",$sendapi['output']);
	$sendapidata = json_decode(end($sendapidatas));
	
	print_r($sendapidata);
	
	//echo "api/timetables/delete  ---- $id";
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
		echo "<span class='label label-success'><i class='icon-ok-sign'></i> Timetable Deleted.</span>
			<script language='javascript'>
				loadform('includes/ttb/ttb_manager_form.php?lang=<?php echo $lang ?>&type=search','#parameter','#loadform');
				$(document).scrollTo('#goparameter',1000, {offset:-61});
				loadform('includes/ttb/ttb_manager_db.php?filter_class_id=$class_id','#entry','#loadtext');
			</script>
		";
	}
}
?>