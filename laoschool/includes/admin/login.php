<?php
	$lang=$_REQUEST['lang'];
	include("../../config.php");
	
	if(isset($_REQUEST['logout'])){
		$_SESSION[$config_session] = "";
		header("location: $dirfile/$language/");
		die();
	}
	
	$url = "https://www.google.com/recaptcha/api/siteverify";
	$POSTVARS['secret'] = '6Le-RA0UAAAAAHhj2DgocKgQzLjY5geG1uJxeYGR';
	$POSTVARS['response'] = $_REQUEST['g-recaptcha-response'];
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_POST,1);
	curl_setopt($ch, CURLOPT_POSTFIELDS,$POSTVARS);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$output = json_decode(curl_exec($ch));
	
	$actype = $_REQUEST['actype'];
	if(isset($_REQUEST['submit']) && $actype=='resetpwd'){
		$sso_id = $_REQUEST['rs_sso_id'];
		$phone = $_REQUEST['phone'];
		
		$get['sso_id'] = $sso_id;
		$get['phone'] = $phone;
		
		$headers = array();
		$sendapi = callapi($headers,'',$get,"forgot_pass");
		$sendapidatas = explode("\n",$sendapi['output']);
		$sendapidata = json_decode(end($sendapidatas));
	
		if($output->success!=1){
			$err = "$lang_wrongcapcha";
		}else{
			if($sendapidata['http_code']!='200'){
				echo '<script language="javascript">grecaptcha.reset();</script>';
				$err = "Error";
			};
			if($sso_id=='') $err = $lang_missusername;
		}
		
		if($err==''){
			echo "OK";
		}else{
			echo "<div class='alert alert-danger'>$err</div>";
		}
	}
	
	if(isset($_REQUEST['submit']) && $actype=='login'){
		$err='';

		if($output->success!=1){
			$err = "$lang_wrongcapcha";
		}else{
			$headers = array();
			$headers[] = "sso_id: $_REQUEST[sso_id]";
			$headers[] = "password: $_REQUEST[password]";
			$data = callapi($headers,'','','login');
			if($data['http_code']!='200'){
				echo '<script language="javascript">grecaptcha.reset();</script>';
				$err = "$lang_loginfailed";
			}
		}
		
		if($err==''){
			echo "<div class='alert alert-success'>$lang_loginsuccess...</div>";
			echo "<script>window.location='$dirfile/$language/admin';</script>";
			$_SESSION[$config_session] = "";
			$_SESSION[$config_session]['loggedin'] = true;
			$_SESSION[$config_session]['auth_key'] = $data['auth_key'];
		}else{
			echo "<div class='alert alert-danger'>$err</div>";
		}
	}
?>