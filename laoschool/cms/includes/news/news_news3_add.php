<?php
	include('../../config.php');
	ensure_permission('news');
	ensure_role('mod,sadmin,admin');
	
	
	if(isset($_REQUEST['submit'])){
		
		
		$title = $_REQUEST['title'];
		$uploadedfile = $_REQUEST['uploadedfile'];
		$detail = $_REQUEST['detail'];
		$lang = $_REQUEST['lang'];

		$err = '';
		
		if($uploadedfile=='' && $image=='')  $err = 'Xin hãy upload file ảnh dại diện';
		if($detail=='') $err = 'Xin hãy điền tin tức';
		if($title=='') $err = 'Xin hãy điền tiêu đề tin';
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

			
			$sql = "insert into vhs_news (`title`,`image`,`detail`,`caid`,`lang`)
					values('$title','$image','$detail','2','$lang')";
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
						loadform('includes/news/news_news3_db.php','#entry','#loadtext');
						$('#queue').html('<input type=hidden class=medium name=\"image\" value=\"$image\">');
					</script>
				";
			}
			weblog($sql,"Insert news id $id with status: $statu");
		}
	}
	?>