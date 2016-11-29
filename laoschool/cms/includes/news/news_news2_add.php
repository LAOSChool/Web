<?php
	include('../../config.php');
	ensure_permission('news');
	ensure_role('mod,sadmin,admin');
	
	
	if(isset($_REQUEST['submit'])){
		
		
		$title = $_REQUEST['title'];
		$uploadedfile = $_REQUEST['uploadedfile'];
		$description = $_REQUEST['description'];
		$detail = $_REQUEST['detail'];
		$image = $_REQUEST['image'];
		$order = $_REQUEST['order'];
		$lang = $_REQUEST['lang'];

		$err = '';

		if(!is_numeric($order)) $err = 'Thứ tự phải là số';
		if($uploadedfile=='' && $image=='')  $err = 'Xin hãy upload file <b>Ảnh bìa</b>';
		if($description=='') $err = 'Xin hãy điền <b>Ý kiến</b>';
		if($title=='') $err = 'Xin hãy điền phụ huynh';
		if($lang=='') $err = 'Xin hãy chọn ngôn ngữ';
	
		if($err!=""){
			echo "<span class='label label-important'><i class='icon-info-sign'></i> $err</span>";
		}else{
			
			if($uploadedfile!=''){
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

			
			$sql = "insert into vhs_news (`title`,`image`,`description`,`order`,`caid`,`lang`)
					values('$title','$image','$description','$order','5','$lang')";
			//echo $sql;	
			$res = $db->query($sql);
			if(PEAR::isError($res)) $status[] =  $res->getMessage();
	 
			if(count($status)>0){
				$statu = implode(', ',$status);
				echo "<div class='messages orange'>
						<span></span> Gặp sự cố, hãy báo cho kỹ thuật.
					</div>";
			}else{
				$statu = "OKIE";
				
				echo "<span class='label label-success'><i class='icon-ok-sign'></i> $title đã được thêm!</span>
					<script language='javascript'>
						loadform('includes/news/news_news2_db.php','#entry','#loadtext');
						$('#queue').html('<input type=hidden class=medium name=\"image\" value=\"$image\">');
					</script>
				";
			}
			weblog($sql,"Insert news id $id with status: $statu");
		}
	}
	?>