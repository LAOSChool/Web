<?php

	$sres = $dbp->query("select * from `vhs_banner` order by `order` asc ");
	while($sres->fetchInto($srow)){
		echo " <div class='img_slide'><img src='$dirfile/images/$srow[image]' title='$srow[name]' alt='$srow[name]'></div>\n";
	}
?>