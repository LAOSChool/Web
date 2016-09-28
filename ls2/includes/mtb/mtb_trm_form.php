<?php
	include('../../config.php');
	ensure_permission('mtb');
	ensure_role('mod,sadmin,admin');

	$type = $_REQUEST['type'];
	$time = time();
?>
<div class="bs-docs-example">
	<div class="navbar navbar-static">
		<div class="navbar-inner">
			<div style="width: auto;" class="container">
				<a href="javascript:;" class="brand"><?php lang('yearmanager') ?></a>
				<ul class="nav">
					<li <?php echo ($type=='search' || $type == '')?"class='active'":"" ?>><a href="javascript:;" class='formchange' form="search"><i class="icon-search"></i> <?php lang('search') ?></a></li>
					<li <?php echo ($type=='add')?"class='active'":"" ?>><a href="javascript:;" class='formchange' form="add"><i class="icon-plus"></i> <?php lang('add') ?></a></li>
					<li><a href="javascript:;" id='loadform'></a></li>
				</ul>
			</div>
		</div>
	</div>
</div>

<?php if($type=='search' || $type == ''): ?>

	<form method=POST id='searchform' class="form-horizontal" action='includes/mtb/mtb_trm_db.php?lang=<?php echo $lang ?>'>
		<div class="control-group">
			<label class="control-label"><?php lang('view') ?></label>
			<div class="controls">
				<select name="filter_year_id" class="input-medium">
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
				<button type="submit" class="btn btn-info" name="submit"><i class="icon-search"></i> <?php lang('view') ?></button>
				<span id='searchtext'></span>
			</div>
		</div>
	</form>

<?php elseif($type=='add'): ?>
	<form method=POST id='managerform' class="form-horizontal" action='includes/mtb/mtb_trm_add.php?lang=<?php echo $lang ?>'>
	
		<div class="control-group">
			<label class="control-label"><?php lang('year') ?> & <?php lang('term') ?></label>
			<div class="controls">
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
								echo "<option value='$lists->id'>$lists->years</option>";
							}
						?>
				</select>
				<input type="text" name='term_val' value="" class="input-small" placeholder="<?php lang('term') ?>">
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label"><?php lang('startdate') ?> & <?php lang('enddate') ?></label>
			<div class="controls">
				<input type="text" name='start_dt' value="" class="datetimepk input-medium" placeholder="<?php lang('startdate') ?>">
				<input type="text" name='end_dt' value="" class="datetimepk input-medium" placeholder="<?php lang('enddate') ?>">
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label"><?php lang('notice') ?></label>
			<div class="controls">
				<input type="text" name='notice' value="" class="input-xlarge">
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

		$gets['from_row'] = $pages;
		$gets['max_result'] = $limit;

		$trmapi = callapi($headers,'',$gets,"api/schools/terms/$id");
		$trmdatas = explode("\n",$trmapi['output']);
		$trmdata = json_decode(end($trmdatas))->messageObject;
		$trmdata->start_dt = reset(explode(' ',$trmdata->start_dt));
		$trmdata->end_dt = reset(explode(' ',$trmdata->end_dt));
		//$trmdata
	?>
	
	<form method=POST id='managerform' class="form-horizontal" action='includes/mtb/mtb_trm_edit.php?lang=<?php echo $lang ?>'>
		<div class="control-group">
			<label class="control-label"><?php lang('year') ?> & <?php lang('term') ?></label>
			<div class="controls">
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
								$selected = ($lists->id==$trmdata->year_id)?"selected":"";
								echo "<option $selected value='$lists->id'>$lists->years</option>";
							}
						?>
				</select>
				<input type="text" name='term_val' value="<?php echo $trmdata->term_val ?>" class="input-small" placeholder="<?php lang('term') ?>">
				<input type="hidden" name='id' value="<?php echo $id ?>">
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label"><?php lang('startdate') ?> & <?php lang('enddate') ?></label>
			<div class="controls">
				<input type="text" name='start_dt' value="<?php echo $trmdata->start_dt ?>" class="datetimepk input-medium" placeholder="<?php lang('startdate') ?>">
				<input type="text" name='end_dt' value="<?php echo $trmdata->end_dt ?>" class="datetimepk input-medium" placeholder="<?php lang('enddate') ?>">
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label"><?php lang('notice') ?></label>
			<div class="controls">
				<input type="text" name='notice' value="<?php echo $trmdata->notice ?>" class="input-xlarge">
			</div>
		</div>
		
		<div class="form-actions">
			<button type="submit" class="btn btn-info" name="submit"><i class="icon-edit"></i> <?php lang('edit') ?></button>
			<span id="submit"></span>
		</div>
	</form>
	
<?php elseif($type=='status'): ?>
	<?php 
		$id = $_REQUEST['id'];
		$table = $_REQUEST['table'];
		
		$auth_key = $_SESSION[$config_session]['auth_key'];

		$headers = array();
		$headers[] = "auth_key: $auth_key";
		$headers[] = "api_key: TEST_API_KEY";

		$gets['from_row'] = $pages;
		$gets['max_result'] = $limit;

		$trmapi = callapi($headers,'',$gets,"api/schools/terms/$id");
		$trmdatas = explode("\n",$trmapi['output']);
		$trmdata = json_decode(end($trmdatas))->messageObject;
		$trmdata->start_dt = reset(explode(' ',$trmdata->start_dt));
		$trmdata->end_dt = reset(explode(' ',$trmdata->end_dt));
		//$trmdata
	?>
	
	<form method=POST id='managerform' class="form-horizontal" action='includes/mtb/mtb_trm_status.php?lang=<?php echo $lang ?>'>
		<div class="control-group">
			<label class="control-label"><?php lang('year') ?> & <?php lang('term') ?></label>
			<div class="controls">
				<select name="actived" class="input-medium">
					<option value="0" <?php echo ($trmdata->actived==0)?"selected":""; ?>><?php lang('inactive') ?></option>
					<option value="1" <?php echo ($trmdata->actived==1)?"selected":""; ?>><?php lang('active') ?></option>
					<!--<option value="2" <?php echo ($trmdata->actived==2)?"selected":""; ?>><?php lang('pending') ?></option>-->
				</select>
				<input type="hidden" name='id' value="<?php echo $id ?>">
			</div>
		</div>
		
		<div class="form-actions">
			<button type="submit" class="btn btn-info" name="submit"><i class="icon-edit"></i> <?php lang('update') ?></button>
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
		loadform('includes/mtb/mtb_trm_form.php?lang=<?php echo $lang ?>&type='+$(this).attr('form'),'#parameter','#loadform');
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