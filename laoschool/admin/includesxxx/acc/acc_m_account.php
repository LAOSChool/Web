<div class="span12">
	<div class="widget green">
		<div class="widget-title">
			<h4><i class="icon-reorder"></i> ອັບ​ເດດ profile information</h4>
			<span class="tools">
			<a href="javascript:;" class="icon-chevron-down"></a>
			</span>
		</div>
		<div class="widget-body">
			<?php
				$username = $_SESSION[$config_session]['username'];
				$res = $db->query("select * from `users` where username='$username' limit 1");
				$row = $res->fetchRow();
			?>
			<form method="POST" id='managerform' class="form-horizontal" action='includes/acc/acc_m_account_edit.php'>
				<div class="control-group">
					<label class="control-label">Username</label>
					<div class="controls">
						<input type="hidden" name="id" value="<?php echo $row['id']?>">
						<input readonly type="text" name="username" class="input-large" value="<?php echo $row['username']?>"/>
						<span class="help-inline">This can't be change</span>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Password</label>
					<div class="controls">
						<input type="password" name="password1" placeholder="Password" class="input-large" />
						<input type="password" name="password2" placeholder="Re-type Password" class="input-large" />
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label">Phone number</label>
					<div class="controls">
						<input type="text" name="phone" class="input-xlarge" value="<?php echo $row['phone'] ?>"/>
					</div>
				</div>
			
				<div class="form-actions">
					<button type="submit" class="btn blue" name="submit"><i class="icon-ok"></i> Save</button>
					<span id="submit"></span>
				</div>
			</form>
		
			
		</div>
	</div>
</div>

<script language="javascript">
	$('#managerform').ajaxForm({ 
		target: '#submit', 
		beforeSubmit: function(){
			$('#submit').show().html('<div class="alert"><?php lang('pleasewait') ?>...</div>'); 
		},
		success: function() { 
			//$('#loadext').show().html(''); 
		}
	}); 
</script>