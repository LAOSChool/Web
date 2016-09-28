<?php
	include('../../config.php');
	ensure_permission('usr');
	ensure_role('mod,sadmin,admin');

	$type = $_REQUEST['type'];
	$time = time();
?>
<div class="bs-docs-example">
	<div class="navbar navbar-static">
		<div class="navbar-inner">
			<div style="width: auto;" class="container">
				<a href="javascript:;" class="brand"><?php lang('usrmanager') ?></a>
				<ul class="nav">
					<li <?php echo ($type=='search' || $type == '')?"class='active'":"" ?>><a href="javascript:;" class='formchange' form="search"><i class="icon-search"></i> <?php lang('search') ?></a></li>
					<li <?php echo ($type=='add')?"class='active'":"" ?>><a href="javascript:;" class='formchange' form="add"><i class="icon-plus"></i> <?php lang('addusr') ?></a></li>
					<li><a href="javascript:;" id='loadform'></a></li>
				</ul>
			</div>
		</div>
	</div>
</div>

<?php if($type=='search' || $type == ''): ?>

	<form method=POST id='searchform' class="form-horizontal" action='includes/usr/usr_manager_db.php?lang=<?php echo $lang ?>'>
		<div class="control-group">
			<label class="control-label"><?php lang('role') ?></label>
			<div class="controls">
				<select name="filter_user_role" class="input-large">
					<option value="TEACHER">TEACHER</option>
					<option value="STUDENT">STUDENT</option>
					<option value="CLS_PRESIDENT">Class President</option>
				</select>
				
				<select name="filter_class_id" class="input-large">
					<option value="">- <?php lang('class') ?> -</option>
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
	<form method=POST id='managerform' class="form-horizontal" action='includes/usr/usr_manager_add.php?lang=<?php echo $lang ?>'>
	
		<div class="control-group">
			<label class="control-label"><?php lang('role') ?></label>
			<div class="controls">
				<select name="roles" class="input-large">
					<option value="TEACHER">TEACHER</option>
					<option value="STUDENT">STUDENT</option>
					<option value="CLS_PRESIDENT">CLS_PRESIDENT</option>
				</select>
			</div>
		</div>
		
		<div class="control-group teacherform">
			<label class="control-label"><?php lang('ssoid') ?></label>
			<div class="controls">
				<input type=text name="sso_id" class="input-large">
			</div>
		</div>	
		
		<div class="control-group">
			<label class="control-label"><?php lang('password') ?></label>
			<div class="controls">
				<input type="text" name="password" class="input-xlarge" value="10136" readonly>
			</div>
		</div>	
		
		<div class="control-group">
			<label class="control-label"><?php lang('name') ?></label>
			<div class="controls">
				<input type=text name="fullname" class="input-xlarge" placeholder="<?php lang('fullname') ?>">
				<input type=text name="nickname" class="input-large" placeholder="<?php lang('nickname') ?>">
			</div>
		</div>
	
		<div class="control-group">
			<label class="control-label"><?php lang('avatar') ?></label>
			<div class="controls">
				<div data-provides="fileupload" class="fileupload fileupload-new">
					<span class="btn btn-file">
						<span class="fileupload-new"><i class="icon-upload-alt"></i> <?php lang('selectfile') ?></span>
						<input name="uploadedfile" type="text">
						<input type="file" class="default" id="fileupload" type="file" name="files[]" data-url="assets/jquery-file-upload/server/php/">
					</span>
					<span class="help-inline" id="progress">
						jpg; png; jpeg
					</span>
				</div>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label"><?php lang('addr') ?></label>
			<div class="controls">
				<input type=text name="addr1" class="input-xxlarge" placeholder="<?php lang('addr1') ?>">
				<input type=text name="addr2" class="input-xxlarge"  placeholder="<?php lang('addr2') ?>">
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label"><?php lang('contactinf') ?></label>
			<div class="controls">
				<input type=text name="phone" class="input-large" placeholder="<?php lang('phone') ?>">
				<input type=text name="ext" class="input-large"  placeholder="<?php lang('ext') ?>">
				<input type=text name="email" class="input-large"  placeholder="<?php lang('email') ?>">
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label"><?php lang('gender') ?> & <?php lang('birthday') ?></label>
			<div class="controls">
				<input type=text name="birthday" class="datetimepk" placeholder="<?php lang('birthday') ?>">
				<select name="gender" class="input-large">
					<option value="">- <?php lang('gender') ?> -</option>
					<option value="male"><?php lang('male') ?></option>
					<option value="female"><?php lang('female') ?></option>
				</select>
			</div>
		</div>
		
		<span class="studentform"><hr>
		
		
		<div class="control-group">
			<label class="control-label"><?php lang('level') ?></label>
			<div class="controls">
				<select name="cls_level" class="input-large">
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
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label"><?php lang('stdname') ?></label>
			<div class="controls">
				<input type=text name="std_contact_name" class="input-large" placeholder="<?php lang('stdname') ?>">
				<input type=text name="std_parent_name" class="input-large" placeholder="<?php lang('stdprname') ?>">
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label">Student contact</label>
			<div class="controls">
				<input type=text name="std_contact_phone" class="input-large" placeholder="Student contact phone">
				<input type=text name="std_contact_email" class="input-large" placeholder="Parent contact email">
			</div>
		</div>
		
		<hr>
		</span>
		
		<div class="control-group">
			<label class="control-label"><?php lang('status') ?></label>
			<div class="controls">
				<select name="state" class="input-large">
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
								echo "<option value='$lists->fval1'>$lists->sval</option>";
							}
						?>
				</select>
			</div>
		</div>

		<div class="form-actions">
			<button type="submit" class="btn btn-info" name="submit"><i class="icon-plus"></i> <?php lang('addusr') ?></button>
			<span id="submit"></span>
		</div>
	</form>
	
<?php elseif($type=='edit'): ?>
	<?php 
		$id = $_REQUEST['id'];

		$auth_key = $_SESSION[$config_session]['auth_key'];

		$headers = array();
		$headers[] = "auth_key: $auth_key";
		$headers[] = "api_key: TEST_API_KEY";

		$usrapi = callapi($headers,'',$gets,"/api/users/$id");
		$usrdatas = explode("\n",$usrapi['output']);
		$usrdata = json_decode(end($usrdatas));
		$usrdata->birthday = reset(explode(' ',$usrdata->birthday));
		//print_r($usrdata);
	?>
	<form method=POST id='managerform' class="form-horizontal" action='includes/usr/usr_manager_edit.php?lang=<?php echo $lang ?>'>
		<div class="control-group teacherform">
			<label class="control-label"><?php lang('ssoid') ?></label>
			<div class="controls">
				<input type="hidden" name="id" value="<?php echo $usrdata->id ?>">
				<input type="hidden" name="roles" value="<?php echo $usrdata->roles ?>">
				<input type=text name="sso_id" class="input-large" value="<?php echo $usrdata->sso_id ?>">
			</div>
		</div>		
		
		<div class="control-group">
			<label class="control-label"><?php lang('name') ?></label>
			<div class="controls">
				<input type=text name="fullname" class="input-xlarge" placeholder="<?php lang('fullname') ?>" value="<?php echo $usrdata->fullname ?>">
				<input type=text name="nickname" class="input-large" placeholder="<?php lang('nickname') ?>" value="<?php echo $usrdata->nickname ?>">
			</div>
		</div>
	
		<div class="control-group">
			<label class="control-label"><?php lang('avatar') ?></label>
			<div class="controls">
				<div data-provides="fileupload" class="fileupload fileupload-new">
					<span class="btn btn-file">
						<span class="fileupload-new"><i class="icon-upload-alt"></i> <?php lang('selectfile') ?></span>
						<input name="uploadedfile" type="text">
						<input name="photo" type="hidden" class="small" value='<?php echo $usrdata->photo ?>'>
						<input type="file" class="default" id="fileupload" type="file" name="files[]" data-url="assets/jquery-file-upload/server/php/">
					</span>
					<span class="help-inline" id="progress">
						<a href="javascript:;" class="btn btn-danger btn-small" id='listenbutton' qid='<?php echo $usrdata->photo ?>' >
							<i class='icon-play-sign'></i> <?php lang('view') ?>
						</a> 
					jpg; png; jpeg
					</span>
				</div>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label"><?php lang('addr') ?></label>
			<div class="controls">
				<input type=text name="addr1" class="input-xxlarge" placeholder="<?php lang('addr1') ?>" value="<?php echo $usrdata->addr1 ?>">
				<input type=text name="addr2" class="input-xxlarge"  placeholder="<?php lang('addr2') ?>" value="<?php echo $usrdata->addr2 ?>">
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label"><?php lang('contactinf') ?></label>
			<div class="controls">
				<input type=text name="phone" class="input-large" placeholder="<?php lang('phone') ?>" value="<?php echo $usrdata->phone ?>">
				<input type=text name="ext" class="input-large"  placeholder="<?php lang('ext') ?>" value="<?php echo $usrdata->ext ?>">
				<input type=text name="email" class="input-large"  placeholder="<?php lang('email') ?>" value="<?php echo $usrdata->email ?>">
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label"><?php lang('gender') ?> & <?php lang('birthday') ?></label>
			<div class="controls">
				<input type=text name="birthday" class="datetimepk" placeholder="<?php lang('birthday') ?>" value="<?php echo $usrdata->birthday ?>">
				<select name="gender" class="input-large">
					<option value="">- <?php lang('gender') ?> -</option>
					<option value="male" <?php echo ($usrdata->gender=='male')?"selected":"" ?>><?php lang('male') ?></option>
					<option value="female" <?php echo ($usrdata->gender=='female')?"selected":"" ?>><?php lang('female') ?></option>
				</select>
			</div>
		</div>
		
		<span class="studentform"><hr>
		<div class="control-group">
			<label class="control-label"><?php lang('level') ?></label>
			<div class="controls">
				<select name="cls_level" class="input-large">
					<?php
							$headers = array();
							$auth_key = $_SESSION[$config_session]['auth_key'];
							$headers[] = "auth_key: $auth_key";
							$headers[] = "api_key: TEST_API_KEY";

							$levaip = callapi($headers,'',$gets,'api/masters/m_clslevel');
							$levdatas = explode("\n",$levaip['output']);
							$levdata = json_decode(end($levdatas));
							
							foreach($levdata->messageObject->list as $lists){
								$selected = ($lists->fval1 ==$usrdata->cls_level)?"selected":"";
								echo "<option $selected value='$lists->fval1'>$lists->sval</option>";
							}
						?>
				</select>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label"><?php lang('stdname') ?></label>
			<div class="controls">
				<input type=text name="std_contact_name" class="input-large" placeholder="<?php lang('stdname') ?>" value="<?php echo $usrdata->std_contact_name ?>">
				<input type=text name="std_parent_name" class="input-large" placeholder="<?php lang('stdprname') ?>" value="<?php echo $usrdata->std_parent_name ?>">
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label">Student contact</label>
			<div class="controls">
				<input type=text name="std_contact_phone" class="input-large" placeholder="Student contact phone" value="<?php echo $usrdata->std_contact_phone ?>">
				<input type=text name="std_contact_email" class="input-large" placeholder="Parent contact email" value="<?php echo $usrdata->std_contact_email ?>">
			</div>
		</div>
		
		<hr>
		</span>
		
		<div class="control-group">
			<label class="control-label"><?php lang('status') ?></label>
			<div class="controls">
				<select name="state" class="input-large">
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
								$selected= ($lists->fval1 == $usrdata->state)?"selected":"";
								echo "<option $selected value='$lists->fval1'>$lists->sval</option>";
							}
						?>
				</select>
			</div>
		</div>
		
		<div class="form-actions">
			<button type="submit" class="btn btn-info" name="submit"><i class="icon-edit"></i> <?php lang('editusr') ?></button>
			<span id="submit"></span>
		</div>
	</form>
	
<?php elseif($type=='changepass'): ?>
	<?php 
		$id = $_REQUEST['id'];

		$auth_key = $_SESSION[$config_session]['auth_key'];

		$headers = array();
		$headers[] = "auth_key: $auth_key";
		$headers[] = "api_key: TEST_API_KEY";

		$usrapi = callapi($headers,'',$gets,"/api/users/$id");
		$usrdatas = explode("\n",$usrapi['output']);
		$usrdata = json_decode(end($usrdatas));
		$usrdata->birthday = reset(explode(' ',$usrdata->birthday));
	?>
	<form method=POST id='managerform' class="form-horizontal" action='includes/usr/usr_manager_pass.php?lang=<?php echo $lang ?>'>
		<div class="control-group teacherform">
			<label class="control-label"><?php lang('ssoid') ?></label>
			<div class="controls">
				<input type=text name="sso_id" class="input-large" value="<?php echo $usrdata->sso_id ?>" readonly>
				<button type="submit" class="btn btn-info" name="submit"><i class="icon-key"></i> <?php lang('confirmrspass') ?></button>
				<span id="submit"></span>
			</div>
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
		loadform('includes/usr/usr_manager_form.php?lang=<?php echo $lang ?>&type='+$(this).attr('form'),'#parameter','#loadform');
	})
///////////////////////////////////////////////////////////////////////

	
	if($('select[name=roles],input[name=roles]').val()=="STUDENT"){
		$('.studentform').show();
		$('.teacherform').hide();
	}else{
		$('.studentform').hide();
		$('.teacherform').show();
	}
		
	$('#fileupload').fileupload({
		dataType: 'json',
		done: function (e, data) {
			if(data.result.error==null){
				$('#progress').html("<span class='badge badge-success'>"+ data.result.name + "</span> <?php lang('uploadok') ?>");
				$('input[name=uploadedfile]').val(data.result.name);
			}else{
				$('#progress').html("<span class='badge badge-important'><?php lang('error') ?>:</span> "+data.result.error);
				$('input[name=uploadedfile]').val('');
			}
			$(this).removeAttr("disabled");
		},
		progressall: function (e, data) {
			var progress = parseInt(data.loaded / data.total * 100, 10)-1;
			$('#progress').html("<span class='badge badge-info'><?php lang('uploading') ?></span> " + progress+"%");
	
		},
		submit: function(e, data){
			$('input[name=uploadedfile]').val('');
			$(this).attr("disabled","disabled");
		},
		formData: {filetype: 'jpg;png;jpeg'}
	});
	
	$('select[name=roles]').change(function(){
		if($(this).val()=="STUDENT"){
			$('.studentform').show();
			$('.teacherform').hide();
		}
		else{
			$('.studentform').hide();
			$('.teacherform').show();
		}
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