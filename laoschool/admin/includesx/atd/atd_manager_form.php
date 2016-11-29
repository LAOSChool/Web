<?php
	include('../../config.php');
	ensure_permission('atd');
	ensure_role('mod,sadmin,admin');

	$type = $_REQUEST['type'];
	$time = time();

?>
<div class="bs-docs-example">
	<div class="navbar navbar-static">
		<div class="navbar-inner">
			<div style="width: auto;" class="container">
				<a href="javascript:;" class="brand">ເຊັກ​ລາຍ​ຊື່​ນັກ​ນ​ຮຽນ​ຂາດ manager</a>
				<ul class="nav">
					<li <?php echo ($type=='search' || $type == '')?"class='active'":"" ?>><a href="javascript:;" class='formchange' form="search"><i class="icon-search"></i> Search</a></li>
					<li><a href="javascript:;" id='loadform'></a></li>
				</ul>
			</div>
		</div>
	</div>
</div>

<?php if($type=='search' || $type == ''): ?>

	<form method=POST id='searchform' class="form-horizontal" action='includes/atd/atd_manager_db.php'>
		<div class="control-group">
			<label class="control-label">Class</label>
			<div class="controls">
				<select name="filter_class_id" data-placeholder="Select Class" class="span3">
					<option value="">- Select Class -</option>
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
		
		
		<div class="control-group">
			<label class="control-label">Select Date</label>
			<div class="controls">
				<input class="datetimepk" name="filter_date" type="text" value="<?php echo date('Y-m-d') ?>" placeholder="">
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label"></label>
			<div class="controls">
				<button type="submit" class="btn btn-info" name="submit"><i class="icon-search"></i> Search</button>
				<span id='searchtext'></span>
			</div>
		</div>
	</form>

<?php elseif($type=='add'): ?>
	<form method=POST id='managerform' class="form-horizontal" action='includes/atd/atd_manager_send.php'>
	
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
				<?php
					$headers = array();
					$auth_key = $_SESSION[$config_session]['auth_key'];
					$headers[] = "auth_key: $auth_key";
					$headers[] = "api_key: TEST_API_KEY";
					$gets['from_row'] = 0;
					$gets['max_result'] = 9999;
					$gets['filter_user_role'] = "STUDENT";

					//$stdaip = callapi($headers,'',$gets,'api/users');
					//$stddatas = explode("\n",$stdaip['output']);
					//$stddata = json_decode($stddatas[14]);
					//print_r($stddata->list[0][0]->id);
				?>
					
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
							$fullname = $lists[0]->fullname;
							$id = $lists[0]->id;
							echo "<option value='$id'>$fullname</option>";
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
			<button type="submit" class="btn btn-info" name="submit"><i class="icon-location-arrow"></i> Send ເຊັກ​ລາຍ​ຊື່​ນັກ​ນ​ຮຽນ​ຂາດ</button>
			<span id="submit"></span>
		</div>
	</form>
	
<?php elseif($type=='edit'): ?>
	<?php
		$id=$_REQUEST['id'];
		$row = $db->getRow("SELECT * FROM songs where id=$id");
	?>
	<form method=POST id='managerform' class="form-horizontal" action='includes/atd/atd_manager_edit.php'>
		<div class="form-actions">
			<button type="submit" class="btn btn-info" name="submit"><i class="icon-edit"></i> Edit Song</button>
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
		loadform('includes/atd/atd_manager_form.php?type='+$(this).attr('form'),'#parameter','#loadform');
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