<?php
	if($_REQUEST['type']=='admin'){
		$act = $_REQUEST['act'];
		$type = $_REQUEST['type'];
		if($act=='') $act='home';
?>
		<div class="header-nav">
			<div class="container">
				<div class="header-nav-bottom">
					<nav class="navbar navbar-default">
						<div class="navbar-header">
						  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
							<span class="sr-only">Toggle navigation</span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
							<span class="icon-bar"></span>
						  </button>
						</div>

						<div class="collapse navbar-collapse nav-wil" id="bs-example-navbar-collapse-1">
							<ul class="nav navbar-nav">
								<!--<li <?php echo ($act=='home')?"class='active'":"" ?>><a href="<?php domain(1) ?>/admin/home"><?php lang('home') ?></a></li>-->
								<li <?php echo ($act=='msg')?"class='active'":"" ?>><a href="<?php domain(1) ?>/admin/msg"><?php lang('msg') ?></a></li>
								<li <?php echo ($act=='ntf')?"class='active'":"" ?>><a href="<?php domain(1) ?>/admin/ntf"><?php lang('ntf') ?></a></li>
								<li <?php echo ($act=='ttb')?"class='active'":"" ?>><a href="<?php domain(1) ?>/admin/ttb"><?php lang('ttb') ?></a></li>
								<li><a href="<?php domain(0) ?>/includes/admin/login.php?logout&language=<?php echo $language; ?>"><?php lang('logout') ?></a></li>
							</ul>
						</div>
					</nav>
				</div>
			</div>
		</div>
<?php
		if(file_exists("includes/admin/$act/$act.php")) 
			include("includes/admin/$act/$act.php");
	}else{
?>
			<div class="banner">
				<div class="container"> 
					<div class="banner-info">
						<h1><?php echo $config_welcome ?></h1>
						<p><?php echo $config_subwelcome ?></p>
						<ul class="social-icons">
							<li><a href="<?php echo $config_facebook; ?>" target="_blank" class="facebook"></a></li>
							<li><a href="<?php echo $config_twitter; ?>" target="_blank" class="twitter"></a></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="header-nav">
				<div class="container">
					<div class="header-nav-bottom">
						<nav class="navbar navbar-default">
							<div class="navbar-header">
							  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							  </button>
							</div>

							<div class="collapse navbar-collapse nav-wil" id="bs-example-navbar-collapse-1">
							 <ul class="nav navbar-nav">
								<?php include("includes/menu.php"); ?>
							  </ul>
							</div>
						</nav>
					</div>
				</div>
			</div>
<?php
		$cmres = $dbp->query("select * from `vhs_menu` where lang='$language' order by `order` asc");
		while($cmres->fetchInto($cmrow)){
			include("includes/$cmrow[type].php");
		}
	}
	
?>