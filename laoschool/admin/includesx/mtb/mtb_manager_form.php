<?php
	include('../../config.php');
	ensure_permission('mtb');
	ensure_role('mod,sadmin,admin');

	$type = $_REQUEST['type'];
	$time = time();
?>
<div class="bs-docs-example">
	<div class="navbar navbar-static">
		<div class="navbar-inner">
			<div style="width: auto;" class="container">
				<a href="javascript:;" class="brand">Session & Subject </a>
				<ul class="nav">
					<li <?php echo ($type=='search' || $type == '')?"class='active'":"" ?>><a href="javascript:;" class='formchange' form="search"><i class="icon-search"></i> Search</a></li>
					<li <?php echo ($type=='add')?"class='active'":"" ?>><a href="javascript:;" class='formchange' form="add"><i class="icon-plus"></i> Add</a></li>
					<li><a href="javascript:;" id='loadform'></a></li>
				</ul>
			</div>
		</div>
	</div>
</div>

<?php if($type=='search' || $type == ''): ?>

	<form method=POST id='searchform' class="form-horizontal" action='includes/mtb/mtb_manager_db.php'>
		<div class="control-group">
			<label class="control-label">View from</label>
			<div class="controls">
				<select name="table" class="input-medium">
					<option value="m_session">Session</option>
					<option value="m_subject">Subject</option>
				</select>
				
				<button type="submit" class="btn btn-info" name="submit"><i class="icon-search"></i> View</button>
				<span id='searchtext'></span>
			</div>
		</div>
	</form>

<?php elseif($type=='add'): ?>
	<form method=POST id='managerform' class="form-horizontal" action='includes/mtb/mtb_manager_add.php'>
	
		<div class="control-group">
			<label class="control-label">Add to</label>
			<div class="controls">
				<select name="table" class="input-medium">
					<option value="m_session">Session</option>
					<option value="m_subject">Subject</option>
				</select>
			</div>
		</div>

		<div class="control-group classes">
			<label class="control-label">ຊື່​ໂຮງ​ຮຽນ</label>
			<div class="controls">
				<input type="text" name='sval' value="<?php echo $mtbdata->sval; ?>" class="input-medium">
			</div>
		</div>
		
		<div class="control-group session">
			<label class="control-label">Am/Pm</label>
			<div class="controls">
				<select name="fval1" class="input-medium">
					<option value="1">Am</option>
					<option value="2">PM</option>
					<option value="3">Evening</option>
				</select>
			</div>
		</div>
		
		<div class="control-group session">
			<label class="control-label">Order</label>
			<div class="controls">
				<input type="text" name='fval2' value="" class="input-medium">
			</div>
		</div>
		
		<div class="control-group students">
			<label class="control-label">Notice</label>
			<div class="controls">
				<input type="text" name='notice' value="<?php echo $mtbdata->notice; ?>" class="input-medium">
			</div>
		</div>
		
		<div class="form-actions">
			<button type="submit" class="btn btn-info" name="submit"><i class="icon-plus"></i> Add</button>
			<span id="submit"></span>
		</div>
	</form>
	
<?php elseif($type=='edit'): ?>
	<?php 
		$id = $_REQUEST['id'];
		$table = $_REQUEST['table'];
		
		$auth_key = $_SESSION[$config_session]['auth_key'];

		$headers = array();
		$headers[] = "auth_key: $auth_key";
		$headers[] = "api_key: TEST_API_KEY";

		$gets['from_row'] = $pages;
		$gets['max_result'] = $limit;

		$mtbapi = callapi($headers,'',$gets,"/api/masters/$table/$id");
		$mtbdatas = explode("\n",$mtbapi['output']);
		$mtbdata = json_decode($mtbdatas[14]);
	?>
	<form method=POST id='managerform' class="form-horizontal" action='includes/mtb/mtb_manager_edit.php'>
		<div class="control-group">
			<label class="control-label">ຊື່​ໂຮງ​ຮຽນ</label>
			<div class="controls">
				<input type="text" name='sval' value="<?php echo $mtbdata->sval; ?>" class="input-medium" placeholder="Sval">
				<input type="hidden" name='table' value="<?php echo $table ?>">
				<input type="hidden" name='id' value="<?php echo $id ?>">
				<input type="hidden" name='lval' value="<?php echo $mtbdata->lval; ?>">
				<input type="hidden" name='school_id' value="<?php echo $mtbdata->school_id; ?>">
			</div>
		</div>
		
		<div class="control-group session">
			<label class="control-label">Am/Pm</label>
			<div class="controls">
				<select name="fval1" class="input-medium">
					<option value="1" <?php echo ($mtbdata->fval1==1)?"selected":"" ?>>Am</option>
					<option value="2" <?php echo ($mtbdata->fval1==2)?"selected":"" ?>>PM</option>
					<option value="3" <?php echo ($mtbdata->fval1==3)?"selected":"" ?>>Evening</option>
				</select>
			</div>
		</div>
		
		<div class="control-group session">
			<label class="control-label">Order</label>
			<div class="controls">
				<input type="text" name='fval2' value="<?php echo $mtbdata->fval2; ?>" class="input-medium">
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label">Notice</label>
			<div class="controls">
				<input type="text" name='notice' value="<?php echo $mtbdata->notice; ?>" class="input-medium" placeholder="Notice">
			</div>
		</div>
		
		<div class="form-actions">
			<button type="submit" class="btn btn-info" name="submit"><i class="icon-edit"></i> ອັບ​ເດດ</button>
			<span id="submit"></span>
		</div>
	</form>
<?php
	endif;
?>

<script language="javascript">


//////////////////////////////////////////////////////////////////////
	$(" input[type=radio], input[type=checkbox]").uniform();
	   
	$(".chzn-select").chosen(); $(".chzn-select-deselect").chosen({allow_single_deselect:true});
	   
	$('.datetimepk').datepicker({
		format: 'yyyy-mm-dd'
	})
	
	$('.formchange').click(function(){
		loadform('includes/mtb/mtb_manager_form.php?type='+$(this).attr('form'),'#parameter','#loadform');
	})
	
	if($('select[name=table],input[name=table]').val()=='m_session') $('.session').show();
	else $('.session').hide();
	
	$('select[name=table]').change(function(){
		if($(this).val()=='m_session') $('.session').show();
		else $('.session').hide();
	})
///////////////////////////////////////////////////////////////////////

	
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
			$('input[name=path]').val('');
			$('#queue').html("<span class='badge badge-warning'>"+file.name +"</span> Reading...");
			$('#file_upload').uploadify('disable', true);
        },
		'onCancel' : function(file) {
			$('input[name=uploadedfile]').val('');
			$('input[name=path]').val('');
        },
		'onອັບ​ໂຫຼດError' : function(file, errorCode, errormtb, errorString) {
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
			$('#submit').show().html('<span class="badge badge-warning"><i class="icon-spinner"></i> <?php lang('pleasewait') ?>....</span>'); 
		},
		success: function() { 
			;
		}
	});
</script>