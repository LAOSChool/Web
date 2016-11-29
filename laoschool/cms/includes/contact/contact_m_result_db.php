<?php

include("../../config.php");

ensure_permission('contact');
ensure_role('sadmin,admin,mod,view');

$supname = $_REQUEST['supname'];
$page = $_REQUEST['page'];
$limit = $_REQUEST['limit'];


if( $limit =='') $limit = 20;
if($page=='') $page = 1;
$pages = ($page-1) * $limit;

$requestparam .= "";
foreach($_REQUEST as $key=>$value){
	$requestparam .= "$key=$value&";
}

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
	
	<table class="table table-striped table-bordered table-advance table-hover">
		<thead> 
			<tr> 
				<th>Ngày liên hệ</th>
				<th>Tên</th>
				<th>Email</th>
				<th>Số điện thoại</th>
				<th>Trạng thái</th>
				<th>Xóa</th>
			</tr>
		</thead> 
eot;
	echo "<tbody>";
	$res = $db->query("select * from vhs_cobtact order by `datetime` desc limit $pages,$limit");

	while($row = $res->fetchRow()){
		$row['readstatus'] = ($row['isread'] == 0)?"<span class='badge badge-important'>Chưa Xem</span>":"<span class='badge badge-success'><i class='icon-ok'></i> Đã xem</span>";
		echo "<tr style='cursor:pointer' class='readmore' qid='$row[id]'>
			<td>$row[datetime]</td>
			<td>$row[name]</td>
			<td>$row[email]</td>
			<td>$row[phone]</td>
			<td>$row[readstatus]</td>
			<td align=center width=20><a title='Remove' class='btn btn-mini btn-danger' id='delbutton' qid='$row[id]' href='javascript:void(0)'><i class='icon-remove'></a></td>
		</tr>";
	};
	echo "";
	echo "</tbody>";
	echo "</table>";
	
	$rrr = $db->getone("select count(*) from vhs_cobtact");
	
	$ceil = ceil($rrr / $limit);
	$next = $page+1; $back = $page-1;
	if($back<=0) $back = 1;
	if($next>$ceil) $next = $ceil;
	
echo <<<eot
	<div class="row-fluid">
		<div class="span6">
			<div class="dataTables_info">
				Tổng số <b>$rrr</b> bản ghi trong <b>$ceil</b> trang, bạn đang ở trang <b>$page</b>.
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
		loadform('includes/contact/contact_m_result_db.php?$requestparam&page='+$(this).attr('page')+'&limit=$limit','#entry','#pageloadtext');
	});
	
	$("select[name=limit]").change(function() {
		loadform('includes/contact/contact_m_result_db.php?$requestparam&page=1&limit='+$(this).val(),'#entry','#limitloadtext')
	});
	
	$('.tooltips').tooltip();
</script>
eot;
?>
