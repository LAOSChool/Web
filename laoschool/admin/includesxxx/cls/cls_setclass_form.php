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
					<li><a href="javascript:;" id='loadform'></a></li>
				</ul>
			</div>
		</div>
	</div>
</div>

<?php if($type=='search' || $type == ''): ?>

	<form method=POST id='searchform' class="form-horizontal" action='includes/cls/cls_setclass_db.php?lang=<?php echo $lang ?>'>
		<div class="control-group">
			<label class="control-label">Class</label>
			<div class="controls">
				<select name="filter_class_id" class="input-large">
					<option value="available">Not Assigned</option>
					<?php
						$headers = array();
						$auth_key = $_SESSION[$config_session]['auth_key'];
						$headers[] = "auth_key: $auth_key";
						$headers[] = "api_key: TEST_API_KEY";

						$clsaip = callapi($headers,'',$gets,'api/classes');
						$clsdatas = explode("\n",$clsaip['output']);
						$clsdata = json_decode($clsdatas[14]);
			
						foreach($clsdata->list as $lists){
							$selected = ($lists->id==$usrdata->classes[0]->id)?"selected":"";
							echo "<option $selected value='$lists->id'>$lists->title</option>";
						}
					?>
					
				</select>
			</div>
		</div>
		<div class="control-group">
			<label class="control-label">Role</label>
			<div class="controls">
				<select name="filter_user_role" class="input-large">
					<option value="">- All -</option>
					<option value="TEACHER">TEACHER</option>
					<option value="STUDENT">STUDENT</option>
				</select>
			</div>
		</div>
		<div class="form-actions">
			<button type="submit" class="btn btn-info" name="submit"><i class="icon-search"></i> View</button>
			<span id='searchtext'></span>
		</div>	
	</form>

<?php elseif($type=='add'): ?>
	;
<?php elseif($type=='edit'): ?>
	<?php 
		$id = $_REQUEST['id'];
		$table = $_REQUEST['table'];

		$auth_key = $_SESSION[$config_session]['auth_key'];

		$headers = array();
		$headers[] = "auth_key: $auth_key";
		$headers[] = "api_key: TEST_API_KEY";

		$usrapi = callapi($headers,'',$gets,"/api/users/$id");
		$usrdatas = explode("\n",$usrapi['output']);
		$usrdata = json_decode($usrdatas[14]);	
		//print_r($usrdata);
	?>
	<form method=POST id='managerform' class="form-horizontal" action='includes/cls/cls_setclass_edit.php'>
		<div class="control-group">
			<label class="control-label">User</label>
			<div class="controls">
				<input type="hidden" name="user_id" value="<?php echo $usrdata->id ?>">
				<input type="hidden" name="filter_user_role" value="<?php echo $table ?>">
				<input type="text" value="<?php echo "($usrdata->sso_id) $usrdata->gender $usrdata->nickname"; ?>" readonly>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label">Class</label>
			<div class="controls">
				<select name="class_id" class="chzn-select input-large">
				
					<?php
							$headers = array();
							$auth_key = $_SESSION[$config_session]['auth_key'];
							$headers[] = "auth_key: $auth_key";
							$headers[] = "api_key: TEST_API_KEY";

							$clsaip = callapi($headers,'',$gets,'api/classes');
							$clsdatas = explode("\n",$clsaip['output']);
							$clsdata = json_decode($clsdatas[14]);
				
							foreach($clsdata->list as $lists){
								$selected = ($lists->id==$usrdata->classes[0]->id)?"selected":"";
								echo "<option $selected value='$lists->id'>$lists->title</option>";
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
		loadform('includes/cls/cls_setclass_form.php?lang=<?php echo $lang ?>&type='+$(this).attr('form'),'#parameter','#loadform');
	})
///////////////////////////////////////////////////////////////////////

	
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