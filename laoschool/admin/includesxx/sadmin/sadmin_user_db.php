<?php
include("../../config.php");

ensure_permission('sadmin');
ensure_role('sadmin');

$page = $_REQUEST['page'];
$limit = $_REQUEST['limit'];
if( $limit =='') $limit = 20;
if($page=='') $page = 1;
$pages = ($page-1) * $limit;

//Describer query
$name = $_REQUEST['name'];
if($name!='') $namequery = "username like '%$name%'";
else $namequery = '1';

$requestparam .= "";
foreach($_REQUEST as $key=>$value){
	$requestparam .= "$key=$value&";
}
$requestparam = substr($requestparam,0,-1);

for($i=5;$i<=51;$i=$i*2){
	if($i==$limit) $sss = "selected";
	else $sss = "";
	$slt .= "<option $sss value='$i'>$i</option> \n";
}

echo<<<eot

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
	</div>
	
	<table class="table table-bordered table-hover dataTable">
		<thead> 
			<tr> 
				<th>Username</th>
				<th>Phone</th>
				<th>Role</th>
				<th>Permission</th>
				<th colspan=2 align=center>Action</th>
			</tr>
		</thead> 
eot;
	echo "<tbody>";
	$res = $db->query("select * from users where ($namequery) order by creat_time desc limit $pages,$limit");
	while($row = $res->fetchRow()){
		echo "<tr>
			<td>$row[username]</td>
			<td>$row[phone]</td>
			<td>$row[role]</td>
			<td>$row[permission]</td>
			<td align=center width=20><a title='Edit' class='btn btn-mini btn-warning' id='editbutton' qid='$row[id]' href='javascript:void(0)'><i class='icon-edit'></a></td>
			<td align=center width=20><a title='Remove' class='btn btn-mini btn-danger' id='delbutton' qid='$row[id]' href='javascript:void(0)'><i class='icon-remove'></a></td>
		</tr>";
	};
	echo "";
	echo "</tbody>";
	echo "</table>";
	
	$rrr = $db->getone("select count(*) from users where ($namequery)");
	
	$ceil = ceil($rrr / $limit);
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
		loadform('includes/sadmin/sadmin_user_db.php?$requestparam&page='+$(this).attr('page')+'&limit=$limit','#entry','#pageloadtext');
	});
	
	$("select[name=limit]").change(function() {
		loadform('includes/sadmin/sadmin_user_db.php?$requestparam&page=1&limit='+$(this).val(),'#entry','#limitloadtext')
	});
	
	$('.tooltips').tooltip();
</script>
eot;
?>
