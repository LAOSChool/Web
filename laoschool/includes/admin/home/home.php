<?php
	$headers = array();
	$auth_key = $_SESSION[$config_session]['auth_key'];
	$headers[] = "auth_key: $auth_key";
	$userdata = callapi($headers,'','','api/users/myprofile');
	$userdatas = explode("\n",$userdata['output']);
	$myprofile = json_decode(end($userdatas));

	$infdata = callapi($headers,'','',"api/schools/$myprofile->school_id");
	$infdatas = explode("\n",$infdata['output']);
	$schooldata = json_decode(end($infdatas));

	$prvdata = callapi($headers,'','',"api/sys/sys_province");
	$prvdatas = explode("\n",$prvdata['output']);
	$provincedata = json_decode(end($prvdatas));
	
	$dgrdata = callapi($headers,'','',"api/sys/sys_degree");
	$dgrdatas = explode("\n",$dgrdata['output']);
	$degreedata = json_decode(end($dgrdatas));
	
	$disdata = callapi($headers,'','',"api/sys/sys_dist");
	$disdatas = explode("\n",$disdata['output']);
	$distdata = json_decode(end($disdatas));
	
	foreach($degreedata->messageObject->list as $list){
		$schooldata->degreetext = ($list->id == $schooldata->degree)?$list->sval:"";
		break;
	}
?>

<div class="about" id="about">
	<div class="container">
		<div class="about-grids">
			<div class="col-md-6 about-grid-left">
				<img src="http://dietmoithanglong.com/wp-content/uploads/2016/08/truonghoc.jpg" alt=" " class="img-responsive" />
			</div>
			<div class="col-md-6 about-grid-right">
				<h3><?php echo $schooldata->title ?></h3>
				
				<p><b><?php lang('principal') ?></b>: <?php echo $schooldata->principal; ?></p>
				<p><b><?php lang('address') ?></b>: <?php echo "$schooldata->addr1" ?> asd à sdfg sdfg sdfg sdfg sdfg sdfg sdfg dfg sdfg sdfg </p>
				<P><b><?php lang('phonnfax') ?></b>: <?php echo "$schooldata->phone" ?></p> 
				<p><b><?php lang('website') ?></b>: <a href="<?php echo "$schooldata->url" ?>"><?php echo "$schooldata->url" ?></a></p>
			</div>
			<div class="clearfix"> </div>
		</div>
	</div>
</div>

<div id="contact" class="contact">
</div>