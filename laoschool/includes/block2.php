<?php

$bl2res = $dbp->query("select * from vhs_news where caid=2 and lang='$language'");
$i=0;
while($bl2res->fetchInto($bl2row)){
	$bl2row['detail'] = truncate_str(strip_tags($bl2row['detail']),100);
	$bl2subcontent .=<<<eot
	<div class="col-md-6 latest-news-grid">
		<div class="col-xs-4 latest-news-grid-left">
			<img src="$dirfile/images/$bl2row[image]" alt="$bl2row[title]" class="img-responsive" />
		</div>
		<div class="col-xs-8 latest-news-grid-right">
			<p><i class="glyphicon glyphicon-calendar" aria-hidden="true"></i><span>$bl2row[datetime]</span></p>
			<h4>$bl2row[title]</h4>
			<p class="man">$bl2row[detail]</p>
		</div>
		<div class="clearfix"> </div>
	</div>
eot;
	$i++;
	if($i==2){
		$bl2content .=<<<eot
			<div class="latest-news-grids">
				$bl2subcontent
				<div class="clearfix"> </div>
			</div>
eot;
		$i=0;
		$bl2subcontent = ""; 
	}
}
echo<<<eot
	<div class="news" id="news">
		<div class="container">
			<h3>$lang_news</h3>
			<div class="latest-news">
				$bl2content
			</div>
		</div>
	</div>
eot;
?>