
<?php
$cres = $db->query("select * from usssd_params");
while($cres->fetchInto($crow)){
	$v = $crow['param_name'];
	$$v = $crow['param_value'];
}
?>
<div class="span12">	
	<div class="widget blue">	
		<div class="widget-title">			
			<h4><i class="icon-reorder"></i> <?php echo $currentsubtab ?></h4>			
			<span class="tools">				
				<span id='monthy_load2'></span>			
				<a href="javascript:;" class="icon-chevron-down"></a>				
			</span>		
		</div>	
		
		<div class="widget-body">			

			<form method=POST id='managerform' class="form-horizontal" action='includes/sadmin/sadmin_setting_edit.php'>
				<fieldset>
					 <legend>Genearal Setting</legend>
				</fieldset>
				
				
				<div class="control-group">
					<label class="control-label">Content API Password</label>
					<div class="controls">
						<input type="text" name="apipass" value="<?php echo $apipass ?>" class="input-xlarge"/> 
						<span class="help-inline" id='queue'>MD5 Password</span>
					</div>
				</div>

				<div class="form-actions">
					<button type="submit" class="btn btn-info" name="submit"><i class="icon-edit"></i> ອັບ​ເດດ</button>
					<span id="submit"></span>
				</div>
			</form>
		</div>	
	</div>
</div>
<script language="javascript">
//////////////////////////////////////////////////////////////////////
	
	$(document).ready(function(){
		$(".chzn-select").chosen({ max_selected_options: 2 });
		$(".chzn-select").bind("chosen:maxselected", function () { }); 
	});

	$(" input[type=radio], input[type=checkbox]").uniform();

	$('.datetimepk').datepicker({
		format: 'yyyy-mm-dd'
	})
	$('#managerform').ajaxForm({
		target: '#submit', 
		beforeSubmit: function(){
			$('#submit').show().html('<span class="badge badge-warning"><i class="icon-spinner"></i> Xin hãy chờ....</span>'); 
		},
		success: function() { 
			;
		}
	});
</script>