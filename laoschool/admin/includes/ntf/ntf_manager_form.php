<?php
	include('../../config.php');
	ensure_permission('ntf');
	ensure_role('mod,sadmin,admin');

	$type = $_REQUEST['type'];
	$time = time();

?>
<div class="bs-docs-example">
	<div class="navbar navbar-static">
		<div class="navbar-inner">
			<div style="width: auto;" class="container">
				<a href="javascript:;" class="brand"><?php lang('ntfmanager') ?></a>
				<ul class="nav">
					<li <?php echo ($type=='search' || $type == '')?"class='active'":"" ?>><a href="javascript:;" class='formchange' form="search"><i class="icon-search"></i> <?php lang('search') ?></a></li>
					<li <?php echo ($type=='add')?"class='active'":"" ?>><a href="javascript:;" class='formchange' form="add"><i class="icon-plus"></i> <?php lang('sendntf') ?></a></li>
					<li><a href="javascript:;" id='loadform'></a></li>
				</ul>
			</div>
		</div>
	</div>
</div>

<?php if($type=='search' || $type == ''): ?>

	<form method=POST id='searchform' class="form-horizontal" action='includes/ntf/ntf_manager_db.php?lang=<?php echo $lang ?>'>
		<div class="control-group">
			<label class="control-label"><?php lang('sentdate') ?></label>
			<div class="controls">
				<input class="datetimepk" name="filter_from_dt" type="text" value="" placeholder="<?php lang('from') ?>">
				<input class="datetimepk" name="filter_to_dt" type="text" value="" placeholder="<?php lang('to') ?>">
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label"></label>
			<div class="controls">
				<button type="submit" class="btn btn-info" name="submit"><i class="icon-search"></i> <?php lang('search') ?></button>
				<span id='searchtext'></span>
			</div>
		</div>
	</form>

<?php elseif($type=='add'): ?>
	<form method=POST id='managerform' class="form-horizontal" action='includes/ntf/ntf_manager_send.php?lang=<?php echo $lang ?>'>
	
		<div class="control-group">
			<label class="control-label"><?php lang('sendtype') ?></label>
			<div class="controls">
				<select name="send_type">
					<option value=2><?php lang('send2allscl') ?></option>
					<option value=1><?php lang('send2class') ?></option>
				</select>
			</div>
		</div>
		
		<div class="control-group classes">
			<label class="control-label"><?php lang('classes') ?></label>
			<div class="controls">
				<select name="filter_class_list[]" data-placeholder="Select Classes" class="chzn-select span5">
					<?php
						$headers = array();
						$auth_key = $_SESSION[$config_session]['auth_key'];
						$headers[] = "auth_key: $auth_key";
						$headers[] = "api_key: TEST_API_KEY";
						$gets['from_row'] = 0;
						$gets['max_result'] = 9999;

						$clsaip = callapi($headers,'',$gets,'api/classes');
						$clsdatas = explode("\n",$clsaip['output']);
						$clsdata = json_decode(end($clsdatas));

						foreach($clsdata->list as $lists){
							echo "<option value='$lists->id'>$lists->title</option>";
						}
					?>
				</select>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label"><?php lang('rcvtype') ?></label>
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
			<label class="control-label"><?php lang('title') ?></label>
			<div class="controls">
				<input type="text" name="title" class="input-xxlarge">
			</div>
		</div>	
		
		<div class="control-group">
			<label class="control-label"><?php lang('content') ?></label>
			<div class="controls">
				<textarea name="message" class="input-xxlarge" rows=5></textarea>
			</div>
		</div>	
		
		<?php for($i=1;$i<=5;$i++): ?>
		
			<div class="control-group">
				<label class="control-label"><?php lang('photo') ?></label>
				<div class="controls">
					<input type="text" name="caption[]" readonly class="input-large" placeholder="Caption <?php echo $i ?>">
					<input name="uploadedfile[]" type="hidden">
					<span class="btn btn-file">
						<span class="fileupload-new"><i class="icon-upload-alt"></i> <?php lang('selectfile') ?></span>
						<input type="file" class="default" id="fileupload<?php echo $i ?>" type="file" name="files[]" data-url="assets/jquery-file-upload/server/php/">
					</span>
					<span class="help-inline" id="progress">
						jpg; png; jpeg
					</span>
				</div>
			</div>
		
		<?php endfor; ?>
		
		<div class="form-actions">
			<button type="submit" class="btn btn-info" name="submit"><i class="icon-location-arrow"></i> <?php lang('sendntf') ?></button>
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
		loadform('includes/ntf/ntf_manager_form.php?lang=<?php echo $lang ?>&type='+$(this).attr('form'),'#parameter','#loadform');
	})
///////////////////////////////////////////////////////////////////////
	
	$('.classes').hide();
	$('.students').hide();
	$('select[name=send_type]').change(function(){
		if($(this).val()==1){
			$('.classes').show();
		}else{
			$('.classes').hide();
		}
	});
	
///////////////////////////////////////////////////////////////////////

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
/////////

$('#fileupload1,#fileupload2,#fileupload3,#fileupload4,#fileupload5').fileupload({
	dataType: 'json',
	done: function (e, data) {
		if(data.result.error==null){
			$(this).parent().parent().children('#progress').html("<span class='badge badge-success'>"+ data.result.name + "</span> <?php lang('uploadok') ?>!");
			$(this).parent().parent().children('input[name="uploadedfile[]"]').val(data.result.name);
			$(this).parent().parent().children('input[name="caption[]"]').removeAttr('readonly');
		}else{
			$(this).parent().parent().children('#progress').html("<span class='badge badge-important'><?php lang('error') ?>:</span> "+data.result.error);
			$(this).parent().parent().children('input[name="uploadedfile[]"]').val('');
		}
		$(this).removeAttr("disabled");
	},
	progressall: function (e, data) {
		var progress = parseInt(data.loaded / data.total * 100, 10)-1;
		$(this).parent().parent().children('#progress').html("<span class='badge badge-info'><?php lang('uploading') ?>...</span> " + progress+"%");

	},
	submit: function(e, data){
		$(this).parent().parent().children('input[name="uploadedfile[]"]').val('');
		$(this).attr("disabled","disabled");
	},
	formData: {filetype: 'jpg;png;jpeg'}
});
</script>