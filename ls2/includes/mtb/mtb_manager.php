<div class="span6">
	<div class="widget blue" id='goparameter'>
		<div class="widget-title">
			<h4><i class="icon-reorder"></i> <?php lang('sltparameters') ?></h4>
			<span class="tools">
				<span id="loadtext1"></span>
				<a href="javascript:;" class="icon-chevron-down"></a>
			</span>
		</div>
		<div class="widget-body" id="parameter">
			<script language="javascript">
				loadform('includes/mtb/mtb_manager_form.php?lang=<?php echo $lang ?>','#parameter','#loadtext1');
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
				loadform('includes/mtb/mtb_manager_db.php?lang=<?php echo $lang ?>','#entry','#loadtext');
			</script>
		</div>
	</div>
</div>


<div id="modalform" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="modalform" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="modalform"><?php lang('detail') ?></h3>
	</div>
	<div class="modal-body" id='listenmusic'>
		
	</div>
	<div class="modal-footer">
		<span id='listenload'></span> 
		<button data-dismiss="modal" class="btn btn-danger"><?php lang('close') ?></button>
	</div>
</div>

<script language="javascript">
	$('#managerform').ajaxForm({
		target: '#submit', 
		beforeSubmit: function(){
			$('#submit').show().html('<span class="badge badge-warning"><i class="icon-spinner"></i> <?php lang('pleasewait') ?>....</span>'); 
		},
		success: function() { 
			;
		}
	}); 	
	
	$('#searchform').ajaxForm({ 
		target: '#entry', 
		beforeSubmit: function(){
			$('#searchtext').show().html('<img src="img/loading.gif">'); 
		},
		success: function() { 
			$('#searchtext').hide().html(''); 
		}
	});
	
	$('#datepicker').datepicker({
		format: 'yyyy-mm-dd'
	})
	
	$('#timepicker').timepicker({
		showMeridian: false,
		showSeconds: true
	});
	
	$('#delbutton').live('click',function(){
		if(confirm('Are you sure?')) loadform('includes/mtb/mtb_manager_del.php?lang=<?php echo $lang ?>&id='+$(this).attr('qid'),$(this).parent(),$(this).parent());
	});
	
	$('#editbutton').live('click',function(){
		loadform('includes/mtb/mtb_manager_form.php?lang=<?php echo $lang ?>&type=edit&id='+$(this).attr('qid')+'&table='+$(this).attr('qtable'),'#parameter','#loadform');
		$(document).scrollTo('#goparameter',1000, {offset:-61})
	});
	
	$('#listenbutton').live('click',function(){
		$('#listenmusic').html('');
		loadform('includes/mtb/mtb_manager_popmusic.php?id='+$(this).attr('qid'),'#listenmusic','#listenload');
		$('#modalform').modal('show');
	});
	
	$('.exam_r').live('change',function(){
		var djson = $(this).attr('d-json');
		//djson = djson.replace('xxxx', $(this).val());
		djson = encodeURI(djson.replace('xxxx',$(this).val()));
		//alert(djson);
		loadform("includes/mtb/mtb_manager_send.php?lang=<?php echo $lang ?>&djson="+djson,$(this));
	});
	
	$('.fulltext').live('click',function(){
		$('#listenmusic').html($(this).attr('data-original-title').replace(/\n/g,"<br>"));
		//loadform('includes/broadcast/broadcast_ussd_popmusic.php?id='+$(this).attr('qid'),'#listenmusic','#listenload');
		$('#modalform').modal('show');
	});
</script>