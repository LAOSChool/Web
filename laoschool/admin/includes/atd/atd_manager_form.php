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
				<a href="javascript:;" class="brand"><?php lang('atdmanager') ?></a>
				<ul class="nav">
					<li <?php echo ($type=='search' || $type == '')?"class='active'":"" ?>><a href="javascript:;" class='formchange' form="search"><i class="icon-search"></i> <?php lang('search') ?></a></li>
					<li><a href="javascript:;" id='loadform'></a></li>
				</ul>
			</div>
		</div>
	</div>
</div>

<?php if($type=='search' || $type == ''): ?>

	<form method=POST id='searchform' class="form-horizontal" action='includes/atd/atd_manager_db.php?lang=<?php echo $lang ?>'>
		<div class="control-group">
			<label class="control-label"><?php lang('class') ?></label>
			<div class="controls">
				<select name="filter_class_id" data-placeholder="Select Class" class="span3">
					<option value="">- <?php lang('selectcls') ?> -</option>
					<?php
						$headers = array();
						$auth_key = $_SESSION[$config_session]['auth_key'];
						$headers[] = "auth_key: $auth_key";
						$headers[] = "api_key: TEST_API_KEY";
						$gets['from_row'] = 0;
						$gets['max_result'] = 9999;

						$clsaip = callapi($headers,'',$gets,'api/classes');
						$clsdatas = explode("\n",$clsaip['output']);
						$clsdata = json_decode(end($clsdatas));

						foreach($clsdata->list as $lists){
							echo "<option value='$lists->id'>$lists->title</option>";
						}
					?>
				</select>
			</div>
		</div>
		
		
		<div class="control-group">
			<label class="control-label"><?php lang('sltdate') ?></label>
			<div class="controls">
				<input class="datetimepk" name="filter_date" type="text" value="<?php echo date('Y-m-d') ?>" placeholder="">
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
		loadform('includes/atd/atd_manager_form.php?lang=<?php echo $lang ?>&type='+$(this).attr('form'),'#parameter','#loadform');
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