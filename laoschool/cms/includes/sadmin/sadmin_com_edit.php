<?php
include('../../config.php');
ensure_role('sadmin');
ensure_permission('sadmin');

if(isset($_REQUEST['submit'])){
		
	$id = $_REQUEST['id'];
	$name = $_REQUEST['name'];
	$short_code = $_REQUEST['short_code'];
	$pbx = $_REQUEST['pbx'];
	$address = $_REQUEST['address'];

	if($address=='') $err = '<b>Address</b> can\'t blank';
	
	if($db->getone("select count(*) from companies where `short_code`='$short_code' and id<>$id")>0){
		$err = '<b>Company code</b> Already exist';
	};
	if($pbx=='') $err = '<b>Company PBX</b> can\'t blank';
	if($short_code=='') $err = '<b>Company code</b> can\'t blank';
	if($name=='') $err = '<b>Company name</b> can\'t blank';


	if($err!=""){
		echo "<span class='label label-important'><i class='icon-info-sign'></i> $err</span>";
	}else{
		$sql = "update companies set `name` = '$name',`short_code` = '$short_code',`pbx`='$pbx',`address` = '$address' where id=$id";
		$res = $db->query($sql);
		if(PEAR::isError($res)) $status[] =  $res->getMessage();
 
		if(count($status)>0){
			$statu = implode(', ',$status);
			
			echo "<span class='label label-important'>
					<i class='icon-bug'></i> Get some error. Let technicians know this.
				</span>";
		}else{
			$statu = "OKIE";
			
			echo "
				<span class='label label-success'><i class='icon-ok-sign'></i> <b>$name</b> has Updated!</span>
				<script language='javascript'>
					loadform('includes/sadmin/sadmin_com_db.php','#entry','#loadtext');
				</script>
			";
		}
		weblog($sql,"Edit Company ID $id with status: $statu");
	}
}
?>