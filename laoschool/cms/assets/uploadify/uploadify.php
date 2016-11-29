<?php
$targetFolder = '../../uploads'; // Relative to the root

function gentimefile($time,$filetype){
	if(file_exists("../../uploads/$time"."$filetype")){
		$time=$time+1;
		$time = gentimefile($time,$filetype);
	}
	return $time;
};

if (!empty($_FILES)) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $targetFolder;
	$filetype = strtolower(substr($_FILES['Filedata']['name'],-4));
	$time = gentimefile(time(),$filetype);
	$targetFile = rtrim($targetPath,'/') . '/' .$time.$filetype;

	if(move_uploaded_file($tempFile,$targetFile))
		echo $time.$filetype;
		//echo rtrim($targetPath,'/') . '/' .$time.$filetype;
	else echo '0';
}
?>