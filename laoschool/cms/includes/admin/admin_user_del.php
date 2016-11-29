<?php
include("../../config.php");
ensure_permission('learn');
ensure_role('mod,sadmin,admin');

if(isset($_REQUEST['id'])){

	$id = $_REQUEST['id'];

	$sql = "DELETE FROM vhs_users WHERE id=$id";
	$res = $db->query($sql);
	
	if(PEAR::isError($res)) $status[] =  $res->getMessage();
	
	if(count($status)>0){
		$statu = implode(', ',$status);
		echo "<script language='javascript'>
				alert('Get some error. Let technicians know this.');
			</script>";
	}else{
		$statu = "OKIE";
		echo "<img src='$dirfile/img/loading.gif'>
			<script language='javascript'>
				loadform('includes/admin/admin_user_db.php','#entry','#loadtext');
			</script>
		";
	}
	//weblog($sql,"Delete id $id with status: $statu");
} 

?>