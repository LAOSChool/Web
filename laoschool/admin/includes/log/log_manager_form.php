<?php
	include('../../config.php');
	ensure_permission('log');
	ensure_role('mod,sadmin,admin');

	$type = $_REQUEST['type'];
	$time = time();

?>
<div class="bs-docs-example">
	<div class="navbar navbar-static">
		<div class="navbar-inner">
			<div style="width: auto;" class="container">
				<a href="javascript:;" class="brand"><?php lang('logmanager') ?></a>
				<ul class="nav">
					<li <?php echo ($type=='search' || $type == '')?"class='active'":"" ?>><a href="javascript:;" class='formchange' form="search"><i class="icon-search"></i> <?php lang('search') ?></a></li>
					<li><a href="javascript:;" id='loadform'></a></li>
				</ul>
			</div>
		</div>
	</div>
</div>

<?php if($type=='search' || $type == ''): ?>

	<form method=POST id='searchform' class="form-horizontal" action='includes/log/log_manager_db.php?lang=<?php echo $lang ?>'>
		<div class="control-group">
			<label class="control-label"><?php lang('sltdate') ?></label>
			<div class="controls">
				<input class="datetimepk" name="filter_from_dt" type="text" value="" placeholder="<?php lang('from') ?>">
				<input class="datetimepk" name="filter_to_dt" type="text" value="" placeholder="<?php lang('to') ?>">
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label"><?php lang('sltteacher') ?></label>
			<div class="controls">					
				<select name="filter_sso_id" data-placeholder="<?php lang('sltteacher') ?>" class="chzn-select span3">
					<?php
						$headers = array();
						$auth_key = $_SESSION[$config_session]['auth_key'];
						$headers[] = "auth_key: $auth_key";
						$headers[] = "api_key: TEST_API_KEY";
						$gets['from_row'] = 0;
						$gets['max_result'] = 99999;
						$gets['filter_user_role'] = "TEACHER";

						$stdaip = callapi($headers,'',$gets,'api/users');
						$stddatas = explode("\n",$stdaip['output']);
						$stddata = json_decode(end($stddatas));
						//print_r($stddata->list);
						
						echo "<option value=''>- $lang_all -</option>";
						foreach($stddata->list as $lists){
							$classes = $lists->classes[0]->title;
							echo "<option value='$lists->sso_id'>$lists->fullname ( $lists->sso_id )</option>";
						}
						
						$gets['filter_user_role'] = "CLS_PRESIDENT";
						$stdaip = callapi($headers,'',$gets,'api/users');
						$stddatas = explode("\n",$stdaip['output']);
						$stddata = json_decode(end($stddatas));
						foreach($stddata->list as $lists){
							$classes = $lists->classes[0]->title;
							echo "<option value='$lists->sso_id'>$lists->fullname ( $lists->sso_id )</option>";
						}
					?>
				</select>

				<select name="filter_type" class="chzn-select span3">
					<option value="">- <?php lang('acttype'); ?> -</option>
					<option value='MESSAGE'>MESSAGE</option>
					<option value='NOTIFY'>NOTIFY</option>
					<option value='ROLLUP'>ROLLUP</option>
					<option value='MARK'>MARK</option>
				</select>
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
		loadform('includes/log/log_manager_form.php?lang=<?php echo $lang ?>&type='+$(this).attr('form'),'#parameter','#loadform');
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