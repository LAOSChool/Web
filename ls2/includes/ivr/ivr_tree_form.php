<?php
	include('../../config.php');
	ensure_permission('ivr');
	ensure_role('mod,sadmin,admin');

	$type = $_REQUEST['type'];
	$time = time();
	
	$com_id = $_SESSION[$config_session]['com_id'];
	$companyinfo = $db->getRow("select * from `companies` where id=$com_id");
?>


<?php if($type == ''): ?>

	<div class="bs-docs-example">
		<div class="navbar navbar-static">
			<div class="navbar-inner">
				<div style="width: auto;" class="container">
					<a href="javascript:;" class="brand"><i class="icon-microphone"></i> Create IVR Tree</a>
				</div>
			</div>
		</div>
	</div>
	
	<?php $cc = $db->getone("select count(*) from `ivr` where com_id=$com_id and parrent=0"); ?>
	<?php if($cc==0): ?>
		<form method=POST id='managerform' class="form-horizontal" action='includes/ivr/ivr_tree_add.php'>
			<div class="control-group">
				<label class="control-label">IVR Tree</label>
				<div class="controls">
					<div class="input-prepend">
						<input type="hidden" name='key_pressed' value=0>
						<input type="hidden" name='parrent' value=0>
						<span class="add-on"><i class="icon-microphone"></i></span>
						<input readonly type="text" name="name" class="input-xlarge" value='<?php echo $companyinfo['pbx']; ?>'>
					</div>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label">Sound file</label>
				<div class="controls">
					<input name="uploadedfile" type="hidden">						
					<input id="file_upload" name="file_upload" type="file" />
					<span class="help-inline" id='queue'>mp3 and wav only</span>
				</div>
			</div>
		
			<div class="form-actions">
				<button type="submit" class="btn btn-info" name="submit"><i class="icon-microphone"></i> Create IVR Tree</button>
			</div>
			<div id="submit"></div>
		</form>
	<?php else: ?>
		<div class='alert alert-info'><i class="icon-info-sign"></i> Select in the left to add or edit every single node.</div>
	<?php endif; ?>

<?php elseif($type=='add'): ?>
	<?php
		$parrent = $_REQUEST['parrent'];
		if($parrent == 0 ) $parrentname = $companyinfo['pbx'];
		else $parrentname = $db->getone("select name from ivr where id=$parrent and com_id=$com_id");
	?>
	
	<div class="bs-docs-example">
		<div class="navbar navbar-static">
			<div class="navbar-inner">
				<div style="width: auto;" class="container">
					<a href="javascript:;" class="brand"><i class='icon-plus-sign'></i> Add Child node to: <?php echo $parrentname; ?></a>
				</div>
			</div>
		</div>
	</div>
	
	<form method=POST id='managerform' class="form-horizontal" action='includes/ivr/ivr_tree_add.php'>
		<div class="control-group">
			<label class="control-label">Node Name</label>
			<div class="controls">
				<input type="hidden" name="parrent" value='<?php echo $parrent; ?>'>
				<input type="text" name="name" class="input-xlarge">
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label">Key Pressed</label>
			<div class="controls">
				<select name="key_pressed">
					<option value='0'>0</option>
					<option value='1'>1</option>
					<option value='2'>2</option>
					<option value='3'>3</option>
					<option value='4'>4</option>
					<option value='5'>5</option>
					<option value='6'>6</option>
					<option value='7'>7</option>
					<option value='8'>8</option>
					<option value='9'>9</option>
				</select>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label">Sound file</label>
			<div class="controls">
				<input name="uploadedfile" type="hidden">						
				<input id="file_upload" name="file_upload" type="file" />
				<span class="help-inline" id='queue'>mp3 and wav only</span>
			</div>
		</div>	

		<div class="form-actions">
			<button type="submit" class="btn btn-info" name="submit"><i class="icon-plus-sign"></i> Add Node</button>
		</div>
		<div id="submit"></div>
	</form>
	
<?php elseif($type=='edit'): ?>
	<?php
		$id=$_REQUEST['id'];
		$row = $db->getRow("SELECT * FROM `ivr` where id=$id and com_id=$com_id");
	?>
	
	<div class="bs-docs-example">
		<div class="navbar navbar-static">
			<div class="navbar-inner">
				<div style="width: auto;" class="container">
					<a href="javascript:;" class="brand"><i class='icon-edit'></i> Edit node: <?php echo $row['name']; ?></a>
				</div>
			</div>
		</div>
	</div>
	
	<form method=POST id='managerform' class="form-horizontal" action='includes/ivr/ivr_tree_edit.php'>
		<div class="control-group">
			<label class="control-label">Node Name</label>
			<div class="controls">
				<input type="hidden" name="id" value='<?php echo $id; ?>'>
				<input <?php echo ($row['parrent']==0)?"readonly":""; ?> type="text" name="name" class="input-xlarge" value='<?php echo $row['name']; ?>'>
			</div>
		</div>
		
		<?php if($row['parrent']!=0): ?>
		<div class="control-group">
			<label class="control-label">Key Pressed</label>
			<div class="controls">
				<select name="key_pressed">
					<option value='0' <?php echo ($row['key_pressed']==0)?"selected":"" ?>>0</option>
					<option value='1' <?php echo ($row['key_pressed']==1)?"selected":"" ?>>1</option>
					<option value='2' <?php echo ($row['key_pressed']==2)?"selected":"" ?>>2</option>
					<option value='3' <?php echo ($row['key_pressed']==3)?"selected":"" ?>>3</option>
					<option value='4' <?php echo ($row['key_pressed']==4)?"selected":"" ?>>4</option>
					<option value='5' <?php echo ($row['key_pressed']==5)?"selected":"" ?>>5</option>
					<option value='6' <?php echo ($row['key_pressed']==6)?"selected":"" ?>>6</option>
					<option value='7' <?php echo ($row['key_pressed']==7)?"selected":"" ?>>7</option>
					<option value='8' <?php echo ($row['key_pressed']==8)?"selected":"" ?>>8</option>
					<option value='9' <?php echo ($row['key_pressed']==9)?"selected":"" ?>>9</option>
				</select>
			</div>
		</div>
		<?php else: ?>
			<input type="hidden" name='key_pressed' value=0>
		<?php endif; ?>
		
		<div class="control-group">
			<label class="control-label">Sound file</label>
			<div class="controls">
				<input name="uploadedfile" type="hidden">						
				<input id="file_upload" name="file_upload" type="file" />
				<input name="content" type="hidden" class="small" value='<?php echo $row['content']; ?>'>
				<span class="help-inline" id='queue'>
					<a href="javascript:;" class="btn btn-danger btn-small" id='listenbutton' qid='<?php echo $id ?>' >
						<i class='icon-play-sign'></i> Listen music
					</a> 
					mp3 and wav only
				</span>
			</div>
		</div>	
	
		<div class="form-actions">
			<button type="button" class="btn btn-danger"><i class="icon-remove"></i></button>
			<button type="submit" class="btn btn-info" name="submit"><i class="icon-edit"></i> Edit node</button>
		</div>
		<div id="submit"></div>
	</form>
<?php
	endif;
?>

<script language="javascript">
//////////////////////////////////////////////////////////////////////
	$("input[type=radio], input[type=checkbox]").uniform();
	   
	$(".chzn-select").chosen();$(".chzn-select-deselect").chosen({allow_single_deselect:true});
	   
	$('.datetimepk').datepicker({
		format: 'yyyy-mm-dd'
	})
///////////////////////////////////////////////////////////////////////
	$('#file_upload').uploadify({
        'swf'      : 'assets/uploadify/uploadify.swf',
        'uploader' : 'assets/uploadify/uploadify.php',
		'checkExisting' : 'assets/uploadify/check-exists.php',
		
		'fileTypeDesc' : 'Sound file',
        'fileTypeExts' : '*.wav;*.mp3', 
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
			$('input[name=content]').val('');
			$('#queue').html("<span class='badge badge-warning'>"+file.name +"</span> Reading...");
			$('#file_upload').uploadify('disable', true);
        },
		'onCancel' : function(file) {
			$('input[name=uploadedfile]').val('');
			$('input[name=content]').val('');
        },
		'onອັບ​ໂຫຼດError' : function(file, errorCode, errorMsg, errorString) {
			$('#queue').html("<span class='badge badge-important'>"+file.name +"</span> could not be uploaded: "+ errorString);
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
			$('#submit').show().html('<div class="alert alert-warning"><i class="icon-spinner"></i> <?php lang('pleasewait') ?>....</div>'); 
		},
		success: function() { 
			;
		}
	});
</script>