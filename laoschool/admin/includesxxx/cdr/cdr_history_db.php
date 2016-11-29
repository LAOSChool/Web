<?php
include("../../config.php");
ensure_permission('cdr');
ensure_role('sadmin,admin');


$orderfeild = 'start_time';
$ordertype = 'desc';

$orderfeild = ($_REQUEST['orderfeild']=='')?$orderfeild:$_REQUEST['orderfeild'];
$ordertype = ($_REQUEST['ordertype']=='')?$ordertype:$_REQUEST['ordertype'];

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

//source_number Query
if ($source_number!=""){
	$source_numberquery = "source_number like '%$source_number%' ";
}else{
	$source_numberquery = "1";
}


$err = "";
//Time Query
if ($startdate!="" && $enddate!=""){
	$timequery = "date(start_time) >= '$startdate' and date(start_time) <= '$enddate'";
}elseif($enddate!=""){
	$timequery = "date(start_time) <= '$enddate'";
}elseif($startdate!="" ){
	$timequery = "date(start_time) >= '$startdate'";		
}else{
	$timequery = "1";
}

$requestparam .= "";
foreach($_REQUEST as $key=>$value){
	$requestparam .= "$key=$value&";
}

for($i=5;$i<=51;$i=$i*2){
	if($i==$limit) $sss = "selected";
	else $sss = "";
	$slt .= "<option $sss value='$i'>$i</option> \n";
}

echo <<<eot

<div class="dataTables_wrapper form-inline">
	
	<div class="clearfix">
		<div class="btn-group">
			<div class="limit">
				<label>
					<select class="input-mini" name="limit">
						$slt
					</select> records per page 
					<span id='limitloadtext'></span>
				</label>
			</div>
		</div>
		
		<div class="btn-group pull-right">
			<button class="btn dropdown-toggle" data-toggle="dropdown">Tools <i class="icon-angle-down"></i>
			</button>
			<ul class="dropdown-menu pull-right">
				<li><a href="#">Print</a></li>
				<li><a href="#">Save as PDF</a></li>
				<li><a href="#">Export to Excel</a></li>
			</ul>
		</div>
		
		<div class="pull-right">
			 <a href="includes/cdr/cdr_history_csv.php?$requestparam" class="btn"><i class="icon-table"></i> Export to CSV</a>
		</div>
	</div>

	<table class="table table-bordered table-hover dataTable">
		<thead> 
			<tr>
				<th>Phone number</th>
				<th>Call to</th>
				<th id="start_time" class="sorting">Date time</th>
				<th>Duration</th>
				<th>Network</th>
			</tr> 
		</thead> 
eot;
	
	$sql = "select * from cdr where ($source_numberquery) and ($timequery) order by $orderfeild $ordertype limit $pages,$limit";
			$res = $db->query($sql);
			
			$i=0; $stt=1;
			echo "<tbody>";

			
			while($res->fetchInto($row)){
				$row['duration_seconds'] = sec2hms($row['duration_seconds']);
				echo "
				<tr>
					<td>$row[source_number]</td>
					<td>$row[destination_number]</td>
					<td>$row[start_time]</td>
					<td>$row[duration_seconds]</td>
					<td>$row[network_addr]</td>
				</tr>";
		
				$i++; $stt++;
			};
		
			echo "</tbody>";
echo "</table>";

$rrr = $db->getone("select count(*) from cdr where ($source_numberquery) and ($timequery)");

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
		$('#$orderfeild').addClass("sorting_{$ordertype}")
	
		$('.sorting').click(function(){
			orderfeild = $(this).attr('id');
			if($(this).hasClass('sorting_asc')) ordertype = 'desc';
			else if($(this).hasClass('sorting_desc')) ordertype = 'asc';
			else ordertype = 'desc';
			loadform('includes/cdr/cdr_history_db.php?$requestparam&orderfeild='+orderfeild+'&ordertype='+ordertype,'#entry','#loadtext');
		})
		
		$('.changepage').click(function(){
			loadform('includes/cdr/cdr_history_db.php?$requestparam&page='+$(this).attr('page')+'&limit=$limit','#entry','#pageloadtext');
		});
		
		$("select[name=limit]").change(function() {
			loadform('includes/cdr/cdr_history_db.php?$requestparam&page=1&limit='+$(this).val(),'#entry','#limitloadtext')
		});
		
		$('.tooltips').tooltip();
	</script>
eot;
	
?>