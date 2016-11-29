<div class="span12">
	<div class="widget blue">
		<div class="widget-title">
			<h4><i class="<?php echo $param_icon; ?>"></i> <?php lang('sltparameters') ?></h4>
			<span class="tools">
			<a href="javascript:;" class="icon-chevron-down"></a>
			</span>
		</div>
		<div class="widget-body">
			<form method="POST" id='searchform' class="form-horizontal" action='includes/weblog/weblog_history_db.php'>
				<div class="control-group">
					<label class="control-label">Username</label>
					<div class="controls">
						<input type="text" name="username" class="input-large" value=""/>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label">Description</label>
					<div class="controls">
						<input type="text" name="description" class="input-xxlarge" value=""/>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label">Default datepicker</label>

					<div class="controls">
						<input class="datetimepk" name="startdate" type="text" value="" placeholder="From" class="m-ctrl-medium" size=16>
						<input class="datetimepk" name="enddate" type="text" value="" placeholder="To" class="m-ctrl-medium" size=16>
					</div>
				</div>
			
				<div class="form-actions">
					<button type="submit" class="btn btn-info" name="submit"><i class="icon-search"></i> Search</button>
					<span id="searchtext"></span>
				</div>
			</form>
		</div>
	</div>
	<div class="widget green">
		<div class="widget-title">
			<h4><i class="icon-reorder"></i> <?php echo $currentsubtab ?></h4>
			<span class="tools">
				<span id="loadtext"></span>
				<a href="javascript:;" class="icon-chevron-down"></a>
			</span>
		</div>
		<div class="widget-body" id="entry">
			<script language="javascript">
				loadform('includes/weblog/weblog_history_db.php','#entry','#loadtext');
			</script>
		</div>
	</div>
</div>

<script language="javascript">
	$('.datetimepk').datepicker({
		format: 'yyyy-mm-dd'
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
	
</script>