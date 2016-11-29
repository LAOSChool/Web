<?php
	include('../../config.php');
	ensure_permission('config');
	if(isset($_REQUEST['submit'])){

		$res = $db->query("select * from vhs_config");
		while($res->fetchInto($row)){
			$value = (isset($_REQUEST[$row['name']]))?$_REQUEST[$row['name']]:$_POST[$row['name']];
			if(is_array($value)) $value = implode(',',$value);

			//echo "UPDATE vhs_config SET vn='$value' WHERE name='$row[name]'";
			
			$res2 = $db->query("UPDATE vhs_config SET `value` = '$value', la='$value',vn='$value',en='$value' WHERE name='$row[name]'");
			if(PEAR::isError($res2)) $status[] =  $res2->getMessage();
		}
		
		if(count($status)>0){
				$statu = implode(', ',$status);

				echo "<span class='label label-important'>
					<i class='icon-bug'></i> Gặp sự cố, xin báo cho VHSTEAM
				</span>";
				
			}else{
				$statu = "OKIE";
				
				echo "<span class='label label-success'><i class='icon-ok-sign'></i> Cập nhật thành công!</span>
				";
			}
			
	}