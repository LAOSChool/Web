<?php
include("../../config.php");
ensure_permission('admin');
ensure_role('admin');

	$username = $_REQUEST['username'];
	$password1 = $_REQUEST['password1'];
	$password2 = $_REQUEST['password2'];
	$phone = $_REQUEST['phone'];
	$role = $_REQUEST['role'];
	@$permission = implode(',',$_POST['permission']);

	if($role=='') $err = "You must choose at least 1 <b>User type</b>.";
	if($permission=='') $err = "You must choose at least 1 <b>Permission</b>.";
	if($phone=='') $err = "<b>Phone</b> can't blank";
	if($password1!=$password2) $err = "<b>Password</b> does not match";
	if(strlen($password1)<5) $err = "<b>Password</b> must be greater than 6 letters";
	$rown = $db->getone("SELECT count(*) FROM `users` WHERE `username` = '$username'");
	if($rown > 0 || $username=='itpro') $err = "<b>Username</b> already exist";
	if(strlen($username)<2) $err = "<b>Username</b> must include at least 3 characters";
	if($username=='') $err = "<b>Username</b> can't be blank";
	
	$permission .= ",$role"; //Them permission trung ten voi role, quan ly tuong duong
	
	if($err!=""){
		echo "<span class='label label-important'><i class='icon-info-sign'></i> $err</span>";
	}else{
		$password = md5($password1);
		
		$sql = "insert into users(`username`,`password`,`phone`,`role`,`permission`) 
						values('$username','$password','$phone','$role','$permission')";
		
		$res = $db->query($sql);
		if(PEAR::isError($res)) $status[] =  $res->getຂໍ້​ຄວາມ();
		
		if(count($status)>0){
			$statu = implode(', ',$status);
			
			echo "<span class='label label-important'>
					<i class='icon-bug'></i> Get some error. Let technicians know this.
				</span>";
		}else{
			$statu = "OKIE";
			echo "
				<span class='label label-success'><i class='icon-ok-sign'></i> <b>$username</b> has added!</span>
				<script language='javascript'>
					loadform('includes/admin/admin_user_db.php','#entry','#loadtext');
				</script>
			";
		}
		weblog($sql,"Add user $username with status: $statu");
	}; ?>
