<?php
include("../../../config.php");
?>
<p class="lead"></p>
<form id='msgform' class="form-horizontal" action="<?php domain(0) ?>/includes/admin/msg/msg_manager_send.php?language=<?php echo $language; ?>">
	<div class="form-group">
	<label for="to_user_id" class="col-sm-2 control-label"><?php lang('sendto') ?></label>
		<div class="col-sm-10">
			<?php
				$auth_key = $_SESSION[$config_session]['auth_key'];
				$headers = array();
				$headers[] = "auth_key: $auth_key";

				$userdata = callapi($headers,'','','api/users/myprofile');
				$userdatas = explode("\n",$userdata['output']);
				$myprofile = json_decode(end($userdatas));	
			?>
			<select id='to_user_id' name='to_user_id' class="form-control">
				<?php foreach($myprofile->classes as $classes): ?>
					<option value='<?php echo $classes->head_teacher_id ?>'><?php echo $classes->headTeacherName ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</div>
	<div class="form-group">
		<label for="content" class="col-sm-2 control-label"><?php lang('msgcontent') ?></label>
		<div class="col-sm-10">
			<textarea class="form-control" name='content' id='content' rows=5></textarea>
		</div>
	</div>

	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<button type="submit" name="submit" class="btn btn-primary"><?php lang('msgsend'); ?></button>
			<span id='msgsubmit'></span>
		</div>
	</div>
</form>

<script language='javascript'>
	$('#msgform').ajaxForm({
		target: '#msgsubmit', 
		beforeSubmit: function(){
			$('#msgsubmit').show().html('<span class="alert alert-warning"><?php lang('pleasewait') ?></span>'); 
		},
		success: function() { 
			;
		}
	})
</script>