<?php
	include("../../config.php");
	ensure_permission('contact');
	ensure_role('sadmin,admin,mod,view');
	
	if(isset($_REQUEST['id'])){
		$id = $_REQUEST['id'];
		
		$res = $db->query("delete from vhs_cobtact where id='$id'");
		if(DB::isError($res)) die("<script type='text/javascript'>alert('".$res->getMessage()."');</script>");
		
		echo "<img src='img/loading.gif' width=12 height=12>";
		
		echo "<script type='text/javascript'>
			loadform('includes/contact/contact_m_result_db.php','#entry','#loadtext');
		</script>";

	}else echo "Em có làm gì nên tội đâu mà anh hack em :((";
?>