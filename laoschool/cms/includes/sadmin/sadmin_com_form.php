<?php
	include('../../config.php');
	ensure_role('sadmin');
	ensure_permission('sadmin');

	$type = $_REQUEST['type'];
	$time = time();
?>

<div class="bs-docs-example">
	<div class="navbar navbar-static">
		<div class="navbar-inner">
			<div style="width: auto;" class="container">
				<a href="javascript:;" class="brand">Company manager</a>
				<ul class="nav">
					<li <?php echo ($type=='search' || $type == '')?"class='active'":"" ?>><a href="javascript:;" class='formchange' form="search"><i class="icon-search"></i> Search</a></li>
					<li <?php echo ($type=='add')?"class='active'":"" ?>><a href="javascript:;" class='formchange' form="add"><i class="icon-plus"></i> Add Company</a></li>
					<li><a href="javascript:;" id='loadform'></a></li>
				</ul>
				
			</div>
		</div>
	</div>
</div>

<?php if($type=='search' || $type == ''): ?>

	<form method=POST id='searchform' class="form-horizontal" action='includes/sadmin/sadmin_com_db.php'>
		<div class="control-group">
			<label class="control-label">Company name</label>
			<div class="controls">
				<input type="text" name="name" value="" class="input-xlarge"/> 
				<button type="submit" class="btn btn-info" name="submit"><i class="icon-search"></i> Search</button>
				<span id='searchtext'></span>
			</div>
		</div>
	</form>

<?php elseif($type=='add'): ?>
	<form method=POST id='managerform' class="form-horizontal" action='includes/sadmin/sadmin_com_add.php'>	
		<div class="control-group">
			<label class="control-label">Company name</label>
			<div class="controls">
				<input name="name" class="input-large" value="" type="text" placeholder="Company name">
				<input name="short_code" class="input-medium" value="" type="text" placeholder="Company code">
				
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label">Company PBX</label>
			<div class="controls">
				<div class="input-prepend">
					<span class="add-on"><i class="icon-microphone"></i></span>
					<input name="pbx" class="input-large" value="" type="text">
				</div>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label">Address</label>
			<div class="controls">
				<input name="address" class="input-xxlarge" value="" type="text">
			</div>
		</div>

		<div class="form-actions">
			<button type="submit" class="btn btn-info" name="submit"><i class="icon-plus"></i> Add Company</button>
			<span id="submit"></span>
		</div>
	</form>
	
<?php elseif($type=='edit'): ?>
	<?php
		$id = $_REQUEST['id'];
		$res = $db->query("select * from companies where id='$id'");
		$res->fetchInto($row);
	?>
	<form method=POST id='managerform' class="form-horizontal" action='includes/sadmin/sadmin_com_edit.php'>
		<div class="control-group">
			<label class="control-label">Company name</label>
			<div class="controls">
				<input name="id" value="<?php echo $id?>" type="hidden">
				<input name="name" class="input-large" value="<?php echo $row['name']?>" type="text" placeholder="Company name">
				<input name="short_code" class="input-medium" value="<?php echo $row['short_code']?>" type="text" placeholder="Company code">
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label">Company PBX</label>
			<div class="controls">
				<div class="input-prepend">
					<span class="add-on"><i class="icon-microphone"></i></span>
					<input name="pbx" class="input-large" value="<?php echo $row['pbx']?>" type="text">
				</div>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label">Address</label>
			<div class="controls">
				<input name="address" class="input-xxlarge" value="<?php echo $row['address']?>" type="text">
			</div>
		</div>

		<div class="form-actions">
			<button type="submit" class="btn btn-info" name="submit"><i class="icon-edit"></i> Edit Company</button>
			<span id="submit"></span>
		</div>
	</form>
<?php
	endif;
?>

<script language="javascript">


	$('.datetimepk').datepicker({
		format: 'yyyy-mm-dd'
	})

	$("input[type=radio], input[type=checkbox]").uniform();
		
	$('.formchange').click(function(){
		loadform('includes/sadmin/sadmin_com_form.php?type='+$(this).attr('form'),'#parameter','#loadform');
	})

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
			$('#submit').show().html('<span class="badge badge-warning"><i class="icon-spinner"></i> Please wait....</span>'); 
		}, 
		success: function() { 
			;
		}
	}); 	
</script>