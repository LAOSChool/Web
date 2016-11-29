<div class="span12">
	<div class="widget green">
		<div class="widget-title">
			<h4><i class="icon-reorder"></i> <?php echo $currentsubtab ?></h4>
			<span class="tools">
				<span id="loadtext"></span>
				<a href="javascript:;" class="icon-refresh" id="result"></a>
				<a href="javascript:;" class="icon-chevron-down"></a>
			</span>
		</div>
		<div class="widget-body" id="entry">
			<script language="javascript">
				var hash = window.location.hash.substring(1);
				if(hash=='') loadform('includes/contact/contact_m_result_db.php','#entry','#loadtext');
				else loadform('includes/contact/contact_m_result_read.php?id='+hash,'#entry','#loadtext');
			</script>
		</div>
	</div>
</div>

<script language="javascript">
	$('#editbutton').live('click',function(){
		$(document).scrollTo('#parameter',500);
		loadform('includes/contact/contact_m_result_form.php?type=edit&id='+$(this).attr('qid'),'#parameter','#submit');
	});
	
	$('#delbutton').live('click',function(e){
		 e.stopPropagation();
		if(confirm('Bạn chắc chứ?')) loadform('includes/contact/contact_m_result_del.php?id='+$(this).attr('qid'),$(this).parent(),$(this).parent());
	});
	
	$('.icon-refresh').click(function(){
		loadform('includes/contact/contact_m_'+$(this).attr('id')+'_db.php','#entry','#loadtext');
	})

	$(window).on('hashchange',function(){
		var hash = window.location.hash.substring(1);
		if(hash!='') loadform('includes/contact/contact_m_result_read.php?id='+hash,'#entry','#limitloadtext');
	});
	
	$('.readmore').live('click',function(){
		window.location = '#'+$(this).attr('qid');
	})
</script>