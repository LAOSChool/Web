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

	<form method=POST id='searchform' class="form-horizontal" action='includes/mtb/mtb_scy_db.php?lang=<?php echo $lang ?>'>
		<div class="control-group">
			<label class="control-label"><?php lang('view') ?></label>
			<div class="controls">
				<button type="submit" class="btn btn-info" name="submit"><i class="icon-search"></i> <?php lang('view') ?></button>
				<span id='searchtext'></span>
			</div>
		</div>
	</form>

<?php elseif($type=='add'): ?>
	<form method=POST id='managerform' class="form-horizontal" action='includes/mtb/mtb_scy_add.php?lang=<?php echo $lang ?>'>
	
		<div class="control-group">
			<label class="control-label"><?php lang('year') ?></label>
			<div class="controls">
				<input type="text" name='years' value="" class="input-medium">
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label"><?php lang('from') ?> & <?php lang('to') ?></label>
			<div class="controls">
				<input type="text" name='from_year' value="" class="input-small" placeholder="<?php lang('from') ?>">
				<input type="text" name='to_year' value="" class="input-small" placeholder="<?php lang('to') ?>">
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

		$scyapi = callapi($headers,'',$gets,"api/schools/years/$id");
		$scydatas = explode("\n",$scyapi['output']);
		$scydata = json_decode(end($scydatas))->messageObject;
		//$scydata
	?>
	
	<form method=POST id='managerform' class="form-horizontal" action='includes/mtb/mtb_scy_edit.php?lang=<?php echo $lang ?>'>
		<div class="control-group">
			<label class="control-label"><?php lang('year') ?></label>
			<div class="controls">
				<input type="text" name='years' value="<?php echo $scydata->years ?>" class="input-medium">
				<input type="hidden" name='id' value="<?php echo $id ?>">
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label"><?php lang('from') ?> & <?php lang('to') ?></label>
			<div class="controls">
				<input type="text" name='from_year' value="<?php echo $scydata->from_year ?>" class="input-small" placeholder="<?php lang('from') ?>">
				<input type="text" name='to_year' value="<?php echo $scydata->to_year ?>" class="input-small" placeholder="<?php lang('to') ?>">
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
		loadform('includes/mtb/mtb_scy_form.php?lang=<?php echo $lang ?>&type='+$(this).attr('form'),'#parameter','#loadform');
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