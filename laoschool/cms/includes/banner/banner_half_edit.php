<?php
	include('../../config.php');
	
	ensure_permission('banner');

	
	if(isset($_REQUEST['submit'])){
		

		$name = $_REQUEST['name'];
		$image = $_REQUEST['image'];
		$description = $_REQUEST['description'];
		$uploadedfile = $_REQUEST['uploadedfile'];
		$id = $_REQUEST['id'];

		$err = '';
		
		if($image=='' && $uploadedfile=='') $err = 'Xin hãy upload Hình ảnh';
		if($name=='') $err = 'Tên ảnh không được trống';
		if($description=='') $err = 'Cần điền mô tả.';
		
		if($err!=""){
			echo "<span class='label label-important'><i class='icon-info-sign'></i> $err</span>";
		}else{
			if($image=='' && $uploadedfile!=''){
				$filename = end(explode('/',$uploadedfile));
				$filetype = end(explode('.',$filename));
				$time = reset(explode('.',$filename));
				$y = date('Y',$time);
				$m = date('m',$time);
				$d = date('d',$time);
				
				if (!is_dir("../../../images/$y/")) mkdir("../../../images/$y");         
				if (!is_dir("../../../images/$y/$m")) mkdir("../../../images/$y/$m");         
				if (!is_dir("../../../images/$y/$m/$d")) mkdir("../../../images/$y/$m/$d"); 
				
				if(!rename("../../uploads/".$uploadedfile,"../../../images/$y/$m/$d/$filename")) $status[] = "Không di chuyển đợc file";
				
				$image = "$y/$m/$d/$filename";
			};
			
			$sql = "update vhs_banner set `name` = '$name',`image`='$image',`description` = '$description' where id=$id";
			$res = $db->query($sql);
			if(PEAR::isError($res)) $status[] =  $res->getMessage();
	 
			if(count($status)>0){
				$statu = implode(', ',$status);

				echo "<span class='label label-important'>
					<i class='icon-bug'></i> Gặp sự cố. Hãy báo cho VHSTEAM
				</span>";
				
			}else{
				$statu = "OKIE";
				
				echo "<span class='label label-success'><i class='icon-ok-sign'></i> $name đã được cập nhật!</span>
					<script language='javascript'>
						$('input[name=uploadedfile]').val('');
						$('#queue').html('');
						loadform('includes/banner/banner_half_db.php','#entry','#loadtext');
					</script>
				";
			}
			
			weblog($sql,"Edit banner where id $id to with status: $statu");
		}
	}
	?>