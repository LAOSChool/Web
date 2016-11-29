<?php
include("../../config.php");
	ensure_permission('admin');
	ensure_role('sadmin,admin');

	$username = $_REQUEST['username'];
	$password1 = $_REQUEST['password1'];
	$password2 = $_REQUEST['password2'];
	$phone = $_REQUEST['phone'];
	$role = $_REQUEST['role'];
	@$permission = implode(',',$_POST['permission']);

	if($role=='') $err = "Bạn cần chọn 1 <b>Kiểu user</b>.";
	if($permission=='') $err = "Bạn cần chọn <b>quyền truy cập</b>.";
	if($password1!=$password2) $err = "<b>Mật khẩu</b> không khớp";
	if(strlen($password1)<5) $err = "<b>Mật khẩu</b> cần ít nhẩu 6 ký tự";
	$rown = $db->getone("SELECT count(*) FROM `vhs_users` WHERE `username` = '$username'");
	

	if($rown > 0 || $username=='vhsteam') $err = "<b>Username</b> đã tồn tại";
	if(strlen($username)<2) $err = "<b>Username</b> cần ít nhất 3 ký tự";
	if($username=='') $err = "<b>Username</b> không được trống";
	
	if($err!=""){
		echo "<span class='label label-important'><i class='icon-info-sign'></i> $err</span>";
	}else{
		$password = md5($password1);
		$permission .=",$role";
		$sql = "insert into vhs_users(`username`,`password`,`phone`,`role`,`permission`) 
						values('$username','$password','$phone','$role','$permission')";
		
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
				<span class='label label-success'><i class='icon-ok-sign'></i> <b>$username</b> đã đợc thêm!</span>
				<script language='javascript'>
					loadform('includes/admin/admin_user_db.php','#entry','#loadtext');
				</script>
			";
		}

	}; ?>
