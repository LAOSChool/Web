<?php
	include('../../config.php');
	ensure_permission('rnk');
	ensure_role('mod,sadmin,admin');

	$type = $_REQUEST['type'];
	$time = time();
?>
<div class="bs-docs-example">
	<div class="navbar navbar-static">
		<div class="navbar-inner">
			<div style="width: auto;" class="container">
				<a href="javascript:;" class="brand"><?php lang('rnkview') ?></a>
				<ul class="nav">
					<li><a href="javascript:;" id='loadform'></a></li>
				</ul>
			</div>
		</div>
	</div>
</div>

<?php if($type=='search' || $type == ''): ?>

	<form method=POST  id='searchform' class="form-horizontal" action='includes/rnk/rnk_view_db.php?lang=<?php echo $lang ?>'>
		<div class="control-group">
			<label class="control-label"><?php lang('selectcls') ?></label>
			<div class="controls">
				<ul>
					<?php
						$headers = array();
						$auth_key = $_SESSION[$config_session]['auth_key'];
						$headers[] = "auth_key: $auth_key";
						$headers[] = "api_key: TEST_API_KEY";

						$levaip = callapi($headers,'',$gets,'api/masters/m_clslevel');
						$levdatas = explode("\n",$levaip['output']);
						$levdata = json_decode(end($levdatas));
						
						$clsaip = callapi($headers,'',$gets,'api/classes');
						$clsdatas = explode("\n",$clsaip['output']);
						$clsdata = json_decode(end($clsdatas));
						
						foreach($levdata->messageObject->list as $lists){
							echo "<li><label class='parentlevel' qid='$lists->fval1'>
										<b>$lists->sval</b>
									</label>";
								echo "<ul class='chirldlevel chirldlevel$lists->fval1'>";
		
									foreach($clsdata->list as $lists2){
										if($lists2->level != $lists->fval1) continue;
										echo "<li><label class='checkbox'>
												<input name='filter_class_id' type='radio' value='$lists2->id' class='class level$lists->fval1'/> $lists2->title
											</label></li>";
									}
								echo "</ul>";
							echo "</li>";
						}
					?>
			
				</ul>
			</div>
		</div>
		
		
		<div class="control-group">
			<label class="control-label"><?php lang('selectexam') ?></label>
			<div class="controls">
				
				<select name="ex_key" class="input-large">
					<?php
						$headers = array();
						$auth_key = $_SESSION[$config_session]['auth_key'];
						$headers[] = "auth_key: $auth_key";
						$headers[] = "api_key: TEST_API_KEY";

						$sceapi = callapi($headers,'',$gets,"api/schools/exams");
						$scedatas = explode("\n",$sceapi['output']);
						$scedata = json_decode(end($scedatas));
			
						foreach($scedata->messageObject as $lists){
							echo "<option $selected value='$lists->ex_key'>$lists->ex_displayname</option>";
						}
					?>
				</select>
			</div>
		</div>
		
		<div class="form-actions">
			<button type="submit" class="btn btn-info" name="submit"><i class="icon-search"></i> <?php lang('view') ?></button>
			<span id="searchtext"></span>
		</div>
		
	</form>
<?php
	endif;
?>

<script language="javascript">
	$('.chirldlevel').hide();
	$('.selectall').change(function(){
		if($(this).is(":checked")){
			$('.chirldlevel').show();
			$('.level').attr('checked', true);
			$('.class').attr('checked', true);
		}else{
			$('.level').attr('checked', false);
			$('.class').attr('checked', false);
		} 
		$(" input[type=radio], input[type=checkbox]").uniform();
	})

	$('.level').change(function(){
		if($(this).is(":checked")){
			$('.'+$(this).attr('id')).attr('checked', true);
		}else{
			$('.'+$(this).attr('id')).attr('checked', false);
			$('.selectall').attr('checked', false);
		}
		$(" input[type=radio], input[type=checkbox]").uniform();
	});
	
	$('.parentlevel').click(function(){
		if($('.chirldlevel'+$(this).attr('qid')).is(":visible"))
			$('.chirldlevel'+$(this).attr('qid')).hide();
		else $('.chirldlevel'+$(this).attr('qid')).show();
	})
	
//////////////////////////////////////////////////////////////////////
	$(" input[type=radio], input[type=checkbox]").uniform();
	   
	$(".chzn-select").chosen(); 
	$(".chzn-select-deselect").chosen({allow_single_deselect:true});
	   
	$('.datetimepk').datepicker({
		format: 'yyyy-mm-dd'
	})
	
	$('.formchange').click(function(){
		loadform('includes/rnk/rnk_view_form.php?lang=<?php echo $lang ?>&type='+$(this).attr('form'),'#parameter','#loadform');
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

	$('#viewform').ajaxForm({
		target: '#submit', 
		beforeSubmit: function(){
			$('#submit').show().html('<span class="badge badge-warning"><i class="icon-spinner"></i> <?php lang('pleasewait') ?>....</span>'); 
		},
		success: function() { 
			;
		}
	});
</script>