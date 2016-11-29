<div class="span12">
	<div class="widget blue" id='goparameter'>
		<div class="widget-title">
			<h4><i class="icon-reorder"></i> Select parameters</h4>
			<span class="tools">
				<span id="loadtext1"></span>
				<a href="javascript:;" class="icon-chevron-down"></a>
			</span>
		</div>
		<div class="widget-body" id="parameter">
			<script language="javascript">
				loadform('includes/admin/admin_user_form.php','#parameter','#loadtext1');
			</script>
		</div>
	</div>
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
				loadform('includes/admin/admin_user_db.php','#entry','#loadtext');
			</script>
		</div>
	</div>
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
		'buttonText' : 'Upload...',
		'width':93,
		'height':28,
		'onUploadProgress' : function(file, bytesUploaded, bytesTotal, totalBytesUploaded, totalBytesTotal) {
			$('#queue').html("<span class='badge badge-info'>"+file.name +"</span> Uploading... "+ Math.round((bytesUploaded/bytesTotal)*100)+'%');
        },
		'onUploadSuccess' : function(file, data, response) {
			$('input[name=uploadedfile]').val(data);
			$('#queue').html("<span class='badge badge-success'>"+file.name + "</span> Upload Successfully!")
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
		'onUploadError' : function(file, errorCode, errorMsg, errorString) {
			$('#queue').html("<span class='badge badge-important'>"+file.name +"</span> could not be uploaded: "+ errorString);
        }
    }); 
	
	$('#file_upload-button').prepend('<i class="icon-upload-alt"></i> ');
	
	$('#delbutton').live('click',function(){
		if(confirm('Bạn chắc chứ?')) loadform('includes/admin/admin_user_del.php?id='+$(this).attr('qid'),$(this).parent(),$(this).parent());
	});
	
	$('#editbutton').live('click',function(){
		loadform('includes/admin/admin_user_form.php?type=edit&id='+$(this).attr('qid'),'#parameter','#loadform');
		$(document).scrollTo('#goparameter',1000, {offset:-61})
	});
</script>