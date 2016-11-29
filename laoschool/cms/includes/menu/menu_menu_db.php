<?php

include("../../config.php");
ensure_permission('menu');


$page = $_REQUEST['page'];
$limit = $_REQUEST['limit'];
if( $limit =='') $limit = 20;
if($page=='') $page = 1;
$pages = ($page-1) * $limit;

//title query
$title = $_REQUEST['title'];
if($title!='') $titlequery = "title like '%$title%'";
else $titlequery = '1';

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
					</select> bản ghi mỗi trang
					<span id='limitloadtext'></span>
				</label>
			</div>
		</div>
	</div>
	
	<table class="table table-bordered table-hover dataTable">
		<thead> 
			<tr> 
				<th>Kiểu menu</th>
				<th>Tiêu đề</th>
				<th>Thứ tự</th>
				<th>Ngôn ngữ</th>
				<th align=center>Sửa</th>
			</tr>
		</thead> 
eot;
	echo "<tbody>";
	
	$res = $db->query("select * from vhs_menu where ($titlequery) order by lang asc, `order` asc limit $pages,$limit");

	while($row = $res->fetchRow()){
		if($row['type']=='mencontent') $row['type'] = "Content";
		if($row['type']=='menlink') $row['type'] = "Link";
		echo "<tr>
			<td>$row[type]</td>
			<td>$row[title]</td>
			<td>$row[order]</td>
			<td>$row[lang]</td>
			<td align=center width=20><a title='Sửa' class='btn btn-mini btn-warning' id='editbutton' qid='$row[id]' href='javascript:void(0)'><i class='icon-edit'></a></td>
		</tr>";
	};
	echo "";
	echo "</tbody>";
	echo "</table>";
	
	$rrr = $db->getone("select count(*) from vhs_menu where ($titlequery)");
	
	$ceil = ceil($rrr / $limit);
	$next = $page+1; $back = $page-1;
	if($back<=0) $back = 1;
	if($next>$ceil) $next = $ceil;
	
echo <<<eot
	<div class="row-fluid">
		<div class="span6">
			<div class="dataTables_info">
				Có <b>$rrr</b> bản ghi trong <b>$ceil</b> trang. Trang <b>$page</b>.
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
		loadform('includes/menu/menu_menu_db.php?$requestparam&page='+$(this).attr('page')+'&limit=$limit','#entry','#pageloadtext');
	});
	
	$("select[name=limit]").change(function() {
		loadform('includes/menu/menu_menu_db.php?$requestparam&page=1&limit='+$(this).val(),'#entry','#limitloadtext')
	});
	
	$('.tooltips').tooltip();
</script>
eot;
?>
