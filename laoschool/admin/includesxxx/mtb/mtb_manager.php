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
	
	$('#file_upload').uploadify({
        'swf'      : 'assets/uploadify/uploadify.swf',
        'uploader' : 'assets/uploadify/uploadify.php',
		'checkExisting' : 'assets/uploadify/check-exists.php',
		
		'fileTypeDesc' : 'Sound file',
        'fileTypeExts' : '*.txt', 
		'multi'    : false,
		'queueSizeLimit' : 1,
		'itemTemplate' : '<span></span>',
		'buttonText' : 'ອັບ​ໂຫຼດ...',
		'width':93,
		'height':28,
		'onອັບ​ໂຫຼດProgress' : function(file, bytesອັບ​ໂຫຼດed, bytesTotal, totalBytesອັບ​ໂຫຼດed, totalBytesTotal) {
			$('#queue').html("<span class='badge badge-info'>"+file.name +"</span> ອັບ​ໂຫຼດing... "+ Math.round((bytesອັບ​ໂຫຼດed/bytesTotal)*100)+'%');
        },
		'onອັບ​ໂຫຼດSuccess' : function(file, data, response) {
			$('input[name=uploadedfile]').val(data);
			$('#queue').html("<span class='badge badge-success'>"+file.name + "</span> ອັບ​ໂຫຼດສຳ​ເລັດ!")
			$('#file_upload').uploadify('disable', false);
		},
		'onSelect' : function(file) {
			$('input[name=uploadedfile]').val('');
			$('#queue').html("<span class='badge badge-warning'>"+file.name +"</span> Reading...");
			$('#file_upload').uploadify('disable', true);
        },
		'onCancel' : function(file) {
			$('input[name=uploadedfile]').val('');
        },
		'onອັບ​ໂຫຼດError' : function(file, errorCode, errormtb, errorString) {
			$('#queue').html("<span class='badge badge-important'>"+file.name +"</span> could not be uploaded: "+ errorString);
        }
    }); 
	
	$('#file_upload-button').prepend('<i class="icon-upload-alt"></i> ');
	
	$('#delbutton').live('click',function(){
		if(confirm('Are you sure?')) loadform('includes/mtb/mtb_manager_del.php?id='+$(this).attr('qid'),$(this).parent(),$(this).parent());
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
		loadform("includes/mtb/mtb_manager_send.php?djson="+djson,$(this));
	});
	
	$('.fulltext').live('click',function(){
		$('#listenmusic').html($(this).attr('data-original-title').replace(/\n/g,"<br>"));
		//loadform('includes/broadcast/broadcast_ussd_popmusic.php?id='+$(this).attr('qid'),'#listenmusic','#listenload');
		$('#modalform').modal('show');
	});
</script>