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
				<a href="javascript:;" class="brand">Subject & Session</a>
				<ul class="nav">
					<li <?php echo ($type=='search' || $type == '')?"class='active'":"" ?>><a href="javascript:;" class='formchange' form="search"><i class="icon-search"></i> Search</a></li>
					<li <?php echo ($type=='add')?"class='active'":"" ?>><a href="javascript:;" class='formchange' form="add"><i class="icon-plus"></i> Add</a></li>
					<li><a href="javascript:;" id='loadform'></a></li>
				</ul>
			</div>
		</div>
	</div>
</div>

<?php if($type=='search' || $type == ''): ?>

	<form method=POST id='searchform' class="form-horizontal" action='includes/mtb/mtb_scy_db.php?lang=<?php echo $lang ?>'>
		<div class="control-group">
			<label class="control-label">Table</label>
			<div class="controls">
				<button type="submit" class="btn btn-info" name="submit"><i class="icon-search"></i> View</button>
				<span id='searchtext'></span>
			</div>
		</div>
	</form>

<?php elseif($type=='add'): ?>
	<form method=POST id='managerform' class="form-horizontal" action='includes/mtb/mtb_scy_add.php'>
	
		<div class="control-group">
			<label class="control-label">Years</label>
			<div class="controls">
				<input type="text" name='years' value="" class="input-medium">
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label">From & To</label>
			<div class="controls">
				<input type="text" name='from_year' value="" class="input-small" placeholder="From">
				<input type="text" name='to_year' value="" class="input-small" placeholder="To">
			</div>
		</div>
		
		<div class="form-actions">
			<button type="submit" class="btn btn-info" name="submit"><i class="icon-plus"></i> Add</button>
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
		$scydata = json_decode($scydatas[14])->messageObject;
		//$scydata
	?>
	
	<form method=POST id='managerform' class="form-horizontal" action='includes/mtb/mtb_scy_edit.php'>
		<div class="control-group">
			<label class="control-label">Years</label>
			<div class="controls">
				<input type="text" name='years' value="<?php echo $scydata->years ?>" class="input-medium">
				<input type="hidden" name='id' value="<?php echo $id ?>">
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label">From & To</label>
			<div class="controls">
				<input type="text" name='from_year' value="<?php echo $scydata->from_year ?>" class="input-small" placeholder="From">
				<input type="text" name='to_year' value="<?php echo $scydata->to_year ?>" class="input-small" placeholder="To">
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
	   
	$(".chzn-select").chosen(); $(".chzn-select-deselect").chosen({allow_single_deselect:true});
	   
	$('.datetimepk').datepicker({
		format: 'yyyy-mm-dd'
	})
	
	$('.formchange').click(function(){
		loadform('includes/mtb/mtb_scy_form.php?lang=<?php echo $lang ?>&type='+$(this).attr('form'),'#parameter','#loadform');
	})
///////////////////////////////////////////////////////////////////////

	
///////////////////////////////////////////////////////////////////////
	$('#file_upload').uploadify({
        'swf'      : 'assets/uploadify/uploadify.swf',
        'uploader' : 'assets/uploadify/uploadify.php',
		'checkExisting' : 'assets/uploadify/check-exists.php',
		
		'fileTypeDesc' : 'Sound file',
        'fileTypeExts' : '*.wav;*.mp3', 
		'multi'    : false,
		'queueSizeLimit' : 1,
		'itemTemplate' : '<span></span>',
		'buttonText' : 'ອັບ​ໂຫຼດ...',
		'width':93,
		'height':28,
		'onອັບ​ໂຫຼດProgress' : function(file, bytesອັບ​ໂຫຼດed, bytesTotal, totalBytesອັບ​ໂຫຼດed, totalBytesTotal) {
			$('#queue').html("<span class='badge badge-info'>"+file.name +"</span> ອັບ​ໂຫຼດing... "+ Math.round((bytesອັບ​ໂຫຼດed/bytesTotal)*100)+'%');
        },
		'onອັບ​ໂຫຼດSuccess' : function(file, data, response) {
			$('input[name=uploadedfile]').val(data);
			$('#queue').html("<span class='badge badge-success'>"+file.name + "</span> ອັບ​ໂຫຼດສຳ​ເລັດ!")
			$('#file_upload').uploadify('disable', false);
		},
		'onSelect' : function(file) {
			$('input[name=uploadedfile]').val('');
			$('input[name=path]').val('');
			$('#queue').html("<span class='badge badge-warning'>"+file.name +"</span> Reading...");
			$('#file_upload').uploadify('disable', true);
        },
		'onCancel' : function(file) {
			$('input[name=uploadedfile]').val('');
			$('input[name=path]').val('');
        },
		'onອັບ​ໂຫຼດError' : function(file, errorCode, errormtb, errorString) {
			$('#queue').html("<span class='badge badge-important'>"+file.name +"</span> could not be uploaded: "+ errorString);
        }
    }); 
	
	$('#file_upload-button').prepend('<i class="icon-upload-alt"></i> ');
	
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