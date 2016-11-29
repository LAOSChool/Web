<?php
	include('../../config.php');
	
	ensure_permission('sadmin');
	ensure_role('sadmin');
	
	$type = $_REQUEST['type'];
	$time = time();
?>

<div class="bs-docs-example">
	<div class="navbar navbar-static">
		<div class="navbar-inner">
			<div style="width: auto;" class="container">
				<a href="javascript:;" class="brand">User manager</a>
				<ul class="nav">
					<li <?php echo ($type=='search' || $type == '')?"class='active'":"" ?>><a href="javascript:;" class='formchange' form="search"><i class="icon-search"></i> Search</a></li>
					<li <?php echo ($type=='add')?"class='active'":"" ?>><a href="javascript:;" class='formchange' form="add"><i class="icon-plus"></i> Add User</a></li>
					<li><a href="javascript:;" id='loadform'></a></li>
				</ul>
				
			</div>
		</div>
	</div>
</div>

<?php if($type=='search' || $type == ''): ?>

	<form method=POST id='searchform' class="form-horizontal" action='includes/sadmin/sadmin_user_db.php'>
		<div class="control-group">
			<label class="control-label">User name</label>
			<div class="controls">
				<input type="text" name="name" value="" class="input-xlarge"/> 
				<button type="submit" class="btn btn-info" name="submit"><i class="icon-search"></i> Search</button>
				<span id='searchtext'></span>
			</div>
		</div>
	</form>

<?php elseif($type=='add'): ?>
	<form method=POST id='managerform' class="form-horizontal" action='includes/sadmin/sadmin_user_add.php'>	
		<div class="control-group">
			<label class="control-label">Username</label>
			<div class="controls">
				<input name="username" class="input-large" value="" type="text">
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label">Password</label>
			<div class="controls">
				<input type="password" name="password1" class="input-medium"  placeholder="Password"> 
				<input type="password" name="password2" class="input-medium"  placeholder="Re-type password">
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label">Phone</label>
			<div class="controls">
				<input type="text" name="phone" class="input-xlarge"> 
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label">Permission</label>
			<div class="controls">
				<?php
					include('../tab.php');
					$i=1;
					foreach($tabs as $tab){
						if($tab[2]==1){
							echo "<label class='checkbox'>
									<input type='checkbox' name='permission[]' value='$tab[0]'/>
									$tab[1]
								</label>";
						};
						$i++;
					}
				?>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label">User type</label>
			<div class="controls">
				<label class='radio'>
					<input type="radio" name="role" value="sadmin"> 
					System user
				</label>
				
				<label class='radio'>
					<input type="radio" name="role" value="admin"> 
					Admin
				</label>
				
				<label class='radio'>
					<input type="radio" name="role" value="mod"> 
					Manager
				</label>
				
				<label class='radio'>
					<input type="radio" name="role" value="viewer"> 
					Reporter
				</label>
			</div>
		</div>

		<div class="form-actions">
			<button type="submit" class="btn btn-info" name="submit"><i class="icon-plus"></i> Add User</button>
			<span id="submit"></span>
		</div>
	</form>
	
<?php elseif($type=='edit'): ?>
	<?php
		$id = $_REQUEST['id'];
		$res = $db->query("select * from users where id='$id'");
		$res->fetchInto($row);
	?>
	<form method=POST id='managerform' class="form-horizontal" action='includes/sadmin/sadmin_user_edit.php'>
		<div class="control-group">
			<label class="control-label">Username</label>
			<div class="controls">
				<input name="username" class="input-large" value="<?php echo $row['username']?>" type="text" readonly>
				<input name="id" value="<?php echo $id?>" type="hidden">
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label">Password</label>
			<div class="controls">
				<input type="password" name="password1" class="input-medium"  placeholder="Password"> 
				<input type="password" name="password2" class="input-medium"  placeholder="Re-type password">
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label">Phone</label>
			<div class="controls">
				<input type="text" name="phone" class="input-xlarge" value="<?php echo $row['phone']?>"> 
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label">Permission</label>
			<div class="controls">
				<?php
					include('../tab.php');
					$i=1;
					foreach($tabs as $tab){
						if($tab[2]==1){
							$permissions = explode(',',$row['permission']);
							if(in_array($tab[0],$permissions)) $checked='checked';
							else $checked = '';
							
							echo "<label class='checkbox'>
									<input $checked type='checkbox' name='permission[]' value='$tab[0]'/>
									$tab[1]
								</label>";
						};
						$i++;
					}
				?>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label">User type</label>
			<div class="controls">
				<label class='radio'>
					<input type="radio" name="role" value="sadmin" <?=($row['role']=='sadmin')?"checked":""?>> 
					System user
				</label>
				
				<label class='radio'>
					<input type="radio" name="role" value="admin" <?=($row['role']=='admin')?"checked":""?>> 
					Admin
				</label>

				<label class='radio'>
					<input type="radio" name="role" value="mod" <?=($row['role']=='mod')?"checked":""?>> 
					Manager
				</label>
				
				<label class='radio'>
					<input type="radio" name="role" value="viewer" <?=($row['role']=='viewer')?"checked":""?>> 
					Reporter
				</label>
			</div>
		</div>
		
		<div class="form-actions">
			<button type="submit" class="btn btn-info" name="submit"><i class="icon-edit"></i> Edit User</button>
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
		loadform('includes/sadmin/sadmin_user_form.php?lang=<?php echo $lang ?>&type='+$(this).attr('form'),'#parameter','#loadform');
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
			$('#submit').show().html('<span class="badge badge-warning"><i class="icon-spinner"></i> <?php lang('pleasewait') ?>....</span>'); 
		},
		success: function() { 
			;
		}
	}); 	
</script>