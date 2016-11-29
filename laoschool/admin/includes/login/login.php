<?php
	$_SESSION[$config_session] = "";
	if(isset($_REQUEST['logout'])){
		$_SESSION[$config_session]['loggedin'] = 'false';
		$_SESSION[$config_session] = 'false';
		echo "
		<div class='alert'>
			<strong>Processing!</strong> $lang_pleasewait.....
		</div>
		<script>
			window.location='index.php?lang=$lang';
		</script>
		";
		exit();
	}

	if(isset($_REQUEST['submit'])){
		$headers = array();
		$headers[] = "sso_id: $_REQUEST[sso_id]";
		$headers[] = "api_key: TEST_API_KEY";
		$headers[] = "password: $_REQUEST[password]";
		$data = callapi($headers,'','','login');
		if($data['http_code']=='200'){
			echo "<div class='alert'>
				<strong>Processing!</strong> $lang_pleasewait.....
			</div>";
			$_SESSION[$config_session] = "";
			$_SESSION[$config_session]['loggedin'] = true;
			$_SESSION[$config_session]['auth_key'] = $data['auth_key'];
			$_SESSION['ccc'] = $data['auth_key'];
			echo "<script>window.location='index.php?lang=$lang';</script>";
		}else{
			$_SESSION[$config_session]['loggedin'] = false;
			$err = $lang_wrongpass;
			$type="red";
		}
	}else $username='';
?>

<div class="lock-header">
	<!-- BEGIN LOGO -->
	<a class="center" id="logo" href="index.html">
		<img class="center" alt="logo" src="img/logo.png">
		<?php echo $config_webtitle; ?>
	</a>
	<!-- END LOGO -->
</div>
<div class="login-wrap">
	<form action="" method="post">
		<div class="metro single-size red">
			<div class="locked">
				<i class="icon-lock"></i>
				<span><?php lang('login') ?></span>
			</div>
		</div>
		
		<div class="metro double-size green">
			<div class="input-append lock-input">
				<input type="text" class="" placeholder="<?php lang('username') ?>" name="sso_id" value="<?php echo $username?>">
			</div>
		</div>
		<div class="metro double-size yellow">
			<div class="input-append lock-input">
				<input type="password" class="" placeholder="<?php lang('password') ?>" name="password">
			</div>
		</div>
		<div class="metro single-size terques login">
			<button type="submit" class="btn login-btn" name="submit" value="1">
				<?php lang('login') ?>
				<i class=" icon-long-arrow-right"></i>
			</button>
		</div>
	</form>
	<div class="login-footer">
	</div>
	<?php if($dberror!=''): ?>
		<div class="alert alert-error">
			<b><?php lang('connecterror') ?></b> - <?php echo $dberror; ?>
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