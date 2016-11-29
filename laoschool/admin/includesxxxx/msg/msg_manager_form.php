<?php
	include('../../config.php');
	ensure_permission('msg');
	ensure_role('mod,sadmin,admin');

	$type = $_REQUEST['type'];
	$time = time();

?>
<div class="bs-docs-example">
	<div class="navbar navbar-static">
		<div class="navbar-inner">
			<div style="width: auto;" class="container">
				<a href="javascript:;" class="brand"><?php lang('msgmanager') ?></a>
				<ul class="nav">
					<li <?php echo ($type=='search' || $type == '')?"class='active'":"" ?>><a href="javascript:;" class='formchange' form="search"><i class="icon-search"></i> <?php lang('search') ?></a></li>
					<li <?php echo ($type=='add')?"class='active'":"" ?>><a href="javascript:;" class='formchange' form="add"><i class="icon-plus"></i> <?php lang('sendmsg') ?></a></li>
					<li><a href="javascript:;" id='loadform'></a></li>
				</ul>
			</div>
		</div>
	</div>
</div>

<?php if($type=='search' || $type == ''): ?>

	<form method=POST id='searchform' class="form-horizontal" action='includes/msg/msg_manager_db.php?lang=<?php echo $lang ?>'>
		<div class="control-group">
			<label class="control-label"><?php lang('senddate') ?></label>
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
	<form method=POST id='managerform' class="form-horizontal" action='includes/msg/msg_manager_send.php'>
	
		<div class="control-group">
			<label class="control-label"><?php lang('sendtype') ?></label>
			<div class="controls">
				<select name="send_type">
					<option value=0><?php lang('send2allscl') ?></option>
					<option value=1><?php lang('send2class') ?></option>
					<option value=3><?php lang('send2std') ?></option>
				</select>
			</div>
		</div>
		
		<div class="control-group classes">
			<label class="control-label"><?php lang('classes') ?></label>
			<div class="controls">	
				<select name="filter_class_list[]" data-placeholder="<?php lang('selectcls') ?>" class="chzn-select span5" multiple="multiple">
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
			<label class="control-label"><?php lang('students') ?></label>
			<div class="controls">					
				<select name="student[]" data-placeholder="<?php lang('selectstd') ?>" class="chzn-select span5" multiple="multiple">
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
							echo "<option value='$lists->id'>$lists->fullname</option>";
						}
					?>
				</select>

			</div>
		</div>
		
		<div class="control-group classes">
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
			<label class="control-label"><?php lang('msgcontent') ?></label>
			<div class="controls">
				<textarea name="message" class="input-xxlarge" rows=5></textarea>
			</div>
		</div>
		
		<div class="form-actions">
			<button type="submit" class="btn btn-info" name="submit"><i class="icon-location-arrow"></i> <?php lang('sendmsg') ?></button>
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
		loadform('includes/msg/msg_manager_form.php?lang=<?php echo $lang ?>&type='+$(this).attr('form'),'#parameter','#loadform');
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