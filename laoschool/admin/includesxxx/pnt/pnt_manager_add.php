<?php
include("../../config.php");
	ensure_permission('pnt');
	ensure_role('mod,sadmin,admin');
		
	@$cats = ".".implode('.',$_POST['cat']).".";
	@$ccats = implode(',',$_POST['cat']);

	$ivrrow = $db->getRow("select * from category where id IN ($ccats) and type='ivr'",array());
	if(is_array($ivrrow)) if($ivrrow['parent_id']!='' && $ivrrow['parent_id']!=$ivrrow['id'].".") $cats = $cats.$ivrrow['parent_id'].".";
	
	$ivr_enable = $_REQUEST['ivr_enable'];
	$lname = $_REQUEST['lname'];
	$sname = $_REQUEST['sname'];
	
	if($_SESSION[$config_session]['role']=='sadmin'){ 
		if(isset($_REQUEST['cp_id'])) $cp_id = $_REQUEST['cp_id'];
	}else $cp_id = $_SESSION[$config_session]['cp_id'];
	
	$com_id = $_REQUEST['com_id'];
	$singer_id = $_REQUEST['singer_id'];
	$singer_code = $_REQUEST['singer_code'];
	$uploadedfile = $_REQUEST['uploadedfile'];
	$path = $_REQUEST['path'];
	$song_code = $_REQUEST['song_code'];
	$part_code = $_REQUEST['part_code'];
	$singer_list = $_REQUEST['singer_list'];
	$id = $_REQUEST['id'];
	$tone_code = $singer_code.$song_code.$part_code;
	$price_id = $db->getone("select price_id from category where id IN ($ccats) and price_id>0 limit 1");
	$approved =  1;

	$err = '';
	
	
	if($uploadedfile=='') $err = 'Have no <b>Sound file</b> is uploaded';
	$softcode = $db->getone("select softcode from company where id=$com_id");
	//if($softcode=='123' && strlen($part_code)!='2') $err = "<b>Part code</b> must be have 2 charater";
	if($softcode!='123' && strlen($part_code)!='1') $err = "<b>Part code</b> must be have 1 charater";
	if(!is_numeric($part_code)) $err = "<b>Part code</b> must be a number";
	if($db->getone("select count(*) from songs where `tone_code`='$tone_code'")>0){
		$err = "This <b>Song code</b> and <b>Part code</b> arealy exist";
	};
	if(strlen($song_code)!=3) $err = "<b>Song code</b> must have 3 digits";
	if(!is_numeric($song_code)) $err = "<b>Song code</b> must be a number";
	if($softcode==123){
		if( ($song_code<200 || $song_code>499) || ($song_code>299 && $song_code<400) ){
			$err = "<b>Song code</b> Must in 200-299 or 400-499";
		}
	}
		
	if($singer_code=='') $err = 'Please choose <b>Singer code</b>';
	if($singer_id=='') $err = 'Please choose <b>Singer</b>';
	if($com_id=='') $err = 'Please choose <b>Company</b>';
	if(!is_numeric($price_id)) $err = 'Please select <b>Types Of Songs</b>';
	$cc = $db->getone("select count(*) from category where id IN ($ccats) and price_id>0");
	if($cc>1){
		$err = 'Please chooose only 1 IVR category';
	}
	if($sname=='') $err = 'Please fill <b>Song short name</b>';
	if($lname=='') $err = 'Please fill <b>Song long name</b>';
	if($ivr_enable=='') $err = 'Please choose <b>IVR ສະຖາ​ນະ</b>';
	if($cp_id=='') $err = 'Please choose <b>CP</b>';
	
	if($err==""){
		$filename = end(explode('/',$uploadedfile));
		$filetype = end(explode('.',$filename));
		$time = reset(explode('.',$filename));
		$y = date('Y',$time);
		$m = date('m',$time);
		$d = date('d',$time);
	
		if (!is_dir("../../contents/$y/")) mkdir("../../contents/$y");         
		if (!is_dir("../../contents/$y/$m")) mkdir("../../contents/$y/$m");         
		if (!is_dir("../../contents/$y/$m/$d")) mkdir("../../contents/$y/$m/$d");         
		
		if(!rename("../../uploads/".$uploadedfile,"../../contents/$y/$m/$d/$filename")) $status[] = "Can't move sound file.";
		
		if($status[0]!="Can't move sound file." && $filetype == 'wav'){
			$convertfile = "/sounds/crbt/musics/$y/$m/$d/$filename";
			try{
				ini_set("soap.wsdl_cache_enabled", "0");
				$client = new SoapClient('http://10.10.1.54:8080/axis2/services/CrbtFadaoWS?wsdl');
				$result = $client->convert_wav2mp3(array('wav_file'=>$convertfile));
				$info = $result->return;
				$filename = $info;
			}catch (Exception $e) {
				print('SoapResult error:'.$e->getຂໍ້​ຄວາມ());
			}
		}

		$path = "$y/$m/$d/$filename";
		
		$sql = "insert into songs (`ivr_enable`,`sname`,`lname`,`cp_id`,`com_id`,`singer_id`,`singer_code`,`path`,`cats`,`song_code`,`tone_code`,`part_code`,`price_id`,`approved`)
		values ('$ivr_enable','$sname','$lname','$cp_id','$com_id','$singer_id','$singer_code','$path','$cats','$song_code','$tone_code','$part_code','$price_id','$approved')";
		
		$res = $db->query($sql);
		if(PEAR::isError($res)) $status[] =  $res->getຂໍ້​ຄວາມ();
			
		if(count($status)>0){
			$statu = implode(', ',$status);
			echo "<span class='label label-important'>
					<i class='icon-bug'></i> Get some error. Let technicians know this.
				</span>";
		}else{
			$statu = "OKIE";
			
			echo "<span class='label label-success'><i class='icon-ok-sign'></i> <b>$lname</b> has added!</span>
				<script language='javascript'>
					$('input[name=uploadedfile]').val('');
					loadform('includes/pnt/pnt_manager_db.php?lang=<?php echo $lang ?>','#entry','#loadtext');
				</script>
			";
		}
		
		weblog($sql,"Add songs $lname with status: $statu");
		
	}else{
		echo "<span class='label label-important'><i class='icon-info-sign'></i> $err</span>";
	}; 
?>
