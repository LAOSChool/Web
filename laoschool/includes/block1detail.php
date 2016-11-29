<?php
	include("../config.php");
	$id = $_REQUEST['id'];

	$bl1row = $dbp->getrow("select * from vhs_news where id='$id'");
	echo nl2br($bl1row['detail']);
	
?>