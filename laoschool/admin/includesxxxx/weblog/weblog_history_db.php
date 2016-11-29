<?php
include("../../config.php");
ensure_permission('weblog');

$requestparam .= "";
foreach($_REQUEST as $key=>$value){
	$requestparam .= "$key=$value&";
}

// Get condition
$username = $_REQUEST['username'];
$description = $_REQUEST['description'];
// Get date
$startdate = $_REQUEST['startdate'];
$enddate = $_REQUEST['enddate'];
if($startdate=="From") $startdate = "";
if($enddate=="To") $enddate = "";

$page = $_REQUEST['page'];
$limit = $_REQUEST['limit'];

if( $limit =='') $limit = 20;

if($page=='') $page = 1;
$pages = ($page-1) * $limit;

//username Query
if ($username!=""){
	$usernamequery = "username like '%$username%' ";
}else{
	$usernamequery = "1";
}

//username Query
if ($description!=""){
	$desquery = "description like '%$description%' ";
}else{
	$desquery = "1";
}

$err = "";
//Time Query
if ($startdate!="" && $enddate!=""){
	$timequery = "date(time_call) >= '$startdate' and date(time_call) <= '$enddate'";
}elseif($enddate!=""){
	$timequery = "date(time_call) <= '$enddate'";
}elseif($startdate!="" ){
	$timequery = "date(time_call) >= '$startdate'";		
}else{
	$timequery = "1";
}
if ($startdate > $enddate) {
	$err .= " - To date can not less than From date";
	$timequery = "1";
}

if ($err!=""){
	echo "<div class=\"messages orange\">";
    echo "<span></span> $err;";
	echo "</div>";
}

for($i=5;$i<=51;$i=$i*2){
	if($i==$limit) $sss = "selected";
	else $sss = "";
	$slt .= "<option $sss value='$i'>$i</option> \n";
}

echo <<<eot

<div class="dataTables_wrapper form-inline">
	<div class="row-fluid">
		<div class="span6">
			<div class="limit">
				<label>
					<select class="input-mini" name="limit">
						$slt
					</select> records per page 
					<span id='limitloadtext'></span>
				</label>
			</div>
		</div>
		<div class="span6">
			<div id="sample_1_filter" class="dataTables_filter"><label></label></div>
		</div>
	</div>

	<table class="table table-bordered table-hover dataTable">
		<thead> 
			<tr>
				<th width=50 align='center'>No.</th> 
				<th width=80 align='center'>User name</th>
				<th>Description</th>
				<th>Date time</th>
			</tr> 
		</thead> 
eot;
	$order = "order by datetime desc";

	$sql = "select * from weblog where ($usernamequery) and ($timequery) and ($desquery) $order limit $pages,$limit";
			$res = $db->query($sql);
			
			$i=0; $stt=1;
			echo "<tbody>";


			while($res->fetchInto($row)){
				$des = snippet($row['description'], 98, "...");
				if($i%2==0) $bg = "class='event'";
				else $bg = "class='odd'";
					echo "
					<tr $bg>
						<td style='font-weight:bold' align=center>$stt</td>
						<td >$row[username]</td>
						<td >$des</td>
						<td >$row[datetime]</td>
					</tr>";
			
					$i++; $stt++;
			};
		
			echo "</tbody>";
echo "</table>";

$rrr = $db->getone("select count(*) from weblog where ($usernamequery) and ($timequery) and ($desquery)");

$ceil = ceil($rrr / $limit);
if ($ceil<=0) $ceil =1;
$next = $page+1; $back = $page-1;
if($back<=0) $back = 1;
if($next>$ceil) $next = $ceil;


echo <<<eot
	<div class="row-fluid">
		<div class="span6">
			<div class="dataTables_info">
				Total <b>$rrr</b> records in <b>$ceil</b> pages, you are in page <b>$page</b>.
			</div>
		</div>
		<div class="span6">
			<div class="dataTables_paginate paging_bootstrap pagination">
				<ul>
					<li><span id="pageloadtext" class='hide'></span></li>
					<li><a href="javascript:;" class='changepage' page='$back'><i class="icon-chevron-left"></i></a></li>
					<li><a href="javascript:;" class='tooltips' data-original-title='Page $page of $ceil'><b>$page</b>/$ceil</a></li>
					<li><a href="javascript:;" class='changepage' page='$next'><i class="icon-chevron-right"></i></a></li>
				</ul>
			</div>
		</div>
	</div>
</div>
	<script language='javascript'>
		$('.changepage').click(function(){
			loadform('includes/weblog/weblog_history_db.php?$requestparam&page='+$(this).attr('page')+'&limit=$limit','#entry','#pageloadtext');
		});
		
		$("select[name=limit]").change(function() {
			loadform('includes/weblog/weblog_history_db.php?$requestparam&page=1&limit='+$(this).val(),'#entry','#limitloadtext')
		});
		
		$('.tooltips').tooltip();
	</script>
eot;
	
?>