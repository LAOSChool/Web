<?php
	include('../../config.php');
	ensure_permission('cls');
	ensure_role('mod,sadmin,admin');

	$type = $_REQUEST['type'];
	$time = time();
?>
<div class="bs-docs-example">
	<div class="navbar navbar-static">
		<div class="navbar-inner">
			<div style="width: auto;" class="container">
				<a href="javascript:;" class="brand"><?php lang('clsmanager') ?></a>
				<ul class="nav">
					<li <?php echo ($type=='search' || $type == '')?"class='active'":"" ?>><a href="javascript:;" class='formchange' form="search"><i class="icon-search"></i> <?php lang('search') ?></a></li>
					<li <?php echo ($type=='add')?"class='active'":"" ?>><a href="javascript:;" class='formchange' form="add"><i class="icon-plus"></i> <?php lang('addclass') ?></a></li>
					<li><a href="javascript:;" id='loadform'></a></li>
				</ul>
			</div>
		</div>
	</div>
</div>

<?php if($type=='search' || $type == ''): ?>

	<form method=POST id='searchform' class="form-horizontal" action='includes/cls/cls_manager_db.php?lang=<?php echo $lang ?>'>
		<div class="control-group">
			<label class="control-label"><?php lang('classname') ?></label>
			<div class="controls">
				
				
				<button type="submit" class="btn btn-info" name="submit"><i class="icon-search"></i> <?php lang('view') ?></button>
				<span id='searchtext'></span>
			</div>
		</div>
	</form>

<?php elseif($type=='add'): ?>
	<form method=POST id='managerform' class="form-horizontal" action='includes/cls/cls_manager_add.php?lang=<?php echo $lang ?>'>
	
		<div class="control-group">
			<label class="control-label"><?php lang('titlenlocation') ?></label>
			<div class="controls">
				<input type="text" class="input-medium" name="title" value="" placeholder="<?php lang('classtitle') ?>">
				<input type="text" class="input-large" name="location" value="" placeholder="<?php lang('classlocation') ?>">
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label"><?php lang('levelnyear') ?></label>
			<div class="controls">
				<select name="level" class="input-large">
					<option value="">- <?php lang('level') ?> -</option>
					<?php
							$headers = array();
							$auth_key = $_SESSION[$config_session]['auth_key'];
							$headers[] = "auth_key: $auth_key";
							$headers[] = "api_key: TEST_API_KEY";

							$levaip = callapi($headers,'',$gets,'api/masters/m_clslevel');
							$levdatas = explode("\n",$levaip['output']);
							$levdata = json_decode(end($levdatas));
							
							foreach($levdata->messageObject->list as $lists){
								$selected = ($lists->fval1==$clsdata->level)?"selected":"";
								echo "<option $selected value='$lists->fval1'>$lists->sval</option>";
							}
						?>
				</select>
				
				<select name="year_id" class="input-large">
					<option value="">- <?php lang('year') ?> -</option>
				
					<?php
							$headers = array();
							$auth_key = $_SESSION[$config_session]['auth_key'];
							$headers[] = "auth_key: $auth_key";
							$headers[] = "api_key: TEST_API_KEY";

							$yrsaip = callapi($headers,'',$gets,'api/schools/years');
							$yrsdatas = explode("\n",$yrsaip['output']);
							$yrsdata = json_decode(end($yrsdatas));
						
						//print_r($yrsdata);
							foreach($yrsdata->messageObject as $lists){
								echo "<option value='$lists->id'>$lists->years</option>";
							}
						?>
				</select>
			
			</div>
		</div>
	
		<div class="control-group">
			<label class="control-label"><?php lang('startdate') ?> & <?php lang('teacher') ?></label>
			<div class="controls">
				<input class="datetimepk input-small" name="start_dt" type="text" value="" placeholder="<?php lang('startdate') ?>">
				
				<select name="head_teacher_id" data-placeholder="Select Students" class="input-large">
					<option value="">- <?php lang('teacher') ?> -</option>
					<?php
							$headers = array();
							$auth_key = $_SESSION[$config_session]['auth_key'];
							$headers[] = "auth_key: $auth_key";
							$headers[] = "api_key: TEST_API_KEY";
							$gets['filter_user_role'] = "TEACHER";
							$stdaip = callapi($headers,'',$gets,'api/users/available');
							$stddatas = explode("\n",$stdaip['output']);
							$stddata = json_decode(end($stddatas));
						
							foreach($stddata->list as $lists){
								echo "<option value='$lists->id'>$lists->fullname</option>";
							}
						?>
				</select>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label"><?php lang('status') ?></label>
			<div class="controls">
				<select name="sts" class="input-large">
					<option value="">- <?php lang('status') ?> -</option>
					<?php
							$headers = array();
							$auth_key = $_SESSION[$config_session]['auth_key'];
							$headers[] = "auth_key: $auth_key";
							$headers[] = "api_key: TEST_API_KEY";

							$stsaip = callapi($headers,'',$gets,'api/sys/sys_sts');
							$stsdatas = explode("\n",$stsaip['output']);
							$stsdata = json_decode(end($stsdatas));
							
							foreach($stsdata->messageObject->list as $lists){
								echo "<option value='$lists->id'>$lists->sval</option>";
							}
						?>
				</select>
			</div>
		</div>
		
		
	
		<div class="form-actions">
			<button type="submit" class="btn btn-info" name="submit"><i class="icon-plus"></i> <?php lang('addclass') ?></button>
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

		$clsapi = callapi($headers,'',$gets,"/api/classes/$id");
		$clsdatas = explode("\n",$clsapi['output']);
		$clsdata = json_decode(end($clsdatas));
		
		$clsdata->start_dt = reset(explode(' ',$clsdata->start_dt));
		
	?>
	<form method=POST id='managerform' class="form-horizontal" action='includes/cls/cls_manager_edit.php?lang=<?php echo $lang ?>'>
		<div class="control-group">
			<label class="control-label"><?php lang('titlenlocation') ?></label>
			<div class="controls">
				<input type="hidden" name="id" value="<?php echo $clsdata->id ?>">
				<input type="text" class="input-medium" name="title" value="<?php echo $clsdata->title ?>" placeholder="<?php lang('classtitle') ?>">
				<input type="text" class="input-large" name="location" value="<?php echo $clsdata->location ?>" placeholder="<?php lang('classlocation') ?>">
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label"><?php lang('levelnyear') ?></label>
			<div class="controls">
				<select name="level" class="input-large">
					<option value="">- <?php lang('level') ?> -</option>
					<?php
							$headers = array();
							$auth_key = $_SESSION[$config_session]['auth_key'];
							$headers[] = "auth_key: $auth_key";
							$headers[] = "api_key: TEST_API_KEY";

							$levaip = callapi($headers,'',$gets,'api/masters/m_clslevel');
							$levdatas = explode("\n",$levaip['output']);
							$levdata = json_decode(end($levdatas));
							
							foreach($levdata->messageObject->list as $lists){
								$selected = ($lists->fval1==$clsdata->level)?"selected":"";
								echo "<option $selected value='$lists->fval1'>$lists->sval</option>";
							}
						?>
				</select>
	
				<select name="year_id" class="input-medium">
					<option value="">- <?php lang('year') ?> -</option>
				
					<?php
							$headers = array();
							$auth_key = $_SESSION[$config_session]['auth_key'];
							$headers[] = "auth_key: $auth_key";
							$headers[] = "api_key: TEST_API_KEY";

							$yrsaip = callapi($headers,'',$gets,'api/schools/years');
							$yrsdatas = explode("\n",$yrsaip['output']);
							$yrsdata = json_decode(end($yrsdatas));
	
							foreach($yrsdata->messageObject as $lists){
								$selected = ($lists->id==$clsdata->year_id)?"selected":"";
								echo "<option $selected value='$lists->id'>$lists->years</option>";
							}
						?>
				</select>
			</div>
		</div>
	
		<div class="control-group">
			<label class="control-label"><?php lang('startdate') ?> & <?php lang('teacher') ?></label>
			<div class="controls">
				<input class="datetimepk input-small" name="start_dt" type="text" value="<?php echo $clsdata->start_dt ?>" placeholder="<?php lang('startdate') ?>">
				<select name="head_teacher_id" data-placeholder="Select Students" class="input-large">
					<option value="<?php echo $clsdata->head_teacher_id ?>"><?php echo $clsdata->headTeacherName ?></option>
					<?php
							$headers = array();
							$auth_key = $_SESSION[$config_session]['auth_key'];
							$headers[] = "auth_key: $auth_key";
							$headers[] = "api_key: TEST_API_KEY";
							$gets['filter_user_role'] = "TEACHER";

							$stdaip = callapi($headers,'',$gets,'api/users/available');
							$stddatas = explode("\n",$stdaip['output']);
							$stddata = json_decode(end($stddatas));
			
							foreach($stddata->list as $lists){
								$selected = ($lists->id==$clsdata->head_teacher_id)?"selected":"";
								
								echo "<option $selected value='$lists->id'>$lists->fullname</option>";
							}
						?>
				</select>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label"><?php lang('status') ?></label>
			<div class="controls">
				<select name="sts" class="input-large">
					<option value="">- <?php lang('status') ?> -</option>
					<?php
							$headers = array();
							$auth_key = $_SESSION[$config_session]['auth_key'];
							$headers[] = "auth_key: $auth_key";
							$headers[] = "api_key: TEST_API_KEY";

							$stsaip = callapi($headers,'',$gets,'api/sys/sys_sts');
							$stsdatas = explode("\n",$stsaip['output']);
							$stsdata = json_decode(end($stsdatas));
							
							foreach($stsdata->messageObject->list as $lists){
								$selected = ($lists->id==$clsdata->sts)?"selected":"";
								echo "<option $selected value='$lists->id'>$lists->sval</option>";
							}
						?>
				</select>
				
			</div>
		</div>
		
		<div class="form-actions">
			<button type="submit" class="btn btn-info" name="submit"><i class="icon-edit"></i> <?php lang('edit') ?></button>
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
		loadform('includes/cls/cls_manager_form.php?lang=<?php echo $lang ?>&type='+$(this).attr('form'),'#parameter','#loadform');
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