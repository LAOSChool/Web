<?php

$sres = $dbp->query("select * from `vhs_banner` where lang='$language' order by `datetime` desc");
$i=0;
while($sres->fetchInto($srow)){
	$bl3subcontent.=<<<eot
		<div class="gallery-grid1">
			<figure class="effect-bubba">
				<a href="$dirfile/images/$srow[image]" rel="title" class="b-link-stripe b-animate-go  thickbox">
					<img src="$dirfile/images/$srow[image]" alt="$srow[name]" class="img-responsive" />
					<figcaption>
						<h4>$srow[name]</h4>
						<p>$srow[description]</p>	
					</figcaption>
				</a>
			</figure>
		</div>
eot;
	$i++;
	if($i==2){
		$bl3content .=<<<eot
			<div class="gallery-grid">
				$bl3subcontent
			</div>
eot;
		$i=0;
		$bl3subcontent = ""; 
	}
}
	
	
echo<<<eot
	<div id="gallery" class="gallery">
		<div class="container">
			<h3>$lang_album</h3>
			<div class="gallery-grids">
				$bl3content
				<div class="clearfix"> </div>
			</div>
		</div>
	</div>
eot;
?>