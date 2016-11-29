<?php
	foreach($tabs as $tab) if($tab[0]==$act){
		$currenttab = $tab[1];
		$param_icon = $tab[3];
	}
	
	//$subtablang = 
	
	$subtabs = $act."_tabs_$lang";

	if(is_array($$subtabs)){
		foreach($$subtabs as $subtab) if($subtab[0]==$go) $currentsubtab = $subtab[1];
	}else{
		$currentsubtab = $lang_pagenotfound;
	};
?>
<div class="container-fluid"> 
	<div class="row-fluid">
		<div class="span12">
			<h3 class="page-title"><?php echo $currenttab ?></h3>
			<ul class="breadcrumb">
				<li><a href="index.php"><?php lang('home') ?></a>
				<span class="divider">/</span></li>
				<li><a href="index.php?act=<?php echo $act; ?>"><?php echo $currenttab ?></a>
				<span class="divider">/</span></li>
				<li class="active"><?php echo $currentsubtab ?></li>
			</ul>
		</div>
	</div>
	<div class="row-fluid">
		<?php
			if(file_exists("includes/$act/{$act}_$go.php")) include("includes/$act/{$act}_$go.php");
			//echo "<script>alert('includes/$act/{$act}_$go.php')</script>";
			elseif(file_exists("includes/$act/$act.php")) 
				include("includes/$act/$act.php");
				//echo "<script>alert('includes/$act/$act.php')</script>";
			else include("includes/404.php");
		?>
	</div>
</div>