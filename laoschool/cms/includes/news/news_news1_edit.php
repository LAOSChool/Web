<?php
	include('../../config.php');
	ensure_permission('news');
	ensure_role('mod,sadmin,admin');
	
	
	if(isset($_REQUEST['submit'])){
		
		$id = $_REQUEST['id'];
		$title = $_REQUEST['title'];
		$camnhan_cv = $_REQUEST['camnhan_cv'];
		$camnhan_ct = $_REQUEST['camnhan_ct'];
		$camnhan_sv = $_REQUEST['camnhan_sv'];
		
		$uploadedfile = $_REQUEST['uploadedfile'];
		$description = $_REQUEST['description'];
		$detail = $_REQUEST['detail'];
		$image = $_REQUEST['image'];
		$order = $_REQUEST['order'];

		$err = '';

		if(!is_numeric($order)) $err = 'Thứ tự phải là số';
		if($uploadedfile=='' && $image=='')  $err = 'Xin hãy upload file <b>Ảnh bìa</b>';
		if($detail=='') $err = 'Xin hãy điền <b>Chi tiết tin</b>';
		if($description=='') $err = 'Xin hãy điền <b>Ý kiến</b>';
		if($camnhan_sv=='') $err = 'Xin hãy điền Khóa học';
		if($camnhan_ct=='') $err = 'Xin hãy điền Công việc';
		if($camnhan_cv=='') $err = 'Xin hãy điền Chức vụ';
		if($title=='') $err = 'Xin hãy điền <b>Tên học viên</b>';
	
		if($err!=""){
			echo "<span class='label label-important'><i class='icon-info-sign'></i> $err</span>";
		}else{
			
			if($uploadedfile!='' && $image==''){
				$filename = end(explode('/',$uploadedfile));
				$filetype = end(explode('.',$filename));
				$time = reset(explode('.',$filename));
				$y = date('Y',$time);
				$m = date('m',$time);
				$d = date('d',$time);
		
				if (!is_dir("../../../images/$y/")) mkdir("../../../images/$y");         
				if (!is_dir("../../../images/$y/$m")) mkdir("../../../images/$y/$m");         
				if (!is_dir("../../../images/$y/$m/$d")) mkdir("../../../images/$y/$m/$d");   
		
				if(!rename("../../uploads/".$uploadedfile,"../../../images/$y/$m/$d/$filename")) $status[] = "Không thể di chuyển file ảnh.";
				
				$image = "$y/$m/$d/$filename";
			};

			
			$sql = "update vhs_news set `title`='$title', `camnhan_cv`='$camnhan_cv', `camnhan_ct`='$camnhan_ct', `camnhan_sv`='$camnhan_sv', 
					`image` = '$image',`description`='$description',`detail`='$detail',`order`='$order' where id=$id";
			$res = $db->query($sql);
			if(PEAR::isError($res)) $status[] =  $res->getMessage();
	 
			if(count($status)>0){
				$statu = implode(', ',$status);
				echo "<div class='messages orange'>
						<span></span> Gặp sự cố, hãy báo cho kỹ thuật.
					</div>";
			}else{
				$statu = "OKIE";
				
				echo "<span class='label label-success'><i class='icon-ok-sign'></i> $title đã được cập nhật!</span>
					<script language='javascript'>
						loadform('includes/news/news_news1_db.php','#entry','#loadtext');
						$('#queue').html('<input type=hidden class=medium name=\"image\" value=\"$image\">');
					</script>
				";
			}
			weblog($sql,"Update news id $id with status: $statu");
		}
	}
	?>