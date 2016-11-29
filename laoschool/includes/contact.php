<?php
	include("../config.php");

	$name = $_REQUEST['name'];
	$phone = $_REQUEST['phone'];
	$email = $_REQUEST['email'];
	$message = $_REQUEST['message'];


	$err = '';
	
	if(!isValidEmail($email)) $err = 'Email bạn điền không hợp lệ';
	if($email=='') $err = 'Xin hãy điền Email';
	if($phone=='') $err = 'Xin hãy điền Số điện thoại';
	if($name=='') $err = 'Xin hãy điền họ tên';
	if($message=='') $err = 'Xin hãy điền nội dung';

	if($err!=""){
		echo "<font color=red> $err</font>";
	}else{
		
		
		$sql = "insert into vhs_cobtact(`name`,`phone`,`email`,`message`) 
		values('$name','$phone','$email','$message')";
		$res = $dbp->query($sql);
		if(PEAR::isError($res)) $status[] =  $res->getMessage();
		
		if(count($status)>0){
			$statu = implode(', ',$status);
			
			echo "<font color=red> Gặp sự cố, hãy báo cho <a href='http://vhsteam.net'>VHSTEAM</a></font>";
		}else{
			echo "
				<font color=green> Chúng tôi đã nhận được tin nhắn của bạn.</font>
			";
		}
		
	}; ?>
