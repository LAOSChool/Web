<?php
	include('../../config.php');
	
	if(isset($_REQUEST['submit'])){
		$username = $_REQUEST['username'];
		$phone = $_REQUEST['phone'];
		$email = $_REQUEST['email'];
		$password1 = $_REQUEST['password1'];
		$password2 = $_REQUEST['password2'];

		$err = '';

		if($password1!='' || $password2!=''){
			if($password1!=$password2) $err = "2 mật khẩu không khớp";
			if(strlen($password1)<5) $err = "Mật khẩu cần có hơn 6 ký tự";
		}
		if($_SESSION[$config_session]['username']!=$username) $err = "Đệt!";
		
		
		if($err!=""){
			echo "<span class='label label-important'><i class='icon-ok-sign'></i> $err</span>";
		}else{
			if($password1!='' && $password2!='' && $password1==$password2){
				$password = md5($password1);
			}else $password = $db->getone("select password from vhs_users where username='$username'");
			 
			$res = $db->query("UPDATE `vhs_users` set `email`='$email',`phone` = '$phone' ,`password` = '$password' where username='$username' limit 1");
			if(PEAR::isError($res)) die("Error: ".$res->getMessage());
			echo "<span class='label label-success'><i class='icon-ok-sign'></i> Thông tin cá nhân đã được cập nhật</span>";
		};
		exit();
	}
	
	?>