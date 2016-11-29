<?php
	include('../../config.php');
	ensure_permission('ivr');
	ensure_role('mod,sadmin,admin');
	
	
	if(isset($_REQUEST['submit'])){
		
		$com_id = $_SESSION[$config_session]['com_id'];
		$id = $_REQUEST['id'];
		$name = $_REQUEST['name'];
		$key_pressed = $_REQUEST['key_pressed'];
		$content = $_REQUEST['content'];
		$uploadedfile = $_REQUEST['uploadedfile'];
		$parrent = $db->getone("select parrent from `ivr` where id=$id and com_id=$com_id");
		//die($money);
		$err = '';
		
		if($uploadedfile=='' && $content=='') $err = 'Have no <b>Sound file</b> is uploaded';
		//echo "select count(*) from `ivr` where parrent=$parrent and key_pressed=$key_pressed and com_id=$com_id and id<>$id";
		$c = $db->getone("select count(*) from `ivr` where parrent=$parrent and key_pressed=$key_pressed and com_id=$com_id and id<>$id");
		if($c>0) $err = '<b>Key presssed</b> is existed in this node';
		if($key_pressed=='') $err = 'Please choose <b>Key presssed</b>';
		if($name=='') $err = 'Please fill <b>Node name</b>';
		if($id=='') $err = 'Can not get ID, please try again.';
		if($com_id=='') $err = 'Can not get company info, please try again.';
		
		if($err!=""){
			echo "<div class='alert alert-error'><i class='icon-info-sign'></i> $err</div>";
		}else{
			
			if($content==''){
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
				}
				
				$content = "$y/$m/$d/$filename";
			};
			$sql = "update `ivr` set `name` = '$name',`key_pressed` = '$key_pressed',`content` = '$content' where id=$id";
					//die($sql);
			$res = $db->query($sql);
			if(PEAR::isError($res)) $status[] =  $res->getຂໍ້​ຄວາມ();
	 
			if(count($status)>0){
				$statu = implode(', ',$status);
				echo "<div class='alert alert-error'>
						<i class='icon-bug'></i> Get some error. Let technicians know this.
					</div>";
			}else{
				$statu = "OKIE";
				
				
				echo "<div class='alert alert-success'><i class='icon-ok-sign'></i> $name is updated!</div>
					<script language='javascript'>
						loadform('includes/ivr/ivr_tree_db.php?lang=<?php echo $lang ?>','#entry','#loadtext');
						$('#queue').html('<input type=hidden class=medium name=\"content\" value=\"$content\">');
					</script>
				";
				//unlink("../../contents/ivr/$oldpath");
				
			}
			weblog($sql,"ອັບ​ເດດ song width id $id with status: $statu");
		}
	}
	?>