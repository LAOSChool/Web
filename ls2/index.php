<?php
include("config.php");?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
	<meta charset="utf-8" />
	<title><?php echo $config_webtitle ?></title>
	<meta content="width=device-width, initial-scale=1.0" name="viewport" />
	<meta content="" name="description" />
	<meta content="" name="author" />
	
	<link rel="stylesheet" type="text/css" href="assets/bootstrap/css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="assets/bootstrap/css/bootstrap-responsive.min.css" />
	<link rel="stylesheet" type="text/css" href="assets/bootstrap-datepicker/css/datepicker.css" />
    <link rel="stylesheet" type="text/css" href="assets/bootstrap-timepicker/compiled/timepicker.css" />
	<link rel="stylesheet" type="text/css" href="assets/chosen-bootstrap/chosen/chosen.css" />
	<link rel="stylesheet" type="text/css" href="assets/font-awesome/css/font-awesome.css" />
    <link rel="stylesheet" type="text/css" href="assets/uniform/css/uniform.default.css" />
	<link rel="stylesheet" type="text/css" href="assets/nestable/jquery.nestable.css" />
	<link rel="stylesheet" type="text/css" href="assets/data-tables/DT_bootstrap.css" />
	<link rel="stylesheet" type="text/css" href="css/style.css" />
	<link rel="stylesheet" type="text/css" href="css/style-responsive.css" />
	<link rel="stylesheet" type="text/css" href="css/style-default.css" id="style_color" />
	<link rel="stylesheet" type="text/css" href="assets/bootstrap/css/bootstrap-fileupload.css" />
	
	<script src="js/jquery-1.8.3.min.js"></script>
	<script src="js/jquery.nicescroll.js" type="text/javascript"></script>
	<script src="js/jquery.scrollto-min.js" type="text/javascript"></script>
	<script src="js/jquery.form.min.js"></script>
	
	<script type="text/javascript" src="assets/jquery-slimscroll/jquery-ui-1.9.2.custom.min.js"></script>
	<script type="text/javascript" src="assets/jquery-slimscroll/jquery.slimscroll.min.js"></script>
	<script type="text/javascript" src="assets/fullcalendar/fullcalendar/fullcalendar.min.js"></script>
	<script type="text/javascript" src="assets/flot/jquery.flot.js"></script>
	<script type="text/javascript" src="assets/flot/jquery.flot.resize.js"></script>
	<script type="text/javascript" src="assets/flot/jquery.flot.pie.js"></script>
	<script type="text/javascript" src="assets/flot/jquery.flot.stack.js"></script>
	<script type="text/javascript" src="assets/flot/jquery.flot.crosshair.js"></script>
	<script type="text/javascript" src="assets/jquery-file-upload/jquery.fileupload.js"></script>
	<script type="text/javascript" src="assets/uniform/jquery.uniform.min.js"></script>
	<script type="text/javascript" src="assets/bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="assets/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
	<script type="text/javascript" src="assets/bootstrap-daterangepicker/date.js"></script>
	<script type="text/javascript" src="assets/bootstrap-daterangepicker/daterangepicker.js"></script>
	<script type="text/javascript" src="assets/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>
	<script type="text/javascript" src="assets/chosen-bootstrap/chosen/chosen.jquery.min.js"></script>
	<script type="text/javascript" src="assets/nestable/jquery.nestable.js"></script>
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<link rel="icon" href="favicon.ico" type="image/x-icon">
	
	<script type="text/javascript">
		function loadform(x,y,z){
			
			if(z===undefined){
				$('#loadfix').show().html('<img src="img/loading.gif">');
				$(y).load(x,function(){
					$('#loadfix').hide();
					$(y).effect('highlight', {}, 1000);
				});
			}else{
				$(z).show().html('<img src="img/loading.gif">');
				$(y).load(x,function() {
					$(z).hide().html('');
				});
			}
		};
	</script>
</head>
<body class="fixed-top">

<div id="loadfix" style="position:fixed;top:0px;right:0px;padding:10px;background:black;opacity:0.8;filter:alpha(opacity=80);"></div>
<script type="text/javascript">$('#loadfix').hide();</script>

<?php

	if($_SESSION[$config_session]['loggedin'] == 'true' ){
		$logged = $_SESSION[$config_session]['loggedin'];
		$auth_key = $_SESSION[$config_session]['auth_key'];
		$permissionx = "*";
		$rolex = "admin";
		
		$headers = array();
		$headers[] = "auth_key: $auth_key";
		$headers[] = "api_key: TEST_API_KEY";
		$userdata = callapi($headers,'','','api/users/myprofile');
		$userdatas = explode("\n",$userdata['output']);
		
		$myprofile = json_decode(end($userdatas));
		
		if($userdata['http_code']!==200) echo "<script>window.location='index.php?act=login&lang=$lang';</script>";
	}else $logged = '';

	if($logged!='true'){
		include('includes/login/login.php');
		exit();
	}
	
?>

<?php
	if(isset($_REQUEST['act'])){
		$act = $_REQUEST['act'];
	}else{
		if($permissionx=='*') $tabs2[0] = $tabs[0][0];
		else $tabs2 = explode(',', $permissionx);
		$act = $tabs2[0];
	};
	
	if(isset($_REQUEST['go'])){
		$go = $_REQUEST['go'];
	}else{
		$gotab = $act."_tabs";
		@$firstsubtab = array_shift(array_values($$gotab));
		if(is_array($firstsubtab)) $go = $firstsubtab[0];
	}
	
?>
<div id="header" class="navbar navbar-inverse navbar-fixed-top">

	<div class="navbar-inner">
		<div class="container-fluid">
			<div class="sidebar-toggle-box hidden-phone">
			   <div class="icon-reorder"></div>
			</div>

			<a class="brand" href="index.php">
			   <img src="img/logo.png" alt="<?php echo $config_webtitle ?>" /> 
			   <span><?php echo $config_webtitle; ?></span>
			</a>

			<a class="btn btn-navbar collapsed" id="main_menu_trigger" data-toggle="collapse" data-target=".nav-collapse">
			   <span class="icon-bar"></span>
			   <span class="icon-bar"></span>
			   <span class="icon-bar"></span>
			   <span class="arrow"></span>
			</a>
			 <div id="top_menu" class="nav notify-row">
				<ul class="nav top-menu">
					<li class="<?php echo ($lang=='la')?"open":"" ?> dropdown">
						<a href="index.php?lang=la" class="dropdown-toggle">
							<i class="icon-book"></i>
							Laos
						</a>
					</li>
					
					<li class="<?php echo ($lang=='en')?"open":"" ?> dropdown">
						<a href="index.php?lang=en" class="dropdown-toggle">
							<i class="icon-book"></i>
							English
						</a>
					</li>
				</ul>
			 </div>
			<div class="top-nav ">
				<ul class="nav pull-right top-menu" >
					<?php if($rolex=='sadmin'): ?> 
						
					<?php endif; ?>
					
					<?php if($rolex=='admin'): ?> 
					
					<?php endif; ?>
					
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<img src="<?php echo $myprofile->photo ?>" alt="" width=30 height=30>
							<span class="username"><?php echo strtoupper($myprofile->sso_id); ?></span>
							
						</a>
						
						<ul class="dropdown-menu extended logout">
							<li><a href="index.php?act=login&logout=1"><i class="icon-signout"></i> Log Out</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>

<div id="container" class="row-fluid">
	<div class="sidebar-scroll">
		<div id="sidebar" class="nav-collapse collapse">
			<div class="navbar-inverse">
				<form class="navbar-search visible-phone">
				   <input type="text" class="search-query" placeholder="Search" />
				</form>
			</div>
			<ul class="sidebar-menu">
				<?php
					$tabs2 = explode(',', $permissionx);
					foreach($tabs as $tab){
						if($tab[2]==0) continue;
						
						$checked = '';
						foreach($tabs2 as $tab2){
							if($tab2 == $tab[0]) $checked = 'checked';
						}
						
						if($checked == 'checked' || $permissionx=='*'){
							$current_page_item = ($act == $tab[0])?'active':"";
							if($permissionx=='*' && $tab[2]==='0'){
								;
							}else{
								$subcontent = '';
								$subtab = "$tab[0]_tabs";
								if(is_array($$subtab) && count($$subtab)>1){
									foreach($$subtab as $sub){
										$current_sub_page_item = ($act == $tab[0] && $go==$sub[0])?'active':"";
										$subcontent.="<li class='$current_sub_page_item'><a class='' href='?act=$tab[0]&go=$sub[0]&lang=$lang'>$sub[1]</a></li>\n";
									};
									
									echo "<li class='sub-menu $current_page_item'>
										<a href='javascript:;' class=''>
											<i class='$tab[3]'></i>
											<span>$tab[1]</span>
											<span class='arrow'></span>
										</a>
										<ul class='sub'>
											$subcontent
										</ul>
									</li>";
								}else{
									echo "
									<li class='sub-menu $current_page_item'>
										<a class='' href='?act=$tab[0]&lang=$lang'>
											<i class='$tab[3]'></i> 
											<span>$tab[1]</span>
										</a>
									</li>";
								}
							}
						}
					}
				?>
			</ul>
		</div>
	</div>
	<div id="main-content">
		<?php 
			$tabs2 = explode(',', $permissionx);
			$checked = '';
			foreach($tabs2 as $tab2) if($tab2 == $act || $tab2=='*') $checked = 'checked';
			if($rolex=='sadmin' && $act=='account') $checked = 'checked';
			if($rolex=='admin' && $act=='admin') $checked = 'checked';
			if($act=='acc') $checked = 'checked';
			
			if($checked == 'checked' || $act=='home' || $act=='login'){
				include("includes/content.php");
			}else{
				include("includes/404.php");
			}			
		?>
	</div>
</div>

<div id="footer">
	<?php echo date('Y') ?> &copy; ITPRO.
</div>

<!-- ie8 fixes -->
<!--[if lt IE 9]>
<script src="js/excanvas.js"></script>
<script src="js/respond.js"></script>
<![endif]-->


<script src="js/common-scripts.js"></script>
<script src="js/form-component.js"></script>
</body>
</html>