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
				<a href="javascript:;" class="brand">Master Table</a>
				<ul class="nav">
					<li <?php echo ($type=='search' || $type == '')?"class='active'":"" ?>><a href="javascript:;" class='formchange' form="search"><i class="icon-search"></i> Search</a></li>
					<li <?php echo ($type=='add')?"class='active'":"" ?>><a href="javascript:;" class='formchange' form="add"><i class="icon-plus"></i> Add Class</a></li>
					<li><a href="javascript:;" id='loadform'></a></li>
				</ul>
			</div>
		</div>
	</div>
</div>

<?php if($type=='search' || $type == ''): ?>

	<form method=POST id='searchform' class="form-horizontal" action='includes/cls/cls_manager_db.php'>
		<div class="control-group">
			<label class="control-label">Class name</label>
			<div class="controls">
				
				
				<button type="submit" class="btn btn-info" name="submit"><i class="icon-search"></i> View</button>
				<span id='searchtext'></span>
			</div>
		</div>
	</form>

<?php elseif($type=='add'): ?>
	<form method=POST id='managerform' class="form-horizontal" action='includes/cls/cls_manager_add.php'>
	
		<div class="control-group">
			<label class="control-label">ຊື່​ໂຮງ​ຮຽນ & Location</label>
			<div class="controls">
				<input type="text" class="input-medium" name="title" value="" placeholder="Class ຊື່​ໂຮງ​ຮຽນ">
				<input type="text" class="input-large" name="location" value="" placeholder="Class Location">
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label">Level & Year</label>
			<div class="controls">
				<select name="level" class="input-large">
					<option value="">- Level -</option>
					<?php
							$headers = array();
							$auth_key = $_SESSION[$config_session]['auth_key'];
							$headers[] = "auth_key: $auth_key";
							$headers[] = "api_key: TEST_API_KEY";

							$levaip = callapi($headers,'',$gets,'api/masters/m_clslevel');
							$levdatas = explode("\n",$levaip['output']);
							$levdata = json_decode($levdatas[14]);
							
							foreach($levdata->messageObject->list as $lists){
								$selected = ($lists->id==$clsdata->level)?"selected":"";
								echo "<option $selected value='$lists->id'>$lists->sval</option>";
							}
						?>
				</select>
				
				<select name="year_id" class="input-large">
					<option value="">- Year -</option>
				
					<?php
							$headers = array();
							$auth_key = $_SESSION[$config_session]['auth_key'];
							$headers[] = "auth_key: $auth_key";
							$headers[] = "api_key: TEST_API_KEY";

							$yrsaip = callapi($headers,'',$gets,'api/schools/years');
							$yrsdatas = explode("\n",$yrsaip['output']);
							$yrsdata = json_decode($yrsdatas[14]);
						
						//print_r($yrsdata);
							foreach($yrsdata->messageObject as $lists){
								echo "<option value='$lists->id'>$lists->years</option>";
							}
						?>
				</select>
			
			</div>
		</div>
	
		<div class="control-group">
			<label class="control-label">Start date & Teacher</label>
			<div class="controls">
				<input class="datetimepk input-small" name="start_dt" type="text" value="" placeholder="Start Date">
				
				<select name="head_teacher_id" data-placeholder="Select Students" class="input-large">
					<option value="">- Teacher -</option>
					<?php
							$headers = array();
							$auth_key = $_SESSION[$config_session]['auth_key'];
							$headers[] = "auth_key: $auth_key";
							$headers[] = "api_key: TEST_API_KEY";
							$gets['filter_user_role'] = "TEACHER";
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
		
		<div class="control-group">
			<label class="control-label">ສະຖາ​ນະ</label>
			<div class="controls">
				<select name="sts" class="input-large">
					<option value="">- ສະຖາ​ນະ -</option>
					<?php
							$headers = array();
							$auth_key = $_SESSION[$config_session]['auth_key'];
							$headers[] = "auth_key: $auth_key";
							$headers[] = "api_key: TEST_API_KEY";

							$stsaip = callapi($headers,'',$gets,'api/sys/sys_sts');
							$stsdatas = explode("\n",$stsaip['output']);
							$stsdata = json_decode($stsdatas[14]);
							
							foreach($stsdata->messageObject->list as $lists){
								echo "<option value='$lists->id'>$lists->sval</option>";
							}
						?>
				</select>
			</div>
		</div>
		
		
	
		<div class="form-actions">
			<button type="submit" class="btn btn-info" name="submit"><i class="icon-plus"></i> Add class</button>
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
		$clsdata = json_decode($clsdatas[14]);
		
		$clsdata->start_dt = reset(explode(' ',$clsdata->start_dt));
		
	?>
	<form method=POST id='managerform' class="form-horizontal" action='includes/cls/cls_manager_edit.php'>
		<div class="control-group">
			<label class="control-label">ຊື່​ໂຮງ​ຮຽນ & Location</label>
			<div class="controls">
				<input type="hidden" name="id" value="<?php echo $clsdata->id ?>">
				<input type="text" class="input-medium" name="title" value="<?php echo $clsdata->title ?>" placeholder="Class ຊື່​ໂຮງ​ຮຽນ">
				<input type="text" class="input-large" name="location" value="<?php echo $clsdata->location ?>" placeholder="Class Location">
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label">Level & Year</label>
			<div class="controls">
				<select name="level" class="input-large">
					<option value="">- Level -</option>
					<?php
							$headers = array();
							$auth_key = $_SESSION[$config_session]['auth_key'];
							$headers[] = "auth_key: $auth_key";
							$headers[] = "api_key: TEST_API_KEY";

							$levaip = callapi($headers,'',$gets,'api/masters/m_clslevel');
							$levdatas = explode("\n",$levaip['output']);
							$levdata = json_decode($levdatas[14]);
							
							foreach($levdata->messageObject->list as $lists){
								$selected = ($lists->id==$clsdata->level)?"selected":"";
								echo "<option $selected value='$lists->id'>$lists->sval</option>";
							}
						?>
				</select>
	
				<select name="year_id" class="input-medium">
					<option value="">- Year -</option>
				
					<?php
							$headers = array();
							$auth_key = $_SESSION[$config_session]['auth_key'];
							$headers[] = "auth_key: $auth_key";
							$headers[] = "api_key: TEST_API_KEY";

							$yrsaip = callapi($headers,'',$gets,'api/schools/years');
							$yrsdatas = explode("\n",$yrsaip['output']);
							$yrsdata = json_decode($yrsdatas[14]);
	
							foreach($yrsdata->messageObject as $lists){
								$selected = ($lists->id==$clsdata->year_id)?"selected":"";
								echo "<option $selected value='$lists->id'>$lists->years</option>";
							}
						?>
				</select>
			</div>
		</div>
	
		<div class="control-group">
			<label class="control-label">Start Date & teacher</label>
			<div class="controls">
				<input class="datetimepk input-small" name="start_dt" type="text" value="<?php echo $clsdata->start_dt ?>" placeholder="Start Date">
				<select name="head_teacher_id" data-placeholder="Select Students" class="input-large">
					<option value="">- Teacher -</option>
					<?php
							$headers = array();
							$auth_key = $_SESSION[$config_session]['auth_key'];
							$headers[] = "auth_key: $auth_key";
							$headers[] = "api_key: TEST_API_KEY";
							$gets['filter_user_role'] = "TEACHER";

							$stdaip = callapi($headers,'',$gets,'api/users');
							$stddatas = explode("\n",$stdaip['output']);
							$stddata = json_decode($stddatas[14]);
			
							foreach($stddata->list as $lists){
								$selected = ($lists->id==$clsdata->head_teacher_id)?"selected":"";
								
								echo "<option $selected value='$lists->id'>$lists->fullname</option>";
							}
						?>
				</select>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label">ສະຖາ​ນະ</label>
			<div class="controls">
				<select name="sts" class="input-large">
					<option value="">- ສະຖາ​ນະ -</option>
					<?php
							$headers = array();
							$auth_key = $_SESSION[$config_session]['auth_key'];
							$headers[] = "auth_key: $auth_key";
							$headers[] = "api_key: TEST_API_KEY";

							$stsaip = callapi($headers,'',$gets,'api/sys/sys_sts');
							$stsdatas = explode("\n",$stsaip['output']);
							$stsdata = json_decode($stsdatas[14]);
							
							foreach($stsdata->messageObject->list as $lists){
								$selected = ($lists->id==$clsdata->sts)?"selected":"";
								echo "<option $selected value='$lists->id'>$lists->sval</option>";
							}
						?>
				</select>
				
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