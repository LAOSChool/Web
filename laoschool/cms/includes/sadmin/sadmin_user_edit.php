<?php
include('../../config.php');

ensure_permission('sadmin');
ensure_role('sadmin');
	
if(isset($_REQUEST['submit'])){
		
	$id = $_REQUEST['id'];
	$username = $_REQUEST['username'];
	$password1 = $_REQUEST['password1'];
	$password2 = $_REQUEST['password2'];
	$phone = $_REQUEST['phone'];
	$com_id = $_REQUEST['com_id'];
	$role = $_REQUEST['role'];
	@$permission = implode(',',$_POST['permission']);
	
	if($role == 'sadmin')$com_id='0';
	
	if($role!='sadmin' && $com_id=='') $err = "You must choose <b>Company</b>.";
	if($role=='') $err = "You must choose at least 1 <b>User type</b>.";
	if($permission=='') $err = "You must choose at least 1 <b>Permission</b>.";
	//if($phone=='') $err = "<b>Phone number</b> can't blank";
	if($password1!='' || $password2!=''){
		if($password1!=$password2) $err = "<b>Password</b> does not match";
		if(strlen($password1)<5) $err = "<b>Password</b> must be greater than 6 letters";
	}
	$rown = $db->getone("SELECT count(*) FROM `users` WHERE `username` = '$username' AND id<>$id");
	if($rown > 0 || $username=='itpro') $err = "<b>Username</b> already exist";
	if(strlen($username)<2) $err = "<b>Username</b> must include at least 3 characters";
	if($username=='') $err = "<b>Username</b> can't be blank";

	$permission .= ",$role"; //Them permission trung ten voi role, quan ly tuong duong
	
	if($err!=""){
		echo "<span class='label label-important'><i class='icon-info-sign'></i> $err</span>";
	}else{
		if($password1=='' && $password2=='') $password=$db->getone("select password from users where id=$id");
		else $password = md5($password1);
			
		$sql = "update users set `username` = '$username',`password` = '$password',`phone`='$phone',
		`com_id` = '$com_id',`role` = '$role',`permission`='$permission' where id=$id";
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
				<span class='label label-success'><i class='icon-ok-sign'></i> <b>$username</b> has Updated!</span>
				<script language='javascript'>
					loadform('includes/sadmin/sadmin_user_db.php','#entry','#loadtext');
				</script>
			";
		}
		weblog($sql,"Edit User ID $id with status: $statu");
	}
}
?>