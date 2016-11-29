<?php
include('../../config.php');
	ensure_permission('admin');
	ensure_role('sadmin,admin');
	
if(isset($_REQUEST['submit'])){
		
	$id = $_REQUEST['id'];
	$username = $_REQUEST['username'];
	$password1 = $_REQUEST['password1'];
	$password2 = $_REQUEST['password2'];
	$phone = $_REQUEST['phone'];
	$cpid = $_SESSION[$config_session]['cpid'];
	$role = $_REQUEST['role'];
	@$permission = implode(',',$_POST['permission']);
	
	if($role=='') $err = "Bạn cần chọn 1 <b>Kiểu user</b>.";
	if($permission=='') $err = "Bạn cần chọn <b>quyền truy cập</b>.";
	if($password1!='' || $password2!=''){
		if($password1!=$password2) $err = "<b>Mật khẩu</b> không khớp";
		if(strlen($password1)<5) $err = "<b>Mật khẩu</b> cần ít nhẩu 6 ký tự";
	}
	$rown = $db->getone("SELECT count(*) FROM `vhs_users` WHERE `username` = '$username' AND id<>$id");
	if($rown > 0 || $username=='vhsteam') $err = "<b>Username</b> đã tồn tại";
	if(strlen($username)<2) $err = "<b>Username</b> cần ít nhất 3 ký tự";
	if($username=='') $err = "<b>Username</b> không được trống";


	if($err!=""){
		echo "<span class='label label-important'><i class='icon-info-sign'></i> $err</span>";
	}else{
		if($password1=='' && $password2=='') $password=$db->getone("select password from vhs_users where id=$id");
		else $password = md5($password1);
		$permission .=",$role";	
		$sql = "update vhs_users set `username` = '$username',`password` = '$password',`phone`='$phone',
		`role` = '$role',`permission`='$permission' where id=$id";
		
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
				<span class='label label-success'><i class='icon-ok-sign'></i> <b>$username</b> đã được cập nhật!</span>
				<script language='javascript'>
					loadform('includes/admin/admin_user_db.php','#entry','#loadtext');
				</script>
			";
		}
		

	}
}
?>