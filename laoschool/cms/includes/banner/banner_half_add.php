<?php
include("../../config.php");

	ensure_permission('banner');

	$name = $_REQUEST['name'];
	$image = $_REQUEST['image'];
	$description = $_REQUEST['description'];
	$uploadedfile = $_REQUEST['uploadedfile'];
	$lang = $_REQUEST['lang'];

	$err = '';
	
	if($uploadedfile=='') $err = 'Xin hãy upload Hình ảnh';
	if($name=='') $err = 'Tên ảnh không được trống';
	if($description=='') $err = 'Cần điền mô tả';
	if($lang=='') $err = 'Cần chọn ngôn ngữ.';

	
	if($err!=""){
		echo "<span class='label label-important'><i class='icon-info-sign'></i> $err</span>";
	}else{
	
		if($uploadedfile!=''){
			$filename = end(explode('/',$uploadedfile));
			$time = reset(explode('.',$filename));
			$y = date('Y',$time);
			$m = date('m',$time);
			$d = date('d',$time);
		
			if (!is_dir("../../../images/$y/")) mkdir("../../../images/$y");         
			if (!is_dir("../../../images/$y/$m")) mkdir("../../../images/$y/$m");         
			if (!is_dir("../../../images/$y/$m/$d")) mkdir("../../../images/$y/$m/$d");         
			
			if(!rename("../../uploads/".$uploadedfile,"../../../images/$y/$m/$d/$filename")) $status[] = "Không di chuyển được file";
			$image = "$y/$m/$d/$filename";
		}else $image = "";
		
		$sql = "insert into vhs_banner(`name`,`image`,`description`,`lang`) values('$name','$image','$description','$lang')";
		$res = $db->query($sql);
		if(PEAR::isError($res)) $status[] =  $res->getMessage();
		
		if(count($status)>0){
			$statu = implode(', ',$status);
			
			echo "<span class='label label-important'>
					<i class='icon-bug'></i> Gặp sự cố, hãy báo cho VHSTEAM
				</span>";
		}else{
			$statu = "OKIE";
			
			echo "
				<span class='label label-success'><i class='icon-ok-sign'></i> <b>$name</b> đã được thêm!</span>
				<script language='javascript'>
					$('input[name=uploadedfile]').val('');
					$('#queue').html('');

					loadform('includes/banner/banner_half_db.php','#entry','#loadtext');
				</script>
			";
		}
		weblog($sql,"Add banner $name with status: $statu");
	}; ?>
