<?php
include('../../config.php');
$timetables = $_SESSION[$config_session]['timetables'];
$filter_class_id = $_SESSION[$config_session]['filter_class_id'];
$atddata = $_SESSION[$config_session]['atddata'];
//print_r($atddata);
$id = $_REQUEST['id'];

foreach($atddata->messageObject->students as $lists){
	if($lists->id == $id) $stddata = $lists;
}

//print_r($stddata);
$subject = "";
//print_r($timetables);
foreach($timetables as $timetable){
	$ss = explode('@',$timetable->session);
	$subject.="<option value='$timetable->subject_id'>$ss[0] - $timetable->subject</option>";
}

$date = date('Y-m-d');
echo<<<EOT
<form method=POST id='atdform' class="form-horizontal" action='includes/atd/atd_manager_send.php'>
		<div class="control-group">
			<label class="control-label">Student</label>
			<div class="controls">
				<input type='text' name='' value="$stddata->fullname ($stddata->sso_id)" readonly>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label">Subject</label>
			<div class="controls">
				<input type=hidden value="$lists->school_id" name="school_id">
				<input type=hidden value="$filter_class_id" name="class_id">
				<input type=hidden value="$date" name="att_dt">
				<input type=hidden value="$id" name="student_id">
				<input type=hidden value="$lists->state" name="state">
				<input type=hidden value="1" name="absent">
				<input type=hidden value="0" name="excused">
				<input type=hidden value="0" name="late">
				<select name="subject_id">
					$subject
				</select>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label">Reason</label>
			<div class="controls">
				<select name="notice">
					<option value="Ly Do 1">Ly Do 1</option>
					<option value="Ly Do 2">Ly Do 2</option>
					<option value="Ly Do 3">Ly Do 3</option>
					<option value="Ly Do 4">Ly Do 4</option>
					<option value="6">Lý do khác</option>
				</select>
			</div>
		</div>

		<div class="control-group" id="reason">
			<label class="control-label">Reason</label>
			<div class="controls">
				<input type=text>
			</div>
		</div>
		
		<div class="form-actions">
			<button type="submit" class="btn btn-info" name="submit"><i class="icon-location-arrow"></i> Send ເຊັກ​ລາຍ​ຊື່​ນັກ​ນ​ຮຽນ​ຂາດ</button>
			<span id="atdsubmit"></span>
		</div>
</form>

<script language="javascript">
	$('#reason').hide();
	$('select[name=notice]').change(function(){
		if($(this).val()==6){
			$('#reason').show();
		}else $('#reason').hide();
	})
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
