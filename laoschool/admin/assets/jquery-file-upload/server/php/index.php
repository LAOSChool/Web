<?php
$targetFolder = '../../../../uploads'; // Relative to the root
if (!empty($_FILES)) {
	$tempFile = $_FILES['files']['tmp_name'][0];
	$targetPath = $targetFolder;
	$filetype = ".".end(explode('.',$_FILES['files']['name'][0]));
	
	$time = time();
	$targetFile = rtrim($targetPath,'/') . '/' .$time.$filetype;
	$filetypes = $_REQUEST['filetype'];
	
	$typesArray = split('\;',$filetypes);
	$fileParts  = pathinfo($_FILES['files']['name'][0]);
	
	$fp=fopen("a.txt",'w');
	fwrite($fp,print_r($fileParts,true));
	fclose($fp);

	$file['extension'] = $fileParts['extension'];
	$file['name'] = $time.$filetype;
	
	if (in_array($fileParts['extension'],$typesArray)) {
		if(move_uploaded_file($tempFile,$targetFile))
			;
		else $file['error'] = "Upload failed";
	} else {
		$file['error'] = 'Invalid file type.';
	}
	header('Content-type: application/json');
	echo json_encode($file);
}
?>