<?php
	include('../../config.php');
	ensure_permission('menu');
	
	if(isset($_REQUEST['submit'])){
		$id = $_REQUEST['id'];
		
		$title = $_REQUEST['title'];
		$order = $_REQUEST['order'];

		$err = '';
		
		if(!is_numeric($order)) $err = 'Thứ tự phải là số';
		if($title=='') $err = 'Xin hãy điền <b>tiêu đề';
		
		if($err!=""){
			echo "<span class='label label-important'><i class='icon-info-sign'></i> $err</span>";
		}else{
		
		
			$sql = "update vhs_menu set `title` = '$title',`description` = '$title', `order` = '$order' where id=$id";
			$res = $db->query($sql);
			if(PEAR::isError($res)) $status[] =  $res->getMessage();
	 
			if(count($status)>0){
				$statu = implode(', ',$status);

				echo "<span class='label label-important'>
						<i class='icon-bug'></i> Gặp lỗi, xin báo cho kỹ thuật.
				</span>";
				
			}else{
				$statu = "OKIE";
				
				echo "<span class='label label-success'><i class='icon-ok-sign'></i> $title đã được sửa!</span>
					<script language='javascript'>
						loadform('includes/menu/menu_menu_db.php','#entry','#loadtext');
					</script>
				";
			}
			
			weblog($sql,"Edit menu where id $id to with status: $statu");
		}
	}
	?>