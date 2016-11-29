<?php
	include('../../config.php');
	ensure_permission('msg');
	ensure_role('mod,sadmin,admin');

	$type = $_REQUEST['type'];
	$time = time();

?>
<div class="bs-docs-example">
	<div class="navbar navbar-static">
		<div class="navbar-inner">
			<div style="width: auto;" class="container">
				<a href="javascript:;" class="brand"><?php lang('msgmanager') ?></a>
				<ul class="nav">
					<li <?php echo ($type=='search' || $type == '')?"class='active'":"" ?>><a href="javascript:;" class='formchange' form="search"><i class="icon-search"></i> <?php lang('search') ?></a></li>
					<li <?php echo ($type=='add')?"class='active'":"" ?>><a href="javascript:;" class='formchange' form="add"><i class="icon-plus"></i> <?php lang('sendmsg') ?></a></li>
					<li><a href="javascript:;" id='loadform'></a></li>
				</ul>
			</div>
		</div>
	</div>
</div>

<?php if($type=='search' || $type == ''): ?>

	<form method=POST id='searchform' class="form-horizontal" action='includes/msg/msg_manager_db.php'>
		<div class="control-group">
			<label class="control-label">Sent Date</label>
			<div class="controls">
				<input class="datetimepk" name="filter_from_dt" type="text" value="" placeholder="From">
				<input class="datetimepk" name="filter_to_dt" type="text" value="" placeholder="To">
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label"></label>
			<div class="controls">
				<button type="submit" class="btn btn-info" name="submit"><i class="icon-search"></i> ຄົ້ນຫາ</button>
				<span id='searchtext'></span>
			</div>
		</div>
	</form>

<?php elseif($type=='add'): ?>
	<form method=POST id='managerform' class="form-horizontal" action='includes/msg/msg_manager_send.php'>
	
		<div class="control-group">
			<label class="control-label">Send Type</label>
			<div class="controls">
				<select name="send_type">
					<option value=0>Send to All school</option>
					<option value=1>Send to Clases</option>
					<option value=3>Send to students</option>
				</select>
			</div>
		</div>
		
		<div class="control-group classes">
			<label class="control-label">Classes</label>
			<div class="controls">	
				<select name="filter_class_list[]" data-placeholder="Select Classes" class="chzn-select span5" multiple="multiple">
						<?php
						$headers = array();
						$auth_key = $_SESSION[$config_session]['auth_key'];
						$headers[] = "auth_key: $auth_key";
						$headers[] = "api_key: TEST_API_KEY";
						$gets['from_row'] = 0;
						$gets['max_result'] = 9999;

						$clsaip = callapi($headers,'',$gets,'api/classes');
						$clsdatas = explode("\n",$clsaip['output']);
						$clsdata = json_decode($clsdatas[14]);
					
						foreach($clsdata->list as $lists){
							echo "<option value='$lists->id'>$lists->title</option>";
						}
					?>
				</select>
			</div>
		</div>
		
		
		<div class="control-group students">
			<label class="control-label">Students</label>
			<div class="controls">					
				<select name="student[]" data-placeholder="Select Students" class="chzn-select span5" multiple="multiple">
					<?php
						$headers = array();
						$auth_key = $_SESSION[$config_session]['auth_key'];
						$headers[] = "auth_key: $auth_key";
						$headers[] = "api_key: TEST_API_KEY";
						$gets['from_row'] = 0;
						$gets['max_result'] = 99999;
						$gets['filter_user_role'] = "STUDENT";

						$stdaip = callapi($headers,'',$gets,'api/users');
						$stddatas = explode("\n",$stdaip['output']);
						$stddata = json_decode($stddatas[14]);
						foreach($stddata->list as $lists){
							echo "<option value='$lists->id'>$lists->fullname</option>";
						}
					?>
				</select>

			</div>
		</div>
		
		<div class="control-group classes">
			<label class="control-label">Reciver Type</label>
			<div class="controls">
				<label class="checkbox">
					<input name="filter_roles[]" type="checkbox" value="STUDENT" /> STUDENT
				</label>
				<label class="checkbox">
					<input name="filter_roles[]" type="checkbox" value="TEACHER_H" /> TEACHER_H
				</label>
				
				<label class="checkbox">
					<input name="filter_roles[]" type="checkbox" value="TEACHER_S" /> TEACHER_S
				</label>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label">Messange Content</label>
			<div class="controls">
				<textarea name="message" class="input-xxlarge" rows=5></textarea>
			</div>
		</div>
		
		<div class="form-actions">
			<button type="submit" class="btn btn-info" name="submit"><i class="icon-location-arrow"></i> Send ຂໍ້​ຄວາມ</button>
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
		loadform('includes/msg/msg_manager_form.php?type='+$(this).attr('form'),'#parameter','#loadform');
	})
///////////////////////////////////////////////////////////////////////
	
	$('.classes').hide();
	$('.students').hide();
	$('select[name=send_type]').change(function(){
		if($(this).val()==3){
			$('.students').show();
		}else{
			$('.students').hide();
		}
		if($(this).val()==1){
			$('.classes').show();
		}else{
			$('.classes').hide();
		}
	});
	
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
			$('#submit').show().html('<span class="badge badge-warning"><i class="icon-spinner"></i> <?php lang('pleasewait') ?>....</span>'); 
		},
		success: function() { 
			;
		}
	});
</script>