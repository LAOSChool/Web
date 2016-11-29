<div class="span12">
	<div class="widget green">
		<div class="widget-title">
			<h4><i class="icon-reorder"></i> Cập nhân thông tin cá nhân</h4>
			<span class="tools">
			<a href="javascript:;" class="icon-chevron-down"></a>
			</span>
		</div>
		<div class="widget-body">
			<?php
				$username = $_SESSION[$config_session]['username'];
				$res = $db->query("select * from `vhs_users` where username='$username' limit 1");
				$row = $res->fetchRow();
			?>
			<form method="POST" id='managerform' class="form-horizontal" action='includes/acc/acc_m_account_edit.php'>
				<div class="control-group">
					<label class="control-label">Tên đăng nhập</label>
					<div class="controls">
						<input type="hidden" name="id" value="<?php echo $row['id']?>">
						<input readonly type="text" name="username" class="input-large" value="<?php echo $row['username']?>"/>
						<span class="help-inline">Không thể sửa</span>
					</div>
				</div>
				<div class="control-group">
					<label class="control-label">Mật khẩu</label>
					<div class="controls">
						<input type="password" name="password1" placeholder="Mật khẩu mới" class="input-large" />
						<input type="password" name="password2" placeholder="Nhập lại mật khẩu" class="input-large" />
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label">Số điện thoại</label>
					<div class="controls">
						<input type="text" name="phone" class="input-xlarge" value="<?php echo $row['phone'] ?>"/>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label">Địa chỉ email</label>
					<div class="controls">
						<input type="text" name="email" class="input-xlarge" value="<?php echo $row['email'] ?>"/>
					</div>
				</div>
			
				<div class="form-actions">
					<button type="submit" class="btn blue" name="submit"><i class="icon-ok"></i> Cập nhật</button>
					<button type="button" class="btn" onclick="window.location='index.php'"><i class=" icon-remove"></i> Bỏ qua</button>
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
			$('#submit').show().html('<span class="badge badge-warning"><i class="icon-spinner"></i> Xin hãy chờ....</span>'); 
		},
		success: function() { 
			//$('#loadext').show().html(''); 
		}
	}); 
</script>