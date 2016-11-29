<?php
include("../../config.php");
ensure_permission('news');
ensure_role('mod,sadmin,admin');

echo<<<eot

<div class="dataTables_wrapper form-inline">
	<table class="table table-bordered table-hover dataTable">
		<thead> 
			<tr> 
				<th>Tên Giáo sư</th>
				<th>Thứ tự</th>
				<th>Ngôn ngữ</th>
				<th align=center colspan=2>Tùy chọn</th>
			</tr>
		</thead> 
eot;
	echo "<tbody>";

	$res = $db->query("select * from vhs_news where caid=5 order by `order` asc");
	
	while($row = $res->fetchRow()){
		echo "<tr id='row$row[id]'>
			<td>$row[title]</td>
			<td>$row[order]</td>
			<td>$row[lang]</td>
			<td align=center width=20><a title='Xem ảnh' class='tooltips btn btn-mini btn-info' id='listenbutton' qid='$row[image]' href='javascript:void(0)'><i class='icon-picture'></i></a></td>
			<td align=center width=20><a title='Sửa' class='tooltips btn btn-mini btn-warning' id='editbutton' qid='$row[id]' href='javascript:void(0)'><i class='icon-edit'></a></td>
			<td align=center width=20><a title='Xóa' class='tooltips btn btn-mini btn-danger' id='delbutton' qid='$row[id]' href='javascript:void(0)'><i class='icon-remove'></a></td>
		</tr>";
	};
	echo "";
	echo "</tbody>";
	echo "</table>";
	
echo <<<eot
<script language='javascript'>
	$('.tooltips').tooltip();
</script>
eot;
?>

	