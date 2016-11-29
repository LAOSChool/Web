<?php 
include("config.php"); 

$language = $_REQUEST['language'];
if($language==''){
	header("Location: $dirfile/la/");
}
?>

<!DOCTYPE html>
<html>
<head>
<title><?php echo $config_webtitle ?></title>
<!-- for-mobile-apps -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />


<meta name="description" content="<?php echo $config_description ?>" />
<meta name="keywords" content="<?php echo $config_keywords ?>">

<script type="application/x-javascript"> 
	addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false);
	function hideURLbar(){ window.scrollTo(0,1); } 
</script>
		
<!-- //for-mobile-apps -->
<link href="<?php domain(0)?>/css/bootstrap.css" rel="stylesheet" type="text/css" media="all" />
<link href="<?php domain(0)?>/css/style.css" rel="stylesheet" type="text/css" media="all" />

<!-- js -->
<script src="<?php domain(0)?>/js/jquery-1.11.1.min.js"></script>
<script src="<?php domain(0) ?>/js/jquery.form.js" type="text/javascript"></script>
<!-- //js -->

<!--script-->
<script src="<?php domain(0)?>/js/jquery.chocolat.js"></script>
<link rel="stylesheet" href="<?php domain(0)?>/css/chocolat.css" type="text/css" media="screen" charset="utf-8">
<!--light-box-files-->
<script type="text/javascript" charset="utf-8">
	$(function() {
		$('.gallery-grid a').Chocolat();
	});
</script>
<!--script-->

<!-- start-smoth-scrolling -->
<script type="text/javascript" src="<?php domain(0)?>/js/move-top.js"></script>
<script type="text/javascript" src="<?php domain(0)?>/js/easing.js"></script>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$(".scroll").click(function(event){		
			event.preventDefault();
			$('html,body').animate({scrollTop:$(this.hash).offset().top},1000);
		});
	});
</script>
<!-- start-smoth-scrolling -->
<link href='//fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic,800,800italic' rel='stylesheet' type='text/css'>
<link href='//fonts.googleapis.com/css?family=Questrial' rel='stylesheet' type='text/css'>
<script src='https://www.google.com/recaptcha/api.js?hl=<?php echo ($language=='la')?"lo":"vi" ?>'></script>
</head>
	
<body>
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

<!-- header -->
	<div class="header">
		<div class="container">
			<div class="header-left">
				<a href="<?php domain(1) ?>/"><i class="glyphicon glyphicon-book" aria-hidden="true">
					</i><?php echo $config_webtitle ?><span>
					<?php echo $config_subtitle ?></span>
				</a>
			</div>
			<div class="header-left1">
				<ul>
					<?php if($_SESSION[$config_session]['loggedin']!=true): ?>
						<li>
							<a href="javascript:;" data-toggle="modal" data-target="#myModal">
								<i class="glyphicon glyphicon-log-in"></i> <?php lang('clklogin') ?>
							</a>
						</li>
					<?php endif; ?>
					
					<?php
						if($_SESSION[$config_session]['loggedin']==true): 
							//$_SESSION[$config_session] = "";
							$auth_key = $_SESSION[$config_session]['auth_key'];
							//echo $auth_key;
							$headers = array();
							$headers[] = "auth_key: $auth_key";
							$userdata = callapi($headers,'','','api/users/myprofile');
							$userdatas = explode("\n",$userdata['output']);
							$myprofile = json_decode(end($userdatas));
							//print_r($userdata);
					?>
						<li>
							<a href="<?php domain(1) ?>/admin">
								<i class="glyphicon glyphicon-home"></i> <?php lang('stdmanager') ?>
							</a>
						</li>
						<li>
							
						</li>
					<?php endif; ?>
				</ul>
			</div>
			<div class="header-right">
				<ul>
					<li><a href="<?php echo $config_facebook; ?>" target="_blank" class="facebook"></a></li>
				</ul>
			</div>
			<div class="clearfix"> </div>
		</div>
	</div>
<!-- //header -->

	<?php include("includes/content.php"); ?>

<!-- footer -->
	<!--<div class="footer">	
		<div class="container">
			<div class="footer-grids">
				<div class="col-md-3 footer-grid">
					<h3 class="title">Services</h3>
					<ul>
						<li><a href="#">Rerum hic tenetur</a></li>
						<li><a href="#">Molestiae non recusandae</a></li>
						<li><a href="#">Voluptates repudiandae</a></li>
						<li><a href="#">Necessitatibus saepe</a></li>
						<li><a href="#">Debitis aut rerum</a></li>
					</ul>
				</div>
				<div class="col-md-3 footer-grid">
					<h3 class="title">Information</h3>
					 <ul>
						<li><a href="#">Quibusdam et aut</a></li>
						<li><a href="#">Testimonals</a></li>
						<li><a href="#">Archives</a></li>
						<li><a href="#">Our Staff</a></li>
					</ul>
				</div>
				<div class="col-md-3 footer-grid">
					<h3 class="title">More details</h3>
					<ul>
						<li><a href="#">About us</a></li>
						<li><a href="#">Privacy Policy</a></li>
						<li><a href="#">Terms &amp; Conditions</a></li>
						<li><a href="#">Sitemap</a></li>
					</ul>
				</div>
				<div class="col-md-3 footer-grid contact-grid">
						<h3 class="title">Contact us</h3>
						<ul>
							<li><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>Newyork Still Road.</li>							
							<li class="adrs">756 gt globel Place</li>
							<li><span class="glyphicon glyphicon-earphone" aria-hidden="true"></span>+000 100 444 1111</li>
							<li><span class="glyphicon glyphicon-envelope" aria-hidden="true"></span><a href="mailto:example@mail.com">mail@example.com</a></li>
						</ul>
				</div>
				<div class="clearfix"> </div>
			</div>
		</div>
	</div>-->
	<div class="copy">
		<div class="container">
			<div class="copy-left">
				<p><?php echo $config_footer ?></p>
			</div>
			<div class="copy-left-right">
				<ul>
					<li><a href="<?php echo $config_facebook; ?>" target="_blank" class="facebook"></a></li>
				</ul>	
			</div>
			<div class="clearfix"> </div>
		</div>
	</div>
	
	
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?php lang('logintopanel') ?></h4>
      </div>
		<form method=POST id='loginform' class="form-horizontal" action='<?php domain(0) ?>/includes/admin/login.php?lang=<?php echo $language ?>'>
			<div class="modal-body">
				<ul class="nav nav-tabs" role="tablist">
					<li role="presentation" class="active"><a href="#login" aria-controls="login" role="tab" data-toggle="tab"><?php lang('login') ?></a></li>
					<li role="presentation"><a href="#resetpwd" aria-controls="resetpwd" role="tab" data-toggle="tab"><?php lang('resetpwd') ?></a></li>
				</ul>
				<div class="tab-content">
				
					<div role="tabpanel" class="tab-pane active" id="login">
						<p class='lead'></p>
						<div class="input-group input-group-lg">
						  <span class="input-group-addon"><i class="glyphicon glyphicon-user" aria-hidden="true"></i></span>
						  <input name='sso_id' type="text" class="form-control" placeholder="<?php lang('username') ?>">
						</div>
						<div class="input-group input-group-lg">
							<span class="input-group-addon"><i class="glyphicon glyphicon-eye-close" aria-hidden="true"></i></span>
							<input type="password" name='password' class="form-control" placeholder="<?php lang('password') ?>">
						</div>
					</div>
					
					<div role="tabpanel" class="tab-pane" id="resetpwd">
						<p class='lead'></p>
						<div class="input-group input-group-lg">
						  <span class="input-group-addon"><i class="glyphicon glyphicon-user" aria-hidden="true"></i></span>
						  <input name='rs_sso_id' type="text" class="form-control" placeholder="<?php lang('username') ?>">
						</div>
						
						<div class="input-group input-group-lg">
							<span class="input-group-addon"><i class="glyphicon glyphicon-phone" aria-hidden="true"></i></span>
							<input name='phone' type="text" class="form-control" placeholder="<?php lang('phone') ?>">
						</div>
					</div>
					
					<div class="input-group input-group-lg">
						<span class="input-group-addon"><i class="glyphicon glyphicon-check" aria-hidden="true"></i></span>
						<input type="hidden" name='actype' id='actype' value='login'>
						<div id='g-recaptcha' class="g-recaptcha" data-sitekey="6Le-RA0UAAAAAMN2onx_Ea7r2ayR5AAB9iQnHRMl"></div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<div id='loginsubmit'><div class="alert alert-info"><?php lang('loginhint') ?></div></div>
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php lang('close') ?></button>
				<button type="submit" name='submit' class="btn btn-primary"><i class="glyphicon glyphicon-log-in"></i> Submit</button>
			</div>
		</form>

		<script language='javascript'>
			$('#loginform').ajaxForm({
				target: '#loginsubmit', 
				beforeSubmit: function(){
					$('#loginsubmit').show().html('<div class="alert alert-warning"><?php lang('pleasewait') ?></div>'); 
				},
				success: function() { 
					;
				}
			});

			$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
				var str = e.target;
				$('#actype').val(str.hash.substr(1));
			})
		</script>
    </div>
  </div>
</div>

	<script src="<?php domain(0) ?>/js/bootstrap.js"> </script>

	<script type="text/javascript">
		$(document).ready(function() {
			/*
				var defaults = {
				containerID: 'toTop', // fading element id
				containerHoverID: 'toTopHover', // fading element hover id
				scrollSpeed: 1200,
				easingType: 'linear' 
				};
			*/
								
			$().UItoTop({ easingType: 'easeOutQuart' });
								
			});
	</script>

</body>
</html>