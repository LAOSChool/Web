<?php
	include('../../config.php');
	
	ensure_permission('sadmin');
	ensure_role('sadmin');
	
	if(isset($_REQUEST['submit'])){
		$res = $db->query("select * from usssd_params");
		while($res->fetchInto($row)){
			
			$value = (isset($_REQUEST[$row['param_name']]))?$_REQUEST[$row['param_name']]:$_POST[$row['param_name']];
			if(is_array($value)) $value = implode(',',$value);

			$res2 = $db->query("UPDATE usssd_params SET param_value='$value' WHERE param_name='$row[param_name]'");
			if(PEAR::isError($res2)) $status[] =  $res2->getຂໍ້​ຄວາມ();
		}
		
		if(count($status)>0){
			$statu = implode(', ',$status);

			echo "<span class='label label-important'>
				<i class='icon-bug'></i> Get some error. Let technicians know this.
			</span>";
			
		}else{
			$statu = "OKIE";
			echo "<span class='label label-success'><i class='icon-ok-sign'></i> ອັບ​ເດດ success fully!</span>
			";
		}
		weblog('Many sql',"ອັບ​ເດດ system setting with status: $statu");	
	}