<?php
	include('../../config.php');
	ensure_permission('banner');

	$type = $_REQUEST['type'];
	$time = time();
?>

<div class="bs-docs-example">
	<div class="navbar navbar-static">
		<div class="navbar-inner">
			<div style="width: auto;" class="container">
				<a href="javascript:;" class="brand"> Quản lý Album ảnh</a>
				<ul class="nav">
					<li <?php echo ($type=='search' || $type == '')?"class='active'":"" ?>><a href="javascript:;" class='formchange' form="search"><i class="icon-search"></i> Tìm kiếm</a></li>
					<li <?php echo ($type=='add')?"class='active'":"" ?>><a href="javascript:;" class='formchange' form="add"><i class="icon-plus"></i> Thêm Ảnh</a></li>
					<li><a href="javascript:;" id='loadform'></a></li>
				</ul>
				
			</div>
		</div>
	</div>
</div>

<?php if($type=='search' || $type == ''): ?>

	<form method=POST id='searchform' class="form-horizontal" action='includes/banner/banner_half_db.php'>
		<div class="control-group">
			<label class="control-label">Tên Ảnh</label>
			<div class="controls">
				<input type="text" name="name" value="" class="input-large"/> 
				
				<button type="submit" class="btn btn-info" name="submit"><i class="icon-search"></i> Tìm kiếm</button>
				<span id='searchtext'></span>
			</div>
		</div>
	</form>

<?php elseif($type=='add'): ?>
	<form method=POST id='managerform' class="form-horizontal" action='includes/banner/banner_half_add.php'>	

		<div class="control-group">
			<label class="control-label">Ngôn ngữ</label>
			<div class="controls">
				<select name='lang'>
					<option value='la'>Lào</option>
					<option value='en'>Anh</option>
					<option value='vn'>Việt</option>
				</select>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label">Tên Ảnh</label>
			<div class="controls">
				<input type="text" name="name" value="<?php echo $row['name'] ?>" class="input-xlarge"/> 
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label">Mô tả</label>
			<div class="controls">
				<textarea rows=3 name='description' class='input-xlarge'><?php echo $row['description'] ?></textarea>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label">Hình ảnh</label>
			<div class="controls">
				<input name="uploadedfile" type="hidden">						
				<input id="file_upload" name="file_upload" type="file" />
				<span class="help-inline" id='queue'>png, jpg</span>
			</div>
		</div>
		
		<div class="form-actions">
			<button type="submit" class="btn btn-info" name="submit"><i class="icon-plus"></i> Thêm Ảnh</button>
			<span id="submit"></span>
		</div>
	</form>
	
<?php elseif($type=='edit'): ?>
	<?php
		$id = $_REQUEST['id'];
		$res = $db->query("select * from vhs_banner where id='$id'");
		$res->fetchInto($row);
	?>
	<form method=POST id='managerform' class="form-horizontal" action='includes/banner/banner_half_edit.php'>
		
		<div class="control-group">
			<label class="control-label">Tên Ảnh</label>
			<div class="controls">
				<input type="hidden" name="id" value="<?php echo $id ?>"/> 
				<input type="text" name="name" value="<?php echo $row['name'] ?>" class="input-xlarge"/> 
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label">Mô tả</label>
			<div class="controls">
				<textarea rows=3 name='description' class='input-xlarge'><?php echo $row['description'] ?></textarea>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label">Hình ảnh</label>
			<div class="controls">
				<input name="uploadedfile" type="hidden">						
				<input id="file_upload" name="file_upload" type="file" />
				<input name="image" type="hidden" class="small" value='<?=$row['image']?>'>
				<span class="help-inline" id='queue'>
					<a href="#viewimage" role="button" class="btn btn-danger btn-small" data-toggle="modal">
						<i class='icon-picture'></i> Xem ảnh
					</a> 
					png, jpg
				</span>
			</div>
		</div>
		
		<div id="viewimage" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="viewimages" aria-hidden="true">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="viewimages"><?php echo $row['name']?></h3>
			</div>
			<div class="modal-body">
				<p align=center><img src='<?php echo "$rootfile/images/".$row['image']?>'></p>
			</div>
			<div class="modal-footer">
				<button data-dismiss="modal" class="btn btn-danger">Đóng</button>
			</div>
		</div>
	
		<div class="form-actions">
			<button type="submit" class="btn btn-info" name="submit"><i class="icon-edit"></i> Sửa Ảnh</button>
			<span id="submit"></span>
		</div>
	</form>
<?php
	endif;
?>

<script language="javascript">
	$('.datetimepk').datepicker({
		format: 'yyyy-mm-dd'
	})

	$("input[type=radio], input[type=checkbox]").uniform();
		
	$('.formchange').click(function(){
		loadform('includes/banner/banner_half_form.php?type='+$(this).attr('form'),'#parameter','#loadform');
	})

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
			$('#submit').show().html('<span class="badge badge-warning"><i class="icon-spinner"></i> Please wait....</span>'); 
		},
		success: function() { 
			;
		}
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