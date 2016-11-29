<?php
include('../../config.php');
$timetables = $_SESSION[$config_session]['timetables'];

$student = $_SESSION[$config_session]['student'];

foreach($student as $s){
	$stt .= "<input type=hidden name='student[]' value='$s'>";
}

$id = $_REQUEST['id'];


echo<<<EOT

<form method=POST id='atdform' class="form-horizontal" action='includes/atd/atd_manager_send2.php'>
		<div class="control-group">
			<label class="control-label">Content</label>
			<div class="controls">
				<textarea rows=4 name="message" class="input-xlarge"></textarea>
				<label class="checkbox">
					<input name="filter_roles[]" type="checkbox" value="STUDENT" /> Send SMS
				</label>
				$stt
				<input type=hidden name="send_type" value="3">
			</div>
		</div>
		<div class="form-actions">
			<button type="submit" class="btn btn-info" name="submit"><i class="icon-location-arrow"></i> Send</button>
			<span id="atdsubmit"></span>
		</div>
</form>

<script language="javascript">
	$(" input[type=radio], input[type=checkbox]").uniform();
	$('#atdform').ajaxForm({
			target: '#atdsubmit', 
			beforeSubmit: function(){
				$('#atdsubmit').show().html('<span class="badge badge-warning"><i class="icon-spinner"></i> <?php lang('pleasewait') ?>....</span>'); 
			},
			success: function() { 
				;
			}
	});
</script>
EOT;
?>
