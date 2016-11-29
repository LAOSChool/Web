<div class="span6">
	<div class="widget blue" id='goparameter'>
		<div class="widget-title">
			<h4><i class="icon-reorder"></i> Tùy chọn</h4>
			<span class="tools">
				<span id="loadtext1"></span>
				<a href="javascript:;" class="icon-chevron-down"></a>
			</span>
		</div>
		<div class="widget-body" id="parameter">
			<script language="javascript">
				loadform('includes/banner/banner_half_form.php','#parameter','#loadtext1');
			</script>
		</div>
	</div>
</div>
<div class="span6">
	<div class="widget green">
		<div class="widget-title">
			<h4><i class="icon-reorder"></i> <?php echo $currentsubtab ?></h4>
			<span class="tools">
				<span id="loadtext"></span>
				<a href="javascript:;" class="icon-chevron-down"></a>
			</span>
		</div>
		<div class="widget-body" id="entry">
			<script language="javascript">
				loadform('includes/banner/banner_half_db.php','#entry','#loadtext');
			</script>
		</div>
	</div>
</div>


<div id="modalform" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="modalform" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="modalform">Xem ảnh</h3>
	</div>
	<div class="modal-body" id='listenmusic'>
		
	</div>
	<div class="modal-footer">
		<span id='listenload'></span> 
		<button data-dismiss="modal" class="btn btn-danger" onclick="$('#html5player')[0].pause();">Đóng</button>
	</div>
</div>

<script language="javascript">
	$('#delbutton').live('click',function(){
		if(confirm('Bạn chắc chứ?')) loadform('includes/banner/banner_half_del.php?id='+$(this).attr('qid'),$(this).parent(),$(this).parent());
	});
	
	$('#editbutton').live('click',function(){
		loadform('includes/banner/banner_half_form.php?type=edit&id='+$(this).attr('qid'),'#parameter','#loadform');
		$(document).scrollTo('#goparameter',1000, {offset:-61})
	});
	
	$('#listenbutton').live('click',function(){
		$('#listenmusic').html('');
		$('#listenmusic').html("<img src='<?php echo "$rootfile/images/"; ?>"+$(this).attr('qid')+"'>");
		$('#modalform').modal('show');
	});
</script>