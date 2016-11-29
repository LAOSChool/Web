<?php
	include("../../config.php");
	ensure_permission('contact');
	ensure_role('sadmin,admin,mod,view');
	
	function test_url_file($url){
		$res = (($ftest = @fopen($url, 'r')) === false) ? false : @fclose($ftest);
		return ($res == TRUE) ? 1:0 ;
	}
	
	if(isset($_REQUEST['submit'])){
		$supid = $_REQUEST['supid'];
		$supname = $_REQUEST['supname'];
		$suptype = $_REQUEST['suptype'];
		$supnick = $_REQUEST['supnick'];
		$supord = $_REQUEST['supord'];

		$err = '';

		if($supname=='')$err = "Name can't blank";
		if($supnick=='')$err = "Account can't blank";
		if($supid=='') $err = "Error";
		
		if($err!=""){
			echo "<span class='label label-important'><i class='icon-info-sign'></i> $err</span>";
		}else{
	
			$res = $db->query("UPDATE `tbcontact` set `supname` = '$supname', `suptype` = '$suptype',
			`supnick` = '$supnick', `supord` = '$supord' WHERE supid='$supid'");
			
			if(DB::isError($res)) die("Unknow error. Please contact to developer. ".$res->getMessage());

			echo "<span class='label label-success'><i class='icon-ok-sign'></i> contact has updated!</span>";

			echo "<script type='text/javascript'>
				loadform('includes/contact/contact_m_result_db.php','#entry','#loadtext');
			</script>";
		}; 
	}else echo "<div class='messages orange'><span></span>Em có làm gì nên tội đâu, mà các anh hack em :((</div>";
?>