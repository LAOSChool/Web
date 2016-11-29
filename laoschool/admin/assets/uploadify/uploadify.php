<?php
$targetFolder = '../../uploads'; // Relative to the root

if (!empty($_FILES)) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $targetFolder;
	$filetype = strtolower(substr($_FILES['Filedata']['name'],-4));
	$time = time();
	$targetFile = rtrim($targetPath,'/') . '/' .$time.$filetype;

	 $fileTypes  = str_replace('*.','',$_REQUEST['fileext']);
	 $fileTypes  = str_replace(';','|',$fileTypes);
	 $typesArray = split('\|',$fileTypes);
	 $fileParts  = pathinfo($_FILES['Filedata']['name']);

	//if (in_array($fileParts['extension'],$typesArray)){
		if(move_uploaded_file($tempFile,$targetFile))
			echo $time.$filetype;
		else echo '0';
	// } else {
	 	//echo 'Invalid file type.';
	// }
}
?>