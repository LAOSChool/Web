<?php
	include('../../config.php');
	ensure_permission('contact');
	ensure_role('sadmin,admin,mod,view');
	
	
	$id = $_REQUEST['id'];
	$res = $db->query("select * from vhs_cobtact where id=$id");
	$res->fetchInto($row);

	$db->query("update vhs_cobtact set isread=1 where id=$id");
?>
<div class="clearfix">

	<button id='backcontact' class="btn btn-primary">
		<i class="icon-long-arrow-left"></i> Trở lại
	</button> 
	<span id='loadtextback'></span>

</div>
<div class="space15"></div>
								 
 <div class="well">
	<dl class="dl-horizontal">
		<dt>Ngày liên hệ</dt>
		<dd><?php echo $row['datetime']; ?></dd>
		
		<dt>Họ Tên</dt>
		<dd><?php echo $row['name']; ?></dd>
		
		<dt>Nơi ở</dt>
		<dd><?php echo $row['khuvuc']; ?></dd>
		
		<dt>Số điện thoại</dt>
		<dd><?php echo $row['phone']; ?></dd>
		
		<dt>Email</dt>
		<dd><a href='mailto:<?php echo $row['email']; ?>'><?php echo $row['email']; ?></a></dd>
		
		<dt>Trình độ học vấn</dt>
		<dd><?php echo $row['trinhdo']; ?></dd>
		
		<dt>Khóa học</dt>
		<dd><?php echo $row['khoahoc']; ?></dd>

	</dl>
</div>
<script language="javascript">

	$('#managerform').ajaxForm({
		target: '#submit', 
		beforeSubmit: function(){
			$('#submit').show().html('<span class="badge badge-warning"><i class="icon-spinner"></i> Please wait....</span>'); 
		},
		success: function() { 
			;
		}
	});
	
	$('#backcontact').click(function(){
		window.location.hash = '';
		loadform('includes/contact/contact_m_result_db.php','#entry','#loadtextback');
	})
</script>