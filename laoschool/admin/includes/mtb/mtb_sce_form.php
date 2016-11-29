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
				<a href="javascript:;" class="brand"><?php lang('sclexam') ?></a>
				<ul class="nav">
					<li <?php echo ($type=='search' || $type == '')?"class='active'":"" ?>><a href="javascript:;" class='formchange' form="search"><i class="icon-search"></i> <?php lang('search') ?></a></li>
					<li><a href="javascript:;" id='loadform'></a></li>
				</ul>
			</div>
		</div>
	</div>
</div>

<?php if($type=='search' || $type == ''): ?>

	<form method=POST id='searchform' class="form-horizontal" action='includes/mtb/mtb_sce_db.php?lang=<?php echo $lang ?>'>
		<div class="control-group">
			<label class="control-label"><?php lang('view') ?></label>
			<div class="controls">
				<button type="submit" class="btn btn-info" name="submit"><i class="icon-search"></i> <?php lang('view') ?></button>
				<span id='searchtext'></span>
			</div>
		</div>
	</form>

<?php elseif($type=='add'): ?>
	
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

		$sceapi = callapi($headers,'',$gets,"api/schools/exams/$id");
		$scedatas = explode("\n",$sceapi['output']);
		$scedata = json_decode(end($scedatas))->messageObject;
		//print_r($scedata);
	?>
	
	<form method=POST id='managerform' class="form-horizontal" action='includes/mtb/mtb_sce_edit.php?lang=<?php echo $lang ?>'>
		<div class="control-group">
			<label class="control-label"><?php lang('examname') ?></label>
			<div class="controls">
				<input type="text" name='ex_displayname' value="<?php echo $scedata->ex_displayname ?>" class="input-medium">
				<input type="hidden" name='id' value="<?php echo $id ?>">
				<input type="hidden" name='school_id' value="<?php echo $scedata->school_id ?>">
				<input type="hidden" name='term_val' value="<?php echo $scedata->term_val ?>">
				<input type="hidden" name='ex_month' value="<?php echo $scedata->ex_month ?>">
				<input type="hidden" name='ex_type' value="<?php echo $scedata->ex_type ?>">
				<input type="hidden" name='ex_name' value="<?php echo $scedata->ex_name ?>">
				<input type="hidden" name='cls_levels' value="<?php echo $scedata->cls_levels ?>">
				<input type="hidden" name='ex_key' value="<?php echo $scedata->ex_key ?>">
			</div>
		</div>
		
	
		
		<div class="control-group">
			<label class="control-label"><?php lang('min') ?> & <?php lang('max') ?></label>
			<div class="controls">
				<input type="text" name='min' value="<?php echo $scedata->min ?>" class="input-small" placeholder="Min">
				<input type="text" name='max' value="<?php echo $scedata->max ?>" class="input-small" placeholder="Max">
			</div>
		</div>
		
		<div class="form-actions">
			<button type="submit" class="btn btn-info" name="submit"><i class="icon-edit"></i> <?php lang('edit') ?></button>
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
		loadform('includes/mtb/mtb_sce_form.php?lang=<?php echo $lang ?>&type='+$(this).attr('form'),'#parameter','#loadform');
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