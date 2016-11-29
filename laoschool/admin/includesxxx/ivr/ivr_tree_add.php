<?php
include("../../config.php");
	ensure_permission('ivr');
	ensure_role('mod,sadmin,admin');
	
	$com_id = $_SESSION[$config_session]['com_id'];
	$parrent = $_REQUEST['parrent'];
	$name = $_REQUEST['name'];
	$key_pressed = $_REQUEST['key_pressed'];
	$uploadedfile = $_REQUEST['uploadedfile'];

	$err = '';

	if($uploadedfile=='') $err = 'Have no <b>Sound file</b> is uploaded';
	$c = $db->getone("select count(*) from `ivr` where parrent=$parrent and key_pressed=$key_pressed and com_id=$com_id");
	if($c>0) $err = '<b>Key presssed</b> is existed in this node';
	if($key_pressed=='') $err = 'Please choose <b>Key presssed</b>';
	if($name=='') $err = 'Please fill <b>Node name</b>';
	if($parrent=='') $err = 'Can not get parrent, please try again.';
	if($com_id=='') $err = 'Can not get company info, please try again.';
	$cc = $db->getone("select count(*) from `ivr` where com_id=$com_id and parrent=0");
	if($cc>0 && $parrent==0) $err = 'Only 1 IVR tree is allowed';
	
	if($err==""){
		$filename = end(explode('/',$uploadedfile));
		$filetype = end(explode('.',$filename));
		$time = reset(explode('.',$filename));
		$y = date('Y',$time);
		$m = date('m',$time);
		$d = date('d',$time);
	
		if (!is_dir("../../contents/ivr/$y/")) mkdir("../../contents/ivr/$y");         
		if (!is_dir("../../contents/ivr/$y/$m")) mkdir("../../contents/ivr/$y/$m");         
		if (!is_dir("../../contents/ivr/$y/$m/$d")) mkdir("../../contents/ivr/$y/$m/$d");         
		
		if(!rename("../../uploads/".$uploadedfile,"../../contents/ivr/$y/$m/$d/$filename")) $status[] = "Can't move sound file.";
		
		if($status[0]!="Can't move sound file." && $filetype == 'wav'){
			/*$convertfile = "/sounds/tree/musics/$y/$m/$d/$filename";
			try{
				ini_set("soap.wsdl_cache_enabled", "0");
				$client = new SoapClient('http://10.10.1.54:8080/axis2/services/treeFadaoWS?wsdl');
				$result = $client->convert_wav2mp3(array('wav_file'=>$convertfile));
				$info = $result->return;
				$filename = $info;
			}catch (Exception $e) {
				print('SoapResult error:'.$e->getຂໍ້​ຄວາມ());
			}*/
			;
		}

		$content = "$y/$m/$d/$filename";
		
		$sql = "insert into ivr (`parrent`,`name`,`key_pressed`,`content`,`com_id`) values ('$parrent','$name','$key_pressed','$content','$com_id')";
		
		$res = $db->query($sql);
		if(PEAR::isError($res)) $status[] =  $res->getຂໍ້​ຄວາມ();
			
		if(count($status)>0){
			$statu = implode(', ',$status);
			echo "<div class='alert alert-error'>
					<i class='icon-bug'></i> Get some error. Let technicians know this.
				</div>";
		}else{
			$statu = "OKIE";
			
			echo "<div class='alert alert-success'><i class='icon-ok-sign'></i> <b>$name</b> has added!</div>
				<script language='javascript'>
					$('input[name=uploadedfile]').val('');
					loadform('includes/ivr/ivr_tree_db.php?lang=<?php echo $lang ?>','#entry','#loadtext');
				</script>
			";
		}
		
		weblog($sql,"Add IVR node $name with status: $statu");
		
	}else{
		echo "<div class='alert alert-error'><i class='icon-info-sign'></i> $err</div>";
	}; 
?>
