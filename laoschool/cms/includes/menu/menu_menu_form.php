<?php
	include('../../config.php');
	ensure_permission('menu');
	
	$type = $_REQUEST['type'];
	$time = time();
?>

<div class="bs-docs-example">
	<div class="navbar navbar-static">
		<div class="navbar-inner">
			<div style="width: auto;" class="container">
				<a href="javascript:;" class="brand"> Quản lý menu</a>
				<ul class="nav">
					<li <?php echo ($type=='search' || $type == '')?"class='active'":"" ?>><a href="javascript:;" class='formchange' form="search"><i class="icon-search"></i> Tìm kiếm</a></li>
					<li><a href="javascript:;" id='loadform'></a></li>
				</ul>
				
			</div>
		</div>
	</div>
</div>

<?php if($type=='search' || $type == ''): ?>

	<form method=POST id='searchform' class="form-horizontal" action='includes/menu/menu_menu_db.php'>
		<div class="control-group">
			<label class="control-label">Tên menu</label>
			<div class="controls">
				<input type="text" name="title" value="" class="input-large"/> 
				
				<button type="submit" class="btn btn-info" name="submit"><i class="icon-search"></i> Tìm kiếm</button>
				<span id='searchtext'></span>
			</div>
		</div>
	</form>

	
<?php elseif($type=='edit'): ?>
	<?php
		$id = $_REQUEST['id'];
		$res = $db->query("select * from vhs_menu where id='$id'");
		$res->fetchInto($row);
	?>
	<form method=POST id='managerform' class="form-horizontal" action='includes/menu/menu_menu_edit.php'>
		<div class="control-group">
			<label class="control-label">Kiểu menu</label>
			<div class="controls">
				<input type="hidden" name="id" value="<?php echo $id; ?>"/> 
				<input type="text" readonly name="type" value="<?php echo $row['type']; ?>" class="input-xlarge"/>
			</div>
		</div>
		
		
		<div class="control-group">
			<label class="control-label">Tên menu</label>
			<div class="controls">
				<input type="text" name="title" value="<?php echo $row['title']; ?>" class="input-xlarge"/>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label">Thứ tự</label>
			<div class="controls">
				<input type="text" name="order" value="<?php echo $row['order']; ?>" class="input-small"/> 
			</div>
		</div>
	
		<div class="form-actions">
			<button type="submit" class="btn btn-info" name="submit"><i class="icon-edit"></i> Cập nhật</button>
			<span id="submit"></span>
		</div>
	</form>
<?php
	endif;
?>

<script language="javascript">
	$('.menutype').hide();
	$('#'+$('select[name=type]').val()).show();
	if($('select[name=type]').val()=='newscategory') $('input[name=title]').attr('readonly','readonly');
	
	$('select[name=type]').change(function(){
		$('.menutype').hide();
		$('#'+$(this).val()).show();
		if($(this).val()=='newscategory') $('input[name=title]').attr('readonly','readonly');
		else $('input[name=title]').removeAttr('readonly');
	})

	$('select[name=uid]').change(function(){
		$('input[name=title]').val($("select[name=uid] :selected").text());
	})
	
	$('.datetimepk').datepicker({
		format: 'yyyy-mm-dd'
	})

	$("input[type=radio], input[type=checkbox]").uniform();
		
	$('.formchange').click(function(){
		loadform('includes/menu/menu_menu_form.php?type='+$(this).attr('form'),'#parameter','#loadform');
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