<div class="span12">
	<div class="widget blue">
		<div class="widget-title">
			<h4><i class="icon-reorder"></i> Select parameters</h4>
			<span class="tools">
				<span id="loadtext1"></span>
				<a href="javascript:;" class="icon-chevron-down"></a>
			</span>
		</div>
		<div class="widget-body" id="parameter">
			<form method=POST id='managerform' class="form-horizontal" action='includes/sendsms/sendsms_sender_send.php'>
				<div class="control-group">
					<label class="control-label">Send time</label>
					<div class="controls">
						 <div class="input-append bootstrap-timepicker">
							<input id="datepicker" class="input-medium" name="datetime" type="text" value="<?php echo date('Y-m-d')?>">
							<input id="timepicker" type="text" class="input-small">
							<span class="add-on btn"><i class="icon-time"></i></span>
						</div>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label">Turn name</label>
					<div class="controls">
						<input type="text" name="smsturn" value="" class="input-xlarge"/>
						<span class="help-inline" style='background:Green'>Text file, 1 nunber per line</span>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label">Number list</label>
					<div class="controls">
						<input name="uploadedfile" type="hidden">						
						
						<input id="file_upload" name="file_upload" type="file" />
						
						<div style="height: 28px; width: 93px;" class="uploadify" id="file_upload">
							<div style="background-image: url(&quot;img/uploadbtn.png&quot;); text-indent: -9999px; height: 28px; line-height: 28px; width: 93px;" class="uploadify-button " id="file_upload-button">
								<span class="uploadify-button-text"></span>
							</div>
						</div>
						
						<div class='uploadify'>sdf</div>
						<span class="help-inline" style='background:Green'>Text file, 1 nunber per line</span>
					
					</div>

				</div>

				<div class="control-group" id='level'>
					<label class="control-label">SMS Content</label>
					<div class="controls">
						 <textarea class="span6 " name='smscontent' rows="5"></textarea>
					</div>
				</div>
				
			
				<div class="form-actions">
					<button type="submit" class="btn btn-info" name="submit"><i class="icon-share"></i> Send SMS</button>
					<span id="submit"></span>
				</div>
			</form>
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
				loadform('includes/sendsms/sendsms_send_db.php','#entry','#loadtext');
			</script>
		</div>
	</div>
</div>

<script language="javascript">
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
		'buttonImage' : 'img/uploadbtn.png',
		'fileTypeDesc' : 'Sound file',
        'fileTypeExts' : '*.wav', 
		'multi'    : false,
		'queueSizeLimit' : 1,
		'itemTemplate' : '<span></span>',
		'buttonText' : '',
		'width':93,
		'height':28,
		'onUploadProgress' : function(file, bytesUploaded, bytesTotal, totalBytesUploaded, totalBytesTotal) {
			$('#queue').html("<font color=orange><b>"+file.name +"</b></font> is uploading... "+ Math.round((bytesUploaded/bytesTotal)*100)+'%');
        },
		'onUploadSuccess' : function(file, data, response) {
			$('input[name=uploadedfile]').val(data);
			$('#queue').html("<font color=green><b>"+file.name + "</b></font> uploaded successfully!")
			$('#file_upload').uploadify('disable', false);
		},
		'onSelect' : function(file) {
			$('input[name=uploadedfile]').val('');
			$('#queue').html('Reading file...');
			$('#file_upload').uploadify('disable', true);
        },
		'onCancel' : function(file) {
			$('input[name=uploadedfile]').val('');
        },
		'onUploadError' : function(file, errorCode, errorMsg, errorString) {
			$('#queue').html("<font color=red><b>"+file.name +"</b> could not be uploaded: "+ errorString+"</font>");
        }
    }); 
	
	$('#delbutton').live('click',function(){
		if(confirm('Are you sure?')) loadform('includes/sendsms/sendsms_send_del.php?id='+$(this).attr('qid'),$(this).parent(),$(this).parent());
	});
	
	$('#editbutton').live('click',function(){
		loadform('includes/sendsms/sendsms_send_form.php?type=edit&id='+$(this).attr('qid'),'#parameter',$(this));
		$(document).scrollTo('#parameter',{duration:1000})
	});
</script>