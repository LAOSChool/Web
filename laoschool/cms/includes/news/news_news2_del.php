<?php
include("../../config.php");
ensure_permission('news');

if(isset($_REQUEST['id'])){

	$id = $_REQUEST['id'];

	$sql = "DELETE FROM vhs_news WHERE id=$id";
	$res = $db->query($sql);
	
	if(PEAR::isError($res)) $status =  $res->getMessage();
	else $status = "OKIE";
	
	weblog($sql,"Delete from vhs_news where id $id with status: $status");
		
	echo "<img id=load src=gfx/loading.gif width=14 heigh=14>";
	echo "
		<script type='text/javascript'>
			loadform('includes/news/news_news2_db.php','#entry','#loadtext');
		</script>
	";
} 

?>