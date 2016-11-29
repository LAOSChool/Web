<?php
	include('../../config.php');
	ensure_permission('admin');
	ensure_role('sadmin,admin');

	$type = $_REQUEST['type'];
?>

<div class="bs-docs-example">
	<div class="navbar navbar-static">
		<div class="navbar-inner">
			<div style="width: auto;" class="container">
				<a href="javascript:;" class="brand">Quản lý tài khoản</a>
				<ul class="nav">
					<li <?php echo ($type=='search' || $type == '')?"class='active'":"" ?>><a href="javascript:;" class='formchange' form="search"><i class="icon-search"></i> Tìm kiếm</a></li>
					<li <?php echo ($type=='add')?"class='active'":"" ?>><a href="javascript:;" class='formchange' form="add"><i class="icon-plus"></i> Thêm tài khoản</a></li>
					<li><a href="javascript:;" id='loadform'></a></li>
				</ul>
				
			</div>
		</div>
	</div>
</div>

<?php if($type=='search' || $type == ''): ?>

	<form method=POST id='searchform' class="form-horizontal" action='includes/admin/admin_user_db.php'>
		<div class="control-group">
			<label class="control-label">User name</label>
			<div class="controls">
				<input type="text" name="name" value="" class="input-xlarge"/> 
				<button type="submit" class="btn btn-info" name="submit"><i class="icon-search"></i> Tìm kiếm</button>
				<span id='searchtext'></span>
			</div>
		</div>
	</form>

<?php elseif($type=='add'): ?>
	<form method=POST id='managerform' class="form-horizontal" action='includes/admin/admin_user_add.php'>	
		<div class="control-group">
			<label class="control-label">Username</label>
			<div class="controls">
				<input name="username" class="input-large" value="" type="text">
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label">Mật Khẩu</label>
			<div class="controls">
				<input type="password" name="password1" class="input-medium"  placeholder="Mật Khẩu"> 
				<input type="password" name="password2" class="input-medium"  placeholder="Nhắc lại mất khẩu">
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label">Số điện thoại</label>
			<div class="controls">
				<input type="text" name="phone" class="input-xlarge"> 
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label">Quyền truy cập</label>
			<div class="controls">
				<?php
					include('../tab.php');
					$tab2s = explode(',', $_SESSION[$config_session]['permission'] );
					
					foreach($tab2s as $tab2){
						foreach($tabs as $tab){
							if($tab[0] == $tab2){
								$tabname=$tab[1];
								$tabshow=$tab[2];
							}
						}
						if($tabshow==1){
							echo "<label class='checkbox'>
									<input type='checkbox' name='permission[]' value='$tab2'/>
									$tabname
								</label>";
						};
					}
				?>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label">Kiểu user</label>
			<div class="controls">
				<label class='radio'>
					<input type="radio" name="role" value="admin"> 
					Admin
				</label>
				
				<label class='radio'>
					<input type="radio" name="role" value="mod"> 
					Mod
				</label>
				
				<label class='radio'>
					<input type="radio" name="role" value="viewer"> 
					Viewer
				</label>
			</div>
		</div>

		<div class="form-actions">
			<button type="submit" class="btn btn-info" name="submit"><i class="icon-plus"></i> Thêm tài khoản</button>
			<span id="submit"></span>
		</div>
	</form>
	
<?php elseif($type=='edit'): ?>
	<?php
		$id = $_REQUEST['id'];
		$res = $db->query("select * from vhs_users where id='$id'");
		$res->fetchInto($row);
	?>
	<form method=POST id='managerform' class="form-horizontal" action='includes/admin/admin_user_edit.php'>
		<div class="control-group">
			<label class="control-label">Username</label>
			<div class="controls">
				<input name="username" class="input-large" value="<?php echo $row['username']?>" type="text" readonly>
				<input name="id" value="<?php echo $id?>" type="hidden">
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label">Mật khẩu</label>
			<div class="controls">
				<input type="password" name="password1" class="input-medium"  placeholder="Mật khẩu"> 
				<input type="password" name="password2" class="input-medium"  placeholder="Nhắc lại mật khẩu">
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label">Số điện thoại</label>
			<div class="controls">
				<input type="text" name="phone" class="input-xlarge" value="<?php echo $row['phone']?>"> 
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label">Quyền truy cập</label>
			<div class="controls">
				<?php
					include('../tab.php');
					$tab2s = explode(',', $_SESSION[$config_session]['permission'] );
					
					foreach($tab2s as $tab2){
					
						foreach($tabs as $tab) if($tab[0] == $tab2){
							$tabname=$tab[1];
							$tabshow=$tab[2];
						}
						if($tabshow==1){
							$permissions = explode(',',$row['permission']);
							$checked = (in_array($tab2,$permissions))?$checked='checked':'';
			
							
							echo "<label class='checkbox'>
									<input type='checkbox' name='permission[]' $checked value='$tab2'/>
									$tabname
								</label>";
						};
					}
				?>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label">Kiểu user</label>
			<div class="controls">
				<label class='radio'>
					<input type="radio" name="role" value="admin" <?=($row['role']=='admin')?"checked":""?>> 
					Admin
				</label>
				
				<label class='radio'>
					<input type="radio" name="role" value="mod" <?=($row['role']=='mod')?"checked":""?>> 
					Mod
				</label>
				
				<label class='radio'>
					<input type="radio" name="role" value="viewer" <?=($row['role']=='viewer')?"checked":""?>> 
					Viewer
				</label>
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
	
	$('.datetimepk').datepicker({
		format: 'yyyy-mm-dd'
	})

	$("input[type=radio], input[type=checkbox]").uniform();
		
	$('.formchange').click(function(){
		loadform('includes/admin/admin_user_form.php?type='+$(this).attr('form'),'#parameter','#loadform');
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