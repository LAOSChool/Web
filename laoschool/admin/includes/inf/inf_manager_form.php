<?php
	include('../../config.php');
	ensure_permission('inf');
	ensure_role('mod,sadmin,admin');
	
	$type = $_REQUEST['type'];
	$time = time();

	$headers = array();
	$auth_key = $_SESSION[$config_session]['auth_key'];
	$headers[] = "auth_key: $auth_key";
	$headers[] = "api_key: TEST_API_KEY";
	$userdata = callapi($headers,'','','api/users/myprofile');
	$userdatas = explode("\n",$userdata['output']);
	$myprofile = json_decode(end($userdatas));
	
	$infdata = callapi($headers,'','',"api/schools/$myprofile->school_id");
	$infdatas = explode("\n",$infdata['output']);
	$schooldata = json_decode(end($infdatas));
	//print_r($schooldata);
	
	$prvdata = callapi($headers,'','',"api/sys/sys_province");
	$prvdatas = explode("\n",$prvdata['output']);
	$provincedata = json_decode(end($prvdatas));
	
	$dgrdata = callapi($headers,'','',"api/sys/sys_degree");
	$dgrdatas = explode("\n",$dgrdata['output']);
	$degreedata = json_decode(end($dgrdatas));
	
	$disdata = callapi($headers,'','',"api/sys/sys_dist");
	$disdatas = explode("\n",$disdata['output']);
	$distdata = json_decode(end($disdatas));
	//print_r($distdata);

?>
<div class="bs-docs-example">
	<div class="navbar navbar-static">
		<div class="navbar-inner">
			<div style="width: auto;" class="container">
				<a href="javascript:;" class="brand"><?php lang('sclmanager') ?></a>
				<ul class="nav">
					<li <?php echo ($type=='search' || $type == '')?"class='active'":"" ?>><a href="javascript:;" class='formchange' form="search"><i class="icon-edit"></i> <?php echo $schooldata->title ?></a></li>
					<li><a href="javascript:;" id='loadform'></a></li>
				</ul>
			</div>
		</div>
	</div>
</div>

<?php if($type=='search' || $type == ''): ?>

	<form method=POST id='managerform' class="form-horizontal" action='includes/inf/inf_manager_edit.php'>
	
		<div class="control-group">
			<label class="control-label"><?php lang('title') ?></label>
			<div class="controls">
				<input type="text" class="input-xlarge" name="title" value="<?php echo $schooldata->title ?>">
				<input type="hidden" name="id" value="<?php echo $schooldata->id ?>">
				<input type="hidden" name="lang" value="<?php echo $lang ?>">
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label"><?php lang('degreenfounddate') ?></label>
			<div class="controls">
				<select name="degree">
					<?php
						foreach($degreedata->messageObject->list as $list){
							$selected = ($list->id == $schooldata->degree)?"selected":"";
							echo "<option $selected value='$list->id'>$list->sval</option>";
						}
					?>
				</select>
				<input type="text" class="datetimepk" name="found_dt" value="<?php echo $schooldata->found_dt ?>" placeholder="<?php lang('founddate') ?>">
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label"><?php lang('addr1') ?></label>
			<div class="controls">
				<input type="text" class="input-xxlarge" name="addr1" value="<?php echo $schooldata->addr1 ?>">
			</div>
		</div>	
		
		<div class="control-group">
			<label class="control-label"><?php lang('addr2') ?></label>
			<div class="controls">
				<input type="text" class="input-xxlarge" name="addr2" value="<?php echo $schooldata->addr2 ?>">
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label"><?php lang('prnvncity') ?></label>
			<div class="controls">
				<select name="prov_city">
					<option value="">- <?php lang('pronvice') ?> -</option>
					<?php
						foreach($provincedata->messageObject->list as $list){
							$selected = ($list->id == $schooldata->prov_city)?"selected":"";
							echo "<option $selected value='$list->id'>$list->sval</option>";
						}
					?>
				</select>
				
				<select name="dist">
					<option value="">- <?php lang('dist') ?> -</option>
					<?php
						foreach($distdata->messageObject->list as $list){
							$selected = ($list->id == $schooldata->dist)?"selected":"";
							echo "<option class='fval fval_$list->fval1' $selected value='$list->id'>$list->sval</option>";
						}
					?>
				</select>
				
				<select name="county">
					<option value="1">Laos</option>
				</select>
				
				
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label"><?php lang('website') ?></label>
			<div class="controls">
				<input type="text" class="input-xxlarge" name="url" value="<?php echo $schooldata->url ?>" placeholder="http://">
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label"><?php lang('phonenfax') ?></label>
			<div class="controls">
				<input type="text" class="input-large" name="phone" value="<?php echo $schooldata->phone ?>" placeholder="<?php lang('phone') ?>">
				<input type="text" class="input-large" name="ext" value="<?php echo $schooldata->ext ?>" placeholder="<?php lang('ext') ?>">
				<input type="text" class="input-large" name="fax" value="<?php echo $schooldata->fax ?>" placeholder="<?php lang('fax') ?>">
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label"><?php lang('principal')?></label>
			<div class="controls">
				<input type="text" class="input-xlarge" name="principal" value="<?php echo $schooldata->principal ?>">
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label"><?php lang('photo') ?></label>
			<div class="controls">
				<div data-provides="fileupload" class="fileupload fileupload-new">
					<span class="btn btn-file">
						<span class="fileupload-new"><i class="icon-upload-alt"></i> <?php lang('selectfile') ?></span>
						<input name="uploadedfile" type="text">
						<input name="photo" type="hidden" class="small" value='<?php echo $schooldata->photo ?>'>
						<input type="file" class="default" id="fileupload" type="file" name="files[]" data-url="assets/jquery-file-upload/server/php/">
					</span>
					<span class="help-inline" id="progress">
						<a href="javascript:;" class="btn btn-danger btn-small" id='listenbutton' qid='<?php echo $schooldata->photo ?>' >
							<i class='icon-play-sign'></i> <?php lang('viewphoto') ?>
						</a> 
					jpg; png; jpeg
					</span>
				</div>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label"><?php lang('status') ?></label>
			<div class="controls">
				<select name="state">
					<option value="1"><?php lang('active') ?></option>
					<option value="0"><?php lang('deactive') ?></option>
				</select>
				
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label"></label>
			<div class="controls">
				<button type="submit" class="btn btn-info" name="submit"><i class="icon-edit"></i> <?php lang('update') ?></button>
				<span id='submit'></span>
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
		loadform('includes/inf/inf_manager_form.php?lang=<?php echo $lang ?>&type='+$(this).attr('form'),'#parameter','#loadform');
	})
///////////////////////////////////////////////////////////////////////
	
	$('select[name=dist] .fval').hide();
	$('select[name=dist] .fval_'+$('select[name=prov_city]').val()).show();
	
	$('select[name=prov_city]').change(function(){
		$('select[name=dist] .fval').hide();
		$('select[name=dist] .fval_'+$(this).val()).show();
		$('select[name=dist]').val('');
	})
	
///////////////////////////////////////////////////////////////////////
	$('#fileupload').fileupload({
		dataType: 'json',
		done: function (e, data) {
			if(data.result.error==null){
				$('#progress').html("<span class='badge badge-success'>"+ data.result.name + "</span> <?php lang('uploadok') ?>!");
				$('input[name=uploadedfile]').val(data.result.name);
			}else{
				$('#progress').html("<span class='badge badge-important'><?php lang('error') ?>:</span> "+data.result.error);
				$('input[name=uploadedfile]').val('');
			}
			$(this).removeAttr("disabled");
		},
		progressall: function (e, data) {
			var progress = parseInt(data.loaded / data.total * 100, 10)-1;
			$('#progress').html("<span class='badge badge-info'><?php lang('uploading') ?>...</span> " + progress+"%");
	
		},
		submit: function(e, data){
			$('input[name=uploadedfile]').val('');
			$(this).attr("disabled","disabled");
		},
		formData: {filetype: 'jpg;png;jpeg'}
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