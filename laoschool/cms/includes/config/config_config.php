
<?php
$cres = $db->query("select * from vhs_config");
while($cres->fetchInto($crow)){
	$v = $crow['name'];
	$$v = $crow['la'];
}
?>
<div class="span12">	
	<div class="widget blue">	
		<div class="widget-title">			
			<h4><i class="icon-reorder"></i> <?php echo $currentsubtab ?></h4>			
			<span class="tools">				
				<span id='monthy_load2'></span>			
				<a href="javascript:;" class="icon-chevron-down"></a>				
			</span>		
		</div>	
		
		<div class="widget-body">			

			<form method=POST id='managerform' class="form-horizontal" action='includes/config/config_config_edit.php'>
				<fieldset>
					 <legend>Cấu hình chung</legend>
				</fieldset>
				
				<div class="control-group">
					<label class="control-label">Tên website</label>
					<div class="controls">
						<input type="text" name="webtitle" value="<?php echo $webtitle; ?>" class="input-xlarge"/> 
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label">Solgan</label>
					<div class="controls">
						<input type="text" name="subtitle" value="<?php echo $subtitle; ?>" class="input-xlarge"/> 
					</div>
				</div>

				<div class="control-group">
					<label class="control-label">Lời chào</label>
					<div class="controls">
						<input type="text" name="welcome" value="<?php echo $welcome; ?>" class="input-xxlarge"/> 
					</div>
				</div>		
				
				<div class="control-group">
					<label class="control-label">Ghi chú</label>
					<div class="controls">
						<input type="text" name="subwelcome" value="<?php echo $subwelcome; ?>" class="input-xxlarge"/> 
					</div>
				</div>	
				
				<div class="control-group">
					<label class="control-label">Mô tả chung</label>
					<div class="controls">
						<textarea class="span10" name="description" rows="3"><?php echo $description; ?></textarea>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label">Từ khóa</label>
					<div class="controls">
						<input type="text" name="keywords" class="tags" value="<?php echo $keywords; ?>"/>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label">Chân trang</label>
					<div class="controls">
						<textarea class="span10 wysihtmleditor5" name="footer" rows="4"><?php echo $footer; ?></textarea>
					</div>
				</div>
				
				<fieldset>
					<legend>Thông tin liên lạc</legend>
				 </fieldset>
				 <div class="control-group">
					<label class="control-label">Hot line</label>
					<div class="controls">
						<input type="text" name="hotline" value="<?php echo $hotline; ?>" class="input-xlarge"/> 
					</div>
				</div> 
				
				<div class="control-group">
					<label class="control-label">Fax</label>
					<div class="controls">
						<input type="text" name="fax" value="<?php echo $fax; ?>" class="input-xlarge"/> 
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label">Email</label>
					<div class="controls">
						<input type="text" name="email" value="<?php echo $email; ?>" class="input-xlarge"/> 
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label">Địa chỉ</label>
					<div class="controls">
						<input type="text" name="address" value="<?php echo $address; ?>" class="input-xxlarge"/> 
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label">Facebook</label>
					<div class="controls">
						<input type="text" name="facebook" value="<?php echo $facebook; ?>" class="input-xlarge"/> 
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label">Twitter</label>
					<div class="controls">
						<input type="text" name="twitter" value="<?php echo $twitter; ?>" class="input-xlarge"/> 
					</div>
				</div>

				<div class="form-actions">
					<button type="submit" class="btn btn-info" name="submit"><i class="icon-edit"></i> Cập nhật</button>
					<span id="submit"></span>
				</div>
			</form>
			
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
		
		</div>	
	</div>
</div>
<script language="javascript">
//////////////////////////////////////////////////////////////////////
	
	$(document).ready(function(){
		$(".chzn-select").chosen({ max_selected_options: 2 });
		$(".chzn-select").bind("chosen:maxselected", function () { }); 
	});

	$(" input[type=radio], input[type=checkbox]").uniform();
	$('.tags').tagsInput({width:'auto'});
	$('.wysihtmleditor5').wysihtml5();
	$('.datetimepk').datepicker({
		format: 'yyyy-mm-dd'
	})
	$('#managerform').ajaxForm({
		target: '#submit', 
		beforeSubmit: function(){
			$('#submit').show().html('<span class="badge badge-warning"><i class="icon-spinner"></i> Xin hãy chờ....</span>'); 
		},
		success: function() { 
			;
		}
	});
	
	$('#listenbutton').live('click',function(){
		$('#listenmusic').html('');
		$('#listenmusic').html("<img src='<?php echo "$rootfile/images/"; ?>"+$(this).attr('qid')+"'>");
		$('#modalform').modal('show');
	});
	
$('#file_upload').uploadify({
        'swf'      : 'assets/uploadify/uploadify.swf',
        'uploader' : 'assets/uploadify/uploadify.php',
		'checkExisting' : 'assets/uploadify/check-exists.php',
		
		'fileTypeDesc' : 'Sound file',
        'fileTypeExts' : '*.png;*.jpg;*.gif', 
		'multi'    : false,
		'queueSizeLimit' : 1,
		'itemTemplate' : '<span></span>',
		'buttonText' : 'Upload...',
		'width':93,
		'height':28,
		'onUploadProgress' : function(file, bytesUploaded, bytesTotal, totalBytesUploaded, totalBytesTotal) {
			$('#queue').html("<span class='badge badge-info'>"+file.name +"</span> Đang upload... "+ Math.round((bytesUploaded/bytesTotal)*100)+'%');
        },
		'onUploadSuccess' : function(file, data, response) {
			$('input[name=uploadedfile]').val(data);
			$('#queue').html("<span class='badge badge-success'>"+file.name + "</span> Upload xong!")
			$('#file_upload').uploadify('disable', false);
		},
		'onSelect' : function(file) {
			$('input[name=uploadedfile]').val('');
			$('input[name=image]').val('');
			$('#queue').html("<span class='badge badge-warning'>"+file.name +"</span> Đang đọc...");
			$('#file_upload').uploadify('disable', true);
        },
		'onCancel' : function(file) {
			$('input[name=uploadedfile]').val('');
			$('input[name=image]').val('');
        },
		'onUploadError' : function(file, errorCode, errorMsg, errorString) {
			$('#queue').html("<span class='badge badge-important'>"+file.name +"</span> Không thể upload: "+ errorString);
        }
    }); 
</script>