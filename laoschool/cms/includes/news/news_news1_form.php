<?php
	include('../../config.php');
	ensure_permission('news');
	ensure_role('mod,sadmin,admin');

	$type = $_REQUEST['type'];
	$time = time();

?>
<div class="bs-docs-example">
	<div class="navbar navbar-static">
		<div class="navbar-inner">
			<div style="width: auto;" class="container">
				<a href="javascript:;" class="brand">Quản lý cảm nhận</a>
				<ul class="nav">
					<li><a href="javascript:;" id='loadform'></a></li>
				</ul>
			</div>
		</div>
	</div>
</div>

<?php
	if($type=='edit'): ?>
	<?php
		$id=$_REQUEST['id'];
		$row = $db->getRow("SELECT * FROM vhs_news where id=$id");
	?>
	<form method=POST id='managerform' class="form-horizontal" action='includes/news/news_news1_edit.php'>
	
		<div class="control-group">
			<label class="control-label">Tên học viên</label>
			<div class="controls">
				<input type="hidden" name="id" value="<?php echo $id; ?>">
				<input type="text" name="title" class="input-xxlarge" value="<?php echo $row['title']; ?>">
			</div>
		</div>	
		
		<div class="control-group">
			<label class="control-label">Chức vụ</label>
			<div class="controls">
				<input type="text" name="camnhan_cv" class="input-xlarge" value="<?php echo $row['camnhan_cv']; ?>">
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label">Công việc</label>
			<div class="controls">
				<input type="text" name="camnhan_ct" class="input-xlarge" value="<?php echo $row['camnhan_ct']; ?>">
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label">Khóa học</label>
			<div class="controls">
				<input type="text" name="camnhan_sv" class="input-xlarge" value="<?php echo $row['camnhan_sv']; ?>">
			</div>
		</div>
		<div class="control-group">
			<label class="control-label">Ý kiến</label>
			<div class="controls">
				<textarea name="description" class="input-xxlarge" rows=4><?php echo $row['description']; ?></textarea>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label">Chi tiết tin</label>
			<div class="controls">
				<textarea name="detail"class="span12 wysihtmleditor5" rows="6"><?php echo $row['detail']; ?></textarea>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label">Ảnh đại diện</label>
			<div class="controls">
				<input name="uploadedfile" type="hidden">						
				<input id="file_upload" name="file_upload" type="file" />
				<input name="image" type="hidden" class="small" value='<?php echo $row['image']?>'>
				<span class="help-inline" id='queue'>
					<a href="javascript:;" class="btn btn-danger btn-small" id='listenbutton' qid='<?php echo $row['image']?>' >
						<i class='icon-picture'></i> Xem ảnh
					</a> 
					Chỉ JPG và PNG
				</span>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label">Thứ tự</label>
			<div class="controls">
				<input type="text" name="order" value="<?php echo $row['order']; ?>" class="input-small"/> 
			</div>
		</div>
		
		<div class="form-actions">
			<button type="submit" class="btn btn-info" name="submit"><i class="icon-edit"></i> Cập nhật</button>
			<span id="submit"></span>
		</div>
	</form>
	<?php else: ?>
		Lựa chọn ở dưới để sửa
<?php
	endif;
?>

<script language="javascript">
//////////////////////////////////////////////////////////////////////
	$(" input[type=radio], input[type=checkbox]").uniform();
	$('.tags').tagsInput({width:'auto'});
	$('.wysihtmleditor5').wysihtml5();
	$('.datetimepk').datepicker({
		format: 'yyyy-mm-dd'
	})
	
	$('.formchange').click(function(){
		loadform('includes/news/news_news1_form.php?type='+$(this).attr('form'),'#parameter','#loadform');
	})
///////////////////////////////////////////////////////////////////////
	$('#file_upload').uploadify({
        'swf'      : 'assets/uploadify/uploadify.swf',
        'uploader' : 'assets/uploadify/uploadify.php',
		'checkExisting' : 'assets/uploadify/check-exists.php',
		
		'fileTypeDesc' : 'Sound file',
        'fileTypeExts' : '*.jpg;*.png', 
		'multi'    : false,
		'queueSizeLimit' : 1,
		'itemTemplate' : '<span></span>',
		'buttonText' : 'Upload...',
		'width':93,
		'height':28,
		'onUploadProgress' : function(file, bytesUploaded, bytesTotal, totalBytesUploaded, totalBytesTotal) {
			$('#queue').html("<span class='badge badge-info'>"+file.name +"</span> Đang tải... "+ Math.round((bytesUploaded/bytesTotal)*100)+'%');
        },
		'onUploadSuccess' : function(file, data, response) {
			$('input[name=uploadedfile]').val(data);
			$('#queue').html("<span class='badge badge-success'>"+file.name + "</span> Đã upload xong!")
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
			$('#queue').html("<span class='badge badge-important'>"+file.name +"</span> Lỗi khi upload: "+ errorString);
        }
    }); 
	
	$('#file_upload-button').prepend('<i class="icon-upload-alt"></i> ');
	
	$('#searchform').ajaxForm({ 
		target: '#entry', 
		beforeSubmit: function(){
			$('#searchtext').show().html('<img src="img/loading.gif">'); 
		},
		success: function() { 
			$('#searchtext').hide().html(''); 
		}
	}); 

	$('#managerform').ajaxForm({
		target: '#submit', 
		beforeSubmit: function(){
			$('#submit').show().html('<span class="badge badge-warning"><i class="icon-spinner"></i> Xin hãy chờ....</span>'); 
		},
		success: function() { 
			;
		}
	});
</script>