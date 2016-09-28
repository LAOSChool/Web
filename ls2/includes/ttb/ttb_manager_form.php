<?php
	include('../../config.php');
	ensure_permission('ttb');
	ensure_role('mod,sadmin,admin');

	$type = $_REQUEST['type'];
	$time = time();
?>
<div class="bs-docs-example">
	<div class="navbar navbar-static">
		<div class="navbar-inner">
			<div style="width: auto;" class="container">
				<a href="javascript:;" class="brand"><?php lang('ttbmanager') ?></a>
				<ul class="nav">
					<li><a href="javascript:;" id='loadform'></a></li>
				</ul>
			</div>
		</div>
	</div>
</div>

<?php if($type=='search' || $type == ''): ?>

	<form method=POST id='searchform' class="form-horizontal" action='includes/ttb/ttb_manager_db.php?lang=<?php echo $lang ?>'>
		<div class="control-group">
			<label class="control-label"><?php lang('lang_selectcls') ?></label>
			<div class="controls">
				
				<select name="filter_class_id" class="input-large">
					<?php
						$headers = array();
						$auth_key = $_SESSION[$config_session]['auth_key'];
						$headers[] = "auth_key: $auth_key";
						$headers[] = "api_key: TEST_API_KEY";

						$clsaip = callapi($headers,'',$gets,'api/classes');
						$clsdatas = explode("\n",$clsaip['output']);
						$clsdata = json_decode(end($clsdatas));
			
						foreach($clsdata->list as $lists){
							$selected = ($lists->id==$usrdata->classes[0]->id)?"selected":"";
							echo "<option $selected value='$lists->id'>$lists->title</option>";
						}
					?>
				</select>
				
				<button type="submit" class="btn btn-info" name="submit"><i class="icon-search"></i> <?php lang('view') ?></button>
				<span id='searchtext'></span>
			</div>
		</div>
	</form>

<?php elseif($type=='add'): ?>
	<?php
		$tables = $_REQUEST['table'];
		$table = explode('|',$tables);
		
	?>
	<form method=POST id='managerform' class="form-horizontal" action='includes/ttb/ttb_manager_add.php'>
		<div class="control-group">
			<label class="control-label"><?php lang('subject') ?> & <?php lang('teacher') ?></label>
			<div class="controls">
				<input name="class_id" value="<?php echo $table[0]; ?>" type="hidden">
				<input name="session_id" value="<?php echo $table[2]; ?>" type="hidden">
				<input name="weekday_id" value="<?php echo $table[1]; ?>" type="hidden">
				<input name="term_val" value="<?php echo $table[3]; ?>" type="hidden">
	
				<select name="subject_id" data-placeholder="Select Subject" class="span3">
					<option value="">- <?php lang('sltsubject') ?> -</option>
					<?php
						$headers = array();
						$auth_key = $_SESSION[$config_session]['auth_key'];
						$headers[] = "auth_key: $auth_key";
						$headers[] = "api_key: TEST_API_KEY";
						$gets['from_row'] = 0;
						$gets['max_result'] = 9999;

						$sjtaip = callapi($headers,'',$gets,'api/masters/m_subject');
						$sjtdatas = explode("\n",$sjtaip['output']);
						$sjtdata = json_decode(end($sjtdatas));
						
						foreach($sjtdata->messageObject->list as $lists){
							echo "<option value='$lists->id'>$lists->sval</option>";
						}
					?>
				</select>
				<select name="teacher_id" data-placeholder="Select Students" class="input-large">
					<option value="">- <?php lang('sltteacher') ?> -</option>
					<?php
							$headers = array();
							$auth_key = $_SESSION[$config_session]['auth_key'];
							$headers[] = "auth_key: $auth_key";
							$headers[] = "api_key: TEST_API_KEY";
							$gets['filter_user_role'] = "TEACHER";
							$stdaip = callapi($headers,'',$gets,'api/users');
							$stddatas = explode("\n",$stdaip['output']);
							$stddata = json_decode(end($stddatas));
						
							foreach($stddata->list as $lists){
								echo "<option value='$lists->id'>$lists->fullname</option>";
							}
						?>
				</select>
			</div>
		</div>

		<div class="form-actions">
			<button type="submit" class="btn btn-info" name="submit"><i class="icon-plus"></i> <?php lang('add') ?></button>
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

		$ttbapi = callapi($headers,'',$gets,"api/timetables/$id");
		$ttbdatas = explode("\n",$ttbapi['output']);
		$ttbdata = json_decode(end($ttbdatas));
		//print_r($ttbdata);
	?>
	<form method=POST id='managerform' class="form-horizontal" action='includes/ttb/ttb_manager_edit.php'>
		<div class="control-group">
			<label class="control-label"><?php lang('subject') ?> & <?php lang('teacher') ?></label>
			<div class="controls">
				<input name="id" value="<?php echo $ttbdata->id; ?>" type="hidden">
				<input name="class_id" value="<?php echo $ttbdata->class_id; ?>" type="hidden">
				
				<input name="session_id" value="<?php echo $ttbdata->session_id; ?>" type="hidden">
				<input name="weekday_id" value="<?php echo $ttbdata->weekday_id; ?>" type="hidden">
				<input name="description" value="<?php echo $ttbdata->description; ?>" type="hidden">
				<input name="term_val" value="<?php echo $ttbdata->term_val; ?>" type="hidden">
				<input name="year_id" value="<?php echo $ttbdata->year_id; ?>" type="hidden">
				
				<select name="subject_id" data-placeholder="Select Subject" class="span3">
					<option value="">- <?php lang('sltsubject') ?> -</option>
					<?php
						$headers = array();
						$auth_key = $_SESSION[$config_session]['auth_key'];
						$headers[] = "auth_key: $auth_key";
						$headers[] = "api_key: TEST_API_KEY";
						$gets['from_row'] = 0;
						$gets['max_result'] = 9999;

						$sjtaip = callapi($headers,'',$gets,'api/masters/m_subject');
						$sjtdatas = explode("\n",$sjtaip['output']);
						$sjtdata = json_decode(end($sjtdatas));
						
						foreach($sjtdata->messageObject->list as $lists){
							$selected = ($lists->id==$ttbdata->subject_id)?"selected":"";
							
							echo "<option $selected value='$lists->id'>$lists->sval</option>";
						}
					?>
				</select>
				
				<select name="teacher_id" data-placeholder="Select Students" class="input-large">
					<option value="">- <?php lang('sltteacher') ?> -</option>
					<?php
							$headers = array();
							$auth_key = $_SESSION[$config_session]['auth_key'];
							$headers[] = "auth_key: $auth_key";
							$headers[] = "api_key: TEST_API_KEY";
							$gets['filter_user_role'] = "TEACHER";
							$stdaip = callapi($headers,'',$gets,'api/users');
							$stddatas = explode("\n",$stdaip['output']);
							$stddata = json_decode(end($stddatas));
						
							foreach($stddata->list as $lists){
								$selected = ($lists->id==$ttbdata->teacher_id)?"selected":"";
								
								echo "<option $selected value='$lists->id'>$lists->fullname</option>";
							}
						?>
				</select>
			</div>
		</div>
		
		<div class="form-actions">
			<button type="submit" class="btn btn-info" name="submit"><i class="icon-edit"></i> <?php lang('edit') ?></button>
			<button type="button" class="btn btn-danger" id="delbutton" qtable='<?php echo $ttbdata->class_id; ?>' qid='<?php echo $ttbdata->id; ?>' name="submit"><i class="icon-remove"></i> <?php lang('remove') ?></button>
			<span id="submit"></span>
		</div>
	</form>
<?php
	endif;
?>

<script language="javascript">


//////////////////////////////////////////////////////////////////////
	$(" input[type=radio], input[type=checkbox]").uniform();
	   
	$(".chzn-select").chosen(); 
	$(".chzn-select-deselect").chosen({allow_single_deselect:true});
	   
	$('.datetimepk').datepicker({
		format: 'yyyy-mm-dd'
	})
	
	$('.formchange').click(function(){
		loadform('includes/ttb/ttb_manager_form.php?lang=<?php echo $lang ?>&type='+$(this).attr('form'),'#parameter','#loadform');
	})
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
</script>